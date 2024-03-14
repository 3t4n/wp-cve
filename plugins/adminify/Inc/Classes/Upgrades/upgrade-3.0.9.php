<?php

/**
 * Update: Custom Header Footer CSS and JS
 */
function update_custom_header_footer_css_js() {
	$adminify_custom_header_footer_settings = get_option( '_wpadminify_custom_js_css', '' );

	if ( ! empty( $adminify_custom_header_footer_settings ) ) {
		if ( ! empty( $adminify_custom_header_footer_settings['custom_scripts'] ) ) {
            $data = [];
            $options = $adminify_custom_header_footer_settings['custom_scripts'];
			foreach ( $options as $key => $value ) {
                $css = $value['custom_css'];
                $css = str_replace( '<style>', '', $css );
                $css = str_replace( '</style>', '', $css );
                $value['custom_css'] = $css;

                $js = $value['custom_js'];
                $js = str_replace( '<script>', '', $js );
                $js = str_replace( '</script>', '', $js );
                $value['custom_js'] = $js;

                $data[$key] = $value; 
            }
            $adminify_custom_header_footer_settings['custom_scripts'] = $data;
            update_option( '_wpadminify_custom_js_css', $adminify_custom_header_footer_settings );
		}
        
	}
}
update_custom_header_footer_css_js();

/**
 * Update: Admin Custom CSS and JS saving error issue
 */
function update_custom_css_js_script() {
	$adminify_options_settings = get_option( '_wpadminify', '' );

	if ( ! empty( $adminify_options_settings ) ) {
		// Custom CSS
		if ( ! empty( $adminify_options_settings['custom_css'] ) ) {
			$css = $adminify_options_settings['custom_css'];
			$css = str_replace( '<style>', '', $css );
			$css = str_replace( '</style>', '', $css );
            $adminify_options_settings['custom_css'] = $css;
			update_option( '_wpadminify', $adminify_options_settings );
		}

		// Custom JS
		if ( ! empty( $adminify_options_settings['custom_js'] ) ) {
			$js = $adminify_options_settings['custom_js'];
			$js = str_replace( '<script>', '', $js );
			$js = str_replace( '</script>', '', $js );
            $adminify_options_settings['custom_js'] = $js;
			update_option( '_wpadminify', $adminify_options_settings );
		}
	}
}
update_custom_css_js_script();

/**
 * Update: Login Customizer CSS and JS saving error issue
 */
function update_jltwp_adminify_customizer_custom_css_js_script() {
	$adminify_login_customizer_settings = get_option( 'jltwp_adminify_login', '' );

	if ( ! empty( $adminify_login_customizer_settings ) ) {
		// Custom CSS
		if ( ! empty( $adminify_login_customizer_settings['jltwp_adminify_customizer_custom_css'] ) ) {
			$css = $adminify_login_customizer_settings['jltwp_adminify_customizer_custom_css'];
			$css = str_replace( '<style>', '', $css );
			$css = str_replace( '</style>', '', $css );
			$adminify_login_customizer_settings['jltwp_adminify_customizer_custom_css'] = $css;
			update_option( 'jltwp_adminify_login', $adminify_login_customizer_settings );
		}

		// Custom JS
		if ( ! empty( $adminify_login_customizer_settings['jltwp_adminify_customizer_custom_js'] ) ) {
			$js = $adminify_login_customizer_settings['jltwp_adminify_customizer_custom_js'];
			$js = str_replace( '<script>', '', $js );
			$js = str_replace( '</script>', '', $js );
			$adminify_login_customizer_settings['jltwp_adminify_customizer_custom_js'] = $js;
			update_option( 'jltwp_adminify_login', $adminify_login_customizer_settings );
		}
	}
}
update_jltwp_adminify_customizer_custom_css_js_script();


// update version once migration is completed.
update_option( $this->option_name, $version );
