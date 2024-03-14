<?php
require("hpbseo.php");

	delete_option(hpbseoClass::field_prefix . 'global_setting');

	$allposts = get_posts( 'numberposts=0&post_type=any&post_status=' );
	foreach( $allposts as $postinfo ) {
		delete_post_meta( $postinfo->ID, hpbseoClass::field_prefix . 'meta' );
	}

?>
