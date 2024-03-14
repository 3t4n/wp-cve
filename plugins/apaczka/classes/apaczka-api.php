<?php

class apaczkaApi {

 const SERVICE_UPS_K_STANDARD = 'UPS_K_STANDARD';
 const SERVICE_UPS_K_EX_SAV= 'UPS_K_EX_SAV';
 const SERVICE_UPS_K_EX = 'UPS_K_EX';
 const SERVICE_UPS_K_EXP_PLUS = 'UPS_K_EXP_PLUS';
 const SERVICE_DPD_CLASSIC = 'DPD_CLASSIC';
 const SERVICE_DHLSTD = 'DHLSTD';
 const SERVICE_DHL12 = 'DHL12';
 const SERVICE_DHL09 = 'DHL09';
 const SERVICE_DHL1722 = 'DHL1722';
 const SERVICE_UPS_Z_STANDARD = 'UPS_Z_STANDARD';
 const SERVICE_UPS_Z_EX_SAV = 'UPS_Z_EX_SAV';
 const SERVICE_UPS_Z_EX = 'UPS_Z_EX';
 const SERVICE_UPS_Z_EXPEDITED = 'UPS_Z_EXPEDITED';
 const SERVICE_TNT = 'TNT';
 const SERVICE_FEDEX = 'FEDEX';
 const SERVICE_KEX_EXPRESS = 'KEX_EXPRESS';
 const SERVICE_POCZTA_POLSKA = 'POCZTA_POLSKA';
 const SERVICE_POCZTA_POLSKA_E24 = 'POCZTA_POLSKA_E24';
 const SERVICE_SIODEMKA_STD = 'SIODEMKA_STD';
 const SERVICE_DPD_CLASSIC_FOREIGN = 'DPD_CLASSIC_FOREIGN';
 const SERVICE_TNT_Z = 'TNT_Z';
 const SERVICE_UPS_Z = 'UPS_Z';
 const SERVICE_INPOST = 'INPOST';
 const SERVICE_APACZKA_DE = 'APACZKA_DE';
 
//	Configuration
	var $apiKey = " Add apiKey Here";
	var $login = " Add User login Here";
	var $password = " Add User password Here";

	var $url_test = "http://test.apaczka.pl/webservice/order";
	var $url_prod = "https://apaczka.pl/webservice/order";

	var $wsdl_test = "http://test.apaczka.pl/webservice/order?wsdl";
	var $wsdl_prod = "https://apaczka.pl/webservice/order?wsdl";

	var $outputFileName = "XOLTResult.xml";

	var $soapError = "";

	private $mode = array('trace' => 1, 'exceptions' => 1, 'encoding' => 'UTF-8' );
	protected $client;

	private $isTest = 0;
	private $isVerboseMode = 0;
	
	function clearCache() {
		delete_option( 'apaczka_countries_cache' );
	}

	function __construct($login = '', $passwd = '', $apiKey = '', $isTest = 1 ) {




		$this->isTest = $isTest;
		if ($login != '' && $passwd != '' && $apiKey != '' ) {
			$this->apiKey 	= $apiKey;
			$this->login 	= $login;
			$this->password = $passwd;
		}

		$this->init();
	}

	function init() {
		if ($this->isTest) {
			$this->client = new SoapClient( $this->wsdl_test, $this->mode );
			$this->client->__setLocation($this->url_test);
		} 
		else {
			$this->client = new SoapClient($this->wsdl_prod, $this->mode);
			$this->client->__setLocation($this->url_prod);
		}
	}

	function placeOrder(ApaczkaOrder $order) {
		$PlaceOrderRequest = array();
		$PlaceOrderRequest['authorization'] = $this->bindAuthorization();
		$PlaceOrderRequest['order'] = $order->getOrder();
		$resp = $this->soapCall("placeOrder", array('placeOrder' => array('PlaceOrderRequest' => $PlaceOrderRequest)));
		return $resp;
	}

	function validateAuthData() {
		$validateAuthData = array();
		$validateAuthData['validateAuthData']['Authorization'] = $this->bindAuthorization();

		$resp = $this->soapCall("validateAuthData", $validateAuthData);

		return $resp;
	}

	function getCountries() {
		$getCountriesData = array();
		$getCountriesData['getCountries']['CountryRequest']['authorization'] = $this->bindAuthorization();

		$resp = $this->soapCall("getCountries", $getCountriesData);

		return $resp;
	}

