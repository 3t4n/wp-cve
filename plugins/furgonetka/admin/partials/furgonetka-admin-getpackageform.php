<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://furgonetka.pl
 * @since      1.0.0
 *
 * @package    Furgonetka
 * @subpackage Furgonetka/admin/partials
 */

/**
 * /wp-content/plugins/furgonetka/admin/class-furgonetka-admin.php:168
 * available variables
 */
if ( ! defined( 'WPINC' ) ) {
    die;
}
?>
<?php require __DIR__ . '/furgonetka-admin-messages.php'; ?>
<div class="wrap">
    <?php if ( $furgonetka_package_form_url ) : ?>
        <iframe src="<?php echo esc_html( $furgonetka_package_form_url ); ?>" style="width: 100%;height: 88vh"></iframe>
    <?php endif; ?>
</div>
