<?php
/**
 * Packlink PRO Shipping WooCommerce Integration.
 *
 * @package Packlink
 */

namespace Packlink\WooCommerce\Components;

use Logeecom\Infrastructure\Configuration\ConfigEntity;
use Logeecom\Infrastructure\Configuration\Configuration;
use Logeecom\Infrastructure\Http\CurlHttpClient;
use Logeecom\Infrastructure\Http\HttpClient;
use Logeecom\Infrastructure\Logger\Interfaces\ShopLoggerAdapter;
use Logeecom\Infrastructure\Logger\LogData;
use Logeecom\Infrastructure\ORM\Exceptions\RepositoryClassException;
use Logeecom\Infrastructure\ORM\RepositoryRegistry;
use Logeecom\Infrastructure\Serializer\Concrete\NativeSerializer;
use Logeecom\Infrastructure\Serializer\Serializer;
use Logeecom\Infrastructure\ServiceRegister;
use Logeecom\Infrastructure\TaskExecution\Process;
use Logeecom\Infrastructure\TaskExecution\QueueItem;
use Packlink\Brands\Packlink\PacklinkConfigurationService;
use Packlink\BusinessLogic\BootstrapComponent;
use Packlink\BusinessLogic\Brand\BrandConfigurationService;
use Packlink\BusinessLogic\FileResolver\FileResolverService;
use Packlink\BusinessLogic\Order\Interfaces\ShopOrderService;
use Packlink\BusinessLogic\OrderShipmentDetails\Models\OrderShipmentDetails;
use Packlink\BusinessLogic\Registration\RegistrationInfoService;
use Packlink\BusinessLogic\Scheduler\Models\Schedule;
use Packlink\BusinessLogic\ShipmentDraft\Models\OrderSendDraftTaskMap;
use Packlink\BusinessLogic\ShipmentDraft\ShipmentDraftService;
use Packlink\WooCommerce\Components\Services\Shipment_Draft_Service;
use Packlink\BusinessLogic\ShippingMethod\Interfaces\ShopShippingMethodService;
use Packlink\BusinessLogic\ShippingMethod\Models\ShippingMethod;
use Packlink\BusinessLogic\SystemInformation\SystemInfoService;
use Packlink\WooCommerce\Components\Order\Order_Drop_Off_Map;
use Packlink\WooCommerce\Components\Order\Shop_Order_Service;
use Packlink\WooCommerce\Components\Repositories\Base_Repository;
use Packlink\WooCommerce\Components\Repositories\Queue_Item_Repository;
use Packlink\WooCommerce\Components\Services\Config_Service;
use Packlink\WooCommerce\Components\Services\Logger_Service;
use Packlink\WooCommerce\Components\Services\Registration_Info_Service;
use Packlink\WooCommerce\Components\Services\System_Info_Service;
use Packlink\WooCommerce\Components\ShippingMethod\Shipping_Method_Map;
use Packlink\WooCommerce\Components\ShippingMethod\Shop_Shipping_Method_Service;

/**
 * Class Bootstrap_Component
 *
 * @package Packlink\WooCommerce\Components
 */
class Bootstrap_Component extends BootstrapComponent {
	/**
	 * Initializes services and utilities.
	 */
	protected static function initServices() {
		parent::initServices();

		ServiceRegister::registerService(
			Serializer::CLASS_NAME,
			function () {
				return new NativeSerializer();
			}
		);

		ServiceRegister::registerService(
			Configuration::CLASS_NAME,
			static function () {
				return Config_Service::getInstance();
			}
		);

		ServiceRegister::registerService(
			BrandConfigurationService::CLASS_NAME,
			static function () {
				return new PacklinkConfigurationService();
			}
		);

		ServiceRegister::registerService(
			ShopLoggerAdapter::CLASS_NAME,
			static function () {
				return Logger_Service::getInstance();
			}
		);

		ServiceRegister::registerService(
			ShopShippingMethodService::CLASS_NAME,
			static function () {
				return Shop_Shipping_Method_Service::getInstance();
			}
		);

		ServiceRegister::registerService(
			ShopOrderService::CLASS_NAME,
			static function () {
				return Shop_Order_Service::getInstance();
			}
		);

		ServiceRegister::registerService(
			HttpClient::CLASS_NAME,
			static function () {
				return new CurlHttpClient();
			}
		);

		ServiceRegister::registerService(
			RegistrationInfoService::CLASS_NAME,
			static function () {
				return new Registration_Info_Service();
			}
		);

		ServiceRegister::registerService(
			SystemInfoService::CLASS_NAME,
			static function () {
				return new System_Info_Service();
			}
		);

		ServiceRegister::registerService(
			FileResolverService::CLASS_NAME,
			function () {
				return new FileResolverService(array(
					__DIR__ . '/../resources/packlink/brand/countries',
					__DIR__ . '/../resources/packlink/countries',
					__DIR__ . '/../resources/countries',
				));
			}
		);

		ServiceRegister::registerService(
			ShipmentDraftService::CLASS_NAME,
			static function () {
				return Shipment_Draft_Service::getInstance();
			}
		);
	}

	/**
	 * Initializes repositories.
	 *
	 * @throws RepositoryClassException If repository class is not instance of repository interface.
	 */
	protected static function initRepositories() {
		parent::initRepositories();

		RepositoryRegistry::registerRepository( ConfigEntity::CLASS_NAME, Base_Repository::getClassName() );
		RepositoryRegistry::registerRepository( Process::CLASS_NAME, Base_Repository::getClassName() );
		RepositoryRegistry::registerRepository( ShippingMethod::CLASS_NAME, Base_Repository::getClassName() );
		RepositoryRegistry::registerRepository( Shipping_Method_Map::CLASS_NAME, Base_Repository::getClassName() );
		RepositoryRegistry::registerRepository( OrderShipmentDetails::CLASS_NAME, Base_Repository::getClassName() );
		RepositoryRegistry::registerRepository( Schedule::CLASS_NAME, Base_Repository::getClassName() );
		RepositoryRegistry::registerRepository( QueueItem::CLASS_NAME, Queue_Item_Repository::getClassName() );
		RepositoryRegistry::registerRepository( LogData::CLASS_NAME, Base_Repository::getClassName() );
		RepositoryRegistry::registerRepository( OrderSendDraftTaskMap::CLASS_NAME, Base_Repository::getClassName() );
		RepositoryRegistry::registerRepository( Order_Drop_Off_Map::CLASS_NAME, Base_Repository::getClassName() );
	}
}
