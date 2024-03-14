<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class MJTC_message {
    /*
     * Set Message
     * @params $message = Your message to display
     * @params $type = Messages types => 'updated','error','update-nag'
     */
    public static $ms_response_msg = array();

    static function MJTC_setMessage($message, $type) {
        MJTC_includer::MJTC_getObjectClass('wphdnotification')->MJTC_addSessionNotificationDataToTable($message,$type,'notification');
    }

    static function MJTC_getMessage() {
        $frontend = (is_admin()) ? '' : 'frontend';
        $divHtml = '';
        $option = get_option('majesticsupport', array());
        $notificationdata = MJTC_includer::MJTC_getObjectClass('wphdnotification')->MJTC_getNotificationDatabySessionId('notification',true);
        if (isset($notificationdata) && !empty($notificationdata)) {
            $data = $notificationdata;
            for ($i = 0; $i < COUNT($data['msg']); $i++){
                $divHtml .= '<div class=" ' . esc_attr($frontend) . ' ' . esc_attr($data['type'][$i]) . '"><p>' . wp_kses($data['msg'][$i], MJTC_ALLOWED_TAGS) . '</p></div>';
            }
        }
        echo wp_kses($divHtml, MJTC_ALLOWED_TAGS);
    }

}

?>
