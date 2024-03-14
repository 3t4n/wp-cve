<div class="tab-pane fade" id="v-pills-widget" role="tabpanel" aria-labelledby="widget-style-tab">

    <div class="title">Widget style</div>

    <div class="form-group">
        <div class="subtitle">Widget style (Optional)</div>
        <div class="ui fluid search selection dropdown">
            <input type="hidden" name="style_widget" value="<?php echo esc_html($this->variables->style_widget); ?>">
            <i class="dropdown icon"></i>
            <div class="default text"><?php echo __('Select widget style', 'conveythis-translate'); ?></div>
            <div class="menu" id="style_widget_list">
                <div class="item" data-value="">No value</div>
                <?php foreach ($this->getWidgetStyles() as $styleCode => $styleName): ?>
                    <div class="item" data-value="<?php echo esc_attr($styleCode); ?>">
                        <?php esc_html_e($styleName, 'conveythis-translate'); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="subtitle">Picture</div>
        <label for="">Select the display style for flags</label>
        <div class="radio-block">
            <div class="form-check">
                <input type="radio" class="form-check-input me-2" name="style_flag" id="rect" value="rect" <?php echo  $this->variables->style_flag == 'rect' ? 'checked' : '' ?>>
                <label for="rect">Rectangle</label></div>
            <div class="form-check">
                <input type="radio" class="form-check-input me-2" name="style_flag" id="sqr" value="sqr" <?php echo  $this->variables->style_flag == 'sqr' ? 'checked' : '' ?> >
                <label for="sqr">Square</label></div>
            <div class="form-check">
                <input type="radio" class="form-check-input me-2" name="style_flag" id="cir" value="cir" <?php echo  $this->variables->style_flag == 'cir' ? 'checked' : '' ?>>
                <label for="cir">Circle</label></div>
            <div class="form-check">
                <input type="radio" class="form-check-input me-2" name="style_flag" id="without-flag" value="without-flag" <?php echo  $this->variables->style_flag == 'without-flag' ? 'checked' : '' ?>>
                <label for="without-flag">Without</label></div>
        </div>
    </div>

    <div class="form-group">
        <div class="subtitle">Text</div>
        <label for="">Display the text name of the language</label>
        <div class="radio-block">
            <div class="form-check">
                <input type="radio" class="form-check-input me-2" name="style_text" id="full-text" value="full-text" <?php echo  $this->variables->style_text == 'full-text' ? 'checked' : '' ?> >
                <label for="full-text">Full text</label></div>
            <div class="form-check">
                <input type="radio" class="form-check-input me-2" name="style_text" id="short-text" value="short-text" <?php echo  $this->variables->style_text == 'short-text' ? 'checked' : '' ?> >
                <label for="short-text">Short text</label></div>
            <div class="form-check">
                <input type="radio" class="form-check-input me-2" name="style_text" id="without-text" value="without-text" <?php echo  $this->variables->style_text == 'without-text' ? 'checked' : '' ?> >
                <label for="without-text">Without text</label></div>
        </div>
    </div>

    <div class="notify" style="display: none; color: red;">
        <p>You must select at least one option: either leave a text or check the flag.</p>
    </div>

    <div class="form-group">
        <div class="subtitle">Change flag</div>
        <label>By default all the languages have their flags in accordance with ISO standards. If you want to change the flag for one or several languages here you can customize this.</label>
        <div id="flag-style_wrapper" class="w-100">
            <div class="style-language w-100 position-relative cloned">
                <button class="conveythis-delete-page"></button>
                <div class="row w-100">
                    <div class="col-md-6">
                        <div class="ui fluid search selection dropdown change_language">
                            <i class="dropdown icon"></i>
                            <div class="default text"><?php echo  __( 'Select language', 'conveythis-translate' ); ?></div>
                            <div class="menu">

                                <?php foreach( $this->variables->matchingLanguages as $id => $language ): ?>

                                    <div class="item" data-value="<?php echo  esc_attr($id); ?>">
                                        <?php esc_html_e( $language['title_en'], 'conveythis-translate' ); ?>
                                    </div>

                                <?php endforeach; ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="ui fluid search selection dropdown change_flag">
                            <i class="dropdown icon"></i>
                            <div class="default text"><?php echo  __( 'Select Flag', 'conveythis-translate' ); ?></div>
                            <div class="menu">

                                <?php foreach( $this->variables->matchingFlags as $flag ): ?>

                                    <div class="item" data-value="<?php echo  esc_attr( $flag['code'] ); ?>">
                                        <div class="ui image" style="height: 28px; width: 30px; background-position: 50% 50%; background-size: contain; background-repeat: no-repeat; background-image: url('//cdn.conveythis.com/images/flags/svg/<?php echo  esc_attr($flag['code']); ?>.svg')"></div>
                                        <?php esc_html_e( $flag['title'], 'conveythis-translate' ); ?>
                                    </div>

                                <?php endforeach; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $i = 0;
            while( $i < (count($this->variables->style_change_language)) ): ?>
            <?php if($this->variables->style_change_language[$i] > 0): ?>
                <div class="style-language w-100 position-relative">
                    <button class="conveythis-delete-page"></button>
                    <div class="row w-100">
                        <div class="col-md-6">
                            <div class="ui fluid search selection dropdown change_language">
                                <input type="hidden" name="style_change_language[]" value="<?php echo  (!empty($this->variables->style_change_language[$i])) ? esc_attr($this->variables->style_change_language[$i]): "" ; ?>">
                                <i class="dropdown icon"></i>
                                <div class="default text"><?php echo  __( 'Select language', 'conveythis-translate' ); ?></div>
                                <div class="menu">

                                    <?php foreach( $this->variables->matchingLanguages as $id => $language ): ?>

                                        <div class="item" data-value="<?php echo  esc_attr($id); ?>">
                                            <?php esc_html_e( $language['title_en'], 'conveythis-translate' ); ?>
                                        </div>

                                    <?php endforeach; ?>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="ui fluid search selection dropdown change_flag">
                                <input type="hidden" name="style_change_flag[]" value="<?php echo  (!empty($this->variables->style_change_flag[$i])) ? esc_attr($this->variables->style_change_flag[$i]) : ""; ?>">
                                <i class="dropdown icon"></i>
                                <div class="default text"><?php echo  __( 'Select Flag', 'conveythis-translate' ); ?></div>
                                <div class="menu">
                                    <?php $languageId = $this->variables->style_change_language[$i];?> 
                                    <?php $flagsArr = $this->variables->flags;?>

                                    <?php foreach( $this->variables->matchingLanguageToFlag[$languageId] as $id ): ?>

                                        <div class="item" data-value="<?php echo  esc_attr( $flagsArr[$id]['code'] ); ?>">
                                            <div class="ui image" style="height: 28px; width: 30px; background-position: 50% 50%; background-size: contain; background-repeat: no-repeat; background-image: url('//cdn.conveythis.com/images/flags/svg/<?php echo  esc_attr($flagsArr[$id]['code']); ?>.svg')"></div>
                                            <?php esc_html_e( $flagsArr[$id]['title'], 'conveythis-translate' ); ?>
                                        </div>

                                    <?php endforeach; ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                <?php $i++;?>
            <?php endwhile; ?>
        </div>
        <button class="btn-default" type="button" id="add_flag_style" style="color: #8A8A8A">Add more rule</button>
    </div>

    <div class="form-group">
        <div class="subtitle">Color Style</div>
        <div class="row w-100">
            <div class="col-md-6">
                <label>Background color of widget</label>
                <div class="d-flex">
                    <input type="color" class="form-control form-control-color me-2" id="style_background_color" name="style_background_color" value="<?php echo  esc_attr($this->variables->style_background_color) ?>" data-default="#ffffff">
                    <button class="btn-default-color" type="button">Set default</button>
                </div>
            </div>
            <div class="col-md-6">
                <label>Background color of widget on hover</label>
                <div class="d-flex">
                    <input type="color" class="form-control form-control-color me-2" id="style_hover_color" name="style_hover_color" value="<?php echo  esc_attr($this->variables->style_hover_color) ?>"
                    data-default="#f6f6f6">
                    <button class="btn-default-color" type="button">Set default</button>
                </div>
            </div>
        </div>
        <div class="row w-100">
            <div class="col-md-6">
                <label>Border color of widget</label>
                <div class="d-flex">
                    <input type="color" class="form-control form-control-color me-2" id="style_border_color" name="style_border_color" value="<?php echo  esc_attr($this->variables->style_border_color) ?>"  data-default="#e0e0e0">
                    <button class="btn-default-color" type="button">Set default</button>
                </div>
            </div>
            <div class="col-md-6">
                <label>Text color of widget</label>
                <div class="d-flex">
                    <input type="color" class="form-control form-control-color me-2" id="style_text_color" name="style_text_color" value="<?php echo  esc_attr($this->variables->style_text_color) ?>" data-default="#000000">
                    <button class="btn-default-color" type="button">Set default</button>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="subtitle">Hide ConveyThis logo</div>
        <label class="hide-paid" for="">This feature is not available on Free plan. If you want to use this feature, please <a href="https://app.conveythis.com/dashboard/pricing/?utm_source=widget&utm_medium=wordpress" target="_blank" class="grey">upgrade your plan</a>.</label>
        <div class="radio-block">
            <div class="form-check paid-function">
                <input type="radio" class="form-check-input me-2" id="hide_conveythis_logo_yes" name="hide_conveythis_logo" value="1" <?php echo $this->variables->hide_conveythis_logo == 1 ? 'checked' : ''?>>
                <label for="hide_conveythis_logo_yes">Yes</label></div>
            <div class="form-check">
                <input type="radio" class="form-check-input me-2" id="hide_conveythis_logo_no" name="hide_conveythis_logo" value="0" <?php echo $this->variables->hide_conveythis_logo == 0 ? 'checked' : ''?>>
                <label for="hide_conveythis_logo_no">No</label></div>
        </div>
    </div>

    <div class="form-group">
        <div class="subtitle">Corner type</div>
        <div class="radio-block">
            <div class="form-check">
                <input type="radio" class="form-check-input me-2" name="style_corner_type" id="cir2" value="cir" <?php echo  $this->variables->style_corner_type == 'cir' ? 'checked' : '' ?> >
                <label for="cir2">Circle</label></div>
            <div class="form-check">
                <input type="radio" class="form-check-input me-2" name="style_corner_type" id="rect2" value="rect" <?php echo  $this->variables->style_corner_type == 'rect' ? 'checked' : '' ?>>
                <label for="rect2">Rectangle</label></div>
        </div>
    </div>

    <div class="form-group">
        <div class="subtitle">Position type</div>
        <div class="radio-block">
            <div class="form-check">
                <input type="radio" class="form-check-input me-2" name="style_position_type" id="fixed" value="fixed" <?php echo  $this->variables->style_position_type == 'fixed' ? 'checked' : '' ?>>
                <label for="fixed">Fixed</label></div>
            <div class="form-check">
                <input type="radio" class="form-check-input me-2" name="style_position_type" id="custom" value="custom" <?php echo  $this->variables->style_position_type == 'custom' ? 'checked' : '' ?>>
                <label for="custom">Custom</label>
            </div>
        </div>
    </div>
    <div id="position-custom" <?php echo $this->variables->style_position_type == 'fixed' ? 'style="display:none;"' : '' ?> >
        <div class="form-group">
            <div class="subtitle"> <?php echo __( 'Enter id of element, where button will be placed', 'conveythis-translate' ); ?></div>
            <label><?php echo  __( '* If id of element will not be found on the page, default position will be used', 'conveythis-translate' );?></label>
            <div class="ui input">
                <input type="text" name="style_selector_id" class="w-100" placeholder="Enter id" value="<?php echo  $this->variables->style_selector_id;?>">
            </div>
        </div>
        <div class="form-group">
            <div class="subtitle"> <?php echo __( 'Select dropdown menu direction', 'conveythis-translate' ); ?></div>
            <div class="radio-block">
                <div class="form-check">
                    <input type="radio" class="form-check-input me-2" name="style_position_vertical_custom" id="bottom" value="bottom" <?php echo  $this->variables->style_position_vertical_custom == 'bottom' ? 'checked' : '' ?>>
                    <label for="top">Bottom</label></div>
                <div class="form-check">
                    <input type="radio" class="form-check-input me-2" name="style_position_vertical_custom" id="top" value="top" <?php echo  $this->variables->style_position_vertical_custom == 'top' ? 'checked' : '' ?>>
                    <label for="bottom">Top</label></div>
            </div>
        </div>

    </div>

    <div class="form-group">
        <div class="subtitle">Vertical location of the language selection button on the site</div>
        <div class="radio-block">
            <div class="form-check">
                <input type="radio" class="form-check-input me-2" name="style_position_vertical" id="top" value="top" <?php echo  $this->variables->style_position_vertical == 'top' ? 'checked' : '' ?>>
                <label for="top">Top</label></div>
            <div class="form-check">
                <input type="radio" class="form-check-input me-2" name="style_position_vertical" id="bottom" value="bottom" <?php echo  $this->variables->style_position_vertical == 'bottom' ? 'checked' : '' ?>>
                <label for="bottom">Bottom</label></div>
        </div>
    </div>

    <div class="form-group">
        <div class="subtitle">Horizontal location of the language selection button on the site</div>
        <div class="radio-block">
            <div class="form-check">
                <input type="radio" class="form-check-input me-2" name="style_position_horizontal" id="left" value="left" <?php echo  $this->variables->style_position_horizontal == 'left' ? 'checked' : '' ?>>
                <label for="left">Left</label></div>
            <div class="form-check">
                <input type="radio" class="form-check-input me-2" name="style_position_horizontal" id="right" value="right" <?php echo  $this->variables->style_position_horizontal == 'right' ? 'checked' : '' ?>>
                <label for="right">Right</label></div>
        </div>
    </div>

    <div class="form-group">
        <div class="subtitle">Indenting</div>
        <label>Vertical spacing from the top or bottom of the browser</label>
        <div class="d-flex align-items-center w-100">
            <div>
                <input type="hidden" name="style_indenting_vertical" value="<?php echo  esc_attr($this->variables->style_indenting_vertical); ?>">
                <span id="display-style-indenting-vertical"><?php echo  esc_attr($this->variables->style_indenting_vertical); ?></span>px
            </div>
            <div class="ui grey slider" id="range-style-indenting-vertical">
                <div class="inner">
                    <div class="track" style="height: 4px;"></div>
                    <div class="track-fill" style="height: 4px;"></div>
                    <div class="thumb"></div>
                </div>
            </div>
        </div>
        <label>Horizontal spacing from the top or bottom of the browser</label>
        <div class="d-flex align-items-center w-100">
            <div>
                <input type="hidden" name="style_indenting_horizontal" value="<?php echo  esc_attr($this->variables->style_indenting_horizontal); ?>">
                <span id="display-style-indenting-horizontal"><?php echo  esc_attr($this->variables->style_indenting_horizontal); ?></span>px
            </div>    
            <div class="ui grey slider" id="range-style-indenting-horizontal">
                <div class="inner">
                    <div class="track" style="height: 4px;"></div>
                    <div class="track-fill" style="height: 4px;"></div>
                    <div class="thumb"></div>
                </div>
            </div>
        </div>
    </div>
</div>