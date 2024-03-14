<?php
if (!defined('ABSPATH')) exit;  // if direct access

class class_job_bm_emails
{

    public function __construct()
    {
    }





    public function job_bm_send_email($email_data)
    {



        $email_to = isset($email_data['email_to']) ? $email_data['email_to'] : '';
        $email_bcc = isset($email_data['email_bcc']) ? $email_data['email_bcc'] : '';

        $email_from = isset($email_data['email_from']) ? $email_data['email_from'] : get_option('admin_email');
        $email_from_name = isset($email_data['email_from_name']) ? $email_data['email_from_name'] : get_bloginfo('name');
        $subject = isset($email_data['subject']) ? $email_data['subject'] : '';
        $email_body = isset($email_data['html']) ? $email_data['html'] : '';
        $attachments = isset($email_data['attachments']) ? $email_data['attachments'] : '';


        $headers = array();
        $headers[] = "From: " . $email_from_name . " <" . $email_from . ">";
        $headers[] = "MIME-Version: 1.0";
        $headers[] = "Content-Type: text/html; charset=UTF-8";
        if (!empty($email_bcc)) {
            $headers[] = "Bcc: " . $email_bcc;
        }
        $headers = apply_filters('job_bm_mail_headers', $headers);

        $status = wp_mail($email_to, $subject, $email_body, $headers, $attachments);

        return $status;
    }






