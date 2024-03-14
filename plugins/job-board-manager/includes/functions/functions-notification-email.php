<?php
if ( ! defined('ABSPATH')) exit;  // if direct access


// Email notifications


/*
 * Send notification for application submitted.
 * args:
 * $application_id => application post ID
 * $post_data => $_POST data
 * */

function job_bm_application_submitted_send_email($application_id, $post_data){



    $email_data = array();
    $class_job_bm_emails = new class_job_bm_emails();
    $job_bm_email_templates_data_default = $class_job_bm_emails->job_bm_email_templates_data();
    $job_bm_email_templates_data = get_option('job_bm_email_templates_data', $job_bm_email_templates_data_default);


    $enable = isset($job_bm_email_templates_data['application_submitted']['enable']) ? $job_bm_email_templates_data['application_submitted']['enable'] : 'no';


    if($enable == 'yes'):

        global $current_user;

        // Site information
        $admin_email = get_option('admin_email');
        $site_name = get_bloginfo('name');
        $site_description = get_bloginfo('description');
        $site_url = get_bloginfo('url');
        $job_bm_logo_url = get_option('job_bm_logo_url');
        $job_bm_logo_url = wp_get_attachment_url($job_bm_logo_url);
        $job_bm_from_email = get_option('job_bm_from_email', $admin_email);

        $email_to = isset($job_bm_email_templates_data['application_submitted']['email_to']) ? $job_bm_email_templates_data['application_submitted']['email_to'] : '';
        $email_from_name = isset($job_bm_email_templates_data['application_submitted']['email_from_name']) ? $job_bm_email_templates_data['application_submitted']['email_from_name'] : $site_name;
        $email_from = isset($job_bm_email_templates_data['application_submitted']['email_from']) ? $job_bm_email_templates_data['application_submitted']['email_from'] : $job_bm_from_email;
        $email_subject = isset($job_bm_email_templates_data['application_submitted']['subject']) ? $job_bm_email_templates_data['application_submitted']['subject'] : '';
        $email_html = isset($job_bm_email_templates_data['application_submitted']['html']) ? $job_bm_email_templates_data['application_submitted']['html'] : '';




        $applicant_name = isset($post_data['applicant_name']) ? sanitize_text_field($post_data['applicant_name']) : '';
        $application_email = isset($post_data['application_email']) ? sanitize_email($post_data['application_email']) : '';
        $application_message = isset($post_data['application_message']) ? wp_kses_post($post_data['application_message']) : '';
        $job_id = isset($post_data['job_id']) ? sanitize_text_field($post_data['job_id']) : '';





        $job_data = get_post($job_id);
        $job_author_id = isset($job_data->author_id) ? $job_data->author_id : '';
        $job_post_title = isset($job_data->post_title) ? $job_data->post_title : '';
        $job_post_content = isset($job_data->post_content) ? $job_data->post_content : '';
        $job_author_id = isset($job_data->author_id) ? $job_data->author_id : '';
        $job_author_avatar = get_avatar( $current_user->ID, 60 );
        $job_url = get_permalink($job_id);
        $job_edit_url = esc_url_raw(get_admin_url().'post.php?post='.$job_id.'&action=edit');


        $job_author_data = get_user_by('ID', $job_id);
        $job_author_name = isset($job_author_data->display_name) ? $job_author_data->display_name : __('Anonymous','');


        $job_bm_contact_email = get_post_meta($job_id, 'job_bm_contact_email', true);
        $job_author_email = isset($job_author_data->user_email) ? $job_author_data->user_email : '';
        $job_author_email = !empty($job_bm_contact_email) ? $job_bm_contact_email : $job_author_email;


        $application_data = get_post($application_id);
        $application_post_title = isset($application_data->post_title) ? $application_data->post_title : '';
        $application_author_avatar = get_avatar( $application_data->author_id, 60 );
        $application_url = get_permalink($application_id);
        $application_edit_url = esc_url_raw(get_admin_url().'post.php?post='.$application_id.'&action=edit');


        $application_author_data = get_user_by('ID', $application_data->post_author);
        $application_author_id = isset($application_data->author_id) ? $application_data->author_id : '';

        $application_author_email = get_post_meta($application_id,'job_bm_am_user_email', true);
        $application_author_email = !empty($application_email) ? $application_email : $application_author_email;

        $application_author_name = isset($application_author_data->display_name) ? $application_author_data->display_name : __('Anonymous','');
        $application_author_name = !empty($applicant_name) ? $applicant_name : $application_author_name;



        $vars = array(
            '{site_name}'=> $site_name,
            '{site_description}' => $site_description,
            '{site_url}' => $site_url,
            '{site_logo_url}' => $job_bm_logo_url,

            '{application_id}'  => $application_id,
            '{application_title}'  => $application_post_title,
            '{application_url}'  => $application_url,
            '{application_edit_url}'  => $application_edit_url,
            '{application_author_id}'  => $application_author_id,
            '{application_author_name}'  => $application_author_name,
            '{application_author_avatar}'  => $application_author_avatar,
            '{application_message}'  => $application_message,

            '{job_id}'  => $job_id,
            '{job_title}'  => $job_post_title,
            '{job_content}'  => $job_post_content,
            '{job_url}'  => $job_url,
            '{job_edit_url}'  => $job_edit_url,
            '{job_author_id}'  => $job_author_id,
            '{job_author_name}'  => $job_author_name,
            '{job_author_avatar}'  => $job_author_avatar,

            '{current_user_id}'  => $current_user->ID,
            '{current_user_name}'  => $application_author_name,
            '{current_user_avatar}'  => get_avatar( $current_user->ID, 60 ),
        );


        $email_data['email_to'] =  $application_author_email;
        $email_data['email_bcc'] =  $email_to;
        $email_data['email_from'] = $email_from ;
        $email_data['email_from_name'] = $email_from_name;
        $email_data['subject'] = strtr($email_subject, $vars);
        $email_data['html'] = strtr($email_html, $vars);
        $email_data['attachments'] = array();


        $status = $class_job_bm_emails->job_bm_send_email($email_data);

        $email_data['email_to'] =  $job_author_email;
        $email_data['email_bcc'] =  $email_to;
        $email_data['email_from'] = $email_from ;
        $email_data['email_from_name'] = $email_from_name;
        $email_data['subject'] = strtr($email_subject, $vars);
        $email_data['html'] = strtr($email_html, $vars);
        $email_data['attachments'] = array();


        $status = $class_job_bm_emails->job_bm_send_email($email_data);



    endif;


}

