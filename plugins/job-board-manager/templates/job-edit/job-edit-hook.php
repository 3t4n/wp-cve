<?php
if ( ! defined('ABSPATH')) exit;  // if direct access 


/* Display question title field */

add_action('job_bm_job_edit_form', 'job_bm_job_edit_form_title', 0);

function job_bm_job_edit_form_title($job_id){

    $job_post = get_post($job_id);

    $post_title = isset($_POST['post_title']) ? sanitize_text_field($_POST['post_title']) : $job_post->post_title;

    ?>
    <div class="form-field-wrap">
        <div class="field-title"><?php _e('Job title','job-board-manager'); ?></div>
        <div class="field-input">
            <input placeholder="" type="text" value="<?php echo esc_attr($post_title); ?>" name="post_title">
            <p class="field-details"><?php _e('Write your job title','job-board-manager');
            ?></p>
        </div>
    </div>
    <?php
}


/* Display question details input field*/

add_action('job_bm_job_edit_form', 'job_bm_job_edit_form_content', 10);

function job_bm_job_edit_form_content($job_id){

    $job_post = get_post($job_id);

    $field_id = 'post_content';
    $post_content = isset($_POST['post_content']) ? wp_kses_post($_POST['post_content']) : $job_post->post_content;


    ?>
    <div class="form-field-wrap">
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

add_action('job_bm_job_edit_form', 'job_bm_job_edit_form_categories', 20);

function job_bm_job_edit_form_categories($job_id){

    $job_categories = get_the_terms($job_id, 'job_category');

    $job_category_id = isset($job_categories[0]->term_id) ? $job_categories[0]->term_id : '';
    $job_category = isset($_POST['job_category']) ? sanitize_text_field($_POST['job_category']) : $job_category_id;

    $categories = get_terms( array(
        'taxonomy' => 'job_category',
        'hide_empty' => false,
    ) );

    $terms = array();

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
            <select name="job_category">
                <?php
                if(!empty($terms)):
                    foreach ($terms as $term_id => $term_name){

                        $selected = ($job_category == $term_id) ? 'selected' : '';

                        ?>
                        <option <?php echo $selected; ?> value="<?php echo esc_attr($term_id); ?>"><?php echo esc_html
                            ($term_name); ?></option>
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






add_action('job_bm_job_edit_form', 'job_bm_job_edit_form_job_info_title', 30);


function job_bm_job_edit_form_job_info_title($job_id){


    ?>
    <div class="form-field-wrap ">
        <div class="field-separator"><?php echo __('Job Information','job-board-manager'); ?></div>
    </div>
    <?php
}











/* Display vacancies input field  */

add_action('job_bm_job_edit_form', 'job_bm_job_edit_form_total_vacancies', 30);


function job_bm_job_edit_form_total_vacancies($job_id){

    $job_bm_total_vacancies = get_post_meta($job_id,'job_bm_total_vacancies', true);

    $job_bm_total_vacancies = isset($_POST['job_bm_total_vacancies']) ? sanitize_text_field($_POST['job_bm_total_vacancies']) : $job_bm_total_vacancies;

    ?>
    <div class="form-field-wrap">
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

add_action('job_bm_job_edit_form', 'job_bm_job_edit_form_job_type', 30);

function job_bm_job_edit_form_job_type($job_id){

    $class_job_bm_functions = new class_job_bm_functions();
    $job_type_list = $class_job_bm_functions->job_type_list();

    $job_bm_job_type = get_post_meta($job_id,'job_bm_job_type', true);

    $job_bm_job_type = isset($_POST['job_bm_job_type']) ? sanitize_text_field($_POST['job_bm_job_type']) : $job_bm_job_type;


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

add_action('job_bm_job_edit_form', 'job_bm_job_edit_form_job_level', 30);

function job_bm_job_edit_form_job_level($job_id){

    $class_job_bm_functions = new class_job_bm_functions();
    $job_level_list = $class_job_bm_functions->job_level_list();

    $job_bm_job_level = get_post_meta($job_id,'job_bm_job_level', true);

    $job_bm_job_level = isset($_POST['job_bm_job_level']) ? sanitize_text_field($_POST['job_bm_job_level']) : $job_bm_job_level;


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

add_action('job_bm_job_edit_form', 'job_bm_job_edit_form_years_experience', 30);

function job_bm_job_edit_form_years_experience($job_id){

    $job_bm_years_experience = get_post_meta($job_id,'job_bm_years_experience', true);

    $job_bm_years_experience = isset($_POST['job_bm_years_experience']) ? sanitize_text_field($_POST['job_bm_years_experience']) : $job_bm_years_experience;

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

add_action('job_bm_job_edit_form', 'job_bm_job_edit_form_salary_type', 30);

function job_bm_job_edit_form_salary_type($job_id){

    $class_job_bm_functions = new class_job_bm_functions();
    $salary_type_list = $class_job_bm_functions->salary_type_list();

    $job_bm_salary_type = get_post_meta($job_id,'job_bm_salary_type', true);
    $job_bm_salary_type = !empty($job_bm_salary_type) ? $job_bm_salary_type : 'negotiable';

    $job_bm_salary_type = isset($_POST['job_bm_salary_type']) ? sanitize_text_field($_POST['job_bm_salary_type']) : $job_bm_salary_type;


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






add_action('job_bm_job_edit_form', 'job_bm_job_edit_form_salary_fixed', 30);

function job_bm_job_edit_form_salary_fixed($job_id){

    $job_bm_salary_type = get_post_meta($job_id,'job_bm_salary_type', true);
    $job_bm_salary_fixed = get_post_meta($job_id,'job_bm_salary_fixed', true);

    $job_bm_salary_fixed = isset($_POST['job_bm_salary_fixed']) ? sanitize_text_field($_POST['job_bm_salary_fixed']) : $job_bm_salary_fixed;

    ?>
    <div class="form-field-wrap salary_fixed" <?php if($job_bm_salary_type =='fixed'): ?> style="display: block" <?php endif; ?>>
        <div class="field-title"><?php _e('Fixed salary','job-board-manager'); ?></div>
        <div class="field-input">
            <input placeholder="50000" type="text" value="<?php echo esc_attr($job_bm_salary_fixed); ?>" name="job_bm_salary_fixed">
            <p class="field-details"><?php _e('Salary fixed, ex: 1200','job-board-manager');
                ?></p>
        </div>
    </div>
    <?php
}

add_action('job_bm_job_edit_form', 'job_bm_job_edit_form_salary_min', 30);


function job_bm_job_edit_form_salary_min($job_id){

    $job_bm_salary_type = get_post_meta($job_id,'job_bm_salary_type', true);
    $job_bm_salary_min = get_post_meta($job_id,'job_bm_salary_min', true);

    $job_bm_salary_min = isset($_POST['job_bm_salary_min']) ? sanitize_text_field($_POST['job_bm_salary_min']) : $job_bm_salary_min;

    ?>
    <div class="form-field-wrap salary_min" <?php if($job_bm_salary_type =='min-max'): ?> style="display: block" <?php endif; ?>>
        <div class="field-title"><?php _e('Minimum salary','job-board-manager'); ?></div>
        <div class="field-input">
            <input placeholder="5000" type="text" value="<?php echo esc_attr($job_bm_salary_min); ?>" name="job_bm_salary_min">
            <p class="field-details"><?php _e('Minimum salary amount, ex: 5000','job-board-manager');
                ?></p>
        </div>
    </div>
    <?php
}



add_action('job_bm_job_edit_form', 'job_bm_job_edit_form_salary_max', 30);

function job_bm_job_edit_form_salary_max($job_id){

    $job_bm_salary_type = get_post_meta($job_id,'job_bm_salary_type', true);
    $job_bm_salary_max = get_post_meta($job_id,'job_bm_salary_max', true);

    $job_bm_salary_max = isset($_POST['job_bm_salary_max']) ? sanitize_text_field($_POST['job_bm_salary_max']) : $job_bm_salary_max;

    ?>
    <div class="form-field-wrap salary_max" <?php if($job_bm_salary_type =='min-max'): ?> style="display: block" <?php endif; ?>>
        <div class="field-title"><?php _e('Maximum salary','job-board-manager'); ?></div>
        <div class="field-input">
            <input placeholder="10000" type="text" value="<?php echo esc_attr($job_bm_salary_max); ?>" name="job_bm_salary_max">
            <p class="field-details"><?php _e('Maximum salary amount, ex: 1000','job-board-manager');
                ?></p>
        </div>
    </div>
    <?php
}


add_action('job_bm_job_edit_form', 'job_bm_job_edit_form_salary_duration', 30);

function job_bm_job_edit_form_salary_duration(){

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




add_action('job_bm_job_edit_form', 'job_bm_job_edit_form_salary_currency', 30);

function job_bm_job_edit_form_salary_currency($job_id){

    $job_bm_salary_currency = get_post_meta($job_id,'job_bm_salary_currency', true);


    $job_bm_salary_currency = isset($_POST['job_bm_salary_currency']) ? sanitize_text_field($_POST['job_bm_salary_currency']) : $job_bm_salary_currency;

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








add_action('job_bm_job_edit_form', 'job_bm_job_edit_form_contact_email', 30);


function job_bm_job_edit_form_contact_email($job_id){

    $job_bm_contact_email = get_post_meta($job_id,'job_bm_contact_email', true);

    $job_bm_contact_email = isset($_POST['job_bm_contact_email']) ? sanitize_text_field($_POST['job_bm_contact_email']) : $job_bm_contact_email;

    ?>
    <div class="form-field-wrap">
        <div class="field-title"><?php _e('Contact email','job-board-manager'); ?></div>
        <div class="field-input">
            <input placeholder="contact@company.com" type="email" value="<?php echo esc_attr($job_bm_contact_email); ?>" name="job_bm_contact_email">
            <p class="field-details"><?php _e('Write your contact email','job-board-manager');
                ?></p>
        </div>
    </div>
    <?php
}


add_action('job_bm_job_edit_form', 'job_bm_job_edit_form_company_info', 40);


function job_bm_job_edit_form_company_info($job_id){


    ?>
    <div class="form-field-wrap ">
        <div class="field-separator"><?php _e('Company Information','job-board-manager'); ?></div>
    </div>
    <?php
}







add_action('job_bm_job_edit_form', 'job_bm_job_edit_form_company_name', 45);


function job_bm_job_edit_form_company_name($job_id){

    $job_bm_company_name = get_post_meta($job_id,'job_bm_company_name', true);

    $job_bm_company_name = isset($_POST['job_bm_company_name']) ? sanitize_text_field($_POST['job_bm_company_name']) : $job_bm_company_name;

    ?>
    <div class="form-field-wrap">
        <div class="field-title"><?php _e('Company name','job-board-manager'); ?></div>
        <div class="field-input">
            <input placeholder="Google Inc" type="text" value="<?php echo esc_attr($job_bm_company_name); ?>" name="job_bm_company_name">
            <p class="field-details"><?php _e('Write your company name','job-board-manager');
                ?></p>
        </div>
    </div>
    <?php
}



add_action('job_bm_job_edit_form', 'job_bm_job_edit_form_location', 45);

function job_bm_job_edit_form_location($job_id){

    $job_bm_location = get_post_meta($job_id,'job_bm_location', true);

    $job_bm_location = isset($_POST['job_bm_location']) ? sanitize_text_field($_POST['job_bm_location']) : $job_bm_location;

    ?>
    <div class="form-field-wrap">
        <div class="field-title"><?php _e('Location','job-board-manager'); ?></div>
        <div class="field-input">
            <input placeholder="Mountain View" type="text" value="<?php echo esc_attr($job_bm_location); ?>" name="job_bm_location">
            <p class="field-details"><?php _e('Write company location','job-board-manager');
                ?></p>
        </div>
    </div>
    <?php
}



add_action('job_bm_job_edit_form', 'job_bm_job_edit_form_address', 45);
function job_bm_job_edit_form_address($job_id){

    $job_bm_address = get_post_meta($job_id,'job_bm_address', true);

    $job_bm_address = isset($_POST['job_bm_address']) ? sanitize_text_field($_POST['job_bm_address']) : $job_bm_address;

    ?>
    <div class="form-field-wrap">
        <div class="field-title"><?php _e('Address','job-board-manager'); ?></div>
        <div class="field-input">
            <input placeholder="1600 Amphitheatre Parkway, Mountain View, CA" type="text" value="<?php echo esc_attr($job_bm_address); ?>" name="job_bm_address">
            <p class="field-details"><?php _e('Write company address','job-board-manager');
                ?></p>
        </div>
    </div>
    <?php
}




add_action('job_bm_job_edit_form', 'job_bm_job_edit_form_company_website', 45);
function job_bm_job_edit_form_company_website($job_id){

    $job_bm_company_website = get_post_meta($job_id,'job_bm_company_website', true);

    $job_bm_company_website = isset($_POST['job_bm_company_website']) ? esc_url_raw($_POST['job_bm_company_website']) : $job_bm_company_website;

    ?>
    <div class="form-field-wrap">
        <div class="field-title"><?php _e('Company website','job-board-manager'); ?></div>
        <div class="field-input">
            <input placeholder="http://domain.com" type="text" value="<?php echo esc_url_raw($job_bm_company_website); ?>" name="job_bm_company_website">
            <p class="field-details"><?php _e('Write company website','job-board-manager');
                ?></p>
        </div>
    </div>
    <?php
}

add_action('job_bm_job_edit_form', 'job_bm_job_edit_form_job_link', 45);
function job_bm_job_edit_form_job_link($job_id){

    $job_bm_job_link = get_post_meta($job_id,'job_bm_job_link', true);
    $job_bm_job_link = isset($_POST['job_bm_job_link']) ? esc_url_raw($_POST['job_bm_job_link']) : $job_bm_job_link;

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


add_action('job_bm_job_edit_form', 'job_bm_job_edit_form_company_logo', 45);
function job_bm_job_edit_form_company_logo($job_id){

    $job_bm_company_logo = get_post_meta($job_id,'job_bm_company_logo', true);

    //var_dump($job_bm_company_logo);



    $job_bm_company_logo = !empty($job_bm_company_logo) ? $job_bm_company_logo : job_bm_plugin_url."assets/front/images/placeholder.png";

    $job_bm_company_logo = isset($_POST['job_bm_company_logo']) ? sanitize_text_field($_POST['job_bm_company_logo']) : $job_bm_company_logo;

    ?>
    <div class="form-field-wrap job-bm-media-upload">
        <div class="field-title"><?php _e('Company logo','job-board-manager'); ?></div>
        <div class="field-input">
            <div class="media-preview-wrap" style="">
                <img class="media-preview" src="<?php echo esc_url_raw($job_bm_company_logo); ?>" style="width:100%;box-shadow: none;"/>
            </div>

            <input placeholder="" type="text" value="<?php echo esc_url_raw($job_bm_company_logo); ?>" name="job_bm_company_logo">
            <span class="media-upload " id=""><?php echo __('Upload','job-board-manager');?></span>
            <p class="field-details"><?php _e('Upload company logo','job-board-manager'); ?></p>
        </div>
    </div>
    <?php
}




/* display reCaptcha */

add_action('job_bm_job_edit_form', 'job_bm_job_edit_form_recaptcha', 60);

function job_bm_job_edit_form_recaptcha($job_id){

    $job_bm_reCAPTCHA_enable		= get_option('job_bm_reCAPTCHA_enable');
    $job_bm_reCAPTCHA_site_key		        = get_option('job_bm_reCAPTCHA_site_key');

    if($job_bm_reCAPTCHA_enable != 'yes'){
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

add_action('job_bm_job_edit_form', 'job_bm_job_edit_form_nonce' );

function job_bm_job_edit_form_nonce($job_id){

    ?>
    <div class="form-field-wrap">
        <div class="field-title"></div>
        <div class="field-input">

            <?php wp_nonce_field( 'job_bm_job_edit_nonce','job_bm_job_edit_nonce' ); ?>

        </div>
    </div>
    <?php
}


/* Display submit button */

add_action('job_bm_job_edit_form', 'job_bm_job_edit_form_submit', 90);

function job_bm_job_edit_form_submit($job_id){

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

add_action('job_bm_job_edit_data', 'job_bm_job_edit_data', 90, 2);

function job_bm_job_edit_data($job_id, $post_data){

    $job_bm_reCAPTCHA_enable		    = get_option('job_bm_reCAPTCHA_enable', 'no');
    $job_bm_edited_job_status 		    = get_option('job_bm_edited_job_status', 'pending' );

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

        if( isset($post_data['job_bm_salary_max']) && empty($post_data['job_bm_salary_max'])){
            $error->add( 'job_bm_salary_max', __( 'ERROR: Salary maximum is empty.', 'job-board-manager' ) );
        }

    }





    if(empty($post_data['job_bm_contact_email'])){

        $error->add( 'job_bm_contact_email', __( 'ERROR: Contact email is empty.', 'job-board-manager' ) );
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

    if(empty($post_data['job_bm_company_logo'])){

        $error->add( 'job_bm_company_logo', __( 'ERROR: Company logo is empty.', 'job-board-manager' ) );
    }

    if(empty($post_data['g-recaptcha-response']) && $job_bm_reCAPTCHA_enable =='yes'){

        $error->add( 'g-recaptcha-response', __( 'ERROR: reCaptcha test failed.', 'job-board-manager' ) );
    }


    if(! isset( $_POST['job_bm_job_edit_nonce'] ) || ! wp_verify_nonce( $_POST['job_bm_job_edit_nonce'], 'job_bm_job_edit_nonce' ) ){

        $error->add( '_wpnonce', __( 'ERROR: security test failed.', 'job-board-manager' ) );
    }



    $errors = apply_filters( 'job_bm_job_edit_errors', $error, $post_data );






    if ( !$error->has_errors() ) {

        $post_title = isset($post_data['post_title']) ? $post_data['post_title'] :'';
        $post_content = isset($post_data['post_content']) ? wp_kses_post($post_data['post_content']) : "";


        wp_update_post(
            array(
                'ID'    => $job_id,
                'post_title'    => $post_title,
                'post_content'  => $post_content,
                'post_status'   => $job_bm_edited_job_status,

            )
        );

        do_action('job_bm_job_edited', $job_id, $post_data);


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

add_action('job_bm_job_edited', 'job_bm_job_edited_save_data', 90, 2);

function job_bm_job_edited_save_data($job_id, $post_data){

    $user_id = get_current_user_id();

    $job_category = isset($post_data['job_category']) ? sanitize_text_field($post_data['job_category']) : "";
    $job_bm_total_vacancies = isset($post_data['job_bm_total_vacancies']) ? sanitize_text_field($post_data['job_bm_total_vacancies']) : "";
    $job_bm_job_type = isset($post_data['job_bm_job_type']) ? sanitize_text_field($post_data['job_bm_job_type']) : "";
    $job_bm_job_level = isset($post_data['job_bm_job_level']) ? sanitize_text_field($post_data['job_bm_job_level']) : "";
    $job_bm_years_experience = isset($post_data['job_bm_years_experience']) ? sanitize_text_field($post_data['job_bm_years_experience']) : "";
    $job_bm_salary_type = isset($post_data['job_bm_salary_type']) ? sanitize_text_field($post_data['job_bm_salary_type']) : "";
    $job_bm_salary_fixed = isset($post_data['job_bm_salary_fixed']) ? sanitize_text_field($post_data['job_bm_salary_fixed']) : "";
    $job_bm_salary_min = isset($post_data['job_bm_salary_min']) ? sanitize_text_field($post_data['job_bm_salary_min']) : "";
    $job_bm_salary_max = isset($post_data['job_bm_salary_max']) ? sanitize_text_field($post_data['job_bm_salary_max']) : "";
    $job_bm_salary_duration = isset($post_data['job_bm_salary_duration']) ? sanitize_text_field($post_data['job_bm_salary_duration']) : "";

    $job_bm_salary_currency = isset($post_data['job_bm_salary_currency']) ? sanitize_text_field($post_data['job_bm_salary_currency']) : "";

    $job_bm_contact_email = isset($post_data['job_bm_contact_email']) ? sanitize_text_field($post_data['job_bm_contact_email']) : "";
    $job_bm_company_name = isset($post_data['job_bm_company_name']) ? sanitize_text_field($post_data['job_bm_company_name']) : "";
    $job_bm_location = isset($post_data['job_bm_location']) ? sanitize_text_field($post_data['job_bm_location']) : "";
    $job_bm_address = isset($post_data['job_bm_address']) ? sanitize_text_field($post_data['job_bm_address']) : "";
    $job_bm_company_logo = isset($post_data['job_bm_company_logo']) ? sanitize_text_field($post_data['job_bm_company_logo']) : "";
    $job_bm_company_website = isset($post_data['job_bm_company_website']) ? esc_url_raw($post_data['job_bm_company_website']) : "";
    $job_bm_job_link = isset($post_data['job_bm_job_link']) ? esc_url_raw($post_data['job_bm_job_link']) : "";



    wp_set_post_terms( $job_id, $job_category, 'job_category' );


    update_post_meta($job_id, 'job_bm_total_vacancies', $job_bm_total_vacancies);
    update_post_meta($job_id, 'job_bm_job_type', $job_bm_job_type);
    update_post_meta($job_id, 'job_bm_job_level', $job_bm_job_level);
    update_post_meta($job_id, 'job_bm_years_experience', $job_bm_years_experience);
    update_post_meta($job_id, 'job_bm_salary_type', $job_bm_salary_type);
    update_post_meta($job_id, 'job_bm_salary_fixed', $job_bm_salary_fixed);
    update_post_meta($job_id, 'job_bm_salary_min', $job_bm_salary_min);
    update_post_meta($job_id, 'job_bm_salary_max', $job_bm_salary_max);
    update_post_meta($job_id, 'job_bm_salary_duration', $job_bm_salary_duration);
    update_post_meta($job_id, 'job_bm_salary_currency', $job_bm_salary_currency);
    update_post_meta($job_id, 'job_bm_contact_email', $job_bm_contact_email);

    update_post_meta($job_id, 'job_bm_company_name', $job_bm_company_name);
    update_post_meta($job_id, 'job_bm_location', $job_bm_location);
    update_post_meta($job_id, 'job_bm_address', $job_bm_address);
    update_post_meta($job_id, 'job_bm_company_website', $job_bm_company_website);
    update_post_meta($job_id, 'job_bm_job_link', $job_bm_job_link);
    update_post_meta($job_id, 'job_bm_company_logo', $job_bm_company_logo);


    update_post_meta($job_id, 'job_bm_job_status', 'open');
    update_post_meta($job_id, 'job_bm_expire_date', '');
    update_post_meta($job_id, 'job_bm_featured', 'no');


}






/* Display success message after submitted question */

add_action('job_bm_job_edited', 'job_bm_job_edited_message', 80, 2);

function job_bm_job_edited_message($job_id, $post_data){

    ?>
    <div class="job-submitted success">
        <?php echo apply_filters('job_bm_job_edited_thank_you', _e('Thanks for update job.', 'job-board-manager')); ?>
    </div>
    <?php


}







add_action('job_bm_job_edited', 'job_bm_job_edited_redirect', 99999, 2);

function job_bm_job_edited_redirect($job_id, $post_data){

    $job_bm_edited_redirect_link 	= get_option('job_bm_edited_redirect_link');




    if(!empty($job_bm_edited_redirect_link)):

        if($job_bm_edited_redirect_link =='job_preview'){
            $redirect_page_url = get_preview_post_link($job_id);
        }
        elseif($job_bm_edited_redirect_link =='job_link'){
            $redirect_page_url = get_permalink($job_id);
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


}
