<?php

namespace FedExVendor\FedEx\TrackService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * PieceCountLocationType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Package Movement Information Service
 */
class PieceCountLocationType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _DESTINATION = 'DESTINATION';
    const _ORIGIN = 'ORIGIN';
}
