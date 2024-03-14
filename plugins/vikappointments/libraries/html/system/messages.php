<?php
/** 
 * @package     VikAppointments - Libraries
 * @subpackage  html.system
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

$queue = !empty($displayData['queue']) ? $displayData['queue'] : array();
$class = !empty($displayData['class']) ? $displayData['class'] : 'notice';

foreach ($queue as $state)
{
	$state->type    = !empty($state->type) && $state->type != 'notice' ? $state->type : 'info';
	$state->message = (array) $state->message;

	?>
	<div class="<?php echo $class; ?> is-dismissible <?php echo $class; ?>-<?php echo $state->type; ?>">
		<?php
		foreach ($state->message as $text)
		{
			if (!preg_match("/^<(.*?)>/", $text))
			{
				// wrap text in a paragraph if doesn't start with a tag
				$text = '<p>' . $text . '</p>';
			}

			echo $text;
		}
		?>
	</div>
	<?php
}
