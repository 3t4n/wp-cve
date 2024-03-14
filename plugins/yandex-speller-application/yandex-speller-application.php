<?php
/*
Plugin Name: Yandex Speller Application
Plugin URI: http://wordpress.org/plugins/yandex-speller-application/
Description: Модифицирует в визуальном редакторе TinyMCE стандартную проверку правописания на проверку правописания используя сервис <a href="http://api.yandex.ru/speller/doc/dg/concepts/speller-overview.xml">Яндекс.Спеллер</a>, что очень подходит для проверки текстов на русском языке.
Version: 1.0.2
Author: Dmitry Ponomarev
Author URI: http://earthperson.info
*/

/*  Copyright 2009  Dmitry Ponomarev (email : ponomarev.dev@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function ysa_mce_spellchecker_languages() {
	return '+Russian=ru,English=en,Ukrainian=uk';
}

function ysa_mce_before_init($initArray) {
	$url = WP_PLUGIN_URL.'/'.str_replace(basename(__FILE__), 'rpc.php', plugin_basename(__FILE__));
	$initArray['spellchecker_rpc_url'] = parse_url($url, PHP_URL_PATH);
	$initArray['spellchecker_word_separator_chars'] = '\\s!\\"#$%&()*+,./:;<=>?@[\]^_{|}\xa7 \xa9\xab\xae\xb1\xb6\xb7\xb8\xbb\xbc\xbd\xbe\u00bf\xd7\xf7\xa4\u201d\u201c';
	return $initArray;
}

add_filter('mce_spellchecker_languages', 'ysa_mce_spellchecker_languages');
add_filter('tiny_mce_before_init', 'ysa_mce_before_init');
?>