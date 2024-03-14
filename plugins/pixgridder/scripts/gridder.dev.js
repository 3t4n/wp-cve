jQuery.noConflict();

/********************************
*
*   Select
*
********************************/
function getSelValue(){
    jQuery('#pix_builder_canvas select, #pix_builder_id_fields select').off('change');
    jQuery('#pix_builder_canvas select, #pix_builder_id_fields select').on('change',function(){
        var tx = jQuery('option:selected',this).text();
        if ( !jQuery(this).parents('span').eq(0).find('.appended').length ) {
			jQuery(this).parents('span').eq(0).prepend('<span class="appended" />');
		}
		var elm = jQuery(this).siblings('.appended');
		jQuery(elm).text(tx);
    }).triggerHandler('change');

	jQuery('.pix_section_builder').each(function(){
		var dataCols = jQuery(this).attr('data-cols');
		jQuery('.pix_section_template option[value="'+dataCols+'"]',this).prop('selected',true);
		jQuery('.pix_section_template',this).change();
	});

	jQuery('#pix_builder_canvas .pix_section_template').off('change');
	jQuery('#pix_builder_canvas .pix_section_template').on('change',function(){ //if I change the number of columns
		var section = jQuery(this).parents('.pix_section_builder').eq(0),
			dataCol = parseFloat(jQuery('option:selected',this).val()),
			lengAct = 0,
			lengCol = 0,
			t = jQuery(this);
		jQuery('.pix_builder_column',section).not('.pix_clone_column').each(function(){
			lengCol = lengCol + parseFloat(jQuery(this).attr('data-col'));
		});
		jQuery('.pix_builder_column.pix_column_active',section).not('.pix_clone_column').each(function(){
			lengAct = lengAct + parseFloat(jQuery(this).attr('data-col'));
		});
		if(dataCol < lengAct) { //in this case you are trying to remove columns from the layout, but you can't because the script can't know how to manage the extra content
			jQuery('option[value="'+lengAct+'"]',this).prop('selected',true);
			var tx = jQuery('option:selected',this).text(),
				elm = jQuery(this).siblings('.appended');
			jQuery(elm).text(tx);
			section.find('.pix_section_error').fadeIn(400,function(){jQuery(this).delay(1500).fadeOut();});
		} else { //in this case you can change the layout
			section.attr('data-cols',dataCol);
			if ( dataCol > lengCol ) {
				var i = lengCol;
				while (i<dataCol) {
					section.find('.pix_builder_column').not('.pix_clone_column').last().after('<div class="pix_builder_column" data-col="1" />');
					i++;
				}
				jQuery('.pix_builder_column',section).not('.pix_clone_column').each(function(){ //I calculate again lengCol to avoid that the 'while' loop replicates the action and add too many columns
					lengCol = lengCol + parseFloat(jQuery(this).attr('data-col'));
				});
				checkEmptyColumns();	
			} else {
				var i = dataCol;
				while (i<lengCol) {
					jQuery('.pix_builder_column',section).not('.pix_clone_column').not('.pix_column_active').last().attr('data-col','0');
					i++;
					jQuery('.pix_builder_column[data-col=0]',section).remove(); //repeated, because of the getSelValue is init on DOM too
					checkEmptyColumns();
				}
			}
			jQuery('.pix_builder_column[data-col=0]',section).remove(); //repeated, because of the getSelValue is init on DOM too
			setVisualContent();
			columnWidth();
		}
		getSelValue();
	});
}


/********************************
*
*   iFrame scroll and fade
*
********************************/
function pix_loaded_iframe(){
	var myIframe = document.getElementById('pix-builder-iframe');
	iframeDoc = myIframe.contentWindow.document;
	if ( jQuery(iframeDoc).find('#main').length ) {
		var content = jQuery(iframeDoc).find('#main').offset().top;
	} else {
		var content = 0;
	}
	myIframe.contentWindow.scrollTo(0,content);
	jQuery('#pix-builder-iframe').css('visibility','visible');
	jQuery('#pix_loader_iframe').fadeOut();
}


