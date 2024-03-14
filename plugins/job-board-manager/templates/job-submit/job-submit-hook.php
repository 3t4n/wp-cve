<?php
if ( ! defined('ABSPATH')) exit;  // if direct access 




/* Display question title field */

add_action('job_bm_job_submit_form', 'job_bm_job_submit_form_title', 5);

function job_bm_job_submit_form_title(){

    $post_title = isset($_POST['post_title']) ? sanitize_text_field($_POST['post_title']) : "";

    ?>
    <div class="form-field-wrap is_required">
        <div class="field-title"><?php echo __('Job title','job-board-manager'); ?></div>
        <div class="field-input">
            <input placeholder="" type="text" value="<?php echo esc_attr($post_title); ?>" name="post_title">
            <p class="field-details"><?php _e('Write your job title','job-board-manager');
            ?></p>
        </div>
    </div>
    <?php
}


/* Display question details input field*/

add_action('job_bm_job_submit_form', 'job_bm_job_submit_form_content', 10);

function job_bm_job_submit_form_content(){

    $field_id = 'post_content';
    $post_content = isset($_POST['post_content']) ? wp_kses_post($_POST['post_content']) : "";


    ?>
    <div class="form-field-wrap is_required">
        <div class="field-title"><?php _e('Job description','job-board-manager'); ?></div>
        <div class="field-input">
            <?php
            ob_start();
            wp_editor( $post_content, $field_id, $settings = array('textarea_name'=>$field_id,
                'media_buttons'=>false,'wpautop'=>true,'editor_height'=>'200px', ) );
            echo ob_get_clean();

            ?>

            <p class="field-details"><?php _e('Write your job details.','job-board-manager'); ?></p>

        </div>
    </div>
    <?php
}




/* Display category input field  */

add_action('job_bm_job_submit_form', 'job_bm_job_submit_form_categories', 20);

function job_bm_job_submit_form_categories(){

    $job_category = isset($_POST['job_category']) ? sanitize_text_field($_POST['job_category']) : "";

    $categories = get_terms( array(
        'taxonomy' => 'job_category',
        'hide_empty' => false,
    ) );

    $terms = array();

    //var_dump($categories);



    if(!empty($categories)) {
        foreach ($categories as $category) {

            $name = $category->name;
            $cat_ID = $category->term_id;
            $terms[$cat_ID] = $name;
        }
    }

    ?>
    <div class="form-field-wrap">
        <div class="field-title"><?php _e('Job category','job-board-manager'); ?></div>
        <div class="field-input">
            <select name="job_category" >
                <?php
                if(!empty($terms)):
                    foreach ($terms as $term_id => $term_name){

                        $selected = ($job_category == $term_id) ? 'selected' : '';

                        ?>
                        <option <?php echo $selected; ?> value="<?php echo esc_attr($term_id); ?>"><?php echo esc_html($term_name); ?></option>
                        <?php
                    }
                endif;
                ?>
            </select>
            <p class="field-details"><?php _e('Select job category.','job-board-manager'); ?></p>

        </div>
    </div>
    <?php
}






add_action('job_bm_job_submit_form', 'job_bm_job_submit_form_job_info_title', 30);


function job_bm_job_submit_form_job_info_title(){


    ?>
    <div class="form-field-wrap ">
        <div class="field-separator"><?php echo __('Job Information','job-board-manager'); ?></div>
    </div>
    <?php
}











/* Display vacancies input field  */

add_action('job_bm_job_submit_form', 'job_bm_job_submit_form_total_vacancies', 30);


function job_bm_job_submit_form_total_vacancies(){

    $job_bm_total_vacancies = isset($_POST['job_bm_total_vacancies']) ? sanitize_text_field($_POST['job_bm_total_vacancies']) : 1;

    ?>
    <div class="form-field-wrap is_required">
        <div class="field-title"><?php _e('Total vacancies','job-board-manager'); ?></div>
        <div class="field-input">
            <input placeholder="3" type="text" value="<?php echo esc_attr($job_bm_total_vacancies); ?>" name="job_bm_total_vacancies">
            <p class="field-details"><?php _e('Total number of vacancies','job-board-manager');
                ?></p>
        </div>
    </div>
    <?php
}



/* Display job_type input field  */

add_action('job_bm_job_submit_form', 'job_bm_job_submit_form_job_type', 30);

