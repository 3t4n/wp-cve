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

extract($displayData);

/**
 * Layout variables
 * -----------------
 * @var   string   $html        The menu HTML.
 * @var   boolean  $compressed  True if the menu is compressed.
 */

?>

<a class="btn mobile-only" id="vap-menu-toggle-phone">
	<i class="fas fa-bars"></i>
	<?php echo JText::translate('VAPMENU'); ?>
</a>

<div class="vap-leftboard-menu<?php echo $compressed ? ' compressed' : ''; ?>" id="vap-main-menu">
	<?php echo $html; ?>
</div>