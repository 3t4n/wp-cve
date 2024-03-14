jQuery(document).ready(function($) {
	$('#commentform').validate({
		rules: {
			comment: {
				required: true
			},
			
			author: {
				required: true,
				minlength: 2
			},

			email: {
				required: true,
				email: true
			}
		},

		messages: {
		  comment: "Please enter your comment.",
		  author: "Please enter your name.",
		  email: {
		        required: "Please enter your email address.",
				email: "Please enter a valid email address."
		  }
		},

		errorElement: "div",
		errorPlacement: function(error, element) {
		  element.after(error);
		}

	});
});
