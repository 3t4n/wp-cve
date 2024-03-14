<?php

function fnsf_get_kontaktformularbuilder_settings_elements() {
    $editArray = array();

    array_push($editArray, 
        array(
            'editContentId' => 'backend_heading',
            'fields' => array(
                array(
                    'type' => 'textarea_',
                    'icon' => 'fas fa-tag',
                    'label' => __('Contact form title (backend)', 'funnelforms-free'),
                    'placeholder' => __('Enter title...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => true,
                        'htmlId' => 'af2_contact_form_backend_heading',
                        'empty_value' => __('Contact form title (backend)', 'funnelforms-free'),
                        'saveObjectId' => 'name'
                    )
                ),
            ),
        ),
    );


    return $editArray;
}