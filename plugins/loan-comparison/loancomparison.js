jQuery(document).ready(function($) {
(function(factory) {
	'use strict';

	if (typeof define === 'function' && define.amd) {
		// AMD. Register as an anonymous module.
		define(['jquery'], factory);
	}
	else if (typeof exports === 'object') {
		// CommonJS
		factory(require('jquery'));
	} else {
		// Browser globals
		factory(jQuery);
	}
}(function($) {
	'use strict';

	/**
	 * Range feature detection
	 * @return {Boolean}
	 */
	function supportsRange() {
		var input = document.createElement('input');
		input.setAttribute('type', 'range');
		return input.type !== 'text';
	}

	var pluginName = 'loancomparison',
		pluginInstances = [],
		inputrange = supportsRange(),
		defaults = {
			polyfill: true,
			rangeClass: 'loancomparison',
			disabledClass: 'loancomparison--disabled',
			fillClass: 'loancomparison__fill',
			handleClass: 'loancomparison__handle',
			startEvent: ['mousedown', 'touchstart', 'pointerdown'],
			moveEvent: ['mousemove', 'touchmove', 'pointermove'],
			endEvent: ['mouseup', 'touchend', 'pointerup']
		};

	function delay(fn, wait) {
		var args = Array.prototype.slice.call(arguments, 2);
		return setTimeout(function(){ return fn.apply(null, args); }, wait);
	}

	function debounce(fn, debounceDuration) {
		debounceDuration = debounceDuration || 100;
		return function() {
			if (!fn.debouncing) {
				var args = Array.prototype.slice.apply(arguments);
				fn.lastReturnVal = fn.apply(window, args);
				fn.debouncing = true;
			}
			clearTimeout(fn.debounceTimeout);
			fn.debounceTimeout = setTimeout(function(){
				fn.debouncing = false;
			}, debounceDuration);
			return fn.lastReturnVal;
		};
	}

	function Plugin(element, options) {
		this.$window	= $(window);
		this.$document  = $(document);
		this.$element   = $(element);
		this.options	= $.extend( {}, defaults, options );
		this._defaults  = defaults;
		this._name	  = pluginName;
		this.startEvent = this.options.startEvent.join('.' + pluginName + ' ') + '.' + pluginName;
		this.moveEvent  = this.options.moveEvent.join('.' + pluginName + ' ') + '.' + pluginName;
		this.endEvent   = this.options.endEvent.join('.' + pluginName + ' ') + '.' + pluginName;
		this.polyfill   = this.options.polyfill;
		this.onInit	 = this.options.onInit;
		this.onSlide	= this.options.onSlide;
		this.onSlideEnd = this.options.onSlideEnd;

		if (this.polyfill) {
			if (inputrange) { return false; }
		}

		this.identifier = 'js-' + pluginName + '-' +(+new Date());
		this.min		= parseFloat(this.$element[0].getAttribute('min') || 0);
		this.max		= parseFloat(this.$element[0].getAttribute('max') || 100);
		this.value	  = parseFloat(this.$element[0].value || this.min + (this.max-this.min)/2);
		this.step	   = parseFloat(this.$element[0].getAttribute('step') || 1);
		this.$fill	  = $('<div class="' + this.options.fillClass + '" />');
		this.$handle	= $('<div class="' + this.options.handleClass + '" />');
		this.$range	 = $('<div class="' + this.options.rangeClass + '" id="' + this.identifier + '" />').insertAfter(this.$element).prepend(this.$fill, this.$handle);

		this.$element.css({
			'position': 'absolute',
			'width': '1px',
			'height': '1px',
			'overflow': 'hidden',
			'opacity': '0'
		});

		this.handleDown = $.proxy(this.handleDown, this);
		this.handleMove = $.proxy(this.handleMove, this);
		this.handleEnd  = $.proxy(this.handleEnd, this);

		this.init();

		var _this = this;
		
		this.$window.on('resize' + '.' + pluginName, debounce(function() {
			delay(function() { _this.update(); }, 300);
		}, 20));

		this.$document.on(this.startEvent, '#' + this.identifier + ':not(.' + this.options.disabledClass + ')', this.handleDown);

		this.$element.on('change' + '.' + pluginName, function(e, data) {
			if (data && data.origin === pluginName) {
				return;
			}

			var value = e.target.value,
				pos = _this.getPositionFromValue(value);
			_this.setPosition(pos);
		});
	}

	Plugin.prototype.init = function() {
		if (this.onInit && typeof this.onInit === 'function') {
			this.onInit();
		}
		this.update();
	};

	Plugin.prototype.attributes = function() {
		var e = this.$element;
		
		this.min = parseFloat(e.attr('min'));
		this.max = parseFloat(e.attr('max'));
		this.value = parseFloat(e.attr('value'));
		if (this.value > this.max) this.setValue(this.max);
		if (this.value < this.min) this.setValue(this.min);
		if (this.value > this.min && this.value > this.min) this.setValue(this.value);
		
		this.update();
	};
	
	Plugin.prototype.update = function() {
		this.handleWidth	= this.$handle[0].offsetWidth;
		this.rangeWidth	 = this.$range[0].offsetWidth;
		this.maxHandleX	 = this.rangeWidth - this.handleWidth;
		this.grabX		  = this.handleWidth / 2;
		this.position	   = this.getPositionFromValue(this.value);
		
		if (this.$element[0].disabled) {
			this.$range.addClass(this.options.disabledClass);
		} else {
			this.$range.removeClass(this.options.disabledClass);
		}

		this.setPosition(this.position);
	};

	Plugin.prototype.handleDown = function(e) {
		e.preventDefault();
		this.$document.on(this.moveEvent, this.handleMove);
		this.$document.on(this.endEvent, this.handleEnd);

		if ((' ' + e.target.className + ' ').replace(/[\n\t]/g, ' ').indexOf(this.options.handleClass) > -1) {
			return;
		}

		var posX = this.getRelativePosition(this.$range[0], e),
			handleX = this.getPositionFromNode(this.$handle[0]) - this.getPositionFromNode(this.$range[0]);

		this.setPosition(posX - this.grabX);

		if (posX >= handleX && posX < handleX + this.handleWidth) {
			this.grabX = posX - handleX;
		}
	};

	Plugin.prototype.handleMove = function(e) {
		e.preventDefault();
		var posX = this.getRelativePosition(this.$range[0], e);
		
		this.setPosition(posX - this.grabX);
	};

	Plugin.prototype.handleEnd = function(e) {
		e.preventDefault();
		this.$document.off(this.moveEvent, this.handleMove);
		this.$document.off(this.endEvent, this.handleEnd);

		if (this.onSlideEnd && typeof this.onSlideEnd === 'function') {
			this.onSlideEnd(this.position, this.value);
		}
	};

	Plugin.prototype.cap = function(pos, min, max) {
		if (pos < min) { return min; }
		if (pos > max) { return max; }
		return pos;
	};

	Plugin.prototype.setPosition = function(pos) {
		var value, left;

		value = (this.getValueFromPosition(this.cap(pos, 0, this.maxHandleX)) / this.step) * this.step;
		left = this.getPositionFromValue(value);

		this.$fill[0].style.width = (left + this.grabX)  + 'px';
		this.$handle[0].style.left = left + 'px';
		
		this.setValue(value);

		this.position = left;
		this.value = value;

	};

	Plugin.prototype.getPositionFromNode = function(node) {
		var i = 0;
		while (node !== null) {
			i += node.offsetLeft;
			node = node.offsetParent;
		}
		return i;
	};

	Plugin.prototype.getRelativePosition = function(node, e) {
		return (e.pageX || e.originalEvent.clientX || e.originalEvent.touches[0].clientX || e.currentPoint.x) - this.getPositionFromNode(node);
	};

	Plugin.prototype.getPositionFromValue = function(value) {
		var percentage, pos;
		percentage = (value - this.min)/(this.max - this.min);
		pos = percentage * this.maxHandleX;
		
		return pos;
	};

	Plugin.prototype.getValueFromPosition = function(pos) {
		var percentage, value;
		percentage = ((pos) / (this.maxHandleX || 1));
		value = this.step * Math.round((((percentage) * (this.max - this.min)) + this.min) / this.step);
		return Number((value).toFixed(2));
	};

	Plugin.prototype.setValue = function(value) {
		if (value !== this.value) {
			this.$element.val(value).trigger('change', {origin: pluginName});
		}
	};

	Plugin.prototype.destroy = function() {
		this.$document.off(this.startEvent, '#' + this.identifier, this.handleDown);
		this.$element
			.off('.' + pluginName)
			.removeAttr('style')
			.removeData('plugin_' + pluginName);

		if (this.$range && this.$range.length) {
			this.$range[0].parentNode.removeChild(this.$range[0]);
		}

		pluginInstances.splice(pluginInstances.indexOf(this.$element[0]),1);
		if (!pluginInstances.length) {
			this.$window.off('.' + pluginName);
		}
	};

	$.fn[pluginName] = function(options) {
		return this.each(function() {
			var $this = $(this),
				data  = $this.data('plugin_' + pluginName);

			if (!data) {
				$this.data('plugin_' + pluginName, (data = new Plugin(this, options)));
				pluginInstances.push(this);
			}

			if (typeof options === 'string') {
				data[options]();
			}
		});
	};

}));
});

