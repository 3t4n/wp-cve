<div class="is-<?php echo esc_attr($size);?> <?php echo esc_attr($has_image); ?>">
    <?php echo esc_attr($image_html); ?>
    <strong><?php echo esc_attr($name); ?></strong>
</div>
<?php 
$argsmain = array(
    'menu'              => $nav,
    'menu_class'    => "menu ".$ul_class,
    'container'      => false,
    'items_wrap'        => '<ul id="%1$s" class="%2$s">%3$s</ul>',
    'walker'         => '',
    'add_li_class'  => '',
);
if($toggle=='yes'){
    $argsmain ['add_li_class'] = 'active';
}
wp_nav_menu($argsmain);