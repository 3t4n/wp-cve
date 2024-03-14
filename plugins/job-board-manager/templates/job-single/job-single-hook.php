<?php
if (!defined('ABSPATH')) exit;  // if direct access



function job_bmpost_type_template_job($content)
{

    global $post;

    if (is_singular('job') && $post->post_type == 'job') {

        ob_start();
        include(job_bm_plugin_dir . 'templates/job-single/job-single.php');

        wp_enqueue_style('job_bm_job_single');
        wp_enqueue_style('font-awesome-5');
        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_script('job-bm-applications');
        wp_localize_script('job-bm-applications', 'job_bm_ajax', array('job_bm_ajaxurl' => admin_url('admin-ajax.php')));



        return ob_get_clean();
    } else {
        return $content;
    }
}
add_filter('the_content', 'job_bmpost_type_template_job');

add_filter('the_excerpt', 'job_bmpost_type_template_job');










add_action('job_bm_single_job_main', 'job_bm_single_job_main_preview', 5);
if (!function_exists('job_bm_single_job_main_preview')) {
    function job_bm_single_job_main_preview($job_id)
    {

        $job_bm_job_edit_page_id    = get_option('job_bm_job_edit_page_id');
        $job_bm_job_edit_page_url   = get_permalink($job_bm_job_edit_page_id);

        if (is_preview()) :


?>
            <div class="job-preview-notice">

                <?php
                ob_start();
                ?>
                <?php echo __('This is preview of your job, please do not share link.', 'job-board-manager'); ?>
                <a href="<?php echo $job_bm_job_edit_page_url; ?>?job_id=<?php echo $job_id; ?>" class="edit-link"><?php echo sprintf(__('%s Edit', 'job-board-manager'), '<i class="far fa-edit"></i>') ?></a>
                <?php
                $preview_text = ob_get_clean();
                echo apply_filters('job_bm_single_job_preview_html', $preview_text);


                ?>
            </div>
        <?php




        endif;
    }
}




add_action('job_bm_single_job_main', 'job_bm_single_job_main_meta_start', 10);
if (!function_exists('job_bm_single_job_main_meta_start')) {
    function job_bm_single_job_main_meta_start($job_id)
    {

        $meta_items = array();
        $job_bm_job_data = new job_bm_job_data($job_id);

        $job_location = $job_bm_job_data->get_location();
        $job_is_featured = $job_bm_job_data->is_featured();
        $job_categories = $job_bm_job_data->get_categories('link', ', ');
        $job_publish_date = $job_bm_job_data->get_publish_date();


        if ($job_is_featured == 'yes') :
            ob_start();
        ?>
            <span class=" meta-item featured"><i class="far fa-star"></i> <?php echo __('Featured', 'job-board-manager'); ?></span>
        <?php
            $meta_items['featured'] = ob_get_clean();
        endif;

        if (!empty($job_location)) :
            ob_start();
        ?>
            <span class="job-location meta-item"><i class="fas fa-map-marker-alt"></i> <?php echo $job_location; ?></span>
        <?php
            $meta_items['location'] = ob_get_clean();
        endif;

        ob_start();
        ?>
        <span class="job-post-date meta-item"><i class="far fa-calendar-alt"></i> <?php echo sprintf(__('Posted %s ago', 'job-board-manager'), human_time_diff(get_the_time('U'), current_time('timestamp'))) ?></span>
        <?php
        $meta_items['post_date'] = ob_get_clean();



        if (!empty($job_categories)) :
            ob_start();
        ?>
            <span class="job-category meta-item"><i class="fas fa-code-branch"></i> <?php echo sprintf(__('Posted on %s', 'job-board-manager'), $job_categories) ?></span>
        <?php
            $meta_items['job_category'] = ob_get_clean();
        endif;




        $meta_items = apply_filters('job_bm_single_job_meta', $meta_items);


        ?>
        <div class="job-meta-top">



            <?php

            if (!empty($meta_items)) :
                foreach ($meta_items as $item) :

                    echo $item;

                endforeach;
            endif;

            ?>


        </div>
    <?php


    }
}



