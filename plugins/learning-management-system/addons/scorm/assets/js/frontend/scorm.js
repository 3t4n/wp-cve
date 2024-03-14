/**
 * SCORM API Wrapper for managing SCORM-based courses within an LMS.
 * This implementation ensures compatibility with older browsers by adhering to ES5 standards.
 * Functions include initializing and terminating SCORM sessions, setting and getting values, and committing data to the LMS.
 *
 * @since 1.8.3
 */

/**
 * Reference to the pipwerks SCORM API, ensuring SCORM functionality is available.
 *
 * @since 1.8.3
 */
var SCORM = pipwerks.SCORM;

/**
 * SCORM_API object to encapsulate SCORM operations.
 *
 * @since 1.8.3
 */
var SCORM_API = {
	CourseId: null,
	LastError: 0,
	Initialized: false,
	Data: {},
	LMSProgress: {},

	/**
	 * Initializes the SCORM session. Sets up initial state and prepares for interaction with the LMS.
	 *
	 * @since 1.8.3
	 *
	 * @returns {Boolean} True if initialization is successful, otherwise false.
	 */
	Initialize: function () {
		this.ModuleRunning = true;
		this.Initialized = true;
		this.LMSProgress = {};

		return true;
	},

	/**
	 * Terminates the SCORM session. Resets the internal state and optionally reloads the page based on LMS progress.
	 *
	 * @since 1.8.3
	 *
	 * @returns {String} "true" as a string indicating successful termination.
	 */
	Terminate: function () {
		this.ModuleRunning = false;
		this.Initialized = false;
		this.CourseId = null;
		this.Data = {};
		SCORM.connection.isActive = false;

		/**
		 * If specific progress conditions are met, the page is reloaded.
		 */
		if (
			this.LMSProgress.hasOwnProperty('lesson_status') &&
			this.LMSProgress &&
			this.LMSProgress.hasOwnProperty('completion_status') &&
			this.LMSProgress.hasOwnProperty('success_status')
		) {
			if (
				this.LMSProgress.lesson_status == 'passed' ||
				(this.LMSProgress.completion_status == 'completed' &&
					this.LMSProgress.success_status == 'passed')
			) {
				location.reload(true);
			}
		}

		this.LMSProgress = {};

		return 'true';
	},

	/**
	 * Retrieves a value for a specified key from the stored SCORM data.
	 *
	 * @since 1.8.3
	 *
	 * @param {String} key - The key associated with the desired value.
	 * @returns {String} - The value associated with the key, or an empty string if the key is not found.
	 */
	GetValue: function (key) {
		this.LastError = 0;

		if (!this.Initialized) {
			this.LastError = scormErrors.GetValueBeforeInit;
			return '';
		}

		return this.Data[key] !== undefined ? this.Data[key] : '';
	},

	/**
	 * Sets a value for a specified key within the SCORM data.
	 *
	 * @since 1.8.3
	 *
	 * @param {String} key - The key to set the value for.
	 * @param {*} value - The value to be associated with the key.
	 * @returns {String} - "true" as a string indicating the value was set successfully.
	 */
	SetValue: function (key, value) {
		this.LastError = 0;

		if (!this.Initialized) {
			this.LastError = scormErrors.SetValueBeforeInit;
			return '';
		}

		this.Data[key] = value;

		return 'true';
	},

	/**
	 * Commits all SCORM data to the LMS. This should be called to ensure all data is saved within the LMS.
	 *
	 * @since 1.8.3
	 *
	 * @returns {String} - "true" as a string if commit was successful, "false" otherwise.
	 */
	Commit: function () {
		this.LastError = 0;

		if (this.CourseId) {
			var url =
				_MASTERIYO_SCORM_COURSE_.restUrl + '/course_progress/' + this.CourseId;
			var data = this.Data;
			var scorm_api = this;

			/**
			 * AJAX call to POST data to the LMS
			 */
			jQuery.ajax({
				url: url,
				type: 'POST',
				dataType: 'json',
				contentType: 'application/json',
				data: JSON.stringify(data),
				beforeSend: function (xhr) {
					xhr.setRequestHeader(
						'X-WP-Nonce',
						_MASTERIYO_SCORM_COURSE_.wp_rest_nonce,
					);
				},
				success: function (response) {
					scorm_api.LMSProgress = response;
				},
			});

			return 'true';
		} else {
			return 'false';
		}
	},

	/**
	 * Retrieves the last error code.
	 *
	 * @since 1.8.3
	 *
	 * @returns {Number} - The last error code.
	 */
	GetLastError: function () {
		var error = this.LastError;
		this.LastError = 0;

		return error;
	},

	/**
	 * Provides a human-readable string representing the last SCORM error code.
	 *
	 * @since 1.8.3
	 *
	 * @param {Number} errorCode - The error code to get the string for.
	 * @returns {String} - A descriptive string of the error.
	 */
	GetErrorString: function (error) {
		return 'Error: ' + error;
	},

	/**
	 * Provides diagnostic information for the specified error code.
	 *
	 * @since 1.8.3
	 *
	 * @param {Number} errorCode - The error code to get diagnostic information for.
	 * @returns {String} - Diagnostic information for the error.
	 */
	GetDiagnostic: function () {
		var message = 'Diagnostic: ' + this.LastError;
		this.LastError = 0;

		return message;
	},

	/**
	 * Compatibility wrapper for SCORM 1.2's LMSInitialize function. Calls the Initialize
	 * method to start a SCORM session, ensuring compatibility with SCORM 1.2.
	 *
	 * @since 1.8.3
	 *
	 * @returns {Boolean} True if the initialization was successful, otherwise false.
	 */
	LMSInitialize: function () {
		return this.Initialize();
	},

	/**
	 * Compatibility wrapper for SCORM 1.2's LMSFinish function. Calls the Terminate
	 * method to properly close a SCORM session, ensuring compatibility with SCORM 1.2.
	 *
	 * @since 1.8.3
	 *
	 * @returns {String} "true" as a string indicating successful termination.
	 */
	LMSFinish: function () {
		return this.Terminate();
	},

	/**
	 * Compatibility wrapper for SCORM 1.2's LMSGetValue function. Retrieves a value
	 * for a specified key from the SCORM data model, ensuring compatibility with SCORM 1.2.
	 *
	 * @since 1.8.3
	 *
	 * @param {String} key The key associated with the desired value.
	 * @returns {String} The value for the specified key, or an empty string if the key is not found.
	 */
	LMSGetValue: function (key) {
		return this.GetValue(key);
	},

	/**
	 * Compatibility wrapper for SCORM 1.2's LMSSetValue function. Sets a value for
	 * a specified key within the SCORM data model, ensuring compatibility with SCORM 1.2.
	 *
	 * @since 1.8.3
	 *
	 * @param {String} key The key to set the value for.
	 * @param {*} value The value to be associated with the key.
	 * @returns {String} "true" as a string indicating the value was set successfully.
	 */
	LMSSetValue: function (key, value) {
		return this.SetValue(key, value);
	},

	/**
	 * Compatibility wrapper for SCORM 1.2's LMSCommit function. Commits all SCORM data
	 * to the LMS, ensuring data persistence, and is compatible with SCORM 1.2.
	 *
	 * @since 1.8.3
	 *
	 * @returns {String} "true" as a string if the commit was successful, "false" otherwise.
	 */
	LMSCommit: function () {
		return this.Commit();
	},

	/**
	 * Compatibility wrapper for SCORM 1.2's LMSGetLastError function. Retrieves the
	 * last error code in a manner that is compatible with SCORM 1.2.
	 *
	 * @since 1.8.3
	 *
	 * @returns {Number} The last error code.
	 */
	LMSGetLastError: function () {
		return this.GetLastError();
	},

	/**
	 * Compatibility wrapper for SCORM 1.2's LMSGetErrorString function. Provides a
	 * human-readable string representing the last SCORM error code, ensuring compatibility
	 * with SCORM 1.2.
	 *
	 * @since 1.8.3
	 *
	 * @param {Number} error The error code to translate.
	 * @returns {String} A descriptive string of the error.
	 */
	LMSGetErrorString: function () {
		return this.GetErrorString();
	},

	/**
	 * Compatibility wrapper for SCORM 1.2's LMSGetDiagnostic function. Offers diagnostic
	 * information for the specified error code, ensuring compatibility with SCORM 1.2.
	 *
	 * @since 1.8.3
	 *
	 * @returns {String} Diagnostic information for the error.
	 */
	LMSGetDiagnostic: function () {
		return this.GetDiagnostic();
	},
};

