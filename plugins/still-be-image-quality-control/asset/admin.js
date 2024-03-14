(function(){

	"use strict";


	const __ = window.wp && window.wp.i18n && window.wp.i18n.__ ? window.wp.i18n.__ :
		(
			window.$stillbe && window.$stillbe.func && window.$stillbe.func.__ ? window.$stillbe.func.__ :
			function($id, $domain){
				if(!("translate" in window.$stillbe.admin)){
					return $id;
				}
				return window.$stillbe.admin.translate[$id] || $id;
			}
		);


	/**
	 * Clone Template & Return Fragment
	 *  @param  Element          $temp
	 *  @param  object           $params
	 *  @return DocumentFragment
	 */
	const cloneTemplate = ($temp, $params) => {

		if(!$temp){
			return new DocumentFragment();
		}

		const generateHTML = $temp.innerHTML.replace(/{{([^}]+)}}/g, ($a, $m) => {
			const objctKey = $m.split(".");
			let param = JSON.parse(JSON.stringify($params));
			while(objctKey.length && param){
				param = param[objctKey.shift()] || "";
			}
			return param;
		});

		const dummyTemp = document.createElement("template");
		dummyTemp.innerHTML = generateHTML;

		return dummyTemp.content;

	};


	// Set a New Image Size Setting Fields
	const setAddImageSize = () => {

		const tbody    = document.getElementById("quality_level_table_body");
		const button   = document.getElementById("add_image_size_button");
		const template = document.getElementById("temp_quality_level_fields");

		if(!tbody || !button || !template){
			return null;
		}

		const addingImageSizes = document.getElementsByClassName("added-image-size");

		button.onclick = function(){
			const i = addingImageSizes.length;
			tbody.appendChild(cloneTemplate(template, {i: String(i)}));
			checkUniqueSizeName();
		};

		checkUniqueSizeName();

	};


	// Set a New Threshold for WebP of Original Image
	const setAddThreashold = () => {

		const tbody    = document.getElementById("original_quality_table_body");
		const button   = document.getElementById("add_original_quality_button");
		const template = document.getElementById("temp_original_quality_fields");

		if(!tbody || !button || !template){
			return null;
		}

		button.onclick = function(){
			const i = tbody.children.length;
			tbody.insertBefore(cloneTemplate(template, {i: String(i)}), tbody.lastChild);
		};

	};


	// Check Whether the Image Size Name is Unique
	const checkUniqueSizeName = () => {

		const sizes      = window.$stillbe.admin.testImage.sizes;
		const sizeNames  = document.getElementsByClassName("add-image-size-name");
		const embedNames = document.getElementsByClassName("embed-image-size-name");

		for(const name of sizeNames){
			name.onchange = function(){
				for(const _name of [...sizeNames, ...embedNames]){
					if(this.value.match(/\W/)){
						this.select();
						alert(__("You can use only alphanumeric and underscore.", "still-be-image-quality-control"));
						return false;
					}
					if(this === _name){
						continue;
					}
					if(this.value === (_name.value || _name.innerText)){
						this.select();
						alert(__("You cannot use duplicate name.", "still-be-image-quality-control"));
						return false;
					}
				}
			};
		}

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


	// Handle Target Conditions Height
	const handleTargetConditionsHeight = () => {

		const target = document.getElementsByClassName("target-conditions-setting-container");
		const height = Array.prototype.map.call(target, $t => $t.scrollHeight)
		                .reduce((_, h) => Math.max(_, h));

		if(height < 10){
			return 0;
		}

		const style = document.head.appendChild(document.createElement("style"));
		style.textContent = `#target_conditions_display:checked ~ .target-conditions-setting-container{max-height: ${~~height}px;}`;

		Array.prototype.forEach.call(target, $t => {
			$t.previousElementSibling.onclick = function(){
				Array.prototype.forEach.call(target, $_ => {
					$_.previousElementSibling.previousElementSibling.click();
				});
				return false;
			};
		});

		return height;

	};


	// Set Target Conditions Height
	const initTargetConditionsHeight = () => {

		if(handleTargetConditionsHeight() > 0){
			return true;
		}

		// 
		const tab = document.querySelector(".settings-tabs-wrapper label[for='tab_sb-imgq-ss-recomp']");
		if(!tab){
			return null;
		}

		const setRecompTabClickEvent = () => {
			
			if(handleTargetConditionsHeight() > 0){
				tab.removeEventListener("click", setRecompTabClickEvent, false);
				return true;
			}

			setTimeout(handleTargetConditionsHeight, 200);
			tab.removeEventListener("click", setRecompTabClickEvent, false);

		};

		tab.addEventListener("click", setRecompTabClickEvent, false);

	};


	window.addEventListener("DOMContentLoaded", function(){
		setAddImageSize();
		setAddThreashold();
		setTabControl();
		initTargetConditionsHeight();
	});


})();