let pipwerks = cluevo_scorm_wrapper();
var scorm = pipwerks.SCORM; //Shortcut
var cluevo_scorm_window = null;
var cluevoClosingLightbox = false;
var cluevoCommitTimer = null;
var cluevoCompletionCommitTimer = null;
let cluevo_doing_rating = false;
// SCORM 2004
var scorm_api = {
  //ModuleComplete: false,
  ItemId: null,
  LastError: 0,
  Initialized: false,
  _session_index: null,
  _session_id: null,
  _init_cluevo_progress: function () {
    this._cluevo_progress = {
      attempt_id: null,
      completion_status: null,
      credit: null,
      date_added: null,
      date_modified: null,
      date_started: null,
      date_user_deleted: null,
      is_practice: null,
      lesson_status: null,
      module_id: null,
      progress: null,
      score_max: null,
      score_min: null,
      score_raw: null,
      score_scaled: null,
      success_status: null,
      user_deleted: null,
      user_id: null,
    };
  },
  reset: function () {
    console.log("resetting scorm api");
    this._stopAutoCommit();
    this.ItemId = null;
    this.LastError = 0;
    this.Initialized = false;
    this.Values = {};
    this._autoCommitInit = false;
    this._terminated = false;
    this._terminating = false;
    this._committing = false;
    this._lastCommit = null;
    this._lastCommitError = null;
    this._session_index = null;
    this._lms_session_id = null;
    API_1484_11 = null;
    API = null;
    pipwerks = cluevo_scorm_wrapper();
  },
  prime: function (itemId) {
    console.log("priming api", itemId);
    this.ItemId = itemId;
    console.log("api primed", itemId);
  },
  Initialize: function () {
    if (this.Initialized) {
      console.warn("CLUEVO scorm api already initialized");
      return "true";
    }
    console.info("Cluevo API, lms init");
    this.ModuleRunning = true;
    this.ParmTypes = scormParameterTypes;
    this.Initialized = true;
    this._init_cluevo_progress();
    this._terminated = false;
    if (!this.ItemId) {
      console.warn("CLUEVO scorm api initialized without item id");
    }
    return "true";
  },
  GetLastError: function () {
    let error = this.LastError || 0;
    this.LastError = 0;
    return error;
  },
  GetErrorString: function (code) {
    return "Error: " + code;
  },
  GetValue: function (parameter) {
    console.log('GetValue', parameter);
    this.LastError = 0;
    if (!this.Initialized) {
      this.LastError = scormErrors.GetValueBeforeInit;
      return "";
    }
    if (parameter.indexOf("._count") !== -1) {
      var pattern = "(.*)\\._count";
      var regex = new RegExp(pattern, "g");
      var match = regex.exec(parameter);
      return this.CountParameter(match[1]);
    }
    if (this.ParmTypes.hasOwnProperty(parameter)) {
      if (this.ParmTypes[parameter].hasOwnProperty("mode")) {
        if (this.ParmTypes[parameter].mode == "wo") {
          this.LastError = scormErrors.ElementIsWriteOnly;
          console.warn('GetValue: Parameter is write only', parameter);
          return "";
        }
      }
    }

    if (this.Values[parameter] !== undefined) {
      if (this.ParmTypes.hasOwnProperty(parameter)) {
        var type = this.ParmTypes[parameter].type;
        switch (type) {
          case "string":
            console.log('GetValue (string)', "" + this.Values[parameter]);
            return "" + this.Values[parameter];
          case "real":
            var num = Number(this.Values[parameter]);
            var digits = this.ParmTypes[parameter].digits;
            console.log('GetValue (real)', "" + num.toPrecision(digits[1]));
            return num.toPrecision(digits[1]);
          case "integer":
            console.log('GetValue (integer)', "" + parseInt(this.Values[parameter], 10));
            return parseInt(this.Values[parameter], 10);
        }
      }
      return this.Values[parameter];
    } else {
      if (this.ParmTypes.hasOwnProperty(parameter)) {
        if (this.ParmTypes[parameter].hasOwnProperty("default")) {
          return this.ParmTypes[parameter].default;
        }
      }
      if (parameter.indexOf("._count") !== -1) {
        return "0";
      }
      return "";
    }
  },
  CountParameter: function (parm) {
    var indexes = [];
    for (var key in this.Values) {
      if (key !== parm + "._count" && key !== parm + "._children") {
        if (key.indexOf(parm) > -1) {
          var pattern = parm + "\\.(\\d*)";
          var regex = new RegExp(pattern, "g");
          var match = regex.exec(key);
          if (match.length > 0) {
            if (match[1] !== "") {
              if (indexes.indexOf(match[1]) === -1) {
                indexes.push(match[1]);
              }
            }
          }
        }
      }
    }
    return indexes.length;
  },
  Values: {},
  StartSession: function (id) {
    this._session_id = id;
    let count = this.GetValue("cluevo.sessions._count") ?? -1;
    ["cmi.session_time", "cmi.core.session_time"].forEach((key) => {
      if (this.GetValue(key) !== "") {
        this.SetValue(key, "");
      }
    });
    this.SetValue("cluevo.session_time", "");
    this.SetValue("cluevo.session_id", id);
    this._session_index = count;
    this.SetValue(`cluevo.sessions.${count}.id`, id);
    this.SetValue(`cluevo.sessions.${count}.start`, new Date().toISOString());
  },
  EndSession: function () {
    this.SetValue(
      `cluevo.sessions.${this._session_index}.end`,
      new Date().toISOString(),
    );
  },
  SetValue: function (parameter, value) {
    let cleared = false;
    if (cluevoWpApiSettings.commitOnCompletion && cluevoCompletionCommitTimer) {
      console.log("completion commit pending, restarting timer");
      clearTimeout(cluevoCompletionCommitTimer);
      cleared = true;
    }
    this.LastError = 0;
    if (!this.Initialized) {
      this.LastError = scormErrors.SetValueBeforeInit;
      return "";
    }
    console.log("SetValue", parameter, value);
    if (["cmi.core.session_time", "cmi.session_time"].includes(parameter)) {
      this.Values["cluevo.session_time"] = value;
    }
    this.Values[parameter] = value;
    // for (let key in this.Values) {
    const key = parameter.replace("cmi.", "");
    if (key in this._cluevo_progress) {
      this._cluevo_progress[key] = value;
    }
    // }
    let api = this;
    if (
      cluevoWpApiSettings.commitOnCompletion == 1 &&
      !cluevoCompletionCommitTimer
    ) {
      if (
        parameter === "cmi.core.lesson_status" &&
        ["passed", "completed", "failed"].includes(value)
      ) {
        console.log("forcing commit because of lesson status", value);
        cluevoCompletionCommitTimer = setTimeout(() => api.Commit(), 100);
      }
      if (parameter === "cmi.completion_status" && value === "completed") {
        console.log("forcing commit because of lesson status", value);
        cluevoCompletionCommitTimer = setTimeout(() => api.Commit(), 100);
      }
    }
    if (cleared && !cluevoCompletionCommitTimer) {
      console.log("completion commit timer was cleared, restarting timer");
      cluevoCompletionCommitTimer = setTimeout(() => api.Commit(), 100);
    }
    return "true";
  },
  Commit: function (input) {
    if (cluevoCompletionCommitTimer) {
      clearTimeout(cluevoCompletionCommitTimer);
    }
    if (!this.Initialized) {
      console.warn(
        "api not initialized, aborting commit",
        this.Initialized,
        this,
      );
      return;
    }
    if (this._committing) {
      console.warn("commit in progress");
      return;
    }
    console.log("commit");
    this.LastError = 0;
    let curTime = Date.now();
    /* if (this._lastCommit && curTime - this._lastCommit < 1000) {
      console.warn('commit throttled');
    } */
    this._committing = true;
    this._lastCommit = curTime;
    if (this.ItemId) {
      var url =
        cluevoWpApiSettings.root +
        "cluevo/v1/modules/" +
        this.ItemId +
        "/parameters";
      if (this._session_index !== null) {
        let commitCount = this.GetValue(
          `cluevo.sessions.${this._session_index}.commits._count`,
        );
        this.SetValue(
          `cluevo.sessions.${this._session_index}.commits.${commitCount}.timestamp`,
          new Date().toISOString(),
        );
        this.SetValue(
          `cluevo.sessions.${this._session_index}.time`,
          this.GetValue("cluevo.session_time"),
        );
      }
      var data = this.Values;
      if (!data.hasOwnProperty("cmi.score.scaled")) {
        if (
          data.hasOwnProperty("cmi.score.raw") &&
          data.hasOwnProperty("cmi.score.max") &&
          Number(data["cmi.score.max"]) > 0
        )
          data["cmi.score.scaled"] =
            data["cmi.score.raw"] / data["cmi.score.max"];
      }
      if (!data.hasOwnProperty("cmi.core.score.scaled")) {
        if (
          data.hasOwnProperty("cmi.core.score.raw") &&
          data.hasOwnProperty("cmi.core.score.max") &&
          Number(data["cmi.core.score.max"]) > 0
        )
          data["cmi.core.score.scaled"] =
            data["cmi.core.score.raw"] / data["cmi.core.score.max"];
      }
      var api = this;

      jQuery.ajax({
        url: url,
        method: "PUT",
        contentType: "application/json",
        dataType: "json",
        data: JSON.stringify(data),
        beforeSend: function (xhr) {
          xhr.setRequestHeader("X-WP-Nonce", cluevoWpApiSettings.nonce);
        },
        success: function (response) {
          api._committing = false;
          api._cluevo_progress = response;
          api._startAutoCommit();
        },
        error: function (xhr) {
          api._committing = false;
          if (xhr.responseJSON) {
            if (
              xhr.responseJSON.code === "cluevo_no_saved_progress_for_guests" &&
              (!cluevoWpApiSettings.displayGuestProgressNotification ||
                cluevoWpApiSettings.displayGuestProgressNotification === "")
            )
              return;
            if (xhr.responseJSON.message !== api._lastCommitError) {
              cluevoAlert(
                cluevoStrings.message_title_error,
                xhr.responseJSON.message,
                "error",
              );
              api._lastCommitError = xhr.responseJSON.message;
            }
          } else {
            if (cluevoStrings.message_commit_error !== api._lastCommitError) {
              cluevoAlert(
                cluevoStrings.message_title_error,
                cluevoStrings.message_commit_error,
                "error",
              );
              api._lastCommitError = cluevoStrings.message_commit_error;
            }
          }
        },
      });
      this._committing = false;
      return "true";
    } else {
      console.warn("failed to commit");
      this._committing = false;
      cluevoAlert(
        cluevoStrings.message_title_error,
        cluevoStrings.message_commit_no_item_id_error,
        "error",
      );
      return "false";
    }
  },
  CommitData: function (input) {
    return this.Commit();
  },
  getHandle: function () {
    return this;
  },
  GetStudentName: function () {
    console.log("get student name");
  },
  GetDiagnostic: function () {
    var string = "Diagnostic: " + this.LastError;
    this.LastError = 0;
    return string;
  },
  Terminate: function () {
    if (this._terminated || this._terminating) return;
    this.EndSession();
    this.Commit();
    console.log("terminating api");
    this._terminating = true;
    this.Values = {};
    this.ModuleRunning = false;
    this.Initialized = false;
    this.LastError = 0;
    let tmpId = parseInt(this.ItemId, 10);
    this.ItemId = null;
    console.warn("item id reset", tmpId);
    scorm.connection.isActive = false;
    if (cluevo_scorm_window !== null) {
      cluevo_scorm_window.close();
      cluevo_scorm_window = null;
    }

    this._stopAutoCommit();

    let cancelClose = false;
    if (
      this._cluevo_progress &&
      this._cluevo_progress.hasOwnProperty("completion_status") &&
      this._cluevo_progress.hasOwnProperty("success_status") &&
      this._cluevo_progress.hasOwnProperty("lesson_status")
    ) {
      if (
        (this._cluevo_progress.completion_status == "completed" &&
          this._cluevo_progress.success_status == "passed") ||
        this._cluevo_progress.lesson_status == "passed"
      ) {
        cancelClose = window.dispatchEvent(
          new CustomEvent("cluevo_module_passed", { detail: tmpId }),
        );
        if (!cancelClose) {
          location.reload(true);
        }
      }
    }

    if (!cancelClose && !cluevo_doing_rating) {
      cluevoCloseLightbox();
    }
    // this._cluevo_progress = {};
    this._terminated = true;
    this._terminating = false;
    this.reset();
    return "true";
  },
  LMSInitialize: function () {
    return this.Initialize();
  },
  LMSFinish: function () {
    return this.Terminate();
  },
  LMSGetValue: function (key) {
    return this.GetValue(key);
  },
  LMSSetValue: function (key, value) {
    return this.SetValue(key, value);
  },
  LMSCommit: function () {
    return this.Commit();
  },
  LMSGetLastError: function () {
    return this.GetLastError();
  },
  LMSGetErrorString: function () {
    return this.GetLastErrorString();
  },
  LMSGetDiagnostic: function () {
    return this.GetDiagnostic();
  },
  _startAutoCommit: function () {
    if (this._autoCommitInit) return;
    if (cluevoCommitTimer) {
      clearInterval(cluevoCommitTimer);
    }
    if (!cluevoCommitTimer && cluevoWpApiSettings.commitInterval) {
      let t = parseInt(cluevoWpApiSettings.commitInterval, 10);
      if (!isNaN(t) && t >= 10) {
        t *= 1000;
        let api = this;
        cluevoCommitTimer = setInterval(function () {
          api.Commit();
        }, t);
      }
    }
    this._autoCommitInit = true;
  },
  _stopAutoCommit: function () {
    if (cluevoCommitTimer) {
      console.log("stopping auto commit", cluevoCommitTimer);
      clearInterval(cluevoCommitTimer);
    }
    this._autoCommitInit = false;
  },
  _autoCommitInit: false,
  _cluevo_progress: {
    attempt_id: null,
    completion_status: null,
    credit: null,
    date_added: null,
    date_modified: null,
    date_started: null,
    date_user_deleted: null,
    is_practice: null,
    lesson_status: null,
    module_id: null,
    progress: null,
    score_max: null,
    score_min: null,
    score_raw: null,
    score_scaled: null,
    success_status: null,
    user_deleted: null,
    user_id: null,
  },
  _terminated: false,
  _terminating: false,
  _committing: false,
  _lastCommit: null,
  _lastCommitError: null,
};

