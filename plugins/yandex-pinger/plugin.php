<?php
/*
Plugin Name: Яндекс.ПДС Пингер / Yandex Site search pinger
Plugin URI: http://site.yandex.ru/cms-plugins/
Description: Плагин оповещает сервис Яндекс.Поиск для сайта о новых и измененных документах
Version: 1.5
Author: ООО "ЯНДЕКС"
Author URI: http://www.yandex.ru/
License: GPL2
*/

/*  Copyright 2012 Yandex LLC

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

  (Это свободная программа: вы можете перераспространять ее и/или изменять
   ее на условиях Стандартной общественной лицензии GNU в том виде, в каком
   она была опубликована Фондом свободного программного обеспечения; либо
   версии 2 лицензии, либо (по вашему выбору) любой более поздней версии.

   Эта программа распространяется в надежде, что она будет полезной,
   но БЕЗО ВСЯКИХ ГАРАНТИЙ; даже без неявной гарантии ТОВАРНОГО ВИДА
   или ПРИГОДНОСТИ ДЛЯ ОПРЕДЕЛЕННЫХ ЦЕЛЕЙ. Подробнее см. в Стандартной
   общественной лицензии GNU.

   Вы должны были получить копию Стандартной общественной лицензии GNU
   вместе с этой программой. Если это не так, см.
   <http://www.gnu.org/licenses/>.)
   
*/

load_plugin_textdomain('yandex-pinger', false, basename( dirname( __FILE__ ) ) . '/languages' );

class plgContentPinger{
	
	static private $key = '';
	static private $login ='';
	static private $searchId = 0;
	static private $message = ''; 
	static private $pluginId = 0;
	
	static private $_prefix = 'yandex_pinger';

	static private $_cache = array();

	static function init_settings()
	{
		$settings = plugin_dir_path(__FILE__).'settings.ini';
		try{
			if(!is_file($settings)) throw new Exception("file does not exist");
			
			if(!$file = parse_ini_file($settings)) throw new Exception("parse error");
			
			if( key_exists('key', $file) 
				&& key_exists('login', $file)
				&& key_exists('searchId', $file)
				&& key_exists('message', $file)
				&& key_exists('pluginId', $file)
				) {
	
				self::$key = $file['key'];
				self::$login = $file['login'];
				self::$searchId = $file['searchId'];				
				self::$message = $file['message'];
				self::$pluginId = $file['pluginId'];
				
				return true;
			} else {
				throw new Exception("incorrect ini");
			}
					
		} catch(exception $e){
			return false;
		}
			
	}
	
	static function public_entity( $entity )
	{
		$post = get_post($entity);
		try{
		
			if (in_array($post->post_status, array('publish','future'))) {
				$timestamp = self::get_date($post->post_date);
			} else {
				throw new Exception("wrong event");
			}
			$url = self::get_link( $entity );
			
			$key = md5($url);
			if (empty(self::$_cache[$key])) {
				self::ping($url, $timestamp);
				self::$_cache[$key] = true;
			}
			
		} catch(exception  $e) {
			return null;
		}
	}
	static function get_link( $entity )
	{
		if(is_object($entity) == true) {
			return $entity -> guid;
		} elseif(is_scalar($entity)) {
			return get_permalink($entity);
		} else {
			throw new Exception("Wrong event");
		}
	}
	
	static function get_date($date)
	{
		$timestamp = 0;
		$timestamp = strtotime($date);
		$delta = $timestamp - time() - get_option('gmt_offset')*60*60;
		return $delta;
	}
	
