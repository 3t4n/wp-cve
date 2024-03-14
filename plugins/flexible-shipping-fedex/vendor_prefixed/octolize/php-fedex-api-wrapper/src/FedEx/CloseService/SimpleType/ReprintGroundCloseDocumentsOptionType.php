<?php

namespace FedExVendor\FedEx\CloseService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * Identifies the requested options to reprinting Ground Close Documents
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Close Service
 */
class ReprintGroundCloseDocumentsOptionType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _BY_SHIP_DATE = 'BY_SHIP_DATE';
    const _BY_TRACKING_NUMBER = 'BY_TRACKING_NUMBER';
}
