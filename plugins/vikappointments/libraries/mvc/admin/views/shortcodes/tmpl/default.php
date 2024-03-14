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

JHtml::fetch('bootstrap.tooltip');
JHtml::fetch('formbehavior.chosen');

$app = JFactory::getApplication();
$vik = VAPApplication::getInstance();

$dt_format = $app->get('date_format') . ' ' . $app->get('time_format');

$is_searching = $this->hasFilters();

?>

<form action="admin.php" method="post" name="adminForm" id="adminForm">

	
	<div class="btn-toolbar" style="height: 32px;">

		<div class="btn-group pull-left input-append">
			<input type="search" name="filter_search" id="post-search-input" size="32" 
				value="<?php echo $this->escape($this->filters['search']); ?>" placeholder="<?php echo $this->escape(JText::translate('JSEARCH_FILTER_SUBMIT')); ?>" />

			<button type="submit" class="btn">
				<i class="fas fa-search"></i>
			</button>
		</div>

		<div class="btn-group pull-left">
			<button type="button" class="btn <?php echo ($is_searching ? 'btn-primary' : ''); ?>" onclick="vapToggleSearchToolsButton(this);">
				<?php echo JText::translate('JSEARCH_TOOLS'); ?>&nbsp;<i class="fas fa-caret-<?php echo ($is_searching ? 'up' : 'down'); ?>" id="vap-tools-caret"></i>
			</button>
		</div>
		
		<div class="btn-group pull-left">
			<button type="button" class="btn" onclick="clearFilters();">
				<?php echo JText::translate('JSEARCH_FILTER_CLEAR'); ?>
			</button>
		</div>

	</div>

	<div class="btn-toolbar" id="vap-search-tools" style="height: 32px;<?php echo ($is_searching ? '' : 'display: none;'); ?>">

		<!-- TYPE filter -->
		<?php
		$options = array(
			JHtml::fetch('select.option', '', JText::translate('JOPTION_SELECT_TYPE')),
		);

		foreach ($this->views as $type => $title)
		{
			$options[] = JHtml::fetch('select.option', $type, JText::translate($title));
		}
		?>
		<div class="btn-group pull-left">
			<select name="filter_type" id="vik-type-filter" class="<?php echo ($this->filters['type'] != -1 ? 'active' : ''); ?>" onchange="document.adminForm.submit();">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $this->filters['type']); ?>
			</select>
		</div>

		<!-- LANGUAGE filter -->
		<?php
		$options = array(
			JHtml::fetch('select.option', '*', JText::translate('JOPTION_SELECT_LANGUAGE')),
		);

		foreach (JLanguage::getKnownLanguages() as $tag => $lang)
		{
			$options[] = JHtml::fetch('select.option', $tag, $lang['nativeName']);
		}
		?>
		<div class="btn-group pull-left">
			<select name="filter_lang" id="vik-lang-filter" class="<?php echo ($this->filters['lang'] != '*' ? 'active' : ''); ?>" onchange="document.adminForm.submit();">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $this->filters['lang']); ?>
			</select>
		</div>

	</div>

