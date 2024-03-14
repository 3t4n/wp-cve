<?php

namespace FedExVendor\FedEx\OpenShipService;

use FedExVendor\FedEx\AbstractRequest;
/**
 * Request sends the SOAP call to the FedEx servers and returns the response
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  OpenShip Service
 */
class Request extends \FedExVendor\FedEx\AbstractRequest
{
    const PRODUCTION_URL = 'https://ws.fedex.com:443/web-services/openship';
    const TESTING_URL = 'https://wsbeta.fedex.com:443/web-services/openship';
    protected static $wsdlFileName = 'OpenshipService_v20.wsdl';
    /**
     * Sends the ModifyConsolidationRequest and returns the response
     *
     * @param ComplexType\ModifyConsolidationRequest $modifyConsolidationRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\ModifyConsolidationReply|stdClass
     */
    public function getModifyConsolidationReply(\FedExVendor\FedEx\OpenShipService\ComplexType\ModifyConsolidationRequest $modifyConsolidationRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->modifyConsolidation($modifyConsolidationRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $modifyConsolidationReply = new \FedExVendor\FedEx\OpenShipService\ComplexType\ModifyConsolidationReply();
        $modifyConsolidationReply->populateFromStdClass($response);
        return $modifyConsolidationReply;
    }
    /**
     * Sends the ValidateOpenShipmentRequest and returns the response
     *
     * @param ComplexType\ValidateOpenShipmentRequest $validateOpenShipmentRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\ValidateOpenShipmentReply|stdClass
     */
    public function getValidateOpenShipmentReply(\FedExVendor\FedEx\OpenShipService\ComplexType\ValidateOpenShipmentRequest $validateOpenShipmentRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->validateOpenShipment($validateOpenShipmentRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $validateOpenShipmentReply = new \FedExVendor\FedEx\OpenShipService\ComplexType\ValidateOpenShipmentReply();
        $validateOpenShipmentReply->populateFromStdClass($response);
        return $validateOpenShipmentReply;
    }
    /**
     * Sends the RetrieveOpenShipmentRequest and returns the response
     *
     * @param ComplexType\RetrieveOpenShipmentRequest $retrieveOpenShipmentRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\RetrieveOpenShipmentReply|stdClass
     */
    public function getRetrieveOpenShipmentReply(\FedExVendor\FedEx\OpenShipService\ComplexType\RetrieveOpenShipmentRequest $retrieveOpenShipmentRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->retrieveOpenShipment($retrieveOpenShipmentRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $retrieveOpenShipmentReply = new \FedExVendor\FedEx\OpenShipService\ComplexType\RetrieveOpenShipmentReply();
        $retrieveOpenShipmentReply->populateFromStdClass($response);
        return $retrieveOpenShipmentReply;
    }
    /**
     * Sends the DeleteOpenConsolidationRequest and returns the response
     *
     * @param ComplexType\DeleteOpenConsolidationRequest $deleteOpenConsolidationRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\DeleteOpenConsolidationReply|stdClass
     */
    public function getDeleteOpenConsolidationReply(\FedExVendor\FedEx\OpenShipService\ComplexType\DeleteOpenConsolidationRequest $deleteOpenConsolidationRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->deleteOpenConsolidation($deleteOpenConsolidationRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $deleteOpenConsolidationReply = new \FedExVendor\FedEx\OpenShipService\ComplexType\DeleteOpenConsolidationReply();
        $deleteOpenConsolidationReply->populateFromStdClass($response);
        return $deleteOpenConsolidationReply;
    }
    /**
     * Sends the CreateConsolidationRequest and returns the response
     *
     * @param ComplexType\CreateConsolidationRequest $createConsolidationRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\CreateConsolidationReply|stdClass
     */
    public function getCreateConsolidationReply(\FedExVendor\FedEx\OpenShipService\ComplexType\CreateConsolidationRequest $createConsolidationRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->createConsolidation($createConsolidationRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $createConsolidationReply = new \FedExVendor\FedEx\OpenShipService\ComplexType\CreateConsolidationReply();
        $createConsolidationReply->populateFromStdClass($response);
        return $createConsolidationReply;
    }
    /**
     * Sends the RetrievePackageInOpenShipmentRequest and returns the response
     *
     * @param ComplexType\RetrievePackageInOpenShipmentRequest $retrievePackageInOpenShipmentRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\RetrievePackageInOpenShipmentReply|stdClass
     */
    public function getRetrievePackageInOpenShipmentReply(\FedExVendor\FedEx\OpenShipService\ComplexType\RetrievePackageInOpenShipmentRequest $retrievePackageInOpenShipmentRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->retrievePackageInOpenShipment($retrievePackageInOpenShipmentRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $retrievePackageInOpenShipmentReply = new \FedExVendor\FedEx\OpenShipService\ComplexType\RetrievePackageInOpenShipmentReply();
        $retrievePackageInOpenShipmentReply->populateFromStdClass($response);
        return $retrievePackageInOpenShipmentReply;
    }
    /**
     * Sends the RetrieveConsolidatedCommoditiesRequest and returns the response
     *
     * @param ComplexType\RetrieveConsolidatedCommoditiesRequest $retrieveConsolidatedCommoditiesRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\RetrieveConsolidatedCommoditiesReply|stdClass
     */
    public function getRetrieveConsolidatedCommoditiesReply(\FedExVendor\FedEx\OpenShipService\ComplexType\RetrieveConsolidatedCommoditiesRequest $retrieveConsolidatedCommoditiesRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->retrieveConsolidatedCommodities($retrieveConsolidatedCommoditiesRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $retrieveConsolidatedCommoditiesReply = new \FedExVendor\FedEx\OpenShipService\ComplexType\RetrieveConsolidatedCommoditiesReply();
        $retrieveConsolidatedCommoditiesReply->populateFromStdClass($response);
        return $retrieveConsolidatedCommoditiesReply;
    }
    /**
     * Sends the ModifyPackageInOpenShipmentRequest and returns the response
     *
     * @param ComplexType\ModifyPackageInOpenShipmentRequest $modifyPackageInOpenShipmentRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\ModifyPackageInOpenShipmentReply|stdClass
     */
    public function getModifyPackageInOpenShipmentReply(\FedExVendor\FedEx\OpenShipService\ComplexType\ModifyPackageInOpenShipmentRequest $modifyPackageInOpenShipmentRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->modifyPackageInOpenShipment($modifyPackageInOpenShipmentRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $modifyPackageInOpenShipmentReply = new \FedExVendor\FedEx\OpenShipService\ComplexType\ModifyPackageInOpenShipmentReply();
        $modifyPackageInOpenShipmentReply->populateFromStdClass($response);
        return $modifyPackageInOpenShipmentReply;
    }
    /**
     * Sends the DeleteShipmentRequest and returns the response
     *
     * @param ComplexType\DeleteShipmentRequest $deleteShipmentRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\ShipmentReply|stdClass
     */
    public function getDeleteShipmentReply(\FedExVendor\FedEx\OpenShipService\ComplexType\DeleteShipmentRequest $deleteShipmentRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->deleteShipment($deleteShipmentRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $shipmentReply = new \FedExVendor\FedEx\OpenShipService\ComplexType\ShipmentReply();
        $shipmentReply->populateFromStdClass($response);
        return $shipmentReply;
    }
    /**
     * Sends the CreateOpenShipmentRequest and returns the response
     *
     * @param ComplexType\CreateOpenShipmentRequest $createOpenShipmentRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\CreateOpenShipmentReply|stdClass
     */
    public function getCreateOpenShipmentReply(\FedExVendor\FedEx\OpenShipService\ComplexType\CreateOpenShipmentRequest $createOpenShipmentRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->createOpenShipment($createOpenShipmentRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $createOpenShipmentReply = new \FedExVendor\FedEx\OpenShipService\ComplexType\CreateOpenShipmentReply();
        $createOpenShipmentReply->populateFromStdClass($response);
        return $createOpenShipmentReply;
    }
    /**
     * Sends the DeletePendingShipmentRequest and returns the response
     *
     * @param ComplexType\DeletePendingShipmentRequest $deletePendingShipmentRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\DeletePendingShipmentReply|stdClass
     */
    public function getDeletePendingShipmentReply(\FedExVendor\FedEx\OpenShipService\ComplexType\DeletePendingShipmentRequest $deletePendingShipmentRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->deletePendingShipment($deletePendingShipmentRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $deletePendingShipmentReply = new \FedExVendor\FedEx\OpenShipService\ComplexType\DeletePendingShipmentReply();
        $deletePendingShipmentReply->populateFromStdClass($response);
        return $deletePendingShipmentReply;
    }
    /**
     * Sends the ConfirmOpenShipmentRequest and returns the response
     *
     * @param ComplexType\ConfirmOpenShipmentRequest $confirmOpenShipmentRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\ConfirmOpenShipmentReply|stdClass
     */
    public function getConfirmOpenShipmentReply(\FedExVendor\FedEx\OpenShipService\ComplexType\ConfirmOpenShipmentRequest $confirmOpenShipmentRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->confirmOpenShipment($confirmOpenShipmentRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $confirmOpenShipmentReply = new \FedExVendor\FedEx\OpenShipService\ComplexType\ConfirmOpenShipmentReply();
        $confirmOpenShipmentReply->populateFromStdClass($response);
        return $confirmOpenShipmentReply;
    }
    /**
     * Sends the GetConfirmOpenShipmentResultsRequest and returns the response
     *
     * @param ComplexType\GetConfirmOpenShipmentResultsRequest $getConfirmOpenShipmentResultsRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\GetConfirmOpenShipmentResultsReply|stdClass
     */
    public function getGetConfirmOpenShipmentResultsReply(\FedExVendor\FedEx\OpenShipService\ComplexType\GetConfirmOpenShipmentResultsRequest $getConfirmOpenShipmentResultsRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->getConfirmOpenShipmentResults($getConfirmOpenShipmentResultsRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $getConfirmOpenShipmentResultsReply = new \FedExVendor\FedEx\OpenShipService\ComplexType\GetConfirmOpenShipmentResultsReply();
        $getConfirmOpenShipmentResultsReply->populateFromStdClass($response);
        return $getConfirmOpenShipmentResultsReply;
    }
    /**
     * Sends the GetConfirmConsolidationResultsRequest and returns the response
     *
     * @param ComplexType\GetConfirmConsolidationResultsRequest $getConfirmConsolidationResultsRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\GetConfirmConsolidationResultsReply|stdClass
     */
    public function getGetConfirmConsolidationResultsReply(\FedExVendor\FedEx\OpenShipService\ComplexType\GetConfirmConsolidationResultsRequest $getConfirmConsolidationResultsRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->getConfirmConsolidationResults($getConfirmConsolidationResultsRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $getConfirmConsolidationResultsReply = new \FedExVendor\FedEx\OpenShipService\ComplexType\GetConfirmConsolidationResultsReply();
        $getConfirmConsolidationResultsReply->populateFromStdClass($response);
        return $getConfirmConsolidationResultsReply;
    }
    /**
     * Sends the ModifyOpenShipmentRequest and returns the response
     *
     * @param ComplexType\ModifyOpenShipmentRequest $modifyOpenShipmentRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\ModifyOpenShipmentReply|stdClass
     */
    public function getModifyOpenShipmentReply(\FedExVendor\FedEx\OpenShipService\ComplexType\ModifyOpenShipmentRequest $modifyOpenShipmentRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->modifyOpenShipment($modifyOpenShipmentRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $modifyOpenShipmentReply = new \FedExVendor\FedEx\OpenShipService\ComplexType\ModifyOpenShipmentReply();
        $modifyOpenShipmentReply->populateFromStdClass($response);
        return $modifyOpenShipmentReply;
    }
    /**
     * Sends the ConfirmConsolidationRequest and returns the response
     *
     * @param ComplexType\ConfirmConsolidationRequest $confirmConsolidationRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\ConfirmConsolidationReply|stdClass
     */
    public function getConfirmConsolidationReply(\FedExVendor\FedEx\OpenShipService\ComplexType\ConfirmConsolidationRequest $confirmConsolidationRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->confirmConsolidation($confirmConsolidationRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $confirmConsolidationReply = new \FedExVendor\FedEx\OpenShipService\ComplexType\ConfirmConsolidationReply();
        $confirmConsolidationReply->populateFromStdClass($response);
        return $confirmConsolidationReply;
    }
    /**
     * Sends the GetModifyOpenShipmentResultsRequest and returns the response
     *
     * @param ComplexType\GetModifyOpenShipmentResultsRequest $getModifyOpenShipmentResultsRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\GetModifyOpenShipmentResultsReply|stdClass
     */
    public function getGetModifyOpenShipmentResultsReply(\FedExVendor\FedEx\OpenShipService\ComplexType\GetModifyOpenShipmentResultsRequest $getModifyOpenShipmentResultsRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->getModifyOpenShipmentResults($getModifyOpenShipmentResultsRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $getModifyOpenShipmentResultsReply = new \FedExVendor\FedEx\OpenShipService\ComplexType\GetModifyOpenShipmentResultsReply();
        $getModifyOpenShipmentResultsReply->populateFromStdClass($response);
        return $getModifyOpenShipmentResultsReply;
    }
    /**
     * Sends the DeletePackagesFromOpenShipmentRequest and returns the response
     *
     * @param ComplexType\DeletePackagesFromOpenShipmentRequest $deletePackagesFromOpenShipmentRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\DeletePackagesFromOpenShipmentReply|stdClass
     */
    public function getDeletePackagesFromOpenShipmentReply(\FedExVendor\FedEx\OpenShipService\ComplexType\DeletePackagesFromOpenShipmentRequest $deletePackagesFromOpenShipmentRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->deletePackagesFromOpenShipment($deletePackagesFromOpenShipmentRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $deletePackagesFromOpenShipmentReply = new \FedExVendor\FedEx\OpenShipService\ComplexType\DeletePackagesFromOpenShipmentReply();
        $deletePackagesFromOpenShipmentReply->populateFromStdClass($response);
        return $deletePackagesFromOpenShipmentReply;
    }
    /**
     * Sends the ReprintShippingDocumentsRequest and returns the response
     *
     * @param ComplexType\ReprintShippingDocumentsRequest $reprintShippingDocumentsRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\ReprintShippingDocumentsReply|stdClass
     */
    public function getReprintShippingDocumentsReply(\FedExVendor\FedEx\OpenShipService\ComplexType\ReprintShippingDocumentsRequest $reprintShippingDocumentsRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->reprintShippingDocuments($reprintShippingDocumentsRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $reprintShippingDocumentsReply = new \FedExVendor\FedEx\OpenShipService\ComplexType\ReprintShippingDocumentsReply();
        $reprintShippingDocumentsReply->populateFromStdClass($response);
        return $reprintShippingDocumentsReply;
    }
    /**
     * Sends the AddPackagesToOpenShipmentRequest and returns the response
     *
     * @param ComplexType\AddPackagesToOpenShipmentRequest $addPackagesToOpenShipmentRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\AddPackagesToOpenShipmentReply|stdClass
     */
    public function getAddPackagesToOpenShipmentReply(\FedExVendor\FedEx\OpenShipService\ComplexType\AddPackagesToOpenShipmentRequest $addPackagesToOpenShipmentRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->addPackagesToOpenShipment($addPackagesToOpenShipmentRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $addPackagesToOpenShipmentReply = new \FedExVendor\FedEx\OpenShipService\ComplexType\AddPackagesToOpenShipmentReply();
        $addPackagesToOpenShipmentReply->populateFromStdClass($response);
        return $addPackagesToOpenShipmentReply;
    }
    /**
     * Sends the GetCreateOpenShipmentResultsRequest and returns the response
     *
     * @param ComplexType\GetCreateOpenShipmentResultsRequest $getCreateOpenShipmentResultsRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\GetCreateOpenShipmentResultsReply|stdClass
     */
    public function getGetCreateOpenShipmentResultsReply(\FedExVendor\FedEx\OpenShipService\ComplexType\GetCreateOpenShipmentResultsRequest $getCreateOpenShipmentResultsRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->getCreateOpenShipmentResults($getCreateOpenShipmentResultsRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $getCreateOpenShipmentResultsReply = new \FedExVendor\FedEx\OpenShipService\ComplexType\GetCreateOpenShipmentResultsReply();
        $getCreateOpenShipmentResultsReply->populateFromStdClass($response);
        return $getCreateOpenShipmentResultsReply;
    }
    /**
     * Sends the RetrieveConsolidationRequest and returns the response
     *
     * @param ComplexType\RetrieveConsolidationRequest $retrieveConsolidationRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\RetrieveConsolidationReply|stdClass
     */
    public function getRetrieveConsolidationReply(\FedExVendor\FedEx\OpenShipService\ComplexType\RetrieveConsolidationRequest $retrieveConsolidationRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->retrieveConsolidation($retrieveConsolidationRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $retrieveConsolidationReply = new \FedExVendor\FedEx\OpenShipService\ComplexType\RetrieveConsolidationReply();
        $retrieveConsolidationReply->populateFromStdClass($response);
        return $retrieveConsolidationReply;
    }
    /**
     * Sends the DeleteOpenShipmentRequest and returns the response
     *
     * @param ComplexType\DeleteOpenShipmentRequest $deleteOpenShipmentRequest
     * @param bool $returnStdClass Return the $stdClass response directly from \SoapClient
     * @return ComplexType\DeleteOpenShipmentReply|stdClass
     */
    public function getDeleteOpenShipmentReply(\FedExVendor\FedEx\OpenShipService\ComplexType\DeleteOpenShipmentRequest $deleteOpenShipmentRequest, $returnStdClass = \false)
    {
        $response = $this->getSoapClient()->deleteOpenShipment($deleteOpenShipmentRequest->toArray());
        if ($returnStdClass) {
            return $response;
        }
        $deleteOpenShipmentReply = new \FedExVendor\FedEx\OpenShipService\ComplexType\DeleteOpenShipmentReply();
        $deleteOpenShipmentReply->populateFromStdClass($response);
        return $deleteOpenShipmentReply;
    }
}
