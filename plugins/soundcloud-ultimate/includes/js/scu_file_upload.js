jQuery(document).ready(function($) {
$('#upload_track_button').click(function() {
 tb_show('Select A Track For Upload To SoundCloud', 'media-upload.php?referer=wpshq_scu_plugin_options&amp;TB_iframe=true&amp;post_id=0', false);
 return false;
});
 
window.send_to_editor = function(html) {
	var fileelement = $(html);
    audiourl = fileelement.attr('href');
 $('#upload_track').val(audiourl);
 tb_remove();
}
});