<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\HookProvider;

use ShopMagicVendor\WPDesk\PluginBuilder\Plugin\Hookable;

interface HookProvider extends Hookable {

	public function hooks(): void;

}
