<?php
/**
 *  Endpoints for general usage.
 *
 * @package SurferSEO
 * @link https://surferseo.com
 */

namespace SurferSEO\Surfer;

use SurferSEO\Surferseo;
use SurferSEO\Surfer\Integrations\Integrations;
use SurferSEO\Surfer\Content_Parsers\Parsers_Controller;
use WP_REST_Response;

/**
 * Object to reach Surfer endpoints with general usage.
 */
class Surfer_General_Endpoints {

	/**
	 * Object to manager content parsing for different editors.
	 *
	 * @var Parsers_Controller
	 */
	protected $content_parser = null;

	/**
	 * Object construct.
	 */
	public function __construct() {

		$this->content_parser = new Parsers_Controller();

		add_action( 'init', array( $this, 'register_request_to_endpoints' ) );
	}

	/**
	 * Ajax points, that can be reachable from React front-end and make requests to Surfer.
	 */
	public function register_request_to_endpoints() {

		add_filter( 'wp_ajax_surfer_get_user_drafts', array( $this, 'get_user_drafts' ) );
		add_filter( 'wp_ajax_surfer_get_user_credits', array( $this, 'get_user_credits' ) );
	}

	/**
	 * Returns user drafts from Surfer.
	 */
	public function get_user_drafts() {

		$json = file_get_contents( 'php://input' );
		$data = json_decode( $json );

		if ( ! surfer_validate_custom_request( $data->_surfer_nonce ) ) {
			echo wp_json_encode( array( 'message' => 'Security check failed.' ) );
			wp_die();
		}

		$query = isset( $data->query ) ? $data->query : '';

		$params = array(
			'query_keyword' => $query,
		);

		list(
			'code'     => $code,
			'response' => $response,
		) = Surfer()->get_surfer()->make_surfer_request( '/get_user_drafts', $params );

		if ( 200 === $code || 201 === $code ) {

			$drafts = array();

			foreach ( $response['drafts'] as $draft ) {
				$drafts[] = array(
					'draftId'               => $draft['id'],
					'title'                 => trim( wp_strip_all_tags( isset( $draft['title'] ) ? $draft['title'] : $this->content_parser->parse_only_title( $draft['content'] ) ) ),
					'contentScore'          => $this->get_content_score( $draft ),
					'keyword'               => $draft['keyword']['item'],
					'keywords'              => $this->put_keywords_in_array( $draft['keywords'] ),
					'location'              => $draft['scrape']['location'],
					'folderName'            => isset( $draft['folder'] ) ? $draft['folder'] : null,
					'editedInSurferDate'    => gmdate( 'd M Y H:i:s', strtotime( $draft['updated_at'] ) ),
					'editedInWordPressDate' => $this->get_last_edition_date_if_post_synced( $draft ),
					'permalinkHash'         => $draft['permalink_hash'],
					'is_ai_generated'       => $this->draft_is_ai_generated( $draft ),
					'is_wp_connected'       => $this->draft_is_wp_connected( $draft ),
				);
			}

			$response['drafts'] = $drafts;
		}

		echo wp_json_encode( $response );
		wp_die();
	}

	/**
	 * Gets last edition date of post if it is synced with Surfer.
	 *
	 * @param array $draft - Draft from Surfer.
	 * @return string|null
	 */
	private function get_last_edition_date_if_post_synced( $draft ) {

		if ( $this->draft_is_wp_connected( $draft ) ) {
			$post_id = $draft['connected_wordpress_post']['wp_post_id'];
			$post    = get_post( $post_id );

			if ( $post ) {
				return gmdate( 'd M Y H:i:s', strtotime( $post->post_modified ) );
			}
		}

		return gmdate( 'd M Y H:i:s' );
	}

	/**
	 * Gets content score from draft.
	 *
	 * @param array $draft - Draft from Surfer.
	 * @return int
	 */
	private function get_content_score( $draft ) {

		if ( isset( $draft['progress_snapshots'] ) && count( $draft['progress_snapshots'] ) > 0 ) {
			foreach ( $draft['progress_snapshots'] as $snapshot ) {
				if ( 'content_score' === $snapshot['name'] ) {
					return $snapshot['value'];
				}
			}
		}

		return 0;
	}

	/**
	 * Puts keywords from Surfer object to simple array.
	 *
	 * @param array $keywords - Keywords from Surfer.
	 * @return array
	 */
	private function put_keywords_in_array( $keywords ) {

		$keywords_array = array();

		foreach ( $keywords as $keyword ) {
			$keywords_array[] = $keyword['item'];
		}

		return $keywords_array;
	}

	/**
	 * Checks if draft is connected by AI
	 *
	 * @param array $draft - Draft from Surfer.
	 * @return bool
	 */
	private function draft_is_ai_generated( $draft ) {

		if ( isset( $draft['active_draft_article'] ) && ( is_object( $draft['active_draft_article'] ) || is_array( $draft['active_draft_article'] ) ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Checks if draft is connected to WP
	 *
	 * @param array $draft - Draft from Surfer.
	 * @return array
	 */
	private function draft_is_wp_connected( $draft ) {

		if ( isset( $draft['connected_wordpress_post'] ) && isset( $draft['connected_wordpress_post']['id'] ) && $draft['connected_wordpress_post']['id'] > 0 ) {
			return $draft['connected_wordpress_post'];
		}

		return false;
	}

	/**
	 * Returns number of available credits for the user.
	 *
	 * @return void
	 */
	public function get_user_credits() {

		$json = file_get_contents( 'php://input' );
		$data = json_decode( $json );

		if ( ! surfer_validate_custom_request( $data->_surfer_nonce ) ) {
			echo wp_json_encode( array( 'message' => 'Security check failed.' ) );
			wp_die();
		}

		list(
			'code'     => $code,
			'response' => $response,
		) = Surfer()->get_surfer()->make_surfer_request( '/get_organization_credits', array(), 'GET' );

		echo wp_json_encode( $response );
		wp_die();
	}
}
