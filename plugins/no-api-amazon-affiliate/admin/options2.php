<?php
if (! current_user_can( 'manage_options')) wp_die(_e('No tienes permisos','no-api-amazon-affiliate'));
?>


<div class="wrap">
    <h1 class="wp-heading-inline"><?php echo get_admin_page_title() ?></h1>

    <form method="post" action="options.php">
        <?php
            settings_fields( 'naaa-amazon-options2' );
        ?>

        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row"><label for="naaa_num_items_row"><?php _e('Productos por fila', 'no-api-amazon-affiliate') ?></label></th>
                    <td>
                    <fieldset>
                        <input name="naaa_num_items_row" type="number" id="naaa_num_items_row" aria-describedby="naaa_num_items_row_description" value="<?php echo esc_attr(get_option('naaa_num_items_row')); ?>" class="regular-text">
                        <p class="description" id="naaa_num_items_row_description"><?php _e('Número de items por fila.', 'no-api-amazon-affiliate') ?></p>
                        <br>
                        <label for="naaa_responsive">
                            <input name="naaa_responsive" type="checkbox" id="naaa_responsive" value="1" <?php checked( '1', esc_attr(get_option( 'naaa_responsive',1)) ); ?> />
                            <?php _e('Visualización <strong>responsive</strong>. En dispositivos móviles con poca pantalla, prevalece la visualización del producto al número de items por fila.', 'no-api-amazon-affiliate') ?>
                        </label>
                        <div id="naaa_min_width_gridbox_group">
                            <input name="naaa_min_width_gridbox" type="number" id="naaa_min_width_gridbox" aria-describedby="naaa_min_width_gridbox_description" value="<?php echo esc_attr(get_option('naaa_min_width_gridbox', 145)); ?>" class="regular-text">
                            <p class="description" id="naaa_min_width_gridbox_description"><?php _e('Anchura mínima (pixels) de la caja del producto. (Default: 145px)', 'no-api-amazon-affiliate') ?></p>
                        </div>
                    </fieldset>


                    </td>
                </tr>
                <tr>
                    <th scope="row"><label ><?php _e('Conf. caja del producto', 'no-api-amazon-affiliate') ?></label></th>
                    <td>
                        <input name="naaa_bg_color" type="text" class="naaa_color_field" data-default-color="#ffffff" value="<?php echo esc_attr(get_option('naaa_bg_color','#ffffff')); ?>" /> 
                        <p class="description" id="naaa_bg_color_description"><?php _e('Color de fondo.', 'no-api-amazon-affiliate') ?></p>
                        <br>
                        <select name="naaa_border_size" id="naaa_border_size">
                            <option value="0" <?php selected(esc_attr(get_option('naaa_border_size')), 0); ?>><?php _e('Sin borde', 'no-api-amazon-affiliate') ?></option>
                            <option value="1" <?php selected(esc_attr(get_option('naaa_border_size')), 1); ?>><?php _e('Fino', 'no-api-amazon-affiliate') ?></option>
                            <option value="2" <?php selected(esc_attr(get_option('naaa_border_size')), 2); ?>><?php _e('Normal', 'no-api-amazon-affiliate') ?></option>
                            <option value="3" <?php selected(esc_attr(get_option('naaa_border_size')), 3); ?>><?php _e('Medio', 'no-api-amazon-affiliate') ?></option>
                            <option value="5" <?php selected(esc_attr(get_option('naaa_border_size')), 5); ?>><?php _e('Ancho', 'no-api-amazon-affiliate') ?></option>
                            <option value="10" <?php selected(esc_attr(get_option('naaa_border_size')), 10); ?>><?php _e('Gigante', 'no-api-amazon-affiliate') ?></option>
                            <option value="20" <?php selected(esc_attr(get_option('naaa_border_size')), 20); ?>><?php _e('Descomunal', 'no-api-amazon-affiliate') ?></option>
                        </select>
                        <p class="description" id="naaa_corner_description"><?php _e('Anchura del borde.', 'no-api-amazon-affiliate') ?></p>
                        <br>
                        <input name="naaa_border_color" type="text" class="naaa_color_field" data-default-color="#dad8d8" value="<?php echo esc_attr(get_option('naaa_border_color','#dad8d8')); ?>" /> 
                        <p class="description" id="naaa_border_color_description"><?php _e('Color del borde.', 'no-api-amazon-affiliate') ?></p>
                        <br>
                        <label for="naaa_product_color_show">
                            <input name="naaa_product_color_show" type="checkbox" id="naaa_product_color_show" value="1" <?php checked( '1', esc_attr(get_option( 'naaa_product_color_show', 0)) ); ?> />
                            <?php _e('Configurar color para el texto del producto. Desmar para usar el color por defecto del Tema.', 'no-api-amazon-affiliate') ?>
                        </label>
                        <div id="naaa_product_color_box">
                            <br>
                            <input name="naaa_product_color" type="text" class="naaa_color_field" data-default-color="#a94207" value="<?php echo esc_attr(get_option('naaa_product_color','#a94207')); ?>" /> 
                            <p class="description" id="naaa_product_color_description"><?php _e('Color de texto en la caja del producto.', 'no-api-amazon-affiliate') ?></p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="naaa_precio_text"><?php _e('Texto para indicar precio', 'no-api-amazon-affiliate') ?></label></th>
                    <td><input name="naaa_precio_text" type="text" id="naaa_precio_text" aria-describedby="naaa_precio_text_description" value="<?php echo esc_attr(get_option('naaa_precio_text')); ?>" class="regular-text">
                    <p class="description" id="naaa_precio_text_description"><?php _e('Etiqueta de precio, por defecto vacia.', 'no-api-amazon-affiliate') ?></p></td>
                </tr>
                <tr>
                    <th scope="row"><label><?php _e('Título', 'no-api-amazon-affiliate') ?></label></th>
                    <td>
                        <select name="naaa_heading_level" id="naaa_heading_level">
                            <option value="0" <?php selected(esc_attr(get_option('naaa_heading_level',0)), 0); ?>><?php _e('Ninguno', 'no-api-amazon-affiliate') ?></option>
                            <option value="1" <?php selected(esc_attr(get_option('naaa_heading_level',0)), 1); ?>><?php _e('H1', 'no-api-amazon-affiliate') ?></option>
                            <option value="2" <?php selected(esc_attr(get_option('naaa_heading_level',0)), 2); ?>><?php _e('H2', 'no-api-amazon-affiliate') ?></option>
                            <option value="3" <?php selected(esc_attr(get_option('naaa_heading_level',0)), 3); ?>><?php _e('H3', 'no-api-amazon-affiliate') ?></option>
                            <option value="4" <?php selected(esc_attr(get_option('naaa_heading_level',0)), 4); ?>><?php _e('H4', 'no-api-amazon-affiliate') ?></option>
                            <option value="5" <?php selected(esc_attr(get_option('naaa_heading_level',0)), 5); ?>><?php _e('H5', 'no-api-amazon-affiliate') ?></option>
                            <option value="6" <?php selected(esc_attr(get_option('naaa_heading_level',0)), 6); ?>><?php _e('H6', 'no-api-amazon-affiliate') ?></option>
                        </select>
                        <p class="description" id="naaa_heading_level_description"><?php _e('Heading Level del título del producto', 'no-api-amazon-affiliate') ?></p>
                        <br>
                        <input name="naaa_num_lines_title" type="number" id="naaa_num_lines_title" aria-describedby="naaa_num_lines_title_description" value="<?php echo esc_attr(get_option('naaa_num_lines_title')); ?>" class="regular-text">
                        <p class="description" id="naaa_num_lines_title_description"><?php _e('Número de líneas disponibles en el título.', 'no-api-amazon-affiliate') ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Mostrar precio', 'no-api-amazon-affiliate') ?></th>
                    <td> 
                        <fieldset>
                            <label for="naaa_precio_new_show">
                                <input name="naaa_precio_new_show" type="checkbox" id="naaa_precio_new_show" value="1" <?php checked( '1', esc_attr(get_option( 'naaa_precio_new_show' )) ); ?> />
                                <?php _e('Mostrar el <strong>precio actual</strong> si esta disponible.', 'no-api-amazon-affiliate') ?>
                            </label>
                            <br>
                            <label for="naaa_precio_old_show">
                                <input name="naaa_precio_old_show" type="checkbox" id="naaa_precio_old_show" value="1" <?php checked( '1', esc_attr(get_option( 'naaa_precio_old_show' )) ); ?> />
                                <?php _e('Mostrar el <strong>precio anterior</strong> si esta disponible.', 'no-api-amazon-affiliate') ?>
                            </label>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="naaa_button_text"><?php _e('Conf. Botón', 'no-api-amazon-affiliate') ?></label></th>
                    <td>
                        <input name="naaa_button_text" type="text" id="naaa_button_text" aria-describedby="naaa_button_text_description" value="<?php echo esc_attr(get_option('naaa_button_text')); ?>" class="regular-text">
                        <p class="description" id="naaa_button_text_description"><?php _e('Texto dentro del botón.', 'no-api-amazon-affiliate') ?></p>
                        
                        <br>
                        
                        <select name="naaa_corner" id="naaa_corner">
                            <option value="0" <?php selected(esc_attr(get_option('naaa_corner')), 0); ?>><?php _e('Cuadrado', 'no-api-amazon-affiliate') ?></option>
                            <option value="5" <?php selected(esc_attr(get_option('naaa_corner')), 5); ?>><?php _e('Suave', 'no-api-amazon-affiliate') ?></option>
                            <option value="10" <?php selected(esc_attr(get_option('naaa_corner')), 10); ?>><?php _e('Normal', 'no-api-amazon-affiliate') ?></option>
                            <option value="15" <?php selected(esc_attr(get_option('naaa_corner')), 15); ?>><?php _e('Grande', 'no-api-amazon-affiliate') ?></option>
                            <option value="20" <?php selected(esc_attr(get_option('naaa_corner')), 20); ?>><?php _e('Enorme', 'no-api-amazon-affiliate') ?></option>
                            <option value="50" <?php selected(esc_attr(get_option('naaa_corner')), 50); ?>><?php _e('Gigante', 'no-api-amazon-affiliate') ?></option>
                            <option value="100" <?php selected(esc_attr(get_option('naaa_corner')), 100); ?>><?php _e('Circular', 'no-api-amazon-affiliate') ?></option>
                        </select>
                        <p class="description" id="naaa_corner_description"><?php _e('Efecto redondeo de las esquinas.', 'no-api-amazon-affiliate') ?></p>
                        <br>
                        <label for="naaa_button_border_show">
                            <input name="naaa_button_border_show" type="checkbox" id="naaa_button_border_show" value="1" <?php checked( '1', esc_attr(get_option( 'naaa_button_border_show' )) ); ?> />
                            <?php _e('Mostrar borde.', 'no-api-amazon-affiliate') ?>
                        </label>
                        <br><br>
                        <input name="naaa_button_text_color" type="text" class="naaa_color_field" data-default-color="#000000" value="<?php echo esc_attr(get_option('naaa_button_text_color','#000000')); ?>" /> 
                        <p class="description" id="naaa_button_text_color_description"><?php _e('Color del texto.', 'no-api-amazon-affiliate') ?></p>
                        <br>
                        <input name="naaa_button_bg_color" type="text" class="naaa_color_field" data-default-color="#f7dfa5" value="<?php echo esc_attr(get_option('naaa_button_bg_color','#f7dfa5')); ?>" /> 
                        <p class="description" id="naaa_button_bg_color_description"><?php _e('Color de fondo.', 'no-api-amazon-affiliate') ?></p>
                        <br>
                        <label for="naaa_button_bg_color2_show">
                            <input name="naaa_button_bg_color2_show" type="checkbox" id="naaa_button_bg_color2_show" value="1" <?php checked( '1', esc_attr(get_option( 'naaa_button_bg_color2_show', 1)) ); ?> />
                            <?php _e('Agregar color gradiente al fondo del botón.', 'no-api-amazon-affiliate') ?>
                        </label>
                        <div id="naaa_button_bg_color2_box">
                            <br>
                            <input name="naaa_button_bg_color2" type="text" class="naaa_color_field" data-default-color="#f0c14b" value="<?php echo esc_attr(get_option('naaa_button_bg_color2','#f0c14b')); ?>" /> 
                            <p class="description" id="naaa_button_bg_color2_description"><?php _e('Color de fondo final.', 'no-api-amazon-affiliate') ?></p>
                        </div>
                        <br><br>
                        <label for="naaa_button_shadow_show">
                            <input name="naaa_button_shadow_show" type="checkbox" id="naaa_button_shadow_show" value="1" <?php checked( '1', esc_attr(get_option( 'naaa_button_shadow_show' )) ); ?> />
                            <?php _e('Mostrar sombra (efecto flotante).', 'no-api-amazon-affiliate') ?>
                        </label>
                        <div id="naaa_button_bg_color_shadow_box">
                            <br>
                            <input name="naaa_button_bg_color_shadow" type="text" class="naaa_color_field" data-default-color="#999" value="<?php echo esc_attr(get_option('naaa_button_bg_color_shadow','#999')); ?>" /> 
                            <p class="description" id="naaa_button_bg_color_shadow_description"><?php _e('Color del sombreado.', 'no-api-amazon-affiliate') ?></p>
                        <div>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Valoración', 'no-api-amazon-affiliate') ?></th>
                    <td> 
                        <fieldset>
                            <label for="naaa_valoracion_show">
                                <input name="naaa_valoracion_show" type="checkbox" id="naaa_valoracion_show" value="1" <?php checked( '1', esc_attr(get_option( 'naaa_valoracion_show' )) ); ?> />
                                <?php _e('Mostrar valoración.', 'no-api-amazon-affiliate') ?>
                            </label>
                            <br>
                            <label for="naaa_valoracion_desc_show" id="naaa_label_valoracion_desc_show">
                                <input name="naaa_valoracion_desc_show" type="checkbox" id="naaa_valoracion_desc_show" value="1" <?php checked( '1', esc_attr(get_option( 'naaa_valoracion_desc_show' )) ); ?> />
                                <?php _e('Mostrar en modo texto la valoracion.', 'no-api-amazon-affiliate') ?>
                            </label>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Comentarios', 'no-api-amazon-affiliate') ?></th>
                    <td> 
                        <fieldset>
                            <label for="naaa_comentarios_show">
                                <input name="naaa_comentarios_show" type="checkbox" id="naaa_comentarios_show" value="1" <?php checked( '1', esc_attr(get_option( 'naaa_comentarios_show' )) ); ?> />
                                <?php _e('Mostrar número de comentarios.', 'no-api-amazon-affiliate') ?>
                            </label>
                            <br><br>
                            <div id="naaa_comentarios_text_group">
                            <input name="naaa_comentarios_text" type="text" id="naaa_comentarios_text" aria-describedby="naaa_comentarios_text_description" value="<?php echo esc_attr(get_option( 'naaa_comentarios_text')); ?>" class="regular-text">
                            <p class="description" id="naaa_comentarios_text_description"><?php _e('Etiqueta de comentarios, p. ej. Reviews/Comentarios/Opiniones/[dejar vacio].', 'no-api-amazon-affiliate') ?></p>
                            </div>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Descuento', 'no-api-amazon-affiliate') ?></th>
                    <td> 
                        <fieldset>
                            <label for="naaa_discount_show">
                                <input name="naaa_discount_show" type="checkbox" id="naaa_discount_show" value="1" <?php checked( '1', esc_attr(get_option( 'naaa_discount_show' )) ); ?> />
                                <?php _e('Mostrar descuento si existe el precio anterior.', 'no-api-amazon-affiliate') ?>
                            </label>
                            <div id="naaa_discount_bg_color_box">
                                <br>
                                <input name="naaa_discount_bg_color" type="text" class="naaa_color_field" data-default-color="#d9534f" value="<?php echo esc_attr(get_option('naaa_discount_bg_color','#d9534f')); ?>" /> 
                                <p class="description" id="naaa_discount_bg_color_description"><?php _e('Color del descuento.', 'no-api-amazon-affiliate') ?></p>
                            <div>
                            <div id="naaa_discount_text_color_box">
                                <br>
                                <input name="naaa_discount_text_color" type="text" class="naaa_color_field" data-default-color="#ffffff" value="<?php echo esc_attr(get_option('naaa_discount_text_color','#ffffff')); ?>" /> 
                                <p class="description" id="naaa_discount_text_color_description"><?php _e('Color del texto descuento.', 'no-api-amazon-affiliate') ?></p>
                            <div>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Prime', 'no-api-amazon-affiliate') ?></th>
                    <td> 
                        <fieldset>
                            <label for="naaa_prime_show">
                                <input name="naaa_prime_show" type="checkbox" id="naaa_prime_show" value="1" <?php checked( '1', esc_attr(get_option( 'naaa_prime_show' )) ); ?> />
                                <?php _e('Mostrar etiqueta de los productos Prime.', 'no-api-amazon-affiliate') ?>
                            </label>
                        </fieldset>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php submit_button(); ?>
        
    </form>





</div>
