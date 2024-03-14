<?php


if (!defined('ABSPATH')) exit;  // if direct access 


add_action('job_bm_settings_tabs_content_archive', 'job_bm_settings_tabs_content_archive');

if (!function_exists('job_bm_settings_tabs_content_archive')) {
    function job_bm_settings_tabs_content_archive($tab)
    {

        $settings_tabs_field = new settings_tabs_field();

        $job_bm_list_per_page = get_option('job_bm_list_per_page');
        $job_bm_pagination_bg_color = get_option('job_bm_pagination_bg_color');
        $job_bm_pagination_active_bg_color = get_option('job_bm_pagination_active_bg_color');
        $job_bm_pagination_text_color = get_option('job_bm_pagination_text_color');
        $job_bm_salary_currency = get_option('job_bm_salary_currency');



?>
        <div class="section">
            <div class="section-title"><?php echo __('Archive settings', 'job-board-manager'); ?></div>
            <p class="description section-description"><?php echo __('Choose option for archive.', 'job-board-manager'); ?></p>

            <?php

            $args = array(
                'id'        => 'job_bm_list_per_page',
                //'parent'		=> '',
                'title'        => __('Job per page', 'job-board-manager'),
                'details'    => __('Set custom number of job per page on job archive', 'job-board-manager'),
                'type'        => 'text',
                'value'        => $job_bm_list_per_page,
                'default'        => '',
                'placeholder'        => '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'        => 'job_bm_pagination_bg_color',
                //'parent'		=> '',
                'title'        => __('Pagination background color', 'job-board-manager'),
                'details'    => __('Choose pagination custom background color.', 'job-board-manager'),
                'type'        => 'colorpicker',
                'value'        => $job_bm_pagination_bg_color,
                'default'        => '#656565',
                'placeholder'        => '',
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'        => 'job_bm_pagination_active_bg_color',
                //'parent'		=> '',
                'title'        => __('Pagination active background color', 'job-board-manager'),
                'details'    => __('Choose pagination active custom background color.', 'job-board-manager'),
                'type'        => 'colorpicker',
                'value'        => $job_bm_pagination_active_bg_color,
                'default'        => '#949494',
                'placeholder'        => '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'        => 'job_bm_pagination_text_color',
                //'parent'		=> '',
                'title'        => __('Pagination text color', 'job-board-manager'),
                'details'    => __('Choose pagination custom text color.', 'job-board-manager'),
                'type'        => 'colorpicker',
                'value'        => $job_bm_pagination_text_color,
                'default'        => '#ffffff',
                'placeholder'        => '',
            );

            $settings_tabs_field->generate_field($args);




            $args = array(
                'id'        => 'job_bm_salary_currency',
                //'parent'		=> '',
                'title'        => __('Salary currency', 'job-board-manager'),
                'details'    => __('Salary currency display on job page.', 'job-board-manager'),
                'type'        => 'text',
                //'multiple'		=> true,
                'value'        => $job_bm_salary_currency,
                'default'        => '',
            );

            $settings_tabs_field->generate_field($args);



            ?>


        </div>
    <?php


    }
}






add_action('job_bm_settings_tabs_content_pages', 'job_bm_settings_tabs_content_pages');

if (!function_exists('job_bm_settings_tabs_content_pages')) {
    function job_bm_settings_tabs_content_pages($tab)
    {

        $settings_tabs_field = new settings_tabs_field();


        $job_bm_archive_page_id = get_option('job_bm_archive_page_id');
        $job_bm_job_submit_page_id = get_option('job_bm_job_submit_page_id');
        $job_bm_job_edit_page_id = get_option('job_bm_job_edit_page_id');
        $job_bm_job_login_page_id = get_option('job_bm_job_login_page_id');




    ?>
        <div class="section">
            <div class="section-title"><?php echo __('Pages settings', 'job-board-manager'); ?></div>
            <p class="description section-description"><?php echo __('Choose option for pages.', 'job-board-manager'); ?></p>

            <?php

            $args = array(
                'id'        => 'job_bm_job_login_page_id',
                //'parent'		=> '',
                'title'        => __('Dashboard page', 'job-board-manager'),
                'details'    => __('Choose the page for dashboard page, where the shortcode <code>[job_bm_dashboard]</code> used.', 'job-board-manager'),
                'type'        => 'select',
                //'multiple'		=> true,
                'value'        => $job_bm_job_login_page_id,
                'default'        => '',
                'args'        => job_bm_page_list_id(),
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'        => 'job_bm_archive_page_id',
                //'parent'		=> '',
                'title'        => __('Job archive page', 'job-board-manager'),
                'details'    => __('Choose the page for job archive page, where the shortcode <code>[job_bm_archive]</code> used.', 'job-board-manager'),
                'type'        => 'select',
                //'multiple'		=> true,
                'value'        => $job_bm_archive_page_id,
                'default'        => '',
                'args'        => job_bm_page_list_id(),
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'        => 'job_bm_job_submit_page_id',
                //'parent'		=> '',
                'title'        => __('Job submission page', 'job-board-manager'),
                'details'    => __('Choose the page for job submission page, where the shortcode <code>[job_submit_form]</code> used.', 'job-board-manager'),
                'type'        => 'select',
                //'multiple'		=> true,
                'value'        => $job_bm_job_submit_page_id,
                'default'        => '',
                'args'        => job_bm_page_list_id(),
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'        => 'job_bm_job_edit_page_id',
                //'parent'		=> '',
                'title'        => __('Job edit page', 'job-board-manager'),
                'details'    => __('Choose the page for job edit page, where the shortcode <code>[job_bm_job_edit]</code> used.', 'job-board-manager'),
                'type'        => 'select',
                //'multiple'		=> true,
                'value'        => $job_bm_job_edit_page_id,
                'default'        => '',
                'args'        => job_bm_page_list_id(),
            );

            $settings_tabs_field->generate_field($args);







            ?>


        </div>
    <?php


    }
}




add_action('job_bm_settings_tabs_content_job_submit', 'job_bm_settings_tabs_content_job_submit');

