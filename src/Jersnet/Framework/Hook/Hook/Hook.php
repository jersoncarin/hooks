<?php

/*****************************************************************
* MIT License
* -----------
*
* Copyright (c) 2021 Jerson Carin (https://jersnetcms.com)
* Permission is hereby granted, free of charge, to any person
* obtaining a copy of this software and associated documentation
* files (the "Software"), to deal in the Software without
* restriction, including without limitation the rights to use,
* copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the
* Software is furnished to do so, subject to the following
* conditions:

* The above copyright notice and this permission notice shall be
* included in all copies or substantial portions of the Software.

* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
* EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
* OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
* NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
* HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
* WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
* FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
* OTHER DEALINGS IN THE SOFTWARE.
*
****************************************************************/

// Main Hooks API

namespace Jersnet\Framework\Hook\Hook;

use Jersnet\Framework\Hook\Interface\HookInterface;

class Hook implements HookInterface {

    /**
     * Hooks array list
     * 
     * @var array
     */
    private static array $hooks = [];

    /**
     * Add a filter into hooks
     * 
     * @param string $tag Identifier name of hooks
     * @param mixed $object (Function,Method to be add into hook)
     * @param int $priority The Highest Priority will be executed first
     * @param int $args_limit (This will accept the args according to the limit)
     * 
     * @return void
     */
    public function add_filter(string $tag,mixed $object,int $priority = 10,int $args_limit = 1): void
    {
        $hooks = static::$hooks;

        if(isset($hooks[$tag])) {
            static::$hooks[$tag]['sorted'] = false;
            static::$hooks[$tag]['priority'][] = $priority;
            static::$hooks[$tag]['object'][] = $object;
            static::$hooks[$tag]['args_limit'][] = $args_limit;
        } else {
            static::$hooks[$tag] = [
                'sorted' => true,
                'priority' => [$priority],
                'object' => [$object],
                'args_limit' => [$args_limit]
            ];
        }
    }

   /**
     * Remove a filter from the hooks
     * 
     * @param string $tag Identifier name of hooks
     * @param mixed $object (Function,Method to be add into hook)
     * @param int $priority The Highest Priority will be executed first
     * 
     * @return bool
     */
    public function remove_filter(string $tag,mixed $object,int $priority = 10): bool
    {
        $hooks = static::$hooks;

        if(isset($hooks[$tag])) {
            $hooks = $hooks[$tag];
            foreach($hooks['object'] as $key => $value) {
              if($object === $value && $hooks['priority'] === $priority) {
                  unset(static::$hooks[$tag]['object'][$key]);
                  unset(static::$hooks[$tag]['priority'][$key]);
                  unset(static::$hooks[$tag]['args_limit'][$key]);
                  return true;
              }
            }
        }

        return false;
    }

    /**
     * Apply filter from the hooks to a callbacks
     * 
     * @param string $tag Identifier name of hooks
     * @param mixed $value
     * @param mixed $args (Additional Arguments to be passed)
     * 
     * @return mixed
     */
    public function apply_filters(string $tag,mixed $value,mixed ...$args): mixed
    {
        $hooks = static::$hooks;
        // +1 for $value parameter
        $num_args = count($args) + 1;

        if(isset($hooks[$tag])) {
            $hooks = $hooks[$tag];
            if(!$hooks['sorted']) {
                // Sort filter by priority
                array_multisort(
                    $hooks['priority'], 
                    SORT_NUMERIC, 
                    $hooks['object'],
                    $hooks['args_limit']
                );

                // Sorted already!
                $hooks['sorted'] = true;
            }

            foreach($hooks['object'] as $key => $object) {
                $args_limit = $hooks['args_limit'][$key];
                
                if(0 === $args_limit) {
                    $value = call_user_func($object);
                } elseif($args_limit >= $num_args) {
                    $value = call_user_func($object,$value,...$args);
                } else {
                    // Slice arguments if not meet from the second statement
                    $slice = array_slice($args,0,$args_limit);
                    $value = call_user_func($object,$value,...$slice);
                }
            }
        }

        return $value;
    }

    /**
     * Calls the callback from the hooks
     * 
     * @param string $tag Identifier name of hooks
     * @param mixed $args (Additional Arguments to be passed)
     * 
     * @return void
     */
    public function do_action(string $tag,mixed ...$args): mixed
    {
        $hooks = static::$hooks;
        // +1 for $value parameter
        $num_args = count($args);

        if(isset($hooks[$tag])) {
            $hooks = $hooks[$tag];
            if(!$hooks['sorted']) {
                // Sort filter by priority
                array_multisort(
                    $hooks['priority'], 
                    SORT_NUMERIC, 
                    $hooks['object'],
                    $hooks['args_limit']
                );

                // Sorted already!
                $hooks['sorted'] = true;
            }

            foreach($hooks['object'] as $key => $object) {
                $args_limit = $hooks['args_limit'][$key];
                
                if(0 === $args_limit) {
                    call_user_func($object);
                } elseif($args_limit >= $num_args) {
                    call_user_func_array($object,$args);
                } else {
                    // Slice arguments if not meet from the second statement
                    call_user_func_array($object,array_slice($args,0,$args_limit));
                }
            }
        }
    }
    

    /**
    * Checks if any hooks registered
    *
    *
    * @return bool True if any hooks registered, otherwise false
    */
    public function has_filters() {
	return static::$hooks !== [];
    }


     /**
     * Check if the hook is registered as filter
     * 
     * @param string $tag
     * @param callable $callable_object
     * 
     * @return bool
     */
    public function has_filter(string $tag, callable $callable = null): bool
    {
        if(!isset(static::$hooks[$tag])) {
            return false;
        }

        if($callable === null) {
            return true;
        }

        foreach(static::$hooks[$tag]['object'] as $key => $value) {
            if($value === $callable) {
                return !isset(static::$hooks[$tag]['object'][$key])
                    && !isset(static::$hooks[$tag]['priority'][$key])
                    && !isset(static::$hooks[$tag]['args_limit'][$key]);
            }
        }

        return false;
    }
}
