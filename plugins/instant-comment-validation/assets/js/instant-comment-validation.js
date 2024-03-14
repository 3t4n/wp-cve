/*!-------------------------------------------------------
 * Instant Comment Validation - v1.0 - 30/6/2014
 * http://wordpress.org/plugins/instant-comment-validation/
 * Copyright (c) 2014 Mrinal Kanti Roy; License: GPLv2 or later
---------------------------------------------------------*/
jQuery.validator.addMethod("better_email", function(value, element) {
  // a better (but not 100% perfect) email validation
  return this.optional( element ) || /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/.test( value );
}, 'Please enter a valid email address.');

jQuery(document).ready(function($) {
	$('#commentform').validate({	 
		rules: {
		  author: {
			required: true,
			minlength: 2
		  },		 
		  email: {
			required: true,
			email: true
		  },
		  comment: {
			required: true,
			minlength: 20
		  }
		},		 
		messages: {
		  author: "Please enter your name",
		  email: "Please enter a valid email address",
		  comment: "Please type your comment"
		},		 
		errorElement: "div",
		errorPlacement: function(error, element) {
		  element.after(error);
		}	 
	});
});
