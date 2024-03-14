<?php
if ( ! defined('ABSPATH')) exit;  // if direct access 


    $keywords = isset($atts['keywords']) ? $atts['keywords'] : '';
    $company_name = isset($atts['company_name']) ? $atts['company_name'] : '';
    $location = isset($atts['location']) ? $atts['location'] : '';
    $per_page = isset($atts['per_page']) ? $atts['per_page'] : '';
    $cat_ids = isset($atts['cat_ids']) ? $atts['cat_ids'] : '';



	$job_bm_job_login_page_id = get_option('job_bm_job_login_page_id');
	$job_bm_login_enable = get_option('job_bm_login_enable');	
	$job_bm_registration_enable = get_option('job_bm_registration_enable');
	$date_format = get_option( 'date_format' );
	$job_bm_list_per_page = (int) get_option('job_bm_list_per_page', 10);


    $job_bm_list_per_page = !empty($per_page) ? (int)$per_page : (int)$job_bm_list_per_page;


	$job_bm_archive_page_id = get_option('job_bm_archive_page_id');
	$job_bm_archive_page_link = get_permalink($job_bm_archive_page_id);
	$permalink_structure = get_option('permalink_structure');
		
	$class_job_bm_functions = new class_job_bm_functions();
	$job_type_list = $class_job_bm_functions->job_type_list();
	$job_status_list = $class_job_bm_functions->job_status_list();	
	
	$meta_query = array();
	$tax_query = array();
	$job_category = '';
	
	if(empty($permalink_structure)){ $permalink_joint = '&'; }
	else{ $permalink_joint = '?'; }

	if ( get_query_var('paged') ) {$paged = get_query_var('paged');} 
	elseif ( get_query_var('page') ) {$paged = get_query_var('page');} 
	else {$paged = 1;}




    $keywords = isset($_GET['keywords']) ? sanitize_text_field($_GET['keywords']) : $keywords;



	if( empty($location) && !empty($_GET['locations'])){

		$meta_query[] = array(
            'key' => 'job_bm_location',
            'value' => sanitize_text_field($_GET['locations']),
            'compare' => '=',
            );
		}
	else{
		
		if(!empty($location))
		$meta_query[] = array(
            'key' => 'job_bm_location',
            'value' => sanitize_text_field($location),
            'compare' => '=',
            );
		
		}





	if( empty($company_name) && !empty($_GET['company_name'])){

		$meta_query[] = array(
            'key' => 'job_bm_company_name',
            'value' => sanitize_text_field($_GET['company_name']),
            'compare' => '=',
            );
		
		}
	else{
		
		if(!empty($company_name))
		$meta_query[] = array(
		
            'key' => 'job_bm_company_name',
            'value' => sanitize_text_field($company_name),
            'compare' => '=',

            );
							
		}





	
	if(!empty($_GET['job_cat'])){
		
			$job_category = sanitize_text_field($_GET['job_cat']);
			
			//var_dump($job_category);
		
			$tax_query[] = array(
                'taxonomy' => 'job_category',
                'field'    => 'slug',
                'terms'    => $job_category,
                //'operator'    => '',
                );
		}


    if(!empty($cat_ids)){

        $cat_ids = explode(',', $cat_ids);

        //var_dump($job_category);

        $tax_query[] = array(
            'taxonomy' => 'job_category',
            'field'    => 'term_id',
            'terms'    => $cat_ids,
            //'operator'    => '',
        );
    }


	
	

	
	if( empty($job_type) && !empty($_GET['job_type'])){
		
		$meta_query_job_type = array();

		if(is_array($_GET['job_type'])){
			
			foreach($_GET['job_type'] as $type){
				
				$meta_query_job_type[] = array(
				
                    'key' => 'job_bm_job_type',
                    'value' => sanitize_text_field($type),
                    'compare' => '=',

                    );
				} 
			
			$meta_query = array_merge(array('relation' => 'OR'), $meta_query_job_type);

			}
		else{
				$meta_query[] = array(
				
                    'key' => 'job_bm_job_type',
                    'value' => sanitize_text_field($_GET['job_type']),
                    'compare' => '=',

                    );
			}
		}
	
	else{
		if(!empty($job_type))
		$meta_query[] = array(
		
            'key' => 'job_bm_job_type',
            'value' => sanitize_text_field($job_type),
            'compare' => '=',

            );
		}	
	


        if(empty($job_status) && !empty($_GET['job_status'])){

            $meta_query[] = array(

                'key' => 'job_bm_job_status',
                'value' => sanitize_text_field($_GET['job_status']),
                'compare' => '=',

                    );

            }
        else{

            if(!empty($job_status))
            $meta_query[] = array(

                    'key' => 'job_bm_job_status',
                    'value' => sanitize_text_field($job_status),
                    'compare' => '=',
                );
            }







        if(!empty($_GET['expire_date'])){

            $meta_query[] = array(
                    'key' => 'job_bm_expire_date',
                    'value' => sanitize_text_field($_GET['expire_date']),
                    'compare' => '=',
                );


        }

        $sticky_jobs = get_option( 'sticky_jobs' );

        $featured_query_args = array (
            'post_type' => 'job',
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC',
            'posts_per_page' => -1,
            'post__in' => $sticky_jobs,
        );

        //echo '<pre>'.var_export($sticky_jobs, true).'</pre>';
        //echo '<pre>'.var_export($featured_query_args, true).'</pre>';


        $query_args = array (
            'post_type' => 'job',
            'post_status' => 'publish',
            's' => $keywords,
            'orderby' => 'date',
            'meta_query' => $meta_query,
            'tax_query' => $tax_query,
            'order' => 'DESC',
            'posts_per_page' => $job_bm_list_per_page,
            'post__not_in' => $sticky_jobs,
            'paged' => $paged,
        );


        $query_args = apply_filters('job_bm_job_archive_query_args', $query_args);


        $atts['query_args'] = $query_args;

        $featured_query = new WP_Query($featured_query_args);

        $wp_query = new WP_Query($query_args);

        $wp_query = apply_filters('job_bm_job_archive_wp_query', $wp_query, $atts);




