<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if (
    ! empty( $_POST['update'] )
    && ! empty( $_POST['cf7sr_nonce'] )
    && wp_verify_nonce( $_POST['cf7sr_nonce'], 'cf7sr_update_recaptcha' )
) {
    $cf7sr_key = ! empty( $_POST['cf7sr_key'] ) ? sanitize_text_field( $_POST['cf7sr_key'] ) : '';
    update_option( 'cf7sr_key', $cf7sr_key );

    $cf7sr_secret = ! empty( $_POST['cf7sr_secret'] ) ? sanitize_text_field( $_POST['cf7sr_secret'] ) : '';
    update_option( 'cf7sr_secret', $cf7sr_secret );

    $cf7sr_message = ! empty( $_POST['cf7sr_message'] ) ? sanitize_text_field( $_POST['cf7sr_message'] ) : '';
    update_option( 'cf7sr_message', $cf7sr_message );

    $cf7sr_language = ! empty( $_POST['cf7sr_language'] ) ? sanitize_text_field( $_POST['cf7sr_language'] ) : '';
    update_option( 'cf7sr_language', $cf7sr_language );

    $updated = 1;
} else {
    $cf7sr_key      = get_option( 'cf7sr_key' );
    $cf7sr_secret   = get_option( 'cf7sr_secret' );
    $cf7sr_message  = get_option( 'cf7sr_message' );
    $cf7sr_language = get_option( 'cf7sr_language' );
}

?>
<?php if ( ! empty( $updated ) ) { ?>
    <p class="cf7sr-msg cf7sr-success-msg"><?php echo __( 'Updated successfully!', 'cf7sr-free' ); ?></p>
<?php } ?>
<form class="cf7sr-settings" action="<?php echo esc_attr( admin_url( 'options-general.php?page=cf7sr-edit&tab=recaptcha' ) ); ?>" method="POST">
    <input type="hidden" value="1" name="update">
    <?php wp_nonce_field( 'cf7sr_update_recaptcha', 'cf7sr_nonce' ); ?>

    <?php if (empty($cf7sr_key) || empty($cf7sr_secret)) { ?>
        <p class="cf7sr-title cf7sr-danger-msg"><?php echo __( 'reCaptcha will not work unless you set up the configuration below', 'cf7sr-free' ); ?></p>
    <?php } ?>

    <div class="cf7sr-row">
        <label>Site key</label>
        <input type="text" value="<?php echo esc_attr( $cf7sr_key ); ?>" name="cf7sr_key">
    </div>

    <div class="cf7sr-row">
        <label>Secret key</label>
        <input type="text" value="<?php echo esc_attr( $cf7sr_secret ); ?>" name="cf7sr_secret">
    </div>

    <div class="cf7sr-row">
        <label><?php echo __( 'Invalid captcha error message', 'cf7sr-free' ); ?></label>
        <input type="text" placeholder="<?php echo __( 'Invalid captcha', 'cf7sr-free' ); ?>" value="<?php echo esc_attr( $cf7sr_message ); ?>" name="cf7sr_message">
    </div>

    <div class="cf7sr-row">
        <label><?php echo __( 'Force reCaptcha to render in a specific language. Google auto-detects if unspecified.', 'cf7sr-free' ); ?></label>
        <select name="cf7sr_language">
            <option value=""></option>
            <?php foreach ( CF7SR_LANGUAGES as $key => $label ) { ?>
                <option value="<?php echo $key; ?>"
                    <?php
                    if ( $key == $cf7sr_language ) {
                        echo 'selected'; }
                    ?>
                ><?php echo $label; ?></option>
            <?php } ?>
        </select>
    </div>

    <input type="submit" class="button-primary" value="<?php echo __( 'Save Settings', 'cf7sr-free' ); ?>">
</form>

<p class="cf7sr-title cf7sr-warning-msg">
    <?php echo __( 'To add reCaptcha to Contact Form 7 form, add <strong>[cf7sr-recaptcha]</strong> in your form ( preferable above submit button )', 'cf7sr-free' ); ?>
</p>
<p class="cf7sr-title cf7sr-warning-msg">
    <?php echo __( 'Default size of reCaptcha is normal, for compact size use shortcode:', 'cf7sr-free' ); ?>
    <strong>[cf7sr-recaptcha size="compact"]</strong></p>
<p class="cf7sr-title cf7sr-warning-msg">
    <?php echo __( 'Default color theme of reCaptcha is light, for dark theme use shortcode:', 'cf7sr-free' ); ?>
    <strong>[cf7sr-recaptcha theme="dark"]</strong>
</p>
<p class="cf7sr-title cf7sr-warning-msg">
    <?php echo __( 'Default type of reCaptcha is image, for audio type use shortcode:', 'cf7sr-free' ); ?>
    <strong>[cf7sr-recaptcha type="audio"]</strong>
</p>
<p class="cf7sr-title cf7sr-warning-msg">
    <?php echo __( 'You can combine multiple attributes, sample shortcode:', 'cf7sr-free' ); ?>
    <strong>[cf7sr-recaptcha  size="compact" theme="dark"]</strong>
</p>

<div class="cf7sr-generate">
    <p class="cf7sr-title cf7sr-info-msg">
        <?php echo __( 'Use this link to generate', 'cf7sr-free' ); ?>  <i>Site key</i> and <i>Secret key</i>: <a target="_blank" href="https://www.google.com/recaptcha/admin">https://www.google.com/recaptcha/admin</a><br>
        <?php echo __( 'Choose', 'cf7sr-free' ); ?>  Challenge (v2) -> "I'm not a robot" Checkbox
    </p>
    <p><a target="_blank" href="https://www.google.com/recaptcha/admin"><img src="<?php echo CF7SR_PLUGIN_URL; ?>/assets/img/recaptcha.jpg" width="400" alt="captcha" /></a></p>
</div>
