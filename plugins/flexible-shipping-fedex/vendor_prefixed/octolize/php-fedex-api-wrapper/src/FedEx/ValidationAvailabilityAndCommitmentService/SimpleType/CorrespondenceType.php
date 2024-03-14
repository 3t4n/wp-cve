<?php

namespace FedExVendor\FedEx\ValidationAvailabilityAndCommitmentService\SimpleType;

use FedExVendor\FedEx\AbstractSimpleType;
/**
 * CorrespondenceType
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Validation Availability And Commitment Service Service
 */
class CorrespondenceType extends \FedExVendor\FedEx\AbstractSimpleType
{
    const _CHAT = 'CHAT';
    const _DIRECT_MAIL = 'DIRECT_MAIL';
    const _EMAIL = 'EMAIL';
    const _FAX = 'FAX';
    const _PHONE = 'PHONE';
    const _SOCIAL_MEDIA = 'SOCIAL_MEDIA';
    const _TEXT_MESSAGE = 'TEXT_MESSAGE';
    const _UNSPECIFIED = 'UNSPECIFIED';
}
