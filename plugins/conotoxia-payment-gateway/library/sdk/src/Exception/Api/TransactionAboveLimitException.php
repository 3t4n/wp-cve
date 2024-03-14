<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Api;

use CKPL\Pay\Exception\Http\HttpConflictException;

/**
 * Class TransactionAboveLimitException.
 *
 * @package CKPL\Pay\Exception\Api
 */
class TransactionAboveLimitException extends HttpConflictException implements ApiExceptionInterface
{
    /**
     * @type string
     */
    const TYPE = 'transaction-above-limit';

    protected $messages;

    public function __construct(string $detail = null, string $limitType = null, array $money = null, array $limit = null)
    {
        parent::__construct($detail);

        switch ($limitType) {
            case 'POINT_OF_SALE':
                $this->messages = [
                    'pl' => sprintf('Kwota transakcji %s %s przekroczyła limit punktu płatności wynoszący %s %s. Skontaktuj się z działem sprzedaży',
                        $money['value'],
                        $money['currency'],
                        $limit['value'],
                        $limit['currency']),
                    'en' => $detail . '. Please contact our sales department.'
                ];
                break;
            case 'PAYMENT_METHOD':
                $this->messages = [
                    'pl' => sprintf('Dla wybranej metody płatności kwota transakcji %s %s przekroczyła limit %s %s.',
                        $money['value'],
                        $money['currency'],
                        $limit['value'],
                        $limit['currency']),
                    'en' => $detail
                ];
                break;
            default:
                $this->messages = [
                    'pl' => 'Podana kwota płatności znajduje się powyżej określonego limitu.',
                    'en' => $detail
                ];
                break;
        }
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE;
    }
}
