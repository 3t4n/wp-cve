<div class="sbs-6310">
   <div class="sbs-6310-sm">
    <?php 
      include sbs_6310_plugin_url . 'settings/helper/item-save.php';
      if (!empty($_POST['update_style_change']) && $_POST['update_style_change'] == 'Save' && $_POST['id'] != '') {
        $nonce = $_REQUEST['_wpnonce'];
        if (!wp_verify_nonce($nonce, 'sbs_6310_nonce_field_form')) {
            die('You do not have sufficient permissions to access this pagess.');
        } else {
            $css = sbs_6310_extract_data($_POST);
            $wpdb->query($wpdb->prepare("UPDATE $style_table SET css = %s WHERE id = %d", $css, sanitize_text_field($_POST['id'])));
        }
      }
      $styledata = $wpdb->get_row($wpdb->prepare("SELECT * FROM $style_table WHERE id = %d ", $styleId), ARRAY_A);
      $css = explode("!!##!!", $styledata['css']);
      $key = explode(",", $css[0]);
      $value = explode("||##||", $css[1]);
      $filterKey = [];
      $filterValue = [];
      for($i = 0; $i < count($key); $i++) {
        $filterKey[] = esc_attr($key[$i]);
      }
      for($i = 0; $i < count($value); $i++) {
        $filterValue[] = esc_attr($value[$i]);
      }
      $cssData = array_combine($filterKey, $filterValue);
      $results = sbs_6310_extract_item(esc_attr($styledata['itemids']));
      $tablet_row = isset($cssData['tablet_item_per_row']) ? esc_attr($cssData['tablet_item_per_row']) : 1;
      $mobile_row = isset($cssData['mobile_item_per_row']) ? esc_attr($cssData['mobile_item_per_row']) : 1;
      $bgType = isset($cssData['background_type']) ? esc_attr($cssData['background_type']) : 1;
      
      include sbs_6310_plugin_url . "settings/form/_form-".esc_attr($templateId).".php";
      include sbs_6310_plugin_url . "settings/css/_css-".esc_attr($templateId).".php";
    ?>
    <div class="sbs-6310-plugin-setting-left">
      <div class="sbs-6310-preview-box">
          <div class="sbs-6310-preview">
            Preview
            <div style="display: inline; float: right">
              <input type="text" id="sbs_6310_background_preview"
                class="sbs-6310-form-input  sbs-6310-pull-right sbs_6310_color_picker sbs_6310_preview_color_chooser" data-format="rgb"
                data-opacity=".8" value="rgba(255, 255, 255, .8)"></div>
          </div>
          <hr />
      </div>
      <div 
        class="sbs_6310_tabs_panel_preview" 
        data-main-template-id="<?php echo esc_attr($styleId) ?>"        
      >
        <div class="sbs-6310-service-box"
          sbs-6310-style-id='<?php echo esc_attr($templateId) ?>'
          sbs-6310-style-desktop='<?php echo esc_attr($cssData['desktop_item_per_row']) ?>'
          sbs-6310-style-tablet='<?php echo esc_attr($cssData['tablet_item_per_row']) ?>'
          sbs-6310-style-mobile='<?php echo esc_attr($cssData['mobile_item_per_row']) ?>'
          sbs-6310-slider-active="<?php echo isset($cssData['sbs_6310_fun_template_slider']) ? 1 : 0 ?>"
          sbs-6310-slider-margin='<?php echo esc_attr($cssData['item_margin']) ?>'
          sbs-6310-slider-duration='<?php echo esc_attr($cssData['effect_duration']) ?>'
          sbs-6310-slider-nav='<?php echo isset($cssData['prev_next_active']) ? 1 : 0 ?>'
          sbs-6310-slider-dot='<?php echo isset($cssData['indicator_activation']) ? 1 : 0 ?>'
          sbs-6310-slider-navText='<?php echo esc_attr($cssData['slider_icon_style']) ?>'
        >
            <?php include sbs_6310_plugin_url . 'settings/templates/' . $styledata['style_name'] . '.php';  ?>
            <?php include sbs_6310_plugin_url . 'settings/css/_common-css.php';  ?>
            <?php include sbs_6310_plugin_url . "settings/helper/_helper-".esc_attr($templateId).".php"; ?>
            <?php include sbs_6310_plugin_url . 'settings/helper/_common-script.php';  ?>
        </div>
      </div>
      <br />
    </div>
    <div class="sbs-6310-plugin-setting-right">
        <?php 
          sbs_6310_add_new_media($styleId, $styledata['itemids']);
        ?>
    </div>
   </div>
</div>   