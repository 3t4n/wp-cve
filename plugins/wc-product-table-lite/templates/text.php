<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if( ! $text ){
	return;
}

// echo '<span class="wcpt-text '. $html_class .'">' . htmlentities( $text, ENT_NOQUOTES ) . '</span>';
echo '<span class="wcpt-text '. $html_class .'">' . wcpt_esc_tag( $text ) . '</span>';
