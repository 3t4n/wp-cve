<?php
/**
 * SeShippingItem.php.
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
 * This class represents one item in sent JSON request array.
 *
 * @package    ShippingEasy
 * @subpackage SeApi
 * @author     Saturized - The Interactive Agency <office@saturized.com>
 * @version    Release: SeShippingItem.v.0.1
 */
class SeShippingItem
{
  /**
   * order id from shop system.
   *
   * @var integer
   */
  protected $orderId;

  /**
   * date created from shop system.
   *
   * @var UNIX timestamp
   */
  protected $created;

  /**
   * tracking number.
   *
   * @var string
   */
  protected $trackingNumber;

  /**
   * shipping method.
   *
   * @var string
   */
  protected $shippingMethod;

  /**
   * courier service id.
   *
   * @var integer
   */
  protected $serviceId;

  /**
   * courier name.
   *
   * @var string
   */
  protected $courierName;

  /**
   * ship date.
   *
   * @var UNIX timestamp
   */
  protected $shipDate;

  /**
   * expected delivery of the shipment.
   *
   * @var UNIX timestamp
   */
  protected $expectedDelivery;

  /**
   * cost of the shipment.
   *
   * @var double
   */
  protected $cost;

  protected $currency;

  /**
   * length of the package.
   *
   * @var double
   */
  protected $length;

  /**
   * width of the package.
   *
   * @var double
   */
  protected $width;

  /**
   * height of the package.
   *
   * @var double
   */
  protected $height;

  /**
   * package dimensions length units.
   *
   * @var string
   */
  protected $lengthUnits;

  /**
   * weigth of the package.
   *
   * @var double
   */
  protected $weight;

  /**
   * package dimensions weight units.
   *
   * @var string
   */
  protected $weightUnits;

  /**
   * array of SeShippingProductItem instances.
   *
   * @var array
   */
  protected $products=array();

  /**
   * Returns orderId.
   *
   * @param
   * @return orderId
   */
  public function getOrderId()
  {
    return $this->orderId;
  }

  /**
   * Sets orderId.
   *
   * @param orderId
   * @return
   */
  public function setOrderId($orderId)
  {
    $this->orderId = $orderId;
  }

  /**
   * Returns created.
   *
   * @param
   * @return created
   */
  public function getCreated()
  {
    return $this->created;
  }

  /**
   * Sets created.
   *
   * @param created
   * @return
   */
  public function setCreated($created)
  {
    $this->created = $created;
  }

  /**
   * Returns trackingNumber.
   *
   * @param
   * @return trackingNumber
   */
  public function getTrackingNumber()
  {
    return $this->trackingNumber;
  }

  /**
   * Sets trackingNumber.
   *
   * @param trackingNumber
   * @return
   */
  public function setTrackingNumber($trackingNumber)
  {
    $this->trackingNumber = $trackingNumber;
  }

  /**
   * Returns shippingMethod.
   *
   * @param
   * @return shippingMethod
   */
  public function getShippingMethod()
  {
    return $this->shippingMethod;
  }

