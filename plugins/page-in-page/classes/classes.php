<?php

/**
 * Include classes
 */

// Class to accesss config array (see ./config.php
require TWL_PIP_CLASSPATH . '/config.php';

// Class to extract template variables from feed
require TWL_PIP_CLASSPATH . '/vars.php';

// Widget class
require TWL_PIP_CLASSPATH . '/widgets.php';

// Main class
require TWL_PIP_CLASSPATH . '/main.php';

// Admin Interface
require TWL_PIP_CLASSPATH . '/admin.php';

// Main class -  register shortcodes and SDKs
require TWL_PIP_CLASSPATH . '/page.php';