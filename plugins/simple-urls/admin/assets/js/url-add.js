jQuery(document).ready(function() {
	// All add related actions - lives here because it's on every page
	let add_popup          = jQuery('#url-add');
	let save_form          = jQuery('#add_new_form');
	let form_link_box      = jQuery('#add-new-url-box');
	let save_link_btn      = save_form.find('button');
	let save_link_btn_html = save_link_btn.html();
	let js_error           = save_form.find('.js-error');
	let edit_page          = lassoLiteOptionsData.site_url + '/wp-admin/edit.php?post_type=' + lassoLiteOptionsData.simple_urls_slug + '&page=' + lassoLiteOptionsData.simple_urls_slug + '-' + lassoLiteOptionsData.page_url_details;

	let btn_lasso_add_link       = jQuery('.btn-lasso-add-link');
	let btn_lasso_add_link_clone = jQuery(btn_lasso_add_link).html();
	let is_from_editor           = jQuery(add_popup).data('is-from-editor');
	let go_to_detail_modal       = is_from_editor !== undefined && is_from_editor === 1;
	let url_quick_detail_modal   = jQuery("#url-quick-detail");
	let loading_by_font_awesome  = '<i class="far fa-circle-notch fa-spin"></i>'; // Re-defined for case get_loading_by_font_awesome is not defined

	let toolbarOptions = [
		[
			'bold',
			'italic',
			'underline',
			'strike'],

		[
			'link',
			{ 'list': 'bullet' }
		],

		[
			{ 'color': [] },
			{ 'background': [] }
		],
		['clean'],
	];
	let quill_options = {theme: 'snow', placeholder: 'Enter a description', modules: {toolbar: toolbarOptions, clipboard: {matchVisual: false}}};

	// Render Setup Progress information
	let current_page = lasso_lite_helper.get_url_parameter('post_type');
	if ( current_page === 'surl' ) {
		lasso_lite_helper.inject_to_template(jQuery("#wrapper-circle"), 'setup-pregress-jsrender', lassoLiteOptionsData.setup_progress);
		render_setup_progress_circle(lassoLiteOptionsData.setup_progress.progress);
	}


	jQuery(document)
		.on('click', '#btn-lasso-add-new-link', save_lasso_url)
		.on('click', '.image_update', set_thumbnail)
		.on('click', '.btn-lasso-save-link.lite', save_url_quick_detail)
		.on('keyup', '.affiliate_name', product_name_key_up)
		.on('click', '.btn-close-save-quick-link', close_save_quick_link)
		.on('click', process_up_sell_modal)
		.on('click', '#wrapper-circle', process_progress_dropdown)
		.on('click', 'body', close_progress_dropdown)
		.on('click', '.btn-add-20-links', open_modal_add_link)
		.on('click', '#setup-progress-enable-support', open_enable_support_modal);

	function save_lasso_url() {
		// ? Re-assign case elements existing render yet
		if ( js_error.length === 0) {
			save_form                = jQuery('#add_new_form');
			js_error                 = save_form.find('.js-error');
			save_link_btn            = save_form.find('button');
			save_link_btn_html       = save_link_btn.html();
			form_link_box            = jQuery('#add-new-url-box');
			add_popup                = jQuery('#url-add');
			btn_lasso_add_link       = jQuery('.btn-lasso-add-link');
			btn_lasso_add_link_clone = jQuery(btn_lasso_add_link).html();
			is_from_editor           = jQuery(add_popup).data('is-from-editor');
			go_to_detail_modal       = is_from_editor !== undefined && is_from_editor === 1;
		}

		let url = form_link_box.val();

		js_error.addClass('d-none');

		if (url === '' || !is_valid_http_url(url)) {
			js_error.text('URL is incorrect.');
			js_error.removeClass('d-none');
			return;
		}

		jQuery.ajax({
			url: lassoLiteOptionsData.ajax_url,
			type: 'post',
			data: {
				action: 'lasso_lite_add_a_new_link',
				nonce: lassoLiteOptionsData.optionsNonce,
				link: url,
			},
			beforeSend: function() {
				save_link_btn.prop('disabled', true);
				form_link_box.prop('disabled', true);
				save_link_btn.html(get_loading_image_small())
			}
		})
			.done(function(res) {
				if (res.success) {
					if ( go_to_detail_modal ) {
						load_url_quick_detail( res.data['post_id'] );
					} else {
						let post_id = res.data['post_id'] ? res.data['post_id'] : res.data.post['lasso_id'];
						let lasso_lite_edit_url = edit_page + '&post_id=' + post_id;

						if ( res.data['is_duplicate'] !== undefined && res.data['is_duplicate'] === true ) {
							lasso_lite_edit_url += '&is_duplicate=true';
						}

						add_popup.modal('hide');
						window.location.href = lasso_lite_edit_url;
					}
				} else {
					js_error.text(res.data);
					js_error.removeClass('d-none');

					save_link_btn.text('Continue Anyway');
					save_link_btn.data('continue', 1);

					if ( is_from_editor ) {
						btn_lasso_add_link.data('disabled', 0);
						btn_lasso_add_link.html(btn_lasso_add_link_clone);
					}
				}
			})
			.error(function(xhr, status, error) {
				js_error.text(error);
				js_error.removeClass('d-none');
			})
			.always(function() {
				save_link_btn.prop('disabled', false);
				form_link_box.prop('disabled', false);

				save_link_btn.text('Add Link');
			});
	}

	function load_url_quick_detail(post_id) {
		// ? Re-assign case elements existing render yet
		form_link_box            = jQuery('#add-new-url-box');
		btn_lasso_add_link       = jQuery('.btn-lasso-add-link');
		btn_lasso_add_link_clone = jQuery(btn_lasso_add_link).html();
		add_popup                = jQuery('#url-add');
		url_quick_detail_modal   = jQuery("#url-quick-detail");

		jQuery.ajax({
			url: lassoLiteOptionsData.ajax_url,
			type: 'post',
			data: {
				action: 'lasso_lite_get_link_quick_detail',
				nonce: lassoLiteOptionsData.optionsNonce,
				post_id: post_id,
			},
			beforeSend: function() {
			}
		})
			.done(function(res) {
				// ? Reset the form add link to default behavior
				form_link_box.val("");
				btn_lasso_add_link.data('disabled', 0);
				btn_lasso_add_link.html(btn_lasso_add_link_clone);
				add_popup.modal('hide');

				if(res.success) {
					if (typeof res.data != 'undefined') {
						let data      = res.data;
						let lasso_url = data.lasso_url;

						let json_data = [
							{
								id                          : lasso_url.id,
								name                        : lasso_url.name,
								image_src                   : lasso_url.image_src,
								primary_button_text         : lasso_url.display.primary_button_text,
								primary_button_text_default : lasso_url.display.primary_button_text_default,
								thumbnail_id                : lasso_url.thumbnail_id,
								slug                        : lasso_url.slug
							}
						];

						lasso_lite_helper.inject_to_template('.url-quick-detail-wrapper', 'url-quick-detail-jsrender', json_data);

						// INITIALIZE QUILL
						let quill = new Quill('#description', quill_options);

						// Fix error when bold format is link
						// load_url_quick_detail
						quill.on('editor-change', function(eventName, ...args) {
							if ('selection-change' === eventName) {
								quill.update();
							}
						});

						window.lite_quill = quill;

						// RECREATE HOVER EFFECT ON DESCRIPTION BOX
						jQuery('.ql-editor').focus(
							function(){
								jQuery(this).parent('div').attr('style', 'border-color: var(--lasso-light-purple) !important');
							}).blur(
							function(){
								jQuery(this).parent('div').removeAttr('style');
							});

						// ? url_quick_detail_modal initial when Choose a Display Type is loaded
						url_quick_detail_modal.modal("show");
					}
				}
			})
	}

	function save_url_quick_detail () {
		// ? Re-assign case elements existing render yet
		let btn_lasso_save_link       = jQuery(this);
		let btn_lasso_save_link_clone = btn_lasso_save_link.html();
		let description               = window.lite_quill.root.innerHTML;
		url_quick_detail_modal        = jQuery("#url-quick-detail");

		jQuery.ajax({
			url: lassoLiteOptionsData.ajax_url,
			type: 'post',
			data: {
				action              : 'lasso_lite_save_link_quick_detail',
				nonce               : lassoLiteOptionsData.optionsNonce,
				lasso_id            : jQuery("#lasso_id").val(),
				affiliate_name      : jQuery("#affiliate_name").val(),
				buy_btn_text        : jQuery("#buy_btn_text").val(),
				thumbnail_image_url : jQuery("#thumbnail_image_url").val(),
				description         : description,
				badge_text          : jQuery("#badge_text").val()
			},
			beforeSend: function() {
				jQuery('.js-error').addClass("d-none");
				btn_lasso_save_link.prop('disabled', true);
				btn_lasso_save_link.html(loading_by_font_awesome);
			}
		})
			.done(function(res) {
				if (typeof res.data != 'undefined') {
					let response = res.data;
					if ( response.success ) {
						try { add_short_code_single_block( btn_lasso_save_link ); } catch (error) {}
						try { add_short_code_single( btn_lasso_save_link ); } catch (error) {}

						url_quick_detail_modal.modal("hide");
					} else {
						jQuery('.js-error').removeClass("d-none");
						jQuery('.js-error').html(response.msg);
						btn_lasso_save_link.html(btn_lasso_save_link_clone);
						btn_lasso_save_link.prop('disabled', false);
					}
				}
			});
	}


	function set_thumbnail() {
		let custom_uploader = wp.media({
			title: 'Select an Image',
			multiple: false,
			library: { type : 'image' },
			button: { text: 'Select Image' }
			// frame: 'post'
		});

		if(custom_uploader) {
			// When a file is selected, grab the URL
			custom_uploader.on('select', function() {
				let attachment = custom_uploader.state().get('selection').first().toJSON();
				jQuery("#lasso_render_thumbnail").attr('src', attachment.url);
				jQuery("#lasso_thumbnail_id").val(attachment.id);
				jQuery("#thumbnail_image_url").val(attachment.url);
			});

			custom_uploader.open();
		}
	}

	function get_loading_image_small() {
		return '<div class="loader-small"></div>';
	}

	function is_valid_http_url(string) {
		let url;

		try {
			url = new URL(string);
		} catch (e) {
			return false;
		}

		return url.protocol === "http:" || url.protocol === "https:";
	}

	form_link_box.off('change').on('change',function(e) {
		// ? Re-assign case elements existing render yet
		if ( js_error.length === 0) {
			save_form          = jQuery('#add_new_form');
			js_error           = save_form.find('.js-error');
			save_link_btn      = save_form.find('button');
			save_link_btn_html = save_link_btn.html();
		}

		js_error.addClass('d-none');
		save_link_btn.html(save_link_btn_html);
	});

	form_link_box.off('paste').on('paste',function(e) {
		// ? Re-assign case elements existing render yet
		if ( js_error.length === 0) {
			save_form          = jQuery('#add_new_form');
			js_error           = save_form.find('.js-error');
			save_link_btn      = save_form.find('button');
			save_link_btn_html = save_link_btn.html();
		}

		js_error.addClass('d-none');
		save_link_btn.html(save_link_btn_html);
	});

	form_link_box.off('keypress').on('keypress',function(e) {
		// ? Re-assign case elements existing render yet
		if (js_error.length === 0) {
			save_form          = jQuery('#add_new_form');
			js_error           = save_form.find('.js-error');
			save_link_btn      = save_form.find('button');
			save_link_btn_html = save_link_btn.html();
		}

		js_error.addClass('d-none');

		// WHEN ENTER IS PRESSED, SEARCH
		if(e.which === 13) {
			jQuery(this).focusout();
			save_lasso_url();
			return false;
		} else {
			save_link_btn.html(save_link_btn_html);
		}
	});

	function product_name_key_up() {
		jQuery(".product-name").text(jQuery(this).val());
	}

	function close_save_quick_link() {
		jQuery("#url-quick-detail").modal("hide");
		jQuery("#lasso-display-type").addClass("d-none");
		jQuery("#lasso-urls").removeClass("d-none");
		jQuery("#lasso-display-add").modal("show");
	}

	function process_up_sell_modal( event ) {
		if ( jQuery('#up-sell-modal').length && jQuery('.lite-container').length ) {
			let up_sell_modal = jQuery("#up-sell-modal");
			let up_sell_modal_w = jQuery("#up-sell-modal").width();
			let maximum_left = jQuery('.lite-container').width() + jQuery('.lite-container').offset().left;
			let lasso_lite_disabled_wrapper = jQuery(event.target).closest('.lasso-lite-disabled');
			let support = lasso_lite_helper.get_url_parameter('support');

			if ( lasso_lite_disabled_wrapper.length !== 0 && support === null ) {
				let x = ( event.pageX - 150 );
				if ( x + up_sell_modal_w + 160 > maximum_left ) {
					x = maximum_left - up_sell_modal_w - 200;
				}
				up_sell_modal.css('left', x + "px");
				up_sell_modal.css('top', ( event.pageY - 10 ) + "px");
				up_sell_modal.css('display', 'block');
			} else {
				up_sell_modal.css('display', 'none');
			}
		}
	}

	function process_progress_dropdown() {
		let body_el = jQuery("body");
		let setup_progress_wrapper = jQuery("#setup-progress-wrapper");
		if ( body_el.hasClass('setup-progress-dropdown-open') === false ) {
			body_el.addClass('setup-progress-dropdown-open');
			setup_progress_wrapper.addClass("animation");
		} else {
			body_el.removeClass('setup-progress-dropdown-open');
			setup_progress_wrapper.removeClass("animation");
		}
	}

	function close_progress_dropdown(event) {
		let body_el = jQuery(this);
		let setup_progress_wrapper = jQuery("#setup-progress-wrapper");
		if( jQuery(event.target).closest("#wrapper-circle").length === 0 && body_el.hasClass('setup-progress-dropdown-open') === true ) {
			body_el.removeClass('setup-progress-dropdown-open');
			setup_progress_wrapper.removeClass("animation");
		}
	}
});