    public function job_bm_email_templates_data()
    {

        $templates_data_html = array();
        $admin_email = get_option('admin_email');
        $site_name = get_bloginfo('name');


        include(job_bm_plugin_dir . 'templates/emails-templates/application_hire.php');
        include(job_bm_plugin_dir . 'templates/emails-templates/application_new_comment.php');
        include(job_bm_plugin_dir . 'templates/emails-templates/application_not_hire.php');
        include(job_bm_plugin_dir . 'templates/emails-templates/application_rate.php');
        include(job_bm_plugin_dir . 'templates/emails-templates/application_submitted.php');
        include(job_bm_plugin_dir . 'templates/emails-templates/application_trash.php');
        include(job_bm_plugin_dir . 'templates/emails-templates/job_edited.php');
        include(job_bm_plugin_dir . 'templates/emails-templates/job_featured.php');
        include(job_bm_plugin_dir . 'templates/emails-templates/job_published.php');
        include(job_bm_plugin_dir . 'templates/emails-templates/job_submitted.php');
        include(job_bm_plugin_dir . 'templates/emails-templates/job_trash.php');






        $templates_data = array(

            'application_hire' => array(
                'name' => __('Application hire', 'job-board-manager'),
                'description' => __('Notification email for when application hired.', 'job-board-manager'),
                'subject' => __('Your application hired - {site_url}', 'job-board-manager'),
                'html' => $templates_data_html['application_hire'],
                'email_to' => $admin_email,
                'email_from' => $admin_email,
                'email_from_name' => $site_name,
                'enable' => 'yes',
            ),

            'application_new_comment' => array(
                'name' => __('Application new Comment', 'job-board-manager'),
                'description' => __('Notification email for when new comment posted on application.', 'job-board-manager'),
                'subject' => __('Comment on application - {site_url}', 'job-board-manager'),
                'html' => $templates_data_html['application_new_comment'],
                'email_to' => $admin_email,
                'email_from' => $admin_email,
                'email_from_name' => $site_name,
                'enable' => 'yes',
            ),

            'application_not_hire' => array(
                'name' => __('Application hire removed', 'job-board-manager'),
                'description' => __('Notification email for application hire removed.', 'job-board-manager'),
                'subject' => __('Application hire removed - {site_url}', 'job-board-manager'),
                'html' => $templates_data_html['application_not_hire'],
                'email_to' => $admin_email,
                'email_from' => $admin_email,
                'email_from_name' => $site_name,
                'enable' => 'yes',
            ),

            'application_rate' => array(
                'name' => __('Application rated', 'job-board-manager'),
                'description' => __('Notification email for application rated.', 'job-board-manager'),
                'subject' => __('Application rated - {site_url}', 'job-board-manager'),
                'html' => $templates_data_html['application_rate'],
                'email_to' => $admin_email,
                'email_from' => $admin_email,
                'email_from_name' => $site_name,
                'enable' => 'yes',
            ),

            'application_submitted' => array(
                'name' => __('Application submitted', 'job-board-manager'),
                'description' => __('Notification email for application submitted.', 'job-board-manager'),
                'subject' => __('Application submitted - {site_url}', 'job-board-manager'),
                'html' => $templates_data_html['application_submitted'],
                'email_to' => $admin_email,
                'email_from' => $admin_email,
                'email_from_name' => $site_name,
                'enable' => 'yes',
            ),


            'application_trash' => array(
                'name' => __('Application trashed', 'job-board-manager'),
                'description' => __('Notification email for application trashed.', 'job-board-manager'),
                'subject' => __('Application trashed - {site_url}', 'job-board-manager'),
                'html' => $templates_data_html['application_trash'],
                'email_to' => $admin_email,
                'email_from' => $admin_email,
                'email_from_name' => $site_name,
                'enable' => 'yes',
            ),



            'job_edited' => array(
                'name' => __('Job edited', 'job-board-manager'),
                'description' => __('Notification email for admin when user edited job.', 'job-board-manager'),
                'subject' => __('Job Edited - {site_url}', 'job-board-manager'),
                'html' => $templates_data_html['job_edited'],
                'email_to' => $admin_email,
                'email_from' => $admin_email,
                'email_from_name' => $site_name,
                'enable' => 'yes',
            ),


            'job_featured' => array(
                'name' => __('Job featured', 'job-board-manager'),
                'description' => __('Notification email for admin featured a job.', 'job-board-manager'),
                'subject' => __('Job featured - {site_url}', 'job-board-manager'),
                'html' => $templates_data_html['job_featured'],
                'email_to' => $admin_email,
                'email_from' => $admin_email,
                'email_from_name' => $site_name,
                'enable' => 'yes',
            ),

            'job_published' => array(
                'name' => __('Job published', 'job-board-manager'),
                'description' => __('Notification email for admin published a job.', 'job-board-manager'),
                'subject' => __('Job published - {site_url}', 'job-board-manager'),
                'html' => $templates_data_html['job_published'],
                'email_to' => $admin_email,
                'email_from' => $admin_email,
                'email_from_name' => $site_name,
                'enable' => 'yes',
            ),


            'job_submitted' => array(
                'name' => __('Job submitted', 'job-board-manager'),
                'description' => __('Notification email for user submitted job.', 'job-board-manager'),
                'subject' => __('Job Submitted - {site_url}', 'job-board-manager'),
                'html' => $templates_data_html['job_submitted'],
                'email_to' => $admin_email,
                'email_from' => $admin_email,
                'email_from_name' => $site_name,
                'enable' => 'yes',
            ),

            'job_trash' => array(
                'name' => __('Job trashed', 'job-board-manager'),
                'description' => __('Notification email for trash job.', 'job-board-manager'),
                'subject' => __('Job trashed - {site_url}', 'job-board-manager'),
                'html' => $templates_data_html['job_trash'],
                'email_to' => $admin_email,
                'email_from' => $admin_email,
                'email_from_name' => $site_name,
                'enable' => 'yes',
            ),



        );

        $templates_data = apply_filters('job_bm_email_templates_data', $templates_data);

        return $templates_data;
    }



