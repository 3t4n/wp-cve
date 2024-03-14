<div class="sbs_6310_tabs_panel_settings">
  <form method="post">
    <?php wp_nonce_field("sbs_6310_nonce_field_form") ?>
    <input type="hidden" name="id" value="<?php echo esc_attr($styleId) ?>" />
    <div class="sbs_6310_padding_15_px">
      <?php include sbs_6310_plugin_url . 'settings/helper/tab-menu.php'; ?>
    </div>
    <div class="sbs-6310-tab-content">
      <div id="tab-1">
        <div class="row sbs_6310_padding_15_px">
          <h3 class="sbs-6310-tab-menu-settings">General Settings</h3>
          <div class="sbs-6310-col-50">
            <table class="table table-responsive sbs_6310_admin_table">
              <?php sbs_6310_general_form_left($bgType, $cssData) ?>
              <tr height="45">
                <td>
                  <b>Items Margin</b>
                  <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
                  <div class="sbs-6310-no-live-preview">Live Preview Not Available</div>
                </td>
                <td>
                  <input type="number" name="item_margin" id="sbs_6310_item_margin" class="sbs-6310-form-input" value="<?php echo (isset($cssData['item_margin']) && $cssData['item_margin']) ? esc_attr($cssData['item_margin']) : "15" ?>">
                </td>
              </tr>
              <tr>
                <td><b>Box Radius</b>
                  <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
                </td>
                <td>
                  <input type="number" min="0" name="sbs_6310_box_radius" value="<?php echo  esc_attr($cssData['sbs_6310_box_radius']) ?>" class="sbs-6310-form-input" id="sbs_6310_box_radius" />
                </td>
              </tr>
            </table>
          </div>
          <div class="sbs-6310-col-50">
            <table class="table table-responsive sbs_6310_admin_table">
              <tr>
                <td><b>Box Background</b>
                  <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
                </td>
                <td>
                  <input type="text" name="sbs_6310_box_background_color" id="sbs_6310_box_background_color" class="sbs-6310-form-input sbs_6310_color_picker" data-format="rgb" data-opacity=".8" value="<?php echo esc_attr($cssData['sbs_6310_box_background_color']) ?>">
                </td>
              </tr>
              <tr>
                <td width="55%"><b>Box Hover Background</b>
                  <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
                </td>
                <td>
                  <input type="text" name="sbs_6310_box_background_hover_color" id="sbs_6310_box_background_hover_color" class="sbs-6310-form-input sbs_6310_color_picker" data-opacity=".8" data-format="rgb" value="<?php echo esc_attr($cssData['sbs_6310_box_background_hover_color']) ?>">
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>
      <div id="tab-2">
        <div class="row">
          <h3 class="sbs-6310-tab-menu-settings">Title Settings</h3>
          <div class="sbs-6310-col-50">
            <table class="table table-responsive sbs_6310_admin_table">
              <tr>
                <td><b>Font Size</b>
                  <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
                </td>
                <td>
                  <input type="number" min="0" name="sbs_6310_title_font_size" value="<?php echo esc_attr($cssData['sbs_6310_title_font_size']) ?>" class="sbs-6310-form-input" step="1" id="sbs_6310_title_font_size" />
                </td>
              </tr>
              <tr>
                <td><b>Line Height</b>
                  <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
                </td>
                <td>
                  <input name="sbs_6310_title_line_height" id="sbs_6310_title_line_height" type="number" value="<?php echo esc_attr($cssData['sbs_6310_title_line_height']) ?>" class="sbs-6310-form-input" />
                </td>
              </tr>
              <tr>
                <td><b>Font Color</b>
                  <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
                </td>
                <td>
                  <input type="text" name="sbs_6310_title_font_color" id="sbs_6310_title_font_color" class="sbs-6310-form-input sbs_6310_color_picker" data-format="rgb" value="<?php echo esc_attr($cssData['sbs_6310_title_font_color']) ?>">
                </td>
              </tr>
              <tr>
                <td><b>Font Hover Color</b>
                  <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
                </td>
                <td>
                  <input type="text" name="sbs_6310_title_font_hover_color" id="sbs_6310_title_font_hover_color" class="sbs-6310-form-input sbs_6310_color_picker" data-format="rgb" value="<?php echo esc_attr($cssData['sbs_6310_title_font_hover_color']) ?>">
                </td>
              </tr>
              <tr>
                <td><b>Font Weight</b>
                  <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
                </td>
                <td>
                  <select name="sbs_6310_title_font_weight" class="sbs-6310-form-input" id="sbs_6310_title_font_weight">
                    <option value="100" <?php if ($cssData['sbs_6310_title_font_weight'] == '100') echo " selected=''" ?>>100</option>
                    <option value="200" <?php if ($cssData['sbs_6310_title_font_weight'] == '200') echo " selected=''" ?>>200</option>
                    <option value="300" <?php if ($cssData['sbs_6310_title_font_weight'] == '300') echo " selected=''" ?>>300</option>
                    <option value="400" <?php if ($cssData['sbs_6310_title_font_weight'] == '400') echo " selected=''" ?>>400</option>
                    <option value="500" <?php if ($cssData['sbs_6310_title_font_weight'] == '500') echo " selected=''" ?>>500</option>
                    <option value="600" <?php if ($cssData['sbs_6310_title_font_weight'] == '600') echo " selected=''" ?>>600</option>
                    <option value="700" <?php if ($cssData['sbs_6310_title_font_weight'] == '700') echo " selected=''" ?>>700</option>
                    <option value="800" <?php if ($cssData['sbs_6310_title_font_weight'] == '800') echo " selected=''" ?>>800</option>
                    <option value="900" <?php if ($cssData['sbs_6310_title_font_weight'] == '900') echo " selected=''" ?>>900</option>
                    <option value="normal" <?php if ($cssData['sbs_6310_title_font_weight'] == 'normal') echo " selected=''" ?>>Normal</option>
                    <option value="bold" <?php if ($cssData['sbs_6310_title_font_weight'] == 'bold') echo " selected=''" ?>>Bold</option>
                    <option value="lighter" <?php if ($cssData['sbs_6310_title_font_weight'] == 'lighter') echo " selected=''" ?>>Lighter</option>
                    <option value="initial" <?php if ($cssData['sbs_6310_title_font_weight'] == 'initial') echo " selected=''" ?>>Initial</option>
                  </select>
                </td>
              </tr>
            </table>
          </div>
          <div class="sbs-6310-col-50">
            <table class="table table-responsive sbs_6310_admin_table">

              <tr>
                <td><b>Text Transform</b>
                  <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
                </td>
                <td>
                  <select name="sbs_6310_title_text_transform" class="sbs-6310-form-input" id="sbs_6310_title_text_transform">
                    <option value="capitalize" <?php if ($cssData['sbs_6310_title_text_transform'] == 'capitalize') echo " selected=''" ?>>Capitalize</option>
                    <option value="uppercase" <?php if ($cssData['sbs_6310_title_text_transform'] == 'uppercase') echo " selected=''" ?>>Uppercase</option>
                    <option value="lowercase" <?php if ($cssData['sbs_6310_title_text_transform'] == 'lowercase') echo " selected=''" ?>>Lowercase</option>
                    <option value="none" <?php if ($cssData['sbs_6310_title_text_transform'] == 'none') echo " selected=''" ?>>As Input</option>

                  </select>
                </td>
              </tr>
              <tr>
                <td><b>Text Align</b>
                  <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
                </td>
                <td>
                  <select name="sbs_6310_title_text_align" class="sbs-6310-form-input" id="sbs_6310_title_text_align">
                    <option value="center" <?php if ($cssData['sbs_6310_title_text_align'] == 'center') echo " selected=''" ?>>Center</option>
                    <option value="left" <?php if ($cssData['sbs_6310_title_text_align'] == 'left') echo " selected=''" ?>>Left</option>
                    <option value="right" <?php if ($cssData['sbs_6310_title_text_align'] == 'right') echo " selected=''" ?>>Right</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td><b>Font Family</b>
                  <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
                </td>
                <td>
                  <input name="sbs_6310_title_font_family" id="sbs_6310_title_font_family" type="text" value="<?php echo esc_attr($cssData['sbs_6310_title_font_family']) ?>" />
                </td>
              </tr>
              <tr>
                <td><b>Padding Top</b>
                  <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
                </td>
                <td>
                  <input name="sbs_6310_title_padding_top" id="sbs_6310_title_padding_top" type="number" value="<?php echo esc_attr($cssData['sbs_6310_title_padding_top']) ?>" class="sbs-6310-form-input" />
                </td>
              </tr>
              <tr>
                <td><b>Padding Bottom</b>
                  <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
                </td>
                <td>
                  <input name="sbs_6310_title_padding_bottom" id="sbs_6310_title_padding_bottom" type="number" value="<?php echo esc_attr($cssData['sbs_6310_title_padding_bottom']) ?>" class="sbs-6310-form-input" />
                </td>
              </tr>
            </table>
          </div>
        </div>
      </div>

      <div id="tab-5">
        <div class="row">
          <h3 class="sbs-6310-tab-menu-settings"> Icon Settings</h3>
          <div class="sbs-6310-col-50">
            <table class="table table-responsive sbs_6310_admin_table">
              <tr class="title-act-field">
                <td><b>Icon Size</b>
                  <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
                </td>
                <td>
                  <input type="number" min="0" name="sbs_6310_icon_font_size" value="<?php echo esc_attr($cssData['sbs_6310_icon_font_size']) ?>" class="sbs-6310-form-input" step="1" id="sbs_6310_icon_font_size" />
                </td>
              </tr>
              <tr>
                <td><b>Icon Color</b>
                  <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
                </td>
                <td>
                  <input type="text" name="sbs_6310_icon_color" id="sbs_6310_icon_color" class="sbs-6310-form-input sbs_6310_color_picker" data-format="rgb" value="<?php echo esc_attr($cssData['sbs_6310_icon_color']) ?>">
                </td>
              </tr>
              <tr>
                <td><b>Icon Hover Color</b>
                  <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
                </td>
                <td>
                  <input type="text" name="sbs_6310_icon_hover_color" id="sbs_6310_icon_hover_color" class="sbs-6310-form-input sbs_6310_color_picker" data-format="rgb" value="<?php echo esc_attr($cssData['sbs_6310_icon_hover_color']) ?>">
                </td>
              </tr>
            </table>
          </div>
          <div class="sbs-6310-col-50">
            <table class="table table-responsive sbs_6310_admin_table">

            </table>
          </div>
        </div>
      </div>

      <?php include sbs_6310_plugin_url . 'settings/form/_button-form.php'; ?>
      <?php include sbs_6310_plugin_url . 'settings/form/_slider-form.php'; ?>
      <?php include sbs_6310_plugin_url . 'settings/form/_search-form.php'; ?>
      <?php include sbs_6310_plugin_url . 'settings/helper/details_form.php'; ?>

      <br class="sbs-6310-clear" />
      <br class="sbs-6310-clear" />
      <hr />
      <input type="submit" name="update_style_change" value="Save" class="sbs-6310-btn-primary sbs-6310-pull-right" style="margin-right: 15px; margin-bottom: 10px; display: block" />
      <br class="sbs-6310-clear" />
    </div>
  </form>
</div>