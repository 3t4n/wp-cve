<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Api\Strategy;

use CKPL\Pay\Definition\Payload\PayloadInterface;
use CKPL\Pay\Exception\Api\ApiExceptionInterface;
use CKPL\Pay\Exception\Api\ContractCategoryNotSupportedException;
use CKPL\Pay\Exception\Api\CurrencyUnavailableException;
use CKPL\Pay\Exception\Api\InvalidPemException;
use CKPL\Pay\Exception\Api\MaxRefundsReachedException;
use CKPL\Pay\Exception\Api\OtherRefundsNotCompletedException;
use CKPL\Pay\Exception\Api\PaymentMethodNotAvailableException;
use CKPL\Pay\Exception\Api\TransactionAboveLimitException;
use CKPL\Pay\Exception\Api\TransactionBelowLimitException;
use CKPL\Pay\Exception\Api\PaymentNotBookedException;
use CKPL\Pay\Exception\Api\PointOfSaleCurrencyNotSupportedException;
use CKPL\Pay\Exception\Api\PointOfSaleForbiddenErrorUrlException;
use CKPL\Pay\Exception\Api\PointOfSaleForbiddenNotificationUrlException;
use CKPL\Pay\Exception\Api\PointOfSaleForbiddenReturnUrlException;
use CKPL\Pay\Exception\Api\PointOfSaleNotActiveException;
use CKPL\Pay\Exception\Api\PointOfSaleNotFoundException;
use CKPL\Pay\Exception\Api\PublicKeyAlreadyExistException;
use CKPL\Pay\Exception\Api\PublicKeyAlreadyRevoked;
use CKPL\Pay\Exception\Api\PublicKeyIsNotActivatedException;
use CKPL\Pay\Exception\Api\RefundAmountTooLargeException;
use CKPL\Pay\Exception\Api\RefundAmountTooSmallException;
use CKPL\Pay\Exception\Api\RefundIncorrectCurrencyCodeException;
use CKPL\Pay\Exception\Api\RefundNotAllowedException;
use CKPL\Pay\Exception\Api\StoreNotFoundException;
use CKPL\Pay\Exception\Api\UnknownApiErrorException;
use CKPL\Pay\Exception\Api\ValidationErrorException;
use CKPL\Pay\Exception\PayloadException;

/**
 * Class ApiExceptionStrategy.
 *
 * @package CKPL\Pay\Exception\Api\Strategy
 */
class ApiExceptionStrategy implements ApiExceptionStrategyInterface
{
    /**
     * @var string|null
     */
    protected $type;
    /**
     * @var PayloadInterface
     */
    protected $payload;

    /**
     * ApiExceptionStrategy constructor.
     *
     * @param PayloadInterface $payload
     *
     * @throws PayloadException
     */
    public function __construct(PayloadInterface $payload)
    {
        if ($payload->hasElement('type')) {
            $this->type = $payload->expectStringOrNull('type');
        }

        $this->payload = $payload;
    }

    /**
     * @return bool
     */
    public function isApi(): bool
    {
        return null !== $this->type;
    }

