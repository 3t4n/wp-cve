<?php
/***********************************************************
Plugin Name: hpb seo plugin for WordPress
Plugin URI: http://www.allegro-inc.com/seo/9080.html
Description: ホームページビルダー向けのSEO対策プラグインです ※このプラグインを使用するには、hpbダッシュボードを使用する必要があります。
Version: 2.2.2
Author: Allegro Marketing
Author URI:http://seo-composer.com
License:GPL2
***********************************************************/

/**============================================================================
 * 定数
 * ==========================================================================*/

class hpbseoClass{	//※constでは式や関数は使えない。
	const field_prefix     = "_hpbseo_";	//テーブル名接頭語
	const field_obj_prefix = "hpbseo_";		//テーブル内の要素名接頭語
	const input_prefix     = "hpbseo_";		//form部品接頭語（id/class）
	const metabox_prefix   = "hpbseo";		//add_meta_boxのid
}

define('PLUGIN_URL'        , plugin_dir_url( __FILE__ ));	//プラグインフォルダまでのURL
define('PLUGIN_IMG_URL'    , PLUGIN_URL . 'image/');		//プラグイン-画像フォルダまでのURL
//define('PLUGIN_URL'        , plugins_url("/", __FILE__));	//プラグインフォルダまでのURL
//define('PLUGIN_IMG_URL'    , plugins_url("image/", __FILE__));		//プラグイン-画像フォルダまでのURL

define('CSS_FILE_NAME'     , 'hpbseo.css');					//CSSファイル名
define('JS_FILE_NAME_ADMIN', 'hpbseo.js');					//JSファイル名
define('IMG_ICON', 'icon_seo.png');							//アイコン
define('IMG_MENU', 'menu_seo.png');							//アイコン

define('LINT_CONTENT_TUNE' , 400);							//コンテンツ分析表示ON/OFF閾値（記事の文字数）
define('DEFAULT_DISP_IMAGE_FLG' , true);					//表示イメージ設定デフォルト値（on/off）
define('DEFAULT_DISP_IMAGE_OPT' , 'local');					//表示イメージ設定デフォルト値（local/http）

define('CNFLICT_LIST' , 'all-in-one-seo-pack,headspace2');	//競合チェック用（プラグインのフォルダ名を指定／使うときは「,」でsplitする）

define('DISPIMAGE_MAX_TITLE'    , 66);						//表示イメージの最大バイト数（タイトル）
define('DISPIMAGE_MAX_URL'      , 60);						//表示イメージの最大バイト数（URL）
define('DISPIMAGE_MAX_META_DES' , 240);						//表示イメージの最大バイト数（スニペット）

define('CATEGORY_SERVICE_URL' , "http://www.allegro-inc.com/user_data/hpb_seo_plugin.php");	//カテゴリサービス登録状況URL
define('HELP_URL'             , "http://www.allegro-inc.com/seo/9080.html");				//ヘルプページURL
define('HPB18_URL'            , "http://seo-composer.com");		//HPB18 seoマスターページURL

define('TEMPLATE_CHECK' , "hpb18T");						//hpbのテンプレートフォルダ名（前方一致検索用）
define('HPB_PATH' , "hpbtool/hpbtools.php");				//hpbのダッシュボードプラグインフォルダ名

/**----------------------------------------------------------------------------
 * cssファイル読込
 * --------------------------------------------------------------------------*/
function fncHpbSeo_IncludeCSS() {
	wp_enqueue_style( "hpbseo_css", PLUGIN_URL . CSS_FILE_NAME);	//引数：識別名（＝登録させるフック名）,ファイルパス
}

/**----------------------------------------------------------------------------
 * jsファイル読込（ダッシュボード用）
 * --------------------------------------------------------------------------*/
function fncHpbSeo_IncludeAdminJS() {
	wp_enqueue_script( "jquery");
	wp_enqueue_script( "hpbseo_admin_js", PLUGIN_URL . JS_FILE_NAME_ADMIN);
}



/**----------------------------------------------------------------------------
 * プラグイン有効化
 * --------------------------------------------------------------------------*/
function fncHpbSeo_ActivationHook() {

	//既存がある場合はそのまま使用
	$global_setting  = get_option(hpbseoClass::field_prefix . 'global_setting');
	if($global_setting){
		return;
	}

	//一括設定項目の保存（wp_options）
	$save_arr = array(
		 hpbseoClass::field_obj_prefix . 'global_title'    => ''
		,hpbseoClass::field_obj_prefix . 'global_meta_des' => ''
		,hpbseoClass::field_obj_prefix . 'global_meta_key' => ''
		,hpbseoClass::field_obj_prefix . 'dispimage_flg'   => DEFAULT_DISP_IMAGE_FLG
		,hpbseoClass::field_obj_prefix . 'dispimage_opt'   => DEFAULT_DISP_IMAGE_OPT
	);
	update_option(hpbseoClass::field_prefix . 'global_setting' , $save_arr);

}

/**----------------------------------------------------------------------------
 * hpb環境チェック
 * --------------------------------------------------------------------------*/
function fncHpbSeo_hpbCheck() {

	//テーマのチェック
	$_template_check_list = array("hpb18T", "hpb19T", "hpb19S", "hpb20T", "hpb20S", "hpb21T", "hpb21S", "hpb22T", "hpb22S");
	$template_name = wp_get_theme()->template;
	$flg = false;
	for($i=0;$i<count($_template_check_list);$i++){
		if(strpos($template_name, $_template_check_list[$i], 0) === 0){
			$flg = true;
			break;
		}
	}
	if(!$flg){
		return false;
	}

	//hpbダッシュボードが有効化されているか
	$active_plugins = get_option('active_plugins');
	if(array_search(HPB_PATH,$active_plugins) === false){
		return false;
	}

	//OK
	return true;
}

