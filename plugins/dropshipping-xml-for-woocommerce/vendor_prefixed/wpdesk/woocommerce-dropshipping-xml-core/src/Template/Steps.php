<?php

namespace DropshippingXmlFreeVendor;

/**
 * @var \WPDesk\Library\DropshippingXmlCore\Infrastructure\View\FormView $form
 * @var string $mode mode page
 * @var bool $edit edit page
 * @var null|string $previous_step previous_step url
 */
?>
<hr>
<table class="form-table">
	<tbody>
	<tr valign="top">
		<td class="forminp txt-center">
			<?php 
if ($mode === 'edit') {
    ?>
				<div class="hidden"><?php 
    $form->show_field('next_step');
    ?></div>
				<input class="button button-primary button-hero" name="next_step" id="next_step" type="submit" value="<?php 
    echo \esc_html(\__('Save and go back', 'dropshipping-xml-for-woocommerce'));
    ?>">
			<?php 
} else {
    ?>
				<?php 
    if (isset($previous_step) && $previous_step) {
        ?>
					<a href="<?php 
        echo \esc_url($previous_step);
        ?>" id="previous_step" class="button button-hero"
					   name="button button-hero"><?php 
        echo \esc_html(\__('&larr; Go to the previous step', 'dropshipping-xml-for-woocommerce'));
        ?></a>
				<?php 
    }
    ?>
				<?php 
    if ($form->has_field('next_step')) {
        $form->show_field('next_step');
    }
    ?>
			<?php 
}
?>
		</td>
	</tr>
	</tbody>
</table>
<?php 
