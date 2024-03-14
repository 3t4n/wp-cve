<?php
/**
 *  Object that exports content to Surfer.
 *
 * @package SurferSEO
 * @link https://surferseo.com
 */

namespace SurferSEO\Surfer;

use DOMDocument;
use SurferSEO\Surferseo;

/**
 * Content exporter object.
 */
class Content_Exporter {


	/**
	 * Object construct.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Init function.
	 */
	public function init() {
		add_filter( 'post_row_actions', array( $this, 'add_export_content_buttonto_posts_list' ), 10, 2 );
		add_filter( 'page_row_actions', array( $this, 'add_export_content_buttonto_posts_list' ), 10, 2 );

		add_filter( 'wp_ajax_surfer_create_content_editor', array( $this, 'create_content_editor' ) );
		add_filter( 'wp_ajax_surfer_update_content_editor', array( $this, 'update_content_editor' ) );
		add_filter( 'wp_ajax_surfer_remove_post_draft_connection', array( $this, 'remove_post_draft_connection' ) );
		add_filter( 'wp_ajax_surfer_check_draft_status', array( $this, 'check_draft_status' ) );
		add_filter( 'wp_ajax_surfer_get_locations', array( $this, 'get_locations' ) );
		add_filter( 'wp_ajax_surfer_get_post_sync_status', array( $this, 'get_post_sync_status' ) );
	}

	/**
	 * Add export content button to post/page list.
	 *
	 * @param array   $actions - actions array.
	 * @param WP_Post $post - post object.
	 */
	public function add_export_content_buttonto_posts_list( $actions, $post ) {
		$draft_id = get_post_meta( $post->ID, 'surfer_draft_id', true );

		if ( $draft_id ) {
			$actions['export_to_surfer'] = '<a href="' . Surfer()->get_surfer()->get_surfer_url() . '/drafts/' . intval( $draft_id ) . '" >' . __( 'Check in Surfer', 'surferseo' ) . '</a>';
		}

		return $actions;
	}

	/**
	 * Makes export content to Surfer.
	 */
	public function create_content_editor() {
		$json = file_get_contents( 'php://input' );
		$data = json_decode( $json );

		if ( ! surfer_validate_custom_request( $data->_surfer_nonce ) ) {
			echo wp_json_encode( array( 'message' => 'Security check failed.' ) );
			wp_die();
		}

		$keywords = isset( $data->keywords ) ? sanitize_text_field( wp_unslash( $data->keywords ) ) : false;
		$location = isset( $data->location ) ? sanitize_text_field( wp_unslash( $data->location ) ) : 'United States';
		$content  = isset( $data->content ) ? wp_kses_post( $data->content ) : false;
		$post_id  = isset( $data->post_id ) ? intval( $data->post_id ) : false;

		if ( false === $keywords || '' === $keywords || empty( $keywords ) ) {
			echo wp_json_encode( array( 'message' => 'You need to provide at least one keyword.' ) );
			wp_die();
		}

		if ( ! is_array( $keywords ) ) {
			$keywords = explode( ',', $keywords );
		}

		$params = array(
			'keywords'   => $keywords,
			'location'   => $location,
			'content'    => $content,
			'wp_post_id' => $post_id,
			'url'        => get_site_url(),
		);

		list(
			'code'     => $code,
			'response' => $response,
		) = Surfer()->get_surfer()->make_surfer_request( '/import_content', $params );

		if ( 200 === $code || 201 === $code ) {
			$this->save_post_surfer_details( $post_id, $params['keywords'], $params['location'], $response['id'], $response['permalink_hash'] );
		}

		echo wp_json_encode( $response );
		wp_die();
	}

