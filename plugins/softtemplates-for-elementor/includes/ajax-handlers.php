<?php
/**
 * Class description
 *
 * @package   package_name
 * @author    SoftHopper
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Soft_template_Core_Ajax_Handlers' ) ) {

	/**
	 * Define Soft_template_Core_Ajax_Handlers class
	 */
	class Soft_template_Core_Ajax_Handlers {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Constructor for the class
		 */
		public function __construct() {

			// Register private actions
			$priv_actions = array(
				'soft_template_search_posts' => array( $this, 'search_posts' ),
				'soft_template_search_pages' => array( $this, 'search_pages' ),
				'soft_template_search_cats'  => array( $this, 'search_cats' ),
				'soft_template_search_tags'  => array( $this, 'search_tags' ),
				'soft_template_search_terms' => array( $this, 'search_terms' ),
			);

			foreach ( $priv_actions as $tag => $callback ) {
				add_action( 'wp_ajax_' . $tag, $callback );
			}
		}



		/**
		 * Serch page
		 *
		 * @return [type] [description]
		 */
		public function search_pages() {

			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_send_json( array() );
			}

			$query = isset( $_GET['q'] ) ? sanitize_key( $_GET['q'] ) : '';
			$ids   = isset( $_GET['ids'] ) ? sanitize_key( $_GET['ids'] ) : array();

			wp_send_json( array(
				'results' => Soft_template_Core_Utils::search_posts_by_type( 'page', $query, $ids ),
			) );

		}

		/**
		 * Serch post
		 *
		 * @return [type] [description]
		 */
		public function search_posts() {

			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_send_json( array() );
			}

			$query     = isset( $_GET['q'] ) ? sanitize_key( $_GET['q'] ) : '';
			$post_type = isset( $_GET['preview_post_type'] ) ? sanitize_key( $_GET['preview_post_type'] ) : 'post';
			$ids       = isset( $_GET['ids'] ) ? sanitize_key( $_GET['ids'] ) : array();

			wp_send_json( array(
				'results' => Soft_template_Core_Utils::search_posts_by_type( $post_type, $query, $ids ),
			) );

		}

		/**
		 * Serch category
		 *
		 * @return [type] [description]
		 */
		public function search_cats() {

			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_send_json( array() );
			}

			$query = isset( $_GET['q'] ) ? sanitize_key( $_GET['q'] ) : '';
			$ids   = isset( $_GET['ids'] ) ? sanitize_key( $_GET['ids'] ) : array();

			wp_send_json( array(
				'results' => Soft_template_Core_Utils::search_terms_by_tax( 'category', $query, $ids ),
			) );

		}

		/**
		 * Serch tag
		 *
		 * @return [type] [description]
		 */
		public function search_tags() {

			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_send_json( array() );
			}

			$query = isset( $_GET['q'] ) ? sanitize_key( $_GET['q'] ) : '';

			wp_send_json( array(
				'results' => Soft_template_Core_Utils::search_terms_by_tax( 'post_tag', $query, $ids ),
			) );

		}

		/**
		 * Serach terms from passed taxonomies
		 * @return [type] [description]
		 */
		public function search_terms() {

			if ( ! current_user_can( 'edit_posts' ) ) {
				wp_send_json( array() );
			}

			$query = isset( $_GET['q'] ) ? sanitize_key( $_GET['q'] ) : '';


			$tax = '';

			if ( isset( $_GET['conditions_archive-tax_tax'] ) ) {
				$tax = sanitize_key($_GET['conditions_archive-tax_tax']);
			}

			if ( isset( $_GET['conditions_singular-post-from-tax_tax'] ) ) {
				$tax = sanitize_key($_GET['conditions_singular-post-from-tax_tax']);
			}

			$tax = explode( ',', $tax );

			$ids = isset( $_GET['ids'] ) ? sanitize_key( $_GET['ids'] ) : array();

			wp_send_json( array(
				'results' => Soft_template_Core_Utils::search_terms_by_tax( $tax, $query, $ids ),
			) );

		}

	}

}
