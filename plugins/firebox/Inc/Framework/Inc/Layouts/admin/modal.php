<?php
/**
 * @package         FirePlugins Framework
 * @version         1.1.94
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
$allowed_tags = \FPFramework\Helpers\WPHelper::getAllowedHTMLTags();
$closeButton = $this->data->get('closeButton', true);
$footer = $this->data->get('footer', '');

$id = $this->data->get('id', '');
$attr_id = !empty($id) ? ' id="' . esc_attr($id) . '"' : '';
$class = $this->data->get('class', []);
if ($id)
{
	$class[] = 'fpf-modal-' . $id;
}
$class = count($class) ? ' ' . implode(' ', $class) : '';

$width = $this->data->get('width', '');
$height = $this->data->get('height', '');

$modal_body_class = $this->data->get('modal_body_class', []);
$modal_body_class = count($modal_body_class) ? ' ' . implode(' ', $modal_body_class) : '';

$overlay_click = $this->data->get('overlay_click', true);
?>
<div class="fpf-content-wrapper fpf-modal<?php echo esc_attr($class); ?>">
	<div class="modal viewport-width<?php echo esc_attr($width); ?>"<?php echo wp_kses_data($attr_id); ?>>
		<div class="modal-content">
			<div class="modal-header">
				<div class="modal-title-wrapper">
					<?php do_action('fpframework/modal/' . esc_attr($id) . '/header/before_title'); ?>
					<h3 class="modal-title"><?php echo fpframework()->_($this->data->get('title')); ?></h3>
				</div>
				<?php do_action('fpframework/modal/' . esc_attr($id) . '/header/middle'); ?>
				<div class="actions-wrapper">
					<ul class="actions">
						<?php
						do_action('fpframework/modal/' . esc_attr($id) . '/header/prepend_actions');
						if ($closeButton)
						{
							?>
							<li>
								<a href="#" class="fpf-modal-close">
									<svg height="14" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
										<rect x="14" y="12.5933" width="2.47487" height="17.3241" transform="rotate(135 14 12.5933)" fill="currentColor"/>
										<rect width="2.47487" height="17.3241" transform="matrix(-0.707109 -0.707105 0.707109 -0.707105 1.75 14.3433)" fill="currentColor"/>
									</svg>
								</a>
							</li>
							<?php
						}
						?>
					</ul>
				</div>
			</div>
			<div class="modal-body viewport-height<?php echo esc_attr($height . $modal_body_class); ?>"><?php echo wp_kses($this->data->get('content', ''), $allowed_tags); ?></div>
			<?php if (!empty($footer)): ?>
			<div class="modal-footer"><?php echo wp_kses($footer, $allowed_tags); ?></div>
			<?php endif; ?>
		</div>
	</div>
	<div class="overlay" data-on-overlay-click="<?php echo esc_attr($overlay_click); ?>"></div>
</div>