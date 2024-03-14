<?php
/*
Plugin Name: EasyMe Connect
Plugin URI: https://easyme.dk/
Description: Connect your EasyMe account to Wordpress and offer your services directly from your own Web site.
Author: EasyMe
Version: 3.0.2
Requires PHP: 5.4
Requires at least: 5.3
Text Domain: easyme
Domain Path: /lang
*/

include_once(__DIR__ . '/src/autoload.php');

\EasyMe\WP::run();

?>
