<?php
namespace QuadLayers\QLWAPP\Entities;

use QuadLayers\WP_Orm\Entity\SingleEntity;

class Settings extends SingleEntity {
	public $googleAnalytics         = 'disable';
	public $googleAnalyticsScript   = 'no';
	public $googleAnalyticsV3Id     = '';
	public $googleAnalyticsV4Id     = '';
	public $googleAnalyticsLabel    = '';
	public $googleAnalyticsCategory = '';
}
