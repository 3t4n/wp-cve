<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}
?>
<div class="wrap">
    <h2><?php esc_html_e( 'Enable Accessibility Options', 'enable-accessibility' ) ?></h2>

	<?php
	$license      = get_option( 'enable-accessibility', '' );
	$license_data = $this->_get_license_data( $license );
	$this->update_license_data( $license_data );
	$host = isset( $host ) ? $host : 'enable.co.il';
	?>
    <form class="oc-accessibilty-form" method="post" action="<?php echo esc_attr( $_SERVER['REQUEST_URI'] ); ?>">
        <h2><?php esc_html_e( 'General Options', 'enable-accessibility' ) ?></h2>
        <input type="hidden" name="action" value="save_accessibility_settings">
		<?php wp_nonce_field( 'save_accessibility_settings' ); ?>
        <table class="form-table oc-accessibilty-style">
            <tbody>
            <tr valign="top">
                <th scope="row"><?php esc_html_e( 'License', 'enable-accessibility' ); ?></th>
                <td>
                    <input type="text" class="large-text" name="enable_license"
                           placeholder="<?php esc_attr_e( 'Paste your license key', 'enable-accessibility' ) ?>"
                           value="<?php echo esc_attr( $license ); ?>"/>
					<?php if ( $license == "" ): ?>
                        <p></p>
					<?php else: ?>
                        <p><?php echo $this->_get_license_message( $license_data ); ?></p>
					<?php endif; ?>
                </td>
            </tr>
            </tbody>
        </table>
        <p class="submit">
            <input type="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes', 'enable-accessibility' ) ?>"/>

            <?php if ( isset( $license_data->data->status ) && 'Active' == $license_data->data->status ) : ?>
                <a href="https://my.<?php echo esc_attr( $host ); ?>/index.php?m=goenable" target="_blank"><?php _e( 'Manage My License', 'enable-accessibility' ); ?></a>
            <?php endif; ?>
        </p>

    </form>
</div>
