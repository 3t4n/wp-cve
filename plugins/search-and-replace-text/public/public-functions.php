<?php
/*
* Apply filters on public page
*/
function far_ob_call( $buffer ) {
	$fnrt = get_option( 'fnrt_data' );
	if ( is_array( $fnrt['search'] ) ) {
		foreach ( $fnrt['search'] as $key => $find )  {
			$replaceword = esc_html($fnrt['replace'][$key]);
			if (is_page($fnrt['page'][$key]) ){
				$buffer = str_replace( $find,$replaceword, $buffer );
			}
			if ($fnrt['page'][$key] == 'allpage') {
				$buffer = str_replace( $find,$replaceword, $buffer );
			}	
		}
	}
	return $buffer;
}
function fnrt_redirect() {
	ob_start();
	ob_start( 'far_ob_call' );
}


