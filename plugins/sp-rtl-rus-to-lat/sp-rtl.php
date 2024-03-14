<?php
/*
Plugin Name: SP RTL (RusToLat)
Text Domain: sp_rtl
Description: This plugin converts Cyrillic characters in post, page slugs to Latin characters. <a href="https://money.yandex.ru/to/410013249628330">Donate this plugin</a>
Version: 1.0.5
Author: spoot1986
Author URI: https://cms3.ru/
Plugin URI: https://cms3.ru/kirillicu-v-latinicu-wordpress/
*/

$sp_rtl_array = array(
   "Є"=>"YE","І"=>"I","Ѓ"=>"G","і"=>"i","№"=>"#","є"=>"ye","ѓ"=>"g",
   "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D",
   "Е"=>"E","Ё"=>"YO","Ж"=>"ZH",
   "З"=>"Z","И"=>"I","Й"=>"J","К"=>"K","Л"=>"L",
   "М"=>"M","Н"=>"N","О"=>"O","П"=>"P","Р"=>"R",
   "С"=>"S","Т"=>"T","У"=>"U","Ф"=>"F","Х"=>"X",
   "Ц"=>"C","Ч"=>"CH","Ш"=>"SH","Щ"=>"SHH","Ъ"=>"'",
   "Ы"=>"Y","Ь"=>"","Э"=>"E","Ю"=>"YU","Я"=>"YA",
   "а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d",
   "е"=>"e","ё"=>"yo","ж"=>"zh",
   "з"=>"z","и"=>"i","й"=>"j","к"=>"k","л"=>"l",
   "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
   "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"x",
   "ц"=>"c","ч"=>"ch","ш"=>"sh","щ"=>"shh","ъ"=>"",
   "ы"=>"y","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya",
   "—"=>"-","«"=>"","»"=>"","…"=>""
  );

function sp_rtl_sanitize_title_with_translit($title) {
	global $sp_rtl_array;
	return strtr($title, $sp_rtl_array);
}
add_action('sanitize_title', 'sp_rtl_sanitize_title_with_translit', 0);
?>