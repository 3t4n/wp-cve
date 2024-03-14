<?php
/**
 *  Object that handle importing content from:
 *  - Surfer
 *  - Google Docs
 *
 * @package SurferSEO
 * @link https://surferseo.com
 */

namespace SurferSEO\Surfer;

use SurferSEO\Surferseo;
use SurferSEO\Surfer\Content_Parsers\Parsers_Controller;


/**
 * Object that imports data from different sources into WordPress.
 */
class Content_Importer {

	/**
	 * Object to manager content parsing for different editors.
	 *
	 * @var Parsers_Controller
	 */
	protected $content_parser = null;


	/**
	 * Basic construct.
	 */
	public function __construct() {

		$this->content_parser = new Parsers_Controller();

		add_filter( 'init', array( $this, 'register_ajax_actions' ) );
	}

	/**
	 * Register ajax functions for React front-end.
	 */
	public function register_ajax_actions() {

		add_action( 'wp_ajax_surfer_pull_and_override_content', array( $this, 'pull_and_override_content' ) );
	}

	/**
	 * Save imported data in database.
	 *
	 * @param string $content - post content.
	 * @param array  $args    - array of optional params.
	 * @return int|WP_Error
	 */
	public function save_data_into_database( $content, $args = array() ) {

		$content = $this->content_parser->parse_content( $content );
		$title   = $this->content_parser->return_title();

		$data = array(
			'post_title'   => $title,
			'post_content' => $content,
		);

		if ( isset( $args['post_id'] ) && $args['post_id'] > 0 ) {

			$post_id    = $args['post_id'];
			$data['ID'] = $post_id;
			$post       = (array) get_post( $post_id );

			// WordPress set current date as default and we do not want to change publication date.
			if ( 'published' === $post['post_status'] ) {
				$data['post_date'] = $post['post_date'];
			}

			// Create copy of the post as a backup.
			unset( $post['ID'] );
			$post['post_status'] = 'surfer-backup';
			wp_insert_post( $post );

			$post_id = wp_update_post( $data );
		} else {
			$this->resolve_post_author( $args, $data );
			$this->resolve_post_status( $args, $data );
			$this->resolve_post_date( $args, $data );
			$this->resolve_post_permalink( $args, $data );
			$this->resolve_post_category( $args, $data );
			$this->resolve_post_tags( $args, $data );
			$this->resolve_post_meta_details( $args, $data );

			$post_id = wp_insert_post( $data );
		}

		if ( ! is_wp_error( $post_id ) && isset( $args['draft_id'] ) ) {
			update_post_meta( $post_id, 'surfer_draft_id', $args['draft_id'] );
			update_post_meta( $post_id, 'surfer_permalink_hash', isset( $args['permalink_hash'] ) ? $args['permalink_hash'] : '' );
			update_post_meta( $post_id, 'surfer_keywords', $args['keywords'] );
			update_post_meta( $post_id, 'surfer_location', $args['location'] );
			update_post_meta( $post_id, 'surfer_scrape_ready', true );
			update_post_meta( $post_id, 'surfer_last_post_update', round( microtime( true ) * 1000 ) );
			update_post_meta( $post_id, 'surfer_last_post_update_direction', 'from Surfer to WordPress' );
		}

		$this->content_parser->run_after_post_insert_actions( $post_id );

		return $post_id;
	}

	/**
	 * Fill $data array with proper attribute for post_author or leave empty to fill default.
	 *
	 * @param array $args - array of arguments pasted to request.
	 * @param array $data - pointer to array where we store data to put into post.
	 * @return void
	 */
	private function resolve_post_author( $args, &$data ) {

		if ( isset( $args['post_author'] ) && null !== $args['post_author'] ) {
			if ( is_numeric( $args['post_author'] ) && $args['post_author'] > 0 ) {
				$data['post_author'] = $args['post_author'];
			} else {
				$data['post_author'] = $this->get_user_id_by_login( $args['post_author'] );
			}
		} else {
			$default = Surfer()->get_surfer_settings()->get_option( 'content-importer', 'default_post_author', false );

			if ( false !== $default ) {
				$data['post_author'] = $default;
			}
		}
	}