function job_bm_job_submit_form_job_type(){

    $class_job_bm_functions = new class_job_bm_functions();
    $job_type_list = $class_job_bm_functions->job_type_list();

    $job_bm_job_type = isset($_POST['job_bm_job_type']) ? sanitize_text_field($_POST['job_bm_job_type']) : "";


    ?>
    <div class="form-field-wrap">
        <div class="field-title"><?php _e('Job type','job-board-manager'); ?></div>
        <div class="field-input">
            <select name="job_bm_job_type" >
                <?php
                if(!empty($job_type_list)):
                    foreach ($job_type_list as $job_type => $job_type_name){

                        $selected = ($job_bm_job_type == $job_type) ? 'selected' : '';

                        ?>
                        <option <?php echo $selected; ?> value="<?php echo esc_attr($job_type); ?>"><?php echo esc_html($job_type_name); ?></option>
                        <?php
                    }
                endif;
                ?>
            </select>
            <p class="field-details"><?php _e('Select job type.','job-board-manager'); ?></p>

        </div>
    </div>
    <?php
}



/* Display job_type input field  */

add_action('job_bm_job_submit_form', 'job_bm_job_submit_form_job_level', 30);

function job_bm_job_submit_form_job_level(){

    $class_job_bm_functions = new class_job_bm_functions();
    $job_level_list = $class_job_bm_functions->job_level_list();

    $job_bm_job_level = isset($_POST['job_bm_job_level']) ? sanitize_text_field($_POST['job_bm_job_level']) : "";


    ?>
    <div class="form-field-wrap">
        <div class="field-title"><?php _e('Job level','job-board-manager'); ?></div>
        <div class="field-input">
            <select name="job_bm_job_level" >
                <?php
                if(!empty($job_level_list)):
                    foreach ($job_level_list as $job_level => $job_level_name){

                        $selected = ($job_bm_job_level == $job_level) ? 'selected' : '';

                        ?>
                        <option <?php echo $selected; ?> value="<?php echo esc_attr($job_level); ?>"><?php echo esc_html($job_level_name); ?></option>
                        <?php
                    }
                endif;
                ?>
            </select>
            <p class="field-details"><?php _e('Select job level.','job-board-manager'); ?></p>

        </div>
    </div>
    <?php
}





/* Display years_experience input field  */

add_action('job_bm_job_submit_form', 'job_bm_job_submit_form_years_experience', 30);

function job_bm_job_submit_form_years_experience(){

    $job_bm_years_experience = isset($_POST['job_bm_years_experience']) ? sanitize_text_field($_POST['job_bm_years_experience']) : 1;

    ?>
    <div class="form-field-wrap">
        <div class="field-title"><?php _e('Years of experience','job-board-manager'); ?></div>
        <div class="field-input">
            <input placeholder="5" type="text" value="<?php echo esc_attr($job_bm_years_experience); ?>" name="job_bm_years_experience">
            <p class="field-details"><?php _e('Years of experience must have.','job-board-manager');
                ?></p>
        </div>
    </div>
    <?php
}






/* Display job_type input field  */

add_action('job_bm_job_submit_form', 'job_bm_job_submit_form_salary_type', 30);

function job_bm_job_submit_form_salary_type(){

    $class_job_bm_functions = new class_job_bm_functions();
    $salary_type_list = $class_job_bm_functions->salary_type_list();

    $job_bm_salary_type = isset($_POST['job_bm_salary_type']) ? sanitize_text_field($_POST['job_bm_salary_type']) : "negotiable";


    ?>
    <div class="form-field-wrap">
        <div class="field-title"><?php _e('Salary type','job-board-manager'); ?></div>
        <div class="field-input">
            <select name="job_bm_salary_type" >
                <?php
                if(!empty($salary_type_list)):
                    foreach ($salary_type_list as $salary_type => $salary_type_name){

                        $selected = ($job_bm_salary_type == $salary_type) ? 'selected' : '';

                        ?>
                        <option <?php echo $selected; ?> value="<?php echo esc_attr($salary_type); ?>"><?php echo esc_html($salary_type_name); ?></option>
                        <?php
                    }
                endif;
                ?>
            </select>
            <p class="field-details"><?php _e('Select salary type.','job-board-manager'); ?></p>

        </div>
    </div>

    <style type="text/css">

        <?php

        if($job_bm_salary_type =='negotiable'){
            ?>
            .salary_max, .salary_min, .salary_fixed, .salary_duration, .salary_currency{
                display: none;
            }
            <?php
        }elseif ($job_bm_salary_type =='fixed'){
            ?>
            .salary_max, .salary_min, .salary_duration, .salary_currency{
                display: none;
            }
            <?php
        }elseif ($job_bm_salary_type =='min-max'){
            ?>
            .salary_fixed{
                display: none;
            }
            <?php
        }



        ?>

    </style>


    <?php
}






add_action('job_bm_job_submit_form', 'job_bm_job_submit_form_salary_fixed', 30);

