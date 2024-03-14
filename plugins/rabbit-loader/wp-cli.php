<?php

defined( 'ABSPATH' ) or die( 'No jokes please!' );

/**
* Activate RabbitLoader cloud services
*
* ## OPTIONS
*
* <ApiKey>
* : Your API key, can be obtained from profile page https://rabbitloader.com/account/#apikeys
*
*
* ## EXAMPLE
*
* wp rabbitloader connect ApiKey
*/

function cli_rabbitloader_connect($args, $assoc_args) {
    $apikey = !empty($args[0]) ? $args[0] : "";
    $urlparts = parse_url(home_url());
}

WP_CLI::add_command("rabbitloader connect", "cli_rabbitloader_connect");
?>