function FontViewModel(font) {
	var object = this;
	
	object.id = font.id;
	object.family = font.family;
	object.name = ko.observable(font.name);
	object.preview_text = font.preview;
	object.provider = font.provider;
	object.selectors = ko.observableArray(font.selectors);
	
	object.style_properties = { 'font-family': object.family };
	for(var style_property in font.additional_styles) {
		object.style_properties[style_property] = font.additional_styles[style_property];
	}
	
	// For adding a new selector to the font
	
	/// Data for selector to add
	object.fontstack = ko.observable('');
	object.selector = ko.observable('');

	/// Conditionals for enabling / display
	object.is_valid_selector = ko.computed(function() { return '' != object.selector() }, object);
	
	/// Woo, let's add it
	object.add_selector = function() {
		object.selectors.push({ tag: object.selector(), fallback: object.fontstack() });
		
		object.fontstack('');
		object.selector('');
	};
	
	object.remove_selector = function(data) {
		object.selectors.remove(data);
	};
	
	return object;
}

function SelectorViewModel(selector) {
	var object = this;
	
	object.id = selector.id;
	object.editing = !selector.id;
	object.fallback = ko.observable(selector.fallback);
	object.font = ko.observable(WebFontsStylesheetViewModel.get_font_object(selector.font));
	
	object.tag = ko.observable(selector.tag);
	object.provider = selector.provider;
	
	return object;
}

function StylesheetViewModel() {
	var object = this;
	
	object.can_submit = ko.observable(true);
	
	object.visible_tab = ko.observable('font');
	
	object.show_by_font = ko.computed(function() { return object.visible_tab() == 'font'; }, object);
	object.show_by_selector = ko.computed(function() { return object.visible_tab() == 'selector'; }, object);
	
	object.fonts = ko.observableArray();
	object.selectors = ko.observableArray();
	
	object.fonts_for_selectors = ko.computed(function() {
		var fonts = object.fonts();
		
		var result = [];
		jQuery.each(fonts, function(index, font) {
			result.push({
				id: font.provider + '-' + font.id,
				family: font.family,
				name: font.name
			});
		});
		
		return result;
	}, object);

	object.new_selector = function() {
		object.selectors.push(new SelectorViewModel({ id: 0, tag: '', font_id: '', fallback: '' }));
	}
	
	object.remove_selector = function(data) {
		object.selectors.remove(data);
	};
	
	object.get_font_object = function(font_id) {
		var the_font = false;
		
		jQuery.each(object.fonts_for_selectors(), function(index, font) {
			if(font_id == font.id) {
				the_font = font;
				return false;
			}
		})
		
		return the_font;
	};
	
	return object;
}

var WebFontsStylesheetViewModel = new StylesheetViewModel();

jQuery(document).ready(function($) {
	if(typeof WebFontsStylesheetFonts != 'undefined') {
		ko.applyBindings(WebFontsStylesheetViewModel);
		
		$.each(WebFontsStylesheetFonts, function(index, font) {
			WebFontsStylesheetViewModel.fonts.push(new FontViewModel(font));
		});
		
		
		$.each(WebFontsStylesheetSelectors, function(index, selector) {
			WebFontsStylesheetViewModel.selectors.push(new SelectorViewModel(selector));
		});
		
		$('#web-fonts-font-selectors form').submit(function(event) {
			WebFontsStylesheetViewModel.can_submit(false);
			
			var $this = $(this);
			
			var $input = $('<input type="hidden" name="web-fonts-stylesheet-data" />');
			$input.val(ko.toJSON(WebFontsStylesheetViewModel));
			
			$this.append($input);
		});
	}
});