/**
 * Organize Media Folder
 *
 * @package Organize Media Folder
 * @subpackage jquery.organizemediafolder.js
/*  Copyright (c) 2020- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
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

		/* Select bulk folder */
		$( '#all_change' ).click(
			function () {
				var select_val = $( 'select[name="bulk_folder"]' ).val();
				var select_text = $( 'select[name="bulk_folder"] option:selected' ).text();
				$( 'select[name^="targetdirs"]' ).val( select_val );
				$( 'select[name="targetdirs"]' ).text( select_text );
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
