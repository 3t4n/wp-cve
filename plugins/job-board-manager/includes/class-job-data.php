<?php
if ( ! defined('ABSPATH')) exit;  // if direct access

class job_bm_job_data{

    public $job_id;
	
	public function __construct($jobId = 0){

	    $this->job_id = $jobId;


		}




    public function get_total_vacancies(){

        $job_id = $this->job_id;
        $total_vacancies = (int) get_post_meta($job_id, 'job_bm_total_vacancies', true);
        return $total_vacancies;


    }


    public function get_job_type(){
        $class_job_bm_functions = new class_job_bm_functions();
        $job_type_list = $class_job_bm_functions->job_type_list();

        $job_id = $this->job_id;
        $job_type = get_post_meta($job_id, 'job_bm_job_type', true);

        $job_type_name =  isset($job_type_list[$job_type]) ? $job_type_list[$job_type] : '';

        return array('type'=>$job_type,'type_name' => $job_type_name);

    }




    public function get_job_level(){

        $class_job_bm_functions = new class_job_bm_functions();
        $job_level_list = $class_job_bm_functions->job_level_list();


        $job_id = $this->job_id;
        $job_level = get_post_meta($job_id, 'job_bm_job_level', true);
        $job_level_name =  isset($job_level_list[$job_level]) ? $job_level_list[$job_level] : '';

        return array('level'=>$job_level,'level_name' => $job_level_name);
    }

    public function get_job_status(){
        $class_job_bm_functions = new class_job_bm_functions();
        $job_status_list = $class_job_bm_functions->job_status_list();

        $job_id = $this->job_id;
        $job_status = get_post_meta($job_id, 'job_bm_job_status', true);

        $job_status_name = isset($job_status_list[$job_status]) ? $job_status_list[$job_status] : '';

        return array('status'=>$job_status,'status_name' => $job_status_name);
    }



    public function get_years_experience(){

        $job_id = $this->job_id;
        $years_experience = get_post_meta($job_id, 'job_bm_years_experience', true);
        return $years_experience;


    }


    public function get_salary_type(){

        $job_id = $this->job_id;
        $salary_type = get_post_meta($job_id, 'job_bm_salary_type', true);
        return $salary_type;


    }

    public function get_salary_fixed(){

        $job_id = $this->job_id;
        $salary_fixed = (int) get_post_meta($job_id, 'job_bm_salary_fixed', true);
        return $salary_fixed;


    }


    public function get_salary_minimum(){

        $job_id = $this->job_id;
        $salary_min = (int) get_post_meta($job_id, 'job_bm_salary_min', true);
        return $salary_min;


    }


    public function get_salary_maximum(){

        $job_id = $this->job_id;
        $salary_maximum = (int) get_post_meta($job_id, 'job_bm_salary_max', true);
        return $salary_maximum;


    }

    public function get_salary_duration(){
        $class_job_bm_functions = new class_job_bm_functions();
        $salary_duration_list = $class_job_bm_functions->salary_duration_list();

        $job_id = $this->job_id;
        $salary_duration = get_post_meta($job_id, 'job_bm_salary_duration', true);
        $salary_duration = isset($salary_duration_list[$salary_duration]) ? $salary_duration_list[$salary_duration] : '';


        return $salary_duration;


    }


    public function get_salary_currency(){

        $salary_currency_main = get_option('job_bm_salary_currency', 'USD');

        $job_id = $this->job_id;
        $salary_currency = get_post_meta($job_id, 'job_bm_salary_currency', true);
        $salary_currency = !empty($salary_currency) ? $salary_currency : $salary_currency_main;

        return $salary_currency;


    }


    public function get_salary_html(){

        $job_salary_currency = $this->get_salary_currency();
        $job_salary_type = $this->get_salary_type();
        $job_salary_minimum = $this->get_salary_minimum();
        $job_salary_maximum = $this->get_salary_maximum();
        $job_salary_fixed = $this->get_salary_fixed();
        $job_salary_duration = $this->get_salary_duration();


        $job_id = $this->job_id;

        $salary_html = '';

        if($job_salary_type == 'fixed'):
            $salary_html = $job_salary_currency.$job_salary_fixed.' /'.$job_salary_duration;
        elseif($job_salary_type == 'negotiable'):
            $salary_html = __('Negotiable', 'job-board-manager');
        elseif($job_salary_type == 'min-max'):
            $salary_html = $job_salary_currency.$job_salary_minimum.' - '.$job_salary_currency.$job_salary_maximum.' /'.$job_salary_duration;
        else:

            if(!empty($job_salary_type))
            $salary_html = apply_filters('job_bm_salary_html_'.$job_salary_type, $job_id);

        endif;


        return $salary_html;

    }

    public function get_contact_email(){

        $job_id = $this->job_id;
        $contact_email = get_post_meta($job_id, 'job_bm_contact_email', true);
        return $contact_email;
    }

    public function get_company_name(){

        $job_id = $this->job_id;
        $company_name = get_post_meta($job_id, 'job_bm_company_name', true);
        return $company_name;


    }



    public function get_location(){

        $job_id = $this->job_id;
        $location = get_post_meta($job_id, 'job_bm_location', true);
        return $location;


    }

    public function get_address(){

        $job_id = $this->job_id;
        $address = get_post_meta($job_id, 'job_bm_address', true);
        return $address;


    }


    public function get_job_link(){

        $job_id = $this->job_id;
        $job_link = get_post_meta($job_id, 'job_bm_job_link', true);
        return $job_link;


    }

