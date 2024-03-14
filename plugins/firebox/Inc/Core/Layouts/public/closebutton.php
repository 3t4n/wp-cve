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
$btnSource = is_string($this->data->get('box.params.data.closebutton.source', 'icon')) ? $this->data->get('box.params.data.closebutton.source', 'icon') : 'icon';
$size      = is_string($this->data->get('box.params.data.closebutton.size', null)) || is_int($this->data->get('box.params.data.closebutton.size', null)) ? (int) $this->data->get('box.params.data.closebutton.size', null) : null;
?>
<button type="button" data-fbox-cmd="close" class="fb-close" aria-label="Close">
	<?php if ($btnSource == "image") { ?>
		<img src="<?php echo esc_url($this->data->get('box.params.data.closebutton.image', '')); ?>"/>
	<?php } else { ?>
		<svg width="<?php esc_attr_e($size); ?>" height="<?php esc_attr_e($size); ?>" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"><mask id="mask0_2255_1643" style="mask-type:alpha" maskUnits="userSpaceOnUse" x="0" y="0" width="32" height="32"><rect width="32" height="32" fill="#D9D9D9"/></mask><g mask="url(#mask0_2255_1643)"><path d="M9.6 24L8 22.4L14.4 16L8 9.6L9.6 8L16 14.4L22.4 8L24 9.6L17.6 16L24 22.4L22.4 24L16 17.6L9.6 24Z" fill="currentColor"/></g></svg>
	<?php } ?>
</button>