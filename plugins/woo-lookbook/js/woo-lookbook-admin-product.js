jQuery(document).ready(function () {
	/*Select2 search product*/
	jQuery('.wlb-lookbook-search').select2({
		placeholder           : "Please fill in your lookbook title",
		ajax                  : {
			url           : "admin-ajax.php?action=wlb_search_lookbook",
			dataType      : 'json',
			type          : "GET",
			quietMillis   : 50,
			delay         : 250,
			data          : function (params) {
				return {
					keyword: params.term,
					nonce: _wlb_params.nonce,
				};
			},
			processResults: function (data) {
				return {
					results: data
				};
			},
			cache         : true
		},
		escapeMarkup          : function (markup) {
			return markup;
		}, // let our custom formatter work
		minimumInputLength    : 1,
		maximumSelectionLength: 2
	});
})