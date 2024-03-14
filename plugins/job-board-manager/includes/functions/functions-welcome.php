<?php
if (!defined('ABSPATH')) exit;  // if direct access 





add_action('job_bm_welcome_tabs_content_start', 'job_bm_welcome_tabs_content_start');

if (!function_exists('job_bm_welcome_tabs_content_start')) {
    function job_bm_welcome_tabs_content_start($tab)
    {



?>

        <h2><?php echo __('Welcome to Job Board Manager Setup', 'job-board-manager'); ?></h2>
        <p><?php echo __('Thanks for choosing Job Board Manager for your job site, Please go step by step and choose some options to get started.', 'job-board-manager'); ?></p>
        <p><?php echo __('If you have any issue during setup please contact us for help and you can post on our forum by creating support tickets.', 'job-board-manager'); ?></p>
        <p><a target="_blank" class="button" href="https://www.pickplugins.com/forum/"><?php echo __('Create Ticket', 'job-board-manager'); ?></a></p>

        <p><?php echo sprintf(__('We spend thousand hours to build this plugin for you, continuously updating, fixing bugs, add new features, creating add-ons, solving user issues and many more. we do live by creating plugin like Job Board Manager, we hope your wise feedback and reviews on plugin page. Give us %s', 'job-board-manager'), '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>') ?> </p>
        <p><a target="_blank" class="button" href="https://wordpress.org/support/plugin/job-board-manager/reviews/#new-post"><?php echo __('Write a reviews', 'job-board-manager'); ?></a></p>
    <?php


    }
}



add_action('job_bm_welcome_tabs_content_general', 'job_bm_welcome_tabs_content_general');

if (!function_exists('job_bm_welcome_tabs_content_general')) {
    function job_bm_welcome_tabs_content_general($tab)
    {

        $settings_tabs_field = new settings_tabs_field();
        $class_job_bm_functions = new class_job_bm_functions();
        $apply_method_list = $class_job_bm_functions->apply_method_list();

        $job_bm_list_per_page = get_option('job_bm_list_per_page');
        $job_bm_account_required_post_job = get_option('job_bm_account_required_post_job');
        $job_bm_submitted_job_status = get_option('job_bm_submitted_job_status');
        $job_bm_can_user_edit_published_jobs = get_option('job_bm_can_user_edit_published_jobs');
        $job_bm_can_user_delete_jobs = get_option('job_bm_can_user_delete_jobs');
        $job_bm_can_user_delete_application = get_option('job_bm_can_user_delete_application');
        $job_bm_application_methods = get_option('job_bm_application_methods');
        $job_bm_salary_currency = get_option('job_bm_salary_currency');



    ?>
        <div class="section">
            <div class="section-title"><?php echo __('General settings', 'job-board-manager'); ?></div>
            <p class="description section-description"><?php echo __('Choose some general option.', 'job-board-manager'); ?></p>

            <?php

            $args = array(
                'id'        => 'job_bm_list_per_page',
                //'parent'		=> '',
                'title'        => __('Job per page', 'job-board-manager'),
                'details'    => __('Set custom number of job per page on job archive', 'job-board-manager'),
                'type'        => 'text',
                'value'        => $job_bm_list_per_page,
                'default'        => 10,
                'placeholder'        => '',
            );

            $settings_tabs_field->generate_field($args);


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

            $args = array(
                'id'        => 'job_bm_salary_currency',
                //'parent'		=> '',
                'title'        => __('Salary currency', 'job-board-manager'),
                'details'    => __('Salary currency display on job page.', 'job-board-manager'),
                'type'        => 'text',
                //'multiple'		=> true,
                'value'        => $job_bm_salary_currency,
                'default'        => 'USD',
            );

            $settings_tabs_field->generate_field($args);

            ?>


        </div>
    <?php


    }
}




add_action('job_bm_welcome_tabs_content_create_pages', 'job_bm_welcome_tabs_content_create_pages');

