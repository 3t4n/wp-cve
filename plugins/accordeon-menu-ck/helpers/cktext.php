<?php

/**
 * @name		Mobile Menu CK
 * @copyright	Copyright (C) since 2018. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.ceikay.com
 */

Namespace Accordeonmenuck;
defined('CK_LOADED') or die;

Class CKText {
	public static function _($text) {

		return __($text, 'accordeon-menu-ck');
	}
}
