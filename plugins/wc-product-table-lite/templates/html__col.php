<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( ! $html ){
	return;
}

echo '<span class="wcpt-html '. $html_class .'">' . wcpt_general_placeholders__parse( $html ) . '</span>';
