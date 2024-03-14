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
namespace Glossary\Integrations\Widgets;

/**
 * A2Z widget
 */
class A2Z extends \WPH_Widget
{
    /**
     * Initialize the class
     */
    // phpcs:disable
    public function __construct()
    {
        $args = array(
            'label'       => \__( 'Glossary Alphabetical Index', GT_TEXTDOMAIN ),
            'description' => \__( 'Alphabetical ordered letter list of Glossary terms', GT_TEXTDOMAIN ),
            'slug'        => 'glossary-alphabetical-index',
        );
        $args['fields'] = array( array(
            'name'     => \__( 'Title', GT_TEXTDOMAIN ),
            'desc'     => \__( 'Enter the widget title.', GT_TEXTDOMAIN ),
            'id'       => 'title',
            'type'     => 'text',
            'class'    => 'widefat',
            'validate' => 'alpha_dash',
            'filter'   => 'strip_tags|esc_attr',
        ), array(
            'name' => \__( 'Show Counts', GT_TEXTDOMAIN ),
            'id'   => 'show_counts',
            'type' => 'checkbox',
        ), array(
            'name'     => \__( 'Category', GT_TEXTDOMAIN ),
            'desc'     => \__( 'Filter from Glossary category.', GT_TEXTDOMAIN ),
            'id'       => 'tax',
            'type'     => 'taxonomyterm',
            'taxonomy' => 'glossary-cat',
        ) );
        $this->create_widget( $args );
    }
    
    // phpcs:enable
    /**
     * Output the widget
     *
     * @param array $args     Arguments.
     * @param array $instance Fields of the widget.
     * @global object $wpdb Object.
     * @return void
     */
    public function widget( $args, $instance )
    {
        //phpcs:ignore
        $key = 'glossary-a2z-transient-' . \get_locale() . '-' . \md5( \wp_json_encode( $instance ) );
        $html = \get_transient( $key );
        if ( !isset( $instance['theme'] ) ) {
            $instance['theme'] = 'hyphen';
        }
        $out = $args['before_widget'];
        $out .= '<div class="theme-' . $instance['theme'] . '">';
        
        if ( isset( $instance['title'] ) ) {
            $out .= $args['before_title'];
            $out .= $instance['title'];
            $out .= $args['after_title'];
        }
        
        $out .= $this->generate_list( $key, $html, $instance );
        $out .= '</div>' . $args['after_widget'];
        echo  $out ;
        //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
    
    /**
     * Output the list
     *
     * @param string $key     Transient key.
     * @param string $html    HTML.
     * @param array  $instance Fields of the widget.
     * @return string
     */
    public function generate_list( string $key, string $html, array $instance )
    {
        
        if ( false === $html || empty($html) ) {
            $count_pages = \wp_count_posts( 'glossary' );
            if ( $count_pages->publish > 0 ) {
                $html = '<ul>' . \implode( '', $this->generate_list_item( $instance ) ) . '</ul>';
            }
            \set_transient( $key, $html, DAY_IN_SECONDS );
        }
        
        return $html;
    }
    
    /**
     * Output the list
     *
     * @param array $instance Fields of the widget.
     * @return array
     */
    public function generate_list_item( array $instance )
    {
        $show_counts = false;
        if ( isset( $instance['show_counts'] ) ) {
            $show_counts = (bool) $instance['show_counts'];
        }
        $pt_initials = \gl_get_a2z_initial( array(
            'show_counts' => $show_counts,
            'taxonomy'    => $instance['tax'],
        ) );
        $initial_arr = array();
        $base_url = \gl_get_base_url();
        $settings = \gl_get_settings();
        
        if ( !isset( $settings['archive'] ) || empty($settings['archive']) ) {
            $posttype = \get_post_type_object( 'glossary' );
            if ( is_object( $posttype ) && is_array( $posttype->rewrite ) ) {
                $base_url = \get_bloginfo( 'url' ) . '/' . $posttype->rewrite['slug'];
            }
        }
        
        foreach ( $pt_initials as $pt_rec ) {
            $link = \add_query_arg( 'az', $pt_rec['initial'], $base_url );
            $item = '<li><a href="' . $link . '">' . $pt_rec['initial'] . '</a></li>';
            if ( $show_counts ) {
                $item = '<li class="count"><a href="' . $link . '">' . $pt_rec['initial'] . ' <span>(' . $pt_rec['counts'] . ')</span></a></li>';
            }
            $initial_arr[] = $item;
        }
        return $initial_arr;
    }
    
    /**
     * After Validate Fields
     *
     * Allows to modify the output after validating the fields.
     *
     * @param array $instance Settings.
     * @return array
     */
    public function after_validate_fields( $instance = '' )
    {
        //phpcs:ignore
        $key = 'glossary-a2z-transient-' . \get_locale() . '-' . \md5( \wp_json_encode( $instance ) );
        \delete_transient( $key );
        return $instance;
    }
    
    /**
     * Initialize the class.
     *
     * @return void
     */
    public function initialize()
    {
        \add_action( 'widgets_init', static function () {
            \register_widget( 'Glossary\\Integrations\\Widgets\\A2Z' );
        } );
    }
    
    /**
     * Main Glossary_a2z_Archive.
     *
     * Ensure only one instance of Glossary_a2z_Archive is loaded.
     *
     * @return \Glossary\Integrations\Widgets\Glossary_a2z_Archive - Main instance.
     */
    public static function get_instance()
    {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

}