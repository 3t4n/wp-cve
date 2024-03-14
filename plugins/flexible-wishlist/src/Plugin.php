<?php
/**
 * Plugin main class.
 *
 * @package WPDesk\FlexibleWishlist
 */

namespace WPDesk\FlexibleWishlist;

use FlexibleWishlistVendor\WPDesk\PluginBuilder\Plugin\AbstractPlugin;
use FlexibleWishlistVendor\WPDesk\PluginBuilder\Plugin\HookableCollection;
use FlexibleWishlistVendor\WPDesk\PluginBuilder\Plugin\HookableParent;
use FlexibleWishlistVendor\WPDesk_Plugin_Info;
use WPDesk\FlexibleWishlist\Archive;
use WPDesk\FlexibleWishlist\Endpoint;
use WPDesk\FlexibleWishlist\Migration\MigrationsManager;
use WPDesk\FlexibleWishlist\Notice\AnalyticsExtensionNotice;
use WPDesk\FlexibleWishlist\Notice\NoticeIntegration;
use WPDesk\FlexibleWishlist\Repository\SettingsRepository;
use WPDesk\FlexibleWishlist\Repository\UserRepository;
use WPDesk\FlexibleWishlist\Repository\WishlistItemRepository;
use WPDesk\FlexibleWishlist\Repository\WishlistRepository;
use WPDesk\FlexibleWishlist\Service;
use WPDesk\FlexibleWishlist\Settings;

/**
 * @package WPDesk\FlexibleWishlist
 */
class Plugin extends AbstractPlugin implements HookableCollection {
	use HookableParent;

	/**
	 * @var SettingsRepository
	 */
	private $settings_repository;

	/**
	 * @var WishlistItemRepository
	 */
	private $wishlist_item_repository;

	/**
	 * @var WishlistRepository
	 */
	private $wishlist_repository;

	/**
	 * @var UserRepository
	 */
	private $user_repository;

	/**
	 * @var Service\TemplateLoader
	 */
	private $template_loader;

	public function __construct( WPDesk_Plugin_Info $plugin_info ) {
		parent::__construct( $plugin_info );

		$this->plugin_url               = $this->plugin_info->get_plugin_url();
		$this->plugin_namespace         = $this->plugin_info->get_text_domain();
		$this->settings_repository      = new SettingsRepository();
		$this->wishlist_item_repository = new WishlistItemRepository();
		$this->wishlist_repository      = new WishlistRepository( $this->settings_repository );
		$this->user_repository          = new UserRepository( $this->wishlist_repository );
		$this->template_loader          = new Service\TemplateLoader( $plugin_info->get_plugin_dir(), 'flexible-wishlist' );
	}

