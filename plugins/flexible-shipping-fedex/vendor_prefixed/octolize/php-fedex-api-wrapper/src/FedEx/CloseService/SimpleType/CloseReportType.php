<?php

namespace FedExVendor\FedEx\CloseService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * CloseReportType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Close Service
 */
class CloseReportType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _ALL = 'ALL';
    const _COD = 'COD';
    const _HAZMAT = 'HAZMAT';
    const _MANIFEST = 'MANIFEST';
    const _MULTIWEIGHT = 'MULTIWEIGHT';
}
