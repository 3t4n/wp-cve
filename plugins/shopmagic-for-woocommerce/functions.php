<?php

function autowire(string $class = null): \WPDesk\ShopMagic\DI\AutowireDefinitionHelper {
	return new \WPDesk\ShopMagic\DI\AutowireDefinitionHelper($class);
}
