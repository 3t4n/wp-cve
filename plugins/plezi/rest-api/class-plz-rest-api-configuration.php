<?php
if ( ! defined( 'ABSPATH' ) ) :
	exit;
endif;

if ( ! class_exists( 'PLZ_Rest_API_Configuration' ) ) :
  class PLZ_Rest_API_Configuration {
    public function __construct() {
      $this->tools = new PLZ_Rest_API_Tools();
    }

    public function register_routes() {
      register_rest_route(
        $this->tools->get_plugin_namespace(),
        '/configuration/set-authentification',
        array(
          'methods' => WP_REST_Server::CREATABLE,
          'callback' => array( $this, 'set_authentification' ),
          'permission_callback' => array( $this, 'get_permissions' )
        )
      );
      register_rest_route(
        $this->tools->get_plugin_namespace(),
        '/configuration/remove-authentification',
        array(
          'methods' => WP_REST_Server::READABLE,
          'callback' => array( $this, 'remove_authentification' ),
          'permission_callback' => array( $this, 'get_permissions' )
        )
      );
      register_rest_route(
        $this->tools->get_plugin_namespace(),
        '/configuration/set-tracking-manual-status',
        array(
          'methods' => WP_REST_Server::CREATABLE,
          'callback' => array( $this, 'set_tracking_manual_status' ),
					'permission_callback' => array( $this, 'get_permissions' )
        )
      );
      register_rest_route(
        $this->tools->get_plugin_namespace(),
        '/configuration/get-tracking-api',
        array(
          'methods' => WP_REST_Server::READABLE,
          'callback' => array( $this, 'get_tracking_api' ),
          'permission_callback' => array( $this, 'get_permissions' )
        )
      );
      register_rest_route(
        $this->tools->get_plugin_namespace(),
        '/configuration/set-tracking-api',
        array(
          'methods' => WP_REST_Server::CREATABLE,
          'callback' => array( $this, 'set_tracking_api' ),
          'permission_callback' => array( $this, 'get_permissions' )
        )
      );
      register_rest_route(
        $this->tools->get_plugin_namespace(),
        '/configuration/remove-tracking-api',
        array(
          'methods' => WP_REST_Server::READABLE,
          'callback' => array( $this, 'remove_tracking_api' ),
					'permission_callback' => array( $this, 'get_permissions' )
        )
      );
      register_rest_route(
        $this->tools->get_plugin_namespace(),
        '/configuration/set-tracking-api-status',
        array(
          'methods' => WP_REST_Server::CREATABLE,
          'callback' => array( $this, 'set_tracking_api_status' ),
          'permission_callback' => array( $this, 'get_permissions' )
        )
      );
      register_rest_route(
        $this->tools->get_plugin_namespace(),
        '/configuration/get-forms-list',
        array(
          'methods' => WP_REST_Server::CREATABLE,
          'callback' => array( $this, 'get_forms_list' ),
					'permission_callback' => array( $this, 'get_permissions' )
        )
      );

      return true;
    }

    public function set_authentification( $request ) {
      $params = $request->get_params();

      if ( isset( $params['public'] ) && ! empty( $params['public'] ) && isset( $params['secret'] ) && ! empty( $params['secret'] ) ) :
        $body = array( 'client_id' => $params['public'], 'client_secret' => $params['secret'] );
        $args = array( 'body' => $body, 'timeout' => '3', 'redirection' => '5', 'httpversion' => '1.0', 'blocking' => true );
        $response = wp_remote_post( 'https://brain.plezi.co/api/v1/api_keys/authenticate', $args );
        $response_code = wp_remote_retrieve_response_code( $response );

        if ( $response_code && 200 === $response_code ) :
          $options = get_option( 'plz_configuration_authentification_options' );
          $options['plz_authentification_status'] = '1';
          $options['plz_authentification_public_key'] = $params['public'];
          $options['plz_authentification_secret_key'] = $params['secret'];

          update_option( 'plz_configuration_authentification_options', $options );

          return new WP_REST_Response( array( 'message' => __( 'Authentification access saved.', 'plezi-for-wordpress' ), 'success' => true ), 200 );
        else :
          return new WP_REST_Response( array( 'message' => __( 'Authentification access not saved.', 'plezi-for-wordpress' ), 'error' => __( 'Authentification issue.', 'plezi-for-wordpress' ) ), 200 );
        endif;
      else :
        return new WP_REST_Response( array( 'message' => __( 'Authentification access not saved.', 'plezi-for-wordpress' ), 'error' => __( 'Data issue.', 'plezi-for-wordpress' ) ), 200 );
      endif;
    }

    public function remove_authentification() {
      $this->tools->clean_old_plugin_options( true, true, true );

      $options = get_option( 'plz_configuration_authentification_options' );
      $options['plz_authentification_status'] = '0';
      $options['plz_authentification_public_key'] = '';
      $options['plz_authentification_secret_key'] = '';

      update_option( 'plz_configuration_authentification_options', $options );

      return new WP_REST_Response( array( 'message' => __( 'Authentification access removed.', 'plezi-for-wordpress' ), 'success' => true ), 200 );
    }

    public function set_tracking_manual_status( $request ) {
      $params = $request->get_params();

      if ( isset( $params['status'] ) ) :
        $this->tools->clean_old_plugin_options( true, false, false );

        $options = get_option( 'plz_configuration_tracking_options' );
        $options['plz_configuration_tracking_enable_manual'] = $params['status'];

        if ( 'checked' === $params['status'] ) :
          $options['plz_tracking_choice'] = 'manual';
          $options['plz_configuration_tracking_enable'] = '';
          $options['plz_configuration_tracking_manual_date'] = gmdate( 'H:i:s d/m/Y' );
        else :
          $options['plz_tracking_choice'] = '';
        endif;

        update_option( 'plz_configuration_tracking_options', $options );

        return new WP_REST_Response( array( 'message' => __( 'Tracking manual status updated.', 'plezi-for-wordpress' ), 'date' => gmdate( 'H:i:s d/m/Y' ) ), 200 );
      else :
        return new WP_REST_Response( array( 'message' => __( 'Tracking manual status not updated.', 'plezi-for-wordpress' ), 'error' => __( 'Data issue.', 'plezi-for-wordpress' ) ), 200 );
      endif;
    }

    public function get_tracking_api() {
      $timestamp = time();
      $signature = $this->tools->get_signature_api( 'analytics_script', $timestamp );

      if ( $signature && ! empty( $signature ) ) :
        $this->tools->clean_old_plugin_options( true, true, true );

        $options = get_option( 'plz_configuration_tracking_options' );
        $authentification = get_option( 'plz_configuration_authentification_options' );
        $args = array( 'headers' => array( 'X-AUTHORIZATION' => 'plezi id=' . $authentification['plz_authentification_public_key'] . ',algo=hmac-sha256,nonce=WP-PLEZI,signature=' . $signature . ',ts=' . $timestamp));
        $result = wp_remote_get( 'https://brain.plezi.co/api/v1/analytics_script', $args );
        $result = json_decode( wp_remote_retrieve_body( $result ) );

        if ( $result && isset( $result->data ) && isset( $result->data->script ) && ! empty( $result->data->script ) ) :
          $script = $result->data->script;
          $options['plz_configuration_tracking_code'] = $script;
          $options['plz_configuration_tracking_date'] = gmdate( 'd/m/Y' );

          update_option( 'plz_configuration_tracking_options', $options );

          return new WP_REST_Response( array( 'message' => __( 'Tracking code added.', 'plezi-for-wordpress' ), 'script' => $script ), 200 );
        endif;

        return new WP_REST_Response( array( 'message' => __( 'Tracking code not added.', 'plezi-for-wordpress' ), 'error' => __( 'Call API issue.', 'plezi-for-wordpress' ) ), 200 );
      else :
        return new WP_REST_Response( array( 'message' => __( 'Tracking code not added.', 'plezi-for-wordpress' ), 'error' => __( 'Signature issue.', 'plezi-for-wordpress' ) ), 200 );
      endif;
    }

    public function set_tracking_api( $request ) {
			$params = $request->get_params();

			if ( isset( $params['tracking'] ) && ! empty( $params['tracking'] ) ) :
      	$this->tools->clean_old_plugin_options( true, true, true );

      	$options = get_option( 'plz_configuration_tracking_options' );
      	$options['plz_configuration_tracking_code'] = $params['tracking'];
      	$options['plz_configuration_tracking_date'] = gmdate( 'd/m/Y' );

      	update_option( 'plz_configuration_tracking_options', $options );

      	return new WP_REST_Response( array( 'message' => __( 'Tracking code added.', 'plezi-for-wordpress' ), 'script' => $params['tracking'] ), 200 );
			else :
				return new WP_REST_Response( array( 'message' => __( 'Tracking code not added.', 'plezi-for-wordpress' ), 'error' => __( 'Tracking code not valide.', 'plezi-for-wordpress' ) ), 200 );
			endif;
		}

    public function remove_tracking_api() {
      $this->tools->clean_old_plugin_options( true, true, true );

      $options = get_option( 'plz_configuration_tracking_options' );
      $options['plz_configuration_tracking_enable'] = '';
      $options['plz_configuration_tracking_code'] = '';
      $options['plz_configuration_tracking_date'] = '';
      $options['plz_tracking_choice'] = '';

      update_option( 'plz_configuration_tracking_options', $options );

      return new WP_REST_Response( array( 'message' => __( 'Tracking code removed.', 'plezi-for-wordpress' ), 'success' => true ), 200 );
    }

    public function set_tracking_api_status( $request ) {
      $params = $request->get_params();

      if ( isset( $params['status'] ) ) :
        $this->tools->clean_old_plugin_options( true, false, false );

        $options = get_option( 'plz_configuration_tracking_options' );
        $options['plz_configuration_tracking_enable'] = $params['status'];

        if ( 'checked' === $params['status'] ) :
          $options['plz_tracking_choice'] = 'api';
          $options['plz_configuration_tracking_enable_manual'] = '';
          $options['plz_configuration_tracking_date'] = gmdate( 'd/m/Y' );
        else :
          $options['plz_tracking_choice'] = '';
        endif;

        update_option( 'plz_configuration_tracking_options', $options );

        return new WP_REST_Response( array( 'message' => __( 'Tracking API status updated.', 'plezi-for-wordpress' ), 'date' => gmdate( 'd/m/Y' ) ), 200 );
      else :
        return new WP_REST_Response( array( 'message' => __( 'Tracking API status not updated.', 'plezi-for-wordpress' ), 'error' => __( 'Data issue.', 'plezi-for-wordpress' ) ), 200 );
      endif;
    }

    public function get_forms_list( $request ) {
		$params = $request->get_params();

		if ( isset( $params['args'] ) && ! empty( $params['args'] ) && isset( $params['filters'] ) && ! empty( $params['filters'] ) ) :
			$args = $params['args'];
			$filters = $params['filters'];
		else :
			$args = 'sort_by=title&sort_dir=asc&page=1&per_page=20';
			$filters = array('sort_by' => 'title', 'sort_dir' => 'asc', 'page' => '1', 'per_page' => '20' );
		endif;

		$timestamp = time();
		$signature = $this->tools->get_signature_api( 'content_web_forms', $timestamp, $args );

      if ( $signature && ! empty( $signature ) ) :
        $authentification = get_option( 'plz_configuration_authentification_options' );
        $args = array( 'headers' => array( 'X-AUTHORIZATION' => 'plezi id=' . $authentification['plz_authentification_public_key'] . ',algo=hmac-sha256,nonce=WP-PLEZI,signature=' . $signature . ',ts=' . $timestamp ), 'body' => $filters );
        $result = wp_remote_get( 'https://brain.plezi.co/api/v1/content_web_forms', $args );
        $result = json_decode( wp_remote_retrieve_body( $result ) );

        if ( $result && isset( $result->data ) && is_array( $result->data ) && ! empty( $result->data ) ) :
			$forms = $result->data;
			$metas = $result->meta;

			foreach ( $forms as $form ) :
				$date = new DateTime( $form->attributes->created_at );

				$form->attributes->custom_title = $form->attributes->title . ' - ' . __( 'Created on ', 'plezi-for-wordpress' ) . date_i18n( get_option( 'date_format' ), $date->getTimestamp() ) . ' - ' . date_i18n( get_option( 'time_format' ), $date->getTimestamp() );
			endforeach;

			if ( isset( $params['default'] ) && ! empty( $params['default'] ) && $params['default'] ) :
				array_unshift( $forms, array( 'id' => '', 'type' => 'default', 'attributes' => array( 'id' => '', 'title' => __('Choose a Plezi form', 'plezi-for-wordpress'), 'custom_title' => __('Choose a Plezi form', 'plezi-for-wordpress') ) ) );
			endif;

          return new WP_REST_Response( array( 'message' => __( 'Forms list retrieved.', 'plezi-for-wordpress' ), 'list' => $forms, 'metas' => $metas ), 200 );
        endif;

        return new WP_REST_Response( array( 'message' => __( 'Forms list not retrieved.', 'plezi-for-wordpress' ), 'error' => __( 'Call API issue.', 'plezi-for-wordpress' ) ), 200 );
      else :
        return new WP_REST_Response( array( 'message' => __( 'Forms list not retrieved.', 'plezi-for-wordpress' ), 'error' => __( 'Signature issue.', 'plezi-for-wordpress' ) ), 200 );
      endif;
    }

	public function get_permissions() {
		return current_user_can( 'manage_options' );
	}
  }
endif;

if ( ! function_exists( 'plz_rest_api_configuration_init' ) ) :
  function plz_rest_api_configuration_init() {
		$class = new PLZ_Rest_API_Configuration();

		add_filter( 'rest_api_init', array( $class, 'register_routes' ) );
	}
endif;
