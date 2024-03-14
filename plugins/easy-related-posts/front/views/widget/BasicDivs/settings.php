<?php
/**
 * Basic widget template settings.
 *
 * This file will be loaded in widget settings page
 * when basic template is sellected
 *
 * @package   Easy_Related_Posts_Templates_Widget
 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @link      http://erp.xdark.eu
 * @copyright 2014 Panagiotis Vagenas <pan.vagenas@gmail.com>
 */
?>
<label for="<?php echo $widgetInstance->get_field_id('thumbCaption'); ?>">Use thumbnail captions: </label>
<input class="erp-optchbx" id="<?php echo $widgetInstance->get_field_id('thumbCaption'); ?>" name="<?php echo $widgetInstance->get_field_name('thumbCaption'); ?>" type="checkbox" <?php checked((bool) $thumbCaption); ?> />