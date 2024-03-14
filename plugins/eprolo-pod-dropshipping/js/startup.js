

//初始化
jQuery(document).ready(function() {

var server_domain = 'https://inkedjoy.com/';
//检查是否已经链接 eprolo_pod_connected=1
var eprolo_pod_connected = jQuery("#eprolo_pod_connected").val();
var eprolo_pod_user_id= jQuery("#eprolo_pod_user_id").val();
var eprolo_pod_shop_url = jQuery("#eprolo_pod_shop_url").val();
var eprolo_pod_store_token= jQuery("#eprolo_pod_store_token").val();
var domain = window.location.protocol+"//"+window.location.host;    

if(eprolo_pod_connected==1){//已链接、

	var url =  server_domain+'prod-api/woocommerce/woocommerce_installPre.html?domain='+domain+'&user_id='+eprolo_pod_user_id;
	jQuery("#Go_to_EPROLO_POD_DIV").show();
	if(eprolo_pod_store_token == null || eprolo_pod_store_token == '' || eprolo_pod_store_token.length == ''){//无token，未授权
		  jQuery("#go_to_url").attr("href",url); //更换去授权的链接
		  jQuery("#go_to_url").html("Connect to Inkedjoy POD"); //更换去授权的链接
		  jQuery("#Go_to_EPROL_tip").html("You are currently not connected to your Inkedjoy POD account.");
	}else{//有token，已经授权,直接去登陆
	
	
	}
	
}else{//未链接,可刷新+加用key链接
 jQuery("#Go_to_EPROLO_POD_DIV").hide();
 jQuery("#POD_DisconnectIV").hide();
 jQuery("#Connect_to_EPROLO_POD").attr("href",server_domain+"prod-api/woocommerce/woocommerce_installPre.html?domain="+domain); 
 jQuery("#eprolo_pod_connect_keyDIV").show();
}

});

let EPROLO_POD_ERROR_MSG = 'Network error, please try again or contact Inkedjoy POD';

//清掉eprolo_pod_connected
function eprolo_pod_disconnect() {
		//删除本地key等值，调用服务器API（清楚key等值，注销账号，加入操作记录）
       var eprolo_pod_user_id= jQuery("#eprolo_pod_user_id").val();   
       var eprolo_pod_nonce_field = jQuery("#eprolo_pod_nonce_field").val(); 
			 jQuery.ajax({
        type: 'POST',
        url: ajax_startup.ajaxUrl, // ajaxurl为内置js变量，值为"/wp-admin/admin-ajax.php"
        data: {
 			'action': 'eprolo_pod_disconnect',
            'eprolo_pod_user_id':eprolo_pod_user_id,
            'eprolo_pod_nonce_field': eprolo_pod_nonce_field
        },
        success: function (data) {
            if(data.data.code==0){
             		alert(data.data.msg);
             		location.reload() ;
            }else{
            	alert(data.data.msg || EPROLO_POD_ERROR_MSG);
                if (data.data.code == -1) {
                    location.reload();
                }
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
	
}

function eprolo_pod_connect_key() {
		var domain = window.location.protocol+"//"+window.location.host;
	   var  user_id = jQuery("#eprolo_pod_connect_key").val();
       var eprolo_pod_nonce_field = jQuery("#eprolo_pod_nonce_field").val(); 
	   if(user_id==''){
         alert('Auth key is required');
	   		 return;
	   }
		 jQuery.ajax({
        type: 'POST',
        url: ajax_startup.ajaxUrl, // ajaxurl为内置js变量，值为"/wp-admin/admin-ajax.php"
        data: {
 			'action': 'eprolo_pod_connect_key',
            'user_id':user_id,
            'domain':domain,
            'eprolo_pod_nonce_field': eprolo_pod_nonce_field
        },
        success: function (data) {
            //console.log( data);
            if(data.data.code==0){
             		alert(data.data.msg);
             		jQuery("#eprolo_pod_connect_keyDIV").hide();
             		location.reload() ;
            }else{
            		alert(data.data.msg || EPROLO_POD_ERROR_MSG);
                    if (data.data.code == -1) {
                        location.reload();
                    }
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
}


function eprolo_pod_reflsh() {
		// 根据user_id刷新token,刷新domain
	 var eprolo_pod_user_id= jQuery("#eprolo_pod_user_id").val();  
	 var domain = window.location.protocol+"//"+window.location.host;
   var eprolo_pod_store_token= jQuery("#eprolo_pod_store_token").val();
   var eprolo_pod_nonce_field = jQuery("#eprolo_pod_nonce_field").val();
    jQuery.ajax({
        type: 'POST',
        url: ajax_startup.ajaxUrl, // ajaxurl为内置js变量，值为"/wp-admin/admin-ajax.php"
        data: {
 			'action': 'eprolo_pod_reflsh',
            'user_id':eprolo_pod_user_id,
            'domain':domain,
            'eprolo_pod_store_token':eprolo_pod_store_token,
            'eprolo_pod_nonce_field': eprolo_pod_nonce_field
        },
        success: function (data) {
            console.log( data);
            if(data.data.code==0){
             		alert(data.data.msg);
             		jQuery("#eprolo_pod_connect_keyDIV").hide();
             		location.reload();
            }else{
            		alert(data.data.msg || EPROLO_POD_ERROR_MSG);
                    if (data.data.code == -1) {
                        location.reload();
                    }
            }
        },
        error: function (data) {
            console.log(data);
        }
    });
    
}