//var API = API_1484_11;
var API_1484_11 = null;
var API = null;

function initCluevoLmsApi(itemId, module, skipPrompt) {
  skipPrompt = skipPrompt || false;
  if (cluevo_scorm_window === null) {
    var url =
      cluevoWpApiSettings.root + "cluevo/v1/modules/" + itemId + "/parameters";
    module = module || false;
    jQuery.ajax({
      url: url,
      method: "GET",
      contentType: "application/json",
      dataType: "json",
      beforeSend: function (xhr) {
        xhr.setRequestHeader("X-WP-Nonce", cluevoWpApiSettings.nonce);
      },
      success: function (response) {
        if (response.hasOwnProperty("_scorm_version")) {
          scorm.version = response._scorm_version;
          if (scorm.version === "1.2") {
            API_1484_11 = null;
            API = scorm_api;
          } else {
            API = null;
            API_1484_11 = scorm_api;
          }
        }
        if (
          !skipPrompt &&
          response.hasOwnProperty("cmi.suspend_data") &&
          !response["_resume"]
        ) {
          if (
            response["cmi.suspend_data"].value &&
            response["cmi.suspend_data"].value != ""
          ) {
            var startOver = showResumePrompt(
              itemId,
              response,
              function (value, parms) {
                if (value == true && parms) {
                  response = parms;
                }
                scorm_api.prime(itemId);
                var lmsConnected = scorm.init();
                var curApi = scorm.API.get();
                curApi.Values = {};
                for (var key in response) {
                  if (response[key]) curApi.Values[key] = response[key].value;
                }
                console.log(
                  "init cluevo lms api with resume, setting api item id",
                  itemId,
                );
                curApi.ItemId = itemId;
                curApi.StartSession(response?.["cluevo.session_id"] ?? null);
                if (!lmsConnected) {
                  // TODO: Handle lms connection failed
                  console.error("LMS CONNECTION FAILED");
                  cluevoAlert(
                    cluevoStrings.message_title_error,
                    cluevoStrings.lms_connection_error,
                    "error",
                  );
                } else {
                  if (module !== false) {
                    cluevo_scorm_window = window.open(module);
                  }
                }
              },
            );
          }
        } else {
          scorm_api.prime(itemId);
          var lmsConnected = scorm.init();
          var curApi = scorm.API.get();
          curApi.Values = {};
          for (var key in response) {
            if (response[key]) curApi.Values[key] = response[key].value;
          }
          console.log(
            "init cluevo lms api without suspend data, setting api item id",
            itemId,
          );
          curApi.ItemId = itemId;
          curApi.StartSession(response?.["cluevo.session_id"] ?? null);
          if (!lmsConnected) {
            // TODO: Handle lms connection failed
            console.error("LMS CONNECTION FAILED");
            cluevoAlert(
              cluevoStrings.message_title_error,
              cluevoStrings.lms_connection_error,
              "error",
            );
          } else {
            if (module !== false) {
              cluevo_scorm_window = window.open(module);
            }
          }
        }
      },
      error: function (xhr) {
        if (xhr.responseJSON) {
          cluevoAlert(
            cluevoStrings.message_title_error,
            xhr.responseJSON.message,
            "error",
          );
        } else {
          cluevoAlert(
            cluevoStrings.message_title_error,
            cluevoStrings.message_unknown_error,
            "error",
          );
        }
      },
    });
  } else {
    cluevoAlert(
      cluevoStrings.message_title_error,
      cluevoStrings.message_module_already_running,
      "error",
    );
  }
}

