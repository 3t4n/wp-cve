<?php
if (!defined('ABSPATH'))
    die('Restricted Access');


// Updates login failed to send user back to the custom form with a query var
add_action( 'wp_login_failed', 'jsjobs_login_failed', 10, 2 );
// Updates authentication to return an error when one field or both are blank
add_filter( 'authenticate', 'jsjobs_authenticate_username_password', 30, 3);

function jsjobs_login_failed( $username ){
    $referrer = wp_get_referer();
    if ( $referrer && ! jsjobslib::jsjobs_strstr($referrer, 'wp-login') && ! jsjobslib::jsjobs_strstr($referrer, 'wp-admin') ){
        if (isset($_POST['wp-submit'])){
            $key = JSJOBSincluder::getJSModel('user')->getMessagekey();
            JSJOBSMessages::setLayoutMessage(__('Username / password is incorrect',"js-jobs"), 'error',$key);
            $referrer=jsjobs::makeUrl(array('jsjobspageid'=>jsjobs::getPageid(),'jsjobsme'=>'jsjobs','jsjobslt'=>'login'));
            wp_redirect($referrer);
            exit;
        }else{
            return;
        }
    }
}

function jsjobs_authenticate_username_password( $user, $username, $password ){
    if ( is_a($user, 'WP_User') ) {
        return $user;
    }
    if (isset($_POST['wp-submit']) && (empty($_POST['pwd']) || empty($_POST['log']))){
        return false;
    }
    return $user;
}



add_action('admin_head', 'jsjobs_custom_css_add');

function jsjobs_custom_css_add() {
    echo  wp_enqueue_style('jsjobs-admin-menu', JSJOBS_PLUGIN_URL . 'includes/css/adminmenu.css');
}

// --------------------------WP registration from fields --------
// 1. wp register form extra field
add_action('register_form', 'jsjobs_add_registration_fields');

