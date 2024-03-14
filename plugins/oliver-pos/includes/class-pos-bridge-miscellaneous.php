<?php
defined( 'ABSPATH' ) || exit;

include_once OLIVER_POS_ABSPATH . 'includes/models/class-pos-bridge-product.php';

/**
 * This class is responsible for all miscellaneous operations
 */

use bridge_models\Pos_Bridge_Product as Product;

class Pos_Bridge_Miscellaneous {

    private $pos_bridge_product;
    function __construct() {
        $this->pos_bridge_product = new Product();
    }

    // *****  All category relative routes  ***** //
    private function oliver_pos_get_category_by_id( $id ) {
        return get_term_by( 'id', $id, 'product_cat' );
    }

    public function oliver_pos_get_categories() {
        $orderby = 'name';
        $order = 'asc';
        $hide_empty = false ;
        $cat_args = array(
            'orderby'    => $orderby,
            'order'      => $order,
            'hide_empty' => $hide_empty,
        );
        return $product_categories = get_terms( 'product_cat', $cat_args );
    }

    public function oliver_pos_get_category( $request_data ) {
        $parameters = $request_data->get_params();
        if (isset($parameters['category_id']) && !empty($parameters['category_id'])) {
            $id = (int) sanitize_text_field( $parameters['category_id'] );
            $category = $this->oliver_pos_get_category_by_id( $id );
            if ( ! $category ) {
                return null;
            }
            return $category;
        }
	    return oliver_pos_api_response('Invalid Request', -1);
    }

    public function oliver_pos_get_subcategory( $request_data ) {
        $parameters = $request_data->get_params();
        if (isset($parameters['category_id']) && !empty($parameters['category_id'])) {
            $id = (int) sanitize_text_field( $parameters['category_id'] );
            $get_term_children = get_term_children( $id, 'product_cat' );
            if (!empty($get_term_children)) {
                foreach ($get_term_children as $key => $value) {
                    $data[] = $this->oliver_pos_get_category_by_id( $value );
                }
                return $data;
            }
	        return oliver_pos_api_response('No sub-categories found', -1);
        }
	    return oliver_pos_api_response('Invalid Request', -1);
    }

    public function oliver_pos_get_category_product( $request_data ) {
        $parameters = $request_data->get_params();
        $data = array();
        if (isset($parameters['category_slug']) && !empty($parameters['category_slug'])) {

            $loop = new WP_Query( array(
                'post_type' => 'product',
                'post_status' => array('publish', 'private'),
                'product_cat' => sanitize_text_field( $parameters['category_slug'] ),
                'orderby' => 'name' ,
                'order'   => 'ASC',
                'posts_per_page' => -1
            ) );

            while ($loop->have_posts()):
                $loop->the_post();
                $id = (int) $loop->post->ID;
                array_push($data, $this->pos_bridge_product->oliver_pos_get_product_data( $id ) );
            endwhile;
            if (!empty($data)) {
                return $data;
            }
	        return oliver_pos_api_response('No Product found for the category', -1);
        }
	    return oliver_pos_api_response('Invalid Request', -1);
    }

    // *****  settings  ***** //
    public function oliver_pos_reset_subscription() {
        delete_option( 'oliver_pos_subscription_client_id' );
        delete_option( 'oliver_pos_subscription_password' );
        delete_option( 'oliver_pos_subscription_udid' );
        delete_option( 'oliver_pos_subscription_item_id' );
        delete_option( 'oliver_pos_subscription_subscription_id' );
        delete_option( 'oliver_pos_server_url' );
        delete_option( 'oliver_pos_subscription_info_id' );
        delete_option( 'oliver_pos_subscription_emenc_id' );
        delete_option( 'oliver_pos_subscription_penc_id' );
        delete_option( 'oliver_pos_subscription_udid' );
        echo json_encode( array( 'success' => true ) );
        exit;
    }
    // *****  settings  ***** //

    // *****  All attribute relative routes  ***** //

    private function oliver_pos_get_attribute_by_slug( $slug ) {
        if (!is_null($slug) && is_string($slug)) {
            return get_terms( $slug, 'orderby=name&hide_empty=0' );
        }
    }

    public function oliver_pos_get_attributes() {
        $data = array();
        $taxonomies = wc_get_attribute_taxonomies();
        if (! empty($taxonomies)) {
            foreach ($taxonomies as $key => $taxonomie) {
                array_push($data, $this->oliver_pos_get_attribute_by_id( $taxonomie->attribute_id ));
            }
        }
        return $data;
    }

    public function oliver_pos_get_attribute( $request_data ) {
        $parameters = $request_data->get_params();
        $array = array();

        if ( isset($parameters['attribute_id']) && !empty($parameters['attribute_id']) ) {
            return $this->oliver_pos_get_attribute_by_id( $parameters['attribute_id'] );
        }
	    return oliver_pos_api_response('Invalid Request', -1);
    }

    public function oliver_pos_get_attribute_by_id( $id ) {
        $data = wc_get_attribute( sanitize_text_field($id) );
        if ( !empty($data) ) {
            return array(
                'attribute_id' 			=> (int) $data->id,
                'attribute_name' 		=> str_replace('pa_', '', $data->slug),
                'attribute_label' 		=> $data->name,
                'attribute_type' 		=> $data->type,
                'attribute_orderby' 	=> $data->order_by,
                'attribute_public' 		=> ($data->has_archives === false) ? "0" : $data->has_archives,
            );
        }
        return array();
    }

    public function oliver_pos_get_subattribute( $request_data ) {
        $parameters = $request_data->get_params();

        if (isset($parameters['attribute_name']) && !empty($parameters['attribute_name'])) {
            $slug = sanitize_text_field( $parameters['attribute_name'] );
            $slug = (strpos($slug, 'pa_') !== false) ? $slug : "pa_".$slug;
            return $this->oliver_pos_get_attribute_by_slug( $slug );
            // return $this->get_attribute_by_slug( wc_attribute_taxonomy_name( $slug ) );
        }
	    return oliver_pos_api_response('Invalid Request', -1);
    }

    /**
     * Get product by attribute when request by API.
     * @param string $request_data params array
     * @return int Returns array.
     */
    public function oliver_pos_get_attribute_product($request_data) {
        $parameters = $request_data->get_params();
        $data = array();
        if (isset($parameters['attribute_name']) && !empty($parameters['attribute_name'])) {
            $attribute = sanitize_text_field($parameters['attribute_name']);
            $attribute_name = (strpos($attribute, 'pa_') !== false) ? $attribute : "pa_".$attribute;
            $data = $this->oliver_pos_get_product_by_attribute($attribute_name);

            if (!empty($data)) {
                return $data;
            }
	        return oliver_pos_api_response('No Product found for the attribute', -1);
        }
    }