add_action('job_bm_single_job_main', 'job_bm_template_single_job_description', 20);

if (!function_exists('job_bm_template_single_job_description')) {
    function job_bm_template_single_job_description($job_id)
    {


        global $post;

    ?>
        <div class="single-job-details">
            <div itemprop="description" class="description"><?php echo wpautop(do_shortcode($post->post_content)); ?></div>
        </div>
    <?php


    }
}





add_action('job_bm_single_job_main', 'job_bm_single_job_main_company', 25);
if (!function_exists('job_bm_single_job_main_company')) {
    function job_bm_single_job_main_company($job_id)
    {


        $job_bm_job_data = new job_bm_job_data($job_id);

        $job_address = $job_bm_job_data->get_address();
        $job_company_name = $job_bm_job_data->get_company_name();
        $job_company_website = $job_bm_job_data->get_company_website();
        $job_company_logo = $job_bm_job_data->get_company_logo();



    ?>
        <div class="job-meta-company">
            <?php
            if (!empty($job_company_name)) :
            ?>
                <h3><?php echo __('About Company', 'job-board-manager'); ?></h3>
            <?php endif; ?>

            <?php if ($job_company_logo) : ?>
                <div class="company-logo">
                    <img src="<?php echo $job_company_logo; ?>">
                </div>
            <?php endif; ?>
            <?php if (!empty($job_company_name)) : ?>
                <div class="company-name"><?php echo $job_company_name; ?></div>
            <?php endif; ?>

            <?php if (!empty($job_address)) : ?>
                <div class="company-address"><i class="fas fa-map-marked-alt"></i> <?php echo $job_address; ?></div>
            <?php endif; ?>


            <?php if (!empty($job_company_website['host'])) : ?>
                <div class="company-website"><i class="fas fa-link"></i> <a href="<?php echo $job_company_website['main_url']; ?>"><?php echo $job_company_website['host']; ?></a> </div>
            <?php endif; ?>


        </div>
        <div class="clear"></div>
        <?php


    }
}






