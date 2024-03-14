jQuery( document ).ready( function( $ ) {
	var customIconMedia, brandImageMedia;
	$( '#tabs' ).tabs( { active: $( '#ui_tabs_activate' ).val() } );
	$( "#tabs" ).on( "tabsactivate", function( event, ui ) {
		$( '#ui_tabs_activate' ).val( $( "#tabs" ).tabs( "option", "active" ) );
	});

	// Color pickers start
	$( '.sl-pl-text-color' ).wpColorPicker({change: function(event, ui){textColorChange( ui.color.toString() );}});

	$( '.sl-pl-bar-border-color' ).wpColorPicker({change: function(event, ui){barBorderColorChange( ui.color.toString() );}});

	$( '.sl-pl-counter-color' ).wpColorPicker({change: function(event, ui){counterColorChange( ui.color.toString() );}});

	// Initial elements
	updateStageOnload();

	// Icon tab elements event
	$( "[name='safelayout_preloader_options[icon]']" ).change( function() {
		iconChange();
	});

	$( '#custom_icon' ).change( function() {
		customIconChange();
	});

	// Select custom icon image from media library
	$( '#sl-pl-custom-media' ).click( function() {
		event.preventDefault();
		customImageSelect();
	});

	$( '#icon_size' ).change( function() {
		iconSizeChange( this.value );
	});

	$( "[name='safelayout_preloader_options[icon_gradient_value]']" ).change( function() {
		iconColorChange();
	});

	$( "[name='safelayout_preloader_options[icon_effect]']" ).change( function() {
		iconEffectChange();
	});

	// Background tab elements event
	$( "[name='safelayout_preloader_options[background_anim]']" ).change( function() {
		updatePreviewBackground();
	});

	$("#sl-pl-background-alpha").on("input change", function() {
		$("#background-alpha-output").html( this.value );
		backgroundAlphaChange();
	});

	$( "[name='safelayout_preloader_options[background_gradient_value]']" ).change( function() {
		backgroundColorChange();
	});

	$( ".sl-pl-background-div" ).hover(function() {
		$( this ).addClass( 'sl-pl-loaded' );},
		function() {$( this ).removeClass( 'sl-pl-loaded' );
	});

	// Text tab elements event
	$( "[name='safelayout_preloader_options[text]']" ).change( function() {
		textAnimChange();
	});

	$( '#text_anim' ).change( function() {
		textAnimChange();
	});

	$( '#text_size' ).change( function() {
		textSizeChange( '#text_size', 'font-size' );
	});

	$( '#text_margin_top' ).change( function() {
		textSizeChange( '#text_margin_top', 'margin-top' );
	});

	// Brand Image tab elements event
	$( "[name='safelayout_preloader_options[brand_url]']" ).change( function() {
		brandImageChange();
	});

	// Select brand image from media library
	$( '#sl-pl-brand-media' ).click( function() {
		event.preventDefault();
		brandImageSelect();
	});

	$( '#brand_anim' ).change( function() {
		brandImageChange();
	});

	$( '#brand_margin_top' ).change( function() {
		brandMarginChange( '#brand_margin_top', 'margin-top' );
	});

	$( '#brand_margin_bottom' ).change( function() {
		brandMarginChange( '#brand_margin_bottom', 'margin-bottom' );
	});

	// Display tab elements event
	$( "[name='safelayout_preloader_options[display_on]']" ).change( function() {
		AdvancedDisplayOnChange();
	});

	$( '#specific_IDs_select' ).change( function() {
		$( '#specific_IDs' ).val( $( '#specific_IDs_select' ).val().toString() );
	});

	$( '#specific_names_select' ).change( function() {
		$( '#specific_names' ).val( $( '#specific_names_select' ).val().toString() );
	});

	// special preloader tab elements event
	$( '#special_code_btn_copy' ).click( function() {
		event.preventDefault();
		$( '#special_code_txt' ).select();
		document.execCommand( 'copy' );
	});

	$( '#special_code_btn_gen' ).click( function() {
		event.preventDefault();
		$( '#special_code_txt' ).val( specialCodeGen() );
	});

	// progress bar tab elements event
	$( "[name='safelayout_preloader_options[bar_shape]']" ).change( function() {
		barShapeChange();
		barWidthChange();
		barPositionChange();
	});

	$( '#sl-pl-play-bar' ).click( function() {
		event.preventDefault();
		this.parentNode.style.borderRadius = '100%';
		$( '#sl-pl-play-bar' ).parent().animate( { borderRadius: '0%' }, {
			duration: 600,
			step: function(now, fx) {
				now = ( document.dir === 'rtl' ? -1 : 1 ) * now;
				$( '#sl-pl-preview-progress-view1' ).css( 'transform', 'translateX(' + ( -now ) + '%)' );
				$( '#sl-pl-preview-progress-view2' ).css( 'transform', 'translateX(' + now + '%)' );
			},
			complete: function() {
				setTimeout( function(){
					var temp = ( document.dir === 'rtl' ? -30 : 30 );
					$( '#sl-pl-preview-progress-view1' ).css( 'transform', 'translateX(' + ( -temp ) + '%)' );
					$( '#sl-pl-preview-progress-view2' ).css( 'transform', 'translateX(' + temp + '%)' );
				}, 500 );
			}
		});
	});

	$( "[name='safelayout_preloader_options[bar_light]']" ).change( function() {
		barLightChange();
	});

	$( "[name='safelayout_preloader_options[bar_position]']" ).change( function() {
		barPositionChange();
	});

	$( '#bar_width' ).change( function() {
		barWidthChange();
	});

	$( '#bar_width_unit' ).change( function() {
		barWidthChange();
	});

	$( '#bar_height' ).change( function() {
		barPropChange( '#bar_height', 'height' );
		barPositionChange();
	});

	$( '#bar_border_radius' ).change( function() {
		barPropChange( '#bar_border_radius', 'border-radius' );
	});

	$( '#bar_margin_top' ).change( function() {
		barPropChange( '#bar_margin_top', 'margin-top' );
	});

	$( '#bar_margin_bottom' ).change( function() {
		barPositionChange();
	});

	$( '#bar_margin_left' ).change( function() {
		barPropChange( '#bar_margin_left', 'margin-left' );
	});

	$( "[name='safelayout_preloader_options[bar_gradient_value]']" ).change( function() {
		barColorChange();
	});

	// Counter tab elements event
	$( "[name='safelayout_preloader_options[counter_text]']" ).change( function() {
		counterTextChange();
	});

	$( '#counter_margin_top' ).change( function() {
		counterPropChange( '#counter_margin_top', 'margin-top' );
	});

	$( '#counter_margin_bottom' ).change( function() {
		counterPropChange( '#counter_margin_bottom', 'margin-bottom' );
	});

	$( '#counter_margin_left' ).change( function() {
		counterPropChange( '#counter_margin_left', 'margin-left' );
	});

	$( '#counter_size' ).change( function() {
		counterPropChange( '#counter_size', 'font-size' );
	});

	// Initial elements
	function updateStageOnload() {
		iconChange();
		updatePreviewBackground();
		backgroundColorChange();
		backgroundAlphaChange();
		textAnimChange();
		brandImageChange();
		AdvancedDisplayOnChange();
		updateBar();
		updateCounter();
	}

	// Show selected icon in preview window.
	function iconChange() {
		var value = $( "[name='safelayout_preloader_options[icon]']:checked" ).val();
		if ( value === 'Custom' ) {
			$( '#custom_icon' ).prop( 'disabled', false ).next().removeClass( 'disabled' );
			customIconChange();
		} else {
			$( '#custom_icon' ).prop( 'disabled', true ).next().addClass( 'disabled' );
			var copy = $( '#sl-pl-' + value ).clone().removeClass( 'sl-pl-spin' ).addClass( 'sl-pl-spin-admin' );
			if ( copy.length ) {
				copy.html(copy.html().replace( /sl-pl-svg-effect/g, 'sl-pl-svg-effect-admin' ).replace( /sl-pl-svg-color/g, 'sl-pl-svg-color-admin' ) );
				copy.html(copy.html().replace( /sl-pl-tube-grad/g, 'sl-pl-gr00' ).replace( /sl-pl-mark/g, 'sl-pl-grad-container' ) );
				copy.html(copy.html().replace( /sl-pl-stream-grad01/g, 'sl-pl-gr00' ).replace( /sl-pl-stream-grad02/g, 'sl-pl-gr01' ) );
				copy.html(copy.html().replace( /sl-pl-t-s-symbol/g, 'sl-pl-t-s-symbol00' ) );
			}
			$( '#sl-pl-icon-box' ).empty().append( copy );
			$( '#sl-pl-icon-preview-title' ).text( ' ( ' + value + ' ) ' );
			iconSizeChange( $( '#icon_size' ).val() );
			iconColorChange();
			iconEffectChange();
		}
	}

	// Custom icon Change
	function customIconChange() {
		var url = $( "#custom_icon" ).val();
		var img = new Image();
		img.onload = function() {
			$( '#sl-pl-icon-box' ).empty().append( '<img class="sl-pl-svg-effect-admin sl-pl-custom" src="' + this.src + '" />' );
			$( '#sl-pl-icon-preview' ).css( {'width': Math.max( this.width + 60, 200 ) + 'px', 'height': Math.max( this.height + 60, 200 ) + 'px'} );
			$( '#custom_icon_width' ).val( this.width );
			$( '#custom_icon_height' ).val( this.height );
			iconEffectChange();
		};
		img.onerror = function(){
			$( '#sl-pl-icon-box' ).empty().append( '<div style="color:red">Image loading error!</div>' );
			$( '#custom_icon_width' ).val( 0 );
			$( '#custom_icon_height' ).val( 0 );
		};
		$( '#sl-pl-icon-preview-title' ).text( ' ( Custom ) ' );
		if ( url != '' ) {
			img.src = url;
		} else {
			$( '#sl-pl-icon-box' ).empty();
			$( '#custom_icon_width' ).val( 0 );
			$( '#custom_icon_height' ).val( 0 );
		}
	}

	// Select custom icon image from media library
	function customImageSelect() {
		if( customIconMedia ) {
			customIconMedia.open();
			return;
		}
		customIconMedia = wp.media({
			title: 'Choose Image',
			multiple: false,
			'library': {
				type: 'image'
			},
			button: {
				text: 'Select Image'
			}
		}).on('select', function() {
			var img = customIconMedia.state().get('selection').first().toJSON();

			$( '#custom_icon' ).val( img.url );
			$( '#custom_icon_alt' ).val( img.alt || img.title );
			customIconChange();
		});

		customIconMedia.open();
	}

	// Change icon size in preview window.
	function iconSizeChange( value ) {
		var icon = $( "[name='safelayout_preloader_options[icon]']:checked" ).val();
		if ( icon === 'Custom' ) {
			return;
		}
		var deflt = $( '#icon_size' ).attr( 'data-default-size' );
		if ( !$.isNumeric( value ) ) {
			value = deflt;
		}
		value = Number( value );
		var array = [ 'cycle', 'wheel', '3d-bar', 'blade-horizontal', 'blade-horizontal1', 'cube', 'cube1', 'leap', 'grid', '3d-square', 'flight', 'dive' ];
		if ( -1 != $.inArray( icon, array ) ){
			$( '.sl-pl-spin-admin' ).css( {'width': '50px', 'height': '50px'} );
			var scale = 'scale(' + ( value / deflt ) + ')';
			$( '.sl-pl-spin-admin' ).css( {'transform': scale, '-webkit-transform': scale} );
		} else {
			$( '.sl-pl-spin-admin' ).css( {'width': value + 'px', 'height': value + 'px'} );
		}
		$( '#sl-pl-icon-preview' ).css( {'width': Math.max( value + 60, 200 ) + 'px', 'height': Math.max( value + 60, 200 ) + 'px'} );
	}

	// Change icon color in preview window, based on its type.
	function iconColorChange() {
		var icon = $( "[name='safelayout_preloader_options[icon]']:checked" ).val();
		if ( icon === 'Custom' ) {
			return;
		}

		var value = $( "[name='safelayout_preloader_options[icon_gradient_value]']:checked" ).val();
		var color = colors[ value ];

		switch ( icon ) {
			case 'cycle':
			case 'bubble':
			case 'stream':
			case 'tube':
			case 'turn':
			case 'turn1':
			case 'wheel':
			case 'triple-spinner':
				if ( value > 6 ) {
					document.getElementById( 'sl-pl-grad-container' ).innerHTML = colors[ Number( value ) + 7 ];
					color = 'url(#sl-pl-converted-grad00)';
				}
				var array0 = [ 'tube', 'triple-spinner' ];
				$( '.sl-pl-svg-color-admin' ).css( ( -1 != $.inArray( icon, array0 ) ? 'fill' : 'stroke' ), color );
				break;
			default:
				$( '.sl-pl-spin-admin span' ).css( 'background', color );
				break;
		}
	}

	// Apply filter effect to icon.
	function iconEffectChange() {
		var icon = $( "[name='safelayout_preloader_options[icon]']:checked" ).val();
		var effect = $( "[name='safelayout_preloader_options[icon_effect]']:checked" ).val();
		var filter = 'none';
		if ( effect > 0 ) {
			filter = 'url(#sl-pl-svg-filter' + ( effect ) + ')';
		}
		if( icon == 'tube' && effect == 0 ){
			filter = 'url(#sl-pl-tube-filter)';
		}
 		var array0 = [ 'cycle', 'bubble', 'stream', 'tube', 'turn', 'turn1', 'wheel', 'triple-spinner', 'Custom' ];
		var array1 = [ 'crawl', 'gear', 'blade-horizontal', 'blade-horizontal1' ];
		if ( -1 != $.inArray( icon, array0 ) ) {
			$( '.sl-pl-svg-effect-admin' ).css( {'filter': filter, '-webkit-filter': filter} );
		} else if ( -1 != $.inArray( icon, array1 ) ) {
			$( '.sl-pl-spin-admin' ).css( {'filter': filter, '-webkit-filter': filter} );
		} else {
			$( '.sl-pl-spin-admin span' ).css( {'filter': filter, '-webkit-filter': filter} );
		}
	}

	// Change background color in background menu and icon preview window.
	function backgroundColorChange() {
		var value = $( "[name='safelayout_preloader_options[background_gradient_value]']:checked" ).val();
		var color = colors[ value ];

		$( '.sl-pl-icon-preview-background' ).css( 'background', color );
		backgroundPropChange( 'background', color );
	}

	// Change display of background in icon preview window.
	function updatePreviewBackground() {
		var value = $( "[name='safelayout_preloader_options[background_anim]']:checked" ).val();
		var cls = '.sl-pl-icon-preview-background';
		if( value == 'No' ){
			$( cls ).css( 'display', 'none' );
		}else{
			$( cls ).css( 'display', 'inline-block' );
		}
	}

	// Change background opacity.
	function backgroundAlphaChange() {
		var value = $("#sl-pl-background-alpha").val() / 100;
		$( '.sl-pl-icon-preview-background' ).css( 'opacity', value );
		backgroundPropChange( 'transition-property', 'none' );
		backgroundPropChange( 'opacity', value );
		setTimeout( function(){backgroundPropChange( 'transition-property', 'all, background' );}, 200 );
	}

	function backgroundPropChange( prop, value ) {
		$( '.sl-pl-bg-admin' ).css( prop, value );
		$( '.sl-pl-bg-admin-linear div' ).css( prop, value );
	}

	// Change text animation.
	function textAnimChange() {
		var text = $( "[name='safelayout_preloader_options[text]']" ).val().trim();
		var anim = $( "#text_anim" ).val();
		var html = '';
		if ( anim == 'No' ) {
			html = '<div class="sl-pl-text">' + text;
		} else {
			html = '<div class="sl-pl-text" id="sl-pl-' + anim + '">';
			html += wrapChars( text );
		}
		html += '</div>';

		$( '#sl-pl-text-box' ).empty().html( html );
		$( '#sl-pl-text-preview-title' ).text( ' ( ' + anim + ' ) ' );
		textColorChange();
		textSizeChange( '#text_size', 'font-size' );
		textSizeChange( '#text_margin_top', 'margin-top' );
	}

	// Change text size.
	function textSizeChange( id, prop ) {
		var value = $( id ).val();
		if ( !$.isNumeric( value ) ) {
			value = $( id ).attr( 'data-default-size' );
		}
		value = Number( value );
		textPropChange( prop, value );
	}

	// Change text color.
	function textColorChange( color ) {
		color = getElementColor( "[name='safelayout_preloader_options[text_color]']", color );
		textPropChange( 'color', color );
	}

	function textPropChange( prop, value ) {
		$( '.sl-pl-text' ).css( prop, value );
		$( '.sl-pl-text span' ).css( prop, value );
	}

	// Select brand image from media library
	function brandImageSelect() {
		if( brandImageMedia ) {
			brandImageMedia.open();
			return;
		}
		brandImageMedia = wp.media({
			title: 'Choose Image',
			multiple: false,
			'library': {
				type: 'image'
			},
			button: {
				text: 'Select Image'
			}
		}).on('select', function() {
			var img = brandImageMedia.state().get('selection').first().toJSON();

			$( '#brand_url' ).val( img.url );
			$( '#brand_url_alt' ).val( img.alt || img.title );
			brandImageChange();
		});

		brandImageMedia.open();
	}

	// Brand Image Change
	function brandImageChange() {
		var url = $( "[name='safelayout_preloader_options[brand_url]']" ).val();
		var anim = $( '#brand_anim' ).val();
		var img = new Image();
		img.onload = function() {
			var array = [ 'wrest-X', 'wrest-Y', 'swirl', 'sheet', 'roll', 'pipe', ];
			if ( ( this.width >= 550 || this.height >= 550 ) && -1 != $.inArray( anim, array ) ) {
				alert( $( '#sl-pl-brand-size-alert' ).html() );
				$( '#brand_anim' ).val( 'No' );
				anim = 'No';
				$( '#sl-pl-brand-preview-title' ).text( ' ( ' + anim + ' ) ' );
			}
			var elm = brandImageCodeGen( anim, this.src, this.width, this.height );
			$( '#sl-pl-brand-box' ).empty().append( elm );
			$( '#sl-pl-brand-preview' ).css( {'width': Math.max( this.width + 60, 200 ) + 'px', 'height': Math.max( this.height + 60, 200 ) + 'px'} );
			brandMarginChange( '#brand_margin_top', 'margin-top' );
			brandMarginChange( '#brand_margin_bottom', 'margin-bottom' );
			$( '#brand_width' ).val( this.width );
			$( '#brand_height' ).val( this.height );
		};
		img.onerror = function(){
			$( '#sl-pl-brand-box' ).empty().append( '<div style="color:red">Image loading error!</div>' );
			$( '#brand_width' ).val( 0 );
			$( '#brand_height' ).val( 0 );
		};
		$( '#sl-pl-brand-preview-title' ).text( ' ( ' + anim + ' ) ' );
		if ( url != '' ) {
			img.src = url;
		} else {
			$( '#sl-pl-brand-box' ).empty();
			$( '#brand_width' ).val( 0 );
			$( '#brand_height' ).val( 0 );
		}
	}

	// Generate brand image code
	function brandImageCodeGen( anim, src, width, height ) {
		var elm = '',
			prog0 = 'width:' + width + 'px;height:' + height + 'px;',
			prog1 = "url('" + src + "');";

		switch ( anim ) {
			case 'No':
				elm = '<div class="sl-pl-brand-container"><img class="sl-pl-brand" src="' + src + '" /></div>';
				break;
			case 'bounce':
			case 'yoyo':
			case 'swing':
			case 'rotate-2D':
			case 'rotate-3D-X':
			case 'rotate-3D-Y':
			case 'flash':
				elm = '<div class="sl-pl-brand-container"><img id="sl-pl-brand-' + anim + '" class="sl-pl-brand" src="' + src + '" /></div>';
				break;
			case 'wrest-X':
				elm = brandImageSplitCodeGen( 'sl-pl-brand-wrest-X', src, width, height, 0, 1, 4 );
				break;
			case 'wrest-Y':
				elm = brandImageSplitCodeGen( 'sl-pl-brand-wrest-Y', src, width, height, 1, 0, 4 );
				break;
			case 'roll':
			case 'pipe':
			case 'swirl':
			case 'sheet':
				elm = brandImageSplitCodeGen( 'sl-pl-brand-' + anim, src, width, height, 1, 0, -6 );
				break;
		}
		return elm;
	}

	// Generate brand image code
	function brandImageSplitCodeGen( cls, src, width, height, wKey, hKey, delay ) {
		var len = hKey ? width : height;
		var elm = '<div class="sl-pl-brand-container"><div class="sl-pl-brand" style="width:' + width + 'px;height:' + height + 'px">';
		for ( var i = 0; i < len -1 ; i++ ) {
			elm += '<div class="' + cls + '" style="position:absolute;background-image:' + "url('" + src + "');";
			elm += 'background-repeat:no-repeat;background-position:' + ( -i * hKey ) + 'px ' + ( -i * wKey ) + 'px;width:';
			elm += ( hKey ? 2 : width ) + 'px;height:' + ( wKey ? 2 : height ) + 'px;';
			elm += 'top:' + ( i * wKey ) + 'px;left:' + ( i * hKey ) + 'px;animation-delay:' + ( i * delay ) + 'ms;-webkit-animation-delay:' + ( i * delay ) + 'ms;"></div>';
		}
		return elm + '</div></div>';
	}

	// Change brand margin
	function brandMarginChange( id, prop ) {
		var value = $( id ).val();
		if ( !$.isNumeric( value ) ) {
			value = $( id ).attr( 'data-default-size' );
		}
		value = Number( value );
		$( '.sl-pl-brand-container' ).css( prop, value );
	}

	// Change display on
	function AdvancedDisplayOnChange() {
		var display = $( "[name='safelayout_preloader_options[display_on]']:checked" ).val();
		var id = false, name = false;
		if ( display == 'custom-id' ) {
			id = true;
		} else if ( display == 'custom-name' ) {
			name = true;
		}

		showHideElement( '#specific_IDs_select', id, 300 );
		showHideElement( '#specific_names_select', name, 300 );
	}

	// Show Hide Element
	function showHideElement( id, key , time ) {
		if ( key ) {
			$( id ).slideDown( time );
		} else {
			$( id ).slideUp( time );
		}
	}

	// special preloader Code Generator 
	function specialCodeGen() {
		var n1 = "[name='safelayout_preloader_options[";

		var code = '[safelayout_preloader close_button=' + $( '#close_button' ).val();
		code += ' minimum_time=' + $( '#minimum_time' ).val();
		code += ' maximum_time=' + $( '#maximum_time' ).val();
		code += ' background_anim="' + $( n1 + "background_anim]']:checked" ).val();
		code += '" background_gradient_value=' + $( n1 + "background_gradient_value]']:checked" ).val();
		code += ' background_alpha=' + $( n1 + "background_alpha]']" ).val();
		code += ' icon="' + $( n1 + "icon]']:checked" ).val();
		code += '" custom_icon="' + $( '#custom_icon' ).val().replace(/%20/g, ' ');
		code += '" custom_icon_alt="' + $( '#custom_icon_alt' ).val();
		code += '" custom_icon_width=' + $( '#custom_icon_width' ).val();
		code += ' custom_icon_height=' + $( '#custom_icon_height' ).val();
		code += ' icon_size=' + $( '#icon_size' ).val();
		code += ' icon_gradient_value=' + $( n1 + "icon_gradient_value]']:checked" ).val();
		code += ' icon_effect=' + $( n1 + "icon_effect]']:checked" ).val();
		code += ' text="' + $( n1 + "text]']" ).val();
		code += '" text_anim="' + $( '#text_anim' ).val();
		code += '" text_size=' + $( '#text_size' ).val();
		code += ' text_color="' + $( n1 + "text_color]']" ).val();
		code += '" text_margin_top=' + $( '#text_margin_top' ).val();
		code += ' brand_url="' + $( n1 + "brand_url]']" ).val().replace(/%20/g, ' ');
		code += '" brand_url_alt="' + $( '#brand_url_alt' ).val();
		code += '" brand_width=' + $( '#brand_width' ).val();
		code += ' brand_height=' + $( '#brand_height' ).val();
		code += ' brand_anim="' + $( '#brand_anim' ).val();
		code += '" brand_position="' + $( '#brand_position' ).val();
		code += '" brand_margin_top=' + $( '#brand_margin_top' ).val();
		code += ' brand_margin_bottom=' + $( '#brand_margin_bottom' ).val();
		code += ' bar_shape="' + $( n1 + "bar_shape]']:checked" ).val();
		code += '" bar_light="' + $( n1 + "bar_light]']:checked" ).val();
		code += '" bar_position="' + $( '#bar_position' ).val();
		code += '" bar_width=' + $( '#bar_width' ).val();
		code += ' bar_width_unit="' + $( '#bar_width_unit' ).val();
		code += '" bar_height=' + $( '#bar_height' ).val();
		code += ' bar_border_radius=' + $( '#bar_border_radius' ).val();
		code += ' bar_border_color="' + $( n1 + "bar_border_color]']" ).val();
		code += '" bar_margin_top=' + $( '#bar_margin_top' ).val();
		code += ' bar_margin_bottom=' + $( '#bar_margin_bottom' ).val();
		code += ' bar_margin_left=' + $( '#bar_margin_left' ).val();
		code += ' bar_gradient_value=' + $( n1 + "bar_gradient_value]']:checked" ).val();
		code += ' counter="' + $( n1 + "counter]']:checked" ).val();
		code += '" counter_text="' + $( n1 + "counter_text]']" ).val();
		code += '" counter_position="' + $( '#counter_position' ).val();
		code += '" counter_size=' + $( '#counter_size' ).val();
		code += ' counter_margin_top=' + $( '#counter_margin_top' ).val();
		code += ' counter_margin_bottom=' + $( '#counter_margin_bottom' ).val();
		code += ' counter_margin_left=' + $( '#counter_margin_left' ).val();
		code += ' counter_color="' + $( n1 + "counter_color]']" ).val();
		code += '" rand=' + Math.floor(Math.random() * 1000) + ']';
		code = code.replaceAll( 'undefined', '' );

		return code;
	}

	// Change bar shape
	function barShapeChange() {
		var value = $( "[name='safelayout_preloader_options[bar_shape]']:checked" ).val();
		if ( value === 'No' ) {
			$( '.sl-pl-preview-bar-container' ).css( 'visibility', 'Hidden' );
		} else {
			$( '.sl-pl-preview-bar-container' ).css( 'visibility', 'visible' );
			$( '.sl-pl-preview-bar-container' ).attr( 'id', 'sl-pl-' + value + '-container' );
			$( '.sl-pl-preview-bar' ).attr( 'id', 'sl-pl-' + value );
		}
		$( '#sl-pl-bar-preview-title' ).text( ' ( ' + value + ' ) ' );
	}

	// Change bar light
	function barLightChange() {
		var value = $( "[name='safelayout_preloader_options[bar_light]']:checked" ).val();
		if ( value === 'enable' ) {
			value = 'block';
		} else {
			value = 'none';
		}
		$( '#sl-pl-light-move-selector' ).css( 'display', value );
	}

	// Change bar position
	function barPositionChange() {
		var value = $( "[name='safelayout_preloader_options[bar_position]']:checked" ).val(),
			mBottom = Number( $( "[name='safelayout_preloader_options[bar_margin_bottom]']" ).val() ),
			height = $( '.sl-pl-preview-bar-container' ).outerHeight();
		if ( value === 'top' ) {
			value = ( - mBottom ) + 'px';
		} else if ( value === 'bottom' ) {
			height += mBottom;
			value = 'calc(100% - ' + height + 'px)';
		} else {
			height = height / 2 + mBottom;
			value = 'calc(50% - ' + height + 'px)';
		}
		$( '.sl-pl-preview-bar-container' ).css( 'top', value );
	}

	// Change bar width
	function barWidthChange() {
		var value = $( "[name='safelayout_preloader_options[bar_width]']" ).val(),
			unit = $( "[name='safelayout_preloader_options[bar_width_unit]']" ).val(),
			temp = ' - 0px)',
			id = $( '.sl-pl-preview-bar-container' ).attr( 'id' );
		if ( id && id.indexOf('border') != -1 ) {
			temp = ' - 6px)';
		}
		$( '.sl-pl-preview-bar-container' ).css( 'width', 'calc(' + value + unit + temp );
		$( '.sl-pl-preview-bar' ).css( 'width', '100%' );
	}

	// Change bar prop
	function barPropChange( pName1, pName2 ) {
		var value = $( pName1 ).val();
		$( '.sl-pl-preview-bar-container' ).css( pName2, value + 'px' );
	}

	// Change bar border color.
	function barBorderColorChange( color ) {
		color = getElementColor( "[name='safelayout_preloader_options[bar_border_color]']", color );
		$( '.sl-pl-preview-bar-container' ).css( 'border-color', color );
	}

	// Change bar color
	function barColorChange() {
		var value = $( "[name='safelayout_preloader_options[bar_gradient_value]']:checked" ).val();
		var color = colors[ value ];
		$( '.sl-pl-preview-bar-container .sl-pl-bar-bg' ).css( 'background', color );
		$( '.sl-pl-preview-bar' ).css( 'background', color );
	}

	// Update bar on load
	function updateBar() {
		barShapeChange();
		barLightChange();
		barWidthChange();
		barPropChange( '#bar_height', 'height' );
		barPropChange( '#bar_border_radius', 'border-radius' );
		barPropChange( '#bar_margin_top', 'margin-top' );
		barPropChange( '#bar_margin_bottom', 'margin-bottom' );
		barPropChange( '#bar_margin_left', 'margin-left' );
		barBorderColorChange();
		barColorChange();
		barPositionChange();
	}

	// Counter text change
	function counterTextChange() {
		var text = $( "[name='safelayout_preloader_options[counter_text]']" ).val().trim();
		$( '#sl-pl-counter' ).empty().html( '59<span>' + text + '</span>' );
	}

	// Change counter prop
	function counterPropChange( pName1, pName2 ) {
		var value = $( pName1 ).val();
		$( '#sl-pl-counter' ).css( pName2, value + 'px' );
	}

	// Change counter color.
	function counterColorChange( color ) {
		color = getElementColor( "[name='safelayout_preloader_options[counter_color]']", color );
		$( '#sl-pl-counter' ).css( 'color', color );
	}

	// Update counter on load
	function updateCounter() {
		counterTextChange();
		counterPropChange( '#counter_size', 'font-size' );
		counterPropChange( '#counter_margin_top', 'margin-top' );
		counterPropChange( '#counter_margin_bottom', 'margin-bottom' );
		counterPropChange( '#counter_margin_left', 'margin-left' );
		counterColorChange();
	}

	function wrapChars( text ) {
		var html = '', counter = 0;
		for ( var i = 0, len = text.length; i < len ; i++ ) {
			if ( text[i].trim() == '' ) {
				html += text[i];
			} else {
				html += '<span style="animation-delay:' + counter + 's;-webkit-animation-delay:' + counter + 's;">' + text[i] +'</span>';
				counter += 0.06;
			}
		}
		return html;
	}

	function getElementColor( name, color ) {
		if ( ! color ) {
			color = $( name ).val();
		}
		color = validColor( color ) ? color : $( name ).attr( 'data-default-color' );
		return color;
	}

	function validColor( color ) {
		var $div = $( '<div>' );
		$div.css( 'border', '1px solid ' + color );
		return ( $div.css( 'border-color' ) != '' && color.trim() != '' );
	}
});

