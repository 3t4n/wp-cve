(function(){

	"use strict";

	const BLANK_TRANSPARENT_PNG_IMAGE       = "data:image/gif;base64,R0lGODlhAQABAGAAACH5BAEKAP8ALAAAAAABAAEAAAgEAP8FBAA7";

	const STILLBE_REST_API_UPDATE_CACHE_URL = window.$stillbeCombineSocialPhotos?.rest?.cacheUpdateUrl || null;
	const STILLBE_BLOCK_ROOT_CLASSES        = [ 'sb-csp-simple-grid-root', 'sb-csp-simple-slider-root' ];

	const STILLBE_CACHE_EXPIRED_THUMBNAIL   = window.$stillbeCombineSocialPhotos?.asset?.thumbnail?.cacheExpired
	                                            || BLANK_TRANSPARENT_PNG_IMAGE;

	const STILLBE_QUEUE_INTERVAL            = window.$stillbeCombineSocialPhotos?.rest?.queueInterval || 500;   // 5000 [ms]

	if(!STILLBE_REST_API_UPDATE_CACHE_URL) {
		return null;
	}


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
				const _key = objctKey.shift();
				param = _key in param ? param[_key] : "";
			}
			return param;
		});

		const dummyTemp = document.createElement("template");
		dummyTemp.innerHTML = generateHTML;

		return dummyTemp.content;

	};


	/**
	 *  Convert flatten data in form data format into a multidimensional associative array
	 * 
	 *   @param  object $flattenObject
	 *   @return array
	 */
	const convFlattenToMulidimensional = $flattenObject => {

		if(!$flattenObject || typeof $flattenObject !== "object"){
			return {};
		}

		const _parseFromKey = ($obj, $key, $value) => {
			const m = $key.trim().match(/([^\[]*)\[([^\[\]]*)\](?:\[([\s\S]*)\])?/);
			if(m){
				const thisIndex = m[1] || Array.isArray($obj) && $obj.length || Math.max(-1, ...Object.keys($obj).filter($k => Number($k) === ~~$k)) + 1;
				if(typeof $obj[ thisIndex ] !== "object" || Array.isArray()){
					$obj[ thisIndex ] = m[2] ? {} : [];
				}
				if(m[3] || m[3] === ""){
					_parseFromKey( $obj[ thisIndex ], `${m[2]}[${m[3]}]`, $value );
				} else{
					const nextIndex = m[2] || Array.isArray($obj[ thisIndex ]) && $obj[ thisIndex ].length || Math.max(-1, ...Object.keys($obj[ thisIndex ]).filter($k => Number($k) === ~~$k)) + 1;
					$obj[ thisIndex ][ nextIndex ] = $value;
				}
			} else{
				$obj[ $key ] = $value;
			}
		};

		const newObject = {};

		for(const key in $flattenObject){
			_parseFromKey(newObject, key, $flattenObject[ key ]);
		}

		return newObject;

	};


	/**
	 *  Set up a cache update to be run when images have expired
	 * 
	 *   @param  none
	 *   @return none
	 */
	const updateQueueList = [];
	const setUpdateAt404Image = () => {

		const imgSelectors = STILLBE_BLOCK_ROOT_CLASSES.map(c => `.${c} img:not(.emoji)`);
		const igImages     = document.querySelectorAll(imgSelectors.join(", "));

		const sentParams = {};

		const _checkContainsClass = $elem => {
			return STILLBE_BLOCK_ROOT_CLASSES.reduce((b, c) => b || $elem.classList.contains(c), false);
		};

		const _requestUpdate = $img => {
			const rootElem = (_i => {
				let r = _i.parentNode;
				while(r && !_checkContainsClass(r)){
					r = r.parentNode;
				}
				return r;
			})($img);
			const parsedParams = convFlattenToMulidimensional(rootElem.dataset);
			if(parsedParams.advanced?.hashtag_recent){
				// Temporary
				$img.src = STILLBE_CACHE_EXPIRED_THUMBNAIL;
				$img.onerror = null;
				return null;
			/*
				const permalink = ($i => {
					let elem = $i.parentNode;
					while(elem && !elem.classList.contains("ig-post")){
						elem = elem.parentNode;
					}
					return elem && elem.href;
				})($img);
				if(!permalink){
					console.warn("Permalink is unknown.");
					return false;
				}
				parsedParams.permalink = permalink;
			*/
			}
			const body = JSON.stringify(parsedParams);
			if(sentParams[body]){
				console.log("This cache update has been requested.", parsedParams);
			} else{
				sentParams[body] = true;
				updateQueueList.push(body);
				if(updateQueueList.length === 1){
					setTimeout(requestRunFromQueue, STILLBE_QUEUE_INTERVAL);
				}
			}
			$img.src = STILLBE_CACHE_EXPIRED_THUMBNAIL;
			$img.onerror = null;
		}

		igImages.forEach($i => {
			$i.onerror = _requestUpdate.bind(null, $i);
		});

	};


	/**
	 *  Request queues in sequence
	 * 
	 *   @param  none
	 *   @return none
	 */
	const requestRunFromQueue = () => {

		const body = updateQueueList.shift();

		if(!body){
			return null;
		}

		fetch(STILLBE_REST_API_UPDATE_CACHE_URL, {
			method  : "PATCH",
			body    : body,
			headers : {
				"Content-Type": "application/json",
			},
		})
		.then(res => res.json())
		.then(console.log)
		.catch(console.error);

		if(updateQueueList.length){
			setTimeout(requestRunFromQueue, STILLBE_QUEUE_INTERVAL);
		}

	};


	/**
	 *  Set Action to Open Modal Window
	 * 
	 *   @param  Element       $targetElement
	 *   @param  String        $searchClass
	 *   @return Element|null  $foundElement
	 */
	const searchParentElementFromClassName = ($targetElement, $searchClass) => {

		let elem = $targetElement;

		while(elem && !elem.classList.contains($searchClass)){
			elem = elem.parentNode;
		}

		return elem || null;

	};


	/**
	 *  Set Action to Open Modal Window
	 * 
	 *   @param  none
	 *   @return none
	 */
	const setOpenModalWindow = () => {

		const openModalLinks = document.querySelectorAll("a.ig-post[target='stillbe-modal-win']");

		openModalLinks.forEach($a => $a.addEventListener("click", openModalWindowRun, false));

	};


	/**
	 *  Open Modal Window
	 * 
	 *   @param  Event
	 *   @return boolean
	 */
	let openingDirection = null;
	const openModalWindowRun = $e => {

		const a = $e.currentTarget;
		const d = a.dataset;

		if(!d.img || !d.permalink){
			openingDirection = null;
			return true;
		}

		// Format Data
		const data = (data => {
			let caption = "";
			try{
				caption = JSON.parse(data.caption);
			} catch($error){
				console.error($error);
			}
			const medias = data.img.split(",").map($i => {
				const media = {};
				$i.split("||").forEach($u => {
					const m = $u.match(/(image|video|thumb)::(https?:\/\/.+)/);
					if(m){
						media[ m[1].trim() ] = m[2].trim();
					}
				});
				if(Object.keys(media).length < 1){
					return null;
				}
				return media;
			}).filter(Boolean);
			return {
				permalink      : data.permalink,
				profilePicture : data.profilePicture || BLANK_TRANSPARENT_PNG_IMAGE,
				medias         : medias,
				caption        : caption.replace(/\n/g, "<br>"),
				likeCount      : data.likeCount     === "" ? null : data.likeCount     * 1,
				commentsCount  : data.commentsCount === "" ? null : data.commentsCount * 1,
				username       : data.username || null,
				account        : data.name ? `<span>${data.name}</span><span>${data.username}</span>` : `<span>${data.username || ""}</span>`,
				openInstagram  : data.openInstagram,
				timestamp      : data.timestamp,
				time           : data.time,
			};
		})(d);

		const wrapper = searchParentElementFromClassName(a, "ig-wrapper");

		if(!wrapper){
			openingDirection = null;
			return true;
		}

		const temp = wrapper.querySelector(".sb-csp-modal-temp");

		if(!temp){
			openingDirection = null;
			return true;
		}

		$e.preventDefault();

		const residueModals = document.querySelectorAll(".sb-csp-modal-wrapper");
		residueModals.forEach($r => $r.click());

		const frag = cloneTemplate(temp, data);

		// Close Action
		const wrapperElement = frag.querySelector(".sb-csp-modal-wrapper");
		const modalElement   = frag.querySelector(".sb-csp-modal");
		if(!wrapperElement || !modalElement){
			openingDirection = null;
			return true;
		}

		wrapperElement.dataset.active = "true";

		wrapperElement.addEventListener("click", $e => {
			const _this = $e.currentTarget;
			const duration = getComputedStyle(_this).transitionDuration.replace(/(\d+(?:\.\d+)?)(m?s)/i, ($m, $p1, $p2) => $p1 * (/ms/i.test($p2) ? 1 : 1000));
			_this.style.transitionFunction = "ease-out";
			_this.dataset.active = "false";
			setTimeout(() => {
				_this.style.opacity = 0
			}, 50);
			setTimeout(() => {
				_this.remove();
			}, duration);
		}, false);

		modalElement.addEventListener("click", $e => $e.stopPropagation(), false);

		// Medias
		const pictures = frag.querySelector(".sb-csp-modal-pictures");
		data.medias.forEach($src => {
			const li  = pictures.appendChild(document.createElement("li"));
			if($src.video){
				const media = li.appendChild(document.createElement("video"));
				media.src         = $src.video;
				media.poster      = $src.thumb || BLANK_TRANSPARENT_PNG_IMAGE;
				media.className   = "sb-csp-modal-video";
				media.loading     = "lazy";
				media.controls    = true;
				media.muted       = true;
				media.autoplay    = true;
				media.playsinline = true;
			} else if($src.image){
				const media = li.appendChild(new Image());
				const i     = pictures.children.length;
				media.src       = $src.image;
				media.className = "sb-csp-modal-img";
				media.alt       = `Image ${i} of @${data.username}`;
				media.loading   = "lazy";
			}
		});

		// Username
		if(data.username === null){
			const headerElem = frag.querySelector(".sb-csp-modal-header");
			headerElem?.remove();
			const mainElem = frag.querySelector(".sb-csp-modal-main");
			if(mainElem){
				mainElem.style.borderTop = "none";
			}
		}

		// Like Count
		if(data.likeCount === null){
			const likeCountElem = frag.querySelector(".sb-csp-modal-like-count");
			likeCountElem?.remove();
		}

		// Comments Count
		if(data.commentsCount === null){
			const commentsCountElem = frag.querySelector(".sb-csp-modal-comments-count");
			commentsCountElem?.remove();
		}

		// Post Time
		if(!data.timestamp || !data.time){
			const postTimeElem = frag.querySelector(".sb-csp-modal-time");
			postTimeElem?.remove();
		}

		// CTA
		const cta = frag.querySelector(".sb-csp-modal-cta");
		if(cta && !cta.innerHTML.trim()){
			cta.remove();
		}

		document.body.appendChild(frag);

		const Slider = window.__stillbe?.class?.Slider;

		if(!Slider){
			console.error("Class 'Slider' is not found....");
			openingDirection = null;
			return false;
		}

		// Others
		const modalWrapper = document.querySelector(".sb-csp-modal-wrapper[data-active='true']");
		const modal        = modalWrapper.querySelector(".sb-csp-modal");

		// Opening Position Offset
		switch(openingDirection){
			case "to-prev":
				modal.style.transform = "translateX(-4em)";
				break;
			case "to-next":
				modal.style.transform = "translateX(+4em)";
				break;
		}

		// Reset Opening Direction
		openingDirection = null;

		// Show Modal & Set a Slider
		const startTime     = (new Date()).getTime();
		const rootClassName = "sb-csp-modal-pictures-root";
		const timerID = setInterval(() => {
			const now = (new Date()).getTime();
			if(modalWrapper.getElementsByClassName(rootClassName).length > 0 || now - startTime > 250){
				if(modalWrapper){
					modalWrapper.style.opacity = "1";
				}
				if(modal){
					modal.style.opacity = "1";
					modal.style.transform = "none";
				}
				new Slider(pictures, rootClassName);
				clearInterval(timerID);
			}
		}, 25);

		// IG Feed List Element
		const igFeedListElem = searchParentElementFromClassName(a, "ig-feed-wrapper");

		if(!igFeedListElem){
			return false;
		}

		// Set the Prev or Next Modal Display Button
		const currentPostUrl = a.href;
		const anchors        = igFeedListElem.querySelectorAll("a.ig-post[target='stillbe-modal-win']");
		const currentIndex   = Array.from(anchors).map($a => $a.href).indexOf(currentPostUrl);
		if(currentIndex > -1){
			const buttons = {
				prev: anchors[ currentIndex - 1 ],
				next: anchors[ currentIndex + 1 ],
			};
			for(const dir in buttons){
				if(!buttons[dir]){
					continue;
				}
				const button = modalWrapper.appendChild(document.createElement("button"));
				button.type      = "button";
				button.className = `sb-csp-modal-toggle-post-button ${dir}`;
				button.ariaLabel = `Show the ${dir} post`;
				button.addEventListener("click", () => {
					openingDirection = `to-${dir}`;
					buttons[dir].click();
				}, false);
			}
		}

		return false;

	};


	/**
	 *  Set Simple Sliders
	 * 
	 *   @param  Selector of the Slider Containers
	 *   @return void
	 */
	const setSimpleSliders = function($containerSelector){

		const Slider = window.__stillbe?.class?.Slider;

		if(!Slider){
			console.error("Class 'Slider' is not found....");
			return;
		}

		const containerClass = Array.from($containerSelector && document.querySelectorAll($containerSelector) || []);

		const setContainers = containerClass.map($t => {
			for(const slider of sliders){
				if($t === slider.target){
					return null;
				}
			}
			return {
				target   : $t,
				instance : new Slider($t, "sb-csp-simple-slider-root"),
			};
		}).filter(Boolean);

		sliders.push(...setContainers);

	};


	const sliders = [];


	// Global for Editor
	window.__stillbe = window.__stillbe || {};
	window.__stillbe.func = window.__stillbe.func || {};
	window.__stillbe.func.setSimpleSliders = setSimpleSliders.bind(null, ".sb-csp-ig-simple-slider .ig-feed-list");

	// Sliders
	window.__stillbe.vars         = window.__stillbe.vars || {};
	window.__stillbe.vars.sliders = sliders;




	const init = () => {
		// Set Actions
		window.__stillbe.func.setSimpleSliders();
		setUpdateAt404Image();
		setOpenModalWindow();
	};


	if(/complete|loaded|interactive/.test(document.readyState)){
		// readyState = interactive is just before 'DOMContentLoaded' event
		init();
	} else{
		window.addEventListener("DOMContentLoaded", init, false);
	}


})();