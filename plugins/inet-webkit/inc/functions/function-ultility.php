<?php

/**
 * @param $text
 * @return string
 */
function inet_wk_format_text($text)
{
    $expr = '/(?<=\s|^)[a-z]/i';
    preg_match_all($expr, $text, $matches);
    $result = implode('', $matches[0]);
    return strtoupper($result);
}

/**
 * @param $date
 * @return string
 */
function inet_wk_format_date($date)
{
    $format_date = strtotime($date);
    $date_exp = explode(" ", $format_date);
    $newDate = date("d-m-Y", $date_exp[0]);
    return $newDate;
}

add_action('wp_ajax_inet_wk_send_mail', 'inet_wk_send_mail');
add_action('wp_ajax_nopriv_inet_wk_send_mail', 'inet_wk_send_mail');

/**
 * @return void
 */
function inet_wk_send_mail()
{
    if (sanitize_text_field($_POST['email'])) {
        $to = sanitize_text_field($_POST['email']);
        $subject = 'iNET Webkit - Cấu hình SMTP thành công';
        $headers = array('Content-Type: text/html; charset=UTF-8');

        ob_start();

        echo 'Xin chúc mừng bạn đã cấu hình máy chủ SMTP thành công.' . PHP_EOL;
        echo 'iNET Webkit Team.' . PHP_EOL;

        $message = ob_get_contents();

        ob_end_clean();

        $mail = wp_mail($to, $subject, $message, $headers);

        if ($mail) {
            echo 'success';
        }
    }
    die();
}