<?php
if (count($this->shortcodes) == 0)
{
	echo $vik->alert(JText::translate('JGLOBAL_NO_MATCHING_RESULTS'));
}
else
{
	?>
	<table cellpadding="4" cellspacing="0" border="0" width="100%" class="<?php echo $vik->getAdminTableClass(); ?>" style="margin-top:10px;">
		
		<?php echo $vik->openTableHead(); ?>
			<tr>
				<td width="2%" class="manage-column column-cb check-column">
					<?php echo $vik->getAdminToggle(count($this->shortcodes)); ?>
				</td>

				<!-- ID -->

				<th class="<?php echo $vik->getAdminThClass('left nowrap hidden-phone'); ?>" width="1%" style="text-align: left;">
					<?php echo JText::translate('JID'); ?>
				</th>

				<!-- NAME -->
				
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="20%" style="text-align: left;">
					<?php echo JText::translate('JNAME'); ?>
				</th>

				<!-- TYPE -->
				
				<th class="<?php echo $vik->getAdminThClass('left'); ?>" width="15%" style="text-align: left;">
					<?php echo JText::translate('JTYPE'); ?>
				</th>

				<!-- SHORTCODE -->
				
				<th class="<?php echo $vik->getAdminThClass(); ?>" width="10%" style="text-align: center;">
					<?php echo JText::translate('JSHORTCODE'); ?>
				</th>
				
				<!-- POST -->

				<th class="<?php echo $vik->getAdminThClass(); ?>" width="10%" style="text-align: center;">
					<?php echo JText::translate('JPOST'); ?>
				</th>
				
				<!-- LANGUAGE -->

				<th class="<?php echo $vik->getAdminThClass('hidden-phone'); ?>" width="10%" style="text-align: center;">
					<?php echo JText::translate('JLANGUAGE'); ?>
				</th>
				
				<!-- AUTHOR -->

				<th class="<?php echo $vik->getAdminThClass('hidden-phone'); ?>" width="15%" style="text-align: center;">
					<?php echo JText::translate('JCREATEDBY'); ?>
				</th>
				
				<!-- CREATION DATE -->

				<th class="<?php echo $vik->getAdminThClass('hidden-phone'); ?>" width="15%" style="text-align: center;">
					<?php echo JText::translate('JCREATEDON'); ?>
				</th>

			</tr>
		<?php echo $vik->closeTableHead(); ?>
		
		<?php
		foreach ($this->shortcodes as $i => $row)
		{
			?>
			<tr class="row">

				<td>
					<input type="checkbox" id="cb<?php echo $i;?>" name="cid[]" value="<?php echo $row->id; ?>" onClick="<?php echo $vik->checkboxOnClick(); ?>">
				</td>
				
				<!-- ID -->

				<td class="hidden-phone">
					<?php echo $row->id; ?>
				</td>

				<!-- NAME -->
				
				<td>
					<div class="td-primary">
						<?php
						if ($row->parent_id)
						{
							echo str_repeat('— ', count($row->ancestors));
						}
						?>
						
						<a href="javascript: void(0);" onclick="jQuery('#cb<?php echo $i; ?>').prop('checked', true);Joomla.submitbutton('shortcodes.edit');">
							<?php echo $row->name; ?>
						</a>
					</div>
				</td>

				<!-- TITLE -->

				<td>
					<?php echo JText::translate($row->title); ?>
				</td>

				<!-- SHORTCODE -->

				<td style="text-align: center;">
					<?php
					echo $vik->createPopover(array(
						'title'   => JText::translate('JSHORTCODE'),
						'content' => '<textarea style="width:250px;height:200px;resize:none;" onclick="this.select();">' . $row->shortcode . '</textarea>',
						'icon'    => 'qrcode',
						'trigger' => 'click',
					));
					?>
				</td>

				<!-- POST -->

				<td style="text-align: center;">
					<?php
					// display link to reach the page in the front-end
					if ($row->post_id)
					{
						?>
						<a href="<?php echo get_permalink($row->post_id); ?>" target="_blank" class="hasTooltip" title="<?php echo $this->escape(JText::translate('VAP_SHORTCODE_VIEW_FRONT')); ?>">
							<i class="fas fa-external-link-square-alt" style="font-size: 18px;"></i>
						</a>
						<?php
					}
					// display trashed link to edit the post
					else if ($row->tmp_post_id)
					{
						$post = get_post($row->tmp_post_id);
						?>
						<a href="edit.php?post_status=trash&post_type=<?php echo $post->post_type; ?>" target="_blank" class="hasTooltip" title="<?php echo $this->escape(JText::translate('VAP_SHORTCODE_VIEW_TRASHED')); ?>">
							<i class="fas fa-external-link-square-alt" style="color: #900; font-size: 18px;"></i>
						</a>
						<?php
					}
					// display button to create a new post
					else
					{
						?>
						<a href="javascript:void(0);" class="shortcode-add-post hasTooltip" title="<?php echo $this->escape(JText::translate('VAP_SHORTCODE_CREATE_PAGE')); ?>">
							<i class="fas fa-plus-square" style="font-size: 18px;"></i>
						</a>
						<?php
					}
					?>
				</td>

				<!-- LANGUAGE -->

				<td style="text-align: center;" class="hidden-phone">
					<?php
					if ($row->lang == '*')
					{
						echo JText::translate('JALL');
					}
					else
					{
						// explode langtag
						$parts = preg_split('/[\-_]/', $row->lang);
						$flag  = strtolower(end($parts)) . '.png';

						// do not display a flag that doesn't exist
						if (is_file(VAPBASE . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'flags' . DIRECTORY_SEPARATOR . $flag))
						{
							?>
							<img src="<?php echo VAPASSETS_URI . 'css/flags/' . $flag; ?>" />
							<?php
						}
					}
					?>	
				</td>

				<!-- AUTHOR -->
				
				<td style="text-align: center;" class="hidden-phone">
					<?php echo JUser::getInstance($row->createdby)->username; ?>
				</td>

				<!-- CREATION DATE -->

				<td style="text-align: center;" class="hidden-phone">
					<?php echo JHtml::fetch('date', $row->createdon, $dt_format); ?>
				</td>

			</tr>
			<?php
		}
		?>
	</table>
	<?php
}
?>

	<input type="hidden" name="option" value="com_vikappointments" />
	<input type="hidden" name="view" value="shortcodes" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="return" value="<?php echo $this->escape($this->returnLink); ?>" />
	<?php echo $this->navbut; ?>

</form>

<?php
JText::script('VAP_SHORTCODE_CREATE_PAGE_CONFIRM');
?>

<script>

	(function($) {
		'use strict';

		window['clearFilters'] = () => {
			$('#post-search-input').val('');
			$('#vik-type-filter').updateChosen('');
			$('#vik-lang-filter').updateChosen('*');
			
			document.adminForm.submit();
		}

		$(function() {
			$('.shortcode-add-post').on('click', function() {
				// ask for a confirmation
				if (!confirm(Joomla.JText._('VAP_SHORTCODE_CREATE_PAGE_CONFIRM'))) {
					// action denied
					return false;
				}

				// uncheck all checkboxes
				$('input[name="cid[]"],thead input[type="checkbox"]').prop('checked', false);

				// get parent <tr>
				var tr = $(this).closest('tr');
				// find checkbox and toggle it
				tr.find('input[name="cid[]"]').prop('checked', true);

				// submit form
				Joomla.submitform('shortcode.addpage');
			});
		});
	})(jQuery);

</script>
