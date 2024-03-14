'use strict';

function otw_shortcode_object(){
	
	this.shortcodes = {};
	
	this.labels = {};
	
	this.wp_url = '';
	
	this.dropdown_menu = false;
	
}
otw_shortcode_object.prototype.open_drowpdown_menu = function( append_to ){
	
	this.dropdown_menu = jQuery( '#otw_shortcode_dropdown_menu' );
	
	if( !this.dropdown_menu.size() ){
	
		var links = '<div id=\"otw_shortcode_dropdown_menu\">';
		
		links = links + '<ul>';
		
		for( var shortcode in this.shortcodes ){
			
			if( this.shortcodes[ shortcode ].enabled && !this.shortcodes[ shortcode ].parent ){
				
				if( this.shortcodes[ shortcode ].children ){
					
					links = links + '<li class="otw-shortcode-dropdown-item-parent" ><a class="otw-shortcode-dropdown-parent">' + this.shortcodes[ shortcode ].title + '</a>';
					
					links = links + '<ul class="otw-shortcode-dropdown-level1">';
					
					for( var cC = 0; cC < this.shortcodes[ shortcode ].children.length; cC++ ){
						
						var sub_shortcode = this.shortcodes[ shortcode ].children[ cC ]
						
						links = links + '<li><a class="otw-shortcode-dropdown-action-' + sub_shortcode + '">' + this.shortcodes[ sub_shortcode ].title + '</a></li>';
					}
					links = links + '</ul>';
					
					links = links + '</li>';
					
				}else{
					links = links + '<li><a class="otw-shortcode-dropdown-action-' + shortcode + '">' + this.shortcodes[ shortcode ].title + '</a></li>';
				}
			};
		};
		links = links + '<li class="otw-dropdown-line"><a class="otw-shortcode-dropdown-action-close">' + this.get_label( 'Close' ) + '</a></li>';
		
		links = links + '</ul>';
		
		links = links + '</div>';
		
		this.dropdown_menu = jQuery( links );
		
		this.init_dropdown_actions();
		
		this.dropdown_menu.appendTo( jQuery( 'body' ) );
	}
	else
	{
		this.dropdown_menu.hide();
	}
	var link = jQuery( append_to );
	
	var link_height = link.outerHeight();
	
	this.dropdown_menu.css("top", link.offset().top + link_height );
	
	var dropdown_right_postion = link.offset().left + this.dropdown_menu.width();
	
	if( ( dropdown_right_postion ) > jQuery(document).width() ){
		this.dropdown_menu.css("left", link.offset().left - this.dropdown_menu.width() + link.width() );
	}else{
		this.dropdown_menu.css("left", link.offset().left );
	};
	
	this.dropdown_menu.slideDown(100);
	this.dropdown_menu.show();
};

otw_shortcode_object.prototype.insert_code = function( shortcode_object ){
	
};

