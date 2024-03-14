<?php

namespace FedExVendor\FedEx\TrackService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * Options available for a tracking notification recipient.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Package Movement Information Service
 *
 * @property \FedEx\TrackService\SimpleType\NotificationEventType|string[] $NotificationEventsAvailable
 */
class TrackNotificationRecipientDetail extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'TrackNotificationRecipientDetail';
    /**
     * Set NotificationEventsAvailable
     *
     * @param \FedEx\TrackService\SimpleType\NotificationEventType[]|string[] $notificationEventsAvailable
     * @return $this
     */
    public function setNotificationEventsAvailable(array $notificationEventsAvailable)
    {
        $this->values['NotificationEventsAvailable'] = $notificationEventsAvailable;
        return $this;
    }
}