    /**
     * Get product by attribute name.
     * @since 2.2.5.6
     * @param string $attribute_name
     * @return int Returns array.
     */
    public function oliver_pos_get_product_by_attribute( $attribute_name = '' ) {
        $data = array();

        if ( ! empty($attribute_name) && is_string($attribute_name)) {
            $attribute = sanitize_text_field($attribute_name);
            $terms = get_terms( $attribute );
            foreach ($terms as $key => $term) {
                $terms_array[] = $term->slug;
            }

            $loop = new WP_Query( array(
                'post_type' => 'product',
                'post_status' => array('publish', 'private'),
                'tax_query' => array(
                    array(
                        'taxonomy'    => $attribute,
                        'terms'     => $terms_array,
                        'field'     => 'slug',
                        'operator'    => 'IN'
                    )
                ),
                'orderby' => 'title',
                'order'   => 'ASC',
                'posts_per_page' => -1
            ));
            while ($loop->have_posts()):
                $loop->the_post();
                $id = (int) $loop->post->ID;
                array_push($data, $this->pos_bridge_product->oliver_pos_get_product_data( $id ) );
            endwhile;
        }
        return $data;
    }

    /**
     * Run when sub attribute or after reordered.
     * @since 2.2.2.0
     * @return vois Return void.
     */
    /* comment since 2.4.0.9
    public function oliver_pos_after_subattribute_reorder( $term, $index, $taxonomy ) {
        $data['attribute'] = $this->oliver_pos_get_attribute_by_slug($taxonomy);
        $data['products'] = $this->oliver_pos_get_product_by_attribute($taxonomy);

        wp_remote_post( esc_url_raw( $url ), array(
            'body' => $data,
            'headers' => array(
                'Authorization' => 'Basic ' . base64_encode( get_option( 'oliver_pos_subscription_client_id' ).":".get_option( 'oliver_pos_subscription_token' ) ),
            ),
        ) );
        return true;
    } */

    public function oliver_pos_get_sub_attribute_product( $request_data ) {
        $parameters = $request_data->get_params();
        $data = array();
        if (isset($parameters['attribute_name']) && isset($parameters['sub_attribute_name'])) {
            $attribute = 'pa_'.sanitize_text_field( $parameters['attribute_name'] );
            $terms_array[] = sanitize_text_field( $parameters['sub_attribute_name'] );

            $loop = new WP_Query( array(
                'post_type' => 'product',
                'post_status' => array('publish', 'private'),
                'tax_query' => array(
                    array(
                        'taxonomy'    => $attribute,
                        'terms'     => $terms_array,
                        'field'     => 'slug',
                        'operator'    => 'IN'
                    )
                ),
                'orderby' => 'title',
                'order'   => 'ASC',
                'posts_per_page' => -1
            ));
            while ($loop->have_posts()):
                $loop->the_post();
                $id = (int) $loop->post->ID;
                array_push($data, $this->pos_bridge_product->oliver_pos_get_product_data( $id ) );
            endwhile;

            if (!empty($data)) {
                return $data;
            }
	        return oliver_pos_api_response('No Product found for the attribute', -1);
        }
	    return oliver_pos_api_response('Invalid Request', -1);
    }

    /**
     * Get attribute count.
     * @since 2.2.2.0
     * @return int Returns count of customers.
     */
    public function oliver_pos_get_attribute_count() {
        return count(wc_get_attribute_taxonomies());

        // $subattribute_count = 0;
        // if ( !empty($attributea)) {
        // 	foreach ($attributea as $key => $attribute) {
        // 		$subattribute_count += (int) wp_count_terms( wc_attribute_taxonomy_name( $attribute->attribute_label ) );
        // 	}
        // }
    }

    /**
     * Get category count.
     * @since update 2.3.9.7
     * @since 2.2.2.0
     * @return int Returns count of customers.
     */
    public function oliver_pos_get_category_count() {
        return (int) wp_count_terms('product_cat');
    }

    /**
     * Get count by element.
     * @since 2.2.2.0
     * @param array $request_data
     * @return int Returns count of given element | otherwise error.
     */
    public function oliver_pos_get_count_for( $request_data ) {
        $parameters = $request_data->get_params();

        if ( !empty($parameters['entity'])) {
            $antity = sanitize_text_field( $parameters['entity'] );
            $count = 0;
            $antities = array('attribute', 'category', 'order', 'parent_product_count', 'product', 'tax', 'customers', 'tickera_forms', 'tickera_events', 'tickera_tickets', 'tickera_charts');

            if (in_array($antity, $antities)){
                return $this->oliver_pos_get_count_for_entity($antity);
            } else {
	            return oliver_pos_api_response( 'Invalid entity, Supported entities are: ' . implode( ',' ,$antities ), -1);
            }

        }
	    return oliver_pos_api_response('Invalid Request', -1);
    }

    /**
     * Get count by entity.
     * @since 2.2.2.0
     * @param string $antity containn entity name
     * @return int Returns count of given entity | default attribute entity.
     */
    private function oliver_pos_get_count_for_entity($antity = 'attribute')
    {
        switch ($antity) {
            case 'attribute':
                return $this->oliver_pos_get_attribute_count();
                break;

            case 'category':
                return $this->oliver_pos_get_category_count();
                break;

            case 'order':
                return Pos_Bridge_Order::oliver_pos_order_count();
                break;

            case 'parent_product_count':
                return Pos_Bridge_Product::oliver_pos_parent_product_count();
                break;

            case 'product':
                return Pos_Bridge_Product::oliver_pos_product_count();
                break;

            case 'tax':
                return Pos_Bridge_Tax::oliver_pos_tax_count();
                break;

            case 'customers':
                return Pos_Bridge_User::oliver_pos_get_customer_count();
                break;

            case 'tickera_forms':
                return Pos_Bridge_Tickera::oliver_pos_get_form_count();
                break;

            case 'tickera_events':
                return Pos_Bridge_Tickera::oliver_pos_get_event_count();
                break;

            case 'tickera_tickets':
                return Pos_Bridge_Tickera::oliver_pos_get_ticket_count();
                break;

            case 'tickera_charts':
                return Pos_Bridge_Tickera::oliver_pos_get_seating_chart_count();
                break;

            default:
                return 0;
                break;
        }
    }


    // ***** miscellaneous ***** //
	public function oliver_pos_get_tags() {
		$orderby = 'name';
		$order = 'asc';
		$hide_empty = false ;
		$tag_args = array(
			'orderby'    => $orderby,
			'order'      => $order,
			'hide_empty' => $hide_empty,
		);
		return $product_tags = get_terms( 'product_tag', $tag_args );
	}
    public function oliver_pos_get_counts() {
        // === attribute ===
        $data['attribute'] = $this->oliver_pos_get_attribute_count();

        // === category ===
        $data['category'] = $this->oliver_pos_get_category_count();
        $data['order'] = Pos_Bridge_Order::oliver_pos_order_count();
        $data['parent_product_count'] = Pos_Bridge_Product::oliver_pos_parent_product_count();
        $data['product'] = Pos_Bridge_Product::oliver_pos_product_count();
        $data['tax'] = Pos_Bridge_Tax::oliver_pos_tax_count();

        $data['customers'] = Pos_Bridge_User::oliver_pos_get_customer_count();
        // tickera counts
        $data['tickera_forms'] 	 = Pos_Bridge_Tickera::oliver_pos_get_form_count();
        $data['tickera_events']  = Pos_Bridge_Tickera::oliver_pos_get_event_count();
        $data['tickera_tickets'] = Pos_Bridge_Tickera::oliver_pos_get_ticket_count();
        $data['tickera_charts']  = Pos_Bridge_Tickera::oliver_pos_get_seating_chart_count();

        return $data;
    }

