<?php
/**
 * Add Portfolio post type and taxonomies
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://averta.net/phlox/
 * @copyright  (c) 2010-2023 averta
*/

// no direct access allowed
if ( ! defined('ABSPATH') )  exit;



/**
 * Register Portfolio post type and taxonomies
 *
 */
class Auxpfo_Post_Type_Portfolio extends Auxin_Post_Type_Base {



    function __construct() {

        $post_type = 'portfolio';

        //add_filter( 'admin_post_thumbnail_html' , array( $this, 'featured_image_instruction' ) );
        //add_filter( 'the_permalink'             , array( $this, 'posttype_permalink' ), 10, 1 );

        parent::__construct( $post_type );
    }


    /**
     * Register post type
     *
     * @return void
     */
    public function register_post_type() {

        if( ! $single_slug  = get_theme_mod( $this->prefix.'permalink_'.$this->post_type.'_structure', '' ) )
            $single_slug    = $this->post_type; // validate single slug

        if( ! $archive_slug = get_theme_mod( $this->prefix.'permalink_'.$this->post_type.'_archive_structure', '' ) )
            $archive_slug   = $this->post_type.'/all'; // validate archive slug


        $labels = array(
            'name'               => _x( 'Portfolios'          , 'auxin-portfolio' ),
            'singular_name'      => __( 'Portfolio'           , 'auxin-portfolio' ),
            'menu_name'          => _x( 'Portfolios'          , 'Admin menu name', 'auxin-portfolio' ),
            'add_new'            => __( 'Add New'             , 'auxin-portfolio' ),
            'all_items'          => __( 'All Portfolios'      , 'auxin-portfolio' ),
            'add_new_item'       => __( 'Add New Portfolio'   , 'auxin-portfolio' ),
            'edit_item'          => __( 'Edit Portfolio'      , 'auxin-portfolio' ),
            'new_item'           => __( 'New Portfolio'       , 'auxin-portfolio' ),
            'view_item'          => __( 'View Portfolio'      , 'auxin-portfolio' ),
            'search_items'       => __( 'Search Portfolios'   , 'auxin-portfolio' ),
            'parent'             => __( 'Parent Portfolio'    , 'auxin-portfolio' ),
            'not_found'          => __( 'No Portfolios found' , 'auxin-portfolio' ),
            'not_found_in_trash' => __( 'No Portfolios found in Trash', 'auxin-portfolio' )
        );

        $args = array(
            'labels'                => $labels,
            'description'           => __( 'Here you can add new portfolio to your website.', 'auxin-portfolio' ),
            'public'                => true,
            'publicly_queryable'    => true,
            'exclude_from_search'   => false,
            'show_ui'               => true,
            'query_var'             => true,
            'rewrite'               => array (
                'slug'       => $single_slug,
                'with_front' => true,
                'feeds'      => true
            ),
            'capability_type'       => $this->post_type,
            'map_meta_cap'          => true,
            'hierarchical'          => false,
            'menu_position'         => 30,
            'show_in_nav_menus'     => true,
            'menu_icon'             => 'dashicons-art',
            'supports'              => array( 'title','editor','thumbnail','excerpt','page-attributes', 'revisions', 'custom-fields' ),
            'has_archive'           => $archive_slug,
            'show_in_rest'          => true
        );

        return register_post_type( $this->post_type, apply_filters( "auxin_register_post_type_args_{$this->post_type}", $args ) );
    }

