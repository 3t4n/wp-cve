<?php

add_filter('manage_edit-tcpc_columns', 'add_new_tcpc_columns');
function add_new_tcpc_columns($tcpc_columns) {


  $new_columns= array(
    'cb' => '<input type="checkbox" />',
    'title' => __( 'Title' ),
    'featured_image' => __( 'Product Images' ),
    'tcpc_rprice'=>_('Regular Price'),
    'tcpc_sprice'=>_('Sale Price'),
    'tcpc_catalog'=>_('Product Catalog'),
    'tcpc_cat'=>_('Product Category'),
    'author' => __( 'Author' ),
    'date' => __( 'Date' )
  );


    return $new_columns;
}

add_action('manage_tcpc_posts_custom_column', 'manage_tcpc_columns', 10, 2);
function get_tcpc_img($post_ID)
{
    $tcpc_post_id = get_post_thumbnail_id($post_ID);
    return $tcpc_img_url = wp_get_attachment_image_src($tcpc_post_id, array(40,40), true);
}
function manage_tcpc_columns( $column,$post_ID) {
  $tcpc_img=get_tcpc_img($post_ID);
    switch ( $column ) {
	case 'featured_image' :
		global $post;
		$slug = '' ;
		$slug = $post->ID;
    $featured_image ='<img src="' . $tcpc_img[0] . '" width="90px"/>';
    echo $featured_image;
    break;

   case 'tcpc_rprice':
   $currency_icon =get_post_meta(get_the_ID(), 'tcpc_fields_currency_icon', true);
   $regular_price = get_post_meta(get_the_ID(), 'tcpc_fields_regular_price', true);
   echo $currency_icon.$regular_price;
  break;

   case 'tcpc_sprice':
   $currency_icon =get_post_meta(get_the_ID(), 'tcpc_fields_currency_icon', true);
   $sale_price = get_post_meta(get_the_ID(), 'tcpc_fields_sale_price', true);
   echo $currency_icon.$sale_price;
  break;
  case 'tcpc_cat' :
   $tcpc_cats =wp_get_post_terms($post_ID, 'tcpc_category', array("fields" => "names"));
     foreach ( $tcpc_cats as $tcpc_cat ) {
           echo $tcpc_cat.', ';

   }
    break;
  case 'tcpc_catalog':
  $tcpc_catalogs =wp_get_post_terms($post_ID, 'tcpc_catalog', array("fields" => "names"));
    foreach ( $tcpc_catalogs  as $tcpc_catalog ) {
          echo $tcpc_catalog ;

  }

    }
}


 ?>
