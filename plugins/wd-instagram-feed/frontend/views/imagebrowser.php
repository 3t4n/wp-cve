<?php

class WDI_ImageBrowser_view {

  private $model;

  public function __construct($model) {
    $this->model = $model;
  }

  public function display() {
    global $user_feed_header_args;
    $this->pass_feed_data_to_js();
    $feed_row = $this->model->get_feed_row();
    $this->add_theme_styles();
    $this->generate_feed_styles($feed_row);
    $style = $this->model->theme_row;
    $wdi_feed_counter = $this->model->wdi_feed_counter;
    $container_class = 'wdi_feed_theme_' . $style['id'] . ' wdi_feed_browser_' . $style['id'];
    $wdi_data_ajax = defined('DOING_AJAX') && DOING_AJAX ? 'data-wdi_ajax=1' : '';
    ?>
    <div id="wdi_feed_<?php echo esc_attr($wdi_feed_counter) ?>" class="wdi_feed_main_container wdi_layout_ib <?php echo esc_attr($container_class); ?>" <?php echo esc_attr($wdi_data_ajax); ?> >
      <?php wdi_feed_frontend_messages();?>
      <div id="wdi_spider_popup_loading_<?php echo esc_attr($wdi_feed_counter) ?>" class="wdi_spider_popup_loading"></div>
      <div id="wdi_spider_popup_overlay_<?php echo esc_attr($wdi_feed_counter) ?>" class="wdi_spider_popup_overlay"
           onclick="wdi_spider_destroypopup(1000)"></div>
      <div class="wdi_feed_container">
        <div class="wdi_feed_info">
          <div id="wdi_feed_<?php echo esc_attr($wdi_feed_counter) ?>_header" class='wdi_feed_header'></div>
          <div id="wdi_feed_<?php echo esc_attr($wdi_feed_counter) ?>_users" class='wdi_feed_users'>
            <?php
            if ( !empty($user_feed_header_args) ) {
              //WDILibrary::user_feed_header_info function returns fully escaped data
              //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
              echo WDILibrary::user_feed_header_info( $user_feed_header_args );
            } ?>
          </div>
        </div>
        <?php
        if ($feed_row['feed_display_view'] === 'pagination' && $style['pagination_position_vert'] === 'top') {
          ?>
          <div id="wdi_pagination" class="wdi_pagination wdi_hidden">
            <div class="wdi_pagination_container">
              <i id="wdi_first_page" title="<?php echo esc_attr(__('First Page', "wd-instagram-feed")) ?>" class="tenweb-i tenweb-i-step-backward wdi_pagination_ctrl wdi_disabled"></i>
              <i id="wdi_prev" title="<?php echo esc_attr(__('Previous Page', "wd-instagram-feed")) ?>" class="tenweb-i tenweb-i-arrow-left wdi_pagination_ctrl"></i>
              <i id="wdi_current_page" class="wdi_pagination_ctrl" style="font-style:normal">1</i>
              <i id="wdi_next" title="<?php echo esc_attr(__('Next Page', "wd-instagram-feed")) ?>" class="tenweb-i tenweb-i-arrow-right wdi_pagination_ctrl"></i>
              <i id="wdi_last_page" title="<?php echo esc_attr(__('Last Page', "wd-instagram-feed")) ?>" class="tenweb-i tenweb-i-step-forward wdi_pagination_ctrl wdi_disabled"></i>
            </div>
          </div>
          <?php
        }
        ?>
        <div class="wdi_feed_wrapper <?php echo esc_attr('wdi_col_' . $feed_row['number_of_columns']) ?>" wdi-res='<?php echo esc_attr('wdi_col_' . $feed_row['number_of_columns']) ?>'></div>
        <div class="wdi_clear"></div>
        <?php
          switch ($feed_row['feed_display_view']) {
            case 'load_more_btn': {
              ?>
              <div class="wdi_load_more">
              <div class="wdi_load_more_container">
                <div class="wdi_load_more_wrap">
                  <div class="wdi_load_more_wrap_inner">
                    <div class="wdi_load_more_text"><?php echo esc_html(__('Load More', "wd-instagram-feed")); ?></div>
                  </div>
                </div>
              </div></div><?php
              break;
            }
            case 'pagination': {
              if ($style['pagination_position_vert'] === 'bottom') {
                ?>
                <div id="wdi_pagination" class="wdi_pagination wdi_hidden">
                  <div class="wdi_pagination_container">
                    <i id="wdi_first_page" title="<?php echo esc_attr(__('First Page', "wd-instagram-feed")) ?>" class="tenweb-i tenweb-i-step-backward wdi_pagination_ctrl wdi_disabled"></i>
                    <i id="wdi_prev" title="<?php echo esc_attr(__('Previous Page', "wd-instagram-feed")) ?>" class="tenweb-i tenweb-i-arrow-left wdi_pagination_ctrl"></i>
                    <i id="wdi_current_page" class="wdi_pagination_ctrl" style="font-style:normal">1</i>
                    <i id="wdi_next" title="<?php echo esc_attr(__('Next Page', "wd-instagram-feed")) ?>" class="tenweb-i tenweb-i-arrow-right wdi_pagination_ctrl"></i>
                    <i id="wdi_last_page" title="<?php echo esc_attr(__('Last Page', "wd-instagram-feed")) ?>" class="tenweb-i tenweb-i-step-forward wdi_pagination_ctrl wdi_disabled"></i>
                  </div>
                </div>
                <?php
              }
              break;
            }
            case 'infinite_scroll': {
              ?>
              <div id="wdi_infinite_scroll" class="wdi_infinite_scroll"></div> <?php
            }
          }
          if ($feed_row['feed_display_view'] === 'pagination') { ?>
            <div class="wdi_page_loading wdi_hidden"><img class="wdi_load_more_spinner" src="<?php echo esc_url(WDI_URL) ?>/images/ajax_loader.png"></div>
            <?php
          }
        ?>
      </div>
      <div class="wdi_front_overlay"></div>
    </div>
    <?php
  }

