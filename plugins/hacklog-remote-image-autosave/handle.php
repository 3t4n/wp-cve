<?php
/**
 * @package Hacklog Remote Image Autosave
 * @encoding UTF-8
 * @author 荒野无灯 <HuangYeWuDeng>
 * @link http://ihacklog.com
 * @copyright Copyright (C) 2012 荒野无灯
 * @license http://www.gnu.org/licenses/
 */

require __DIR__ . '/header.php';
@header ( 'Content-Type: ' . get_option ( 'html_type' ) . '; charset=' . get_option ( 'blog_charset' ) );

$GLOBALS ['body_id'] = 'media-upload';
iframe_header ( __ ( 'Hacklog Remote Images Autodown', hacklog_remote_image_autosave::textdomain ), false );
?>
<style type="text/css" media="screen">
.error {
	color:#F00;
}
</style>

<script type="text/javascript">
//Preload images
(new Image()).src = "<?php echo WP_PLUGIN_URL . '/hacklog-remote-image-autosave/images/ok_24.png';
	?>";
(new Image()).src = "<?php echo WP_PLUGIN_URL . '/hacklog-remote-image-autosave/images/downloading.gif';
	?>";	
</script>

<script type="text/javascript">
	var hacklog_ria_debug = false;
	var check_down_interval = 1000;
	var img_arr = [];
	var mce = typeof(parent.tinyMCE) != 'undefined' ? parent.tinyMCE.activeEditor : false;
	var check_down = function()
				{
					var button_obj = document.getElementById('replace-token');
					var len = jQuery('#img-cnt').val();
					if( img_arr.length == len )
					{
						button_obj.click();
						button_obj.style.display= 'none';
						jQuery('#all-done').slideDown("slow");
					}
					else
					{
						setTimeout("check_down();",check_down_interval);
					}
				};
			
