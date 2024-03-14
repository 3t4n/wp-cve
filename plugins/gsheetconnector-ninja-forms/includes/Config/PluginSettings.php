<?php if ( ! defined( 'ABSPATH' ) ) exit;


$settings['njgs_api_base_url'] = array(
    'id'    => 'njgs_api_base_url',
    'type'  => 'textbox',
    'label' => __( 'API Base URL', 'ninja-forms-njgs' ),
    'desc'  => __( 'Enter your njgs Base Url' ),
);

$settings['njgs_api_version'] = array(
    'id'    => 'njgs_api_version',
    'type'  => 'select',
    'options' => array(
        array(
            'label' => __( 'Select One', 'ninja-forms' ),
            'value' => '',
        ),
        array(
            'label' => __( 'OAuth1a', 'ninja-forms' ),
            'value' => 'OAuth1a',
        ),
        array(
            'label' => __( 'OAuth2', 'ninja-forms' ),
            'value' => 'OAuth2',
        ),
    ),
    'label' => __( 'API OAuth Version', 'ninja-forms-njgs' ),
    'desc'  => __( 'Enter your njgs Authentication Version (e.g. OAuth1a or OAuth2' ),
);

$settings['njgs_api_client_key'] = array(
    'id'    => 'njgs_api_client_key',
    'type'  => 'textbox',
    'label' => __( 'API Client Key', 'ninja-forms-njgs' ),
    'desc'  => __( 'Enter your API Client Key' ),
);

$settings['njgs_api_client_secret'] = array(
    'id'    => 'njgs_api_client_secret',
    'type'  => 'textbox',
    'label' => __( 'API Client Secret', 'ninja-forms-njgs' ),
    'desc'  => __( 'Enter your njgs Client Secret' ),
);

$settings['njgs_api_callback'] = array(
    'id'    => 'njgs_api_callback',
    'type'  => 'textbox',
    'label' => __( 'API Callback', 'ninja-forms-njgs' ),
    'desc'  => __( 'OPTIONAL: Enter your njgs Callback. Will use this page if not entered.' ),
);

$settings['njgs_authorize'] = array(
    'id'    => 'njgs_authorize',
    'type'  => 'html',
    'label' => __( 'Click button to authorize njgs', 'ninja-forms-njgs' ),
    'html' => '<button type="submit" id="ninja_forms[njgs_authorize]" name="ninja_forms[njgs_authorize]" class="button-primary" value="1">'
        . __(
            (Ninja_Forms()->get_setting('njgs_api_access_token') && Ninja_Forms()->get_setting('njgs_api_access_token_secret') ? 'Re-' : '')
        . 'Authorize', 'ninja-forms-njgs' )
        . '</button>'
);

if (!strlen(Ninja_Forms()->get_setting('njgs_api_access_token')) && !isset($_GET['oauth_verifier']) && !isset($_GET['state'])) {

    $settings['njgs_api_access_token'] = array(
        'id'    => 'njgs_api_access_token',
        'type'  => 'textbox',
        'label' => __( 'API Access Token', 'ninja-forms-njgs' ),
        'desc'  => __( 'The access token when fetched or manually entered' ),
    );

    $settings['njgs_api_access_token_secret'] = array(
        'id'    => 'njgs_api_access_token_secret',
        'type'  => 'textbox',
        'label' => __( 'API Access Token Secret', 'ninja-forms-njgs' ),
        'desc'  => __( 'The access token secret when fetched or manually entered' ),
    );
} else {
    $settings['njgs_deauthorize'] = array(
        'id'    => 'njgs_deauthorize',
        'type'  => 'html',
        'label' => __( 'Click button to revoke njgs', 'ninja-forms-njgs' ),
        'html' => '<button type="submit" id="ninja_forms[njgs_deauthorize]" name="ninja_forms[njgs_deauthorize]" class="button-primary" value="1">'
            . __( 'De-Authorize', 'ninja-forms-njgs' ). '</button>'
    );

}

$settings['njgs_api_last_status'] = array(
    'id'    => 'njgs_api_last_status',
    'type'  => 'html',
    'label' => __( 'API - Last Status', 'ninja-forms-njgs' ),
    'html' => 'Status: ' . Ninja_Forms()->get_setting('njgs_api_last_status'),
);

return apply_filters( 'ninja_forms_njgs_plugin_settings', $settings );
