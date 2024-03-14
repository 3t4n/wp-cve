<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_filter('manage_edit-tctestimonial_columns', 'add_new_tctestimonial_columns');
function add_new_tctestimonial_columns($tctestimonial_columns) {


  $new_columns= array(
    'cb' => '<input type="checkbox" />',
    'title' => __( 'Title' ),
    'featured_image' => __( 'Client Image' ),
    'testimonial_cat'=>_('Testimonial Category'),
    'c_company'=>_('Client Company'),
    'author' => __( 'Author' ),
    'date' => __( 'Date' )
  );


    return $new_columns;
}

add_action('manage_tctestimonial_posts_custom_column', 'manage_tctestimonial_columns', 10, 2);

function tcodes_get_author_img($post_ID){
    $get_author_img_id = get_post_thumbnail_id($post_ID);

    return $get_author_img_url = wp_get_attachment_image_src($get_author_img_id, array(30,30), true);
}

 function manage_tctestimonial_columns( $column,$post_ID) {
    $author_img=tcodes_get_author_img($post_ID);
      switch ( $column ) {
    	case 'featured_image' :
  		global $post;
  		$slug = '' ;
  		$slug = $post->ID;
      $featured_image ='<img src="' . $author_img[0] . '"/>';
      echo $featured_image;
      break;
      case 'testimonial_cat' :
       $tct_cats = wp_get_post_terms($post_ID, 'tctestimonial_category', array("fields" => "names"));
         foreach ( $tct_cats as $tct_cat ) {
               echo $tct_cat.'<br>';

       }
        break;
        case 'c_company' :
       $tct_company_name=testimonial_author_s_info_get_meta( 'testimonial_author_s_info_company_name' );
            echo $tct_company_name;
            //echo 'test';
            //die($tct_company_name);

  }

}

 ?>
