<?php
/**
 * API for Nova Poshta
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta\Api;

use WP_Error;
use Exception;
use NovaPoshta\DB;
use NovaPoshta\Main;
use NovaPoshta\Language;
use NovaPoshta\Settings\Settings;
use NovaPoshta\Cache\FactoryCache;
use NovaPoshta\Api\V2\Entities\Sender;
use NovaPoshta\Api\V2\Entities\Recipient;
use NovaPoshta\Api\Exception\AllowOnlyOneRecipientAddress;

/**
 * Class API
 *
 * @package Nova_Poshta\Core
 */
class Api {

	/**
	 * Nova Poshta API endpoint
	 */
	const ENDPOINT = 'https://api.novaposhta.ua/v2.0/json/';

	/**
	 * Client.
	 *
	 * @var Client
	 */
	private $client;

	/**
	 * Database
	 *
	 * @var DB
	 */
	private $db;

	/**
	 * Cache
	 *
	 * @var FactoryCache
	 */
	private $factory_cache;

	/**
	 * Plugin settings
	 *
	 * @var array
	 */
	private $settings;

	/**
	 * Language object
	 *
	 * @var Language
	 */
	private $language;

	/**
	 * API constructor.
	 *
	 * @param Client       $client        Client.
	 * @param DB           $db            Database.
	 * @param FactoryCache $factory_cache Factory Cache.
	 * @param Settings     $settings      Plugin settings.
	 * @param Language     $language      Language object.
	 */
	public function __construct( Client $client, DB $db, FactoryCache $factory_cache, Settings $settings, Language $language ) {

		$this->client        = $client;
		$this->settings      = $settings;
		$this->factory_cache = $factory_cache;
		$this->db            = $db;
		$this->language      = $language;
	}

	/**
	 * List of the cities
	 *
	 * @param string $search Search string.
	 * @param int    $limit  Limit cities in result.
	 *
	 * @return array
	 */
	public function cities( string $search = '', int $limit = 0 ): array {

		if ( ! $this->settings->api_key() ) {
			return [];
		}

		if ( empty( $limit ) ) {
			$limit = (int) apply_filters( 'shipping_nova_poshta_for_woocommerce_api_cities_limit', 20 );
		}

		$cache = $this->factory_cache->transient();

		if ( $cache->get( 'cities_updated' ) ) {
			return $this->db->cities( $search, $limit );
		}

		$connection = $this->client->new_connection( $this->settings->api_key() );
		$response   = $connection->get_cities();

		if ( is_wp_error( $response ) || is_wp_error( $response->get_body() ) ) {
			return $this->db->cities( $search, $limit );
		}

		$cities = $response->get_body();

		$this->db->update_cities( $cities );
		$cache->set( 'cities_updated', 1, constant( 'DAY_IN_SECONDS' ) );
		unset( $response );
		unset( $cities );

		return $this->db->cities( $search, $limit );
	}

	/**
	 * City name
	 *
	 * @param string $city_id City ID.
	 *
	 * @return string
	 */
	public function city( string $city_id ): string {

		return $this->db->city( $city_id );
	}

	/**
	 * City area
	 *
	 * @param string $city_id City ID.
	 *
	 * @return string
	 */
	public function area( string $city_id ): string {

		return $this->db->area( $city_id );
	}

	/**
	 * Warehouse full description
	 *
	 * @param string $city_id      City ID.
	 * @param string $warehouse_id Warehouse ID.
	 *
	 * @return string
	 */
	public function warehouse( string $city_id, string $warehouse_id ): string {

		$warehouses = $this->warehouses( $city_id );

		return ! empty( $warehouses[ $warehouse_id ] ) ? $warehouses[ $warehouse_id ] : '';
	}

	/**
	 * List of warehouses
	 *
	 * @param string $city_id Warehouse ID.
	 *
	 * @return array
	 */
	public function warehouses( string $city_id ): array {

		$cache      = $this->factory_cache->transient();
		$lang       = 'ru' === $this->language->get_current_language() ? 'ru' : 'ua';
		$cache_key  = 'warehouse-' . $lang . '-' . $city_id;
		$warehouses = $cache->get( $cache_key );

		if ( $warehouses ) {
			return $warehouses;
		}

		$connection = $this->client->new_connection( $this->settings->api_key() );
		$response   = $connection->get_warehouses( $city_id );

		if ( is_wp_error( $response ) || is_wp_error( $response->get_body() ) ) {
			return [];
		}

		$warehouses = $response->get_body();

		$this->db->update_warehouses( $warehouses );

		$field_name = 'ru' === $lang ? 'DescriptionRu' : 'Description';
		$warehouses = wp_list_pluck( $warehouses, $field_name, 'Ref' );
		$cache->set( $cache_key, $warehouses, constant( 'DAY_IN_SECONDS' ) );

		return $warehouses;
	}