	function getCountriesFromCache() {
		$countries = get_option( 'apaczka_countries_cache', '' );
		if ( $countries == '' ) {
			$countries = $this->getCountries();
			update_option( 'apaczka_countries_cache', $countries );
		}
		return $countries;
	}
	
	function getCollectiveWaybillDocument($idsArray = false) {
		$req = array();
		$req['authorization'] = $this->bindAuthorization();
		$req['orderIds'] = array();

		if ($idsArray)
			if (is_array($idsArray)) {
				$req['orderIds'] = array('long' => $idsArray);
			} else {
				$req['orderIds'] = array('long' => $idsArray);
			}

		$getCollectiveWaybillDocumentData = array();
		$getCollectiveWaybillDocumentData['getCollectiveWaybillDocument']['CollectiveWaybillRequest'] = $req;

		$resp = $this->soapCall("getCollectiveWaybillDocument", $getCollectiveWaybillDocumentData);

		return $resp;
	}

	function getWaybillDocument($orderId = false) {

		if (!is_numeric($orderId) || !(intval($orderId) > 0)) {
			throw new Exception('orderId must be intval: [' . print_r($orderId, 1) . '] given.');
		}

		$req = array();
		$req['authorization'] = $this->bindAuthorization();
		$req['orderId'] = $orderId;

		$getWaybillDocumentData = array();
		$getWaybillDocumentData['getWaybillDocument']['WaybillRequest'] = $req;
		$resp = $this->soapCall("getWaybillDocument", $getWaybillDocumentData);

		return $resp;
	}

	function getCollectiveTurnInCopyDocument($idsArray = false) {
		$req = array();
		$req['authorization'] = $this->bindAuthorization();
		$req['orderIds'] = array();

		if ($idsArray)
			if (is_array($idsArray)) {
				$req['orderIds'] = array('long' => $idsArray);
			} else {
				$req['orderIds'] = array('long' => $idsArray);
			}

		$getCollectiveTurnInCopyDocumentData = array();
		$getCollectiveTurnInCopyDocumentData['getCollectiveTurnInCopyDocument']['CollectiveTurnInCopyRequest'] = $req;

		$resp = $this->soapCall("getCollectiveTurnInCopyDocument", $getCollectiveTurnInCopyDocumentData);

		return $resp;
	}

	function soapCall($operation, $SoapBody) {
		if (!in_array($operation, array("placeOrder", "validateAuthData", "getCountries", "getCollectiveWaybillDocument", "getWaybillDocument", "getCollectiveTurnInCopyDocument"))) {
			throw new Exception('Unsupported operation: [' . $operation . ']');
		}

		try {
			$resp = $this->client->__soapCall($operation, $SoapBody);


			//save soap request and response to file
			//file_put_contents($this->outputFileName, "[" . date('c') . "]\n" . "SoapCall: [$operation]\n", FILE_APPEND);
			//file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . $this->outputFileName, "Request: \n" . $this->client->__getLastRequest() . "\n", FILE_APPEND);
			//file_put_contents($this->outputFileName, "Response: \n" . $this->client->__getLastResponse() . "\n\n", FILE_APPEND);

		} catch (Exception $ex) {
			if ($this->isVerboseMode) {
				print_r($ex);
			}

			//file_put_contents($this->outputFileName, "[" . date('c') . "]\n" . "SoapCall: [$operation]\n", FILE_APPEND);
			//file_put_contents($this->outputFileName, "Request: \n" . $this->client->__getLastRequest() . "\n", FILE_APPEND);
			//file_put_contents($this->outputFileName, "Response: \n" . $this->client->__getLastResponse() . "\n\n", FILE_APPEND);

			$this->soapError = $resp;

			//throw $ex;
			//return false;
		}
        $last = $this->client->__getLastRequest();
		if ($this->isVerboseMode) {
			echo("\n\n");
			print_r($this->client->__getLastRequest());
			echo("\n\n");
			print_r($this->client->__getLastResponse());
			echo("\n\n");
		}

		return $resp;
	}

	function bindAuthorization() {
		$auth = array();
		$auth['apiKey'] = $this->apiKey;
		$auth['login'] = $this->login;
		$auth['password'] = $this->password;

		return $auth;
	}

	function setVerboseMode() {
		$this->isVerboseMode = true;
	}

	function setTestMode() {
		$this->isTest = true;
		$this->init();
	}