	/**
	 * Makes update content to Surfer.
	 */
	public function update_content_editor() {
		$json = file_get_contents( 'php://input' );
		$data = json_decode( $json );

		if ( ! surfer_validate_custom_request( $data->_surfer_nonce ) ) {
			echo wp_json_encode( array( 'message' => 'Security check failed.' ) );
			wp_die();
		}

		$keywords = isset( $data->keywords ) ? $data->keywords : false;

		$content        = isset( $data->content ) ? wp_kses_post( $this->parse_content_for_surfer( $data->content ) ) : false;
		$draft_id       = isset( $data->draft_id ) ? intval( $data->draft_id ) : false;
		$post_id        = isset( $data->post_id ) ? intval( $data->post_id ) : false;
		$permalink_hash = isset( $data->permalink_hash ) ? sanitize_text_field( wp_unslash( $data->permalink_hash ) ) : false;
		$keywords       = is_array( $data->keywords ) ? array_map( 'sanitize_text_field', $data->keywords ) : sanitize_text_field( wp_unslash( $data->keywords ) );
		$location       = isset( $data->location ) ? sanitize_text_field( wp_unslash( $data->location ) ) : false;

		$params = array(
			'draft_id'   => $draft_id,
			'content'    => $content,
			'wp_post_id' => $post_id,
			'url'        => get_site_url(),
		);

		list(
			'code'     => $code,
			'response' => $response,
		) = Surfer()->get_surfer()->make_surfer_request( '/import_content_update', $params );

		if ( 200 === $code || 201 === $code ) {
			update_post_meta( $post_id, 'surfer_last_post_update', round( microtime( true ) * 1000 ) );
			update_post_meta( $post_id, 'surfer_last_post_update_direction', 'from WordPress to Surfer' );

			$this->save_post_surfer_details( $post_id, $keywords, $location, $draft_id, $permalink_hash );
		}

		echo wp_json_encode( $response );
		wp_die();
	}

	/**
	 * Saves Surfer details about post.
	 *
	 * @param int    $post_id - ID of the post.
	 * @param string $keyword - keyword for the post.
	 * @param string $location - location for the post.
	 * @param int    $draft_id - ID of the draft in Surfer.
	 * @param string $permalink_hash - hash of the post permalink.
	 * @return void
	 */
	private function save_post_surfer_details( $post_id, $keyword, $location, $draft_id, $permalink_hash ) {

		update_post_meta( $post_id, 'surfer_last_post_update', round( microtime( true ) * 1000 ) );
		update_post_meta( $post_id, 'surfer_last_post_update_direction', 'from WordPress to Surfer' );
		update_post_meta( $post_id, 'surfer_keywords', $keyword );
		update_post_meta( $post_id, 'surfer_location', $location );
		update_post_meta( $post_id, 'surfer_draft_id', $draft_id );
		update_post_meta( $post_id, 'surfer_permalink_hash', $permalink_hash );
	}

	/**
	 * Allows to check draft status.
	 *
	 * @return void
	 */
	public function check_draft_status() {
		$json = file_get_contents( 'php://input' );
		$data = json_decode( $json );

		if ( ! surfer_validate_custom_request( $data->_surfer_nonce ) ) {
			echo wp_json_encode( array( 'message' => 'Security check failed.' ) );
			wp_die();
		}

		$draft_id = isset( $data->draft_id ) ? intval( $data->draft_id ) : false;
		$post_id  = isset( $data->post_id ) ? intval( $data->post_id ) : false;

		$params = array(
			'draft_id' => $draft_id,
		);

		list(
			'code'     => $code,
			'response' => $response,
		) = Surfer()->get_surfer()->make_surfer_request( '/check_draft_status', $params );

		if ( 200 === $code || 201 === $code ) {
			update_post_meta( $post_id, 'surfer_scrape_ready', true );
		}

		echo wp_json_encode( $response );
		wp_die();
	}

	/**
	 * Parse content to match Surfer formatting, and keep whole HTML.
	 *
	 * @param string $content - content to parse.
	 * @return string
	 */
	private function parse_content_for_surfer( $content ) {

		$content = wp_unslash( $content );

		$doc = new DOMDocument();

		$utf8_fix_prefix = '<html><head><meta http-equiv="content-type" content="text/html; charset=utf-8" /></head><body>';
		$utf8_fix_suffix = '</body></html>';

		$doc->loadHTML( $utf8_fix_prefix . $content . $utf8_fix_suffix, LIBXML_HTML_NODEFDTD | LIBXML_SCHEMA_CREATE );

		$parsed_content = '';

		$this->parse_dom_node( $doc, $parsed_content );

		return $parsed_content;
	}

	/**
	 * Function interates by HTML tags in provided content.
	 *
	 * @param DOMDocument $parent_node - node to parse.
	 * @param string      $content     - reference to content variable, to store Gutenberg output.
	 * @return void
	 */
	private function parse_dom_node( $parent_node, &$content ) {
		// @codingStandardsIgnoreLine
		foreach ( $parent_node->childNodes as $node ) {

			// @codingStandardsIgnoreLine
			if ( in_array( $node->nodeName, array( 'html', 'body' ) ) ) {
				$this->parse_dom_node( $node, $content );
				break;
			}

			$node_content = $this->get_inner_html( $node );

			// We need to get IMGs from <p> tag, to allow Surfer to handle this.
			if ( strlen( $node_content ) > 0 && false !== strpos( $node_content, '<img' ) ) {
				$content .= $node_content;
			} else {
				// @codingStandardsIgnoreLine
				$content .= '<' . $node->nodeName . '>' . $node_content . '</' . $node->nodeName . '>';
			}
		}
	}

