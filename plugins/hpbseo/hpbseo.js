//IEではconstが動かない？
id_prefix   = "#hpbseo_";
cls_prefix  = "hpbseo_";
name_prefix = "hpbseo_";
cls_defaulttext = "hpbseo_defaulttext";
ajax_php_file   = "postajax.php";
lint_file       = "hpbseo.txt";
category_php_file = "";
graph_row_max   = 10;	//グラフ表示件数
main_theme_max  = 3;	//メインテーマ表示単語数
uninputted_msg_body     = "記事本文を入力してください。";
uninputted_msg_meta_des = "メタディスクリプションを入力してください。";
uninputted_msg_meta_key = "メタキーワードを入力してください。";
titlebox_under_msg      = "※メインテーマのワードを含めた文章にしてください。<br />※重要なキーワードは、前方に配置してください。<br />※魅力的な文章は、検索結果でクリック率が高まります。";
//lint_error_msg          = "閾値の取得に失敗しました。";
lint_mid_msg            = "最適化されています。";

/*--------------------------------------------------------------------
 * 文字数カウント
--------------------------------------------------------------------*/
function fncStrCount(str){

//	//エディター判別
//	str = fncGetBodyStr(str);

	//タグ・改行除去
	var remove_tag = fncRemoveTag(str);

	//アンエスケープ
	function unescapeHTML(val){ return jQuery('<div>').html(val).text(); };
	remove_tag = unescapeHTML(remove_tag);

	//文字数取得
	var len =remove_tag.length;
	//文字数を返す
	return len;
}


/*--------------------------------------------------------------------
 * タグ・改行
--------------------------------------------------------------------*/
function fncRemoveTag(str){
	//タグ・改行除去
	var remove_tag = str.trim();
	remove_tag = str.trim();
	remove_tag = remove_tag.replace(/<\/?[^>]+>/gi, "");
	remove_tag = remove_tag.replace(/\r\n/gi, "");
	remove_tag = remove_tag.replace(/\r/gi, "");
	remove_tag = remove_tag.replace(/\n/gi, "");
	remove_tag = remove_tag.replace(/ /gi, "");	//スペース削除

	return remove_tag;
}

//IE7.0以下trim対応
if(typeof String.prototype.trim !== 'function') {
	String.prototype.trim = function() {
		return this.replace(/^\s+|\s+$/g, '');
	}
}

/*--------------------------------------------------------------------
 * 記事本文取得（エディター判別/記事本文を返す）
--------------------------------------------------------------------*/
function fncGetBodyStr(str){

	//※ビジュアルエディタ使用時※
	if (jQuery('#wp-content-wrap').hasClass('tmce-active') && tinyMCE.get("content") != undefined) {
		var mstDom = tinyMCE.get('content');
		str = mstDom.serializer.serialize(mstDom.getBody());	//タグ有／改行なし
//	}else{
//		//アンエスケープ
//		function unescapeHTML(val){ return jQuery('<div>').html(val).text(); };
//		str = unescapeHTML(str);
	} else if (jQuery('.block-editor-block-list__layout').text() != undefined && jQuery('.block-editor-block-list__layout').text() != "") {
		str = jQuery('.block-editor-block-list__layout').text(); //タグ有／改行なし 2020/03
	} else if (jQuery('.block-editor-post-text-editor').text() != undefined && jQuery('.block-editor-post-text-editor').text() != "") {
		str = jQuery('.block-editor-post-text-editor').text(); //タグ有／改行なし 2020/03
	}

	if(str==undefined) str='';

	str = str.replace('ブロックを選択するには「/」を入力', '');

	return str;
}


/*--------------------------------------------------------------------
 * 閾値比較（記事本文の文字数）
--------------------------------------------------------------------*/
function fncBodyCount(e){
	var str      = e.data.str.val();
	var disp_len = e.data.disp_len;
	var disp     = e.data.disp;
	var lint     = e.data.lint;

	//本文取得（エディターチェック）
	str = fncGetBodyStr(str);

	//文字数取得
	var len = fncStrCount(str);

	//文字数表示
	disp_len.text(len);
	//空値の場合
	if(len==0){
		disp.text(uninputted_msg_body);
		disp.addClass(cls_prefix + "arrow_box_top");
		return;
	}
	//アラート文取得/表示
	fncLintCheck(lint,len,disp, cls_prefix + "arrow_box_top");

}

