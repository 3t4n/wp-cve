<script>
    jQuery(document).ready(function ($) {
        // The "Upload" button
        $('.upload_image_button').click(function() {
            var send_attachment_bkp = wp.media.editor.send.attachment;
            var button = $(this);
            wp.media.editor.send.attachment = function(props, attachment) {
                $(button).parent().prev().attr('src', attachment.url);
                $(button).prev().val(attachment.id);
                wp.media.editor.send.attachment = send_attachment_bkp;
            }
            wp.media.editor.open(button);
            return false;
        });

        // The "Remove" button (remove the value from input type='hidden')
        $('.remove_image_button').click(function() {
            var answer = confirm('Are you sure?');
            if (answer == true) {
                var src = $(this).parent().prev().attr('data-src');
                $(this).parent().prev().attr('src', src);
                $(this).prev().prev().val('');
            }
            return false;
        });
    });
</script>

<?php
// both wpml and polylang fill this
$languages = apply_filters( 'wpml_active_languages', false );
?>

<div class="clear"></div>
<div class="postbox " id="dashboard_right_now--disabled" style="width:90%;margin-top:20px;">
    <h3 class="hndle" style="margin: 0px;padding: 10px 15px;"><?php echo __('ESTO Woocommerce Settings', 'woo-esto') ?></h3>
    <div class="inside">
        <div class="main">
            <form method="post" action="" name="<?php echo self::$plugin_slug; ?>">
                <?php wp_nonce_field( 'esto_calculator_settings' ) ?>
                <input type="hidden" name="<?php echo self::$plugin_slug; ?>" value="1"/>
                <table class="esto_table" width="100%;">
                    <tr>
                        <td width="30%"><?php echo __('Enable product monthly payment', 'woo-esto') ?></td>
                        <td>
                            <input type="checkbox" name="enable_calc" <?php echo ($this->get_setting("enable_calc")) ? "checked=checked" : ""; ?> value="1" />
                        </td>
                    </tr>

                    <tr>
                        <td width="30%"><?php echo __('Product monthly payment URL', 'woo-esto') ?></td>
                        <td>
                            <input type="text" name="calc_url" value="<?php echo $this->get_setting('calc_url'); ?>" />
                        </td>
                    </tr>

                    <?php
                    if ( $languages ) {
                        foreach ( $languages as $language_key => $language ) {
                            $language_name = $language_key;
                            if ( ! empty( $language['translated_name'] ) ) {
                                $language_name = $language['translated_name'];
                            }
                            elseif ( ! empty( $language['display_name'] ) ) {
                                $language_name = $language['display_name'];
                            }
                            elseif ( ! empty( $language['native_name'] ) ) {
                                $language_name = $language['native_name'];
                            }
                            ?>
                            <tr>
                                <td width="30%"><?php echo __('Product monthly payment URL', 'woo-esto' ) . ' (' . $language_name . ')' ?></td>
                                <td>
                                    <input type="text" name="calc_url_<?= $language_key ?>" value="<?php echo $this->get_setting('calc_url_' . $language_key ); ?>" />
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>

                    <tr>
                        <td>
                            <h4><?= __( 'Default logo', 'woo-esto' ) ?></h4>
                            <div class="upload">
                                <img data-src="https://via.placeholder.com/110x60"
                                     src="<?php echo $this->get_setting('logo_src'); ?>" style="max-width:110px;width:auto;" />
                                <div>
                                    <input type="hidden" name="esto_calc_logo" id="esto_calc_logo"
                                           value="<?php echo $this->get_setting('logo_value'); ?>" />
                                    <button type="submit" class="upload_image_button button">Upload</button>
                                    <button type="submit" class="remove_image_button button">&times;</button>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <?php
                    if ( $languages ) {
                        foreach ( $languages as $language_key => $language ) {
                            // default logos are url's, but media uploader uses ids, which means we have to check if stored value is url or id.
                            $logo_id = $this->get_setting( 'calculator_logo_url_' . $language_key );
                            $logo_src = 'https://via.placeholder.com/110x60';

                            if ( $logo_id ) {
                                if ( is_numeric( $logo_id ) ) {
                                    $logo_attachment = wp_get_attachment_image_src( $logo_id, 'full' );
                                    if ( ! empty( $logo_attachment ) ) {
                                        $logo_src = $logo_attachment[0];
                                    }
                                }
                                else {
                                    $logo_src = $logo_id;
                                }
                            }
                            // comparison with null; if logo is later deleted value will be empty string
                            elseif ( $language_key == 'et' && $logo_id === null ) {
                                $logo_id = $logo_src = plugins_url( 'woo-esto/assets/images/icons/logo-esto.svg', 'woo-esto' );
                            }
                            elseif ( $language_key == 'en' && $logo_id === null ) {
                                $logo_id = $logo_src = plugins_url( 'woo-esto/assets/images/icons/logo-esto-en.svg', 'woo-esto' );
                            }
                            elseif ( $language_key == 'lv' && $logo_id === null ) {
                                $logo_id = $logo_src = plugins_url( 'woo-esto/assets/images/icons/logo-esto-lv.svg', 'woo-esto' );
                            }
                            elseif ( $language_key == 'lt' && $logo_id === null ) {
                                $logo_id = $logo_src = plugins_url( 'woo-esto/assets/images/icons/logo-esto-lt.svg', 'woo-esto' );
                            }
                            elseif ( $language_key == 'ru' && $logo_id === null ) {
                                $logo_id = $logo_src = plugins_url( 'woo-esto/assets/images/icons/logo-esto-ru.svg', 'woo-esto' );
                            }

                            $language_name = $language_key;
                            if ( ! empty( $language['translated_name'] ) ) {
                                $language_name = $language['translated_name'];
                            }
                            elseif ( ! empty( $language['display_name'] ) ) {
                                $language_name = $language['display_name'];
                            }
                            elseif ( ! empty( $language['native_name'] ) ) {
                                $language_name = $language['native_name'];
                            }
                            ?>
                            <tr>
                                <td>
                                    <h4><?= sprintf( __( '%s logo', 'woo-esto' ), $language_name ) ?></h4>
                                    <div class="upload">
                                        <img data-src="https://via.placeholder.com/110x60"
                                             src="<?= $logo_src ?>" style="width:110px;height:auto;" />
                                        <div>
                                            <input type="hidden" name="calculator_logo_url_<?= $language_key ?>" id="esto_calc_logo_<?= $language_key ?>"
                                                   value="<?= $logo_id ?>" />
                                            <button type="submit" class="upload_image_button button">Upload</button>
                                            <button type="submit" class="remove_image_button button">&times;</button>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td width="30%">
                                    <h4><?= sprintf( __( '%s text', 'woo-esto' ), $language_name ) ?></h4>
                                    <i><?= __( '%s will be replaced by monthly payment value, for instance "Monthly payment from %s" will become "Monthly payment from 7.4€ / 6 months". Leave empty to use default text.', 'woo-esto' ) ?></i>
                                </td>
                                <td>
                                    <textarea name="calc_text_<?= $language_key ?>" rows="4" cols="50"><?= $this->get_setting( 'calc_text_' . $language_key ) ?></textarea>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    else {
                        ?>
                        <tr>
                            <td width="30%">
                                <h4><?= __( 'Text', 'woo-esto' ) ?></h4>
                                <i><?= __( '%s will be replaced by monthly payment value, for instance "Monthly payment from %s" will become "Monthly payment from 7.4€ / 6 months". Leave empty to use default text.', 'woo-esto' ) ?></i>
                            </td>
                            <td>
                                <textarea name="calc_text" rows="4" cols="50"><?= $this->get_setting( 'calc_text' ) ?></textarea>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>

                    <tr>
                        <td width="30%"><?= __( 'Override logo width (px)', 'woo-esto' ) ?></td>
                        <td>
                            <input type="number" name="calc_logo_width" value="<?= $this->get_setting( 'calc_logo_width' ) ?>" />
                        </td>
                    </tr>

                    <tr>
                        <td width="30%"><?= __( 'Override logo height (px)', 'woo-esto' ) ?></td>
                        <td>
                            <input type="number" name="calc_logo_height" value="<?= $this->get_setting( 'calc_logo_height' ) ?>" />
                        </td>
                    </tr>

                    <tr>
                        <td width="30%"><?php echo __('Show Esto 3 monthly payment instead of the regular', 'woo-esto') ?></td>
                        <td>
                            <input type="checkbox" name="show_esto_3" <?php echo ($this->get_setting("show_esto_3")) ? "checked=checked" : ""; ?> value="1" />
                        </td>
                    </tr>

                    <tr>
                        <td width="30%"><?php echo __( 'Show monthly payment on shop and category pages', 'woo-esto' ) ?></td>
                        <td>
                            <input type="checkbox" name="show_monthly_payment_on_archive_pages" <?php echo ( $this->get_setting( 'show_monthly_payment_on_archive_pages' ) ) ? "checked=checked" : ""; ?> value="1" />
                        </td>
                    </tr>

                    <tr>
                        <td width="30%">
                            <?= __( 'Minimum product price to show monthly payment', 'woo-esto' ) ?>
                            <br>
                            <i><?= sprintf( __( 'Leave empty to use default, which is currently %s', 'woo-esto' ), WC_Esto_Calculator::MIN_PRICE_DEFAULT ) ?></i>
                        </td>
                        <td>
                            <input type="number" step="0.01" name="minimum_price" value="<?= $this->get_setting( 'minimum_price' ) ?>" />
                        </td>
                    </tr>

                    <tr>
                        <td width="30%">
                            <?= __( 'Maximum product price to show monthly payment', 'woo-esto' ) ?>
                            <br>
                            <i><?= sprintf( __( 'Leave empty to use default, which is currently %s', 'woo-esto' ), WC_Esto_Calculator::MAX_PRICE_DEFAULT ) ?></i>
                        </td>
                        <td>
                            <input type="number" step="1" name="maximum_price" value="<?= $this->get_setting( 'maximum_price' ) ?>" />
                        </td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                        <td>
                            <input type="submit" class="button button-primary" name="btn-esto-submit" value="<?php echo __("Save Settings", "woo-esto") ?>" />
                        </td>
                    </tr>

                    <tr>
                        <td width="30%">
                            <h4><?= __( 'What to do when the calculator does not show up', 'woo-esto' ) ?></h4>
                        </td>
                        <td>
                            <ol>
                                <li><?= __( 'Ensure product price is sufficiently high', 'woo-esto' ) ?></li>
                                <li><?= __( 'Try inserting the shortcode [esto_monthly_payment] at your chosen location in product loop or single view template', 'woo-esto' ) ?></li>
                            </ol>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<div class="clear"></div>