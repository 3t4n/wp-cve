<?php
/**
 * Template_Extender class file.
 *
 * @package WooCommerce Utils
 * @subpackage Core
 */

namespace Oblak\WooCommerce\Core;

use WC_Admin_Status;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

/**
 * Enables easy extending of WooCommerce templates.
 *
 * @since 1.1.0
 */
abstract class Base_Template_Extender {

    /**
     * Base path
     *
     * @var string
     */
    protected $base_path = '';

    /**
     * Template filename array
     *
     * @var array
     */
    protected $templates = array();

    /**
     * Template filenames that are static
     *
     * Static templates, are templates that cannot be overriden in the theme.
     *
     * @var array
     */
    protected $static_templates = array();

    /**
     * Path tokens array
     *
     * @var array
     */
    protected $path_tokens = array();

    /**
     * Class constructor
     */
    public function __construct() {
        if ( '' === $this->base_path ) {
            return;
        }
        add_filter( 'woocommerce_get_path_define_tokens', array( $this, 'add_path_define_tokens' ), 99, 1 );
        add_filter( 'woocommerce_locate_template', array( $this, 'modify_template_path' ), 99, 2 );
        add_filter( 'rest_pre_dispatch', array( $this, 'modify_rest_response' ), 22, 3 );
        add_filter( 'pre_set_transient_wc_system_status_theme_info', array( $this, 'add_custom_template_files_to_status_report' ), 99, 1 );
    }

    /**
     * Adds custom path define tokens to the existing WooCommerce tokens.
     *
     * @param  array $tokens Existing path define tokens.
     * @return array         Modified array of tokens.
     */
    public function add_path_define_tokens( $tokens ) {
        return array_merge( $tokens, $this->path_tokens );
    }

    /**
     * Locate a template and return the path for inclusion.
     *
     * This is the load order:
     *
     * yourtheme/$template_path/$template_name
     * yourtheme/$template_name
     * yourplugin/$template_path/$template_name
     *
     * @param  string $template      Full template path.
     * @param  string $template_name Template name.
     * @return string                Modified template path.
     */
    public function modify_template_path( $template, $template_name ) {
        // If not one of our templates, bail out.
        if ( ! in_array( $template_name, $this->templates, true ) ) {
            return $template;
        }

        // If template is static, set default path to plugin.
        if ( in_array( $template_name, $this->static_templates, true ) ) {
            return trailingslashit( $this->base_path ) . $template_name;
        }

        // Try to locate the template file in the theme.
        $template = locate_template(
            array(
                trailingslashit( WC()->template_path() ) . $template_name,
                $template_name,
            )
        );

        // If template is found within the theme return it, otherwise return the plugin template file.
        return $template
            ? $template
            : trailingslashit( $this->base_path ) . $template_name;
    }

    /**
     * Modifies the REST response for the WooCommerce system status
     *
     * @param  WP_REST_Response $response REST response.
     * @param  WP_REST_Server   $server   REST server.
     * @param  WP_REST_Request  $request  REST request.
     * @return WP_REST_Response           Modified REST response.
     */
    public function modify_rest_response( $response, $server, $request ) {
        if ( ! str_contains( $request->get_route(), 'system_status' ) ) {
            return $response;
        }

        remove_filter( 'rest_pre_dispatch', array( $this, 'modify_rest_response' ), 22 );

        wc()->api->get_endpoint_data( '/wc/v3/system_status' );

        return $response;
    }

    /**
     * Adds plugin custom templates to the WooCommerce system status report.
     *
     * @param  array $theme_info Theme info.
     * @return array             Modified theme info.
     */
    public function add_custom_template_files_to_status_report( $theme_info ) {
        if ( empty( $this->templates ) && empty( $this->static_templates ) ) {
            return $theme_info;
        }

        $woocommerce_template_files = WC_Admin_Status::scan_template_files( WC()->plugin_path() . '/templates/' );
        $plugin_all_files           = array_unique( array_merge( $this->templates, $this->static_templates ) );
        $common_static_files        = array_intersect( $woocommerce_template_files, array_intersect( $plugin_all_files, $this->static_templates ) );
        $plugin_static_files        = array_diff( $this->static_templates, $common_static_files );
        $plugin_custom_files        = array_diff( $plugin_all_files, $this->static_templates, $woocommerce_template_files, );

        // Remove WooCommerce templates overriden statically by the plugin.
        foreach ( $common_static_files as $static_file ) {
            $theme_info['overrides'] = array_filter(
                $theme_info['overrides'],
                function ( $data ) use ( $static_file ) {
                    return $this->remove_file_from_overrides( $data, $static_file );
                }
            );
        }

        $theme_info['overrides'] = array_merge(
            $this->check_file_versions( $common_static_files, $this->base_path ),
            $this->check_file_versions( $plugin_static_files, $this->base_path ),
            $this->check_file_versions( $plugin_custom_files, get_stylesheet_directory() . '/woocommerce', $this->base_path ),
            $theme_info['overrides'],
        );

        return $theme_info;
    }

    /**
     * Removes WooCommerce templates overriden statically by the plugin.
     *
     * @param  array  $file_data File data.
     * @param  string $file_path File path.
     */
    private function remove_file_from_overrides( $file_data, $file_path ) {
        return ! str_contains( $file_data['file'], $file_path );
    }

    /**
     * Checks the version of the files.
     *
     * @param  string[] $files_to_check Array of files to check.
     * @param  string   $base_path      Base path.
     * @param  string   $core_path      Core path.
     */
    private function check_file_versions( $files_to_check, $base_path, $core_path = '' ) {
        if ( '' === $core_path ) {
            $core_path = WC()->plugin_path() . '/templates';
        }

        $override_files = array();
        $base_path      = trailingslashit( $base_path );
        $core_path      = trailingslashit( $core_path );

        $to_replace = str_contains( $base_path, 'themes' )
            ? WP_CONTENT_DIR . '/themes/'
            : WP_PLUGIN_DIR . '/';

        foreach ( $files_to_check as $file ) {
            $located = trailingslashit( $base_path ) . $file;

            $our_version  = WC_Admin_Status::get_file_version( $located );
            $core_version = WC_Admin_Status::get_file_version( $core_path . $file );

            $override_files[] = array(
                'file'         => str_replace( $to_replace, '', $located ),
                'version'      => ! empty( $our_version ) ? $our_version : $core_version,
                'core_version' => $core_version,
            );

        }

        return $override_files;
    }
}
