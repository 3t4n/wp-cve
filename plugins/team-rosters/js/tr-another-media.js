
/*----------------------------------------------------------------------------
 * tr-another-media.js
 *  Supports uploading files to the WordPress media library
 *
 *	MSTW Wordpress Plugins (http://shoalsummitsolutions.com)
 *	Copyright 2017-22 Mark O'Donnell (mark@shoalsummitsolutions.com)
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *--------------------------------------------------------------------------*/

jQuery(document).ready(function($)
{
    var _custom_media = true,
        _orig_send_attachment = wp.media.editor.send.attachment;

    // ADJUST THIS to match the correct button
    $('.uploader .button').click(function(e) 
	//$('input#_wpse_82857_button').click(function(e) 
    {
        var send_attachment_bkp = wp.media.editor.send.attachment;

        var button = $(this);
        var id = button.attr('id').replace('_btn', '');
		//var id = button.attr('id');
		//alert( id );
        _custom_media = true;
        wp.media.editor.send.attachment = function(props, attachment)
        {
            if ( _custom_media ) 
            {
                $("#"+id).val(attachment.url);
				$("#"+id+"_img").attr("src", attachment.url );
				
            } else {
                return _orig_send_attachment.apply( this, [props, attachment] );
            };
        }

        wp.media.editor.open(button);
        return false;
    });

    $('.add_media').on('click', function()
    {
        _custom_media = false;
    });
	
	$('.mstw_logo_text').blur(function(e) {
		var text = $(this);
		var id = text.attr('id');
		var value = text.val();
		//alert( "#"+id+"_img" );
		//alert( value );
		$("#"+id+"_img").attr("src", value );
	
	});
});