<?php
    if (! current_user_can( 'manage_options')) wp_die(_e('No tienes permisos','no-api-amazon-affiliate'));
    require_once(NAAA_PATH_INC.'naaa-functions.php');

    //save data
    if(isset($_POST['naaa_btn_guardar'])){
        $naaa_asin_item = sanitize_text_field($_POST['naaa_asin_item']);
        $naaa_market = sanitize_key($_POST['naaa_market']);

        //find element info, and save if is new.
        if (naaa_is_valid_asin_item($naaa_asin_item) && naaa_is_valid_market($naaa_market)){
            naaa_get_item_data($naaa_asin_item, $naaa_market);
        }
        
    }
    
    if(isset($_POST['naaa_btn_guardar_title'])){
        //SAVE TITLE
        $naaa_id_item_amazon = trim(sanitize_text_field($_POST['naaa_id_item_amazon']));
        $naaa_title_manual_item = trim(sanitize_text_field($_POST['naaa_title_manual_item']));

        if (naaa_is_valid_number($naaa_id_item_amazon) && naaa_is_valid_title_item($naaa_title_manual_item)){
            naaa_update_item_title($naaa_id_item_amazon, $naaa_title_manual_item);
        }

        //SABE ALT TEXT
        $naaa_alt_manual_item = trim(sanitize_text_field($_POST['naaa_alt_manual_item']));

        if (naaa_is_valid_number($naaa_id_item_amazon) && naaa_is_valid_alt_item($naaa_alt_manual_item)){
            naaa_update_item_alt($naaa_id_item_amazon, $naaa_alt_manual_item);
        }
        

        //DELETE DATA AND SAVE NEW LINKS OTHER AFFILIATE
        /*
        naaa_delete_other_affiliate_link($naaa_id_item_amazon);
        foreach ($_POST['naaa_other_affiliate_link'] as $key => $naaa_other_affiliate_link){
            $naaa_link_other_affiliate_button = $_POST['naaa_other_affiliate_link_button'][$key];
            
            $naaa_other_affiliate_link = trim(esc_url_raw($naaa_other_affiliate_link, array('http', 'https')));
            $naaa_link_other_affiliate_button = trim(sanitize_text_field($naaa_link_other_affiliate_button));

            if (naaa_is_valid_number($naaa_link_other_affiliate_button) && naaa_is_valid_url($naaa_other_affiliate_link) ){
                naaa_insert_other_affiliate_link($naaa_id_item_amazon, $naaa_other_affiliate_link, $naaa_link_other_affiliate_button);
            }
        }
        */
        
    }

    //load data
    $query = "SELECT * FROM {$wpdb->prefix}naaa_item_amazon";
    $lista_items_amazon = $wpdb->get_results($query, ARRAY_A);
    if(empty($lista_items_amazon)) $lista_items_amazon = array();

?>

<div class="wrap">
    <h1 class="wp-heading-inline"> <?php echo get_admin_page_title() ?></h1>
    <a id="naaa_button_add_item_amazon" class="page-title-action"><?php _e('Añadir producto', 'no-api-amazon-affiliate') ?></a>
    <br><br>

    <table class="wp-list-table widefat fixed striped pages">
        <thead>
            <tr>
            <th><?php _e('Imagen Url', 'no-api-amazon-affiliate') ?></th>
            <th style="width:30%"><?php _e('Título', 'no-api-amazon-affiliate') ?></th>
            <th><?php _e('ASIN', 'no-api-amazon-affiliate') ?></th>
            <th><?php _e('Precio', 'no-api-amazon-affiliate') ?></th>
            <th><?php _e('Precio anterior', 'no-api-amazon-affiliate') ?></th>
            <th><?php _e('Valoración', 'no-api-amazon-affiliate') ?></th>
            <th><?php _e('Opiniones', 'no-api-amazon-affiliate') ?></th>
            <th><?php _e('Prime', 'no-api-amazon-affiliate') ?></th>
            <th><?php _e('Mercado', 'no-api-amazon-affiliate') ?></th>
            <th><?php _e('Actualizado (hora-servidor)', 'no-api-amazon-affiliate') ?></th>
            <th><?php _e('Acciones', 'no-api-amazon-affiliate') ?></th>
            </tr>
        </thead>
        <tbody id="item-list">
            <?php
                $label_edit = __('Editar', 'no-api-amazon-affiliate');
                $label_update = __('Actualizar', 'no-api-amazon-affiliate');
                $label_delete = __('Eliminar', 'no-api-amazon-affiliate');
                foreach ($lista_items_amazon as $item => $value) {
                    echo '
                    <tr>
                        <td><img src="'.esc_url($value['imagen_url']).'_AC_AC_SR80,80_.jpg" height="80" width="80" alt="'.esc_html($value['imagen_url']).'_AC_AC_SR80,80_.jpg"></td>
                        <td>
                            '.naaa_get_html_title_list(esc_html($value['titulo']) , esc_html($value['titulo_manual'])).'
                            <br><a target="_blank" rel="sponsored,nofollow" href="'.esc_url(naaa_get_amazon_url_product($value['asin'], $value['mercado'])).'">'.esc_html__( 'Ver en Amazon', 'no-api-amazon-affiliate' ).'<span class="dashicons dashicons-external"></span></a>
                        </td>
                        <td><strong>'.esc_html($value['asin']).'</strong></td>
                        <td>'.esc_html($value['precio']).'</td>
                        <td>'.esc_html($value['precio_anterior']).'</td>
                        <td>'.esc_html($value['valoracion']).'</td>
                        <td>'.esc_html($value['opiniones']).'</td>
                        <td>'.esc_html($value['prime']).'</td>
                        <td>'.esc_html($value['mercado']).'</td>
                        <td>'.esc_html($value['fecha_ultimo_update']).'</td>
                        <td>
                            <a data-asin="'.esc_html($value['asin']).'" data-id="'.esc_html($value['id_naaa_item_amazon']).'" data-title="'.esc_html($value['titulo']).'" data-title_manual="'.esc_html($value['titulo_manual']).'" data-alt_manual="'.esc_html($value['alt_manual']).'"class="button naaa_btn naaa_btn_edit" title="'.$label_edit.'"><span class="dashicons dashicons-edit"></span></a>
                            <a data-asin="'.esc_html($value['asin']).'" data-market="'.esc_html($value['mercado']).'" class="button naaa_btn naaa_btn_update" title="'.$label_update.'"><span class="dashicons dashicons-update"></span></a>
                            <a data-asin="'.esc_html($value['asin']).'" data-market="'.esc_html($value['mercado']).'" class="button naaa_btn naaa_btn_delete" title="'.$label_delete.'"><span class="dashicons dashicons-trash"></span></a>
                        </td>
                    </tr>
                    ';
                }
            ?>
            
        </tbody>
    </table>
