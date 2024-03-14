(function( $ ) {
	'use strict';
	
	window.socialRocketAdmin.formIsDirty = false;
	window.socialRocketAdmin.formSubmitting = false;
	window.socialRocketAdmin.tweetStyleIsLoading = false;
	
	
	/*
	 * Hooks
	 */
	window.socialRocketAdmin.hooks = {
		'floating_mobile_scope':            '',
		'floating_mobile_settings':         '',
		'floating_update_preview':          [],
		'gutenberg_extra_image_controls':   [],
		'inline_mobile_scope':              '',
		'inline_mobile_settings':           '',
		'inline_update_preview':            []
	};
	
	
	/*
	 * Settings page: initial tasks
	 */
	if (
		$('body').hasClass('toplevel_page_social_rocket') ||
		$('body').hasClass('social-rocket_page_social_rocket_click_to_tweet') ||
		$('body').hasClass('social-rocket_page_social_rocket_floating_buttons') ||
		$('body').hasClass('social-rocket_page_social_rocket_inline_buttons') ||
		$('body').hasClass('social-rocket_page_social_rocket_license_keys') ||
		$('body').hasClass('social-rocket_page_social_rocket_settings')
	) {
	
		// tab switcher
		$(document).on('click', '.social-rocket-tab', function(e){
			e.preventDefault();
			
			var $divs = $('.social-rocket-settings-section');
			var $tabs = $('.social-rocket-tab');
			var currentTab = $(this).data('tab');
			
			$divs.hide();
			$('#social-rocket-settings-'+currentTab).show();
			location.hash = currentTab;
			$(window).scrollTop(0);
			
			$tabs.removeClass('nav-tab-active');
			$('.social-rocket-tab[data-tab="'+currentTab+'"]').addClass('nav-tab-active');
			
			layoutAdjustments();
		});
		
		// layout adjustments
		var $sidebar = $('#social-rocket-settings-content-sidebar');
		var sidebarOffset = $sidebar.offset();
		
		var layoutAdjustments = function(){
			
			var viewportWidth = $( window ).width();
			
			// header
			var $header = $('.social-rocket-settings-header');
			var headerOffset = $header.offset();
			
			// preview panes
			var $content = $('#social-rocket-settings');
			var width = $content.width();
			var $inlinePreview = $('#social_rocket_inline_preview');
			$inlinePreview.css({
				position: 'fixed',
				left: headerOffset.left - 22,
				bottom: 0,
				width: width + 44
			});
			
			var $floatingPreview = $('#social_rocket_floating_preview');
			$floatingPreview.css({
				position: 'fixed',
				left: headerOffset.left - 22,
				bottom: 0,
				width: width + 44
			});
			
		};
		
		// sticky sidebar (and jumpy save button thingy)
		var $sidebar = $('#social-rocket-settings-content-sidebar');
		var stickySidebar = function() {
			
			var scrollPosition = $(window).scrollTop();
			
			// var $saveButton = $('#social_rocket_big_save_button');
			// var saveButtonInHeader  = $saveButton.parent().hasClass('social-rocket-settings-header-right');
			// var saveButtonInSidebar = $saveButton.parent().attr('id') === 'social-rocket-settings-content-sidebar';
			
			// if (
				// ( $( window ).width() <= 1366 && saveButtonInSidebar ) ||
				// ( scrollPosition < 1 && saveButtonInSidebar )
			// ) {
				// var saveButtonDom = $saveButton.detach();
				// $('.social-rocket-settings-header-right').first().prepend( saveButtonDom );
			// }
			// if ( $( window ).width() > 1366 && scrollPosition > 1 && saveButtonInHeader ) {
				// var saveButtonDom = $saveButton.detach();
				// $('#social-rocket-settings-content-sidebar').prepend( saveButtonDom );
			// }
			
			var adminBarHeight = $('#wpadminbar').height();
			var contentHeight = $('#social-rocket-settings-content').height();
			var sidebarHeight = $sidebar.outerHeight();
			
			var minOffset = sidebarOffset.top - adminBarHeight;
			var topMargin = scrollPosition - minOffset;
			if ( scrollPosition + sidebarHeight >= contentHeight ) {
				topMargin = contentHeight - sidebarHeight - minOffset;
			}
			
			if ( $( window ).width() > 1366 ) {
				$sidebar.css('margin-top',( scrollPosition > minOffset ? topMargin : 0 )+'px');
			} else {
				$sidebar.css('margin-top','');
			}
		};
		
		// init
		(function(){
			
			layoutAdjustments();
			stickySidebar();
			
			// select tab
			var hash = location.hash.replace('#','');
			if ( hash > '' ) {
				$('.social-rocket-tab[data-tab="'+hash+'"]').click();
			}
			
		})();
		
		$(window).on('resize',layoutAdjustments);
		$(window).on('scroll',stickySidebar);
	
	}
	
	
	/*
	 * Settings page: collapsables
	 */
	$( document ).on( 'click', '.social-rocket-collapsable-toggle', function( e ) {
		e.preventDefault();
		
		var showOrHide = false;
		var target     = $( this ).data('sr-toggle');
		var $targets   = $( ( target.charAt( 0 ) === '.' || target.charAt( 0 ) === '#' ? '' : '#' ) + target );
		var $toggles   = $( 'a[data-sr-toggle="' + target + '"]' );
		
		$targets.each(function() {
			var $target = $( this );
			if ( $target.hasClass( 'social-rocket-collapsable-expanded' ) ) {
				$target.removeClass( 'social-rocket-collapsable-expanded' ).slideUp();
				showOrHide = 'hide';
			} else {
				$target.addClass( 'social-rocket-collapsable-expanded' ).slideDown();
				showOrHide = 'show';
			}
		});
		
		if ( showOrHide === 'show' ) {
			$toggles.each(function() {
				$( this ).children( 'i' ).removeClass( 'fa-plus-square' ).addClass( 'fa-minus-square' );
				$( this ).children( 'span' ).html( socialRocketAdmin.i18n.collapsable_hide );
			});
		} else if ( showOrHide === 'hide' ) {
			$toggles.each(function() {
				$( this ).children( 'i' ).removeClass( 'fa-minus-square' ).addClass( 'fa-plus-square' );
				$( this ).children( 'span' ).html( socialRocketAdmin.i18n.collapsable_show );
			});
		}
	});
	
	
	/*
	 * Settings page: color pickers
	 */
	$(document).ready(function() {
		$('.social-rocket-color-picker').wpColorPicker({
			change: function (event, ui) {
				setTimeout(function(){				   
					var element = event.target;
					$(element).trigger('colorPickerChange');
				},1);
			},
			clear: function (event) {
				var colorContainer = $(event.target).parents('.wp-picker-container');
				var colorPicker = colorContainer.find('.social-rocket-color-picker');
				var colorPlaceholder = colorPicker.attr('placeholder');
				colorContainer.find('button.wp-color-result').css('background-color',colorPlaceholder);
				colorPicker.wpColorPicker('close');
				setTimeout(function(){				   
					var element = $(event.target).parent().find('.wp-color-picker')[0];
					$(element).trigger('colorPickerChange');
				},1);
			}
		}).each(function(){
			
			var colorContainer = $(this).parents('.wp-picker-container');
			var colorButton = colorContainer.find('button.wp-color-result')
			var colorPlaceholder = $(this).attr('placeholder');
			var colorValue = $(this).val();
			
			if ( ! colorValue > '' ) {
				colorButton.css('background-color',colorPlaceholder);
			}
			
			colorContainer.find('.wp-picker-clear').val(socialRocketAdmin.i18n.colorpicker_reset);
			
		});
	});
	
	
	/*
	 * Settings page: sortables
	 */
	$(document).ready(function() {
		$('.social-rocket-settings-networks-sortable:not(.inactive)').sortable({
			handle: '.social-rocket-settings-networks-sort-handle',
			update: function( event, ui ) {
				var order = $(this).sortable( "toArray", { attribute: 'data-network' } );
				var id = $(this).parents('.social-rocket-settings-section').attr( 'id' );
				if ( id === 'social-rocket-settings-inline-buttons-desktop' ) {
					$('input[name="social_rocket_inline_networks_order"]').val(order).change();
				} else if ( id === 'social-rocket-settings-inline-buttons-mobile' ) {
					$('input[name="social_rocket_inline_mobile_networks_order"]').val(order).change();
				} else if ( id === 'social-rocket-settings-floating-buttons-desktop' ) {
					$('input[name="social_rocket_floating_networks_order"]').val(order).change();
				} else if ( id === 'social-rocket-settings-floating-buttons-mobile' ) {
					$('input[name="social_rocket_floating_mobile_networks_order"]').val(order).change();
				}
			}
		});
	});
	
	
	/*
	 * Settings page: thickbox popups
	 */
	$(document).on('click','.social-rocket-network-settings-close',function(){
		$('#TB_closeWindowButton').click();
	});
	
	$(document).on('click','.social-rocket-network-settings-reset',function(){
		if ( confirm( socialRocketAdmin.i18n.confirm ) ) {
			var $wrapper = $(this).closest('.social-rocket-network-settings-wrapper');
			$wrapper.find('.social-rocket-network-settings-colorpicker-toggle').click();
			$wrapper.find('input').each(function(){
				if ( $(this).attr('type') === 'checkbox' ) {
					$(this).prop( 'checked', false );
				} else if ( $(this).attr('type') === 'text' ) {
					$(this).val('');
				}
			});
			$wrapper.find('textarea').each(function(){
				$(this).val('');
			});
			$wrapper.find('.wp-picker-clear').click();
			$wrapper.find('.iris-picker').hide();
			$wrapper.find('.social-rocket-network-settings-colorpicker-row').hide();
			$wrapper.find('input').first().change(); // trigger an update
		}
	});
	
	$(document).on('click','.social-rocket-network-settings-colorpicker-toggle',function(){
		var $colorpickerToggleRow = $(this).parents('.social-rocket-network-settings-colorpicker-toggle-row');
		var $colorpickerRows = $colorpickerToggleRow.siblings('.social-rocket-network-settings-colorpicker-row');
		if ( $(this).prop( 'checked' ) ) {
			$colorpickerRows.show();
		} else {
			$colorpickerRows.hide();
		}
	});
	
	
	/*
	 * Settings page: tooltips
	 */
	$(document).on('click', '.social-rocket-tooltip-toggle', function(e){
		e.preventDefault();
		
		var $toggle = $(this);
		var $target = $(this).next('.social-rocket-tooltip');
		var position = $toggle.position();
		
		$('.social-rocket-tooltip').not($target).hide();
		
		if ( $target.hasClass('bottom') ) {
			$target.css({
				'left': ( position.left - $target.width() / 2 ) + 'px',
				'top': ( position.top + $toggle.height() + 9 ) + 'px'
			});
		} else {
			$target.css({
				'left': ( position.left + $toggle.width() + 9 ) + 'px',
				'top': ( position.top + $toggle.height() / 2 - 18 ) + 'px'
			});
		}
		
		$target.toggle();
		e.stopPropagation();
	});

	$(document).on('click', function (e) {
		$('.social-rocket-tooltip').hide();
	});

	$('.social-rocket-tooltip').click(function (e) {
		e.stopPropagation();
	});
	
	
	/*
	 * Settings page: network buttons
	 */
	$( '.social-rocket-settings-preview-wrapper .social-rocket-button, .social-rocket-settings-preview-wrapper .social-rocket-floating-button' ).on( 'click', function( e ) {
		
		if ( $( this ).hasClass( 'social-rocket-no-pop' ) ) {
			$( this ).find( 'a' ).removeAttr( 'target' );
			return;
		}
		
		e.preventDefault();
		
		var href = $( this ).find('a').attr( 'href' );
		var network = $(this).data('network');
		
		if ( network === 'pinterest' ) {
			var hasMedia = href.indexOf('&media=') > 0 ? true : false;
			if ( ! hasMedia ) {
				var el = document.createElement('script');
				el.setAttribute('type','text/javascript');
				el.setAttribute('charset','UTF-8');
				el.setAttribute('src','//assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);
				document.body.appendChild(el);
				return false;
			}
		}
		
		var height = 270;
		var width = 500;
		var top = window.screenY + (window.innerHeight - height) / 2;
		var left = window.screenX + (window.innerWidth - width) / 2;
		var windowFeatures = 'height=' + height + ',width=' + width + ',top=' + top + ',left=' + left;
		var instance = window.open( href, '_blank', windowFeatures );
		
		return false;
	});
	
	// "More" button
	$( document ).on( 'click', '.social-rocket-more-button', function( e ) {
		
		e.preventDefault();
			
		var $parent;
		var $toggle = $( this );
		var $target = $toggle.next('.social-rocket-more-buttons');
		
		var togglePosition = $toggle.position();
		
		var isFloating = $toggle.closest( '.social-rocket-floating-buttons' ).length ? true : false;
		var isInline   = $toggle.closest( '.social-rocket-buttons' ).length ? true : false;
		
		var left = 0;
		var top  = 0;
		
		if ( isFloating ) {
			$parent = $toggle.closest( '.social-rocket-floating-buttons' );
			$target.css({
				'width': ( $toggle.width() + 30 ) + 'px'
			});
		}
		
		if ( isInline ) {
			$parent = $toggle.closest( '.social-rocket-buttons' );
		}
		
		if ( $target.hasClass('bottom') ) {
			left = togglePosition.left - $target.width() / 2;
			top = togglePosition.top + $toggle.height() + 9;
		} else {
			left = togglePosition.left;
			top = togglePosition.top - $target.height() - 30;
		}
		
		if ( $parent.hasClass( 'social-rocket-position-left' ) ) {
			left = left + $toggle.width() + 5;
		} else if ( $parent.hasClass( 'social-rocket-position-right' ) ) {
			left = left - $toggle.width() - 35;
		} else if ( $parent.hasClass( 'social-rocket-position-top' ) ) {
			top = $toggle.height() + 5;
		}
		
		$target.css({
			'left': left + 'px',
			'top': top + 'px'
		});
		
		$target.toggle();
		e.stopPropagation();
	
	});

	$( document ).on( 'click', function (e) {
		$( '.social-rocket-more-buttons' ).hide();
	});
	
	$( '.social-rocket-more-buttons' ).click(function ( e ) {
		e.stopPropagation();
	});
	
	
	/*
	 * Settings page: networks selectors
	 */
	
	$(document).on('click', '.social-rocket-select-networks, .social-rocket-select-networks-apply', function(e){
		e.preventDefault();
		var $wrapper = $(this).parents('.social-rocket-settings-networks-selector-wrapper');
		var $outer = $wrapper.find('.social-rocket-settings-networks-selector-outer');
		var $emptyDiv = $wrapper.find('.social-rocket-settings-networks-empty');
		var $activeNetworks = $wrapper.find('.social-rocket-settings-networks tr.active');
		if ( $outer.hasClass('active') ) {
			$outer.slideUp().removeClass('active');
			if ( ! $activeNetworks.length ) {
				$emptyDiv.slideDown().addClass('active');
			}
		} else {
			$outer.slideDown().addClass('active');
			$emptyDiv.slideUp().removeClass('active');
		}
	});
	
	$(document).on('click', '.social-rocket-settings-networks-selector input[type="checkbox"]', function(e){
		var $section = $(this).parents('.social-rocket-settings-section');
		var $wrapper = $(this).parents('.social-rocket-settings-networks-selector-wrapper');
		var $networks = $wrapper.find('.social-rocket-settings-networks');
		var activeCount = 0;
		if ( $section.attr( 'id' ) === 'social-rocket-settings-inline-buttons-desktop' ) {
			var floatingOrInline = 'inline';
		} else if ( $section.attr( 'id' ) === 'social-rocket-settings-inline-buttons-mobile' ) {
			var floatingOrInline = 'inline_mobile';
		} else if ( $section.attr( 'id' ) === 'social-rocket-settings-floating-buttons-desktop' ) {
			var floatingOrInline = 'floating';
		} else if ( $section.attr( 'id' ) === 'social-rocket-settings-floating-buttons-mobile' ) {
			var floatingOrInline = 'floating_mobile';
		}
		$section.find('input[name="social_rocket_'+floatingOrInline+'_networks[]"]').each(function(){
			if ( $(this).prop( 'checked' ) ) {
				$networks.find('tr[data-network="'+$(this).val()+'"]').show().addClass('active');
				activeCount++;
			} else {
				$networks.find('tr[data-network="'+$(this).val()+'"]').hide().removeClass('active');
			}
		});
		if ( activeCount > 0 ) {
			$networks.slideDown();
		} else {	
			$networks.hide();
		}
		$( this ).trigger( 'sortupdate' );
	});
	
	$(document).on('click', '.social-rocket-settings-networks-remove', function(e){
		e.preventDefault();
		var $wrapper = $(this).parents('.social-rocket-settings-networks-selector-wrapper');
		var $outer = $wrapper.find('.social-rocket-settings-networks-selector-outer');
		var $emptyDiv = $wrapper.find('.social-rocket-settings-networks-empty');
		var $networks = $wrapper.find('.social-rocket-settings-networks');
		var network = $( this ).closest('tr').data('network');
		$( this ).closest('tr').hide().removeClass('active');
		$wrapper.find('input[data-network="' + network + '"]').prop( 'checked', false ).change();
		if ( $networks.find('tr.active').length == 0 ) {
			$networks.hide();
			if ( ! $outer.hasClass('active') ) {
				$emptyDiv.slideDown().addClass('active');
			}
		}
		$( this ).trigger( 'sortupdate' );
	});
	
	
	/*
	 * Settings page: inline buttons stuff
	 */
	$( document ).ready(function() {
	
		if ( ! $( 'body' ).hasClass( 'social-rocket_page_social_rocket_inline_buttons' ) ) {
			return; // nothing to do
		}
		
		// FOR DEBUGGING:
		// window.durationcount = 0;
		// window.durationtotal = 0;
		
		// function to update preview
		var updatePreview = function( e ) {
			
			if (
				typeof e !== "undefined" &&
				( e.type === 'keyup' || e.type === 'change' ) &&
				$.inArray( e.target.tagName, [ 'INPUT', 'SELECT', 'TEXTAREA' ] ) < 0
			) {
				// ignore keyup and change events not from our form fields
				return;
			}
			
			// FOR DEBUGGING:
			// var start = Date.now();
			// console.log( 'inline updatePreview() started at: ' + start );
			
			// setup vars
			var $previewDesktop  = $( '#social_rocket_inline_preview_desktop' );
			var $previewMobile   = $( '#social_rocket_inline_preview_mobile' );
			var $settingsDesktop = $( '#social-rocket-settings-inline-buttons-desktop' );
			var $settingsMobile  = $( socialRocketAdmin.hooks.inline_mobile_settings > '' ?
										socialRocketAdmin.hooks.inline_mobile_settings :
										'#social-rocket-settings-inline-buttons-mobile-default' );
			var mobileSetting    = $( 'input[name="social_rocket_inline_mobile_setting"]:checked' ).val();
			var scopes           = {
				desktop:				'social_rocket_inline',
				mobile:					( socialRocketAdmin.hooks.inline_mobile_scope > '' ?
											socialRocketAdmin.hooks.inline_mobile_scope :
											'social_rocket_inline_mobile' )
			};
			
			// start loop
			$.each( scopes, function( scope, scopePrefix ) {
				
				var $dest;
				var $source;
				
				if ( scope === 'desktop' ) {
					$dest = $previewDesktop;
					$source = $settingsDesktop;
				}
				if ( scope === 'mobile' ) {
					$dest = $previewMobile;
					if ( mobileSetting === 'default' ) {
						$source = $settingsDesktop;
						scopePrefix = scopes.desktop;
					} else {
						$source = $settingsMobile;
					}
				}
				
				// decide how much we need to update
				var updateButtons = false;
				var updateColors  = false;
				
				// if "disable on mobile" checked, hide mobile preview and stop
				if ( scope === 'mobile' && mobileSetting === 'disabled' ) {
					$dest.find( '.social-rocket-inline-buttons' ).css( 'visibility', 'hidden' );
					return;
				}
				
				// otherwise make sure mobile preview is visible
				$dest.find( '.social-rocket-inline-buttons' ).css( 'visibility', 'visible' );
				
				if ( typeof e === "undefined" ) {
					
					// if not triggered by an event, update everything
					updateButtons = true;
					updateColors = true;
					
				} else {
					
					if ( e.type === 'keyup' || e.type === 'change' ) {
						updateButtons = true;
					}
					
					if ( 
						e.type === 'colorPickerChange' ||
						$.inArray( e.target.name, [
							'social_rocket_inline_mobile_setting',
							scopePrefix + '_button_color_scheme',
							scopePrefix + '_button_color_scheme_custom_icon',
							scopePrefix + '_button_color_scheme_custom_background',
							scopePrefix + '_button_color_scheme_custom_border',
							scopePrefix + '_button_color_scheme_custom_hover',
							scopePrefix + '_button_color_scheme_custom_hover_bg',
							scopePrefix + '_button_color_scheme_custom_hover_border'
						] ) > -1 ||
						e.target.name.slice(-16) === '[color_override]'
					) {
						updateColors = true;
					}
					
					// special handling for certain colorpickers
					if (
						e.target.name === scopePrefix + '_total_color'
					) {
						updateButtons = true;
					}
					
					// sortUpdate triggers a change on the networks_order field, so no need to listen separately for it
					
				}
				
				// COLORS
				if ( updateColors ) {
					
					var extraSelector = '';
					if ( scope === 'mobile' ) {
						extraSelector = '.social-rocket-mobile-only';
					}
					
					// temporary solution:
					var tmpScope = 'social-rocket-inline';
					var tmpScopePrefix = scopePrefix;
					if ( scope === 'mobile' && mobileSetting !== 'default' ) {
						tmpScope = 'social-rocket-inline_mobile';
						tmpScopePrefix = 'social_rocket_inline_mobile';
					}
					var $tmpSource;
					if ( $( '#TB_window' ).find( '.social-rocket-network-settings-wrapper' ).length ) {
						$tmpSource = $( '#TB_ajaxContent' );
					} else {
						$tmpSource = $( '#social-rocket-settings-content' ).find( '.' + tmpScope + '-network-settings' );
					}
					
					// color scheme stuff
					var colorScheme            = $( '#' + scopePrefix + '_button_color_scheme' ).val();
					var customIcon             = $( 'input[name="' + scopePrefix + '_button_color_scheme_custom_icon"]:checked' ).val();
					var customIconColor        = $( '#' + scopePrefix + '_button_color_scheme_custom_icon_color' ).val();
					var customBackground       = $( 'input[name="' + scopePrefix + '_button_color_scheme_custom_background"]:checked' ).val();
					var customBackgroundColor  = $( '#' + scopePrefix + '_button_color_scheme_custom_background_color' ).val();
					var customBorder           = $( 'input[name="' + scopePrefix + '_button_color_scheme_custom_border"]:checked' ).val();
					var customBorderColor      = $( '#' + scopePrefix + '_button_color_scheme_custom_border_color' ).val();
					var customHover            = $( 'input[name="' + scopePrefix + '_button_color_scheme_custom_hover"]:checked' ).val();
					var customHoverColor       = $( '#' + scopePrefix + '_button_color_scheme_custom_hover_color' ).val();
					var customHoverBg          = $( 'input[name="' + scopePrefix + '_button_color_scheme_custom_hover_bg"]:checked' ).val();
					var customHoverBgColor     = $( '#' + scopePrefix + '_button_color_scheme_custom_hover_bg_color' ).val();
					var customHoverBorder      = $( 'input[name="' + scopePrefix + '_button_color_scheme_custom_hover_border"]:checked' ).val();
					var customHoverBorderColor = $( '#' + scopePrefix + '_button_color_scheme_custom_hover_border_color' ).val();
					
					// loop through network setting overrides
					$tmpSource.each(function() {
						
						// temporary solution, part 2:
						if (
							$( this ).attr( 'id' ) === 'TB_ajaxContent' &&
							$( this ).find( '.social-rocket-network-settings-wrapper' ).data( 'scope' ) !== tmpScope
						) {
							return;
						}
						
						var css                      = '';
						var finalIconColor           = false;
						var finalBackgroundColor     = false;
						var finalBorderColor         = false;
						var finalHoverColor          = false;
						var finalHoverBgColor        = false;
						var finalHoverBorderColor    = false;
						var network                  = $( this ).find( '.social-rocket-network-settings-wrapper' ).data( 'network' );
						var $networkColor            = $( this ).find( '#' + tmpScopePrefix + '_network_' + network + '\\[color\\]' );
						var $networkColorBg          = $( this ).find( '#' + tmpScopePrefix + '_network_' + network + '\\[color_bg\\]' );
						var $networkColorBorder      = $( this ).find( '#' + tmpScopePrefix + '_network_' + network + '\\[color_border\\]' );
						var $networkColorHover       = $( this ).find( '#' + tmpScopePrefix + '_network_' + network + '\\[color_hover\\]' );
						var $networkColorBgHover     = $( this ).find( '#' + tmpScopePrefix + '_network_' + network + '\\[color_bg_hover\\]' );
						var $networkColorBorderHover = $( this ).find( '#' + tmpScopePrefix + '_network_' + network + '\\[color_border_hover\\]' );
						var networkColorOverride     = $( this ).find( '#' + tmpScopePrefix + '_network_' + network + '\\[color_override\\]' ).prop( 'checked' );
						var networkColorValue        = networkColorOverride && $networkColor.val() > '' ? $networkColor.val() : $networkColor.attr( 'placeholder' );
						var networkColorBgValue      = networkColorOverride && $networkColorBg.val() > '' ? $networkColorBg.val() : $networkColorBg.attr( 'placeholder' );
						var networkColorBorderValue  = networkColorOverride && $networkColorBorder.val() > '' ? $networkColorBorder.val() : $networkColorBorder.attr( 'placeholder' );
						var networkColorHoverValue   = networkColorOverride && $networkColorHover.val() > '' ? $networkColorHover.val() : $networkColorHover.attr( 'placeholder' );
						var networkColorBgHoverValue = networkColorOverride && $networkColorBgHover.val() > '' ? $networkColorBgHover.val() : $networkColorBgHover.attr( 'placeholder' );
						var networkColorBorderHoverValue = networkColorOverride && $networkColorBorderHover.val() > '' ? $networkColorBorderHover.val() : $networkColorBorderHover.attr( 'placeholder' );
						
						// icon color
						if ( networkColorOverride ) {
							finalIconColor = networkColorValue; // network-specific setting always wins
						} else if ( colorScheme === 'custom' ) {
							if ( customIcon === 'custom' && customIconColor > '' ) {
								finalIconColor = customIconColor;
							} else if ( customIcon === 'network_icon' ) {
								finalIconColor = networkColorValue;
							} else if ( customIcon === 'network_background' ) {
								finalIconColor = networkColorBgValue;
							} else if ( customIcon === 'network_border' ) {
								finalIconColor = networkColorBorderValue;
							}
						} else if ( colorScheme === 'inverted' ) {
							finalIconColor = networkColorBgValue;
						} else if ( colorScheme === 'default' ) {
							finalIconColor = '#ffffff';
						}
						if ( finalIconColor ) {
							css += '.social-rocket-inline-preview ' + extraSelector + ' .social-rocket-' + network + ' .social-rocket-button-icon,';
							css += '.social-rocket-inline-preview ' + extraSelector + ' .social-rocket-' + network + ' .social-rocket-button-cta,';
							css += '.social-rocket-inline-preview ' + extraSelector + ' .social-rocket-' + network + ' .social-rocket-button-count {';
							css += 'color:' + finalIconColor + ';';
							css += '}';
							css += '.social-rocket-inline-preview ' + extraSelector + ' .social-rocket-button.social-rocket-' + network + ' .social-rocket-button-icon svg,';
							css += '.social-rocket-inline-preview ' + extraSelector + ' .social-rocket-button.social-rocket-' + network + ' .social-rocket-button-icon svg g,';
							css += '.social-rocket-inline-preview ' + extraSelector + ' .social-rocket-button.social-rocket-' + network + ' .social-rocket-button-icon svg path {';
							css += 'fill: ' + finalIconColor + ';';
							css += '}';
						}
						
						// background color
						if ( networkColorOverride ) {
							finalBackgroundColor = networkColorBgValue; // network-specific setting always wins
						} else if ( colorScheme === 'custom' ) {
							if ( customBackground === 'custom' ) {
								finalBackgroundColor = customBackgroundColor;
							} else if ( customBackground === 'none' ) {
								finalBackgroundColor = 'transparent';
							} else if ( customBackground === 'network_icon' ) {
								finalBackgroundColor = networkColorValue;
							} else if ( customBackground === 'network_background' ) {
								finalBackgroundColor = networkColorBgValue;
							} else if ( customBackground === 'network_border' ) {
								finalBackgroundColor = networkColorBorderValue;
							}
						} else if ( colorScheme === 'inverted' ) {
							finalBackgroundColor = 'transparent';
						}
						if ( finalBackgroundColor ) {
							css += '.social-rocket-inline-preview ' + extraSelector + ' .social-rocket-button.social-rocket-' + network + '{';
							css += 'background-color:' + finalBackgroundColor + ';';
							css += '}';
						}
						
						// border color
						if ( networkColorOverride ) {
							finalBorderColor = networkColorBorderValue; // network-specific setting always wins
						} else if ( colorScheme === 'custom' ) {
							if ( customBorder === 'custom' ) {
								finalBorderColor = customBorderColor;
							} else if ( customBorder === 'none' ) {
								finalBorderColor = 'transparent';
							} else if ( customBorder === 'network_icon' ) {
								finalBorderColor = networkColorValue;
							} else if ( customBorder === 'network_background' ) {
								finalBorderColor = networkColorBgValue;
							} else if ( customBorder === 'network_border' ) {
								finalBorderColor = networkColorBorderValue;
							}
						} else if ( colorScheme === 'inverted' ) {
							finalBorderColor = 'transparent';
						}
						if ( finalBorderColor ) {
							css += '.social-rocket-inline-preview ' + extraSelector + ' .social-rocket-button.social-rocket-' + network + '{';
							css += 'border-color:' + finalBorderColor + ';';
							css += '}';
						}
						
						// icon hover color
						if ( networkColorOverride ) {
							finalHoverColor = networkColorHoverValue; // network-specific setting always wins
						} else if ( colorScheme === 'custom' ) {
							if ( customHover === 'custom' ) {
								finalHoverColor = customHoverColor;
							} else if ( customHover === 'none' ) {
								finalHoverColor = false;
							} else if ( customHover === 'network_hover' ) {
								finalHoverColor = networkColorHoverValue;
							}
						} else if ( colorScheme === 'inverted' ) {
							finalHoverColor = false;
							var invertedHoverColor = networkColorBgHoverValue;
							css += '.social-rocket-inline-preview ' + extraSelector + ' .social-rocket-button.social-rocket-' + network + ':hover .social-rocket-button-icon,';
							css += '.social-rocket-inline-preview ' + extraSelector + ' .social-rocket-button.social-rocket-' + network + ':hover .social-rocket-button-cta,';
							css += '.social-rocket-inline-preview ' + extraSelector + ' .social-rocket-button.social-rocket-' + network + ':hover .social-rocket-button-count {';
							css += 'color: ' + invertedHoverColor + ';';
							css += '}';
							css += '.social-rocket-inline-preview ' + extraSelector + ' .social-rocket-button.social-rocket-' + network + ':hover .social-rocket-button-icon svg,';
							css += '.social-rocket-inline-preview ' + extraSelector + ' .social-rocket-button.social-rocket-' + network + ':hover .social-rocket-button-icon svg g,';
							css += '.social-rocket-inline-preview ' + extraSelector + ' .social-rocket-button.social-rocket-' + network + ':hover .social-rocket-button-icon svg path {';
							css += 'fill: ' + invertedHoverColor + ';';
							css += '}';
						}
						if ( finalHoverColor ) {
							css += '.social-rocket-inline-preview ' + extraSelector + ' .social-rocket-' + network + ':hover .social-rocket-button-icon,';
							css += '.social-rocket-inline-preview ' + extraSelector + ' .social-rocket-' + network + ':hover .social-rocket-button-cta,';
							css += '.social-rocket-inline-preview ' + extraSelector + ' .social-rocket-' + network + ':hover .social-rocket-button-count {';
							css += 'color:' + finalHoverColor + ';';
							css += '}';
							css += '.social-rocket-inline-preview ' + extraSelector + ' .social-rocket-button.social-rocket-' + network + ':hover .social-rocket-button-icon svg,';
							css += '.social-rocket-inline-preview ' + extraSelector + ' .social-rocket-button.social-rocket-' + network + ':hover .social-rocket-button-icon svg g,';
							css += '.social-rocket-inline-preview ' + extraSelector + ' .social-rocket-button.social-rocket-' + network + ':hover .social-rocket-button-icon svg path {';
							css += 'fill: ' + finalHoverColor + ';';
							css += '}';
						}
						
						// background hover color
						if ( networkColorOverride ) {
							finalHoverBgColor = networkColorBgHoverValue; // network-specific setting always wins
						} else if ( colorScheme === 'custom' ) {
							if ( customHoverBg === 'custom' ) {
								finalHoverBgColor = customHoverBgColor;
							} else if ( customHoverBg === 'none' ) {
								finalHoverBgColor = false;
							} else if ( customHoverBg === 'network_hover_bg' ) {
								finalHoverBgColor = networkColorBgHoverValue;
							}
						} else if ( colorScheme === 'inverted' ) {
							finalHoverBgColor = false;
						}
						if ( finalHoverBgColor ) {
							css += '.social-rocket-inline-preview ' + extraSelector + ' .social-rocket-button.social-rocket-' + network + ':hover{';
							css += 'background-color:' + finalHoverBgColor + ';';
							css += '}';
						}
						
						// border hover color
						if ( networkColorOverride ) {
							finalHoverBorderColor = networkColorBorderHoverValue; // network-specific setting always wins
						} else if ( colorScheme === 'custom' ) {
							if ( customHoverBorder === 'custom' ) {
								finalHoverBorderColor = customHoverBorderColor;
							} else if ( customHoverBorder === 'none' ) {
								finalHoverBorderColor = false;
							} else if ( customHoverBorder === 'network_hover_border' ) {
								finalHoverBorderColor = networkColorBorderHoverValue;
							}
						} else if ( colorScheme === 'inverted' ) {
							finalHoverBorderColor = false;
						}
						if ( finalHoverBorderColor ) {
							css += '.social-rocket-inline-preview ' + extraSelector + ' .social-rocket-button.social-rocket-' + network + ':hover{';
							css += 'border-color:' + finalHoverBorderColor + ';';
							css += '}';
						}
					
						// wrap up
						var $existingCssBlock = $( '#social-rocket-inline-preview-custom-css-' + network + '-' + scope );
						if ( $existingCssBlock.length ) {
							$existingCssBlock.html( css );
						} else {
							css = '<style id="social-rocket-inline-preview-custom-css-' + network + '-' + scope + '">' + css;
							css += '</style>';
							$( 'head' ).append( css );
						}
						
					});
					
				}
				
				// BUTTONS
				if ( updateButtons ) {
				
					// 1) button alignment (do this first because we reset style)
					var buttonAlignment = $( '#' + scopePrefix + '_button_alignment' ).val();
					if ( buttonAlignment === 'stretch' ) {
						$dest.find( '.social-rocket-buttons' )
							.attr( 'style', 'display: -webkit-box; display: -moz-box; display: -ms-flexbox; display: -webkit-flex; display: flex; -webkit-box-orient: horizontal; -webkit-box-direction: normal; -ms-flex-direction: row; flex-direction: row; -ms-flex-wrap: wrap; flex-wrap: wrap; -webkit-box-pack: justify; -ms-flex-pack: justify; justify-content: space-between; -ms-flex-line-pack: stretch; align-content: stretch; -webkit-box-align: stretch; -ms-flex-align: stretch; align-items: stretch;' )
							.find('.social-rocket-button, .social-rocket-button-anchor, .social-rocket-shares-total')
							.attr( 'style', '-webkit-box-flex: 1; -ms-flex: 1; flex: 1;' );
					} else {
						$dest.find( '.social-rocket-buttons' ) // reset here
							.attr( 'style', '' )
							.css( 'text-align', buttonAlignment )
							.find('.social-rocket-button, .social-rocket-button-anchor, .social-rocket-shares-total')
							.attr( 'style', '' );
					}
					
					// 2) show/hide networks
					var activeNetworks = [];
					$source.find( '.social-rocket-settings-networks tr.active' ).each(function() {
						if ( $( this ).data( 'network' ) ) {
							activeNetworks.push( $( this ).data( 'network' ) );
						}
					});
					$dest.find( '.social-rocket-button' ).each(function() {
						if ( $.inArray( $( this ).data( 'network' ), activeNetworks ) >= 0 ) {
							if ( $( this ).css( 'display' ) === 'none' ) {
								$( this ).css( 'display', '' );
							}
						} else {
							$( this ).hide();
						}
					});
					
					// 3) update networks order
					var $tmpSource;
					if ( scope === 'desktop' ) {
						$tmpSource = $previewDesktop;
					}
					if ( scope === 'mobile' ) {
						$tmpSource = $previewMobile;
					}
					var buttons = [];
					var order = $source.find( '.social-rocket-settings-networks-sortable' ).sortable( "toArray", { attribute: 'data-network' } );
					var totalPosition = $( '#' + scopePrefix + '_total_position' ).val();
					if ( totalPosition === 'before' ) {
						$tmpSource.find( '.social-rocket-shares-total' ).each(function() {
							buttons.push( $( this ).detach() );
						});
					}
					$.each( order, function() {
						buttons.push( $tmpSource.find( '.social-rocket-' + this ).detach() );
						if ( this === '_more' ) {
							var $div = $( '<div>', { 'class': 'social-rocket-more-buttons' } );
							buttons.push( $div );
						}
					});
					if ( totalPosition === 'after' ) {
						$tmpSource.find( '.social-rocket-shares-total' ).each(function() {
							buttons.push( $( this ).detach() );
						});
					}
					$dest.find( '.social-rocket-buttons' ).empty().append( buttons );
					
					// 3b) more button
					if ( $source.find( '.social-rocket-settings-networks-sortable' ).find( 'tr[data-network="_more"]' ).hasClass( 'active' ) ) {
						var moreTriggered = false;
						var moreButtons = [];
						var moreVisible = 0;
						$.each( order, function() {
							if ( moreTriggered ) {
								if ( $tmpSource.find( '.social-rocket-' + this ).css( 'display' ) !== 'none' ) {
									moreVisible++;
								}
								moreButtons.push( $tmpSource.find( '.social-rocket-' + this ).detach() );
							}
							if ( this === '_more' ) {
								moreTriggered = true;
							}
						});
						if ( ! moreVisible ) {
							$dest.find( '.social-rocket-_more' ).hide();
						}
						$dest.find( '.social-rocket-more-buttons' ).empty().append( moreButtons );
					}
					
					// 3c) total shares color
					$dest.find( '.social-rocket-shares-total' )
						.css( 'color', $( '#' + scopePrefix + '_total_color' ).val() );
					
					// 4) button size
					var buttonSize = $( '#' + scopePrefix + '_button_size' ).val();
					var buttonStyle = $( '#' + scopePrefix + '_button_style' ).val();
					if ( buttonSize > '' && buttonSize != 100 ) {
						var defaultButtonHeight   = 40;
						var defaultButtonWidth    = 40;
						var defaultLineHeight     = $.inArray( buttonStyle, [ 'round', 'square' ] ) >= 0 ? 40 : 30;
						var defaultIconSize       = 16;
						var defaultCtaSize        = 13;
						var defaultTotalCountSize = 18;
						var defaultTotalHeight    = 14;
						var defaultTotalLabelSize = 9;
						if ( $.inArray( buttonStyle, [ 'round', 'square' ] ) >= 0 ) {
							$dest.find( '.social-rocket-button' )
								.css( 'width', defaultButtonWidth * ( buttonSize / 100 ) + 'px' )
								.css( 'height', defaultButtonHeight * ( buttonSize / 100 ) + 'px' );
						} else {
							$dest.find( '.social-rocket-button' )
								.css( 'width', '' )
								.css( 'height', '' );
						}
						$dest.find( '.social-rocket-button' )
							.css( 'max-height', defaultLineHeight * ( buttonSize / 100 ) + 'px' );
						$dest.find( '.social-rocket-button-anchor' )
							.css( 'line-height', defaultLineHeight * ( buttonSize / 100 ) + 'px' );
						$dest.find( '.social-rocket-button-icon' )
							.css( 'font-size', defaultIconSize * ( buttonSize / 100 ) + 'px' )
							.find( 'svg' )
							.css( 'width', 'auto' )
							.css( 'height', defaultIconSize * ( buttonSize / 100 ) + 'px' );
						$dest.find( '.social-rocket-button-cta, .social-rocket-button-count' )
							.css( 'font-size', defaultCtaSize * ( buttonSize / 100 ) + 'px' );
						$dest.find( '.social-rocket-shares-total' )
							.css( 'line-height', defaultLineHeight * ( buttonSize / 100 ) + 'px' );
						$dest.find( '.social-rocket-shares-total' ).children( 'i' )
							.css( 'font-size', defaultIconSize * ( buttonSize / 100 ) + 'px' );
						$dest.find( '.social-rocket-shares-total-count' )
							.css( 'font-size', defaultTotalCountSize * ( buttonSize / 100 ) + 'px' )
							.css( 'line-height', defaultTotalHeight * ( buttonSize / 100 ) + 'px' );
						$dest.find( '.social-rocket-shares-total-label' )
							.css( 'font-size', defaultTotalLabelSize * ( buttonSize / 100 ) + 'px' )
							.css( 'line-height', defaultTotalHeight * ( buttonSize / 100 ) + 'px' );
						$dest.find( '.social-rocket-shares-total-inner' )
							.css( 'margin-top', ( ( defaultLineHeight * ( buttonSize / 100 ) ) * 0.16 ) + 'px' );
					} else {
						$dest.find( '.social-rocket-button' )
							.css( 'width', '' )
							.css( 'height', '' )
							.css( 'max-height', '' );
						$dest.find( '.social-rocket-button-anchor' )
							.css( 'line-height', '' );
						$dest.find( '.social-rocket-button-icon')
							.css( 'font-size', '' )
							.find( 'svg' )
							.css( 'width', '' )
							.css( 'height', '' );
						$dest.find( '.social-rocket-button-cta, .social-rocket-button-count' )
							.css( 'font-size', '' );
						$dest.find( '.social-rocket-shares-total' )
							.css( 'line-height', '' );
						$dest.find( '.social-rocket-shares-total' ).children( 'i' )
							.css( 'font-size', '' );
						$dest.find( '.social-rocket-shares-total-count' )
							.css( 'font-size', '' )
							.css( 'line-height', '' );
						$dest.find( '.social-rocket-shares-total-label' )
							.css( 'font-size', '' )
							.css( 'line-height', '' );
						$dest.find( '.social-rocket-shares-total-inner' )
							.css( 'margin-top', '' );
					}
					
					// 5) everything else button-related
					$dest.find( '.social-rocket-button' )
						.removeClass( 'social-rocket-button-oval social-rocket-button-rectangle social-rocket-button-round social-rocket-button-square' )
						.addClass( 'social-rocket-button-' + $( '#' + scopePrefix + '_button_style' ).val() )
						.css( 'border-style', $( '#' + scopePrefix + '_border' ).val() )
						.css( 'border-width', $( '#' + scopePrefix + '_border_size' ).val() + 'px' )
						.css( 'border-radius', ( $.inArray( $( '#' + scopePrefix + '_button_style' ).val(), [ 'rectangle', 'square' ] ) >= 0 ? $( '#' + scopePrefix + '_border_radius' ).val() + 'px' : '' ) );
						
					$dest.find( '.social-rocket-button, .social-rocket-shares-total' )
						.css( 'margin-right', $( '#' + scopePrefix + '_margin_right' ).val() + 'px' )
						.css( 'margin-bottom', $( '#' + scopePrefix + '_margin_bottom' ).val() + 'px' );
					
					if (
						$( '#' + scopePrefix + '_button_show_cta' ).prop( 'checked' ) == true &&
						$.inArray( $( '#' + scopePrefix + '_button_style' ).val(), [ 'rectangle', 'oval' ] ) >= 0
					) {
						$dest.find( '.social-rocket-button-cta' ).show();
					} else {
						$dest.find( '.social-rocket-button-cta' ).hide();
					}
				
					// 6) heading text
					var headingText = $( '#' + scopePrefix + '_heading_text' ).val();
					if ( headingText > '' ) {
						$dest.find( '.social-rocket-buttons-heading' ).show().text( headingText );
					} else {
						$dest.find( '.social-rocket-buttons-heading' ).hide();
					}
					var headingElement = $( '#' + scopePrefix + '_heading_element' ).val();
					if ( headingElement > '' ) {
						$dest.find( '.social-rocket-buttons-heading' ).replaceWith( $( '<' + headingElement + ' class="social-rocket-buttons-heading">' + headingText + '</' + headingElement + '>' ) );
					}
					var headingAlignment = $( '#' + scopePrefix + '_heading_alignment' ).val();
					if ( headingAlignment !== 'default' ) {
						$dest.find( '.social-rocket-buttons-heading' ).css( 'text-align', headingAlignment );
					} else {
						$dest.find( '.social-rocket-buttons-heading' ).css( 'text-align', '' );
					}
					
					// 7) share count settings
					var showCounts = $( '#' + scopePrefix + '_show_counts' ).is( ':checked' )
					if ( showCounts ) {
						$dest.find( '.social-rocket-button-count' ).css( 'display', '' );
					} else {
						$dest.find( '.social-rocket-button-count' ).hide();
					}
					var showTotal = $( '#' + scopePrefix + '_show_total' ).is(':checked');
					if ( showTotal ) {
						$dest.find( '.social-rocket-shares-total' ).css( 'display', 'inline-block' );
					} else {
						$dest.find( '.social-rocket-shares-total' ).css( 'display', 'none' );
					}
					var totalShowIcon = $( '#' + scopePrefix + '_total_show_icon' ).is(':checked');
					if ( totalShowIcon ) {
						$dest.find( '.social-rocket-shares-total i' ).show();
					} else {
						$dest.find( '.social-rocket-shares-total i' ).hide();
					}
					
					// 8) network settings overrides
					// temporary solution:
					var tmpScope = 'social-rocket-inline';
					var tmpScopePrefix = scopePrefix;
					if ( scope === 'mobile' && mobileSetting !== 'default' ) {
						tmpScope = 'social-rocket-inline_mobile';
						tmpScopePrefix = 'social_rocket_inline_mobile';
					}
					var $tmpSource;
					if ( $( '#TB_window' ).find( '.social-rocket-network-settings-wrapper' ).length ) {
						$tmpSource = $( '#TB_ajaxContent' );
					} else {
						$tmpSource = $( '#social-rocket-settings-content' ).find( '.' + tmpScope + '-network-settings' );
					}
					$tmpSource.each(function() {
						var network = $( this ).find( '.social-rocket-network-settings-wrapper' ).data( 'network' );
						
						// CTA
						var $cta     = $( this ).find( '#' + tmpScopePrefix + '_network_' + network + '\\[cta\\]' );
						var ctaValue = $cta.val() > '' ? $cta.val() : $cta.attr( 'placeholder' );
						if ( typeof ctaValue !== "undefined" ) {
							$dest.find( '.social-rocket-' + network + ' .social-rocket-button-cta' ).each(function() {
								$( this ).html( ctaValue );
							});
						}
						
						// Icon
						var $iconClass     = $( this ).find( '#' + tmpScopePrefix + '_network_' + network + '\\[icon_class\\]' );
						var iconClassValue = $iconClass.val();
						if ( iconClassValue	> '' ) {
							iconClassValue = iconClassValue.replace( '<i class="', '' ).replace( '"></i>', '' ); // filter for people who paste the entire html code, not just the css classes
							$iconClass.val( iconClassValue );
						} else {
							iconClassValue = $iconClass.attr( 'placeholder' );
						}
						if ( typeof iconClassValue !== "undefined" ) {
							$dest.find( '.social-rocket-' + network + ' .social-rocket-button-icon' ).each(function() {
								$( this ).removeClass().addClass( iconClassValue + ' social-rocket-button-icon' );
							});
						}
						
						// Icon SVG
						var $iconSvg     = $( this ).find( '#' + tmpScopePrefix + '_network_' + network + '\\[icon_svg\\]' );
						var iconSvgValue = $iconSvg.val() > '' ? $iconSvg.val() : $iconSvg.attr( 'placeholder' );
						if ( typeof iconSvgValue !== "undefined" ) {
							$dest.find( '.social-rocket-' + network + ' .social-rocket-button-icon' ).each(function() {
								$( this ).html( iconSvgValue );
							});
						}
					});
				
				}
				
			});
			
			// execute hooks from external add-ons, if any
			$( socialRocketAdmin.hooks.inline_update_preview ).each( function( key, val ) {
				val();
			});
			
			// FOR DEBUGGING:
			// var end = Date.now()
			// var duration = end - start;
			// console.log( 'inline updatePreview() finished at: ' + end );
			// console.log( 'duration: ' + duration + 'ms' );
			// window.durationcount++;
			// window.durationtotal = window.durationtotal + duration;
			// console.log( 'average duration this session: ' + ( window.durationtotal / window.durationcount ) + 'ms' );
		
		};
		
		
		// function to update the "use same settings as desktop" div when networks are activated, removed, or sort order changed
		var syncNetworks = function() {
			
			var $desktop = $( '#social-rocket-settings-inline-buttons-desktop' );
			var $mobile  = $( '#social-rocket-settings-inline-buttons-mobile-default' );
			
			// show/hide networks
			var activeNetworks = [];
			$desktop.find( '.social-rocket-settings-networks tr.active' ).each(function(){
				if ( $( this ).data( 'network' ) ) {
					activeNetworks.push( $( this ).data( 'network' ) );
				}
			});
			$mobile.find( '.social-rocket-settings-networks tbody tr' ).each(function(){
				if ( $.inArray( $( this ).data( 'network' ), activeNetworks ) >= 0 ) {
					$( this ).addClass( 'active' ).show();
				} else {
					$( this ).removeClass( 'active' ).hide();
				}
			});
			if ( activeNetworks.length > 0 ) {
				$mobile.find( '.social-rocket-settings-networks' ).show();
				$mobile.find( '.social-rocket-settings-networks-empty' ).hide();
			} else {	
				$mobile.find( '.social-rocket-settings-networks' ).hide();
				$mobile.find( '.social-rocket-settings-networks-empty' ).show();
			}
			
			// update networks order
			var order = $desktop.find( '.social-rocket-settings-networks-sortable' ).sortable( "toArray", { attribute: 'data-network' } );
			var networks = [];
			$.each( order, function(){
				networks.push( $mobile.find('.social-rocket-settings-networks tr[data-network="'+this+'"]').detach() );
			});
			$.each( networks, function(){
				$mobile.find( '.social-rocket-settings-networks' ).append( this );
			});
			
		}
		
		
		// function to update a specific setting in the "use same settings as desktop" div on the mobile tab, when it changes on the desktop tab
		var syncSetting = function( e ) {
			
			var sourceName = e.target.name;
			var sourceId   = e.target.id;
			var sourceVal  = e.target.value;
			
			if ( e.type === "colorPickerChange" ) {
				
				var destId = sourceId.replace( 'social_rocket_inline_', 'social_rocket_inline_mobile_' );
				$( '#' + destId ).css( 'background-color', sourceVal );
				
			} else if ( e.target.type === 'radio' ) {
				
				if ( typeof sourceName !== "undefined" && sourceName ) {
					
					var destName = sourceName.replace( 'social_rocket_inline_', 'social_rocket_inline_mobile_' );
					$( 'input[name="' + destName + '"]' ).val( [ sourceVal ] );
					
				}
				
			} else if ( e.target.type === 'checkbox' ) {
				
				if ( typeof sourceName !== "undefined" && sourceName ) {
					
					var sourceVals = [];
					$( 'input[name="' + sourceName + '"]' ).filter( ':checked' ).each(function() {
						sourceVals.push( $( this ).val() );
					});
					
					var destName = sourceName.replace( 'social_rocket_inline_', 'social_rocket_inline_mobile_' );
					$( 'input[name="' + destName + '"]' ).val( [ sourceVals ] );
					
				}
			
			} else {
				
				if ( typeof sourceId !== "undefined" && sourceId ) {
					
					var destId = sourceId.replace( 'social_rocket_inline_', 'social_rocket_inline_mobile_' );
					$( '#' + destId ).val( sourceVal );
					
				}
				
			}
			
		};
		
		
		// change handler for desktop tab
		$( document ).on( 'change', '#social-rocket-settings-inline-buttons-desktop', function( e ) {
			
			// "Button color scheme" toggles stuff
			if ( e.target.id === 'social_rocket_inline_button_color_scheme' ) {
				if ( e.target.value === 'custom' ) {
					$( '.social_rocket_inline_button_color_scheme_custom_toggle' ).show();
					$( '.social_rocket_inline_button_color_scheme_custom_toggle' ).first().find('.social-rocket-collapsable-toggle').click();
				} else {
					$( '.social_rocket_inline_button_color_scheme_custom_toggle' ).hide()
					if ( $( '.social_rocket_inline_button_color_scheme_custom_colors' ).first().hasClass( 'social-rocket-collapsable-expanded' ) ) {
						$( '.social_rocket_inline_button_color_scheme_custom_toggle' ).first().find('.social-rocket-collapsable-toggle').click();
					}
				}
			}
			
			// "Button style" toggles stuff
			if ( e.target.id === 'social_rocket_inline_button_style' ) {
				if ( $.inArray( e.target.value, [ 'rectangle', 'square' ] ) >= 0 ) {
					$( '.social_rocket_inline_border_radius_wrapper' ).show();
				} else {
					$( '.social_rocket_inline_border_radius_wrapper' ).hide();
				}
				if ( $.inArray( e.target.value, [ 'rectangle', 'oval' ] ) >= 0 ) {
					$( '.social_rocket_inline_button_show_cta_wrapper' ).show();
				} else {
					$( '.social_rocket_inline_button_show_cta_wrapper' ).hide();
				}
				if ( $.inArray( e.target.value, [ 'round', 'square' ] ) >= 0 ) {
					if ( $( '#social_rocket_inline_button_alignment' ).val() === 'stretch' ) {
						$( '#social_rocket_inline_button_alignment' ).val( 'center' );
					}
					$( '#social_rocket_inline_button_alignment' ).find( '[value="stretch"]' ).hide();
				} else {
					$( '#social_rocket_inline_button_alignment' ).find( '[value="stretch"]' ).show();
				}
			}
			
			// "Enable "More" button" toggles network
			if ( e.target.id === 'social_rocket_inline_more_enable' ) {
				$( '#social_rocket_inline_networks__more' ).click();
			}
			
			// "Show share count" toggles "Minimum shares"
			if ( e.target.id === 'social_rocket_inline_show_counts' ) {
				if ( e.target.checked == true ) {
					$( '.social_rocket_inline_show_counts_min' ).show();
				} else {
					$( '.social_rocket_inline_show_counts_min' ).hide();
				}
			}
			
			// "Show total share count" toggles stuff
			if ( e.target.id === 'social_rocket_inline_show_total' ) {
				if ( e.target.checked == true ) {
					$( '.social_rocket_inline_show_total' ).show();
				} else {
					$( '.social_rocket_inline_show_total' ).hide();
				}
			}
			
			// sync it to the "Use same settings as desktop" div (mobile tab)
			// (except networks, we handle that separately)
			if ( ! $( e.target ).closest( '.social-rocket-settings-networks-selector-wrapper' ).length ) {
				syncSetting( e );
			}
			
		});
		
		
		// change handler for mobile setting
		$( document ).on( 'change', 'input[name="social_rocket_inline_mobile_setting"]', function() {
			
			// toggle "Use same settings as desktop" div
			if ( $( '#social_rocket_inline_mobile_setting_default' ).is( ':checked' ) ) {
				$( '#social-rocket-settings-inline-buttons-mobile-default' ).show();
			} else {
				$( '#social-rocket-settings-inline-buttons-mobile-default' ).hide();
			}
			
		});
		
		
		// colorPickerChange handler for desktop tab
		$( document ).on( 'colorPickerChange', '#social-rocket-settings-inline-buttons-desktop', function( e ) {
			
			// if colorpicker is linked to a radio button or something
			if ( $( e.target ).data( 'for' ) > '' ) {
				var forId = '#' + $( e.target ).data( 'for' );
				var forName = $( forId ).attr( 'name' );
				var forValue = $( forId ).val();
				if ( forName > '' ) {
					$( 'input[name="' + forName + '"]' ).val( [ forValue ] );
				}
			}
			
			// sync it to the "Use same settings as desktop" div (mobile tab)
			syncSetting( e );
			
		});
		
		
		// sortupdate handler for desktop tab
		$( document ).on( 'sortupdate', '#social-rocket-settings-inline-buttons-desktop', function() {
			
			// sync it to the "Use same settings as desktop" div (mobile tab)
			syncNetworks();
			
		});
		
		
		// click handler
		$( document ).on( 'click', '.social-rocket_page_social_rocket_inline_buttons', function( e ) {
			
			// Desktop tab switches to desktop preview
			if ( $( e.target ).data( 'tab' ) === 'inline-buttons-desktop' ) {
				$( '#social_rocket_inline_preview_mobile' ).hide();
				$( '#social_rocket_inline_preview_desktop' ).show();
			}
			
			/*
			// Desktop preview toggle button
			if ( e.target.id === 'social_rocket_inline_preview_desktop_toggle' ) {
				e.preventDefault();
				if ( $('#social_rocket_inline_preview_desktop').is(':visible') ) {
					$('a[data-sr-toggle="social_rocket_inline_preview_inner"]').click();
				} else {
					$('#social_rocket_inline_preview_mobile').hide();
					$('#social_rocket_inline_preview_desktop').show();
					if ( ! $('#social_rocket_inline_preview_inner').is(':visible') ) {
						$('a[data-sr-toggle="social_rocket_inline_preview_inner"]').click();
					}
				}
			}
			*/
			
			// Mobile tab switches to mobile preview
			if ( $( e.target ).data( 'tab' ) === 'inline-buttons-mobile' ) {
				$( '#social_rocket_inline_preview_desktop' ).hide();
				$( '#social_rocket_inline_preview_mobile' ).show();
			}
			
			/*
			// Mobile preview toggle button
			if ( e.target.id === 'social_rocket_inline_preview_mobile_toggle' ) {
				e.preventDefault();
				if ( $('#social_rocket_inline_preview_mobile').is(':visible') ) {
					$('a[data-sr-toggle="social_rocket_inline_preview_inner"]').click();
				} else {
					$('#social_rocket_inline_preview_desktop').hide();
					$('#social_rocket_inline_preview_mobile').show();
					if ( ! $('#social_rocket_inline_preview_inner').is(':visible') ) {
						$('a[data-sr-toggle="social_rocket_inline_preview_inner"]').click();
					}
				}
			}
			*/
			
		});
		
		
		// handler for everything that triggers a preview update
		$( document ).on( 'change colorPickerChange keyup', '.social-rocket_page_social_rocket_inline_buttons', function( e ) {
			
			updatePreview( e );
			
		});
		
		
		// trigger the first update manually
		updatePreview();
		
		
		// make sure we're seeing the correct preview to start with
		var hash = location.hash.replace( '#', '' );
		if ( hash === 'inline-buttons-mobile' ) {
			$( '#social_rocket_inline_preview_desktop' ).hide();
			$( '#social_rocket_inline_preview_mobile' ).show();
		}
		
	});
	
	
	/*
	 * Settings page: floating buttons stuff
	 */
	$( document ).ready(function() {
		
		if ( ! $( 'body' ).hasClass( 'social-rocket_page_social_rocket_floating_buttons' ) ) {
			return; // nothing to do
		}
		
		// FOR DEBUGGING:
		// window.durationcount = 0;
		// window.durationtotal = 0;
		
		// function to update preview
		var updatePreview = function( e ) {
			
			if (
				typeof e !== "undefined" &&
				( e.type === 'keyup' || e.type === 'change' ) &&
				$.inArray( e.target.tagName, [ 'INPUT', 'SELECT', 'TEXTAREA' ] ) < 0
			) {
				// ignore keyup and change events not from our form fields
				return;
			}
			
			// FOR DEBUGGING:
			// var start = Date.now();
			// console.log( 'floating updatePreview() started at: ' + start );
			
			// setup vars
			var $previewDesktop  = $( '#social_rocket_floating_preview_desktop' );
			var $previewMobile   = $( '#social_rocket_floating_preview_mobile' );
			var $settingsDesktop = $( '#social-rocket-settings-floating-buttons-desktop' );
			var $settingsMobile  = $( socialRocketAdmin.hooks.floating_mobile_settings > '' ?
										socialRocketAdmin.hooks.floating_mobile_settings :
										'#social-rocket-settings-floating-buttons-mobile-default' );
			var mobileSetting    = $( 'input[name="social_rocket_floating_mobile_setting"]:checked' ).val();
			var scopes           = {
				desktop:				'social_rocket_floating',
				mobile:					( socialRocketAdmin.hooks.floating_mobile_scope > '' ?
											socialRocketAdmin.hooks.floating_mobile_scope :
											'social_rocket_floating_mobile' )
			};
			
			// start loop
			$.each( scopes, function( scope, scopePrefix ) {
				
				var $dest;
				var $source;
				
				if ( scope === 'desktop' ) {
					$dest = $previewDesktop;
					$source = $settingsDesktop;
				}
				if ( scope === 'mobile' ) {
					$dest = $previewMobile;
					if ( mobileSetting === 'default' ) {
						$source = $settingsDesktop;
						scopePrefix = scopes.desktop;
					} else {
						$source = $settingsMobile;
					}
				}
				
				// decide how much we need to update
				var updateButtons = false;
				var updateColors  = false;
				
				// if "disable on mobile" checked, hide mobile preview and stop
				if ( scope === 'mobile' && mobileSetting === 'disabled' ) {
					$dest.find( '.social-rocket-floating-buttons' ).css( 'visibility', 'hidden' );
					return;
				}
				
				// otherwise make sure mobile preview is visible
				$dest.find( '.social-rocket-floating-buttons' ).css( 'visibility', 'visible' );
				
				if ( typeof e === "undefined" ) {
					
					// if not triggered by an event, update everything
					updateButtons = true;
					updateColors = true;
					
				} else {
					
					if ( e.type === 'keyup' || e.type === 'change' ) {
						updateButtons = true;
					}
					
					if ( 
						e.type === 'colorPickerChange' ||
						$.inArray( e.target.name, [
							'social_rocket_floating_mobile_setting',
							scopePrefix + '_button_color_scheme',
							scopePrefix + '_button_color_scheme_custom_icon',
							scopePrefix + '_button_color_scheme_custom_background',
							scopePrefix + '_button_color_scheme_custom_border',
							scopePrefix + '_button_color_scheme_custom_hover',
							scopePrefix + '_button_color_scheme_custom_hover_bg',
							scopePrefix + '_button_color_scheme_custom_hover_border'
						] ) > -1 ||
						e.target.name.slice(-16) === '[color_override]'
					) {
						updateColors = true;
					}
					
					// special handling for certain colorpickers
					if (
						e.target.name === scopePrefix + '_bar_background_color' ||
						e.target.name === scopePrefix + '_total_color'
					) {
						updateButtons = true;
					}
					
					// sortUpdate triggers a change on the networks_order field, so no need to listen separately for it
					
				}
				
				// COLORS
				if ( updateColors ) {
					
					var extraSelector = '';
					if ( scope === 'mobile' ) {
						extraSelector = '.social-rocket-mobile-only';
					}
					
					// temporary solution:
					var tmpScope = 'social-rocket-floating';
					var tmpScopePrefix = scopePrefix;
					if ( scope === 'mobile' && mobileSetting !== 'default' ) {
						tmpScope = 'social-rocket-floating_mobile';
						tmpScopePrefix = 'social_rocket_floating_mobile';
					}
					var $tmpSource;
					if ( $( '#TB_window' ).find( '.social-rocket-network-settings-wrapper' ).length ) {
						$tmpSource = $( '#TB_ajaxContent' );
					} else {
						$tmpSource = $( '#social-rocket-settings-content' ).find( '.' + tmpScope + '-network-settings' );
					}
					
					// color scheme stuff
					var colorScheme            = $( '#' + scopePrefix + '_button_color_scheme' ).val();
					var customIcon             = $( 'input[name="' + scopePrefix + '_button_color_scheme_custom_icon"]:checked' ).val();
					var customIconColor        = $( '#' + scopePrefix + '_button_color_scheme_custom_icon_color' ).val();
					var customBackground       = $( 'input[name="' + scopePrefix + '_button_color_scheme_custom_background"]:checked' ).val();
					var customBackgroundColor  = $( '#' + scopePrefix + '_button_color_scheme_custom_background_color' ).val();
					var customBorder           = $( 'input[name="' + scopePrefix + '_button_color_scheme_custom_border"]:checked' ).val();
					var customBorderColor      = $( '#' + scopePrefix + '_button_color_scheme_custom_border_color' ).val();
					var customHover            = $( 'input[name="' + scopePrefix + '_button_color_scheme_custom_hover"]:checked' ).val();
					var customHoverColor       = $( '#' + scopePrefix + '_button_color_scheme_custom_hover_color' ).val();
					var customHoverBg          = $( 'input[name="' + scopePrefix + '_button_color_scheme_custom_hover_bg"]:checked' ).val();
					var customHoverBgColor     = $( '#' + scopePrefix + '_button_color_scheme_custom_hover_bg_color' ).val();
					var customHoverBorder      = $( 'input[name="' + scopePrefix + '_button_color_scheme_custom_hover_border"]:checked' ).val();
					var customHoverBorderColor = $( '#' + scopePrefix + '_button_color_scheme_custom_hover_border_color' ).val();
					
					// loop through network setting overrides
					$tmpSource.each(function() {
						
						// temporary solution, part 2:
						if (
							$( this ).attr( 'id' ) === 'TB_ajaxContent' &&
							$( this ).find( '.social-rocket-network-settings-wrapper' ).data( 'scope' ) !== tmpScope
						) {
							return;
						}
						
						var css                      = '';
						var finalIconColor           = false;
						var finalBackgroundColor     = false;
						var finalBorderColor         = false;
						var finalHoverColor          = false;
						var finalHoverBgColor        = false;
						var finalHoverBorderColor    = false;
						var network                  = $( this ).find( '.social-rocket-network-settings-wrapper' ).data( 'network' );
						var $networkColor            = $( this ).find( '#' + tmpScopePrefix + '_network_' + network + '\\[color\\]' );
						var $networkColorBg          = $( this ).find( '#' + tmpScopePrefix + '_network_' + network + '\\[color_bg\\]' );
						var $networkColorBorder      = $( this ).find( '#' + tmpScopePrefix + '_network_' + network + '\\[color_border\\]' );
						var $networkColorHover       = $( this ).find( '#' + tmpScopePrefix + '_network_' + network + '\\[color_hover\\]' );
						var $networkColorBgHover     = $( this ).find( '#' + tmpScopePrefix + '_network_' + network + '\\[color_bg_hover\\]' );
						var $networkColorBorderHover = $( this ).find( '#' + tmpScopePrefix + '_network_' + network + '\\[color_border_hover\\]' );
						var networkColorOverride     = $( this ).find( '#' + tmpScopePrefix + '_network_' + network + '\\[color_override\\]' ).prop( 'checked' );
						var networkColorValue        = networkColorOverride && $networkColor.val() > '' ? $networkColor.val() : $networkColor.attr( 'placeholder' );
						var networkColorBgValue      = networkColorOverride && $networkColorBg.val() > '' ? $networkColorBg.val() : $networkColorBg.attr( 'placeholder' );
						var networkColorBorderValue  = networkColorOverride && $networkColorBorder.val() > '' ? $networkColorBorder.val() : $networkColorBorder.attr( 'placeholder' );
						var networkColorHoverValue   = networkColorOverride && $networkColorHover.val() > '' ? $networkColorHover.val() : $networkColorHover.attr( 'placeholder' );
						var networkColorBgHoverValue = networkColorOverride && $networkColorBgHover.val() > '' ? $networkColorBgHover.val() : $networkColorBgHover.attr( 'placeholder' );
						var networkColorBorderHoverValue = networkColorOverride && $networkColorBorderHover.val() > '' ? $networkColorBorderHover.val() : $networkColorBorderHover.attr( 'placeholder' );
					
						// icon color
						if ( networkColorOverride ) {
							finalIconColor = networkColorValue; // network-specific setting always wins
						} else if ( colorScheme === 'custom' ) {
							if ( customIcon === 'custom' && customIconColor > '' ) {
								finalIconColor = customIconColor;
							} else if ( customIcon === 'network_icon' ) {
								finalIconColor = networkColorValue;
							} else if ( customIcon === 'network_background' ) {
								finalIconColor = networkColorBgValue;
							} else if ( customIcon === 'network_border' ) {
								finalIconColor = networkColorBorderValue;
							}
						} else if ( colorScheme === 'inverted' ) {
							finalIconColor = networkColorBgValue;
						} else if ( colorScheme === 'default' ) {
							finalIconColor = '#ffffff';
						}
						if ( finalIconColor ) {
							css += '.social-rocket-floating-preview ' + extraSelector + ' .social-rocket-' + network + ' .social-rocket-floating-button-icon,';
							css += '.social-rocket-floating-preview ' + extraSelector + ' .social-rocket-' + network + ' .social-rocket-floating-button-count {';
							css += 'color:' + finalIconColor + ';';
							css += '}';
							css += '.social-rocket-floating-preview ' + extraSelector + ' .social-rocket-floating-button.social-rocket-' + network + ' .social-rocket-floating-button-icon svg,';
							css += '.social-rocket-floating-preview ' + extraSelector + ' .social-rocket-floating-button.social-rocket-' + network + ' .social-rocket-floating-button-icon svg g,';
							css += '.social-rocket-floating-preview ' + extraSelector + ' .social-rocket-floating-button.social-rocket-' + network + ' .social-rocket-floating-button-icon svg path {';
							css += 'fill: ' + finalIconColor + ';';
							css += '}';
						}
					
						// background color
						if ( networkColorOverride ) {
							finalBackgroundColor = networkColorBgValue; // network-specific setting always wins
						} else if ( colorScheme === 'custom' ) {
							if ( customBackground === 'custom' ) {
								finalBackgroundColor = customBackgroundColor;
							} else if ( customBackground === 'none' ) {
								finalBackgroundColor = 'transparent';
							} else if ( customBackground === 'network_icon' ) {
								finalBackgroundColor = networkColorValue;
							} else if ( customBackground === 'network_background' ) {
								finalBackgroundColor = networkColorBgValue;
							} else if ( customBackground === 'network_border' ) {
								finalBackgroundColor = networkColorBorderValue;
							}
						} else if ( colorScheme === 'inverted' ) {
							finalBackgroundColor = 'transparent';
						}
						if ( finalBackgroundColor ) {
							css += '.social-rocket-floating-preview ' + extraSelector + ' .social-rocket-floating-button.social-rocket-' + network + '{';
							css += 'background-color:' + finalBackgroundColor + ';';
							css += '}';
						}
					
						// border color
						if ( networkColorOverride ) {
							finalBorderColor = networkColorBorderValue; // network-specific setting always wins
						} else if ( colorScheme === 'custom' ) {
							if ( customBorder === 'custom' ) {
								finalBorderColor = customBorderColor;
							} else if ( customBorder === 'none' ) {
								finalBorderColor = 'transparent';
							} else if ( customBorder === 'network_icon' ) {
								finalBorderColor = networkColorValue;
							} else if ( customBorder === 'network_background' ) {
								finalBorderColor = networkColorBgValue;
							} else if ( customBorder === 'network_border' ) {
								finalBorderColor = networkColorBorderValue;
							}
						} else if ( colorScheme === 'inverted' ) {
							finalBorderColor = 'transparent';
						}
						if ( finalBorderColor ) {
							css += '.social-rocket-floating-preview ' + extraSelector + ' .social-rocket-floating-button.social-rocket-' + network + '{';
							css += 'border-color:' + finalBorderColor + ';';
							css += '}';
						}
					
						// icon hover color
						if ( networkColorOverride ) {
							finalHoverColor = networkColorHoverValue; // network-specific setting always wins
						} else if ( colorScheme === 'custom' ) {
							if ( customHover === 'custom' ) {
								finalHoverColor = customHoverColor;
							} else if ( customHover === 'none' ) {
								finalHoverColor = false;
							} else if ( customHover === 'network_hover' ) {
								finalHoverColor = networkColorHoverValue;
							}
						} else if ( colorScheme === 'inverted' ) {
							finalHoverColor = false;
							var invertedHoverColor = networkColorBgHoverValue;
							css += '.social-rocket-floating-preview ' + extraSelector + ' .social-rocket-floating-button.social-rocket-' + network + ':hover .social-rocket-floating-button-icon,';
							css += '.social-rocket-floating-preview ' + extraSelector + ' .social-rocket-floating-button.social-rocket-' + network + ':hover .social-rocket-floating-button-cta,';
							css += '.social-rocket-floating-preview ' + extraSelector + ' .social-rocket-floating-button.social-rocket-' + network + ':hover .social-rocket-floating-button-count {';
							css += 'color: ' + invertedHoverColor + ';';
							css += '}';
							css += '.social-rocket-floating-preview ' + extraSelector + ' .social-rocket-floating-button.social-rocket-' + network + ':hover .social-rocket-floating-button-icon svg,';
							css += '.social-rocket-floating-preview ' + extraSelector + ' .social-rocket-floating-button.social-rocket-' + network + ':hover .social-rocket-floating-button-icon svg g,';
							css += '.social-rocket-floating-preview ' + extraSelector + ' .social-rocket-floating-button.social-rocket-' + network + ':hover .social-rocket-floating-button-icon svg path {';
							css += 'fill: ' + invertedHoverColor + ';';
							css += '}';
						}
						if ( finalHoverColor ) {
							css += '.social-rocket-floating-preview ' + extraSelector + ' .social-rocket-floating-button.social-rocket-' + network + ':hover .social-rocket-floating-button-icon,';
							css += '.social-rocket-floating-preview ' + extraSelector + ' .social-rocket-floating-button.social-rocket-' + network + ':hover .social-rocket-floating-button-cta,';
							css += '.social-rocket-floating-preview ' + extraSelector + ' .social-rocket-floating-button.social-rocket-' + network + ':hover .social-rocket-floating-button-count {';
							css += 'color: ' + finalHoverColor + ';';
							css += '}';
							css += '.social-rocket-floating-preview ' + extraSelector + ' .social-rocket-floating-button.social-rocket-' + network + ':hover .social-rocket-floating-button-icon svg,';
							css += '.social-rocket-floating-preview ' + extraSelector + ' .social-rocket-floating-button.social-rocket-' + network + ':hover .social-rocket-floating-button-icon svg g,';
							css += '.social-rocket-floating-preview ' + extraSelector + ' .social-rocket-floating-button.social-rocket-' + network + ':hover .social-rocket-floating-button-icon svg path {';
							css += 'fill: ' + finalHoverColor + ';';
							css += '}';
						}
						
						// background hover color
						if ( networkColorOverride ) {
							finalHoverBgColor = networkColorBgHoverValue; // network-specific setting always wins
						} else if ( colorScheme === 'custom' ) {
							if ( customHoverBg === 'custom' ) {
								finalHoverBgColor = customHoverBgColor;
							} else if ( customHoverBg === 'none' ) {
								finalHoverBgColor = false;
							} else if ( customHoverBg === 'network_hover_bg' ) {
								finalHoverBgColor = networkColorBgHoverValue;
							}
						} else if ( colorScheme === 'inverted' ) {
							finalHoverBgColor = false;
						}
						if ( finalHoverBgColor ) {
							css += '.social-rocket-floating-preview ' + extraSelector + ' .social-rocket-floating-button.social-rocket-' + network + ':hover{';
							css += 'background-color:' + finalHoverBgColor + ';';
							css += '}';
						}
						
						// border hover color
						if ( networkColorOverride ) {
							finalHoverBorderColor = networkColorBorderHoverValue; // network-specific setting always wins
						} else if ( colorScheme === 'custom' ) {
							if ( customHoverBorder === 'custom' ) {
								finalHoverBorderColor = customHoverBorderColor;
							} else if ( customHoverBorder === 'none' ) {
								finalHoverBorderColor = false;
							} else if ( customHoverBorder === 'network_hover_border' ) {
								finalHoverBorderColor = networkColorBorderHoverValue;
							}
						} else if ( colorScheme === 'inverted' ) {
							finalHoverBorderColor = false;
						}
						if ( finalHoverBorderColor ) {
							css += '.social-rocket-floating-preview ' + extraSelector + ' .social-rocket-floating-button.social-rocket-' + network + ':hover{';
							css += 'border-color:' + finalHoverBorderColor + ';';
							css += '}';
						}
					
						// wrap up
						var $existingCssBlock = $('#social-rocket-floating-preview-custom-css-' + network + '-' + scope );
						if ( $existingCssBlock.length ) {
							$existingCssBlock.html( css );
						} else {
							css = '<style id="social-rocket-floating-preview-custom-css-' + network + '-' + scope + '">' + css;
							css += '</style>';
							$( 'head' ).append( css );
						}
						
					});
					
				}
				
				// BUTTONS
				if ( updateButtons ) {
					
					// 1) button alignment (do this first because we reset style)
					var buttonAlignment = $( '#' + scopePrefix + '_button_alignment' ).val();
					if ( buttonAlignment === 'stretch' ) {
						$dest.find( '.social-rocket-floating-buttons' )
							.attr( 'style', 'display: -webkit-box; display: -moz-box; display: -ms-flexbox; display: -webkit-flex; display: flex; -webkit-box-orient: horizontal; -webkit-box-direction: normal; -ms-flex-direction: row; flex-direction: row; -ms-flex-wrap: wrap; flex-wrap: wrap; -webkit-box-pack: justify; -ms-flex-pack: justify; justify-content: space-between; -ms-flex-line-pack: stretch; align-content: stretch; -webkit-box-align: stretch; -ms-flex-align: stretch; align-items: stretch;' )
							.find('.social-rocket-floating-button, .social-rocket-floating-button-anchor')
							.attr( 'style', '-webkit-box-flex: 1; -ms-flex: 1; flex: 1;' );
						if ( scope === 'mobile' ) {
							$dest.find('.social-rocket-shares-total')
								.attr( 'style', '-webkit-box-flex: 1.4; -ms-flex: 1.4; flex: 1.4;' );
						} else {
							$dest.find('.social-rocket-shares-total')
								.attr( 'style', '-webkit-box-flex: 1; -ms-flex: 1; flex: 1;' );
						}
					} else {
						$dest.find( '.social-rocket-floating-buttons' ) // reset here
							.attr( 'style', '' )
							.css( 'text-align', buttonAlignment )
							.find('.social-rocket-floating-button, .social-rocket-floating-button-anchor, .social-rocket-shares-total')
							.attr( 'style', '' );
					}
					
					// 2) show/hide networks
					var activeNetworks = [];
					$source.find( '.social-rocket-settings-networks tr.active' ).each(function() {
						if ( $( this ).data( 'network' ) ) {
							activeNetworks.push( $( this ).data( 'network' ) );
						}
					});
					$dest.find( '.social-rocket-floating-button' ).each(function() {
						if ( $.inArray( $( this ).data( 'network' ), activeNetworks ) >= 0 ) {
							if ( $( this ).css( 'display' ) === 'none' ) {
								$( this ).css( 'display', '' );
							}
						} else {
							$( this ).hide();
						}
					});
					
					// 3) update networks order
					var $tmpSource;
					if ( scope === 'desktop' ) {
						$tmpSource = $previewDesktop;
					}
					if ( scope === 'mobile' ) {
						$tmpSource = $previewMobile;
					}
					var buttons = [];
					var order = $source.find( '.social-rocket-settings-networks-sortable' ).sortable( "toArray", { attribute: 'data-network' } );
					var totalPosition = $( '#' + scopePrefix + '_total_position' ).val();
					if ( totalPosition === 'before' ) {
						$tmpSource.find( '.social-rocket-shares-total' ).each(function() {
							buttons.push( $( this ).detach() );
						});
					}
					$.each( order, function() {
						buttons.push( $tmpSource.find( '.social-rocket-' + this ).detach() );
						if ( this === '_more' ) {
							var $div = $( '<div>', { 'class': 'social-rocket-more-buttons' } );
							buttons.push( $div );
						}
					});
					if ( totalPosition === 'after' ) {
						$tmpSource.find( '.social-rocket-shares-total' ).each(function() {
							buttons.push( $( this ).detach() );
						});
					}
					$dest.find( '.social-rocket-floating-buttons' ).empty().append( buttons );
					
					// 3b) more button
					if ( $source.find( '.social-rocket-settings-networks-sortable' ).find( 'tr[data-network="_more"]' ).hasClass( 'active' ) ) {
						var moreTriggered = false;
						var moreButtons = [];
						var moreVisible = 0;
						$.each( order, function() {
							if ( moreTriggered ) {
								if ( $tmpSource.find( '.social-rocket-' + this ).css( 'display' ) !== 'none' ) {
									moreVisible++;
								}
								moreButtons.push( $tmpSource.find( '.social-rocket-' + this ).detach() );
							}
							if ( this === '_more' ) {
								moreTriggered = true;
							}
						});
						if ( ! moreVisible ) {
							$dest.find( '.social-rocket-_more' ).hide();
						}
						$dest.find( '.social-rocket-more-buttons' ).empty().append( moreButtons );
					}
					
					// 4) button position
					var buttonPosition = $( 'input[name="' + scopePrefix + '_default_position"]:checked' ).val();
					if ( buttonPosition === 'none' ) {
						$dest.find( '#social-rocket-floating-buttons' )
							.removeClass( 'social-rocket-position-left social-rocket-position-right social-rocket-position-top social-rocket-position-bottom' )
							.addClass( 'social-rocket-position-top' )
							.css( 'visibility', 'hidden' );
					} else {
						$dest.find( '#social-rocket-floating-buttons' )
							.removeClass( 'social-rocket-position-left social-rocket-position-right social-rocket-position-top social-rocket-position-bottom' )
							.addClass( 'social-rocket-position-' + buttonPosition )
							.css( 'visibility', 'visible' );
					}
					if ( buttonPosition === 'top' ) {
						$dest.find( '.social-rocket-floating-preview-after' ).css( 'padding-bottom', '100px' );
						$dest.find( '.social-rocket-floating-preview-before' ).css( 'padding-top', '' );
					} else if ( buttonPosition === 'bottom' ) {
						$dest.find( '.social-rocket-floating-preview-before' ).css( 'padding-top', '100px' );
						$dest.find( '.social-rocket-floating-preview-after' ).css( 'padding-bottom', '' );
					} else {
						$dest.find( '.social-rocket-floating-preview-before' ).css( 'padding-top', '' );
						$dest.find( '.social-rocket-floating-preview-after' ).css( 'padding-bottom', '20px' );
					}
					$dest.find( '.social-rocket-floating-button' ).each(function() {
						if ( $( this ).css( 'display' ) !== 'none' ) {
							$( this ).css( 'display', '' );
						}
					});
					
					// 4b) button bar background color
					if ( buttonPosition === 'top' || buttonPosition === 'bottom' ) {
						$dest.find( '.social-rocket-floating-buttons')
							.css( 'background-color', $( '#' + scopePrefix + '_bar_background_color' ).val() )
							.css( 'padding', $( '#' + scopePrefix + '_bar_padding' ).val() );
					} else {
						$dest.find( '.social-rocket-floating-buttons')
							.css( 'background-color', '' )
							.css( 'padding', '' );
					}
					
					// 4c) total shares color
					$dest.find( '.social-rocket-shares-total' )
						.css( 'color', $( '#' + scopePrefix + '_total_color' ).val() );
					
					// 5) button size
					var buttonSize = $( '#' + scopePrefix + '_button_size' ).val();
					var buttonStyle = $( '#' + scopePrefix + '_button_style' ).val();
					var resized = false;
					if ( buttonSize > '' && buttonSize != 100 ) {
						resized = true;
						var defaultButtonHeight    = 50;
						var defaultButtonWidth     = 50;
						var defaultLineHeight      = $.inArray( buttonStyle, [ 'round', 'square' ] ) >= 0 ? 50 : 35;
						var defaultLineHeightHC    = 35;
						var defaultIconSize        = 16;
						var defaultCtaSize         = 13;
						var defaultCountSize       = $.inArray( buttonStyle, [ 'round', 'square' ] ) >= 0 ? 11 : 13;
						var defaultTotalCountSize  = $.inArray( buttonStyle, [ 'round', 'square' ] ) >= 0 ? 12 : 18;
						var defaultTotalHeight     = 14;
						var defaultTotalLabelSize  = 9;
						var defaultTotalLineHeight = 30;
						if ( $.inArray( buttonStyle, [ 'round', 'square' ] ) >= 0 ) {
							$dest.find( '.social-rocket-floating-button, .social-rocket-shares-total' )
								.css( 'width', defaultButtonWidth * ( buttonSize / 100 ) + 'px' )
								.css( 'height', defaultButtonHeight * ( buttonSize / 100 ) + 'px' );
						} else {
							$dest.find( '.social-rocket-floating-button, .social-rocket-shares-total' )
								.css( 'width', '' )
								.css( 'height', '' );
						}
						$dest.find( '.social-rocket-floating-button-anchor' )
							.css( 'line-height', defaultLineHeight * ( buttonSize / 100 ) + 'px' );
						$dest.find( '.social-rocket-floating-button-anchor.social-rocket-has-count' )
							.css( 'line-height', defaultLineHeightHC * ( buttonSize / 100 ) + 'px' );
						$dest.find( '.social-rocket-floating-button-icon' )
							.css( 'font-size', defaultIconSize * ( buttonSize / 100 ) + 'px' )
							.find( 'svg' )
							.css( 'width', 'auto' )
							.css( 'height', defaultIconSize * ( buttonSize / 100 ) + 'px' );
						$dest.find( '.social-rocket-floating-button-cta' )
							.css( 'font-size', defaultCtaSize * ( buttonSize / 100 ) + 'px' );
						$dest.find( '.social-rocket-floating-button-count' )
							.css( 'font-size', defaultCountSize * ( buttonSize / 100 ) + 'px' );
						$dest.find( '.social-rocket-shares-total' )
							.css( 'line-height', defaultTotalLineHeight * ( buttonSize / 100 ) + 'px' );
						$dest.find( '.social-rocket-shares-total' ).children( 'i' )
							.css( 'font-size', defaultIconSize * ( buttonSize / 100 ) + 'px' );
						$dest.find( '.social-rocket-shares-total-count' )
							.css( 'font-size', defaultTotalCountSize * ( buttonSize / 100 ) + 'px' )
							.css( 'line-height', defaultTotalHeight * ( buttonSize / 100 ) + 'px' );
						$dest.find( '.social-rocket-shares-total-label' )
							.css( 'font-size', defaultTotalLabelSize * ( buttonSize / 100 ) + 'px' )
							.css( 'line-height', defaultTotalHeight * ( buttonSize / 100 ) + 'px' );
						if ( $.inArray( buttonStyle, [ 'oval', 'rectangle' ] ) >= 0 ) {
							$dest.find( '.social-rocket-floating-button-count' )
								.css( 'margin-top', ( ( ( defaultLineHeight * ( buttonSize / 100 ) ) - 28 ) / 2 ) + 'px' );
							$dest.find( '.social-rocket-shares-total-inner' )
								.css( 'margin-top', ( ( defaultLineHeight * ( buttonSize / 100 ) ) * 0.16 ) + 'px' );
						} else {
							$dest.find( '.social-rocket-floating-button-count' )
								.css( 'margin-top', '' );
							$dest.find( '.social-rocket-shares-total-inner' )
								.css( 'margin-top', '' );
						}
					} else {
						if ( $.inArray( buttonStyle, [ 'round', 'square' ] ) >= 0 ) {
							$dest.find( '.social-rocket-floating-button, .social-rocket-shares-total' )
								.css( 'width', '50px' )
								.css( 'height', '50px' );
						} else {
							$dest.find( '.social-rocket-floating-button, .social-rocket-shares-total' )
								.css( 'width', '' )
								.css( 'height', '' );
						}
						$dest.find( '.social-rocket-floating-button-anchor' )
							.css( 'line-height', '' );
						$dest.find( '.social-rocket-floating-button-anchor.social-rocket-has-count' )
							.css( 'line-height', '' );
						$dest.find( '.social-rocket-floating-button-icon')
							.css( 'font-size', '' )
							.find( 'svg' )
							.css( 'width', '' )
							.css( 'height', '' );
						$dest.find( '.social-rocket-floating-button-cta' )
							.css( 'font-size', '' );
						$dest.find( '.social-rocket-floating-button-count' )
							.css( 'font-size', '' );
						$dest.find( '.social-rocket-shares-total' )
							.css( 'line-height', '' );
						$dest.find( '.social-rocket-shares-total' ).children( 'i' )
							.css( 'font-size', '' );
						$dest.find( '.social-rocket-shares-total-count' )
							.css( 'font-size', '' )
							.css( 'line-height', '' );
						$dest.find( '.social-rocket-shares-total-label' )
							.css( 'font-size', '' )
							.css( 'line-height', '' );						
						$dest.find( '.social-rocket-floating-button-count' )
							.css( 'margin-top', '' );
						$dest.find( '.social-rocket-shares-total-inner' )
							.css( 'margin-top', '' );
					}
					
					// 6) everything else button-related
					var borderStyle = $( '#' + scopePrefix + '_border' ).val();
					var borderWidth = $( '#' + scopePrefix + '_border_size' ).val();
					$dest.find( '.social-rocket-floating-button' )
						.removeClass( 'social-rocket-floating-button-oval social-rocket-floating-button-rectangle social-rocket-floating-button-round social-rocket-floating-button-square' )
						.addClass( 'social-rocket-floating-button-' + buttonStyle )
						.css( 'border-style', borderStyle )
						.css( 'border-width', borderWidth + 'px' )
						.css( 'border-radius', ( $.inArray( buttonStyle, [ 'rectangle', 'square' ] ) >= 0 ? $( '#' + scopePrefix + '_border_radius' ).val() + 'px' : '' ) );
					
					$dest.find( '.social-rocket-shares-total' )
						.removeClass( 'social-rocket-shares-total-oval social-rocket-shares-total-rectangle social-rocket-shares-total-round social-rocket-shares-total-square' )
						.addClass( 'social-rocket-shares-total-' + buttonStyle );
					
					if ( buttonPosition === 'top' || buttonPosition === 'bottom' ) {
						$dest.find( '.social-rocket-floating-button, .social-rocket-shares-total' )
							.css( 'margin-right', $( '#' + scopePrefix + '_margin_right' ).val() + 'px' )
							.css( 'margin-bottom', '0px' );
					} else {
						$dest.find( '.social-rocket-floating-button, .social-rocket-shares-total' )
							.css( 'margin-right', '0px' )
							.css( 'margin-bottom', $( '#' + scopePrefix + '_margin_bottom' ).val() + 'px' )
							.last()
							.css( 'margin-bottom', '0px' );
					}
					if ( borderStyle !== 'none' && borderWidth > 0 ) {
						$dest.find( '.social-rocket-shares-total' )
							.css( 'padding', borderWidth + 'px' );
					} else {
						$dest.find( '.social-rocket-shares-total' )
							.css( 'padding', '' );
					}
					if (
						$( '#' + scopePrefix + '_button_show_cta' ).prop( 'checked' ) == true &&
						$.inArray( buttonStyle, [ 'rectangle', 'oval' ] ) >= 0
					) {
						$dest.find( '.social-rocket-floating-button-cta' ).css( 'display', '' );
					} else {
						$dest.find( '.social-rocket-floating-button-cta' ).hide();
					}
					if (
						$( '#' + scopePrefix + '_button_show_cta' ).prop( 'checked' ) == false &&
						$.inArray( buttonStyle, [ 'rectangle', 'oval' ] ) >= 0
					) {
						$dest.find( '.social-rocket-floating-button, .social-rocket-shares-total' )
							.css( 'width', '95px' );
					} else {
						if ( ! resized ) {
							$dest.find( '.social-rocket-floating-button' )
								.css( 'width', '' );
						}
					}
					
					// 7) share count settings
					var showCounts = $( '#' + scopePrefix + '_show_counts' ).is( ':checked' )
					if ( showCounts ) {
						$dest.find( '.social-rocket-could-have-count' )
							.removeClass( 'social-rocket-could-have-count' )
							.addClass( 'social-rocket-has-count' );
						$dest.find( '.social-rocket-floating-button-count' ).css( 'display', '' );
						$dest.find( '.social-rocket-floating-button-anchor' ).css( 'text-align', '' );
					} else {
						$dest.find( '.social-rocket-has-count' )
							.removeClass( 'social-rocket-has-count' )
							.addClass( 'social-rocket-could-have-count' );
						$dest.find( '.social-rocket-floating-button-count' ).hide();
						if ( buttonPosition === 'top' || buttonPosition === 'bottom' ) {
							$dest.find( '.social-rocket-floating-button-anchor' ).css( 'text-align', 'center' );
						}
					}
					var showTotal = $( '#' + scopePrefix + '_show_total' ).is( ':checked' );
					if ( showTotal ) {
						$dest.find( '.social-rocket-shares-total' ).css( 'display', '' );
					} else {
						$dest.find( '.social-rocket-shares-total' ).css( 'display', 'none' );
					}
					var totalShowIcon = $( '#' + scopePrefix + '_total_show_icon' ).is(':checked');
					if ( totalShowIcon ) {
						$dest.find( '.social-rocket-shares-total' ).removeClass( 'no-total-icon' ).find( 'i' ).show();
					} else {
						$dest.find( '.social-rocket-shares-total' ).addClass( 'no-total-icon' ).find( 'i' ).hide();
					}
					
					// 8) network settings overrides
					// temporary solution:
					var tmpScope = 'social-rocket-floating';
					var tmpScopePrefix = scopePrefix;
					if ( scope === 'mobile' && mobileSetting !== 'default' ) {
						tmpScope = 'social-rocket-floating_mobile';
						tmpScopePrefix = 'social_rocket_floating_mobile';
					}
					var $tmpSource;
					if ( $( '#TB_window' ).find( '.social-rocket-network-settings-wrapper' ).length ) {
						$tmpSource = $( '#TB_ajaxContent' );
					} else {
						$tmpSource = $( '#social-rocket-settings-content' ).find( '.' + tmpScope + '-network-settings' );
					}
					$tmpSource.each(function() {
						var network = $( this ).find( '.social-rocket-network-settings-wrapper' ).data( 'network' );
						
						// CTA
						var $cta     = $( this ).find( '#' + tmpScopePrefix + '_network_' + network + '\\[cta\\]' );
						var ctaValue = $cta.val() > '' ? $cta.val() : $cta.attr( 'placeholder' );
						if ( typeof ctaValue !== "undefined" ) {
							$dest.find( '.social-rocket-' + network + ' .social-rocket-floating-button-cta' ).each(function() {
								$( this ).html( ctaValue );
							});
						}
						
						// Icon
						var $iconClass     = $( this ).find( '#' + tmpScopePrefix + '_network_' + network + '\\[icon_class\\]' );
						var iconClassValue = $iconClass.val();
						if ( iconClassValue	> '' ) {
							iconClassValue = iconClassValue.replace( '<i class="', '' ).replace( '"></i>', '' ); // filter for people who paste the entire html code, not just the css classes
							$iconClass.val( iconClassValue );
						} else {
							iconClassValue = $iconClass.attr( 'placeholder' );
						}
						if ( typeof iconClassValue !== "undefined" ) {
							$dest.find( '.social-rocket-' + network + ' .social-rocket-floating-button-icon i' ).each(function() {
								$( this ).removeClass().addClass( iconClassValue + ' social-rocket-floating-button-icon' );
							});
						}
						
						// Icon SVG
						var $iconSvg     = $( this ).find( '#' + tmpScopePrefix + '_network_' + network + '\\[icon_svg\\]' );
						var iconSvgValue = $iconSvg.val() > '' ? $iconSvg.val() : $iconSvg.attr( 'placeholder' );
						if ( typeof iconSvgValue !== "undefined" ) {
							$dest.find( '.social-rocket-' + network + ' .social-rocket-floating-button-icon i' ).each(function() {
								$( this ).html( iconSvgValue );
							});
						}
					});
				}
				
			});
			
			
			// execute hooks from external add-ons, if any
			$( socialRocketAdmin.hooks.floating_update_preview ).each( function( key, val ) {
				val();
			});
		
			// FOR DEBUGGING:
			// var end = Date.now()
			// var duration = end - start;
			// console.log( 'floating updatePreview() finished at: ' + end );
			// console.log( 'duration: ' + duration + 'ms' );
			// window.durationcount++;
			// window.durationtotal = window.durationtotal + duration;
			// console.log( 'average duration this session: ' + ( window.durationtotal / window.durationcount ) + 'ms' );
		
		};
		
		
		// function to update the "use same settings as desktop" div when networks are activated, removed, or sort order changed
		var syncNetworks = function() {
			
			var $desktop = $( '#social-rocket-settings-floating-buttons-desktop' );
			var $mobile  = $( '#social-rocket-settings-floating-buttons-mobile-default' );
			
			// show/hide networks
			var activeNetworks = [];
			$desktop.find( '.social-rocket-settings-networks tr.active' ).each(function(){
				if ( $( this ).data( 'network' ) ) {
					activeNetworks.push( $( this ).data( 'network' ) );
				}
			});
			$mobile.find( '.social-rocket-settings-networks tbody tr' ).each(function(){
				if ( $.inArray( $( this ).data( 'network' ), activeNetworks ) >= 0 ) {
					$( this ).addClass( 'active' ).show();
				} else {
					$( this ).removeClass( 'active' ).hide();
				}
			});
			if ( activeNetworks.length > 0 ) {
				$mobile.find( '.social-rocket-settings-networks' ).show();
				$mobile.find( '.social-rocket-settings-networks-empty' ).hide();
			} else {	
				$mobile.find( '.social-rocket-settings-networks' ).hide();
				$mobile.find( '.social-rocket-settings-networks-empty' ).show();
			}
			
			// update networks order
			var order = $desktop.find( '.social-rocket-settings-networks-sortable' ).sortable( "toArray", { attribute: 'data-network' } );
			var networks = [];
			$.each( order, function(){
				networks.push( $mobile.find('.social-rocket-settings-networks tr[data-network="'+this+'"]').detach() );
			});
			$.each( networks, function(){
				$mobile.find( '.social-rocket-settings-networks' ).append( this );
			});
			
		}
		
		
		// function to update a specific setting in the "use same settings as desktop" div on the mobile tab, when it changes on the desktop tab
		var syncSetting = function( e ) {
			
			var sourceName = e.target.name;
			var sourceId   = e.target.id;
			var sourceVal  = e.target.value;
			
			if ( e.type === "colorPickerChange" ) {
				
				var destId = sourceId.replace( 'social_rocket_floating_', 'social_rocket_floating_mobile_' );
				$( '#' + destId ).css( 'background-color', sourceVal );
				
			} else if ( e.target.type === 'radio' ) {
				
				if ( typeof sourceName !== "undefined" && sourceName ) {
					
					var destName = sourceName.replace( 'social_rocket_floating_', 'social_rocket_floating_mobile_' );
					$( 'input[name="' + destName + '"]' ).val( [ sourceVal ] );
					
				}
				
			} else if ( e.target.type === 'checkbox' ) {
				
				if ( typeof sourceName !== "undefined" && sourceName ) {
					
					var sourceVals = [];
					$( 'input[name="' + sourceName + '"]' ).filter( ':checked' ).each(function() {
						sourceVals.push( $( this ).val() );
					});
					
					var destName = sourceName.replace( 'social_rocket_floating_', 'social_rocket_floating_mobile_' );
					$( 'input[name="' + destName + '"]' ).val( [ sourceVals ] );
					
				}
			
			} else {
				
				if ( typeof sourceId !== "undefined" && sourceId ) {
					
					var destId = sourceId.replace( 'social_rocket_floating_', 'social_rocket_floating_mobile_' );
					$( '#' + destId ).val( sourceVal );
					
				}
				
			}
			
		};
		
		
		// change handler for desktop tab
		$( document ).on( 'change', '#social-rocket-settings-floating-buttons-desktop', function( e ) {
			
			// "Button color scheme" toggles stuff
			if ( e.target.id === 'social_rocket_floating_button_color_scheme' ) {
				if ( e.target.value === 'custom' ) {
					$( '.social_rocket_floating_button_color_scheme_custom_toggle' ).show();
					$( '.social_rocket_floating_button_color_scheme_custom_toggle' ).first().find('.social-rocket-collapsable-toggle').click();
				} else {
					$( '.social_rocket_floating_button_color_scheme_custom_toggle' ).hide()
					if ( $( '.social_rocket_floating_button_color_scheme_custom_colors' ).first().hasClass( 'social-rocket-collapsable-expanded' ) ) {
						$( '.social_rocket_floating_button_color_scheme_custom_toggle' ).first().find('.social-rocket-collapsable-toggle').click();
					}
				}
			}
			
			// "Default buttons placement" toggles stuff
			if ( e.target.name === 'social_rocket_floating_default_position' ) {
				if ( $.inArray( e.target.value, [ 'left', 'right' ] ) >= 0 ) {
					$( '.social_rocket_floating_default_position_left_right' ).show();
					$( '.social_rocket_floating_default_position_top_bottom' ).hide();
					if ( $( '#social_rocket_floating_button_alignment' ).val() === 'stretch' ) {
						$( '#social_rocket_floating_button_alignment' ).val( 'center' );
					}
				} else if ( $.inArray( e.target.value, [ 'top', 'bottom' ] ) >= 0 ) {
					$( '.social_rocket_floating_default_position_top_bottom' ).show();
					$( '.social_rocket_floating_default_position_left_right' ).hide();
				} else {
					$( '.social_rocket_floating_default_position_left_right' ).hide();
					$( '.social_rocket_floating_default_position_top_bottom' ).hide();
				}
			}
			
			// "Button style" toggles stuff
			if ( e.target.id === 'social_rocket_floating_button_style' ) {
				if ( $.inArray( e.target.value, [ 'rectangle', 'square' ] ) >= 0 ) {
					$( '.social_rocket_floating_border_radius_wrapper' ).show();
				} else {
					$( '.social_rocket_floating_border_radius_wrapper' ).hide();
				}
				if ( $.inArray( e.target.value, [ 'rectangle', 'oval' ] ) >= 0 ) {
					$( '.social_rocket_floating_button_show_cta_wrapper' ).show();
				} else {
					$( '.social_rocket_floating_button_show_cta_wrapper' ).hide();
				}
				if ( $.inArray( e.target.value, [ 'round', 'square' ] ) >= 0 ) {
					if ( $( '#social_rocket_floating_button_alignment' ).val() === 'stretch' ) {
						$( '#social_rocket_floating_button_alignment' ).val( 'center' );
					}
					$( '#social_rocket_floating_button_alignment' ).find( '[value="stretch"]' ).hide();
				} else {
					$( '#social_rocket_floating_button_alignment' ).find( '[value="stretch"]' ).show();
				}
			}
			
			// "Enable "More" button" toggles network
			if ( e.target.id === 'social_rocket_floating_more_enable' ) {
				$( '#social_rocket_floating_networks__more' ).click();
			}
			
			// "Show share count" toggles "Minimum shares"
			if ( e.target.id === 'social_rocket_floating_show_counts' ) {
				if ( e.target.checked == true ) {
					$( '.social_rocket_floating_show_counts_min' ).show();
				} else {
					$( '.social_rocket_floating_show_counts_min' ).hide();
				}
			}
			
			// "Show total share count" toggles stuff
			if ( e.target.id === 'social_rocket_floating_show_total' ) {
				if ( e.target.checked == true ) {
					$( '.social_rocket_floating_show_total' ).show();
				} else {
					$( '.social_rocket_floating_show_total' ).hide();
				}
			}
			
			// sync it to the "Use same settings as desktop" div (mobile tab)
			// (except networks, we handle that separately)
			if ( ! $( e.target ).closest( '.social-rocket-settings-networks-selector-wrapper' ).length ) {
				syncSetting( e );
			}
			
		});
		
		
		// change handler for mobile setting
		$( document ).on( 'change', 'input[name="social_rocket_floating_mobile_setting"]', function() {
			
			// toggle "Use same settings as desktop" div
			if ( $( '#social_rocket_floating_mobile_setting_default' ).is( ':checked' ) ) {
				$( '#social-rocket-settings-floating-buttons-mobile-default' ).show();
			} else {
				$( '#social-rocket-settings-floating-buttons-mobile-default' ).hide();
			}
			
		});
		
		
		// colorPickerChange handler for desktop tab
		$( document ).on( 'colorPickerChange', '#social-rocket-settings-floating-buttons-desktop', function( e ) {
			
			// if colorpicker is linked to a radio button or something
			if ( $( e.target ).data( 'for' ) > '' ) {
				var forId = '#' + $( e.target ).data( 'for' );
				var forName = $( forId ).attr( 'name' );
				var forValue = $( forId ).val();
				if ( forName > '' ) {
					$( 'input[name="' + forName + '"]' ).val( [ forValue ] );
				}
			}
			
			// sync it to the "Use same settings as desktop" div (mobile tab)
			syncSetting( e );
			
		});
		
		
		// sortupdate handler for desktop tab
		$( document ).on( 'sortupdate', '#social-rocket-settings-floating-buttons-desktop', function() {
			
			// sync it to the "Use same settings as desktop" div (mobile tab)
			syncNetworks();
			
		});
		
		
		// click handler
		$( document ).on( 'click', '.social-rocket_page_social_rocket_floating_buttons', function( e ) {
		
			// Desktop tab switches to desktop preview
			if ( $( e.target ).data( 'tab' ) === 'floating-buttons-desktop' ) {
				$( '#social_rocket_floating_preview_mobile' ).hide();
				$( '#social_rocket_floating_preview_desktop' ).show();
			}
			
			/*
			// Desktop preview toggle button
			if ( e.target.id === 'social_rocket_floating_preview_desktop_toggle' ) {
				e.preventDefault();
				if ( $('#social_rocket_floating_preview_desktop').is(':visible') ) {
					$('a[data-sr-toggle="social_rocket_floating_preview_inner"]').click();
				} else {
					$('#social_rocket_floating_preview_mobile').hide();
					$('#social_rocket_floating_preview_desktop').show();
					if ( ! $('#social_rocket_floating_preview_inner').is(':visible') ) {
						$('a[data-sr-toggle="social_rocket_floating_preview_inner"]').click();
					}
				}
			}
			*/
			
			// Mobile tab switches to mobile preview
			if ( $( e.target ).data( 'tab' ) === 'floating-buttons-mobile' ) {
				$( '#social_rocket_floating_preview_desktop' ).hide();
				$( '#social_rocket_floating_preview_mobile' ).show();
			}
			
			/*
			// Mobile preview toggle button
			if ( e.target.id === 'social_rocket_floating_preview_mobile_toggle' ) {
				e.preventDefault();
				if ( $('#social_rocket_floating_preview_mobile').is(':visible') ) {
					$('a[data-sr-toggle="social_rocket_floating_preview_inner"]').click();
				} else {
					$('#social_rocket_floating_preview_desktop').hide();
					$('#social_rocket_floating_preview_mobile').show();
					if ( ! $('#social_rocket_floating_preview_inner').is(':visible') ) {
						$('a[data-sr-toggle="social_rocket_floating_preview_inner"]').click();
					}
				}
			}
			*/
			
		});
		
		
		// handler for everything that triggers a preview update
		$( document ).on( 'change colorPickerChange keyup', '.social-rocket_page_social_rocket_floating_buttons', function( e ) {
			
			updatePreview( e );
			
		});
		
		
		// trigger the first update manually
		updatePreview();
		
		
		// make sure we're seeing the correct preview to start with
		var hash = location.hash.replace( '#', '' );
		if ( hash === 'floating-buttons-mobile' ) {
			$( '#social_rocket_floating_preview_desktop' ).hide();
			$( '#social_rocket_floating_preview_mobile' ).show();
		}
		
	});
	
	
	/*
	 * Settings page: click to tweet stuff
	 */
	$(document).ready(function(){
		
		if ( ! $( 'body' ).hasClass( 'social-rocket_page_social_rocket_click_to_tweet' ) ) {
			return; // nothing to do
		}
	
		// preview stuff
		var updatePreview = function() {
		
			var $preview = $('#social_rocket_tweet_preview');
			
			var $accentColor     = $( '#social_rocket_tweet_accent_color' );
			var $backgroundColor = $( '#social_rocket_tweet_background_color' );
			var $borderColor     = $( '#social_rocket_tweet_border_color' );
			var $ctaColor        = $( '#social_rocket_tweet_cta_color' );
			var $textColor       = $( '#social_rocket_tweet_text_color' );
			
			var accentColorValue     = $accentColor.val() > ''     ? $accentColor.val()     : $accentColor.attr( 'placeholder' );
			var backgroundColorValue = $backgroundColor.val() > '' ? $backgroundColor.val() : $backgroundColor.attr( 'placeholder' );
			var borderColorValue     = $borderColor.val() > ''     ? $borderColor.val()     : $borderColor.attr( 'placeholder' );
			var ctaColorValue        = $ctaColor.val() > ''        ? $ctaColor.val()        : $ctaColor.attr( 'placeholder' );
			var textColorValue       = $textColor.val() > ''       ? $textColor.val()       : $textColor.attr( 'placeholder' );
			
			$preview.find('.social-rocket-tweet')
				.css( 'background-color', backgroundColorValue )
				.css( 'color', textColorValue )
				.css( 'font-size', $('#social_rocket_tweet_text_size').val() + 'px' )
				.css( 'border-style', $('#social_rocket_tweet_border').val() )
				.css( 'border-width', $('#social_rocket_tweet_border_size').val() + 'px' )
				.css( 'border-color', borderColorValue )
				.css( 'border-radius', $('#social_rocket_tweet_border_radius').val() + 'px' );
			
			$preview.find('.social-rocket-tweet a')
				.css( 'border-left', '10px solid ' + accentColorValue );
			
			$preview.find('.social-rocket-tweet-cta')
				.css( 'color', ctaColorValue )
				.css( 'text-align', $('#social_rocket_tweet_cta_position').val() )
				.html( function() { return $(this).html().replace( $(this).text(), $('#social_rocket_tweet_cta_text').val() ); } );
			
		}
		
		$(document).on('keyup change colorPickerChange','#social-rocket-settings-tweet input, #social-rocket-settings-tweet select',function(){
			updatePreview();
		});
		
		updatePreview();
		
		$('.social-rocket-tweet').on('click',function(e){
			e.preventDefault();
			var href = $( this ).find('a').attr( 'href' );
			var height = 270;
			var width = 500;
			var top = window.screenY + (window.innerHeight - height) / 2;
			var left = window.screenX + (window.innerWidth - width) / 2;
			var windowFeatures = 'height=' + height + ',width=' + width + ',top=' + top + ',left=' + left;
			var instance = window.open( href, '_blank', windowFeatures );	
			return false;
		});
	
		// saved styles
		// get current settings
		var $tweet_settings = $('#social-rocket-settings-tweet-default-settings input, #social-rocket-settings-tweet-default-settings select')
			.not('[type="button"]')
			.not('[type="hidden"]');
		
		// "Save" button
		$(document).on( 'click','#social-rocket-settings-tweet-saved-settings-update', function(){
			if ( confirm( socialRocketAdmin.i18n.confirm_tweet_settings_update ) ) {
				var $button = $(this);
				var id = $(this).data('id');
				var name = $(this).data('name');
				$button.prop( 'disabled', true );
				var postData = {
					id: id,
					data: {
						'name': name
					}
				};
				var key;
				var value;
				$tweet_settings.each(function(){
					key = $(this).attr('id') || $(this).attr('name');
					if ( $(this).attr('type') === "radio" && $(this).prop( 'checked' ) ) {
						value = $(this).val();
					} else if ( $(this).attr('type') === "checkbox" ) {
						value = $(this).prop( 'checked' ) ? 1 : 0;
					} else {
						value = $(this).val();
					}
					if ( value === '' && $(this).hasClass('social-rocket-color-picker') ) {
						value = $(this).attr( 'placeholder' );
					}
					postData.data[key] = value;
				});
				$.ajax( ajaxurl + '?action=social_rocket_tweet_settings_update', {
					type: 'POST',
					data: postData,
					dataType: 'json',
					success: function(response) {
						$button.prop( 'disabled', false ).removeClass( 'button-primary' );
						socialRocketAdmin.formIsDirty = false;
					},
					error: function ( jqXHR ) {
						alert( jqXHR.responseText );
					}
				});
			}
		});
		
		// "Save As New Style" button
		$(document).on( 'click', '#social-rocket-settings-tweet-saved-settings-save', function(){
			var $button = $(this)
			$button.prop('disabled', true);
			var name = $('#social-rocket-settings-tweet-saved-settings-name').val();
			var postData = {
				data: {
					'name': name
				}
			};
			var key;
			var value;
			$tweet_settings.each(function(){
				key = $(this).attr('id') || $(this).attr('name');
				if ( $(this).attr('type') === "radio" && $(this).prop( 'checked' ) ) {
					value = $(this).val();
				} else if ( $(this).attr('type') === "checkbox" ) {
					value = $(this).prop( 'checked' ) ? 1 : 0;
				} else {
					value = $(this).val();
				}
				if ( value === '' && $(this).hasClass('social-rocket-color-picker') ) {
					value = $(this).attr( 'placeholder' );
				}
				postData.data[key] = value;
			});
			
			$.ajax( ajaxurl + '?action=social_rocket_tweet_settings_save', {
				type: 'POST',
				data: postData,
				dataType: 'json',
				success: function(response) {
					var displayName = ( name > '' ? name : '(' + socialRocketAdmin.i18n.settings_tweet_no_name + ')' );
					var $table = $('#social-rocket-settings-tweet-saved-settings');
					$table.find('tbody').append( 
						'<tr><td><span class="social_rocket_tweet_saved_settings_name" data-id="' + response.id + '">' + displayName + '</span></td>' +
						'<td><span class="description">' + response.id + '</span></td>' +
						'<td><button type="button" class="social-rocket-settings-tweet-saved-settings-load button button-small" data-id="'+response.id+'">'+socialRocketAdmin.i18n.settings_tweet_load+'</button> <button type="button" class="social-rocket-settings-tweet-saved-settings-delete button button-small" data-id="'+response.id+'">'+socialRocketAdmin.i18n.settings_tweet_delete+'</button></td></tr>'
					);
					$button.removeClass( 'button-primary' );
					$( '#social-rocket-settings-tweet-saved-settings-name' ).val( '' );
					$( '#social-rocket-settings-tweet-saved-settings-current-style' ).html( displayName );
					$( '#social-rocket-settings-tweet-saved-settings-id' ).html( response.id );
					$( '#social-rocket-settings-tweet-saved-settings-update' )
						.data( 'id', response.id )
						.data( 'name', name )
						.prop( 'disabled', false )
						.removeClass( 'button-primary' );
					socialRocketAdmin.formIsDirty = false;
					$('#TB_closeWindowButton').click();
				},
				error: function ( jqXHR ) {
					alert( jqXHR.responseText );
				}
			});
		});
		
		// "Save As New Style" input
		$( document ).on( 'change keyup', '#social-rocket-settings-tweet-saved-settings-name', function() {
			var $button = $( '#social-rocket-settings-tweet-saved-settings-save' );
			var value = $(this).val();
			if ( value.length > 0 ) {
				$button.prop( 'disabled', false ).addClass( 'button-primary' );
			} else {
				$button.prop( 'disabled', true ).removeClass( 'button-primary' );
			}
		});
		
		// "Load" button
		$(document).on('click','.social-rocket-settings-tweet-saved-settings-load',function(){
			var $row = $(this).closest('tr');
			var id = $(this).data('id');
			$row.find('button').prop('disabled', true);
			$.ajax( ajaxurl + '?action=social_rocket_tweet_settings_load&id='+id, {
				dataType: 'json',
				success: function(response) {
					socialRocketAdmin.tweetStyleIsLoading = true;
					$tweet_settings.each(function(){
						var setting = $(this).attr('id') || $(this).attr('name');
						var key = setting.replace( 'social_rocket_tweet_', '' );
						if ( typeof response.data[key] !== "undefined" ) {
							if ( $(this).attr('type') === "radio" ) {
								$( 'input[name="'+setting+'"][value="'+response.data[key]+'"]' ).prop( 'checked', true ).change();
							} else if ( $(this).attr('type') === "checkbox" ) {
								$( 'input[name="'+setting+'"][type="checkbox"]' ).prop( 'checked', response.data[key] == "1" ? true : false ).change();
							} else {
								$(this).val( response.data[key] ).change();
							}
						}
					});
					$row.find('button').prop('disabled', false);
					$( '#social-rocket-settings-tweet-saved-settings-current-style' ).html( response.data.name );
					$( '#social-rocket-settings-tweet-saved-settings-id' ).html( response.id );
					$( '#social-rocket-settings-tweet-saved-settings-update' )
						.data( 'id', response.id )
						.data( 'name', response.data.name )
						.prop( 'disabled', false )
						.removeClass( 'button-primary' );
					alert( socialRocketAdmin.i18n.tweet_settings_loaded );
					setTimeout( function(){ socialRocketAdmin.tweetStyleIsLoading = false; }, 100 );
					socialRocketAdmin.formIsDirty = false;
				},
				error: function ( jqXHR ) {
					alert( jqXHR.responseText );
				}
			});
		});
		
		// "Delete" button
		$(document).on('click','.social-rocket-settings-tweet-saved-settings-delete',function(){
			if ( confirm( socialRocketAdmin.i18n.confirm ) ) {
				var $row = $(this).closest('tr');
				var postData = {
					id: $(this).data('id')
				};
				$row.find('button').prop('disabled', true);
				$.ajax( ajaxurl + '?action=social_rocket_tweet_settings_delete', {
					type: 'POST',
					data: postData,
					dataType: 'json',
					success: function(response) {
						$row.remove();
					},
					error: function ( jqXHR ) {
						alert( jqXHR.responseText );
					}
				});
			}
		});
		
		$('#social-rocket-settings-tweet-saved-settings-name').bind("keypress", function(e) {
            if (e.keyCode == 13) {
				e.preventDefault();
				$( '#social-rocket-settings-tweet-saved-settings-save' ).click();
                return false;
            }
        });
		
	});
	
	
	/*
	 * Settings page: save button
	 */
	$(document).on('change colorPickerChange','#social-rocket-settings, .social-rocket-network-settings-wrapper',function(e){
		if ( $(e.target).hasClass('social-rocket-excluded-input') ) {
			return;
		}
		if ( ! socialRocketAdmin.formIsDirty ) {
			
			$('input[name="social_rocket_save"]').addClass('button-primary');
			socialRocketAdmin.formIsDirty = true;
			
			// click to tweet page only
			if ( $( 'body' ).hasClass( 'social-rocket_page_social_rocket_click_to_tweet' ) ) {
				var $cttSaveButton = $( '#social-rocket-settings-tweet-saved-settings-update' );
				if ( ! socialRocketAdmin.tweetStyleIsLoading ) {
					$cttSaveButton.addClass('button-primary');
				}
			}
		}
	});
	$(document).on('submit','#social-rocket-settings',function(){
		socialRocketAdmin.formSubmitting = true;
	});
	window.addEventListener("beforeunload", function (e) {
		if ( socialRocketAdmin.formSubmitting || ! socialRocketAdmin.formIsDirty ) {
			return undefined;
		}
		var confirmationMessage = socialRocketAdmin.i18n.confirm_unsaved;
		(e || window.event).returnValue = confirmationMessage; //Gecko + IE
		return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
	});
	
	
	/*
	 * Settings pages -- everything else
	 */
	
	// settings import/backup restore
	$('#social_rocket_settings_import_file, #social_rocket_settings_restore_file').on('change',function(){
		var file = $(this).val();
		if ( file.length && file.toLowerCase().substr( file.length - 5 ) === '.json') {
			$(this).parent().find('input[type="submit"]').removeClass().addClass('social-rocket-excluded-input button-primary');
		} else {
			$(this).parent().find('input[type="submit"]').removeClass().addClass('social-rocket-excluded-input button-secondary');
		}
	});
	
	// click handler
	$( document ).on( 'click', '#social-rocket-settings', function( e ) {
		
		// "Recalculate all counts" button
		if ( e.target.id === 'social-rocket-settings-recalc-all' ) {
			if ( confirm( socialRocketAdmin.i18n.confirm ) ) {
				var $this = $(this);
				var postData = {
					nonce: $('#social-rocket-settings-recalc-nonce').val()
				};
				$.ajax( ajaxurl + '?action=social_rocket_recalc_all', {
					type: 'POST',
					data: postData,
					dataType: 'json',
					success: function(response) {
						$this.removeClass( 'button-primary' ).addClass( 'button-secondary' ).text( socialRocketAdmin.i18n.recalc_requested ).prop( 'disabled', true );
					},
					error: function ( jqXHR ) {
						alert( jqXHR.responseText );
					}
				});
			}
		}
		
		// "Reset all Social Rocket settings" button
		if ( e.target.id === 'social_rocket_reset_settings' ) {
			return confirm( socialRocketAdmin.i18n.confirm );
		}
		
		// "Get System Info" button
		if ( e.target.id === 'social_rocket_show_system_info' ) {
			var $systemInfo = $( '#social_rocket_system_info' );
			if ( $systemInfo.is( ':visible' ) ) {
				$systemInfo.slideUp();
			} else {
				$systemInfo.slideDown();
				$systemInfo.find( 'textarea' ).focus().select();
			}
		}
		
	});
	
	
	/*
	 * Image Edit modal -- Add Pinterest fields
	 */
	var templateImageDetails = $('#tmpl-image-details');
	
	if (
		templateImageDetails.length > 0 &&
		typeof window.pagenow !== 'undefined' &&
		( window.pagenow !== 'widgets' && window.pagenow !== 'customize' ) 
	) {
		
		var html = templateImageDetails.html();
		
		var htmlToAdd = '';
		htmlToAdd += '<div class="social-rocket-section">';
		htmlToAdd += '<h2>' + socialRocketAdmin.i18n.media_section_header + '</h2>';
		htmlToAdd += '<label class="setting pin-description"><span>' + socialRocketAdmin.i18n.media_pin_description + '</span><textarea data-setting="social_rocket_pinterest_description">{{ data.attachment.social_rocket_pinterest_description }}</textarea></label>';
		htmlToAdd += '<label class="setting pin-title"><span>' + socialRocketAdmin.i18n.media_pin_title + '</span><input type="text" disabled="disabled" /></label>';
		htmlToAdd += '<label class="setting pin-id"><span>' + socialRocketAdmin.i18n.media_pin_id + '</span><input type="text" disabled="disabled" /></label>';
		htmlToAdd += '<label class="setting pin-nopin"><span>' + socialRocketAdmin.i18n.media_pin_nopin + '</span><input type="checkbox" checked="checked" disabled="disabled" /></label>';
		htmlToAdd += '<div class="social-rocket-section-addons"></div>';
		htmlToAdd += '</div>';
		
		if ( templateImageDetails.text().indexOf( '<div class="advanced-section">' ) !== -1 ) {
			templateImageDetails.text( html.replace( /(<div class="advanced-section">)/, htmlToAdd + '$1' ) );
		}
		
	}
	
	if( typeof wp.media != 'undefined' && typeof wp.media.events != 'undefined' ) {

		// If image has an existing data-pin-description, use it
		wp.media.events.on( 'editor:image-edit', function(e) {
			
			var existingPinDescription = e.editor.$(e.image).attr( 'data-pin-description' );
			
			if ( typeof existingPinDescription !== "undefined" ) {
				e.metadata.social_rocket_pinterest_description = existingPinDescription;
			}

		});

		// If pin description has been updated here (separate from the attachment-based pin description), save it
		wp.media.events.on( 'editor:image-update', function(e) {
			
			var updatedPinDescription = e.metadata.social_rocket_pinterest_description;
			
			e.editor.$(e.image).attr( 'data-pin-description', updatedPinDescription );
			
		});

	}
	
	
	/*
	 * Post metaboxes: characters remaining counters
	 */
	$(document).ready(function(){
		$('.social-rocket-characters-remaining').each(function(i){
			var $counterDiv = $(this);
			var textarea = $(this).data('for-textarea');
			$('#'+textarea).on('keydown keyup change',{target:$counterDiv},function(event){
				var $counterDiv = event.data.target;
				var chars = $(this).val().length; 
				var maxChars = parseInt( $counterDiv.data('max-chars') );
				var $count = $counterDiv.find('.chars');
				$count.html( ( maxChars - chars ) + '/' + maxChars );
				if ( $count.hasClass('negative') ) {
					if ( maxChars - chars >= 0 ) {
						$count.removeClass('negative');
					}
				} else {
					if ( maxChars - chars < 0 ) {
						$count.addClass('negative');
					}
				}
			});
		});
	});
	
	
	/*
	 * Post metaboxes: image uploaders
	 */
	$(document).ready(function() {
		
		var $container;
		
		$( document ).on( 'click', '.social-rocket-image-upload-button', function(event) {
			event.preventDefault();
			$container = $(this).parents('.social-rocket-image-uploader');
			var frame;
			if (frame) {
				frame.open();
				return;
			}
			frame = wp.media();
			frame.on( "select", function() {
				var attachment = frame.state().get("selection").first();
				frame.close();
				$container.addClass('has-image').find('.social-rocket-image-uploader-image > img').attr("src", attachment.attributes.url);
				$container.find('.social-rocket-image-uploader-image-edit').attr("href", attachment.attributes.editLink);
				$container.find('.social-rocket-image-upload-id').val(attachment.attributes.id);
			});
			frame.open();
		});
		
		$( document ).on( 'click', '.social-rocket-image-uploader-image-remove', function(event) {
			event.preventDefault();
			$container = $(this).parents('.social-rocket-image-uploader');
			$container.removeClass('has-image').find('.social-rocket-image-uploader-image > img').attr("src", socialRocketAdmin.social_image_placeholder);
			$container.find('.social-rocket-image-uploader-image-edit').attr("href", '#');
			$container.find('.social-rocket-image-upload-id').val('');
			return false;
		});
		
	});
	
	
	/*
	 * Taxonomy metaboxes
	 */
	$(document).on('click','.social-rocket-taxonomy-metabox .handlediv, .social-rocket-taxonomy-metabox .hndle',function(){
		var $el = $( this ),
			p = $el.parent( '.postbox' ),
			id = p.attr( 'id' ),
			ariaExpandedValue;

		p.toggleClass( 'closed' );

		ariaExpandedValue = ! p.hasClass( 'closed' );

		if ( $el.hasClass( 'handlediv' ) ) {
			// The handle button was clicked.
			$el.attr( 'aria-expanded', ariaExpandedValue );
		} else {
			// The handle heading was clicked.
			$el.closest( '.postbox' ).find( 'button.handlediv' )
				.attr( 'aria-expanded', ariaExpandedValue );
		}
	});
	
	
})( jQuery );