function job_bm_job_submit_form_salary_fixed(){

    $job_bm_salary_fixed = isset($_POST['job_bm_salary_fixed']) ? sanitize_text_field($_POST['job_bm_salary_fixed']) : "";
    $job_bm_salary_type = isset($_POST['job_bm_salary_type']) ? sanitize_text_field($_POST['job_bm_salary_type']) : "";

    ?>
    <div class="form-field-wrap salary_fixed" <?php if($job_bm_salary_type =='fixed'): ?> style="display: block" <?php endif; ?>>
        <div class="field-title"><?php _e('Fixed salary','job-board-manager'); ?></div>
        <div class="field-input">
            <input placeholder="<?php echo __('50000','job-board-manager'); ?>" type="text" value="<?php echo esc_attr($job_bm_salary_fixed); ?>" name="job_bm_salary_fixed">
            <p class="field-details"><?php _e('Salary fixed, ex: 1200','job-board-manager');
                ?></p>
        </div>
    </div>
    <?php
}

add_action('job_bm_job_submit_form', 'job_bm_job_submit_form_salary_min', 30);


function job_bm_job_submit_form_salary_min(){

    $job_bm_salary_min = isset($_POST['job_bm_salary_min']) ? sanitize_text_field($_POST['job_bm_salary_min']) : "";
    $job_bm_salary_type = isset($_POST['job_bm_salary_type']) ? sanitize_text_field($_POST['job_bm_salary_type']) : "";

    ?>
    <div class="form-field-wrap salary_min" <?php if($job_bm_salary_type =='min-max'): ?> style="display: block" <?php endif; ?>>
        <div class="field-title"><?php _e('Minimum salary','job-board-manager'); ?></div>
        <div class="field-input">
            <input placeholder="<?php echo __('5000','job-board-manager'); ?>" type="text" value="<?php echo esc_attr($job_bm_salary_min); ?>" name="job_bm_salary_min">
            <p class="field-details"><?php _e('Minimum salary amount, ex: 5000','job-board-manager');
                ?></p>
        </div>
    </div>
    <?php
}



add_action('job_bm_job_submit_form', 'job_bm_job_submit_form_salary_max', 30);

function job_bm_job_submit_form_salary_max(){

    $job_bm_salary_max = isset($_POST['job_bm_salary_max']) ? sanitize_text_field($_POST['job_bm_salary_max']) : "";
    $job_bm_salary_type = isset($_POST['job_bm_salary_type']) ? sanitize_text_field($_POST['job_bm_salary_type']) : "";

    ?>
    <div class="form-field-wrap salary_max" <?php if($job_bm_salary_type =='min-max'): ?> style="display: block" <?php endif; ?>>
        <div class="field-title"><?php _e('Maximum salary','job-board-manager'); ?></div>
        <div class="field-input">
            <input placeholder="<?php echo __('10000','job-board-manager'); ?>" type="text" value="<?php echo esc_attr($job_bm_salary_max); ?>" name="job_bm_salary_max">
            <p class="field-details"><?php _e('Maximum salary amount, ex: 1000','job-board-manager');
                ?></p>
        </div>
    </div>
    <?php
}


add_action('job_bm_job_submit_form', 'job_bm_job_submit_form_salary_duration', 30);

function job_bm_job_submit_form_salary_duration(){

    $class_job_bm_functions = new class_job_bm_functions();
    $salary_duration_list = $class_job_bm_functions->salary_duration_list();

    $job_bm_salary_duration = isset($_POST['job_bm_salary_duration']) ? sanitize_text_field($_POST['job_bm_salary_duration']) : "month";


    ?>
    <div class="form-field-wrap salary_duration">
        <div class="field-title"><?php _e('Salary duration','job-board-manager'); ?></div>
        <div class="field-input">
            <select name="job_bm_salary_duration" >
                <?php
                if(!empty($salary_duration_list)):
                    foreach ($salary_duration_list as $salary_duration => $salary_duration_name){

                        $selected = ($job_bm_salary_duration == $salary_duration) ? 'selected' : '';

                        ?>
                        <option <?php echo $selected; ?> value="<?php echo esc_attr($salary_duration); ?>"><?php echo esc_html($salary_duration_name); ?></option>
                        <?php
                    }
                endif;
                ?>
            </select>
            <p class="field-details"><?php _e('Select salary duration.','job-board-manager'); ?></p>

        </div>
    </div>
    <?php
}



add_action('job_bm_job_submit_form', 'job_bm_job_submit_form_salary_currency', 30);

