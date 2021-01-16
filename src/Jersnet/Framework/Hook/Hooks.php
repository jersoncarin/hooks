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

// Hooks API

namespace Jersnet\Framework\Hook;

use Jersnet\Framework\Hook\Hook\Hook;

class Hooks extends Hook {

    /**
     * Add a action into hooks
     * 
     * @param string $tag Identifier name of hooks
     * @param mixed $object (Function,Method to be add into hook)
     * @param int $priority The Highest Priority will be executed first
     * @param int $args_limit (This will accept the args according to the limit)
     * 
     * @return void
     */
    public function add_action(string $tag,mixed $object,int $priority = 10,int $args_limit = 1): void
    {
        $this->add_filter($tag,$object,$priority,$args_limit);
    }

    /**
     * Remove action from the hooks
     * 
     * @param string $tag Identifier name of hooks
     * @param mixed $object (Function,Method to be add into hook)
     * @param int $priority The Highest Priority will be executed first
     * 
     * @return bool
     */
    public function remove_action(string $tag,mixed $object,int $priority = 10): bool
    {
       return $this->remove_filter($tag,$object,$priority);
    }

    /**
	 * Checks if any hooks registered
	 *
	 *
	 * @return bool True if any hooks registered, otherwise false
	 */
	public function has_actions() {
		return $this->has_filters();
    }
    
    /**
     * Check if the hook is registered as action
     * 
     * @param string $tag
     * @param callable $callable_object
     * 
     * @return bool
     */
    public function has_action(string $tag, callable $callable = null): bool
    {
        return $this->has_filter($tag,$callable);
    }

    /**
     * Reference Array
     * 
     * Same as above but argument pass by array
     */

    /**
     * Apply Filters to a hook Ref array
     * @param string $tag
     * @param array $args
     * 
     * @return mixed
     */
    public function apply_filters_array(string $tag, array $args): mixed
    {
        return $this->apply_filters($tag,...$args);
    }

    /**
     * Remove action from the hooks Ref array
     * 
     * @param string $tag Identifier name of hooks
     * @param array $args
     * 
     * @return bool
     */
    public function remove_filter_array(string $tag,array $args): bool
    {
        return $this->remove_filter($tag,...$args);
    }

    /**
     * Add filters to a hook Ref array
     * @param string $tag
     * @param array $args
     * 
     * @return void
     */
    public function add_filters_array(string $tag, array $args): void
    {
        $this->add_filters($tag,...$args);
    }

    /**
     * Remove action from the hooks Ref array
     * 
     * @param string $tag Identifier name of hooks
     * @param array $args
     * 
     * @return bool
     */
    public function remove_action_array(string $tag,array $args): bool
    {
        return $this->remove_action($tag,...$args);
    }

    /**
     * Add action to a hook Ref array
     * @param string $tag
     * @param array $args
     * 
     * @return void
     */
    public function add_action_array(string $tag, array $args): void
    {
        $this->add_action($tag,...$args);
    }

    /**
     * Do action Ref array
     * 
     * @param string $tag
     * @param array $args
     * 
     * @return void
     */
    public function do_action_array(string $tag,array $args): void
    {
        $this->do_action($tag,...$args);
    }
}