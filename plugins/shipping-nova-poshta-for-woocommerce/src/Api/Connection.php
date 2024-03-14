<?php
/**
 * Connection with API.
 *
 * @package   Shipping-Nova-Poshta-For-Woocommerce
 * @author    WP Unit
 * @link      http://wp-unit.com/
 * @copyright Copyright (c) 2020
 * @license   GPL-2.0+
 * @wordpress-plugin
 */

namespace NovaPoshta\Api;

use DateTime;
use WP_Error;
use Exception;
use DateTimeZone;
use NovaPoshta\Api\Http\Request;
use NovaPoshta\Api\Http\Response;
use NovaPoshta\Api\V2\Entities\Sender;
use NovaPoshta\Api\V2\Entities\Recipient;

/**
 * Class Client is the main class that communicates with
 * the Sendinblue API.
 *
 * @since 1.0.0
 */
class Connection {

	/**
	 * Request.
	 *
	 * @since {VERSION}
	 *
	 * @var Request
	 */
	private $request;

	/**
	 * Validator for fields.
	 *
	 * @var ValidateField
	 */
	private $validator;

	/**
	 * Client constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param Request       $request   Request.
	 * @param ValidateField $validator Validate fields.
	 */
	public function __construct( Request $request, ValidateField $validator ) {

		$this->request   = $request;
		$this->validator = $validator;
	}

	/**
	 * Get list of cities.
	 *
	 * @return Response|WP_Error
	 */
	public function get_cities() {

		return $this->request->request(
			'POST',
			'Address',
			'getCities'
		);
	}

	/**
	 * Get one city for validation.
	 *
	 * @return Response|WP_Error
	 */
	public function get_city() {

		return $this->request->request(
			'POST',
			'Address',
			'getCities',
			[
				'Limit' => 1,
			]
		);
	}

	/**
	 * Get list of warehouses for passed city.
	 *
	 * @param string $city_id City ID.
	 *
	 * @return Response|WP_Error
	 */
	public function get_warehouses( string $city_id ) {

		try {
			return $this->request->request(
				'POST',
				'AddressGeneral',
				'getWarehouses',
				[
					'CityRef' => $this->validator->validate_id( $city_id ),
				]
			);
		} catch ( Exception $e ) {
			return new WP_Error( $e->getCode(), $e->getMessage() );
		}
	}

	/**
	 * Calculate shipping cost.
	 *
	 * @param string $city_sender_id    City sender ID.
	 * @param string $city_recipient_id City recipient ID.
	 * @param array  $volume            Volume.
	 * @param float  $weight            Weight.
	 *
	 * @return Response|WP_Error
	 */
	public function calculate_shipping_cost( string $city_sender_id, string $city_recipient_id, array $volume, float $weight ) {

		try {
			return $this->request->request(
				'POST',
				'InternetDocument',
				'getDocumentPrice',
				[
					'CitySender'    => $this->validator->validate_id( $city_sender_id ),
					'CityRecipient' => $this->validator->validate_id( $city_recipient_id ),
					'CargoType'     => 'Parcel',
					'DateTime'      => $this->get_current_date(),
					'OptionsSeat'   => [
						[
							'weight'           => max( 0.1, $weight ),
							'volumetricWidth'  => wc_get_dimension( $volume['width'], 'cm', 'm' ),
							'volumetricLength' => wc_get_dimension( $volume['length'], 'cm', 'm' ),
							'volumetricHeight' => wc_get_dimension( $volume['height'], 'cm', 'm' ),
						],
					],
				]
			);
		} catch ( Exception $e ) {
			return new WP_Error( $e->getCode(), $e->getMessage() );
		}
	}

	/**
	 * Get current date.
	 *
	 * @return string Current date in valid format.
	 *
	 * @throws \Exception Invalid date time.
	 */
	private function get_current_date(): string {

		return ( new DateTime( '', new DateTimeZone( 'Europe/Kiev' ) ) )->format( 'd.m.Y' );
	}

	/**
	 * Get list of counterparties.
	 *
	 * @param string $city_id Current city ID.
	 *
	 * @return Response|WP_Error
	 */
	public function get_counterparties( string $city_id ) {

		try {
			return $this->request->request(
				'POST',
				'Counterparty',
				'getCounterparties',
				[
					'City'                 => $this->validator->validate_id( $city_id ),
					'CounterpartyProperty' => 'Sender',
					'Page'                 => 1,
				]
			);
		} catch ( Exception $e ) {
			return new WP_Error( $e->getCode(), $e->getMessage() );
		}
	}

	/**
	 * Get list of counterparty contact person.
	 *
	 * @param string $counterparty_id Counterparty ID.
	 *
	 * @return Response|WP_Error
	 */
	public function get_counterparty_contact_persons( string $counterparty_id ) {

		try {
			return $this->request->request(
				'POST',
				'Counterparty',
				'getCounterpartyContactPersons',
				[
					'Ref' => $this->validator->validate_id( $counterparty_id ),
				]
			);
		} catch ( Exception $e ) {
			return new WP_Error( $e->getCode(), $e->getMessage() );
		}
	}

