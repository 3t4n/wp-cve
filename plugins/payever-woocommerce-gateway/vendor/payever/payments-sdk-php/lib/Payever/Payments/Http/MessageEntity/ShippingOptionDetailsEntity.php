<?php

/**
 * PHP version 5.4 and 8
 *
 * @category  MessageEntity
 * @package   Payever\Payments
 * @author    payever GmbH <service@payever.de>
 * @copyright 2017-2023 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Payments\Http\MessageEntity;

use Payever\Sdk\Core\Base\MessageEntity;
/**
 * This class represents ShippingOptionDetails entity
 *
 * @method string getTimeslot()
 * @method PickupLocationEntity|array getPickupLocation()
 */
class ShippingOptionDetailsEntity extends MessageEntity
{
    /**
     * @var \DateTime|null
     */
    protected $timeslot;

    /**
     * @var PickupLocationEntity
     */
    protected $pickupLocation;

    /**
     * Sets Timeslot
     *
     * @param string $timeslot
     *
     * @return $this
     */
    public function setTimeslot($timeslot)
    {
        if ($timeslot) {
            $this->timeslot = date_create($timeslot);
        }

        return $this;
    }

    /**
     * Sets Pickup Location
     *
     * @param PickupLocationEntity|array $address
     * @return self
     */
    public function setPickupLocation($pickupLocation)
    {
        if (!$pickupLocation) {
            return $this;
        }

        if (is_string($pickupLocation)) {
            $pickupLocation = json_decode($pickupLocation);
        }

        if (!is_array($pickupLocation) && !is_object($pickupLocation)) {
            return $this;
        }

        $this->pickupLocation = new PickupLocationEntity($pickupLocation);

        return $this;
    }
}
