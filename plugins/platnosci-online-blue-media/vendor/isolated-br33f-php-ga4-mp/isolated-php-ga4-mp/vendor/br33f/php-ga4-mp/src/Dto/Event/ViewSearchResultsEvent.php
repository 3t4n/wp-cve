<?php

/**
 * User: Damian Zamojski (br33f)
 * Date: 25.06.2021
 * Time: 13:33
 */
namespace Isolated\Blue_Media\Isolated_Php_ga4_mp\Br33f\Ga4\MeasurementProtocol\Dto\Event;

use Isolated\Blue_Media\Isolated_Php_ga4_mp\Br33f\Ga4\MeasurementProtocol\Dto\Parameter\AbstractParameter;
/**
 * Class ViewSearchResultsEvent
 * @package Br33f\Ga4\MeasurementProtocol\Dto\Event
 * @method string getSearchTerm()
 * @method ViewSearchResultsEvent setSearchTerm(string $itemListId)
 */
class ViewSearchResultsEvent extends ItemBaseEvent
{
    private $eventName = 'view_search_results';
    /**
     * ViewSearchResultsEvent constructor.
     * @param AbstractParameter[] $paramList
     */
    public function __construct(array $paramList = [])
    {
        parent::__construct($this->eventName, $paramList);
    }
}
