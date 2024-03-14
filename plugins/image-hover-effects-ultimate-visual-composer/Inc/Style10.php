<?php

namespace OXI_FLIP_BOX_PLUGINS\Inc;

/**
 * Description of Style1
 *
 * @author biplo
 */

use OXI_FLIP_BOX_PLUGINS\Page\Admin_Render;

class Style10 extends Admin_Render
{
    public function Rearrange()
    {
        return ['tag' => 'title', 'id' => 1];
    }

    public function register_style()
    {
        $data = 'oxilab-flip-type |' . sanitize_text_field($_POST['oxilab-flip-type']) . '|'
            . ' oxilab-flip-effects |' . sanitize_text_field($_POST['oxilab-flip-effects']) . '|'
            . ' front-background-color |' . sanitize_text_field($_POST['front-background-color']) . '|'
            . ' front-icon-color |' . sanitize_hex_color($_POST['front-icon-color']) . '|'
            . ' front-icon-background-color|' . sanitize_text_field($_POST['front-icon-background-color']) . '|'
            . ' front-heading-color |' . sanitize_hex_color($_POST['front-heading-color']) . '|'
            . ' backend-background-color |' . sanitize_text_field($_POST['backend-background-color']) . '|'
            . ' backend-icon-color|' . sanitize_hex_color($_POST['backend-icon-color']) . '|'
            . ' backend-info-color |' . sanitize_hex_color($_POST['backend-info-color']) . '|'
            . ' ||'
            . ' ||'
            . ' ||'
            . ' ||'
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
            . ' flip-border-radius|' . sanitize_text_field($_POST['flip-border-radius']) . '|'
            . ' front-padding-top |' . sanitize_text_field($_POST['front-padding-top']) . '|'
            . ' front-padding-left |' . sanitize_text_field($_POST['front-padding-left']) . '|'
            . ' ||'
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
            . ' backend-icon-size |' . sanitize_text_field($_POST['backend-icon-size']) . '|'
            . ' backend-icon-width |' . sanitize_text_field($_POST['backend-icon-width']) . '|'
            . ' backend-icon-padding-top-bottom |' . sanitize_text_field($_POST['backend-icon-padding-top-bottom']) . '|'
            . ' backend-icon-padding-left-right |' . sanitize_text_field($_POST['backend-icon-padding-left-right']) . '|'
            . ' front-icon-padding-top-bottom |' . sanitize_text_field($_POST['front-icon-padding-top-bottom']) . '|'
            . ' front-icon-padding-left-right |' . sanitize_text_field($_POST['front-icon-padding-left-right']) . '|'
            . ' custom-css |' . sanitize_text_field($_POST['custom-css']) . '|'
            . '|';
        return $data;
    }

    public function register_child()
    {
        $data = ' flip-box-front-title {#}|{#}' . $this->admin_special_charecter($_POST['flip-box-front-title']) . '{#}|{#}'
            . ' flip-box-front-icons {#}|{#}' . sanitize_text_field($_POST['flip-box-front-icons']) . '{#}|{#}'
            . ' flip-box-image-upload-url-01 {#}|{#}' . sanitize_url($_POST['flip-box-image-upload-url-01']) . '{#}|{#}'
            . ' flip-box-backend-desc {#}|{#}' . $this->admin_special_charecter($_POST['flip-box-backend-desc']) . '{#}|{#}'
            . ' flip-box-backend-icons{#}|{#}' . sanitize_text_field($_POST['flip-box-backend-icons']) . '{#}|{#}'
            . ' flip-box-backend-link {#}|{#}' . sanitize_url($_POST['flip-box-backend-link']) . '{#}|{#}'
            . ' flip-box-image-upload-url-02 {#}|{#}' . sanitize_url($_POST['flip-box-image-upload-url-02']) . '{#}|{#}';
        return $data;
    }

