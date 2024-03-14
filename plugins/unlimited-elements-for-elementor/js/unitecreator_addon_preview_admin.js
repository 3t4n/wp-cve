"use strict";

function UniteAddonPreviewAdmin() {
	var g_objPreview, g_addonID, g_requestPreview;
	var g_helper = new UniteCreatorHelper();
	var g_settings = new UniteSettingsUC();
	var that = this;

	if (!g_ucAdmin)
		var g_ucAdmin = new UniteAdminUC();

	/**
	 * init the view
	 */
	this.init = function () {
		boot();

		loadSettings(function () {
			refreshPreview();
		});
	};

	/**
	 * init the view by slot
	 */
	this.initBySlot = function (slot) {
		boot();

		loadSettings(function () {
			that.restoreSlot(slot);
		});
	};

	/**
	 * get addon id
	 */
	this.getAddonId = function () {
		return g_addonID;
	};

	/**
	 * get settings
	 */
	this.getSettings = function () {
		return g_settings;
	};

	/**
	 * restore slot
	 */
	this.restoreSlot = function (slot) {
		var data = {
			id: g_addonID,
			slotnum: slot,
			combine: true,
		};

		g_ucAdmin.ajaxRequest("get_test_addon_data", data, function (response) {
			var values = g_ucAdmin.getVal(response, "settings_values");

			trace("restoring settings:");
			trace(values);

			if (!values) {
				trace("no settings found");
				return;
			}

			g_settings.setValues(values);

			refreshPreview();
		});
	};

	/**
	 * boot the view
	 */
	function boot() {
		g_addonID = jQuery("#uc_preview_addon_wrapper").data("addonid");
		g_objPreview = jQuery("#uc_preview_wrapper");
	}

	/**
	 * load settings
	 */
	function loadSettings(onSuccess) {
		g_ucAdmin.setAjaxLoaderID("uc_settings_loader");

		return g_ucAdmin.ajaxRequest("get_addon_settings_html", { id: g_addonID }, function (response) {
			trace("initializing settings");

			initSettingsByHtml(response.html);

			if (typeof onSuccess === "function")
				onSuccess();
		});
	}

	/**
	 * init settings by its html
	 */
	function initSettingsByHtml(html) {
		var objSettingsWrapper = jQuery("#uc_settings_wrapper");
		objSettingsWrapper.html(html);

		g_settings.init(objSettingsWrapper);
		g_settings.setEventOnChange(refreshPreview);
		g_settings.setEventOnSelectorsChange(handleSelectorsChange);
	}

	/**
	 * handle selectors change
	 */
	function handleSelectorsChange() {
		updateSelectorsIncludes();
		updateSelectorsStyles();
	}

	/**
	 * update selectors includes (like google font)
	 */
	function updateSelectorsIncludes() {
		var includes = g_settings.getSelectorsIncludes();

		if (includes)
			g_helper.putIncludes(window, includes);
	}

	/**
	 * update selectors styles
	 */
	function updateSelectorsStyles() {
		var css = g_settings.getSelectorsCss();

		jQuery("[name=uc_selectors_css]").text(css);
	}

	/**
	 * render preview
	 */
	function renderPreview(data) {
		var html = g_ucAdmin.getVal(data, "html");
		var includes = g_ucAdmin.getVal(data, "includes");

		g_helper.putIncludes(window, includes, function () {
			g_objPreview.html(html);
		});
	}

	/**
	 * refresh preview
	 */
	function refreshPreview() {
		var values = g_settings.getSettingsValues();

		var data = {
			id: g_addonID,
			settings: values,
			selectors: true,
		};

		g_ucAdmin.setAjaxLoaderID("uc_preview_loader");
		g_objPreview.addClass("uc-preview-loading");

		if (g_requestPreview)
			g_requestPreview.abort();

		g_requestPreview = g_ucAdmin.ajaxRequest("get_addon_output_data", data, function (response) {
			g_objPreview.removeClass("uc-preview-loading");

			renderPreview(response);
		});
	}
}
