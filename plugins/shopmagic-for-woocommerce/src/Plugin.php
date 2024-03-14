<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic;

use ShopMagicVendor\Psr\Container\ContainerInterface;
use ShopMagicVendor\DI\ContainerBuilder;
use ShopMagicVendor\WPDesk\Migrations\Migrator;
use ShopMagicVendor\WPDesk\PluginBuilder\Plugin\AbstractPlugin;
use ShopMagicVendor\WPDesk\PluginBuilder\Plugin\Activateable;
use ShopMagicVendor\WPDesk\PluginBuilder\Plugin\Deactivateable;
use ShopMagicVendor\WPDesk\PluginBuilder\Plugin\HookableCollection;
use ShopMagicVendor\WPDesk\PluginBuilder\Plugin\HookableParent;
use ShopMagicVendor\WPDesk\ShowDecision\PostTypeStrategy;
use ShopMagicVendor\WPDesk_Plugin_Info;
use WPDesk\ShopMagic\Admin\Admin;
use WPDesk\ShopMagic\Admin\RateNotice\RateNotices;
use WPDesk\ShopMagic\Admin\RateNotice\TwoWeeksNotice;
use WPDesk\ShopMagic\Admin\Settings\GeneralSettings;
use WPDesk\ShopMagic\Admin\Settings\ModulesSettings;
use WPDesk\ShopMagic\Admin\Welcome\Welcome;
use WPDesk\ShopMagic\Components\HookProvider\Conditional;
use WPDesk\ShopMagic\Components\Routing\RestRoutesRegistry;
use WPDesk\ShopMagic\Components\Routing\WpRoutesRegistry;
use WPDesk\ShopMagic\Customer\Guest\Interceptor\BackgroundOrderInterceptor;
use WPDesk\ShopMagic\Helper\PluginBag;
use WPDesk\ShopMagic\Helper\TemplateResolver;
use WPDesk\ShopMagic\Helper\WordPressPluggableHelper;
use WPDesk\ShopMagic\Integration\ExternalPluginsAccess;
use WPDesk\ShopMagic\Modules\Module;
use WPDesk\ShopMagic\Modules\Mulitilingual\MultilingualModule;
use WPDesk\ShopMagic\Workflow\Action\Action;
use WPDesk\ShopMagic\Workflow\Automation\AutomationPostType;
use WPDesk\ShopMagic\Workflow\Event\Builtin\Order\OrderPending;
use WPDesk\ShopMagic\Workflow\Event\Event;
use WPDesk\ShopMagic\Workflow\Event\EventMutex;
use WPDesk\ShopMagic\Workflow\Extensions\Builtin\CoreExtension;
use WPDesk\ShopMagic\Workflow\Extensions\Builtin\FlexibleCheckoutFieldsExtension;
use WPDesk\ShopMagic\Workflow\Extensions\Builtin\FlexibleShippingExtension;
use WPDesk\ShopMagic\Workflow\Extensions\Builtin\PaidPromotionExtension;
use WPDesk\ShopMagic\Workflow\Extensions\Builtin\StagingExtension;
use WPDesk\ShopMagic\Workflow\Extensions\Builtin\WooCommerceExtension;
use WPDesk\ShopMagic\Workflow\Extensions\ExtensionsSet;
use WPDesk\ShopMagic\Workflow\Filter\ComparisonType\ComparisonType;
use WPDesk\ShopMagic\Workflow\Filter\Filter;
use WPDesk\ShopMagic\Workflow\Placeholder\Placeholder;
use WPDesk\ShopMagic\Workflow\Placeholder\TemplateRendererForPlaceholders;
use WPDesk\ShopMagic\Workflow\Queue\Queue;
use WPDesk\ShopMagic\Workflow\Validator\FailingLanguageValidator;
use WPDesk\ShopMagic\Workflow\WorkflowInitializer;

/**
 * Main plugin class. The most important flow decisions are made here.
 */
final class Plugin extends AbstractPlugin implements HookableCollection, Activateable, Deactivateable {
	use HookableParent;

	private const ADDONS = [
		'shopmagic-advanced-filters/shopmagic-advanced-filters.php',
		'shopmagic-customer-coupons/shopmagic-customer-coupons.php',
		'shopmagic-delayed-actions/shopmagic-delayed-actions.php',
		'shopmagic-for-gravity-forms/shopmagic-for-gravity-forms.php',
		'shopmagic-manual-actions/shopmagic-manual-actions.php',
		'shopmagic-reviews/shopmagic-reviews.php',
		'shopmagic-slack/shopmagic-slack.php',
		'shopmagic-woocommerce-bookings/shopmagic-woocommerce-bookings.php',
		'shopmagic-woocommerce-memberships/shopmagic-woocommerce-memberships.php',
		'shopmagic-woocommerce-subscriptions/shopmagic-woocommerce-subscriptions.php',
	];

