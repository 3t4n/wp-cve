//http://knockoutjs.com/examples/animatedTransitions.html

// Here's a custom Knockout binding that makes elements shown/hidden via jQuery's fadeIn()/fadeOut() methods
// Could be stored in a separate utility library
ko.bindingHandlers.fadeVisible = {
    init: function(element, valueAccessor) {
        // Initially set the element to be instantly visible/hidden depending on the value
        var value = valueAccessor();
        jQuery(element).toggle(ko.unwrap(value)); // Use "unwrapObservable" so we can handle values that may or may not be observable
    },
    update: function(element, valueAccessor) {
        // Whenever the value subsequently changes, slowly fade the element in or out
        var value = valueAccessor();
        ko.unwrap(value) ? jQuery(element).fadeIn(150) : jQuery(element).fadeOut(150);
    }
};

ko.bindingHandlers.slowFadeVisible = {
    init: function(element, valueAccessor) {
        // Initially set the element to be instantly visible/hidden depending on the value
        var value = valueAccessor();
        jQuery(element).toggle(ko.unwrap(value)); // Use "unwrapObservable" so we can handle values that may or may not be observable
    },
    update: function(element, valueAccessor) {
        // Whenever the value subsequently changes, slowly fade the element in or out
        var value = valueAccessor();
        ko.unwrap(value) ? jQuery(element).fadeIn(500) : jQuery(element).fadeOut(500);
    }
};

/*BINDING FOR ENTER AND RETURN AND ARROW KEYS*/
ko.bindingHandlers.returnKey = {
	init: function(element, valueAccessor, allBindingsAccessor, viewModel) {
		ko.utils.registerEventHandler(element, 'keydown', function(evt) {
			if (evt.which === 13) {
				evt.preventDefault();
				valueAccessor().call(viewModel, evt);
				return evt.preventDefault();
			}
		});
	}
};
ko.bindingHandlers.escKey = {
	init: function(element, valueAccessor, allBindingsAccessor, viewModel) {
		ko.utils.registerEventHandler(element, 'keydown', function(evt) {
			if (evt.which === 27) {
				evt.preventDefault();
				valueAccessor().call(viewModel, evt);
				return evt.preventDefault();
			}
		});
	}
};