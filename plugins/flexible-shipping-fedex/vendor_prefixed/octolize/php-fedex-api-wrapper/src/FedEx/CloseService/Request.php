<?php

namespace FedExVendor\FedEx\CloseService;

use FedExVendor\FedEx\AbstractRequest;
/**
 * Request sends the SOAP call to the FedEx servers and returns the response
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Close Service
 */
class Request extends \FedExVendor\FedEx\AbstractRequest
{
    const PRODUCTION_URL = 'https://ws.fedex.com:443/web-services/close';
    const TESTING_URL = 'https://wsbeta.fedex.com:443/web-services/close';
    protected static $wsdlFileName = 'CloseService_v5.wsdl';
    /**
     * Sends the CloseWithDocumentsRequest and returns the response
     *
     * @param ComplexType\CloseWithDocumentsRequest $closeWithDocumentsRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\CloseWithDocumentsReply|stdClass
     */
    public function getCloseWithDocumentsReply(\FedExVendor\FedEx\CloseService\ComplexType\CloseWithDocumentsRequest $closeWithDocumentsRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->closeWithDocuments($closeWithDocumentsRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $closeWithDocumentsReply = new \FedExVendor\FedEx\CloseService\ComplexType\CloseWithDocumentsReply();
        $closeWithDocumentsReply->populateFromStdClass($response);
        return $closeWithDocumentsReply;
    }
    /**
     * Sends the SmartPostCloseRequest and returns the response
     *
     * @param ComplexType\SmartPostCloseRequest $smartPostCloseRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\SmartPostCloseReply|stdClass
     */
    public function getSmartPostCloseReply(\FedExVendor\FedEx\CloseService\ComplexType\SmartPostCloseRequest $smartPostCloseRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->smartPostClose($smartPostCloseRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $smartPostCloseReply = new \FedExVendor\FedEx\CloseService\ComplexType\SmartPostCloseReply();
        $smartPostCloseReply->populateFromStdClass($response);
        return $smartPostCloseReply;
    }
    /**
     * Sends the GroundCloseRequest and returns the response
     *
     * @param ComplexType\GroundCloseRequest $groundCloseRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\GroundCloseReply|stdClass
     */
    public function getGroundCloseReply(\FedExVendor\FedEx\CloseService\ComplexType\GroundCloseRequest $groundCloseRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->groundClose($groundCloseRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $groundCloseReply = new \FedExVendor\FedEx\CloseService\ComplexType\GroundCloseReply();
        $groundCloseReply->populateFromStdClass($response);
        return $groundCloseReply;
    }
    /**
     * Sends the GroundCloseWithDocumentsRequest and returns the response
     *
     * @param ComplexType\GroundCloseWithDocumentsRequest $groundCloseWithDocumentsRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\GroundCloseDocumentsReply|stdClass
     */
    public function getGroundCloseWithDocumentsReply(\FedExVendor\FedEx\CloseService\ComplexType\GroundCloseWithDocumentsRequest $groundCloseWithDocumentsRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->groundCloseWithDocuments($groundCloseWithDocumentsRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $groundCloseDocumentsReply = new \FedExVendor\FedEx\CloseService\ComplexType\GroundCloseDocumentsReply();
        $groundCloseDocumentsReply->populateFromStdClass($response);
        return $groundCloseDocumentsReply;
    }
    /**
     * Sends the ReprintGroundCloseDocumentsRequest and returns the response
     *
     * @param ComplexType\ReprintGroundCloseDocumentsRequest $reprintGroundCloseDocumentsRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\GroundCloseDocumentsReply|stdClass
     */
    public function getReprintGroundCloseDocumentsReply(\FedExVendor\FedEx\CloseService\ComplexType\ReprintGroundCloseDocumentsRequest $reprintGroundCloseDocumentsRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->reprintGroundCloseDocuments($reprintGroundCloseDocumentsRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $groundCloseDocumentsReply = new \FedExVendor\FedEx\CloseService\ComplexType\GroundCloseDocumentsReply();
        $groundCloseDocumentsReply->populateFromStdClass($response);
        return $groundCloseDocumentsReply;
    }
    /**
     * Sends the GroundCloseReportsReprintRequest and returns the response
     *
     * @param ComplexType\GroundCloseReportsReprintRequest $groundCloseReportsReprintRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\GroundCloseReportsReprintReply|stdClass
     */
    public function getGroundCloseReportsReprintReply(\FedExVendor\FedEx\CloseService\ComplexType\GroundCloseReportsReprintRequest $groundCloseReportsReprintRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->groundCloseReportsReprint($groundCloseReportsReprintRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $groundCloseReportsReprintReply = new \FedExVendor\FedEx\CloseService\ComplexType\GroundCloseReportsReprintReply();
        $groundCloseReportsReprintReply->populateFromStdClass($response);
        return $groundCloseReportsReprintReply;
    }
}
