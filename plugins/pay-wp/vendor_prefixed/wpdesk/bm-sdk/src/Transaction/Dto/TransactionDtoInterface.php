<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\Transaction\Dto;

use WPPayVendor\BlueMedia\Transaction\ValueObject\Transaction;
interface TransactionDtoInterface
{
    /**
     * @return Transaction
     */
    public function getTransaction() : \WPPayVendor\BlueMedia\Transaction\ValueObject\Transaction;
    /**
     * @return string
     */
    public function getHtmlFormLanguage() : string;
    /**
     * @param string $htmlFormLanguage
     * @return TransactionDto
     */
    public function setHtmlFormLanguage(string $htmlFormLanguage) : \WPPayVendor\BlueMedia\Transaction\Dto\TransactionDto;
}
