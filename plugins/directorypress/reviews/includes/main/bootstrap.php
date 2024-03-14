<?php defined('ABSPATH') or die;

	// ensure EXT is defined
	if ( ! defined('EXT')) {
		define('EXT', '.php');
	}

	$basepath = DIRS_DIR .'includes/main/';
	require $basepath.'main'.EXT;

	// load classes

	$interfacepath = $basepath.'interfaces'.DIRECTORY_SEPARATOR;
	direviews::require_all($interfacepath);

	$classpath = $basepath.'classes'.DIRECTORY_SEPARATOR;
	direviews::require_all($classpath);

	// load callbacks

	$callbackpath = $basepath.'callbacks'.DIRECTORY_SEPARATOR;
	direviews::require_all($callbackpath);
