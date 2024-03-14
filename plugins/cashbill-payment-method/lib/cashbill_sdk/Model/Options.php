<?php

/**
 *
 * CashBill Payment PHP SDK
 *
 * @author Lukasz Firek <lukasz.firek@cashbill.pl>
 * @version 1.0.0
 * @license MIT
 * @copyright CashBill S.A. 2015
 *
 * http://cashbill.pl
 *
 */
namespace CashBill\Payments\Model;

use CashBill\Payments\Interfaces\Data;
use CashBill\Payments\Services\CashBillTransactionException;

class Options implements Data
{
    private $options = array();
    
    /**
     *
     * @param string $name
     * @param string $value
     * @throws CashBillTransactionException
     */
    public function addOption($name, $value)
    {
        if (empty($name) || empty($value)) {
            throw new CashBillTransactionException("Required params not set");
        }

        $this->options[$name] = $value;
    }
    
    /**
     *
     * @see CashBillData::toArray()
     */
    public function toArray()
    {
        $array = array();
        foreach ($this->options as $name => $value) {
            $array[] = array(
                "name" => $name,
                "value" => $value
            );
        }
        return $array;
    }
}