	function setProductionMode() {
		$this->isTest = false;
		$this->init();
	}
	
	public function parse_return( $return ) {
		$ret = json_decode( json_encode( $return ), true );
		return $ret;
	}	
}

class ApaczkaOrder {

	var $notificationDelivered = array();
	var $notificationException = array();
	var $notificationNew = array();
	var $notificationSent = array();

	// cash on delivery
	var $accountNumber = "";
	var $codAmount = "";

	var $orderPickupType = "SELF";
	var $pickupTimeFrom = "";
	var $pickupTimeTo = "";
	var $pickupDate = "";

	var $options = "";

	private $address_receiver = array();
	private $address_sender = array();

	var $referenceNumber = '';
	var $serviceCode = "";
	public  $isDomestic = "true";
	var $contents = "";

	var $shipments = array();

	private static $dictServiceCode = array(
	    'UPS_K_STANDARD',
        'UPS_K_EX_SAV',
        'UPS_K_EX',
        'UPS_K_EXP_PLUS',
        'DPD_CLASSIC',
        'DHLSTD',
        'DHL12',
        'DHL09',
        'DHL1722',
        'UPS_Z_STANDARD',
        'UPS_Z_EX_SAV',
        'UPS_Z_EX',
        'UPS_Z_EXPEDITED',
        'TNT',
        'FEDEX',
        'KEX_EXPRESS',
        'POCZTA_POLSKA',
        'POCZTA_POLSKA_E24',
        'SIODEMKA_STD',
        'DPD_CLASSIC_FOREIGN',
        'TNT_Z',
        'UPS_Z',
        'INPOST',
        'APACZKA_DE',
        'PACZKOMAT',

    );
	private static $dictOrderPickupType = array(
	    'COURIER',
        'SELF',
        'EVERYDAY',
        'PHONE',
        'BOX_MACHINE');
	private static $dictOrderOptions = array('POBRANIE', 'ZWROT_DOK', 'DOR_OSOBA_PRYW', 'DOST_SOB', 'PODPIS_DOROS');

	function __construct($isDomestic = "true") {
		$this->notificationDelivered = $this->emptyNotification();
		$this->notificationException = $this->emptyNotification();
		$this->notificationNew = $this->emptyNotification();
		$this->notificationSent = $this->emptyNotification();
		$this->isDomestic = $isDomestic;
	}

	function setPobranie($accountNumber, $codAmount) {
		if (strlen($accountNumber) < 26) {
			throw new Exception('Bank account number to short: len(' . strlen($accountNumber) . ')[' . $accountNumber . ']');
		}

		if (!($codAmount > 0)) {
			throw new Exception('Cash on delivery amount must be greater then 0: [' . $codAmount . ']');
		}

		$this->accountNumber = $accountNumber;
		$this->codAmount = $codAmount;
		$this->addOrderOption('POBRANIE');
	}

	function createNotification($isReceiverEmail, $isReceiverSms, $isSenderEmail, $isSenderSms) {
		$notification = array();
		$notification['isReceiverEmail'] = $isReceiverEmail;
		$notification['isReceiverSms'] = $isReceiverSms;
		$notification['isSenderEmail'] = $isSenderEmail;
		$notification['isSenderSms'] = $isSenderSms;

		return $notification;
	}

	function emptyNotification() {
		$notification = array();
		$notification['isReceiverEmail'] = '';
		$notification['isReceiverSms'] = '';
		$notification['isSenderEmail'] = '';
		$notification['isSenderSms'] = '';

		return $notification;
	}

	function setPickup($orderPickupType, $pickupTimeFrom, $pickupTimeTo, $pickupDate) {
		if (!in_array($orderPickupType, self::$dictOrderPickupType)) {
			throw new Exception('UNSUPPORTED order pickup type: [' . $orderPickupType . '] must be one of: ' . print_r(self::$dictOrderPickupType, 1));
		}

		$this->orderPickupType = $orderPickupType;
		$this->pickupTimeFrom = $pickupTimeFrom;
		$this->pickupDate = $pickupDate;
		$this->pickupTimeTo = $pickupTimeTo;
	}

	function setServiceCode($serviceCode) {
		if (!in_array($serviceCode, self::$dictServiceCode)) {
			throw new Exception('UNSUPPORTED service code: [' . $serviceCode . '] must be one of: ' . print_r(self::$dictServiceCode, 1));
		}

		$this->serviceCode = $serviceCode;
	}

