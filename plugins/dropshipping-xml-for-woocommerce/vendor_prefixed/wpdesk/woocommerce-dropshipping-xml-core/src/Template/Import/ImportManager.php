<?php

namespace DropshippingXmlFreeVendor;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportMapperViewAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportOptionsViewAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportStatusViewAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportManagerViewAction;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportManagerForm;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportFileViewAction;
/**
 * @var string $title
 * @var string $import_url
 * @var string $header_url
 * @var WPDesk\View\Renderer\Renderer $renderer
 * @var \WPDesk\Library\DropshippingXmlCore\Infrastructure\View\FormView $form
 * @var \WPDesk\Library\DropshippingXmlCore\Helper\PluginHelper $plugin_helper
 * @var \WPDesk\Library\DropshippingXmlCore\Entity\Import[] $imports
 * @var string $nonce
 * @var string $elements number of all import items
 */
$display_import_status = function (\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import $import) {
    $status = $class = $error_message = '';
    // phpcs:ignore
    switch ($import->get_status()) {
        case \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import::STATUS_WAITING:
            $class = 'color-success';
            $status = \__('Synchronization active', 'dropshipping-xml-for-woocommerce');
            break;
        case \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import::STATUS_STOPPED:
            $class = 'color-error';
            $status = \__('Synchronization stopped', 'dropshipping-xml-for-woocommerce');
            break;
        case \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import::STATUS_ERROR:
            $class = 'color-error';
            $status = \__('Synchronization error', 'dropshipping-xml-for-woocommerce');
            $error_message = '<p class="' . $class . '">' . $import->get_error_message() . '</p>';
            break;
        default:
            $class = 'color-success';
            $status = \__('Synchronization in progress', 'dropshipping-xml-for-woocommerce');
            break;
    }
    return \str_replace(['{class}', '{status}', '{error}'], [$class, $status, $error_message], '<span class="{class}"><strong>{status}</strong>{error}</span>');
};
$display_action_url = function (\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import $import) use($plugin_helper, $nonce) {
    switch ($import->get_status()) {
        case \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import::STATUS_STOPPED:
        case \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import::STATUS_ERROR:
            $anchor = \__('Activate synchronization', 'dropshipping-xml-for-woocommerce');
            $url = $plugin_helper->generate_url_by_view(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportManagerViewAction::class, ['activate' => $import->get_id(), 'nonce' => $nonce]);
            break;
        default:
            $anchor = \__('Stop synchronization', 'dropshipping-xml-for-woocommerce');
            $url = $plugin_helper->generate_url_by_view(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportManagerViewAction::class, ['stop' => $import->get_id(), 'nonce' => $nonce]);
            break;
    }
    return \str_replace(['{url}', '{anchor}'], [$url, $anchor], '	<a href="{url}">{anchor}</a>');
};
$renderer->output_render('Header', ['title' => $title, 'header_url' => $header_url]);
?>

<p style="font-weight:bold;"><?php 
echo \wp_kses_post(\__('Read more in the <a href="https://wpde.sk/dropshipping-import-2" class="docs-url" target="_blank">plugin documentation &rarr;</a>', 'dropshipping-xml-for-woocommerce'));
?></p>
<p><?php 
echo \wp_kses_post(\__('Have you encountered any problems with the import or want to know more? <a href="https://wpde.sk/dropshipping-faq" class="docs-url" target="_blank">Visit FAQ  &rarr;</a>.', 'dropshipping-xml-for-woocommerce'));
?></p>


<?php 
$form->form_start();
?>
<div class="tablenav top">
	<div class="alignleft actions bulkactions">
		<?php 
$form->show_field('manage');
?>
		<?php 
$form->show_field('apply');
?>
	</div>
	<div class="tablenav-pages one-page">
		<span class="displaying-num"><?php 
echo \esc_html($elements);
?></span>
		<br class="clear">
	</div>
	<table class="wp-list-table widefat fixed striped posts">
		<thead>
			<tr>
				<td id="cb" class="manage-column column-cb check-column">
					<label class="screen-reader-text" for="cb-select-all-1"><?php 
echo \esc_html(\__('Select all', 'dropshipping-xml-for-woocommerce'));
?></label>
					<input id="cb-select-all-1" type="checkbox">
				</td>
				<th scope="col" id="id" class="manage-column column-id desc">
					<span><?php 
echo \esc_html(\__('ID', 'dropshipping-xml-for-woocommerce'));
?></span>
				</th>
				<th scope="col" id="schedule" class="manage-column column-schedule">
					<span><?php 
