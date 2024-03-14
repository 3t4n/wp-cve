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
$class = $this->data->get('class') ? ' ' . $this->data->get('class') : '';
$show_overlay_message = (bool) $this->data->get('show_overlay_message', false);
$feature = $this->data->get('feature');
$image = $this->data->get('image', '');
$plugin = $this->data->get('plugin');
$message = $this->data->get('message') ? $this->data->get('message') : 'FPF_FEATURE_IMAGE_UPGRADE_PRO_MSG1';

$allowed_tags = [
    'strong' => true
];
?>
<div class="fpf-free-upgrade-img-container<?php echo esc_attr($class); ?>">
    <div class="image">
        <img src="<?php echo esc_url($image); ?>" alt="fireplugins pro feature upgrade to pro to unlock" />
        <div class="inner">
            <?php
            if ($show_overlay_message)
            {
                ?>
                <div class="inner-bg">
                    <p><?php echo wp_kses(sprintf(fpframework()->_($message), $feature), $allowed_tags); ?></p>
                    <p><?php echo esc_html(fpframework()->_('FPF_FEATURE_IMAGE_UPGRADE_PRO_MSG2')); ?></p>
                <?php
            }

            echo \FPFramework\Helpers\HTML::renderProButton(fpframework()->_('FPF_UPGRADE_TO_PRO'), $feature, $plugin);
            
            if ($show_overlay_message)
            {
                ?>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>