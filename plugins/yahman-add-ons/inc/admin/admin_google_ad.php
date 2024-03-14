<?php
defined( 'ABSPATH' ) || exit;
/**
 * Admin Google AdSense Page
 *
 * @package YAHMAN Add-ons
 */
function yahman_addons_admin_google_ad($option,$option_key,$option_checkbox){

  foreach ($option_key['google_ad'] as $key => $value ) {
    $google_ad[$key] = $option['google_ad'][$key];
  }
  foreach ($option_checkbox['google_ad'] as $key => $value ) {
    $google_ad[$key] = isset($option['google_ad'][$key]) ? true: false;
  }

  $widget_key = array(
    'google_ad_responsive',
    'google_ad_infeed',
    'google_ad_inarticle',
    'google_ad_autorelaxed',
    'google_ad_link',
  );

  foreach ($widget_key as $key ) {
    $widget[$key] = isset($option['widget'][$key]) ? true: false;
  }

  $rows_mobile = $rows_pc = $columns_mobile = $columns_pc = $num_type = $ui_type = array();


  $num_type = array(
    '' => '',
    '1' => 1,
    '2' => 2,
    '3' => 3,
    '4' => 4,
    '5' => 5,
    '6' => 6
  );

  $ui_type = array(
    '' => '',
    'image_sidebyside' => esc_attr__('Image and text side by side', 'yahman-add-ons'),
    'image_card_sidebyside' => esc_attr__('Image and text side by side with card', 'yahman-add-ons'),
    'image_stacked' => esc_attr__('Image stacked above text', 'yahman-add-ons'),
    'image_card_stacked' => esc_attr__('Image stacked above text with card', 'yahman-add-ons'),
    'text' => esc_attr__('Text only', 'yahman-add-ons'),
    'text_card' => esc_attr__('Text with card', 'yahman-add-ons')
  );

  $local = get_locale();

  ?>

  <div id="ya_google_ad_content" class="tab_content ya_box_design">
    <h2><?php esc_html_e('Google AdSense','yahman-add-ons'); ?></h2>


    <div class="ya_hr"></div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="google_ad_id">
          <?php esc_html_e('publisher ID','yahman-add-ons'); ?><?php echo esc_html('(data-ad-client)'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('Enter your publisher ID.', 'yahman-add-ons'); ?><br>
          <?php echo esc_html__('e.g.&nbsp;', 'yahman-add-ons').esc_html('ca-pub-1234567890'); ?><br>
          <a href="<?php echo esc_url('https://support.google.com/adsense/answer/105516?hl=' . $local ); ?>" target="_blank" rel="nofollow noopener noreferrer"><?php esc_html_e('AdSense Help', 'yahman-add-ons'); ?></a>
        </div>
      </div>
      <input name="yahman_addons[google_ad][id]" type="text" id="google_ad_id" value="<?php echo esc_attr($google_ad['id']); ?>" class="ya_textbox" />
    </div>

    <div class="ya_hr"></div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="google_ad_labeling">
          <?php esc_html_e('Labeling ads','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('When displaying a label on an AdSense ad unit.', 'yahman-add-ons'); ?><br>
          <a href="<?php echo esc_url('https://support.google.com/adsense/answer/4533986?hl=' . $local ); ?>" target="_blank" rel="nofollow noopener noreferrer"><?php esc_html_e('AdSense Help', 'yahman-add-ons'); ?></a>
        </div>
      </div>
      <select name="yahman_addons[google_ad][labeling]" id="google_ad_labeling">
        <option value="0"<?php selected( $google_ad['labeling'], '0' ); ?>><?php esc_html_e('Hide', 'yahman-add-ons'); ?></option>
        <option value="1"<?php selected( $google_ad['labeling'], '1' ); ?>><?php esc_html_e('Advertisements', 'yahman-add-ons'); ?></option>
        <option value="2"<?php selected( $google_ad['labeling'], '2' ); ?>><?php esc_html_e('Sponsored Links', 'yahman-add-ons'); ?></option>
      </select>
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="google_ad_dp">
          <?php esc_html_e('Labeling ads display position','yahman-add-ons'); ?>
        </label>
        <div class="ya_tooltip">
          <?php esc_html_e('The position to display the label.', 'yahman-add-ons'); ?><br>
        </div>
      </div>
      <select name="yahman_addons[google_ad][dp]" id="google_ad_dp">
        <option value="left"<?php selected( $google_ad['dp'], 'left' ); ?>><?php esc_html_e('Left', 'yahman-add-ons'); ?></option>
        <option value="center"<?php selected( $google_ad['dp'], 'center' ); ?>><?php esc_html_e('Center', 'yahman-add-ons'); ?></option>
        <option value="right"<?php selected( $google_ad['dp'], 'right' ); ?>><?php esc_html_e('Right', 'yahman-add-ons'); ?></option>
      </select>
    </div>

    <div class="ya_hr"></div>
    <h3><?php echo sprintf(esc_html__('%s ad setting', 'yahman-add-ons'),esc_html_x('Display', 'google_ad', 'yahman-add-ons')); ?></h3>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="google_ad_slot_responsive"><?php echo sprintf( esc_html__( '%1$s ad %2$s', 'yahman-add-ons'),esc_html_x('Display', 'google_ad', 'yahman-add-ons') , esc_html( 'data-ad-slot', 'yahman-add-ons') ); ?></label>
        <div class="ya_tooltip">
          <?php echo sprintf( esc_html__( 'Enter the %2$s for your %1$s ad', 'yahman-add-ons') , esc_html_x('Display', 'google_ad', 'yahman-add-ons') , esc_html('data-ad-slot') ); ?>
          <br>
          <?php echo esc_html__('e.g.&nbsp;', 'yahman-add-ons').esc_html('1234567890'); ?><br>
          <a href="<?php echo esc_url('https://support.google.com/adsense/answer/105516?hl=' . $local ); ?>" target="_blank" rel="nofollow noopener noreferrer"><?php esc_html_e('AdSense Help', 'yahman-add-ons'); ?></a>
        </div>
      </div>
      <input name="yahman_addons[google_ad][slot_responsive]" type="text" id="google_ad_slot_responsive" value="<?php echo esc_attr($google_ad['slot_responsive']); ?>" class="ya_textbox" />
    </div>

    <div class="ya_hr"></div>

    <label class="ya_link_color" for="ya_widget" onclick="to_top();">
      <?php esc_html_e('There are related widgets.','yahman-add-ons'); ?><br>
      &rsaquo; <?php echo sprintf(esc_html__('Google AdSense %s Widget', 'yahman-add-ons'),esc_html_x('Display', 'google_ad', 'yahman-add-ons')); ?><br>
    </label>

    <h3><?php echo sprintf(esc_html__('%s ad setting', 'yahman-add-ons'),esc_html_x('In-feed', 'google_ad', 'yahman-add-ons')); ?></h3>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="google_ad_slot_infeed"><?php echo sprintf( esc_html__( '%1$s ad %2$s', 'yahman-add-ons'),esc_html_x('In-feed', 'google_ad', 'yahman-add-ons') , esc_html( 'data-ad-slot', 'yahman-add-ons') ); ?></label>
        <div class="ya_tooltip">
          <?php echo sprintf( esc_html__( 'Enter the %2$s for your %1$s ad', 'yahman-add-ons') , esc_html_x('In-feed', 'google_ad', 'yahman-add-ons') , esc_html('data-ad-slot') ); ?><br>
          <?php echo esc_html__('e.g.&nbsp;', 'yahman-add-ons').esc_html('1234567890'); ?><br>
          <a href="<?php echo esc_url('https://support.google.com/adsense/answer/9189557?hl=' . $local ); ?>" target="_blank" rel="nofollow noopener noreferrer"><?php esc_html_e('AdSense Help', 'yahman-add-ons'); ?></a>
        </div>
      </div>
      <input name="yahman_addons[google_ad][slot_infeed]" type="text" id="google_ad_slot_infeed" value="<?php echo esc_attr($google_ad['slot_infeed']); ?>" class="ya_textbox" />
    </div>

    <div class="ya_hr"></div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="google_ad_layout_infeed"><?php echo sprintf(esc_html__( '%1$s ad %2$s', 'yahman-add-ons'),esc_html_x('In-feed', 'google_ad', 'yahman-add-ons') , esc_html( 'data-ad-layout-key', 'yahman-add-ons') ); ?></label>
        <div class="ya_tooltip">
          <?php echo sprintf( esc_html__( 'Enter the %2$s for your %1$s ad', 'yahman-add-ons') , esc_html_x('In-feed', 'google_ad', 'yahman-add-ons') , esc_html('data-ad-layout-key') ); ?><br>
          <?php echo esc_html__('e.g.&nbsp;', 'yahman-add-ons').esc_html('-a0+b1+2c-d3+4e'); ?><br>
          <a href="<?php echo esc_url('https://support.google.com/adsense/answer/9189557?hl=' . $local ); ?>" target="_blank" rel="nofollow noopener noreferrer"><?php esc_html_e('AdSense Help', 'yahman-add-ons'); ?></a>
        </div>
      </div>
      <input name="yahman_addons[google_ad][layout_infeed]" type="text" id="google_ad_layout_infeed" value="<?php echo esc_attr($google_ad['layout_infeed']); ?>" class="ya_textbox" />
    </div>

    <div class="ya_hr"></div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="google_ad_slot_infeed_mobile"><?php echo sprintf(esc_html__( '%1$s ad %2$s', 'yahman-add-ons'),esc_html_x('In-feed', 'google_ad', 'yahman-add-ons') , esc_html( 'data-ad-slot', 'yahman-add-ons').'('.esc_html_x('For Mobile', 'google_ad', 'yahman-add-ons').')' ); ?></label>
        <div class="ya_tooltip">
          <?php echo sprintf( esc_html__( 'Enter the %2$s for your %1$s ad', 'yahman-add-ons') , esc_html_x('In-feed', 'google_ad', 'yahman-add-ons') , esc_html('data-ad-slot') ); ?><br>
          <?php echo esc_html__('e.g.&nbsp;', 'yahman-add-ons').esc_html('1234567890'); ?><br>
          <a href="<?php echo esc_url('https://support.google.com/adsense/answer/9189557?hl=' . $local ); ?>" target="_blank" rel="nofollow noopener noreferrer"><?php esc_html_e('AdSense Help', 'yahman-add-ons'); ?></a>
        </div>
      </div>
      <input name="yahman_addons[google_ad][slot_infeed_mobile]" type="text" id="google_ad_slot_infeed_mobile" value="<?php echo esc_attr($google_ad['slot_infeed_mobile']); ?>" class="ya_textbox" />
    </div>

    <div class="ya_hr"></div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="google_ad_layout_infeed_mobile"><?php echo sprintf(esc_html__( '%1$s ad %2$s', 'yahman-add-ons'),esc_html_x('In-feed', 'google_ad', 'yahman-add-ons') , esc_html( 'data-ad-layout-key', 'yahman-add-ons') ).'('.esc_html_x('For Mobile', 'google_ad', 'yahman-add-ons').')'; ?></label>
        <div class="ya_tooltip">
          <?php echo sprintf( esc_html__( 'Enter the %2$s for your %1$s ad', 'yahman-add-ons') , esc_html_x('In-feed', 'google_ad', 'yahman-add-ons') , esc_html('data-ad-layout-key') ); ?><br>
          <?php echo esc_html__('e.g.&nbsp;', 'yahman-add-ons').esc_html('-a0+b1+2c-d3+4e'); ?><br>
          <a href="<?php echo esc_url('https://support.google.com/adsense/answer/9189557?hl=' . $local ); ?>" target="_blank" rel="nofollow noopener noreferrer"><?php esc_html_e('AdSense Help', 'yahman-add-ons'); ?></a>
        </div>
      </div>
      <input name="yahman_addons[google_ad][layout_infeed_mobile]" type="text" id="google_ad_layout_infeed_mobile" value="<?php echo esc_attr($google_ad['layout_infeed_mobile']); ?>" class="ya_textbox" />
    </div>

    <div class="ya_hr"></div>

    <label class="ya_link_color" for="ya_widget" onclick="to_top();">
      <?php esc_html_e('There are related widgets.','yahman-add-ons'); ?><br>
      &rsaquo; <?php echo sprintf(esc_html__('Google AdSense %s Widget', 'yahman-add-ons'),esc_html_x('In-feed', 'google_ad', 'yahman-add-ons')); ?>
    </label>

    <div class="ya_hr"></div>

    <h3><?php echo sprintf(esc_html__('%s ad setting', 'yahman-add-ons'),esc_html_x('In-article', 'google_ad', 'yahman-add-ons')); ?></h3>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="google_ad_slot_inarticle"><?php echo sprintf( esc_html__( '%1$s ad %2$s', 'yahman-add-ons'),esc_html_x('In-article', 'google_ad', 'yahman-add-ons') , esc_html( 'data-ad-slot', 'yahman-add-ons') ); ?></label>
        <div class="ya_tooltip">
          <?php echo sprintf( esc_html__( 'Enter the %2$s for your %1$s ad', 'yahman-add-ons') , esc_html_x('In-article', 'google_ad', 'yahman-add-ons') , esc_html('data-ad-slot') ); ?><br>
          <?php echo esc_html__('e.g.&nbsp;', 'yahman-add-ons').esc_html('1234567890'); ?><br>
          <a href="<?php echo esc_url('https://support.google.com/adsense/answer/9189562?hl=' . $local ); ?>" target="_blank" rel="nofollow noopener noreferrer"><?php esc_html_e('AdSense Help', 'yahman-add-ons'); ?></a>
        </div>
      </div>
      <input name="yahman_addons[google_ad][slot_inarticle]" type="text" id="google_ad_slot_inarticle" value="<?php echo esc_attr($google_ad['slot_inarticle']); ?>" class="ya_textbox" />
    </div>

    <div class="ya_hr"></div>

    <label class="ya_link_color" for="ya_widget" onclick="to_top();">
      <?php esc_html_e('There are related widgets.','yahman-add-ons'); ?><br>
      &rsaquo; <?php echo sprintf(esc_html__('Google AdSense %s Widget', 'yahman-add-ons'),esc_html_x('In-article', 'google_ad', 'yahman-add-ons')); ?>
    </label>

    <h3><?php echo sprintf(esc_html__('%s ad setting', 'yahman-add-ons'),esc_html_x('Link', 'google_ad', 'yahman-add-ons')); ?></h3>

    <div class="important" style="background-color: #fce8e6;color: #b31412;border-radius: 4px;padding: 16px 20px;margin:24px auto;"><?php esc_html_e('On March 10, 2021,','yahman-add-ons'); ?> <a href="<?php echo esc_url('https://support.google.com/adsense/answer/9987221?hl=' . $local ); ?>" target="_blank" rel="nofollow noopener noreferrer"><?php esc_html_e('link units are being retired.','yahman-add-ons'); ?></a></div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="google_ad_slot_link"><?php echo sprintf( esc_html__( '%1$s ad %2$s', 'yahman-add-ons'),esc_html_x('Link', 'google_ad', 'yahman-add-ons') , esc_html( 'data-ad-slot', 'yahman-add-ons') ); ?></label>
        <div class="ya_tooltip">
          <?php echo sprintf( esc_html__( 'Enter the %2$s for your %1$s ad', 'yahman-add-ons') , esc_html_x('Link', 'google_ad', 'yahman-add-ons') , esc_html('data-ad-slot') ); ?><br>
          <?php echo esc_html__('e.g.&nbsp;', 'yahman-add-ons').esc_html('1234567890'); ?><br>
          <a href="<?php echo esc_url('https://support.google.com/adsense/answer/9189562?hl=' . $local ); ?>" target="_blank" rel="nofollow noopener noreferrer"><?php esc_html_e('AdSense Help', 'yahman-add-ons'); ?></a>
        </div>
      </div>
      <input name="yahman_addons[google_ad][slot_link]" type="text" id="google_ad_slot_link" value="<?php echo esc_attr($google_ad['slot_link']); ?>" class="ya_textbox" />
    </div>

    <div class="ya_hr"></div>

    <label class="ya_link_color" for="ya_widget" onclick="to_top();">
      <?php esc_html_e('There are related widgets.','yahman-add-ons'); ?><br>
      &rsaquo; <?php echo sprintf(esc_html__('Google AdSense %s Widget', 'yahman-add-ons'),esc_html_x('Link unit', 'google_ad', 'yahman-add-ons')); ?>
    </label>


    <h3><?php echo sprintf(esc_html__('%s setting', 'yahman-add-ons'),esc_html_x('Matched content', 'google_ad', 'yahman-add-ons')); ?></h3>

    <div class="important" style="background-color: #fce8e6;color: #b31412;border-radius: 4px;padding: 16px 20px;margin:24px auto;"><?php esc_html_e('On March 1, 2022,','yahman-add-ons'); ?> <a href="<?php echo esc_url('https://support.google.com/adsense/answer/11170711?hl=' . $local ); ?>" target="_blank" rel="nofollow noopener noreferrer"><?php esc_html_e('Important changes to Matched content.','yahman-add-ons'); ?></a></div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="google_ad_slot_autorelaxed"><?php echo sprintf( esc_html__( '%1$s ad %2$s', 'yahman-add-ons'),esc_html_x('Matched content', 'google_ad', 'yahman-add-ons') , esc_html( 'data-ad-slot', 'yahman-add-ons') ); ?></label>
        <div class="ya_tooltip">
          <?php echo sprintf( esc_html__( 'Enter the %2$s for your %1$s ad', 'yahman-add-ons') , esc_html_x('Matched content', 'google_ad', 'yahman-add-ons') , esc_html('data-ad-slot') ); ?><br>
          <?php echo esc_html__('e.g.&nbsp;', 'yahman-add-ons').esc_html('1234567890'); ?><br>
          <a href="<?php echo esc_url('https://support.google.com/adsense/answer/9189566?hl=' . $local ); ?>" target="_blank" rel="nofollow noopener noreferrer"><?php esc_html_e('AdSense Help', 'yahman-add-ons'); ?></a>
        </div>
      </div>
      <input name="yahman_addons[google_ad][slot_autorelaxed]" type="text" id="google_ad_slot_autorelaxed" value="<?php echo esc_attr($google_ad['slot_autorelaxed']); ?>" class="ya_textbox" />
    </div>


    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="google_ad_rows_pc_autorelaxed">
          <?php echo sprintf(esc_html__( 'content %1$s for %2$s', 'yahman-add-ons'),esc_html_x('rows num', 'google_ad', 'yahman-add-ons'),esc_html_x('desktop', 'google_ad', 'yahman-add-ons')); ?>
        </label>
        <div class="ya_tooltip">
          <a href="<?php echo esc_url('https://support.google.com/adsense/answer/7533385?hl=' . $local ); ?>" target="_blank" rel="nofollow noopener noreferrer"><?php esc_html_e('AdSense Help', 'yahman-add-ons'); ?></a>
        </div>
      </div>
      <select name="yahman_addons[google_ad][rows_pc_autorelaxed]" id="google_ad_rows_pc_autorelaxed">
        <?php
        foreach($num_type as $num_type_key => $num_type_value){
          echo '<option value="'.$num_type_key.'"';
          selected( $google_ad['rows_pc_autorelaxed'], $num_type_key );
          echo '>'.$num_type_value.'</option>';
        }
        ?>
      </select>
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="google_ad_columns_pc_autorelaxed">
          <?php echo sprintf(esc_html__( 'content %1$s for %2$s', 'yahman-add-ons'),esc_html_x('columns num', 'google_ad', 'yahman-add-ons'),esc_html_x('desktop', 'google_ad', 'yahman-add-ons')); ?>
        </label>
        <div class="ya_tooltip">
          <a href="<?php echo esc_url('https://support.google.com/adsense/answer/7533385?hl=' . $local ); ?>" target="_blank" rel="nofollow noopener noreferrer"><?php esc_html_e('AdSense Help', 'yahman-add-ons'); ?></a>
        </div>
      </div>
      <select name="yahman_addons[google_ad][columns_pc_autorelaxed]" id="google_ad_columns_pc_autorelaxed">
        <?php
        foreach($num_type as $num_type_key => $num_type_value){
          echo '<option value="'.$num_type_key.'"';
          selected( $google_ad['columns_pc_autorelaxed'], $num_type_key );
          echo '>'.$num_type_value.'</option>';
        }
        ?>
      </select>
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="google_ad_ui_pc_autorelaxed">
          <?php echo sprintf(esc_html__( 'content %1$s for %2$s', 'yahman-add-ons'),esc_html_x('layout', 'google_ad', 'yahman-add-ons'),esc_html_x('desktop', 'google_ad', 'yahman-add-ons')); ?>
        </label>
        <div class="ya_tooltip">
          <a href="<?php echo esc_url('https://support.google.com/adsense/answer/7533385?hl=' . $local ); ?>" target="_blank" rel="nofollow noopener noreferrer"><?php esc_html_e('AdSense Help', 'yahman-add-ons'); ?></a>
        </div>
      </div>
      <select name="yahman_addons[google_ad][ui_pc_autorelaxed]" id="google_ad_ui_pc_autorelaxed">
        <?php
        foreach($ui_type as $ui_type_key => $ui_type_value){
          echo '<option value="'.$ui_type_key.'"';
          selected( $google_ad['ui_pc_autorelaxed'], $ui_type_key );
          echo '>'.$ui_type_value.'</option>';
        }
        ?>
      </select>
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="google_ad_rows_mobile_autorelaxed">
          <?php echo sprintf(esc_html__( 'content %1$s for %2$s', 'yahman-add-ons'),esc_html_x('rows num', 'google_ad', 'yahman-add-ons'),esc_html_x('mobile', 'google_ad', 'yahman-add-ons')); ?>
        </label>
        <div class="ya_tooltip">
          <a href="<?php echo esc_url('https://support.google.com/adsense/answer/7533385?hl=' . $local ); ?>" target="_blank" rel="nofollow noopener noreferrer"><?php esc_html_e('AdSense Help', 'yahman-add-ons'); ?></a>
        </div>
      </div>
      <select name="yahman_addons[google_ad][rows_mobile_autorelaxed]" id="google_ad_rows_mobile_autorelaxed">
        <?php
        foreach($num_type as $num_type_key => $num_type_value){
          echo '<option value="'.$num_type_key.'"';
          selected( $google_ad['rows_mobile_autorelaxed'], $num_type_key );
          echo '>'.$num_type_value.'</option>';
        }
        ?>
      </select>
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="google_ad_columns_mobile_autorelaxed">
          <?php echo sprintf(esc_html__( 'content %1$s for %2$s', 'yahman-add-ons'),esc_html_x('columns num', 'google_ad', 'yahman-add-ons'),esc_html_x('mobile', 'google_ad', 'yahman-add-ons')); ?>
        </label>
        <div class="ya_tooltip">
          <a href="<?php echo esc_url('https://support.google.com/adsense/answer/7533385?hl=' . $local ); ?>" target="_blank" rel="nofollow noopener noreferrer"><?php esc_html_e('AdSense Help', 'yahman-add-ons'); ?></a>
        </div>
      </div>
      <select name="yahman_addons[google_ad][columns_mobile_autorelaxed]" id="google_ad_columns_mobile_autorelaxed">
        <?php
        foreach($num_type as $num_type_key => $num_type_value){
          echo '<option value="'.$num_type_key.'"';
          selected( $google_ad['columns_mobile_autorelaxed'], $num_type_key );
          echo '>'.$num_type_value.'</option>';
        }
        ?>
      </select>
    </div>

    <div class="ya_setting_content">
      <div class="ya_tooltip_wrap">
        <label for="google_ad_ui_mobile_autorelaxed">
          <?php echo sprintf(esc_html__( 'content %1$s for %2$s', 'yahman-add-ons'),esc_html_x('layout', 'google_ad', 'yahman-add-ons'),esc_html_x('mobile', 'google_ad', 'yahman-add-ons')); ?>
        </label>
        <div class="ya_tooltip">
          <a href="<?php echo esc_url('https://support.google.com/adsense/answer/7533385?hl=' . $local ); ?>" target="_blank" rel="nofollow noopener noreferrer"><?php esc_html_e('AdSense Help', 'yahman-add-ons'); ?></a>
        </div>
      </div>
      <select name="yahman_addons[google_ad][ui_mobile_autorelaxed]" id="google_ad_ui_mobile_autorelaxed">
        <?php
        foreach($ui_type as $ui_type_key => $ui_type_value){
          echo '<option value="'.$ui_type_key.'"';
          selected( $google_ad['ui_mobile_autorelaxed'], $ui_type_key );
          echo '>'.$ui_type_value.'</option>';
        }
        ?>
      </select>
    </div>


    <div class="ya_hr"></div>

    <label class="ya_link_color" for="ya_widget" onclick="to_top();">
      <?php esc_html_e('There are related widgets.','yahman-add-ons'); ?><br>
      &rsaquo; <?php echo sprintf(esc_html__('Google AdSense %s Widget', 'yahman-add-ons'),esc_html_x('Matched content', 'google_ad', 'yahman-add-ons')); ?><br>
    </label>



  </div>




  <?php
}
