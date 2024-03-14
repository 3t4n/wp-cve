<?php
	require_once IWP_PLUGIN_PATH . 'includes/iwpPluginOptions.php';

	//Plugin row metas
	add_filter('plugin_row_meta', 'row_meta_links', 10, 2);

	function row_meta_links( $meta_fields, $plugin ) {
		if ($plugin === IWP_PLUGIN_BASENAME) {
			$documentationUrl = 'https://documentation.iurny.com/docs/installation-in-3-steps';
			$documentationLabel = __('Documentation', 'iwp-text-domain');
			$meta_fields[] = "<a href='$documentationUrl' target='_blank'>$documentationLabel</a>";

			$supportUrl = 'mailto:support@indigitall.com';
			$supportLabel = __('Support', 'iwp-text-domain');
			$meta_fields[] = "<a href='$supportUrl' target='_blank'>$supportLabel</a>";
		}
		return $meta_fields;
	}

