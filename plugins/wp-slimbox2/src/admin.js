var store = '';
jQuery(function($) {
	$(".keys").keyup(function (e) {
		if(!$("#wp_slimbox_manual_key").is(':checked')) {
			var evt = (window.event) ? event.keyCode : e.keyCode;
			var strTest=new Array();
			$(".keys").each(function () {strTest=strTest.concat((this.value).split(' ').join('').split(','));})
			if($.inArray(evt+'', strTest)!=-1) {
				this.value=store.replace(/.$/,'')
				alert($("#wp_slimbox_key_defined").val());
			} else this.value=store+evt;
			return false;
		}
	}).keydown(function (e) {
		if(!$("#wp_slimbox_manual_key").is(':checked')) {
			store = (this.value == ''?'':this.value+',');
			return false;
		}
	});
	$('#picker').farbtastic('#wp_slimbox_overlayColor');
});