<?php
namespace bridge_models;
defined( 'ABSPATH' ) || exit;

use WC_Order;
use WC_Customer;
use WP_Query;
use WC_API_Exception;
use WP_User;
use WP_Error;

/**
 *  In this class the term "user | users" denote only for customer | customers
 *  because in oliver pos web register we can only doing operation with customers
 */

class Pos_Bridge_User
{

    function __construct()
    {
        # code...
    }

    /**
     * Get users (pagination)
     * @param $page page number
     * @param $limit limit
     * @return array return array of remaining customers
     * @Update since 2.3.8.6
     */
    public function oliver_pos_get_paged_users( $page, $limit ) {
        $users = array();
        global $wp_roles;
        $all_roles = $wp_roles->roles;
        $roles_key = array();
        foreach ( $all_roles as $key => $all_role )
        {
            $roles_key[] = $key;
        }
        $del_val= 'administrator';
        if (($key = array_search($del_val, $roles_key)) !== false) {
            unset($roles_key[$key]);
        }

        $user_args = array(
            'role__in'     => $roles_key,
            'number'     => $limit,
            'paged' => $page
        );

        $get_users = get_users( $user_args );

        if(! empty($get_users)){
            foreach ($get_users as $key => $user) {
                $customer_id = $user->ID;
                array_push($users, $this->oliver_pos_get_user($customer_id));
            }
        }

        if ( empty($users) ) {
	        return oliver_pos_api_response('no record found', -1);
        }

        return $users;
    }

    /**
     * Get remaining users
     * @param $remainig
     * @return array return array of remaining customers
     * @Update since 2.3.8.6
     */
    public function oliver_pos_get_remainig_users( $remainig ) {
        $users = array();
        global $wp_roles;
        $all_roles = $wp_roles->roles;
        $roles_key = array();
        foreach ( $all_roles as $key => $all_role )
        {
            $roles_key[] = $key;
        }
        $del_val= 'administrator';
        if (($key = array_search($del_val, $roles_key)) !== false) {
            unset($roles_key[$key]);
        }
        $user_args = array(
            'role__in'     => $roles_key,
            'number'     => $remainig,
        );
        $get_users = get_users( $user_args );

        if(! empty($get_users)){
            foreach ($get_users as $key => $user) {
                $customer_id = $user->ID;
                array_push($users, $this->oliver_pos_get_user($customer_id));
            }
        }

        if ( empty($users) ) {
	        return oliver_pos_api_response('No record found', -1);
        }

        return $users;
    }


    /**
     * Get the user by customer id
     * @param $id
     * @return object Customer details
     */
    public function oliver_pos_get_user( $id ) {
        global $wpdb;

        if ( is_wp_error( $id ) ) {
            return $id;
        }

        $customer      = new WC_Customer( $id );
        // $last_order    = $customer->get_last_order();
        $customer_data = array(
            'id'               => $customer->get_id(),
            'created_at'       => $customer->get_date_created() ? $customer->get_date_created()->getTimestamp() : 0, // API gives UTC times.
            'email'            => $customer->get_email(),

            'first_name'       => $customer->get_first_name(),
            'last_name'        => $customer->get_last_name(),
            'username'         => $customer->get_username(),
            'role'             => $customer->get_role(),
            'last_order_id'    => 0,
            // 'last_order_id' => is_object( $last_order ) ? $last_order->get_id() : null,
            'last_order_date'  => null, // API gives UTC times.
            // 'last_order_date'=> is_object( $last_order ) ? $last_order->get_date_created() ? $last_order->get_date_created()->getTimestamp() : 0 : null, // API gives UTC times.
            'orders_count'     => 0,
            // 'orders_count'  => $customer->get_order_count(),
            'total_spent'      => 0,
            // 'total_spent'   => wc_format_decimal( $customer->get_total_spent(), 2 ),
            'avatar_url'       => $customer->get_avatar_url(),
            'store_credit'     => (float) esc_attr(get_user_meta($customer->get_id(), 'oliver_store_credit', true)),
            'user_note'		   => esc_attr(get_user_meta($customer->get_id(), 'user_note', true)),
            'wc_points'		   => $this->oliver_pos_wc_points_balance( $customer->get_id() ),
            'billing_address'  => array(
                'first_name' => $customer->get_billing_first_name(),
                'last_name'  => $customer->get_billing_last_name(),
                'company'    => $customer->get_billing_company(),
                'address_1'  => $customer->get_billing_address_1(),
                'address_2'  => $customer->get_billing_address_2(),
                'city'       => $customer->get_billing_city(),
                'state'      => $customer->get_billing_state(),
                'postcode'   => $customer->get_billing_postcode(),
                'country'    => $customer->get_billing_country(),
                'email'      => $customer->get_billing_email(),
                'phone'      => $customer->get_billing_phone(),
            ),
            'shipping_address' => array(
                'first_name' => $customer->get_shipping_first_name(),
                'last_name'  => $customer->get_shipping_last_name(),
                'company'    => $customer->get_shipping_company(),
                'address_1'  => $customer->get_shipping_address_1(),
                'address_2'  => $customer->get_shipping_address_2(),
                'city'       => $customer->get_shipping_city(),
                'state'      => $customer->get_shipping_state(),
                'postcode'   => $customer->get_shipping_postcode(),
                'country'    => $customer->get_shipping_country(),
            ),
        );
        return $customer_data;
    }


