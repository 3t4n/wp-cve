/**********************************
*********** CERTIFICATES **********
**********************************/

// Set default selection data
var data = {
	selected : {
		status 		: false,
		containerID : 0,
		rowID 		: 0,
		elemID		: null,
		type 		: null,
		grab 		: false,
	}
}

jQuery(document).ready(function($){

	function wpcClearAllDataSelected(){
		data.selected.id = null;
		data.selected.type = null;
		data.selected.containerID = null;
		data.selected.elemID = null;
		data.selected.rowID = null;
	}

	function wpcReturnSelected(type = 'container'){
		if(type == 'container') {
			var selected = containers[data.selected.containerID];
		} else if(type == 'row'){
			var selected = containers[data.selected.containerID].rows[data.selected.rowID];
		} else {
			var selected = containers[data.selected.containerID].rows[data.selected.rowID].elements[data.selected.elemID];
		}

		return selected;
	}

    var typingTimer;
    var doneTypingInterval = 500;

    $(document).on('keyup', '.wpc-builder-range-input', function(){

        value = $(this).val();
        textInput = $(this).prev();

        // set unit of measurement
        if(value.indexOf('%') != -1) {
           $(this).attr('data-unit', '%');
           $(this).prev().attr('max', 100);
        } else if(value.indexOf('px') != -1){
            $(this).attr('data-unit', 'px');
        } else if(value.indexOf('em') != -1) {
            $(this).attr('data-unit', 'em');
        }

        unit = $(this).attr('data-unit');

        intValue = value.replace(/[^\d.-]/g, '');
        $(this).prev().val(intValue);

        if(intValue > 100){
            textInput.attr('max', intValue);
        } else {
            textInput.attr('max', 100);
        }

        textInput.val(intValue + unit);

        elem = wpcReturnSelected(data.selected.type);
        style = $(this).data('key');
        elem.style[style] = value + unit;

        jQuery('#wpc-certificate-preview').html(wpcRenderPage(containers));

    });

	$(document).on('input change', '.wpc-builder-range', function(){
        var val = $(this).val();
        var unit = $(this).next().attr('data-unit');

        var elem = wpcReturnSelected(data.selected.type);
        var style = $(this).next().data('key');
        elem.style[style] = val + unit;

        $(this).next().val(val + unit);

        jQuery('#wpc-certificate-preview').html(wpcRenderPage(containers));
    });

	function wpcBuilderMediaUploader(){
		var mediaUploader;
		jQuery('.wpc-builder-add-media-button, #wpc-add-image').on("click", function(e) {
			var prop = $(this).attr('data-key');
			var val = $(this).val();
			var thisSelected = $('.wpc-builder-img-url');
			// buttonId = this.id;
			e.preventDefault();
			// If the uploader object has already been created, reopen the dialog
			  if (mediaUploader) {
			  mediaUploader.open();
			  return;
			}
			// Extend the wp.media object
			mediaUploader = wp.media.frames.file_frame = wp.media({
			  title: 'Choose Attachment',
			  button: {
			  text: 'Choose Attachment'
			}, multiple: false });
				mediaUploader.on('select', function() {
				var selectedObject = wpcReturnSelected(data.selected.type);
				attachment = mediaUploader.state().get('selection').first().toJSON();

				if(prop === 'background-image'){
					selectedObject.style[prop] = 'url(' + attachment.url + ')';
					$('.wpc-builder-bg-img-wrapper').css('background-image', 'url(' + attachment.url + ')');
				} else if(prop === 'src') {
					selectedObject.content = '<img src="' + attachment.url + '" class="wpc-builder-img"/>';
					thisSelected.val(attachment.url);
				}


				jQuery('#wpc-certificate-preview').html(wpcRenderPage(containers));

			});
			// Open the uploader dialog
			mediaUploader.open();
		});
	}

	$(document).on('input change', '#wpc-certificate-editor-size', function(){
		var val = $(this).val();
		$('#wpc-certificate-preview').css({
			'transform' 		: 'scale(' + val + ')',
			'webkit-transform'	: 'scale(' + val + ')',
			'moz-transform'		: 'scale(' + val + ')',
		});
	});

	$('#wpc-certificate-background-editor-open').click(function(){
		$('.wpc-container-settings-icons .fa-cog').click();
	});

	function wpcToolbarDisplayLogic(){
		var selectedObj = wpcReturnSelected(data.selected.type);

		if(data.selected.grab === false){
			$('.wpc-floating-toolbar').fadeIn();
		}

		$('.wpc-toolbar-textarea').val(selectedObj.content);

		var buttons = $('.wpc-builder-button-select');

		$(buttons).each(function(index){
			var val = $(this).val();
			var prop = $(this).attr('data-key');

			if(selectedObj.style[prop] == val){
				$(this).addClass('wpc-builder-selected-button');
			} else {
				$(this).removeClass('wpc-builder-selected-button');
			}
		});

		// set toolbar buttons to appropriate values
		if(selectedObj.style['font-weight'] == 'bold') {
			$('button[data-key="font-weight"]').addClass('wpc-builder-selected-button');
		} else {
			$('button[data-key="font-weight"]').removeClass('wpc-builder-selected-button');
		}

		if(selectedObj.style['text-decoration'] == 'underline') {
			$('button[data-key="text-decoration"]').addClass('wpc-builder-selected-button');
		} else {
			$('button[data-key="text-decoration"]').removeClass('wpc-builder-selected-button');
		}

		if(selectedObj.style['font-style'] == 'italic') {
			$('button[data-key="font-style"]').addClass('wpc-builder-selected-button');
		} else {
			$('button[data-key="font-style"]').removeClass('wpc-builder-selected-button');
		}

		// set toolbar font select to same as selected element
		$('select[data-key="font-family"]').val(selectedObj.style['font-family']);

		// set toolbar font size select to same as selected element
		$('select[data-key="font-size"]').val(selectedObj.style['font-size']);

		// set colors
		var colors = $(".wpc-color-field");

		$(colors).each(function(index){
			var prop = $(this).attr('data-key');
			$(this).spectrum("set", selectedObj.style[prop]);

		});

		// push object properties to inputs
		var inputs = $('.wpc-builder-input-number');
		$(inputs).each(function(index){
			var prop = $(this).attr('data-key');
			var val = selectedObj.style[prop];
			var intVal = val.replace(/[^\d.-]/g, '');
			$(this).val(intVal);
		});

		var inputs = $('.wpc-builder-select');
		$(inputs).each(function(index){
			var prop = $(this).attr('data-key');
			var val = selectedObj.style[prop];
			$(this).val(val);
		});

		// set bg

		$('.wpc-builder-bg-img-wrapper').css('background-image', selectedObj.style['background-image']);

		// Display the appropriate tools for editing depending upon the selected container, row or element type
		var type = data.selected.type;
		var selectedObj = wpcReturnSelected(type);

		$(".wpc-accordion-option-header").addClass( "ui-state-disabled" );

		$('.wpc-accordion-option-content, .wpc-accordion-option-header').hide().data('show', 'false');

		for(i=0; i<selectedObj.tools.length; i++){
			$('*[data-view-property="' + selectedObj.tools[i] + '"]').show().data('show', 'true');
			$('*[data-view-property="' + selectedObj.tools[i] + '"]').removeClass( "ui-state-disabled" );
		}

		$('#wpc-tool-tab-content-1 h3').each(function(index){
			if($(this).data('show') == 'true'){
				$(this).next().show();
				$(this).next().attr('aria-hidden', 'false');
				$(this).next().addClass('ui-accordion-content-active');
				return false;
			}
		});

		$('#wpc-tool-tab-content-2 h3').each(function(index){
			if($(this).data('show') == 'true'){
				$(this).next().show();
				$(this).next().attr('aria-hidden', 'false');
				$(this).next().addClass('ui-accordion-content-active');
				return false;
			} 
		});


		// open first tab
		$('.wpc-tool-tab-1').click();	

		$('.wpc-element-type-toolbar').fadeOut();
	}

	function wpcPropogateSelectedIDs(el){
		data.selected.type = jQuery(el).attr('data-type');
		data.selected.rowID = parseInt(jQuery(el).attr('data-id'));
		data.selected.rowID = parseInt(jQuery(el).attr('data-id'));
		data.selected.elemID = parseInt(jQuery(el).attr('data-id'));

		if(data.selected.type == 'container'){
			var id = parseInt(jQuery(el).attr('data-id'));
			data.selected.id = id;
			data.selected.containerID = id;
			data.selected.rowID = null;
			data.selected.elemID = null;
		} else if(data.selected.type == 'row'){
			var id = parseInt(jQuery(el).attr('data-id'));
			data.selected.id = id;
			data.selected.containerID = parseInt(jQuery(el).attr('data-container-id'));
			data.selected.rowID = id;
			data.selected.elemID = 0;
		} else if(data.selected.type == 'element') {
			var id = parseInt(jQuery(el).attr('data-id'));
			data.selected.id = id;
			data.selected.containerID = parseInt(jQuery(el).attr('data-container-id'));
			data.selected.rowID = parseInt(jQuery(el).attr('data-row-id'));
			data.selected.elemID = id;
		} else {
			data.selected.id = null;
			data.selected.containerID = null;
			data.selected.rowID = null;
			data.selected.elemID = null;
		}
	}

	/*
	** CRUD
	*/

	// Rows

	function wpcAddRow(containerID, rowID){

		containers[containerID].rows.splice(rowID, 0, {
			columns		: [12],
			editable 	: true,
			selected 	: false,
			tools : ['background', 'spacing', 'sizing'],
			style 		: {
				'background-image'	: 'none',
				'background-color'	: 'none',
				'background-size'	: 'auto',
				'background-repeat'	: 'no-repeat',
				'padding-top'		: '40px',
				'padding-bottom'	: '40px',
				'padding-left'		: '40px',
				'padding-right'		: '40px',
				'margin-top'		: '0',
				'margin-bottom'		: '0',
				'margin-left'		: '0',
				'margin-right'		: '0',
			},
			elements : [],
		});
		wpcClearAllDataSelected();
	}

	// Elements

	function wpcDeleteElement(containerID, rowID, elementID){
		containers[containerID].rows[rowID].elements.splice(elementID, 1);
		wpcClearAllDataSelected();
	}

	function wpcAddElement(containerID, rowID, elementID, elemType){
	
		eID = parseInt(elementID);
		containers[containerID].rows[rowID].elements.splice(eID, 0, {
			position	: 0, // array key for rows.columns
			editable 	: false,
			type  		: elemType,
			tools : ['background', 'spacing', 'typography', 'content', 'sizing', 'alignment'],
			content 	: '',
			style 		: {
				'background-image'	: 'none',
				'background-color'	: 'none',
				'background-size'	: 'auto',
				'background-repeat'	: 'no-repeat',
				'color'				: '#000000',
				'font-family'		: 'Arial',
				'font-style'		: 'normal',
				'font-weight'		: 'normal',
				'font-size'			: '24px',
				'text-align'		: 'center',
				'text-decoration'	: 'none',
				'padding-top'		: '20px',
				'padding-bottom'	: '20px',
				'padding-left'		: '20px',
				'padding-right'		: '20px',
				'margin-top'		: '0',
				'margin-bottom'		: '0',
				'margin-left'		: '0',
				'margin-right'		: '0',
			},
		});
		data.selected.elemID = eID;

		eID = data.selected.elemID;

		if(elemType === 'text'){
			containers[cID].rows[rID].elements[eID].editable = true;
			containers[cID].rows[rID].elements[eID].content = 'Your text goes here.  Click to edit.';
			containers[cID].rows[rID].elements[eID].tools = ['background', 'spacing', 'typography', 'content', 'sizing'];
		} else if(elemType === 'username'){
			containers[cID].rows[rID].elements[eID].editable = false;
			containers[cID].rows[rID].elements[eID].content = '{Username}';
			containers[cID].rows[rID].elements[eID].tools = ['background', 'spacing', 'typography', 'sizing'];
		} else if(elemType === 'name'){
			containers[cID].rows[rID].elements[eID].editable = false;
			containers[cID].rows[rID].elements[eID].content = '{Full Name}';
			containers[cID].rows[rID].elements[eID].tools = ['background', 'spacing', 'typography', 'sizing'];
		} else if(elemType === 'date'){
			containers[cID].rows[rID].elements[eID].editable = false;
			containers[cID].rows[rID].elements[eID].content = '{Date}';
			containers[cID].rows[rID].elements[eID].tools = ['background', 'spacing', 'typography', 'sizing'];
		} else if(elemType === 'image') {
			containers[cID].rows[rID].elements[eID].editable = true;
			containers[cID].rows[rID].elements[eID].content = '';
			containers[cID].rows[rID].elements[eID].tools = ['background', 'spacing', 'image', 'sizing', 'alignment'];
		}
	}

	function wpcInlineStyles(obj){
		CSS = '';

		Object.keys(obj.style).forEach(
			el => CSS += el + ':' + obj.style[el] + ';'
		);

		return CSS;
	}

	function wpcRenderPage(containers){

		var html = '<div class="wpc-page-builder-wrapper">';

		for(c=0; c<containers.length; c++){

			containerCSS = wpcInlineStyles(containers[c]);

			html += '<ul data-id="' + c + '" data-type="container" class="wpc-certificate wpc-builder-row-sortable' + containers[c].class + ' certificate-bg wpc-builder-container wpc-builder-selectable" style="' + containerCSS + '">';

			html += '<div class="wpc-container-settings-icons"><i class="fa fa-cog"></i></div>';

			if(containers[c].rows.length ===0){
				html += '<div class="wpc-row-empty-icon"><i class="fa fa-plus"></i></div>';
			}

			// loop through rows
			for(r=0; r<containers[c].rows.length; r++){

				rowCSS = wpcInlineStyles(containers[c].rows[r]);

				html += '<li class="wpc-builder-row wpc-builder-selectable wpc-builder-row-sortable" data-container-id="' + c + '" data-id="' + r + '" data-type="row" data-selected="' + containers[c].rows.selected + '" style="' + rowCSS + '">';
				html += '<div class="wpc-row-settings-icons wpc-settings-icons"><i class="fa fa-arrows"></i><i class="fa fa-cog"></i><i class="fa fa-plus"></i><i class="fa fa-trash"></i></div>';
				if(containers[c].rows[r].elements.length === 0){
					html += '<div class="wpc-element-empty-icon"><i class="fa fa-plus" data-id="' + 0 + '"></i></div>';
				}

				for(col=0; col<containers[c].rows[r].columns.length; col++){
					if(containers[c].rows[r].elements){
						// loop through elements
						for(e=0; e<containers[c].rows[r].elements.length; e++){

							if(col == containers[c].rows[r].elements[e].position){
								elemCSS = wpcInlineStyles(containers[c].rows[r].elements[e]);

								var colClass = 'wpc-' + containers[c].rows[r].columns[col];
								var editableClass = containers[c].rows[r].elements[e].editable == 'false' ? 'wpc-not-editable' : 'wpc-editable';

								html += '<div class="wpc-builder-element wpc-builder-selectable ' + editableClass + ' ' + colClass + '" data-container-id="' + c + '" data-row-id="' + r + '" data-id="' + e + '" data-type="element" style="' + elemCSS + '">';
								html += '<div class="wpc-element-settings-icons wpc-settings-icons"><i class="fa fa-arrows"></i><i class="fa fa-cog"></i><i class="fa fa-plus" data-id="' + e + '"></i><i class="fa fa-trash"></i></div>';
								html += containers[c].rows[r].elements[e].content;
								html += '</div>'; // element
							}

						}
					}
				}

				html += '</li>'; // row

			}

			html += '</ul>'; // container

		}

		html += '</div>'; // wrapper

		return html;
	}

	wpcBuilderMediaUploader();

	$(".wpc-floating-toolbar").draggable({
		containment: "window"
	});

	$('.wpc-tools-accordion').accordion({
        heightStyle: "content",
        icons: {
	        activeHeader: "wpc-arrow-up",
	        header: "wpc-arrow-down"
	    }
    });

	$('.wpc-tool-tab-1').click(function(){
		$(this).siblings().removeClass('wpc-tools-tab-active');
		$(this).addClass('wpc-tools-tab-active');
		$(this).parent().siblings('.wpc-tool-tab-content').hide();
		$(this).parent().siblings('.wpc-tool-tab-content').eq(0).show();
	});

	$('.wpc-tool-tab-2').click(function(){
		$(this).siblings().removeClass('wpc-tools-tab-active');
		$(this).addClass('wpc-tools-tab-active');
		$(this).parent().siblings('.wpc-tool-tab-content').hide();
		$(this).parent().siblings('.wpc-tool-tab-content').eq(1).show();
	});

	$('#wpc-tool-tab-content-2').hide();
	$('.wpc-floating-toolbar').hide();

	// Sorting: sets grabbed element type and pushes element object to selected.data.element to be used during mousemove
	$(document).on('mousedown', '.wpc-settings-icons .fa-arrows', function(e){
		var element = $(this).parent().parent();
		data.selected.grab = element.attr('data-type');
		element.addClass('wpc-builder-grabbed-' + data.selected.grab);
		data.selected.element = element;
		wpcPropogateSelectedIDs(element);
		$('.wpc-floating-toolbar').hide();
		e.stopPropagation();
	});

	// Sorting: logic for where to place selected element after mouseup
	$(document).on('mouseup', '.wpc-page-builder-wrapper', function(e){
		$(document).css('cursor', 'auto');
		var mouseY = e.pageY;
		var thisRowID = null;
		var thisRowID = null;
		var thisElemID = null;
		var thisElemID = null;
		selectedObj = wpcReturnSelected(data.selected.type);
		// selectedH = data.selected.element.height();

		if(data.selected.grab === 'row'){

			var allRows = $('.wpc-builder-row');

			$(allRows).not('.wpc-builder-grabbed-row').each(function(index){

				var thisRowY = $(this).offset();
				thisRowY = thisRowY.top;

				if(mouseY > thisRowY){
					thisRowID = $(this).attr('data-id');
					thisRowID = parseInt(thisRowID);
				} else {
					return false;
				}
			}); 

			containers[data.selected.containerID].rows.splice(data.selected.rowID, 1);
			containers[data.selected.containerID].rows.splice(thisRowID, 0, selectedObj);
			
			jQuery('#wpc-certificate-preview').html(wpcRenderPage(containers));
		}

		if(data.selected.grab === 'element'){

			var allRows = $('.wpc-builder-row');

			$(allRows).each(function(index){

				var thisRowY = $(this).offset();
				thisRowY = thisRowY.top;

				if(mouseY > thisRowY){
					thisRowID = $(this).attr('data-id');
					thisRowID = parseInt(thisRowID);
				} else {
					return false;
				}

			}); 

			var allElements = $('.wpc-builder-element');

			$(allElements).not('.wpc-builder-grabbed-element').each(function(index){

				var thisElemY = $(this).offset();
				thisElemY = thisElemY.top;

				if(mouseY > thisElemY){
					thisElemID = $(this).attr('data-id');
					thisElemID = parseInt(thisElemID);
				} else {
					return false;
				}

			});

			containers[data.selected.containerID].rows[data.selected.rowID].elements.splice(data.selected.elemID, 1);
			containers[data.selected.containerID].rows[thisRowID].elements.splice(thisElemID, 0, selectedObj);
			
			jQuery('#wpc-certificate-preview').html(wpcRenderPage(containers));
		}

		$('.wpc-builder-row').removeClass('wpc-builder-grabbed-row');
		data.selected.grab = false;
		data.selected.element = false;

	});

	// Sorting: grab and drag rows on Y axis
	$(document).on('mousemove', document, function(e){
		 if(data.selected.grab !== false){

		 	$(document).css('cursor', 'move');

		    toSort = data.selected.element;
		    var type = data.selected.type;
			var w = toSort.width();
			var h = toSort.height();
			var offset = toSort.offset();
			var offsetY = offset.top;
			// var mouseX = e.pageX;

			if($('.wpc-page-builder-wrapper').length > 0) {
				var pageWrapperOffset = $('.wpc-page-builder-wrapper').offset();
				var pageWrapperOffsetY = pageWrapperOffset.top;
				
				var mouseY = e.pageY;

				toSort.css({
					position 	: 'fixed',
					top 		: mouseY + 'px',
					width 		: w + 'px',
					height 		: h + 'px',
				});
			}
		 }
	});

	$(".wpc-color-field").spectrum({
	  type: "flat",
	  change : function() {
	  	jQuery('#wpc-certificate-preview').html(wpcRenderPage(containers));
	  },
	  move : function(event, ui) {
	  		var color = jQuery(this).val();
	  		var type = data.selected.type;
	  		var prop = $(this).attr('data-key');
	  		selected = wpcReturnSelected(type);

			selected.style[prop] = color;
	  		
	  		jQuery('#wpc-certificate-preview').html(wpcRenderPage(containers));
	  	}
	});

	$('.wpc-element-type-header .fa-times').click(function(){
		$(this).parent().parent().fadeOut();
		wpcClearAllDataSelected();
	});

	$('.wpc-builder-delete-image-button').click(function(e){
			var type = data.selected.type;
	  		selected = wpcReturnSelected(type);
	  		selected.content = '';
	  		$('.wpc-builder-img-url').val('');
	  		jQuery('#wpc-certificate-preview').html(wpcRenderPage(containers));
	  		e.preventDefault();
	});

	$(document).on('keyup', '.wpc-toolbar-textarea', function(){
		var type = data.selected.type;
		var selected = wpcReturnSelected(type);
		var val = $(this).val();
		if(selected.type == 'text'){
			selected.content = val;
		}
		jQuery('#wpc-certificate-preview').html(wpcRenderPage(containers));
	});

	$('.wpc-builder-align').click(function(){
		var type = data.selected.type;
		var selected = wpcReturnSelected(type);
		var val = $(this).val();
		var left = $('*[data-key="margin-left"]');
		var right = $('*[data-key="margin-right"]');

		if(val === 'left') {
			selected.style['margin-left'] = '0';
			selected.style['margin-right'] = 'auto';
			left.val('0');
			right.val('auto');
		} else if(val === 'center') {
			selected.style['margin-left'] = 'auto';
			selected.style['margin-right'] = 'auto';
			left.val('auto');
			right.val('auto');
		} else if(val === 'right') {
			selected.style['margin-left'] = 'auto';
			selected.style['margin-right'] = '0';
			left.val('auto');
			right.val('0');
		}

		jQuery('#wpc-certificate-preview').html(wpcRenderPage(containers));

	});

	$('.wpc-builder-bg-tools .fa-trash').click(function(e){
		var element = $(this).parent().parent();
		var prop = $(this).attr('data-key');
		var type = data.selected.type;
		var selected = wpcReturnSelected(type);
		element.attr('style', '');
		selected.style[prop] = 'none';
		jQuery('#wpc-certificate-preview').html(wpcRenderPage(containers));
		e.stopPropagation();
	});

	$(document).on('click', '.wpc-builder-element .fa-trash', function(e){
		var el = $(this).parent().parent();
		wpcPropogateSelectedIDs(el);
		var cID = el.attr('data-container-id');
		var rID = el.attr('data-row-id');
		var eID = el.attr('data-id');
		wpcDeleteElement(cID, rID, eID);
		$('.wpc-element-type-toolbar').fadeOut();
		$('.wpc-floating-toolbar').fadeOut();
		jQuery('#wpc-certificate-preview').html(wpcRenderPage(containers));
		e.stopPropagation();
	});

	$('.wpc-add-element').click(function(){
		cID = data.selected.containerID;
		rID = data.selected.rowID;
		eID = parseInt(data.selected.elemID);
		var type = $(this).val();
		if(containers[data.selected.containerID].rows[data.selected.rowID].elements.length > 0) {
			eID = eID + 1;
		}
		wpcAddElement(cID, rID, eID, type);
		wpcToolbarDisplayLogic();
		$('.wpc-element-type-toolbar').fadeOut();
		$('.wpc-floating-toolbar').fadeIn();
		jQuery('#wpc-certificate-preview').html(wpcRenderPage(containers));
	});

	$(document).on('click', '.wpc-builder-element .fa-plus, .wpc-element-empty-icon .fa-plus', function(e){

		var pos = $(this).offset();

		var w = $('.wpc-floating-toolbar').outerWidth();

		$('.wpc-element-type-toolbar').css({
			top : (pos.top + 38) + 'px',
			left : pos.left - (w / 2 - 16) + 'px'
		});

		var parent = $(this).parent().parent();
		var cID = parseInt(parent.attr('data-container-id'));
		var rID = parent.attr('data-row-id');

		if ($(this).parent().hasClass('wpc-element-empty-icon') == false) {
			// row has elements
		    rID = parseInt(parent.attr('data-row-id'));
		    var ID = parseInt($(this).attr('data-id')) + 1;
		} else {
			// row has no elements
			ID = 0;
			var rID = parseInt(parent.attr('data-id'));
		}

		var ID = parseInt($(this).attr('data-id'));
		data.selected.containerID = cID;
		data.selected.rowID = rID;
		data.selected.elemID = ID;

		$('.wpc-element-type-toolbar').fadeIn();
		$('.wpc-floating-toolbar').fadeOut();

		e.stopPropagation();
	});

	$(document).on('click', '.wpc-row-settings-icons .fa-plus', function(e){
		el = $(this).parent().parent();
		var cID = el.attr('data-container-id');
		var rID = parseInt(el.attr('data-id')) + 1;
		wpcAddRow(cID, rID);
		$('.wpc-floating-toolbar').fadeOut();
		jQuery('#wpc-certificate-preview').html(wpcRenderPage(containers));
		e.stopPropagation();
	});

	$(document).on('click', '.wpc-row-empty-icon .fa-plus', function(e){
		var parent = $(this).parent().parent();
		var cID = parent.attr('data-id');
		wpcAddRow(cID, 0);
		$('.wpc-floating-toolbar').fadeOut();
		jQuery('#wpc-certificate-preview').html(wpcRenderPage(containers));
		e.stopPropagation();
	});

	$(document).on('click', '.wpc-row-settings-icons .fa-trash', function(e){
		var parent = $(this).parent().parent();
		var cID = parent.attr('data-container-id');
		var rID = parseInt(parent.attr('data-id'));
		containers[cID].rows.splice(rID, 1);

		wpcClearAllDataSelected();

		$('.wpc-element-type-toolbar').fadeOut();
		$('.wpc-floating-toolbar').fadeOut();

		jQuery('#wpc-certificate-preview').html(wpcRenderPage(containers));

		e.stopPropagation();
	});

	$('.wpc-toolbar-close').click(function(){
		$('.wpc-floating-toolbar').fadeOut();
		wpcClearAllDataSelected();
	});

	$(document).on('click', '.wpc-builder-selectable', function(e){
		wpcPropogateSelectedIDs($(this));
		wpcToolbarDisplayLogic();
		jQuery('#wpc-certificate-preview').html(wpcRenderPage(containers));
		e.stopPropagation();
	});

	$('.wpc-builder-button-select').click(function(){
		var type = data.selected.type;

		var prop = $(this).attr('data-key');	
		var val = $(this).val();
		var selectedObj = wpcReturnSelected(type);
		selectedObj.style[prop] = val;
		$(this).siblings().removeClass('wpc-builder-selected-button');
		if($(this).hasClass('wpc-builder-selected-button') === false){
			$(this).toggleClass('wpc-builder-selected-button');
		}
		$('#wpc-certificate-preview').html(wpcRenderPage(containers));
	});


	$('.wpc-builder-select').change(function(){

		var prop = $(this).attr('data-key');
		var type = data.selected.type;
		var obj = wpcReturnSelected(type);

		obj.style[prop] = $(this).val();

		$('#wpc-certificate-preview').html(wpcRenderPage(containers));

	});

	$(document).on('keyup click', '.wpc-builder-input-number', function(){
		var val = $(this).val();
		var prop = $(this).attr('data-key');
		var unit = $(this).attr('data-unit');
		var type = data.selected.type;
		wpcReturnSelected(type).style[prop] = val + unit;

		$('#wpc-certificate-preview').html(wpcRenderPage(containers));
	});

	$('.wpc-builder-button-toggle').click(function(){

		var prop = $(this).attr('data-key');
		var type = data.selected.type;
		var selected = wpcReturnSelected(type);
		$(this).toggleClass('wpc-builder-selected-button');

		if(prop === 'font-weight'){
			selected.style['font-weight'] === 'bold' ? selected.style['font-weight'] = 'normal' : selected.style['font-weight'] = 'bold';
		} else if(prop === 'font-style'){
			selected.style['font-style'] === 'normal' ? selected.style['font-style'] = 'italic' : selected.style['font-style'] = 'normal';
		} else if(prop === 'text-decoration'){
			selected.style['text-decoration'] === 'none' ? selected.style['text-decoration'] = 'underline' : selected.style['text-decoration'] = 'none';
		}
		
		$('#wpc-certificate-preview').html(wpcRenderPage(containers));

	});

	$('.wpc-certificate-bg-select').change(function(){
		var val = $(this).val();
		var prop = $(this).attr('data-key');
		$('.wpc-builder-bg-img-wrapper').css('background-image', val);
		var selected = wpcReturnSelected(data.selected.type);
		selected.style[prop] = val;
	});
	$('.wpc-builder-bg-tools .fa-trash').click(function(){
		$('.wpc-certificate-bg-select').val('none');
	});
		
	// render the certificate
	//$('#wpc-certificate-preview').html(wpcRenderPage(containers));

	function wpcBuilderInit(elementID = '#wpc-builder', designObj = null){
		if(typeof designObj === 'undefined' || designObj === null){
			// set default empty container
			containers = [{
			tools : ['background', 'spacing'],
				class : '',
				style : {
					'-webkit-box-sizing' 	: 'border-box',
					'box-sizing'			: 'border-box',
					'background-color'		: '#f5f5f5',
					'background-image'		: "none",
					'background-size'		: "auto",
					'padding-top'			: '60px',
					'padding-bottom'		: '60px',
					'padding-left'			: '85px',
					'padding-right'			: '85px',
					'margin-top'			: '0',
					'margin-bottom'			: '0',
					'margin-left'			: '0',
					'margin-right'			: '0',
				},
				rows : [],
			}]
		} else {
			containers = designObj;
		}

		// ajax doesn't save empty arrays so if no elements exist for certain rows, populate them with empty arrays
		for(c=0; c<containers.length; c++) {
			for(r=0; r<containers[c].rows.length; r++) {
				if(typeof containers[c].rows[r].elements === 'undefined'){
				containers[c].rows[r].elements = [];
				}
			}
		}

		// return rendered content
		var content = wpcRenderPage(containers);

		// show the content
		$(elementID).html(content);
	}

	if(typeof certificateContainers !== 'undefined'){
		wpcBuilderInit('#wpc-certificate-preview', certificateContainers);
	}

});