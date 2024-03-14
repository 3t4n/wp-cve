<?php
/**
 * Plugin Name: Pulsating Chat Button
 * Description: WhatsApp Chat🔥. Adds a pulsating WhatsApp or Telegram button 🍀 to your website. Fast and easy installation. Setting up target id GTM and YandexMetrics. Setting pre-filled Message.
 * Version: 1.2.7
 * Author: Amin Shah
 * Author URI: https://t.me/aminsha
 */

// Add a menu item in the WordPress admin panel
function amin_chat_button_plugin_menu() {
    add_menu_page(
        'Chat Button Plugin Settings',
        'Chat Button',
        'manage_options',
        'amin-chat-button-settings',
        'amin_chat_button_settings_page',
        'dashicons-whatsapp'
    );
}
add_action('admin_menu', 'amin_chat_button_plugin_menu');

// Create the settings page for the plugin
function amin_chat_button_settings_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    // Save the settings if the form is submitted
    if (isset($_POST['amin_chat_button_plugin_submit'])) {
        update_option('amin_chat_button_plugin_enabled', isset($_POST['amin_chat_button_plugin_enabled']) ? '1' : '0');
        update_option('amin_chat_button_plugin_phone', sanitize_text_field($_POST['amin_chat_button_plugin_phone']));
        update_option('amin_chat_button_plugin_text', sanitize_text_field($_POST['amin_chat_button_plugin_text']));
        update_option('amin_chat_button_plugin_position', sanitize_text_field($_POST['amin_chat_button_plugin_position']));
        update_option('amin_chat_button_tag_select', sanitize_text_field($_POST['amin_chat_button_tag_select']));
        update_option('amin_chat_button_msg_select', sanitize_text_field($_POST['amin_chat_button_msg_select']));
        update_option('amin_chat_button_plugin_target_id', sanitize_text_field($_POST['amin_chat_button_plugin_target_id']));
        update_option('amin_chat_button_plugin_gtag_report', sanitize_textarea_field(wp_unslash($_POST['amin_chat_button_plugin_gtag_report'])));
        update_option('amin_chat_button_plugin_yametrik_id', sanitize_text_field($_POST['amin_chat_button_plugin_yametrik_id']));
        update_option('amin_chat_button_plugin_yametrik_account', sanitize_text_field($_POST['amin_chat_button_plugin_yametrik_account']));
    }

    // Get the saved settings
    $enabled = get_option('amin_chat_button_plugin_enabled', '0');
    $phone = get_option('amin_chat_button_plugin_phone', '');
    $text = get_option('amin_chat_button_plugin_text', '');
    $position = get_option('amin_chat_button_plugin_position', 'right-bottom');
    $tag_select = get_option('amin_chat_button_tag_select', 'event');
    $tag_select = get_option('amin_chat_button_msg_select', 'WhatsApp');
    $target_id = get_option('amin_chat_button_plugin_target_id', '');
    $gtag_report = get_option('amin_chat_button_plugin_gtag_report', '');
    $yametrik_id = get_option('amin_chat_button_plugin_yametrik_id', '');
    $yametrik_account = get_option('amin_chat_button_plugin_yametrik_account', '');

    // Display the settings form
    ?>
    <div class="cbp-wrap">
        <h1><?php echo esc_html__('Chat Button Plugin Settings', 'amin-chat-button'); ?></h1>

        <form method="post" action="">
            <table class="cbp-form-table">
                <tr>
                    <th scope="row"><?php echo esc_html__('Enable Chat Button', 'amin-chat-button'); ?></th>
                    <td>
                        <label for="amin_chat_button_plugin_enabled">
                            <input type="checkbox" id="amin_chat_button_plugin_enabled" name="amin_chat_button_plugin_enabled" value="1" <?php checked($enabled, '1'); ?>>
                            <?php echo esc_html__('Enable the chat button on the website', 'amin-chat-button'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__('Select messenger', 'amin-chat-button'); ?></th>
                    <td>
                        <select id="amin_chat_button_msg_select" name="amin_chat_button_msg_select">
                            <option value="WhatsApp" <?php selected($tag_select, 'WhatsApp'); ?>><?php echo esc_html__('WhatsApp', 'amin-chat-button'); ?></option>
                            <option value="Telegram" <?php selected($tag_select, 'Telegram'); ?>><?php echo esc_html__('Telegram', 'amin-chat-button'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__('Phone Number', 'amin-chat-button'); ?></th>
                    <td>
                        <input type="text" id="amin_chat_button_plugin_phone" name="amin_chat_button_plugin_phone" value="<?php echo esc_attr($phone); ?>">
                        <p class="cbp-description"><?php echo esc_html__('Enter the WhatsApp or Telegram phone number in international format without the "+" sign.', 'amin-chat-button'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__('First message text', 'amin-chat-button'); ?></th>
                    <td>
                        <textarea id="amin_chat_button_plugin_text" name="amin_chat_button_plugin_text" rows="3" cols="40"><?php echo esc_attr($text); ?></textarea>
                        <p class="cbp-description"><?php echo esc_html__('Enter your message text only for Whatsapp.', 'amin-chat-button'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__('Button Position', 'amin-chat-button'); ?></th>
                    <td>
                        <select id="amin_chat_button_plugin_position" name="amin_chat_button_plugin_position">
                            <option value="cbp-left-bottom" <?php selected($position, 'cbp-left-bottom'); ?>><?php echo esc_html__('Left Bottom', 'amin-chat-button'); ?></option>
                            <option value="cbp-right-bottom" <?php selected($position, 'cbp-right-bottom'); ?>><?php echo esc_html__('Right Bottom', 'amin-chat-button'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__('YandexMetrika Account ID', 'amin-chat-button'); ?></th>
                    <td>                
                        <input type="text" id="amin_chat_button_plugin_yametrik_account" name="amin_chat_button_plugin_yametrik_account" value="<?php echo esc_attr($yametrik_account); ?>">
                        <p class="cbp-description"><?php echo esc_html__('Enter the ID account of the Yandex.', 'amin-chat-button'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__('YandexMetrika Target ID', 'amin-chat-button'); ?></th>
                    <td>
                        <input type="text" id="amin_chat_button_plugin_yametrik_id" name="amin_chat_button_plugin_yametrik_id" value="<?php echo esc_attr($yametrik_id); ?>"> 
                        <p class="cbp-description"><?php echo esc_html__('Enter the target ID to insert into the button.', 'amin-chat-button'); ?></p>                       
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__('Tag Google', 'amin-chat-button'); ?></th>
                    <td>
                        <select id="amin_chat_button_tag_select" name="amin_chat_button_tag_select">
                            <option value="config" <?php selected($tag_select, 'config'); ?>><?php echo esc_html__('config', 'amin-chat-button'); ?></option>
                            <option value="event" <?php selected($tag_select, 'event'); ?>><?php echo esc_html__('event', 'amin-chat-button'); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__('GTM Target ID', 'amin-chat-button'); ?></th>
                    <td>
                        <input type="text" id="amin_chat_button_plugin_target_id" name="amin_chat_button_plugin_target_id" value="<?php echo esc_attr($target_id); ?>">
                        <p class="cbp-description"><?php echo esc_html__('Enter the target ID to insert into the button.', 'amin-chat-button'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php echo esc_html__('Function gtag_report_conversion', 'amin-chat-button'); ?></th>
                    <td>
                        <textarea id="amin_chat_button_plugin_gtag_report" name="amin_chat_button_plugin_gtag_report" rows="6" cols="40"><?php echo esc_textarea(get_option('amin_chat_button_plugin_gtag_report', '')); ?></textarea>
                        <p class="cbp-description"><?php echo esc_html__('Add a function to call gtag_report_conversion when your Target ID is clicked. The function is automatically wrapped in a <script></script> tag', 'amin-chat-button'); ?></p>
                    </td>
                </tr>
            </table>

            <p class="cbp-submit">
                <input type="submit" name="amin_chat_button_plugin_submit" class="cbp-button-primary" value="<?php echo esc_attr__('Save Settings', 'amin-chat-button'); ?>">
            </p>
        </form>
    </div>
    <?php
}

// Add the WhatsApp button to the website
function amin_chat_button_plugin_add_button() {
    $enabled = get_option('amin_chat_button_plugin_enabled', '0');
    $phone = get_option('amin_chat_button_plugin_phone', '');
    $text = get_option('amin_chat_button_plugin_text', '');
    $position = get_option('amin_chat_button_plugin_position', 'right-bottom');
    $tag_select = get_option('amin_chat_button_tag_select', 'event');
    $tag_select = get_option('amin_chat_button_msg_select', 'WhatsApp');
    $target_id = get_option('amin_chat_button_plugin_target_id', '');
    $gtag_report = get_option('amin_chat_button_plugin_gtag_report', '');
    $yametrik_id = get_option('amin_chat_button_plugin_yametrik_id', '');
    $yametrik_account = get_option('amin_chat_button_plugin_yametrik_account', '');
    $gtag_report = get_option('amin_chat_button_plugin_gtag_report', '');

    if ($tag_select == 'WhatsApp') {
        $link = 'https://api.whatsapp.com/send?phone=' . rawurlencode($phone) . '&text=' . rawurlencode($text);
    }
    if ($tag_select == 'Telegram') {
        $link = 'https://t.me/+' . rawurlencode($phone);
    }

        // Начало HTML кода кнопки
        echo '<a href="' . esc_url($link) . '" target="_blank" rel="noopener noreferrer nofollow" onclick="';

        // Добавляем вызовы Yandex.Metrica и Google Analytics
        echo 'safeYm(\'' . esc_attr($yametrik_account) . '\',\'reachGoal\', \'' . esc_attr($yametrik_id) . '\'), gtag(\'' . esc_attr($tag_select) . '\', \'' . esc_attr($target_id) . '\')';

        // Добавляем условие для вызова gtag_report_conversion
        if (!empty($gtag_report)) {
            echo ', gtag_report_conversion()';
        }

        // Завершение HTML кода кнопки
       

        if ($tag_select == 'WhatsApp') {
        echo '" class="cbp-whatsapp-button ' . esc_attr($position) . '">';

        echo '<div type="button"><div class="cbp-text-button"><img src="data:image/svg+xml;base64,PHN2ZyBhcmlhLWhpZGRlbj0idHJ1ZSIgZm9jdXNhYmxlPSJmYWxzZSIgZGF0YS1wcmVmaXg9ImZhYiIgZGF0YS1pY29uPSJ3aGF0c2FwcCIgY2xhc3M9InN2Zy1pbmxpbmUtLWZhIGZhLXdoYXRzYXBwIGZhLXctMTQiIHJvbGU9ImltZyIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB2aWV3Qm94PSIwIDAgNDQ4IDUxMiI+PHBhdGggZmlsbD0iY3VycmVudENvbG9yIiBkPSJNMzgwLjkgOTcuMUMzMzkgNTUuMSAyODMuMiAzMiAyMjMuOSAzMmMtMTIyLjQgMC0yMjIgOTkuNi0yMjIgMjIyIDAgMzkuMSAxMC4yIDc3LjMgMjkuNiAxMTFMMCA0ODBsMTE3LjctMzAuOWMzMi40IDE3LjcgNjguOSAyNyAxMDYuMSAyN2guMWMxMjIuMyAwIDIyNC4xLTk5LjYgMjI0LjEtMjIyIDAtNTkuMy0yNS4yLTExNS02Ny4xLTE1N3ptLTE1NyAzNDEuNmMtMzMuMiAwLTY1LjctOC45LTk0LTI1LjdsLTYuNy00LTY5LjggMTguM0w3MiAzNTkuMmwtNC40LTdjLTE4LjUtMjkuNC0yOC4yLTYzLjMtMjguMi05OC4yIDAtMTAxLjcgODIuOC0xODQuNSAxODQuNi0xODQuNSA0OS4zIDAgOTUuNiAxOS4yIDEzMC40IDU0LjEgMzQuOCAzNC45IDU2LjIgODEuMiA1Ni4xIDEzMC41IDAgMTAxLjgtODQuOSAxODQuNi0xODYuNiAxODQuNnptMTAxLjItMTM4LjJjLTUuNS0yLjgtMzIuOC0xNi4yLTM3LjktMTgtNS4xLTEuOS04LjgtMi44LTEyLjUgMi44LTMuNyA1LjYtMTQuMyAxOC0xNy42IDIxLjgtMy4yIDMuNy02LjUgNC4yLTEyIDEuNC0zMi42LTE2LjMtNTQtMjkuMS03NS41LTY2LTUuNy05LjggNS43LTkuMSAxNi4zLTMwLjMgMS44LTMuNy45LTYuOS0uNS05LjctMS40LTIuOC0xMi41LTMwLjEtMTcuMS00MS4yLTQuNS0xMC44LTkuMS05LjMtMTIuNS05LjUtMy4yLS4yLTYuOS0uMi0xMC42LS4yLTMuNyAwLTkuNyAxLjQtMTQuOCA2LjktNS4xIDUuNi0xOS40IDE5LTE5LjQgNDYuMyAwIDI3LjMgMTkuOSA1My43IDIyLjYgNTcuNCAyLjggMy43IDM5LjEgNTkuNyA5NC44IDgzLjggMzUuMiAxNS4yIDQ5IDE2LjUgNjYuNiAxMy45IDEwLjctMS42IDMyLjgtMTMuNCAzNy40LTI2LjQgNC42LTEzIDQuNi0yNC4xIDMuMi0yNi40LTEuMy0yLjUtNS0zLjktMTAuNS02LjZ6Ij48L3BhdGg+PC9zdmc+"><span>WhatsApp</span></div></div>';

        }
        if ($tag_select == 'Telegram') {
        echo '" class="cbp-tg-button ' . esc_attr($position) . '">';

        echo '<div type="button"><div class="cbp-text-button"><img src="data:image/png;base64,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"><span>Telegram</span></div></div>';
        }


        echo '</a>';
}



// Enqueue styles and scripts
function amin_chat_button_plugin_enqueue_scripts() {
    wp_enqueue_style('amin-chat-button-style', plugin_dir_url(__FILE__) . 'style.css',[],'1.8');

    wp_register_script( 'amin-chat-button-safeym-script', '' );
    wp_enqueue_script( 'amin-chat-button-safeym-script' );

    wp_add_inline_script('amin-chat-button-safeym-script', "
    function safeYm(metrikaId, goalName, addit) {
        if (typeof ym === 'function' && Boolean(metrikaId)) {    
            ym(metrikaId, goalName, addit);
        } else {
            console.error('Yandex.Metrika haven\'t loaded or blocked');
        }
        return false;
    }
    function safeGtag(event, targetId, addit, callback) {
        if (typeof gtag === 'function' && Boolean(targetId)) {    
            gtag(event, targetId, addit, callback);
        } else {
            console.error('Gtag haven\'t loaded or blocked');
        }
        return false;
    }

    ", 'before');
}

add_action('wp_enqueue_scripts', 'amin_chat_button_plugin_enqueue_scripts');

function amin_chat_button_enqueue_custom_admin_style() {
        wp_enqueue_style('amin-chat-button-admin', plugin_dir_url(__FILE__) . 'admin.css',[],'2.0');
}
add_action( 'admin_enqueue_scripts', 'amin_chat_button_enqueue_custom_admin_style' );

// Add settings link on plugin page
function amin_chat_button_plugin_settings_link($links) {
    $settings_link = '<a href="options-general.php?page=amin-chat-button">' . esc_html__('Settings', 'amin-chat-button') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}

$plugin_basename = plugin_basename(__FILE__);
add_filter('cbp_plugin_action_links_' . $plugin_basename, 'amin_chat_button_plugin_settings_link');


// Display the WhatsApp button on the website
add_action('wp_footer', 'amin_chat_button_plugin_add_button');

function amin_chat_button_output_script_in_header() {
    $script_code = get_option('amin_chat_button_plugin_gtag_report', '');
    if (!empty($script_code)) {
        echo '<script type="text/javascript">' . $script_code . '</script>';
    }
}

add_action('wp_head', 'amin_chat_button_output_script_in_header');

