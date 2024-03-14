jQuery(document).ready(function() {
	jQuery(document)
		.on('click', '#onboarding_container .tab-item .next-step', go_to_next_step)
		.on('click', '#onboarding_container .progressbar_container .progressbar li', access_step);
});

function go_to_next_step() {
	go_to_next_step_action(this);
}

function go_to_next_step_action( tab_item_child_element ) {
	let current_tab_container = jQuery(tab_item_child_element).closest('.tab-item');
	let next_tab_container    = current_tab_container.next('.tab-item');

	if ( next_tab_container.length ) {
		current_tab_container.addClass('d-none');
		next_tab_container.removeClass('d-none');

		window.scrollTo(0, 0);
	}
}

function access_step() {
	let access_step = jQuery(this).data('step');

	if ( access_step ) {
		let access_step_tab = jQuery('.tab-item[data-step="' + access_step + '"]');
		if ( access_step_tab.length ) {
			jQuery('#onboarding_container .tab-item').addClass('d-none');
			access_step_tab.removeClass('d-none');
		}

	}
}

