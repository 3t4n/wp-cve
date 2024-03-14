<?php
namespace QuadLayers\QLWAPP\Entities;

use QuadLayers\WP_Orm\Entity\SingleEntity;

class Scheme extends SingleEntity {
	public $font_family                = 'inherit';
	public $font_size                  = '18';
	public $icon_size                  = '60';
	public $icon_font_size             = '24';
	public $brand                      = '';
	public $text                       = '';
	public $link                       = '';
	public $message                    = '';
	public $label                      = '';
	public $name                       = '';
	public $contact_role_color         = '';
	public $contact_name_color         = '';
	public $contact_availability_color = '';
}
