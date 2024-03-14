<?php
	if( function_exists( 'curl_init') ) {
		$errorMessage										= false;
		$output												= '';

		try {
			$api_id			= get_option('sv_provenexpert_modules_settings_api_id');
			$api_key		= get_option('sv_provenexpert_modules_settings_api_key');

			if( get_transient( 'sv_provenexpert' ) ) {
				$data										= get_transient( 'sv_provenexpert' );
			} elseif( strlen( $api_id ) > 0 && strlen( $api_key ) > 0 ) {
				$curl = $this->get_parent()::$remote_get->create( $this );
				
				$auth = base64_encode( trim( $api_id ) . ':' . trim( $api_key ) );

				$curl
					->set_request_url( 'https://www.provenexpert.com/api_rating_v2.json?v=' . $this->get_version_core().'&id=straightvisions&type=wordpress-plugin' )
					->set_args(array(
						'timeout'		=> 3,
						'sslverify'		=> false,
						'headers'		=> array(
							'Authorization' => 'Basic '.$auth
						)
					) );
				
				$json										= $curl->get_response_body();

				// convert json to array
				$data										= json_decode( $json, true );

				if( !is_array( $data ) ) {
					error_log('SV ProvenExpert - API ERROR - Wrong JSON format - '.$json);
				}

				if( isset($data['errors']) && in_array( 'wrongPlan', $data['errors'] ) ) {
					error_log('SV ProvenExpert - API ERROR - Your current ProvenExpert Plan has no API access, please upgrade');
				}

				if( $data['status'] == 'success' ) {
					set_transient( 'sv_provenexpert', $data, 86400 );
					error_log('SV ProvenExpert - CACHE - filled successfully.'.var_export($data, true));
				}else {
					$tmp									= get_transient( 'sv_provenexpert' );

					if( !is_array( $tmp ) ) {
						error_log('SV ProvenExpert - API ERROR - The version is outdated, please update - No cached output available');
						return '';
					}

					$data								= $tmp;
					error_log('SV ProvenExpert - API ERROR - The version is outdated, please update - cached output available');
				}
			}else{
				error_log('SV ProvenExpert - API ERROR - Empty API Credentials.');
				return '';
			}

			// print aggregate rating html
			if(!isset($data)){
				error_log('SV ProvenExpert - API ERROR - No Data Found');
				return '';
			}

			if( isset( $data['errors'] ) && is_array( $data['errors'] ) ) {
				error_log('SV ProvenExpert - API ERROR - There were errors. '.implode( ', ', $data['errors'] ));
				return '';
			}

			if( !isset($data['status']) || $data['status'] != 'success' ) {
				error_log('SV ProvenExpert - API ERROR - Unsupported API Status '.var_export($data, true));
				return '';
			}

			$output										= $data['aggregateRating'];

		} catch( Exception $e ) {
			error_log('SV ProvenExpert - API ERROR - Exception Error. '.$e->__toString());
		}
	} else {
		error_log('SV ProvenExpert - API ERROR - The CURL package is not installed, please install CURL to use this plugin.');
	}

	// filter
	$output		= str_replace('@font-face{', '@font-face{font-display: swap;',  $output);
	preg_match('/<style(.*)?>(.*)?<\/style>/', $output, $match);
	$output		= str_replace($match[0], '', $output);

	$stars		= '
	<span class="sv_pe_stars" style="width:'.(round(floatval($data['ratingValue']*15),2)).'px;"><img width="15" height="15" src="'.$this->get_url('lib/frontend/img/star.svg').'" alt="" /><img width="15" height="15" src="'.$this->get_url('lib/frontend/img/star.svg').'" alt="" /><img width="15" height="15" src="'.$this->get_url('lib/frontend/img/star.svg').'" alt="" /><img width="15" height="15" src="'.$this->get_url('lib/frontend/img/star.svg').'" alt="" /><img width="15" height="15" src="'.$this->get_url('lib/frontend/img/star.svg').'" alt="" /></span>
	';

	$output		= str_replace('<span id="pe_stars">', '<span id="pe_stars">'.$stars, $output);

	echo '<div class="sv_provenexpert">' . $output . '</div>';