	static function ping($url, $timestamp)
	{
	 	$postdata = http_build_query(array(
				'key' => urlencode(get_option(self::$_prefix.'key')),
				'login' => urlencode(get_option(self::$_prefix.'login')),
				'search_id' => urlencode(get_option(self::$_prefix.'searchId')),
				'pluginid' => urlencode(get_option(self::$_prefix.'pluginId')),
				'cmsver' => $GLOBALS['wp_version']."_v1.5",
				'publishdate' => $timestamp,
				'urls' => $url
			));
			
		
		$host = 'site.yandex.ru';
		$length = strlen($postdata);		   
		
		
		$out = "POST /ping.xml HTTP/1.0\r\n";
		$out.= "HOST: ".$host."\r\n";
		$out.= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out.= "Content-Length: ".$length."\r\n\r\n";
		$out.= $postdata."\r\n\r\n";
		try{
		
			$errno='';
			$errstr = '';
			$result = '';
			$socket = @fsockopen($host, 80, $errno, $errstr, 30);
			if($socket){
				if(!fwrite($socket, $out)){
					throw new Exception("unable to write");
				} else {
					while ($in = @fgets ($socket, 1024)){
	            		$result.=$in;
	           		} 
				}
				
			} else {
				throw new Exception("unable to create socket");
			}
			fclose($socket);
			
			$result_xml = array();			
			preg_match('/(<.*>)/u', $result, $result_xml);
			if(count($result_xml) && function_exists('simplexml_load_string')) {
				$result = array_pop($result_xml);
				$xml = simplexml_load_string($result);
				
				if(isset( $xml -> error ) && isset( $xml -> error -> code)) {
					if($xml -> error -> code){
						$errorcode = (string)$xml -> error -> code;

						if (($errorcode=="ILLEGAL_VALUE_TYPE")||($errorcode=="SEARCH_NOT_OWNED_BY_USER")||($errorcode=="NO_SUCH_USER_IN_PASSPORT"))
							$message = "Один или несколько параметров в настройках плагина указаны неверно - ключ (key), логин (login) или ID поиска (searchid).";
						elseif ($errorcode == "TOO_DELAYED_PUBLISH")
							$message = "Максимальный срок отложенной публикации - 6 месяцев";
						elseif ($errorcode=="USER_NOT_PERMITTED")
						{
							$errorparam = (string)$xml -> error -> param;
							$errorvalue = (string)$xml -> error -> value;
							if ($errorparam=="key")
								$message = "Неверный ключ (key) ".$errorvalue.". Проверьте настройки плагина.";
							elseif ($errorparam=="ip")
								$message = "Запрос приходит с IP адреса ".$errorvalue.", который не указан в списке адресов в настройках вашего поиска";
							else
								$message = "Запрос приходит с IP адреса, который не указан в списке адресов в настройках вашего поиска, либо Вы указали неправильный ключ (key) в настройках плагина.";

						}
						else $message=$errorcode;
					}
				}
				elseif(isset($xml -> invalid)) {
					$invalidurl = $xml->invalid->url;
					$errorcode = $xml->invalid["reason"];
					if ($errorcode=="NOT_CONFIRMED_IN_WMC")
						$message = "Сайт не подтвержден в сервисе Яндекс.Вебмастер для указанного имени пользователя.";

					elseif ($errorcode=="OUT_OF_SEARCH_AREA")
						$message = "Адрес ".$invalidurl." не принадлежит области поиска вашей поисковой площадки.";

					elseif ($errorcode=="MALFORMED_URLS")
						$message = "Невозможно принять некорректный адрес: ".$invalidurl;
					
					else $message=$errorcode;
					
					} elseif( isset($xml -> added) 
					&& isset($xml -> added['count']) 
					&& $xml -> added['count'] >0) {
						$addedaddress = $xml->added->url;
						$message = "Плагин работает корректно. Последний принятый адрес: ".$addedaddress;
				}
				
				if(isset($message) && $message) {
					update_option(self::$_prefix.'message', $message);	
				}
				return true;
			}
		} catch(exception $e) {
			return false;
		}
	}
	
	static function settings_view()
	{
		$url='';
		echo '
		<form method="post" action="options.php"> ';
			settings_fields('yandex_pinger');
			do_settings_sections('yandex_pinger');
		echo '
		<input type="submit" value="Сохранить" />
		</form>
		';
	}
	
