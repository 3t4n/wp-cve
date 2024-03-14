<div id="tab-12">
  <div class="row">
    <h3 class="sbs-6310-tab-menu-settings">Search Settings</h3>
    <div class="sbs-6310-col-50">
      <table class="table table-responsive sbs_6310_admin_table" width="100%">
        <tr height="45">
          <td>
            <b>Activate Search</b>
            <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span><br />    
            <small style="color:#2196F3">(Available only in non-slider)</small>
          </td>
          <td>
            <label class="switch" for="sbs_6310_search_activation">
              <input type="checkbox" name="search_activation" id="sbs_6310_search_activation" value="1" <?php echo (isset($cssData['search_activation']) && $cssData['search_activation']) ? 'checked' : '' ?>>
              <span class="slider round"></span>
            </label>
          </td>
        </tr>
        <tr height="45" class="search_act_field">
          <td>
            <b>Placeholder Text</b>
            <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
          </td>
          <td>
            <input type="text" name="search_placeholder" id="sbs_6310_search_placeholder" class="sbs-6310-form-input" value="<?php echo (isset($cssData['search_placeholder']) && $cssData['search_placeholder'] !== '') ? esc_attr($cssData['search_placeholder']) : 'Search by Name or Designation' ?>">
          </td>
        </tr>
        <tr height="45" class="search_act_field">
          <td>
            <b>Alignment</b>
            <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
          </td>
          <td>
            <select name="search_align" class="sbs-6310-form-input" id="sbs_6310_search_align">
              <option value="center" <?php if (isset($cssData['search_align']) && $cssData['search_align'] == 'center') echo "selected" ?>>
                Center</option>
              <option value="flex-start" <?php if (isset($cssData['search_align']) && $cssData['search_align'] == 'flex-start') echo "selected" ?>>Left
              </option>
              <option value="flex-end" <?php if (!isset($cssData['search_align']) || (isset($cssData['search_align']) && $cssData['search_align'] == 'flex-end')) echo "selected" ?>>Right
              </option>
            </select>
          </td>
        </tr>
        <tr height="45" class="search_act_field">
          <td>
            <b>Font Color</b>
            <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
          </td>
          <td>
            <input type="text" name="search_font_color" id="sbs_6310_search_font_color" class="sbs-6310-form-input sbs_6310_color_picker" data-format="rgb" value="<?php echo (isset($cssData['search_font_color']) && $cssData['search_font_color'] !== '') ? esc_attr($cssData['search_font_color']) : 'rgb(0, 0, 0)' ?>">
          </td>
        </tr>
        <tr height="45" class="search_act_field">
          <td>
            <b>Placeholder Font Color</b>
            <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
          </td>
          <td>
            <input type="text" name="search_placeholder_font_color" id="sbs_6310_search_placeholder_font_color" class="sbs-6310-form-input sbs_6310_color_picker" data-format="rgb" value="<?php echo (isset($cssData['search_placeholder_font_color']) && $cssData['search_placeholder_font_color'] !== '') ? esc_attr($cssData['search_placeholder_font_color']) : 'rgb(128, 128, 128)' ?>">
          </td>
        </tr>
      </table>
    </div>
    <div class="sbs-6310-col-50">
      <table class="table table-responsive sbs_6310_admin_table search_act_field">
        <tr height="45">
          <td>
            <b>Search Box Height</b>
            <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
          </td>
          <td>
            <input type="number" min="0" name="search_height" value="<?php echo (isset($cssData['search_height']) && $cssData['search_height'] !== '') ? esc_attr($cssData['search_height']) : 40 ?>" class="sbs-6310-form-input" id="sbs_6310_search_height" />
          </td>
        </tr>
        <tr height="45">
          <td>
            <b>Border Width</b>
            <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
          </td>
          <td>
            <input type="number" min="0" name="search_border_width" value="<?php echo (isset($cssData['search_border_width']) && $cssData['search_border_width'] !== '') ? esc_attr($cssData['search_border_width']) : 2 ?>" class="sbs-6310-form-input" id="sbs_6310_search_border_width" />
          </td>
        </tr>
        <tr height="45">
          <td>
            <b>Border Color</b>
            <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
          </td>
          <td>
            <input type="text" name="search_border_color" id="sbs_6310_search_border_color" class="sbs-6310-form-input sbs_6310_color_picker" data-format="rgb" data-opacity=".8" value="<?php echo (isset($cssData['search_border_color']) && $cssData['search_border_color'] !== '') ? esc_attr($cssData['search_border_color']) : 'rgba(0, 0, 0, 1)' ?>">
          </td>
        </tr>
        <tr height="45">
          <td>
            <b>Border Radius</b>
            <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
          </td>
          <td>
            <input type="number" min="0" name="search_border_radius" value="<?php echo (isset($cssData['search_border_radius']) && $cssData['search_border_radius'] !== '') ? esc_attr($cssData['search_border_radius']) : 50 ?>" class="sbs-6310-form-input" id="sbs_6310_search_border_radius" />
          </td>
        </tr>
        <tr height="45">
          <td>
            <b>Margin Bottom</b>
            <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span>
          </td>
          <td>
            <input name="sbs_6310_search_margin_bottom" id="sbs_6310_search_margin_bottom" type="number" min="0" value="<?php echo (isset($cssData['sbs_6310_search_margin_bottom']) && $cssData['sbs_6310_search_margin_bottom'] !== '') ? esc_attr($cssData['sbs_6310_search_margin_bottom']) : 10; ?>" class="sbs-6310-form-input" />
          </td>
        </tr>
      </table>
    </div>
  </div>
</div>