    // ***** settings ***** //
    public function oliver_pos_get_general_settings() {
        $state = $state_full_name = $country_code = null;
        $country = get_option('woocommerce_default_country');

        if (!empty( $country )) {
            $explode_state = explode(':', $country);
            if (isset($explode_state[1])) {
                $state = $explode_state[1];
                $country_code = $explode_state[0];
                $states = WC()->countries->get_states( $country_code );
                $state_full_name = ! empty( $states[ $state ] ) ? $states[ $state ] : null;
            }
        }
        return array(
            'shop_country' => $country,
            'shop_country_full_name' => WC()->countries->countries[ ($country_code != null) ? $country_code : $country ],
            'shop_state' => $state,
            'state_full_name' => $state_full_name,
            'shop_title' => get_bloginfo('name'),
            'shop_website' => get_site_url(),
            'currency_symbol' => $this->oliver_pos_get_shop_currency(),
            'shop_address_1' => get_option('woocommerce_store_address'),
            'shop_address_2' => get_option('woocommerce_store_address_2'),
            'shop_city' => stripslashes( get_option('woocommerce_store_city')),
            'shop_postcode' => get_option('woocommerce_store_postcode'),
            'shop_currency' => get_option('woocommerce_currency'),
            'shop_price_num_decimals' => get_option('woocommerce_price_num_decimals'),
            'shop_price_decimal_sep' => get_option('woocommerce_price_decimal_sep'),
            'shop_price_thousand_sep' => get_option('woocommerce_price_thousand_sep'),
            'shop_currency_pos' => get_option('woocommerce_currency_pos'),
            'shop_calc_taxes' => get_option('woocommerce_calc_taxes'),
            'shop_enable_coupons' => get_option('woocommerce_enable_coupons'),
            'shop_calc_discounts_sequentially' => get_option('woocommerce_calc_discounts_sequentially'),
            'shop_default_customer_address' => get_option('woocommerce_default_customer_address'),
            'shop_language' => get_option('WPLANG'),
            'shop_timezone' => get_option('timezone_string'),
            'shop_date_format' => get_option('date_format'),
            'shop_time_format' => get_option('time_format'),
            'shop_start_of_week' => get_option('start_of_week'),
            'gmt_offset' => get_option('gmt_offset'),
        );
    }
	public function oliver_pos_get_shop_currency() {
		$op_currency_symbol = get_woocommerce_currency_symbol();
		if( !empty( $op_currency_symbol )) {
			return $op_currency_symbol;
		}
		else{
			$op_symbols = get_woocommerce_currency_symbols();
			$op_currency = get_option('woocommerce_currency');
			$op_currency_symbol = isset( $op_symbols[ $op_currency ] ) ? $op_symbols[ $op_currency ] : '';
			return $op_currency_symbol;
		}
	}

    public function oliver_pos_get_coupon() {
        $data = array();
        $args = array(
            'posts_per_page'   => -1,
            'orderby'          => 'title',
            'order'            => 'asc',
            'post_type'        => 'shop_coupon',
            'post_status'      => 'publish',
        );

        $coupons = get_posts( $args );

        if ( !empty( $coupons )) {
            foreach ($coupons as $key => $coupon) {
                $id = (int) $coupon->ID;
                $wc_coupon = new WC_Coupon( $id );

                array_push($data, array(
                    'code' => $wc_coupon->get_code(),
                    'amount' => $wc_coupon->get_amount(),
                    'date_expires' => $wc_coupon->get_date_expires(),
                    'discount_type' => $wc_coupon->get_discount_type(),
                    'usage_count' => $wc_coupon->get_usage_count(),
                    'product_ids' => $wc_coupon->get_product_ids(),
                    'usage_limit' => $wc_coupon->get_usage_limit(),
                    'usage_limit_per_user' => $wc_coupon->get_usage_limit_per_user(),
                    'minimum_amount' => $wc_coupon->get_minimum_amount(),
                    'maximum_amount' => $wc_coupon->get_maximum_amount(),
                    'used_by' => $wc_coupon->get_used_by(),
                ));
            }
        }
        return $data;
    }

    public function oliver_pos_get_countries() {
        global $woocommerce;
        $data = array();
        $countries_obj   = new WC_Countries();
        $countries   = $countries_obj->__get('countries');
        foreach ($countries as $key => $country) {
            array_push($data, array(
                'code' => $key,
                'name' => $country,
            ));
        }
        return $data;
    }

    public function oliver_pos_get_states() {
        global $woocommerce;
        $data = array();
        $countries_obj   = new WC_Countries();
        $countries   = $countries_obj->__get('countries');
        foreach ($countries as $key => $country) {
            $country_name = $key;
            $county_states = $countries_obj->get_states( $key );
            foreach ($county_states as $key => $value) {
                array_push($data, array(
                    'country' => $country_name,
                    'code' => $key,
                    'name' => $value,
                ));
            }
        }
        return $data;
    }

    /* Woo and wordpress general setting post Listner section */
    //Since 2.3.9.1 Update
    public function oliver_pos_woocommerce_general_settings_post_listener() {
        oliver_log("=== post woo general setting ===");
        $shop_general_setting_data = $this->oliver_pos_get_general_settings();
        wp_remote_post( esc_url_raw(ASP_TRIGGER_SETTING_SAVE), array(
            'headers'     => array('Content-Type' => 'application/json; charset=utf-8',
                'Authorization' => AUTHORIZATION,
            ),
            'body' => json_encode($shop_general_setting_data),
        ));
    }
    /* End Woo and Wordpress general setting Listner section */

    /* send email section */
    /*
		its getting two params $order_id, $receiver
	*/
    public function oliver_pos_send_email( $request_data ) {
        $parameters = $request_data->get_params();
        $message = "Invalid Request";
        $status = -1;
        if ( isset($parameters['order_id']) && !empty($parameters['order_id']) ) {
            $order_id = sanitize_text_field($parameters['order_id']);

            // Since 2.3.9.1 add email id for refund order
            if (isset($parameters['email']) && !empty($parameters['email'])) {
                oliver_log('send email to='.$parameters['email']);
                if (!email_exists($parameters['email'])) {
                    oliver_log('create new user then send email');
                    $random_password = wp_generate_password(12, true, false);
                    $data = array(
                        'user_login' => $parameters['email'],
                        'user_pass' => $random_password,
                        'show_admin_bar_front' => false,
                        'role' => 'customer',
                        'user_email' => $parameters['email'],
                    );
                    $customer_id = wp_insert_user($data);
                    wp_send_new_user_notifications($customer_id, 'user');
                    oliver_log('Create new customer using wc_create_new_customer()');
                }
                else{
                    $get_user_by_email = get_user_by('email', sanitize_email( $parameters['email'] ));
                    $customer_id = $get_user_by_email->ID;
                }
                $order = new WC_Order( $order_id );
                $order->set_customer_id( $customer_id );
                $order->set_billing_email($parameters['email']);
                $order->save();
            }
            //Since 2.3.8.7 add new email check parameter to send email check from
            return oliver_pos_send_order_email($order_id, $email_check=true);
        }
	    return oliver_pos_api_response( $message, $status );
    }
    /* send email section */