otw_shortcode_object.prototype.load_shortcode_editor_dialog = function( otw_shortcode_name ){

	var oSelf = this;
	
	jQuery.get( oSelf.wp_url + '.php?action=otw_shortcode_editor_dialog&shortcode=' + otw_shortcode_name,function(b){
								
		jQuery( "#otw-dialog").remove();
		var cont = jQuery( '<div id="otw-dialog">' + b + '</div>' );
		jQuery( "body").append( cont );
		jQuery( "#otw-dialog").hide();
		tb_position = function(){
			var isIE6 = typeof document.body.style.maxHeight === "undefined";
			var b=jQuery(window).height();
			jQuery("#TB_window").css({marginLeft: '-' + parseInt((TB_WIDTH / 2),10) + 'px', width: TB_WIDTH + 'px'});
			if ( ! isIE6 ) { // take away IE6
				jQuery("#TB_window").css({marginTop: '-' + parseInt((TB_HEIGHT / 2),10) + 'px'});
			}
			jQuery( '#TB_ajaxContent' ).css( 'width', '950px' );
			jQuery( '#TB_ajaxContent' ).css( 'padding', '0' );
			otw_setup_html_areas();
		}
		if( typeof( window.otw_tb_remove ) == 'undefined' ){
			window.otw_tb_remove = window.tb_remove;
			function tb_remove(){
				
				otw_close_html_areas();
				window.otw_tb_remove();
			}
		}
		
		jQuery( 'body' ).on( 'thickbox:removed', function(){
			otw_close_html_areas();
		} );
		
		var f=jQuery(window).width();
		b=jQuery(window).height();
		f=1000<f?1000:f;
		f-=80;
		b=760<b?760:b;
		b-=110; 
		otw_form_init_fields();
		otw_shortcode_editor = new otw_shortcode_editor_object( otw_shortcode_name );
		otw_shortcode_editor.preview = oSelf.shortcodes[ otw_shortcode_name ].object.preview;
		otw_shortcode_editor.wp_url = oSelf.wp_url;
		
		otw_shortcode_editor.init_fields();
		
		otw_shortcode_editor.shortcode_created = function( shortcode_object ){
			oSelf.insert_code( shortcode_object );
		}
		tb_show( oSelf.get_label( 'Insert' ) + ' OTW ' + oSelf.shortcodes[ otw_shortcode_name ].title, "#TB_inline?width="+f+"&height="+b+"&inlineId=otw-dialog" );
		
	} );
};

otw_shortcode_object.prototype.init_dropdown_actions = function(){
	
	var oSelf = this;
	
	jQuery( 'body' ).on( 'click',  function( event ){
	
		var close_it = true;
		
		if( jQuery( event.target ).parents( '.mce-btn' ).attr( 'data-otwkey' ) == otw_shortcode_component.tinymce_button_key ){
			close_it = false
		};
		if( jQuery( event.target ).parents( '.mce_' + otw_shortcode_component.tinymce_button_key ).size() ){
			close_it = false;
		};
		if( jQuery( event.target ).hasClass( 'otw-column-control-add' ) ){
			close_it = false;
		};
		if( jQuery( event.target ).parents( '#otw_shortcode_dropdown_menu' ).size() ){
			close_it = false;
		};
		if( close_it && oSelf.dropdown_menu.css( 'display' ) == 'block' ){
			oSelf.dropdown_menu.hide();
		};
	} );
	
	this.dropdown_menu.find( 'a' ).on( 'click',  function(){
		
		var class_name = jQuery( this ).attr( 'class' );
		
		if( class_name ){
			
			var matches = false;
			if( matches = jQuery( this ).attr( 'class' ).match( /^otw\-shortcode\-dropdown\-action\-([a-zA-Z0-9_\-]+)$/ ) ){
				
				switch( matches[1] ){
					
					case 'close':
							oSelf.dropdown_menu.hide();
						break;
					default:
							oSelf.load_shortcode_editor_dialog( matches[1] );
							oSelf.dropdown_menu.hide();
						break;
				};
			};
		};
	} );
};

otw_shortcode_object.prototype.get_label = function( label ){

	if( this.labels[ label ] ){
		return this.labels[ label ];
	};
	
	return label;
};
function otw_shortcode_editor_object( type ){
	
	this.fields = {};
	
	this.shortcode_type = type;
	
	this.preview = 'iframe';
	
	this.code = '';
	
	this.wp_url = '';
	
	this.init_action_buttons();
};

