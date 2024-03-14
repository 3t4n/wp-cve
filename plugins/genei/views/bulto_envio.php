<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly <div class="wrap">    
?><tr>
    <td><?= $contador_bultos ?></td>
    <td><?= $bulto['cantidad'] ?></td>
    <td><?= $bulto['descripcion'] ?></td>
    <td class="alinear_derecha"><?= number_format($bulto['peso'],2) ?></td>
    <td class="alinear_derecha"><?= number_format($bulto['alto'],2) ?></td>
    <td class="alinear_derecha"><?= number_format($bulto['ancho'],2) ?></td>
    <td class="alinear_derecha"><?= number_format($bulto['largo'],2) ?></td>

</tr>




