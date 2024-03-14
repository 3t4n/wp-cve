<?php
defined( 'ABSPATH' ) || exit;
/**
 * Admin Site map Page
 *
 * @package YAHMAN Add-ons
 */
function yahman_addons_admin_sitemap($option,$option_key,$option_checkbox){

  foreach ($option_key['sitemap'] as $key => $value  ) {
    $sitemap[$key] = $option['sitemap'][$key];
  }
  foreach ($option_checkbox['sitemap'] as $key => $value  ) {
    $sitemap[$key] = isset($option['sitemap'][$key]) ? true: false;
  }

  ?>

  <div id="ya_sitemap_content" class="tab_content ya_box_design">
    <h2><?php esc_html_e('Site map','yahman-add-ons'); ?></h2>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="sitemap_enable">
          <?php esc_html_e('Enable','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Display the site map on the page.','yahman-add-ons'); ?>
          <br>
          <?php esc_html_e('Create an empty page.','yahman-add-ons'); ?>
        </div>
      </div>
      <div class="ya_checkbox">
        <input name="yahman_addons[sitemap][enable]" type="checkbox" id="sitemap_enable"<?php checked(true, $sitemap['enable']); ?> class="ya_checkbox" />
        <label for="sitemap_enable"></label>
      </div>
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="sitemap_slug"><?php esc_html_e('Page slug','yahman-add-ons'); ?></label>
        <div class="ya_tooltip">
          <?php esc_html_e( 'Enter the slug of the page that displays the sitemap.', 'yahman-add-ons'); ?>
          <br>
          <?php echo esc_html__('e.g.&nbsp;', 'yahman-add-ons').' sitemap'; ?>
        </div>
      </div>
      <input name="yahman_addons[sitemap][slug]" type="text" id="sitemap_slug" value="<?php echo esc_html($sitemap['slug']); ?>" class="ya_textbox" />
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="sitemap_exclude"><?php esc_html_e('Enter the post ID that does not use this function','yahman-add-ons'); ?></label>
        <div class="ya_tooltip">
          <?php esc_html_e( 'Please enter the post ID that will not be displayed on the site map.', 'yahman-add-ons'); ?>
          <br>
          <?php echo sprintf( esc_html__('Separate multiple %s with ,(comma).', 'yahman-add-ons') , esc_html__( 'ID', 'yahman-add-ons' ) ); ?>
        </div>
      </div>
      <input name="yahman_addons[sitemap][exclude]" type="text" id="sitemap_exclude" value="<?php echo $sitemap['exclude']; ?>" class="widefat" />
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="sitemap_exclude_tree"><?php esc_html_e('Enter the ID of the parent page that does not use this function','yahman-add-ons'); ?></label>
        <div class="ya_tooltip">
          <?php echo sprintf( esc_html__('Separate multiple %s with ,(comma).', 'yahman-add-ons') , esc_html__( 'ID', 'yahman-add-ons' ) ); ?>
          <br>
          <?php esc_html_e( 'Child pages that belong to the parent page are also included.', 'yahman-add-ons'); ?>
          <br>
        </div>
      </div>
      <input name="yahman_addons[sitemap][exclude_tree]" type="text" id="sitemap_exclude_tree" value="<?php echo $sitemap['exclude_tree']; ?>" class="widefat" />
    </div>


  </div>




  <?php
}