	function addOrderOption($option) {
		if (!in_array($option, self::$dictOrderOptions)) {
			throw new Exception('UNSUPPORTED order option: [' . $option . '] must be one of: ' . print_r(self::$dictOrderOptions, 1));
		}

		if ($this->options == "") {
			$this->options = array('string' => $option);
		} else if (!is_array($this->options['string'])) {
			$tmp_option = $this->options['string'];

			if ($tmp_option != $option) {
				$this->options['string'] = array($tmp_option, $option);
			}
		} else {
			if (in_array($option, self::$dictOrderOptions)) {
				$this->options['string'][] = $option;
			}
		}
	}

	function setReceiverAddress(
			$name = '', 
			$contactName = '', 
			$addressLine1 = '', 
			$addressLine2 = '', 
			$city = '', 
			$countryId = '', 
			$postalCode = '', 
			$stateCode = '', 
			$email = '', 
			$phone = ''
	) {
		$this->address_receiver = $this->createAddress($name, $contactName, $addressLine1, $addressLine2, $city, $countryId, $postalCode, $stateCode, $email, $phone);
	}

	function setSenderAddress(
			$name = '', 
			$contactName = '', 
			$addressLine1 = '', 
			$addressLine2 = '', 
			$city = '', 
			$countryId = '', 
			$postalCode = '', 
			$stateCode = '', 
			$email = '', 
			$phone = ''
	) {
		$this->address_sender = $this->createAddress($name, $contactName, $addressLine1, $addressLine2, $city, $countryId, $postalCode, $stateCode, $email, $phone);
	}

	function createAddress($name = '', $contactName = '', $addressLine1 = '', $addressLine2 = '', $city = '', $countryId = '', $postalCode = '', $stateCode = '', $email = '', $phone = '') {

		$address = array();
		$address['name'] = substr($name, 0, 50);
		$address['contactName'] = $contactName;

		$address['addressLine1'] = $addressLine1;
		$address['addressLine2'] = $addressLine2;
		$address['city'] = $city;
		$address['countryId'] = $countryId;
		$address['postalCode'] = $postalCode;

		if ($stateCode != '') {
			$address['stateCode'] = $stateCode;
		}

		$address['email'] = $email;
		$address['phone'] = $phone;

		return $address;

		die;
	}

	function addShipment(ApaczkaOrderShipment $shipment) {
		$this->shipments[] = $shipment;
		return;

		if ($this->shipments == "") {
			$this->shipments[] = $shipment;
		} else if (is_array($this->shipments) && count($this->shipments) == 1) {
			$tmp = $this->shipments;

			$this->shipments = array();
			$this->shipments[] = $tmp;
			$this->shipments[] = $shipment;
		} else {
			$this->shipments[] = $shipment;
		}
	}

	function createShipment() {
		$return = array();
		$position = 0;
		$t_tmp = $this->shipments;

		if (!is_array($t_tmp)) {
			$t_tmp = array($t_tmp);
		}

		foreach ($t_tmp as $a) {
			$ship = array();
			$ship['dimension1'] = $a->dimension1;
			$ship['dimension2'] = $a->dimension2;
			$ship['dimension3'] = $a->dimension3;
			$ship['weight'] = $a->weight;
			$ship['shipmentTypeCode'] = $a->getShipmentTypeCode();
			$ship['position'] = $position;

			if ($a->getShipmentValue() > 0) {
				$ship['shipmentValue'] = $a->getShipmentValue();
			}

			$ship['options'] = $a->getOptions();

			$return[] = $ship;

			$position++;
		}

		if ($position === 1) {
			return array('Shipment' => $ship);
		}

		return array('Shipment' => $return);
	}

	function getOrder() {
		$order = array();

		if (!($this->accountNumber == "" || $this->codAmount == "")) {
			$order['accountNumber'] = $this->accountNumber;
			$order['codAmount'] = $this->codAmount;
		}

		$order['notificationDelivered'] = $this->notificationDelivered;
		$order['notificationException'] = $this->notificationException;
		$order['notificationNew'] = $this->notificationNew;
		$order['notificationSent'] = $this->notificationSent;

		$order['orderPickupType'] = $this->orderPickupType;

		if ($this->pickupTimeFrom != '' and $this->pickupTimeTo != '') {
			$order['pickupTimeFrom'] = $this->pickupTimeFrom;
			$order['pickupTimeTo'] = $this->pickupTimeTo;
			$order['pickupDate'] = $this->pickupDate;
		}

		$order['options'] = $this->options;

		$order['serviceCode'] = $this->serviceCode;
		$order['referenceNumber'] = $this->referenceNumber;


		if ('true' === $this->isDomestic) {
		    //fix: isDomestic jest obsługiwany tylko dla Polski
            $order['isDomestic'] = $this->isDomestic;
        } else {
            $order['isDomestic'] = false;
        }

		$order['contents'] = $this->contents;


		$order['receiver'] = $this->address_receiver;
		$order['sender'] = $this->address_sender;

		$order['shipments'] = $this->createShipment();

		return $order;
	}

}

