var iHomefinderShortcodeSelector = {
	onBuildShortcode: function(shortcode, theForm) {
		console.log(shortcode);
	},
	init: function() {
	},
	insertFeaturedListings: function(theForm) {
		var slug = this.getFieldValue(theForm.slug);
		var parameters = {
			sortBy: this.getFieldValue(theForm.sortBy),
			propertyType: this.getFieldValue(theForm.propertyType),
			displayType: this.getFieldValue(theForm.displayType),
			resultsPerPage: this.getFieldValue(theForm.resultsPerPage),
			header: this.getFieldValue(theForm.header),
			includeMap: this.getFieldValue(theForm.includeMap),
			status: this.getFieldValue(theForm.status)
		};
		var shortcode = this.buildShortcode(slug, parameters);
		this.onBuildShortcode(shortcode, theForm);
	},
	insertHotSheetListingReport: function(theForm) {
		var slug = this.getFieldValue(theForm.slug);
		var parameters = {
			id: this.getFieldValue(theForm.hotSheetId),
			sortBy: this.getFieldValue(theForm.sortBy),
			displayType: this.getFieldValue(theForm.displayType),
			resultsPerPage: this.getFieldValue(theForm.resultsPerPage),
			header: this.getFieldValue(theForm.header),
			includeMap: this.getFieldValue(theForm.includeMap),
			status: this.getFieldValue(theForm.status)
		};
		var shortcode = this.buildShortcode(slug, parameters);
		this.onBuildShortcode(shortcode, theForm);
	},
	insertHotSheetOpenHomeReport: function(theForm) {
		var slug = this.getFieldValue(theForm.slug);
		var parameters = {
			id: this.getFieldValue(theForm.hotSheetId),
			sortBy: this.getFieldValue(theForm.sortBy),
			header: this.getFieldValue(theForm.header),
			includeMap: this.getFieldValue(theForm.includeMap)
		};
		var shortcode = this.buildShortcode(slug, parameters);
		this.onBuildShortcode(shortcode, theForm);
	},
	insertHotSheetMarketReport: function(theForm) {
		var slug = this.getFieldValue(theForm.slug);
		var parameters = {
			id: this.getFieldValue(theForm.hotSheetId),
			header: this.getFieldValue(theForm.header),
			columns: this.getFieldValue(theForm.columns)
		};
		var shortcode = this.buildShortcode(slug, parameters);
		this.onBuildShortcode(shortcode, theForm);
	},
	insertSearchResults: function(theForm) {
		var slug = this.getFieldValue(theForm.slug);
		var parameters = {
			cityId: this.getFieldValue(theForm.cityId),
			propertyType: this.getFieldValue(theForm.propertyType),
			bed: this.getFieldValue(theForm.bed),
			bath: this.getFieldValue(theForm.bath),
			minPrice: this.getFieldValue(theForm.minPrice),
			maxPrice: this.getFieldValue(theForm.maxPrice),
			sortBy: this.getFieldValue(theForm.sortBy),
			displayType: this.getFieldValue(theForm.displayType),
			resultsPerPage: this.getFieldValue(theForm.resultsPerPage),
			header: this.getFieldValue(theForm.header),
			includeMap: this.getFieldValue(theForm.includeMap),
			status: this.getFieldValue(theForm.status)
		};
		var shortcode = this.buildShortcode(slug, parameters);
		this.onBuildShortcode(shortcode, theForm);
	},
	insertGallerySlider: function(theForm) {
		var slug = this.getFieldValue(theForm.slug);
		var parameters = {};
		parameters["id"] = this.getFieldValue(theForm.hotSheetId);
		if(theForm.fitToWidth == undefined || theForm.fitToWidth.checked == false) {
			parameters["width"] = this.getFieldValue(theForm.width);
		}
		parameters["height"] = this.getFieldValue(theForm.height);
		parameters["rows"] = this.getFieldValue(theForm.rows);
		parameters["nav"] = this.getFieldValue(theForm.nav);
		parameters["style"] = this.getFieldValue(theForm.style);
		parameters["columns"] = this.getFieldValue(theForm.columns);
		parameters["effect"] = this.getFieldValue(theForm.effect);
		parameters["auto"] = this.getFieldValue(theForm.auto);
		parameters["interval"] = this.getFieldValue(theForm.interval);
		parameters["status"] = this.getFieldValue(theForm.status);
		parameters["sortBy"] = this.getFieldValue(theForm.sortBy);
		parameters["maxResults"] = this.getFieldValue(theForm.maxResults);
		var shortcode = this.buildShortcode(slug, parameters);
		this.onBuildShortcode(shortcode, theForm);
	},
	insertValuationWidget: function(theForm) {
		var slug = this.getFieldValue(theForm.slug);
		var parameters = {};
		parameters["style"] = this.getFieldValue(theForm.style);
		var shortcode = this.buildShortcode(slug, parameters);
		this.onBuildShortcode(shortcode, theForm);
	},
	insertOrganizerLoginWidget: function(theForm) {
		var slug = this.getFieldValue(theForm.slug);
		var parameters = {};
		parameters["style"] = this.getFieldValue(theForm.style);
		var shortcode = this.buildShortcode(slug, parameters);
		this.onBuildShortcode(shortcode, theForm);
	},
	insertRegistrationWidget: function(theForm) {
		var slug = this.getFieldValue(theForm.slug);
		var parameters = {};
		parameters["url"] = this.getFieldValue(theForm.url);
		parameters["buttonText"] = this.getFieldValue(theForm.buttonText);
		var shortcode = this.buildShortcode(slug, parameters);
		this.onBuildShortcode(shortcode, theForm);
	},
	insertQuickSearch: function(theForm) {
		var slug = this.getFieldValue(theForm.slug);
		var style = this.getFieldValue(theForm.style);
		var showPropertyType;
		if (style !== 'universal') {
			showPropertyType = this.getFieldValue(theForm.showPropertyType);
		}
		var parameters = {
			style: style,
			showPropertyType: showPropertyType,
		};
		var shortcode = this.buildShortcode(slug, parameters);
		this.onBuildShortcode(shortcode, theForm);
	},
	insertSearchByAddress: function(theForm) {
		var slug = this.getFieldValue(theForm.slug);
		var parameters = {
			style: this.getFieldValue(theForm.style)
		};
		var shortcode = this.buildShortcode(slug, parameters);
		this.onBuildShortcode(shortcode, theForm);
	},
	insertSearchByListingId: function(theForm) {
		var slug = this.getFieldValue(theForm.slug);
		var shortcode = this.buildShortcode(slug);
		this.onBuildShortcode(shortcode, theForm);
	},
	insertBasicSearch: function(theForm) {
		var slug = this.getFieldValue(theForm.slug);
		var shortcode = this.buildShortcode(slug);
		this.onBuildShortcode(shortcode, theForm);
	},
	insertAdvancedSearch: function(theForm) {
		var parameters = {};
		if(theForm.boardId != undefined) {
			parameters["boardId"] = this.getFieldValue(theForm.boardId);
		}
		var slug = this.getFieldValue(theForm.slug);
		var shortcode = this.buildShortcode(slug, parameters);
		this.onBuildShortcode(shortcode, theForm);
	},
	insertOrganizerLogin: function(theForm) {
		var slug = this.getFieldValue(theForm.slug);
		var parameters;
		var shortcode = this.buildShortcode(slug, parameters);
		this.onBuildShortcode(shortcode, theForm);
	},
	insertEmailAlerts: function(theForm) {
		var slug = this.getFieldValue(theForm.slug);
		var parameters;
		var shortcode = this.buildShortcode(slug, parameters);
		this.onBuildShortcode(shortcode, theForm);
	},
	insertValuationForm: function(theForm) {
		var slug = this.getFieldValue(theForm.slug);
		var parameters;
		var shortcode = this.buildShortcode(slug, parameters);
		this.onBuildShortcode(shortcode, theForm);
	},
	insertMortgageCalculator: function(theForm) {
		var slug = this.getFieldValue(theForm.slug);
		var parameters;
		var shortcode = this.buildShortcode(slug, parameters);
		this.onBuildShortcode(shortcode, theForm);
	},
	insertContactForm: function(theForm) {
		var slug = this.getFieldValue(theForm.slug);
		var parameters;
		var shortcode = this.buildShortcode(slug, parameters);
		this.onBuildShortcode(shortcode, theForm);
	},
	insertMapSearch: function(theForm) {
		var slug = this.getFieldValue(theForm.slug);
		var parameters = {};
		if(theForm.fitToWidth == undefined || theForm.fitToWidth.checked == false) {
			parameters["width"] = this.getFieldValue(theForm.width);
		}
		parameters["height"] = this.getFieldValue(theForm.height);
		parameters["centerlat"] = this.getFieldValue(theForm.centerlat);
		parameters["centerlong"] = this.getFieldValue(theForm.centerlong);
		parameters["address"] = this.getFieldValue(theForm.address);
		parameters["zoom"] = this.getFieldValue(theForm.zoom);
		var shortcode = this.buildShortcode(slug, parameters);
		this.onBuildShortcode(shortcode, theForm);
	},
	insertAgentDetail: function(theForm) {
		var slug = this.getFieldValue(theForm.slug);
		var parameters = {
			agentId: this.getFieldValue(theForm.agentId)
		};
		var shortcode = this.buildShortcode(slug, parameters);
		this.onBuildShortcode(shortcode, theForm);
	},
	insertAgentList: function(theForm) {
		var slug = this.getFieldValue(theForm.slug);
		var shortcode = this.buildShortcode(slug);
		this.onBuildShortcode(shortcode, theForm);
	},
	insertOfficeList: function(theForm) {
		var slug = this.getFieldValue(theForm.slug);
		var shortcode = this.buildShortcode(slug);
		this.onBuildShortcode(shortcode, theForm);
	},
	insertAgentListings: function(theForm) {
		var slug = this.getFieldValue(theForm.slug);
		var parameters = {
			agentId: this.getFieldValue(theForm.agentId)
		};
		var shortcode = this.buildShortcode(slug, parameters);
		this.onBuildShortcode(shortcode, theForm);
	},
	insertOfficeListings: function(theForm) {
		var slug = this.getFieldValue(theForm.slug);
		var parameters = {
			officeId: this.getFieldValue(theForm.officeId)
		};
		var shortcode = this.buildShortcode(slug, parameters);
		this.onBuildShortcode(shortcode, theForm);
	},
	insertHotSheetReportSignup: function(theForm) {
		var slug = this.getFieldValue(theForm.slug);
		var parameters = {
			id: this.getFieldValue(theForm.hotSheetId),
			reportType: this.getFieldValue(theForm.hotSheetReportType)
		};
		var shortcode = this.buildShortcode(slug, parameters);
		this.onBuildShortcode(shortcode, theForm);
	},
	buildShortcode: function(slug, parameters) {
		var result = "[" + slug;
		if(parameters) {
			for(var key in parameters) {
				var value = parameters[key];
				if(value != undefined && value != null && value.length != 0) {
					result += " " + key + "=\"" + value + "\"";
				}
			}
		}
		result += "]";
		return result;
	},
	getFieldValue: function(formField) {
		var value = null;
		if(formField != undefined) {
			if(formField.type == "checkbox") {
				value = formField.checked;
			} else {
				value = formField.value;
			}
		}
		if (value === undefined || value === null || value.length === 0) {
			return null;
		} else {
			return value;
		}
	}
}
