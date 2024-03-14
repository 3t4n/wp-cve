<?php

namespace CBChangeMailSender\Admin;

use CBChangeMailSender\Helpers;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Settings.
 *
 * Handles the plugin settings.
 *
 * @since 1.3.0
 */
class Settings {

	/**
	 * Settings slug.
	 *
	 * @since 1.3.0
	 *
	 * @var string
	 */
	const TAB_SLUG = 'settings';

	/**
	 * Constructor.
	 *
	 * @since 1.3.0
	 */
	public function __construct() {

		$this->hooks();
	}

	/**
	 * Hook the settings.
	 *
	 * @since 1.3.0
	 *
	 * @return void
	 */
	private function hooks() {

		add_action( 'admin_init', [ $this, 'register_settings_fields' ] );
		add_action( 'current_screen', [ $this, 'current_screen' ] );
	}

	/**
	 * Register the Settings fields.
	 *
	 * @since 1.3.0
	 *
	 * @return void
	 */
	public function register_settings_fields() {

		add_settings_section(
			'cb_mail_sender_section',
			__( 'Settings', 'cb-mail' ),
			[ $this, 'settings_section_callback' ],
			'cb_mail_sender'
		);

		add_settings_field(
			'cb_mail_sender_id',
			__( 'Sender Name','cb-mail' ),
			[ $this, 'sender_id_field_callback' ],
			'cb_mail_sender',
			'cb_mail_sender_section'
		);

		register_setting( 'cb_mail_sender_section', 'cb_mail_sender_id' );

		add_settings_field(
			'cb_mail_sender_email_id',
			__( 'Sender Email', 'cb-mail' ),
			[ $this, 'sender_email_id_field_callback' ],
			'cb_mail_sender',
			'cb_mail_sender_section'
		);

		register_setting('cb_mail_sender_section', 'cb_mail_sender_email_id');
	}

	/**
	 * Settings section callback.
	 *
	 * @since 1.3.0
	 *
	 * @return void
	 */
	public function settings_section_callback() {
		?>
		<p>
			<?php esc_html_e( 'Change your default WordPress mail sender name and email.', 'cb-mail' ); ?>
		</p>
		<div class="cb-mail-sender-sep"></div>
		<?php
	}

	/**
	 * Render the "Sender Name" field.
	 *
	 * @since 1.3.0
	 *
	 * @return void
	 */
	public function sender_id_field_callback() {
		?>
		<input
			name="cb_mail_sender_id"
			type="text"
			class="regular-text"
			value="<?php echo esc_attr( get_option('cb_mail_sender_id') ); ?>"
			placeholder="<?php echo esc_attr( __( 'Mail Sender Name', 'cb-mail') ); ?>"
		/>
		<?php
	}

	/**
	 * Render the "Sender Email" field.
	 *
	 * @since 1.3.0
	 *
	 * @return void
	 */
	public function sender_email_id_field_callback() {
		?>
		<input
			name="cb_mail_sender_email_id"
			type="email"
			class="regular-text"
			value="<?php echo esc_attr( get_option( 'cb_mail_sender_email_id' ) ); ?>"
			placeholder="sender_email@yourdomain.com"
		/>
		<?php
	}

