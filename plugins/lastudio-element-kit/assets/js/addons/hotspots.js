class LaStudioKit_Hotspots extends elementorModules.frontend.handlers.Base {
    getDefaultSettings() {
        return {
            selectors: {
                hotspot: '.lakit-hotspot',
                tooltip: '.lakit-hotspot__tooltip'
            }
        };
    }

    getDefaultElements() {
        const selectors = this.getSettings('selectors');
        return {
            $hotspot: this.$element.find(selectors.hotspot),
            $hotspotsExcludesLinks: this.$element.find(selectors.hotspot).filter(':not(.lakit-hotspot--no-tooltip)'),
            $tooltip: this.$element.find(selectors.tooltip),
            $templateRoot: this.$element.closest('.elementor[data-elementor-id]'),
            $parentSwiper: this.$element.closest('.swiper-container'),
        };
    }

    bindEvents() {

        const tooltipTrigger = this.getCurrentDeviceSetting('tooltip_trigger'),
            tooltipTriggerEvent = 'mouseenter' === tooltipTrigger ? 'mouseleave mouseenter' : tooltipTrigger;

        if (tooltipTriggerEvent !== 'none') {
            this.elements.$hotspotsExcludesLinks.on(tooltipTriggerEvent, event => this.onHotspotTriggerEvent(event));
        }
        this.$element.on('touchend', event => {
            if(jQuery(event.target).closest('.lakit-hotspot').length === 0){
                jQuery('.elementor-element-'+this.getID()+' .lakit-hotspot').removeClass('lakit-hotspot--active');
            }
        })
    }

    onDeviceModeChange() {
        this.elements.$hotspotsExcludesLinks.off();
        this.bindEvents();
    }

    onHotspotTriggerEvent(event) {

        if( 'mouseleave' === event.type ) {
            if ('ontouchstart' in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0) {
                return false;
            }
        }
        var self = this;
        const currentHotspotID = event.currentTarget.getAttribute('data-id');

        const elementTarget = jQuery(event.target),
            isHotspotButtonEvent = elementTarget.closest('.lakit-hotspot__button').length,
            isTooltipMouseLeave = 'mouseleave' === event.type && (elementTarget.is('.lakit-hotspot--tooltip-position') || elementTarget.parents('.lakit-hotspot--tooltip-position').length),
            isMobile = 'mobile' === elementorFrontend.getCurrentDeviceMode(),
            isHotspotLink = elementTarget.closest('.lakit-hotspot--link').length,
            triggerTooltip = !(isHotspotLink && isMobile && ('mouseleave' === event.type || 'mouseenter' === event.type));

        const $remoteHotspots = jQuery('.elementor-element-fake-'+this.getID()+ ' .lakit-hotspot');

        if (triggerTooltip && (isHotspotButtonEvent || isTooltipMouseLeave)) {

            const currentHotspot = this.elements.$hotspot.filter('[data-id='+currentHotspotID+']'),
                currentRemoteHotspot = $remoteHotspots.filter('[data-id='+currentHotspotID+']');

            this.elements.$hotspot.not(currentHotspot).removeClass('lakit-hotspot--active');
            $remoteHotspots.not(currentRemoteHotspot).removeClass('lakit-hotspot--active');
            if(currentHotspot.hasClass('lakit-hotspot--active')){
                currentHotspot.removeClass('lakit-hotspot--active');
                currentRemoteHotspot.removeClass('lakit-hotspot--active');
            }
            else{
                self.setupPositionForRelativeToolTip(currentHotspot.find('.lakit-hotspot__tooltip'), currentRemoteHotspot.find('.lakit-hotspot__tooltip'));
                currentHotspot.addClass('lakit-hotspot--active');
                currentRemoteHotspot.addClass('lakit-hotspot--active');
            }
        }
    }

    editorAddSequencedAnimation() {
        this.elements.$hotspot.toggleClass('lakit-hotspot--sequenced', 'yes' === this.getElementSettings('hotspot_sequenced_animation'));
    }

    hotspotSequencedAnimation() {
        const elementSettings = this.getElementSettings(),
            isSequencedAnimation = elementSettings.hotspot_sequenced_animation;

        if ('no' === isSequencedAnimation) {
            return;
        } //start sequenced animation when element on viewport

        const hotspotObserver = elementorModules.utils.Scroll.scrollObserver({
            callback: event => {
                if (event.isInViewport) {
                    hotspotObserver.unobserve(this.$element[0]); //add delay for each hotspot
                    this.elements.$hotspot.each((index, element) => {
                        if (0 === index) {
                            return;
                        }
                        const sequencedAnimation = elementSettings.hotspot_sequenced_animation_duration,
                            sequencedAnimationDuration = sequencedAnimation ? sequencedAnimation.size : 1000,
                            animationDelay = index * (sequencedAnimationDuration / this.elements.$hotspot.length);
                        element.style.animationDelay = animationDelay + 'ms';
                    });
                }
            }
        });
        hotspotObserver.observe(this.$element[0]);
    }

    setTooltipPositionControl( isEditor ) {

        var self = this;

        const elementSettings = this.getElementSettings(),
            isDirectionAnimation = 'undefined' !== typeof elementSettings.tooltip_animation && elementSettings.tooltip_animation.match(/^lakit-hotspot--(slide|fade)-direction/);

        if (isDirectionAnimation) {
            this.elements.$tooltip.removeClass('lakit-hotspot--tooltip-animation-from-left lakit-hotspot--tooltip-animation-from-top lakit-hotspot--tooltip-animation-from-right lakit-hotspot--tooltip-animation-from-bottom');
            this.elements.$tooltip.addClass('lakit-hotspot--tooltip-animation-from-' + elementSettings.tooltip_position);
        }
        const rootTemplateID = this.elements.$templateRoot.data('elementor-id');
        if( jQuery('.elementor-fake-' + rootTemplateID).length === 0 ){
            jQuery('body').append('<div class="elementor elementor-'+rootTemplateID+' elementor-root-fake elementor-fake-'+rootTemplateID+'"></div>')
        }
        const $fakeRoot = jQuery('.elementor-fake-' + rootTemplateID);
        if( jQuery('.elementor-element-fake-' + this.getID()).length === 0 ){
            $fakeRoot.append('<div class="elementor-element-fake elementor-element elementor-element-'+this.getID()+' elementor-element-fake-'+this.getID()+'"></div>')
        }
        const $elFake = jQuery('.elementor-element-fake-' + this.getID());

        $elFake.empty();

        this.elements.$hotspot.each( (index, element) => {
            const $hotspotfake = jQuery(element).clone();
            $hotspotfake.find('.lakit-hotspot__button').remove();
            $hotspotfake.addClass('lakit-hotspot--fake');
            $hotspotfake.appendTo($elFake);
        } );

        const setupFakePos = () => {
            this.elements.$tooltip.each(function (index, element){
                let $_elFake = jQuery('.elementor-element-fake.elementor-element-'+self.getID()+' .lakit-hotspot__tooltip[data-id='+element.getAttribute('data-id')+']');
                if(LaStudioKits.isInViewport(element)){
                    self.setupPositionForRelativeToolTip( jQuery(element), $_elFake );
                }
                else{
                    $_elFake.css({ visibility: 'hidden' });
                }
            })
        }

        if(this.getCurrentDeviceSetting('tooltip_trigger') === 'mouseenter'){
            const $remoteHotspots = jQuery('.elementor-element-fake-'+this.getID()+ ' .lakit-hotspot');
            $remoteHotspots.on('mouseleave', event => this.onHotspotTriggerEvent(event))
        }
        if(isEditor){
            setupFakePos();
        }
        else{
            document.addEventListener('DOMContentLoaded', setupFakePos)
        }
        window.addEventListener('resize', setupFakePos);
        window.addEventListener('scroll', setupFakePos);
    }

    onInit() {
        super.onInit(...arguments);
        this.hotspotSequencedAnimation();
        this.setTooltipPositionControl(false);
        if (window.elementor) {
            elementor.listenTo(elementor.channels.deviceMode, 'change', () => this.onDeviceModeChange());
        }
    }

    onElementChange(propertyName) {
        if (propertyName.startsWith('tooltip_position')) {
            this.setTooltipPositionControl(true);
        }
        if (propertyName.startsWith('hotspot_sequenced_animation')) {
            this.editorAddSequencedAnimation();
        }
    }

    setupPositionForRelativeToolTip( $currentElement, $relativeElement ){

        let elPos = $currentElement.get(0).getBoundingClientRect();

        let cssObj = {
            'left': elPos.left,
            'top': elPos.top,
            'width': elPos.width,
            'right': 'initial',
            'bottom': 'initial',
            'display': 'flex',
            'visibility': 'inherit'
        }
        if(elPos.left <= 10){
            cssObj.left = 10;
        }
        else if( elPos.left + elPos.width > window.innerWidth){
            cssObj.left =  window.innerWidth - elPos.width - 10;
        }
        $relativeElement.css(cssObj)
    }
}

jQuery( window ).on( 'elementor/frontend/init', () => {
    const addHandler = ( $element ) => {
        elementorFrontend.elementsHandler.addHandler( LaStudioKit_Hotspots, { $element } );
    };
    elementorFrontend.hooks.addAction( 'frontend/element_ready/lakit-hotspots.default', addHandler );

    jQuery(document).on('lastudio-kit/carousel/init_success', function (evt, { swiperContainer, SwiperInstance } ){
        if(swiperContainer.find('.elementor-widget-lakit-hotspots').length){
            jQuery('.swiper-slide-duplicate .elementor-widget-lakit-hotspots', swiperContainer).each(function(){
                window.elementorFrontend.hooks.doAction('frontend/element_ready/lakit-hotspots.default', jQuery(this), jQuery);
            })
            SwiperInstance.on('slideChangeTransitionStart', function (){
                jQuery('.lakit-hotspot').removeClass('lakit-hotspot--active')
            })
        }
    });

} );