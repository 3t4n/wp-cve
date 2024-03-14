<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\Transaction\Parser;

use WPPayVendor\BlueMedia\Common\Enum\ClientEnum;
use WPPayVendor\BlueMedia\Common\Exception\HashException;
use WPPayVendor\BlueMedia\Common\Parser\ResponseParser;
use WPPayVendor\BlueMedia\Common\Util\XMLParser;
use WPPayVendor\BlueMedia\Hash\HashChecker;
use WPPayVendor\BlueMedia\HttpClient\ValueObject\Response;
use WPPayVendor\BlueMedia\Serializer\SerializableInterface;
use WPPayVendor\BlueMedia\Serializer\Serializer;
use WPPayVendor\BlueMedia\Transaction\ValueObject\TransactionBackground;
use WPPayVendor\BlueMedia\Transaction\ValueObject\TransactionContinue;
use WPPayVendor\BlueMedia\Transaction\ValueObject\TransactionInit;
final class TransactionResponseParser extends \WPPayVendor\BlueMedia\Common\Parser\ResponseParser
{
    public function parse(bool $transactionInit = \false) : \WPPayVendor\BlueMedia\HttpClient\ValueObject\Response
    {
        $this->isErrorResponse();
        $paywayForm = $this->getPaywayFormResponse();
        if (!empty($paywayForm)) {
            return new \WPPayVendor\BlueMedia\HttpClient\ValueObject\Response(\htmlspecialchars_decode($paywayForm['1']['0']));
        }
        if ($transactionInit === \true) {
            return new \WPPayVendor\BlueMedia\HttpClient\ValueObject\Response($this->parseTransactionInitResponse());
        }
        return new \WPPayVendor\BlueMedia\HttpClient\ValueObject\Response($this->parseTransactionBackgroundResponse());
    }
    private function getPaywayFormResponse() : array
    {
        $matchesCount = \preg_match_all(\WPPayVendor\BlueMedia\Common\Enum\ClientEnum::PATTERN_PAYWAY, $this->response, $data);
        return $matchesCount === 0 ? [] : $data;
    }
    private function parseTransactionBackgroundResponse() : \WPPayVendor\BlueMedia\Serializer\SerializableInterface
    {
        /** @var TransactionBackground $transaction */
        $transaction = (new \WPPayVendor\BlueMedia\Serializer\Serializer())->deserializeXml($this->response, \WPPayVendor\BlueMedia\Transaction\ValueObject\TransactionBackground::class);
        if (\WPPayVendor\BlueMedia\Hash\HashChecker::checkHash($transaction, $this->configuration) === \false) {
            throw \WPPayVendor\BlueMedia\Common\Exception\HashException::wrongHashError();
        }
        return $transaction;
    }
    private function parseTransactionInitResponse() : \WPPayVendor\BlueMedia\Serializer\SerializableInterface
    {
        $xmlTransaction = \WPPayVendor\BlueMedia\Common\Util\XMLParser::parse($this->response);
        if (isset($xmlTransaction->redirecturl)) {
            /** @var TransactionContinue $transaction */
            $transaction = (new \WPPayVendor\BlueMedia\Serializer\Serializer())->deserializeXml($this->response, \WPPayVendor\BlueMedia\Transaction\ValueObject\TransactionContinue::class);
        } else {
            /** @var TransactionInit $transaction */
            $transaction = (new \WPPayVendor\BlueMedia\Serializer\Serializer())->deserializeXml($this->response, \WPPayVendor\BlueMedia\Transaction\ValueObject\TransactionInit::class);
        }
        if (\WPPayVendor\BlueMedia\Hash\HashChecker::checkHash($transaction, $this->configuration) === \false) {
            throw \WPPayVendor\BlueMedia\Common\Exception\HashException::wrongHashError();
        }
        return $transaction;
    }
}