async function initIframe(itemId, module, next) {
  var url =
    cluevoWpApiSettings.root + "cluevo/v1/modules/" + itemId + "/parameters";
  module = module || false;
  var success = false;
  await jQuery.ajax({
    url: url,
    method: "GET",
    contentType: "application/json",
    dataType: "json",
    beforeSend: function (xhr) {
      xhr.setRequestHeader("X-WP-Nonce", cluevoWpApiSettings.nonce);
    },
    success: function (response) {
      if (response.hasOwnProperty("_scorm_version")) {
        scorm.version = response._scorm_version;
        if (scorm.version === "1.2") {
          API_1484_11 = null;
          API = scorm_api;
        } else {
          API = null;
          API_1484_11 = scorm_api;
        }
      }
      if (response.hasOwnProperty("cmi.suspend_data") && !response["_resume"]) {
        if (
          response["cmi.suspend_data"].value &&
          response["cmi.suspend_data"].value != ""
        ) {
          var startOver = showResumePrompt(
            itemId,
            response,
            function (value, parms) {
              // cluevoCloseLightbox();
              if (value == true && parms) {
                response = parms;
              }
              iframeInitSuccess(
                itemId,
                module,
                response,
                function (success, module) {
                  if (typeof next === "function") {
                    if (success) {
                      next(true, module);
                    } else {
                      next(false);
                    }
                  }
                },
              );
            },
          );
        }
      } else {
        iframeInitSuccess(itemId, module, response, function (success, module) {
          if (typeof next === "function") {
            if (success) {
              next(true, module);
            } else {
              next(false);
            }
          }
        });
      }
    },
  });
  return success;
}

