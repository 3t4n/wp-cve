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
$allowed_tags = [
	'a' => [ 'href' => true, 'target' => true ],
	'i' => [ 'class' => true ],
	'em' => [ 'class' => true ],
	'b' => true,
	'strong' => [ 'class' => true ]
];
?>
<div class="pro-only-body text-center">
    <span class="dashicons dashicons-lock icon-lock"></span>

    <!-- This is shown when we click on a Pro only feature button -->
    <div class="po-feature">
        <h2><?php echo wp_kses(fpframework()->_('FPF_PRO_MODAL_IS_PRO_FEATURE'), $allowed_tags); ?></h2>
        <p><?php echo wp_kses(fpframework()->_('FPF_PRO_MODAL_WERE_SORRY'), $allowed_tags); ?></p>
    </div>

    <!-- This is shown when click on Upgrade to Pro button -->
    <div class="po-upgrade" style="display: none;">
        <h2><span data-text="{{PLUGIN_NAME}}"></span> <?php esc_html_e(fpframework()->_('FPF_PRO')); ?></h2>
        <p data-text="<?php echo wp_kses(fpframework()->_('FPF_UPGRADE_TO_PRO_VERSION'), $allowed_tags); ?>"></p>
    </div>

    <p><a class="fpf-button upgrade large" data-href="<?php echo esc_url(FPF_SITE_URL); ?>{{PLUGIN_SLUG}}/upgrade?coupon=FREE2PRO&amp;utm_source=Wordpress&amp;utm_medium=upgradebutton&amp;utm_campaign=freeversion" href="#" target="_blank"><?php echo esc_html(fpframework()->_('FPF_UPGRADE_TO_PRO')); ?></a></p>
    <div class="pro-only-bonus" data-text="<?php echo htmlentities(wp_kses(sprintf(fpframework()->_('FPF_PRO_MODAL_PERCENTAGE_OFF'), '{{PLUGIN_NAME}}'), $allowed_tags)); ?>"></div>

    <div class="pro-only-footer">
        <div data-text="<?php echo htmlentities(wp_kses(sprintf(fpframework()->_('FPF_PRO_MODAL_PRESALES_QUESTIONS'), esc_url(FPF_SUPPORT_URL) . '?topic=Pre-sale Question&amp;plugin={{PLUGIN_NAME}}'), $allowed_tags)); ?>"></div>
        <div><?php echo wp_kses(sprintf(fpframework()->_('FPF_PRO_MODAL_UNLOCK_PRO_FEATURES'), esc_url(FPF_SITE_URL) . 'docs/general/plugins/upgrade-to-pro/'), $allowed_tags); ?></div>
    </div>
</div>