<?php

if ( ! defined('ABSPATH')) exit;  // if direct access 

class class_job_bm_shortcodes_job_categories{
	
    public function __construct(){
		
		add_shortcode( 'job_bm_job_categories', array( $this, 'job_bm_job_categories' ) );

   		}

    public function job_bm_job_categories($atts, $content = null ) {



      $atts = shortcode_atts(
        array(
          'enable_thumb' => true,
          'enable_count' => true,
          'enable_link' => true,

          'max_count' => 9,
          'column_number' => '3|2|1',




          ),

        $atts
      );


      $atts = apply_filters('job_bm_job_categories_atts', $atts);


      $max_count = isset($atts['max_count']) ? $atts['max_count'] : 9;
      $enable_link = isset($atts['enable_link']) ? $atts['enable_link'] : true;
      $column_number = isset($atts['column_number']) ? $atts['column_number'] : 3;

        $column_number = explode( '|', $column_number);

        $column_number_lg = isset($column_number[0]) ? $column_number[0] : 3;
        $column_number_md = isset($column_number[1]) ? $column_number[1] : 2;
        $column_number_sm = isset($column_number[2]) ? $column_number[2] : 1;



    ob_start();



    do_action('job_bm_job_categories', $atts);

    $terms_query_args = array(
        'taxonomy' => 'job_category',
        'hide_empty' => false,
        'number' => $max_count
    );

    $terms_query_args = apply_filters('job_bm_job_categories_query_args', $terms_query_args, $atts);
    $terms = get_terms( $terms_query_args );

      ?>
      <div class="<?php echo apply_filters('job_bm_job_categories_wrapper_class','job-categories', $atts); ?>">

      <?php

      do_action('job_bm_job_categories_top', $atts);


      if(!empty($terms)){
        foreach ($terms as $term){

          $term_id = $term->term_id;

          $term_name = $term->name;
          $term_count = $term->count;
          $term_url = get_term_link($term_id);
          $term_thumb 	 		= get_term_meta($term_id,  'job_bm_thumb', true );
          $term_thumb_url	= wp_get_attachment_url( $term_thumb );

          //var_dump($term_id);

            $args = array('term_id' => $term_id, 'term' => $term,'atts' => $atts);

          ?>
          <div class="job-category">

            <?php if($enable_link): ?>

            <a href="<?php echo esc_url_raw($term_url); ?>">
            <?php endif;

            do_action('job_bm_job_categories_loop', $args);

            if($enable_link): ?>

              </a>
            <?php endif; ?>

          </div>
          <?php


        }
      }

      do_action('job_bm_job_categories_bottom', $atts);

      ?>

      </div>


      <style type="text/css">

        .job-categories{
          columns: auto <?php echo $column_number_lg; ?>;
          column-gap: 20px;
        }
        .job-category{
          margin-bottom: 20px;
          border: 1px solid #ddd;
            -webkit-column-break-inside: avoid;
            column-break-inside:avoid;
        }

        .job-category a{
            padding: 15px 20px;
            display: block;
        }

        .job-category-thumb{
          display: inline-block;
          width: 50px;
          vertical-align: middle;
          margin-right: 20px;
        }
        .job-category-title{
          display: inline-block;

        }
        .job-category-count{
          display: inline-block;

        }

        @media only screen and ( min-width: 0px ) and ( max-width: 767px ) {
            .job-categories{
                columns: auto <?php echo $column_number_sm; ?>;
            }
        }
        @media only screen and ( min-width: 768px ) and ( max-width: 1023px ) {
            .job-categories{
                columns: auto <?php echo $column_number_md; ?>;
            }
        }
        @media only screen and (min-width: 1024px ){
            .job-categories{
                columns: auto <?php echo $column_number_lg; ?>;
            }
        }

      </style>

      <?php


        return ob_get_clean();
    }

}
	
	new class_job_bm_shortcodes_job_categories();