jQuery(document).ready(function () {
	

    $ = jQuery;

	//show and hide instructions

    $("#auth_help").click(function () {
        $("#auth_troubleshoot").toggle();
    });
	$("#conn_help").click(function () {
        $("#conn_troubleshoot").toggle();
    });
	
	$("#conn_help_user_mapping").click(function () {
        $("#conn_user_mapping_troubleshoot").toggle();
    });
	
	//show and hide attribute mapping instructions
    $("#toggle_am_content").click(function () {
        $("#show_am_content").toggle();
    });

	 //Instructions
    $("#mo_lla_help_curl_title").click(function () {
    	$("#mo_lla_help_curl_desc").slideToggle(400);
    });
	
	$("#mo_lla_help_mobile_auth_title").click(function () {
    	$("#mo_lla_help_mobile_auth_desc").slideToggle(400);
    });
	
	$("#mo_lla_help_disposable_title").click(function () {
    	$("#mo_lla_help_disposable_desc").slideToggle(400);
    });
	
	$("#mo_lla_help_strong_pass_title").click(function () {
    	$("#mo_lla_help_strong_pass_desc").slideToggle(400);
    });
	
	$("#mo_lla_help_adv_user_ver_title").click(function () {
    	$("#mo_lla_help_adv_user_ver_desc").slideToggle(400);
    });
	
	$("#mo_lla_help_social_login_title").click(function () {
    	$("#mo_lla_help_social_login_desc").slideToggle(400);
    });
	
	$("#mo_lla_help_custom_template_title").click(function () {
    	$("#mo_lla_help_custom_template_desc").slideToggle(400);
    });

    $(".feedback").click(function(){
         ajaxCall("dissmissfeedback",".feedback-notice",true);
    });

    $(".bruteforce-dissmiss").click(function(){
        ajaxCall("dissmissbruteforce",".enable-bruteforce-notice",true);
    });

    $(".whitelist_self").click(function(){
        ajaxCall("whitelistself",".whitelistself-notice",true);
    });

    $(".enable_brute_force").click(function(){
        window.location.href = "admin.php?page=mo_lla_login_and_spam";      
    });

    $(".llas_premium_option :input").attr("disabled",true);

    $('#molla-slide-support').click(function(){
            $('.molla-support-div').toggleClass('molla-support-closed');
            $('#molla-slide-support').toggleClass('dashicons-arrow-right-alt2');
            $('#molla-slide-support').toggleClass('molla-support-icon');
            $('#molla-support-section').toggleClass('molla-support-section');
    });

    $('#footer-upgrade').html('');
    $('#footer-upgrade').click(()=>{
        window.open('https://wordpress.org/plugins/miniorange-limit-login-attempts/#reviews', '_blank');
    })
});


function ajaxCall(option,element,hide)
{
    jQuery.ajax({
            url: "",
            type: "GET",
            data: "option="+option,
            crossDomain: !0,
            dataType: "json",
            contentType: "application/json; charset=utf-8",
            success: function(o) {
                if (hide!=undefined)
                    jQuery(element).slideUp();
            },
            error: function(o, e, n) {}
        });
}