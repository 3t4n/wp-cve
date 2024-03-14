<?php

/**
 * Cookie consent.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */

$cookie_consent_settings = get_option( 'acadp_cookie_consent' );

$show_cookie_consent = false;
if ( ! isset( $_COOKIE['acadp_gdpr_consent'] ) && ! empty( $cookie_consent_settings['show_cookie_consent'] ) && ! is_user_logged_in() ) {
    $show_cookie_consent = true;
}

if ( ! $show_cookie_consent ) {
    return false;
}

$consent_message = apply_filters( 'acadp_translate_strings', $cookie_consent_settings['consent_message'], 'consent_message' );
$consent_button_label = apply_filters( 'acadp_translate_strings', $cookie_consent_settings['consent_button_label'], 'consent_button_label' ); 
?>
<div class="acadp-cookie-consent acadp-absolute acadp-inset-0 acadp-flex acadp-flex-col acadp-gap-4 acadp-items-center acadp-justify-center acadp-bg-gray-50 acadp-p-4">
    <div class="acadp-text-center">
        <?php echo wp_kses_post( trim( $consent_message ) ); ?>
    </div>

    <button type="button" class="acadp-button acadp-button-secondary acadp-button-cookie-consent acadp-py-2">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="20px" height="20px" stroke-width="1.5" stroke="currentColor" class="acadp-flex-shrink-0">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
        </svg>
        <?php echo esc_html( $consent_button_label ); ?>
    </button>
</div>