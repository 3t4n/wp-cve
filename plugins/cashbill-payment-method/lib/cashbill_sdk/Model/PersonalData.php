<?php
/**
 *
 * CashBill Payment PHP SDK
 *
 * @author Lukasz Firek <lukasz.firek@cashbill.pl>
 * @version 1.0.0
 * @license MIT
 * @copyright CashBill S.A. 2015
 *
 * http://cashbill.pl
 *
 */
namespace CashBill\Payments\Model;

use CashBill\Payments\Interfaces\Data;
use CashBill\Payments\Services\CashBillPersonalDataException;

class PersonalData implements Data {
	
	/**
	 *
	 * @var string $firstName
	 * @var string $surname
	 * @var string $email
	 * @var string $country
	 * @var string $city
	 * @var string $postcode
	 * @var string $street
	 * @var string $house
	 * @var string $flat
	 */
	public $firstName, $surname, $email, $country, $city, $postcode, $street, $house, $flat;
	
	
	/**
	 *
	 * @param string $firstName
	 * @param string $surname
	 */
	public function setName($firstName, $surname) {
		$this->firstName = $firstName;
		$this->surname = $surname;
	}
	
	/**
	 *
	 * @param string $country
	 * @param string $city
	 * @param string $postCode
	 * @param string $street
	 * @param string $house
	 * @param string $flat
	 */
	
	public function setAddress($country, $city, $postCode, $street, $house, $flat = '') {
		$this->country = $country;
		$this->city = $city;
		$this->postcode = $postCode;
		$this->street = $street;
		$this->house = $house;
		$this->flat = $flat;
	}
	
	/**
	 *
	 * @param string $email        	
	 * @throws CashBillPersonalDataException
	 */
	public function setEmail($email) {
		if (! filter_var ( $email, FILTER_VALIDATE_EMAIL )) {
			throw new CashBillPersonalDataException ( "Email address not valid" );
		}
		
		$this->email = $email;
	}
	
	public static function fromArray($userData)
	{
		$returnObject = new PersonalData();
		
		if (isset ( $userData ['firstName'] )) {
			$returnObject->firstName = $userData ['firstName'];
		}
		if (isset ( $userData ['surname'] )) {
			$returnObject->surname = $userData ['surname'];
		}
		if (isset ( $userData ['email'] )) {
			$returnObject->setEmail ( $userData ['email'] );
		}
		if (isset ( $userData ['country'] )) {
			$returnObject->country = $userData ['country'];
		}
		if (isset ( $userData ['city'] )) {
			$returnObject->city = $userData ['city'];
		}
		if (isset ( $userData ['postcode'] )) {
			$returnObject->postcode = $userData ['postcode'];
		}
		if (isset ( $userData ['street'] )) {
			$returnObject->street = $userData ['street'];
		}
		if (isset ( $userData ['house'] )) {
			$returnObject->house = $userData ['house'];
		}
		if (isset ( $userData ['flat'] )) {
			$returnObject->flat = $userData ['flat'];
		}
		return $returnObject;
	}
	
	/**
	 *
	 * @see CashBillData::toArray()
	 */
	public function toArray() {
		$array = array ();
		foreach ( $this as $key => $value ) {
			$array ["personalData." . $key] = $value;
		}
		return $array;
	}
}
