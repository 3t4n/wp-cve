<?php
defined( 'ABSPATH' ) || exit;
/**
 * Admin Social Page
 *
 * @package YAHMAN Add-ons
 */

function yahman_addons_admin_social($option,$option_key,$option_checkbox){

  foreach ( $option_key['sns_account'] as $key => $value ) {
    $sns_account[$key] = $option['sns_account'][$key];
  }

  foreach ($option_checkbox['sns_account'] as $key => $value  ) {
    $sns_account[$key] = isset($option['sns_account'][$key]) ? true: false;
  }

  $sns_name = yahman_addons_social_name_list();
  unset($sns_name['none']);
  unset($sns_name['buffer']);
  unset($sns_name['digg']);
  unset($sns_name['mail']);
  unset($sns_name['evernote']);
  unset($sns_name['messenger']);
  unset($sns_name['pocket']);
  unset($sns_name['reddit']);
  unset($sns_name['whatsapp']);
  unset($sns_name['feedly']);
  unset($sns_name['rdf']);
  unset($sns_name['rss']);
  unset($sns_name['rss2']);
  unset($sns_name['atom']);
  unset($sns_name['print']);

  ?>
  <div id="ya_sns_content" class="tab_content ya_box_design">
    <h2><?php esc_html_e('Social Account','yahman-add-ons'); ?></h2>

    <?php

    foreach ($sns_name as $key => $value) {
      $sns_account[$key] = isset($option['sns_account'][$key]) ? $option['sns_account'][$key] : ''; ?>

      <div class="ya_setting_content">
        <div class="ya_tooltip_wrap">
          <label for="sns_account_<?php echo esc_attr($key); ?>">
            <?php echo esc_html($value['name']); ?>
          </label>
          <div class="ya_tooltip">
           <?php
           if($key === 'amazon'){
            echo esc_html__("add your Amazon website's full URL", 'yahman-add-ons').'<br>'.esc_html__('e.g.&nbsp;', 'yahman-add-ons').esc_html__('Your wish list URL', 'yahman-add-ons');
          }else if($key === 'tumblr'){
            yahman_addons_sns_account_eg_before('.tumblr.com');
          }else{
            yahman_addons_sns_account_eg($value['base']);
          }
          ?>
        </div>
      </div>
      <div class="ya_flex ya_ai_c">
        <input name="yahman_addons[sns_account][<?php echo esc_attr($key); ?>]" type="text" id="sns_account_<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($sns_account[$key]); ?>" class="ya_textbox" />
      </div>
    </div>

    <?php

  }


  ?>

  <div class="ya_hr"></div>

  <label class="ya_link_color" for="ya_widget" onclick="to_top();">
    <?php esc_html_e('There are related widgets.','yahman-add-ons'); ?><br>
    &rsaquo; <?php esc_html_e('Social Links widget','yahman-add-ons'); ?>
  </label>



  <h3><?php esc_html_e('Facebook','yahman-add-ons'); ?></h3>

  <div class="ya_setting_content">
    <div class="ya_tooltip_wrap">
      <label for="sns_account_facebook_script">
        <?php esc_html_e('Facebook Script','yahman-add-ons'); ?>
      </label>
      <div class="ya_tooltip">
        <?php esc_html_e('If your Facebook Like button or widget doesn\'t work, turn it on.','yahman-add-ons'); ?>
      </div>
    </div>
    <div class="ya_checkbox">
      <input name="yahman_addons[sns_account][facebook_script]" type="checkbox" id="sns_account_facebook_script"<?php checked(true, $sns_account['facebook_script']); ?> class="ya_checkbox" />
      <label for="sns_account_facebook_script"></label>
    </div>
  </div>


  <div class="ya_setting_content">
    <div class="ya_tooltip_wrap">
      <label for="sns_account_facebook_app_id">
        <?php esc_html_e('Facebook App ID','yahman-add-ons'); ?>
      </label>
      <div class="ya_tooltip">
        <?php echo esc_html__('e.g.&nbsp;', 'yahman-add-ons').esc_html('1234567890');; ?>
      </div>
    </div>
    <input name="yahman_addons[sns_account][facebook_app_id]" type="text" id="sns_account_facebook_app_id" value="<?php echo esc_attr($sns_account['facebook_app_id']); ?>" class="ya_textbox" />
  </div>
  <div class="ya_setting_content">
    <div class="ya_tooltip_wrap">
      <label for="sns_account_facebook_admins">
        <?php esc_html_e('Facebook fb:admins','yahman-add-ons'); ?>
      </label>
      <div class="ya_tooltip">
        <?php echo esc_html__('e.g.&nbsp;', 'yahman-add-ons').esc_html('1234567890'); ?>
      </div>
    </div>
    <input name="yahman_addons[sns_account][facebook_admins]" type="text" id="sns_account_facebook_admins" value="<?php echo esc_attr($sns_account['facebook_admins']); ?>" class="ya_textbox" />
  </div>





</div>

<?php
}

function yahman_addons_sns_account_eg($account){

  esc_html_e('type the &lowast;&lowast;&lowast;&lowast;&lowast;&lowast; part of your url', 'yahman-add-ons');
  echo '<br>';
  esc_html_e('e.g.&nbsp;', 'yahman-add-ons');
  echo esc_html($account);
  echo '<strong class="highlighter">&lowast;&lowast;&lowast;&lowast;&lowast;&lowast;</strong>';
}

function yahman_addons_sns_account_eg_before($account){
  esc_html_e('type the &lowast;&lowast;&lowast;&lowast;&lowast;&lowast; part of your url', 'yahman-add-ons');
  echo '<br>';
  esc_html_e('e.g.&nbsp;', 'yahman-add-ons');
  echo '<strong class="highlighter">&lowast;&lowast;&lowast;&lowast;&lowast;&lowast;</strong>'.esc_html($account);
}