    /**
     * Get the orders by customer id
     * @param int $id
     * @param int $page
     * @param int $from
     * @param int $to
     * @return array Return array of user/customer orders
     */
    public function oliver_pos_get_user_order( $id, $page, $from, $to ) {
        $pos_order = new Pos_Bridge_Order();
        $page = is_null($page) ? 1 : $page ;
        $args = array('posts_per_page'   => 10,
            'orderby'          => 'post_date',
            'order'            => 'DESC',
            'post_status' => OP_ORDER_STATUS,
            'post_type' => OP_POST_TYPE,
            'paged'    => $page,
            'meta_query' => array(
                array(
                    'key'     => '_customer_user',
                    'value'   => $id,
                    'compare' => '=',
                ),
            ),
        );

        if ( !empty($from) && !empty($to) ) {
            $args['date_query'] = array(
                array(
                    'after'     => date( 'Y-m-d', strtotime( $from ) ),
                    'before'    => date( 'Y-m-d', strtotime( $to ) ),
                    'inclusive' => true,
                ),
            );
        }

        $customer_orders = get_posts( $args );

        if(!empty($customer_orders)){
            foreach($customer_orders as $key => $customer_order) {
                $order_id = (int)$customer_order->ID;
                $data[] = $pos_order->oliver_pos_get_order( $order_id, null, array() );
            }
        }

        if ( !empty($data) ) {
            return $data;
        } else {
	        return oliver_pos_api_response('No Order Found', -1);
        }
    }

    /**
     * Delete user by ID
     * @param int $id
     * @return array Success message
     * Since 2.3.8.6 update checkpoint and include user file
     */
    public function oliver_pos_delete_user($id) {
        require_once(ABSPATH.'wp-admin/includes/user.php' );
        $user_details = get_userdata($id);

        if(!$user_details){
            oliver_log('user not found');
	        return oliver_pos_api_response('User id not exist', -1);
        }
        // Get all the user roles as an array.
        $user_roles = $user_details->roles;
        if(in_array( 'administrator', $user_roles, true))
        {
            oliver_log('admin user');
	        return oliver_pos_api_response('Shop admin can not deleted', -1);
        }

        $user_deleted_res = wp_delete_user($id);
        if($user_deleted_res){
            oliver_log('user deleted');
	        return oliver_pos_api_response('Record Deleted', 1);
        }
        else{
            oliver_log('user not deleted');
	        return oliver_pos_api_response('Record not Deleted', -1);
        }
    }

    /**
     * Delete customer by customer email
     * @since 2.2.0.1
     * @param string $email
     * @return object Returns API response.
     * Since 2.3.8.4 update checkpoint and include user file
     */
    public function oliver_pos_delete_user_by_email($email) {
        oliver_log('delete user by email');
        require_once(ABSPATH.'wp-admin/includes/user.php' );
        if ( email_exists($email)) {
            $get_user_by_email = get_user_by('email', sanitize_email( $email ));
            if ( ! empty($get_user_by_email)) {
                $id = (integer) $get_user_by_email->ID;
                if(is_super_admin($id)){
	                return oliver_pos_api_response('Shop admin can not deleted', -1);
                }
                $user_deleted_res = wp_delete_user($id);
                if($user_deleted_res){
                    oliver_log('user deleted');
	                return oliver_pos_api_response('User Deleted Successfully', 1);
                }
                else{
                    oliver_log('user not deleted');
	                return oliver_pos_api_response('User not deleted', -1);
                }
            }
	        return oliver_pos_api_response('User id not Exist', -1);
        }
	    return oliver_pos_api_response('Email ID not exist', -1);
    }