function jsjobs_add_registration_fields() {
    //Get and set any values already sent
    if (isset($_SESSION['js_cpfrom'])) {
        ?>
        <label><?php echo __('User role', 'js-jobs'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
        <?php if ($_SESSION['js_cpfrom'] == 1) { ?>
            <input type="hidden" name="jobs_role" value="1" />
            <span><?php echo __('Employer', 'js-jobs'); ?></span><br />
            <?php
        } elseif ($_SESSION['js_cpfrom'] == 2) {
            ?>
            <input type="hidden" name="jobs_role" value="2" />
            <span><?php echo __('Job Seeker', 'js-jobs'); ?></span><br />
            <?php
        }
    } else {
        ?>
        <p>
            <label for="jobs_role"> <?php _e('User Role', 'js-jobs') ?></label>
            <select id="jobs_role" name="jobs_role" class="input form-control jsjb-jm-select-field jsjb-jh-select-field">
                <option value="0"><?php echo __('Select user role', 'js-jobs'); ?></option>
                <option value="1"><?php echo __('Employer', 'js-jobs'); ?></option>
                <option value="2"><?php echo __('Job Seeker', 'js-jobs'); ?></option>
            </select>
            <input type="hidden" name="jobs_notfromourform" value="1" />
        </p>
        <?php
    }
    if (isset($_SESSION['js_cpfrom'])) {
        unset($_SESSION['js_cpfrom']);
    }
}

//2. Add validation. In this case, we make sure jobs_role is required
add_filter('registration_errors', 'jsjobs_registration_errors', 10, 3);

function jsjobs_registration_errors($errors, $sanitized_user_login, $user_email) {

    if (isset($_POST['jobs_role']) && $_POST['jobs_role'] == 0) {

        $errors->add('user_role_error','<strong>'.__("Error","js-jobs").'</strong>:'. __('You must set jobs user role', 'js-jobs').'.');
    }

    return $errors;
}

// 3. wp register form extra field get and set to user meta
add_action('user_register', 'jsjobs_registration_save', 10, 1);

function jsjobs_registration_save($user_id) {
    //if (isset($_POST['jobs_role'])) {
    if (isset($_POST['jobs_role']) && !isset($_POST['jsjobs_jobs_register_nonce']) && !wp_verify_nonce($_POST['jsjobs_jobs_register_nonce'], 'jsjobs-jobs-register-nonce') ) {
        $role = sanitize_key($_POST['jobs_role']);
        $user_email = sanitize_email($_POST['jsjobs_user_email']);
        if (is_numeric($role)) {
            if ($role == 1) {
                update_user_meta($user_id, 'jobs_role', 'employer');
                $employer_defaultgroup = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('employer_defaultgroup');
                wp_update_user(array('ID' => $user_id, 'role' => $employer_defaultgroup));
            } elseif ($role == 2) {
                update_user_meta($user_id, 'jobs_role', 'jobseeker');
                $jobseeker_defaultgroup = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('jobseeker_defaultgroup');
                wp_update_user(array('ID' => $user_id, 'role' => $jobseeker_defaultgroup));
            }

            if (isset($_POST['jobs_notfromourform']) AND $_POST['jobs_notfromourform'] == 1) {
                $nickname = get_user_meta($user_id, 'nickname', true);

                $row = JSJOBSincluder::getJSTable('users');
                $data['uid'] = $user_id;
                $data['roleid'] = $role;
                $data['first_name'] = $nickname;
                $data['emailaddress'] = $user_email;
                $data['status'] = 1;
                $data['created'] = date_i18n('Y-m-d H:i:s');

                if (!$row->bind($data)) {
                    echo JSJOBS_SAVE_ERROR;
                }
                if (!$row->store()) {
                    echo JSJOBS_SAVE_ERROR;
                }
                JSJOBSincluder::getJSModel('emailtemplate')->sendMail(6,$role,$row->id); // 6 for regesitration $role for role jobseeker and employer
            }
        }
    }
}

// ------------------- jsjobs registrationFrom request handler--------
// register a new user
function jsjobs_add_new_member() {
    if (isset($_POST["jsjobs_user_login"]) && isset($_POST['jsjobs_jobs_register_nonce']) && wp_verify_nonce($_POST['jsjobs_jobs_register_nonce'], 'jsjobs-jobs-register-nonce')) {
        $user_login = sanitize_user($_POST["jsjobs_user_login"]);
        $user_email = sanitize_email($_POST["jsjobs_user_email"]);
        $user_first = sanitize_text_field($_POST["jsjobs_user_first"]);
        $user_last = sanitize_text_field($_POST["jsjobs_user_last"]);
        $user_pass = sanitize_text_field($_POST["jsjobs_user_pass"]);
        $pass_confirm = sanitize_text_field($_POST["jsjobs_user_pass_confirm"]);

        // this is required for username checks
        // require_once(ABSPATH . WPINC . '/registration.php');

        if (username_exists($user_login)) {
            // Username already registered
            jsjobs_errors()->add('username_unavailable', __('Username already taken', 'js-jobs'));
        }
        if (!validate_username($user_login)) {
            // invalid username
            jsjobs_errors()->add('username_invalid', __('Invalid username', 'js-jobs'));
        }
        if ($user_login == '') {
            // empty username
            jsjobs_errors()->add('username_empty', __('Please enter a username', 'js-jobs'));
        }
        if (!is_email($user_email)) {
            //invalid email
            jsjobs_errors()->add('email_invalid', __('Invalid email', 'js-jobs'));
        }
        if (email_exists($user_email)) {
            //Email address already registered
            jsjobs_errors()->add('email_used', __('Email already registered', 'js-jobs'));
        }
        if ($user_pass == '') {
            // passwords do not match
            jsjobs_errors()->add('password_empty', __('Please enter a password', 'js-jobs'));
        }
        if ($user_pass != $pass_confirm) {
            // passwords do not match
            jsjobs_errors()->add('password_mismatch', __('Passwords do not match', 'js-jobs'));
        }

        $config_array = JSJOBSincluder::getJSModel('configuration')->getConfigByFor('captcha');
        if ($config_array['cap_on_reg_form'] == 1) {
            if ($config_array['captcha_selection'] == 1) { // Google recaptcha

                $gresponse = sanitize_text_field($_POST['g-recaptcha-response']);
                $resp = googleRecaptchaHTTPPost($config_array['recaptcha_privatekey'] , $gresponse);

                if (! $resp) {
                    jsjobs_errors()->add('invalid_captcha', __('Invalid captcha', 'js-jobs'));
                }
            } else { // own captcha
                $captcha = new JSJOBScaptcha;
                $result = $captcha->checkCaptchaUserForm();
                if ($result != 1) {
                    jsjobs_errors()->add('invalid_captcha', __('Invalid captcha', 'js-jobs'));
                }
            }
        }

        $errors = jsjobs_errors()->get_error_messages();

        // only create the user in if there are no errors
        if (empty($errors)) {

            $new_user_id = wp_insert_user(array(
                'user_login' => $user_login,
                'user_pass' => $user_pass,
                'user_email' => $user_email,
                'first_name' => $user_first,
                'last_name' => $user_last,
                'user_registered' => date_i18n('Y-m-d H:i:s'),
                'role' => 'subscriber'
                )
            );
            if ($new_user_id) {
                // send an email to the admin alerting them of the registration
                wp_new_user_notification($new_user_id);
                // log the new user in
                wp_set_current_user($new_user_id, $user_login);
                wp_set_auth_cookie($new_user_id);
                //do_action('wp_login', $user_login);

                $role = sanitize_key($_POST['jobs_role']);

                if (is_numeric($role)) {
                    if ($role == 1) {
                        update_user_meta($new_user_id, 'jobs_role', 'employer');
                        $employer_defaultgroup = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('employer_defaultgroup');
                        wp_update_user(array('ID' => $new_user_id, 'role' => $employer_defaultgroup));
                    } elseif ($role == 2) {
                        update_user_meta($new_user_id, 'jobs_role', 'jobseeker');
                        $jobseeker_defaultgroup = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('jobseeker_defaultgroup');
                        wp_update_user(array('ID' => $new_user_id, 'role' => $jobseeker_defaultgroup));
                    }
                }

                // insert entry into out db also
                $userrole = get_user_meta($new_user_id, 'jobs_role', true);
                $url = '';
                if ($userrole == 'employer') {
                    $userrole = 1;
                    $url = jsjobs::makeUrl(array('jsjobsme'=>'employer', 'jsjobslt'=>'controlpanel',"jsjobspageid"=>jsjobs::getPageid()));
                } elseif ($userrole == 'jobseeker') {
                    $userrole = 2;
                    $url = jsjobs::makeUrl(array('jsjobsme'=>'jobseeker', 'jsjobslt'=>'controlpanel',"jsjobspageid"=>jsjobs::getPageid()));
                }
                    $row = JSJOBSincluder::getJSTable('users');
                    $data['uid'] = $new_user_id;
                    $data['roleid'] = $userrole;
                    $data['first_name'] = $user_first;
                    $data['last_name'] = $user_last;
                    $data['emailaddress'] = $user_email;
                    $data['status'] = 1;
                    $data['created'] = date_i18n('Y-m-d H:i:s');
                    $key = JSJOBSincluder::getJSModel('user')->getMessagekey();


                    if (!$row->bind($data)) {
                        JSJOBSMessages::setLayoutMessage(__('Error Updating User', 'js-jobs'), 'error',$key);
                    }
                    if (!$row->store()) {
                        JSJOBSMessages::setLayoutMessage(__('Error Updating User', 'js-jobs'), 'error',$key);
                    }
                    JSJOBSincluder::getJSModel('emailtemplate')->sendMail(6,$userrole,$row->id); // 6 for regesitration $role for role jobseeker and employer
                    $nickname = $user_first . ' ' . $user_last;

                    $pageid = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('register_jobseeker_redirect_page');
                    JSJOBSMessages::setLayoutMessage(__('User Added Successfully', 'js-jobs'), 'updated',$key);
                    if($userrole == 1){
                        $pageid = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('register_employer_redirect_page');
                    }elseif($userrole == 2){
                        $pageid = JSJOBSincluder::getJSModel('configuration')->getConfigurationByConfigName('register_jobseeker_redirect_page');
                    }
                    // $url = home_url();
                    if(is_page($pageid)){
                        if(get_post_status($pageid) == 'publish'){
                            $url = get_the_permalink($pageid);
                        }
                    }
                wp_redirect($url);
                exit;
            }
        }
    }
}

add_action('init', 'jsjobs_add_new_member');

// used for tracking error messages
function jsjobs_errors() {
    static $wp_error; // Will hold global variable safely
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, null, null));
}