var loancomparison_loan_selector = 'form.loancomparison_form';
var loancomparison_slider_selector = 'div.loancomparison-range';

function loancomparisonShowMore(e) {
	var $			= jQuery,
		form		= $(this).closest(loancomparison_loan_selector),
		rates		= sc_rates;
		hidden		= form.find('.bank_box:hidden');
		filtered	= hidden.filter(":lt(3)");
	
	filtered.each(function() {
		$(this).fadeIn(500);
	});
	
	if (hidden.size() == filtered.size()) {
		form.find('#lc_show_more').hide();
	}
}

function loancomparison_repeat(n,max) {
	if (n > max) n = max;
	
	var newString = '';
	for (i = n; i > 0; i--) {
		if (i < 1)
			newString += '<i class="fas fa-star-half-alt" aria-hidden="true"></i>';
		else
			newString += '<i class="fas fa-star" aria-hidden="true"></i>';
	}
	
	for (n = Math.ceil(n); n < max; n++) {
		newString += '<i class="far fa-star" aria-hidden="true"></i>';
	}
	
	return newString;
}

function old_loancomparison_repeat(n,max) {
	if (n > max) n = max;
	if(n % 1 !== 0) n=n-1;
	var newString = '<div class="bank_rating">';
	for (i = 0; i < n; i++) {
		newString += '<i class="fas fa-star" aria-hidden="true"></i>';
	}
	if(n % 1 !== 0) {
		newString += '<i class="fas fa-star-half-alt" aria-hidden="true"></i>';
		n=i+1;
	}
	for (i = n; i < max; i++) {
		newString += '<i class="far fa-star" aria-hidden="true"></i>';
	}
	newString += '</div>';
	return newString;
}
function loancomparisonCalculate(e) {

	// Change relevent element's output value
	var $				= jQuery,
		form			= $(this).closest(loancomparison_loan_selector),
		rates			= sc_rates,
		sliders		 = form.find(loancomparison_slider_selector),
		p				= sliders.eq(0),
		t				= sliders.eq(1),
		principal		= parseFloat(p.find('input').val()),
		term			= parseFloat(t.find('input').val()),
		logonofollow	= rates.logonofollow ? ' rel="nofollow"' : '',
		logoblank	   = rates.logoblank ? ' target="_blank"' : '',
		buttonnofollow  = rates.buttonnofollow ? ' rel="nofollow"' : '',
		buttonblank	 = rates.buttonblank ?' target="_blank"' : '',
		unit		   = {'y':rates.yearlabel,'m':rates.monthlabel,'d':rates.daylabel},
		units		   = {'y':rates.yearslabel,'m':rates.monthslabel,'d':rates.dayslabel},
		currencyspace   = rates.currencyspace ? '&nbsp;' : '';
	
	if (rates.showfico) {
		var ficogroup = rates.fico[parseInt($('.fico-option.selected').attr('rel'))];
	}

	// Convert Principal & Term to actual values
	prstep		= principal;
	principal	= rates.steps.principal[principal].amount;
	term_obj	= rates.steps.term[term];
	term_unit	= unit[term_obj.period.toLowerCase()];
	term_units	= units[term_obj.period.toLowerCase()];
	term_value  = term_obj.term;

	rates.cb = rates.ca = '';
	
	if (rates.currency == false) rates.currency = '';
	if (rates.ba == 'before') rates.cb = rates.currency+currencyspace;
	if (rates.ba == 'after') rates.ca = currencyspace+rates.currency;

	// Rates or term changed - Remove all banks
	$('.loancomparison_rates tr').not('.loancomparison_rates_header').remove();

	var filters = {
		'a':$('input#filter1').is(':checked'),
		'b':$('input#filter2').is(':checked'),
		'c':$('input#filter3').is(':checked'),
		'd':$('input#filter4').is(':checked'),
		'e':$('input#filter5').is(':checked'),
		'f':$('input#filter6').is(':checked'),
	};
	
	var filterdrop = form.find('#filters').val();

	var num = 0, bars = [];
	
	// Loop through all banks
	var bank,
		row			= '',
		rc			= 0,
		monthly		= 0, 
		mininterest	= 0, 
		total		= {},
		index		= 0,
		nofollow	= '',
		target	  = '',
		ribbon	  = false,
		ribbonlabel = false,
		addterms	= '';
	
	$('.loancomparison_rates').empty();
	form.find('#lc_show_more').hide();
	
	// Filter Results
	banks = [];
	
	var bankfilters = [],
		checks		= $('.loancomparison_bankfilter').filter(':checked'),
		toshow		= false;

	for (l = 0; l < checks.length; l++) {
		bankfilters.push(checks.eq(l).val());
	}

	for (i in rates.rates) {

		bank = Object.assign({},rates.rates[i]);
		
		if (bank.hide == 'checked') continue;
		
		if (typeof bank.max_loan == 'undefined') continue;
		
		if (!rates.filter_hidden) {
			if ((filters.a || filterdrop == 'filter1' ) && !bank.filter1)  continue;
			if ((filters.b || filterdrop == 'filter2' ) && !bank.filter2)  continue;
			if ((filters.c || filterdrop == 'filter3' ) && !bank.filter3)  continue;
			if ((filters.d || filterdrop == 'filter4' ) && !bank.filter4)  continue;
			if ((filters.e || filterdrop == 'filter5' ) && !bank.filter5)  continue;
			if ((filters.f || filterdrop == 'filter6' ) && !bank.filter6)  continue;
			if ((filters.g || filterdrop == 'filter7' ) && !bank.filter7)  continue;
			if ((filters.h || filterdrop == 'filter8' ) && !bank.filter8)  continue;
			if ((filters.i || filterdrop == 'filter9' ) && !bank.filter9)  continue;
			if ((filters.j || filterdrop == 'filter10') && !bank.filter10) continue;
			if ((filters.k || filterdrop == 'filter11') && !bank.filter11) continue;
			if ((filters.l || filterdrop == 'filter12') && !bank.filter12) continue;
			if ((filters.m || filterdrop == 'filter13') && !bank.filter13) continue;
			if ((filters.n || filterdrop == 'filter14') && !bank.filter14) continue;
			if ((filters.o || filterdrop == 'filter15') && !bank.filter15) continue;
		}
		
		if (rates.showbankfilters && checks.length) {
			toshow = false;

			for (c in bankfilters) {
				if (bankfilters[c] == bank.alt) toshow = true;
			}

			if (!toshow) continue;
			toshow = false;
		}
		
		if (!rates.term_slider_hidden) {
			if (bank.min_term > term) continue;
			if (bank.max_term < term) continue;
		}
		
		if (!rates.loan_slider_hidden) {
			if (parseInt(bank.max_loan.removeSpaces()) < principal) continue;
			if (parseInt(bank.min_loan.removeSpaces()) > principal) continue;
		}
		
		if (sc_rates.zeropercent && sc_rates.zeropercent >= principal) {
			bank.mininterest = bank.maxinterest = 0;
		}

		if (rates.interest == 'simple') {
			bank.total = loancomparison_simple(term_obj, principal, rates, bank);
		} else {
			bank.total = loancomparison_amortisation(term_obj, principal, rates, bank);
		}
		
		if (rates.showfico && parseInt(ficogroup.min) < parseInt(bank.minimum_fico)) continue;

		banks.push(bank);
		
	}
	
	// Sort
	if (rates.showsorting) {
		var sort	= form.find('#sortby').val();
		
		banks.sort(function(a,b) {
			switch(sort) {
				case 'rating':
					return parseFloat(b.rating) - parseFloat(a.rating);
				break;
				case 'interest':
					return parseFloat(a.mininterest) - parseFloat(b.mininterest);
				break;
				case 'loanamount':
					return parseFloat(a.min_loan) - parseFloat(b.min_loan);
				break;
				case 'fees':
					return parseFloat(a.startupfee) - parseFloat(b.startupfee);
				break;
				case 'total':
					return parseFloat(a.total.total) - parseFloat(b.total.total);
				break;
				case 'repayment':
					return parseFloat(a.total.repayment) - parseFloat(b.total.repayment);
				break;
				case 'bankname':
					if(a.alt < b.alt) return -1;
					if(a.alt > b.alt) return 1;
					return 0;
				break;
			}
		});
	}
	
	// Output principal
	p.find('output').html(rates.cb+principal.toString().loancomparison_separator(rates)+rates.ca);
	form.find('.loan-amount').html(rates.cb+principal.toString().loancomparison_separator(rates)+rates.ca);
	
	
	// Output term
	
	if (term_unit == false || term_units == false) {
		var termname = '';
	} else {
		if (term_units) var termname = term_value > 1 ? term_units : term_unit;
		else var termname = term_unit+((term_value > 1) ? 's' : '');
	}

	t.find('output').text(term_value+' '+termname);
	form.find('.loan-term').text(term_value+' '+termname);

	var numbering = '';
	if (rates.shownumbering) {
		numbering = 'class="loancomparison-numbering"';
		if (rates.roundnumbering) {
			numbering = 'class="loancomparison-numbering-circle"';
		}
	}
	
	// Loop through banks
	for (i in banks) {
		
		bank = banks[i];
		
		extrastyle = 'display: block;';
		if (index >= rates.numbertoshow) extrastyle = 'display: none;';
		
		if (!bank.logolink.length || 0 === bank.logolink.length ) bank.logolink = bank.link;
		
		sponsored = bank.sponsored ? ' rel="sponsored"' : '';

		// Log that a new row is outputting
		
		if (rates.showribbon) {
			ribbon = false
			if (bank.ribbon1) {ribbon = rates.ribbonlabel1;ribbonclass='ribbon1';}
			if (bank.ribbon2) {ribbon = rates.ribbonlabel2;ribbonclass='ribbon2';}
			if (bank.ribbon3) {ribbon = rates.ribbonlabel3;ribbonclass='ribbon3';}
			if (bank.ribbon4) {ribbon = rates.ribbonlabel4;ribbonclass='ribbon4';}
			if (bank.ribbon5) {ribbon = rates.ribbonlabel5;ribbonclass='ribbon5';}
			if (bank.ribbon6) {ribbon = rates.ribbonlabel6;ribbonclass='ribbon6';}
		}  
		
		if (rates.showbankname && rates.showribbonlabel) {
			ribbonlabel = false
			if (bank.ribbon1) {ribbonlabel = rates.ribbonlabel1;ribbonlabelclass='ribbonlabel1';}
			if (bank.ribbon2) {ribbonlabel = rates.ribbonlabel2;ribbonlabelclass='ribbonlabel2';}
			if (bank.ribbon3) {ribbonlabel = rates.ribbonlabel3;ribbonlabelclass='ribbonlabel3';}
			if (bank.ribbon4) {ribbonlabel = rates.ribbonlabel4;ribbonlabelclass='ribbonlabel4';}
			if (bank.ribbon5) {ribbonlabel = rates.ribbonlabel5;ribbonlabelclass='ribbonlabel5';}
			if (bank.ribbon6) {ribbonlabel = rates.ribbonlabel6;ribbonlabelclass='ribbonlabel6';}
		} 
		
		addterms = rates.addterms ? '?amount='+rates.cb+principal+'&term='+term_value+'%20'+termname+'&bank='+bank.alt : '';
		//addterms = addterms.replace(" ", "%20");
		
		term_min	= rates.steps.term[bank.min_term];
		term_unit	= unit[term_min.period.toLowerCase()];
		term_units	= units[term_min.period.toLowerCase()];
		term_min	= term_min.term;
		
		if (term_unit == false || term_units == false) {
			var termmin = '';
		} else {
			if (term_units) var termmin = term_min > 1 ? ' '+term_units : ' '+term_unit;
			else var termmin = term_unit+((term_min > 1) ? 's' : '');
		}
		
		term_max	= rates.steps.term[bank.max_term];
		term_unit	= unit[term_max.period.toLowerCase()];
		term_units	= units[term_max.period.toLowerCase()];
		term_max	= term_max.term;
		
		if (term_unit == false || term_units == false) {
			var termmax = '';
		} else {
			if (term_units) var termmax = term_max > 1 ? ' '+term_units : ' '+term_unit;
			else var termmax = term_unit+((term_max > 1) ? 's' : '');
		}
		
		if (termmin == termmax) termmin = '';

		index++;
		
		// Lets build a row
		total = bank.total;

		if (rates.alternate) row = '<div class="bank_box'+((rc % 2)? ' bank_offset':'')+'" style="'+extrastyle+'">';
		else row = '<div class="bank_box" style="'+extrastyle+'">';
		
		if (rates.showbankname) {
			row += '<div class="bank_name">'+bank.alt;
			if (rates.showribbonlabel && ribbonlabel) row += '<span class="ribbonlabel '+ribbonlabelclass+'">'+ribbonlabel+'</span>';
			row += '</div>';
		}
		row += '<div class="colmd8 bank_logo"><a href="'+bank.logolink+'"'+sponsored+logonofollow+logoblank+'><img src="'+bank.logo+'" alt="'+bank.alt+'" /></a></div>';
		if (ribbon) row += '<div class="loancomparison-ribbon"><span class="'+ribbonclass+'">'+ribbon+'</span></div>';
		if (rates.shownumbering) row += '<div '+numbering+'>'+(rc+1)+'</div>';
		row += '<div class="colmd8 hiderating">';
		if (sc_rates.showrating && !sc_rates.sortby_rating) row += loancomparison_repeat(bank.rating,rates.maxrating);
		row += '</div>';
		
		if (!rates.authorized) {
			
			row += '<div class="bank_details"><span>'+sc_rates.interestlabel+'</span><br><b>'+bank.mininterest+'%';
			if (bank.maxinterest) row += ' - '+bank.maxinterest+'%';
			row += '</b></div>';
			
			if (sc_rates.showfees) row += '<div class="bank_details"><span>'+sc_rates.feeslabel+'</span><br><b>'+rates.cb+bank.startupfee+rates.ca+'</b></div>';
			else row += '<div class="bank_details"><span>'+sc_rates.loanlabel+'</span><br><b>'+rates.cb+bank.min_loan.loancomparison_separator(rates)+rates.ca+'</b></div>';
			
			if (sc_rates.showterm) row += '<div class="bank_details"><span>'+sc_rates.termlabel+'</span><br><b>'+term_min+' '+termmin+' '+rates.termseparator+' '+term_max+' '+termmax+'</b></div>';
			else row += '<div class="bank_details"><span>'+sc_rates.repaymentlabel+'</span><br><b>'+rates.cb+loancomparison_doubledigit(total.repayment,rates).loancomparison_separator(rates)+rates.ca+'</b></div>';
			
			row += '<div class="bank_details"><span>'+sc_rates.totallabel+'</span><br><b>'+rates.cb+loancomparison_doubledigit(total.total,rates).loancomparison_rounding(rates)+rates.ca+'</b></div>';
			
		} else {
			
			for (i in rates.columnorder) {
				sorted = rates.columnorder[i];
				if (!sorted.checked) continue;
				
				switch (sorted.name) {
					
					case 'rating':
						row += '<div class="bank_details"><span>'+sc_rates.ratinglabel+'</span><br><b>'+loancomparison_repeat(bank.rating,rates.maxrating)+'</b></div>';
					break;
					
					case 'total':
						row += '<div class="bank_details"><span>'+sc_rates.totallabel+'</span><br><b>'+rates.cb+loancomparison_doubledigit(total.total,rates).loancomparison_rounding(rates)+rates.ca;
						if (sc_rates.showlimits) row += ' '+rates.termseparator+' '+rates.cb+loancomparison_doubledigit(total.maxtotal,rates).loancomparison_rounding(rates)+rates.ca;
						row += '</b></div>';
					break;
					
					case 'bankname':
						row += '<div class="bank_details"><span>'+sc_rates.banknamelabel+'</span><br><b>'+bank.alt+'</b></div>';
					break;
					
					case 'interest':
						row += '<div class="bank_details"><span>'+sc_rates.interestlabel+'</span><br><b>'+bank.mininterest+'%';
						if (bank.maxinterest) row += ' '+rates.termseparator+' '+bank.maxinterest+'%';
						row += '</b></div>';
					break;
					
					case 'term':
						row += '<div class="bank_details"><span>'+sc_rates.termlabel+'</span><br><b>'+term_min+termmin+' '+rates.termseparator+' '+term_max+termmax+'</b></div>';
					break;
						
					case 'loan':
						row += '<div class="bank_details"><span>'+sc_rates.loanslabel+'</span><br><b>'+rates.cb+bank.min_loan.loancomparison_separator(rates)+rates.ca+' '+rates.termseparator+' '+rates.cb+bank.max_loan.loancomparison_separator(rates)+rates.ca+'</b></div>';
					break;
					
					case 'loanamount':
						row += '<div class="bank_details"><span>'+sc_rates.loanlabel+'</span><br><b>'+rates.cb+bank.min_loan.loancomparison_separator(rates)+rates.ca+'</b></div>';
					break;
					
					case 'repayment':
						row += '<div class="bank_details"><span>'+sc_rates.repaymentlabel+'</span><br><b>'+rates.cb+loancomparison_doubledigit(total.repayment,rates).loancomparison_rounding(rates)+rates.ca;
						if (sc_rates.showlimits) row += ' '+rates.termseparator+' '+rates.cb+loancomparison_doubledigit(total.maxrepayment,rates).loancomparison_rounding(rates)+rates.ca;
						row +='</b></div>';
					break;
						
					case 'interestamount':
						row += '<div class="bank_details"><span>'+sc_rates.interestamountlabel+'</span><br><b>'+rates.cb+loancomparison_doubledigit(total.mininterestamount,rates).loancomparison_rounding(rates)+rates.ca;
						if (sc_rates.showlimits) row += ' '+rates.termseparator+' '+rates.cb+loancomparison_doubledigit(total.maxinterestamount,rates).loancomparison_rounding(rates)+rates.ca;
						row +='</b></div>';
					break;
					
					case 'fees':
						row += '<div class="bank_details"><span>'+sc_rates.feeslabel+'</span><br><b>'+rates.cb+loancomparison_doubledigit(total.fees,rates).loancomparison_rounding(rates)+rates.ca+'</b></div>';
					break;
						
					case 'infolink':
						row += '<div class="bank_details"><span>'+sc_rates.infolinklabel+'</span><br><b><a href="'+bank.infolink+'">'+rates.infolinkanchor+'<a></b></div>';
					break;
						
					case 'loancost':
						row += '<div class="bank_details"><span>'+sc_rates.loancostlabel+'</span><br><b>'+rates.cb+loancomparison_doubledigit(total.mininterest,rates).loancomparison_rounding(rates)+rates.ca;
						if (sc_rates.showlimits) row += ' '+rates.termseparator+' '+rates.cb+loancomparison_doubledigit(total.maxinterest,rates).loancomparison_rounding(rates)+rates.ca;
						row +='</b></div>';
					break;
						
					case 'otherinfo':
						row += '<div class="bank_details"><span>'+sc_rates.otherinfolabel+'</span><br><b>'+bank.otherinfo+'</b></div>';
					break;
						
					case 'creditscore':
						row += '<div class="bank_details"><span>'+sc_rates.creditscorelabel+'</span><br><b>'+bank.minimum_fico+'</b></div>';
					break;  
					
				}
			}
		}
		
		if (sc_rates.showchecks) row += '<div class="colmd4 checklist">'+loancomparison_format_checks(bank,sc_rates)+'</div>';
		
		if (!sc_rates.singlebank) {
			row += '<div class="colmd4"><div class="bank_apply">';
			if (bank.link && !bank.blocklink) row += '<a href="'+bank.link+addterms+'"'+sponsored+buttonnofollow+buttonblank+'>'+sc_rates.applylabel+'</a>';
			else row += '<span>'+sc_rates.applylabel+'</span>';
			row += '</div>';
			if (bank.sponsored) row+= '<div class="bank_sponsored">'+rates.sponsoredlabel+'</div>';
			row += '</div>';
		}
		
		row += '<div style="clear:both"></div><div class="colmd8 showrating">';
		if (sc_rates.showrating && !sc_rates.sortby_rating) row += loancomparison_repeat(bank.rating,rates.maxrating);
		row += '</div>';
		
		row += '<div class="colmd5 showchecks">';
		if (sc_rates.showchecks) row += loancomparison_format_checks(bank,sc_rates);
		row += '</div>';
		
		row += '<div style="clear:both"></div>';
		
		if (rates.showexample && rates.examplelocation == 'grid') row += '<div class="colmd6 showexample">'+bank.example+'</div><div style="clear:both"></div>';
		
		var mic = loancomparison_more(sc_rates,bank);
		
		var drop = sc_rates.showmoreinfo ? '<a href="javascript:void(0);" class="readmore"><i class="fa fa-chevron-down read-more-toggle-indicator"></i> '+sc_rates.moreinfo+'</a> ' : false;
		
		var reviewbank = sc_rates.reviewbank ? ' '+bank.alt : '';
		var reviewtarget = sc_rates.reviewtarget ? ' target="_blank"' : '';
		
		var review = sc_rates.showreviewlink ? '<a class="review" href="'+bank.logolink+'"'+reviewtarget+'>'+rates.reviewlabel+reviewbank+' <i class="fas fa-chevron-right"></i></a>' : '';

		if ( (drop && mic) || review) {

			row += '<!-- START Desktop View -->';
			row += '<div class="extra">';
			row += '<div class="infogrid">';
			row += '<div class="colmd6"><div class="bank_inside">'+drop+review+'</div>';
			row += '</div>';
			row += '</div>';
			row += '<div class="drop">'+mic+'</div>';
			row += '</div>';
			row += '<!-- END Desktop View -->';
		}
			
		row += '</div>';
		
		bars.push({'id':'bank_'+num,'link':bank.logolink,'image':bank.logo,'display':(rates.cb+(loancomparison_doubledigit(total.repayment,rates))+rates.ca).trim(),'value':total.repayment});
		
		if (sc_rates.barchartenabled) $('#bargraph').bargraph('setbars',bars);

		$('.loancomparison_rates').append(row);
		
		num++;
		
		rc++;
	}
	
	if (index > rates.numbertoshow) form.find('#lc_show_more').show();
	
	// Show/Hide Filters
	if (num == 0) {
		form.find('.offers').hide();
		form.find('.one-offer').hide();
		form.find('.no-offers').show();
		form.find('.loancomparison_rates_header').hide();
		if (!filters.a && !filters.b && !filters.c && !filters.d && !filters.e) form.find('.filterlabel').hide();
		form.find('.sorting').hide();
	} else if (num == 1) {
		form.find('.no-offers').hide();
		form.find('.one-offer').show();
		form.find('.offers').hide();
		form.find('.loancomparison_rates_header').show();
		if (!filters.a && !filters.b && !filters.c && !filters.d && !filters.e) form.find('.filterlabel').hide();
		form.find('.sorting').hide();
	} else {
		form.find('.no-offers').hide();
		form.find('.one-offer').hide();
		form.find('.offers').show();
		form.find('.loancomparison_rates_header').show();
		form.find('.filterlabel').show();
		form.find('.sorting').show();
	}
	
	form.find('.numberofoffers').text(num);

	// Set Rates Page URL 
	var rp = form.find('#ratespage');
	var filterlist = '';
	
	if (rates.filterquery) var filterlist = '&filters='+rates.filterquery;
	
	rp.attr('href',rp.attr('rel')+'?amount='+prstep+'&term='+term+filterlist);
	
	// Add functionality to the More Info Buttons
	form.find('.readmore').click(function() {
		var b = $(this).closest('.bank_box'), r = b.find('.readmore'), d = b.find('.drop');
		if (b.find('.drop').is(':visible')) {
			d.slideUp('slow');
			r.find('i').removeClass('fa-chevron-down fa-chevron-up').addClass('fa-chevron-down');
		} else {
			d.slideDown('slow');
			r.find('i').removeClass('fa-chevron-down fa-chevron-up').addClass('fa-chevron-up');
		}
	});
}