add_action('job_bm_single_job_main', 'job_bm_single_job_main_job_info', 30);
if (!function_exists('job_bm_single_job_main_job_info')) {
    function job_bm_single_job_main_job_info($job_id)
    {

        $meta_items = array();

        $job_bm_job_data = new job_bm_job_data($job_id);
        $job_status = $job_bm_job_data->get_job_status();
        $job_total_vacancies = $job_bm_job_data->get_total_vacancies();
        $job_type = $job_bm_job_data->get_job_type();
        $job_level = $job_bm_job_data->get_job_level();
        $job_years_experience = $job_bm_job_data->get_years_experience();
        $job_salary_html = $job_bm_job_data->get_salary_html();
        $job_expire_in = $job_bm_job_data->get_expire_in();
        $job_publish_date = $job_bm_job_data->get_publish_date('d M Y');


        //var_dump($job_status['status_name']);

        if (!empty($job_status['status_name'])) :
            ob_start();
        ?>
            <span class=" meta-item"><?php echo sprintf(__('%s Status: %s', 'job-board-manager'), '<i class="fas fa-traffic-light"></i>', $job_status['status_name']); ?></span>
        <?php
            $meta_items['job_status'] = ob_get_clean();
        endif;

        if ($job_total_vacancies) :
            ob_start();
        ?>
            <span class=" meta-item"><?php echo sprintf(__('%s No of vacancies: %s', 'job-board-manager'), '<i class="fas fa-user-friends"></i>', $job_total_vacancies) ?></span>
        <?php
            $meta_items['total_vacancies'] = ob_get_clean();
        endif;

        if (!empty($job_type['type'])) :
            ob_start();
        ?>
            <span class=" meta-item"><?php echo sprintf(__('%s Job type: %s', 'job-board-manager'), '<i class="fas fa-swatchbook"></i>', $job_type['type_name']) ?></span>
        <?php
            $meta_items['job_type'] = ob_get_clean();
        endif;

        if (!empty($job_level['level'])) :
            ob_start();
        ?>
            <span class=" meta-item"><?php echo sprintf(__('%s Job level: %s', 'job-board-manager'), '<i class="fas fa-users"></i>', $job_level['level_name']) ?></span>
        <?php
            $meta_items['job_level'] = ob_get_clean();
        endif;

        if ($job_years_experience) :
            ob_start();
        ?>
            <span class=" meta-item"><?php echo sprintf(__('%s Years of experience: %s', 'job-board-manager'), '<i class="fas fa-crosshairs"></i>', $job_years_experience) ?></span>
        <?php
            $meta_items['years_experience'] = ob_get_clean();
        endif;


        if (!empty($job_salary_html)) :
            ob_start();
        ?>
            <span class="meta-item"><?php echo sprintf(__('%s Salary: %s', 'job-board-manager'), '<i class="fas fa-pizza-slice"></i>', $job_salary_html); ?></span>
        <?php
            $meta_items['job_salary'] = ob_get_clean();
        endif;


        if (!empty($job_publish_date)) :
            ob_start();
        ?>
            <span class="meta-item"><?php echo sprintf(__('%s Publish date: %s', 'job-board-manager'), '<i class="far fa-calendar-alt"></i>', $job_publish_date); ?></span>
        <?php
            $meta_items['publish_date'] = ob_get_clean();
        endif;


        if (!empty($job_expire_in)) :
            ob_start();
        ?>
            <span class="meta-item"><?php echo sprintf(__('%s Expire in: %s', 'job-board-manager'), '<i class="far fa-calendar-check"></i>', $job_expire_in); ?></span>
        <?php
            $meta_items['expire_in'] = ob_get_clean();
        endif;





        $meta_items = apply_filters('job_bm_single_job_infos', $meta_items);

        ?>
        <div class="job-meta-info">
            <h3><?php echo __('Job Information', 'job-board-manager'); ?></h3>
            <?php

            if (!empty($meta_items)) :
                foreach ($meta_items as $item) :
                    echo $item;
                endforeach;
            endif;
            ?>
        </div>
    <?php
    }
}





