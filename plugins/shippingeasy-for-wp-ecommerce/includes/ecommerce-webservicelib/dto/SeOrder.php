<?php
/**
 * SeOrder.php.
 *
 * PHP Version 5.3.1
 *
 * @category  SeDto
 * @package   Shippingeasy
 * @author    Saturized - The Interactive Agency <office@saturized.com>
 * @copyright 2010 Saturized - The Interactive Agency
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt GPLv2
 * @version   SVN: $Id: nebojsa $
 */

/**
 * This class represents one item in request result array.
 *
 * @package    ShippingEasy
 * @subpackage SeApi
 * @author     Saturized - The Interactive Agency <office@saturized.com>
 * @version    Release: SeOrder.v.0.1
 */
class SeOrder
{
  /**
   * OrderId from shop system.
   *
   * @var integer
   */
  protected $id;

  /**
   * Date from shop system.
   *
   * @var UNIX timestamp
   */
  protected $date;

  /**
   * serviceId.
   *
   * If serviceId is provided, then courier and serviceName are not mandatory.
   * If serviceId is not provided, then courier and serviceName are mandatory.
   *
   * @var serviceId
   */
  protected $serviceId;

  /**
   * courier.
   *
   * @var courier
   */
  protected $courier;

  /**
   * serviceName.
   *
   * @var serviceName
   */
  protected $serviceName;

  /**
   * shipmentPrice.
   *
   * @var shipmentPrice
   */
  protected $shipmentPrice;

  /**
   * shipmentCurrency.
   *
   * @var shipmentCurrency
   */
  protected $shipmentCurrency;

  /**
   * Determine if order is shipped over ShippingEasy or not.
   *
   * @var boolean
   */
  protected $shippingEasyService;

  /**
   * SeRecipient object.
   *
   * @var SeRecipient recipient
   */
  protected $recipient;

  /**
   * Array of SeOrderItem instances.
   *
   * @var array
   */
  protected $items=array();

  /**
   * Returns id.
   *
   * @param
   * @return id
   */
  public function getId()
  {
    return $this->id;
  }

  /**
   * Sets id.
   *
   * @param id
   * @return
   */
  public function setId($id)
  {
    $this->id = $id;
  }

  /**
   * Returns date.
   *
   * @param
   * @return date
   */
  public function getDate()
  {
    return $this->date;
  }

  /**
   * Sets date.
   *
   * @param date
   * @return
   */
  public function setDate($date)
  {
    $this->date = $date;
  }

  /**
   * Returns serviceId.
   *
   * @param
   * @return serviceId
   */
  public function getServiceId()
  {
    return $this->serviceId;
  }

  /**
   * Sets serviceId.
   *
   * @param serviceId
   * @return
   */
  public function setServiceId($serviceId)
  {
    $this->serviceId = $serviceId;
  }

  /**
   * Returns courier.
   *
   * @param
   * @return courier
   */
  public function getCourier()
  {
    return $this->courier;
  }

  /**
   * Sets courier.
   *
   * @param courier
   * @return
   */
  public function setCourier($courier)
  {
    $this->courier = $courier;
  }

  /**
   * Returns serviceName.
   *
   * @param
   * @return serviceName
   */
  public function getServiceName()
  {
    return $this->serviceName;
  }

  /**
   * Sets serviceName.
   *
   * @param serviceName
   * @return
   */
  public function setServiceName($serviceName)
  {
    $this->serviceName = $serviceName;
  }

  /**
   * Returns shipmentPrice.
   *
   * @param
   * @return shipmentPrice
   */
  public function getShipmentPrice()
  {
    return $this->shipmentPrice;
  }

  /**
   * Sets shipmentPrice.
   *
   * @param shipmentPrice
   * @return
   */
  public function setShipmentPrice($shipmentPrice)
  {
    $this->shipmentPrice = $shipmentPrice;
  }

  /**
   * Returns shipmentCurrency.
   *
   * @param
   * @return shipmentCurrency
   */
  public function getShipmentCurrency()
  {
    return $this->shipmentCurrency;
  }

  /**
   * Sets shipmentCurrency.
   *
   * @param shipmentCurrency
   * @return
   */
  public function setShipmentCurrency($shipmentCurrency)
  {
    $this->shipmentCurrency = $shipmentCurrency;
  }

  /**
   * Returns shippingEasyService.
   *
   * @param
   * @return shippingEasyService
   */
  public function getShippingEasyService()
  {
    return $this->shippingEasyService;
  }

  /**
   * Sets shippingEasyService.
   *
   * @param shippingEasyService
   * @return
   */
  public function setShippingEasyService($shippingEasyService)
  {
    $this->shippingEasyService = $shippingEasyService;
  }

  /**
   * Returns recipient.
   *
   * @param
   * @return recipient
   */
  public function getRecipient()
  {
    return $this->recipient;
  }

  /**
   * Sets recipient.
   *
   * @param recipient
   * @return
   */
  public function setRecipient($recipient)
  {
    $this->recipient = $recipient;
  }

  /**
   * Returns items.
   *
   * @param
   * @return items
   */
  public function getItems()
  {
    return $this->items;
  }

  /**
   * Sets array of SeOrderItem instances..
   *
   * @param items array
   * @return
   */
  public function setItems($items=array())
  {
    $this->items = $items;
  }

  /**
   * Sets single SeOrderItem instance.
   *
   * @param SeOrderItem item
   * @return
   */
  public function addItem($item)
  {
    array_push($this->items, $item);
  }

  /**
   * object's constructor
   *
   * @param $id, $date, $recipient, $items, $serviceId, $courier, $serviceName, $shipmentPrice,
   *        $shipmentCurrency, $shippingEasyService
   *
   * @return
   */
  public function __construct($id=null, $date=null, $recipient=null, $items=array(),
                              $serviceId=null, $courier=null, $serviceName=null,
                              $shipmentPrice=null, $shipmentCurrency=null,
                              $shippingEasyService=false)
  {
    $this->setId($id);
    $this->setDate($date);
    $this->setRecipient($recipient);
    $this->setItems($items);
    $this->setServiceId($serviceId);
    $this->setCourier($courier);
    $this->setServiceName($serviceName);
    $this->setShipmentPrice($shipmentPrice);
    $this->setShipmentCurrency($shipmentCurrency);
    $this->setShippingEasyService($shippingEasyService);
  }
}
?>