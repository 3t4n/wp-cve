<?php

namespace FedExVendor\FedEx\UploadDocumentService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * Indicates the damage status of a package.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Upload Document Service
 */
class DamageStatusType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _DAMAGE_KNOWN = 'DAMAGE_KNOWN';
    const _NO_KNOWN_DAMAGE = 'NO_KNOWN_DAMAGE';
}
