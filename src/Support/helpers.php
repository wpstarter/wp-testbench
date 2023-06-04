<?php
/**
 * Adds hooks before loading WP.
 *
 * @since UT (3.7.0)
 *
 * @see add_filter()
 * @global WP_Hook[] $wp_filter A multidimensional array of all hooks and the callbacks hooked to them.
 *
 * @param string   $hook_name     The name of the filter to add the callback to.
 * @param callable $callback      The callback to be run when the filter is applied.
 * @param int      $priority      Optional. Used to specify the order in which the functions
 *                                associated with a particular action are executed.
 *                                Lower numbers correspond with earlier execution,
 *                                and functions with the same priority are executed
 *                                in the order in which they were added to the action. Default 10.
 * @param int      $accepted_args Optional. The number of arguments the function accepts. Default 1.
 * @return true Always returns true.
 */
function tests_add_filter( $hook_name, $callback, $priority = 10, $accepted_args = 1 ) {
    return \WpStarter\WpTestbench\Support\Hook::addFilter($hook_name,$callback,$priority,$accepted_args);
}

/**
 * Load plugin to test at muplugins_loaded hook
 * @param $plugins string|array Plugin file to load
 * @return true
 */
function tests_load_plugin(...$plugins){
    $plugins=is_array($plugins[0])?$plugins[0]:$plugins;
    return tests_add_filter('muplugins_loaded', function()use($plugins){
        foreach ($plugins as $plugin){
            require $plugin;
        }
    });
}