?>
        <div class="job-bm-archive">
            <?php

            do_action('job_bm_job_archive_loop_before', $wp_query, $atts);
            ?>
            <div class="job-list">
                <?php
                // action hook top the loop
                do_action('job_bm_job_archive_loop_top', $wp_query, $atts);




                if ( $featured_query->have_posts() && $paged ==1 && !empty($sticky_jobs)) :
                    $count = 1;

                    while ( $featured_query->have_posts() ) : $featured_query->the_post();

                        $job_id = get_the_ID();
                        $atts['loop_count'] = $count;

                        $job_bm_featured = get_post_meta($job_id, 'job_bm_featured', true);
                        $featured_class = ($job_bm_featured=='yes') ? 'featured' :'';

                        ?>
                        <div class="<?php echo apply_filters('job_bm_job_archive_loop_class','single '.$featured_class); ?>">
                            <?php
                            // action hook for loop
                            do_action('job_bm_job_archive_loop', $job_id, $atts);
                            ?>
                        </div>
                        <?php

                        $count++;
                    endwhile;
                endif;


            if ( $wp_query->have_posts() ) :
                $count = 1;

                while ( $wp_query->have_posts() ) : $wp_query->the_post();

                    $job_id = get_the_ID();
                    $atts['loop_count'] = $count;

                    $job_bm_featured = get_post_meta($job_id, 'job_bm_featured', true);
                    $featured_class = ($job_bm_featured=='yes') ? 'featured' :'';

                    ?>
                    <div class="<?php echo apply_filters('job_bm_job_archive_loop_class','single '); ?>">
                        <?php
                        // action hook for loop
                        do_action('job_bm_job_archive_loop', $job_id, $atts);
                        ?>
                    </div>
                    <?php

                    $count++;
                endwhile;

                // action hook bottom the loop
                do_action('job_bm_job_archive_loop_bottom', $wp_query, $atts);

                ?>
                </div>
                <?php
                do_action('job_bm_job_archive_loop_after', $wp_query, $atts);
                wp_reset_query();
            else:

                do_action('job_bm_job_archive_loop_no_post', $wp_query, $atts);

            endif;

            ?>

        </div>