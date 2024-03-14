<?php
/**
 * Wizard template
 *
 * @package SurferSEO
 */

?>
<div class="wrap wizard-wrapper">

	<header class="wizard-wrapper__header">
		<img src="<?php echo esc_html( Surfer()->get_baseurl() ); ?>assets/images/admin_menu_logo.svg" />
		<h3><?php esc_html_e( 'Help us making Surfer better for you', 'surferseo' ); ?></h3>
	</header>

	<article class="wizard-wrapper__content">

		<p><?php esc_html_e( 'We want to gather some basic information about how you are using the plugin, to make it better for you.', 'surferseo' ); ?></p>
		<p><?php esc_html_e( 'What data we gather?', 'surferseo' ); ?></p>
		<ul>
			<li><?php esc_html_e( 'What features of Surfer plugin you are using,', 'surferseo' ); ?></li>
			<li><?php esc_html_e( 'Your PHP and WordPress versions,', 'surferseo' ); ?></li>
			<li><?php esc_html_e( 'What plugins are you using.', 'surferseo' ); ?></li>
		</ul>
		<p><?php esc_html_e( 'This data will allow us to focus on developing most importat features, and prioriterize integrations with most popular plugins.', 'surferseo' ); ?></p>

		<p><?php esc_html_e( 'You can disable tracking everytime you want.', 'surferseo' ); ?></p>

		<?php /* translators: %s URL to privacy policy  */ ?>
		<p><?php printf( wp_kses( __( 'If you have any questions, please read our <a href="%s" target="_blank">privacy policy</a> or contact our support team.', 'surferseo' ), 'a' ), esc_html( Surfer()->get_surfer()->get_privacy_policy_url() ) ); ?></p>

		<a href="<?php echo esc_url( admin_url( 'admin.php?page=surfer&surfer_enable_tracking=1' ) ); ?>" class="button button-surfer-primary"><?php esc_html_e( 'Allow tracking', 'surferseo' ); ?></a>
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=surfer' ) ); ?>" class="button button-surfer"><?php esc_html_e( 'Skip', 'surferseo' ); ?></a>
	</article>

</div>
