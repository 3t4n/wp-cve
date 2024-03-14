<?php
/** 
 * @package     VikAppointments - Libraries
 * @subpackage  html.form
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

$name    = !empty($displayData['name'])   ? $displayData['name']    : '';
$class   = !empty($displayData['class'])  ? $displayData['class']   : '';
$id      = !empty($displayData['id'])     ? $displayData['id']      : '';
$visible = isset($displayData['visible']) ? $displayData['visible'] : true;
$tag     = !empty($displayData['tag'])    ? $displayData['tag']     : '';

?>

<div class="postbox-container vap">

	<div class="postbox<?php echo $visible ? '' : ' closed'; ?>" data-tag="<?php echo $this->escape($tag); ?>">

		<?php
		if (!empty($name))
		{
			?>
			<div class="postbox-header">
				<h2><?php echo JText::translate($name); ?></h2>

				<div class="handle-actions">
					<button type="button" class="handlediv toggler" aria-expanded="true">
						<i class="fas fa-caret-<?php echo $visible ? 'up' : 'down'; ?>"></i>
					</button>
				</div>
			</div>
			<?php
		}
		?>

		<div class="inside<?php echo $class ? ' ' . $this->escape($class) : ''; ?>" <?php echo $id ? ' id="' . $this->escape($id) . '"' : ''; ?>>
