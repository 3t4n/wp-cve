/*
Author: Robin Phillips (Author Help)
Author URI: https://www.authorhelp.uk/
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

WP Payhip Integration is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

WP Payhip Integration is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with WP Payhip Integration. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

jQuery(document).ready(function() {
	jQuery('a').click(function() {

		// Make sure Payhip URL is HTTPS and does not have www prefix
		wp_payhip_url = jQuery(this).attr('href')
		wp_payhip_url = wp_payhip_url.replace ('http://', 'https://')
		wp_payhip_url = wp_payhip_url.replace ('https://www.', 'https://')
		
		// Check the link is a Payhip product link
		if (wp_payhip_url.substr(0, 21).toLowerCase() == 'https://payhip.com/b/') {
			// Payhip product link
			// Regular expression to get product code from URL
			re = /.*\/([^/]+)/;
			sReplace = `$1`

			// Get product code from URL
			productCode = wp_payhip_url.replace(re, sReplace);

			// Show Payhip buy box
			Payhip.Checkout.open({
				product: productCode
			})

			// Return false so that the link is not opened
			return false
		}
		else {
			// Not a Payhip product link. Return true so that the link is opened normally
			return true
		}
	})
})