function otw_close_html_areas(){

	var areas = jQuery( '.otw-html-area-holder' );
	
	for( var cA = 0; cA < areas.size(); cA++ ){
		
		var id_matches = false;
		
		if( id_matches = areas[cA].id.match( /^otw\-shortcode\-element\-(.*)\-holder$/ ) ){
			
			var editor_node = jQuery( '#otw-shortcode-element-' + id_matches[1] + '_tmce-form-control' )
			
			editor_node.hide();
		}
	}
}
function otw_setup_html_areas(){

	var areas = jQuery( '.otw-html-area-holder' );
	
	if( jQuery( '#TB_window' ).size() ){
		
		for( var cA = 0; cA < areas.size(); cA++ ){
			
			var id_matches = false;
			
			if( id_matches = areas[cA].id.match( /^otw\-shortcode\-element\-(.*)\-holder$/ ) ){
				
				var editor_node = jQuery( '#otw-shortcode-element-' + id_matches[1] + '_tmce-form-control' )
				
				var ivalue = jQuery( '#otw-shortcode-element-' + id_matches[1] ).val();
				
				jQuery( '#TB_ajaxContent' ).css( 'overflow', 'visible' );
				editor_node.css( 'position', 'fixed' );
				editor_node.css( 'display', 'block' );
				editor_node.css( 'top', ( jQuery( '#TB_window' ).position().top - ( TB_HEIGHT / 2  ) ) + jQuery( areas[cA] ).position().top + 120 + 'px' );
				
				editor_node.css( 'left', ( jQuery( '#TB_window' ).offset().left + 40 )  + 'px');
				editor_node.css( 'z-index', '4000000' );
				
				if( typeof tinymce != "undefined" ) {
					
					var editor = tinymce.get( 'otw-shortcode-element-' + id_matches[1] + '_tmce' );
					
					if( editor && editor instanceof tinymce.Editor ) {
						
						var editor = tinymce.get( 'otw-shortcode-element-' + id_matches[1] + '_tmce' );
						editor.setContent( ivalue );
						
						if( jQuery( '#otw-shortcode-element-' + id_matches[1] ).attr( 'data-loaded' ) != 1 ){
							
							editor.onChange.add( function(){
								jQuery( '#otw-shortcode-element-' + id_matches[1] ).val( editor.getContent() );
								otw_shortcode_editor.live_preview();
							} );
						}
						editor.save( { no_events: true } );
						jQuery( '#otw-shortcode-element-' + id_matches[1] ).attr( 'data-loaded', 1 );
						
					}else{
						jQuery( '#otw-shortcode-element-' + id_matches[1]  + '_tmce' ).val( ivalue );
					}
					jQuery( '#otw-shortcode-element-' + id_matches[1]  + '_tmce' ).off( 'change' );
					jQuery( '#otw-shortcode-element-' + id_matches[1]  + '_tmce' ).on( 'change', function(){
					
						var editor = tinymce.get( 'otw-shortcode-element-' + id_matches[1] + '_tmce' );
						
						jQuery( '#otw-shortcode-element-' + id_matches[1] ).val( this.value );
						otw_shortcode_editor.live_preview();
					} );
					
					if( jQuery( '#otw-shortcode-element-' + id_matches[1] ).attr( 'data-loaded' ) != 1 ){
						
						jQuery( '#wp-otw-shortcode-element-' + id_matches[1]  + '_tmce-wrap' ).on( 'DOMSubtreeModified', function(){
						
							if( jQuery( '#otw-shortcode-element-' + id_matches[1] ).attr( 'data-loaded' ) != 1 ){
								var editor = tinymce.get( 'otw-shortcode-element-' + id_matches[1] + '_tmce' );
								
								if( editor && editor instanceof tinymce.Editor ) {
									editor.onChange.add( function(){
										jQuery( '#otw-shortcode-element-' + id_matches[1] ).val( editor.getContent() );
										otw_shortcode_editor.live_preview();
									} );
									jQuery( '#otw-shortcode-element-' + id_matches[1] ).attr( 'data-loaded', 1 );
								}
							}
						});
					}
				}
				
			}
		}
	}
}