/********************************
*
*   Field creation
*
********************************/
function setVisualContent() {

	var content = '';

	jQuery('#pix_builder_canvas .pix_section_builder_movable').not('.pix_clone_section').each(function(){

		var t = jQuery(this),
			dataCols = parseFloat(t.attr('data-cols')),
			dataId = (typeof jQuery(this).attr('data-id')!='undefined' && jQuery(this).attr('data-id')!=='') ? ' data-id['+jQuery(this).attr('data-id')+']' : '',
			dataClass = (typeof jQuery(this).attr('data-class')!='undefined' && jQuery(this).attr('data-class')!=='') ? ' data-class['+jQuery(this).attr('data-class')+']' : '';

		content = content + '<!--pixgridder:row[cols='+dataCols+']' + dataId + dataClass + '-->' + jQuery('textarea.pix_section_txt',this).val();

		jQuery('.pix_builder_column.pix_column_active',t).not('.pix_clone_column').each(function(){
			var dataCol = parseFloat(jQuery(this).attr('data-col')),
				dataId = (typeof jQuery(this).attr('data-id')!='undefined' && jQuery(this).attr('data-id')!=='') ? ' data-id['+jQuery(this).attr('data-id')+']' : '',
				dataClass = (typeof jQuery(this).attr('data-class')!='undefined' && jQuery(this).attr('data-class')!=='') ? ' data-class['+jQuery(this).attr('data-class')+']' : '',
				colCont = jQuery('textarea',this).val();
			content = content + '<!--pixgridder:column[col='+dataCol+']' + dataId + dataClass + '-->';
			content = content + colCont;
			content = content + '<!--/pixgridder:column[col='+dataCol+']-->';
		});

		content = content + '<!--/pixgridder:row[cols='+dataCols+']-->';

	});

	content = content.replace(/<p><\/p>/g,'');
	/*content = content.replace(/<span(.+?)font-size: 1rem(.+?)>(.+?)<\/span>/g,'$3');
	content = content.replace(/<(.+?) (.+?)font-size: 1rem(.+?)>(.+?)<\//g,'<$1>$4</');
	content = content.replace(/<(.+?) font-size: 1rem(.+?)>(.+?)<\//g,'<$1>$3</');*/
	content = content.replace(/<iframe(.+?)data-utoplay(.+?)>/g,'<iframe$1autoplay$2>');

	if ( typeof tinyMCE!=='undefined' && typeof tinyMCE.get('content')!=='undefined' ) {
		tinyMCE.get('content').setContent(content);
	}

	jQuery('textarea#content').val(content);

}