	/**
	 * Extract inner HTML for provided node.
	 *
	 * @param DOMElement $node - node element to parse.
	 * @return string
	 */
	private function get_inner_html( $node ) {
		$inner_html = '';

		// @codingStandardsIgnoreLine
		foreach ( $node->childNodes as $child ) {

			// @codingStandardsIgnoreLine
			$content = $child->ownerDocument->saveXML( $child );

			if ( '<li/>' !== $content ) {
				$inner_html .= $content;
			}
		}

		return $inner_html;
	}

	/**
	 * Returns available locations.
	 */
	public function get_locations() {
		$json = file_get_contents( 'php://input' );
		$data = json_decode( $json );

		if ( ! surfer_validate_custom_request( $data->_surfer_nonce ) ) {
			echo wp_json_encode( array( 'message' => 'Security check failed.' ) );
			wp_die();
		}

		echo wp_json_encode( Surfer()->get_surfer()->surfer_hardcoded_location() );
		wp_die();
	}

	/**
	 * Removes a draft connection.
	 */
	public function remove_post_draft_connection() {
		$json = file_get_contents( 'php://input' );
		$data = json_decode( $json );

		if ( ! surfer_validate_custom_request( $data->_surfer_nonce ) ) {
			echo wp_json_encode( array( 'message' => 'Security check failed.' ) );
			wp_die();
		}

		$post_id  = isset( $data->post_id ) ? intval( $data->post_id ) : false;
		$draft_id = isset( $data->draft_id ) ? intval( $data->draft_id ) : false;

		$params = array(
			'draft_id'   => $draft_id,
			'wp_post_id' => $post_id,
			'url'        => get_site_url(),
		);

		list(
			'code'     => $code,
			'response' => $response,
		) = Surfer()->get_surfer()->make_surfer_request( '/disconnect_draft', $params );

		delete_post_meta( $post_id, 'surfer_draft_id' );
		delete_post_meta( $post_id, 'surfer_scrape_ready' );
		delete_post_meta( $post_id, 'surfer_permalink_hash' );
		delete_post_meta( $post_id, 'surfer_last_post_update' );
		delete_post_meta( $post_id, 'surfer_last_post_update_direction' );
		delete_post_meta( $post_id, 'surfer_keywords' );
		delete_post_meta( $post_id, 'surfer_location' );

		$response = array(
			'connection_removed' => true,
			'surfer_response'    => $response,
		);

		echo wp_json_encode( $response );
		wp_die();
	}

	/**
	 * Gets post sync status from WordPress and Surfer.
	 */
	public function get_post_sync_status() {
		$json = file_get_contents( 'php://input' );
		$data = json_decode( $json );

		if ( ! surfer_validate_custom_request( $data->_surfer_nonce ) ) {
			echo wp_json_encode( array( 'message' => 'Security check failed.' ) );
			wp_die();
		}

		$draft_id = isset( $data->draft_id ) ? intval( $data->draft_id ) : false;
		$post_id  = isset( $data->post_id ) ? intval( $data->post_id ) : false;

		$params = array(
			'draft_id' => $draft_id,
		);

		list(
			'code'     => $code,
			'response' => $response,
		) = Surfer()->get_surfer()->make_surfer_request( '/get_draft_sync_status', $params );

		if ( 200 === $code || 201 === $code ) {
			$response['code']                    = $code;
			$response['wp_last_update_date']     = get_the_modified_date( 'M d, Y H:i:s', $post_id );
			$response['keywords']                = get_post_meta( $post_id, 'surfer_keywords', true );
			$response['location']                = get_post_meta( $post_id, 'surfer_location', true );
			$response['surfer_last_update_date'] = gmdate( 'M d, Y H:i:s', strtotime( $response['surfer_last_update_date'] ) );

			$wp_last_sync_date = get_post_meta( $post_id, 'surfer_last_post_update', true );

			// Should be the same, but we want to be sure!
			if ( $response['last_sync_date'] <= $wp_last_sync_date ) {
				$response['last_sync_date']      = $wp_last_sync_date;
				$response['last_sync_direction'] = get_post_meta( $post_id, 'surfer_last_post_update_direction', true );
			}
		}

		echo wp_json_encode( $response );
		wp_die();
	}
}
