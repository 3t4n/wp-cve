<?php

namespace OXI_FLIP_BOX_PLUGINS\Inc;

/**
 * Description of Style1
 *
 * @author biplo
 */
use OXI_FLIP_BOX_PLUGINS\Page\Admin_Render;

class Style12 extends Admin_Render {

    public function register_controls() {
        ?>
        <div class="oxi-addons-tabs-content-tabs" id="oxilab-tabs-id-5">
            <div class="oxi-addons-col-6">
                <div class="oxi-addons-content-div">
                    <div class="oxi-head">
                        General Settings
                    </div>
                    <?php
                    $this->oxilab_flip_box_flip_type_effects_type($this->style[1], $this->style[3]);
                    $this->oxilab_flip_box_admin_col_data('flip-col', $this->style[43], 'Item per Rows', 'How many item shows in single Rows');
                    $this->oxilab_flip_box_admin_number('flip-width', $this->style[45], '1', 'Width', 'Give your Filp Width');
                    $this->oxilab_flip_box_admin_number('flip-height', $this->style[47], '1', 'Height', 'Give your Flip Height');
                    $this->oxilab_flip_box_admin_number('flip-border-radius', $this->style[129], '1', 'Border Radius', 'Set your flip Border Radius');
                    ?>
                </div>
                <div class="oxi-addons-content-div">
                    <div class="oxi-head">
                        Optional Settings
                    </div>
                    <?php
                    $this->oxilab_flip_box_admin_number_double('margin-top', $this->style[49], 'margin-left', $this->style[51], 'Margin', 'Set your Margin top bottom and left right');
                    $this->oxilab_flip_box_admin_true_false('flip-open-tabs', $this->style[53], 'New tabs', '_blank', 'Normal', '', 'Link Open', 'Dow you want to open link at same Tabs or new Windows');
                    ?>
                </div>
            </div>
            <div class="oxi-addons-col-6">
                <div class="oxi-addons-content-div">
                    <div class="oxi-head">
                        Animation
                    </div>
                    <?php
                    $this->oxilab_flip_box_admin_animation_select($this->style[55]);
                    $this->oxilab_flip_box_admin_number('animation-duration', $this->style[57], '0.1', 'Animation Duration', 'Give your Animation Duration into Second');
                    ?>
                </div>
                <div class="oxi-addons-content-div">
                    <div class="oxi-head">
                        Box Shadow
                    </div>
                    <?php
                    $this->oxilab_flip_box_admin_color('flip-boxshow-color', $this->style[59], 'rgba', 'Color', 'Give your Box Shadow Color', '', '');
                    $this->oxilab_flip_box_admin_number_double('flip-boxshow-horizontal', $this->style[61], 'flip-boxshow-vertical', $this->style[63], 'Shadow Length', 'Giveyour Box Shadow lenth as horizontal and vertical');
                    $this->oxilab_flip_box_admin_number_double('flip-boxshow-blur', $this->style[65], 'flip-boxshow-spread', $this->style[67], 'Shadow Radius', 'Giveyour Box Shadow Radius as Blur and Spread');
                    ?>
                </div>
            </div>
        </div>
        <div class="oxi-addons-tabs-content-tabs" id="oxilab-tabs-id-4">
            <div class="oxi-addons-col-6">
                <div class="oxi-addons-content-div">
                    <div class="oxi-head">
                        General Settings
                    </div>
                    <?php
                    $this->oxilab_flip_box_admin_color('front-background-color', $this->style[5], 'rgba', 'Background Color', 'Set your Front Background Color', 'background-color', '.oxilab-flip-box-' . $this->oxiid . '');
                    $this->oxilab_flip_box_admin_color('front-border-color', $this->style[7], '', 'Border Color', 'Set your Border Color', 'border-color', '.oxilab-flip-box-' . $this->oxiid . '');
                    $this->oxilab_flip_box_admin_border('flip-col-border-size', $this->style[125], 'flip-col-border-style', $this->style[127], 'Border Size', 'Set your front border size with different style');
                    $this->oxilab_flip_box_admin_number_double('front-padding-top', $this->style[71], 'front-padding-left', $this->style[73], 'Padding', 'Set your Front Padding as Top Bottom and Left Right');
                    ?>
                </div>
                <div class="oxi-addons-content-div">
                    <div class="oxi-head">
                        Icon Settings
                    </div>
                    <?php
                    $this->oxilab_flip_box_admin_number('front-icon-size', $this->style[77], '1', 'Icon Size', 'Set your Icon Font Size');
                    $this->oxilab_flip_box_admin_color('front-icon-color', $this->style[9], '', 'Icon Color', 'Set your Icon Color', 'color', '.oxilab-flip-box-' . $this->oxiid . '-data2 .oxilab-icon-data [class^=\'fa\']');
                    $this->oxilab_flip_box_admin_color('front-icon-background', $this->style[11], 'rgba', 'Icon Background', 'Set your icon Background Color', 'background-color', '.oxilab-flip-box-' . $this->oxiid . '-data2');
                    $this->oxilab_flip_box_admin_number('front-icon-width', $this->style[79], '1', 'Icon width', 'Set your Icon Width and Height Size.');
                    $this->oxilab_flip_box_admin_number('front-icon-height', $this->style[75], '1', 'Icon Box Hight', 'Set your Icon Box height, start with the top.');
                    $this->oxilab_flip_box_admin_number('front-icon-border-radius', $this->style[81], '1', 'Border Radius', 'Set Your Icon Border Radius');
                    ?>
                </div>
            </div>
            <div class="oxi-addons-col-6">
                <div class="oxi-addons-content-div">
                    <div class="oxi-head">
                        Heading Settings
                    </div>
                    <?php
                    $this->oxilab_flip_box_admin_number('front-heading-size', $this->style[83], '1', 'Font Size', 'Set your front Heading Font Size');
                    $this->oxilab_flip_box_admin_color('front-heading-color', $this->style[13], '', 'Title Color', 'Set your Front Heading Color', 'color', '.oxilab-flip-box-' . $this->oxiid . '-data .oxilab-heading');
                    $this->oxilab_flip_box_admin_font_family('front-heading-family', $this->style[85], 'Font Family', 'Give your Prepared Font from our Google Font List');
                    $this->oxilab_flip_box_admin_font_style('front-heding-style', $this->style[87], 'Font Style', 'Set your Heading Font Style');
                    $this->oxilab_flip_box_admin_font_weight('front-heding-weight', $this->style[89], 'Font Weight', 'Give your Front Heading Font Weight');
                    $this->oxilab_flip_box_admin_text_align('front-heding-text-align', $this->style[91], 'Text Align', 'Give your Heading Text Align');
                    $this->oxilab_flip_box_admin_number_double('front-heding-padding-top', $this->style[93], 'front-heding-padding-bottom', $this->style[95], 'Padding Top Bottom', 'Set Your Heading  Padding Top and Bottom');
                    $this->oxilab_flip_box_admin_number_double('front-heding-padding-left', $this->style[97], 'front-heding-padding-right', $this->style[99], 'Padding Left Right', 'Set Your Heading  Padding Left and Right');
                    ?>
                </div>
                <div class="oxi-addons-content-div">
                    <div class="oxi-head">
                        Information Settings
                    </div>
                    <?php
                    $this->oxilab_flip_box_admin_number('front-info-size', $this->style[139], '1', 'Font Size', 'Set your front Info Font Size');
                    $this->oxilab_flip_box_admin_color('front-info-color', $this->style[15], '', 'Text Color', 'Set your Front Heading Color', 'color', '.oxilab-flip-box-' . $this->oxiid . '-data .oxilab-info');
                    $this->oxilab_flip_box_admin_font_family('front-info-family', $this->style[141], 'Font Family', 'Give your Prepared Font from our Google Font List');
                    $this->oxilab_flip_box_admin_font_style('front-info-style', $this->style[143], 'Font Style', 'Set your Info Font Style');
                    $this->oxilab_flip_box_admin_font_weight('front-info-weight', $this->style[145], 'Font Weight', 'Give your Front Info Font Weight');
                    $this->oxilab_flip_box_admin_text_align('front-info-text-align', $this->style[147], 'Text Align', 'Give your Info Text Align');
                    $this->oxilab_flip_box_admin_number_double('front-info-padding-top', $this->style[149], 'front-info-padding-bottom', $this->style[151], 'Padding Top Bottom', 'Set Your Info  Padding Top and Bottom');
                    $this->oxilab_flip_box_admin_number_double('front-info-padding-left', $this->style[153], 'front-info-padding-right', $this->style[155], 'Padding Left Right', 'Set Your Info  Padding Left and Right');
                    ?>
                </div>
            </div>
        </div>
        <div class="oxi-addons-tabs-content-tabs" id="oxilab-tabs-id-3">
            <div class="oxi-addons-col-6">
                <div class="oxi-addons-content-div">
                    <div class="oxi-head">
                        General Settings
                    </div>
                    <?php
                    $this->oxilab_flip_box_admin_color('backend-background-color', $this->style[17], 'rgba', 'Background Color', 'Set your Backend Background Color', 'background-color', '.oxilab-flip-box-back-' . $this->oxiid . '');
                    $this->oxilab_flip_box_admin_color('backend-border-color', $this->style[19], '', 'Border Color', 'Set your Border Color', 'border-color', '.oxilab-flip-box-back-' . $this->oxiid . '');
                    $this->oxilab_flip_box_admin_border('flip-backend-border-size', $this->style[131], 'flip-backend-border-style', $this->style[133], 'Border Size', 'Set your backend border size with different style');
                    $this->oxilab_flip_box_admin_number_double('backend-padding-top', $this->style[101], 'backend-padding-left', $this->style[103], 'Padding', 'Set your Backend Padding as Top Bottom and Left Right');
                    ?>
                </div>
                <div class="oxi-addons-content-div">
                    <div class="oxi-head">
                        Heading Settings
                    </div>
                    <?php
                    $this->oxilab_flip_box_admin_number('backend-heading-size', $this->style[157], '1', 'Font Size', 'Set your backend Heading Font Size');
                    $this->oxilab_flip_box_admin_color('backend-title-color', $this->style[21], '', 'Title Color', 'Set your Backend title Color', 'color', '.oxilab-flip-box-back-' . $this->oxiid . '-data .oxilab-heading');
                    $this->oxilab_flip_box_admin_font_family('backend-heading-family', $this->style[159], 'Font Family', 'Give your Prepared Font from our Google Font List');
                    $this->oxilab_flip_box_admin_font_style('backend-heading-style', $this->style[161], 'Font Style', 'Set your Heading Font Style');
                    $this->oxilab_flip_box_admin_font_weight('backend-heading-weight', $this->style[163], 'Font Weight', 'Give your backend Heading Font Weight');
                    $this->oxilab_flip_box_admin_text_align('backend-heading-text-align', $this->style[165], 'Text Align', 'Give your Heading Text Align');
                    $this->oxilab_flip_box_admin_number_double('backend-heading-padding-top', $this->style[167], 'backend-heading-padding-bottom', $this->style[169], 'Padding Top Bottom', 'Set Your backend Heading  Padding Top and Bottom');
                    $this->oxilab_flip_box_admin_number_double('backend-heading-padding-left', $this->style[171], 'backend-heading-padding-right', $this->style[173], 'Padding Left Right', 'Set Your backend Heading  Padding Left and Right');
                    ?>
                </div>
            </div>
            <div class="oxi-addons-col-6">
                <div class="oxi-addons-content-div">
                    <div class="oxi-head">
                        Backend Title Border
                    </div>
                    <?php
                    $this->oxilab_flip_box_admin_number('backend-title-border-width', $this->style[175], '1', 'Border Width', 'Set your Backend Title Bottom Border Width');
                    $this->oxilab_flip_box_admin_number('backend-title-border-height', $this->style[177], '1', 'Border Height', 'Set your Backend Title Bottom Border Height');
                    $this->oxilab_flip_box_admin_color('backend-title-bottom-border-color', $this->style[23], '', 'Title Bottom Color', 'Set your Backend Title Bottom Border Color', 'background-color', '.oxilab-flip-box-back-' . $this->oxiid . '-data .oxilab-heading .oxilab-span');
                    ?>
                </div>
                <div class="oxi-addons-content-div">
                    <div class="oxi-head">
                        Backend Info
                    </div>
                    <?php
                    $this->oxilab_flip_box_admin_number('backend-info-size', $this->style[107], '1', 'Font Size', 'Set your Backend Info Font Size');
                    $this->oxilab_flip_box_admin_color('backend-info-color', $this->style[25], '', 'Text Color', 'Set your Backend Info Color', 'color', '.oxilab-flip-box-back-' . $this->oxiid . '-data .oxilab-info');
                    $this->oxilab_flip_box_admin_font_family('backend-info-family', $this->style[109], 'Font Family', 'Give your Prepared Font from our Google Font List');
                    $this->oxilab_flip_box_admin_font_style('backend-info-style', $this->style[111], 'Font Style', 'Set your Backend Info Font Style');
                    $this->oxilab_flip_box_admin_font_weight('backend-info-weight', $this->style[113], 'Font Weight', 'Give your Backend Info Font Weight');
                    $this->oxilab_flip_box_admin_text_align('backend-info-text-align', $this->style[115], 'Text Align', 'Give your Backend Info Text Align');
                    $this->oxilab_flip_box_admin_number_double('backend-info-padding-top', $this->style[117], 'backend-info-padding-bottom', $this->style[119], 'Padding Top Bottom', 'Set Your Backend Info  Padding Top and Bottom');
                    $this->oxilab_flip_box_admin_number_double('backend-info-padding-left', $this->style[121], 'backend-info-padding-right', $this->style[123], 'Padding Left Right', 'Set Your Backend Info  Padding Left and Right');
                    ?>
                </div>
            </div>
        </div>
        <div class="oxi-addons-tabs-content-tabs" id="oxilab-tabs-id-2">
            <div class="col-xs-12 p-2">
                <div class="form-group">
                    <label for="custom-css" class="custom-css">Custom CSS:</label>
                    <textarea class="form-control" rows="4" id="custom-css" name="custom-css"><?php echo esc_html($this->style[179]); ?></textarea>
                    <small class="form-text text-muted">Add Your Custom CSS Unless make it blank.</small>
                </div>
            </div>
        </div>
        <div class="oxi-addons-tabs-content-tabs" id="oxilab-tabs-id-1">
            <?php
            $this->oxilab_flip_box_admin_support();
            ?>
        </div>
        <?php
    }

