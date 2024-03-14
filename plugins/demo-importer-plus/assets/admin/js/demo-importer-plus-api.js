(function ($) {
	DemoImporterPlusAPI = {
		/**
		 * API Request
		 */
		_api_request: function (args, callback) {
			var params = {
				method: "GET",
				cache: "default",
			};

			if (demoImporterPlusVars.headers) {
				params["headers"] = demoImporterPlusVars.headers;
			}

			fetch(demoImporterPlusVars.ApiURL + args.slug, params)
				.then((response) => {
					if (response.status === 200) {
						return response.json().then((items) => ({
							items: items,
							items_count: response.headers.get("x-wp-total"),
							item_pages: response.headers.get("x-wp-totalpages"),
						}));
					} else {
						$(document).trigger(
							"demo-importer-plus-api-request-error"
						);
						return response.json();
					}
				})
				.then((data) => {
					if ("object" === typeof data) {
						data["args"] = args;
						if (data.args.id) {
							demoImporterPlusVars.stored_data[args.id] = $.merge(
								demoImporterPlusVars.stored_data[data.args.id],
								data.items
							);
						}
						data["args"]["favorites"] =
							demoImporterPlusVars.favorite_data;

						if (
							"undefined" !== typeof args.trigger &&
							"" !== args.trigger
						) {
							$(document).trigger(args.trigger, [data]);
						}

						if (callback && typeof callback == "function") {
							callback(data);
						}
					}
				});
		},

		/**
		 * API Request
		 */
		_api_single_request: function (args, callback) {
			var params = {
				method: "GET",
				cache: "default",
			};

			if (demoImporterPlusVars.headers) {
				params["headers"] = demoImporterPlusVars.headers;
			}

			fetch(demoImporterPlusVars.ApiURL + args.slug, params)
				.then((response) => {
					if (response.status === 200) {
						return response.json();
					} else {
						$(document).trigger(
							"demo-importer-plus-api-request-error"
						);
						return response.json();
					}
				})
				.then((data) => {
					if ("object" === typeof data) {

						if ( "undefined" !== typeof args.trigger && "" !== args.trigger ) {
							$(document).trigger(args.trigger, [data]);
						}

						if (callback && typeof callback == "function") {
							callback(data);
						}
					}
				});
		},
	};
})(jQuery);
