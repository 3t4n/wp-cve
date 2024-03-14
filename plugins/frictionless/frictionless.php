<?php
/**
 * Frictionless
 *
 * @package           Frictionless
 * @author            ScheduleEngine team
 * @copyright         2023 ServiceTitan, Inc.
 *
 * @wordpress-plugin
 * Plugin Name:       Frictionless
 * Plugin URI:        https://www.scheduleengine.com/
 * Description:       Unlock appointment booking
 * Version:           0.0.23
 * Requires at least: 4.7
 * Requires PHP:      7.2
 * Author:            ScheduleEngine team
 * Author URI:        https://www.scheduleengine.com/
 * Text Domain:       Frictionless
 */

add_action('wp_footer', 'frictionless_widget_footer');
add_action('admin_menu', 'frictionless_admin_menu');
add_action('admin_notices', 'frictionless_admin_notice');
add_action('wp_enqueue_scripts', 'frictionless_startup_scripts');
add_action('admin_enqueue_scripts', 'frictionless_editor_scripts'); // load editor-specific scripts/css
add_action('admin_init', 'frictionless_settings_init');
add_shortcode('frictionless_form', 'frictionless_form_shortcode_handler');

function frictionless_check_apikey()
{
  // not configured.
  $settings = get_frictionless_settings();
  if (!$settings->booking_widget_key || $settings->chat_widget_key) {
    return;
  }
}

function frictionless_admin_menu()
{
  add_options_page(
    'Frictionless Options',
    'Frictionless',
    'manage_options',
    'frictionless',
    'frictionless_settings_html'
  );
}

function frictionless_admin_notice()
{
  $is_plugins_page = substr($_SERVER['PHP_SELF'], -11) == 'plugins.php';

  if ($is_plugins_page && function_exists('admin_url')) {
    echo '<div class="error"><p><strong>' .
      sprintf(
        __('<a href="%s">Enter your Schedule Engine Booking Widget API Key</a>.', 'frictionless'),
        admin_url('options-general.php?page=frictionless')
      ) .
      '</strong></p></div>';
  }
}

