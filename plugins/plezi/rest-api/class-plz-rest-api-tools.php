<?php
if ( ! defined( 'ABSPATH' ) ) :
	exit;
endif;

if ( ! class_exists( 'PLZ_Rest_API_Tools' ) ) :
  class PLZ_Rest_API_Tools {
    public function get_plugin_namespace() {
      return 'plz/v2';
    }

    public function clean_old_plugin_options( $status = false, $tracker = false, $date = false ) {
	    $old_options = get_option( 'plz_configuration_options' );

      if ( $status && $old_options && isset( $old_options['plz_configuration_tracker_enable'] ) ) :
        unset( $old_options['plz_configuration_tracker_enable'] );

        update_option( 'plz_configuration_options', $old_options );
      endif;

      if ( $tracker && $old_options && isset( $old_options['plz_configuration_tracker_code'] ) ) :
        unset( $old_options['plz_configuration_tracker_code'] );

        update_option( 'plz_configuration_options', $old_options );
      endif;

      if ( $date && $old_options && isset( $old_options['plz_configuration_tracker_date'] ) ) :
        unset( $old_options['plz_configuration_tracker_date'] );

        update_option( 'plz_configuration_options', $old_options );
      endif;
    }

    public function get_signature_api( $endpoint = null, $timestamp = null, $body = null ) {
      if ( isset( $endpoint ) && ! empty( $endpoint ) && isset( $timestamp ) && ! empty( $timestamp ) ) :
        $authentification   = get_option( 'plz_configuration_authentification_options' );

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
  }
endif;
