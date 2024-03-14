<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Integration\Mailchimp;

use ShopMagicVendor\Psr\Log\LoggerInterface;
use WPDesk\ShopMagic\Customer\Customer;

/**
 * MailChimp Tools for ShopMagic
 *
 * @since   1.0.0
 */
final class APITools extends MailChimp implements MailchimpApi {
	private const YES          = 'yes';
	private const MERGE_FIELDS = 'merge_fields';
	private const LNAME        = 'LNAME';
	private const ADDRESS      = 'ADDRESS';
	private const CITY         = 'CITY';
	private const STATE        = 'STATE';
	private const COUNTRY      = 'COUNTRY';
	// Look for new 'merge-fields' and add them if necessary
	// Get new merge-fields 'TAG'=>'name'.
	/**
	 * @var array<string, string>
	 */
	private const MAILCHIMP_NEW_MERGEFIELDS = [
		self::ADDRESS => 'Address',
		self::CITY    => 'City',
		self::STATE   => 'State',
		self::COUNTRY => 'Country',
	];

	/** @var LoggerInterface */
	private $logger;

	public function __construct( $api_key, LoggerInterface $logger ) {
		parent::__construct( $api_key );
		$this->logger = $logger;
	}

	public function add_member( MemberParamsBag $member_params ): bool {
		if ( $member_params->get_order() !== null ) {
			return $this->add_member_from_order(
				$member_params->get_order(),
				$member_params->get_list_id(),
				$member_params->is_double_opt_in()
			);
		}

		if ( $member_params->get_customer() !== null ) {
			return $this->add_member_from_user_customer(
				$member_params->get_customer(),
				$member_params->get_list_id(),
				$member_params->is_double_opt_in()
			);
		}

		if ( $member_params->get_email() !== null ) {
			return $this->add_member_from_email(
				$member_params->get_email(),
				$member_params->get_list_id(),
				$member_params->is_double_opt_in()
			);
		}

		return false;
	}

	private function add_member_from_user_customer( Customer $customer, string $mailchimp_list_id, string $mailchimp_doubleoptin ): bool {
		$basic_params = $this->prepare_basic_params( $customer->get_email(), $customer->get_first_name(), $customer->get_last_name(), $mailchimp_doubleoptin );

		return $this->add_to_list( $basic_params, $mailchimp_list_id );
	}

	private function add_member_from_email( string $email, string $mailchimp_list_id, string $mailchimp_doubleoptin ): bool {
		$basic_params = $this->prepare_basic_params( $email, '', '', $mailchimp_doubleoptin );

		return $this->add_to_list( $basic_params, $mailchimp_list_id );
	}

	/**
	 * @return array<string, mixed[]>
	 */
	private function prepare_basic_params( string $email, string $first_name, string $last_name, string $double_optin ): array {
		$member_status = \in_array( strtolower( $double_optin ), [
			'on',
			self::YES,
		] ) ? 'pending' : 'subscribed';

		return [
			'email_address'    => $email,
			'status'           => $member_status,
			self::MERGE_FIELDS => [
				'FNAME'     => $first_name,
				self::LNAME => $last_name,
			],
		];
	}

	/**
	 * @return bool true on success
	 */
	private function add_to_list( array $params, string $list_id ): bool {
		$response = $this->post(
			'lists/' . $list_id . '/members',
			$params
		);

		$this->logger->debug( 'Response from MailChimp: ' . json_encode( [ 'response' => $response ] ) );

		return \is_array( $response );
	}

