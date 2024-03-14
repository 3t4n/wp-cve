<?php
/**
 * WP Ultimate Exporter plugin file.
 *
 * Copyright (C) 2010-2020, Smackcoders Inc - info@smackcoders.com
 */
namespace Smackcoders\SMEXP;

if ( ! defined( 'ABSPATH' ) )
exit; // Exit if accessed directly

/**
 * Class LearnPressExport
 * @package Smackcoders\SMEXP
 */

class metabox extends ExportExtension{

	protected static $instance = null,$export_instance;	
	public static function getInstance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
			metabox::$export_instance = ExportExtension::getInstance();
		}
		return self::$instance;
	}

	/**
	 * CustomerReviewExport constructor.
	 */
	public function __construct() {
		$this->plugin = Plugin::getInstance();

    }
   
}
global $metabox_exp_class;
$metabox_exp_class = new metabox();