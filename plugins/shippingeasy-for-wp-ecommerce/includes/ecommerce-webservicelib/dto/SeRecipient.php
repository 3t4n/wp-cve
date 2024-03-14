<?php
/**
 * SeRecipient.php.
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
 * @version    Release: SeRecipient.v.0.1
 */
class SeRecipient
{
  /**
   * recipient id from shop system.
   *
   * @var integer
   */
  protected $id;

  /**
   * firstName from shop system.
   *
   * @var string
   */
  protected $firstName;

  /**
   * lastName from shop system.
   *
   * @var string
   */
  protected $lastName;

  /**
   * phone from shop system.
   *
   * @var string
   */
  protected $phone;

  /**
   * email from shop system.
   *
   * @var string
   */
  protected $email;

  /**
   * line1 from shop system.
   *
   * @var string
   */
  protected $line1;

  /**
   * line2 from shop system.
   *
   * @var string optional
   */
  protected $line2;

  /**
   * city from shop system.
   *
   * @var string
   */
  protected $city;

  /**
   * postal code from shop system.
   *
   * @var string
   */
  protected $postalCode;

  /**
   * state from shop system.
   *
   * @var string
   */
  protected $state;

  /**
   * country from shop system.
   *
   * @var string
   */
  protected $country;

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
   * Returns firstName.
   *
   * @param
   * @return firstName
   */
  public function getFirstName()
  {
    return $this->firstName;
  }

  /**
   * Sets firstName.
   *
   * @param firstName
   * @return
   */
  public function setFirstName($firstName)
  {
    $this->firstName = $firstName;
  }

  /**
   * Returns lastName.
   *
   * @param
   * @return lastName
   */
  public function getLastName()
  {
    return $this->lastName;
  }

  /**
   * Sets lastName.
   *
   * @param lastName
   * @return
   */
  public function setLastName($lastName)
  {
    $this->lastName = $lastName;
  }

  /**
   * Returns phone.
   *
   * @param
   * @return phone
   */
  public function getPhone()
  {
    return $this->phone;
  }

  /**
   * Sets phone.
   *
   * @param phone
   * @return
   */
  public function setPhone($phone)
  {
    $this->phone = $phone;
  }

  /**
   * Returns email.
   *
   * @param
   * @return email
   */
  public function getEmail()
  {
    return $this->email;
  }

  /**
   * Sets email.
   *
   * @param email
   * @return
   */
  public function setEmail($email)
  {
    $this->email = $email;
  }

  /**
   * Returns line1.
   *
   * @param
   * @return line1
   */
  public function getLine1()
  {
    return $this->line1;
  }

  /**
   * Sets line1.
   *
   * @param line1
   * @return
   */
  public function setLine1($line1)
  {
    $this->line1 = $line1;
  }

  /**
   * Returns line2.
   *
   * @param
   * @return line2
   */
  public function getLine2()
  {
    return $this->line2;
  }

  /**
   * Sets line2.
   *
   * @param line2
   * @return
   */
  public function setLine2($line2)
  {
    $this->line2 = $line2;
  }

  /**
   * Returns city.
   *
   * @param
   * @return city
   */
  public function getCity()
  {
    return $this->city;
  }

  /**
   * Sets city.
   *
   * @param city
   * @return
   */
  public function setCity($city)
  {
    $this->city = $city;
  }

  /**
   * Returns postalCode.
   *
   * @param
   * @return postalCode
   */
  public function getPostalCode()
  {
    return $this->postalCode;
  }

  /**
   * Sets postalCode.
   *
   * @param postalCode
   * @return
   */
  public function setPostalCode($postalCode)
  {
    $this->postalCode = $postalCode;
  }

  /**
   * Returns state.
   *
   * @param
   * @return state
   */
  public function getState()
  {
    return $this->state;
  }

  /**
   * Sets state.
   *
   * @param state
   * @return
   */
  public function setState($state)
  {
    $this->state = $state;
  }

  /**
   * Returns country.
   *
   * @param
   * @return country
   */
  public function getCountry()
  {
    return $this->country;
  }

  /**
   * Sets country.
   *
   * @param country
   * @return
   */
  public function setCountry($country)
  {
    $this->country = $country;
  }

  /**
   * object's constructor
   *
   * @param $id, $firstName, $lastName, $phone, $email, $line1, $line2, $city, $postalCode, $state, $country
   *
   * @return
   */
  public function __construct($id=null, $firstName=null, $lastName=null, $phone=null, $email=null,
                              $line1=null, $line2=null, $city=null, $postalCode=null, $state=null,
                              $country=null)
  {
    $this->setId($id);
    $this->setFirstName($firstName);
    $this->setLastName($lastName);
    $this->setPhone($phone);
    $this->setEmail($email);
    $this->setLine1($line1);
    $this->setLine2($line2);
    $this->setCity($city);
    $this->setPostalCode($postalCode);
    $this->setState($state);
    $this->setCountry($country);
  }
}
?>