<?php

/**
 * PHP version 5.4 and 8
 *
 * @category  Converter
 * @package   Payever\Payments
 * @author    payever GmbH <service@payever.de>
 * @author    Hennadii.Shymanskyi <gendosua@gmail.com>
 * @copyright 2017-2023 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Payments\Converter;

use Payever\Sdk\Payments\Http\MessageEntity\ConvertedPaymentOptionEntity;
use Payever\Sdk\Payments\Http\MessageEntity\ListPaymentOptionsVariantsResultEntity;

class PaymentOptionConverter
{
    /**
     * @param array|ListPaymentOptionsVariantsResultEntity[] $poWithVariants
     *
     * @return array|ConvertedPaymentOptionEntity[]
     */
    public static function convertPaymentOptionVariants(array $poWithVariants)
    {
        $result = [];

        foreach ($poWithVariants as $poWithVariant) {
            $result = array_merge($result, $poWithVariant->toConvertedPaymentOptions());
        }

        return $result;
    }
}
