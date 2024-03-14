<?php
/**
 * SeOrderItem.php.
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
 * @version    Release: SeOrderItem.v.0.1
 */
class SeOrderItem
{
  /**
   * id from shop system.
   *
   * @var integer
   */
  protected $id;

  /**
   * name.
   *
   * @var string
   */
  protected $name;

  /**
   * transactionId.
   *
   * @var integer
   */
  protected $transactionId;

  /**
   * quantity.
   *
   * @var integer
   */
  protected $quantity;

  /**
   * price.
   *
   * @var double
   */
  protected $price;

  /**
   * total. quantity * price.
   *
   * @var double
   */
  protected $total;

  /**
   * currency.
   *
   * @var string
   */
  protected $currency;

  /**
   * is item paid or not.
   *
   * @var boolean
   */
  protected $paid;

  /**
   * height.
   *
   * @var numeric
   */
  protected $height;

  /**
   * width.
   *
   * @var numeric
   */
  protected $width;

  /**
   * length.
   *
   * @var numeric
   */
  protected $length;

  /**
   * weight.
   *
   * @var numeric
   */
  protected $weight;

  /**
   * is metric or not.
   *
   * @var boolean
   */
  protected $isMetric;

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
   * Returns name.
   *
   * @param
   * @return name
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * Sets name.
   *
   * @param name
   * @return
   */
  public function setName($name)
  {
    $this->name = $name;
  }

  /**
   * Returns transactionId.
   *
   * @param
   * @return transactionId
   */
  public function getTransactionId()
  {
    return $this->transactionId;
  }

  /**
   * Sets transactionId.
   *
   * @param transactionId
   * @return
   */
  public function setTransactionId($transactionId)
  {
    $this->transactionId = $transactionId;
  }

  /**
   * Returns quantity.
   *
   * @param
   * @return quantity
   */
  public function getQuantity()
  {
    return $this->quantity;
  }

  /**
   * Sets quantity.
   *
   * @param quantity
   * @return
   */
  public function setQuantity($quantity)
  {
    $this->quantity = $quantity;
  }

  /**
   * Returns price.
   *
   * @param
   * @return price
   */
  public function getPrice()
  {
    return $this->price;
  }

  /**
   * Sets price.
   *
   * @param price
   * @return
   */
  public function setPrice($price)
  {
    $this->price = $price;
  }

  /**
   * Returns total.
   *
   * @param
   * @return total
   */
  public function getTotal()
  {
    return $this->total;
  }

  /**
   * Sets total.
   *
   * @param total
   * @return
   */
  public function setTotal($total)
  {
    $this->total = $total;
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
   * Returns paid.
   *
   * @param
   * @return paid
   */
  public function getPaid()
  {
    return $this->paid;
  }

  /**
   * Sets paid.
   *
   * @param paid
   * @return
   */
  public function setPaid($paid)
  {
    $this->paid = $paid;
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
   * Returns isMetric.
   *
   * @param
   * @return isMetric
   */
  public function getIsMetric()
  {
    return $this->isMetric;
  }

  /**
   * Sets isMetric.
   *
   * @param isMetric
   * @return
   */
  public function setIsMetric($isMetric)
  {
    $this->isMetric = $isMetric;
  }

  /**
   * object's constructor
   *
   * @param $id, $name, $transactionId, $quantity, $price, $total, $currency, $paid
   *
   * @return
   */
  public function __construct($id=null, $name=null, $transactionId=null, $quantity=null, $price=null,
                              $total=null, $currency=null, $paid=null, $height=null, $width=null,
                              $length=null, $weight=null, $isMetric=null)
  {
    $this->setId($id);
    $this->setName($name);
    $this->setTransactionId($transactionId);
    $this->setQuantity($quantity);
    $this->setPrice($price);
    $this->setTotal($total);
    $this->setCurrency($currency);
    $this->setPaid($paid);
    $this->setHeight($height);
    $this->setWidth($width);
    $this->setLength($length);
    $this->setWeight($weight);
    $this->setIsMetric($isMetric);
  }
}
?>