<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly <div class="wrap">    
?><table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th><?=__('Contenido');?></th>
                <th><?=__('Valor');?></th>
                <th><?=__('Taric');?></th>                
            </tr></thead>
<?php
$pedido = wc_get_order($numero_pedido_wp);
$items = $pedido->get_items();
$contador = 1;
    foreach ($items as $item) {
        $producto = $item->get_product();
        echo '<tr>';
        echo '<td><input type = "text" id="mercancia_aduana_contenido_'.$contador.'" name="mercancia_aduana_contenido_'.$contador.'" value="'.$item->get_name().'"></td>';        
        echo '<td><input type = "text" class="moneda" id="mercancia_aduana_valor_'.$contador.'" name="mercancia_aduana_valor_'.$contador.'" value="1.00"></td>';        
        echo '<td><input type = "text" id="mercancia_aduana_taric_'.$contador.'" name="mercancia_aduana_taric_'.$contador.'" value=""></td>';
        echo '</tr>';
        $contador++;
    }
    ?>
</tbody>
</table>