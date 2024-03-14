<?php
if (!function_exists('nktgnfw_send_test_message')) {
    add_action('wp_ajax_nktgnfw_send_test_message', 'nktgnfw_send_test_message');
    add_action('wp_ajax_nopriv_nktgnfw_send_test_message', 'nktgnfw_send_test_message');
    function nktgnfw_send_test_message()
    {
        try{
            $telegram = new \NineKolor\TelegramWC\Classes\Sender();
            $telegram->chatID = get_option('nktgnfw_setting_chatid');
            $telegram->token = get_option('nktgnfw_setting_token');
            $template = get_option('nktgnfw_setting_template');
            $telegram->sendMessage($template);
            echo json_encode(['error' => 0,'message' => __('Message was sent!')]);wp_die();
        }
        catch (\Exception $ex){
            echo json_encode(['error' => 1,'message' => $ex->getMessage()]);wp_die();
        }
    }
}