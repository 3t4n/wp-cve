<?php

namespace Hurrytimer\Placeholders;

use Hurrytimer\C;

class Delivery_Day_Placeholder extends Placeholder
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
        return apply_filters( 'hurrytimer_variable_delivery_day_format', 'l' );
    }

    public function get_value( $options = [] )
    {
        $variable = 'delivery_day';
        $value = '{' . $variable . '}';
        $date = null;

        switch ( $this->campaign->mode ) {
            case C::MODE_RECURRING:
                $date = $this->campaign->getRecurrenceEndDate();
                $date = $date->toDateTime();
                $value = date_i18n( $this->get_format(), $date->getTimestamp());
                break;
            
        }

        return apply_filters( 'hurrytimer_variable_delivery_day_value', $value, $date,
            $this->campaign->get_id() );
          
    }

}