if (!function_exists('job_bm_welcome_tabs_content_create_pages')) {
    function job_bm_welcome_tabs_content_create_pages($tab)
    {

        $settings_tabs_field = new settings_tabs_field();

        $job_bm_archive_page_id = get_option('job_bm_archive_page_id');
        $job_bm_job_submit_page_id = get_option('job_bm_job_submit_page_id');
        $job_bm_job_edit_page_id = get_option('job_bm_job_edit_page_id');
        $job_bm_job_login_page_id = get_option('job_bm_job_login_page_id');


        $page_list = job_bm_page_list_id();

        $page_list['create_new'] = __('-- Create new page --', 'job-board-manager');

        //$page_list = array_merge($page_list, array('create_new'=> '-- Create new page --'));


        //echo '<pre>'.var_export($page_list, true).'</pre>';


    ?>
        <div class="section">
            <div class="section-title"><?php echo __('Create pages', 'job-board-manager'); ?></div>
            <p class="description section-description"><?php echo __('You can create some basic pages or choose from existing. please choose <b>-- Create new page --</b> to create.', 'job-board-manager'); ?></p>

            <?php


            $args = array(
                'id'        => 'job_bm_job_login_page_id',
                //'parent'		=> '',
                'title'        => __('Dashboard page', 'job-board-manager'),
                'details'    => __('Choose the page for dashboard page, where the shortcode <code>[job_bm_dashboard]</code> used.', 'job-board-manager'),
                'type'        => 'select',
                //'multiple'		=> true,
                'value'        => $job_bm_job_login_page_id,
                'default'        => 'create_new',
                'args'        => $page_list,
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
                'default'        => 'create_new',
                'args'        => $page_list,
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
                'default'        => 'create_new',
                'args'        => $page_list,
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
                'default'        => 'create_new',
                'args'        => $page_list,
            );

            $settings_tabs_field->generate_field($args);






            ?>


        </div>
    <?php


    }
}






add_action('job_bm_welcome_tabs_content_done', 'job_bm_welcome_tabs_content_done');

if (!function_exists('job_bm_welcome_tabs_content_done')) {
    function job_bm_welcome_tabs_content_done($tab)
    {

        $hidden = isset($_POST['job_bm_hidden']) ? sanitize_text_field($_POST['job_bm_hidden']) : '';

        //var_dump($hidden);

    ?>
        <div class="section">

            <h3 style="text-align: center" class=""><?php echo __('Click to save settings.', 'job-board-manager'); ?></h3>
            <p style="text-align: center"><?php echo __('You can review settings by clicking next and previous button', 'job-board-manager'); ?></p>
            <div class="submit-wrap">
                <?php wp_nonce_field('job_bm_nonce'); ?>
                <input class="button" type="submit" name="submit" value="<?php _e('Save Settings', 'job-board-manager'); ?>" />
            </div>


        </div>
    <?php


    }
}


add_action('job_bm_welcome_submit', 'job_bm_welcome_submit_after_html');

if (!function_exists('job_bm_welcome_submit_after_html')) {
    function job_bm_welcome_submit_after_html($form_data)
    {

        $job_bm_job_submit_page_id           = get_option('job_bm_job_submit_page_id');
        $job_bm_job_submit_page_url                 = get_permalink($job_bm_job_submit_page_id);

        $class_job_bm_support_help = new class_job_bm_support_help();
        $addons_list = $class_job_bm_support_help->addons_list();

    ?>
        <div class="welcome-tabs">
            <div class="tab-content active" style="text-align: center">
                <h3><?php echo sprintf(__('%s Great, All looks good.', 'job-board-manager'), '<i class="far fa-thumbs-up"></i>'); ?></h3>
                <p><?php echo __('You have successfully completed welcome setup <br>and you are almost ready to start your job site, go and visit created pages.', 'job-board-manager'); ?></p>
                <p>
                    <a class="button" target="_blank" href="<?php echo esc_url($job_bm_job_submit_page_url); ?>"><?php echo __('Post a job', 'job-board-manager'); ?></a>
                    <a class="button" target="_blank" href="<?php echo esc_url(admin_url('edit.php?post_type=job&page=job_bm_settings')); ?>"><?php echo __('Check settings', 'job-board-manager'); ?></a>
                    <a class="button" target="_blank" href="<?php echo esc_url(admin_url()); ?>"><?php echo __('Go dashboard', 'job-board-manager'); ?></a>

                </p>

                <h5><?php echo __('Write a reviews', 'job-board-manager'); ?></h5>
                <p><?php echo sprintf(__('We spend most of our work hours to build WordPress plugin, <br>we expect your few minutes to provide your wise feedback and suggestions. and give us %s', 'job-board-manager'), '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>'); ?></p>
                <p><a class="button" href="https://wordpress.org/support/plugin/job-board-manager/reviews/#new-post"><?php echo __('Write a reviews', 'job-board-manager'); ?> </a></p>

                <h5><?php echo __('Download some add-ons', 'job-board-manager'); ?></h5>

                <div class="addon-list">

                    <?php

                    if (!empty($addons_list)) :
                        foreach ($addons_list as $addon) :
                            $addon_title = $addon['title'];
                            $addon_link = $addon['item_link'];
                            $addon_thumb = $addon['thumb'];

                    ?>
                            <div class="item">
                                <div class="thumb-wrap">
                                    <img src="<?php echo $addon_thumb; ?>">
                                </div>
                                <div class="addon-title"><?php echo $addon_title; ?></div>
                                <div class="addon-link button"><a href="<?php echo $addon_link; ?>"><?php echo __('Download', 'job-board-manager'); ?></a> </div>
                            </div>
                    <?php
                        endforeach;
                    endif;

                    ?>


                </div>


            </div>
        </div>
<?php


    }
}


