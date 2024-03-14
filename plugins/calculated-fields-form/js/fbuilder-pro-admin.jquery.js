	$.fbuilder[ 'typeList' ] = [];
	$.fbuilder[ 'categoryList' ] = [];
	$.fbuilder[ 'controls' ] = {};
	$.fbuilder[ 'deletedFields' ] = {};

	$.fbuilder[ 'displayedDuplicateContainerMessage' ] = false;
	$.fbuilder[ 'duplicateContainerMessage' ] = 'Note: If the container field being duplicated includes calculated fields or fields with dependency rules, the equations and dependencies rules in the new fields are exactly the same equations and dependency rules than in the original fields.';

	$.fbuilder['isNumeric'] = function(n){return !isNaN(parseFloat(n)) && isFinite(n);};

	$.fbuilder[ 'intializeDeletedFields' ] = function(){
		for(let i in $.fbuilder[ 'deletedFields' ] ) {
			$.fbuilder['deletedFields'][i] = 0;
		}
	};

	$.fbuilder[ 'checkDeletedFields' ] = function( v ){
		let result = '', separator = '';
		for(let i in $.fbuilder[ 'deletedFields' ] ) {
			if( (new RegExp('\\b'+i+'\\b', 'i')).test(v) ) {
				$.fbuilder['deletedFields'][i]++;
				result += separator+i;
				separator = ' | ';
			}
		}
		return result != '' ? ' [' + result + ']' : '';
	};

	$.fbuilder[ 'purgeDeletedFields' ] = function(){
		for(let i in $.fbuilder[ 'deletedFields' ] ) {
			if( ! $.fbuilder['deletedFields'][i] ) delete $.fbuilder['deletedFields'][i];
		}
	};

	$.fbuilder[ 'delete_form_preview_window' ] = function( e ){
		if('cff_form_preview_window' in window) {
			cff_form_preview_window.close();
			delete cff_form_preview_window;
		}
	};

	$.fbuilder[ 'preview' ] = function( e )
	{
		if ( 'cff_form_preview_window_flag' in window ) return;
		cff_form_preview_window_flag = true;

		var w = screen.width*0.8,
			h = screen.height*0.7,
			l = screen.width/2 - w/2,
			t = screen.height/2 - h/2,
			f  = $( e.form );

		$.fbuilder[ 'delete_form_preview_window' ]();
		cff_form_preview_window = window.open('', 'cff_form_preview_window', 'resizeable,scrollbars,width='+w+',height='+h+',left='+l+',top='+t),

		f.attr( 'target', 'cff_form_preview_window' );
		f.attr( 'method', 'POST' );
		f.append('<input type="hidden" name="preview" value="1" />');

		setTimeout(function(){
			f.submit();
			f.attr( 'target', '_self' ).find( 'input[name="preview"]').remove();
			delete cff_form_preview_window_flag;
			cff_form_preview_window.focus();
		}, 500);
	};

    $.fbuilder['printFields'] = function(){
        var h = '<div><b>field name (title) [Exclude from submission]</b></div><hr />', w;
        $.each(window.cff_form.fBuild.getItems(), function(i, item){
            h += '<div>'+item.name;
            if('title' in item) h += ' ('+item.title+')';
            if('exclude' in item && item.exclude) h += '[EXCLUDED]';
            h += '</div>';
        });
        w = window.open("","cff-fieldlist-popup", "width=500,height=300,scrollbars=1,resizable=1");
        w.document.title = 'Fields List';
        w.document.body.innerHTML = h;
    };

	$.fbuilder[ 'htmlEncode' ] = window[ 'cff_esc_attr' ] = function(value)
	{
		value = $('<div/>').text(value).html();
		value = value.replace(/&/g, '&amp;')
					 .replace(/"/g, "&quot;")
					 .replace(/&amp;lt;/g, '&lt;')
					 .replace(/&amp;gt;/g, '&gt;');
		value = value.replace(/&amp;/g, '&');
		return value;
	};

    $.fbuilder['sanitize'] = window['cff_sanitize'] = function(value)
	{
        if(typeof value == 'string')
            value = value.replace(/<script\b.*\bscript>/ig, '')
                         .replace(/<script[^>]*>/ig, '')
                         .replace(/(\b)(on[a-z]+)\s*=/ig, "$1_$2=");
		return value;
	};

	$.fbuilder['htmlDecode'] = window['cff_html_decode'] = function(value)
	{
		if( /&(?:#x[a-f0-9]+|#[0-9]+|[a-z0-9]+);?/ig.test( value ) ) value = $( '<div/>' ).html( value ).text();
		return value;
	};

	$.fbuilder[ 'escapeSymbol' ] = function( value ) // Escape the symbols used in regulars expressions
	{
		return value.replace(/([\^\$\-\.\,\[\]\(\)\/\\\*\?\+\!\{\}])/g, "\\$1");
	};

	$.fbuilder[ 'parseVal' ] = function( value, thousandSeparator, decimalSymbol )
	{
		if( value == '' ) return 0;
		value += '';

		thousandSeparator = new RegExp( $.fbuilder.escapeSymbol( ( typeof thousandSeparator == 'undefined' ) ? ',' : thousandSeparator ), 'g' );
		decimalSymbol = new RegExp( $.fbuilder.escapeSymbol( ( typeof decimalSymbol == 'undefined' || /^\s*$/.test( decimalSymbol ) ) ? '.' : decimalSymbol ), 'g' );

		var t = value.replace( thousandSeparator, '' ).replace( decimalSymbol, '.' ).replace( /\s/g, '' ),
			p = /[+\-]?((\d+(\.\d+)?)|(\.\d+))(?:[eE][+\-]?\d+)?/.exec( t );

		return ( p ) ? p[0]*1 : '"' + value.replace(/'/g, "\\'").replace( /\$/g, '') + '"';
	};

    $.fbuilder[ 'showErrorMssg' ] = function( str ) // Display an error message
    {
        $( '.form-builder-error-messages' ).html( '<div class="error-text">' + str + '</div>' );
    };

    // fbuilder plugin
	$.fn.fbuilder = function(){
		var typeList = 	$.fbuilder.typeList,
			categoryList = $.fbuilder.categoryList;

		$.fbuilder[ 'getNameByIdFromType' ] = function( id )
			{
				for ( var i = 0, h = typeList.length; i < h; i++ )
				{
					if ( typeList[i].id == id )
					{
						return  typeList[i].name;
					}
				}
				return "";
			};

		for ( var i=0, h = typeList.length; i < h; i++ )
		{
			var category_id = typeList[ i ].control_category;

			if( typeof categoryList[ category_id ]  == 'undefined' )
			{
				categoryList[ category_id ] = { title : '', description : '', typeList : [] };
			}
			else if( typeof categoryList[ category_id ][ 'typeList' ]  == 'undefined' )
			{
				categoryList[ category_id ][ 'typeList' ] = [];
			}

			categoryList[ category_id ].typeList.push( i );
		}

		let title_margin = '0';
		for ( var i in categoryList )
		{
			$("#tabs-1").append('<div style="clear:both;"></div><div style="margin-top:' + title_margin + '">'+categoryList[ i ].title+'</div><hr />');

			title_margin = '20px;';

			if( typeof categoryList[ i ][ 'description' ] != 'undefined' && !/^\s*$/.test( categoryList[ i ][ 'description' ] ) )
			{
				$("#tabs-1").append('<div style="clear:both;"></div><div class="category-description">'+categoryList[ i ].description+'</div>');
			}

			if( typeof categoryList[ i ][ 'typeList' ]  != 'undefined' )
			{
				for( var j = 0, k = categoryList[ i ].typeList.length; j < k; j++ )
				{
					var index = categoryList[ i ].typeList[ j ];
					$("#tabs-1").append('<div class="button itemForm width40" id="'+typeList[ index ].id+'">'+typeList[ index ].name+'</div>');
				}
			}
		}

		$("#tabs-1").append('<div class="clearer"></div>');
		$( ".button").button();
        $(document).on('mousedown', function(){$.fbuilder.mousedown = 1;})
                   .on('mouseup', function(){$.fbuilder.mousedown = 0;})
                   .on('mouseover', '.ctrlsColumn .itemForm:not(#fCalculated)', function(){
                       $(this).addClass('button-primary');
                   })
                   .on('mouseout', '.ctrlsColumn .itemForm', function(){
                        if(!('mousedown' in $.fbuilder) || !$.fbuilder.mousedown)
                                $(this).removeClass('button-primary');
                   });

		// Create a items object
		var items = [],
            fieldsIndex = {},
            selected = -3;

		$.fbuilder[ 'editItem' ] = function( id )
			{
                selected = id;

                try
                {
                    $('#tabs-2').html( items[id].showAllSettings() );
                } catch (e) {}
				items[id].editItemEvents();
				setTimeout(function(){try{$('#tabs-2 .choicesSet select:visible, #tabs-2 .cf_dependence_field:visible, #tabs-2 #sSelectedField, #tabs-2 #sFieldList').chosen({search_contains: true});}catch(e){}}, 50);
			};

		$.fbuilder[ 'removeItem' ] = function( index )
			{
				$.fbuilder['deletedFields'][items[ index ]['name']] = 0;
				if( typeof items[ index ][ 'remove' ] != 'undefined' ) items[ index ][ 'remove' ]();
				items[ index ] = 0;
				selected = -2;
				$('#tabs').tabs("option", "active", 0);
			};

		$.fbuilder[ 'duplicateItem' ] = function( index, parentItem )
			{
				var n = 0, i, h, item, nIndex, duplicate = items[index];
				for ( i in fieldsIndex ) if( /fieldname/.test( i ) ) n = Math.max( parseInt( i.replace( /fieldname/g,"" ) ), n );

				item = $.extend( true, {}, duplicate, { name:"fieldname"+(n+1) } );
				if( typeof item[ 'fields' ] != 'undefined' && typeof item[ 'duplicateItem' ] != 'undefined') item[ 'fields' ] = [];
				if( typeof parentItem != 'undefined' ) item[ 'parent' ] = parentItem;
				else
				{
					/* Check if the parent is a container, and insert the new item as child of parent */
					if(
						duplicate[ 'parent' ] != '' &&
						typeof items[ fieldsIndex[ duplicate[ 'parent' ] ] ][ 'duplicateItem' ] != 'undefined'
					)
					items[ fieldsIndex[ duplicate[ 'parent' ] ] ][ 'duplicateItem' ]( duplicate.name, item['name'] );
				}

				// Insert the duplicated item just below the original
				nIndex = index*1+1;
				items.splice( nIndex, 0,  item);
				fieldsIndex[ item[ 'name' ] ] = nIndex;
				i = nIndex; h = items.length;
				for ( i; i<h; i++ ) // Correct the rest of indices
				{
					items[i].index = i;
					fieldsIndex[ items[i].name ] = i;
				}

				// The duplicated item is a container
				if( typeof item[ 'duplicateItem' ] != 'undefined' )
				{
					// Alert Message
					if( !$.fbuilder[ 'displayedDuplicateContainerMessage' ] )
					{
						alert( $.fbuilder[ 'duplicateContainerMessage' ] );
						$.fbuilder[ 'displayedDuplicateContainerMessage' ] = true;
					}

					i = 0; h = duplicate[ 'fields' ].length;
					for( i; i < h; i++ )
					{
						item[ 'fields' ][ i ] = $.fbuilder[ 'duplicateItem' ]( fieldsIndex[duplicate[ 'fields' ][ i ]], item[ 'name' ] );
					}
				}
				return item[ 'name' ];
			};

		$.fbuilder[ 'editForm' ] = function()
			{
				$('#tabs-3').html(theForm.showAllSettings());
				selected = -1;

				$("#fTitle").on( 'keyup', function()
				{
					theForm.title = $(this).val();
					$.fbuilder.reloadItems({'form':1});
				});

				$("#fTitleTag").on( 'change', function()
				{
					theForm.titletag = $(this).val();
					$.fbuilder.reloadItems({'form':1});
				});

				$("[name='fTextAlign']").on( 'click', function()
				{
					theForm.textalign = $("[name='fTextAlign']:checked").val();
					$.fbuilder.reloadItems({'form':1});
				});

				$("#fHeaderColor").on('change input', function()
				{
					theForm.headertextcolor = $(this).val();
					$.fbuilder.reloadItems({'form':1});
				});

				$("#fEvalEquations").on( 'click', function()
				{
					theForm.evalequations = ($(this).is( ':checked' )) ? 1 : 0;
					$.fbuilder.reloadItems({'form':1});
				});

				$("#fEvalEquationsDelay").on( 'click', function()
				{
					theForm.evalequations_delay = ($(this).is( ':checked' )) ? 1 : 0;
					$.fbuilder.reloadItems({'form':1});
				});

				$("#fAnimateForm").on( 'click', function()
				{
					theForm.animate_form = ($(this).is( ':checked' )) ? 1 : 0;
					$.fbuilder.reloadItems({'form':1});
				});

				$("#fAnimationEffect").on( 'change', function()
				{
					theForm.animation_effect = $(this).val();
					$.fbuilder.reloadItems({'form':1});
				});

				$("[name='fEvalEquationsEvent']").on( 'change', function()
				{
					theForm.evalequationsevent = $("[name='fEvalEquationsEvent']:checked").val();
					$.fbuilder.reloadItems({'form':1});
				});

				$("#fDirection").on( 'click', function()
				{
					theForm.direction = $(this).val();
					$.fbuilder.reloadItems({'form':1});
				});

				$("#fLoadingAnimation").on( 'click', function()
				{
					theForm.loading_animation = ($(this).is( ':checked' )) ? 1 : 0;
					$.fbuilder.reloadItems({'form':1});
				});

				$("#fAutocomplete").on( 'click', function()
				{
					theForm.autocomplete = ($(this).is( ':checked' )) ? 1 : 0;
					$.fbuilder.reloadItems({'form':1});
				});

				$("#fPersistence").on( 'click', function()
				{
					theForm.persistence = ($(this).is( ':checked' )) ? 1 : 0;
					$.fbuilder.reloadItems({'form':1});
				});

				$("#fDescription").on( 'keyup', function()
				{
					theForm.description = $(this).val();
					$.fbuilder.reloadItems({'form':1});
				});

				$("#fLayout").on( 'change', function()
				{
					theForm.formlayout = $(this).val();
					$.fbuilder.reloadItems();
				});

				$("#fTemplate").on( 'change', function()
				{
					theForm.formtemplate = $(this).val();
					var template 	= $.fbuilder.showSettings.formTemplateDic[ theForm.formtemplate ],
						thumbnail	= '',
						description = '';

					if( typeof template != 'undefined' )
					{
						if( typeof template[ 'thumbnail' ] != 'undefined' )
						{
							thumbnail = '<img src="' + template[ 'thumbnail' ] + '">';
						}
						if( typeof template[ 'description' ] != 'undefined' )
						{
							description = template[ 'description' ];
						}
					}
					$( '#fTemplateThumbnail' ).html( thumbnail );
					$( '#fTemplateDescription' ).html( description );
					$.fbuilder.reloadItems({'form':1});
				});

				$("#fCustomStyles").on( 'change', function()
				{
					theForm.customstyles = $(this).val();
					$.fbuilder.reloadItems({'form':1});
				});

				// CSS Editor
				if( 'codeEditor' in wp)
				{
					var cssEditorSettings = wp.codeEditor.defaultSettings ? _.clone( wp.codeEditor.defaultSettings ) : {},
						editor;
					cssEditorSettings.codemirror = _.extend(
						{},
						cssEditorSettings.codemirror,
						{
							indentUnit: 2,
							tabSize: 2,
							mode: 'css'
						}
					);
					editor = wp.codeEditor.initialize( $('#fCustomStyles'), cssEditorSettings );
					editor.codemirror.on('change', function(cm){ $('#fCustomStyles').val(cm.getValue()).trigger('change');});
					editor.codemirror.on('keydown', function(cm, evt){
						if ( 'Escape' == evt.key && $('.CodeMirror-hint').length ) {
							evt.stopPropagation();
						}
					});

					$('.cff-editor-extend-shrink').on('click', function(){
						let e = $(this).closest('.cff-editor-container'),
							c = e.closest('.ctrlsColumn');
						e.toggleClass('fullscreen');
						if(e.hasClass('fullscreen')) c.css('z-index', 99991);
						else c.css('z-index', 999);
					});
				}
			};

		$.fbuilder[ 'defineGeneralEvents' ] = function()
			{
				// Fields events
				$(document).on(
					{
						'click' : function(evt){
							$.fbuilder[ 'editItem' ]($(this).attr("id").replace("field-",""));
							$( '#fieldlist .ui-selected' ).removeClass("ui-selected");
							$(this).addClass("ui-selected");
							$('#tabs').tabs("option", "active", 1);
							evt.stopPropagation();
						},
						'mouseover' : function(evt){
							$(this).addClass("ui-over");
							evt.stopPropagation();
						},
						'mouseout' : function(evt){
							$(this).removeClass("ui-over");
							evt.stopPropagation();
						}
					},
					'.fields'
				);

				$(document).on('focus', '.field', function(){$(this).trigger('blur');});

				// Handle events
				$(document).on('click', '.fields .remove', function(evt){
					evt.stopPropagation();
					$.fbuilder[ 'removeItem' ]($(this).parent().attr("id").replace("field-",""));
					items = $.grep( items, function( e ){ return (e != 0 ); } );
					$.fbuilder.reloadItems();
				});

				$(document).on('click', '.fields .copy', function(evt){
					evt.stopPropagation();
					$.fbuilder[ 'duplicateItem' ]($(this).parent().attr("id").replace("field-",""));
					$('#tabs').tabs("option", "active", 0);
					$.fbuilder.reloadItems();
				});

				$(document).on('click', '.fields .collapse', function(evt){
					evt.stopPropagation();
					var f = $(this).closest('.fields'),
						i = f.attr("id").replace("field-",""),
						item = ffunct.getItems()[i];

					item['collapsed'] = true;
					f.addClass('collapsed');
					$.fbuilder.reloadItems({'field': item});
				});

				$(document).on('click', '.fields .uncollapse', function(evt){
					evt.stopPropagation();
					var f = $(this).closest('.fields'),
						i = f.attr("id").replace("field-",""),
						item = ffunct.getItems()[i];

					item['collapsed'] = false;
					f.removeClass('collapsed');
					$.fbuilder.reloadItems({'field': item});
				});

				// Title and subtitle section events
				$(document).on(
					{
						'mouseover' : function(){
							$(this).addClass("ui-over");
						},
						'mouseout' : function(){
							$(this).removeClass("ui-over");
						},
						'click' : function(evt){
							evt.stopPropagation();
							$('#tabs').tabs("option", "active", 2);
							$.fbuilder.editForm();
							$(this).siblings().removeClass("ui-selected");
							$(this).addClass("ui-selected");
						}
					},
					'.fform'
				);

				// Dashboard event
				$(document).on('click', '.expand-shrink', function(){
					$(this).toggleClass( 'ui-icon-triangle-1-e ui-icon-triangle-1-w' );
					$('.form-builder .ctrlsColumn').toggleClass( 'expanded' );
				});

				$(document).on('click', '#fbuilder', function(evt)
					{
						evt.stopPropagation();
						selected = -2;
						$(".fform").removeClass("ui-selected")
						$( '#fieldlist .ui-selected' ).removeClass("ui-selected");
						$('#tabs').tabs("option", "active", 0);
					}
				);

				$(document).on('click', '.cff-form-builder-extend-shrink [name="cff_expand_btn"]', function(){
					$('#metabox_form_structure').addClass('fullscreen');
					document.getElementById('metabox_form_structure').scrollIntoView();
				});

				$(document).on('click', '.cff-form-builder-extend-shrink [name="cff_shrink_btn"]', function(){
					$('#metabox_form_structure').removeClass('fullscreen');
				});

				$(document).on('keydown', function(evt){
					if ( 'Escape' == evt.key ) {
						if ( $('#cff-advanced-equation-editor:visible').length ) {
							return;
						} else if ( $('.cff-editor-container.fullscreen').length ) {
							$('.cff-editor-extend-shrink').trigger('click');
						} else if ( $('#metabox_form_structure.fullscreen').length ) {
							$('.cff-form-builder-extend-shrink [name="cff_shrink_btn"]').trigger('click');
						}
					}
				});
			};

		$.fbuilder[ 'reloadItems' ] = function( args )
			{
				function replaceFieldTags( field )
				{
					if( typeof field[ 'display' ] != 'undefined' )
					{
						var e  = $('.'+field['name']),
							n  = $(cff_sanitize(field.display()).replace(/(\b)fields(\b)/i, '$1fields$2'+('fieldlayout' in field && field.fieldlayout != 'default' ? ' '+field.fieldlayout : ''))),
							as = true; // Call after_show

						if( n.find( '.dfield:eq(0)>.fcontainer>.fieldscontainer').length )
						{
							n.find( '.dfield:eq(0)>.fcontainer>.fieldscontainer')
							 .replaceWith( e.find( '.dfield:eq(0)>.fcontainer>.fieldscontainer' ) );
							as = false;
						}
						e.replaceWith(n);
						if( as && typeof field[ 'after_show' ] != 'undefined') field.after_show();
						$("#field-"+field.index).addClass("ui-selected");
					}
				} // End replaceFieldTags

				function replaceTitleDescTags()
				{
					$("#formheader").html(theForm.display());
				} // End replaceTitleDescTags

				var default_args = {
						'field' : {},
						'form'  : false
					};

				args = $.extend(true, {}, default_args, ( typeof args != 'undefined' ) ? args : {} );
				if( !$.isEmptyObject( args[ 'field' ] ) )
				{
					replaceFieldTags( args[ 'field' ] );
				}
				else if( args['form'] )
				{
					replaceTitleDescTags();
				}
				else
				{
					var	email_str = '', // email fields list
						cu_user_email_field = ($('#cu_user_email_field').attr("def") || '').split( ',' ),

						cost_str = '', // fields list for paypal request
						request_cost = $('#request_cost').attr("def"),

						recurrent_str = '', // fields list for recurrent payments
						paypal_recurrent_field = $('[name="paypal_recurrent_field"]').attr("def"),

						paypal_price_field = $('#paypal_price_field').attr("def"), // fields for times intervals in recurrent payments
						interval_fields_str = '<option value="" '+( ('' == paypal_price_field ) ? "selected" : "" )+'> ---- No ---- </option>';

					// Set the correct fields alignment class
					for ( var i=0, h = $.fbuilder.showSettings.formlayoutList.length; i < h; i++ )
					{
						$("#fieldlist").removeClass( $.fbuilder.showSettings.formlayoutList[i].id );
					}
					$("#fieldlist").addClass(theForm.formlayout);

					replaceTitleDescTags();
					$("#fieldlist").html("");
					fieldsIndex = {};

					$.fbuilder[ 'intializeDeletedFields' ]();
					for ( var i=0, h = items.length; i < h; i++ )
					{
						var item = items[i];

						item.index = i;
						item.parent = '';
						fieldsIndex[ item.name ] = i;

						$("#fieldlist").append(cff_sanitize(item.display()).replace(/(\b)fields(\b)/i, '$1fields$2'+('fieldlayout' in item && item.fieldlayout != 'default' ? ' '+item.fieldlayout : '')));
						if ( i == selected )
						{
							$("#field-"+i).addClass("ui-selected");
							if( $('#tabs').tabs("option", "active") != 1 )
							{
								$.fbuilder[ 'editItem' ]( i );
							}
						}
						else
						{
							$("#field-"+i).removeClass("ui-selected");
						}

						// Email fields
						if (item.ftype=="femail" || item.ftype=="femailds")
						{
                            email_str += '<option value="'+cff_esc_attr(item.name)+'" '+( ( $.inArray( item.name, cu_user_email_field ) != -1 ) ? "selected" : "" )+'>'+cff_esc_attr(item.name+' ('+cff_sanitize(item.title)+')')+'</option>';
						}
						else
						{
							// Request cost fields
                            if(!/(femail)|(fdate)|(ffile)|(fpassword)|(fphone)|(fsectionbreak)|(fpagebreak)|(fsummary)|(fcontainer)|(ffieldset)|(fdiv)|(fmedia)|(fbutton)|(fhtml)|(frecordsetds)|(fcommentarea)/i.test(item.ftype))
							{
                                cost_str += '<option value="'+cff_esc_attr(item.name)+'" '+( ( item.name == request_cost ) ? "selected" : "" )+'>'+cff_esc_attr(item.name+'('+cff_sanitize(item.title)+')')+'</option>'
							}

							// Recurrent Payments
                            if (item.ftype=="fradio" || item.ftype=="fdropdown" || item.ftype=="fCalculated")
							{
                                recurrent_str += '<option value="'+cff_esc_attr(item.name)+'" '+( ( item.name == paypal_recurrent_field ) ? "selected" : "" )+'>'+cff_esc_attr(item.name+' ('+cff_sanitize(item.title)+')')+'</option>';
							}

							// Times Intervals
                            interval_fields_str += '<option value="'+cff_esc_attr(item.name)+'" '+( ( item.name == paypal_price_field ) ? "selected" : "" )+'>'+cff_esc_attr(cff_sanitize(item.title))+'</option>';
						}
					}
					$.fbuilder[ 'purgeDeletedFields' ]();

					// Assign the email fields to the "cu_user_email_field" list
					$('#cu_user_email_field').html(email_str);

					// Assign the fields to the "request_cost" list
					$('#request_cost').html(cost_str);

					// Assign the fields to the "paypal_recurrent_field" list
					$('[name="paypal_recurrent_field"]').html(recurrent_str);

					// Assign the fields to the "paypal_price_field" list
					$('#paypal_price_field').html(interval_fields_str);

					for ( var i=0, h = items.length; i < h; i++ )
					{
						if( typeof items[ i ].after_show != 'undefined' ) items[ i ].after_show();
					}
				}

				ffunct.saveData("form_structure");
                $(document).trigger('cff_reloadItems', items);
			};

		var fform=function(){};
		$.extend(fform.prototype,
			{
				title:"Untitled Form",
				titletag:"H2",
				textalign:"default",
				headertextcolor:"",
				description:"This is my form. Please fill it out. It's awesome!",
				formlayout:"top_aligned",
				formtemplate:$.fbuilder.default_template,
                evalequations:1,
                evalequations_delay:0,
                evalequationsevent:2,
				direction:'ltr',
                loading_animation:0,
                autocomplete:1,
				persistence:0,
                animate_form:0,
                animation_effect:'fade',
				customstyles:"",
				display:function()
				{
					let css = '';
					if(this.textalign != 'default') css+='text-align:'+this.textalign+';'
					if(this.headertextcolor != '') css+='color:'+this.headertextcolor+';';
					return cff_sanitize('<div class="fform" id="field"><div class="arrow ui-icon ui-icon-play "></div><'+this.titletag+' style="'+css+'">'+this.title+'</'+this.titletag+'><span style="display:block;'+css+'">'+this.description+'</span></div>');
				},

				showAllSettings:function()
				{
					var me 			= this,
						layout 	    = '',
						template    = '<option value="">Use default template</option>',
						thumbnail   = '',
						description = '',
						selected    = '',
						str 		= '';

					for ( var i = 0; i< $.fbuilder.showSettings.formlayoutList.length; i++ )
					{
						layout += '<option value="'+cff_esc_attr($.fbuilder.showSettings.formlayoutList[i].id)+'" '+(($.fbuilder.showSettings.formlayoutList[i].id==me.formlayout)?"selected":"")+'>'+cff_esc_attr($.fbuilder.showSettings.formlayoutList[i].name)+'</option>';
					}

					for ( var i in $.fbuilder.showSettings.formTemplateDic )
					{
						if( /^\s*$/.test( i ) ) break;
						selected = '';
						if( $.fbuilder.showSettings.formTemplateDic[i].prefix==me.formtemplate )
						{
							selected = 'SELECTED';
							if( typeof $.fbuilder.showSettings.formTemplateDic[i].thumbnail != 'undefined' )
							{
								thumbnail = '<img src="'+$.fbuilder.showSettings.formTemplateDic[i].thumbnail+'">';
							}

							if( typeof $.fbuilder.showSettings.formTemplateDic[i].description != 'undefined' )
							{
								description = $.fbuilder.showSettings.formTemplateDic[i].description;
							}
						}

						template += '<option value="'+cff_esc_attr($.fbuilder.showSettings.formTemplateDic[i].prefix)+'" ' + selected + '>'+cff_esc_attr($.fbuilder.showSettings.formTemplateDic[i].title)+'</option>';
					}

					str += '<div><label>Form Name</label><input type="text" class="large" name="fTitle" id="fTitle" value="'+cff_esc_attr(me.title)+'" /></div>'+
					'<div><label>Form Name Tag</label><select class="large" id="fTitleTag" name="fTitleTag">'+
					['H1', 'H2', 'H3', 'H4', 'H5', 'H6'].reduce(function(o, t){ return o += '<option value="'+t+'" '+(t == me.titletag ? 'SELECTED' : '')+'>'+t+'</option>';}, '')+
					'</select></div>'+
					'<div><label>Description</label><textarea class="large" name="fDescription" id="fDescription">'+cff_esc_attr(me.description)+'</textarea></div>'+

					'<div><label>Text Align</label>'+
					'<div class="cff-radio-group-ctrl">'+
					'<label><input type="radio" name="fTextAlign" value="default" '+(me.textalign == 'default' ? 'checked' : '')+'><span>Default</span></label>'+
					'<label><input type="radio" name="fTextAlign" value="left" '+(me.textalign == 'left' ? 'checked' : '')+'><span>Left</span></label>'+
					'<label><input type="radio" name="fTextAlign" value="center" '+(me.textalign == 'center' ? 'checked' : '')+'><span>Center</span></label>'+
					'<label><input type="radio" name="fTextAlign" value="right" '+(me.textalign == 'right' ? 'checked' : '')+'><span>Right</span></label></div></div>'+

					'<div style="margin-top:10px;"><label style="display:inline-block;">Text Color</label> <input type="color" id="fHeaderColor" name="fHeaderColor" value="'+me.headertextcolor+'"></div>'+
					/* General Settings */
					'<hr style="margin-top:10px;" />'+
					'<h3>Form Settings</h3>'+
					'<div><label>Label Placement</label><select name="fLayout" id="fLayout" class="large">'+layout+'</select></div>'+
					'<div><label>Direction</label><select name="fDirection" id="fDirection" class="large"><option value="ltr" '+(me.direction == 'ltr' ? 'SELECTED' : '')+'>LTR</option><option value="rtl" '+(me.direction == 'rtl' ? 'SELECTED' : '')+'>RTL</option></select></div>'+
					'<div><label><input type="checkbox" name="fLoadingAnimation" id="fLoadingAnimation" '+( ( me.loading_animation ) ? 'CHECKED' : '' )+' /> Display loading form animation</label></div><div><label><input type="checkbox" name="fAutocomplete" id="fAutocomplete" '+( ( me.autocomplete ) ? 'CHECKED' : '' )+' /> Enable autocompletion</label></div><div><label><input type="checkbox" name="fPersistence" id="fPersistence" '+( ( me.persistence ) ? 'CHECKED' : '' )+' /> Enable the browser\'s persistence (the data are stored locally on browser)</label></div>';

					if(typeof $.fbuilder.controls[ 'fCalculated' ] != 'undefined')
					{
						str += '<hr />';
						str += '<div><label><input type="checkbox" name="fEvalEquations" id="fEvalEquations" '+( ( me.evalequations ) ? 'CHECKED' : '' )+' /> Dynamically evaluate the equations associated with the calculated fields</label></div>';
						str += '<div><label><input type="checkbox" name="fEvalEquationsDelay" id="fEvalEquationsDelay" '+( ( me.evalequations_delay ) ? 'CHECKED' : '' )+' /> Delay the equations evaluation (evaluate the equations after rendering the form)</label></div>';

						str += '<div class="groupBox"><label><input type="radio" name="fEvalEquationsEvent" name="fEvalEquationsEvent" value="1" '+( ( me.evalequationsevent == 1 ) ? 'CHECKED' : '' )+' /> Eval the equations in the onchange events</label><label><input type="radio" name="fEvalEquationsEvent" name="fEvalEquationsEvent" value="2" '+( ( 'undefined' == typeof me.evalequationsevent || me.evalequationsevent == 2 ) ? 'CHECKED' : '' )+' /> Eval the equations in the onchange and keyup events</label></div>';
						str += '<hr />';
					}

					str += '<div><label>Form Template</label><select name="fTemplate" id="fTemplate" class="large">'+template+'</select></div><div style="text-align:center;padding:10px 0;"><span id="fTemplateThumbnail">'+thumbnail+'</span><div></div><span  id="fTemplateDescription">'+description+'</span></div>'+
                    '<div><label><input type="checkbox" name="fAnimateForm" id="fAnimateForm" '+( ( me.animate_form ) ? 'CHECKED' : '' )+' /> Animate page breaks in multipage forms, and dependencies</label></div>'+
                    '<div><label>Animation effect</label><select name="fAnimationEffect" id="fAnimationEffect" class="large">'+
					'<option value="fade" '+( (me.animation_effect == 'fade') ? 'SELECTED' : '' )+'>Fade</option>'+
					'<option value="slide" '+( (me.animation_effect == 'slide') ? 'SELECTED' : '' )+'>Slide</option>'+
					'</select></div>'+
                    '<div class="cff-editor-container"><label><div class="cff-editor-extend-shrink" title="Fullscreen"></div>Customize Form Design <i>(Enter the CSS rules. <a href="http://cff.dwbooster.com/faq#q82" target="_blank">More information</a>)</i></label><textarea id="fCustomStyles" style="width:100%;height:150px;">'+cff_esc_attr(me.customstyles)+'</textarea></div>' ;

					return str;
				}
			}
		);

		var theForm = new fform();
		$("#fieldlist").sortable(
			{
				'connectWith': '.ui-sortable',
				'delay': 500,
				'distance': 5,
				'items': '.fields',
				'placeholder': 'ui-state-highlight',
				'tolerance': 'pointer',
				'update': function( event, ui )
				{
                    var index = ui.item.index('#fieldlist>div');
                    if(0<=index)
                    {
                        if(ui.item.hasClass('cff-button-drag')) // It is a new control
                        {
                            ui.item = $('.'+window['cff_form'].fBuild.addItem(ui.item.data('control'), -3).name);
                        }
                        var i, h = items.length;
                        for( i = 0; i < h; i++ )
                        {
                            if( ui.item.hasClass(items[i].name)) break;
                        }

                        if( index )
                        {
                            var prev = $('#fieldlist>div:eq('+(index-1)+')');
                            for( var j = 0; j < h; j++ )
                            {
                                if( prev.hasClass(items[j].name) )
                                {
                                    index = (i<=j) ? j : ++j;
                                    break;
                                }
                            }
                        }

                        items.splice( index, 0,  items.splice( i, 1 )[ 0 ] );
                        $.fbuilder.reloadItems();
                        $('.'+/((fieldname)|(separator))\d+/.exec(ui.item.attr('class'))[0]).trigger('click');
                    }
                    else
                    {
                        // remove
                        try
                        {
                            var i, h = items.length;
                            for( i = 0; i < h; i++ ) if( ui.item.hasClass(items[i].name)) break;
                            items = items.concat( items.splice( i, 1 ) );
                        }
                        catch(err){}
                    }
				}
			}
		);

		$('#tabs').tabs(
			{
				activate: function(event, ui)
					{
						switch( $(this).tabs( "option", "active" ) )
						{
							case 0:
								$(".fform").removeClass("ui-selected");
							break;
							case 1:
								$(".fform").removeClass("ui-selected");
								if (selected < 0)
								{
								   $('#tabs-2').html('<b>No Field Selected</b><br />Please click on a field in the form preview on the right to change its properties.');
								}
							break;
							case 2:
								$(".fields").removeClass("ui-selected");
								$(".fform").addClass("ui-selected");
								$.fbuilder.editForm();
							break;
						}
					}
			}
		);

	    var ffunct = {
	        getFieldsIndex: function()
			{
			   return fieldsIndex;
		    },
		    getItems: function()
			{
			   return items;
		    },
		    addItem: function(id, _selected)
			{
			    var obj = new $.fbuilder.controls[id](),
					fBuild = this,
					n = 0;

                selected = _selected || selected;

                obj.init();
				for ( var i in fieldsIndex ) if( /fieldname/.test( i ) ) n = Math.max( parseInt( i.replace( /fieldname/g,"" ) ), n );
			    n++;

				obj.fBuild = fBuild;
			    $.extend(obj,{name:"fieldname"+n});

                if( selected >= 0 )
                {
					n =  (selected)*1+1;
                    items.splice( n, 0, obj );
					fieldsIndex[obj.name] = n;
					for(var i = n, h = items.length; i<h; i++) fieldsIndex[items[i].name] = i;

					if( id != 'fPageBreak' )
					{
						if( typeof items[ selected ][ 'addItem' ] != 'undefined' )
						{
							obj.name[ 'parent' ] = items[ selected ][ 'name' ];
							items[ selected ][ 'addItem' ]( obj.name );
						}
						else
						{
							// get the parent
							if( items[ selected ][ 'parent' ] !== '' )
							{
								items[ fieldsIndex[ items[ selected ][ 'parent' ] ] ][ 'addItem' ]( obj.name, items[ selected ][ 'name' ]);
							}

							selected++;
						}
					}
					else
					{
						selected++;
					}
                }
                else
                {
                    selected = items.length;
                    items[selected] = obj;
                }
				$.fbuilder.reloadItems();
				return obj;
		    },
		    saveData:function(f)
			{
				try{
					var itemsStringified   = $.stringifyXX( items ),
						theFormStringified = $.stringifyXX( theForm ),
						errorTxt = 'The entered data includes invalid characters. Please, if you are copying and pasting from another platform, be sure the data not include invalid characters.',
						str;

					if( typeof global_varible_save_data != 'undefined' )
					{
						// If the global_varible_save_data exists clear the form-builder-error-messages
						$( '.form-builder-error-messages' ).html( '' );
					}
					else
					{
						setTimeout(function(){ global_varible_save_data = true; }, 1000);
					}

					try{
						if( JSON.parse( itemsStringified ) != null && JSON.parse( theFormStringified ) != null )
						{
							str = "["+ itemsStringified +",["+ theFormStringified +"]]";
							$( "#"+f ).val( str );
						}
						else
						{
							$.fbuilder[ 'showErrorMssg' ]( errorTxt );
						}
					}
					catch( err )
					{
						$.fbuilder[ 'showErrorMssg' ]( errorTxt );
					}
				}catch( err ){}
		    },
		    loadData:function(form_structure, available_templates)
			{
				var structure,
					templates = null,
					fBuild = this;

				try{
					structure =  JSON.parse( $("#"+form_structure).val() );
				}
				catch(err)
				{
					structure = [];
					if(typeof console != 'undefined') console.log(err);
				}

			    try{
					 if( typeof available_templates != 'undefined' ) templates = JSON.parse( $("#"+available_templates).val() );
				}
				catch(err)
				{
					templates = null;
					if(typeof console != 'undefined') console.log(err);
				}

			    if ( structure )
				{
					$.fbuilder.defineGeneralEvents();
					if (structure.length==2)
					{
						items = [];
						for (var i=0;i<structure[0].length;i++)
						{
						   var obj = new $.fbuilder.controls[structure[0][i].ftype]();
						   obj = $.extend( true, {}, obj, structure[0][i] );
						   obj.fBuild = fBuild;
						   items[items.length] = obj;
						}
						theForm = new fform();
						theForm = $.extend(theForm,structure[1][0]);
						$.fbuilder.reloadItems();
					}
				}

				if( templates )
				{
					$.fbuilder.showSettings.formTemplateDic = templates;
				}
		    },
		    removeItem: $.fbuilder[ 'removeItem' ],
		    editItem:   $.fbuilder[ 'editItem' ]
	    }

	    this.fBuild = ffunct;
	    return this;
	};

    $.fbuilder[ 'showSettings' ] = {
		sizeList:new Array({id:"small",name:"Small"},{id:"medium",name:"Medium"},{id:"large",name:"Large"}),
		layoutList:new Array({id:"one_column",name:"One Column"},{id:"two_column",name:"Two Column"},{id:"three_column",name:"Three Column"},{id:"side_by_side",name:"Side by Side"}),
		formlayoutList:new Array({id:"top_aligned",name:"Top Aligned"},{id:"left_aligned",name:"Left Aligned"},{id:"right_aligned",name:"Right Aligned"}),
		formTemplateDic: {}, // Form Template dictionary
        showFieldType: function( v )
        {
            return '<label><b>Field Type: '+$.fbuilder[ 'getNameByIdFromType' ]( v )+'</b></label>';
        },
		showTitle: function(v, l)
		{
			l = l || 'default';
			return '<label>Field Label</label><textarea class="large" name="sTitle" id="sTitle">'+cff_esc_attr(v)+'</textarea>'+
					'<div><label>Label Placement</label>'+
					'<div class="cff-radio-group-ctrl">'+
					'<label><input type="radio" name="sFieldLayout" value="default" '+(l == 'default' ? 'checked' : '')+'><span>Default</span></label>'+
					'<label><input type="radio" name="sFieldLayout" value="top_aligned" '+(l == 'top_aligned' ? 'checked' : '')+'><span>Top</span></label>'+
					'<label><input type="radio" name="sFieldLayout" value="left_aligned" '+(l == 'left_aligned' ? 'checked' : '')+'><span>Left</span></label>'+
					'<label><input type="radio" name="sFieldLayout" value="right_aligned" '+(l == 'right_aligned' ? 'checked' : '')+'><span>Right</span></label></div></div>';
		},
		showShortLabel: function( v )
		{
			return '<div><label>Short label (optional) [<a class="helpfbuilder" text="The short label is used at title for the column when exporting the form data to CSV files.\n\nIf the short label is empty then, the field label will be used for the CSV file.">help?</a>] :</label><input type="text" class="large" name="sShortlabel" id="sShortlabel" value="'+cff_esc_attr(v)+'" /></div>';
		},
		showName: function( v )
		{
			return '<div><label>Field name, tag for the message:</label><input type="text" readonly="readonly" class="large" name="sNametag" id="sNametag" value="&lt;%'+cff_esc_attr(v)+'%&gt;" />'+
				   '<input style="display:none" readonly="readonly" class="large" name="sName" id="sName" value="'+cff_esc_attr(v)+'" /></div>';
		},
		showPredefined: function(v,c)
		{
			return '<div><label>Predefined Value</label><textarea class="large" name="sPredefined" id="sPredefined">'+cff_esc_attr(v)+'</textarea><br /><i>It is possible to use another field in the form as predefined value. Ex: fieldname1</i><label><input type="checkbox" name="sPredefinedClick" id="sPredefinedClick" '+((c)?"checked":"")+' value="1" > Use predefined value as placeholder.</label></div>';
		},
		showEqualTo: function(v,name)
		{
			return '<div><label>Equal to [<a class="helpfbuilder" text="Use this field to create password confirmation field or email confirmation fields.\n\nSpecify this setting ONLY into the confirmation field, not in the original field.">help?</a>]</label><select class="equalTo" name="sEqualTo" id="sEqualTo" dvalue="'+cff_esc_attr(v)+'" dname="'+cff_esc_attr(name)+'"></select></div>';
		},
		showAutocomplete: function(v)
		{
            var options = '', values = ['off', 'on', 'name', 'honorific-prefix', 'given-name', 'additional-name', 'family-name', 'honorific-suffix', 'nickname', 'email', 'username', 'new-password', 'current-password', 'one-time-code', 'organization-title', 'organization', 'street-address', 'address-line1', 'address-line2', 'address-line3', 'address-level4', 'address-level3', 'address-level2', 'address-level1', 'country', 'country-name', 'postal-code', 'cc-name', 'cc-given-name', 'cc-additional-name', 'cc-family-name', 'cc-number', 'cc-exp', 'cc-exp-month', 'cc-exp-year', 'cc-csc', 'cc-type', 'transaction-currency', 'transaction-amount', 'language', 'bday', 'bday-day', 'bday-month', 'bday-year', 'sex', 'tel', 'tel-country-code', 'tel-national', 'tel-area-code', 'tel-local', 'tel-extension', 'impp', 'url', 'photo'];

            for(var i = 0, h = values.length; i<h; i++)
            {
                options += '<option value="'+cff_esc_attr(values[i])+'" '+(values[i] == v ? 'SELECTED' : '')+'>'+cff_esc_attr(values[i])+'</option>';
            }
			return '<div><label>Autocomplete</label>'+
            '<select class="large" name="sAutocomplete" id="sAutocomplete">'+options+'</select><br><i>The field attribute takes precedence over the form settings.</i></div>';
		},
		showRequired: function(v)
		{
			return '<label><input type="checkbox" name="sRequired" id="sRequired" '+((v)?"checked":"")+'>Required</label>';
		},
		showExclude: function(v)
		{
			return '<label><input type="checkbox" name="sExclude" id="sExclude" '+((v)?"checked":"")+'>Exclude from submission</label>';
		},
		showSelect2: function(v)
		{
			return '<label><input type="checkbox" name="sSelect2" id="sSelect2" '+((v)?"checked":"")+'>Apply Select2 library (Experimental)</label>';
		},
		showReadonly: function(v)
		{
			return '<label><input type="checkbox" name="sReadonly" id="sReadonly" '+((v)?"checked":"")+'>Read Only</label>';
		},
		showNumberpad: function(v)
		{
			return '<label><input type="checkbox" name="sNumberpad" id="sNumberpad" '+((v)?"checked":"")+'>Forcing numberpad on mobiles</label>';
		},
		showSize: function(v)
		{
			var str = '<div class="cff-radio-group-ctrl">';
			for (var i=0;i<this.sizeList.length;i++)
			{
				str += '<label><input type="radio" name="sSize" value="'+cff_esc_attr(this.sizeList[i].id)+'" '+( this.sizeList[i].id==v ? 'checked' : '')+'><span>'+cff_esc_attr(this.sizeList[i].name)+'</span></label>';
			}
			str += '</div>'
			return '<label>Field Size</label>'+str;
		},
		showLayout: function(v)
		{
			var str = "";
			for (var i=0;i<this.layoutList.length;i++)
			{
				str += '<option value="'+cff_esc_attr(this.layoutList[i].id)+'" '+((this.layoutList[i].id==v)?"selected":"")+'>'+cff_esc_attr(this.layoutList[i].name)+'</option>';
			}
			return '<label>Field Layout</label><select name="sLayout" id="sLayout">'+str+'</select>';
		},
		showUserhelp: function(v,a,c,i)
		{
			return '<hr>'+
			'<label>Instructions for User</label><textarea class="large" name="sUserhelp" id="sUserhelp">'+cff_esc_attr(v)+'</textarea><label class="column"><input type="checkbox" name="sUserhelpTooltip" id="sUserhelpTooltip" '+((c)?"checked":"")+' value="1" > Show as floating tooltip&nbsp;&nbsp;</label><label class="column"><input type="checkbox" name="sTooltipIcon" id="sTooltipIcon" '+((i)?"checked":"")+' value="1" > Display on icon</label><div class="clearer"></div>'+
			'<label>Audio Tutorial</label>'+
			'<div><input type="text" style="width:70%;" name="sAudioSrc" id="sAudioSrc" value="'+cff_esc_attr(a)+'"><input id="sSelectAudioBtn" type="button" value="Browse" style="width:28%;" class="button-secondary" /></div>'+
			'<hr>';
		},
		showCsslayout: function(v)
		{
			return '<div><label>Add Css Layout Keywords</label><input type="text" class="large" name="sCsslayout" id="sCsslayout" value="'+cff_esc_attr(v)+'" /></div>';
		}
	};

	$.fbuilder.controls[ 'ffields' ] = function(){};
	$.extend( $.fbuilder.controls[ 'ffields' ].prototype,
		{
			form_identifier:"",
			name:"",
			fieldlayout:"default",
			shortlabel:"",
			index:-1,
			ftype:"",
			userhelp:"",
			audiotutorial:"",
			userhelpTooltip:false,
			tooltipIcon:false,
			csslayout:"",
			controlLabel:function( l ){ return this.name + ' - ' + l; },
			init:function(){},
			editItemEvents:function( e )
			{
				if( typeof e != 'undefined' && typeof e.length != 'undefined' )
				{
					for( var i = 0, h = e.length; i<h; i++ )
					{
						/**
						* s -> selector
						* e -> event name
						* l -> element
						* f -> function to apply the value
                        * x -> escape
						*/
						$(e[i].s).on(e[i].e, {obj:this, i:e[i]}, function(e){
							var v = $(this).val();
							if(typeof e.data.i['f'] != 'undefined') v = e.data.i.f($(this));
							e.data.obj[e.data.i.l] = ('x' in e.data.i && e.data.i.x) ? cff_esc_attr(v) : v;
							$.fbuilder.reloadItems( {'field': e.data.obj} );
						});
					}
				}

				$("#sTitle").on("keyup", {obj: this}, function(e)
					{
						var str = $(this).val();
						e.data.obj.title = str.replace(/\n/g,"<br />");
						$.fbuilder.reloadItems( {'field': e.data.obj} );
					});

				$("[name='sFieldLayout']").on("click change", {obj: this}, function(e)
					{
						e.data.obj.fieldlayout = $("[name='sFieldLayout']:checked").val();
						$.fbuilder.reloadItems( {'field': e.data.obj} );
					});

				$("[name='sSize']").on("click change", {obj: this}, function(e)
					{
						e.data.obj.size = $("[name='sSize']:checked").val();
						$.fbuilder.reloadItems( {'field': e.data.obj} );
					});

				$("#sShortlabel").on("keyup", {obj: this}, function(e)
					{
						e.data.obj.shortlabel = $(this).val();
						$.fbuilder.reloadItems( {'field': e.data.obj} );
					});

				$("#sReadonly").on("click", {obj: this}, function(e)
					{
						e.data.obj.readonly = $(this).is(':checked');
						$.fbuilder.reloadItems( {'field': e.data.obj} );
					});

				$("#sNumberpad").on("click", {obj: this}, function(e)
					{
						e.data.obj.numberpad = $(this).is(':checked');
						$.fbuilder.reloadItems( {'field': e.data.obj} );
					});

				$("#sAutocomplete").on("change", {obj: this}, function(e)
					{
						e.data.obj.autocomplete = $(this).val();
						$.fbuilder.reloadItems( {'field': e.data.obj} );
					});

				$("#sPredefined").on("keyup", {obj: this}, function(e)
					{
						e.data.obj.predefined = $(this).val();
						$.fbuilder.reloadItems( {'field': e.data.obj} );
					});

				$("#sPredefinedClick").on("click", {obj: this}, function(e)
					{
						e.data.obj.predefinedClick = $(this).is(':checked');
						$.fbuilder.reloadItems( {'field': e.data.obj} );
					});

				$("#sRequired").on("click", {obj: this}, function(e)
					{
						e.data.obj.required = $(this).is(':checked');
						$.fbuilder.reloadItems( {'field': e.data.obj} );
					});

				$("#sExclude").on("click", {obj: this}, function(e)
					{
						e.data.obj.exclude = $(this).is(':checked');
						$.fbuilder.reloadItems( {'field': e.data.obj} );
					});

				$("#sUserhelp").on("keyup", {obj: this}, function(e)
					{
						e.data.obj.userhelp = $(this).val();
						$.fbuilder.reloadItems( {'field': e.data.obj} );
					});

				$("#sUserhelpTooltip").on("click", {obj: this}, function(e)
					{
						e.data.obj.userhelpTooltip = $(this).is(':checked');
						$.fbuilder.reloadItems( {'field': e.data.obj} );
					});

				$("#sTooltipIcon").on("click", {obj: this}, function(e)
					{
						e.data.obj.tooltipIcon = $(this).is(':checked');
						$.fbuilder.reloadItems( {'field': e.data.obj} );
					});

				$("#sAudioSrc").on("keyup change", {obj: this}, function(e)
					{
						e.data.obj.audiotutorial = $(this).val();
						$.fbuilder.reloadItems( {'field': e.data.obj} );
					});

				$("#sSelectAudioBtn").on("click", {obj: this}, function(e)
					{
						var media = wp.media({
									title: 'Select Source',
									button: {
										text: 'Select Source'
									},
									multiple: false
							}).on('select',
								function() {
									var regExp = new RegExp( 'audio', 'i'),
										attachment = media.state().get('selection').first().toJSON();
									if( !regExp.test( attachment.mime ) )
									{
										alert( 'Invalid mime type' );
										return;
									}
									$( '#sAudioSrc' ).val( attachment.url ).trigger('change');
								}
							).open();
						return false;
					});

				$("#sCsslayout").on("keyup", {obj: this}, function(e)
					{
						e.data.obj.csslayout = $(this).val().replace(/\,/g, ' ').replace(/\s+/g, ' ');
						$.fbuilder.reloadItems( {'field': e.data.obj} );
					});

                $(".helpfbuilder").off('click');
				$(".helpfbuilder").on('click', function()
					{
						alert($(this).attr("text"));
					});
				$("#sDeveloperNotes").on("keyup", {obj: this}, function(e)
					{
						e.data.obj._developerNotes = $(this).val();
						$.fbuilder.reloadItems( {'field': e.data.obj} );
					});
			},

			showSpecialData:function()
			{
				if(typeof this.showSpecialDataInstance != 'undefined')
				{
					return this.showSpecialDataInstance();
				}
				else
				{
					return "";
				}
			},

			showEqualTo:function()
			{
				if(typeof this.equalTo != 'undefined')
				{
					return $.fbuilder.showSettings.showEqualTo(this.equalTo,this.name);
				}
				else
				{
					return "";
				}
			},

			showPredefined:function()
			{
				if(typeof this.predefined != 'undefined')
				{
					return $.fbuilder.showSettings.showPredefined(this.predefined,this.predefinedClick);
				}
				else
				{
					return "";
				}
			},
			/** Modified for showing required and readonly attributes **/
			showRequired:function()
			{
				var result = '';
                if(typeof this.autocomplete != 'undefined') result += $.fbuilder.showSettings.showAutocomplete(this.autocomplete);
				if(typeof this.required != 'undefined') result += $.fbuilder.showSettings.showRequired(this.required);
				if(typeof this.exclude != 'undefined')  result += $.fbuilder.showSettings.showExclude(this.exclude);
				if(typeof this.select2 != 'undefined')  result += $.fbuilder.showSettings.showSelect2(this.select2);
				if(typeof this.readonly != 'undefined') result += $.fbuilder.showSettings.showReadonly(this.readonly);
				if(typeof this.numberpad != 'undefined') result += $.fbuilder.showSettings.showNumberpad(this.numberpad);
				return result;
			},

			showSize:function()
			{
				if(typeof this.size != 'undefined')
				{
					return $.fbuilder.showSettings.showSize(this.size);
				}
				else
				{
					return "";
				}
			},

			showLayout:function()
			{
				if(typeof this.layout != 'undefined')
				{
					return $.fbuilder.showSettings.showLayout(this.layout);
				}
				else
				{
					return "";
				}
			},

			showRange:function()
			{
				if(typeof this.min != 'undefined')
				{
					return this.showRangeIntance();
				}
				else
				{
					return "";
				}
			},

			showFormat:function()
			{
				if(typeof this.dformat != 'undefined')
				{
					try
					{
						return this.showFormatIntance();
					} catch(e) {return "";}
				}
				else
				{
					return "";
				}
			},

			showChoice:function()
			{
				if(typeof this.choices != 'undefined')
				{
					return this.showChoiceIntance();
				}
				else
				{
					return "";
				}
			},

			showUserhelp:function()
			{
				return $.fbuilder.showSettings.showUserhelp(this.userhelp,this.audiotutorial,this.userhelpTooltip,this.tooltipIcon);
			},

			showCsslayout:function()
			{
				return $.fbuilder.showSettings.showCsslayout(this.csslayout);
			},

			showDeveloperNotes:function()
			{
				if(typeof this._developerNotes != 'undefined')
				{
					return '<hr><label>Developer Notes</label><textarea class="large" name="sDeveloperNotes" id="sDeveloperNotes">'+cff_esc_attr(this._developerNotes)+'</textarea><div class="clearer"><span class="uh">Developer notes. Only visible in the Form Builder</span></div><hr>';
				}
				else
				{
					return "";
				}
			},

			showAllSettings:function()
			{
				return this.showFieldType()+this.showTitle()+this.showShortLabel()+this.showName()+this.showSize()+this.showLayout()+this.showFormat()+this.showRange()+this.showRequired()+this.showSpecialData()+this.showEqualTo()+this.showPredefined()+this.showChoice()+this.showUserhelp()+this.showCsslayout();
			},

			showFieldType:function()
			{
				return $.fbuilder.showSettings.showFieldType(this.ftype);
			},

			showTitle:function()
			{
				return $.fbuilder.showSettings.showTitle(this.title, 'fieldlayout' in this ? this.fieldlayout : null );
			},

			showName:function()
			{
				return $.fbuilder.showSettings.showName(this.name)+this.showDeveloperNotes();
			},

			showShortLabel:function()
			{
				return $.fbuilder.showSettings.showShortLabel(this.shortlabel);
			},

			display:function()
			{
				return 'Not available yet';
			},

			show:function()
			{
				return 'Not available yet';
			}
		}
	);

	$( '.cff-metabox .hndle' ).on( 'click', function(){
		var e = $( this ).closest('.cff-metabox');
		e.toggleClass( 'cff-metabox-opened cff-metabox-closed' );
		$.post(
			'admin.php?page=cp_calculated_fields_form',
			{
				'cff-metabox-id' : e.attr('id'),
				'cff-metabox-action' : e.hasClass( 'cff-metabox-opened' ) ? 'open' : 'close',
				'cff-metabox-nonce'  : cff_metabox_nonce || 0
			}
		);
	} );

	// Redirect to the admin list sections
	$(window).on('load', function(){
		if ( /cp_calculated_fields_form_sub_addons/i.test(document.location.search) ) {
			$('#metabox_addons_area')[0].scrollIntoView();
		} else if ( /cp_calculated_fields_form_sub_troubleshoots_settings/i.test(document.location.search) ) {
			$('#metabox_troubleshoot_area')[0].scrollIntoView();
		} else if ( /cp_calculated_fields_form_sub_import_export/i.test(document.location.search) ) {
			$('#metabox_import_export_area')[0].scrollIntoView();
		} else if ( /cp_calculated_fields_form_sub_new/i.test(document.location.search) ) {
			cff_openLibraryDialog( true );
		}

		$( '#cff-ai-assistant-container' ).draggable({ handle: ".cff-ai-assistan-title" });
	});

	$(document).on('submit', '#cff-ai-assistant-register-form', function(evt){
		evt.preventDefault();
		evt.stopPropagation();

		let url  = $(evt.target).prop('action')+'/',
			data = $(evt.target).serialize(),
			openai_api_key = $('[name="cff-openai-api-key"]').val().replace(/^\s*/, '').replace(/\s*$/, '');

		if( '' == openai_api_key) {
			$('.cff-ai-assistant-register-error').show();
		} else {
			$('#cff-ai-assistant-container').append('<div class="cff-open-ai-loader"></div>');
			$('.cff-ai-assistant-register-error').hide();
			// Submit the value in background
			$.ajax({
				type: "POST",
				url: url,
				data: data,
				success: function(data) {
					if(data == 'ok'){
						$('#cff-ai-assistant-container .cff-open-ai-loader').remove();
						setTimeout( function(){
							$('.cff-ai-assistance-settings').removeClass('button-primary').addClass('button-secondary');
							$('#cff-ai-assistant-register-form').hide();
							$('#cff-ai-assistant-interaction-form').show('slow');
						}, 1000 );
					} else {
						alert( data );
					}
				}
			});
		}
		return false;
	});

	$(document).on('submit', '#cff-ai-assistant-interaction-form', function(evt){
		evt.preventDefault();
		evt.stopPropagation();

		let url  = $(evt.target).prop('action')+'/',
			data = $(evt.target).serialize(),
			openai_question = $('[name="cff-openai-question"]').val().replace(/^\s*/, '').replace(/\s*$/, '');

		if( '' == openai_question) {
			$('.cff-ai-assistant-question-error').show();
		} else {
			$('#cff-ai-assistant-container').append('<div class="cff-open-ai-loader"></div>');
			$('.cff-ai-assistant-question-error').hide();
			// Submit the value in background
			$.ajax({
				type: "POST",
				url: url,
				data: data,
				success: (function(question){
					return function(data) {
						let data_obj = JSON.parse(data),
							css_class = ({'mssg': 'cff-ai-assistance-mssg', 'error': 'cff-ai-assistance-error', 'warning': 'cff-ai-assistance-warning'})[data_obj['type']],
							econtainer = $('.cff-ai-assistant-answer-row'),
							equestion  = $('<div class="cff-ai-assistance-question-frame"></div>'),
							eanswer    = $('<div class="cff-ai-assistance-answer-frame ' + css_class + '"></div>');

						equestion.text( question );
						eanswer.html( data_obj['message'] );
						if( data_obj['type'] == 'mssg') {
							$('[name="cff-openai-question"]').val('');
						}
						econtainer.prepend(eanswer)
								  .prepend(equestion);
						$('#cff-ai-assistant-container .cff-open-ai-loader').remove();
						econtainer.animate({ scrollTop: 0 }, 'slow');
					}
				})(openai_question)
			});
		}
		return false;
	});

	$(document).on('click', '.cff-ai-assistance-settings', function(){
		let btn = $(this),
			register = $('#cff-ai-assistant-register-form'),
			interaction = $('#cff-ai-assistant-interaction-form');
		if(register.is(':visible')) {
			register.hide();
			interaction.show();
			btn.text(btn.attr('data-label-open'));
		} else {
			register.show();
			interaction.hide();
			btn.text(btn.attr('data-label-close'));
		}
	});

	$(document).on('keypress', '.cff_form_builder input[type="text"],.cff_form_builder input[type="number"]', function( evt ) {
		var keycode = (evt.keyCode ? evt.keyCode : evt.which);
		if( keycode == 13 ) {
			evt.preventDefault();
			evt.stopPropagation();
			return false;
		}
	});