/**
 * Render setup progress circle
 *
 * @param value
 */
function render_setup_progress_circle( value ) {
	let noti_modal = new lasso_lite_helper.lasso_generate_modal();
	noti_modal
		.init( {
			hide_btn_cancel: true,
			hide_btn_ok: false,
			use_modal_large: false
		})
		.set_heading( "Hooray!" )
		.set_description("You've completed of your Lasso setup tasks")
		.set_btn_ok({
			class: 'green-bg'
		});

	let lasso_lite_close_noti_modal = localStorage.getItem('lasso_lite_close_noti_modal');
	jQuery(document).on('click', jQuery('#' + noti_modal.get_modal_id() + ' .btn-ok'), function () {
		lasso_lite_helper.set_local_storage('lasso_lite_close_noti_modal', 1);
		noti_modal.hide();
	});
	jQuery('#circle').circleProgress({
		value: value,
		size: 75,
		emptyFill: 'transparent',
		startAngle: -190,
		fill: {
			gradient: ["#00ffd3", "#10bea0"]
		},
	}).on('circle-animation-progress', function (event, animationProgress, stepValue) {
		if ( jQuery('#circle-holder').length === 0 ) {
			jQuery(event.target).closest('#wrapper-circle').append('<span id="circle-holder"><span>0</span><small class="percent"> %</small></span>');
		} else {
			stepValue = Math.round( stepValue * 100 );
			jQuery('#circle-holder span').text( stepValue );
		}
	}).on('circle-animation-end', function (event) {
		if ( value === 0.95 ) {
			jQuery('#setup-progress-wrapper #setup-progress').addClass('.progress-95');
			jQuery('#setup-progress-wrapper #progress-complete').css('display', 'none');
			jQuery('#wrapper-circle').trigger('click');
		}

		if ( value >= 1 && lasso_lite_close_noti_modal === null ) {
			noti_modal.show();
		}
		if ( value === 1 ) {
			jQuery('#setup-progress-wrapper #progress-complete').css('display', 'block');
			jQuery('#setup-progress-wrapper #setup-progress').css('display', 'none');
		}
	});
}

/**
 * Update setup process display
 */
function refresh_setup_progress() {
	if ( ! window.jQuery) {
		console.log('jQuery is not loaded. Waiting for a moment.');
		return;
	}

	jQuery.ajax({
		url: lassoLiteOptionsData.ajax_url,
		type: 'post',
		data: {
			action: 'lasso_lite_get_setup_progress',
			nonce: lassoLiteOptionsData.optionsNonce,
		},
	})
		.done(function(res) {
			if (res.success) {
				let data = res.data;
				let setup_progress = data.progress;

				if ( jQuery("#wrapper-circle").length ) {
					lasso_lite_helper.inject_to_template(jQuery("#wrapper-circle"), 'setup-pregress-jsrender', data);
					render_setup_progress_circle(setup_progress);
				}
			}
		});
}

/**
 * Open enable support model if customer click from the Setup progress
 */
function open_enable_support_modal() {
	let is_enabled_support = lassoLiteOptionsData.setup_progress.enable_support;
	if ( ! is_enabled_support ) {
		jQuery('#enable-support').modal('show');
	}
}

function open_modal_add_link() {
	jQuery('#url-add').modal('show');
}
