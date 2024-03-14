<?php
if ( ! defined('ABSPATH')) exit;  // if direct access




add_action('job_bm_metabox_job_content_job_info','job_bm_metabox_job_content_job_info');


function job_bm_metabox_job_content_job_info($job_id){


    ?>
    <div class="section">
        <div class="section-title"><?php echo __('Job Information','job-board-manager'); ?></div>
        <p class="section-description"></p>
    </div>


    <?php


}







add_action('job_bm_metabox_job_content_job_info','job_bm_meta_box_job_total_vacancies');

function job_bm_meta_box_job_total_vacancies($job_id){

    $settings_tabs_field = new settings_tabs_field();

    $job_bm_total_vacancies = get_post_meta($job_id, 'job_bm_total_vacancies', true);

    $args = array(
        'id'		=> 'job_bm_total_vacancies',
        //'parent'		=> '',
        'title'		=> __('Total vacancies','job-board-manager'),
        'details'	=> __('Total number of vacancies','job-board-manager'),
        'type'		=> 'text',
        'value'		=> $job_bm_total_vacancies,
        'default'		=> '',
        'placeholder'		=> '5',
    );

    $settings_tabs_field->generate_field($args);

}








add_action('job_bm_metabox_job_content_job_info','job_bm_meta_box_job_type');

function job_bm_meta_box_job_type($job_id){

    $settings_tabs_field = new settings_tabs_field();
    $class_job_bm_functions = new class_job_bm_functions();
    $job_type_list = $class_job_bm_functions->job_type_list();

    $job_bm_job_type = get_post_meta($job_id, 'job_bm_job_type', true);

    $args = array(
        'id'		=> 'job_bm_job_type',
        //'parent'		=> '',
        'title'		=> __('Job type','job-board-manager'),
        'details'	=> __('Select job type.','job-board-manager'),
        'type'		=> 'select',
        'value'		=> $job_bm_job_type,
        'default'		=> '',
        'args'		=> $job_type_list,
    );

    $settings_tabs_field->generate_field($args);

}



add_action('job_bm_metabox_job_content_job_info','job_bm_meta_box_job_level');

function job_bm_meta_box_job_level($job_id){

    $settings_tabs_field = new settings_tabs_field();
    $class_job_bm_functions = new class_job_bm_functions();
    $job_level_list = $class_job_bm_functions->job_level_list();

    $job_bm_job_level = get_post_meta($job_id, 'job_bm_job_level', true);

    $args = array(
        'id'		=> 'job_bm_job_level',
        //'parent'		=> '',
        'title'		=> __('Job level','job-board-manager'),
        'details'	=> __('Select job level.','job-board-manager'),
        'type'		=> 'select',
        'value'		=> $job_bm_job_level,
        'default'		=> '',
        'args'		=> $job_level_list,
    );

    $settings_tabs_field->generate_field($args);

}










add_action('job_bm_metabox_job_content_job_info','job_bm_meta_box_job_years_experience');

function job_bm_meta_box_job_years_experience($job_id){

    $settings_tabs_field = new settings_tabs_field();

    $job_bm_years_experience = get_post_meta($job_id, 'job_bm_years_experience', true);

    $args = array(
        'id'		=> 'job_bm_years_experience',
        //'parent'		=> '',
        'title'		=> __('Years of experience','job-board-manager'),
        'details'	=> __('Years of experience must have.','job-board-manager'),
        'type'		=> 'text',
        'value'		=> $job_bm_years_experience,
        'default'		=> '',
        'placeholder'		=> '5',
    );

    $settings_tabs_field->generate_field($args);

}








add_action('job_bm_metabox_job_content_job_info','job_bm_meta_box_salary_type');

