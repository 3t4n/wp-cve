<?php
/**
 * Service providers for the addon.
 *
 * @since 1.8.3
 */

use Masteriyo\Addons\Scorm\Providers\ScormServiceProvider;

return array_unique(
	array(
		ScormServiceProvider::class,
	)
);
