<?php
$has_wc = spoki_has_woocommerce();
$has_elementor = spoki_has_elementor();
$sections = ['fab', 'single_product', 'product_item', 'cart', 'other_buttons', 'elementor', 'qr_code'];
$current_section = isset($_GET['section']) && in_array($_GET['section'], $sections) ? $_GET['section'] : $sections[0];
$is_current_tab = $GLOBALS['current_tab'] == 'buttons';
if (false == $has_wc) : ?>
    <div class="notice notice-error">
        <p>
			<?php _e("Install and activate the <strong>WooCommerce</strong> plugin to enable the Spoki features for WooCommerce.", "spoki") ?>
        </p>
    </div>
<?php endif ?>

<?php if ($current_section == 'elementor' && !$has_elementor): ?>
    <div class="notice notice-error">
        <p>
			<?php _e("Install and activate the <strong>Elementor</strong> plugin to enable the Spoki features for Elementor.", "spoki") ?>
        </p>
    </div>
<?php endif ?>


<div <?php if (!$is_current_tab) echo 'style="display:none"' ?>>
    <ul class="subsubsub">
        <li><a href="<?php echo "?page=" . urlencode(SPOKI_PLUGIN_NAME) . "&tab=" . urlencode($GLOBALS['current_tab']) ?>&section=fab" <?php if ($current_section == 'fab') echo "class='current'" ?>><?php _e('Floating Button', "spoki") ?></a> |</li>
        <li><a href="<?php echo "?page=" . urlencode(SPOKI_PLUGIN_NAME) . "&tab=" . urlencode($GLOBALS['current_tab']) ?>&section=other_buttons" <?php if ($current_section == 'other_buttons') echo "class='current'" ?>><?php _e('Shortcode Button', "spoki") ?></a> |</li>
        <li><a href="<?php echo "?page=" . urlencode(SPOKI_PLUGIN_NAME) . "&tab=" . urlencode($GLOBALS['current_tab']) ?>&section=product_item" <?php if ($current_section == 'product_item') echo "class='current'" ?>><?php _e('Shop page', "spoki") ?></a> |</li>
        <li><a href="<?php echo "?page=" . urlencode(SPOKI_PLUGIN_NAME) . "&tab=" . urlencode($GLOBALS['current_tab']) ?>&section=single_product" <?php if ($current_section == 'single_product') echo "class='current'" ?>><?php _e('Product page', "spoki") ?></a> |</li>
        <li><a href="<?php echo "?page=" . urlencode(SPOKI_PLUGIN_NAME) . "&tab=" . urlencode($GLOBALS['current_tab']) ?>&section=cart" <?php if ($current_section == 'cart') echo "class='current'" ?>><?php _e('Cart page', "spoki") ?></a> |</li>
        <li><a href="<?php echo "?page=" . urlencode(SPOKI_PLUGIN_NAME) . "&tab=" . urlencode($GLOBALS['current_tab']) ?>&section=qr_code" <?php if ($current_section == 'qr_code') echo "class='current'" ?>><?php _e('QR Code', "spoki") ?></a> |</li>
        <li><a href="<?php echo "?page=" . urlencode(SPOKI_PLUGIN_NAME) . "&tab=" . urlencode($GLOBALS['current_tab']) ?>&section=elementor" <?php if ($current_section == 'elementor') echo "class='current'" ?>><?php _e('Elementor', "spoki") ?></a></li>
    </ul>
    <br/>
    <br/>
    <div <?php if ($current_section != 'fab') echo "style='display:none'" ?>>
        <h2 class="title">
			<?php _e('Floating Button', "spoki") ?>
            <a href="#TB_inline?&width=300&inlineId=buttons-info-dialog" class="thickbox button-info">ℹ</a>
        </h2>
        <p class="description">
			<?php _e("Insert the fixed WhatsApp chat button on your website and receive information requests from your customers, to your number.", "spoki") ?>
        </p>
        <img class="cover-image" src="<?php echo plugins_url() . '/' . SPOKI_PLUGIN_NAME . '/assets/images/home-page-button.png' ?>"/>
        <table class="form-table" role="presentation">
            <tr>
                <th scope="row">
                    <label for="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_check]">
						<?php _e('Enable', "spoki") ?>
                    </label>
                </th>
                <td>
                    <input type="checkbox" id="fixed_support_button_check"
                           name="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_check]"
                           value="1" <?php if (isset($this->options['buttons']['fixed_support_button_check'])) echo checked(1, $this->options['buttons']['fixed_support_button_check'], false) ?>>

                    <label for="fixed_support_button_check"><?php _e('Enable the button', "spoki") ?></label>
                    <p class="description">
                        <b><?php _e("Note", "spoki") ?></b>: <?php _e('If you enable the chat button you make it visible on all pages of the site', "spoki") ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_text]">
						<?php _e('Customer Message', "spoki") ?>
                    </label>
                </th>
                <td>
                <textarea class="regular-text"
                          name="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_text]"
                          cols="50" rows="5"
                          placeholder="<?php _e('Hi, I would like to receive more information about your business', "spoki") ?>"><?php if (isset($this->options['buttons']['fixed_support_button_text']) && $this->options['buttons']['fixed_support_button_text'] !== '') echo $this->options['buttons']['fixed_support_button_text'] ?></textarea>
                    <p class="description">
                        <b><?php _e("Note", "spoki") ?></b>: <?php _e('Preset message in chat that the customer can send via button to start a conversation', "spoki") ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_text]">
						<?php _e('Customer Message on non-working days and times', "spoki") ?>
                    </label>
                </th>
                <td>
                    <label>
                        <input id="fixed-support-button-non-working-text-toggle" type="checkbox" name="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_non_working_text_enabled]"
                               value="1" <?php if (isset($this->options['buttons']['fixed_support_button_non_working_text_enabled'])) echo checked(1, $this->options['buttons']['fixed_support_button_non_working_text_enabled'], false) ?>>
						<?php _e('Enable alternative message on non-working days and times', "spoki") ?>
                    </label>
                    <br/>
                    <textarea
                            id="fixed-support-button-non-working-text"
                            class="regular-text"
                            name="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_non_working_text]"
                            cols="50" rows="5"
                            placeholder="<?php _e('Hi, I would like to receive more information about your business', "spoki") ?>"><?php if (isset($this->options['buttons']['fixed_support_button_non_working_text']) && $this->options['buttons']['fixed_support_button_non_working_text'] !== '') echo $this->options['buttons']['fixed_support_button_non_working_text'] ?></textarea>
                    <p class="description">
                        <b><?php _e("Note", "spoki") ?></b>: <?php _e('The customer will send you this message only the non-working days and times.<br/>Set the working days and times in the settings panel.', "spoki") ?>
                    </p>
                    <script>
                        (function () {
                            function toggleNonWorkingText(enable) {
                                const el = document.getElementById('fixed-support-button-non-working-text');
                                if (enable) {
                                    el.removeAttribute('disabled');
                                } else {
                                    el.setAttribute('disabled', true);
                                }
                            }

                            const nonWorkingTextToggleEl = document.getElementById('fixed-support-button-non-working-text-toggle');
                            toggleNonWorkingText(nonWorkingTextToggleEl.getAttribute('checked'))
                            nonWorkingTextToggleEl.addEventListener('click', e => toggleNonWorkingText(e.target.checked));
                        })();
                    </script>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_position]">
						<?php _e('Position', "spoki") ?>
                    </label>
                </th>
                <td>
                    <label class="checkbox" for="fixed_support_button_position_left" style="margin-right: 8px">
                        <input type="radio" id="fixed_support_button_position_left" name="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_position]"
                               value="Left" <?php if (isset($this->options['buttons']['fixed_support_button_position']) && $this->options['buttons']['fixed_support_button_position'] == 'Left') echo 'checked' ?> />
                        <span><?php _e('Left', "spoki"); ?></span>
                    </label>
                    <label class="checkbox" for="fixed_support_button_position_right">
                        <input type="radio" id="fixed_support_button_position_right" name="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_position]"
                               value="Right" <?php if (isset($this->options['buttons']['fixed_support_button_position']) && $this->options['buttons']['fixed_support_button_position'] == 'Right') echo 'checked' ?> />
                        <span><?php _e('Right', "spoki"); ?></span>
                    </label>
                    <p class="description">
                        <b><?php _e("Note", "spoki") ?></b>: <?php _e('Select where to display the button on the screen', "spoki") ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="fixed_support_button_color">
						<?php _e('Color', "spoki") ?>
                    </label>
                </th>
                <td>
                    <input type="color"
                           id="fixed_support_button_color"
                           name="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_color]"
                           value="<?php echo (isset($this->options['buttons']['fixed_support_button_color']) && $this->options['buttons']['fixed_support_button_color'] !== '') ? $this->options['buttons']['fixed_support_button_color'] : '#23D366' ?>"
                    >
                    <p class="description">
                        <b><?php _e("Note", "spoki") ?></b>: <?php _e('Set the background color of the button (Suggested: <i>#23D366</i> - <i>rgb(35, 211, 102)</i>)', "spoki") ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="fixed_support_button_border">
                        <b><?php _e('Border type', "spoki") ?></b>
                    </label>
                </th>
                <td>
					<?php $fixed_support_button_border = isset($this->options['buttons']['fixed_support_button_border']) ? $this->options['buttons']['fixed_support_button_border'] : 'circle' ?>
                    <select name="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_border]" id="fixed_support_button_border" class="regular-text">
                        <option value="circle" <?php echo $fixed_support_button_border == 'circle' ? 'selected' : '' ?>><?php echo __('Circle', "spoki") ?></option>
                        <option value="squared" <?php echo $fixed_support_button_border == 'squared' ? 'selected' : '' ?>><?php echo __('Squared', "spoki") ?></option>
                        <option value="rounded" <?php echo $fixed_support_button_border == 'rounded' ? 'selected' : '' ?>><?php echo __('Rounded', "spoki") ?></option>
                    </select>
                    <p class="description">
                        <b><?php _e("Note", "spoki") ?></b>: <?php _e('Choose the button border type', "spoki"); ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="fixed_support_button_side_space">
						<?php _e('Space from side', "spoki") ?>
                    </label>
                </th>
                <td>
                    <label>
                        <input type="number" id="fixed_support_button_side_space"
                               name="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_side_space]"
                               placeholder="12"
                               value="<?php echo (isset($this->options['buttons']['fixed_support_button_side_space']) && $this->options['buttons']['fixed_support_button_side_space'] !== '') ? $this->options['buttons']['fixed_support_button_side_space'] : '12' ?>">
                        px
                    </label>
                    <p class="description">
                        <b><?php _e("Note", "spoki") ?></b>: <?php _e('Set the space from the left/right side of the screen', "spoki") ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="fixed_support_button_bottom_space">
						<?php _e('Space from bottom', "spoki") ?>
                    </label>
                </th>
                <td>
                    <label>
                        <input type="number" id="fixed_support_button_bottom_space"
                               name="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_bottom_space]"
                               placeholder="12"
                               value="<?php echo (isset($this->options['buttons']['fixed_support_button_bottom_space']) && $this->options['buttons']['fixed_support_button_bottom_space'] !== '') ? $this->options['buttons']['fixed_support_button_bottom_space'] : '12' ?>">
                        px
                    </label>
                    <p class="description">
                        <b><?php _e("Note", "spoki") ?></b>: <?php _e('Set the space from the bottom of the screen', "spoki") ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="fixed_support_button_size">
						<?php _e('Size', "spoki") ?>
                    </label>
                </th>
                <td>
                    <label>
                        <input type="number" id="fixed_support_button_size"
                               name="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_size]"
                               placeholder="50"
                               value="<?php echo (isset($this->options['buttons']['fixed_support_button_size']) && $this->options['buttons']['fixed_support_button_size'] !== '') ? $this->options['buttons']['fixed_support_button_size'] : '50' ?>">
                        px
                    </label>
                    <p class="description">
                        <b><?php _e("Note", "spoki") ?></b>: <?php _e('Set the size of the button', "spoki") ?>
                    </p>
                </td>
            </tr>
        </table>
        <br/>
        <hr/>
        <h3><?php _e('Chat widget', "spoki") ?></h3>
        <p class="description">
			<?php _e("The chat widget will be <b>shown on button click</b>.", "spoki") ?>
        </p>
        <img class="cover-image" style="max-height: 210px;margin-top: 10px;border-radius:4px;" src="<?php echo plugins_url() . '/' . SPOKI_PLUGIN_NAME . '/assets/images/fab-chat-widget.png' ?>"/>

        <table class="form-table" role="presentation">
            <tr>
                <th scope="row">
                    <label for="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_show_chat_widget]">
						<?php _e('Enable chat widget', "spoki") ?>
                    </label>
                </th>
                <td>
                    <input type="checkbox" id="fixed_support_button_show_chat_widget"
                           name="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_show_chat_widget]"
                           value="1" <?php if (isset($this->options['buttons']['fixed_support_button_show_chat_widget'])) echo checked(1, $this->options['buttons']['fixed_support_button_show_chat_widget'], false) ?>>

                    <label for="fixed_support_button_show_chat_widget"><?php _e('Show chat widget on button click', "spoki") ?></label>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_chat_widget_message]">
						<?php _e('Your Message', "spoki") ?>
                    </label>
                </th>
                <td>
                <textarea class="regular-text" rows="1"
                          name="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_chat_widget_message]"
                          placeholder="<?php _e('Chat with us 👋', "spoki") ?>"><?php if (isset($this->options['buttons']['fixed_support_button_chat_widget_message']) && $this->options['buttons']['fixed_support_button_chat_widget_message'] !== '') echo $this->options['buttons']['fixed_support_button_chat_widget_message'] ?></textarea>
                    <p class="description">
                        <b><?php _e("Note", "spoki") ?></b>: <?php _e('Thi is the text of the message sent from you.', "spoki") ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="fixed_support_button_chat_widget_delay">
						<?php _e('Delay', "spoki") ?>
                    </label>
                </th>
                <td>
                    <label>
                        <input type="number" id="fixed_support_button_chat_widget_delay"
                               name="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_chat_widget_delay]"
                               min="0"
                               value="<?php echo (isset($this->options['buttons']['fixed_support_button_chat_widget_delay']) && $this->options['buttons']['fixed_support_button_chat_widget_delay'] !== '') ? $this->options['buttons']['fixed_support_button_chat_widget_delay'] : '0' ?>">
						<?php _e('seconds', "spoki") ?>
                    </label>
                    <p class="description">
                        <b><?php _e("Note", "spoki") ?></b>: <?php _e('Show the chat widget after this delay (0 = delay disabled)', "spoki") ?>
                    </p>
                </td>
            </tr>
        </table>
        <br/>
        <hr/>
        <h3><?php _e('Label', "spoki") ?></h3>
        <p class="description">
			<?php _e("Display a label for your floating button to entice the user to click on the button.", "spoki") ?>
        </p>
        <img class="cover-image" style="max-height: 150px;margin-top: 10px;border-radius:4px;" src="<?php echo plugins_url() . '/' . SPOKI_PLUGIN_NAME . '/assets/images/fab-label.png' ?>"/>

        <table class="form-table" role="presentation">
            <tr>
                <th scope="row">
                    <label for="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_show_label]">
						<?php _e('Enable label', "spoki") ?>
                    </label>
                </th>
                <td>
                    <input type="checkbox" id="fixed_support_button_show_label"
                           name="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_show_label]"
                           value="1" <?php if (isset($this->options['buttons']['fixed_support_button_show_label'])) echo checked(1, $this->options['buttons']['fixed_support_button_show_label'], false) ?>>

                    <label for="fixed_support_button_show_label"><?php _e('Show label', "spoki") ?></label>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="fixed_support_button_label_delay">
						<?php _e('Delay', "spoki") ?>
                    </label>
                </th>
                <td>
                    <label>
                        <input type="number" id="fixed_support_button_label_delay"
                               name="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_label_delay]"
                               min="0"
                               value="<?php echo (isset($this->options['buttons']['fixed_support_button_label_delay']) && $this->options['buttons']['fixed_support_button_label_delay'] !== '') ? $this->options['buttons']['fixed_support_button_label_delay'] : '0' ?>">
						<?php _e('seconds', "spoki") ?>
                    </label>
                    <p class="description">
                        <b><?php _e("Note", "spoki") ?></b>: <?php _e('Show the label after this delay (0 = immediately)', "spoki") ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_show_label_on_hover]">
						<?php _e('Show on hover', "spoki") ?>
                    </label>
                </th>
                <td>
                    <input type="checkbox" id="fixed_support_button_show_label_on_hover"
                           name="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_show_label_on_hover]"
                           value="1" <?php if (isset($this->options['buttons']['fixed_support_button_show_label_on_hover'])) echo checked(1, $this->options['buttons']['fixed_support_button_show_label_on_hover'], false) ?>>

                    <label for="fixed_support_button_show_label_on_hover"><?php _e('Show label only on button hover', "spoki") ?></label>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_label_content]">
						<?php _e('Label text', "spoki") ?>
                    </label>
                </th>
                <td>
                <textarea class="regular-text" rows="1"
                          name="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_label_content]"
                          placeholder="<?php _e('Chat with us 👋', "spoki") ?>"><?php if (isset($this->options['buttons']['fixed_support_button_label_content']) && $this->options['buttons']['fixed_support_button_label_content'] !== '') echo $this->options['buttons']['fixed_support_button_label_content'] ?></textarea>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="fixed_support_button_label_font_size">
						<?php _e('Font size', "spoki") ?> (px)
                    </label>
                </th>
                <td>
                    <label>
                        <input type="number" id="fixed_support_button_label_font_size"
                               name="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_label_font_size]"
                               placeholder="16"
                               value="<?php echo (isset($this->options['buttons']['fixed_support_button_label_font_size']) && $this->options['buttons']['fixed_support_button_label_font_size'] !== '') ? $this->options['buttons']['fixed_support_button_label_font_size'] : '16' ?>">
                    </label>
                    <p class="description">
                        <b><?php _e("Note", "spoki") ?></b>: <?php _e('Set the font size of the label', "spoki") ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="fixed_support_button_label_text_color">
						<?php _e('Color', "spoki") ?>
                    </label>
                </th>
                <td>
                    <input type="color"
                           id="fixed_support_button_label_text_color"
                           name="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_label_text_color]"
                           value="<?php echo (isset($this->options['buttons']['fixed_support_button_label_text_color']) && $this->options['buttons']['fixed_support_button_label_text_color'] !== '') ? $this->options['buttons']['fixed_support_button_label_text_color'] : '#333333' ?>"
                    >
                    <p class="description">
                        <b><?php _e("Note", "spoki") ?></b>: <?php _e('Set the color of the text of the label (Suggested: <i>#333333</i> - <i>rgb(51, 51, 51)</i>)', "spoki") ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="fixed_support_button_label_background_color">
						<?php _e('Background Color', "spoki") ?>
                    </label>
                </th>
                <td>
                    <input type="color"
                           id="fixed_support_button_label_background_color"
                           name="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_label_background_color]"
                           value="<?php echo (isset($this->options['buttons']['fixed_support_button_label_background_color']) && $this->options['buttons']['fixed_support_button_label_background_color'] !== '') ? $this->options['buttons']['fixed_support_button_label_background_color'] : '#FFFFFF' ?>"
                    >
                    <p class="description">
                        <b><?php _e("Note", "spoki") ?></b>: <?php _e('Set the background color of the label (Suggested: <i>#FFFFFF</i> - <i>rgb(255, 255, 255)</i>)', "spoki") ?>
                    </p>
                </td>
            </tr>
        </table>
        <br/>
        <hr/>
        <h3><?php _e('Badge', "spoki") ?></h3>
        <p class="description">
			<?php _e("Show the unread message badge to entice the user to click on the button.", "spoki") ?>
        </p>
        <img class="cover-image" style="max-height: 100px;margin-top: 10px;border-radius:4px;" src="<?php echo plugins_url() . '/' . SPOKI_PLUGIN_NAME . '/assets/images/popup.png' ?>"/>
        <table class="form-table" role="presentation">
            <tr>
                <th scope="row">
                    <label for="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_show_popup]">
						<?php _e('Enable badge', "spoki") ?>
                    </label>
                </th>
                <td>
                    <input type="checkbox" id="fixed_support_button_show_popup"
                           name="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_show_popup]"
                           value="1" <?php if (isset($this->options['buttons']['fixed_support_button_show_popup'])) echo checked(1, $this->options['buttons']['fixed_support_button_show_popup'], false) ?>>

                    <label for="fixed_support_button_show_popup"><?php _e('Show badge', "spoki") ?></label>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="fixed_support_button_popup_delay">
						<?php _e('Delay', "spoki") ?>
                    </label>
                </th>
                <td>
                    <label>
                        <input type="number" id="fixed_support_button_popup_delay"
                               name="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_popup_delay]"
                               min="0"
                               value="<?php echo (isset($this->options['buttons']['fixed_support_button_popup_delay']) && $this->options['buttons']['fixed_support_button_popup_delay'] !== '') ? $this->options['buttons']['fixed_support_button_popup_delay'] : '0' ?>">
						<?php _e('seconds', "spoki") ?>
                    </label>
                    <p class="description">
                        <b><?php _e("Note", "spoki") ?></b>: <?php _e('Show the badge after this delay (0 = immediately)', "spoki") ?>
                    </p>
                </td>
            </tr>
        </table>
        <br/>
        <hr/>
        <h3><?php _e('Visibility rules', "spoki") ?></h3>
        <p class="description">
			<?php _e("Set custom visibility rules for the floating button.", "spoki") ?>
        </p>
        <table class="form-table" role="presentation">
            <tr>
                <th scope="row">
                    <label for="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_hide_non_working]">
						<?php _e('Hide on non-working days and times', "spoki") ?>
                    </label>
                </th>
                <td>
                    <input type="checkbox" id="fixed_support_button_hide_non_working"
                           name="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_hide_non_working]"
                           value="1" <?php if (isset($this->options['buttons']['fixed_support_button_hide_non_working'])) echo checked(1, $this->options['buttons']['fixed_support_button_hide_non_working'], false) ?>>

                    <label for="fixed_support_button_hide_non_working"><?php _e('Hide the button on non-working days and times', "spoki") ?></label>
                    <p class="description">
                        <b><?php _e("Note", "spoki") ?></b>: <?php _e('Set the working days and times in the settings panel', "spoki") ?>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_hide_mobile]">
						<?php _e('Hide on Mobile', "spoki") ?>
                    </label>
                </th>
                <td>
                    <input type="checkbox" id="fixed_support_button_hide_mobile"
                           name="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_hide_mobile]"
                           value="1" <?php if (isset($this->options['buttons']['fixed_support_button_hide_mobile'])) echo checked(1, $this->options['buttons']['fixed_support_button_hide_mobile'], false) ?>>

                    <label for="fixed_support_button_hide_mobile"><?php _e('Hide Floating Button on Mobile', "spoki") ?></label>
                    <p class="description">
                        <b><?php _e("Note", "spoki") ?></b>: <?php _e('The floating button will be hidden on mobile resolutions', "spoki") ?> <i>(<576px)</i>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_hide_tablet]">
						<?php _e('Hide on Tablet', "spoki") ?>
                    </label>
                </th>
                <td>
                    <input type="checkbox" id="fixed_support_button_hide_tablet"
                           name="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_hide_tablet]"
                           value="1" <?php if (isset($this->options['buttons']['fixed_support_button_hide_tablet'])) echo checked(1, $this->options['buttons']['fixed_support_button_hide_tablet'], false) ?>>

                    <label for="fixed_support_button_hide_tablet"><?php _e('Hide Floating Button on Tablet', "spoki") ?></label>
                    <p class="description">
                        <b><?php _e("Note", "spoki") ?></b>: <?php _e('The floating button will be hidden on tablet resolutions', "spoki") ?> <i>(>575px & <992px)</i>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_hide_desktop]">
						<?php _e('Hide on Desktop', "spoki") ?>
                    </label>
                </th>
                <td>
                    <input type="checkbox" id="fixed_support_button_hide_desktop"
                           name="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_hide_desktop]"
                           value="1" <?php if (isset($this->options['buttons']['fixed_support_button_hide_desktop'])) echo checked(1, $this->options['buttons']['fixed_support_button_hide_desktop'], false) ?>>

                    <label for="fixed_support_button_hide_desktop"><?php _e('Hide Floating Button on Desktop', "spoki") ?></label>
                    <p class="description">
                        <b><?php _e("Note", "spoki") ?></b>: <?php _e('The floating button will be hidden on desktop resolutions', "spoki") ?> <i>(>991px)</i>
                    </p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_hide_post_page]">
						<?php _e('Hide on Post Page', "spoki") ?>
                    </label>
                </th>
                <td>
                    <input type="checkbox" id="fixed_support_button_hide_post_page"
                           name="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_hide_post_page]"
                           value="1" <?php if (isset($this->options['buttons']['fixed_support_button_hide_post_page'])) echo checked(1, $this->options['buttons']['fixed_support_button_hide_post_page'], false) ?>>
                    <label for="fixed_support_button_hide_post_page"><?php _e('Hide Floating Button on all Single Post pages', "spoki") ?></label>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_hide_single_page]">
						<?php _e('Hide on Single Page', "spoki") ?>
                    </label>
                </th>
                <td>
                    <input type="checkbox" id="fixed_support_button_hide_single_page"
                           name="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_hide_single_page]"
                           value="1" <?php if (isset($this->options['buttons']['fixed_support_button_hide_single_page'])) echo checked(1, $this->options['buttons']['fixed_support_button_hide_single_page'], false) ?>>
                    <label for="fixed_support_button_hide_single_page"><?php _e('Hide Floating Button on all Single pages', "spoki") ?></label>
                </td>
            </tr>
			<?php if ($has_wc): ?>
                <tr>
                    <th scope="row">
                        <label for="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_hide_shop_page]">
							<?php _e('Hide on Shop Page', "spoki") ?>
                        </label>
                    </th>
                    <td>
                        <input type="checkbox" id="fixed_support_button_hide_shop_page"
                               name="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_hide_shop_page]"
                               value="1" <?php if (isset($this->options['buttons']['fixed_support_button_hide_shop_page'])) echo checked(1, $this->options['buttons']['fixed_support_button_hide_shop_page'], false) ?>>
                        <label for="fixed_support_button_hide_shop_page"><?php _e('Hide Floating Button on Shop page', "spoki") ?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_hide_product_page]">
							<?php _e('Hide on Product Page', "spoki") ?>
                        </label>
                    </th>
                    <td>
                        <input type="checkbox" id="fixed_support_button_hide_product_page"
                               name="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_hide_product_page]"
                               value="1" <?php if (isset($this->options['buttons']['fixed_support_button_hide_product_page'])) echo checked(1, $this->options['buttons']['fixed_support_button_hide_product_page'], false) ?>>
                        <label for="fixed_support_button_hide_product_page"><?php _e('Hide Floating Button on all Product Detail pages', "spoki") ?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_hide_cart_page]">
							<?php _e('Hide on Cart Page', "spoki") ?>
                        </label>
                    </th>
                    <td>
                        <input type="checkbox" id="fixed_support_button_hide_cart_page"
                               name="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_hide_cart_page]"
                               value="1" <?php if (isset($this->options['buttons']['fixed_support_button_hide_cart_page'])) echo checked(1, $this->options['buttons']['fixed_support_button_hide_cart_page'], false) ?>>
                        <label for="fixed_support_button_hide_cart_page"><?php _e('Hide Floating Button on Cart page', "spoki") ?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_hide_checkout_page]">
							<?php _e('Hide on Checkout', "spoki") ?>
                        </label>
                    </th>
                    <td>
                        <input type="checkbox" id="fixed_support_button_hide_checkout_page"
                               name="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_hide_checkout_page]"
                               value="1" <?php if (isset($this->options['buttons']['fixed_support_button_hide_checkout_page'])) echo checked(1, $this->options['buttons']['fixed_support_button_hide_checkout_page'], false) ?>>
                        <label for="fixed_support_button_hide_checkout_page"><?php _e('Hide Floating Button on Checkout pages', "spoki") ?></label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="<?php echo SPOKI_OPTIONS ?>[buttons][fixed_support_button_hide_on_page]">
							<?php _e('Hide on Page', "spoki") ?>
                        </label>
                    </th>
                    <td>
                        <label><?php _e('Hide Floating Button in a specific page', "spoki") ?></label>
                        <ul>
                            <li>• <?php _e('Copy the following snippet', "spoki") ?>: <code id="hide-fab-snippet"></code> <a id="copy-hide-fab-snippet" href="#"><?php _e('copy', "spoki") ?></a></li>
                            <li>• <?php _e('Paste the snippet in an HTML block in the page where you want to hide the button', "spoki") ?></li>
                            <li>• <?php _e('Enjoy', "spoki") ?></li>
                        </ul>
                        <script>
                            const snippet = '<style>#spoki-shadowed-fixed-button{display:none!important;}</style>';
                            document.getElementById('hide-fab-snippet').innerText = snippet;
                            document.getElementById('copy-hide-fab-snippet').addEventListener('click', function (e) {
                                e.preventDefault();
                                const textField = document.createElement('textarea');
                                textField.innerText = snippet;
                                document.body.appendChild(textField);
                                textField.select();
                                document.execCommand('copy');
                                textField.remove();
                            });
                        </script>
                    </td>
                </tr>
			<?php endif; ?>
        </table>
		<?php submit_button(); ?>
    </div>

    <div <?php if ($current_section != 'product_item') echo "style='display:none'" ?> >
        <h2>
			<?php _e('Shop page', "spoki") ?>
            <a href="#TB_inline?&width=300&inlineId=buttons-info-dialog" class="thickbox button-info">ℹ</a>
        </h2>
        <p><?php _e('Add a WhatsApp button for every product in the WooCommerce Shop Page.', "spoki") ?></p>
        <img class="cover-image" src="<?php echo plugins_url() . '/' . SPOKI_PLUGIN_NAME . '/assets/images/shop-page-button.png' ?>"/>

        <fieldset <?php if (false == $has_wc) : ?>disabled<?php endif ?>>
            <table class="form-table">
                <tr>
                    <th>
                        <label for="product_item_listing_button_check">
                            <b><?php _e('Enable', "spoki") ?></b>
                        </label>
                    </th>
                    <td>
                        <input type="checkbox" id="product_item_listing_button_check"
                               name="<?php echo SPOKI_OPTIONS ?>[woocommerce][product_item_listing_button_check]"
                               value="1" <?php if (isset($this->options['woocommerce']['product_item_listing_button_check'])) echo checked(1, $this->options['woocommerce']['product_item_listing_button_check'], false) ?>
                        />
                        <label for="product_item_listing_button_check">
							<?php _e('Enable the button', "spoki") ?>
                        </label>
                        <p class="description">
                            <b><?php _e("Note", "spoki") ?></b>: <?php _e('If you enable the chat button, you make it visible in the product list', "spoki") ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="<?php echo SPOKI_OPTIONS ?>[woocommerce][product_item_listing_button_cta]">
                            <b><?php _e('Button text (CTA)', "spoki") ?></b>
                        </label>
                    </th>
                    <td>
                        <input type="text"
                               id="product_item_listing_button_cta"
                               class="regular-text"
                               name="<?php echo SPOKI_OPTIONS ?>[woocommerce][product_item_listing_button_cta]"
                               value="<?php if (isset($this->options['woocommerce']['product_item_listing_button_cta'])) echo $this->options['woocommerce']['product_item_listing_button_cta'] ?>"
                               placeholder="<?php _e("Request support on WhatsApp", "spoki") ?>"
                        />
                        <p class="description">
                            <b><?php _e("Note", "spoki") ?></b>: <?php _e('Enter the text visible on the button', "spoki") ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="<?php echo SPOKI_OPTIONS ?>[woocommerce][product_item_listing_button_text]">
                            <b><?php _e('Customer Message', "spoki") ?></b>
                        </label>
                    </th>
                    <td>
                            <textarea class="regular-text"
                                      name="<?php echo SPOKI_OPTIONS ?>[woocommerce][product_item_listing_button_text]"
                                      id="<?php echo SPOKI_OPTIONS ?>[woocommerce][product_item_listing_button_text]"
                                      rows="5"
                                      placeholder="<?php _e('Hi, I want to buy:', "spoki") ?>"><?php if (isset($this->options['woocommerce']['product_item_listing_button_text'])) echo $this->options['woocommerce']['product_item_listing_button_text'] ?></textarea>
                        <p class="description">
                            <b><?php _e("Note", "spoki") ?></b>: <?php _e('Preset message in chat that the customer can send via a button to request product information', "spoki") ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="<?php echo SPOKI_OPTIONS ?>[woocommerce][product_item_listing_button_non_working_text_enabled]">
							<?php _e('Customer Message on non-working days and times', "spoki") ?>
                        </label>
                    </th>
                    <td>
                        <label>
                            <input id="product-item-listing-button-non-working-text-toggle" type="checkbox" name="<?php echo SPOKI_OPTIONS ?>[woocommerce][product_item_listing_button_non_working_text_enabled]"
                                   value="1" <?php if (isset($this->options['woocommerce']['product_item_listing_button_non_working_text_enabled'])) echo checked(1, $this->options['woocommerce']['product_item_listing_button_non_working_text_enabled'], false) ?>>
							<?php _e('Enable alternative message on non-working days and times', "spoki") ?>
                        </label>
                        <br/>
                        <textarea
                                id="product-item-listing-button-non-working-text"
                                class="regular-text"
                                name="<?php echo SPOKI_OPTIONS ?>[woocommerce][product_item_listing_button_non_working_text]"
                                cols="50" rows="5"
                                placeholder="<?php _e('Hi, I would like to receive more information about your business', "spoki") ?>"><?php if (isset($this->options['woocommerce']['product_item_listing_button_non_working_text']) && $this->options['woocommerce']['product_item_listing_button_non_working_text'] !== '') echo $this->options['woocommerce']['product_item_listing_button_non_working_text'] ?></textarea>
                        <p class="description">
                            <b><?php _e("Note", "spoki") ?></b>: <?php _e('The customer will send you this message only the non-working days and times.<br/>Set the working days and times in the settings panel.', "spoki") ?>
                        </p>
                        <script>
                            (function () {
                                function toggleNonWorkingText(enable) {
                                    const el = document.getElementById('product-item-listing-button-non-working-text');
                                    if (enable) {
                                        el.removeAttribute('disabled');
                                    } else {
                                        el.setAttribute('disabled', true);
                                    }
                                }

                                const nonWorkingTextToggleEl = document.getElementById('product-item-listing-button-non-working-text-toggle');
                                toggleNonWorkingText(nonWorkingTextToggleEl.getAttribute('checked'))
                                nonWorkingTextToggleEl.addEventListener('click', e => toggleNonWorkingText(e.target.checked));
                            })();
                        </script>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="product_item_listing_button_color">
							<?php _e('Color', "spoki") ?>
                        </label>
                    </th>
                    <td>
                        <input type="color"
                               id="product_item_listing_button_color"
                               name="<?php echo SPOKI_OPTIONS ?>[woocommerce][product_item_listing_button_color]"
                               value="<?php echo (isset($this->options['woocommerce']['product_item_listing_button_color']) && $this->options['woocommerce']['product_item_listing_button_color'] !== '') ? $this->options['woocommerce']['product_item_listing_button_color'] : '#23D366' ?>"
                        >
                        <p class="description">
                            <b><?php _e("Note", "spoki") ?></b>: <?php _e('Set the background color of the button (Suggested: <i>#23D366</i> - <i>rgb(35, 211, 102)</i>)', "spoki") ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="product_item_listing_button_border">
                            <b><?php _e('Border type', "spoki") ?></b>
                        </label>
                    </th>
                    <td>
						<?php $product_item_listing_button_border = isset($this->options['woocommerce']['product_item_listing_button_border']) ? $this->options['woocommerce']['product_item_listing_button_border'] : 'rounded' ?>
                        <select name="<?php echo SPOKI_OPTIONS ?>[woocommerce][product_item_listing_button_border]" id="product_item_listing_button_border" class="regular-text">
                            <option value="rounded" <?php echo $product_item_listing_button_border == 'rounded' ? 'selected' : '' ?>><?php echo __('Rounded', "spoki") ?></option>
                            <option value="squared" <?php echo $product_item_listing_button_border == 'squared' ? 'selected' : '' ?>><?php echo __('Squared', "spoki") ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="product_item_listing_button_font_size">
							<?php _e('Font size', "spoki") ?> (px)
                        </label>
                    </th>
                    <td>
                        <label>
                            <input type="number" id="product_item_listing_button_font_size"
                                   name="<?php echo SPOKI_OPTIONS ?>[woocommerce][product_item_listing_button_font_size]"
                                   placeholder="12"
                                   value="<?php echo (isset($this->options['woocommerce']['product_item_listing_button_font_size']) && $this->options['woocommerce']['product_item_listing_button_font_size'] !== '') ? $this->options['woocommerce']['product_item_listing_button_font_size'] : '12' ?>">
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label>
							<?php _e('Margin', "spoki") ?> (px)
                        </label>
                    </th>
                    <td>
                        <div class="spacing-items">
                            <label>
								<?php _e('Top', "spoki") ?>
                                <input type="number" id="product_item_listing_button_margin_top"
                                       name="<?php echo SPOKI_OPTIONS ?>[woocommerce][product_item_listing_button_margin_top]"
                                       placeholder="4"
                                       value="<?php echo (isset($this->options['woocommerce']['product_item_listing_button_margin_top']) && $this->options['woocommerce']['product_item_listing_button_margin_top'] !== '') ? $this->options['woocommerce']['product_item_listing_button_margin_top'] : '4' ?>">
                            </label>
                            <label>
								<?php _e('Bottom', "spoki") ?>
                                <input type="number" id="product_item_listing_button_margin_bottom"
                                       name="<?php echo SPOKI_OPTIONS ?>[woocommerce][product_item_listing_button_margin_bottom]"
                                       placeholder="4"
                                       value="<?php echo (isset($this->options['woocommerce']['product_item_listing_button_margin_bottom']) && $this->options['woocommerce']['product_item_listing_button_margin_bottom'] !== '') ? $this->options['woocommerce']['product_item_listing_button_margin_bottom'] : '4' ?>">
                            </label>
                            <label>
								<?php _e('Left', "spoki") ?>
                                <input type="number" id="product_item_listing_button_margin_left"
                                       name="<?php echo SPOKI_OPTIONS ?>[woocommerce][product_item_listing_button_margin_left]"
                                       placeholder="0"
                                       value="<?php echo (isset($this->options['woocommerce']['product_item_listing_button_margin_left']) && $this->options['woocommerce']['product_item_listing_button_margin_left'] !== '') ? $this->options['woocommerce']['product_item_listing_button_margin_left'] : '0' ?>">
                            </label>
                            <label>
								<?php _e('Right', "spoki") ?>
                                <input type="number" id="product_item_listing_button_margin_right"
                                       name="<?php echo SPOKI_OPTIONS ?>[woocommerce][product_item_listing_button_margin_right]"
                                       placeholder="0"
                                       value="<?php echo (isset($this->options['woocommerce']['product_item_listing_button_margin_right']) && $this->options['woocommerce']['product_item_listing_button_margin_right'] !== '') ? $this->options['woocommerce']['product_item_listing_button_margin_right'] : '0' ?>">
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label>
							<?php _e('Padding', "spoki") ?> (px)
                        </label>
                    </th>
                    <td>
                        <div class="spacing-items">
                            <label>
								<?php _e('Top', "spoki") ?>
                                <input type="number" id="product_item_listing_button_padding_top"
                                       name="<?php echo SPOKI_OPTIONS ?>[woocommerce][product_item_listing_button_padding_top]"
                                       placeholder="8"
                                       value="<?php echo (isset($this->options['woocommerce']['product_item_listing_button_padding_top']) && $this->options['woocommerce']['product_item_listing_button_padding_top'] !== '') ? $this->options['woocommerce']['product_item_listing_button_padding_top'] : '8' ?>">
                            </label>
                            <label>
								<?php _e('Right', "spoki") ?>
                                <input type="number" id="product_item_listing_button_padding_right"
                                       name="<?php echo SPOKI_OPTIONS ?>[woocommerce][product_item_listing_button_padding_right]"
                                       placeholder="14"
                                       value="<?php echo (isset($this->options['woocommerce']['product_item_listing_button_padding_right']) && $this->options['woocommerce']['product_item_listing_button_padding_right'] !== '') ? $this->options['woocommerce']['product_item_listing_button_padding_right'] : '14' ?>">
                            </label>
                            <label>
								<?php _e('Bottom', "spoki") ?>
                                <input type="number" id="product_item_listing_button_padding_bottom"
                                       name="<?php echo SPOKI_OPTIONS ?>[woocommerce][product_item_listing_button_padding_bottom]"
                                       placeholder="8"
                                       value="<?php echo (isset($this->options['woocommerce']['product_item_listing_button_padding_bottom']) && $this->options['woocommerce']['product_item_listing_button_padding_bottom'] !== '') ? $this->options['woocommerce']['product_item_listing_button_padding_bottom'] : '8' ?>">
                            </label>
                            <label>
								<?php _e('Left', "spoki") ?>
                                <input type="number" id="product_item_listing_button_padding_left"
                                       name="<?php echo SPOKI_OPTIONS ?>[woocommerce][product_item_listing_button_padding_left]"
                                       placeholder="14"
                                       value="<?php echo (isset($this->options['woocommerce']['product_item_listing_button_padding_left']) && $this->options['woocommerce']['product_item_listing_button_padding_left'] !== '') ? $this->options['woocommerce']['product_item_listing_button_padding_left'] : '14' ?>">
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label><?php _e('Hide on non-working days and times', "spoki") ?></label>
                    </th>
                    <td>
                        <input type="checkbox" id="product_item_listing_button_hide_non_working"
                               name="<?php echo SPOKI_OPTIONS ?>[woocommerce][product_item_listing_button_hide_non_working]"
                               value="1" <?php if (isset($this->options['woocommerce']['product_item_listing_button_hide_non_working'])) echo checked(1, $this->options['woocommerce']['product_item_listing_button_hide_non_working'], false) ?>>

                        <label for="product_item_listing_button_hide_non_working"><?php _e('Hide the button on non-working days and times', "spoki") ?></label>
                        <p class="description">
                            <b><?php _e("Note", "spoki") ?></b>: <?php _e('Set the working days and times in the settings panel', "spoki") ?>
                        </p>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>

    <div <?php if ($current_section != 'single_product') echo "style='display:none'" ?>>
        <h2>
			<?php _e('Product page', "spoki") ?>
            <a href="#TB_inline?&width=300&inlineId=buttons-info-dialog" class="thickbox button-info">ℹ</a>
        </h2>
        <p><?php _e('Add the WhatsApp button on the WooCommerce Product Detail Page.', "spoki") ?></p>
        <img class="cover-image" src="<?php echo plugins_url() . '/' . SPOKI_PLUGIN_NAME . '/assets/images/product-page-button.png' ?>"/>

        <fieldset <?php if (false == $has_wc) : ?>disabled<?php endif ?>>
            <table class="form-table">
                <tr>
                    <th>
                        <label for="single_product_button_check">
                            <b><?php _e('Enable', "spoki") ?></b>
                        </label>
                    </th>
                    <td>
                        <input type="checkbox" id="single_product_button_check"
                               name="<?php echo SPOKI_OPTIONS ?>[woocommerce][single_product_button_check]"
                               value="1" <?php if (isset($this->options['woocommerce']['single_product_button_check'])) echo checked(1, $this->options['woocommerce']['single_product_button_check'], false) ?>
                        />
                        <label for="single_product_button_check">
							<?php _e('Enable the button', "spoki") ?>
                        </label>
                        <p class="description">
                            <b><?php _e("Note", "spoki") ?></b>: <?php _e('If you enable the chat button you make it visible in the product detail', "spoki") ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="single_product_button_position">
                            <b><?php _e('Button position', "spoki") ?></b>
                        </label>
                    </th>
                    <td>
						<?php $single_product_button_position = isset($this->options['woocommerce']['single_product_button_position']) ? $this->options['woocommerce']['single_product_button_position'] : 'after_atc' ?>
                        <select name="<?php echo SPOKI_OPTIONS ?>[woocommerce][single_product_button_position]" id="single_product_button_position" class="regular-text">
                            <option value="after_atc" <?php echo $single_product_button_position == 'after_atc' ? 'selected' : '' ?>><?php echo __('After Add to Cart Button', "spoki") ?></option>
                            <option value="after_shortdesc" <?php echo $single_product_button_position == 'after_shortdesc' ? 'selected' : '' ?>><?php echo __('After Short Description', "spoki") ?></option>
                        </select>
                        <p class="description">
                            <b><?php _e("Note", "spoki") ?></b>: <?php _e('Choose where to insert the chat button', "spoki"); ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="<?php echo SPOKI_OPTIONS ?>[woocommerce][single_product_button_cta]">
                            <b><?php _e('Button text (CTA)', "spoki") ?></b>
                        </label>
                    </th>
                    <td>
                        <input type="text"
                               id="single_product_button_cta"
                               class="regular-text"
                               name="<?php echo SPOKI_OPTIONS ?>[woocommerce][single_product_button_cta]"
                               value="<?php if (isset($this->options['woocommerce']['single_product_button_cta'])) echo $this->options['woocommerce']['single_product_button_cta'] ?>"
                               placeholder="<?php _e("Request support on WhatsApp", "spoki") ?>"
                        />
                        <p class="description">
                            <b><?php _e("Note", "spoki") ?></b>: <?php _e('Enter the text visible on the button', "spoki") ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="<?php echo SPOKI_OPTIONS ?>[woocommerce][single_product_button_text]">
                            <b><?php _e('Customer Message', "spoki") ?></b>
                        </label>
                    </th>
                    <td>
                        <textarea class="regular-text"
                                  name="<?php echo SPOKI_OPTIONS ?>[woocommerce][single_product_button_text]"
                                  id="<?php echo SPOKI_OPTIONS ?>[woocommerce][single_product_button_text]"
                                  rows="5"
                                  placeholder="<?php _e('Hi, I want to buy:', "spoki") ?>"
                        ><?php if (isset($this->options['woocommerce']['single_product_button_text'])) echo $this->options['woocommerce']['single_product_button_text'] ?></textarea>
                        <p class="description">
                            <b><?php _e("Note", "spoki") ?></b>: <?php _e('The customer will send you the following message via WhatsApp whenever press on this button', "spoki") ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="<?php echo SPOKI_OPTIONS ?>[buttons][single_product_button_text]">
							<?php _e('Customer Message on non-working days and times', "spoki") ?>
                        </label>
                    </th>
                    <td>
                        <label>
                            <input id="single-product-button-non-working-text-toggle" type="checkbox" name="<?php echo SPOKI_OPTIONS ?>[woocommerce][single_product_button_non_working_text_enabled]"
                                   value="1" <?php if (isset($this->options['woocommerce']['single_product_button_non_working_text_enabled'])) echo checked(1, $this->options['woocommerce']['single_product_button_non_working_text_enabled'], false) ?>>
							<?php _e('Enable alternative message on non-working days and times', "spoki") ?>
                        </label>
                        <br/>
                        <textarea
                                id="single-product-button-non-working-text"
                                class="regular-text"
                                name="<?php echo SPOKI_OPTIONS ?>[woocommerce][single_product_button_non_working_text]"
                                cols="50" rows="5"
                                placeholder="<?php _e('Hi, I would like to receive more information about your business', "spoki") ?>"><?php if (isset($this->options['woocommerce']['single_product_button_non_working_text']) && $this->options['woocommerce']['single_product_button_non_working_text'] !== '') echo $this->options['woocommerce']['single_product_button_non_working_text'] ?></textarea>
                        <p class="description">
                            <b><?php _e("Note", "spoki") ?></b>: <?php _e('The customer will send you this message only the non-working days and times.<br/>Set the working days and times in the settings panel.', "spoki") ?>
                        </p>
                        <script>
                            (function () {
                                function toggleNonWorkingText(enable) {
                                    const el = document.getElementById('single-product-button-non-working-text');
                                    if (enable) {
                                        el.removeAttribute('disabled');
                                    } else {
                                        el.setAttribute('disabled', true);
                                    }
                                }

                                const nonWorkingTextToggleEl = document.getElementById('single-product-button-non-working-text-toggle');
                                toggleNonWorkingText(nonWorkingTextToggleEl.getAttribute('checked'))
                                nonWorkingTextToggleEl.addEventListener('click', e => toggleNonWorkingText(e.target.checked));
                            })();
                        </script>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="single_product_button_color">
							<?php _e('Color', "spoki") ?>
                        </label>
                    </th>
                    <td>
                        <input type="color"
                               id="single_product_button_color"
                               name="<?php echo SPOKI_OPTIONS ?>[woocommerce][single_product_button_color]"
                               value="<?php echo (isset($this->options['woocommerce']['single_product_button_color']) && $this->options['woocommerce']['single_product_button_color'] !== '') ? $this->options['woocommerce']['single_product_button_color'] : '#23D366' ?>"
                        >
                        <p class="description">
                            <b><?php _e("Note", "spoki") ?></b>: <?php _e('Set the background color of the button (Suggested: <i>#23D366</i> - <i>rgb(35, 211, 102)</i>)', "spoki") ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="single_product_button_border">
                            <b><?php _e('Border type', "spoki") ?></b>
                        </label>
                    </th>
                    <td>
						<?php $single_product_button_border = isset($this->options['woocommerce']['single_product_button_border']) ? $this->options['woocommerce']['single_product_button_border'] : 'rounded' ?>
                        <select name="<?php echo SPOKI_OPTIONS ?>[woocommerce][single_product_button_border]" id="single_product_button_border" class="regular-text">
                            <option value="rounded" <?php echo $single_product_button_border == 'rounded' ? 'selected' : '' ?>><?php echo __('Rounded', "spoki") ?></option>
                            <option value="squared" <?php echo $single_product_button_border == 'squared' ? 'selected' : '' ?>><?php echo __('Squared', "spoki") ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="single_product_button_font_size">
							<?php _e('Font size', "spoki") ?> (px)
                        </label>
                    </th>
                    <td>
                        <label>
                            <input type="number" id="single_product_button_font_size"
                                   name="<?php echo SPOKI_OPTIONS ?>[woocommerce][single_product_button_font_size]"
                                   placeholder="12"
                                   value="<?php echo (isset($this->options['woocommerce']['single_product_button_font_size']) && $this->options['woocommerce']['single_product_button_font_size'] !== '') ? $this->options['woocommerce']['single_product_button_font_size'] : '12' ?>">
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label>
							<?php _e('Margin', "spoki") ?> (px)
                        </label>
                    </th>
                    <td>
                        <div class="spacing-items">
                            <label>
								<?php _e('Top', "spoki") ?>
                                <input type="number" id="single_product_button_margin_top"
                                       name="<?php echo SPOKI_OPTIONS ?>[woocommerce][single_product_button_margin_top]"
                                       placeholder="4"
                                       value="<?php echo (isset($this->options['woocommerce']['single_product_button_margin_top']) && $this->options['woocommerce']['single_product_button_margin_top'] !== '') ? $this->options['woocommerce']['single_product_button_margin_top'] : '4' ?>">
                            </label>
                            <label>
								<?php _e('Right', "spoki") ?>
                                <input type="number" id="single_product_button_margin_right"
                                       name="<?php echo SPOKI_OPTIONS ?>[woocommerce][single_product_button_margin_right]"
                                       placeholder="0"
                                       value="<?php echo (isset($this->options['woocommerce']['single_product_button_margin_right']) && $this->options['woocommerce']['single_product_button_margin_right'] !== '') ? $this->options['woocommerce']['single_product_button_margin_right'] : '0' ?>">
                            </label>
                            <label>
								<?php _e('Bottom', "spoki") ?>
                                <input type="number" id="single_product_button_margin_bottom"
                                       name="<?php echo SPOKI_OPTIONS ?>[woocommerce][single_product_button_margin_bottom]"
                                       placeholder="4"
                                       value="<?php echo (isset($this->options['woocommerce']['single_product_button_margin_bottom']) && $this->options['woocommerce']['single_product_button_margin_bottom'] !== '') ? $this->options['woocommerce']['single_product_button_margin_bottom'] : '4' ?>">
                            </label>
                            <label>
								<?php _e('Left', "spoki") ?>
                                <input type="number" id="single_product_button_margin_left"
                                       name="<?php echo SPOKI_OPTIONS ?>[woocommerce][single_product_button_margin_left]"
                                       placeholder="0"
                                       value="<?php echo (isset($this->options['woocommerce']['single_product_button_margin_left']) && $this->options['woocommerce']['single_product_button_margin_left'] !== '') ? $this->options['woocommerce']['single_product_button_margin_left'] : '0' ?>">
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label>
							<?php _e('Padding', "spoki") ?> (px)
                        </label>
                    </th>
                    <td>
                        <div class="spacing-items">
                            <label>
								<?php _e('Top', "spoki") ?>
                                <input type="number" id="single_product_button_padding_top"
                                       name="<?php echo SPOKI_OPTIONS ?>[woocommerce][single_product_button_padding_top]"
                                       placeholder="8"
                                       value="<?php echo (isset($this->options['woocommerce']['single_product_button_padding_top']) && $this->options['woocommerce']['single_product_button_padding_top'] !== '') ? $this->options['woocommerce']['single_product_button_padding_top'] : '8' ?>">
                            </label>
                            <label>
								<?php _e('Right', "spoki") ?>
                                <input type="number" id="single_product_button_padding_right"
                                       name="<?php echo SPOKI_OPTIONS ?>[woocommerce][single_product_button_padding_right]"
                                       placeholder="14"
                                       value="<?php echo (isset($this->options['woocommerce']['single_product_button_padding_right']) && $this->options['woocommerce']['single_product_button_padding_right'] !== '') ? $this->options['woocommerce']['single_product_button_padding_right'] : '14' ?>">
                            </label>
                            <label>
								<?php _e('Bottom', "spoki") ?>
                                <input type="number" id="single_product_button_padding_bottom"
                                       name="<?php echo SPOKI_OPTIONS ?>[woocommerce][single_product_button_padding_bottom]"
                                       placeholder="8"
                                       value="<?php echo (isset($this->options['woocommerce']['single_product_button_padding_bottom']) && $this->options['woocommerce']['single_product_button_padding_bottom'] !== '') ? $this->options['woocommerce']['single_product_button_padding_bottom'] : '8' ?>">
                            </label>
                            <label>
								<?php _e('Left', "spoki") ?>
                                <input type="number" id="single_product_button_padding_left"
                                       name="<?php echo SPOKI_OPTIONS ?>[woocommerce][single_product_button_padding_left]"
                                       placeholder="14"
                                       value="<?php echo (isset($this->options['woocommerce']['single_product_button_padding_left']) && $this->options['woocommerce']['single_product_button_padding_left'] !== '') ? $this->options['woocommerce']['single_product_button_padding_left'] : '14' ?>">
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label><?php _e('Hide on non-working days and times', "spoki") ?></label>
                    </th>
                    <td>
                        <input type="checkbox" id="single_product_button_hide_non_working"
                               name="<?php echo SPOKI_OPTIONS ?>[woocommerce][single_product_button_hide_non_working]"
                               value="1" <?php if (isset($this->options['woocommerce']['single_product_button_hide_non_working'])) echo checked(1, $this->options['woocommerce']['single_product_button_hide_non_working'], false) ?>>

                        <label for="single_product_button_hide_non_working"><?php _e('Hide the button on non-working days and times', "spoki") ?></label>
                        <p class="description">
                            <b><?php _e("Note", "spoki") ?></b>: <?php _e('Set the working days and times in the settings panel', "spoki") ?>
                        </p>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>

    <div <?php if ($current_section != 'cart') echo "style='display:none'" ?> >
        <h2>
			<?php _e('Cart page', "spoki") ?>
            <a href="#TB_inline?&width=300&inlineId=buttons-info-dialog" class="thickbox button-info">ℹ</a>
        </h2>
        <p><?php _e('Add a WhatsApp button in the WooCommerce Cart Page.', "spoki") ?></p>
        <img class="cover-image" src="<?php echo plugins_url() . '/' . SPOKI_PLUGIN_NAME . '/assets/images/cart-page-button.png' ?>"/>

        <fieldset <?php if (false == $has_wc) : ?>disabled<?php endif ?>>
            <table class="form-table">
                <tr>
                    <th>
                        <label for="cart_button_check">
                            <b><?php _e('Enable', "spoki") ?></b>
                        </label>
                    </th>
                    <td>
                        <input type="checkbox" id="cart_button_check"
                               name="<?php echo SPOKI_OPTIONS ?>[woocommerce][cart_button_check]"
                               value="1" <?php if (isset($this->options['woocommerce']['cart_button_check'])) echo checked(1, $this->options['woocommerce']['cart_button_check'], false) ?>
                        />
                        <label for="cart_button_check">
							<?php _e('Enable the button', "spoki") ?>
                        </label>
                        <p class="description">
                            <b><?php _e("Note", "spoki") ?></b>: <?php _e('If you enable the chat button you make it visible in the cart page', "spoki") ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="cart_button_hide_checkout_button_check">
                            <b><?php _e('Hide "Proceed to Checkout" button', "spoki") ?></b>
                        </label>
                    </th>
                    <td>
                        <input type="checkbox" id="cart_button_hide_checkout_button_check"
                               name="<?php echo SPOKI_OPTIONS ?>[woocommerce][cart_button_hide_checkout_button_check]"
                               value="1" <?php if (isset($this->options['woocommerce']['cart_button_hide_checkout_button_check'])) echo checked(1, $this->options['woocommerce']['cart_button_hide_checkout_button_check'], false) ?>
                        />
                        <label for="cart_button_hide_checkout_button_check">
							<?php _e('Hide "Proceed to Checkout" button and show the WhatsApp button only in the cart page', "spoki") ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="<?php echo SPOKI_OPTIONS ?>[woocommerce][cart_button_cta]">
                            <b><?php _e('Button text (CTA)', "spoki") ?></b>
                        </label>
                    </th>
                    <td>
                        <input type="text"
                               id="cart_button_cta"
                               class="regular-text"
                               name="<?php echo SPOKI_OPTIONS ?>[woocommerce][cart_button_cta]"
                               value="<?php if (isset($this->options['woocommerce']['cart_button_cta'])) echo $this->options['woocommerce']['cart_button_cta'] ?>"
                               placeholder="<?php _e("Order via WhatsApp", "spoki") ?>"
                        />
                        <p class="description">
                            <b><?php _e("Note", "spoki") ?></b>: <?php _e('Enter the text visible on the button', "spoki") ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="<?php echo SPOKI_OPTIONS ?>[woocommerce][cart_button_text]">
                            <b><?php _e('Customer Message', "spoki") ?></b>
                        </label>
                    </th>
                    <td>
                        <textarea class="regular-text"
                                  name="<?php echo SPOKI_OPTIONS ?>[woocommerce][cart_button_text]"
                                  id="<?php echo SPOKI_OPTIONS ?>[woocommerce][cart_button_text]"
                                  rows="5"
                                  placeholder="<?php _e('Hi, I want to buy:', "spoki") ?>"><?php if (isset($this->options['woocommerce']['cart_button_text'])) echo $this->options['woocommerce']['cart_button_text'] ?></textarea>
                        <p class="description">
                            <b><?php _e("Note", "spoki") ?></b>: <?php _e('The customer will send you the following message via WhatsApp whenever press on this button', "spoki") ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="<?php echo SPOKI_OPTIONS ?>[buttons][cart_button_text]">
							<?php _e('Customer Message on non-working days and times', "spoki") ?>
                        </label>
                    </th>
                    <td>
                        <label>
                            <input id="cart-button-non-working-text-toggle" type="checkbox" name="<?php echo SPOKI_OPTIONS ?>[woocommerce][cart_button_non_working_text_enabled]"
                                   value="1" <?php if (isset($this->options['woocommerce']['cart_button_non_working_text_enabled'])) echo checked(1, $this->options['woocommerce']['cart_button_non_working_text_enabled'], false) ?>>
							<?php _e('Enable alternative message on non-working days and times', "spoki") ?>
                        </label>
                        <br/>
                        <textarea
                                id="cart-button-non-working-text"
                                class="regular-text"
                                name="<?php echo SPOKI_OPTIONS ?>[woocommerce][cart_button_non_working_text]"
                                cols="50" rows="5"
                                placeholder="<?php _e('Hi, I would like to receive more information about your business', "spoki") ?>"><?php if (isset($this->options['woocommerce']['cart_button_non_working_text']) && $this->options['woocommerce']['cart_button_non_working_text'] !== '') echo $this->options['woocommerce']['cart_button_non_working_text'] ?></textarea>
                        <p class="description">
                            <b><?php _e("Note", "spoki") ?></b>: <?php _e('The customer will send you this message only the non-working days and times.<br/>Set the working days and times in the settings panel.', "spoki") ?>
                        </p>
                        <script>
                            (function () {
                                function toggleNonWorkingText(enable) {
                                    const el = document.getElementById('cart-button-non-working-text');
                                    if (enable) {
                                        el.removeAttribute('disabled');
                                    } else {
                                        el.setAttribute('disabled', true);
                                    }
                                }

                                const nonWorkingTextToggleEl = document.getElementById('cart-button-non-working-text-toggle');
                                toggleNonWorkingText(nonWorkingTextToggleEl.getAttribute('checked'))
                                nonWorkingTextToggleEl.addEventListener('click', e => toggleNonWorkingText(e.target.checked));
                            })();
                        </script>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="cart_button_color">
							<?php _e('Color', "spoki") ?>
                        </label>
                    </th>
                    <td>
                        <input type="color"
                               id="cart_button_color"
                               name="<?php echo SPOKI_OPTIONS ?>[woocommerce][cart_button_color]"
                               value="<?php echo (isset($this->options['woocommerce']['cart_button_color']) && $this->options['woocommerce']['cart_button_color'] !== '') ? $this->options['woocommerce']['cart_button_color'] : '#23D366' ?>"
                        >
                        <p class="description">
                            <b><?php _e("Note", "spoki") ?></b>: <?php _e('Set the background color of the button (Suggested: <i>#23D366</i> - <i>rgb(35, 211, 102)</i>)', "spoki") ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="cart_button_border">
                            <b><?php _e('Border type', "spoki") ?></b>
                        </label>
                    </th>
                    <td>
						<?php $cart_button_border = isset($this->options['woocommerce']['cart_button_border']) ? $this->options['woocommerce']['cart_button_border'] : 'rounded' ?>
                        <select name="<?php echo SPOKI_OPTIONS ?>[woocommerce][cart_button_border]" id="cart_button_border" class="regular-text">
                            <option value="rounded" <?php echo $cart_button_border == 'rounded' ? 'selected' : '' ?>><?php echo __('Rounded', "spoki") ?></option>
                            <option value="squared" <?php echo $cart_button_border == 'squared' ? 'selected' : '' ?>><?php echo __('Squared', "spoki") ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="cart_button_font_size">
							<?php _e('Font size', "spoki") ?> (px)
                        </label>
                    </th>
                    <td>
                        <label>
                            <input type="number" id="cart_button_font_size"
                                   name="<?php echo SPOKI_OPTIONS ?>[woocommerce][cart_button_font_size]"
                                   placeholder="12"
                                   value="<?php echo (isset($this->options['woocommerce']['cart_button_font_size']) && $this->options['woocommerce']['cart_button_font_size'] !== '') ? $this->options['woocommerce']['cart_button_font_size'] : '12' ?>">
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label>
							<?php _e('Margin', "spoki") ?> (px)
                        </label>
                    </th>
                    <td>
                        <div class="spacing-items">
                            <label>
								<?php _e('Top', "spoki") ?>
                                <input type="number" id="cart_button_margin_top"
                                       name="<?php echo SPOKI_OPTIONS ?>[woocommerce][cart_button_margin_top]"
                                       placeholder="4"
                                       value="<?php echo (isset($this->options['woocommerce']['cart_button_margin_top']) && $this->options['woocommerce']['cart_button_margin_top'] !== '') ? $this->options['woocommerce']['cart_button_margin_top'] : '4' ?>">
                            </label>
                            <label>
								<?php _e('Right', "spoki") ?>
                                <input type="number" id="cart_button_margin_right"
                                       name="<?php echo SPOKI_OPTIONS ?>[woocommerce][cart_button_margin_right]"
                                       placeholder="0"
                                       value="<?php echo (isset($this->options['woocommerce']['cart_button_margin_right']) && $this->options['woocommerce']['cart_button_margin_right'] !== '') ? $this->options['woocommerce']['cart_button_margin_right'] : '0' ?>">
                            </label>
                            <label>
								<?php _e('Bottom', "spoki") ?>
                                <input type="number" id="cart_button_margin_bottom"
                                       name="<?php echo SPOKI_OPTIONS ?>[woocommerce][cart_button_margin_bottom]"
                                       placeholder="4"
                                       value="<?php echo (isset($this->options['woocommerce']['cart_button_margin_bottom']) && $this->options['woocommerce']['cart_button_margin_bottom'] !== '') ? $this->options['woocommerce']['cart_button_margin_bottom'] : '4' ?>">
                            </label>
                            <label>
								<?php _e('Left', "spoki") ?>
                                <input type="number" id="cart_button_margin_left"
                                       name="<?php echo SPOKI_OPTIONS ?>[woocommerce][cart_button_margin_left]"
                                       placeholder="0"
                                       value="<?php echo (isset($this->options['woocommerce']['cart_button_margin_left']) && $this->options['woocommerce']['cart_button_margin_left'] !== '') ? $this->options['woocommerce']['cart_button_margin_left'] : '0' ?>">
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label>
							<?php _e('Padding', "spoki") ?> (px)
                        </label>
                    </th>
                    <td>
                        <div class="spacing-items">
                            <label>
								<?php _e('Top', "spoki") ?>
                                <input type="number" id="cart_button_padding_top"
                                       name="<?php echo SPOKI_OPTIONS ?>[woocommerce][cart_button_padding_top]"
                                       placeholder="8"
                                       value="<?php echo (isset($this->options['woocommerce']['cart_button_padding_top']) && $this->options['woocommerce']['cart_button_padding_top'] !== '') ? $this->options['woocommerce']['cart_button_padding_top'] : '8' ?>">
                            </label>
                            <label>
								<?php _e('Right', "spoki") ?>
                                <input type="number" id="cart_button_padding_right"
                                       name="<?php echo SPOKI_OPTIONS ?>[woocommerce][cart_button_padding_right]"
                                       placeholder="14"
                                       value="<?php echo (isset($this->options['woocommerce']['cart_button_padding_right']) && $this->options['woocommerce']['cart_button_padding_right'] !== '') ? $this->options['woocommerce']['cart_button_padding_right'] : '14' ?>">
                            </label>
                            <label>
								<?php _e('Bottom', "spoki") ?>
                                <input type="number" id="cart_button_padding_bottom"
                                       name="<?php echo SPOKI_OPTIONS ?>[woocommerce][cart_button_padding_bottom]"
                                       placeholder="8"
                                       value="<?php echo (isset($this->options['woocommerce']['cart_button_padding_bottom']) && $this->options['woocommerce']['cart_button_padding_bottom'] !== '') ? $this->options['woocommerce']['cart_button_padding_bottom'] : '8' ?>">
                            </label>
                            <label>
								<?php _e('Left', "spoki") ?>
                                <input type="number" id="cart_button_padding_left"
                                       name="<?php echo SPOKI_OPTIONS ?>[woocommerce][cart_button_padding_left]"
                                       placeholder="14"
                                       value="<?php echo (isset($this->options['woocommerce']['cart_button_padding_left']) && $this->options['woocommerce']['cart_button_padding_left'] !== '') ? $this->options['woocommerce']['cart_button_padding_left'] : '14' ?>">
                            </label>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label><?php _e('Hide on non-working days and times', "spoki") ?></label>
                    </th>
                    <td>
                        <input type="checkbox" id="cart_button_hide_non_working"
                               name="<?php echo SPOKI_OPTIONS ?>[woocommerce][cart_button_hide_non_working]"
                               value="1" <?php if (isset($this->options['woocommerce']['cart_button_hide_non_working'])) echo checked(1, $this->options['woocommerce']['cart_button_hide_non_working'], false) ?>>

                        <label for="cart_button_hide_non_working"><?php _e('Hide the button on non-working days and times', "spoki") ?></label>
                        <p class="description">
                            <b><?php _e("Note", "spoki") ?></b>: <?php _e('Set the working days and times in the settings panel', "spoki") ?>
                        </p>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>

    <div <?php if ($current_section != 'other_buttons') echo "style='display:none'" ?> >
        <h2>
			<?php _e('Shortcode Button', "spoki") ?>
            <a href="#TB_inline?&width=300&inlineId=buttons-info-dialog" class="thickbox button-info">ℹ</a>
        </h2>
        <p><?php _e('Put WhatsApp Buttons anywhere using the shortcode.', "spoki") ?></p>
        <img class="cover-image" src="<?php echo plugins_url() . '/' . SPOKI_PLUGIN_NAME . '/assets/images/spoki-buttons.png' ?>"/>
        <h4><?php _e('How to use shortcodes', "spoki") ?></h4>
        <p>
			<?php _e('Shortcodes can be used on pages and posts in WordPress.', "spoki") ?>
        </p>
        <ul>
            <li>• <?php _e('If you are using the block editor, there is a shortcode block you can use to <b>paste the shortcode</b> in.', "spoki") ?></li>
            <li>• <?php _e('If you are using the classic editor, you can paste the shortcode on the page or post.', "spoki") ?></li>
        </ul>
        <h4><?php _e('Shortcode', "spoki") ?></h4>
        <p>
            <code>[spoki_button]</code>: <?php _e('use this shortcode to render the WhatsApp button in WordPress.', "spoki") ?>
        </p>
        <h4><?php _e('Args (or Arguments)', "spoki") ?></h4>
        <p>
			<?php _e('“Args” are ways to make the shortcode more specific. For example, by adding <code>cta="contact us"</code> to the <code>[spoki_button]</code> shortcode, it will create a WhatsApp button with the cta "contact us".', "spoki") ?>
        </p>
        <ul>
            <li>• <code style="color: black">cta</code>: <?php _e('The text inside the button.', "spoki") ?></li>
            <li>• <code style="color: black">message</code>: <?php _e('The text of the message the customer will send to phone via WhatsApp on button click. <i>Default value: ""</i>.', "spoki") ?></li>
            <li>• <code>phone</code>: <?php /* translators: %1$s: Telephone. */
				printf(__('The WhatsApp telephone that will receive the message. <i>Default value: "%1$s" <small>(Your WhatsApp Telephone)</small></i>.', "spoki"), Spoki()->shop['telephone']) ?></li>
            <li>• <code>color</code>: <?php _e('The background color of the button. <i>Default value: "#23D366"</i>.', "spoki") ?></li>
            <li>• <code>border_type</code>: <?php _e('"rounded" | "squared". <i>Default value: "rounded"</i>.', "spoki") ?></li>
            <li>• <code>margin_top</code>: <?php _e('<i>Default value: "4"</i> px.', "spoki") ?></li>
            <li>• <code>margin_bottom</code>: <?php _e('<i>Default value: "4"</i> px.', "spoki") ?></li>
            <li>• <code>margin_left</code>: <?php _e('<i>Default value: "0"</i> px.', "spoki") ?></li>
            <li>• <code>margin_right</code>: <?php _e('<i>Default value: "0"</i> px.', "spoki") ?></li>
            <li>• <code>padding_top</code>: <?php _e('<i>Default value: "8"</i> px.', "spoki") ?></li>
            <li>• <code>padding_bottom</code>: <?php _e('<i>Default value: "8"</i> px.', "spoki") ?></li>
            <li>• <code>padding_left</code>: <?php _e('<i>Default value: "14"</i> px.', "spoki") ?></li>
            <li>• <code>padding_right</code>: <?php _e('<i>Default value: "14"</i> px.', "spoki") ?></li>
            <li>• <code>font_size</code>: <?php _e('<i>Default value: "12"</i> px.', "spoki") ?></li>
            <li>• <code>id</code>: <?php _e('The id selector of the button.', "spoki") ?></li>
            <li>• <code>class_names</code>: <?php _e('The additional classes of the button.', "spoki") ?></li>
            <li>• <code>custom_css</code>: <?php _e('insert the custom css code for the button there.', "spoki") ?></li>
            <li>• <code>title</code>: <?php _e('The html title attr of the link of the button.', "spoki") ?></li>
            <li>• <code>hide_non_working</code>: <?php _e('Hide the button on non-working days and times. <i>To enable set value = "1"</i>.', "spoki") ?></li>
            <li>• <code>enable_non_working_message</code>: <?php _e('Enable alternative message on non-working days and times. <i>To enable set value = "1"</i>.', "spoki") ?></li>
            <li>• <code>non_working_message</code>: <?php _e('The customer will send you this message only the non-working days and times.', "spoki") ?></li>
        </ul>
        <h4 style="margin-bottom: 0"><?php _e('Example', "spoki") ?> 1</h4>
        <p class="description"><?php _e('Button with cta only.', "spoki") ?></p>
        <p>
            <code>[spoki_button cta="contact us"]</code>
			<?php do_shortcode('[spoki_button cta="contact us"]') ?>
        </p>
        <h4 style="margin-bottom: 0"><?php _e('Example', "spoki") ?> 2</h4>
        <p class="description"><?php _e('Button with cta & message.', "spoki") ?></p>
        <p>
            <code>[spoki_button cta="contact us" message="Hi, I need more info about your company"]</code>
			<?php do_shortcode('[spoki_button cta="contact us" message="Hi, I need more info about your company"]') ?>
        </p>
        <h4 style="margin-bottom: 0"><?php _e('Example', "spoki") ?> 3</h4>
        <p class="description"><?php _e('Customized button with custom phone.', "spoki") ?></p>
        <p>
            <code>[spoki_button phone="+393331234567" cta="contact us" message="Hi, I need more info about your company" border_type="squared" color="#34B7F1" font_size="18"]</code>
			<?php do_shortcode('[spoki_button phone="+393331234567" cta="contact us" message="Hi, I need more info about your company" border_type="squared" color="#34B7F1" font_size="18"]') ?>
        </p>
        <h4 style="margin-bottom: 0"><?php _e('Example', "spoki") ?> 4</h4>
        <p class="description"><?php _e('Custom css with custom class_names.', "spoki") ?></p>
        <p>
            <code>[spoki_button cta="contact us" message="Hi, I need more info about your company" class_names="dark" custom_css=".dark .spoki-button{background:black!important}"]</code>
			<?php do_shortcode('[spoki_button cta="contact us" message="Hi, I need more info about your company" class_names="dark" custom_css=".dark .spoki-button{background:black!important}"]') ?>
        </p>
    </div>

    <div <?php if ($current_section != 'elementor') echo "style='display:none'" ?> >
        <h2>
			<?php _e('Elementor Button', "spoki") ?>
            <a href="#TB_inline?&width=300&inlineId=buttons-info-dialog" class="thickbox button-info">ℹ</a>
        </h2>
        <p><?php _e('Spoki WhatsApp Button basic element for Elementor.', "spoki") ?></p>
        <a href="<?php echo plugins_url() . '/' . SPOKI_PLUGIN_NAME . '/assets/images/elementor-sections.png' ?>" target="_blank">
            <img class="cover-image" src="<?php echo plugins_url() . '/' . SPOKI_PLUGIN_NAME . '/assets/images/elementor-sections.png' ?>"/>
        </a>
        <a href="<?php echo plugins_url() . '/' . SPOKI_PLUGIN_NAME . '/assets/images/elementor-button.png' ?>" target="_blank">
            <img class="cover-image" src="<?php echo plugins_url() . '/' . SPOKI_PLUGIN_NAME . '/assets/images/elementor-button.png' ?>"/>
        </a>
        <a href="<?php echo plugins_url() . '/' . SPOKI_PLUGIN_NAME . '/assets/images/elementor-button-2.png' ?>" target="_blank">
            <img class="cover-image" src="<?php echo plugins_url() . '/' . SPOKI_PLUGIN_NAME . '/assets/images/elementor-button-2.png' ?>"/>
        </a>
        <p>
        <ol>
            <li> <?php _e('Customize the page with Elementor', "spoki") ?></li>
            <li> <?php _e('Select the "<b>Spoki WhatsApp Button</b>" from the "BASIC" section', "spoki") ?></li>
            <li> <?php _e('Drag & Drop the element where you want', "spoki") ?></li>
            <li> <?php _e('Customize the button', "spoki") ?></li>
            <li> <?php _e('Enjoy', "spoki") ?> 🎉</li>
        </ol>
        </p>
    </div>

    <div <?php if ($current_section != 'qr_code') echo "style='display:none'" ?> >
        <h2>
			<?php _e('QR Code', "spoki") ?>
            <a href="#TB_inline?&width=300&inlineId=buttons-info-dialog" class="thickbox button-info">ℹ</a>
        </h2>
        <p><?php _e('Generate WhatsApp QR Code.', "spoki") ?></p>
        <img class="cover-image" src="<?php echo plugins_url() . '/' . SPOKI_PLUGIN_NAME . '/assets/images/spoki-qr-code.png' ?>"/>
        <p>
        <ol>
            <li> <?php _e('Create the WhatsApp Qr Code', "spoki") ?></li>
            <li> <?php _e('Print or insert the snippet where you want', "spoki") ?></li>
            <li> <?php _e('Enjoy', "spoki") ?> 🎉</li>
        </ol>
        </p>
        <a href="https://spoki.app/widget-builder?ref=plugin" target="_blank">
            <button type="button" class="button button-primary">
				<?php _e('Create QR Code', "spoki") ?>
            </button>
        </a>
    </div>

	<?php
	if ($current_section != 'fab' && $current_section != 'other_buttons' && $current_section != 'elementor' && $current_section != 'qr_code'):
		if ($has_wc) : submit_button(null, 'primary', 'submit-templates');
		else : ?>
            <p>
				<?php _e("Install and activate the <strong>WooCommerce</strong> plugin to enable the Spoki features for WooCommerce.", "spoki") ?>
            </p>
		<?php
		endif;
	endif;
	?>
</div>