	/**
	 * Get recipient.
	 *
	 * @param string $first_name First name.
	 * @param string $last_name  Last name.
	 * @param string $phone      Phone.
	 * @param string $city_id    City ID.
	 * @param string $location   Warehouse ID or Home address.
	 * @param string $type       Type of delivery warehouse|address.
	 *
	 * @return Response|WP_Error
	 */
	public function get_recipient( string $first_name, string $last_name, string $phone, string $city_id, string $location, string $type = 'warehouse' ) {

		try {
			return $this->request->request(
				'POST',
				'Counterparty',
				'save',
				[
					'FirstName'            => $this->validator->validate_name( $first_name ),
					'LastName'             => $this->validator->validate_name( $last_name ),
					'Phone'                => $this->validator->validate_phone( $phone ),
					'RecipientsPhone'      => $this->validator->validate_phone( $phone ),
					'City'                 => $this->validator->validate_id( $city_id ),
					'CityRecipient'        => $this->validator->validate_id( $city_id ),
					'RecipientAddress'     => 'warehouse' === $type ?
						$this->validator->validate_id( $location ) :
						$this->validator->validate_address( $location ),
					'CounterpartyProperty' => 'Recipient',
					'CounterpartyType'     => 'PrivatePerson',
				]
			);
		} catch ( Exception $e ) {
			return new WP_Error( $e->getCode(), $e->getMessage() );
		}
	}

	/**
	 * Create invoice.
	 *
	 * @param Sender    $sender      Sender.
	 * @param Recipient $recipient   Recipient.
	 * @param float     $price       Price.
	 * @param string    $description Description of delivery product.
	 * @param array     $volume      Volume of package.
	 * @param float     $weight      Weight of package.
	 * @param float|int $redelivery  Price for redelivery.
	 *
	 * @return Response|WP_Error
	 */
	public function create_invoice(
		Sender $sender,
		Recipient $recipient,
		float $price,
		string $description,
		array $volume,
		float $weight,
		float $redelivery = 0
	) {

		try {
			$data = [
				'CitySender'       => $this->validator->validate_id( $sender->get_city_id() ),
				'SenderAddress'    => $this->validator->validate_id( $sender->get_warehouse_id() ),
				'SendersPhone'     => $this->validator->validate_phone( $sender->get_phone() ),
				'Sender'           => $this->validator->validate_id( $sender->get_id() ),
				'ContactSender'    => $this->validator->validate_id( $sender->get_person_id() ),
				'CityRecipient'    => $this->validator->validate_id( $recipient->get_city_id() ),
				'RecipientsPhone'  => $this->validator->validate_phone( $recipient->get_phone() ),
				'Recipient'        => $this->validator->validate_id( $recipient->get_id() ),
				'ContactRecipient' => $this->validator->validate_id( $recipient->get_person_id() ),
				'ServiceType'      => 'address' === $recipient->get_delivery_type() ? 'WarehouseDoors' : 'WarehouseWarehouse',
				'PaymentMethod'    => 'Cash',
				'PayerType'        => 'Recipient',
				'Cost'             => $price,
				'SeatsAmount'      => 1,
				'Description'      => $this->validator->validate_description( $description ),
				'OptionsSeat'      => [
					[
						'volumetricVolume' => 1,
						'volumetricWidth'  => wc_get_dimension( $volume['width'], 'cm', 'm' ),
						'volumetricLength' => wc_get_dimension( $volume['length'], 'cm', 'm' ),
						'volumetricHeight' => wc_get_dimension( $volume['height'], 'cm', 'm' ),
						'weight'           => max( 0.1, $weight ),
					],
				],
				'CargoType'        => 'Parcel',
				'DateTime'         => $this->get_current_date(),
			];

			if ( 'address' === $recipient->get_delivery_type() ) {
				$data['NewAddress']           = 1;
				$data['RecipientType']        = 'PrivatePerson';
				$data['RecipientAddressName'] = $this->validator->validate_address( $recipient->get_address() );
			} else {
				$data['RecipientAddress'] = $this->validator->validate_id( $recipient->get_address() );
			}

			if ( ! empty( $redelivery ) ) {
				$data['BackwardDeliveryData'] = [
					[
						'PayerType'        => 'Recipient',
						'CargoType'        => 'Money',
						'RedeliveryString' => $redelivery,
					],
				];
			}

			return $this->request->request(
				'POST',
				'InternetDocument',
				'save',
				$data
			);
		} catch ( Exception $e ) {
			return new WP_Error( $e->getCode(), $e->getMessage() );
		}
	}

}
