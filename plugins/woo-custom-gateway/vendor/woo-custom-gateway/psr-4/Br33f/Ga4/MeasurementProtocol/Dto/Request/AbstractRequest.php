<?php
/**
 * User: Damian Zamojski (br33f)
 * Date: 22.06.2021
 * Time: 11:10
 */

namespace RichardMuvirimi\WooCustomGateway\Vendor\Br33f\Ga4\MeasurementProtocol\Dto\Request;


use RichardMuvirimi\WooCustomGateway\Vendor\Br33f\Ga4\MeasurementProtocol\Dto\ExportableInterface;
use RichardMuvirimi\WooCustomGateway\Vendor\Br33f\Ga4\MeasurementProtocol\Dto\RequestValidateInterface;

abstract class AbstractRequest implements ExportableInterface, RequestValidateInterface
{
}