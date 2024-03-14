(function( $ ) {
	'use strict';

	$(function() {
		var wb_tab=
		{
			tab_single_html:'',
			no_tabs_html:'',
			title_max:50,
			new_tab_edit:false,
			Init:function()
			{
				this.tab_title();
				this.add_new();
				this.remove_tab();
				this.edit_tab();
				this.reg_popup_close();
				this.reg_youtube_embed();
			},
			tab_title:function()
			{
				jQuery(document).on('paste keyup change', '.wb_tab_title_input', function(e){
					var vl= $(this).val().trim();
					if(vl.length>wb_tab.title_max)
					{ 
						vl=vl.substr(0, wb_tab.title_max); 
						$(this).val(vl);
						$(this).siblings('.wb_tab_er').text("Maximum "+wb_tab.title_max+" characters allowed for tab title.");
					}else
					{
						$(this).siblings('.wb_tab_er').text('');
					}
				});
			},
			add_new:function()
			{
				$('.wb_tab_addnew_btn').on('click', function(){
					wb_tab.store_single_tab_html();
					let new_elm = $(wb_tab.tab_single_html);

					if($('.wb_tab_panel_local').length>0)
					{
						$('.wb_tab_panel_local:last').after(new_elm);
					}else
					{
						$('.wb_tab_main_inner').prepend(new_elm);
					}

					$('.wb_no_tabs').remove();
					wb_tab.process_tabs();

					new_elm.find('.wb_tab_title_input').val('');
					new_elm.find('.wb_tab_panel_edit').trigger('click'); /* open the edit screen */
					setTimeout(function(){
						wb_tab.new_tab_edit = true;
					}, 300);

				});
			},
			edit_tab:function()
			{
				$(document).on('click', '.wb_tab_panel .wb_tab_panel_edit', function(e){ 
					e.stopPropagation();
					if(e.target.className=='wb_tab_panel_edit' || e.target.className=='dashicons dashicons-edit')
					{
						wb_tab.new_tab_edit = false;
						wb_tab.popup_open('wb_cptb_tab_edit_popup');					
						wb_tab.set_content_to_edit_form($(this));
					}
				});
				$('.wb_cptb_tab_save_btn').off('click').on('click', function(){
					var tab_title=$('[name="wb_tab_title"]').val().trim();
					if(tab_title=="")
					{
						alert(wb_custom_tabs_params.msgs.title_mandatory);
						$('[name="wb_tab_title"]').focus();
						return false;
					}

					var tab_content = wb_tab.get_tab_content_form_val();
					if(tab_content=="")
					{
						alert(wb_custom_tabs_params.msgs.content_mandatory);
						return false;
					}

					wb_tab.popup_close($(this));
					wb_tab.set_content_to_main_form();
				});
			},
			update_tab_head:function(current_tab, vl)
			{
				var hd_elm=current_tab.find('.wb_tab_panel_hd_txt');
				hd_elm.text(vl);
			},
			set_content_to_main_form:function()
			{
				var elm=$('.wb_cptb_tab_save_btn').data('target-tab');
				if(elm!==null)
				{
					var current_tab=elm.parents('.wb_tab_panel');
					var tab_title=$('[name="wb_tab_title"]').val();
					var tab_position=$('[name="wb_tab_position"]').val();
					var tab_nickname=$('[name="wb_tab_nickname"]').val();
					current_tab.find('.wb_tab_title_input').val(tab_title);
					current_tab.find('.wb_tab_position_input').val(tab_position);
					current_tab.find('.wb_tab_nickname_input').val(tab_nickname);

					var tab_content = this.get_tab_content_form_val();
					current_tab.find('.wb_tab_content_input').val(tab_content);
					$('.wb_cptb_tab_save_btn').data('target-tab', null);
					wb_tab.update_tab_head(current_tab, (tab_nickname.trim()!="" ? tab_nickname : tab_title));
				}			
			},
			get_tab_content_form_val:function()
			{
				var tab_content='';
				
				if(typeof tinyMCE!="undefined")
				{
					var activeEditor=tinyMCE.get('wb_tab_editor');
					if(activeEditor!=null)
					{
						tab_content=activeEditor.getContent();

					}else if($('textarea#wb_tab_editor').length)
					{
						tab_content = $('textarea#wb_tab_editor').val().trim();
					}
				}

				return tab_content;
			},
			set_content_to_edit_form:function(elm)
			{
				var current_tab=elm.parents('.wb_tab_panel');
				var tab_title=current_tab.find('.wb_tab_title_input').val();
				var tab_position=current_tab.find('.wb_tab_position_input').val();
				var tab_nickname=current_tab.find('.wb_tab_nickname_input').val();
				$('[name="wb_tab_title"]').val(tab_title);
				$('[name="wb_tab_position"]').val(tab_position);
				$('[name="wb_tab_nickname"]').val(tab_nickname);

				var tab_content=current_tab.find('.wb_tab_content_input').val();
				if(typeof tinyMCE!="undefined")
				{
					var activeEditor=tinyMCE.get('wb_tab_editor');
					if(activeEditor!=null)
					{
						activeEditor.setContent(tab_content);
					}
				}

				$('.wb_cptb_tab_save_btn').data('target-tab', elm);
			},
			popup_open:function(popup_class)
			{
				let target_elm = $('.' + popup_class + ', .wb_tab_popup_overlay');
				
				if($('.wb_tab_popup_overlay').is(':visible'))
				{
					target_elm = $('.' + popup_class);
				}

				target_elm.css({'opacity':0, 'display':'block'}).animate({'opacity':1});
			},
			popup_close:function(elm)
			{
				if(1 === $('.wb_tab_popup:visible').length)
				{
					$('.wb_tab_popup_overlay').hide();
				}

				elm.parents('.wb_tab_popup').hide();

			},
			reg_popup_close:function()
			{
				$('.wb_tab_popup_close, .wb_tab_cancel_btn').click(function(){
					
					if(wb_tab.new_tab_edit && $(this).parents('.wb_tab_popup').find('.wb_cptb_tab_save_btn').length) /* cancelled new tab edit */
					{
						let target_elm = $('.wb_cptb_tab_save_btn').data('target-tab');

						if(target_elm!==null && target_elm.parents('.wb_tab_panel').length)
						{
							target_elm.parents('.wb_tab_panel').remove();
						}
					}

					wb_tab.popup_close($(this));
				});
			},
			remove_tab:function()
			{
				$(document).on('click', '.wb_tab_panel .wb_tab_panel_delete', function(e){ 
					e.stopPropagation();
					if(e.target.className=='wb_tab_panel_delete' || e.target.className=='dashicons dashicons-trash')
					{ 
						if(confirm(wb_custom_tabs_params.msgs.sure))
						{
							if($('.wb_tab_main_inner .wb_tab_panel.wb_tab_panel_local').length === 1 ) /* this is the only one local tab. So save the HTML before deleting */
							{
								wb_tab.store_single_tab_html();
							}

							$(this).parents('.wb_tab_panel').remove();
							if($('.wb_tab_main_inner .wb_tab_panel').length==0)
							{
								$('.wb_tab_main_inner').html(wb_tab.no_tabs_html);
							}else
							{
								wb_tab.process_tabs();
							}
						}
					}
				});
			},
			store_single_tab_html:function()
			{
				if(this.tab_single_html=='')
				{ 
					if($('.wb_no_tabs').length>0) /* no tab exists */
					{
						this.no_tabs_html=$('.wb_no_tabs:eq(0)')[0].outerHTML;
					}

					if($('.wb_tab_default').length>0) /* no local tab exists */
					{
						this.tab_single_html=$('.wb_tab_default').html();
						$('.wb_tab_default').remove();
					}else
					{
						this.tab_single_html=$('.wb_tab_main_inner .wb_tab_panel_local:eq(0)')[0].outerHTML;	
					}
					this.cleanup_single_tab_html();
				}	
			},
			cleanup_single_tab_html:function()
			{
				var temp_dom=$('<div />').html(this.tab_single_html);
				temp_dom.find('.wb_tab_panel_hd_txt').html(wb_custom_tabs_params.msgs.untitled);
				temp_dom.find('.wb_tabpanel_txt:not(.wb_tab_nickname_input)').attr('value', wb_custom_tabs_params.msgs.untitled);
				temp_dom.find('.wb_tabpanel_txtarea').html('');
				temp_dom.removeAttr('data-disabled');
				this.tab_single_html=temp_dom.html();
			},
			process_tabs:function()
			{
				var inc=0;
				$('.wb_tab_main_inner .wb_tab_panel').each(function(){
					$(this).find('.wb_tab_title_input').attr('name','wb_tab['+inc+'][title]');
					$(this).find('.wb_tab_position_input').attr('name','wb_tab['+inc+'][position]');
					$(this).find('.wb_tab_content_input').attr('name','wb_tab['+inc+'][content]');
					inc++;
				});
			},
			reg_youtube_embed:function()
			{
				$(document).on('click', '.wb_cptb-embed-youtube', function(){
					let editor_id = $(this).attr('data-editor');

					if(null === tinymce.get(editor_id))
					{
						let visual_btn = $('button.wp-switch-editor.switch-tmce[data-wp-editor-id="' + editor_id + '"]');

						if(visual_btn.length && visual_btn.parents('.wp-editor-wrap').hasClass('html-active'))
						{
							visual_btn.trigger('click');
							setTimeout(function(){ 
								$('button.wp-switch-editor.switch-html[data-wp-editor-id="' + editor_id + '"]').trigger('click');
							}, 500);

							if(null === tinymce.get(editor_id))
							{
								return;
							}
						}else
						{
							return;
						}
					}

					wb_tab.popup_open('wb_cptb_youtube_popup');
					$('.wb_cptb_youtube_insert_btn').attr('data-editor', editor_id);
				});

				$(document).on('input', '[name="wb_cptb_youtube_url"]', function(){
					var vl = $(this).val().trim();

					if('' === vl || vl.length < 11 || null !== wb_tab.extract_youtube_video_id(vl))
					{ 
						$(this).siblings('.wb_tab_er').text('');
					}else
					{
						$(this).siblings('.wb_tab_er').text("Is this valid Youtube URL/Video ID?");
					}

				});

				$(document).on('blur', '[name="wb_cptb_youtube_url"]', function(){
					var vl = $(this).val().trim();

					if(vl.length < 11)
					{ 
						$(this).siblings('.wb_tab_er').text("Is this valid Youtube URL/Video ID?");
					}else
					{
						$(this).siblings('.wb_tab_er').text('');
					}
				});

				$(document).on('click', '.wb_cptb_youtube_insert_btn', function(){
					
					var wb_cptb_yt_bt_elm = $(this);
					let video_id = wb_tab.extract_youtube_video_id($('[name="wb_cptb_youtube_url"]').val().trim());

					if(null === video_id)
					{
						alert(wb_custom_tabs_params.msgs.invalid_video_id);
						return false;
					}

					let video_width = parseInt($('[name="wb_cptb_youtube_width"]').val().trim());
					let video_width_attr = !isNaN(video_width) ? ' width="' + video_width + '"' : '';

					let video_height = parseInt($('[name="wb_cptb_youtube_height"]').val().trim());
					let video_height_attr = !isNaN(video_height) ? ' height="' + video_height + '"' : '';
					var video_embed_html = '[wb_cpt_youtube_embed_shortcode video_id="' + video_id + '"' + video_width_attr + video_height_attr + ']';

					let editor_id = wb_cptb_yt_bt_elm.attr('data-editor');
					let visual_btn = $('button.wp-switch-editor.switch-tmce[data-wp-editor-id="' + editor_id + '"]');

					if(visual_btn.parents('.wp-editor-wrap').hasClass('html-active'))
					{
						wb_cptb_yt_bt_elm.html(wb_custom_tabs_params.msgs.inserting).prop('disabled', true);
						visual_btn.trigger('click');

						setTimeout(function(){ 
							wb_cptb_yt_bt_elm.html(wb_custom_tabs_params.msgs.insert).prop('disabled', false);
							tinymce.get(editor_id).insertContent(video_embed_html);
							$('button.wp-switch-editor.switch-html[data-wp-editor-id="' + editor_id + '"]').trigger('click');				
							wb_tab.popup_close(wb_cptb_yt_bt_elm);

						}, 500);

					}else
					{
						tinymce.get(editor_id).insertContent(video_embed_html);
						wb_tab.popup_close(wb_cptb_yt_bt_elm);
					}
				});
			},
			extract_youtube_video_id:function(youtube_data)
			{
				const patterns = [
					/^.*(?:(?:youtu\.be\/|v\/|vi\/|u\/\w\/|embed\/|shorts\/)|(?:(?:watch)?\?v(?:i)?=|\&v(?:i)?=))([^#\&\?]*).*/,
					/(?:https?:\/\/)?(?:www\.)?youtu(?:be\.com\/(?:watch\?v=|embed\/|v\/)|\.be\/)([\w\-]+)(?:\S+)?/,
					/^(?:https?:\/\/)?(?:www\.)?youtube\.com\/shorts\/([\w\-]+)/,
					/^([\w\-]+)$/
				];

				for (let i = 0; i < patterns.length; i++)
				{
					const match = youtube_data.match(patterns[i]);
					if(match && match[1] && this.validate_youtube_video_id(match[1]))
					{
				  		return match[1];
					}
				}

				return null;
			},
			validate_youtube_video_id:function(video_id)
			{
				const pattern = /^[a-zA-Z0-9_-]{11}$/;
  				return pattern.test(video_id);
			}
		}
		wb_tab.Init();
		
	});

})( jQuery );