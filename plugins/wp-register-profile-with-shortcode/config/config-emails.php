<?php

global $wprw_mail_to_user_subject, $wprw_mail_to_user_body;

// Registration mail to user //
$wprw_mail_to_user_subject = 'Registration Successful';

$wprw_mail_to_user_body = 'We are pleased to confirm your registration for #site_name#. Below is your login credential.
<br><br>
<strong>Username</strong> : #user_name#
<br>
<strong>Password</strong> : #user_password#
<br>
<strong>Site Link</strong> : #site_url#
<br><br>
Thank You';
// Registration mail to user //


// Registration mail to admin //
// This mail will be sent to admin when new user make registration in the site. //

// mail subject 
// Please update mail subject if you want //
$wprw_mail_to_admin_subject = 'New User Registration';
// mail subject 

// mail body //
// Please update mail body if you want //
$wprw_mail_to_admin_body = 'A new user with Username #user_name# has registered on #site_name#
<br><br>
<h3>New User Information</h3>
<br>
#new_user_data#
<br><br>
Thank You
';
// mail body //

// Registration mail to admin //