    /**
     * @return ApiExceptionInterface
     * @throws PayloadException
     *
     */
    public function getException(): ApiExceptionInterface
    {
        $title = $this->payload->hasElement('title') ? $this->payload->expectStringOrNull('title') : 'Error';
        $detail = $this->payload->hasElement('detail') ? $this->payload->expectStringOrNull('detail') : 'UNKNOWN';

        switch ($this->type) {
            case InvalidPemException::TYPE:
                $exception = new InvalidPemException($title, $detail);
                break;
            case PublicKeyAlreadyRevoked::TYPE:
                $exception = new PublicKeyAlreadyRevoked($title, $detail);
                break;
            case PublicKeyAlreadyExistException::TYPE:
                $exception = new PublicKeyAlreadyExistException($title, $detail, $this->payload->expectStringOrNull('kid'));
                break;
            case ValidationErrorException::TYPE:
                $exception = new ValidationErrorException($title, $detail);
                $validationCollection = $exception->createValidationCollection();

                foreach ($this->payload->expectArrayOrNull('validation-errors') as $error) {
                    $validationCollection->addError($error['message-key'], $error['context-key'], $error['message'], $error['params']);
                }

                break;
            case PointOfSaleNotFoundException::TYPE:
                $exception = new PointOfSaleNotFoundException($title, $detail);
                break;
            case StoreNotFoundException::TYPE:
                $exception = new StoreNotFoundException($title, $detail);
                break;
            case ContractCategoryNotSupportedException::TYPE:
                $exception = new ContractCategoryNotSupportedException($title, $detail);
                break;
            case PaymentMethodNotAvailableException::TYPE:
                $exception = new PaymentMethodNotAvailableException($detail);
                break;
            case TransactionBelowLimitException::TYPE:
                $limitType = $this->payload->hasElement('limitType') ? $this->payload->expectStringOrNull('limitType') : null;
                $money = $this->payload->hasElement('money') ? $this->payload->expectArrayOrNull('money') : null;
                $limit = $this->payload->hasElement('limit') ? $this->payload->expectArrayOrNull('limit') : null;
                $exception = new TransactionBelowLimitException($detail, $limitType, $money, $limit);
                break;
            case TransactionAboveLimitException::TYPE:
                $limitType = $this->payload->hasElement('limitType') ? $this->payload->expectStringOrNull('limitType') : null;
                $money = $this->payload->hasElement('money') ? $this->payload->expectArrayOrNull('money') : null;
                $limit = $this->payload->hasElement('limit') ? $this->payload->expectArrayOrNull('limit') : null;
                $exception = new TransactionAboveLimitException($detail, $limitType, $money, $limit);
                break;
            case PaymentNotBookedException::TYPE:
                $exception = new PaymentNotBookedException($title, $detail);
                break;
            case PointOfSaleCurrencyNotSupportedException::TYPE:
                $exception = new PointOfSaleCurrencyNotSupportedException($title, $detail);
                break;
            case PointOfSaleForbiddenErrorUrlException::TYPE:
                $exception = new PointOfSaleForbiddenErrorUrlException($title, $detail);
                break;
            case PointOfSaleForbiddenNotificationUrlException::TYPE:
                $exception = new PointOfSaleForbiddenNotificationUrlException($title, $detail);
                break;
            case PointOfSaleForbiddenReturnUrlException::TYPE:
                $exception = new PointOfSaleForbiddenReturnUrlException($title, $detail);
                break;
            case PointOfSaleNotActiveException::TYPE:
                $exception = new PointOfSaleNotActiveException($title, $detail);
                break;
            case RefundAmountTooLargeException::TYPE:
                $exception = new RefundAmountTooLargeException($title, $detail);
                break;
            case RefundAmountTooSmallException::TYPE:
                $limit = $this->payload->hasElement('limit') ? $this->payload->expectArrayOrNull('limit') : null;
                $exception = new RefundAmountTooSmallException($title, $limit);
                break;
            case RefundNotAllowedException::TYPE:
                $exception = new RefundNotAllowedException($title, $detail);
                break;
            case RefundIncorrectCurrencyCodeException::TYPE:
                $exception = new RefundIncorrectCurrencyCodeException($title, $detail);
                break;
            case OtherRefundsNotCompletedException::TYPE:
                $exception = new OtherRefundsNotCompletedException($title, $detail);
                break;
            case MaxRefundsReachedException::TYPE:
                $exception = new MaxRefundsReachedException($title, $detail);
                break;
            case PublicKeyIsNotActivatedException::TYPE:
                $exception = new PublicKeyIsNotActivatedException($title, $detail);
                break;
            case CurrencyUnavailableException::TYPE:
                $exception = new CurrencyUnavailableException($title, $detail);
                break;
            default:
                $exception = new UnknownApiErrorException('Unknown API exception type', $this->type);
                break;
        }

        return $exception;
    }
}
