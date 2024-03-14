<?php
if (!defined('ABSPATH'))
    exit; // Exit if accessed directly <div class="wrap">    
?><div class="wrap">
    <label for="direccion_remitente"><?=__('Dirección remitente:');?></label>
    <select id="direccion_remitente" name="direccion_remitente" <?php
            $direccion_seleccionada = false;
            if ($_GET['dr'] != '') {
                echo (" disabled");
                $direccion_seleccionada = true;
            }
            ?>>
                <?php
                $grupoimpultec_direccion_predeterminada = get_option('grupoimpultec_direccion_predeterminada');

                foreach ($direcciones_remitente as $direccion) {
                    if ($direccion_seleccionada && $direccion['id_direccion'] == $_GET['dr'] || ($grupoimpultec_direccion_predeterminada == $direccion['id_direccion'])) {
                        $selected = 'selected';
                    } else {
                        $selected = '';
                    }
                    echo('<option value="' . $direccion['id_direccion'] . '" ' . $selected . ' >' .
                    $direccion['codigo'] . ': ' . $direccion['nombre'] . ', ' . $direccion['direccion'] . ', ' . $direccion['codigo_postal'] . ' - ' . $direccion['nombre_pais'] .
                    '</option>');
                }
                ?>

    </select>

</div>