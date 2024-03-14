<?php

namespace FedExVendor\FedEx\AsyncService;

use FedExVendor\FedEx\AbstractRequest;
/**
 * Request sends the SOAP call to the FedEx servers and returns the response
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  ASync Service
 */
class Request extends \FedExVendor\FedEx\AbstractRequest
{
    const PRODUCTION_URL = 'https://ws.fedex.com:443/web-services/async';
    const TESTING_URL = 'https://wsbeta.fedex.com:443/web-services/async';
    protected static $wsdlFileName = 'ASYNCService_v4.wsdl';
    /**
     * Sends the RetrieveJobResultsRequest and returns the response
     *
     * @param ComplexType\RetrieveJobResultsRequest $retrieveJobResultsRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\RetrieveJobResultsReply|stdClass
     */
    public function getRetrieveJobResultsReply(\FedExVendor\FedEx\AsyncService\ComplexType\RetrieveJobResultsRequest $retrieveJobResultsRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->retrieveJobResults($retrieveJobResultsRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $retrieveJobResultsReply = new \FedExVendor\FedEx\AsyncService\ComplexType\RetrieveJobResultsReply();
        $retrieveJobResultsReply->populateFromStdClass($response);
        return $retrieveJobResultsReply;
    }
}
