<?php

namespace FedExVendor\FedEx\InFlightShipmentService;

use FedExVendor\FedEx\AbstractRequest;
/**
 * Request sends the SOAP call to the FedEx servers and returns the response
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  In Flight Shipment Service
 */
class Request extends \FedExVendor\FedEx\AbstractRequest
{
    const PRODUCTION_URL = 'https://ws.fedex.com:443/web-services/ifss';
    const TESTING_URL = 'https://wsbeta.fedex.com:443/web-services/ifss';
    protected static $wsdlFileName = 'InFlightShipmentService_v1.wsdl';
    /**
     * Sends the ValidateDeliveryRequest and returns the response
     *
     * @param ComplexType\ValidateDeliveryRequest $validateDeliveryRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\ValidateDeliveryReply|stdClass
     */
    public function getValidateDeliveryReply(\FedExVendor\FedEx\InFlightShipmentService\ComplexType\ValidateDeliveryRequest $validateDeliveryRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->validateDelivery($validateDeliveryRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $validateDeliveryReply = new \FedExVendor\FedEx\InFlightShipmentService\ComplexType\ValidateDeliveryReply();
        $validateDeliveryReply->populateFromStdClass($response);
        return $validateDeliveryReply;
    }
    /**
     * Sends the ProcessDeliveryRequest and returns the response
     *
     * @param ComplexType\ProcessDeliveryRequest $processDeliveryRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\ProcessDeliveryReply|stdClass
     */
    public function getProcessDeliveryReply(\FedExVendor\FedEx\InFlightShipmentService\ComplexType\ProcessDeliveryRequest $processDeliveryRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->processDelivery($processDeliveryRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $processDeliveryReply = new \FedExVendor\FedEx\InFlightShipmentService\ComplexType\ProcessDeliveryReply();
        $processDeliveryReply->populateFromStdClass($response);
        return $processDeliveryReply;
    }
}