function job_bm_meta_box_salary_type($job_id){

    $settings_tabs_field = new settings_tabs_field();
    $class_job_bm_functions = new class_job_bm_functions();
    $salary_type_list = $class_job_bm_functions->salary_type_list();

    $job_bm_salary_type = get_post_meta($job_id, 'job_bm_salary_type', true);

    $args = array(
        'id'		=> 'job_bm_salary_type',
        //'parent'		=> '',
        'title'		=> __('Salary type','job-board-manager'),
        'details'	=> __('Select salary type.','job-board-manager'),
        'type'		=> 'select',
        'value'		=> $job_bm_salary_type,
        'default'		=> '',
        'args'		=> $salary_type_list,
    );

    $settings_tabs_field->generate_field($args);

    ?>
    <script>
        jQuery(document).ready(function($) {


            <?php
            if($job_bm_salary_type == 'fixed'):
                ?>
                $('#job_bm_salary_fixed').parent().parent().fadeIn();
                $('#job_bm_salary_duration').parent().parent().fadeIn();
                $('#job_bm_salary_currency').parent().parent().fadeIn();
                $('#job_bm_salary_min').parent().parent().fadeOut();
                $('#job_bm_salary_max').parent().parent().fadeOut();
                <?php

            elseif ($job_bm_salary_type == 'min-max'):
                ?>
                $('#job_bm_salary_min').parent().parent().fadeIn();
                $('#job_bm_salary_max').parent().parent().fadeIn();
                $('#job_bm_salary_duration').parent().parent().fadeIn();
                $('#job_bm_salary_currency').parent().parent().fadeIn();
                $('#job_bm_salary_fixed').parent().parent().fadeOut();
                <?php
            else:
                ?>
                $('#job_bm_salary_fixed').parent().parent().fadeOut();
                $('#job_bm_salary_min').parent().parent().fadeOut();
                $('#job_bm_salary_max').parent().parent().fadeOut();
                $('#job_bm_salary_duration').parent().parent().fadeOut();
                $('#job_bm_salary_currency').parent().parent().fadeOut();
                <?php
            endif;
            ?>

            $(document).on('change', '#job_bm_salary_type', function(){
                var salary_type = $(this).val();
                //alert(salary_type);

                if(salary_type=='fixed'){

                    $('#job_bm_salary_fixed').parent().parent().fadeIn();
                    $('#job_bm_salary_duration').parent().parent().fadeIn();
                    $('#job_bm_salary_currency').parent().parent().fadeIn();
                    $('#job_bm_salary_min').parent().parent().fadeOut();
                    $('#job_bm_salary_max').parent().parent().fadeOut();

                }
                else if(salary_type=='min-max'){

                    $('#job_bm_salary_min').parent().parent().fadeIn();
                    $('#job_bm_salary_max').parent().parent().fadeIn();
                    $('#job_bm_salary_duration').parent().parent().fadeIn();
                    $('#job_bm_salary_currency').parent().parent().fadeIn();
                    $('#job_bm_salary_fixed').parent().parent().fadeOut();
                }
                else{
                    $('#job_bm_salary_fixed').parent().parent().fadeOut();
                    $('#job_bm_salary_min').parent().parent().fadeOut();
                    $('#job_bm_salary_max').parent().parent().fadeOut();
                    $('#job_bm_salary_duration').parent().parent().fadeOut();
                    $('#job_bm_salary_currency').parent().parent().fadeOut();
                }

            })
        })
    </script>
    <?php

}






add_action('job_bm_metabox_job_content_job_info','job_bm_meta_box_job_salary_fixed');

function job_bm_meta_box_job_salary_fixed($job_id){

    $settings_tabs_field = new settings_tabs_field();
    $job_bm_salary_fixed = get_post_meta($job_id, 'job_bm_salary_fixed', true);

    $args = array(
        'id'		=> 'job_bm_salary_fixed',
        //'parent'		=> '',
        'title'		=> __('Fixed salary','job-board-manager'),
        'details'	=> __('Salary fixed, ex: 1200','job-board-manager'),
        'type'		=> 'text',
        'value'		=> $job_bm_salary_fixed,
        'default'		=> '',
        'placeholder'		=> '',
    );

    $settings_tabs_field->generate_field($args);

}




add_action('job_bm_metabox_job_content_job_info','job_bm_meta_box_job_salary_min');

function job_bm_meta_box_job_salary_min($job_id){

    $settings_tabs_field = new settings_tabs_field();
    $job_bm_salary_min = get_post_meta($job_id, 'job_bm_salary_min', true);

    $args = array(
        'id'		=> 'job_bm_salary_min',
        //'parent'		=> '',
        'title'		=> __('Minimum salary','job-board-manager'),
        'details'	=> __('Minimum salary amount, ex: 5000','job-board-manager'),
        'type'		=> 'text',
        'value'		=> $job_bm_salary_min,
        'default'		=> '',
        'placeholder'		=> '',
    );

    $settings_tabs_field->generate_field($args);

}




add_action('job_bm_metabox_job_content_job_info','job_bm_meta_box_job_salary_max');

