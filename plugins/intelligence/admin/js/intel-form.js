var _intel_form = (function( $ ) {
	'use strict';

	var ths = {};

	$( window ).load(function() {
	  init();
	});

	function init() {
		var id, $this;
		$(".bootstrap-wrapper .fieldset-panel").each(function(index) {
			$this = $(this);

			id = $this.attr('id');
			// strip off 'fieldset-panel-'
			var i = id.substr(15);

			if ($this.hasClass('in')) {
				$('.collapsible-fieldset-icon-' + i).addClass('glyphicon-triangle-bottom');
			}
			else {
				$('.collapsible-fieldset-icon-' + i).addClass('glyphicon-triangle-right');
			}

			$('#fieldset-panel-' + i).first().on('shown.bs.collapse', function(event) {
				// prevents embedded fieldset events from bubbling up.
				event.stopPropagation();
				$('.collapsible-fieldset-icon-' + i).addClass('glyphicon-triangle-bottom').removeClass('glyphicon-triangle-right');
			});
			$('#fieldset-panel-' + i).first().on('hidden.bs.collapse', function(event) {
				// prevents embedded fieldset events from bubbling up.
				event.stopPropagation();
				$('.collapsible-fieldset-icon-' + i).addClass('glyphicon-triangle-right').removeClass('glyphicon-triangle-bottom');
			});


		});

		// transform field descriptions into tooltips
		var $description, $label, content;
		$('.bootstrap-wrapper .form-item').each(function (index, value) {
			$description = $('.description', this);
			if ($description.length) {
				$(this).addClass('form-item-desc-popover');
				$label = $('.control-label', this);
				content = $description.html();
				$label.after(' <a role="button" data-toggle="popover" class="form-desc-popover-btn"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span></a>');
				$description.hide();
			}
		});
    var popovers = $('[data-toggle="popover"]');
		if (popovers.popover) {
			popovers.popover({
				html: true,
				placement: 'auto right',
				content: function() {
					var $formItem = $(this).parent('.form-item-desc-popover');
					return $('.description', $formItem).html();
				}
			});
		};

		// check highlight
		if (0 && _ioq) {
			var urlObj = _ioq.parseUrl(window.location);
			urlObj.params = _ioq.parseUrlSearch(urlObj.search);
			//console.log(urlObj);
		}


		/*
		// transform field descriptions into tooltips
		var $description, $label;
		$('.bootstrap-wrapper .form-item').each(function (index, value) {
			$description = $('.description', this);
			if ($description.length) {
				$label = $('.control-label', this);
				$label.after(' <a href="javascript:void(0)" data-toggle="tooltip" data-placement="bottom" title="' + $description.text() + '"><span class="glyphicon glyphicon-question-sign" aria-hidden="true"></span></a>');
				$description.hide();
			}
		});
		*/
		//return this;
	}

	ths.goto = function ($url) {
		window.location = $url;
	};

	return ths;

})( jQuery );