    public function email_templates_parameters()
    {



        $parameters = array(

            'application_hire' => array(
                'parameters' => array(
                    '{site_url}' => __('Website Home URL', 'job-board-manager'),
                    '{site_description}' => __('Website tagline', 'job-board-manager'),
                    '{site_logo_url}' => __('Logo url', 'job-board-manager'),

                    '{application_id}'  => __('Application post ID', 'job-board-manager'),
                    '{application_title}'  => __('Application post title', 'job-board-manager'),
                    '{application_url}'  => __('Application post URL', 'job-board-manager'),
                    '{application_edit_url}'  => __('Application admin post edit URL', 'job-board-manager'),
                    '{application_author_id}'  => __('Application post author ID', 'job-board-manager'),
                    '{application_author_name}'  => __('Application post author name', 'job-board-manager'),

                    '{job_id}'  => __('Job ID', 'job-board-manager'),
                    '{job_title}'  => __('Job Title', 'job-board-manager'),
                    '{job_url}'  => __('Job post URL', 'job-board-manager'),
                    '{job_edit_url}'  => __('Job admin post edit URL', 'job-board-manager'),
                    '{job_author_id}'  => __('Job post author ID', 'job-board-manager'),
                    '{job_author_name}'  => __('Job post author name', 'job-board-manager'),

                    '{current_user_id}'  => __('Logged-in user ID', 'job-board-manager'),
                    '{current_user_name}'  => __('Logged-in user display name', 'job-board-manager'),
                    '{current_user_avatar}'  => __('Logged-in user avatar', 'job-board-manager'),
                ),

            ),

            'application_new_comment' => array(

                'parameters' => array(
                    '{site_url}' => __('Website Home URL', 'job-board-manager'),
                    '{site_description}' => __('Website tagline', 'job-board-manager'),
                    '{site_logo_url}' => __('Logo url', 'job-board-manager'),

                    '{application_id}'  => __('Application post ID', 'job-board-manager'),
                    '{application_title}'  => __('Application post title', 'job-board-manager'),
                    '{application_url}'  => __('Application post URL', 'job-board-manager'),
                    '{application_edit_url}'  => __('Application admin post edit URL', 'job-board-manager'),
                    '{application_author_id}'  => __('Application post author ID', 'job-board-manager'),
                    '{application_author_name}'  => __('Application post author name', 'job-board-manager'),

                    '{comment_id}'  => __('Comment ID', 'job-board-manager'),
                    '{comment_author_email}'  => __('Comment author email', 'job-board-manager'),
                    '{comment_content}'  => __('Comment content', 'job-board-manager'),

                    '{current_user_id}'  => __('Logged-in user ID', 'job-board-manager'),
                    '{current_user_name}'  => __('Logged-in user display name', 'job-board-manager'),
                    '{current_user_avatar}'  => __('Logged-in user avatar', 'job-board-manager'),
                ),
            ),

            'application_not_hire' => array(

                'parameters' => array(
                    '{site_url}' => __('Website Home URL', 'job-board-manager'),
                    '{site_description}' => __('Website tagline', 'job-board-manager'),
                    '{site_logo_url}' => __('Logo url', 'job-board-manager'),

                    '{application_id}'  => __('Application post ID', 'job-board-manager'),
                    '{application_title}'  => __('Application post title', 'job-board-manager'),
                    '{application_url}'  => __('Application post URL', 'job-board-manager'),
                    '{application_edit_url}'  => __('Application admin post edit URL', 'job-board-manager'),
                    '{application_author_id}'  => __('Application post author ID', 'job-board-manager'),
                    '{application_author_name}'  => __('Application post author name', 'job-board-manager'),

                    '{current_user_id}'  => __('Logged-in user ID', 'job-board-manager'),
                    '{current_user_name}'  => __('Logged-in user display name', 'job-board-manager'),
                    '{current_user_avatar}'  => __('Logged-in user avatar', 'job-board-manager'),
                ),
            ),

            'application_rate' => array(

                'parameters' => array(
                    '{site_url}' => __('Website Home URL', 'job-board-manager'),
                    '{site_description}' => __('Website tagline', 'job-board-manager'),
                    '{site_logo_url}' => __('Logo url', 'job-board-manager'),

                    '{application_id}'  => __('Application post ID', 'job-board-manager'),
                    '{application_title}'  => __('Application post title', 'job-board-manager'),
                    '{application_url}'  => __('Application post URL', 'job-board-manager'),
                    '{application_edit_url}'  => __('Application admin post edit URL', 'job-board-manager'),
                    '{application_author_id}'  => __('Application post author ID', 'job-board-manager'),
                    '{application_author_name}'  => __('Application post author name', 'job-board-manager'),

                    '{current_user_id}'  => __('Logged-in user ID', 'job-board-manager'),
                    '{current_user_name}'  => __('Logged-in user display name', 'job-board-manager'),
                    '{current_user_avatar}'  => __('Logged-in user avatar', 'job-board-manager'),
                ),
            ),

            'application_submitted' => array(

                'parameters' => array(
                    '{site_url}' => __('Website Home URL', 'job-board-manager'),
                    '{site_description}' => __('Website tagline', 'job-board-manager'),
                    '{site_logo_url}' => __('Logo url', 'job-board-manager'),

                    '{application_id}'  => __('Application post ID', 'job-board-manager'),
                    '{application_title}'  => __('Application post title', 'job-board-manager'),
                    '{application_url}'  => __('Application post URL', 'job-board-manager'),
                    '{application_edit_url}'  => __('Application admin post edit URL', 'job-board-manager'),
                    '{application_author_id}'  => __('Application post author ID', 'job-board-manager'),
                    '{application_author_name}'  => __('Application post author name', 'job-board-manager'),

                    '{current_user_id}'  => __('Logged-in user ID', 'job-board-manager'),
                    '{current_user_name}'  => __('Logged-in user display name', 'job-board-manager'),
                    '{current_user_avatar}'  => __('Logged-in user avatar', 'job-board-manager'),
                ),
            ),


            'application_trash' => array(

                'parameters' => array(
                    '{site_url}' => __('Website Home URL', 'job-board-manager'),
                    '{site_description}' => __('Website tagline', 'job-board-manager'),
                    '{site_logo_url}' => __('Logo url', 'job-board-manager'),

                    '{application_id}'  => __('Application post ID', 'job-board-manager'),
                    '{application_title}'  => __('Application post title', 'job-board-manager'),
                    '{application_url}'  => __('Application post URL', 'job-board-manager'),
                    '{application_edit_url}'  => __('Application admin post edit URL', 'job-board-manager'),
                    '{application_author_id}'  => __('Application post author ID', 'job-board-manager'),
                    '{application_author_name}'  => __('Application post author name', 'job-board-manager'),

                    '{current_user_id}'  => __('Logged-in user ID', 'job-board-manager'),
                    '{current_user_name}'  => __('Logged-in user display name', 'job-board-manager'),
                    '{current_user_avatar}'  => __('Logged-in user avatar', 'job-board-manager'),
                ),
            ),



            'job_edited' => array(

                'parameters' => array(
                    '{site_url}' => __('Website Home URL', 'job-board-manager'),
                    '{site_description}' => __('Website tagline', 'job-board-manager'),
                    '{site_logo_url}' => __('Logo url', 'job-board-manager'),

                    '{job_id}'  => __('Job ID', 'job-board-manager'),
                    '{job_title}'  => __('Job Title', 'job-board-manager'),
                    '{job_url}'  => __('Job post URL', 'job-board-manager'),
                    '{job_edit_url}'  => __('Job admin post edit URL', 'job-board-manager'),
                    '{job_author_id}'  => __('Job post author ID', 'job-board-manager'),
                    '{job_author_name}'  => __('Job post author name', 'job-board-manager'),

                    '{current_user_id}'  => __('Logged-in user ID', 'job-board-manager'),
                    '{current_user_name}'  => __('Logged-in user display name', 'job-board-manager'),
                    '{current_user_avatar}'  => __('Logged-in user avatar', 'job-board-manager'),
                ),
            ),


            'job_featured' => array(

                'parameters' => array(
                    '{site_url}' => __('Website Home URL', 'job-board-manager'),
                    '{site_description}' => __('Website tagline', 'job-board-manager'),
                    '{site_logo_url}' => __('Logo url', 'job-board-manager'),

                    '{job_id}'  => __('Job ID', 'job-board-manager'),
                    '{job_title}'  => __('Job Title', 'job-board-manager'),
                    '{job_url}'  => __('Job post URL', 'job-board-manager'),
                    '{job_edit_url}'  => __('Job admin post edit URL', 'job-board-manager'),
                    '{job_author_id}'  => __('Job post author ID', 'job-board-manager'),
                    '{job_author_name}'  => __('Job post author name', 'job-board-manager'),

                    '{current_user_id}'  => __('Logged-in user ID', 'job-board-manager'),
                    '{current_user_name}'  => __('Logged-in user display name', 'job-board-manager'),
                    '{current_user_avatar}'  => __('Logged-in user avatar', 'job-board-manager'),
                ),
            ),

            'job_published' => array(

                'parameters' => array(
                    '{site_url}' => __('Website Home URL', 'job-board-manager'),
                    '{site_description}' => __('Website tagline', 'job-board-manager'),
                    '{site_logo_url}' => __('Logo url', 'job-board-manager'),

                    '{job_id}'  => __('Job ID', 'job-board-manager'),
                    '{job_title}'  => __('Job Title', 'job-board-manager'),
                    '{job_url}'  => __('Job post URL', 'job-board-manager'),
                    '{job_edit_url}'  => __('Job admin post edit URL', 'job-board-manager'),
                    '{job_author_id}'  => __('Job post author ID', 'job-board-manager'),
                    '{job_author_name}'  => __('Job post author name', 'job-board-manager'),

                    '{current_user_id}'  => __('Logged-in user ID', 'job-board-manager'),
                    '{current_user_name}'  => __('Logged-in user display name', 'job-board-manager'),
                    '{current_user_avatar}'  => __('Logged-in user avatar', 'job-board-manager'),
                ),
            ),


            'job_submitted' => array(

                'parameters' => array(
                    '{site_url}' => __('Website Home URL', 'job-board-manager'),
                    '{site_description}' => __('Website tagline', 'job-board-manager'),
                    '{site_logo_url}' => __('Logo url', 'job-board-manager'),

                    '{job_id}'  => __('Job ID', 'job-board-manager'),
                    '{job_title}'  => __('Job Title', 'job-board-manager'),
                    '{job_url}'  => __('Job post URL', 'job-board-manager'),
                    '{job_edit_url}'  => __('Job admin post edit URL', 'job-board-manager'),
                    '{job_author_id}'  => __('Job post author ID', 'job-board-manager'),
                    '{job_author_name}'  => __('Job post author name', 'job-board-manager'),

                    '{current_user_id}'  => __('Logged-in user ID', 'job-board-manager'),
                    '{current_user_name}'  => __('Logged-in user display name', 'job-board-manager'),
                    '{current_user_avatar}'  => __('Logged-in user avatar', 'job-board-manager'),
                ),
            ),

            'job_trash' => array(

                'parameters' => array(
                    '{site_url}' => __('Website Home URL', 'job-board-manager'),
                    '{site_description}' => __('Website tagline', 'job-board-manager'),
                    '{site_logo_url}' => __('Logo url', 'job-board-manager'),

                    '{job_id}'  => __('Job ID', 'job-board-manager'),
                    '{job_title}'  => __('Job Title', 'job-board-manager'),
                    '{job_url}'  => __('Job post URL', 'job-board-manager'),
                    '{job_edit_url}'  => __('Job admin post edit URL', 'job-board-manager'),
                    '{job_author_id}'  => __('Job post author ID', 'job-board-manager'),
                    '{job_author_name}'  => __('Job post author name', 'job-board-manager'),

                    '{current_user_id}'  => __('Logged-in user ID', 'job-board-manager'),
                    '{current_user_name}'  => __('Logged-in user display name', 'job-board-manager'),
                    '{current_user_avatar}'  => __('Logged-in user avatar', 'job-board-manager'),
                ),
            ),



        );


        $parameters = apply_filters('job_bm_emails_templates_param', $parameters);


        return $parameters;
    }
}

new class_job_bm_emails();
