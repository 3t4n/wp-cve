<?php
defined( 'ABSPATH' ) || exit;
/**
 * Admin PWA
 *
 * @package YAHMAN Add-ons
 */

function yahman_addons_admin_pwa($option,$option_key,$option_checkbox){

  foreach ($option_key['pwa'] as $key => $value  ) {
    $pwa[$key] = $option['pwa'][$key];
  }
  foreach ($option_checkbox['pwa'] as $key => $value  ) {
    $pwa[$key] = isset($option['pwa'][$key]) ? true: false;
  }

  ?>

  <div id="ya_pwa_content" class="tab_content ya_box_design">
    <h2><?php esc_html_e('Progressive Web App(PWA)','yahman-add-ons'); ?></h2>


    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="pwa_enable">
          <?php esc_html_e('Enable','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Convert to PWA.','yahman-add-ons'); ?>
        </div>
      </div>
      <div class="ya_checkbox">
        <input name="yahman_addons[pwa][enable]" type="checkbox" id="pwa_enable"<?php checked(true, $pwa['enable']); ?> class="ya_checkbox" />
        <label for="pwa_enable"></label>
      </div>
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="pwa_manifest">
          <?php esc_html_e('The manifest file','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php echo esc_html__('e.g.&nbsp;', 'yahman-add-ons').esc_html('https://****.com/manifest.json'); ?>
        </div>
      </div>
      <input name="yahman_addons[pwa][manifest]" type="text" id="pwa_manifest" value="<?php echo esc_attr($pwa['manifest']); ?>" class="ya_textbox widefat" />
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="pwa_service_worker">
          <?php esc_html_e('The Service Worker file','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php echo esc_html__('e.g.&nbsp;', 'yahman-add-ons').esc_html('https://****.com/sw.js'); ?>
        </div>
      </div>
      <input name="yahman_addons[pwa][service_worker]" type="text" id="pwa_service_worker" value="<?php echo esc_attr($pwa['service_worker']); ?>" class="ya_textbox widefat" />
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="pwa_theme_color">
          <?php esc_html_e('Theme color','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php echo esc_html__('e.g.&nbsp;', 'yahman-add-ons').esc_html('#5fb17f'); ?>
        </div>
      </div>
      <input name="yahman_addons[pwa][theme_color]" type="text" id="pwa_theme_color" value="<?php echo esc_attr($pwa['theme_color']); ?>" class="ya_textbox" />
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="pwa_apple_touch_icon">
          <?php esc_html_e('apple-touch-icon','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php echo esc_html__('e.g.&nbsp;', 'yahman-add-ons').esc_html('https://****.com/assets/icons/icon-192x192.png'); ?>
        </div>
      </div>
      <input name="yahman_addons[pwa][apple_touch_icon]" type="text" id="pwa_apple_touch_icon" value="<?php echo esc_attr($pwa['apple_touch_icon']); ?>" class="ya_textbox widefat" />
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="pwa_post_not_in">
          <?php esc_html_e('Enter the post ID that does not use this function','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('The post do not loading PWA when input post id','yahman-add-ons'); ?>
          <br>
          <?php echo sprintf( esc_html__('Separate multiple %s with ,(comma).', 'yahman-add-ons') , esc_html__( 'ID', 'yahman-add-ons' ) ); ?>
        </div>
      </div>
      <input name="yahman_addons[pwa][post_not_in]" type="text" id="pwa_post_not_in" value="<?php echo $pwa['post_not_in']; ?>" class="widefat" />
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="pwa_parent_not_in">
          <?php esc_html_e('Enter the ID of the parent page that does not use this function','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('The post do not loading PWA when input post id','yahman-add-ons'); ?>
          <br>
          <?php echo sprintf( esc_html__('Separate multiple %s with ,(comma).', 'yahman-add-ons') , esc_html__( 'ID', 'yahman-add-ons' ) ); ?>
          <br>
          <?php esc_html_e( 'Child pages that belong to the parent page are also included.', 'yahman-add-ons'); ?>
          <br>
        </div>
      </div>
      <input name="yahman_addons[pwa][parent_not_in]" type="text" id="pwa_parent_not_in" value="<?php echo $pwa['parent_not_in']; ?>" class="widefat" />
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="pwa_post_in">
          <?php esc_html_e('Use this feature only for the entered post ID','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php echo sprintf( esc_html__('Separate multiple %s with ,(comma).', 'yahman-add-ons') , esc_html__( 'ID', 'yahman-add-ons' ) ); ?>
        </div>
      </div>
      <input name="yahman_addons[pwa][post_in]" type="text" id="pwa_post_in" value="<?php echo $pwa['post_in']; ?>" class="widefat" />
    </div>







  </div>




  <?php
}