class ApaczkaOrderShipment {

	var $dimension1 = '';
	var $dimension2 = '';
	var $dimension3 = '';
	var $weight = '';
	private $shipmentTypeCode = '';
	private $shipmentValue = '';
	public  $options = '';
	private $position = 0;
	private static $dictShipmentOptions = array('UBEZP', 'PRZES_NIETYP', 'DUZA_PACZKA');
	private static $dictShipmentTypeCode = array('LIST', 'PACZ', 'PALETA');

	function __construct($shipmentTypeCode = '', $dim1 = '', $dim2 = '', $dim3 = '', $weight = '') {
		if ($shipmentTypeCode == 'LIST') {
			$this->createShipment($shipmentTypeCode, 0, 0, 0, 0);
		} else {
			if ($dim1 != '' && $dim2 != '' && $dim3 != '' && $weight != '' && $shipmentTypeCode != '') {
				$this->createShipment($shipmentTypeCode, $dim1, $dim2, $dim3, $weight);
			}
		}
	}

	function getShipmentTypeCode() {
		return $this->shipmentTypeCode;
	}

	function setShipmentTypeCode($shipmentTypeCode) {
		if (!in_array($shipmentTypeCode, self::$dictShipmentTypeCode)) {
			throw new Exception('UNSUPPORTED service code: [' . $shipmentTypeCode . '] must be one of: ' . print_r(self::$dictShipmentTypeCode, 1));
		}

		$this->shipmentTypeCode = $shipmentTypeCode;
	}

	function getOptions() {
		return $this->options;
	}

	function addOrderOption($option) {
		if (!in_array($option, self::$dictShipmentOptions)) {
			throw new Exception('UNSUPPORTED order option: [' . $option . '] must be one of: ' . print_r(self::$dictShipmentOptions, 1));
		}

		if ($this->options == "") {
			$this->options = array('string' => $option);
		} else if (!is_array($this->options['string'])) {
			$tmp_option = $this->options['string'];

			if ($tmp_option != $option) {
				$this->options['string'] = array($tmp_option, $option);
			}
		} else {
		    if (!in_array($option, $this->options['string'])) {
                $this->options['string'][] = $option;
            }
		}
	}

    function addParcelLockerId($id) {
        if ($this->options == "") {
            $this->options = array('string' => $id);
        } else if (!is_array($this->options['string'])) {
            $tmp_option = $this->options['string'];
            if ($tmp_option !== $id) {
                $this->options['string'] = array($tmp_option, $id);
            }
        } else {
            $this->options['string'][] = $id;
        }
    }

	function getShipmentValue() {
		return $this->shipmentValue;
	}

	function setShipmentValue($value) {
		if (!$value > 0) {
			throw new Exception('UNSUPPORTED ShipmentValue: [' . $value . '] ShipmentValue must be greater then 0');
		}

		$this->shipmentValue = $value;
		$this->addOrderOption('UBEZP');
	}

	function createShipment($shipmentTypeCode, $dim1 = '', $dim2 = '', $dim3 = '', $weight = '') {

		$this->setShipmentTypeCode($shipmentTypeCode);

		$this->dimension1 = $dim1;
		$this->dimension2 = $dim2;
		$this->dimension3 = $dim3;

		$this->weight = $weight;

		if ((300 < (2 * $dim1 + 2 * $dim2 + $dim3) && (2 * $dim1 + 2 * $dim2 + $dim3) < 330) || (32 < $weight) && ($weight < 70)) {
			$this->addOrderOption('PRZES_NIETYP');
		}
		/*
		  if(330<(2*$dim1+2*$dim2+$dim3)<419 || $weight >= 70){
		  addOrderOption('DUZA_PACZKA');
		  }
		 */
	}
	
} 