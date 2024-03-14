<?php

/**
 * PHP version 5.4 and 8
 *
 * @category  ResponseEntity
 * @package   Payever\Payments
 * @author    payever GmbH <service@payever.de>
 * @author    Andrey Puhovsky <a.puhovsky@gmail.com>
 * @copyright 2017-2023 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Payments\Http\ResponseEntity;

use Payever\Sdk\Core\Http\ResponseEntity;
use Payever\Sdk\Payments\Http\MessageEntity\SubmitPaymentResultEntity;

/**
 * This class represents Submit Payment ResponseInterface Entity
 */
class SubmitPaymentResponse extends ResponseEntity
{
    /**
     * {@inheritdoc}
     */
    public function setResult($result)
    {
        $this->result = new SubmitPaymentResultEntity($result);
    }
}
