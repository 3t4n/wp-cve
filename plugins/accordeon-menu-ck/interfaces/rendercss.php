<?php
/**
 * @name		Mobile Menu CK
 * @copyright	Copyright (C) since 2018. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.ceikay.com
 */

defined('CK_LOADED') or die('Restricted access');

$id = $this->input->get('ckobjid');
$class = $this->input->get('objclass');
$fields = stripslashes($this->input->get('fields', '', 'string'));
$fields = json_decode($fields); //test
$action = $this->input->get('action');
$customstyles = stripslashes( $this->input->get('customstyles', '', 'string'));
$customstyles = json_decode($customstyles);
$cssstyles = new CKStyles();
$styles = $cssstyles->create($fields, $id, $action, $class, 'ltr', $customstyles);

if ($action == 'preview') {
	echo '<style>' . $styles . '</style>';
} else {
	return $styles;
}