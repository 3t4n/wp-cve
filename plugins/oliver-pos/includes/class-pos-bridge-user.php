<?php

defined( 'ABSPATH' ) || exit;

include_once OLIVER_POS_ABSPATH . 'includes/models/class-pos-bridge-user.php';
/**
 *
 */
class Pos_Bridge_User {

    private $pos_bridge_user;

    function __construct() {
        $this->pos_bridge_user = new bridge_models\Pos_Bridge_User;
    }

    public function oliver_pos_users( $request_data ) {
        $parameters = $request_data->get_params();

        if ( isset( $parameters['page'] ) && isset( $parameters['per_page'] ) ) {
            $user_data = $this->pos_bridge_user->oliver_pos_get_paged_users( sanitize_text_field( $parameters['page'] ), sanitize_text_field( $parameters['per_page'] ) );
        } else {
            $user_data = $this->pos_bridge_user->oliver_pos_get_paged_users( sanitize_text_field( 1 ), sanitize_text_field( 10 ) );
        }
        return $user_data;
    }

    public function oliver_pos_user( $request_data ) {
        $parameters = $request_data->get_params();

        if ( isset( $parameters['id'] ) || !empty( $parameters['id'] ) ) {
            $id = (int) sanitize_text_field( $parameters['id'] );
            $user_data = $this->pos_bridge_user->oliver_pos_get_user( $id );
            return $user_data;
        }
	    return oliver_pos_api_response('Invalid Request', -1);
    }

    public function oliver_pos_get_remainig_users( $request_data ) {
        $parameters = $request_data->get_params();

        if ( isset( $parameters['remaining'] ) && !empty( $parameters['remaining'] ) ) {
            $data = $this->pos_bridge_user->oliver_pos_get_remainig_users( sanitize_text_field( $parameters['remaining'] ) );
            return $data;
        }
	    return oliver_pos_api_response('Invalid Request', -1);
    }

    /**
     * Set store credit
     *
     * @since 2.1.3.2
     * @param array $request_data
     * @return object Returns customer details.
     */
    public function oliver_pos_set_store_credit($request_data) {
        $parameters = $request_data->get_params();

        if ( !empty( $parameters['email'] ) && !empty( $parameters['amount'] ) ) {
            $email = sanitize_email($parameters['email']);
            $amount = sanitize_text_field($parameters['amount']);
            return $this->pos_bridge_user->oliver_pos_set_store_credit($email, $amount);
        }
	    return oliver_pos_api_response('Invalid Request', -1);
    }

    /**
     * Get store credit
     *
     * @since 2.1.3.2
     * @param array $request_data
     * @return float Returns customer store credit amount.
     */
    public function oliver_pos_get_store_credit($request_data) {
        $parameters = $request_data->get_params();

        if ( isset( $parameters['email'] ) && !empty( $parameters['email'] ) ) {
            $email = sanitize_email($parameters['email']);
            return $this->pos_bridge_user->oliver_pos_get_store_credit($email);
        }
	    return oliver_pos_api_response('Invalid Request', -1);
    }

    public function oliver_pos_edit_user_listener( $user_id ) {
        oliver_log("=== === ===");
        oliver_log("Start update user trigger");

        //$this->sync_user_dotnet( $user_id, esc_url_raw( ASP_TRIGGER_UPDATE_USER ) );
        $this->oliver_pos_post_user_data_to_dotnet( $user_id, esc_url_raw( ASP_TRIGGER_UPDATE_USER ) );
        oliver_log("Stop update user trigger");
        return array();
    }

    /**
     * Get store credit
     *
     * @since 2.3.4.1
     * @param array $user id of updated customer.
     * @return array|void Returns empty array or void.
     */
    public function oliver_pos_update_customer( $user_id ) {
        oliver_log("=== === ===");
        oliver_log("Start oliver_pos_update_customer trigger");

        //$this->sync_user_dotnet( $user_id, esc_url_raw( ASP_TRIGGER_UPDATE_USER ) );
        $this->oliver_pos_post_user_data_to_dotnet( $user_id, esc_url_raw( ASP_TRIGGER_UPDATE_USER ) );

        oliver_log("Stop oliver_pos_update_customer trigger");

        return array();
    }

    public function oliver_pos_register_user_listener( $user_id ) {
        oliver_log("Start create user trigger");

        //$this->sync_user_dotnet( $user_id, esc_url_raw( ASP_TRIGGER_CREATE_USER ) );
        $this->oliver_pos_post_user_data_to_dotnet( $user_id, esc_url_raw( ASP_TRIGGER_CREATE_USER ) );

        oliver_log("Stop create user trigger");
        return array();
    }

    public function oliver_pos_delete_user_listener( $user_id ) {
        oliver_log("=== === ===");
        oliver_log("Start delete user trigger");

        $this->oliver_pos_sync_user_dotnet( $user_id, esc_url_raw( ASP_TRIGGER_REMOVE_USER ) );
        oliver_log("Stop delete user trigger");
        return array();
    }

