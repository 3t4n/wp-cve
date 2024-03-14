<?php 
global $bizcor_options;
$topbar_disable = get_theme_mod('bizcor_topbar_disable',$bizcor_options['bizcor_topbar_disable']);
$topbar_texts = bizcor_header_topbar_data();
$topbar_icons = bizcor_header_topbar_icons_data();
$topbar_icons_target = get_theme_mod('bizcor_topbar_icons_target',$bizcor_options['bizcor_topbar_icons_target']);
if($topbar_disable==false){
?>
<div class="row-top">
    <div class="container">
        <div class="row">
            <div class="col-6 my-auto">
                <div class="main-menu-right main-left">
                    <ul class="menu-right-list">
                        <?php 
                        if(!empty($topbar_texts)) { 
                            foreach ($topbar_texts as $text) {
                        ?>
                        <li class="content-list">
                            <aside class="widget widget-contact first">
                                <div class="contact-area">
                                    <div class="contact-info">
                                        <p class="top-text"><?php echo wp_kses_post($text['text']); ?></p>
                                    </div>
                                </div>
                            </aside>
                        </li>
                        <?php } } ?>
                    </ul>
                </div>
            </div>
            <div class="col-6 my-auto">
                <div class="main-menu-right main-right">
                    <ul class="menu-right-list">
                        <li class="social-list">
                            <aside class="widget widget_social">
                                <?php 
                                if(!empty($topbar_icons)) { 
                                    foreach ($topbar_icons as $icon) {
                                ?>
                                <div class="circle">
                                    <a href="<?php echo esc_url($icon['link']); ?>" <?php if($topbar_icons_target==true){ echo 'target="_blank"'; } ?>><i class="<?php echo esc_attr($icon['icon']); ?>"></i></a>
                                </div>
                                <?php } } ?>
                            </aside>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>