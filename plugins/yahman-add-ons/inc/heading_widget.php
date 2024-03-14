<?php
defined( 'ABSPATH' ) || exit;

function yahman_addons_heading_widget($the_content,$post_type){


	$wrap_before = '<aside class="post_widget post_item fit_content mb_L">';
	$wrap_after = '</aside>';
	

	$pattern = '{<h2.*?>.+?<\/h2>}ismu';


	if ( preg_match_all( $pattern, $the_content, $result )) {
		if ( $result[0] ) {
			if ( isset($result[0][0]) && is_active_sidebar( $post_type.'_before_h2_no_1' )) {
				ob_start();
				dynamic_sidebar( $post_type.'_before_h2_no_1' );
				$before_h2 = ob_get_clean();
				$the_content  = str_replace($result[0][0], $wrap_before.$before_h2.$wrap_after.$result[0][0], $the_content);

			}
			if ( isset($result[0][1]) && is_active_sidebar( $post_type.'_before_h2_no_2' )) {
				ob_start();
				dynamic_sidebar( $post_type.'_before_h2_no_2' );
				$before_h2 = ob_get_clean();
				$the_content  = str_replace($result[0][1], $wrap_before.$before_h2.$wrap_after.$result[0][1], $the_content);
			}
			if ( isset($result[0][2]) && is_active_sidebar( $post_type.'_before_h2_no_3' ) ) {
				ob_start();
				dynamic_sidebar( $post_type.'_before_h2_no_3' );
				$before_h2 = ob_get_clean();
				$the_content  = str_replace($result[0][2], $wrap_before.$before_h2.$wrap_after.$result[0][2], $the_content);
			}
		}
	}


	return $the_content;

}