function loancomparison_amortisation(term, principal, rates, bank) {
	var P	= principal;
	var T	= term.term;
	var A	= bank.mininterest;
	var B   = bank.maxinterest;
	if (rates.napr) {
		var J	= A * .01 / 12;
		var K	= B * .01 / 12;
	} else {
		var J	= Math.pow(1 + A * .01,1/12) -1;
		var K	= Math.pow(1 + B * .01,1/12) -1;
	}

	var FF  = 0;
	var PF  = 0;
	var Q	= T;
	var U   = T / 12;
	var V   = 0;
	var G   = 0;
	
	if (term.period.toLowerCase() == 'd') {J = A * .01/365;K = B * .01/365;}
	if (term.period.toLowerCase() == 'y') {Q = T * 12; U = T;}

	if (bank.startupfee) FF  = parseFloat(bank.startupfee);
	if (bank.percentfee) PF  = parseFloat(bank.percentfee) * P * 0.01;
	if (bank.annualfixed) FF = FF * U;
	if (bank.annualpercent) PF = PF * U;
	var F = FF + PF;
	if (rates.minfee) F = PF;
	if (rates.minfee && (PF < FF)) F = FF;
	
	if (rates.addfees && rates.whenfees == 'beforeinterest') P = P + F;
	if (rates.addfees && rates.whenfees == 'afterinterest') G = F;
	if (rates.savings) V = F;

	var M 	= ((J == 0)? P / Q:(J * P) / (1 - Math.pow(1 + J,-Q)));
	var N 	= ((K == 0)? P / Q:(K * P) / (1 - Math.pow(1 + K,-Q)));
	var Imin= M * Q - P;
	var Imax= N * Q - P;
	var R	= M * Q - V + G;
	var S	= N * Q - V + G;
	
	returning = {'repayment':M,'total':R,mininterest: (R - P),maxinterest: (S - P),'maxrepayment':N,'maxtotal':S,maxinterest: (S - P),fees: F,mininterestamount: Imin,maxinterestamount: Imax };
	return returning;
}