	/**
	 * Fill $data array with proper attribute for post_status or leave empty to fill default.
	 *
	 * @param array $args - array of arguments pasted to request.
	 * @param array $data - pointer to array where we store data to put into post.
	 * @return void
	 */
	private function resolve_post_status( $args, &$data ) {

		$allowed_statuses = array(
			'published',
			'draft',
		);

		if ( isset( $args['post_status'] ) && in_array( $args['post_status'], $allowed_statuses, true ) ) {
			$data['post_status'] = $args['post_status'];
		} else {
			$default = Surferseo::get_instance()->get_surfer_settings()->get_option( 'content-importer', 'default_post_status', false );

			if ( false !== $default ) {
				$data['post_status'] = $default;
			}
		}
	}

	/**
	 * Fill $data array with proper attribute for post_date or leave empty to fill default.
	 *
	 * @param array $args - array of arguments pasted to request.
	 * @param array $data - pointer to array where we store data to put into post.
	 * @return void
	 */
	private function resolve_post_date( $args, &$data ) {

		if ( isset( $args['post_date'] ) && is_date( $args['post_date'] ) ) {
			$data['post_date'] = $args['post_date'];
		}
	}

	/**
	 * Fill $data array with proper attribute for post_name or leave empty to fill default.
	 *
	 * @param array $args - array of arguments pasted to request.
	 * @param array $data - pointer to array where we store data to put into post.
	 * @return void
	 */
	private function resolve_post_permalink( $args, &$data ) {

		if ( isset( $args['post_name'] ) && '' !== $args['post_name'] ) {
			$data['post_name'] = $args['post_name'];
		}
	}

	/**
	 * Fill $data array with proper attribute for post_category or leave empty to fill default.
	 *
	 * @param array $args - array of arguments pasted to request.
	 * @param array $data - pointer to array where we store data to put into post.
	 * @return void
	 */
	private function resolve_post_category( $args, &$data ) {

		if ( isset( $args['post_category'] ) && '' !== $args['post_category'] ) {
			$data['post_category'] = $args['post_category'];
		} else {
			$default = Surferseo::get_instance()->get_surfer_settings()->get_option( 'content-importer', 'default_category', false );

			if ( false !== $default ) {
				$data['post_category'] = array( $default );
			}
		}
	}

	/**
	 * Fill $data array with proper attribute for tags_input or leave empty to fill default.
	 *
	 * @param array $args - array of arguments pasted to request.
	 * @param array $data - pointer to array where we store data to put into post.
	 * @return void
	 */
	private function resolve_post_tags( $args, &$data ) {

		if ( isset( $args['tags_input'] ) && '' !== $args['tags_input'] ) {
			$data['tags_input'] = $args['tags_input'];
		} else {
			$default = Surferseo::get_instance()->get_surfer_settings()->get_option( 'content-importer', 'default_tags', false );

			if ( false !== $default ) {
				$data['tags_input'] = $default;
			}
		}
	}

