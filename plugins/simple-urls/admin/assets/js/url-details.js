jQuery(document).ready(function() {
	let is_update = parseInt(jQuery('#is-update').val()) === 1;
	jQuery('.lasso-box-2').html(jQuery('#image_editor').html());

	jQuery("#basic-categories").select2({
		width: '100%',
		allowClear: true,
		tags: true,
	});

	init_event();
	init_quill();
	amazon_notification();
	product_duplicate_notification();

	/**
	 *
	 * This is a jQuery init event
	 */
	function init_event() {
		jQuery(document)
			.on('click', '#btn-save-url', save_url_details)
			.on('click', '#lasso-delete-url', delete_url_details)
			.on('click', '#btn-confirm-delete', confirm_delete_url_details)
			.on('click', '#lasso-thumbnail', set_thumbnail)
			.on('click', '#copy-shortcode', copy_shortcode)
			.on('keyup', '#affiliate_name', affiliate_name_key_up)
			.on('keyup', '#permalink', permalink_key_up)
			.on('keyup', '#buy_btn_text', primary_button_key_up)
			.on('keyup', '#surl_redirect', primary_url_key_up)
			.on('change', '#surl_redirect', change_redirect_url)
			.on('click', '#btn-tracking-id-save', save_tracking_id)
			.on('change', '#show_pricing', update_price_section)
			.on('keyup', '#price', update_price_section)
			.on('keyup', '#badge_text', update_badge_section)
			.on('change', '#show_disclosure', show_disclosure_section);
	}

	/**
	 * It initializes the Quill rich text editor for the description field
	 */
	function init_quill() {
		// FOR DESCRIPTION RICH EDITOR
		// ADD OPTIONS FOR EDITOR TOOLBAR
		let toolbarOptions = [
			[
				'bold',
				'italic',
				'underline',
				'strike'
			],
			[
				'link',
				{ 'list': 'bullet' }
			],
			[
				{ 'color': [] }, { 'background': [] }
			],
			['clean'],
		];

		// SET THEME, PLACEHOLDER, AND TOOLBAR OPTIONS
		let quill_options = {theme: 'snow', placeholder: 'Enter a description', modules: {toolbar: toolbarOptions, clipboard: {matchVisual: false}}};

		// INITIALIZE QUILL
		let quill = new Quill('#description', quill_options);

		quill.on('text-change', function(delta, oldDelta, source) {
			let description_value = quill.root.innerHTML;
			description_value = '<p><br></p>' == description_value ? '' : description_value;
			add_description_block();
			jQuery(".lasso-lite-description").html(description_value);
		});

		// Fix error when bold format is link
		quill.on('editor-change', function(eventName, ...args) {
			if ('selection-change' === eventName) {
				quill.update();
			}
		});

		window.quill = quill;

		// RECREATE HOVER EFFECT ON DESCRIPTION BOX
		jQuery('.ql-editor').focus(
			function(){
				jQuery(this).parent('div').attr('style', 'border-color: var(--light-purple) !important');
			}).blur(
			function(){
				jQuery(this).parent('div').removeAttr('style');
			});
	}

	function save_url_details(event , is_change_primary_link = false) {
		change_progress_message();
		lasso_lite_helper.setProgressZero();
		lasso_lite_helper.scrollTop();

		let ajax_url                = lassoLiteOptionsData.ajax_url;
		let lasso_lite_update_popup = jQuery('#url-save');
		let ajax_data               = get_payload_to_save_url(is_change_primary_link);

		ajax_data.get_display_html = event ? false : true;

		jQuery.ajax({
			url: ajax_url,
			type: 'post',
			data: ajax_data,
			beforeSend: function (xhr) {
				// Collapse current error + success notifications
				if ( event || is_change_primary_link ) {
					jQuery(".alert.red-bg.collapse").collapse('hide');
					jQuery(".alert.green-bg.collapse").collapse('hide');
					lasso_lite_update_popup.modal('show');
					lasso_lite_helper.set_progress_bar( 98, 20 );
				}
			}
		})
		.done(function (res) {
			let post = res.data.post;
			
			if (res.success) {
				// ? Show success notification
				if ( event ) {
					lasso_lite_helper.do_notification('Your link saved.', 'green', 'save-success-notification' );
				} else if (res.display_html) {
					jQuery('#demo_display_box').html(res.display_html);
				}

				if( ! is_update ) {
					window.location.replace(post.edit_link);
				}

				if ( res.data['is_duplicate'] !== undefined && res.data['is_duplicate'] === true ) {
					let edit_page        = lassoLiteOptionsData.site_url + '/wp-admin/edit.php?post_type=' + lassoLiteOptionsData.simple_urls_slug + '&page=' + lassoLiteOptionsData.simple_urls_slug + '-' + lassoLiteOptionsData.page_url_details;
					let post_id          = res.data['post_id'] ? res.data['post_id'] : res.data.post['lasso_id'];
					window.location.href = edit_page + '&post_id=' + post_id + '&is_duplicate=true';
				}

				// Update info
				update_ui( post );

				if(res.data.warning !== '') {
					lasso_lite_helper.do_notification(res.data.warning, 'orange');
				}
			} else {
				// ? Show error notification
				lasso_lite_helper.do_notification(res.data, 'red');
			}
		})
		.fail(function (xhr, status, error) {
			// ? Show error notification
			lasso_lite_helper.do_notification(error, 'red');
		})
		.always(function() {
			lasso_lite_helper.set_progress_bar_complete();
			setTimeout(function() {
				// Hide update popup by setTimeout to make sure this run after lasso_update_popup.modal('show')
				lasso_lite_update_popup.modal('hide');
			}, 1000);

		});
	}

	function update_ui( post ) {
		if (typeof post === 'undefined') {
			return;
		}

		let price_el = jQuery("#price");
		let image_src = post.image_src;
		let post_name = decodeEntities(post.name);
		let open_new_tab = post.open_new_tab === "1";
		let enable_nofollow = post.enable_nofollow === "1";
		let public_link = post.public_link;

		let rel = enable_nofollow ? 'nofollow' : '';
		rel = open_new_tab ? rel + ' noopener' : rel;

		jQuery('a.lasso-title').attr('href', public_link);
		jQuery('a.lasso-title').attr('rel', rel.trim());
		jQuery('a.lasso-title').attr('target', post.html_attribute.target);
		jQuery('a.lasso-title').text(post_name);
		jQuery('#lasso-url-heading').text(post_name);
		jQuery('#affiliate_name').val(post_name);
		jQuery('#surl_redirect').val(post.target_url);
		jQuery('#permalink').val(post.slug);
		jQuery('#render_thumbnail').attr('src', image_src);
		jQuery('.js-permalink').attr('href', public_link);
		jQuery('.js-permalink').text(public_link);
		jQuery('a.lasso-button-1').attr('href', public_link);
		jQuery('a.lasso-button-1').attr('rel', rel.trim());
		jQuery('a.lasso-button-1').attr('target', post.html_attribute.target);
		jQuery('.lasso-price .latest-price').text(post.price);
		price_el.val(post.price);

		if ( post.is_amazon_page ) {
			jQuery('.permalink-wrapper').addClass('lasso-none');
			price_el.prop('disabled', true);
		} else {
			jQuery('.permalink-wrapper').removeClass('lasso-none');
			price_el.prop('disabled', false);
		}
	}

	function delete_url_details() {
		let post_id = jQuery("#lasso-lite-id").val();

		jQuery.ajax({
			url: lassoLiteOptionsData.ajax_url,
			type: 'post',
			data: {
				action: 'lasso_lite_delete_post',
				nonce: lassoLiteOptionsData.optionsNonce,
				post_id: post_id
			},
			beforeSend: function (xhr) {
			}
		})
			.done(function (res) {
				res = res.data;
				if (res.data == 1) {
					window.location.href = "/wp-admin/edit.php?post_type=surl&page=surl-dashboard";
				} else {
					lasso_lite_helper.do_notification('Something went wrong.', 'red');
				}
			})
			.fail(function (xhr, status, error) {
				lasso_lite_helper.do_notification('save-error-notification', error);
				lasso_lite_helper.do_notification( error, 'red');
			})
			.always(function(){
				jQuery('#url-delete').modal('hide');
			});
	}

	function confirm_delete_url_details() {
		// Delete Lasso Lite URL
		jQuery('#url-delete').modal('show');
	}

	function get_payload_to_save_url(is_change_primary_link = false) {
		let post_id        = jQuery("#lasso-lite-id").val();
		let action         = 'lasso_lite_save_lasso_url';
		let affiliate_name = jQuery("#affiliate_name").val();
		let surl_redirect  = jQuery("#surl_redirect").val();
		let thumbnail_id   = jQuery("#thumbnail_id").val();
		let permalink      = jQuery('#permalink').val();
		let description    = window.quill.root.innerHTML;

		let settings = {
			// fields
			post_name        : jQuery("[name='uri']").val(),
			affiliate_name   : affiliate_name,
			surl_redirect    : surl_redirect,
			thumbnail        : jQuery("#render_thumbnail").attr('src'),
			permalink        : jQuery("#permalink").val(),
			theme_name       : jQuery("#theme_name").val(),
			buy_btn_text     : jQuery("#buy_btn_text").val(),
			enable_nofollow  : jQuery("#url-en-nofollow").prop("checked") ? 1 : 0,
			open_new_tab     : jQuery("#url-open-link").prop("checked") ? 1 : 0,
			enable_sponsored : jQuery("#enable_sponsored").prop("checked") ? 1 : 0,
			show_disclosure  : jQuery("#show_disclosure").prop("checked") ? 1 : 0,
			show_price       : jQuery("#show_pricing").prop("checked") ? 1 : 0,
			price            : jQuery("#price").val(),
			description      : description,
			categories       : jQuery("#basic-categories").val(),
			badge_text       : jQuery('#badge_text').val()
		};

		return {
			action                 : action,
			nonce                  : lassoLiteOptionsData.optionsNonce,
			post_id                : post_id,
			settings               : settings,
			thumbnail_id           : thumbnail_id,
			permalink              : permalink,
			is_change_primary_link : is_change_primary_link
		};
	}

	function set_thumbnail() {
		if ( lasso_lite_helper.is_empty( wp ) || ! wp.hasOwnProperty('media') || typeof wp.media !== 'function') {
			console.warn('Lasso cannot load WP media JS');
		}

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
				jQuery("#render_thumbnail").attr('src', attachment.url);
				let image_editor = jQuery("#image_editor");
				jQuery(image_editor).find("#render_thumbnail").attr("src", attachment.url);
				jQuery("#thumbnail_id").val(attachment.id);
				jQuery("#thumbnail_image_url").val("");
			});

			custom_uploader.open();
		}
	}

	// COPY SHORTCODE
	function copy_shortcode() {
		// ANIMATE CLICK
		jQuery('#copy-shortcode').addClass('animate-bounce-in').delay(500).queue(function(){
			jQuery(this).removeClass('animate-bounce-in').dequeue();
		});

		jQuery('#copy-shortcode').attr('data-tooltip', 'Copied!');

		let copyText = document.getElementById("shortcode");

		copyText.select();
		copyText.setSelectionRange(0, 99999); /*For mobile devices*/

		document.execCommand("copy");
	}

	function affiliate_name_key_up(event){
		jQuery("#lasso-url-heading").text(jQuery(event.target).val());
		jQuery(".lasso-title").text(jQuery(event.target).val());
	}

	function permalink_key_up(){
		let el = jQuery(this);
		let permalink = jQuery('.js-permalink');
		let post_name = el.val().trim().replace(/[\W_]+/g,"-");

		el.val(post_name);

		if (post_name.length) {
			permalink.text(lassoLiteOptionsData.site_url + '/' + lassoLiteOptionsData.rewrite_slug_default + '/' + post_name + '/');
		} else {
			permalink.text(lassoLiteOptionsData.site_url + '/' + lassoLiteOptionsData.rewrite_slug_default + '/');
		}

	}

	function primary_button_key_up(event){
		let button_text = jQuery(event.target).val();
		if (button_text.trim() === '') {
			jQuery(".lasso-button-1").text(jQuery(event.target).attr('placeholder'));
		} else {
			jQuery(".lasso-button-1").text(button_text);
		}
	}

	function primary_url_key_up(event) {
		let el = jQuery(event.target);
		let url = el.val();
		let placeholder = el.attr('placeholder');
		let display_box = jQuery('#demo_display_box');
		let new_url = url.trim() === '' ? placeholder : url;

		display_box.find('a').attr('href', new_url);
	}

	function change_redirect_url( event ) {
		let is_change_primary_link = event.target.defaultValue !== jQuery(event.target).val();
		save_url_details(null, is_change_primary_link);
	}

	function change_progress_message() {
		let default_message = 'Saving your changes.';

		jQuery('#url-save').find('p').text(default_message);
	}

	function decodeEntities(encodedString) {
		let textArea = document.createElement('textarea');
		textArea.innerHTML = encodedString;
		return textArea.value;
	}

	/**
	 * Check and add description block if this one not exiting
	 *
	 * @return void
	 */
	function add_description_block() {
		if ( ! jQuery(".lasso-lite-description").length ) {
			let lasso_description_html = '<div class="lasso-lite-description"></div>';
			jQuery(lasso_description_html).insertAfter(jQuery('#demo_display_box div.clear:last-child'));
		}
	}

	function amazon_notification() {
		let $lite_container             = jQuery('.lite-container.container');
		let is_amazon_link              = $lite_container.data('is-amazon-link') === 1;
		let amazon_primary_tracking_id  = $lite_container.data('amazon-primary-tracking-id');
		let amazon_tracking_id          = $lite_container.data('amazon-tracking-id');
		let disable_amazon_notification = $lite_container.data('disable-amazon-notification') === 1;

		if ( disable_amazon_notification ) {
			return;
		}

		if ( 0 === amazon_tracking_id.length && is_amazon_link ) {
			let json_data = [
				{
					is_amazon_link             : is_amazon_link,
					amazon_primary_tracking_id : amazon_primary_tracking_id,
					amazon_tracking_id         : amazon_tracking_id,
				}
			];

			lasso_lite_helper.inject_to_template(jQuery("#lasso_lite_notifications"), 'amazon-url-detected', json_data);
		}
	}

	function save_tracking_id() {
		let amazon_tracking_id = jQuery('#btn-tracking-id-save').data('tracking-id');

		jQuery.ajax({
			url: lassoLiteOptionsData.ajax_url,
			type: 'post',
			data: {
				action             : 'lasso_lite_save_amazon_tracking_id',
				nonce              : lassoLiteOptionsData.optionsNonce,
				amazon_tracking_id : amazon_tracking_id
			},
			beforeSend: function (xhr) {
				jQuery('#btn-tracking-id-save').html(get_loading_image_small());
			}
		})
		.done(function (res) {
			if ( res.success ) {
				jQuery('#btn-tracking-id-save').html("Yes");
				jQuery('#amazon-url-detected').collapse('hide');
				lasso_lite_helper.do_notification(res.data.msg, 'green');
			} else {
				lasso_lite_helper.do_notification('Something went wrong.', 'red');
			}
		})
		.fail(function (xhr, status, error) {
			lasso_lite_helper.do_notification( error, 'red');
		});
	}

	function get_loading_image_small() {
		return '<div class="loader-small"></div>';
	}

	function update_price_section() {
		let price_date = jQuery(".lasso-date");
		let price = jQuery("#price").val().trim();
		let price_wrapper = jQuery(".lasso-price");
		let lasso_price_value = jQuery(".lasso-price-value");
		let is_checked = jQuery('#show_pricing').is(":checked");

		// ? Show/Hide price
		if ( is_checked && price ) {
			price_date.removeClass('lasso-none');
			price_wrapper.removeClass('lasso-none');
			lasso_price_value.removeClass('lasso-none');
			
			lasso_price_value.find('.latest-price').text(price);
		} else {
			price_date.addClass('lasso-none');
			price_wrapper.addClass('lasso-none');
			lasso_price_value.addClass('lasso-none');
		}

		// If not show price, we set price input to readonly
		if ( ! is_checked ) {
			jQuery('#price').prop('readonly', true);
		} else {
			jQuery('#price').prop('readonly', false);
		}
	}

	function update_badge_section() {
		let badge_text = jQuery("#badge_text").val().trim();

		if ( badge_text ) {
			jQuery('.lasso-display .lasso-badge').removeClass('lasso-none').text(badge_text);
		} else {
			jQuery('.lasso-display .lasso-badge').addClass('lasso-none').text(badge_text);
		}
	}

	function show_disclosure_section() {
		let is_show_disclosure_checked = jQuery('#show_disclosure').is(":checked");

		if ( is_show_disclosure_checked ) {
			jQuery('.lasso-display .lasso-disclosure').removeClass('lasso-none');
		} else {
			jQuery('.lasso-display .lasso-disclosure').addClass('lasso-none');
		}
	}

	function product_duplicate_notification() {
		// Show warning message
		let is_duplicate_url = lasso_lite_helper.get_url_parameter('is_duplicate');
		if ( is_duplicate_url === 'true' ) {
			lasso_lite_helper.do_notification('This product already exists. Please update the Primary URL.', 'orange');
		}
	}
});
