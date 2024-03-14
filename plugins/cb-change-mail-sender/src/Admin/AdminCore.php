<?php

namespace CBChangeMailSender\Admin;

/**
 * Class AdminCore.
 *
 * Handle all admin-related functions.
 *
 * @since 1.3.0
 */
class AdminCore {

	/**
	 * Admin menu slug.
	 *
	 * @since 1.3.0
	 *
	 * @var string
	 */
	const MENU_SLUG = 'cb_mail_sender';

	/**
	 * Admin page hook suffix.
	 *
	 * @since 1.3.0
	 *
	 * @var string
	 */
	private $page_hook_suffix;

	/**
	 * Constructor.
	 *
	 * @since 1.3.0
	 */
	public function __construct() {

		$this->hooks();
	}

	/**
	 * Admin hooks.
	 *
	 * @since 1.3.0
	 *
	 * @return void
	 */
	private function hooks() {

		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_filter( 'admin_footer_text', [ $this, 'admin_footer_text' ] );

		$this->get_settings();
		$this->get_smtp();
		$this->get_product_education();
	}

	/**
	 * Hook the admin menu.
	 *
	 * @since 1.3.0
	 *
	 * @return void
	 */
	public function admin_menu() {

		$capability = 'manage_options';
		$menu_slug  = self::MENU_SLUG;

		$this->page_hook_suffix = add_menu_page(
			__( 'Mail Sender Options', 'cb-mail' ),
			__( 'Mail Sender', 'cb-mail' ),
			$capability,
			$menu_slug,
			[ $this, 'render_admin_page' ],
			'dashicons-email'
		);

		add_submenu_page(
			$menu_slug,
			__( 'Change Mail Sender Settings', 'cb-mail' ),
			__( 'Settings', 'cb-mail' ),
			$capability,
			$menu_slug
		);

		add_submenu_page(
			$menu_slug,
			__( 'SMTP', 'cb-mail' ),
			__( 'SMTP', 'cb-mail' ),
			$capability,
			$menu_slug . '&tab=smtp',
			'__return_null'
		);

		// Fix submenu highlighting depending on the selected tab.
		add_filter( 'submenu_file', function( $submenu_file, $parent_file ) use ( $menu_slug ) {

			if ( $parent_file !== $menu_slug ) {
				return $submenu_file;
			}

			$tab = filter_input( INPUT_GET, 'tab' );

			if ( ! empty( $tab ) ) {
				return $menu_slug . '&tab=' . esc_html( $tab );
			}

			return $submenu_file;
		}, 10, 2 );
	}

	/**
	 * Render the admin page.
	 *
	 * @since 1.3.0
	 *
	 * @return void
	 */
	public function render_admin_page() {
		?>

		<div id="cb-change-mail-sender">
			<?php
			$this->render_admin_page_header();

			/**
			 * Render the tab content.
			 *
			 * @since 1.3.0
			 *
			 * @param string $tab Current active tab.
			 */
			do_action( 'cb_change_mail_sender_admin_core_render_admin_page_tab', $this->get_active_tab() );
			?>
		</div>
		<?php
	}

	/**
	 * Get the active tab.
	 *
	 * @since 1.3.0
	 *
	 * @return string
	 */
	public function get_active_tab() {

		$default_tab = 'settings';
		$tab         = filter_input( INPUT_GET, 'tab' );

		if ( empty( $tab ) || ! in_array( $tab, [ 'settings', 'smtp' ], true ) ) {
			return $default_tab;
		}

		return $tab;
	}