// @see http://www.tinymce.com/wiki.php/API3:class.tinymce.dom.Selection
// @see http://www.tinymce.com/wiki.php/API3:method.tinymce.dom.Selection.getContent
jQuery(function($){

	$.ajaxSetup({
  			url:'<?php echo $url;?>',
			type:'post',
			dataType: 'json',
			async: false,
			cache: false,
			timeout: 300*1000,
			complete: function(jqXHR, textStatus){
				hacklog_ria_debug && alert('complete hook called. textStatus: ' + textStatus);
				if( textStatus == 'success')
				{

				}
			},
			statusCode: 
			{
    			404: function() {  hacklog_ria_debug && alert('404 page not found.'); },
    			500: function() {  hacklog_ria_debug && alert('500 Internal Server Error!'); }
			},			
			error: function(jqXHR, textStatus, errorThrown){
				hacklog_ria_debug && alert('error hook called. textStatus: ' + textStatus +"\n" + 'errorThrown: ' + errorThrown);
			}

	});

var getContent = function(){
	return mce.getContent({format : 'raw'});
};

var setContent = function(new_content )
{
	return mce.setContent(new_content,{format : 'raw'});
};

var set_status_downloading = function(id)
{
		var download_img = '<img src="<?php echo WP_PLUGIN_URL . '/hacklog-remote-image-autosave/images/downloading.gif';
		?>" alt="downloading">下载中...';
	if($('#img-status-' + id ).length > 0 )
	{
		$('#img-status-' + id ).html(download_img);
	}
	else
	{	
		$('#img-'+ id ).parent().append('<span id="img-status-' + id + '">' + download_img + '</span>');
	}
};

var set_status_done = function(id)
{
	$('#img-status-'+ id ).html('<img src="<?php echo WP_PLUGIN_URL . '/hacklog-remote-image-autosave/images/ok_24.png';?>" alt="done">');
};

var set_status_failed = function(id,msg)
{
	//$('#img-status-'+ id + ' img').hide();
	$('#img-status-'+ id ).html('<strong>Error:</strong><span class="error">' + msg + '</span>&nbsp;&nbsp;<a href="javascript:void(0);" rel="' + id + '" class="retry">Retry</a>');
};

$('#replace-token').click(
		function(e)
		{
			e.stopPropagation();
			replace_token();
			return false;
		});

$('.retry').live('click',function(e){
	e.stopPropagation();
	var id = $(this).attr('rel');
	var post_id = $('#post_id').val();
	var url = $('#img-'+ id ).val();
	//alert( 'id:' + id + '    post_id:' + post_id + '    url:' + url );
	//$('#img-status-'+ id ).remove();
	down_single_img(id,post_id,url);
	return false;
	});	

var replace_token =  function()
{
	var content = getContent();
	var len = img_arr.length;
	for(var i=0; i< len; ++i)
	{
				var token = $('#img-' + img_arr[i].id ).attr('rel');
				var img_html =  img_arr[i].html;
				hacklog_ria_debug && console.log('token: '+ token);
				content = content.replace( token, img_html );
				hacklog_ria_debug && console.log('set new content:'+ content);

	}
	setContent( content );
};


	var down_single_img = function(id,post_id,url)
	{
		hacklog_ria_debug && console.log(url);
		set_status_downloading(id);

		$.ajax(
		{
			url: '<?php echo $url;?>?act=do_download',
			data: {'url': url, 'post_id': post_id},
			async: true,
			success: function(data,textStatus){
				//alert(textStatus + 'data: ' + data);
				if( 'ok' == data.status )
				{
				$('#img-'+ id ).val(data.src);
				//id ,token, data
				var token = $('#img-'+ id ).attr('rel');
				hacklog_ria_debug && console.log('down_single_img get token: ' + token);
				img_arr.push({'id':id,'token': token,'html':data.html});
				set_status_done(id);
				}
				else
				{
					set_status_failed(id,data.error_msg);
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				errorThrown = errorThrown ? errorThrown :'Fetch timeout';
				set_status_failed(id,errorThrown + '. Check your HTTP Server error log or PHP error log to see what happend.');
			}

		}
			);

	};

	var get_images = function()
	{

		//@see http://api.jquery.com/jQuery.ajax/
		var content = getContent();
		$.ajax({
			url:'<?php echo $url;?>?act=get_images',
			type:'post',
			data: {'content':  content},
			dataType: 'json',
			success: function(data,textStatus){
				//alert( data.content );
				if( data && data.status != 'no_img' )
				{
				//设置把图片置空后的内容
				setContent(data.content);
				hacklog_ria_debug && console.log('replaced content:  ' + data.content);
				//帖出图片信息
				var html = $('<ol>');
				for(var i=0;i<data.images.length;++i)
				{
					html.append('<li><input size="85" type="text" name="img[]" id="img-' + data.images[i].id + '" rel="' + data.images[i].token + '"  value="' + data.images[i].url + '" /></li>');
					//alert( data.images[i].url);
				}
				$('#image-list').html(html);
				$('#img-cnt').val( data.images.length );
				var post_id = $('#post_id').val();
				for(var i=0;i<data.images.length;++i)
				{
					var id =  data.images[i].id;
					down_single_img(id,post_id, data.images[i].url);
				}	
				setTimeout("check_down();",check_down_interval);
			}
			else
			{
				$('#image-list').html('<p style="font-size:24px;">No remote images to download!</p>');
				$('#img-cnt').val( 0 );
				$('#replace-token').attr('disabled',true);
				$('#replace-token').css('display','none');
			}

			}
		}

			);
	};

	if( !mce )
	{
		alert('This can only be run under tinyMCE editor!');
	}
	else
	{
		$('#hacklog-ria-form').show();
		get_images();
	}
});
</script>

<h3 id="all-done" style="display:none;color:#57d;margin:15px auto 0 40px;">All remote images has been downloaded.Have fun -_-.</h3>
<form id="hacklog-ria-form" action="" method="post" accept-charset="utf-8" style="display:none;margin: 0 auto 8px; padding: 10px;">
	<input type="hidden" id="post_id" name="post_id" value="<?php echo $post_id;?>"> 
	<input type="hidden" id="img-cnt" name="img_cnt" value="0">
	<div id="image-list"></div>
	<input type="button" class="button-primary"	style="position: absolute; right: 60px;" id="replace-token"
		name="update" value="OK">
</form>
<?php
require dirname ( __FILE__) . '/footer.php';
?>