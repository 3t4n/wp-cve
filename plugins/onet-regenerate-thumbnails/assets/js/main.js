jQuery(document).ready(function () {
	var $ = jQuery, // jQuety alias
	lockUnload = null, // Locked unload
	
	/**
	* Run regenrating process
	**/
	runProcess = function () {
		// Ger base variables
		var item_num = window.onetrt_taskdata.item_total,
		task_id = window.onetrt_taskdata.task_id;
		// Default variables
		var startTime = new Date(),                        // Time of first request
		lastAjaxTime = 0,                                  // Time of last ajax request 
		doneItems = 0,                                     // Done items
		retry = 0,                                         // Actual number of retries
		maxRetry = 5,                                      // Number of maximum retries in a row before the script terminates
		asyncUpdThreshold = 4,                             // If sendRequest does not deliver update in n seconds the async status update will be performed
		liveTimeCounter = 0,                               // This variable contains the actual number for live time meter
		terminated = false;                                // Status of script
		// DOM elements cache
		var logElem = $("#progresslog"),                    // Log element
		remainingElem = $("#progressbar .remaining span"),  // Remaining elements number
		estTimeElem = $("#progressbar .estimated span"),    // Estimated time element
		percentElem = $("#progressbar .percent"),           // Percentage of elements done
		progressbarElem = $("#progressbar .bar" ),          // The progressbar itself
		nonce = $("input[name='onetrt_nonce']").val();      // Nonce field to keep the ajax safe from arbitrary queries
		// AJAX query holders
		var actualAjaxWrap = null,                          // Variable for main ajax so it can be modified on flow.
		actualAsyncUpd = null;								// Variable for async status updater ajax query
		
		// Log a message
		log = function (text,classes) {
			if (typeof classes != "undefined" && classes.match(/terminated/i)) terminated = true;
			logElem.prepend('<li class="'+(typeof classes !== "undefined" ? classes : "")+'">'+text+'</li>');
		}

		// Convert milisecs to "human" time
		humanTime = function(seconds) {
			if (typeof seconds === "undefined") return onetrt_optspage.time_infinity;
			if (typeof seconds !== "number") seconds = parseInt(seconds);
			if (seconds < 0) seconds = 0;

			var tryVal = Math.floor(seconds / 31536000);
			if (tryVal > 1) return tryVal + onetrt_optspage.time_year;

			tryVal = Math.floor(seconds / 2592000);
			if (tryVal > 1) return tryVal + onetrt_optspage.time_month;

			tryVal = Math.floor(seconds / 86400);
			if (tryVal >= 1) return tryVal + onetrt_optspage.time_day;

			tryVal = Math.floor(seconds / 3600);
			if (tryVal >= 1) return tryVal + onetrt_optspage.time_hour;

			tryVal = Math.floor(seconds / 60);
			if (tryVal > 1) return tryVal + onetrt_optspage.time_min;

			return Math.floor(seconds) + onetrt_optspage.time_sec;
		}

		// Get remaining time
		getRemainingTime = function () {
			var percent = (doneItems / item_num) * 100,
			elapsed = ((new Date()).getTime() - startTime.getTime())/1000,
			remaining = ((elapsed/doneItems) * (item_num-doneItems));
			if (remaining < 0) remaining = 0;
			return {'percent':percent,'remaining':remaining};
		}

		/*
		This function in disabled or the time being
		// This function does nothing else than decreasing time in UI in each second so user will know that the scrtipt actually working.
		makeTimeLive = function () {
			var t = getRemainingTime();
			if (t.remaining > 0 && !terminated) estTimeElem.html( humanTime(t.remaining-liveTimeCounter) );
			liveTimeCounter++;
		}
		*/
		makeTimeLive = function () {}

		// Update the UI elements
		updateProgress = function () {
			var t = getRemainingTime();

			estTimeElem.html( humanTime(t.remaining) );
			remainingElem.html(item_num-doneItems);
			percentElem.html( Math.ceil(t.percent) + "%");
			progressbarElem.progressbar( "option", "value", t.percent );
		}

		// Do a request which will update images
		sendRequest = function () {
			if (terminated) return; // Do nothing if script is terminated
			if (retry >= maxRetry) {
				log(onetrt_optspage.error_terminated,"terminated");
				lockUnload = null;
			} else if (item_num <= doneItems) {
				log(onetrt_optspage.success_done,"done");
				lockUnload = null;
				terminated = true;
				if (onetrt_opts.success_redir == "1") window.location.href = $("input[name='onetrt_success']").val();
			} else {
				lastAjaxTime = (new Date()).getTime();

				actualAjaxWrap = $.ajax(ajaxurl+"?action=onetrt&nonce="+nonce+"&task_id="+task_id,{
					cache: false,       // turn off browser cache
					dataType: "json",   // Answer should be in JSON
					type: "get",        // Query type is get, not post
					timeout: 60*1000,        // Server side script will automatically terminate process in 60 seconds so do this script on client side.
					
					// Get those errors
					error: function (a,type) {
						// Sometimes the query fails because timeout or abort by the code itself which requires different handle
						if (type == "timeout" || type == "abort") {
							log(onetrt_optspage.error_intended,"error");
						} else {
							log(onetrt_optspage.error_retrissue.replace(/\{retryNum\}/i,maxRetry-retry),"error");
							retry++; // increase retry counter
						}
						sendRequest(); // request retry
					},

					// Hell yeah, the request was completed, cool.
					success: function (data,b,reguest) { resolveResponse(data,"main"); }
				});
			}
		}

		// Helper function to fetch actual status.
		asyncStatusUpdate = function () {
			if (terminated || lastAjaxTime < 1) return; // Do nothing if script is terminated
			var atm = (new Date()).getTime();
			if (lastAjaxTime > atm - (asyncUpdThreshold*1000) ) return;
			else {
				lastAjaxTime = (new Date()).getTime(); // Update last ajax time to prevent async status update from run

				actualAsyncUpd = $.ajax(ajaxurl+"?action=onetrt&nonce="+nonce+"&task_id="+task_id+"&status_update=1",{
					cache: false,       // turn off browser cache
					dataType: "json",   // Answer should be in JSON
					type: "get",        // Query type is get, not post
					timeout: 60*1000,        // Server side script will automatically terminate process in 60 seconds so do this script on client side.
					
					// This is not the best but because status update error is not cardinal issue keep it low profile. In short: Say nothing!
					error: function (a,type) { },
					// Send response data to resolver.
					success: function (data) { resolveResponse(data,"async"); }
				});
			}
		}

		// Resolve response data and update every single variable
		resolveResponse = function (data,mode) {
			if (terminated) return;
			// Check if response if a timeout error.
			if (typeof data === "string" && data.toLowerCase().match(/Maximum\ execution\ time/i)) {

			// Check if data code is missing which is similar to fatal error
			} else if (typeof data.code === "undefined") {
				log(onetrt_optspage.error_serverside,"terminated");
			// Check if 
			} else if (data.code != 200) {
				log(onetrt_optspage.error_ecode.replace(/\{errorCode\}/,data.code),"terminated");
			} else {
				// Clean rerty counter
				if (retry > 0) log(onetrt_optspage.notice_issueresolved);
				retry=0; // set retry to zero.
				// Set "live time variable" to zero
				liveTimeCounter=0;

				// Also, update client side task id
				task_id = data.task_meta.id;

				if (mode == "main") {
					// Output updated files
					if (data.files_done.length > 0) {
						var fileList = [];
						for (var i=0;i<data.files_done.length;i++) fileList.push( "<strong>"+data.files_done[i].name+"</strong>" );
						log( onetrt_optspage.success_thumbs_done.replace(/\{imageList\}/i,fileList.join(", ")) );
					}

					// Increase done item counter
					doneItems = parseInt(data.task_meta.items_done);
					// request next item
					sendRequest(); 
				} else {
					log(onetrt_optspage.success_update_itemsdone.replace(/\{fileNum\}/i,data.task_meta.items_done-doneItems));
					doneItems = parseInt(data.task_meta.items_done);
				}
				// Update progress
				updateProgress();
			}
		}

		// Initalize the stuff
		// If there is basically no items
		if (item_num < 1) {
			alert(onetrt_optspage.error_noimage);
			lockUnload = null;
			$("#onetrt-cancel").trigger("click");
		// Otherwise let's get the party started
		} else {
			log(onetrt_optspage.notice_started);
			sendRequest();
			setInterval(makeTimeLive,1000);
			setInterval(asyncStatusUpdate,1000);
		}
	}

	/**********************
	* Custom dim editor
	**********************/
	customDim = function () {
		var dim = $("#onetrt-customdim");
		var active = false;

		// Add actions to actual custom dimension editor elements
		bind = function () {
			if (dim.hasClass("appended")) return;
			dim.find("a").click(function (e) {
				execute(this.href);
				return false;
			});
			dim.find("form.editor").submit(function (e) {
				execute(this.action, $(this).serialize() + "&" + (dim.find("input[name='save_thumb']").length > 0 ? "save_thumb=1" : "update_thumb=1") );
				return false;
			});
		}

		// Execute panel update/post
		execute = function (url,post) {
			if (active == true) return;
			active = true;
			dim.addClass("load");
			$.post(url,post,function (data) {
				drop = $(data);
				dim.children(".inside").html(drop.find("#onetrt-customdim").children(".inside"));
				dim.removeClass("load appended");
				active = false;
				bind();
			});
		}

		bind();
	}

	// Init
	customDim();

	$("#progressbar .bar" ).progressbar({ value: 0 });
	$("#onetrt-start").click(function (e) {
		e.preventDefault();
		$("#onetRegenThumb .datadisplay").addClass("processing");
		$(this).prop({disabled:"disabled"});
		$("#onetrt-reset").addClass("hidden");
		$("#onetrt-cancel").removeClass("hidden");
		lockUnload = true;
		runProcess(); // Run regenrating process
	});

	// Prevent page from unload
	$(window).on('beforeunload', function(){
		if (lockUnload == true) {
			return onetrt_optspage.unload_confirm;
		}
	});

	// Check if URL should be updated
	if (window.onetrt_taskdata.update_url.length > 0 && window.onetrt_taskdata.task_id > 0) {
		if (typeof window.history !== "undefined")
			window.history.pushState(null, null, window.onetrt_taskdata.update_url);
	}

	// Hide message box after a while
	setTimeout(function () { $("#message.fade").fadeOut(300); }, 10000);
});