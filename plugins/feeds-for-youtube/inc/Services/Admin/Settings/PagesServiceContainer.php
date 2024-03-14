<?php

namespace SmashBalloon\YouTubeFeed\Services\Admin\Settings;

use Smashballoon\Stubs\Services\ServiceProvider;
use SmashBalloon\YouTubeFeed\Container;

class PagesServiceContainer extends ServiceProvider {
	private $services = [
		SettingsPage::class,
		HelpPage::class,
		AboutPage::class,
	];

	public function register() {
		$container = Container::get_instance();

		if ( sby_is_pro() ) {
			array_splice( $this->services, 2, 0, [SingleVideoPage::class]);
		}

		foreach ( $this->services as $service ) {
			$container->get($service)->register();
		}
	}
}