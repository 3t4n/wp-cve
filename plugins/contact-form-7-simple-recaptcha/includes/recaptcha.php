<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$cf7sr_key    = get_option( 'cf7sr_key' );
$cf7sr_secret = get_option( 'cf7sr_secret' );

if ( empty( $cf7sr_key ) || empty( $cf7sr_secret ) || is_admin() ) {
    return;
}

function enqueue_cf7sr_recaptcha_script() {
    global $cf7sr_recaptcha_on;
    if ( ! $cf7sr_recaptcha_on ) {
        return;
    }
    $cf7sr_script_url = 'https://www.google.com/recaptcha/api.js?onload=cf7srLoadRecaptcha&render=explicit';
    $language = get_option( 'cf7sr_language' );
    if ( ! empty( $language ) && ! empty( CF7SR_LANGUAGES[ $language ] ) ) {
        $cf7sr_script_url .= '&hl=' . $language;
    }
    $cf7sr_key = get_option( 'cf7sr_key' );
    ?>
    <script type="text/javascript">
        var recaptchaIds = [];

        var cf7srLoadRecaptcha = function() {
            var widgets = document.querySelectorAll('.cf7sr-g-recaptcha');
            for (var i = 0; i < widgets.length; ++i) {
                var widget = widgets[i];
                recaptchaIds.push(
                    grecaptcha.render(widget.id, {
                        'sitekey' : <?php echo wp_json_encode( $cf7sr_key ); ?>
                    })
                );
            }
        };

        function cf7srResetRecaptcha() {
            for (var i = 0; i < recaptchaIds.length; i++) {
                grecaptcha.reset(recaptchaIds[i]);
            }
        }

        document.querySelectorAll('.wpcf7').forEach(function(element) {
            element.addEventListener('wpcf7invalid', cf7srResetRecaptcha);
            element.addEventListener('wpcf7mailsent', cf7srResetRecaptcha);
            element.addEventListener('invalid.wpcf7', cf7srResetRecaptcha);
            element.addEventListener('mailsent.wpcf7', cf7srResetRecaptcha);
        });
    </script>
    <script src="<?php echo esc_url( $cf7sr_script_url ); ?>" async defer></script>
    <?php
}
add_action( 'wp_footer', 'enqueue_cf7sr_recaptcha_script' );

function cf7sr_recaptcha_wpcf7_form_elements( $form ) {
    $form = do_shortcode( $form );
    return $form;
}
add_filter( 'wpcf7_form_elements', 'cf7sr_recaptcha_wpcf7_form_elements' );

function cf7sr_recaptcha_shortcode( $atts ) {
    global $cf7sr_recaptcha_on;
    $cf7sr_recaptcha_on       = true;
    $cf7sr_key   = get_option( 'cf7sr_key' );
    $cf7sr_theme = ! empty( $atts['theme'] ) && 'dark' == $atts['theme'] ? 'dark' : 'light';
    $cf7sr_type  = ! empty( $atts['type'] ) && 'audio' == $atts['type'] ? 'audio' : 'image';
    $cf7sr_size  = ! empty( $atts['size'] ) && 'compact' == $atts['size'] ? 'compact' : 'normal';

    $cf7sr_id       = 'cf7sr-' . uniqid();

    return '<div id="' . $cf7sr_id . '" class="cf7sr-g-recaptcha" data-theme="' . esc_attr( $cf7sr_theme ) . '" data-type="'
        . esc_attr( $cf7sr_type ) . '" data-size="' . esc_attr( $cf7sr_size ) . '" data-sitekey="' . esc_attr( $cf7sr_key )
        . '"></div><span class="wpcf7-form-control-wrap cf7sr-recaptcha" data-name="cf7sr-recaptcha"><input type="hidden" name="cf7sr-recaptcha" value="" class="wpcf7-form-control"></span>';
}
add_shortcode( 'cf7sr-simple-recaptcha', 'cf7sr_recaptcha_shortcode' );
add_shortcode( 'cf7sr-recaptcha', 'cf7sr_recaptcha_shortcode' );

function cf7sr_verify_recaptcha( $result, $tags ) {
    if ( ! class_exists( 'WPCF7_Submission' ) ) {
        return $result;
    }

    $_wpcf7 = ! empty( $_POST['_wpcf7'] ) ? absint( $_POST['_wpcf7'] ) : 0;
    if ( empty( $_wpcf7 ) ) {
        return $result;
    }

    $submission = WPCF7_Submission::get_instance();
    $data       = $submission->get_posted_data();

    $cf7_text  = do_shortcode( '[contact-form-7 id="' . $_wpcf7 . '"]' );
    $cf7sr_key = get_option( 'cf7sr_key' );
    if ( false === strpos( $cf7_text, $cf7sr_key ) ) {
        return $result;
    }

    $message = get_option( 'cf7sr_message' );
    if ( empty( $message ) ) {
        $message = 'Invalid captcha';
    }

    if (empty( $data['g-recaptcha-response'])) {
        $result->invalidate(
            array(
                'type' => 'captcha',
                'name' => 'cf7sr-recaptcha',
            ),
            $message
        );
        return $result;
    }

    $cf7sr_secret = get_option( 'cf7sr_secret' );
    $url          = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $cf7sr_secret . '&response=' . $data['g-recaptcha-response'];
    $request      = wp_remote_get( $url );
    $body         = wp_remote_retrieve_body( $request );
    $response     = json_decode( $body );
    if ( ! ( isset( $response->success ) && 1 == $response->success ) ) {
        $result->invalidate(
            array(
                'type' => 'captcha',
                'name' => 'cf7sr-recaptcha',
            ),
            $message
        );
    }

    return $result;
}
add_filter( 'wpcf7_validate', 'cf7sr_verify_recaptcha', 20, 2 );
