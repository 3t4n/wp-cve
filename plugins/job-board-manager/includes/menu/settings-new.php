<?php	
if ( ! defined('ABSPATH')) exit;  // if direct access


$job_bm_settings_tab = array();

$job_bm_settings_tab[] = array(
    'id' => 'archive',
    'title' => sprintf(__('%s Archive','job-board-manager'),'<i class="fas fa-list-ul"></i>'),
    'priority' => 1,
    'active' => true,
);

$job_bm_settings_tab[] = array(
    'id' => 'pages',
    'title' => sprintf(__('%s Pages','job-board-manager'),'<i class="far fa-copy"></i>'),
    'priority' => 2,
    'active' => false,
);



$job_bm_settings_tab[] = array(
    'id' => 'job_submit',
    'title' => sprintf(__('%s Job Submit','job-board-manager'),'<i class="fas fa-pencil-ruler"></i>'),
    'priority' => 3,
    'active' => false,
);

$job_bm_settings_tab[] = array(
    'id' => 'job_edit',
    'title' => sprintf(__('%s Job Edit','job-board-manager'),'<i class="fas fa-pencil-alt"></i>'),
    'priority' => 4,
    'active' => false,
);

$job_bm_settings_tab[] = array(
    'id' => 'dashboard',
    'title' => sprintf(__('%s Dashboard','job-board-manager'),'<i class="fas fa-tachometer-alt"></i>'),
    'priority' => 5,
    'active' => false,
);


$job_bm_settings_tab[] = array(
    'id' => 'email',
    'title' => sprintf(__('%s Email','job-board-manager'),'<i class="far fa-envelope"></i>'),
    'priority' => 6,
    'active' => false,
);

$job_bm_settings_tab[] = array(
    'id' => 'style',
    'title' => sprintf(__('%s Style','job-board-manager'),'<i class="fas fa-palette"></i>'),
    'priority' => 7,
    'active' => false,
);

$job_bm_settings_tab[] = array(
    'id' => 'expiry',
    'title' => sprintf(__('%s Expiry','job-board-manager'),'<i class="fas fa-calendar-day"></i>'),
    'priority' => 8,
    'active' => false,
);

$job_bm_settings_tab[] = array(
    'id' => 'applications',
    'title' => sprintf(__('%s Applications','job-board-manager'), '<i class="fas fa-envelope-open-text"></i>'),
    'priority' => 9,
    'active' => false,
);

$job_bm_settings_tab = apply_filters('job_bm_settings_tabs', $job_bm_settings_tab);

$tabs_sorted = array();
foreach ($job_bm_settings_tab as $page_key => $tab) $tabs_sorted[$page_key] = isset( $tab['priority'] ) ? $tab['priority'] : 0;
array_multisort($tabs_sorted, SORT_ASC, $job_bm_settings_tab);

wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-sortable');
wp_enqueue_script( 'jquery-ui-core' );
wp_enqueue_script('jquery-ui-accordion');

wp_enqueue_style( 'font-awesome-5' );
wp_enqueue_script( 'settings-tabs' );
wp_enqueue_style( 'settings-tabs' );
wp_enqueue_script( 'wp-color-picker' );
wp_enqueue_style( 'wp-color-picker' );

