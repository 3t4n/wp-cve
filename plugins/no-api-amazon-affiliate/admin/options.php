<?php
if (! current_user_can( 'manage_options')) wp_die(_e('No tienes permisos','no-api-amazon-affiliate'));
?>


<div class="wrap">
    <h1 class="wp-heading-inline"><?php echo get_admin_page_title() ?></h1>
    <form method="post" action="options.php">
        <?php
            settings_fields( 'naaa-amazon-options' );
            //do_settings_sections( 'naaa-amazon-options' );
        ?>

        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th scope="row"><label for="naaa_amazon_country"><?php _e('Tienda de Amazon por defecto', 'no-api-amazon-affiliate') ?></label></th>
                    <td>
                        <select name="naaa_amazon_country" id="amazon_country">
                            <option value="ca" <?php selected(esc_attr(get_option('naaa_amazon_country', 'es')), 'ca'); ?>>(CA) <?php _e('Canadá', 'no-api-amazon-affiliate') ?></option>
                            <option value="de" <?php selected(esc_attr(get_option('naaa_amazon_country', 'es')), 'de'); ?>>(DE) <?php _e('Alemania', 'no-api-amazon-affiliate') ?></option>
                            <option value="es" <?php selected(esc_attr(get_option('naaa_amazon_country', 'es')), 'es'); ?>>(ES) <?php _e('España', 'no-api-amazon-affiliate') ?></option>
                            <option value="fr" <?php selected(esc_attr(get_option('naaa_amazon_country', 'es')), 'fr'); ?>>(FR) <?php _e('Francia', 'no-api-amazon-affiliate') ?></option>
                            <option value="gb" <?php selected(esc_attr(get_option('naaa_amazon_country', 'es')), 'gb'); ?>>(GB) <?php _e('Reino Unido', 'no-api-amazon-affiliate') ?></option>
                            <option value="it" <?php selected(esc_attr(get_option('naaa_amazon_country', 'es')), 'it'); ?>>(IT) <?php _e('Italia', 'no-api-amazon-affiliate') ?></option>
                            <option value="jp" <?php selected(esc_attr(get_option('naaa_amazon_country', 'es')), 'jp'); ?>>(JP) <?php _e('Japón', 'no-api-amazon-affiliate') ?></option>
                            <option value="us" <?php selected(esc_attr(get_option('naaa_amazon_country', 'es')), 'us'); ?>>(US) <?php _e('Estados Unidos', 'no-api-amazon-affiliate') ?></option>
                            <option value="mx" <?php selected(esc_attr(get_option('naaa_amazon_country', 'es')), 'mx'); ?>>(MX) <?php _e('México-Beta', 'no-api-amazon-affiliate') ?> unsupported</option>
                            <option value="br" <?php selected(esc_attr(get_option('naaa_amazon_country', 'es')), 'br'); ?>>(BR) <?php _e('Brasil-Beta', 'no-api-amazon-affiliate') ?> unsupported</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('Tag Afiliado Amazon', 'no-api-amazon-affiliate') ?></th>
                    <td> 
                        <fieldset>
                            <label for="naaa_amazon_tag_ca">
                                <input name="naaa_amazon_tag_ca" type="text" id="naaa_amazon_tag_ca" value="<?php echo esc_attr(get_option('naaa_amazon_tag_ca')); ?>" class="regular-text">
                                <?php _e('Amazon Canadá.', 'no-api-amazon-affiliate') ?>
                            </label>
                            <br>
                            <label for="naaa_amazon_tag_de">
                                <input name="naaa_amazon_tag_de" type="text" id="naaa_amazon_tag_de" value="<?php echo esc_attr(get_option('naaa_amazon_tag_de')); ?>" class="regular-text">
                                <?php _e('Amazon Alemania.', 'no-api-amazon-affiliate') ?>
                            </label>
                            <br>
                            <label for="naaa_amazon_tag_es">
                                <input name="naaa_amazon_tag_es" type="text" id="naaa_amazon_tag_es" value="<?php echo esc_attr(get_option('naaa_amazon_tag_es')); ?>" class="regular-text">
                                <?php _e('Amazon España.', 'no-api-amazon-affiliate') ?>
                            </label>
                            <br>
                            <label for="naaa_amazon_tag_fr">
                                <input name="naaa_amazon_tag_fr" type="text" id="naaa_amazon_tag_fr" value="<?php echo esc_attr(get_option('naaa_amazon_tag_fr')); ?>" class="regular-text">
                                <?php _e('Amazon Francia.', 'no-api-amazon-affiliate') ?>
                            </label>
                            <br>
                            <label for="naaa_amazon_tag_gb">
                                <input name="naaa_amazon_tag_gb" type="text" id="naaa_amazon_tag_gb" value="<?php echo esc_attr(get_option('naaa_amazon_tag_gb')); ?>" class="regular-text">
                                <?php _e('Amazon Reino Unido.', 'no-api-amazon-affiliate') ?>
                            </label>
                            <br>
                            <label for="naaa_amazon_tag_it">
                                <input name="naaa_amazon_tag_it" type="text" id="naaa_amazon_tag_it" value="<?php echo esc_attr(get_option('naaa_amazon_tag_it')); ?>" class="regular-text">
                                <?php _e('Amazon Italia.', 'no-api-amazon-affiliate') ?>
                            </label>
                            <br>
                            <label for="naaa_amazon_tag_jp">
                                <input name="naaa_amazon_tag_jp" type="text" id="naaa_amazon_tag_jp" value="<?php echo esc_attr(get_option('naaa_amazon_tag_jp')); ?>" class="regular-text">
                                <?php _e('Amazon Japón.', 'no-api-amazon-affiliate') ?>
                            </label>
                            <br>
                            <label for="naaa_amazon_tag_us">
                                <input name="naaa_amazon_tag_us" type="text" id="naaa_amazon_tag_us" value="<?php echo esc_attr(get_option('naaa_amazon_tag_us')); ?>" class="regular-text">
                                <?php _e('Amazon Estados Unidos.', 'no-api-amazon-affiliate') ?>
                            </label>
                            <br>
                            <label for="naaa_amazon_tag_mx">
                                <input name="naaa_amazon_tag_mx" type="text" id="naaa_amazon_tag_mx" value="<?php echo esc_attr(get_option('naaa_amazon_tag_mx')); ?>" class="regular-text">
                                <del><?php _e('Amazon México.', 'no-api-amazon-affiliate') ?></del> <?php _e('Beta - Sin soporte.', 'no-api-amazon-affiliate') ?>
                            </label>
                            <br>
                            <label for="naaa_amazon_tag_br">
                                <input name="naaa_amazon_tag_br" type="text" id="naaa_amazon_tag_br" value="<?php echo esc_attr(get_option('naaa_amazon_tag_br')); ?>" class="regular-text">
                                <del><?php _e('Amazon Brasil.', 'no-api-amazon-affiliate') ?></del> <?php _e('Beta - Sin soporte.', 'no-api-amazon-affiliate') ?>
                            </label>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="naaa_time_update"><?php _e('Tiempo de actualización', 'no-api-amazon-affiliate') ?></label></th>
                    <td>
                        <select name="naaa_time_update" id="naaa_time_update">
                            <option value="3600" <?php selected(esc_attr(get_option('naaa_time_update')), 3600); ?>>1 <?php _e('hora', 'no-api-amazon-affiliate') ?></option>
                            <option value="10800" <?php selected(esc_attr(get_option('naaa_time_update')), 10800); ?>>3 <?php _e('horas', 'no-api-amazon-affiliate') ?></option>
                            <option value="21600" <?php selected(esc_attr(get_option('naaa_time_update')), 21600); ?>>6 <?php _e('horas', 'no-api-amazon-affiliate') ?></option>
                            <option value="43200" <?php selected(esc_attr(get_option('naaa_time_update')), 43200); ?>>12 <?php _e('horas', 'no-api-amazon-affiliate') ?></option>
                            <option value="86400" <?php selected(esc_attr(get_option('naaa_time_update')), 86400); ?>>24 <?php _e('horas', 'no-api-amazon-affiliate') ?></option>
                            <option value="172800" <?php selected(esc_attr(get_option('naaa_time_update')), 172800); ?>>2 <?php _e('días', 'no-api-amazon-affiliate') ?></option>
                            <option value="345600" <?php selected(esc_attr(get_option('naaa_time_update')), 345600); ?>>4 <?php _e('días', 'no-api-amazon-affiliate') ?></option>
                            <option value="604800" <?php selected(esc_attr(get_option('naaa_time_update')), 604800); ?>>7 <?php _e('días', 'no-api-amazon-affiliate') ?></option>
                            <option value="1209600" <?php selected(esc_attr(get_option('naaa_time_update')), 1209600); ?>>14 <?php _e('días', 'no-api-amazon-affiliate') ?></option>
                            <option value="1814400" <?php selected(esc_attr(get_option('naaa_time_update')), 1814400); ?>>21 <?php _e('días', 'no-api-amazon-affiliate') ?></option>
                            <option value="2419200" <?php selected(esc_attr(get_option('naaa_time_update')), 2419200); ?>>28 <?php _e('días', 'no-api-amazon-affiliate') ?></option>
                        </select>
                        <p class="description" id="naaa_time_update_description"><?php _e('Tiempo transcurrido antes de actualizar los datos de un producto.', 'no-api-amazon-affiliate') ?></p>
                        <p class="description" id="naaa_time_update_description"><?php _e('A menor tiempo los datos serán más actuales, pero menor eficiencia en tiempo de carga de página.', 'no-api-amazon-affiliate') ?></p>
                        <p class="description" id="naaa_time_update_description"><strong><?php _e('Nuestra recomendación: 24 horas.', 'no-api-amazon-affiliate') ?></strong></p>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php submit_button(); ?>
        
    </form>

</div>
