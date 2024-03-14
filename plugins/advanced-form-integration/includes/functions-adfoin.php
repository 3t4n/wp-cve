<?php

/**
* Redirects the user to a given URL.
* @param string $url The URL to redirect the user to.
* @return void
*/
function advanced_form_integration_redirect( $url )
{
    $string = '<script type="text/javascript">';
    $string .= 'window.location = "' . $url . '"';
    $string .= '</script>';
    echo  $string ;
}

/**
* Retrieves an array of supported form providers for the plugin.
* @return array An array containing the names and display labels of supported form providers.
*/
function adfoin_get_form_providers()
{
    $providers = array(
        'academylms'   => __( 'Academy LMS', 'advanced-form-integration' ),
        'affiliatewp'  => __( 'AffiliateWP', 'advanced-form-integration' ),
        'amelia'       => __( 'Amelia', 'advanced-form-integration' ),
        'arforms'      => __( 'ARForms', 'advanced-form-integration' ),
        'armember'     => __( 'ARMember', 'advanced-form-integration' ),
        'beaver'       => __( 'Beaver Builder', 'advanced-form-integration' ),
        'buddyboss'    => __( 'BuddyBoss', 'advanced-form-integration' ),
        'calderaforms' => __( 'Caldera Forms', 'advanced-form-integration' ),
        'cf7'          => __( 'Contact Form 7', 'advanced-form-integration' ),
        'diviform'     => __( 'Divi Form', 'advanced-form-integration' ),
        'eform'        => __( 'eForm', 'advanced-form-integration' ),
        'elementorpro' => __( 'Elementor Pro Form', 'advanced-form-integration' ),
        'everestforms' => __( 'Everest Forms', 'advanced-form-integration' ),
        'fluentforms'  => __( 'Fluent Forms', 'advanced-form-integration' ),
        'formcraft'    => __( 'FormCraft 3', 'advanced-form-integration' ),
        'formcraftb'   => __( 'FormCraft Basic', 'advanced-form-integration' ),
        'formidable'   => __( 'Formidable Forms', 'advanced-form-integration' ),
        'forminator'   => __( 'Forminator (forms only)', 'advanced-form-integration' ),
        'gamipress'    => __( 'GamiPress', 'advanced-form-integration' ),
        'givewp'       => __( 'GiveWP', 'advanced-form-integration' ),
        'gravityforms' => __( 'Gravity Forms', 'advanced-form-integration' ),
        'happyforms'   => __( 'Happy Forms', 'advanced-form-integration' ),
        'kadence'      => __( 'Kadence Blocks Form', 'advanced-form-integration' ),
        'learndash'    => __( 'LearnDash', 'advanced-form-integration' ),
        'lifterlms'    => __( 'LifterLMS', 'advanced-form-integration' ),
        'liveforms'    => __( 'Live Forms', 'advanced-form-integration' ),
        'metform'      => __( 'Metform', 'advanced-form-integration' ),
        'ninjaforms'   => __( 'Ninja Forms', 'advanced-form-integration' ),
        'quform'       => __( 'Quform', 'advanced-form-integration' ),
        'smartforms'   => __( 'Smart Forms', 'advanced-form-integration' ),
        'tutorlms'     => __( 'Tutor LMS', 'advanced-form-integration' ),
        'weforms'      => __( 'weForms', 'advanced-form-integration' ),
        'wpforms'      => __( 'WPForms', 'advanced-form-integration' ),
        'woocommerce'  => __( 'WooCommerce', 'advanced-form-integration' ),
    );
    return apply_filters( 'adfoin_form_providers', $providers );
}

/**
* Generates the HTML options for the form integration providers dropdown.
*
* @return string The HTML options for the form integration providers dropdown.
*/
function adfoin_get_form_providers_html()
{
    $form_providers = adfoin_get_form_providers();
    $providers_html = '';
    foreach ( $form_providers as $key => $provider ) {
        $providers_html .= '<option value="' . $key . '">' . $provider . '</option>';
    }
    return $providers_html;
}

/**
* Retrieves the available form integration action providers.
*
* @return array The available form integration action providers.
*/
function adfoin_get_actions()
{
    $actions = array();
    return apply_filters( 'adfoin_action_providers', $actions );
}

/**
* Retrieves the available form integration action providers as an associative array with provider key as key and provider title as value.
*
* @return array The available form integration action providers as an associative array with provider key as key and provider title as value.
*/
function adfoin_get_action_porviders()
{
    $actions = adfoin_get_actions();
    $providers = array();
    foreach ( $actions as $key => $value ) {
        $providers[$key] = $value['title'];
    }
    return $providers;
}

/**
* Retrieves the available form integration action tasks for the specified provider.
*
* @param string $provider The provider key for which the action tasks should be retrieved.
*
* @return array The available form integration action tasks for the specified provider.
*/
function adfoin_get_action_tasks( $provider = '' )
{
    $actions = adfoin_get_actions();
    $tasks = array();
    if ( $provider ) {
        foreach ( $actions as $key => $value ) {
            if ( $provider == $key ) {
                $tasks = $value['tasks'];
            }
        }
    }
    return $tasks;
}

/**
* Returns an array of available settings tabs for the Advanced Form Integration plugin.
*
* @return array An array of available settings tabs.
*/
function adfoin_get_settings_tabs()
{
    $tabs = array(
        'general' => __( 'General', 'advanced-form-integration' ),
    );
    return apply_filters( 'adfoin_settings_tabs', $tabs );
}

