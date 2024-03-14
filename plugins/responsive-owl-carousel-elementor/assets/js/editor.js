(function ($) {
	elementor.hooks.addAction(
		"panel/open_editor/widget/owl-carousel-elementor",
		function (panel, model, view) {
			var $panelEl = panel.$el;
			var $viewEl = view.$el;

			if (!$panelEl.length) {
				return;
			}

			// when panel editor loads first time
			owce_repeater_field_hide($panelEl);

			// remember to add change, click-for repeater
			$panelEl.on(
				"input change",
				"textarea, input, select",
				function (e) {
					owce_do_bunch_of_stuff($(this), $panelEl, $viewEl, e);
				}
			);

			// this is required as elementor re-renders panel/repeater fields when go to/come back from other tabs(styles/advance)
			$panelEl.on("click", function () {
				owce_repeater_field_hide($panelEl);
			});
		}
	);

	function owce_do_bunch_of_stuff($this, $panelEl, $viewEl, event) {
		var dataSetting = $this.attr("data-setting");
		var val = $this.val();

		if ($this.is("select")) {
			if (dataSetting === "carousel_layout") {
				if (event.type === "change") {
					owce_repeater_field_hide($panelEl);
				}
				owce_modify_setting($panelEl, val);
			}
		}

		if ($this.closest(".js_repeater_single").length) {
			owce_update_carousel_item($this, $viewEl, dataSetting, val, event);
		}
	}

	// hide repeater fields depending on layout
	function owce_repeater_field_hide($element) {
		var $repeater = $element.find(".js_items_list_repeater");
		var currentLayout = owce_find_by_data(
			$element,
			"carousel_layout"
		).val();

		$repeater.find(".js_repeater_single").each(function () {
			var $this = $(this);
			if ($this.hasClass("js_hide_on_layout_" + currentLayout)) {
				// elementor removes the class 'elementor-hidden-control' when start typing on the other/visible field
				$this.css("display", "none");
			} else {
				$this.css("display", "block");
			}
		});
	} // owce_repeater_field_hide

	function owce_update_carousel_item( $this, $element, currentSetting, currentVal, event ) {
		// every repeater item has unique id
		var currentRepeaterItem = $this
			.closest(".elementor-repeater-row-controls")
			.find(".elementor-control-_id input")
			.val();

		var $currentViewItem = $element.find(
			".carousel-item-" + currentRepeaterItem
		);

		if (currentSetting !== "url") {
			owce_find_by_data($currentViewItem, currentSetting).html(
				currentVal
			);
		}

		// fires when focused out of the input/textarea
		if (event.type === "change" && currentSetting === "url") {
			$this.trigger("input");
		}
	} // owce_update_carousel_item

	function owce_modify_setting( $element, currentVal ) {
		var imgSize = owce_find_by_data($element, "carousel_thumbnail_size");

		var trigger = true;

		if (currentVal === "testimonial") {
			imgSize.val("owl_elementor_testimonial");
		} else if ( currentVal === "team" ) {
			imgSize.val("owl_elementor_team");
		} else {
			trigger = false;
			if ( imgSize.val() !== "owl_elementor_thumbnail" ) {
				imgSize.val("owl_elementor_thumbnail");
				trigger = true;
			}
		}
		// $this.add(imgSize).trigger("change");
		if (trigger) {
			imgSize.trigger("change");
		}
	} // owce_modify_setting

	// helpers
	function owce_find_by_data(element, val, attr = "setting") {
		var dataAttr = "[data-" + attr + '="' + val + '"]';
		return $(element).find(dataAttr).length
			? $(element).find(dataAttr)
			: $("");
	}
})(jQuery);
