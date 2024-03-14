<?php

if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
	add_action('wpcf7_before_send_mail', 'wpcf7_custom_send_to_api');

	function wpcf7_get_additional_setting($form_cf, $additional_setting_key) {
		$additional_settings_arr = $form_cf->additional_setting($additional_setting_key);
		$additional_settings_val = trim(($additional_settings_arr[0] ?? ''));
		return $additional_settings_val;
	}
	function wpcf7_replace_placeholder($submitted_data, $inputdata, $urlencodeflag) {
		if (!empty($inputdata)) {
			$inputdata = preg_replace("/\\\/", "", $inputdata);
			foreach ($submitted_data as $sub_k => $sub_v) {
				if ($urlencodeflag) {
					$sub_v = urlencode($sub_v);
				}
				$inputdata = preg_replace("/#$sub_k#/", $sub_v, $inputdata);
			}
		}
		return $inputdata;
	}
	function wpcf7_custom_send_to_api($contact_form) {
		$submission = WPCF7_Submission::get_instance();
		if ($submission) {
			$form = $submission->get_contact_form();
			if ( $form ) {	$form_id = $form->id();	}	
			if (function_exists('wpcf7_contact_form')) {
				$form_cf = wpcf7_contact_form($form_id);
				if (WP_DEBUG) {    error_log('CF7 FormId: '.$form_id);   }
				if (empty($form_cf)) {
					if (WP_DEBUG) {    error_log('CF7: empty Form'.$form_id);   }
				} else {
					$submitted_data = $submission->get_posted_data();

					$additional_settings_url = wpcf7_get_additional_setting($form_cf, 'jci_url');
					if (empty($additional_settings_url)) {
						# no url set, no action
						if (WP_DEBUG) {    error_log('CF7 jci_url set: no URL set, no action');   }
						return TRUE;
					}
					if (WP_DEBUG) {    error_log('CF7 jci_url set: '.$additional_settings_url);   }
					$additional_settings_url = wpcf7_replace_placeholder($submitted_data, $additional_settings_url, TRUE);
					if (WP_DEBUG) {    error_log('CF7 jci_url used: '.$additional_settings_url);   }
					
					$additional_settings_timeout = wpcf7_get_additional_setting($form_cf, 'jci_timeout');
					if (empty($additional_settings_timeout)) {
						$additional_settings_timeout = 5;
					}
					if (WP_DEBUG) {    error_log('CF7 jci_timeout: '.$additional_settings_timeout);   }
					
					$additional_settings_header = wpcf7_get_additional_setting($form_cf, 'jci_header');
					$additional_settings_header = wpcf7_replace_placeholder($submitted_data, $additional_settings_header, FALSE);
					$header_arr = array();
					if (!empty($additional_settings_header)) {
						$additional_settings_header_arr = explode(";", $additional_settings_header);
						foreach ($additional_settings_header_arr as $header_item) {
							$header_tmp_arr = explode(":", $header_item, 2);
							$header_arr[$header_tmp_arr[0]] = $header_tmp_arr[1];
						}
					}
					if (WP_DEBUG) {    error_log('CF7 jci_header: '.$additional_settings_header);   }
					if (WP_DEBUG) {    error_log('CF7 jci_header JSON: '.json_encode($header_arr));   }
					
					$additional_settings_method = wpcf7_get_additional_setting($form_cf, 'jci_method');
					if ("post"!=$additional_settings_method) { $additional_settings_method = "get"; }
					if (WP_DEBUG) {    error_log('CF7 jci_method: '.$additional_settings_method);   }
					
					$additional_settings_payload = wpcf7_get_additional_setting($form_cf, 'jci_payload');
					$additional_settings_payload = wpcf7_replace_placeholder($submitted_data, $additional_settings_payload, FALSE);
					if (WP_DEBUG) {    error_log('CF7 jci_payload: '.$additional_settings_payload);   }
					
					if (!empty($additional_settings_url)) {
						######################
						$args = array(
							'headers'     => $header_arr,
							'sslverify'   => FALSE
						);
						if (!empty($additional_settings_timeout)) {
							$args['timeout'] = $additional_settings_timeout;
						}
						
						
						if ("post"==$additional_settings_method) {
							# post
							$args['body'] = $additional_settings_payload;
							$response = wp_remote_post( $additional_settings_url, $args );
						} else {
							# get
							$response = wp_remote_get( $additional_settings_url, $args );
						}
						if ( is_wp_error( $response ) ) {
							$error_message = $response->get_error_message();
							if (WP_DEBUG) {    error_log('CF7 response error: '.$error_message );   }
						} else {
							$body = wp_remote_retrieve_body( $response );
							#$data = json_decode( $body );
							if (WP_DEBUG) {    error_log('CF7 response ok: '.$body );   }
						}
					}
				}
				
				# save in file
				/*
				$addsetings_jcifree_pathfile = wpcf7_get_additional_setting($form_cf, 'jci_filepath');
				if (WP_DEBUG) {    error_log('CF7 jci_filepath: '.$addsetings_jcifree_pathfile);   }
				
				$addsetings_jcifree_filecontent = wpcf7_get_additional_setting($form_cf, 'jci_filecontent');
				if (WP_DEBUG) {    error_log('CF7 jci_filecontent: '.$addsetings_jcifree_filecontent);   }

				if (!empty($addsetings_jcifree_pathfile) && !empty($addsetings_jcifree_filecontent)) {
					if(preg_match("/^\//", $addsetings_jcifree_pathfile)) {
						$addsetings_jcifree_pathfile = "/".$addsetings_jcifree_pathfile;
					}
					foreach ($submitted_data as $sub_k => $sub_v) {
						$addsetings_jcifree_filecontent = preg_replace("/#$sub_k#/", $sub_v, $addsetings_jcifree_filecontent);
					}
					$file_path = wp_upload_dir()['basedir'].$addsetings_jcifree_pathfile;
					if (!file_exists(dirname($file_path))) { wp_mkdir_p(dirname($file_path)); }
					if (file_put_contents($file_path, $addsetings_jcifree_filecontent, FILE_APPEND) !== FALSE) {
					};
				}
				*/
			}
		}
    }
}
?>