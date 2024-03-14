<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly <div class="wrap">    
?><div class="wrap">
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th><?=__('Pedido');?></th>
                <th><?=__('Cliente');?></th>
                <th><?=__('Origen');?></th>
                <th><?=__('Destino');?></th>
            </tr></thead>
        <tbody>
            <tr>
                <td><strong>#<?=$pedido->get_id()?></strong></td>
                <td><?= $pedido->get_shipping_first_name() . ' ' . $pedido->get_shipping_last_name(); ?></td>
                <td><?= $array_direccion_remitente['direccion'] . ' ' . $array_direccion_remitente['codigo_postal'] . ' - ' . $array_direccion_remitente['poblacion'] . ' ' . $datos_array['iso_pais_salida'] ?></td>
                <td><?= $shipping_address['address_1'] . ' ' . $shipping_address['address_2'] . ' ' . $shipping_address['postcode'] . ' - ' . $shipping_address['city'] . ' ' . $shipping_address['country'] ?></td>
            </tr>
        </tbody>
    </table>
</div>            


