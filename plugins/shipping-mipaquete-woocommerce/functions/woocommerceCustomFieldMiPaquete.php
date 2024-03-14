<?php
// Add custom field to product shipping tab
function actionWoocommerceProductOptionsShipping(){ 
    $args = array(
        'label' => __( 'Valor declarado del producto', 'woocommerce' ),
        'placeholder' => __( 'Valor declarado de este producto', 'woocommerce' ),
        'id' => '_custom_declared_value',
        'type' => 'number',
        'desc_tip' => true,
        'description' => __( 'El valor por el cual la transportadora responderá en caso de indemnización', 'woocommerce' ),
        'min' => 1,
        'max' => 2000000
    );
    woocommerce_wp_text_input( $args );
}
add_action( 'woocommerce_product_options_shipping', 'actionWoocommerceProductOptionsShipping', 10, 0 );

// Save
function actionWoocommerceAdminProcessProductObject( $product ) {
    // Isset
    if( isset($_POST['_custom_declared_value']) ) {
        $product->update_meta_data( '_custom_declared_value', sanitize_text_field( $_POST['_custom_declared_value'] ) );
    }
}
add_action( 'woocommerce_admin_process_product_object', 'actionWoocommerceAdminProcessProductObject', 10, 1 );