function job_bm_job_submit_form_salary_currency(){

    $job_bm_salary_currency = isset($_POST['job_bm_salary_currency']) ? sanitize_text_field($_POST['job_bm_salary_currency']) : "";

    ?>
    <div class="form-field-wrap salary_currency" >
        <div class="field-title"><?php _e('Salary currency','job-board-manager'); ?></div>
        <div class="field-input">
            <input placeholder="<?php echo __('USD','job-board-manager'); ?>" type="text" value="<?php echo esc_attr($job_bm_salary_currency); ?>" name="job_bm_salary_currency">
            <p class="field-details"><?php _e('Write salary currency, ex: USD','job-board-manager');
                ?></p>
        </div>
    </div>
    <?php
}








add_action('job_bm_job_submit_form', 'job_bm_job_submit_form_contact_email', 30);


function job_bm_job_submit_form_contact_email(){

    $job_bm_job_submit_create_account = get_option('job_bm_job_submit_create_account');
    $job_bm_job_submit_generate_username = get_option('job_bm_job_submit_generate_username');


    global $current_user;

    $logged_user_email =  isset($current_user->user_email) ? $current_user->user_email : '';
    //var_dump($current_user->user_email);

    $job_bm_contact_email = isset($_POST['job_bm_contact_email']) ? sanitize_text_field($_POST['job_bm_contact_email']) : $logged_user_email;
    $job_bm_username = isset($_POST['job_bm_username']) ? sanitize_text_field($_POST['job_bm_username']) : '';
    $job_bm_create_account = isset($_POST['job_bm_create_account']) ? sanitize_text_field($_POST['job_bm_create_account']) : '';

    ?>
    <div class="form-field-wrap is_required">
        <div class="field-title"><?php _e('Contact email','job-board-manager'); ?></div>
        <div class="field-input">
            <input placeholder="contact@company.com" type="email" value="<?php echo esc_attr($job_bm_contact_email); ?>" name="job_bm_contact_email">
            <p class="field-details"><?php _e('Write your contact email','job-board-manager');
                ?></p>
        </div>
    </div>

    <?php

    if(!is_user_logged_in() && $job_bm_job_submit_create_account == 'yes'):
        ?>
        <div class="form-field-wrap">
            <div class="field-title"></div>
            <div class="field-input">
                <label><input type="checkbox" <?php if($job_bm_create_account) echo 'checked'; ?>  value="1" name="job_bm_create_account"> <?php echo __('Create account?'); ?></label>
                <p class="field-details"></p>
                <input style="display: <?php if($job_bm_create_account) echo 'block'; else echo 'none'; ?>" placeholder="username" type="text" value="<?php echo esc_attr($job_bm_username); ?>" name="job_bm_username">

            </div>
        </div>

    <script>
        jQuery(document).ready(function($) {
            $(document).on('change', '.job-bm-job-submit input[name="job_bm_create_account"]', function(){

                if($(this).attr("checked") ){
                    $('input[name="job_bm_username"]').fadeIn();
                }else{
                    $('input[name="job_bm_username"]').fadeOut();
                }



            })
        })
    </script>

        <?php
    endif;

    ?>



    <?php
}


add_action('job_bm_job_submit_form', 'job_bm_job_submit_form_company_info', 40);


function job_bm_job_submit_form_company_info(){


    ?>
    <div class="form-field-wrap ">
        <div class="field-separator"><?php echo __('Company Information','job-board-manager'); ?></div>
    </div>
    <?php
}







add_action('job_bm_job_submit_form', 'job_bm_job_submit_form_company_name', 45);


function job_bm_job_submit_form_company_name(){

    $job_bm_company_name = isset($_POST['job_bm_company_name']) ? sanitize_text_field($_POST['job_bm_company_name']) : "";

    ?>
    <div class="form-field-wrap is_required">
        <div class="field-title"><?php _e('Company name','job-board-manager'); ?></div>
        <div class="field-input">
            <input placeholder="" type="text" value="<?php echo esc_attr($job_bm_company_name); ?>" name="job_bm_company_name">
            <p class="field-details"><?php _e('Write your company name','job-board-manager');
                ?></p>
        </div>
    </div>
    <?php
}



add_action('job_bm_job_submit_form', 'job_bm_job_submit_form_location', 45);

function job_bm_job_submit_form_location(){

    $job_bm_location = isset($_POST['job_bm_location']) ? sanitize_text_field($_POST['job_bm_location']) : "";

    ?>
    <div class="form-field-wrap is_required">
        <div class="field-title"><?php _e('Location','job-board-manager'); ?></div>
        <div class="field-input">
            <input placeholder="New York" type="text" value="<?php echo esc_attr($job_bm_location); ?>" name="job_bm_location">
            <p class="field-details"><?php _e('Write company location, use city or state. ex: New Work','job-board-manager');
                ?></p>
        </div>
    </div>
    <?php
}



