<div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5 tred-main-content tred-easydash-tab"
    id="tred-easydash-tab-settings" style="display:none">

    <div class="wrap tred-wrap-grid"
        style="display: flex;flex-wrap: wrap;justify-content: flex-start;align-items: flex-start;">
        <form method="post" action="options.php">

            <?php settings_fields('tred-settings-group'); ?>
            <?php do_settings_sections('tred-settings-group'); ?>

            <div class="tred-form-fields">

                <div class="tred-settings-title">
                    <?php esc_html_e('Easy Dash for LearnDash - Settings', 'learndash-easy-dash'); ?>
                </div>

                <?php foreach (TRED_OPTIONS_ARRAY as $op => $vals) {
                    if ($vals['type'] === 'hidden') {
                        continue;
                    } ?>

                    <div class="tred-form-fields-label">
                        <?php esc_html_e($vals['description'], 'learndash-easy-dash'); ?>
                        <?php if (!empty($vals['obs'])) { ?>
                            <span>*
                                <?php esc_html_e($vals['obs'], 'learndash-easy-dash'); ?>
                            </span>
                        <?php } ?>
                    </div>
                    <div class="tred-form-fields-group">
                        <?php if ($vals['type'] === 'select') { ?>
                            <!-- select -->
                            <div class="tred-form-div-select">
                                <label>
                                    <select class="tred-settings-select"
                                        name="<?php echo ($vals['kind'] === 'multiple') ? esc_attr($op) . '[]' : esc_attr($op); ?>"
                                        <?php echo esc_attr($vals['kind']); ?>>
                                        <?php if (empty($vals['options'])) {
                                            $vals['options'] = $vals['get_options']();
                                        }
                                        foreach ($vals['options'] as $v => $label) {
                                            $pt = (is_integer($v)) ? $label : $v;
                                            if ($label == 'all time') {
                                                $pt = '-1';
                                            }
                                            ?>
                                            <option value="<?php echo esc_attr($pt); ?>" <?php
                                               if (empty(get_option($op)) && $vals['default'] === $pt) {
                                                   echo esc_attr('selected');
                                               } else if ($vals['kind'] === 'multiple') {
                                                   if (is_array(get_option($op)) && in_array($pt, get_option($op))) {
                                                       echo esc_attr('selected');
                                                   }
                                               } else {
                                                   selected($pt, get_option($op), true);
                                               }
                                               ?>>
                                                <?php echo esc_html($label); ?>
                                            </option>
                                        <?php } //end foreach ?>
                                    </select>
                                </label>
                            </div>
                        <?php } else if ($vals['type'] === 'text') { ?>
                                <!-- text -->
                                <input type="text" placeholder="<?php echo esc_attr($vals['default']); ?>" class=""
                                    value="<?php echo esc_attr(get_option($op)); ?>" name="<?php echo esc_attr($op); ?>">
                        <?php } else if ($vals['type'] === 'textarea') { ?>
                                    <!-- textarea -->
                                    <textarea class="large-text" cols="80" rows="10"
                                        name="<?php echo esc_attr($op); ?>"><?php echo esc_html(get_option($op)); ?></textarea>
                        <?php } else if ($vals['type'] === 'checkbox') { ?>
                                        <!-- checkbox -->
                                        <div class="tred-form-div-checkbox">
                                            <label>
                                                <input class="tred-checkbox" type="checkbox" name="<?php echo esc_attr($op); ?>" value="1"
                                        <?php checked(1, get_option($op), true); ?>>
                                    <?php if (!empty($vals['label'])) { ?>
                                                    <span class="tred-form-fields-style-label">
                                            <?php esc_html_e($vals['label'], 'tred-grid-button'); ?>
                                                    </span>
                                    <?php } ?>
                                            </label>
                                        </div>
                        <?php } ?>

                        <?php if (!empty($vals['final'])) { ?>
                            <span>
                                <?php esc_html_e($vals['final'], 'learndash-easy-dash'); ?>
                            </span>
                        <?php } ?>
                    </div>
                    <hr>
                <?php } //end foreach TRED_OPTIONS_ARRAY ?>

                <?php submit_button(); ?>

            </div> <!-- end form fields -->
        </form>
        <?php tred_template_wptrat_links(); ?>
    </div> <!-- end tred-wrap-grid -->


</div>