/*--------------------------------------------------------------------
 * 閾値比較（メタディスクリプション）
--------------------------------------------------------------------*/
function fncMetaDesCount(e){
	//引数取得
	var str     = e.data.str.val();
	var disp    = e.data.disp;
	var lint    = e.data.lint;
	var g_str   = e.data.g_str.val();
	var g_flg   = e.data.g_flg;
	var prev    = e.data.prev;
	var msg     = e.data.msg;
	var len     = 0;

	//改行削除
	str = str.replace(/\r\n/gi, "");
	str = str.replace(/\r/gi, "");
	str = str.replace(/\n/gi, "");

	//一括設定の値を使う→文字数に追加
	if(g_flg.attr('checked') && !g_flg.attr('disabled')){
		len += g_str.length;
	}else{
		g_str = '';
	}

	//文字数取得（入力表示回避）
	if(str!=msg && str!=""){
		len += str.length;
	}else{
		str = '';
	}

	//空値の場合
	if(len==0){
		disp.text("");
		disp.removeClass(cls_prefix + "arrow_box_left");
		prev.text("");
		return;
	}
	//アラート文取得/表示
	fncLintCheck(lint,len,disp,cls_prefix + "arrow_box_left");
	//プレビュー表示
	prev.text( str + g_str );

}

/*--------------------------------------------------------------------
 * 閾値比較（メタキーワード単語数）
--------------------------------------------------------------------*/
function fncMetaKeyCount(e){
	//引数取得
	var str     = e.data.str.val();
	var disp    = e.data.disp;
	var lint    = e.data.lint;
	var g_str   = e.data.g_str.val();
	var g_flg   = e.data.g_flg;
	var prev    = e.data.prev;
	var msg     = e.data.msg;
	var len     = 0;

	var arr      = new Array();
	var join_str = "";

	//文字数取得（入力表示回避）
	if(str!=msg && str!=""){
		arr.push(str);
	}

	//一括設定の値を使う
	if(g_flg.attr('checked') && !g_flg.attr('disabled') && g_str!=""){
		arr.push(g_str);
	}

	//カンマ区切りで結合
	join_str = arr.join(',');

	//空値の場合
	if(join_str==""){
		disp.text("");
		disp.removeClass(cls_prefix + "arrow_box_left");
		prev.text("");
		return;
	}
	//単語数取得
	var arrMetaKey = join_str.split(",");
	var len        = arrMetaKey.length;
	if(len == 1 && arrMetaKey[0] == ""){
		len = 0;
	}

	//アラート文取得/表示
	fncLintCheck(lint,len,disp,cls_prefix + "arrow_box_left");

	//プレビュー表示
	prev.text( join_str );

}


/*--------------------------------------------------------------------
 * 入力欄のデフォルトテキスト表示 IN
--------------------------------------------------------------------*/
function fncTextBoxFocusIn(e){
	var input = e.data.input;	//入力欄
	var msg   = e.data.msg;		//デフォルトメッセージ

	//デフォルトメッセージが表示されている場合
	if(input.val()==msg){
		input.val('');
        input.removeClass(cls_defaulttext);
	}
}
/*--------------------------------------------------------------------
 * 入力欄のデフォルトテキスト表示 OUT
--------------------------------------------------------------------*/
function fncTextBoxFocusOut(e){
	var input = e.data.input;	//入力欄
	var msg   = e.data.msg;		//デフォルトメッセージ

	//入力値が空の場合
	if(input.val()==''){
		input.val(msg);
		input.addClass(cls_defaulttext);
	}
}


/*--------------------------------------------------------------------
 * オプション設定―表示イメージON/OFF
--------------------------------------------------------------------*/
function fncDispImageSetting(e){
	var flg = e.data.flg;
	var opt = e.data.opt;
	var div = e.data.div;
	var off_css = cls_prefix + "dispimage_div_off";
	
	//表示切替
	if(flg.attr('checked')){
		//表示ON
		opt.removeAttr("disabled");
        div.removeClass(off_css);
	}else{
		//表示OFF
		opt.attr('disabled',"disabled");
		div.addClass(off_css);
		
	}
}


