<?php

namespace LaStudioKitExtensions\Events;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

use LaStudioKitExtensions\Module_Base;

class Module extends Module_Base {

    /**
     * Module version.
     *
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Module directory path.
     *
     * @since 1.0.0
     * @access protected
     * @var string $path
     */
    protected $path;

    /**
     * Module directory URL.
     *
     * @since 1.0.0
     * @access protected
     * @var string $url.
     */
    protected $url;

    public static function is_active(){
        $available_extension = lastudio_kit_settings()->get_option('avaliable_extensions', []);
        return !empty($available_extension['event_content_type']) && filter_var($available_extension['event_content_type'], FILTER_VALIDATE_BOOLEAN);
    }

    public function __construct()
    {
        $this->path = lastudio_kit()->plugin_path('includes/extensions/events/');
        $this->url  = lastudio_kit()->plugin_url('includes/extensions/events/');

		add_action( 'init', [ $this, 'register_content_type' ] );
	    add_action( 'init', [ $this, 'add_metaboxes' ], -5 );

	    add_action( 'elementor/widgets/register', function ($widgets_manager){
		    $widgets_manager->register( new Widgets\Events() );
	    } );
    }

	public function register_content_type(){

		register_post_type( 'la_event', apply_filters('lastudio-kit/admin/events/args', [
			'labels'                => [
				'name'          => __( 'Events', 'lastudio-kit' ),
				'singular_name' => __( 'Events', 'lastudio-kit' ),
			],
			'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
			'menu_icon'             => 'dashicons-calendar-alt',
			'public'                => true,
			'menu_position'         => 8,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'rewrite'               => array( 'slug' => 'event' )
		]));
		register_taxonomy( 'la_event_type', 'la_event', apply_filters('lastudio-kit/admin/event_types/args', [
			'hierarchical'      => true,
			'show_in_nav_menus' => true,
			'labels'            => array(
				'name'          => __( 'Types', 'lastudio-kit' ),
				'singular_name' => __( 'Type', 'lastudio-kit' )
			),
			'query_var'         => true,
			'show_admin_column' => true,
			'rewrite'           => array('slug' => 'event-type')
		]));
	}

