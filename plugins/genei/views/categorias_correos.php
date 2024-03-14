<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly <div class="wrap">    
?><section id="seccion_categorias_correos">
    <div class="row">
        <div id="fila_categoria_correos" class="form-group col-12 col-sm-4">
            <label for="categorias_envios"><?=__('Categoría (requerido):');?></label>
            <select name="categorias_envios" title="<?=__('Categorias envíos');?>" id="categorias_envios" class="form-control">
                <option value=""><?=__('Seleccione categoría');?></option>    
                <?php
                if (count($datos_array['rs_categorias_correos']) > 0) {
                    foreach ($datos_array['rs_categorias_correos'] as $fila_categoria_correos) {
                        ?>
                        <option value="<?= $fila_categoria_correos['codigo_mercancia'] ?>"><?= $fila_categoria_correos['mercancia'] ?></option>
                        <?php
                    }
                }
                ?>
            </select>
        </div>
        <div id="fila_valor_correos" class="form-group col-12 col-sm-4">
            <label for="valor_mercancia"><?=__('Valor de la mercancía:');?></label>
            <div class="input-group">
                <input type="text" class="form-control valor_contenido" id="valor_mercancia" value="1.00" name="valor_mercancia" aria-describedby="addon-basico-eur2" value="" required>
                <div class="input-group-append">
                    <span class="input-group-text addon-basico" id="addon-basico-eur2">€</span>
                </div>
            </div>
        </div>
    </div>
</section>