function loancomparison_simple(term, principal, rates, bank) {
	var P	= principal;
	var T	= term.term;
	var A	= bank.mininterest;
	var B   = bank.maxinterest;
	if (rates.napr) {
		var J	= A * .01 / 12;
		var K	= B * .01 / 12;
	} else {
		var J	= Math.pow(1 + A * .01,1/12) -1;
		var K	= Math.pow(1 + B * .01,1/12) -1;
	}
	var FF  = 0;
	var PF  = 0;
	var Q	= T;
	var U   = T / 12;
	var V   = 0;
	var G   = 0;
	
	if (term.period.toLowerCase() == 'd') {J = A * .01/365;K = B * .01/365;}
	if (term.period.toLowerCase() == 'y') {Q = T * 12; U = T;}

	if (bank.startupfee) FF  = parseFloat(bank.startupfee);
	if (bank.percentfee) PF  = parseFloat(bank.percentfee) * P * 0.01;
	if (bank.annualfixed) FF = FF * U;
	if (bank.annualpercent) PF = PF * U;
	var F = FF + PF;
	if (rates.minfee) F = PF;
	if (rates.minfee && (PF < FF)) F = FF;
	
	if (rates.addfees && rates.whenfees == 'beforeinterest') P = P + F;
	if (rates.addfees && rates.whenfees == 'afterinterest') G = F;
	if (rates.savings) V = F;
	
	var Imin= J * P * Q;
	var Imax= K * P * Q;
	
	var R	= P + Imin;
	var S	= P + Imax;
	
	var M 	= R / Q;
	var N 	= S / Q;
	
	
	returning = {'repayment':M,'total':R,mininterest: (R - P),maxinterest: (S - P),'maxrepayment':N,'maxtotal':S,maxinterest: (S - P),fees: F,mininterestamount: Imin,maxinterestamount: Imax };
	return returning;
}