add_action('job_bm_job_submit_form', 'job_bm_job_submit_form_address', 45);
function job_bm_job_submit_form_address(){

    $job_bm_address = isset($_POST['job_bm_address']) ? sanitize_text_field($_POST['job_bm_address']) : "";

    ?>
    <div class="form-field-wrap is_required">
        <div class="field-title"><?php _e('Address','job-board-manager'); ?></div>
        <div class="field-input">
            <input placeholder="4549 Godfrey Road" type="text" value="<?php echo esc_attr($job_bm_address); ?>" name="job_bm_address">
            <p class="field-details"><?php _e('Write company address','job-board-manager');
                ?></p>
        </div>
    </div>
    <?php
}




add_action('job_bm_job_submit_form', 'job_bm_job_submit_form_company_website', 45);
function job_bm_job_submit_form_company_website(){

    $job_bm_company_website = isset($_POST['job_bm_company_website']) ? esc_url_raw($_POST['job_bm_company_website']) : "";

    ?>
    <div class="form-field-wrap">
        <div class="field-title"><?php _e('Company website','job-board-manager'); ?></div>
        <div class="field-input">
            <input placeholder="http://companywebsite.com" type="text" value="<?php echo esc_url_raw($job_bm_company_website); ?>" name="job_bm_company_website">
            <p class="field-details"><?php _e('Write company website','job-board-manager');
                ?></p>
        </div>
    </div>
    <?php
}

add_action('job_bm_job_submit_form', 'job_bm_job_submit_form_job_link', 45);
function job_bm_job_submit_form_job_link(){

    $job_bm_job_link = isset($_POST['job_bm_job_link']) ? esc_url_raw($_POST['job_bm_job_link']) : "";

    ?>
    <div class="form-field-wrap">
        <div class="field-title"><?php _e('Job link','job-board-manager'); ?></div>
        <div class="field-input">
            <input placeholder="http://companywebsite.com/job-details" type="text" value="<?php echo esc_url_raw($job_bm_job_link); ?>" name="job_bm_job_link">
            <p class="field-details"><?php _e('Job link at company website','job-board-manager');
                ?></p>
        </div>
    </div>
    <?php
}


add_action('job_bm_job_submit_form', 'job_bm_job_submit_form_company_logo', 45);
function job_bm_job_submit_form_company_logo(){

    $job_bm_company_logo = isset($_POST['job_bm_company_logo']) ? sanitize_text_field($_POST['job_bm_company_logo']) : job_bm_plugin_url."assets/front/images/placeholder.png";

    ?>
    <div class="form-field-wrap job-bm-media-upload">
        <div class="field-title"><?php _e('Company logo','job-board-manager'); ?></div>
        <div class="field-input">


            <?php

            if(is_user_logged_in()):
                ?>
                <div class="media-preview-wrap" style="">
                    <img class="media-preview" src="<?php echo $job_bm_company_logo; ?>" style="width:100%;box-shadow: none;"/>
                </div>

                <input placeholder="" type="text" value="<?php echo esc_url_raw($job_bm_company_logo); ?>" name="job_bm_company_logo">


                <span class="media-upload " id=""><?php echo __('Upload','job-board-manager');?></span>
                <p class="field-details"><?php _e('Upload company logo','job-board-manager'); ?></p>
                <?php
            else:

                ?>
                <input type="file" name="job_bm_company_logo"  multiple="false" />


                <p class="field-details"><?php echo __('Choose image to upload company logo, you can use logo url.','job-board-manager'); ?></p>
                <?php

            endif;

            ?>
        </div>
    </div>
    <?php
}





/* display reCaptcha */

add_action('job_bm_job_submit_form', 'job_bm_job_submit_form_recaptcha', 60);

function job_bm_job_submit_form_recaptcha(){

    $job_bm_reCAPTCHA_enable		= get_option('job_bm_reCAPTCHA_enable');
    $job_bm_reCAPTCHA_site_key		        = get_option('job_bm_reCAPTCHA_site_key');

    if(empty($job_bm_reCAPTCHA_site_key) || $job_bm_reCAPTCHA_enable != 'yes'){
        return;
    }

    ?>
    <div class="form-field-wrap">
        <div class="field-title"></div>
        <div class="field-input">
            <div class="g-recaptcha" data-sitekey="<?php echo $job_bm_reCAPTCHA_site_key; ?>"></div>

            <?php wp_enqueue_script('google-recaptcha'); ?>
            <p class="field-details"><?php _e('Please prove you are human.','job-board-manager'); ?></p>

        </div>
    </div>
    <?php
}


/* Display nonce  */

add_action('job_bm_job_submit_form', 'job_bm_job_submit_form_nonce' );

function job_bm_job_submit_form_nonce(){

    ?>
    <div class="form-field-wrap">
        <div class="field-title"></div>
        <div class="field-input">

            <?php wp_nonce_field( 'job_bm_job_submit_nonce','job_bm_job_submit_nonce' ); ?>

        </div>
    </div>
    <?php
}