var colors = [ '#fff', '#ffc0cb', '#ffff60', '#0f0', '#f00', '#4285f4', '#101010',
	'linear-gradient(90deg, #c5d06c, #c5d06c 50%, #d2dd72 53%, #d2dd72)',
	'linear-gradient(45deg, #ff0, #008000 50%, #ff0)',
	'linear-gradient(0deg, #ab82bc, #fdea72)',
	'linear-gradient(0deg, #800000, #f00)',
	'linear-gradient(90deg, #8abcfd, #67a5f5 44%, #5197ec 54%, #4087dc)',
	'linear-gradient(90deg, #ff8c59, #ffb37f 24%, #a3bf5f 49%, #7ca63a 75%, #527f32)',
	'linear-gradient(45deg, #000, #803100 49%, #800000 50%, #000)',
	'<linearGradient id="sl-pl-converted-grad00" x1="0" y1="0" x2="1" y2="0"><stop stop-color="#c5d06c" offset="0"/><stop stop-color="#c5d06c" offset="0.5"/><stop stop-color="#d2dd72" offset="0.53"/><stop stop-color="#d2dd72" offset="1"/></linearGradient>',
	'<linearGradient id="sl-pl-converted-grad00" x1="0" y1="0.7" x2="0.7" y2="0"><stop stop-color="#ff0" offset="0"/><stop stop-color="#008000" offset="0.5"/><stop stop-color="#ff0" offset="1"/></linearGradient>',
	'<linearGradient id="sl-pl-converted-grad00" x1="0" y1="1" x2="0" y2="0"><stop stop-color="#ab82bc" offset="0"/><stop stop-color="#fdea72" offset="1"/></linearGradient>',
	'<linearGradient id="sl-pl-converted-grad00" x1="0" y1="1" x2="0" y2="0"><stop stop-color="#800000" offset="0"/><stop stop-color="#f00" offset="1"/></linearGradient>',
	'<linearGradient id="sl-pl-converted-grad00" x1="0" y1="0" x2="1" y2="0"><stop stop-color="#8abcfd" offset="0"/><stop stop-color="#67a5f5" offset="0.44"/><stop stop-color="#5197ec" offset="0.54"/><stop stop-color="#4087dc" offset="1"/></linearGradient>',
	'<linearGradient id="sl-pl-converted-grad00" x1="0" y1="0" x2="1" y2="0"><stop stop-color="#ff8c59" offset="0"/><stop stop-color="#ffb37f" offset="0.24"/><stop stop-color="#a3bf5f" offset="0.49"/><stop stop-color="#7ca63a" offset="0.75"/><stop stop-color="#527f32" offset="1"/></linearGradient>',
	'<linearGradient id="sl-pl-converted-grad00" x1="0" y1="0.7" x2="0.7" y2="0"><stop stop-color="#000" offset="0"/><stop stop-color="#803100" offset="0.49"/><stop stop-color="#800000" offset="0.5"/><stop stop-color="#000" offset="1"/></linearGradient>' ];