var hcapp = {};

function hc2_get_scripts( scripts, callback )
{
	var progress = 0;
	scripts.forEach( function(script){ 
		jQuery.getScript( script, function(){
			if( ++progress == scripts.length ) callback();
		});
	});
}

function hc2_try_parse_json( jsonString )
{
	try {
		var o = JSON.parse( jsonString );
		/* 
		 * Handle non-exception-throwing cases:
		 * Neither JSON.parse(false) or JSON.parse(1234) throw errors, hence the type-checking,
		 * but... JSON.parse(null) returns 'null', and typeof null === "object", 
		 * so we must check for that, too.
		 */ 
		if ( o && typeof o === "object" && o !== null ) {
			return o;
		}
	}
	catch ( e ) { }
	return false;
}

jQuery(document).on( 'click', '.hcj2-target ul.hcj2-dropdown-menu', function(e)
{
	e.stopPropagation();
//	e.preventDefault();
});

function hc2_set_loader( $el )
{
	var loader = '<div class="hc-loader"></div';
	var shader = '<div class="hc-loader-shader"></div';
	$el.css('position', 'relative'); 
	$el.append( shader );
	$el.append( loader );
}

function hc2_unset_loader( $el )
{
	$el.find('[class="hc-loader-shader"]').remove();
	$el.find('[class="hc-loader"]').remove();
}

jQuery(document).on( 'click', '.hcj2-confirm', function(event)
{
	if( window.confirm("Are you sure?") ){
		return true;
	}
	else {
		event.preventDefault();
		event.stopPropagation();
		return false;
	}
});

jQuery(document).on( 'submit', '.hcj2-alert-dismisser', function(e)
{
	jQuery(this).closest('.hcj2-alert').hide();
	return false;
});

jQuery(document).on( 'click', '.hcj2-as-label', function(e)
{
	jQuery(this).closest('.hcj2-as-label-container').find(':checkbox,:radio').each( function(){
		if( ! jQuery(this).attr('disabled') ){
			jQuery(this)[0].checked = ! jQuery(this)[0].checked;
			jQuery(this).trigger('change');
		}
	});
});

jQuery(document).on( 'click', '.hcj2-action-setter', function(event)
{
	var thisForm = jQuery(this).closest('form');
	var actionFieldName = 'action';
	var actionValue = jQuery(this).attr('name');

	thisForm.find("input[name='" + actionFieldName + "']").each( function(){
		jQuery(this).val( actionValue );
	});
});

/*
this displays more info divs for radio choices
*/
jQuery(document).on( 'change', '.hcj2-radio-more-info', function(event)
{
	// jQuery('.hcj2-radio-info').hide();
	var total_container = jQuery( this ).closest('.hcj2-radio-info-container');
	total_container.find('.hcj2-radio-info').hide();

	var my_container = jQuery( this ).closest('label');
	var my_info = my_container.find('.hcj2-radio-info');
	my_info.show();
});

/* toggle */
jQuery(document).on('click', '.hcj2-toggle', function(e)
{
	var this_target_id = jQuery(this).data('target');
	if( this_target_id.length > 0 ){
		this_target = jQuery(this_target_id);
		if( this_target.is(':visible') ){
			this_target.hide();
		}
		else {
			this_target.show();
		}
	}
	return false;
});

/* collapse next */
jQuery(document).on('click', '.hcj2-collapse-next', function(e)
{
	var this_target = jQuery(this).closest('.hcj2-collapse-container').children('.hcj2-collapse');

	if( this_target.is(':visible') ){
		this_target.hide();
		this_target.removeClass('hcj-open');
		jQuery(this).trigger({
			type: 'hidden.hc.collapse'
		});
	}
	else {
		this_target.show();
		this_target.addClass('hcj-open');
		jQuery(this).trigger({
			type: 'shown.hc.collapse'
		});

		if( jQuery(this).hasClass('hcj2-collapser-hide')){
			// jQuery(this).closest('li').hide();
			jQuery(this).hide();
		}
	}
//	this_target.collapse('toggle');

	if( jQuery(this).attr('type') != 'checkbox' ){
		/* scroll into view */
//		var this_parent = jQuery(this).parents('.collapse-panel');
//		this_parent[0].scrollIntoView();
		return false;
	}
	else {
		return true;
	}
});

