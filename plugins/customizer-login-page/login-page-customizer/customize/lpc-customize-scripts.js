(function ($) {
	// Function to find element in customizer preview.  
	function lpc_find(finder = '#lpc-customize') {

		var customizer_finder = $('#customize-preview iframe').contents().find(finder);
		return customizer_finder;
	}
	// function for change lpc_element_attr_property().
	function lpc_element_attr_property(hook, selector, property) {
		wp.customize(hook, function (value) {
			value.bind(function (lpcVal) {

				if (lpcVal == '') {
					lpc_find(selector).attr(property, '');
				} else {
					lpc_find(selector).attr(property, lpcVal);
				}
			});
		});
	}
	// end lpc_element_attr_property().

	// function for change CSS lpc_css_change().
	function lpc_css_change(hook, selector, property, suffix) {
		// Update the login logo width in real time...
		wp.customize(hook, function (value) {
			value.bind(function (lpcVal) {

				if (lpcVal == '') {
					lpc_find(selector).css(property, '');
				} else {
					lpc_find(selector).css(property, lpcVal + suffix);
				}
			});
		});
	} // finish lpc_css_change();

	// Function for changing CSS with two individual hooks
	function lpc_css_change_double(hook1, hook2, selector, property, suffix) {
		// Update the CSS property in real time for both hooks
		wp.customize(hook1, function (value1) {
			value1.bind(function (lpcVal1) {
				var value1 = lpcVal1 ? lpcVal1 + suffix : '';
				var value2 = wp.customize.instance(hook2).get() ? wp.customize.instance(hook2).get() + suffix : '';
				var combinedValue = value1 + ' ' + value2;
				lpc_find(selector).css(property, combinedValue);
			});
		});

		wp.customize(hook2, function (value2) {
			value2.bind(function (lpcVal2) {
				var value1 = wp.customize.instance(hook1).get() ? wp.customize.instance(hook1).get() + suffix : '';
				var value2 = lpcVal2 ? lpcVal2 + suffix : '';
				var combinedValue = value1 + ' ' + value2;
				lpc_find(selector).css(property, combinedValue);
			});
		});
	}
	// finish lpc_css_change_double().

	// Function to update the Background gradient CSS
	function lpc_updateGradient(lpc_gcolor1, gcol1percent, lpc_gcolor2, gcol2percent, lpc_gangle) {
		let lpc_gradientCSS = 'linear-gradient(' + lpc_gangle + 'deg, ' + lpc_gcolor1 + ' ' + gcol1percent + '%, ' + lpc_gcolor2 + ' ' + gcol2percent + '%)';
		// Apply the CSS to the desired element, in this case, I'm applying it to the body just for demonstration
		lpc_find('body.login').css('background', lpc_gradientCSS);
	}
	// end lpc_updateGradient().

	$(window).on('load', function () {
		// Show/Hide Logo Related Controls.{ On Load Only }
		if ($('#customize-control-lpc_opts-lpc-logo-enable-control input[type="checkbox"]').is(":checked")) {
			$('#customize-control-lpc_opts-lpc-logo-enable-control').nextAll().show();
		} else {
			$('#customize-control-lpc_opts-lpc-logo-enable-control').nextAll().hide();
		}
		// Show/Hide BG image Controls.{ On Load Only }
		if ($('#customize-control-lpc_opts-lpc-bg-img-enable-control input[type="checkbox"]').is(":checked")) {
			$('#customize-control-lpc_opts-lpc-bg-img-enable-control').nextUntil('#customize-control-lpc-background-video-heading-control').show();
		} else {
			$('#customize-control-lpc_opts-lpc-bg-img-enable-control').nextUntil('#customize-control-lpc-background-video-heading-control').hide();
		}
		// Check if the lpc-bg-video-enable is enabled or disabled.
		if ($('#customize-control-lpc_opts-lpc-bg-video-enable-control input[type="checkbox"]').is(":checked")) {
			$('#customize-control-lpc_opts-lpc-bg-video-enable-control').nextAll().show();
		} else {
			$('#customize-control-lpc_opts-lpc-bg-video-enable-control').nextAll().hide();
		}
		// Hide Background Gradient Controls.
		if ($('input[name="lpc_opts[lpc-background-color-choice]-control"]:checked').val() === 'solid') {
			$('#customize-control-lpc_opts-lpc-background-color-control').show();
			$('#customize-control-lpc_opts-lpc-background-gcolor1-control').hide();
			$('#customize-control-lpc_opts-lpc-background-gcol1percent-control').hide();
			$('#customize-control-lpc_opts-lpc-background-gcolor2-control').hide();
			$('#customize-control-lpc_opts-lpc-background-gcol2percent-control').hide();
			$('#customize-control-lpc_opts-lpc-background-gangle-control').hide();
		} else if ($('input[name="lpc_opts[lpc-background-color-choice]-control"]:checked').val() === 'gradient') {
			$('#customize-control-lpc_opts-lpc-background-color-control').hide();
			$('#customize-control-lpc_opts-lpc-background-gcolor1-control').show();
			$('#customize-control-lpc_opts-lpc-background-gcol1percent-control').show();
			$('#customize-control-lpc_opts-lpc-background-gcolor2-control').show();
			$('#customize-control-lpc_opts-lpc-background-gcol2percent-control').show();
			$('#customize-control-lpc_opts-lpc-background-gangle-control').show();
		}
		// var lpcbgvideoenable = wp.customize.control('lpc-bg-video-enable');
		// 	console.log(lpcbgvideoenable);
		// if ( lpcbgvideoenable && lpcbgvideoenable.value() == 1 ) {
		// 	//console.log('Toggle control is enabled');
		// 	$('#customize-control-lpc-bg-video-enable-control').nextAll().show();
		// 	console.log( "Video Controls Displaying" );
		// } else if ( lpcbgvideoenable && lpcbgvideoenable.value() == 0 ) {
		// 	$('#customize-control-lpc-bg-video-enable-control').nextAll().hide();
		// 	console.log( "Video Controls Removed" );
		// }
		// For Page Title Setting.
		// Get the image URL from the localized script
		//var title_ctrl_image_url = lpc_script.title_ctrl_image_url;
		// Title Prepend the image tag to the control's title
		//$('#customize-control-lpc-title-text-control .customize-control-title').prepend('<img src="' + title_ctrl_image_url + '" alt="Logo Image" style="max-width: 320px; height: auto;">');
	});

	// Update the Login Page login logo Realtime.
	wp.customize('lpc_opts[lpc-logo-image]', function (value) {
		value.bind(function (lpcVal) {

			if (lpcVal == '') {
				lpc_find('#login h1 a').css('background-image', 'url(' + lpc_script.admin_url + 'images/wordpress-logo.svg)');
			} else {
				lpc_find('#login h1 a').css('background-image', 'url(' + lpcVal + ')');
			}
		});
	});


	// Enable / Disabe Login Page login logo in real time.
	wp.customize('lpc_opts[lpc-logo-enable]', function (value) {
		value.bind(function (lpcVal) {
			if (lpcVal == false) {
				lpc_find('#login h1 a').fadeOut();
				$('#customize-control-lpc_opts-lpc-logo-enable-control').nextAll().hide();
				$('#customize-control-lpc_page_title').show();
			} else {
				lpc_find('#login h1 a').fadeIn().css('display', 'block');
				$('#customize-control-lpc_opts-lpc-logo-enable-control').nextAll().show();
			}
		});
	});
	// Background Color choice settings display Realtime.
	wp.customize('lpc_opts[lpc-background-color-choice]', function (value) {
		value.bind(function (lpcVal) {
			if (lpcVal == 'solid') {
				$('#customize-control-lpc_opts-lpc-background-color-control').show();
				$('#customize-control-lpc_opts-lpc-background-gcolor1-control').hide();
				$('#customize-control-lpc_opts-lpc-background-gcol1percent-control').hide();
				$('#customize-control-lpc_opts-lpc-background-gcolor2-control').hide();
				$('#customize-control-lpc_opts-lpc-background-gcol2percent-control').hide();
				$('#customize-control-lpc_opts-lpc-background-gangle-control').hide();
				let lpc_bglive = wp.customize('lpc_opts[lpc-background-color]').get();
				lpc_find('body.login').css('background', lpc_bglive);
			} else if (lpcVal == 'gradient') {
				$('#customize-control-lpc_opts-lpc-background-color-control').hide();
				$('#customize-control-lpc_opts-lpc-background-gcolor1-control').show();
				$('#customize-control-lpc_opts-lpc-background-gcol1percent-control').show();
				$('#customize-control-lpc_opts-lpc-background-gcolor2-control').show();
				$('#customize-control-lpc_opts-lpc-background-gcol2percent-control').show();
				$('#customize-control-lpc_opts-lpc-background-gangle-control').show();
				let lpc_bglive = wp.customize('lpc_opts[lpc-background-color]').get();
				lpc_find('body.login').css('background', lpc_bglive);
			}
		});
	});

	// Enable / Disable Background Image in real time.
	wp.customize('lpc_opts[lpc-bg-img-enable]', function (value) {
		value.bind(function (lpcVal) {
			if (lpcVal == 1) {
				$('#customize-control-lpc_opts-lpc-bg-img-enable-control').nextUntil('#customize-control-lpc-background-video-heading-control').show();
				//lpc_find('body.login').css('background-image', 'url(' + lpc_script.lpc_bg_img_url + ')');
				var setlpcbgimageControl = wp.customize.control('lpc_opts[lpc-background-image]-control');
				//console.log(setlpcbgimageControl);
				if (setlpcbgimageControl) {
					setlpcbgimageControl.setting.set(lpc_script.lpc_bg_img_url);
				}
			} else if (lpcVal == 0) {
				$('#customize-control-lpc_opts-lpc-bg-img-enable-control').nextUntil('#customize-control-lpc-background-video-heading-control').hide();
				lpc_find('body.login').css('background-image', 'none');
				// Remove Image from Image Control.
				var lpcbgimageControl = wp.customize.control('lpc_opts[lpc-background-image]-control');
				if (lpcbgimageControl && lpcbgimageControl.setting.get() !== '') {
					lpcbgimageControl.setting.set('');
					//console.log('Image removed');
				}
				// set background color
				var getlpcbgcolor = wp.customize('lpc_opts[lpc-background-color-choice]').get();
				if (getlpcbgcolor == 'solid') {
					$('#customize-control-lpc_opts-lpc-background-color-control').show();
					$('#customize-control-lpc_opts-lpc-background-gcolor1-control').hide();
					$('#customize-control-lpc_opts-lpc-background-gcolor2-control').hide();
					$('#customize-control-lpc_opts-lpc-background-gangle-control').hide();
					let lpc_bgsaved = wp.customize('lpc_opts[lpc-background-color]').get();
					lpc_find('body.login').css('background', lpc_bgsaved);
				} else if (getlpcbgcolor == 'gradient') {
					$('#customize-control-lpc_opts-lpc-background-color-control').hide();
					$('#customize-control-lpc_opts-lpc-background-gcolor1-control').show();
					$('#customize-control-lpc_opts-lpc-background-gcolor2-control').show();
					$('#customize-control-lpc_opts-lpc-background-gangle-control').show();
					let lpc_bgsaved = wp.customize('lpc_opts[lpc-background-color]').get();
					lpc_find('body.login').css('background', lpc_bgsaved);
				}
			}
		});
	});
	// Update the Login Page Background Image.
	wp.customize('lpc_opts[lpc-background-image]', function (value) {
		value.bind(function (lpcVal) {
			lpc_find('body.login').css('background-image', 'url(' + lpcVal + ')');
		});
	});


	// Change Css logo size.
	lpc_css_change('lpc_opts[lpc-logo-height]', '.login h1 a', 'height', 'px');
	lpc_css_change('lpc_opts[lpc-logo-width]', '.login h1 a', 'width', 'px');
	lpc_css_change('lpc_opts[lpc-logo-padding]', '.login h1 a', 'padding-bottom', 'px');
	lpc_css_change('lpc_opts[lpc-logo-margin-top]', '.login h1 a', 'margin-top', 'px');
	lpc_css_change('lpc_opts[lpc-logo-margin-bottom]', '.login h1 a', 'margin-bottom', 'px');

	lpc_element_attr_property('lpc_opts[lpc-logo-link]', '.login h1 a', 'href');
	// Change Css Login Page Background.
	lpc_css_change('lpc_opts[lpc-background-color]', 'body.login', 'background-color', '');

	// Enable/Disable Background Video Realtime.
	wp.customize('lpc_opts[lpc-bg-video-enable]', function (value) {
		value.bind(function (lpcVal) {
			if (lpcVal == 1) {
				$('#customize-control-lpc_opts-lpc-bg-video-enable-control').nextAll().show();
				//console.log(lpc_script.lpc_bg_video_url);
				//lpc_background_video(lpc_script.lpc_bg_video_url);
				var setlpcbgvideoControl = wp.customize.control('lpc_opts[lpc-background-video]-control');
				//console.log(setlpcbgvideoControl);
				if (setlpcbgvideoControl) {
					setlpcbgvideoControl.setting.set(lpc_script.lpc_bg_video_url);
					//console.log('Control Video is Set');
				}
			} else if (lpcVal == 0) {
				$('#customize-control-lpc_opts-lpc-bg-video-enable-control').nextAll().hide();
				// Remove Video Wrapper.
				var lpcbgvideoWrapper = lpc_find('#lpc-background-video-wrapper');
				if (lpcbgvideoWrapper) {
					lpcbgvideoWrapper.remove();
					//console.log('Video Wrapper Removed');
				}
				// Remove Video From Video Control.
				var lpcbgvideoControl = wp.customize.control('lpc_opts[lpc-background-video]-control');

				if (lpcbgvideoControl && lpcbgvideoControl.setting.get() !== '') {
					lpcbgvideoControl.setting.set('');
					//console.log('Video removed from Control');
				} else {
					// No Video is selected
					//console.log('No Video was selected to Control');
				}
			}
		});
	});

	// Form Related CSS. Outer Form.
	lpc_css_change('lpc_opts[lpc-form-width]', 'body.login div#login', 'width', 'px');
	lpc_css_change('lpc_opts[lpc-form-height]', 'body.login div#login', 'height', 'px');
	lpc_css_change_double('lpc_opts[lpc-form-padding-tb]', 'lpc_opts[lpc-form-padding-lr]', 'body.login div#login', 'padding', 'px');
	lpc_css_change('lpc_opts[lpc-form-bg-color]', 'body.login div#login', 'background-color', '');
	lpc_css_change('lpc_opts[lpc-form-border-style]', 'body.login div#login', 'border-style', '');
	lpc_css_change('lpc_opts[lpc-form-border-color]', 'body.login div#login', 'border-color', '');
	lpc_css_change('lpc_opts[lpc-form-border-width]', 'body.login div#login', 'border-width', 'px');
	lpc_css_change('lpc_opts[lpc-form-position-top]', 'body.login div#login', 'top', '%');

	// Form Related CSS. Inner Form.
	lpc_css_change('lpc_opts[lpc-inner-form-width]', 'body.login div#login form#loginform', 'width', 'px');
	lpc_css_change('lpc_opts[lpc-inner-form-height]', 'body.login div#login form#loginform', 'height', 'px');
	lpc_css_change('lpc_opts[lpc-inner-form-padding-top]', 'body.login div#login form#loginform', 'padding-top', 'px');
	lpc_css_change('lpc_opts[lpc-inner-form-padding-right]', 'body.login div#login form#loginform', 'padding-right', 'px');
	lpc_css_change('lpc_opts[lpc-inner-form-padding-bottom]', 'body.login div#login form#loginform', 'padding-bottom', 'px');
	lpc_css_change('lpc_opts[lpc-inner-form-padding-left]', 'body.login div#login form#loginform', 'padding-left', 'px');
	lpc_css_change('lpc_opts[lpc-inner-form-margin-top]', 'body.login div#login form#loginform', 'margin-top', 'px');
	lpc_css_change('lpc_opts[lpc-inner-form-margin-right]', 'body.login div#login form#loginform', 'margin-right', 'px');
	lpc_css_change('lpc_opts[lpc-inner-form-margin-bottom]', 'body.login div#login form#loginform', 'margin-bottom', 'px');
	lpc_css_change('lpc_opts[lpc-inner-form-margin-left]', 'body.login div#login form#loginform', 'margin-left', 'px');
	lpc_css_change('lpc_opts[lpc-inner-form-border-style]', 'body.login div#login form#loginform', 'border-style', '');
	lpc_css_change('lpc_opts[lpc-inner-form-border-width]', 'body.login div#login form#loginform', 'border-width', 'px');
	lpc_css_change('lpc_opts[lpc-inner-form-border-color]', 'body.login div#login form#loginform', 'border-color', '');
	lpc_css_change('lpc_opts[lpc-inner-form-border-radius]', 'body.login div#login form#loginform', 'border-radius', 'px');
	lpc_css_change('lpc_opts[lpc-inner-form-box-shadow]', 'body.login div#login form#loginform', 'box-shadow', '');
	lpc_css_change('lpc_opts[lpc-inner-form-bg-color]', 'body.login div#login form#loginform', 'background-color', '');
	lpc_css_change('lpc_opts[lpc-inner-form-position-top]', 'body.login div#login form#loginform', 'top', '%');
	lpc_css_change('lpc_opts[lpc-inner-form-position-left]', 'body.login div#login form#loginform', 'left', '%');

	// Update the Inner Form Background Image.
	wp.customize('lpc_opts[lpc-inner-form-bg-image]', function (value) {
		value.bind(function (lpcVal) {
			lpc_find('body.login div#login form#loginform').css('background-image', 'url(' + lpcVal + ')');
		});
	});

	// Inputs CSS Change.
	lpc_css_change('lpc_opts[lpc-form-inputs-font]', 'body.login div#login', 'font-family', '');
	lpc_css_change('lpc_opts[lpc-form-inputs-labels-color]', '.login #login #loginform label', 'color', '');
	lpc_css_change('lpc_opts[lpc-form-inputs-labels-size]', '.login #login #loginform label', 'font-size', 'px');
	lpc_css_change('lpc_opts[lpc-form-inputs-text-width]', '.login #login #loginform #user_login, .login #login #loginform .wp-pwd', 'width', '%');
	lpc_css_change('lpc_opts[lpc-form-inputs-text-height]', '.login #login #loginform input#user_login, .login #login #loginform input#user_pass', 'height', 'px');
	lpc_css_change('lpc_opts[lpc-form-inputs-text-font-size]', '.login #login #loginform input#user_login, .login #login #loginform input#user_pass', 'font-size', 'px');
	lpc_css_change_double('lpc_opts[lpc-form-inputs-text-padding-tb]', 'lpc_opts[lpc-form-inputs-text-padding-lr]', '.login #login #loginform input#user_login, .login #login #loginform input#user_pass', 'padding', 'px');
	lpc_css_change('lpc_opts[lpc-form-inputs-text-margin-top]', '.login #login #loginform input#user_login, .login #login #loginform input#user_pass', 'margin-top', 'px');
	lpc_css_change('lpc_opts[lpc-form-inputs-text-margin-bottom]', '.login #login #loginform input#user_login, .login #login #loginform input#user_pass', 'margin-bottom', 'px');
	lpc_css_change('lpc_opts[lpc-form-inputs-tb-color]', '.login #login #loginform input#user_login, .login #login #loginform input#user_pass', 'background-color', '');
	lpc_css_change('lpc_opts[lpc-form-inputs-text-color]', '.login #login #loginform input#user_login, .login #login #loginform input#user_pass', 'color', '');

	// Button Css Change.
	lpc_css_change('lpc_opts[lpc-form-button-width]', '.login #login #loginform p.submit #wp-submit', 'width', '%');
	lpc_css_change('lpc_opts[lpc-form-button-height]', '.login #login #loginform p.submit #wp-submit', 'height', 'px');
	lpc_css_change('lpc_opts[lpc-form-button-font-size]', '.login #login #loginform p.submit #wp-submit', 'font-size', 'px');
	lpc_css_change('lpc_opts[lpc-form-button-margin-top]', '.login #login #loginform p.submit #wp-submit', 'margin-top', 'px');
	lpc_css_change('lpc_opts[lpc-form-button-margin-right]', '.login #login #loginform p.submit #wp-submit', 'margin-right', 'px');
	lpc_css_change('lpc_opts[lpc-form-button-margin-bottom]', '.login #login #loginform p.submit #wp-submit', 'margin-bottom', 'px');
	lpc_css_change('lpc_opts[lpc-form-button-margin-left]', '.login #login #loginform p.submit #wp-submit', 'margin-left', 'px');
	lpc_css_change_double('lpc_opts[lpc-form-button-padding-tb]', 'lpc_opts[lpc-form-button-padding-lr]', '.login #login #loginform p.submit #wp-submit', 'padding', 'px');
	lpc_css_change('lpc_opts[lpc-form-button-border-style]', '.login #login #loginform p.submit #wp-submit', 'border-style', '');
	lpc_css_change('lpc_opts[lpc-form-button-border-width]', '.login #login #loginform p.submit #wp-submit', 'border-width', 'px');

	// Button Color Change on customizer.
	var lpcBtnClr;
	var lpcBtnHvr;
	wp.customize('lpc_opts[lpc-form-button-color]', function (value) {
		value.bind(function (lpcVal) {
			if (lpcVal == '') {
				lpcBtnClr = undefined;
				lpc_find('.wp-core-ui #login  .button-primary').css('background', '');
				lpc_find('.wp-core-ui #login  .button-primary').on('mouseover', function () {
					if (typeof lpcBtnHvr !== "undefined" || lpcBtnHvr === null) {
						$(this).css('background', lpcBtnHvr);
					} else {
						$(this).css('background', '');
					}
				}).on('mouseleave', function () {
					$(this).css('background', '');
				});
			} else {
				lpc_find('.wp-core-ui #login .button-primary').css('background', lpcVal);
				lpcBtnClr = lpcVal;

				lpc_find('.wp-core-ui #login  .button-primary').on('mouseover', function () {
					if (typeof lpcBtnHvr !== "undefined" || lpcBtnHvr === null) {
						$(this).css('background', lpcBtnHvr);
					} else {
						$(this).css('background', '');
					}
				}).on('mouseleave', function () {
					$(this).css('background', lpcVal);
				});
			}
		});
	});
	// Button hover color change on customizer.
	wp.customize('lpc_opts[lpc-form-button-color-hover]', function (value) {
		value.bind(function (lpcVal) {
			if (lpcVal == '') {
				lpcBtnHvr = undefined;
				lpc_find('.wp-core-ui #login  .button-primary').on('mouseover', function () {
					$(this).css('background', '');
				}).on('mouseleave', function () {
					if (typeof lpcBtnClr !== "undefined" || lpcBtnClr === null) {
						$(this).css('background', lpcBtnClr);
					} else {
						$(this).css('background', '');
					}
				});
			} else {
				lpcBtnHvr = lpcVal;
				lpc_find('.wp-core-ui #login  .button-primary').on('mouseover', function () {
					$(this).css('background', lpcVal);
				}).on('mouseleave', function () {
					if (typeof lpcBtnClr !== "undefined" || lpcBtnClr === null) {
						$(this).css('background', lpcBtnClr);
					} else {
						$(this).css('background', '');
					}
				});
			}
		});
	});
	// Button Text Color.
	var lpcBtnTxtClr;
	var lpcBtnTxtHvr;
	wp.customize('lpc_opts[lpc-form-button-text-color]', function (value) {
		value.bind(function (lpcVal) {
			if (lpcVal == '') {
				lpcBtnTxtClr = undefined;
				lpc_find('.wp-core-ui #login .button-primary').css('color', '');
			} else {
				lpc_find('.wp-core-ui #login .button-primary').css('color', lpcVal);

				lpc_find('.wp-core-ui #login  .button-primary').on('mouseover', function () {
					if (typeof lpcBtnTxtHvr !== "undefined" || lpcBtnTxtHvr === null) {
						$(this).css('color', lpcBtnTxtHvr);
					} else {
						$(this).css('color', '');
					}
				}).on('mouseleave', function () {
					$(this).css('color', lpcVal);
				});
				lpcBtnTxtClr = lpcVal;
			}
		});
	});
	// Button Text Hover Color.
	wp.customize('lpc_opts[lpc-form-button-text-color-hover]', function (value) {
		value.bind(function (lpcVal) {
			if (lpcVal == '') {
				lpcBtnTxtHvr = undefined;
				lpc_find('.wp-core-ui #login  .button-primary').on('mouseover', function () {
					$(this).css('color', lpcVal);
				}).on('mouseleave', function () {
					if (typeof lpcBtnTxtClr !== "undefined" || lpcBtnTxtClr === null) {
						$(this).css('color', lpcBtnTxtClr);
					} else {
						$(this).css('color', '');
					}
				});
			} else {
				lpcBtnTxtHvr = lpcVal;
				lpc_find('.wp-core-ui #login  .button-primary').on('mouseover', function () {
					$(this).css('color', lpcVal);
				}).on('mouseleave', function () {
					if (typeof lpcBtnTxtClr !== "undefined" || lpcBtnTxtClr === null) {
						$(this).css('color', lpcBtnTxtClr);
					} else {
						$(this).css('color', '');
					}
				});
			}
		});
	});

	// Button Border Color.
	var lpcBtnBrdrClr;

	wp.customize('lpc_opts[lpc-form-button-border-color]', function (value) {
		value.bind(function (lpcVal) {
			if (lpcVal == '') {
				lpc_find('.wp-core-ui #login  .button-primary').css('border-color', '');
			} else {
				lpc_find('.wp-core-ui #login  .button-primary').css('border-color', lpcVal);
				lpcBtnBrdrClr = lpcVal;
			}
		});
	});
	// Button Border Hover Color.
	wp.customize('lpc_opts[lpc-form-button-border-hover-color]', function (value) {
		value.bind(function (lpcVal) {
			if (lpcVal == '') {
				lpc_find('.wp-core-ui #login  .button-primary').css('border-color', '');
			} else {
				lpc_find('.wp-core-ui #login  .button-primary').on('mouseover', function () {
					$(this).css('border-color', lpcVal);
				}).on('mouseleave', function () {
					if (typeof lpcBtnBrdrClr !== "undefined" || lpcBtnBrdrClr === null) {
						$(this).css('border-color', lpcBtnBrdrClr);
					} else {
						$(this).css('border-color', '');
					}
				});
			}
		});
	});

	// Lost Password Link Visibility.
	wp.customize('lpc_opts[lpc-form-lostpass-enable]', function (value) {
		value.bind(function (lpcVal) {
			if (lpcVal) {
				lpc_find('.login #login p#nav').show();
			} else {
				lpc_find('.login #login p#nav').hide();
			}
		});
	});

	// Lost Password link text color.
	var lpcLostPassColor;
	wp.customize('lpc_opts[lpc-form-lostpass-text-color]', function (value) {
		value.bind(function (lpcVal) {
			if (lpcVal === '') {
				lpcLostPassColor = undefined;
				lpc_find('.login #login #nav a').css('color', '');
			} else {
				lpc_find('.login #login #nav a').css('color', lpcVal);

				lpc_find('.login #login #nav a').on('mouseover', function () {
					if (typeof lpcLostPassHoverColor !== "undefined" || lpcLostPassHoverColor === null) {
						$(this).css('color', lpcLostPassHoverColor);
					} else {
						$(this).css('color', '');
					}
				}).on('mouseleave', function () {
					$(this).css('color', lpcVal);
				});

				lpcLostPassColor = lpcVal;
			}
		});
	});

	// Lost Password link hover color.
	var lpcLostPassHoverColor;
	wp.customize('lpc_opts[lpc-form-lostpass-text-color-hover]', function (value) {
		value.bind(function (lpcVal) {
			if (lpcVal === '') {
				lpcLostPassHoverColor = undefined;
				lpc_find('.login #login #nav a').on('mouseover', function () {
					$(this).css('color', lpcVal);
				}).on('mouseleave', function () {
					if (typeof lpcLostPassColor !== "undefined" || lpcLostPassColor === null) {
						$(this).css('color', lpcLostPassColor);
					} else {
						$(this).css('color', '');
					}
				});
			} else {
				lpcLostPassHoverColor = lpcVal;
				lpc_find('.login #login #nav a').on('mouseover', function () {
					$(this).css('color', lpcVal);
				}).on('mouseleave', function () {
					if (typeof lpcLostPassColor !== "undefined" || lpcLostPassColor === null) {
						$(this).css('color', lpcLostPassColor);
					} else {
						$(this).css('color', '');
					}
				});
			}
		});
	});

	// Lostpass Css changes.
	lpc_css_change('lpc_opts[lpc-form-lostpass-font-size]', '.login #login p#nav a', 'font-size', 'px');
	lpc_css_change('lpc_opts[lpc-form-lostpass-box-label-size]', '.login #lostpasswordform label[for="user_login"]', 'font-size', 'px');

	// Back to Link Visibility.
	wp.customize('lpc_opts[lpc-backtolink-enable]', function (value) {
		value.bind(function (lpcVal) {
			if (lpcVal) {
				lpc_find('.login #login p#backtoblog').show();
			} else {
				lpc_find('.login #login p#backtoblog').hide();
			}
		});
	});
	// Back to link text color.
	var lpcbacktolinkColor;
	wp.customize('lpc_opts[lpc-backtolink-text-color]', function (value) {
		value.bind(function (lpcVal) {
			if (lpcVal === '') {
				lpcbacktolinkColor = undefined;
				lpc_find('.login #login p#backtoblog a').css('color', '');
			} else {
				lpc_find('.login #login p#backtoblog a').css('color', lpcVal);

				lpc_find('.login #login p#backtoblog a').on('mouseover', function () {
					if (typeof lpcbacktolinkHoverColor !== "undefined" || lpcbacktolinkHoverColor === null) {
						$(this).css('color', lpcbacktolinkHoverColor);
					} else {
						$(this).css('color', '');
					}
				}).on('mouseleave', function () {
					$(this).css('color', lpcVal);
				});

				lpcbacktolinkColor = lpcVal;
			}
		});
	});

	// Back to link hover color.
	var lpcbacktolinkHoverColor;
	wp.customize('lpc_opts[lpc-backtolink-text-color-hover]', function (value) {
		value.bind(function (lpcVal) {
			if (lpcVal === '') {
				lpcbacktolinkHoverColor = undefined;
				lpc_find('.login #login p#backtoblog a').on('mouseover', function () {
					$(this).css('color', lpcVal);
				}).on('mouseleave', function () {
					if (typeof lpcbacktolinkColor !== "undefined" || lpcbacktolinkColor === null) {
						$(this).css('color', lpcbacktolinkColor);
					} else {
						$(this).css('color', '');
					}
				});
			} else {
				lpcbacktolinkHoverColor = lpcVal;
				lpc_find('.login #login p#backtoblog a').on('mouseover', function () {
					$(this).css('color', lpcVal);
				}).on('mouseleave', function () {
					if (typeof lpcbacktolinkColor !== "undefined" || lpcbacktolinkColor === null) {
						$(this).css('color', lpcbacktolinkColor);
					} else {
						$(this).css('color', '');
					}
				});
			}
		});
	});
	// Back to link Css change.
	lpc_css_change('lpc_opts[lpc-backtolink-font-size]', '.login #login p#backtoblog a', 'font-size', 'px');

	// footer Enable/Disable.
	wp.customize('lpc_opts[lpc-footer-enable]', function (value) {
		value.bind(function (lpcVal) {
			if (lpcVal == 0) {
				lpc_find('.login .lpc-footer-wrap').fadeOut();
			} else if (lpcVal == 1) {
				lpc_find('.login .lpc-footer-wrap').fadeIn();
			}
		});
	});


	// Copyright Enable/Disable.
	wp.customize('lpc_opts[lpc-copyright-enable]', function (value) {
		value.bind(function (lpcVal) {
			if (lpcVal == 0) {
				lpc_find('.login .lpc-footer-wrap .lpc-copyright').fadeOut();
				lpc_find('.login .lpc-footer-wrap').css('padding', 'unset');
			} else if (lpcVal == 1) {
				lpc_find('.login .lpc-footer-wrap').css('padding', '5px');
				lpc_find('.login .lpc-footer-wrap .lpc-copyright').fadeIn();

			}
		});
	});

	// powered by Enable/Disable.
	wp.customize('lpc_opts[lpc-poweredby-enable]', function (value) {
		value.bind(function (lpcVal) {
			if (lpcVal == 0) {
				lpc_find('.login .lpc-footer-wrap .lpc-poweredby').fadeOut();
			} else if (lpcVal == 1) {
				lpc_find('.login .lpc-footer-wrap .lpc-poweredby').fadeIn();
			}
		});
	});
	// Copyright text change.
	wp.customize('lpc_opts[lpc-footer-copyright-text]', function (value) {
		value.bind(function (lpcVal) {
			if (lpcVal === '') {
				lpc_find('.login .lpc-footer-wrap .lpc-copyright').fadeOut();
				lpc_find('.login .lpc-footer-wrap').css('padding', '0');
			} else {
				lpc_find('.login .lpc-footer-wrap').css('padding', '5px');
				lpc_find('.login .lpc-footer-wrap .lpc-copyright').fadeIn();
				lpc_find('.login .lpc-footer-wrap .lpc-copyright').text(lpcVal);
			}
		});
	});
	lpc_css_change('lpc_opts[lpc-footer-background]', '.login .lpc-footer-wrap', 'background', '');
	lpc_css_change('lpc_opts[lpc-footer-copyright-color]', '.login .lpc-footer-wrap .lpc-copyright', 'color', '');
	lpc_css_change('lpc_opts[lpc-footer-copyright-font-size]', '.login .lpc-footer-wrap .lpc-copyright', 'font-size', 'px');

	// Poweredby position change.
	wp.customize('lpc_opts[lpc-poweredby-position]', function (value) {
		value.bind(function (lpcVal) {
			if (lpcVal === 'left') {
				lpc_find('.login .lpc-footer-wrap .lpc-poweredby').css('right', 'unset');
				lpc_find('.login .lpc-footer-wrap .lpc-poweredby').css('left', '0');
			} else if (lpcVal === 'right') {
				lpc_find('.login .lpc-footer-wrap .lpc-poweredby').css('left', 'unset');
				lpc_find('.login .lpc-footer-wrap .lpc-poweredby').css('right', '0');

			}
		});
	});

	lpc_css_change('lpc_opts[lpc-footer-copyright-font-weight]', '.login .lpc-footer-wrap .lpc-copyright', 'font-weight', '');
	lpc_css_change('lpc_opts[lpc-footer-poweredby-color]', '.login .lpc-footer-wrap .lpc-poweredby', 'color', '');
	lpc_css_change('lpc_opts[lpc-footer-poweredby-color]', '.login .lpc-footer-wrap .lpc-poweredby a', 'color', '');

})(jQuery);

