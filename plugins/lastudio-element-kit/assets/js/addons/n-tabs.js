( function( $, elementorFrontend ) {

    "use strict";

    class NestedTabs extends elementorModules.frontend.handlers.Base {

        getDefaultSettings() {
            const e_id = this.getID();
            return {
                isInPopup: !!this.$element.closest('.elementor-location-popup').length,
                isAccordion: this.$element.hasClass('lakit-ntabs-type--accordion'),
                active_class: 'e-active',
                selectors: {
                    tabs: '.lakit-ntabs',
                    control: '.lakit-ntabs-heading',
                    controlItem: '.lakit-ntab-title:not(.clone--item)',
                    content: '.lakit-ntabs-content',
                    contentItem: '.lakit-ntab-content-' + e_id,
                    cControlItem: '.lakit-ntab-title.e-collapse',
                },
            };
        }
        _debounce(callback, delay = 100){
            let timer;
            return evt => {
                if(timer) clearTimeout(timer);
                timer = setTimeout( callback, delay, evt);
            }
        }

        getDefaultElements() {
            const selectors = this.getSettings( 'selectors' );
            const elements = {
                $tabs: this.$element.find( selectors.tabs ).first(),
                $control: this.$element.find( selectors.control ).first(),
                $content: this.$element.find( selectors.content ).first()
            }
            elements.$selectBoxWrap = $('.ntabs-selectbox--wrap', elements.$control);
            elements.$selectBoxControl = $('.ntabs-selectbox--label', elements.$selectBoxWrap);
            elements.$controlItem = $(selectors.controlItem, elements.$control);
            elements.$cControlItem = $(selectors.cControlItem, elements.$content);
            elements.$contentItem = $(selectors.contentItem, elements.$content);
            return elements;
        }

        bindEvents() {
            this.elements.$controlItem.on( 'click', this.onControlItemClick.bind( this ) );
            this.elements.$cControlItem.on( 'click', this.onCollapseControlItemClick.bind( this ) );

            this.elements.$controlItem.first().trigger('click', [ true, this.getSettings('isAccordion') ]);
            this.elements.$cControlItem.first().trigger('click', [ true, this.getSettings('isAccordion') ]);

            this.elements.$selectBoxControl.on( 'click', this.onSelectBoxControlClick.bind( this ) );

            this.onCanChangeToSelectBox( false );

            window.addEventListener('resize', this._debounce( () => this.onCanChangeToSelectBox( true ) ) )
            window.addEventListener('hashchange', this.onHashChange.bind(this))
            document.addEventListener('click', this.onSelectBoxClose.bind(this) )
            this.onHashChange();
            window.addEventListener('scroll', this.detectSticky.bind(this))
        }

        onCollapseControlItemClick( evt ) {
            evt.preventDefault();
            this.onControlItemClick(evt, false, true)
        }

        onControlItemClick( evt, isAutoTrigger, isAccordion ) {
            evt.preventDefault();

            const tab_as_selectbox = this.getElementSettings('tab_as_selectbox');
            let active_class = this.getSettings('active_class');
            let $currentItem = $(evt.currentTarget);
            let cIndex = $currentItem.data('tabindex') - 1;

            if(tab_as_selectbox === 'yes'){
                this.elements.$selectBoxWrap.removeClass('e-open')
            }

            if( this.elements.$tabs.hasClass('e-active-selectbox') ){
                this.elements.$control.toggleClass('e-open');
            }

            if($currentItem.hasClass(active_class)){
                return;
            }

            if(!this.getSettings('isInPopup') && !isAutoTrigger && !isAccordion && this.elements.$control.hasClass('e--sticky')){
                let _offset = this.elements.$tabs.offset().top - 100;
                if(  !$('.lakit-site-wrapper').hasClass('lakit--is-vheader') ){
                    _offset -= parseInt(document.documentElement.style.getPropertyValue('--lakit-header-height') || 0)
                }
                if(elementorFrontend.elements.$wpAdminBar.length > 0){
                    _offset -= elementorFrontend.elements.$wpAdminBar.height()
                }
                $('html,body').animate({
                    scrollTop: _offset
                }, 300);
            }

            this.elements.$controlItem.each( ( idx, item ) => {
                if(idx !== cIndex){
                    $(item).removeClass(active_class)
                }
                else{
                    $(item).addClass(active_class)
                }
            } );

            this.elements.$cControlItem.each( ( idx, item ) => {
                if(idx !== cIndex){
                    if(isAccordion){
                        setTimeout(() => $(item).removeClass(active_class), 300)
                    }
                    else{
                        $(item).removeClass(active_class);
                    }
                    item.setAttribute('aria-selected', 'false');
                    item.setAttribute('tabindex', '-1');
                }
                else{
                    $(item).addClass(active_class);
                    item.setAttribute('aria-selected', 'true');
                    item.setAttribute('tabindex', '0');
                }
            } )
            this.elements.$contentItem.each( ( idx, item ) => {
                if(idx !== cIndex){
                    $(item).removeClass(active_class);
                    if(isAccordion){
                        $('>.elementor-element', $(item)).slideUp({
                            duration: 300,
                        });
                    }
                }
                else{
                    if(isAccordion){
                        $('>.elementor-element', $(item)).slideDown({
                            duration: 300,
                            start: () => $('>.elementor-element', $(item)).css('display', 'flex')
                        });
                    }
                    $(item).addClass(active_class);
                }
            } );

            let $activeContent = this.elements.$contentItem.eq( cIndex );

            if($('.slick-slider', $activeContent).length > 0){
                try{ $('.slick-slider', $activeContent).slick('setPosition') }
                catch (e) { }
            }
            if($('.swiper-container', $activeContent).length > 0){
                try{ $('.swiper-container', $activeContent).data('swiper').resize.resizeHandler() }
                catch (e) { }
            }
            $('.lakit-masonry-wrapper', $activeContent).trigger('resize');

            if(tab_as_selectbox === 'yes'){
                let cloneItem = $currentItem.clone();
                cloneItem.removeAttr('id');
                cloneItem.addClass('clone--item');
                $('.ntabs-selectbox--label .lakit-ntab-title', this.elements.$control).replaceWith(cloneItem);
            }

            $(document).trigger('lastudio-kit/active-tabs', [ $activeContent ]);
        }

        onSelectBoxControlClick( evt ) {
            evt.preventDefault();
            this.elements.$selectBoxWrap.toggleClass('e-open');
        }
        onSelectBoxClose( evt ){
            if( !$(evt.target).closest(this.elements.$selectBoxWrap).length ){
                this.elements.$selectBoxWrap.removeClass('e-open')
            }
        }
        onElementChange(propertyName) {
            if( 'tab_type' === propertyName ){
                $('>.elementor-element', this.elements.$contentItem).removeAttr('style')
            }
        }
        onCanChangeToSelectBox( isAccordion ) {
            let breakpoint_selector = this.getElementSettings('breakpoint_selector');
            let sticky_breakpoint = this.getElementSettings('sticky_breakpoint');
            if(breakpoint_selector && breakpoint_selector !== 'none'){
                let maxWidth = elementorFrontend.breakpoints.responsiveConfig.breakpoints[breakpoint_selector].value + 1;
                if( window.innerWidth < maxWidth){
                    this.elements.$tabs.addClass('e-active-selectbox');
                }
                else{
                    this.elements.$tabs.removeClass('e-active-selectbox');
                }
            }
            if(sticky_breakpoint && sticky_breakpoint !== 'none'){
                if(sticky_breakpoint === 'all'){
                    this.elements.$control.addClass('e--sticky');
                }
                else{
                    let maxWidth = elementorFrontend.breakpoints.responsiveConfig.breakpoints[sticky_breakpoint].value + 1;
                    if( window.innerWidth < maxWidth){
                        this.elements.$control.addClass('e--sticky');
                    }
                    else{
                        this.elements.$control.removeClass('e--sticky');
                    }
                }
            }

            if(isAccordion){
                this.elements.$contentItem.css("display","")
            }
        }

        onHashChange(){
            let w_hash = window.location.hash.split('#'),
                hashArr = w_hash.filter( _h => {
                    return _h !== '' && document.querySelector('#' + _h + '.lakit-ntab-title')
                })
            if(hashArr.length > 0){
                hashArr.map( _h => jQuery('#' + _h).trigger('click').closest('.lakit-ntabs-heading').removeClass('e-open'))
            }
        }

        detectSticky(evt){
            const elm = this.elements.$control.get(0);
            let eposT = parseInt(getComputedStyle(elm).top);
            if ( elm?.getBoundingClientRect()?.top === eposT ) {
                elm.classList.add("e-sticky--activated");
            } else {
                elm.classList.remove("e-sticky--activated");
            }
        }

        onInit(){
            for (var _len = arguments.length, args = new Array(_len), _key = 0; _key < _len; _key++) {
                args[_key] = arguments[_key];
            }
            this.createMobileTabs(args);
            super.onInit(...args);
        }

        createMobileTabs(args) {
            const settings = this.getSettings();
            const _id = this.getID();
            if (elementorFrontend.isEditMode()) {
                const $widget = this.$element,
                    $removed = this.findElement('.e-collapse').remove();
                let index = 1;
                this.findElement('.e-con').each(function () {
                    const $current = jQuery(this),
                        $desktopTabTitle = $widget.find(`${settings.selectors.control} > *:nth-child(${index})`),
                        mobileTitleHTML = `<div class="lakit-ntab-title e-collapse lakit-ntab-controlid-${_id}" data-tabindex="${index}" data-tab="${index}" role="tab">${$desktopTabTitle.html()}</div>`;
                    if($current.parent('.lakit-ntabs-content-item').length === 0){
                        $current.wrap(`<div class="lakit-ntabs-content-item lakit-ntab-content-${_id}"/>`);
                    }
                    $current.before(mobileTitleHTML);
                    ++index;
                });

                // On refresh since indexes are rearranged, do not call `activateDefaultTab` let editor control handle it.
                if ($removed.length) {
                    return elementorModules.ViewModule.prototype.onInit.apply(this, args);
                }
            }
        }
    }

    $( window ).on( 'elementor/frontend/init', () => {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/lakit-nested-tabs.default', ( $element ) => {
            elementorFrontend.elementsHandler.addHandler( NestedTabs, { $element } );
        } );
    } );

}( jQuery, window.elementorFrontend ) );