/* Display submit button */

add_action('job_bm_job_submit_form', 'job_bm_job_submit_form_submit', 90);

function job_bm_job_submit_form_submit(){

    ?>
    <div class="form-field-wrap">
        <div class="field-title"></div>
        <div class="field-input">
            <input type="submit"  name="submit" value="<?php _e('Submit', 'job-board-manager'); ?>" />
        </div>
    </div>
    <?php
}





/* Process the submitted data  */

add_action('job_bm_job_submit_data', 'job_bm_job_submit_data');

function job_bm_job_submit_data($post_data){

    $job_bm_reCAPTCHA_enable		    = get_option('job_bm_reCAPTCHA_enable');
    $job_bm_account_required_post_job 	= get_option('job_bm_account_required_post_job', 'yes');
    $job_bm_submitted_job_status 		= get_option('job_bm_submitted_job_status', 'pending' );
    $job_bm_job_login_page_id           = get_option('job_bm_job_login_page_id');
    $dashboard_page_url                 = get_permalink($job_bm_job_login_page_id);


    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
    } else {
        $user_id = 0;
    }

    $error = new WP_Error();




    if(empty($post_data['post_title'])){

        $error->add( 'post_title', __( 'ERROR: Job title is empty.', 'job-board-manager' ) );
    }

    if(empty($post_data['post_content'])){

        $error->add( 'post_content', __( 'ERROR: Job details is empty.', 'job-board-manager' ) );
    }

    if(empty($post_data['job_bm_total_vacancies'])){

        $error->add( 'job_bm_total_vacancies', __( 'ERROR: Total vacancies is empty.', 'job-board-manager' ) );
    }


    if((isset($post_data['job_bm_salary_type']) && $post_data['job_bm_salary_type'] == 'fixed')){
        if(empty($post_data['job_bm_salary_fixed'])){
            $error->add( 'job_bm_salary_fixed', __( 'ERROR: Salary fixed is empty.', 'job-board-manager' ) );
        }
    }

    if((isset($post_data['job_bm_salary_type']) && $post_data['job_bm_salary_type'] == 'min-max')){
        if(empty($post_data['job_bm_salary_min'])){
            $error->add( 'job_bm_salary_min', __( 'ERROR: Salary minimum is empty.', 'job-board-manager' ) );
        }

        if(isset($post_data['job_bm_salary_max']) && empty($post_data['job_bm_salary_max'])){
            $error->add( 'job_bm_salary_max', __( 'ERROR: Salary maximum is empty.', 'job-board-manager' ) );
        }

    }





    if(empty($post_data['job_bm_contact_email'])){
        $error->add( 'job_bm_contact_email', __( 'ERROR: Contact email is empty.', 'job-board-manager' ) );

    }

    if ( !is_email( $post_data['job_bm_contact_email'] ) ) {
        $error->add('email_invalid', __('ERROR: Email is not valid','job-board-manager'));
    }


    if(isset($post_data['job_bm_create_account'])){

        $email = isset($post_data['job_bm_contact_email']) ? sanitize_email($post_data['job_bm_contact_email']) : '';
        if ( email_exists( $email ) ) {
            $error->add('email_exists', __('ERROR: User already registered with this email.','job-board-manager'));
        }

        if(empty($post_data['job_bm_username'])){
            $error->add( 'username_exist', __( 'ERROR: username is empty.', 'job-board-manager' ) );

        }

        if ( username_exists( $post_data['job_bm_username'] ) ){
            $error->add('username_exist',__( 'ERROR: username already exists!','job-board-manager'));
        }

        if ( strlen( $post_data['job_bm_username'] ) < 4 ) {
            $error->add('username_short', __('ERROR: username at least 4 characters is required','job-board-manager'));
        }


    }



    if(empty($post_data['job_bm_company_name'])){

        $error->add( 'job_bm_company_name', __( 'ERROR: Company name is empty.', 'job-board-manager' ) );
    }

    if(empty($post_data['job_bm_location'])){

        $error->add( 'job_bm_location', __( 'ERROR: Location is empty.', 'job-board-manager' ) );
    }


    if(empty($post_data['job_bm_address'])){

        $error->add( 'job_bm_address', __( 'ERROR: Address is empty.', 'job-board-manager' ) );
    }


    if ( !is_user_logged_in() && !empty($_FILES['job_bm_company_logo']['name']) ) {
        // These files need to be included as dependencies when on the front end.
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );

        $attachment_id = media_handle_upload( 'job_bm_company_logo', 0 );



        if( is_wp_error( $attachment_id )){
            $error->add( 'company_logo', __( 'ERROR: Sorry, this file type is not permitted for security reasons.', 'job-board-manager-resume-manager' ) );
        }else{
            $post_data['company_logo_id'] = $attachment_id;
        }
    }

    if(empty($post_data['g-recaptcha-response']) && $job_bm_reCAPTCHA_enable =='yes'){

        $error->add( 'g-recaptcha-response', __( 'ERROR: reCaptcha test failed.', 'job-board-manager' ) );
    }

    if($job_bm_account_required_post_job == 'yes' && !is_user_logged_in()){

        $error->add( 'login',  sprintf (__('ERROR: Please <a target="_blank" href="%s">login</a> to submit question.',
            'job-board-manager'), $dashboard_page_url ));
    }

    if(! isset( $_POST['job_bm_job_submit_nonce'] ) || ! wp_verify_nonce( $_POST['job_bm_job_submit_nonce'], 'job_bm_job_submit_nonce' ) ){

        $error->add( '_wpnonce', __( 'ERROR: security test failed.', 'job-board-manager' ) );
    }


    if(isset($post_data['job_bm_create_account'])){

        $username = isset($post_data['job_bm_username']) ? sanitize_user($post_data['job_bm_username']) : "";
        $password = wp_generate_password(8);
        $email = isset($post_data['job_bm_contact_email']) ? sanitize_email($post_data['job_bm_contact_email']) : "";

        if(!empty($username) && !empty($email)){

            $userdata = array(
                'user_login'	=> 	$username,
                'user_email' 	=> 	$email,
                'user_pass' 	=> 	$password,
                'role' 	=> 	'job_poster',
            );

            $user_id = wp_insert_user( $userdata );

            if( is_wp_error( $user_id )){
                $error->add( 'account_create', __( 'ERROR: Something is wrong when creating account.', 'job-board-manager-resume-manager' ) );
            }
        }

    }



    $errors = apply_filters( 'job_bm_job_submit_errors', $error, $post_data );






    if ( !$error->has_errors() ) {

        $post_title = isset($post_data['post_title']) ? $post_data['post_title'] :'';
        $post_content = isset($post_data['post_content']) ? wp_kses_post($post_data['post_content']) : "";

        $job_ID = wp_insert_post(
            array(
                'post_title'    => $post_title,
                'post_content'  => $post_content,
                'post_status'   => $job_bm_submitted_job_status,
                'post_type'   	=> 'job',
                'post_author'   => $user_id,
            )
        );

        do_action('job_bm_job_submitted', $job_ID, $post_data);






    }
    else{

        $error_messages = $error->get_error_messages();

        ?>
        <div class="errors">
            <?php

            if(!empty($error_messages))
            foreach ($error_messages as $message){
                ?>
                <div class="job-bm-error"><?php echo $message; ?></div>
                <?php
            }
            ?>
        </div>
        <?php
    }
}