    /**
     * Get the count of customers
     * @return int Count of customers.
     *
     */
    public function oliver_pos_count_users() {
        $count = 0;
        $result = count_users();

        if (isset($result['total_users']) && !empty($result['total_users'])) {
            $count = $result['total_users'];
        }

        return $count;
    }

    /**
     * Create new user
     * @param array $param
     * @return array Customer details on success | Error message
     * @since 2.3.8.5
     * update wp_insert_user
     */
    public function oliver_pos_create_user( $param ) {
        oliver_log( "===== ===== ===== ===== =====" );
        oliver_log( "start create_user()" );

        if (!empty($param['email'])) {
            if (email_exists($param['email'])) {
                return $this->oliver_pos_update_user($param);
            } else {
                $random_password = wp_generate_password( 12, true, false );
                $data = array(
                    'user_login'           => $param['email'],
                    'user_pass'            => $random_password,
                    'show_admin_bar_front' => false,
                    'role' => 'customer',
                    'user_email' => $param['email'],
                );
                $wp_create_user_id = wp_insert_user($data);
                wp_send_new_user_notifications( $wp_create_user_id, 'user');
                oliver_log( "Create new customer using wc_create_new_customer()" );

                if ( is_integer($wp_create_user_id) ) {
                    $customer = new WC_Customer( $wp_create_user_id );
                    $id = $customer->get_id();
                    $customer->set_first_name(isset($param['first_name']) ? sanitize_text_field($param['first_name']) : '');
                    $customer->set_last_name(isset($param['last_name']) ? sanitize_text_field($param['last_name']) : '' );
                    $customer->save();

                    add_user_meta($id, 'user_note', sanitize_text_field( isset( $param['note'] ) ? $param['note'] : '') );

                    oliver_log("Set customer information");
                    // set customer billing address
                    $this->oliver_pos_set_customer_billing_address( $param, $id);
                    $this->oliver_pos_set_customer_shipping_address( $param, $id);

                    oliver_log("set customer billing address");
                    oliver_log("Close create_user()");
                    // get customer data.
                    return $this->oliver_pos_get_user( $customer->get_id() );
                } else {
                    $send_errores= '';
                    $error_count=1;
                    foreach($wp_create_user_id->errors as $wp_errors){
                        foreach($wp_errors as $wp_error){
                            $send_errores.= $error_count.'.'.$wp_error;
                            $error_count++;
                        }
                    }
	                return oliver_pos_api_response($send_errores, -1);
                }
            }
        } else {
	        return oliver_pos_api_response('Email required', -1);
        }
    }

    /**
     * Update the customer details
     * @param array $param
     * @return array Customer details on success | Error message
     * Since 2.3.8.8 Update some checks
     */
    public function oliver_pos_update_user( $param ) {

        oliver_log( "===== ===== ===== ===== =====" );
        oliver_log( "start update_user()" );
        // Update customer.
        if (empty($param['email'])) {
            oliver_log("user Invalid Requst");
	        return oliver_pos_api_response('Invalid Request', -1);
        }
        //if user id not exists then create new user
        if (!empty( $param['id'] )){
            //search users with email
            $get_user_details = get_user_by('email', sanitize_email( $param['email']) );
            if(empty($get_user_details)){
                $check_user_id = $param['id'];
            }
            else{
                $check_user_id = $get_user_details->ID;
            }
            if($check_user_id==$param['id']){

                oliver_log("updated email");
                wp_update_user( array(
                    'ID' => $param['id'],
                    'user_email' => $param['email']
                ) );

                $get_user = get_user_by( 'id', $param['id'] );

                if ( !empty( $get_user )) {
                    $id = (integer) $get_user->ID;
                    $customer = new WC_Customer( $id );
                    oliver_log("Get customer id by email=".$id);
                    $customer->set_first_name( ! empty( $param['first_name'] ) ? sanitize_text_field($param['first_name']) : '' );
                    $customer->set_last_name( ! empty( $param['last_name'] ) ? sanitize_text_field($param['last_name']) : '' );
                    $customer->save();

                    update_user_meta($id, 'user_note', sanitize_text_field( ! empty( $param['note'] ) ? $param['note'] : '') );

                    oliver_log("Save customer informations");

                    // set customer billing address
                    $this->oliver_pos_set_customer_billing_address( $param, $id);
                    $this->oliver_pos_set_customer_shipping_address( $param, $id);

                    // get customer data.
                    return $this->oliver_pos_get_user( $customer->get_id() );
                }
                else {
	                return oliver_pos_api_response('customer not exists', -1);
                }
            }
            else{
	            return oliver_pos_api_response('Email already exist', -1);
            }

        } else {
            $get_user_by_email = get_user_by('email', sanitize_email($param['email']));
            if (!empty($get_user_by_email)) {

                $id = (integer) $get_user_by_email->ID;
                $user_data = $this->oliver_pos_get_user( $id );
                return $user_data;
            }
	        return oliver_pos_api_response('Email id not exist', -1);
        }
    }

