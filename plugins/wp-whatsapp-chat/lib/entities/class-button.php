<?php
namespace QuadLayers\QLWAPP\Entities;

use QuadLayers\WP_Orm\Entity\SingleEntity;

class Button extends SingleEntity {
	public $layout          = 'button';
	public $box             = 'no';
	public $position        = 'bottom-right';
	public $text            = '';
	public $message         = '';
	public $icon            = 'qlwapp-whatsapp-icon';
	public $type            = 'phone';
	public $phone           = QLWAPP_PHONE_NUMBER;
	public $group           = '';
	public $developer       = 'no';
	public $rounded         = 'yes';
	public $timefrom        = '00:00';
	public $timeto          = '00:00';
	public $timedays        = array();
	public $timezone        = '';
	public $visibility      = 'readonly';
	public $timeout         = 'readonly';
	public $animation_name  = '';
	public $animation_delay = '';

	public function __construct() {
		$this->text     = esc_html__( 'How can I help you?', 'wp-whatsapp-chat' );
		$this->message  = sprintf( esc_html__( 'Hello! I\'m testing the %1$s plugin %2$s', 'wp-whatsapp-chat' ), QLWAPP_PLUGIN_NAME, QLWAPP_LANDING_URL );
		$this->timezone = qlwapp_get_timezone_current();
	}
}
