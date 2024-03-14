(function( $ ) {
	'use strict';
	
	$( document ).ready(function() {
        $('.tranzly_select_li').on('click', function(event) {
        	jQuery('.tranzly_select_li').addClass('tranzly_active');
        	var tranzly_tag=$(this).attr('tranzly_tag');
        	var tranzly_value =$(this).attr('tranzly_value');
        	if (tranzly_tag=='selected') {

        	}else{
        		$('.tranzly_select_li').attr('tranzly_tag', '');
        		$(this).attr('tranzly_tag', 'selected');
        		$('.tranzly_select_li').removeClass('tranzly_active');
        		$(this).addClass('tranzly_active');
        		var tranzly_url=$('.tranzly_url').val();
        		window.location.href=tranzly_url+'?lang='+tranzly_value;
        	}
        });

        $('.tranzly_select_li_page').on('click', function(event) {
        	jQuery('.tranzly_select_li_page').addClass('tranzly_active');
        	var tranzly_tag=$(this).attr('tranzly_tag');
        	var tranzly_value =$(this).attr('tranzly_value');
        	if (tranzly_tag=='selected') {

        	}else{
        		$('.tranzly_select_li_page').attr('tranzly_tag', '');
        		$(this).attr('tranzly_tag', 'selected');
        		$('.tranzly_select_li_page').removeClass('tranzly_active');
        		$(this).addClass('tranzly_active');
        		window.location.href=tranzly_value;
        	}
        });


         $('.cnopen').on('click', function(event) {
        	jQuery('.tranzly_select_li_page').addClass('tranzly_active');
        });


    });


})( jQuery );


// function tranzly_select_ul_click(){
// 	jQuery('.tranzly_select_li').addClass('tranzly_active');
// }


function tranzly_language_for_page(url){
	var cn=jQuery('#tranzly_language_switcher').val();
	var tranzly_page_id=jQuery('#tranzly_page_id').val();
	// var clocation=window.location;
	//window.location.href=url+'?lang='+cn;

	jQuery.post(tranzly_plugin_vars.ajaxurl,{
		'action': 'tranzly_public_tranzly_ajax',
		'param': 'find_post_page',
		'tranzly_page_id':tranzly_page_id
	}, function(response){
		
		console.log(response);
		//var newResponse = JSON.parse(response);
		// if (newResponse.success=='success') {
		// 	window.location.href=newResponse.location_url;
		// }
		
	});



}

function tranzly_language_switcher(url){
	var cn=jQuery('#tranzly_language_switcher').val();
	// var clocation=window.location;
	alert(cn);
	window.location.href=url+'?lang='+cn;
}

function tranzly_language_page(){
	var cn=jQuery('#tranzly_language_switcher').val();
	// var clocation=window.location;
	if (cn!='') {
		window.location.href=cn;	
	}
	
}