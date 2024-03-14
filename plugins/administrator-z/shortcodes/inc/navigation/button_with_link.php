<div class="nav-head">
    <div class="navhead is-<?php echo esc_attr($size);?> <?php echo esc_attr($has_image); ?>">
        <?php echo esc_attr($image_html); ?>
        <strong><?php echo esc_attr($name); ?></strong>
    </div> 
    <a href="<?php echo esc_attr($menu_mobile_link)? esc_attr($menu_mobile_link) : "#" ; ?> ">
        <?php echo esc_attr($menu_mobile_link_text)? "<small>".esc_attr($menu_mobile_link_text)."</small>"." " : ""; ?>
        <i class="icon-angle-right" style="vertical-align: middle;"></i>
    </a>
</div>
<style type="text/css">#<?php echo esc_attr($id); ?> .header-nav{width: unset; margin-left: auto;}</style>