/**----------------------------------------------------------------------------
 * カスタム投稿タイプのスラッグ一覧取得
 * --------------------------------------------------------------------------*/
function fncHpbSeo_getCustomSlug() {
	//一覧取得
	$custom_post_type_obj  = get_post_types(array('_builtin' => false), 'objects');
	//スラッグ名の配列に変換
	$custom_post_type_list = array_keys($custom_post_type_obj);
	return $custom_post_type_list;
}


/**----------------------------------------------------------------------------
 * \' \" \\のアンエスケープ（画面表示用）
 * --------------------------------------------------------------------------*/
function fncHpbSeo_unescape($str) {
	//\'変換
	$str = str_replace("\'", "'", $str);
	//\"変換（シングルに置換）
	$str = str_replace('\"', "'", $str);
	//\\変換
//	$str = str_replace('\\', "\\", $str);
	$str = str_replace("\\\\", "\\", $str);

	return $str;
}


/**----------------------------------------------------------------------------
 * コンテンツ分析
 * --------------------------------------------------------------------------*/
class clsHpbSeo_ContentTune{

	function __construct() {

		//カスタム投稿タイプの一覧取得
		$post_type_list = fncHpbSeo_getCustomSlug();
		//「投稿」を追加
		$post_type_list[] = 'post';
		//カスタムボックス表示
		foreach( $post_type_list as $val ) {
			add_meta_box(
				hpbseoClass::metabox_prefix . "ContentTune",
				"コンテンツ分析", 
				array($this, "fncContentTune"), 
				$val,
				"side",
				"high"
			);
		}

	}

	function fncContentTune($post){
		$html  = "";

		//記事の文字数を取得（初期表示）
		$str = strip_tags($post-> post_content);
		$str = str_replace(array("\r\n","\r","\n"), '', $str);
		$len = mb_strlen($str);

		//*****更新ボタン*****
 		$html .= "\n" . '<div id="' . hpbseoClass::input_prefix . 'content_tune_btn_div">';
		$html .= "\n" . '<input type="button" id="' . hpbseoClass::input_prefix . 'content_tune" class="button-secondary" value="更新">';
 		$html .= "\n" . '</div>';
		//*****メインテーマ*****
		$html .= "\n" . '<div id="' . hpbseoClass::input_prefix . 'content_tune_wrap">';
		$html .= "\n" . '<div id="' . hpbseoClass::input_prefix . 'content_tune_div" class="' . hpbseoClass::input_prefix . 'display_none">';
		$html .= "\n" . '<span class="' . hpbseoClass::input_prefix . 'contenttune_subtitle">ページのメインテーマ</span>';
//		$html .= "\n" . '<br class="clearfix" />';
		$html .= "\n" . '<br />';
		$html .= "\n" . '<span id="' . hpbseoClass::input_prefix . 'main_theme_word"></span>';
		$html .= "\n" . '<br />';
		$html .= "\n" . '<div  id="' . hpbseoClass::input_prefix . 'main_theme_alert" class="' . hpbseoClass::input_prefix . 'arrow_box_top"></div>';
		$html .= "\n" . '<br />';
		//*****構成ワード*****
		$html .= "\n" . '<div id="' . hpbseoClass::input_prefix . 'composition_word_div">';
		$html .= "\n" . '<span class="' . hpbseoClass::input_prefix . 'contenttune_subtitle">ページの構成ワード</span>';
//		$html .= "\n" . '<br class="clearfix" />';
		$html .= "\n" . '<br />';
		$html .= "\n" . '<div id="' . hpbseoClass::input_prefix . 'composition_word_graph"></div>';
		$html .= "\n" . '<div  id="' . hpbseoClass::input_prefix . 'composition_word_alert" class="' . hpbseoClass::input_prefix . 'arrow_box_top"></div>';
		//区切り線
		$html .= "\n" . '<hr class="' . hpbseoClass::input_prefix . 'sep_line">';
		//閾値
		$html .= "\n" . '<input type="hidden" id="' . hpbseoClass::input_prefix . 'lint_content_tune" value="' . LINT_CONTENT_TUNE . '">';
		$html .= "\n" . '</div>';
		$html .= "\n" . '</div>';
		//*****ローディング*****
		$html .= "\n" . '<div id="' . hpbseoClass::input_prefix . 'content_tune_loading"><img src="' . PLUGIN_IMG_URL . 'loading.gif"></div>';
		$html .= "\n" . '</div>';

		//*****文字数*****
		$html .= "\n" . '<div class="clearfix">';
		$html .= "\n" . '<span class="' . hpbseoClass::input_prefix . 'contenttune_subtitle">ページの文字数</span>';
		$html .= "\n" . '<br />';
		$html .= "\n" . '<span id="' . hpbseoClass::input_prefix . 'content_length">' . $len . '</span>文字';
		$html .= "\n" . '<br />';
		$html .= "\n" . '<div  id="' . hpbseoClass::input_prefix . 'title_alert"></div>';
		$html .= "\n" . '<br />';
		$html .= "\n" . '</div>';

		//カスタム投稿タイプリスト
		$post_type_list = fncHpbSeo_getCustomSlug();
		$post_type_txt  = join(',',$post_type_list);
		$html .= "\n" . '<input type="hidden" id="' . hpbseoClass::input_prefix . 'custom_post_type_list" value="' . $post_type_txt . '">';

		// 表示
		echo $html;
	}

}


/**----------------------------------------------------------------------------
 * ヘッダ情報
 * --------------------------------------------------------------------------*/
