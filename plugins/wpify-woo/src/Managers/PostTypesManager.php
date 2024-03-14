<?php

namespace WpifyWoo\Managers;

use WpifyWoo\Plugin;
use WpifyWoo\PostTypes\PacketaOrderPostType;
use WpifyWoo\PostTypes\WooOrderPostType;
use WpifyWooDeps\Wpify\Core\Abstracts\AbstractManager;

/**
 * Class CptManager
 *
 * @package Wpify\Managers
 * @property Plugin $plugin
 */
class PostTypesManager extends AbstractManager {
	protected $modules = array(
		WooOrderPostType::class,
		PacketaOrderPostType::class
	);
}
