/**
 * @copyright	Copyright (C) 2016. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Plugins CK - Cédric KEIFLIN - https://www.ceikay.com
 */


var $ck = jQuery.noConflict();

function ckEditStyleWidget(btn) {
	var style = $ck(btn).prev().val();
	if (style == '-1') {
		alert('No style is selected, there is nothing to edit');
		return;
	}

	CKBox.open({url: accordeonmenuck_urls.adminurl + '/admin.php?page=accordeonmenuck_edit_style&id='+style+'&modal=1'});
}