<?php
/*
Name: SCSS Compiled
Slug: scss-compiled
Description: Reduce the size of compiled Bootstrap CSS by excluding specific utilities and components. When a component is excluded the compiled Bootstrap CSS will not include the relevant styles for that component. Use these options with care as excluding styles will make some blocks render incorrectly.
Position: 10
Theme: 
*/
$slug = AREOI__PREPEND . ( !empty( $section ) ? '-' . $section : '' )  . '-compiled-';

return array(
	array(
		'label' => 'Exclude @import "utilities";',
		'name' => $slug . 'exclude-utilities',
		'variable' => '$exclude-utilities',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the utilities classes will be excluded from compiled Bootstrap CSS.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Exclude @import "root";',
		'name' => $slug . 'exclude-root',
		'variable' => '$exclude-root',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the root classes will be excluded from compiled Bootstrap CSS.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Exclude @import "reboot";',
		'name' => $slug . 'exclude-reboot',
		'variable' => '$exclude-reboot',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the reboot classes will be excluded from compiled Bootstrap CSS.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Exclude @import "type";',
		'name' => $slug . 'exclude-type',
		'variable' => '$exclude-type',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the type classes will be excluded from compiled Bootstrap CSS.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Exclude @import "images";',
		'name' => $slug . 'exclude-images',
		'variable' => '$exclude-images',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the images classes will be excluded from compiled Bootstrap CSS.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Exclude @import "containers";',
		'name' => $slug . 'exclude-containers',
		'variable' => '$exclude-containers',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the containers classes will be excluded from compiled Bootstrap CSS.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Exclude @import "grid";',
		'name' => $slug . 'exclude-grid',
		'variable' => '$exclude-grid',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the grid classes will be excluded from compiled Bootstrap CSS.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Exclude @import "tables";',
		'name' => $slug . 'exclude-tables',
		'variable' => '$exclude-tables',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the tables classes will be excluded from compiled Bootstrap CSS.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Exclude @import "forms";',
		'name' => $slug . 'exclude-forms',
		'variable' => '$exclude-forms',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the forms classes will be excluded from compiled Bootstrap CSS.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Exclude @import "buttons";',
		'name' => $slug . 'exclude-buttons',
		'variable' => '$exclude-buttons',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the buttons classes will be excluded from compiled Bootstrap CSS.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Exclude @import "transitions";',
		'name' => $slug . 'exclude-transitions',
		'variable' => '$exclude-transitions',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the transitions classes will be excluded from compiled Bootstrap CSS.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Exclude @import "dropdown";',
		'name' => $slug . 'exclude-dropdown',
		'variable' => '$exclude-dropdown',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the dropdown classes will be excluded from compiled Bootstrap CSS.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Exclude @import "button-group";',
		'name' => $slug . 'exclude-button-group',
		'variable' => '$exclude-button-group',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the button-group classes will be excluded from compiled Bootstrap CSS.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Exclude @import "nav";',
		'name' => $slug . 'exclude-nav',
		'variable' => '$exclude-nav',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the nav classes will be excluded from compiled Bootstrap CSS.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Exclude @import "navbar";',
		'name' => $slug . 'exclude-navbar',
		'variable' => '$exclude-navbar',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the navbar classes will be excluded from compiled Bootstrap CSS.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Exclude @import "card";',
		'name' => $slug . 'exclude-card',
		'variable' => '$exclude-card',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the card classes will be excluded from compiled Bootstrap CSS.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Exclude @import "accordion";',
		'name' => $slug . 'exclude-accordion',
		'variable' => '$exclude-accordion',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the accordion classes will be excluded from compiled Bootstrap CSS.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Exclude @import "breadcrumb";',
		'name' => $slug . 'exclude-breadcrumb',
		'variable' => '$exclude-breadcrumb',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the breadcrumb classes will be excluded from compiled Bootstrap CSS.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Exclude @import "pagination";',
		'name' => $slug . 'exclude-pagination',
		'variable' => '$exclude-pagination',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the pagination classes will be excluded from compiled Bootstrap CSS.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Exclude @import "badge";',
		'name' => $slug . 'exclude-badge',
		'variable' => '$exclude-badge',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the badge classes will be excluded from compiled Bootstrap CSS.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Exclude @import "alert";',
		'name' => $slug . 'exclude-alert',
		'variable' => '$exclude-alert',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the alert classes will be excluded from compiled Bootstrap CSS.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Exclude @import "progress";',
		'name' => $slug . 'exclude-progress',
		'variable' => '$exclude-progress',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the progress classes will be excluded from compiled Bootstrap CSS.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Exclude @import "list-group";',
		'name' => $slug . 'exclude-list-group',
		'variable' => '$exclude-list-group',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the list-group classes will be excluded from compiled Bootstrap CSS.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Exclude @import "close";',
		'name' => $slug . 'exclude-close',
		'variable' => '$exclude-close',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the close classes will be excluded from compiled Bootstrap CSS.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Exclude @import "toasts";',
		'name' => $slug . 'exclude-toasts',
		'variable' => '$exclude-toasts',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the toasts classes will be excluded from compiled Bootstrap CSS.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Exclude @import "modal";',
		'name' => $slug . 'exclude-modal',
		'variable' => '$exclude-modal',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the modal classes will be excluded from compiled Bootstrap CSS.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Exclude @import "tooltip";',
		'name' => $slug . 'exclude-tooltip',
		'variable' => '$exclude-tooltip',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the tooltip classes will be excluded from compiled Bootstrap CSS.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Exclude @import "popover";',
		'name' => $slug . 'exclude-popover',
		'variable' => '$exclude-popover',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the popover classes will be excluded from compiled Bootstrap CSS.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Exclude @import "carousel";',
		'name' => $slug . 'exclude-carousel',
		'variable' => '$exclude-carousel',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the carousel classes will be excluded from compiled Bootstrap CSS.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Exclude @import "spinners";',
		'name' => $slug . 'exclude-spinners',
		'variable' => '$exclude-spinners',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the spinners classes will be excluded from compiled Bootstrap CSS.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Exclude @import "offcanvas";',
		'name' => $slug . 'exclude-offcanvas',
		'variable' => '$exclude-offcanvas',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the offcanvas classes will be excluded from compiled Bootstrap CSS.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Exclude @import "placeholders";',
		'name' => $slug . 'exclude-placeholders',
		'variable' => '$exclude-placeholders',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the placeholders classes will be excluded from compiled Bootstrap CSS.',
		'allow_reset' => false,
		'options' => array()
	),
	array(
		'label' => 'Exclude @import "helpers";',
		'name' => $slug . 'exclude-helpers',
		'variable' => '$exclude-helpers',
		'row' => 'default',
		'input' => 'checkbox',
		'default' => 0,
		'description' => 'If checked the helpers classes will be excluded from compiled Bootstrap CSS.',
		'allow_reset' => false,
		'options' => array()
	),
);