/*--------------------------------------------------------------------
 * 閾値
--------------------------------------------------------------------*/
function fncLintCheck(lint,val,disp,cls){
	var msg     = "";
	var arrLint = lint["params"].split(',');
	var check_img_url = plugin_img_url + "check.png";
	var icon    = "";
//	var ok_flg  = false;

	//アラート文作成
	if( val < arrLint[0] ){
		msg = lint["alertLow"];
	}else if( arrLint[0] <= val && val < arrLint[1] ){
		msg = lint["alertMidLow"];
	}else if( arrLint[1] <= val && val <= arrLint[2] ){
//		ok_flg = true;
		msg = lint["alertMid"];
		//空の場合はメッセージ補完
		if(msg==""){
			msg  = lint_mid_msg;
			icon = '<img src="' + check_img_url + '" class="' + cls_prefix + 'lint_img">';
		}
	}else if( arrLint[2] <  val && val <= arrLint[3] ){
		msg = lint["alertMidHigh"];
	}else if( arrLint[3] <  val ){
		msg = lint["alertHigh"];
	}

	//空値の場合
	if(msg==""){
		disp.text("");
		disp.removeClass(cls);
		disp.removeClass(cls + "_ok");
		return;
	}

	//「%value%」を置き換える
	msg = msg.replace("%value%",val);
	//表示
	disp.empty()
	disp.append(icon + msg);
//	disp.text(msg);
	disp.addClass(cls);

//	if(ok_flg){
//		disp.addClass(cls + "_ok");
//	}else{
//		disp.removeClass(cls + "_ok");
//	}

	return;
}


/*--------------------------------------------------------------------
 * 記事投稿時の未入力メッセージチェック
--------------------------------------------------------------------*/
function fncSubmitCheck(e){
	var meta_des = e.data.meta_des;
	var meta_key = e.data.meta_key;

	//未入力メッセージ表示時は値を空にする
	if(meta_des.val()==uninputted_msg_meta_des){
		meta_des.val('');
	}
	if(meta_key.val()==uninputted_msg_meta_key){
		meta_key.val('');
	}
}


/*--------------------------------------------------------------------
 * 一括設定呼び出しボタン押下
--------------------------------------------------------------------*/
function fncGlobalSet(e){
	var input = e.data.input;
	var str   = e.data.str.val();
	var msg   = e.data.msg;
	var fnc   = e.data.fnc;

	//入力値あるとき
	if(input.val()!='' && input.val()!=msg){
		//確認ダイアログ表示
		flg = window.confirm('入力されている文字列を上書きしますか？');
		// キャンセルの場合は終了
		if(!flg){
			return;
		}
	}

	//表示
	input.val(str);
	//入力中表示
	input.removeClass(cls_defaulttext);
	//フォーカス移動
	input.focus();

	//閾値チェック
	fnc(e);

}


/*--------------------------------------------------------------------
 * カテゴリサービス登録状況
--------------------------------------------------------------------*/
function fncCategoryCheck(e){
	var post_url  = e.data.view_post_url.val();
	var cat_value = e.data.category_value;
	var cat_cnt   = e.data.category_cnt;
	var comment   = e.data.category_comment;

	//ajax用url
	var ajax_url = plugin_url + category_php_file;
	//引数
	var data = {'url':post_url};

	//カテゴリサービス登録状況取得
	jQuery.ajax({
		type: "POST",
		url: ajax_url,
		data: data,
		traditional: true,
		dataType: "json",
		scriptCharset:"UTF-8",
		success: function(json){
			if(json.status["cod"]!="success"){
				cat_value.text("");
				cat_cnt.text("カテゴリサービス登録状況の取得に失敗しました。");
				comment.text("取得失敗");
			}else if(json.result["cnt"] == 0){
				cat_value.text(json.result["cnt"] + "/" +json.result["all"]);
				cat_cnt.text("現在、ディレクトリサービスへの登録がありません。");
				comment.text("登録状況");
			}else{
				cat_value.text(json.result["cnt"] + "/" +json.result["all"]);
				cat_cnt.text("現在 " + json.result["cnt"] + " つのディレクトリサービスに対して登録がされております。");
				comment.text("登録状況");
			}
		},
		error: function(xhr, status, XMLHttpRequest, textStatus, errorThrown){
			//取得失敗
			cat_value.text("");
			cat_cnt.text("カテゴリサービス登録状況の取得に失敗しました。");
			comment.text("取得失敗");
		}
	});

}


