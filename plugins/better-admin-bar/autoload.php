<?php
/**
 * Autoloading
 *
 * @package Better_Admin_Bar
 */

namespace SwiftControl;

defined( 'ABSPATH' ) || die( "Can't access directly" );

// Require helper classes.
require __DIR__ . '/helpers/class-export.php';
require __DIR__ . '/helpers/class-import.php';
require __DIR__ . '/helpers.php';

// Require ajax classes.
require __DIR__ . '/ajax/class-change-widgets-order.php';
require __DIR__ . '/ajax/class-change-widget-settings.php';
require __DIR__ . '/ajax/class-save-general-settings.php';
require __DIR__ . '/ajax/class-save-position.php';

// Require backwards compatibility class.
require __DIR__ . '/class-backwards-compatibility.php';

// Require setup class.
require __DIR__ . '/class-setup.php';

// Init classes.
Backwards_Compatibility::init();
Setup::init();