// displays error messages from form submissions
function jsjobs_show_error_messages() {
    if ($codes = jsjobs_errors()->get_error_codes()) {
        echo '<div class="jsjobs_errors">';
        // Loop error codes and display errors
        $alert_class = 'danger';
        $img_name = 'job-alert-unsuccessful.png';
        foreach ($codes as $code) {
            $message = jsjobs_errors()->get_error_message($code);
            if(jsjobs::$theme_chk  != 0){
                echo wp_kses('<div class="alert alert-' . esc_attr($alert_class) . '" role="alert" id="autohidealert">
                    <img class="leftimg" src="'.JSJOBS_PLUGIN_URL.'includes/images/'.$img_name.'" />
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    '. esc_html($message) . '
                </div>', JSJOBS_ALLOWED_TAGS);
            }else{
                echo '<div class="frontend error"><p>' . esc_html($message) . '</p></div>';
            }
        }
        echo '</div>';
    }
}

// ---------------Remove wp user ---------------

function jsjobs_remove_jobs_user($user_id) {
    //$userrole = get_user_meta( $new_user_id, 'jobs_role', true );

    $js_model = JSJOBSincluder::getJSModel('user');
    $userrole = $js_model->getUserRoleByWPUid($user_id);
    $userid = $js_model->getUserIDByWPUid($user_id);

    if (isset($_POST['delete_option']) AND $_POST['delete_option'] == 'delete') {
        $result = $js_model->enforceDeleteOurUser($userid, $userrole);
        if ($result) {

        } else {

        }
    }
}

add_action('delete_user', 'jsjobs_remove_jobs_user');

// visual composer hooks

add_action( 'vc_before_init', 'js_jobs_vcSetAsTheme' );
function js_jobs_vcSetAsTheme() {
    if(jsjobs::$theme_chk == 0){
        vc_set_as_theme();
        vc_map( array(
              "name" => __( "Employer Control Panel", "job-hub" ),
              "base" => "jsjobs_employer_controlpanel",
              "class" => "",
              "category" => __( "JS Jobs Pages", "job-hub"),
              "icon" => JSJOBS_PLUGIN_URL . 'includes/images/vc-icons/dashboard.png',
              "show_settings_on_create" => false,
            )
        );

        vc_map( array(
              "name" => __( "Jobseeker Control Panel", "job-hub" ),
              "base" => "jsjobs_jobseeker_controlpanel",
              "class" => "",
              "category" => __( "JS Jobs Pages", "job-hub"),
              "icon" => JSJOBS_PLUGIN_URL . 'includes/images/vc-icons/dashboard.png',
              "show_settings_on_create" => false,
            )
        );

        vc_map( array(
              "name" => __( "Login", "job-hub" ),
              "base" => "jsjobs_login_page",
              "class" => "",
              "category" => __( "JS Jobs Pages", "job-hub"),
              "icon" => JSJOBS_PLUGIN_URL . 'includes/images/vc-icons/login.png',
              "show_settings_on_create" => false,
            )
        );

        vc_map( array(
              "name" => __( "Job Search", "job-hub" ),
              "base" => "jsjobs_job_search",
              "class" => "",
              "category" => __( "JS Jobs Pages", "job-hub"),
              "icon" => JSJOBS_PLUGIN_URL . 'includes/images/vc-icons/job-search.png',
              "show_settings_on_create" => false,
            )
        );

        vc_map( array(
              "name" => __( "Job Listing", "job-hub" ),
              "base" => "jsjobs_job",
              "class" => "",
              "category" => __( "JS Jobs Pages", "job-hub"),
              "icon" => JSJOBS_PLUGIN_URL . 'includes/images/vc-icons/job-list.png',
              "show_settings_on_create" => false,
            )
        );

        vc_map( array(
              "name" => __( "Jobs By Catergories", "job-hub" ),
              "base" => "jsjobs_job_categories",
              "class" => "",
              "category" => __( "JS Jobs Pages", "job-hub"),
              "icon" => JSJOBS_PLUGIN_URL . 'includes/images/vc-icons/job-category.png',
              "show_settings_on_create" => false,
            )
        );

        vc_map( array(
              "name" => __( "Jobs By Types", "job-hub" ),
              "base" => "jsjobs_job_types",
              "class" => "",
              "category" => __( "JS Jobs Pages", "job-hub"),
              "icon" => JSJOBS_PLUGIN_URL . 'includes/images/vc-icons/job-type.png',
              "show_settings_on_create" => false,
            )
        );

        vc_map( array(
              "name" => __( "My Applied Jobs", "job-hub" ),
              "base" => "jsjobs_my_appliedjobs",
              "class" => "",
              "category" => __( "JS Jobs Pages", "job-hub"),
              "icon" => JSJOBS_PLUGIN_URL . 'includes/images/vc-icons/my-applied-job.png',
              "show_settings_on_create" => false,
            )
        );

        vc_map( array(
              "name" => __( "My Companies", "job-hub" ),
              "base" => "jsjobs_my_companies",
              "class" => "",
              "category" => __( "JS Jobs Pages", "job-hub"),
              "icon" => JSJOBS_PLUGIN_URL . 'includes/images/vc-icons/companies.png',
              "show_settings_on_create" => false,
            )
        );


        vc_map( array(
              "name" => __( "My Jobs", "job-hub" ),
              "base" => "jsjobs_my_jobs",
              "class" => "",
              "category" => __( "JS Jobs Pages", "job-hub"),
              "icon" => JSJOBS_PLUGIN_URL . 'includes/images/vc-icons/jobs.png',
              "show_settings_on_create" => false,
            )
        );

        vc_map( array(
              "name" => __( "My Resumes", "job-hub" ),
              "base" => "jsjobs_my_resumes",
              "class" => "",
              "category" => __( "JS Jobs Pages", "job-hub"),
              "icon" => JSJOBS_PLUGIN_URL . 'includes/images/vc-icons/resume.png',
              "show_settings_on_create" => false,
            )
        );

        vc_map( array(
              "name" => __( "Add Company", "job-hub" ),
              "base" => "jsjobs_add_company",
              "class" => "",
              "category" => __( "JS Jobs Pages", "job-hub"),
              "icon" => JSJOBS_PLUGIN_URL . 'includes/images/vc-icons/ad-company.png',
              "show_settings_on_create" => false,
            )
        );


        vc_map( array(
              "name" => __( "Add Job", "job-hub" ),
              "base" => "jsjobs_add_job",
              "class" => "",
              "category" => __( "JS Jobs Pages", "job-hub"),
              "icon" => JSJOBS_PLUGIN_URL . 'includes/images/vc-icons/ad-job.png',
              "show_settings_on_create" => false,
            )
        );

        vc_map( array(
              "name" => __( "Add Resume", "job-hub" ),
              "base" => "jsjobs_add_resume",
              "class" => "",
              "category" => __( "JS Jobs Pages", "job-hub"),
              "icon" => JSJOBS_PLUGIN_URL . 'includes/images/vc-icons/ad_resume.png',
              "show_settings_on_create" => false,
            )
        );

        vc_map( array(
              "name" => __( "Resume Search", "job-hub" ),
              "base" => "jsjobs_resume_search",
              "class" => "",
              "category" => __( "JS Jobs Pages", "job-hub"),
              "icon" => JSJOBS_PLUGIN_URL . 'includes/images/vc-icons/resume-search.png',
              "show_settings_on_create" => false,
            )
        );

        vc_map( array(
              "name" => __( "Employer Registration", "job-hub" ),
              "base" => "jsjobs_employer_registration",
              "class" => "",
              "category" => __( "JS Jobs Pages", "job-hub"),
              "icon" => JSJOBS_PLUGIN_URL . 'includes/images/vc-icons/employer-register.png',
              "show_settings_on_create" => false,
            )
        );

        vc_map( array(
              "name" => __( "Jobseeker Registration", "job-hub" ),
              "base" => "jsjobs_jobseeker_registration",
              "class" => "",
              "category" => __( "JS Jobs Pages", "job-hub"),
              "icon" => JSJOBS_PLUGIN_URL . 'includes/images/vc-icons/jobseeker-register.png',
              "show_settings_on_create" => false,
            )
        );

        vc_map( array(
              "name" => __( "All Companies", "job-hub" ),
              "base" => "jsjobs_all_companies",
              "class" => "",
              "category" => __( "JS Jobs Pages", "job-hub"),
              "icon" => JSJOBS_PLUGIN_URL . 'includes/images/vc-icons/all-companies.png',
              "show_settings_on_create" => false,
            )
        );


        vc_map( array(
              "name" => __( "My Cover Letters", "job-hub" ),
              "base" => "jsjobs_my_coverletter",
              "class" => "",
              "category" => __( "JS Jobs Pages", "job-hub"),
              "icon" => JSJOBS_PLUGIN_URL . 'includes/images/vc-icons/cover-letter.png',
              "show_settings_on_create" => false,
            )
        );

        vc_map( array(
              "name" => __( "My Departments", "job-hub" ),
              "base" => "jsjobs_my_departments",
              "class" => "",
              "category" => __( "JS Jobs Pages", "job-hub"),
              "icon" => JSJOBS_PLUGIN_URL . 'includes/images/vc-icons/department.png',
              "show_settings_on_create" => false,
            )
        );


        vc_map( array(
              "name" => __( "Add Cover Letter", "job-hub" ),
              "base" => "jsjobs_add_coverletter",
              "class" => "",
              "category" => __( "JS Jobs Pages", "job-hub"),
              "icon" => JSJOBS_PLUGIN_URL . 'includes/images/vc-icons/ad-cover-letter.png',
              "show_settings_on_create" => false,
            )
        );

        vc_map( array(
              "name" => __( "Add Department", "job-hub" ),
              "base" => "jsjobs_add_department",
              "class" => "",
              "category" => __( "JS Jobs Pages", "job-hub"),
              "icon" => JSJOBS_PLUGIN_URL . 'includes/images/vc-icons/ad-department.png',
              "show_settings_on_create" => false,
            )
        );
        vc_map( array(
              "name" => __( "Employer My Stats", "job-hub" ),
              "base" => "jsjobs_employer_my_stats",
              "class" => "",
              "category" => __( "JS Jobs Pages", "job-hub"),
              "icon" => JSJOBS_PLUGIN_URL . 'includes/images/vc-icons/employer-stats.png',
              "show_settings_on_create" => false,
            )
        );

        vc_map( array(
              "name" => __( "Jobseeker My Stats", "job-hub" ),
              "base" => "jsjobs_jobseeker_my_stats",
              "class" => "",
              "category" => __( "JS Jobs Pages", "job-hub"),
              "icon" => JSJOBS_PLUGIN_URL . 'includes/images/vc-icons/jobseeker-stats.png',
              "show_settings_on_create" => false,
            )
        );
    }
}
?>