	/**
	 * Shipping cost
	 *
	 * @param string $recipient_city_id Recipient City ID.
	 * @param float  $weight            Weight.
	 * @param array  $volume            Volume weight.
	 *
	 * @return array
	 */
	public function shipping_cost( string $recipient_city_id, float $weight, array $volume ): array {

		$city_id = $this->settings->city_id();
		$key     = 'shipping_cost-from-' . $city_id . '-to-' . $recipient_city_id . '-' . $weight . '-' . implode( '-', $volume );
		$cache   = $this->factory_cache->object();
		$costs   = $cache->get( $key );
		if ( ! empty( $costs ) ) {
			return $costs;
		}

		$connection = $this->client->new_connection( $this->settings->api_key() );
		$response   = $connection->calculate_shipping_cost(
			$city_id,
			$recipient_city_id,
			$volume,
			$weight
		);

		if ( is_wp_error( $response ) || is_wp_error( $response->get_body() ) ) {
			return [];
		}

		$costs = ! empty( $response->get_body()[0] ) ? $response->get_body() : 0;
		$cache->set( $key, $costs, 5 * MINUTE_IN_SECONDS );

		return $costs;
	}

	/**
	 * Create internet document
	 *
	 * @param string $first_name Customer first name.
	 * @param string $last_name  Customer last name.
	 * @param string $phone      Customer phone.
	 * @param string $city_id    Customer city ID.
	 * @param string $location   Warehouse ID or Address.
	 * @param string $type       Type of delivery warehouse|address.
	 * @param float  $price      Order price.
	 * @param float  $weight     Weight of all products in order.
	 * @param array  $volume     Volume of all products in order.
	 * @param float  $redelivery Cash on delivery price.
	 *
	 * @return string|WP_Error
	 * @throws Exception Invalid DateTime.
	 */
	public function internet_document( // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh
		string $first_name,
		string $last_name,
		string $phone,
		string $city_id,
		string $location,
		string $type,
		float $price,
		float $weight = 0,
		array $volume = [],
		float $redelivery = 0
	) {

		if ( empty( $this->settings->api_key() ) ) {
			return new WP_Error(
				400,
				sprintf( /* translators: %s - link to the settings page */
					esc_html__( 'Failed to create a sender, fill the API key and sender\'s data, on <a href="%s">the settings page</a>', 'shipping-nova-poshta-for-woocommerce' ),
					esc_url( get_admin_url( null, 'admin.php?page=' . Main::PLUGIN_SLUG ) )
				)
			);
		}

		$sender = $this->sender();

		if ( is_wp_error( $sender ) ) {
			return $sender;
		}

		$recipient = $this->recipient( $first_name, $last_name, $phone, $city_id, $location, $type );

		if ( is_wp_error( $recipient ) ) {
			return $recipient;
		}

		$connection = $this->client->new_connection( $this->settings->api_key() );
		$response   = $connection->create_invoice(
			$sender,
			$recipient,
			$price,
			! empty( $this->settings->description() )
				? $this->settings->description()
				: 'Товар',
			$volume,
			max( 0.1, $weight ),
			$redelivery
		);

		if ( is_wp_error( $response ) || is_wp_error( $response->get_body() ) ) {
			return $response;
		}

		return ! empty( $response->get_body()[0]['IntDocNumber'] )
			? $response->get_body()[0]['IntDocNumber']
			: new WP_Error(
				400,
				sprintf( /* translators: %s - link to the settings page */
					esc_html__( 'Failed to create a an invoice.', 'shipping-nova-poshta-for-woocommerce' ),
					esc_url( get_admin_url( null, 'admin.php?page=' . Main::PLUGIN_SLUG ) )
				)
			);
	}

