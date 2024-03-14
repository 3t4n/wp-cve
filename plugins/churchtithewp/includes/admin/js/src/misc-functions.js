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
 * Takes a date value array from a Church Tithe WP list view component, passes it to church_tithe_wp_format_date, and returns it.
 *
 * @since    1.0.0
 * @param    array data
 * @return   string
 */
window.church_tithe_wp_list_view_format_date_and_time = function church_tithe_wp_list_view_format_date( data ) {
	return church_tithe_wp_format_date_and_time( data['value'] );
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

		// Make sure the locale uses a dash and not an underscore
		locale = locale.replace( '_', '-' );

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
 * Takes a value array from mpwpadmin's list view component, passes it to church_tithe_wp_format_visual_amount, and returns it.
 *
 * @since    1.0.0
 * @param    array data
 * @return   string
 */
window.church_tithe_wp_list_view_format_money = function church_tithe_wp_list_view_format_money( data ) {
	return church_tithe_wp_format_money( data['cents'], data['currency'], data['is_zero_decimal_currency'], data['locale'], data['string_after'] );
}
