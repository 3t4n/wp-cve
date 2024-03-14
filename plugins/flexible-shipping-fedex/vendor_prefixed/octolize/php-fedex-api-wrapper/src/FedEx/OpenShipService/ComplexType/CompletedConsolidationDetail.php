<?php

namespace FedExVendor\FedEx\OpenShipService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * CompletedConsolidationDetail
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  OpenShip Service
 *
 * @property ConsolidationShipment[] $ConsolidationShipments
 * @property ConsolidationDocument[] $Documents
 */
class CompletedConsolidationDetail extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'CompletedConsolidationDetail';
    /**
     * The shipment-level artifacts constructed when confirming this consolidation.
     *
     * @param ConsolidationShipment[] $consolidationShipments
     * @return $this
     */
    public function setConsolidationShipments(array $consolidationShipments)
    {
        $this->values['ConsolidationShipments'] = $consolidationShipments;
        return $this;
    }
    /**
     * Contains all documents produced for this distribution consolidation.
     *
     * @param ConsolidationDocument[] $documents
     * @return $this
     */
    public function setDocuments(array $documents)
    {
        $this->values['Documents'] = $documents;
        return $this;
    }
}