if (!function_exists('job_bm_settings_tabs_content_job_submit')) {
    function job_bm_settings_tabs_content_job_submit($tab)
    {

        $settings_tabs_field = new settings_tabs_field();





        $job_bm_account_required_post_job = get_option('job_bm_account_required_post_job');
        $job_bm_reCAPTCHA_enable = get_option('job_bm_reCAPTCHA_enable');
        $job_bm_reCAPTCHA_site_key = get_option('job_bm_reCAPTCHA_site_key');
        $job_bm_reCAPTCHA_secret_key = get_option('job_bm_reCAPTCHA_secret_key');
        $job_bm_submitted_job_status = get_option('job_bm_submitted_job_status');
        $job_bm_redirect_preview_link = get_option('job_bm_redirect_preview_link');
        $job_bm_restrict_media_file = get_option('job_bm_restrict_media_file');
        $job_bm_job_submit_create_account = get_option('job_bm_job_submit_create_account');
        $job_bm_job_submit_generate_username = get_option('job_bm_job_submit_generate_username');




    ?>
        <div class="section">
            <div class="section-title"><?php echo __('Job posting settings', 'job-board-manager'); ?></div>
            <p class="description section-description"><?php echo __('Choose option for posting.', 'job-board-manager'); ?></p>

            <?php

            $args = array(
                'id'        => 'job_bm_account_required_post_job',
                //'parent'		=> '',
                'title'        => __('Account required', 'job-board-manager'),
                'details'    => __('Account required to post job.', 'job-board-manager'),
                'type'        => 'select',
                //'multiple'		=> true,
                'value'        => $job_bm_account_required_post_job,
                'default'        => '',
                'args'        => array('yes' => __('Yes', 'job-board-manager'), 'no' => __('No', 'job-board-manager'),),
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'        => 'job_bm_reCAPTCHA_enable',
                //'parent'		=> '',
                'title'        => __('reCAPTCHA enable', 'job-board-manager'),
                'details'    => __('Enable reCAPTCHA to protect spam.', 'job-board-manager'),
                'type'        => 'select',
                //'multiple'		=> true,
                'value'        => $job_bm_reCAPTCHA_enable,
                'default'        => '',
                'args'        => array('yes' => __('Yes', 'job-board-manager'), 'no' => __('No', 'job-board-manager'),),
            );

            $settings_tabs_field->generate_field($args);



            $args = array(
                'id'        => 'job_bm_reCAPTCHA_site_key',
                //'parent'		=> '',
                'title'        => __('reCAPTCHA site key', 'job-board-manager'),
                'details'    => __('reCAPTCHA site key, please go <a href="https://www.google.com/recaptcha">google.com/reCAPTCHA</a> and register your site to get site key.', 'job-board-manager'),
                'type'        => 'text',
                //'multiple'		=> true,
                'value'        => $job_bm_reCAPTCHA_site_key,
                'default'        => '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'        => 'job_bm_reCAPTCHA_secret_key',
                //'parent'		=> '',
                'title'        => __('reCAPTCHA secret key', 'job-board-manager'),
                'details'    => __('reCAPTCHA secret key, please go <a href="https://www.google.com/recaptcha">google.com/reCAPTCHA</a> and register your site to get site key.', 'job-board-manager'),
                'type'        => 'text',
                //'multiple'		=> true,
                'value'        => $job_bm_reCAPTCHA_secret_key,
                'default'        => '',
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'        => 'job_bm_submitted_job_status',
                //'parent'		=> '',
                'title'        => __('Submitted job status', 'job-board-manager'),
                'details'    => __('Choose job status for newly submitted jobs.', 'job-board-manager'),
                'type'        => 'select',
                //'multiple'		=> true,
                'value'        => $job_bm_submitted_job_status,
                'default'        => '',
                'args'        => array('draft' => __('Draft', 'job-board-manager'), 'pending' => __('Pending', 'job-board-manager'), 'publish' => __('Published', 'job-board-manager'), 'private' => __('Private', 'job-board-manager'), 'trash' => __('Trash', 'job-board-manager')),
            );

            $settings_tabs_field->generate_field($args);





            $page_list = job_bm_page_list_id();
            //$page_list = array_merge($page_list, array('job_preview'=>'Job Preview'));

            $page_list['job_preview'] = __('-- Job Preview --', 'job-board-manager');
            $page_list['job_link'] = __('-- Job Link --', 'job-board-manager');

            $args = array(
                'id'        => 'job_bm_redirect_preview_link',
                //'parent'		=> '',
                'title'        => __('Redirect after job submit', 'job-board-manager'),
                'details'    => __('Redirect other link after job submitted', 'job-board-manager'),
                'type'        => 'select',
                //'multiple'		=> true,
                'value'        => $job_bm_redirect_preview_link,
                'default'        => '',
                'args'        => $page_list,
            );

            $settings_tabs_field->generate_field($args);



            $args = array(
                'id'        => 'job_bm_restrict_media_file',
                //'parent'		=> '',
                'title'        => __('Restrict media uploader files', 'job-board-manager'),
                'details'    => __('Restricted media uploader file only logged-in user.', 'job-board-manager'),
                'type'        => 'select',
                //'multiple'		=> true,
                'value'        => $job_bm_restrict_media_file,
                'default'        => '',
                'args'        => array('yes' => __('Yes', 'job-board-manager'), 'no' => __('No', 'job-board-manager'),),
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'        => 'job_bm_job_submit_create_account',
                //'parent'		=> '',
                'title'        => __('Allow user create account', 'job-board-manager'),
                'details'    => __('Allow user create account on job submit.', 'job-board-manager'),
                'type'        => 'select',
                //'multiple'		=> true,
                'value'        => $job_bm_job_submit_create_account,
                'default'        => '',
                'args'        => array('yes' => __('Yes', 'job-board-manager'), 'no' => __('No', 'job-board-manager'),),
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'        => 'job_bm_job_submit_generate_username',
                //'parent'		=> '',
                'title'        => __('Allow user create username', 'job-board-manager'),
                'details'    => __('Allow user create username on job submit.', 'job-board-manager'),
                'type'        => 'select',
                //'multiple'		=> true,
                'value'        => $job_bm_job_submit_generate_username,
                'default'        => '',
                'args'        => array('yes' => __('Yes', 'job-board-manager'), 'no' => __('No', 'job-board-manager'),),
            );

            $settings_tabs_field->generate_field($args);


            ?>


        </div>
    <?php


    }
}



add_action('job_bm_settings_tabs_content_job_edit', 'job_bm_settings_tabs_content_job_edit');

if (!function_exists('job_bm_settings_tabs_content_job_edit')) {
    function job_bm_settings_tabs_content_job_edit($tab)
    {

        $settings_tabs_field = new settings_tabs_field();


        $job_bm_can_user_edit_published_jobs = get_option('job_bm_can_user_edit_published_jobs');
        $job_bm_edited_job_status = get_option('job_bm_edited_job_status');
        $job_bm_edited_redirect_link = get_option('job_bm_edited_redirect_link');
        $job_bm_job_edit_notify_email = get_option('job_bm_job_edit_notify_email');



    ?>
        <div class="section">
            <div class="section-title"><?php echo __('Job edit settings', 'job-board-manager'); ?></div>
            <p class="description section-description"><?php echo __('Customize the job edit.', 'job-board-manager'); ?></p>

            <?php


            $args = array(
                'id'        => 'job_bm_can_user_edit_published_jobs',
                //'parent'		=> '',
                'title'        => __('Allow edit jobs', 'job-board-manager'),
                'details'    => __('Allow user edit their own jobs', 'job-board-manager'),
                'type'        => 'select',
                //'multiple'		=> true,
                'value'        => $job_bm_can_user_edit_published_jobs,
                'default'        => 'no',
                'args'        => array('no' => __('No', 'job-board-manager'), 'yes' => __('Yes', 'job-board-manager'),),
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'        => 'job_bm_edited_job_status',
                //'parent'		=> '',
                'title'        => __('Edited job status', 'job-board-manager'),
                'details'    => __('Choose job status for newly edited jobs.', 'job-board-manager'),
                'type'        => 'select',
                //'multiple'		=> true,
                'value'        => $job_bm_edited_job_status,
                'default'        => '',
                'args'        => array('draft' => __('Draft', 'job-board-manager'), 'pending' => __('Pending', 'job-board-manager'), 'publish' => __('Published', 'job-board-manager'), 'private' => __('Private', 'job-board-manager'), 'trash' => __('Trash', 'job-board-manager')),
            );

            $settings_tabs_field->generate_field($args);


            $page_list = job_bm_page_list_id();
            //$page_list = array_merge($page_list, array('job_preview'=>'Job Preview'));

            $page_list['job_preview'] = __('-- Job Preview --', 'job-board-manager');
            $page_list['job_link'] = __('-- Job Link --', 'job-board-manager');


            $args = array(
                'id'        => 'job_bm_edited_redirect_link',
                //'parent'		=> '',
                'title'        => __('Redirect after job edit', 'job-board-manager'),
                'details'    => __('Redirect other link after job edited', 'job-board-manager'),
                'type'        => 'select',
                //'multiple'		=> true,
                'value'        => $job_bm_edited_redirect_link,
                'default'        => '',
                'args'        => $page_list,
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'        => 'job_bm_job_edit_notify_email',
                //'parent'		=> '',
                'title'        => __('Notify email on job edited', 'job-board-manager'),
                'details'    => __('Notify admin when new job edited.', 'job-board-manager'),
                'type'        => 'select',
                //'multiple'		=> true,
                'value'        => $job_bm_job_edit_notify_email,
                'default'        => '',
                'args'        => array('yes' => __('Yes', 'job-board-manager'), 'no' => __('No', 'job-board-manager'),),
            );

            $settings_tabs_field->generate_field($args);


            ?>

        </div>
    <?php



    }
}