/**
* Returns an array of supported integrations and their titles.
* @return array An array of integrations and their titles.
*/
function adfoin_get_action_platform_list()
{
    return array(
        'acelle'           => array(
        'title' => __( 'Acelle Mail', 'advanced-form-integration' ),
        'basic' => 'acelle',
    ),
        'activecampaign'   => array(
        'title' => __( 'ActiveCampaign', 'advanced-form-integration' ),
        'basic' => 'activecampaign',
    ),
        'agilecrm'         => array(
        'title' => __( 'Agile CRM', 'advanced-form-integration' ),
        'basic' => 'agilecrm',
    ),
        'airtable'         => array(
        'title' => __( 'Airtable', 'advanced-form-integration' ),
        'basic' => 'airtable',
    ),
        'asana'            => array(
        'key'   => 'asana',
        'title' => __( 'Asana', 'advanced-form-integration' ),
        'basic' => 'asana',
    ),
        'autopilot'        => array(
        'title' => __( 'Autopilot', 'advanced-form-integration' ),
        'basic' => 'autopilot',
    ),
        'aweber'           => array(
        'title' => __( 'Aweber', 'advanced-form-integration' ),
        'basic' => 'aweber',
    ),
        'beehiiv'          => array(
        'title' => __( 'beehiiv', 'advanced-form-integration' ),
        'basic' => 'beehiiv',
    ),
        'benchmark'        => array(
        'title' => __( 'Benchmark', 'advanced-form-integration' ),
        'basic' => 'benchmark',
    ),
        'bigin'            => array(
        'title' => __( 'Bigin', 'advanced-form-integration' ),
        'basic' => 'bigin',
    ),
        'campaignmonitor'  => array(
        'title' => __( 'Campaign Monitor', 'advanced-form-integration' ),
        'basic' => 'campaignmonitor',
    ),
        'capsulecrm'       => array(
        'title' => __( 'Capsule CRM', 'advanced-form-integration' ),
        'basic' => 'capsulecrm',
    ),
        'clickup'          => array(
        'title' => __( 'Clickup', 'advanced-form-integration' ),
        'basic' => 'clickup',
    ),
        'clinchpad'        => array(
        'title' => __( 'ClinchPad', 'advanced-form-integration' ),
        'basic' => 'clinchpad',
    ),
        'close'            => array(
        'title' => __( 'Close', 'advanced-form-integration' ),
        'basic' => 'close',
    ),
        'companyhub'       => array(
        'title' => __( 'CompanyHub', 'advanced-form-integration' ),
        'basic' => 'companyhub',
    ),
        'constantcontact'  => array(
        'title' => __( 'Constant Contact', 'advanced-form-integration' ),
        'basic' => 'constantcontact',
    ),
        'convertkit'       => array(
        'title' => __( 'ConvertKit', 'advanced-form-integration' ),
        'basic' => 'convertkit',
    ),
        'copper'           => array(
        'title' => __( 'Copper', 'advanced-form-integration' ),
        'basic' => 'copper',
    ),
        'curated'          => array(
        'title' => __( 'Curated', 'advanced-form-integration' ),
        'basic' => 'curated',
    ),
        'demio'            => array(
        'title' => __( 'Demio', 'advanced-form-integration' ),
        'basic' => 'demio',
    ),
        'directiq'         => array(
        'title' => __( 'DirectIQ', 'advanced-form-integration' ),
        'basic' => 'directiq',
    ),
        'drip'             => array(
        'title' => __( 'Drip', 'advanced-form-integration' ),
        'basic' => 'drip',
    ),
        'easysendy'        => array(
        'title' => __( 'EasySendy', 'advanced-form-integration' ),
        'basic' => 'easysendy',
    ),
        'elasticemail'     => array(
        'title' => __( 'Elastic Email', 'advanced-form-integration' ),
        'basic' => 'elasticemail',
    ),
        'emailoctopus'     => array(
        'title' => __( 'EmailOctopus', 'advanced-form-integration' ),
        'basic' => 'emailoctopus',
    ),
        'encharge'         => array(
        'title' => __( 'Encharge', 'advanced-form-integration' ),
        'basic' => 'encharge',
    ),
        'engagebay'        => array(
        'title' => __( 'EngageBay', 'advanced-form-integration' ),
        'basic' => 'engagebay',
    ),
        'everwebinar'      => array(
        'title' => __( 'EverWebinar', 'advanced-form-integration' ),
        'basic' => 'everwebinar',
    ),
        'flowlu'           => array(
        'title' => __( 'Flowlu', 'advanced-form-integration' ),
        'basic' => 'flowlu',
    ),
        'freshsales'       => array(
        'title' => __( 'Freshworks CRM', 'advanced-form-integration' ),
        'basic' => 'freshsales',
    ),
        'getresponse'      => array(
        'title' => __( 'GetResponse', 'advanced-form-integration' ),
        'basic' => 'getresponse',
    ),
        'googlecalendar'   => array(
        'title' => __( 'Google Calendar', 'advanced-form-integration' ),
        'basic' => 'googlecalendar',
    ),
        'googlesheets'     => array(
        'title' => __( 'Google Sheets', 'advanced-form-integration' ),
        'basic' => 'googlesheets',
    ),
        'hubspot'          => array(
        'title' => __( 'Hubspot', 'advanced-form-integration' ),
        'basic' => 'hubspot',
    ),
        'insightly'        => array(
        'title' => __( 'Insightly CRM', 'advanced-form-integration' ),
        'basic' => 'insightly',
    ),
        'jumplead'         => array(
        'title' => __( 'Jumplead', 'advanced-form-integration' ),
        'basic' => 'jumplead',
    ),
        'keap'             => array(
        'title' => __( 'Keap', 'advanced-form-integration' ),
        'basic' => 'keap',
    ),
        'klaviyo'          => array(
        'title' => __( 'Klaviyo', 'advanced-form-integration' ),
        'basic' => 'klaviyo',
    ),
        'lemlist'          => array(
        'title' => __( 'lemlist', 'advanced-form-integration' ),
        'basic' => 'lemlist',
    ),
        'liondesk'         => array(
        'title' => __( 'LionDesk', 'advanced-form-integration' ),
        'basic' => 'liondesk',
    ),
        'livestorm'        => array(
        'title' => __( 'Livestorm', 'advanced-form-integration' ),
        'basic' => 'livestorm',
    ),
        'mailbluster'      => array(
        'title' => __( 'MailBluster', 'advanced-form-integration' ),
        'basic' => 'mailbluster',
    ),
        'mailchimp'        => array(
        'title' => __( 'Mailchimp', 'advanced-form-integration' ),
        'basic' => 'mailchimp',
    ),
        'mailercloud'      => array(
        'title' => __( 'Mailercloud', 'advanced-form-integration' ),
        'basic' => 'mailercloud',
    ),
        'mailerlite'       => array(
        'title' => __( 'MailerLite Classic', 'advanced-form-integration' ),
        'basic' => 'mailerlite',
    ),
        'mailerlite2'      => array(
        'title' => __( 'MailerLite', 'advanced-form-integration' ),
        'basic' => 'mailerlite2',
    ),
        'mailify'          => array(
        'title' => __( 'Mailify', 'advanced-form-integration' ),
        'basic' => 'mailify',
    ),
        'mailjet'          => array(
        'title' => __( 'Mailjet', 'advanced-form-integration' ),
        'basic' => 'mailjet',
    ),
        'mailwizz'         => array(
        'title' => __( 'MailWizz', 'advanced-form-integration' ),
        'basic' => 'mailwizz',
    ),
        'mautic'           => array(
        'title' => __( 'Mautic', 'advanced-form-integration' ),
        'basic' => 'mautic',
    ),
        'moosend'          => array(
        'title' => __( 'Moosend', 'advanced-form-integration' ),
        'basic' => 'moosend',
    ),
        'nimble'           => array(
        'title' => __( 'Nimble', 'advanced-form-integration' ),
        'basic' => 'nimble',
    ),
        'omnisend'         => array(
        'title' => __( 'Omnisend', 'advanced-form-integration' ),
        'basic' => 'omnisend',
    ),
        'onehash'          => array(
        'title' => __( 'Onehash', 'advanced-form-integration' ),
        'basic' => 'onehash',
    ),
        'autopilotnew'     => array(
        'title' => __( 'Ortto', 'advanced-form-integration' ),
        'basic' => 'autopilotnew',
    ),
        'pabbly'           => array(
        'title' => __( 'Pabbly', 'advanced-form-integration' ),
        'basic' => 'pabbly',
    ),
        'pipedrive'        => array(
        'title' => __( 'Pipedrive', 'advanced-form-integration' ),
        'basic' => 'pipedrive',
    ),
        'pushover'         => array(
        'title' => __( 'Pushover', 'advanced-form-integration' ),
        'basic' => 'pushover',
    ),
        'revue'            => array(
        'title' => __( 'Revue', 'advanced-form-integration' ),
        'basic' => 'revue',
    ),
        'robly'            => array(
        'title' => __( 'Robly', 'advanced-form-integration' ),
        'basic' => 'robly',
    ),
        'salesflare'       => array(
        'title' => __( 'Salesflare', 'advanced-form-integration' ),
        'basic' => 'salesflare',
    ),
        'salesrocks'       => array(
        'title' => __( 'Sales Rocks', 'advanced-form-integration' ),
        'basic' => 'salesrocks',
    ),
        'selzy'            => array(
        'title' => __( 'Selzy', 'advanced-form-integration' ),
        'basic' => 'selzy',
    ),
        'sendfox'          => array(
        'title' => __( 'Sendfox', 'advanced-form-integration' ),
        'basic' => 'sendfox',
    ),
        'sendinblue'       => array(
        'title' => __( 'Sendinblue', 'advanced-form-integration' ),
        'basic' => 'sendinblue',
    ),
        'sendpulse'        => array(
        'title' => __( 'Sendpulse', 'advanced-form-integration' ),
        'basic' => 'sendpulse',
    ),
        'sendx'            => array(
        'title' => __( 'SendX', 'advanced-form-integration' ),
        'basic' => 'sendx',
    ),
        'sendy'            => array(
        'title' => __( 'Sendy', 'advanced-form-integration' ),
        'basic' => 'sendy',
    ),
        'slack'            => array(
        'title' => __( 'Slack', 'advanced-form-integration' ),
        'basic' => 'slack',
    ),
        'smartsheet'       => array(
        'title' => __( 'Smartsheet', 'advanced-form-integration' ),
        'basic' => 'smartsheet',
    ),
        'trello'           => array(
        'title' => __( 'Trello', 'advanced-form-integration' ),
        'basic' => 'trello',
    ),
        'twilio'           => array(
        'title' => __( 'Twilio', 'advanced-form-integration' ),
        'basic' => 'twilio',
    ),
        'verticalresponse' => array(
        'title' => __( 'Vertical Response', 'advanced-form-integration' ),
        'basic' => 'verticalresponse',
    ),
        'vtiger'           => array(
        'title' => __( 'Vtiger CRM', 'advanced-form-integration' ),
        'basic' => 'vtiger',
    ),
        'wealthbox'        => array(
        'title' => __( 'Wealthbox', 'advanced-form-integration' ),
        'basic' => 'wealthbox',
    ),
        'webhook'          => array(
        'title' => __( 'Webhook', 'advanced-form-integration' ),
        'basic' => 'webhook',
    ),
        'webinarjam'       => array(
        'title' => __( 'WebinarJam', 'advanced-form-integration' ),
        'basic' => 'webinarjam',
    ),
        'woodpecker'       => array(
        'title' => __( 'Woodpecker.co', 'advanced-form-integration' ),
        'basic' => 'woodpecker',
    ),
        'wordpress'        => array(
        'title' => __( 'WordPress', 'advanced-form-integration' ),
        'basic' => 'wordpress',
    ),
        'zapier'           => array(
        'title' => __( 'Zapier', 'advanced-form-integration' ),
        'basic' => 'zapier',
    ),
        'zohocampaigns'    => array(
        'title' => __( 'Zoho Campaigns', 'advanced-form-integration' ),
        'basic' => 'zohocampaigns',
    ),
        'zohocrm'          => array(
        'title' => __( 'Zoho CRM', 'advanced-form-integration' ),
        'basic' => 'zohocrm',
    ),
        'zohosheet'        => array(
        'title' => __( 'Zoho Sheet', 'advanced-form-integration' ),
        'basic' => 'zohosheet',
    ),
    );
}

