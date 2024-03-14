<?php

/**
 * Social Login Settings.
 */
function youzify_membership_social_login_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Social Login', 'youzify' ),
            'desc'  => __( 'Activate social login', 'youzify' ),
            'id'    => 'youzify_enable_social_login',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable E-mail Confirmation', 'youzify' ),
            'desc'  => __( 'Enable email confirmation on social registration.', 'youzify' ),
            'id'    => 'youzify_enable_social_login_email_confirmation',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Buttons Type', 'youzify' ),
            'opts'  => $Youzify_Settings->get_field_options( 'social_buttons_type' ),
            'desc'  => __( 'Select buttons type', 'youzify' ),
            'id'    => 'youzify_social_btns_type',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Buttons Icons Position', 'youzify' ),
            'opts'  => $Youzify_Settings->get_field_options( 'form_icons_position' ),
            'desc'  => __( 'Select buttons icons position', 'youzify' ),
            'id'    => 'youzify_social_btns_icons_position',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Buttons Border Style', 'youzify' ),
            'opts'  => $Youzify_Settings->get_field_options( 'fields_format' ),
            'desc'  => __( 'Select buttons border style', 'youzify' ),
            'id'    => 'youzify_social_btns_format',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    // Get Providers.
    $providers = youzify_get_social_login_providers();

    if ( empty( $providers ) ) {
        return false;
    }

    foreach( $providers as $provider ) :

        // Get Provider Data.
        $provider_data = youzify_get_social_login_provider_data( $provider );

        // Get Provider.
        $lowercase_provider = strtolower( $provider );

        // Get Key Or ID.
        $key = ( 'key' == $provider_data['app'] ) ? __( 'Key', 'youzify' ) : __( 'ID', 'youzify' );

        // Get Setup Instruction.
        get_provider_settings_note( $lowercase_provider );

        $Youzify_Settings->get_field(
            array(
            'title' => sprintf( __( '%s Settings', 'youzify' ), $provider ),
            'type'  => 'openBox'
            )
        );

        $Youzify_Settings->get_field(
            array(
                'title' => __( 'Enable Network', 'youzify' ),
                'desc'  => __( 'Enable application', 'youzify' ),
                'id'    => 'youzify_' . $lowercase_provider . '_app_status',
                'type'  => 'checkbox',
                'std'   => 'on'
            )
        );

        $Youzify_Settings->get_field(
            array(
                'title' => sprintf( __( 'Application %s', 'youzify' ), $key ),
                'desc'  => sprintf( __( 'Enter application %s', 'youzify' ), $key ),
                'id'    => 'youzify_' . $lowercase_provider . '_app_key',
                'type'  => 'text'
            )
        );

        $Youzify_Settings->get_field(
            array(
                'title' => __( 'Application Secret', 'youzify' ),
                'desc'  => __( 'Enter application secret key', 'youzify' ),
                'id'    => 'youzify_' . $lowercase_provider . '_app_secret',
                'type'  => 'text'
            )
        );

        $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    endforeach;

}


/**
 * Get Setup Instructions.
 */
function get_provider_settings_note( $provider ) {

    global $Youzify_Settings;

    $steps = get_provider_instructions( $provider );

    $steps = apply_filters( 'youzify_providet_setup_instrcutions', $steps );

    if ( empty( $steps ) ) {
        return false;
    }

    $Youzify_Settings->get_field(
        array(
            'msg_type'  => 'info',
            'type'      => 'msgBox',
            'id'        => 'youzify_' . $provider . '_setup_steps',
            'title'     => sprintf( __( 'How to get %s keys?', 'youzify' ), $provider ),
            'msg'       => implode( '<br>', $steps )
        )
    );
}

/**
 * Get Provide instructions
 */
