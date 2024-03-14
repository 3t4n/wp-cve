 
(function($){
do_cek_resi = function(){
$('#cekbutton').on('click',function(){
	$('#cekresiresult').text('');
	$('#cekbutton').text('Checking..');
	$('#cekbutton').attr('disabled','disabled');
	$('#cekresiresult').append('<p align=center style=\"margin-top: 25px;\">Give me one second</p>');
	$.get(PT_Ajax_Cek_Resi.ajaxurl, 
	{   
		action: 'get_track_awb',
		nextNonce: PT_Ajax_Cek_Resi.nextNonce,
		awb: $('#noresi').val(),
		kurir: $('#kurir').val()
	},  
	function(data,status){
		data=data.substring(0,data.length-1);
		data=data+"<script type='text/javascript'>jQuery(document).ready(function($) {$('#cekbutton').removeAttr('disabled');$('#cekbutton').text('Cek Resi');});</script>";
		$('#cekresiresult').text('');
		$("#cekresiresult").append(data);
	});
});
}
})(jQuery);
