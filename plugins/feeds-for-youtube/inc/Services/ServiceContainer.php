<?php

namespace SmashBalloon\YouTubeFeed\Services;

use SmashBalloon\YouTubeFeed\Container;
use Smashballoon\Customizer\Customizer_Service;
use Smashballoon\Stubs\Services\ServiceProvider;
use SmashBalloon\YouTubeFeed\Builder\Tooltip_Wizard;
use SmashBalloon\YouTubeFeed\Customizer\Tabs\TabsService;
use SmashBalloon\YouTubeFeed\Builder\SBY_Feed_Saver_Manager;
use SmashBalloon\YouTubeFeed\Services\Upgrade\RoutineManagerService;
use SmashBalloon\YouTubeFeed\Services\LicenseNotification;

class ServiceContainer extends ServiceProvider {

	private $services = [
		CronUpdaterService::class,
		RoutineManagerService::class,
		ConfigService::class,
		AssetsService::class,
		TabsService::class,
		Customizer_Service::class,
		AdminAjaxService::class,
		DebugReportingService::class,
		ErrorReportingService::class,
		ShortcodeService::class,
		SBY_Feed_Saver_Manager::class,
		Tooltip_Wizard::class,
		LicenseNotification::class,
	];

	public function register() {
		$container = Container::get_instance();

		foreach ( $this->services as $service ) {
			$container->get($service)->register();
		}
	}
}