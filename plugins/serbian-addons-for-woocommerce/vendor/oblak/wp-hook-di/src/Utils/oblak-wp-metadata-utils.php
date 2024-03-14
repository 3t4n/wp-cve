<?php
/**
 * WordPress metadata utils
 *
 * @package WP Utils
 * @subpackage Utility functions
 */

namespace Oblak\WP\Utils;

use Automattic\Jetpack\Constants;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionMethod;

/**
 * Get decorators for a class or object.
 *
 * @template T
 * @param  string|object   $class_or_obj Class or object to get the decorators for.
 * @param  class-string<T> $decorator    Decorator class to get.
 * @param  bool            $all          Whether to get all the decorators or only the ones in the class.
 * @return T[]                           Array of decorators.
 */
function get_decorators( $class_or_obj, $decorator, bool $all = false ) {
    $decorators = array();

    while ( $class_or_obj ) {
        $decorators   = array_merge(
            $decorators,
            array_map(
                fn( $d ) => $d?->newInstance(),
                ( new ReflectionClass( $class_or_obj ) )?->getAttributes( $decorator, ReflectionAttribute::IS_INSTANCEOF )
            )
        );
        $class_or_obj = $all ? get_parent_class( $class_or_obj ) : null;
    }

    return $decorators;
}

/**
 * Parses PHPDoc annotations.
 *
 * @param  ReflectionMethod $method Method to parse annotations for.
 * @param  array<string>    $needed_keys Keys that must be present in the parsed annotations.
 * @return array<string,string>     Parsed annotations.
 */
function parse_annotations( ReflectionMethod &$method, ?array $needed_keys = null ): ?array {
    $doc = $method->getDocComment();
    if ( ! $doc ) {
        return null;
    }

    preg_match_all( '/@([a-z]+?)\s+(.*?)\n/i', $doc, $annotations );

    if ( ! isset( $annotations[1] ) || 0 === count( $annotations[1] ) ) {
        return array();
    }

    $needed_keys ??= array( 'hook', 'type' );

    $annotations = array_filter(
        array_merge( // Merge the parsed annotations with number of params from the method.
            array_combine(  // Combine the annotations with their values.
                array_map( 'trim', $annotations[1] ), // Trim the keys.
                array_map( 'trim', $annotations[2] ) // Trim the values.
            ),
            array( 'args' => $method->getNumberOfParameters() ) // Add the number of params.
        ),
        fn( $v ) => '' !== $v
    );

    // If the number of found annotations doesn't match the number of needed keys -> gtfo.
    return count( $annotations ) >= count( $needed_keys ) &&
        count( array_intersect( $needed_keys, array_keys( $annotations ) ) ) >= count( $needed_keys )
        ? $annotations
        : array();
}

/**
 * Determine the priority of a hook.
 *
 * @param  int|string|null $priority_prop Priority property.
 * @return int
 */
function get_hook_priority( int|string|null $priority_prop = null ): int {
    $priority_prop ??= 10;
    if ( is_numeric( $priority_prop ) ) {
        return intval( $priority_prop );
    } elseif ( Constants::get_constant( $priority_prop ) ) {
        return Constants::get_constant( $priority_prop );
    } elseif ( str_starts_with( $priority_prop, 'filter:' ) ) {
        $filter_data = explode( ':', $priority_prop );

        return apply_filters( $filter_data[1], $filter_data[2] ?? 10 ); //phpcs:ignore WooCommerce.Commenting.HookComment
    } else {
        return 10;
    }
}

/**
 * Get all the hooks in public methods of a class
 *
 * @param  class-string|object $class_or_obj Class or object to get the hooks for.
 * @param  array|null          $needed_keys  Keys that must be present in the parsed annotations.
 * @param  bool                $all          Whether to get all the hooks or only the ones in the class.
 * @return array                             Array of hooks.
 */
function get_class_hooks( $class_or_obj, ?array $needed_keys = null, bool $all = false ): array {
    $reflector = new ReflectionClass( $class_or_obj );

    return array_filter(
        array_map(
            fn( $hook_args ) => wp_parse_args(
                $hook_args,
                array(
					'hook' => null,
					'args' => 0,
                )
            ),
            array_filter(
                wp_array_flatmap(
                    array_filter(
                        $reflector->getMethods( ReflectionMethod::IS_PUBLIC ) ?? array(),
                        fn( $method ) => $all || $method->class === $reflector->getName()
                    ),
                    fn( $method ) => array( $method->getName() => parse_annotations( $method, $needed_keys ) ),
                )
            )
        ),
        fn ( $h ) => is_null( $needed_keys ) || ( count( array_intersect( $needed_keys, array_keys( $h ) ) ) >= count( $needed_keys ) )
    );
}

/**
 * Invoke hooks for a class or object.
 *
 * @param  string|object $class_or_obj Class or object to invoke the hooks for.
 * @param  array|null    $hooks        Hooks to invoke.
 */
function invoke_class_hooks( $class_or_obj, ?array $hooks = null ) {
    $hooks ??= get_class_hooks( $class_or_obj );

    foreach ( $hooks as $function => $hook_data ) {
        $hook_names      = array_map( 'trim', explode( ',', $hook_data['hook'] ) );
        $hook_priorities = array_map( fn( $p ) => get_hook_priority( $p ), explode( ',', $hook_data['priority'] ?? '' ) );

        foreach ( $hook_names as $index => $hook_name ) {
            "add_{$hook_data['type']}"(
                $hook_name,
                array( $class_or_obj, $function ),
                $hook_priorities[ $index ] ?? 10,
                $hook_data['args']
            );
        }
    }
}
