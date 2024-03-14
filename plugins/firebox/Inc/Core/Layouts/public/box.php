<?php
/**
 * @package         FireBox
 * @version         2.1.8 Free
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}
$box = $this->data->get('box', []);
$box = new \FPFramework\Libs\Registry($box);

$custom_code = $box->get('params.data.customcode', '');

$close_button = (int) $box->get('params.data.closebutton.show', '');

$rtl = (int) $box->get('params.data.rtl', 0);
?>
<div data-id="<?php echo esc_attr($box->get('ID')); ?>" 
	class="fb-inst fb-hide <?php echo esc_attr(implode(' ', (array) $box->get('classes'))); ?>"
	data-options='<?php echo wp_json_encode($box->get('settings')); ?>'
	data-type='<?php echo esc_attr($box->get('params.data.mode')); ?>'
	<?php if ($rtl == 1) { ?>dir="rtl"<?php } ?>>

	<?php if ($close_button == 2) { firebox()->renderer->public->render('closebutton', ['box' => $this->data->get('box', [])]); } ?>

	<div class="fb-dialog <?php echo esc_attr(implode(' ', (array) $box->get('dialog_classes', []))); ?>" style="<?php echo esc_attr($box->get('style')); ?>" role="dialog" tabindex="-1">
		
		<?php if ($close_button == 1) { firebox()->renderer->public->render('closebutton', ['box' => $this->data->get('box', [])]); } ?>

		<div class="fb-container">
			<div class="fb-content">
				<?php echo $box->get('post_content'); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		</div>
		<?php
		if (is_string($custom_code) && !empty($custom_code))
		{
			$custom_code = html_entity_decode(stripslashes($custom_code));
			wp_add_inline_script('firebox', $custom_code);
		}
		?>
	</div>
</div>
<?php
