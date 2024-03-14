<?php
namespace QuadLayers\QLWAPP\Entities;

use QuadLayers\WP_Orm\Entity\CollectionEntity;
use QuadLayers\QLWAPP\Services\Entity_Options;

class Contact extends CollectionEntity {
	public static $primaryKey = 'id'; //phpcs:ignore
	public $id                = 0;
	public $order             = 1;
	public $active            = 1;
	public $chat              = 1;
	public $auto_open         = 0;
	public $avatar            = 'https://www.gravatar.com/avatar/00000000000000000000000000000000';
	public $type              = 'phone';
	public $phone             = '';
	public $group             = '';
	public $firstname         = 'John';
	public $lastname          = 'Doe';
	public $label;
	public $message;
	public $timefrom = '00:00';
	public $timeto   = '00:00';
	public $timezone;
	public $visibility = 'readonly';
	public $timeout    = 'readonly';
	public $timedays   = array();
	public $display;

	public function __construct() {
		$this->label    = esc_html__( 'Support', 'wp-whatsapp-chat' );
		$this->message  = sprintf( esc_html__( 'Hello! I\'m testing the %1$s plugin %2$s', 'wp-whatsapp-chat' ), QLWAPP_PLUGIN_NAME, QLWAPP_LANDING_URL );
		$this->timezone = qlwapp_get_timezone_current();

		$entity_options = Entity_Options::instance();

		$this->display = $entity_options->get_args();
	}
}