class clsHpbSeo_HeadTune{

	function __construct() {

		//カスタム投稿タイプの一覧取得
		$post_type_list = fncHpbSeo_getCustomSlug();
		//「投稿」を追加
		$post_type_list[] = 'post';
		//カスタムボックス表示
		foreach( $post_type_list as $val ) {
			add_meta_box(
				hpbseoClass::metabox_prefix . "HeadTune",
				"ヘッダ情報",
				array($this, "fncHeadTune"),
				$val,
				"normal", 
				"high"
			);
		}

	}

	function fncHeadTune($post){
		global $post;

		//他のサイトから来ていないかどうか（保護用？）
		//echo wp_nonce_field('example_meta', 'my_meta_nonce');
		echo wp_nonce_field( hpbseoClass::input_prefix . 'nonce_action',hpbseoClass::input_prefix . 'nonce_field' );

		//DBから取得
		$meta  = get_post_meta($post->ID, hpbseoClass::field_prefix . 'meta', true);
		if($meta){
			$meta_des = $meta[hpbseoClass::field_obj_prefix . 'meta_des'] ;
			$meta_key = $meta[hpbseoClass::field_obj_prefix . 'meta_key'] ;
			$meta_des_add_flg = $meta[hpbseoClass::field_obj_prefix . 'meta_des_add_flg'] ;
			$meta_key_add_flg = $meta[hpbseoClass::field_obj_prefix . 'meta_key_add_flg'] ;
		}else{
			$meta_des = '';
			$meta_key = '';
			$meta_des_add_flg = 0;
			$meta_key_add_flg = 0;
		}

		//一括設定値の取得
		$global_setting  = get_option(hpbseoClass::field_prefix . 'global_setting');
		$global_meta_des = $global_setting[hpbseoClass::field_obj_prefix . 'global_meta_des'];
		$global_meta_key = $global_setting[hpbseoClass::field_obj_prefix . 'global_meta_key'];

		//アンエスケープ
		$meta_des = fncHpbSeo_unescape($meta_des);
		$meta_key = fncHpbSeo_unescape($meta_key);
		$global_meta_des = fncHpbSeo_unescape($global_meta_des);
		$global_meta_key = fncHpbSeo_unescape($global_meta_key);

		//表示イメージ出力
		$this->fncDispImage($post->ID,$meta_des,$meta_des_add_flg);

		// フォーム部のHTML
		$html = '';

		//*****メタディスクリプション*****
		$html .= "\n" . '<br />';
		$html .= "\n" . '<span class="' . hpbseoClass::input_prefix . 'headtune_subtitle">メタディスクリプション設定</span>';
		//一括設定呼び出しボタン
		$html .= "\n" . '<input type="button" id="' . hpbseoClass::input_prefix . 'global_set_meta_des" class="button-secondary" value="一括設定呼び出し"';
		if($global_meta_des==""){
			$html .= ' disabled="disabled" ';
		}
		$html .= "\n" . ' >';
		$html .= "\n" . '<br />';
		//テキストエリア
		$html .= "\n" . '<textarea cols="50" rows="3" id="' . hpbseoClass::input_prefix . 'meta_des" name="' . hpbseoClass::input_prefix . 'meta_des">' . $meta_des . '</textarea>';
		//入力ボックス下メッセージ
		$html .= "\n" . '<p class="' . hpbseoClass::input_prefix . 'meta_cautions">';
		$html .= "\n" . '※空白の場合、テンプレートから自動的に判断された内容で反映されます。';
		$html .= "\n" . '</p>';
		//プレビュー
		$html .= "\n" . "下記は、上記と一括設定の内容をあわせたものです。";
		$html .= "\n" . '<div class="clearfix">';
		$html .= "\n" . '<div class="' . hpbseoClass::input_prefix . 'leftbox">';
		$html .= "\n" . '<p id="' . hpbseoClass::input_prefix . 'meta_des_preview" class="' . hpbseoClass::input_prefix . 'meta_preview"></p>';
		$html .= "\n" . '</div>';
		//閾値
		$html .= "\n" . '<div id="' . hpbseoClass::input_prefix . 'meta_des_alert"></div>';
		$html .= "\n" . '</div>';
		//チェックボックス
		$html .= "\n" . '<input type="hidden" name="' . hpbseoClass::input_prefix . 'meta_des_add_flg" value="0">';	//null回避用
		$html .= "\n" . '<input type="checkbox" id="' . hpbseoClass::input_prefix . 'meta_des_add_flg" name="' . hpbseoClass::input_prefix . 'meta_des_add_flg" value="1" ';
		if($meta_des_add_flg==1){
			$html .= ' checked="checked" ';
		}
		if($global_meta_des==""){
			$html .= ' disabled="disabled" ';
		}
		$html .= ' />';
		$html .= '<span ';
		if($global_meta_des==""){
			$html .= 'class="' . hpbseoClass::input_prefix . 'disabled_text"';
		}
		$html .= '> 一括設定の文字列を追記して反映する。</span>';
		$html .= "\n" . '<input type="hidden" id="' . hpbseoClass::input_prefix . 'global_meta_des" value="' . $global_meta_des . '">';
		$html .= "\n" . '<br />';

		//区切り線
		$html .= "\n" . '<hr class="' . hpbseoClass::input_prefix . 'sep_line">';

		//*****メタキーワード*****
		$html .= "\n" . '<span class="' . hpbseoClass::input_prefix . 'headtune_subtitle">メタキーワード設定</span>';
		//一括設定呼び出しボタン
		$html .= "\n" . '<input type="button" id="' . hpbseoClass::input_prefix . 'global_set_meta_key" class="button-secondary" value="一括設定呼び出し"';
		if($global_meta_key==""){
			$html .= ' disabled="disabled" ';
		}
		$html .= "\n" . ' >';
		$html .= "\n" . '<br />';
		//テキストボックス
		$html .= "\n" . '<input type="text" id="' . hpbseoClass::input_prefix . 'meta_key" name="' . hpbseoClass::input_prefix . 'meta_key" value="' . $meta_key . '" />';
		//入力ボックス下メッセージ
		$html .= "\n" . '<p class="' . hpbseoClass::input_prefix . 'meta_cautions">';
		$html .= "\n" . '※できる限りページ個々のメタキーワードを設定してください。';
		$html .= "\n" . '<br />';
		$html .= "\n" . '※デフォルトのキーワードを変更する場合はオプションから変更してください。';
		$html .= "\n" . '</p>';
		//プレビュー
		$html .= "\n" . "下記は、上記と一括設定の内容をあわせたものです。";
		$html .= "\n" . '<div class="clearfix">';
		$html .= "\n" . '<div class="' . hpbseoClass::input_prefix . 'leftbox">';
		$html .= "\n" . '<p id="' . hpbseoClass::input_prefix . 'meta_key_preview" class="' . hpbseoClass::input_prefix . 'meta_preview"></p>';
		$html .= "\n" . '</div>';
		//閾値
		$html .= "\n" . '<div id="' . hpbseoClass::input_prefix . 'meta_key_alert"></div>';
		$html .= "\n" . '</div>';
		//チェックボックス
		$html .= "\n" . '<input type="hidden" name="' . hpbseoClass::input_prefix . 'meta_key_add_flg" value="0">';	//null回避用
		$html .= "\n" . '<input type="checkbox" id="' . hpbseoClass::input_prefix . 'meta_key_add_flg" name="' . hpbseoClass::input_prefix . 'meta_key_add_flg" value="1" ';
		if($meta_key_add_flg==1){
			$html .= ' checked="checked" ';
		}
		if($global_meta_key==""){
			$html .= ' disabled="disabled" ';
		}
		$html .= ' />';
		$html .= '<span ';
		if($global_meta_key==""){
			$html .= 'class="' . hpbseoClass::input_prefix . 'disabled_text"';
		}
		$html .= '> 一括設定の文字列を追記して反映する。</span>';
		$html .= "\n" . '<input type="hidden" id="' . hpbseoClass::input_prefix . 'global_meta_key" value="' . $global_meta_key . '">';
		$html .= "\n" . '<br />';
		$html .= "\n" . '<input type="hidden" id="' . hpbseoClass::input_prefix . 'plugin_url" value="' . PLUGIN_URL . '">';

		//区切り線
		$html .= "\n" . '<hr class="' . hpbseoClass::input_prefix . 'sep_line">';

//		//*****カテゴリサービス登録状況*****
//		$html .= "\n" . '<input type="hidden" id="' . hpbseoClass::input_prefix . 'view_post_url" value="' . get_permalink($post->ID) . '">';
//		$html .= "\n" . '<span class="' . hpbseoClass::input_prefix . 'headtune_subtitle">カテゴリサービス登録状況</span>';
//		$html .= "\n" . '<div class="clearfix" style="margin-bottom:15px;">';
//		//左
//		$html .= "\n" . '<div id="' . hpbseoClass::input_prefix . 'category_box" class="clearfix">';
//		$html .= "\n" . '<div id="' . hpbseoClass::input_prefix . 'category_box_left">';
//		$html .= "\n" . '<p id="' . hpbseoClass::input_prefix . 'category_value"></p>';
//		$html .= "\n" . '<p id="' . hpbseoClass::input_prefix . 'category_comment" class="' . hpbseoClass::input_prefix . 'category_comment">登録状況</p>';
//		$html .= "\n" . '</div>';
//		$html .= "\n" . '<div id="' . hpbseoClass::input_prefix . 'category_box_right">';
//		$html .= "\n" . '<span id="' . hpbseoClass::input_prefix . 'category_cnt"></span>';
//		$html .= "\n" . '<br />';
//		$html .= "\n" . 'ディレクトリ登録の詳しい状況は下記よりご確認ください。';
//		$html .= "\n" . '<p class="link">';
//		$html .= "\n" . '<a href="' . CATEGORY_SERVICE_URL . '" target="_blank">&gt;&gt;カテゴリ登録状況を確認する（カテゴリ登録とは？）</a>';
//		$html .= "\n" . '</p>';
//		$html .= "\n" . '</div>';
//		$html .= "\n" . '</div>';
////		//一覧画像
////		$html .= "\n" . '<div id="' . hpbseoClass::input_prefix . 'category_image">';
////		$html .= "\n" . '</div>';
//		$html .= "\n" . '</div>';
		$html .= "\n" . '<a href="' . HPB18_URL . '" target="_blank">ホームページ・ビルダー22 ビジネスプレミアムに同梱されているSEO Composerについてはこちら</a>';

		//プラグイン競合チェック
		if($this->fncConflictCheck()){
			$html .= "\n" . '<hr class="' . hpbseoClass::input_prefix . 'sep_line">';
			$html .= "\n" . '<p id="' . hpbseoClass::input_prefix . 'conflict_message">プラグインが競合する可能性があります。</p>';
		}

		//表示
		echo $html;
	}