    /**
     * Set customer billing address
     * @param array $param
     * @param int $customer_id
     * @return void return void
     *
     */
    protected function oliver_pos_set_customer_billing_address( $param , $customer_id ) {
        $customer = new WC_Customer( $customer_id );
        // initialize variables
        $country   = isset($param['country']) ? sanitize_text_field($param['country']) : '';
        $state 	   = isset($param['state']) ? sanitize_text_field($param['state']) : '';
        $postcode  = isset($param['post_code']) ? sanitize_text_field($param['post_code']) : '';
        $city 	   = isset($param['city']) ? sanitize_text_field($param['city']) : '';
        $customer->set_billing_location( $country, $state, $postcode, $city );
        $customer->set_billing_email( $customer->get_email() );
        $customer->set_billing_first_name( $customer->get_first_name() );
        $customer->set_billing_last_name( $customer->get_last_name() );
        $customer->set_billing_phone( isset( $param['contact_number'] ) ? sanitize_text_field($param['contact_number']) : '' );
        $customer->set_billing_address_1( isset( $param['address'] ) ? sanitize_text_field($param['address']) : '' );
        $customer->set_billing_address_2( isset( $param['address2'] ) ? sanitize_text_field($param['address2']) : '' );
        $customer->save();
    }

    /**
     * Set customer shipping address
     * @param array $param
     * @param int $customer_id
     * @return void return void
     * @since 2.4.0.9
     * Developer note: Please don't add shipping email, it will generate fatal error.
     */
    protected function oliver_pos_set_customer_shipping_address( $param, $customer_id ) {
        $customer = new WC_Customer( $customer_id );
        $customer->set_shipping_first_name( isset( $param['shipping_first_name'] ) ? sanitize_text_field($param['shipping_first_name']) : '' );
        $customer->set_shipping_last_name( isset( $param['shipping_last_name'] ) ? sanitize_text_field($param['shipping_last_name']) : '' );
        $customer->set_shipping_phone( isset( $param['shipping_phone'] ) ? sanitize_text_field($param['shipping_phone']) : '' );
        $customer->set_shipping_address_1( isset( $param['shipping_address_line1'] ) ? sanitize_text_field($param['shipping_address_line1']) : '' );
        $customer->set_shipping_address_2( isset( $param['shipping_address_line2'] ) ? sanitize_text_field($param['shipping_address_line2']) : '' );
        $customer->set_shipping_city( isset( $param['shipping_city'] ) ? sanitize_text_field($param['shipping_city']) : '' );
        $customer->set_shipping_postcode( isset( $param['shipping_pincode'] ) ? sanitize_text_field($param['shipping_pincode']) : '' );
        $customer->set_shipping_country( isset( $param['shipping_country'] ) ? sanitize_text_field($param['shipping_country']) : '' );
        $customer->set_shipping_state( isset( $param['shipping_state'] ) ? sanitize_text_field($param['shipping_state']) : '' );
        $customer->save();
    }

