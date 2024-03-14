<?php

// Block direct access
defined("ABSPATH") or die;

$_conf = [
    "api_endpoint" => "https://api.nextsale.io",
    "oauth_endpoint" => "https://app.nextsale.io/oauth",
    'platform_check_interval' => 3600
];

return json_decode(json_encode($_conf));