function iframeInitSuccess(itemId, module, response, next) {
  console.log("initializing iframe", itemId, module);
  jQuery("iframe#cluevo-module-iframe").attr(
    "src",
    jQuery("iframe#cluevo-module-iframe").data("src"),
  );
  scorm_api.prime(itemId);
  var lmsConnected = scorm.init();
  var curApi = scorm.API.get();
  curApi.Values = {};
  for (var key in response) {
    if (response[key]) curApi.Values[key] = response[key].value;
  }
  curApi.StartSession(response?.["cluevo.session_id"] ?? null);
  console.log("iframe initialized, setting api item id prop", itemId);
  curApi.ItemId = itemId;
  if (!lmsConnected) {
    console.error("LMS not connected");
    // TODO: Handle lms connection failed
  } else {
    if (module !== false) {
      if (jQuery("#cluevo-module-iframe").length === 1) {
        jQuery("#cluevo-module-iframe").attr("src", module);
        jQuery([document.documentElement, document.body]).animate(
          {
            scrollTop: jQuery("#cluevo-module-iframe").offset().top,
          },
          500,
        );
        success = true;
        next(true, module);
      } else {
        success = true;
        next(true, module);
      }
    } else {
      next(false);
      success = false;
      return false;
    }
  }
}

function initApiWithItem(itemId, callback) {
  var url = cluevoWpApiSettings.root + "cluevo/v1/items/" + itemId;
  jQuery.ajax({
    url: url,
    method: "GET",
    contentType: "application/json",
    beforeSend: function (xhr) {
      xhr.setRequestHeader("X-WP-Nonce", cluevoWpApiSettings.nonce);
    },
    success: function (response) {
      callback(response);
    },
    error: function (xhr) {
      if (xhr.responseJSON) {
        cluevoAlert(
          cluevoStrings.message_title_error,
          xhr.responseJSON.message,
          "error",
        );
      } else {
        cluevoAlert(
          cluevoStrings.message_title_error,
          cluevoStrings.message_unknown_error,
          "error",
        );
      }
    },
  });
}

