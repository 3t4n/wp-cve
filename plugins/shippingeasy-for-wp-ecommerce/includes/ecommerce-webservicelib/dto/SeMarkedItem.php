<?php
/**
 * SeMarkedItem.php.
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
 * @version    Release: SeMarkedItem.v.0.1
 */
class SeMarkedItem
{
  /**
   * OrderId from shop system.
   *
   * @var integer
   */
  protected $orderId;

  /**
   * Order Product ID from shop system.
   *
   * @var integer
   */
  protected $orderProductId;

  /**
   * Item quantity from shop system.
   *
   * @var integer
   */
  protected $quantity;

  /**
   * Returns orderProductId.
   *
   * @param
   * @return orderProductId
   */
  public function getOrderProductId()
  {
    return $this->orderProductId;
  }

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
   * Sets orderProductId.
   *
   * @param orderProductId
   * @return
   */
  public function setOrderProductId($orderProductId)
  {
    $this->orderProductId = $orderProductId;
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
   * object's constructor
   *
   * @param $orderId, $orderProductId, $quantity
   *
   * @return
   */
  public function __construct($orderId, $orderProductId, $quantity)
  {
    $this->setOrderId($orderId);
    $this->setOrderProductId($orderProductId);
    $this->setQuantity($quantity);
  }
}
?>