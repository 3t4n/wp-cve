<?php
/**
 * Settings_Helper trait file
 *
 * @package WooCommerce Sync Service
 * @subpackage Utils
 */

namespace Oblak\WooCommerce\Core;

trait Settings_Helper {

    /**
     * Array of settings
     *
     * @var array
     */
    protected array $settings;

    /**
     * Get the settings array from the database
     *
     * @param  string $prefix        The settings prefix.
     * @param  array  $raw_settings  The settings fields.
     * @param  mixed  $default_value The default value for the settings.
     * @return array                 The settings array.
     */
    protected function load_settings( string $prefix, array $raw_settings, $default_value ): array {
        $defaults   = $this->get_defaults( $raw_settings, $default_value );
        $settings   = array();
        $option_key = $prefix . '_settings_';

        foreach ( $this->get_registered_sections( $option_key, array_keys( $defaults ) ) as $section ) {
            $default_values       = $defaults[ $section ] ?? array();
            $section_settings     = wp_parse_args(
                get_option( $option_key . $section, array() ),
                $default_values
            );
            $settings[ $section ] = array();

            foreach ( $section_settings as $raw_key => $raw_value ) {
                $value = in_array( $raw_value, array( 'yes', 'no' ), true )
                    ? ( 'yes' === $raw_value )
                    : $raw_value;

                if ( str_contains( $raw_key, '-' ) ) {
                    $keys = explode( '-', $raw_key );

                    if ( ! isset( $settings[ $section ][ $keys[0] ] ) ) {
                        $settings[ $section ][ $keys[0] ] = array();
                    }

                    $settings[ $section ][ $keys[0] ][ $keys[1] ] = $value;

                    continue;
                }

                $settings[ $section ][ $raw_key ] = $value;
            }
        }

        return $settings;
    }

    /**
     * Get the settings section from the database
     *
     * This function was added because of the dynamic sections
     *
     * @param  string   $option_key       The option key base.
     * @param  string[] $default_sections The default sections.
     * @return string[]                   The registered sections.
     */
    protected function get_registered_sections( $option_key, $default_sections ) {
        global $wpdb;

        $like     = $wpdb->esc_like( $option_key ) . '%';
        $sections = $wpdb->get_col(
            $wpdb->prepare(
                "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s",
                $like
            )
        );

        return array_unique(
            array_merge(
                array_map(
                    function ( $section ) use( $option_key ) {
                        return str_replace( $option_key, '', $section );
                    },
                    $sections
                ),
                $default_sections
            )
        );
    }

    /**
     * Iterate over the settings array and get the default values
     *
     * @param  array $settings      The settings fields.
     * @param  mixed $default_value The default value for the settings.
     * @return array                The default values.
     */
    protected function get_defaults( array $settings, $default_value = false ): array {
        $defaults = array();
        foreach ( $settings as $section => $data ) {
            $section_data = array();
            $section      = '' !== $section ? $section : 'general';
            $fields       = array_filter( $data['fields'], fn( $f ) => ! in_array( $f['type'], array( 'title', 'sectionend', 'info' ), true ) && ! isset( $f['field_name'] ) );

            foreach ( $fields as $field ) {
                $section_data[ $field['id'] ] = $field['default'] ?? $default_value;
            }

            $defaults[ $section ] = $section_data;
        }

        return $defaults;
    }

    /**
     * Get the settings array
     *
     * @param  string $section The section to get.
     * @param  string ...$args The sub-sections to get.
     * @return array<string, mixed>|mixed           Array of settings or a single setting.
     */
    public function get_settings( string $section = 'all', string ...$args ) {
        if ( 'all' === $section ) {
            return $this->settings;
        }

        $sub_section = $this->settings[ $section ] ?? array();

        foreach ( $args as $arg ) {
            $sub_section = $sub_section[ $arg ] ?? array();
        }

        return $sub_section;
    }
}
