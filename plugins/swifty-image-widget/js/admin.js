(function ($) {
	"use strict";
	$(function () {

		$("body").on("change", ".swifty-img-size", function(e){
			if($(this).val() == "custom") {
				$(this).parent().next().show();
			} else {
				$(this).parent().next().hide();
			}
		});

		// Add New Image
		$("body").on("click", ".swifty_add_img", function(e){
			e.preventDefault();
			var widget_holder = $(this).closest('.widget-inside');
			var clone = widget_holder.find('.swifty_imgs_clone').clone();
			$('.swifty_img_holder').append(clone.html());
		});

		// Remove image
		$("body").on("click", ".swifty_remove_img", function(e){
			e.preventDefault();
			$(this).parent().remove();
		});

		//Reattach sortable on save and add
		jQuery(document).on('widget-updated widget-added', function(e, widget){
			$(".swifty_img_holder").sortable({
				cursor:"move",
				axis:"y"
			});
		});
		// Envoke Sortable
		$(".swifty_img_holder").sortable({
			cursor:"move",
			axis:"y"
		});

		var frame,
			imgContainer,
			imgIdInput;

		//Image Frame
		$("body").on("click",".swifty_img_holder .swifty_add_image", function(e) {
			
			var $this = $(this);
			imgContainer = $this.parent().find(".imgContainer");
			imgIdInput = $this.parent().find('.imgIdInput');

			e.preventDefault();

		    // Create a new media frame
		    frame = wp.media({
		      title: 'Select Image',
		      button: {
		        text: 'Use This Image'
		      },
		      library : { type : 'image' },
		      multiple: false
		    });

		    

		    // When an image is selected in the media frame...
		    frame.on( 'select', function() {

		      // Get media attachment details from the frame state
		      var attachment = frame.state().get('selection').first().toJSON();

		      // Send the attachment URL to our custom image input field.
		      imgContainer.html( '<img src="'+attachment.url+'" alt="" style="max-width:100%;"/>' );

		      // Send the attachment id to our hidden input
		      imgIdInput.val( attachment.id );

		      // Remove image button
		      $this.text("Edit Image");

		    });

		     // When an image is already selected in the media frame...
		    frame.on( 'open', function() {
		      	var selection = frame.state().get('selection');
				
				//Get current image
				var attachment = wp.media.attachment(imgIdInput.val());
				attachment.fetch();
				
				//Preselect in media frame
				selection.add( attachment ? [ attachment ] : [] );
		    });

			frame.open();
			
		});

	});
}(jQuery));