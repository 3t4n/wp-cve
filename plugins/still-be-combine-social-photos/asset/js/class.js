(function(){


	"use strict";


	// Slider Class
	const Slider = class {

		constructor($target, $rootClass){
			this.isDoneInitialize = false;
			// Main Elements
			this.target = $target;
			if(this.target.children.length < 1){
				return;
			}
			this.parent = this.target.parentNode;
			this.root   = (($p, $r) => {
				let p = $p;
				while(p && !p.classList?.contains($r)){
					p = p.parentNode;
				}
				return p;
			})(this.parent, $rootClass);
			if(!this.root){
				console.error(`Root element of Simple Slider is not found.... Element className is '${$rootClass}'.`);
				return;
			}
			this.target.dataset.role = "target";
			// Constance Values
			this.setConstanceValues();
			// Set Elements
			this.setElements();
			// Set Scroll Operation
			this.setScroll();
			// Set Resize Operation
			this.resize();
			//
			this.isDoneInitialize = true;
		}

		setConstanceValues(){
			if(this.isDoneInitialize){
				return;
			}
			// Image Size
			this.widthImage = Number(this.root?.style.getPropertyValue("--sb-csp-width-base").trim().replace(/(\d+(?:\.\d+)?)px/, "$1")) || 300;   // Initial Value is Base Width
			Object.defineProperty(this, "widthBase", { value: this.widthImage });
			Object.defineProperty(this, "widthMin",  { value: Number(this.root?.style.getPropertyValue("--sb-csp-width-min" ).trim().replace(/(\d+(?:\.\d+)?)px/, "$1")) || 200 });
			Object.defineProperty(this, "colsMin",   { value: Number(this.root?.style.getPropertyValue("--sb-csp-columns-min")) || 2 });
			// Is Touch Device
			Object.defineProperty(this, "isTouchDevice", { value: "ontouchstart" in window || "ontouchstart" in document.body });
			// Transition Time
			const duration = this.root?.style.getPropertyValue("--sb-csp-scroll-duration").trim().replace(/(\d+(?:\.\d+)?)([um]?s)/i, ($m, $num, $unit) => {
				if($unit === "us"){
					return Number($num / 1000);
				}
				if($unit === "ms"){
					return Number($num);
				}
				return Number($num * 1000);
			});
			Object.defineProperty(this, "duration", { value: duration || 300 });
			// Numerical accuracy of cubic-bezier
			Object.defineProperty(this, "cubicBezierAccuracy", { value: Number(this.root?.style.getPropertyValue("--sb-csp-cubix-bezier-acc")) || 5e-2 });   // Default: 5%
			// Easing Function
			const easingFunction = ((_this, $m) => {
				switch($m){
					case "1":
						return _this.easeLinear;
					break;
					case "2":
						return _this.easeInOutSine;
					break;
					case "3":
						return _this.easeInOutQuad;
					break;
					case "4":
						return _this.easeOutBounce;
					break;
					case "5":
						return _this.easeInOutCubic;
					break;
					
					case "99":
						// Default: cubic-bezier(0.46, 0, 0.36, 1.44)
						const x1 = _this.clamp(0, Number(_this.root?.style.getPropertyValue("--sb-csp-cubix-bezier-x1").trim() || 0.46), 1);
						const y1 = Number(_this.root?.style.getPropertyValue("--sb-csp-cubix-bezier-y1").trim() || 0   );
						const x2 = _this.clamp(0, Number(_this.root?.style.getPropertyValue("--sb-csp-cubix-bezier-x2").trim() || 0.36), 1);
						const y2 = Number(_this.root?.style.getPropertyValue("--sb-csp-cubix-bezier-y2").trim() || 1.44);
						return _this.cubicBezier(x1, y1, x2, y2);
					break;
				}
				return _this.easeInOutQuad;
			})(this, this.root?.style.getPropertyValue("--sb-csp-easing-function").trim());
			Object.defineProperty(this, "easingFunction", { value: easingFunction || this.easeInOutQuad });
		}

		setElements(){
			if(this.isDoneInitialize){
				return;
			}
			// Add Styles at Attribute
			this.target.style.overflow = "hidden";
			if(window.getComputedStyle(this.parent).position === "static"){
				this.parent.style.position = "relative";
			}
			// Fit to Container
			this.adjustImageWidth();
			// Scroll Buttons
			if(!this.isTouchDevice){
				this.buttonMoveLeft  = this.parent.appendChild(document.createElement("button"));
				this.buttonMoveRight = this.parent.appendChild(document.createElement("button"));
				this.buttonMoveLeft .className = "ig-scroll-move-left";
				this.buttonMoveRight.className = "ig-scroll-move-right";
				this.buttonMoveLeft .type      = "button";
				this.buttonMoveRight.type      = "button";
				this.buttonMoveLeft .ariaLabel = "Slide to Left";
				this.buttonMoveRight.ariaLabel = "Slide to Right";
			}
			// Show Navigation
			this.setNavigationElements();
		}

		setNavigationElements(){
			if(this.nav){
				this.nav.remove();
				delete this.nav;
			}
			this.nav = this.parent.insertBefore(document.createElement("ul"), this.target.nextElementSibling);
			this.nav.className = "ig-scroll-nav";
			this.navs = [];
			const widthClient = this.getBoxWidthClient();
			const widthScroll = this.getBoxWidthScroll();
			if(widthScroll === widthClient){
				this.nav.remove();
				this.buttonMoveLeft?.remove();
				this.buttonMoveRight?.remove();
				delete this.nav, this.buttonMoveLeft, this.buttonMoveRight;
			}
			const nums = Math.ceil( widthScroll / this.imagePlusGap );
			for(let i = 0; i < nums; ++i){
				this.navs.push(this.nav.appendChild(document.createElement("li")));
				const _nav = this.navs[i].appendChild(document.createElement("button"));
				_nav.addEventListener("click", this.scrollToIndex.bind(this, i), false);
				_nav.type      = "button";
				_nav.ariaLabel = `Slide to Index ${i + 1}`;
			}
			const _this = this;
			this.navPos = new Proxy({ current: 0 }, {
				set(_obj, _prop, _newVal){
					const _oldVal = _obj[_prop];
					if(_prop === "current"){
						if(_this.navs[_oldVal]){
							_this.navs[_oldVal].classList.remove("current");
						}
						if(_this.navs[_newVal]){
							_this.navs[_newVal].classList.add("current");
						}
					}
					_obj[_prop] = _newVal;
					return true;
				}
			});
			const right = this.getCurrentRight();
			this.showNavCurrent(right);
			// Arrow Buttons
			if(!this.isTouchDevice){
				const rectTarget     = this.target.getBoundingClientRect();
				const rectParent     = this.parent.getBoundingClientRect();
				const positionTop    = rectTarget.top    - rectParent.top;
				const positionBottom = rectParent.bottom - rectTarget.bottom;
				this.buttonMoveLeft .style.top    = positionTop + "px";
				this.buttonMoveRight.style.top    = positionTop + "px";
				this.buttonMoveLeft .style.bottom = positionBottom + "px";
				this.buttonMoveRight.style.bottom = positionBottom + "px";
				this.buttonMoveLeft .style.width  = `clamp(3em, ${this.widthImage * 0.2}px, 6em)`;
				this.buttonMoveRight.style.width  = `clamp(3em, ${this.widthImage * 0.2}px, 6em)`;
			}
		}

		showNavCurrent($right, $index){
			if($index !== undefined){
				this.navPos.current = $index;
				this.toggleDisplayButtonMove($index);
				return;
			}
			const scrollMax = this.getBoxWidthScroll() - this.getBoxWidthClient();
			const navsNum   = this.navs.length;
			const divisions = Math.max(1, (navsNum - 1) * 2);
			const divBase   = scrollMax / divisions;
			if(divBase < 1){
				return;
			}
			let x = 0;
			while($right >= x++ * divBase){
				if($right < x * divBase){
					break;
				}
			}
			const index = ~~(x / 2);
			this.navPos.current = index;
			this.toggleDisplayButtonMove(index)
		}

		toggleDisplayButtonMove($index){
			if($index === undefined){
				return;
			}
			const indexMax = this.navs.length - 1;
			if(!this.isTouchDevice){
				this.buttonMoveLeft .style.display = $index > 0        ? "" : "none";
				this.buttonMoveRight.style.display = $index < indexMax ? "" : "none";
			}
		}

		getBoxWidthClient(){
			return this.target.clientWidth;
		}

		getBoxWidthScroll(){
			return this.target.scrollWidth;
		}

		getCurrentRight(){
			return this.target.getBoundingClientRect().left - this.target.firstElementChild.getBoundingClientRect().left;
		}

		easeLinear($t){
			return $t;
		}

		easeInOutSine($t){
			return -(Math.cos(Math.PI * $t) - 1) / 2;
		}

		easeInOutQuad($t){
			return $t < 0.5 ? (2 * $t * $t) : (-1 + (4 - 2 * $t) * $t);
		}

		easeInOutCubic($t){
			return $t < 0.5 ? 4 * ($t ** 3) : 1 - (-2 * $t + 2 ) ** 3 / 2;
		}

		easeOutBounce($t){
			const n1 = 7.5625;
			const d1 = 2.75;
			if($t < 1 / d1){
				return n1 * $t * $t;
			} else if($t < 2 / d1){
				return n1 * ($t -= 1.5 / d1) * $t + 0.75;
			} else if($t < 2.5 / d1){
				return n1 * ($t -= 2.25 / d1) * $t + 0.9375;
			} else{
				return n1 * ($t -= 2.625 / d1) * $t + 0.984375;
			}
		}

		cubicBezier($x1, $y1, $x2, $y2){
			// Refer: http://www.moshplant.com/direct-or/bezier/math.html
			const cx = 3 * $x1,
			      bx = 3 * ($x2 - $x1) - cx,
			      ax = 1 - cx - bx;
			const cy = 3 * $y1,
			      by = 3 * ($y2 - $y1) - cy,
			      ay = 1 - cy - by;
			// X-coordinate with mediate variable displayed
			var bezierX = $t => {
				return $t * (cx + $t * (bx + $t * ax));
			};
			// t-diff of X coordinate
			var bezierDX = $t => {
				return cx + $t * (2 * bx + 3 * ax * $t);
			};
			// Numerical analysis by Newton's method
			var newtonRaphson = $x => {
				if($x <= 0){
					return 0;
				}
				if($x >= 1){
					return 1;
				}
				let prev, t = $x;
				do{
					prev = t;
					t = t - ((bezierX(t) - $x) / bezierDX(t));
				} while(Math.abs(t - prev) > this.cubicBezierAccuracy);
				return t;
			};
			return function($t){
				// Obtain the value of the mediator variable t corresponding to the X coordinate (time)
				const t = newtonRaphson($t);
				// Calculate Y-coordinate (Easing amount)
				return t * (cy + t * (by + t * ay));
			};
		}

		clamp($min, $value, $max){
			const value = Number($value) || 0;
			return Math.min($max, Math.max($min, value));
		}

		getGap(){
			const _this = this;
			const gap = Number(window.getComputedStyle(this.target).columnGap.replace(/(\d+(?:\.\d+)?)(px|%)/, ($m, $num, $unit) => {
				if($unit === "px"){
					return Number($num);
				}
				if($unit === "%"){
					return Number($num) / 100 * _this.parent.clientWidth;
				}
				return 0;
			}));
			if(!Number.isFinite(gap)){
				return 0;
			}
			return gap;
		}

		adjustImageWidth(){
			this.parent.style.removeProperty("width");
			const widthClient = this.getBoxWidthClient();
			const widthScroll = this.getBoxWidthScroll();
			if(widthClient === widthScroll){
				this.parent.style.width = widthClient + "px";
				this.root.style.removeProperty("--sb-csp-width-fit");
				return true;
			}
			if(!this.root){
				return false;
			}
			//
			const gap = this.getGap();
			let columns    = Math.max( this.colsMin, Math.ceil( ( widthClient + gap ) / ( this.widthBase + gap ) ) );
			let widthGuess = ( widthClient + gap ) / columns - gap;
			while(widthGuess < this.widthMin && columns > this.colsMin){
				widthGuess = ( widthClient + gap ) / --columns - gap;
			}
			if(this.parent.dataset.size !== "fit-container"){
				widthGuess = Math.min(widthGuess, this.widthBase);
				this.parent.style.setProperty("width", `${( this.widthImage + gap ) * this.columns - gap}px`);
			//	this.root.style.removeProperty("--sb-csp-width-fit");
			}
			this.widthImage = widthGuess;
			this.root.style.setProperty("--sb-csp-width-fit", this.widthImage + "px");
			this.columns   = columns;
			this.isColsMin = this.columns <= this.colsMin;
			this.imagePlusGap = ( this.widthImage + gap ) * this.columns;
			return true;
		}

		setScroll(){
			if(this.isTouchDevice){
				this.setScrollSP();
			} else{
				this.setScrollPC();
			}
		}

		setScrollSP(){
			if(this.isDoneInitialize){
				return;
			}
			// Touch Vars
			const touch = {
				startX: 0,
				movedX: 0,
				currentRight: 0,
				startTime: 0,
			};
			// Self Instance
			const _this = this;
			// Touch Start
			this.target.addEventListener("touchstart", $event => {
				touch.startX = touch.movedX = $event.changedTouches[0].pageX;
				touch.currentRight = _this.getCurrentRight();
				touch.startTime    = (new Date()).getTime();
			}, {passive : false});
			// Touch Moving
			this.target.addEventListener("touchmove", $event => {
				touch.movedX  = $event.changedTouches[0].pageX;
				// Box Width
				const widthClient = _this.getBoxWidthClient();
				const widthScroll = _this.getBoxWidthScroll();
				// Scroll Position Follows Swiping
				const max   = widthScroll - widthClient;
				const move  = touch.currentRight + (touch.startX - touch.movedX);
				const right = Math.max(0, Math.min(move, max));
				// Scroll
				_this.target.scrollTo(right, 0);
				// Show Current Nav
				_this.showNavCurrent(right);
			}, {passive : false});
			// Touch End
			this.target.addEventListener("touchend", $event => {
				if(!this.isColsMin){
					// Box Width
					const widthClient = _this.getBoxWidthClient();
					const widthScroll = _this.getBoxWidthScroll();
					// Scroll Position Follows Swiping
					const max   = widthScroll - widthClient;
					const move  = touch.startX - touch.movedX;
					const time  = (new Date()).getTime() - touch.startTime;
					let accelerate = move / time * 17;
					// Momentum Scrolling
					let timestamp = null;
					window.requestAnimationFrame(function momentumScrolling($timestamp){
						timestamp = timestamp || $timestamp;
						accelerate = accelerate / Math.pow(1.06, ($timestamp - timestamp) / 17);
						timestamp = $timestamp;
						const targetLeft = _this.target.getBoundingClientRect().left;
						const scrollLeft = _this.target.firstElementChild.getBoundingClientRect().left;
						const right = _this.clamp(0, targetLeft - scrollLeft + accelerate, max);
						if(right <= 0 || right >= max){
							accelerate = 0;
						}
						// Scroll
						_this.target.scrollTo(right, 0);
						// Show Current Nav
						_this.showNavCurrent(right);
						// Render a Next Frame
						if(Math.abs(accelerate) > 1){
							window.requestAnimationFrame(momentumScrolling);
						}
					});
				} else{
					// Box Width
					const widthClient = _this.getBoxWidthClient();
					const widthScroll = _this.getBoxWidthScroll();
					// Box Scrolled Position
					const right = _this.getCurrentRight();
					// Gap
					const gap = _this.getGap();
					// The Min Columns are Scrolled Together
					const goal = ($right => {
						let goal = 0;
						if(Math.abs(touch.currentRight - $right) / widthClient < 0.2){
							goal = touch.currentRight;
						} else{
							if(touch.currentRight > $right){
								goal = Math.max(0, touch.currentRight - widthClient - gap)
							} else{
							//	goal = Math.min(touch.currentRight + _this.imagePlusGap, widthScroll - widthClient);
								goal = touch.currentRight + _this.imagePlusGap;
							}
						}
						goal = ~~( goal / _this.imagePlusGap + 0.5 ) * _this.imagePlusGap;
						return goal;
					})(right);
					// Scrolling
					_this.scrollTo({
						right : right,
						goal  : goal,
					});
				}
			}, false);
		}

		setScrollPC(){
			if(this.isDoneInitialize){
				return;
			}
			/**
			 * Calcurate the Next Position
			 * 
			 * @param     $direction: -1 or +1
			 * @required  Bind a Class Instance to 'this'
			 */
			const calcGoal = function($direction){
				this.scrollToIndex(this.navPos.current + $direction);
			};
			// Set Event
			this.buttonMoveLeft .addEventListener("click", calcGoal.bind(this, -1), false);
			this.buttonMoveRight.addEventListener("click", calcGoal.bind(this, +1), false);
		}

		scrollToIndex($index){
			const index = ~~(this.clamp(0, $index, this.navs.length - 1));
			const goal  = this.imagePlusGap * index;
			const right = this.getCurrentRight();
			this.scrollTo({
				right : right,
				goal  : goal,
				index : index,
			});
		}

		scrollTo($scrollParams){
			const _this     = this;
			let   _duration = Number(this.duration);
			// Max Scroll Width
			const scrollMax = this.getBoxWidthScroll() - this.getBoxWidthClient();
			// Previous Position
			const startRelativePos   = ($scrollParams.goal < $scrollParams.right ? +1 : -1) * this.imagePlusGap;
			$scrollParams.rightStart = $scrollParams.goal + startRelativePos;
			// Halfway Scrolling
			if(scrollMax < $scrollParams.goal){
				_duration          = ~~(_duration * Math.sqrt( Math.abs( (scrollMax - $scrollParams.rightStart) / ($scrollParams.goal - $scrollParams.rightStart) ) ) );
				$scrollParams.goal = scrollMax;
			}
			if($scrollParams.right > $scrollParams.goal && this.imagePlusGap > $scrollParams.right){
				_duration          = ~~(_duration * Math.sqrt( $scrollParams.right / this.imagePlusGap ) );
				$scrollParams.goal = 0;
			}
			// Run Animation
			window.requestAnimationFrame(function easeScrolling($microTimestamp){
				const now = $microTimestamp;
				// Start Time
				$scrollParams.timeStart = $scrollParams.timeStart || now;
				// 
				const spentTime = now - $scrollParams.timeStart;
				const process   = spentTime / _duration;
				// Scroll
				const right = $scrollParams.right + ($scrollParams.goal - $scrollParams.right) * _this.easingFunction(process);
				_this.target.scrollTo(right, 0);
				if(right < 0){
					_this.target.style.setProperty("--sb-csp-scroll-right", `${right}px`);
				}
				if(right > scrollMax){
					_this.target.style.setProperty("--sb-csp-scroll-right", `${right - scrollMax}px`);
				}
				// Show Current Nav
				_this.showNavCurrent(right, $scrollParams.index);
				// Render a Next Frame
				if(Math.abs(process) < 1){
					window.requestAnimationFrame(easeScrolling);
				}
			});
		}

		resize(){
			if(this.isDoneInitialize){
				return;
			}
			// Global
			window.__stillbe = window.__stillbe || {};
			window.__stillbe.resizeObserver = window.__stillbe.resizeObserver || new ResizeObserver(entries => {
				let instance = null;
				for(const entry of entries){
					instance = null;
					for(const slider of (window.__stillbe?.vars?.sliders || [])){
						if(entry.target.querySelector("[data-role='target']") === slider.target){
							instance = slider.instance;
							break;
						}
					}
					if(instance){
						instance.adjustImageWidth();
						instance.target.scrollTo(0, 0);
						instance.setNavigationElements();
					}
				}
			});
			// Observe Slider Target Element
			window.__stillbe.resizeObserver.observe(this.root);
		/*
			// 
			const _this = this;
			window.addEventListener("resize", () => {
				_this.adjustImageWidth();
				_this.target.scrollTo(0, 0);
				_this.setNavigationElements();
			}, false);
		*/
		}

	}


	// Global for Editor
	window.__stillbe              = window.__stillbe || {};
	window.__stillbe.class        = window.__stillbe.class || {};
	window.__stillbe.class.Slider = window.__stillbe.class.Slider || Slider;


})();