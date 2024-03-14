<div id="simple-flat-advanced-design-settings">
    <div class="dh_ptp_accordion">
        <h3><?php _e('General Settings', 'easy-pricing-tables'); ?></h3>
        <div>
            <table>
                <tr>
                    <td class="settings-title"><?php _e('Featured Label Text', 'easy-pricing-tables'); ?></td>
                    <?php $mb->the_field('most-popular-label-text'); ?>
                    <td>
                        <?php $value = (!is_null($mb->get_the_value()))?$metabox->get_the_value():__('Most Popular', 'easy-pricing-tables'); ?>
                        <input type="text" name="<?php esc_attr( $metabox->the_name() ); ?>" id="<?php $metabox->the_name(); ?>" value="<?php echo esc_attr( $value ); ?>" />
                    </td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Border Radius', 'easy-pricing-tables'); ?></td>
                    <?php $mb->the_field('rounded-corners'); ?>
                    <td>
                        <select class="form-control" name="<?php esc_attr( $metabox->the_name() ); ?>">
                            <option value="0px" <?php
                            if(!is_null($mb->get_the_value())) {
                                if($mb->get_the_value() == '0px') {
                                    echo 'selected';
                                }
                            } else {
                                echo 'selected';
                            }
                            ?> ><?php _e('No Rounded Corners', 'easy-pricing-tables'); ?></option>
                            <?php
                                for($i=1;$i<=20;++$i) {
                                    if($mb->get_the_value() == $i . 'px') {
                                        echo '<option value="' . $i . 'px" selected>' . $i . 'px</option>';
                                    } else {
                                        echo '<option value="' . $i . 'px" >' . $i . 'px</option>';
                                    }
                                }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="settings-title">
                        <?php $mb->the_field('design1-hover-effects'); ?>
                        <label for="design1-hover-effects" style="margin: 0; font-weight: normal;"><?php _e('Enlarge Column on Hover', 'easy-pricing-tables'); ?></label>
                    </td>
                    <td>
                        <input type="checkbox" name="<?php esc_attr( $metabox->the_name() ); ?>" id="design1-hover-effects" value="1"<?php if ($metabox->get_the_value()) { echo ' checked="checked"'; } ?>/>
                    </td>
                </tr>
                <tr>
                    <?php $mb->the_field('shake-buttons-on-hover'); ?>
                    <td class="settings-title">
                        <label for="design1-shake-buttons-on-hover" style="margin: 0; font-weight: normal;"><?php _e('Shake Button on Hover', 'easy-pricing-tables'); ?></label>
                    </td>
                    <td>
                        <?php $mb->the_field('shake-buttons-on-hover'); ?>
                        <input type="checkbox" name="<?php esc_attr( $metabox->the_name() ); ?>" id="design1-shake-buttons-on-hover" value="1" <?php if ($metabox->get_the_value()) echo 'checked="checked"'; ?>/>
                    </td>
                </tr>
                <!--  Automatically match Column Height  -->
                  <tr>
                     <?php $mb->the_field('match-column-height-dg1'); ?>
                    <td class="settings-title">
                        <label for="match-column-height-dg1" style="margin: 0; font-weight: normal;"><?php _e('Automatically match Row Height', 'easy-pricing-tables'); ?></label>
                    </td>
                    <td>
                        <?php $mb->the_field('match-column-height-dg1'); ?>
                        <input type="checkbox" onchange="return consistent_match_column_height(this) " class="tt-match-column-height-checkbox" name="<?php esc_attr( $metabox->the_name() ); ?>" id="match-column-height-dg1" value="1" <?php      if (!$meta) { echo 'checked="checked"'; } else  if ($metabox->get_the_value()) echo 'checked="checked"'; ?>/>
                    </td>
                </tr>
            </table>
        </div>
        <h3><?php _e('Font Sizes', 'easy-pricing-tables'); ?></h3>
        <div>
            <table>
                <tr>
                    <td class="settings-title"><?php _e('Featured Label Font Size', 'easy-pricing-tables'); ?></td>
                    <td>
                        <?php $mb->the_field('most-popular-font-size'); ?>
                        <input class="form-control float-input" type="text" name="<?php esc_attr( $metabox->the_name() ); ?>" value="<?php if(!is_null($mb->get_the_value())) { echo $metabox->the_value(); } else { echo "0.9"; } ?>"/>
                    </td>
                    <td>
                        <?php $mb->the_field('most-popular-font-size-type'); ?>
                        <select  name="<?php esc_attr( $metabox->the_name() ); ?>">
                            <option value="em" <?php
                            if(!is_null($mb->get_the_value())) {
                                if($mb->get_the_value() == 'em') {
                                    echo 'selected';
                                }
                            } else {
                                echo 'selected';
                            }
                            ?> >em</option>
                            <option value="px" <?php
                            if(!is_null($mb->get_the_value())) {
                                if($mb->get_the_value() == 'px') {
                                    echo 'selected';
                                }
                            }
                            ?>>px</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Plan Name Font Size', 'easy-pricing-tables'); ?></td>
                    <td>
                        <?php $mb->the_field('plan-name-font-size'); ?>
                        <input class="form-control float-input" type="text" name="<?php esc_attr( $metabox->the_name() ); ?>" value="<?php if(!is_null($mb->get_the_value())) { echo $metabox->the_value(); } else { echo "1"; } ?>"/>
                    </td>
                    <td>
                        <?php $mb->the_field('plan-name-font-size-type'); ?>
                        <select  name="<?php esc_attr( $metabox->the_name() ); ?>">
                            <option value="em" <?php
                            if(!is_null($mb->get_the_value())) {
                                if($mb->get_the_value() == 'em') {
                                    echo 'selected';
                                }
                            } else {
                                echo 'selected';
                            }
                            ?> >em</option>
                            <option value="px" <?php
                            if(!is_null($mb->get_the_value())) {
                                if($mb->get_the_value() == 'px') {
                                    echo 'selected';
                                }
                            }
                            ?>>px</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Price Font Size', 'easy-pricing-tables'); ?></td>
                    <td>
                        <?php $mb->the_field('price-font-size'); ?>
                        <input class="form-control float-input" type="text" name="<?php esc_attr( $metabox->the_name() ); ?>" value="<?php if(!is_null($mb->get_the_value())) { echo $metabox->the_value(); } else { echo "1.25"; } ?>"/>
                    </td>
                    <td>
                        <?php $mb->the_field('price-font-size-type'); ?>
                        <select  name="<?php esc_attr( $metabox->the_name() ); ?>">
                            <option value="em" <?php
                            if(!is_null($mb->get_the_value())) {
                                if($mb->get_the_value() == 'em') {
                                    echo 'selected';
                                }
                            } else {
                                echo 'selected';
                            }
                            ?> >em</option>
                            <option value="px" <?php
                            if(!is_null($mb->get_the_value())) {
                                if($mb->get_the_value() == 'px') {
                                    echo 'selected';
                                }
                            }
                            ?>>px</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Bullet Item Font Size', 'easy-pricing-tables'); ?></td>
                    <td>
                        <?php $mb->the_field('bullet-item-font-size'); ?>
                        <input class="form-control float-input" type="text" name="<?php esc_attr( $metabox->the_name() ); ?>" value="<?php if(!is_null($mb->get_the_value())) { echo $metabox->the_value(); } else { echo "0.875"; } ?>"/>
                    </td>
                    <td>
                        <?php $mb->the_field('bullet-item-font-size-type'); ?>
                        <select  name="<?php esc_attr( $metabox->the_name() ); ?>">
                            <option value="em" <?php
                            if(!is_null($mb->get_the_value())) {
                                if($mb->get_the_value() == 'em') {
                                    echo 'selected';
                                }
                            } else {
                                echo 'selected';
                            }
                            ?> >em</option>
                            <option value="px" <?php
                            if(!is_null($mb->get_the_value())) {
                                if($mb->get_the_value() == 'px') {
                                    echo 'selected';
                                }
                            }
                            ?>>px</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Button Font Size', 'easy-pricing-tables'); ?></td>
                    <td>
                        <?php $mb->the_field('button-font-size'); ?>
                        <input class="form-control float-input" type="text" name="<?php esc_attr( $metabox->the_name() ); ?>" value="<?php if(!is_null($mb->get_the_value())) { echo $metabox->the_value(); } else { echo "1"; } ?>"/>
                    </td>
                    <td>
                        <?php $mb->the_field('button-font-size-type'); ?>
                        <select  name="<?php esc_attr( $metabox->the_name() ); ?>">
                            <option value="em" <?php
                            if(!is_null($mb->get_the_value())) {
                                if($mb->get_the_value() == 'em') {
                                    echo 'selected';
                                }
                            } else {
                                echo 'selected';
                            }
                            ?> >em</option>
                            <option value="px" <?php
                            if(!is_null($mb->get_the_value())) {
                                if($mb->get_the_value() == 'px') {
                                    echo 'selected';
                                }
                            }
                            ?>>px</option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
        <h3><?php _e('Unfeatured Columns Colors', 'easy-pricing-tables'); ?></h3>
        <div>
            <table>                
                <!-- Headline -->
                <tr class="table-headline">
                    <td><?php _e('Background Colors', 'easy-pricing-tables'); ?></td>
                </tr>
                
                <tr>
                    <td class="settings-title"><?php _e('Border Color', 'easy-pricing-tables'); ?></td>
                    <?php $mb->the_field('unfeatured-border-color'); ?>
                    <td>
                        <?php $value = (!is_null($mb->get_the_value()))?$mb->get_the_value():"#dddddd"; ?>
                        <input type="text" name="<?php esc_attr( $mb->the_name() ); ?>" value="<?php echo esc_attr( $value ); ?>" class="colorpicker-no-palettes" data-default-color="#dddddd" />
                    </td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Title Background Color', 'easy-pricing-tables'); ?></td>
                    <?php $mb->the_field('title-area-background-color'); ?>
                    <td>
                        <?php $value = (!is_null($mb->get_the_value()))?$mb->get_the_value():"#dddddd"; ?>
                        <input type="text" name="<?php esc_attr( $mb->the_name() ); ?>" value="<?php echo esc_attr( $value ); ?>" class="colorpicker-no-palettes" data-default-color="#dddddd" />
                    </td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Pricing Background Color', 'easy-pricing-tables'); ?></td>
                    <?php $mb->the_field('pricing-background-color'); ?>
                    <td>
                        <?php $value = (!is_null($mb->get_the_value()))?$mb->get_the_value():"#eeeeee"; ?>
                        <input type="text" name="<?php esc_attr( $mb->the_name() ); ?>" value="<?php echo esc_attr( $value ); ?>" class="colorpicker-no-palettes" data-default-color="#eeeeee" />
                    </td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Button Area Background Color', 'easy-pricing-tables'); ?></td>
                    <?php $mb->the_field('unfeatured-button-area-background-color'); ?>
                    <td>
                        <?php $value = (!is_null($mb->get_the_value()))?$mb->get_the_value():"#eeeeee"; ?>
                        <input type="text" name="<?php esc_attr( $mb->the_name() ); ?>" value="<?php echo esc_attr( $value ); ?>" class="colorpicker-no-palettes" data-default-color="#eeeeee" />
                    </td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Bullet Item Background Color', 'easy-pricing-tables'); ?></td>
                    <?php $mb->the_field('featured-background-color'); ?>
                    <td>
                        <?php $value = (!is_null($mb->get_the_value()))?$mb->get_the_value():"#ffffff"; ?>
                        <input type="text" name="<?php esc_attr( $mb->the_name() ); ?>" class="button-border-color" value="<?php echo esc_attr( $value ); ?>" class="my-color-field" data-default-color="#ffffff;" />
                    </td>
                </tr>
                
                <!-- Headline -->
                <tr class="table-headline">
                    <td><br/><?php _e('Font Colors', 'easy-pricing-tables'); ?></td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Title Font Color', 'easy-pricing-tables'); ?></td>
                    <?php $mb->the_field('title-area-font-color'); ?>
                    <td>
                        <?php $value = (!is_null($mb->get_the_value()))?$mb->get_the_value():"#333333"; ?>
                        <input type="text" name="<?php esc_attr( $mb->the_name() ); ?>" value="<?php echo esc_attr( $value ); ?>" class="colorpicker-no-palettes" data-default-color="#333333" />
                    </td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Pricing Font Color', 'easy-pricing-tables'); ?></td>
                    <?php $mb->the_field('pricing-area-font-color'); ?>
                    <td>
                        <?php $value = (!is_null($mb->get_the_value()))?$mb->get_the_value():"#333333"; ?>
                        <input type="text" name="<?php esc_attr( $mb->the_name() ); ?>" value="<?php echo esc_attr( $value ); ?>" class="colorpicker-no-palettes" data-default-color="#333333" />
                    </td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Feature Color', 'easy-pricing-tables'); ?></td>
                    <?php $mb->the_field('featured-font-color'); ?>
                    <td>
                        <?php $value = (!is_null($mb->get_the_value()))?$mb->get_the_value():"#333333"; ?>
                        <input type="text" name="<?php esc_attr( $mb->the_name() ); ?>" class="button-border-color" value="<?php echo esc_attr( $value ); ?>" class="my-color-field" data-default-color="#333333" />
                    </td>
                </tr>
                
                <!-- Headline -->
                <tr class="table-headline">
                    <td><br/><?php _e('Button Colors', 'easy-pricing-tables'); ?></td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Button Color', 'easy-pricing-tables'); ?></td>
                    <?php
                        $mb->the_field('button-color');
                        $button_color = (!is_null($mb->get_the_value()))?$mb->get_the_value():"#3498db";
                    ?>
                    <td>
                        <input type="text" name="<?php esc_attr( $mb->the_name() ); ?>" class="button-color" value="<?php echo $button_color; ?>" class="my-color-field form-control" data-default-color="#3498db" />
                    </td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Button Border Color', 'easy-pricing-tables'); ?></td>
                    <?php
                        $mb->the_field('button-border-color');
                        $button_border_color = (!is_null($mb->get_the_value()))?$mb->get_the_value():"#2980b9";
                    ?>
                    <td>
                        <input type="text" name="<?php esc_attr( $mb->the_name() ); ?>" class="button-border-color" value="<?php echo $button_border_color; ?>" class="my-color-field" data-default-color="#2980b9" />
                    </td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Button Hover Color', 'easy-pricing-tables'); ?></td>
                    <?php
                        $mb->the_field('button-hover-color');
                        $button_hover_color = (!is_null($mb->get_the_value()))?$mb->get_the_value():"#2980b9";
                    ?>
                    <td>
                        <input type="text" name="<?php esc_attr( $mb->the_name() ); ?>" class="button-border-color" value="<?php echo $button_hover_color; ?>" class="my-color-field" data-default-color="#2980b9" />
                    </td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Button Font Color', 'easy-pricing-tables'); ?></td>
                    <?php
                        $mb->the_field('button-font-color');
                        $button_font_color = (!is_null($mb->get_the_value()))?$mb->get_the_value():"#ffffff";
                    ?>
                    <td>
                        <input type="text" name="<?php esc_attr( $mb->the_name() ); ?>" class="colorpicker-no-palettes" value="<?php echo $button_font_color; ?>" class="my-color-field" data-default-color="#ffffff" />
                    </td>
                </tr>
            </table>
        </div>
        <h3><?php _e('Featured Column Colors', 'easy-pricing-tables'); ?></h3>
        <div>
            <table>
                <!-- Headline -->
                <tr class="table-headline">
                    <td><?php _e('Background Colors', 'easy-pricing-tables'); ?></td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Border Color', 'easy-pricing-tables'); ?></td>
                    <?php $mb->the_field('featured-border-color'); ?>
                    <td>
                        <?php $value = (!is_null($mb->get_the_value()))?$mb->get_the_value():"#dddddd"; ?>
                        <input type="text" name="<?php esc_attr( $mb->the_name() ); ?>" value="<?php echo esc_attr( $value ); ?>" class="colorpicker-no-palettes" data-default-color="#dddddd" />
                    </td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Title Background Color', 'easy-pricing-tables'); ?></td>
                    <?php $mb->the_field('featured-title-area-background-color'); ?>
                    <td>
                        <?php $value = (!is_null($mb->get_the_value()))?$mb->get_the_value():"#dddddd"; ?>
                        <input type="text" name="<?php esc_attr( $mb->the_name() ); ?>" value="<?php echo esc_attr( $value ); ?>" class="colorpicker-no-palettes" data-default-color="#dddddd" />
                    </td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Pricing Background Color', 'easy-pricing-tables'); ?></td>
                    <?php $mb->the_field('featured-pricing-background-color'); ?>
                    <td>
                        <?php $value = (!is_null($mb->get_the_value()))?$mb->get_the_value():"#eeeeee"; ?>
                        <input type="text" name="<?php esc_attr( $mb->the_name() ); ?>" value="<?php echo esc_attr( $value ); ?>" class="colorpicker-no-palettes" data-default-color="#eeeeee" />
                    </td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Button Area Background Color', 'easy-pricing-tables'); ?></td>
                    <?php $mb->the_field('featured-button-area-background-color'); ?>
                    <td>
                        <?php $value = (!is_null($mb->get_the_value()))?$mb->get_the_value():"#eeeeee"; ?>
                        <input type="text" name="<?php esc_attr( $mb->the_name() ); ?>" value="<?php echo esc_attr( $value ); ?>" class="colorpicker-no-palettes" data-default-color="#eeeeee" />
                    </td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Bullet Item Background Color', 'easy-pricing-tables'); ?></td>
                    <?php $mb->the_field('featured-feature-background-color'); ?>
                    <td>
                        <?php $value = (!is_null($mb->get_the_value()))?$mb->get_the_value():"#ffffff"; ?>
                        <input type="text" name="<?php esc_attr( $mb->the_name() ); ?>" class="button-border-color" value="<?php echo esc_attr( $value ); ?>" class="my-color-field" data-default-color="#ffffff;" />
                    </td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Featured Label Border Color', 'easy-pricing-tables'); ?></td>
                    <?php $mb->the_field('featured-feature-label-border-color'); ?>
                    <td>
                        <?php $value = (!is_null($mb->get_the_value()))?$mb->get_the_value():"#7f8c8d"; ?>
                        <input type="text" name="<?php esc_attr( $mb->the_name() ); ?>" value="<?php echo esc_attr( $value ); ?>" class="colorpicker-no-palettes" data-default-color="#7f8c8d" />
                    </td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Featured Label Background Color', 'easy-pricing-tables'); ?></td>
                    <?php $mb->the_field('most-popular-area-background-color'); ?>
                    <td>
                        <?php $value = (!is_null($mb->get_the_value()))?$mb->get_the_value():"#7f8c8d"; ?>
                        <input type="text" name="<?php esc_attr( $mb->the_name() ); ?>" value="<?php echo esc_attr( $value ); ?>" class="colorpicker-no-palettes" data-default-color="#7f8c8d" />
                    </td>
                </tr>
                
                <!-- Headline -->
                <tr class="table-headline">
                    <td><br/><?php _e('Font Colors', 'easy-pricing-tables'); ?></td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Title Font Color', 'easy-pricing-tables'); ?></td>
                    <?php $mb->the_field('featured-title-area-font-color'); ?>
                    <td>
                        <?php $value = (!is_null($mb->get_the_value()))?$mb->get_the_value():"#333333"; ?>
                        <input type="text" name="<?php esc_attr( $mb->the_name() ); ?>" value="<?php echo esc_attr( $value ); ?>" class="colorpicker-no-palettes" data-default-color="#333333" />
                    </td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Pricing Font Color', 'easy-pricing-tables'); ?></td>
                    <?php $mb->the_field('featured-pricing-area-font-color'); ?>
                    <td>
                        <?php $value = (!is_null($mb->get_the_value()))?$mb->get_the_value():"#333333"; ?>
                        <input type="text" name="<?php esc_attr( $mb->the_name() ); ?>" value="<?php echo esc_attr( $value ); ?>" class="colorpicker-no-palettes" data-default-color="#333333" />
                    </td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Feature Color', 'easy-pricing-tables'); ?></td>
                    <?php $mb->the_field('featured-feature-font-color'); ?>
                    <td>
                        <?php $value = (!is_null($mb->get_the_value()))?$mb->get_the_value():"#333333"; ?>
                        <input type="text" name="<?php esc_attr( $mb->the_name() ); ?>" class="button-border-color" value="<?php echo esc_attr( $value ); ?>" class="my-color-field" data-default-color="#333333" />
                    </td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Feature Label Font Color', 'easy-pricing-tables'); ?></td>
                    <?php $mb->the_field('most-popular-font-color'); ?>
                    <td>
                        <?php $value = (!is_null($mb->get_the_value()))?$mb->get_the_value():"#ffffff"; ?>
                        <input type="text" name="<?php esc_attr( $mb->the_name() ); ?>" value="<?php echo esc_attr( $value ); ?>" class="colorpicker-no-palettes" data-default-color="#ffffff" />
                    </td>
                </tr>
                
                
                <!-- Headline -->
                <tr class="table-headline">
                    <td><br/><?php _e('Button Colors', 'easy-pricing-tables'); ?></td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Featured-Button Color', 'easy-pricing-tables'); ?></td>
                    <?php $mb->the_field('featured-button-color'); ?>
                    <td>
                        <?php $featured_button_color = (!is_null($mb->get_the_value()))?$mb->get_the_value():"#e74c3c";?>
                        <input type="text" name="<?php esc_attr( $mb->the_name() ); ?>" class="button-color" value="<?php echo $featured_button_color; ?>" class="my-color-field form-control" data-default-color="#e74c3c" />
                    </td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Featured-Button Border Color', 'easy-pricing-tables'); ?></td>
                    <?php $mb->the_field('featured-button-border-color'); ?>
                    <td>
                        <?php $featured_button_border_color = (!is_null($mb->get_the_value()))?$mb->get_the_value():"#c0392b";?>
                        <input type="text" name="<?php esc_attr( $mb->the_name() ); ?>" class="button-border-color" value="<?php echo $featured_button_border_color; ?>" class="my-color-field" data-default-color="#c0392b" />
                    </td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Featured-Button Hover Color', 'easy-pricing-tables'); ?></td>
                    <?php $mb->the_field('featured-button-hover-color'); ?>
                    <td>
                        <?php $featured_button_hover_color = (!is_null($mb->get_the_value()))?$mb->get_the_value():"#c0392b";?>
                        <input type="text" name="<?php esc_attr( $mb->the_name() ); ?>" class="button-border-color" value="<?php echo $featured_button_hover_color; ?>" class="my-color-field" data-default-color="#c0392b" />
                    </td>
                </tr>
                <tr>
                    <td class="settings-title"><?php _e('Featured-Button Font Color', 'easy-pricing-tables'); ?></td>
                    <?php $mb->the_field('featured-button-font-color'); ?>
                    <td>
                        <?php $featured_button_font_color = (!is_null($mb->get_the_value()))?$mb->get_the_value():"#ffffff";?>
                        <input type="text" name="<?php esc_attr( $mb->the_name() ); ?>" class="colorpicker-no-palettes" value="<?php echo $featured_button_font_color; ?>" class="my-color-field" data-default-color="#ffffff" />
                    </td>
                </tr>
            </table>
        </div>
        <h3><?php _e('Advanced Settings', 'easy-pricing-tables'); ?></h3>
        <div>
            <table>               
                <tr>
                    <?php $mb->the_field('design1-hide-empty-rows'); ?>
                    <td class="settings-title">
                        <label for="design1-hide-empty-rows" style="margin: 0; font-weight: normal;"><?php _e('Hide Empty Rows', 'easy-pricing-tables'); ?></label>
                    </td>
                    <td>
                        <input type="checkbox" name="<?php esc_attr( $metabox->the_name() ); ?>" id="design1-hide-empty-rows" value="1" <?php if ($metabox->get_the_value()) echo 'checked="checked"'; ?>/>
                    </td>
                </tr>
                <tr>
                    <?php $mb->the_field('hide-call-to-action-buttons'); ?>
                    <td class="settings-title">
                        <label for="design1-call-action-buttons" style="margin: 0; font-weight: normal;"><?php _e('Hide Call To Action Buttons', 'easy-pricing-tables'); ?></label>
                    </td>
                    <td>
                        <input type="checkbox" name="<?php esc_attr( $metabox->the_name() ); ?>" id="design1-call-action-buttons" value="1"<?php if ($metabox->get_the_value()) echo ' checked="checked"'; ?>/>
                    </td>
                </tr>
                <tr>
                    <?php $mb->the_field('design1-open-link-in-new-tab'); ?>
                    <td class="settings-title">
                        <label for="design1-open-link-in-new-tab" style="margin: 0; font-weight: normal;"><?php _e('Open Link in New Tab', 'easy-pricing-tables'); ?></label>
                    </td>
                    <td>
                        <input type="checkbox" name="<?php esc_attr( $metabox->the_name() ); ?>" id="design1-open-link-in-new-tab" value="1" <?php if ($metabox->get_the_value()) echo 'checked="checked"'; ?>/>
                    </td>
                </tr>
                <tr>
                    <?php $mb->the_field('design1-no-spacing-between-columns'); ?>
                    <td class="settings-title">
                        <label for="design1-no-spacing-betwen-columns" style="margin: 0; font-weight: normal;"><?php _e('No Spacing Between Columns', 'easy-pricing-tables'); ?></label>
                    </td>
                    <td>
                        <input type="checkbox" name="<?php esc_attr( $metabox->the_name() ); ?>" id="design1-no-spacing-betwen-columns" value="1"<?php if ($metabox->get_the_value()) echo ' checked="checked"'; ?>/> 
                    </td>
                </tr>
            </table>
        </div>
             <!-- ept-custom-css-setting -->
        <h3><?php _e('Custom CSS', 'easy-pricing-tables'); ?></h3>
        <div >
 
            <table>
                <tr>
                    <?php $mb->the_field('ept-custom-css-setting-dg1'); ?>
                    <td class="settings-title">
                        <label for="custom-css-setting" style="margin: 0; font-weight: bold;"><?php _e('Custom Pricing Table CSS', 'easy-pricing-tables'); ?></label>
                    </td>
                    <td class="custom-css-setting-td">
                        
                        <textarea class="custom-css-setting-textbox"  name="<?php esc_attr( $metabox->the_name() ); ?>"  rows="10" cols="60" <?php if (!$metabox->get_the_value()) echo  'placeholder="Type your custom css here"' ?> ><?php if ($metabox->get_the_value()) echo " " . esc_attr( $metabox->get_the_value() );else {
                         echo " ";
                     } ?></textarea>
                        </td>
                </tr>
           
            </table>
        
           </div>
    </div>
</div>