add_action('job_bm_application_submitted', 'job_bm_application_submitted_send_email', 99, 2);





/*
 * Send notification mail on hire or not hire application.
 * args:
 * $application_id => application post ID
 * $status => hire status
 * */

function job_bm_application_hire_send_email($status, $application_id){

    global $current_user;
    $admin_email = get_option('admin_email');
    $site_name = get_bloginfo('name');
    $site_description = get_bloginfo('description');
    $site_url = get_bloginfo('url');
    $job_bm_logo_url = get_option('job_bm_logo_url');
    $job_bm_logo_url = wp_get_attachment_url($job_bm_logo_url);
    $job_bm_from_email = get_option('job_bm_from_email', $admin_email);

    $application_data = get_post($application_id);

    $application_author_email = get_post_meta($application_id, 'job_bm_am_user_email', true);
    $job_id = get_post_meta($application_id, 'job_bm_am_job_id', true);
    $job_data = get_post($job_id);

    $email_data = array();
    $class_job_bm_emails = new class_job_bm_emails();
    $job_bm_email_templates_data_default = $class_job_bm_emails->job_bm_email_templates_data();
    $job_bm_email_templates_data = get_option('job_bm_email_templates_data', $job_bm_email_templates_data_default);


    $enable = isset($job_bm_email_templates_data['application_hire']['enable']) ? $job_bm_email_templates_data['application_hire']['enable'] : 'no';


    if($enable == 'yes'):

        $email_to = isset($job_bm_email_templates_data['application_hire']['email_to']) ? $job_bm_email_templates_data['application_hire']['email_to'] : '';
        $email_from_name = isset($job_bm_email_templates_data['application_hire']['email_from_name']) ? $job_bm_email_templates_data['application_hire']['email_from_name'] : $site_name;
        $email_from = isset($job_bm_email_templates_data['application_hire']['email_from']) ? $job_bm_email_templates_data['application_hire']['email_from'] : $job_bm_from_email;
        $email_subject = isset($job_bm_email_templates_data['application_hire']['subject']) ? $job_bm_email_templates_data['application_hire']['subject'] : '';
        $email_html = isset($job_bm_email_templates_data['application_hire']['html']) ? $job_bm_email_templates_data['application_hire']['html'] : '';

        $application_data = get_post($application_id);
        $application_author_data = get_user_by('ID', $application_data->post_author);
        $application_author_email = get_post_meta($application_id, 'job_bm_am_user_email', true);



        $vars = array(
            '{site_name}'=> $site_name,
            '{site_description}' => $site_description,
            '{site_url}' => $site_url,
            '{site_logo_url}' => $job_bm_logo_url,

            '{application_id}'  => $application_id,
            '{application_title}'  => $application_data->post_title,
            '{application_url}'  => get_permalink($application_id),
            '{application_edit_url}'  => get_admin_url().'post.php?post='.$application_id.'&action=edit',
            '{application_author_id}'  => $application_data->author_id,
            '{application_author_name}'  => $application_author_data->display_name,
            '{application_author_avatar}'  => get_avatar( $application_data->author_id, 60 ),

            '{job_id}'  => $job_id,
            '{job_title}'  => $job_data->post_title,
            '{job_content}'  => $job_data->post_content,
            '{job_url}'  => get_permalink($job_id),
            '{job_edit_url}'  => get_admin_url().'post.php?post='.$job_id.'&action=edit',
            '{job_author_id}'  => $job_id,
            '{job_author_name}'  => $job_id,
            '{job_author_avatar}'  => get_avatar( $job_data->author_id, 60 ),

            '{current_user_id}'  => $current_user->ID,
            '{current_user_name}'  => $current_user->display_name,
            '{current_user_avatar}'  => get_avatar( $current_user->ID, 60 ),
        );


        $email_data['email_to'] =  $application_author_email;
        $email_data['email_bcc'] =  $email_to;
        $email_data['email_from'] = $email_from ;
        $email_data['email_from_name'] = $email_from_name;
        $email_data['subject'] = strtr($email_subject, $vars);
        $email_data['html'] = strtr($email_html, $vars);
        $email_data['attachments'] = array();


        $status = $class_job_bm_emails->job_bm_send_email($email_data);

    endif;


}

