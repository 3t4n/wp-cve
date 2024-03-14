<h3><?php _e( 'Configure', $text_domain ); ?></h3>
<div id="sbspf_config">
    <?php
    $notice_not_dismissed = sby_notice_not_dismissed( 'sby_connect_warning_notice' );
    $warning_data_att = $notice_not_dismissed ? ' data-show-warning="1"' : '';
    ?>
    <a href="<?php echo $oauth_processor_url . admin_url( 'admin.php?page=' . esc_attr( $slug ) ); ?>" id="sbspf_get_token"<?php echo $warning_data_att; ?>><?php echo sby_icon( $slug, 'sbspf_small_svg' ); ?> <?php echo sprintf( __( 'Connect to %s to Create a Feed', $text_domain ), $social_network ); ?></a>
    <a class="sbspf_not_working" href="https://smashballoon.com/<?php echo esc_attr( $slug ); ?>/token/" target="_blank"><?php _e( "Button not working?", $text_domain ); ?></a>
</div>

<form method="post" action="">
	<?php
	$settings = $this->settings;
	$api_key = isset( $settings['api_key'] ) ? $settings['api_key'] : '';
	?>
    <table class="form-table sbspf_own_credentials_wrap" role="presentation">
        <tbody>
        <tr>
            <th scope="row">
                <label for="sby_app_id"><?php _e( "API Key", $text_domain ); ?></label>
                <p class="sbspf_aside sbspf_red"><?php _e( "Recommended", $text_domain ); ?></p>
            </th>
            <td>
                <input name="<?php echo $this->get_option_name(); ?>[api_key]" id="sby_api_key" type="text" value="<?php echo esc_attr( $api_key ); ?>" size="64" minlength="15" maxlength="200" />
                <p class="sbspf_aside"><?php echo __( 'An API Key is needed for several features to work. It\'s free to create and it only takes a few minutes: <a href="https://smashballoon.com/youtube-api-key/" target="_blank" rel="noopener">Get my API key.</a>', $text_domain ); ?></p>
            </td>
        </tr>

        </tbody>
    </table>
	<?php $this->hidden_fields_for_tab( 'configure' ); ?>
	<?php include_once $this->get_path( 'connected-accounts' ); ?>

	<?php foreach ( $this->get_sections( 'configure' ) as $section ) : ?>

		<?php do_settings_sections( $section['id'] ); // matches the section name ?>
		<?php if ( $section['save_after'] ) : ?>
            <p class="submit"><input class="button-primary" type="submit" name="save" value="<?php esc_attr_e( 'Save Changes' ); ?>" /></p>
		<?php endif; ?>
        <hr>
	<?php endforeach; ?>
</form>

<?php if ( empty( $api_key ) && sby_api_key_notice_not_dismissed() ) { include $this->get_path( 'modal' ); } ?>