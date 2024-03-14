(function () {
    const CODOC_URL = CODOCEDITOR.codoc_url;
    const CODOC_USER_CODE = CODOCEDITOR.codoc_usercode;
    
    tinymce.create('tinymce.plugins.Codoc', {
        init: function (ed, url) {
            // 表示用にタグを変換
            ed.on( 'BeforeSetContent', function( event ) {
		        if ( event.content ) {
			        if ( event.content.indexOf( '<!-- wp:codoc/codoc-block' ) !== -1 ) {
                        moretext = "codoc";
                        title ="codoc";
				        event.content = event.content.replace( /(<!-- wp:codoc\/codoc-block[\s\S]*<!-- \/wp:codoc\/codoc-block -->)/g, function( match ) {
                            this.codocTag = match;
					        return '<img id="codoc-block-img" src="' + tinymce.Env.transparentSrc + '" data-wp-more="codoc" data-wp-more-text="' + moretext + '" ' +
						        'class="codoc-block" alt="" title="' + title + '" data-mce-resize="false" data-mce-placeholder="1" />';
				        });
			        }
                }});
            // 保存用にタグを変換
	        ed.on( 'PostProcess', function( event ) {
		        if ( event.get ) {
			        event.content = event.content.replace(/<img[^>]+>/g, function( image ) {
                        if ( image.indexOf( 'data-wp-more="codoc"' ) !== -1 ) {
                            return this.codocTag;
			            }
                        return image;
		            });
                }
            });
            ed.addButton('codoc', {
                title: 'codoc記事設定',
                onclick: function () {
                    tb_show('codoc記事設定:', '#TB_inline');
                    
                    jQuery.ajaxSetup({
                        cache: false,
                    });
                    let date = new Date;
                    let ymd = sprintf('%s%s%s',date.getYear(), date.getMonth(),date.getDate());
                    jQuery.getScript("https://codoc.jp/sdk/js/sdk.v1.js?ver=" + ymd, function(data, textStatus, jqxhr) {
                    //jQuery.getScript("https://codoc.jp/sdk/sdk/js/sdk.v1.js?ver=" + ymd, function(data, textStatus, jqxhr) {
                        
                        let body = tinymce.activeEditor.getBody();
                        let util = new CodocUtil({ url: CODOC_URL, user_code: CODOC_USER_CODE, html: (window.codocTag ? window.codocTag : '') });
                        
                        //貼付け時の挙動
                        util.registerSubmitHook(function(me){
                            var editor = tinymce.activeEditor;
                            editor.selection.collapse();
                            // すでにcodocタグがあるかどうか
                            if (jQuery(editor.dom.doc).find('.codoc-block')) {
                                // caret position を現在codocタグの位置に移動
                                editor.selection.select(jQuery(editor.dom.doc).find('.codoc-block')[0]);
                                // ブロックを一旦リセット
                                jQuery(editor.dom.doc).find('.codoc-block').remove();
                            }
                            // ブロックを作成
                            tinymce.activeEditor.execCommand('mceInsertRawHTML', false, me.blockHtml );
                            
                            tb_remove();
                        });
                        // サブスクリプスションを取得
                        util.fetchSubscriptions(function(me,res){
                            // フォームを作成
                            let el = me.createForm('codoc-sdk');
                            me.write(el,document.getElementById('TB_ajaxContent'));
                        });

                    });                  
                }});
        },
        createControl: function (n, cm) {
            return null;
        },
        getInfo: function () {
            return {
                longname: 'codoc Shortcodes',
                author: 'codoc.jp',
                authorurl: 'codoc.jp',
                infourl: '',
                version: '1.0'
            };
        }
    });
    
    tinymce.PluginManager.add('codoc', tinymce.plugins.Codoc);
    
    jQuery(document).on('click', '.return_codoc_shortcode', function () {
        var id = jQuery(this).data('id');
        tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[codoc id="' + id + '"]');
        tb_remove();
    });
    
    jQuery(document).on('click', '.submit-codoc-form', function () {
        // todo
        //alert(CODOCEDITOR.nonce)
        var url = 'admin-ajax.php?action=codoc_shortcodes&height=600&width=600&usercode=' + jQuery('#codoc-usercode')[0].value + '&entrycode=' + jQuery('#codoc-entrycode')[0].value + '&_wpnonce=' + CODOCEDITOR.nonce;
        tb_show('codocの記事一覧:',url);
        jQuery('#TB_window').addClass('codocTB');
    });


})();
