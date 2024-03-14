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

// displayed after menu.php layout file

extract($displayData);

/**
 * Layout variables
 * -----------------
 * @var   boolean  $compressed  True if the menu is compressed.
 */

// fix content padding to stick the menu to the left side
VAPApplication::getInstance()->fixContentPadding();

?>

<div class="vap-task-wrapper<?php echo $compressed ? ' extended' : ''; ?>">