function loancomparison_amortization(term, principal, rates, bank) {
	var P	= principal;
	var T	= term.term;
	var V   = 1;
	var F   = 0;
	if (rates.addfees) F = parseFloat(bank.startupfee);
	var Q	= T;
	if (term.period.toLowerCase() == 'd') {V = T/31;Q = T;}
	if (term.period.toLowerCase() == 'y') {V = 12;  Q = T * 12}
	var A	= bank.mininterest;
	var J	= A * .01 / V;
	var M 	= (J * P) / (1 - Math.pow(1 + J,-Q));
	var R	= M * Q + F;
	var Imin= M * Q;
	
	returning = {'minrepayment':M,'total':R,mininterest:(returning.total - P),mininterestamount: Imin};
	return returning;
}

function loancomparison_format_checks(bank,rates) {
	var name = '', check = '', details = "<ul class='checks'>";
	
	for (var i = 1; i <= 6; i++) {
		if (bank['check'+i])  {
			check = rates.useinfoboxes ? bank['info'+i] : rates['check'+i];
			details += '<li><i class="fas fa-check" aria-hidden="true"></i> '+check+'</li>';
		}
	}
	details += '</ul>';
	
	return details;
}

function loancomparison_more(rates,bank) {
	var row = '';
	if (rates.showexample && rates.examplelocation == 'drop') row += '<div class="colmd6 showexample">'+bank.example+'</div>';
	if (bank.info1) row += "<div class='colmd3'><h6>"+rates.info1label+"</h6><div class='colsmindent'>"+bank.info1+"</div></div>";
	if (bank.info2) row += "<div class='colmd3'><h6>"+rates.info2label+"</h6><div class='colsmindent'>"+bank.info2+"</div></div>";
	if (bank.info3) row += "<div class='colmd3'><h6>"+rates.info3label+"</h6><div class='colsmindent'>"+bank.info3+"</div></div>";
	if (bank.info4) row += "<div class='colmd3'><h6>"+rates.info4label+"</h6><div class='colsmindent'>"+bank.info4+"</div></div>";
	if (row) row = "<div class='infogrid'>"+row+"</div>";
	return row;
}