add_action('job_bm_single_job_main', 'job_bm_single_job_main_job_apply', 30);
if (!function_exists('job_bm_single_job_main_job_apply')) {
    function job_bm_single_job_main_job_apply($job_id)
    {

        $class_job_bm_functions = new class_job_bm_functions();
        $apply_method_list = $class_job_bm_functions->apply_method_list();

        $job_bm_job_data = new job_bm_job_data($job_id);
        $job_application_methods = $job_bm_job_data->get_application_methods();

        $job_bm_login_required_on_apply = get_option('job_bm_login_required_on_apply', 'yes');
        $job_bm_job_login_page_id = get_option('job_bm_job_login_page_id');
        $job_bm_job_login_page_url = get_permalink($job_bm_job_login_page_id);

        $application_method_id = isset($_POST['application_method']) ? sanitize_text_field($_POST['application_method']) : '';

    ?>
        <div class="job-apply">
            <h3><?php echo __('Apply for job', 'job-board-manager'); ?></h3>
            <div class="apply-methods">
                <?php
                if (!empty($job_application_methods)) :
                    $active_id = 9999;
                    $i = 0;

                    foreach ($job_application_methods as $method) :
                        if ($method == 'none') {
                ?>
                            <div class=""><?php echo apply_filters('job_bm_application_method_none_text', __('Sorry! 
                            application is not available.')); ?></div>
                        <?php

                            return;
                        }

                        if (($application_method_id == $method)) {
                            $active_id = $i;
                        }

                        $method_name = isset($apply_method_list[$method]) ? $apply_method_list[$method] : '';

                        if (!empty($method_name)) :
                        ?>
                            <div id="<?php echo 'apply-method-' . $method; ?>" class="method-header ">
                                <div class="method-name"><?php echo $method_name; ?></div>
                            </div>
                            <div class="method-form ">
                                <?php
                                if (!is_user_logged_in() && $job_bm_login_required_on_apply == 'yes') {
                                    $login_required = apply_filters('job_bm_application_login_required_text', sprintf(__('Please <a href="%s">login</a> to submit application', 'job-board-manager'), $job_bm_job_login_page_url));
                                    echo $login_required;
                                } else {
                                    do_action('job_bm_application_methods_form_' . $method, $job_id);
                                }
                                ?>
                            </div>
                <?php
                        endif;

                        $i++;
                    endforeach;
                endif;
                ?>
            </div>
        </div>
        <script>
            jQuery(function($) {
                $(".apply-methods").accordion({
                    active: <?php echo $active_id; ?>,
                    collapsible: true,
                    icons: false,
                    heightStyle: 'content',
                });
            });
        </script>
        <div class="clear"></div>
        <?php
    }
}





add_action('job_bm_application_methods_form_direct_email', 'job_bm_application_methods_form_direct_email');

function job_bm_application_methods_form_direct_email($job_id)
{

    $application_method = 'direct_email';

    $job_bm_apply_enable_recaptcha        = get_option('job_bm_apply_enable_recaptcha');
    $job_bm_reCAPTCHA_site_key                = get_option('job_bm_reCAPTCHA_site_key');
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
    } else {
        $user_id = 0;
    }

    global $current_user;

    $class_job_bm_applications = new class_job_bm_applications();

    if (isset($_POST['application_method']) && $_POST['application_method'] == 'direct_email') {

        //var_dump($_POST);


        $error = new WP_Error();

        if (empty($_POST['applicant_name'])) {

            $error->add('applicant_name', __('ERROR: Applicant name is empty.', 'job-board-manager'));
        }

        if (empty($_POST['application_email'])) {

            $error->add('application_email', __('ERROR: Email is empty.', 'job-board-manager'));
        }

        if (!is_email($_POST['application_email'])) {

            $error->add('application_email', __('ERROR: ' . sanitize_text_field($_POST['application_email']) . ' is not valid email address.', 'job-board-manager'));
        }



        if ($job_bm_apply_enable_recaptcha == 'yes' && empty($_POST['g-recaptcha-response'])) {

            $error->add('recaptcha', __('ERROR: reCaptcha test failed', 'job-board-manager'));
        }

        $errors = apply_filters('job_bm_application_submit_errors_' . $application_method, $error, $_POST);


        if (!$error->has_errors()) {

            $applicant_name = isset($_POST['applicant_name']) ? sanitize_text_field($_POST['applicant_name']) : "";
            $email = isset($_POST['application_email']) ? sanitize_email($_POST['application_email']) : "";
            $post_content = isset($_POST['application_message']) ? wp_kses_post($_POST['application_message']) : "";
            $application_method = isset($_POST['application_method']) ? sanitize_text_field($_POST['application_method']) : "";


            $has_applied = $class_job_bm_applications->has_applied($job_id, $email);

            //var_dump($has_applied);


            if (!$has_applied) {
                $application_ID = wp_insert_post(
                    array(
                        'post_title'    => '',
                        'post_content'  => $post_content,
                        'post_status'   => 'publish',
                        'post_type'       => 'application',
                        'post_author'   => $user_id,
                    )
                );

                $update_args = array('ID' => $application_ID, 'post_title' => '#' . $application_ID);

                wp_update_post($update_args);


                update_post_meta($application_ID, 'user_id', $user_id);
                update_post_meta($application_ID, 'applicant_name', $applicant_name);
                update_post_meta($application_ID, 'job_bm_am_user_email', $email);
                update_post_meta($application_ID, 'job_bm_am_job_id', $job_id);
                update_post_meta($application_ID, 'job_bm_am_apply_method', $application_method);


                $application_url = get_permalink($application_ID);

                do_action('job_bm_application_submitted', $application_ID, $_POST);

        ?>
                <div class="success"><?php echo sprintf(__('Your application has sent. see your application here <a href="%s">#%s</a>', 'job-board-manager'), $application_url, $application_ID); ?></div>
            <?php

            } else {
            ?>
                <div class="errors">
                    <div class="job-bm-error"><?php echo __('You already sent an application.', 'job-board-manager'); ?></div>
                </div>

            <?php
            }
        } else {

            $error_messages = $error->get_error_messages();

            ?>
            <div class="errors">
                <?php

                if (!empty($error_messages))
                    foreach ($error_messages as $message) {
                ?>
                    <div class="job-bm-error"><?php echo $message; ?></div>
                <?php
                    }
                ?>
            </div>
    <?php
        }
    }



    $current_user_name = isset($current_user->display_name) ? $current_user->display_name : '';
    $current_user_email = isset($current_user->user_email) ? $current_user->user_email : '';


    $applicant_name = isset($_POST['applicant_name']) ? sanitize_text_field($_POST['applicant_name']) : $current_user_name;
    $email = isset($_POST['application_email']) ? sanitize_email($_POST['application_email']) : $current_user_email;
    $application_message = isset($_POST['application_message']) ? wp_kses_post($_POST['application_message']) : "";


    ?>

    <form method="post" action="#apply-method-direct_email" class="apply-method-form">

        <input type="hidden" name="application_method" value="direct_email">
        <input type="hidden" name="job_id" value="<?php echo esc_attr($job_id); ?>">
        <div class="apply-method-response"></div>
        <div class="form-field-wrap">
            <div class="field-title"><?php echo __('Your name', 'job-board-manager'); ?></div>
            <div class="field-input">
                <input placeholder="" type="text" value="<?php echo esc_attr($applicant_name); ?>" name="applicant_name">
                <p class="field-details"><?php echo __('Write your name', 'job-board-manager'); ?></p>
            </div>
        </div>

        <div class="form-field-wrap">
            <div class="field-title"><?php echo __('Your email', 'job-board-manager'); ?></div>
            <div class="field-input">
                <input placeholder="" type="text" value="<?php echo esc_attr($email); ?>" name="application_email">
                <p class="field-details"><?php echo __('Write your email address', 'job-board-manager'); ?></p>
            </div>
        </div>

        <div class="form-field-wrap">
            <div class="field-title"><?php echo __('Message', 'job-board-manager'); ?></div>
            <div class="field-input">
                <textarea placeholder="" name="application_message"><?php echo esc_attr($application_message);
                                                                    ?></textarea>
                <p class="field-details"><?php echo __('Write your message', 'job-board-manager'); ?></p>
            </div>
        </div>


        <?php
        if ($job_bm_apply_enable_recaptcha == 'yes') :
        ?>
            <div class="form-field-wrap">
                <div class="field-title"></div>
                <div class="field-input">
                    <div class="g-recaptcha" data-sitekey="<?php echo $job_bm_reCAPTCHA_site_key; ?>"></div>

                    <?php wp_enqueue_script('google-recaptcha'); ?>

                    <p class="field-details"><?php _e('Please prove you are human.', 'job-board-manager'); ?></p>

                </div>
            </div>
        <?php
        endif;
        ?>



        <?php wp_nonce_field('job_bm_application_nonce', 'job_bm_application_nonce'); ?>

        <div class="form-field-wrap">
            <div class="field-title"></div>
            <div class="field-input">
                <input placeholder="" type="submit" value="<?php echo __('Submit', 'job-board-manager'); ?>">
                <p class="field-details"></p>
            </div>
        </div>




    </form>
<?php

}




add_action('job_bm_application_methods_form_external_website', 'job_bm_application_methods_form_external_website');

function job_bm_application_methods_form_external_website($job_id)
{

    $application_method = 'external_website';

    $job_bm_apply_enable_recaptcha        = get_option('job_bm_apply_enable_recaptcha');
    $job_bm_reCAPTCHA_site_key                = get_option('job_bm_reCAPTCHA_site_key');
    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
    } else {
        $user_id = 0;
    }

    global $current_user;

    $class_job_bm_applications = new class_job_bm_applications();


    $job_bm_job_data = new job_bm_job_data($job_id);
    $job_link = $job_bm_job_data->get_job_link();

?>
    <form method="post" action="#apply-method-external_website" class="apply-method-form">

        <input type="hidden" name="application_method" value="external_website">

        <div class="form-field-wrap">
            <p>
                <?php

                if (!empty($job_link)) {
                    echo apply_filters('job_bm_application_method_external_website_text', __('Please go following link to apply on their website.', 'job-board-manager'));

                ?>
            <div>
                <?php
                    echo apply_filters('job_bm_application_method_external_website_button', sprintf(__('<a class="external_website_button" href="%s">Click to see job details</a>.', 'job-board-manager'), $job_link));
                ?>
            </div>
        <?php
                } else {
                    echo apply_filters('job_bm_application_method_external_website_no_link', __('Sorry! job link is not available right now. this might be temporary, please check back later.', 'job-board-manager'));
                }

        ?>
        </p>
        </div>

    </form>
    <?php

}