add_action('job_bm_settings_tabs_content_dashboard', 'job_bm_settings_tabs_content_dashboard');

if (!function_exists('job_bm_settings_tabs_content_dashboard')) {
    function job_bm_settings_tabs_content_dashboard($tab)
    {

        $settings_tabs_field = new settings_tabs_field();


        $job_bm_redirect_login = get_option('job_bm_redirect_login');
        $job_bm_redirect_logout = get_option('job_bm_redirect_logout');
        $job_bm_registration_enable = get_option('job_bm_registration_enable');
        $job_bm_registration_recaptcha = get_option('job_bm_registration_recaptcha');
        $job_bm_redirect_after_signup = get_option('job_bm_redirect_after_signup');
        $job_bm_auto_login_after_signup = get_option('job_bm_auto_login_after_signup');



        $job_bm_login_enable = get_option('job_bm_login_enable');
        $job_bm_can_user_delete_jobs = get_option('job_bm_can_user_delete_jobs');
        $job_bm_can_user_delete_application = get_option('job_bm_can_user_delete_application');




        $page_list = job_bm_page_list_id();
        //$page_list = array_merge($page_list, array('job_preview'=>'Job Preview'));

    ?>
        <div class="section">
            <div class="section-title"><?php echo __('Dashboard settings', 'job-board-manager'); ?></div>
            <p class="description section-description"><?php echo __('Customize the dashboard.', 'job-board-manager'); ?></p>

            <?php


            $args = array(
                'id'        => 'job_bm_can_user_delete_jobs',
                //'parent'		=> '',
                'title'        => __('Allow delete jobs', 'job-board-manager'),
                'details'    => __('Allow user delete their own jobs', 'job-board-manager'),
                'type'        => 'select',
                //'multiple'		=> true,
                'value'        => $job_bm_can_user_delete_jobs,
                'default'        => '',
                'args'        => array('yes' => __('Yes', 'job-board-manager'), 'no' => __('No', 'job-board-manager'),),
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'        => 'job_bm_can_user_delete_application',
                //'parent'		=> '',
                'title'        => __('Allow delete application', 'job-board-manager'),
                'details'    => __('Allow user delete their own application', 'job-board-manager'),
                'type'        => 'select',
                //'multiple'		=> true,
                'value'        => $job_bm_can_user_delete_application,
                'default'        => '',
                'args'        => array('yes' => __('Yes', 'job-board-manager'), 'no' => __('No', 'job-board-manager'),),
            );

            $settings_tabs_field->generate_field($args);




            ?>

        </div>


        <div class="section">
            <div class="section-title"><?php echo __('Login settings', 'job-board-manager'); ?></div>
            <p class="description section-description"><?php echo __('Customize the login settings.', 'job-board-manager'); ?></p>

            <?php

            $args = array(
                'id'        => 'job_bm_login_enable',
                //'parent'		=> '',
                'title'        => __('Login enable', 'job-board-manager'),
                'details'    => __('Login enable on dashboard page.', 'job-board-manager'),
                'type'        => 'select',
                //'multiple'		=> true,
                'value'        => $job_bm_login_enable,
                'default'        => '',
                'args'        => array('yes' => __('Yes', 'job-board-manager'), 'no' => __('No', 'job-board-manager'),),
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'        => 'job_bm_redirect_login',
                //'parent'		=> '',
                'title'        => __('Redirect after login', 'job-board-manager'),
                'details'    => __('Redirect other link after logged.', 'job-board-manager'),
                'type'        => 'select',
                //'multiple'		=> true,
                'value'        => $job_bm_redirect_login,
                'default'        => '',
                'args'        => $page_list,
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'        => 'job_bm_redirect_logout',
                //'parent'		=> '',
                'title'        => __('Redirect after logged out', 'job-board-manager'),
                'details'    => __('Redirect other link after logged out.', 'job-board-manager'),
                'type'        => 'select',
                //'multiple'		=> true,
                'value'        => $job_bm_redirect_logout,
                'default'        => '',
                'args'        => $page_list,
            );

            $settings_tabs_field->generate_field($args);







            ?>

        </div>


        <div class="section">
            <div class="section-title"><?php echo __('Registration settings', 'job-board-manager'); ?></div>
            <p class="description section-description"><?php echo __('Customize the registration settings.', 'job-board-manager'); ?></p>

            <?php

            $args = array(
                'id'        => 'job_bm_registration_enable',
                //'parent'		=> '',
                'title'        => __('Registration enable', 'job-board-manager'),
                'details'    => __('Registration enable on dashboard page.', 'job-board-manager'),
                'type'        => 'select',
                //'multiple'		=> true,
                'value'        => $job_bm_registration_enable,
                'default'        => '',
                'args'        => array('yes' => __('Yes', 'job-board-manager'), 'no' => __('No', 'job-board-manager'),),
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'        => 'job_bm_registration_recaptcha',
                //'parent'		=> '',
                'title'        => __('Registration reCAPTCHA enable', 'job-board-manager'),
                'details'    => __('Enable reCAPTCHA on registration.', 'job-board-manager'),
                'type'        => 'select',
                //'multiple'		=> true,
                'value'        => $job_bm_registration_recaptcha,
                'default'        => '',
                'args'        => array('yes' => __('Yes', 'job-board-manager'), 'no' => __('No', 'job-board-manager'),),
            );

            $settings_tabs_field->generate_field($args);






            $args = array(
                'id'        => 'job_bm_auto_login_after_signup',
                //'parent'		=> '',
                'title'        => __('Auto Login after registration', 'job-board-manager'),
                'details'    => __('New user will automatically logged-in after registration.', 'job-board-manager'),
                'type'        => 'select',
                //'multiple'		=> true,
                'value'        => $job_bm_auto_login_after_signup,
                'default'        => '',
                'args'        => array('yes' => __('Yes', 'job-board-manager'), 'no' => __('No', 'job-board-manager'),),
            );

            $settings_tabs_field->generate_field($args);



            $args = array(
                'id'        => 'job_bm_redirect_after_signup',
                //'parent'		=> '',
                'title'        => __('Redirect after registration', 'job-board-manager'),
                'details'    => __('Redirect other link after registration complete.', 'job-board-manager'),
                'type'        => 'select',
                //'multiple'		=> true,
                'value'        => $job_bm_redirect_after_signup,
                'default'        => '',
                'args'        => $page_list,
            );

            $settings_tabs_field->generate_field($args);








            ?>

        </div>






    <?php











    }
}




add_action('job_bm_settings_tabs_content_email', 'job_bm_settings_tabs_content_email');