	/** @var array<int, class-string<Module>> */
	private const MODULES = [
		MultilingualModule::class,
	];

	/** @var WPDesk_Plugin_Info */
	protected $plugin_info;

	/** @var ContainerInterface */
	private $container;

	/** @var array<string, Module> */
	private $modules = [];

	public function __construct( WPDesk_Plugin_Info $plugin_info ) {
		/** @noinspection PhpParamsInspection */
		parent::__construct( $plugin_info );

		$this->set_class_aliases();

		TemplateResolver::set_root_path( $plugin_info->get_plugin_dir() );
	}

	private function set_class_aliases(): void {
		class_alias( self::class, 'ShopMagic' );
		class_alias( Placeholder::class, \WPDesk\ShopMagic\Placeholder\BasicPlaceholder::class );
		class_alias( Event::class, \WPDesk\ShopMagic\Event\BasicEvent::class );
		class_alias( Action::class, \WPDesk\ShopMagic\Action\BasicAction::class );
		class_alias( Filter::class, \WPDesk\ShopMagic\Filter\BasicFilter::class );
		class_alias( ComparisonType::class, WPDesk\ShopMagic\Filter\ComparisionType\ComparisionType::class );
		class_alias(
			\ShopMagicVendor\WPDesk\Forms\Field\CheckboxField::class,
			\WPDesk\ShopMagic\FormField\Field\CheckboxField::class
		);
		class_alias(
			\ShopMagicVendor\WPDesk\Forms\Field\Header::class,
			\WPDesk\ShopMagic\FormField\Field\Header::class
		);
		class_alias(
			\ShopMagicVendor\WPDesk\Forms\Field\HiddenField::class,
			\WPDesk\ShopMagic\FormField\Field\HiddenField::class
		);
		class_alias(
			\ShopMagicVendor\WPDesk\Forms\Field\InputTextField::class,
			\WPDesk\ShopMagic\FormField\Field\InputTextField::class
		);
		class_alias(
			\ShopMagicVendor\WPDesk\Forms\Field\MultipleInputTextField::class,
			\WPDesk\ShopMagic\FormField\Field\MultipleInputTextField::class
		);
		class_alias(
			\ShopMagicVendor\WPDesk\Forms\Field\ImageInputField::class,
			\WPDesk\ShopMagic\FormField\Field\ImageInputField::class
		);
		class_alias(
			\ShopMagicVendor\WPDesk\Forms\Field\NoOnceField::class,
			\WPDesk\ShopMagic\FormField\Field\NoOnceField::class
		);
		class_alias(
			\ShopMagicVendor\WPDesk\Forms\Field\NoValueField::class,
			\WPDesk\ShopMagic\FormField\Field\NoValueField::class
		);
		class_alias(
			\ShopMagicVendor\WPDesk\Forms\Field\Paragraph::class,
			\WPDesk\ShopMagic\FormField\Field\Paragraph::class
		);
		class_alias(
			\ShopMagicVendor\WPDesk\Forms\Field\ProductSelect::class,
			\WPDesk\ShopMagic\FormField\Field\ProductSelect::class
		);
		class_alias(
			\ShopMagicVendor\WPDesk\Forms\Field\RadioField::class,
			\WPDesk\ShopMagic\FormField\Field\RadioField::class
		);
		class_alias(
			\ShopMagicVendor\WPDesk\Forms\Field\SelectField::class,
			\WPDesk\ShopMagic\FormField\Field\SelectField::class
		);
		class_alias(
			\ShopMagicVendor\WPDesk\Forms\Field\SubmitField::class,
			\WPDesk\ShopMagic\FormField\Field\SubmitField::class
		);
		class_alias(
			\ShopMagicVendor\WPDesk\Forms\Field\TextAreaField::class,
			\WPDesk\ShopMagic\FormField\Field\TextAreaField::class
		);
		class_alias(
			\ShopMagicVendor\WPDesk\Forms\Field\WooSelect::class,
			\WPDesk\ShopMagic\FormField\Field\WooSelect::class
		);
		class_alias(
			\ShopMagicVendor\WPDesk\Forms\Field\WyswigField::class,
			\WPDesk\ShopMagic\FormField\Field\WyswigField::class
		);

		class_alias(
			\ShopMagicVendor\WPDesk\Forms\Field\NoValueField::class,
			\WPDesk\ShopMagic\FormField\NoValueField::class
		);
		class_alias(
			\ShopMagicVendor\WPDesk\Forms\Field\BasicField::class,
			\WPDesk\ShopMagic\FormField\BasicField::class
		);
	}

