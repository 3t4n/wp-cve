<?php
/**
 * Handle the contact object.
 *
 * @version     1.0.2
 * @package     EverAccounting\Models
 * @class       Customer
 */

namespace EverAccounting\Abstracts;

use EverAccounting\Abstracts\Resource_Model;
use EverAccounting\Repositories;

defined( 'ABSPATH' ) || exit;

/**
 * Class Contact
 *
 * @since   1.1.0
 *
 * @package EverAccounting\Models
 */
abstract class Contact extends Resource_Model {

	/**
	 * This is the name of this object type.
	 *
	 * @var string
	 */
	protected $object_type = 'contact';

	/**
	 * Cache group.
	 *
	 * @since 1.1.0
	 * @var string
	 */
	protected $cache_group = 'ea_contacts';

	/**
	 * Repository name.
	 *
	 * @since 1.1.0
	 * @var string
	 */
	protected $repository_name = 'contacts';

	/**
	 * Item Data array.
	 *
	 * @since 1.1.0
	 *
	 * @var array
	 */
	protected $data = array(
		'user_id'       => null,
		'name'          => '',
		'company'       => '',
		'email'         => '',
		'phone'         => '',
		'birth_date'    => '',
		'street'        => '',
		'city'          => '',
		'state'         => '',
		'postcode'      => '',
		'country'       => '',
		'website'       => '',
		'vat_number'    => '',
		'currency_code' => '',
		'type'          => 'contact',
		'thumbnail_id'  => null,
		'enabled'       => 1,
		'creator_id'    => null,
		'date_created'  => null,
	);

	/**
	 * Get the contact if ID is passed, otherwise the contact is new and empty.
	 *
	 * @param int|object $data object to read.
	 *
	 * @since 1.1.0
	 */
	public function __construct( $data = 0 ) {
		parent::__construct( $data );
		$this->repository = Repositories::load( $this->repository_name );
	}

	/*
	|--------------------------------------------------------------------------
	| Getters
	|--------------------------------------------------------------------------
	|
	| Functions for getting item data. Getter methods wont change anything unless
	| just returning from the props.
	|
	*/

	/**
	 * Get contact's wp user ID.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @since 1.0.2
	 *
	 * @return int|null
	 */
	public function get_user_id( $context = 'edit' ) {
		return $this->get_prop( 'user_id', $context );
	}

	/**
	 * Get contact Name.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @since 1.0.2
	 *
	 * @return string
	 */
	public function get_name( $context = 'edit' ) {
		return $this->get_prop( 'name', $context );
	}

	/**
	 * Get contact company.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @since 1.0.2
	 *
	 * @return string
	 */
	public function get_company( $context = 'edit' ) {
		return $this->get_prop( 'company', $context );
	}

	/**
	 * Get contact's email.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @since 1.0.2
	 *
	 * @return string
	 */
	public function get_email( $context = 'edit' ) {
		return $this->get_prop( 'email', $context );
	}

	/**
	 * Get contact's phone number.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @since 1.0.2
	 *
	 * @return string
	 */
	public function get_phone( $context = 'edit' ) {
		return $this->get_prop( 'phone', $context );
	}

	/**
	 * Get contact's website number.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @since 1.0.2
	 *
	 * @return string
	 */
	public function get_website( $context = 'edit' ) {
		return $this->get_prop( 'website', $context );
	}

	/**
	 * Get contact's birth date.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @since 1.0.2
	 *
	 * @return string
	 */
	public function get_birth_date( $context = 'edit' ) {
		return $this->get_prop( 'birth_date', $context );
	}

	/**
	 * Get contact's street.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @since 1.0.2
	 *
	 * @return string
	 */
	public function get_street( $context = 'edit' ) {
		return $this->get_prop( 'street', $context );
	}

	/**
	 * Get contact's city.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @since 1.0.2
	 *
	 * @return string
	 */
	public function get_city( $context = 'edit' ) {
		return $this->get_prop( 'city', $context );
	}

	/**
	 * Get contact's state.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @since 1.0.2
	 *
	 * @return string
	 */
	public function get_state( $context = 'edit' ) {
		return $this->get_prop( 'state', $context );
	}

	/**
	 * Get contact's postcode.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @since 1.0.2
	 *
	 * @return string
	 */
	public function get_postcode( $context = 'edit' ) {
		return $this->get_prop( 'postcode', $context );
	}

	/**
	 * Get contact's country.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @since 1.0.2
	 *
	 * @return string
	 */
	public function get_country( $context = 'edit' ) {
		return $this->get_prop( 'country', $context );
	}

	/**
	 * Get contact's country.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @since 1.0.2
	 *
	 * @return string
	 */
	public function get_country_nicename( $context = 'edit' ) {
		$countries = eaccounting_get_countries();

		return isset( $countries[ $this->get_country() ] ) ? $countries[ $this->get_country() ] : $this->get_country();
	}

	/**
	 * Get contact's vat number.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @since 1.0.2
	 *
	 * @return string
	 */
	public function get_vat_number( $context = 'edit' ) {
		return $this->get_prop( 'vat_number', $context );
	}

	/**
	 * Get the currency code of the contact.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @since 1.0.2
	 *
	 * @return string
	 */
	public function get_currency_code( $context = 'edit' ) {
		return $this->get_prop( 'currency_code', $context );
	}

	/**
	 * Get the type of contact.
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @since 1.0.2
	 *
	 * @return string
	 */
	public function get_type( $context = 'edit' ) {
		return $this->get_prop( 'type', $context );
	}