function loancomparison_doubledigit(num,rates) {
	
	if (rates.decimals == 'non') return Math.round(num).toString();
	var n = num.toFixed(2);
	if (rates.decimals == 'float') return n.replace('.00','');
	return n;
}

function loancomparison_reformat_rates(e) {
	var banks = Object.assign({},sc_rates.rates), bars = [];

	for (i in banks) {
		bars.push({'id':'bank_'+i,'image':banks[i].logo,'value':Math.random() * 100});
	}
	
	jQuery('#bargraph').bargraph({
		'bars':bars,
		'orientation':'width',
		'padding':3,
		'border-radius':0,
		'border-width':2,
		'bar-background':'#369e71',
		'bar-background-hover':'#AAA',
		'bar-border':'#e6e6e6',
		'bar-border-hover':'#BBB',
		'order':((sc_rates.barchartorder)? 'desc':'asc')
	});
}

var loancomparison__starting = {};

jQuery(document).ready(function($) {

	/* Select all relevant loan slider forms */
	$(loancomparison_loan_selector).each(function() {
		
		/* Initialize sliders */
		var sliders = $(this).find('[data-loancomparison]'), x = $(this);

		if (sc_rates.barchartenabled) loancomparison_reformat_rates($(this));

		sliders.change(loancomparisonCalculate);
		sliders.loancomparison({polyfill:false});
		x.find('#lc_show_more .fg').click(loancomparisonShowMore);
		x.find('#sortby').change(loancomparisonCalculate);
		
		loancomparison__starting = {'amount':sliders.eq(0).val(), 'term':sliders.eq(1).val()};
		
		x.find('.fico-option').click(function() {
			$(this).closest('.loancomparison-fico').find('.fico-option').removeClass('selected');
			$(this).addClass('selected');
			
			sliders.change();
		});
		
		// click the first fico option selected, to start the process
		x.find('.selected-start').click();
		
		sliders.change();
		
		x.find('.circle-control').click(function() {
			var holder	= $(this).closest('.loancomparison-range'),
				range	= holder.find('input'),
				newVal	= parseFloat(range.val())
				step	= parseFloat(range.attr('step')),
				min		= parseFloat(range.attr('min')),
				max		= parseFloat(range.attr('max'));
			
			if ($(this).hasClass('circle-down')) { // reduce the slider value
				newVal = newVal - step;
				if (newVal > min) range.val(newVal);
				else range.val(min);
			} else { // raise the slider value
				newVal = newVal + step;
				if (newVal < max) range.val(newVal);
				else range.val(max);
			}
			
			range.change();
		});
		
		$(this).find('.loancomparison_filter').change(function() {
			var obj = $(this),
				rates = sc_rates;
			
			if (rates.filtertype == 'radio') {
				$(this).closest('.loancomparison-filterlabel').find('input').not(this).attr('checked',false)
			}
			
			sliders.change();
			
		});

		$(this).find('.loancomparison_bankfilter').change(function() {
			sliders.change();
		});
		$(this).find('#filters').change(function() {
			sliders.change();
		});
		

		
		if (sc_rates.ratespage == true) {
			
			var p		= sliders.eq(0),
			t			= sliders.eq(1);

			p.val(parseInt(sc_rates.getamt) || sc_rates.loaninitial);
			t.val(parseInt(sc_rates.gettrm) || sc_rates.periodinitial);
			
			p.change();
			
		}
		
		
		
	});
});

