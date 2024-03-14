<?php

// Integration with Contact Form 7 Multi-Step Forms. Webhead LLC.
if ( function_exists( 'cf7msm_fs' ) ) {
	add_action(
		'wp_enqueue_scripts',
		function() {
			wp_enqueue_script(
				'cf7ds_multi_step_forms',
				plugins_url( '../plugins-integration/contact-form-7-multi-step-forms/script.js', __FILE__ ),
				array( 'jquery' ),
				CF7_DATASOURCE::$version
			);
		}
	);
}
