jQuery(document).ready(function ($) {
	var modal = $('.packeta-modal');

	// Open modal
	$('body').on('click', '#packeta-details-link', function () {
		order_id = $(this).data('order_id');
		weight = $(this).data('weight');
		$('form#packeta-details #order_id').val(order_id);
		$('form#packeta-details #weight').val(weight);
		modal.show()
	})

	// Close modal
	$('body').on('click', '#close-packeta-modal', function () {
		modal.hide();
	})

	// packeta-details Ajax
	$('body').on('submit', 'form#packeta-details', function (e) {
		e.preventDefault()

		const data = $(this).serialize();
		const order_id = $(this).find('#order_id').val();
		const weight = $(this).find('#weight').val();

		$.ajax({
			url: packetaAdmin.restUrl + '/packeta/order-details',
			method: 'POST',
			beforeSend: function (xhr) {
				xhr.setRequestHeader('X-WP-Nonce', packetaAdmin.nonce);
				$('form#packeta-details').addClass('update');
			},
			data,
			success: function (response) {
				$('.packeta__weight[data-id="' + order_id + '"]').html(weight);
				modal.hide();
			},

			error: function (response) {
				console.warn(response);
			}
		}, 'json');
	})
});
