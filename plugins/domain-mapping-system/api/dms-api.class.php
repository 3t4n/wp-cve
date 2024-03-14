<?php

class DMS_API
{
    /**
     * Documentation link
     *
     * @var string
     */
    private  $documentation_link = 'https://docs.domainmappingsystem.com/features/rest-api' ;
    /**
     * API routes
     *
     * @return void
     */
    public static function routes()
    {
        // Create new instance
        $instance = new self();
        /**
         * Register routes
         * 1. mappings
         * 2. token
         */
        register_rest_route( 'dms/v1', 'mappings', [
            'methods'             => 'POST',
            'callback'            => array( $instance, 'insertMapping' ),
            'permission_callback' => array( $instance, 'checkAccess' ),
        ] );
        register_rest_route( 'dms/v1', 'token', [
            'methods'             => 'POST',
            'callback'            => array( $instance, 'token' ),
            'permission_callback' => '__return_true',
        ] );
    }
    
    /**
     * Insert mapping via rest api
     *
     * @param $data
     *
     * @return WP_Error|WP_REST_Response
     */
    public function insertMapping( $data )
    {
        if ( $data->get_header( 'Content-Type' ) !== 'application/json' ) {
            return self::errorResponse( 'invalid_content_type', sprintf( __( 'Invalid content type. Please check our documentation %s', 'dms' ), $this->documentation_link ) );
        }
        global  $wpdb ;
        $instance = DMS::getInstance();
        $item = $data->get_params();
        if ( empty($item['host']) || empty($item['value']) ) {
            return self::errorResponse( 'host_and_value_are_required', __( 'Host and value are required', 'dms' ) );
        }
        // Validate host
        $parsed_host = wp_parse_url( 'http://' . $item['host'], PHP_URL_HOST );
        if ( $parsed_host != $item['host'] || strpos( $item['host'], '.' ) === false || 'http://' . $item['host'] != esc_url_raw( $item['host'] ) ) {
            return self::errorResponse( 'invalid_host_specified', __( 'Invalid host specified', 'dms' ) );
        }
        $item['path'] = null;
        // Validate value
        $post = get_post( sanitize_post( $item['value'] ) );
        if ( (empty($post) || $post->post_status !== 'publish') && !DMS::isTaxonomyTerm( sanitize_text_field( $item['value'] ) ) ) {
            return self::errorResponse( 'invalid_values_exist', __( 'Invalid values exist', 'dms' ) );
        }
        // Check if host is our base host
        if ( $item['host'] === DMS_Helper::getBaseHost() ) {
            return self::errorResponse( 'unable_to_map_primary_domain', sprintf( __( 'Unable to map primary domain. Please check our documentation %s', 'dms' ), $this->documentation_link ) );
        }
        // Check if exists mapping with host+path
        $args = [ $item['host'] ];
        if ( !empty($item['path']) ) {
            $args[] = $item['path'];
        }
        $id = $wpdb->get_row( $wpdb->prepare( 'SELECT `id` FROM ' . $wpdb->prefix . 'dms_mappings WHERE `host`="%s" AND `path`' . (( !empty($item['path']) ? '="%s"' : ' IS NULL' )), $args ) );
        // If exists then update
        
        if ( !empty($id) ) {
            $mapping = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'dms_mapping_values WHERE `host_id`=%d AND `value`= %s', [ $id->id, sanitize_text_field( $item['value'] ) ] ) );
            // Check if exists exactly the same mapping
            
            if ( !empty($mapping) && (int) $mapping->primary == rest_sanitize_boolean( $item['primary'] ) ) {
                return self::errorResponse( 'duplicate_entries_detected_with_the_same_root_domain_and_subdirectory', __( 'Duplicate entries detected with the same root domain and subdirectory', 'dms' ) );
            } elseif ( !empty($mapping) && rest_sanitize_boolean( $item['primary'] ) ) {
                $wpdb->update( $wpdb->prefix . 'dms_mapping_values', [
                    'primary' => 0,
                ], [
                    'host_id' => $id->id,
                ] );
                $wpdb->update( $wpdb->prefix . 'dms_mapping_values', [
                    'primary' => 1,
                ], [
                    'host_id' => $id->id,
                    'value'   => sanitize_text_field( $item['value'] ),
                ] );
                return self::response( [
                    'message' => __( 'Successfully saved', 'dms' ),
                ] );
            } elseif ( !empty($mapping) ) {
                return self::errorResponse( 'duplicate_entries_detected_with_the_same_root_domain_and_subdirectory', __( 'Duplicate entries detected with the same root domain and subdirectory', 'dms' ) );
            }
            
