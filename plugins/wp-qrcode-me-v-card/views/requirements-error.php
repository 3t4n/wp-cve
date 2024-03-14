<?php
defined( 'ABSPATH' ) || exit;

/* @var array $errors */
?>
<div class="error">
    <p><?php echo __( 'QR code MeCard/vCard generator', 'wp-qrcode-me-v-card' ) . ' ' . __( "error: Your environment doesn't meet all of the system requirements listed below.", 'wp-qrcode-me-v-card' ) ?> </p>

    <ul class="ul-disc">
		<?php foreach ( $errors as $error ): ?>
            <li>
                <strong><?php echo $error ?></strong>
            </li>
		<?php endforeach; ?>
    </ul>

    <p><?php _e( 'If you need to upgrade your version of PHP you can ask your hosting company for assistance, and if you need help upgrading WordPress you can refer to the', 'wp-qrcode-me-v-card' ) ?>
        <a href="https://wordpress.org/documentation/article/updating-wordpress">Codex</a>.</p>
</div>
