function YrmAccordion() {
	this.options = {};
}

YrmAccordion.start = function () {
	jQuery('.yrm-accordion-wrapper').each(function () {
		var options = jQuery(this).data('options');
		var obj = new YrmAccordion()
		obj.options = options;
		obj.init();
	})
}

YrmAccordion.prototype.init = function () {
	var event = 'click';
	var that = this;

	if (this.options['yrm-accordion-activate-event']) {
		event = this.options['yrm-accordion-activate-event'];
	}
	var easings = this.options['yrm-accordion-animate-easings'];
	var duration = parseInt(this.options['yrm-accordion-animate-duration']);

	var keepOpen = true;
	if (typeof this.options['yrm-accordion-keep-extended']) {
		keepOpen = this.options['yrm-accordion-keep-extended'];
	}

	var scrollToActive = this.options['yrm-accordion-scroll-to-active-item'];
	if (scrollToActive) {
		jQuery([document.documentElement, document.body]).animate({
			scrollTop: jQuery(".yrm-accordion-item[data-expanded='1']").first().offset().top
		}, 1000)
	}

	jQuery('.yrm-accordion-item-header').unbind(event).bind(event, function (e) {
		e.preventDefault();
		var parentItem = jQuery(this).parents('.yrm-accordion-item').first();
		var statusExpanded = Boolean(parentItem.data('expanded'));

		var accordionContent = parentItem.find('.yrm-accordion-item-content');
		var icons = jQuery(this).parents(".yrm-accordion-wrapper").data('options')['yrm-accordion-icons'];
		var splittedIcons = icons.split('_');
		var openClass = splittedIcons[0];
		var closeClass = splittedIcons[1];

		if (!statusExpanded) {
			if (keepOpen == 'false') {
				jQuery('.yrm-accordion-item').data('expanded', false);
				//jQuery('.yrm-accordion-item .accordion-header-icon').removeClass('yrm-rotate-90');
				jQuery('.yrm-accordion-item .yrm-accordion-item-content').slideUp(duration, easings, function () {

				});
			}
			parentItem.find('.accordion-header-icon').removeClass(openClass).addClass(closeClass)
			accordionContent.slideToggle(duration, easings, function () {
				parentItem.data('expanded', true);

			});
		}
		else {
			parentItem.find('.accordion-header-icon').removeClass(closeClass).addClass(openClass)
			accordionContent.removeClass('yrm-show')
			accordionContent.slideUp(duration, easings, function () {
				parentItem.data('expanded', false);
			});
		}
	})
}

jQuery(document).ready(function () {
	YrmAccordion.start();
});