<?php
defined( 'ABSPATH' ) || exit;
/**
 * Admin Pageview Page
 *
 * @package YAHMAN Add-ons
 */

function yahman_addons_admin_page_view($option,$option_key,$option_checkbox){

  foreach ($option_checkbox['pv'] as $key => $value  ) {
    $pv[$key] = isset($option['pv'][$key]) ? true: false;
  }

  ?>

  <div id="ya_pv_content" class="tab_content ya_box_design">
    <h2><?php esc_html_e('Pageview','yahman-add-ons'); ?></h2>

    <div class="ya_hr"></div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="pv_enable">
          <?php esc_html_e('Count of Pageview','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip"><?php esc_html_e('Records the number of visits to post and pages.','yahman-add-ons'); ?></div>
      </div>
      <div class="ya_checkbox">
        <input type="checkbox" name="yahman_addons[pv][enable]" id="pv_enable"<?php checked(true, $pv['enable']); ?>>
        <label for="pv_enable"></label>
      </div>
    </div>

    <div class="ya_hr"></div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="pv_count_reset">
          <?php esc_html_e('Reset the pageview count','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip"><?php echo sprintf( esc_html__('Multiple %s must be separated by a comma.', 'yahman-add-ons') , esc_html__( 'ID', 'yahman-add-ons' ) ); ?></div>
      </div>
      <div class="ya_flex ya_ai_c">
        <label for="pv_count_reset" class="ya_mr8"><?php esc_html_e('Post ID','yahman-add-ons'); ?></label>
        <input name="yahman_addons_reset[pv][count]" type="text" id="pv_count_reset" value="" class="widefat ya_flex1" />
      </div>
    </div>

    <div class="ya_hr"></div>

    <label class="ya_link_color" for="ya_widget" onclick="to_top();">
      <?php esc_html_e('There are related widgets.','yahman-add-ons'); ?><br>
      &rsaquo; <?php esc_html_e('Pageview widget','yahman-add-ons'); ?><br>
      &rsaquo; <?php esc_html_e('Popular Post widget','yahman-add-ons'); ?>
    </label>


  </div>




  <?php
}
