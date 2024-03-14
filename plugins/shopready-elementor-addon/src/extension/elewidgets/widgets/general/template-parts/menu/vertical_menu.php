<?php
if (!defined('ABSPATH')) {
    exit;
}
use \Elementor\Icons_Manager;

/**
 * Vertical Menu
 * Footer
 * @since 1.0
 */

    $menu_list = $settings['list'];
 
?>

<ul class="woo-ready-footer-vertical-menu">
    <?php foreach( $menu_list as $item ):  ?>
    <li class="woo-ready-menu-item position:relative elementor-repeater-item-<?php echo esc_attr($item['_id']); ?> ">
        <?php if($item['icon_position'] == 'yes'): ?>
        <?php wp_kses_post( is_null( $item['menu_icon'] ) ? '' : Icons_Manager::render_icon( $item['menu_icon'], [ 'class' => 'menu-icon' ] ) ); ?>
        <?php endif; ?>
        <a href="<?php echo esc_url($item['website_link']['url']); ?>">
            <span class="wready-menu-title"> <?php echo esc_html($item['list_title']); ?> </span>
        </a>
        <?php if($item['icon_position'] == ''): ?>
        <?php wp_kses_post(is_null( $item['menu_icon'] ) ? '' : Icons_Manager::render_icon( $item['menu_icon'], [ 'class' => 'menu-icon' ] )); ?>
        <?php endif; ?>
        <?php echo wp_kses_post(sprintf("<span class='bedge'> %s </span>", esc_html($item['menu_bedge']))) ?>
    </li>
    <?php endforeach; ?>
</ul>