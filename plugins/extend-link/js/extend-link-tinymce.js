/*
	Extend Link v1.0.3
	By Alobaidi
	http://wp-plugins.in
*/

(function() {

	tinymce.PluginManager.add('wptp_extend_link_tinymce', function( editor, url ) {

		editor.addButton( 'wptp_extend_link_tinymce', {

			text: 'Extend Link',

			title: 'Inserting link Rel, ID, Classes, and more!',

			icon: 'extendlink-mce-icon',

			onclick: function() {
				var wptExtLinkGetSelection = tinyMCE.activeEditor.selection;

				var wptExtLinkSelectedText = wptExtLinkGetSelection.getContent( { format: "text" } );

				if( !wptExtLinkSelectedText ){
					alert('Firstly select link or text, and click on "Extend Link" button.');
					return false;
				}

				var wptExtLinkGetHref = wptExtLinkGetSelection.getNode().getAttribute('href');

				var wptExtLinkGetRel = wptExtLinkGetSelection.getNode().getAttribute('rel');

				var wptExtLinkGetTitle = wptExtLinkGetSelection.getNode().getAttribute('title');

				var wptExtLinkGetID = wptExtLinkGetSelection.getNode().getAttribute('id');

				var wptExtLinkGetClass = wptExtLinkGetSelection.getNode().getAttribute('class');

				var wptExtLinkGetTarget = wptExtLinkGetSelection.getNode().getAttribute('target');

				var wptExtLinkGetDownload = wptExtLinkGetSelection.getNode().getAttribute('download');

				var wptExtLinkGetElementType = wptExtLinkGetSelection.getNode();

				if( wptExtLinkGetTarget ){
					var wptexl_TargetChecked = true;
				}else{
					var wptexl_TargetChecked = false;
				}

				if( wptExtLinkGetDownload ){
					var wptexl_DownloadChecked = true;
				}else{
					var wptexl_DownloadChecked = false;
				}

				if( wptExtLinkGetElementType.tagName == 'A' ){
					var element_type = 'Edit Link';
				}else{
					var element_type = 'Insert a new link';
				}

				editor.windowManager.open( {

					title: 'Extend Link',

					body: [

							{
								type: 'label',
								text: element_type,
								style: "font-size:20px !important; font-weight: bold !important;"
							},

							{
								type: 'textbox',
								name: 'wptexl_LinkText',
								label: 'Text',
								value: wptExtLinkSelectedText,
								minWidth: 600
							},

							{
								type: 'textbox',
								name: 'wptexl_URL',
								label: 'URL',
								value: wptExtLinkGetHref,
								minWidth: 600
							},

							{
								type: 'textbox',
								name: 'wptexl_Title',
								label: 'Title',
								tooltip: 'Enter your title. The title appears when the mouse passes over the element or the link.',
								value: wptExtLinkGetTitle,
								minWidth: 600
							},

							{
								type: 'textbox',
								name: 'wptexl_Class',
								label: 'Class',
								tooltip: 'Enter your class name. If you have more than 1 class, enter space between each class, for example: class1 class2 class3',
								value: wptExtLinkGetClass,
								maxWidth: 200
							},

							{
								type: 'textbox',
								name: 'wptexl_ID',
								label: 'ID',
								tooltip: 'Enter your ID, one ID only!',
								value: wptExtLinkGetID,
								maxWidth: 200
							},

							{
								type: 'textbox',
								name: 'wptexl_Rel',
								label: 'Rel',
								tooltip: 'Enter link Rel. For example, enter "nofollow" for external links.',
								value: wptExtLinkGetRel,
								maxWidth: 200
							},

							{
								type: 'checkbox',
								name: 'wptexl_Target',
								label: 'Open link in a new tab',
								tooltip: 'Select "Open link in a new tab" if you want to open your link in a new tab.',
								maxWidth: 30,
								checked: wptexl_TargetChecked
							},

							{
								type: 'checkbox',
								name: 'wptexl_Download',
								label: 'Downloadable',
								maxWidth: 30,
								tooltip: 'Select "Downloadable" if you have downloadable link.',
								checked: wptexl_DownloadChecked
							},

							{
								type: 'label',
								text: 'Recommended Links:',
								style:"color:#56a639 !important; font-weight:bold !important;"
							},

							{
								type: 'button',
								text: 'Divi Theme',
								tooltip: 'The Ultimate WordPress Theme & Visual Page Builder. Try it, a 30-Day Money Back Guarantee!',
								maxWidth: 100,
								onclick: function( e ) {
									window.open('http://wp-plugins.in/ElegantThemes_ExtendLinkTinyMCE_Divi');
								}
							},

							{
								type: 'button',
								text: 'Bluehost',
								tooltip: 'The Best Web and WordPress Hosting. Try it, a 30-Day Money Back Guarantee!',
								maxWidth: 100,
								onclick: function( e ) {
									window.open('http://wp-plugins.in/Bluehost_ExtendLink');
								}
							},

							{
								type: 'label',
								style: "font-size:12px !important;color:#888 !important; text-decoration:underline !important;",
								text: 'Why do you see "Recommended Links" in this plugin?',
								tooltip: "We offer you free professional plugins for free, so you'll see Recommended Links, which is the only support source.",
								maxWidth: 315
							}
					],

					onsubmit: function(e) {

						if( e.data.wptexl_Rel ){
							var wptexlAttrRel = ' rel="'+e.data.wptexl_Rel+'"';
						}else{
							var wptexlAttrRel = null;
						}

						if( e.data.wptexl_Title ){
							var wptexlAttrTitle = ' title="'+e.data.wptexl_Title+'"';
						}else{
							var wptexlAttrTitle = null;
						}

						if( e.data.wptexl_ID ){
							var wptexlAttrID = ' id="'+e.data.wptexl_ID+'"';
						}else{
							var wptexlAttrID = null;
						}

						if( e.data.wptexl_Class ){
							var wptexlAttrClass = ' class="'+e.data.wptexl_Class+'"';
						}else{
							var wptexlAttrClass = null;
						}

						if( e.data.wptexl_Target === true){
							var wptexlAttrTarget = ' target="_blank"';
						}else{
							var wptexlAttrTarget = null;
						}

						if( e.data.wptexl_Download === true){
							var wptexlAttrDownload = ' download="true"';
						}else{
							var wptexlAttrDownload = null;
						}

						editor.insertContent('<a href="'+e.data.wptexl_URL+'"'+wptexlAttrRel+wptexlAttrTitle+wptexlAttrID+wptexlAttrClass+wptexlAttrDownload+wptexlAttrTarget+'>'+e.data.wptexl_LinkText+'</a>');

            		}

				})

			}

		});

	});

})();