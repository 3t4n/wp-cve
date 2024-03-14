<?php
/**
 * This file contains a class for registering the Reference post type.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes/extensions
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This class registers the Reference post type and its statuses.
 */
class Nelio_Content_Reference_Post_Type_Register {

	/**
	 * The single instance of this class.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    Nelio_Content
	 */
	protected static $instance;

	/**
	 * Returns the single instance of this class.
	 *
	 * @return Nelio_Content the single instance of this class.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}//end if

		return self::$instance;

	}//end instance()

	public function init() {

		add_action( 'init', array( $this, 'register_post_type' ), 5 );
		add_action( 'init', array( $this, 'register_post_statuses' ), 9 );

		add_filter( 'user_has_cap', array( $this, 'set_user_capabilities' ), 10, 4 );

	}//end init()

	public function register_post_type() {

		if ( post_type_exists( 'nc_reference' ) ) {
			return;
		}//end if

		/**
		 * This action fires right before registering the "Reference" and
		 * "Reference Author" post types.
		 *
		 * @since 1.0.0
		 */
		do_action( 'nelio_content_register_post_types' );

		register_post_type(
			'nc_reference',
			/**
			 * Filters the args of the nc_reference post type.
			 *
			 * @since 1.0.0
			 *
			 * @param array $args The arguments, as defined in WordPress function register_post_type.
			 */
			apply_filters(
				'nelio_content_register_reference_post_type',
				array(
					'labels'          => array(
						'name'               => _x( 'External References', 'text', 'nelio-content' ),
						'singular_name'      => _x( 'Reference', 'text', 'nelio-content' ),
						'menu_name'          => _x( 'References', 'text', 'nelio-content' ),
						'all_items'          => _x( 'References', 'text', 'nelio-content' ),
						'add_new'            => _x( 'Add Reference', 'command', 'nelio-content' ),
						'add_new_item'       => _x( 'Add Reference', 'text', 'nelio-content' ),
						'edit_item'          => _x( 'Edit Reference', 'text', 'nelio-content' ),
						'new_item'           => _x( 'New Reference', 'text', 'nelio-content' ),
						'search_items'       => _x( 'Search References', 'command', 'nelio-content' ),
						'not_found'          => _x( 'No references found', 'text', 'nelio-content' ),
						'not_found_in_trash' => _x( 'No references found in trash', 'text', 'nelio-content' ),
					),
					'can_export'      => true,
					'capability_type' => 'nc_reference',
					'hierarchical'    => false,
					'map_meta_cap'    => true,
					'public'          => false,
					'query_var'       => false,
					'rewrite'         => false,
					'show_in_menu'    => 'nelio-content',
					'show_ui'         => false,
					'supports'        => array( 'title', 'author' ),
				)
			)
		);

	}//end register_post_type()

	public function register_post_statuses() {

		$args = array(
			'protected'   => true,
			'label'       => _x( 'Pending', 'text (reference)', 'nelio-content' ),
			/* translators: a number */
			'label_count' => _nx_noop( 'Pending <span class="count">(%s)</span>', 'Pending <span class="count">(%s)</span>', 'text (reference)', 'nelio-content' ),
		);
		register_post_status( 'nc_pending', $args );

		$args = array(
			'protected'   => true,
			'label'       => _x( 'Improvable', 'text (reference)', 'nelio-content' ),
			/* translators: a number */
			'label_count' => _nx_noop( 'Improvable <span class="count">(%s)</span>', 'Improvable <span class="count">(%s)</span>', 'text (reference)', 'nelio-content' ),
		);
		register_post_status( 'nc_improvable', $args );

		$args = array(
			'protected'   => true,
			'label'       => _x( 'Complete', 'text (reference)', 'nelio-content' ),
			/* translators: a number */
			'label_count' => _nx_noop( 'Complete <span class="count">(%s)</span>', 'Complete <span class="count">(%s)</span>', 'text (reference)', 'nelio-content' ),
		);
		register_post_status( 'nc_complete', $args );

		$args = array(
			'protected'   => true,
			'label'       => _x( 'Broken', 'text (reference)', 'nelio-content' ),
			/* translators: a number */
			'label_count' => _nx_noop( 'Broken <span class="count">(%s)</span>', 'Broken <span class="count">(%s)</span>', 'text (reference)', 'nelio-content' ),
		);
		register_post_status( 'nc_broken', $args );

		$args = array(
			'protected'   => true,
			'label'       => _x( 'Check Required', 'text (reference)', 'nelio-content' ),
			/* translators: a number */
			'label_count' => _nx_noop( 'Check Required <span class="count">(%s)</span>', 'Check Required <span class="count">(%s)</span>', 'text (reference)', 'nelio-content' ),
		);
		register_post_status( 'nc_check', $args );

	}//end register_post_statuses()

	public function set_user_capabilities( $capabilities, $_, $__, $user ) {

		$capabilities = array_filter(
			$capabilities,
			function( $cap ) {
				return false === strpos( $cap, 'nc_reference' );
			},
			ARRAY_FILTER_USE_KEY
		);

		if ( get_current_user_id() !== $user->ID ) {
			return $capabilities;
		}//end if

		remove_filter( 'user_has_cap', array( $this, 'set_user_capabilities' ), 10, 4 );
		if ( nc_can_current_user_use_plugin() ) {
			$reference_capabilities = array(
				'create_nc_references',
				'delete_nc_reference',
				'delete_nc_references',
				'delete_others_nc_references',
				'delete_private_nc_references',
				'delete_published_nc_references',
				'edit_nc_reference',
				'edit_nc_references',
				'edit_others_nc_reference',
				'edit_others_nc_references',
				'edit_private_nc_references',
				'edit_published_nc_references',
				'publish_nc_references',
				'read_nc_reference',
				'read_private_nc_references',
			);
			foreach ( $reference_capabilities as $cap ) {
				$capabilities[ $cap ] = true;
			}//end foreach
		}//end if
		add_filter( 'user_has_cap', array( $this, 'set_user_capabilities' ), 10, 4 );

		return $capabilities;
	}//end set_user_capabilities()

}//end class

