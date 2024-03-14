<?php
/**
 * ZURCF7_Admin_Filter Class
 *
 * Handles the admin functionality.
 *
 * @package WordPress
 * @subpackage Plugin name
 * @since 1.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'ZURCF7_Admin_Filter' ) ) {

	/**
	 *  The ZURCF7_Admin_Filter Class
	 */
	class ZURCF7_Admin_Filter {

		function __construct() {
			add_filter( 'manage_edit-'.ZURCF7_POST_TYPE.'_sortable_columns',	array( $this, 'filter__manage_zurcf7_data_sortable_columns' ), 10, 3 );
			add_filter( 'manage_'.ZURCF7_POST_TYPE.'_posts_columns',			array( $this, 'filter__zurcf7_manage_data_posts_columns' ), 10, 3 );
			add_filter('post_row_actions', array( $this, 'filter__zurcf7_remove_actions_links' ), 10, 2);
		}

		/*
		######## #### ##       ######## ######## ########   ######
		##        ##  ##          ##    ##       ##     ## ##    ##
		##        ##  ##          ##    ##       ##     ## ##
		######    ##  ##          ##    ######   ########   ######
		##        ##  ##          ##    ##       ##   ##         ##
		##        ##  ##          ##    ##       ##    ##  ##    ##
		##       #### ########    ##    ######## ##     ##  ######
		*/

		/**
		 * Filter: manage_edit-zuserreg_data_sortable_columns
		 *
		 * - Used to add the sortable fields into "zuserreg_data" CPT
		 *
		 * @method filter__manage_zurcf7_data_sortable_columns
		 *
		 * @param  array $columns
		 *
		 * @return array
		 */
		function filter__manage_zurcf7_data_sortable_columns( $columns ) {
			$columns['total'] = '_total';
			return $columns;
		}

		/**
		 * Filter: manage_zuserreg_data_posts_columns
		 *
		 * - Used to add new column fields for the "zuserreg_data" CPT
		 *
		 * @method filter__zurcf7_manage_zuserreg_data_posts_columns
		 *
		 * @param  array $columns
		 *
		 * @return array
		 */
		function filter__zurcf7_manage_data_posts_columns( $columns ) {
			unset( $columns['date'] );
			$columns[ZURCF7_META_PREFIX.'user_login'] = 			__( 'User Name', 'zeal-user-reg-cf7' );
			$columns[ZURCF7_META_PREFIX.'role'] = 				__( 'User Role', 'zeal-user-reg-cf7' );
			$columns['date'] = 					__( 'Date', 'zeal-user-reg-cf7' );
			return $columns;
		}


		/**
		 * Filter: filter__zurcf7_remove_actions_links
		 *
		 * - Remove quick action links.
		 *
		 * @method filter__zurcf7_remove_actions_links
		 *
		 * @param  array $actions
		 *
		 * @return array
		 */
		function filter__zurcf7_remove_actions_links( $actions, $post ){
			
			if (isset($_GET['post_type']) && ($_GET['post_type'] === ZURCF7_POST_TYPE)) {
				unset($actions['inline hide-if-no-js']);
				unset($actions['trash']);
				
				// Build your links URL.
				$url = get_edit_post_link( $post->ID );
 
				// Maybe put in some extra arguments based on the post status.
				$edit_link = add_query_arg( array( 'action' => 'edit' ), $url );
				$actions = array(
					'edit' => sprintf( '<a href="%1$s">%2$s</a>',
					esc_url( $edit_link ),
					esc_html( __( 'View', 'zeal-user-reg-cf7' ) ) )
				);
			}

			return $actions;
		}
		

		/*
		######## ##     ## ##    ##  ######  ######## ####  #######  ##    ##  ######
		##       ##     ## ###   ## ##    ##    ##     ##  ##     ## ###   ## ##    ##
		##       ##     ## ####  ## ##          ##     ##  ##     ## ####  ## ##
		######   ##     ## ## ## ## ##          ##     ##  ##     ## ## ## ##  ######
		##       ##     ## ##  #### ##          ##     ##  ##     ## ##  ####       ##
		##       ##     ## ##   ### ##    ##    ##     ##  ##     ## ##   ### ##    ##
		##        #######  ##    ##  ######     ##    ####  #######  ##    ##  ######
		*/


	}

	add_action( 'plugins_loaded', function() {
		ZURCF7()->admin->filter = new ZURCF7_Admin_Filter;
	} );
}
