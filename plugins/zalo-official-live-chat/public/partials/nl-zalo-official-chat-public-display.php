<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/public/partials
 */
?>

<?php
    $default = array(
        'zalo_oa_id' => '', 
        'zalo_hello_message' => 'Rất vui khi được hỗ trợ bạn!',
        'zalo_popup_time' => 0,
        'zalo_data_height' => 420,
        'zalo_data_width' => 350,
    );
    $settings = get_option('zalo-oa-chat', $default);
    $ao_id = $settings['zalo_oa_id'];
    $hello = $settings['zalo_hello_message'];
    $popup = $settings['zalo_popup_time'];
    $width = $settings['zalo_data_width'];
    $height = $settings['zalo_data_height'];
    
    if($ao_id){
        ?>
        <div class="zalo-chat-widget" data-oaid="<?php echo esc_html($ao_id); ?>" data-welcome-message="<?php echo esc_html($hello); ?>" data-autopopup="<?php echo esc_html($popup); ?>" data-width="<?php echo esc_html($width); ?>" data-height="<?php echo esc_html($height); ?>"></div>
        <?php
    }
?>
