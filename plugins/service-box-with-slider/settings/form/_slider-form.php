<div id="tab-8">
   <div class="row sbs_6310_padding_15_px">
      <h3 class="sbs-6310-tab-menu-settings">Slider Settings</h3>
      <div class="sbs-6310-col-50">
         <table class="table table-responsive sbs_6310_admin_table">
            <tr>
               <td>
                  <b>Activate Slider</b> <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span></td>
               <td>
                  <label class="switch" for="sbs_6310_fun_template_slider">
                     <input type="checkbox" name="sbs_6310_fun_template_slider" <?php echo isset($cssData['sbs_6310_fun_template_slider']) ? ' checked' : '' ?> id="sbs_6310_fun_template_slider" value="1">
                     <span class="slider round"></span>
                  </label>

               </td>
            </tr>
         </table>
         <table class="table table-responsive sbs_6310_admin_table sbs_6310_carousel_field">
            <tr>
               <td><b>Effect Duration</b> <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span><div class="sbs-6310-no-live-preview">Live Preview Not Available</div></td>
               <td>
                  <select name="effect_duration" id="effect_duration" class="sbs-6310-form-input">
                     <option value="1000" <?php if ($cssData['effect_duration'] == "1000") echo " selected" ?>>1 Second</option>
                     <?php
                     $n = 2000;
                     for ($m = 2; $m <= 20; $m++) {
                     ?>
                        <option value="<?php echo esc_attr($n); ?>" <?php if ($cssData['effect_duration'] == $n) echo " selected" ?>><?php echo esc_attr($m) ?> Seconds</option>
                     <?php
                        $n += 1000;
                     }
                     ?>
                  </select>
               </td>
            </tr>
            <tr>
               <td>
                  <b>Activate Previous/Next</b> <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span></td>
               <td>
                  <label class="switch" for="prev_next_active">
                     <input type="checkbox" name="prev_next_active" <?php echo isset($cssData['prev_next_active']) ? 'checked' : '' ?> id="prev_next_active" value="1">
                     <span class="slider round"></span>
                  </label>
               </td>
            </tr>
            <tr class="sbs_6310_prev_next_act">
               <td>
                  <b>Previous/Next Icon</b> <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span></td>
               <td>
                  <select name="slider_icon_style" id="slider_icon_style" class="sbs-6310-form-input">
                     <option value="fas fa-angle" <?php if ($cssData['slider_icon_style'] == "fas fa-angle") echo " selected=''" ?>>Angle</option>
                     <option value="fas fa-arrow" <?php if ($cssData['slider_icon_style'] == "fas fa-arrow") echo " selected=''" ?>>Arrow</option>
                     <option value="fas fa-arrow-circle" <?php if ($cssData['slider_icon_style'] == "fas fa-arrow-circle") echo " selected=''" ?>>Arrow Circle</option>
                     <option value="far fa-arrow-alt-circle" <?php if ($cssData['slider_icon_style'] == "far fa-arrow-alt-circle") echo " selected=''" ?>>Arrow Circle2</option>
                     <option value="fas fa-caret" <?php if ($cssData['slider_icon_style'] == "fas fa-caret") echo " selected=''" ?>>Caret</option>
                     <option value="fas fa-caret-square" <?php if ($cssData['slider_icon_style'] == "fas fa-caret-square") echo " selected=''" ?>>Caret Square</option>
                     <option value="fas fa-chevron" <?php if ($cssData['slider_icon_style'] == "fas fa-chevron") echo " selected=''" ?>>Chevron</option>
                     <option value="fas fa-chevron-circle" <?php if ($cssData['slider_icon_style'] == "fas fa-chevron-circle") echo " selected=''" ?>>Chevron Circle</option>
                  </select>
               </td>
            </tr>
            <tr class="sbs_6310_prev_next_act">
               <td>
                  <b>Previous/Next Icon Size</b> <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span></td>
               <td>
                  <input type="number" min="0" name="slider_prev_next_icon_size" id="slider_prev_next_icon_size" class="sbs-6310-form-input" value="<?php echo esc_attr($cssData['slider_prev_next_icon_size']) ?>" />
               </td>
            </tr>
            <tr class="sbs_6310_prev_next_act">
               <td>
                  <b>Border Radius</b> <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span></td>
               <td>
                  <input type="number" min="0" name="slider_prev_next_icon_border_radius" id="slider_prev_next_icon_border_radius" class="sbs-6310-form-input" value="<?php echo esc_attr($cssData['slider_prev_next_icon_border_radius']) ?>" />
               </td>
            </tr>
            <tr class="sbs_6310_prev_next_act">
               <td><b>Previous/Next Background Color</b> <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span></td>
               <td>
                  <input type="text" name="slider_prev_next_bgcolor" id="slider_prev_next_bgcolor" class="sbs-6310-form-input sbs_6310_color_picker" data-format="rgb" data-opacity=".8" value="<?php echo esc_attr($cssData['slider_prev_next_bgcolor']) ?>">
               </td>
            </tr>
            <tr class="sbs_6310_prev_next_act">
               <td><b>Previous/Next Text Color</b> <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span></td>
               <td>
                  <input type="text" name="slider_prev_next_color" id="slider_prev_next_color" class="sbs-6310-form-input sbs_6310_color_picker" data-format="rgb" data-opacity=".8" value="<?php echo esc_attr($cssData['slider_prev_next_color']) ?>">
               </td>

            </tr>
            <tr class="sbs_6310_prev_next_act">
               <td><b>Previous/Next Hover Background Color</b> <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span></td>
               <td>
                  <input type="text" name="slider_prev_next_hover_bgcolor" id="slider_prev_next_hover_bgcolor" class="sbs-6310-form-input sbs_6310_color_picker" data-format="rgb" data-opacity=".8" value="<?php echo esc_attr($cssData['slider_prev_next_hover_bgcolor']) ?>">
               </td>
            </tr>
            <tr class="sbs_6310_prev_next_act">
               <td><b>Previous/Next Hover Text Color</b> <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span></td>
               <td>
                  <input type="text" name="slider_prev_next_hover_color" id="slider_prev_next_hover_color" class="sbs-6310-form-input sbs_6310_color_picker" data-format="rgb" data-opacity=".8" value="<?php echo esc_attr($cssData['slider_prev_next_hover_color']) ?>">
               </td>
            </tr>
         </table>
      </div>
      <div class="sbs-6310-col-50">
         <table class="table table-responsive sbs_6310_admin_table sbs_6310_carousel_field">
            <tr>
               <td>
                  <b>Activate Indicator</b> <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span></td>
               <td>
                  <label class="switch" for="indicator_activation">
                     <input type="checkbox" name="indicator_activation" <?php echo isset($cssData['indicator_activation']) ? 'checked' : '' ?> id="indicator_activation" value="1">
                     <span class="slider round"></span>
                  </label>
               </td>
            </tr>
            <tr class="sbs_6310_indicator_act">
               <td>
                  <b>Indicator Width</b> <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span></td>
               <td>
                  <input type="number" min="0" name="slider_indicator_width" id="slider_indicator_width" class="sbs-6310-form-input" value="<?php echo esc_attr($cssData['slider_indicator_width']) ?>" />
               </td>
            </tr>
            <tr class="sbs_6310_indicator_act">
               <td>
                  <b>Indicator Height</b> <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span></td>
               <td>
                  <input type="number" min="0" name="slider_indicator_height" id="slider_indicator_height" class="sbs-6310-form-input" value="<?php echo esc_attr($cssData['slider_indicator_height']) ?>" />
               </td>
            </tr>
            <tr class="sbs_6310_indicator_act">
               <td><b>Active Indicator Color</b> <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span></td>
               <td>
                  <input type="text" name="slider_indicator_active_color" id="slider_indicator_active_color" class="sbs-6310-form-input sbs_6310_color_picker" data-format="rgb" data-opacity=".8" value="<?php echo esc_attr($cssData['slider_indicator_active_color']) ?>">
               </td>
            </tr>
            <tr class="sbs_6310_indicator_act">
               <td><b>Indicator Color</b> <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span></td>
               <td>
                  <input type="text" name="slider_indicator_color" id="slider_indicator_color" class="sbs-6310-form-input sbs_6310_color_picker" data-format="rgb" data-opacity=".8" value="<?php echo esc_attr($cssData['slider_indicator_color']) ?>">
               </td>
            </tr>
            <tr class="sbs_6310_indicator_act">
               <td><b>Border Radius</b> <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span></td>
               <td>
                  <input type="number" min="0" name="slider_indicator_border_radius" id="slider_indicator_border_radius" class="sbs-6310-form-input" value="<?php echo esc_attr($cssData['slider_indicator_border_radius']) ?>">
               </td>
            </tr>
            <tr class="sbs_6310_indicator_act">
               <td><b>Indicator Margin</b> <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span></td>
               <td>
                  <input type="number" min="0" name="slider_indicator_margin" id="slider_indicator_margin" class="sbs-6310-form-input" value="<?php echo esc_attr($cssData['slider_indicator_margin']) ?>">
               </td>
            </tr>
         </table>
      </div>
   </div>
</div>
<div id="tab-9">
   <h3 class="sbs-6310-tab-menu-settings">Custom CSS Settings <span class="sbs-6310-pro">(Pro) <div class="sbs-6310-pro-text">This feature is available on the pro version only. You can view changes in the admin panel, not in the output.</div></span></h3>
   <p for="" style="width: calc(100% - 30px); margin: 0 15px 5px; font-size: 14px; padding-top: 15px; color: #000"><b>Add Your Custom CSS Code Here</b></p><br />
   
   <div class="css-area">
      <textarea class="codemirror-textarea" name="custom_css" rows="8"><?php echo esc_attr($cssData['custom_css']) ?></textarea>
   </div>
</div>