<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if( empty( $decimals ) ){
	$decimals = 1;
}

if( empty( $dec_point ) ){
	$dec_point = '.';
}

$rating_number = number_format( $GLOBALS['wcpt_rating_number'], $decimals, $dec_point, '' );

?>
<div class="wcpt-average-rating <?php echo $html_class; ?>"><?php echo $rating_number; ?></div>
