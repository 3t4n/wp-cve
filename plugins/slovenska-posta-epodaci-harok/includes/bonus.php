<?php

/*
* Manage license
*/
add_action( 'wp_ajax_tsseph_manage_license', 'tsseph_manage_license'); 

function tsseph_manage_license() {

    global $tsseph_bonus_options;

    $tsseph_bonus_options= get_option( 'tsseph_bonus_options' );

    if(isset($_POST['task'])) { $task = sanitize_text_field($_POST['task']);} else $task = 0;	
    if(isset($_POST['license_key'])) { $license_key = sanitize_text_field($_POST['license_key']);} else $license_key = 0;
    if(isset($_POST['extension_id'])) { $extension_id = intval($_POST['extension_id']);} else $extension_id = 0;

    //Get extension XML markup
    $bonus = json_decode(file_get_contents(SPIRIT_EPH_PLUGIN_PATH . '/src/bonus.json'));
    $sel_extension = 0;

    foreach($bonus->extension as $extension) {
    
        if ($extension->id == $extension_id) {
            $sel_extension = $extension;
        }

    }

    //Activate task
    if ($task == 'activate') {

        $site_url = str_replace(array('http://','https://'),'',get_site_url());

        $api_params = array(
            'slm_action' => 'slm_activate',
            'secret_key' => '604d11a5c09165.28013117',
            'license_key' => $license_key,
            'registered_domain' => $site_url
        );
            
        // Send query to activate license
        $response = wp_remote_get(add_query_arg($api_params, "https://matejpodstrelenec.sk"), array('timeout' => 20, 'sslverify' => false));
                    
        // License data.
        $license_data = json_decode(wp_remote_retrieve_body($response));

        if($license_data->result == 'success') {
            $tsseph_bonus_options[$extension_id] = array(
                'LicenseKey' => $license_key,
                'LicenseStatus' => 1,
                'Enabled' => 1
            );
            $lc_message = __( 'Rozšírenie bolo úspešne aktivované.', 'spirit-eph' );
        } else {
            $lc_message = tsseph_get_lc_message($license_data->error_code);
        }
    }
    //Deactivate task
    else if ($task == 'deactivate') {

        $site_url = str_replace(array('http://','https://'),'',get_site_url());

        $api_params = array(
            'slm_action' => 'slm_deactivate',
            'secret_key' => '604d11a5c09165.28013117',
            'license_key' => $license_key,
            'registered_domain' => $site_url,
            );
            
            // Send query to activate license
            $response = wp_remote_get(add_query_arg($api_params, "https://matejpodstrelenec.sk"), array('timeout' => 20, 'sslverify' => false));
            
            // License data.
            $license_data = json_decode(wp_remote_retrieve_body($response));

            if($license_data->result == 'success') {
                $tsseph_bonus_options[$extension_id] = [
                    'LicenseKey' => '',
                    'LicenseStatus' => 0,
                    'Enabled' => 0
                ];
                $lc_message = __( 'Rozšírenie bolo úspešne deaktivované.', 'spirit-eph' );
            } else {
                $tsseph_options['LicenseStatus'] = 0;
                $lc_message = tsseph_get_lc_message($license_data->error_code);
            }
    }

    update_option('tsseph_bonus_options', $tsseph_bonus_options);

    echo json_encode(array(
        'lc_result' => $license_data->result,
        'lc_message' => $lc_message,
        'task' => $task,
        'extension_block' => tsseph_get_bonus_extension($sel_extension, $tsseph_bonus_options)
    ));

    wp_die();
}

/*
* License error codes
*/
function tsseph_get_lc_message($error_code) {

	$lc_message = "";

	switch($error_code) {
		case 10:
			$lc_message = "Služba zlyhala. (Code: 10)";
			break;
		case 20:
			$lc_message = "Aktivačný kľúč je zablokovaný. (Code: 20)";
			break;
		case 30:
			$lc_message = "Aktivačný kľúč expiroval. (Code: 30)";
			break;
		case 40:
			$lc_message = "Aktivačný kľúč sa už používa. (Code: 40)";
			break;			
		case 50:
			$lc_message = "Aktivačný kľúč môže byť použitý iba na jednej doméne. (Code: 50)";
			break;	
		case 60:
			$lc_message = "Neplatný aktivačný kľúč. (Code: 60)";
			break;	
		case 70:
			$lc_message = "Neplatný aktivačný kľúč. (Code: 70)";
			break;	
		case 80:
			$lc_message = "Neplatný aktivačný kľúč. (Code: 80)";
			break;
		case 90:
			$lc_message = "Neplatný aktivačný kľúč. (Code: 90)";
			break;
		case 100:
			$lc_message = "Neplatný aktivačný kľúč. (Code: 100)";
			break;
		case 110:
			$lc_message = "Aktivačný kľúč môže byť použitý iba na jednej doméne. (Code: 110)";
			break;	
		default:
			$lc_message = "Neplatný aktivačný kľúč. (Code: W1)";																		
	}

	return $lc_message;
}

