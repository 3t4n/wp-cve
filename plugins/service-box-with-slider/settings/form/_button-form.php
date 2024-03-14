<div id="tab-10">
  <div class="row">
    <h3 class="sbs-6310-tab-menu-settings">Read More Settings</h3>
    <div class="sbs-6310-col-50">
      <table class="table table-responsive sbs_6310_admin_table">
        <tr>
          <td>
            <b>Read More Show/Hide</b>
            <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
          </td>
          <td>
            <label class="switch" for="sbs_6310_fun_template_button">
              <input type="checkbox" name="sbs_6310_fun_template_button" <?php echo isset($cssData['sbs_6310_fun_template_button']) ? ' checked' : '' ?> id="sbs_6310_fun_template_button" value="1">
              <span class="slider round"></span>
            </label>

          </td>
        </tr>
        <tr class="read_more_act_field">
          <td><b>Read More Height</b>
            <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
          </td>
          <td>
            <input type="number" min="0" name="sbs_6310_read_more_height" value="<?php echo esc_attr($cssData['sbs_6310_read_more_height']) ?>" class="sbs-6310-form-input" step="1" id="sbs_6310_read_more_height" />
          </td>
        </tr>
        <tr class="read_more_act_field">
          <td><b>Read More Width</b>
            <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
          </td>
          <td>
            <input type="number" name="sbs_6310_read_more_width" class="sbs-6310-form-input" id="sbs_6310_read_more_width" value="<?php echo esc_attr($cssData['sbs_6310_read_more_width']) ?>">
          </td>
        </tr>
        <tr class="read_more_act_field">
          <td><b>Read More Font Size</b>
            <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
          </td>
          <td>
            <input type="number" min="0" name="sbs_6310_read_more_font_size" value="<?php echo esc_attr($cssData['sbs_6310_read_more_font_size']) ?>" class="sbs-6310-form-input" step="1" id="sbs_6310_read_more_font_size" />
          </td>
        </tr>

        <tr class="read_more_act_field">
          <td><b>Read More Font Color</b>
            <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
          </td>
          <td>
            <input type="text" name="sbs_6310_read_more_font_color" id="sbs_6310_read_more_font_color" class="sbs-6310-form-input sbs_6310_color_picker" data-format="rgb" value="<?php echo esc_attr($cssData['sbs_6310_read_more_font_color']) ?>">
          </td>
        </tr>
        <tr class="read_more_act_field">
          <td><b>Read More Font Hover Color</b>
            <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
          </td>
          <td>
            <input type="text" name="sbs_6310_read_more_font_hover_color" id="sbs_6310_read_more_font_hover_color" class="sbs-6310-form-input sbs_6310_color_picker" data-format="rgb" value="<?php echo esc_attr($cssData['sbs_6310_read_more_font_hover_color']) ?>">
          </td>
        </tr>
        <tr class="read_more_act_field">
          <td><b>Read More Border Width</b>
            <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
          </td>
          <td>
            <select name="sbs_6310_read_more_border_width" class="sbs-6310-form-input" id="sbs_6310_read_more_border_width">
              <option value="0" <?php if ($cssData['sbs_6310_read_more_border_width'] == '0') echo " selected=''" ?>>None
              </option>
              <option value="1px" <?php if ($cssData['sbs_6310_read_more_border_width'] == '1px') echo " selected=''" ?>>1px
              </option>
              <option value="2px" <?php if ($cssData['sbs_6310_read_more_border_width'] == '2px') echo " selected=''" ?>>2px
              </option>
              <option value="3px" <?php if ($cssData['sbs_6310_read_more_border_width'] == '3px') echo " selected=''" ?>>3px
              </option>
              <option value="4px" <?php if ($cssData['sbs_6310_read_more_border_width'] == '4px') echo " selected=''" ?>>4px
              </option>
              <option value="5px" <?php if ($cssData['sbs_6310_read_more_border_width'] == '5px') echo " selected=''" ?>>5px
              </option>
            </select>
          </td>
        </tr>
        <tr class="read_more_act_field">
          <td><b>Read More Border Color</b>
            <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
          </td>
          <td>
            <input type="text" name="sbs_6310_read_more_box_border_color" id="sbs_6310_read_more_box_border_color" class="sbs-6310-form-input sbs_6310_color_picker" data-format="rgb" value="<?php echo esc_attr($cssData['sbs_6310_read_more_box_border_color']) ?>">
          </td>
        </tr>
        <tr class="read_more_act_field">
          <td><b>Read More Border Hover Color</b>
            <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
          </td>
          <td>
            <input type="text" name="sbs_6310_read_more_border_hover_color" id="sbs_6310_read_more_border_hover_color" class="sbs-6310-form-input sbs_6310_color_picker" data-format="rgb" value="<?php echo (isset($cssData['sbs_6310_read_more_border_hover_color']) && $cssData['sbs_6310_read_more_border_hover_color']) ? esc_attr($cssData['sbs_6310_read_more_border_hover_color']) : "#e60000" ?> ">
          </td>
        </tr>
        <tr class="read_more_act_field">
          <td><b>Read More Border Radius</b>
            <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
          </td>
          <td>
            <input type="number" name="sbs_6310_read_more_border_radius" id="sbs_6310_read_more_border_radius" class="sbs-6310-form-input " value="<?php echo esc_attr($cssData['sbs_6310_read_more_border_radius']) ?>">
          </td>
        </tr>
      </table>
    </div>
    <div class="sbs-6310-col-50">
      <table class="table table-responsive sbs_6310_admin_table">
        <tr class="read_more_act_field">
          <td><b>Read More Backgrond Color</b>
            <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
          </td>
          <td>
            <input type="text" name="sbs_6310_read_more_background_color" id="sbs_6310_read_more_background_color" class="sbs-6310-form-input sbs_6310_color_picker" data-format="rgb" value="<?php echo esc_attr($cssData['sbs_6310_read_more_background_color']) ?>">
          </td>
        </tr>
        <tr class="read_more_act_field">
          <td><b>Read More Backgrond Hover Color</b>
            <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
          </td>
          <td>
            <input type="text" name="sbs_6310_read_more_background_hover_color" id="sbs_6310_read_more_background_hover_color" class="sbs-6310-form-input sbs_6310_color_picker" data-format="rgb" value="<?php echo esc_attr($cssData['sbs_6310_read_more_background_hover_color']) ?>">
          </td>
        </tr>
        <tr class="read_more_act_field">
          <td><b>Read More Font Weight</b>
            <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
          </td>
          <td>
            <select name="sbs_6310_read_more_font_weight" class="sbs-6310-form-input" id="sbs_6310_read_more_font_weight">
              <option value="100" <?php if ($cssData['sbs_6310_read_more_font_weight'] == '100') echo " selected=''" ?>>100
              </option>
              <option value="200" <?php if ($cssData['sbs_6310_read_more_font_weight'] == '200') echo " selected=''" ?>>200
              </option>
              <option value="300" <?php if ($cssData['sbs_6310_read_more_font_weight'] == '300') echo " selected=''" ?>>300
              </option>
              <option value="400" <?php if ($cssData['sbs_6310_read_more_font_weight'] == '400') echo " selected=''" ?>>400
              </option>
              <option value="500" <?php if ($cssData['sbs_6310_read_more_font_weight'] == '500') echo " selected=''" ?>>500
              </option>
              <option value="600" <?php if ($cssData['sbs_6310_read_more_font_weight'] == '600') echo " selected=''" ?>>600
              </option>
              <option value="700" <?php if ($cssData['sbs_6310_read_more_font_weight'] == '700') echo " selected=''" ?>>700
              </option>
              <option value="800" <?php if ($cssData['sbs_6310_read_more_font_weight'] == '800') echo " selected=''" ?>>800
              </option>
              <option value="900" <?php if ($cssData['sbs_6310_read_more_font_weight'] == '900') echo " selected=''" ?>>900
              </option>
              <option value="normal" <?php if ($cssData['sbs_6310_read_more_font_weight'] == 'normal') echo " selected=''" ?>>
                Normal</option>
              <option value="bold" <?php if ($cssData['sbs_6310_read_more_font_weight'] == 'bold') echo " selected=''" ?>>Bold
              </option>
              <option value="lighter" <?php if ($cssData['sbs_6310_read_more_font_weight'] == 'lighter') echo " selected=''" ?>>
                Lighter</option>
              <option value="initial" <?php if ($cssData['sbs_6310_read_more_font_weight'] == 'initial') echo " selected=''" ?>>
                Initial</option>
            </select>
          </td>
        </tr>
        <tr class="read_more_act_field">
          <td><b>Read More Text Transform</b>
            <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
          </td>
          <td>
            <select name="sbs_6310_read_more_text_transform" class="sbs-6310-form-input" id="sbs_6310_read_more_text_transform">
              <option value="capitalize" <?php if ($cssData['sbs_6310_read_more_text_transform'] == 'capitalize') echo " selected=''" ?>>Capitalize
              </option>
              <option value="uppercase" <?php if ($cssData['sbs_6310_read_more_text_transform'] == 'uppercase') echo " selected=''" ?>>Uppercase</option>
              <option value="lowercase" <?php if ($cssData['sbs_6310_read_more_text_transform'] == 'lowercase') echo " selected=''" ?>>Lowercase</option>
              <option value="none" <?php if ($cssData['sbs_6310_read_more_text_transform'] == 'none') echo " selected=''" ?>>As
                Input</option>
            </select>
          </td>
        </tr>
        <tr class="read_more_act_field">
          <td><b>Read More Text Align</b>
            <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
          </td>
          <td>
            <select name="sbs_6310_read_more_text_align" class="sbs-6310-form-input" id="sbs_6310_read_more_text_align">
              <option value="center" <?php if ($cssData['sbs_6310_read_more_text_align'] == 'center') echo " selected=''" ?>>
                Center</option>
              <option value="left" <?php if ($cssData['sbs_6310_read_more_text_align'] == 'left') echo " selected=''" ?>>Left
              </option>
              <option value="right" <?php if ($cssData['sbs_6310_read_more_text_align'] == 'right') echo " selected=''" ?>>Right
              </option>

            </select>
          </td>
        </tr>
        <tr class="read_more_act_field">
          <td><b>Read More Font Family</b>
            <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
          </td>

          <td>
            <input name="sbs_6310_read_more_font_family" id="sbs_6310_read_more_font_family" type="text" value="<?php echo esc_attr($cssData['sbs_6310_read_more_font_family']) ?>" />
          </td>

        </tr>
        <tr class="read_more_act_field">
          <td><b>Read More Margin Top</b>
            <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
          </td>
          <td>
            <input name="sbs_6310_read_more_margin_top" id="sbs_6310_read_more_margin_top" type="number" value="<?php echo esc_attr($cssData['sbs_6310_read_more_margin_top']) ?>" class="sbs-6310-form-input" />
          </td>
        </tr>
        <tr class="read_more_act_field">
          <td><b>Read More Margin Bottom</b>
            <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
          </td>
          <td>
            <input name="sbs_6310_read_more_margin_bottom" id="sbs_6310_read_more_margin_bottom" type="number" value="<?php echo esc_attr($cssData['sbs_6310_read_more_margin_bottom']) ?>" class="sbs-6310-form-input" />
          </td>
        </tr>
      </table>
    </div>
  </div>
</div>