  /**
   * Sets shippingMethod.
   *
   * @param shippingMethod
   * @return
   */
  public function setShippingMethod($shippingMethod)
  {
    $this->shippingMethod = $shippingMethod;
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
   * Returns courierName.
   *
   * @param
   * @return courierName
   */
  public function getCourierName()
  {
    return $this->courierName;
  }

  /**
   * Sets courierName.
   *
   * @param courierName
   * @return
   */
  public function setCourierName($courierName)
  {
    $this->courierName = $courierName;
  }

  /**
   * Returns shipDate.
   *
   * @param
   * @return shipDate
   */
  public function getShipDate()
  {
    return $this->shipDate;
  }

  /**
   * Sets shipDate.
   *
   * @param shipDate
   * @return
   */
  public function setShipDate($shipDate)
  {
    $this->shipDate = $shipDate;
  }

  /**
   * Returns expectedDelivery.
   *
   * @param
   * @return expectedDelivery
   */
  public function getExpectedDelivery()
  {
    return $this->expectedDelivery;
  }

  /**
   * Sets expectedDelivery.
   *
   * @param expectedDelivery
   * @return
   */
  public function setExpectedDelivery($expectedDelivery)
  {
    $this->expectedDelivery = $expectedDelivery;
  }

  /**
   * Returns cost.
   *
   * @param
   * @return cost
   */
  public function getCost()
  {
    return $this->cost;
  }

  /**
   * Sets cost.
   *
   * @param cost
   * @return
   */
  public function setCost($cost)
  {
    $this->cost = $cost;
  }

  /**
   * Returns currency.
   *
   * @param
   * @return currency
   */
  public function getCurrency()
  {
    return $this->currency;
  }

  /**
   * Sets currency.
   *
   * @param currency
   * @return
   */
  public function setCurrency($currency)
  {
    $this->currency = $currency;
  }

  /**
   * Returns length.
   *
   * @param
   * @return length
   */
  public function getLength()
  {
    return $this->length;
  }

  /**
   * Sets length.
   *
   * @param length
   * @return
   */
  public function setLength($length)
  {
    $this->length = $length;
  }

  /**
   * Returns width.
   *
   * @param
   * @return width
   */
  public function getWidth()
  {
    return $this->width;
  }

  /**
   * Sets width.
   *
   * @param width
   * @return
   */
  public function setWidth($width)
  {
    $this->width = $width;
  }

  /**
   * Returns height.
   *
   * @param
   * @return height
   */
  public function getHeight()
  {
    return $this->height;
  }

  /**
   * Sets height.
   *
   * @param height
   * @return
   */
  public function setHeight($height)
  {
    $this->height = $height;
  }

  /**
   * Returns lengthUnit.
   *
   * @param
   * @return lenghtUnit
   */
  public function getLengthUnit()
  {
    return $this->lengthUnit;
  }

  /**
   * Sets lengthUnit.
   *
   * @param lengthUnit
   * @return
   */
  public function setLengthUnit($lengthUnit)
  {
    $this->lengthUnit = $lengthUnit;
  }

  /**
   * Returns weight.
   *
   * @param
   * @return weight
   */
  public function getWeight()
  {
    return $this->weight;
  }

  /**
   * Sets weight.
   *
   * @param weight
   * @return
   */
  public function setWeight($weight)
  {
    $this->weight = $weight;
  }

  /**
   * Returns weightUnit.
   *
   * @param
   * @return weightUnit
   */
  public function getWeightUnit()
  {
    return $this->weightUnit;
  }

  /**
   * Sets weightUnit.
   *
   * @param weightUnit
   * @return
   */
  public function setWeightUnit($weightUnit)
  {
    $this->weightUnit = $weightUnit;
  }

  /**
   * Returns products.
   *
   * @param
   * @return products
   */
  public function getProducts()
  {
    return $this->products;
  }

  /**
   * Sets array of SeShippingProductItem instances..
   *
   * @param products array
   * @return
   */
  public function setProducts($products=array())
  {
    $this->products = $products;
  }

  /**
   * Sets single SeShippingProductItem instance.
   *
   * @param SeShippingProductItem product
   * @return
   */
  public function addProduct($product)
  {
    array_push($this->products, $product);
  }

  /**
   * object's constructor
   *
   * @param $orderId, $created, $trackingNumber, $shippingMethod, $serviceId, $courierName, $shipDate,
   *        $expectedDelivery, $cost, $currency, $products, $length, $width, $height, $weight, $lengthUnit,
   *        $weightUnit
   *
   * @return
   */
  public function __construct($orderId, $created, $trackingNumber, $shippingMethod=null, $serviceId=null,
                              $courierName=null, $shipDate=null, $expectedDelivery=null,
                              $cost=null, $products=array(), $currency=null, $length=null, $width=null,
                              $height=null, $weight=null, $lengthUnit=null, $weightUnit=null)
  {
    $this->setOrderId($orderId);
    $this->setCreated($created);
    $this->setTrackingNumber($trackingNumber);
    $this->setShippingMethod($shippingMethod);
    $this->setServiceId($serviceId);
    $this->setCourierName($courierName);
    $this->setShipDate($shipDate);
    $this->setExpectedDelivery($expectedDelivery);
    $this->setCost($cost);
    $this->setCurrency($currency);
    $this->setProducts($products);
    $this->setLength($length);
    $this->setWidth($width);
    $this->setHeight($height);
    $this->setWeight($weight);
    $this->setLengthUnit($lengthUnit);
    $this->setWeightUnit($weightUnit);
  }
}
?>