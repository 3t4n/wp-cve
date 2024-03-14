<?php
defined( 'ABSPATH' ) || exit;
/**
 * Admin OGP Page
 *
 * @package YAHMAN Add-ons
 */
function yahman_addons_admin_seo($option,$option_key,$option_checkbox){

  foreach ($option_key['ogp'] as $key => $value  ) {
    $ogp[$key] = $option['ogp'][$key];
  }
  foreach ($option_checkbox['ogp'] as $key => $value  ) {
    $ogp[$key] = isset($option['ogp'][$key]) ? true: false;
  }
  foreach ($option_key['json'] as $key => $value  ) {
    $json[$key] = $option['json'][$key];
  }
  foreach ($option_checkbox['json'] as $key => $value  ) {
    $json[$key] = isset($option['json'][$key]) ? true: false;
  }
  foreach ($option_checkbox['header'] as $key => $value  ) {
    $header[$key] = isset($option['header'][$key]) ? true: false;
  }

  ?>

  <div id="ya_seo_content" class="tab_content ya_box_design">
    <h2><?php esc_html_e('SEO','yahman-add-ons'); ?></h2>

    <h3><?php esc_html_e('Meta Data', 'yahman-add-ons'); ?></h3>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="ogp_meta">
          <?php esc_html_e('Open Graph protocol(OGP)','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Add the Open Graph protocol to the meta tag.','yahman-add-ons'); ?>
        </div>
      </div>
      <div class="ya_checkbox">
        <input name="yahman_addons[ogp][meta]" type="checkbox" id="ogp_meta"<?php checked(true, $ogp['meta']); ?> class="ya_checkbox" />
        <label for="ogp_meta"></label>
      </div>
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="select_ogp_image">
          <?php esc_html_e('OGP Image','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Use this image if you don\'t have thumbnails or for OGP images on your home page.','yahman-add-ons'); ?>
        </div>
      </div>
      <div class="ogp_image" style="width: 100%; max-width:320px; height:auto;">
        <div class="ogp_image_id_placeholder" style="width: 100%; position: relative; text-align: center; cursor: default;border: 1px dashed #b4b9be;box-sizing: border-box;padding: 9px 0;line-height: 20px; margin: 10px 0;<?php if( !empty( $ogp['image'] ) ){echo 'display:none;';} ?>"><?php esc_html_e( 'No image selected', 'yahman-add-ons' ); ?></div>
        <img class="ogp_image_id_media_image custom_media_image" src="<?php if( !empty( $ogp['image'] ) ){echo esc_url($ogp['image']);} ?>" style="width: 100%; max-width: 120px; height:auto; margin-bottom: 10px;" />

      </div>
      <input type="hidden" type="text" class="ogp_image_id_media_id custom_media_id" name="yahman_addons[ogp][image_id]" id="ogp_image_id" value="<?php echo esc_attr($ogp['image_id']); ?>" />

      <input type="hidden" type="text" class="ogp_image_id_media_url custom_media_url" name="yahman_addons[ogp][image]" id="ogp_image_url" value="<?php echo esc_url($ogp['image']); ?>" >
      <input type="button" value="<?php esc_html_e( 'Clear Image', 'yahman-add-ons' ); ?>" class="button ogp_image_id_remove-button custom_media_clear" data-media_clear="ogp_image_id" style="<?php if( !empty( $ogp['image'] ) ){echo 'display:inline-block;';}else{echo 'display:none;';} ?>" />
      <input id="select_ogp_image" type="button" value="<?php esc_html_e( 'Select Image', 'yahman-add-ons' ); ?>" class="button upload-button custom_media_upload" data-media_select="ogp_image_id"/>
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="ogp_twitter_card">
          <?php esc_html_e('Twitter Card','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Add a Twitter Card to the meta tag.','yahman-add-ons'); ?>
        </div>
      </div>
      <select name="yahman_addons[ogp][twitter_card]" id="ogp_twitter_card">
        <option value="false"<?php selected( $ogp['twitter_card'], 'false' ); ?>><?php esc_html_e('Disable', 'yahman-add-ons'); ?></option>
        <option value="summary"<?php selected( $ogp['twitter_card'], 'summary' ); ?>><?php esc_html_e('Summary Card', 'yahman-add-ons'); ?></option>
        <option value="summary_large_image"<?php selected( $ogp['twitter_card'], 'summary_large_image' ); ?>><?php esc_html_e('Summary Card with Large Image', 'yahman-add-ons'); ?></option>
      </select>
    </div>
    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="header_meta_thum">
          <?php esc_html_e('Add thumbnail','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Add thumbnails to the meta tag.','yahman-add-ons'); ?>
        </div>
      </div>
      <div class="ya_checkbox">
        <input name="yahman_addons[header][meta_thum]" type="checkbox" id="header_meta_thum"<?php checked(true, $header['meta_thum']); ?> class="ya_checkbox" />
        <label for="header_meta_thum"></label>
      </div>
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="header_meta_description">
          <?php esc_html_e('Add description','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Add a description to the meta tag.','yahman-add-ons'); ?>
          <br>
          <?php esc_html_e('An input area is added to the post page.','yahman-add-ons'); ?>
        </div>
      </div>
      <div class="ya_checkbox">
        <input name="yahman_addons[header][meta_description]" type="checkbox" id="header_meta_description"<?php checked(true, $header['meta_description']); ?> class="ya_checkbox" />
        <label for="header_meta_description"></label>
      </div>
    </div>

    <h3><?php esc_html_e('Structured Data Markup', 'yahman-add-ons'); ?></h3>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="json_breadcrumblist">
          <?php esc_html_e('Breadcrumb','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Add JSON-LD format bread crumbs.','yahman-add-ons'); ?>
        </div>
      </div>
      <div class="ya_checkbox">
        <input name="yahman_addons[json][breadcrumblist]" type="checkbox" id="json_breadcrumblist"<?php checked(true, $json['breadcrumblist']); ?> class="ya_checkbox" />
        <label for="json_breadcrumblist"></label>
      </div>
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="json_page">
          <?php esc_html_e('Post','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Add post data in JSON-LD format.','yahman-add-ons'); ?>
        </div>
      </div>
      <div class="ya_checkbox">
        <input name="yahman_addons[json][page]" type="checkbox" id="json_page"<?php checked(true, $json['page']); ?> class="ya_checkbox" />
        <label for="json_page"></label>
      </div>
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="select_json_logo_image">
          <?php esc_html_e('Logo Image','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Add logo image in JSON-LD format.','yahman-add-ons'); ?>
        </div>
      </div>
      <div class="json_logo_image" style="width: 100%; max-width:320px; height:auto;">
        <div class="json_logo_image_id_placeholder" style="width: 100%; position: relative; text-align: center; cursor: default;border: 1px dashed #b4b9be;box-sizing: border-box;padding: 9px 0;line-height: 20px; margin: 10px 0;<?php if( !empty( $json['logo_image'] ) ){echo 'display:none;';} ?>"><?php esc_html_e( 'No image selected', 'yahman-add-ons' ); ?></div>
        <img class="json_logo_image_id_media_image custom_media_image" src="<?php if( !empty( $json['logo_image'] ) ){echo esc_url($json['logo_image']);} ?>" style="width: 100%; max-width: 120px; height:auto; margin-bottom: 10px;" />

      </div>
      <input type="hidden" type="text" class="json_logo_image_id_media_id custom_media_id" name="yahman_addons[json][logo_image_id]" id="json_logo_image_id" value="<?php echo esc_attr($json['logo_image_id']); ?>" />

      <input type="hidden" type="text" class="json_logo_image_id_media_url custom_media_url" name="yahman_addons[json][logo_image]" id="json_logo_image_url" value="<?php echo esc_url($json['logo_image']); ?>" >
      <input type="button" value="<?php esc_html_e( 'Clear Image', 'yahman-add-ons' ); ?>" class="button json_logo_image_id_remove-button custom_media_clear" data-media_clear="json_logo_image_id" style="<?php if( !empty( $json['logo_image'] ) ){echo 'display:inline-block;';}else{echo 'display:none;';} ?>" />
      <input id="select_json_logo_image" type="button" value="<?php esc_html_e( 'Select Image', 'yahman-add-ons' ); ?>" class="button upload-button custom_media_upload" data-media_select="json_logo_image_id"/>
    </div>
  </div>




  <?php
}