/**
*
* Retrieves the action platform settings.
*
* @global object $wpdb WordPress database access object.
*
* @return array The action platform settings.
*/
function adfoin_get_action_platform_settings()
{
    global  $wpdb ;
    $settings = ( get_option( 'adfoin_general_settings_platforms' ) ? get_option( 'adfoin_general_settings_platforms' ) : array() );
    $saved_records = $wpdb->get_results( "SELECT form_provider, action_provider FROM {$wpdb->prefix}adfoin_integration WHERE status = 1", ARRAY_A );
    if ( !is_wp_error( $saved_records ) ) {
        
        if ( $saved_records && is_array( $saved_records ) ) {
            $action_providers = wp_list_pluck( $saved_records, 'action_provider' );
            
            if ( $action_providers ) {
                $action_providers = array_unique( $action_providers );
                foreach ( $action_providers as $single ) {
                    $settings[$single] = true;
                }
            }
        
        }
    
    }
    return $settings;
}

/**
* Renders the general settings view for Adfo.in plugin.
*/
add_action(
    'adfoin_settings_view',
    'adfoin_general_settings_view',
    10,
    1
);
/**
* Displays the view for general settings of Advanced Form Integration plugin
*
* @param string $current_tab The current tab name
*
* @return void
*/
function adfoin_general_settings_view( $current_tab )
{
    if ( $current_tab != 'general' ) {
        return;
    }
    $nonce = wp_create_nonce( "adfoin_general_settings" );
    $log_settings = ( get_option( 'adfoin_general_settings_log' ) ? get_option( 'adfoin_general_settings_log' ) : '' );
    $st_settings = ( get_option( 'adfoin_general_settings_st' ) ? get_option( 'adfoin_general_settings_st' ) : '' );
    $utm_settings = ( get_option( 'adfoin_general_settings_utm' ) ? get_option( 'adfoin_general_settings_utm' ) : '' );
    $job_queue = ( get_option( 'adfoin_general_settings_job_queue' ) ? get_option( 'adfoin_general_settings_job_queue' ) : '' );
    $platform_settings = adfoin_get_action_platform_settings();
    $platforms = adfoin_get_action_platform_list();
    ?>

    <form name="general_save_form" action="<?php 
    echo  esc_url( admin_url( 'admin-post.php' ) ) ;
    ?>"
          method="post" class="container">

        <input type="hidden" name="action" value="adfoin_save_general_settings">
        <input type="hidden" name="_nonce" value="<?php 
    echo  $nonce ;
    ?>"/>

        <div class="afi-row">
        <div class="col-full">
            <h3><?php 
    _e( 'Activate Platforms', 'advacned-form-integration' );
    ?></h3>
            <div class="afi-checkbox-container">
                <?php 
    foreach ( $platforms as $key => $platform ) {
        $status = ( isset( $platform_settings[$key] ) ? $platform_settings[$key] : '' );
        ?>
                    <div class="afi-checkbox">
                    <div class="afi-elements-info">
                        <p class="afi-el-title">
                            <label for="<?php 
        echo  esc_attr( $key ) ;
        ?>"><?php 
        echo  esc_html( $platform['title'] ) ;
        ?></label>
                        </p>
                    </div>
                    <label class="adfoin-toggle-form form-enabled">
                    <input type="checkbox" value="1" id="<?php 
        echo  esc_attr( $key ) ;
        ?>" name="platforms[<?php 
        echo  esc_attr( $key ) ;
        ?>]" <?php 
        checked( $status, 1 );
        ?>>
                    <span class="slider round"></span></label>
                </div>
                <?php 
    }
    ?>
                
            </div>

            <h3><?php 
    _e( 'General Settings', 'advacned-form-integration' );
    ?></h3>
            <div class="afi-checkbox-container">
                <div class="afi-checkbox">
                    <div class="afi-elements-info">
                        <p class="afi-el-title">
                            <label for="adfoin_disable_log"><?php 
    _e( 'Disable Log', 'advanced-form-integration' );
    ?></label>
                        </p>
                    </div>
                    <label class="adfoin-toggle-form form-enabled">
                    <input type="checkbox" value="1" id="adfoin_disable_log" name="adfoin_disable_log" <?php 
    checked( $log_settings, 1 );
    ?>>
                    <span class="slider round"></span></label>
                </div>
                <div class="afi-checkbox">
                    <div class="afi-elements-info">
                        <p class="afi-el-title">
                            <label for="adfoin_disable_st"><?php 
    _e( 'Disable Special Tags', 'advanced-form-integration' );
    ?></label>
                        </p>
                    </div>
                    <label class="adfoin-toggle-form form-enabled">
                    <input type="checkbox" value="1" id="adfoin_disable_st" name="adfoin_disable_st" <?php 
    checked( $st_settings, 1 );
    ?>>
                    <span class="slider round"></span></label>
                </div>
                <div class="afi-checkbox">
                    <div class="afi-elements-info">
                        <p class="afi-el-title">
                            <label for="adfoin_enable_utm"><?php 
    _e( 'Send UTM Variables', 'advanced-form-integration' );
    ?></label>
                        </p>
                    </div>
                    <label class="adfoin-toggle-form form-enabled">
                    <input type="checkbox" value="1" id="adfoin_enable_utm" name="adfoin_enable_utm" <?php 
    checked( $utm_settings, 1 );
    ?>>
                    <span class="slider round"></span></label>
                </div>
                <div class="afi-checkbox">
                    <div class="afi-elements-info">
                        <p class="afi-el-title">
                            <label for="adfoin_job_queue"><?php 
    _e( 'Enable Job Queue', 'advanced-form-integration' );
    ?></label>
                        </p>
                    </div>
                    <label class="adfoin-toggle-form form-enabled">
                    <input type="checkbox" value="1" id="adfoin_job_queue" name="adfoin_job_queue" <?php 
    checked( $job_queue, 1 );
    ?>>
                    <span class="slider round"></span></label>
                </div>
            </div>
        </div>
    </div>
        
    <?php 
    submit_button();
    ?>
    </form>

    <?php 
}

