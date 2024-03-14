<?php

// ===============
// = Plugin Path =
// ===============
define('DIRECTORYPRESS_PATH', plugin_dir_path(__FILE__));

// ===============
// = Plugin URL =
// ===============

define('DIRECTORYPRESS_URL', plugins_url('/', __FILE__));

// ============
// = Templates Path =
// ============

define('DIRECTORYPRESS_TEMPLATES_PATH', DIRECTORYPRESS_PATH . 'public/');

// ===================
// = Assets Path =
// ===================

define('DIRECTORYPRESS_RESOURCES_PATH', DIRECTORYPRESS_PATH . 'assets/');

// ==============
// = Resources URL =
// ==============

define('DIRECTORYPRESS_RESOURCES_URL', DIRECTORYPRESS_URL . 'assets/');
define('DIRECTORYPRESS_MAP_ICONS_PATH', DIRECTORYPRESS_RESOURCES_PATH . 'images/map_icons/');
	define('DIRECTORYPRESS_MAP_ICONS_URL', DIRECTORYPRESS_RESOURCES_URL . 'images/map_icons/');


// ===============
// = Post Type =
// ===============

define('DIRECTORYPRESS_POST_TYPE', 'dp_listing');

// =============
// = Taxanomies =
// =============
define('DIRECTORYPRESS_CATEGORIES_TAX', 'directorypress-category');
define('DIRECTORYPRESS_LOCATIONS_TAX', 'directorypress-location');
define('DIRECTORYPRESS_TYPE_TAX', 'directorypress-type');
define('DIRECTORYPRESS_TAGS_TAX', 'directorypress-tag');

