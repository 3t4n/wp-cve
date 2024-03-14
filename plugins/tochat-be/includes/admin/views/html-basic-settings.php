<?php defined( 'ABSPATH' ) || exit; ?>
<div class="wrap">
    <h1>Settings</h1>
    <?php settings_errors(); ?>
    <?php $tab = isset($_GET['tab']) ? $_GET['tab'] : null; ?>

    <nav class="nav-tab-wrapper">
        <a href="?page=to-chat-be-whatsapp_settings" class="nav-tab <?php echo ( $tab === null ) ? 'nav-tab-active' : ''; ?>">Basic Settings</a>
        <a href="?page=to-chat-be-whatsapp_settings&tab=type_and_chat" class="nav-tab <?php echo ( $tab === 'type_and_chat' ) ? 'nav-tab-active' : '';?>">Type And Chat</a>
        <a href="?page=to-chat-be-whatsapp_settings&tab=just_whatsapp_icon" class="nav-tab <?php echo ( $tab === 'just_whatsapp_icon' ) ? 'nav-tab-active' : '';?>">Just WhatsApp Icon</a>
        <a href="?page=to-chat-be-whatsapp_settings&tab=woo_order_button" class="nav-tab <?php echo ( $tab === 'woo_order_button' ) ? 'nav-tab-active' : '';?>">Woo Order Button</a>
    </nav>

    <div class="tab-content">
        <?php if ( 'type_and_chat' === $tab ) : ?>
            <form action="options.php" method="post" class="tochatbe-setting-table">
                <?php settings_fields( 'tochatbe-type-and-chat-settings' ); ?>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label for="">Type and Chat</label>
                            </th>
                            <td>
                                <input type="checkbox" name="tochatbe_type_and_chat_settings[type_and_chat]" <?php checked( 'yes', tochatbe_type_and_chat_option( 'type_and_chat' ), true); ?>> Enable/ Disable
                                <p class="description">You can enable and disable type to chat option.</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="">Support Number</label>
                            </th>
                            <td>
                                <input type="number" name="tochatbe_type_and_chat_settings[type_and_chat_number]" class="regular-text" value="<?php echo esc_attr( tochatbe_type_and_chat_option( 'type_and_chat_number' ) ); ?>" step="1" min="0">
                                <p class="description">Enter contact person WhatsApp number.</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="">Type And Chat Placeholder</label>
                            </th>
                            <td>
                                <input type="text" name="tochatbe_type_and_chat_settings[type_and_chat_placeholder]" class="regular-text" value="<?php echo esc_attr( tochatbe_type_and_chat_option( 'type_and_chat_placeholder' ) ); ?>">
                                <p class="description">You can change type and chat input placeholder.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?php submit_button(); ?>
            </form>

        <?php elseif ( 'just_whatsapp_icon' === $tab ): ?>

            <form action="options.php" method="post" class="tochatbe-setting-table">
                <?php settings_fields( 'tochatbe-just-whatsapp-icon-settings' ); ?>
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="">Just WhatsApp Icon</label>
                            </th>
                            <td>
                                <input 
                                    type="checkbox" 
                                    name="tochatbe_just_whatsapp_icon_settings[status]" 
                                    <?php checked( 'yes', tochatbe_just_whatsapp_icon_option( 'status' ) ); ?> > Enable/ Disable
                                <p class="description">If you want to expand the widget and add more agents, etc... Please disable this option and configure the plugin using other sections in the widget.</p>
                                <p class="description">For more information, visit our website <a href="https://tochat.be/click-to-chat/wordpress-plugin">https://tochat.be/click-to-chat/wordpress-plugin</a></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="">WhatsApp Number</label>
                            </th>
                            <td>
                                <input 
                                    type="number" 
                                    name="tochatbe_just_whatsapp_icon_settings[number]" 
                                    class="regular-text" 
                                    step="1" 
                                    value="<?php echo esc_attr( tochatbe_just_whatsapp_icon_option( 'number' ) ); ?>">
                                <p class="description">Enter mobile phone number with the international country code, without + character. Example:  911234567890 for (+91) 1234567890</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="">Icon Link</label>
                            </th>
                            <td>
                                <input 
                                    type="text" 
                                    name="tochatbe_just_whatsapp_icon_settings[icon_link]" 
                                    class="regular-text" 
                                    value="<?php echo esc_url( tochatbe_just_whatsapp_icon_option( 'icon_link' ) ); ?>">
                                <p class="description">You can change the trigger icon by enter your own icon link or leave blank for default icon.</p>
                            </td>
                        </tr>
                    </table>
                    <?php submit_button(); ?>
                </form>

        <?php elseif ( 'woo_order_button' === $tab ): ?>

            <form action="options.php" method="post" class="tochatbe-setting-table">
                <?php settings_fields( 'tochatbe-woo-order-button-settings' ); ?>
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="">Woo Order Button</label>
                            </th>
                            <td>
                                <input type="checkbox" name="tochatbe_woo_order_button_settings[status]" <?php checked( 'yes', tochatbe_woo_order_button_option( 'status' ) ); ?> > Enable/ Disable
                            </td>
                        </tr>                    
                    </table>

                    <h3>WooCommerce Order Pre-Messages</h3>
                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label>Processing</label>
                            </th>
                            <td>
                                <textarea class="regular-text" name="tochatbe_woo_order_button_settings[pre_message_processing_order]" rows="5"><?php echo esc_textarea( tochatbe_woo_order_button_option( 'pre_message_processing_order' ) ); ?></textarea>
                                <p class="description">Enter the pre-message text for WooCommerce <strong>processing</strong> orders.</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label>Canceled</label>
                            </th>
                            <td>
                                <textarea class="regular-text" name="tochatbe_woo_order_button_settings[pre_message_canceled_order]" rows="5"><?php echo esc_textarea( tochatbe_woo_order_button_option( 'pre_message_canceled_order' ) ); ?></textarea>
                                <p class="description">Enter the pre-message text for WooCommerce <strong>canceled</strong> orders.</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label>Completed</label>
                            </th>
                            <td>
                                <textarea class="regular-text" name="tochatbe_woo_order_button_settings[pre_message_completed_order]" rows="5"><?php echo esc_textarea( tochatbe_woo_order_button_option( 'pre_message_completed_order' ) ); ?></textarea>
                                <p class="description">Enter the pre-message text for WooCommerce <strong>completed</strong> orders.</p>
                            </td>
                        </tr>
                        <?php if ( tochatbe_get_woo_order_statuses() ) : ?>
                            <?php foreach ( tochatbe_get_woo_order_statuses() as $status => $label ) :
                                if ( 'wc-processing' === $status || 'wc-cancelled' === $status || 'wc-completed' === $status ) {
                                    continue;
                                }

                                $status = str_replace( 'wc-', '', $status );
                                $status = str_replace( '-', '_', $status );
                                ?>
                            <tr>
                                <th scope="row">
                                    <label><?php echo esc_html( $label ); ?></label>
                                </th>
                                <td>
                                    <textarea class="regular-text" name="tochatbe_woo_order_button_settings[pre_message_<?php echo esc_attr( $status ); ?>_order]" rows="5"><?php echo esc_textarea( tochatbe_woo_order_button_option( 'pre_message_' . $status . '_order' ) ); ?></textarea>
                                    <p class="description">Enter the pre-message text for WooCommerce <strong><?php echo esc_html( $status ); ?></strong> orders.</p>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </table>

                    <hr>
                    <h3>Placeholders:</h3>
                    <p><strong>You can use the following Placeholders in the WooCommerce Order Pre-Messages.</strong></p>
                    <ul>
                        <li>1. <strong>{full_name}</strong>: Customer's full name.</li>
                        <li>2. <strong>{first_name}</strong>: Customer's first name.</li>
                        <li>3. <strong>{last_name}</strong>: Customer's last name.</li>
                        <li>4. <strong>{order_id}</strong>: Customer's order ID.</li>
                    </ul>
                    <?php submit_button(); ?>
                </form>

        <?php else : ?>
            <form action="options.php" method="post" class="tochatbe-setting-table">
                <?php settings_fields( 'tochatbe-basic-settings' ); ?>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label for="">Location</label>
                            </th>
                            <td>
                                <select name="tochatbe_basic_settings[location]">
                                    <option value="br" <?php selected( 'br', tochatbe_basic_option( 'location' ), true ); ?>>Bottom Right</option>
                                    <option value="bl" <?php selected( 'bl', tochatbe_basic_option( 'location' ), true ); ?>>Bottom Left</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="">Display On Mobile</label>
                            </th>
                            <td>
                                <input type="checkbox" name="tochatbe_basic_settings[on_mobile]" <?php checked( 'yes', tochatbe_basic_option( 'on_mobile' ), true); ?>> Enable/ Disable
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="">Display On Desktop</label>
                            </th>
                            <td>
                                <input type="checkbox" name="tochatbe_basic_settings[on_desktop]" <?php checked( 'yes', tochatbe_basic_option( 'on_desktop' ), true); ?>> Enable/ Disable
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label for="">Auto Popup</label>
                            </th>
                            <td>
                                <input type="checkbox" name="tochatbe_basic_settings[auto_popup_status]" <?php checked( 'yes', tochatbe_basic_option( 'auto_popup_status' ), true); ?>> Enable/ Disable
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="">Auto Popup Delay</label>
                            </th>
                            <td>
                                <input type="number" name="tochatbe_basic_settings[auto_popup_delay]" class="small-text" value="<?php echo intval( tochatbe_basic_option( 'auto_popup_delay' ) ); ?>" step="1" min="0">
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label for=""><?php esc_html_e( 'Display by Page(s)', 'tochatbe' ); ?></label>
                            </th>
                            <td>
                                <input 
                                    type="checkbox" 
                                    name="tochatbe_basic_settings[filter_by_pages][on_all_pages]"
                                    <?php checked( 'yes', tochatbe_get_filter_by_pages_option( 'on_all_pages' ), true ); ?>> <?php esc_html_e( 'On all pages.', 'tochatbe' ); ?>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for=""></label>
                            </th>
                            <td>
                                <input
                                    type="checkbox"
                                    name="tochatbe_basic_settings[filter_by_pages][on_front_page]"
                                    <?php checked( 'yes', tochatbe_get_filter_by_pages_option( 'on_front_page' ), true ); ?>> <?php esc_html_e( 'Front page only.', 'tochatbe' ); ?>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for=""></label>
                            </th>
                            <td>
                                <?php 
                                    tochatbe_page_dropdown(
                                        array(
                                            'name'      => 'tochatbe_basic_settings[filter_by_pages][include_pages][]',
                                            'multiple'  => true,
                                            'class'     => 'regular-text tochatbe-select2',
                                            'selected'  => tochatbe_get_filter_by_pages_option( 'include_pages' ),
                                        )
                                    )
                                ?>
                                <p class="description"><?php esc_html_e( 'Include on page(s)', 'tochatbe' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for=""></label>
                            </th>
                            <td>
                                <?php 
                                    tochatbe_page_dropdown(
                                        array(
                                            'name'      => 'tochatbe_basic_settings[filter_by_pages][exclude_pages][]',
                                            'multiple'  => true,
                                            'class'     => 'regular-text tochatbe-select2',
                                            'selected'  => tochatbe_get_filter_by_pages_option( 'exclude_pages' ),
                                        )
                                    )
                                ?>
                                <p class="description"><?php esc_html_e( 'Exclude on page(s)', 'tochatbe' ); ?></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label for=""><?php esc_html_e( 'Schedule', 'tochatbe' ); ?></label>
                            </th>
                            <td>
                                <table>
                                    <tbody>
                                        <tr>
                                            <td><?php esc_html_e( 'Monday', 'tochatbe') ?></td>
                                            <td><input type="checkbox" name="tochatbe_basic_settings[schedule][monday][status]" <?php checked( 'yes', tochatbe_get_schedule_option( 'monday', 'status' ), true  ); ?> /></td>
                                            <td><input type="text" name="tochatbe_basic_settings[schedule][monday][start]" class="timepicker" value="<?php echo esc_attr( tochatbe_get_schedule_option( 'monday', 'start' ) ); ?>" /></td>
                                            <td>-</td>
                                            <td><input type="text" name="tochatbe_basic_settings[schedule][monday][end]" class="timepicker" value="<?php echo esc_attr( tochatbe_get_schedule_option( 'monday', 'end' ) ); ?>" /></td>
                                        </tr>
                                        <tr>
                                            <td><?php esc_html_e( 'Tuesday', 'tochatbe') ?></td>
                                            <td><input type="checkbox" name="tochatbe_basic_settings[schedule][tuesday][status]" <?php checked( 'yes', tochatbe_get_schedule_option( 'tuesday', 'status' ), true  ); ?> /></td>
                                            <td><input type="text" name="tochatbe_basic_settings[schedule][tuesday][start]" class="timepicker" value="<?php echo esc_attr( tochatbe_get_schedule_option( 'tuesday', 'start' ) ); ?>" /></td>
                                            <td>-</td>
                                            <td><input type="text" name="tochatbe_basic_settings[schedule][tuesday][end]" class="timepicker" value="<?php echo esc_attr( tochatbe_get_schedule_option( 'tuesday', 'end' ) ); ?>" /></td>
                                        </tr>
                                        <tr>
                                            <td><?php esc_html_e( 'Wednesday', 'tochatbe') ?></td>
                                            <td><input type="checkbox" name="tochatbe_basic_settings[schedule][wednesday][status]" <?php checked( 'yes', tochatbe_get_schedule_option( 'wednesday', 'status' ), true  ); ?> /></td>
                                            <td><input type="text" name="tochatbe_basic_settings[schedule][wednesday][start]" class="timepicker" value="<?php echo esc_attr( tochatbe_get_schedule_option( 'wednesday', 'start' ) ); ?>" /></td>
                                            <td>-</td>
                                            <td><input type="text" name="tochatbe_basic_settings[schedule][wednesday][end]" class="timepicker" value="<?php echo esc_attr( tochatbe_get_schedule_option( 'wednesday', 'end' ) ); ?>" /></td>
                                        </tr>
                                        <tr>
                                            <td><?php esc_html_e( 'Thursday', 'tochatbe') ?></td>
                                            <td><input type="checkbox" name="tochatbe_basic_settings[schedule][thursday][status]" <?php checked( 'yes', tochatbe_get_schedule_option( 'thursday', 'status' ), true  ); ?> /></td>
                                            <td><input type="text" name="tochatbe_basic_settings[schedule][thursday][start]" class="timepicker" value="<?php echo esc_attr( tochatbe_get_schedule_option( 'thursday', 'start' ) ); ?>" /></td>
                                            <td>-</td>
                                            <td><input type="text" name="tochatbe_basic_settings[schedule][thursday][end]" class="timepicker" value="<?php echo esc_attr( tochatbe_get_schedule_option( 'thursday', 'end' ) ); ?>" /></td>
                                        </tr>
                                        <tr>
                                            <td><?php esc_html_e( 'Friday', 'tochatbe') ?></td>
                                            <td><input type="checkbox" name="tochatbe_basic_settings[schedule][friday][status]" <?php checked( 'yes', tochatbe_get_schedule_option( 'friday', 'status' ), true  ); ?> /></td>
                                            <td><input type="text" name="tochatbe_basic_settings[schedule][friday][start]" class="timepicker" value="<?php echo esc_attr( tochatbe_get_schedule_option( 'friday', 'start' ) ); ?>" /></td>
                                            <td>-</td>
                                            <td><input type="text" name="tochatbe_basic_settings[schedule][friday][end]" class="timepicker" value="<?php echo esc_attr( tochatbe_get_schedule_option( 'friday', 'end' ) ); ?>" /></td>
                                        </tr>
                                        <tr>
                                            <td><?php esc_html_e( 'Saturday', 'tochatbe') ?></td>
                                            <td><input type="checkbox" name="tochatbe_basic_settings[schedule][saturday][status]" <?php checked( 'yes', tochatbe_get_schedule_option( 'saturday', 'status' ), true  ); ?> /></td>
                                            <td><input type="text" name="tochatbe_basic_settings[schedule][saturday][start]" class="timepicker" value="<?php echo esc_attr( tochatbe_get_schedule_option( 'saturday', 'start' ) ); ?>" /></td>
                                            <td>-</td>
                                            <td><input type="text" name="tochatbe_basic_settings[schedule][saturday][end]" class="timepicker" value="<?php echo esc_attr( tochatbe_get_schedule_option( 'saturday', 'end' ) ); ?>" /></td>
                                        </tr>
                                        <tr>
                                            <td><?php esc_html_e( 'Sunday', 'tochatbe') ?></td>
                                            <td><input type="checkbox" name="tochatbe_basic_settings[schedule][sunday][status]" <?php checked( 'yes', tochatbe_get_schedule_option( 'sunday', 'status' ), true  ); ?> /></td>
                                            <td><input type="text" name="tochatbe_basic_settings[schedule][sunday][start]" class="timepicker" value="<?php echo esc_attr( tochatbe_get_schedule_option( 'sunday', 'start' ) ); ?>" /></td>
                                            <td>-</td>
                                            <td><input type="text" name="tochatbe_basic_settings[schedule][sunday][end]" class="timepicker" value="<?php echo esc_attr( tochatbe_get_schedule_option( 'sunday', 'end' ) ); ?>" /></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row">
                                <label for="">Custom CSS</label>
                            </th>
                            <td>
                                <textarea name="tochatbe_basic_settings[custom_css]" class="regular-text" style="height: 120px;"><?php echo wp_kses_post( tochatbe_basic_option( 'custom_css' ) ); ?></textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?php submit_button(); ?>
            </form>
        <?php endif; ?>
    </div>

</div>



<?php require_once TOCHATBE_PLUGIN_PATH . 'includes/admin/views/notifications/html-purchase-premium.php'; ?>