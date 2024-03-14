<?php
/**
 * Cleaner class file.
 *
 * @package WordPress Polyfills
 */

namespace Oblak\WP\Filter;

/**
 * Filter Cleaner is a simple class for managing wp filters and hooks.
 *
 * It enables you to find a filter by its class and method name, and remove it.
 */
class Cleaner {
    /**
     * Finds a filter by its class and method name.
     *
     * @param  string $hook_name   The name of the filter to find.
     * @param  string $class_name  The name of the class to find.
     * @param  string $method_name The name of the method to find.
     * @param  int    $priority    The priority of the filter to find.
     * @return string|null         The unique ID for the filter, or null if not found.
     */
    public static function find_class_with_filter( string $hook_name, string $class_name, string $method_name, int $priority = 10 ): ?string {
        global $wp_filter;
        $unique_id = null;

        if ( ! \is_array( $wp_filter[ $hook_name ]->callbacks[ $priority ] ?? null ) ) {
            return $unique_id;
        }

        foreach ( $wp_filter[ $hook_name ]->callbacks[ $priority ] as $id => $filter_array ) {
            if (
                ! \is_array( $filter_array['function'] ?? null ) || // If not an array, it's not a class/method.
                ! \is_object(
                    $filter_array['function'][0] ?? null,
                ) || // If not an object, it's not a class/method.
                ! \get_class(
                    $filter_array['function'][0] ?? null,
                ) || // If not a class, it's not a class/method.
                \get_class(
                    $filter_array['function'][0],
                ) !== $class_name || // If not the right class, it's not a class/method.
                $method_name !== $filter_array['function'][1] // If not the right method, it's not a class/method.
                ) {
                continue;
			}

            $unique_id = $id;
            break;
        }

        return $unique_id;
    }

    /**
     * Removes a filter by unique ID
     *
     * @param  string $hook_name  The name of the filter to remove.
     * @param  int    $priority   The priority of the filter to remove.
     * @param  string $unique_id  The unique ID for the filter.
     */
    public static function remove_filter_by_id( string $hook_name, int $priority, string $unique_id ) {
        global $wp_filter;

        if ( ! \is_array( $wp_filter[ $hook_name ]->callbacks[ $priority ] ?? null ) ) {
            return null;
        }

        unset( $wp_filter[ $hook_name ]->callbacks[ $priority ][ $unique_id ] );
    }

    /**
     * Gets a filter by class and method name, and removes it.
     *
     * @param  string $hook_name   The name of the filter to get.
     * @param  string $class_name  The name of the class to get.
     * @param  string $method_name The name of the method to get.
     * @param  int    $priority    The priority of the filter to get.
     * @return object|null          The filter, or null if not found.
     */
    public static function get_class_with_filter_and_remove( string $hook_name, string $class_name, string $method_name, int $priority = 10 ): ?object {
        global $wp_filter;

        $unique_id = self::find_class_with_filter( $hook_name, $class_name, $method_name, $priority );

        if ( ! $unique_id ) {
            return null;
        }

        $filter = $wp_filter[ $hook_name ]->callbacks[ $priority ][ $unique_id ];

        self::remove_filter_by_id( $hook_name, $priority, $unique_id );

        return $filter['function'][0] ?? null;
    }

    /**
     * Removes a filter by class and method name.
     *
     * @param  string $hook_name   The name of the filter to remove.
     * @param  string $class_name  The name of the class to remove.
     * @param  string $method_name The name of the method to remove.
     * @param  int    $priority    The priority of the filter to remove.
     */
    public static function remove_filter_by_class_and_method( string $hook_name, string $class_name, string $method_name, int $priority = 10 ) {
        $unique_id = self::find_class_with_filter( $hook_name, $class_name, $method_name, $priority );

        if ( ! $unique_id ) {
            return;
        }

        self::remove_filter_by_id( $hook_name, $priority, $unique_id );

        return null;
    }

    /**
     * Removes all filters by class name.
     *
     * @param  array<class-string> ...$class_names The name of the class to remove.
     * @return array <string,array <int,array <int,string>>>
     */
    public static function remove_all_class_filters( ...$class_names ): array {
        global $wp_filter;

        $removed = array();

        foreach ( $class_names as $class_name ) {
            foreach ( $wp_filter as $hook_name => $data ) {
                foreach ( $data->callbacks as $priority => $callbacks ) {
                    foreach ( $callbacks as $id => $filter_array ) {
                        if (
                            ! \is_array(
                                $filter_array['function'] ?? null,
                            ) || // If not an array, it's not a class/method.
                            ! \is_object(
                                $filter_array['function'][0] ?? null,
                            ) || // If not an object, it's not a class/method.
                            ! \get_class(
                                $filter_array['function'][0] ?? null,
                            ) || // If not a class, it's not a class/method.
                            \get_class(
                                $filter_array['function'][0],
                            ) !== $class_name // If not the right class, it's not a class/method.
                        ) {
                            continue;
                        }

                        $removed[ $hook_name ]              ??= array();
                        $removed[ $hook_name ][ $priority ] ??= array();
                        $removed[ $hook_name ][ $priority ][] = $filter_array['function'][1];

                        unset( $wp_filter[ $hook_name ]->callbacks[ $priority ][ $id ] );
                    }
                }
            }
        }

        return $removed;
    }
}
