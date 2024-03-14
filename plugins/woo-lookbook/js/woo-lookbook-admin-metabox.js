'use strict';
jQuery(document).ready(function () {
	jQuery('#post').bind('submit', function (e) {

		jQuery('.wlb-product').each(function () {
			var i_val = jQuery(this).val();
			if (!i_val) {
				jQuery(this).focus();
				jQuery('.wlb-error').html('Please select product');
				var id = jQuery(this).closest('.wlb-data').attr('data-id');
				if (id) {
					jQuery('.wlb-node').removeClass('wlb-active');
					jQuery('.wlb-data').removeClass('wlb-active');
					jQuery('.wlb-node-' + id).addClass('wlb-active');
					jQuery('.wlb-item-' + id).addClass('wlb-active');
				}
				e.preventDefault();
			}
		})
	});
	jQuery('.wlb-add-new').bind('click', function () {
		/*Add field*/
		if (jQuery('.wlb-data').length > 1) {
			return;
		}
		var u_id = Date.now();
		var data = '<div class="wlb-data wlb-item-' + u_id + '" data-id="' + u_id + '">'
			+ '<div class="wlb-field">'
			+ '<select class="wlb-product wlb-product-search" name="wlb_params[product_id][]" data-placeholder="Search your product"> </select>'
			+ '</div>'
			+ '<div class="wlb-field">'
			+ 'X <input class="wlb-x" type="number" name="wlb_params[x][]" value="0" min="0" max="100" step="0.01" />'
			+ 'Y <input class="wlb-y" type="number" name="wlb_params[y][]" value="0" min="0" max="100" step="0.01" />'
			+ '</div>'
			+ '<span class="wlb-remove">x</span>'
			+ '</div>';
		jQuery('.wlb-table').append(data);
		/*Add node*/
		var node_data = '<span class="wlb-node wlb-node-' + u_id + '" data-id="' + u_id + '">+</span>';
		jQuery('.wlb-image-container').append(node_data);
		drag();

	});
	jQuery(".wlb-shortcode input").click(function () {
		jQuery(this).select();
	});

	function drag() {
		/*Select2 search product*/
		jQuery('.wlb-table .wlb-product-search').select2({
			placeholder       : "Please fill in your product title",
			ajax              : {
				url           : "admin-ajax.php?action=wlb_search_product",
				dataType      : 'json',
				type          : "GET",
				quietMillis   : 50,
				delay         : 250,
				data          : function (params) {
					return {
						keyword: params.term,
						nonce: _wlb_params.nonce,
					};
				},
				processResults: function (data) {
					return {
						results: data
					};
				},
				cache         : true
			},
			escapeMarkup      : function (markup) {
				return markup;
			}, // let our custom formatter work
			minimumInputLength: 1
		});
		/*Init drag node*/
		jQuery('.wlb-node').draggable({
			containment: '.wlb-image-container',
			cursor     : "crosshair",
			drag       : function (event, ui) {
				var image = jQuery(this).closest('.wlb-image-container');
				var width = image.width();
				var height = image.height();

				var xPos = (ui.position.left / width) * 100;
				var yPos = (ui.position.top / height) * 100;

				var id = jQuery(this).attr('data-id');
				if (id) {
					jQuery('.wlb-node').removeClass('wlb-active');
					jQuery('.wlb-data').removeClass('wlb-active');
					jQuery('.wlb-node-' + id).addClass('wlb-active');
					jQuery('.wlb-item-' + id).addClass('wlb-active');
					var item = jQuery('.wlb-item-' + id);
					item.find('.wlb-x').val(xPos.toFixed(2));
					item.find('.wlb-y').val(yPos.toFixed(2));
				}
			}
		});
		jQuery('.wlb-remove').unbind('click');
		jQuery('.wlb-remove').bind('click', function () {
			if (confirm("Would you want to remove this node?")) {
				var id = jQuery(this).closest('.wlb-data').attr('data-id');
				jQuery(this).closest('.wlb-data').remove();
				jQuery('.wlb-node-' + id).remove();
			}
		});
		jQuery('.wlb-node,.wlb-data').unbind('click');
		jQuery('.wlb-node,.wlb-data').bind('click', function () {
			var id = jQuery(this).attr('data-id');
			jQuery('.wlb-node').removeClass('wlb-active');
			jQuery('.wlb-data').removeClass('wlb-active');
			jQuery('.wlb-node-' + id).addClass('wlb-active');
			jQuery('.wlb-item-' + id).addClass('wlb-active');
		})
		jQuery('.wlb-x,.wlb-y').unbind('change');
		jQuery('.wlb-x,.wlb-y').bind('change', function () {
			var pos_x = jQuery(this).closest('.wlb-data').find('.wlb-x').val();
			var pos_y = jQuery(this).closest('.wlb-data').find('.wlb-y').val();

			jQuery('.wlb-node.wlb-active').css({
				'left': pos_x + '%',
				'top' : pos_y + '%'
			})
		});
	}

	/*Init node*/
	function init() {
		drag();
		// Set all variables to be used in scope
		var frame,
			metaBox = jQuery('#woo-lookbook.postbox'), // Your meta box id here
			addImgLink = metaBox.find('.wlb-upload-img'),
			delImgLink = metaBox.find('.wlb-delete-img'),
			imgContainer = metaBox.find('.wlb-image-container'),
			imgIdInput = metaBox.find('.wlb-image-data');

		// ADD IMAGE LINK
		addImgLink.on('click', function (event) {

			event.preventDefault();

			// If the media frame already exists, reopen it.
			if (frame) {
				frame.open();
				return;
			}

			// Create a new media frame
			frame = wp.media({
				title   : 'Select or Upload Media',
				button  : {
					text: 'Use image'
				},
				multiple: false  // Set to true to allow multiple files to be selected
			});


			// When an image is selected in the media frame...
			frame.on('select', function () {

				// Get media attachment details from the frame state
				var attachment = frame.state().get('selection').first().toJSON();

				// Send the attachment URL to our custom image input field.
				imgContainer.append('<img class="wlb-image" src="' + attachment.url + '" alt="" style="max-width:100%;"/>');

				// Send the attachment id to our hidden input
				imgIdInput.val(attachment.id);

				// Hide the add image link
				addImgLink.addClass('hidden');

				// Unhide the remove image link
				delImgLink.removeClass('hidden');
			});

			// Finally, open the modal on click
			frame.open();
		});


		// DELETE IMAGE LINK
		delImgLink.on('click', function (event) {

			event.preventDefault();

			// Clear out the preview image
			imgContainer.html('');

			// Un-hide the add image link
			addImgLink.removeClass('hidden');

			// Hide the delete image link
			delImgLink.addClass('hidden');

			// Delete the image id from the hidden input
			imgIdInput.val('');
			jQuery('.wlb-table').html('');

		});

	}

	init();

});