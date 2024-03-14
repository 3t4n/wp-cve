<?php
add_action('admin_menu', 'RV_add_admin_menu');
add_action('admin_init', 'RV_settings_init');

function RV_add_admin_menu()
{
    add_menu_page(
        'ResponsiveVoice Settings',
        'ResponsiveVoice Text to Speech',
        'manage_options',
        'responsivevoice-text-to-speech',
        'RV_options_page',
        plugin_dir_url(__FILE__) . '/images/responsivevoice-icon-20x20.png'
    );
}

function RV_settings_init()
{
    wp_enqueue_style('rv-style', plugin_dir_url(__FILE__) . 'css/responsivevoice-admin.css');

    register_setting('pluginPage', 'RV_settings', 'RV_settings_sanitize');

    /**
     * Section API Key
     */
    add_settings_section(
        'RV_pluginPage_section',
        null,
        'RV_settings_section_callback',
        'pluginPage'
    );

    add_settings_field(
        'RV_API_Key',
        __('API Key', 'wordpress'),
        'RV_text_api_key_render',
        'pluginPage',
        'RV_pluginPage_section'
    );
}

function RV_text_api_key_render()
{
    $options = get_option('RV_settings', '');
    $apiKey  = !empty($options) && array_key_exists('RV_text_api_key', $options) ? $options['RV_text_api_key'] : '';
    echo '<input class="form-control" type="text" name="RV_settings[RV_text_api_key]" value="' . $apiKey . '">';
}

function RV_settings_section_callback()
{
    echo "<h6 class='text-secondary text-center mt-2 mb-2'>You will need an API Key to use the entire range of voices available in ResponsiveVoice.</h6>";
    echo "<h6 class='text-secondary text-center mt-2 mb-4'>If you haven't created an account yet, <a href='https://responsivevoice.org/register' target='_blank'>click here</a>.</h6>";

}

function RV_options_page()
{
    $responsivevoiceLogo = plugin_dir_url(__FILE__) . '/images/responsivevoice-logo-300x58.png';

    echo settings_errors();

    echo <<<HTML
<div class="rvbs">
    <div class="container mt-3 px-0">
        <div class="row justify-content-center">
            <div class="row col-9 col-sm-6 col-xl-10">
                <img class="mx-auto d-block" src="{$responsivevoiceLogo}">
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="card bg-app col-sm-6 col-9 col-xl-10 p-0">
                <div class="card-header pb-2 border-0 border-bottom bg-light">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="dropdown">
                            <a class="nav-link nav-item bg-app active" style="margin-bottom: -2px; border-color: #dee2e6 #dee2e6 #e9ecef;">Settings</a>
                        </li>
                    </ul>
                </div>
                
                <div class="card-body p-3">
                    <form class="rvbs" action="options.php" method="post">
HTML;
                        // Wordpress hidden fields
                        settings_fields('pluginPage');

    echo <<<HTML
                        <div class="card text-dark col-12 mb-3 p-3">
                            <div class="card-block py-1">
HTML;
                                do_settings_sections('pluginPage');
    echo <<<HTML
                            </div>
                        </div>
HTML;

    echo '<div class="row">
                <button type="submit" class="btn btn-lg btn-primary mx-auto mb-2 mt-2">Save Settings</button>
            </div>';


    echo <<<'HTML'
                </form>
            </div>
            </div>
            </div>
        </div>
    </div>
</div>
HTML;
}

function RV_settings_sanitize( $data )
{


    $sanitizeTextFields = [
        'RV_text_api_key',
    ];

    foreach ( $sanitizeTextFields as $field ) {

        // Only strip tags on API Keys if they don't have a <script> tag, otherwise we extract by regexp at a later stage
        if ($field === 'RV_text_api_key' && stripos( $data[ $field ], '<script' ) === FALSE ) {
            $data[ $field ] = sanitize_text_field( $data[ $field ] );

            if(!$data[ $field ]){
                add_settings_error( 'RV_text_api_key', 'RV_text_api_key', "You have no value as the current API Key. The voice collection will be limited to available browser voices.", 'error' );
            }
        }

        // Sanitize all text fields by default
        if ( $data[ $field ] && $field !== 'RV_text_api_key' ) {
            $data[ $field ] = sanitize_text_field( $data[ $field ] );
        }
    }

    // Detect script tags from the ResponsiveVoice App control panel
    if ( $data[ 'RV_text_api_key' ] && preg_match( '/key=([0-9a-zA-Z]+)/i', $data[ 'RV_text_api_key' ], $found ) ) {
        $data[ 'RV_text_api_key' ] = $found[ 1 ];

        $warningMessage = 'Settings saved - we extracted your API Key from the pasted script tag.';
        add_settings_error( 'RV_text_api_key', 'RV_text_api_key', $warningMessage, 'success' );
    }

    return $data;
}
