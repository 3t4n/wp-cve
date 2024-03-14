<?php

namespace OXI_FLIP_BOX_PLUGINS\Inc;

/**
 * Description of Style1
 *
 * @author biplo
 */

use OXI_FLIP_BOX_PLUGINS\Page\Admin_Render;

class Style8 extends Admin_Render
{



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
            $this->oxilab_flip_box_admin_input_text('flip-box-front-title', $this->child_editable[11], 'Rearrange Title', 'Add your flip Rearrange  title.');
            $this->oxilab_flip_box_admin_input_icon('flip-box-front-icons', $this->child_editable[7], 'Front Icon', 'Add your front icon, Use Font-Awesome class name. As example fab fa-facebook');
            $this->image_upload('flip-box-image-upload-url-01', $this->child_editable[1], 'Front Image', 'Add or modify your front image.');
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
            $this->oxilab_flip_box_admin_input_icon('flip-box-backend-icons', $this->child_editable[9], 'Backend Icon', 'Add your front icon, Use Font-Awesome class name. As example fab fa-facebook');
            $this->oxilab_flip_box_admin_input_text('flip-box-backend-link', $this->child_editable[3], 'Link', 'Add your desire link or url unless make it blank');
            $this->image_upload('flip-box-image-upload-url-02', $this->child_editable[5], 'Backend Background Image', 'Add or Modify Your Backend Background Image. Unless make it blank.');
            ?>
        </div>
    <?php
    }

    public function Rearrange()
    {
        return ['tag' => 'title', 'id' => 11];
    }
    public function register_child()
    {
        $data = ' flip-box-image-upload-url-01 {#}|{#}' . sanitize_url($_POST['flip-box-image-upload-url-01']) . '{#}|{#}'
            . ' flip-box-backend-link {#}|{#}' . sanitize_url($_POST['flip-box-backend-link']) . '{#}|{#}'
            . ' flip-box-image-upload-url-02 {#}|{#}' . sanitize_url($_POST['flip-box-image-upload-url-02']) . '{#}|{#}'
            . ' flip-box-front-icons {#}|{#}' . sanitize_text_field($_POST['flip-box-front-icons']) . '{#}|{#}'
            . ' flip-box-backend-icons {#}|{#}' . sanitize_text_field($_POST['flip-box-backend-icons']) . '{#}|{#}'
            . ' flip-box-front-title {#}|{#}' . sanitize_text_field($_POST['flip-box-front-title']) . '{#}|{#}';
        return $data;
    }

    public function register_style()
    {
        $data = 'oxilab-flip-type |' . sanitize_text_field($_POST['oxilab-flip-type']) . '|'
            . ' oxilab-flip-effects |' . sanitize_text_field($_POST['oxilab-flip-effects']) . '|'
            . ' front-background-color |' . sanitize_text_field($_POST['front-background-color']) . '|'
            . ' front-border-color |' . sanitize_hex_color($_POST['front-border-color']) . '| '
            . ' backend-background-color |' . sanitize_text_field($_POST['backend-background-color']) . '|'
            . ' backend-border-color |' . sanitize_hex_color($_POST['backend-border-color']) . '|'
            . ' front-icon-color |' . sanitize_hex_color($_POST['front-icon-color']) . '|'
            . ' backend-icon-color |' . sanitize_hex_color($_POST['backend-icon-color']) . '|'
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
            . ' flip-font-border-size|' . sanitize_text_field($_POST['flip-font-border-size']) . '|'
            . ' flip-font-border-style|' . sanitize_text_field($_POST['flip-font-border-style']) . '|'
            . ' flip-backend-border-size|' . sanitize_text_field($_POST['flip-backend-border-size']) . '|'
            . ' flip-backend-border-style|' . sanitize_text_field($_POST['flip-backend-border-style']) . '|'
            . ' front-icon-size |' . sanitize_text_field($_POST['front-icon-size']) . '|'
            . ' front-icon-width |' . sanitize_text_field($_POST['front-icon-width']) . '|'
            . ' front-icon-padding-top-bottom |' . sanitize_text_field($_POST['front-icon-padding-top-bottom']) . '|'
            . ' front-icon-padding-left-right |' . sanitize_text_field($_POST['front-icon-padding-left-right']) . '|'
            . ' backend-icon-size |' . sanitize_text_field($_POST['backend-icon-size']) . '|'
            . ' backend-icon-width |' . sanitize_text_field($_POST['backend-icon-width']) . '|'
            . ' backend-icon-padding-top-bottom |' . sanitize_text_field($_POST['backend-icon-padding-top-bottom']) . '|'
            . ' backend-icon-padding-left-right |' . sanitize_text_field($_POST['backend-icon-padding-left-right']) . '|'
            . ' flip-border-radius |' . sanitize_text_field($_POST['flip-border-radius']) . '|'
            . ' custom-css |' . sanitize_text_field($_POST['custom-css']) . '|'
            . '|';
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
                    $this->oxilab_flip_box_admin_number('flip-border-radius', $this->style[93], '1', 'Border Radius', 'Set your flip Border Radius');
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
                    $this->oxilab_flip_box_admin_border('flip-font-border-size', $this->style[69], 'flip-font-border-style', $this->style[71], 'Border Size', 'Set your front border size with different style');
                    ?>
                </div>
            </div>
            <div class="oxi-addons-col-6">
                <div class="oxi-addons-content-div">
                    <div class="oxi-head">
                        Icon Settings
                    </div>
                    <?php
                    $this->oxilab_flip_box_admin_number('front-icon-size', $this->style[77], '1', 'Icon Size', 'Set your Icon Font Size');
                    $this->oxilab_flip_box_admin_color('front-icon-color', $this->style[13], '', 'Icon Color', 'Set your Icon Color', 'color', '.oxilab-flip-box-' . $this->oxiid . '-data .oxilab-icon-data [class^=\'fa\']');
                    $this->oxilab_flip_box_admin_number('front-icon-width', $this->style[79], '1', 'Icon width', 'Set your Icon Width and Height Size.');
                    $this->oxilab_flip_box_admin_number_double('front-icon-padding-top-bottom', $this->style[81], 'front-icon-padding-left-right', $this->style[83], 'Icon Padding', 'Set your Icon Padding as Top Bottom and Left Right');
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
                    $this->oxilab_flip_box_admin_color('backend-background-color', $this->style[9], 'rgba', 'Background Color', 'Set your Backend Background Color', 'background-color', '.oxilab-flip-box-back-' . $this->oxiid . '');
                    $this->oxilab_flip_box_admin_color('backend-border-color', $this->style[11], '', 'Border Color', 'Set your Border Color', 'border-color', '.oxilab-flip-box-back-' . $this->oxiid . '');
                    $this->oxilab_flip_box_admin_border('flip-backend-border-size', $this->style[73], 'flip-backend-border-style', $this->style[75], 'Border Size', 'Set your front border size with different style');
                    ?>
                </div>
            </div>
            <div class="oxi-addons-col-6">
                <div class="oxi-addons-content-div">
                    <div class="oxi-head">
                        Icon Settings
                    </div>
                    <?php
                    $this->oxilab_flip_box_admin_number('backend-icon-size', $this->style[85], '1', 'Icon Size', 'Set your Icon Font Size');
                    $this->oxilab_flip_box_admin_color('backend-icon-color', $this->style[15], '', 'Icon Color', 'Set your Icon Color', 'color', '.oxilab-flip-box-back-' . $this->oxiid . ' .oxilab-icon-data [class^=\'fa\']');
                    $this->oxilab_flip_box_admin_number('backend-icon-width', $this->style[87], '1', 'Icon width', 'Set your Icon Width and Height Size.');
                    $this->oxilab_flip_box_admin_number_double('backend-icon-padding-top-bottom', $this->style[89], 'backend-icon-padding-left-right', $this->style[91], 'Icon Padding', 'Set your Icon Padding as Top Bottom and Left Right');
                    ?>
                </div>
            </div>
        </div>
        <div class="oxi-addons-tabs-content-tabs" id="oxilab-tabs-id-2">
            <div class="col-xs-12 p-2">
                <div class="form-group">
                    <label for="custom-css" class="custom-css">Custom CSS:</label>
                    <textarea class="form-control" rows="4" id="custom-css" name="custom-css"><?php echo esc_html($this->style[95]); ?></textarea>
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
}
