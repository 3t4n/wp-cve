<?php
/**
 * SeShippingProductItem.php.
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
 * @version    Release: SeShippingProductItem.v.0.1
 */
class SeShippingProductItem
{
  /**
   * order product id from shop system.
   *
   * @var integer
   */
  protected $orderProductId;

  /**
   * product quantity from shop system.
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
   * @param $orderProductId, $quantity
   *
   * @return
   */
  public function __construct($orderProductId, $quantity)
  {
    $this->setOrderProductId($orderProductId);
    $this->setQuantity($quantity);
  }
}
?>