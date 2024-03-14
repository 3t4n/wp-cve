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
 * This class represents Company Identifier Result Entity
 *
 * @method string        getIdValue()
 * @method string        getIdTypeCode()
 * @method bool          getIsPrincipal()
 * @method self          setIdValue(string $value)
 * @method self          setIdTypeCode(string $value)
 * @method self          setIsPrincipal(bool $value)
 */
class CompanyIdentifierEntity extends MessageEntity
{
    /**
     * @var string
     */
    protected $idValue;

    /**
     * @var string
     */
    protected $idTypeCode;

    /**
     * @var bool
     */
    protected $isPrincipal;
}