/*
* Get list of bonus items
*/
function tsseph_get_bonus($tsseph_bonus_options) {

    $bonus = json_decode(file_get_contents(SPIRIT_EPH_PLUGIN_PATH . '/src/bonus.json'));

    $html = '';

    foreach($bonus->extension as $extension) {
        
        $html .= tsseph_get_bonus_extension($extension, $tsseph_bonus_options);

    }

    return $html;

}

/*
* Get single Bonus extension
*/
function tsseph_get_bonus_extension($extension, $tsseph_bonus_options) {

    $html = "";

    $html .='<div id="ext_' . $extension->id . '" class="tsseph-bonus">';
        $html .='<div class="tsseph-bonus-wrap">';
            $html .='<label class="switch">';
                $html .='<input type="checkbox" name="ext_check_' . $extension->id .'" ' . (isset($tsseph_bonus_options[$extension->id]['Enabled']) && $tsseph_bonus_options[$extension->id]['Enabled'] ? 'checked' : '' ) . ' ' . (isset($tsseph_bonus_options[$extension->id]['LicenseStatus']) && $tsseph_bonus_options[$extension->id]['LicenseStatus'] ? '' : 'disabled' ) . '>';
                $html .='<span class="ephslider round"></span>';
                $html .='<input type="hidden" name="ext_id_' . $extension->id . '" class="ext-id" value="' . $extension->id . '">';
            $html .='</label>';
            $html .='<span>';
                $html .='<span class="activate" onclick="tsseph_show_activate_form(this)">' . $extension->name . '</span>';
            $html .='</span>';
        $html .='</div>';
        $html .='<div class="tsseph-activate-wrap">';
            $html .='</br>';
            $html .='<div class="tsseph-activate-form">';
                $html .='<label for="ext_lic_key_' . $extension->id . '">Licencia:</label>';
                $html .='<input name="ext_lic_key_' . $extension->id . '" value="' . (isset($tsseph_bonus_options[$extension->id]['LicenseKey']) ? $tsseph_bonus_options[$extension->id]['LicenseKey'] : '' ) . '" type="text"/>';
            $html .='</div>';
            $html .='</br>';   
            $html .='<div class="tsseph-activate-buttons">';
                if (isset($tsseph_bonus_options[$extension->id]['LicenseStatus']) && $tsseph_bonus_options[$extension->id]['LicenseStatus']) {
                    $html .='<button class="eph-deactivate">Deaktivovať</button>';
                }
                else {
                    $html .='<button class="eph-get-key"  onclick=" window.open(\'' . $extension->url . '\',\'_blank\')">Získať kľúč</button>';
                    $html .='<button class="eph-activate">Aktivovať</button>';
                }
            
                $html .='<span class="ajax-loader"></span>';
            $html .='</div>';        
        $html .='</div>';
    $html .='</div></br>';

    return $html;

}

/*
*	Ajax callback to update Bonus extension 
*/ 
function tsseph_bonus_ext_status() {
    if(isset($_POST['ext_id'])) { $ext_id = absint($_POST['ext_id']);} else $ext_id = 0;
    if(isset($_POST['ext_status'])) { $ext_status = absint($_POST['ext_status']);} else $ext_status = 0;

    echo $ext_status;

    $tsseph_bonus_options = get_option( 'tsseph_bonus_options' );

    if ( $tsseph_bonus_options[$ext_id]['LicenseStatus']) {
        $tsseph_bonus_options[$ext_id]['Enabled'] = $ext_status;
    }

    update_option('tsseph_bonus_options', $tsseph_bonus_options);

    wp_die();
}
add_action('wp_ajax_tsseph_bonus_ext_status', 'tsseph_bonus_ext_status');