    /* category listener section */
    //Since update 2.3.9.0
    public function oliver_pos_category_created_listener( $term_id, $tt_id, $taxonomy ) {
        if ( strpos($taxonomy, 'product_cat') !== false ) {
            //$this->miscellaneous_sync_dotnet( $term_id, esc_url_raw( ASP_TRIGGER_CREATE_CATEGORY ), null);
            $this->oliver_pos_post_dot_net_sync_category( $term_id, esc_url_raw( ASP_TRIGGER_CREATE_CATEGORY ), null);
        } else {
            //Create sub attribute
            $this->oliver_pos_miscellaneous_sync_dotnet( $term_id, esc_url_raw( ASP_TRIGGER_CREATE_SUB_ATTRIBUTE ), $taxonomy);
        }
    }

    //Since update 2.3.9.0
    public function oliver_pos_category_updated_listener( $term_id, $tt_id, $taxonomy ) {
        if ( strpos($taxonomy, 'product_cat') !== false ) {
            //$this->miscellaneous_sync_dotnet( $term_id, esc_url_raw( ASP_TRIGGER_UPDATE_CATEGORY ), null);
            $this->oliver_pos_post_dot_net_sync_category( $term_id, esc_url_raw( ASP_TRIGGER_UPDATE_CATEGORY ), null);
        } else {
            //Update sub attribute
            $this->oliver_pos_miscellaneous_sync_dotnet( $term_id, esc_url_raw( ASP_TRIGGER_UPDATE_SUB_ATTRIBUTE ), $taxonomy);
        }
    }

    public function oliver_pos_category_deleted_listener( $term_id, $tt_id, $taxonomy, $deleted_term, $object_ids ) {
        if ( strpos($taxonomy, 'product_cat') !== false ) {
            $this->oliver_pos_miscellaneous_sync_dotnet( $term_id, esc_url_raw( ASP_TRIGGER_REMOVE_CATEGORY ), null);
        } else {
            $this->oliver_pos_miscellaneous_sync_dotnet( $term_id, esc_url_raw( ASP_TRIGGER_REMOVE_SUB_ATTRIBUTE ), $taxonomy);
        }

    }

    /* category Listner section */

    /* category Listner section */

    public function oliver_pos_attribute_created_listener( $attribute_id, $attribute_data ) {
        oliver_log("create attribute");
        //$this->miscellaneous_sync_dotnet( $attribute_id, esc_url_raw( ASP_TRIGGER_CREATE_ATTRIBUTE ), null);
        $this->oliver_pos_get_products_for_attribute( $attribute_id );
        wp_schedule_single_event(  time() + 5, 'woocommerce_attribute_create_delay', array($attribute_id, $attribute_data));
    }

    public function oliver_pos_attribute_create_listener_delay_call( $attribute_id, $attribute_data ) {
        oliver_log("create attribute delay");
        $this->oliver_pos_miscellaneous_sync_dotnet( $attribute_id, esc_url_raw( ASP_TRIGGER_CREATE_ATTRIBUTE ), null);
        $this->oliver_pos_get_products_for_attribute( $attribute_id );
    }
    //Since update 2.3.9.0
    public function oliver_pos_attribute_updated_listener( $attribute_id, $attribute, $old_attribute_name ) {
        oliver_log("update attribute");
        //$this->miscellaneous_sync_dotnet( $attribute_id, esc_url_raw( ASP_TRIGGER_UPDATE_ATTRIBUTE ), null);
        $this->oliver_pos_get_products_for_attribute( $attribute_id );
        wp_schedule_single_event(  time() + 5, 'woocommerce_attribute_updated_delay', array( $attribute_id, $attribute, $old_attribute_name ));
    }
    //Since version 2.3.8.1 Add
    public function oliver_pos_attribute_updated_listener_delay_call( $attribute_id, $attribute, $old_attribute_name ) {
        oliver_log('update attribute delay');
        $this->oliver_pos_miscellaneous_sync_dotnet( $attribute_id, esc_url_raw( ASP_TRIGGER_UPDATE_ATTRIBUTE ), null);
        $this->oliver_pos_get_products_for_attribute( $attribute_id );
    }

    public function oliver_pos_attribute_deleted_listener( $attribute_id, $attribute, $old_attribute_name ) {
        $this->oliver_pos_miscellaneous_sync_dotnet( $attribute_id, esc_url_raw( ASP_TRIGGER_REMOVE_ATTRIBUTE ), null);
    }

    /* category Listner section */

    private function oliver_pos_get_products_for_attribute( $attribute_id ) {
        $attribute = wc_get_attribute( $attribute_id );
        $attribute_slug = $attribute->slug;
        $sub_attributes = $this->oliver_pos_get_subattributes_by_attribute_slug( $attribute_slug );
        $this->oliver_pos_get_products_id_for_attribute( $attribute_slug, $sub_attributes );
    }

    public function oliver_pos_get_products_id_for_attribute( $slug, $sub_attributes ) {
        $product_ids = array();
        // The query
        $products = new WP_Query( array(
            'post_type'      => array('product'),
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'tax_query'      => array( array(
                'taxonomy'        => $slug,
                'field'           => 'slug',
                'terms'           => $sub_attributes,
                'operator'        => 'IN',
            ) )
        ) );

        // The Loop
        if ( $products->have_posts() ): while ( $products->have_posts() ):
            $products->the_post();
            $product_ids[] = $products->post->ID;
        endwhile;
            wp_reset_postdata();
        endif;
        // TEST: Output the Products IDs
        if ( !empty($product_ids) ) {
            $url = esc_url_raw( ASP_TRIGGER_BULK_ATTRIBUTE_PRODUCTS );
            $udid = ASP_DOT_NET_UDID;
            $object_ids = implode(",", $product_ids);

            wp_remote_get( "{$url}?udid={$udid}&productids={$object_ids}", array(
                'headers' => array(
	                'Authorization' => AUTHORIZATION,
                ),
            ));
        }
    }

    private function oliver_pos_get_products_for_subattribute( $slug ) {
        $this->oliver_pos_get_products_id_for_attribute($slug, $this->oliver_pos_get_subattributes_by_attribute_slug( $slug ));
    }

    private function oliver_pos_get_subattributes_by_attribute_slug( $slug ) {
        $data = array();

        if ( !empty($slug)) {
            $get_terms = get_terms( $slug, 'orderby=name&hide_empty=0' );
            if (!empty($get_terms)) {
                foreach ($get_terms as $key => $term) {
                    array_push($data, $term->slug);
                }
            }
        }
        return $data;
    }

    /**
     * Set oliver order email flag (send or not).
     * @since 2.3.3.2
     * @return void Return suceess status and message
     */
    public function oliver_pos_set_oliver_email_flag($request_data) {
        $parameters = $request_data->get_params();
        $flag = true;
        if (isset($parameters['flag'])) {
            $flag = $parameters['flag'];
        }
        update_option( 'oliver_pos_email_flag', $flag);
        return $flag;
    }

    /**
     * Set oliver order email flag (send or not).
     * @since 2.3.3.2
     * @return void Return suceess status and message
     */
    public function oliver_pos_get_oliver_email_flag() {
        $flag = get_option('oliver_pos_email_flag');
        return ($flag) ? $flag : true;
    }

    // === Super admin API's ===

