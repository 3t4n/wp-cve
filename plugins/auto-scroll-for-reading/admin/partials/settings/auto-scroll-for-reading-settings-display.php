<?php
    require_once( AUTO_SCROLL_FOR_READING_ADMIN_PATH . "/partials/settings/auto-scroll-for-reading-settings-actions-options.php" );
?>
<div class="wrap" style="position:relative;">
    <div class="wpg-auto-scroll-heading-box">
        <div class="wpg-auto-scroll-wordpress-user-manual-box">
            <a href="https://wpglob.com/wordpress-autoscroll-plugin/" target="_blank"><?php echo __("View Documentation", $this->plugin_name); ?></a>
        </div>
    </div>
    <div class="container-fluid">
        <form method="post" id="wpg-auto-scroll-settings-form">
            <input type="hidden" name="wpg_auto_scroll_tab" value="<?php echo $wpg_auto_scroll_tab; ?>">
            <h1 class="wp-heading-inline">
            <?php
                echo __('General Settings',$this->plugin_name);
            ?>
            </h1>
            <?php
                if( isset( $_REQUEST['status'] ) ){
                    $actions->auto_scroll_settings_notices( sanitize_text_field( $_REQUEST['status'] ) );
                }
            ?>
            <hr/>
            <div class="wpg-settings-wrapper">
                <div>
                    <div class="nav-tab-wrapper" style="position:sticky; top:35px;">
                        <a href="#tab1" data-tab="tab1" class="nav-tab <?php echo ($wpg_auto_scroll_tab == 'tab1') ? 'nav-tab-active' : ''; ?>">
                            <?php echo __("General", $this->plugin_name);?>
                        </a>
                    </div>
                </div>
                <div class="wpg-auto-scroll-tabs-wrapper">
                    <div id="tab1" class="wpg-auto-scroll-tab-content <?php echo ($wpg_auto_scroll_tab == 'tab1') ? 'wpg-auto-scroll-tab-content-active' : ''; ?>">
                        <p class="wpg-subtitle"><?php echo __('General Settings',$this->plugin_name)?></p>
                        <hr/>
                        <fieldset>
                            <legend>
                                <strong style="font-size:30px;"><i class="wpg_fa wpg_fa_question_circle"></i></strong>
                                <h5><?php echo __('Settings',$this->plugin_name)?></h5>
                            </legend>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="wpg_auto_scroll_button_positions">
                                        <?php echo __( "Button position", $this->plugin_name ); ?>
                                        <a class="wpg_help" data-toggle="tooltip" title="<?php echo __('Specify the position of the button.',$this->plugin_name)?>">
                                            <i class="wpg_fa wpg_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <div class="form-check form-check-inline wpg_auto_scrol_radio_box">
                                        <label class="form-check-label wpg_auto_scrol_check_label" for="wpg_auto_scrol_position_left"> <?php echo __("Left", $this->plugin_name)?> </label>
                                        <input type="radio" class="wpg_auto_scrol_positions" id="wpg_auto_scrol_position_left" name="wpg_auto_scrol_positions" value="left" <?php echo ( $wpg_auto_scroll_button_position ==  "left") ? "checked" : ""; ?>>
                                    </div>
                                    <div class="form-check form-check-inline wpg_auto_scrol_radio_box">
                                        <label class="form-check-label wpg_auto_scrol_check_label" for="wpg_auto_scrol_position_right"> <?php echo __("Right", $this->plugin_name)?> </label>
                                        <input type="radio" class="wpg_auto_scrol_positions" id="wpg_auto_scrol_position_right" name="wpg_auto_scrol_positions" value="right" <?php echo ( $wpg_auto_scroll_button_position ==  "right") ? "checked" : ""; ?>>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="wpg-auto-scroll-button-color">
                                        <?php echo __( "Button color", $this->plugin_name ); ?>
                                        <a class="wpg_help" data-toggle="tooltip" title="<?php echo __('Specify the color of the button.',$this->plugin_name)?>">
                                            <i class="wpg_fa wpg_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" class="wpg-text-input" id='wpg-auto-scroll-button-color' name='wpg_auto_scroll_button_color' data-alpha="true" data-default-color="#6369d1" value="<?php echo $wpg_auto_scroll_button_color; ?>"/>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="wpg-auto-scroll-rescroll-delay">
                                        <?php echo __( "Rescroll delay", $this->plugin_name ); ?>
                                        <a class="wpg_help" data-toggle="tooltip" title="<?php echo __('Define the period of pause in seconds.',$this->plugin_name)?>">
                                            <i class="wpg_fa wpg_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" class="wpg-text-input" id='wpg-auto-scroll-rescroll-delay' name='wpg_auto_scroll_rescroll_delay' value="<?php echo $wpg_auto_scroll_rescroll_delay; ?>"/>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row wpg_toggle_parent">
                                <div class="col-sm-4">
                                    <label for="wpg-auto-scroll-autoplay">
                                        <?php echo __( "Autoplay on page load", $this->plugin_name ); ?>
                                        <a class="wpg_help" data-toggle="tooltip" title="<?php echo __('When this option is enabled, the page will automatically start scrolling after page load.',$this->plugin_name)?>">
                                            <i class="wpg_fa wpg_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-1">
                                    <input type="checkbox" class="wpg-text-input wpg_toggle_checkbox" id='wpg-auto-scroll-autoplay' name='wpg_auto_scroll_autoplay' value="on" <?php echo ($wpg_auto_scroll_autoplay) ? "checked" : ""; ?>/>
                                </div>
                                <div class="col-sm-7 wpg_toggle_target <?php echo $wpg_auto_scroll_autoplay ? '' : 'display_none'; ?>">
                                    <div class="form-group row">
                                        <div class="col-sm-2">
                                            <label for="wpg-auto-scroll-autoplay-delay">
                                                <?php echo __('Delay',$this->plugin_name)?>
                                                <a class="wpg_help" data-toggle="tooltip" title="<?php echo __('Specify the time after which the scrolling starts in seconds.',$this->plugin_name); ?>">
                                                    <i class="wpg_fa wpg_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-10">
                                            <input type="number" class="wpg-text-input" id="wpg-auto-scroll-autoplay-delay" name="wpg_auto_scroll_autoplay_delay" value="<?php echo $wpg_auto_scroll_autoplay_delay; ?>"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="wpg-auto-scroll-hover-title">
                                        <?php echo __( "Show the title by hovering", $this->plugin_name ); ?>
                                        <a class="wpg_help" data-toggle="tooltip" title="<?php echo __('The title attribute appears instantly after hovering.',$this->plugin_name)?>">
                                            <i class="wpg_fa wpg_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-1">
                                    <input type="checkbox" class="wpg-text-input wpg_toggle_checkbox" id='wpg-auto-scroll-hover-title' name='wpg_auto_scroll_hover_title' value="on" <?php echo ($wpg_auto_scroll_hover_title) ? "checked" : ""; ?>/>
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row wpg_toggle_parent">
                                <div class="col-sm-4">
                                    <label for="wpg-auto-scroll-go-to-top-automatically">
                                        <?php echo __( "Move to the top automatically", $this->plugin_name ); ?>
                                        <a class="wpg_help" data-toggle="tooltip" title="<?php echo __('After clicking on the autoscroll button right after reaching to the end of the page you will automatically move to the top.',$this->plugin_name)?>">
                                            <i class="wpg_fa wpg_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-1">
                                    <input type="checkbox" class="wpg-text-input wpg_toggle_checkbox" id='wpg-auto-scroll-go-to-top-automatically' name='wpg_auto_scroll_go_to_top_automatically' value="on" <?php echo ($wpg_auto_scroll_go_to_top_automatically) ? "checked" : ""; ?>/>
                                </div>
                                <div class="col-sm-7 wpg_toggle_target <?php echo $wpg_auto_scroll_go_to_top_automatically ? '' : 'display_none'; ?>">
                                    <div class="form-group row">
                                        <div class="col-sm-2">
                                            <label for="wpg-auto-scroll-go-to-top-automatically-delay">
                                                <?php echo __('Delay',$this->plugin_name)?>
                                                <a class="wpg_help" data-toggle="tooltip" title="<?php echo __('Specify the time after which you will automatically moved to the top of the page',$this->plugin_name); ?>">
                                                    <i class="wpg_fa wpg_fa_info_circle"></i>
                                                </a>
                                            </label>
                                        </div>
                                        <div class="col-sm-10">
                                            <input type="number" class="wpg-text-input" id="wpg-auto-scroll-go-to-top-automatically-delay" name="wpg_auto_scroll_go_to_top_automatically_delay" value="<?php echo $wpg_auto_scroll_go_to_top_automatically_delay; ?>"/>
                                        </div>
                                    </div>
                                </div>
                            </div>      
                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="wpg-auto-scroll-default-speed">
                                        <?php echo __( "Default speed", $this->plugin_name ); ?>
                                        <a class="wpg_help" data-toggle="tooltip" title="<?php //echo __('After clicking on the autoscroll button right after reaching to the end of the page you will automatically move to the top.',$this->plugin_name)?>">
                                            <i class="wpg_fa wpg_fa_info_circle"></i>
                                        </a>
                                    </label>
                                </div>
                                <div class="col-sm-1">
                                    <select class="wpg-select wpg_choose_select" id='wpg-auto-scroll-default-speed' name='wpg_auto_scroll_default_speed' value="on"/>
                                        <option value="1" <?php echo ($wpg_auto_scroll_default_speed  == "1") ? "selected" : ""; ?> >x1</option>
                                        <option value="2" <?php echo ($wpg_auto_scroll_default_speed  == "2") ? "selected" : ""; ?> >x2</option>
                                        <option value="4" <?php echo ($wpg_auto_scroll_default_speed  == "4") ? "selected" : ""; ?> >x4</option>
                                    </select>
                                </div>
                            </div>                     
                        </fieldset>
                    </div>
                </div>
            </div>
            <hr/>
            <div style="position:sticky;padding:15px 0px;bottom:0;">
                <?php
                    wp_nonce_field('settings_action', 'settings_action');
                    $other_attributes = array();
                    submit_button(__('Save changes', $this->plugin_name), 'primary wpg-auto-scroll-loader-banner wpg-auto-scroll-gen-settings-save', 'wpg_submit', true, $other_attributes);
                    // echo $loader_iamge;
                ?>
            </div>
        </form>
    </div>
</div>
