<?php

namespace Dropp;

use Dropp\Shipping_Method\Shipping_Method;
use Dropp\Shipping_Method\Dropp;

class Options
{
	public string $title;
	public string $api_key = '';
	public string $api_key_test;
	public string $store_id;
	public string $new_order_status;
	public bool   $enable_return_labels;
	public string $copy_order_notes;
	public bool   $test_mode;
	public bool   $debug_mode;
	public bool   $enable_ssn;
	public bool   $require_ssn;
	public bool   $location_name_in_label;
	public bool   $dropp_rates_first;
	public array  $price_info;

	protected string $key;
	protected array  $raw_options;

	protected static Options $instance;

	private function __construct(Shipping_Method $shipping_method)
	{
		$this->key = $shipping_method->get_option_key();
		$this->set_options(get_option($this->key));
	}

	public static function init(Shipping_Method $shipping_method): void
	{
		if (isset(self::$instance) || $shipping_method::class !== Dropp::class) {
			return;
		}
		self::$instance = new Options($shipping_method);
	}

	public static function get_instance(): Options
	{
		static $recursion = false;
		if (! isset(self::$instance) && ! $recursion) {
			$recursion = true;
			// The option instance is initialised during Shipping_Method::__construct
			new Dropp();
			$recursion = false;
		}
		return self::$instance;
	}

	private function set_options($options): void
	{
		$raw_options = wp_parse_args(
			$options,
			[
				'title'                  => '',
				'api_key'                => '',
				'api_key_test'           => '',
				'store_id'               => '',
				'new_order_status'       => '',
				'enable_return_labels'   => '',
				'copy_order_notes'       => 'yes',
				'test_mode'              => '',
				'debug_mode'             => '',
				'enable_ssn'             => 'yes',
				'require_ssn'            => 'yes',
				'location_name_in_label' => '',
				'dropp_rates_first'      => 'yes',
				'price_info'             => [],
			]
		);

		$this->title                  = $raw_options['title'];
		$this->api_key                = $raw_options['api_key'];
		$this->api_key_test           = $raw_options['api_key_test'];
		$this->store_id               = $raw_options['store_id'];
		$this->new_order_status       = $raw_options['new_order_status'];
		$this->enable_return_labels   = 'yes' === $raw_options['enable_return_labels'];
		$this->copy_order_notes       = 'yes' === $raw_options['copy_order_notes'];
		$this->test_mode              = 'yes' === $raw_options['test_mode'];
		$this->debug_mode             = 'yes' === $raw_options['debug_mode'];
		$this->enable_ssn             = 'yes' === $raw_options['enable_ssn'];
		$this->require_ssn            = 'yes' === $raw_options['require_ssn'] ?? 'yes';
		$this->location_name_in_label = 'yes' === $raw_options['location_name_in_label'];
		$this->dropp_rates_first      = 'yes' === $raw_options['dropp_rates_first'];
		$this->price_info             = $raw_options['price_info'];

		$this->raw_options = $raw_options;
	}
}
