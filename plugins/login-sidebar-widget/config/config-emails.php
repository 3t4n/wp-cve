<?php

global $forgot_password_link_mail_subject, $forgot_password_link_mail_body, $new_password_mail_subject, $new_password_mail_body;

$forgot_password_link_mail_subject = "Password Reset Request";
$forgot_password_link_mail_body = "Someone requested that the password be reset for the following account:
<br>
#site_url#
<br>
Username: #user_name#
<br>
If this was a mistake, just ignore this email and nothing will happen.
<br>
To reset your password, visit the following address:
<br>
#resetlink#";

$new_password_mail_subject = "Password Reset Request";
$new_password_mail_body = "Your new password for the account at:
<br>
#site_url#
<br>
Username: #user_name#
<br>
Password: #user_password#
<br>
You can now login with your new password at:
#site_url#";

