<?php
/*
Plugin Name: RSS Manager
Plugin URI:
Description: This plugin gives you the ability to change the look of your RSS feed. You can easily add images from custom fields, read more link, category list, tag list and any custom code before or after the feed post.
Author: ajayver, sputnik1818
Author URI: http://travelbloggers.ru/
Version: 0.06
*/ 
/*  Copyright 2011  Ajay Verma, Dmitry Vojtekhovich  (email : ajayverma1986@gmail.com)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function rss_manager_options(){

$rss_manager_options = get_option('rss_manager');
if (empty($rss_manager_options)) {
  $rss_manager_options = array('custom_field_name' => 'thumbnail',
'rss_pause_time' => '0',
'rss_unit_of_time' => __('Minute(s)', 'rss-manager'),
'readmore_text' => __('Read more...', 'rss-manager'),
'category_text' => __('Categories:', 'rss-manager'),
'tag_text' => __('Tags:', 'rss-manager'),
'thumbnail_width' => '',
'thumbnail_height' => '',
'categories_separator' => ', ',
'tags_separator' => ', ',
'cats_tags_position' => 'before-text',
'readmore_align' => 'right',
'content_text_align' => 'left',
'custom_header_code' => '',
'custom_footer_code' => '',
'thumbnail_position' => 'none'); 
update_option('rss_manager', $rss_manager_options);
} 

return $rss_manager_options; 

			}
          
function rss_manager_filter($content) {      // Функция перехватывает содержимое поста и запоминает его в переменную $content

	if(is_feed()) {   //Если выводится RSS
		
    $options = rss_manager_options();
		
		global $wp_query;  //В этой переменной хранятся все данные обрабатываемого поста
		$post_id = $wp_query->post->ID; //Находим ID поста
		$post_title = $wp_query->post->post_title; //Название поста
		
		$post_url = get_permalink($post_id);    //Ссылку на пост
		
		/* Added by Cristiano Leoni 2012-02-12 */		
		if ( function_exists('has_post_thumbnail') && has_post_thumbnail($post_id) ) {
			$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), full );
			if ($thumbnail[0]) $post_thumbnail=$thumbnail[0];
		}
		/* End Added */
		
		if ( get_post_meta($post_id, $options['custom_field_name'], true) ) $post_thumbnail = get_post_meta($post_id, $options['custom_field_name'], true); //Берем ссылку на картинку из произвольного поля с названием thumbnail
		
			
    
    if($post_thumbnail != '') {   //Если адрес картинки найден
		    if ($options['thumbnail_height'] > 0) { $width_height .= "height='" . $options['thumbnail_height'] . "px' "; } // если надо ресайзить по длине
		    if ($options['thumbnail_width'] > 0) { $width_height .= "width='". $options['thumbnail_width'] ."px' "; } // если надо ресайзить по ширине
				$post_thumbnail = "<a href='$post_url' title='$post_title'><img src='$post_thumbnail' border='0'  ".$width_height." /></a>"; //Добавляем картинку со сылкой        
			}
			
		if ($options['readmore_text'] != '') {
			$readmore = "<table width='100%'><tr><td align=" . $options['readmore_align'] . "><p><b>(<a href='$post_url' title='$post_title'>" . $options['readmore_text'] . "</a>)</b></p></td></tr></table>";  // Добавляем ссылку "Читать дальше" после содержимого.
         }
    //вывод категорий, если нужно
		if ($options['category_text'] != "") { 
			$categories_list = get_the_category_list($options['categories_separator'],'',$post_id);
			$cats_tags .= '<p>';
			$cats_tags .= $options['category_text'] . ' ';
			$cats_tags .= $categories_list;
			$cats_tags .= '</p>';
			}
		//вывод тегов, если нужно	
		if ($options['tag_text'] != "") { 
			$tags_list = get_the_term_list($post_id,'post_tag',$options['tag_text'] . ' ',$options['tags_separator'],'');
			$cats_tags .= '<p>';
			$cats_tags .= $tags_list;
			$cats_tags .= '</p>';
		}
		
	if ($cats_tags) {
		
		switch ($options['cats_tags_position']) {
			case 'after-title':
				$updated_content .= $cats_tags;
				break;
			case 'before-text':
				$content = $cats_tags . $content;
				break;
			case 'after-text':
				$content = $content . $cats_tags;
				break;
			case 'bottom':
				
				break;
        
		} 
	} 
    if ($readmore) $content = $content . $readmore;
    
    $updated_content .= "<table cellpadding='10'><tr>";
    if ($options['custom_header_code'] != "") {
    $updated_content .= '<td>';    
    $updated_content .= $options['custom_header_code'];
    $updated_content .= '</td></tr><tr>';}
		switch ($options['thumbnail_position']) {
			case 'top-left':
				$updated_content .= "<td valign='top'>";
				$updated_content .= $post_thumbnail;
				$updated_content .= "</td></tr><tr><td  valign='top' align='" . $options['content_text_align'] . "'>";
				$updated_content .=  $content;
				break;
			case 'top-center':
				$updated_content .= "<td valign='top' align='center'>";
				$updated_content .= $post_thumbnail;
				$updated_content .= "</td></tr><tr><td valign='top' align='" . $options['content_text_align'] . "'>";
				$updated_content .=  $content;       
				break;
			case 'top-right':
				$updated_content .= "<td valign='top' align='right'>";
				$updated_content .= $post_thumbnail;
				$updated_content .= "</td></tr><tr><td valign='top' align='" . $options['content_text_align'] . "'>";
				$updated_content .=  $content;
				break;
			case 'left':
				$updated_content .= "<td valign='top'>";
				$updated_content .= $post_thumbnail;
				$updated_content .= "</td><td valign='top' align='" . $options['content_text_align'] . "'>";
				$updated_content .=  $content;      
				break;
				case 'right':
				$updated_content .= "<td valign='top' align='" . $options['content_text_align'] . "'>";
				$updated_content .=  $content;
				$updated_content .= "</td><td valign='top'>";
				$updated_content .= $post_thumbnail;
				break;
				case 'none':
				$updated_content .= "<td valign='top' align='" . $options['content_text_align'] . "'>";        
				$updated_content .=  $content;
				break;     
		}   
	$updated_content .= "</td></tr>";
	if ($options['cats_tags_position'] == 'bottom') {
		$updated_content .= '<tr><td>';
		$updated_content .= $cats_tags;
		$updated_content .= '</td></tr>';
	}        
	if ($options['custom_footer_code'] != "") {
		$updated_content .= '<tr><td>';    
		$updated_content .= $options['custom_footer_code'];
		$updated_content .= '</td></tr>';
	}
	$updated_content .= "</table>";         
				
	
	}
	else {     //Если это вообще не RSS:
		$updated_content = $content; //Просто копируем содержимое
		}
	return $updated_content; //Возвращаем обработанное содержимое

}

