<?php

/**
 * User: Damian Zamojski (br33f)
 * Date: 25.06.2021
 * Time: 13:52
 */
namespace Isolated\Blue_Media\Isolated_Php_ga4_mp\Br33f\Ga4\MeasurementProtocol\Dto\Event;

use Isolated\Blue_Media\Isolated_Php_ga4_mp\Br33f\Ga4\MeasurementProtocol\Dto\Parameter\AbstractParameter;
/**
 * Class SignUpEvent
 * @package Br33f\Ga4\MeasurementProtocol\Dto\Event
 * @method string getMethod()
 * @method SignUpEvent setMethod(string $method)
 */
class SignUpEvent extends AbstractEvent
{
    private $eventName = 'sign_up';
    /**
     * SignUpEvent constructor.
     * @param AbstractParameter[] $paramList
     */
    public function __construct(array $paramList = [])
    {
        parent::__construct($this->eventName, $paramList);
    }
    /**
     * @return bool
     */
    public function validate()
    {
        return \true;
    }
}
