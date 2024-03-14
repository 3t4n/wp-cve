/**
 * Migrate from Erident Custom Login & Dashboard to Ultimate Dashboard.
 */
(function ($) {
	var isRequesting = false;
	var loading = {};
	var ajax = {};
	var migrationButton;

	var elms = {};

	elms.migrationFailed = document.querySelector(
		".cldashboard-migration-status.migration-failed"
	);

	if (!elms.migrationFailed) return;

	elms.errorMessage = elms.migrationFailed.querySelector(".error-message");

	elms.uninstallOldPlugin = document.querySelector(
		".cldashboard-migration-status.cldashboard-uninstalled"
	);

	elms.uninstallOldPluginMsg =
		elms.uninstallOldPlugin.querySelector(".process-message");

	elms.installNewPlugin = document.querySelector(
		".cldashboard-migration-status.ultimate-dashboard-installed"
	);

	elms.installNewPluginMsg =
		elms.installNewPlugin.querySelector(".process-message");

	elms.activateNewPlugin = document.querySelector(
		".cldashboard-migration-status.ultimate-dashboard-activated"
	);

	elms.activateNewPluginMsg =
		elms.activateNewPlugin.querySelector(".process-message");

	/**
	 * Call the main functions here.
	 */
	function init() {
		$(document).on("click", ".cldashboard-migration-button", ajax.migration);
	}

	loading.start = function () {
		migrationButton.classList.add("is-loading");
	};

	loading.stop = function () {
		migrationButton.classList.remove("is-loading");
	};

	/**
	 * Send ajax request to save the settings.
	 */
	ajax.migration = function (e) {
		e.preventDefault();

		var confirmMsg =
			"Please don't leave this page until the migration is complete. Migrate now?";

		if (!confirm(confirmMsg)) {
			return;
		}

		if (!migrationButton) migrationButton = this;

		if (isRequesting) return;
		isRequesting = true;
		loading.start();

		var data = {};

		data.action = "cldashboard_migration";
		data.nonce = CldashboardMigration.nonces.migration;

		data.old_plugin_slug = CldashboardMigration.oldPlugin.slug;
		data.old_plugin_basename = CldashboardMigration.oldPlugin.basename;

		data.new_plugin_slug = CldashboardMigration.newPlugin.slug;
		data.new_plugin_basename = CldashboardMigration.newPlugin.basename;

		elms.uninstallOldPluginMsg.innerHTML =
			"Uninstalling Erident Custom Login & Dashboard plugin...";
		elms.uninstallOldPlugin.classList.add("is-waiting");

		elms.migrationFailed.classList.remove("is-done");

		$.ajax({
			url: ajaxurl,
			type: "post",
			dataType: "json",
			data: data,
		})
			.done(function (r) {
				// Error is handled in the "fail" callback.
				if (!r.success) return;

				elms.uninstallOldPluginMsg.innerHTML = r.data;
				elms.uninstallOldPlugin.classList.remove("is-waiting");
				elms.uninstallOldPlugin.classList.add("is-done");

				elms.installNewPluginMsg.innerHTML =
					"Installing Ultimate Dashboard plugin...";
				elms.installNewPlugin.classList.add("is-waiting");

				isRequesting = false;
				installUltimateDashboard();
			})
			.fail(function (jqXHR) {
				var errorMessage =
					"Something went wrong. Are you connected to the internet?";

				if (jqXHR.responseJSON && jqXHR.responseJSON.data) {
					errorMessage = jqXHR.responseJSON.data;
				}

				elms.errorMessage.innerHTML = errorMessage;
				elms.uninstallOldPlugin.classList.remove("is-waiting");
				elms.migrationFailed.classList.add("is-done");
				loading.stop();
				isRequesting = false;
			});
	};

	function installUltimateDashboard() {
		if (isRequesting) return;
		isRequesting = true;

		wp.updates.installPlugin({
			slug: CldashboardMigration.newPlugin.slug,
			success: function () {
				elms.installNewPluginMsg.innerHTML =
					"Ultimate Dashboard plugin has been installed";
				elms.installNewPlugin.classList.remove("is-waiting");
				elms.installNewPlugin.classList.add("is-done");

				elms.activateNewPluginMsg.innerHTML =
					"Activating Ultimate Dashboard plugin...";
				elms.activateNewPlugin.classList.add("is-waiting");

				isRequesting = false;
				activateUltimateDashboard();
			},
			error: function (jqXHR) {
				if (jqXHR.responseJSON && jqXHR.responseJSON.data) {
					elms.errorMessage.innerHTML = jqXHR.responseJSON.data;
				}

				elms.installNewPlugin.classList.remove("is-waiting");
				elms.migrationFailed.classList.add("is-done");
				loading.stop();
				isRequesting = false;
			},
		});
	}

	function activateUltimateDashboard() {
		if (isRequesting) return;
		isRequesting = true;

		$.ajax({
			async: true,
			type: "GET",
			url: CldashboardMigration.newPlugin.activationUrl,
			success: function () {
				elms.activateNewPluginMsg.innerHTML =
					"Ultimate Dashboard plugin has been activated";
				elms.activateNewPlugin.classList.remove("is-waiting");
				elms.activateNewPlugin.classList.add("is-done");

				loading.stop();
				isRequesting = false;

				// Redirect to Ultimate Dashboard settings page.
				window.location.replace(CldashboardMigration.redirectUrl);
			},
			error: function (jqXHR) {
				if (jqXHR.responseJSON && jqXHR.responseJSON.data) {
					elms.errorMessage.innerHTML = jqXHR.responseJSON.data;
				}

				elms.activateNewPlugin.classList.remove("is-waiting");
				elms.migrationFailed.classList.add("is-done");
				loading.stop();
				isRequesting = false;
			},
		});
	}

	init();
})(jQuery);