function job_bm_meta_box_job_salary_max($job_id){

    $settings_tabs_field = new settings_tabs_field();
    $job_bm_salary_max = get_post_meta($job_id, 'job_bm_salary_max', true);

    $args = array(
        'id'		=> 'job_bm_salary_max',
        //'parent'		=> '',
        'title'		=> __('Maximum salary','job-board-manager'),
        'details'	=> __('Maximum salary amount, ex: 10000','job-board-manager'),
        'type'		=> 'text',
        'value'		=> $job_bm_salary_max,
        'default'		=> '',
        'placeholder'		=> '',
    );

    $settings_tabs_field->generate_field($args);

}




add_action('job_bm_metabox_job_content_job_info','job_bm_meta_box_salary_duration');

function job_bm_meta_box_salary_duration($job_id){

    $settings_tabs_field = new settings_tabs_field();
    $class_job_bm_functions = new class_job_bm_functions();
    $salary_duration_list = $class_job_bm_functions->salary_duration_list();

    $job_bm_salary_duration = get_post_meta($job_id, 'job_bm_salary_duration', true);

    $args = array(
        'id'		=> 'job_bm_salary_duration',
        //'parent'		=> '',
        'title'		=> __('Salary duration','job-board-manager'),
        'details'	=> __('Select salary duration.','job-board-manager'),
        'type'		=> 'select',
        'value'		=> $job_bm_salary_duration,
        'default'		=> '',
        'args'		=> $salary_duration_list,
    );

    $settings_tabs_field->generate_field($args);


}






add_action('job_bm_metabox_job_content_job_info','job_bm_meta_box_job_salary_currency');

function job_bm_meta_box_job_salary_currency($job_id){

    $settings_tabs_field = new settings_tabs_field();
    $job_bm_salary_currency = get_post_meta($job_id, 'job_bm_salary_currency', true);

    $args = array(
        'id'		=> 'job_bm_salary_currency',
        //'parent'		=> '',
        'title'		=> __('Salary currency','job-board-manager'),
        'details'	=> __('Write salary currency, ex: USD','job-board-manager'),
        'type'		=> 'text',
        'value'		=> $job_bm_salary_currency,
        'default'		=> '',
        'placeholder'		=> 'USD',
    );

    $settings_tabs_field->generate_field($args);

}


add_action('job_bm_metabox_job_content_job_info','job_bm_meta_box_job_contact_email');

function job_bm_meta_box_job_contact_email($job_id){

    $settings_tabs_field = new settings_tabs_field();
    $job_bm_contact_email = get_post_meta($job_id, 'job_bm_contact_email', true);

    $args = array(
        'id'		=> 'job_bm_contact_email',
        //'parent'		=> '',
        'title'		=> __('Contact email','job-board-manager'),
        'details'	=> __('Write your contact email','job-board-manager'),
        'type'		=> 'text',
        'value'		=> $job_bm_contact_email,
        'default'		=> '',
        'placeholder'		=> 'contact@company.com',
    );

    $settings_tabs_field->generate_field($args);

}


add_action('job_bm_metabox_job_content_company_info','job_bm_metabox_job_content_company_info');

function job_bm_metabox_job_content_company_info($job_id){


    ?>
    <div class="section">
        <div class="section-title"><?php echo __('Company Information','job-board-manager'); ?></div>
        <p class="section-description"></p>
    </div>
    <?php

}



add_action('job_bm_metabox_job_content_company_info','job_bm_metabox_job_content_company_info_company_name');

function job_bm_metabox_job_content_company_info_company_name($job_id){

    $settings_tabs_field = new settings_tabs_field();
    $job_bm_company_name = get_post_meta($job_id, 'job_bm_company_name', true);

    $args = array(
        'id'		=> 'job_bm_company_name',
        //'parent'		=> '',
        'title'		=> __('Company name','job-board-manager'),
        'details'	=> __('Write your company name','job-board-manager'),
        'type'		=> 'text',
        'value'		=> $job_bm_company_name,
        'default'		=> '',
        'placeholder'		=> 'Company name',
    );

    $settings_tabs_field->generate_field($args);

}



add_action('job_bm_metabox_job_content_company_info','job_bm_metabox_job_content_company_info_location');

function job_bm_metabox_job_content_company_info_location($job_id){

    $settings_tabs_field = new settings_tabs_field();
    $job_bm_location = get_post_meta($job_id, 'job_bm_location', true);

    $args = array(
        'id'		=> 'job_bm_location',
        //'parent'		=> '',
        'title'		=> __('Location','job-board-manager'),
        'details'	=> __('Write company location, use city or state. ex: New Work','job-board-manager'),
        'type'		=> 'text',
        'value'		=> $job_bm_location,
        'default'		=> '',
        'placeholder'		=> 'New York',
    );

    $settings_tabs_field->generate_field($args);

}



