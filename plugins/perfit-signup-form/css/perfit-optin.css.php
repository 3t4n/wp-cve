<?php
header("Content-type: text/css; charset: UTF-8");

$button_bg = '#'.$_GET['button-bg'];
$button_text = '#'.$_GET['button-text'];
$form_bg = '#'.$_GET['form-bg'];
$form_text = '#'.$_GET['form-text'];

?>

form.p-optin {
    font-size: 14px;
}

form.p-optin {
    margin-top: 50px;
}

div.p-body {
    background-color: <?= $form_bg ?>;
}

.p-optin div.p-header,
.p-optin .p-body button,
button.p-open {
    background: <?= $button_bg ?>;
    color: <?= $button_text ?>;
}

.p-optin .p-body button:hover,
button.p-open:hover{
    background:  <?= $button_bg ?>;
    opacity: 0.8;
}

.p-optin div.p-body p,
.p-optin div.p-field label span,
.p-optin .p-body .p-success {
    color: <?= $form_text ?>;
}
