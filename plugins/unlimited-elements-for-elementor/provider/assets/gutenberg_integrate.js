(function (wp) {
	var wbe = wp.blockEditor;
	var wc = wp.components;
	var wd = wp.data;
	var we = wp.element;
	var el = we.createElement;

	var edit = function (props) {
		var blockProps = wbe.useBlockProps();
		var settingsVisibleState = we.useState(false);
		var settingsContentState = we.useState(null);
		var widgetContentState = we.useState(null);
		var widgetReloadsState = we.useState(0);

		var widgetRequestRef = we.useRef(null);
		var ucSettingsRef = we.useRef(new UniteSettingsUC());
		var ucHelperRef = we.useRef(new UniteCreatorHelper());

		var isEditorSidebarOpened = wd.useSelect(function (select) {
			return select("core/edit-post").isEditorSidebarOpened();
		});

		var widgetId = "unite-gutenberg-widget-" + blockProps.id;
		var widgetLoaderId = widgetId + "-loader";

		var settingsId = "unite-gutenberg-settings-" + blockProps.id;
		var settingsTempId = settingsId + "-temp";
		var settingsErrorId = settingsId + "-error";

		var settingsVisible = settingsVisibleState[0];
		var setSettingsVisible = settingsVisibleState[1];

		var settingsContent = settingsContentState[0];
		var setSettingsContent = settingsContentState[1];

		var widgetContent = widgetContentState[0];
		var setWidgetContent = widgetContentState[1];

		var widgetReloads = widgetReloadsState[0];
		var setWidgetReloads = widgetReloadsState[1];

		var ucSettings = ucSettingsRef.current;
		var ucHelper = ucHelperRef.current;

		var getSettings = function () {
			return props.attributes.data ? JSON.parse(props.attributes.data) : null;
		};

		var saveSettings = function () {
			props.setAttributes({ data: JSON.stringify(ucSettings.getSettingsValues()) });
		};

		var saveSettingsAndReloadWidget = function () {
			saveSettings();

			setWidgetReloads(function (count) {
				return count + 1;
			});
		};

		var getSettingsElement = function () {
			if (!settingsContent)
				return;

			var settingsElement = jQuery("#" + settingsId);
			var settingsTempElement = jQuery("#" + settingsTempId);

			settingsTempElement.remove();

			if (settingsElement.length)
				return settingsElement;

			settingsTempElement = jQuery("<div id='" + settingsTempId + "' />")
				.hide()
				.html(settingsContent)
				.appendTo("body");

			return settingsTempElement;
		};

		var initSettings = function () {
			ucSettings.destroy();

			var settingsElement = getSettingsElement();

			if (!settingsElement)
				return;

			ucSettings.init(settingsElement);

			ucSettings.setEventOnChange(function () {
				saveSettingsAndReloadWidget();
			});

			ucSettings.setEventOnSelectorsChange(function () {
				saveSettings();

				var css = ucSettings.getSelectorsCss();
				var includes = ucSettings.getSelectorsIncludes();

				jQuery("#" + widgetId).find("[name=uc_selectors_css]").text(css);

				if (includes)
					ucHelper.putIncludes(window, includes);
			});

			// restore current settings, otherwise apply current
			var values = getSettings();

			if (values !== null)
				ucSettings.setValues(values);
			else
				saveSettingsAndReloadWidget();
		};

		var loadSettingsContent = function () {
			g_ucAdmin.setErrorMessageID(settingsErrorId);

			g_ucAdmin.ajaxRequest("get_addon_settings_html", {
				id: props.attributes._id,
				config: getSettings(),
			}, function (response) {
				var html = g_ucAdmin.getVal(response, "html");

				setSettingsContent(html);
			});
		};

		var loadWidgetContent = function () {
			if (!widgetContent) {
				// load existing widgets from the page
				for (var index in g_gutenbergParsedBlocks) {
					var block = g_gutenbergParsedBlocks[index];

					if (block.name === props.name) {
						setWidgetContent(block.html);

						delete g_gutenbergParsedBlocks[index];

						return;
					}
				}
			}

			var loaderElement = jQuery("#" + widgetLoaderId);

			loaderElement.show();

			if (widgetRequestRef.current !== null)
				widgetRequestRef.current.abort();

			widgetRequestRef.current = g_ucAdmin.ajaxRequest("get_addon_output_data", {
				id: props.attributes._id,
				settings: getSettings(),
				selectors: true,
			}, function (response) {
				var html = g_ucAdmin.getVal(response, "html");
				var includes = g_ucAdmin.getVal(response, "includes");

				ucHelper.putIncludes(window, includes, function () {
					setWidgetContent(html);
				});
			}).always(function () {
				loaderElement.hide();
			});
		};

		we.useEffect(function () {
			// load the settings on the block mount
			loadSettingsContent();

			// remove loaded styles from the page
			jQuery("#unlimited-elements-styles").remove();

			return function () {
				// destroy the settings on the block unmount
				ucSettings.destroy();
			};
		}, []);

		we.useEffect(function () {
			setSettingsVisible(props.isSelected && isEditorSidebarOpened);
		}, [props.isSelected, isEditorSidebarOpened]);

		we.useEffect(function () {
			if (!settingsVisible)
				return;

			initSettings();
		}, [settingsVisible]);

		we.useEffect(function () {
			if (!settingsContent)
				return;

			initSettings();
		}, [settingsContent]);

		we.useEffect(function () {
			loadWidgetContent();
		}, [widgetReloads]);

		var settings = el(
			wbe.InspectorControls, {},
			el("div", { className: "unite-gutenberg-settings-error", id: settingsErrorId }),
			settingsContent && el("div", { id: settingsId, dangerouslySetInnerHTML: { __html: settingsContent } }),
			!settingsContent && el("div", { className: "unite-gutenberg-settings-spinner" }, el(wc.Spinner)),
		);

		var widget = el(
			"div", { className: "unite-gutenberg-widget-wrapper" },
			widgetContent && el("div", { id: widgetId, dangerouslySetInnerHTML: { __html: widgetContent } }),
			widgetContent && el("div", { className: "unite-gutenberg-widget-loader", id: widgetLoaderId }, el(wc.Spinner)),
			!widgetContent && el("div", { className: "unite-gutenberg-widget-placeholder" }, el(wc.Spinner)),
		);

		return el("div", blockProps, settings, widget);
	};

	for (var name in g_gutenbergBlocks) {
		var block = g_gutenbergBlocks[name];
		var args = jQuery.extend(block, { edit: edit });

		wp.blocks.registerBlockType(name, args);
	}
})(wp);
