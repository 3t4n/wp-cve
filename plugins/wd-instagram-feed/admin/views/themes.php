<?php

class Themes_view_wdi {

  public function display() {
    WDILibrary::topbar();
    ?>
    <div class="wrap">
      <div class="wdi_pro_notice"> <?php _e("This is free version, Customizing themes is available only in premium version","wd-instagram-feed"); ?> </div>
      <?php
        $this->buildFreeThemeDemo();
      ?>
    </div>
    <?php
  }

  private function buildFreeThemeDemo(){
    ?>
    <div class="wdi_demo_img" demo-tab="general"><img src="<?php echo esc_url(WDI_URL) . '/demo_images/1.png'; ?>" alt=""></div>
    <div class="wdi_demo_img" demo-tab="header"><img src="<?php echo esc_url(WDI_URL) . '/demo_images/2.png'; ?>" alt=""></div>
    <div class="wdi_demo_img" demo-tab="load_more"><img src="<?php echo esc_url(WDI_URL) . '/demo_images/3.png'; ?>" alt=""></div>
    <div class="wdi_demo_img" demo-tab="thumbnails"><img src="<?php echo esc_url(WDI_URL) . '/demo_images/4.png'; ?>" alt=""></div>
    <div class="wdi_demo_img" demo-tab="masonry"><img src="<?php echo esc_url(WDI_URL) . '/demo_images/5.png'; ?>" alt=""></div>
    <div class="wdi_demo_img" demo-tab="blog_style"><img src="<?php echo esc_url(WDI_URL) . '/demo_images/6.png'; ?>" alt=""></div>
    <div class="wdi_demo_img" demo-tab="image_browser"><img src="<?php echo esc_url(WDI_URL) . '/demo_images/7.png'; ?>" alt=""></div>
    <div class="wdi_demo_img" demo-tab="image_browser"><img src="<?php echo esc_url(WDI_URL) . '/demo_images/8.png'; ?>" alt=""></div>
    <div class="wdi_demo_img" demo-tab="lb_general"><img src="<?php echo esc_url(WDI_URL) . '/demo_images/l1.png'; ?>" alt=""></div>
    <div class="wdi_demo_img" demo-tab="lb_ctrl_btns"><img src="<?php echo esc_url(WDI_URL) . '/demo_images/l2.png'; ?>" alt=""></div>
    <div class="wdi_demo_img" demo-tab="lb_close_btn"><img src="<?php echo esc_url(WDI_URL) . '/demo_images/l3.png'; ?>" alt=""></div>
    <div class="wdi_demo_img" demo-tab="lb_nav_btns"><img src="<?php echo esc_url(WDI_URL) . '/demo_images/l4.png'; ?>" alt=""></div>
    <div class="wdi_demo_img" demo-tab="lb_filmstrip"><img src="<?php echo esc_url(WDI_URL) . '/demo_images/l5.png'; ?>" alt=""></div>
    <div class="wdi_demo_img" demo-tab="lb_info"><img src="<?php echo esc_url(WDI_URL) . '/demo_images/l6.png'; ?>" alt=""></div>
    <div class="wdi_demo_img" demo-tab="lb_comments"><img src="<?php echo esc_url(WDI_URL) . '/demo_images/l7.png'; ?>" alt=""></div>
    <?php
  }
}