    /**
     * Get all plugins list.
     * @since 2.3.3.1
     * @return array Returns list of all plugins
     */
    public function oliver_pos_get_all_plugins_details() {
        $get_plugins = get_plugins();
        if ( ! empty($get_plugins) && is_array($get_plugins) ) {
            foreach ($get_plugins as $key => $plugin) {
                $is_active = false;
                if (is_plugin_active( $key )) {
                    $is_active = true;
                }
                $get_plugins[ $key ]['is_active'] = $is_active;
            }
        }
        return empty($get_plugins) ? array() : $get_plugins;
    }

    /**
     * Get Wp and Site URL.
     * @since 2.3.3.1
     * @return array Returns wp and site url
     */
    public function oliver_pos_get_wp_site_url() {
        return array(
            'wp_url'   => $this->oliver_pos_get_wp_url(),
            'site_url' => $this->oliver_pos_get_site_url()
        );
    }

    /**
     * Get Wp URL.
     * @since 2.3.3.1
     * @return array Returns wp url
     */
    public function oliver_pos_get_wp_url() {
        return site_url();
    }

    /**
     * Get Site URL.
     * @since 2.3.3.1
     * @return array Returns site url
     */
    public function oliver_pos_get_site_url() {
        return home_url();
    }

    /**
     * Get PHP and MySql version.
     * @since 2.3.3.1
     * @return array Returns PHP and MySql version.
     */
    public function oliver_pos_get_php_mysql_version() {
        return array(
            'php'   => $this->oliver_pos_get_php_version(),
            'mysql' => $this->oliver_pos_get_mysql_version()
        );
    }

    /**
     * Get PHP version.
     * @since 2.3.3.1
     * @return array Returns PHP version.
     */
    public function oliver_pos_get_php_version() {
        return phpversion();
    }

    /**
     * Get MySql version.
     * @since 2.3.3.1
     * @return array Returns MySql version.
     */
    public function oliver_pos_get_mysql_version() {
        global $wpdb;
        $version = $wpdb->get_var("select version()");
        return $version;
    }

    /**
     * Get wp and wc version.
     * @since 2.3.3.1
     * @return array Return wp and wc version
     */
    public function oliver_pos_get_wp_wc_version() {
        return array(
            'wp' => $this->oliver_pos_get_wp_version(),
            'wc' => $this->oliver_pos_get_wc_version()
        );
    }

    /**
     * Get wp version.
     * @since 2.3.3.1
     * @return array Return wp version
     */
    public function oliver_pos_get_wp_version() {
        return get_bloginfo( 'version' );
    }

    /**
     * Get wc version.
     * @since 2.3.3.1
     * @return array Return wc version
     */
    public function oliver_pos_get_wc_version() {
        return get_option('woocommerce_version', true);
    }

    /**
     * Get count of all kind of products.
     * @since 2.3.3.1
     * @return array Return products count
     */
    public function oliver_pos_get_products_count() {
        return Pos_Bridge_Product::oliver_pos_get_products_count();
    }

    /**
     * Get count the orders eiher which creates by Oliver POS or shop
     * @since 2.3.6.1
     * @return int count of orders
     */
    public function oliver_pos_get_orders_count() {
        return Pos_Bridge_Order::oliver_pos_get_orders_count();
    }

    /**
     * Get configuration details.
     * @since 2.3.3.1
     * @return array Return configuration details
     */
    public function oliver_pos_get_bridge_details() {
        return array(
            'php' 			=> $this->oliver_pos_get_php_version(),
            'mysql' 		=> $this->oliver_pos_get_mysql_version(),
            'wp' 			=> $this->oliver_pos_get_wp_version(),
            'wc' 			=> $this->oliver_pos_get_wc_version(),
            'wp_url' 		=> $this->oliver_pos_get_wp_url(),
            'site_url' 		=> $this->oliver_pos_get_site_url(),
            'plugin_info' 	=> $this->oliver_pos_get_all_plugins_details(),
            'product_info' 	=> $this->oliver_pos_get_products_count(),
            // since 2.3.6.1
            //'order_info' 	=> $this->get_orders_count(),
            // since 2.3.3.2
            'email_flag'	=> $this->oliver_pos_get_oliver_email_flag()
        );
    }

    // === Super admin API's ===

    private function oliver_pos_miscellaneous_sync_dotnet( $object_id, $method, $is_taxonomy ) {
        $udid = ASP_DOT_NET_UDID;
        if ( ! is_null( $is_taxonomy ) ) {
            $url = "{$method}?udid={$udid}&wpid={$object_id}&taxonomy={$is_taxonomy}";
            $type = "Sub Attribute";

            wp_remote_get( esc_url_raw($url), array(
                'timeout'   => 0.01,
                'blocking'  => false,
                'sslverify' => false,
                'headers' => array(
	                'Authorization' => AUTHORIZATION,
                ),
            ));

            $this->oliver_pos_get_products_for_subattribute( $is_taxonomy );
        } else {
            $url = "{$method}?udid={$udid}&wpid={$object_id}";
            $type = "Category, Sub Category or Attribute";

            wp_remote_get( esc_url_raw($url), array(
                'timeout'   => 0.01,
                'blocking'  => false,
                'sslverify' => false,
                'headers' => array(
	                'Authorization' => AUTHORIZATION,
                ),
            ));
        }
    }

    //Add Since 2.3.9.0
    //Create category and update category;
    private function oliver_pos_post_dot_net_sync_category($id ,$method , $taxonomy) {
        $category_data = $this->oliver_pos_get_category_by_id( $id );
        wp_remote_post( esc_url_raw($method), array(
            'headers'     => array('Content-Type' => 'application/json; charset=utf-8',
                    'Authorization' => AUTHORIZATION,
                ),
            'body' => json_encode($category_data),

        ) );
    }

    // ====== connection checkinng ======== //

    public function oliver_pos_is_connection_alive() {
        $method = ASP_CHECK_IS_CONNECTION_ALIVE;
        $udid   = ASP_DOT_NET_UDID;
        $url = esc_url_raw( "{$method}?udid={$udid}" );

        $status = false;
        $message = 'success';

        $wp_remote_get = wp_remote_get( $url, array(
            'headers' => array(
	            'Authorization' => AUTHORIZATION,
            ),
        ));

        $response = json_decode( wp_remote_retrieve_body( $wp_remote_get ) );
        if (wp_remote_retrieve_response_code( $wp_remote_get ) == 200) {

            if (is_object( $response )) {
                if (isset($response->IsSuccess) && is_bool($response->IsSuccess)) {
                    $status  = $response->IsSuccess;
                    $message = $response->Message;
                } else {
                    $message = 'invalid JSON format.';
                }

            } else {
                $message = 'invalid response format, expact object in response.';
            }

        } elseif (wp_remote_retrieve_response_code( $wp_remote_get ) == 403) {
            $message = $response->Message;
        } else {
            $message = 'Could not resolve host.';
        }

        //For manage trigger sync
        $response__body = json_decode( wp_remote_retrieve_body( $wp_remote_get ) ); // get response data
        $response_code = wp_remote_retrieve_response_code( $wp_remote_get ); // get response code
        $sync = isset($response__body->IsSuccess) ? (bool) $response__body->IsSuccess : true;

        //invoke logger
        //Event_Log::pos_bridge_sync_logger( 'Checking Connecion', $url, $response_code, $sync );

        return array(
            'status'  => $status,
            'message' => $message,
            'code'    => $response_code
        );
    }

