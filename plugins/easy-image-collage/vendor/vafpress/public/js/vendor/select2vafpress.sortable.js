/**
 * jQuery select2vafpress Sortable
 * - enable select2vafpress to be sortable via normal select element
 * 
 * author      : Vafour
 * inspired by : jQuery Chosen Sortable (https://github.com/mrhenry/jquery-chosen-sortable)
 * License     : GPL
 */

(function($){
	$.fn.extend({
		select2vafpressSortableOrder: function(){
			var $this = this.filter('[multiple]');

			$this.each(function(){
				var $select  = $(this);

				// skip elements not select2vafpress-ed
				if(typeof($select.data('select2vafpress')) !== 'object'){
					return false;
				}

				var $select2vafpress = $select.siblings('.select2vafpress-container'),
				    unselected = [],
				    sorted;

				$select.find('option').each(function(){
					!this.selected && unselected.push(this);
				});

				sorted = $($select2vafpress.find('.select2vafpress-choices li[class!="select2vafpress-search-field"]').map( function() {
					if (!this) {
						return undefined;
					}
					var id = $(this).data('select2vafpressData').id;
					return $select.find('option[value="' + id + '"]')[0];
				}));

				sorted.push.apply(sorted, unselected);
				$select.children().remove();
				$select.append(sorted);
			});

			return $this;
		},
		select2vafpressSortable: function(){
			var args         = Array.prototype.slice.call(arguments, 0);
			    $this        = this.filter('[multiple]'),
			    validMethods = ['destroy'];

			if(args.length === 0 || typeof(args[0]) === 'object')
			{
				var defaultOptions = {
					bindOrder       : 'formSubmit', // or sortableStop
					sortableOptions : {
						placeholder : 'ui-state-highlight',
						items       : 'li:not(.select2vafpress-search-field)',
						tolerance   : 'pointer'
					}
				};
				var options = $.extend(defaultOptions, args[0]);

				// Init select2vafpress only if not already initialized to prevent select2vafpress configuration loss
				if(typeof($this.data('select2vafpress')) !== 'object'){
					$this.select2vafpress();
				}

				$this.each(function(){
					var $select  = $(this),
					    $select2vafpresschoices = $select.siblings('.select2vafpress-container').find('.select2vafpress-choices');

					// Init jQuery UI Sortable
					$select2vafpresschoices.sortable(options.sortableOptions);

					switch(options.bindOrder){
						case 'sortableStop':
							// apply options ordering in sortstop event
							$select2vafpresschoices.on("sortstop.select2vafpresssortable", function( event, ui ) {
								$select.select2vafpressSortableOrder();
							});
							$select.on('change', function(e){
								$(this).select2vafpressSortableOrder();
							});
							break;
						default:
							// apply options ordering in form submit
							$select.closest('form').unbind('submit.select2vafpresssortable').on('submit.select2vafpresssortable', function(){
								$select.select2vafpressSortableOrder();
							});
					}

				});
			}
			else if(typeof(args[0] === 'string'))
			{
				if($.inArray(args[0], validMethods) == -1)
				{
					throw "Unknown method: " + args[0];
				}
				if(args[0] === 'destroy')
				{
					$this.select2vafpressSortableDestroy();
				}
			}
			return $this;
		},
		select2vafpressSortableDestroy: function(){
			var $this = this.filter('[multiple]');
			$this.each(function(){
				var $select         = $(this),
				    $select2vafpresschoices = $select.parent().find('.select2vafpress-choices');

				// unbind form submit event
				$select.closest('form').unbind('submit.select2vafpresssortable');

				// unbind sortstop event
				$select2vafpresschoices.unbind("sortstop.select2vafpresssortable");

				// destroy select2vafpressSortable
				$select2vafpresschoices.sortable('destroy');
			});
			return $this;
		}
	});
}(jQuery));