<?php
defined( 'ABSPATH' ) || exit;
/**
 * Admin cta_social Page
 *
 * @package YAHMAN Add-ons
 */
function yahman_addons_admin_cta_social($option,$option_key,$option_checkbox){

  foreach ($option_key['cta_social'] as $key => $value  ) {
    $cta_social[$key] = $option['cta_social'][$key];
  }
  foreach ($option_checkbox['cta_social'] as $key => $value  ) {
    $cta_social[$key] = isset($option['cta_social'][$key]) ? true: false;
  }


  ?>

  <div id="ya_cta_social_content" class="tab_content ya_box_design">
    <h2><?php esc_html_e('Call To Action','yahman-add-ons'); ?></h2>

    <h3><?php esc_html_e('Social', 'yahman-add-ons'); ?></h3>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="cta_social_post">
          <?php esc_html_e('Call To Action under in the contents of the post','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Place a like or follow button.','yahman-add-ons'); ?>
        </div>
      </div>
      <div class="ya_checkbox">
        <input name="yahman_addons[cta_social][post]" type="checkbox" id="cta_social_post"<?php checked(true, $cta_social['post']); ?> class="ya_checkbox" />
        <label for="cta_social_post"></label>
      </div>
    </div>

    <div class="ya_hr"></div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="cta_social_page">
          <?php esc_html_e('Call To Action under in the contents of the page','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Place a like or follow button.','yahman-add-ons'); ?>
        </div>
      </div>
      <div class="ya_checkbox">
        <input name="yahman_addons[cta_social][page]" type="checkbox" id="cta_social_page"<?php checked(true, $cta_social['page']); ?> class="ya_checkbox" />
        <label for="cta_social_page"></label>
      </div>
    </div>

    <div class="ya_hr"></div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="cta_social_heading"><?php esc_html_e('heading text','yahman-add-ons'); ?></label>
        <div class="ya_tooltip">
          <?php esc_html_e( 'The text above the button.', 'yahman-add-ons'); ?>
        </div>
      </div>
      <input name="yahman_addons[cta_social][heading]" type="text" id="cta_social_heading" value="<?php echo esc_html($cta_social['heading']); ?>" class="ya_textbox widefat" />
    </div>

    <div class="ya_hr"></div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="cta_social_ending"><?php esc_html_e('end text','yahman-add-ons'); ?></label>
        <div class="ya_tooltip">
          <?php esc_html_e( 'The text at the bottom of the button.', 'yahman-add-ons'); ?>
        </div>
      </div>
      <input name="yahman_addons[cta_social][ending]" type="text" id="cta_social_ending" value="<?php echo esc_html($cta_social['ending']); ?>" class="ya_textbox widefat" />
    </div>

    <div class="ya_hr"></div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="cta_social_facebook">
          <?php esc_html_e('Facebook','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Place a Like button on your Facebook page.','yahman-add-ons'); ?>
          <br>
          <label class="ya_link_color" for="ya_sns" onclick="to_top();">
            <?php esc_html_e('Based on social account settings.','yahman-add-ons'); ?>
          </label>
        </div>
      </div>
      <div class="ya_checkbox">
        <input name="yahman_addons[cta_social][facebook]" type="checkbox" id="cta_social_facebook"<?php checked(true, $cta_social['facebook']); ?> class="ya_checkbox" />
        <label for="cta_social_facebook"></label>
      </div>
    </div>

    <div class="ya_hr"></div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="cta_social_twitter">
          <?php esc_html_e('Twitter','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Place a follow button on Twitter.','yahman-add-ons'); ?>
          <br>
          <label class="ya_link_color" for="ya_sns">
            <?php esc_html_e('Based on social account settings.','yahman-add-ons'); ?>
          </label>
        </div>
      </div>
      <div class="ya_checkbox">
        <input name="yahman_addons[cta_social][twitter]" type="checkbox" id="cta_social_twitter"<?php checked(true, $cta_social['twitter']); ?> class="ya_checkbox" />
        <label for="cta_social_twitter"></label>
      </div>
    </div>

    <div class="ya_hr"></div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="cta_social_feedly">
          <?php esc_html_e('Feedly','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Link your site\'s feed to Feedly.','yahman-add-ons'); ?>
        </div>
      </div>
      <div class="ya_checkbox">
       <input name="yahman_addons[cta_social][feedly]" type="checkbox" id="cta_social_feedly"<?php checked(true, $cta_social['feedly']); ?> class="ya_checkbox" />
       <label for="cta_social_feedly"></label>
     </div>
   </div>

 </div>

 <?php
}
