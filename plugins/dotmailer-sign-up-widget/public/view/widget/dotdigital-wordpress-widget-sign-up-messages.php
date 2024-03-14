<?php

namespace Dotdigital_WordPress_Vendor;

/**
 * Print out widget success/error messages.
 *
 * @package    Dotdigital_WordPress
 *
 * @var \Dotdigital_WordPress\Includes\Widget\Dotdigital_WordPress_Sign_Up_Widget $widget
 * @var string $dd_widget_id
 */
$messages = get_option(\Dotdigital_WordPress\Includes\Setting\Dotdigital_WordPress_Config::SETTING_MESSAGES_PATH);
?>
	<div class="form_messages">
		<?php 
if ($widget->get_message($dd_widget_id)) {
    ?>
		<p class='<?php 
    echo esc_attr($widget->get_message_class($dd_widget_id));
    ?>'><?php 
    echo esc_html($widget->get_message($dd_widget_id));
    ?></p>
		<?php 
}
?>
	</div>
<?php 
