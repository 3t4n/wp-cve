<?php

namespace WpifyWoo\Managers;

use WpifyWoo\Plugin;
use WpifyWoo\Repositories\PacketaOrderRepository;
use WpifyWoo\Repositories\WooOrderRepository;
use WpifyWooDeps\Wpify\Core\Abstracts\AbstractManager;

/**
 * Class RepositoriesManager
 *
 * @package Wpify\Managers
 * @property Plugin $plugin
 */
class RepositoriesManager extends AbstractManager {
	protected $modules = array(
		WooOrderRepository::class,
		PacketaOrderRepository::class,
	);
}
