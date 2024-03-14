/**
 * Simple TinyMCE plugin to add wp-monalisa smilies
 *
 *  @package wp-monalisa
 */

(function() {

	tinymce.create(
		'tinymce.plugins.wpml_smiley',
		{
			/**
			 * Initializes the plugin, this will be executed after the plugin has been created.
			 * This call is done before the editor instance has finished it's initialization so use the onInit event
			 * of the editor instance to intercept that event.
			 *
			 * @param {tinymce.Editor} editor Editor instance that the plugin is initialized in.
			 * @param {string} url Absolute URL to where the plugin is located.
			 */
			init : function(editor, url) {
				function wpml_get_smilies() {
					var resmilies = _wpml_richedit_smilies;
					var colcount = 7;
					var smhtml = "";
					var i = 0;

					var l = resmilies.length;
					for (var j = 0; j < l; j++) {
						  var cs = resmilies[j];
						  smhtml = smhtml + '<img class="wpml_ico_rich" id="wpmlre' + j + '" src="' + cs[2] + '" />';

						if (i % colcount == colcount - 1) {
							smhtml += '<br/>';
						}
						  i = i + 1;
					}
					return smhtml;
				}

				// Add Button to Visual Editor Toolbar.
				editor.addButton(
					'wpml_smiley',
					{
						title: 'Insert Smiley',
						tooltip: "wp-Monalisa Smilies",
						image: url + '/smiley.png',
						cmd: 'wpml_smiley',
						type: 'panelbutton',
						panel: {
							classes: 'wpml_richedit_button',
							role: 'application',
							autohide: true,
							html: wpml_get_smilies,
							onclick: function(e) {
								var eid = e.target.id;
								var it = jQuery( '#' + eid ).attr( 'src' );
								if (it) {
									editor.insertContent( '<img style="vertical-align:bottom" class="wpml_ico" src="' + it + '" />' );
								}
								this.hide();
							}
						}
					}
				);
			}
		}
	);
	// Register plugin.
	tinymce.PluginManager.add( 'wpml_smiley', tinymce.plugins.wpml_smiley );

})();
