<?php

$bst_fields = array(
    'bst_tracking_provider_name' => array(
        'id' => 'bst_tracking_provider_name',
        'type' => 'text',
        'label' => '',
        'placeholder' => '',
        'description' => '',
        'class' => 'hidden'
    ),

    'bst_tracking_number' => array(
        'id' => 'bst_tracking_number',
        'type' => 'text',
        'label' => 'Tracking number',
        'placeholder' => '',
        'description' => '',
        'class' => ''
    ),

    'bst_tracking_shipdate' => array(
        'key' => 'tracking_ship_date',
        'id' => 'bst_tracking_shipdate',
        'type' => 'date',
        'label' => 'Date shipped',
        'placeholder' => 'YYYY-MM-DD',
        'description' => '',
        'class' => 'date-picker-field hidden-field'
    )

);
