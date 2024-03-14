<?php

namespace UpsFreeVendor\Ups\Entity\RateTimeInTransit;

use UpsFreeVendor\Ups\Entity\ServiceSummaryTrait;
class ServiceSummary
{
    use ServiceSummaryTrait;
    /**
     * @var
     */
    protected $estimatedArrival;
    /**
     * @param \stdClass|null $response
     */
    public function __construct(\stdClass $response = null)
    {
        $this->build($response);
        $this->setEstimatedArrival(new \UpsFreeVendor\Ups\Entity\RateTimeInTransit\EstimatedArrival());
        if (null !== $response) {
            if (isset($response->EstimatedArrival)) {
                $this->setEstimatedArrival(new \UpsFreeVendor\Ups\Entity\RateTimeInTransit\EstimatedArrival($response->EstimatedArrival));
            }
        }
    }
    /**
     * @return EstimatedArrival|null
     */
    public function getEstimatedArrival()
    {
        return $this->estimatedArrival;
    }
    /**
     * @param EstimatedArrival $estimatedArrival
     */
    public function setEstimatedArrival(\UpsFreeVendor\Ups\Entity\RateTimeInTransit\EstimatedArrival $estimatedArrival)
    {
        $this->estimatedArrival = $estimatedArrival;
    }
}