/* collapse other */
jQuery(document).on('click', '.hcj2-collapser', function(e)
{
	// var targetUrl = jQuery(this).attr('href');
	var targetUrl = jQuery(this).data('target');
	if(
		( targetUrl.length > 0 ) &&
		( targetUrl.charAt(targetUrl.length-1) == '#' )
		){
		return false;
	}

	if( targetUrl.charAt(0) != '#' ){
		targetUrl = '#' + targetUrl
	}

	var this_target = jQuery(targetUrl);

	if( this_target.is(':visible') ){
		this_target.hide();
		this_target.removeClass('hcj-open');
		jQuery(this).trigger({
			type: 'hidden.hc.collapse'
		});
	}
	else {
		this_target.show();
		this_target.addClass('hcj-open');
		jQuery(this).trigger({
			type: 'shown.hc.collapse'
		});
	}
//	this_target.collapse('toggle');
	if( jQuery(this).attr('type') != 'checkbox' ){
		return false;
	}
	else {
		return true;
	}
});

/* collapse other */
jQuery(document).on('click', '.hcj2-collapse-closer', function(e)
{
	var this_target = jQuery(this).closest('.hcj2-collapse');

	if( this_target.is(':visible') ){
		this_target.hide();
		this_target.removeClass('in');
		jQuery(this).trigger({
			type: 'hidden.hc.collapse'
		});
	}
	else {
		this_target.show();
		this_target.addClass('in');
		jQuery(this).trigger({
			type: 'shown.hc.collapse'
		});
	}

	if( jQuery(this).attr('type') != 'checkbox' ){
		return false;
	}
	else {
		return true;
	}
});

jQuery(document).on('click', '.hcj2-dropdown-menu select', function()
{
	return false;
});

jQuery(document).on( 'click', 'a.hcj2-toggler', function(event)
{
	jQuery('.hcj2-toggled').toggle();
	return false;
});

jQuery(document).on( 'click', '.hcj2-all-checker', function(event)
{
	var thisLink = jQuery( this );
	var firstFound = false;
	var whatSet = true;

	var moreCollect = thisLink.data('collect');
	if( ! moreCollect ){
		moreCollect = 'id';
	}
	if( moreCollect ){
		var myParent = thisLink.closest('.hcj2-collector-wrap');
		if( ! myParent.length ){
			myParent = thisLink.closest('form');
		}

		if( myParent.length > 0 ){
			myParent.first();
		}
		else {
			myParent = jQuery('#nts');
		}

		var what_find = "input[name='" + moreCollect + "']";
		myParent.find(what_find).each( function()
		{
			if( 
				( jQuery(this).attr('type') == 'checkbox' )
				){
				if( ! firstFound ){
					whatSet = ! this.checked;
					firstFound = true;
				}
				// this.checked = whatSet;
				jQuery(this)
					.prop("checked", whatSet)
					.change()
					;
			}
		});
	}

	if(
		( thisLink.prop('tagName').toLowerCase() == 'input' ) &&
		( thisLink.attr('type').toLowerCase() == 'checkbox' )
		){
		return true;
	}
	else {
		return false;
	}
});

/* color picker */
jQuery(document).on('click', 'a.hcj2-color-picker-selector', function(event)
{
	var my_value = jQuery(this).data('color');

	var my_form = jQuery(this).closest('.hcj2-color-picker');
	my_form.find('.hcj2-color-picker-value').val( my_value );
	my_form.find('.hcj2-color-picker-display').css('background-color', my_value);

	/* close collapse */
	return false;
});

/* icon picker */
jQuery(document).on('click', 'a.hcj2-icon-picker-selector', function(event)
{
	var my_value = jQuery(this).data('icon');

	var my_form = jQuery(this).closest('.hcj2-icon-picker');
	my_form.find('.hcj2-icon-picker-value').val( my_value );
	my_form.find('.hcj2-icon-picker-display').html( jQuery(this).html() );

	/* close collapse */
	return false;
});