add_action('job_bm_application_hire', 'job_bm_application_hire_send_email', 99, 2);






/*
 * Send notification mail on post comment application.
 * args:
 * $comment_id => comment ID
 * */

function job_bm_application_post_comment_send_email($comment_id){

    global $current_user;
    $admin_email = get_option('admin_email');
    $site_name = get_bloginfo('name');
    $site_description = get_bloginfo('description');
    $site_url = get_bloginfo('url');
    $job_bm_logo_url = get_option('job_bm_logo_url');
    $job_bm_logo_url = wp_get_attachment_url($job_bm_logo_url);
    $job_bm_from_email = get_option('job_bm_from_email', $admin_email);

    $comment_data = get_comment($comment_id);
    $application_id = $comment_data->comment_post_ID;
    $comment_author_email  = $comment_data->comment_author_email ;

    $job_id = get_post_meta($application_id, 'job_bm_am_job_id', true);
    $job_contact_email = get_post_meta($job_id, 'job_bm_contact_email', true);

    $email_data = array();
    $class_job_bm_emails = new class_job_bm_emails();
    $job_bm_email_templates_data_default = $class_job_bm_emails->job_bm_email_templates_data();
    $job_bm_email_templates_data = get_option('job_bm_email_templates_data', $job_bm_email_templates_data_default);


    $enable = isset($job_bm_email_templates_data['application_new_comment']['enable']) ? $job_bm_email_templates_data['application_new_comment']['enable'] : 'no';


    if($enable == 'yes'):

        $email_to = isset($job_bm_email_templates_data['application_new_comment']['email_to']) ? $job_bm_email_templates_data['application_new_comment']['email_to'] : '';
        $email_from_name = isset($job_bm_email_templates_data['application_new_comment']['email_from_name']) ? $job_bm_email_templates_data['application_new_comment']['email_from_name'] : $site_name;
        $email_from = isset($job_bm_email_templates_data['application_new_comment']['email_from']) ? $job_bm_email_templates_data['application_new_comment']['email_from'] : $job_bm_from_email;
        $email_subject = isset($job_bm_email_templates_data['application_new_comment']['subject']) ? $job_bm_email_templates_data['application_new_comment']['subject'] : '';
        $email_html = isset($job_bm_email_templates_data['application_new_comment']['html']) ? $job_bm_email_templates_data['application_new_comment']['html'] : '';

        $application_data = get_post($application_id);
        $application_author_data = get_user_by('ID', $application_data->post_author);
        $application_author_email = get_post_meta($application_id, 'job_bm_am_user_email', true);



        $vars = array(
            '{site_name}'=> $site_name,
            '{site_description}' => $site_description,
            '{site_url}' => $site_url,
            '{site_logo_url}' => $job_bm_logo_url,

            '{application_id}'  => $application_id,
            '{application_title}'  => $application_data->post_title,
            '{application_url}'  => get_permalink($application_id),
            '{application_edit_url}'  => get_admin_url().'post.php?post='.$application_id.'&action=edit',
            '{application_author_id}'  => $application_data->author_id,
            '{application_author_name}'  => $application_author_data->display_name,
            '{application_author_avatar}'  => get_avatar( $application_data->author_id, 60 ),

            '{comment_id}'  => $comment_id,
            '{comment_author_email}'  => $comment_author_email,
            '{comment_content}'  => $comment_data->comment_content ,

            '{current_user_id}'  => $current_user->ID,
            '{current_user_name}'  => $current_user->display_name,
            '{current_user_avatar}'  => get_avatar( $current_user->ID, 60 ),
        );


        $email_data['email_to'] =  $application_author_email;
        $email_data['email_bcc'] =  $email_to;
        $email_data['email_from'] = $email_from ;
        $email_data['email_from_name'] = $email_from_name;
        $email_data['subject'] = strtr($email_subject, $vars);
        $email_data['html'] = strtr($email_html, $vars);
        $email_data['attachments'] = array();


        $status = $class_job_bm_emails->job_bm_send_email($email_data);

    endif;


}

add_action('job_bm_application_post_comment', 'job_bm_application_post_comment_send_email', 99);