    /**
     * Register taxonomies
     *
     * @return void
     */
    public function register_taxonomies() {

        // labels for Category of this post type
        $cat_labels = array(
            'name'              => __( 'Portfolio Categories'        , 'auxin-portfolio' ),
            'singular_name'     => __( 'Portfolio Category'        , 'auxin-portfolio' ),
            'all_items'         => __( 'All Portfolio Categories'  , 'auxin-portfolio' ),
            'parent_item'       => __( 'Parent Portfolio Category' , 'auxin-portfolio' ),
            'parent_item_colon' => __( 'Parent Portfolio Category:', 'auxin-portfolio' ),
            'edit_item'         => __( 'Edit Portfolio Category'   , 'auxin-portfolio' ),
            'update_item'       => __( 'Update Portfolio Category' , 'auxin-portfolio' ),
            'add_new_item'      => __( 'Add New Portfolio Category', 'auxin-portfolio' ),
            'new_item_name'     => __( 'New Portfolio Category'    , 'auxin-portfolio' ),
            'search_items'      => __( 'Search in Portfolio Categories', 'auxin-portfolio' ),
            'menu_name'         => _x( 'Categories', 'portfolio-cat admin menu name', 'auxin-portfolio' )
        );

        $tax_cat_name = 'portfolio-cat';

        register_taxonomy( $tax_cat_name,
            apply_filters( "auxin_taxonomy_post_types_for_{$tax_cat_name}" , array( $this->post_type ) ),
            apply_filters( "auxin_taxonomy_args_{$tax_cat_name}"       , array(
                'hierarchical'          => true,
                'tax_position'          => true,
                'label'                 => __( 'Portfolio Categories', 'auxin-portfolio' ),
                'labels'                => $cat_labels,
                'show_ui'               => true,
                'update_count_callback' => '_update_post_term_count',
                'query_var'             => true,
                'capabilities'          => array(
                    'manage_terms'      => "manage_{$this->post_type}_terms",
                    'edit_terms'        => "edit_{$this->post_type}_terms",
                    'delete_terms'      => "delete_{$this->post_type}_terms",
                    'assign_terms'      => "assign_{$this->post_type}_terms",
                ),
                'rewrite'       => array(
                    'slug'          => get_theme_mod( $this->prefix.'permalink_'. $this->post_type. '_' .str_replace('-', '_', $tax_cat_name ) .'_structure', $tax_cat_name ),
                    'hierarchical'  => false
                ),
                'show_in_rest'          => true
            ) )
        );





        // labels for Tag of this post type
        $tag_labels = array(
            'name'                       => __( 'Portfolio Tags'                   , 'auxin-portfolio' ),
            'singular_name'              => __( 'Portfolio Tag'                    , 'auxin-portfolio' ),
            'search_items'               => __( 'Search in Portfolio Tags'         , 'auxin-portfolio' ),
            'popular_items'              => __( 'Popular Tags'                     , 'auxin-portfolio' ),
            'all_items'                  => __( 'All Portfolio Tags'               , 'auxin-portfolio' ),
            'parent_item'                => __( 'Parent Portfolio Tag'             , 'auxin-portfolio' ),
            'parent_item_colon'          => __( 'Parent Portfolio Tag:'            , 'auxin-portfolio' ),
            'edit_item'                  => __( 'Edit Portfolio Tag'               , 'auxin-portfolio' ),
            'update_item'                => __( 'Update Portfolio Tag'             , 'auxin-portfolio' ),
            'add_new_item'               => __( 'Add new Portfolio Tag'            , 'auxin-portfolio' ),
            'new_item_name'              => __( 'New Portfolio Tag'                , 'auxin-portfolio' ),

            'separate_items_with_commas' => __( 'Separate tags with commas'        , 'auxin-portfolio' ),
            'add_or_remove_items'        => __( 'Add or remove Tag'                , 'auxin-portfolio' ),
            'choose_from_most_used'      => __( 'Choose from the most used tags'   , 'auxin-portfolio' ),
            'menu_name'                  => _x( 'Tags', 'portfolio-tag admin menu name'  , 'auxin-portfolio' )
        );

        $tax_tag_name = 'portfolio-tag';

        register_taxonomy( $tax_tag_name,
            apply_filters( "auxin_taxonomy_post_types_for_{$tax_tag_name}" , array( $this->post_type ) ),
            apply_filters( "auxin_taxonomy_args_{$tax_tag_name}"       , array(
                'hierarchical'          => false,
                'label'                 => __( 'Portfolio Tags', 'auxin-portfolio' ),
                'labels'                => $tag_labels,
                'show_ui'               => true,
                'update_count_callback' => '_update_post_term_count',
                'query_var'             => true,
                'capabilities'          => array(
                    'manage_terms'      => "manage_{$this->post_type}_terms",
                    'edit_terms'        => "edit_{$this->post_type}_terms",
                    'delete_terms'      => "delete_{$this->post_type}_terms",
                    'assign_terms'      => "assign_{$this->post_type}_terms",
                ),
                'rewrite'       => array(
                    'slug'          => get_theme_mod( $this->prefix.'permalink_'. $this->post_type. '_' .str_replace('-', '_', $tax_tag_name ).'_structure', $tax_tag_name ),
                    'hierarchical'  => false
                ),
                'show_in_rest'          => true
            ) )
        );





        // labels for filter of this post type
        $tag_labels = array(
            'name'                       => __( 'Portfolio Filters'                , 'auxin-portfolio' ),
            'singular_name'              => __( 'Portfolio Filter'                 , 'auxin-portfolio' ),
            'search_items'               => __( 'Search in Portfolio Filters'      , 'auxin-portfolio' ),
            'popular_items'              => __( 'Popular Filters'                  , 'auxin-portfolio' ),
            'all_items'                  => __( 'All Portfolio Filters'            , 'auxin-portfolio' ),
            'parent_item'                => __( 'Parent Portfolio Filter'          , 'auxin-portfolio' ),
            'parent_item_colon'          => __( 'Parent Portfolio Filter:'         , 'auxin-portfolio' ),
            'edit_item'                  => __( 'Edit Portfolio Filter'            , 'auxin-portfolio' ),
            'update_item'                => __( 'Update Portfolio Filter'          , 'auxin-portfolio' ),
            'add_new_item'               => __( 'Add new Portfolio Filter'         , 'auxin-portfolio' ),
            'new_item_name'              => __( 'New Portfolio Filter'             , 'auxin-portfolio' ),

            'separate_items_with_commas' => __( 'Separate filters with commas'     , 'auxin-portfolio' ),
            'add_or_remove_items'        => __( 'Add or remove filter'             , 'auxin-portfolio' ),
            'choose_from_most_used'      => __( 'Choose from the most used filters', 'auxin-portfolio' ),
            'menu_name'                  => _x( 'Filters', 'portfolio-filter admin menu name'  , 'auxin-portfolio' )
        );

        $tax_tag_name = 'portfolio-filter';

        register_taxonomy( $tax_tag_name,
            apply_filters( "auxin_taxonomy_post_types_for_{$tax_tag_name}" , array( $this->post_type ) ),
            apply_filters( "auxin_taxonomy_args_{$tax_tag_name}"       , array(
                'hierarchical'          => false,
                'label'                 => __( 'Portfolio Filters', 'auxin-portfolio' ),
                'labels'                => $tag_labels,
                'show_ui'               => true,
                'update_count_callback' => '_update_post_term_count',
                'query_var'             => true,
                'capabilities'          => array(
                    'manage_terms'      => "manage_{$this->post_type}_terms",
                    'edit_terms'        => "edit_{$this->post_type}_terms",
                    'delete_terms'      => "delete_{$this->post_type}_terms",
                    'assign_terms'      => "assign_{$this->post_type}_terms",
                ),
                'rewrite'  => false,
                'show_in_rest'          => true
            ) )
        );

    }


