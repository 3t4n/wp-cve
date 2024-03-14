<?php defined( 'ABSPATH' ) || exit; ?>
<div class="wrap">
    <h1>Appearance Settings</h1>
    <?php settings_errors(); ?>
    <hr>
    <form action="options.php" method="post" class="tochatbe-setting-table">
        <?php settings_fields( 'tochatbe-appearance-settings' ); ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="">Background Color</label>
                    </th>
                    <td>
                        <input type="text" name="tochatbe_appearance_settings[background_color]" class="tochatbe-color-picker" value="<?php echo esc_attr( tochatbe_appearance_option( 'background_color' ) ); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="">Text Color</label>
                    </th>
                    <td>
                        <input type="text" name="tochatbe_appearance_settings[text_color]" class="tochatbe-color-picker" value="<?php echo esc_attr( tochatbe_appearance_option( 'text_color' ) ); ?>">
                    </td>
                </tr>
            </tbody>
        </table>
        <hr>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="">About Message</label>
                    </th>
                    <td>
                        <textarea name="tochatbe_appearance_settings[about_message]" class="regular-text" style="height: 120px;"><?php echo esc_textarea( tochatbe_appearance_option( 'about_message' ) ); ?></textarea>
                        <p class="description">This text will be on the Widget Window. Use it to describe your company, service, business.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="">Trigger Button Text</label>
                    </th>
                    <td>
                        <input type="text" name="tochatbe_appearance_settings[trigger_btn_text]" class="regular-text" value="<?php echo esc_attr( tochatbe_appearance_option( 'trigger_btn_text' ) ); ?>">
                        <p class="description">Add something like “Let´s chat”, “Can we help you?</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <hr>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="">Custom Offers</label>
                    </th>
                    <td>
                        <div style="width: 550px;">
                        <?php
                            wp_editor( 
                                tochatbe_appearance_option( 'custom_offer' ), 
                                'tochatbeCustomOffer', 
                                array(
                                    'textarea_name' => 'tochatbe_appearance_settings[custom_offer]',
                                    'editor_height' => 200,
                                ) 
                            );
                            ?>
                        </div>
                        <p class="description">You can add your custom offer here.</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php submit_button(); ?>
    </form>
</div>

<?php require_once TOCHATBE_PLUGIN_PATH . 'includes/admin/views/notifications/html-purchase-premium.php'; ?>