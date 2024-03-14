var wt_pf_basic_history=(function( $ ) {
	//'use strict';
	var wt_pf_basic_history=
	{
		log_offset:0,
		Set:function()
		{
			this.reg_delete();
			this.reg_view_log();
			this.reg_bulk_action();
                        this.reg_copy_action();
                        this.reg_refresh();
		},
		reg_view_log:function()
		{
			jQuery(document).on('click', ".wt_pf_view_log_btn", function(){
                            jQuery('.wt_pf_overlay, .wt_pf_popup').hide();
                            wt_pf_basic_history.show_log_popup();
                            var history_id=$(this).attr('data-history-id');
                            if(history_id>0)
                            {
                                    wt_pf_basic_history.log_offset=0;
                                    wt_pf_basic_history.load_page(history_id);
                            }else
                            {
                                    var log_file=$(this).attr('data-log-file');
                                    if(log_file!="")
                                    {
                                            wt_pf_basic_history.view_raw_log(log_file);
                                    }
                            }
			});
		},
		view_raw_log:function(log_file)
		{
			$('.wt_pf_log_container').html('<div class="wt_pf_log_loader">'+wt_pf_basic_params.msgs.loading+'</div>');
			$.ajax({
				url:wt_pf_basic_params.ajax_url,
				data:{'action':'iew_history_ajax_basic', _wpnonce:wt_pf_basic_params.nonces.main, 'history_action':'view_log', 'log_file':log_file, 'data_type':'json'},
				type:'post',
				dataType:"json",
				success:function(data)
				{
					if(data.status==1)
					{
						$('.wt_pf_log_container').html(data.html);
					}else
					{
						$('.wt_pf_log_loader').html(wt_pf_basic_params.msgs.error);
						wt_pf_notify_msg.error(wt_pf_basic_params.msgs.error);
					}
				},
				error:function()
				{
					$('.wt_pf_log_loader').html(wt_pf_basic_params.msgs.error);
					wt_pf_notify_msg.error(wt_pf_basic_params.msgs.error);
				}
			});
		},
		show_log_popup:function()
		{
			var pop_elm=$('.wt_pf_view_log');
			var ww=$(window).width();
			pop_w=(ww<1300 ? ww : 1300)-200;
			pop_w=(pop_w<200 ? 200 : pop_w);
			pop_elm.width(pop_w);

			wh=$(window).height();
			pop_h=(wh>=400 ? (wh-200) : wh);
			$('.wt_pf_log_container').css({'max-height':pop_h+'px','overflow':'auto'});
			wt_pf_popup.showPopup(pop_elm);
		},
		load_page:function(history_id)
		{
			var offset=wt_pf_basic_history.log_offset;
			if(offset==0)
			{
				$('.wt_pf_log_container').html('<div class="wt_pf_log_loader">'+wt_pf_basic_params.msgs.loading+'</div>');
			}else
			{
				$('.wt_pf_history_loadmore_btn').hide();
				$('.wt_pf_history_loadmore_loading').show();
			}
			$.ajax({
				url:wt_pf_basic_params.ajax_url,
				data:{'action':'iew_history_ajax_basic', _wpnonce:wt_pf_basic_params.nonces.main, 'history_action':'view_log', 'offset': offset, 'history_id':history_id, 'data_type':'json'},
				type:'post',
				dataType:"json",
				success:function(data)
				{
					$('.wt_pf_history_loadmore_btn').show();
					$('.wt_pf_history_loadmore_loading').hide();
					if(data.status==1)
					{
						wt_pf_basic_history.log_offset=data.offset;
						if(offset==0)
						{
							$('.wt_pf_log_container').html(data.html);
						}else
						{
							$('.log_view_tb_tbody').append(data.html);
						}
						if(data.finished)
						{
							$('.wt_pf_history_loadmore_btn').hide();
						}else
						{
							if(offset==0)
							{
								$('.wt_pf_history_loadmore_btn').unbind('click').click(function(){
									wt_pf_basic_history.load_page(history_id);
								});
							}
						}
					}else
					{
						$('.wt_pf_log_loader').html(wt_pf_basic_params.msgs.error);
						wt_pf_notify_msg.error(wt_pf_basic_params.msgs.error);
					}				
				},
				error:function()
				{
					$('.wt_pf_log_loader').html(wt_pf_basic_params.msgs.error);
					$('.wt_pf_history_loadmore_btn').show();
					$('.wt_pf_history_loadmore_loading').hide();
					wt_pf_notify_msg.error(wt_pf_basic_params.msgs.error);
				}
			});
		},
		reg_delete:function()
		{
			jQuery('.wt_pf_delete_history, .wt_pf_delete_log').click(function(){
				if(confirm(wt_pf_history_basic_params.msgs.sure))
				{
					window.location.href=jQuery(this).attr('data-href');
				}
			});
		},
                reg_refresh:function()
		{
			jQuery('.wt_pf_export_refresh_btn').click(function(){
                            var cron_id = jQuery(this).attr('data-cron_id');		
                            $.ajax({
				url:wt_pf_basic_params.ajax_url,
				data:{'action':'pf_schedule_refresh', _wpnonce:wt_pf_basic_params.nonces.main, 'cron_id':cron_id, 'force_refresh': true},
				type:'post',
				dataType:"json",
				success:function(data)
				{
					if(data.status==1)
					{
						wt_pf_notify_msg.success(data.msg);
					}else
					{
						wt_pf_notify_msg.error(wt_pf_basic_params.msgs.error);
					}
				},
				error:function()
				{
					wt_pf_notify_msg.error(wt_pf_basic_params.msgs.error);
				}
			});
			});
		},
                reg_copy_action:function()
		{
			jQuery('.wt_pf_copy').click(function(){
                            var data_uri = jQuery(this).attr('data-uri');
                                var result = wt_pf_basic_history.copyToClipboard(data_uri);
                                wt_pf_notify_msg.success(wt_pf_history_basic_params.copied_msg);
			});
		},
                copyToClipboard: function(text){
                    if (window.clipboardData && window.clipboardData.setData) {
                        // IE specific code path to prevent textarea being shown while dialog is visible.
                        return clipboardData.setData("Text", text);

                    } else if (document.queryCommandSupported && document.queryCommandSupported("copy")) {
                        var textarea = document.createElement("textarea");
                        textarea.textContent = text;
                        textarea.style.position = "fixed";  // Prevent scrolling to bottom of page in MS Edge.
                        document.body.appendChild(textarea);
                        textarea.select();
                        try {
                            return document.execCommand("copy");  // Security exception may be thrown by some browsers.
                        } catch (ex) {
                            console.warn("Copy to clipboard failed.", ex);
                            return false;
                        } finally {
                            document.body.removeChild(textarea);
                        }
                    }
                },
		reg_bulk_action:function()
		{
			var checkbox_main=$('.wt_pf_history_checkbox_main');
			var checkbox_sub=$('.wt_pf_history_checkbox_sub');
			var tb=$('.history_list_tb');
			if(tb.find('.wt_pf_history_checkbox_sub:checked').length==tb.find('.wt_pf_history_checkbox_sub').length)
			{
				checkbox_main.prop('checked',true);
			}else
			{
				checkbox_main.prop('checked',false);
			}

			checkbox_main.unbind('click').click(function()
			{
				if($(this).is(':checked'))
				{
					checkbox_sub.prop('checked',true);
				}else
				{
					checkbox_sub.prop('checked',false);
				}
			});
			checkbox_sub.unbind('click').click(function()
			{
				if($(this).is(':checked') && $('.wt_pf_history_checkbox_sub:checked').length==checkbox_sub.length)
				{
					checkbox_main.prop('checked',true);
				}else
				{
					checkbox_main.prop('checked',false);
				}
			});

			$('.wt_pf_bulk_action_btn').click(function(){
				if($('.wt_pf_history_checkbox_sub:checked').length>0 && $('.wt_pf_bulk_action option:selected').val()!="")
				{
					var cr_action=$('.wt_pf_bulk_action option:selected').val();
					if(cr_action=='delete')
					{
						if(confirm(wt_pf_history_basic_params.msgs.sure))
						{
							var id_arr=new Array();
							$('.wt_pf_history_checkbox_sub:checked').each(function(){
								id_arr.push($(this).val());
							});
							var delete_url=wt_pf_history_basic_params.delete_url.replace('_history_id_', id_arr.join(','));
							window.location.href=delete_url;
						}
					}
				}
			});                   
                        $('.wt_pf_bulk_action_logs_btn').click(function(){
				if($('.wt_pf_history_checkbox_sub:checked').length>0 && $('.wt_pf_bulk_action option:selected').val()!="")
				{
					var cr_action=$('.wt_pf_bulk_action option:selected').val();
					if(cr_action=='delete')
					{
						if(confirm(wt_pf_history_basic_params.msgs.sure))
						{
							var id_arr=new Array();
							$('.wt_pf_history_checkbox_sub:checked').each(function(){
								id_arr.push($(this).val());
							});
							var delete_url=wt_pf_history_basic_params.delete_url.replace('_log_file_', id_arr.join(','));
							window.location.href=delete_url;
						}
					}
				}
			});
		}
	}
	return wt_pf_basic_history;
	
})( jQuery );

jQuery(function() {			
	wt_pf_basic_history.Set();
});