add_action('job_bm_metabox_job_content_company_info','job_bm_metabox_job_content_company_info_address');

function job_bm_metabox_job_content_company_info_address($job_id){

    $settings_tabs_field = new settings_tabs_field();
    $job_bm_address = get_post_meta($job_id, 'job_bm_address', true);

    $args = array(
        'id'		=> 'job_bm_address',
        //'parent'		=> '',
        'title'		=> __('Address','job-board-manager'),
        'details'	=> __('Write company address','job-board-manager'),
        'type'		=> 'text',
        'value'		=> $job_bm_address,
        'default'		=> '',
        'placeholder'		=> '4549 Godfrey Road',
    );

    $settings_tabs_field->generate_field($args);

}


add_action('job_bm_metabox_job_content_company_info','job_bm_metabox_job_content_company_info_website');

function job_bm_metabox_job_content_company_info_website($job_id){

    $settings_tabs_field = new settings_tabs_field();
    $job_bm_company_website = get_post_meta($job_id, 'job_bm_company_website', true);

    $args = array(
        'id'		=> 'job_bm_company_website',
        //'parent'		=> '',
        'title'		=> __('Company website','job-board-manager'),
        'details'	=> __('Write company website','job-board-manager'),
        'type'		=> 'text',
        'value'		=> $job_bm_company_website,
        'default'		=> '',
        'placeholder'		=> 'http://companywebsite.com',
    );

    $settings_tabs_field->generate_field($args);

}


add_action('job_bm_metabox_job_content_company_info','job_bm_metabox_job_content_company_info_job_link');

function job_bm_metabox_job_content_company_info_job_link($job_id){

    $settings_tabs_field = new settings_tabs_field();
    $job_bm_job_link = get_post_meta($job_id, 'job_bm_job_link', true);

    $args = array(
        'id'		=> 'job_bm_job_link',
        //'parent'		=> '',
        'title'		=> __('Job link','job-board-manager'),
        'details'	=> __('Job link at company website','job-board-manager'),
        'type'		=> 'text',
        'value'		=> $job_bm_job_link,
        'default'		=> '',
        'placeholder'		=> 'http://companywebsite.com/job-details',
    );

    $settings_tabs_field->generate_field($args);

}



add_action('job_bm_metabox_job_content_company_info','job_bm_metabox_job_content_company_info_logo');

function job_bm_metabox_job_content_company_info_logo($job_id){

    $settings_tabs_field = new settings_tabs_field();
    $job_bm_company_logo = get_post_meta($job_id, 'job_bm_company_logo', true);


    $job_bm_company_logo = !empty($job_bm_company_logo) ? $job_bm_company_logo : job_bm_plugin_url."assets/front/images/placeholder.png";


    if(is_serialized($job_bm_company_logo)){

        $job_bm_company_logo = unserialize($job_bm_company_logo);
        if(!empty($job_bm_company_logo[0])){
            $job_bm_company_logo = $job_bm_company_logo[0];
            $job_bm_company_logo = wp_get_attachment_url($job_bm_company_logo);

            if($job_bm_company_logo == false){
                $job_bm_company_logo =job_bm_plugin_url."assets/front/images/placeholder.png";

            }

        }
        else{
            $job_bm_company_logo = job_bm_plugin_url."assets/front/images/placeholder.png";
        }
    }


    $args = array(
        'id'		=> 'job_bm_company_logo',
        //'parent'		=> '',
        'title'		=> __('Company logo','job-board-manager'),
        'details'	=> __('Upload company logo','job-board-manager'),
        'type'		=> 'media_url',
        'value'		=> $job_bm_company_logo,
        'default'		=> '',
        'placeholder'		=> '',
    );

    $settings_tabs_field->generate_field($args);

}






add_action('job_bm_metabox_job_content_admin','job_bm_metabox_job_content_admin_job_status');

function job_bm_metabox_job_content_admin_job_status($job_id){

    $settings_tabs_field = new settings_tabs_field();
    $class_job_bm_functions = new class_job_bm_functions();
    $job_status_list = $class_job_bm_functions->job_status_list();

    $job_bm_job_status = get_post_meta($job_id, 'job_bm_job_status', true);

    $args = array(
        'id'		=> 'job_bm_job_status',
        //'parent'		=> '',
        'title'		=> __('Job status','job-board-manager'),
        'details'	=> __('Select job status.','job-board-manager'),
        'type'		=> 'select',
        'value'		=> $job_bm_job_status,
        'default'		=> '',
        'args'		=> $job_status_list,
    );

    $settings_tabs_field->generate_field($args);

}


