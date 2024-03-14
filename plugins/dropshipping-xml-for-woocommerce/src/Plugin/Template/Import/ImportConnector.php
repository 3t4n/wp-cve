<?php
/**
 * @var string $title
 * @var string $mode
 * @var bool $edit
 * @var \WPDesk\Library\DropshippingXmlCore\Infrastructure\View\FormView $form
 * @var WPDesk\View\Renderer\Renderer $renderer
 */

$renderer->output_render( 'Header', [ 'title' => $title ] );
?>

<h2>
<?php
// TRANSLATORS: %s: page step.
echo esc_html( sprintf( __( 'Step %s', 'dropshipping-xml-for-woocommerce' ), '1/4' ) );
?>
</h2>
<p><?php echo wp_kses_post( __( 'XML or CSV file import. Import a CSV or XML file using a link. Learn more about the import process and <b>Read more in the</b> <a href="https://wpde.sk/dropshipping-import" class="docs-url" target="_blank">plugin documentation &rarr;</a>', 'dropshipping-xml-for-woocommerce' ) ); ?></p>
<hr>
<?php
$form->form_start(
	[
		'method'   => 'POST',
		'template' => 'form-start-no-table',
	]
);
?>
	<table class="form-table import-data-table">
	<tbody>
<?php $form->show_field( 'file_url', [ 'parent_template' => 'form-field' ] ); ?>
	<tr>
		<td class="forminp">
			<?php $form->show_field( 'import' ); ?>
			<img id="droppshiping-import-loader" class="to-right" style="display: none" src="<?php echo esc_url( includes_url() . 'js/tinymce/skins/lightgray/img/loader.gif' ); ?>">
		</td>
	</tr>
	</tbody>
	</table>
<?php
$renderer->output_render(
	'Steps',
	[
		'mode' => $mode,
		'edit' => $edit,
		'form' => $form,
	]
);
?>
<input type="hidden" name="file_mode" id="file_mode" value="<?php echo wp_kses_post( $mode ); ?>">
<?php $form->form_fields_complete(); ?>
<?php $form->form_end( [ 'template' => 'form-end-no-table' ] ); ?>

<?php
$renderer->output_render( 'Footer' );