</div>

<!-- Modal Add Product-->
<div class="modal fade" id="addAmazonItemModal" tabindex="-1" aria-labelledby="addAmazonItemModalLabel" aria-hidden="true">
  <div class="modal-dialog">
  <form method="post" role="form">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addAmazonItemModalLabel"><?php _e('Insertar nuevo producto Amazon', 'no-api-amazon-affiliate') ?></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
            <p>
                <?php _e('Nota: No es necesario que agregues los productos de forma manual. <strong>Se agregan automáticamente</strong> cuando se usan en el shortcode.', 'no-api-amazon-affiliate') ?>
            </p>
            <div class="form-group row">
                <label for="naaa_asin_item" class="col-sm-4"><?php _e('Asin del producto:', 'no-api-amazon-affiliate') ?></label>
                <div class="col-sm-8">
                    <input type="text" id="naaa_asin_item" name="naaa_asin_item" style="width:100%" placeholder="<?php _e('Ej. B002UP16AQ', 'no-api-amazon-affiliate') ?>">
                </div>
                <br><br>
                <label for="naaa_amazon_country" class="col-sm-4"><?php _e('Tienda Amazon:', 'no-api-amazon-affiliate') ?></label>
                <div class="col-sm-8">
                    <select name="naaa_market" id="amazon_country">
                        <option value="ca" <?php selected(esc_attr(get_option('naaa_amazon_country', 'es')), 'ca'); ?>>(CA) <?php _e('Canadá', 'no-api-amazon-affiliate') ?></option>
                        <option value="de" <?php selected(esc_attr(get_option('naaa_amazon_country', 'es')), 'de'); ?>>(DE) <?php _e('Alemania', 'no-api-amazon-affiliate') ?></option>
                        <option value="es" <?php selected(esc_attr(get_option('naaa_amazon_country', 'es')), 'es'); ?>>(ES) <?php _e('España', 'no-api-amazon-affiliate') ?></option>
                        <option value="fr" <?php selected(esc_attr(get_option('naaa_amazon_country', 'es')), 'fr'); ?>>(FR) <?php _e('Francia', 'no-api-amazon-affiliate') ?></option>
                        <option value="gb" <?php selected(esc_attr(get_option('naaa_amazon_country', 'es')), 'gb'); ?>>(GB) <?php _e('Reino Unido', 'no-api-amazon-affiliate') ?></option>
                        <option value="it" <?php selected(esc_attr(get_option('naaa_amazon_country', 'es')), 'it'); ?>>(IT) <?php _e('Italia', 'no-api-amazon-affiliate') ?></option>
                        <option value="jp" <?php selected(esc_attr(get_option('naaa_amazon_country', 'es')), 'jp'); ?>>(JP) <?php _e('Japón', 'no-api-amazon-affiliate') ?></option>
                        <option value="us" <?php selected(esc_attr(get_option('naaa_amazon_country', 'es')), 'us'); ?>>(US) <?php _e('Estados Unidos', 'no-api-amazon-affiliate') ?></option>
                    </select>
                </div>
                <br>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php _e('Cerrar', 'no-api-amazon-affiliate') ?></button>
        <button type="submit" class="btn btn-primary" name="naaa_btn_guardar" id="naaa_btn_guardar"><?php _e('Insertar producto', 'no-api-amazon-affiliate') ?></button>
      </div>
    </div>
    </form>
  </div>
