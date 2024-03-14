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

$fields = !empty($displayData['fields']) ? $displayData['fields'] : array();
$params = !empty($displayData['params']) ? $displayData['params'] : array();

$html = '';

$vik = VAPApplication::getInstance();

$hasPassword = false;

foreach ($fields as $key => $f)
{
	$def_val = '';

	if (!empty($params[$key]))
	{
		$def_val = $this->escape($params[$key]);
	}
	else if (!empty($f['default']))
	{
		$def_val = $f['default'];
	}
	
	$_label_arr = explode('//', $f['label']);
	$label 		= str_replace(':', '', $_label_arr[0]);

	$title = $label;

	if (!empty($label))
	{
		$label .= (!empty($f['required']) ? '*' : '').':';
	}

	unset($_label_arr[0]);
	$helplabel = implode('//', $_label_arr);

	echo $vik->openControl($label);
	
	$input = '';

	if ($f['type'] == 'text')
	{
		?>
		<input type="text" class="<?php echo (!empty($f['required']) ? 'required' : ''); ?>" value="<?php echo $def_val; ?>" name="<?php echo $key; ?>" size="40" />
		<?php
	}
	else if ($f['type'] == 'password')
	{
		$hasPassword = true;
		?>
		<input type="password" class="<?php echo (!empty($f['required']) ? 'required' : ''); ?>" value="<?php echo $def_val; ?>" name="<?php echo $key; ?>" size="40" />

		<a href="javascript:void(0)" class="input-align" onclick="switchPasswordField(this);"><i class="fas fa-lock big" style="margin-left: 10px;"></i></a>
		<?php
	}
	else if ($f['type'] == 'select')
	{
		$is_assoc = (array_keys($f['options']) !== range(0, count($f['options']) - 1));
		?>
		<select name="<?php echo $key.(!empty($f['multiple']) ? '[]' : ''); ?>"
			class="<?php echo (!empty($f['required']) ? 'required' : ''); ?>" 
			<?php echo (!empty($f['multiple']) ? 'multiple' : ''); ?>
		>
			<?php 
			foreach ($f['options'] as $opt_key => $opt_val)
			{
				if (!$is_assoc)
				{
					$opt_key = $opt_val;
				}

				?>

				<option 
					value="<?php echo $opt_key; ?>"
					<?php echo ((is_array($def_val) && in_array($opt_key, $def_val)) || $opt_key == $def_val ? 'selected="selected"' : ''); ?>
				><?php echo $opt_val; ?></option>

				<?php
			}
			?>
		</select>
		<?php
	}
	else
	{
		echo $f['html']; 
	}
	
	if ($helplabel)
	{
		echo $vik->createPopover(array(
			'title' 	=> $title,
			'content' 	=> strtoupper($helplabel[0]) . substr($helplabel, 1),
		));
	}
	
	echo $vik->closeControl();
}

if ($hasPassword)
{
	?>
	<script>

		function switchPasswordField(link) {
			
			if (jQuery(link).prev().is(':password'))
			{
				jQuery(link).prev().attr('type', 'text');
				jQuery(link).find('i').removeClass('fa-lock').addClass('fa-unlock');
			}
			else
			{
				jQuery(link).prev().attr('type', 'password');
				jQuery(link).find('i').removeClass('fa-unlock').addClass('fa-lock');
			}

		}

	</script>
	<?php
}
