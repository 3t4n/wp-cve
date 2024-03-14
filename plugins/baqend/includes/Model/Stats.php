<?php

namespace Baqend\WordPress\Model;

/**
 * Class Stats created on 2018-06-27.
 *
 * @author Konstantin Simon Maria Möllers
 * @author Kevin Twesten
 * @package Baqend\WordPress\Model
 */
class Stats {

    /**
     * @var string
     */
    public $app_name;

    /**
     * @var string
     */
    public $app_type;

    /**
     * @var bool
     */
    public $app_exceeded;

    /**
     * @var int|null
     */
    public $trial_duration;

    /**
     * @var int|null
     */
    public $remaining_days;

    /**
     * @var int
     */
    public $fix_price;

    /**
     * @var int
     */
    public $price_max;

    /**
     * @var int
     */
    public $price_now;

    /**
     * @var int
     */
    public $requests_max;

    /**
     * @var int
     */
    public $requests_now;

    /**
     * @var int
     */
    public $traffic_max;

    /**
     * @var int
     */
    public $traffic_now;

    /**
     * @var int
     */
    public $price_percent;

    /**
     * @var int
     */
    public $requests_percent;

    /**
     * @var int
     */
    public $traffic_percent;

    /**
     * @var int
     */
    public $percent;

    /**
     * @var \DateTime|null
     */
    public $next_plan_date;

    /**
     * Stats constructor.
     *
     * @param array $data
     */
    public function __construct( array $data ) {
        if ( ! $data || isset( $data['appExceeded'] ) ) {
            $this->app_exceeded     = true;
            $this->price_max        = 0;
            $this->percent          = 100;
            $this->trial_duration   = ( array_key_exists( 'trialDuration', $data ) ) ? $data['trialDuration'] : null;
            $this->remaining_days   = 0;
            return;
        }

        $this->app_name         = $data['appName'];
        $this->app_type         = $data['appType'];
        $this->fix_price        = $data['fixPrice'];
        $this->price_max        = $data['priceMax'];
        $this->price_now        = $data['priceNow'];
        $this->price_percent    = $this->percent( $this->price_now, $this->price_max );
        $this->requests_max     = $data['requestsMax'];
        $this->requests_now     = $data['requestsNow'];
        $this->requests_percent = $this->percent( $this->requests_now, $this->requests_max );
        $this->traffic_max      = $data['trafficMax'];
        $this->traffic_now      = $data['trafficNow'];
        $this->traffic_percent  = $this->percent( $this->traffic_now, $this->traffic_max );
        $this->percent          = max( $this->requests_max > 0 ? $this->requests_now / $this->requests_max : 0, $this->traffic_max > 0 ? $this->traffic_now / $this->traffic_max : 0 );
        $this->trial_duration   = ( array_key_exists( 'trialDuration', $data ) ) ? $data['trialDuration'] : null;
        // Will only set remaining days for new Speed Kit Plan
        $this->remaining_days = ( array_key_exists( 'remainingDays', $data ) ) ? $data['remainingDays'] : null;
        $this->next_plan_date = ( isset( $data['nextPlanStartedAt'] ) ) ? new \DateTime( get_date_from_gmt( $data['nextPlanStartedAt'] ) ) : null;
        $this->app_exceeded   = false;
    }

    /**
     * Returns true, if the given app has the old free plan of Speed Kit
     *
     * @return bool
     */
    public function is_free() {
        return $this->price_max === 0 && ! $this->trial_duration;
    }

    /**
     * Returns true, if the given app has the new free trial plan of Speed Kit
     *
     * @return bool
     */
    public function is_free_trial() {
        return $this->price_max === 0 && $this->trial_duration !== null && $this->remaining_days !== null;
    }

    /**
     * Returns true, if the given app has bought a new plan
     *
     * @return bool
     */
    public function has_next_plan() {
        return $this->next_plan_date !== null;
    }

    /**
     * @return bool
     */
    public function is_limited() {
        return $this->price_max > - 1;
    }

    /**
     * @return bool
     */
    public function is_unlimited() {
        return $this->price_max === - 1;
    }

    /**
     * @return bool
     */
    public function is_exceeded() {
        return $this->app_exceeded;
    }

    public function is_plesk_user() {
        if ( ! $this->app_type ) {
            return null;
        }

        return $this->app_type === 'plesk';
    }

    /**
     * @param int $now
     * @param int $max
     *
     * @return int
     */
    private function percent( $now, $max ) {
        if ( $max <= 0 ) {
            return - 1;
        }

        return (int) round( 100 * $now / $max );
    }
}