otw_shortcode_editor_object.prototype.init_action_buttons = function(){
	
	var oSelf = this;
	
	jQuery( '#otw-shortcode-btn-cancel' ).on( 'click',  function(){
		otw_close_html_areas()
		tb_remove();
	});
	
	jQuery( '#otw-shortcode-btn-insert' ).on( 'click',  function(){
		
		oSelf.get_code();
	} );
	
	jQuery( '#otw-shortcode-btn-cancel-bottom' ).on( 'click',  function(){
		otw_close_html_areas()
		tb_remove();
	});
	
	jQuery( '#otw-shortcode-btn-insert-bottom' ).on( 'click',  function(){
		
		oSelf.get_code();
	} );
};

otw_shortcode_editor_object.prototype.live_reload = function(){
	
	var oSelf = this;
	
	var s_code = this.get_values();
	
	var matches = false;
	var post_id = 0;
	if( matches = location.href.match( /post\=([0-9]+)/ ) ){
		post_id = matches[1];
	}
	
	jQuery( '.otw-shortcode-editor-preview' ).html( 'Loading, please wait...' );
	
	var save_button_value = jQuery( '#TB_ajaxContent' ).find( '#otw-shortcode-btn-insert' ).val();
	
	jQuery.post( this.wp_url + '.php?action=otw_shortcode_live_reload&shortcode=' + this.shortcode_type , { 'shortcode': s_code, 'post': post_id }, function( response ){
		
		jQuery( '#TB_ajaxContent' ).html( response );
		
		otw_form_init_fields();
		
		oSelf.fields = {};
		
		oSelf.code = '';
		
		oSelf.init_fields();
		
		oSelf.init_action_buttons();
		
		jQuery( '#TB_ajaxContent' ).find( '#otw-shortcode-btn-insert' ).val( save_button_value );
		jQuery( '#TB_ajaxContent' ).find( '#otw-shortcode-btn-insert-bottom' ).val( save_button_value );
	});
};
otw_shortcode_editor_object.prototype.live_preview = function(){
	
	var oSelf = this;
	
	if( !jQuery( '.otw-shortcode-editor-preview' ).size() ){
		return ;
	};
	
	if( this.preview != 'iframe' ){
		var preview_html = '<div id="otw-shortcode-preview"></div>';
	}else{
		var preview_html = '<iframe width="100%" scrolling="no" id="otw-shortcode-preview"></iframe>';
	}
	
	jQuery( '.otw-shortcode-editor-preview' ).html( preview_html );
	
	var s_code = this.get_values();
	
	var matches = false;
	var post_id = 0;
	if( matches = location.href.match( /post\=([0-9]+)/ ) ){
		post_id = matches[1];
	}
	
	jQuery.post( this.wp_url + '.php?action=otw_shortcode_live_preview&shortcode=' + this.shortcode_type , { 'shortcode': s_code, 'post': post_id }, function( response ){
		
		if( oSelf.preview != 'iframe' ){
			
			jQuery( '#otw-shortcode-preview' ).height(jQuery('#TB_ajaxContent').height() - 150 );
			jQuery( '#otw-shortcode-editor-buttons' ).show();
			
			jQuery( '#otw-shortcode-preview' ).html( response );
			jQuery( '#otw-shortcode-preview' ).find('a,input').on( 'click',  function( event ){
				event.stopPropagation();
				return false;
			});
			jQuery( '#otw-shortcode-preview' ).css( 'overflow', 'hidden' );
			jQuery( '.otw-shortcode-editor-preview' ).fadeIn();
			
			jQuery( '#TB_ajaxContent' ).scroll( function(){
				jQuery( '#otw-shortcode-preview' ).parents( '.otw-shortcode-editor-preview-container' ).css( 'padding-top', this.scrollTop + 'px');
			});
			
			if( typeof( twttr ) == 'object' ){
				twttr.widgets.load();
			};
		}else{
			jQuery( '#otw-shortcode-preview' ).height(jQuery('#TB_ajaxContent').height() - 150 );
			jQuery( '#otw-shortcode-editor-buttons' ).show();
			jQuery( '#otw-shortcode-preview' ).contents().find('body').html( '' );
			jQuery( '#otw-shortcode-preview' ).contents().find('body').append(response);
			jQuery( '#otw-shortcode-preview' ).contents().find('body')[0].style.border=  'none';
			jQuery( '#otw-shortcode-preview' ).contents().find('body')[0].style.background =  'none';
			jQuery( '#otw-shortcode-preview' ).contents().find('body')[0].style.padding =  '0';
			jQuery( '#otw-shortcode-preview' ).contents().find('a,input').on( 'click',  function( event ){
				event.stopPropagation();
				return false;
			});
			jQuery( '.otw-shortcode-editor-preview' ).fadeIn();
			
			jQuery( '#TB_ajaxContent' ).scroll( function(){
				jQuery( '#otw-shortcode-preview' ).parents( '.otw-shortcode-editor-preview-container' ).css( 'padding-top', this.scrollTop + 'px');
			});
		}
	});
};

