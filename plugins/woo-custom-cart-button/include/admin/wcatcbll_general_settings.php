<section class="m-2 mainsec catcbll_general_sec" id="wcatcbll_stng">
    <form method="post" id="wcatbltn_option_save" class="">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-6 col-lg-6">
                    <div class="row mb-3">
                        <!-- First Column Start-->
                        <!-- Choose type (With Post  / With Images) Start-->
                        <div class="col-12 colbox d-flex justify-content-center p-3">
                            <!--Choose Setting Start-->
                            <div class="btn_preview_div"> <?php
                                                            if ($catcbll_btn_icon_psn == 'right') { ?> <a type="button" href="#" class="btn <?php echo esc_attr($catcbll_hide_2d_trans) . ' ' . esc_attr($catcbll_hide_btn_bghvr) ?> btn-lg wccbtn" id="btn_prvw"><?php echo __('Add To Cart', 'catcbll') ?>
                                        <?php if (esc_html($catcbll_btn_icon_cls) != "") {
                                                                    echo '<i class="fa ' . esc_html($catcbll_btn_icon_cls) . '"></i>';
                                                                } ?></a>
                                <?php } else { ?> <a type="button" href="#" class="btn <?php echo esc_attr($catcbll_hide_2d_trans) . ' ' . esc_attr($catcbll_hide_btn_bghvr) ?> btn-lg wccbtn" id="btn_prvw">
                                        <?php
                                                                if (esc_html($catcbll_btn_icon_cls) != "") {
                                                                    echo '<i class="fa ' . esc_html($catcbll_btn_icon_cls) . '"></i>';
                                                                } ?><?php echo __('Add To Cart', 'catcbll') ?></a>
                                <?php } ?> </div>
                        </div>
                        <!-- Choose type (With Post  / With Images) End-->
                    </div>
                    <div class="row">
                        <!-- Advance Settings Start -->
                        <div class="col-xl-6 col-md-6">
                            <!-- 1st Box Cart Button's Settings Start-->
                            <div class="row p-3 colbox mb-3 me-md-3">
                                <div class="col-12 p-0">
                                    <h6 class="brdrbtm px-0 mb-3"><?php echo __("Cart Button's Settings", "catcbll"); ?>
                                    </h6>
                                    <!-- Cart Button's Settings-->
                                    <!-- Both Start-->
                                    <div class="row mt-2">
                                        <div class="col-12 d-flex justify-content-between align-items-center">
                                            <label class="form-check-label"><?php echo __("Dual Button", "catcbll"); ?></label>
                                            <label class="switch">
                                                <input type="checkbox" class="button1" name="catcbll_both_btn" <?php if ($both == 'both') echo "checked='checked'";  ?> value="both" />
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Both End-->
                                    <!-- Default Add To Cart Per Product Start-->
                                    <div class="row mt-2">
                                        <div class="col-12 d-flex justify-content-between align-items-center">
                                            <label class="form-check-label"><?php echo __("Default ATC Button Per Product", "catcbll"); ?></label>
                                            <label class="switch">
                                                <input type="checkbox" class="button2" id="wcatcbll_add2_cart" name="catcbll_add2_cart" <?php if ($add2cart == 'add2cart') echo "checked='checked'"; ?> value="add2cart" />
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Default Add To Cart Per Product End-->
                                    <!-- Custom Button Per Product Start-->
                                    <div class="row mt-2">
                                        <div class="col-12 d-flex justify-content-between align-items-center">
                                            <label class="form-check-label"><?php echo __("Custom ATC Button Per Product", "catcbll"); ?></label>
                                            <label class="switch">
                                                <input type="checkbox" class="button3" id="wcatcbll_custom" name="catcbll_custom" <?php if ($custom == 'custom') echo "checked='checked'";  ?> value="custom" />
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Custom Button Per Product End-->
                                    <!-- Custom Button Position Start-->
                                    <div class="row mt-2">
                                        <div class="col-12 d-flex justify-content-between align-items-center">
                                            <label class="form-check-label"><?php echo __("Custom Button Position", "catcbll"); ?>
                                                <div class="wcatcblltooltip"><i class="fa fa-question-circle" aria-hidden="true"></i>
                                                    <span class="wcatcblltooltiptext"><?php echo __("Custom Button Position", 'catcbll') ?></span>
                                                </div>
                                            </label>
                                            <select name="catcbll_custom_btn_position" id="wcatcbll_custom_btn_position">
                                                <option <?php if ($catcbll_custom_btn_position == 'up') echo "selected='selected'";  ?> value="up"><?php echo __("Up", "catcbll"); ?></option>
                                                <option <?php if ($catcbll_custom_btn_position == 'down') echo "selected='selected'";  ?> value="down"><?php echo __("Down", "catcbll"); ?></option>
                                                <option <?php if ($catcbll_custom_btn_position == 'left') echo "selected='selected'";  ?> value="left"><?php echo __("Left", "catcbll"); ?></option>
                                                <option <?php if ($catcbll_custom_btn_position == 'right') echo "selected='selected'";  ?> value="right"><?php echo __("Right", "catcbll"); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Custom Button Position End-->
                                    <!-- Custom Button Alignment Start-->
                                    <div class="row mt-2">
                                        <div class="col-12 d-flex justify-content-between align-items-center">
                                            <label class="form-check-label"><?php echo __("Custom Button Alignment", "catcbll"); ?>
                                                <div class="wcatcblltooltip"><i class="fa fa-question-circle" aria-hidden="true"></i>
                                                    <span class="wcatcblltooltiptext"><?php echo __("Custom Button Alignment", 'catcbll') ?></span>
                                                </div>
                                            </label>
                                            <select name="catcbll_custom_btn_alignment" id="catcbll_custom_btn_alignment">
                                                <option <?php if ($catcbll_custom_btn_alignment == 'left') echo "selected='selected'";  ?> value="left"><?php echo __("Left", "catcbll"); ?></option>
                                                <option <?php if ($catcbll_custom_btn_alignment == 'right') echo "selected='selected'";  ?> value="right"><?php echo __("Right", "catcbll"); ?></option>
                                                <option <?php if ($catcbll_custom_btn_alignment == 'center') echo "selected='selected'";  ?> value="center"><?php echo __("Center", "catcbll"); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Custom Button Alignment End-->
                                    <!-- Cart Button's Settings End-->
                                </div>
                            </div>
                            <!-- 1st Box Cart Button's Settings End-->
                            <!-- 2nd Box Custom Button Display Start-->
                            <div class="row p-3 colbox mb-3 me-md-3">
                                <div class="col-12 p-0">
                                    <h6 class="brdrbtm px-0 mb-3"><?php echo __("Custom Button Display", "catcbll"); ?>
                                    </h6>
                                    <!-- Cart Button's Settings-->
                                    <!-- Global Start-->
                                    <div class="row mt-2">
                                        <div class="col-12 d-flex justify-content-between align-items-center">
                                            <label class="form-check-label"><?php echo __("Global", "catcbll"); ?></label>
                                            <label class="switch">
                                                <input type="checkbox" class="class1" name="catcbll_cart_global" <?php if ($global == 'global') echo "checked='checked'"; ?> value="global" />
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Global End-->
                                    <!-- Shop Start-->
                                    <div class="row mt-2">
                                        <div class="col-12 d-flex justify-content-between align-items-center">
                                            <label class="form-check-label"><?php echo __("On Shop page", "catcbll"); ?></label>
                                            <label class="switch">
                                                <input type="checkbox" class="class2" id="wcatcbll_cart_shop" name="catcbll_cart_shop" <?php if ($shop == 'shop') echo "checked='checked'"; ?> value="shop" />
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Shop End-->
                                    <!-- Single Product Start-->
                                    <div class="row mt-2">
                                        <div class="col-12 d-flex justify-content-between align-items-center">
                                            <label class="form-check-label"><?php echo __("On Single Product page", "catcbll"); ?></label>
                                            <label class="switch">
                                                <input type="checkbox" class="class3" id="wcatcbll_cart_single_product" name="catcbll_cart_single_product" <?php if ($single == 'single-product') echo "checked='checked'";  ?> value="single-product" />
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Single Product End-->
                                    <!-- Open Link In New Tab Start-->
                                    <div class="row mt-2">
                                        <div class="col-12 d-flex justify-content-between align-items-center">
                                            <label class="form-check-label"><?php echo __("Open Link In New Tab", "catcbll"); ?>
                                                <div class="wcatcblltooltip"><i class="fa fa-question-circle" aria-hidden="true"></i>
                                                    <span class="wcatcblltooltiptext"><?php echo __('If Checkbox Is Checked Then Button Link will be Opened In a New Tab', 'catcbll'); ?></span>
                                                </div>
                                            </label>
                                            <label class="switch">
                                                <input type="checkbox" class="class4" name="catcbll_btn_open_new_tab" <?php if ($btn_opnt == '1') echo "checked='checked'";  ?> value="1" />
                                                <span class="slider round"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- Open Link In New Tab End-->
                                </div>
                            </div>
                            <!-- 2nd Box Custom Button Display End-->
                            <!-- 3rd Box Custom Button Display Start-->
                            <!-- <div class="row p-3 colbox mb-3 me-md-3">
                                <div class="col-12 p-0">
                                    <h6 class="brdrbtm px-0 mb-3">
                                        <?php echo __("Distinct Information Per Product", "catcbll"); ?> </h6>
                                    <a href="https://youtu.be/7t63igatlOU" class="btn btn-danger fs-6 text fst-normal" target="_blank"><i class="fa fa-youtube" aria-hidden="true"></i> <?php echo __('Watch Now', 'catcbll'); ?></a>
                                </div>
                            </div> -->
                            <!-- 3rd Box Custom Button Display End-->
                        </div>
                        <!-- Advance Settings End -->
                        <!-- Custom Button Display Start -->
                        <div class="col-xl-6 col-md-6">
                            <!-- 2nd Box Custom Button Display Start-->
                            <div class="row p-3 colbox">
                                <div class="col-12 p-0">
                                    <h6 class="brdrbtm px-0 mb-3"><?php echo __("Custom Button Style", "catcbll"); ?>
                                    </h6>
                                    <!-- Cart Button's Settings-->
                                    <!-- Text Font Size Start-->
                                    <div class="row mt-2">
                                        <div class="col-12 d-flex justify-content-between align-items-center">
                                            <label class="form-check-label"><?php echo __("Text Font Size", "catcbll"); ?></label>
                                            <div class="wcatcbll_range_slider">
                                                <input class="wcatcbll_range_slider_range" id="catcbll_btn_fsize" type="range" value="<?php echo $catcbll_btn_fsize; ?>" name="catcbll_btn_fsize" min="5" max="50">
                                                <span class="wcatcbll_range_slider_value"><?php esc_attr_e($catcbll_btn_fsize); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Text Font Size End-->
                                    <!-- Border Size Start-->
                                    <div class="row mt-2">
                                        <div class="col-12 d-flex justify-content-between align-items-center">
                                            <label class="form-check-label"><?php echo __("Border Width", "catcbll"); ?></label>
                                            <div class="wcatcbll_range_slider">
                                                <input class="wcatcbll_range_slider_range" id="catcbll_border_size" type="range" value="<?php echo $catcbll_border_size; ?>" name="catcbll_border_size" min="0" max="20">
                                                <span class="wcatcbll_range_slider_value" id="ccbtn_border_size"><?php esc_attr_e($catcbll_border_size); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Border Size End-->
                                    <!-- Border Radius Start-->
                                    <div class="row mt-2">
                                        <div class="col-12 d-flex justify-content-between align-items-center">
                                            <label class="form-check-label"><?php echo __("Border Radius", "catcbll"); ?></label>
                                            <div class="wcatcbll_range_slider">
                                                <input class="wcatcbll_range_slider_range" id="catcbll_btn_radius" type="range" value="<?php echo $catcbll_btn_radius; ?>" name="catcbll_btn_radius" min="1" max="50">
                                                <span class="wcatcbll_range_slider_value" id="brdr_rds"><?php esc_attr_e($catcbll_btn_radius); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Border Radius End-->
                                    <!-- Button Background Start-->
                                    <div class="row mt-2">
                                        <div class="col-12 d-flex justify-content-between align-items-center">
                                            <label class="form-check-label"><?php echo __("Button Background", "catcbll"); ?></label>
                                            <input class="color-picker" data-alpha-enabled="true" type="text" name="catcbll_btn_bg" id="catcbll_btn_bg" value="<?php echo $catcbll_btn_bg; ?>" />
                                        </div>
                                    </div>
                                    <!-- Button Background End-->
                                    <!-- Text Font Color Start-->
                                    <div class="row mt-2">
                                        <div class="col-12 d-flex justify-content-between align-items-center">
                                            <label class="form-check-label"><?php echo __("Text Font Color", "catcbll"); ?></label>
                                            <input class="color-picker" id="catcbll_btn_fclr" data-alpha-enabled="true" type="text" name="catcbll_btn_fclr" value="<?php echo $catcbll_btn_fclr; ?>" />
                                        </div>
                                    </div>
                                    <!-- Text Font Color End-->
                                    <!-- Border Color Start-->
                                    <div class="row mt-2">
                                        <div class="col-12 d-flex justify-content-between align-items-center">
                                            <label class="form-check-label"><?php echo __("Border Color", "catcbll"); ?></label>
                                            <input class="color-picker" id="catcbll_btn_border_clr" data-alpha-enabled="true" type="text" name="catcbll_btn_border_clr" value="<?php echo $catcbll_btn_border_clr; ?>" />
                                        </div>
                                    </div>
                                    <!-- Border Color End-->
                                    <!-- Hover Color Start-->
                                    <div class="row mt-2">
                                        <div class="col-12 d-flex justify-content-between align-items-center">
                                            <label class="form-check-label"><?php echo __("Hover Color", "catcbll"); ?></label>
                                            <input class="color-picker" id="catcbll_btn_hvrclr" data-alpha-enabled="true" type="text" name="catcbll_btn_hvrclr" value="<?php if (!empty($catcbll_btn_hvrclr)) {
                                                                                                                                                                            echo $catcbll_btn_hvrclr;
                                                                                                                                                                        } ?>" />
                                        </div>
                                    </div>
                                    <!-- Hover Color End-->
                                    <!-- Button padding Start-->
                                    <div class="row mt-2">
                                        <div class="col-12 d-flex justify-content-between align-items-center">
                                            <label class="form-check-label"><?php echo __("Button Padding", "catcbll"); ?>
                                                <div class="wcatcblltooltip"><i class="fa fa-question-circle" aria-hidden="true"></i>
                                                    <span class="wcatcblltooltiptext"><?php echo __('Button Padding', 'catcbll') ?></span>
                                                </div>
                                            </label>
                                            <ul class="btnpd_st">
                                                <li>
                                                    <input type="number" name="catcbll_padding_top_bottom" id="catcbll_padding_top_bottom" placeholder="0" value="<?php echo $catcbll_padding_top_bottom ?>" class="btn_pv">
                                                    <label><?php echo __("Vertically", "catcbll"); ?></label>
                                                </li>
                                                <li>
                                                    <input type="number" name="catcbll_padding_left_right" id="catcbll_padding_left_right" placeholder="0" value="<?php echo $catcbll_padding_left_right ?>" class="btn_ph">
                                                    <label><?php echo __("Horizontally", "catcbll"); ?></label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- Button padding End-->
                                    <!-- Button Margin Start-->
                                    <div class="row mt-2">
                                        <div class="col-12 d-flex justify-content-between align-items-center">
                                            <label class="form-check-label"><?php echo __("Button Margin", "catcbll"); ?>
                                                <div class="wcatcblltooltip"><i class="fa fa-question-circle" aria-hidden="true"></i>
                                                    <span class="wcatcblltooltiptext"><?php echo __('Button Margin', 'catcbll') ?></span>
                                                </div>
                                            </label>
                                            <ul class="btnpd_st btnmd_st p-0">
                                                <li>
                                                    <input type="number" name="catcbll_margin_top" value="<?php echo $catcbll_margin_top ?>" class="btn_mt">
                                                    <label><?php echo __("Top", "catcbll"); ?></label>
                                                </li>
                                                <li>
                                                    <input type="number" name="catcbll_margin_right" value="<?php echo $catcbll_margin_right ?>" class="btn_mr">
                                                    <label><?php echo __("Right", "catcbll"); ?></label>
                                                </li>
                                                <li>
                                                    <input type="number" name="catcbll_margin_bottom" value="<?php echo $catcbll_margin_bottom ?>" class="btn_mb">
                                                    <label><?php echo __("Bottom", "catcbll"); ?></label>
                                                </li>
                                                <li>
                                                    <input type="number" name="catcbll_margin_left" value="<?php echo $catcbll_margin_left ?>" class="btn_ml">
                                                    <label><?php echo __("Left", "catcbll"); ?></label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- Button Margin End-->
                                    <!-- Button Margin Start-->
                                    <div class="row mt-2">
                                        <div class="col-12 d-flex justify-content-between align-items-center">
                                            <label class="form-check-label"><?php echo __("Button Icon", "catcbll"); ?></label>
                                            <select name="catcbll_btn_icon_cls" id="wcatcll_font_icon"> <?php
                                                                                                        foreach ($wcatcbll_icons as $wcatcbll_key => $wcatcbll_val) { ?> <option <?php if ($catcbll_btn_icon_cls == $wcatcbll_key) echo "selected='selected'";  ?> value="<?php echo $wcatcbll_key; ?>"><?php echo $wcatcbll_val; ?>
                                                    </option> <?php }
                                                                ?> </select>
                                        </div>
                                    </div>
                                    <!-- Button Margin End-->
                                    <!-- Button Margin Start-->
                                    <div class="row mt-2">
                                        <div class="col-12 d-flex justify-content-between align-items-center">
                                            <label class="form-check-label"><?php echo __("Button Icon Position", "catcbll"); ?></label>
                                            <select name="catcbll_btn_icon_psn" id="wcatcbll_btn_icon_psn">
                                                <option <?php if ($catcbll_btn_icon_psn == 'left') echo "selected='selected'";  ?> value="left"><?php echo __("Left", "catcbll"); ?></option>
                                                <option <?php if ($catcbll_btn_icon_psn == 'right') echo "selected='selected'";  ?> value="right"><?php echo __("Right", "catcbll"); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Button Margin End-->
                                    <!-- Button Margin Start-->
                                    <div class="row mt-2">
                                        <div class="col-12 d-flex justify-content-between align-items-center">
                                            <label class="form-check-label"><?php echo __("2D Transitions", "catcbll"); ?>
                                                <div class="wcatcblltooltip"><i class="fa fa-question-circle" aria-hidden="true"></i><span class="wcatcblltooltiptext"><?php echo __('2D Transitions Hover On Add To Cart Button', 'catcbll') ?></span>
                                                </div>
                                            </label>
                                            <select name="catcbll_btn_2dhvr" id="wcatcbll_btn_2Dhvr"> <?php
                                                                                                        foreach ($wcatcbll_btn_2dhvrs as $wcatcbll_hvr_key => $wcatcbll_hvr_val) { ?> <option <?php if ($catcbll_btn_2dhvr == $wcatcbll_hvr_key) echo "selected='selected'";  ?> value="<?php echo $wcatcbll_hvr_key; ?>">
                                                        <?php echo $wcatcbll_hvr_val; ?></option> <?php }
                                                                                                    ?> </select>
                                        </div>
                                    </div>
                                    <!-- Button Margin End-->
                                    <!-- Button Margin Start-->
                                    <div class="row mt-2 ">
                                        <div class="col-12 d-flex justify-content-between align-items-center">
                                            <label class="form-check-label"><?php echo __("Background Transitions", "catcbll"); ?>
                                                <div class="wcatcblltooltip"><i class="fa fa-question-circle" aria-hidden="true"></i><span class="wcatcblltooltiptext"><?php echo __('Add To Cart Button Background Transitions On hover', 'catcbll') ?></span>
                                                </div>
                                            </label>
                                            <select name="catcbll_btn_bghvr" id="wcatcbll_btn_bghvr"> <?php
                                                                                                        foreach ($wcatcbll_btn_bghvrs as $wcatcbll_bghvr_key => $wcatcbll_bghvr_val) { ?> <option <?php if ($catcbll_btn_bghvr == $wcatcbll_bghvr_key) echo "selected='selected'";  ?> value="<?php echo $wcatcbll_bghvr_key; ?>">
                                                        <?php echo $wcatcbll_bghvr_val; ?></option> <?php }
                                                                                                    ?> </select>
                                        </div>
                                    </div>
                                    <!-- Button Margin End-->
                                    <!-- Cart Button's Settings End-->
                                </div>
                            </div>
                            <!-- 2nd Box Custom Button Display End-->
                        </div>
                    </div>
                    <div class="row mt-3">
                        <!--Shortcode Start-->
                        <div class="col-xl-12 col-lg-12 p-3 colbox">
                            <button type="submit" name="submit" id="submit_settings" class="btn btn-primary"><?php echo __('Save Changes', 'catcbll'); ?></button>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#shortcodeModal"><?php echo __('Shortcode', 'catcbll'); ?></button>
                            <button type="button" class="btn btn-info rdtuse text-white" data-bs-toggle="modal" data-bs-target="#demoModal" title="<?php echo __("Click Here To See Button Design", 'catcbll'); ?>"><?php echo __("Click Here To See Button Design", 'catcbll'); ?></button>
                            <input type="hidden" id="hide_2d_trans" name="catcbll_hide_2d_trans" value="<?php echo $catcbll_btn_2dhvr; ?>" />
                            <input type="hidden" id="hide_btn_bghvr" name="catcbll_hide_btn_bghvr" value="<?php echo $catcbll_btn_bghvr; ?>" />
                            <input type="hidden" id="ready_to_use" name="catcbll_ready_to_use" value="<?php echo $catcbll_ready_to_use; ?>" />
                        </div>
                        <!--Shortcode End-->
                    </div>
                </div>
                <!-- Left Box End-->
                <!-- Right Box Start-->
                <div class="col-xl-6 col-lg-6">
                    <div class="row justify-content-end">
                        <div class="col-xl-6 col-lg-6 ps-3 pe-0">
                            <div class="profeature card p-0 m-0" style="max-width:100%">
                                <h6 class="m-0 p-3 border-bottom"><?php echo __("Custom Add to Cart Button Label and Link - Pro", 'catcbll'); ?></h6>
                                <div class="p-3 active show" id="feature" role="tabpanel" aria-labelledby="feature-tab">
                                    <h6><?php echo __("Pro Version Features", 'catcbll'); ?></h6>
                                    <ul class="p-0 profeature">
                                        <li class="mb-2">
                                            <strong><?php echo __("Layout", 'catcbll'); ?></strong>
                                            <ul class="p-1">
                                                <li>* <?php echo __("Unique ID and a Class option for each product’s custom button", 'catcbll'); ?></li>
                                                <li>* <?php echo __("Different Google fonts with API", 'catcbll'); ?></li>
                                                <li>* <?php echo __("Option for setting custom Button’s font-weight, font-size, font-case", 'catcbll'); ?></li>
                                                <li>* <?php echo __("Separate margins, padding, and alignment options for shop and single product pages", 'catcbll'); ?></li>
                                                <li>* <?php echo __("Border radius for all corners", 'catcbll'); ?></li>
                                            </ul>
                                        </li>
                                        <li class="mb-2">
                                            <strong><?php echo __("Styling", 'catcbll'); ?></strong>
                                            <ul class="p-1">
                                                <li>* <?php echo __("Icon select with icon picker", 'catcbll'); ?></li>
                                                <li>* <?php echo __("Change icon size", 'catcbll'); ?></li>
                                                <li>* <?php echo __("Change icon Position", 'catcbll'); ?> </li>
                                                <li>* <?php echo __("Icon spacing / Spinner", 'catcbll'); ?></li>
                                            </ul>
                                        </li>
                                        <li class="mb-2">
                                            <strong><?php echo __("Advance Settings", 'catcbll'); ?></strong>
                                            <ul class="p-1">
                                                <li>* <?php echo __("All custom button Same Size", 'catcbll'); ?></li>
                                                <li>* <?php echo __("Default woocommerce button styling", 'catcbll'); ?></li>
                                            </ul>
                                        </li>
                                        <li class="mb-2">
                                            <strong><?php echo __("Category Based Custom Buttons", 'catcbll'); ?></strong>
                                        </li>
                                        <li class="mb-2">
                                            <strong><?php echo __("Sold Out Buttons / Out of Stock Buttons which appear automatically when Product is out of Stock.", 'catcbll'); ?></strong>
                                        </li>
                                        <li class="mb-2">
                                            <strong><?php echo __("Per Button Style", 'catcbll'); ?></strong>
                                        </li>
                                        <li class="mb-2">
                                            <strong><?php echo __("Global ( Label / URL )", 'catcbll'); ?></strong>
                                        </li>
                                        <li class="mb-2">
                                            <strong><?php echo __("Shortcode generater", 'catcbll'); ?></strong>
                                        </li>
                                        <li class="mb-2">
                                            <strong><?php echo __("Per category", 'catcbll'); ?></strong>
                                        </li>
                                        <li class="mb-2">
                                            <strong><?php echo __("Out of stock", 'catcbll'); ?></strong>
                                        </li>
                                        <li class="mb-2">
                                            <strong><?php echo __("Plugin Premium support", 'catcbll'); ?></strong>
                                        </li>
                                        <li class="mb-2">
                                            <strong><?php echo __("Import 1.5.4 button styling", 'catcbll'); ?></strong>
                                        </li>
                                        <li class="mb-2">
                                            <strong><?php echo __("Compatible with all product types.", 'catcbll'); ?></strong>
                                        </li>
                                        <li class="mb-2">
                                            <strong><?php echo __("Compatible with lambda, Avada, Astra, Divi, The7, etc.", 'catcbll'); ?></strong>
                                        </li>
                                    </ul>
                                </div>
                                <div class="p-3 border-top">
                                    <a href="https://plugins.hirewebxperts.com/documentation/" class="btn btn-primary text-white" target="_blank"><?php echo __("Read More", 'catcbll'); ?></a>
                                    <a href="https://plugins.hirewebxperts.com/custom-add-to-cart-button-and-link-pro/#ctbtnprice" class="btn btn-info text-white ms-2" target="_blank"><?php echo __("Buy Now", 'catcbll'); ?></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 ps-3 pe-0">
                            <div class="colbox p-3 ourplugin">
                                <h6 class="more_infor mt-3"><?php echo __("How to use Custom Add to Cart Plugin?", 'catcbll'); ?></h6>
                                <div class="colbox">
                                    <div class="side_review">
                                        <iframe src="https://www.youtube.com/embed/7t63igatlOU" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                        <div class="youtube_button">
                                            <p class="mb-0 mt-1 px-3 pb-3 vido"><a href="https://wordpress.org/support/plugin/woo-custom-cart-button/reviews/" target="_blank"><?php echo __('Please Review', 'catcbll'); ?> <span class="dashicons dashicons-thumbs-up"></span>
                                                </a>
                                            </p>
                                            <p class="mb-0 mt-1 px-3 pb-3 vido text-end">
                                                <a href="https://www.youtube.com/channel/UClog8CJFaMUqll0X5zknEEQ" class="sub_btn" target="_blank"><?php echo __('SUBSCRIBE', 'catcbll'); ?>
                                                </a>
                                            </p>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                                <h6 class="more_infor mt-3"><?php echo __("Try Our Other WordPress Plugins", 'catcbll'); ?>:</h6>
                                <div class="owl-carousel owl-theme " id="banners">
                                    <div class="item">
                                        <div class="card p-0 ">
                                            <img src="<?php echo WCATCBLL_CART_IMG . 'awesome-checkout.jpg' ?>">
                                            <div class="card-body p-2">
                                                <a href="https://wordpress.org/plugins/awesome-checkout-templates/" class="" target="_blank"><?php echo __("Awesome Checkout Templates", 'catcbll'); ?></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="card p-0">
                                            <img src="<?php echo WCATCBLL_CART_IMG . 'show-state-for-woocommerce.jpg' ?>">
                                            <div class="card-body p-2">
                                                <a href="https://wordpress.org/plugins/show-state-field-for-woocommerce/" class="" target="_blank"><?php echo __("Show State Field for WooCommerce", 'catcbll'); ?></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="card p-0">
                                            <img src="<?php echo WCATCBLL_CART_IMG . 'pasword-manager.jpg' ?>">
                                            <div class="card-body p-2">
                                                <a href="https://wordpress.org/plugins/passwords-manager/" class="" target="_blank"><?php echo __("Passwords Manager", 'catcbll'); ?></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="card p-0">
                                            <img src="<?php echo WCATCBLL_CART_IMG . 'text-convertor.jpg' ?>">
                                            <div class="card-body p-2">
                                                <a href="https://wordpress.org/plugins/text-case-converter/" class="" target="_blank"><?php echo __("Text Case Converter", 'catcbll'); ?></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="card p-0">
                                            <img src="<?php echo WCATCBLL_CART_IMG . 'horizontal-slider.jpg' ?>">
                                            <div class="card-body p-2">
                                                <a href="https://wordpress.org/plugins/horizontal-slider-with-scroll/" class="" target="_blank"><?php echo __("Horizontal Slider with Scroll", 'catcbll'); ?></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="card p-0">
                                            <img src="<?php echo WCATCBLL_CART_IMG . 'digital-warranty-card.jpg' ?>">
                                            <div class="card-body p-2">
                                                <a href="https://wordpress.org/plugins/digital-warranty-card-generator/" class="" target="_blank"><?php echo __("Digital Warranty Card Generator", 'catcbll'); ?></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="card p-0">
                                            <img src="<?php echo WCATCBLL_CART_IMG . 'country-state-selection.jpg' ?>">
                                            <div class="card-body p-2">
                                                <a href="https://wordpress.org/plugins/gforms-addon-for-country-and-state-selection/" class="" target="_blank"><?php echo __("Country and State Selection Addon for Gravity Form", 'catcbll'); ?>s</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h6 class="more_infor my-3"><?php echo __("Try World Class Hosting Services", 'catcbll'); ?>:</h6>

                                <div class="owl-carousel owl-theme  " id="kinsta_banners">
                                    <div class="item">
                                        <a href="https://kinsta.com/" target="_blank">
                                            <img src="<?php echo WCATCBLL_CART_IMG . 'kinsta1.png' ?>" />
                                        </a>
                                    </div>
                                    <div class="item">
                                        <a href="https://kinsta.com/" target="_blank">
                                            <img src="<?php echo WCATCBLL_CART_IMG . 'kinsta2.jpg' ?>" />
                                        </a>
                                    </div>
                                    <div class="item">
                                        <a href="https://kinsta.com/" target="_blank">
                                            <img src="<?php echo WCATCBLL_CART_IMG . 'kinsta3.png' ?>" />
                                        </a>
                                    </div>
                                    <div class="item">
                                        <a href="https://kinsta.com/" target="_blank">
                                            <img src="<?php echo WCATCBLL_CART_IMG . 'kinsta4.png' ?>" />
                                        </a>
                                    </div>
                                    <div class="item">
                                        <a href="https://kinsta.com/" target="_blank">
                                            <img src="<?php echo WCATCBLL_CART_IMG . 'kinsta5.png' ?>" />
                                        </a>
                                    </div>
                                    <div class="item">
                                        <a href="https://kinsta.com/" target="_blank">
                                            <img src="<?php echo WCATCBLL_CART_IMG . 'kinsta6.jpg' ?>" />
                                        </a>
                                    </div>
                                    <div class="item">
                                        <a href="https://kinsta.com/" target="_blank">
                                            <img src="<?php echo WCATCBLL_CART_IMG . 'kinsta7.jpg' ?>" />
                                        </a>
                                    </div>
                                    <div class="item">
                                        <a href="https://kinsta.com/" target="_blank">
                                            <img src="<?php echo WCATCBLL_CART_IMG . 'kinsta8.png' ?>" />
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
<div id="wcbnl_overlay">
    <div class="cv-spinner">
        <img src="<?php echo WCATCBLL_CART_IMG . 'spinner.svg' ?>">
    </div>
</div> <?php include(WCATCBLL_CART_INC . '/admin/wcatcbll_setting_modal_popup.php'); ?>