echo \esc_html(\__('Cron schedule', 'dropshipping-xml-for-woocommerce'));
?></span>
				</th>
				<th scope="col" id="actions" class="manage-column column-actions">
					<span><?php 
echo \esc_html(\__('Actions', 'dropshipping-xml-for-woocommerce'));
?></span>
				</th>
				<th scope="col" id="import-status" class="manage-column column-import-status">
					<span><?php 
echo \esc_html(\__('Status', 'dropshipping-xml-for-woocommerce'));
?></span>
				</th>
				<th scope="col" id="import-stats" class="manage-column column-import-status">
					<span><?php 
echo \esc_html(\__('Import statistics', 'dropshipping-xml-for-woocommerce'));
?></span>
				</th>
				<th scope="col" id="date" class="manage-column column-date asc">
					<span><?php 
echo \esc_html(\__('Created', 'dropshipping-xml-for-woocommerce'));
?></span>
				</th>
			</tr>
		</thead>

		<tbody id="the-list">
			<?php 
if (empty($imports)) {
    ?>
				<tr>
					<td colspan="5">
						<span>
						<?php 
    /* TRANSLATORS: %s: http url to import page */
    echo \wp_kses_post(\sprintf(\__('Import list is empty, <a href="%s" target="_self">please create your first import.</a>', 'dropshipping-xml-for-woocommerce'), \esc_url($import_url)));
    ?>
						</span>
					</td>
				</tr>
			<?php 
} else {
    ?>

				<?php 
    foreach ($imports as $import_file) {
        ?>
					<tr id="post-<?php 
        echo \esc_html($import_file->get_id());
        ?>" class="iedit level-0 status-publish hentry">
						<th scope="row" class="check-column">
							<input id="cb-select-<?php 
        echo \esc_html($import_file->get_id());
        ?>" type="checkbox" name="<?php 
        echo \esc_html(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\ImportManagerForm::ID);
        ?>[id][]" value="<?php 
        echo \esc_html($import_file->get_id());
        ?>">
						</th>
						<td class="id column-id has-row-actions column-primary">
							<?php 
        if (!empty($import_file->get_import_name())) {
            echo '<strong><span class="row-file-name">' . $import_file->get_import_name() . '</span></strong><br>';
            echo '<small><span class="row-file">' . \esc_url($import_file->get_url()) . '</span></small>';
        } else {
            echo '<strong><span class="row-file">' . \esc_url($import_file->get_url()) . '</span></strong>';
        }
        ?>
							<div class="row-actions">
							<span class="edit">
									<a href="
									<?php 
        echo \esc_url($plugin_helper->generate_url_by_view(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportFileViewAction::class, ['uid' => $import_file->get_uid(), 'mode' => 'edit_all']));
        ?>
									"><?php 
        echo \esc_html(\__('Edit', 'dropshipping-xml-for-woocommerce'));
        ?></a> |
								</span>
								<span class="edit">
									<a href="
									<?php 
        echo \esc_url($plugin_helper->generate_url_by_view(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportMapperViewAction::class, ['uid' => $import_file->get_uid(), 'mode' => 'edit']));
        ?>
												"><?php 
        echo \esc_html(\__('Edit mapper', 'dropshipping-xml-for-woocommerce'));
        ?></a> |
								</span>
								<span class="edit">
									<a href="
									<?php 
        echo \esc_url($plugin_helper->generate_url_by_view(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportOptionsViewAction::class, ['uid' => $import_file->get_uid(), 'mode' => 'edit']));
        ?>
												"><?php 
        echo \esc_html(\__('Edit options', 'dropshipping-xml-for-woocommerce'));
        ?></a> |
								</span>
								<span class="edit">
									<a href="<?php 
        echo \esc_url($plugin_helper->get_url_to_plugin_file($import_file->get_uid()));
        ?>"><?php 
        echo \esc_html(\__('Imported file preview', 'dropshipping-xml-for-woocommerce'));
        ?></a> |
								</span>
								<span class="edit">
									<a href="
									<?php 
        echo \esc_url($plugin_helper->generate_url_by_view(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportManagerViewAction::class, ['clone' => $import_file->get_uid(), 'nonce' => $nonce]));
        ?>
												"><?php 
        echo \esc_html(\__('Clone', 'dropshipping-xml-for-woocommerce'));
        ?></a> |
								</span>
								<span class="trash">
									<a href="
									<?php 
        echo \esc_url($plugin_helper->generate_url_by_view(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportManagerViewAction::class, ['delete' => $import_file->get_id(), 'nonce' => $nonce]));
        ?>
												"><?php 
        echo \esc_html(\__('Delete', 'dropshipping-xml-for-woocommerce'));
        ?></a> |
								</span>
							</div>
						</td>
						<td class="id column-id">
							<span title="Cron Schedule"><?php 
        echo \esc_html($import_file->get_cron_schedule());
        ?></span>
						</td>
						<td class="id column-id">
							<ul>
								<li>
									<strong>
										<a href="<?php 
        echo \esc_url($plugin_helper->generate_url_by_view(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Action\View\ImportStatusViewAction::class, ['uid' => $import_file->get_uid()]));
        ?>"><?php 
        echo \esc_html(\__('Import now', 'dropshipping-xml-for-woocommerce'));
        ?></a>
									</strong>
								</li>
								<li>
									<strong>
										<?php 
        echo \wp_kses_post($display_action_url($import_file));
        ?>
									</strong>
								</li>
							</ul>
						</td>
						<td class="id column-id">
							<ul>
								<li>
									<?php 
        echo \wp_kses_post($display_import_status($import_file));
        ?>
								</li>
							</ul>
						</td>
						<td class="id column-id">
							<ul>
								<li>
									<?php 
        echo \esc_html(\__('Created', 'dropshipping-xml-for-woocommerce'));
        ?>: <strong><?php 
        echo \esc_html($import_file->get_created());
        ?></strong>
								</li>
								<li>
									<?php 
        echo \esc_html(\__('Updated', 'dropshipping-xml-for-woocommerce'));
        ?>: <strong><?php 
        echo \esc_html($import_file->get_updated());
        ?></strong>
								</li>
								<li>
									<?php 
        echo \esc_html(\__('Skipped', 'dropshipping-xml-for-woocommerce'));
        ?>: <strong><?php 
        echo \esc_html($import_file->get_skipped());
        ?></strong>
								</li>
							</ul>
						</td>
						<td class="date column-date"><span title="<?php 
        echo \esc_html($import_file->get_date_created());
        ?>"><?php 
        echo \esc_html($import_file->get_date_created());
        ?></span></td>
					</tr>
				<?php 
    }
    ?>
			<?php 
}
?>

		</tbody>
		<tfoot>
			<tr>
				<td id="cb2" class="manage-column column-cb check-column">
					<label class="screen-reader-text" for="cb-select-all-1"><?php 