?>
<div class="wrap">
	<div id="icon-tools" class="icon32"><br></div><h2><?php echo sprintf(__('%s Settings', 'job-board-manager'), job_bm_plugin_name)?></h2>
		<form  method="post" action="<?php echo str_replace( '%7E', '~', esc_url_raw($_SERVER['REQUEST_URI'])); ?>">
	        <input type="hidden" name="job_bm_hidden" value="Y">
            <?php
            if(!empty($_POST['job_bm_hidden'])){

                $nonce = sanitize_text_field($_POST['_wpnonce']);

                if(wp_verify_nonce( $nonce, 'job_bm_nonce' ) && $_POST['job_bm_hidden'] == 'Y') {

                    $job_bm_list_per_page = isset($_POST['job_bm_list_per_page']) ?  sanitize_text_field($_POST['job_bm_list_per_page']) : '';
                    $job_bm_pagination_bg_color = isset($_POST['job_bm_pagination_bg_color']) ?  sanitize_text_field($_POST['job_bm_pagination_bg_color']) : '';
                    $job_bm_pagination_active_bg_color = isset($_POST['job_bm_pagination_active_bg_color']) ?  sanitize_text_field($_POST['job_bm_pagination_active_bg_color']) : '';
                    $job_bm_pagination_text_color = isset($_POST['job_bm_pagination_text_color']) ?  sanitize_text_field($_POST['job_bm_pagination_text_color']) : '';
                    $job_bm_salary_currency = isset($_POST['job_bm_salary_currency']) ?  sanitize_text_field($_POST['job_bm_salary_currency']) : '';
                    $job_bm_can_user_delete_jobs = isset($_POST['job_bm_can_user_delete_jobs']) ?  sanitize_text_field($_POST['job_bm_can_user_delete_jobs']) : '';
                    $job_bm_can_user_delete_application = isset($_POST['job_bm_can_user_delete_application']) ?  sanitize_text_field($_POST['job_bm_can_user_delete_application']) : '';
                    $job_bm_can_user_edit_published_jobs = isset($_POST['job_bm_can_user_edit_published_jobs']) ?  sanitize_text_field($_POST['job_bm_can_user_edit_published_jobs']) : '';
                    $job_bm_job_login_page_id = isset($_POST['job_bm_job_login_page_id']) ?  sanitize_text_field($_POST['job_bm_job_login_page_id']) : '';
                    $job_bm_archive_page_id = isset($_POST['job_bm_archive_page_id']) ?  sanitize_text_field($_POST['job_bm_archive_page_id']) : '';
                    $job_bm_job_submit_page_id = isset($_POST['job_bm_job_submit_page_id']) ?  sanitize_text_field($_POST['job_bm_job_submit_page_id']) : '';
                    $job_bm_job_edit_page_id = isset($_POST['job_bm_job_edit_page_id']) ?  sanitize_text_field($_POST['job_bm_job_edit_page_id']) : '';
                    $job_bm_registration_enable = isset($_POST['job_bm_registration_enable']) ?  sanitize_text_field($_POST['job_bm_registration_enable']) : '';
                    $job_bm_registration_recaptcha = isset($_POST['job_bm_registration_recaptcha']) ?  sanitize_text_field($_POST['job_bm_registration_recaptcha']) : '';


                    $job_bm_login_enable = isset($_POST['job_bm_login_enable']) ?  sanitize_text_field($_POST['job_bm_login_enable']) : '';
                    $job_bm_account_required_post_job = isset($_POST['job_bm_account_required_post_job']) ?  sanitize_text_field($_POST['job_bm_account_required_post_job']) : '';
                    $job_bm_reCAPTCHA_enable = isset($_POST['job_bm_reCAPTCHA_enable']) ?  sanitize_text_field($_POST['job_bm_reCAPTCHA_enable']) : '';
                    $job_bm_reCAPTCHA_site_key = isset($_POST['job_bm_reCAPTCHA_site_key']) ?  sanitize_text_field($_POST['job_bm_reCAPTCHA_site_key']) : '';
                    $job_bm_reCAPTCHA_secret_key = isset($_POST['job_bm_reCAPTCHA_secret_key']) ?  sanitize_text_field($_POST['job_bm_reCAPTCHA_secret_key']) : '';
                    $job_bm_submitted_job_status = isset($_POST['job_bm_submitted_job_status']) ?  sanitize_text_field($_POST['job_bm_submitted_job_status']) : '';
                    $job_bm_application_methods = isset($_POST['job_bm_application_methods']) ?  job_bm_recursive_sanitize_arr($_POST['job_bm_application_methods']) : array('none');
                    $job_bm_apply_enable_recaptcha = isset($_POST['job_bm_apply_enable_recaptcha']) ?  job_bm_recursive_sanitize_arr($_POST['job_bm_apply_enable_recaptcha']) : '';
                    $job_bm_login_required_on_apply = isset($_POST['job_bm_login_required_on_apply']) ?  job_bm_recursive_sanitize_arr($_POST['job_bm_login_required_on_apply']) : '';
                    $job_bm_redirect_preview_link = isset($_POST['job_bm_redirect_preview_link']) ?  sanitize_text_field($_POST['job_bm_redirect_preview_link']) : '';
                    $job_bm_edited_job_status = isset($_POST['job_bm_edited_job_status']) ?  sanitize_text_field($_POST['job_bm_edited_job_status']) : '';
                    $job_bm_edited_redirect_link = isset($_POST['job_bm_edited_redirect_link']) ?  sanitize_text_field($_POST['job_bm_edited_redirect_link']) : '';
                    $job_bm_job_edit_notify_email = isset($_POST['job_bm_job_edit_notify_email']) ?  sanitize_email($_POST['job_bm_job_edit_notify_email']) : '';
                    $job_bm_redirect_login = isset($_POST['job_bm_redirect_login']) ?  sanitize_text_field($_POST['job_bm_redirect_login']) : '';
                    $job_bm_redirect_logout = isset($_POST['job_bm_redirect_logout']) ?  sanitize_text_field($_POST['job_bm_redirect_logout']) : '';

                    $job_bm_redirect_after_signup = isset($_POST['job_bm_redirect_after_signup']) ?  sanitize_text_field($_POST['job_bm_redirect_after_signup']) : '';
                    $job_bm_auto_login_after_signup = isset($_POST['job_bm_auto_login_after_signup']) ?  sanitize_text_field($_POST['job_bm_auto_login_after_signup']) : '';



                    $job_bm_logo_url = isset($_POST['job_bm_logo_url']) ?  sanitize_text_field($_POST['job_bm_logo_url']) : '';
                    $job_bm_from_email = isset($_POST['job_bm_from_email']) ?  sanitize_text_field($_POST['job_bm_from_email']) : '';
                    $job_bm_featured_bg_color = isset($_POST['job_bm_featured_bg_color']) ?  sanitize_text_field($_POST['job_bm_featured_bg_color']) : '';
                    $job_bm_job_type_bg_color = isset($_POST['job_bm_job_type_bg_color']) ?  job_bm_recursive_sanitize_arr($_POST['job_bm_job_type_bg_color']) : array();
                    $job_bm_job_type_text_color = isset($_POST['job_bm_job_type_text_color']) ?  job_bm_recursive_sanitize_arr($_POST['job_bm_job_type_text_color']) : array();
                    $job_bm_job_status_bg_color = isset($_POST['job_bm_job_status_bg_color']) ?  job_bm_recursive_sanitize_arr($_POST['job_bm_job_status_bg_color']) : array();
                    $job_bm_job_status_text_color = isset($_POST['job_bm_job_status_text_color']) ?  job_bm_recursive_sanitize_arr($_POST['job_bm_job_status_text_color']) : array();
                    $job_bm_email_templates_data = isset($_POST['job_bm_email_templates_data']) ?  job_bm_recursive_sanitize_arr($_POST['job_bm_email_templates_data']) : array();





                    update_option('job_bm_list_per_page', $job_bm_list_per_page);
                    update_option('job_bm_pagination_bg_color', $job_bm_pagination_bg_color);
                    update_option('job_bm_pagination_active_bg_color', $job_bm_pagination_active_bg_color);
                    update_option('job_bm_pagination_text_color', $job_bm_pagination_text_color);
                    update_option('job_bm_salary_currency', $job_bm_salary_currency);
                    update_option('job_bm_can_user_delete_jobs', $job_bm_can_user_delete_jobs);
                    update_option('job_bm_can_user_delete_application', $job_bm_can_user_delete_application);
                    update_option('job_bm_can_user_edit_published_jobs', $job_bm_can_user_edit_published_jobs);
                    update_option('job_bm_job_login_page_id', $job_bm_job_login_page_id);
                    update_option('job_bm_archive_page_id', $job_bm_archive_page_id);
                    update_option('job_bm_job_submit_page_id', $job_bm_job_submit_page_id);
                    update_option('job_bm_job_edit_page_id', $job_bm_job_edit_page_id);
                    update_option('job_bm_registration_enable', $job_bm_registration_enable);
                    update_option('job_bm_registration_recaptcha', $job_bm_registration_recaptcha);

                    update_option('job_bm_login_enable', $job_bm_login_enable);
                    update_option('job_bm_account_required_post_job', $job_bm_account_required_post_job);
                    update_option('job_bm_reCAPTCHA_enable', $job_bm_reCAPTCHA_enable);
                    update_option('job_bm_reCAPTCHA_site_key', $job_bm_reCAPTCHA_site_key);
                    update_option('job_bm_reCAPTCHA_secret_key', $job_bm_reCAPTCHA_secret_key);
                    update_option('job_bm_submitted_job_status', $job_bm_submitted_job_status);
                    update_option('job_bm_application_methods', $job_bm_application_methods);
                    update_option('job_bm_apply_enable_recaptcha', $job_bm_apply_enable_recaptcha);
                    update_option('job_bm_login_required_on_apply', $job_bm_login_required_on_apply);
                    update_option('job_bm_redirect_preview_link', $job_bm_redirect_preview_link);
                    update_option('job_bm_edited_job_status', $job_bm_edited_job_status);
                    update_option('job_bm_edited_redirect_link', $job_bm_edited_redirect_link);
                    update_option('job_bm_job_edit_notify_email', $job_bm_job_edit_notify_email);
                    update_option('job_bm_redirect_login', $job_bm_redirect_login);
                    update_option('job_bm_redirect_logout', $job_bm_redirect_logout);

                    update_option('job_bm_redirect_after_signup', $job_bm_redirect_after_signup);
                    update_option('job_bm_auto_login_after_signup', $job_bm_auto_login_after_signup);


                    update_option('job_bm_logo_url', $job_bm_logo_url);
                    update_option('job_bm_from_email', $job_bm_from_email);
                    update_option('job_bm_featured_bg_color', $job_bm_featured_bg_color);
                    update_option('job_bm_job_type_bg_color', $job_bm_job_type_bg_color);
                    update_option('job_bm_job_type_text_color', $job_bm_job_type_text_color);
                    update_option('job_bm_job_status_bg_color', $job_bm_job_status_bg_color);
                    update_option('job_bm_job_status_text_color', $job_bm_job_status_text_color);
                    update_option('job_bm_email_templates_data', $job_bm_email_templates_data);


                    $job_bm_enable_expiry = isset($_POST['job_bm_enable_expiry']) ?  sanitize_text_field($_POST['job_bm_enable_expiry']) : '';
                    update_option('job_bm_enable_expiry', $job_bm_enable_expiry);

                    $job_bm_experied_jobs_post_status = isset($_POST['job_bm_experied_jobs_post_status']) ?  sanitize_text_field($_POST['job_bm_experied_jobs_post_status']) : '';
                    update_option('job_bm_experied_jobs_post_status', $job_bm_experied_jobs_post_status);

                    $job_bm_experied_check_recurrance = isset($_POST['job_bm_experied_check_recurrance']) ?  sanitize_text_field($_POST['job_bm_experied_check_recurrance']) : '';
                    update_option('job_bm_experied_check_recurrance', $job_bm_experied_check_recurrance);

                    $job_bm_job_expiry_days = isset($_POST['job_bm_job_expiry_days']) ?  sanitize_text_field($_POST['job_bm_job_expiry_days']) : '';
                    update_option('job_bm_job_expiry_days', $job_bm_job_expiry_days);

                    $job_bm_restrict_media_file = isset($_POST['job_bm_restrict_media_file']) ?  sanitize_text_field($_POST['job_bm_restrict_media_file']) : '';
                    update_option('job_bm_restrict_media_file', $job_bm_restrict_media_file);

                    $job_bm_job_submit_create_account = isset($_POST['job_bm_job_submit_create_account']) ?  sanitize_text_field($_POST['job_bm_job_submit_create_account']) : '';
                    update_option('job_bm_job_submit_create_account', $job_bm_job_submit_create_account);

                    $job_bm_job_submit_generate_username = isset($_POST['job_bm_job_submit_generate_username']) ?  sanitize_text_field($_POST['job_bm_job_submit_generate_username']) : '';
                    update_option('job_bm_job_submit_generate_username', $job_bm_job_submit_generate_username);



                    do_action('job_bm_settings_save');

                    ?>
                    <div class="updated notice  is-dismissible"><p><strong><?php _e('Changes Saved.', 'job-board-manager' ); ?></strong></p></div>

                    <?php
                }
            }
            ?>
            <div class="settings-tabs vertical has-right-panel">

                <ul class="tab-navs">
                    <?php
                    foreach ($job_bm_settings_tab as $tab){
                        $id = $tab['id'];
                        $title = $tab['title'];
                        $active = $tab['active'];
                        $data_visible = isset($tab['data_visible']) ? $tab['data_visible'] : '';
                        $hidden = isset($tab['hidden']) ? $tab['hidden'] : false;
                        ?>
                        <li <?php if(!empty($data_visible)):  ?> data_visible="<?php echo $data_visible; ?>" <?php endif; ?> class="tab-nav <?php if($hidden) echo 'hidden';?> <?php if($active) echo 'active';?>" data-id="<?php echo $id; ?>"><?php echo $title; ?></li>
                        <?php
                    }
                    ?>
                </ul>

                <div class="settings-tabs-right-panel">
                    <?php
                    foreach ($job_bm_settings_tab as $tab) {
                        $id = $tab['id'];
                        $active = $tab['active'];

                        ?>
                        <div class="right-panel-content <?php if($active) echo 'active';?> right-panel-content-<?php echo $id; ?>">
                            <?php

                            do_action('job_bm_settings_tabs_right_panel_'.$id);
                            ?>

                        </div>
                        <?php

                    }
                    ?>
                </div>

                <?php
                foreach ($job_bm_settings_tab as $tab){
                    $id = $tab['id'];
                    $title = $tab['title'];
                    $active = $tab['active'];
                    ?>

                    <div class="tab-content <?php if($active) echo 'active';?>" id="<?php echo $id; ?>">
                        <?php
                        do_action('job_bm_settings_tabs_content_'.$id, $tab);
                        ?>


                    </div>

                    <?php
                }
                ?>

            </div>

            <div class="clear clearfix"></div>
            <p class="submit">
                <?php wp_nonce_field( 'job_bm_nonce' ); ?>
                <input class="button button-primary" type="submit" name="Submit" value="<?php _e('Save Changes','job-board-manager' ); ?>" />
            </p>
		</form>
</div>
