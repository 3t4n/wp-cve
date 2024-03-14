<?php

namespace Dotdigital_WordPress_Vendor;

/**
 * Data Fields tab view
 *
 * This file is used to display the data fields tab
 *
 * @package    Dotdigital_WordPress
 *
 * @var Dotdigital_WordPress_Setting_Form $form
 * @var Dotdigital_WordPress\Admin\Page\Tab\Dotdigital_WordPress_Lists_Admin $view
 */
use Dotdigital_WordPress\Includes\Setting\Form\Dotdigital_WordPress_Setting_Form;
?>
<div class="wrap">

	<div class="card w-100 widefat">
		<h2><?php 
echo esc_html($form->get_title());
?></h2>
		<form method="<?php 
echo esc_attr($form->get_method());
?>"
			  action="<?php 
echo esc_attr($form->get_action());
?>">
			<table class="wp-list-table widefat fixed form-table">

				<thead>
				<tr>
					<th scope="col" class="manage-column column-cb check-column">
						<input class="multiselector" type="checkbox">
					</th>
					<th scope="col" id="lists" class="manage-column column-lists sortable desc" style="">
						<a href="?page=<?php 
echo esc_attr($view->get_page_slug());
?>&tab=<?php 
echo esc_attr($view->get_url_slug());
?>&order=<?php 
echo $view->get_sort_order() === 'desc' ? 'asc' : 'desc';
?>">
							<span>Data Field</span><span class="sorting-indicator"></span>
						</a>
					</th>
					<th scope="col" id="changelabel" class="manage-column column-changelabel" style="">
						Change label
					</th>
					<th scope="col" id="visible" class="manage-column column-visible" style="text-align: center;">
						Required?
					</th>
				</tr>
				</thead>

				<tfoot>
				<tr>
					<th scope="col" class="manage-column column-cb check-column">
						<input class="multiselector" type="checkbox">
					</th>
					<th scope="col" id="lists" class="manage-column column-lists sortable desc" style="">
						<a href="?page=dotdigital-settings&tab=<?php 
echo esc_attr($view->get_url_slug());
?>&order=<?php 
echo $view->get_sort_order() === 'desc' ? 'asc' : 'desc';
?>">
							<span>Data Field</span><span class="sorting-indicator"></span>
						</a>
					</th>
					<th scope="col" id="changelabel" class="manage-column column-changelabel" style="">
						Change label
					</th>
					<th scope="col" id="visible" class="manage-column column-visible" style="text-align: center;">
						Required?
					</th>
				</tr>
				</tfoot>

				<tbody class="sortable ui-sortable">
				<?php 
foreach ($form->get_grouped_inputs() as $group_id => $inputs) {
    ?>
					<tr id="<?php 
    echo esc_attr($group_id);
    ?>" class="dragger toggle-inputs" toggle-row-inputs="true">
						<?php 
    list($name_input, $label_input, $type_input, $required_input) = $inputs;
    ?>
						<th scope="row">
							<span class="handle ui-sortable-handle"><img
									src="<?php 
    echo esc_url(plugins_url('../../../assets/large.png', __FILE__));
    ?>"
									class="drag_image"/></span>
							<?php 
    $name_input->render();
    ?>
						</th>
						<td class="list-column"><?php 
    echo esc_html($label_input->get_label());
    ?></td>
						<td>
							<?php 
    $type_input->render();
    ?>
							<?php 
    $label_input->render();
    ?>
						</td>
						<td><?php 
    $required_input->render();
    ?></td>
					</tr>
				<?php 
}
?>
				</tbody>

			</table>
			<?php 
settings_fields($view->get_slug());
?>
			<?php 
submit_button();
?>
		</form>
		<p>
			<a href="https://support.dotdigital.com/hc/en-gb/articles/212216058-Using-the-dotmailer-WordPress-sign-up-form-plugin-v2#My%20contact%20data%20fields"
			   target="_blank">Find out more...</a>
		</p>
	</div>
</div>
<?php 
