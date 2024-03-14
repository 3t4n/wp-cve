<?php
/*
Plugin Name: Japanese font for WordPress (Previously: Japanese Font for TinyMCE)
Description: Adds Japanese fonts to Gutenberg and TinyMCE.
Version: 4.28
Author: raspi0124
Author URI: https://raspi0124.dev/
License: GPLv2
*/

/*  Copyright 2017-2022 raspi0124 (email : raspi0124@gmail.com)

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
Copyright  2017  raspi0124

このプログラムはフリーソフトウェアです。あなたはこれを、フリーソフトウェ
ア財団によって発行された GNU 一般公衆利用許諾契約書(バージョン2か、希
望によってはそれ以降のバージョンのうちどれか)の定める条件の下で再頒布
または改変することができます。

このプログラムは有用であることを願って頒布されますが、*全くの無保証*
です。商業可能性の保証や特定の目的への適合性は、言外に示されたものも含
め全く存在しません。詳しくはGNU 一般公衆利用許諾契約書をご覧ください。

あなたはこのプログラムと共に、GNU 一般公衆利用許諾契約書の複製物を一部
受け取ったはずです。もし受け取っていなければ、フリーソフトウェア財団ま
で請求してください(宛先は the Free Software Foundation, Inc., 59
Temple Place, Suite 330, Boston, MA 02111-1307 USA)。

For futrher information about licence, please read LICENCE.txt.
Tinymce版作成において参考にさせていただいた記事:
http://www.de2p.co.jp/tech/wordpress/admin-notices/
http://learn.wpeditpro.com/adding-new-wordpress-tinymce-fonts/
https://nelog.jp/add-quicktags-to-wordpress-text-editor
https://wpdocs.osdn.jp/Settings_API
https://nelog.jp/wordpress-visual-editor-font-size
Gutenberg版で参考になった記事についてははgutenjpfont/gutenjpfont.phpをご覧ください
*/
// define $
$version = "4.28";
//1 is enable, 0 is disable unless written.
// config 1 is CDN
//conbfig 2 is font load mode
//config 3 is enable/disable gutenberg setting
//config 4 is load by header or footer. 0=header, 1=footer
$config1 = get_option('tinyjpfont_check_cdn');
$config2 = get_option('tinyjpfont_select');
$config3 = get_option('tinyjpfont_gutenberg');
$config4 = get_option('tinyjpfont_head');
$config5 = get_option('tinyjpfont_default_font');
$defaultvalue = "0";
$isknown = "";
//Load settings.php
include(plugin_dir_path(__FILE__) . 'settings.php');
//Load notice.php
include(plugin_dir_path(__FILE__) . 'notice.php');
//もしCDNがtrueでフォントロードモードもNormalだったら
if ($config1 == "1" and $config2 == "0") {
	// enque CSS at CDN
	function tinyjpfont_style()
	{
		wp_register_style('tinyjpfont-styles', 'https://cdn.jsdelivr.net/gh/raspi0124/Japanese-font-for-TinyMCE@stable/addfont.css');
		wp_enqueue_style('tinyjpfont-styles');
	}
	//もしheader読み込みだったら
	if ($config4 ==  "0") {
		add_action('wp_enqueue_scripts', 'tinyjpfont_style');
		add_action('admin_enqueue_scripts', 'tinyjpfont_style');
	} else {
		add_action('get_footer', 'tinyjpfont_style');
		add_action('admin_enqueue_scripts', 'tinyjpfont_style');
	}
}
//もしCDNがtrueでフォントロードモードがLiteだったら
if ($config1 == "1" and $config2 == "1") {
	// enque Lite version of CSS at CDNs
	function tinyjpfont_style()
	{
		wp_register_style('tinyjpfont-styles', 'https://cdn.jsdelivr.net/gh/raspi0124/Japanese-font-for-TinyMCE@stable/addfont_lite.css');
		wp_enqueue_style('tinyjpfont-styles');
	}
	if ($config4 ==  "0") {
		add_action('wp_enqueue_scripts', 'tinyjpfont_style');
		add_action('admin_enqueue_scripts', 'tinyjpfont_style');
	} else {
		add_action('get_footer', 'tinyjpfont_style');
		add_action('admin_enqueue_scripts', 'tinyjpfont_style');
	}
}
//もしCDNがfalseでフォントロードモードがLiteだったら
if ($config1 == "0" and $config2 == "1") {
	function tinyjpfont_style()
	{
		wp_register_style('tinyjpfont-styles', plugin_dir_url(__FILE__) . 'addfont_lite.css');
		wp_enqueue_style('tinyjpfont-styles');
	}
	if ($config4 ==  "0") {
		add_action('wp_enqueue_scripts', 'tinyjpfont_style');
		add_action('admin_enqueue_scripts', 'tinyjpfont_style');
	} else {
		add_action('get_footer', 'tinyjpfont_style');
		add_action('admin_enqueue_scripts', 'tinyjpfont_style');
	}
}
if ($config1 == "0" and $config2 == "0") {
	//もしCDNがFalseでロードモードがNormalだったら
	function tinyjpfont_style()
	{
		wp_register_style('tinyjpfont-styles', plugin_dir_url(__FILE__) . 'addfont.css');
		wp_enqueue_style('tinyjpfont-styles');
	}
	if ($config4 ==  "0") {
		add_action('wp_enqueue_scripts', 'tinyjpfont_style');
		add_action('admin_enqueue_scripts', 'tinyjpfont_style');
	} else {
		add_action('get_footer', 'tinyjpfont_style');
		add_action('admin_enqueue_scripts', 'tinyjpfont_style');
	}
}