    private function oliver_pos_sync_user_dotnet( $user_id, $method ) {
        $udid = ASP_DOT_NET_UDID;
        $url = esc_url_raw("{$method}/?udid={$udid}&wpid={$user_id}");

        wp_remote_get( $url, array(
            'timeout'   => 0.01,
            'blocking'  => false,
            'sslverify' => false,
            'headers' => array(
	            'Authorization' => AUTHORIZATION,
            ),
        ));
    }
    /**
     * post user details.
     *
     * @since 2.3.8.8
     * @param int user id and post method
     */
    private function oliver_pos_post_user_data_to_dotnet( $user_id, $post_method ) {
        $user_data = $this->pos_bridge_user->oliver_pos_get_user($user_id);
        wp_remote_post( esc_url_raw( $post_method ), array(
            'headers'     => array('Content-Type' => 'application/json; charset=utf-8',
                'Authorization' => AUTHORIZATION,
            ),
            'body' => json_encode($user_data),
        ) );
    }

    public function oliver_pos_create_user( $request_data ) {
        $parameters = $request_data->get_params();

        if ( isset( $parameters['email'] ) && !empty( $parameters['email'] ) ) {
            return $this->pos_bridge_user->oliver_pos_create_user( $parameters );
        }
	    return oliver_pos_api_response('Email Required', -1);
    }

    public function oliver_pos_update_user( $request_data ) {
        $parameters = $request_data->get_params();
        if ( isset( $parameters['email'] ) && !empty( $parameters['email'] ) ) {
            $email = sanitize_email( $parameters['email'] );
            $data = $this->pos_bridge_user->oliver_pos_update_user( $parameters );
            return $data;
        } else {
	        return oliver_pos_api_response('Email Required', -1);
        }
    }

    public function oliver_pos_get_user_order( $request_data ) {
        $parameters = $request_data->get_params();

        if ( isset( $parameters['id'] ) && !empty( $parameters['id'] ) && is_numeric($parameters['id']) ) {
            $id = (int) sanitize_text_field( $parameters['id'] );
            $page = ( isset( $parameters['page'] ) && !empty( $parameters['page'] ) ) ? $parameters['page'] : 1 ;
            $from = ( isset( $parameters['from'] ) && !empty( $parameters['from'] ) ) ? $parameters['from'] : '' ;
            $to = ( isset( $parameters['to'] ) && !empty( $parameters['to'] ) ) ? $parameters['to'] : '' ;
            $data = $this->pos_bridge_user->oliver_pos_get_user_order( $id, sanitize_text_field($page), sanitize_text_field($from), sanitize_text_field($to) );
            return $data;
        }
	    return oliver_pos_api_response('Invalid Request', -1);
    }

    public function oliver_pos_delete_user( $request_data ) {
        $parameters = $request_data->get_params();

        if ( isset( $parameters['id'] ) && !empty( $parameters['id'] ) && is_numeric($parameters['id']) ) {
            $id = (int) sanitize_text_field($parameters['id']);
            $data = $this->pos_bridge_user->oliver_pos_delete_user( $id );
            return $data;
        }
	    return oliver_pos_api_response('Invalid Request', -1);
    }

    /**
     * Delete customer by customer email
     *
     * @since 2.2.0.1
     * @param array $request_data
     * @return object Returns API response.
     */
    public function oliver_pos_delete_user_by_email($request_data) {
        $parameters = $request_data->get_params();

        if (!empty($parameters['email'])) {
            $email = sanitize_email($parameters['email']);
            $data = $this->pos_bridge_user->oliver_pos_delete_user_by_email($email);
            return $data;
        }
	    return oliver_pos_api_response('Invalid Request', -1);
    }

    /**
     * get user by email
     *
     * @since 2.3.9.8
     * @param array $request_data
     * @return object Returns API response.
     */
    public function oliver_pos_get_user_by_email($request_data) {
        $parameters = $request_data->get_params();

        if (!empty($parameters['email'])) {
            $email = sanitize_email($parameters['email']);
            if ( email_exists($email)) {
                $get_user_by_email = get_user_by('email', $email );
                if ( ! empty($get_user_by_email->ID)) {
                    $id = (integer) $get_user_by_email->ID;
                    $user_data = $this->pos_bridge_user->oliver_pos_get_user( $id );
                    return $user_data;
                }
	            return oliver_pos_api_response('User id not Exist', -1);
            }
	        return oliver_pos_api_response('Email id not Exist', -1);
        }
	    return oliver_pos_api_response('Invalid Request', -1);
    }

    public function oliver_pos_count_users() {
        $data = $this->pos_bridge_user->oliver_pos_count_users();
        return $data;
    }

    /**
     * Get customer count.
     *
     * @since update 2.3.9.3
     * @return int Returns count of customers.
     */
    public static function oliver_pos_get_customer_count() {
        $count_users = 0;
        global $wp_roles;
        $all_roles = $wp_roles->roles;
        $roles_key = array();
        foreach ($all_roles as $key =>$all_role)
        {
            $roles_key[] = $key;
        }
        $del_val= 'administrator';
        if(($unset_key = array_search($del_val, $roles_key)) !== false) {
            unset($roles_key[$unset_key]);
        }
        $args = [
            'role__in'=> $roles_key,
            'fields' => 'ids',
        ];
        $get_users = get_users($args);
        if(!empty($get_users)){
            $count_users = count($get_users);
        }
        return $count_users;
    }
    /**
     * Get All User Roles
     *
     * @since 2.3.8.5
     * @param string Roles
     * @return  Returns user roles.
     */
    public static function oliver_pos_get_all_roles() {
        global $wp_roles;
        $all_roles = $wp_roles->roles;
        $role_key = array();
        foreach ( $all_roles as $key => $all_role ) {
            $role_key [] =  $key;
        }
        return $role_key;
    }   
}