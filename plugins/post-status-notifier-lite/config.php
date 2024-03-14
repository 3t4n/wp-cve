<?php
$config = array(
    'plugin' => array(
        'uniqueId' => 'Ifw-Plugin-Psn',
        'Ifw-Plugin-Psn' => 1,
        'premiumUrl' => 'https://codecanyon.net/item/post-status-notifier/4809420?ref=ifeelweb',
        'docUrl' => 'https://docs.ifeelweb.de/post-status-notifier/',
        'faqUrl' => 'https://ifeelwebde.zendesk.com/hc/en-us/sections/202820483-FAQ',
        'changelogUrl' => 'https://codecanyon.net/item/post-status-notifier/4809420#item-description__changelog',
        'supportUrl' => 'https://www.ifeelweb.de/support/',
        'wpMinVersion' => '3.3',
        'selftestInterval' => 3600,
        'autoupdate' => 1,
        'updateApi' => 'envato',
        'updateServer' => 'https://update.ifeelweb.de/',
        'updateTest' => 0,
        'optionsPage' => 'options-general.php?page=post-status-notifier&controller=options&appaction=index',
        'licensePage' => 'options-general.php?page=post-status-notifier&mod=premium&controller=license&appaction=index',
        'licensePageNetwork' => 'options-general.php?page=post-status-notifier&controller=options&appaction=index'
    ),
    'orm' => array(
        'init' => 1,
        'use_pdo' => 0
    ),
    'log' => array(
        'file' => ''
    ),
    'debug' => array(
        'env' => 0,
        'pathinfo' => 0,
        'update' => 0,
        'show_errors' => 0
    ),
    'application' => array(
        'controller' => array(
            'key' => 'controller'
        ),
        'action' => array(
            'key' => 'appaction'
        )
    )
);

if (isset($env) && $env == 'development') {

    $config['plugin']['updateServer'] = 'https://update.ifeelweb.de/';
    $config['plugin']['updateTest'] = 1;
    $config['plugin']['simulateLiteVersion'] = 0;

    $config['debug']['update'] = 1;
    $config['debug']['show_errors'] = 1;
}

return $config;