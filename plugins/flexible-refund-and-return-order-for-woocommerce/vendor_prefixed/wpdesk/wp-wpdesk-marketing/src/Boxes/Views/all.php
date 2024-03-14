<?php

namespace FRFreeVendor;

/**
 * @var array $boxes
 * @var \WPDesk\Library\Marketing\Boxes\BoxRenderer $plugin
 * @var \WPDesk\Library\Marketing\Boxes\Helpers\BBCodes $bbcodes
 * @var \WPDesk\Library\Marketing\Boxes\Helpers\Markers $markers
 * @var \WPDesk\View\Renderer\Renderer $renderer
 */
?>
<div class="wpdm-box-wrapper">
	<?php 
foreach ($boxes as $box) {
    $box = $plugin->get_box_type($box);
    $type = $box->get_type();
    if ($box->get_row_open()) {
        $renderer->output_render('row_open');
    }
    ?>
		<div class="col-xs">
			<?php 
    echo \wp_kses_post($box->render(['bbcodes' => $bbcodes, 'markers' => $markers]));
    ?>
		</div>
		<?php 
    if ($box->get_row_close()) {
        $renderer->output_render('row_close');
    }
    ?>
		<?php 
}
?>
</div>
<?php 
