<?php

/**
 * Status messages.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */

if ( ! isset( $_GET['status'] ) ) {
    return false;
}

$status = sanitize_text_field( $_GET['status'] );

$html = '';

switch ( $status ) {
    case 'draft':
        $html = sprintf(
            '<div class="acadp-alert acadp-alert-info" role="alert">%s</div>',
            esc_html__( 'Listing saved successfully.', 'advanced-classifieds-and-directory-pro' )
        );
        break;
        
    case 'permission_denied':
        $html = sprintf(
            '<div class="acadp-alert acadp-alert-error" role="alert">%s</div>',
            esc_html__( "Sorry, you don't have permission to do this action.", 'advanced-classifieds-and-directory-pro' )
        );
        break;

    case 'publish':
        $html = sprintf(
            '<div class="acadp-alert acadp-alert-info" role="alert">%s</div>',
            esc_html__( 'Listing published successfully.', 'advanced-classifieds-and-directory-pro' )
        );
        break;

    case 'updated':
        $html = sprintf(
            '<div class="acadp-alert acadp-alert-info" role="alert">%s</div>',
            esc_html__( 'Listing updated successfully.', 'advanced-classifieds-and-directory-pro' )
        );
        break;

    case 'pending':
        $html = sprintf(
            '<div class="acadp-alert acadp-alert-info" role="alert">%s</div>',
            esc_html__( "Listing submitted successfully and it's pending review. This review process could take up to 48 hours. Please be patient.", 'advanced-classifieds-and-directory-pro' )
        );
        break;

    case 'renewed':
        $html = sprintf(
            '<div class="acadp-alert acadp-alert-info" role="alert">%s</div>',
            esc_html__( 'Listing renewed successfully.', 'advanced-classifieds-and-directory-pro' )
        );
        break;

    case 'deleted':
        $html = sprintf(
            '<div class="acadp-alert acadp-alert-info" role="alert">%s</div>',
            esc_html__( 'Listing deleted successfully.', 'advanced-classifieds-and-directory-pro' )
        );
        break;

    case 'invalid_captcha':
        $html = sprintf(
            '<div class="acadp-alert acadp-alert-error" role="alert">%s</div>',
            esc_html__( 'Invalid Captcha: Please try again.', 'advanced-classifieds-and-directory-pro' )
        );
        break;
}

echo apply_filters( 'acadp_status_message', $html, $status );	
