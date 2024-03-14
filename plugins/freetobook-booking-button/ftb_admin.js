

function checkVis(radio)
{
	customStyle=(radio.value=='custom')?'table-row':'none';
	buttonStyle=(radio.value=='button')?'table-row':'none';
	
	jQuery('#button-upload').css('display',customStyle);
	jQuery('#button-chooser').css('display',buttonStyle);
}


jQuery(document).ready(function() {
var ww = jQuery('#fake_post_id').attr('value');
window.original_send_to_editor = window.send_to_editor;
window.send_to_editor_clone = function(html){
imgurl = jQuery('img',html).attr('src');
jQuery('#upload_image').val(imgurl);
tb_remove();
}
jQuery('#add_image').click(function() {
window.send_to_editor=window.original_send_to_editor;
tb_show('', 'media-upload.php?post_id=' + ww + '&type=image&TB_iframe=true');
return false;
});
jQuery('#upload_image_button').click(function() {
window.send_to_editor=window.send_to_editor_clone;
formfield = jQuery('#upload_image').attr('name');
tb_show('', 'media-upload.php?post_id=' + ww + '&type=image&TB_iframe=true');
return false;
});
});