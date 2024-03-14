<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Integration;

use ShopMagicVendor\Psr\Log\LoggerInterface;
use ShopMagicVendor\DI\Container;
use ShopMagicVendor\WPDesk\View\Resolver\Resolver;
use WPDesk\ShopMagic\Admin\Settings\SettingsCollection;
use WPDesk\ShopMagic\Admin\Settings\SettingTab;
use WPDesk\ShopMagic\Components\Routing\RoutesConfigurator;
use WPDesk\ShopMagic\DataSharing\DataProvider;
use WPDesk\ShopMagic\DataSharing\TestProvider;
use WPDesk\ShopMagic\DI\CompositeContainer;
use WPDesk\ShopMagic\Helper\PluginBag;
use WPDesk\ShopMagic\Helper\TemplateResolver;
use WPDesk\ShopMagic\Workflow\ActionExecution\ExecutionCreator\ExecutionCreator;
use WPDesk\ShopMagic\Workflow\ActionExecution\ExecutionCreator\ExecutionCreatorContainer;
use WPDesk\ShopMagic\Workflow\Extensions\Extension;
use WPDesk\ShopMagic\Workflow\Extensions\ExtensionsSet;
use WPDesk\ShopMagic\Workflow\Validator\WorkflowValidator;
use WPDesk\ShopMagic\Workflow\WorkflowInitializer;

/**
 * Class that grants access to some internal classes and info about ShopMagic to external plugins.
 */
final class ExternalPluginsAccess {

	/** @var string */
	private $version;

	/** @var WorkflowInitializer */
	private $automation_factory;

	/** @var ExecutionCreatorContainer */
	private $executor_factory;

	/** @var ExtensionsSet */
	private $extensions_set;

	/** @var Container */
	private $container;

	/** @var SettingsCollection */
	private $settings;

	/** @var TemplateResolver */
	private $resolver;

	public function __construct(
		PluginBag $plugin_bag,
		WorkflowInitializer $automation_factory,
		ExecutionCreatorContainer $executor_factory,
		ExtensionsSet $extensions_set,
		SettingsCollection $settings,
		Container $container,
		TemplateResolver $resolver
	) {
		$this->version            = $plugin_bag->get_version();
		$this->automation_factory = $automation_factory;
		$this->executor_factory   = $executor_factory;
		$this->extensions_set     = $extensions_set;
		$this->settings           = $settings;
		$this->container          = $container;
		$this->resolver           = $resolver;
	}

	/**
	 * @deprecated 3.0.9 Use add_validator
	 * @codeCoverageIgnore
	 */
	public function set_validator( WorkflowValidator $validator ): void {
		$this->automation_factory->add_validator( $validator );
	}

	public function add_validator( WorkflowValidator $validator ): void {
		$this->automation_factory->add_validator( $validator );
	}

	public function add_extension( Extension $extension ): void {
		$this->extensions_set->add_extension( $extension );
	}

	public function add_test_provider( DataProvider $provider ): void {
		$this->container->get( TestProvider::class )->add_provider( $provider );
	}

	public function append_setting_tab( SettingTab $tab ): void {
		$this->settings->append_setting_tab( $tab );
	}

	public function get_logger(): LoggerInterface {
		return $this->container->get( 'logger' );
	}

	/**
	 * @todo    refine this comment!
	 * You can extend the logic of how ShopMagic enqueues action to execute.
	 * WARNING: be cautious about adding the strategies because those are executed in order of
	 * addition. I.e. if you add two executors, which both returns `true` in `should_create`
	 * method, only the first one will execute.
	 * @see     QueueExecutionStrategyFactory::create_strategy() for implementation details
	 * @example Delayed Actions add-on
	 */
	public function add_execution_creator( ExecutionCreator $factory ): void {
		$this->executor_factory->add_execution_creator( $factory );
	}

	public function get_version(): string {
		return $this->version;
	}

	public function get_container(): CompositeContainer {
		return new CompositeContainer( [ $this->container ] );
	}

	public function get_routes_configurator(): RoutesConfigurator {
		return $this->container->make( 'routesConfigurator.api' );
	}

	/**
	 * Allow external plugins to add own templates via custom template
	 * resolver.
	 *
	 * The order or resolvers is always the following:
	 *  1. Theme template resolver
	 *  2. External template resolver (attached here)
	 *  3. Default template resolver (ShopMagic own templates)
	 *
	 * @param Resolver $resolver The easiest way to attach own template
	 *  resolver is to use `\ShopMagicVendor\WPDesk\View\Resolver\DirResolver`,
	 *  which takes a base directory path,
	 *  i.e. `new DirResolver('<my-plugin>/templates')`.
	 */
	public function add_template_resolver( Resolver $resolver ): void {
		$this->resolver->add_resolver( $resolver );
	}

}
