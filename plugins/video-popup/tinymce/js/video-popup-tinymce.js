/*
	Video PopUp for TinyMCE | v1.0.1
	By Alobaidi
	http://wp-plugins.in
*/

(function() {

	tinymce.PluginManager.add('video_popup_tinymce', function( editor, url ) {

		editor.addButton( 'video_popup_tinymce', {

			text: false,

			title: 'Display YouTube, Vimeo, SoundCloud, and MP4 Video in PopUp',

			icon: 'vp-mce-icon',

			onclick: function() {
				var vptmceGetSelection = tinyMCE.activeEditor.selection;

				var vptmceSelectedText = vptmceGetSelection.getContent( { format: "text" } );

				if( !vptmceSelectedText ){
					alert( 'Firstly select link or text!' );
					return false;
				}

				var vptmceGetHref = vptmceGetSelection.getNode().getAttribute('href');

				var vptmceGetYTID = vptmceGetSelection.getNode().getAttribute('data-ytid');

				if( vptmceGetYTID && ( vptmceGetHref.match(/(youtube.com)/) || vptmceGetHref.match(/(youtu.be)/) ) ){
					var vptmceGetHref = 'https://www.youtube.com/watch?v='+vptmceGetYTID+'';
				}

				var vptmceGetTitle = vptmceGetSelection.getNode().getAttribute('title');

				var vptmceGetAutoplay = vptmceGetSelection.getNode().getAttribute('data-autoplay');

				var vptmceGetRel = vptmceGetSelection.getNode().getAttribute('rel');

				var vptmceGetSoundCloud = vptmceGetSelection.getNode().getAttribute('data-soundcloud');

				var vptmceGetDisWrap = vptmceGetSelection.getNode().getAttribute('data-dwrap');

				if( vptmceGetSoundCloud == '1' ){
					var vptmceGetHref = vptmceGetSelection.getNode().getAttribute('data-soundcloud-url');
				}

				if( vptmceGetAutoplay ){
					var vpmce_autoplayCheckbox = true;
				}else{
					var vpmce_autoplayCheckbox = false;
				}

				if( vptmceGetRel ){
					var vpmce_relCheckbox = true;
				}else{
					var vpmce_relCheckbox = false;
				}

				if( vptmceGetDisWrap || video_popup_translation_vars.unprm_r_border ){
					var vpmce_DisWrap = true;
				}else{
					var vpmce_DisWrap = false;
				}

				var vp_tinymce_screen_height = jQuery(window).height();

				if( vp_tinymce_screen_height < 747 && vp_tinymce_screen_height > 500 ){
					var vp_tinymce_class = 'vp_tinymce_not_hd vp_tinymce_wrap';
				}
				else if( vp_tinymce_screen_height < 501 ){
					var vp_tinymce_class = 'vp_tinymce_is_tab vp_tinymce_wrap';
				}
				else{
					var vp_tinymce_class = 'vp_tinymce_is_hd vp_tinymce_wrap';
				}

				editor.windowManager.open( {

					title: 'Video PopUp',

					classes: vp_tinymce_class,

					body: [
							{
								type: 'textbox',
								name: 'vpmce_LinkText',
								label: 'Text',
								value: vptmceSelectedText,
								minWidth: 750
							},

							{
								type: 'textbox',
								name: 'vpmce_URL',
								label: 'URL',
								value: vptmceGetHref,
								minWidth: 750,
								tooltip: 'Enter YouTube, Vimeo, SoundCloud, or MP4 Video link only.',
								classes: 'vpmce_url_c'
							},

							{
								type: 'textbox',
								name: 'vpmce_Title',
								label: 'Title',
								value: vptmceGetTitle,
								minWidth: 750,
								tooltip: 'This title appears when the mouse passes over the link.',
								classes: 'vpmce_title_c'
							},

							{
								type: 'textbox',
								name: 'vpmce_imageLink',
								label: 'Image URL',
								value: '',
								minWidth: 750,
								tooltip: 'Enter an image link to display an image as link for the Video Popup. It’s works with YouTube, Vimeo, SoundCloud, and MP4 video.'
							},

							{
								type: 'textbox',
								label: 'You can using this Shortcode',
								value: video_popup_translation_vars.o_v_shortcode,
								minWidth: 750,
								tooltip: 'Click on "Shortcode Usage" button for the explanation.',
								classes: 'vp_shortcode_node_val',
								onclick: function( e ) {
									document.getElementsByClassName('mce-vp_shortcode_node_val')[0].select();
								}
							},

							{
								type: 'checkbox',
								name: 'vpmce_rel_nofollow',
								label: 'Rel Nofollow',
								checked: vpmce_relCheckbox,
								maxWidth: 20,
								tooltip: "Select this option if your URL is YouTube, Vimeo, or external MP4 video link, it's good for SEO. Do not select this option if your URL is locally MP4 video link or SoundCloud link.",
								classes: 'vpmce_nofollow_c'
							},

							{
								type: 'checkbox',
								name: 'vpmce_autoplay',
								label: 'Autoplay',
								maxWidth: 20,
								checked: vpmce_autoplayCheckbox,
								tooltip: 'Autoplay for YouTube, Vimeo, SoundCloud, and MP4 video (externally and locally).'
							},

							{
								type: 'checkbox',
								name: 'vpmce_dis_wrap',
								label: 'Remove Border',
								tooltip: 'Removing the white border.',
								checked: vpmce_DisWrap,
								maxWidth: 20
							},

							{
								type: 'checkbox',
								name: 'vpmce_dis_rel',
								label: 'Disable Related Videos (Premium)',
								checked: false,
								maxWidth: 20,
								disabled: true,
								tooltip: 'The behavior for the rel parameter is changing on or after September 25, 2018. The effect of the change is that you will not be able to disable related videos. Prior to the change, if you disabled the related videos, then the player does not show related videos. After the change, if you disabled the related videos, the player will show related videos that are from the same channel. This option for YouTube only. This option for the Premium Extension.'
							},

							{
								type: 'checkbox',
								name: 'vpmce_dis_controls',
								label: 'Disable Controls (Premium)',
								checked: false,
								maxWidth: 20,
								disabled: true,
								tooltip: 'Disable YouTube player controls. This option for YouTube only. This option for the Premium Extension.'
							},

							{
								type: 'checkbox',
								name: 'vpmce_dis_iv',
								label: 'Disable Annotations (Premium)',
								checked: false,
								maxWidth: 20,
								disabled: true,
								tooltip: 'Disable video annotations. This option for YouTube only. This option for the Premium Extension.'
							},

							{
								type: 'checkbox',
								name: 'vpmce_display_yt_img',
								label: 'Display YouTube Image (Premium)',
								checked: false,
								maxWidth: 20,
								disabled: true,
								tooltip: 'Display YouTube video image inside the Video PopUp link. This option for YouTube only. This option for the Premium Extension.'
							},

							{
								type: 'textbox',
								name: 'vpmce_time',
								label: 'Starting Time (Premium)',
								tooltip: 'Enter the starting time for the video, for example enter "90" (1 minute + 30 seconds = 90), the video will be played in "1:30". Numbers only. This option for YouTube only. This option for the Premium Extension.',
								value: '',
								disabled: true,
								maxWidth: 76
							},

							{
								type: 'textbox',
								name: 'vpmce_ending_time',
								label: 'Ending Time (Premium)',
								tooltip: 'The time offset at which the video should stop playing. The value is a positive integer that specifies the number of seconds into the video that the player stops playback. For example enter "90" (1 minute + 30 seconds = 90), now the video will be stopped playing in "1:30". This option for YouTube only. This option for the Premium Extension.',
								value: '',
								disabled: true,
								maxWidth: 76
							},

							{
								type: 'textbox',
								name: 'vpmce_width',
								label: 'Width Size (Premium)',
								tooltip: 'Enter width size for the video, for example "1200". Numbers only. This option for the Premium Extension.',
								value: '',
								disabled: true,
								maxWidth: 76
							},

							{
								type: 'textbox',
								name: 'vpmce_height',
								label: 'Height Size (Premium)',
								tooltip: 'Enter height size for the video, for example "600". Numbers only. This option for the Premium Extension.',
								value: '',
								disabled: true,
								maxWidth: 76
							},

							{
								type: 'textbox',
								name: 'vpmce_olcolor',
								label: 'Color of Overlay (Premium)',
								tooltip: 'Enter the color of overlay, enter HEX code only, for example "#ffffff". Enter full HEX code such as "#ffffff", not shortened such as "#fff". Default is black "#000000". This option for the Premium Extension.',
								value: '',
								disabled: true,
								maxWidth: 76,
								classes: 'vp_overlay_color_node_val'
							},

							{
								type: 'button',
								text: 'Explanation of Use',
								tooltip: 'Need help? Support? Questions? Read the Explanation of Use.',
								maxWidth: 220,
								classes: 'vp_doc_link_node',
								onclick: function( e ) {
									window.open('https://wp-plugins.in/VideoPopUp-Usage');
								}
							},

							{
								type: 'button',
								text: 'Shortcode Usage',
								tooltip: 'Read Explanation of Use the Shortcode.',
								maxWidth: 220,
								classes: 'vp_admin_link_node',
								onclick: function( e ) {
									window.open(video_popup_translation_vars.shortcode_usage);
								}
							},

							{
								type: 'button',
								text: 'General Settings',
								tooltip: "General settings will applied to all the video popup's.",
								maxWidth: 220,
								classes: 'vp_admin_link_node',
								onclick: function( e ) {
									window.open(video_popup_translation_vars.gen_settings);
								}
							},

							{
								type: 'button',
								text: 'On Page Load',
								tooltip: "Display Pop-up Video on page loading.",
								maxWidth: 220,
								classes: 'vp_admin_link_node',
								onclick: function( e ) {
									window.open(video_popup_translation_vars.on_pageload);
								}
							},

							{
								type: 'button',
								text: 'Get The Premium Extension!',
								tooltip: "Get it at a low price! Unlock all the features. Easy to use, download it, install it, activate it, and enjoy! Get it now!",
								maxWidth: 220,
								classes: 'vp_buy_extension_btn',
								onclick: function( e ) {
									window.open('https://wp-plugins.in/Get-VP-Premium-Extension');
								}
							}

					],

					onsubmit: function(e) {

						if( e.data.vpmce_rel_nofollow === true){
							var vpAttrRel = ' rel="nofollow"';
						}else{
							var vpAttrRel = null;
						}

						if( e.data.vpmce_Title ){
							var vpAttrTitle = ' title="'+e.data.vpmce_Title+'"';
						}else{
							var vpAttrTitle = null;
						}

						if( e.data.vpmce_dis_wrap === true){
							var vpAttrDisWrap = ' data-dwrap="1"';
						}else{
							var vpAttrDisWrap = null;
						}

						if( e.data.vpmce_autoplay === true){
							var vpAttrAutoplay = ' data-autoplay="1"';
							var soundcloudAutoPlay = '&vp_soundcloud_a=true';
						}else{
							var vpAttrAutoplay = null;
							var soundcloudAutoPlay = '&vp_soundcloud_a=false';
						}

						if( e.data.vpmce_URL.match(/(soundcloud.com)/) ){
							var link_class = 'vp-sc-type';
						}else if( e.data.vpmce_URL.match(/(vimeo.com)/) ){
							var link_class = 'vp-vim-type';
						}else if( e.data.vpmce_URL.match(/(youtube.com)/) || e.data.vpmce_URL.match(/(youtu.be)/) ){
							var link_class = 'vp-yt-type';
						}else{
							var link_class = 'vp-mp4-type';
						}

						if( vpAttrAutoplay ){
							var vpAttrClass = ' class="vp-a '+link_class+'"';
						}else{
							var vpAttrClass = ' class="vp-s '+link_class+'"';
						}

						if( e.data.vpmce_URL.match(/(soundcloud.com)/) ){
							var vpAttrYouTubeVideoID = null;
							var vp_url = '#';
							var vp_sc_url = video_popup_translation_vars.soundcloud_url + e.data.vpmce_URL + soundcloudAutoPlay;
							var vpAttSoundCloud = ' data-soundcloud="1" data-soundcloud-url="'+e.data.vpmce_URL+'" data-embedsc="'+vp_sc_url+'"';
						}else{
							if( e.data.vpmce_URL ){
								if( e.data.vpmce_URL.match(/(youtu.be)/) || e.data.vpmce_URL.match(/(youtube.com)/) ){
									if( e.data.vpmce_URL.match(/(youtube.com)/) ){
                    					var split_c = "v=";
                    					var split_n = 1;
                					}

                					if( e.data.vpmce_URL.match(/(youtu.be)/) ){
                    					var split_c = "/";
                    					var split_n = 3;
                					}

                					var getYouTubeVideoID = e.data.vpmce_URL.split(split_c)[split_n];

               					 	var cleanVideoID = getYouTubeVideoID.replace(/(&)+(.*)/, "");

               					 	var vpAttrYouTubeVideoID = ' data-ytid="'+cleanVideoID+'"';

               					 	var vp_url = 'https://www.youtube.com/watch?v='+cleanVideoID+'';
								}else{
									var vpAttrYouTubeVideoID = null;
									var vp_url = e.data.vpmce_URL;
								}
							}else{
								var vpAttrYouTubeVideoID = null;
								var vp_url = '#';
							}
							var vpAttSoundCloud = null;
						}

						var vpLinkAttrs = vpAttrTitle+vpAttSoundCloud+vpAttrRel+vpAttrAutoplay+vpAttrDisWrap+vpAttrClass+vpAttrYouTubeVideoID;

						if( e.data.vpmce_imageLink ){
							var vp_get_the_image = '<img class="vp-img" src="'+e.data.vpmce_imageLink+'">';
							var vp_the_element = '<p class="vp-img-paragraph"><a href="'+vp_url+'"'+vpLinkAttrs+'>'+vp_get_the_image+'</a></p>';
						}else{
							var vp_the_element = '<a href="'+vp_url+'"'+vpLinkAttrs+'>'+e.data.vpmce_LinkText+'</a>';
						}

                		editor.insertContent(vp_the_element);
            		}

				})
			
			}

		});

	});

})();