function get_provider_instructions( $provider ) {

    switch ( $provider ) {

        case 'facebook':

            // Init Vars.
            $auth_url = home_url( '/youzify-auth/social-login/Facebook' );
            $apps_url = 'https://developers.facebook.com/apps';

            // Get Steps.
            $steps[] = sprintf( __( '1. Go to <a href="%1s">%2s</a>', 'youzify' ), $apps_url, $apps_url );
            $steps[] = __( '2. Create a new application by clicking "Create New App".', 'youzify' );
            $steps[] = __( '3. Fill out any required fields such as the application name and description.', 'youzify' );
            $steps[] = __( '4. Put your website domain in the site URL field.', 'youzify' );
            $steps[] = __( '5. Go to the Status & Review page.', 'youzify' );
            $steps[] = __( '6. Enable <strong>"Do you want to make this app and all its live features available to the general public?"</strong>.', 'youzify' );
            $steps[] = __( '7. Facebook Login > Settings > Valid OAuth redirect URIs:', 'youzify' );
            $steps[] = sprintf( __( '8. OAuth URL : <strong><a>%s</a></strong>', 'youzify' ), $auth_url );
            $steps[] = __( '9. Go to dashboard and get your <strong>App ID</strong> and <strong>App Secret</strong>', 'youzify' );

            return $steps;

        case 'twitter':

            // Init Vars.
            $apps_url = 'https://dev.twitter.com/apps';
            $auth_url = home_url( '/youzify-auth/social-login/Twitter' );

            // Get Note
            $steps[] = __( '<strong><a>Note:</a> Twitter do not provide their users email address, to make that happen you have to submit your application for review untill that time we will request the email from users while registration.</strong>', 'youzify' ) . '<br>';

            // Get Steps.
            $steps[] = sprintf( __( '1. Go to <a href="%1s">%2s</a>', 'youzify' ), $apps_url, $apps_url );
            $steps[] = __( '2. Create a new application by clicking "Create New App".', 'youzify' );
            $steps[] = __( '3. Fill out any required fields such as the application name and description.', 'youzify' );
            $steps[] = __( '4. Put your website domain in the Site URL field.', 'youzify' );
            $steps[] = __( "5. Provide URL's below as the Callback URL's for your application respecting the same order.", 'youzify' );
            $steps[] = sprintf( __( '5.1 First Callback URL: <strong><a>%s</a></strong>', 'youzify' ), home_url() );
            $steps[] = sprintf( __( '5.2 Second Callback URL: <strong><a>%s</a></strong>', 'youzify' ), $auth_url );
            $steps[] = __( '6. Register Settings and get Consumer Key and Secret.', 'youzify' );

            return $steps;

        case 'google':

            // Init Vars.
            $apps_url = 'https://code.google.com/apis/console/';
            $auth_url = home_url( '/youzify-auth/social-login/Google' );
            // Get Steps.
            $steps[] = sprintf( __( '1. Go to <a href="%1s">%2s</a>', 'youzify' ), $apps_url, $apps_url );
            $steps[] = __( '2. Create a new application by clicking "Create a new project".', 'youzify' );
            $steps[] = __( '3. Go to API Access under API Project.', 'youzify' );
            $steps[] = __( '4. After that click on Create an OAuth 2.0 client ID to create a new application.', 'youzify' );
            $steps[] = __( '5. A pop-up named "Create Client ID" will appear, fill out any required fields such as the application name and description and Click on Next.', 'youzify' );
            $steps[] = __( '6. On the popup set Application type to Web application and switch to advanced settings by clicking on ( more options ) .', 'youzify' );
            $steps[] = __( '7. Provide URL below as the Callback URL for your application.', 'youzify' );
            $steps[] = sprintf( __( '8. Callback URL: <strong><a>%s</a></strong>', 'youzify' ), $auth_url );
            $steps[] = __( '9. Once you have registered, copy the created application credentials (Client ID and Secret ) .', 'youzify' );
            $steps[] = __( "<br><strong style='color: red;'>Notice : </strong> if google did not approved your application because of the sign in button design you can add this code snippet to the file <strong>'bp-custom.php'</strong> in the path <strong>'wp-content/plugins'</strong> : <a href='https://gist.github.com/KaineLabs/cc8d2479f59f09e04450e8b55ca8da51'>New Google Sign in Button</a>.<br><strong>Ps</strong>: if you didn't find the file 'bp-custom.php', just create a new one !", 'youzify' );

            return $steps;

        case 'linkedin':

            // Init Vars.
            $apps_url = 'https://www.linkedin.com/developer/apps';
            $auth_url = home_url( '/youzify-auth/social-login/LinkedIn' );

            // Get Steps.
            $steps[] = sprintf( __( '1. Go to <a href="%1s">%2s</a>', 'youzify' ), $apps_url, $apps_url );
            $steps[] = __( '2. Create a new application by clicking "Create Application".', 'youzify' );
            $steps[] = __( '3. Fill out any required fields such as the application name and description.', 'youzify' );
            $steps[] = __( '4. Put the below URL in the OAuth 2.0 Authorized Redirect URLs:', 'youzify' );
            $steps[] = sprintf( __( '5. Redirect URL: <strong><a>%s</a></strong>', 'youzify' ), $auth_url );
            $steps[] = __( '6. Once you have registered, copy the created application credentials ( Client ID and Secret ) .', 'youzify' );
            return $steps;

        case 'instagram':

            // Init Vars.
            $apps_url = 'https://kainelabs.ticksy.com/article/15737/';
            $auth_url = home_url( '/youzify-auth/social-login/Instagram' );

            // Get Note
            $steps[] = __( '<strong><a>Note:</a> Instagram do not provide their users email address, to make that happen you have to submit your application for review untill that time we will request the email from users while registration.</strong>', 'youzify' ) . '<br>';

            // Get Steps.
            $steps[] = sprintf( __( '1. Check this topic on <a href="%1s">How to Setup Instagram Social Login</a> for a detailed steps.', 'youzify' ), $apps_url );
            $steps[] = __( '2. Put the below URL as OAuth redirect_uri Authorized Redirect URLs:', 'youzify' );
            $steps[] = sprintf( __( '3. Redirect URL: <strong><a>%s</a></strong>', 'youzify' ), $auth_url );

            return $steps;

        case 'twitchtv':

            // Init Vars.
            $apps_url = 'https://dev.twitch.tv/console/apps/create';
            $auth_url = home_url( '/youzify-auth/social-login/TwitchTV' );
            // Get Steps.
            $steps[] = sprintf( __( '1. Go to <a href="%1s">%2s</a>', 'youzify' ), $apps_url, $apps_url );
            $steps[] = __( '2. Fill out any required fields such as the application name and Category.', 'youzify' );
            $steps[] = __( '3. Put the below URL as OAuth redirect_uri  Authorized Redirect URLs:', 'youzify' );
            $steps[] = sprintf( __( 'Redirect URL: <strong><a>%s</a></strong>', 'youzify' ), $auth_url );
            $steps[] = __( '4. Once you have registered, copy the created application credentials ( Client ID and Secret ) .', 'youzify' );

            return $steps;

        default:
            return false;
    }
}