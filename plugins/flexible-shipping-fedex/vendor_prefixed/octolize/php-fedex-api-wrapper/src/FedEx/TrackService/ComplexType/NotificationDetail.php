<?php

namespace FedExVendor\FedEx\TrackService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * NotificationDetail
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Package Movement Information Service
 *
 * @property \FedEx\TrackService\SimpleType\NotificationType|string $NotificationType
 * @property EMailDetail $EmailDetail
 * @property Localization $Localization
 */
class NotificationDetail extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'NotificationDetail';
    /**
     * Indicates the type of notification that will be sent.
     *
     * @param \FedEx\TrackService\SimpleType\NotificationType|string $notificationType
     * @return $this
     */
    public function setNotificationType($notificationType)
    {
        $this->values['NotificationType'] = $notificationType;
        return $this;
    }
    /**
     * Specifies the email notification details.
     *
     * @param EMailDetail $emailDetail
     * @return $this
     */
    public function setEmailDetail(\FedExVendor\FedEx\TrackService\ComplexType\EMailDetail $emailDetail)
    {
        $this->values['EmailDetail'] = $emailDetail;
        return $this;
    }
    /**
     * Specifies the localization for this notification.
     *
     * @param Localization $localization
     * @return $this
     */
    public function setLocalization(\FedExVendor\FedEx\TrackService\ComplexType\Localization $localization)
    {
        $this->values['Localization'] = $localization;
        return $this;
    }
}