add_action('job_bm_metabox_job_content_admin','job_bm_metabox_job_content_admin_featured');

function job_bm_metabox_job_content_admin_featured($job_id){

    $settings_tabs_field = new settings_tabs_field();
    $class_job_bm_functions = new class_job_bm_functions();
    $job_status_list = $class_job_bm_functions->job_status_list();

    $job_bm_featured = get_post_meta($job_id, 'job_bm_featured', true);

    $args = array(
        'id'		=> 'job_bm_featured',
        //'parent'		=> '',
        'title'		=> __('Featured job','job-board-manager'),
        'details'	=> __('Choose job as featured','job-board-manager'),
        'type'		=> 'select',
        'value'		=> $job_bm_featured,
        'default'		=> '',
        'args'		=> array('no'=>__('No', 'job-board-manager'),'yes'=>__('Yes', 'job-board-manager')),
    );

    $settings_tabs_field->generate_field($args);

}




add_action('job_bm_metabox_job_content_admin','job_bm_metabox_job_content_admin_expire_date');

function job_bm_metabox_job_content_admin_expire_date($job_id){

    $settings_tabs_field = new settings_tabs_field();
    $job_bm_expire_date = get_post_meta($job_id, 'job_bm_expire_date', true);

    $job_bm_job_expiry_days = (int) get_option('job_bm_job_expiry_days', 30);
    $current_date = date('Y-m-d');
    $expiry_date = strtotime($current_date. ' + '.$job_bm_job_expiry_days.' days');
    $expiry_date = date('Y-m-d', $expiry_date);


    $args = array(
        'id'		=> 'job_bm_expire_date',
        //'parent'		=> '',
        'title'		=> __('Expiry date','job-board-manager'),
        'details'	=> __('Choose custom job expiry date','job-board-manager'),
        'type'		=> 'datepicker',
        'format'		=> 'yy-mm-dd',
        'value'		=> $job_bm_expire_date,
        'default'		=> $expiry_date,
        'placeholder'		=> '',
    );

    $settings_tabs_field->generate_field($args);

}



add_action('job_bm_metabox_job_content_admin','job_bm_metabox_job_content_admin_application_methods');

function job_bm_metabox_job_content_admin_application_methods($job_id){

    $settings_tabs_field = new settings_tabs_field();
    $class_job_bm_functions = new class_job_bm_functions();
    $apply_method_list = $class_job_bm_functions->apply_method_list();

    $job_bm_application_methods= get_post_meta($job_id, 'job_bm_application_methods', true);

    $args = array(
        'id'		=> 'job_bm_application_methods',
        //'parent'		=> '',
        'title'		=> __('Application methods','job-board-manager'),
        'details'	=> __('Choose application methods','job-board-manager'),
        'type'		=> 'select',
        'multiple'		=> true,
        'value'		=> $job_bm_application_methods,
        'default'		=> array(),
        'args'		=> $apply_method_list,
    );

    $settings_tabs_field->generate_field($args);

}





add_action('job_bm_meta_box_save_job','job_bm_meta_box_save_job');

