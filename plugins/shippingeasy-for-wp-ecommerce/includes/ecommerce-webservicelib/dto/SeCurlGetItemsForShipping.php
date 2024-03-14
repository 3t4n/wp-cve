<?php
/**
 * SeCurlGetItemsForShipping.php.
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
 * This class represents request result array.
 *
 * @package    ShippingEasy
 * @subpackage SeApi
 * @author     Saturized - The Interactive Agency <office@saturized.com>
 * @version    Release: SeCurlGetItemsForShipping.v.0.1
 */
class SeCurlGetItemsForShipping
{
  /**
   * Current UNIX timestamp.
   *
   * @var integer
   */
  protected $timeStamp;

  /**
   * Error message if exists.
   *
   * @var string
   */
  protected $errorMessage;

  /**
   * Array of SeOrder objects.
   *
   * @var array
   */
  protected $orders=array();

  /**
   * Returns timeStamp.
   *
   * @param
   * @return timeStamp
   */
  public function getTimeStamp()
  {
    return $this->timeStamp;
  }

  /**
   * Sets timeStamp.
   *
   * @param timeStamp
   * @return
   */
  public function setTimeStamp($timeStamp)
  {
    $this->timeStamp = $timeStamp;
  }

  /**
   * Returns errorMessage.
   *
   * @param
   * @return errorMessage
   */
  public function getErrorMessage()
  {
    return $this->errorMessage;
  }

  /**
   * Sets errorMessage.
   *
   * @param errorMessage
   * @return
   */
  public function setErrorMessage($errorMessage)
  {
    $this->errorMessage = $errorMessage;
  }

  /**
   * Returns orders.
   *
   * @param
   * @return orders
   */
  public function getOrders()
  {
    return $this->orders;
  }

  /**
   * Sets array of SeOrder instances.
   *
   * @param orders array
   * @return
   */
  public function setOrders($orders)
  {
    $this->orders = $orders;
  }

  /**
   * Sets single SeOrderItem instance.
   *
   * @param SeOrder order
   * @return
   */
  public function addOrder($order)
  {
    array_push($this->orders, $order);
  }

  /**
   * object's constructor
   *
   * @param $timeStamp, $errorMessage, $orders
   *
   * @return
   */
  public function __construct($timeStamp=null, $errorMessage=null, $orders=array())
  {
    $this->setTimeStamp($timeStamp);
    $this->setErrorMessage($errorMessage);
    $this->setOrders($orders);
  }
}
?>