    public function register_controls()
    {
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
                    $this->oxilab_flip_box_admin_number('flip-border-radius', $this->style[69], '1', 'Border Radius', 'Set your flip Border Radius');
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
                    $this->oxilab_flip_box_admin_color('front-background-color', $this->style[5], 'rgba', 'Background Color', 'Set your Front Background Color', ' background-color', '.oxilab-flip-box-' . $this->oxiid . '');
                    $this->oxilab_flip_box_admin_number_double('front-padding-top', $this->style[71], 'front-padding-left', $this->style[73], 'Padding', 'Set your Front Padding as Top Bottom and Left Right');
                    ?>
                </div>
                <div class="oxi-addons-content-div">
                    <div class="oxi-head">
                        Icon Settings
                    </div>
                    <?php
                    $this->oxilab_flip_box_admin_number('front-icon-size', $this->style[77], '1', 'Icon Size', 'Set your Icon Font Size');
                    $this->oxilab_flip_box_admin_color('front-icon-color', $this->style[7], '', 'Icon Color', 'Set your Icon Color', 'color', '.oxilab-flip-box-' . $this->oxiid . '-data .oxilab-icon-data [class^=\'fa\']');
                    $this->oxilab_flip_box_admin_color('front-icon-background-color', $this->style[9], '', 'Icon Background Color', 'Set your Icon Background Color', 'background-color', '.oxilab-flip-box-' . $this->oxiid . '-data .oxilab-icon-data');
                    $this->oxilab_flip_box_admin_number('front-icon-width', $this->style[79], '1', 'Icon width', 'Set your Icon Width and Height Size.');
                    $this->oxilab_flip_box_admin_number('front-icon-border-radius', $this->style[81], '1', 'Border Radius', 'Set Your Icon Border Radius');
                    $this->oxilab_flip_box_admin_number_double('front-icon-padding-top-bottom', $this->style[133], 'front-icon-padding-left-right', $this->style[135], 'Icon Padding', 'Set your Icon Padding as Top Bottom and Left Right');
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
                    $this->oxilab_flip_box_admin_color('front-heading-color', $this->style[11], '', 'Heading Color', 'Set your Front Heading Color', 'color', '.oxilab-flip-box-' . $this->oxiid . '-data .oxilab-heading');
                    $this->oxilab_flip_box_admin_font_family('front-heading-family', $this->style[85], 'Font Family', 'Give your Prepared Font from our Google Font List');
                    $this->oxilab_flip_box_admin_font_style('front-heding-style', $this->style[87], 'Font Style', 'Set your Heading Font Style');
                    $this->oxilab_flip_box_admin_font_weight('front-heding-weight', $this->style[89], 'Font Weight', 'Give your Front Heading Font Weight');
                    $this->oxilab_flip_box_admin_text_align('front-heding-text-align', $this->style[91], 'Text Align', 'Give your Heading Text Align');
                    $this->oxilab_flip_box_admin_number_double('front-heding-padding-top', $this->style[93], 'front-heding-padding-bottom', $this->style[95], 'Padding Top Bottom', 'Set Your Heading  Padding Top and Bottom');
                    $this->oxilab_flip_box_admin_number_double('front-heding-padding-left', $this->style[97], 'front-heding-padding-right', $this->style[99], 'Padding Left Right', 'Set Your Heading  Padding Left and Right');
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
                    $this->oxilab_flip_box_admin_color('backend-background-color', $this->style[13], 'rgba', 'Background Color', 'Set your Backend Background Color', 'background-color', '.oxilab-flip-box-back-' . $this->oxiid . '');
                    $this->oxilab_flip_box_admin_number_double('backend-padding-top', $this->style[101], 'backend-padding-left', $this->style[103], 'Padding', 'Set your Backend Padding as Top Bottom and Left Right');
                    ?>
                </div>
                <div class="oxi-addons-content-div">
                    <div class="oxi-head">
                        Icon Settings
                    </div>
                    <?php
                    $this->oxilab_flip_box_admin_number('backend-icon-size', $this->style[77], '1', 'Icon Size', 'Set your Icon Font Size');
                    $this->oxilab_flip_box_admin_color('backend-icon-color', $this->style[15], '', 'Icon Color', 'Set your Icon Color', 'color', '.oxilab-flip-box-back-' . $this->oxiid . '-data .oxilab-icon-data [class^=\'fa\']');
                    $this->oxilab_flip_box_admin_number('backend-icon-width', $this->style[79], '1', 'Icon width', 'Set your Icon Width and Height Size.');
                    $this->oxilab_flip_box_admin_number_double('backend-icon-padding-top-bottom', $this->style[129], 'backend-icon-padding-left-right', $this->style[131], 'Icon Padding', 'Set your Icon Padding as Top Bottom and Left Right');
                    ?>
                </div>
            </div>
            <div class="oxi-addons-col-6">
                <div class="oxi-addons-content-div">
                    <div class="oxi-head">
                        Backend Info
                    </div>
                    <?php
                    $this->oxilab_flip_box_admin_number('backend-info-size', $this->style[107], '1', 'Font Size', 'Set your Backend Info Font Size');
                    $this->oxilab_flip_box_admin_color('backend-info-color', $this->style[17], '', 'Text Color', 'Set your Backend Info Color', 'color', '.oxilab-flip-box-back-' . $this->oxiid . '-data .oxilab-info');
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
                    <textarea class="form-control" rows="4" id="custom-css" name="custom-css"><?php echo esc_html($this->style[137]); ?></textarea>
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


    public function modal_form_data()
    {
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
            $this->oxilab_flip_box_admin_input_icon('flip-box-front-icons', $this->child_editable[3], 'Front Icon', 'Add your front icon, Use Font-Awesome class name. As example fab fa-facebook-f');
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
            <?Php
            $this->oxilab_flip_box_admin_input_icon('flip-box-backend-icons', $this->child_editable[9], 'Backend Icon', 'Add your Backend icon, Use Font-Awesome class name. As example fab fa-facebook-f');
            $this->oxilab_flip_box_admin_input_text_area('flip-box-backend-desc', $this->child_editable[7], 'Backend Info:', 'Add backend Info text unless make it blank.');
            $this->oxilab_flip_box_admin_input_text('flip-box-backend-link', $this->child_editable[11], 'Link', 'Add your desire link or url unless make it blank');
            $this->image_upload('flip-box-image-upload-url-02', $this->child_editable[13], 'Backend Background Image', 'Add or Modify Your Backend Background Image. Unless make it blank.');
            ?>
        </div>
<?php
    }
}