otw_shortcode_editor_object.prototype.shortcode_error = function( errors ){
	
	var error_html = '<div class=\"otw-shortcode-editor-error\" >';
	
	for( var cE = 0; cE < errors.length; cE++){
	
		error_html = error_html + '<p>' + errors[ cE ]  + '</p>';
	}
	
	error_html = error_html + '</div>';
	
	jQuery( '.otw-shortcode-editor-preview' ).html( error_html );
}

otw_shortcode_editor_object.prototype.get_values = function(){

	var v_code = {};
	v_code.shortcode_code = '';
	v_code.shortcode_type = this.shortcode_type;
	
	for( var field in otw_shortcode_editor.fields ){
	
		var matches = false;
		if( matches = field.match( /^otw\-shortcode\-element\-([A-Za-z0-9\_\-]+)$/ ) ){
			
			switch( otw_shortcode_editor.fields[ field ].element_type ){
				
				case 'checkbox':
						
						if( otw_shortcode_editor.fields[ field ].element[0].checked == true ){
							v_code[ matches[1] ] = otw_shortcode_editor.fields[ field ].current_value;
						}else{
							v_code[ matches[1] ] = '';
						}
					break;
				case 'text_area':
						
						if( ( otw_shortcode_editor.fields[ field ].element.attr( 'data-type' ) == 'tmce' ) && ( otw_shortcode_editor.fields[ field ].element.attr( 'data-loaded' ) == 1 ) ){
						
							if( tinyMCE.get( 'otw-shortcode-element-' + matches[1] + '_tmce' ) != null ){
								
								var textArea = jQuery( '#otw-shortcode-element-' + matches[1] + '_tmce' );
								
								if( ( textArea.length > 0 ) && textArea.is(':visible') ){
									v_code[ matches[1] ] = textArea.val();
								}else{
									v_code[ matches[1] ] = tinyMCE.get( 'otw-shortcode-element-' + matches[1] + '_tmce' ).getContent();
								}
								otw_shortcode_editor.fields[ field ].element.val( v_code[ matches[1] ] );
								
							}else if( jQuery( '#otw-shortcode-element-' + matches[1] + '_tmce' ).size() ){
								
								v_code[ matches[1] ] = jQuery( '#otw-shortcode-element-' + matches[1] + '_tmce' ).val();
								otw_shortcode_editor.fields[ field ].element.val( v_code[ matches[1] ] );
							}else{
								
								v_code[ matches[1] ] = otw_shortcode_editor.fields[ field ].current_value;
							}
							
						}else{
							v_code[ matches[1] ] = otw_shortcode_editor.fields[ field ].current_value;
						}
					break;
				default:
						v_code[ matches[1] ] = otw_shortcode_editor.fields[ field ].current_value;
					break;
			};
		}else if( field == 'otw_item_id' ){
			v_code.otw_item_id = otw_shortcode_editor.fields[ field ].current_value;
		}
	};
	
	return v_code;
};