	//表示イメージ出力
	//スニペットはディスクリプションを使用
	//http選択時の優先順位
	// 1.ページ毎の個別設定
	// 2.サイト全体の一括設定
	// 3.設定―ブログタイトル・キャッチフレーズ or URLから取得（オプション画面で選択）
	function fncDispImage($post_id,$meta_des,$meta_des_add_flg){
		global $post;

		//一括設定値の取得
		$global_setting  = get_option(hpbseoClass::field_prefix . 'global_setting');
		$global_meta_des = $global_setting[hpbseoClass::field_obj_prefix . 'global_meta_des'];
		$dispimage_flg   = $global_setting[hpbseoClass::field_obj_prefix . 'dispimage_flg'];
		$dispimage_opt   = $global_setting[hpbseoClass::field_obj_prefix . 'dispimage_opt'];
		//アンエスケープ
		$global_meta_des = fncHpbSeo_unescape($global_meta_des);

		//表示イメージを出力しない場合
		if(!$dispimage_flg){
			return;
		}

		//ステータスチェック（公開前の場合は取得できない）
		if(get_post_status($post_id)!="publish"){
			return;
		}

		$disp_title    = "";
		$disp_meta_des = "";
		$err_msg       = "";
		$err_tmp       = array();

		if($meta_des!=""){
			//個別設定有の場合
			$disp_meta_des = $meta_des;
			//一括設定を使用する場合
			if($global_meta_des!="" && $meta_des_add_flg==1){
				$disp_meta_des .= $global_meta_des;
			}
		}else if($global_meta_des!=""){
			//一括設定を使用する場合
			$disp_meta_des = $global_meta_des;
		}

		//ページのURL取得（日本語は自動でエンコードされる）
		$page_url = get_permalink($post_id);

		if($dispimage_opt=="local"){
			//ワードプレス内から取得
//			$disp_title = wp_title('',false);
//			if($disp_title==""){
				$disp_title = $post->post_title . "｜" . get_bloginfo('name') ;
//			}
			if($disp_meta_des==""){
				$disp_meta_des = get_bloginfo('description');
			}

		}else{
			//実際のURLから取得

			//SSLの場合→取れない？？？
			if(preg_match("/https:.+$/",$page_url, $matches)==1){
				array_push($err_tmp,"SSLのページでは情報を取得できません。");
			}else{
				//タイトル取得
				if($get_title = @file_get_contents($page_url)){
					mb_language('Japanese');	//文字化け防止用
					$get_title = mb_convert_encoding($get_title, "UTF-8", "auto" );  
					if ( preg_match( "/<title>(.*?)<\/title>/i", $get_title, $matches) ) {  
						$disp_title = $matches[1];
					} else {
						array_push($err_tmp,"タイトルの取得に失敗しました。");
					}
				}else{
					array_push($err_tmp,"タイトルの取得に失敗しました。");
				}

				//メタディスクリプション取得
				if($disp_meta_des==""){
					$arr_meta = @get_meta_tags($page_url);
					if($arr_meta==false){
						array_push($err_tmp,"メタディスクリプションの取得に失敗しました。");
					}else if($arr_meta["description"]==""){
						array_push($err_tmp,"メタディスクリプションが見つかりません。");
					}else{
						$disp_meta_des = $arr_meta["description"];
					}
				}
			}
		}

		//エラーメッセージ取得
		$err_msg = join($err_tmp,'<br />');

		//出力
		$html  = '';
		$html .= "\n" . '<div id="' . hpbseoClass::input_prefix . 'disp_image">';
		$html .= "\n" . '<p class="' . hpbseoClass::input_prefix . 'boxtitle">表示イメージ</p>';

		if($err_msg == ''){

			//表示文字数の調整
			if(strlen($disp_title) > DISPIMAGE_MAX_TITLE){
				$disp_title = mb_strimwidth($disp_title, 0, DISPIMAGE_MAX_TITLE,'','UTF-8') . '...';
			}
			$page_url = str_replace('http://','',$page_url);	// http://を削除（httpsはそのまま）
			if(strlen($page_url) > DISPIMAGE_MAX_URL){
				$page_url = mb_strimwidth($page_url, 0, DISPIMAGE_MAX_URL,'','UTF-8') . '...';
			}
			if(strlen($disp_meta_des) > DISPIMAGE_MAX_META_DES){
				$disp_meta_des = mb_strimwidth($disp_meta_des, 0, DISPIMAGE_MAX_META_DES,'','UTF-8') . '...';
			}

			//エスケープ
			$disp_meta_des = htmlspecialchars($disp_meta_des);

			$html .= "\n" . '<p class="' . hpbseoClass::input_prefix . 'title">'    . $disp_title    . '</p>';
			$html .= "\n" . '<p class="' . hpbseoClass::input_prefix . 'url">'      . $page_url      . '</p>';
			$html .= "\n" . '<p class="' . hpbseoClass::input_prefix . 'meta_des">' . $disp_meta_des . '</p>';
		}else{
			$html .= "\n" . '<p class="' . hpbseoClass::input_prefix . 'err">' . $err_msg . '</p>';
		}

		$html .= "\n" . '</div>';
		echo $html;

	}


