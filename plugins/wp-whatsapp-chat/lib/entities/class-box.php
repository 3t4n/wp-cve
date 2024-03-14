<?php
namespace QuadLayers\QLWAPP\Entities;

use QuadLayers\WP_Orm\Entity\SingleEntity;

class Box extends SingleEntity {
	public $enable          = 'yes';
	public $auto_open       = 'no';
	public $auto_delay_open = 1000;
	public $lazy_load       = 'no';
	public $header          = '<h3 style="
									font-size: 26px;
									font-weight: bold;
									margin: 0 0 0.25em 0;
								">Hello!</h3>
								<p style="
									font-size: 14px;
								">Click one of our contacts below to chat on WhatsApp</p>';
	public $footer          = '<p style="text-align: start;">Social Chat is free, download and try it now <a target="_blank" href="' . QLWAPP_LANDING_URL . '">here!</a></p>';
	public $response;

	public function __construct() {
		$this->response = esc_html__( 'Write a response', 'wp-whatsapp-chat' );
	}
}
