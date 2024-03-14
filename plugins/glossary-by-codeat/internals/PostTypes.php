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
namespace Glossary\Internals;

use  Glossary\Engine ;
/**
 * Post Types and Taxonomies
 */
class PostTypes extends Engine\Base
{
    /**
     * Tax and Post Types labels.
     *
     * @var array
     */
    private  $labels = array() ;
    /**
     * Initialize the class.
     *
     * @return bool
     */
    public function initialize()
    {
        parent::initialize();
        $this->generate_labels();
        \add_action( 'init', array( $this, 'load_cpts' ) );
        \add_action( 'init', array( $this, 'load_taxs' ) );
        \add_filter(
            'posts_orderby',
            array( $this, 'orderby_whitespace' ),
            9999,
            2
        );
        return true;
    }
    
    /**
     * Change the orderby for the glossary auto link system to add priority based on number of the spaces
     *
     * @param string $orderby   How to oder the query.
     * @param object $wp_object The object.
     * @global object $wpdb
     * @return string
     */
    public function orderby_whitespace( string $orderby, $wp_object )
    {
        
        if ( isset( $wp_object->query['glossary_auto_link'] ) ) {
            global  $wpdb ;
            $orderby = '(LENGTH(' . $wpdb->prefix . 'posts.post_title) - LENGTH(REPLACE(' . $wpdb->prefix . "posts.post_title, ' ', ''))+1) DESC";
        }
        
        return $orderby;
    }
    
    /**
     * Define the labels of the Glossary post type
     *
     * @return void
     */
    public function generate_labels()
    {
        $single = \__( 'Glossary Term', GT_TEXTDOMAIN );
        $multi = \__( 'Glossary', GT_TEXTDOMAIN );
        if ( isset( $this->settings['label_single'] ) ) {
            $single = $this->settings['label_single'];
        }
        if ( isset( $this->settings['label_multi'] ) ) {
            $multi = $this->settings['label_multi'];
        }
        $this->labels = array(
            'singular' => $single,
            'plural'   => $multi,
        );
        if ( empty($this->settings['slug']) ) {
            return;
        }
        $this->labels['slug'] = $this->settings['slug'];
    }
    
    /**
     * Generate the parameters for the post type
     *
     * @return array
     */
    public function generate_cpt_parameters()
    {
        $glossary_cpt = array(
            'slug'               => 'glossary',
            'show_in_rest'       => true,
            'menu_icon'          => 'dashicons-book-alt',
            'dashboard_activity' => true,
            'capability_type'    => array( 'glossary', 'glossaries' ),
            'supports'           => array(
            'thumbnail',
            'author',
            'editor',
            'title',
            'genesis-seo',
            'genesis-layouts',
            'genesis-cpt-archive-settings',
            'revisions'
        ),
            'admin_cols'         => array( 'title', 'glossary-cat' => array(
            'taxonomy' => 'glossary-cat',
        ), 'date' => array(
            'title'   => \__( 'Date', GT_TEXTDOMAIN ),
            'default' => 'ASC',
        ) ),
            'admin_filters'      => array(
            'glossary-cat' => array(
            'taxonomy' => 'glossary-cat',
        ),
        ),
        );
        if ( isset( $this->settings['post_type_hide'] ) ) {
            $glossary_cpt['publicly_queryable'] = false;
        }
        if ( isset( $this->settings['archive'] ) ) {
            $glossary_cpt['has_archive'] = false;
        }
        return $glossary_cpt;
    }
    
    /**
     * Initialize the post type
     *
     * @return void
     */
    public function load_cpts()
    {
        $glossary_cpt = $this->generate_cpt_parameters();
        $posttype = \register_extended_post_type( 'glossary', $glossary_cpt, $this->labels );
        $posttype->add_taxonomy( 'glossary-cat', array(
            'hierarchical' => \apply_filters( $this->default_parameters['filter_prefix'] . '_tax_hierarchical', false ),
            'show_ui'      => false,
        ) );
    }
    
    /**
     * Load Taxonomies on WordPress
     *
     * @return void
     */
    public function load_taxs()
    {
        $glossary_tax = $this->labels;
        $glossary_tax['plural'] = \__( 'Categories' );
        if ( !empty($this->settings['slug_cat']) ) {
            $glossary_tax['slug'] = $this->settings['slug_cat'];
        }
        \register_extended_taxonomy(
            'glossary-cat',
            'glossary',
            array(
            'public'           => true,
            'dashboard_glance' => true,
            'slug'             => 'glossary-cat',
            'show_in_rest'     => true,
            'capabilities'     => array(
            'manage_terms' => 'manage_glossaries',
            'edit_terms'   => 'manage_glossaries',
            'delete_terms' => 'manage_glossaries',
            'assign_terms' => 'read_glossary',
        ),
        ),
            $glossary_tax
        );
    }

}