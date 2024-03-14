/**
 * Format a unix timestamp (UTC) into a date (Jan 1, 1970) in the user's local timezone and locale.
 *
 * @since    1.0.0
 * @param    string date_string
 * @return   string
 */
window.church_tithe_wp_format_date = function church_tithe_wp_format_date( date_string, locale ) {
	var date = new Date( date_string.replace(/\s/, 'T') + 'Z' );

	if ( 'Invalid Date' == date ) {
		return date_string;
	}

	return date.toLocaleDateString();
}

/**
 * Format a unix timestamp (UTC) into a time (00:00:00) in the user's local timezone and locale.
 *
 * @since    1.0.0
 * @param    string date_string
 * @return   string
 */
window.church_tithe_wp_format_time = function church_tithe_wp_format_time( date_string ) {
	var date = new Date( date_string.replace(/\s/, 'T') + 'Z' );

	if ( 'Invalid Date' == date ) {
		return date_string;
	}

	return date.toLocaleTimeString() + + ' (' + date.toLocaleTimeString( navigator.language,{timeZoneName:'short'}).split(' ')[2] + ')';
}

/**
 * Format a unix timestamp (UTC) into a date and time (Jan 1, 1970, 00:00:00) in the user's local timezone and locale.
 *
 * @since    1.0.0
 * @param    string date_string
 * @return   string
 */
window.church_tithe_wp_format_date_and_time = function church_tithe_wp_format_date_and_time( date_string ) {
	var date = new Date( date_string.replace(/\s/, 'T') + 'Z' );

	if ( 'Invalid Date' == date ) {
		return date_string;
	}
	
	return date.toLocaleString() + ' (' + date.toLocaleTimeString( navigator.language,{timeZoneName:'short'}).split(' ')[2] + ')';
}

/**
 * Takes a date value array from a Church Tithe WP list view component, passes it to church_tithe_wp_format_date, and returns it.
 *
 * @since    1.0.0
 * @param    array data
 * @return   string
 */
window.church_tithe_wp_list_view_format_date = function church_tithe_wp_list_view_format_date( data ) {
	return church_tithe_wp_format_date( data['value'] );
}

/**
 * Format a money amount properly for the user's locale.
 *
 * @since    1.0.0
 * @param    int cents
 * @param    string currency
 * @param    bool is_zero_decimal_currency
 * @param    string locale
 * @param    string string_after
 * @return   string
 */
window.church_tithe_wp_format_money = function church_tithe_wp_format_money( cents, currency, is_zero_decimal_currency, locale, string_after ) {

		// If this browser supports the navigator.language
		if ( navigator && navigator.language ) {
			locale = navigator.language;

			// Otherwise, default to the server's locale settings.
		} else {
			// Make sure the locale uses a dash and not an underscore
			locale = locale.replace( '_', '-' );
		}

		// If this is a zero-decimal currency
		if ( is_zero_decimal_currency ) {
			var formatted_amount = Number( cents );
		}
		// If this is not a zero decimal currency
		else {
			var formatted_amount = Number( cents ) / 100;
		}

		// Format the currency based on the site's locale. This allows commas to be used as the decimal seperator, which is technically a translation.
		// Multi currency sites can allow people to set their locale, so we'll just use the site's current locale.
		formatted_amount = new Intl.NumberFormat(locale, { style: 'currency', currency: currency }).format(formatted_amount);

		return formatted_amount + string_after;
}

/**
 * Takes a value array from a Church Tithe WP list view component, passes it to church_tithe_wp_format_visual_amount, and returns it.
 *
 * @since    1.0.0
 * @param    array data
 * @return   string
 */
window.church_tithe_wp_list_view_format_money = function church_tithe_wp_list_view_format_money( data ) {
	return church_tithe_wp_format_money( data['cents'], data['currency'], data['is_zero_decimal_currency'], data['locale'], data['string_after'] );
}

window.church_tithe_wp_get_current_view_class = function church_tithe_wp_get_current_view_class( component, views_in_question ) {

	var currently_in_view_class_name = 'church-tithe-wp-current-view';
	var hidden_class_name = 'church-tithe-wp-hidden-view';
	var at_least_one_in_view = false;

	for (var i = 0; i < views_in_question.length; i++) {
		// If the current visual state matches the view we are getting the class for
		if( component.state.current_visual_state == views_in_question[i] ) {

			var at_least_one_in_view = true;

		}
	}

	if ( at_least_one_in_view ) {
		return ' ' + currently_in_view_class_name;
	} else {
		return ' ' + hidden_class_name;
	}

}

// Convert a string to a bool
window.church_tithe_wp_string_to_bool = function church_tithe_wp_string_to_bool( the_string ) {

	// Convert the local storage from string to bool so we can compare them
	if ( the_string == 'true' ){
		return true;
	} else {
		return false;
	}
}

// Validate an email address is the expected format for an email address
window.church_tithe_wp_validate_email = function church_tithe_wp_validate_email( email ) {
	var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(String(email).toLowerCase());
}

// Accepts a locale string and returns the character to use as a decimal separator when displaying money
window.church_tithe_wp_get_decimal_character_for_locale = function church_tithe_wp_get_decimal_character_for_locale( locale ) {
	var mock_price = new Intl.NumberFormat(locale, { style: 'currency', currency: 'USD' }).format(0);

	if ( mock_price.includes(",") ) {
		decimal_character = ',';
	}

	if ( mock_price.includes(".") ) {
		decimal_character = '.';
	}

	return decimal_character;
}

