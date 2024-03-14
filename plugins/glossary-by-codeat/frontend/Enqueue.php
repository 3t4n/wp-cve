<?php

/**
 * Glossary
 *
 * @package   Glossary
 * @author  Codeat <support@codeat.co>
 * @copyright 2020
 * @license   GPL 3.0+
 * @link      https://codeat.co
 */
namespace Glossary\Frontend;

use  Glossary\Engine ;
/**
 * Enqueue stuff on the frontend
 */
class Enqueue extends Engine\Base
{
    /**
     * Initialize the class.
     *
     * @return bool
     */
    public function initialize()
    {
        parent::initialize();
        // Add the url of the themes in the plugin
        \add_filter( 'glossary_themes_url', array( $this, 'add_glossary_url' ) );
        if ( !isset( $this->settings['tooltip'] ) ) {
            return false;
        }
        \add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 9999 );
        \add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 9999 );
        return true;
    }
    
    /**
     * Add the path to the themes
     *
     * @param array $themes List of themes.
     * @return array
     */
    public function add_glossary_url( array $themes )
    {
        $themes['classic'] = \plugins_url( 'assets/css/tooltip-classic.css', GT_PLUGIN_ABSOLUTE );
        $themes['box'] = \plugins_url( 'assets/css/tooltip-box.css', GT_PLUGIN_ABSOLUTE );
        $themes['line'] = \plugins_url( 'assets/css/tooltip-line.css', GT_PLUGIN_ABSOLUTE );
        $themes['simple'] = \plugins_url( 'assets/css/tooltip-simple.css', GT_PLUGIN_ABSOLUTE );
        return $themes;
    }
    
    /**
     * Check if shortcode is in page
     *
     * @param string $shortcode Shortcode name.
     * @return bool
     */
    public function is_shortcode_in_page( string $shortcode )
    {
        global  $post ;
        if ( !\is_a( $post, 'WP_Post' ) ) {
            return false;
        }
        if ( \has_shortcode( $post->post_content, $shortcode ) ) {
            return true;
        }
        
        if ( \defined( 'ELEMENTOR_PRO_VERSION' ) || \defined( 'ELEMENTOR_VERSION' ) ) {
            $data = \get_post_meta( $post->ID, '_elementor_data', true );
            if ( !empty($data) && \is_string( $data ) && \has_shortcode( $data, $shortcode ) ) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Register and enqueue public-facing style sheet.
     *
     * @return void
     */
    public function enqueue_styles()
    {
        /**
         * Array with all the url of themes
         *
         * @since 1.2.0
         * @param array $urls The list.
         * @return array $urls The list filtered.
         */
        $url_themes = \apply_filters( 'glossary_themes_url', array() );
        \wp_enqueue_style(
            GT_SETTINGS . '-hint',
            $url_themes[$this->settings['tooltip_style']],
            array(),
            GT_VERSION
        );
        if ( $this->is_shortcode_in_page( 'glossary-terms' ) || \is_active_widget(
            false,
            false,
            'glossary-categories',
            true
        ) || \is_active_widget(
            false,
            false,
            'glossary-latest-terms',
            true
        ) ) {
            \wp_enqueue_style(
                GT_SETTINGS . '-general',
                \plugins_url( 'assets/css/general.css', GT_PLUGIN_ABSOLUTE ),
                array(),
                GT_VERSION
            );
        }
        $this->enqueue_css_widget();
    }
    
    /**
     * Enqueue the css for widgets
     *
     * @return bool
     */
    public function enqueue_css_widget()
    {
        if ( \is_active_widget(
            false,
            false,
            'glossary-alphabetical-index',
            true
        ) ) {
            \wp_enqueue_style(
                GT_SETTINGS . '-a2z-widget',
                \plugins_url( 'assets/css/A2Z-widget.css', GT_PLUGIN_ABSOLUTE ),
                array(),
                GT_VERSION
            );
        }
        if ( !\is_active_widget(
            false,
            false,
            'glossary-search-terms',
            true
        ) ) {
            return false;
        }
        \wp_enqueue_style(
            GT_SETTINGS . '-search-widget',
            \plugins_url( 'assets/css/css-pro/search-widget.css', GT_PLUGIN_ABSOLUTE ),
            array(),
            GT_VERSION
        );
        return true;
    }
    
    /**
     * Register and enqueues public-facing JavaScript files.
     *
     * @return void
     */
    public function enqueue_scripts()
    {
        \wp_enqueue_script(
            GT_SETTINGS . '-off-screen',
            \plugins_url( 'assets/js/off-screen.js', GT_PLUGIN_ABSOLUTE ),
            array(),
            GT_VERSION,
            true
        );
    }

}