	/**
	 * Initializes plugin external state.
	 *
	 * The plugin internal state is initialized in the constructor and the plugin should be internally consistent after
	 * creation. The external state includes hooks execution, communication with other plugins, integration with WC
	 * etc.
	 *
	 * @return void
	 */
	public function init() {
		$user_auth_manager = new Service\UserAuthManager( $this->user_repository, $this->wishlist_repository );

		$this->add_hookable( $user_auth_manager );
		$this->add_hookable( new MigrationsManager( $this->plugin_info ) );

		$this->add_hookable( new Archive\FrontAssets( $this->plugin_info, $user_auth_manager, $this->settings_repository, $this->wishlist_repository ) );
		$this->add_hookable( new Archive\PermalinksGenerator( $this->template_loader, $user_auth_manager, $this->settings_repository, $this->wishlist_repository, $this->wishlist_item_repository, $this->user_repository ) );
		$this->add_hookable( new Archive\MenuGenerator( $this->settings_repository ) );
		$this->add_hookable( new Archive\ButtonGenerator( $this->template_loader, $user_auth_manager, $this->settings_repository ) );
		$this->add_hookable( new Endpoint\EndpointIntegrator( new Endpoint\WishlistCreateEndpoint( $user_auth_manager, $this->settings_repository, $this->wishlist_repository, $this->user_repository ) ) );
		$this->add_hookable( new Endpoint\EndpointIntegrator( new Endpoint\WishlistUpdateEndpoint( $user_auth_manager, $this->wishlist_repository ) ) );
		$this->add_hookable( new Endpoint\EndpointIntegrator( new Endpoint\WishlistItemUpdateEndpoint( $user_auth_manager, $this->wishlist_repository, $this->wishlist_item_repository ) ) );
		$this->add_hookable( new Endpoint\EndpointIntegrator( new Endpoint\WishlistItemToggleEndpoint( $user_auth_manager, $this->user_repository, $this->settings_repository, $this->wishlist_repository, $this->wishlist_item_repository ) ) );
		$this->add_hookable( new Endpoint\EndpointIntegrator( new Endpoint\WishlistItemRemoveEndpoint( $user_auth_manager, $this->wishlist_repository, $this->wishlist_item_repository ) ) );
		$this->add_hookable( new Settings\MenuSettingsUpdater( $this->settings_repository ) );
		$this->add_hookable( new Settings\SettingsPageGenerator( $this->plugin_info, $this->template_loader, $this->settings_repository ) );
		$this->add_hookable( new Settings\SettingsTranslator( $this->settings_repository ) );
		$this->add_hookable( new Tracker\DeactivationTracker( $this->plugin_info ) );
		$this->add_hookable( new Service\PluginDataCleaner( $this->plugin_info ) );
		$this->add_hookable( new Service\PluginDataCleaner( $this->plugin_info ) );
		$this->add_hookable( new NoticeIntegration( $this->plugin_info, new AnalyticsExtensionNotice( $this ) ) );
		$this->add_hookable( new Marketing\SupportPage( $this->template_loader ) );

		parent::init();
	}

	/**
	 * Integrate with WordPress and with other plugins using action/filter system.
	 *
	 * @return void
	 */
	public function hooks() {
		parent::hooks();

		add_action(
			'woocommerce_init',
			function () {
				do_action( 'flexible_wishlist/init', $this->settings_repository, $this->wishlist_repository );
			}
		);

		$this->hooks_on_hookable_objects();
	}

	/**
	 * {@inheritdoc}
	 */
	public function links_filter( $links ): array {
		$plugin_links = [];

		if ( ! is_plugin_active( 'flexible-wishlist-analytics/flexible-wishlist-analytics.php' ) ) {
			$plugin_links[] = '<a href="' . esc_url( menu_page_url( 'flexible-wishlist-marketing', false ) ) . '" style="color:#007050;font-weight: bold">' . __( 'Start here', 'flexible-wishlist' ) . '</a>';
		}

		$plugin_links[] = '<a href="' . menu_page_url( Settings\SettingsPageGenerator::MENU_PAGE_SLUG, false ) . '">' . __( 'Settings', 'flexible-wishlist' ) . '</a>';
		$plugin_links[] = '<a href="' . esc_url( __( 'https://wpde.sk/fw-settings-row-action-docs', 'flexible-wishlist' ) ) . '" target="_blank">' . __( 'Docs', 'flexible-wishlist' ) . '</a>';
		$plugin_links[] = '<a href="' . esc_url( __( 'https://wpde.sk/fw-settings-row-action-support', 'flexible-wishlist' ) ) . '" target="_blank">' . __( 'Support', 'flexible-wishlist' ) . '</a>';

		if ( ! is_plugin_active( 'flexible-wishlist-analytics/flexible-wishlist-analytics.php' ) ) {
			$plugin_links[] = '<a href="' . esc_attr( __( 'https://wpde.sk/fw-settings-row-action-upgrade', 'flexible-wishlist' ) ) . '" target="_blank" style="color:#FF9743;font-weight:bold;">' . __( 'Upgrade to PRO &rarr;', 'flexible-wishlist' ) . '</a>';
		}

		return array_merge( $plugin_links, $links );
	}
}
