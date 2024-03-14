<?php
namespace WPHR\HR_MANAGER\Admin;
use WPHR\HR_MANAGER\Company;
use WPHR\HR_MANAGER\Framework\Traits\Hooker;

/**
 * Admin form handler
 *
 * Handles all the form submission
 */
class Form_Handler {

    use Hooker;

    /**
     * [__construct description]
     */
    public function __construct() {
        $this->action( 'wphr_action_create_new_company', 'create_new_company' );

        $this->action( 'admin_init', 'save_settings' );
        $this->action( 'admin_init', 'tools_general' );
        $this->action( 'admin_init', 'tools_test_mail' );
        $this->action( 'admin_init', 'update_hr_managers' );
        $this->action( 'admin_init', 'update_line_managers' );

        $wphr_settings = sanitize_title( __( 'wphr Settings', 'wphr' ) );
        add_action( "load-{$wphr_settings}_page_wphr-audit-log", array( $this, 'audit_log_bulk_action' ) );
    }

	/**
	* Extend HR managers capabities
	*
	* @since 0.1.8
	*
	* @return void
	*/
	public function update_hr_managers(){
        if ( ! isset( $_POST['wphr_manager_update'] ) ) {
            return;
        }

        if ( ! wp_verify_nonce( $_POST['wphr_hr_manager'], 'wphr_nonce' ) ) {
            return;
        }
		
		if( is_array( $_POST['users'] ) ){
            $users = custom_sanitize_array( $_POST['users'] );
			foreach( $users as $user ){
				if( isset( $_POST['receive_mail_for_leaves'][ $user ] ) ){
					update_user_meta( $user, 'receive_mail_for_leaves', 1 );
				}else{
					update_user_meta( $user, 'receive_mail_for_leaves', 0 );	
				}

				if( isset( $_POST['manage_leave_of_employees'][ $user ] ) ){
					update_user_meta( $user, 'manage_leave_of_employees', 1 );
				}else{
					update_user_meta( $user, 'manage_leave_of_employees', 0 );	
				}
			}
		}
		$url = add_query_arg( 'status', 'success', sanitize_text_field($_POST['_wp_http_referer']) );
        wp_redirect( $url );
        exit();
	}


	/**
	* Extend HR managers capabities
	*
	* @since 0.1.8
	*
	* @return void
	*/
	public function update_line_managers(){
        if ( ! isset( $_POST['wphr_line_manager_update'] ) ) {
            return;
        }

        if ( ! wp_verify_nonce( $_POST['wphr_line_manager'], 'wphr_nonce' ) ) {
            return;
        }
		global $wpdb;
		if( is_array( $_POST['users'] ) ){
            $users = custom_sanitize_array( $_POST['users'] );
			foreach( $users as $user_id ){
				$manage_leave_of_employees = $receive_mail_for_leaves = null;
				if( isset( $_POST['receive_mail_for_leaves'][ $user_id ] ) ){
					$receive_mail_for_leaves = 'on';
				}
				if( isset( $_POST['manage_leave_of_employees'][ $user_id ] ) ){
					$manage_leave_of_employees = 'on';
				}
			    $employee_table_data = array(
					'manage_leave_by_reporter' => $manage_leave_of_employees,
					'send_mail_to_reporter' => $receive_mail_for_leaves,
			    );
  
			    $wpdb->update( $wpdb->prefix . 'wphr_hr_employees', $employee_table_data, array( 'user_id' => $user_id ) );
		
			}
		}
	    
		$url = add_query_arg( 'status', 'success', sanitize_text_field($_POST['_wp_http_referer']) );
        wp_redirect( $url );
        exit();
	}
	
    /**
     * Save all settings
     *
     * @since 0.1
     *
     * @return void
     */
    public function save_settings() {
        if ( ! isset( $_POST['wphr_module_status'] ) ) {
            return;
        }

        if ( ! wp_verify_nonce( $_POST['wphr_settings'], 'wphr_nonce' ) ) {
            return;
        }

        $inactive    =  ( isset( $_GET['tab'] ) && sanitize_text_field($_GET['tab']) == 'inactive' ) ? true : false;
        $modules     = isset( $_POST['modules'] ) ? custom_sanitize_array( $_POST['modules'] ) : array();
        $all_modules = wphr()->modules->get_modules();

        foreach ( $all_modules as $key => $module ) {
            if ( ! in_array( $key, $modules ) ) {
                unset( $all_modules[$key] );
            }
        }

        if ( $inactive ) {
            $active_modules = wphr()->modules->get_active_modules();
            $all_modules    = array_merge( $all_modules, $active_modules );
        }
        update_option( 'wphr_modules', $all_modules );
        wp_redirect( $_POST['_wp_http_referer'] );
        exit();
    }

