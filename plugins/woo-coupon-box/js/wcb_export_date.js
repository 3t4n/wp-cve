'use strict';

jQuery(document).ready(function () {
	if (jQuery('[type="date"]').prop('type') != 'date') {
		jQuery('[type="date"]').datepicker({dateFormat: 'yy-mm-dd'});
	}

	jQuery('.vi-ui.dropdown').dropdown();

	jQuery('.wcb_post_date').dependsOn({
		'.wcb_select_export select': {
			values: ['1']
		}
	});

	jQuery('.wcb_post_campaign').dependsOn({
		'.wcb_select_export select': {
			values: ['2']
		}
	});
});