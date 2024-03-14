<?php

namespace ZPOS;

use ZPOS\Admin\Setting\PostTab;
use ZPOS\Admin\Stations\Post;
use ZPOS_UI\License as UILicense;

class Station
{
	const CLOUD_POS_STATION_SLUG_META_FIELD = '_pos_cloud_pos_station_url';

	protected $id;
	public $post;

	public function __construct($id)
	{
		$this->id = $id;
		$this->post = get_post($id);

		if ($this->post->post_type !== Post::TYPE || $this->post === null) {
			throw new StationException('Not found station');
		}
	}

	public function getBaseURL($append = '')
	{
		$structure = get_option('permalink_structure');

		$url = parse_url(home_url());

		$base_url =
			$url['scheme'] . '://' . $url['host'] . (isset($url['port']) ? ':' . $url['port'] : '');

		return str_replace($base_url, '', get_the_permalink($this->post->ID)) .
			($structure ? '' : ($append ? '&rest=/' : '&rest=')) .
			$append;
	}

	public function getID()
	{
		return $this->post->ID;
	}

	public function getDebugURL()
	{
		return add_query_arg('debug', '1', $this->getBaseURL());
	}

	public function getData($key)
	{
		return PostTab::getValue($key, $this->post->ID);
	}

	public static function getFromOrder($order)
	{
		if (!$order instanceof \WC_Order) {
			if ($order instanceof \WP_Post) {
				$order = new \WC_Order($order->ID);
			} elseif (is_int($order)) {
				$order = new \WC_Order($order);
			} else {
				throw new \Exception('The $order parameter must be WC_Order, or WC_Post, or integer.');
			}
		}
		$station_id = $order->get_meta('_pos_by');
		if ($station_id === 'pos') {
			$station_id = self::getDefaultStationID();
		} elseif (empty($station_id)) {
			$station_id = self::getWCStationID();
		}
		return new self($station_id);
	}

	public static function getDefaultStationID()
	{
		return (int) get_option('pos_legacy_station_id', null);
	}

	public static function getWCStationID()
	{
		return (int) get_option('pos_wc_station_id', null);
	}

	public static function isWCStation($post)
	{
		return self::getWCStationID() === (int) $post;
	}

	public static function isDefaultStation($post)
	{
		return self::getDefaultStationID() === (int) $post;
	}

	public static function getURL($post_id)
	{
		return Plugin::isActive('pos-ui')
			? (UILicense::isActive()
				? get_the_permalink($post_id)
				: '#')
			: Plugin::getPOSCloudAppURL() .
					'/cloud-pos-station/?url=' .
					home_url('/') .
					'&stationSlug=' .
					(self::getCloudAppStationSlug($post_id) ?: $post_id);
	}

	public static function getCloudAppStationSlug($post_id)
	{
		return get_post_meta($post_id, self::CLOUD_POS_STATION_SLUG_META_FIELD, true);
	}

	public static function setCloudAppStationSlug($post_id, $pos_url)
	{
		return update_post_meta($post_id, self::CLOUD_POS_STATION_SLUG_META_FIELD, $pos_url);
	}
}
