<?php

/**
 * Video.
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
?>

<div class="acadp-video acadp-relative">
    <iframe class="acadp-iframe-video acadp-w-full acadp-aspect-video acadp-m-0" data-src="<?php echo esc_url( $video_url ); ?>" frameborder="0" scrolling="no" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
    
    <?php if ( $show_cookie_consent ) :
        $consent_message = apply_filters( 'acadp_translate_strings', $cookie_consent_settings['consent_message'], 'consent_message' );
        $consent_button_label = apply_filters( 'acadp_translate_strings', $cookie_consent_settings['consent_button_label'], 'consent_button_label' ); 
        ?>
        <div class="acadp-cookie-consent acadp-absolute acadp-inset-0 acadp-flex acadp-flex-col acadp-gap-4 acadp-items-center acadp-justify-center acadp-bg-gray-50 acadp-p-4">
            <div class="acadp-text-center">
                <?php echo wp_kses_post( trim( $consent_message ) ); ?>
            </div>

            <button type="button" class="acadp-button acadp-button-secondary acadp-button-cookie-consent acadp-py-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="20px" height="20px" stroke-width="1.5" stroke="currentColor" class="acadp-flex-shrink-0">
                    <path stroke-linecap="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" />
                </svg>
                <?php echo esc_html( $consent_button_label ); ?>
            </button>
        </div>
    <?php endif; ?>
</div>