	/**
	 * Get avatar id
	 *
	 * @param string $context What the value is for. Valid values are view and edit.
	 *
	 * @since 1.1.0
	 *
	 * @return int|null
	 */
	public function get_thumbnail_id( $context = 'edit' ) {
		return $this->get_prop( 'thumbnail_id', $context );
	}

	/*
	|--------------------------------------------------------------------------
	| Setters
	|--------------------------------------------------------------------------
	|
	| Functions for setting item data. These should not update anything in the
	| database itself and should only change what is stored in the class
	| object.
	*/

	/**
	 * Set wp user id.
	 *
	 * @param int $id WP user id.
	 *
	 * @since 1.0.2
	 */
	public function set_user_id( $id ) {
		$this->set_prop( 'user_id', absint( $id ) );
	}

	/**
	 * Set contact name.
	 *
	 * @param string $name Contact name.
	 *
	 * @since 1.0.2
	 */
	public function set_name( $name ) {
		$this->set_prop( 'name', eaccounting_clean( $name ) );
	}

	/**
	 * Set contact company.
	 *
	 * @param string $company Contact company.
	 *
	 * @since 1.0.2
	 */
	public function set_company( $company ) {
		$this->set_prop( 'company', eaccounting_clean( $company ) );
	}

	/**
	 * Set contact's email.
	 *
	 * @param string $value Email.
	 *
	 * @since 1.0.2
	 */
	public function set_email( $value ) {
		if ( $value && is_email( $value ) ) {
			$this->set_prop( 'email', sanitize_email( $value ) );
		}
	}

	/**
	 * Set contact's phone.
	 *
	 * @param string $value Phone.
	 *
	 * @since 1.0.2
	 */
	public function set_phone( $value ) {
		$this->set_prop( 'phone', eaccounting_clean( $value ) );
	}


	/**
	 * Set contact's birth date.
	 *
	 * @param string $date Birth date.
	 *
	 * @since 1.0.2
	 */
	public function set_birth_date( $date ) {
		$this->set_date_prop( 'birth_date', $date );
	}

	/**
	 * Set contact's website.
	 *
	 * @param string $value Website.
	 *
	 * @since 1.0.2
	 */
	public function set_website( $value ) {
		$this->set_prop( 'website', esc_url( $value ) );
	}

	/**
	 * Set contact's street.
	 *
	 * @param string $value Street.
	 *
	 * @since 1.0.2
	 */
	public function set_street( $value ) {
		$this->set_prop( 'street', sanitize_text_field( $value ) );
	}

	/**
	 * Set contact's city.
	 *
	 * @param string $city City.
	 *
	 * @since 1.0.2
	 */
	public function set_city( $city ) {
		$this->set_prop( 'city', sanitize_text_field( $city ) );
	}

	/**
	 * Set contact's state.
	 *
	 * @param string $state State.
	 *
	 * @since 1.0.2
	 */
	public function set_state( $state ) {
		$this->set_prop( 'state', sanitize_text_field( $state ) );
	}

	/**
	 * Set contact's postcode.
	 *
	 * @param string $postcode Postcode.
	 *
	 * @since 1.0.2
	 */
	public function set_postcode( $postcode ) {
		$this->set_prop( 'postcode', sanitize_text_field( $postcode ) );
	}

	/**
	 * Set contact country.
	 *
	 * @param string $country Country.
	 *
	 * @since 1.0.2
	 */
	public function set_country( $country ) {
		if ( array_key_exists( $country, eaccounting_get_countries() ) ) {
			$this->set_prop( 'country', $country );
		}
	}

	/**
	 * Set contact's tax_number.
	 *
	 * @param string $value Tax number.
	 *
	 * @since 1.0.2
	 */
	public function set_vat_number( $value ) {
		$this->set_prop( 'vat_number', eaccounting_clean( $value ) );
	}

	/**
	 * Set contact's currency_code.
	 *
	 * @param string $value Currency code.
	 *
	 * @since 1.0.2
	 */
	public function set_currency_code( $value ) {
		if ( eaccounting_get_currency( $value ) ) {
			$this->set_prop( 'currency_code', eaccounting_clean( $value ) );
		}
	}

	/**
	 * Set contact type.
	 *
	 * @param string $type Contact type.
	 *
	 * @since 1.0.2
	 */
	public function set_type( $type ) {
		if ( array_key_exists( $type, eaccounting_get_contact_types() ) ) {
			$this->set_prop( 'type', $type );
		}
	}

	/**
	 * Set avatar id
	 *
	 * @param int $thumbnail_id Avatar id.
	 *
	 * @since 1.1.0
	 */
	public function set_thumbnail_id( $thumbnail_id ) {
		$this->set_prop( 'thumbnail_id', absint( $thumbnail_id ) );
	}

	/*
	|--------------------------------------------------------------------------
	| Extra
	|--------------------------------------------------------------------------
	*/

	/**
	 * Return this customer's avatar.
	 *
	 * @param array $args Arguments to pass to get_avatar().
	 *
	 * @return string
	 */
	public function get_avatar_url( $args = array() ) {
		$thumbnail_id = $this->get_thumbnail_id();
		$url          = $thumbnail_id ? wp_get_attachment_url( $thumbnail_id ) : '';
		if ( ! empty( $thumbnail_id ) && $url ) {
			return $url;
		}

		return get_avatar_url( $this->get_email(), wp_parse_args( $args, array( 'size' => '100' ) ) );
	}

	/**
	 * Get default image url.
	 *
	 * @since 1.1.0
	 * @return string
	 */
	public function get_default_image_url() {
		return $this->get_avatar_url();
	}
}