function pageBuilder(){

	jQuery('#wp-content-editor-tools').prepend(
		'<a id="pix-content-preview" class="hide-if-no-js wp-switch-editor switch-preview" onclick="switchEditors.switchto(this);">'+pixgridder_preview_text+'</a>'
	).prepend(
		'<a id="pix-content-builder" class="hide-if-no-js wp-switch-editor switch-builder" onclick="switchEditors.switchto(this);">'+pixgridder_builder_text+'</a>'
	);
	var after = jQuery('#pix-builder-editor-container');
	jQuery('#wp-content-editor-container').after(after);


	var pix_editor_tab = localStorage.getItem("pix_editor_tab"),
		page_template,
		max_columns,
		class_wrap;

	function switchBuilder(){
		jQuery('#pix_builder_preview').hide();
		jQuery('#pix_builder_canvas').show();
		var wrap = jQuery('#wp-content-wrap');
		wrap.removeClass('preview-active').addClass('builder-active');
		localStorage.setItem('pix_editor_tab', 'pix_builder');
	}

	function switchPreview(){
		jQuery('#pix_loader_iframe').show();
		jQuery('#pix-builder-iframe').css('visibility','hidden');
		jQuery('#pix_builder_canvas').hide();
		jQuery('#pix_builder_preview').show();
		var wrap = jQuery('#wp-content-wrap');
		wrap.removeClass('builder-active').addClass('preview-active');
		localStorage.setItem('pix_editor_tab', 'pix_preview');
		if ( jQuery('#auto_draft').val() == '1'/* && notSaved*/ ) {
			//autosaveDelayPreview = true;
			autosave();
			return false;
		}
		doPixPreview();
		return false;
	}

	function doPixPreview(){
		jQuery('input#wp-preview').val('dopreview');
		jQuery('form#post').attr('target', 'iframe-preview').submit().attr('target', '');

		var ua = navigator.userAgent.toLowerCase();
		if ( ua.indexOf('safari') != -1 && ua.indexOf('chrome') == -1 ) {
			jQuery('form#post').attr('action', function(index, value) {
				return value + '?t=' + new Date().getTime();
			});
		}

		jQuery('input#wp-preview').val('');
	}

	jQuery(document).off('click','#pix-content-builder');
	jQuery(document).on('click','#pix-content-builder',function(){
		switchBuilder();
	});

	jQuery(document).off('click','#pix-content-preview');
	jQuery(document).on('click','#pix-content-preview',function(){
		switchPreview();
	});

	if ( pix_editor_tab == 'pix_builder' ) {
		switchBuilder();
	} else {
		switchPreview();
	}

	jQuery(document).off('click',"#pix_builder_canvas .pix_column_edit");
	jQuery(document).on('click',"#pix_builder_canvas .pix_column_edit",function(){
		if ( typeof tinyMCE!=='undefined' ) {
			tinyMCE.execCommand('mceRemoveEditor',false,'textArea');
		}
		var t = jQuery(this).parents('.pix_builder_column').eq(0),
			textA = jQuery('textarea',t),
			textCont = jQuery('.pix_builder_content',t),
			htmlThis = textA.val(),
			h = jQuery(window).height(),
			div = jQuery('#textarea_builder'),
			dataCol = t.attr('data-col');
			dataCols = t.parents('.pix_section_builder').eq(0).attr('data-cols');
		htmlThis = htmlThis.replace(/<p><\/p>/g,'');
		htmlThis = htmlThis.replace(/<p><\!--\/pixgridder(.+?)-->(?!<\!--)/g, '<!--/pixgridder$1--><p>');
		htmlThis = htmlThis.replace(/<p><\!--pixgridder(.+?)--><\/p>/g, '<!--pixgridder$1-->');
		htmlThis = htmlThis.replace(/<p><\!--\/pixgridder(.+?)--><\/p>/g, '<!--/pixgridder$1-->');
		htmlThis = htmlThis.replace(/<iframe(.+?)data-utoplay(.+?)>/g,'<iframe$1autoplay$2>');
		jQuery(div).dialog({
			height: (h*0.8),
			width: '80%',
			modal: false,
			dialogClass: 'wp-dialog pix-dialog pix-page-builder',
			position: { my: "center", at: "center", of: window },
			title: 'Add some content',
			zIndex: 10000,
			open: function(){
				jQuery(window).trigger('pix_builder_modal');
				if ( typeof tinyMCE!=='undefined' ) {
					pixgridderTinyMCEinit();
				}
				jQuery(this).closest('.ui-dialog').find('.ui-button').eq(0).addClass('ui-dialog-titlebar-edit');
				jQuery('body').addClass('overflow_hidden').append('<div id="pix-modal-overlay" />');
				jQuery('#pix-modal-overlay').css({
					background: '#000000',
					bottom: 0,
					height: '100%',
					left: 0,
					opacity: 0.6,
					position: 'fixed',
					right: 0,
					top: 0,
					width: '100%',
					zIndex: 99
				});
				if ( typeof tinyMCE!=='undefined' ) {
					tinyMCE.execCommand('mceAddEditor',false,'textArea');
					tinyMCE.execCommand('mceFocus',false,'textArea');
					tinyMCE.get('textArea').setContent(htmlThis);
					var bodyTMCE = tinyMCE.get('textArea').dom.select('body');
					jQuery(bodyTMCE).css({width:(pixgridder_content_width*(dataCol/dataCols))}); //to display a particular width of the editor according with the width of the column you're editing
					jQuery('#wp-textArea-wrap').removeClass('html-active').addClass('tmce-active');
				} else {
					jQuery('textarea#textArea').val(htmlThis);
				}
				jQuery(window).bind('resize',function() {
					if ( typeof tinyMCE === 'undefined' ) {
						var txtH = parseFloat(jQuery('#textarea_builder').height()),
							qtH = parseFloat(jQuery('#qt_textArea_toolbar').outerHeight()),
							toolH = parseFloat(jQuery('#wp-textArea-editor-tools').outerHeight());
						jQuery('#textArea').height(txtH-(qtH+toolH+40));
					}
				});
				var set = setTimeout(function(){ jQuery(window).triggerHandler('resize'); },100);
			},
			buttons: {
				'': function() {
					var cont;
					if ( typeof tinyMCE!=='undefined' ) {
						if ( jQuery('#wp-textArea-wrap').hasClass('html-active') && tinyMCE.activeEditor.getParam('wpautop', true) ) {
							cont = switchEditors.wpautop(jQuery('textarea#textArea').val());
						} else {
							cont = tinyMCE.activeEditor.getContent();
						}
					} else {
						cont = jQuery('textarea#textArea').val();
					}
					/*cont = cont.replace(/<span(.+?)font-size: 1rem(.+?)>(.+?)<\/span>/g,'$3');
					cont = cont.replace(/<(.+?) (.+?)font-size: 1rem(.+?)>(.+?)<\//g,'<$1>$4</');
					cont = cont.replace(/<(.+?) font-size: 1rem(.+?)>(.+?)<\//g,'<$1>$3</');*/
					cont = cont.replace(/<iframe(.+?)autoplay(.+?)>/g,'<iframe$1data-utoplay$2>');

					textA.val(cont);
					textCont.html(cont);

					var set;
					clearTimeout(set);
					set = setTimeout(function(){ setVisualContent(); },200);

					jQuery( this ).dialog( "close" );
				}
			},
			close: function(){
				jQuery('body').removeClass('overflow_hidden');
				jQuery('#pix-modal-overlay').remove();
				jQuery(window).unbind('resize');
			}
		});
		jQuery(window).bind('resize',function() {
			h = jQuery(window).height();
			jQuery(div).dialog('option',{'height':(h*0.8),'position':{ my: "center", at: "center", of: window }});
		}).triggerHandler('resize');
	});




	jQuery(document).off('click',"#pix_builder_canvas .pix_column_id");
	jQuery(document).on('click',"#pix_builder_canvas .pix_column_id",function(){
		var t = jQuery(this).parents('.pix_builder_column').eq(0),
			textA = jQuery('textarea',t),
			h = jQuery(window).height(),
			div = jQuery('#pix_builder_id_fields'),
			idT = typeof t.attr('data-id')!='undefined' ? t.attr('data-id') : '',
			classT = typeof t.attr('data-class')!='undefined' ? t.attr('data-class') : '';

		jQuery('input[data-use="id"]',div).val(idT);
		jQuery('input[data-use="class"]',div).val(classT);

		jQuery(div).dialog({
			height: 400,
			width: 400,
			modal: false,
			dialogClass: 'wp-dialog pix-dialog pix-page-builder-id',
			position: { my: "center", at: "center", of: window },
			title: 'Add some values',
			zIndex: 99,
			open: function(){
				jQuery(this).closest('.ui-dialog').find('.ui-button').eq(0).addClass('ui-dialog-titlebar-edit');
				jQuery('body').addClass('overflow_hidden').append('<div id="pix-modal-overlay" />');
				jQuery('#pix-modal-overlay').css({
					background: '#000000',
					bottom: 0,
					height: '100%',
					left: 0,
					opacity: 0.6,
					position: 'fixed',
					right: 0,
					top: 0,
					width: '100%',
					zIndex: 99
				});
			},
			buttons: {
				'': function() {
					t.attr('data-id', jQuery('[data-use="id"]',div).val());
					t.attr('data-class', jQuery('[data-use="class"]',div).val());
					jQuery( this ).dialog( "close" );
					var set = setTimeout(function(){ setVisualContent(); },200);
				}
			},
			close: function(){
				jQuery('body').removeClass('overflow_hidden');
				jQuery('#pix-modal-overlay').remove();
				div.find('input').val('');
				jQuery(window).unbind('resize');
			}
		});
		jQuery(window).bind('resize',function() {
			jQuery(div).dialog('option',{'position':{ my: "center", at: "center", of: window }});
		}).triggerHandler('resize');
	});




	jQuery(document).off('click',"#pix_builder_canvas .pix_column_clone");
	jQuery(document).on('click',"#pix_builder_canvas .pix_column_clone",function(){
		var t = jQuery(this),
			column = t.parents('.pix_column_active').eq(0),
			dataCol = parseFloat(column.attr('data-col')),
			section = column.parents('.pix_section_builder').eq(0),
			dataCols = parseFloat(section.attr('data-cols')),
			clone = column.clone(),
			textA = column.find('textarea').val(),
			lengCol = 0;
		jQuery('.pix_builder_column.pix_column_active',section).not('.pix_clone_column').each(function(){
			lengCol = lengCol + parseFloat(jQuery(this).attr('data-col'));
		});
		if ( dataCol <= (dataCols-lengCol) ) {
			clone.attr('data-col','0').css({display:'none'});
			column.after(clone);
			var i = 0;
			while (i<dataCol) {
				jQuery('.pix_builder_column:not(.pix_clone_column):last',section).remove();
				i++;
			}
			var set = setTimeout(function(){
				clone.fadeIn();
				clone.attr('data-col',dataCol);
				clone.find('textarea').val(textA);
				sortColumns();
				checkEmptyColumns();
				expandColumns();
				removeColumns();
				setVisualContent();
				columnWidth();
				getSelValue();
			},100);
		} else {
			t.parents('.pix_section_builder').eq(0).find('.pix_section_error').fadeIn(400,function(){jQuery(this).delay(1500).fadeOut();});
		}
	});




	jQuery(document).off('click',"#pix_builder_canvas .pix_section_clone");
	jQuery(document).on('click',"#pix_builder_canvas .pix_section_clone",function(){
		var t = jQuery(this),
			section = t.parents('.pix_section_builder').eq(0),
			clone = section.clone().hide(),
			textASection = jQuery('textarea.pix_section_txt',section).val();
		jQuery('.pix_builder_column.pix_column_active:not(".pix_clone_column")', section).each(function(){
			var textA =  jQuery('textarea',this).val(),
				ind = jQuery(this).index();
			jQuery('.pix_builder_column.pix_column_active textarea',clone).eq(ind).val(textA);
		});
		jQuery('textarea.pix_section_txt',clone).val(textASection);
		section.after(clone);
		clone.slideDown();
		sortColumns();
		expandColumns();
		addColumns();
		removeColumns();
		removeSections();
		setVisualContent();
		getSelValue();
	});




	jQuery(document).off('click',"#pix_builder_canvas .pix_section_id");
	jQuery(document).on('click',"#pix_builder_canvas .pix_section_id",function(){
		var t = jQuery(this).parents('.pix_section_builder').eq(0),
			textA = jQuery('textarea.pix_section_txt',t),
			h = jQuery(window).height(),
			div = jQuery('#pix_builder_id_fields'),
			idT = typeof t.attr('data-id')!='undefined' ? t.attr('data-id') : '',
			classT = typeof t.attr('data-class')!='undefined' ? t.attr('data-class') : '';

		jQuery('input[data-use="id"]',div).val(idT);
		jQuery('input[data-use="class"]',div).val(classT);

		jQuery(div).dialog({
			height: 300,
			width: 400,
			modal: false,
			dialogClass: 'wp-dialog pix-dialog pix-page-builder-id',
			position: { my: "center", at: "center", of: window },
			title: 'Add some values',
			zIndex: 99,
			open: function(){
				jQuery(this).closest('.ui-dialog').find('.ui-button').eq(0).addClass('ui-dialog-titlebar-edit');
				jQuery('body').addClass('overflow_hidden').append('<div id="pix-modal-overlay" />');
				jQuery('#pix-modal-overlay').css({
					background: '#000000',
					bottom: 0,
					height: '100%',
					left: 0,
					opacity: 0.6,
					position: 'fixed',
					right: 0,
					top: 0,
					width: '100%',
					zIndex: 99
				});
			},
			buttons: {
				'': function() {
					t.attr('data-id', jQuery('[data-use="id"]',div).val());
					t.attr('data-class', jQuery('[data-use="class"]',div).val());
					jQuery( this ).dialog( "close" );
					var set = setTimeout(function(){ setVisualContent(); },200);
				}
			},
			close: function(){
				jQuery('body').removeClass('overflow_hidden');
				jQuery('#pix-modal-overlay').remove();
				div.find('input').val('');
				jQuery(window).unbind('resize');
			}
		});
		jQuery(window).bind('resize',function() {
			jQuery(div).dialog('option',{'position':{ my: "center", at: "center", of: window }});
		}).triggerHandler('resize');
	});
}


