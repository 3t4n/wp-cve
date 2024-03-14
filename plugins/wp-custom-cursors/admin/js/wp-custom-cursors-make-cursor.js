/*!
 * WP Custom Cursors | WordPress Cursor Plugin
 * Author: Hamid Reza Sepehr
 * License: GPLv2 or later
 * License https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * "Open your hands if you want to be held." -Rumi
 *
 */ 

jQuery(document).ready(function($){

	// Main Object to hold cursor options
	let cursorObj = {}, hoverArray = [], submitError = false, unsavedForm = false, updateFlag = false;
	if (typeof hovers !== 'undefined') {hoverArray = hovers;}

	$('#create_cursor_form').on('submit', function(event){
		if (unsavedForm) {
			fillHoverInput(reset = false, update = updateFlag);
		}
		if (submitError) {
			event.preventDefault();
		}
		else {
			let cursorType = $('#cursor_type').val();
			if (cursorType == 'text') {
				cursorType = $('#normal_text_type').val()
			}
			let inputs = $(`#normal_${cursorType}_options *[data-name]`);
			inputs.each(function(){
				cursorObj[$(this).attr('data-name')] = $(this).val();
			});

			hoverArray.forEach(function(item, index){
				if (item == 'del') {hoverArray.splice(index, 1);}
			});
			$('#hover_cursors').val(JSON.stringify(hoverArray));

			$('#cursor_options').val(JSON.stringify(cursorObj));
		}

	});


	// Add Hover Cursor
	$('#add_hover_btn').on('click', function(){
		updateFlag = false;
		if (unsavedForm) {
			fillHoverInput(true, updateFlag);
		}
		if (!submitError) {
			$('#hover_list .hover-badge').each(function(){
				$(this).removeClass('active');
			});

			$('#hover_list').append($('<div class="hover-badge active"><div class="cursor-pointer select-hover" data-id="' + (hoverArray.length) + '">' + $('#hover_cursor_type').val().charAt(0).toUpperCase() + $('#hover_cursor_type').val().slice(1) + '</div><i class="ms-1 ri-close-fill remove-hover cursor-pointer" data-id="' + (hoverArray.length) + '"></i></div>'));

			$('#hover_cursor_type').val('default').trigger('change');
		}
		
		
	});

	// Remove Hover Cursor
	$(document).on('click','.remove-hover',function(){
		let id = $(this).attr('data-id');
		hoverArray[id] = 'del';
		$(this).parents('.hover-badge').remove();
	});

	// Select Hover Cursor
	$(document).on('click','.select-hover',function(){
		if (unsavedForm) {
			fillHoverInput(true, true);
		}
		if (!submitError) {
			let id = $(this).attr('data-id'), 
				hoverType = hoverArray[id]['hover_type'];
			$(this).parent().addClass('active').siblings().removeClass('active');

			if(hoverType == 'horizontal') {
				$('#hover_cursor_type').val('text');
			}
			else {
				$('#hover_cursor_type').val(hoverType).trigger('change');
			}

			let inputs = $(`#hover_${hoverType}_options *[data-name]`);
			inputs.each(function(){
				if ($(this).is(':checkbox')) {
					if (hoverArray[id][$(this).attr('data-name')] == 'on') {
						$(this).val(hoverArray[id][$(this).attr('data-name')]).prop('checked', true).trigger('change')
					}
				}
				else {
					$(this).val(hoverArray[id][$(this).attr('data-name')]);
					$(this).trigger('change');
				}
			});
			unsavedForm = false;
		}
	});

	// Functions
	function displayOptions(containerId, optionsId ) {
		jQuery('#'+containerId).children().fadeOut(0);
		jQuery('#'+optionsId).fadeIn(0);
	}

	function fillHoverInput(reset = true, update = false) {
		let hoverObj = {}, 
			hoverType = $('#hover_cursor_type').val();
		if (hoverType == 'text') {
			hoverType = $('#hover_text_type').val()
		}
		hoverObj['hover_type'] = hoverType;

		let hoverElementSelected = false;
		$(`#hover_${hoverType}_options .hover-elements-wrapper input[type=checkbox]`).each(function(){
			if ($(this).is(':checked')) {
				hoverElementSelected = true;
				return false;
			}
		});
		if ($(`#hover_${hoverType}_options .hover-elements-wrapper`).length == 0) {
			hoverElementSelected = true;
		}

		if (hoverElementSelected) {
			let inputs = $(`#hover_${hoverType}_options *[data-name]`);
			inputs.each(function(){
				hoverObj[$(this).attr('data-name')] = $(this).val();
			});

			if (reset) {
				inputs.each(function(){
					if ($(this).is(':checkbox')) {
						if ($(this).is(':checked')) {
							$(this).val($(this).attr('data-default')).prop('checked', false).trigger('change')
						}
					}
					else {
						$(this).val($(this).attr('data-default')).trigger('change');
					}
				});
			}
			
			if (update) {
				let arrayId = $('#hover_list .hover-badge.active .select-hover').attr('data-id');
				hoverArray[arrayId] = hoverObj;
			}
			else {
				hoverArray.push(hoverObj);	
			}
			unsavedForm = false;
			
			$('#hover_cursors').val(JSON.stringify(hoverArray));
			
		}
		else {
			submitError = true;
			$(`#hover_${hoverType}_options .hover-elements-wrapper`).addClass("show-error");
		}
	}


	// Editor
	$('#cursor_type, #hover_cursor_type').on('change', function(){
		let state = $(this).data('state');
		if ($(this).val() == 'text') {
			let textType = $('#' + state + '_text_type').val();
			displayOptions(state + '_preview_container', state + "_" + textType + "_preview");
			displayOptions('options_container', state + "_" + textType + "_options");
		}
		else {
			displayOptions(state + '_preview_container', state + "_" + $(this).val() + "_preview");
			displayOptions('options_container', state + "_" + $(this).val() + "_options");
		}
	});
	$('#hover_cursor_type').on('change', function(){
		$('#hover_list > .hover-badge.active .select-hover').html($(this).val().charAt(0).toUpperCase() + $(this).val().slice(1));
		if ($(this).val() == 'default') {
			$('#add_hover_btn').fadeOut();
		}
		else {
			$('#add_hover_btn').fadeIn();
		}
		unsavedForm = true;
	});

	$('#hover_shape_options [data-name], #hover_image_options [data-name], #hover_text_options [data-name], #hover_horizontal_options [data-name], #hover_snap_options [data-name]').on('change', function(){
		unsavedForm = true;
		updateFlag = true;
	});
	$('#hover_shape_options input[type=range], #hover_image_options input[type=range], #hover_text_options input[type=range], #hover_horizontal_options input[type=range], #hover_snap_options input[type=range]').on('input', function(){
		unsavedForm = true;
		updateFlag = true;
	});

	// Normal/Hover Preview Click
	$('.cursor-preview').on('click', function(){
		let state = $(this).data('state'); 
		$("#normal_preview_container, #hover_preview_container").removeClass('active-preview');
		$("#" + state + "_preview_container").addClass('active-preview');

		displayOptions('cursor_type_container', state + "_cursor_type_wrapper");
		displayOptions('options_container', state + "_" + $(this).data('preview-type') + "_options");
	});


	let imageContainer = $('#normal_image_preview, #hover_image_preview');
	imageContainer.each(function(){
		let state = $(this).data('state'),
			uploadBtn = $(this).find('.image-upload-btn'),
			imageWrapper = $(this).find('.uploaded-image-wrapper'),
			uploadedImage = $(this).find('.uploaded-image'),
			imageUrlInput = $(`#${state}_image_url_input`),
			delBtn = $(this).find('.image-del-btn'),
			clickPointInfo = $(this).find('.click-point-info'),
			imageHeight = $(`#${state}_image_height`),
			clickPointInput = $(`#${state}_click_point_input`),
			clickPoint = $(this).find('.click-point'),
			mediaUploader;

		uploadBtn.click(function(e){
			e.preventDefault();
			if (mediaUploader) {
				mediaUploader.open();
				return;
			}
			mediaUploader = wp.media.frames.file_frame = wp.media({
				title: 'Choose Cursor Image',
				button: {
					text: 'Select Cursor'
				}, 
				multiple: false }
			);
			mediaUploader.on('select', function() {
				var attachment = mediaUploader.state().get('selection').first().toJSON();

				uploadBtn.addClass('visually-hidden');
				imageWrapper.removeClass('visually-hidden');
				uploadedImage.attr('src', attachment.url);
				delBtn.removeClass('visually-hidden');
				imageUrlInput.val(attachment.url);
				clickPointInfo.removeClass('visually-hidden');
				setTimeout(function(){
					imageHeight.val(uploadedImage.height());
				}, 1000);
			});
			mediaUploader.open();
		});

		// Delete Button
		delBtn.click(function(){
			uploadBtn.removeClass('visually-hidden');
			imageWrapper.addClass('visually-hidden');
			uploadedImage.attr('src', '');
			imageUrlInput.val('');
			$(this).addClass('visually-hidden');
			clickPointInfo.addClass('visually-hidden');
			return false;
		});

		// Click Point
		let newImageEl = uploadedImage, newImageElWidth, newImageElHeight;
		
		let position = { x: 0, y: 0 }
		interact(clickPoint[0]).draggable({
		  	listeners: {
			    start (event) {
			      	newImageElWidth = Math.round(newImageEl.width());
			      	newImageElHeight = Math.round(newImageEl.height());
			    },
		    	move (event) {
		      		position.x += event.dx
		      		position.y += event.dy
		      		event.target.style.transform =`translate(${position.x}px, ${position.y}px)`;
		      		let pEl = document.getElementById(`${state}_image_preview`),
		      			cpx = Math.round((Math.round(position.x) * 100) / newImageElWidth),
		      			cpy = Math.round((Math.round(position.y) * 100) / newImageElHeight);
		        	clickPointInput.val(cpx + "," + cpy).trigger('change');
		        	pEl.style.setProperty('--click-point-x', (cpx * -1) + "%");
		        	pEl.style.setProperty('--click-point-y', (cpy * -1) + "%");
		    	},
		  	}
		});


		// Image Width Change Calculate and Set Height
		$('.image-width-input').on('input', function(){
			let state = $(this).data('state');
			$(`#${state}_image_height`).val(uploadedImage.height());
		});
		
	});

	
	// Range/Number Change
	$('input[data-apply]:not([type=text]):not([type=checkbox])').on('input change', function(){
		let element = $(this).attr('data-apply'),
		variable = $(this).attr('data-variable'),
		unit = $(this).attr('data-unit') || '';
		$('#' + element)[0].style.setProperty('--' + variable, $(this).val() + unit);
	});


	// Color Change
    $('.wp-custom-cursor-color-picker').spectrum({
		type: "component",
		move: function(color) {
			let element = $(this).attr('data-apply'),
			variable = $(this).attr('data-variable');
		    $('#' + element)[0].style.setProperty('--' + variable, color.toRgbString()); 
		}
	});


	// Select Change
	$('select[data-apply]').on('change', function(){
		let element = $(this).attr('data-apply'),
		variable = $(this).attr('data-variable');
		$('#' + element)[0].style.setProperty('--' + variable, $(this).val());
	});

	// Toggle Buttons
	$('input[type=checkbox]').on('change', function(){
		if($(this).is(':checked')) {
			$(this).val('on');
			if ($(this).data('toggle')) {
				let toggle = $(this).data('toggle');
				$(this).parent().next(`.${toggle}`).fadeIn();
			}
			if ($(this).data('off')) {
				let off = $(this).data('off').split(',');
				off.forEach(function(id){
					if($(`#${id}`).is(':checked')) {
						$(`#${id}`).prop('checked', false).val('off');
						if ($(`#${id}`).data('toggle')) {
							let toggle = $(`#${id}`).data('toggle');
							$(`#${id}`).parent().next(`.${toggle}`).fadeOut();
						}
					}
				})
			}
			if ($(this).data('hide-error')) {
				submitError = false;
				let element = $(this).data('hide-error').split(',')[0],
					className = $(this).data('hide-error').split(',')[1];
					$(this).parents(`.${element}`).removeClass(`${className}`);
			}
		}
		else {
			$(this).val('off'); 
			if ($(this).data('toggle')) {
				let toggle = $(this).data('toggle');
				$(this).parent().next(`.${toggle}`).fadeOut();
			}
		}
	});

	// Show/Hide Image Background
	$('#image_background, #hover_image_background').on('change', function(){
		if(!$(this).is(':checked')) {
			let state = $(this).data('state');
			$(`#${state}_image_background_color`).val('transparent');
			$(`#${state}_uploaded_image_wrapper`)[0].style.setProperty('--image-background-color', 'transparent'); 
		}
	});

	// Change Text Type
	$('[data-select]').on('change', function(){
		let element = $(this).attr('data-select'), state = $(this).data('state');
		displayOptions(state + "_" + 'preview_container', state + "_" + $(this).val() + "_preview");
		displayOptions('options_container', state + "_" + $(this).val() + "_options");
		$('#' + element).val($(this).val());
	});

	// Change Cursor Text 
	$('input[type=text]').on('input', function(){
		let element = $(this).attr('data-apply');
		$('#' + element).html($(this).val());
	});

	// Show/Hide Dot
	$('#show_dot, #hover_show_dot').on('change', function(){
		let element = $(this).attr('data-apply');
		if($(this).is(':checked')) {
			$('#' + element)[0].style.setProperty('--dot-display', 'block'); 
		}
		else {
			$('#' + element)[0].style.setProperty('--dot-display', 'none');
		}
	});

	// Range Input Change
	$('input[type=range]').each(function(){
		if( $(this).next().attr('type') == 'number' ) {
			$(this).on('input change', function(){
				$(this).next().val($(this).val());
			});

			$(this).next().on('input change', function(){
				$(this).prev().val($(this).val());
			});
		}
	});

	// Enabling Bootstrap Tooltips
	const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
	const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

});