function job_bm_meta_box_save_job($job_id){

    $job_bm_total_vacancies = isset($_POST['job_bm_total_vacancies']) ? sanitize_text_field($_POST['job_bm_total_vacancies']) : '';
    update_post_meta($job_id, 'job_bm_total_vacancies', $job_bm_total_vacancies);

    $job_bm_job_type = isset($_POST['job_bm_job_type']) ? sanitize_text_field($_POST['job_bm_job_type']) : '';
    update_post_meta($job_id, 'job_bm_job_type', $job_bm_job_type);

    $job_bm_job_level = isset($_POST['job_bm_job_level']) ? sanitize_text_field($_POST['job_bm_job_level']) : '';
    update_post_meta($job_id, 'job_bm_job_level', $job_bm_job_level);

    $job_bm_years_experience = isset($_POST['job_bm_years_experience']) ? sanitize_text_field($_POST['job_bm_years_experience']) : '';
    update_post_meta($job_id, 'job_bm_years_experience', $job_bm_years_experience);

    $job_bm_salary_type = isset($_POST['job_bm_salary_type']) ? sanitize_text_field($_POST['job_bm_salary_type']) : '';
    update_post_meta($job_id, 'job_bm_salary_type', $job_bm_salary_type);

    $job_bm_salary_fixed = isset($_POST['job_bm_salary_fixed']) ? sanitize_text_field($_POST['job_bm_salary_fixed']) : '';
    update_post_meta($job_id, 'job_bm_salary_fixed', $job_bm_salary_fixed);

    $job_bm_salary_min = isset($_POST['job_bm_salary_min']) ? sanitize_text_field($_POST['job_bm_salary_min']) : '';
    update_post_meta($job_id, 'job_bm_salary_min', $job_bm_salary_min);

    $job_bm_salary_max = isset($_POST['job_bm_salary_max']) ? sanitize_text_field($_POST['job_bm_salary_max']) : '';
    update_post_meta($job_id, 'job_bm_salary_max', $job_bm_salary_max);

    $job_bm_salary_duration = isset($_POST['job_bm_salary_duration']) ? sanitize_text_field($_POST['job_bm_salary_duration']) : '';
    update_post_meta($job_id, 'job_bm_salary_duration', $job_bm_salary_duration);

    $job_bm_salary_currency = isset($_POST['job_bm_salary_currency']) ? sanitize_text_field($_POST['job_bm_salary_currency']) : '';
    update_post_meta($job_id, 'job_bm_salary_currency', $job_bm_salary_currency);

    $job_bm_contact_email = isset($_POST['job_bm_contact_email']) ? sanitize_text_field($_POST['job_bm_contact_email']) : '';
    update_post_meta($job_id, 'job_bm_contact_email', $job_bm_contact_email);



    $job_bm_company_name = isset($_POST['job_bm_company_name']) ? sanitize_text_field($_POST['job_bm_company_name']) : '';
    update_post_meta($job_id, 'job_bm_company_name', $job_bm_company_name);

    $job_bm_location = isset($_POST['job_bm_location']) ? sanitize_text_field($_POST['job_bm_location']) : '';
    update_post_meta($job_id, 'job_bm_location', $job_bm_location);

    $job_bm_address = isset($_POST['job_bm_address']) ? sanitize_text_field($_POST['job_bm_address']) : '';
    update_post_meta($job_id, 'job_bm_address', $job_bm_address);

    $job_bm_company_website = isset($_POST['job_bm_company_website']) ? esc_url_raw($_POST['job_bm_company_website']) : '';
    update_post_meta($job_id, 'job_bm_company_website', $job_bm_company_website);

    $job_bm_job_link = isset($_POST['job_bm_job_link']) ? esc_url_raw($_POST['job_bm_job_link']) : '';
    update_post_meta($job_id, 'job_bm_job_link', $job_bm_job_link);


    $job_bm_company_logo = isset($_POST['job_bm_company_logo']) ? sanitize_text_field($_POST['job_bm_company_logo']) : '';
    update_post_meta($job_id, 'job_bm_company_logo', $job_bm_company_logo);


    $job_bm_job_status = isset($_POST['job_bm_job_status']) ? sanitize_text_field($_POST['job_bm_job_status']) : '';
    update_post_meta($job_id, 'job_bm_job_status', $job_bm_job_status);

    $job_bm_featured = isset($_POST['job_bm_featured']) ? sanitize_text_field($_POST['job_bm_featured']) : '';
    update_post_meta($job_id, 'job_bm_featured', $job_bm_featured);


    if($job_bm_featured == 'yes'){

        $sticky_jobs = get_option( 'sticky_jobs', array() );
        if(!in_array($job_id, $sticky_jobs)){
            $sticky_jobs = array_merge($sticky_jobs, array($job_id));
            update_option('sticky_jobs', $sticky_jobs);
        }
    }else{
        $sticky_jobs = get_option( 'sticky_jobs', array() );
        $key = array_search($job_id, $sticky_jobs);
        if (false !== $key) {
            unset($sticky_jobs[$key]);
        }
        update_option('sticky_jobs', $sticky_jobs);
    }





    $job_bm_expire_date = isset($_POST['job_bm_expire_date']) ? sanitize_text_field($_POST['job_bm_expire_date']) : '';
    update_post_meta($job_id, 'job_bm_expire_date', $job_bm_expire_date);


    $job_bm_application_methods = isset($_POST['job_bm_application_methods']) ? job_bm_recursive_sanitize_arr($_POST['job_bm_application_methods']) : '';
    update_post_meta($job_id, 'job_bm_application_methods', $job_bm_application_methods);


}

