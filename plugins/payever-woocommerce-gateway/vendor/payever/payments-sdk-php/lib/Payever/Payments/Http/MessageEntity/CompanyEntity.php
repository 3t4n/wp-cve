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
 * This class represents Company entity
 *
 * @method string getType()
 * @method string getName()
 * @method string getRegistrationNumber()
 * @method string getRegistrationLocation()
 * @method string getTaxId()
 * @method string getHomepage()
 * @method string getExternalId()
 * @method self setType(string $value)
 * @method self setName(string $value)
 * @method self setRegistrationNumber(string $value)
 * @method self setRegistrationLocation(string $value)
 * @method self setTaxId(string $value)
 * @method self setHomepage(string $value)
 * @method self setExternalId(string $value)
 */
class CompanyEntity extends MessageEntity
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $registrationNumber;

    /**
     * @var string
     */
    protected $registrationLocation;

    /**
     * @var string
     */
    protected $taxId;

    /**
     * @var string
     */
    protected $homepage;

    /**
     * @var string
     */
    protected $externalId;

    /**
     * {@inheritdoc}
     */
    public function getRequired()
    {
        return [
            'name'
        ];
    }
}
