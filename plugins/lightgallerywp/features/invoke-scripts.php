<?php
/**
 * Function to generate Javascript compatible key value pairs of config value based on settings.
 *
 * @param array  $data The data.
 * @param string $field_name The field name / key.
 */
function lightgallerywp_get_setting_parameter( $data, $field_name ) {
	switch ( true ) {
		case strpos( $field_name, '_ignore' ) !== false:
			return '';
		case strpos( $field_name, '_string' ) !== false:
			$parameter_type = 'string';
			$parameter      = str_replace( '_string', '', $field_name );
			break;
		case strpos( $field_name, '_boolean' ) !== false:
			$parameter_type = 'boolean';
			$parameter      = str_replace( '_boolean', '', $field_name );
			break;
		case strpos( $field_name, '_number' ) !== false:
			$parameter_type = 'number';
			$parameter      = str_replace( '_number', '', $field_name );
			break;
		case strpos( $field_name, '_multioption' ) !== false:
			$parameter_type = 'multioption';
			$parameter      = str_replace( '_multioption', '', $field_name );
			break;
		default:
			$parameter_type = 'string';
			$parameter      = str_replace( '_string', '', $field_name );
			break;
	}
	$parameter = esc_attr( $parameter );
	if ( isset( $data ) && is_array( $data ) && isset( $data[ $field_name ] ) && ( '' !== $data[ $field_name ] ) ) {
		switch ( $parameter_type ) {
			case 'string':
				return $parameter . ': "' . esc_attr( $data[ $field_name ] ) . '",' . PHP_EOL;
			case 'boolean':
			case 'number':
				return $parameter . ': ' . esc_attr( $data[ $field_name ] ) . ',' . PHP_EOL;
			case 'multioption':
				return $parameter . ': [' . esc_attr( implode( ',', $data[ $field_name ] ) ) . '],' . PHP_EOL;
		}
	}
	return '';
}

/**
 * Function to get the names of active plugins based on the settings.
 *
 * @param array $settings Array of settings data.
 */
function lightgallerywp_get_active_plugins( $settings ) {
	return apply_filters( 'lightgallerywp_active_plugins', [ 'lgVideo' ], $settings );
}

/**
 * Enqueue necessary Styles and Scripts in the frontend
 */
add_action(
	'wp_enqueue_scripts',
	function() {
		$active_plugins = lightgallerywp_get_active_plugins( [] );
		wp_enqueue_script( 'justifiedgalleryjs', plugins_url( 'assets/js/jquery.justifiedGallery.min.js', dirname( __FILE__ ) ), [ 'jquery' ], '3.8.1', false );
		wp_enqueue_script( 'lightgalleryjs', plugins_url( 'assets/js/lightgallery.min.js', dirname( __FILE__ ) ), [], '1.0.3', false );
		if ( isset( $active_plugins ) && is_array( $active_plugins ) ) {
			foreach ( $active_plugins as $active_plugin ) {
				$plugin_file_name = strtolower( str_replace( 'lg', '', $active_plugin ) );
				wp_enqueue_script( 'lightgalleryjs-' . $plugin_file_name, plugins_url( 'assets/plugins/' . $plugin_file_name . '/lg-' . $plugin_file_name . '.min.js', dirname( __FILE__ ) ), [], '1.0.3', false );
				if ( 'video' === $plugin_file_name ) {
					wp_enqueue_script( 'lightgalleryvideo-vimeojs', plugins_url( 'assets/plugins/video/vimeo.player.min.js', dirname( __FILE__ ) ), [], '2.16.3', false );
					wp_enqueue_style( 'lightgalleryvideocss', plugins_url( 'assets/plugins/video/lg-video.css', dirname( __FILE__ ) ), [], '1.0.3', false );
				}
			}
		}
		wp_enqueue_style( 'justifiedgallerycss', plugins_url( 'assets/css/jquery.justifiedGallery.css', dirname( __FILE__ ) ), [], '3.8.1', false );
		wp_enqueue_style( 'lightgallerycss', plugins_url( 'assets/css/lightgallery.css', dirname( __FILE__ ) ), [], '1.0.3', false );
		wp_add_inline_script(
			'lightgalleryjs',
			'function lightgallerywp_document_ready(fn) {
				if (document.readyState === "complete" || document.readyState === "interactive") {
					setTimeout(fn, 1);
				} else {
					document.addEventListener("DOMContentLoaded", fn);
				}
			}'
		);
	},
	100
);

/**
 * Return the content of the File after processing.
 *
 * @param string $file File name.
 * @param array  $args Data to pass to the file.
 */
function lightgallerywp_load_file( $file, $args = [] ) {
	if ( ( '' !== $file ) && file_exists( dirname( __FILE__ ) . '/' . $file ) ) {
		//phpcs:disable
		// Usage of extract() is necessary in this content to simulate templating functionality.
		extract( $args );
		//phpcs:enable
		ob_start();
		include dirname( __FILE__ ) . '/' . $file;
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
}