function frictionless_widget_footer()
{
  $booking_settings = get_option('frictionless_booking_settings');
  $chat_settings = get_option('frictionless_chat_settings');
  $imgPath = plugin_dir_url(__FILE__) . 'img/calendar.png';

  if (isset($chat_settings['se_chat_enable']) && $chat_settings['se_chat_enable'] === 'true') {
    echo wp_get_script_tag([
      'src' => 'https://webchat.scheduleengine.net/webchat-v1.js',
    ]);
  }

  if (isset($booking_settings['se_booking_enable']) && $booking_settings['se_booking_enable'] === 'true') {
    if (isset($booking_settings['se_booking_position']) && $booking_settings['se_booking_position'] !== 'hide') { ?>

      <div 
        class="booking-widget float-<?php echo esc_attr($booking_settings['se_booking_position']); ?>"
        style="background-color: <?php echo esc_attr(
          $booking_settings['se_booking_background_color']
        ); ?>; color: <?php echo esc_attr($booking_settings['se_booking_text_color']); ?>"        
      >
        <div class="booking-widget-image" src="<?php echo esc_attr(
          $imgPath
        ); ?>" style="border-color: <?php echo esc_attr(
  $booking_settings['se_booking_background_color']
); ?>;background-color: <?php echo esc_attr($booking_settings['se_booking_text_color']); ?>;">
        <svg version="1.0" xmlns="http://www.w3.org/2000/svg" fill="<?php echo esc_attr(
          $booking_settings['se_booking_background_color']
        ); ?>" viewBox="0 0 64 64"><path d="M18.506 15.71c0 2.673.007 2.898.132 3.114.348.626.744.858 1.35.788.236-.023.382-.085.459-.193.062-.085.18-.193.264-.24.362-.208.376-.324.376-3.431l-.007-2.875-1.287-.024-1.287-.015v2.875zM41.672 15.694c0 1.978.028 2.921.077 3.037.118.248.591.742.793.827.264.108.71.093.932-.03.286-.17.62-.542.703-.781.049-.14.076-1.245.076-3.046v-2.828l-1.294-.024-1.287-.015v2.86z"/><path d="M13.1 15.763c-.243.031-.466.132-.682.302-.174.131-.362.247-.41.247-.167 0-.946 1.12-1.12 1.592-.098.27-.105 1.917-.09 15.373l.02 15.072.23.44c.132.248.264.449.292.449.035 0 .118.123.18.27.063.147.147.27.196.27.041 0 .23.14.417.31.18.17.39.309.452.309.063 0 .292.07.515.155.39.154.57.154 18.304.154 17.754 0 17.921 0 18.269-.154.195-.085.41-.155.48-.155.132 0 1.05-.742 1.05-.85 0-.031.091-.163.195-.278.112-.124.265-.348.348-.503l.153-.286V33.2c0-13.541-.014-15.303-.104-15.52-.16-.363-.932-1.368-1.058-1.368-.055 0-.23-.108-.375-.24-.376-.332-.842-.386-3.013-.363l-1.808.023-.007 1.353c-.014 1.94-.105 2.334-.69 2.96-.604.65-.869.78-1.676.812-.835.038-1.14-.047-1.67-.44-.89-.673-1.03-1.09-1.064-3.2l-.028-1.523-9.01.015-9.008.023.007 1.183c.013 1.19-.077 2.017-.25 2.295a.686.686 0 0 0-.091.286c0 .201-.69.928-1.141 1.206-.167.1-.397.132-1.086.132-.82 0-.89-.016-1.245-.217-.438-.263-1.03-.927-1.183-1.345-.083-.224-.11-.68-.125-1.94l-.028-1.638-1.892.007c-1.043.008-2.052.04-2.254.062zM49.464 35.55v11.98l-.236.27-.23.278H13.705l-.208-.324-.21-.317v-11.88c0-6.53.022-11.902.05-11.926.02-.03 8.16-.054 18.088-.054h18.04V35.55z"/><path d="M24.62 26.337a.584.584 0 0 0-.264.247c-.048.093-.076.82-.076 1.97v1.81l.18.239.182.24h5.106l.188-.217.188-.216-.014-1.925-.021-1.917-.174-.146c-.167-.132-.348-.147-2.637-.163-1.753-.007-2.518.008-2.657.078zM33.039 26.321c-.404.155-.41.201-.41 2.226v1.847l.201.224.202.225H38.097l.187-.217.188-.216-.014-1.925-.02-1.917-.175-.146c-.166-.132-.347-.147-2.594-.163-1.531-.007-2.498.016-2.63.062zM41.29 26.398l-.209.163-.02 1.924-.015 1.925.188.216.188.217H46.535l.14-.225c.139-.216.146-.324.146-2.11-.007-1.97-.021-2.07-.327-2.18-.084-.03-1.239-.061-2.568-.069-2.337-.015-2.434-.007-2.636.14zM16.203 33.64c-.265.178-.285.348-.258 2.404.021 1.755.021 1.77.188 1.917.153.14.306.147 2.679.147 2.497 0 2.511 0 2.706-.178l.195-.17v-1.924c0-1.693-.014-1.933-.118-2.064a.71.71 0 0 0-.237-.186c-.07-.023-1.217-.054-2.546-.061-2.164-.016-2.435 0-2.61.115zM24.635 33.594c-.32.147-.355.356-.355 2.272v1.763l.18.24.182.239h2.532c2.505 0 2.54 0 2.741-.17l.209-.17v-1.902c0-2.079-.021-2.18-.404-2.28-.375-.085-4.89-.085-5.085.008zM33.025 33.61c-.39.177-.39.208-.397 2.195v1.824l.181.24.181.239h5.1l.194-.193.188-.201v-3.788l-.174-.185c-.09-.108-.208-.193-.257-.193-2.762-.047-4.835-.023-5.016.062zM41.408 33.579c-.348.2-.362.278-.362 2.257v1.854l.195.21.195.208h5.099l.14-.201c.139-.193.146-.302.146-2.048 0-1.963-.035-2.188-.327-2.273-.272-.07-4.96-.085-5.086-.007zM16.21 40.906c-.272.178-.285.317-.265 2.396.021 1.569.035 1.77.147 1.893.11.124.327.14 2.504.163 2.623.03 2.936.007 3.047-.224.105-.225.098-3.911-.014-4.058-.208-.278-.348-.294-2.796-.302-2.192-.007-2.442.008-2.623.132zM24.69 40.828c-.368.14-.41.348-.41 2.265 0 2.048.028 2.164.5 2.241.168.024 1.371.04 2.672.024 2.227-.024 2.387-.031 2.526-.17.139-.14.146-.217.146-2.033 0-1.183-.028-1.948-.077-2.04a.557.557 0 0 0-.257-.24c-.223-.108-4.828-.147-5.1-.047zM33.06 40.836c-.41.14-.439.294-.418 2.373.021 1.654.035 1.863.146 1.986.112.132.32.14 2.77.14h2.65l.132-.186c.125-.162.132-.325.132-2.033 0-1.113-.028-1.909-.07-1.994-.16-.332-.278-.348-2.747-.355-1.378 0-2.463.03-2.595.07z"/></svg>
        </div>
        <div class="booking-widget-text"><?php esc_html_e($booking_settings['se_booking_text']); ?></div>
      </div>
    <?php }
  }
}

