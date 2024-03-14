<?php

namespace UpsFreeVendor\Ups\Entity;

class QuantumViewEvents
{
    public $SubscriberID;
    public $SubscriptionEvents;
    public function __construct($response = null)
    {
        $this->SubscriptionEvents = [];
        if (null !== $response) {
            if (isset($response->SubscriberID)) {
                $this->SubscriberID = new $response->SubscriberID();
            }
        }
        if (isset($response->SubscriptionEvents)) {
            if (\is_array($response->SubscriptionEvents)) {
                foreach ($response->SubscriptionEvents as $SubscriptionEvents) {
                    $this->SubscriptionEvents[] = new \UpsFreeVendor\Ups\Entity\SubscriptionEvents($SubscriptionEvents);
                }
            } else {
                $this->SubscriptionEvents[] = new \UpsFreeVendor\Ups\Entity\SubscriptionEvents($response->SubscriptionEvents);
            }
        }
    }
}
