<?php


$notification = @file_get_contents(autoindex_upload . '/notification.json');
$notification = json_decode($notification);


if ($notification->request_notify) {
    add_action('admin_notices', 'autoin_admin_notice__error_notification');

}


function autoin_admin_notice__error_notification()
{
    $get = @file_get_contents(autoindex_upload. '/notification.json');
    $data = json_decode($get);
    $class = 'notice notice-error';
    if ($data->valid == 0) {
        $message = __(esc_attr($data->request_notify), 'sample-text-domain') . "  <a href='" . admin_url('admin.php?page=lisense') . "' >Click Here</a>";

        printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), $message);

    }


}


?>