	//プラグイン競合チェック
	static function fncConflictCheck(){

		//競合プラグインリスト取得（※文字列のためsplit）
		$conflict_list = explode(',',CNFLICT_LIST);

		//有効化されているプラグイン（パス）を取得
		$active_plugins = get_option('active_plugins');
		$conflict_flg = false;

		//有効化プラグイン分ループ
		for($i=0;$i<count($active_plugins);$i++){
			//競合プラグインリストとの比較
			for($j=0;$j<count($conflict_list);$j++){
				$tmp=preg_match("/" .$conflict_list[$j]. "/",$active_plugins[$i]);
				if($tmp===1){
					$conflict_flg = true;
					break 2;
				}
			}

		}

		return $conflict_flg;
	}

}

/**----------------------------------------------------------------------------
 * 保存処理用コールバック関数
 * --------------------------------------------------------------------------*/
function fncHpbSeo_MetaUpdate($post_id){

	//nonceが正しく設定されている＆制限時間内かどうか（外部からのアクセスでないかチェック？）
//	if (!wp_verify_nonce( $_POST['my_meta_nonce'], 'example_meta')) {
//		return $post_id;
//	}

	//※nonceの値が取得できない場合は終了
	if( !$_POST[hpbseoClass::input_prefix . 'nonce_field']){
		return $post_id;
	}

	if ( !empty($_POST) && check_admin_referer( hpbseoClass::input_prefix . 'nonce_action', hpbseoClass::input_prefix . 'nonce_field' ) ) {
		//データ更新の処理（update_optionなど）
	}else{
		return $post_id;
	}

	//自動保存の場合は何もしない
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}

	//カスタム投稿タイプの一覧取得
	$post_type_list = fncHpbSeo_getCustomSlug();

	//ユーザーの権限チェック
	if ('post' == $_POST['post_type'] || in_array($_POST['post_type'] ,$post_type_list)) {
		if(!current_user_can('edit_post', $post_id)) {
			return $post_id;
		}
	} else {
		return $post_id;
	}

	//保存（タイトル・メタ情報）
	$meta_des = trim($_POST[hpbseoClass::input_prefix . 'meta_des']);
	$meta_key = trim($_POST[hpbseoClass::input_prefix . 'meta_key']);
	$meta_des_add_flg = $_POST[hpbseoClass::input_prefix . 'meta_des_add_flg'];
	$meta_key_add_flg = $_POST[hpbseoClass::input_prefix . 'meta_key_add_flg'];

	//改行コード削除
	$meta_des = str_replace(array("\r\n","\r","\n"), '', $meta_des);

	//※正規表現にエスケープ（ . \ + * ? [ ^ ] $ ( ) { } = ! < > | : -）
	$meta_des = preg_quote ($meta_des);
	$meta_key = preg_quote ($meta_key);

	if($meta_des=='' && $meta_key=='' & $meta_des_add_flg==0 && $meta_key_add_flg==0){
		//全て未入力の場合は削除
		delete_post_meta($post_id, hpbseoClass::field_prefix . 'meta' );
	}else{
		//保存
		$save_arr = array(
			 hpbseoClass::field_obj_prefix . 'meta_des' => $meta_des
			,hpbseoClass::field_obj_prefix . 'meta_key' => $meta_key
			,hpbseoClass::field_obj_prefix . 'meta_des_add_flg' => $meta_des_add_flg
			,hpbseoClass::field_obj_prefix . 'meta_key_add_flg' => $meta_key_add_flg
		);
		update_post_meta($post_id, hpbseoClass::field_prefix . 'meta' , $save_arr);
	}


}


