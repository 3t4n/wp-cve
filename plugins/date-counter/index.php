<?php

/*
Plugin Name: Date counter
Plugin URI: https://github.com/KonstantinPankratov/WordPress-Plugin-Date-counter
Description: Date counter is a WordPress plugin that allows you to add a shortcode to count difference between two dates using start and end dates or display the current date easily.
Version: 2.0.3
Author: Konstantin Pankratov
Author URI: http://kopa.pw/
*/

require_once __DIR__ . '/Classes/AbstractDatetime.php';
require_once __DIR__ . '/Classes/CurrentDatetime.php';
require_once __DIR__ . '/Classes/DatetimeDifference.php';
require_once __DIR__ . '/Classes/TotalDatetimeDifference.php';
require_once __DIR__ . '/Classes/DateCounter.php';