if (!function_exists('job_bm_settings_tabs_content_email')) {
    function job_bm_settings_tabs_content_email($tab)
    {

        $settings_tabs_field = new settings_tabs_field();
        $class_job_bm_emails = new class_job_bm_emails();
        $templates_data_default = $class_job_bm_emails->job_bm_email_templates_data();
        $email_templates_parameters = $class_job_bm_emails->email_templates_parameters();


        $job_bm_logo_url = get_option('job_bm_logo_url');
        $job_bm_from_email = get_option('job_bm_from_email');
        $templates_data_saved = get_option('job_bm_email_templates_data', $templates_data_default);

        //        $job_bm_test_option = get_option('job_bm_test_option');
        //
        //var_dump($templates_data);


    ?>
        <div class="section">
            <div class="section-title"><?php echo __('Email settings', 'job-board-manager'); ?></div>
            <p class="description section-description"><?php echo __('Customize email settings.', 'job-board-manager'); ?></p>

            <?php

            $args = array(
                'id'        => 'job_bm_logo_url',
                //'parent'		=> '',
                'title'        => __('Email logo', 'job-board-manager'),
                'details'    => __('Email logo URL to display on mail.', 'job-board-manager'),
                'type'        => 'media',
                'value'        => $job_bm_logo_url,
                'default'        => '',
                'placeholder'        => '',
            );

            $settings_tabs_field->generate_field($args);



            $args = array(
                'id'        => 'job_bm_from_email',
                //'parent'		=> '',
                'title'        => __('From email address', 'job-board-manager'),
                'details'    => __('Write from email address.', 'job-board-manager'),
                'type'        => 'text',
                //'multiple'		=> true,
                'value'        => $job_bm_from_email,
                'default'        => '',
            );

            $settings_tabs_field->generate_field($args);






            ob_start();


            ?>
            <div class="reset-email-templates button">Reset</div>
            <br><br>
            <div class="templates_editor expandable">
                <?php




                if (!empty($templates_data_default))
                    foreach ($templates_data_default as $key => $templates) {

                        $templates_data_display = isset($templates_data_saved[$key]) ? $templates_data_saved[$key] : $templates;


                        $email_to = isset($templates_data_display['email_to']) ? $templates_data_display['email_to'] : '';
                        $email_from = isset($templates_data_display['email_from']) ? $templates_data_display['email_from'] : '';
                        $email_from_name = isset($templates_data_display['email_from_name']) ? $templates_data_display['email_from_name'] : '';
                        $enable = isset($templates_data_display['enable']) ? $templates_data_display['enable'] : '';
                        $description = isset($templates_data_display['description']) ? $templates_data_display['description'] : '';

                        $parameters = isset($email_templates_parameters[$key]['parameters']) ? $email_templates_parameters[$key]['parameters'] : array();


                        //echo '<pre>'.var_export($enable).'</pre>';

                ?>
                    <div class="item template <?php echo $key; ?>">
                        <div class="header">
                            <span title="<?php echo __('Click to expand', 'job-board-manager'); ?>" class="expand ">
                                <i class="fa fa-expand"></i>
                                <i class="fa fa-compress"></i>
                            </span>

                            <?php
                            if ($enable == 'yes') :
                            ?>
                                <span title="<?php echo __('Enable', 'job-board-manager'); ?>" class="is-enable ">
                                    <i class="fa fa-check-square"></i>
                                </span>
                            <?php
                            else :
                            ?>
                                <span title="<?php echo __('Disabled', 'job-board-manager'); ?>" class="is-enable ">
                                    <i class="fa fa-times-circle"></i>
                                </span>
                            <?php
                            endif;
                            ?>


                            <?php echo $templates['name']; ?>
                        </div>
                        <input type="hidden" name="job_bm_email_templates_data[<?php echo esc_attr($key); ?>][name]" value="<?php echo esc_attr($templates['name']); ?>" />
                        <div class="options">
                            <div class="description"><?php echo esc_html($description); ?></div><br /><br />


                            <div class="setting-field">
                                <div class="field-lable"><?php echo __('Enable?', 'job-board-manager'); ?></div>
                                <div class="field-input">
                                    <select name="job_bm_email_templates_data[<?php echo esc_attr($key); ?>][enable]">
                                        <option <?php echo selected($enable, 'yes'); ?> value="yes"><?php echo __('Yes', 'job-board-manager'); ?></option>
                                        <option <?php echo selected($enable, 'no'); ?> value="no"><?php echo __('No', 'job-board-manager'); ?></option>
                                    </select>
                                    <p class="description"><?php echo __('Enable or disable this email notification.', 'job-board-manager'); ?></p>
                                </div>
                            </div>


                            <div class="setting-field">
                                <div class="field-lable"><?php echo __('Email To(Bcc)', 'job-board-manager'); ?></div>
                                <div class="field-input">
                                    <input placeholder="hello_1@hello.com,hello_2@hello.com" type="text" name="job_bm_email_templates_data[<?php echo $key; ?>][email_to]" value="<?php echo esc_attr($email_to); ?>" />
                                    <p class="description"><?php echo __('Email send to(copy)', 'job-board-manager'); ?></p>
                                </div>
                            </div>

                            <div class="setting-field">
                                <div class="field-lable"><?php echo __('Email from name', 'job-board-manager'); ?></div>
                                <div class="field-input">
                                    <input placeholder="hello_1@hello.com" type="text" name="job_bm_email_templates_data[<?php echo esc_attr($key); ?>][email_from_name]" value="<?php echo esc_attr($email_from_name); ?>" />
                                    <p class="description"><?php echo __('Email send from name', 'job-board-manager'); ?></p>
                                </div>
                            </div>

                            <div class="setting-field">
                                <div class="field-lable"><?php echo __('Email from', 'job-board-manager'); ?></div>
                                <div class="field-input">
                                    <input placeholder="hello_1@hello.com" type="text" name="job_bm_email_templates_data[<?php echo esc_attr($key); ?>][email_from]" value="<?php echo esc_attr($email_from); ?>" />
                                    <p class="description"><?php echo __('Email send from', 'job-board-manager'); ?></p>
                                </div>
                            </div>

                            <div class="setting-field">
                                <div class="field-lable"><?php echo __('Email Subject', 'job-board-manager'); ?></div>
                                <div class="field-input">
                                    <input type="text" name="job_bm_email_templates_data[<?php echo esc_attr($key); ?>][subject]" value="<?php echo esc_attr($templates['subject']); ?>" />
                                    <p class="description"><?php echo __('Write email subject', 'job-board-manager'); ?></p>
                                </div>
                            </div>

                            <div class="setting-field">
                                <div class="field-lable"><?php echo __('Email Body', 'job-board-manager'); ?></div>
                                <div class="field-input">
                                    <?php

                                    wp_editor($templates['html'], $key, $settings = array('textarea_name' => 'job_bm_email_templates_data[' . $key . '][html]', 'media_buttons' => false, 'wpautop' => true, 'teeny' => true, 'editor_height' => '400px',));

                                    ?>
                                    <p class="description"><?php echo __('Write email body', 'job-board-manager'); ?></p>
                                </div>
                            </div>

                            <div class="setting-field">
                                <div class="field-lable"><?php echo __('Parameter', 'job-board-manager'); ?></div>
                                <div class="field-input">

                                    <ul>


                                        <?php

                                        if (!empty($parameters)) :
                                            foreach ($parameters as $parameterId => $parameter) :
                                        ?>
                                                <li><code><?php echo $parameterId; ?></code> => <?php echo $parameter; ?></li>
                                        <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </ul>

                                    <p class="description"><?php echo __('Available parameter for this email template', 'job-board-manager'); ?></p>
                                </div>
                            </div>

                        </div>

                    </div>
                <?php

                    }


                ?>


            </div>
            <?php


            $html = ob_get_clean();




            $args = array(
                'id'        => 'job_bm_email_templates',
                //'parent'		=> '',
                'title'        => __('Email templates', 'job-board-manager'),
                'details'    => __('Customize email templates.', 'job-board-manager'),
                'type'        => 'custom_html',
                //'multiple'		=> true,
                'html'        => $html,
            );

            $settings_tabs_field->generate_field($args);




            ?>


        </div>
    <?php


    }
}






