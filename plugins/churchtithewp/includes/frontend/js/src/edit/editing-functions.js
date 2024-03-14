window.church_tithe_wp_pass_value_to_block = function church_tithe_wp_pass_value_to_block( main_component, editing_keys_arg, new_value, use_typing_delay = false ){

	return new Promise( (resolve, reject) => {

		var editing_keys = editing_keys_arg.split("/");
		var recreated_unique_settings = {
			...main_component.state.unique_settings
		};

		// The string path is sent in separated by slashes like this: strings/input_field_instructions/tithe_amount/yo

		// Loop through each level, in regards to the main javascript object
		for (var i = 1; i <= editing_keys.length; i++) {

			// Re-create that level...
			if ( 1 === i ) {
				// If we are on the last item, the one we want to change here...
				if ( i === editing_keys.length ) {
					recreated_unique_settings[editing_keys[0]] = new_value
				}
			}

			if ( 2 === i ) {
				// If we are on the last item, the one we want to change here...
				if ( i === editing_keys.length ) {
					recreated_unique_settings[editing_keys[0]][editing_keys[1]] = new_value
				}
			}

			if ( 3 === i ) {
				// If we are on the last item, the one we want to change here...
				if ( i === editing_keys.length ) {
					recreated_unique_settings[editing_keys[0]][editing_keys[1]][editing_keys[2]] = new_value
				}
			}

			if ( 4 === i ) {
				// If we are on the last item, the one we want to change here...
				if ( i === editing_keys.length ) {
					recreated_unique_settings[editing_keys[0]][editing_keys[1]][editing_keys[2]][editing_keys[3]] = new_value
				}
			}

			if ( 5 === i ) {
				// If we are on the last item, the one we want to change here...
				if ( i === editing_keys.length ) {
					recreated_unique_settings[editing_keys[0]][editing_keys[1]][editing_keys[2]][editing_keys[3]][editing_keys[4]] = new_value
				}
			}

			if ( 6 === i ) {
				// If we are on the last item, the one we want to change here...
				if ( i === editing_keys.length ) {
					recreated_unique_settings[editing_keys[0]][editing_keys[1]][editing_keys[2]][editing_keys[3]][editing_keys[4]][editing_keys[5]] = new_value
				}
			}

			if ( 7 === i ) {
				// If we are on the last item, the one we want to change here...
				if ( i === editing_keys.length ) {
					recreated_unique_settings[editing_keys[0]][editing_keys[1]][editing_keys[2]][editing_keys[3]][editing_keys[4]][editing_keys[5]][editing_keys[6]] = new_value
				}
			}

		}

		// Pass the new value up to the Gutenberg attribute state...
		main_component.props.editing_parent_component.onChangeHandler( recreated_unique_settings, use_typing_delay ).then( () => {
			resolve();
			return;
		});

	});

}