</div>
<!-- Modal Edit Product-->
<div class="modal fade" id="editAmazonItemModal" tabindex="-1" aria-labelledby="editAmazonItemModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
  <form method="post" role="form">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editAmazonItemModalLabel"><?php _e('Editando producto', 'no-api-amazon-affiliate') ?> <span id="editAmazonItemAsinTitle"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul class="nav nav-tabs" id="tabAmazonItemOptions" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="naaa_title_item_area_tab" data-bs-toggle="tab" data-bs-target="#naaa_title_item_area" type="button" role="tab" aria-controls="naaa_title_item_area" aria-selected="true"><?php _e('Título', 'no-api-amazon-affiliate') ?></button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="naaa_alt_item_area_tab" data-bs-toggle="tab" data-bs-target="#naaa_alt_item_area" type="button" role="tab" aria-controls="naaa_alt_item_area" aria-selected="false"><?php _e('Alt Image', 'no-api-amazon-affiliate') ?></button>
            </li>
            <!--
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="naaa_other_affiliate_tab" data-bs-toggle="tab" data-bs-target="#naaa_other_affiliate" type="button" role="tab" aria-controls="naaa_other_affiliate" aria-selected="false"><?php _e('Otros Links Afiliados', 'no-api-amazon-affiliate') ?></button>
            </li>
            -->
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="naaa_title_item_area" role="tabpanel" aria-labelledby="naaa_title_item_area_tab">
                <br>
                <div class="form-group row">
                    <label for="naaa_title_item" class="col-sm-3"><?php _e('Título estandar:', 'no-api-amazon-affiliate') ?></label>
                    <div class="col-sm-9">
                        <textarea id="naaa_title_item" name="naaa_title_item" style="width:100%" disabled rows="4"></textarea>
                    </div>
                    <br><br>
                    <label for="naaa_title_manual_item" class="col-sm-3"><?php _e('Título manual:', 'no-api-amazon-affiliate') ?></label>
                    <div class="col-sm-9">
                        <textarea id="naaa_title_manual_item" name="naaa_title_manual_item" style="width:100%" rows="4"  maxlength="255" placeholder="<?php _e('Ej. Título alternativo a mostrar', 'no-api-amazon-affiliate') ?>"></textarea>
                    </div>
                    <br>
                    <input type="hidden" id="naaa_id_item_amazon" name="naaa_id_item_amazon">
                </div>
            </div>
            <div class="tab-pane fade" id="naaa_alt_item_area" role="tabpanel" aria-labelledby="naaa_alt_item_area_tab">
                <br>
                <div class="form-group row">
                    <label for="naaa_alt_item" class="col-sm-3"><?php _e('Alt de la imagen estandar:', 'no-api-amazon-affiliate') ?></label>
                    <div class="col-sm-9">
                        <textarea id="naaa_alt_item" name="naaa_alt_item" style="width:100%" disabled rows="4"></textarea>
                    </div>
                    <br><br>
                    <label for="naaa_alt_manual_item" class="col-sm-3"><?php _e('Alt de la imagen manual:', 'no-api-amazon-affiliate') ?></label>
                    <div class="col-sm-9">
                        <textarea id="naaa_alt_manual_item" name="naaa_alt_manual_item" style="width:100%" rows="4"  maxlength="255" placeholder="<?php _e('Ej. Alt Imagen alternativo a mostrar', 'no-api-amazon-affiliate') ?>"></textarea>
                    </div>
                    <br>
                </div>
            </div>
            <!--
            <div class="tab-pane fade" id="naaa_other_affiliate" role="tabpanel" aria-labelledby="naaa_other_affiliate_tab">
                <br>
                <div id="naaa_other_affiliate_div">
                    <div class="form-group row">
                        <label for="naaa_other_affiliate_link" class="col-form-label col-sm-1"><?php _e('Link:', 'no-api-amazon-affiliate') ?></label>
                        <div class="col-sm-6">
                            <input type="text" name="naaa_other_affiliate_link[]" id="naaa_other_affiliate_link" class="form-control name_list">
                        </div>
                        <div class="col-sm-3">
                            <select name="naaa_other_affiliate_link_button[]" id="naaa_other_affiliate_link_button" class="form-control name_list">
                                <option value="1">Botón 1</option>
                                <option value="2" selected>Botón 2</option>
                                <option value="3">Botón 3</option>
                                <option value="4">Botón 4</option>
                                <option value="5">Botón 5</option>
                                <option value="6">Botón 6</option>
                                <option value="7">Botón 7</option>
                                <option value="8">Botón 8</option>
                                <option value="9">Botón 9</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <button name="naaa_other_affiliate_add" id="naaa_other_affiliate_add" class="btn btn-success" >Agregar</button>
                        </div>
                    </div>
                </div>
            </div>
            -->
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php _e('Cerrar', 'no-api-amazon-affiliate') ?></button>
        <button type="submit" class="btn btn-primary" name="naaa_btn_guardar_title" id="naaa_btn_guardar_title"><?php _e('Guardar', 'no-api-amazon-affiliate') ?></button>
      </div>
    </div>
   </form>
  </div>
</div>