  public function pass_feed_data_to_js()
  {
    global $wdi_options;
    $feed_row = $this->model->get_feed_row();

    $users = isset($feed_row['feed_users']) ? json_decode($feed_row['feed_users']) : null;
    if($users === null) {
      $users = array();
    }

    $wdi_feed_counter = $this->model->wdi_feed_counter;
    $feed_row['access_token'] = WDILibrary::get_user_access_token($users);
    $feed_row['wdi_feed_counter'] = $wdi_feed_counter;

    wp_localize_script("wdi_frontend", 'wdi_feed_' . $wdi_feed_counter, array('feed_row' => $feed_row, 'data' => array(), 'usersData' => array(), 'dataCount' => 0));
    wp_localize_script("wdi_frontend", 'wdi_theme_' . $this->model->theme_row['id'], $this->model->theme_row);
    wp_localize_script("wdi_frontend", 'wdi_front', array('feed_counter' => $wdi_feed_counter));

    if(WDILibrary::is_ajax() || WDILibrary::elementor_is_active()) {
      wdi_load_frontend_scripts_ajax();
      echo wp_kses('<style id="generate_feed_styles-inline-css">' . $this->generate_feed_styles( $feed_row, TRUE ) .'</style>', array('style' => array('id' => true)));
    }
  }

  private function add_theme_styles(){
    $theme = $this->model->theme_row;

    require_once WDI_DIR . '/framework/WDI_generate_styles.php';
    $generator = new WDI_generate_styles($theme['id'], $theme);

    if($this->load_theme_css_file($generator) === true) {
      return;
    }

    if($generator->all_views_styles(true, true) === true &&
      $this->load_theme_css_file($generator) === true) {
      return;
    }

    echo '<style>' . esc_html($generator->get_css()) . '</style>';
  }

  /**
   * @param $generator WDI_generate_styles
   * @return boolean
   * */
  private function load_theme_css_file($generator){
    $file_url = $generator->get_file_url();
    if($file_url !== "") {
      $theme_path_parts = pathinfo($file_url);

      if(WDILibrary::is_ajax() || WDILibrary::elementor_is_active()) {
        wp_register_style($theme_path_parts['filename'], esc_url_raw($file_url), array(), $generator->get_file_key(), true);
        wp_print_styles($theme_path_parts['filename']);
      }
      else {
        wp_enqueue_style($theme_path_parts['filename'], $file_url . '?key=' . $generator->get_file_key());
      }

      return true;
    } else {
      return false;
    }
  }

