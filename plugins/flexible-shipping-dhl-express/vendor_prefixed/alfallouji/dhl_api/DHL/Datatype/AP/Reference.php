<?php

/**
 * Note : Code is released under the GNU LGPL
 *
 * Please do not change the header of this file
 *
 * This library is free software; you can redistribute it and/or modify it under the terms of the GNU
 * Lesser General Public License as published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * See the GNU Lesser General Public License for more details.
 */
/**
 * File:        Reference.php
 * Project:     DHL API
 *
 * @author      Al-Fallouji Bashar
 * @version     0.1
 */
namespace DhlVendor\DHL\Datatype\AP;

use DhlVendor\DHL\Datatype\Base;
/**
 * Reference Request model for DHL API
 */
class Reference extends \DhlVendor\DHL\Datatype\Base
{
    /**
     * Is this object a subobject
     * @var boolean
     */
    protected $_isSubobject = \true;
    /**
     * Parameters of the datatype
     * @var array
     */
    protected $_params = array('ReferenceID' => array('type' => 'ReferenceID', 'required' => \false, 'subobject' => \false, 'comment' => 'Shipper reference ID', 'maxLength' => '35'), 'ReferenceType' => array('type' => 'ReferenceType', 'required' => \false, 'subobject' => \false, 'comment' => 'Shipment reference type', 'length' => '2'));
}
