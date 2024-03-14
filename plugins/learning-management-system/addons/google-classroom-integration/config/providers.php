<?php
/**
 * Google Classroom Integration config.
 *
 * @since 1.8.3
 */
use Masteriyo\Addons\GoogleClassroomIntegration\Providers\GoogleClassroomIntegrationServiceProvider;

/**
 * Masteriyo Google Classroom Integration service providers.
 *
 * @since 1.8.3
 */
return array_unique(
	array(
		GoogleClassroomIntegrationServiceProvider::class,
	)
);
