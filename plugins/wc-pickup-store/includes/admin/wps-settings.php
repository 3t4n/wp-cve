<?php
$form_fields = array(
    'enabled' => array(
        'title' => __( 'Enable/Disable', 'wc-pickup-store' ),
        'type' => 'checkbox',
        'label' => __( 'Enable', 'wc-pickup-store' ),
        'default'  => 'yes',
        'description' => __( 'Enable/Disable shipping method', 'wc-pickup-store' ),
        'desc_tip'    => true
    ),
    'enable_store_select' => array(
        'title' => __( 'Enable stores in checkout', 'wc-pickup-store' ),
        'type' => 'checkbox',
        'label' => __( 'Enable', 'wc-pickup-store' ),
        'default'  => 'no',
        'description' => __( 'Shows select field to pick a store in checkout', 'wc-pickup-store' ),
        'desc_tip'    => true
    ),
    'title' => array(
        'title' => __( 'Shipping Method Title', 'wc-pickup-store' ),
        'type' => 'text',
        'description' => __( 'Label that appears in checkout options', 'wc-pickup-store' ),
        'default' => __( 'Pickup Store', 'wc-pickup-store' ),
        'desc_tip'    => true
    ),
    'select_first_option' => array(
        'title' => __( 'Select first option text', 'wc-pickup-store' ),
        'type' => 'text',
        'description' => __( 'Text to be displayed as first option of the stores dropdown', 'wc-pickup-store' ),
        'default' => __( 'Select a store', 'wc-pickup-store' ),
        'desc_tip'    => true
    ),
    'costs_type' => array(
        'title' => __( 'Shipping Costs Type', 'wc-pickup-store' ),
        'type' => 'select',
        'class' => 'wc-enhanced-select',
        'description' => __( 'Choose a shipping costs type to calculate Pick up store costs. Use None to deactivate shipping store costs', 'wc-pickup-store' ),
        'default' => 'flat',
        'options' => array(
            'none' => __('None', 'wc-pickup-store'),
            'flat' => __('Flat Rate', 'wc-pickup-store'),
            'percentage' => __('Percentage', 'wc-pickup-store')
        ),
        'desc_tip'    => true
    ),
    'costs' => array(
        'title' => __( 'Shipping Costs', 'wc-pickup-store' ),
        'type' => 'text',
        'description' => __( 'Adds main shipping cost to store pickup', 'wc-pickup-store' ),
        'default' => 0,
        'placeholder' => '0',					
        'desc_tip'    => true
    ),
    'costs_per_store' => array(
        'title' => __( 'Enable costs per store', 'wc-pickup-store' ),
        'type' => 'checkbox',
        'label' => __( 'Enable', 'wc-pickup-store' ),
        'default'  => 'no',
        'description' => __( 'Allows to add shipping costs by store that will override the main shipping cost.', 'wc-pickup-store' ),
        'desc_tip'    => true
    ),
    'stores_order_by' => array(
        'title' => __( 'Order Stores by', 'wc-pickup-store' ),
        'type' => 'select',
        'class' => 'wc-enhanced-select',
        'description' => __( 'Choose what order the stores will be shown', 'wc-pickup-store' ),
        'default' => 'title',
        'options' => array(
            'title' => 'Title',
            'date' => 'Date',
            'ID' => 'ID',
            'rand' => 'Random'
        ),
        'desc_tip'    => true
    ),
    'stores_order' => array(
        'title' => __( 'Order', 'wc-pickup-store' ),
        'type' => 'select',
        'class' => 'wc-enhanced-select',
        'description' => __( 'Choose what order the stores will be shown', 'wc-pickup-store' ),
        'default' => 'DESC',
        'options' => array(
            'DESC' => 'DESC',
            'ASC' => 'ASC'
        ),
        'desc_tip'    => true
    ),
    'store_default' => array(
        'type' => 'store_default',
        'description' => __( 'Choose a default store to Checkout', 'wc-pickup-store' ),
        'desc_tip'    => true
    ),
    'checkout_notification' => array(
        'title' => __( 'Checkout notification', 'wc-pickup-store' ),
        'type' => 'textarea',
        'description' => __( 'Message that appears next to shipping options on the Checkout page', 'wc-pickup-store' ),
        'default' => __( '', 'wc-pickup-store' ),
        'desc_tip'    => true
    ),
    'hide_store_details' => array(
        'title' => __( 'Hide store details on Checkout', 'wc-pickup-store' ),
        'type' => 'checkbox',
        'label' => __( 'Hide', 'wc-pickup-store' ),
        'default'  => 'no',
        'description' => __( 'Hide selected store details on the Checkout page.', 'wc-pickup-store' ),
        'desc_tip'    => true
    ),
    'country_filtering' => array(
        'title' => __( 'Disable store filtering by Country', 'wc-pickup-store' ),
        'type' => 'checkbox',
        'label' => __( 'Disable', 'wc-pickup-store' ),
        'default'  => 'no',
        'description' => __( 'By default, stores will be filtered by country on the Checkout.', 'wc-pickup-store' ),
        'desc_tip'    => true
    ),
    'external_bootstrap' => array(
        'title' => 'Bootstrap CSS',
        'type' => 'select',
        'options' => array(
            'disable' => __( 'Disable', 'wc-pickup-store' ),
            'version_3' => sprintf( __('Use version %s', 'wc-pickup-store'), '3.3.7' ),
            'version_4' => sprintf( __('Use version %s', 'wc-pickup-store'), '4.5.2' )
        ),
        'default'  => 'version_3',
        'description' => __( 'Choose for external Bootstrap library version. Use Disable to disable the library.', 'wc-pickup-store' ),
        'desc_tip'    => true
    ),
    'external_font_awesome' => array(
        'title' => 'Font Awesome',
        'type' => 'select',
        'options' => array(
            'disable' => __( 'Disable', 'wc-pickup-store' ),
            'version_4' => sprintf( __('Use version %s', 'wc-pickup-store'), '4.7.0' ),
            'version_5' => sprintf( __('Use version %s', 'wc-pickup-store'), '5.15.2' )
        ),
        'default'  => 'version_4',
        'description' => __( 'Choose for external Font Awesome library version. Use Disable to disable the library.', 'wc-pickup-store' ),
        'desc_tip'    => true
    ),
    'local_css' => array(
        'title' => __( 'Disable local css', 'wc-pickup-store' ),
        'type' => 'checkbox',
        'label' => __( 'Disable', 'wc-pickup-store' ),
        'default'  => 'no',
        'description' => __( 'Disable WC Pickup Store css library.', 'wc-pickup-store' ),
        'desc_tip'    => true
    ),
    'disable_select2' => array(
        'title' => __( 'Disable select2 on Checkout', 'wc-pickup-store' ),
        'type' => 'checkbox',
        'label' => __( 'Disable', 'wc-pickup-store' ),
        'default'  => 'no',
        'description' => __( 'Disable select2 library for stores dropdown on Checkout page.', 'wc-pickup-store' ),
        'desc_tip'    => true
    ),
);

if ( wc_tax_enabled() ) {
    $form_fields = wp_parse_args( array(
        'tax_configuration_details' => array(
            'title'			=> __( 'Configure tax options', 'wc-pickup-store' ),
            'type'			=> 'title',
            'description'	=> __( 'Configure the usage for taxes based on shipping method or per stores', 'wc-pickup-store' )
        ),
        'wps_tax_status'    	=> array(
            'title'			=> __( 'Tax status', 'wc-pickup-store' ),
            'type' => 'select',
            'options' => array(
                'none'      => __( 'None', 'wc-pickup-store' ),
                'taxable'   => __( 'Taxable', 'wc-pickup-store' ),
                'taxable_per_store' => __( 'Taxable per store', 'wc-pickup-store' )
            ),
            'default'       => 'none',
            'description' => __( 'Use Taxable to enable tax calculation for the shipping method. Use Taxable per store to enable tax calculation based on store tax configuration. Use none to disable tax calculations. Avoid using Taxable per store when costs per store are disabled.', 'wc-pickup-store' ),
            'desc_tip'      => true,
        )
    ), $form_fields );
}

$form_fields['plugin_version'] = array(
    'type' => 'plugin_version',
);

return $form_fields;