(function( $ ) {
	'use strict';
	// Saving Data With Ajax Request
	$(document).ready(() => {
		// Tabs hide and show
		$(".marvy-tabs .tab").on("click", function (event) {
			event.preventDefault();
			$('.marvy-tabs li').removeClass('tab-active');
			$(this).parent().addClass('tab-active');
			$('.marvy-tab .marvy-tab-detail').hide();
			$($(this).attr('href')).show();
		});

		$('.marvy-tab .marvy-tab-detail').hide();
		$($('.marvy-tabs .tab-active .tab').attr('href')).show();

		$(".marvy-setting-save").on("click", function (event) {
			event.preventDefault();
			let _this = $(this);
			$.ajax({
				url: localize.ajaxurl,
				type: "post",
				data: {
					action: "save_marvy_settings",
					_ajax_nonce: localize.nonce,
					fields: $("form#marvy-settings").serialize(),
				},
				beforeSend: function () {
					_this.html(
						'<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 48 48"><circle cx="24" cy="4" r="4" fill="#fff"/><circle cx="12.19" cy="7.86" r="3.7" fill="#fffbf2"/><circle cx="5.02" cy="17.68" r="3.4" fill="#fef7e4"/><circle cx="5.02" cy="30.32" r="3.1" fill="#fef3d7"/><circle cx="12.19" cy="40.14" r="2.8" fill="#feefc9"/><circle cx="24" cy="44" r="2.5" fill="#feebbc"/><circle cx="35.81" cy="40.14" r="2.2" fill="#fde7af"/><circle cx="42.98" cy="30.32" r="1.9" fill="#fde3a1"/><circle cx="42.98" cy="17.68" r="1.6" fill="#fddf94"/><circle cx="35.81" cy="7.86" r="1.3" fill="#fcdb86"/></svg><span>Saving Data..</span>'
					);
				},
				success: function (response) {
					_this.html("Save Settings");
					if(response === true) {
						setTimeout(function () {
							Swal.fire({
								icon: 'success',
								title: "Settings has been saved!",
								showConfirmButton: false,
								timer: 2000,
							});
						}, 500);
					}else{
						Swal.fire({
							icon: "error",
							title: "Oops...",
							text: "Something went wrong!",
						});
					}
				},
				error: function () {
					_this.html("Save Settings");
					Swal.fire({
						icon: "error",
						title: "Oops...",
						text: "Something went wrong!",
					});
				}
			});
		});

	});
})( jQuery );
