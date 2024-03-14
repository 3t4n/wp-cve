<?php

defined('ABSPATH') or exit;

$options = get_option('yandex_smtp_settings');
if (empty($options['from']) || !is_email($options['login']) || empty($options['password'])) {
    return;
}

$phpmailer->Mailer = "smtp";
$phpmailer->From = $options['login'];
$phpmailer->FromName = $options['from'];
$phpmailer->Sender = $phpmailer->From;
$phpmailer->AddReplyTo($phpmailer->From, $phpmailer->FromName);
$phpmailer->Host = 'smtp.yandex.com';
$phpmailer->SMTPSecure = 'ssl';
$phpmailer->Port = '465';
$phpmailer->SMTPAuth = true;
$phpmailer->Username = $options['login'];
$phpmailer->Password = $options["password"];
if ($options['copy'] == '1') {
    $phpmailer->AddAddress($phpmailer->From, $phpmailer->FromName);
} else {

}
