<?php 
if ( ! function_exists( 'plz_get_forms_list' ) ) :
	function plz_get_forms_list ( $args = null, $filters = null ) {
		if ( ! $args || ! $filters ) :
			$args = 'sort_by=title&sort_dir=asc&page=1&per_page=20';
			$filters = [ 'sort_by' => 'title', 'sort_dir' => 'asc', 'page' => '1', 'per_page' => '20' ];
		endif;

		$timestamp = time();
		$signature = plz_get_signature_api( 'content_web_forms', $timestamp, $args );

		if ( $signature && ! empty( $signature ) ) :
			$authentification = get_option( 'plz_configuration_authentification_options' );
			$args = [ 'headers' => [ 'X-AUTHORIZATION' => 'plezi id=' . $authentification['plz_authentification_public_key'] . ',algo=hmac-sha256,nonce=WP-PLEZI,signature=' . $signature . ',ts=' . $timestamp ], 'body' => $filters ];
			$result = wp_remote_get( 'https://brain.plezi.co/api/v1/content_web_forms', $args );
			$result_decode = json_decode( wp_remote_retrieve_body( $result ) );

			if ( $result_decode && isset( $result_decode->data ) && is_array( $result_decode->data ) && ! empty( $result_decode->data ) ) :
				$forms = $result_decode->data;
				$metas = $result_decode->meta;

				foreach ( $forms as $form ) :
					$date = new DateTime( $form->attributes->created_at );

					$form->attributes->custom_title = $form->attributes->title . ' - ' . __( 'Created on ', 'plezi-for-wordpress' ) . date_i18n( get_option( 'date_format' ), $date->getTimestamp() ) . ' - ' . date_i18n( get_option( 'time_format' ), $date->getTimestamp() );
				endforeach;

				if ( isset( $params['default'] ) && ! empty( $params['default'] ) && $params['default'] ) :
					array_unshift( $forms, [ 'id' => '', 'type' => 'default', 'attributes' => [ 'id' => '', 'title' => __( 'Choose a Plezi form', 'plezi-for-wordpress' ), 'custom_title' => __( 'Choose a Plezi form', 'plezi-for-wordpress' ) ] ] );
				endif;

  				return [ 'message' => __( 'Forms list retrieved.', 'plezi-for-wordpress' ), 'list' => $forms, 'metas' => $metas ];
			endif;

			return [ 'message' => __( 'Forms list not retrieved.', 'plezi-for-wordpress' ), 'error' => __( 'Call API issue.', 'plezi-for-wordpress' ) ];
		else :
			return [ 'message' => __( 'Forms list not retrieved.', 'plezi-for-wordpress' ), 'error' => __( 'Signature issue.', 'plezi-for-wordpress' ) ];
		endif;
	}
endif;

if ( ! function_exists( 'plz_get_signature_api' ) ) :
	function plz_get_signature_api ( $endpoint = null, $timestamp = null, $body = null ) {
		if ( isset( $endpoint ) && ! empty( $endpoint ) && isset( $timestamp ) && ! empty( $timestamp ) ) :
	  		$authentification = get_option( 'plz_configuration_authentification_options' );

	  		if ( $authentification && isset( $authentification['plz_authentification_public_key'] ) && ! empty( $authentification['plz_authentification_public_key'] ) && isset( $authentification['plz_authentification_secret_key'] ) && ! empty( $authentification['plz_authentification_secret_key'] ) ) :
				$call = 'GET\nbrain.plezi.co\n/api/v1/' . $endpoint . '\n' . $body . '\nid=' . $authentification['plz_authentification_public_key'] . '&algo=hmac-sha256&nonce=WP-PLEZI&ts=' . $timestamp;
				$signature = hash_hmac( 'sha256', $call, $authentification['plz_authentification_secret_key'], true );
				$encoded = base64_encode( $signature );

				return $encoded;
	  		else :
				return false;
	  		endif;
		else :
	  		return false;
		endif;
	}
endif;