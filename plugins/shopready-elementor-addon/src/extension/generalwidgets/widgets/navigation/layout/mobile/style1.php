<?php
if (!defined('ABSPATH')) {
  exit;
}
$first_label_indicator_icon = $settings['first_label_indicator_icon'];

if (isset($first_label_indicator_icon['value']) && is_string($first_label_indicator_icon['value'])) {
  $indicator_icon = $first_label_indicator_icon['value'];
}

?>

<div class="woo-ready-mobile-menu-wr" data-indicator="<?php echo esc_attr($indicator_icon); ?>">
    <?php wp_nav_menu($args); ?>
</div>