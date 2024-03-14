(function ($) {
	"use strict";

	// ssi_icons contains list of fontawesome icon names

	var selected_icons = [],
		$body,
		default_style_rules = {
			facebook: ["#fff", "#fff", "#1e73be", "#194da8"],
			twitter: ["#fff", "#fff", "#00bde2", "#0097ce"],
			youtube: ["#fff", "#fff", "#e51b1b", "#c41717"],
			pinterest: ["#fff", "#fff", "#c62929", "#af2424"],
			envelope: ["#fff", "#fff", "#ea8a35", "#d17030"],
			whatsapp: ["#fff", "#fff", "#8abc3a", "#4cd140"],
			linkedin: ["#fff", "#fff", "#2f82d6", "#4665a3"],
			instagram: ["#fff", "#fff", "#e878e8", "#ae78e8"],
			skype: ["#fff", "#fff", "#39b9ef", "#329cd1"],
			x: ["#fff", "#fff", "#000000", "#000000"],
		};

	$(document).ready(function () {
		$body = $("body");

		// initialize starter function
		init();

		// icon package change.
		$("#sanil_ssi_db_icon_package").change(function (e) {
			let $form = $(this).parents("form");
			if (confirm("Are you sure ?\nAny unsaved changes will be lost. ")) {
				// trigger form submit
				$form.submit();
			}
		});

		// search icon
		$("#icon-search").keyup(
			delay(function () {
				var query = $.trim($(this).val());
				generate_new_icons(filterItems(query, ssi_icons));
			}, 500)
		);

		// on icon click
		$body.on("click", ".icon-wrapper", function () {
			var sel_icon = $(this).data("icon");

			//prevent duplicates
			if (!selected_icons.includes(sel_icon)) {
				selected_icons.push(sel_icon);

				// add icon to selected icons section
				add_to_selected_icons(sel_icon.replaceAll("_", " "));

				$(this).addClass("selected");

			}
		});

		// show / hide .more-options-container
		$body.on("click", "button.more-options-btn", function () {
			var $sibling = $(this).siblings(".more-options-container");

			if ($sibling.is(":visible")) {
				$sibling.slideUp();
			} else {
				$sibling.slideDown();
			}
		});

		// hide .more-options-container
		$body.on("click", "button.close-moc", function () {
			$(this).parents(".more-options-container").slideUp();
		});

		// remove item from selected icons list
		$body.on("click", ".remove-item", function () {
			var $this_parents = $(this).parents(".icon-row");
			var icon_id = $this_parents.data("icon");

			// remove from dom with animation
			$this_parents.slideUp("fast", function () {
				$this_parents.remove();
			});

			// remove from selected_icons array
			for (var i = 0; i < selected_icons.length; i++) {
				if (selected_icons[i] == icon_id) {
					selected_icons.splice(i, 1);
				}
			}

			// mark icon as not selected from "available icon" section
			$('.icon-wrapper.selected[data-icon="' + icon_id + '"]').removeClass(
				"selected"
			);
		});

		// main settings page form of wordpress
		$("#sanil-ssi-form").on("submit", function (e) {
			e.preventDefault();

			var selected_icon_data = {},
				has_error = false;

			$("#selected-icons-container .icon-row").each(function (index) {
				var icon = $(".selected-icon", this).val(),
					url = $(".url-input", this).val();

				// url field validation
				if (
					url.substring(0, 4).toLowerCase() != "http" &&
					url.substring(0, 6).toLowerCase() != "mailto" &&
					url.substring(0, 4).toLowerCase() != "tel:"
				) {
					has_error = true;
					$(".error-msg", this).show();
				} else {
					$(".error-msg", this).hide();
				}

				selected_icon_data[index] = JSON.stringify({
					url: $(".url-input", this).val(),
					icon: $(".selected-icon", this).val(),
					new_tab: $('input[name="open_in_new_tab"]', this).prop("checked"),
					tooltip_label: $('input[name="tooltip_label"]', this).val(),
					icon_color: $('input[name="icon_color"]', this).val(),
					icon_color_on_hover: $(
						'input[name="icon_color_on_hover"]',
						this
					).val(),
					bck_color: $('input[name="bck_color"]', this).val(),
					bck_color_on_hover: $('input[name="bck_color_on_hover"]', this).val(),
				});
			});

			// insert icon options into selected icons input field
			$("#sanil_ssi_db_selected_icons").attr(
				"value",
				JSON.stringify(selected_icon_data)
			);

			// submit form if there are no errors
			if (!has_error) $("#sanil-ssi-form")[0].submit();
		});

		// jquery sortable
		$("#selected-icons-container").sortable().disableSelection();

		// on init
		// first function to run
		function init() {
			// show default icons list in "available icons" section
			generate_new_icons(filterItems("", ssi_icons));

			// if values exists in db
			// show icons in "selected icons" section from data received from database

			var selected_icons_from_db = $("#sanil_ssi_db_selected_icons").val();
			if (selected_icons_from_db.length) {
				// convert into object
				selected_icons_from_db = JSON.parse(selected_icons_from_db);
			}

			// loop though object to get inner object
			Object.keys(selected_icons_from_db).forEach(function (index) {
				// get inner object
				var icon_obj = JSON.parse(selected_icons_from_db[index]);

				// generate default icons from object data
				add_to_selected_icons(icon_obj.icon, icon_obj, false);

				// add to selected icon array
				if (!selected_icons.includes(get_icon_id(icon_obj.icon))) {
					selected_icons.push(get_icon_id(icon_obj.icon));
				}

				// generate <style> for icon preview and insert into <head>

				generate_stylesheet(
					get_icon_id(icon_obj.icon),
					icon_obj.icon_color,
					icon_obj.icon_color_on_hover,
					icon_obj.bck_color,
					icon_obj.bck_color_on_hover
				);
			});

			// mark icons as selected, if already selected
			mark_icons_as_selected();
		}

		// genereate icon template
		function generate_new_icons(icon_names) {
			if (icon_names.length < 1) {
				$("#available-icons-container").html(
					"<p><strong>0 result found</strong></p>"
				);
				return;
			}

			var template = "";
			icon_names.forEach(function (icon_name) {
				template +=
					'<a data-icon="' +
					icon_name +
					'" class="icon-wrapper" ><div class="icon-holder">';
				template += '<i class="' + icon_name.replaceAll("_", " ") + '"></i>';
				template += "</div></a>";
			});

			$("#available-icons-container").html(template);

			// mark icons as selected, if already selected
			mark_icons_as_selected();
		}

		// search array
		function filterItems(needle, heystack) {
			var query = needle.toLowerCase(),
				return_data = [];

			var return_limit = 44;

			if (is_mobile()) return_limit = 12;

			heystack.filter(function (item) {
				if (return_data.length >= return_limit) return return_data;
				if (item.toLowerCase().indexOf(query) >= 0) {
					return_data.push(item);
				}
			});

			return return_data;
		}

		// delay search keyup
		function delay(callback, ms) {
			var timer = 0;
			return function () {
				var context = this,
					args = arguments;
				clearTimeout(timer);
				timer = setTimeout(function () {
					callback.apply(context, args);
				}, ms || 0);
			};
		}

		// generate markup and append to dom
		function add_to_selected_icons(icon, icon_obj, scroll_down) {
			// if icon_obj is not available
			if (icon_obj === undefined) {
				var got_styles_from_global_rules = 0;

				// try to get default styles from global "default_style_rules" object
				Object.keys(default_style_rules).forEach(function (index) {

					if (icon.includes(index)) {
						var styles = default_style_rules[index];

						icon_obj = {
							url: "",
							icon: icon,
							new_tab: 1,
							icon_color: styles[0],
							icon_color_on_hover: styles[1],
							bck_color: styles[2],
							bck_color_on_hover: styles[3],
						};

						got_styles_from_global_rules = 1;
					}
				});

				// if default styles was not generated from global style rules
				if (!got_styles_from_global_rules) {
					// create default styles
					icon_obj = {
						url: "",
						icon: icon,
						new_tab: 1,
						icon_color: "#000",
						icon_color_on_hover: "#fff",
						bck_color: "#fff",
						bck_color_on_hover: "#000",
					};
				}
			}

			var is_checked = icon_obj.new_tab == true ? "checked" : "",
				enable_tooltip = sanil_ssi_objects.enable_tooltip,
				show_flashing = scroll_down != false ? "flashing-anim" : "",
				tooltip_value =
					icon_obj.tooltip_label !== undefined && icon_obj.tooltip_label.length
						? icon_obj.tooltip_label
						: "";
			("");

			// show parent container
			$("#selected-icons-section").show();

			var template =
				'<div class="icon-row ui-state-default" data-icon="' +
				get_icon_id(icon) +
				'">';
			template +=
				'<div class="drag" title="' +
				sanil_ssi_objects.text_drag_msg +
				'">' +
				sanil_ssi_objects.text_drag +
				"</div>";
			template += '<div class="icon-holder"><i class="' + icon + '"></i></div>';
			template +=
				'<input type="text" name="url_input" class="url-input" placeholder="' +
				sanil_ssi_objects.text_url_to_open +
				'" value="' +
				icon_obj.url +
				'">';
			template +=
				'<input type="hidden" name="icon" class="selected-icon" value="' +
				icon +
				'" >';
			template +=
				'<button type="button" class="more-options-btn ' +
				show_flashing +
				'">' +
				sanil_ssi_objects.text_more_options +
				"</button>";
			template +=
				'<button type="button" class="remove-item remove-item-btn">' +
				sanil_ssi_objects.text_remove +
				"</button>";

			template +=
				'<div class="error-msg">URL should start with <strong>http://</strong> or <strong>https://</strong> or <strong>mailto:</strong> or <strong>tel:</strong></div>';

			template += '<div class="more-options-container" style="display: none;">';
			template +=
				'<h4 class="title">' +
				sanil_ssi_objects.text_more_options +
				'<button type="button" class="close-moc">' +
				sanil_ssi_objects.text_close +
				"</button></h4>";

			template +=
				'<div class="form-group"><label>' +
				sanil_ssi_objects.text_open_in_new_tab +
				'</label><div class="moc-input-wrapper"><input type="checkbox" value="1" name="open_in_new_tab" ' +
				is_checked +
				"></div></div>";

			if (enable_tooltip) {
				template +=
					'<div class="form-group"><label>' +
					sanil_ssi_objects.text_tooltip_label +
					'</label><div class="moc-input-wrapper"><input type="text" value="' +
					tooltip_value +
					'" name="tooltip_label" class="tooltip-label" ></div></div>';
			}

			template +=
				'<div class="form-group"><label>' +
				sanil_ssi_objects.text_colors +
				"</label>";
			template += '<div class="moc-input-wrapper has-color-picker">';
			template +=
				'<div class="colorpicker-group"><p>' +
				sanil_ssi_objects.text_icon_color +
				'</p><input type="text" value="' +
				icon_obj.icon_color +
				'" name="icon_color"  class="color-picker icon-color" data-alpha="true"></div>';
			template +=
				'<div class="colorpicker-group"><p>' +
				sanil_ssi_objects.text_icon_color_on_hover +
				'</p><input type="text" value="' +
				icon_obj.icon_color_on_hover +
				'" name="icon_color_on_hover"  class="color-picker icon-color-hover" data-alpha="true"></div>';
			template +=
				'<div class="colorpicker-group"><p>' +
				sanil_ssi_objects.text_bck_color +
				'</p><input type="text" value="' +
				icon_obj.bck_color +
				'" name="bck_color"  class="color-picker bck-color" data-alpha="true"></div>';
			template +=
				'<div class="colorpicker-group"><p>' +
				sanil_ssi_objects.text_bck_color_on_hover +
				'</p><input type="text" value="' +
				icon_obj.bck_color_on_hover +
				'" name="bck_color_on_hover"  class="color-picker bck-color-hover" data-alpha="true"></div>';

			template += "</div></div>";

			template +=
				'<button type="button" class="remove-item">Remove Icon</button>';

			template += "</div></div>";

			$("#selected-icons-container").append(template);

			// should we scroll down ?
			if (scroll_down != false) {
				// scroll down
				$("html, body").animate({ scrollTop: $(document).height() }, 500);
			}

			// generate styles
			on_color_change(
				'.icon-row[data-icon="' + get_icon_id(icon) + '"] .url-input'
			);

			// delay for smoother animation
			setTimeout(function () {
				// initialize wp color picker
				$(".icon-row .color-picker").wpColorPicker({
					change: function (event, ui) {
						on_color_change(event.target);
					},
				});
			}, 500);
		}

		// generate <style> tag and insert into <head>
		function generate_stylesheet(
			icon_id,
			icon_color,
			icon_color_hover,
			bck_color,
			bck_color_hover
		) {
			var styles = "";

			// normal state
			styles += '.icon-row[data-icon="' + icon_id + '"] .icon-holder{ ';
			styles += "color: " + icon_color + " !important; ";
			styles += "background: " + bck_color + " !important; ";
			styles += "}";

			// hover state
			styles += '.icon-row[data-icon="' + icon_id + '"] .icon-holder:hover{ ';
			styles += "color: " + icon_color_hover + " !important; ";
			styles += "background: " + bck_color_hover + " !important; ";
			styles += "}";

			if ($("#" + icon_id + "-styles").length) {
				$("#" + icon_id + "-styles").html(styles);
			} else {
				$("head").append(
					'<style id="' + icon_id + '-styles">' + styles + "</style>"
				);
			}
		}

		// replace space with underscore
		function get_icon_id(icon) {
			return icon.replaceAll(" ", "_");
		}

		// loop through all  .icon-wrapper and mark appropriate as selected
		function mark_icons_as_selected() {
			$("#available-icons-container a").each(function () {
				if (selected_icons.includes($(this).data("icon"))) {
					$(this).addClass("selected");
				}
			});
		}

		function on_color_change(sel) {
			var $parent = $(sel).parents(".icon-row");
			var icon_id = $parent.data("icon");

			// generate styles for preview
			generate_stylesheet(
				icon_id,
				$(
					'.icon-row[data-icon="' + icon_id + '"] .color-picker.icon-color'
				).val(),
				$(
					'.icon-row[data-icon="' +
					icon_id +
					'"] .color-picker.icon-color-hover'
				).val(),
				$(
					'.icon-row[data-icon="' + icon_id + '"] .color-picker.bck-color'
				).val(),
				$(
					'.icon-row[data-icon="' + icon_id + '"] .color-picker.bck-color-hover'
				).val()
			);
		}

		function is_mobile() {
			return $(window).width() < 768 ? true : false;
		}
	});
})(jQuery);
