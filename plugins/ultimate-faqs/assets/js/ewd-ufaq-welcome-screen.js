jQuery(document).ready(function() {
	jQuery('.ewd-ufaq-welcome-screen-box h2').on('click', function() {
		var page = jQuery(this).parent().data('screen');
		EWD_UFAQ_Toggle_Welcome_Page(page);
	});

	jQuery('.ewd-ufaq-welcome-screen-next-button').on('click', function() {
		var page = jQuery(this).data('nextaction');
		EWD_UFAQ_Toggle_Welcome_Page(page);
	});

	jQuery('.ewd-ufaq-welcome-screen-previous-button').on('click', function() {
		var page = jQuery(this).data('previousaction');
		EWD_UFAQ_Toggle_Welcome_Page(page);
	});

	jQuery('.ewd-ufaq-welcome-screen-add-category-button').on('click', function() {

		jQuery('.ewd-ufaq-welcome-screen-show-created-categories').show();

		var category_name = jQuery('.ewd-ufaq-welcome-screen-add-category-name input').val();
		var category_description = jQuery('.ewd-ufaq-welcome-screen-add-category-description textarea').val();

		jQuery('.ewd-ufaq-welcome-screen-add-category-name input').val('');
		jQuery('.ewd-ufaq-welcome-screen-add-category-description textarea').val('');

		var params = {};

		params.nonce  = ewd_ufaq_getting_started.nonce;
		params.action = 'ewd_ufaq_welcome_add_category';
		params.category_name = category_name;
		params.category_description = category_description;

		var data = jQuery.param( params );
		jQuery.post(ajaxurl, data, function(response) {
			var HTML = '<tr class="ewd-ufaq-welcome-screen-category">';
			HTML += '<td class="ewd-ufaq-welcome-screen-category-name">' + category_name + '</td>';
			HTML += '<td class="ewd-ufaq-welcome-screen-category-description">' + category_description + '</td>';
			HTML += '</tr>';

			jQuery('.ewd-ufaq-welcome-screen-show-created-categories').append(HTML);

			var category = JSON.parse(response); 
			jQuery('.ewd-ufaq-welcome-screen-add-faq-category select').append('<option value="' + category.category_id + '">' + category.category_name + '</option>');
		});
	});

	jQuery('.ewd-ufaq-welcome-screen-add-faq-page-button').on('click', function() {
		var faq_page_title = jQuery('.ewd-ufaq-welcome-screen-add-faq-page-name input').val();

		EWD_UFAQ_Toggle_Welcome_Page('options');

		var params = {};

		params.nonce = ewd_ufaq_getting_started.nonce;
		params.faq_page_title = faq_page_title;
		params.action = 'ewd_ufaq_welcome_add_faq_page';

		var data = jQuery.param( params );
		jQuery.post(ajaxurl, data, function(response) {});
	});

	jQuery('.ewd-ufaq-welcome-screen-save-options-button').on('click', function() {
		var faq_accordion = jQuery('input[name="faq_accordion"]:checked').val(); 
		var faq_toggle = jQuery('input[name="faq_toggle"]:checked').val(); 
		var group_by_category = jQuery('input[name="group_by_category"]:checked').val(); 
		var order_by_setting = jQuery('select[name="order_by_setting"]').val();

		var params = {};

		params.nonce  = ewd_ufaq_getting_started.nonce;
		params.action = 'ewd_ufaq_welcome_set_options';
		params.faq_accordion = faq_accordion;
		params.faq_toggle    = faq_toggle;
		params.group_by_category = group_by_category;
		params.order_by_setting  = order_by_setting;

		var data = jQuery.param( params );

		jQuery.post(ajaxurl, data, function(response) {
			jQuery('.ewd-ufaq-welcome-screen-save-options-button').after('<div class="ewd-ufaq-save-message"><div class="ewd-ufaq-save-message-inside">Options have been saved.</div></div>');
			jQuery('.ewd-ufaq-save-message').delay(2000).fadeOut(400, function() {jQuery('.ewd-ufaq-save-message').remove();});
		});
	});

	jQuery('.ewd-ufaq-welcome-screen-add-faq-button').on('click', function() {

		jQuery('.ewd-ufaq-welcome-screen-show-created-faqs').show();

		var faq_question = jQuery('.ewd-ufaq-welcome-screen-add-faq-question input').val();
		var faq_answer = jQuery('.ewd-ufaq-welcome-screen-add-faq-answer textarea').val();
		var faq_category = jQuery('.ewd-ufaq-welcome-screen-add-faq-category select').val();
		var faq_category_name = jQuery('.ewd-ufaq-welcome-screen-add-faq-category select option:selected').text();

		jQuery('.ewd-ufaq-welcome-screen-add-faq-question input').val('');
		jQuery('.ewd-ufaq-welcome-screen-add-faq-answer textarea').val('');
		jQuery('.ewd-ufaq-welcome-screen-add-faq-category select').val('');

		var params = {};

		params.nonce  = ewd_ufaq_getting_started.nonce;
		params.action = 'ewd_ufaq_welcome_add_faq';
		params.faq_question = faq_question;
		params.faq_answer   = faq_answer;
		params.faq_category = faq_category;

		var data = jQuery.param( params );
		jQuery.post(ajaxurl, data, function(response) {
			var HTML = '<tr class="ewd-ufaq-welcome-screen-faq">';
			HTML += '<td class="ewd-ufaq-welcome-screen-faq-question">' + faq_question + '</td>';
			HTML += '<td class="ewd-ufaq-welcome-screen-faq-answer">' + faq_answer + '</td>';
			HTML += '<td class="ewd-ufaq-welcome-screen-faq-category">' + faq_category_name + '</td>';
			HTML += '</tr>';

			jQuery('.ewd-ufaq-welcome-screen-show-created-faqs').append(HTML);
		});
	});
});

function EWD_UFAQ_Toggle_Welcome_Page(page) {
	jQuery('.ewd-ufaq-welcome-screen-box').removeClass('ewd-ufaq-welcome-screen-open');
	jQuery('.ewd-ufaq-welcome-screen-' + page).addClass('ewd-ufaq-welcome-screen-open');
}