    public function Rearrange() {
        return ['tag' => 'title', 'id' => 1];
    }

    public function register_style() {
        $data = 'oxilab-flip-type |' . sanitize_text_field($_POST['oxilab-flip-type']) . '|'
                . ' oxilab-flip-effects |' . sanitize_text_field($_POST['oxilab-flip-effects']) . '|'
                . ' front-background-color |' . sanitize_text_field($_POST['front-background-color']) . '|'
                . ' front-border-color |' . sanitize_hex_color($_POST['front-border-color']) . '| '
                . ' front-icon-color |' . sanitize_hex_color($_POST['front-icon-color']) . '|'
                . ' front-icon-background |' . sanitize_text_field($_POST['front-icon-background']) . '|'
                . ' front-heading-color |' . sanitize_hex_color($_POST['front-heading-color']) . '|'
                . ' front-info-color |' . sanitize_hex_color($_POST['front-info-color']) . '|'
                . ' backend-background-color |' . sanitize_text_field($_POST['backend-background-color']) . '|'
                . ' backend-border-color |' . sanitize_hex_color($_POST['backend-border-color']) . '|'
                . ' backend-title-color |' . sanitize_hex_color($_POST['backend-title-color']) . '|'
                . ' backend-title-bottom-border-color |' . sanitize_text_field($_POST['backend-title-bottom-border-color']) . '|'
                . ' backend-info-color |' . sanitize_hex_color($_POST['backend-info-color']) . '|'
                . ' ||'
                . ' ||'
                . ' ||'
                . ' ||'
                . ' ||'
                . ' ||'
                . ' ||'
                . ' ||'
                . ' flip-col |' . sanitize_text_field($_POST['flip-col']) . '|'
                . ' flip-width |' . sanitize_text_field($_POST['flip-width']) . '|'
                . ' flip-height |' . sanitize_text_field($_POST['flip-height']) . '|'
                . ' margin-top |' . sanitize_text_field($_POST['margin-top']) . '|'
                . ' margin-left |' . sanitize_text_field($_POST['margin-left']) . '|'
                . ' flip-open-tabs |' . sanitize_text_field($_POST['flip-open-tabs']) . '|'
                . ' oxilab-animation |' . sanitize_text_field($_POST['oxilab-animation']) . '|'
                . ' animation-duration |' . sanitize_text_field($_POST['animation-duration']) . '|'
                . ' flip-boxshow-color |' . sanitize_text_field($_POST['flip-boxshow-color']) . '|'
                . ' flip-boxshow-horizontal |' . sanitize_text_field($_POST['flip-boxshow-horizontal']) . '|'
                . ' flip-boxshow-vertical |' . sanitize_text_field($_POST['flip-boxshow-vertical']) . '|'
                . ' flip-boxshow-blur |' . sanitize_text_field($_POST['flip-boxshow-blur']) . '|'
                . ' flip-boxshow-spread |' . sanitize_text_field($_POST['flip-boxshow-spread']) . '|'
                . '  ||'
                . ' front-padding-top |' . sanitize_text_field($_POST['front-padding-top']) . '|'
                . ' front-padding-left |' . sanitize_text_field($_POST['front-padding-left']) . '|'
                . ' front-icon-height|' . sanitize_text_field($_POST['front-icon-height']) . '|'
                . ' front-icon-size |' . sanitize_text_field($_POST['front-icon-size']) . '|'
                . ' front-icon-width |' . sanitize_text_field($_POST['front-icon-width']) . '|'
                . ' front-icon-border-radius |' . sanitize_text_field($_POST['front-icon-border-radius']) . '|'
                . ' front-heading-size |' . sanitize_text_field($_POST['front-heading-size']) . '|'
                . ' front-heading-family |' . sanitize_text_field($_POST['front-heading-family']) . '|'
                . ' front-heding-style |' . sanitize_text_field($_POST['front-heding-style']) . '|'
                . ' front-heding-weight |' . sanitize_text_field($_POST['front-heding-weight']) . '|'
                . ' front-heding-text-align |' . sanitize_text_field($_POST['front-heding-text-align']) . '|'
                . ' front-heding-padding-top |' . sanitize_text_field($_POST['front-heding-padding-top']) . '|'
                . ' front-heding-padding-bottom |' . sanitize_text_field($_POST['front-heding-padding-bottom']) . '|'
                . ' front-heding-padding-left |' . sanitize_text_field($_POST['front-heding-padding-left']) . '|'
                . ' front-heding-padding-right |' . sanitize_text_field($_POST['front-heding-padding-right']) . '|'
                . ' backend-padding-top |' . sanitize_text_field($_POST['backend-padding-top']) . '|'
                . ' backend-padding-left |' . sanitize_text_field($_POST['backend-padding-left']) . '|'
                . ' ||'
                . ' backend-info-size |' . sanitize_text_field($_POST['backend-info-size']) . '|'
                . ' backend-info-family |' . sanitize_text_field($_POST['backend-info-family']) . '|'
                . ' backend-info-style |' . sanitize_text_field($_POST['backend-info-style']) . '|'
                . ' backend-info-weight |' . sanitize_text_field($_POST['backend-info-weight']) . '|'
                . ' backend-info-text-align |' . sanitize_text_field($_POST['backend-info-text-align']) . '|'
                . ' backend-info-padding-top |' . sanitize_text_field($_POST['backend-info-padding-top']) . '|'
                . ' backend-info-padding-bottom |' . sanitize_text_field($_POST['backend-info-padding-bottom']) . '|'
                . ' backend-info-padding-left |' . sanitize_text_field($_POST['backend-info-padding-left']) . '|'
                . ' backend-info-padding-right |' . sanitize_text_field($_POST['backend-info-padding-right']) . '|'
                . ' flip-col-border-size |' . sanitize_text_field($_POST['flip-col-border-size']) . '|'
                . ' flip-col-border-style |' . sanitize_text_field($_POST['flip-col-border-style']) . '|'
                . ' flip-border-radius |' . sanitize_text_field($_POST['flip-border-radius']) . '|'
                . ' flip-backend-border-size |' . sanitize_text_field($_POST['flip-backend-border-size']) . '|'
                . ' flip-backend-border-style |' . sanitize_text_field($_POST['flip-backend-border-style']) . '|'
                . '||'
                . '||'
                . ' front-info-size |' . sanitize_text_field($_POST['front-info-size']) . '|'
                . ' front-info-family |' . sanitize_text_field($_POST['front-info-family']) . '|'
                . ' front-info-style |' . sanitize_text_field($_POST['front-info-style']) . '|'
                . ' front-info-weight |' . sanitize_text_field($_POST['front-info-weight']) . '|'
                . ' front-info-text-align |' . sanitize_text_field($_POST['front-info-text-align']) . '|'
                . ' front-info-padding-top |' . sanitize_text_field($_POST['front-info-padding-top']) . '|'
                . ' front-info-padding-bottom |' . sanitize_text_field($_POST['front-info-padding-bottom']) . '|'
                . ' front-info-padding-left |' . sanitize_text_field($_POST['front-info-padding-left']) . '|'
                . ' front-info-padding-right |' . sanitize_text_field($_POST['front-info-padding-right']) . '|'
                . ' backend-heading-size |' . sanitize_text_field($_POST['backend-heading-size']) . '|'
                . ' backend-heading-family |' . sanitize_text_field($_POST['backend-heading-family']) . '|'
                . ' backend-heading-style |' . sanitize_text_field($_POST['backend-heading-style']) . '|'
                . ' backend-heading-weight |' . sanitize_text_field($_POST['backend-heading-weight']) . '|'
                . ' backend-heading-text-align |' . sanitize_text_field($_POST['backend-heading-text-align']) . '|'
                . ' backend-heading-padding-top |' . sanitize_text_field($_POST['backend-heading-padding-top']) . '|'
                . ' backend-heading-padding-bottom |' . sanitize_text_field($_POST['backend-heading-padding-bottom']) . '|'
                . ' backend-heading-padding-left |' . sanitize_text_field($_POST['backend-heading-padding-left']) . '|'
                . ' backend-heading-padding-right |' . sanitize_text_field($_POST['backend-heading-padding-right']) . '|'
                . ' backend-title-border-width |' . sanitize_text_field($_POST['backend-title-border-width']) . '|'
                . ' backend-title-border-height |' . sanitize_text_field($_POST['backend-title-border-height']) . '|'
                . ' custom-css |' . sanitize_text_field($_POST['custom-css']) . '|'
                . '|';
        return $data;
    }

