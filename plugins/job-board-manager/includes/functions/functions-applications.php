<?php
if (!defined('ABSPATH')) exit;  // if direct access



function job_bm_ajax_application_submit()
{
    $response = array();

    $current_user_id = get_current_user_id();
    $form_data = isset($_POST['form_data']) ? job_bm_recursive_sanitize_arr($_POST['form_data']) : '';

    $formdata = array();

    foreach ($form_data as $data) {
        $field_name = isset($data['name']) ? $data['name'] : '';
        $field_value = isset($data['value']) ? $data['value'] : '';

        $formdata[$field_name] = $field_value;
    }

    $application_method = isset($formdata['application_method']) ? sanitize_text_field($formdata['application_method']) : "";


    //error_log(serialize($formdata));

    ob_start();
    do_action('job_bm_application_submit_' . $application_method,  $formdata);

    $html = ob_get_clean();


    $response['html'] = $html;




    echo json_encode($response);

    die();
}

add_action('wp_ajax_job_bm_ajax_application_submit', 'job_bm_ajax_application_submit');
add_action('wp_ajax_nopriv_job_bm_ajax_application_submit', 'job_bm_ajax_application_submit');
























add_action('job_bm_application_submit_direct_email', 'job_bm_application_submit_direct_email');

function job_bm_application_submit_direct_email($formdata)
{

    $job_bm_apply_enable_recaptcha        = get_option('job_bm_apply_enable_recaptcha');
    $job_bm_reCAPTCHA_site_key                = get_option('job_bm_reCAPTCHA_site_key');
    $user_id = get_current_user_id();
    $class_job_bm_applications = new class_job_bm_applications();

    $applicant_name = isset($formdata['applicant_name']) ? sanitize_text_field($formdata['applicant_name']) : "";
    $email = isset($formdata['application_email']) ? sanitize_email($formdata['application_email']) : "";
    $post_content = isset($formdata['application_message']) ? wp_kses_post($formdata['application_message']) : "";
    $application_method = isset($formdata['application_method']) ? sanitize_text_field($formdata['application_method']) : "";
    $job_id = isset($formdata['job_id']) ? sanitize_text_field($formdata['job_id']) : "";



    $error = new WP_Error();

    if (empty($formdata['applicant_name'])) {

        $error->add('applicant_name', __('ERROR: Applicant name is empty.', 'job-board-manager'));
    }

    if (empty($formdata['application_email'])) {

        $error->add('application_email', __('ERROR: Email is empty.', 'job-board-manager'));
    }

    if (!is_email($formdata['application_email'])) {

        $error->add('application_email', __('ERROR: ' . sanitize_text_field($_POST['application_email']) . ' is not valid email address.', 'job-board-manager'));
    }



    if ($job_bm_apply_enable_recaptcha == 'yes' && empty($formdata['g-recaptcha-response'])) {

        $error->add('recaptcha', __('ERROR: reCaptcha test failed', 'job-board-manager'));
    }

    $errors = apply_filters('job_bm_application_submit_errors_' . $application_method, $error, $_POST);

    ob_start();

    if (!$error->has_errors()) {


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


function job_bm_ajax_application_marked_hired()
{

    $current_user_id = get_current_user_id();
    $application_id = isset($_POST['application_id']) ? sanitize_text_field($_POST['application_id']) : '';
    $job_id = (int)get_post_meta($application_id, 'job_bm_am_job_id', true);

    $job_data = get_post($job_id);
    $job_author_id = (int) $job_data->post_author;


    $response = array();

    $application_hired = get_post_meta($application_id, 'application_hired', true);

    if ($current_user_id == $job_author_id) {

        if ($application_hired == 'yes') {
            update_post_meta($application_id, 'application_hired', 'no');
            $response['hired'] = 'no';
            $response['message'] = __('Application not hired', 'job-board-manager');

            do_action('job_bm_application_hire', 'no', $application_id);
        } else {
            update_post_meta($application_id, 'application_hired', 'yes');
            $response['hired'] = 'yes';
            $response['message'] = __('Application hired', 'job-board-manager');

            do_action('job_bm_application_hire', 'yes', $application_id);
        }
    } else {
        $response['hired'] = 'no';
        $response['message'] = __('You are not authorized to do this.', 'job-board-manager');
    }




    echo json_encode($response);

    die();
}

add_action('wp_ajax_job_bm_ajax_application_marked_hired', 'job_bm_ajax_application_marked_hired');
//add_action('wp_ajax_nopriv_job_bm_ajax_delete_job_by_id', 'job_bm_ajax_delete_job_by_id');



function job_bm_ajax_application_marked_trash()
{

    $current_user_id = (int) get_current_user_id();

    $application_id = isset($_POST['application_id']) ? sanitize_text_field($_POST['application_id']) : '';
    $application_data = get_post($application_id);
    $application_author_id = (int) $application_data->post_author;

    $job_id = (int)get_post_meta($application_id, 'job_bm_am_job_id', true);
    $job_data = get_post($job_id);
    $job_author_id = (int) $job_data->post_author;


    $response = array();

    $application_trash = get_post_meta($application_id, 'application_trash', true);


    if (($current_user_id == $application_author_id) || ($current_user_id == $job_author_id)) {

        if ($application_trash == 'yes') {
            delete_post_meta($application_id, 'application_trash');
            $response['trash'] = 'no';
            $response['message'] = __('Application not trashed.', 'job-board-manager');

            do_action('job_bm_application_trash', 'no', $application_id);
        } else {
            update_post_meta($application_id, 'application_trash', 'yes');
            $response['trash'] = 'yes';
            $response['message'] = __('Application trashed.', 'job-board-manager');

            do_action('job_bm_application_trash', 'yes', $application_id);
        }
    } else {
        $response['trash'] = 'no';
        $response['message'] = __('You are not authorized to do this.', 'job-board-manager');
    }



    echo json_encode($response);

    die();
}

add_action('wp_ajax_job_bm_ajax_application_marked_trash', 'job_bm_ajax_application_marked_trash');
//add_action('wp_ajax_nopriv_job_bm_ajax_delete_job_by_id', 'job_bm_ajax_delete_job_by_id');





function job_bm_ajax_application_rate()
{

    $current_user_id = (int) get_current_user_id();

    $application_id = isset($_POST['application_id']) ? sanitize_text_field($_POST['application_id']) : '';
    $application_data = get_post($application_id);
    $application_author_id = (int) $application_data->post_author;

    $job_id = (int)get_post_meta($application_id, 'job_bm_am_job_id', true);
    $job_data = get_post($job_id);
    $job_author_id = (int) $job_data->post_author;



    $data_count = isset($_POST['data_count']) ? (int)sanitize_text_field($_POST['data_count']) : '';

    $response = array();

    if ($current_user_id == $job_author_id) {
        update_post_meta($application_id, 'application_rating', $data_count);
        do_action('job_bm_application_rate', $data_count, $application_id);
        $response['star_rate'] = $data_count;
        $response['message'] = __('Rate successful.', 'job-board-manager');
    } else {
        $response['star_rate'] = 0;

        $response['message'] = __('You cant rate this application.', 'job-board-manager');
    }





    echo json_encode($response);

    die();
}

add_action('wp_ajax_job_bm_ajax_application_rate', 'job_bm_ajax_application_rate');
//add_action('wp_ajax_nopriv_job_bm_ajax_delete_job_by_id', 'job_bm_ajax_delete_job_by_id');
