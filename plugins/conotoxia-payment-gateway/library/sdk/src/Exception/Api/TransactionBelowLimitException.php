<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Api;

use CKPL\Pay\Exception\Http\HttpConflictException;

/**
 * Class TransactionBelowLimitException.
 *
 * @package CKPL\Pay\Exception\Api
 */
class TransactionBelowLimitException extends HttpConflictException implements ApiExceptionInterface
{
    /**
     * @type string
     */
    const TYPE = 'transaction-below-limit';

    protected $messages;

    public function __construct(string $detail = null, string $limitType = null, array $money = null, array $limit = null)
    {
        parent::__construct($detail);

        switch ($limitType) {
            case 'CURRENCY':
                $this->messages = [
                    'pl' => sprintf('Kwota transakcji %s %s znajduje się poniżej zdefiniowanej wartości dla danej waluty wynoszącej %s %s.',
                        $money['value'],
                        $money['currency'],
                        $limit['value'],
                        $limit['currency']),
                    'en' => $detail
                ];
                break;
            case 'COMMISSION':
                $this->messages = [
                    'pl' => sprintf('Dla wybranej metody płatności opłaty przewyższają kwotę transakcji %s %s. Minimalna kwota transakcji powinna wynosić %s %s.',
                        $money['value'],
                        $money['currency'],
                        $limit['value'],
                        $limit['currency']),
                    'en' => $detail
                ];
                break;
            default:
                $this->messages = [
                    'pl' => 'Podana kwota płatności znajduje się poniżej określonego limitu.',
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