add_action('job_bm_settings_tabs_content_style', 'job_bm_settings_tabs_content_style');

if (!function_exists('job_bm_settings_tabs_content_style')) {
    function job_bm_settings_tabs_content_style($tab)
    {

        $settings_tabs_field = new settings_tabs_field();
        $class_job_bm_functions = new class_job_bm_functions();




        $job_bm_featured_bg_color = get_option('job_bm_featured_bg_color');
        $job_bm_job_type_bg_color = get_option('job_bm_job_type_bg_color');
        $job_bm_job_type_text_color = get_option('job_bm_job_type_text_color');
        $job_bm_job_status_bg_color = get_option('job_bm_job_status_bg_color');
        $job_bm_job_status_text_color = get_option('job_bm_job_status_text_color');







    ?>
        <div class="section">
            <div class="section-title"><?php echo __('Style settings', 'job-board-manager'); ?></div>
            <p class="description section-description"><?php echo __('Customize the style.', 'job-board-manager'); ?></p>

            <?php

            $args = array(
                'id'        => 'job_bm_featured_bg_color',
                //'parent'		=> '',
                'title'        => __('Featured job background color', 'job-board-manager'),
                'details'    => __('Choose custom background color featured job.', 'job-board-manager'),
                'type'        => 'colorpicker',
                'value'        => $job_bm_featured_bg_color,
                'default'        => '#fff8bf',
                'placeholder'        => '',
            );

            $settings_tabs_field->generate_field($args);



            $args = array(
                'id'        => 'job_bm_job_type_bg_color',
                //'parent'		=> '',
                'title'        => __('Job type background color', 'job-board-manager'),
                'details'    => __('Job types area background color.', 'job-board-manager'),
                'type'        => 'colorpicker_multi',
                'value'        => $job_bm_job_type_bg_color,
                'args'        => $class_job_bm_functions->job_type_bg_color(),

            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'        => 'job_bm_job_type_text_color',
                //'parent'		=> '',
                'title'        => __('Job type text color', 'job-board-manager'),
                'details'    => __('Job types area text color.', 'job-board-manager'),
                'type'        => 'colorpicker_multi',
                'value'        => $job_bm_job_type_text_color,
                'args'        => $class_job_bm_functions->job_type_text_color(),

            );

            $settings_tabs_field->generate_field($args);



            $args = array(
                'id'        => 'job_bm_job_status_bg_color',
                //'parent'		=> '',
                'title'        => __('Job status background color', 'job-board-manager'),
                'details'    => __('Job status area background color.', 'job-board-manager'),
                'type'        => 'colorpicker_multi',
                'value'        => $job_bm_job_status_bg_color,
                'args'        => $class_job_bm_functions->job_status_bg_color(),

            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'        => 'job_bm_job_status_text_color',
                //'parent'		=> '',
                'title'        => __('Job status text color', 'job-board-manager'),
                'details'    => __('Job status area text color.', 'job-board-manager'),
                'type'        => 'colorpicker_multi',
                'value'        => $job_bm_job_status_text_color,
                'args'        => $class_job_bm_functions->job_status_text_color(),

            );

            $settings_tabs_field->generate_field($args);



            ?>


        </div>
    <?php


    }
}













add_action('job_bm_settings_tabs_content_expiry', 'job_bm_settings_tabs_content_expiry');

if (!function_exists('job_bm_settings_tabs_content_expiry')) {
    function job_bm_settings_tabs_content_expiry($tab)
    {

        $settings_tabs_field = new settings_tabs_field();
        $class_job_bm_functions = new class_job_bm_functions();




        $job_bm_enable_expiry = get_option('job_bm_enable_expiry');
        $job_bm_experied_jobs_post_status = get_option('job_bm_experied_jobs_post_status');
        $job_bm_experied_check_recurrance = get_option('job_bm_experied_check_recurrance');
        $job_bm_job_expiry_days = get_option('job_bm_job_expiry_days');






    ?>
        <div class="section">
            <div class="section-title"><?php echo __('Job expire settings', 'job-board-manager'); ?></div>
            <p class="description section-description"><?php echo __('Customize job expire settings.', 'job-board-manager'); ?></p>

            <?php

            $args = array(
                'id'        => 'job_bm_enable_expiry',
                //'parent'		=> '',
                'title'        => __('Enable job expiry', 'job-board-manager'),
                'details'    => __('You can enable or disable job expiry.', 'job-board-manager'),
                'type'        => 'select',
                //'multiple'		=> true,
                'value'        => $job_bm_enable_expiry,
                'default'        => 'no',
                'args'        => array('no' => __('No', 'job-board-manager'), 'yes' => __('Yes', 'job-board-manager'),),
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'        => 'job_bm_experied_jobs_post_status',
                //'parent'		=> '',
                'title'        => __('Expired jobs status', 'job-board-manager'),
                'details'    => __('Set post status for expired jobs.', 'job-board-manager'),
                'type'        => 'select',
                //'multiple'		=> true,
                'value'        => $job_bm_experied_jobs_post_status,
                'default'        => 'trash',
                'args'        => array('publish' => __('Publish', 'job-board-manager'), 'draft' => __('Draft', 'job-board-manager'), 'pending' => __('Pending', 'job-board-manager'), 'private' => __('Private', 'job-board-manager'), 'trash' => __('Trash', 'job-board-manager')),
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'        => 'job_bm_experied_check_recurrance',
                //'parent'		=> '',
                'title'        => __('Expired check recurrence', 'job-board-manager'),
                'details'    => __('Set recurrence for checking expired jobs.', 'job-board-manager'),
                'type'        => 'select',
                //'multiple'		=> true,
                'value'        => $job_bm_experied_check_recurrance,
                'default'        => 'daily',
                'args'        => array('hourly' => __('Hourly', 'job-board-manager'), 'twicedaily' => __('Twicedaily', 'job-board-manager'), 'daily' => __('Daily', 'job-board-manager')),
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'        => 'job_bm_job_expiry_days',
                //'parent'		=> '',
                'title'        => __('Expire days', 'job-board-manager'),
                'details'    => __('Set custom value for expire in.', 'job-board-manager'),
                'type'        => 'text',
                //'multiple'		=> true,
                'value'        => $job_bm_job_expiry_days,
                'default'        => '30',
            );

            $settings_tabs_field->generate_field($args);




            ?>


        </div>
    <?php


    }
}


add_action('job_bm_settings_tabs_right_panel_archive', 'job_bm_settings_tabs_right_panel');

