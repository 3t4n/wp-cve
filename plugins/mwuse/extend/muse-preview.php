<?php

function mtw_remove_muse_preview_elements($dom, $parent)
{
	$muse_previews = mw_dom_getElementsByClass( $dom , "muse-preview" );
	foreach ($muse_previews as $key => $muse_preview) {
		$muse_preview->parentNode->removeChild($muse_preview);
	}
}
add_action( 'DOMDocument_loaded', 'mtw_remove_muse_preview_elements', 15, 2 );

?>