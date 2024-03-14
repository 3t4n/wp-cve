<?php
/**
 * NETOPIA
 *
 * @package   Netopia_Payment_Request_Card
 * @copyright  Copyright (c) 2007-2013 Netopia
 * @author      Claudiu Tudose <claudiu.tudose@netopia-system.com>
 *
 * This class can be used for accessing NETOPIA payment interface for your configured online services
 */
class Netopia_Payment_Request_Card extends Netopia_Payment_Request_Abstract  
{
    /**
     *
     * class-specific errors
     * @var integer
     */
    const ERROR_LOAD_FROM_XML_ORDER_INVOICE_ELEM_MISSING = 0x30000001;

    /**
     *
     * customer types
     * @var integer
     */
    const CUSTOMER_TYPE_MERCHANT = 0x01;
    const CUSTOMER_TYPE_MOBILPAY = 0x02;

    /**
     *
     * invoice details object
     * @var Netopia_Payment_Invoice
     */
    public $invoice = null;

    /**
     *
     * recurrent informations object
     * @var Netopia_Payment_Recurrence
     */
    public $recurrence = null;

    /**
     *
     * split informations
     * @var Netopia_Payment_Split
     */
    public $split = null;

    /**
     *
     * Constructor
     */
    function __construct()
	{
		parent::__construct();
		$this->type = self::PAYMENT_TYPE_CARD;
    }

    /**
     *
     * Populate the class from the request xml
     * @param DOMNode $elem
     * @return Netopia_Payment_Reuquest_Card
     * @throws Exception On missing xml attributes
     */
    protected function _loadFromXml(DOMElement $elem)
	{
		parent::_parseFromXml($elem);
		//card request specific data
		$elems = $elem->getElementsByTagName('invoice');
		if ($elems->length != 1)
		{
			throw new Exception('Netopia_Payment_Request_Card::loadFromXml failed; invoice element is missing', self::ERROR_LOAD_FROM_XML_ORDER_INVOICE_ELEM_MISSING);
		}
		$this->invoice = new Netopia_Payment_Invoice($elems->item(0));
		$elems = $elem->getElementsByTagName('recurrence');
		if ($elems->length > 0)
		{
			$this->recurrence = new Netopia_Payment_Recurrence($elems->item(0));
		}
		$elems = $elem->getElementsByTagName('split');
		if ($elems->length > 0)
		{
			$this->split = new Netopia_Payment_Split($elems->item(0));
		}
	
		$elems = $elem->getElementsByTagName('payment_instrument');
		if ($elems->length > 0)
		{
			$this->paymentInstrument = new Netopia_Payment_Instrument_Card($elems->item(0));
		}

		return $this;
    }

