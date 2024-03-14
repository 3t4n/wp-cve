<?php
function cwmp_register_shipment_arrival_order_status() {
    register_post_status( 'wc-pedido-enviado', array(
        'label'                     => 'Pedido Enviado',
        'public'                    => true,
        'show_in_admin_status_list' => true,
        'show_in_admin_all_list'    => true,
        'exclude_from_search'       => false,
        'label_count'               => _n_noop( 'Pedidos Enviados <span class="count">(%s)</span>', 'Pedidos Enviados <span class="count">(%s)</span>' )
    ) );
    register_post_status( 'wc-separacao', array(
        'label'                     => 'Em Separação',
        'public'                    => true,
        'show_in_admin_status_list' => true,
        'show_in_admin_all_list'    => true,
        'exclude_from_search'       => false,
        'label_count'               => _n_noop( 'Em Separação <span class="count">(%s)</span>', 'Em Separação <span class="count">(%s)</span>' )
    ) );
}
add_action( 'init', 'cwmp_register_shipment_arrival_order_status' );
function cwmp_add_awaiting_shipment_to_order_statuses( $order_statuses ) {
    $new_order_statuses = array();
    foreach ( $order_statuses as $key => $status ) {
        $new_order_statuses[ $key ] = $status;
        if ( 'wc-processing' === $key ) {
            $new_order_statuses['wc-pedido-enviado'] = 'Pedido Enviado';
            $new_order_statuses['wc-separacao'] = 'Em Separação';
        }
    }
    return $new_order_statuses;
}
add_filter( 'wc_order_statuses', 'cwmp_add_awaiting_shipment_to_order_statuses' );