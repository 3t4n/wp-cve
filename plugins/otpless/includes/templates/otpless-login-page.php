<?php
$file_path = dirname(__DIR__) . '/class-login.php';
if (file_exists($file_path)) {
    include_once $file_path;
}
$otpless_login_instance = new Otpless_Login();
$cId_value = $otpless_login_instance->cId;
$appId_value = $otpless_login_instance->appId;
?>
<div id="otpless-login-page"></div>
<script src="https://otpless.com/v2/wordpress.js.gz" id="otpless-sdk" cid="<?php echo $cId_value ?>"
    data-appid="<?php echo $appId_value ?>"></script>
<!-- From wp-login.php line 285-305 -->