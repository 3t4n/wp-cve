(function($) {
	NJBATabs = function( settings )
	{
		this.settings 	= settings;
		this.nodeClass  = '.fl-node-' + settings.id;
		this._init();
	};
	NJBATabs.prototype = {
		settings	: {},
		nodeClass   : '',
		_init: function()
		{
			$(this.nodeClass + ' .njba-tabs-labels .njba-tabs-label').click($.proxy(this._labelClick, this));	
		},
		_labelClick: function(e)
		{
			var label       = $(e.target).closest('.njba-tabs-label'),
				index       = label.data('index'),
				wrap        = label.closest('.njba-tabs'),
				allIcons    = wrap.find('.njba-tabs-label .fa'),
				icon        = wrap.find('.njba-tabs-label[data-index="' + index + '"] .fa');
			// Toggle the tabs.
			wrap.find('.njba-tabs-labels:first > .njba-tab-active').removeClass('njba-tab-active');
			wrap.find('.njba-tabs-panels:first > .njba-tabs-panel > .njba-tab-active').removeClass('njba-tab-active');
			wrap.find('.njba-tabs-labels:first > .njba-tabs-label[data-index="' + index + '"]').addClass('njba-tab-active');
			wrap.find('.njba-tabs-panels:first > .njba-tabs-panel > .njba-tabs-panel-content[data-index="' + index + '"]').addClass('njba-tab-active');
		},
	};
	
})(jQuery);


jQuery(document).ready(function() {
	function close_accordion_section() {
		jQuery('.accordion .accordion-section-title').removeClass('active');
		jQuery('.accordion .accordion-section-content').slideUp(300).removeClass('open');
	}
	jQuery('.accordion-section-title').click(function(e) {
		var currentAttrValue = jQuery(this).attr('href');
		$this = jQuery(this);
		if (jQuery($this).hasClass("active")) {
			close_accordion_section();
		}else {
			close_accordion_section();
			jQuery(this).addClass('active');
			jQuery('.accordion ' + currentAttrValue).slideDown(300).addClass('open'); 
		}

		e.preventDefault();
	});
});