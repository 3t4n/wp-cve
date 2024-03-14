<?php

/*
Plugin Name: Webo-facto
Plugin URI: https://www.webo-facto.com
Description: Cette extension permet la liaison du site WordPress avec le webo-facto (https://www.webo-facto.com). Grâce à celle-ci, vous êtes automatiquement authentifié sur l'interface d'administation du site avec votre compte utilisateur webo-facto.
Author: Medialibs
Author URI: https://www.medialibs.com
Version: 1.37
License: GPLv2 or later
 */

/**
 * Autoloader
 */
spl_autoload_register(function ($classname) {
	if (class_exists($classname)) {
		return null;
	}
	$explodedClassName = explode('_', $classname);
	if (count($explodedClassName) === 1) {
		return null;
	}
	$classPath = plugin_dir_path(__FILE__) . 'WeboFacto/' . $explodedClassName[1] . '.php';
	if (file_exists($classPath)) {
		require_once plugin_dir_path(__FILE__) . 'WeboFacto/' . $explodedClassName[1] . '.php';
	}
});

// Gestion de l'installation, de l'activation et de la désactivation
(new WeboFacto_Installer())->init(__FILE__);

// Gestion du SSO
(new WeboFacto_Sso())->init();