function setBuilderContent() {

	var theDom,
		theBody,
		set, set2;

	if ( typeof tinyMCE !== "undefined" && typeof tinyMCE.get('content') !== "undefined" && tinyMCE.get('content') !== null ) {
		theDom = tinyMCE.get('content').dom.select('body');
		theBody = jQuery(theDom).html();
	} else {
		theBody = jQuery('textarea#content').val();
	}


	theBody = theBody.replace(/<p><\!--pixgridder(.+?)-->(?!<\!--)/g, '<!--pixgridder$1--><p>');
	theBody = theBody.replace(/<p><\!--\/pixgridder(.+?)-->(?!<\!--)/g, '<!--/pixgridder$1--><p>');
	theBody = theBody.replace(/<p><\!--pixgridder(.+?)--><\/p>/g, '<!--pixgridder$1-->');
	theBody = theBody.replace(/<p><\!--\/pixgridder(.+?)--><\/p>/g, '<!--/pixgridder$1-->');
	theBody = theBody.replace(/<p><\!--\/pixgridder(.+?)--><\/p>/g, '<!--/pixgridder$1-->');
	theBody = theBody.replace(/<\!--\/pixgridder(.+?)--><p><\/p>/g, '<!--/pixgridder$1-->');
	theBody = theBody.replace(/<!--pixgridder:row\[(.?[^\]\s]+)\]--><!--\/pixgridder:row(.+?)-->/g, '');
	theBody = theBody.replace(/<!--pixgridder:column\[(.?[^\]\s]+)\]--><!--\/pixgridder:column(.?)-->/g, '');
	theBody = theBody.replace(/<!--pixgridder:row\[cols=(.?)\] data(.+?)-->/g, '<div class="pix_builder_row" data-cols="$1" data$2>');
	theBody = theBody.replace(/<!--pixgridder:row\[cols=(.+?)\]-->/g, '<div class="pix_builder_row" data-cols="$1">');
	theBody = theBody.replace(/<!--\/pixgridder:row\[cols=(.+?)\]-->/g, '</div>');
	theBody = theBody.replace(/<!--pixgridder:column\[col=(.?)\] data(.+?)-->/g, '<div class="pix_builder_column" data-col="$1" data$2>');
	theBody = theBody.replace(/<!--pixgridder:column\[col=(.+?)\]-->/g, '<div class="pix_builder_column" data-col="$1">');
	theBody = theBody.replace(/<!--\/pixgridder:column\[col=(.+?)\]-->/g, '</div>');
	theBody = theBody.replace(/data-id\[(.+?)\]/g, 'data-id="$1"');
	theBody = theBody.replace(/data-class\[(.+?)\]/g, 'data-class="$1"');
	theBody = theBody.replace(/<iframe(.+?)autoplay(.+?)>/g,'<iframe$1data-utoplay$2>');

	jQuery('#pix_builder_yard').append(theBody);

	if (!jQuery('#pix_builder_yard .pix_builder_row').length || !jQuery('#pix_builder_yard .pix_builder_column').length) {
		clearTimeout(set);
		clearTimeout(set2);
		set = setTimeout(function(){
			jQuery('#pix_builder_canvas .pix_add_section').click();
			jQuery('.pix_section_builder').not('.pix_clone_section').find('.pix_add_column').click();
			if ( theBody != '' ) {
				var dataCols = jQuery('.pix_builder_column').eq(0).parents('.pix_section_builder').attr('data-cols');
				jQuery('.pix_builder_column').eq(0).attr('data-col',dataCols).find('textarea').val(theBody);
				jQuery('.pix_builder_content').eq(0).html(theBody);
				checkEmptyColumns();
				columnWidth();
			}
			jQuery('body').addClass('pixgridder_generated');
		},500);
		set2 = setTimeout(function(){
			setVisualContent();
		},600);
	} else {

		jQuery('#pix_builder_canvas').hide();

		jQuery('#pix_builder_yard .pix_builder_row').each(function(){

			var t = jQuery(this),
				dataCols = parseFloat(t.attr('data-cols')),
				dataId = t.attr('data-id'),
				dataClass = t.attr('data-class'),
				dataId = typeof t.attr('data-id')!='undefined' ? t.attr('data-id') : '',
				dataClass = typeof t.attr('data-class')!='undefined' ? t.attr('data-class') : '',
				cloneOriginal = jQuery('.pix_section_builder.pix_clone_section'),
				clone = cloneOriginal.clone(),
				idClass = t.clone().children().remove().end().html();

			if ( jQuery('.pix_builder_column',t).length ) {
				clone.hide().removeClass('pix_clone_section').attr('data-cols',dataCols).attr('data-id',dataId).attr('data-class',dataClass);
				cloneOriginal.before(clone);
				clone.show();
			}

			jQuery('.pix_builder_column',t).each(function(){
				var cloneCol = jQuery('.pix_builder_column.pix_clone_column', clone).clone(),
					html = jQuery(this).html(),
					dataCol = parseFloat(jQuery(this).attr('data-col')),
					dataId = typeof jQuery(this).attr('data-id')!='undefined' ? jQuery(this).attr('data-id') : '',
					dataClass = typeof jQuery(this).attr('data-class')!='undefined' ? jQuery(this).attr('data-class') : '';
				cloneCol.hide().removeClass('pix_clone_column');
				jQuery('textarea',cloneCol).val(html);
				jQuery('.pix_builder_content',cloneCol).html(html);

				cloneCol.attr('data-col',dataCol).attr('data-id',dataId).attr('data-class',dataClass).show();
				jQuery('.pix_builder_column',clone).not('.pix_column_active').eq(0).before(cloneCol);
			});

			clone.find('textarea.pix_section_txt').val(idClass);

		});


		set = setTimeout( function(){ 
			jQuery('.pix_section_template').change(); 
		},10);
		sortColumns();
		checkEmptyColumns();
		expandColumns();
		removeColumns();
		getSelValue();
		addColumns();
		clearTimeout(set);
		jQuery('body').addClass('pixgridder_generated');

		var pix_editor_tab = localStorage.getItem("pix_editor_tab");
		if ( pix_editor_tab == 'pix_builder' ) {
			jQuery('#pix_builder_canvas').show();
		}

	}

}

