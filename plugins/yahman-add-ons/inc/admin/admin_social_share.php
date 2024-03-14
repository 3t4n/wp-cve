<?php
defined( 'ABSPATH' ) || exit;
/**
 * Admin Social Share Page
 *
 * @package YAHMAN Add-ons
 */
function yahman_addons_admin_social_share($option,$option_key,$option_checkbox){

  $sns_name = yahman_addons_social_name_list();
  unset($sns_name['feedly']);
  unset($sns_name['rdf']);
  unset($sns_name['rss']);
  unset($sns_name['rss2']);
  unset($sns_name['atom']);
  unset($sns_name['amazon']);
  unset($sns_name['codepen']);
  unset($sns_name['flickr']);
  unset($sns_name['github']);
  unset($sns_name['instagram']);
  unset($sns_name['meetup']);
  unset($sns_name['soundcloud']);
  unset($sns_name['vimeo']);
  unset($sns_name['youtube']);


  foreach ($option_key['share'] as $key => $value  ) {
    $share[$key] = $option['share'][$key];
  }
  foreach ($option_checkbox['share'] as $key => $value  ) {
    $share[$key] = isset($option['share'][$key]) ? true: false;
  }



  ?>

  <div id="ya_share_content" class="tab_content ya_box_design">
    <h2><?php esc_html_e('Social Share','yahman-add-ons'); ?></h2>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="share_post">
          <?php esc_html_e('Social share under in the contents of the post','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Show the share button and let your readers share it.','yahman-add-ons'); ?>
        </div>
      </div>
      <div class="ya_checkbox">
        <input name="yahman_addons[share][post]" type="checkbox" id="share_post"<?php checked(true, $share['post']); ?> class="ya_checkbox" />
        <label for="share_post"></label>
      </div>
    </div>

    <div class="ya_hr"></div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="share_page">
          <?php esc_html_e('Social share under in the contents of the page','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Show the share button and let your readers share it.','yahman-add-ons'); ?>
        </div>
      </div>
      <div class="ya_checkbox">
        <input name="yahman_addons[share][page]" type="checkbox" id="share_page"<?php checked(true, $share['page']); ?> class="ya_checkbox" />
        <label for="share_page"></label>
      </div>
    </div>

    <div class="ya_hr"></div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="share_title"><?php esc_html_e('Title','yahman-add-ons'); ?></label>
        <div class="ya_tooltip">
          <?php esc_html_e( 'Share button heading title.', 'yahman-add-ons'); ?>
        </div>
      </div>
      <input name="yahman_addons[share][title]" type="text" id="share_title" value="<?php echo esc_html($share['title']); ?>" class="ya_textbox" />
    </div>

    <?php
    $i = 1;

    while($i < 11){
      $share['icon_'.$i] = isset($option['share']['icon_'.$i]) ? $option['share']['icon_'.$i] : 'none';
      ?>

      <div class="ya_setting_content">
        <div class="ya_tooltip_wrap">
          <label for="share_icon_<?php echo esc_attr($i); ?>">
            <?php esc_html_e('Icon','yahman-add-ons'); ?> #<?php echo esc_attr($i); ?>
          </label>
          <div class="ya_tooltip">
            <?php esc_html_e('Select the icon you want to display.', 'yahman-add-ons'); ?>
          </div>
        </div>
        <select name="yahman_addons[share][icon_<?php echo esc_attr($i); ?>]" id="share_icon_<?php echo esc_attr($i); ?>">
          <?php
          foreach ($sns_name as $key => $value) {
            echo '<option value="'.esc_attr($key).'"'.selected( $share['icon_'.$i], $key ).'>'. esc_html($value['name']).'</option>';
          }
          ?>
        </select>
      </div>

      <?php
      ++$i;
    }
    ?>

    <div class="ya_hr"></div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="share_icon_shape">
          <?php esc_html_e('Button style','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Select the display style of the button.', 'yahman-add-ons'); ?>
        </div>
      </div>
      <select name="yahman_addons[share][icon_shape]" id="share_icon_shape">
        <?php

        foreach (yahman_addons_social_shape_list() as $key => $value) {
          echo '<option value="'.esc_attr($key).'"'.selected( $share['icon_shape'], $key ).'>'. esc_html($value).'</option>';
        }
        ?>
      </select>
    </div>

    <div class="ya_hr"></div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="share_icon_align">
          <?php esc_html_e('Align','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Select the button placement method.', 'yahman-add-ons'); ?>
        </div>
      </div>
      <select name="yahman_addons[share][icon_align]" id="share_icon_align">
        <?php

        foreach (yahman_addons_social_align_list() as $key => $value) {
          echo '<option value="'.esc_attr($key).'"'.selected( $share['icon_align'], $key ).'>'. esc_html($value).'</option>';
        }
        ?>
      </select>
    </div>

    <div class="ya_hr"></div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="share_icon_size">
          <?php esc_html_e('Button Size','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Choose the size of the button.', 'yahman-add-ons'); ?>
        </div>
      </div>
      <select name="yahman_addons[share][icon_size]" id="share_icon_size">
        <?php

        foreach (yahman_addons_social_size_list() as $key => $value) {
          echo '<option value="'.esc_attr($key).'"'.selected( $share['icon_size'], $key ).'>'. esc_html($value).'</option>';
        }
        ?>
      </select>
    </div>

    <div class="ya_hr"></div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="share_icon_user_color">
          <?php esc_html_e('Color of button','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Choose a button color.','yahman-add-ons'); ?>
          <br>
          <?php esc_html_e('If blank, the default color for each social will be set.','yahman-add-ons'); ?>
        </div>
      </div>
      <input class="ya_color-picker" id="share_icon_user_color" name="yahman_addons[share][icon_user_color]" type="text" value="<?php echo esc_attr( $share['icon_user_color'] ); ?>" data-alpha-enabled="true" data-alpha-color-type="hex" />
    </div>

    <div class="ya_hr"></div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="share_icon_user_hover_color">
          <?php esc_html_e('Hover color of button','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Select the color when hovering on the button.','yahman-add-ons'); ?>
          <br>
          <?php esc_html_e('If blank, the default color for each social will be set.','yahman-add-ons'); ?>
        </div>
      </div>
      <input class="ya_color-picker" id="share_icon_user_hover_color" name="yahman_addons[share][icon_user_hover_color]" type="text" value="<?php echo esc_attr( $share['icon_user_hover_color'] ); ?>" data-alpha-enabled="true" data-alpha-color-type="hex" />
    </div>

    <div class="ya_hr"></div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="share_icon_tooltip">
          <?php esc_html_e('Tool tip','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Shows the social name when hovering over the button.','yahman-add-ons'); ?>
          <br>
          <?php esc_html_e('If the button style is rectangular, it will not be displayed.','yahman-add-ons'); ?>
        </div>
      </div>
      <div class="ya_checkbox">
        <input name="yahman_addons[share][icon_tooltip]" type="checkbox" id="share_icon_tooltip"<?php checked(true, $share['icon_tooltip']); ?> class="ya_checkbox" />
        <label for="share_icon_tooltip"></label>
      </div>
    </div>


  </div>

  <?php
}