    // ====== connection checkinng ======== //

    // ====== resync records ======== //

    /**
     * Resync the remaining records
     * @since 2.1.2.2
     * @return boolean Return true.
     */
    /*public function resync_remaining_records()
    {
		$records = Event_Log::sync_remaining_records();
		print_r(array(
			'count' => $records,
			'status'=> true
		));

		exit;

    }*/
    // ====== resync records ======== //

    /**
     * Get products id and their quntity.
     * @since 2.3.8.6
     * @return array Returns products ids and Quantity.
     */
    public function oliver_pos_get_products_id_and_quantity() {
        global $post;
        $product_details = get_posts(
            array(
                'post_type' => array('product','product_variation'),
                'post_status' => array('publish','private'),
                'posts_per_page' => '-1'
            ));
        $products_data = array();
        foreach($product_details as $product_detail)
        {
            $product = wc_get_product($product_detail->ID);
            if(!empty($product)) {
                $products_data[] = array(
                    'product_id' => $product->get_id(),
                    'quantity' => $product->get_stock_quantity()
                );
            }
        }
        return $products_data;
    }
    /**
     * Set oliver shop settings.
     * @since 2.3.8.6
     * update 2.3.8.7
     * @return void Return shop setting status
     */
    public function oliver_pos_set_oliver_shop_settings($request_data) {
        $parameters = $request_data->get_params();

        oliver_log('set_oliver_shop_settings');
	    $email_to_customer=$parameters['send_order_email_to_customer']=='true'?1:0;
	    $email_to_admin=$parameters['send_order_email_to_admin']=='true'?1:0;
	    $show_back_links=$parameters['show_back_links']=='true'?1:0;
	    $print_on_online_order=$parameters['print_on_online_order']=='true'?1:0;

        update_option('send_order_email_to_customer', $email_to_customer);
        update_option('send_order_email_to_admin', $email_to_admin);
        update_option('show_back_links', $show_back_links);
        update_option('print_on_online_order', $print_on_online_order);
        return array('status' => 'success');
    }
    /**
     * Update plugin list when activate and deactivate
     * @since 2.3.8.7
     *
     */
    public function oliver_pos_wordpress_plugin_update_option($oldvalue , $newvalue) {
        $all_plugins = get_plugins();
        $clientGuid = get_option('oliver_pos_subscription_client_id');
        $active_plugins = [];
        if ($clientGuid) {
            $clientGuid = urlencode($clientGuid);
        }
        else{
            $clientGuid='';
        }
        //array_diff to get which plugin activate
        $plugin_results = array_diff($newvalue,$oldvalue);
        if(empty($plugin_results)){
            //array_diff to get which plugin deactivate
            $plugin_results=array_diff($oldvalue,$newvalue);
            $active_plugins['plugins_update']='deactivate';
        }
        else{
            $active_plugins['plugins_update']='activate';
        }
        foreach($plugin_results as $plugin_result)
        {
            $active_plugins[$all_plugins[$plugin_result]['Name']] = $all_plugins[$plugin_result]['PluginURI'];
        }
        $send_plugin_details = array(
            "clientUrl" => GET_SITE_URL,
            "clientGuid" => $clientGuid,
            "tablename" => ASP_PLUGIN_DETAILS,
            "data" =>$active_plugins
        );

        wp_remote_post( esc_url_raw( ASP_BRIDGEINFOPOST ), array(
            'headers'     => array('Content-Type' => 'application/json; charset=utf-8'),
            'body' => json_encode($send_plugin_details),
        ) );
    }
    /**
     * Add additional information to woocommerce single product page tab
     * @since 2.3.8.7
     */
    public function oliver_pos_woocommerce_additional_tab($tabs) {
        global $product;
        $product_stock = $product->is_in_stock();
        $store_address     = get_option( 'woocommerce_store_address' );
        $store_address_2   = get_option( 'woocommerce_store_address_2' );
        $store_city        = get_option( 'woocommerce_store_city' );
        $store_postcode    = get_option( 'woocommerce_store_postcode' );
        $full_address =  $store_address.$store_address_2.$store_city;
        if (get_option('show_back_links') == 1 and !empty($product_stock)) {
            ?>
            <table class="woocommerce-product-attributes shop_attributes">
                <tbody>
                <tr class="woocommerce-product-attributes-item woocommerce-product-attributes-item--weight">
                    <th class="woocommerce-product-attributes-item__label">Availability</th>
                    <td class="woocommerce-product-attributes-item__value">
                        <?php
                        echo 'Now available in-store ';
                        if(!empty($full_address))
                        {
                            echo 'at';
                            echo '<br>';
                            echo $store_address.' '. $store_address_2.' '.$store_city.' '.$store_postcode;
                        }
                        echo "<br>";
                        echo '<p style="font-size: 13px;">Powered With <a href="https://oliverpos.com/" target="_blank" >Oliver Pos</a></p>';
                        ?>
                    </td>
                </tr>
                </tbody>
            </table>
            <?php
        }
    }
    /**
     * Add additional information to woocommerce single product page tab
     * @since 2.3.8.8
     */
    public function oliver_pos_feedback_form($oliver_pos_plugins) {
        $troubleshoot_url = ASP_TROUBLESHOOT;
        if ( get_option('oliver_pos_subscription_client_id', false)) {
            $troubleshoot_url .= '?_client=' . get_option( 'oliver_pos_subscription_client_id' );
        }

        if ( get_option('oliver_pos_subscription_autologin_token', false)) {
            $troubleshoot_url .= '&_token=' . get_option( 'oliver_pos_subscription_autologin_token' );
        }
        ?>
        <div class="popup-overlay"></div>
        <div class="popup-content popup-sm">
            <div class="wpsite-content wpsite-content-100 blocker-font block-d-flex block-align-center"
                 id="oliver-modal-content">
                <div class="blocker-card">
                    <div class="blocker-card-header center-block block-justify-start">
                        Deactivation
                        <span class="close">
                    <img src="<?php echo plugins_url("public/resource/img/close-dark.svg", dirname(__FILE__));?>"
                         class="blocker-pop-close">
                </span>
                    </div>
                    <div class="blocker-card-body blocker-scroll">
                        <div class="blocker-font-12">
                            <div class="blocker-simple-content">
                                <div class="block-d-flex block-align-left">
                                    <p>
                                        If you have a moment, please let us know why you are deactivating Oliver POS. All
                                        submissions are anonymous and we only use this feedback to improve this plugin.
                                    </p>
                                </div>
                                <div class="block-d-flex block-align-center">
                                    <a href="<?php echo $troubleshoot_url;?>" target="_blank">
                                        <button class="blocker-primary-btn blocker-btn-sm blocker-white-nowrap"> Run Automatic
                                            Troubleshoot
                                        </button>
                                    </a>
                                    <span class="bold-text">
                                This fixes 99% of all issues related to Oliver POS
                            </span>
                                </div>

                                <script>
                                    hbspt.forms.create({
                                        portalId: "4818102",
                                        formId: "c39eab71-18cb-4565-8da0-14389e531a09",
                                        translations: {
                                            en: {
                                                submitText: "Submit & Deactivate",
                                            }
                                        },
                                        onFormSubmit: function($form) {
                                            $form.find('input[name="email"]').val(
                                                "<?php echo get_option('admin_email');?>");
                                            $form.find('input[name="website"]').val("<?php echo site_url();?>");
                                            jQuery.post("<?php echo admin_url( 'admin-ajax.php' );?>", {
                                                    'action': 'oliver_pos_deactivate_plugin',
                                                    'security': "<?php echo wp_create_nonce( 'oliver-pos-nonce' ); ?>",
                                                    'service': '1',
                                                },
                                                function(response) {
                                                    window.location.href = './plugins.php';
                                                }
                                            );
                                        }
                                    });
                                </script>
                            </div>
                        </div>
                    </div>
                    <div class="blocker-card-footer">
                        <div class="block-e-flex block-align-center block-justify-between">
                            <button
                                    class="blocker-danger-link-btn blocker-text-red blocker-font-14 oliver-skip-deactivate">Deactivate
                                Oliver POS</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        return $oliver_pos_plugins;
    }
    /**
     * Create warehouse.
     * @since 2.4.0.1
     * @return true
     */
    public function oliver_pos_create_warehouse( $request_data ) {
        global $wpdb;
        $parameters = $request_data->get_params();
        if ( ! empty( $parameters['Id'] ) ) {
            $name                  = isset( $parameters['Name'] ) ? sanitize_text_field( $parameters['Name'] ) : '';
            $type                  = isset( $parameters['Type'] ) ? sanitize_text_field( $parameters['Type'] ) : '';
            $relwarehouselocations = isset( $parameters['relWarehouseLocations'] ) ? serialize( $parameters['relWarehouseLocations'] ) : '';
            $isdefault             = isset( $parameters['IsDefault'] ) ? sanitize_text_field( $parameters['IsDefault'] ) : '';
            $syncerror             = isset( $parameters['SyncError'] ) ? sanitize_text_field( $parameters['SyncError'] ) : '';
            $isdeleted             = isset( $parameters['IsDeleted'] ) ? sanitize_text_field( $parameters['IsDeleted'] ) : '';
            $warehouser_id         = isset( $parameters['Id'] ) ? absint( $parameters['Id'] ) : 0;
            $time                  = current_time( 'mysql' );
            $table                 = $wpdb->prefix . 'pos_warehouse';

			// Prepare the SQL query
			$sql = $wpdb->prepare( 
				"INSERT INTO $table (name, type, time, oliver_warehouseid, relwarehouselocations, isdefault, isdeleted, syncerror) 
				VALUES (%s, %s, %s, %d, %s, %s, %s, %s)", 
				$name, $type, $time, $warehouser_id, $relwarehouselocations, $isdefault, $isdeleted, $syncerror 
			);

			// Insert data into the database
			$result = $wpdb->query( $sql );
            // Check if insertion was successful
            if ( $result ) {
                // Log success message
                oliver_log( 'create warehouse response=' . $wpdb->insert_id );
                // Warehouse updated successfully
                oliver_log( 'warehouse updated =' . $warehouser_id );
                return true;
            } else {
                // Log failure message
                oliver_log( 'warehouse creation failed' );
                return false;
            }
        } else {
	        return oliver_pos_api_response('Empty warehouse id', -1);
        }
    }

