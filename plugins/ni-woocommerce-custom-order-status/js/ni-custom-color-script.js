// JavaScript Document

"use strict";

jQuery(function($){

/*Color Picker*/	
//$('.order_status_color').wpColorPicker();	
jQuery('.order_status_color').wpColorPicker();

var slug = function(str) {
		  	str = str.replace(/^\s+|\s+$/g, ''); // trim
		  	str = str.toLowerCase();
		
		  	// remove accents, swap ñ for n, etc
		  	var from = "ãàáäâẽèéëêìíïîõòóöôùúüûñç·/_,:;";
		  	var to   = "aaaaaeeeeeiiiiooooouuuunc------";
		  	for (var i=0, l=from.length ; i<l ; i++) {
		  	  	str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
		  	}
		
		  	str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
		  	  	.replace(/\s+/g, '-') // collapse whitespace and replace by -
		  	  	.replace(/-+/g, '-'); // collapse dashes
		
		  	return str;
		},
		slug_field			= $('#_ni_order_status_slug'),
		title				= $('#title');

	slug_field.prop('readonly', true);

	if(slug_field.val().length < 1){
		
		
		// Fix for drafted statuses
		if (title.val().length > 0) {
			slug_field.val(slug(title.val()));
		}
		
		
		title.on('keyup', function(){
			
			if (title.val().length <= 17){
			slug_field.val(slug(title.val()));
			}else{
						//alert("else");
			}	
			
			//alert(slug_field.val().length);
			//slug_field.val(slug(title.val()));
		});
	}
});