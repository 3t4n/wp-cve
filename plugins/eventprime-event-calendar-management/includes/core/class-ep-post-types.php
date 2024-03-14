<?php
defined( 'ABSPATH' ) || exit;
/**
 * Class to create initial post type, taxonomy and status
 */
class EventM_Post_types {
	/**
	 * Init action
	 */
	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_taxonomies' ), 5 );
		add_action( 'init', array( __CLASS__, 'register_post_types' ), 5 );
		add_action( 'init', array( __CLASS__, 'register_post_status' ), 9 );
	}

	/**
	 * Register plugin's default taxonomies
	 */
	public static function register_taxonomies() {
        if( ! is_blog_installed() ) {
            return;
        }

		if ( taxonomy_exists( 'em_event_type' ) ) {
			return;
		}

        do_action( 'eventprime_register_taxonomy' );

        $plural_type_text = ep_global_settings_button_title( 'Event-Types' );
        $singular_type_text = ep_global_settings_button_title( 'Event-Type' );

        if( $plural_type_text == 'Event-Types' ) {
            $plural_type_text = 'Types';
        }

		register_taxonomy(
			'em_event_type',
			'em_event',
			array(
				'label'             => $plural_type_text,
				'labels'            => array(
                    'name'              => $plural_type_text,
                    'singular_name'     => $singular_type_text,
                    'menu_name'         => _x( $plural_type_text, 'Admin menu name', 'eventprime-event-calendar-management' ),
                    'search_items'      => sprintf( esc_html__( 'Search %s', 'eventprime-event-calendar-management' ), $plural_type_text ),
                    'all_items'         => sprintf( esc_html__( 'All %s', 'eventprime-event-calendar-management' ), $plural_type_text ),
                    'parent_item'       => sprintf( esc_html__( 'Parent %s', 'eventprime-event-calendar-management' ), $singular_type_text ),
                    'parent_item_colon' => sprintf( esc_html__( 'Parent %s:', 'eventprime-event-calendar-management' ), $singular_type_text ),
                    'edit_item'         => sprintf( esc_html__( 'Edit %s', 'eventprime-event-calendar-management' ), $singular_type_text ),
                    'update_item'       => sprintf( esc_html__( 'Update %s', 'eventprime-event-calendar-management' ), $singular_type_text ),
                    'add_new_item'      => sprintf( esc_html__( 'Add New %s', 'eventprime-event-calendar-management' ), $singular_type_text ),
                    'new_item_name'     => sprintf( esc_html__( 'New %s', 'eventprime-event-calendar-management ' ), $singular_type_text ),
                    'not_found'         => sprintf( esc_html__( 'No %s found', 'eventprime-event-calendar-management' ), $plural_type_text ),
                ),
		        'public'            => true,
                'show_ui'           => true,
                'show_in_nav_menus' => true,
                'show_in_menu'      => true,
                'query_var'         => true,
                'hierarchical'      => true,
                'show_in_quick_edit' => false,
                'capabilities'      => array(
                    'manage_terms' => 'manage_em_event_terms',
                    'edit_terms'   => 'edit_em_event_terms',
                    'delete_terms' => 'delete_em_event_terms',
                    'assign_terms' => 'assign_em_event_terms',
                ),
                'rewrite'           => array(
                    'slug'       => ep_get_seo_page_url( 'event-type' ),
                    //'ep_mask'    => EP_EM_EVENTS,
                    'with_front' => true
                ),
                'meta_box_cb'       => 'ep_taxonomy_select_meta_box',
                //'show_in_rest'      => true,
			)
		);

        $plural_venue_text = ep_global_settings_button_title( 'Venues' );
        $singular_venue_text = ep_global_settings_button_title( 'Venue' );

		register_taxonomy(
			'em_venue',
			'em_event',
			array(
				'label'             => $plural_venue_text,
				'labels'            => array(
                    'name'              => $plural_venue_text,
                    'singular_name'     => $singular_venue_text,
                    'menu_name'         => _x( $plural_venue_text, 'Admin menu name', 'eventprime-event-calendar-management' ),
                    'search_items'      => sprintf( esc_html__( 'Search %s', 'eventprime-event-calendar-management' ), $plural_venue_text ),
                    'all_items'         => sprintf( esc_html__( 'All %s', 'eventprime-event-calendar-management' ), $plural_venue_text ),
                    'parent_item'       => sprintf( esc_html__( 'Parent %s', 'eventprime-event-calendar-management' ), $singular_venue_text ),
                    'parent_item_colon' => sprintf( esc_html__( 'Parent %s:', 'eventprime-event-calendar-management' ), $singular_venue_text ),
                    'edit_item'         => sprintf( esc_html__( 'Edit %s', 'eventprime-event-calendar-management' ), $singular_venue_text ),
                    'update_item'       => sprintf( esc_html__( 'Update %s', 'eventprime-event-calendar-management' ), $singular_venue_text ),
                    'add_new_item'      => sprintf( esc_html__( 'Add New %s', 'eventprime-event-calendar-management' ), $singular_venue_text ),
                    'new_item_name'     => sprintf( esc_html__( 'New %s', 'eventprime-event-calendar-management' ), $singular_venue_text ),
                    'not_found'         => sprintf( esc_html__( 'No %s found', 'eventprime-event-calendar-management' ), $plural_venue_text ),
                ),
                'show_ui'           => true,
                'show_in_nav_menus' => true,
                'show_in_menu'      => true,
                'query_var'         => true,
                'hierarchical'      => true,
                'show_in_quick_edit' => false,
                'rewrite'           => array(
                    'slug'       => ep_get_seo_page_url( 'venue' ),
                    //'ep_mask'  => EP_EM_EVENTS,
                    'with_front' => true
                ),
                'capabilities'      => array(
                    'manage_terms' => 'manage_em_event_terms',
                    'edit_terms'   => 'edit_em_event_terms',
                    'delete_terms' => 'delete_em_event_terms',
                    'assign_terms' => 'assign_em_event_terms',
                ),
                'meta_box_cb'       => 'ep_taxonomy_select_meta_box',
                //'show_in_rest'      => true,
			)
		);

        $plural_organizer_text = ep_global_settings_button_title( 'Organizers' );
        $singular_organizer_text = ep_global_settings_button_title( 'Organizer' );

		register_taxonomy(
			'em_event_organizer',
			'em_event',
			array(
	            'label'             => $plural_organizer_text,
	            'labels'            => array(
	                'name'              => $plural_organizer_text,
	                'singular_name'     => $singular_organizer_text,
                    'menu_name'         => _x( $plural_organizer_text, 'Admin menu name', 'eventprime-event-calendar-management' ),
	                'search_items'      => sprintf( esc_html__( 'Search %s', 'eventprime-event-calendar-management' ), $plural_organizer_text ),
	                'all_items'         => sprintf( esc_html__( 'All %s', 'eventprime-event-calendar-management' ), $plural_organizer_text ),
	                'parent_item'       => sprintf( esc_html__( 'Parent %s', 'eventprime-event-calendar-management' ), $singular_organizer_text ),
	                'parent_item_colon' => sprintf( esc_html__( 'Parent %s:', 'eventprime-event-calendar-management' ), $singular_organizer_text ),
	                'edit_item'         => sprintf( esc_html__( 'Edit %s', 'eventprime-event-calendar-management' ), $singular_organizer_text ),
	                'update_item'       => sprintf( esc_html__( 'Update %s', 'eventprime-event-calendar-management' ), $singular_organizer_text ),
	                'add_new_item'      => sprintf( esc_html__( 'Add New %s', 'eventprime-event-calendar-management' ), $singular_organizer_text ),
	                'new_item_name'     => sprintf( esc_html__( 'New %s', 'eventprime-event-calendar-management' ), $singular_organizer_text ),
                    'not_found'         => sprintf( esc_html__( 'No %s found', 'eventprime-event-calendar-management' ), $plural_organizer_text ),
	            ),
	            'show_ui'           => true,
	            'query_var'         => true,
	            'show_in_nav_menus' => true,
	            'show_in_menu'      => true,
                'hierarchical'      => true,
                'show_in_quick_edit' => false,
	            'capabilities'      => array(
	                'manage_terms' => 'manage_em_event_terms',
	                'edit_terms'   => 'edit_em_event_terms',
	                'delete_terms' => 'delete_em_event_terms',
	                'assign_terms' => 'assign_em_event_terms',
	            ),
	            'rewrite'           => array(
                    'slug'       => ep_get_seo_page_url( 'organizer' ),
                    //'ep_mask'  => EP_EM_EVENTS,
                    'with_front' => true
                ),
                //'show_in_rest'      => true,

            )
        );

        do_action( 'eventprime_after_register_taxonomy' );
	}

	/**
	 * Register core post types.
	 */
	public static function register_post_types() {
		if ( ! is_blog_installed() || post_type_exists( 'em_event' ) ) {
		    return;
		}

        do_action( 'eventprime_register_post_type' );

        $support = array('title', 'editor', 'thumbnail', 'custom-fields', 'publicize', 'wpcom-markdown', 'comments');

		register_post_type(
            'em_event', array(
                'labels'              => array(
                    'name'                  => __( 'Events', 'eventprime-event-calendar-management' ),
                    'singular_name'         => __( 'Event', 'eventprime-event-calendar-management' ),
                    'add_new'               => __( 'Add New', 'eventprime-event-calendar-management' ),
                    'add_new_item'          => __( 'Add New Event', 'eventprime-event-calendar-management' ),
                    'edit'                  => __( 'Edit', 'eventprime-event-calendar-management' ),
                    'edit_item'             => __( 'Edit Event', 'eventprime-event-calendar-management' ),
                    'new_item'              => __( 'New Event', 'eventprime-event-calendar-management' ),
                    'view'                  => __( 'View Event', 'eventprime-event-calendar-management' ),
                    'view_item'             => __( 'View Event', 'eventprime-event-calendar-management' ),
                    'not_found'             => __( 'No Events found', 'eventprime-event-calendar-management' ),
                    'not_found_in_trash'    => __( 'No Events found in trash', 'eventprime-event-calendar-management' ),
                    'featured_image'        => __( 'Event Image', 'eventprime-event-calendar-management' ),
                    'set_featured_image'    => __( 'Set event image', 'eventprime-event-calendar-management' ),
                    'remove_featured_image' => __( 'Remove event image', 'eventprime-event-calendar-management' ),
                    'use_featured_image'    => __( 'Use as event image', 'eventprime-event-calendar-management' ),
                    'menu_name'             => __( 'All Events', 'eventprime-event-calendar-management'  ),
                    'search_items'          => __( 'Search Event', 'eventprime-event-calendar-management'  ),
                ),
                'description'         => __( 'Here you can add new events.', 'eventprime-event-calendar-management' ),
                'public'              => true,
                'publicly_queryable'  => true,
                'show_ui'             => true,
                'show_in_nav_menus'   => true,
		        'show_in_menu'        => true,
                'has_archive'         => false,
                'capability_type'     => 'em_event',
                'map_meta_cap'        => true,
                'exclude_from_search' => false,
                'hierarchical'        => false,
                'query_var'           => true,
		        'menu_icon'           => 'dashicons-tickets-alt',
                'supports'            => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'publicize', 'wpcom-markdown', 'comments' ),
                'rewrite'             => array(
                    'slug'       => ep_get_seo_page_url( 'event' ),
                    'with_front' => true
                ),
                //'show_in_rest'        => true,
            )
		);

        $plural_performer_text = ep_global_settings_button_title( 'Performers' );
        $singular_performer_text = ep_global_settings_button_title( 'Performer' );

        register_post_type(
            'em_performer', array(
                'labels' => array(
                    'name'                  => $plural_performer_text,
                    'singular_name'         => $singular_performer_text,
                    'add_new'               => sprintf( esc_html__( 'Add %s', 'eventprime-event-calendar-management' ), $singular_performer_text ),
                    'add_new_item'          => sprintf( esc_html__( 'Add New %s', 'eventprime-event-calendar-management' ), $singular_performer_text ),
                    'edit'                  => sprintf( esc_html__( 'Edit %s', 'eventprime-event-calendar-management' ), $singular_performer_text ),
                    'edit_item'             => sprintf( esc_html__( 'Edit %s', 'eventprime-event-calendar-management' ), $singular_performer_text ),
                    'new_item'              => sprintf( esc_html__( 'New %s', 'eventprime-event-calendar-management' ), $singular_performer_text ),
                    'view'                  => sprintf( esc_html__( 'View %s', 'eventprime-event-calendar-management' ), $singular_performer_text ),
                    'view_item'             => sprintf( esc_html__( 'View %s', 'eventprime-event-calendar-management' ), $singular_performer_text ),
                    'not_found'             => sprintf( esc_html__( 'No %s found', 'eventprime-event-calendar-management' ), $singular_performer_text ),
                    'not_found_in_trash'    => sprintf( esc_html__( 'No %s found in trash', 'eventprime-event-calendar-management' ), $singular_performer_text ),
                    'featured_image'        => sprintf( esc_html__( '%s Image', 'eventprime-event-calendar-management' ), $singular_performer_text ),
                    'set_featured_image'    => sprintf( esc_html__( 'Set %s image', 'eventprime-event-calendar-management' ), $singular_performer_text ),
                    'remove_featured_image' => sprintf( esc_html__( 'Remove %s image', 'eventprime-event-calendar-management' ), $singular_performer_text ),
                    'use_featured_image'    => sprintf( esc_html__( 'Use as %s image', 'eventprime-event-calendar-management' ), $singular_performer_text ),
                    'menu_name'             => $plural_performer_text,
                ),
                'description' => sprintf( esc_html__( 'Here you can add new %s.', 'eventprime-event-calendar-management' ), strtolower( $plural_performer_text ) ),
                'public'              => true,
                'publicly_queryable'  => true,
                'show_ui'             => true,
                'show_in_nav_menus'   => true,
				'show_in_menu'        => 'edit.php?post_type=em_event',
                'has_archive'         => false,
                'map_meta_cap'        => true,
                'exclude_from_search' => false,
                'hierarchical'        => false,
                'query_var'           => true,
				'menu_icon'           => 'dashicons-businessperson',
                'supports'            => $support,
                'capability_type'     => 'em_performer',
                'rewrite'             => array(
                    'slug'       => ep_get_seo_page_url( 'performer' ),
                    'with_front' => true
                ),
            )
		);

		register_post_type(
            'em_booking', array(
                'labels'              => array(
                    'name'                  => __( 'Bookings', 'eventprime-event-calendar-management' ),
                    'singular_name'         => __( 'Booking', 'eventprime-event-calendar-management' ),
                    'add_new'               => __( 'Add Booking', 'eventprime-event-calendar-management' ),
                    'add_new_item'          => __( 'Add New Booking', 'eventprime-event-calendar-management' ),
                    'edit'                  => __( 'Edit', 'eventprime-event-calendar-management' ),
                    'edit_item'             => __( 'Edit Booking', 'eventprime-event-calendar-management' ),
                    'new_item'              => __( 'New Booking', 'eventprime-event-calendar-management' ),
                    'view'                  => __( 'View Booking', 'eventprime-event-calendar-management' ),
                    'view_item'             => __( 'View Booking', 'eventprime-event-calendar-management' ),
                    'not_found'             => __( 'No Booking found', 'eventprime-event-calendar-management' ),
                    'not_found_in_trash'    => __( 'No Booking found in trash', 'eventprime-event-calendar-management' ),
                    'featured_image'        => __( 'Booking Image', 'eventprime-event-calendar-management' ),
                    'set_featured_image'    => __( 'Set Booking image', 'eventprime-event-calendar-management' ),
                    'remove_featured_image' => __( 'Remove Booking image', 'eventprime-event-calendar-management' ),
                    'use_featured_image'    => __( 'Use as Booking image', 'eventprime-event-calendar-management' ),
                    'menu_name'             => __( 'Bookings', 'eventprime-event-calendar-management' ),
                ),
                'description'         => __( 'Here you can add new bookings.', 'eventprime-event-calendar-management' ),
                'public'              => false,
                'publicly_queryable'  => false,
                'show_ui'             => true,
                'show_in_nav_menus'   => false,
		        'show_in_menu'        => 'edit.php?post_type=em_event',
                'has_archive'         => false,
                'map_meta_cap'        => false,
                'exclude_from_search' => false,
                'hierarchical'        => false,
                'query_var'           => false,
                'supports'            => $support,
                'show_in_nav_menus'   => false,
                'capability_type'     => 'em_booking',
                'capabilities'        => array(
                    'create_posts' => false,
                ),
                'rewrite'             => array(
                    'slug'       => 'booking',
                    'with_front' => true
                ),
            )
		);

        do_action( 'eventprime_after_register_post_type' );

		flush_rewrite_rules();
	}

	/**
	 * Register our custom post statuses, used for event status.
	 */
	public static function register_post_status() {
		register_post_status('emexpired', array(
			'label'                     => __( 'EM Expired', 'Event status', 'eventprime-event-calendar-management' ),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'EM Expired <span class="count">(%s)</span>', 'EM Expired <span class="count">(%s)</span>', 'eventprime-event-calendar-management' )
		));

		register_post_status('expired', array(
			'label'                     => __( 'Expired', 'Event status', 'eventprime-event-calendar-management' ),
			'public'                    => true,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Expired <span class="count">(%s)</span>', 'Expired <span class="count">(%s)</span>', 'eventprime-event-calendar-management' )
		));

		register_post_status('cancelled', array(
			'label'                     => _x( 'Cancelled', 'Event status', 'eventprime-event-calendar-management'),
			'public'                    => false,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Cancelled <span class="count">(%s)</span>', 'Cancelled <span class="count">(%s)</span>', 'eventprime-event-calendar-management' )
		));

		register_post_status('pending', array(
			'label'                     => _x( 'Pending', 'Event status', 'eventprime-event-calendar-management'),
			'public'                    => false,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Pending <span class="count">(%s)</span>', 'Pending <span class="count">(%s)</span>', 'eventprime-event-calendar-management' )
		));

		register_post_status('refunded', array(
			'label'                     => _x( 'Refunded', 'Event status', 'eventprime-event-calendar-management'),
			'public'                    => false,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Refunded <span class="count">(%s)</span>', 'Refunded <span class="count">(%s)</span>', 'eventprime-event-calendar-management' )
		));
		
		register_post_status('completed', array(
			'label'                     => _x( 'Completed', 'Event status', 'eventprime-event-calendar-management'),
			'public'                    => false,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'Completed <span class="count">(%s)</span>', 'Completed <span class="count">(%s)</span>', 'eventprime-event-calendar-management' )
		));
	}

}

EventM_Post_types::init();