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
 * This class represents Shipping Address Entity
 *
 * @method string getSalutation()
 * @method float  getFirstName()
 * @method float  getLastName()
 * @method string getOrganizationName()
 * @method float  getStreet()
 * @method float  getStreetNumber()
 * @method string getZip()
 * @method string getCountry()
 * @method string getCity()
 * @method string getRegion()
 * @method string getStreetLine2()
 * @method self   setSalutation(string $salutation)
 * @method self   setFirstName(string $firstName)
 * @method self   setLastName(string $lastName)
 * @method self   setOrganizationName(string $value)
 * @method self   setStreet(string $street)
 * @method self   setStreetNumber(string $streetNumber)
 * @method self   setZip(string $zip)
 * @method self   setCountry(string $country)
 * @method self   setCity(string $city)
 * @method self   setRegion(string $region)
 * @method self   setStreetLine2(string $addressLine2)
 */
class CustomerAddressV3Entity extends MessageEntity
{
    /**
     * @var string
     */
    protected $salutation;

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $organizationName;

    /**
     * @var string
     */
    protected $street;

    /**
     * @var string
     */
    protected $streetNumber;

    /**
     * @var string
     */
    protected $zip;

    /**
     * @var string
     */
    protected $country;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $streetLine2;

    /**
     * @var string
     */
    protected $streetName;

    /**
     * @var string
     */
    protected $houseExtension;

    /**
     * {@inheritdoc}
     */
    public function getRequired()
    {
        return [
            'first_name',
            'last_name',
            'street_name',
            'street_number',
            'city',
            'zip',
            'country'
        ];
    }
}
