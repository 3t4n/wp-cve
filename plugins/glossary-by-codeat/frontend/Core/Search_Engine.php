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
namespace Glossary\Frontend\Core;

use  Glossary\Engine ;
/**
 * Combine the Core to gather, search and inject
 */
class Search_Engine extends Engine\Base
{
    /**
     * Is_Methods class
     *
     * @var \Glossary\Engine\Is_Methods
     */
    private  $content ;
    /**
     * Terms_list class
     *
     * @var \Glossary\Frontend\Core\Terms_List
     */
    private  $terms_list ;
    /**
     * Terms list
     *
     * @var array
     */
    private  $terms ;
    /**
     * Injector class
     *
     * @var \Glossary\Frontend\Core\Term_Injector
     */
    private  $injector ;
    public function __construct()
    {
        parent::initialize();
        $this->injector = new Term_Injector();
        $this->injector->initialize();
        $this->content = new Engine\Is_Methods();
        $this->terms = array();
        $this->terms_list = new Terms_List();
        $this->terms_list->initialize();
    }
    
    /**
     * Initialize the class with all the hooks
     *
     * @since 1.0.0
     * @return bool
     */
    public function initialize()
    {
        $priority = 999;
        // Change priority for Fixed TOC
        if ( \defined( 'FTOC_VERSION' ) ) {
            $priority = 9;
        }
        // Change priority for Easy Table of content
        if ( \class_exists( 'ezTOC' ) ) {
            $priority = 99;
        }
        // Support on Elementor for the Text Widget
        if ( \defined( 'ELEMENTOR_VERSION' ) ) {
            \add_filter( 'widget_text', array( $this, 'check_auto_link' ), $priority );
        }
        // Support on Divi on shortcode flow execution
        if ( \function_exists( 'et_setup_theme' ) ) {
            \add_action(
                'do_shortcode_tag',
                array( $this, 'check_divi_block' ),
                3,
                9999
            );
        }
        $priority = apply_filters( $this->default_parameters['filter_prefix'] . '_content_priority', $priority );
        \add_filter( 'the_content', array( $this, 'check_auto_link' ), $priority );
        \add_filter( 'the_excerpt', array( $this, 'check_auto_link' ), $priority );
        // BuddyPress support on activities
        if ( \apply_filters( $this->default_parameters['filter_prefix'] . '_buddypress_support', false ) ) {
            \add_filter( 'bp_get_activity_content_body', array( $this, 'auto_link' ), $priority );
        }
        // BBpress support on content
        if ( \apply_filters( $this->default_parameters['filter_prefix'] . '_bbpress_support', false ) ) {
            \add_filter( 'bbp_get_reply_content', array( $this, 'auto_link' ), $priority );
        }
        \add_filter( 'the_excerpt_rss', array( $this, 'check_auto_link' ), $priority );
        return false;
        return true;
    }
    
    /**
     * Validate to show the auto link
     *
     * @param string $text The content.
     * @return string
     */
    public function check_auto_link( $text )
    {
        // phpcs:ignore
        $text = \strval( $text );
        // Don't execute glossary on header (with block theme support) and if Bricks didn't printed yet the body
        if ( (function_exists( 'wp_is_block_theme' ) && !wp_is_block_theme() || !function_exists( 'wp_is_block_theme' )) && !did_action( 'wp_head' ) || defined( 'BRICKS_VERSION' ) && !did_action( 'bricks_body' ) ) {
            return $text;
        }
        if ( !$this->content->is_already_parsed( $text ) && \apply_filters( $this->default_parameters['filter_prefix'] . '_is_page_to_parse', $this->content->is_page_type_to_check() ) ) {
            return $this->auto_link( $text );
        }
        return $text;
    }
    
    /**
     * If there are terms to inject, let's do it.
     *
     * @param string $text String that wrap with a tooltip/link.
     * @return string
     */
    public function auto_link( $text )
    {
        // phpcs:ignore
        if ( empty($this->terms) ) {
            $this->terms = $this->terms_list->get();
        }
        if ( empty($this->terms) ) {
            return $text;
        }
        return $this->injector->do_wrap( (string) $text, $this->terms );
    }
    
    /**
     * Parse the shortcode content for some Divi blocks
     *
     * @param string $output The shortcode content.
     * @param string $tag The shortcode tag.
     * @param string $attr The shortcode attributes.
     * @return string
     */
    public function check_divi_block( $output, $tag, $attr )
    {
        if ( (isset( $attr['theme_builder_area'] ) && $attr['theme_builder_area'] === 'post_content' || isset( $attr['_builder_version'] )) && \in_array( $tag, array( 'et_pb_text', 'et_pb_wc_description' ), true ) ) {
            if ( \apply_filters( $this->default_parameters['filter_prefix'] . '_is_page_to_parse', $this->content->is_page_type_to_check() ) ) {
                $output = $this->auto_link( $output );
            }
        }
        return $output;
    }

}