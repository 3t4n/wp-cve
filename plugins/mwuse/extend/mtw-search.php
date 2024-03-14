<?php


function mtw_search($dom)
{
	$mtw_searchs = mw_dom_getElementsByClass( $dom , "mtw-search" );
	
	foreach ( $mtw_searchs as $key => $mtw_search ) 
	{
		$mtw_search->setAttribute( 'action', site_url() );
	}
}
add_action( 'DOMDocument_body_load', 'mtw_search', 10, 1 );

?>