(function($) {
	"use strict";
	var inputLoader = '<div class="input-ajax-loader"><div class="input-ajax-loader-holder"><div></div><div></div><div></div><div></div><div></div></div></div>';
	window.directorypress_sort_autocomplete_items = function(a, b) {
		if (typeof a.is_listing != "undefined" && a.is_listing && (typeof b.is_listing == "undefined" || !b.is_listing)) {
			return -1;
		} else if (typeof a.is_listing == "undefined" || !a.is_listing) {
			if (a.term_in_name && !b.term_in_name) {
				return -1;
			} else if (!a.term_in_name && b.term_in_name) {
				return 1;
			} else if (a.is_term && !b.is_term) {
				return -1;
			} else if (!a.is_term && b.is_term) {
				return 1;
			} else if (a.parents == '' && b.parents != '') {
				return -1;
			} else if (a.parents != '' && b.parents == '') {
				return 1;
			} else if (a.name > b.name) {
				return 1;
			} else if (b.name > a.name) {
				return -1;
			} else {
				return 0;
			}
		}
	}
	
	if (typeof $.ui != 'undefined') {
		// search suggestions links
		$(document).on("click", ".directorypress-search-suggestions a", function() {
			var input = $(this).parents(".directorypress-search-input-field-wrap").find(".directorypress-default-field");
			var value = $(this).text();

			input.val(value)
			.trigger("focus")
			.trigger("change");
			
			if (input.hasClass('ui-autocomplete-input')) {
				input.autocomplete("search", value);
			}
		});
		// redirect to listing
		$(document).on("click", ".directorypress-dropmenubox a", function() {
			if ($(this).attr("target") == "_blank") {
				window.open($(this).attr("href"), '_blank');
			} else {
				window.location = $(this).attr("href");
			}
		});
		// correct menu width
		if(typeof $.ui.autocomplete != 'undefined'){
			$.ui.autocomplete.prototype._resizeMenu = function () {
				var ul = this.menu.element;
				ul.outerWidth(this.element.outerWidth());
			}
		}
		
		$(document).on('change paste keyup', '.directorypress-default-field', function() {
			var input = $(this);
			if (input.val()) {
				input.parent().find(".directorypress-dropmenubox-button").addClass("directorypress-search-input-reset");
			} else {
				input.parent().find(".directorypress-dropmenubox-button").removeClass("directorypress-search-input-reset");
			}
		});

		window.catcombo = $.widget("custom.catcombo", {
			input_icon_class: "glyphicon-search",
			cache: new Object(),

			_create: function() {
				this.wrapper = $("<div>")
				.addClass("directorypress-cat-autocomplete-dropmenu")
				.insertAfter(this.element);

				this.element.hide();
				this._createAutocomplete();
				this._createShowAllButton();
			},
			
			_appendWrapper: function() {
				// when combobox is placed on has_sticky map or has_sticky search - append to its wrapper
				if (this.wrapper.parents(".directorypress-maps-container.directorypress-has-stickyscroll, .directorypress-search-form.directorypress-has-stickyscroll").length) {
					var append_to = this.wrapper;
				} else {
					var append_to = null;
				}
				return append_to;
			},
			
			_autocompleteWithOptions: function(input) {
				input.autocomplete({
					delay: 300,
					minLength: 0,
					appendTo: this._appendWrapper(),
					source: $.proxy(this, "_source"),
					open: function(event, ui) {
						if (navigator.userAgent.match(/(iPod|iPhone|iPad)/)) {
							$('.ui-autocomplete').off('menufocus hover mouseover mouseenter');
						}
					}
				});
			},
			
			_autocompleteRenderItem: function(input) {
				input.autocomplete("widget").addClass("directorypress-dropmenubox");
				
				input.autocomplete("instance")._renderItem = function(ul, item) {
					var label = item.label;
					
					var counter_markup = '';
					if (typeof item.count != "undefined") {
						counter_markup = '<span class="directorypress-dropmenubox-count">' + item.count + '</span>';
					}
					
					var item_class = "directorypress-search-dropmenubox";
					if (typeof item.is_term != "undefined" && item.is_term) {
						item_class = item_class + " directorypress-search-term-dropmenubox";
					}
					if (typeof item.is_listing != "undefined" && item.is_listing) {
						item_class = item_class + " directorypress-search-listing-dropmenubox";
					}
					var item_parent_class = 'parent';
					if(item.parent_id){
						item_parent_class = 'child'
					}
					
					
					var li = $("<li>", {
						class: item_parent_class
					}),
					wrapper = $("<div>", {
						html: label + counter_markup,
						class: item_class
					});

					if (item.icon && item.icontype == 'img') {
						var icon_class = "directorypress-ui-icon ";
						$("<span>", {
							style: "background-image: url('" + item.icon + "'); background-size: 30px; background-repeat:no-repeat;",
							class: icon_class
						})
						.appendTo(wrapper);
					}else if(item.icon && item.icontype == 'font'){
						if(item.fonticolor != ''){
							var fontcolor = item.fonticolor;
						}else{
							var fontcolor = '';
						}
						var icon_class = "directorypress-ui-icon font-icon " + item.icon;
						$("<span>", {
							style:"color:" + fontcolor + ";",
							class: icon_class
						})
						.appendTo(wrapper);
					}else if (item.icon) {
						var icon_class = "directorypress-ui-icon ";
						$("<span>", {
							style: "background-image: url('" + item.icon + "'); background-size: 30px; background-repeat:no-repeat;",
							class: icon_class
						})
						.appendTo(wrapper);
					}

					if (item.sublabel) {
						var sublabel = item.sublabel;

						$("<span>")
						.html(sublabel)
						.addClass("directorypress-search-innerlab-dropmenubox")
						.appendTo(wrapper);
					} else {
						wrapper.addClass("directorypress-search-parent-dropmenubox");
					}
					
					return li.append(wrapper).appendTo(ul);
					
				};
			},
			
			_openMobileKeyboard: function() {
				if (!this.element.data("autocomplete-name") && screen.height <= 768) {
					return true;
				} else {
					return false;
				}
			},
			
			_createAutocomplete: function() {
				var selected = this.element.find("[data-selected=\"selected\"]");
				if (this.element.data("autocomplete-name") && this.element.data("autocomplete-value")) {
					var value = this.element.data("autocomplete-value");
				} else {
					var value = selected.data("name") ? selected.data("name") : "";
				}

				this.input = $("<input>", {
							name: this.element.data("autocomplete-name") ? this.element.data("autocomplete-name") : "",
							readonly: this._openMobileKeyboard() ? "true" : false
				})
				.appendTo(this.wrapper)
				.val(value)
				.attr("placeholder", this.element.data("placeholder"))
				.addClass("form-control directorypress-default-field");
				
				this._autocompleteWithOptions(this.input);
				this._autocompleteRenderItem(this.input);

				var input = this.input;
				var id = this.element.data("id");

				this._on(input, {
					autocompleteselect: function(event, ui) {
						this._trigger("select", event, {
							item: ui.item
						});

						if (ui.item.is_term) {
							var name = ui.item.name;
							$('#selected_tax\\['+id+'\\]').val(ui.item.value).trigger("change");
							$('#selected_tax_text\\['+id+'\\]').val(ui.item.full_value).trigger("change");
						} else {
							var name = ui.item.value;
							$('#selected_tax\\['+id+'\\]').val('');
							$('#selected_tax_text\\['+id+'\\]').val('');
						}
						name = $('<textarea />').html(name).text(); // HTML Entity Decode
						this.input.val(name);
						this.input.trigger('change');
						
						var form = this.input.parents("form");
						form.trigger("submit");

						event.preventDefault();
					},
					autocompletefocus: function(event, ui) {
						event.preventDefault();
					},
					click: function(event, ui) {
						if (this._openMobileKeyboard()) {
							input.trigger("focus");
							input.autocomplete("search", input.val());
							
							if ($("body").hasClass("directorypress-touch")) {
								this._scrollToInputTop(input);
							}
						} else {
							input.trigger("focusout");
							input.autocomplete("search", '');
						}
					},
					autocompletesearch: function(event, ui) {
						if (input.val() == '') {
							$('#selected_tax\\['+id+'\\]').val('');
							$('#selected_tax_text\\['+id+'\\]').val('');
						}
					}
				});
				
				$(document).on("submit", "form", function() {
					input.autocomplete("close");
				});
			},
			
			_scrollToInputTop: function(input) {
				$('html, body').animate({
					scrollTop: input.offset().top
				}, 500);
			},

			_createShowAllButton: function() {
				var input = this.input,
				_this = this,
				wasOpen = false;

				this.wrapper.addClass("has-feedback");
				$("<span>", {
					class: "directorypress-dropmenubox-button glyphicon directorypress-form-control-feedback " + this.element.data("icon")
				})
				.appendTo(this.wrapper)
				.on("mousedown", function() {
					wasOpen = input.autocomplete("widget").is(":visible");
				})
				.on("click", function(e) {
					input.trigger("focus");
					if ($(this).hasClass("directorypress-search-input-reset")) {
						input.val('');
						// if it is get my location button - remove reset button later, when geolocation will start
						if (!$(this).hasClass('directorypress-mylocation')) {
							$(this).removeClass('directorypress-search-input-reset');
						}
						
						// submit search form on input reset
						$('#selected_tax\\['+id+'\\]').val('');
						$('#selected_tax_text\\['+id+'\\]').val('').trigger("change");
					}
					// Close if already visible
					if (wasOpen) {
						return;
					}

					if (_this._openMobileKeyboard()) {
						
					} else {
						input.autocomplete("search", '');
					}
				});
			},

			_source: function(request, response) {
				var term = $.trim(request.term).toLowerCase();
				var matcher = new RegExp($.ui.autocomplete.escapeRegex(term), "i");
				var common_array = [];
				
				this.element.children("option").map(function() {
					var text = $(this).text(),
					value = $(this).val(),
					name = $(this).data("name"),
					icon = $(this).data("icon"),
					fonticolor  = $(this).data("fonticolor"),
					icontype = $(this).data("icontype"),
					count = $(this).data("count"),
					sublabel = $(this).data("sublabel"),
					level = $(this).data("level"),
					parent_id = $(this).data("parent"),
					term_in_name = matcher.test(name),
					term_in_sublabel = matcher.test(sublabel);
					if (this.value && (!term || term_in_name || term_in_sublabel)) {
						common_array.push({
							label: text,
							value: value,
							name: name,
							full_value: name + ', ' + sublabel,
							count: count,
							icon: icon,
							fonticolor: fonticolor,
							icontype: icontype,
							sublabel: sublabel,
							level: level,
							parent_id: parent_id,
							option: this,
							is_term: true,
							is_listing: false,
							term_in_name: term_in_name
						});
					}
				});

				if (this.element.data("ajax-search") && term) {
					
					
					this.wrapper.find(".directorypress-dropmenubox-button").append(inputLoader);
					this.wrapper.find(".directorypress-dropmenubox-button").addClass("input-loading");

					if (term in this.cache) {
						var cache_array = this.cache[term];
						
						this.wrapper.find(".directorypress-dropmenubox-button .input-ajax-loader").remove('.input-ajax-loader');
						this.wrapper.find(".directorypress-dropmenubox-button").removeClass('input-loading');
						common_array = cache_array.slice(0); // simply duplicate this array

						response(common_array);
					} else {
						if (this.input.parents("form").find("[name=directorytypes]").length) {
							var directorytypes = this.input.parents("form").find("[name=directorytypes]").val();
						} else {
							var directorytypes = 0; 
						}
						
						$.ajax({
				        	url: directorypress_js_instance.ajaxurl,
				        	type: "POST",
				        	dataType: "json",
				            data: {
				            	action: 'directorypress_keywords_search',
				            	term: term,
				            	directorytypes: directorytypes
				            },
				            combobox: this,
				            success: function(response_from_the_action_function){
				            	if (response_from_the_action_function != 0 && response_from_the_action_function.listings) {
				            		var cache_array = [];
				            		response_from_the_action_function.listings.map(function(listing) {
				            			var item = {
											label: listing.title,      // text in option
											value: listing.name,      // value depends on is_term
											name: listing.name,       // text to place in input
											full_value: listing.name,       // full value of the item
											icon: listing.icon,
											sublabel: listing.sublabel,  // sub-description
											option: listing,
											is_term: false,
											is_listing: true,
											term_in_name: true
										}
				            			common_array.push(item);
				            			cache_array.push(item);
				            		});
				            		common_array.sort(directorypress_sort_autocomplete_items);

				            		this.combobox.cache[term] = common_array;
				            	}
				            	response(common_array);
				            },
				            complete: function() {
				            	
								this.combobox.wrapper.find(".directorypress-dropmenubox-button .input-ajax-loader").remove('.input-ajax-loader');
								this.combobox.wrapper.find(".directorypress-dropmenubox-button").removeClass('input-loading');
				            }
				        });
					}
				} else {
					if (term) {
						common_array.sort(directorypress_sort_autocomplete_items);
					}
					response(common_array);
				}
			},

			_destroy: function() {
				this.wrapper.remove();
				this.element.show();
			}
		});
		window.keywords_autocomplete = $.widget("custom.keywords_autocomplete", catcombo, {
			cache: new Object(),

			_create: function() {
				this.wrapper = this.element.parent();
				this.wrapper.addClass("directorypress-autocomplete-dropmenubox-keywords");

				this._createAutocomplete();
			},
			
			_createAutocomplete: function() {
				this._autocompleteWithOptions(this.element);
				this._autocompleteRenderItem(this.element);
				
				var input = this.element;

				this._on(input, {
					autocompleteselect: function(event, ui) {
						this._trigger("select", event, {
							item: ui.item
						});

						input.val(ui.item.value);
						input.trigger('change');

						event.preventDefault();
					},
					autocompletefocus: function(event, ui) {
						event.preventDefault();
					},
					click: function(event, ui) {
						input.trigger("focus");
						input.autocomplete("search", input.val());
					}
				});
			},

			_source: function(request, response) {
				var term = $.trim(request.term).toLowerCase();
				var matcher = new RegExp($.ui.autocomplete.escapeRegex(term), "i");
				var common_array = [];
				
				if (term) {
					this.wrapper.find(".directorypress-dropmenubox-button").addClass('input-loading');
					this.wrapper.find(".directorypress-dropmenubox-button").append(inputLoader);

					if (term in this.cache) {
						var cache_array = this.cache[term];
						this.wrapper.find(".directorypress-dropmenubox-button .input-ajax-loader").remove('.input-ajax-loader');
						this.wrapper.find(".directorypress-dropmenubox-button").removeClass('input-loading');
						common_array = cache_array.slice(0); // simply duplicate this array
						response(common_array);
					} else {
						if (this.element.parents("form").find("[name=directorytypes]").length) {
							var directorytypes = this.element.parents("form").find("[name=directorytypes]").val();
						} else {
							var directorytypes = 0; 
						}
						
						$.ajax({
							url: directorypress_js_instance.ajaxurl,
							type: "POST",
							dataType: "json",
							data: {
								action: 'directorypress_keywords_search',
								term: term,
								directorytypes: directorytypes
							},
							combobox: this,
							success: function(response_from_the_action_function){
								if (response_from_the_action_function != 0 && response_from_the_action_function.listings) {
									response_from_the_action_function.listings.map(function(listing) {
										var item = {
												label: listing.title,      // text in option
												value: listing.name,      // value depends on is_term
												name: listing.name,       // text to place in input
												full_value: listing.name,       // full value of the item
												icon: listing.icon,
												sublabel: listing.sublabel,  // sub-description
												option: listing,
												is_term: false,
												is_listing: true,
												term_in_name: true
										}
										common_array.push(item);
									});
									common_array.sort(directorypress_sort_autocomplete_items);

									this.combobox.cache[term] = common_array;
								}
								response(common_array);
							},
							complete: function() {
								this.combobox.wrapper.find(".directorypress-dropmenubox-button .input-ajax-loader").remove('.input-ajax-loader');
								this.combobox.wrapper.find(".directorypress-dropmenubox-button").removeClass('input-loading');
							}
						});
					}
				}
			},
		});
		window.locations_and_address = $.widget(".locations_and_address", catcombo, {
			input_icon_class: "dicode-material-icons dicode-material-icons-map-marker-outline",
			placeholder: "",

			_create: function() {
				this.wrapper = $("<div>")
				.addClass("directorypress-autocomplete-dropmenubox-locations")
				.insertAfter(this.element);
				
				this.uID = this.element.data("id");
				this.placeholder = this.element.data("placeholder");

				this.element.hide();
				this._createAutocomplete();
				this._createShowAllButton();
				this._addMyLocationButton();
			},
			
			_createAutocomplete: function() {
				this._super();

				this._on(this.input, {
					autocompleteselect: function(event, ui) {
						var form = this.input.parents("form");
						var id = form.data("id");
						if ($("#radius_"+id).val() > 0) {
							form.trigger("submit");
						}
						this.input.trigger('change');
					}
				});
			},
			
			_addMyLocationButton: function() {
				if (this.element.data("autocomplete-name")) {
					this.wrapper.find(".directorypress-form-control-feedback").addClass("directorypress-mylocation");
				}
			},
			
			_autocompleteRenderItem: function(input) {
				input.autocomplete("widget").addClass("directorypress-dropmenubox");
				input.autocomplete("instance")._renderItem = function(ul, item) {
					var label = item.label;
					var counter_markup = '';
					if (typeof item.count != "undefined") {
						counter_markup = '<span class="directorypress-dropmenubox-count">' + item.count + '</span>';
					}
					//var term = $.trim(term).toLowerCase();
					var item_class = "directorypress-search-dropmenubox";
					if (typeof item.is_term != "undefined" && item.is_term) {
						item_class = item_class + " directorypress-search-term-dropmenubox";
					}
					if (typeof item.is_listing != "undefined" && item.is_listing) {
						item_class = item_class + " directorypress-search-listing-dropmenubox";
					}
					var item_parent_class = 'parent';
					if(item.parent_id){
						item_parent_class = 'child'
					}
					
					
					var li = $("<li>", {
						class: item_parent_class
					}),
					wrapper = $("<div>", {
						html: label + counter_markup,
						class: item_class
					});

					if (item.icon) {
						var icon_class = "directorypress-ui-icon-location";
						$("<span>", {
							style: "background-image: url('" + item.icon + "'); background-repeat:no-repeat;",
							class: icon_class
						})
						.appendTo(wrapper);
					}

					if (item.sublabel) {
						var sublabel = item.sublabel;

						$("<span>")
						.html(sublabel)
						.addClass("directorypress-search-innerlab-dropmenubox")
						.appendTo(wrapper);
					} else {
						wrapper.addClass("directorypress-search-parent-dropmenubox");
					}
		
					return li.append(wrapper).appendTo(ul);
				};
			},

			_source: function(request, response) {
				var term = $.trim(request.term);
				
				var common_array = [];
				
				var matcher = new RegExp($.ui.autocomplete.escapeRegex(term), "i");
				this.element.children("option").map(function() {
					var text = $(this).text(),
					value = $(this).val(),
					name = $(this).data("name"),
					icon = $(this).data("icon"),
					count = $(this).data("count"),
					level = $(this).data("level"),
					parent_id = $(this).data("parent"),
					sublabel = $(this).data("sublabel"),
					term_in_name = matcher.test(name),
					term_in_sublabel = matcher.test(sublabel);
					if (this.value && (!term || term_in_name || term_in_sublabel)) {
						common_array.push({
							label: text,
							value: value,
							name: name,
							full_value: name + ', ' + sublabel,
							icon: icon,
							count: count,
							level: level,
							parent_id: parent_id,
							sublabel: sublabel,
							option: this,
							is_term: true,
							term_in_name: term_in_name
						});
					}
				});

				window.directorypress_collect_locationsPreditions = function(predictions, common_array, response) {
					$.map(predictions, function (prediction, i) {
						common_array.push({
							label: prediction.label,
							value: prediction.value,
							name: prediction.name,
							icon: "",
							sublabel: prediction.sublabel,
							is_term: false,
							term_in_name: true
						});
					})

					common_array.sort(directorypress_sort_autocomplete_items);
					response(common_array);
				}

				if (this.element.data("autocomplete-name") && directorypress_js_instance.has_map) {
					if (term && directorypress_maps_instance.address_autocomplete) {
						directorypress_autocompleteService(term, directorypress_maps_instance.address_autocomplete_code, common_array, response, directorypress_collect_locationsPreditions);
					} else {
						if (term) {
							common_array.sort(directorypress_sort_autocomplete_items);
						}
						response(common_array);
					}
				} else {
					if (term) {
						common_array.sort(directorypress_sort_autocomplete_items);
					}
					response(common_array);
				}
			},
		});
		window.address_autocomplete = $.widget("custom.address_autocomplete", catcombo, {
			input_icon_class: "dicode-material-icons dicode-material-icons-map-marker-outline",

			_create: function() {
				this.wrapper = this.element.parent();
				this.wrapper.addClass("directorypress-autocomplete-dropmenubox-locations");
				
				this._createAutocomplete();
				this._addMyLocationButton();
			},
			
			_addMyLocationButton: function() {
				this.element.next(".directorypress-form-control-feedback").addClass("directorypress-mylocation");
			},
			
			_createAutocomplete: function() {
				this._autocompleteWithOptions(this.element);
				this._autocompleteRenderItem(this.element);

				var input = this.element;

				this._on(input, {
					autocompleteselect: function(event, ui) {
						this._trigger("select", event, {
							item: ui.item
						});

						input.val(ui.item.value);
						input.trigger('change');
						
						var form = input.parents("form");
						var id = form.data("id");
						if ($("#radius_"+id).val() > 0) {
							form.trigger("submit");
						}
						
						return false;
					},
					autocompletefocus: function(event, ui) {
						event.preventDefault();
					},
					click: function(event, ui) {
						input.trigger("focus");
						input.autocomplete("search", input.val());
						
						if ($("body").hasClass("directorypress-touch")) {
							this._scrollToInputTop(input);
						}
					}
				});
			},
			
			_autocompleteRenderItem: function(input) {
				this._super(input);

				this.element.autocomplete("widget").addClass("directorypress-dropmenubox-only-address");
			},
			
			_source: function(request, response) {
				var term = $.trim(request.term);
				
				var common_array = [];

				window.directorypress_collectAddressPreditions = function(predictions, common_array, response) {
					$.map(predictions, function (prediction, i) {
						common_array.push({
							label: prediction.label,
							value: prediction.value,
							name: prediction.name,
							icon: "",
							sublabel: prediction.sublabel,
							is_term: false,
						});
					})

					common_array.sort(function(a,b) {return (a.name > b.name) ? 1 : ((b.name > a.name) ? -1 : 0);} );
					response(common_array);
				}

				if (term && directorypress_maps_instance.address_autocomplete && typeof directorypress_autocompleteService != 'undefined') {
					directorypress_autocompleteService(term, directorypress_maps_instance.address_autocomplete_code, common_array, response, directorypress_collectAddressPreditions);
				} else {
					response(common_array);
				}
			},
		});
		window.listing_address_autocomplete = $.widget("custom.listing_address_autocomplete", address_autocomplete, {

			_create: function() {
				this.wrapper = this.element.parent();
				
				this._createAutocomplete();
				this._addMyLocationButton();
			},
		});
	}

	$(document).on('change', '.directorypress-tax-dropdowns-wrap select', function() {
		var select_box = $(this).attr('id').split('_');
		var parent = $(this).val();
		var current_level = select_box[1];
		var uID = select_box[2];

		var divclass = $(this).parents('.directorypress-tax-dropdowns-wrap').attr('class').split(' ');
		var tax = divclass[0];
		var count = divclass[1];
		var hide_empty = divclass[2];

		directorypress_update_tax(parent, tax, current_level, count, hide_empty, uID, function() {});
	});

	function directorypress_update_tax(parent, tax, current_level, count, hide_empty, uID, callback) {
		var current_level = parseInt(current_level);
		var next_level = current_level + 1;
		var prev_level = current_level - 1;
		var selects_length = $('#directorypress-tax-dropdowns-wrap-'+uID+' select').length;
		
		if (parent)
			$('#selected_tax\\['+uID+'\\]').val(parent).trigger('change');
		else if (current_level > 1)
			$('#selected_tax\\['+uID+'\\]').val($('#chainlist_'+prev_level+'_'+uID).val()).trigger('change');
		else
			$('#selected_tax\\['+uID+'\\]').val(0).trigger('change');

		var exact_terms = $('#exact_terms\\['+uID+'\\]').val();

		for (var i=next_level; i<=selects_length; i++)
			$('#wrap_chainlist_'+i+'_'+uID).remove();
		
		if (parent) {
			var labels_source = directorypress_js_instance['tax_dropdowns_'+uID][uID];

			if (labels_source.labels[current_level] != undefined)
				var label = labels_source.labels[current_level];
			else
				var label = '';
			if (labels_source.titles[current_level] != undefined)
				var title = labels_source.titles[current_level];
			else
				var title = '';

			$('#chainlist_'+current_level+'_'+uID).addClass('directorypress-ajax-loading').attr('disabled', 'disabled');
			
			$.post(
				directorypress_js_instance.ajaxurl,
				{'action': 'directorypress_tax_dropdowns_hook', 'parentid': parent, 'next_level': next_level, 'tax': tax, 'count': count, 'hide_empty': hide_empty, 'label': label, 'title': title, 'exact_terms': exact_terms, 'uID': uID},
				function(response_from_the_action_function){
					if (response_from_the_action_function != 0){
						
						$('#directorypress-tax-dropdowns-wrap-'+uID).append(response_from_the_action_function);
						if(!directorypress_js_instance.is_admin){
							
							$('#directorypress-tax-dropdowns-wrap-'+uID+' select').select2();
						}
					}
					$('#chainlist_'+current_level+'_'+uID).removeClass('directorypress-ajax-loading').removeAttr('disabled');
					
					callback();
				}
			);
		}
	}
	
	function first(p){for(var i in p)return p[i];}
}(jQuery));