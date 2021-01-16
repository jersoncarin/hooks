# Hooks API for PHP

Hooks is a hooking system which like wordpress hooking

  - apply_filters
  - add_filter
  - remove_filter
  - add_action
  - do_action
  - remove_action

# Requirements

  - PHP 8.0+


# Installation
```php
composer require jerson/hooks
```
# How to use
```php
$hooks = new \Jersnet\Framework\Hook\Hooks;

// adding action to the hooks
// Note: it can be a closure, a function and a class method
// add_action(string $tag,mixed $object,int $priority,int $args_limit)
$hooks->add_action('action_name',function(){
    // some code
});

// Firing an action
// do_action(string $tag,...$args);
// you can pass as many as you can arguments
$hooks->do_action('action_name');

// Removing an action
// remove_action(string $tag,callable $callable,int $priority = 10)
// Third argument is optional
$hooks->remove_action('action_name','some_function');

// Has action(s)
// Returns boolean
$hooks->has_action('action_name','some_function');
$hooks->has_actions();

// adding filter to the hooks
// Note: it can be a closure, a function and a class method
// add_filter(string $tag,mixed $object,int $priority,int $args_limit)
$hooks->add_filter('filter_name',function($arg){
    // some code
});

// Removing an filter
// remove_filter(string $tag,callable $callable,int $priority = 10)
// Third argument is optional
$hooks->remove_filter('filter_name','some_function');

// Has filter(s)
// Returns boolean
$hooks->has_filter('action_name','some_function');
$hooks->has_filters();

// Applying Filters
// Apply filters to a hook
// apply_filters(string $tag,mixed $value,mixed ...$args)
// Return mixed
$hooks->apply_filters('filter_name','some_filter','extra_filer');
```
# Author
  - Jerson Carin
 
# License
   - MIT License