	public function add_metaboxes() {

		lastudio_kit_post_meta()->add_options( array (
			'id'            => 'lastudiokit-event-settings',
			'title'         => esc_html__( 'Event Settings', 'lastudio-kit' ),
			'page'          => array( 'la_event' ),
			'context'       => 'normal',
			'priority'      => 'high',
			'callback_args' => false,
			'admin_columns' => array(
				'lakit_thumb' => array(
					'label'    => sprintf('<span class="lakit-image">%1$s</span>', __('Images', 'lastudio-kit')),
					'callback' => array( $this, 'metabox_callback__column' ),
					'position' => 1,
				),
				'event_start_date' => array(
					'label'    => __( 'Event Date', 'lastudio-kit' ),
					'callback' => array( $this, 'metabox_callback__column' ),
				),
				'event_status' => array(
					'label'    => __( 'Status', 'lastudio-kit' ),
					'callback' => array( $this, 'metabox_callback__column' ),
				),
			),
			'fields'        => array(
				'event_status' => array(
					'type'        => 'select',
					'title'       => esc_html__( 'Event Status', 'lastudio-kit' ),
					'description' => esc_html__( 'Choose a status for tickets for this event.', 'lastudio-kit' ),
					'options'     => array(
						'upcoming'  => esc_html__('Up Coming', 'lastudio-kit'),
						'past'      => esc_html__('Past', 'lastudio-kit'),
						'cancelled'  => esc_html__('Cancelled', 'lastudio-kit'),
						'sold_out'   => esc_html__('Sold Out', 'lastudio-kit'),
					),
				),
				'event_start_date' => array(
					'type'        => 'text',
					'input_type'  => 'date',
					'title'       => esc_html__( 'Start Date', 'lastudio-kit' ),
					'description' => esc_html__( 'Formatted like "YYYY-MM-DD".', 'lastudio-kit' ),
					'placeholder' => 'YYYY-MM-DD'
				),
				'event_end_date' => array(
					'type'        => 'text',
					'input_type'  => 'date',
					'title'       => esc_html__( 'End Date', 'lastudio-kit' ),
					'description' => esc_html__( 'Formatted like "YYYY-MM-DD".', 'lastudio-kit' ),
					'placeholder' => 'YYYY-MM-DD'
				),
				'event_time' => array(
					'type'        => 'text',
					'title'       => esc_html__( 'Time', 'lastudio-kit' ),
					'description' => esc_html__( 'Set a time for this event.', 'lastudio-kit' ),
					'placeholder' => 'HH:MM'
				),
				'event_location' => array(
					'type'        => 'text',
					'title'       => esc_html__( 'Location', 'lastudio-kit' ),
					'description' => esc_html__( 'Set a location for the event.', 'lastudio-kit' ),
					'placeholder' => esc_html__('e.g: "Bruges, Belgium" or " New Orleans, LA"', 'lastudio-kit')
				),
				'event_stage' => array(
					'type'        => 'text',
					'title'       => esc_html__( 'Stage', 'lastudio-kit' ),
					'description' => esc_html__( 'Select a stage for the event.', 'lastudio-kit' ),
					'placeholder' => esc_html__( 'Stage', 'lastudio-kit' ),
				),
				'event_website' => array(
					'type'        => 'text',
					'title'       => esc_html__( 'Website', 'lastudio-kit' ),
					'description' => esc_html__( 'Input the website for this event.', 'lastudio-kit' ),
					'placeholder' => esc_html__( 'https://website.com', 'lastudio-kit' ),
				),
				'event_organized_by' => array(
					'type'        => 'text',
					'title'       => esc_html__( 'Organized By', 'lastudio-kit' ),
					'description' => esc_html__( 'Input the name of the event organizer.', 'lastudio-kit' ),
					'placeholder' => esc_html__( 'Organized', 'lastudio-kit' ),
				),
				'event_ticket_link' => array(
					'type'        => 'text',
					'title'       => esc_html__( 'Buy Tickets Link', 'lastudio-kit' ),
					'description' => esc_html__( 'Input a link to the website where tickets can be purchased for this event.', 'lastudio-kit' ),
					'placeholder' => esc_html__( 'https://website.com', 'lastudio-kit' ),
				),
				'event_backtolink' => array(
					'type'        => 'text',
					'title'       => esc_html__( 'Back to Link', 'lastudio-kit' ),
					'description' => esc_html__( 'Input a "back to" link from the event\'s single page.', 'lastudio-kit' ),
					'placeholder' => esc_html__( 'https://website.com', 'lastudio-kit' ),
				),
			),
		) );
	}

	public function metabox_callback__column( $column, $post_id ){
		if($column === 'lakit_thumb'){
			return printf('<a href="%2$s">%1$s</a>', get_the_post_thumbnail($post_id), get_edit_post_link($post_id) );
		}
		elseif ( $column === 'event_status' ){
			$value = get_post_meta( $post_id, $column, true );
			$opts = array(
				'upcoming'  => esc_html__('Up Coming', 'lastudio-kit'),
				'past'      => esc_html__('Past', 'lastudio-kit'),
				'cancelled'  => esc_html__('Cancelled', 'lastudio-kit'),
				'sold_out'   => esc_html__('Sold Out', 'lastudio-kit'),
			);
			if( $value && isset($opts[$value]) ){
				return printf('<span>%1$s</span>', $opts[$value]);
			}
			else{
				return printf('<span>%1$s</span>', __('N/A', 'lastudio-kit'));
			}
		}
		elseif ( $column === 'event_start_date' ){
			$_date = get_post_meta( $post_id, $column, true );
			$_time = get_post_meta( $post_id, 'event_time', true );
			return printf('<span>%1$s %2$s</span>', $_date, $_time);
		}
	}
}