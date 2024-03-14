<?php
namespace LeadloversPlugin;
use LeadloversPlugin\Actions\Leadlovers;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'elementor_pro/init', function() {
	
	include_once( __DIR__ .'/elementor-integration/leadlovers.php' );
	
	$sendy_action = new Leadlovers();
	\ElementorPro\Plugin::instance()->modules_manager->get_modules( 'forms' )->add_form_action( $sendy_action->get_name(), $sendy_action );
});

