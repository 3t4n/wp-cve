<?php
/**
 * Defining the UpStream version, and also any addon's version that working with this this release.
 *
 * @package UpStream
 */

define( 'UPSTREAM_VERSION', '2.0.9' );

global $upstream_addon_requirements;
$upstream_addon_requirements = array(
	array( 'Upstream-Advanced-Permissions/upstream-advanced-permissions.php', '2.0.7' ),
	array( 'UpStream-API/upstream-api.php', '2.0.7' ),
	array( 'UpStream-Calendar-View/upstream-calendar-view.php', '2.0.7' ),
	array( 'UpStream-Copy-Project/upstream-copy-project.php', '2.0.7' ),
	array( 'UpStream-Custom-Fields/upstream-custom-fields.php', '2.0.7' ),
	array( 'UpStream-Customizer/upstream-customizer.php', '2.0.7' ),
	array( 'UpStream-Email-Notifications/upstream-email-notifications.php', '2.0.7' ),
	array( 'UpStream-Forms/upstream-forms.php', '2.0.7' ),
	array( 'UpStream-Frontend-Edit/upstream-frontend-edit.php', '2.0.7' ),
	array( 'UpStream-Name-Replacement/upstream-name-replacement.php', '2.0.7' ),
	array( 'UpStream-Project-Timeline/upstream-project-timeline.php', '2.0.7' ),
	array( 'UpStream-Reporting/upstream-reporting.php', '2.0.7' ),
	array( 'UpStream-Time-Tracking/upstream-time-tracking.php', '2.0.7' ),
);