var API_1484_11 = null;
var API = null;

/**
 * Initializes the SCORM course with necessary parameters, fetches initial course data,
 * and prepares the SCORM API for interaction.
 *
 * @since 1.8.3
 *
 * @param {String} CourseId - The unique identifier for the course.
 * @param {String} scormVersion - The SCORM version used by the course ("1.2" or "2004").
 * @param {String} dataSrc - The source URL for the course content.
 * @param {Object} masteriyoScormCourse - Configuration object containing course-specific settings and URLs.
 */
async function initLms(CourseId, scormVersion, dataSrc, masteriyoScormCourse) {
	var url = masteriyoScormCourse.restUrl + '/course_progress/' + CourseId;

	/**
	 * AJAX call to GET initial data for the course
	 *
	 * @since 1.8.3
	 */
	await jQuery.ajax({
		url: url,
		type: 'GET',
		dataType: 'json',
		contentType: 'application/json',
		beforeSend: function (xhr) {
			xhr.setRequestHeader('X-WP-Nonce', masteriyoScormCourse.wp_rest_nonce);
		},
		success: function (response) {
			SCORM.version = scormVersion;

			// Set the appropriate API instance based on SCORM version.
			if (SCORM.version === '1.2') {
				API_1484_11 = null;
				API = SCORM_API;
			} else {
				API = null;
				API_1484_11 = SCORM_API;
			}

			/**
			 * Initialize the SCORM session and set the iframe source
			 *
			 * @since 1.8.3
			 */
			jQuery('iframe#masteriyo-scorm-course-iframe').attr('src', dataSrc);
			var ScormConnected = SCORM.init();
			var ScormApi = SCORM.API.get();
			ScormApi.Data = {};
			ScormApi.CourseId = CourseId;

			/**
			 * Load initial course data into the SCORM API.
			 *
			 * @since 1.8.3
			 */
			if (
				response.hasOwnProperty('cmi.suspend_data') &&
				response['cmi.suspend_data'] !== ''
			) {
				for (var key in response) {
					if (response.hasOwnProperty(key)) {
						ScormApi.Data[key] = response[key];
					}
				}
			}
		},
	});
}

/**
 * Document ready function to initialize the SCORM course.
 *
 * @since 1.8.3
 */
jQuery(document).ready(function ($) {
	var $iframe = $('iframe#masteriyo-scorm-course-iframe');
	if ($iframe.length > 0) {
		var CourseId = $iframe.data('course-id');
		var scormVersion = $iframe.data('scorm-version').toString();
		var dataSrc = $iframe.data('src');
		initLms(CourseId, scormVersion, dataSrc, _MASTERIYO_SCORM_COURSE_);
	}
});