/* observe forms */
function hc_observe_input( this_input )
{
	// var my_form = this_input.closest('form');
	var my_form = this_input.closest('.hcj2-observe');

	my_form.find('[data-hc-observe]').each( function(){
		var my_this = jQuery(this);
		var whats = my_this.data('hc-observe').toString().split(' ');

		var my_holder = my_this.closest('.hcj2-input-holder');
		if( my_holder.length ){
			my_holder.hide();
		}
		else {
			my_this.hide();
		}

		for( var ii = 0; ii < whats.length; ii++ ){
			var what_parts = whats[ii].split('=');
			var what_param = what_parts[0];
			var what_value = what_parts[1];
// alert( this_input.attr('name') + 'observe: ' + what_param + ' = ' + what_value + '?' );

			var show_this = false;

			var search_name = what_param;
			if( what_param.substring(0,3) != 'hc-' ){
				search_name = 'hc-' + search_name;
			}
			search_name = search_name.replace(':', '\\:');

			var find_this = '[name="' + search_name + '"]';
			// trigger_input = my_form.find('[name="' + search_name + '"]');
			trigger_input = my_form.find(find_this);

			// if( ! trigger_input ){
			if( ! trigger_input.length ){
				search_name = search_name + '\[\]';
				var find_this = '[name="' + search_name + '"]';
				trigger_input = my_form.find(find_this);
				if( ! trigger_input.length ){
					continue;
				}
			}

			if( trigger_input.prop('type') == 'select-one' ){
				trigger_val = trigger_input.val();
			}
			else if( trigger_input.prop('type') == 'radio' ){
				// trigger_val = my_form.find('[name=' + search_name + ']:checked').val();
				trigger_val = my_form.find(find_this + ':checked').val();
			}
			else if( trigger_input.prop('type') == 'checkbox' ){
				trigger_val = my_form.find(find_this + ':checked').val();
			}
			else {
				trigger_val = trigger_input.val();
			}

// alert( trigger_input.prop('type') + '=' + trigger_val );
// alert( 'search_name = ' + search_name + ', trigger_val = ' + trigger_val + ', what_val = ' + what_value );

			if( what_value.substr(0,1) == '!' ){
				what_value = what_value.substr(1);
				if( what_value != trigger_val ){
					show_this = true;
				}
			}
			else {
				if( what_value == trigger_val ){
					show_this = true;
				}
				else if( what_value == '*' && trigger_val ){
					alert( trigger_val );
					show_this = true;
				}
			}

			if( show_this ){
				if( my_holder.length ){
					my_holder.show();
					my_this.show();
				}
				else {
					my_this.show();
				}
				break;
			}
		}
		// alert( jQuery(this).data('hc-observe') );
	});
	return false;
}

jQuery(document).on('change', '.hcj2-observe input, select', function(event)
{
	return hc_observe_input( jQuery(this) );
});

function hc2_init_page( where )
{
	if( typeof where !== 'undefined' ){
	}
	else {
		if( jQuery(document.body).find("#nts").length ){
			where = jQuery("#nts");
		}
		else {
			where = jQuery(document.body);
		}
	}

	where.find('.hcj2-observe input, select').each( function(){
		hc_observe_input( jQuery(this) );
	});

	where.find('.hcj2-radio-more-info:checked').each( function(){
		var my_container = jQuery( this ).closest('label');
		var my_info = my_container.find('.hcj2-radio-info');
		my_info.show();
	});

	if( where.find('.hc-datepicker2').length ){
		where.find('.hc-datepicker2').hc_datepicker2({
			})
			.on('changeDate', function(ev)
				{
				var dbDate = 
					ev.date.getFullYear() 
					+ "" + 
					("00" + (ev.date.getMonth()+1) ).substr(-2)
					+ "" + 
					("00" + ev.date.getDate()).substr(-2);

			// remove '_display' from end
				var display_id = jQuery(this).attr('id');
				var display_suffix = '_display';
				var value_id = display_id.substr(0, (display_id.length - display_suffix.length) );

				jQuery(this).closest('form').find('#' + value_id)
					.val(dbDate)
					.trigger('change')
					;
				});
	}
}

window.addEventListener('load', function()
{
	hc2_init_page();

	/* add icon for external links */
	// jQuery('#nts a[target="_blank"]').append( '<i class="fa fa-fw fa-external-link"></i>' );

	jQuery('#nts a[target="_blank"]').each(function(index){
		var my_icon = '<i class="fa fa-fw fa-external-link"></i>';
		var common_link_parent = jQuery(this).closest('.hcj2-common-link-parent');
		if( common_link_parent.length > 0 ){
			// common_link_parent.prepend(my_icon);
		}
		else {
			jQuery(this).append(my_icon);
		}
	});

	/* scroll into view */
	if ( typeof nts_no_scroll !== 'undefined' ){
		// no scroll
	}
	else {
		// document.getElementById("nts").scrollIntoView();	
	}

	/* auto dismiss alerts */
	jQuery('.hcj2-auto-dismiss').delay(4000).slideUp(200, function(){
		// jQuery('.hcj2-auto-dismiss .alert').alert('close');
	});
});