    /**
     * (non-PHPdoc)
     * @see Netopia_Payment_Request_Abstract::_prepare()
     */
    protected function _prepare()
	{
		if (is_null($this->signature) || is_null($this->orderId) || !($this->invoice instanceof Netopia_Payment_Invoice))
		{
			throw new Exception('One or more mandatory properties are invalid!', self::ERROR_PREPARE_MANDATORY_PROPERTIES_UNSET);
		}
		$this->_xmlDoc = new DOMDocument('1.0', 'utf-8');
		$rootElem = $this->_xmlDoc->createElement('order');
		//set payment type attribute
		$xmlAttr = $this->_xmlDoc->createAttribute('type');
		$xmlAttr->nodeValue = $this->type;
		$rootElem->appendChild($xmlAttr);
		//set id attribute
		$xmlAttr = $this->_xmlDoc->createAttribute('id');
		$xmlAttr->nodeValue = $this->orderId;
		$rootElem->appendChild($xmlAttr);
		//set timestamp attribute
		$xmlAttr = $this->_xmlDoc->createAttribute('timestamp');
		$xmlAttr->nodeValue = date('YmdHis');
		$rootElem->appendChild($xmlAttr);
		$xmlElem = $this->_xmlDoc->createElement('signature');
		$xmlElem->nodeValue = $this->signature;
		$rootElem->appendChild($xmlElem);
		$xmlElem = $this->_xmlDoc->createElement('service');
		$xmlElem->nodeValue = $this->service;
		$rootElem->appendChild($xmlElem);
		if ($this->secretCode)
		{
			$xmlAttr = $this->_xmlDoc->createAttribute('secretcode');
			$xmlAttr->nodeValue = $this->secretCode;
			$rootElem->appendChild($xmlAttr);
		}
		$xmlElem = $this->invoice->createXmlElement($this->_xmlDoc);
		$rootElem->appendChild($xmlElem);
		if($this->ipnCipher !== null)
		{
			$xmlElem = $this->_xmlDoc->createElement('ipn_cipher');
			$xmlElem->nodeValue = $this->ipnCipher;
			$rootElem->appendChild($xmlElem);	
		}

		if ($this->recurrence instanceof Netopia_Payment_Recurrence)
		{
			$xmlElem = $this->recurrence->createXmlElement($this->_xmlDoc);
			$rootElem->appendChild($xmlElem);
		}

		if ($this->split instanceof Netopia_Payment_Split)
		{
			$xmlSplit = $this->_xmlDoc->createElement('split');
			$xmlElems = $this->split->createXmlElement($this->_xmlDoc);
			foreach($xmlElems as $xmlElem)
			{
				$xmlSplit->appendChild($xmlElem);
			}
			$rootElem->appendChild($xmlSplit);
		}

		if ($this->paymentInstrument instanceof Netopia_Payment_Instrument_Card)
		{
			$xmlElem = $this->_xmlDoc->createElement('payment_instrument');
			$xmlElem2 = $this->paymentInstrument->createXmlElement($this->_xmlDoc);
			$xmlElem->appendChild($xmlElem2);
			$rootElem->appendChild($xmlElem);
		}
		if (is_array($this->params) && sizeof($this->params) > 0)
		{
			$xmlParams = $this->_xmlDoc->createElement('params');
			foreach ($this->params as $key => $value)
			{
				if (is_array($value))
				{
					foreach ($value as $v)
					{
						$xmlParam = $this->_xmlDoc->createElement('param');
						$xmlName = $this->_xmlDoc->createElement('name');
						$xmlName->nodeValue = trim($key);
						$xmlParam->appendChild($xmlName);
						$xmlValue = $this->_xmlDoc->createElement('value');
						$xmlValue->appendChild($this->_xmlDoc->createCDATASection($v));
						$xmlParam->appendChild($xmlValue);
						$xmlParams->appendChild($xmlParam);
					}
				}
				else
				{
					$xmlParam = $this->_xmlDoc->createElement('param');
					$xmlName = $this->_xmlDoc->createElement('name');
					$xmlName->nodeValue = trim($key);
					$xmlParam->appendChild($xmlName);
					$xmlValue = $this->_xmlDoc->createElement('value');
					$xmlValue->appendChild($this->_xmlDoc->createCDATASection($value));
					$xmlParam->appendChild($xmlValue);
					$xmlParams->appendChild($xmlParam);
				}
	    	}
			$rootElem->appendChild($xmlParams);
		}
		if (!is_null($this->returnUrl) || !is_null($this->confirmUrl))
		{
			$xmlUrl = $this->_xmlDoc->createElement('url');
			if (!is_null($this->returnUrl))
			{
				$xmlElem = $this->_xmlDoc->createElement('return');
				$c = $this->_xmlDoc->createCDATASection($this->returnUrl);
				$xmlElem->appendChild($c);
				$xmlUrl->appendChild($xmlElem);
			}
	    	if (!is_null($this->confirmUrl))
			{
				$xmlElem = $this->_xmlDoc->createElement('confirm');
				$xmlElem->nodeValue = $this->confirmUrl;
				$xmlUrl->appendChild($xmlElem);
			}
			$rootElem->appendChild($xmlUrl);
		}

		$this->_xmlDoc->appendChild($rootElem);

		return $this;
    }

    /**
     *
     * Serialization logic
     * @return array
     */
    public function __sleep()
	{
		$ret=  parent::__sleep();
		$ret[]='split';
		return $ret;
    }

    public function getPurchase()
	{
		throw new Ntp_Exception('IDS_BAD_CALL');
    }

}