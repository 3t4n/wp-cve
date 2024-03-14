<?php


function cc_jetengine_importer_ifcj_special_notice( $html ){

	// Only display notice if Pro Pack isn't installed.
	if( !class_exists('PRO_IMPORTER_JETENGINE_Plugin') ) {
		return '';
	}
	
	$html = <<<EOT
	<div class="ifcj-special-notice">
	<h2>Coding Chicken JetEngine Importer</h2>
	
	A Pro Pack is now available which adds support for many additional fields. You also gain access to professional support. <a target="_blank" href="https://codingchicken.com/wp-all-import-add-on-for-crocoblock-jetengine/">Visit our site to see the latest offers and purchase the Pro Pack now!</a>
	</div>
EOT;

	return $html;
}