function frictionless_form_shortcode_handler($attributes)
{
  $form_id = $attributes['form_id'];

  if (!$form_id) {
    return '';
  }

  return "<div id=\"cr-form-$form_id\"></div>";
}

/**
 * Enqueue frontend scripts/styles
 */
function frictionless_startup_scripts()
{
  $current_url = get_bloginfo('url');
  $debugUrls = ['localhost', '.local'];
  $isDebug = preg_match('(' . implode('|', $debugUrls) . ')', $current_url);

  wp_enqueue_script('frictionless-scripts', plugin_dir_url(__FILE__) . '/js/utils.js');

  $envVar = [
    'isDebug' => $isDebug,
    'env' => $isDebug ? 'integration' : 'production',
    'bookingUrl' => 'https://embed' . ($isDebug ? '.integration' : '') . '.scheduleengine.net/schedule-engine-v3.js',
  ];
  wp_localize_script('frictionless-scripts', 'envSettings', $envVar);

  wp_enqueue_style('frictionless-css', plugin_dir_url(__FILE__) . '/css/styles.css');
  wp_localize_script('frictionless-scripts', 'frictionless_chat_settings', get_option('frictionless_chat_settings'));
  wp_localize_script(
    'frictionless-scripts',
    'frictionless_booking_settings',
    get_option('frictionless_booking_settings')
  );
}

/**
 * Enqueue backend scripts/styles
 */
function frictionless_editor_scripts()
{
  wp_enqueue_script('frictionless-editor-scripts', plugin_dir_url(__FILE__) . '/js/editor-utils.js');
  wp_enqueue_style('frictionless-editor-css', plugin_dir_url(__FILE__) . '/css/editor-styles.css');
}

