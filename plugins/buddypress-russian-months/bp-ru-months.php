<?php
/*
Plugin Name: BuddyPress Russian Months
Plugin URI: http://cosydale.com/
Description: Данный плагин исправляет неправильный падеж месяца в дате сообщений (сетевых и личных), записей, комментариев (в случае, если выбран формат Число Месяц [Год]).
Version: 0.4
Requires at least: WPMU 2.8.4, BuddyPress 1.1
Tested up to: WPMS 3.1.1, BuddyPress 1.2.8
Author: slaFFik
Author URI: http://cosydale.com
License: GNU GENERAL PUBLIC LICENSE 3.0 http://www.gnu.org/licenses/gpl.txt
Site Wide Only: true
*/

function bp_ru_month_name( $month_name = null ) {
	if ( WPLANG != 'ru_RU' )
		return $month_name;
	
	$month_name = preg_replace('/([A-Za-z]*) ([0-9]*), ([A-Z0-9+*?].*)/m', '\2 \1, \3', $month_name);
	$month_name_replace = array (
		"Январь" => "января",
		"Февраль" => "февраля",
		"Март" => "марта",
		//"Апрель" => "апреля",
		"Май" => "мая",
		"Июнь" => "июня",
		"Июль" => "июля",
		"Август" => "августа",
		"Сентябрь" => "сентября",
		"Октябрь" => "октября",
		"Ноябрь" => "ноября",
		"Декабрь" => "декабря",
		"January" => "января",
		"February" => "февраля",
		"March" => "марта",
		"April" => "апреля",
		"May" => "мая",
		"June" => "июня",
		"July" => "июля",
		"August" => "августа",
		"September" => "сентября",
		"October" => "октября",
		"November" => "ноября",
		"December" => "декабря",
	);
	return strtr($month_name, $month_name_replace);
}

// For BP 1.2.x
add_filter('bp_format_time', 'bp_ru_month_name');

// For BP 1.1.x
add_filter('bp_get_wire_poster_date', 'bp_ru_month_name');
add_filter('bp_get_wire_post_date', 'bp_ru_month_name');
add_filter('bp_get_comment_date', 'bp_ru_month_name');
add_filter('bp_get_post_date', 'bp_ru_month_name');
add_filter('bp_get_format_time', 'bp_ru_month_name');
add_filter('bp_get_message_thread_last_post_date', 'bp_ru_month_name');
add_filter('bp_get_message_date_sent', 'bp_ru_month_name');
add_filter('bp_get_message_notice_post_date', 'bp_ru_month_name');

?>