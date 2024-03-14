<?php

namespace Hurrytimer\Placeholders;

use Hurrytimer\C;
use Hurrytimer\Dependencies\Carbon\Carbon;


class Time_Placeholder extends Placeholder
{

    /**
     * @var \Hurrytimer\Campaign
     */
    protected $campaign;

    public function __construct( $campaign )
    {
        $this->campaign = $campaign;
    }

    public function get_format()
    {
        return apply_filters( 'hurryt_time_format', get_option( 'time_format' ) );
    }

    public function get_value( $options = [])
    {
        switch ( $this->campaign->mode ) {
            case C::MODE_REGULAR:
                return Carbon::parse( $this->campaign->getEndDatetime() )->format( $this->get_format() );
            case C::MODE_RECURRING:
                return $this->campaign->getRecurrenceEndDate()->format( $this->get_format() );
            default:
                return '{time}';
        }
    }

}