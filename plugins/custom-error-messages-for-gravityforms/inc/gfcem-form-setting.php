<?php

if (!defined('ABSPATH')) {
	exit;
}

add_filter( 'gform_form_settings_fields', function ( $fields, $form ) {

    $fields['gfcem'] = [];
    $fields['gfcem']['title'] = __('Custom Error Messages', 'custom-error-messages-for-gravityforms');

    $fields['gfcem']['fields'] = [
        [
            'type' => 'toggle',
            'name' => 'gfcem_default_enabled',
            'label' => __('Enable Custom Error Message', 'custom-error-messages-for-gravityforms'),
            'description' => __('Enable custom error messages for all supported fields', 'custom-error-messages-for-gravityforms'),
        ],
        [
            'type' => 'text',
            'name' => 'gfcem_default_required',
            'label' => __('Required Error Message', 'custom-error-messages-for-gravityforms')
        ],
        [
            'type' => 'text',
            'name' => 'gfcem_default_unique',
            'label' => __('Unique Error Message', 'custom-error-messages-for-gravityforms')
        ],
        [
            'type' => 'text',
            'name' => 'gfcem_default_valid_email',
            'label' => __('Valid Email Error Message', 'custom-error-messages-for-gravityforms')
        ],
        [
            'type' => 'text',
            'name' => 'gfcem_default_confirm_email',
            'label' => __('Confirm Email Message', 'custom-error-messages-for-gravityforms')
        ],
    ];
  
    return $fields;
}, 10, 2 );

