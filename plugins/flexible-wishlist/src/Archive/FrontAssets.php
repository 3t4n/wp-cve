<?php

namespace WPDesk\FlexibleWishlist\Archive;

use FlexibleWishlistVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use FlexibleWishlistVendor\WPDesk_Plugin_Info;
use WPDesk\FlexibleWishlist\Endpoint\WishlistCreateEndpoint;
use WPDesk\FlexibleWishlist\Endpoint\WishlistItemToggleEndpoint;
use WPDesk\FlexibleWishlist\Exception\InvalidSettingsOptionKey;
use WPDesk\FlexibleWishlist\Repository\SettingsRepository;
use WPDesk\FlexibleWishlist\Repository\WishlistRepository;
use WPDesk\FlexibleWishlist\Service\UserAuthManager;
use WPDesk\FlexibleWishlist\Settings\Option\TextAddItemOption;
use WPDesk\FlexibleWishlist\Settings\Option\TextCopyItemOption;
use WPDesk\FlexibleWishlist\Settings\Option\TextCreateWishlistButtonOption;
use WPDesk\FlexibleWishlist\Settings\Option\TextCreateWishlistInputOption;
use WPDesk\FlexibleWishlist\Settings\Option\TextNotLoggedInUserOption;
use WPDesk\FlexibleWishlist\Settings\Option\TextPopupTitleOption;

/**
 * Loads assets files needed for shop pages.
 */
class FrontAssets implements Hookable {

	/**
	 * @var WPDesk_Plugin_Info
	 */
	private $plugin_info;

	/**
	 * @var SettingsRepository
	 */
	private $settings_repository;

	/**
	 * @var UserDataGenerator
	 */
	private $user_data_generator;

	public function __construct(
		WPDesk_Plugin_Info $plugin_info,
		UserAuthManager $user_auth_manager,
		SettingsRepository $settings_repository,
		WishlistRepository $wishlist_repository,
		UserDataGenerator $user_data_generator = null
	) {
		$this->plugin_info         = $plugin_info;
		$this->settings_repository = $settings_repository;
		$this->user_data_generator = $user_data_generator ?: new UserDataGenerator( $user_auth_manager, $settings_repository, $wishlist_repository );
	}

	/**
	 * {@inheritdoc}
	 */
	public function hooks() {
		add_filter( 'wp_enqueue_scripts', [ $this, 'load_assets' ] );
		add_action( 'wp_footer', [ $this, 'load_js_data' ] );
	}

	/**
	 * @return void
	 * @internal
	 */
	public function load_assets() {
		$version = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? time() : $this->plugin_info->get_version();

		wp_register_style(
			'flexible-wishlist-front',
			untrailingslashit( $this->plugin_info->get_plugin_url() ) . '/assets/css/front.css',
			[],
			(string) $version
		);
		wp_enqueue_style( 'flexible-wishlist-front' );

		wp_register_script(
			'flexible-wishlist-front',
			untrailingslashit( $this->plugin_info->get_plugin_url() ) . '/assets/js/front.js',
			[],
			(string) $version,
			true
		);
		wp_enqueue_script( 'flexible-wishlist-front' );
	}

	/**
	 * @return void
	 * @throws InvalidSettingsOptionKey
	 * @internal
	 */
	public function load_js_data() {
		global $wp;
		$js_data   = $this->user_data_generator->get_user_data();
		$login_url = ( ! is_user_logged_in() )
			? sprintf(
				$this->settings_repository->get_value( TextNotLoggedInUserOption::FIELD_NAME ),
				'<a href="' . wp_login_url( home_url( $wp->request ) ) . '">',
				'</a>'
			)
			: '';

		?>

		<script>
			(function () {
				window.flexible_wishlist_data                              = <?php echo json_encode( $js_data ); ?>;
				window.flexible_wishlist_settings                          = {};
				window.flexible_wishlist_settings.create_wishlist_endpoint = '<?php echo esc_url_raw( WishlistCreateEndpoint::get_route_url() ); ?>';
				window.flexible_wishlist_settings.toggle_wishlist_endpoint = '<?php echo esc_url_raw( WishlistItemToggleEndpoint::get_route_url() ); ?>';
				window.flexible_wishlist_settings.i18n_popup_title         = '<?php echo esc_html( $this->settings_repository->get_value( TextPopupTitleOption::FIELD_NAME ) ); ?>';
				window.flexible_wishlist_settings.i18n_add_to_list         = '<?php echo esc_html( $this->settings_repository->get_value( TextAddItemOption::FIELD_NAME ) ); ?>';
				window.flexible_wishlist_settings.i18n_copy_to_list        = '<?php echo esc_html( $this->settings_repository->get_value( TextCopyItemOption::FIELD_NAME ) ); ?>';
				window.flexible_wishlist_settings.i18n_create_placeholder  = '<?php echo esc_html( $this->settings_repository->get_value( TextCreateWishlistInputOption::FIELD_NAME ) ); ?>';
				window.flexible_wishlist_settings.i18n_create_button       = '<?php echo esc_html( $this->settings_repository->get_value( TextCreateWishlistButtonOption::FIELD_NAME ) ); ?>';
				window.flexible_wishlist_settings.i18n_see_list            = '<?php echo esc_html( __( '(see more)', 'flexible-wishlist' ) ); ?>';
				window.flexible_wishlist_settings.i18n_log_in              = '<?php echo wp_kses_post( $login_url ); ?>';
			})();
		</script>
		<?php
	}
}
