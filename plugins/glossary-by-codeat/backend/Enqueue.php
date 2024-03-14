<?php

/**
 * Glossary
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2020
 * @license   GPL 3.0+
 * @link      https://codeat.co
 */
namespace Glossary\Backend;

use  Glossary\Engine ;
/**
 * This class contain the Enqueue stuff for the backend
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
        if ( !parent::initialize() ) {
            return false;
        }
        // Load admin style sheet and JavaScript.
        \add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
        \add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
        return true;
    }
    
    /**
     * Register and enqueue admin-specific style sheet.
     *
     * @since 2.0
     * @return bool Return early if no settings page is registered.
     */
    public function enqueue_admin_styles()
    {
        \wp_enqueue_style(
            GT_SETTINGS . '-admin-single-style',
            \plugins_url( 'assets/css/glossary-admin.css', GT_PLUGIN_ABSOLUTE ),
            array(),
            GT_VERSION
        );
        $screen = \get_current_screen();
        if ( !\is_null( $screen ) && 'glossary_page_glossary' !== $screen->base ) {
            return false;
        }
        \wp_enqueue_style(
            GT_SETTINGS . '-admin-styles',
            \plugins_url( 'assets/css/admin.css', GT_PLUGIN_ABSOLUTE ),
            array( 'dashicons' ),
            GT_VERSION
        );
        return true;
    }
    
    /**
     * Register and enqueue admin-specific JavaScript.
     *
     * @since 2.0
     * @return bool Return early if no settings page is registered.
     */
    public function enqueue_admin_scripts()
    {
        \wp_enqueue_script(
            GT_SETTINGS . '-admin-script',
            \plugins_url( 'assets/js/admin.js', GT_PLUGIN_ABSOLUTE ),
            array( 'jquery' ),
            GT_VERSION,
            false
        );
        wp_localize_script( GT_SETTINGS . '-admin-script', 'glossaryAdmindata', array(
            'alert'   => \__( 'Error with the ChatGPT request!', GT_TEXTDOMAIN ),
            'warning' => \__( 'Please provide a title to automatically generate the content for the term.', GT_TEXTDOMAIN ),
            'waiting' => \__( 'Waiting for server response', GT_TEXTDOMAIN ),
            'nonce'   => \wp_create_nonce( 'generate_nonce' ),
            'wp_rest' => \wp_create_nonce( 'wp_rest' ),
            'prompt'  => \__( 'Please provide a glossary term definition for \'[replaceme]\' and divide the text into paragraphs. Plain text only, do not use markdown or HTML. Ensure that the content consists of at least 350 words.', GT_TEXTDOMAIN ),
        ) );
        $screen = \get_current_screen();
        if ( !\is_null( $screen ) && 'glossary_page_glossary' !== $screen->base ) {
            return false;
        }
        \wp_enqueue_script(
            GT_SETTINGS . '-admin-pt-script',
            \plugins_url( 'assets/js/pt.js', GT_PLUGIN_ABSOLUTE ),
            array( 'jquery' ),
            GT_VERSION,
            false
        );
        \wp_enqueue_script(
            GT_SETTINGS . '-admin-tabs-script',
            \plugins_url( 'assets/js/tabs.js', GT_PLUGIN_ABSOLUTE ),
            array( 'jquery', 'jquery-ui-tabs' ),
            GT_VERSION,
            false
        );
        return true;
    }

}