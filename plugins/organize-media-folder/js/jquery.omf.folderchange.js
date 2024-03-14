/**
 * Organize Media Folder
 *
 * @package    Organize Media Folder
 * @subpackage jquery.omf.folderchange.js
/*
	Copyright (c) 2021- Katsushi Kawamori (email : dodesyoswift312@gmail.com)
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
		$( document ).on(
			'click',
			'.omf-folders',
			function () {
				let click_folder = omf_fc.menu_text + $( this ).children( 'a' ).attr( 'title' );
				let folder_slug = $( this ).attr( 'id' );
				folder_slug = folder_slug.substr( 13 );
				$.ajax(
					{
						type: 'POST',
						url: omf_fc.ajax_url,
						data: {
							'action': omf_fc.action,
							'nonce': omf_fc.nonce,
							'folder_slug': folder_slug
						}
					}
				).done(
					function (callback) {
						$( '#wp-admin-bar-omf-folder-switch' ).children( 'div.ab-item' ).text( click_folder );
						/* console.log(callback); */
						/* console.log(callback[0]); */
					}
				).fail(
					function (XMLHttpRequest, textStatus, errorThrown) {
						/* console.log( XMLHttpRequest.status ); */
						/* console.log( textStatus ); */
						/* console.log( errorThrown.message ); */
					}
				);
			}
		);
	}
);
