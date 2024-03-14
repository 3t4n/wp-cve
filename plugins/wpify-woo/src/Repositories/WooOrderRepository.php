<?php

namespace WpifyWoo\Repositories;

use WpifyWoo\Plugin;
use WpifyWoo\PostTypes\WooOrderPostType;
use WpifyWooDeps\Wpify\Core\Abstracts\AbstractWooOrderRepository;

/**
 * @property Plugin $plugin
 */
class WooOrderRepository extends AbstractWooOrderRepository {

	public function post_type(): WooOrderPostType {
		return $this->plugin->get_post_type( WooOrderPostType::class );
	}
}
