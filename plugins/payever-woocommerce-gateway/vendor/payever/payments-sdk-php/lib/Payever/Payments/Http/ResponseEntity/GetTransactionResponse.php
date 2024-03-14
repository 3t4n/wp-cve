<?php

/**
 * PHP version 5.4 and 8
 *
 * @category  ResponseEntity
 * @package   Payever\Payments
 * @author    payever GmbH <service@payever.de>
 * @copyright 2017-2023 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Payments\Http\ResponseEntity;

use Payever\Sdk\Core\Http\ResponseEntity;
use Payever\Sdk\Payments\Http\MessageEntity\GetTransactionResultEntity;

/**
 * This class represents Get Transaction ResponseInterface Entity
 */
class GetTransactionResponse extends ResponseEntity
{
    /**
     * {@inheritdoc}
     */
    public function load($data)
    {
        if (!is_array($data) || !isset($data['result'])) {
            $data = ['result' => $data];
        }

        return parent::load($data);
    }

    /**
     * {@inheritdoc}
     */
    public function setResult($result)
    {
        $this->result = new GetTransactionResultEntity($result);
    }
}