/********************************
*
*   Sort sections
*
********************************/
function sortSections(){
	jQuery('#pix_builder_canvas').sortable({
		handle: '.pix_section_mover',
		items: '.pix_section_builder_movable',
		placeholder: 'pix_section_builder_highlight',
		tolerance: 'pointer',
		stop: function( event, ui ) {
			setVisualContent();
		}
	});
}


/********************************
*
*   Sort columns
*
********************************/
function sortColumns(){
	jQuery('.pix_section_body_wrap').sortable({
		handle: '.pix_column_mover',
		items: '.pix_builder_column.pix_column_active',
		placeholder: 'pix_builder_column pix_column_builder_highlight',
		tolerance: 'pointer',
		start: function( event, ui ) {
			jQuery('.pix_column_builder_highlight',this).width( ui.item.width() );
			jQuery('#pix_builder_canvas, .pix_section_body').css({overflow:'visible'});
			setVisualContent();
		},
		stop: function( event, ui ) {
			jQuery('#pix_builder_canvas, .pix_section_body').css({overflow:'hidden'});
			setVisualContent();
		}
	});
}


/********************************
*
*   Check empty columns
*
********************************/
function checkEmptyColumns(){
	jQuery('.pix_section_body_wrap').each(function(){
		if(!jQuery('.pix_builder_column .pix_add_column.pix-ui',this).length){
			jQuery('.pix_builder_column',this).not('.pix_column_active').eq(0).append('<div class="pix_add_column pix-ui" style="display:none"><i class="pixgridder-icon-plus-1"></i></div>');
			jQuery('.pix_builder_column .pix_add_column',this).fadeIn(150);
		} else {
			jQuery('.pix_builder_column .pix_add_column',this).css({opacity:1});
		}
	});
}


