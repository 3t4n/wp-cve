<?php
	// Create a about us page 
	 $post = array(
		  'comment_status' => 'closed',
		  'ping_status' =>  'closed' ,
		  'post_author' => 1,
		  'post_date' => date('Y-m-d H:i:s'),
		  'post_name' => 'About',
		  'post_status' => 'publish' ,
		  'post_title' => 'About',
		  'post_type' => 'page',
		  'post_content' => '<div class="col-md-6 col-sm-6 col-xs-12">
		  <div class="about-img-area" style="visibility: visible;"><img class="img-responsive" src="'. ICYCP_PLUGIN_URL .'inc/industryup/images/portfolio/portfolio4.jpg" alt="Image" /></div>
			</div>
			<div class="col-md-6 col-sm-6 col-xs-12" style="visibility: visible;">
			<h2>About Company</h2>
			Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
			</div>'
	);  
	//insert page and save the id
	$newvalue = wp_insert_post( $post, false );
	if ( $newvalue && ! is_wp_error( $newvalue ) ){
		update_post_meta( $newvalue, '_wp_page_template', 'templates/about.php' );
	}
?>