add_action(
    'admin_post_adfoin_save_general_settings',
    'adfoin_save_general_settings',
    10,
    0
);
function adfoin_save_general_settings()
{
    // Security Check
    if ( !wp_verify_nonce( $_POST['_nonce'], 'adfoin_general_settings' ) ) {
        die( __( 'Security check Failed', 'advanced-form-integration' ) );
    }
    $log_settings = ( isset( $_POST['adfoin_disable_log'] ) ? sanitize_text_field( $_POST['adfoin_disable_log'] ) : '' );
    $st_settings = ( isset( $_POST['adfoin_disable_st'] ) ? sanitize_text_field( $_POST['adfoin_disable_st'] ) : '' );
    $utm_settings = ( isset( $_POST['adfoin_enable_utm'] ) ? sanitize_text_field( $_POST['adfoin_enable_utm'] ) : '' );
    $job_queue = ( isset( $_POST['adfoin_job_queue'] ) ? sanitize_text_field( $_POST['adfoin_job_queue'] ) : '' );
    $default_platforms = array_fill_keys( array_keys( adfoin_get_action_platform_list() ), false );
    $activated_platforms = ( isset( $_POST['platforms'] ) ? adfoin_sanitize_text_or_array_field( $_POST['platforms'] ) : array() );
    $all_platforms = array_merge( $default_platforms, array_fill_keys( array_keys( array_intersect_key( $activated_platforms, $default_platforms ) ), true ) );
    // Save
    update_option( 'adfoin_general_settings_platforms', $all_platforms );
    update_option( 'adfoin_general_settings_log', $log_settings );
    update_option( 'adfoin_general_settings_st', $st_settings );
    update_option( 'adfoin_general_settings_utm', $utm_settings );
    update_option( 'adfoin_general_settings_job_queue', $job_queue );
    advanced_form_integration_redirect( 'admin.php?page=advanced-form-integration-settings' );
}