/*
 * Send notification mail on rating application.
 * args:
 * $application_id => application post ID
 * $data_count => rating value
 * */

function job_bm_application_rate_send_email($data_count, $application_id){

    global $current_user;
    $admin_email = get_option('admin_email');
    $site_name = get_bloginfo('name');
    $site_description = get_bloginfo('description');
    $site_url = get_bloginfo('url');
    $job_bm_logo_url = get_option('job_bm_logo_url');
    $job_bm_logo_url = wp_get_attachment_url($job_bm_logo_url);
    $job_bm_from_email = get_option('job_bm_from_email', $admin_email);


    $email_data = array();
    $class_job_bm_emails = new class_job_bm_emails();
    $job_bm_email_templates_data_default = $class_job_bm_emails->job_bm_email_templates_data();
    $job_bm_email_templates_data = get_option('job_bm_email_templates_data', $job_bm_email_templates_data_default);


    $enable = isset($job_bm_email_templates_data['application_rate']['enable']) ? $job_bm_email_templates_data['application_rate']['enable'] : 'no';


    if($enable == 'yes'):

        $email_to = isset($job_bm_email_templates_data['application_rate']['email_to']) ? $job_bm_email_templates_data['application_rate']['email_to'] : '';
        $email_from_name = isset($job_bm_email_templates_data['application_rate']['email_from_name']) ? $job_bm_email_templates_data['application_rate']['email_from_name'] : $site_name;
        $email_from = isset($job_bm_email_templates_data['application_rate']['email_from']) ? $job_bm_email_templates_data['application_rate']['email_from'] : $job_bm_from_email;
        $email_subject = isset($job_bm_email_templates_data['application_rate']['subject']) ? $job_bm_email_templates_data['application_rate']['subject'] : '';
        $email_html = isset($job_bm_email_templates_data['application_rate']['html']) ? $job_bm_email_templates_data['application_rate']['html'] : '';

        $application_data = get_post($application_id);
        $application_author_data = get_user_by('ID', $application_data->post_author);
        $application_author_email = get_post_meta($application_id, 'job_bm_am_user_email', true);



        $vars = array(
            '{site_name}'=> $site_name,
            '{site_description}' => $site_description,
            '{site_url}' => $site_url,
            '{site_logo_url}' => $job_bm_logo_url,

            '{application_id}'  => $application_id,
            '{application_title}'  => $application_data->post_title,
            '{application_url}'  => get_permalink($application_id),
            '{application_edit_url}'  => get_admin_url().'post.php?post='.$application_id.'&action=edit',
            '{application_author_id}'  => $application_data->author_id,
            '{application_author_name}'  => $application_author_data->display_name,
            '{application_author_avatar}'  => get_avatar( $application_data->author_id, 60 ),

            '{current_user_id}'  => $current_user->ID,
            '{current_user_name}'  => $current_user->display_name,
            '{current_user_avatar}'  => get_avatar( $current_user->ID, 60 ),
        );


        $email_data['email_to'] =  $application_author_email;
        $email_data['email_bcc'] =  $email_to;
        $email_data['email_from'] = $email_from ;
        $email_data['email_from_name'] = $email_from_name;
        $email_data['subject'] = strtr($email_subject, $vars);
        $email_data['html'] = strtr($email_html, $vars);
        $email_data['attachments'] = array();


        $status = $class_job_bm_emails->job_bm_send_email($email_data);

    endif;


}

add_action('job_bm_application_rate', 'job_bm_application_rate_send_email', 99, 2);










/*
 * Send notification mail on trash application.
 * args:
 * $application_id => application post ID
 * $status => status string
 * */

