<?php
/**
 * Page In Page plugin setup
 */

// Define required plugin constants
define('TWL_PIP_TEXT_DOMAIN', 'TWL PageInPage Plugin');
define('TWL_PIP_ROOT', dirname(__FILE__));
define('TWL_PIP_CLASSPATH', TWL_PIP_ROOT . '/classes');
define('TWL_PIP_SDKPATH', TWL_PIP_ROOT . '/sdk');
define('TWL_PIP_INCPATH', TWL_PIP_ROOT . '/inc');
define('TWL_PIP_TEMPLATES', TWL_PIP_INCPATH . '/templates');

// Require necessary files
require TWL_PIP_ROOT . '/config.php';
require TWL_PIP_ROOT . '/functions.php';
require TWL_PIP_CLASSPATH . '/classes.php';

// Initialize config
TWL_PIP_Config::init();