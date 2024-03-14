<?php

namespace HQRentalsPlugin\HQRentalsTasks;

abstract class HQRentalsBaseTask
{
    protected $response;

    /*Get data from api and set response*/
    abstract public function tryToRefreshSettingsData();

    /*Validate that the response have no errors*/
    abstract public function dataWasRetrieved();

    /*Populate WP Database*/
    abstract public function setDataOnWP();

    abstract public function getError();

    abstract public function getResponse();
}
