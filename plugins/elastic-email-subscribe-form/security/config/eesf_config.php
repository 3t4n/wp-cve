<?php
$option = get_option('ee_security_options');

define('EESF_REQUEST', 'https://www.google.com/recaptcha/api/siteverify');
define('EESF_SECRET_KEY', $option['ee_secret_key']);
define('EESF_PUBLIC_ACID', get_option('ee_publicaccountid'));
define('EESF_REQUEST_CONTACT_ADD', 'https://api.elasticemail.com/v2/contact/add?');