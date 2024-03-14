<?php

/**
 * PHP version 5.4 and 8
 *
 * @category  MessageEntity
 * @package   Payever\Payments
 * @author    payever GmbH <service@payever.de>
 * @copyright 2017-2023 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Payments\Http\MessageEntity;

use Payever\Sdk\Core\Base\MessageEntity;

/**
 * This class represents Customer Entity
 *
 * @method string getType()
 * @method string getGender()
 * @method string getCountry()
 * @method \DateTime|null getBirthdate()
 * @method string getPhone()
 * @method string getEmail()
 * @method string getSocialSecurityNumber()
 * @method self setType(string $value)
 * @method self setGender(string $value)
 * @method self setCountry(string $value)
 * @method self setPhone(string $value)
 * @method self setEmail(string $value)
 * @method self setSocialSecurityNumber(string $value)
 */
class CustomerEntity extends MessageEntity
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $gender;

    /**
     * @var \DateTime|null
     */
    protected $birthdate;

    /**
     * @var string
     */
    protected $phone;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $socialSecurityNumber;

    /**
     * {@inheritdoc}
     */
    public function getRequired()
    {
        return [
            'type',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function isValid()
    {
        return parent::isValid() && ($this->email || $this->phone) &&
            (!$this->birthdate || $this->birthdate instanceof \DateTime);
    }

    /**
     * Sets Birthdate
     *
     * @param string $birthdate
     *
     * @return $this
     */
    public function setBirthdate($birthdate)
    {
        if ($birthdate) {
            $this->birthdate = date_create($birthdate);
        }

        return $this;
    }
}