add_action('job_bm_single_job_main', 'job_bm_template_single_job_tags', 90);

if (!function_exists('job_bm_template_single_job_tags')) {
    function job_bm_template_single_job_tags($job_id)
    {


        global $post;
        $tags = get_the_terms($job_id, 'job_tag');

        if (!empty($tags)) :
    ?>
            <div class="single-job-tags">
                <span class="tag-title"><?php echo sprintf(__('%s Job tags:', 'job-board-manager'), '<i class="fas fa-tags"></i>'); ?></span>
                <?php

                $tags_total = count($tags);
                $term_sepa = ', ';
                $tag_count = 1;
                foreach ($tags as $tag) :

                    $term_id = isset($tag->term_id) ? $tag->term_id : '';
                    $term_name = isset($tag->name) ? $tag->name : '';
                    $term_count = isset($tag->count) ? $tag->count : '';

                    $term_url = get_term_link($term_id, 'job_tag');
                ?>
                    <a class="tag-link" href="<?php echo $term_url; ?>"><?php echo $term_name; ?></a>
                    <?php

                    if ($tags_total > $tag_count) {
                    ?>
                        <span class="tag-separator"><?php echo $term_sepa; ?></span>
                <?php
                    }

                    $tag_count++;
                endforeach;
                ?>
            </div>
        <?php
        endif;
    }
}