  public function generate_feed_styles( $feed_row, $return = FALSE ) {
    $style = $this->model->theme_row;
    $colNum = (100 / $feed_row['number_of_columns']);
    $wdi_feed_counter = $this->model->wdi_feed_counter;

    ob_start();
    ?>
      #wdi_feed_<?php echo esc_attr($wdi_feed_counter) ?> .wdi_feed_header {
        display: <?php echo (esc_html(($feed_row['display_header']=='1') ? 'block' : 'none'))?>; /*if display-header is true display:block*/
      }

      <?php
      if($feed_row['display_user_post_follow_number'] == '1'){
        $header_text_padding =(intval($style['user_img_width']) - intval($style['users_text_font_size']))/4;
      }
      else{
        $header_text_padding =(intval($style['user_img_width']) - intval($style['users_text_font_size']))/2;
      }
      ?>
      #wdi_feed_<?php echo esc_attr($wdi_feed_counter)?> .wdi_header_user_text {
        padding-top: <?php echo esc_html($header_text_padding); ?>px;

      }

      #wdi_feed_<?php echo esc_attr($wdi_feed_counter) ?> .wdi_header_user_text h3 {
        margin-top: <?php echo esc_html($header_text_padding) ?>px;
      }

      #wdi_feed_<?php echo esc_attr($wdi_feed_counter)?> .wdi_media_info {
        display: <?php echo (esc_html($feed_row['display_user_post_follow_number'] == '1') ? 'block' : 'none'); ?>
      }

      #wdi_feed_<?php echo esc_attr($wdi_feed_counter) ?> .wdi_feed_item {
        width: <?php echo esc_html($colNum).'%'?>; /*thumbnail_size*/
      }

      <?php if($feed_row['disable_mobile_layout']=="0") { ?>
      @media screen and (min-width: 800px) and (max-width: 1024px) {
        #wdi_feed_<?php echo esc_attr($wdi_feed_counter)?> .wdi_feed_item {
          width: <?php echo (esc_html($colNum<33.33 ? '33.333333333333%' : $colNum.'%'))?>; /*thumbnail_size*/
          margin: 0;
          display: inline-block;
          vertical-align: top;
          overflow: hidden;
        }

        #wdi_feed_<?php echo esc_attr($wdi_feed_counter)?> .wdi_feed_container {
          width: 100%;
          margin: 0 auto;
          background-color: <?php echo esc_html($style['feed_container_bg_color'])?>; /*feed_container_bg_color*/
        }
      }

      @media screen and (min-width: 480px) and (max-width: 800px) {
        #wdi_feed_<?php echo esc_attr($wdi_feed_counter)?> .wdi_feed_item {
          width: <?php echo (esc_html($colNum)<50) ? '50%' : esc_html($colNum).'%'?>; /*thumbnail_size*/
          margin: 0;
          display: inline-block;
          overflow: hidden;
        }

        #wdi_feed_<?php echo esc_attr($wdi_feed_counter)?> .wdi_feed_container {
          width: 100%;
          margin: 0 auto;
          background-color: <?php echo esc_html($style['feed_container_bg_color'])?>; /*feed_container_bg_color*/
        }
      }

      @media screen and (max-width: 480px) {
        #wdi_feed_<?php echo esc_attr($wdi_feed_counter)?> .wdi_feed_item {
          width: <?php echo (esc_html($colNum<100 ? '100%' : esc_html($colNum).'%'))?>; /*thumbnail_size*/
          margin: 0;
          display: inline-block;
          overflow: hidden;
        }

        #wdi_feed_<?php echo esc_attr($wdi_feed_counter)?> .wdi_feed_container {
          width: 100%;
          margin: 0 auto;
          background-color: <?php echo esc_html($style['feed_container_bg_color'])?>; /*feed_container_bg_color*/
        }
      }
    <?php }
    $css = ob_get_contents();
    ob_end_clean();
    if ( $return ) {
      return $css;
    }
    wp_register_style( 'generate_feed_styles', false );
    wp_enqueue_style( 'generate_feed_styles' );
    wp_add_inline_style( 'generate_feed_styles', $css );
  }
}