	/**
	 * Render the admin page header.
	 *
	 * @since 1.3.0
	 *
	 * @return void
	 */
	private function render_admin_page_header() {

		$nav_items = [
			'settings' => [
				'label' => __( 'Settings', 'cb-mail' ),
				'url'   => $this->get_url(),
			],
			'smtp'     => [
				'label' => __( 'SMTP', 'cb-mail' ),
				'url'   => add_query_arg( 'tab', 'smtp', $this->get_url() ),
			],
		];
		?>
		<div id="cb-change-mail-sender-header">
			<div id="cb-change-mail-sender-header-title">
				<div id="cb-change-mail-sender-header-title-image">
					<a href="<?php echo esc_url( $this->get_url() ); ?>">
						<?php
						printf(
							'<img src="%1$s" srcset="%2$s 2x" alt="%3$s"/>',
							esc_url( cb_change_mail_sender()->get_assets_url() . '/images/logo.png' ),
							esc_url( cb_change_mail_sender()->get_assets_url() . '/images/logo@2x.png' ),
							esc_html__( 'Change Mail Sender logo', 'cb-mail' )
						)
						?>
					</a>
				</div>

				<div id="cb-change-mail-sender-header-title-image-sep">
					<img src="<?php echo esc_url( cb_change_mail_sender()->get_assets_url() ); ?>/images/sep.png">
				</div>

				<div id="cb-change-mail-sender-header-title-nav">
					<?php
					foreach ( $nav_items as $nav_slug => $nav_item ) {

						$active_class = '';
						if ( $this->get_active_tab() === $nav_slug ) {
							$active_class = 'active';
						}
						?>
						<div class="cb-change-mail-sender-header-nav-item <?php echo esc_attr( $active_class ); ?>">
							<a href="<?php echo esc_url( $nav_item['url'] ); ?>"
								class="tab">
								<?php echo esc_html( $nav_item['label'] ); ?>
							</a>
						</div>
						<?php
					}
					?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Returns the admin page URL.
	 *
	 * @since 1.3.0
	 *
	 * @return string
	 */
	public function get_url() {

		return add_query_arg( 'page', 'cb_mail_sender', admin_url( 'admin.php' ) );
	}

	/**
	 * Display footer text that graciously asks users to rate the plugin.
	 *
	 * @since 1.3.0
	 *
	 * @param string $text Footer text.
	 *
	 * @return string
	 */
	public function admin_footer_text( $text ) {

		$current_screen = get_current_screen();

		if ( empty( $current_screen->id ) || $current_screen->id !== $this->page_hook_suffix ) {
			return $text;
		}

		$url  = 'https://wordpress.org/support/plugin/cb-change-mail-sender/reviews/?filter=5#new-post';

		return sprintf(
			wp_kses( /* translators: $1$s - Change Mail Sender plugin name; $2$s - WP.org review link; $3$s - WP.org review link. */
				__( 'Please rate <strong>%1$s</strong> <a href="%2$s" target="_blank" rel="noopener noreferrer">&#9733;&#9733;&#9733;&#9733;&#9733;</a> on <a href="%3$s" target="_blank" rel="noopener">WordPress.org</a> to help us spread the word.', 'cb-mail' ),
				[
					'a' => [
						'href'   => [],
						'target' => [],
						'rel'    => [],
					],
					'strong' => [],
				]
			),
			'Change Mail Sender',
			esc_url( $url ),
			esc_url( $url )
		);
	}

	/**
	 * Load the Admin Settings.
	 *
	 * @since 1.3.0
	 *
	 * @return Settings
	 */
	public function get_settings() {

		static $settings;

		if ( ! isset( $settings ) ) {
			$settings = new Settings();
		}

		return $settings;
	}

	/**
	 * Load the SMTP page.
	 *
	 * @since 1.3.0
	 *
	 * @return SMTP
	 */
	public function get_smtp() {

		static $smtp;

		if ( ! isset( $smtp ) ) {
			$smtp = new SMTP();
		}

		return $smtp;
	}

	/**
	 * Load the Product Education.
	 *
	 * @since 1.3.0
	 *
	 * @return ProductEducation
	 */
	public function get_product_education() {

		static $product_education;

		if ( ! isset( $product_education ) ) {
			$product_education = new ProductEducation();
		}

		return $product_education;
	}

	/**
	 * Get the admin page hook suffix.
	 *
	 * @since 1.3.0
	 *
	 * @return string
	 */
	public function get_page_hook_suffix() {

		return $this->page_hook_suffix;
	}
}
