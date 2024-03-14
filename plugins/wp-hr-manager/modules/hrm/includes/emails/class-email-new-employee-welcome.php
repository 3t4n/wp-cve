<?php
namespace WPHR\HR_MANAGER\HRM\Emails;

use WPHR\HR_MANAGER\Email;
use WPHR\HR_MANAGER\Framework\Traits\Hooker;

/**
 * Employee welcome
 */
class New_Employee_Welcome extends Email {

    use Hooker;

    function __construct() {
        $this->id             = 'employee-welcome';
        $this->title          = __( 'Employee welcome', 'wphr' );
        $this->description    = __( 'Welcome email to new employees.', 'wphr' );

        $this->subject        = __( 'Welcome {employee_name} to {company_name}', 'wphr');
        $this->heading        = __( 'Welcome Onboard!', 'wphr');

        $this->find = [
            'full-name'       => '{full_name}',
            'first-name'      => '{first_name}',
            'last-name'       => '{last_name}',
            'job-title'       => '{job_title}',
            'dept-title'      => '{dept_title}',
            'status'          => '{status}',
            'type'            => '{type}',
            'joined-date'     => '{joined_date}',
            'reporting-to'    => '{reporting_to}',
            'compnay-name'    => '{company_name}',
            'compnay-address' => '{company_address}',
            'compnay-phone'   => '{company_phone}',
            'compnay-website' => '{company_website}',
            'login-info'      => '{login_info}',
            'siteurl'         => '{siteurl}'
        ];

        remove_all_actions('wphr_admin_field_' . $this->id . '_help_texts');
        $this->action( 'wphr_admin_field_' . $this->id . '_help_texts', 'replace_keys' );

        parent::__construct();
    }

    public function trigger( $employee_id = null, $send_login = true ) {
        if ( ! $employee_id ) {
            return;
        }

        // setup variables
        $this->employee_id = $employee_id;

        $employee          = new \WPHR\HR_MANAGER\HRM\Employee( $this->employee_id );
        $company           = new \WPHR\HR_MANAGER\Company();

        $this->recipient   = $employee->user_email;
        $this->heading     = $this->get_option( 'heading', $this->heading );
        $this->subject     = $this->get_option( 'subject', $this->subject );

        $this->replace = [
            'full-name'       => $employee->get_full_name(),
            'first-name'      => $employee->first_name,
            'last-name'       => $employee->last_name,
            'job-title'       => $employee->get_job_title(),
            'dept-title'      => $employee->get_department_title(),
            'status'          => $employee->get_status(),
            'type'            => $employee->get_type(),
            'joined-date'     => $employee->get_joined_date(),
            'reporting-to'    => $employee->get_reporting_to() ? $employee->get_reporting_to()->get_full_name() : '',
            'compnay-name'    => $company->name,
            'compnay-address' => $company->get_formatted_address(),
            'compnay-phone'   => $company->phone,
            'compnay-website' => $company->website,
            'login-info'      => '',
            'siteurl'         => site_url()
        ];

        if ( $send_login ) {
            global $wpdb, $wp_hasher;

            // Generate something random for a password reset key.
            $key = wp_generate_password( 20, false );

            // Now insert the key, hashed, into the DB.
            /*if ( empty( $wp_hasher ) ) {
                require_once ABSPATH . WPINC . '/class-phpass.php';
                $wp_hasher = new PasswordHash( 8, true );
            }*/

            if(function_exists('wp_hash_password')){
                $hashed = time() . ':' . wp_hash_password($key);
            }
            
            $hashed = time() . ':' . $wp_hasher->HashPassword( $key );
            $wpdb->update( $wpdb->users, array( 'user_activation_key' => $hashed ), array( 'user_login' => $employee->user_login ) );

            $password = '<a class="button sm" href="' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($employee->user_login), 'login') . '">' . __( 'Set Your Password', 'wphr' ) . '</a>';

            $login_info = '<h3>' . __( 'Login Details:', 'wphr' ) . '</h3>';
            $login_info .= sprintf( __( 'Username: <em>%s</em>', 'wphr' ), $employee->user_login) . '<br>';
            $login_info .= sprintf( __( 'Password: %s', 'wphr' ), $password ) . '<br>';

            $this->replace['login-info'] = $login_info;
        }

        $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
    }

    /**
     * Get template args
     *
     * @return array
     */
    function get_args() {
        return [
            'email_heading' => $this->get_heading(),
            'email_body'    => wpautop( $this->get_option( 'body' ) )
        ];
    }

}