/*--------------------------------------------------------------------
 * メインテーマ＆構成ワード
--------------------------------------------------------------------*/
function fncContentTune(e){
	var str          = e.data.str.val();
	var lint         = e.data.lint;
	var lint_content = e.data.lint_content.val();
	var disp_len     = e.data.disp_len;
	var disp         = e.data.disp;
	var disp_content_tune = e.data.disp_content_tune;
	var content_tune_wrap = e.data.content_tune_wrap;
	var loading           = e.data.loading;

	//本文取得（エディターチェック）
	str = fncGetBodyStr(str);
	//タグ削除
	var str_cnt = fncRemoveTag(str);
	//文字数取得
	var len = fncStrCount(str_cnt);

	//文字数表示
	disp_len.text(len);
	//空値の場合
	if(len==0){
		disp.text(uninputted_msg_body);
		disp.addClass(cls_prefix + "arrow_box_top");
	}else{
		//アラート文取得/表示
		fncLintCheck(lint,len,disp, cls_prefix + "arrow_box_top");
	}

	//文字数チェック
	if(str_cnt.length < lint_content){
		//グラフ表示（サンプルデータ）
		fncContentTune_Disp(e);
		loading.removeClass(cls_prefix + "display");
		loading.addClass(cls_prefix + "display_none");
		loading.height("auto");
		return;
	}

//	//タグ削除（※改行はそのまま）→php側で
//	var remove_tag = str.replace(/<\/?[^>]+>/gi, "");
	var remove_tag = str;

	//改行コード調整（※IE対応）
	remove_tag = remove_tag.replace(/\n/gi, "\r\n");

	//引数
	var data = {'src':remove_tag};
	//ajax用url
	var get_words_url =plugin_url + ajax_php_file;

	//loding画像表示
	var content_height = content_tune_wrap.height();
	var loading_height = loading.height();
	if(content_height < loading_height){
		content_tune_wrap.height(loading_height);
	}else{
		loading.height(content_height);
	}
	loading.removeClass(cls_prefix + "display_none");
	loading.addClass(cls_prefix + "display");

	//形態素解析
	jQuery.ajax({
		type: "POST",
		url: get_words_url,
		data: data,
		traditional: true,
		dataType: "json",
		//async: false,
		beforeSend : function( xhr ){
			xhr.setRequestHeader("If-Modified-Since", "Thu, 01 Jun 1970 00:00:00 GMT");
		},
		success: function(json){
			//グラフ表示
			fncContentTune_Disp(e,json);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			var err_str = "";
			err_str += "XMLHttpRequest : " + XMLHttpRequest.status;
			err_str += "textStatus : " + textStatus;
			err_str += "errorThrown : " + errorThrown.message;

			alert("構成ワードの取得に失敗しました。");
			disp_content_tune.removeClass(cls_prefix + "display");
			disp_content_tune.addClass(cls_prefix + "display_none");
		},
		complete: function(data){
			loading.removeClass(cls_prefix + "display");
			loading.addClass(cls_prefix + "display_none");
			loading.height("auto");
		}

	});

}


