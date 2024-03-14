<?php

namespace ZPOS\API;

use WP_REST_Server;
use WC_REST_Taxes_Controller;
use ZPOS\Admin\Setting\PostTab;
use ZPOS\Admin\Stations\Tabs\Tax;
use ZPOS\API;
use const ZPOS\REST_NAMESPACE;
use ZPOS\Structure\EmptyEntity;

class Taxes extends WC_REST_Taxes_Controller
{
	protected $namespace = REST_NAMESPACE;

	public function __construct()
	{
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);

		add_filter('woocommerce_get_settings_tax', [$this, 'add_shipping_tax_class_setting'], 10, 2);
	}

	public function register_routes()
	{
		parent::register_routes();
		do_action(__METHOD__, $this, $this->namespace, $this->rest_base);
		register_rest_route(
			$this->namespace,
			'/applications/(?P<id>[\d]+)/' . $this->rest_base . '/(?P<tax_class>[^/]+)',
			[
				[
					'methods' => WP_REST_Server::READABLE,
					'callback' => [$this, 'get_shop_items'],
					'permission_callback' => [$this, 'get_items_permissions_check'],
				],
			]
		);
	}

	public function get_shop_items($request)
	{
		$tax_class = $request['tax_class'] === 'standard' ? '' : $request['tax_class'];
		$pos = +$request['id'];
		$empty_entity = new EmptyEntity();
		$taxes = $this->get_current_taxes_rates($empty_entity, $tax_class, $pos);
		return $taxes ? array_values($taxes) : [];
	}

	public function get_items_permissions_check($request)
	{
		if (current_user_can('read_woocommerce_pos_setting')) {
			return true;
		}

		return parent::get_items_permissions_check($request);
	}

	/**
	 * @param $entity \WC_Order|\WC_Customer|EmptyEntity
	 * @param $tax_class
	 * @param $pos \WP_Post|int
	 * @return array
	 */
	public static function get_current_taxes_rates($entity, $tax_class, $pos)
	{
		$options = Tax::getValue('pos_tax_based_on_order', $pos);
		foreach ($options as $option) {
			switch ($option) {
				case 'shipping':
					if (empty($entity->get_shipping_country())) {
						continue 2;
					}
					$args = [
						'country' => $entity->get_shipping_country(),
						'state' => $entity->get_shipping_state(),
						'city' => $entity->get_shipping_city(),
						'postcode' => $entity->get_shipping_postcode(),
					];
					break;
				case 'billing':
					if (empty($entity->get_billing_country())) {
						continue 2;
					}
					$args = [
						'country' => $entity->get_billing_country(),
						'state' => $entity->get_billing_state(),
						'city' => $entity->get_billing_city(),
						'postcode' => $entity->get_billing_postcode(),
					];
					break;
				case 'pos':
					if (empty(PostTab::getValue('pos_country', $pos))) {
						continue 2;
					}
					$args = [
						'country' => PostTab::getValue('pos_country', $pos),
						'state' => PostTab::getValue('pos_state', $pos),
						'city' => PostTab::getValue('pos_city', $pos),
						'postcode' => PostTab::getValue('pos_postcode', $pos),
					];
					break;
				default:
					return \WC_Tax::get_base_tax_rates($tax_class);
			}
			return \WC_Tax::find_rates(
				array_merge($args, [
					'tax_class' => $tax_class,
				])
			);
		}
	}

	/**
	 * Is necessary for the /settings/tax/woocommerce_shipping_tax_class API call
	 * when the Shipping location(s) option is disabled
	 */
	public function add_shipping_tax_class_setting($settings, $current_section)
	{
		if ('' === $current_section && API::is_pos() && !wc_shipping_enabled()) {
			$settings['shipping-tax-class'] = [
				'title' => __('Shipping tax class', 'woocommerce'),
				'desc' => __(
					'Optionally control which tax class shipping gets, or leave it so shipping tax is based on the cart items themselves.',
					'woocommerce'
				),
				'id' => 'woocommerce_shipping_tax_class',
				'css' => 'min-width:150px;',
				'default' => 'inherit',
				'type' => 'select',
				'class' => 'wc-enhanced-select',
				'options' =>
					['inherit' => __('Shipping tax class based on cart items', 'woocommerce')] +
					wc_get_product_tax_class_options(),
				'desc_tip' => true,
			];
		}

		return $settings;
	}
}