/**----------------------------------------------------------------------------
* 投稿ページが開かれたとき
 * --------------------------------------------------------------------------*/
class clsHpbSeo_ReplaceMeta {
	function __construct() {
	}

	//DBから投稿記事を読み込んだ内容を「引数：$content」でうけとる。
	function replaceMeta() {
		global $post;

		//一括設定値の取得
		$global_setting  = get_option(hpbseoClass::field_prefix . 'global_setting');
		$global_meta_des = $global_setting[hpbseoClass::field_obj_prefix . 'global_meta_des'];
		$global_meta_key = $global_setting[hpbseoClass::field_obj_prefix . 'global_meta_key'];

		//個別設定値の取得
		$meta  = get_post_meta($post->ID, hpbseoClass::field_prefix . 'meta', true);
		if($meta){
			$meta_des = $meta[hpbseoClass::field_obj_prefix . 'meta_des'] ;
			$meta_key = $meta[hpbseoClass::field_obj_prefix . 'meta_key'] ;
			$meta_des_add_flg = $meta[hpbseoClass::field_obj_prefix . 'meta_des_add_flg'] ;
			$meta_key_add_flg = $meta[hpbseoClass::field_obj_prefix . 'meta_key_add_flg'] ;
		}else{
			$meta_des = '';
			$meta_key = '';
			$meta_des_add_flg = 0;
			$meta_key_add_flg = 0;
		}

		//メタディスクリプション
		if($meta_des==""){
			$meta_des = $global_meta_des;
		}else if($meta_des_add_flg == 1){
			$meta_des = $meta_des . $global_meta_des;
		}else{
			//設定なし
		}
		if($meta_des!=""){
			$meta_des = fncHpbSeo_unescape($meta_des);
			$meta_des_tag = sprintf( "<meta name=\"description\" content=\"%s\" />\n", $meta_des );
		}

		//メタキーワード
		if($meta_key==""){
			$meta_key = $global_meta_key;
		}else if($meta_key_add_flg == 1 && $global_meta_key != ""){
			$meta_key = $meta_key . ',' . $global_meta_key;
		}else{
			//設定なし
		}
		if($meta_key!=""){
			$meta_key = fncHpbSeo_unescape($meta_key);
			$meta_key_tag = sprintf( "<meta name=\"keywords\" content=\"%s\" />\n", $meta_key );
		}

		//表示
		if($meta_des_tag!="" || $meta_key_tag!=""){
			echo "<!-- hpb SEO - start -->\n";
			echo $meta_des_tag;
			echo $meta_key_tag;
			echo "<!-- hpb SEO - end   -->\n";
		}

	}
}



/**----------------------------------------------------------------------------
 * 設定メニュー
 * --------------------------------------------------------------------------*/
