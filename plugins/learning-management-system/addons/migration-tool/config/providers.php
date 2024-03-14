<?php
/**
 * Service providers for the addon.
 *
 * @since 1.8.0
 */

use Masteriyo\Addons\MigrationTool\Providers\MigrationToolServiceProvider;

return array_unique(
	array(
		MigrationToolServiceProvider::class,
	)
);