if (!function_exists('job_bm_settings_tabs_right_panel')) {
    function job_bm_settings_tabs_right_panel($id)
    {

    ?>
        <h3><?php echo __('Help & Support', 'job-board-manager'); ?></h3>
        <p><?php echo __('Please read documentation for customize Job Board Manger', 'job-board-manager'); ?></p>
        <a target="_blank" class="button" href="https://www.pickplugins.com/documentation/job-board-manager/?ref=dashboard"><?php echo __('Documentation', 'job-board-manager'); ?></a>

        <p><?php echo __('If you found any issue could not manage to solve yourself, please let us know and post your issue on forum.', 'job-board-manager'); ?></p>
        <a target="_blank" class="button" href="https://www.pickplugins.com/forum/?ref=dashboard"><?php echo __('Create Ticket', 'job-board-manager'); ?></a>

        <h3><?php echo __('Write Reviews', 'job-board-manager'); ?></h3>
        <p><?php echo __('If you found Job Board Manger help you to build something useful, please help us by providing your feedback and five star reviews on plugin page.', 'job-board-manager'); ?></p>
        <a target="_blank" class="button" href="https://wordpress.org/support/plugin/job-board-manager/reviews/#new-post"><?php echo sprintf(__('Rate Us %s', 'job-board-manager'), '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>'); ?></a>

        <h3><?php echo __('Shortcodes', 'job-board-manager'); ?></h3>
        <p><code>[job_bm_applications]</code> <br> <?php echo __('Display list of applications.', 'job-board-manager'); ?><br><a href="http://www.pickplugins.com/demo/job-board-manager/job-dashboard/?tabs=applications"><?php echo __('Demo', 'job-board-manager'); ?></a> </p>
        <p><code>[job_bm_dashboard]</code> <br> <?php echo __('Display job dashboard on front-end.', 'job-board-manager'); ?><br><a href="http://www.pickplugins.com/demo/job-board-manager/job-dashboard/"><?php echo __('Demo', 'job-board-manager'); ?></a></p>
        <p><code>[job_bm_archive]</code> <br> <?php echo __('Display job archive on front-end.', 'job-board-manager'); ?><br><a href="http://www.pickplugins.com/demo/job-board-manager/"><?php echo __('Demo', 'job-board-manager'); ?></a></p>
        <p><code>[job_bm_job_categories]</code> <br> <?php echo __('Display job categories in grid view.', 'job-board-manager'); ?><br><a href="http://www.pickplugins.com/demo/job-board-manager/"><?php echo __('Demo', 'job-board-manager'); ?></a></p>

        <p><code>[job_bm_job_edit]</code> <br> <?php echo __('Display job edit form on front-end.', 'job-board-manager'); ?><br><a href="http://www.pickplugins.com/demo/job-board-manager/job-edit/?job_id=4134"><?php echo __('Demo', 'job-board-manager'); ?></a></p>
        <p><code>[job_bm_job_submit]</code> <br> <?php echo __('Display job submit form on front-end.', 'job-board-manager'); ?><br><a href="http://www.pickplugins.com/demo/job-board-manager/job-submit/"><?php echo __('Demo', 'job-board-manager'); ?></a></p>
        <p><code>[job_bm_my_applications]</code> <br> <?php echo __('Display logged-in user submitted applications.', 'job-board-manager'); ?><br><a href="http://www.pickplugins.com/demo/job-board-manager/job-dashboard/?tabs=my_applications"><?php echo __('Demo', 'job-board-manager'); ?></a></p>
        <p><code>[job_bm_my_jobs]</code> <br> <?php echo __('Display logged-in user submitted jobs.', 'job-board-manager'); ?><br><a href="http://www.pickplugins.com/demo/job-board-manager/job-dashboard/?tabs=my_jobs"><?php echo __('Demo', 'job-board-manager'); ?></a></p>
        <p><code>[job_bm_registration_form]</code> <br> <?php echo __('Display register form on front-end.', 'job-board-manager'); ?><br><a href="http://www.pickplugins.com/demo/job-board-manager/job-dashboard/"><?php echo __('Demo', 'job-board-manager'); ?></a></p>




    <?php

    }
}





add_action('job_bm_settings_tabs_right_panel_archive', 'job_bm_settings_tabs_right_panel');

if (!function_exists('job_bm_settings_tabs_right_panel')) {
    function job_bm_settings_tabs_right_panel($id)
    {

    ?>
        <h3><?php echo __('Help & Support', 'job-board-manager'); ?></h3>
        <p><?php echo __('Please read documentation for customize Job Board Manger', 'job-board-manager'); ?></p>
        <a target="_blank" class="button" href="https://www.pickplugins.com/documentation/job-board-manager/?ref=dashboard"><?php echo __('Documentation', 'job-board-manager'); ?></a>

        <p><?php echo __('If you found any issue could not manage to solve yourself, please let us know and post your issue on forum.', 'job-board-manager'); ?></p>
        <a target="_blank" class="button" href="https://www.pickplugins.com/forum/?ref=dashboard"><?php echo __('Create Ticket', 'job-board-manager'); ?></a>

        <h3><?php echo __('Write Reviews', 'job-board-manager'); ?></h3>
        <p><?php echo __('If you found Job Board Manger help you to build something useful, please help us by providing your feedback and five star reviews on plugin page.', 'job-board-manager'); ?></p>
        <a target="_blank" class="button" href="https://wordpress.org/support/plugin/job-board-manager/reviews/#new-post"><?php echo sprintf(__('Rate Us %s', 'job-board-manager'), '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>'); ?></a>

        <h3><?php echo __('Shortcodes', 'job-board-manager'); ?></h3>
        <p><code>[job_bm_applications]</code> <br> <?php echo __('Display list of applications.', 'job-board-manager'); ?><br><a href="http://www.pickplugins.com/demo/job-board-manager/job-dashboard/?tabs=applications"><?php echo __('Demo', 'job-board-manager'); ?></a> </p>
        <p><code>[job_bm_dashboard]</code> <br> <?php echo __('Display job dashboard on front-end.', 'job-board-manager'); ?><br><a href="http://www.pickplugins.com/demo/job-board-manager/job-dashboard/"><?php echo __('Demo', 'job-board-manager'); ?></a></p>
        <p><code>[job_bm_archive]</code> <br> <?php echo __('Display job archive on front-end.', 'job-board-manager'); ?><br><a href="http://www.pickplugins.com/demo/job-board-manager/"><?php echo __('Demo', 'job-board-manager'); ?></a></p>
        <p><code>[job_bm_job_edit]</code> <br> <?php echo __('Display job edit form on front-end.', 'job-board-manager'); ?><br><a href="http://www.pickplugins.com/demo/job-board-manager/job-edit/?job_id=4134"><?php echo __('Demo', 'job-board-manager'); ?></a></p>
        <p><code>[job_bm_job_submit]</code> <br> <?php echo __('Display job submit form on front-end.', 'job-board-manager'); ?><br><a href="http://www.pickplugins.com/demo/job-board-manager/job-submit/"><?php echo __('Demo', 'job-board-manager'); ?></a></p>
        <p><code>[job_bm_my_applications]</code> <br> <?php echo __('Display logged-in user submitted applications.', 'job-board-manager'); ?><br><a href="http://www.pickplugins.com/demo/job-board-manager/job-dashboard/?tabs=my_applications"><?php echo __('Demo', 'job-board-manager'); ?></a></p>
        <p><code>[job_bm_my_jobs]</code> <br> <?php echo __('Display logged-in user submitted jobs.', 'job-board-manager'); ?><br><a href="http://www.pickplugins.com/demo/job-board-manager/job-dashboard/?tabs=my_jobs"><?php echo __('Demo', 'job-board-manager'); ?></a></p>
        <p><code>[job_bm_registration_form]</code> <br> <?php echo __('Display register form on front-end.', 'job-board-manager'); ?> <br><a href="http://www.pickplugins.com/demo/job-board-manager/job-dashboard/"><?php echo __('Demo', 'job-board-manager'); ?></a></p>




    <?php

    }
}




