<?php
if (!defined('ABSPATH')) exit;  // if direct access 



add_action('job_bm_dashboard', 'job_bm_dashboard_notice');

if (!function_exists('job_bm_dashboard_notice')) {
    function job_bm_dashboard_notice()
    {

        /**
         * by default "job-bm-notice" class hidden
         * add class "has-notice" to display
         * status class:  success, fail, error
         */
?>
        <div id="job-bm-notice" class="job-bm-notice <?php echo apply_filters('job_bm_notice_classes', ''); ?>"><?php echo apply_filters('job_bm_notice_message', ''); ?></div>

    <?php
    }
}



add_filter('job_bm_notice_message', 'job_bm_notice_message_permalink');

function job_bm_notice_message_permalink()
{

    $permalink_structure = get_option('permalink_structure');

    if (empty($permalink_structure)) :
        return __('you are using plain permalink, please go <b>Permalink Settings</b> and update.', 'job-board-manager');
    endif;
}


add_filter('job_bm_notice_classes', 'job_bm_notice_classes_permalink');

function job_bm_notice_classes_permalink($classes)
{

    $permalink_structure = get_option('permalink_structure');

    if (empty($permalink_structure)) :
        return 'has-notice error';
    endif;
}









add_action('job_bm_dashboard', 'job_bm_dashboard');

if (!function_exists('job_bm_dashboard')) {
    function job_bm_dashboard()
    {

        global $current_user;
        $switch_user_role = isset($_GET['switch_user_role']) ? sanitize_text_field($_GET['switch_user_role']) : '';

        if ($switch_user_role == 'job_poster') {

            $u = new WP_User($current_user->ID);
            $u->remove_role('job_seeker');
            $u->add_role('job_poster');
        } elseif ($switch_user_role == 'job_seeker') {
            $u = new WP_User($current_user->ID);
            $u->remove_role('job_poster');
            $u->add_role('job_seeker');
        }


        if (is_user_logged_in()) :
            do_action('job_bm_dashboard_logged_in');
        else :
            do_action('job_bm_dashboard_logged_out');
        endif;
    }
}


add_action('job_bm_dashboard_logged_in', 'job_bm_dashboard_logged_in');

if (!function_exists('job_bm_dashboard_logged_in')) {
    function job_bm_dashboard_logged_in()
    {

        $job_bm_redirect_logout = get_option('job_bm_redirect_logout');
        $job_bm_account_page_id = get_option('job_bm_account_page_id');
        $job_bm_account_page_url = get_permalink($job_bm_account_page_id);

        global $current_user;

        $user_roles = $current_user->roles;
        $user_role = array_shift($user_roles);

        //var_dump($user_role);

        $tabs['account'] = array(
            'title' => __('Account', 'job-board-manager'),
            'priority' => 1,
        );

        if ($user_role == 'job_poster' || $user_role == 'administrator') {
            $tabs['my_jobs'] = array(
                'title' => __('My jobs', 'job-board-manager'),
                'priority' => 2,
            );

            $tabs['applications'] = array(
                'title' => __('Applications', 'job-board-manager'),
                'priority' => 4,
            );
        }

        if ($user_role == 'job_seeker' || $user_role == 'administrator') {
            $tabs['my_applications'] = array(
                'title' => __('My applications', 'job-board-manager'),
                'priority' => 3,
            );
        }





        $tabs['logout'] = array(
            'title' => __('Logout!', 'job-board-manager'),
            'link' => wp_logout_url(get_permalink($job_bm_redirect_logout)),
            'priority' => 99,
        );

        $dashboard_tabs = apply_filters('job_bm_dashboard_tabs', $tabs);

        $tabs_sorted = array();
        foreach ($dashboard_tabs as $page_key => $tab) $tabs_sorted[$page_key] = isset($tab['priority']) ? $tab['priority'] : 0;
        array_multisort($tabs_sorted, SORT_ASC, $dashboard_tabs);

        $current_tabs = isset($_GET['tabs']) ? sanitize_text_field($_GET['tabs']) : 'account';


    ?>
        <ul class="navs">
            <?php

            if (!empty($dashboard_tabs))
                foreach ($dashboard_tabs as $tabs_key => $tabs) {

                    $title = isset($tabs['title']) ? $tabs['title'] : '';
                    $link = isset($tabs['link']) ? $tabs['link'] : $job_bm_account_page_url . '?tabs=' . $tabs_key;

            ?>
                <li class="<?php if ($current_tabs == $tabs_key) echo 'current'; ?>">
                    <a href="<?php echo $link; ?>">
                        <?php echo $title; ?>
                    </a>
                </li>
            <?php
                }
            ?>
        </ul>
        <div class="navs-content">
            <?php

            do_action('job_bm_dashboard_tabs_content_' . $current_tabs);
            ?>
        </div>
        <?php


    }
}



/* Display question title field */

add_action('job_bm_dashboard_tabs_content_account', 'job_bm_dashboard_tabs_content_account');

