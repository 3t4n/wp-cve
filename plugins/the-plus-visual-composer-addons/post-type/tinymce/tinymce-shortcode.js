(function ($) {
	tinymce.PluginManager.add('pt_plus_shortcodes', function (editor, url) {
		editor.addButton('pt_plus_shortcodes', {
			text: 'Insert shortcode',
			icon: false,
			type: 'menubutton',
			menu: [
                {
					text : 'Dropcap',
					onclick : function() {
						editor.insertContent('[tp_dropcap font_family="" font_size="40" background="#ff214f" color="#fff" shadow="false" style="1" ]I[/tp_dropcap]nsert your content here');
					}
				},
				{
					text : 'Fancy Link',
					onclick : function() {
						editor.insertContent('[tp_fancy_link title="Insert your content here" link="" target="" style="1" class="" download="" text_color="#252525" text_hover_color="#cccccc" background="#ff214f"]');
					}
				},
				{
					text : 'code',
					onclick : function() {
						editor.insertContent('[tp_code] [/tp_code]');
					}
				},
				{
					text : 'Hightlight',
					onclick : function() {
						editor.insertContent('[tp_hightlight title="Insert your content here" class="" background_hover="#1abc9c" background="#ff214f" text_color="#ffffff" text_hover_color="#121212" animation="yes"]');
					}
				},
				{
					text : 'Blockquote',
					onclick : function() {
						editor.insertContent('[tp_blockquote author="Jhon Doe" link="" target="_blank" color="#fff" background="#ff004b" quote_color="#d71951" border_color="#ff92b2" author_color="#fff" bottom_background="#fb5988" style="1"]Insert your content here[/tp_blockquote]');
					}
				}
			]
		});
	});
})();
