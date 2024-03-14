<?php

declare( strict_types=1 );

use ShopMagicVendor\Psr\Container\ContainerInterface;
use ShopMagicVendor\Psr\Log\LoggerInterface;
use ShopMagicVendor\Psr\Log\LogLevel;
use ShopMagicVendor\Monolog\Formatter\LineFormatter;
use ShopMagicVendor\Monolog\Handler\ErrorLogHandler;
use ShopMagicVendor\Monolog\Handler\FingersCrossedHandler;
use ShopMagicVendor\Monolog\Logger;
use ShopMagicVendor\Monolog\Processor\PsrLogMessageProcessor;
use ShopMagicVendor\Monolog\Processor\UidProcessor;
use ShopMagicVendor\WPDesk\Dashboard\DashboardWidget;
use ShopMagicVendor\WPDesk\Migrations\Migrator;
use ShopMagicVendor\WPDesk\Migrations\WpdbMigrator;
use ShopMagicVendor\WPDesk\Notice\AjaxHandler;
use ShopMagicVendor\WPDesk\View\Renderer\SimplePhpRenderer;
use ShopMagicVendor\WPDesk\View\Renderer\Renderer;
use ShopMagicVendor\WPDesk\View\Resolver\DirResolver;
use WPDesk\ShopMagic\Admin;
use WPDesk\ShopMagic\Admin\Settings\ModulesInfoContainer;
use WPDesk\ShopMagic\Api\Controller\AutomationController;
use WPDesk\ShopMagic\Api\Controller\LogController;
use WPDesk\ShopMagic\Api\Normalizer\FieldNormalizer\ActionFieldNormalizer;
use WPDesk\ShopMagic\Api\Normalizer\FieldNormalizer\CheckboxFieldNormalizer;
use WPDesk\ShopMagic\Api\Normalizer\FieldNormalizer\DateFieldNormalizer;
use WPDesk\ShopMagic\Api\Normalizer\FieldNormalizer\JsonSchemaFieldNormalizer;
use WPDesk\ShopMagic\Api\Normalizer\FieldNormalizer\JsonSchemaNormalizer;
use WPDesk\ShopMagic\Api\Normalizer\FieldNormalizer\ModuleFieldNormalizer;
use WPDesk\ShopMagic\Api\Normalizer\FieldNormalizer\MultipleSelectFieldNormalizer;
use WPDesk\ShopMagic\Api\Normalizer\FieldNormalizer\NoValueFieldNormalizer;
use WPDesk\ShopMagic\Api\Normalizer\FieldNormalizer\ParagraphFieldNormalizer;
use WPDesk\ShopMagic\Api\Normalizer\FieldNormalizer\ProductSelectFieldNormalizer;
use WPDesk\ShopMagic\Api\Normalizer\FieldNormalizer\SelectFieldNormalizer;
use WPDesk\ShopMagic\Api\Normalizer\FieldNormalizer\TextFieldNormalizer;
use WPDesk\ShopMagic\Api\Normalizer\FieldNormalizer\TimeFieldNormalizer;
use WPDesk\ShopMagic\Api\Normalizer\NormalizerCollection;
use WPDesk\ShopMagic\Api\Normalizer\SubscriberHydrator;
use WPDesk\ShopMagic\Api\Normalizer\WorkflowAutomationDenormalizer;
use WPDesk\ShopMagic\Api\Normalizer\WorkflowAutomationNormalizer;
use WPDesk\ShopMagic\Components\Mailer\Mailer;
use WPDesk\ShopMagic\Components\Mailer\WPMailMailer;
use WPDesk\ShopMagic\Components\Routing\Controller\ArgumentResolver;
use WPDesk\ShopMagic\Components\Routing\Controller\ContainerControllerResolver;
use WPDesk\ShopMagic\Components\Routing\Controller\ControllerResolver;
use WPDesk\ShopMagic\Components\Routing\RestRoutesRegistry;
use WPDesk\ShopMagic\Components\Routing\RoutesConfigurator;
use WPDesk\ShopMagic\Components\Routing\WpRoutesRegistry;
use WPDesk\ShopMagic\Components\UrlGenerator\FrontendUrlGenerator;
use WPDesk\ShopMagic\Components\UrlGenerator\RestUrlGenerator;
use WPDesk\ShopMagic\Customer\CustomerRepository;
use WPDesk\ShopMagic\Customer\Guest\GuestDataAccess;
use WPDesk\ShopMagic\Customer\Guest\GuestHydrator;
use WPDesk\ShopMagic\Customer\Guest\GuestManager;
use WPDesk\ShopMagic\Customer\Guest\GuestMetaFactory;
use WPDesk\ShopMagic\Customer\Guest\GuestMetaManager;
use WPDesk\ShopMagic\Customer\Guest\GuestMetaRepository;
use WPDesk\ShopMagic\Customer\Guest\GuestRepository;
use WPDesk\ShopMagic\Customer\Guest\Interceptor\BackgroundOrderInterceptor;
use WPDesk\ShopMagic\Customer\Guest\Interceptor\CommentGuestInterceptor;
use WPDesk\ShopMagic\Customer\Guest\Interceptor\GuestOrderIntegration;
use WPDesk\ShopMagic\Customer\Guest\Interceptor\GuestOrderUpdate;
use WPDesk\ShopMagic\Customer\Guest\Interceptor\GuestProductIntegration;
use WPDesk\ShopMagic\Customer\Guest\Interceptor\OrderGuestInterceptor;
use WPDesk\ShopMagic\Customer\User\UserRepository;
use WPDesk\ShopMagic\DataSharing\CustomerTestProvider;
use WPDesk\ShopMagic\DataSharing\OrderTestProvider;
use WPDesk\ShopMagic\DataSharing\TestProvider;
use WPDesk\ShopMagic\Extensions\ExtensionsSet;
use WPDesk\ShopMagic\Frontend\Interceptor\CurrentCustomer;
use WPDesk\ShopMagic\Frontend\Interceptor\PreSubmitData;
use WPDesk\ShopMagic\Helper\PluginBag;
use WPDesk\ShopMagic\Helper\TemplateResolver;
use WPDesk\ShopMagic\Helper\WordPressPluggableHelper;
use WPDesk\ShopMagic\HookEmitter;
use WPDesk\ShopMagic\Integration\ExternalPluginsAccess;
use WPDesk\ShopMagic\Integration\Mailchimp\APITools;
use WPDesk\ShopMagic\Integration\Mailchimp\MailchimpApi;
use WPDesk\ShopMagic\Integration\Mailchimp\MissingKeyApi;
use WPDesk\ShopMagic\Integration\Mailchimp\Settings;
use WPDesk\ShopMagic\Marketing\HookProviders\ConfirmedSubscriptionSaver;
use WPDesk\ShopMagic\Marketing\HookProviders\FrontendListSubscription;
use WPDesk\ShopMagic\Marketing\HookProviders\ListsOnCheckout;
use WPDesk\ShopMagic\Marketing\HookProviders\PreferencesUpdate;
use WPDesk\ShopMagic\Marketing\HookProviders\RecordEmailSending;
use WPDesk\ShopMagic\Marketing\HookProviders\SignUpCustomerOnSubmit;
use WPDesk\ShopMagic\Marketing\HookProviders\WooCommerceAccountPreferences;
use WPDesk\ShopMagic\Marketing\MailTracking\TrackedClickObjectManager;
use WPDesk\ShopMagic\Marketing\MailTracking\TrackedClickRepository;
use WPDesk\ShopMagic\Marketing\MailTracking\TrackedEmailClickFactory;
use WPDesk\ShopMagic\Marketing\MailTracking\TrackedEmailHydrator;
use WPDesk\ShopMagic\Marketing\MailTracking\TrackedEmailObjectManager;
use WPDesk\ShopMagic\Marketing\MailTracking\TrackedEmailRepository;
use WPDesk\ShopMagic\Marketing\Subscribers\AudienceList\AudienceListObjectManager;
use WPDesk\ShopMagic\Marketing\Subscribers\AudienceList\AudienceListRepository;
use WPDesk\ShopMagic\Marketing\Subscribers\AudienceList\CommunicationListPostType;
use WPDesk\ShopMagic\Marketing\Subscribers\CommunicationPreferencesRenderer;
use WPDesk\ShopMagic\Marketing\Subscribers\ConfirmationDispatcher;
use WPDesk\ShopMagic\Marketing\Subscribers\ListSubscriber\SingleListSubscriberHydrator;
use WPDesk\ShopMagic\Marketing\Subscribers\ListSubscriber\SubscriberObjectRepository;
use WPDesk\ShopMagic\Marketing\Subscribers\ListSubscriber\SubscriptionManager;
use WPDesk\ShopMagic\Marketing\Subscribers\SubscriptionFormShortcode;
use WPDesk\ShopMagic\Tracker;
use WPDesk\ShopMagic\Workflow\ActionExecution\ExecuteNow;
use WPDesk\ShopMagic\Workflow\ActionExecution\ExecutionCreator\ExecutionCreator;
use WPDesk\ShopMagic\Workflow\ActionExecution\ExecutionCreator\ExecutionCreatorContainer;
use WPDesk\ShopMagic\Workflow\ActionExecution\ExecutionCreator\FailingDelayExecution;
use WPDesk\ShopMagic\Workflow\ActionExecution\ExecutionCreator\QueueExecutionCreator;
use WPDesk\ShopMagic\Workflow\ActionExecution\QueueActionRunner;
use WPDesk\ShopMagic\Workflow\Automation\AutomationObjectManager;
use WPDesk\ShopMagic\Workflow\Automation\AutomationPostType;
use WPDesk\ShopMagic\Workflow\Automation\AutomationRepository;
use WPDesk\ShopMagic\Workflow\Outcome\Meta\OutcomeMetaHydrator;
use WPDesk\ShopMagic\Workflow\Outcome\Meta\OutcomeMetaManager;
use WPDesk\ShopMagic\Workflow\Outcome\Meta\OutcomeMetaRepository;
use WPDesk\ShopMagic\Workflow\Outcome\OutcomeHydrator;
use WPDesk\ShopMagic\Workflow\Outcome\OutcomeManager;
use WPDesk\ShopMagic\Workflow\Outcome\OutcomeRepository;
use WPDesk\ShopMagic\Workflow\Outcome\OutcomeSaver;
use WPDesk\ShopMagic\Workflow\Outcome\OutcomeSavingState;
use WPDesk\ShopMagic\Workflow\Queue\ActionSchedulerQueue;
use WPDesk\ShopMagic\Workflow\Queue\Queue;
use WPDesk\ShopMagic\Workflow\Validator\FailingLanguageValidator;
use WPDesk\ShopMagic\Workflow\WorkflowInitializer;
use function ShopMagicVendor\DI\create;
use function ShopMagicVendor\DI\factory;
use function ShopMagicVendor\DI\get;

