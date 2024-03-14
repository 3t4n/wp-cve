<?php

namespace FedExVendor\FedEx\TrackService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * TrackingDocumentImageType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Package Movement Information Service
 */
class TrackingDocumentImageType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _PDF = 'PDF';
    const _PNG = 'PNG';
}