/**
 * Sanitize text or array field.
 *
 * @param mixed $array_or_string The string or array to sanitize.
 * @return mixed The sanitized string or array.
 */
function adfoin_sanitize_text_or_array_field( $array_or_string )
{
    
    if ( is_string( $array_or_string ) ) {
        $array_or_string = stripslashes( $array_or_string );
    } elseif ( is_array( $array_or_string ) ) {
        foreach ( $array_or_string as $key => &$value ) {
            
            if ( is_array( $value ) ) {
                $value = adfoin_sanitize_text_or_array_field( $value );
            } else {
                $value = stripslashes( $value );
            }
        
        }
    }
    
    return $array_or_string;
}

/*
 * Get parsed value
 */
function adfoin_get_parsed_values( $field, $posted_data )
{
    foreach ( $posted_data as $key => $value ) {
        
        if ( is_array( $value ) ) {
            $multi = 0;
            foreach ( $value as $single ) {
                
                if ( is_array( $single ) ) {
                    $multi = 1;
                    break;
                }
            
            }
            
            if ( $multi ) {
                $value = json_encode( $value );
            } else {
                $value = @implode( ",", $value );
            }
        
        }
        
        $field = str_replace( "{{" . $key . "}}", $value, $field );
    }
    $field = preg_replace( "/{{.+?}}/", "", $field );
    if ( strpos( $field, '[' ) !== false && strpos( $field, ']' ) !== false ) {
        $field = do_shortcode( $field );
    }
    return $field;
}

