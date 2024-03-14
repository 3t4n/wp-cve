<?php
/**
 * Extended_Settings_Page class file
 *
 * @package WooCommerce Sync Service
 * @subpackage WooCommerce
 */

namespace Oblak\WooCommerce\Admin;

use WC_Settings_Page;

/**
 * Extended settings page
 */
abstract class Extended_Settings_Page extends WC_Settings_Page {

    /**
     * Array of extended settings
     *
     * @var array
     */
    protected array $settings;

    /**
     * Class constructor
     *
     * @param string $id             Settings page ID.
     * @param string $label          Settings page label.
     * @param array  $settings_array Array of settings.
     */
    public function __construct( string $id, string $label, array $settings_array ) {
        $this->id       = $id;
        $this->label    = $label;
        $this->settings = $this->parse_settings( $settings_array );

        parent::__construct();

        add_filter( 'woocommerce_get_settings_' . $this->id, array( $this, 'get_raw_settings' ), 0, 2 );
    }

    /**
     * Get the raw settings array
     *
     * @param  array  $settings Settings array.
     * @param  string $section  Section ID.
     * @return array            Raw settings array.
     */
    public function get_raw_settings( array $settings, string $section ): array {
        $do_section = array_key_exists( $section, $this->settings );

        /**
         * Dynamically turn on / off sections
         *
         * @param  bool   $do_section Whether to show the section or not.
         * @param  string $section    Section ID.
         * @return bool               Whether to show the section or not.
         *
         * @since 1.13.0
         */
        if ( ! apply_filters( "woocommerce_extended_settings_section_{$this->id}", $do_section, $section ) ) {
            return $settings;
        }

        // Filters get added only when needed.
        add_filter( 'woocommerce_get_settings_' . $this->id, array( $this, 'get_extended_settings' ), 10, 2 );
        add_action( 'woocommerce_settings_save_' . $this->id, array( $this, 'prepare_settings_cleanup' ), 1, 1 );

        return $this->settings[ $section ]['fields'] ?? array();
    }

    /**
     * {@inheritDoc}
     */
    final public function get_own_sections() {
        foreach ( $this->settings as $section => $data ) {
            if ( ! $data['enabled'] ) {
                continue;
            }
            $sections[ $section ] = $data['section_name'];
        }

        return $sections;
    }

    /**
     * Get the settings fields
     *
     * @param  array  $settings Settings array.
     * @param  string $section  Section ID.
     * @return array            Settings fields array.
     */
    public function get_extended_settings( array $settings, string $section ): array {
        $nested = false;

        foreach ( $settings as $index => $field ) {
            if ( isset( $field['field_name'] ) ) {
                continue;
            }
            $settings[ $index ]['id'] = $this->get_setting_field_id( $this->get_option_key( $section ), $field );

            if (
                str_ends_with( $field['id'], '[]' ) ||
                str_ends_with( $field['field_name'] ?? '', '[]' ) ||
                array_key_exists( 'multiple', $field['custom_attributes'] ?? array() ) ||
                'multiselect' === $field['type'] ||
                true === ( $field['nested'] ?? false )
            ) {
                $nested = true;
            }

            foreach ( $field as $key => $val ) {
                if ( is_callable( $val ) ) {
                    $settings[ $index ][ $key ] = $val();
                }
            }
        }

        if ( $nested ) {
            add_filter( 'woocommerce_admin_settings_sanitize_option_' . $this->get_option_key( $section ), array( $this, 'sanitize_nested_array' ), 99, 3 );
        }

        /**
         * Filters the formated settings for the plugin
         *
         * @param array $settings Formated settings array
         * @param string $section Section ID
         * @return array Formated settings array
         *
         * @since 2.2.0
         */
        return apply_filters( "woocommerce_formatted_settings_{$this->id}", $settings, $section );
    }

    /**
     * Parses the raw settings array
     *
     * @param  array $settings Raw settings array.
     * @return array           Parsed settings array.
     */
    final protected function parse_settings( array $settings ): array {
        uasort(
            $settings,
            function ( $a, $b ) {
                return $a['priority'] - $b['priority'];
            }
        );

        return $settings;
    }

    /**
     * Get the option key for a section
     *
     * @param  string $section Section ID.
     * @return string          Option key.
     */
    final protected function get_option_key( string $section ): string {
        return '' !== $section ? "{$this->id}_settings_{$section}" : "{$this->id}_settings_general";
    }

    /**
     * Get the formatted setting field ID.
     *
     * @param  string $option_key Option key.
     * @param  array  $field      Field array.
     * @return string             Formatted setting field ID.
     */
    final protected function get_setting_field_id( string $option_key, array $field ): string {
        $is_multiselect = 'select' === $field['type'] && array_key_exists( 'multiple', ( $field['custom_attributes'] ?? array() ) );
        return sprintf(
            '%s[%s]%s',
            $option_key,
            $field['id'],
            $is_multiselect ? '[]' : ''
        );
    }

    /**
     * Santizes the double nested arrays, since WooCommerce doesn't support them
     *
     * @param  mixed $value     Sanitized value.
     * @param  array $option    Option array.
     * @param  mixed $raw_value Raw value.
     */
    final public function sanitize_nested_array( mixed $value, array $option, $raw_value ) {
        if ( ! str_ends_with( $option['field_name'] ?? $option['id'], '[]' ) ) {
            return $value;
        }

        return array_filter( array_map( $option['sanitize'] ?? 'wc_clean', array_filter( $raw_value ?? array() ) ) );
    }

    /**
     * Cleans up the settings array.
     *
     * Removes all the settings that are not in the POST request.
     * They can be old settings, or deprecated settings.
     */
    public function prepare_settings_cleanup() {
        if ( ! check_admin_referer( 'woocommerce-settings' ) ) {
            return;
        }

        $post = wc_clean( wp_unslash( $_POST ) );
        $get  = wc_clean( wp_unslash( $_GET ) );

        if ( $get['tab'] !== $this->id || empty( $post ) ) {
            return;
        }
        $section      = '' === ( $get['section'] ?? '' ) ? 'general' : $get['section'];
        $option_key   = $this->get_option_key( $section );
        $old_settings = get_option( $option_key );
        $new_settings = array();

        foreach ( is_array( $old_settings ) ? array_keys( $old_settings ) : false as $key ) {
            if ( in_array( $key, array_keys( $post ), true ) ) {
                $new_settings[ $key ] = $old_settings[ $key ];
            }
        }

        if ( $new_settings === $old_settings ) {
            return;
        }

        update_option( $option_key, $new_settings );
    }
}