add_action('job_bm_settings_tabs_right_panel_pages', 'job_bm_settings_tabs_right_pages');
add_action('job_bm_settings_tabs_right_panel_email', 'job_bm_settings_tabs_right_pages');
add_action('job_bm_settings_tabs_right_panel_style', 'job_bm_settings_tabs_right_pages');
add_action('job_bm_settings_tabs_right_panel_expiry', 'job_bm_settings_tabs_right_pages');

if (!function_exists('job_bm_settings_tabs_right_pages')) {
    function job_bm_settings_tabs_right_pages($id)
    {

    ?>
        <h3><?php echo __('Help & Support', 'job-board-manager'); ?></h3>
        <p><?php echo __('Please read documentation for customize Job Board Manger', 'job-board-manager'); ?></p>
        <a target="_blank" class="button" href="https://www.pickplugins.com/documentation/job-board-manager/?ref=dashboard"><?php echo __('Documentation', 'job-board-manager'); ?></a>

        <p><?php echo __('If you found any issue could not manage to solve yourself, please let us know and post your issue on forum.', 'job-board-manager'); ?></p>
        <a target="_blank" class="button" href="https://www.pickplugins.com/forum/?ref=dashboard"><?php echo __('Create Ticket', 'job-board-manager'); ?></a>

        <h3><?php echo __('Write Reviews', 'job-board-manager'); ?></h3>
        <p><?php echo __('If you found Job Board Manger help you to build something useful, please help us by providing your feedback and five star reviews on plugin page.', 'job-board-manager'); ?></p>
        <a target="_blank" class="button" href="https://wordpress.org/support/plugin/job-board-manager/reviews/#new-post"><?php echo sprintf(__('Rate Us %s', 'job-board-manager'), '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>'); ?></a>

        <h3><?php echo __('Shortcodes', 'job-board-manager'); ?></h3>
        <p><code>[job_bm_applications]</code> <br> <?php echo __(' Display list of applications.', 'job-board-manager'); ?><br><a href="http://www.pickplugins.com/demo/job-board-manager/job-dashboard/?tabs=applications"><?php echo __('Demo', 'job-board-manager'); ?></a> </p>
        <p><code>[job_bm_dashboard]</code> <br> <?php echo __('Display job dashboard on front-end.', 'job-board-manager'); ?><br><a href="http://www.pickplugins.com/demo/job-board-manager/job-dashboard/"><?php echo __('Demo', 'job-board-manager'); ?></a></p>
        <p><code>[job_bm_archive]</code> <br> <?php echo __('Display job archive on front-end.', 'job-board-manager'); ?><br><a href="http://www.pickplugins.com/demo/job-board-manager/"><?php echo __('Demo', 'job-board-manager'); ?></a></p>
        <p><code>[job_bm_job_edit]</code> <br> <?php echo __('Display job edit form on front-end. ', 'job-board-manager'); ?><br><a href="http://www.pickplugins.com/demo/job-board-manager/job-edit/?job_id=4134"><?php echo __('Demo', 'job-board-manager'); ?></a></p>
        <p><code>[job_bm_job_submit]</code> <br> <?php echo __('Display job submit form on front-end.', 'job-board-manager'); ?><br><a href="http://www.pickplugins.com/demo/job-board-manager/job-submit/"><?php echo __('Demo', 'job-board-manager'); ?></a></p>
        <p><code>[job_bm_my_applications]</code> <br> <?php echo __('Display logged-in user submitted applications.', 'job-board-manager'); ?><br><a href="http://www.pickplugins.com/demo/job-board-manager/job-dashboard/?tabs=my_applications"><?php echo __('Demo', 'job-board-manager'); ?></a></p>
        <p><code>[job_bm_my_jobs]</code> <br> Display logged-in user submitted jobs. <?php echo __('Demo', 'job-board-manager'); ?><br><a href="http://www.pickplugins.com/demo/job-board-manager/job-dashboard/?tabs=my_jobs"><?php echo __('Demo', 'job-board-manager'); ?></a></p>
        <p><code>[job_bm_registration_form]</code> <br> <?php echo __('Display register form on front-end.', 'job-board-manager'); ?><br><a href="http://www.pickplugins.com/demo/job-board-manager/job-dashboard/"><?php echo __('Demo', 'job-board-manager'); ?></a></p>




    <?php

    }
}





add_action('job_bm_settings_tabs_right_panel_job_submit', 'job_bm_settings_tabs_right_job_submit');

if (!function_exists('job_bm_settings_tabs_right_job_submit')) {
    function job_bm_settings_tabs_right_job_submit($id)
    {

    ?>
        <h3><?php echo __('Help & Support', 'job-board-manager'); ?></h3>
        <p><?php echo __('Please read documentation for customize Job Board Manger', 'job-board-manager'); ?></p>
        <a target="_blank" class="button" href="https://www.pickplugins.com/documentation/job-board-manager/?ref=dashboard"><?php echo __('Documentation', 'job-board-manager'); ?></a>

        <p><?php echo __('If you found any issue could not manage to solve yourself, please let us know and post your issue on forum.', 'job-board-manager'); ?></p>
        <a target="_blank" class="button" href="https://www.pickplugins.com/forum/?ref=dashboard"><?php echo __('Create Ticket', 'job-board-manager'); ?></a>

        <h3><?php echo __('Write Reviews', 'job-board-manager'); ?></h3>
        <p><?php echo __('If you found Job Board Manger help you to build something useful, please help us by providing your feedback and five star reviews on plugin page.', 'job-board-manager'); ?></p>
        <a target="_blank" class="button" href="https://wordpress.org/support/plugin/job-board-manager/reviews/#new-post"><?php echo sprintf(__('Rate Us %s', 'job-board-manager'), '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>'); ?></a>

        <h3><?php echo __('Shortcodes', 'job-board-manager'); ?></h3>

        <p><code>[job_bm_job_submit]</code> <br> <?php echo __('Display job submit form on front-end.', 'job-board-manager'); ?><br><a href="http://www.pickplugins.com/demo/job-board-manager/job-submit/"><?php echo __('Demo', 'job-board-manager'); ?></a></p>




    <?php

    }
}


add_action('job_bm_settings_tabs_right_panel_job_edit', 'job_bm_settings_tabs_right_panel_job_edit');

if (!function_exists('job_bm_settings_tabs_right_panel_job_edit')) {
    function job_bm_settings_tabs_right_panel_job_edit($id)
    {

    ?>
        <h3><?php echo __('Help & Support', 'job-board-manager'); ?></h3>
        <p><?php echo __('Please read documentation for customize Job Board Manger', 'job-board-manager'); ?></p>
        <a target="_blank" class="button" href="https://www.pickplugins.com/documentation/job-board-manager/?ref=dashboard"><?php echo __('Documentation', 'job-board-manager'); ?></a>

        <p><?php echo __('If you found any issue could not manage to solve yourself, please let us know and post your issue on forum.', 'job-board-manager'); ?></p>
        <a target="_blank" class="button" href="https://www.pickplugins.com/forum/?ref=dashboard"><?php echo __('Create Ticket', 'job-board-manager'); ?></a>

        <h3><?php echo __('Write Reviews', 'job-board-manager'); ?></h3>
        <p><?php echo __('If you found Job Board Manger help you to build something useful, please help us by providing your feedback and five star reviews on plugin page.', 'job-board-manager'); ?></p>
        <a target="_blank" class="button" href="https://wordpress.org/support/plugin/job-board-manager/reviews/#new-post"><?php echo sprintf(__('Rate Us %s', 'job-board-manager'), '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>'); ?></a>

        <h3><?php echo __('Shortcodes', 'job-board-manager'); ?></h3>

        <p><code>[job_bm_job_edit]</code> <br> <?php echo __('Display job edit form on front-end.', 'job-board-manager'); ?><br><a href="http://www.pickplugins.com/demo/job-board-manager/job-edit/?job_id=4134"><?php echo __('Demo', 'job-board-manager'); ?></a></p>




    <?php

    }
}