add_action('job_bm_single_job_main', 'job_bm_single_job_main_job_schema', 30);
if (!function_exists('job_bm_single_job_main_job_schema')) {
    function job_bm_single_job_main_job_schema($job_id)
    {

        $job_bm_job_data = new job_bm_job_data($job_id);
        $job_excerpt = get_the_content($job_id);
        $job_categories = $job_bm_job_data->get_categories('name', ',');

        $job_categories = !empty($job_categories) ? $job_categories : 'General';

        $job_type = $job_bm_job_data->get_job_type();
        $job_location = $job_bm_job_data->get_location();
        $job_address = $job_bm_job_data->get_address();

        $job_publish_date = $job_bm_job_data->get_publish_date();
        $job_expire_date = $job_bm_job_data->get_expire_date();

        $job_years_experience = $job_bm_job_data->get_years_experience();
        $job_company_name = $job_bm_job_data->get_company_name();

        $job_salary_currency = $job_bm_job_data->get_salary_currency();
        $job_salary_type = $job_bm_job_data->get_salary_type();
        $job_salary_minimum = $job_bm_job_data->get_salary_minimum();
        $job_salary_maximum = $job_bm_job_data->get_salary_maximum();
        $job_salary_fixed = $job_bm_job_data->get_salary_fixed();




        $job_title = get_the_title();



        $schemaJobPosting = array();


        $schemaJobPosting['@context'] = '"http://schema.org"';
        $schemaJobPosting['@type'] = '"JobPosting"';

        $schemaJobPosting['datePosted'] = '"' . $job_publish_date . '"';
        $schemaJobPosting['validThrough'] = '"' . $job_expire_date . '"';
        $schemaJobPosting['employmentType'] = '"' . $job_type['type_name'] . '"';
        $schemaJobPosting['experienceRequirements'] = '"' . $job_years_experience . '"';
        $schemaJobPosting['hiringOrganization'] = '"' . $job_company_name . '"';
        $schemaJobPosting['occupationalCategory'] = '"' . $job_categories . '"';
        $schemaJobPosting['salaryCurrency'] = '"' . $job_salary_currency . '"';


        if ($job_salary_type == 'fixed') {

            $schemaJobPosting['baseSalary'] = '{
                        "@type": "Number",
                        "currency": "' . $job_salary_currency . '",
                        "price": "' . $job_salary_fixed . '"

                    }';
        } elseif ($job_salary_type == 'min-max') {

            $schemaJobPosting['baseSalary'] = '{
                        "@type": "MonetaryAmount",
                        "currency": "' . $job_salary_currency . '",
                        "minValue": "' . $job_salary_minimum . '",
                        "maxValue": "' . $job_salary_maximum . '"
                    }';
        } elseif ($job_salary_type == 'negotiable') {

            $schemaJobPosting['baseSalary'] = '{
                        "@type": "PriceSpecification",
                        "currency": "' . $job_salary_currency . '",
                        "price": "' . $job_salary_type . '"

                    }';
        }







        $schemaJobPosting['jobLocation'] = '{
                    "@type": "Place",
                    "address": {
                        "@type": "PostalAddress",
                        "addressLocality": "' . $job_address . '",
                        "streetAddress": "' . $job_address . '",
                        "addressRegion": "' . $job_location . '",
                        "postalCode": "' . $job_location . '"
                    }
                }';

        $schemaJobPosting['title'] = '"' . $job_title . '"';
        $schemaJobPosting['description'] = '"' . wp_strip_all_tags($job_excerpt, true) . '"';


        $schemaJobPosting = apply_filters('job_bm_schema_job_posting', $schemaJobPosting);

        ?>
        <script type="application/ld+json">
            {
                <?php

                $itemCount = count($schemaJobPosting);

                if (!empty($schemaJobPosting)) :

                    $i = 1;
                    foreach ($schemaJobPosting as $itemIndex => $item) :

                        echo '"' . $itemIndex . '": ' . $item . '';
                        if ($i < $itemCount) {
                            echo ',';
                            echo '
                        ';
                        }

                        $i++;
                    endforeach;
                endif;


                ?>



            }
        </script>
    <?php

    }
}








add_action('job_bm_single_job_main', 'job_bm_single_job_main_css', 90);

if (!function_exists('job_bm_single_job_main_css')) {
    function job_bm_single_job_main_css($job_id)
    {

        $job_bm_job_type_bg_color = get_option('job_bm_job_type_bg_color');
        $job_bm_job_status_bg_color = get_option('job_bm_job_status_bg_color');
        $job_bm_featured_bg_color = get_option('job_bm_featured_bg_color');


    ?>
        <style type="text/css">
            .job-single .meta-item.featured {
                background: <?php echo $job_bm_featured_bg_color; ?> !important;
            }
        </style>
<?php

    }
}
