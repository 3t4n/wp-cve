<div id="tab-4">
  <div class="row">
    <h3 class="sbs-6310-tab-menu-settings">Description Settings</h3>
    <div class="sbs-6310-col-50">
      <table class="table table-responsive sbs_6310_admin_table">
        <tr>
          <td><b>Description Show/Hide</b></td>
          <td>
            <label class="switch">
              <input type="checkbox" <?php echo (isset($cssData['template_details_show_hide']) && $cssData['template_details_show_hide']) ? ' checked' : '' ?> name="template_details_show_hide" value="1" id="template_details_show_hide">
              <span class="slider round template_details_show_hide"></span>
            </label>
          </td>
        </tr>
        <tr class="details_act_field">
          <td><b>Font Size</b></td>
          <td>
            <input type="number" min="0" name="sbs_6310_details_font_size" value="<?php echo esc_attr($cssData['sbs_6310_details_font_size']) ?>" class="sbs-6310-form-input" step="1" id="sbs_6310_details_font_size" />
          </td>
        </tr>
        <tr class="details_act_field">
          <td><b>Line Height</b></td>
          <td>
            <input name="sbs_6310_details_line_height" id="sbs_6310_details_line_height" type="number" value="<?php echo esc_attr($cssData['sbs_6310_details_line_height']) ?>" class="sbs-6310-form-input" />
          </td>
        </tr>
        <tr class="details_act_field sbs_6310_details_font_color_id">
          <td><b>Font Color</b></td>
          <td>
            <input type="text" name="sbs_6310_details_font_color" id="sbs_6310_details_font_color" class="sbs-6310-form-input sbs_6310_color_picker" data-format="rgb" value="<?php echo esc_attr($cssData['sbs_6310_details_font_color']) ?>">
          </td>
        </tr>
        <tr class="details_act_field sbs_6310_details_font_hover_color_id">
          <td><b>Font Hover Color</b></td>
          <td>
            <input type="text" name="sbs_6310_details_font_hover_color" id="sbs_6310_details_font_hover_color" class="sbs-6310-form-input sbs_6310_color_picker" data-format="rgb" value="<?php echo esc_attr($cssData['sbs_6310_details_font_hover_color']) ?>">
          </td>
        </tr>
      </table>
    </div>

    <div class="sbs-6310-col-50">
      <table class="table table-responsive sbs_6310_admin_table">
        <tr class="details_act_field">
          <td><b>Font Weight</b></td>
          <td>
            <select name="sbs_6310_details_font_weight" class="sbs-6310-form-input" id="sbs_6310_details_font_weight">
              <option value="100" <?php if ($cssData['sbs_6310_details_font_weight'] == '100') echo " selected=''" ?>>100
              </option>
              <option value="200" <?php if ($cssData['sbs_6310_details_font_weight'] == '200') echo " selected=''" ?>>200
              </option>
              <option value="300" <?php if ($cssData['sbs_6310_details_font_weight'] == '300') echo " selected=''" ?>>300
              </option>
              <option value="400" <?php if ($cssData['sbs_6310_details_font_weight'] == '400') echo " selected=''" ?>>400
              </option>
              <option value="500" <?php if ($cssData['sbs_6310_details_font_weight'] == '500') echo " selected=''" ?>>500
              </option>
              <option value="600" <?php if ($cssData['sbs_6310_details_font_weight'] == '600') echo " selected=''" ?>>600
              </option>
              <option value="700" <?php if ($cssData['sbs_6310_details_font_weight'] == '700') echo " selected=''" ?>>700
              </option>
              <option value="800" <?php if ($cssData['sbs_6310_details_font_weight'] == '800') echo " selected=''" ?>>800
              </option>
              <option value="900" <?php if ($cssData['sbs_6310_details_font_weight'] == '900') echo " selected=''" ?>>900
              </option>
              <option value="normal" <?php if ($cssData['sbs_6310_details_font_weight'] == 'normal') echo " selected=''" ?>>
                Normal</option>
              <option value="bold" <?php if ($cssData['sbs_6310_details_font_weight'] == 'bold') echo " selected=''" ?>>Bold
              </option>
              <option value="lighter" <?php if ($cssData['sbs_6310_details_font_weight'] == 'lighter') echo " selected=''" ?>>
                Lighter</option>
              <option value="initial" <?php if ($cssData['sbs_6310_details_font_weight'] == 'initial') echo " selected=''" ?>>
                Initial</option>
            </select>
          </td>
        </tr>
        <tr class="details_act_field">
          <td><b>Text Transform</b></td>
          <td>
            <select name="sbs_6310_details_text_transform" class="sbs-6310-form-input" id="sbs_6310_details_text_transform">
              <option value="capitalize" <?php if ($cssData['sbs_6310_details_text_transform'] == 'capitalize') echo " selected=''" ?>>Capitalize</option>
              <option value="uppercase" <?php if ($cssData['sbs_6310_details_text_transform'] == 'uppercase') echo " selected=''" ?>>Uppercase</option>
              <option value="lowercase" <?php if ($cssData['sbs_6310_details_text_transform'] == 'lowercase') echo " selected=''" ?>>Lowercase</option>
              <option value="none" <?php if ($cssData['sbs_6310_details_text_transform'] == 'none') echo " selected=''" ?>>As
                Input</option>
            </select>
          </td>
        </tr>
        <tr class="details_act_field sbs_6310_details_text_align_hide">
          <td><b>Text Align</b></td>
          <td>
            <select name="sbs_6310_details_text_align" class="sbs-6310-form-input" id="sbs_6310_details_text_align">
              <option value="center" <?php if ($cssData['sbs_6310_details_text_align'] == 'center') echo " selected=''" ?>>Center
              </option>
              <option value="left" <?php if ($cssData['sbs_6310_details_text_align'] == 'left') echo " selected=''" ?>>Left
              </option>
              <option value="right" <?php if ($cssData['sbs_6310_details_text_align'] == 'right') echo " selected=''" ?>>Right
              </option>
              <option value="justify" <?php if ($cssData['sbs_6310_details_text_align'] == 'justify') echo " selected=''" ?>>
                Justify</option>
            </select>
          </td>
        </tr>
        <tr class="details_act_field">
          <td><b>Font Family</b></td>
          <td>
            <input name="sbs_6310_details_font_family" id="sbs_6310_details_font_family" type="text" value="<?php echo esc_attr($cssData['sbs_6310_details_font_family']) ?>" />
          </td>
        </tr>
        <tr class="details_act_field">
          <td><b>Margin Top</b></td>
          <td>
            <input name="sbs_6310_details_margin_top" id="sbs_6310_details_margin_top" type="number" value="<?php echo esc_attr($cssData['sbs_6310_details_margin_top']) ?>" class="sbs-6310-form-input" />
          </td>
        </tr>
        <tr class="details_act_field">
          <td><b>Margin Bottom</b></td>
          <td>
            <input name="sbs_6310_details_margin_bottom" id="sbs_6310_details_margin_bottom" type="number" value="<?php echo esc_attr($cssData['sbs_6310_details_margin_bottom']) ?>" class="sbs-6310-form-input" />
          </td>
        </tr>
      </table>
    </div>
  </div>
</div>