            self::insertMappingValues( $item, $id->id, $instance );
        } else {
            $insert_values = array(
                'host'          => $item['host'],
                'order'         => 1,
                'main'          => 0,
                'attachment_id' => 0,
            );
            $insert_where_values = array(
                '%s',
                '%d',
                '%d',
                '%d'
            );
            
            if ( !empty($item['path']) && $instance->dms_fs->can_use_premium_code__premium_only() ) {
                $insert_values['path'] = $item['path'];
                $insert_where_values[] = '%s';
            }
            
            $ok = $wpdb->insert( $wpdb->prefix . 'dms_mappings', $insert_values, $insert_where_values );
            $host_id = $wpdb->insert_id;
            
            if ( $ok ) {
                // If it is inserting mapping first time it will be always primary by default
                $item['primary'] = true;
                self::insertMappingValues( $item, $host_id, $instance );
            }
        
        }
        
        return self::response( [
            'message' => __( 'Successfully saved', 'dms' ),
        ] );
    }
    
    /**
     * Insert mapping values
     *
     * @param $item
     * @param $host_id
     * @param $instance
     *
     * @return void
     */
    private static function insertMappingValues( $item, $host_id, $instance )
    {
        $dms_woo_shop_global_mapping = get_option( 'dms_woo_shop_global_mapping' );
        $shop_page_association = ( !empty($dms_woo_shop_global_mapping) ? DMS_Helper::getShopPageAssociation() : false );
        $dms_archive_global_mapping = get_option( 'dms_archive_global_mapping' );
        $ok_values = 0;
        if ( $item['primary'] === true ) {
            $item['mappings']['primary'] = sanitize_text_field( $item['value'] );
        }
        $insert_result = $instance->prepareMappingInsert(
            sanitize_text_field( $item['value'] ),
            $item,
            $host_id,
            $ok_values
        );
        
        if ( $insert_result = 1 || ($insert_result = 2) ) {
            // Check if taxonomy
            if ( $instance->dms_fs->can_use_premium_code__premium_only() && !empty($dms_archive_global_mapping) ) {
                // Native posts category related mapping
                
                if ( DMS::isTaxonomyTerm( sanitize_text_field( $item['value'] ) ) ) {
                    // Collect all posts connected with taxonomy ( weather native category or taxonomy )
                    $term_taxonomy = DMS::getTaxonomyTermFromValue( sanitize_text_field( $item['value'] ) );
                    $term = get_term_by( 'slug', $term_taxonomy[1], $term_taxonomy[0] );
                    $posts = DMS::getPostsByTaxonomyTermId( $term_taxonomy[0], $term->term_id );
                    foreach ( $posts as $post_id ) {
                        $insert_result = $instance->prepareMappingInsert(
                            $post_id,
                            $item,
                            $host_id,
                            $ok_values
                        );
                        if ( $insert_result === 0 ) {
                            break;
                        }
                    }
                }
            
            }
            // Check if shop page
            if ( $instance->dms_fs->can_use_premium_code__premium_only() && !empty($dms_woo_shop_global_mapping) ) {
                
                if ( $shop_page_association == sanitize_text_field( $item['value'] ) ) {
                    // Get all products and associate with
                    $products = DMS::getAllWooProducts();
                    if ( !empty($products) ) {
                        foreach ( $products as $product_id ) {
                            $insert_result = $instance->prepareMappingInsert(
                                $product_id,
                                $item,
                                $host_id,
                                $ok_values
                            );
                            if ( $insert_result === 0 ) {
                                break;
                            }
                        }
                    }
                }
            
            }
        }
    
    }
    
    /**
     * Login user and return token
     *
     * @param $data
     *
     * @return void|WP_Error|WP_REST_Response
     */
    public function token( $data )
    {
        try {
            if ( $data->get_header( 'Content-Type' ) !== 'application/json' ) {
                return self::errorResponse( 'invalid_content_type', sprintf( __( 'Invalid content type. Please check our documentation %s', 'dms' ), $this->documentation_link ) );
            }
            $user = $data->get_params()['user_name'];
            $password = $data->get_params()['password'];
            if ( empty($user) ) {
                return self::errorResponse( 'empty_username_field', __( 'Empty username field', 'dms' ) );
            }
            if ( empty($password) ) {
                return self::errorResponse( 'empty_password_field', __( 'Empty password field', 'dms' ) );
            }
            $signon = wp_signon( [
                'user_login'    => sanitize_user( $user ),
                'user_password' => $password,
            ] );
            if ( is_wp_error( $signon ) ) {
                return self::errorResponse( $signon->get_error_code(), $signon->get_error_message() );
            }
            $secret = ( is_multisite() ? get_blog_option( get_current_blog_id(), 'dms_api_secret' ) : get_option( 'dms_api_secret' ) );
            if ( empty($secret) ) {
                return self::errorResponse( 'unable_to_get_token', sprintf( __( 'Unable to get token. Please contact the site administrator owner and review our documentation %s' ), $this->documentation_link ) );
            }
            $header = json_encode( [
                'typ' => 'JWT',
                'alg' => 'HS256',
            ] );
            $payload = json_encode( [
                'user_id' => $signon->data->ID,
            ] );
            $token = DMS_Helper::base64UrlEncode( $header, $payload, $secret );
            return self::response( [
                'token' => $token,
            ] );
        } catch ( \Exception $e ) {
            // Technical error
            return self::errorResponse( 'technical_error', sprintf( __( 'Technical error. Please contact support and review our documentation %s', 'dms' ), $this->documentation_link ), 500 );
        }
    }
    
    /**
     * Check access via token
     *
     * @param  WP_REST_Request  $request
     *
     * @return bool
     */
    public function checkAccess( WP_REST_Request $request )
    {
        $secret = ( is_multisite() ? get_blog_option( get_current_blog_id(), 'dms_api_secret' ) : get_option( 'dms_api_secret' ) );
        $authorization = $request->get_header( 'Authorization' );
        if ( empty($authorization) ) {
            return false;
        }
        $jwt = str_replace( 'Bearer ', '', $authorization );
        $tokenParts = explode( '.', $jwt );
        $header = ( !empty($tokenParts[0]) ? base64_decode( $tokenParts[0] ) : null );
        $payloadJSON = ( !empty($tokenParts[1]) ? base64_decode( $tokenParts[1] ) : null );
        $signatureProvided = ( !empty($tokenParts[2]) ? base64_decode( $tokenParts[2] ) : null );
        
        if ( !is_null( $header ) && !is_null( $payloadJSON ) && !is_null( $signatureProvided ) ) {
            $token = DMS_Helper::base64UrlEncode( $header, $payloadJSON, $secret );
        } else {
            return false;
        }
        
        return $jwt === $token;
    }
    
    /**
     * Return error response
     *
     * @param $code
     * @param $message
     * @param $httpStatusCode
     * @param $data
     *
     * @return WP_Error
     */
    public static function errorResponse(
        $code,
        $message,
        $httpStatusCode = 400,
        $data = null
    )
    {
        $dataToSent = array(
            'status' => $httpStatusCode,
        );
        if ( is_array( $data ) ) {
            $dataToSent = array_merge( $dataToSent, $data );
        }
        return new WP_Error( $code, $message, $dataToSent );
    }
    
    /**
     * Response ( success )
     *
     * @param  array  $data
     *
     * @return WP_REST_Response
     */
    public static function response( $data )
    {
        $response = new WP_REST_Response();
        // Set data
        $response->set_data( $data );
        return $response;
    }

}