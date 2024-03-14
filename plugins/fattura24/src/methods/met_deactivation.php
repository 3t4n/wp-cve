<?php

namespace fattura24;

if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_ajax_fattura24_deactivation_reason', __NAMESPACE__ .'\fatt_24_deactivation_reason');

function fatt_24_get_reasons(){
    return array(
        'feature_missing' => array(
            'radio_val' => 'feature_missing',
            'radio_label' => __('A specific feature is missing', 'fattura24'),
            'reason_type' => 'text',
            'reason_placeholder' => __('Type in the feature', 'fattura24')
        ),
        'error_or_not_working'=> array(
            'radio_val'          => 'error_or_not_working',
            'radio_label'        => __('Found an error in the plugin/ Plugin was not working', 'fattura24'),
            'reason_type'        => 'text',
            'reason_placeholder' => __('Specify the issue', 'fattura24'),
        ),

        'hard_to_use' => array(
            'radio_val'          => 'hard_to_use',
            'radio_label'        => __('It was hard to use', 'fattura24'),
            'reason_type'        => 'text',
            'reason_placeholder' => __('How can we improve your experience?', 'fattura24'),
        ),
        'found_better_plugin' => array(
            'radio_val'          => 'found_better_plugin',
            'radio_label'        => __('I found a better plugin', 'fattura24'),
            'reason_type'        => 'text',
            'reason_placeholder' => __('Could you please explain in what this plugin is better?', 'fattura24'),
        ),
        'other' => array(
            'radio_val'          => 'other',
            'radio_label'        => __('Other', 'fattura24'),
            'reason_type'        => 'textarea',
            'reason_placeholder' => __('Kindly tell us your reason, so that we can improve', 'fattura24'),
        ),
    );
 }

function fatt_24_deactivation_reason() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce']), 'fatt24_deactivation_nonce')) {
        wp_die("page killed");
    }


    if (!isset($_POST['reason'])) {
        return;
    }

    $reason = sanitize_text_field($_POST['reason']);
    $comments = sanitize_text_field($_POST['comments']);
    $current_user = \wp_get_current_user();
    $user_name = $current_user->display_name;
    $user_email = $current_user->user_email;
    $source = FATT_24_API_SOURCE;
    $apiKey = get_option(FATT_24_OPT_API_KEY);
        $test = fatt_24_api_call('TestKey', array('apiKey' => $apiKey), FATT_24_API_SOURCE);
        $apiRes = is_array($test)? json_encode($test) : simplexml_load_string($test);
        $account_id = 0;
        if (is_object($apiRes)) {
            $account_id = (int) $apiRes->returnCode == 1 ? 
                         (int) $apiRes->subscription->accountId : 0;
        } 

    $subject = 'Feedback veloce disattivazione '. FATT_24_PLUGIN_DATA['Name'];
    $text = 'Ho disattivato ' . FATT_24_PLUGIN_DATA['Name'] . ' versione ' . FATT_24_PLUGIN_DATA['Version']
            . ' per questo motivo: ';
    
    switch ($reason) {
        case 'feature_missing':
            $text .= 'manca una funzionalità';
            break;
        case 'error_or_not_working':
            $text .= 'va in errore o non funziona';
            break;
        case 'hard_to_use':
            $text .= 'è difficile da usare';
            break;
        case 'found_better_plugin':
            $text .= 'ho trovato un plugin migliore';
            break;    
        default;
            $text .= '';
            break;
    }

    $text .= '<br />' . htmlentities($comments, ENT_HTML401, '');
    
    $content = [
        'username' => $user_name,
        'email' => $user_email,
        'source' => $source,
        'account_id' => $account_id,
        'subject' => $subject,
        'text' => $text
    ];
    
   fatt_24_send_ticket($content);
    
}