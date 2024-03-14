<?php

namespace GSBEH;

defined( 'ABSPATH' ) || exit;

final class TemplateLoader {

    private static $template_path = '';

    private static $theme_path = '';

    private static $child_theme_path = '';

    public function __construct() {

        self::$template_path = GSBEH_PLUGIN_DIR . 'templates/';
        add_action( 'init', [$this, 'set_theme_template_path'] );

    }

    public function set_theme_template_path() {

        $path = apply_filters( 'gs_behance_templates_folder', 'gs-behance' );

        if ( $path ) {
            $path = '/' . trailingslashit( ltrim( $path, '/\\' ) );
            self::$theme_path = get_stylesheet_directory() . $path;
        }

    }

    public static function locate_template( $template_file ) {

        // Default path
        $path = self::$template_path;
        
        if ( file_exists( self::$theme_path . $template_file ) ) {
            // Override default template if exist from theme
            $path = self::$theme_path;
        }
        
        // Check requested file exist
        if ( ! file_exists( $path . $template_file ) ) return new \WP_Error( 'gsbeh_template_not_found', __( 'Template file not found - GsPlugins', 'gs-behance' ) );

        if ( is_child_theme() ) {
            // Override default template if exist from child theme
            if ( file_exists( self::$child_theme_path . $template_file ) ) $path = self::$child_theme_path;
        }
        
        // Return template path, it can be default or overridden by theme
        return $path . $template_file;

    }

}