class clsHpbSeo_AdminMenu {
	function __construct() {
		//hpb環境チェック
		if(fncHpbSeo_hpbCheck()){
			$this->add_pages();
		}else{
			$this->add_pages_err();
		}
	}

	function add_pages() {
		//メニュー追加
		add_menu_page(
			"hpb SEO設定", 
			"hpb SEO設定", 
			'administrator',
			__FILE__, 
			array($this, 'fncAdminMenu'),
			PLUGIN_IMG_URL . IMG_MENU,
			'3.1'	//表示位置（hpbダッシュボード＝3）
		);
	}

	function add_pages_err() {
		//メニュー追加
		add_menu_page(
			"hpb SEO設定", 
			"hpb SEO設定", 
			'administrator',
			__FILE__, 
			array($this, 'fncAdminMenuErr'),
			PLUGIN_IMG_URL . IMG_MENU,
			'3.1'	//表示位置（hpbダッシュボード＝3）
		);
	}

	function fncAdminMenuErr() {
		$html  = '';
		$html .= "\n" . '<div class="wrap">';
		$html .= "\n" . '<h2 class="' . hpbseoClass::input_prefix . 'optionmenu_title"><img src="' . PLUGIN_IMG_URL . IMG_ICON . '" class="' . hpbseoClass::input_prefix . 'optionmenu_icon">hpb SEOプラグイン オプション設定</h2>';
		$html .= "\n" . '</div>';
		$html .= "\n" . '<br />';
		$html .= "\n" . '<div class="' . hpbseoClass::input_prefix . 'hpb_err_msg">';
		$html .= "\n" . '「hpb SEOプラグイン」を使用するには、「hpbダッシュボード」を有効化し、hpbのテーマファイルを使用してください。<br /><br />';
		$html .= "\n" . '<a href="' .HELP_URL. '" target="blank">hpb SEOプラグインの使い方&nbsp;&gt;&gt;</a>';
		$html .= "\n" . '</div>';
		print($html);
	}