    /**
     * Customizing post type list Columns
     *
     * @param  array $column  An array of column name => label
     * @return array          List of columns shown when listing posts of the post type
     */
    public function manage_edit_columns( $columns ){
        unset( $columns['title'], $columns['date'] );

        $new_columns = array(
            "cb"                => "<input type=\"checkbox\" />",
            "portfolio_image"   => _x( 'Image'              , 'Image column at portfolio edit columns', 'auxin-portfolio' ),
            "title"             => _x( 'Title'              , 'Title column at portfolio edit columns', 'auxin-portfolio' ),
            "cat"               => _x( 'Category / Type'    , 'Type  column at portfolio edit columns', 'auxin-portfolio' ),
            "tag"               => _x( 'Tag / Filter'       , 'Tag/Filter column at portfolio edit columns', 'auxin-portfolio' ),
            "release_date"      => _x( 'Release Date'       , 'Date  column at portfolio edit columns', 'auxin-portfolio' )
        );

        return array_merge( $new_columns, $columns );
    }


    /**
     * Applied to the list of columns to print on the manage posts screen for current post type
     *
     * @param  array $column  An array of column name => label
     * @return array          List of columns shown when listing posts of the post type
     */
    public function manage_posttype_custom_columns( $column ){
        global $post;

        switch ( $column ) {
            case "description":
                the_excerpt();
                break;
            case "cat":
                echo get_the_term_list( $post->ID, 'portfolio-cat', '', ', ','' );
                break;
            case "tag":
                echo get_the_term_list( $post->ID, 'portfolio-tag', '', ', ','' );
                break;
            case "portfolio_image":
                echo get_the_post_thumbnail( $post, 'thumbnail' );
                break;
            case "release_date":
                echo get_post_meta( $post->ID, "release-date", true );
                break;
        }
    }


    /**
     * Add instruction to featured post
     */
    function featured_image_instruction( $content ) {
        if( $this->post_type == get_post_type() ){
            return $content .= sprintf('<p>%s</p>', __( 'This is an image that is chosen as the representative/cover image for your project.', 'auxin-portfolio' ) );
        }
        return $content;
    }


    /**
     * Redirect post type single page if redirect URL is set
     */
    function posttype_permalink( $permalink ){
        global $post;

        if ( isset( $post ) && $this->post_type == get_post_type( $post->ID ) ){
            $redirect_url = get_post_meta( $post->ID, "{$this->post_type}-redirect-url", true );
            if( ! empty( $redirect_url ) )
                  return $redirect_url;
        }
        return $permalink;
    }

}
