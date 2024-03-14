<?php

if (!defined('FW')) {
    die('Forbidden');
}

$options = array(
    'vehicle_title' => array(
        'label' => __('HQ Title', 'fw'),
        'desc' => __('HQ Enter the title . %s for make text normal.  if you dont use %s text will be display bold', 'fw'),
        'type' => 'text',
        'value' => 'HQ Vehicle Models - Our rental fleet at a glance'
    ),
    'vehicle_models' => array(
        'type' => 'addable-popup',
        'label' => __('Vehicle Models', 'fw'),
        'desc' => __('Add Vehicle Models', 'fw'),
        'template' => '{{=model_name}}',
        'popup-options' => array(
            'model_name' => array(
                'label' => __('Model Name', 'fw'),
                'desc' => __('Add Vehicle Model Name', 'fw'),
                'type' => 'text',
            ),
            'image' => array(
                'type' => 'upload',
                'value' => '',
                'label' => 'Upload image.',
                'desc' => __('Upload model image.  Size 500px 280px', 'fw'),
            ),
            'rent_rate' => array(
                'label' => __('Rate', 'fw'),
                'desc' => __('Add rent per day', 'fw'),
                'type' => 'text',
                'value' => '$ 37.40'
            ),
            'perday' => array(
                'label' => __('perday', 'fw'),
                'desc' => __('Add rent per day', 'fw'),
                'type' => 'text',
                'value' => 'rent per day'
            ),
            'vehicle_details' => array(
                'type' => 'addable-popup',
                'label' => __('Vehicle Feature', 'fw'),
                'desc' => __('Add Vehicle Feature', 'fw'),
                'template' => '{{=feature}}',
                'popup-options' => array(

                    'feature' => array(
                        'type' => 'text',
                        'value' => '',
                        'label' => 'feature',
                    ),
                    'feature_details' => array(
                        'label' => __('Feature Details', 'fw'),
                        'desc' => __('Feature Details', 'fw'),
                        'type' => 'text',
                    ),
                ),
            ),
//          'reserve_button' => array(
//              'label' => __( 'Link', 'fw' ),
//              'desc' => __( 'Add rent per day', 'fw' ),
//              'type' => 'text',
//              'value' => '$ 37.40 rent per day'
//          )
        ),
    ),
);