    public function modal_form_data() {
        ?>
        <div class="modal-header">
            <h5 class="modal-title">Front Settings</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body row">
            <?php
            $this->oxilab_flip_box_admin_input_text('flip-box-front-title', $this->child_editable[1], 'Front Title', 'Add your flip front title.');
            $this->oxilab_flip_box_admin_input_text_area('flip-box-font-desc', $this->child_editable[15], 'Font Info:', 'Add font Info text unless make it blank.');
            $this->oxilab_flip_box_admin_input_icon('flip-box-front-icons', $this->child_editable[3], 'Front Icon', 'Add your front icon, Use Font-Awesome class name. As example fab fa-facebook');
            $this->image_upload('flip-box-image-upload-url-01', $this->child_editable[5], 'Front Image', 'Add or modify your front image.');
            ?>
        </div>
        <div class="modal-header">
            <h5 class="modal-title">Backend Settings</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body row">
            <?php
            $this->oxilab_flip_box_admin_input_text('flip-box-backend-title', $this->child_editable[17], 'Backend Title', 'Add your flip backend title.');
            $this->oxilab_flip_box_admin_input_text_area('flip-box-backend-desc', $this->child_editable[7], 'Backend Info:', 'Add backend Info text unless make it blank.');
            $this->oxilab_flip_box_admin_input_text('flip-box-backend-link', $this->child_editable[11], 'Link', 'Add your desire link or url unless make it blank');
            $this->image_upload('flip-box-image-upload-url-02', $this->child_editable[13], 'Backend Background Image', 'Add or Modify Your Backend Background Image. Unless make it blank.');
            ?>
        </div>
        <?php
    }