jQuery(document).on( 'keypress', '.hcj2-ajax-form input', function(e){
	if( (e.which && e.which == 13) || (e.keyCode && e.keyCode == 13) ){
		var this_form = jQuery(this).closest('.hcj2-ajax-form');
		this_form.trigger('hc2-submit');
		return false;
	}
	else {
		return true;
	}
});

var hc2 = {};

var hc2_spinner = '<span class="hc-m0 hc-p0 hc-fs5 hc-spin hc-inline-block">&#9788;</span>';
var hc2_absolute_spinner = '<div class="hc-fs5 hc-spin hc-inline-block hc-m0 hc-p0" style="position: absolute; top: 45%;"><span class="hc-m0 hc-p0">&#9788;</span></div>';
var hc2_full_spinner = '<div class="hcj2-full-spinner hc-bg-silver hc-muted-2 hc-align-center" style="z-index: 1000; width: 100%; height: 100%; position: absolute; top: 0; left: 0;">' + hc2_absolute_spinner + '</div>';

jQuery(document).on( 'click', '.hcj2-action-trigger', function(event)
{
	var receiver = jQuery(this).closest('.hcj2-action-receiver');
	receiver.trigger( 'receive', jQuery(this).data() );
	return false;
});


/*
template engine
from https://github.com/jasonmoo/t.js

Simple interpolation: {{=value}}
Scrubbed interpolation: {{%unsafe_value}}
Name-spaced variables: {{=User.address.city}}
If/else blocks: {{value}} <<markup>> {{:value}} <<alternate markup>> {{/value}}
If not blocks: {{!value}} <<markup>> {{/!value}}
Object/Array iteration: {{@object_value}} {{=_key}}:{{=_val}} {{/@object_value}}
*/

(function() {
	var blockregex = /\{\{(([@!]?)(.+?))\}\}(([\s\S]+?)(\{\{:\1\}\}([\s\S]+?))?)\{\{\/\1\}\}/g,
		valregex = /\{\{([=%])(.+?)\}\}/g;

	function Hc2Template(template) {
		this.Hc2Template = template;
	}

	function scrub(val) {
		return new Option(val).innerHTML.replace(/"/g,"&quot;");
	}

	function get_value(vars, key) {
		var parts = key.split('.');
		while (parts.length) {
			if (!(parts[0] in vars)) {
				return false;
			}
			vars = vars[parts.shift()];
		}
		return vars;
	}

	function render(fragment, vars) {
		return fragment
			.replace(blockregex, function(_, __, meta, key, inner, if_true, has_else, if_false) {

				var val = get_value(vars,key), temp = "", i;

				if (!val) {

					// handle if not
					if (meta == '!') {
						return render(inner, vars);
					}
					// check for else
					if (has_else) {
						return render(if_false, vars);
					}

					return "";
				}

				// regular if
				if (!meta) {
					return render(if_true, vars);
				}

				// process array/obj iteration
				if (meta == '@') {
					// store any previous vars
					// reuse existing vars
					_ = vars._key;
					__ = vars._val;
					for (i in val) {
						if (val.hasOwnProperty(i)) {
							vars._key = i;
							vars._val = val[i];
							temp += render(inner, vars);
						}
					}
					vars._key = _;
					vars._val = __;
					return temp;
				}

			})
			.replace(valregex, function(_, meta, key) {
				var val = get_value(vars,key);

				if (val || val === 0) {
					return meta == '%' ? scrub(val) : val;
				}
				return "";
			});
	}

	Hc2Template.prototype.render = function (vars) {
		return render(this.Hc2Template, vars);
	};

	window.Hc2Template = Hc2Template;
})();

function hc2_print_r( thing )
{
	var out = '';
	for( var i in thing ){
		if( typeof thing[i] == 'object' ){
			out += i + ": ";
			for( var j in thing[i] ){
				out += j + ": " + thing[i][j] + ";\n";
			}
			out += "\n";
		}
		else {
			out += i + ": " + thing[i] + "\n";
		}
	}
	alert(out);	
}

// various php functions in javascript taken from locutus.io
function hc2_php_number_format (number, decimals, decPoint, thousandsSep) { // eslint-disable-line camelcase
	number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
	var n = !isFinite(+number) ? 0 : +number
	var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
	var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
	var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
	var s = ''

	var toFixedFix = function (n, prec) {
		var k = Math.pow(10, prec)
		return '' + (Math.round(n * k) / k)
		.toFixed(prec)
	}

	// @todo: for IE parseFloat(0.55).toFixed(0) = 0;
	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.')
	if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
	}
	if ((s[1] || '').length < prec) {
		s[1] = s[1] || ''
		s[1] += new Array(prec - s[1].length + 1).join('0')
	}
	return s.join(dec)
}