	/**
	 * Create sender
	 *
	 * @return WP_Error|Sender
	 */
	private function sender() {

		$cache  = $this->factory_cache->transient();
		$sender = $cache->get( 'warehouse_sender' );

		if ( $sender ) {
			return $sender;
		}

		$connection = $this->client->new_connection( $this->settings->api_key() );
		$response   = $connection->get_counterparties( $this->settings->city_id() );

		if ( is_wp_error( $response ) || is_wp_error( $response->get_body() ) ) {
			return $response;
		}

		if ( empty( $response->get_body()[0]['Ref'] ) ) {
			return new WP_Error(
				400,
				sprintf( /* translators: %s - link to the settings page */
					esc_html__( 'Failed to create a sender, check the data, on <a href="%s">the settings page</a>', 'shipping-nova-poshta-for-woocommerce' ),
					esc_url( get_admin_url( null, 'admin.php?page=' . Main::PLUGIN_SLUG ) )
				)
			);
		}

		$counterparty_id = $response->get_body()[0]['Ref'];
		$response        = $connection->get_counterparty_contact_persons( $counterparty_id );

		if ( is_wp_error( $response ) || is_wp_error( $response->get_body() ) ) {
			return $response;
		}

		if ( empty( $response->get_body()[0]['Ref'] ) ) {
			return new WP_Error(
				400,
				sprintf( /* translators: %s - link to the settings page */
					esc_html__( 'Failed to create a sender, check the data, on <a href="%s">the settings page</a>', 'shipping-nova-poshta-for-woocommerce' ),
					esc_url( get_admin_url( null, 'admin.php?page=' . Main::PLUGIN_SLUG ) )
				)
			);
		}

		$counterparty_contact_id = $response->get_body()[0]['Ref'];

		$sender = new Sender(
			$counterparty_id,
			$counterparty_contact_id,
			$this->settings->phone(),
			$this->settings->city_id(),
			$this->settings->warehouse_id()
		);

		$cache->set( 'warehouse_sender', $sender, constant( 'DAY_IN_SECONDS' ) );

		return $sender;
	}

	/**
	 * Create recipient
	 *
	 * @param string $first_name First name.
	 * @param string $last_name  Last name.
	 * @param string $phone      Phone number.
	 * @param string $city_id    City ID.
	 * @param string $location   Warehouse ID or Address.
	 * @param string $type       Type of delivery warehouse|address.
	 *
	 * @return WP_Error|Recipient
	 *
	 * @throws AllowOnlyOneRecipientAddress Only one address can added to recipient.
	 */
	private function recipient(
		string $first_name,
		string $last_name,
		string $phone,
		string $city_id,
		string $location,
		string $type = 'warehouse'
	) {

		$location = 'address' === $type ? $this->sanitize_address( $location ) : $location;

		$connection = $this->client->new_connection( $this->settings->api_key() );
		$response   = $connection->get_recipient(
			$first_name,
			$last_name,
			$phone,
			$city_id,
			$location,
			$type
		);

		if ( is_wp_error( $response ) || is_wp_error( $response->get_body() ) ) {
			return $response;
		}

		if ( empty( $response->get_body()[0]['Ref'] ) || empty( $response->get_body()[0]['ContactPerson']['data'][0]['Ref'] ) ) {
			return new WP_Error(
				400,
				sprintf( /* translators: %s - link to the settings page */
					esc_html__( 'Failed to create recipient, check order details. Required fields are First name, Last name, Phone, City ID, and Warehouse or Address.', 'shipping-nova-poshta-for-woocommerce' ),
					esc_url( get_admin_url( null, 'admin.php?page=' . Main::PLUGIN_SLUG ) )
				)
			);
		}

		$recipient = new Recipient(
			$response->get_body()[0]['Ref'],
			$response->get_body()[0]['ContactPerson']['data'][0]['Ref'],
			$phone,
			$city_id
		);

		if ( 'address' === $type ) {
			$recipient->add_home_delivery( $location );

			return $recipient;
		}

		$recipient->add_warehouse_delivery( $location );

		return $recipient;
	}

	/**
	 * Prepare address for request.
	 *
	 * @param string $address Address.
	 *
	 * @return string
	 */
	private function sanitize_address( string $address ): string {

		$address = trim( $address );
		$address = preg_replace( '/[^0-9А-Яа-яґіїєҐІЇЄ, -]/u', '', $address );
		$address = preg_replace( '/\s{2,}/', ' ', $address );

		return mb_substr( $address, 0, 36 );
	}

	/**
	 * Validate api key
	 *
	 * @param string $api_key API key.
	 *
	 * @return bool
	 */
	public function validate( string $api_key ): bool {

		$connection = $this->client->new_connection( $api_key );
		$response   = $connection->get_city();

		return ! is_wp_error( $response ) && ! is_wp_error( $response->get_body() );
	}
}