/* Display save data after submitted question */

add_action('job_bm_job_submitted', 'job_bm_job_submitted_save_data', 90, 2);

function job_bm_job_submitted_save_data($job_ID, $post_data){

    $user_id = get_current_user_id();

    $company_logo_id = isset($post_data['company_logo_id']) ? sanitize_text_field($post_data['company_logo_id']) : "";


    $job_category = isset($post_data['job_category']) ? sanitize_text_field($post_data['job_category']) : "";
    $job_bm_total_vacancies = isset($post_data['job_bm_total_vacancies']) ? sanitize_text_field($post_data['job_bm_total_vacancies']) : "";
    $job_bm_job_type = isset($post_data['job_bm_job_type']) ? sanitize_text_field($post_data['job_bm_job_type']) : "";
    $job_bm_job_level = isset($post_data['job_bm_job_level']) ? sanitize_text_field($post_data['job_bm_job_level']) : "";
    $job_bm_years_experience = isset($post_data['job_bm_years_experience']) ? sanitize_text_field($post_data['job_bm_years_experience']) : "";
    $job_bm_salary_type = isset($post_data['job_bm_salary_type']) ? sanitize_text_field($post_data['job_bm_salary_type']) : "";
    $job_bm_salary_fixed = isset($post_data['job_bm_salary_fixed']) ? sanitize_text_field($post_data['job_bm_salary_fixed']) : "";
    $job_bm_salary_min = isset($post_data['job_bm_salary_min']) ? sanitize_text_field($post_data['job_bm_salary_min']) : "";
    $job_bm_salary_max = isset($post_data['job_bm_salary_max']) ? sanitize_text_field($post_data['job_bm_salary_max']) : "";
    $job_bm_salary_currency = isset($post_data['job_bm_salary_currency']) ? sanitize_text_field($post_data['job_bm_salary_currency']) : "";

    $job_bm_contact_email = isset($post_data['job_bm_contact_email']) ? sanitize_text_field($post_data['job_bm_contact_email']) : "";
    $job_bm_company_name = isset($post_data['job_bm_company_name']) ? sanitize_text_field($post_data['job_bm_company_name']) : "";
    $job_bm_location = isset($post_data['job_bm_location']) ? sanitize_text_field($post_data['job_bm_location']) : "";
    $job_bm_address = isset($post_data['job_bm_address']) ? sanitize_text_field($post_data['job_bm_address']) : "";
    $job_bm_company_website = isset($post_data['job_bm_company_website']) ? esc_url_raw($post_data['job_bm_company_website']) : "";
    $job_bm_job_link = isset($post_data['job_bm_job_link']) ? esc_url_raw($post_data['job_bm_job_link']) : "";





    wp_set_post_terms( $job_ID, $job_category, 'job_category' );


    update_post_meta($job_ID, 'job_bm_total_vacancies', $job_bm_total_vacancies);
    update_post_meta($job_ID, 'job_bm_job_type', $job_bm_job_type);
    update_post_meta($job_ID, 'job_bm_job_level', $job_bm_job_level);
    update_post_meta($job_ID, 'job_bm_years_experience', $job_bm_years_experience);
    update_post_meta($job_ID, 'job_bm_salary_type', $job_bm_salary_type);
    update_post_meta($job_ID, 'job_bm_salary_fixed', $job_bm_salary_fixed);
    update_post_meta($job_ID, 'job_bm_salary_min', $job_bm_salary_min);
    update_post_meta($job_ID, 'job_bm_salary_max', $job_bm_salary_max);
    update_post_meta($job_ID, 'job_bm_salary_currency', $job_bm_salary_currency);

    update_post_meta($job_ID, 'job_bm_contact_email', $job_bm_contact_email);

    update_post_meta($job_ID, 'job_bm_company_name', $job_bm_company_name);
    update_post_meta($job_ID, 'job_bm_location', $job_bm_location);
    update_post_meta($job_ID, 'job_bm_address', $job_bm_address);
    update_post_meta($job_ID, 'job_bm_company_website', $job_bm_company_website);
    update_post_meta($job_ID, 'job_bm_job_link', $job_bm_job_link);



    if(is_user_logged_in()){
        $job_bm_company_logo = isset($post_data['job_bm_company_logo']) ? sanitize_text_field($post_data['job_bm_company_logo']) : "";
        update_post_meta($job_ID, 'job_bm_company_logo', $job_bm_company_logo);
    }else{

        $job_bm_company_logo = wp_get_attachment_url($company_logo_id);
        update_post_meta($job_ID, 'job_bm_company_logo', $job_bm_company_logo);
    }




    update_post_meta($job_ID, 'job_bm_job_status', 'open');
    update_post_meta($job_ID, 'job_bm_featured', 'no');

    $job_bm_job_expiry_days = (int) get_option('job_bm_job_expiry_days', 30);
    $current_date = date('Y-m-d');
    $expiry_date = strtotime($current_date. ' + '.$job_bm_job_expiry_days.' days');
    $expiry_date = date('Y-m-d', $expiry_date);


    update_post_meta($job_ID, 'job_bm_expire_date', $expiry_date);
}






