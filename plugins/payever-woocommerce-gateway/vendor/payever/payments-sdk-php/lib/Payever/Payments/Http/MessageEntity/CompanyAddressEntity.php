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
 * This class represents Company Address Result Entity
 *
 * @method string        getStreetNumber()
 * @method string        getStreetName()
 * @method string        getPostCode()
 * @method string        getCity()
 * @method string        getStateCode()
 * @method string        getCountryCode()
 * @method string        getType()
 * @method self          setStreetNumber(string $value)
 * @method self          setStreetName(string $value)
 * @method self          setPostCode(string $value)
 * @method self          setCity(string $value)
 * @method self          setStateCode(string $value)
 * @method self          setCountryCode(string $value)
 * @method self          setType(string $value)
 */
class CompanyAddressEntity extends MessageEntity
{
    /**
     * @var string
     */
    protected $streetNumber;

    /**
     * @var string
     */
    protected $streetName;

    /**
     * @var string
     */
    protected $postCode;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $stateCode;

    /**
     * @var string
     */
    protected $countryCode;

    /**
     * @var string
     */
    protected $type;
}