add_action('job_bm_welcome_submit', 'job_bm_welcome_submit');

if (!function_exists('job_bm_welcome_submit')) {
    function job_bm_welcome_submit($form_data)
    {


        $job_bm_list_per_page = isset($form_data['job_bm_list_per_page']) ? $form_data['job_bm_list_per_page'] : '';
        $job_bm_account_required_post_job = isset($form_data['job_bm_account_required_post_job']) ? $form_data['job_bm_account_required_post_job'] : '';
        $job_bm_submitted_job_status = isset($form_data['job_bm_submitted_job_status']) ? $form_data['job_bm_submitted_job_status'] : '';
        $job_bm_can_user_edit_published_jobs = isset($form_data['job_bm_can_user_edit_published_jobs']) ? $form_data['job_bm_can_user_edit_published_jobs'] : '';
        $job_bm_can_user_delete_jobs = isset($form_data['job_bm_can_user_delete_jobs']) ? $form_data['job_bm_can_user_delete_jobs'] : '';
        $job_bm_can_user_delete_application = isset($form_data['job_bm_can_user_delete_application']) ? $form_data['job_bm_can_user_delete_application'] : '';
        $job_bm_application_methods = isset($form_data['job_bm_application_methods']) ? $form_data['job_bm_application_methods'] : '';
        $job_bm_salary_currency = isset($form_data['job_bm_salary_currency']) ? $form_data['job_bm_salary_currency'] : '';

        $job_bm_archive_page_id = isset($form_data['job_bm_archive_page_id']) ? $form_data['job_bm_archive_page_id'] : '';
        $job_bm_job_submit_page_id = isset($form_data['job_bm_job_submit_page_id']) ? $form_data['job_bm_job_submit_page_id'] : '';
        $job_bm_job_edit_page_id = isset($form_data['job_bm_job_edit_page_id']) ? $form_data['job_bm_job_edit_page_id'] : '';
        $job_bm_job_login_page_id = isset($form_data['job_bm_job_login_page_id']) ? $form_data['job_bm_job_login_page_id'] : '';




        update_option('job_bm_list_per_page', $job_bm_list_per_page);
        update_option('job_bm_account_required_post_job', $job_bm_account_required_post_job);
        update_option('job_bm_submitted_job_status', $job_bm_submitted_job_status);
        update_option('job_bm_submitted_job_status', $job_bm_submitted_job_status);
        update_option('job_bm_can_user_edit_published_jobs', $job_bm_can_user_edit_published_jobs);
        update_option('job_bm_can_user_delete_jobs', $job_bm_can_user_delete_jobs);
        update_option('job_bm_can_user_delete_application', $job_bm_can_user_delete_application);
        update_option('job_bm_application_methods', $job_bm_application_methods);
        update_option('job_bm_salary_currency', $job_bm_salary_currency);


        if ($job_bm_archive_page_id == 'create_new') {

            $page_id = wp_insert_post(
                array(
                    'post_title'    => __('Job Archive', 'job-board-manager'),
                    'post_content'  => '[job_bm_archive]',
                    'post_status'   => 'publish',
                    'post_type'       => 'page',

                )
            );

            update_option('job_bm_archive_page_id', $page_id);
        } else {
            update_option('job_bm_archive_page_id', $job_bm_archive_page_id);
        }


        if ($job_bm_job_submit_page_id == 'create_new') {

            $page_id = wp_insert_post(
                array(
                    'post_title'    => __('Job Submit', 'job-board-manager'),
                    'post_content'  => '[job_submit_form]',
                    'post_status'   => 'publish',
                    'post_type'       => 'page',

                )
            );

            update_option('job_bm_job_submit_page_id', $page_id);
        } else {
            update_option('job_bm_job_submit_page_id', $job_bm_job_submit_page_id);
        }



        if ($job_bm_job_edit_page_id == 'create_new') {

            $page_id = wp_insert_post(
                array(
                    'post_title'    => __('Job Edit', 'job-board-manager'),
                    'post_content'  => '[job_bm_job_edit]',
                    'post_status'   => 'publish',
                    'post_type'       => 'page',

                )
            );

            update_option('job_bm_job_edit_page_id', $page_id);
        } else {
            update_option('job_bm_job_edit_page_id', $job_bm_job_edit_page_id);
        }



        if ($job_bm_job_login_page_id == 'create_new') {

            $page_id = wp_insert_post(
                array(
                    'post_title'    => __('Job Dashboard', 'job-board-manager'),
                    'post_content'  => '[job_bm_dashboard]',
                    'post_status'   => 'publish',
                    'post_type'       => 'page',

                )
            );

            update_option('job_bm_job_login_page_id', $page_id);
        } else {
            update_option('job_bm_job_login_page_id', $job_bm_job_login_page_id);
        }




        update_option('job_bm_welcome', 'done');
    }
}