    public function get_company_website(){

        $job_id = $this->job_id;
        $company_website = get_post_meta($job_id, 'job_bm_company_website', true);

        $url = wp_parse_url($company_website);
        $url['main_url'] = $company_website;

        return $url;


    }

    public function get_company_logo(){

        $job_id = $this->job_id;
        $company_logo = get_post_meta($job_id, 'job_bm_company_logo', true);


        if(!empty($company_logo)){

            if(is_serialized($company_logo)){

                $company_logo = unserialize($company_logo);
                if(!empty($company_logo[0])){
                    $company_logo = $company_logo[0];
                    $company_logo = wp_get_attachment_url($company_logo);
                }

            }

        }



        return $company_logo;


    }



    public function is_featured(){

        $job_id = $this->job_id;
        $is_featured = get_post_meta($job_id, 'job_bm_featured', true);
        return $is_featured;


    }

    public function get_application_methods(){

        $default_application_methods = get_option('job_bm_application_methods', array('direct_email'));

        $job_id = $this->job_id;
        $job_application_methods = get_post_meta($job_id, 'job_bm_application_methods', true);
        $job_application_methods = !empty($job_application_methods) ? $job_application_methods : get_option('job_bm_application_methods', array('direct_email'));


        return $job_application_methods;


    }

    public function get_publish_date($format = 'Y-m-d'){

        $job_id = $this->job_id;

        $publish_date = get_the_date($format, $job_id);
        return $publish_date;
    }


    public function get_expire_date($format = 'Y-m-d'){

	    $publish_date = $this->get_publish_date();

        $gmt_offset = get_option('gmt_offset');
        $current_date = date($format, strtotime('+'.$gmt_offset.' hour'));



        $job_id = $this->job_id;
        $expire_date = get_post_meta($job_id, 'job_bm_expire_date', true);
        $expire_date = !empty($expire_date) ? $expire_date : date($format, strtotime($publish_date. ' + 30 days'));

        return $expire_date;
    }

    public function get_expire_in(){

        $publish_date = $this->get_publish_date();
        $expire_date = $this->get_expire_date();

        $gmt_offset = get_option('gmt_offset');
        $current_date = date('Y-m-d', strtotime('+'.$gmt_offset.' hour'));

        $expire_date_str = strtotime($expire_date);
        $current_date_str = strtotime($current_date);

        $days = '';
        //var_dump($expire_date);
        //var_dump($current_date);

        //var_dump($expire_date_str);
        //var_dump($current_date_str);

        if( $expire_date_str > $current_date_str){
            $days = human_time_diff( $current_date_str, $expire_date_str );
        }



        return $days;
    }




    public function get_tags($return = 'link', $separator = ','){

        $job_id = $this->job_id;
        $job_tags = get_the_terms($job_id, 'job_tag');

        if(!$job_tags) return '';

        if($return == 'link'){
            $total = count($job_tags);
            $html = '';
            $i =1;
            if(!empty($job_tags))
            foreach ($job_tags as $tag){
                $term_link = get_term_link($tag->term_id);
                $term_name = $tag->name;

                $html .= '<a class="job-tag" href="'.$term_link.'">'.$term_name.'</a>';

                if($i < $total)
                $html .= $separator;

                $i++;
            }

            return $html;

        }elseif ($return =='list'){

            $total = count($job_tags);

            $html = '';
            $html .= '<ul class="job-tags-wrap">';
            $i =1;
            if(!empty($job_tags))
                foreach ($job_tags as $tag){

                    $term_link = get_term_link($tag->term_id);
                    $term_name = $tag->name;

                    $html .= '<li><a class="job-tag" href="'.$term_link.'">'.$term_name.'</a></li>';

                    if($i < $total)
                        $html .= $separator;

                    $i++;
                }

            $html .= '</ul>';

            return $html;

        }elseif ($return =='object'){

            return $job_tags;

        }
    }



    public function get_categories($return = 'link', $separator = ','){

        $job_id = $this->job_id;
        $job_categories = get_the_terms($job_id, 'job_category');

        if(!$job_categories) return '';

        if($return == 'link'){
            $total = count($job_categories);
            $html = '';
            $i =1;
            if(!empty($job_categories))
                foreach ($job_categories as $category){

                    $term_link = get_term_link($category->term_id);
                    $term_name = $category->name;

                    $html .= '<a class="job-tag" href="'.$term_link.'">'.$term_name.'</a>';

                    if($i < $total)
                        $html .= $separator;

                    $i++;
                }

            return $html;

        }elseif ($return =='list'){

            $total = count($job_categories);

            $html = '';
            $html .= '<ul class="job-tags-wrap">';
            $i =1;
            if(!empty($job_categories))
                foreach ($job_categories as $category){

                    $term_link = get_term_link($category->term_id);
                    $term_name = $category->name;

                    $html .= '<li><a class="job-tag" href="'.$term_link.'">'.$term_name.'</a></li>';

                    if($i < $total)
                        $html .= $separator;

                    $i++;
                }

            $html .= '</ul>';

            return $html;

        }elseif ($return =='name'){

            $total = count($job_categories);

            $html = '';

            $i =1;
            if(!empty($job_categories))
                foreach ($job_categories as $category){

                    $term_link = get_term_link($category->term_id);
                    $term_name = $category->name;

                    $html .= ''.$term_name.'';

                    if($i < $total)
                        $html .= $separator;

                    $i++;
                }

            $html .= '';

            return $html;

        }elseif ($return =='object'){

            return $job_categories;

        }
    }


}