/* Display success message after submitted question */

add_action('job_bm_job_submitted', 'job_bm_job_submitted_message', 80, 2);

function job_bm_job_submitted_message($job_ID, $post_data){

    ?>
    <div class="job-submitted success">
        <?php echo apply_filters('job_bm_job_submitted_thank_you', _e('Thanks for submit your job, we will review soon.', 'job-board-manager')); ?>
    </div>
    <?php


}







add_action('job_bm_job_submitted', 'job_bm_job_submitted_redirect', 99999, 2);

function job_bm_job_submitted_redirect($job_ID, $post_data){

    $job_bm_redirect_preview_link 	= get_option('job_bm_redirect_preview_link');




    if(!empty($job_bm_redirect_preview_link)):

        if($job_bm_redirect_preview_link =='job_preview'){
            $redirect_page_url = get_preview_post_link($job_ID);
        }
        elseif($job_bm_redirect_preview_link =='job_link'){
            $redirect_page_url = get_permalink($job_ID);
        }
        else{
            $job_bm_job_login_page_id 	= get_option('job_bm_job_login_page_id');
            $redirect_page_url 					= get_permalink($job_bm_job_login_page_id);
        }

        ?>
        <script>
            jQuery(document).ready(function($) {
                window.location.href = '<?php echo $redirect_page_url; ?>';
            })
        </script>
    <?php

    endif;



//    if ( wp_safe_redirect($redirect_page_url) ) {
//        exit;
//    }


}

