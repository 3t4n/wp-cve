<?php
defined( 'ABSPATH' ) || exit;
/**
 * Admin Profile Page
 *
 * @package YAHMAN Add-ons
 */
function yahman_addons_admin_profile($option,$option_key,$option_checkbox){

  foreach ($option_key['profile'] as $key => $value  ) {
    $profile[$key] = $option['profile'][$key];
  }
  foreach ($option_checkbox['profile'] as $key => $value  ) {
    $profile[$key] = isset($option['profile'][$key]) ? true: false;
  }

  $widget_key = array(
    'profile',
    'another',
  );

  foreach ($widget_key as $key ) {
    $widget[$key] = isset($option['widget'][$key]) ? true: false;
  }

  $sns_name = yahman_addons_social_name_list();
  unset($sns_name['buffer']);
  unset($sns_name['digg']);
  unset($sns_name['evernote']);
  unset($sns_name['mail']);
  unset($sns_name['messenger']);
  unset($sns_name['pocket']);
  unset($sns_name['reddit']);
  unset($sns_name['whatsapp']);
  unset($sns_name['print']);





  $i = 1;
  while($i < 6){
    $profile['icon_'.$i] = isset($option['profile']['icon_'.$i]) ? $option['profile']['icon_'.$i] : 'none';
    ++$i;
  }
  //$profile['title'] = !empty($option['profile']['title']) ? $option['profile']['title'] : esc_html__( 'About me', 'yahman-add-ons' );
  //$profile['read_more_text'] = !empty($option['profile']['read_more_text']) ? $option['profile']['read_more_text'] : esc_html__( 'Read More', 'yahman-add-ons' );

  //$profile['icon_shape'] = isset($option['profile']['icon_shape']) ? $option['profile']['icon_shape'] : 'icon_square';
  //$profile['icon_size'] = isset($option['profile']['icon_size']) ? $option['profile']['icon_size'] : 'icon_medium';
  //$profile['icon_align']  = isset($option['profile']['icon_align']) ? $option['profile']['icon_align'] : 'center';
  //$profile['image_shape'] = isset($option['profile']['image_shape']) ? $option['profile']['image_shape'] : 'circle';
  //$profile['read_more_blank'] = isset($option['profile']['read_more_blank']) ? true: false;
  //$profile['icon_tooltip'] = isset($option['profile']['icon_tooltip']) ? true: false;

  ?>

  <div id="ya_profile_content" class="tab_content ya_box_design">
    <h2><?php esc_html_e('Profile','yahman-add-ons'); ?></h2>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="profile_user_profile">
          <?php esc_html_e('Add Social area on User Profile','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Add a social network link to your WordPress profile field.','yahman-add-ons'); ?>
          <br>
          <?php esc_html_e('If the theme is Simple Days or Neatly, it will be reflected in the profile section of the posting page.','yahman-add-ons'); ?>
          <br>
          <a href="<?php echo esc_url(admin_url('profile.php') ); ?>" target="_blank" rel="nofollow noopener noreferrer"><?php esc_html_e('Edit Profile', 'yahman-add-ons'); ?></a>
        </div>
      </div>
      <div class="ya_checkbox">
        <input name="yahman_addons[profile][user_profile]" type="checkbox" id="profile_user_profile"<?php checked(true, $profile['user_profile']); ?> class="ya_checkbox" />
        <label for="profile_user_profile"></label>
      </div>
    </div>


    <h3><?php esc_html_e('Profile widget settings', 'yahman-add-ons'); ?></h3>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="profile_title"><?php esc_html_e( 'Title', 'yahman-add-ons'); ?></label>
        <div class="ya_tooltip">
          <?php esc_html_e( 'The title displayed in the widget.', 'yahman-add-ons'); ?>
        </div>
      </div>
      <input name="yahman_addons[profile][title]" type="text" id="profile_title" value="<?php echo esc_html($profile['title']); ?>" class="ya_textbox widefat" />
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="profile_name"><?php esc_html_e( 'Name', 'yahman-add-ons'); ?></label>
        <div class="ya_tooltip">
          <?php esc_html_e( 'The name displayed in the widget.', 'yahman-add-ons'); ?>
        </div>
      </div>
      <input name="yahman_addons[profile][name]" type="text" id="profile_name" value="<?php echo esc_html($profile['name']); ?>" class="ya_textbox widefat" />
    </div>

    <div class="ya_hr"></div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="select_profile_img"><?php esc_html_e( 'Profile image', 'yahman-add-ons'); ?></label>
        <div class="ya_tooltip">
          <?php esc_html_e( 'The image displayed in the widget.', 'yahman-add-ons'); ?>
        </div>
      </div>

      <div class="profile_img" style="width: 100%; max-width:320px; height:auto;">
        <div class="profile_img_id_placeholder" style="width: 100%; position: relative; text-align: center; cursor: default;border: 1px dashed #b4b9be;box-sizing: border-box;padding: 9px 0;line-height: 20px; margin: 10px 0;<?php if( !empty( $profile['image'] ) ){echo 'display:none;';} ?>"><?php esc_html_e( 'No image selected', 'yahman-add-ons' ); ?></div>
        <img class="profile_img_id_media_image custom_media_image" src="<?php if( !empty( $profile['image'] ) ){echo esc_url($profile['image']);} ?>" style="width: 100%; max-width: 120px; height:auto; margin-bottom: 10px;" />

      </div>
      <input type="hidden" type="text" class="profile_img_id_media_id custom_media_id" name="yahman_addons[profile][image_id]" id="profile_img_id" value="<?php echo esc_attr($profile['image_id']); ?>" />

      <input type="hidden" type="text" class="profile_img_id_media_url custom_media_url" name="yahman_addons[profile][image]" id="profile_img_url" value="<?php echo esc_url($profile['image']); ?>" >
      <input type="button" value="<?php esc_html_e( 'Clear Image', 'yahman-add-ons' ); ?>" class="button profile_img_id_remove-button custom_media_clear" data-media_clear="profile_img_id" style="<?php if( !empty( $profile['image'] ) ){echo 'display:inline-block;';}else{echo 'display:none;';} ?>" />
      <input id="select_profile_img" type="button" value="<?php esc_html_e( 'Select Image', 'yahman-add-ons' ); ?>" class="button upload-button custom_media_upload" data-media_select="profile_img_id"/>


    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="profile_image_shape">
          <?php esc_html_e('Profile image display shape','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('How to process the edges of an image.'); ?>
        </div>
      </div>


      <select name="yahman_addons[profile][image_shape]" id="profile_image_shape">
        <option value="circle"<?php selected( $profile['image_shape'], 'circle' ); ?>><?php esc_html_e('Circle', 'yahman-add-ons'); ?></option>
        <option value="square"<?php selected( $profile['image_shape'], 'square' ); ?>><?php esc_html_e('Square', 'yahman-add-ons'); ?></option>
      </select>

    </div>

    <div class="ya_hr"></div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="select_profile_img_bg"><?php esc_html_e( 'Background image', 'yahman-add-ons'); ?></label>
        <div class="ya_tooltip">
          <?php esc_html_e( 'This is the image to be placed on the background of the profile image.', 'yahman-add-ons'); ?>
        </div>
      </div>

      <div class="profile_img_bg" style="width: 100%; max-width:320px; height:auto;">
        <div class="profile_img_bg_id_placeholder" style="width: 100%; position: relative; text-align: center; cursor: default;border: 1px dashed #b4b9be;box-sizing: border-box;padding: 9px 0;line-height: 20px; margin: 10px 0;<?php if( !empty( $profile['image_bg'] ) ){echo 'display:none;';} ?>"><?php esc_html_e( 'No image selected', 'yahman-add-ons' ); ?></div>
        <img class="profile_img_bg_id_media_image custom_media_image" src="<?php if( !empty( $profile['image_bg'] ) ){echo esc_url($profile['image_bg']);} ?>" style="width: 100%; max-width: 120px; height:auto; margin-bottom: 10px;" />

      </div>
      <input type="hidden" type="text" class="profile_img_bg_id_media_id custom_media_id" name="yahman_addons[profile][image_bg_id]" id="profile_img_bg_id" value="<?php echo esc_attr($profile['image_bg_id']); ?>" />

      <input type="hidden" type="text" class="profile_img_bg_id_media_url custom_media_url" name="yahman_addons[profile][image_bg]" id="profile_img_bg_url" value="<?php echo esc_url($profile['image_bg']); ?>" >
      <input type="button" value="<?php esc_html_e( 'Clear Image', 'yahman-add-ons' ); ?>" class="button profile_img_bg_id_remove-button custom_media_clear" data-media_clear="profile_img_bg_id" style="<?php if( !empty( $profile['image_bg'] ) ){echo 'display:inline-block;';}else{echo 'display:none;';} ?>" />
      <input id="select_profile_img_bg" type="button" value="<?php esc_html_e( 'Select Image', 'yahman-add-ons' ); ?>" class="button upload-button custom_media_upload" data-media_select="profile_img_bg_id"/>


    </div>

    <div class="ya_hr"></div>


    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="profile_text"><?php esc_html_e( 'Profile text', 'yahman-add-ons'); ?></label>
        <div class="ya_tooltip">
          <?php esc_html_e( 'Enter your profile.', 'yahman-add-ons'); ?>
        </div>
      </div>
      <textarea name="yahman_addons[profile][text]" rows="4" cols="48" id="profile_text" class="ya_textbox" /><?php echo $profile['text']; ?></textarea>
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="profile_read_more_url"><?php esc_html_e( 'Read more URL', 'yahman-add-ons'); ?></label>
        <div class="ya_tooltip">
          <?php esc_html_e( 'Enter the link to the profile page.', 'yahman-add-ons'); ?>
        </div>
      </div>
      <input name="yahman_addons[profile][read_more_url]" type="text" id="profile_read_more_url" value="<?php echo esc_url($profile['read_more_url']); ?>" class="ya_textbox widefat" />
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="profile_read_more_text"><?php esc_html_e( 'Read more text', 'yahman-add-ons'); ?></label>
        <div class="ya_tooltip">
          <?php esc_html_e( 'Enter the text to read more.', 'yahman-add-ons'); ?>
        </div>
      </div>
      <input name="yahman_addons[profile][read_more_text]" type="text" id="profile_read_more_text" value="<?php echo esc_html($profile['read_more_text']); ?>" class="ya_textbox widefat" />
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="profile_read_more_blank">
          <?php esc_html_e('Read more link open new window','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip"><?php esc_html_e('Read more link open new window.','yahman-add-ons'); ?></div>
      </div>
      <div class="ya_checkbox">
        <input name="yahman_addons[profile][read_more_blank]" type="checkbox" id="profile_read_more_blank"<?php checked(true, $profile['read_more_blank']); ?> class="ya_checkbox" />
        <label for="profile_read_more_blank"></label>
      </div>
    </div>

    <div class="ya_hr"></div>


    <?php
    $i = 1;

    while($i < 6){
      ?>

      <div class="ya_setting_content">
        <div class="ya_tooltip_wrap">
          <label for="profile_icon_<?php echo esc_attr($i); ?>">
            <?php esc_html_e('Icon','yahman-add-ons'); ?> #<?php echo esc_attr($i); ?>
          </label>
          <div class="ya_tooltip">
            <?php esc_html_e('Select the icon you want to display.', 'yahman-add-ons'); ?>
            <br>
            <label class="ya_link_color" for="ya_sns" onclick="to_top();">
              <?php esc_html_e('Based on social account settings.','yahman-add-ons'); ?>
            </label>
          </div>
        </div>
        <select name="yahman_addons[profile][icon_<?php echo esc_attr($i); ?>]" id="profile_icon_<?php echo esc_attr($i); ?>">
          <?php
          foreach ($sns_name as $key => $value) {
            echo '<option value="'.esc_attr($key).'"'.selected( $profile['icon_'.$i], $key ).'>'. esc_html($value['name']).'</option>';
          }
          ?>
        </select>
      </div>
      <?php
      ++$i;
    }
    ?>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="profile_icon_shape">
          <?php esc_html_e('Button style','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Select the display style of the button.', 'yahman-add-ons'); ?>
        </div>
      </div>
      <select name="yahman_addons[profile][icon_shape]" id="profile_icon_shape">
        <?php
        foreach (yahman_addons_social_shape_list() as $key => $value) {
          echo '<option value="'.esc_attr($key).'"'.selected( $profile['icon_shape'], $key ).'>'. esc_html($value).'</option>';
        }
        ?>
      </select>
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="profile_icon_align">
          <?php esc_html_e('Align','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Select the button placement method.', 'yahman-add-ons'); ?>
        </div>
      </div>
      <select name="yahman_addons[profile][icon_align]" id="profile_icon_align">
        <?php

        foreach (yahman_addons_social_align_list() as $key => $value) {
          echo '<option value="'.esc_attr($key).'"'.selected( $profile['icon_align'], $key ).'>'. esc_html($value).'</option>';
        }
        ?>
      </select>
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="profile_icon_size">
          <?php esc_html_e('Button Size','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Choose the size of the button.', 'yahman-add-ons'); ?>
        </div>
      </div>
      <select name="yahman_addons[profile][icon_size]" id="profile_icon_size">
        <?php
        foreach (yahman_addons_social_size_list() as $key => $value) {
          echo '<option value="'.esc_attr($key).'"'.selected( $profile['icon_size'], $key ).'>'. esc_html($value).'</option>';
        }
        ?>
      </select>
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="profile_icon_user_color">
          <?php esc_html_e('Color of button','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Choose a button color.','yahman-add-ons'); ?>
          <br>
          <?php esc_html_e('If blank, the default color for each social will be set.','yahman-add-ons'); ?>
        </div>
      </div>
      <input class="ya_color-picker" id="profile_icon_user_color" name="yahman_addons[profile][icon_user_color]" type="text" value="<?php echo esc_attr( $profile['icon_user_color'] ); ?>" data-alpha-enabled="true" data-alpha-color-type="hex" />
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="cta_social_page">
          <?php esc_html_e('Hover color of button','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Select the color when hovering on the button.','yahman-add-ons'); ?>
          <br>
          <?php esc_html_e('If blank, the default color for each social will be set.','yahman-add-ons'); ?>
        </div>
      </div>
      <input class="ya_color-picker" id="profile_icon_user_hover_color" name="yahman_addons[profile][icon_user_hover_color]" type="text" value="<?php echo esc_attr( $profile['icon_user_hover_color'] ); ?>" data-alpha-enabled="true" data-alpha-color-type="hex" />
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="profile_icon_tooltip">
          <?php esc_html_e('Tool tip','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Shows the social name when hovering over the button.','yahman-add-ons'); ?>
          <br>
          <?php esc_html_e('If the button style is rectangular, it will not be displayed.','yahman-add-ons'); ?>
        </div>
      </div>
      <div class="ya_checkbox">
        <input name="yahman_addons[profile][icon_tooltip]" type="checkbox" id="profile_icon_tooltip"<?php checked(true, $profile['icon_tooltip']); ?> class="ya_checkbox" />
        <label for="profile_icon_tooltip"></label>
      </div>
    </div>

    <div class="ya_hr"></div>


    <label class="ya_link_color" for="ya_widget" onclick="to_top();">
      <?php esc_html_e('There are related widgets.','yahman-add-ons'); ?><br>
      &rsaquo; <?php esc_html_e('Profile widget', 'yahman-add-ons'); ?><br>
      &rsaquo; <?php esc_html_e('Another Profile widget', 'yahman-add-ons'); ?><br>
    </label>








  </div>




  <?php
}