jQuery(document).on( 'click', '.hcj2-insert-code', function(e)
{
	return false;
});

jQuery(document).on( 'mousedown', '.hcj2-insert-code', function(e)
{
	var $txt = jQuery('textarea:focus');
	if( $txt.length ){
		var txtToAdd = jQuery(this).html();
		var caretPos = $txt[0].selectionStart;
		var textAreaTxt = $txt.val();
		$txt.val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos) );
	}
	else {
		console.log('Please focus on a textarea to add this code to');
	}
	return false;
});

// html
hcapp.html = {};

hcapp.html.List_Inline = function()
{
	var self = this;

	self.items = [];
	self.gutter = 2;

	this.add = function( item )
	{
		self.items.push( item );
		return this;
	}

	this.set_gutter = function( gutter )
	{
		self.gutter = gutter;
		return this;
	}

	this.render = function()
	{
var debug = false;

		var $out = jQuery('<div>');
		$out
			.addClass('hc-nowrap')
			;

if( debug ){
	$out.addClass('hc-border');
}

		for( var ii = 0; ii < self.items.length; ii++ ){
			var $out_item = jQuery('<div>')
				.addClass('hc-inline-block')
				;

if( debug ){
	$out_item.addClass('hc-border');
}

			if( self.gutter && ii < (self.items.length - 1) ){
				$out_item
					.addClass( 'hc-mr' + self.gutter )
					;
			}

			$out_item
				.append( self.items[ii] )
				;

		$out
			.append( $out_item )
			;
		}
		return $out;
	}
}

hcapp.html.List = function()
{
	var self = this;

	self.items = [];
	self.gutter = 2;

	this.add = function( item )
	{
		self.items.push( item );
		return this;
	}

	this.set_gutter = function( gutter )
	{
		self.gutter = gutter;
		return this;
	}

	this.render = function()
	{
var debug = false;

		var $out = jQuery('<div>');

if( debug ){
	$out.addClass('hc-border');
}

		for( var ii = 0; ii < self.items.length; ii++ ){
			var $out_item = jQuery('<div>')
				.addClass('hc-block')
				;

if( debug ){
	$out_item.addClass('hc-border');
}

			if( self.gutter && ii ){
				$out_item
					.addClass( 'hc-mt' + self.gutter )
					;
			}

			$out_item
				.append( self.items[ii] )
				;

		$out
			.append( $out_item )
			;
		}
		return $out;
	}
}