function frictionless_settings_init()
{
  /**
   * REGISTER BOOKING SETTING
   */
  register_setting('frictionless_booking_settings', 'frictionless_booking_settings', [
    'default' => [
      'se_booking_enable' => 'true',
      'se_booking_position' => 'left',
      'se_booking_text' => 'Book Now',
      'se_booking_text_color' => '#ffffff',
      'se_booking_background_color' => '#2e4c51',
      'se_booking_api_key' => '',
      'se_booking_api_key' => '',
      'se_booking_selector_id' => '',
      'se_booking_selector_class' => '',
    ],
  ]);

  /**
   * REGISTER CHAT SETTING
   */
  register_setting('frictionless_chat_settings', 'frictionless_chat_settings', [
    'default' => [
      'se_chat_enable' => 'true',
      'se_chat_auto_open' => 'false',
      'se_chat_auto_open_delay' => '3',
      'se_chat_auto_open_expiration' => '2',
      'se_chat_auto_open_mobile' => 'false',
      'se_chat_auto_open_mobile_delay' => '3',
      'se_chat_show_button' => 'true',
      'se_chat_show_button_mobile' => 'true',
      'se_chat_button_text' => 'Chat with Us',
      'se_chat_height' => '600',
      'se_chat_initial_message' => 'Hi, how can we help you today?',
      'se_chat_mobile_height_percentage' => '100',
      'se_chat_modal' => 'false',
      'se_chat_modal_transparency' => '60',
      'se_chat_position' => 'right',
      'se_chat_remember_state' => 'true',
      'se_chat_title' => 'Welcome',
      'se_chat_width' => '400',
      'se_chat_agent_bubble_background_color' => '#808080',
      'se_chat_agent_bubble_text_color' => '#ffffff',
      'se_chat_background_color' => '#ffffff',
      'se_chat_bubble_background_color' => '#f1f1f1',
      'se_chat_bubble_text_color' => '#000000',
      'se_chat_button_background_color' => '#3c425c',
      'se_chat_button_text_color' => '#ffffff',
      'se_chat_primary_accent_color' => '#808080',
      'se_chat_primary_accent_text_color' => '#ffffff',
      'se_chat_send_button_background_color' => '#808080',
      'se_chat_send_button_text_color' => '#ffffff',
      'se_chat_suggested_response_text_color' => '#808080',
      'se_chat_logo_url' => '',
      'se_chat_api_key' => '',
    ],
  ]);

  /**
   * BOOKING WIDGET SECTIONS
   */
  add_settings_section(
    'frictionless_booking_settings_section', // id
    '', // title
    'frictionless_section_callback', // callback function for html
    'frictionless-booking' // page slug
  );

  /**
   * BOOKING WIDGET FIELDS
   */
  add_settings_field(
    'se_booking_enable', // id
    'Enable', // name
    'frictionless_checkbox_input_callback', // callback
    'frictionless-booking', // page slug
    'frictionless_booking_settings_section', // section id
    [
      'label_for' => 'se_booking_enable',
      'description' => 'Check to enable the booking widget.',
    ]
  );

  add_settings_field(
    'se_booking_api_key', // id
    'API Key', // name
    'frictionless_text_input_callback', // callback
    'frictionless-booking', // page slug
    'frictionless_booking_settings_section', // section id
    [
      'label_for' => 'se_booking_api_key',
      'description' => 'The embed api key for the client.',
    ]
  );

  add_settings_field(
    'se_booking_position',
    'Position',
    'frictionless_select_input_callback',
    'frictionless-booking',
    'frictionless_booking_settings_section',
    [
      'label_for' => 'se_booking_position',
      'options' => ['left', 'right', 'hide'],
      'description' =>
        'Select the screen position where the floating booking widget should appear. Optionally you can hide it.',
    ]
  );

  add_settings_field(
    'se_booking_text',
    'Widget Text',
    'frictionless_text_input_callback',
    'frictionless-booking',
    'frictionless_booking_settings_section',
    [
      'label_for' => 'se_booking_text',
      'description' => 'The text that will appear on the booking widget.',
    ]
  );

  add_settings_field(
    'se_booking_text_color',
    'Widget Text Color',
    'frictionless_color_input_callback',
    'frictionless-booking',
    'frictionless_booking_settings_section',
    [
      'label_for' => 'se_booking_text_color',
      'description' => 'The text color of the booking widget button.',
    ]
  );

  add_settings_field(
    'se_booking_background_color',
    'Widget Background Color',
    'frictionless_color_input_callback',
    'frictionless-booking',
    'frictionless_booking_settings_section',
    [
      'label_for' => 'se_booking_background_color',
      'description' => 'The text color of the booking widget button.',
    ]
  );

  add_settings_field(
    'se_booking_selector_class',
    'CSS Selector Class',
    'frictionless_text_input_callback',
    'frictionless-booking',
    'frictionless_booking_settings_section',
    [
      'label_for' => 'se_booking_selector_class',
      'description' =>
        'Comma separated CSS class that the booking widget should hook up to. e.g.  .schedule-appointment, .book-home-visit',
    ]
  );

  add_settings_field(
    'se_booking_selector_id',
    'Element Id',
    'frictionless_text_input_callback',
    'frictionless-booking',
    'frictionless_booking_settings_section',
    [
      'label_for' => 'se_booking_selector_id',
      'description' =>
        'If have html element with "id" and want to trigger booking widget on click of that element then provide comma separated list of element ids.',
    ]
  );

  /**
   * CHAT WIDGET SETTINGS SECTIONS
   */
  add_settings_section(
    'frictionless_chat_settings_permanent_section',
    '',
    'frictionless_section_callback',
    'frictionless-chat-required'
  );

  add_settings_section(
    'frictionless_chat_settings_general_section',
    '',
    'frictionless_section_callback',
    'frictionless-chat-general'
  );

  add_settings_section(
    'frictionless_chat_settings_color_section',
    '',
    'frictionless_section_callback',
    'frictionless-chat-color'
  );

  add_settings_section(
    'frictionless_chat_settings_advance_section',
    '',
    'frictionless_section_callback',
    'frictionless-chat-advanced'
  );

  /**
   * CHAT WIDGET FIELDS
   */
  add_settings_field(
    'se_chat_enable', // id
    'Enable', // name
    'frictionless_checkbox_input_callback', // callback
    'frictionless-chat-required', // page slug
    'frictionless_chat_settings_permanent_section', // section id
    [
      'label_for' => 'se_chat_enable',
      'description' => 'Check to enable the chat widget.',
    ]
  );

  add_settings_field(
    'se_chat_api_key',
    'API Key',
    'frictionless_text_input_callback',
    'frictionless-chat-required',
    'frictionless_chat_settings_permanent_section',
    [
      'label_for' => 'se_chat_api_key',
      'description' => 'The embed api key for the client.',
    ]
  );

  add_settings_field(
    'se_chat_position',
    'Position',
    'frictionless_select_input_callback',
    'frictionless-chat-general',
    'frictionless_chat_settings_general_section',
    [
      'label_for' => 'se_chat_position',
      'options' => ['left', 'right'],
      'description' => 'The position chat button and the chat widget will appear. (\'right\' or \'left\').',
    ]
  );

  add_settings_field(
    'se_chat_width',
    'Width',
    'frictionless_number_input_callback',
    'frictionless-chat-general',
    'frictionless_chat_settings_general_section',
    [
      'label_for' => 'se_chat_width',
      'trailing_text' => 'px',
      'description' => 'The width of the chat window.',
    ]
  );

  add_settings_field(
    'se_chat_height',
    'Height',
    'frictionless_number_input_callback',
    'frictionless-chat-general',
    'frictionless_chat_settings_general_section',
    [
      'label_for' => 'se_chat_height',
      'trailing_text' => 'px',
      'description' => 'The height of the chat window.',
    ]
  );

  add_settings_field(
    'se_chat_mobile_height_percentage',
    'Mobile Height Percentage',
    'frictionless_number_input_callback',
    'frictionless-chat-general',
    'frictionless_chat_settings_general_section',
    [
      'label_for' => 'se_chat_mobile_height_percentage',
      'trailing_text' => '%',
      'description' => 'The chat widget in mobile format will appear at a percentage of the screen height.',
    ]
  );

  add_settings_field(
    'se_chat_show_button',
    'Show Button',
    'frictionless_checkbox_input_callback',
    'frictionless-chat-general',
    'frictionless_chat_settings_general_section',
    [
      'label_for' => 'se_chat_show_button',
      'description' => 'This will automatically show a "Chat with us button".',
    ]
  );

  add_settings_field(
    'se_chat_show_button_mobile',
    'Show Button Mobile',
    'frictionless_checkbox_input_callback',
    'frictionless-chat-general',
    'frictionless_chat_settings_general_section',
    [
      'label_for' => 'se_chat_show_button_mobile',
      'description' => 'This will hide the button for mobile devices.',
    ]
  );

  add_settings_field(
    'se_chat_title',
    'Title',
    'frictionless_text_input_callback',
    'frictionless-chat-general',
    'frictionless_chat_settings_general_section',
    [
      'label_for' => 'se_chat_title',
      'description' => 'Title for the chat header.',
    ]
  );

  add_settings_field(
    'se_chat_initial_message',
    'Initial Message',
    'frictionless_text_input_callback',
    'frictionless-chat-general',
    'frictionless_chat_settings_general_section',
    [
      'label_for' => 'se_chat_initial_message',
      'description' => 'This will be the opening message the customer will see.',
    ]
  );

  add_settings_field(
    'se_chat_logo_url',
    'Logo Url (optional)',
    'frictionless_text_input_callback',
    'frictionless-chat-general',
    'frictionless_chat_settings_general_section',
    [
      'label_for' => 'se_chat_logo_url',
      'description' => 'Copy and paste a full qualifying url of your logo.',
    ]
  );

  add_settings_field(
    'se_chat_button_text',
    'Button Text',
    'frictionless_text_input_callback',
    'frictionless-chat-general',
    'frictionless_chat_settings_general_section',
    [
      'label_for' => 'se_chat_button_text',
      'description' => 'The text in the chat launch button.',
    ]
  );

  add_settings_field(
    'se_chat_agent_bubble_background_color',
    'Agent Bubble Background Color',
    'frictionless_color_input_callback',
    'frictionless-chat-color',
    'frictionless_chat_settings_color_section',
    [
      'label_for' => 'se_chat_agent_bubble_background_color',
      'description' => 'Color of the agent bubble.',
    ]
  );

  add_settings_field(
    'se_chat_agent_bubble_text_color',
    'Agent Bubble Text Color',
    'frictionless_color_input_callback',
    'frictionless-chat-color',
    'frictionless_chat_settings_color_section',
    [
      'label_for' => 'se_chat_agent_bubble_text_color',
      'description' => 'Color of the agent text.',
    ]
  );

  add_settings_field(
    'se_chat_background_color',
    'Background Color',
    'frictionless_color_input_callback',
    'frictionless-chat-color',
    'frictionless_chat_settings_color_section',
    [
      'label_for' => 'se_chat_background_color',
      'description' => 'The background color of the widget.',
    ]
  );

  add_settings_field(
    'se_chat_bubble_background_color',
    'Bubble Background Color',
    'frictionless_color_input_callback',
    'frictionless-chat-color',
    'frictionless_chat_settings_color_section',
    [
      'label_for' => 'se_chat_bubble_background_color',
      'description' => 'The color of the customer chat bubbles.',
    ]
  );

  add_settings_field(
    'se_chat_bubble_text_color',
    'Bubble Text Color',
    'frictionless_color_input_callback',
    'frictionless-chat-color',
    'frictionless_chat_settings_color_section',
    [
      'label_for' => 'se_chat_bubble_text_color',
      'description' => 'The color of the customer chat text.',
    ]
  );

  add_settings_field(
    'se_chat_button_background_color',
    'Button Background Color',
    'frictionless_color_input_callback',
    'frictionless-chat-color',
    'frictionless_chat_settings_color_section',
    [
      'label_for' => 'se_chat_button_background_color',
      'description' => 'The background color of the chat launch button.',
    ]
  );

  add_settings_field(
    'se_chat_button_text_color',
    'Button Text Color',
    'frictionless_color_input_callback',
    'frictionless-chat-color',
    'frictionless_chat_settings_color_section',
    [
      'label_for' => 'se_chat_button_text_color',
      'description' => 'The color of the text in the chat launch button.',
    ]
  );

  add_settings_field(
    'se_chat_primary_accent_color',
    'Primary Accent Color',
    'frictionless_color_input_callback',
    'frictionless-chat-color',
    'frictionless_chat_settings_color_section',
    [
      'label_for' => 'se_chat_primary_accent_color',
      'description' => 'Color used to decorate the widget.',
    ]
  );

  add_settings_field(
    'se_chat_primary_accent_text_color',
    'Primary Accent Text Color',
    'frictionless_color_input_callback',
    'frictionless-chat-color',
    'frictionless_chat_settings_color_section',
    [
      'label_for' => 'se_chat_primary_accent_text_color',
      'description' => 'Color used along with the `Primary Accent Color` to decorate the widget.',
    ]
  );

  add_settings_field(
    'se_chat_send_button_background_color',
    'Send Button Background Color',
    'frictionless_color_input_callback',
    'frictionless-chat-color',
    'frictionless_chat_settings_color_section',
    [
      'label_for' => 'se_chat_send_button_background_color',
      'description' => 'Color used to decorate the send button.',
    ]
  );

  add_settings_field(
    'se_chat_send_button_text_color',
    'Send Button Text Color',
    'frictionless_color_input_callback',
    'frictionless-chat-color',
    'frictionless_chat_settings_color_section',
    [
      'label_for' => 'se_chat_send_button_text_color',
      'description' => 'Color used to decorate the send icon.',
    ]
  );

  add_settings_field(
    'se_chat_suggested_response_text_color',
    'Suggested Response Text Color',
    'frictionless_color_input_callback',
    'frictionless-chat-color',
    'frictionless_chat_settings_color_section',
    [
      'label_for' => 'se_chat_suggested_response_text_color',
      'description' => 'Color used to decorate the suggested responses.',
    ]
  );

  add_settings_field(
    'se_chat_auto_open',
    'Auto Open',
    'frictionless_checkbox_input_callback',
    'frictionless-chat-advanced',
    'frictionless_chat_settings_advance_section',
    [
      'label_for' => 'se_chat_auto_open',
      'description' => 'Will automatically open the chat upon initialization of the script.',
    ]
  );

  add_settings_field(
    'se_chat_auto_open_delay',
    'Auto Open Delay',
    'frictionless_number_input_callback',
    'frictionless-chat-advanced',
    'frictionless_chat_settings_advance_section',
    [
      'label_for' => 'se_chat_auto_open_delay',
      'trailing_text' => 'seconds',
      'description' => 'The amount of seconds to wait before automatically opening the chat window.',
    ]
  );

  add_settings_field(
    'se_chat_auto_open_expiration',
    'Auto Open Expiration',
    'frictionless_number_input_callback',
    'frictionless-chat-advanced',
    'frictionless_chat_settings_advance_section',
    [
      'label_for' => 'se_chat_auto_open_expiration',
      'trailing_text' => 'hours',
      'description' => 'The number of hours that must elapse when the chat window state will be reset.',
    ]
  );

  add_settings_field(
    'se_chat_auto_open_mobile',
    'Auto Open Mobile',
    'frictionless_checkbox_input_callback',
    'frictionless-chat-advanced',
    'frictionless_chat_settings_advance_section',
    [
      'label_for' => 'se_chat_auto_open_mobile',
      'description' =>
        'This will cause the chat window to open automatically on initial visit in a small format browser. If this value is not specified, the Auto Open value will be used.',
    ]
  );

  add_settings_field(
    'se_chat_auto_open_mobile_delay',
    'Auto Open Mobile Delay',
    'frictionless_number_input_callback',
    'frictionless-chat-advanced',
    'frictionless_chat_settings_advance_section',
    [
      'label_for' => 'se_chat_auto_open_mobile_delay',
      'trailing_text' => 'seconds',
      'description' =>
        'The amount of seconds to wait before automatically opening the chat window in a browser window smaller than 768 pixels. If this value is not specified, the Auto Open Delay value will be used.',
    ]
  );

  add_settings_field(
    'se_chat_modal',
    'Modal',
    'frictionless_checkbox_input_callback',
    'frictionless-chat-advanced',
    'frictionless_chat_settings_advance_section',
    [
      'label_for' => 'se_chat_modal',
      'description' => 'Whether the chat window is modal.',
    ]
  );

  add_settings_field(
    'se_chat_modal_transparency',
    'Modal Transparency',
    'frictionless_number_input_callback',
    'frictionless-chat-advanced',
    'frictionless_chat_settings_advance_section',
    [
      'label_for' => 'se_chat_modal_transparency',
      'trailing_text' => '%',
      'description' => 'The transparency percentage for the screen in modal mode.',
    ]
  );

  add_settings_field(
    'se_chat_remember_state',
    'Remember State',
    'frictionless_checkbox_input_callback',
    'frictionless-chat-advanced',
    'frictionless_chat_settings_advance_section',
    [
      'label_for' => 'se_chat_remember_state',
      'description' => 'Tells the chat widget to keep track of its open state.',
    ]
  );
}