/*--------------------------------------------------------------------
 * メインテーマ＆構成ワード表示
--------------------------------------------------------------------*/
function fncContentTune_Disp(e,json){
	var str          = e.data.str.val();
	var lint         = e.data.lint;
	var lint_content = e.data.lint_content.val();
	var disp_len     = e.data.disp_len;
	var disp         = e.data.disp;

	var disp_main         = e.data.disp_main_theme;
	var disp_graph        = e.data.disp_graph;
	var disp_content_tune = e.data.disp_content_tune;
	var main_theme_alert  = e.data.main_theme_alert;
	var composition_alert = e.data.composition_alert;
	var content_tune_wrap = e.data.content_tune_wrap;

	var graph_img     = plugin_img_url + "graph.gif";		//グラフ画像
	var graph_img_off = plugin_img_url + "graph_off.gif";	//グラフ画像

	var main_theme_tag = "";
	var graph_tag      = "";
	var maxcnt         = 0;
	var keywords_total = 0;
	var graph_percent  = 0;
	var i;
	var sampleflg      = false;
	var keywords_list  = new Array();

	if(json === undefined){
		//jsonが渡されていない＝サンプル表示
		sampleflg = true;
		main_theme_msg  = lint_content + '文字以上の記事本文を入力して更新ボタンを押すと、ページのメインテーマが表示されます。';
		composition_msg = lint_content + '文字以上の記事本文を入力して更新ボタンを押すと、ページの構成ワードとグラフが表示されます。';
	}else if(json.result == 0){
		//名詞が0の場合
		sampleflg = true;
		main_theme_msg  = 'ワードが取得できませんでした。';
		composition_msg = 'ワードが取得できませんでした。';
	}

	if(sampleflg==true){
		//サンプルデータ作成
		keywords_list[0]={key:'ブログ'  ,val:10};
		keywords_list[1]={key:'seo'     ,val:8};
		keywords_list[2]={key:'効果'    ,val:6};
		keywords_list[3]={key:'デザイン',val:4};
		keywords_list[4]={key:'無料'    ,val:2};
		main_theme_tag = "ブログ seo 効果";
		maxcnt         = 10;
		keywords_total = 30;
		//表示
		graph_img = graph_img_off;
		disp_main.removeClass(cls_prefix + "display");
		disp_main.addClass(cls_prefix + "display_off");
		disp_graph.removeClass(cls_prefix + "display");
		disp_graph.addClass(cls_prefix + "display_off");

	}else{
		var tmp             = json.keywords;
		var keywords_total  = json.total;

		//ソート用の連想配列に格納
		for(i in tmp ){
		    keywords_list.push({key:i,val:tmp[i]});
		}
//		//降順に並べ替え
//		function largeVal(a,b){ return (a.val < b.val) ? 1 : -1 ; }
//		keywords_list.sort(largeVal);
		//キーワード数の最大値
		maxcnt = keywords_list[0].val;

		//表示
		main_theme_msg  = 'これらのワードの組み合わせで検索される文章構成になっています。';
		composition_msg = 'ユーザーが検索に使用すると思われるキーワードを効果的に織り交ぜてください。';
		disp_main.removeClass(cls_prefix + "display_off");
		disp_main.addClass(cls_prefix + "display");
		disp_graph.removeClass(cls_prefix + "display_off");
		disp_graph.addClass(cls_prefix + "display");
	}

	//グラフ出力
	var graph_tag = "";
	graph_tag += '<table id="' + cls_prefix + 'graph_tbl">';
	for(i=0;i<keywords_list.length;i++){
		//件数チェック
		if(i >= graph_row_max){
			break;
		}else if(i < main_theme_max){
			main_theme_tag +=  ' ' + keywords_list[i].key;
			main_theme_tag = main_theme_tag.replace(/^\s+/, "");
		}
		//グラフの幅
		graph_width = (keywords_list[i].val / maxcnt) * 100;
		//割合
		graph_percent = Math.round(( keywords_list[i].val / keywords_total) * 100);
		//タグ作成
		graph_tag += '<tr>';
		graph_tag += '<td class="no" nowrap>' + (i+1) + '. </td>';
		graph_tag += '<td class="keywords">' + keywords_list[i].key + '</td>';
		graph_tag += '<td class="value" nowrap>' + keywords_list[i].val     + '</td>';
		graph_tag += '<td><img src="' + graph_img + '"height="12px" width="' + graph_width + '%" /></td>';
		graph_tag += '<td class="percent" nowrap>' + graph_percent     + '%</td>';
		graph_tag += '</tr>';
	}
	graph_tag += '</table>';

	content_tune_wrap.height("auto");

	disp_main.empty();
	disp_main.append(main_theme_tag);
	disp_graph.empty();
	disp_graph.append(graph_tag);

	main_theme_alert.empty();
	main_theme_alert.append(main_theme_msg);
	composition_alert.empty();
	composition_alert.append(composition_msg);

	disp_content_tune.removeClass(cls_prefix + "display_none");
	disp_content_tune.addClass(cls_prefix + "display");

	return;
}

