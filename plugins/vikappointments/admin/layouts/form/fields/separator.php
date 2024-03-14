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

$label = isset($displayData['label']) ? $displayData['label'] : '';
$id    = !empty($displayData['id'])   ? $displayData['id']    : $name;
$class = isset($displayData['class']) ? $displayData['class'] : '';

$class = trim('control-group separator ' . $class);
?>

<div class="<?php echo $this->escape($class); ?>" id="<?php echo $this->escape($id); ?>">
	<?php
	// make sure the label contains at least a character different than
	// a blank space, an hyphen and an underscore
	if (preg_match("/[^\s\-_]/", $label))
	{
		?>
		<strong><?php echo $label; ?></strong>
		<?php
	}
	?>

	<hr />
</div>