/**
 * CALLBACK FUNCTIONS FOR SETTINGS PAGE
 */
function frictionless_text_input_callback($args)
{
  $id = esc_attr($args['label_for']);
  // determine which setting this input is for (booking or chat)
  $type = explode('_', $id)[1];
  $value = esc_attr(get_option('frictionless_' . $type . '_settings')[$id]);
  ?>
    <input type="text" class="regular-text" name="frictionless_<?php esc_html_e($type) ?>_settings[<?php esc_html_e($id); ?>]" value="<?php esc_html_e($value); ?>">
    <p class="description"><?php esc_html_e($args['description']); ?></p>
  <?php
}

function frictionless_number_input_callback($args)
{
  $id = esc_attr($args['label_for']);
  $type = explode('_', $id)[1];
  $value = esc_attr(get_option('frictionless_' . $type . '_settings')[$id]);
  ?>
    <input type="number" name="frictionless_<?php esc_html_e($type); ?>_settings[<?php esc_html_e($id); ?>]" value="<?php esc_html_e($value); ?>"> <?php esc_html_e($args['trailing_text']); ?>
    <p class="description"><?php esc_html_e($args['description']); ?></p>
  <?php
}

function frictionless_color_input_callback($args)
{
  $id = esc_attr($args['label_for']);
  $type = explode('_', $id)[1];
  $value = esc_attr(get_option('frictionless_' . $type . '_settings')[$id]);
  ?>
    <input type="color" name="frictionless_<?php esc_html_e($type); ?>_settings[<?php esc_html_e($id); ?>]" value="<?php esc_html_e($value); ?>">
    <p class="description"><?php esc_html_e($args['description']); ?></p>
  <?php
}