return [
	'beacon'                                       => '6057086f-4b25-4e12-8735-fbc556d2dc01',
	PluginBag::class                               => autowire(),
	'hookable.plugins_loaded'                      => [
		autowire( QueueActionRunner::class ),
		get( BackgroundOrderInterceptor::class ),
		autowire( HookEmitter\CronHeartbeat::class ),
		autowire( DashboardWidget::class ),
	],
	BackgroundOrderInterceptor::class              => autowire()
	->constructorParameter( 'interceptor', get( OrderGuestInterceptor::class ) ),
	'hookable.init'                                => [
		autowire( AjaxHandler::class )
			->constructor(
				static function ( ContainerInterface $c ) {
					$plugin_url = $c->get( PluginBag::class )->get_url();

					return "$plugin_url/vendor_prefixed/wpdesk/wp-notice/assets";
				}
			),
		autowire( AutomationPostType::class ),
		autowire( CommunicationListPostType::class ),
		autowire( Tracker\TrackerNotices::class ),
		autowire( SignUpCustomerOnSubmit::class ),
		autowire( PreferencesUpdate::class ),
		autowire( ListsOnCheckout::class ),
		get( CurrentCustomer::class ),
		autowire( GuestOrderIntegration::class )
		->constructor( get( OrderGuestInterceptor::class ) ),
		get( GuestOrderUpdate::class ),
		autowire( GuestProductIntegration::class )
		->constructor( get( CommentGuestInterceptor::class ) ),
		autowire( WooCommerceAccountPreferences::class ),
		autowire( Tracker\DeactivationTracker::class ),
		get( Tracker\UsageDataTracker::class ),
		autowire( HookEmitter\OutcomeCleaner::class )
		->constructorParameter( 'persister', get( OutcomeManager::class ) ),
		autowire( PreSubmitData::class ),
		autowire( RecordEmailSending::class ),
		autowire( SubscriptionFormShortcode::class ),
		autowire( FrontendListSubscription::class ),
		autowire( ConfirmedSubscriptionSaver::class )
		->constructorParameter( 'url_generator', get( FrontendUrlGenerator::class ) ),
	],
	CurrentCustomer::class                         => autowire()->constructorParameter( 'logger', get( LoggerInterface::class ) ),

	Tracker\UsageDataTracker::class                => autowire()
	->method(
		'set_providers',
		[
			get( Tracker\Provider\AutomationDataProvider::class ),
			get( Tracker\Provider\ExtensionDataProvider::class ),
			get( Tracker\Provider\SettingsDataProvider::class ),
			get( Tracker\Provider\RecipeDataProvider::class ),
		]
	),

	OutcomeManager::class                          => autowire()
		->constructorParameter( 'repository', get( OutcomeRepository::class ) )
		->constructorParameter( 'normalizer', get( OutcomeHydrator::class ) ),

	OutcomeRepository::class                       => autowire()
		->constructorParameter( 'denormalizer', get( OutcomeHydrator::class ) ),

	OutcomeMetaManager::class                      => autowire()
		->constructorParameter( 'repository', get( OutcomeMetaRepository::class ) )
		->constructorParameter( 'normalizer', get( OutcomeMetaHydrator::class ) ),

	OutcomeMetaRepository::class                   => autowire()
		->constructorParameter(
			'denormalizer',
			get( OutcomeMetaHydrator::class )
		),

	OutcomeSaver::class                            => autowire( OutcomeSavingState::class ),

	CustomerRepository::class                      => autowire()
		->constructor(
			get( UserRepository::class ),
			get( GuestDataAccess::class )
		),

	GuestManager::class                            => autowire()
		->constructorParameter( 'repository', get( GuestRepository::class ) )
		->constructorParameter( 'normalizer', get( GuestHydrator::class ) ),

	GuestRepository::class                         => autowire()
		->constructorParameter( 'denormalizer', get( GuestHydrator::class ) ),

	\WPDesk\ShopMagic\Guest\GuestRepository::class => autowire()
		->constructorParameter( 'denormalizer', get( GuestHydrator::class ) ),

	GuestMetaManager::class                        => autowire()
		->constructorParameter( 'repository', get( GuestMetaRepository::class ) )
		->constructorParameter( 'normalizer', get( GuestMetaFactory::class ) ),

	GuestMetaRepository::class                     => autowire()
		->constructorParameter( 'denormalizer', get( GuestMetaFactory::class ) ),

	OrderGuestInterceptor::class                   => autowire()
		->constructorParameter( 'persister', get( GuestDataAccess::class ) ),
	GuestOrderUpdate::class                        => autowire()
		->constructorParameter( 'persister', get( GuestDataAccess::class ) ),

	CommentGuestInterceptor::class                 => autowire()
		->constructorParameter( 'persister', get( GuestManager::class ) ),

	TrackedEmailObjectManager::class               => autowire()
		->constructorParameter( 'repository', get( TrackedEmailRepository::class ) )
		->constructorParameter(
			'normalizer',
			get( TrackedEmailHydrator::class )
		),

	TrackedEmailRepository::class                  => autowire()
		->constructorParameter(
			'denormalizer',
			get( TrackedEmailHydrator::class )
		),

	TrackedClickObjectManager::class               => autowire()
		->constructorParameter( 'repository', get( TrackedClickRepository::class ) )
		->constructorParameter(
			'normalizer',
			get( TrackedEmailClickFactory::class )
		),

	TrackedClickRepository::class                  => autowire()
		->constructorParameter(
			'denormalizer',
			get( TrackedEmailClickFactory::class )
		),

	SubscriptionManager::class                     => autowire()
		->constructorParameter( 'repository', get( SubscriberObjectRepository::class ) )
		->constructorParameter(
			'normalizer',
			get( SingleListSubscriberHydrator::class )
		),

	SubscriberObjectRepository::class              => autowire()
		->constructorParameter(
			'denormalizer',
			get( SubscriberHydrator::class )
		),

	AudienceListObjectManager::class               => autowire()
		->constructorParameter(
			'repository',
			get( AudienceListRepository::class )
		),

	CommunicationPreferencesRenderer::class        => autowire(),

	Admin\Admin::class                             => autowire()
		->constructorParameter( 'url_generator', get( RestUrlGenerator::class ) ),
	Admin\Manifest::class                          => static function ( ContainerInterface $c ) {
		$plugin_bag = $c->get( PluginBag::class );

		return Admin\Manifest::from_file(
			$plugin_bag->get_manifest_path(),
			$plugin_bag->get_admin_assets_url()
		);
	},
	ExecutionCreatorContainer::class               => autowire()
		->method( 'add_execution_creator', get( QueueExecutionCreator::class ) )
		->method( 'add_execution_creator', get( FailingDelayExecution::class ) ),
	ExecutionCreator::class                        => get( ExecutionCreatorContainer::class ),
	Queue::class                                   => get( ActionSchedulerQueue::class ),
	ExternalPluginsAccess::class                   => autowire(),

	\WPDesk\ShopMagic\Workflow\Extensions\ExtensionsSet::class => get( ExtensionsSet::class ),

	wpdb::class                                    => static function () {
		global $wpdb;

		return $wpdb;
	},

	LoggerInterface::class                         => factory(
		static function ( \ShopMagicVendor\DI\Container $c ) {
			$original_handler = new ErrorLogHandler();

			if ( $c->get( PluginBag::class )->debug_enabled() ) {
				$handler = $original_handler;
			} else {
				// In production environment we use FingersCrossedHandler to get rich output at errors.
				$handler = new FingersCrossedHandler( $original_handler, LogLevel::ERROR );
			}

			$logger = new Logger(
				'shopmagic',
				[ $handler ],
				[ new PsrLogMessageProcessor( null, true ), new UidProcessor() ]
			);
			$handler->setFormatter(
				new LineFormatter( '%channel%.%level_name% [%extra.uid%]: %message% %context% %extra%' )
			);

			return $logger;
		}
	),
	'logger'                                       => get( LoggerInterface::class ),
	'logger.admin'                                 => static function ( LoggerInterface $logger ) {
		return $logger->withName( 'shopmagic.admin' );
	},
	LogController::class                           => autowire()
	->constructorParameter( 'logger', get( 'logger.admin' ) ),

	ExecuteNow::class                              => autowire()
	->constructorParameter( 'permit_exceptions', true ),

	WorkflowInitializer::class                     => autowire(),
	Migrator::class                                => static function ( ContainerInterface $c ) {
		$plugin_bag = $c->get( PluginBag::class );

		return WpdbMigrator::from_directories(
			[ $plugin_bag->get_migrations_path() ],
			'shopmagic_db'
		);
	},
	ArgumentResolver::class                        => static function (
		\ShopMagicVendor\DI\Container $c
	) {
		return new ArgumentResolver(
			[
				new ArgumentResolver\RequestValueResolver(),
				new ArgumentResolver\ContainerValueResolver( $c->get( ContainerInterface::class ) ),
				new ArgumentResolver\ParameterValueResolver(),
				new ArgumentResolver\RawRequestValueResolver(),
				new ArgumentResolver\DefaultValueResolver(),
			]
		);
	},
	ControllerResolver::class                      => create( ContainerControllerResolver::class )
	->constructor( get( ContainerInterface::class ) ),

	WpRoutesRegistry::class                        => autowire()
	->constructorParameter( 'configurator', get( 'routesConfigurator.public' ) ),
	RestRoutesRegistry::class                      => autowire()
		->constructorParameter( 'configurator', get( 'routesConfigurator.api' ) )
		->method( 'setLogger', get( LoggerInterface::class ) ),
	'routesConfigurator.public'                    => create( RoutesConfigurator::class ),
	'routesConfigurator.api'                       => static function () {
		$configurator = new RoutesConfigurator();
		$configurator->prefix( 'shopmagic/v1' );
		$configurator->authorize(
			static function () {
				return current_user_can( 'manage_options' ) || current_user_can( 'edit_others_shop_orders' );
			}
		);

		return $configurator;
	},
	TestProvider::class                            => static function (
		ContainerInterface $c
	) {
		$test_provider = new TestProvider();
		$test_provider->add_provider( $c->get( CustomerTestProvider::class ) );

		if ( WordPressPluggableHelper::is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$test_provider->add_provider( $c->get( OrderTestProvider::class ) );
		}

		return $test_provider;
	},
	JsonSchemaNormalizer::class                    => static function () {
		return new JsonSchemaNormalizer(
			new NormalizerCollection(
				new ActionFieldNormalizer(),
				new ModuleFieldNormalizer(),
				new ParagraphFieldNormalizer(),
				new NoValueFieldNormalizer(),
				new CheckboxFieldNormalizer(),
				new ProductSelectFieldNormalizer(),
				new MultipleSelectFieldNormalizer(),
				new SelectFieldNormalizer(),
				new TimeFieldNormalizer(),
				new DateFieldNormalizer(),
				new TextFieldNormalizer(),
				new JsonSchemaFieldNormalizer()
			)
		);
	},

	AutomationController::class                    => autowire()
		->constructorParameter(
			'repository',
			get( AutomationRepository::class )
		)
		->method( 'set_normalizer', get( WorkflowAutomationNormalizer::class ) )
		->method( 'set_denormalizer', get( WorkflowAutomationDenormalizer::class ) ),

	WorkflowAutomationNormalizer::class            => autowire()
		->constructor( get( AutomationRepository::class ), get( RestUrlGenerator::class ) ),

	WorkflowAutomationDenormalizer::class          => autowire()
		->constructorParameter(
			'repository',
			get( AutomationRepository::class )
		),

	AutomationObjectManager::class                 => autowire()
		->constructorParameter(
			'repository',
			get( AutomationRepository::class )
		),

	Admin\Settings\ModulesInfoContainer::class     => static function () {
		return Admin\Settings\ModulesSettings::get_settings_persistence();
	},

	Mailer::class                                  => autowire( WPMailMailer::class ),
	ConfirmationDispatcher::class                  => autowire(),
	MailchimpApi::class                            => static function ( ContainerInterface $c ) {
		try {
			$api_key = Settings::get_option( 'wc_settings_tab_mailchimp_api_key', false );

			return new APITools( $api_key, $c->get( 'logger' ) );
		} catch ( Exception $e ) {
			return new MissingKeyApi();
		}
	},

	Admin\Settings\SettingsCollection::class       => autowire()
	->constructor(
		[
			get( Admin\Settings\GeneralSettings::class ),
			get( Admin\Settings\ModulesSettings::class ),
			get( Settings::class ),
		]
	),
	\WPDesk\ShopMagic\Marketing\Subscribers\PreferencesRoute::class => autowire()
	->constructorParameter( 'url_generator', get( FrontendUrlGenerator::class ) ),

	\WPDesk\ShopMagic\Integration\ContactForms\FormGuestInterceptor::class => autowire()
		->constructorParameter(
			'customer_repository',
			get( CustomerRepository::class )
		)
		->constructorParameter(
			'guest_manager',
			get( GuestManager::class )
		),

	'resolver.default'                             => static function ( PluginBag $plugin_bag ) {
		return new DirResolver( $plugin_bag->get_directory() . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR );
	},
	Renderer::class                                => static function ( TemplateResolver $resolver ) {
		return new SimplePhpRenderer( $resolver );
	},

	FailingLanguageValidator::class                => autowire()->constructorParameter( 'modules', get( Admin\Settings\ModulesInfoContainer::class ) ),
];
