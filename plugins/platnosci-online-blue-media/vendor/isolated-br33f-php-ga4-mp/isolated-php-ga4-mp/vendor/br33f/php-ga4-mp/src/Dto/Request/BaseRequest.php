<?php

/**
 * User: Damian Zamojski (br33f)
 * Date: 22.06.2021
 * Time: 11:22
 */
namespace Isolated\Blue_Media\Isolated_Php_ga4_mp\Br33f\Ga4\MeasurementProtocol\Dto\Request;

use Isolated\Blue_Media\Isolated_Php_ga4_mp\Br33f\Ga4\MeasurementProtocol\Dto\Common\EventCollection;
use Isolated\Blue_Media\Isolated_Php_ga4_mp\Br33f\Ga4\MeasurementProtocol\Dto\Common\UserProperties;
use Isolated\Blue_Media\Isolated_Php_ga4_mp\Br33f\Ga4\MeasurementProtocol\Dto\Common\UserProperty;
use Isolated\Blue_Media\Isolated_Php_ga4_mp\Br33f\Ga4\MeasurementProtocol\Dto\Event\AbstractEvent;
use Isolated\Blue_Media\Isolated_Php_ga4_mp\Br33f\Ga4\MeasurementProtocol\Enum\ErrorCode;
use Isolated\Blue_Media\Isolated_Php_ga4_mp\Br33f\Ga4\MeasurementProtocol\Exception\ValidationException;
class BaseRequest extends AbstractRequest
{
    /**
     * Unique identifier of user instance.
     * Required
     * @var string
     */
    protected $clientId;
    /**
     * Unique identifier for a user.
     * Not required
     * @var string
     */
    protected $userId;
    /**
     * An unix timestamp (microseconds) for the time to associate with the event.
     * Not requied
     * @var int
     */
    protected $timestampMicros = null;
    /**
     * The user properties for the measurement.
     * Not required
     * @var UserProperties
     */
    protected $userProperties = null;
    /**
     * If set true - indicates that events should not be use for personalized ads.
     * Default false
     * @var bool
     */
    protected $nonPersonalizedAds = \false;
    /**
     * Collection of event items. Maximum 25 events.
     * Required
     * @var EventCollection
     */
    protected $events;
    /**
     * BaseRequest constructor.
     * @param string|null $clientId
     * @param AbstractEvent|EventCollection|null $events - Single Event or EventsCollection
     */
    public function __construct(?string $clientId = null, $events = null)
    {
        $this->clientId = $clientId ?? '';
        if ($events !== null) {
            if ($events instanceof EventCollection) {
                $this->events = $events;
            } else {
                if ($events instanceof AbstractEvent) {
                    $this->events = new EventCollection();
                    $this->events->addEvent($events);
                }
            }
        } else {
            $this->events = new EventCollection();
        }
    }
    /**
     * @param UserProperty $userProperty
     * @return BaseRequest
     */
    public function addUserProperty(UserProperty $userProperty)
    {
        if ($this->getUserProperties() === null) {
            $this->setUserProperties(new UserProperties());
        }
        $this->getUserProperties()->addUserProperty($userProperty);
        return $this;
    }
    /**
     * @return UserProperties|null
     */
    public function getUserProperties() : ?UserProperties
    {
        return $this->userProperties;
    }
    /**
     * @param UserProperties|null $userProperties
     * @return BaseRequest
     */
    public function setUserProperties(?UserProperties $userProperties)
    {
        $this->userProperties = $userProperties;
        return $this;
    }
    /**
     * @param AbstractEvent $event
     * @return BaseRequest
     */
    public function addEvent(AbstractEvent $event)
    {
        $this->getEvents()->addEvent($event);
        return $this;
    }
    /**
     * @return EventCollection
     */
    public function getEvents() : EventCollection
    {
        return $this->events;
    }
    /**
     * @param EventCollection $events
     * @return BaseRequest
     */
    public function setEvents(EventCollection $events)
    {
        $this->events = $events;
        return $this;
    }
    /**
     * @return array
     */
    public function export() : array
    {
        $exportBaseRequest = ['client_id' => $this->getClientId(), 'non_personalized_ads' => $this->isNonPersonalizedAds(), 'events' => $this->getEvents()->export()];
        if ($this->getUserId() !== null) {
            $exportBaseRequest['user_id'] = $this->getUserId();
        }
        if ($this->getTimestampMicros() !== null) {
            $exportBaseRequest['timestamp_micros'] = $this->getTimestampMicros();
        }
        if ($this->getUserProperties() !== null) {
            $exportBaseRequest['user_properties'] = $this->getUserProperties()->export();
        }
        return $exportBaseRequest;
    }
    /**
     * @return string
     */
    public function getClientId() : string
    {
        return $this->clientId;
    }
    /**
     * @param string $clientId
     * @return BaseRequest
     */
    public function setClientId(string $clientId)
    {
        $this->clientId = $clientId;
        return $this;
    }
    /**
     * @return bool
     */
    public function isNonPersonalizedAds() : bool
    {
        return $this->nonPersonalizedAds;
    }
    /**
     * @param bool $nonPersonalizedAds
     * @return BaseRequest
     */
    public function setNonPersonalizedAds(bool $nonPersonalizedAds)
    {
        $this->nonPersonalizedAds = $nonPersonalizedAds;
        return $this;
    }
    /**
     * @return string|null
     */
    public function getUserId() : ?string
    {
        return $this->userId;
    }
    /**
     * @param string|null $userId
     * @return BaseRequest
     */
    public function setUserId(?string $userId)
    {
        $this->userId = $userId;
        return $this;
    }
    /**
     * @return ?int
     */
    public function getTimestampMicros() : ?int
    {
        return $this->timestampMicros;
    }
    /**
     * @param ?int $timestampMicros
     * @return BaseRequest
     */
    public function setTimestampMicros(?int $timestampMicros)
    {
        $this->timestampMicros = $timestampMicros;
        return $this;
    }
    /**
     * @return bool
     * @throws ValidationException
     */
    public function validate()
    {
        if (empty($this->getClientId())) {
            throw new ValidationException('Parameter "client_id" is required.', ErrorCode::VALIDATION_CLIENT_ID_REQUIRED, 'client_id');
        }
        $this->getEvents()->validate();
        return \true;
    }
}
