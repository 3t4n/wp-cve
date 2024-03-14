<?php

namespace FedExVendor\FedEx\LocationsService;

use FedExVendor\FedEx\AbstractRequest;
/**
 * Request sends the SOAP call to the FedEx servers and returns the response
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Locations Service
 */
class Request extends \FedExVendor\FedEx\AbstractRequest
{
    const PRODUCTION_URL = 'https://ws.fedex.com:443/web-services/locs';
    const TESTING_URL = 'https://wsbeta.fedex.com:443/web-services/locs';
    protected static $wsdlFileName = 'LocationsService_v12.wsdl';
    /**
     * Sends the SearchLocationsRequest and returns the response
     *
     * @param ComplexType\SearchLocationsRequest $searchLocationsRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\SearchLocationsReply|stdClass
     */
    public function getSearchLocationsReply(\FedExVendor\FedEx\LocationsService\ComplexType\SearchLocationsRequest $searchLocationsRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->searchLocations($searchLocationsRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $searchLocationsReply = new \FedExVendor\FedEx\LocationsService\ComplexType\SearchLocationsReply();
        $searchLocationsReply->populateFromStdClass($response);
        return $searchLocationsReply;
    }
}
