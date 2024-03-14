<?php 



/**
 * Allow HTML in term (category, tag) descriptions
 */
foreach ( array( 'pre_term_description' ) as $filter ) {
	remove_filter( $filter, 'wp_filter_kses' );
	
}
 
foreach ( array( 'term_description' ) as $filter ) {
	remove_filter( $filter, 'wp_kses_data' );
}



  function mcdu_sotto_shortcode( $atts, $content = null ) {



	 $GLOBALS['mcdu_contenuto_salta'] =  $content ;



}





add_shortcode( 'mcdusaltasotto', 'mcdu_sotto_shortcode' );





function mcdu_salta_sotto ( ){






	if (isset($GLOBALS['mcdu_contenuto_salta'])) {







  $mcdu_scritta_salta =  ($GLOBALS['mcdu_contenuto_salta']);





 echo  '<div>' .  $mcdu_scritta_salta  . '</div>';




}





}







add_action( 'woocommerce_after_shop_loop', 'mcdu_salta_sotto' );











