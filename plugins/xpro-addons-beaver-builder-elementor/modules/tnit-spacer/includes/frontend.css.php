<?php
/**
 * This file should contain frontend styles that
 * will be applied to individual module instances.
 *
 * You have access to three variables in this file:
 *
 * $module An instance of your module class.
 * $id The module's ID.
 * $settings The module's settings.
 *
 * @package Spacer Module
 * @since 1.1.3
 */

// Icon Font Size.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'space',
		'selector'     => ".fl-node-$id .tnit-content.tnit-spacer",
		'prop'         => 'height',
		'unit'         => 'px',
	)
);