	/**
	 * @param \WC_Abstract_Order|\WC_Order_Refund $order
	 *
	 * @return bool
	 */
	private function add_member_from_order( $order, string $mailchimp_list_id, string $doubleoptin ): bool {
		if ( $mailchimp_list_id === '' ) {
			return false;
		}
		if ( $this->getApiKey() === false ) {
			return false;
		}
		// Get further information settings.
		$mailchimp_further_information = [
			self::LNAME   => get_option( 'wc_settings_tab_mailchimp_info_lname', false ),
			self::ADDRESS => get_option( 'wc_settings_tab_mailchimp_info_address', false ),
			self::CITY    => get_option( 'wc_settings_tab_mailchimp_info_city', false ),
			self::STATE   => get_option( 'wc_settings_tab_mailchimp_info_state', false ),
			self::COUNTRY => get_option( 'wc_settings_tab_mailchimp_info_country', false ),
		];

		$billing_email = $order->billing_email;
		$billing_fname = $order->billing_first_name;

		// Last name depends on the further information settings.
		$billing_lname = ( in_array( $mailchimp_further_information[ self::LNAME ], [
			self::YES,
			true,
		], true ) ? $order->billing_last_name : '' );

		$billing_address = $order->billing_address_1 . ' ' . $order->billing_address_2;
		$billing_city    = $order->billing_city;
		$billing_state   = $order->billing_state;
		$billing_country = $order->billing_country;

		if ( ! empty( $billing_email ) && filter_var( $billing_email, FILTER_VALIDATE_EMAIL ) ) {
			$mailchimp_add_member_params = $this->prepare_basic_params( $billing_email, $billing_fname, $billing_lname, $doubleoptin );

			foreach ( self::MAILCHIMP_NEW_MERGEFIELDS as $tag => $name ) {

				// If information checked on the settings.
				if ( in_array( $mailchimp_further_information[ $tag ], [
					self::YES,
					true,
				], true ) ) {
					$mailchimp_add_mergefield_params = [
						'tag'  => $tag,
						'name' => $name,
						'type' => 'text',
					];

					// MailChimp API Call for adding new merge-field.
					$this->add_merge_field( $mailchimp_list_id, $mailchimp_add_mergefield_params );

					if ( ! $this->success() ) {
						$this->logger->error( $this->getLastError() );
					}

					// Change params depending further information settings ( from WC settings ShopMagic ).

					switch ( $tag ) {
						case self::ADDRESS:
							$mailchimp_add_member_params[ self::MERGE_FIELDS ][ self::ADDRESS ] = $billing_address;
							break;

						case self::CITY:
							$mailchimp_add_member_params[ self::MERGE_FIELDS ][ self::CITY ] = $billing_city;
							break;

						case self::STATE:
							$mailchimp_add_member_params[ self::MERGE_FIELDS ][ self::STATE ] = $billing_state;
							break;

						case self::COUNTRY:
							$mailchimp_add_member_params[ self::MERGE_FIELDS ][ self::COUNTRY ] = $billing_country;
							break;
					}
				}
			}

			$result = $this->add_to_list( $mailchimp_add_member_params, $mailchimp_list_id );

			if ( ! $this->success() ) {
				$this->logger->error( $this->getLastError() );
			}

			return $result;
		}

		return false;
	}

	/**
	 * @param mixed[] $params
	 */
	private function add_merge_field( string $mailchimp_list_id, array $params ): void {

		// MailChimp API Call for adding new merge-field.
		$this->post(
			'lists/' . $mailchimp_list_id . '/merge-fields',
			$params
		);
	}

	/**
	 * Extract the lists names and id to be used on options for the select element 'List name'
	 *
	 * @return string[]
	 */
	public function get_all_lists_options(): array {
		// Get the list of lists.
		$lists_options = [
			'0' => __( 'Select...', 'shopmagic-for-woocommerce' ),
		];
		$lists         = $this->get( 'lists?count=1000' );

		if ( $this->success() ) {
			if ( ( \is_array( $lists['lists'] ) || $lists['lists'] instanceof \Countable ? \count( $lists['lists'] ) : 0 ) > 0 ) {
				// If one list or more.
				foreach ( $lists['lists'] as $list_obj ) {
					$lists_options[ $list_obj['id'] ] = $list_obj['name'] . ' [' . $list_obj['id'] . ']';
				}
			} else {
				// If no lists yet or an error.
				$lists_options = [
					'0' => __( 'No lists are set yet!', 'shopmagic-for-woocommerce' ),
				];
			}
		} else {
			// If an error is there.
			$lists_options = [
				'0' => __( 'Please make sure to provide Mailchimp API key!', 'shopmagic-for-woocommerce' ),
			];
		}

		return $lists_options;
	}
}