jQuery(document).ready(function () {
  jQuery(".cluevo-module-link").click(function (e) {
    cluevo_doing_rating = false;
    cluevoRemoveTileOverlays();
  });

  if (jQuery("video.cluevo-media-module").length > 0) {
    window.onbeforeunload = function (e) {
      var itemId = jQuery("video.cluevo-media-module").data("item-id");
      var video = jQuery("video.cluevo-media-module:first")[0];
      if (video && video.played.length > 0) {
        var max = video.duration;
        var score = video.ended ? video.duration : video.currentTime;
        var data = {
          id: moduleId,
          max: max,
          score: score,
        };

        var url =
          cluevoWpApiSettings.root + "cluevo/v1/items/" + itemId + "/progress";
        jQuery.ajax({
          url: url,
          method: "POST",
          contentType: "application/json",
          dataType: "json",
          data: JSON.stringify(data),
          beforeSend: function (xhr) {
            xhr.setRequestHeader("X-WP-Nonce", cluevoWpApiSettings.nonce);
          },
          success: function (response) {
            // TODO: Handle success
          },
        });
      }
    };
  }

  jQuery(".cluevo-module-link.cluevo-module-mode-popup").click(function (e) {
    var item = this;
    e.preventDefault();
    if (cluevo_scorm_window !== null) {
      cluevo_scorm_window.close();
      cluevo_scorm_window = null;
      scorm.connection.isActive = false;
    }
    var data = jQuery(this).data();
    var itemId = data.itemId;
    var type = data.moduleType;
    if (type === "scorm 2004") {
      var url = cluevoWpApiSettings.root + "cluevo/v1/items/" + itemId;
      jQuery.ajax({
        url: url,
        method: "GET",
        contentType: "application/json",
        beforeSend: function (xhr) {
          xhr.setRequestHeader("X-WP-Nonce", cluevoWpApiSettings.nonce);
        },
        success: function (response) {
          if (response.access) {
            if (response.scos && response.scos.length > 0) {
              if (response.scos.length == 1) {
                initCluevoLmsApi(itemId, response.scos[0].href);
              } else {
                showScoSelect(itemId, response.scos, async function (e) {
                  var href = jQuery(this).attr("href");
                  initCluevoLmsApi(itemId, href);
                  return;
                });
                return;
              }
            } else {
              if (response.iframe_index) {
                initCluevoLmsApi(itemId, response.iframe_index);
              } else {
                cluevoAlert(
                  cluevoStrings.message_title_error,
                  cluevoStrings.error_loading_module,
                  "error",
                );
              }
            }
          } else {
            let text =
              data.access_denied_text && data.access_denied_text != ""
                ? data.access_denied_text
                : cluevoStrings.message_access_denied;
            cluevoAlert(
              cluevoStrings.message_title_access_denied,
              text,
              "error",
            );
          }
        },
      });
    } else {
      switch (type) {
        case "audio":
        case "video":
          var url = cluevoWpApiSettings.root + "cluevo/v1/items/" + itemId;
          jQuery.ajax({
            url: url,
            method: "GET",
            contentType: "application/json",
            dataType: "json",
            beforeSend: function (xhr) {
              xhr.setRequestHeader("X-WP-Nonce", cluevoWpApiSettings.nonce);
            },
            success: function (response) {
              switch (type) {
                case "audio":
                case "video":
                  var mediaWindow = window.open(response.iframe_index);
                  mediaWindow.onbeforeunload = function (e) {
                    var video = jQuery(mediaWindow.document)
                      .find("video")
                      .first()[0];
                    var itemId = response.item_id;
                    if (video) {
                      var max = video.duration;
                      var score = video.ended
                        ? video.duration
                        : video.currentTime;
                      var data = {
                        id: moduleId,
                        max: max,
                        score: score,
                      };

                      var url =
                        cluevoWpApiSettings.root +
                        "cluevo/v1/items/" +
                        itemId +
                        "/progress";
                      jQuery.ajax({
                        url: url,
                        method: "POST",
                        contentType: "application/json",
                        dataType: "json",
                        data: JSON.stringify(data),
                        beforeSend: function (xhr) {
                          xhr.setRequestHeader(
                            "X-WP-Nonce",
                            cluevoWpApiSettings.nonce,
                          );
                        },
                        success: function (response) {
                          // TODO: Handle success
                        },
                      });
                    }
                  };
                  break;
                default:
              }
            },
          });
          break;
        default:
      }
    }
  });

  jQuery(".cluevo-module-link.cluevo-module-mode-lightbox").click(function (e) {
    e.preventDefault();
    var data = jQuery(this).data();
    var itemId = data.itemId;
    var moduleId = data.moduleId;
    var type = data.moduleType;
    if (type === "scorm 2004") {
      initApiWithItem(itemId, async function (response) {
        scorm.connection.isActive = false;
        if (response.access) {
          if (response.scos && response.scos.length > 0) {
            if (response.scos.length == 1) {
              var success = await initIframe(
                itemId,
                response.scos[0].href,
                function (success, module) {
                  if (success) {
                    cluevoOpenLightbox(data);
                    cluevoShowLightbox();
                    cluevoShowLightboxSpinner();
                    var iframe = jQuery(
                      '<iframe src="' + module + '"></iframe>',
                    );
                    iframe.on("load", handleIframeLoaded);
                    iframe.appendTo("#cluevo-module-lightbox-overlay");
                    cluevoHideLightboxSpinner();
                  }
                },
              );
            } else {
              showScoSelect(itemId, response.scos, async function (e) {
                e.preventDefault();
                cluevoOpenLightbox(data);
                cluevoShowLightbox();
                cluevoShowLightboxSpinner();
                var success = await initIframe(
                  itemId,
                  e.target.href,
                  function (success, module) {
                    if (success) {
                      var iframe = jQuery(
                        '<iframe src="' + module + '"></iframe>',
                      );
                      iframe.on("load", handleIframeLoaded);
                      iframe.appendTo("#cluevo-module-lightbox-overlay");
                      cluevoHideLightboxSpinner();
                    }
                  },
                );
              });
              return;
            }
          } else {
            var success = await initIframe(
              itemId,
              response.iframe_index,
              function (success) {
                cluevoOpenLightbox(data);
                cluevoShowLightbox();
                if (!success) {
                  jQuery("#cluevo-module-lightbox-overlay")
                    .find(".cluevo-spinner-container")
                    .fadeOut(500, function () {
                      jQuery(this).remove();
                      jQuery(
                        '<div class="cluevo-error"><div class="cluevo-error-msg">' +
                          cluevoStrings.error_loading_module +
                          '</div><div class="cluevo-btn cluevo-error-close-button auto">' +
                          cluevoStrings.error_message_close +
                          "</div></div>",
                      ).appendTo(jQuery("#cluevo-module-lightbox-overlay"));
                    });
                } else {
                  var iframe = jQuery(
                    '<iframe src="' + response.iframe_index + '"></iframe>',
                  );
                  iframe.on("load", handleIframeLoaded);
                  iframe.appendTo("#cluevo-module-lightbox-overlay");
                  cluevoHideLightboxSpinner();
                }
              },
            );
          }
        } else {
          let text =
            data.access_denied_text && data.access_denied_text != ""
              ? data.access_denied_text
              : cluevoStrings.message_access_denied;
          cluevoAlert(cluevoStrings.message_title_access_denied, text, "error");
        }
      });
    } else {
      if (type == "audio" || type == "video") {
        var url = cluevoWpApiSettings.root + "cluevo/v1/items/" + itemId;
        jQuery.ajax({
          url: url,
          method: "GET",
          contentType: "application/json",
          dataType: "json",
          beforeSend: function (xhr) {
            xhr.setRequestHeader("X-WP-Nonce", cluevoWpApiSettings.nonce);
          },
          success: function (response) {
            switch (type) {
              case "audio":
              case "video":
                jQuery(
                  '<div id="cluevo-module-lightbox-overlay" data-module-id="' +
                    response.module_id +
                    '"' +
                    'data-item-id="' +
                    itemId +
                    '" class="cluevo-media"><video src="' +
                    response.iframe_index +
                    '" autoplay controls></video><div class="cluevo-close-button cluevo-btn cluevo-media">&times;</div></div>',
                )
                  .css({ display: "flex" })
                  .appendTo("body");
                jQuery("body, html").addClass("cluevo-module-overlay-active");
                jQuery("#cluevo-module-lightbox-overlay").fadeIn();
                break;
              default:
            }
          },
          error: function (xhr) {
            if (xhr.responseJSON && xhr.responseJSON.message) {
              cluevoCloseLightbox(true);
              cluevoAlert(
                cluevoStrings.message_title_error,
                xhr.responseJSON.message,
                "error",
              );
            } else {
              cluevoAlert(
                cluevoStrings.message_title_error,
                cluevoStrings.message_unknown_error,
                "error",
              );
            }
          },
        });
      }
    }
  });

  jQuery(document).on(
    "click",
    "#cluevo-module-lightbox-overlay div.cluevo-close-button, #cluevo-module-lightbox-overlay div.cluevo-error-close-button",
    function () {
      if (jQuery(this).hasClass("cluevo-media")) {
        var video = jQuery("#cluevo-module-lightbox-overlay")
          .find("video")
          .first()[0];
        var itemId = jQuery("#cluevo-module-lightbox-overlay").data("item-id");
        var moduleId = jQuery("#cluevo-module-lightbox-overlay").data(
          "module-id",
        );
        if (video) {
          var max = video.duration;
          var score = video.ended ? video.duration : video.currentTime;
          var data = {
            id: moduleId,
            max: max,
            score: score,
          };

          var url =
            cluevoWpApiSettings.root +
            "cluevo/v1/items/" +
            itemId +
            "/progress";
          jQuery.ajax({
            url: url,
            method: "POST",
            contentType: "application/json",
            dataType: "json",
            data: JSON.stringify(data),
            beforeSend: function (xhr) {
              xhr.setRequestHeader("X-WP-Nonce", cluevoWpApiSettings.nonce);
            },
            success: function (response) {
              // TODO: Handle succcess
            },
          });
        }
      }
      if (scorm_api.Initialized) {
        cluevoCloseLightbox();
        // let res = scorm_api.Terminate();
        // if (res === "true") {
        //   cluevoCloseLightbox();
        // }
      } else {
        if (!cluevo_doing_rating) {
          cluevoCloseLightbox();
        }
      }
    },
  );

  if (jQuery("#cluevo-module-iframe").length > 0) {
    var data = jQuery("#cluevo-module-iframe").data();
    var itemId = data.itemId;
    initApiWithItem(itemId, async function (response) {
      scorm.connection.isActive = false;
      if (response.access) {
        if (response.scos && response.scos.length > 0) {
          if (response.scos.length == 1) {
            cluevoShowLightboxSpinner();
            var success = await initIframe(
              itemId,
              response.scos[0].href,
              function (success) {
                if (success) {
                  jQuery(
                    "#cluevo-module-lightbox-overlay .cluevo-sco-select-container",
                  ).hide();
                }
                cluevoCloseLightbox();
              },
            );
          } else {
            showScoSelect(itemId, response.scos, async function (e) {
              e.preventDefault();
              cluevoShowLightboxSpinner();
              var success = await initIframe(itemId, jQuery(this).attr("href"));
              if (success) {
                jQuery(
                  "#cluevo-module-lightbox-overlay .cluevo-sco-select-container",
                ).hide();
              }
            });
          }
          return;
        } else {
          initIframe(itemId, response.iframe_index);
        }
      } else {
        let text =
          data.access_denied_text && data.access_denied_text != ""
            ? data.access_denied_text
            : cluevoStrings.message_access_denied;
        cluevoAlert(cluevoStrings.message_title_access_denied, text, "error");
      }
    });
  }
});