	/**
	 * Fill the meta_title and meta_description if any SEO plugin is active.
	 *
	 * @param array $args - array of arguments pasted to request.
	 * @param array $data - pointer to array where we store data to put into post.
	 * @return void
	 */
	private function resolve_post_meta_details( $args, &$data ) {

		$seo_plugin_is_active = false;

		if ( ! isset( $data['meta_input'] ) ) {
			$data['meta_input'] = array();
		}

		// Yoast SEO is active.
		if ( surfer_check_if_plugins_is_active( 'wordpress-seo/wp-seo.php' ) ) {

			if ( isset( $args['meta_title'] ) && '' !== $args['meta_title'] ) {
				$data['meta_input']['_yoast_wpseo_title'] = $args['meta_title'];
			}

			if ( isset( $args['meta_description'] ) && '' !== $args['meta_description'] ) {
				$data['meta_input']['_yoast_wpseo_metadesc'] = $args['meta_description'];
			}

			$seo_plugin_is_active = true;
		}

		// All in One SEO is active.
		if ( surfer_check_if_plugins_is_active( 'all-in-one-seo-pack/all_in_one_seo_pack.php' ) ) {

			if ( isset( $args['meta_title'] ) && '' !== $args['meta_title'] ) {
				$data['meta_input']['_aioseo_title'] = $args['meta_title'];
			}

			if ( isset( $args['meta_description'] ) && '' !== $args['meta_description'] ) {
				$data['meta_input']['_aioseo_description'] = $args['meta_description'];
			}

			$seo_plugin_is_active = true;
		}

		// Rank Math SEO.
		if ( surfer_check_if_plugins_is_active( 'seo-by-rank-math/rank-math.php' ) ) {

			if ( isset( $args['meta_title'] ) && '' !== $args['meta_title'] ) {
				$data['meta_input']['rank_math_title'] = $args['meta_title'];
			}

			if ( isset( $args['meta_description'] ) && '' !== $args['meta_description'] ) {
				$data['meta_input']['rank_math_description'] = $args['meta_description'];
			}

			$seo_plugin_is_active = true;
		}

		// Save in Surfer Meta to display.
		if ( ! $seo_plugin_is_active ) {

			if ( isset( $args['meta_title'] ) && '' !== $args['meta_title'] ) {
				$data['meta_input']['_surferseo_title'] = $args['meta_title'];
			}

			if ( isset( $args['meta_description'] ) && '' !== $args['meta_description'] ) {
				$data['meta_input']['_surferseo_description'] = $args['meta_description'];
			}
		}
	}

	/**
	 * Extract h1 from content, to use it as post title.
	 *
	 * @param string $content - Content from Surfer.
	 * @return string
	 */
	private function get_title_from_content( $content ) {

		preg_match( '~<h1[^>]*>(.*?)</h1>~i', $content, $match );
		$title = $match[1];

		return $title;
	}


	/**
	 * Returns ID of the user with given name.
	 *
	 * @param string $login - login of the user.
	 * @return int
	 */
	private function get_user_id_by_login( $login = false ) {

		$user_id = 0;
		$user    = get_user_by( 'login', $login );

		if ( false !== $user ) {
			$user_id = get_option( 'surfer_auth_user', 0 );
		}

		return $user_id;
	}


	/**
	 * Checks if plugin is active even if default function is not loaded.
	 *
	 * @param string $plugin - plugin name to check.
	 * @return bool
	 */
	public function check_if_plugins_is_active( $plugin ) {

		if ( ! function_exists( 'is_plugin_active' ) ) {
			return in_array( $plugin, (array) get_option( 'active_plugins', array() ), true );
		} else {
			return is_plugin_active( $plugin );
		}
	}

		/**
		 * Gets post sync status from WordPress and Surfer.
		 */
	public function pull_and_override_content() {
		$json = file_get_contents( 'php://input' );
		$data = json_decode( $json );

		if ( ! surfer_validate_custom_request( $data->_surfer_nonce ) ) {
			echo wp_json_encode( array( 'message' => 'Security check failed.' ) );
			wp_die();
		}

		$draft_id            = isset( $data->draft_id ) ? intval( $data->draft_id ) : false;
		$post_id             = isset( $data->post_id ) ? intval( $data->post_id ) : false;
		$content_from_surfer = isset( $data->content_from_surfer ) ? $data->content_from_surfer : false;

		$params = array(
			'draft_id' => $draft_id,
			'post_id'  => $post_id,
		);

		list(
			'code'     => $code,
			'response' => $response,
		) = Surfer()->get_surfer()->make_surfer_request( '/update_last_sync_date', $params );

		if ( 200 === $code || 201 === $code ) {

			$args = array(
				'draft_id' => $draft_id,
				'post_id'  => $post_id,
			);
			$this->save_data_into_database( $content_from_surfer, $args );
		}

		echo wp_json_encode( $response );
		wp_die();
	}
}