/**
 * Add a log entry for an integration request/response.
 *
 * This function adds a log entry for an integration request and response. The log entry includes
 * information about the URL, arguments, response data, and integration ID.
 *
 * @param mixed       $return   The response data from the integration request.
 * @param string      $url      The URL of the integration request.
 * @param array       $args     The arguments or data sent with the integration request.
 * @param array       $record   An array containing integration record data.
 * @param string|null $log_id   Optional. The ID of the log entry to update. Default is an empty string.
 * @return void
 */
function adfoin_add_to_log(
    $return,
    $url,
    $args,
    $record,
    $log_id = ''
)
{
    $log_settings = ( get_option( 'adfoin_general_settings_log' ) ? get_option( 'adfoin_general_settings_log' ) : '' );
    if ( "1" == $log_settings ) {
        return;
    }
    if ( isset( $args['body'] ) ) {
        if ( !is_array( $args['body'] ) ) {
            if ( null != json_decode( $args['body'] ) ) {
                $args['body'] = json_decode( $args['body'] );
            }
        }
    }
    $request_data = json_encode( array(
        'url'  => $url,
        'args' => $args,
    ) );
    
    if ( is_wp_error( $return ) ) {
        $data = array(
            'response_code'    => 0,
            'response_message' => 'WP Error',
            'integration_id'   => $record["id"],
            'request_data'     => $request_data,
            'response_data'    => json_encode( $return ),
        );
    } else {
        $data = array(
            'response_code'    => $return["response"]["code"],
            'response_message' => $return["response"]["message"],
            'integration_id'   => $record["id"],
            'request_data'     => $request_data,
            'response_data'    => $return["body"],
        );
    }
    
    $log = new Advanced_Form_Integration_Log();
    
    if ( $log_id ) {
        $log->update( $data, $log_id );
    } else {
        $log->insert( $data );
    }
    
    // if( strpos($data['response_code'], '2' ) === 0 ) {
    //     adfoin_send_email( array( 'integration_id' => $record['id'] ) );
    // }
    return;
}

/*
 * Get User IP
 */
function adfoin_get_user_ip()
{
    // Get real visitor IP behind CloudFlare network
    
    if ( isset( $_SERVER["HTTP_CF_CONNECTING_IP"] ) ) {
        $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        $_SERVER['HTTP_CLIENT_IP'] = ( $_SERVER["HTTP_CF_CONNECTING_IP"] ? $_SERVER["HTTP_CF_CONNECTING_IP"] : '' );
    }
    
    $client = ( isset( $_SERVER['HTTP_CLIENT_IP'] ) ? $_SERVER['HTTP_CLIENT_IP'] : '' );
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote = $_SERVER['REMOTE_ADDR'];
    
    if ( filter_var( $client, FILTER_VALIDATE_IP ) ) {
        $ip = $client;
    } elseif ( filter_var( $forward, FILTER_VALIDATE_IP ) ) {
        $ip = $forward;
    } else {
        $ip = $remote;
    }
    
    return $ip;
}

function adfoin_get_cl_conditions()
{
    return array(
        "equal_to"         => __( 'Equal to', 'advanced-form-integration' ),
        "not_equal_to"     => __( 'Not equal to', 'advanced-form-integration' ),
        "contains"         => __( 'Contains', 'advanced-form-integration' ),
        "does_not_contain" => __( 'Does not Contain', 'advanced-form-integration' ),
        "starts_with"      => __( 'Starts with', 'advanced-form-integration' ),
        "ends_with"        => __( 'Ends with', 'advanced-form-integration' ),
        "greater_than"     => __( 'Greater Than (number)', 'advanced-form-integration' ),
        "less_than"        => __( 'Less Than (number)', 'advanced-form-integration' ),
    );
}

function adfoin_match_conditional_logic( $cl, $posted_data )
{
    if ( $cl["active"] != "yes" ) {
        return true;
    }
    $match = 0;
    $length = count( $cl["conditions"] );
    foreach ( $cl["conditions"] as $condition ) {
        if ( !$condition["field"] && $condition["field"] != 0 ) {
            continue;
        }
        $field = ( strpos( $condition["field"], '{{' ) !== false && strpos( $condition["field"], '}}' ) !== false ? $condition["field"] : '{{' . trim( $condition["field"] ) . '}}' );
        $field_value = adfoin_get_parsed_values( $field, $posted_data );
        if ( adfoin_match_single_logic( $field_value, $condition["operator"], $condition["value"] ) ) {
            $match++;
        }
    }
    if ( $cl["match"] == "any" && $match > 0 ) {
        return true;
    }
    if ( $cl["match"] == "all" && $match == $length ) {
        return true;
    }
    return false;
}

function adfoin_match_single_logic( $data, $operator, $value )
{
    $result = false;
    switch ( $operator ) {
        case 'equal_to':
            if ( $data == $value ) {
                $result = true;
            }
            break;
        case 'not_equal_to':
            if ( $data != $value ) {
                return true;
            }
            break;
        case 'greater_than':
            if ( (double) $data > (double) $value ) {
                return true;
            }
            break;
        case 'less_than':
            if ( (double) $data < (double) $value ) {
                return true;
            }
            break;
        case 'contains':
            if ( strpos( $data, $value ) !== false ) {
                return true;
            }
            break;
        case 'does_not_contains':
            if ( strpos( $data, $value ) === false ) {
                return true;
            }
            break;
        case 'starts_with':
            $length = strlen( $value );
            return substr( $data, 0, $length ) === $value;
            break;
        case 'ends_with':
            $length = strlen( $value );
            if ( $length == 0 ) {
                return true;
            }
            if ( substr( $data, -$length ) === $value ) {
                return true;
            }
        default:
            return false;
    }
    return $result;
}

