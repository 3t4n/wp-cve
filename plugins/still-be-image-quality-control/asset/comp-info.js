window.addEventListener("DOMContentLoaded", function(){

	"use strict";


	const url    = window.$stillbe.admin.ajaxUrl;
	const nonces = window.$stillbe.admin.nonce;
	const action = window.$stillbe.admin.action;


	const __ = $id => {
		if(!("translate" in window.$stillbe.admin)){
			return $id;
		}
		return window.$stillbe.admin.translate[$id] || $id;
	};


	const parseResponse = async $res => {
		if(!$res.ok){
			return Promise.reject("An error has occurred....");
		}
		const json = await $res.json();
		if(json.ok){
			return Promise.resolve(json);
		} else{
			return Promise.reject(json.message);
		}
	}


	const showInfoTable = $json => {

		const meta = $json.meta;

		if(!meta.sizes || Object.keys(meta.sizes).length < 1){
			return false;
		}

		const baseUrl   = window.$stillbe.uploadBaseUrl;   // without end '/'
		const subdirUrl = `${baseUrl}/${meta.file}`.replace(/\/[^\/]*$/, "");

		const back = document.body.appendChild(document.createElement("div"));
		back.classList.add("modal-back");
		back.onclick = function(){
			this.remove();
		};

		const fragment = new DocumentFragment();
		const wrapper  = fragment.appendChild(document.createElement("div"));
		const table    = wrapper.appendChild(document.createElement("table"));
		wrapper.classList.add("scroll-wrapper");
		table.classList.add("info-table");

		const tr = table.appendChild(document.createElement("tr"));
		const sizename = tr.appendChild(document.createElement("th"));
		const size     = tr.appendChild(document.createElement("th"));
		const mime     = tr.appendChild(document.createElement("th"));
		const filename = tr.appendChild(document.createElement("th"));
		const quality  = tr.appendChild(document.createElement("th"));
		const webpname = tr.appendChild(document.createElement("th"));
		const webp_q   = tr.appendChild(document.createElement("th"));
		const webp_m   = tr.appendChild(document.createElement("th"));
		const webp_z   = tr.appendChild(document.createElement("th"));

		sizename.innerText = __("Size Name");
		size    .innerText = __("Size");
		mime    .innerText = __("Mime-Type");
		filename.innerText = __("File Path");
		quality .innerText = __("Quality Level");
		webpname.innerText = __("WebP File Path");
		webp_q  .innerText = __("WebP Quality Level");
		webp_m  .innerText = __("WebP Compression Mode");
		webp_z  .innerText = __("WebP Lossless Level");

		const sizeNames = Object.keys(meta.sizes).sort((_a, _b) => meta.sizes[_a].width - meta.sizes[_b].width);

		for(const sizeName of sizeNames){

			const sizeInfo = meta.sizes[sizeName];
			const compInfo = sizeInfo["sb-iqc"] || {};
			const mimeType = sizeInfo['mime-type']

			const tr = table.appendChild(document.createElement("tr"));
			const sizename = tr.appendChild(document.createElement("td"));
			const size     = tr.appendChild(document.createElement("td"));
			const mime     = tr.appendChild(document.createElement("td"));
			const filename = tr.appendChild(document.createElement("td"));
			const quality  = tr.appendChild(document.createElement("td"));
			const webpname = tr.appendChild(document.createElement("td"));
			const webp_q   = tr.appendChild(document.createElement("td"));
			const webp_m   = tr.appendChild(document.createElement("td"));
			const webp_z   = tr.appendChild(document.createElement("td"));

			sizename.innerText = sizeName;
			size    .innerText = `${sizeInfo.width}x${sizeInfo.height}`;
			mime    .innerText = mimeType;
			filename.innerHTML = `<input type="url" size="25" value="${subdirUrl}/${sizeInfo.file}" disabled>`;
			quality .innerText = mimeType === "image/webp" && "cwebp" in compInfo ? compInfo.cwebp.quality : (compInfo.quality || "-");
			webpname.innerHTML = "webp-file" in compInfo ? `<input type="url" size="25" value="${subdirUrl}/${compInfo["webp-file"]}" disabled>` : "-";
			webp_q  .innerText = compInfo["webp-quality"] || "-";
			webp_m  .innerText = "cwebp" in compInfo && compInfo.cwebp.method ? compInfo.cwebp.method : (compInfo["webp-quality"] ? "loassy" : "-");
			webp_z  .innerText = "cwebp" in compInfo && compInfo.cwebp.q ? compInfo.cwebp.q : "-";

		}

		table.onclick = function($e){
			$e.stopPropagation();
		};

		back.append(fragment);

	};


	const showCompInfo = function(){

		const id = this.dataset.id || 0;

		const query = new URLSearchParams({
			_nonce        : nonces.show,
			action        : action,
			attachment_id : id,
		});

		// Send a Request
		fetch(`${url}?${query.toString()}`)

		// Parse a Response
		.then(parseResponse)

		// Show an Infomation Table
		.then(showInfoTable)

		// Alert Some Errors
		.catch(alert);

	};


	const setShowCompInfo = () => {

		const buttons = document.getElementsByClassName("show-comp-info");

		Array.from(buttons).forEach($b => {
			$b.onclick = showCompInfo;
		});

	};


	setShowCompInfo();




	const runingRecomp = {};

	const runRecomp = function(){

		const id = this.dataset.id || 0;

		if(!id){
			return null;
		}

		if(runingRecomp[id]){
			return null;
		}

		const _this      = this;
		_this.disabled   = true;
		runingRecomp[id] = true;

		const result  = this.nextElementSibling || this.parentNode.appendChild(document.createElement("p"));
		result.style.transition = "";
		result.style.opacity    = "";
		result.classList.add("result-message");
		result.innerText = __("Now processing...");

		const dat = new FormData();
		dat.append("_nonce",        nonces.comp);
		dat.append("action",        "sb_iqc_regenerate_images");
		dat.append("attachment_id", id);

		const fin = $completed => {
			_this.disabled   = false;
			runingRecomp[id] = false;
			if($completed){
				result.style.transition = "1.2s";
				setTimeout(() => {
					if(runingRecomp[id]){
						return null;
					}
					result.style.opacity = "0";
				}, 6400);
			}
		};

		// Send a Request
		fetch(url, {
			method : "POST",
			body   : dat,
		})

		// Parse a Response
		.then(parseResponse)

		// Show an Infomation Table
		.then($json => {
			console.log($json);
			result.innerText = $json.message;
			fin(true);
		})

		// Result Some Errors
		.catch($message => {
			result.innerText = $message;
			fin(false);
		});

	};


	const setRunRecomp = () => {

		const buttons = document.getElementsByClassName("run-recomp");

		Array.from(buttons).forEach($b => {
			$b.onclick = runRecomp;
		});

	};


	setRunRecomp();


}, false);