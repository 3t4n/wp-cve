<?php
/*
Plugin Name: LuckyWP Term Description Rich Text
Plugin URI: https://theluckywp.com/product/term-description-rich-text/
Description: Replaces plain-text editor for category, tag and custom taxonomy term description with the built-in WordPress WYSIWYG editor (TinyMCE).
Version: 1.0.1
Author: LuckyWP
Author URI: https://theluckywp.com/
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: lwptdr
Domain Path: /languages

LuckyWP Term Description Rich Text is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

LuckyWP Term Description Rich Text is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with LuckyWP Term Description Rich Text. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

require 'lwptdrAutoloader.php';
$lwptreAutoloader = new lwptdrAutoloader();
$lwptreAutoloader->register();
$lwptreAutoloader->addNamespace('luckywp\termDescriptionRichText', __DIR__);

$config = require(__DIR__ . '/config/plugin.php');
(new \luckywp\termDescriptionRichText\plugin\Plugin($config))->run('1.0.1', __FILE__, 'lwptdr_');
