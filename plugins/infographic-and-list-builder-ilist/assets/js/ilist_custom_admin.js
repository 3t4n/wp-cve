jQuery(document).ready(function($)
{
	
        
    var sbdstoredNoticeId = localStorage.getItem('qcld_ilist_Notice_set');
    var qcld_ilist_Notice_time_set = localStorage.getItem('qcld_ilist_Notice_time_set');

    var notice_current_time = Math.round(new Date().getTime() / 1000);

    if ('ilist-msg' == sbdstoredNoticeId && qcld_ilist_Notice_time_set > notice_current_time  ) {
        $('#message-ilist').css({'display': 'none'});
    }

    $(document).on('click', '#message-ilist .notice-dismiss', function(e){

        var currentDom = $(this);
        var currentWrap = currentDom.closest('.notice');
        currentWrap.css({'display': 'none'});
        localStorage.setItem('qcld_ilist_Notice_set', 'ilist-msg');
        var ts = Math.round(new Date().getTime() / 1000);
        var tsYesterday = ts + (24 * 3600);
        localStorage.setItem('qcld_ilist_Notice_time_set', tsYesterday);
        console.log(tsYesterday)

    });
		

	$(document).on('click', '#qcld_sl_template_text', function(e){
		$.post(
			ajaxurl,
			{
				action : 'show_ilist_templates',
				list_type: 'textlist',
				security: qcld_ilist_ajax.qcld_ajax_nonce
			},
			function(data){
				$('#wpwrap').append(data);
			}
		)
	});
	$(document).on('click', '#qcld_sl_template_mix', function(e){
		$('#ajax-modal').show();
		$.post(
			ajaxurl,
			{
				action : 'show_ilist_templates',
				list_type: 'infographic',
				security: qcld_ilist_ajax.qcld_ajax_nonce
				
			},
			function(data){
				
				$('#wpwrap').append(data);
				
			}
		)
	});

	$(document).on('click', '#qcld_sl_template_image', function(e){
		
		$.post(
			ajaxurl,
			{
				action : 'show_ilist_templates',
				list_type: 'graphiclist',
				security: qcld_ilist_ajax.qcld_ajax_nonce
				
			},
			function(data){
				
				$('#wpwrap').append(data);
				
			}
		)
	});
	
	
	$(document).on( 'click', '.modal-content-template .close', function(){
        $(this).parent().parent().remove();
    });
	
	$('#ilist_shortcode_generator_meta').on('click', function(){
		$.post(
			ajaxurl,
			{
				action : 'show_shortcodes',
				security: qcld_ilist_ajax.qcld_ajax_nonce
			},
			function(data){
				$('#wpwrap').append(data);
			}
		)
	});
	
	var selector = '';

	$(document).on( 'click', '.ilist_copy_close', function(){
		if(!isGutenbergActive()){
			$(this).parent().parent().parent().parent().parent().remove();
		}
    });
	
    $(document).on( 'click', '.ilist-modal-content .close', function(){
		
			$(this).parent().parent().remove();
		
		
    }).on( 'click', '#add_shortcode',function(){
      var post = $('#ilist_post_select_shortcode').val();
      var upvote = $('.upvote_switcher:checked').val();
      var disable_popup_switcher = $('.disable_popup_switcher:checked').val();
      var column = $('#ilist_column_shortcode').val();
     var item_orderby = $('#ilist_item_orderby').val();
	  
	  if(typeof(main_title)=='undefined'){
		  main_title = 0;
	  }
	  if(typeof(upvote)=='undefined'){
		  upvote = 'off';
	  }
	  if(typeof(disable_popup_switcher)=='undefined'){
		  disable_popup_switcher = 'false';
	  }else{
		  disable_popup_switcher = 'true';
	  }
	  
		var shortcodedata = '[qcld-ilist mode="one"';
	  if(post!==''){
		  shortcodedata +=' list_id="'+post+'"';
		  if(upvote!==''){
			  shortcodedata +=' upvote="'+upvote+'"';
		  }
		  if(disable_popup_switcher!==''){
			  shortcodedata +=' disable_lightbox="'+disable_popup_switcher+'"';
		  }
		  
		  if(column!==''){
			  shortcodedata +=' column="'+column+'"';
		  }
		  if(item_orderby!==''){
			  shortcodedata +=' item_orderby="'+item_orderby+'"';
		  }
		  
		shortcodedata +=']';
		//tinyMCE.activeEditor.selection.setContent(shortcodedata);
		// $('#ilist-modal').remove();
		 
		 $('.sm_shortcode_list').hide();
		$('.ilist_shortcode_container').show();
		$('#ilist_shortcode_container').val(shortcodedata);
		$('.ilist_copy_close').attr('short-data', shortcodedata);
		$('#ilist_shortcode_container').select();
		document.execCommand('copy');
		 
		 
	  }else{
		  alert('Please Select Post!');
		  return;
	  }

    });
	
	
	$(document).on( 'click', '.ilist_list_elem', function(){
        var data = $(this).attr('data');
		var gettype = $('.modal-content-template').attr('data');
		var getid= '';
		if(gettype=='graphiclist'){
			getid = 'qcld_sl_template_image';
		}else if(gettype=='infographic'){
			getid = 'qcld_sl_template_mix';
		}else{
			getid = 'qcld_sl_template_text';
		}
		$('#'+getid).val(data);
		$(this).parent().parent().parent().parent().remove();
    })
	
	
	var iDiv = document.createElement('div');
	iDiv.id = 'ilistimagecontainer1';
	iDiv.className = 'ilistimagecontainer1';
	
	var url = $('#ilist_path').attr('data-path');
	var html = '<a href="https://www.quantumcloud.com/products/infographic-maker-ilist/" target="_blank"><img style="width: 100%;max-width:100%;height:auto;" src="'+url+'/js/ilist-pro.jpg" /></a>';
	iDiv.innerHTML = html;
	if(document.getElementsByClassName('cmb2-id-qcld-text-group')[0]){
		document.getElementsByClassName('cmb2-id-qcld-text-group')[0].appendChild(iDiv);
	}
	
	$(document).on( 'click', 'a.ilist-fancybox-show', function(event){		        	

		var post_id = $(this).attr('post-id');

		$(this).addClass('qcld-loading');

		var data = {
            'post_id': post_id,
            'action': 'ilist_embaded_list_url_info',
            'security': qcld_ilist_ajax.qcld_ajax_nonce
        };

         setTimeout(function(){

        jQuery.post(qcld_ilist_ajax.admin_url, data, function (response) {

	        $('.fancyboxIframe').attr('href', response.html);
	        $('#qcilist-shortcode-meta-box .inside div').html(response.short_code);

	        setTimeout(function(){

	        	$("a.ilist-fancybox-show").removeClass('qcld-loading');

				$('.fancyboxIframe').trigger('click');

			}, 1500);

	    }); 

	    }, 3000);


    });


	$(function() {
		$(".fancyboxIframe").fancybox({
			maxWidth	: 850,
			maxHeight	: 800,
			fitToView	: true,
			width		: 850,
			height		: '90%',
			autoSize	: false,
			closeClick	: false,
            autoSize    : false,
            autoCenter  : true,
            padding: 0,
	    iframe: {
		   scrolling : 'yes',
		    preload   : true,
		    css : {
				margin : '0px'
			},
	    }
		});
	});


	$(document).on( 'click', ".qcld_openai_title_generate_btn", function(){

		var title 		= $('#title').val();
		var number 		= $('#qcld_openai_number').val();
		var post_id 	= $('#post_ID').val();

		if(title == ''){
			alert('Please add your title first.');
			//return false;
		}


        $(this).addClass('spinning');

		$.post(
			ajaxurl,
			{
				action 	: 'qcld_openai_title_generate_desc',
				title 	: title,
				number 	: number,
				post_id : post_id,
				security: qcld_ilist_ajax.qcld_ajax_nonce
				
			},
			function(data){

				// console.log(data);
    			$('.qcld_openai_title_generate_btn').removeClass('spinning');
    		
    			location.reload(true);
        
			}
		);


    });






	jQuery("#carousel").owlCarousel({
	  autoplay: false,
	  lazyLoad: true,
	  loop: true,
	  mouseDrag:false,
	  margin: 20,
	  responsiveClass: true,
	  dots:false,
	  autoplayTimeout: 7000,
	  smartSpeed: 800,
	  nav: true,
	  responsive: {
	    0: {
	      items: 1
	    },

	    600: {
	      items: 1
	    },

	    1024: {
	      items: 1
	    },

	    1366: {
	      items: 1
	    }
	  }
	});

		

	$('.ilist_click_handle').on('click', function(e){
		e.preventDefault();
		var obj = $(this);
		container_id = obj.attr('href');
		$('.ilist_click_handle').each(function(){
			$(this).removeClass('nav-tab-active');
			$($(this).attr('href')).hide();
		})
		obj.addClass('nav-tab-active');
		$(container_id).show();
	})
	var hash = window.location.hash;
	if(hash!=''){
		$('.ilist_click_handle').each(function(){
			
			$($(this).attr('href')).hide();
			if($(this).attr('href')==hash){
				$(this).removeClass('nav-tab-active').addClass('nav-tab-active');
			}else{
				$(this).removeClass('nav-tab-active');
			}
		})
		$(hash).show();
	}
	




});

function isGutenbergActive() {
    return typeof wp !== 'undefined' && typeof wp.blocks !== 'undefined';
}
