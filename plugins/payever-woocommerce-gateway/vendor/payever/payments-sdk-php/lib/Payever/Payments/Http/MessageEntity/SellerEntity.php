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
 * This class represents Seller entity
 *
 * @method string getId()
 * @method string getFirstName()
 * @method string getLastName()
 * @method string getEmail()
 * @method self setId(string $value)
 * @method self setFirstName(string $value)
 * @method self setLastName(string $value)
 * @method self setEmail(string $value)
 */
class SellerEntity extends MessageEntity
{
    /**
     * @var string
     */
    protected $id;

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
    protected $email;
}
