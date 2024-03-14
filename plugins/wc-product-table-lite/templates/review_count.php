<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$review_count = $GLOBALS['wcpt_review_count'];

if( ! $review_count ){
	return;
}

if( ! empty( $brackets ) ){
	$review_count = '('. $review_count .')';
}

echo '<div class="wcpt-review-count '. $html_class .'">' . $review_count . '</div>';