/*====================================================================
 * 呼び出し
====================================================================*/
(function($) {
//$(function(){
//競合を避けるために、$ の代わりに jQuery を使う
jQuery(window).on("load",function(){

//	//投稿編集画面
//	if('post' == $('#post_type').val()){

	//画面チェック
	var custom_post_type_list = $(id_prefix + 'custom_post_type_list').val();
	var post_type_list = custom_post_type_list + ',post';
	var this_post_type =  $('#post_type').val();

	if(this_post_type!=null && post_type_list.indexOf(this_post_type)>=0){

		//プラグインフォルダまでのurl
		plugin_url = $(id_prefix + 'plugin_url').val();
		//プラグイン-画像フォルダまでのurl
		plugin_img_url = plugin_url + 'image/';
		//閾値ファイルurl
		var ajaxurl = plugin_url + lint_file;

		//閾値取得
		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			dataType: "json",
			//async: false,
			cache : true,
			beforeSend : function( xhr ){
				xhr.setRequestHeader("If-Modified-Since", "Thu, 01 Jun 1970 00:00:00 GMT");
			},
			success: function(json){

				var titlebox_after_html = '';
				//タイトル下のメッセージ表示
				if($('.editor-post-title__input').length>0){
					// ビジュアルエディタ
					titlebox_after_html = '<div class="' + cls_prefix + 'arrow_box_top ' + cls_prefix + 'title_alert_visual">' + titlebox_under_msg + '</div>';
					$('.editor-post-title__input').after(titlebox_after_html);
				}else{
					// クラシックエディタ
					titlebox_after_html = '<div class="' + cls_prefix + 'arrow_box_top ' + cls_prefix + 'title_alert_clasic">' + titlebox_under_msg + '</div>';
					$('#titlewrap').after(titlebox_after_html);
				}

				//閾値取得
				var scoreTotalSourceNum 		= json["result_data"]["column"]["scoreTotalSourceNum"];
				var scoreMetaDescriptionLength	= json["result_data"]["column"]["scoreMetaDescriptionLength"];
				var scoreMetaVolume				= json["result_data"]["column"]["scoreMetaVolume"];

				//投稿記事文字数カウント
				var strcnt_obj  = {};
				strcnt_obj.data = {};
				strcnt_obj.data.str      = $('#content');
				strcnt_obj.data.disp_len = $(id_prefix + 'content_length');
				strcnt_obj.data.disp     = $(id_prefix + 'title_alert');
				strcnt_obj.data.lint     = scoreTotalSourceNum;
				//初回
//				fncBodyCount(strcnt_obj);

				//メタディスクリプション文字数カウント
				var metades_cnt_obj  = {};
				metades_cnt_obj.data = {};
				metades_cnt_obj.data.str   = $(id_prefix + 'meta_des');
				metades_cnt_obj.data.disp  = $(id_prefix + 'meta_des_alert');
				metades_cnt_obj.data.lint  = scoreMetaDescriptionLength;
				metades_cnt_obj.data.g_str = $(id_prefix + 'global_meta_des');
				metades_cnt_obj.data.g_flg = $(id_prefix + 'meta_des_add_flg');
				metades_cnt_obj.data.prev  = $(id_prefix + 'meta_des_preview');
				metades_cnt_obj.data.msg   = uninputted_msg_meta_des;
				//初回
				fncMetaDesCount(metades_cnt_obj);
				//入力時
				$(id_prefix + "meta_des").bind("click blur keydown keyup keypress change", metades_cnt_obj.data, fncMetaDesCount);
				//チェックボックスクリック時
				$(id_prefix + 'meta_des_add_flg').bind("click",metades_cnt_obj.data,fncMetaDesCount);

				//メタキーワード単語数カウント
				var metakey_cnt_obj  = {};
				metakey_cnt_obj.data = {};
				metakey_cnt_obj.data.str  = $(id_prefix + 'meta_key');
				metakey_cnt_obj.data.disp = $(id_prefix + 'meta_key_alert');
				metakey_cnt_obj.data.lint = scoreMetaVolume;
				metakey_cnt_obj.data.g_str = $(id_prefix + 'global_meta_key');
				metakey_cnt_obj.data.g_flg = $(id_prefix + 'meta_key_add_flg');
				metakey_cnt_obj.data.prev  = $(id_prefix + 'meta_key_preview');
				metakey_cnt_obj.data.msg   = uninputted_msg_meta_key;
				//初回
				fncMetaKeyCount(metakey_cnt_obj);
				//入力時
				$(id_prefix + "meta_key").bind("click blur keydown keyup keypress change", metakey_cnt_obj.data, fncMetaKeyCount);
				//チェックボックスクリック時
				$(id_prefix + 'meta_key_add_flg').bind("click",metakey_cnt_obj.data,fncMetaKeyCount);

				//入力中表示（メタディスクリプション）
				var metades_focus_obj  = {};
				metades_focus_obj.data = {};
				metades_focus_obj.data.input = $(id_prefix + 'meta_des');
				metades_focus_obj.data.msg   = uninputted_msg_meta_des;
				//初回
				fncTextBoxFocusOut(metades_focus_obj);
				//フォーカスIN
				$(id_prefix + 'meta_des').bind("focus",metades_focus_obj.data,fncTextBoxFocusIn);
				//フォーカスOUT
				$(id_prefix + 'meta_des').bind("blur" ,metades_focus_obj.data,fncTextBoxFocusOut);

				//入力中表示（メタキーワード）
				var metakey_focus_obj  = {};
				metakey_focus_obj.data = {};
				metakey_focus_obj.data.input = $(id_prefix + 'meta_key');
				metakey_focus_obj.data.msg   = uninputted_msg_meta_key;
				//初回
				fncTextBoxFocusOut(metakey_focus_obj);
				//フォーカスIN
				$(id_prefix + 'meta_key').bind("focus",metakey_focus_obj.data,fncTextBoxFocusIn);
				//フォーカスOUT
				$(id_prefix + 'meta_key').bind("blur" ,metakey_focus_obj.data,fncTextBoxFocusOut);

				//一括設定値呼び出し（メタディスクリプション）
				var global_meta_des_obj  = {};
				global_meta_des_obj.data = {};
				global_meta_des_obj.data.input = $(id_prefix + 'meta_des');
				global_meta_des_obj.data.str   = $(id_prefix + 'global_meta_des');
				global_meta_des_obj.data.fnc   = fncMetaDesCount;
				global_meta_des_obj.data.disp  = $(id_prefix + 'meta_des_alert');
				global_meta_des_obj.data.lint  = scoreMetaDescriptionLength;
				global_meta_des_obj.data.g_str = $(id_prefix + 'global_meta_des');
				global_meta_des_obj.data.g_flg = $(id_prefix + 'meta_des_add_flg');
				global_meta_des_obj.data.prev  = $(id_prefix + 'meta_des_preview');
				global_meta_des_obj.data.msg   = uninputted_msg_meta_des;
				//ボタン押下時
				$(id_prefix + 'global_set_meta_des').bind("click",global_meta_des_obj.data,fncGlobalSet);

				//一括設定値呼び出し（メタキーワード）
				var global_meta_key_obj  = {};
				global_meta_key_obj.data = {};
				global_meta_key_obj.data.input = $(id_prefix + 'meta_key');
				global_meta_key_obj.data.str   = $(id_prefix + 'global_meta_key');
				global_meta_key_obj.data.fnc   = fncMetaKeyCount;
				global_meta_key_obj.data.disp  = $(id_prefix + 'meta_key_alert');
				global_meta_key_obj.data.lint  = scoreMetaVolume;
				global_meta_key_obj.data.g_str = $(id_prefix + 'global_meta_key');
				global_meta_key_obj.data.g_flg = $(id_prefix + 'meta_key_add_flg');
				global_meta_key_obj.data.prev  = $(id_prefix + 'meta_key_preview');
				global_meta_key_obj.data.msg   = uninputted_msg_meta_key;
				//ボタン押下時
				$(id_prefix + 'global_set_meta_key').bind("click",global_meta_key_obj.data,fncGlobalSet);

				//メインテーマ・構成ワード
				var content_tune_obj   = {};
				content_tune_obj.data  = {};
				content_tune_obj.data.str          = $('#content');
				content_tune_obj.data.disp_len     = $(id_prefix + 'content_length');
				content_tune_obj.data.disp         = $(id_prefix + 'title_alert');
				content_tune_obj.data.lint         = scoreTotalSourceNum;
				content_tune_obj.data.lint_content = $(id_prefix + 'lint_content_tune');
				content_tune_obj.data.disp_main_theme   = $(id_prefix + 'main_theme_word');
				content_tune_obj.data.disp_graph        = $(id_prefix + 'composition_word_graph');
				content_tune_obj.data.disp_content_tune = $(id_prefix + 'content_tune_div');
				content_tune_obj.data.main_theme_alert  = $(id_prefix + 'main_theme_alert');
				content_tune_obj.data.composition_alert = $(id_prefix + 'composition_word_alert');
				content_tune_obj.data.content_tune_wrap = $(id_prefix + 'content_tune_wrap');
				content_tune_obj.data.loading           = $(id_prefix + 'content_tune_loading');

				//初回
				fncContentTune(content_tune_obj);
				//更新ボタンクリック
				$(id_prefix + 'content_tune').bind("click",content_tune_obj.data,fncContentTune);

			},
			error: function(){
				alert("閾値の取得ができませんでした。\n\n.htaccessでLimit POSTを指定していないかなど、\nご利用のサーバーの設定をご確認ください。");
				//各ボタン押下不可にする
				$(id_prefix + 'content_tune').attr("disabled", "disabled");
				$(id_prefix + 'global_set_meta_des').attr("disabled", "disabled");
				$(id_prefix + 'global_set_meta_key').attr("disabled", "disabled");
			}
		});

//		//カテゴリサービス登録状況
//		var category_obj  = {};
//		category_obj.data = {};
//		category_obj.data.view_post_url    = $(id_prefix + 'view_post_url');
//		category_obj.data.category_value   = $(id_prefix + 'category_value');
//		category_obj.data.category_cnt     = $(id_prefix + 'category_cnt');
//		category_obj.data.category_comment = $(id_prefix + 'category_comment');
//		$(window).bind("load",category_obj.data,fncCategoryCheck);

	}

	//オプション設定画面
	//表示イメージon/off（チェックボックス）
	var dispimage_chk_obj  = {};
	dispimage_chk_obj.data = {};
	dispimage_chk_obj.data.flg = $(id_prefix + 'dispimage_flg');
	dispimage_chk_obj.data.opt = $('input:radio[name=' + name_prefix + 'dispimage_opt]');
	dispimage_chk_obj.data.div = $(id_prefix + 'dispimage_div');
	$(window).bind("load",dispimage_chk_obj.data,fncDispImageSetting);
	$(id_prefix + 'dispimage_flg').bind("click",dispimage_chk_obj.data,fncDispImageSetting);

	//表示イメージon/off（ラジオボタン）
	$('input:radio[name=' + name_prefix + 'dispimage_opt]').bind("click",function(){
		var opt_val = $('input:radio[name=' + name_prefix + 'dispimage_opt]:checked').val();
		$(id_prefix + 'dispimage_opt_sub').val( opt_val )
	});

	//記事投稿時の未入力メッセージチェック
	var submit_check_obj  = {};
	submit_check_obj.data = {};
	submit_check_obj.data.meta_des = $(id_prefix + 'meta_des');
	submit_check_obj.data.meta_key = $(id_prefix + 'meta_key');
	$("#post").submit(submit_check_obj.data, fncSubmitCheck);

});
})(jQuery);
