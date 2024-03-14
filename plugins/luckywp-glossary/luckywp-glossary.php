<?php
/*
Plugin Name: LuckyWP Glossary
Plugin URI: https://theluckywp.com/product/glossary/
Description: The plugin implements the glossary/dictionary functionality with support of synonyms.
Version: 1.0.9
Author: LuckyWP
Author URI: https://theluckywp.com/
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: luckywp-glossary
Domain Path: /languages

LuckyWP Glossary is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

LuckyWP Glossary is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with LuckyWP Glossary. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

require 'lwpglsAutoloader.php';
$lwpglsAutoloader = new lwpglsAutoloader();
$lwpglsAutoloader->register();
$lwpglsAutoloader->addNamespace('luckywp\glossary', __DIR__);

$config = require(__DIR__ . '/config/plugin.php');
(new \luckywp\glossary\plugin\Plugin($config))->run('1.0.9', __FILE__, 'lwpgls_');
