<?php
namespace Skt_Addons_Elementor\Elementor;

defined( 'ABSPATH' ) || die();

use Exception;

class Select2_Handler {

	public static function init() {
		add_action( 'wp_ajax_skt_addons_elementor_process_dynamic_select', [ __CLASS__, 'process_request' ] );
	}

	protected static function validate_reqeust() {
		$nonce = ! empty( $_REQUEST['nonce'] ) ? $_REQUEST['nonce'] : '';

		if ( ! wp_verify_nonce( $nonce, 'skt_addons_elementor_editor_nonce' ) ) {
			throw new Exception( 'Invalid request' );
		}

		if ( ! current_user_can( 'edit_posts' ) ) {
			throw new Exception( 'Unauthorized request' );
		}
	}

	public static function process_request() {
		try {
			self::validate_reqeust();

			$object_type = sanitize_text_field(! empty( $_REQUEST['object_type'] ) ? trim( $_REQUEST['object_type'] ) : '');

			if ( ! in_array( $object_type, [ 'post', 'term', 'user', 'mailchimp_list' ], true ) ) {
				throw new Exception( 'Invalid object type' );
			}

			$response = [];

			if ( $object_type === 'post' ) {
				$response = self::process_post();
			}

			if ( $object_type === 'term' ) {
				$response = self::process_term();
			}

			if ( $object_type === 'mailchimp_list' ) {
				$response = self::process_mailchimp_list();
			}

			wp_send_json_success( $response );
		} catch( Exception $e ) {
			wp_send_json_error( $e->getMessage() );
		}
	}

	public static function process_post() {
		$post_type    = sanitize_text_field(! empty( $_REQUEST['post_type'] ) ? $_REQUEST['post_type'] : 'any');
		$query_term   = sanitize_text_field(! empty( $_REQUEST['query_term'] ) ? $_REQUEST['query_term'] : '');
		$saved_values = sanitize_text_field(! empty( $_REQUEST['saved_values'] ) ? $_REQUEST['saved_values'] : 0);

		$args = [
			'post_type'        => $post_type,
			'suppress_filters' => false,
			'posts_per_page'   => 20,
			'orderby'          => 'title',
			'order'            => 'ASC',
			'post_status'      => 'publish',
		];

		if ( $query_term ) {
			$args['s'] = $query_term;
		}

		if ( $saved_values ) {
			$args['post__in'] = $saved_values;
			$args['posts_per_page'] = count( $saved_values );
			$args['orderby'] = 'post__in';
		}

		$posts = get_posts( $args );

		if ( empty( $posts ) ) {
			return [];
		}

		$out = [];

		foreach ( $posts as $post ) {
			// extra space is needed to maintain order in elementor control
			$out[" {$post->ID}"] = esc_html( $post->post_title );
		}

		return $out;
	}

	public static function process_term() {
		$term_taxonomy = sanitize_text_field(! empty( $_REQUEST['term_taxonomy'] ) ? $_REQUEST['term_taxonomy'] : '');
		$query_term    = sanitize_text_field(! empty( $_REQUEST['query_term'] ) ? $_REQUEST['query_term'] : '');
		$saved_values  = sanitize_text_field(! empty( $_REQUEST['saved_values'] ) ? $_REQUEST['saved_values'] : 0);

		if ( empty( $term_taxonomy ) ) {
			throw new Exception( 'Invalid taxonomy' );
		}

		$args = [
			'taxonomy'   => $term_taxonomy,
			'hide_empty' => false,
			'orderby'    => 'name',
			'order'      => 'ASC',
			'number'     => 20,
		];

		if ( $query_term ) {
			$args['search'] = $query_term;
			$args['count'] = true;
		}

		if ( $saved_values ) {
			$args['include'] = $saved_values;
			$args['number']  = count( $saved_values );
			$args['orderby'] = 'include';
		}

		$terms = get_terms( $args );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return [];
		}

		$out = [];

		foreach ( $terms as $term ) {
			$title = ! empty( $query_term ) ? "{$term->name} ({$term->count})" : $term->name;
			// extra space is needed to maintain order in elementor control
			$out[" {$term->term_id}"] = $title;
		}

		return $out;
	}

	public static function process_mailchimp_list() {
		$choose_api = sanitize_text_field(! empty( $_REQUEST['mailchimp_api_choose'] ) ? $_REQUEST['mailchimp_api_choose'] : '');
		$global_api = sanitize_text_field(! empty( $_REQUEST['global_api'] ) ? $_REQUEST['global_api'] : '');
		$custom_api = sanitize_text_field(! empty( $_REQUEST['mailchimp_api'] ) ? $_REQUEST['mailchimp_api'] : $global_api);
		
		$saved_values  = sanitize_text_field(! empty( $_REQUEST['saved_values'] ) ? $_REQUEST['saved_values'] : 0);

		if ( empty( $custom_api ) && empty( $global_api ) ) {
			throw new Exception( 'Invalid taxonomy' );
		}

		$current_api = $global_api;

        if($choose_api == 'custom') {
            $current_api = $custom_api;
        }

		if(!class_exists('Skt_Addons_Elementor\Elementor\Widget\Mailchimp\Mailchimp_api')) {
			include_once SKT_ADDONS_ELEMENTOR_DIR_PATH . 'widgets/mailchimp/mailchimp-api.php';
		}

		$options = Widget\Mailchimp\Mailchimp_api::get_mailchimp_lists($current_api);

		if ( $saved_values  ){
			return (array_key_exists($saved_values[0], $options)? [ $saved_values[0] => $options[ $saved_values[0] ] ]: [] );
		}else{
			return $options;
		}

	}
}

Select2_Handler::init();