	function fncAdminMenu() {
		global $post,$wpdb;

		// タイトル部のHTML
		$html  = '';
		$html .= "\n" . '<div class="wrap">';
		$html .= "\n" . '<h2 class="' . hpbseoClass::input_prefix . 'optionmenu_title"><img src="' . PLUGIN_IMG_URL . IMG_ICON . '" class="' . hpbseoClass::input_prefix . 'optionmenu_icon" />hpb SEOプラグイン オプション設定</h2>';
		$html .= "\n" . '<p>';
		$html .= "\n" . 'サイトの全ページに共通で挿入されるメタディスクリプション・メタキーワードの設定を行います。<br />';
		$html .= "\n" . '<a href="' .HELP_URL. '" target="blank">hpb SEOプラグインの使い方&nbsp;&gt;&gt;</a>';
		$html .= "\n" . '</p>';
		$html .= "\n" . '</div>';
		print($html);

		//postがある場合は更新
		if($_POST){

			//値の取得
			$global_meta_des   = trim($_POST[hpbseoClass::field_obj_prefix . 'global_meta_des']);
			$global_meta_key   = trim($_POST[hpbseoClass::field_obj_prefix . 'global_meta_key']);
			$dispimage_flg     = $_POST[hpbseoClass::field_obj_prefix . 'dispimage_flg'];
			$dispimage_opt     = $_POST[hpbseoClass::field_obj_prefix . 'dispimage_opt'];
			$dispimage_opt_sub = $_POST[hpbseoClass::field_obj_prefix . 'dispimage_opt_sub'];
			//ラジオボタンが非表示状態の場合はhiddenから値を取得
			if($dispimage_opt == null){
				$dispimage_opt = $dispimage_opt_sub;
			}

			//改行コード削除
			$global_meta_des = str_replace(array("\r\n","\r","\n"), '', $global_meta_des);

			//保存（wp_options）
			$save_arr = array(
				 hpbseoClass::field_obj_prefix . 'global_meta_des' => $global_meta_des
				,hpbseoClass::field_obj_prefix . 'global_meta_key' => $global_meta_key
				,hpbseoClass::field_obj_prefix . 'dispimage_flg'   => $dispimage_flg
				,hpbseoClass::field_obj_prefix . 'dispimage_opt'   => $dispimage_opt
			);
			update_option(hpbseoClass::field_prefix . 'global_setting' , $save_arr);

			//更新完了メッセージ
			print("\n" . '<div class="hpbseo_option_update_msg"><p>設定を保存しました。</p></div>');

		}else{
			//値の取得
			$global_setting  = get_option(hpbseoClass::field_prefix . 'global_setting');
			$global_meta_des = $global_setting[hpbseoClass::field_obj_prefix . 'global_meta_des'];
			$global_meta_key = $global_setting[hpbseoClass::field_obj_prefix . 'global_meta_key'];
			$dispimage_flg   = $global_setting[hpbseoClass::field_obj_prefix . 'dispimage_flg'];
			$dispimage_opt   = $global_setting[hpbseoClass::field_obj_prefix . 'dispimage_opt'];
		}

		//アンエスケープ
		$global_meta_des = fncHpbSeo_unescape($global_meta_des);
		$global_meta_key = fncHpbSeo_unescape($global_meta_key);

		// フォーム部のHTML
		$html  = '';
		$html .= "\n" . '<form action="" method="post" id="' . hpbseoClass::input_prefix . 'adminform">';
		$html .= "\n" . '<div class="' . hpbseoClass::input_prefix . 'optionmenu_sub_title">メタディスクリプション</div>';
		$html .= "\n" . '<div class="' . hpbseoClass::input_prefix . 'optionmenu_sub_caption">';
		$html .= "\n" . 'ここで設定したメタディスクリプションは、全ページに共通で挿入されます。<br />';
		$html .= "\n" . '</div>';
		$html .= "\n" . '<textarea cols="50" rows="3" id="' . hpbseoClass::input_prefix . 'global_meta_des" name="' . hpbseoClass::input_prefix . 'global_meta_des" >' . $global_meta_des . '</textarea>';
		$html .= "\n" . '<div class="' . hpbseoClass::input_prefix . 'optionmenu_sub_title">メタキーワード</div>';
		$html .= "\n" . '<div class="' . hpbseoClass::input_prefix . 'optionmenu_sub_caption">';
		$html .= "\n" . 'ここで設定したメタキーワードは、全ページ共通で挿入されます。半角コンマ区切りで設定してください。';
		$html .= "\n" . '<br />（例）キーワード1,キーワード2,キーワード3';
		$html .= "\n" . '</div>';
		$html .= "\n" . '<input type="text" id="' . hpbseoClass::input_prefix . 'global_meta_key" name="' . hpbseoClass::input_prefix . 'global_meta_key" value="' .$global_meta_key. '" />';
		$html .= "\n" . '<br />';

		$html .= "\n" . '<div class="' . hpbseoClass::input_prefix . 'optionmenu_sub_title">検索結果表示イメージ</div>';
		$html .= "\n" . '<div class="' . hpbseoClass::input_prefix . 'optionmenu_sub_caption">';
		$html .= "\n" . '編集画面で検索結果のイメージを表示するかを選択することができます。';
		$html .= "\n" . '</div>';
		$html .= "\n" . '<div id="' . hpbseoClass::input_prefix . 'dispimage_div">';
		$html .= "\n" . '<input type="hidden" name="' . hpbseoClass::input_prefix . 'dispimage_flg" value="0" />';	//null回避用
		$html .= "\n" . '<input type="checkbox" id="' . hpbseoClass::input_prefix . 'dispimage_flg" name="' . hpbseoClass::input_prefix . 'dispimage_flg" value="1" ';
		if($dispimage_flg==1){
			$html .= ' checked="checked" ';
		}
		$html .= ' />「表示イメージ」を表示する<br />';
		$html .= "\n" . '<input type="radio" name="' . hpbseoClass::input_prefix . 'dispimage_opt" class="' . hpbseoClass::input_prefix . 'dispimage_opt" value="local" ';
		if($dispimage_opt=='local'){
			$html .= ' checked="checked" ';
		}
		$html .= ' />local: ブログ内から取得<br />';
		$html .= "\n" . '<input type="radio" name="' . hpbseoClass::input_prefix . 'dispimage_opt" class="' . hpbseoClass::input_prefix . 'dispimage_opt" value="http" ';
		if($dispimage_opt=='http'){
			$html .= ' checked="checked" ';
		}
		$html .= ' />http: 公開画面から取得（通信に時間がかかる場合があります）<br />';
		$html .= "\n" . '<input type="hidden" id="' . hpbseoClass::input_prefix . 'dispimage_opt_sub" name="' . hpbseoClass::input_prefix . 'dispimage_opt_sub" value="' .$dispimage_opt. '" />';	//null回避用
		$html .= "\n" . '</div>';

		$html .= "\n" . '<input type="submit" id="' . hpbseoClass::input_prefix . 'update_admin" class="button-primary" value="設定を保存する">';
		$html .= "\n" . '</form>';

		//プラグイン競合チェック
		if(clsHpbSeo_HeadTune::fncConflictCheck()){
			$html .= "\n" . '<p id="' . hpbseoClass::input_prefix . 'conflict_message">プラグインが競合する可能性があります。</p>';
		}

		//表示
		print($html);
	}
}




/* ======================================
 * 呼び出し用
 * ====================================== */
//コンテンツ分析
function callHpbSeo_ContentTune() {
	new clsHpbSeo_ContentTune();
}
//タイトル・メタ設定
function callHpbSeo_HeadTune() {
	new clsHpbSeo_HeadTune();
}

//メタ情報置換
function callHpbSeo_ReplaceMeta() {
	$cls = new clsHpbSeo_ReplaceMeta();
	return $cls->replaceMeta();
}

//設定画面
function callHpbSeo_AdminMenu() {
	new clsHpbSeo_AdminMenu();
}





/* ======================================
 * フック一覧
 * ====================================== */
//有効化
register_activation_hook(__FILE__   , 'fncHpbSeo_ActivationHook');
//css読み込み（管理画面）
add_action('admin_print_styles'     , 'fncHpbSeo_IncludeCSS');
//管理画面
add_action('admin_menu', 'callHpbSeo_AdminMenu');

//hpbの環境が整っている場合のみフック
if(fncHpbSeo_hpbCheck()){
	//js読み込み（管理画面）
	add_action('admin_enqueue_scripts'  , 'fncHpbSeo_IncludeAdminJS');

	//タイトル・メタ設定
	add_action('admin_head-post-new.php', 'callHpbSeo_HeadTune');	//新規
	add_action('admin_head-post.php'    , 'callHpbSeo_HeadTune');	//編集
	//コンテンツ分析
	add_action('admin_head-post-new.php', 'callHpbSeo_ContentTune');	//新規
	add_action('admin_head-post.php'    , 'callHpbSeo_ContentTune');	//編集
	//「更新」ボタン押下
	add_action('save_post', 'fncHpbSeo_MetaUpdate');

	//メタ情報置換
	add_filter('wp_head', 'callHpbSeo_ReplaceMeta',100);	//※第3引数＝優先度（デフォルト＝10）

}

?>
