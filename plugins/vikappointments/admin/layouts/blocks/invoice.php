<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Layout variables
 * -----------------
 * @param 	integer  id      The ID of the invoice.
 * @param 	string 	 number  The invoice unique number.
 * @param 	string 	 file    The file (URI) to which the invoice is linked.
 * @param 	string   name    An optional file name to use.
 */
extract($displayData);

if (!isset($name))
{
	// missing name, extract it from the file name
	$name = preg_split("/[\/\\\\]/", $file);
	$name = preg_replace("/\.pdf$/", '', end($name));
}

?>
	
<div class="vap-archive-fileblock">

	<div class="vap-archive-fileicon">
		<img src="<?php echo VAPASSETS_ADMIN_URI . 'images/invoice@big.png'; ?>" />
	</div>

	<div class="vap-archive-filename">
		<a href="<?php echo $file; ?>?t=<?php echo time(); ?>" target="_blank">
			<?php echo $number; ?>
		</a>

		<br />

		<small class="break-word"><?php echo $name; ?></small>
	</div>

	<input type="checkbox" style="display:none;" id="cb<?php echo md5($number . $name); ?>" name="cid[]" value="<?php echo $id; ?>" onchange="<?php echo VAPApplication::getInstance()->checkboxOnClick(); ?>" />

</div>
