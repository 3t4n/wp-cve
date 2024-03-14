<?php
defined( 'ABSPATH' ) || exit;
/**
 * Admin Blog Card Page
 *
 * @package YAHMAN Add-ons
 */
function yahman_addons_admin_blog_card($option,$option_key,$option_checkbox){

  foreach ($option_checkbox['blogcard'] as $key => $value  ) {
    $blogcard[$key] = isset($option['blogcard'][$key]) ? true: false;
  }

  ?>

  <div id="ya_blogcard_content" class="tab_content ya_box_design">
    <h2><?php esc_html_e('Blog Card','yahman-add-ons'); ?></h2>


    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="blogcard_internal">
          <?php esc_html_e('Internal site','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Use a blog card for links on your site.','yahman-add-ons'); ?>
          <br>
          <a href="<?php echo esc_url('https://back2nature.jp/plugins/yahman-add-ons/how-to-display-a-blog-card' ); ?>" target="_blank" rel="nofollow noopener noreferrer"><?php esc_html_e('How to use(Japanese)', 'yahman-add-ons'); ?></a>
        </div>
      </div>
      <div class="ya_checkbox">
        <input name="yahman_addons[blogcard][internal]" type="checkbox" id="blogcard_internal"<?php checked(true, $blogcard['internal']); ?> class="ya_checkbox" />
        <label for="blogcard_internal"></label>
      </div>
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="blogcard_external">
          <?php esc_html_e('External site','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Use blog cards to link to other sites.','yahman-add-ons'); ?>
          <br>
          <a href="<?php echo esc_url('https://back2nature.jp/plugins/yahman-add-ons/how-to-display-a-blog-card' ); ?>" target="_blank" rel="nofollow noopener noreferrer"><?php esc_html_e('How to use(Japanese)', 'yahman-add-ons'); ?></a>
        </div>
      </div>
      <div class="ya_checkbox">
        <input name="yahman_addons[blogcard][external]" type="checkbox" id="blogcard_external"<?php checked(true, $blogcard['external']); ?> class="ya_checkbox" />
        <label for="blogcard_external"></label>
      </div>
    </div>


  </div>




  <?php
}