    /**
     * Check is valid input or not
     *
     * @since 0.1
     *
     * @param  array  $array
     * @param  string  $key
     *
     * @return boolean
     */
    public function is_valid_input( $array, $key ) {
        if ( ! isset( $array[$key]) || empty( $array[$key] ) || $array[$key] == '-1' ) {
            return false;
        }

        return true;
    }

    /**
     * Create a new company
     *
     * @return void
     */
    public function create_new_company() {
        if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'wphr-new-company' ) ) {
            wp_die( __( 'Cheating?', 'wphr' ) );
        }

       // $posted   = array_map( 'sanitize_text_field', $_POST );
        $posted = custom_sanitize_array($_POST);

        //$posted   = array_map( 'trim_deep', $posted );

        $errors   = [];
        $required = [
            'name'    => __( 'Company name', 'wphr' ),
            'address' => [
                'country' => __( 'Country', 'wphr' )
            ]
        ];

        if ( ! $this->is_valid_input( $posted, 'name' ) ) {
            $errors[] = 'error-company=1';
        }

        if ( ! $this->is_valid_input( $posted['address'], 'country' ) ) {
            $errors[] = 'error-country=1';
        }

        if ( $errors ) {
            $args = implode( '&' , $errors );
            $redirect_to = admin_url( 'admin.php?page=wphr-company&action=edit&msg=error&' . $args );
            wp_redirect( $redirect_to );
            exit;
        }

        $args = apply_filters('wphr_company_details',[
            'logo'    => isset( $posted['company_logo_id'] ) ? absint( $posted['company_logo_id'] ) : 0,
            'name'    => $posted['name'],
            'address' => [
                'address_1' => $posted['address']['address_1'],
                'address_2' => $posted['address']['address_2'],
                'city'      => $posted['address']['city'],
                'state'     => $posted['address']['state'],
                'zip'       => $posted['address']['zip'],
                'country'   => $posted['address']['country'],
            ],
            'phone'     => $posted['phone'],
            'fax'       => $posted['fax'],
            'mobile'    => $posted['mobile'],
            'website'   => $posted['website'],
			] );

        $company = new Company();
        $company->update( $args );
		do_action( 'save_company_details', $args );
        $redirect_to = admin_url( 'admin.php?page=wphr-company&action=edit&msg=updated' );
        wp_redirect( $redirect_to );
        exit;
    }

    /**
     * Handle audit log bulk action
     *
     * @since 0.1
     *
     * @return void
     */
    public function audit_log_bulk_action() {

        if ( ! isset( $_REQUEST['_wpnonce'] ) || ! isset( $_GET['page'] ) ) {
            return;
        }

        if ( sanitize_text_field( $_GET['page'] ) != 'wphr-audit-log' ) {
            return;
        }

        if ( ! wp_verify_nonce( sanitize_text_field( $_REQUEST['_wpnonce'] ), 'bulk-audit_logs' ) ) {
            return;
        }

        $redirect = remove_query_arg( array( '_wp_http_referer', '_wpnonce', 'filter_audit_log' ), wp_unslash( $_SERVER['REQUEST_URI'] ) );
        wp_redirect( $redirect );
        exit();
    }

    /**
     * Handle all the forms in the tools page
     *
     * @return void
     */
    public function tools_general() {

        // admin menu form
        if ( isset( $_POST['wphr_admin_menu'] ) && wp_verify_nonce( sanitize_text_field( $_REQUEST['_wpnonce'] ), 'wphr-remove-menu-nonce' ) ) {

            $menu     = isset( $_POST['menu'] ) ? array_map( 'sanitize_text_field', $_POST['menu'] ) : [];
            $bar_menu = isset( $_POST['admin_menu'] ) ? array_map( 'sanitize_text_field', $_POST['admin_menu'] ) : [];

            update_option( '_wphr_admin_menu', $menu );
            update_option( '_wphr_adminbar_menu', $bar_menu );
        }
    }

    /**
     * Send test email
     *
     * @since 1.1.2
     *
     * @return void
     */
    public function tools_test_mail() {
        if ( isset( $_POST['wphr_send_test_email'] ) && wp_verify_nonce( sanitize_text_field( $_REQUEST['_wpnonce'] ), 'wphr-test-email-nonce' ) ) {

            $to      = isset( $_POST['to'] ) ? sanitize_text_field( $_POST['to'] ) : '';
            $subject = sprintf( __( 'Test email from %s', 'wphr' ), get_bloginfo( 'name' ) );
            $body    = isset( $_POST['body'] ) ? sanitize_text_field( $_POST['body'] ) : '';

            if ( empty( $body ) ) {
                $body = sprintf( __( 'This test email proves that your WordPress installation at %1$s can send emails.\n\nSent: %2$s', 'wphr' ), get_bloginfo( 'url' ), date( 'r' ) );
            }

            wphr_mail( $to, $subject, $body );

            $redirect_to = admin_url( 'admin.php?page=wphr-tools&tab=misc&sent=true' );
            wp_redirect( $redirect_to );
            exit;
        }
    }

}

new Form_Handler();
