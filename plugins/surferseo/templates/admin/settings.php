<?php
/**
 * Template for general Surfer plugin settings.
 *
 * @package SurferSEO.
 */

use SurferSEO\Surferseo;

?>

<div class="wrap surfer-layout">
	<h1><?php esc_html_e( 'Surfer: Settings', 'surferseo' ); ?></h1>

	<?php if ( isset( $error ) && true === $error ) : ?>
		<div class="notice error surfer-error is-dismissible" >
			<p><?php esc_html_e( 'There is an error in your form.', 'surferseo' ); ?></p>
		</div>
	<?php endif; ?>

	<?php if ( isset( $success ) && true === $success ) : ?>
		<div class="notice updated surfer-success is-dismissible" >
			<p><?php esc_html_e( 'Form saved properly.', 'surferseo' ); ?></p>
		</div>
	<?php endif; ?>

	<form action="" method="POST">
		<div class="surfer-wraper">
			<div class="surfer-wraper__logo">
				<img src="<?php echo esc_url( Surfer()->get_baseurl() . 'assets/images/surfer_logo.svg' ); ?>" alt="Surfer Logo" />
			</div>
			<div class="surfer-wraper__content">

				<?php wp_nonce_field( 'surfer_settings_save', '_surfer_nonce' ); ?>

				<?php if ( isset( $form ) ) : ?>
					<?php $form->render_admin_form(); ?>
				<?php endif; ?>

				<div class="surfer-admin-footer">

					<div class="surfer-debug-box surfer-connected">
						<h3><?php esc_html_e( 'Debugging', 'surferseo' ); ?></h3>
						<p>
							<?php esc_html_e( 'In case you have any troubles with the plugin, please click the button below to download a .txt file with debug information, and send it to our Support team. This will speed up the debug process. Thank you.', 'surferseo' ); ?>
						</p>
						<a class="surfer-button surfer-button--secondary surfer-button--small" target="_blank" href="<?php echo esc_html( admin_url( 'admin.php?page=surfer&action=download_debug_data' ) ); ?>">
							<?php esc_html_e( 'Download debug data', 'surferseo' ); ?>
						</a>
					</div>

					<?php /* translators: %1$s & %2$s is replaced with "url" */ ?>
					<?php printf( wp_kses( __( 'In case of questions or troubles, please check our <a href="%1$s" target="_blank">documentation</a> or contact our <a href="%2$s" target="_blank">support team.</a>', 'surferseo' ), wp_kses_allowed_html( 'post' ) ), esc_html( Surferseo::get_instance()->url_wpsurfer_docs ), esc_html( 'mailto:support@surferseo.com' ) ); ?>
				</div>
			</div>
		</div>
	</form>
</div>
