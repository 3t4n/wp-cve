<?php

function get_export_settings_from_id( $export_id )
{
    $options = get_option( 'houzez_property_feed' , array() );
    $exports = ( isset($options['exports']) && is_array($options['exports']) && !empty($options['exports']) ) ? $options['exports'] : array();

    if ( isset($exports[$export_id]) )
    {
        return $exports[$export_id];
    }

    return false;
}