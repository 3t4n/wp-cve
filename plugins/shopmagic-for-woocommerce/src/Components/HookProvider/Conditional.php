<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\HookProvider;

interface Conditional {

	public static function is_needed(): bool;

}
