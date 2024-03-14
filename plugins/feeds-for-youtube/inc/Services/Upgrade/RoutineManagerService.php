<?php

namespace SmashBalloon\YouTubeFeed\Services\Upgrade;

use Smashballoon\Stubs\Services\ServiceProvider;
use SmashBalloon\YouTubeFeed\Container;
use SmashBalloon\YouTubeFeed\Services\Upgrade\Routines\UpgradeRoutine;
use SmashBalloon\YouTubeFeed\Services\Upgrade\Routines\V2Routine;

class RoutineManagerService extends ServiceProvider {
	/**
	 * a list of upgrade routines to be executed,
	 * keep the correct order, newer is always at the end of the list.
	 * @var UpgradeRoutine[]
	 */
	private $routines = [
		V2Routine::class
	];

	public function register() {
		$container = Container::get_instance();

		foreach ($this->routines as $routine) {
			$container->get($routine)->register();
		}
	}
}