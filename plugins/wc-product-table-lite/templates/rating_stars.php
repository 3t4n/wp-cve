<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$rating_number = $GLOBALS['wcpt_rating_number'];
$max_rating_score = 5;

// if( ! $rating_number ){
// 	return;
// }

$full_stars  = floor( $rating_number );

$dec = $rating_number - $full_stars;

if( $dec < .25 ){
	$half_stars = 0;

}else if( $dec > .75 ){
	$half_stars = 0;
	++$full_stars;

}else{
	$half_stars = 1;

}

$empty_stars = $max_rating_score - $full_stars - $half_stars;

$fill = '#FFC107';

$fill_style = "";

if( 
	function_exists('wcpt_get_rating_stars_highlight_color') &&
	! empty( $highlight_color_range )
){
	$default_color = $fill;

	if( 
		! empty( $style ) &&
		! empty( $style['[id] .wcpt-star:not(.wcpt-star-empty) > svg:first-child'] )
	){
		$default_color = $style['[id] .wcpt-star:not(.wcpt-star-empty) > svg:first-child'];
	}

	$fill = wcpt_get_rating_stars_highlight_color( $highlight_color_range, $rating_number, $default_color );

	$fill_style = "style='color: $fill;'";
}


ob_start();
foreach ( array( $full_stars, $half_stars, $empty_stars ) as $key => $star_type ) {
    while ($star_type) {
      if ($key === 0) {
				?><i class="wcpt-star wcpt-star-full">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" <?php echo $fill_style; ?>><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
				</i><?php
      } else if ($key === 1) {
				?><i class="wcpt-star wcpt-star-half">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" <?php echo $fill_style; ?>><polygon points="12 2 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#aaa"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77"></polygon></svg>
				</i><?php
      } else {
				?><i class="wcpt-star wcpt-star-empty">
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#aaa"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
				</i><?php
      }

      --$star_type;
    }
}
$rating_stars = ob_get_clean();

echo '<div class="wcpt-rating-stars '. $html_class .'">' . $rating_stars . '</div>';