/********************************
*
*   Add sections
*
********************************/
function addSections(){
	var clone;
	jQuery('#pix_builder_canvas').off('click','.pix_add_section');
	jQuery('#pix_builder_canvas').on('click','.pix_add_section',function(){
		cloneOriginal = jQuery('.pix_section_builder.pix_clone_section');
		clone = cloneOriginal.clone();
		clone.hide().removeClass('pix_clone_section');
		cloneOriginal.before(clone);
		clone.slideDown(400);
		addColumns();
		removeSections();
		setVisualContent();
		getSelValue();
	});
}


/********************************
*
*   Remove sections
*
********************************/
function removeSections(){
	jQuery(document).off('click','.pix_section_builder .pix_section_delete');
	jQuery(document).on('click','.pix_section_builder .pix_section_delete',function(){
		var t = jQuery(this).parents('.pix_section_builder').eq(0);
		t.slideUp(400,function(){
			t.remove();
			setVisualContent();
		});
	});
}


/********************************
*
*   Add columns
*
********************************/
function addColumns(){
	jQuery('.pix_section_body_wrap').each(function(){
		var t = jQuery(this),
			clone;
		t.off('click','.pix_add_column');
		t.on('click','.pix_add_column',function(){
			clone = jQuery('.pix_builder_column.pix_clone_column', t).clone();
			clone.hide().removeClass('pix_clone_column');
			clone.fadeIn();
			jQuery(this).parents('.pix_builder_column').after(clone).remove();
			sortColumns();
			checkEmptyColumns();
			expandColumns();
			removeColumns();
			setVisualContent();
		});
	});
}