function publish_later_on_feed($where) {  
	global $wpdb;  
	$options = rss_manager_options();
	if ( is_feed() && $options['rss_pause_time']>0 && $options['rss_unit_of_time']!='') {  
		$now = gmdate('Y-m-d H:i:s');   
		$rss_pause_time = $options['rss_pause_time'];
		$rss_unit_of_time = $options['rss_unit_of_time'];
		$where .= " AND TIMESTAMPDIFF($rss_unit_of_time, $wpdb->posts.post_date_gmt, '$now') > $rss_pause_time ";  
	}  
return $where;  
}  
  
add_filter('posts_where', 'publish_later_on_feed'); 


function rss_manager_admin() {
	add_options_page(__('RSS Manager options', 'rss-manager'), __('RSS Manager', 'rss-manager'), 'manage_options', 'rss_manager', 'rss_manager_options_page');
}

function rss_manager_options_page() {
include('rss_manager_admin.php');

}
if (function_exists('load_plugin_textdomain'))
	{
		load_plugin_textdomain( 'rss-manager', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
	
add_filter('posts_where', 'publish_later_on_feed'); //Делаем задержку времени публикация поста в ленте...
add_action('admin_menu', 'rss_manager_admin');
add_filter('the_excerpt_rss', 'rss_manager_filter'); //Добавляем функцию к выводу сокращенного RSS (анонсов)

?>