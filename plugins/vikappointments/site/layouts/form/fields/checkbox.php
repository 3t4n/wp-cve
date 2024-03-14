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

$id      = !empty($displayData['id'])     ? $displayData['id']      : '';
$name    = !empty($displayData['name'])   ? $displayData['name']    : 'name';
$checked = isset($displayData['checked']) ? $displayData['checked'] : false;
$class   = isset($displayData['class'])   ? $displayData['class']   : '';

?>

<input
	type="checkbox"
	name="<?php echo $this->escape($name); ?>"
	value="1"
	id="<?php echo $this->escape($id); ?>"
	class="<?php echo $this->escape($class); ?>"
	<?php echo $checked ? 'checked="checked"' : ''; ?>
	aria-labelledby="<?php echo $id; ?>-label"
/>