	private function pre_init(): void {
		$this->initialize_modules();
		$this->initialize_container();
	}

	public function init(): void {
		$this->pre_init();

		$this->container->get( Migrator::class )->migrate();

		parent::init();
	}

	/**
	 * Check if any of ShopMagic PRO add-ons is active
	 */
	private function is_pro_active(): bool {
		foreach ( self::ADDONS as $addon ) {
			if ( WordPressPluggableHelper::is_plugin_active( $addon ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Returns true when debug mode is on.
	 */
	private function is_debug_mode(): bool {
		$enable_log = GeneralSettings::get_option( 'sm_enable_logs' );
		if ( $enable_log === '1' || $enable_log === true ) {
			return true;
		}
		$helper_options = get_option( 'wpdesk_helper_options', [] );

		return isset( $helper_options['debug_log'] ) && '1' === $helper_options['debug_log'];
	}

	/**
	 * Integrate with WordPress and with other plugins using action/filter system.
	 */
	public function hooks(): void {
		parent::hooks();

		// $pro_is_active = $this->is_pro_active();
		// require_once __DIR__ . '/Admin/Welcome/Popups.php';

		add_action(
			'plugins_loaded',
			function (): void {
				foreach ( $this->modules as $module ) {
					$module->set_container( $this->container );
					$module->initialize();
				}

				OrderPending::initialize_pending_on_created_check( $this->container->get( Queue::class ) );

				foreach ( $this->container->get( 'hookable.plugins_loaded' ) as $item ) {
					$item->hooks();
				}

				$this->container->get( BackgroundOrderInterceptor::class )
						->start_guest_extraction_if_needed();
			}
		);

		add_action(
			'rest_api_init',
			function () {
				$routes       = require $this->plugin_info->get_plugin_dir() . '/config/routes/api.php';
				$configurator = $this->container->get( 'routesConfigurator.api' );
				$registry     = $this->container->get( RestRoutesRegistry::class );
				$routes( $configurator );
				do_action( 'shopmagic/core/rest/init' );
				$registry->hooks();
			}
		);

		add_action(
			'init',
			function (): void {
				$extensions_set = $this->container->get( ExtensionsSet::class );
				$this->register_extensions( $extensions_set );

				// FIXME: this should belong to services definition as
				// it is the default behavior, but due to stateful
				// nature of WorkflowValidator we have to do this as
				// a trick, until fixed.
				$this->container->get( WorkflowInitializer::class )->add_validator( $this->container->get( FailingLanguageValidator::class ) );

				/**
				 * If you want to write an integration with ShopMagic you should use this action.
				 * This action is executed when the ShopMagic core is ready to be used and provides a ExternalPluginsAccess object to facilitate integration.
				 * Please make sure that your integration is checking $plugin_access->get_version() to ensure that your integration is compatible with current ShopMagic version.
				 * Remember that we use semantic versioning so can be sure that every time we make a breaking change we also increase a major version of the plugin.
				 *
				 * @param ExternalPluginsAccess $plugin_access Object with various tools that can be used for integration.
				 */
				do_action(
					'shopmagic/core/initialized/v2',
					$this->container->get( ExternalPluginsAccess::class )
				);

				$resolver = $this->container->get( TemplateResolver::class );
				$resolver->add_resolver( $this->container->get( 'resolver.default' ) );

				$extensions_set->init_extensions();

				foreach ( $this->container->get( 'hookable.init' ) as $item ) {
					if ( $item instanceof Conditional && ! $item::is_needed() ) {
						continue;
					}
					$item->hooks();
				}

				$this->register_public_routes();

				do_action(
					'shopmagic/core/after_initialization',
					$this->container->get( ExtensionsSet::class )
				);

				$automations = $this->container->get( WorkflowInitializer::class )
						->initialize_active_automations();

				if ( is_admin() ) {
					// FIXME: This should land in DI container. BTW, it doesn't work either, as
					// we dont have AutomationPostType page in admin (due to JS app).
					( new RateNotices(
						[
							new TwoWeeksNotice(
								$this->plugin_url . '/assets',
								$automations,
								new PostTypeStrategy( AutomationPostType::TYPE )
							),
						]
					) )->hooks();
				}
			},
			9
		);

		add_action(
			'flexible_checkout_fields/init',
			function ( $integrator ): void {
				$extensions_set = $this->container->get( ExtensionsSet::class );
				$fcf_extension  = new FlexibleCheckoutFieldsExtension( $integrator, $this->container->get( 'logger' ) );
				$extensions_set->add_extension( $fcf_extension );
			}
		);

		if ( is_admin() ) {
			$this->container->get( Admin::class )->hooks();
		}

		$this->hooks_on_hookable_objects();
	}

	private function register_extensions( ExtensionsSet $extensions ): void {
		$extensions->add_extension( $this->container->get( CoreExtension::class ) );

		if ( WordPressPluggableHelper::is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$extensions->add_extension(
				new WooCommerceExtension(
					new EventMutex(),
					$this->container->get( TemplateRendererForPlaceholders::class )
				)
			);
		}

		$extensions->add_extension( new PaidPromotionExtension( $this->is_pro_active() ) );
		$extensions->add_extension( new FlexibleShippingExtension( $this->container->get( TemplateRendererForPlaceholders::class ) ) );
		$extensions->add_extension( new StagingExtension() );
	}

	private function register_public_routes(): void {
		$routes   = require $this->plugin_info->get_plugin_dir() . '/config/routes/public.php';
		$registry = $this->container->get( WpRoutesRegistry::class );
		$routes( $this->container->get( 'routesConfigurator.public' ) );
		$registry->hooks();
	}

	/**
	 * Quick links on plugins page.
	 *
	 * @param string[] $links .
	 *
	 * @return string[]
	 * @internal
	 */
	public function links_filter( $links ): array {
		$plugin_links = [];

		$plugin_links[] = '<a href="' . admin_url( 'admin.php?page=shopmagic-admin#/settings' ) . '">' . __(
			'Settings',
			'shopmagic-for-woocommerce'
		) . '</a>';
		$plugin_links[] = '<a href="https://shopmagic.app/docs/" target="_blank">' . __(
			'Docs',
			'shopmagic-for-woocommerce'
		) . '</a>';
		$plugin_links[] = '<a href="https://wordpress.org/support/plugin/shopmagic-for-woocommerce/" target="_blank">' . __(
			'Support',
			'shopmagic-for-woocommerce'
		) . '</a>';

		if ( ! $this->is_pro_active() ) {
			$plugin_links[] = '<a href="https://shopmagic.app/pricing/?utm_source=user-site&utm_medium=quick-link&utm_campaign=shopmagic-upgrade" target="_blank" style="color:#d64e07;font-weight:bold;">' . __(
				'Buy PRO',
				'shopmagic-for-woocommerce'
			) . '</a>';
		}

		return array_merge( $plugin_links, $links );
	}

	public function activate(): void {
		( new Welcome() )->welcome_activate();
	}

	public function deactivate(): void {
		( new Welcome() )->welcome_deactivate();
	}

	public function build_container(): ContainerBuilder {
		$builder = new ContainerBuilder();

		$plugin = new PluginBag();
		$plugin->set_plugin_info( $this->plugin_info );
		$plugin->set_debug( $this->is_debug_mode() );
		$plugin->set_pro( $this->is_pro_active() );

		$builder->addDefinitions(
			$this->plugin_info->get_plugin_dir() . '/config/services.inc.php',
			[ PluginBag::class => $plugin ]
		);

		// Compilation is disabled for now because new implementation of modules requires us to.
		// We cannot compile container ahead as modules are conditionally included.
		// if ( ! getenv( 'DEVELOPMENT' ) ) {
		// $builder->enableCompilation( __DIR__ . '/../cache' );
		// }

		$this->prepare_container( $builder );

		return $builder;
	}

	/**
	 * @return void
	 */
	public function initialize_modules(): void {
		$modules_info = ModulesSettings::get_settings_persistence();
		foreach ( self::MODULES as $module ) {
			$new_module = new $module();
			if ( $modules_info->has( $new_module->get_name() ) ) {
				$this->modules[] = $new_module;
			}
		}
	}

	/**
	 * @return void
	 * @throws \Exception
	 */
	public function initialize_container(): void {
		$builder = $this->build_container();

		$this->container = $builder->build();
	}

	private function prepare_container( ContainerBuilder $builder ): void {
		foreach ( $this->modules as $module ) {
			$module->build( $builder );
		}
	}
}
