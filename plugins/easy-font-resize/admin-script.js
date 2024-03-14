jQuery(document).ready(function ($) {
	let iconContainer = $('#wpavefrsz_instructions_selected_icon');
	let iconInput = $('input[name="wpavefrsz_instructions_icon"]');

	$('#wpavefrsz_instructions_icon').click(function () {
		let upload = wp.media({
			title: 'Add Icon',
			multiple: false
		}).on('select', function () {
			let select = upload.state().get('selection');
			let attach = select.first().toJSON();

			iconContainer.attr('src', attach.url).show();
			iconInput.val(attach.id);
		}).open();
	});

	$('#wpavefrsz_instructions_icon_remove').on('click', function (e) {
		e.preventDefault();

		iconContainer.hide();
		iconInput.val('');
	});
});