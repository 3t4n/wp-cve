<?php
/* 
Plugin Name: Customfields Shortcode
Version: 1.0
Description: Позволяет назначать любые произвольные поля, используя шорткоды вида [custom name="имя" value="значение"] или &lt;!--custom name="имя" value="значение"-->
Plugin URI: http://iskariot.ru/wordpress/remix/#custom-short
Author: Sergey M.
Author URI: http://iskariot.ru/
*/ 
/*
Спасибо awtor (http://awtor.ru) за замечания по поводу соместимости со старыми версиями
*/

//Пытаемся поправить шоткоды в виде комментариев
add_filter('content_save_pre', 'cfsc_right_shortcodes');
function cfsc_right_shortcodes($content) {
	$content=preg_replace('~(\<|&lt;)!--custom\s(.*?)--(>|&gt;)~i','<!--custom \\2-->',$content);
	return $content;
}

//Находим все псевдотеги, вставляем произвольные поля, если надо
add_action('save_post', 'cfsc_add_customfield');
function cfsc_add_customfield($post_ID) {
	//подбираем потс
	$post = get_post($post_ID);

	//подбираем все псевдотеги
	preg_match_all('~\[custom\s([^\]]*?)\]~i',$post->post_content,$matches);
	preg_match_all('~(\<|&lt;)!--custom(.*?)--(>|&gt;)~i',$post->post_content,$matches2);
	//соединяем обав варианта
	$matches[1]=array_merge($matches[1],$matches2[2]);
	
	$n = count( $matches[1] );
	for($i=0;$i<$n;$i++){
		//вытаскиваем из них атрибуты
		preg_match_all('~name\s*=\s*"([^"]*?)"~',$matches[1][$i],$reg);
			//это если кавычки одиночные
		if(empty($reg[1][0])) {
			preg_match_all("~name\s*=\s*'([^']*?)'~",$matches[1][$i],$reg);
			}
		$name=$reg[1][0];
		preg_match_all('~value\s*=\s*"([^"]*?)"~',$matches[1][$i],$reg);
			//это если кавычки одиночные
		if(empty($reg[1][0])) {
			preg_match_all("~value\s*=\s*'([^']*?)'~",$matches[1][$i],$reg);
			}
		$value=$reg[1][0];
		
		//если есть такое имя
		if(!empty($name)) {
			if(empty($value)) {
				//удаляем из БД если значение пустое
				delete_post_meta( $post_ID, $name);
				}
				else{
				//вставляем в БД если значение не пустое
				//для совместимости с 2.3
				add_post_meta ($post_ID,$name,$value,true) 
					or update_post_meta( $post_ID, $name, $value );
				}
			//кеш обновит он сам
			}
		}//for
}

//Убираем все вхождения наших шоткодов и условных комментариев
add_filter('the_content', 'cfsc_remove_shortcode',6);
function cfsc_remove_shortcode($content) {
	$content=preg_replace('~(\<|&lt;)!--custom\s(.*?)--(>|&gt;)~i','',$content);
	$content=preg_replace('~\[custom\s(.*?)\]~i','',$content);
	return $content;
}


?>