async function cluevoGetItem(itemId) {
  const url =
    cluevoWpApiSettings.root + "cluevo/v1/items/" + parseInt(itemId, 10);
  const response = await fetch(url, {
    method: "GET",
    method: "get",
    credentials: "same-origin",
    headers: {
      "Content-Type": "application/json; charset=utf-8",
      Accept: "application/json",
      "X-WP-Nonce": cluevoWpApiSettings.nonce,
    },
  });
  if (!response.ok) {
    const err = await response?.json?.();
    if (err?.message) {
      return Error(err.message);
    } else {
      throw new Error("error getting item");
    }
  }
  if (!response) return false;
  const result = (await response?.json?.()) ?? false;
  return result;
}

function cluevoRemoveTileOverlays() {
  jQuery(".cluevo-sco-select-container").remove();
  jQuery(".cluevo-module-tile-overlay").remove();
}

async function showScoSelect(itemId, list, startFunc) {
  cluevoRemoveTileOverlays();
  let el =
    '<div class="cluevo-sco-select-container"><h2>' +
    cluevoStrings.sco_select_title +
    "</h2><ul>";
  var items = list.map(function (el) {
    return '<li><a href="' + el.href + '">' + el.title + "</a></li>";
  });
  el += items.join("\n");
  el += "</ul></div>";
  var sel = jQuery(el);
  sel.on("click", "a", startFunc);
  sel.on("click", "a", function (e) {
    e.stopPropagation();
    e.preventDefault();
    jQuery(sel).remove();
  });
  jQuery(
    '.cluevo-content-item-link[data-item-id="' +
      itemId +
      '"] .cluevo-post-thumb:first',
  ).append(sel);
  jQuery(sel).fadeIn();
}

