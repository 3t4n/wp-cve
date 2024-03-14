<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Modules\Mulitilingual;

use ShopMagicVendor\DI\ContainerBuilder;
use WPDesk\ShopMagic\DI\ContainerAwareTrait;
use WPDesk\ShopMagic\Frontend\Interceptor\CurrentCustomer;
use WPDesk\ShopMagic\Marketing\Subscribers\AudienceList\AudienceListFactory;
use WPDesk\ShopMagic\Marketing\Subscribers\AudienceList\AudienceListRepository;
use WPDesk\ShopMagic\Modules\Module;
use WPDesk\ShopMagic\Modules\Mulitilingual\Customer\CustomerLanguagePersistence;
use WPDesk\ShopMagic\Modules\Mulitilingual\Integration\BareHandler;
use WPDesk\ShopMagic\Modules\Mulitilingual\Marketing\MultilingualAudienceListRepository;
use WPDesk\ShopMagic\Modules\Mulitilingual\Validator\CustomerLanguageValidator;
use WPDesk\ShopMagic\Workflow\Automation\AutomationRepository;
use WPDesk\ShopMagic\Workflow\WorkflowInitializer;
use function ShopMagicVendor\DI\add;
use function ShopMagicVendor\DI\get;

final class MultilingualModule implements Module {
	use ContainerAwareTrait;

	public function build( ContainerBuilder $builder ): void {
		$builder
			->addDefinitions(
				[
					'hookable.init'                  => add(
						autowire( CustomerLanguagePersistence::class )
							->constructorParameter(
								'customer_provider',
								get( CurrentCustomer::class )
							)
					),
					LanguageMatcher::class           => autowire( BareLanguageMatcher::class )
						->constructor(
							get( AutomationRepository::class )
						),
					LanguageHandler::class           => autowire( BareHandler::class )
						->constructor(
							get( AutomationRepository::class )
						),
					AudienceListRepository::class    => autowire( MultilingualAudienceListRepository::class )
						->constructor(
							get( AudienceListFactory::class ),
							get( CurrentCustomer::class )
						),
					CustomerLanguageValidator::class => autowire(),
				]
			);
	}

	public function initialize(): void {
		$initializer = $this->container->get( WorkflowInitializer::class );

		$initializer->add_validator( $this->container->get( CustomerLanguageValidator::class ) );
	}

	public function get_name(): string {
		return 'multilingual-module';
	}

}
