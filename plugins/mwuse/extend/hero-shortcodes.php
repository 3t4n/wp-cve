<?php
function mwuse_hero_shortcodes( $dom )
{
	$pattern = get_shortcode_regex();
	$array_to_change = array();
	foreach ( $dom->getElementsByTagName('p') as $p_key => $p) {
		preg_match_all( '/'. $pattern .'/s', $p->nodeValue , $matches2 );
		if ( @count( $matches2[0][0] ) ) 
		{
			$array_to_change[] = $p;
		}						
	}
	foreach ( $array_to_change as $t_key => $to_change ) {
		$div = mw_dom_change_node_tagName($to_change, 'div');
		mw_dom_add_class( $div, 'shortcode' );
	}
}
add_action( 'mw_DOMDocument_loaded' , 'mwuse_hero_shortcodes', 0, 1);
?>