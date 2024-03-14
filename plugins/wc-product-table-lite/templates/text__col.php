<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// echo '<span class="wcpt-text '. $html_class .'">' . htmlentities(  wcpt_general_placeholders__parse( $text ), ENT_NOQUOTES ) . '</span>';
echo '<span class="wcpt-text '. $html_class .'">' . wcpt_esc_tag(  wcpt_general_placeholders__parse( $text ) ) . '</span>';
