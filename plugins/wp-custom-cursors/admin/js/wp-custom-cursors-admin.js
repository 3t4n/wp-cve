/*!
 * WP Custom Cursors | WordPress Cursor Plugin
 * Author: Hamid Reza Sepehr
 *
 * "Open your hands if you want to be held." -Rumi
 *
 */ 


jQuery(document).ready(function($){
    // Form Wizard Initialization
    let addNewForm = $('#add_new_form');
	addNewForm.formToWizard({
	    nextBtnClass: 'btn btn-primary next btn-sm',
	    prevBtnClass: 'btn btn-default prev me-2 btn-sm',
	    buttonTag:    'button',
	    validateBeforeNext: function(form, step) {
	        var stepIsValid = true;
	        // var validator = form.validate();
	        // $(':input', step).each( function(index) {
	        //     var xy = validator.element(this);
	        //     stepIsValid = stepIsValid && (typeof xy == 'undefined' || xy);
	        // });
	        return stepIsValid;
	    },
	    progress: function (i, count) {
	        $('.progress-complete').width(''+(i/(count-1)*100)+'%');
	    }
	});

	// Preview Cursor Scripts
	let elementTop = 0, elementLeft = 0, hx = 0, hy = 0;
	let getMousePosition = (e) => {
	    hx = e.clientX;
        hy = e.clientY;
        if (stopFlag) {
        	hx = elementLeft;
        	hy = elementTop;
        }
	    return { x : e.clientX, y : e.clientY,  hx:hx, hy:hy  }
	},
	mousePosition = {x:0, y:0, hx:0, hy:0},
	previewWrapper = $('#wt-preview'),
	calcTop = 0, calcLeft = 0, paddingTop = 0, paddingLeft = 0,
	stopFlag = false,
	body = $('body');

	body.on('pointermove', function(ev) {mousePosition = getMousePosition(ev)});
	createCursor();
	function createCursor() {
		let cursorWrapper = $('<div class="wpcc-cursor">'),
			cursorEl1 = $('<div class="cursor-el1">'), 
			cursorEl2 = $('<div class="cursor-el2">'),
			cursorTypeInput = $('#cursor_type_input');

		cursorWrapper.append(cursorEl1);
		cursorWrapper.append(cursorEl2);
		previewWrapper.append(cursorWrapper);

		let cursorShape = 1,
		cursorWidth = $('#cursor_size_input').val(),
		cursorColor = $('#cursor_color').val(),
		blendingMode = $('#blending_mode').find(":selected").val();

		let cursorShapeRadius = $('[name = cursor_shape]');
		cursorShapeRadius.each(function(index, el) {
			if ($(this).is(':checked')) {
				cursorShape = $(this).val();
			}
		});

		let isCreatedCursor = cursorShape.includes('created'),
			currentCreatedCursorId = cursorShape.substring(8);
		if (isCreatedCursor) {
			$('#shape_cursor_options').fadeOut();
			[...cursors].forEach(function(createdCursor){
				if(createdCursor.cursor_id == currentCreatedCursorId) {
					cursorWrapper.addClass('cursor-created-' + createdCursor.cursor_id);
					switch(createdCursor.cursor_type){
						case 'shape':
							cursorWrapper.css({'--fe-width' : `${createdCursor.cursor_options.fe_width}px` , '--fe-height' : `${createdCursor.cursor_options.fe_height}px` , '--fe-color' : `${createdCursor.cursor_options.fe_color}` , '--fe-radius' : `${createdCursor.cursor_options.fe_radius}px`, '--fe-border' : `${createdCursor.cursor_options.fe_border_width}px`, '--fe-border-color' : `${createdCursor.cursor_options.fe_border_color}`, '--fe-duration' : `${createdCursor.cursor_options.fe_duration}ms`, '--fe-timing' : `${createdCursor.cursor_options.fe_timing}`, '--fe-blending' : `${createdCursor.cursor_options.fe_blending}`, '--fe-zindex' : `${createdCursor.cursor_options.fe_zindex}`, '--se-width' : `${createdCursor.cursor_options.se_width}px` , '--se-height' : `${createdCursor.cursor_options.se_height}px` , '--se-color' : `${createdCursor.cursor_options.se_color}` , '--se-radius' : `${createdCursor.cursor_options.se_radius}px`, '--se-border' : `${createdCursor.cursor_options.se_border_width}px`, '--se-border-color' : `${createdCursor.cursor_options.se_border_color}`, '--se-duration' : `${createdCursor.cursor_options.se_duration}ms`, '--se-timing' : `${createdCursor.cursor_options.se_timing}`, '--se-blending' : `${createdCursor.cursor_options.se_blending}`, '--se-zindex' : `${createdCursor.cursor_options.se_zindex}`});
						break;
						case 'image':
							let imageCursor = $('<img>'),
							clickPointOption = createdCursor.cursor_options.click_point.split(','),
							clickPointX = ( Number(clickPointOption[0]) * Number(createdCursor.cursor_options.width) ) / 100,
							clickPointY = ( Number(clickPointOption[1]) * Number(createdCursor.cursor_options.height) ) / 100;
							imageCursor.prop('src', createdCursor.cursor_options.image_url);
							cursorEl1.append(imageCursor);
							cursorWrapper.addClass('cursor-image');

							if (createdCursor.cursor_options.background != 'off') {
								paddingTop = createdCursor.cursor_options.padding;
								paddingLeft = createdCursor.cursor_options.padding;
							}

							calcTop = ( Number(paddingTop) + clickPointY ) * -1;
							calcLeft = ( Number(paddingLeft) + clickPointX ) * -1;

							cursorWrapper.css({'--width' : `${createdCursor.cursor_options.width}px` , '--color' : `${createdCursor.cursor_options.color}` , '--radius' : `${createdCursor.cursor_options.radius}px` , '--padding' : `${createdCursor.cursor_options.padding}px`, '--blending' : `${createdCursor.cursor_options.blending}` });
						break;
						case 'text':
							if (createdCursor.cursor_options.text_type == 'horizontal') {
								let hrTextCursor = $(`<div class="horizontal-text">${createdCursor.cursor_options.hr_text}</div>`);
						    	cursorEl1.html(hrTextCursor);
						    	cursorWrapper.addClass('cursor-text');

						    	hrTextCursor.css({'--hr-padding' : `${createdCursor.cursor_options.hr_padding}px` , '--hr-width' : `${createdCursor.cursor_options.hr_width}px` , '--hr-transform' : `${createdCursor.cursor_options.hr_transform}` , '--hr-weight' : `${createdCursor.cursor_options.hr_weight}`, '--hr-color' : `${createdCursor.cursor_options.hr_color}`, '--hr-size' : `${createdCursor.cursor_options.hr_size}px`, '--hr-spacing' : `${createdCursor.cursor_options.hr_spacing}px`, '--hr-radius' : `${createdCursor.cursor_options.hr_radius}px`, '--bg-color' : `${createdCursor.cursor_options.hr_bgcolor}`, '--hr-backdrop' : `${createdCursor.cursor_options.hr_backdrop}(${createdCursor.cursor_options.hr_backdrop_amount})` });
						    	
							}
							else {
								let svgTextCursor = $(`<svg viewBox="0 0 500 500"><path d="M50,250c0-110.5,89.5-200,200-200s200,89.5,200,200s-89.5,200-200,200S50,360.5,50,250" id="textcircle" fill="none"></path><text dy="25" style=" font-size:70px;"><textPath xlink:href="#textcircle">${createdCursor.cursor_options.text}</textPath></text><circle cx="250" cy="250" r="10" id="svg_circle_node"/></svg>`);
						    	cursorEl1.html(svgTextCursor);
						    	cursorWrapper.addClass('cursor-text');

						    	svgTextCursor.css({'--dot-fill' : `${createdCursor.cursor_options.dot_color}` , '--text-width' : `${createdCursor.cursor_options.width}px` , '--text-transform' : `${createdCursor.cursor_options.text_transform}` , '--font-weight' : `${createdCursor.cursor_options.font_weight}`, '--text-color' : `${createdCursor.cursor_options.text_color}`, '--font-size' : `${createdCursor.cursor_options.font_size}px`, '--word-spacing' : `${createdCursor.cursor_options.word_spacing}px`, '--animation-name' : `${createdCursor.cursor_options.animation}`, '--animation-duration' : `${createdCursor.cursor_options.animation_duration}s`, '--dot-width' : `${createdCursor.cursor_options.dot_width}px`});
					    	}
						break;
					}
					createdCursor.hover_cursors.forEach(function(hover){
			    		switch (hover.hover_type) {
				    		case 'shape':
				    			createHoverShape(hover, cursorWrapper, cursorEl1);
				    		break;
					    	case 'image':
					    		createHoverImage(hover, cursorWrapper, cursorEl1);
					    	break;
						    case 'text':
						    	createHoverText(hover, cursorWrapper, cursorEl1);
						    break;
							case 'horizontal':
								createHoverHorizontal(hover, cursorWrapper, cursorEl1);
							break;
							case 'snap':
								createHoverSnap(hover, cursorWrapper, cursorEl1);
							break;

			    		}
			    	});
				}
			});
		}
		else {
			cursorWrapper.css({'--fe-width' : `${cursorWidth}px` , '--fe-height' : `${cursorWidth}px` , '--se-width' : `${cursorWidth}px` , '--se-height' : `${cursorWidth}px` , '--fe-color' : `${cursorColor}` , '--se-color' : `${cursorColor}` , '--fe-blending' : `${blendingMode}` , '--se-blending' : `${blendingMode}`, '--fe-border-radius' : '50%' });
			cursorWrapper.addClass('cursor-' + cursorShape);
			let innerLinks = previewWrapper.find('a');
			innerLinks.each(function() {
		        $(this).on('mouseenter', function() {
		        	cursorWrapper.addClass('link-hover');
		        } );
		        $(this).on('mouseleave', function() {
		        	cursorWrapper.removeClass('link-hover');
		        } );
		    }); 
		}

		previewWrapper.on('mouseenter', function() {
			cursorWrapper.addClass('active');
		});

		previewWrapper.on('mouseleave', function() {
			cursorWrapper.removeClass('active');
		}); 

		// previewWrapper.on('pointermove', function() {
			
		// });

		requestAnimationFrame(renderCursor);
		function renderCursor() {
   			cursorEl1.css('transform' , `translate(${mousePosition.hx + calcLeft}px, ${mousePosition.hy + calcTop}px)`);
   			cursorEl2.css('transform' , `translate(${mousePosition.x}px, ${mousePosition.y}px)`);

			requestAnimationFrame(renderCursor);
		}

		// Change Cursor Shape
		$('[name=cursor_shape]').on('click', function(){
			calcTop = 0; paddingTop = 0; calcLeft = 0; paddingLeft = 0;
			cursorWrapper.removeClass();
			if (cursorEl1.children()) {
				cursorEl1.empty();
			}
			cursorWrapper.addClass(`wpcc-cursor cursor-${$(this).val()}`);

			
			//clear all preview event listeners
			//check if this is a created cursor
			//add new event listeners

			$('#wt-preview button, #wt-preview a, #wt-preview img').off('mouseenter').off('mouseleave');

			if ($(this).attr('data-type')) {
				let id = $(this).data('id');
				[...cursors].forEach(function(cursor){
					if(cursor.cursor_id == id) {
						let hovers = cursor.hover_cursors;
						console.log(hovers)
						hovers.forEach(function(hover){
							switch(hover.hover_type) {
								case 'shape':
									createHoverShape(hover, cursorWrapper, cursorEl1);
								break;

								case 'image':
									createHoverImage(hover, cursorWrapper, cursorEl1);
								break;

								case 'text':
									createHoverText(hover, cursorWrapper, cursorEl1);
								break;

								case 'horizontal':
									createHoverHorizontal(hover, cursorWrapper, cursorEl1);
								break;

								case 'snap':
									createHoverSnap(hover, cursorWrapper, cursorEl1);
								break;
							}
						});
					}
				});
			}

			// This is a custom created cursor
			if ($(this).attr('data-type')) {
				let dataType = $(this).attr('data-type'),
					cursorId = $(this).attr('data-id'),
					isCreatedCursor = true;
					currentCreatedCursorId = cursorId; 
				$('#shape_cursor_options').fadeOut();
				switch(dataType){
				  case 'shape':
				    cursors.forEach(function(el){
						if( el.cursor_id == cursorId ) {
							cursorWrapper.removeAttr('style');
							cursorWrapper.css({'--fe-width' : `${el.cursor_options.fe_width}px` , '--fe-height' : `${el.cursor_options.fe_height}px` , '--fe-color' : `${el.cursor_options.fe_color}` , '--fe-radius' : `${el.cursor_options.fe_radius}px`, '--fe-border' : `${el.cursor_options.fe_border_width}px`, '--fe-border-color' : `${el.cursor_options.fe_border_color}`, '--fe-duration' : `${el.cursor_options.fe_duration}ms`, '--fe-timing' : `${el.cursor_options.fe_timing}`, '--fe-blending' : `${el.cursor_options.fe_blending}`, '--fe-zindex' : `${el.cursor_options.fe_zindex}`, '--se-width' : `${el.cursor_options.se_width}px` , '--se-height' : `${el.cursor_options.se_height}px` , '--se-color' : `${el.cursor_options.se_color}` , '--se-radius' : `${el.cursor_options.se_radius}px`, '--se-border' : `${el.cursor_options.se_border_width}px`, '--se-border-color' : `${el.cursor_options.se_border_color}`, '--se-duration' : `${el.cursor_options.se_duration}ms`, '--se-timing' : `${el.cursor_options.se_timing}`, '--se-blending' : `${el.cursor_options.se_blending}`, '--se-zindex' : `${el.cursor_options.se_zindex}`});
							$('#cursor_size_input, #cursor_size_range').val(el.cursor_options.fe_width);
							$('#blending_mode').val('normal');
						}
					});
					cursorTypeInput.val('shape');
					cursorTypeInput.attr('value', 'shape');
				  break;
				  case 'image':
				  	cursors.forEach(function(el){
						if( el.cursor_id == cursorId ) {
							let imageCursor = $('<img>'),
							clickPointOption = el.cursor_options.click_point.split(','),
							clickPointX = ( Number(clickPointOption[0]) * Number(el.cursor_options.width) ) / 100,
							clickPointY = ( Number(clickPointOption[1]) * Number(el.cursor_options.height) ) / 100;
							imageCursor.prop('src', el.cursor_options.image_url);
							cursorEl1.append(imageCursor);
							cursorWrapper.addClass('cursor-image');
							cursorWrapper.removeAttr('style');

							if (el.cursor_options.background != 'off') {
								paddingTop = el.cursor_options.padding;
								paddingLeft = el.cursor_options.padding;
							}

							calcTop = ( Number(paddingTop) + clickPointY ) * -1;
							calcLeft = ( Number(paddingLeft) + clickPointX ) * -1;

							cursorWrapper.css({'--width' : `${el.cursor_options.width}px` , '--color' : `${el.cursor_options.color}` , '--radius' : `${el.cursor_options.radius}px` , '--padding' : `${el.cursor_options.padding}px`, '--blending' : `${el.cursor_options.blending}` });
							$('#cursor_size_input, #cursor_size_range').val(el.cursor_options.width);
							$('#blending_mode').val(el.cursor_options.blending);
						}
					});
					cursorTypeInput.val('image');
					cursorTypeInput.attr('value', 'image');
				  break;
				  case 'text':
				  	 cursors.forEach(function(el){
						if( el.cursor_id == cursorId ) {
							let svgTextCursor = $(`<svg viewBox="0 0 500 500"><path d="M50,250c0-110.5,89.5-200,200-200s200,89.5,200,200s-89.5,200-200,200S50,360.5,50,250" id="textcircle" fill="none"></path><text dy="25" style=" font-size:70px;"><textPath xlink:href="#textcircle">${el.cursor_options.text}</textPath></text><circle cx="250" cy="250" r="10" id="svg_circle_node"/></svg>`);
					    	cursorEl1.html(svgTextCursor);
					    	cursorWrapper.addClass('cursor-text');
					    	cursorWrapper.removeAttr('style');

					    	svgTextCursor.css({'--dot-fill' : `${el.cursor_options.dot_color}` , '--text-width' : `${el.cursor_options.width}px` , '--text-transform' : `${el.cursor_options.text_transform}` , '--font-weight' : `${el.cursor_options.font_weight}`, '--text-color' : `${el.cursor_options.text_color}`, '--font-size' : `${el.cursor_options.font_size}px`, '--word-spacing' : `${el.cursor_options.word_spacing}px`, '--animation-name' : `${el.cursor_options.animation}`, '--animation-duration' : `${el.cursor_options.animation_duration}s`, '--dot-width' : `${el.cursor_options.dot_width}px`});
							$('#cursor_size_input, #cursor_size_range').val(el.cursor_options.width);
							$('#blending_mode').val(el.cursor_options.blending);
						}
					});
				  	cursorTypeInput.val('text');
				  	cursorTypeInput.attr('value', 'text');
				  break;
				  case 'horizontal':
				  	cursors.forEach(function(el){
						if( el.cursor_id == cursorId ) {
						  	let hrTextCursor = $(`<div class="horizontal-text">${el.cursor_options.hr_text}</div>`);
					    	cursorEl1.html(hrTextCursor);
					    	cursorWrapper.addClass('cursor-horizontal');
					    	cursorWrapper.removeAttr('style');

					    	hrTextCursor.css({'--hr-padding' : `${el.cursor_options.hr_padding}px` , '--hr-width' : `${el.cursor_options.hr_width}px` , '--hr-transform' : `${el.cursor_options.hr_transform}` , '--hr-weight' : `${el.cursor_options.hr_weight}`, '--hr-color' : `${el.cursor_options.hr_color}`, '--hr-size' : `${el.cursor_options.hr_size}px`, '--hr-spacing' : `${el.cursor_options.hr_spacing}px`, '--hr-radius' : `${el.cursor_options.hr_radius}px`, '--bg-color' : `${el.cursor_options.hr_bgcolor}`, '--hr-backdrop' : `${el.cursor_options.hr_backdrop}(${el.cursor_options.hr_backdrop_amount})` });
						  	$('#cursor_size_input, #cursor_size_range').val(el.cursor_options.width);
							$('#blending_mode').val(el.cursor_options.blending);
					  	}
					});
					cursorTypeInput.val('horizontal');
				  	cursorTypeInput.attr('value', 'horizontal');
			  	  break;
				}
			}
			
			// This is a plugin default cursor
			else {
				isCreatedCursor = false;
				$('#cursor_size_input, #cursor_size_range').val(30);
				let cursorWidth = $('#cursor_size_input').val(),
					cursorColor = $('#cursor_color').val(),
					blendingMode = $('#blending_mode').find(":selected").val();
				cursorWrapper.css({'--fe-width' : `${cursorWidth}px` ,  '--fe-height' : `${cursorWidth}px` , '--se-width' : `${cursorWidth}px` , '--se-height' : `${cursorWidth}px` , '--fe-color' : `${cursorColor}` , '--se-color' : `${cursorColor}` , '--fe-blending' : `${blendingMode}` , '--se-blending' : `${blendingMode}` ,  '--fe-border-radius' : '50%'});
				$('#shape_cursor_options').fadeIn();
				cursorTypeInput.val('shape');
		    	cursorTypeInput.attr('value', 'shape');

		    	let innerLinks = previewWrapper.find('a');
				innerLinks.each(function() {
			        $(this).on('mouseenter', function() {
			        	cursorWrapper.addClass('link-hover');
			        } );
			        $(this).on('mouseleave', function() {
			        	cursorWrapper.removeClass('link-hover');
			        } );
			    }); 
			}
		});

		// Change Show Default Cursor
		$('#default_cursor').on('change', function(){
			if($(this).is(':checked')) {
				previewWrapper.removeClass('no-cursor');
			}
			else {
				previewWrapper.addClass('no-cursor');	
			}
		});

		// Change Cursor Size
		$('#cursor_size_input, #cursor_size_range').on('input', function(){
			let newWidth = $(this).val();
			if (isCreatedCursor) {
				[...cursors].forEach(function(createdCursor){
					if(createdCursor.cursor_id == currentCreatedCursorId) {
						let feWidth, seWidth, feHeight, seHeight;
						if (createdCursor.fe_width >= createdCursor.se_width) {
							feWidth = newWidth;
							seWidth = (createdCursor.se_width * newWidth) / createdCursor.fe_width;
							feHeight = (createdCursor.fe_width * newWidth) / createdCursor.fe_height;
							seHeight = (createdCursor.se_height * newWidth) / createdCursor.fe_width;
						}
						else {
							seWidth = newWidth;
							feWidth = (createdCursor.fe_width * newWidth) / createdCursor.se_width;
							seHeight = (createdCursor.se_height * newWidth) / createdCursor.se_width;
							feHeight = (createdCursor.fe_height * newWidth) / createdCursor.se_width;
						}
						cursorWrapper.css({'--fe-width' : `${feWidth}px` , '--se-width' : `${seWidth}px`, '--fe-height' : `${feHeight}px`, '--se-height' : `${seHeight}px`});
					}
				});
			}
			else {
				cursorWrapper.css({'--fe-width' : `${$(this).val()}px` , '--se-width' : `${$(this).val()}px` , '--fe-height' : `${$(this).val()}px` , '--se-height' : `${$(this).val()}px`});
			}
		});

		// Change Color
		$('#cursor_color').on('change', function(){
			cursorWrapper.css({'--fe-color' : `${$(this).val()}` , '--se-color' : `${$(this).val()}`});
		});

		// Change Blending Mode
		$('#blending_mode').on('change', function(){
			cursorWrapper.css({'--fe-blending' : `${$(this).find(":selected").val()}` , '--se-blending' : `${$(this).find(":selected").val()}`});
		});

		function createHoverShape(hover, cursorWrapper, cursorEl1) {
			let shapeSelector = [];
			if (hover.buttons == "on") {shapeSelector.push('button')}
			if (hover.images == "on") {shapeSelector.push('img')}
			if (hover.links == "on") {shapeSelector.push('a')}
			if (hover.custom == "on") {shapeSelector.push(hover.selector)}


			shapeSelector.forEach(function(selectorItem){
				let hoverElement = $(`#wt-preview ${selectorItem}`);

				let currentStyles, currentChild = null, currentType, currentCalcTop, currentCalcLeft, currentClass;
				if (hoverElement.length) {
					hoverElement.on('mouseenter', function(){
						currentStyles = cursorWrapper.attr('style');
						if (cursorEl1.children().length > 0) {currentChild = cursorEl1.children();cursorEl1.children().remove() }
						cursorWrapper.removeAttr('style');
						currentCalcTop = calcTop;
						currentCalcLeft = calcLeft;
						calcTop = 0;
						calcLeft = 0;
						if (cursorWrapper.hasClass('cursor-text')) {currentClass = 'cursor-text'; cursorWrapper.removeClass('cursor-text');}
						if (cursorWrapper.hasClass('cursor-horizontal')) {currentClass = 'cursor-horizontal'; cursorWrapper.removeClass('cursor-horizontal');}
						if (cursorWrapper.hasClass('cursor-image')) {currentClass = 'cursor-image'; cursorWrapper.removeClass('cursor-image');}
						if (cursorWrapper.hasClass('cursor-snap')) {currentClass = 'cursor-snap'; cursorWrapper.removeClass('cursor-snap');}

						// cursorWrapper.classList.add(`cursor-${cursor.cursor_shape}`);
						cursorWrapper.css({'--fe-width' : `${hover.hover_fe_width}px` , '--fe-height' : `${hover.hover_fe_height}px` , '--fe-color' : `${hover.hover_fe_color}` , '--fe-radius' : `${hover.hover_fe_radius}px`, '--fe-border' : `${hover.hover_fe_border_width}px`, '--fe-border-color' : `${hover.hover_fe_border_color}`, '--fe-duration' : `${hover.hover_fe_duration}ms`, '--fe-timing' : `${hover.hover_fe_timing}`, '--fe-blending' : `${hover.hover_fe_blending}`, '--fe-zindex' : `${hover.hover_fe_zindex}`, '--se-width' : `${hover.hover_se_width}px` , '--se-height' : `${hover.hover_se_height}px` , '--se-color' : `${hover.hover_se_color}` , '--se-radius' : `${hover.hover_se_radius}px`, '--se-border' : `${hover.hover_se_border_width}px`, '--se-border-color' : `${hover.hover_se_border_color}`, '--se-duration' : `${hover.hover_se_duration}ms`, '--se-timing' : `${hover.hover_se_timing}`, '--se-blending' : `${hover.hover_se_blending}`, '--se-zindex' : `${hover.hover_se_zindex}`});

					});
					hoverElement.on('mouseleave', function(){
						cursorWrapper.attr('style', currentStyles);
						calcTop = currentCalcTop;
						calcLeft = currentCalcLeft;
						if (currentChild) {cursorEl1.append(currentChild);}
						cursorWrapper.addClass(currentClass);
						
					});
				}
			});
		}

		function createHoverImage(hover, cursorWrapper, cursorEl1) {
			let imageSelector = [];
			if (hover.buttons == "on") {imageSelector.push('button')}
			if (hover.images == "on") {imageSelector.push('img')}
			if (hover.links == "on") {imageSelector.push('a')}
			if (hover.custom == "on") {imageSelector.push(hover.selector)}

			let imageCursor = $('<img />');
			imageCursor.attr('src', hover.hover_image_url);

			let clickPointOption = hover.hover_click_point.split(','),
			clickPointX = ( Number(clickPointOption[0]) * Number(hover.width) ) / 100,
			clickPointY = ( Number(clickPointOption[1]) * Number(hover.height) ) / 100; 

			imageSelector.forEach(function(selectorItem){
				let hoverElement = $(`#wt-preview ${selectorItem}`);
				let currentStyles, currentChild = null, currentType, currentCalcTop, currentCalcLeft, currentClass;
				if (hoverElement.length) {
					hoverElement.on('mouseenter', function(){
						
						currentStyles = cursorWrapper.attr('style');

						if (cursorEl1.children().length > 0) {currentChild = cursorEl1.children();cursorEl1.children().remove() }
						cursorWrapper.removeAttr('style');
						cursorEl1.html(imageCursor);
						if (cursorWrapper.hasClass('cursor-text')) {currentClass = 'cursor-text'; cursorWrapper.removeClass('cursor-text');}
						if (cursorWrapper.hasClass('cursor-horizontal')) {currentClass = 'cursor-horizontal'; cursorWrapper.removeClass('cursor-horizontal');}
						if (cursorWrapper.hasClass('cursor-image')) {currentClass = 'cursor-image'; cursorWrapper.removeClass('cursor-image');}
						if (cursorWrapper.hasClass('cursor-snap')) {currentClass = 'cursor-snap'; cursorWrapper.removeClass('cursor-snap');}
						currentCalcTop = calcTop;
						currentCalcLeft = calcLeft;
						cursorWrapper.addClass('cursor-image');

						cursorWrapper.css({'--width': hover.width + "px", '--color': hover.color, '--radius': hover.radius + "px", '--blending': hover.blending});
						if (hover.background != 'off') {
							cursorWrapper.css({'--padding': hover.padding + "px"});
							paddingTop = hover.padding;
							paddingLeft = hover.padding;
						}

						calcTop = ( Number(paddingTop) + clickPointY ) * -1;
						calcLeft = ( Number(paddingLeft) + clickPointX ) * -1;
					});
					hoverElement.on('mouseleave', function(){
						cursorEl1.html('');
						cursorWrapper.attr('style', currentStyles);
						cursorWrapper.removeClass('cursor-image');
						cursorWrapper.addClass(currentClass);
						calcTop = currentCalcTop;
						calcLeft = currentCalcLeft;
						if (currentChild) {cursorEl1.append(currentChild);}
					});
				}
			});
		}

		function createHoverText(hover, cursorWrapper, cursorEl1) {
			let textSelector = [];
			if (hover.buttons == "on") {textSelector.push('button')}
			if (hover.images == "on") {textSelector.push('img')}
			if (hover.links == "on") {textSelector.push('a')}
			if (hover.custom == "on") {textSelector.push(hover.selector)}
			textSelector.forEach(function(selectorItem){
				let hoverElement = $(`#wt-preview ${selectorItem}`);
				let currentStyles, currentChild = null, currentType, currentCalcTop, currentCalcLeft, currentClass;
				if (hoverElement.length) {
					hoverElement.on('mouseenter', function(){
						currentStyles = cursorWrapper.attr('style');
						if (cursorEl1.children().length > 0) {currentChild = cursorEl1.children();cursorEl1.children().remove() }
						cursorWrapper.removeAttr('style');
						currentCalcTop = calcTop;
						currentCalcLeft = calcLeft;
						calcTop = 0;
						calcLeft = 0;

						let svgTextCursor = $(`<svg viewBox="0 0 500 500"><path d="M50,250c0-110.5,89.5-200,200-200s200,89.5,200,200s-89.5,200-200,200S50,360.5,50,250" id="textcircle" fill="none"></path><text dy="25" style=" font-size:70px;"><textPath xlink:href="#textcircle">${hover.hover_text}</textPath></text><circle cx="250" cy="250" r="10" id="svg_circle_node"/></svg>`);
				    	cursorEl1.html(svgTextCursor);
				    	if (cursorWrapper.hasClass('cursor-text')) {currentClass = 'cursor-text'; cursorWrapper.removeClass('cursor-text');}
						if (cursorWrapper.hasClass('cursor-horizontal')) {currentClass = 'cursor-horizontal'; cursorWrapper.removeClass('cursor-horizontal');}
						if (cursorWrapper.hasClass('cursor-image')) {currentClass = 'cursor-image'; cursorWrapper.removeClass('cursor-image');}
						if (cursorWrapper.hasClass('cursor-snap')) {currentClass = 'cursor-snap'; cursorWrapper.removeClass('cursor-snap');}

						cursorWrapper.addClass('cursor-text');

				    	cursorWrapper.css({'--dot-fill' : `${hover.dot_color}` , '--text-width' : `${hover.width}px` , '--text-transform' : `${hover.hover_text_transform}` , '--font-weight' : `${hover.hover_font_weight}`, '--text-color' : `${hover.hover_text_color}`, '--font-size' : `${hover.font_size}px`, '--word-spacing' : `${hover.hover_word_spacing}px`, '--animation-name' : `${hover.hover_animation}`, '--animation-duration' : `${hover.hover_animation_duration}s`, '--dot-width' : `${hover.hover_dot_width}px`});
					});
					hoverElement.on('mouseleave', function(){
						cursorEl1.html('');
						cursorWrapper.removeClass('cursor-text');
						cursorWrapper.addClass(currentClass)

						cursorWrapper.attr('style', currentStyles);
						if (currentChild) {cursorEl1.append(currentChild);}
						calcTop = currentCalcTop;
						calcLeft = currentCalcLeft;
					});
				}
			});
		}

		function createHoverHorizontal(hover, cursorWrapper, cursorEl1) {
			let hrSelector = [];
			if (hover.buttons == "on") {hrSelector.push('button')}
			if (hover.images == "on") {hrSelector.push('img')}
			if (hover.links == "on") {hrSelector.push('a')}
			if (hover.custom == "on") {hrSelector.push(hover.selector)}
			hrSelector.forEach(function(selectorItem){
				let currentStyles, currentChild = null, currentType, currentCalcTop, currentCalcLeft, currentClass;
				let hoverElement = $(`#wt-preview ${selectorItem}`);
				if (hoverElement.length) {
					hoverElement.on('mouseenter', function(){
						currentStyles = cursorWrapper.attr('style');
						if (cursorEl1.children().length > 0) {currentChild = cursorEl1.children();cursorEl1.children().remove() }
						currentCalcTop = calcTop;
						currentCalcLeft = calcLeft;
						calcTop = 0;
						calcLeft = 0;

						cursorWrapper.removeAttr('style');
						if (cursorWrapper.hasClass('cursor-text')) {currentClass = 'cursor-text'; cursorWrapper.removeClass('cursor-text');}
						if (cursorWrapper.hasClass('cursor-horizontal')) {currentClass = 'cursor-horizontal'; cursorWrapper.removeClass('cursor-horizontal');}
						if (cursorWrapper.hasClass('cursor-image')) {currentClass = 'cursor-image'; cursorWrapper.removeClass('cursor-image');}
						if (cursorWrapper.hasClass('cursor-snap')) {currentClass = 'cursor-snap'; cursorWrapper.removeClass('cursor-snap');}
						cursorWrapper.addClass('cursor-horizontal');
	  					let hrTextCursor = $(`<div class="">${hover.hover_hr_text}</div>`);
				    	cursorEl1.html(hrTextCursor);
				    	cursorWrapper.css({'--hr-padding' : `${hover.hover_hr_padding}px` , '--hr-width' : `${hover.hover_hr_width}px` , '--hr-transform' : `${hover.hover_hr_transform}` , '--hr-weight' : `${hover.hover_hr_weight}`, '--hr-color' : `${hover.hover_hr_color}`, '--hr-size' : `${hover.hover_hr_size}px`, '--hr-spacing' : `${hover.hover_hr_spacing}px`, '--hr-radius' : `${hover.hover_hr_radius}px`, '--bg-color' : `${hover.hover_hr_bgcolor}`, '--hr-backdrop' : `${hover.hover_hr_backdrop}(${hover.hover_hr_backdrop_amount})` });
					});
					hoverElement.on('mouseleave', function(){
						cursorWrapper.attr('style', currentStyles);
						cursorEl1.html('');
						cursorWrapper.removeClass('cursor-horizontal');
						cursorWrapper.addClass(currentClass);
						calcTop = currentCalcTop;
						calcLeft = currentCalcLeft;
						if (currentChild) {cursorEl1.append(currentChild);}
					});
				}
			});
		}

		function createHoverSnap(hover, cursorWrapper, cursorEl1) {
			let snapSelector = [];
			if (hover.buttons == "on") {snapSelector.push('button')}
			if (hover.images == "on") {snapSelector.push('img')}
			if (hover.links == "on") {snapSelector.push('a')}
			if (hover.custom == "on") {snapSelector.push(hover.selector)}
			snapSelector.forEach(function(selectorItem){
				let currentStyles, currentChild = null, currentType, currentCalcTop, currentCalcLeft, currentClass;
				let hoverElement = $(`#wt-preview ${selectorItem}`);
				if (hoverElement.length) {
					hoverElement.on('mouseenter', function(){
						stopFlag = true;
						currentStyles = cursorWrapper.attr('style');
						cursorWrapper.removeAttr('style');
						if (cursorEl1.children().length > 0) {currentChild = cursorEl1.children();cursorEl1.children().remove() }
						currentCalcTop = calcTop;
						currentCalcLeft = calcLeft;
						calcTop = 0;
						calcLeft = 0;
						if (cursorWrapper.hasClass('cursor-text')) {currentClass = 'cursor-text'; cursorWrapper.removeClass('cursor-text');}
						if (cursorWrapper.hasClass('cursor-horizontal')) {currentClass = 'cursor-horizontal'; cursorWrapper.removeClass('cursor-horizontal');}
						if (cursorWrapper.hasClass('cursor-image')) {currentClass = 'cursor-image'; cursorWrapper.removeClass('cursor-image');}
						if (cursorWrapper.hasClass('cursor-snap')) {currentClass = 'cursor-snap'; cursorWrapper.removeClass('cursor-snap');}

						cursorWrapper.addClass('cursor-snap');
			        	let elementPos = hoverElement[0].getBoundingClientRect();
			        	elementTop = elementPos.top - hover.padding;
			        	elementLeft = elementPos.left - hover.padding;
			        	hoverElementWidth = elementPos.width + (hover.padding * 2);
			        	hoverElementHeight = elementPos.height + (hover.padding * 2);
			        	cursorWrapper.css({'--width': hoverElementWidth + "px"});
			        	cursorWrapper.css({'--height': hoverElementHeight + "px"});
			        	

			        	cursorWrapper.css({'--blending': hover.blending});
			        	cursorWrapper.css({'--bgcolor': hover.bgcolor});
			        	cursorWrapper.css({'--border-color': hover.border_color});
			        	cursorWrapper.css({'--border-width': hover.border_width + "px"});
			        	cursorWrapper.css({'--radius': hover.radius + "px"});

			        	cursorEl1[0].style.top = 0;
			        	cursorEl1[0].style.left = 0;
					});
					hoverElement.on('mouseleave', function(){
						stopFlag = false;
						calcTop = currentCalcTop;
						calcLeft = currentCalcLeft;
						cursorWrapper.removeAttr('style');
						cursorWrapper.attr('style', currentStyles);
						cursorWrapper.removeClass('cursor-snap');
						cursorWrapper.addClass(currentClass);
						if (currentChild) {cursorEl1.append(currentChild);}
			        	cursorEl1[0].style.removeProperty('top');
			        	cursorEl1[0].style.removeProperty('left');
					});
				}
			});
		}
			
	}

	

	// Select Cursor
	let cursorWrapper = $('.wpcc-cursor');


	// Cursor Size Change
	let cursorSizeRange = $('#cursor_size_range'),
		cursorSizeInput = $('#cursor_size_input');

	cursorSizeRange.on('input', function(){
		cursorSizeInput.val($(this).val());
	});

	cursorSizeInput.on('input', function(){
		cursorSizeRange.val($(this).val());
	});

	// Create Hover Cursor Button
	// let createHoverBtn = $('#create_hover_btn');
	// createHoverBtn.on('click', function(e){
	// 	$('#hover_cursor_wrapper').fadeIn(0);
	// 	$(this).fadeOut(0);
	// });

	// Cancel Hover Cursor Button
	// let cancelHoverBtn = $('#cancel_hover_btn');
	// cancelHoverBtn.on('click', function(e){
	// 	$('#hover_cursor_wrapper').fadeOut(0);
	// 	createHoverBtn.fadeIn(0);
	// });
	

	// Hover Cursor Select
	// let hoverInputs = $('.hover-cursor-radio');
	// hoverInputs.each(function() {
	// 	if ($(this).prop('checked')) {
	// 		showTextIconInput($(this));
	// 	}
	// });
	// hoverInputs.on('click', function() {
	// 	showTextIconInput($(this));
	// });

	// Link/Button Toggle Buttons
	// let hoverTriggerCustomBtn = $('#hover_trigger_custom'),
	// 	hoverTriggerCustomWrapper = $('#hover_trigger_custom_wrapper'),
	// 	hoverTriggerLinks = $('#hover_trigger_link'),
	// 	hoverTriggerButtons = $('#hover_trigger_button');

	// hoverTriggerLinks.on('click', function() {toggleOffCheckboxes($(this), [hoverTriggerCustomBtn])});
	// hoverTriggerButtons.on('click', function() {toggleOffCheckboxes($(this), [hoverTriggerCustomBtn])});

	// toggleElementVisibility($(this), hoverTriggerCustomWrapper);
	// hoverTriggerCustomBtn.on('change', function(){
	// 	toggleElementVisibility($(this), hoverTriggerCustomWrapper);
	// 	toggleOffCheckboxes($(this), [hoverTriggerLinks, hoverTriggerButtons]);
	// });

	// Hover Type Change
	// let hoverType = $('[name=hover_type]');
	// hoverType.on('click', function(){
	// 	switch ($(this).val()) {
	// 		case 'default':
	// 			$('#available_hover_cursors').fadeOut();
	// 		break;
	// 		case 'snap':
	// 			$('#available_hover_cursors').fadeOut();
	// 		break;
	// 		case 'available':
	// 			$('#available_hover_cursors').fadeIn();
	// 		break;
	// 	}
	// });

	// Hover Cursor Width
	// let hoverCursorRange = $('#hover_cursor_width_range'),
	// 	hoverCursorInput = $('#hover_cursor_width_input');

	// hoverCursorRange.on('input', function(){
	// 	hoverCursorInput.val($(this).val());
	// });

	// hoverCursorInput.on('input', function(){
	// 	hoverCursorRange.val($(this).val());
	// });

	// Custom Icon Upload
	// let iconUploadBtn = $('#hover_cursor_icon_wrapper'),
	// 	iconElement = $('#hover_cursor_icon'), 
	// 	iconInputValue = $('#hover_cursor_icon_url'), 
	// 	iconMediaUploader;

	// iconUploadBtn.click(function(e){
	// 	e.preventDefault();
	// 	if (iconMediaUploader) {
	// 		iconMediaUploader.open();
	// 		return;
	// 	}
	// 	iconMediaUploader = wp.media.frames.file_frame = wp.media({
	// 		title: 'Choose Icon',
	// 		button: {
	// 			text: 'Select Icon'
	// 		}, 
	// 		multiple: false }
	// 	);
	// 	iconMediaUploader.on('select', function() {
	// 		var attachment = iconMediaUploader.state().get('selection').first().toJSON();
	// 		iconElement.attr('src', attachment.url);
	// 		iconInputValue.val(attachment.url);

	// 	});
	// 	iconMediaUploader.open();
	// });


	// Save Hover Cursor
	// let cursorArray = $('#hover_cursors').val()? JSON.parse($('#hover_cursors').val()) : [];
	// $('#save_hover_btn').on('click', function(){
	// 	const HOVEROBJECT = {};
	// 	let selector = [];

	// 	if (hoverTriggerLinks.prop('checked')) {
	// 		selector.push('a');
	// 	}
	// 	if (hoverTriggerButtons.prop('checked')) {
	// 		selector.push('button');
	// 	}
	// 	if (hoverTriggerCustomBtn.prop('checked')) {
	// 		if ($('#hover_trigger_selector').val()) {
	// 			selector.push($('#hover_trigger_selector').val());
	// 		}
	// 	}

	// 	if (cursorArray.length) {
	// 		let cursorExists = false;
	// 		cursorArray.some(function(oldCursor){
	// 			if (oldCursor.selector) {
	// 				cursorExists =  oldCursor.selector.some(r => selector.includes(r));	
	// 			}
	// 			else { cursorExists = 0; }
	// 			return cursorExists;
	// 		});
	// 		if (cursorExists) {
	// 			showError(strings[0]);
	// 			return false;
	// 		}
	// 	}

	// 	HOVEROBJECT.selector = selector;

	// 	hoverInputs.each(function() {
	// 		if ($(this).prop('checked')) {
	// 			HOVEROBJECT.cursor = $(this).val();
	// 		}
	// 	});

	// 	hoverType.each(function(){
	// 		if($(this).prop('checked')) {
	// 			HOVEROBJECT.hoverType = $(this).val();
	// 		}
	// 	});

	// 	if ($('#hover_cursor_text').val()) {
	// 		HOVEROBJECT.cursorText = $('#hover_cursor_text').val();
	// 	}
	// 	if ($('#hover_cursor_text').val()) {
	// 		HOVEROBJECT.cursorText = $('#hover_cursor_text').val();
	// 	}

	// 	if (iconInputValue.val()) {
	// 		HOVEROBJECT.cursorIcon = iconInputValue.val();
	// 	}

	// 	if ($('#hover_background_color').val()) {
	// 		HOVEROBJECT.bgColor = $('#hover_background_color').val();
	// 	}

	// 	if ($('#hover_cursor_width_range').val()) {
	// 		HOVEROBJECT.width = $('#hover_cursor_width_range').val();
	// 	}
		
	// 	let cursorArrayLength = cursorArray.push(HOVEROBJECT), 
	// 		cursorArrayIndex = cursorArrayLength - 1;
		
	// 	$('#hover_cursors').val(JSON.stringify(cursorArray));
	// 	let hoverListType;
	// 	if (HOVEROBJECT.hoverType !== 'available') {
	// 		hoverListType = `<div class="col-md-2"><span class="badge text-bg-primary">${HOVEROBJECT.hoverType}</span></div>`;
	// 	}
	// 	else {
	// 		hoverListType = `<div class="col-md-2"><img src="${wpcc_image_path[0]}/cursors/hover-${HOVEROBJECT.cursor}.svg" class="img" /></div><div class="col-md-3"><div class="bg-color">${strings[1]}<div style="background-color: ${HOVEROBJECT.bgColor};"></div></div></div><div class="col-md-3"><div class="width">${strings[2]}<div class="text-muted">${HOVEROBJECT.width} px</div></div></div>`;
	// 	}
	// 	$('.hover-cursors-list-wrapper').append($(`<div class="hover-list-item title-normal row position-relative">${hoverListType}<div class="col-md-3"><div class="activation">${strings[3]}<div class="text-muted">${HOVEROBJECT.selector}</div></div></div><div class="remove-hover" data-id="${cursorArrayIndex}"><i class="ri-close-fill ri-lg"></i></div></div>`));
	// 	$('#hover_cursor_wrapper').fadeOut(0);
	// 	createHoverBtn.fadeIn(0);
	// });

	// Functions
	// $(document).on('click','.remove-hover',function(index){
	// 	let id = $(this).attr('data-id');
	// 	cursorArray[id] = 'del';
		
	// 	$(this).parents('.hover-list-item').remove();
	// });
	addNewForm.on('submit', function(){
		// let updatedCursorArray = [];
		// cursorArray.forEach(function(item, index){
		// 	if (item != 'del') {
		// 		updatedCursorArray.push(item);
		// 	}
		// });
		//$('#hover_cursors').val(JSON.stringify(updatedCursorArray));
	});

	// function showTextIconInput(element) {
	// 	if (element.attr('id') !== 'hover_cursor_1') {
	// 		$('#hover_text_icon_wrapper').fadeOut();
	// 	}
	// 	else {
	// 		$('#hover_text_icon_wrapper').fadeIn();
	// 	}
	// }

	function toggleElementVisibility(toggler, element) {
		if (toggler.prop('checked')) {
			element.fadeIn();
		}
		else {
			element.fadeOut();
		}
	}

	function toggleOffCheckboxes(toggler, checkboxes) {
		if (toggler.prop('checked')) {
			checkboxes.forEach(function(checkbox){
				checkbox.prop('checked', false).trigger('change');
			});
		}
	}

	function showError(message) {
		$('#alert_message').html(message);
		$('#alert_container').removeClass('d-none');
		$('#alert_container').addClass('d-flex show');
		setTimeout(function(){
			$('#alert_container').removeClass('d-flex show');
			$('#alert_container').addClass('d-none');
		}, 10000);
	}

	// Activation
	toggleElementVisibility($('#activate_on_element'), $('#select_element_group'));
	$('#activate_on_page').on('click', function(){
		$('#select_element_group').fadeOut();
	});

	$('#activate_on_section').on('click', function(){
		$('#select_element_group').fadeIn();
	});

	// Color Picker Initialization
    $('.wp-custom-cursor-color-picker').spectrum({
		type: "component"
	});

});