hcapp.html.Month_Calendar = function()
{
	var self = this;
	self.dates = [];
	self.lang = [];

	self._selected_date = null;
	self._cells = {};

	var $this = jQuery({});
	this.on = function( e, callback ){
		$this.on( e, callback );
	}
	this.trigger = function( e, params ){
		$this.trigger( e, params );
	}

	this.get_selected_date = function()
	{
		if( ! (self._selected_date == null) ){
			return self._selected_date;
		}

		var this_date = null
		for( var ii = 0; ii < self.dates.length; ii++ ){
			if( this_date ){
				break;
			}
			for( var jj = 0; jj < self.dates[ii].length; jj++ ){
				var this_date = self.dates[ii][jj];
				if( this_date ){
					break;
				}
			}
		}

		self._selected_date = this_date;
		return self._selected_date;
	}

	this.select_date = function( date )
	{
		self._selected_date = date;
		self.trigger('select-date', date);
		return this;
	}

	this.set_dates = function( dates )
	{
		self.dates = dates;
		return this;
	}

	this.render = function()
	{
		// alert('render cal' + self.dates.length);
		var $out = jQuery('<div>', {
			class:	'hc-block',
			});

		var out = new hcapp.html.List()
			.set_gutter(0)
			;

	// labels
		if( self.lang && self.lang.length ){
			var label_row = new hcapp.html.Grid()
				.set_gutter(0)
				;
			for( var jj = 0; jj < 7; jj++ ){
				var $this_cell = jQuery('<div>', {
					class:	'hc-align-center hc-nowrap hc-fs1',
					})
					.append( self.lang[jj] )
					.attr('title', self.lang[jj])
					;
				label_row.add( $this_cell, '1-7', '1-7' );
			}
			out.add( label_row.render() );
		}

		if( ! self.dates ){
			self.dates = [];
		}
		for( var ii = 0; ii < self.dates.length; ii++ ){
			var row = new hcapp.html.Grid()
				.set_gutter(0)
				;

			for( var jj = 0; jj < self.dates[ii].length; jj++ ){
				var this_date = self.dates[ii][jj];

				var $this_cell = jQuery('<div>', {
					class:	'hc-align-center hc-nowrap',
					});

				self.trigger( 'render-date', {date: this_date, cell: $this_cell} );

				row.add( $this_cell, '1-7', '1-7' );
				self._cells[ this_date ] = $this_cell;
			}

			out.add( row.render() );
		}

		return out.render();
	}

	this.get_date_cell = function( date )
	{
		return self._cells[date];
	}
}

hcapp.html.Grid = function()
{
	this.items = [];
	this.gutter = 2;

	this.add = function( item, width, mobile_width )
	{
		if( ! mobile_width ){
			mobile_width = 12;
		}
		this.items.push( {'item': item, 'width': width, 'mobile_width': mobile_width} );
		return this;
	}

	this.set_gutter = function( gutter )
	{
		this.gutter = gutter;
		return this;
	}

	this.render = function()
	{
var debug = false;
		var rows = [];

		var full_width = 12;
		var current_row = [];
		var taken_width = 0;

		for( var ii = 0; ii < this.items.length; ii++ ){
			var this_width  = this.items[ii].width;
			// this_width = 0;

			if( (taken_width + this_width) > full_width ){
				rows.push( current_row );
				taken_width = 0;
				current_row = [];
			}

			current_row.push( this.items[ii] );
			taken_width += this_width;
		}

		if( current_row.length ){
			rows.push( current_row );
			taken_width = 0;
			current_row = [];
		}

		var $out = jQuery('<div>', {
			})
			;

if( debug ){
	$out.addClass('hc-border');
}

		for( var ii = 0; ii < rows.length; ii++ ){
			var $out_row = jQuery('<div>', {
				class:	'hc-clearfix',
				});

if( debug ){
	$out_row.addClass('hc-border');
}

			if( this.gutter ){
				$out_row
					.addClass( 'hc-mxn' + this.gutter )
					;

				if( (rows.length > 1) && (ii != (rows.length - 1)) ){
					$out_row
						.addClass( 'hc-mb' + this.gutter )
						;
				}
			}

			for( var jj = 0; jj < rows[ii].length; jj++ ){
				var this_width = rows[ii][jj].width;
				var this_mobile_width = rows[ii][jj].mobile_width;

				var cell_classes = [];

				if( this_mobile_width != 12 ){
					cell_classes.push( 'hc-xs-col' );
					cell_classes.push( 'hc-xs-col-' + this_mobile_width );
				}

				cell_classes.push( 'hc-col' );
				cell_classes.push( 'hc-col-' + this_width );
				if( this.gutter ){
					cell_classes.push( 'hc-xs-mb' + this.gutter );
				}

				var $out_cell = jQuery('<div>');
				for( var kk = 0; kk < cell_classes.length; kk++ ){
					$out_cell.addClass( cell_classes[kk] );
				}

				var this_item = rows[ii][jj].item;
				if( ! Array.isArray(this_item) ){
					this_item = [this_item];
				}

				for( var kk = 0; kk < this_item.length; kk++ ){
					$out_cell
						.append( this_item[kk] )
						;
				}

if( debug ){
	$out_cell.addClass('hc-border');
}

				if( this.gutter ){
					$out_cell
						.addClass( 'hc-px' + this.gutter )
						;
				}

				$out_row
					.append( $out_cell )
					;
			}

			$out
				.append( $out_row )
				;
		}
		return $out;
	}
}

