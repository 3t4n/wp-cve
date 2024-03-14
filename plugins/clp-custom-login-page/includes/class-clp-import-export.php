<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
};


class CLP_Import_Export {

    /**
     * defineS options name which are attachments(image/video)
     * @since 1.2.0
	 * @return array
    **/
	private static function get_attachment_options() {
		return array(
			'clp_logo-image',
			'clp_background-pattern-custom',
			'clp_background-image',
			'clp_background-video_local',
			'clp_background-video_thumb'
		);
	}

    /**
     * Generates JSON with the CLP Settings
     * @since 1.2.0
    **/
    public static function clp_export_settings( ) {

		// verify nonce
		check_ajax_referer( 'clp-export-settings', 'nonce' );
		
		// verify user rights
		if ( !current_user_can('publish_pages') ) {
			die('Sorry, but this request is invalid');
		}

		$settings = array(
			'CLP_EXPORT'
		);

		$cust_settings = CLP_Import_Export::get_current_customizer_settings();
		$import_attachments = CLP_Import_Export::get_attachment_options();
		// replace img ids with URLs
		foreach ($import_attachments as $option) {
			$cust_settings[$option] = wp_get_attachment_url($cust_settings[$option]);
		}
	
		array_push($settings, $cust_settings);

		$settings = json_encode( $settings );

		$replace = array('https://', 'http://');
		$home_url = str_replace($replace, '', get_home_url());

		if ( !empty($settings) ) {
			$filename =  $home_url. '-clp-settings-' . date('Y-m-d') . '.json';

			header('Content-Type: application/json');
			header('Content-Disposition: attachment;filename=' . $filename);

			$fp = fopen('php://output', 'w');

			fwrite($fp , $settings);
			fclose($fp);
		}
		die();
	}
	
    /**
     * Resets CLP Customizer settings to the default state
     * @since 1.2.0
    **/
    public static function clp_reset_settings( ) {

		// verify nonce
		check_ajax_referer( 'clp-reset-settings', 'nonce' );
		
		// verify user rights
		if ( !current_user_can('publish_pages') ) {
			die('Sorry, but this request is invalid');
		}
		
		$settings = CLP_Import_Export::get_default_customizer_settings();

		foreach ($settings as $option => $value) {
			delete_option($option);
		}

		wp_die();

	}
    /**
     * Import CLP Customizer settings from JSON file
     * @since 1.2.0
    **/
    public static function clp_import_settings( ) {
		// verify nonce
		check_ajax_referer( 'clp-import-settings', 'nonce' );
		
		// verify user rights
		if ( !current_user_can('publish_pages') ) {
			die('Sorry, but this request is invalid');
		}
		
		$settings = json_decode( stripslashes($_POST['json']), true );
		$import_attachments = CLP_Import_Export::get_attachment_options();
		if ( json_last_error() == JSON_ERROR_NONE ) {
			if ( $settings[0] === 'CLP_EXPORT' ) {
				// remove first value used for JSON CLP Settings check
				unset($settings[0]);
				foreach ($settings[1] as $option => $value) {
					
					// if image URL or video URL, import the attachment and save as attachment ID
					if ( in_array($option, $import_attachments) ) {
						if ( $value ) {
							$value = CLP_Helper_Functions::insert_attachment_from_url($value);
						}
					}

					update_option($option, $value);
				}
				wp_die('success');
			}

		}

		wp_die('error');
		

	}
	
    /**
     * Function to get all customizer settings to export
     * @since 1.2.0
    **/
    private static function get_current_customizer_settings() {

		$customizer_settings = new CLP_Customizer_Settings();

        $settings = $customizer_settings->get_settings_fields();
        $curr_settings = [];
 
        foreach ( $settings as $section => $setting ) {
            if ( $section !== 'import_export' ) {
                $i = 0;
                foreach ($setting['fields'] as $id => $name) {
                    if ( substr_compare( $name['id'], 'separator', -strlen( 'separator' ) ) !== 0 ) {
                        $value = get_option($name['id']) === false ? $settings[$section]['fields'][$i]['default'] : get_option($name['id']);
                        $curr_settings[$name['id']] = $value;
                    }
                    $i++;
                }
            }
        }

        return $curr_settings;
    }

    /**
     * Get all customizer default settings for reset
	 * @since 1.2.0
    **/
    public function get_default_customizer_settings() {

		$customizer_settings = new CLP_Customizer_Settings();

        $settings = $customizer_settings->get_settings_fields();
        $default_settings = [];
 
        foreach ( $settings as $section => $setting ) {
            if ( $section !== 'import_export' ) {
                foreach ($setting['fields'] as $id => $name) {
                    if ( substr_compare( $name['id'], 'separator', -strlen( 'separator' ) ) !== 0 ) {
                        $default_settings[$name['id']] = $name['default'];
                    }
                }
            }
        }

        return $default_settings;
    }
}