<?php

namespace WpStarter\WpTestbench\Support;

class Hook
{
    public static function addFilter($hook_name, $callback, $priority = 10, $accepted_args = 1 ) {
        global $wp_filter;

        if ( function_exists( 'add_filter' ) ) {
            add_filter( $hook_name, $callback, $priority, $accepted_args );
        } else {
            $idx = static::buildUniqueId( $hook_name, $callback, $priority );

            $wp_filter[ $hook_name ][ $priority ][ $idx ] = array(
                'function'      => $callback,
                'accepted_args' => $accepted_args,
            );
        }

        return true;
    }
    public static function buildUniqueId( $hook_name, $callback, $priority ) {
        if ( is_string( $callback ) ) {
            return $callback;
        }

        if ( is_object( $callback ) ) {
            // Closures are currently implemented as objects.
            $callback = array( $callback, '' );
        } else {
            $callback = (array) $callback;
        }

        if ( is_object( $callback[0] ) ) {
            // Object class calling.
            return spl_object_hash( $callback[0] ) . $callback[1];
        } elseif ( is_string( $callback[0] ) ) {
            // Static calling.
            return $callback[0] . '::' . $callback[1];
        }
    }
}