    /**
     * Set store credit
     * @since 2.1.3.2
     * @param string email
     * @param float email
     * @return object Returns customer details.
     */
    public function oliver_pos_set_store_credit($email, $amount=0)
    {
        oliver_log( "start set store credit" );
        if (email_exists( $email )) {
            $user = get_user_by('email', sanitize_email( $email ));
            oliver_log("Get customer id by email");

            if (!empty($user)) {
                $id = $user->ID;
                update_user_meta($id, 'oliver_store_credit', sanitize_text_field($amount));

                oliver_log("Close set store credit");
                // get customer data.
                return $this->oliver_pos_get_user($id);
            } else {
	            return oliver_pos_api_response('Email not exists', -1);
            }

        } else {
	        return oliver_pos_api_response('Email not exists', -1);
        }
    }

    /**
     * Get store credit
     * @since 2.1.3.2
     * @param string email
     * @return float Returns customer store credit amount.
     */
    public function oliver_pos_get_store_credit($email)
    {
        if (email_exists( $email )) {
            $user = get_user_by('email', sanitize_email( $email ));
            oliver_log("Get customer id by email");
            if (!empty($user)) {
                $id = (integer) $user->ID;
                return (float) esc_attr( get_user_meta($id, 'oliver_store_credit', true));
            }
            return 0;
        }
        return 0;
    }

    /**
     * Validate the request by checking:
     *
     * 1) the ID is a valid integer
     * 2) the ID returns a valid WP_User
     * 3) the current user has the proper permissions
     *
     * @see WC_API_Resource::validate_request()
     * @param integer $id the customer ID
     * @param string $type the request type, unused because this method overrides the parent class
     * @param string $context the context of the request, either `read`, `edit` or `delete`
     * @return int|WP_Error valid user ID or WP_Error if any of the checks fails
     */
    protected function oliver_pos_validate_request( $id, $type, $context ) {

        try {
            $id = absint( $id );

            // validate ID
            if ( empty( $id ) ) {
                throw new WC_API_Exception( 'woocommerce_api_invalid_customer_id', __( 'Invalid customer ID', 'woocommerce' ), 404 );
            }

            // non-existent IDs return a valid WP_User object with the user ID = 0
            $customer = new WP_User( $id );

            if ( 0 === $customer->ID ) {
                throw new WC_API_Exception( 'woocommerce_api_invalid_customer', __( 'Invalid customer', 'woocommerce' ), 404 );
            }

            // validate permissions
            switch ( $context ) {

                case 'read':
                    if ( ! current_user_can( 'list_users' ) ) {
                        throw new WC_API_Exception( 'woocommerce_api_user_cannot_read_customer', __( 'You do not have permission to read this customer', 'woocommerce' ), 401 );
                    }
                    break;

                case 'edit':
                    if ( ! current_user_can( 'edit_users' ) ) {
                        throw new WC_API_Exception( 'woocommerce_api_user_cannot_edit_customer', __( 'You do not have permission to edit this customer', 'woocommerce' ), 401 );
                    }
                    break;

                case 'delete':
                    if ( ! current_user_can( 'delete_users' ) ) {
                        throw new WC_API_Exception( 'woocommerce_api_user_cannot_delete_customer', __( 'You do not have permission to delete this customer', 'woocommerce' ), 401 );
                    }
                    break;
            }
            return $id;
        } catch ( WC_API_Exception $e ) {
            return new WP_Error( $e->getErrorCode(), $e->getMessage(), array( 'status' => $e->getCode() ) );
        }
    }
	/**
	 * send user points for points and rewards
	 * @since 2.4.1.6
	 */
    public function oliver_pos_wc_points_balance( $user_id ) {
        $points_balance =0;
        if ( is_plugin_active( 'oliver-pos-points-and-rewards/oliver-pos-points-and-rewards.php' ) ){
            //return esc_attr(get_user_meta($user_id, 'wc_points_balance', true));
            global $wc_points_rewards, $wpdb;
            $query = "SELECT * FROM {$wc_points_rewards->user_points_db_tablename} WHERE user_id = %d AND points_balance != 0";
            $points = $wpdb->get_results( $wpdb->prepare( $query, $user_id ) );
            // total up the existing points balance
            if(!empty($points)){
                foreach ( $points as $_points ) {
                    $points_balance += $_points->points_balance;
                }
            }
        }
        return $points_balance;
    }
}