if (!function_exists('job_bm_dashboard_tabs_content_account')) {
    function job_bm_dashboard_tabs_content_account()
    {

        if (is_user_logged_in()) {

            $job_bm_account_page_id = get_option('job_bm_account_page_id');
            $job_bm_account_page_url = get_permalink($job_bm_account_page_id);

            global $current_user;

            $user_roles = $current_user->roles;
            $user_role = array_shift($user_roles);


            //var_dump(job_bm_user_job_count());

            $user_job_count = job_bm_user_job_count();
            $user_application_count = job_bm_user_application_count();
            $user_application_received_count = job_bm_user_application_received_count();



        ?>
            <p class="welcome">
                <?php echo sprintf(__('Welcome! %s', 'job-board-manager'), '<strong>' . $current_user->display_name . '</strong>'); ?>
            </p>

            <?php

            if ($user_role == 'job_poster') {
            ?>
                <p class=""><?php echo sprintf(__('Switch as <a href="%s">job seeker</a>', 'job-board-manager'), $job_bm_account_page_url . '?switch_user_role=job_seeker'); ?> </p>
            <?php
            } elseif ($user_role == 'job_seeker') {
            ?>
                <p class=""><?php echo sprintf(__('Switch as <a href="%s">job poster</a>', 'job-board-manager'), $job_bm_account_page_url . '?switch_user_role=job_poster'); ?> </p>

            <?php
            } else {
            ?>

                <p class=""><?php echo sprintf(__('Switch as <a href="%s">job poster</a>', 'job-board-manager'), $job_bm_account_page_url . '?switch_user_role=job_poster'); ?> </p>
                <p class=""><?php echo sprintf(__('Switch as <a href="%s">job seeker</a>', 'job-board-manager'), $job_bm_account_page_url . '?switch_user_role=job_seeker'); ?> </p>



            <?php
            }
            ?>


            <div class="user-stats">
                <div class="">
                    <span><?php echo __('Total job post:', 'job-board-manager'); ?></span> <span><?php echo $user_job_count; ?></span>
                </div>

                <div class="">
                    <span><?php echo __('Application submit:', 'job-board-manager'); ?></span> <span><?php echo $user_application_count; ?></span>
                </div>

                <div class="">
                    <span><?php echo __('Application received:', 'job-board-manager'); ?></span> <span><?php echo $user_application_received_count; ?></span>
                </div>




            </div>

        <?php

        }
    }
}



add_action('job_bm_dashboard_tabs_content_my_jobs', 'job_bm_dashboard_tabs_content_my_jobs');

if (!function_exists('job_bm_dashboard_tabs_content_my_jobs')) {
    function job_bm_dashboard_tabs_content_my_jobs()
    {

        echo do_shortcode('[job_bm_my_jobs]');
    }
}





add_action('job_bm_dashboard_tabs_content_logout', 'job_bm_dashboard_tabs_content_logout');

if (!function_exists('job_bm_dashboard_tabs_content_logout')) {
    function job_bm_dashboard_tabs_content_logout()
    {

        echo wp_logout_url();
    }
}


add_action('job_bm_dashboard_tabs_content_my_applications', 'job_bm_dashboard_tabs_content_my_applications');

if (!function_exists('job_bm_dashboard_tabs_content_my_applications')) {
    function job_bm_dashboard_tabs_content_my_applications()
    {

        echo do_shortcode('[job_bm_my_applications]');
    }
}


add_action('job_bm_dashboard_tabs_content_applications', 'job_bm_dashboard_tabs_content_applications');

if (!function_exists('job_bm_dashboard_tabs_content_applications')) {
    function job_bm_dashboard_tabs_content_applications()
    {

        echo do_shortcode('[job_bm_applications]');
    }
}



add_action('job_bm_dashboard_logged_out', 'job_bm_dashboard_logged_out');

if (!function_exists('job_bm_dashboard_logged_out')) {
    function job_bm_dashboard_logged_out()
    {

        $job_bm_login_enable = get_option('job_bm_login_enable');
        $job_bm_registration_enable = get_option('job_bm_registration_enable');



        if ($job_bm_registration_enable == 'yes') {

        ?>
            <div class="register">
                <h3><?php echo __('Register', 'job-board-manager'); ?></h3>
                <?php echo do_shortcode('[job_bm_registration_form]'); ?>
            </div>
        <?php
        }


        if ($job_bm_login_enable == 'yes') {

            $job_bm_redirect_login = get_option('job_bm_redirect_login');
            $job_bm_redirect_login_url = get_permalink($job_bm_redirect_login);

        ?>
            <div class="login">
                <h3><?php echo __('Login', 'job-board-manager'); ?></h3>
                <?php

                $args = array(
                    'echo'           => true,
                    'remember'       => true,
                    'redirect'       => $job_bm_redirect_login_url,
                    'form_id'        => 'loginform',
                    'id_username'    => 'user_login',
                    'id_password'    => 'user_pass',
                    'id_remember'    => 'rememberme',
                    'id_submit'      => 'wp-submit',
                    'label_username' => __('Username or email address', 'job-board-manager'),
                    'label_password' => __('Password', 'job-board-manager'),
                    'label_remember' => __('Remember Me', 'job-board-manager'),
                    'label_log_in'   => __('Log In', 'job-board-manager'),
                    'value_username' => '',
                    'value_remember' => false
                );

                wp_login_form($args);

                ?>
            </div>
<?php

        }
    }
}