add_action('job_bm_settings_tabs_right_panel_dashboard', 'job_bm_settings_tabs_right_panel_dashboard');

if (!function_exists('job_bm_settings_tabs_right_panel_dashboard')) {
    function job_bm_settings_tabs_right_panel_dashboard($id)
    {

    ?>
        <h3><?php echo __('Help & Support', 'job-board-manager'); ?></h3>
        <p><?php echo __('Please read documentation for customize Job Board Manger', 'job-board-manager'); ?></p>
        <a target="_blank" class="button" href="https://www.pickplugins.com/documentation/job-board-manager/?ref=dashboard"><?php echo __('Documentation', 'job-board-manager'); ?></a>

        <p><?php echo __('If you found any issue could not manage to solve yourself, please let us know and post your issue on forum.', 'job-board-manager'); ?></p>
        <a target="_blank" class="button" href="https://www.pickplugins.com/forum/?ref=dashboard"><?php echo __('Create Ticket', 'job-board-manager'); ?></a>

        <h3><?php echo __('Write Reviews', 'job-board-manager'); ?></h3>
        <p><?php echo __('If you found Job Board Manger help you to build something useful, please help us by providing your feedback and five star reviews on plugin page.', 'job-board-manager'); ?></p>
        <a target="_blank" class="button" href="https://wordpress.org/support/plugin/job-board-manager/reviews/#new-post"><?php echo sprintf(__('Rate Us %s', 'job-board-manager'), '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>'); ?></a>

        <h3><?php echo __('Shortcodes', 'job-board-manager'); ?></h3>

        <p><code>[job_bm_dashboard]</code> <br> <?php echo __('Display job dashboard on front-end.', 'job-board-manager'); ?><br><a href="http://www.pickplugins.com/demo/job-board-manager/job-dashboard/"><?php echo __('Demo', 'job-board-manager'); ?></a></p>




    <?php

    }
}




add_action('job_bm_settings_tabs_right_panel_applications', 'job_bm_settings_tabs_right_panel_applications');

if (!function_exists('job_bm_settings_tabs_right_panel_applications')) {
    function job_bm_settings_tabs_right_panel_applications($id)
    {

    ?>
        <h3><?php echo __('Help & Support', 'job-board-manager'); ?></h3>
        <p><?php echo __('Please read documentation for customize Job Board Manger', 'job-board-manager'); ?></p>
        <a target="_blank" class="button" href="https://www.pickplugins.com/documentation/job-board-manager/?ref=dashboard"><?php echo __('Documentation', 'job-board-manager'); ?></a>

        <p><?php echo __('If you found any issue could not manage to solve yourself, please let us know and post your issue on forum.', 'job-board-manager'); ?></p>
        <a target="_blank" class="button" href="https://www.pickplugins.com/forum/?ref=dashboard"><?php echo __('Create Ticket', 'job-board-manager'); ?></a>

        <h3><?php echo __('Write Reviews', 'job-board-manager'); ?></h3>
        <p><?php echo __('If you found Job Board Manger help you to build something useful, please help us by providing your feedback and five star reviews on plugin page.', 'job-board-manager'); ?></p>
        <a target="_blank" class="button" href="https://wordpress.org/support/plugin/job-board-manager/reviews/#new-post"><?php echo sprintf(__('Rate Us %s', 'job-board-manager'), '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>'); ?></a>

        <h3><?php echo __('Shortcodes', 'job-board-manager'); ?></h3>
        <p><code>[job_bm_applications]</code> <br> <?php echo __('Display list of applications.', 'job-board-manager'); ?><br><a href="http://www.pickplugins.com/demo/job-board-manager/job-dashboard/?tabs=applications"><?php echo __('Demo', 'job-board-manager'); ?></a> </p>
        <p><code>[job_bm_my_applications]</code> <br> <?php echo __('Display logged-in user submitted applications.', 'job-board-manager'); ?><br><a href="http://www.pickplugins.com/demo/job-board-manager/job-dashboard/?tabs=my_applications"><?php echo __('Demo', 'job-board-manager'); ?></a></p>




    <?php

    }
}




add_action('job_bm_settings_tabs_content_applications', 'job_bm_settings_tabs_content_applications');

if (!function_exists('job_bm_settings_tabs_content_applications')) {
    function job_bm_settings_tabs_content_applications($tab)
    {

        $settings_tabs_field = new settings_tabs_field();
        $class_job_bm_functions = new class_job_bm_functions();
        $apply_method_list = $class_job_bm_functions->apply_method_list();


        $job_bm_application_methods = get_option('job_bm_application_methods');
        $job_bm_login_required_on_apply = get_option('job_bm_login_required_on_apply');
        $job_bm_apply_enable_recaptcha = get_option('job_bm_apply_enable_recaptcha');







    ?>
        <div class="section">
            <div class="section-title"><?php echo __('Job application settings', 'job-board-manager'); ?></div>
            <p class="description section-description"><?php echo __('Customize job application settings.', 'job-board-manager'); ?></p>

            <?php

            $args = array(
                'id'        => 'job_bm_login_required_on_apply',
                //'parent'		=> '',
                'title'        => __('Login required for application', 'job-board-manager'),
                'details'    => __('Login is required or not for submit application.', 'job-board-manager'),
                'type'        => 'select',
                //'multiple'		=> true,
                'value'        => $job_bm_login_required_on_apply,
                'default'        => 'yes',
                'args'        => array('no' => __('No', 'job-board-manager'), 'yes' => __('Yes', 'job-board-manager'),),
            );

            $settings_tabs_field->generate_field($args);

            $args = array(
                'id'        => 'job_bm_apply_enable_recaptcha',
                //'parent'		=> '',
                'title'        => __('Enable recaptcha', 'job-board-manager'),
                'details'    => __('Enable recaptcha on submit application.', 'job-board-manager'),
                'type'        => 'select',
                //'multiple'		=> true,
                'value'        => $job_bm_apply_enable_recaptcha,
                'default'        => 'yes',
                'args'        => array('no' => __('No', 'job-board-manager'), 'yes' => __('Yes', 'job-board-manager'),),
            );

            $settings_tabs_field->generate_field($args);


            $args = array(
                'id'        => 'job_bm_application_methods',
                //'parent'		=> '',
                'title'        => __('Application methods', 'job-board-manager'),
                'details'    => __('Choose application method on job post.', 'job-board-manager'),
                'type'        => 'select',
                'multiple'        => true,
                'value'        => $job_bm_application_methods,
                'default'        => array('none'),
                'args'        => $apply_method_list,
            );

            $settings_tabs_field->generate_field($args);

            ?>


        </div>
<?php


    }
}
