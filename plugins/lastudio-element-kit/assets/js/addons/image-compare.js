class LaStudioKit_ImageCompare {
    constructor(el, settings = {}) {
        const defaults = {
            controlColor: "#FFFFFF",
            controlShadow: true,
            addCircle: false,
            addCircleBlur: true,
            showLabels: false,
            labelOptions: {
                before: "Before",
                after: "After",
                onHover: false,
            },
            smoothing: true,
            smoothingAmount: 100,
            hoverStart: false,
            verticalMode: false,
            startingPoint: 50,
            fluidMode: false,
            controlType: 'arrow', // triangle
        };

        this.settings = Object.assign(defaults, settings);

        this.safariAgent = navigator.userAgent.indexOf("Safari") !== -1 && navigator.userAgent.indexOf("Chrome") === -1;
        this.el = el;
        this.images = {};
        this.wrapper = null;
        this.control = null;
        this.arrowContainer = null;
        this.arrowAnimator = [];
        this.active = false;
        this.slideWidth = 50;
        this.lineWidth = 2;
        this.arrowCoordinates = {
            circle: [5, 3],
            standard: [8, 0],
        };
    }

    mount() {
        // Temporarily disable Safari smoothing
        if (this.safariAgent) {
            this.settings.smoothing = false;
        }

        this._shapeContainer();
        this._getImages();
        this._buildControl();
        this._events();
    }

    _events() {
        let bodyStyles = ``;

        // Desktop events
        this.el.addEventListener("mousedown", (ev) => {
            this._activate(true);
            document.body.classList.add("lakit-icv__body");
            this._slideCompare(ev);
        });
        this.el.addEventListener(
            "mousemove",
            (ev) => this.active && this._slideCompare(ev)
        );

        this.el.addEventListener("mouseup", () => this._activate(false));
        document.body.addEventListener("mouseup", () => {
            document.body.classList.remove("lakit-icv__body");
            this._activate(false);
        });

        // Mobile events

        this.control.addEventListener("touchstart", (e) => {
            this._activate(true);
            document.body.classList.add("lakit-icv__body");
        });

        this.el.addEventListener("touchmove", (ev) => {
            this.active && this._slideCompare(ev);
        });
        this.el.addEventListener("touchend", () => {
            this._activate(false);
            document.body.classList.remove("lakit-icv__body");
        });

        // hover

        this.el.addEventListener("mouseenter", () => {
            this.settings.hoverStart && this._activate(true);
            let coord = this.settings.addCircle
                ? this.arrowCoordinates.circle
                : this.arrowCoordinates.standard;

            this.arrowAnimator.forEach((anim, i) => {
                anim.style.cssText = `
        ${
                    this.settings.verticalMode
                        ? `transform: translateY(${coord[1] * (i === 0 ? 1 : -1)}px);`
                        : `transform: translateX(${coord[1] * (i === 0 ? 1 : -1)}px);`
                }
        `;
            });
        });

        this.el.addEventListener("mouseleave", () => {
            let coord = this.settings.addCircle
                ? this.arrowCoordinates.circle
                : this.arrowCoordinates.standard;

            this.arrowAnimator.forEach((anim, i) => {
                anim.style.cssText = `
        ${
                    this.settings.verticalMode
                        ? `transform: translateY(${
                            i === 0 ? `${coord[0]}px` : `-${coord[0]}px`
                        });`
                        : `transform: translateX(${
                            i === 0 ? `${coord[0]}px` : `-${coord[0]}px`
                        });`
                }
        `;
            });
        });
    }

    _slideCompare(ev) {
        let bounds = this.el.getBoundingClientRect();
        let x =
            ev.touches !== undefined
                ? ev.touches[0].clientX - bounds.left
                : ev.clientX - bounds.left;
        let y =
            ev.touches !== undefined
                ? ev.touches[0].clientY - bounds.top
                : ev.clientY - bounds.top;

        let position = this.settings.verticalMode
            ? (y / bounds.height) * 100
            : (x / bounds.width) * 100;

        if (position >= 0 && position <= 100) {
            this.settings.verticalMode
                ? (this.control.style.top = `calc(${position}% - ${
                    this.slideWidth / 2
                }px)`)
                : (this.control.style.left = `calc(${position}% - ${
                    this.slideWidth / 2
                }px)`);

            if (this.settings.fluidMode) {
                this.settings.verticalMode
                    ? (this.wrapper.style.clipPath = `inset(0 0 ${100 - position}% 0)`)
                    : (this.wrapper.style.clipPath = `inset(-1px 0 0 ${position}%)`);
            } else {
                this.settings.verticalMode
                    ? (this.wrapper.style.height = `calc(${position}%)`)
                    : (this.wrapper.style.width = `calc(${100 - position}%)`);
            }
        }
    }

    _activate(state) {
        this.active = state;
    }

    _shapeContainer() {
        let imposter = document.createElement("div");
        let label_l = document.createElement("span");
        let label_r = document.createElement("span");

        label_l.classList.add("lakit-icv__label", "lakit-icv__label-before", "lakit-icv---keep");
        label_r.classList.add("lakit-icv__label", "lakit-icv__label-after", "lakit-icv---keep");

        if (this.settings.labelOptions.onHover) {
            label_l.classList.add("on-hover");
            label_r.classList.add("on-hover");
        }

        if (this.settings.verticalMode) {
            label_l.classList.add("vertical");
            label_r.classList.add("vertical");
        }

        label_l.innerHTML = this.settings.labelOptions.before || "Before";
        label_r.innerHTML = this.settings.labelOptions.after || "After";

        if (this.settings.showLabels) {
            this.el.appendChild(label_l);
            this.el.appendChild(label_r);
        }

        this.el.classList.add(
            `lakit-icv`,
            this.settings.verticalMode
                ? `lakit-icv__is--vertical`
                : `lakit-icv__is--horizontal`,
            this.settings.fluidMode ? `lakit-icv__is--fluid` : `lakit-icv__is--standard`
        );

        imposter.classList.add("lakit-icv__imposter");

        this.el.appendChild(imposter);
    }

    _buildControl() {
        let control = document.createElement("div");
        let uiLine = document.createElement("div");
        let arrows = document.createElement("div");
        let circle = document.createElement("div");

        const arrowSize = "20";

        arrows.classList.add("lakit-icv__theme-wrapper");

        for (var idx = 0; idx <= 1; idx++) {
            let animator = document.createElement(`div`);

            let arrow = `<svg height="15" width="15" style="transform: scale(${this.settings.controlType === 'arrow' ? 0.7 : 1.5}) rotateZ(${ idx === 0 ? this.settings.verticalMode ? `-90deg` : `180deg` : this.settings.verticalMode ? `90deg` : `0deg` });" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 15 15"> <path ${ this.settings.controlType === 'arrow' ? `fill="transparent"` : `fill="${this.settings.controlColor}"` } stroke="${this.settings.controlColor}" stroke-linecap="round" stroke-width="${this.settings.controlType === 'arrow' ? 2 : 0}" d="M4.5 1.9L10 7.65l-5.5 5.4"/></svg>`;
            animator.innerHTML += arrow;
            this.arrowAnimator.push(animator);
            arrows.appendChild(animator);
        }

        let coord = this.settings.addCircle
            ? this.arrowCoordinates.circle
            : this.arrowCoordinates.standard;

        this.arrowAnimator.forEach((anim, i) => {
            anim.classList.add("lakit-icv__arrow-wrapper");
            anim.style.cssText = `
            ${
                this.settings.verticalMode
                    ? `transform: translateY(${
                        i === 0 ? `${coord[0]}px` : `-${coord[0]}px`
                    });`
                    : `transform: translateX(${
                        i === 0 ? `${coord[0]}px` : `-${coord[0]}px`
                    });`
            }
            `;
        });

        control.classList.add("lakit-icv__control");

        control.style.cssText = `
        ${this.settings.verticalMode ? `height` : `width `}: ${this.slideWidth}px;
        ${this.settings.verticalMode ? `top` : `left `}: calc(${
            this.settings.startingPoint
        }% - ${this.slideWidth / 2}px);
        ${
            "ontouchstart" in document.documentElement
                ? ``
                : this.settings.smoothing
                    ? `transition: ${this.settings.smoothingAmount}ms ease-out;`
                    : ``
        }
        `;
        uiLine.classList.add("lakit-icv__control-line");
        uiLine.style.cssText = `
          ${this.settings.verticalMode ? `height` : `width `}: ${this.lineWidth}px;
            ${
            this.settings.controlShadow
                ? `box-shadow: 0px 0px 15px rgba(0,0,0,0.33);`
                : ``
        }
        `;
        let uiLine2 = uiLine.cloneNode(true);

        circle.classList.add("lakit-icv__circle");
        circle.style.cssText = `
          ${
            this.settings.addCircleBlur &&
            `-webkit-backdrop-filter: blur(5px); backdrop-filter: blur(5px)`
        };
          ${
            this.settings.controlShadow &&
            `box-shadow: 0px 0px 15px rgba(0,0,0,0.33)`
        };
        `;

        control.appendChild(uiLine);
        this.settings.addCircle && control.appendChild(circle);
        control.appendChild(arrows);
        control.appendChild(uiLine2);

        this.arrowContainer = arrows;

        this.control = control;
        this.el.appendChild(control);
    }

    _getImages() {
        let children = this.el.querySelectorAll("img, video, .lakit-icv---keep");
        this.el.innerHTML = "";
        children.forEach((img) => {
            this.el.appendChild(img);
        });

        let childrenImages = [...children].filter(
            (element) => ["img", "video"].includes(element.nodeName.toLowerCase())
        );

        //  this.settings.verticalMode && [...children].reverse();
        this.settings.verticalMode && childrenImages.reverse();

        for (let idx = 0; idx <= 1; idx++) {
            let child = childrenImages[idx];

            child.classList.add("lakit-icv__img");
            child.classList.add(idx === 0 ? `lakit-icv__img-a` : `lakit-icv__img-b`);

            if (idx === 1) {
                let wrapper = document.createElement("div");
                let afterUrl = childrenImages[1].src;
                wrapper.classList.add("lakit-icv__wrapper");
                wrapper.style.cssText = `
                width: ${100 - this.settings.startingPoint}%; 
                height: ${this.settings.startingPoint}%;
                ${
                    "ontouchstart" in document.documentElement ? `` : this.settings.smoothing ? `transition: ${this.settings.smoothingAmount}ms ease-out;` : ``
                }
                ${
                    this.settings.fluidMode && `background-image: url(${afterUrl}); clip-path: inset(${ this.settings.verticalMode ? ` 0 0 ${100 - this.settings.startingPoint}% 0` : `-1px 0 0 ${this.settings.startingPoint}%` })`
                }
            `;

                wrapper.appendChild(child);
                this.wrapper = wrapper;
                this.el.appendChild(this.wrapper);
            }
        }
        if (this.settings.fluidMode) {
            let url = childrenImages[0].src;
            let fluidWrapper = document.createElement("div");
            fluidWrapper.classList.add("lakit-icv__fluidwrapper");
            fluidWrapper.style.cssText = `background-image: url(${url});`;
            this.el.appendChild(fluidWrapper);
        }
    }
}

jQuery(window).on('elementor/frontend/init', () => {
    const _default_settings = {
        controlColor: "currentColor",
        controlShadow: false,
        addCircle: false,
        addCircleBlur: false,
        showLabels: false,
        labelOptions: {
            before: "",
            after: "",
            onHover: false,
        },
        smoothing: true,
        smoothingAmount: 50,
        hoverStart: false,
        verticalMode: false,
        startingPoint: 50,
        fluidMode: true,
    };

    elementorFrontend.hooks.addAction('frontend/element_ready/lakit-image-compare.default', ($scope) => {
        const $elm = jQuery('.lakit-image-compare', $scope);
        new LaStudioKit_ImageCompare($elm.get(0), jQuery.extend(_default_settings, $elm.data('settings') || {})).mount();
    });

    jQuery(document).on('lastudio-kit/carousel/init_success', function (evt, {swiperContainer, SwiperInstance}) {
        if (swiperContainer.find('.elementor-lakit-image-compare').length) {
            jQuery('.swiper-slide-duplicate .elementor-lakit-image-compare', swiperContainer).each(function () {
                window.elementorFrontend.hooks.doAction('frontend/element_ready/lakit-image-compare.default', jQuery(this), jQuery);
            })
        }
    });

});