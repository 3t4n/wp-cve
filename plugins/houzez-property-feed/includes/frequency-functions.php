<?php

function get_houzez_property_feed_import_frequencies()
{
    $frequencies = array(
        'daily' => array(
            'name' => __( 'Daily', 'houzezpropertyfeed' ),
            'pro' => false
        ),
         'twicedaily' => array(
            'name' => __( 'Twice Daily', 'houzezpropertyfeed' ),
            'pro' => true
        ),
        'hourly' => array(
            'name' => __( 'Hourly', 'houzezpropertyfeed' ),
            'pro' => true
        ),
        'every_fifteen_minutes' => array(
            'name' => __( 'Every Fifteen Minutes', 'houzezpropertyfeed' ),
            'pro' => true
        )
    );

    $frequencies = apply_filters( 'houzez_property_feed_import_frequencies', $frequencies );

    return $frequencies;
}

function get_houzez_property_feed_import_frequency( $key )
{
    $frequencies = get_houzez_property_feed_import_frequencies();
    
    return $frequencies[$key];
}

function get_houzez_property_feed_export_frequencies()
{
    $frequencies = array(
        'daily' => array(
            'name' => __( 'Daily', 'houzezpropertyfeed' ),
            'pro' => false
        ),
         'twicedaily' => array(
            'name' => __( 'Twice Daily', 'houzezpropertyfeed' ),
            'pro' => true
        ),
        'hourly' => array(
            'name' => __( 'Hourly', 'houzezpropertyfeed' ),
            'pro' => true
        ),
    );

    $frequencies = apply_filters( 'houzez_property_feed_export_frequencies', $frequencies );

    return $frequencies;
}

function get_houzez_property_feed_export_frequency( $key )
{
    $frequencies = get_houzez_property_feed_export_frequencies();
    
    return $frequencies[$key];
}