function job_bm_application_trash_send_email($status, $application_id){

    global $current_user;
    $admin_email = get_option('admin_email');
    $site_name = get_bloginfo('name');
    $site_description = get_bloginfo('description');
    $site_url = get_bloginfo('url');
    $job_bm_logo_url = get_option('job_bm_logo_url');
    $job_bm_logo_url = wp_get_attachment_url($job_bm_logo_url);
    $job_bm_from_email = get_option('job_bm_from_email', $admin_email);


    $email_data = array();
    $class_job_bm_emails = new class_job_bm_emails();
    $job_bm_email_templates_data_default = $class_job_bm_emails->job_bm_email_templates_data();
    $job_bm_email_templates_data = get_option('job_bm_email_templates_data', $job_bm_email_templates_data_default);


    $enable = isset($job_bm_email_templates_data['application_trash']['enable']) ? $job_bm_email_templates_data['application_trash']['enable'] : 'no';


    if($enable == 'yes'):

        $email_to = isset($job_bm_email_templates_data['application_trash']['email_to']) ? $job_bm_email_templates_data['application_trash']['email_to'] : '';
        $email_from_name = isset($job_bm_email_templates_data['application_trash']['email_from_name']) ? $job_bm_email_templates_data['application_trash']['email_from_name'] : $site_name;
        $email_from = isset($job_bm_email_templates_data['application_trash']['email_from']) ? $job_bm_email_templates_data['application_trash']['email_from'] : $job_bm_from_email;
        $email_subject = isset($job_bm_email_templates_data['application_trash']['subject']) ? $job_bm_email_templates_data['application_trash']['subject'] : '';
        $email_html = isset($job_bm_email_templates_data['application_trash']['html']) ? $job_bm_email_templates_data['application_trash']['html'] : '';

        $application_data = get_post($application_id);
        $application_author_data = get_user_by('ID', $application_data->post_author);
        $application_author_email = get_post_meta($application_id, 'job_bm_am_user_email', true);



        $vars = array(
            '{site_name}'=> $site_name,
            '{site_description}' => $site_description,
            '{site_url}' => $site_url,
            '{site_logo_url}' => $job_bm_logo_url,

            '{application_id}'  => $application_id,
            '{application_title}'  => $application_data->post_title,
            '{application_url}'  => get_permalink($application_id),
            '{application_edit_url}'  => get_admin_url().'post.php?post='.$application_id.'&action=edit',
            '{application_author_id}'  => $application_data->author_id,
            '{application_author_name}'  => $application_author_data->display_name,
            '{application_author_avatar}'  => get_avatar( $application_data->author_id, 60 ),

            '{current_user_id}'  => $current_user->ID,
            '{current_user_name}'  => $current_user->display_name,
            '{current_user_avatar}'  => get_avatar( $current_user->ID, 60 ),
        );


        $email_data['email_to'] =  $application_author_email;
        $email_data['email_bcc'] =  $email_to;
        $email_data['email_from'] = $email_from ;
        $email_data['email_from_name'] = $email_from_name;
        $email_data['subject'] = strtr($email_subject, $vars);
        $email_data['html'] = strtr($email_html, $vars);
        $email_data['attachments'] = array();


        $status = $class_job_bm_emails->job_bm_send_email($email_data);

    endif;


}

add_action('job_bm_application_trash', 'job_bm_application_trash_send_email', 99, 2);













/*
 * Send notification mail on featured job.
 * args:
 * $job_id => featured post ID
 * $post_data => Form data $_POST variable
 * */

function job_bm_job_edited_send_email($job_id, $post_data){

    global $current_user;
    $admin_email = get_option('admin_email');
    $site_name = get_bloginfo('name');
    $site_description = get_bloginfo('description');
    $site_url = get_bloginfo('url');
    $job_bm_logo_url = get_option('job_bm_logo_url');
    $job_bm_logo_url = wp_get_attachment_url($job_bm_logo_url);
    $job_bm_from_email = get_option('job_bm_from_email', $admin_email);


    $email_data = array();
    $class_job_bm_emails = new class_job_bm_emails();
    $job_bm_email_templates_data_default = $class_job_bm_emails->job_bm_email_templates_data();
    $job_bm_email_templates_data = get_option('job_bm_email_templates_data', $job_bm_email_templates_data_default);


    $enable = isset($job_bm_email_templates_data['job_edited']['enable']) ? $job_bm_email_templates_data['job_edited']['enable'] : 'no';


    if($enable == 'yes'):

        $email_to = isset($job_bm_email_templates_data['job_edited']['email_to']) ? $job_bm_email_templates_data['job_edited']['email_to'] : '';
        $email_from_name = isset($job_bm_email_templates_data['job_edited']['email_from_name']) ? $job_bm_email_templates_data['job_edited']['email_from_name'] : $site_name;
        $email_from = isset($job_bm_email_templates_data['job_edited']['email_from']) ? $job_bm_email_templates_data['job_edited']['email_from'] : $job_bm_from_email;
        $email_subject = isset($job_bm_email_templates_data['job_edited']['subject']) ? $job_bm_email_templates_data['job_edited']['subject'] : '';
        $email_html = isset($job_bm_email_templates_data['job_edited']['html']) ? $job_bm_email_templates_data['job_edited']['html'] : '';

        $job_data = get_post($job_id);
        $job_author_data = get_user_by('ID', $job_data->post_author);
        $job_author_email = get_post_meta($job_id, 'job_bm_contact_email', true);



        $vars = array(
            '{site_name}'=> $site_name,
            '{site_description}' => $site_description,
            '{site_url}' => $site_url,
            '{site_logo_url}' => $job_bm_logo_url,

            '{job_id}'  => $job_id,
            '{job_title}'  => $job_data->post_title,
            '{job_content}'  => $job_data->post_content,
            '{job_url}'  => get_permalink($job_id),
            '{job_edit_url}'  => get_admin_url().'post.php?post='.$job_id.'&action=edit',
            '{job_author_id}'  => $job_data->author_id,
            '{job_author_name}'  => $job_author_data->display_name,
            '{job_author_avatar}'  => get_avatar( $job_data->author_id, 60 ),

            '{current_user_id}'  => $current_user->ID,
            '{current_user_name}'  => $current_user->display_name,
            '{current_user_avatar}'  => get_avatar( $current_user->ID, 60 ),
        );


        $email_data['email_to'] =  $job_author_email;
        $email_data['email_bcc'] =  $email_to;
        $email_data['email_from'] = $email_from ;
        $email_data['email_from_name'] = $email_from_name;
        $email_data['subject'] = strtr($email_subject, $vars);
        $email_data['html'] = strtr($email_html, $vars);
        $email_data['attachments'] = array();


        $status = $class_job_bm_emails->job_bm_send_email($email_data);

    endif;


}