function frictionless_checkbox_input_callback($args)
{
  $id = esc_attr($args['label_for']);
  $type = explode('_', $id)[1];
  $value = esc_attr(get_option('frictionless_' . $type . '_settings')[$id]);
  ?>
    <input type="checkbox" value="true" name="frictionless_<?php esc_html_e($type); ?>_settings[<?php esc_html_e($id); ?>]" <?php if (
  $value === 'on' ||
  $value === 'true'
) {
  echo 'checked="checked"';
} ?>>
    <p class="description"><?php esc_html_e($args['description']); ?></p>
  <?php
}

function frictionless_select_input_callback($args)
{
  $id = esc_attr($args['label_for']);
  $type = explode('_', $id)[1];
  $value = esc_attr(get_option('frictionless_' . $type . '_settings')[$id]);
  $options = $args['options'];
  ?>
    <select type="text" name="frictionless_<?php echo esc_html($type); ?>_settings[<?php echo esc_html($id); ?>]">
      <?php foreach ($options as $option) {
        echo '<option value="';
        echo esc_attr($option);
        echo '"';
        if ($option == $value) {
          echo 'selected';
        }
        echo '>';
        echo esc_html($option);
        echo '</option>';
      } ?>
    </select>
    <p class="description"><?php esc_html_e($args['description']); ?></p>
  <?php
}

