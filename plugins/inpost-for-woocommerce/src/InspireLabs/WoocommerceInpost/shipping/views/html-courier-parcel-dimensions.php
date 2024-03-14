<?php /** @var ShipX_Shipment_Model $shipment */

use InspireLabs\WoocommerceInpost\EasyPack;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Model;


$size_from_main_settings = [];

if( 'yes' === get_option('easypack_set_default_courier_dimensions') ) {
    $size_from_main_settings = get_option('easypack_default_courier_dimensions');
}

$length_value = 0;
$width_value  = 0;
$height_value = 0;
$weight_value = 0;
$non_standard_value = 'no';

if( get_post_meta( $order_id, '_easypack_parcel_length', true ) ) {
    $length_value = get_post_meta( $order_id, '_easypack_parcel_length', true );
} else {
    $length_value = isset($size_from_main_settings['length']) && ! empty( $size_from_main_settings['length'] )
    ? $size_from_main_settings['length']
    : $parcel->getDimensions()->getLength();
}

if( get_post_meta( $order_id, '_easypack_parcel_width', true ) ) {
    $width_value = get_post_meta( $order_id, '_easypack_parcel_width', true );
} else {
    $width_value = isset($size_from_main_settings['width']) && ! empty( $size_from_main_settings['width'] )
    ? $size_from_main_settings['width']
    : $parcel->getDimensions()->getWidth();
}

if( get_post_meta( $order_id, '_easypack_parcel_height', true ) ) {
    $height_value = get_post_meta( $order_id, '_easypack_parcel_height', true );
} else {
$height_value = isset($size_from_main_settings['height']) && ! empty( $size_from_main_settings['height'] )
    ? $size_from_main_settings['height']
    : $parcel->getDimensions()->getHeight();
}

woocommerce_form_field('parcel_length', $length, $length_value);
woocommerce_form_field('parcel_width', $width, $width_value);
woocommerce_form_field('parcel_height', $height, $height_value);

$weight = [
    'type' => 'number',
    'class' => ['easypack_parcel'],
    'input_class' => ['easypack_parcel'],
    'label' => __('Weight:', 'woocommerce-inpost')
        . ' ' . $parcel->getWeight()->getUnit(),
    'required' => true,
];

if( get_post_meta( $order_id, '_easypack_parcel_weight', true ) ) {
    $weight_value = get_post_meta( $order_id, '_easypack_parcel_weight', true );
} else {
    $weight_value = isset($size_from_main_settings['weight']) && ! empty( $size_from_main_settings['weight'] )
        ? $size_from_main_settings['weight']
        : EasyPack_Helper()->get_order_weight( wc_get_order( $order_id ) );
}

woocommerce_form_field('parcel_weight', $weight, $weight_value);

$non_standard = [
    'type' => 'select',
    'options' => [
        'no'  =>  __('no', 'woocommerce-inpost'),
        'yes' =>  __('yes', 'woocommerce-inpost'),

    ],
    'class' => ['easypack_parcel'],
    'input_class' => ['easypack_parcel'],
    'label' => __('Non standard', 'woocommerce-inpost'),
    'required' => true,
];

if( get_post_meta( $order_id, '_easypack_parcel_non_standard', true ) ) {
    $non_standard_value = get_post_meta( $order_id, '_easypack_parcel_non_standard', true );
} else {

    $non_standard_value = isset($size_from_main_settings['non_standard']) && ! empty( $size_from_main_settings['non_standard'] )
        ? $size_from_main_settings['non_standard']
        : $parcel->getTemplate();
}

woocommerce_form_field('parcel_non_standard', $non_standard, $non_standard_value);
