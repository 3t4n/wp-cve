<h1><?php esc_html_e( 'Create MU plugin' ); ?></h1>
<p><?php esc_html_e( 'To enable this functionality, you need to create an MU plugin. Click the button below to generate the MU plugin.' ); ?></p>
<p><?php esc_html_e( '*You can easily remove this MU plugin through the plugin settings, or it will be automatically deleted upon deactivation of this plugin.*' ); ?></p>
<p><?php printf( esc_html__( '%3$sNote%4$s: In case automatic installation fails, you can manually create the `/wp-content/mu-plugins/` folder using SFTP. Then copy the `/wp-content/plugins/check-conflicts/mu-plugins/mu-check-conflicts.php` file into this folder. %1$sMore details about MU plugins%2$s'  ), '<br><a href="https://wpmudev.com/manuals/wpmu-manual-2/using-mu-plugins/" target="_blank">', '</a>', '<strong>', '</strong>' ); ?></p>

<form method="post" name="install">
	<?php wp_nonce_field( 'install MU-plugin' ); ?>

	<p class="submit">
		<?php submit_button( __( 'Generate MU plugin' ), 'primary', 'install_mu_plugin', false ); ?>&nbsp;&nbsp;
	</p>

</form>
