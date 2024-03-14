<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\Transaction;

use WPPayVendor\BlueMedia\Common\Dto\AbstractDto;
use WPPayVendor\BlueMedia\Common\Enum\ClientEnum;
use WPPayVendor\BlueMedia\Common\Util\Translations;
final class View
{
    public static function createRedirectHtml(\WPPayVendor\BlueMedia\Common\Dto\AbstractDto $transactionDto) : string
    {
        $translation = (new \WPPayVendor\BlueMedia\Common\Util\Translations())->getTranslation($transactionDto->getHtmlFormLanguage());
        $result = '<p>' . $translation[\WPPayVendor\BlueMedia\Common\Util\Translations::REDIRECT] . '</p>' . \PHP_EOL;
        $result .= \sprintf('<form action="%s" method="post" id="BlueMediaPaymentForm" name="BlueMediaPaymentForm">', $transactionDto->getGatewayUrl() . \WPPayVendor\BlueMedia\Common\Enum\ClientEnum::PAYMENT_ROUTE) . \PHP_EOL;
        foreach ($transactionDto->getTransaction()->capitalizedArray() as $fieldName => $fieldValue) {
            if (empty($fieldValue)) {
                continue;
            }
            $result .= \sprintf('<input type="hidden" name="%s" value="%s" />', $fieldName, $fieldValue) . \PHP_EOL;
        }
        $result .= '<input type="submit" />' . \PHP_EOL;
        $result .= '</form>' . \PHP_EOL;
        $result .= '<script type="text/javascript">document.BlueMediaPaymentForm.submit();</script>';
        $result .= '<noscript><p>' . $translation[\WPPayVendor\BlueMedia\Common\Util\Translations::JAVASCRIPT_DISABLED] . '<br>';
        return $result . $translation[\WPPayVendor\BlueMedia\Common\Util\Translations::JAVASCRIPT_REQUIRED] . '</p></noscript>' . \PHP_EOL;
    }
}