async function showResumePrompt(itemId, data, callback) {
  cluevoRemoveTileOverlays();
  var el =
    '<div class="cluevo-module-tile-overlay"><h2>' +
    cluevoStrings.start_over_dialog_header +
    '</h2><div class="cluevo-prompt-btns-container"><div class="cluevo-btn yes">' +
    cluevoStrings.start_over_opt_reset +
    '</div><div class="cluevo-btn no">' +
    cluevoStrings.start_over_opt_resume +
    "</div></div></div>";
  var dialog = jQuery(el);
  var needLightbox =
    jQuery(
      '.cluevo-content-item-link[data-item-id="' +
        itemId +
        '"] .cluevo-post-thumb:first',
    ).length === 0;
  dialog.on("click", ".cluevo-btn.yes", async function () {
    cluevoShowLightboxSpinner();
    var url =
      cluevoWpApiSettings.root + "cluevo/v1/modules/" + itemId + "/new-attempt";
    await jQuery.ajax({
      url: url,
      method: "GET",
      contentType: "application/json",
      dataType: "json",
      beforeSend: function (xhr) {
        xhr.setRequestHeader("X-WP-Nonce", cluevoWpApiSettings.nonce);
      },
      success: function (response) {
        jQuery(dialog).remove();
        if (needLightbox) {
          cluevoCloseLightbox();
        }
        callback(true, response);
      },
    });
  });
  dialog.on("click", ".cluevo-btn.no", function (e) {
    e.stopPropagation();
    e.preventDefault();
    jQuery(dialog).remove();
    callback(false);
  });
  dialog.on("click", ".cluevo-btn:not(.no)", function (e) {
    e.stopPropagation();
    e.preventDefault();
    jQuery(dialog).remove();
    if (!needLightbox) {
      cluevoCloseLightbox();
    }
  });
  if (!needLightbox) {
    jQuery(
      '.cluevo-content-item-link[data-item-id="' +
        itemId +
        '"] .cluevo-post-thumb:first',
    ).append(dialog);
  } else {
    cluevoOpenLightbox(null, "", dialog);
    cluevoShowLightbox();
  }
  jQuery(dialog).fadeIn();
}

