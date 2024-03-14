<?php

// add links the right side of the plugin list page
function pzat_plugin_row_meta( $links, $file ) {
	if ( strpos( $file, PZAT_PLUGIN_BASENAME) !== false ) {
		$new_links = array(
      'download_chrome_extension' => '<a href="https://chrome.google.com/webstore/detail/kcebcpmpalcalchafgbhehjblldcakjb/?utm_source=zoom_wp&utm_medium=plugin_list&utm_campaign=chrome-store" target="_blank" style="color:red;">Download Chrome Extension for a free demo</a>'
		);

		$links = array_merge( $links, $new_links );
	}

	return $links;
}
add_filter( 'plugin_row_meta', 'pzat_plugin_row_meta', 10, 2 );

?>