/**
 * Helper function for creating sections. Set $is_expandable=true to allow the section
 * to function like an accordion.
 *
 * @param string $section_name e.g. 'general', 'required', 'color'
 * @param string $section_type e.g. 'chat', 'booking'
 * @param string $section_title e.g. 'General Options' would add <h2>General Options</h2> above the accordion fold
 * @param boolean $is_expandable If this section should function like an accordion (expand/collapse)
 */
function create_frictionless_settings_section($section_name, $section_type, $section_title, $is_expandable)
{
  $header_id = 'frictionless-' . $section_type . '-' . $section_name . '-toggle';
  $div_class = 'frictionless-' . $section_type . '-' . $section_name . '-toggle';
  if ($is_expandable) {
    $div_class .= ' hidable';
  }

  $api_name = 'frictionless-' . $section_type . '-' . $section_name; // e.g. do_settings_sections('frictionless-chat-general');

  echo "<h2 id='" . esc_html($header_id) . "' class='accordionTitle'>" . esc_html($section_title) . '</h2>';
  echo "<div class='" . esc_html($div_class) . "'>";
  do_settings_sections($api_name);
  echo '</div>';
}

function frictionless_section_callback() 
{
  echo ""; 
}

/**
 * Top level menu callback function
 */
function frictionless_settings_html()
{
  // check user capabilities
  if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
  }

  // check if the user have submitted the settings
  // WordPress will add the "settings-updated" $_GET parameter to the url
  if (isset($_GET['settings-updated'])) {
    // add settings saved message with the class of "updated"
    add_settings_error('frictionless_messages', 'frictionless_message', 'Settings Saved', 'updated');
  }

  // default to booking settings
  $active_tab = 'booking_settings';

  if (isset($_GET['tab'])) {
    $active_tab = sanitize_text_field($_GET['tab']);
  }
  ?>
  <div class="wrap">
      <h1><?php esc_html_e(get_admin_page_title()); ?></h1>
      <p>Setup Schedule Engine widgets.</p>
      <span class="description">You can find your company's API keys in
        <a href="https://provider.scheduleengine.net/" target="_blank">Schedule Engine provider dashboard</a>. Please reach out to your implentation specialist to setup your provider dashboard.
      </span>
      <h2 class="nav-tab-wrapper">
          <a href="?page=<?php echo esc_html(sanitize_text_field($_GET['page'])); ?>&tab=booking_settings" class="nav-tab <?php echo $active_tab ==
'booking_settings'
  ? 'nav-tab-active'
  : ''; ?>">Booking Settings</a>
          <a href="?page=<?php echo esc_html(sanitize_text_field($_GET['page'])); ?>&tab=chat_settings" class="nav-tab <?php echo $active_tab ==
'chat_settings'
  ? 'nav-tab-active'
  : ''; ?>">Chat Settings</a>
      </h2>
      <form action="options.php" method="post">
          <?php
          if ($active_tab == 'booking_settings') {
            settings_fields('frictionless_booking_settings');
            do_settings_sections('frictionless-booking');
          } elseif ($active_tab == 'chat_settings') {
            // Initialize hidden fields for submission
            settings_fields('frictionless_chat_settings');

            // Required settings section
            create_frictionless_settings_section('required', 'chat', 'Required Settings', false); // is_expandable=false because we always want this showing

            // General settings section
            create_frictionless_settings_section('general', 'chat', 'General Settings', true);

            // Advanced settings section
            create_frictionless_settings_section('advanced', 'chat', 'Advanced Settings', true);

            // Color settings section
            create_frictionless_settings_section('color', 'chat', 'Color Settings', true);
          }
          submit_button();?>
      </form>
  </div>
  <?php
}