add_action('job_bm_job_edited', 'job_bm_job_edited_send_email', 99, 2);



/*
 * Send notification mail on featured job.
 * args:
 * $job_id => featured post ID
 *
 * */

function job_bm_job_featured_send_email($job_id){

    global $current_user;
    $admin_email = get_option('admin_email');
    $site_name = get_bloginfo('name');
    $site_description = get_bloginfo('description');
    $site_url = get_bloginfo('url');
    $job_bm_logo_url = get_option('job_bm_logo_url');
    $job_bm_logo_url = wp_get_attachment_url($job_bm_logo_url);
    $job_bm_from_email = get_option('job_bm_from_email', $admin_email);

    $job_bm_featured = get_post_meta($job_id, 'job_bm_featured', true);

    $email_data = array();
    $class_job_bm_emails = new class_job_bm_emails();
    $job_bm_email_templates_data_default = $class_job_bm_emails->job_bm_email_templates_data();
    $job_bm_email_templates_data = get_option('job_bm_email_templates_data', $job_bm_email_templates_data_default);


    $enable = isset($job_bm_email_templates_data['job_featured']['enable']) ? $job_bm_email_templates_data['job_featured']['enable'] : 'no';


    if($enable == 'yes' && $job_bm_featured == 'yes'):

        $email_to = isset($job_bm_email_templates_data['job_featured']['email_to']) ? $job_bm_email_templates_data['job_featured']['email_to'] : '';
        $email_from_name = isset($job_bm_email_templates_data['job_featured']['email_from_name']) ? $job_bm_email_templates_data['job_featured']['email_from_name'] : $site_name;
        $email_from = isset($job_bm_email_templates_data['job_featured']['email_from']) ? $job_bm_email_templates_data['job_featured']['email_from'] : $job_bm_from_email;
        $email_subject = isset($job_bm_email_templates_data['job_featured']['subject']) ? $job_bm_email_templates_data['job_featured']['subject'] : '';
        $email_html = isset($job_bm_email_templates_data['job_featured']['html']) ? $job_bm_email_templates_data['job_featured']['html'] : '';

        $job_data = get_post($job_id);
        $job_author_data = get_user_by('ID', $job_data->post_author);
        $job_author_email = get_post_meta($job_id, 'job_bm_contact_email', true);




        $vars = array(
            '{site_name}'=> $site_name,
            '{site_description}' => $site_description,
            '{site_url}' => $site_url,
            '{site_logo_url}' => $job_bm_logo_url,

            '{job_id}'  => $job_id,
            '{job_title}'  => $job_data->post_title,
            '{job_content}'  => $job_data->post_content,
            '{job_url}'  => get_permalink($job_id),
            '{job_edit_url}'  => get_admin_url().'post.php?post='.$job_id.'&action=edit',
            '{job_author_id}'  => $job_data->author_id,
            '{job_author_name}'  => $job_author_data->display_name,
            '{job_author_avatar}'  => get_avatar( $job_data->author_id, 60 ),

            '{current_user_id}'  => $current_user->ID,
            '{current_user_name}'  => $current_user->display_name,
            '{current_user_avatar}'  => get_avatar( $current_user->ID, 60 ),
        );


        $email_data['email_to'] =  $job_author_email;
        $email_data['email_bcc'] =  $email_to;
        $email_data['email_from'] = $email_from ;
        $email_data['email_from_name'] = $email_from_name;
        $email_data['subject'] = strtr($email_subject, $vars);
        $email_data['html'] = strtr($email_html, $vars);
        $email_data['attachments'] = array();


        $status = $class_job_bm_emails->job_bm_send_email($email_data);

    endif;


}

add_action('publish_job', 'job_bm_job_featured_send_email', 99);





/*
 * Send notification mail on published job.
 * args:
 * $job_id => published post ID
 *
 * */