    /**
     * update warehouse.
     * @since 2.3.9.8
     * @return update
     */
    public function oliver_pos_update_warehouse( $request_data ) {
        global $wpdb;
        $parameters = $request_data->get_params();
        if ( ! empty( $parameters['Id'] ) ) {
            $name                  = sanitize_text_field( $parameters['Name'] );
            $type                  = sanitize_text_field( $parameters['Type'] );
            $isdefault             = sanitize_text_field( $parameters['IsDefault'] );
            $SyncError             = sanitize_text_field( $parameters['SyncError'] );
            $relwarehouselocations = serialize( $parameters['relWarehouseLocations'] );
            $oliver_warehouseid    = sanitize_text_field( $parameters['Id'] );
            $IsDeleted             = sanitize_text_field($parameters['IsDeleted']);
            $time                  = current_time( 'mysql' );
            $table                 = $wpdb->prefix . 'pos_warehouse';
            $responce = $wpdb->query($wpdb->prepare( "UPDATE $table SET name = %s, type = %s, relwarehouselocations = %s, isdefault = %s, time = %s WHERE oliver_warehouseid = %d", $name, $type, $relwarehouselocations, $isdefault, $time, $oliver_warehouseid ));
            oliver_log( 'update responce=' . $responce );
            if ( $responce ) {
                oliver_log('warehouse updated ='.$oliver_warehouseid);
                return true;
            }
            return false;
        } else {
	        return oliver_pos_api_response('Empty warehouse id', -1);
        }
    }
    /**
     * Delete warehouse.
     * @since 2.3.9.8
     * @return true
     */
    public function oliver_pos_delete_warehouse( $request_data ) {
        global $wpdb;
        $parameters = $request_data->get_params();
        if ( ! empty( $parameters['id'] ) ) {
            $oliver_warehouseid = sanitize_text_field( $parameters['id'] );
            $table              = $wpdb->prefix . "pos_warehouse";
            $responce           = $wpdb->delete( $table, array( 'oliver_warehouseid' => $oliver_warehouseid ) );
            oliver_log( 'delete responce=' . $responce );
            if ( $responce ) {
                //Delete warehouse from product
                $table_postmeta = $wpdb->prefix . 'postmeta';
                $wpdb->delete( $table_postmeta, array( 'meta_key' => '_warehouse_' . $oliver_warehouseid ) );
                oliver_log( 'warehouse deleted =' . $oliver_warehouseid );
                return true;
            }
            return false;
        } else {
	        return oliver_pos_api_response('Empty warehouse id', -1);
        }
    }
    /**
     * Get all warehouse.
     * @since 2.3.9.8
     */
    public function oliver_pos_get_warehouse() {
        global $wpdb;
        $data = array(
            'is_success' => true,
            'content'    => $this->oliver_pos_get_warehouse_details(),
        );
        return $data;
    }
    /**
     * Get all warehouse details.
     * @since 2.3.9.8
     */
    public function oliver_pos_get_warehouse_details() {
        global $wpdb;
        $data_warehouse = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}pos_warehouse", OBJECT );
        $data = array();
        foreach ( $data_warehouse as $key => $result ) {
            $data[] = array(
                'Name'                  => $result->name,
                'Type'                  => $result->type,
                'IsDefault'             => $result->isdefault,
                'relWarehouseLocations' => array(),
                'Id'                    => $result->oliver_warehouseid,
                'IsDeleted'             => $result->isdeleted,
            );
        }
        return $data;
    }
    /**
     * Update Product Quantity  warehouse.
     * @since 2.3.9.8
     */
    public function oliver_pos_wqty_bulk_update( $request_data ) {
        global $wpdb;
        $parameters  = $request_data->get_params();
        $update_post = 0;
        $response    = array();
        foreach ( $parameters as $parameter ) {
            $data                  = array();
            $product_id            = $parameter['ProductId'];
            $product_qty           = $parameter['Quantity'];
            $product_warehouse_id  = $parameter['WarehouseId'];
            $data_warehouse        = $wpdb->get_results( "SELECT isdefault FROM {$wpdb->prefix}pos_warehouse WHERE oliver_warehouseid = '". $product_warehouse_id ."'", OBJECT );
            oliver_log( 'warehouse_id = ' . $product_warehouse_id );
            oliver_log( 'product_id = ' . $product_id );
            oliver_log( 'quantity = ' . $product_qty );
	        if ( empty($data_warehouse) || $data_warehouse[0]->isdefault == 1 || $product_warehouse_id==0 ) {
                $product = wc_get_product( $product_id );
                if(!empty($product)) {
                    $product->set_manage_stock(true);
                    $product->set_stock_quantity($product_qty);
                    $product->save();
                    $update_post = 1;
                }
            } else {
                $update_post = update_post_meta( $product_id, '_warehouse_'.$product_warehouse_id , $product_qty);
            }
            if ( $update_post ) {
                $data['WarehouseId'] = $parameter['WarehouseId'];
                $data['ProductId']   = $parameter['ProductId'];
                $data['Quantity']    = $parameter['Quantity'];
                $data['Success']     = true;
                $response[]          = $data;
            }
        }
        return $response;
    }
    /**
     * Show oliver pos payment methods to woocommerce.
     * @since 2.4.0.7
     */
    public static function oliver_pos_add_payment_method_to_old_order() {
        $get_val = get_option('oliver_old_done_order_done_for_payment_method');
        if(empty($get_val)){
            $process_order=0;
            $orders = get_posts(array(
                'posts_per_page'  => -1,
                'fields'          => 'ids',
                'post_status' 	  => OP_ORDER_STATUS,
                'post_type'       => OP_POST_TYPE,
                'meta_query'      => array(
                    array(
                        'key'       => '_oliver_pos_receipt_id',
                        'compare'   => 'EXISTS'
                    )
                )
            ));

            $oliver_total_order = count($orders);
            foreach($orders as $order_id){
                $payment_method = get_post_meta($order_id, '_payment_method', true);

                if(empty($payment_method)){

                    $oliver_order_payments = get_post_meta($order_id, '_oliver_order_payments', true);
                    $payment_method ='';
                    foreach($oliver_order_payments as $payment){
                        $payment_method .=$payment['type'].',';
                    }
                    $payment_methods = rtrim($payment_method, ',');
                    update_post_meta( $order_id, '_payment_method', $payment_methods . ' (POS)' );
                    update_post_meta( $order_id, '_payment_method_title',  $payment_methods );
                }
                $process_order++;
                update_option('oliver_old_done_order_done_for_payment_method','done');
            }
        }
    }
	/**
	 * Show oliver pos payment methods to woocommerce.
	 * @since 2.4.0.7
	 */
	public static function oliver_pos_get_oliver_setting() {
		return array(
			'send_order_email_to_admin' => get_option('send_order_email_to_admin'),
			'send_order_email_to_customer' => get_option('send_order_email_to_customer'),
			'oliver_pos_subscription_client_id' => stripslashes( get_option('oliver_pos_subscription_client_id')),
			'oliver_pos_subscription_udid' => get_option('oliver_pos_subscription_udid'),
			'oliver_pos_subscription_token' => get_option('oliver_pos_subscription_token'),
			'oliver_pos_subscription_autologin_token' => get_option('oliver_pos_subscription_autologin_token'),
			'oliver_pos_previouse_version' => get_option('oliver_pos_previouse_version'),
			'oliver_pos_authorization_token' => get_option('oliver_pos_authorization_token'),
			'oliver_pos_email_flag' => get_option('oliver_pos_email_flag'),
			'show_back_links' => stripslashes( get_option('show_back_links')),
			'print_on_online_order' => get_option('print_on_online_order'),
			'oliver_old_done_order_done_for_payment_method' => get_option('oliver_old_done_order_done_for_payment_method'),
			'pos_bridge_plugin_do_activation_redirection' => get_option('pos_bridge_plugin_do_activation_redirection'),
			'cerber_configuration' => get_option('cerber_configuration'),
			'cerber-hardening' => get_option('cerber-hardening'),
			'oliver_pos_sync_data' => get_option('oliver_pos_sync_data'),
		);
	}
	/**
	 * Set Sync data status
	 * @since 2.4.1.3
	 */
	public static function oliver_pos_get_sync_status() {
		update_option('oliver_pos_sync_data', 'synced');
		return oliver_pos_api_response('saved', 1);
	}
	/**
     *
     * @return void create hidden field in general setting panel
     */
	public function oliver_pos_add_setting_field() {
		// register new setting field
		register_setting('general', 'oliver_pos_general_setting_field');
		// add new setting field
		add_settings_field(
			'oliver_pos_general_setting_field',
			'',
			function () {
				echo '<input type="hidden" id="oliver_pos_general_setting_field" name="oliver_pos_general_setting_field" value="' . date('y-m-d H:i:s').'" />';
			},
			'general'
		);
	}
	/**
	 * send points and rewards setting
	 * @since 2.4.1.6
	 */
	public static function oliver_pos_get_points_setting() {
		if ( ! in_array( 'woocommerce-points-and-rewards/woocommerce-points-and-rewards.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			return oliver_pos_api_response('plugin not activated', -1);
		}
		return array(
			'earn_points_ratio' => get_option('wc_points_rewards_earn_points_ratio'),
			'earn_points_rounding' => get_option('wc_points_rewards_earn_points_rounding'),
			'redeem_points_ratio_points' => get_option('wc_points_rewards_redeem_points_ratio'),
			'partial_redemption_enabled' => get_option('wc_points_rewards_partial_redemption_enabled'),
			'cart_min_discount' => get_option('wc_points_rewards_cart_min_discount'),
			'cart_max_discount' => get_option('wc_points_rewards_cart_max_discount'),
			'max_discount' => get_option('wc_points_rewards_max_discount'),
			'points_tax_application' => get_option('wc_points_rewards_points_tax_application'),
			'points_label' => get_option('wc_points_rewards_points_label'),
			'account_signup_points' => get_option('wc_points_rewards_account_signup_points'),
			'write_review_points' => get_option('wc_points_rewards_write_review_points'),
			'points_expiry' => get_option('wc_points_rewards_points_expiry'),
			'points_expire_points_since' => get_option('wc_points_rewards_points_expire_points_since'),
		);
	}
	/**
	 * Get Woo all log files name.
	 * @since 2.4.1.7
	 */
	public function oliver_pos_get_woo_log_files_name() {
		$files  = @scandir( WC_LOG_DIR );
		$result = array();
		if ( ! empty( $files ) ) {
			foreach ( $files as $key => $value ) {
				if ( ! in_array( $value, array( '.', '..' ), true ) ) {
					if ( ! is_dir( $value ) && strstr( $value, '.log' ) ) {
						$result[] = basename($value, '.log');
					}
				}
			}
		}
		return $result;
	}
	/**
	 * Get Woo single log file with name.
	 * @since 2.4.1.7
	 * return file data
	 */
	public function oliver_pos_get_woo_log_file($request_data) {
		$parameters = $request_data->get_params();
		if (isset($parameters['file']) && !empty($parameters['file'])) {
			$file_path = WC_LOG_DIR . $parameters['file'] . '.log';
			if (file_exists($file_path)) {
				return  file_get_contents( $file_path );
			}
			else {
				return ['message' => 'Woo log file not exists.', 'status' => -1];
			}
		}
		return ['message' => 'invalid Request.', 'status' => -1];
	}
}