    public function register_child() {

        $data = ' flip-box-front-title {#}|{#}' . $this->admin_special_charecter($_POST['flip-box-front-title']) . '{#}|{#}'
                . ' flip-box-front-icons {#}|{#}' . sanitize_text_field($_POST['flip-box-front-icons']) . '{#}|{#}'
                . ' flip-box-image-upload-url-01 {#}|{#}' . sanitize_url($_POST['flip-box-image-upload-url-01']) . '{#}|{#}'
                . ' flip-box-backend-desc {#}|{#}' . $this->admin_special_charecter($_POST['flip-box-backend-desc']) . '{#}|{#}'
                . ' {#}|{#}{#}|{#}'
                . ' flip-box-backend-link {#}|{#}' . sanitize_url($_POST['flip-box-backend-link']) . '{#}|{#}'
                . ' flip-box-image-upload-url-02 {#}|{#}' . sanitize_url($_POST['flip-box-image-upload-url-02']) . '{#}|{#}'
                . ' flip-box-font-desc {#}|{#}' . $this->admin_special_charecter($_POST['flip-box-font-desc']) . '{#}|{#}'
                . ' flip-box-backend-title {#}|{#}' . $this->admin_special_charecter($_POST['flip-box-backend-title']) . '{#}|{#}';
        return $data;
    }
}
