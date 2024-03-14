<?php

namespace FedExVendor\FedEx\LocationsService\ComplexType;

use FedExVendor\FedEx\AbstractComplexType;
/**
 * Specifies the location details and other information relevant to the location that is derived from the inputs provided in the request.
 *
 * @author      Jeremy Dunn <jeremy@jsdunn.info>
 * @package     PHP FedEx API wrapper
 * @subpackage  Locations Service
 *
 * @property Distance $Distance
 * @property ReservationAvailabilityDetail $ReservationAvailabilityDetail
 * @property \FedEx\LocationsService\SimpleType\SupportedRedirectToHoldServiceType|string[] $SupportedRedirectToHoldServices
 * @property LocationDetail $LocationDetail
 */
class DistanceAndLocationDetail extends \FedExVendor\FedEx\AbstractComplexType
{
    /**
     * Name of this complex type
     *
     * @var string
     */
    protected $name = 'DistanceAndLocationDetail';
    /**
     * Distance between an address of a geographic location and an address of a FedEx location.
     *
     * @param Distance $distance
     * @return $this
     */
    public function setDistance(\FedExVendor\FedEx\LocationsService\ComplexType\Distance $distance)
    {
        $this->values['Distance'] = $distance;
        return $this;
    }
    /**
     * Set ReservationAvailabilityDetail
     *
     * @param ReservationAvailabilityDetail $reservationAvailabilityDetail
     * @return $this
     */
    public function setReservationAvailabilityDetail(\FedExVendor\FedEx\LocationsService\ComplexType\ReservationAvailabilityDetail $reservationAvailabilityDetail)
    {
        $this->values['ReservationAvailabilityDetail'] = $reservationAvailabilityDetail;
        return $this;
    }
    /**
     * DEPRECATED as of July 2017; See locationCapabilities.
     *
     * @param \FedEx\LocationsService\SimpleType\SupportedRedirectToHoldServiceType[]|string[] $supportedRedirectToHoldServices
     * @return $this
     */
    public function setSupportedRedirectToHoldServices(array $supportedRedirectToHoldServices)
    {
        $this->values['SupportedRedirectToHoldServices'] = $supportedRedirectToHoldServices;
        return $this;
    }
    /**
     * Details about a FedEx location such as services offered, working hours and pick and drop off times.
     *
     * @param LocationDetail $locationDetail
     * @return $this
     */
    public function setLocationDetail(\FedExVendor\FedEx\LocationsService\ComplexType\LocationDetail $locationDetail)
    {
        $this->values['LocationDetail'] = $locationDetail;
        return $this;
    }
}
