jQuery(window).ready(function(){

	jQuery('#connect-ztb, [zb-plugin="zb_zbapp"]').click(function(){
		setTimeout(function(){
			checkToken();
		},3000);
	});

	function checkToken(){
		jQuery.ajax({
			type: "POST",
			url: ZTB_BASE_URL+'/customer/access/checkAuth?callback=?',
			data: {type:'check auth'},
			xhrFields: {
      			withCredentials: true
   			},
   			dataType: "jsonp",
			success: function(response){
				if(response.auth == true){
					jQuery.ajax({
						type: "POST",
						url: ZBT_WP_ADMIN_URL+'admin-ajax.php?action=update_zb_zbapp_code',
						data: {
							domain:response.domain,
							access:response.access,
							customer:response.customer,
							email:response.email,
							token:response.token,
						},
						success: function(response){
							location.reload();
						}
					});
					
				}else{
					setTimeout(function(){
						checkToken();
					},3000);
				}
			}
		});
	}

});