<?php
/**
 * Meta_Getters class file.
 *
 * @package WooCommerce Utils
 * @subpackage Misc
 */

namespace Oblak\WooCommerce\Misc;

/**
 * Meta getters for various data types
 */
final class Meta_Getters {
    /**
     * Get post by meta
     *
     * @param string $meta_key   Meta key.
     * @param mixed  $meta_value Meta value.
     */
    public static function get_post_by_meta( $meta_key, $meta_value ) {
        global $wpdb;

        return (int) $wpdb->get_var(
            $wpdb->prepare(
                "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value = %s",
                $meta_key,
                $meta_value,
            ),
        );
    }

    /**
     * Get term by meta
     *
     * @param string $meta_key   Meta key.
     * @param mixed  $meta_value Meta value.
     */
    public static function get_term_by_meta( $meta_key, $meta_value ) {
        global $wpdb;

        return (int) $wpdb->get_var(
            $wpdb->prepare(
                "SELECT term_id FROM {$wpdb->termmeta} WHERE meta_key = %s AND meta_value = %s",
                $meta_key,
                $meta_value,
            ),
        );
    }

    /**
     * Get user by meta
     *
     * @param string $meta_key   Meta key.
     * @param mixed  $meta_value Meta value.
     */
    public static function get_user_by_meta( $meta_key, $meta_value ) {
        global $wpdb;

        return (int) $wpdb->get_var(
            $wpdb->prepare(
                "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key = %s AND meta_value = %s",
                $meta_key,
                $meta_value,
            ),
        );
    }

    /**
     * Alias for get_user_by_meta()
     *
     * @param string $meta_key   Meta key.
     * @param mixed  $meta_value Meta value.
     */
    public static function get_customer_by_meta( $meta_key, $meta_value ) {
        return self::get_user_by_meta( $meta_key, $meta_value );
    }

    /**
     * Get any entity id from any table by its meta key or value
     *
     * @param  string $table      Table name.
     * @param  string $id_key     ID key.
     * @param  string $meta_key   Meta key.
     * @param  mixed  $meta_value Meta value.
     * @return int                Entity ID.
     */
    public static function get_anything_by_meta( $table, $id_key, $meta_key, $meta_value ) {
        global $wpdb;

        return (int) $wpdb->get_var(
            $wpdb->prepare(
                "SELECT $id_key FROM {$wpdb->prefix}{$table} WHERE meta_key = %s AND meta_value = %s", //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
                $meta_key,
                $meta_value,
            ),
        );
    }
}