function adfoin_get_special_tags( $cat = '' )
{
    $utm_tags = array();
    $special_tags = array();
    
    if ( '1' == get_option( 'adfoin_general_settings_utm' ) ) {
        $utm_tags['utm_source'] = __( 'UTM Source', 'advanced-form-integration' );
        $utm_tags['utm_medium'] = __( 'UTM Medium', 'advanced-form-integration' );
        $utm_tags['utm_term'] = __( 'UTM Term', 'advanced-form-integration' );
        $utm_tags['utm_content'] = __( 'UTM Content', 'advanced-form-integration' );
        $utm_tags['utm_campaign'] = __( 'UTM Campaign', 'advanced-form-integration' );
        $utm_tags['gclid'] = __( 'GCLID', 'advanced-form-integration' );
    }
    
    if ( 'utm' == $cat ) {
        return $utm_tags;
    }
    
    if ( '1' != get_option( 'adfoin_general_settings_st' ) ) {
        $special_tags['_submission_date'] = __( '_Submission_Date', 'advanced-form-integration' );
        $special_tags['_date'] = __( '_Date', 'advanced-form-integration' );
        $special_tags['_time'] = __( '_Time', 'advanced-form-integration' );
        $special_tags['_weekday'] = __( '_Weekday', 'advanced-form-integration' );
        $special_tags['_user_ip'] = __( '_User_IP', 'advanced-form-integration' );
        $special_tags['_user_agent'] = __( '_User_Agent', 'advanced-form-integration' );
        $special_tags['_site_title'] = __( '_Site_Title', 'advanced-form-integration' );
        $special_tags['_site_description'] = __( '_Site_Description', 'advanced-form-integration' );
        $special_tags['_site_url'] = __( '_Site_URL', 'advanced-form-integration' );
        $special_tags['_site_admin_email'] = __( '_Site_Admin_Email', 'advanced-form-integration' );
        $special_tags['_post_id'] = __( '_Post_ID', 'advanced-form-integration' );
        $special_tags['_post_name'] = __( '_Post_Name', 'advanced-form-integration' );
        $special_tags['_post_title'] = __( '_Post_Title', 'advanced-form-integration' );
        $special_tags['_post_url'] = __( '_Post_URL', 'advanced-form-integration' );
        $special_tags['_user_id'] = __( '_Logged_User_ID', 'advanced-form-integration' );
        $special_tags['_user_first_name'] = __( '_Admin_First_Name', 'advanced-form-integration' );
        $special_tags['_user_last_name'] = __( '_Admin_Last_Name', 'advanced-form-integration' );
        $special_tags['_user_display_name'] = __( '_Admin_Display_Name', 'advanced-form-integration' );
        $special_tags['_user_email'] = __( '_Admin_Email', 'advanced-form-integration' );
    }
    
    if ( 'st' == $cat ) {
        return $special_tags;
    }
    $combined = array_merge( $utm_tags, $special_tags );
    return $combined;
}

/**
 * Retrieve values for special tags associated with a post.
 *
 * This function retrieves values for special tags that can be used in various contexts, such as emails
 * or templates, related to a specific post. Special tags may include user-specific information, URL parameters,
 * or other dynamic data.
 *
 * @param WP_Post|null $post The post object for which to retrieve special tag values. Can be null.
 * @return array Associative array containing values for special tags. Keys represent special tag names,
 *               and values represent the corresponding tag values.
 */
function adfoin_get_special_tags_values( $post )
{
    $st_data = array();
    $utm_data = array();
    
    if ( '1' != get_option( 'adfoin_general_settings_st' ) ) {
        if ( !function_exists( 'wp_get_current_user' ) ) {
            include ABSPATH . "wp-includes/pluggable.php";
        }
        $current_user = wp_get_current_user();
        $special_tags = adfoin_get_special_tags( 'st' );
        if ( !empty($special_tags) ) {
            foreach ( $special_tags as $key => $value ) {
                $st_data[$key] = adfoin_get_single_special_tag_value( $key, $current_user, $post );
            }
        }
    }
    
    if ( '1' == get_option( 'adfoin_general_settings_utm' ) ) {
        $utm_data = adfoin_capture_utm_and_url_values();
    }
    $combined = array_merge( $st_data, $utm_data );
    return $combined;
}

/**
 * Retrieves a value for a special tag based on the provided tag name.
 *
 * @param string       $tag           The name of the special tag to retrieve a value for.
 * @param WP_User|null $current_user  The current user object. Can be null.
 * @param WP_Post|null $post          The current post object. Can be null.
 * @return mixed|string|true The value associated with the special tag. Returns true if the tag is not matched.
 */