/********************************
*
*   Add columns
*
********************************/
function removeColumns(){
	jQuery('.pix_builder_column').each(function(){
		var t = jQuery(this),
			parent = t.parents('.pix_section_body_wrap').eq(0),
			cols,
			i,
			setCol;
		t.off('click','.pix_column_delete');
		t.on('click','.pix_column_delete',function(){
			cols = parseFloat(t.attr('data-col')),
			i = 0;
			t.attr('data-col',0).fadeOut(400, function() {
					jQuery(this).remove();
					setVisualContent();
					for (var i = 0; i < cols; i++) {
						parent.append('<div class="pix_builder_column" data-col="0" />');
						parent.find('.pix_builder_column').not('.pix_clone_column').not('.pix_column_active').last().attr('data-col',1);
						checkEmptyColumns();
						columnWidth();
					}
			});
		});
	});
}


/********************************
*
*   Expand columns
*
********************************/
function expandColumns(){
	jQuery('.pix_builder_column').each(function(){
		var t = jQuery(this),
			parent = t.parents('.pix_section_body_wrap').eq(0),
			clone,
			col,
			totCols,
			maxCols,
			setCol;
		t.off('click','.pix_column_increase');
		t.on('click','.pix_column_increase',function(){
			totCols = 0;
			maxCols = parseFloat(t.parents('.pix_section_builder').eq(0).attr('data-cols'));
			jQuery('.pix_builder_column.pix_column_active', parent).not('.pix_clone_column').each(function(){
				totCols = totCols + parseFloat(jQuery(this).attr('data-col'));
			});
			col = parseFloat(t.attr('data-col'));
			if ( totCols < maxCols ) {
				t.attr('data-col',(col+1));
				parent.find('.pix_builder_column').not('.pix_clone_column').not('.pix_column_active').last().attr('data-col',0).fadeOut(400,function() {
						jQuery(this).remove();
				});
			} else {
				t.parents('.pix_section_builder').eq(0).find('.pix_section_error').fadeIn(400,function(){jQuery(this).delay(1500).fadeOut();});
			}
			columnWidth();
			setVisualContent();
		});

		t.off('click','.pix_column_reduce');
		t.on('click','.pix_column_reduce',function(){
			col = parseFloat(t.attr('data-col'));
			if ( col > 1 ) {
				t.attr('data-col',(col-1));
				parent.append('<div class="pix_builder_column" style="display:none" data-col="1" />');
				parent.find('.pix_builder_column').not('.pix_clone_column').not('.pix_column_active').last().fadeIn();
				checkEmptyColumns();
			} else {
				t.parents('.pix_section_builder').eq(0).find('.pix_section_error').fadeIn(400,function(){jQuery(this).delay(1500).fadeOut();});
			}
			columnWidth();
			setVisualContent();
		});
	});
}


/********************************
*
*   Columns width
*
********************************/
function columnWidth(){
	jQuery('.pix_builder_column[data-col]').each(function(){
		var divid = parseFloat(jQuery(this).attr('data-col')),
			per = parseFloat(jQuery(this).parents('.pix_section_builder[data-cols]').eq(0).attr('data-cols'));
		jQuery(this).css({width:100*(divid/per)+'%'});
	});
}


var pixGridderBuilderInit = function(){
	getSelValue();
	var set = setTimeout( function(){ jQuery('.pix_section_template').change(); },10);

	if ( pixgridder_display === true ) {
		pageBuilder();
		sortSections();
		sortColumns();
		checkEmptyColumns();
		addSections();
		removeSections();
		addColumns();
		setBuilderContent();
		columnWidth();
	}

	jQuery('#pix_builder_preview').resizable({ 
		handles: 'se',
		create: function( event, ui ) {
			var h = jQuery(this).height();
			jQuery('iframe',this).height(h);
		},
		stop: function( event, ui ) {
			var h = jQuery(this).height();
			jQuery('iframe',this).height(h);
			var data = {
				action: 'pixgridder_height_preview',
				height: h
			};
			jQuery.post(ajaxurl, data);
		}
	});
};