	/**
	 * Settings page-specific hooks.
	 *
	 * @since 1.3.0
	 *
	 * @return void
	 */
	public function current_screen() {

		$current_screen = get_current_screen();

		if ( $current_screen->id !== cb_change_mail_sender()->get_admin()->get_page_hook_suffix() ) {
			return;
		}

		if ( cb_change_mail_sender()->get_admin()->get_active_tab() !== self::TAB_SLUG ) {
			return;
		}

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'cb_change_mail_sender_admin_core_render_admin_page_tab', [ $this, 'render_content' ] );
	}

	/**
	 * Enqueue the plugin admin scripts.
	 *
	 * @since 1.3.0
	 *
	 * @return void
	 */
	public function enqueue_scripts() {

		// Enqueue Lity.
		wp_enqueue_script(
			'cb-change-mail-sender-admin-lity',
			cb_change_mail_sender()->get_assets_url() . "/lib/lity/lity.min.js",
			[],
			'2.4.1',
			true
		);

		wp_enqueue_style(
			'cb-change-mail-sender-admin-lity',
			cb_change_mail_sender()->get_assets_url() . "/lib/lity/lity.min.css",
			[],
			'2.4.1'
		);

		$min = '';

		if ( ! defined( 'SCRIPT_DEBUG' ) || ! SCRIPT_DEBUG ) {
			$min = '.min';
		}

		wp_enqueue_style(
			'cb-change-mail-sender-admin-settings',
			cb_change_mail_sender()->get_assets_url() . "/css/admin-settings{$min}.css",
			[],
			CB_CHANGE_MAIL_SENDER_VERSION
		);

		wp_enqueue_script(
			'cb-change-mail-sender-admin-settings',
			cb_change_mail_sender()->get_assets_url() . "/js/admin-settings{$min}.js",
			[ 'jquery' ],
			CB_CHANGE_MAIL_SENDER_VERSION,
			true
		);

		wp_localize_script(
			'cb-change-mail-sender-admin-settings',
			'cb_change_mail_sender_admin_settings',
			[
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
			]
		);
	}

	/**
	 * Render the tab content.
	 *
	 * @since 1.3.0
	 *
	 * @return void
	 */
	public function render_content() {
		?>
		<div id="cb-change-mail-sender-admin-wrap" class="wrap">
			<?php
			// Admin notices display after the first heading tag.
			echo '<h1 class="cb-change-mail-sender-hide">' . esc_html__( 'Change Mail Sender', 'cb-mail' ) . '</h1>';

			settings_errors();
			?>

			<form id="cb-change-mail-sender-form" action="options.php" method="POST">
				<?php do_settings_sections( 'cb_mail_sender' );?>
				<?php settings_fields( 'cb_mail_sender_section' );?>
				<div class="cb-mail-sender-sep cb-mail-sender-sep-last"></div>
				<?php submit_button();?>
			</form>

			<?php $this->render_product_education(); ?>
		</div>
		<?php
	}

	/**
	 * Render the product education below the Admin settings.
	 *
	 * @since 1.3.0
	 *
	 * @return void
	 */
	private function render_product_education() {

		$product_education_id = 'admin_settings_bottom';

		if ( ProductEducation::is_banner_dismissed( $product_education_id ) ) {
			return;
		}

		ob_start();
		?>
		<p>
			<?php
			echo wp_kses(
				sprintf(
					/* translators: %s: URL to WP Mail SMTP homepage */
					__( '<a target="_blank" href="%s">WP Mail SMTP</a> allows you to change the default mail sender, view detailed email logs, receive failed email alerts, fix deliverability problems with your WordPress emails and form notifications, plus much more!', 'cb-mail' ),
					esc_url( Helpers::get_utm_url( 'https://wpmailsmtp.com/', 'general' ) )
				),
				[
					'a' => [
						'target' => [],
						'href'   => [],
					],
				]
			)
			?>
		</p>

		<p>
			<?php esc_html_e( "We know you'll love the powerful features in WP Mail SMTP. It's used by over 3,000,000 websites.", 'cb-mail' ); ?>
		</p>

		<div class="cb-change-mail-sender-product-education-images-row">
			<?php

			$screenshots = [
				[
					'filename' => 'archive',
					'label'    => __( 'Email Logs', 'cb-mail' ),
				],
				[
					'filename' => 'single',
					'label'    => __( 'Individual Log', 'cb-mail' ),
				],
				[
					'filename' => 'reports',
					'label'    => __( 'Email Reports', 'cb-mail' ),
				],
			];

			foreach ( $screenshots as $screenshot ) {
				?>
				<div class="cb-change-mail-sender-product-education-images-row-image">
					<a href="<?php echo esc_url( cb_change_mail_sender()->get_assets_url() . "/images/prod-edu/{$screenshot['filename']}.png" ); ?>" data-lity data-lity-desc="<?php echo esc_attr( $screenshot['label'] ); ?>">
						<img src="<?php echo esc_url( cb_change_mail_sender()->get_assets_url(). "/images/prod-edu/{$screenshot['filename']}-thumbnail.png" ); ?>" alt="<?php esc_attr( $screenshot['label'] ); ?>">
					</a>
					<span><?php echo esc_html( $screenshot['label'] ) ?></span>
				</div>
			<?php
			}
			?>
		</div>

		<h3 class="cb-change-mail-sender-product-education-feature-title"><?php esc_html_e( 'Features:', 'cb-mail' ); ?></h3>

		<div class="benefits">
			<ul>
				<li class="plus"><?php esc_html_e( 'Resolve Email Issues: Use any of the 12 popular email providers (Gmail, Outlook, SendLayer,...).', 'cb-mail' ); ?></li>
				<li class="plus"><?php esc_html_e( 'Email Logging: Keep track of every email sent from your site.', 'cb-mail' ); ?></li>
				<li class="plus"><?php esc_html_e( 'Email Reports: Track your email deliverability and engagement with click&open tracking.', 'cb-mail' ); ?></li>
				<li class="plus"><?php esc_html_e( 'Failed Email Alerts: Receive notifications when email fails to send from your site.', 'cb-mail' ); ?></li>
				<li class="plus"><?php esc_html_e( 'Backup Connection: Send your emails even when your primary connection fails.', 'cb-mail' ); ?></li>
				<li class="plus"><?php esc_html_e( 'Smart Routing: Send emails based on your configured conditions.', 'cb-mail' ); ?></li>
				<li class="plus"><?php esc_html_e( 'Manage Notifications: Control which WP emails your site sends.', 'cb-mail' ); ?></li>
				<li class="plus"><?php esc_html_e( 'Multisite Support: Network settings for easy management.', 'cb-mail' ); ?></li>
				<li class="plus"><?php esc_html_e( 'Access to our world class support team.', 'cb-mail' ); ?></li>
			</ul>

			<ul>
				<li class="white-glove plus"><?php esc_html_e( 'White Glove Setup – Sit back and relax while we handle everything for you!', 'cb-mail' ); ?></li>
				<li class="arrow-right"><?php esc_html_e( 'Install WP Mail SMTP Pro plugin', 'cb-mail' ); ?></li>
				<li class="arrow-right"><?php esc_html_e( "Set up domain name verification (DNS)", 'cb-mail' ); ?></li>
				<li class="arrow-right"><?php esc_html_e( 'Configure SendLayer, Sendinblue, or SMTP.com service', 'cb-mail' ); ?></li>
				<li class="arrow-right"><?php esc_html_e( 'Set up WP Mail SMTP Pro plugin', 'cb-mail' ); ?></li>
				<li class="arrow-right"><?php esc_html_e( 'Test and verify email delivery', 'cb-mail' ); ?></li>
			</ul>
		</div>

		<?php
		$content = ob_get_clean();

		ProductEducation::create_banner(
			$product_education_id,
			__( 'Change the Default Mail Sender and Then Some!', 'cb-mail' ),
			$content,
			[
				'url'    => add_query_arg( 'tab', 'smtp', cb_change_mail_sender()->get_admin()->get_url() ),
				'label'  => __( 'Install WP Mail SMTP', 'cb-mail' ),
			]
		);
	}
}
