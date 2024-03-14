<?php

namespace WcMipConnector\Model;

defined('ABSPATH') || exit;

class Order
{
    /* @var string */
    public $OrderID;

    /* @var string */
    public $Reference;

    /** @var string */
    public $Currency;

    /* @var Price */
    public $OrderTotal;

    /* @var string */
    public $PaymentMethod;

    /* @var \DateTime */
    public $DateCreated;

    /* @var \DateTime */
    public $DateUpdated;

    /* @var ShippingAddress */
    public $ShippingAddress;

    /* @var Price */
    public $ShippingCost;

    /* @var OrderLine[] */
    public $OrderLines;

    /* @var string */
    public $State;

    /* @var string */
    public $ShippingService;
}