function adfoin_get_single_special_tag_value( $tag, $current_user, $post )
{
    switch ( $tag ) {
        case "submission_date":
            return date( "Y-m-d H:i:s" );
            break;
        case "_submission_date":
            return wp_date( 'Y-m-d H:i:s' );
            break;
        case "_date":
            return wp_date( get_option( 'date_format' ) );
            break;
        case "_time":
            return wp_date( get_option( 'time_format' ) );
            break;
        case "_weekday":
            return wp_date( 'l' );
            break;
        case "user_ip":
            return adfoin_get_user_ip();
            break;
        case "_user_ip":
            return adfoin_get_user_ip();
            break;
        case "_user_agent":
            return ( isset( $_SERVER['HTTP_USER_AGENT'] ) ? substr( $_SERVER['HTTP_USER_AGENT'], 0, 254 ) : '' );
            break;
        case "_site_title":
            return get_bloginfo( 'name' );
            break;
        case "_site_description":
            return get_bloginfo( 'description' );
            break;
        case "_site_url":
            return get_bloginfo( 'url' );
            break;
        case "_site_admin_email":
            return get_bloginfo( 'admin_email' );
            break;
        case "_post_id":
            return ( isset( $post ) && is_object( $post ) ? $post->ID : "" );
            break;
        case "_post_name":
            return ( isset( $post ) && is_object( $post ) ? $post->post_name : "" );
            break;
        case "_post_title":
            return ( isset( $post ) && is_object( $post ) ? $post->post_title : "" );
            break;
        case "_post_url":
            return ( isset( $post ) && is_object( $post ) ? get_permalink( $post->ID ) : "" );
            break;
        case "_user_id":
            return ( isset( $current_user, $current_user->ID ) ? $current_user->ID : "" );
            break;
        case "_user_first_name":
            return ( isset( $current_user, $current_user->user_firstname ) ? $current_user->user_firstname : "" );
            break;
        case "_user_last_name":
            return ( isset( $current_user, $current_user->user_lastname ) ? $current_user->user_lastname : "" );
            break;
        case "_user_display_name":
            return ( isset( $current_user, $current_user->display_name ) ? $current_user->display_name : "" );
            break;
        case "_user_email":
            return ( isset( $current_user, $current_user->user_email ) ? $current_user->user_email : "" );
            break;
    }
    return true;
}

// Checks if a string is in valid md5 format
function adfoin_is_valid_md5( $md5 = '' )
{
    return preg_match( '/^[a-f0-9]{32}$/', $md5 );
}

// Get saved UTM params
function adfoin_capture_utm_and_url_values()
{
    $fields = adfoin_get_special_tags( 'utm' );
    $cookie_fields = array();
    foreach ( $fields as $field => $title ) {
        
        if ( isset( $_GET[$field] ) && $_GET[$field] ) {
            $cookie_fields[$field] = htmlspecialchars( $_GET[$field], ENT_QUOTES, 'UTF-8' );
        } elseif ( isset( $_COOKIE[$field] ) && $_COOKIE[$field] ) {
            $cookie_fields[$field] = $_COOKIE[$field];
        } else {
            $cookie_fields[$field] = '';
        }
        
        $domain = ( isset( $_SERVER['SERVER_NAME'] ) ? $_SERVER['SERVER_NAME'] : '' );
        if ( strtolower( substr( $domain, 0, 4 ) ) == 'www.' ) {
            $domain = substr( $domain, 4 );
        }
        if ( substr( $domain, 0, 1 ) != '.' && $domain != 'localhost' ) {
            $domain = '.' . $domain;
        }
        setcookie(
            $field,
            $cookie_fields[$field],
            time() + 60 * 60 * 24 * 30,
            '/',
            $domain
        );
        $_COOKIE[$field] = $cookie_fields[$field];
    }
    return $cookie_fields;
}

/*
* The main remote request function for the Advanced Form Integration plugin
*/
function adfoin_remote_request( $url, $args )
{
    return wp_remote_request( $url, $args );
}

/**
 * Helper function to send an email
 *
 * @since 1.72.0
 *
 * @param array $args   Arguments passed to this function.
 *
 * @return bool         Whether the email contents were sent successfully.
 */
function adfoin_send_email( $args = array() )
{
    // Parse the email required args
    $email = wp_parse_args( $args, array(
        'from'        => '',
        'to'          => '',
        'cc'          => '',
        'bcc'         => '',
        'subject'     => '',
        'message'     => '',
        'headers'     => array(),
        'attachments' => array(),
    ) );
    /**
     * Filter available to override the email arguments before process them
     *
     * @since 1.72.0
     *
     * @param array     $email  The email arguments
     * @param array     $args   The original arguments received
     *
     * @return array
     */
    $email = apply_filters( 'adfoin_pre_email_args', $email, $args );
    $email['message'] = wpautop( $email['message'] );
    // Setup headers
    if ( !is_array( $email['headers'] ) ) {
        $email['headers'] = array();
    }
    if ( !empty($email['from']) ) {
        $email['headers'][] = 'From: <' . $email['from'] . '>';
    }
    if ( !empty($email['cc']) ) {
        $email['headers'][] = 'Cc: ' . $email['cc'];
    }
    if ( !empty($email['bcc']) ) {
        $email['headers'][] = 'Bcc: ' . $email['bcc'];
    }
    $email['headers'][] = 'Content-Type: text/html; charset=' . get_option( 'blog_charset' );
    // Setup attachments
    // if( ! is_array( $email['attachments'] ) ) {
    //     $email['attachments'] = array();
    // }
    /**
     * Filter available to override the email arguments after process them
     *
     * @since 1.72.0
     *
     * @param array     $email  The email arguments
     * @param array     $args   The original arguments received
     *
     * @return array
     */
    $email = apply_filters( 'adfoin_email_args', $email, $args );
    add_filter( 'wp_mail_content_type', 'adfoin_set_html_content_type' );
    // Send the email
    $result = wp_mail(
        $email['to'],
        $email['subject'],
        $email['message'],
        $email['headers'],
        $email['attachments']
    );
    remove_filter( 'wp_mail_content_type', 'adfoin_set_html_content_type' );
    return $result;
}

/**
 * Function to set the mail content type
 *
 * @since 1.72.0
 *
 * @param string $content_type
 *
 * @return string
 */
function adfoin_set_html_content_type( $content_type = 'text/html' )
{
    return 'text/html';
}
