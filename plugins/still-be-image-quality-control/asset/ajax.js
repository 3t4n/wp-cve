"use strict";

window.$stillbe       = (window.$stillbe       || {});
window.$stillbe.func  = (window.$stillbe.func  || {});
window.$stillbe.admin = (window.$stillbe.admin || {});

window.$stillbe.func.__ = function($id, $domain){
	if(!("translate" in window.$stillbe.admin)){
		return $id;
	}
	return window.$stillbe.admin.translate[$id] || $id;
};

// Ajax Request Wrapper Function
window.$stillbe.func.ajaxRequest = function($method = "POST", $url = null, $data = {}, $callback = null){
	if(!$url || typeof $callback !== "function"){
		return false;
	}
	const __  = window.wp && window.wp.i18n && window.wp.i18n.__ ? window.wp.i18n.__ : window.$stillbe.func.__;
	const xhr = new XMLHttpRequest();
	const err = function(){
		alert(__("An error has occurred.... Please try again.", "still-be-image-quality-control"));
		$callback.call(null, { ok: false, json: { ok: false, message: __("Network Error...", "still-be-image-quality-control") }, body: xhr.responseText });
	};
	xhr.timeout   = 30 * 1000;
	xhr.onerror   = err;
	xhr.ontimeout = err;
	xhr.onload    = function(){
		let result = false;
		try{
			result = JSON.parse(xhr.responseText);
		} catch{}
		console.log(result);
		$callback.call(null, { ok: true, json: result, body: xhr.responseText });
	};
	// Append Data & Request
	if($method.toUpperCase() !== "GET"){
		const dat = new FormData();
		$data = $data || {};
		for(let key in $data){
			dat.append(key, $data[key]);
		}
		xhr.open(String($method).toUpperCase(), $url, true);
		xhr.send(dat);
	} else{
		const dat = [];
		$data = $data || {};
		for(let key in $data){
			dat.push(encodeURIComponent(key) + "=" + encodeURIComponent($data[key]));
		}
		xhr.open("GET", $url + '?' +  dat.join("&").replace(/%20/g, "+"), true);
		xhr.send();
	}
};


// Get Images IDs
window.addEventListener("DOMContentLoaded", function(){
	const button = document.getElementById("get_attachment_id");
	const url    = window.$stillbe.admin.reComp.ajaxUrl;
	const nonce  = window.$stillbe.admin.reComp.nonce;
	const cache  = window.$stillbe.admin.reComp.cache;
	const __     = window.wp && window.wp.i18n && window.wp.i18n.__ ? window.wp.i18n.__ : window.$stillbe.func.__;
	if(!button || /* !list || */ !url || !nonce){
		return null;
	}
	let runing = false;
	const data = {
		action: "sb_iqc_get_attachment_ids",
		_nonce: nonce.getIds,
	};
	const showTargetCount = function($response){
		if(!$response || !$response.ok){
			if($response){
				alert($response.message || __("An error has occurred.... Please try again.", "still-be-image-quality-control"));
			}
			runing = false;
			return false;
		}
		const showCountElem = ($exists => {
			if($exists){
				return $exists;
			}
			const regenButton = document.getElementById("regenerate_images");
			if(!regenButton){
				return regenButton;
			}
			const elem = regenButton.parentNode.insertBefore(document.createElement("p"), regenButton);
			elem.id = "count_ids";
			elem.dataset.label = __("Target Images", "still-be-image-quality-control");
			return elem;
		})(document.getElementById("count_ids"));
		if(!showCountElem){
			alert($response.message || __("An error has occurred.... Please try again.", "still-be-image-quality-control"));
			runing = false;
			return false;
		}
		const result  = $response.json || {};
		window.$stillbe.admin.reComp.image = {ids: (result.ids || [])};
		showCountElem.innerText = window.$stillbe.admin.reComp.image.ids.length + " " + __("Files", "still-be-image-quality-control");
		button.disabled = false;
		runing = false;
	};
	button.onclick = function(){
		if(runing){
			return null;
		}
		button.disabled = true;
		runing = true;
		// Add the Target Conditions
		const target = {};
		document.querySelectorAll("[data-target-condition]").forEach($e => {
			const cond = $e.getAttribute("data-target-condition");
			const key  = $e.getAttribute("data-key");;
			if(!cond || !key){
				return null;
			}
			target[cond]      = target[cond] || {};
			target[cond][key] = $e.type !== "checkbox" ? $e.value : $e.checked;
		});
		data.target = JSON.stringify(target);console.log(data);
		window.$stillbe.func.ajaxRequest("GET", url, data, showTargetCount);
	};
	// If it has the cache
	if(cache.ids){
		showTargetCount({
			ok: true,
			message: "Cache",
			json: { ids: cache.ids },
			finished: cache.current || 0,
		});
	}
}, false);


