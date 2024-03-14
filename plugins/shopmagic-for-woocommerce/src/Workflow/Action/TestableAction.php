<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Workflow\Action;

use WPDesk\ShopMagic\DataSharing\FieldValuesBag;
use WPDesk\ShopMagic\Workflow\Event\DataLayer;
use WPDesk\ShopMagic\Workflow\Placeholder\PlaceholderProcessor;

interface TestableAction {

	public function execute_test( FieldValuesBag $field_values_bag, DataLayer $resources, PlaceholderProcessor $processor ): void;

}
