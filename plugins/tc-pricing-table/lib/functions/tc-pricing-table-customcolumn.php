<?php

add_filter('manage_edit-tcpricingtable_columns', 'add_new_tcpricingtable_columns');
function add_new_tcpricingtable_columns($tcpricingtable_columns) {


  $new_columns= array(
    'cb' => '<input type="checkbox" />',
    'title' => __( 'Title' ),
    'shortcode' => __( 'shortcode' ),
    'author' => __( 'Author' ),
    'date' => __( 'Date' )
  );


    return $new_columns;
}

add_action('manage_tcpricingtable_posts_custom_column', 'manage_tcpricingtable_columns', 10, 2);

function manage_tcpricingtable_columns( $column,$post_ID) {
    switch ( $column ) {
	case 'shortcode' :
		global $post;
		$slug = '' ;
		$slug = $post->ID;
    $shortcode = '[tc-pricing-table  tableid="'.$slug.'"]';
    echo $shortcode;
    break;
    }
}


 ?>
