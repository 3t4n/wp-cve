(function(){

	"use strict";



	// Global Var
	window.$stillbe = (window.$stillbe || {});
	window.$stillbe.admin = (window.$stillbe.admin || {});
	window.$stillbe.admin.testImage = (window.$stillbe.admin.testImage || {});

	// Dummy Image
	const dummy = "data:image/gif;base64,R0lGODlhAQABAGAAACH5BAEKAP8ALAAAAAABAAEAAAgEAP8FBAA7";

	// Test Settings
	const testSettings = { left: {}, right: {}, size: null };



	// Human Readable Filesize
	const sizeHumanReadable = $byte => {
		const suffixes = ["ki", "Mi", "Gi", "Ti"];
		let size       = $byte * 1;
		let suffix     = "";
		while(size > 999){
			size /= 1024;
			suffix = suffixes.shift();
		}
		return String(size).substring(0, 4).replace(/\.$/, "") + suffix;
	};



	// Get Test Image via Ajax
	const getTestImage = ($targetSide, $param) => {

		// Filters
		const filters = Object.assign({}, $param.filters);
		delete $param.filters;

		// admin-ajax.php
		const url = window.$stillbe.admin.testImage.ajaxUrl;

		// Query String
		const query = new URLSearchParams($param);

		// Filter Query String
		const filterQueryString = Object.keys(filters).map($f => [$f, filters[$f]])
		                          .reduce((_, f) => `${_}&${encodeURIComponent(`filters[${f[0]}]`)}=${encodeURIComponent(f[1])}`, "");

		// Parse Response
		const parseResponse = async $response => {
			const result = {};
			if($response.ok){
				const contentType = $response.headers.get("Content-Type");
				if(contentType === "application/json"){
					const json = await $response.json();
					return Promise.resolve({
						type: "json",
						data: json,
					});
				} else{
					// Information of Converting
					const quality  = $response.headers.get("X-Quality-Level");
					const mode     = $response.headers.get("X-Encode-Mode") || "-";
					const compress = $response.headers.get("X-Compression-Level") || "-";
					const time     = $response.headers.get("X-Convert-Time");
					const memory   = $response.headers.get("X-Memory-Peak");
					const cpu      = $response.headers.get("X-Average-CPU");
					// Resolves with a Blob
					const blob = await $response.blob();
					// Return
					return Promise.resolve({
						type     : "blob",
						data     : blob,
						size     : blob.size,
						mime     : blob.type,
						quality  : quality,
						mode     : mode,
						compress : compress != 0 ? compress : "-",
						time     : time,
						memory   : memory,
						cpu      : cpu,
					});
				}
			}
			return Promise.reject("An error has occurred....");
		};

		// Handle Result
		const handleResult = $result => {
		//	console.log($result);
			if($result.type !== "blob"){
				const consoleMessage = $result.data.ok ? console.log : console.warn;
				consoleMessage($result.data.message, $result.data);
				return $result.data.ok;
			}
			// Render the Test Image
			const imgElem = document.querySelector(`.twentytwenty-image.${$targetSide}`);
			imgElem.onload = () => {
				document.querySelector(`.side-label.${$targetSide}`).classList.remove("loading");
				if(window.$stillbe.admin.testImage.clearTwentytwenty){
					// Set TwentyTwenty
					new Promise($resolve => {
						if(!imgElem.previousElementSibling){
							$resolve()
							return true;;
						}
						const timerID = setInterval(() => {
							const prevElem = imgElem.previousElementSibling;
							if(!prevElem || prevElem.naturalWidth > 1){
								$resolve();
							}
						}, 50);
					})
					// Set the TwentyTwenty Wrapper to the Same Size as the Image
					.then(() => {
						jQuery("#sb_compare_image .twentytwenty-container").twentytwenty();
						const size    = window.$stillbe.admin.testImage.currentSizes[testSettings.size];
						const wrapper = document.querySelector("#sb_compare_image .twentytwenty-wrapper");
						const dpr     = window.devicePixelRatio || 1;
						wrapper.style.width  = `${~~(size.width / dpr)}px`;
						wrapper.style.heught = `${~~(size.heught / dpr)}px`;
						window.$stillbe.admin.testImage.clearTwentytwenty = false;
					});
				}
			};
			imgElem.src = URL.createObjectURL($result.data);
			// Fill the Converting Informations
			document.querySelector(`.sb-ti-quality.${$targetSide}`).innerText = $result.quality;
			document.querySelector(`.sb-ti-mime.${$targetSide}`   ).innerText = $result.mime;
			document.querySelector(`.sb-ti-size.${$targetSide}`   ).innerHTML = `${sizeHumanReadable($result.size)}B<br>(${$result.size}B)`;
			document.querySelector(`.sb-ti-time.${$targetSide}`   ).innerText = $result.time;
			document.querySelector(`.sb-ti-memory.${$targetSide}` ).innerText = $result.memory;
			// Loss-Less Compression Informations
			const _mode = document.querySelector(`.sb-ti-mode.${$targetSide}`);
			const _comp = document.querySelector(`.sb-ti-comp-level.${$targetSide}`)
			if(_mode){
				_mode.innerText = $result.mode;
			}
			if(_comp){
				_comp.innerText = $result.compress;
			}
		};

		// Fetch
		fetch(`${url}?${query.toString()}${filterQueryString}`)

		// Parse
		.then(parseResponse)

		// Render
		.then(handleResult)

		// Error
		.catch(console.error);

	};



	// Put a Container Elements for TwentyTwenty
	const putContainerForTwentytwenty = () => {
		const compImages = document.getElementById("sb_compare_image");
		while(compImages.firstChild){
			compImages.firstChild.remove();
		}
		const container = compImages.appendChild(document.createElement("div"));
		container.className = "twentytwenty-container";
		const leftImage = container.appendChild(new Image());
		leftImage.src = dummy;
		leftImage.className = "twentytwenty-image left";
		const rightImage = container.appendChild(new Image());
		rightImage.src = dummy;
		rightImage.className = "twentytwenty-image right";
		if(!window.$stillbe.admin.testImage.currentSizes){
			return;
		}
		const size   = window.$stillbe.admin.testImage.currentSizes[testSettings.size];
		const images = document.querySelectorAll("#sb_compare_image .twentytwenty-image");
		const dpr    = window.devicePixelRatio || 1;
		images.forEach($i => {
			$i.style.width  = `${~~(size.width / dpr)}px`;
			$i.style.heught = `${~~(size.heught / dpr)}px`;
		});
		// Initialized Flag
		window.$stillbe.admin.testImage.clearTwentytwenty = true;
	};



	// Callback Function of Test Setting Change
	const settingChange = function(){

		if(!window.$stillbe.admin.testImage.attachmentId){
			return false;
		}

		const changed = [];

		// Settings
		const size         = document.getElementById("sb_image_sizes"     ).value;
		const leftQuality  = document.getElementById("sb_ti_left_quality" ).value;
		const leftMime     = document.getElementById("sb_ti_left_mime"    ).value;
		const rightQuality = document.getElementById("sb_ti_right_quality").value;
		const rightMime    = document.getElementById("sb_ti_right_mime"   ).value;

		// Changed Settings
		if(testSettings.size !== size){
			testSettings.size          = size;
			testSettings.left.quality  = leftQuality;
			testSettings.left.mime     = leftMime;
			testSettings.left.filters  = {};
			testSettings.right.quality = rightQuality;
			testSettings.right.mime    = rightMime;
			testSettings.right.filters = {};
			changed.push("left", "right");
			// Put a Container Elements for TwentyTwenty
			putContainerForTwentytwenty();
		} else{
			if(testSettings.left.quality !== leftQuality){
				testSettings.left.quality = leftQuality;
				if(changed.indexOf("left") < 0){
					changed.push("left");
				}
			}
			if(testSettings.left.mime !== leftMime){
				testSettings.left.mime = leftMime;
				if(changed.indexOf("left") < 0){
					changed.push("left");
				}
			}
			if(testSettings.right.quality !== rightQuality){
				testSettings.right.quality = rightQuality;
				if(changed.indexOf("right") < 0){
					changed.push("right");
				}
			}
			if(testSettings.right.mime !== rightMime){
				testSettings.right.mime = rightMime;
				if(changed.indexOf("right") < 0){
					changed.push("right");
				}
			}
			// Toggle Options
			const classes = this.classList;
			if(classes.contains("toggle-option-radio")){
				const side = (this.getAttribute("name") || ".").split(".")[0];
				if(side === "left" || side === "right"){
					const filters = {};
					const radio   = document.querySelectorAll(`.toggle-option-radio.${side}`);
					radio.forEach($r => {
						if($r.checked && $r.value !== "-"){
							const filter = ($r.getAttribute("name") || ".").split(".")[1] || "";
							if(!filter){
								return null;
							}
							filters[filter] = $r.value;
						}
					});
					let isChanged = false;
					for(const filter in filters){
						if(testSettings[side].filters[filter] !== filters[filter]){
							isChanged = true;
							break;
						}
					}
					if(Object.keys(testSettings[side].filters).length !== Object.keys(filters).length){
						isChanged = true;
					}
					testSettings[side].filters = Object.assign({}, filters);
					if(isChanged){
						changed.push(side);
					}
				}
			}
		}

		// Get the Test Image(s)
		changed.forEach($targetSide => {
			// Query Parameters
			const param = {
				_nonce        : window.$stillbe.admin.testImage.nonces.generate_test_image,
				action        : "sb_iqc_generate_test_image",
				attachment_id : window.$stillbe.admin.testImage.attachmentId,
				size          : testSettings.size,
				quality       : testSettings[$targetSide].quality,
				mime          : testSettings[$targetSide].mime,
				filters       : testSettings[$targetSide].filters,
			};
			// Loading
			document.querySelector(`.side-label.${$targetSide}`).classList.add("loading");
			// Get Image
			getTestImage($targetSide, param);
		});

	};



	const getAttachmentSizes = async $id => {

		// admin-ajax.php
		const url = window.$stillbe.admin.testImage.ajaxUrl;

		// Query Parameters
		const param = {
			_nonce        : window.$stillbe.admin.testImage.nonces.get_attachment_meta,
			action        : "sb_iqc_get_attachment_meta",
			attachment_id : $id,
		};

		// Query String
		const query = new URLSearchParams(param);

		// Fetch Request
		const response = await fetch(`${url}?${query.toString()}`);

		if(!response.ok){
			return Promise.reject("An error has occurred....");
		}

		// Parse JSON
		const json = await response.json();

		if(!json.ok){
			return Promise.reject(json.message || "An error has occurred....");
		}

		// Original Dimension
		const width  = json.meta.width;
		const height = json.meta.height;

		// Size Array
		const sizes = {};
		const maxSizes = window.$stillbe.admin.testImage.sizes;
		for(const name in maxSizes){
			const maxSize = maxSizes[name];
			let _w = width;
			let _h = height;
			if(maxSize.crop){
				_w = Math.min(_w, maxSize.width);
				_h = Math.min(_h, maxSize.height);
			} else{
				if(maxSize.width > _w && maxSize.height > _h){
					continue;
				}
				const aspect = width / height;
				_w = maxSize.width;
				_h = Math.round(_w / aspect);
				if(maxSize.height > 0 && _h > maxSize.height){
					_h = maxSize.height;
					_w = Math.round(_h * aspect);
				}
			}
			sizes[name] = {
				width  : _w,
				height : _h,
				crop   : maxSize.crop,
			};
		}

		// Add Full Size to Sizes Array
		sizes.Original = {
			width  : width,
			height : height,
		};

		// Save the Sizes to Global Var
		window.$stillbe.admin.testImage.currentSizes = sizes;

		// Return the Sizes
		return Promise.resolve(sizes);

	};



	// Set Test Settings
	window.addEventListener("DOMContentLoaded", () => {

		const selector = ".sb-test-image-settings select, .sb-test-image-settings input[type=number]";

		document.querySelectorAll(selector).forEach($i => {
			$i.onchange   = settingChange;
			$i.onkeypress = $event => $event.key !== "Enter";
		});

		document.querySelectorAll(".toggle-option-radio").forEach($r => {
			$r.onclick = settingChange;
		});

	}, false);



	// Set a Media Selector
	window.addEventListener("DOMContentLoaded", () => {

		const selectButton = document.getElementById("sb_select_img");
		const deleteButton = document.getElementById("sb_delete_img");
		const thumbImage   = document.getElementById("sb_thumb");
		const filename     = document.getElementById("sb_iqc_filename");
		const compImages   = document.getElementById("sb_compare_image");
		const listSizes    = document.getElementById("sb_image_sizes");
		const convertInfos = document.getElementsByClassName("sb-ti-info");

		const imgSelector  = wp.media({
			title    : selectButton.dataset.title,
			library  : { type : "image" },
			button   : { text : selectButton.dataset.submit },
			multiple : false, // "add",
		});

		selectButton.onclick = () => {
			imgSelector.open();
		};

		imgSelector.on("select", () => {
			const img   = imgSelector.state().get("selection").first().toJSON();
			const sizes = img.sizes;
			const thumb = sizes && sizes.medium ? sizes.medium.url : img.url;
			// Set Attachment ID
			window.$stillbe.admin.testImage.attachmentId = img.id;
			// Set the Sizes Option
			while(listSizes.firstChild && listSizes.dataset.init){
				listSizes.firstChild.remove();
			}
			// Set the Filename
			filename.innerText = img.filename;
			// Get Attachment Sizes
			getAttachmentSizes(img.id)   // via Ajax
				// Add Options
				.then($sizes => {
					const sizeArray = [];
					for(const key in $sizes){
						sizeArray.push({
							name: key,
							data: $sizes[key],
						});
					}
					listSizes.dataset.init = listSizes.dataset.init || listSizes.firstChild.innerText;
					while(listSizes.firstChild){
						listSizes.firstChild.remove();
					}
					sizeArray.sort(($a, $b) => {
						return $a.data.width * $a.data.height - $b.data.width * $b.data.height;
					});
					sizeArray.forEach($s => {
						const optElem = listSizes.appendChild(document.createElement("option"));
						optElem.value     = $s.name;
						optElem.innerText = `${$s.name} (${$s.data.width}x${$s.data.height})`;
					});
					if("medium" in $sizes){
						listSizes.value = "medium";
					}
					// Reset Settings
					testSettings.left  = {};
					testSettings.right = {};
					testSettings.size  = null;
				//	console.log(testSettings);
					// Run
					listSizes.onchange = settingChange;
					settingChange();
				})
				// Failed to Get Sizes
				.catch(console.error);
			// Set the Thumbnail
			thumbImage.dataset.none = thumbImage.dataset.none || thumbImage.src;
			thumbImage.src = thumb;
			// Clear the Comap Images
			while(compImages.firstChild){
				compImages.firstChild.remove();
			}
			// Clear Information
			Array.prototype.forEach.call(convertInfos, $s => {
				$s.innerText = "-";
			});
		});

		deleteButton.onclick = () => {
			thumbImage.src = thumbImage.dataset.none || dummy;
			filename.innerText = "";
			// Clear the Comap Images
			while(compImages.firstChild){
				compImages.firstChild.remove();
			}
			// Clear Information
			Array.prototype.forEach.call(convertInfos, $s => {
				$s.innerText = "-";
			});
			// Clear Size
			if(listSizes.dataset.init){
				while(listSizes.firstChild){
					listSizes.firstChild.remove();
				}
				listSizes.appendChild(document.createElement("option")).innerText = listSizes.dataset.init;
			}
		};

	}, false);


	// Handle Toggle Options Height
	const handleToggleOptionsHeight = () => {

		const options = document.getElementsByClassName("toggle-options");
		const height  = Array.prototype.map.call(options, $o => $o.scrollHeight)
		                .reduce((_, h) => Math.max(_, h));

		if(height < 10){
			return 0;
		}

		const style = document.head.appendChild(document.createElement("style"));
		style.textContent = `.toggle-options-display:checked + .show-toggle-options + .toggle-options{max-height: ${~~height}px;}`;

		Array.prototype.forEach.call(options, $o => {
			$o.previousElementSibling.onclick = function(){
				Array.prototype.forEach.call(options, $_ => {
					$_.previousElementSibling.previousElementSibling.click();
				});
				return false;
			};
		});

		return height;

	};


	// Set Toggle Options Height
	window.addEventListener("DOMContentLoaded", () => {

		if(handleToggleOptionsHeight() > 0){
			return true;
		}

		// 
		const tab = document.querySelector(".settings-tabs-wrapper label[for='tab_sb-imgq-ss-test-quality']");
		if(!tab){
			return null;
		}

		const setTestImageTabClickEvent = () => {
			
			if(handleToggleOptionsHeight() > 0){
				tab.removeEventListener("click", setTestImageTabClickEvent, false);
				return true;
			}

			setTimeout(handleToggleOptionsHeight, 200);
			tab.removeEventListener("click", setTestImageTabClickEvent, false);

		};

		tab.addEventListener("click", setTestImageTabClickEvent, false);

	}, false);



})();



	/*
		// Parse Response
		const parseResponse = $response => {
			return new Promise(async (_resolve, _reject) => {
				const result = {};
				if($response.ok){
					const contentType = $response.headers.get("Content-Type");
					if(contentType === "application/json"){
						const json = await $response.json();
						return _resolve({
							type: "json",
							data: json,
						});
					} else{
						const blob = await $response.blob();
						return _resolve({
							type: "blob",
							data: blob,
						});
					}
				}
				return _reject("An error has occurred....");
			});
		};
	*/



	/*
		new Promise($resolve => {
			jQuery(container).twentytwenty();
			const timerID = setInterval(() => {
				const wrapper = document.querySelector("#sb_compare_image .twentytwenty-wrapper");
					console.log(wrapper);
				if(wrapper){
					clearInterval(timerID);
					$resolve(wrapper);
				}
			}, 50);
		})
		//Set the TwentyTwenty Wrapper to the Same Size as the Image
		.then($wrapper => {
			const size = window.$stillbe.admin.testImage.currentSizes[testSettings.size];
			$wrapper.style.width  = `${size.width}px`;
			$wrapper.style.height = `${size.height}px`;
		});
	*/



