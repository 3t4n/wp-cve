<?php
/**
 * Filter polyfills
 *
 * ! This file intentionally left without namespace
 *
 * @package WP Polyfills
 */

use Oblak\WP\Filter\Cleaner;

if ( ! function_exists( 'remove_filter_by_class_method' ) ) :
    /**
     * Removes a filter by class and method name.
     *
     * @param  string $hook      The name of the filter to remove.
     * @param  string $classname The name of the class to remove.
     * @param  string $method    The name of the method to remove.
     * @param  int    $priority  The priority of the filter to remove.
     * @param  bool   $get_class Whether to return the class instance.
     */
    function remove_filter_by_class_method( string $hook, string $classname, string $method, int $priority, bool $get_class = false ): ?object {
        return $get_class
        ? Cleaner::get_class_with_filter_and_remove( $hook, $classname, $method, $priority )
        : Cleaner::remove_filter_by_class_and_method( $hook, $classname, $method, $priority );
    }
endif;

if ( ! function_exists( 'remove_all_filters_by_class' ) ) :
    /**
     * Removes all filtes added by a class.
     *
     * @param  array<class-string> ...$classes The classes to remove filters from.
     * @return array <string,array <int,array <int,string>>>
     */
    function remove_all_filters_by_class( ...$classes ) {
        return Cleaner::remove_all_class_filters( $classes );
    }
endif;