function job_bm_job_published_send_email($job_id){

    global $current_user;
    $admin_email = get_option('admin_email');
    $site_name = get_bloginfo('name');
    $site_description = get_bloginfo('description');
    $site_url = get_bloginfo('url');
    $job_bm_logo_url = get_option('job_bm_logo_url');
    $job_bm_logo_url = wp_get_attachment_url($job_bm_logo_url);
    $job_bm_from_email = get_option('job_bm_from_email', $admin_email);


    $email_data = array();
    $class_job_bm_emails = new class_job_bm_emails();
    $job_bm_email_templates_data_default = $class_job_bm_emails->job_bm_email_templates_data();
    $job_bm_email_templates_data = get_option('job_bm_email_templates_data', $job_bm_email_templates_data_default);


    $enable = isset($job_bm_email_templates_data['job_published']['enable']) ? $job_bm_email_templates_data['job_published']['enable'] : 'no';


    if($enable == 'yes'):

        $email_to = isset($job_bm_email_templates_data['job_published']['email_to']) ? $job_bm_email_templates_data['job_published']['email_to'] : '';
        $email_from_name = isset($job_bm_email_templates_data['job_published']['email_from_name']) ? $job_bm_email_templates_data['job_published']['email_from_name'] : $site_name;
        $email_from = isset($job_bm_email_templates_data['job_published']['email_from']) ? $job_bm_email_templates_data['job_published']['email_from'] : $job_bm_from_email;
        $email_subject = isset($job_bm_email_templates_data['job_published']['subject']) ? $job_bm_email_templates_data['job_published']['subject'] : '';
        $email_html = isset($job_bm_email_templates_data['job_published']['html']) ? $job_bm_email_templates_data['job_published']['html'] : '';

        $job_data = get_post($job_id);
        $job_author_data = get_user_by('ID', $job_data->post_author);
        $job_author_email = get_post_meta($job_id, 'job_bm_contact_email', true);



        $vars = array(
            '{site_name}'=> $site_name,
            '{site_description}' => $site_description,
            '{site_url}' => $site_url,
            '{site_logo_url}' => $job_bm_logo_url,

            '{job_id}'  => $job_id,
            '{job_title}'  => $job_data->post_title,
            '{job_content}'  => $job_data->post_content,
            '{job_url}'  => get_permalink($job_id),
            '{job_edit_url}'  => get_admin_url().'post.php?post='.$job_id.'&action=edit',
            '{job_author_id}'  => $job_data->author_id,
            '{job_author_name}'  => isset($job_author_data->display_name) ? $job_author_data->display_name : '',
            '{job_author_avatar}'  => get_avatar( $job_data->author_id, 60 ),

            '{current_user_id}'  => $current_user->ID,
            '{current_user_name}'  => $current_user->display_name,
            '{current_user_avatar}'  => get_avatar( $current_user->ID, 60 ),
        );


        $email_data['email_to'] =  $job_author_email;
        $email_data['email_bcc'] =  $email_to;
        $email_data['email_from'] = $email_from ;
        $email_data['email_from_name'] = $email_from_name;
        $email_data['subject'] = strtr($email_subject, $vars);
        $email_data['html'] = strtr($email_html, $vars);
        $email_data['attachments'] = array();


        $status = $class_job_bm_emails->job_bm_send_email($email_data);

    endif;


}

add_action('publish_job', 'job_bm_job_published_send_email', 99);






/*
 * Send notification mail on submit job.
 * args:
 * $job_id => Submitted post ID
 * $post_data => Form data $_POST variable
 *
 * */

function job_bm_job_submitted_send_email($job_id, $post_data){

    global $current_user;
    $admin_email = get_option('admin_email');
    $site_name = get_bloginfo('name');
    $site_description = get_bloginfo('description');
    $site_url = get_bloginfo('url');
    $job_bm_logo_url = get_option('job_bm_logo_url');
    $job_bm_logo_url = wp_get_attachment_url($job_bm_logo_url);
    $job_bm_from_email = get_option('job_bm_from_email', $admin_email);


    $email_data = array();
    $class_job_bm_emails = new class_job_bm_emails();
    $job_bm_email_templates_data_default = $class_job_bm_emails->job_bm_email_templates_data();
    $job_bm_email_templates_data = get_option('job_bm_email_templates_data', $job_bm_email_templates_data_default);


    $enable = isset($job_bm_email_templates_data['job_submitted']['enable']) ? $job_bm_email_templates_data['job_submitted']['enable'] : 'no';


    if($enable == 'yes'):

        $email_to = isset($job_bm_email_templates_data['job_submitted']['email_to']) ? $job_bm_email_templates_data['job_submitted']['email_to'] : '';
        $email_from_name = isset($job_bm_email_templates_data['job_submitted']['email_from_name']) ? $job_bm_email_templates_data['job_submitted']['email_from_name'] : $site_name;
        $email_from = isset($job_bm_email_templates_data['job_submitted']['email_from']) ? $job_bm_email_templates_data['job_submitted']['email_from'] : $job_bm_from_email;
        $email_subject = isset($job_bm_email_templates_data['job_submitted']['subject']) ? $job_bm_email_templates_data['job_submitted']['subject'] : '';
        $email_html = isset($job_bm_email_templates_data['job_submitted']['html']) ? $job_bm_email_templates_data['job_submitted']['html'] : '';

        $job_data = get_post($job_id);
        $job_author_data = get_user_by('ID', $job_data->post_author);
        $job_author_email = get_post_meta($job_id, 'job_bm_contact_email', true);



        $vars = array(
            '{site_name}'=> $site_name,
            '{site_description}' => $site_description,
            '{site_url}' => $site_url,
            '{site_logo_url}' => $job_bm_logo_url,

            '{job_id}'  => $job_id,
            '{job_title}'  => $job_data->post_title,
            '{job_content}'  => $job_data->post_content,
            '{job_url}'  => get_permalink($job_id),
            '{job_edit_url}'  => get_admin_url().'post.php?post='.$job_id.'&action=edit',
            '{job_author_id}'  => $job_data->author_id,
            '{job_author_name}'  => isset($job_author_data->display_name) ? $job_author_data->display_name : '',
            '{job_author_avatar}'  => get_avatar( $job_data->author_id, 60 ),

            '{current_user_id}'  => $current_user->ID,
            '{current_user_name}'  => $current_user->display_name,
            '{current_user_avatar}'  => get_avatar( $current_user->ID, 60 ),
        );


        $email_data['email_to'] =  $job_author_email;
        $email_data['email_bcc'] =  $email_to;
        $email_data['email_from'] = $email_from ;
        $email_data['email_from_name'] = $email_from_name;
        $email_data['subject'] = strtr($email_subject, $vars);
        $email_data['html'] = strtr($email_html, $vars);
        $email_data['attachments'] = array();


        $status = $class_job_bm_emails->job_bm_send_email($email_data);

    endif;


}