// Regenerate Images
window.addEventListener("DOMContentLoaded", function(){
	const button  = document.getElementById("regenerate_images");
	const suspend = document.getElementById("suspend_regenerate");
	const url     = window.$stillbe.admin.reComp.ajaxUrl;
	const nonce   = window.$stillbe.admin.reComp.nonce;
	const cache   = window.$stillbe.admin.reComp.cache;
	const __      = window.wp && window.wp.i18n && window.wp.i18n.__ ? window.wp.i18n.__ : window.$stillbe.func.__;
	if(!button || !url || !nonce){
		return null;
	}
	const progressBar = document.getElementsByClassName("progress-bar");
	const progress    = document.getElementsByClassName("progress");
	let runing = false;
	const data = {
		action: "sb_iqc_regenerate_images",
		_nonce: nonce.reGenImg,
	};
	let suspendFlag = false;
	const doReGen = function(){
		window.$stillbe.func.ajaxRequest("POST", url, data, function($response){
			if(!$response || !$response.ok){
				if($response){
					alert(__("An error has occurred.... Please try again.", "still-be-image-quality-control"));
				}
				button.disabled  = false;
				suspend.disabled = true;
				runing = false;
				return false;
			}
			const result = $response.json || {};
			if(result.nonce){
				data._nonce = result.nonce;
			}
			const prog = ~~(result.progress_ratio * 100) + "%";
			progress[0].style.width      = prog;
			progress[0].dataset.progress = prog;
			if(result.next_id){
				if(suspendFlag){
					alert(__("It was interrupted! Even if you close the page, you can restart the conversion from the continuation.", "still-be-image-quality-control"));
					suspendFlag      = false;
					suspend.disabled = true;
					button.disabled  = false;
					runing = false;
				} else{
					setTimeout(doReGen, 1000);
				}
			} else{
				alert(__("All the regeneration is done!", "still-be-image-quality-control"));
				suspendFlag      = false;
				suspend.disabled = true;
				button.disabled  = false;
				runing = false;
			}
		});
	};
	button.onclick = function(){
		if(runing){
			return null;
		}
		button.disabled = true;
		runing = true;
		// Enable the Suspend Button
		suspend.disabled = false;
		// Progress Bar
		if(progressBar.length < 1){
			button.parentNode.insertBefore(document.createElement("div"), button)
				.classList.add("progress-bar");
			progressBar[0].appendChild(document.createElement("div"))
				.classList.add("progress");
		}
		const prog = "0%";
		progress[0].style.width = prog;
		progress[0].dataset.progress = prog;
		// Start!!
		doReGen();
	};
	if(!button){
		return null;
	}
	// Suspend Re-Comp
	suspend.onclick = function(){
		if(this.disabled){
			return false;
		}
		this.disabled = true;
		suspendFlag = true;
	};
}, false);


// Regenerate ONE Image
window.addEventListener("DOMContentLoaded", function(){
	const button  = document.getElementById("conv_only_one_image_button");
	const result  = document.getElementById("conv_only_one_image_result");
	const id      = document.getElementById("one_attachment_id");
	const url     = window.$stillbe.admin.reComp.ajaxUrl;
	const nonce   = window.$stillbe.admin.reComp.nonce;
	const __      = window.wp && window.wp.i18n && window.wp.i18n.__ ? window.wp.i18n.__ : window.$stillbe.func.__;
	if(!button || !id || !url || !nonce){
		return null;
	}
	let runing = false;
	button.onclick = function(){
		if(runing){
			return null;
		}
		if(!id.value){
			return null;
		}
		button.disabled = true;
		runing = true;
		// Start!!
		result.innerText = "Now processing...";
		const data = {
			action        : "sb_iqc_regenerate_images",
			attachment_id : id.value,
			_nonce        : nonce.reGenImg,
		};
		window.$stillbe.func.ajaxRequest("POST", url, data, function($response){
			if(!$response || !$response.ok){
				if($response.message){
					alert(__("An error has occurred.... Please try again.", "still-be-image-quality-control"));
				}
				result.innerText = __("An error has occurred.... Please try again.", "still-be-image-quality-control");
			} else{
				result.innerText = $response.json.message || $response.message;
			}
			button.disabled = false;
			runing = false;
			return false;
		});
	};
}, false);


// Reset Settings to Defaut
window.addEventListener("DOMContentLoaded", function(){
	const button = document.getElementById("reset_settings");
	if(!button){
		return null;
	}
	const url = window.$stillbe.admin.ajaxUrl;
	const __  = window.wp && window.wp.i18n && window.wp.i18n.__ ? window.wp.i18n.__ : window.$stillbe.func.__;
	let refreshing = false;
	button.onclick = function(){
		if(refreshing || !window.confirm(__( "All settings will revert to their default values. This change is irreversible.", "still-be-image-quality-control"))){
			return null;
		}
		refreshing = true;
		// 
		const data = {
			action : "sb_iqc_reset_settings",
			_nonce : window.$stillbe.admin.reset.nonce,
		};
		window.$stillbe.func.ajaxRequest("POST", url, data, function($response){
			if(!$response || !$response.ok){
				alert($response.message || __("An error has occurred.... Please try again.", "still-be-image-quality-control"));
			} else{
				console.log($response.json);
				alert($response.json.message);
				if($response.json.ok){
					location.reload();
				}
			}
			refreshing = false;
		});
	};
}, false);