String.prototype.addSpaces = function() {
	var str = this.split('.');
	if (str[0].length >= 4) {
		str[0] = str[0].replace(/(\d)(?=(\d{3})+$)/g, '$1 ');
	}
	return str.join('.');
}
String.prototype.removeSpaces = function() {
	return this.replace(/ /g,'');
}

String.prototype.loancomparison_separator = function(rates) {
	
	var sr = rates.separator;
	
	if (sr == 'none') return this;
	else if (sr == 'apostrophe')  var s = "'";
	else if (sr == 'dot')  var s = ".";
	else if (sr == 'comma')  var s = ",";
	else var s = ' ';
	var str = this.split('.');
	if (str[0].length >= 4) {
		str[0] = str[0].replace(/(\d)(?=(\d{3})+$)/g, '$1'+s);
	}
	if (sr == 'dot' || rates.decimalcomma) var decimalsdevider = ',';
	else var decimalsdevider = '.';
	return str.join(decimalsdevider);
}

String.prototype.loancomparison_rounding = function(rates) {
	
	var rr = rates.rounding;
	var r = 1;

	if (rr == 'tenround')  var r = 10;
	if (rr == 'hundredround') var r = 100;
	if (rr == 'thousandround') var r = 1000;
	
	if (rr == 'noround') var num = this;
	else var num = Math.round(this / r) * r;
	
	var rs = rates.separator;
	
	if (rs == 'none') return num;
	if (rs == 'apostrophe')  var s = "'";
	else if (rs == 'dot')  var s = ".";
	else if (rs == 'comma')  var s = ",";
	else var s = ' ';
	var str = num.toString().split('.');
	if (str[0].length >= 4) {
		str[0] = str[0].replace(/(\d)(?=(\d{3})+$)/g, '$1'+s);
	}
	if (rs == 'dot' || rates.decimalcomma) var decimalsdevider = ',';
	else var decimalsdevider = '.';
	return str.join(decimalsdevider);
}