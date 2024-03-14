jQuery(document).ready(function($) {

	//
	// prevent Gravity Forms form being submitted twice++
	//

	var gformSubmitted = false;
	var gformFormId = -1;
	var currentFormExcluded = false;
	jQuery(document).bind('gform_post_render', function(event, form_id, current_page) {
		console.log('gform_post_render: ' + form_id);
		console.log($(this));
		gformFormId = form_id;

		console.log(gfpd_strings.excluded_form_ids);
		var excluded = gfpd_strings.excluded_form_ids.split(',');
		console.log(excluded);
		var excludedInts = excluded.map(function(x) {
			return parseInt(x, 10);
		});
		var idxCurrentFormId = excludedInts.indexOf(gformFormId);
		console.log("prevent dupplicates " + idxCurrentFormId);
		currentFormExcluded = idxCurrentFormId >= 0;
		console.log("prevent dupplicates " + currentFormExcluded);
		if (currentFormExcluded) {
			console.warn('gfpd: form ' + form_id + ' EXcluded from process');
		} else {
			console.log('gfpd: form ' + form_id + ' included from process')
		}

	});

	$(".gform_wrapper form").submit(function(event) {

		console.log("prevent dupplicates " + currentFormExcluded);

		if (currentFormExcluded) {
			// idle
			console.log("currentExcluded: " + currentFormExcluded);
			return;
		} else {
			console.log(currentFormExcluded + " current form to be processed by prevent duplicates");
			if (gformSubmitted) {
				event.preventDefault();
			} else {
				var pendingUploads = false;
				if (typeof gfMultiFileUploader !== 'undefined') {

					$.each(gfMultiFileUploader.uploaders, function(i, uploader) {
						if (uploader.total.queued > 0) {
							pendingUploads = true;
							return false;
						}
					});
				} else {
					console.warn("No gfMultiFileUploader");
				}

				if (pendingUploads) {

					return false;
				} else {
					gformSubmitted = true;
					var outputText = gfpd_strings.button_message;
					$("input[type='submit']", this).val(outputText);
				}

			}
		}

	});

});