//add gutenberg support.
if ($config3 == "1") {
	include(plugin_dir_path(__FILE__) . 'gutenjpfont/gutenjpfont.php');
} else {
}


function tinyjpfont_get_custom_fonts()
{
	$config2 = get_option('tinyjpfont_select');
	if (!isset($config2)) {
		$config2 = "0";
	}
	//add font to tiny mce
	if ($config2 == "0") {
		$custom_fonts = ';' . 'ふい字=Huifont;Noto Sans Japanese=Noto Sans Japanese;太字なNoto Sans Japanese=Noto Sans Japanese-900;細字なNoto Sans Japanese=Noto Sans Japanese-100;エセナパJ=esenapaj;ほのか丸ゴシック=honokamaru;こころ明朝体=kokorom;青柳衡山フォントT=aoyanagiT;たぬき油性マジック=tanukiM';
	} else {
		$custom_fonts = ';' . 'ふい字=Huifont;Noto Sans Japanese=Noto Sans Japanese;';
	}
	return $custom_fonts;
}

$seted_custom_fonts = tinyjpfont_get_custom_fonts();

function tinyjpfont_load_custom_fonts($init)
{
	$stylesheet_url = plugin_dir_url(__FILE__) . 'addfont.css';
	if (empty($init['content_css'])) {
		$init['content_css'] = $stylesheet_url;
	} else {
		$init['content_css'] = $init['content_css'] . ',' . $stylesheet_url;
	}
	global $seted_custom_fonts;
	$custom_fonts = $seted_custom_fonts;

	if (!isset($custom_fonts)) {
		$custom_fonts = ';' . 'ふい字=Huifont;Noto Sans Japanese=Noto Sans Japanese';
	}
	$font_formats = isset($init['font_formats']) ? $init['font_formats'] : 'Andale Mono=andale mono,times;Arial=arial,helvetica,sans-serif;Arial Black=arial black,avant garde;Book Antiqua=book antiqua,palatino;Comic Sans MS=comic sans ms,sans-serif;Courier New=courier new,courier;Georgia=georgia,palatino;Helvetica=helvetica;Impact=impact,chicago;Symbol=symbol;Tahoma=tahoma,arial,helvetica,sans-serif;Terminal=terminal,monaco;Times New Roman=times new roman,times;Trebuchet MS=trebuchet ms,geneva;Verdana=verdana,geneva;Webdings=webdings;Wingdings=wingdings';
	$init['font_formats'] = $font_formats . $custom_fonts;
	return $init;

	add_filter('tiny_mce_before_init', 'tinyjpfont_load_custom_fonts');
}
add_action('tiny_mce_before_init', 'tinyjpfont_load_custom_fonts');


add_filter('tiny_mce_before_init', function ($settings) {
	//フォントサイズの指定
	$settings['fontsize_formats'] =
		'10px 12px 14px 16px 18px 20px 24px 28px 32px 36px 42px 48px';

	return $settings;
});

//also add some font size selecting function for non-tinymce-advanced user.
//https://nelog.jp/wordpress-visual-editor-font-size
add_filter('mce_buttons', function ($buttons) {
	array_push($buttons, 'fontsizeselect');
	return $buttons;
});
//finish


//add font selection to quicktag also<alpha>
//http://webtukuru.com/web/wordpress-quicktag/
//https://wpdocs.osdn.jp/%E3%82%AF%E3%82%A4%E3%83%83%E3%82%AF%E3%82%BF%E3%82%B0API
function tinyjpfont_quicktag()
{
	//スクリプトキューにquicktagsが保存されているかチェック
	if (wp_script_is('quicktags')) { ?>
<script>
QTags.addButton('tinyjpfont-noto', 'Noto Sans Japanese', '<span style="font-family: Noto Sans Japanese;">', '</span>');
QTags.addButton('tinyjpfont-huiji', 'ふい字', '<span style="font-family: Huifont;">', '</span>');
</script>
<?php
	}
}
add_action('admin_print_footer_scripts', 'tinyjpfont_quicktag');



//add font selector to TinyMCE also. no more TinyMCE Advanced plugin

add_filter('tiny_mce_before_init', 'tinyjpfont_custom_tiny_mce_style_formats');
function tinyjpfont_custom_tiny_mce_style_formats($settings)
{
	$style_formats = array(
		array(
			'title' => 'Noto Sans Japanese',
			'block' => 'div',
			'classes' => 'noto',
			'wrapper' => true,
		),
		array(
			'title' => 'Huifont',
			'block' => 'div',
			'classes' => 'huiji',
			'wrapper' => true,
		),
	);
	$settings['style_formats'] = json_encode($style_formats);
	return $settings;
}

add_filter('mce_buttons', 'tinyjpfont_add_original_styles_button');
function tinyjpfont_add_original_styles_button($buttons)
{
	array_splice($buttons, 1, 0, 'fontselect');
	return $buttons;
}

//DEFAULT FONT
function tinyjpfont_getdefaultfonturl()
{
	$config5 = get_option('tinyjpfont_default_font');
	$fontname = $config5;
	if (!isset($config5) || $config5 != "") {
		return plugin_dir_url(__FILE__) . "default-font-css.php?fn=Noto";
	} else {
		$defaultfont_url = plugin_dir_url(__FILE__) . "default-font-css.php?fn=" . $fontname;
		return $defaultfont_url;
	}
}
function tinyjpfont_add_default_font()
{
	if (is_admin()) {
		$defaultfont_url = tinyjpfont_getdefaultfonturl();
		add_editor_style($defaultfont_url);
		wp_register_style('tinyjpfont-default-font', $defaultfont_url);
		wp_enqueue_style('tinyjpfont-default-font');
	}
}
add_action('init', 'tinyjpfont_add_default_font');