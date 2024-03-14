/**
 * Ogp Plus Admin
 *
 * @package    Ogp Plus Admin
 * @subpackage jquery.ogpplus.admin.js
/*  Copyright (c) 2019- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; version 2 of the License.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

jQuery(
	function ($) {

		/* Range */
		$( '#excerpt_range' ).html( $( '#excerpt_bar' ).val() );
		$( '#excerpt_bar' ).on(
			'input change',
			function () {
				$( '#excerpt_range' ).html( $( this ).val() );
			}
		);

		/* Get media url */
		var custom_uploader = wp.media(
			{
				title: ogpplus.button,
				library: {
					type: 'image'
				},
				button: {
					text: ogpplus.button
				},
				multiple: false
			}
		);

		$( '#media-upload' ).on(
			'click',
			function (e) {
				e.preventDefault();
				custom_uploader.open();
			}
		);

		custom_uploader.on(
			'select',
			function () {
				var images = custom_uploader.state().get( 'selection' );
				images.each(
					function (file) {
						$( '#media-id' ).val( file.toJSON().id );
						$( '.image-preview-wrapper' ).children( 'img' ).attr( 'src', file.toJSON().url );
					}
				);
			}
		);

		/* Control of the Enter key */
		$( 'input[type!="submit"][type!="button"]' ).keypress(
			function (e) {
				if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
					return false;
				} else {
					return true;
				}
			}
		);

	}
);