async function cluevoShowModuleRating(itemId, moduleId, progress, startFunc) {
  cluevo_doing_rating = true;
  if (!jQuery("body").hasClass("logged-in")) return;
  cluevoRemoveTileOverlays();
  let el =
    '<div class="cluevo-rating-overlay-container cluevo-module-tile-overlay"><h2 class="cluevo-rating-headline">' +
    cluevoStrings.rate +
    '</h2><div id="cluevo-rating-target"></div>';
  el += "</div>";
  var sel = jQuery(el);
  sel.on("change", "select", startFunc);
  sel.on(
    "click",
    ".cluevo-module-rating-container, .cluevo-module-rating",
    function (e) {
      e.stopPropagation();
      e.preventDefault();
    },
  );
  let rating = cluevoCreateRatingApp({
    rating: 3,
    itemId: itemId,
    moduleId: moduleId,
    progress: progress,
  });
  cluevoChangeLightboxContent(sel);
  rating.mount("#cluevo-rating-target");
  // jQuery("#cluevo-rating-target").append(rating.$el);
  jQuery(sel).fadeIn(100, function () {
    jQuery(sel).css("display", "flex");
  });
}

jQuery(window).on("cluevo_closing_lightbox", function (e) {
  let progress = JSON.parse(JSON.stringify(scorm_api._cluevo_progress));
  if (cluevoSettings.ratingsEnabled == 0) return;
  if (cluevoSettings.ratingsEnabled == 1) {
    if (cluevoSettings.ratingsTrigger !== "always") {
      if (cluevoSettings.ratingsTrigger === "success") {
        if (
          progress &&
          progress.success_status !== "passed" &&
          progress.lesson_status !== "passed"
        )
          return;
      }
      if (cluevoSettings.ratingsTrigger === "completion") {
        if (
          progress &&
          progress.completion_status !== "completed" &&
          progress.lesson_status !== "completed" &&
          progress.lesson_status !== "passed" &&
          progress.lesson_status !== "failed"
        )
          return;
      }
    }
  }
  progress = progress || null;
  if (!e || !e.detail || !e.detail.moduleId || cluevo_doing_rating) return;
  e.preventDefault();
  cluevoShowModuleRating(
    e.detail.itemId,
    e.detail.moduleId,
    progress,
    function (event) {},
  );
});

function handleIframeLoaded() {
  jQuery("#cluevo-module-lightbox-overlay")
    .find(".cluevo-spinner-container")
    .fadeOut(500, function () {
      jQuery(this).remove();
    });
}
jQuery(".iframe-sco-select").change(function (e) {
  var itemId = jQuery("iframe#cluevo-module-iframe").data("item-id");
  if (jQuery(this).val() != 0) {
    jQuery("iframe#cluevo-module-iframe").attr("src", jQuery(this).val());
    initCluevoLmsApi(itemId);
  }
});

function cluevoCreateRatingApp(options) {
  options = options || {};
  const cluevoModuleRatingApp = {
    name: "cluevo-module-rating",
    props: ["rating", "itemId", "moduleId", "progress"],
    data: function () {
      return {
        value: 1,
        hovering: 1,
        working: false,
      };
    },
    methods: {
      rate: function (e, n) {
        e.stopPropagation();
        e.preventDefault();
        this.value = parseInt(n, 10);
        this.working = true;
        jQuery
          .post(cluevoModuleRatings.ajax_url, {
            action: cluevoModuleRatings.action,
            rating: n,
            moduleId: this.moduleId,
            itemId: this.itemId,
            progress: this.progress,
            nonce: cluevoModuleRatings.nonce,
          })
          .done(function () {
            this.working = false;
            location.reload();
            cluevoCloseLightbox(true);
          })
          .error(function () {
            this.working = false;
            cluevoCloseLightbox(true);
          });
      },
    },
    template: `
  <div id="cluevo-module-rating" class="cluevo-module-rating-container">
    <div v-if="!working" class="cluevo-module-stars">
      <div
        v-for="n in 5"
        class="cluevo-module-rating"
        :class="[{ filled: hovering >= n }]"
        @mouseover="hovering = n"
        @click="rate($event, n)"
      ></div>
    </div>
    <div v-else>{{ $strings.working }}</div>
  </div>
  `,
  };
  const rating = Vue.createApp(cluevoModuleRatingApp, options);
  rating.config.globalProperties.$strings = cluevoStrings;
  return rating;
}

// const cluevoModuleRating = Vue.extend(cluevoModuleRatingComp);