add_action('job_bm_job_submitted', 'job_bm_job_submitted_send_email', 99, 2);



/*
 * Send notification mail on trash job.
 * args:
 * $job_id => trashed post ID
 *
 *
 * */

function job_bm_job_trash_send_email($job_id){

    global $current_user;
    $admin_email = get_option('admin_email');

    $site_name = get_bloginfo('name');
    $site_description = get_bloginfo('description');
    $site_url = get_bloginfo('url');
    $job_bm_logo_url = get_option('job_bm_logo_url');
    $job_bm_logo_url = wp_get_attachment_url($job_bm_logo_url);
    $job_bm_from_email = get_option('job_bm_from_email', $admin_email);



    $email_data = array();
    $class_job_bm_emails = new class_job_bm_emails();
    $job_bm_email_templates_data_default = $class_job_bm_emails->job_bm_email_templates_data();
    $job_bm_email_templates_data = get_option('job_bm_email_templates_data', $job_bm_email_templates_data_default);


    $enable = isset($job_bm_email_templates_data['job_trash']['enable']) ? $job_bm_email_templates_data['job_trash']['enable'] : 'no';

    if($enable == 'yes'):

        $email_to = isset($job_bm_email_templates_data['job_trash']['email_to']) ? $job_bm_email_templates_data['job_trash']['email_to'] : '';
        $email_from_name = isset($job_bm_email_templates_data['job_trash']['email_from_name']) ? $job_bm_email_templates_data['job_trash']['email_from_name'] : $site_name;
        $email_from = isset($job_bm_email_templates_data['job_trash']['email_from']) ? $job_bm_email_templates_data['job_trash']['email_from'] : $job_bm_from_email;
        $email_subject = isset($job_bm_email_templates_data['job_trash']['subject']) ? $job_bm_email_templates_data['job_trash']['subject'] : '';
        $email_html = isset($job_bm_email_templates_data['job_trash']['html']) ? $job_bm_email_templates_data['job_trash']['html'] : '';

        $job_data = get_post($job_id);
        $job_author_data = get_user_by('ID', $job_data->post_author);
        $job_author_email = get_post_meta($job_id, 'job_bm_contact_email', true);



        $vars = array(
            '{site_name}'=> $site_name,
            '{site_description}' => $site_description,
            '{site_url}' => $site_url,
            '{site_logo_url}' => $job_bm_logo_url,

            '{job_id}'  => $job_id,
            '{job_title}'  => $job_data->post_title,
            '{job_content}'  => $job_data->post_content,
            '{job_url}'  => get_permalink($job_id),
            '{job_edit_url}'  => get_admin_url().'post.php?post='.$job_id.'&action=edit',
            '{job_author_id}'  => $job_data->author_id,
            '{job_author_name}'  => $job_author_data->display_name,
            '{job_author_avatar}'  => get_avatar( $job_data->author_id, 60 ),

            '{current_user_id}'  => $current_user->ID,
            '{current_user_name}'  => $current_user->display_name,
            '{current_user_avatar}'  => get_avatar( $current_user->ID, 60 ),

        );


        $email_data['email_to'] =  $job_author_email;
        $email_data['email_bcc'] =  $email_to;
        $email_data['email_from'] = $email_from ;
        $email_data['email_from_name'] = $email_from_name;
        $email_data['subject'] = strtr($email_subject, $vars);
        $email_data['html'] = strtr($email_html, $vars);
        $email_data['attachments'] = array();


        $status = $class_job_bm_emails->job_bm_send_email($email_data);

    endif;


}

add_action('job_bm_job_trash', 'job_bm_job_trash_send_email', 99);