echo \esc_html(\__('Select all', 'dropshipping-xml-for-woocommerce'));
?></label>
					<input id="cb-select-all-1" type="checkbox">
				</td>
				<th scope="col" id="id2" class="manage-column column-id desc">
					<span><?php 
echo \esc_html(\__('ID', 'dropshipping-xml-for-woocommerce'));
?></span>
				</th>
				<th scope="col" id="schedule2" class="manage-column column-schedule">
					<span><?php 
echo \esc_html(\__('Cron schedule', 'dropshipping-xml-for-woocommerce'));
?></span>
				</th>
				<th scope="col" id="actions2" class="manage-column column-actions">
					<span><?php 
echo \esc_html(\__('Actions', 'dropshipping-xml-for-woocommerce'));
?></span>
				</th>
				<th scope="col" id="import-status2" class="manage-column column-import-status">
					<span><?php 
echo \esc_html(\__('Status', 'dropshipping-xml-for-woocommerce'));
?></span>
				</th>
				<th scope="col" id="import-stats2" class="manage-column column-import-status">
					<span><?php 
echo \esc_html(\__('Import statistics', 'dropshipping-xml-for-woocommerce'));
?></span>
				</th>
				<th scope="col" id="date2" class="manage-column column-date asc">
					<span><?php 
echo \esc_html(\__('Created', 'dropshipping-xml-for-woocommerce'));
?></span>
				</th>
			</tr>
		</tfoot>
	</table>

	<div class="tablenav bottom">
		<div class="alignleft actions bulkactions">
			<?php 
$form->show_field('manage2');
?>
			<?php 
$form->show_field('apply2');
?>
		</div>
		<div class="alignleft actions"></div>
		<div class="tablenav-pages one-page"><span class="displaying-num"><?php 
echo \esc_html($elements);
?></span><br class="clear"></div>
	</div>
	<?php 
$form->form_fields_complete();
$form->form_end();
$renderer->output_render('Footer');
