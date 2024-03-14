<?php

/**
 * PHP version 5.4 and 8
 *
 * @category  RequestEntity
 * @package   Payever\Payments
 * @author    payever GmbH <service@payever.de>
 * @copyright 2017-2023 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Payments\Http\RequestEntity;

use Payever\Sdk\Core\Http\RequestEntity;

/**
 * This class represents Claim Context Info RequestInterface Entity
 *
 * @method bool getIsInsolvencyProceeding()
 * @method self setIsInsolvencyProceeding(bool $isInsolvencyProceeding)
 * @method bool getIsInvoiceDisputed()
 * @method self setIsInvoiceDisputed(bool $isInvoiceDisputed)
 */
class ClaimContextInfoEntity extends RequestEntity
{
    /** @var bool $isInsolvencyProceeding */
    protected $isInsolvencyProceeding = false;

    /** @var bool $isInvoiceDisputed */
    protected $isInvoiceDisputed = false;

    /**
     * {@inheritdoc}
     */
    public function isValid()
    {
        return parent::isValid() && is_bool($this->isInsolvencyProceeding) && is_bool($this->isInvoiceDisputed);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray($object = null)
    {
        return $object ? get_object_vars($object) : get_object_vars($this);
    }
}
