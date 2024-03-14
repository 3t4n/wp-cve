<?php
/**
 * Created by PhpStorm.
 * User: Edgar
 * Date: 9/19/2018
 * Time: 5:34 AM
 */

namespace rednaoformpdfbuilder\htmlgenerator\merger;


class MergeItem
{
    public $OrderId;
    public $InvoiceId;

    public function __construct($OrderId,$InvoiceId)
    {
        $this->OrderId=$OrderId;
        $this->InvoiceId=$InvoiceId;
    }


}