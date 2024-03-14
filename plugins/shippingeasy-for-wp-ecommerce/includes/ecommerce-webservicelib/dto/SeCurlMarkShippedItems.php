<?php
/**
 * SeCurlMarkShippedItems.php.
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
 * @version    Release: SeCurlMarkShippedItems.v.0.1
 */
class SeCurlMarkShippedItems
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
   * Array of SeMarkedItem objects.
   *
   * @var array
   */
  protected $markedItems=array();

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
   * Returns markedItems.
   *
   * @param
   * @return markedItems
   */
  public function getMarkedItems()
  {
    return $this->markedItems;
  }

  /**
   * Sets array of SeMarkedItem instances.
   *
   * @param markedItems array
   * @return
   */
  public function setMarkedItems($markedItems)
  {
    $this->markedItems = $markedItems;
  }

  /**
   * Sets single SeMarkedItem instance.
   *
   * @param SeMarkedItem markedItem
   * @return
   */
  public function addMarkedItem($markedItem)
  {
    array_push($this->markedItems, $markedItem);
  }

  /**
   * object's constructor
   *
   * @param $timeStamp, $errorMessage, $markedItems
   *
   * @return
   */
  public function __construct($timeStamp=null, $errorMessage=null, $markedItems=array())
  {
    $this->setTimeStamp($timeStamp);
    $this->setErrorMessage($errorMessage);
    $this->setMarkedItems($markedItems);
  }
}
?>