// Accepts an all visual states object, a visual tree object acting as a map for the location of the component in question, and a boolean
// It then recursively goes throguh the component tree object, checking if
function church_tithe_wp_visual_state_should_become( all_current_visual_states, map_of_visual_states, visual_state_should_become, default_visual_state ) {

	// The component tree is a "map" that tells use where this component lives within the parent
	for ( var level in map_of_visual_states ) {
		// If the current component's top-level parent is set in the current visual states object, great! Keep going.
		if ( all_current_visual_states[level] ) {

			// If there are other components ahead of this component in the tree
			if ( typeof map_of_visual_states[level] !== 'undefined' && typeof map_of_visual_states[level] === 'object' && Object.keys(map_of_visual_states[level]).length !== 0){
				// Recursively nest down into the next parent component to see if it is set in the current visual states object.
				visual_state_should_become = church_tithe_wp_visual_state_should_become( all_current_visual_states[level], map_of_visual_states[level], visual_state_should_become, default_visual_state );
			} else {

				// If we are at the end of the component tree, and the component level is in the current visual states object, it's in view!
				visual_state_should_become = level;

			}
		} else if ( 'variable' === level ) {

			// If we are at the end of the component tree, and the component level is variable, grab the value from the master view object at this level
			if ( Object.keys(all_current_visual_states)[0] ) {
				visual_state_should_become = Object.keys(all_current_visual_states)[0];
			} else {
				visual_state_should_become = default_visual_state;
			}

		} else {
			visual_state_should_become = default_visual_state;
		}

	}

	return visual_state_should_become;
}

window.church_tithe_wp_set_visual_state_of_component = function church_tithe_wp_set_visual_state_of_component( settings ) {

	return new Promise( (resolve, reject) => {

		// If we don't have the variables we require, fail.
		if ( ! settings.component ) {
			throw new Error( 'The function "church_tithe_wp_set_visual_state_of_component" is missing required variables' );
		}

		if ( ! settings.component.props.main_component.state.all_current_visual_states ) {
			throw new Error( 'A visual states object is required.' );
		}

		if ( ! settings.component.state.map_of_visual_states ) {
			throw new Error( 'The component must have a map of the view states stored in the state with the key "map_of_visual_states"' );
		}

		var new_map_of_visual_states = church_tithe_wp_get_default_map_of_visual_states( settings.component.state.map_of_visual_states, settings.default_visual_state );

		// Figure out if the parent component is in view
		for ( var level in settings.component.props.main_component.state.all_current_visual_states ) {
			if ( ! new_map_of_visual_states[level] ) {
				parent_component_is_in_view = false;
				break;
			} else {
				parent_component_is_in_view = true;
			}
		}

		// If the parent component is in view, just set it to a default and do nothing else
		if ( ! parent_component_is_in_view ) {
			var visual_state_should_become = settings.default_visual_states.parent_not_in_view;
		} else {
			var visual_state_should_become = church_tithe_wp_visual_state_should_become( settings.component.props.main_component.state.all_current_visual_states, settings.component.state.map_of_visual_states, false, settings.default_visual_states.parent_in_view );
		}

		// If the state of the component is already the current state in the master visual states object, do nothing.
		if ( settings.component.state[settings.name_of_visual_state_variable] == visual_state_should_become ) {

			resolve( visual_state_should_become );
			return;

		} else {

			if ( ! visual_state_should_become ) {

				settings.component.setState( {
					[settings.name_of_visual_state_variable]: settings.default_visual_states.parent_not_in_view
				}, function() {
					//console.log( 'setting default state to: ' + settings.default_visual_states.parent_not_in_view + ' where default was ' + settings.default_visual_states.parent_in_view );
					resolve( settings.default_visual_states.parent_not_in_view );
				} );

			} else {

				settings.component.setState( {
					[settings.name_of_visual_state_variable]: visual_state_should_become
				}, function() {
					//console.log( 'setting visual state to: ' + visual_state_should_become + ' where default was ' + settings.default_visual_states.parent_in_view );
					resolve( visual_state_should_become );
				} );

			}

		}

	});

}

function church_tithe_wp_get_default_map_of_visual_states( map_of_visual_states, default_visual_state, new_map_of_visual_states = {}, previous_level = false ) {

	for ( var level in map_of_visual_states ) {

		// If there's another level, keep going
		if ( typeof map_of_visual_states[level] !== 'undefined' && typeof map_of_visual_states[level] === 'object' && Object.keys(map_of_visual_states[level]).length !== 0) {

			// If we at a level great than 1
			if ( previous_level ) {
				var temp = {};
				temp[level] = {};
				new_map_of_visual_states[previous_level] = church_tithe_wp_get_default_map_of_visual_states( map_of_visual_states[level], default_visual_state, temp, level );
			}
			// If we are at the top level
			else {
				new_map_of_visual_states[level] = {};
				new_map_of_visual_states = church_tithe_wp_get_default_map_of_visual_states( map_of_visual_states[level], default_visual_state, new_map_of_visual_states, level );
			}

			return new_map_of_visual_states;
		}
		// If the last level is reached and it's variable, or empty, set it to the default state and be done. No more nesting and looping.
		else if ( 'variable' === level || 0 === Object.keys(map_of_visual_states[level]).length) {
			new_map_of_visual_states[previous_level][default_visual_state] = {};
			return new_map_of_visual_states;
		}
		// If there's no more levels, and the last level isn't "variable", set it to the current level and be done. No more nesting and looping.
		else {
			new_map_of_visual_states[previous_level][level] = {};
			return new_map_of_visual_states;
		}
	}

}
