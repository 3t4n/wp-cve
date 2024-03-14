jQuery.noConflict();

var already_pixgridderTinyMCEinit = false;

function pixgridderTinyMCEinit() {
	var DOM = tinymce.DOM;

	tinymce.init;

	tinymce.create('tinymce.plugins.PixGridder', {
		mceTout : 0,

		init : function(ed, url) {
			var t = this;

			ed.on('init',function(e) {
				if ( already_pixgridderTinyMCEinit === false ) {
					already_pixgridderTinyMCEinit = true;
					pixGridderBuilderInit();
				}
			});

			ed.on('PostRender',function() {

				jQuery(window).bind('pix_builder_modal', function(){

					setTimeout(function(){
						var h = ( jQuery('#textarea_builder').height() ) - ( jQuery('#textarea_builder .mce-statusbar').outerHeight() + jQuery('#textarea_builder .mce-toolbar-grp').outerHeight() + jQuery('#textarea_builder .wp-editor-tabs').outerHeight() ),
							h2 = (jQuery('#textarea_builder').height() - (jQuery('#qt_textArea_toolbar').height() + jQuery('#wp-textArea-editor-tools .wp-editor-tabs').height()));

						tinyMCE.activeEditor.theme.resizeTo('auto', h);
						jQuery('#wp-textArea-editor-container textarea').css({height:(h2-20)});

						jQuery(window).bind('resize',function(){
							h = ( jQuery('#textarea_builder').height() ) - ( jQuery('#textarea_builder .mce-statusbar').outerHeight() + jQuery('#textarea_builder .mce-toolbar-grp').outerHeight() + jQuery('#textarea_builder .wp-editor-tabs').outerHeight() );
							h2 = (jQuery('#textarea_builder').height() - (jQuery('#qt_textArea_toolbar').height() + jQuery('#wp-textArea-editor-tools .wp-editor-tabs').height()));

							tinyMCE.activeEditor.theme.resizeTo('auto', h);
							jQuery('#wp-textArea-editor-container textarea').css({height:(h2-20)});
						});
					},100);
				});

			});

		}

	});

	tinymce.PluginManager.add('pixgridder', tinymce.plugins.PixGridder);

}
jQuery(function() { pixgridderTinyMCEinit(); });