	static function register_settings()
	{
		add_settings_section(self::$_prefix.'_section', "Настройки плагина Яндекс.ПДС пингер", array('plgContentPinger','draw_section'), 'yandex_pinger');
		add_settings_field(self::$_prefix.'key', "Ключ", array('plgContentPinger','draw_field_key') , 'yandex_pinger', self::$_prefix.'_section');
		add_settings_field(self::$_prefix.'login', "Логин", array('plgContentPinger','draw_field_login') , 'yandex_pinger', self::$_prefix.'_section');
		add_settings_field(self::$_prefix.'searchId', "ID поиска", array('plgContentPinger','draw_field_search_id') , 'yandex_pinger', self::$_prefix.'_section');
		add_settings_field(self::$_prefix.'message', "Сообщение о Статусе плагина", array('plgContentPinger','draw_field_message') , 'yandex_pinger', self::$_prefix.'_section');
		
		register_setting( self::$_prefix, self::$_prefix.'key');
		register_setting( self::$_prefix, self::$_prefix.'login' );
		register_setting( self::$_prefix, self::$_prefix.'searchId' );
		register_setting( self::$_prefix, self::$_prefix.'message' );
		
		
	}
	
	static function unregister_settings()
	{
		unregister_setting( self::$_prefix, self::$_prefix.'key' );
  		unregister_setting( self::$_prefix, self::$_prefix.'searchId' );
  		unregister_setting( self::$_prefix, self::$_prefix.'login' );
		unregister_setting( self::$_prefix, self::$_prefix.'message' );
		
	}
			
	static function draw_section(){}
	
	static function draw_field_key()
	{
		$name = self::$_prefix.'key';
		$val = get_option($name);
		echo '<input size=50 type="text" value="'.$val.'" name="'.$name.'" />';
	}
	static function draw_field_login()
	{
		$name = self::$_prefix.'login';
		$val = get_option($name);
		echo '<input size=50 type="text" value="'.$val.'" name="'.$name.'" />';
	}
	static function draw_field_search_id()
	{
		$name = self::$_prefix.'searchId';
		$val = get_option($name);
		echo '<input size=50 type="text" value="'.$val.'" name="'.$name.'" />';
	}
	
	static function draw_field_message()
		{
			$name = self::$_prefix.'message';
			$val = get_option($name);
			echo '<textarea readonly cols=47 rows=4  name="'.$name.'">'.$val.'</textarea>';
		}
	
	static function draw_menu()
	{
		add_menu_page(
			'Настройки плагина Яндекс.ПДС пингер', 
			'Яндекс.ПДС', 
			'manage_options', 
			'yandex_pinger', 
			array('plgContentPinger','settings_view'),
			plugins_url('yandex-pinger/icon.png')
		);
	}
	static function on_activate()
	{
		self::init_settings();
		add_option(self::$_prefix.'key',self::$key,'',true);
		add_option(self::$_prefix.'login',self::$login,'',true);
		add_option(self::$_prefix.'searchId',self::$searchId,'',true);
		add_option(self::$_prefix.'message',self::$message,'',true);
		add_option(self::$_prefix.'pluginId',self::$pluginId,'',true);
		self::register_settings();
	}
	static function on_deactivate()
	{
		delete_option(self::$_prefix.'key');
		delete_option(self::$_prefix.'login');
		delete_option(self::$_prefix.'searchId');
		delete_option(self::$_prefix.'message');
		delete_option(self::$_prefix.'pluginId');
	}

}

if(is_admin()) {
		add_action('save_post',array('plgContentPinger', 'public_entity'));
		add_action('publish_page', array('plgContentPinger', 'public_entity'));
		add_action('publish_post', array('plgContentPinger', 'public_entity'));
		add_action('xmlrpc_publish_post',array('plgContentPinger', 'public_entity'));
		add_action('publish_future_post',array('plgContentPinger', 'public_entity'));
		
		add_action('admin_menu',array('plgContentPinger', 'draw_menu'));
		
		
		add_action('admin_init', array('plgContentPinger','register_settings'));
		register_activation_hook( __FILE__, array('plgContentPinger','on_activate') );
		register_deactivation_hook( __FILE__, array('plgContentPinger','on_deactivate') );
}


