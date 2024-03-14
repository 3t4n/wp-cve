(function(){

	"use strict";

	const STILLBE_API_ORIGIN = "https://api.still-be.com";
	const STILLBE_AUTH_PATH  = "/wp/combine-social-photos/token/generate";

	const WP_AJAX_URL          = window.$stillbe?.admin.ajaxUrl || null;
	const WP_AJAX_NONCE        = window.$stillbe?.admin.nonce   || null;

	const ACTION_SET_AUTH_USER = window.$stillbe?.combineSocialPhotos?.action.setAuthUser  || null;
	const ACTION_REAUTH_USER   = window.$stillbe?.combineSocialPhotos?.action.reauthUser   || null;
	const ACTION_REFRESH_TOKEN = window.$stillbe?.combineSocialPhotos?.action.refreshToken || null;
	const ACTION_RESET_SETTING = window.$stillbe?.combineSocialPhotos?.action.resetSetting || null;
	const ACTION_UNLOCK_DB     = window.$stillbe?.combineSocialPhotos?.action.unlockDB     || null;

	const CLOSING_DURATION = 250;   // [msec]


	// Temporary Data
	const tempData = {};


	const __ = window.wp?.i18n?.__ || window.$stillbe?.func?.__ || (($id, $domain ) => {
		if(!("translate" in window.$stillbe.admin)){
			return $id;
		}
		return window.$stillbe?.admin?.translate[$id] || $id;
	});


	/**
	 *  Clone Template & Return Fragment
	 * 
	 *   @param  Element          $temp
	 *   @param  object           $params
	 *   @return DocumentFragment
	 */
	const cloneTemplate = ($temp, $params) => {

		if(!$temp){
			return new DocumentFragment();
		}

		const generateHTML = $temp.innerHTML.replace(/{{([^}]+)}}/g, ($a, $m) => {
			const objctKey = $m.split(".");
			let param = JSON.parse(JSON.stringify($params || {}));
			while(objctKey.length && param){
				param = param[objctKey.shift()] || "";
			}
			return param;
		});

		const dummyTemp = document.createElement("template");
		dummyTemp.innerHTML = generateHTML;

		return dummyTemp.content;

	};


	/**
	 *  Parse URL Parameters
	 * 
	 *   @param  string $temp
	 *   @return object
	 */
	const parseUrlParams = $url => {

		const url = (() => {
			const _url = $url.indexOf("?") ? $url : "https://example.com/" + $url;
			try{
				return new URL(_url);
			} catch($e){
				console.error($e);
				return {};
			}
		})();

		if(!url.search){
			return {};
		}

		const params = {};

		for(const pair of url.searchParams.entries()){
			const keys  = pair[0].split(/\s*(?:\]\s*\[|\[|\])\s*/).filter(Boolean);
			const value = pair[1];
			let _key, _parentPointer = params;
			while(_key = keys.shift()){
				if(!_parentPointer.hasOwnProperty(_key)){
					_parentPointer[_key] = keys.length ? {} : value;
				}
				_parentPointer = _parentPointer[_key];
			}
		}

		return params;

	};


	/**
	 *  Flatten object as if they were form data
	 * 
	 *   @param  string $temp
	 *   @return object
	 */
	const flatten_for_formdata = ($object, $base = null) => {

		if(!$object || typeof $object !== "object"){
			return {};
		}

		let newObject = {};

		for(const key in $object){
			const value  = $object[key];
			const _key   = !$base ? key : `${$base}[${key}]`;
			const _value = typeof value === "object" ?
			                 flatten_for_formdata(value, _key) :
			                 { [_key] : value };
			newObject = { ...newObject, ..._value };
		}

		return newObject;

	};


	/**
	 *  Set Tab Control
	 * 
	 *   @param  void
	 *   @return void
	 */
	const setTabControl = () => {

		const tabs = document.querySelectorAll(".settings-tabs-wrapper label");
		if(tabs.length < 1){
			return null;
		}

		const tabWrapper = tabs[0].parentNode;

		const changeActiveTab = function($pushState = true){
			const current  = document.querySelector(".settings-tabs-wrapper label.active");
			const isChange = $pushState && this !== current;
			tabs.forEach($t => {
				if(this === $t){
					$t.classList.add("active");
				} else{
					$t.classList.remove("active");
				}
			});
			if(isChange){
				const id     = this.getAttribute("for");
				const name   = this.innerText;
				const title  = document.getElementsByTagName("title")[0].innreText;
				const search = location.search.replace(/&tab=.+$/, "");
				window.history.pushState({ tab: id }, `${name} | ${title}`,`${search}&tab=${id}`);
				// _wp_http_referer
				const referer = document.querySelector("input[name='_wp_http_referer']");
				if(referer){
					referer.value = /([&\?])tab=[^&$]+/.test(referer.value) ?
					                  referer.value.replace(/([&\?])tab=[^&$]+/, `$1tab=${id}`) :
					                  referer.value + `&tab=${id}`;
				}
			}
			const leftEnd = (tabWrapper.clientWidth - this.clientWidth) / 2;
			const nowPos  = this.getBoundingClientRect().left - tabWrapper.getBoundingClientRect().left;
			tabWrapper.scrollBy({ top: 0, left: nowPos - leftEnd, behavior: "smooth" });
		};

		tabs.forEach($t => {
			$t.onclick = changeActiveTab;
		});

		const tabInit = parseUrlParams(location.href).tab || tabs[0].getAttribute("for");
		if(tabInit){
			const tab     = document.querySelector(`.settings-tabs-wrapper label[for='${tabInit}']`);
			const section = document.querySelector(`#${tabInit}`);
			if(tab && section){
				// Chnage Tab
				changeActiveTab.call(tab, false);
				section.click();
				// Replace State
				const id    = tab.getAttribute("for");
				const name  = tab.innerText;
				const title = document.getElementsByTagName("title")[0].innreText;
				window.history.replaceState({ tab: id }, `${name} | ${title}`, location.href);
			}
		}

		window.addEventListener("popstate", function($e){
			const state   = $e.state;
			const tab     = document.querySelector(`.settings-tabs-wrapper label[for='${state.tab}']`);
			const section = document.querySelector(`#${state.tab}`);
			if(tab && section){
				// Chnage Tab
				changeActiveTab.call(tab, false);
				section.click();
			}
		}, false);

	};


	// Open Authorization Window
	const openAuthWindow = function($callback){

		window.__stillbeCspTemp = $callback || null;
		const button = document.getElementById("open_auth_button");

		const locale = (wp => {
			if(!button || !wp?.i18n?.getLocaleData){
				return "en";
			}
			if(button?.dataset?.locale){
				return String(button.dataset.locale).replace(/\W/, "_");
			}
			const localeData = wp.i18n.getLocaleData();
			if(!localeData[""]){
				return "en";
			}
			return String(localeData[""]?.lang).replace(/\W/, "_");
		})(window.wp);

		// Additional Parameters (optional)
		const additionalParams = button?.dataset || {};
		delete additionalParams.locale;

		// Window Setting
		const authUrl    = new URL(STILLBE_API_ORIGIN + STILLBE_AUTH_PATH + "/" + locale);
		const winSetting = "menubar=no,location=no,resizable=yes,scrollbars=yes,status=no,width=820,height=720";

		// Append Request Parameters
		authUrl.searchParams.append("referer_url", location.href);
		for(const key in additionalParams){
			authUrl.searchParams.append(key, additionalParams[key]);
		}

		// Open Window
		const authWindow = window.open(authUrl.toString(), "still-be-combine-social-photos-oauth", winSetting);

		// If it cannot be opened on iOS or other devices, redirect the page
		const message = __("Go to the Authorization page. If there are settings you have not saved, click 'Cancel' to save your settings first.", "still-be-combine-social-photos");
		if(!authWindow){
			if(window.__stillbeCspTemp){
				alert(__("Your browser does not support child windows. Please try from the settings page.", "still-be-combine-social-photos"));
				return;
			}
			if(window.confirm(message)){
				location.href = openUrl + "&redirect=yes";
			}
			return;
		}

		// Waiting Screen
		const waiting = document.body.appendChild(document.createElement("div"));
		waiting.className = "waiting-screen";
		waiting.innerHTML = "<span class=\"message\">Connecting your Instagram Account....<br>See the opened window.</span>";
		waiting.style.transition = `${CLOSING_DURATION}ms`;

		// Check if the Window is Closed
		const timer = setInterval(() => {
			if(authWindow.closed){
				clearInterval(timer);
				if(waiting){
					waiting.style.opacity = 0;
					setTimeout(() => waiting && waiting.remove(), CLOSING_DURATION);
				}
			}
		}, 250);

	};


	// Set the Open button for Authorization
	const setOpenAuthWindowButton = () => {

		const button = document.getElementById("open_auth_button");
		if(!button){
			return false;
		}

		button.onclick = () => {
			tempData.runningActionRow = null;
			openAuthWindow.call(null, null);
		}

	};


	// Open Popup for Manually Resistering
	const openPopupManually = () => {

		const temp = document.getElementById("temp_popup_manually_account");
		if(!temp){
			return false;
		}

		// Generate Fragment from Template
		const frag = cloneTemplate(temp, {});

		// Elements
		const form   = frag.querySelector("#manually_set_account");
		const submit = frag.querySelector("#manually_set_account_submit");
		if(!form || !submit){
			alert(__("Something wrong. Please reload the page and try again.", "still-be-combine-social-photos"));
			return false;
		}

		// Set Submit Event
		submit.onclick = () => {
			if(!form.checkValidity()){
				return true;
			}
			const data = { action: "manual-setup" };
			for(const input of form.elements){
				if(input.type === "radio" && !input.checked){
					continue;
				}
				data[input.name] = input.value;
			}
			if(data["data[api]"] === "ig_graph"){
				data.api_type = "Graph API";
			}
			if(data["data[api]"] === "ig_basic_display"){
				data.api_type = "Basic Display API";
			}
			window.postMessage(data, location.origin);
			popup.style.opacity = 0;
			setTimeout(() => popup && popup.remove(), CLOSING_DURATION);
		};

		// Show
		document.body.appendChild(frag);

		// Set Duration
		const popup = document.querySelector("body > .popup-wrapper");
		popup.style.transition = `${CLOSING_DURATION}ms`;

		// Close
		popup.onclick = () => {
			popup.style.opacity = 0;
			setTimeout(() => popup && popup.remove(), CLOSING_DURATION);
		};

		// Stop Bubbling
		popup.firstChild.onclick = $event => $event.stopPropagation();

		return false;

	};


	// Set the Open Popup for Manually Resistering
	const setOpenPopUpManually = () => {

		const button = document.getElementById("manyually_set_account_popup_open");
		if(!button){
			return false;
		}

		button.onclick = openPopupManually;

	};


	// Update Reauth User
	const updateRowActions = $json => {

		if(!$json.ok || !$json.account){
			alert($json.message || __("Internal Server Error", "still-be-combine-social-photos"));
			return false;
		}

	//	console.log(tempData, tempData.runningActionRow);
		const tr      = tempData.runningActionRow;
		const account = $json.account || {};

		const expire  = tr.querySelector(".account-expire");
		const reauth  = tr.querySelector("button[data-action='reauth-token']");

		if(expire){
			expire.innerHTML = `<span>${account.expire_string}</span>`;
		}

		reauth?.remove();

	};


	// Insert Row & Set Actions
	const insertRowAndSetActions = $json => {

		if(!$json.ok || !$json.account){
			alert($json.message || __("Internal Server Error", "still-be-combine-social-photos"));
			return false;
		}

		if(window.__stillbeCspTemp){
			window.__stillbeCspTemp($json.account.id);
		}

		const tbody = document.querySelector(".account-list");
		if(!tbody){
		//	alert(__("The listing table does not exist. Please refresh the page.", "still-be-combine-social-photos"));
			return false;
		}

		const row = Math.max(...Array.prototype.map.call(tbody.querySelectorAll("tr"), $r => parseInt($r.dataset.row, 10)).filter(Boolean));

		$json.account.row = row + 1;

		$json.account.me = $json.account.me || {};

		if($json.account.me.account_type){
			$json.account.account_type = $json.account.me.account_type !== "PERSONAL" ? "Pro" : "Personal";
		}

		// Profile Picture
		if($json.account.me.profile_picture_url){
			$json.account.profile_picture_url = $json.account.me.profile_picture_url;
		}

		// Others
		$json.account.profile_picture_url = $json.account.me.profile_picture_url || "";
		$json.account.me.name = $json.account.me.name || $json.account.me.username || "";

		// Create Account Row
		const temp = document.getElementById("temp_account_row");
		const frag = cloneTemplate(temp, $json.account);

		// No Image
		if(!$json.account.profile_picture_url){
			const imgSelector = frag.querySelector(".image-selector");
			if(imgSelector){
				imgSelector.classList.add("no-image");
			}
		}

		// Expired Time is Unknown
		if(!$json.account.token.expire){
			const refreshTokenButton = frag.querySelector(".action-button[data-action='refresh-token']");
			if(refreshTokenButton){
				refreshTokenButton.disabled = false;
			}
		}

		// Set Image Selector
		const imageSelector = frag.querySelector(".image-selector-button");
		const removeImage   = frag.querySelector(".image-remove-button");
		if(imageSelector){
			imageSelector.onclick = openWPMediaSelector;
		}
		if(removeImage){
			removeImage.onclick = removeImage;
		}

		// Remove Refresh Button when Graph API
		if( $json.account.api_type === "Graph API" ) {
			frag.querySelector(".action-button[data-action='refresh-token']").remove();
		}

		// Set Action Buttons
		frag.querySelectorAll(".action-button").forEach($b => {
			$b.onclick = runAction;
		});

		// Insert Row
		tbody.insertBefore(frag, tbody.firstChild);

		// Hidden the No Accounts Row
		const noAccountsRow = document.getElementById("no_accounts");
		if(noAccountsRow){
			noAccountsRow.style.display = "none";
		}

	};


	// Receive postMessage from 'api.still-be.com' & Save an Authorized Account
	window.addEventListener("message", $event => {

		if($event.origin !== STILLBE_API_ORIGIN &&
		     !($event.source === window && $event.data.action === "manual-setup")){
			if($event.source !== window){
				$event.source.postMessage({ action: "close" }, $event.origin);
			}
			return false;
		}

		const isReauth = !!tempData.runningActionRow;

		const token = flatten_for_formdata($event.data);
		const data  = new FormData();

		for(const key in token){
			data.append(key, token[key]);
		}

		// Add the WP-Ajax Action Name & Nonce _Field
		data.append("_nonce", WP_AJAX_NONCE);
		data.delete("action");
		data.append("action", isReauth ? ACTION_REAUTH_USER : ACTION_SET_AUTH_USER);

		// Re-Authorization System ID
		if(isReauth){
			data.append("id", tempData.runningActionRow?.querySelector("[name$='[id]']")?.value || 0);
		}

		// Account List Wrapper (Parent Element of <table> Element)
		const scrollWrapper = document.querySelector(".accounts-table")?.parentNode;
		if(scrollWrapper && window.getComputedStyle(scrollWrapper).position === "static"){
			scrollWrapper.style.position = "relative";
		}

		// Loading...
		if(scrollWrapper){
			scrollWrapper.classList.add("waiting-post-anth-user");
		}

		// Request to WP-Ajax
		fetch(WP_AJAX_URL, {
			method : "POST",
			body   : data,
		})

		// Parse Response as JSON
		.then($res => $res.json())

		// Insert Row & Set Actions
		.then(isReauth ? updateRowActions : insertRowAndSetActions)

		// Catch Error
		.catch(console.error)

		// Remove Waiting Class
		.finally(() => {
			delete tempData.runningActionRow;
			if(!scrollWrapper){
				return false;
			}
			scrollWrapper.classList.remove("waiting-post-anth-user");
		});

		// Close Window
		$event.source.postMessage({ action: "close" }, $event.origin);

	}, false);


	// Replace to Refreshed Token
	const replaceToRefreshedToken = ($tr, $button, $json) => {

		if(!$json.ok || $json.code < 99 ){
			alert($json.message || __("Internal Server Error", "still-be-combine-social-photos"));
			if($json.code === 8){
				$button.disabled = true;
			}
			return false;
		}

		// Replace Account Info
		if($json.me){
			const username    = $tr.querySelector(".account-username span");
			const accountType = $tr.querySelector(".account-type span");
			const mediaCount  = $tr.querySelector(".account-media span");
			if(username){
				username.innerText = $json.me.username || __("Getting...", "still-be-combine-social-photos");
			}
			if(accountType){
				accountType.innerText = $json.me.account_type ?
				                          ($json.me.account_type !== "PERSONAL" ? "Pro" : "Personal") :
				                          __("Getting...", "still-be-combine-social-photos");
			}
			if(mediaCount){
				mediaCount.innerText = $json.me.media_count || __("Getting...", "still-be-combine-social-photos");
			}
		}

		// Replace Expire
		const expireString = $tr.querySelector(".account-expire span");
		if(expireString){
			expireString.innerText = $json.expire;
		}

		// Disable the Refresh Button
		$button.disabled = true;

		// Message!
		alert($json.message || __("Success!!", "still-be-combine-social-photos"));

	};


	// 
	const refreshToken = ($tr, $button) => {

		const id = $tr.querySelector("input[name$='[id]']")?.value;

		$button.classList.add("runing");

		// Request to WP-Ajax
		fetch(`${WP_AJAX_URL}?action=${ACTION_REFRESH_TOKEN}&account_id=${id}&_nonce=${WP_AJAX_NONCE}`)

		// Parse Response as JSON
		.then($res => $res.json())

		// Replace to Refreshed Token
		.then(replaceToRefreshedToken.bind(null, $tr, $button))

		// Catch Error
		.catch(console.error)

		// Remove Waiting Class
		.finally(() => {
			$button.classList.remove("runing");
		});

	};


	// 
	const reauthAccess = ($tr, $button) => {

		$button.classList.add("runing");

		tempData.runningActionRow = $tr;
		openAuthWindow.call(null, null);

	};


	// 
	const runAction = function(){

		const action = this.dataset.action;
		const row    = this.dataset.row;

		const thisTr = document.querySelector(`.account-row[data-row='${row}']`);
		if(!thisTr){
			return null;
		}

		switch(action){

			case "remove-account" :
				thisTr.style.maxHeight = 0;
				thisTr.style.opacity   = 0;
				const duration = window.getComputedStyle(thisTr).transitionDuration.replace(/(\d+\.?\d*)(m?s)/i, ($match, $num, $unit) => {
					return $num * (/ms/i.test($unit) ? 1 : 1000);
				});
				setTimeout(() => {
					thisTr.remove();
					const noAccountsRow = document.getElementById("no_accounts");
					if(noAccountsRow && document.getElementsByClassName("account-row").length < 1){
						noAccountsRow.style.display = "";
					}
				}, duration);
			break;

			case "reauth-token" :
				if(!this.disabled){
					reauthAccess(thisTr, this);
				}
			break;

			case "refresh-token" :
				if(!this.disabled){
					refreshToken(thisTr, this);
				}
			break;

		}

	};


	// 
	const setActionButton = () => {

		const actionButtons = document.querySelectorAll(".action-button");

		actionButtons.forEach($b => {
			$b.onclick = runAction;
		});

	};


	// Set Reset Button
	const setResetButton = () => {

		const resetButton = document.getElementById("reset_settings_button");
		if(!resetButton){
			return false;
		}

		const data = new FormData();
		data.append("_nonce", WP_AJAX_NONCE);
		data.append("action", ACTION_RESET_SETTING);

		resetButton.onclick = () => {
			if(!window.confirm(__("Delete all settings. This action cannot be undone.", "still-be-combine-social-photos"))){
				return false;
			}
			fetch(WP_AJAX_URL, {
				method : "POST",
				body   : data,
			})
			.then($res => $res.json())
			.then($json => {
				($json.ok ? console.log : console.error)($json);
				alert($json.message || "Done!");
				if($json.ok){
					location.reload();
				}
			})
			.catch(console.error);
		};

	};


	// Set Unlock Button
	const setUnlockButton = () => {

		const unlockButton = document.getElementById("unlock_database_button");
		if(!unlockButton){
			return false;
		}

		const data = new FormData();
		data.append("_nonce", WP_AJAX_NONCE);
		data.append("action", ACTION_UNLOCK_DB);

		unlockButton.onclick = () => {
			if(!window.confirm(__("Unlock the database. Is that okay?", "still-be-combine-social-photos"))){
				return false;
			}
			fetch(WP_AJAX_URL, {
				method : "POST",
				body   : data,
			})
			.then($res => $res.json())
			.then($json => {
				($json.ok ? console.log : console.error)($json);
				alert($json.message || "Done!");
				if($json.ok){
					location.reload();
				}
			})
			.catch(console.error);
		};

	};


	// Open WP Media Selector
	const openWPMediaSelector = function(){

		const container = ((_this) => {
			let element = _this;
			while(element && !element.classList.contains("image-selector")){
				element = element.parentNode;
			}
			return element;
		})(this);

		if(!container){
			console.error(__("No image selector found.", "still-be-combine-social-photos"));
			return false;
		}

		//
		const thumbnail  = container.querySelector(".image-thumbnail");
		const pictureUrl = container.querySelector("input[name$='[profile_picture_url]']");
		const pictureId  = container.querySelector("input[name$='[profile_picture_id]']");
		const button     = this;

		if(!thumbnail || !pictureUrl || !pictureId){
			console.error(__("No image selector found.", "still-be-combine-social-photos"), thumbnail, pictureUrl, pictureId);
			return false;
		}

		const selector = wp.media({
			title    : __("Select an image", "still-be-combine-social-photos"),
			library  : {
				type : 'image',
			},
			button   : {
				text : __("Set the image", "still-be-combine-social-photos"),
			},
			multiple : false,
		});

		selector.on("select", () => {
			container.classList.remove("no-image");
			const _img   = selector.state().get("selection").first().toJSON();
			const _thumb = _img.sizes?.sb_csp_ig_thumb?.url ||_img.sizes?.thumbnail?.url || _img.url;
			thumbnail.src    = _thumb;
			pictureUrl.value = _thumb;
			pictureId.value  = _img.id;
			button.innerText = __("Change", "still-be-combine-social-photos");
		});

		selector.open();

	}


	// Remove Image
	const removeImage = function(){

		const container = ((_this) => {
			let element = _this;
			while(element && !element.classList.contains("image-selector")){
				element = element.parentNode;
			}
			return element;
		})(this);

		if(!container){
			console.error(__("No image selector found.", "still-be-combine-social-photos"));
			return false;
		}

		//
		const thumbnail  = container.querySelector(".image-thumbnail");
		const pictureUrl = container.querySelector("input[name$='[profile_picture_url]']");
		const pictureId  = container.querySelector("input[name$='[profile_picture_id]']");
		const button     = container.querySelector(".image-selector-button");

		if(!thumbnail || !pictureUrl || !pictureId || !button){
			console.error(__("No image selector found.", "still-be-combine-social-photos"));
			return false;
		}

		container.classList.add("no-image");

		thumbnail.src    = "data:image/gif;base64,R0lGODlhAQABAGAAACH5BAEKAP8ALAAAAAABAAEAAAgEAP8FBAA7";
		pictureUrl.value = "";
		pictureId.value  = 0;
		button.innerText = __("Select", "still-be-combine-social-photos");

	}


	// Set WP Media
	const setImageSelector = () => {

		if(!wp?.media){
			console.warn(__("'wp.media' is not loaded.", "still-be-combine-social-photos"));
			return false;
		}

		const selectButtons = document.getElementsByClassName("image-selector-button");
		Array.prototype.forEach.call(selectButtons, $b => $b.onclick = openWPMediaSelector);

		const removeButtons = document.getElementsByClassName("image-remove-button");
		Array.prototype.forEach.call(removeButtons, $b => $b.onclick = removeImage);

	};


	// Set an Authentication in Browsers that do not Allow Child Window
	const setAuthThroughRedirect = () => {

		if(!window.__authToken?.data?.token || !window.__authToken?.api_type){
			return null;
		}

		window.__authToken.action =  "manual-setup";
		window.postMessage(window.__authToken, location.origin);

		delete window.__authToken;

	};


	const init = () => {
		// Set Actions
		setTabControl();
		setActionButton();
		setOpenAuthWindowButton();
		setOpenPopUpManually();
		setResetButton();
		setUnlockButton();
		setImageSelector();
		setAuthThroughRedirect();
		// Set Global
		window.openAuthWindow = openAuthWindow;
	};


	if(/complete|loaded/.test(document.readyState)){
		// readyState = interactive is just before 'DOMContentLoaded' event
		init();
	} else{
		window.addEventListener("DOMContentLoaded", init, false);
	}


})();