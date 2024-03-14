<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Database\Abstraction;

class InsufficientPermission extends \RuntimeException implements PersisterException {}