otw_shortcode_editor_object.prototype.get_code = function(){
	
	var oSelf = this;
	
	this.code = this.get_values();
	
	//here make request to get the code validated
	
	if( !this.wp_url ){
		this.wp_url = 'admin-ajax';
	}
	
	jQuery.post( this.wp_url + '.php?action=otw_shortcode_get_code&shortcode=' + this.shortcode_type , this.code, function( response ){
		
		var response_code = jQuery.parseJSON( response );
		
		if( !response_code.has_error ){
			
			oSelf.code.shortcode_code = response_code.code;
			
			if( typeof( response_code.shortcode_attributes ) != 'undefined' ){
			
				for( var sA in response_code.shortcode_attributes ){
				
					oSelf.code[ sA ] = response_code.shortcode_attributes[ sA ];
				}
			}
			oSelf.shortcode_created( oSelf.code );
		}else{
			oSelf.shortcode_error( response_code.errors );
		};
	});
};

otw_shortcode_editor_object.prototype.init_fields = function(){
	
	var oSelf = this;
	
	//collect inputs
	jQuery( '.otw-shortcode-editor-fields' ).find( 'input[type=text]' ).each( function(){
	
		var element = jQuery( this );
		
		if( element.attr( 'id' ) ){
			oSelf.fields[ element.attr( 'id' ) ] = new otw_shortcode_editor_element( 'text_input', element );
		}
		element.on( 'change',  function(){
			oSelf.live_preview();
		});
	} );
	jQuery( '.otw-shortcode-editor-fields' ).find( 'input[type=hidden]' ).each( function(){
	
		var element = jQuery( this );
		
		if( element.attr( 'id' ) ){
			oSelf.fields[ element.attr( 'id' ) ] = new otw_shortcode_editor_element( 'hidden_input', element );
		}
	} );
	jQuery( '.otw-shortcode-editor-fields' ).find( 'input[type=checkbox]' ).each( function(){
	
		var element = jQuery( this );
		
		if( element.attr( 'id' ) ){
			oSelf.fields[ element.attr( 'id' ) ] = new otw_shortcode_editor_element( 'checkbox', element );
		}
		element.on( 'change',  function(){
			oSelf.live_preview();
		});
	} );
	jQuery( '.otw-shortcode-editor-fields' ).find( 'select' ).each( function(){
	
		var element = jQuery( this );
		
		if( element.attr( 'id' ) ){
			oSelf.fields[ element.attr( 'id' ) ] = new otw_shortcode_editor_element( 'select', element );
		}
		element.on( 'change',  function(){
			if( jQuery( this ).attr( 'data-reload' ) == '1' ){
				oSelf.live_reload();
			}else{
				oSelf.live_preview();
			}
		});
	} );
	jQuery( '.otw-shortcode-editor-fields' ).find( 'textarea' ).each( function(){
	
		var element = jQuery( this );
		
		if( element.attr( 'id' ) ){
			oSelf.fields[ element.attr( 'id' ) ] = new otw_shortcode_editor_element( 'text_area', element );
		}
		element.on( 'change',  function(){
			oSelf.live_preview();
		});
	} );
	
	this.live_preview();
};

function otw_shortcode_editor_element( element_type, element ){
	
	this.element_type = element_type;
	
	this.element = element;
	
	this.initial_value = this.element.val();
	
	this.current_value = this.initial_value;
	
	this.is_changed = false;
	
	var oSelf = this;
	
	this.element.on( 'change',  function(){
		
		oSelf.current_value = oSelf.element.val();
		
		if( oSelf.current_value != oSelf.initial_value ){
			oSelf.is_changed = true;
		}else{
			oSelf.is_changed = false;
		}
	} );
};

if( typeof( otw_shortcode_component ) == undefined )
{
	var otw_shortcode_component = null;
}
otw_shortcode_component = null;
if( typeof( otw_shortcode_editor ) == undefined )
{
	var otw_shortcode_editor = null;
}
otw_shortcode_editor = null;
