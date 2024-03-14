<?php
/*
	Plugin Name:谷歌字体与Gravatar头像加速
	Plugin URI: http://guowen.party/
	Description:本插件可针对中国大陆地区网站对Google前端库与Gravatar头像服务进行替换加速，且支持SSL协议。感谢科大LUG提供镜像支持，360停止前端公共库服务对本插件没有任何影响。
	Version: 1.9
	Author: cqdaidong
	Author URI: http://guowen.party/
*/

/*Google公共库加速*/
function guowen_cdn_callback($buffer) {
	$buffer = str_replace(array( "googleapis.com"), "proxy.ustclug.org", $buffer);
        return $buffer;
}
function guowen_buffer_start() {
	ob_start("guowen_cdn_callback");
}
function guowen_buffer_end() {
	ob_end_flush();
}
add_action('init', 'guowen_buffer_start');
add_action('shutdown', 'guowen_buffer_end');

/*gravatar头像加速*/
function guowen_cdn_avatar($avatar) {
	$avatar = str_replace(array( "0.gravatar.com", "1.gravatar.com","www.gravatar.com", "2.gravatar.com","secure.gravatar.com"), "gravatar.proxy.ustclug.org", $avatar);
	return $avatar;
}
function guowen_avatar_start() {
	ob_start("guowen_cdn_avatar");
}
function guowen_avatar_end() {
	ob_end_flush();
}
add_action('init', 'guowen_avatar_start');
add_action('shutdown', 'guowen_avatar_end');
?>