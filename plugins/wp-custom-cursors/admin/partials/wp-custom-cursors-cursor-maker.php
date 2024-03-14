<?php
    /**
	* Cursor Maker
	* Make cursors page
	* php version 7.2
	*
	* @category   Plugin
	* @package    Wp_Custom_Cursors
	* @subpackage Wp_Custom_Cursors/includes
	* @author     Hamid Reza Sepehr <hamidsepehr4@gmail.com>
	* @license    GPLv3 (https://www.gnu.org/licenses/gpl-3.0.html)
	* @link       https://hamidrezasepehr.com/
	* @since      2.1.0
    */

    // Initialization.
    $normal_hover_state                   = 'normal';
    $fe_width_value                       = 7;
    $fe_height_value                      = 7;
    $fe_color_value                       = '#252525';
    $fe_radius_value                      = 100;
    $fe_border_value                      = 0;
    $fe_border_color_value                = '#252525';
    $fe_duration_value                    = 0;
    $fe_timing_value                      = 'ease-out';
    $fe_blending_value                    = 'normal';
    $fe_velocity_value                    = 'none';
    $fe_zindex_value                      = 101;
    $fe_backdrop_value                    = 'none';
    $fe_backdrop_amount_value             = '';
    $se_width_value                       = 45;
    $se_height_value                      = 45;
    $se_color_value                       = 'transparent';
    $se_radius_value                      = 100;
    $se_border_value                      = 1;
    $se_border_color_value                = '#252525';
    $se_duration_value                    = 300;
    $se_timing_value                      = 'ease-out';
    $se_blending_value                    = 'normal';
    $se_velocity_value                    = 'normal';
    $se_zindex_value                      = 100;
    $se_backdrop_value                    = 'none';
    $se_backdrop_amount_value             = '';
    $hover_fe_width_value                 = 7;
    $hover_fe_height_value                = 7;
    $hover_fe_color_value                 = '#252525';
    $hover_fe_radius_value                = 100;
    $hover_fe_border_value                = 0;
    $hover_fe_border_color_value          = '#252525';
    $hover_fe_duration_value              = 0;
    $hover_fe_timing_value                = 'ease-out';
    $hover_fe_blending_value              = 'normal';
    $hover_fe_velocity_value              = 'none';
    $hover_fe_zindex_value                = 101;
    $hover_fe_backdrop_value              = 'none';
    $hover_fe_backdrop_amount_value       = '';
    $hover_se_width_value                 = 45;
    $hover_se_height_value                = 45;
    $hover_se_color_value                 = 'transparent';
    $hover_se_radius_value                = 100;
    $hover_se_border_value                = 1;
    $hover_se_border_color_value          = '#252525';
    $hover_se_duration_value              = 300;
    $hover_se_timing_value                = 'ease-out';
    $hover_se_blending_value              = 'normal';
    $hover_se_velocity_value              = 'none';
    $hover_se_zindex_value                = 100;
    $hover_se_backdrop_value              = 'none';
    $hover_se_backdrop_amount_value       = '';
    $image_url_value                      = null;
    $image_width_value                    = 100;
    $image_height_value                   = null;
    $image_background_value               = 'off';
    $image_background_color_value         = 'transparent';
    $image_background_radius_value        = 0;
    $image_background_padding_value       = 0;
    $image_blending_value                 = 'normal';
    $click_point_value                    = '0,0';
    $hover_image_url_value                = null;
    $hover_image_width_value              = 100;
    $hover_image_height_value             = null;
    $hover_image_background_value         = 'off';
    $hover_image_background_color_value   = 'transparent';
    $hover_image_background_radius_value  = 0;
    $hover_image_background_padding_value = 0;
    $hover_image_blending_value           = 'normal';
    $hover_click_point_value              = '0,0';
    $hover_trigger_link_value             = 'off';
    $hover_trigger_button_value           = 'off';
    $hover_trigger_image_value            = 'off';
    $hover_trigger_custom_value           = 'off';
    $hover_trigger_selector_value         = '';
    $cursor_text_value                    = esc_html__('WP Custom Cursor - Circular Cursor', 'wpcustom-cursors');
    $show_dot_value                       = 'on';
    $dot_width_value                      = 10;
    $dot_color_value                      = '#252500';
    $text_color_value                     = '#252525';
    $text_transform_value                 = 'uppercase';
    $font_size_value                      = 55;
    $font_weight_value                    = 'bold';
    $text_width_value                     = 150;
    $word_spacing_value                   = 30;
    $text_animation_value                 = 'spinright';
    $text_animation_duration_value        = 10;
    $text_type_value                      = 'text';
    $hr_text_value                        = esc_html__('View', 'wpcustom-cursors');
    $hr_backdrop_value                    = 'blur';
    $hr_backdrop_amount_value             = '2px';
    $hr_background_value                  = 'rgba(0, 0, 0, 0.150)';
    $hr_radius_value                      = 100;
    $hr_padding_value                     = 0;
    $hr_width_value                       = 100;
    $hr_size_value                        = 18;
    $hr_transform_value                   = 'uppercase';
    $hr_color_value                       = '#ffffff';
    $hr_weight_value                      = 'normal';
    $hr_spacing_value                     = 5;
    $hr_duration_value                    = 300;
    $hr_timing_value                      = 'ease-out';
    $hover_cursor_text_value              = esc_html__('WP Custom Cursor - Circular Cursor', 'wpcustom-cursors');
    $hover_show_dot_value                 = 'on';
    $hover_dot_width_value                = 10;
    $hover_dot_color_value                = '#252500';
    $hover_text_color_value               = '#252525';
    $hover_text_transform_value           = 'uppercase';
    $hover_font_size_value                = 55;
    $hover_font_weight_value              = 'bold';
    $hover_text_width_value               = 150;
    $hover_word_spacing_value             = 30;
    $hover_text_animation_value           = 'spinright';
    $hover_text_animation_duration_value  = 10;
    $hover_text_type_value                = 'text';
    $hover_hr_text_value                  = esc_html__('View', 'wpcustom-cursors');
    $hover_hr_backdrop_value              = 'blur';
    $hover_hr_backdrop_amount_value       = '2px';
    $hover_hr_background_value            = 'rgba(0, 0, 0, 0.150)';
    $hover_hr_radius_value                = 100;
    $hover_hr_padding_value               = 0;
    $hover_hr_width_value                 = 100;
    $hover_hr_size_value                  = 18;
    $hover_hr_transform_value             = 'uppercase';
    $hover_hr_color_value                 = '#ffffff';
    $hover_hr_weight_value                = 'normal';
    $hover_hr_spacing_value               = 5;
    $hover_hr_duration_value              = 300;
    $hover_hr_timing_value                = 'ease-out';
    $hover_snap_background_value          = 'transparent';
    $hover_snap_blending_value            = 'normal';
    $hover_snap_radius_value              = 5;
    $hover_snap_padding_value             = 10;
    $hover_snap_border_width_value        = 1;
    $hover_snap_border_color_value        = '#252525';
    $cursor_type_value                    = 'shape';
    $cursor_state_value                   = 'normal';
    $hover_cursor_type_value              = 'default';

    // Edit cursor
if (isset($_GET['edit_row']) ) {

    $edit_row = intval( sanitize_text_field( wp_unslash( $_GET['edit_row'] ) ) );
	if ( check_admin_referer( 'edit-created-cursor' . $edit_row, 'wpcc_edit_created_nonce' ) ) {
        global $wpdb;
        $tablename           = $wpdb->prefix . 'created_cursors';
        $query               = $wpdb->prepare('SELECT * from %i WHERE cursor_id           = %d', array( $tablename, $edit_row ));
        $cursor              = $wpdb->get_row($query);
        $stripped            = stripslashes($cursor->cursor_options);
        $decoded             = json_decode($stripped, false);
        $stripped_hover      = stripslashes($cursor->hover_cursors);
        $decoded_hover       = json_decode($stripped_hover, false);
        $hover_cursors_value = null;
        switch ( $cursor->cursor_type ) {
        case 'shape':
            $fe_width_value           = $decoded->fe_width;
            $fe_height_value          = $decoded->fe_height;
            $fe_color_value           = $decoded->fe_color;
            $fe_radius_value          = $decoded->fe_radius;
            $fe_border_value          = $decoded->fe_border_width;
            $fe_border_color_value    = $decoded->fe_border_color;
            $fe_duration_value        = $decoded->fe_duration;
            $fe_timing_value          = $decoded->fe_timing;
            $fe_blending_value        = $decoded->fe_blending;
            $fe_velocity_value        = $decoded->fe_velocity;
            $fe_zindex_value          = $decoded->fe_zindex;
            $fe_backdrop_value        = $decoded->fe_backdrop;
            $fe_backdrop_amount_value = $decoded->fe_backdrop_value;
            $se_width_value           = $decoded->se_width;
            $se_height_value          = $decoded->se_height;
            $se_color_value           = $decoded->se_color;
            $se_radius_value          = $decoded->se_radius;
            $se_border_value          = $decoded->se_border_width;
            $se_border_color_value    = $decoded->se_border_color;
            $se_duration_value        = $decoded->se_duration;
            $se_timing_value          = $decoded->se_timing;
            $se_blending_value        = $decoded->se_blending;
            $se_velocity_value        = $decoded->se_velocity;
            $se_zindex_value          = $decoded->se_zindex;
            $se_backdrop_value        = $decoded->se_backdrop;
            $se_backdrop_amount_value = $decoded->se_backdrop;
            break;
        case 'image':
            $image_url_value                      = $decoded->image_url;
            $image_width_value                    = $decoded->width;
            $image_height_value                   = $decoded->height;
            $image_background_value               = $decoded->background;
            $image_background_color_value         = $decoded->color;
            $image_background_radius_value        = $decoded->radius;
            $image_background_padding_value       = $decoded->padding;
            $image_blending_value                 = $decoded->blending;
            $click_point_value                    = $decoded->click_point; 
            $hover_image_url_value                = $decoded->image_url;
            $hover_image_width_value              = $decoded->width;
            $hover_image_height_value             = $decoded->height;
            $hover_image_background_value         = $decoded->background;
            $hover_image_background_color_value   = $decoded->color;
            $hover_image_background_radius_value  = $decoded->radius;
            $hover_image_background_padding_value = $decoded->padding;
            $hover_image_blending_value           = $decoded->blending;
            $click_point_value                    = $decoded->click_point;
            break;
        case 'text':
            if ('horizontal' === $decoded->text_type ) {
                $hr_text_value                  = $decoded->hr_text;
                $hr_backdrop_value              = $decoded->hr_backdrop;
                $hr_backdrop_amount_value       = $decoded->hr_backdrop_amount;
                $hr_background_value            = $decoded->hr_bgcolor;
                $hr_radius_value                = $decoded->hr_radius;
                $hr_padding_value               = $decoded->hr_padding;
                $hr_width_value                 = $decoded->hr_width;
                $hr_transform_value             = $decoded->hr_transform;
                $hr_weight_value                = $decoded->hr_weight;
                $hr_color_value                 = $decoded->hr_color;
                $hr_size_value                  = $decoded->hr_size;
                $hr_spacing_value               = $decoded->hr_spacing;
                $hr_duration_value              = $decoded->hr_duration;
                $hr_timing_value                = $decoded->hr_timing;
                $text_type_value                = $decoded->text_type;
                $hover_hr_text_value            = $decoded->hover_hr_text;
                $hover_hr_backdrop_value        = $decoded->hover_hr_backdrop;
                $hover_hr_backdrop_amount_value = $decoded->hover_hr_backdrop_amount;
                $hover_hr_background_value      = $decoded->hover_hr_bgcolor;
                $hover_hr_radius_value          = $decoded->hover_hr_radius;
                $hover_hr_padding_value         = $decoded->hover_hr_padding;
                $hover_hr_width_value           = $decoded->hover_hr_width;
                $hover_hr_transform_value       = $decoded->hover_hr_transform;
                $hover_hr_weight_value          = $decoded->hover_hr_weight;
                $hover_hr_color_value           = $decoded->hover_hr_color;
                $hover_hr_size_value            = $decoded->hover_hr_size;
                $hover_hr_spacing_value         = $decoded->hover_hr_spacing;
                $hover_hr_duration_value        = $decoded->hover_hr_duration;
                $hover_hr_timing_value          = $decoded->hover_hr_timing;
                $hover_text_type_value          = $decoded->hover_text_type;
            } else {
                $cursor_text_value                   = $decoded->text;
                $show_dot_value                      = $decoded->dot;
                $dot_color_value                     = $decoded->dot_color;
                $text_color_value                    = $decoded->text_color;
                $text_transform_value                = $decoded->text_transform;
                $font_size_value                     = $decoded->font_size;
                $font_weight_value                   = $decoded->font_weight;
                $text_width_value                    = $decoded->width;
                $word_spacing_value                  = $decoded->word_spacing;
                $text_animation_value                = $decoded->animation;
                $text_animation_duration_value       = $decoded->animation_duration;
                $text_type_value                     = $decoded->text_type;
                $hover_cursor_text_value             = $decoded->hover_text;
                $hover_show_dot_value                = $decoded->hover_dot;
                $hover_dot_color_value               = $decoded->hover_dot_color;
                $hover_text_color_value              = $decoded->hover_text_color;
                $hover_text_transform_value          = $decoded->hover_text_transform;
                $hover_font_size_value               = $decoded->hover_font_size;
                $hover_font_weight_value             = $decoded->hover_font_weight;
                $hover_text_width_value              = $decoded->hover_width;
                $hover_word_spacing_value            = $decoded->hover_word_spacing;
                $hover_text_animation_value          = $decoded->hover_animation;
                $hover_text_animation_duration_value = $decoded->hover_animation_duration;
                $hover_text_type_value               = $decoded->hover_text_type;
            }
            break;
        }
        $cursor_type_value       = $cursor->cursor_type;
        $hover_cursor_type_value = $decoded_hover[0]->hover_type;
        switch ( $decoded_hover[0]->hover_type ) {
        case 'snap':
            $hover_snap_background_value   = $decoded_hover[0]->bgcolor;
            $hover_snap_blending_value     = $decoded_hover[0]->blending;
            $hover_snap_radius_value       = $decoded_hover[0]->radius;
            $hover_snap_padding_value      = $decoded_hover[0]->padding;
            $hover_snap_border_width_value = $decoded_hover[0]->border_width;
            $hover_snap_border_color_value = $decoded_hover[0]->border_color;
            $hover_trigger_link_value      = $decoded_hover[0]->links;
            $hover_trigger_button_value    = $decoded_hover[0]->buttons;
            $hover_trigger_image_value     = $decoded_hover[0]->images;
            $hover_trigger_custom_value    = $decoded_hover[0]->custom;
            $hover_trigger_selector_value  = $decoded_hover[0]->selector;
            break;
        }
    }
    
}
?>
    <div class="card p-0">
        <form action="#" method="post" id="create_cursor_form">
                <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-8">
                        <h3 class="h5 mb-0"><?php echo esc_html__('Create New Cursor', 'wpcustom-cursors'); ?></h3>
                    </div>
                    <div class="col-4" id="cursor_type_container">
                        <!-- Normal Cursor Type -->
                        <div class="cursor-type-selector-wrapper" id="normal_cursor_type_wrapper">
                            <label for="cursor_type" class="title-normal"><?php echo esc_html__('Cursor Type:', 'wpcustom-cursors'); ?></label>
                            <!-- Cursor Type -->
                            <div class="form-group flex-grow-1 ms-3">
                                <select class="form-control" id="cursor_type" name='cursor_type' data-state="normal">
                                    <option value="shape" 
                                    <?php
                                    if (isset($cursor_type_value) ) {
                                        selected($cursor_type_value, 'shape');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Shape', 'wpcustom-cursors'); ?></option>
                                    <option value="image" 
                                    <?php
                                    if (isset($cursor_type_value) ) {
                                        selected($cursor_type_value, 'image');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Image', 'wpcustom-cursors'); ?></option>
                                    <option value="text" 
                                    <?php
                                    if (isset($cursor_type_value) ) {
                                        selected($cursor_type_value, 'text');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Text', 'wpcustom-cursors'); ?></option>
                                </select>
                            </div>
                        </div>

                        <!-- Hover Cursor Type -->
                        <div class="cursor-type-selector-wrapper" style="display:none;" id="hover_cursor_type_wrapper">
                            <label for="hover_cursor_type" class="title-normal"><?php echo esc_html__('Hover Cursor Type:', 'wpcustom-cursors'); ?></label>
                            <!-- Cursor Type -->
                            <div class="form-group flex-grow-1 ms-3">
                                <select class="form-control" id="hover_cursor_type" data-state="hover">
                                    <option value="shape" 
                                    <?php
                                    if (isset($hover_cursor_type_value) ) {
                                        selected($hover_cursor_type_value, 'shape');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Shape', 'wpcustom-cursors'); ?></option>
                                    <option value="image" 
                                    <?php
                                    if (isset($hover_cursor_type_value) ) {
                                        selected($hover_cursor_type_value, 'image');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Image', 'wpcustom-cursors'); ?></option>
                                    <option value="text" 
                                    <?php
                                    if (isset($hover_cursor_type_value) ) {
                                        selected($hover_cursor_type_value, 'text');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Text', 'wpcustom-cursors'); ?></option>
                                    <option value="snap" 
                                    <?php
                                    if (isset($hover_cursor_type_value) ) {
                                        selected($hover_cursor_type_value, 'snap');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Snap', 'wpcustom-cursors'); ?></option>
                                    <option value="default" 
                                    <?php
                                    if (isset($hover_cursor_type_value) ) {
                                        selected($hover_cursor_type_value, 'default');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Browser Default', 'wpcustom-cursors'); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            <div class="card-body py-0">
                <div class="row">
                    <div class="col-9 col-md border-end py-3">
                        <div class="row">
                            <div class="col-6">
                                <div id="normal_preview_container" class="active-preview">
                                    <!-- Shape Preview - Normal -->
                                    <div class="cursor-preview position-relative" id="normal_shape_preview" data-state="normal" data-preview-type="shape" style="
                                    <?php
                                    if ('shape' !== $cursor_type_value ) {
                                        echo esc_attr('display: none;'); 
                                    }
                                    ?>
                                    ">
                                        <div class="badge position-absolute"><?php echo esc_html__('Normal', 'wpcustom-cursors'); ?></div>
                                        <div class="cursor-wrapper bg-white">
                                            <div class="el-1 shape-element" id="el_1" style="--width: <?php echo esc_attr($fe_width_value); ?>px; --height: <?php echo esc_attr($fe_height_value); ?>px; --color: <?php echo esc_attr($fe_color_value); ?>; --radius: <?php echo esc_attr($fe_radius_value); ?>px; --border: <?php echo esc_attr($fe_border_value); ?>px; --border-color: <?php echo esc_attr($fe_border_color_value); ?>; --blending: <?php echo esc_attr($fe_blending_value); ?>; --zindex: <?php echo esc_attr($fe_zindex_value); ?>; --backdrop: <?php echo esc_attr($fe_backdrop_value . '(' . $fe_backdrop_amount_value . ')'); ?>;"></div>
                                            <div class="el-2 shape-element" id="el_2" style="--width: <?php echo esc_attr($se_width_value); ?>px; --height: <?php echo esc_attr($se_height_value); ?>px; --color: <?php echo esc_attr($se_color_value); ?>; --radius: <?php echo esc_attr($se_radius_value); ?>px; --border: <?php echo esc_attr($se_border_value); ?>px; --border-color: <?php echo esc_attr($se_border_color_value); ?>; --blending: <?php echo esc_attr($se_blending_value); ?>; --zindex: <?php echo esc_attr($se_zindex_value); ?>; --backdrop: <?php echo esc_attr($se_backdrop_value . '(' . $se_backdrop_amount_value . ')'); ?>;"></div>
                                        </div>
                                    </div>

                                    <!-- Image Preview - Normal -->
                                    <div class="cursor-preview position-relative" id="normal_image_preview" data-state="normal" data-preview-type="image" style="
                                    <?php
                                    if ($cursor_type_value != 'image' ) {
                                        echo esc_attr('display: none;'); 
                                    }
                                    ?>
                                    ">
                                        <div class="badge position-absolute"><?php echo esc_html__('Normal', 'wpcustom-cursors'); ?></div>
                                        <div class="image-cursor-wrapper">
                                            <div class="image-upload-btn cursor-pointer 
                                            <?php
                                            if ($image_url_value ) {
                                                echo esc_attr('visually-hidden');
                                            }
                                            ?>
                                            " id="image_upload_btn">
                                                <?php echo esc_html__('Click to add image', 'wpcustom-cursors'); ?>
                                            </div>
         
                                            <div class="new-image uploaded-image-wrapper position-relative d-inline-block 
                                            <?php
                                            if (! $image_url_value ) {
                                                echo esc_attr('visually-hidden');
                                            }
                                            ?>
                                            " id="normal_uploaded_image_wrapper" style="--image-width: <?php echo esc_attr($image_width_value); ?>px; --image-background-color: <?php echo esc_attr($image_background_color_value); ?>; --image-background-radius: <?php echo esc_attr($image_background_radius_value); ?>px; --image-background-padding: <?php echo esc_attr($image_background_padding_value); ?>px; --image-background-blending: <?php echo esc_attr($image_blending_value); ?>;">
                                                <!-- Set The Click Point -->
                                                <div class="click-point" id="click_point" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="<?php echo esc_html__('Drag to set the click point', 'wpcustom-cursors'); ?>"></div>
                                                <img class="uploaded-image" src="<?php echo esc_url($image_url_value); ?>" id="uploaded_image" alt="<?php echo esc_html__('Custom Image Cursor', 'wpcustom-cursors'); ?>" />
                                            </div>
                                        </div>

                                        <!-- Delete Button -->
                                        <div id="wpcc_delete_image" class="image-del-btn wpcc_delete_image 
                                        <?php
                                        if (! $image_url_value ) {
                                            echo esc_attr('visually-hidden'); 
                                        }
                                        ?>
                                        ">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                <g>
                                                    <path fill="none" d="M0 0h24v24H0z"/>
                                                    <path d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z" fill="white"/>
                                                </g>
                                            </svg>
                                        </div>

                                        <div id="click_point_info" class="click-point-info text-muted 
                                        <?php
                                        if (! $image_url_value ) {
                                            echo esc_attr('visually-hidden'); 
                                        }
                                        ?>
                                        " data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="<?php echo esc_html__('The red dot defines the clickable point of the image cursor. Simply move the dot to the point of you image cursor where you want to do the click functionality.', 'wpcustom-cursors'); ?>">
                                            <i class="ri-question-fill"></i> <span class="small"><?php echo esc_html__('About Click Point?', 'wpcustom-cursors'); ?></span>    
                                        </div>
                                    </div>

                                    <!-- Text Preview - Normal -->
                                    <div class="cursor-preview position-relative" id="normal_text_preview" data-state="normal" data-preview-type="text" style="
                                    <?php
                                    if ('text' !== $cursor_type_value || 'circular' !== $text_type_value ) {
                                        echo esc_attr('display: none;'); 
                                    }
                                    ?>
                                    ">
                                        <div class="badge position-absolute"><?php echo esc_html__('Normal', 'wpcustom-cursors'); ?></div>
                                        <div class="cursor-text">
                                            <svg viewBox="0 0 500 500" id="svg_node" style="--dot-fill: <?php echo esc_attr($dot_color_value); ?>; --text-color: <?php echo esc_attr($text_color_value); ?>; --text-width: <?php echo esc_attr($text_width_value); ?>px; --text-transform: <?php echo esc_attr($text_transform_value); ?>; --font-size: <?php echo esc_attr($font_size_value); ?>px; --font-weight: <?php echo esc_attr($font_weight_value); ?>; --word-spacing: <?php echo esc_attr($word_spacing_value); ?>px; --animation-name: <?php echo esc_attr($text_animation_value); ?>; --animation-duration: <?php echo esc_attr($text_animation_duration_value); ?>s;"><path d="M50,250c0-110.5,89.5-200,200-200s200,89.5,200,200s-89.5,200-200,200S50,360.5,50,250" id="textcircle" fill="none"></path><text dy="25" id="svg_text_cursor"><textPath xlink:href="#textcircle" id="textpath"><?php echo esc_html($cursor_text_value); ?></textPath></text><circle cx="250" cy="250" r="10" id="svg_circle_node"/></svg>
                                        </div>
                                    </div>

                                    <div class="cursor-preview position-relative" id="normal_horizontal_preview" data-state="normal" data-preview-type="text" style="
                                    <?php
                                    if ('text' !== $cursor_type_value || 'horizontal' !== $text_type_value ) {
                                        echo esc_attr('display: none;'); 
                                    }
                                    ?>
                                    ">
                                        <div class="badge position-absolute"><?php echo esc_html__('Normal', 'wpcustom-cursors'); ?></div>
                                        <div class="cursor-text">
                                            <div id="hr_text_container" class="horizontal-text" style="--hr-color: <?php echo esc_attr($hr_color_value); ?>; --bg-color: <?php echo esc_attr($hr_background_value); ?>; --hr-width: <?php echo esc_attr($hr_width_value); ?>px;; --hr-radius: <?php echo esc_attr($hr_radius_value); ?>px; --hr-transform: <?php echo esc_attr($hr_transform_value); ?>; --hr-size: <?php echo esc_attr($hr_size_value); ?>px; --hr-weight: <?php echo esc_attr($hr_weight_value); ?>; --hr-duration: <?php echo esc_attr($hr_duration_value); ?>ms; --hr-timing: <?php echo esc_attr($hr_timing_value); ?>px; --hr-spacing: <?php echo esc_attr($hr_spacing_value); ?>px; --hr-backdrop: <?php echo esc_attr($hr_backdrop_value . '(' . $hr_backdrop_amount_value . ')'); ?>">
                                                <?php echo esc_html($hr_text_value); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div id="hover_preview_container">
                                    <!-- Shape Preview - Hover -->
                                    <div class="cursor-preview position-relative" id="hover_shape_preview" data-state="hover" data-preview-type="shape" style="
                                    <?php
                                    if ('shape' !== $hover_cursor_type_value ) {
                                        echo esc_attr('display: none;'); 
                                    }
                                    ?>
                                    ">
                                        <div class="badge position-absolute"><?php echo esc_html__('Hover', 'wpcustom-cursors'); ?></div>
                                        <div class="cursor-wrapper bg-white">
                                            <div class="el-1 shape-element" id="hover_el_1" style="--width: <?php echo esc_attr($hover_fe_width_value); ?>px; --height: <?php echo esc_attr($hover_fe_height_value); ?>px; --color: <?php echo esc_attr($hover_fe_color_value); ?>; --radius: <?php echo esc_attr($hover_fe_radius_value); ?>px; --border: <?php echo esc_attr($hover_fe_border_value); ?>px; --border-color: <?php echo esc_attr($hover_fe_border_color_value); ?>; --blending: <?php echo esc_attr($hover_fe_blending_value); ?>; --zindex: <?php echo esc_attr($hover_fe_zindex_value); ?>; --backdrop: <?php echo esc_attr($hover_fe_backdrop_value . '(' . $hover_fe_backdrop_amount_value . ')'); ?>;"></div>
                                            <div class="el-2 shape-element" id="hover_el_2" style="--width: <?php echo esc_attr($hover_se_width_value); ?>px; --height: <?php echo esc_attr($hover_se_height_value); ?>px; --color: <?php echo esc_attr($hover_se_color_value); ?>; --radius: <?php echo esc_attr($hover_se_radius_value); ?>px; --border: <?php echo esc_attr($hover_se_border_value); ?>px; --border-color: <?php echo esc_attr($hover_se_border_color_value); ?>; --blending: <?php echo esc_attr($hover_se_blending_value); ?>; --zindex: <?php echo esc_attr($hover_se_zindex_value); ?>; --backdrop: <?php echo esc_attr($hover_se_backdrop_value . '(' . $hover_se_backdrop_amount_value . ')'); ?>;"></div>
                                        </div>
                                    </div>

                                    <!-- Image Preview - Hover -->
                                    <div class="cursor-preview position-relative" id="hover_image_preview" data-state="hover" data-preview-type="image" style="
                                    <?php
                                    if ('image' !== $hover_cursor_type_value ) {
                                        echo esc_attr('display: none;'); 
                                    }
                                    ?>
                                    ">
                                        <div class="badge position-absolute"><?php echo esc_html__('Hover', 'wpcustom-cursors'); ?></div>
                                        <div class="image-cursor-wrapper">
                                            <div class="image-upload-btn cursor-pointer 
                                            <?php
                                            if ($hover_image_url_value ) {
                                                echo esc_attr('visually-hidden');
                                            }
                                            ?>
                                            " id="hover_image_upload_btn">
                                                <?php echo esc_html__('Click to add image', 'wpcustom-cursors'); ?>
                                            </div>
         
                                            <div class="new-image uploaded-image-wrapper position-relative d-inline-block 
                                            <?php
                                            if (! $hover_image_url_value ) {
                                                echo esc_attr('visually-hidden');
                                            }
                                            ?>
                                            " id="hover_uploaded_image_wrapper" style="--image-width: <?php echo esc_attr($hover_image_width_value); ?>px; --image-background-color: <?php echo esc_attr($hover_image_background_color_value); ?>; --image-background-radius: <?php echo esc_attr($hover_image_background_radius_value); ?>px; --image-background-padding: <?php echo esc_attr($hover_image_background_padding_value); ?>px; --image-background-blending: <?php echo esc_attr($hover_image_blending_value); ?>;">
                                                <!-- Set The Click Point -->
                                                <div class="click-point" id="hover_click_point" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="<?php echo esc_html__('Drag to set the click point', 'wpcustom-cursors'); ?>"></div>
                                                <img class="uploaded-image" src="<?php echo esc_url($hover_image_url_value); ?>" id="hover_uploaded_image" alt="<?php echo esc_html__('Custom Image Cursor', 'wpcustom-cursors'); ?>" />
                                            </div>
                                        </div>

                                        <!-- Delete Button -->
                                        <div id="hover_wpcc_delete_image" class="image-del-btn wpcc_delete_image 
                                        <?php
                                        if (! $hover_image_url_value ) {
                                            echo esc_attr('visually-hidden'); 
                                        }
                                        ?>
                                        ">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                                <g>
                                                    <path fill="none" d="M0 0h24v24H0z"/>
                                                    <path d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z" fill="white"/>
                                                </g>
                                            </svg>
                                        </div>

                                        <div id="hover_click_point_info" class="click-point-info text-muted 
                                        <?php
                                        if (! $hover_image_url_value ) {
                                            echo esc_attr('visually-hidden'); 
                                        }
                                        ?>
                                        " data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="<?php echo esc_html__('The red dot defines the clickable point of the image cursor. Simply move the dot to the point of you image cursor where you want to do the click functionality.', 'wpcustom-cursors'); ?>">
                                            <i class="ri-question-fill"></i> <span class="small"><?php echo esc_html__('About Click Point?', 'wpcustom-cursors'); ?></span>    
                                        </div>
                                    </div>

                                    <!-- Text Preview - Hover -->
                                    <div class="cursor-preview position-relative" id="hover_text_preview" data-state="hover" data-preview-type="text" style="
                                    <?php
                                    if ('text' !== $hover_cursor_type_value || 'circular' !== $hover_text_type_value ) {
                                        echo esc_attr('display: none;'); 
                                    }
                                    ?>
                                    ">
                                        <div class="badge position-absolute"><?php echo esc_html__('Hover', 'wpcustom-cursors'); ?></div>
                                        <div class="cursor-text">
                                            <svg viewBox="0 0 500 500" id="hover_svg_node" style="--dot-fill: <?php echo esc_attr($hover_dot_color_value); ?>; --text-color: <?php echo esc_attr($hover_text_color_value); ?>; --text-width: <?php echo esc_attr($hover_text_width_value); ?>px; --text-transform: <?php echo esc_attr($hover_text_transform_value); ?>; --font-size: <?php echo esc_attr($hover_font_size_value); ?>px; --font-weight: <?php echo esc_attr($hover_font_weight_value); ?>; --word-spacing: <?php echo esc_attr($hover_word_spacing_value); ?>px; --animation-name: <?php echo esc_attr($hover_text_animation_value); ?>; --animation-duration: <?php echo esc_attr($hover_text_animation_duration_value); ?>s;"><path d="M50,250c0-110.5,89.5-200,200-200s200,89.5,200,200s-89.5,200-200,200S50,360.5,50,250" id="hover_textcircle" fill="none"></path><text dy="25" id="hover_svg_text_cursor"><textPath xlink:href="#textcircle" id="hover_textpath"><?php echo esc_html($hover_cursor_text_value); ?></textPath></text><circle cx="250" cy="250" r="10" id="hover_svg_circle_node"/></svg>
                                        </div>
                                    </div>

                                    <!-- Horizontal Preview - Hover -->
                                    <div class="cursor-preview position-relative" id="hover_horizontal_preview" data-state="hover" data-preview-type="text" style="
                                    <?php
                                    if ('text' !== $hover_cursor_type_value || 'horizontal' !== $hover_text_type_value ) {
                                        echo esc_attr('display: none;'); 
                                    }
                                    ?>
                                    ">
                                        <div class="badge position-absolute"><?php echo esc_html__('Hover', 'wpcustom-cursors'); ?></div>
                                        <div class="cursor-text">
                                            <div id="hover_hr_text_container" class="horizontal-text" style="--hr-color: <?php echo esc_attr($hover_hr_color_value); ?>; --bg-color: <?php echo esc_attr($hover_hr_background_value); ?>; --hr-width: <?php echo esc_attr($hover_hr_width_value); ?>px;; --hr-radius: <?php echo esc_attr($hover_hr_radius_value); ?>px; --hr-transform: <?php echo esc_attr($hover_hr_transform_value); ?>; --hr-size: <?php echo esc_attr($hover_hr_size_value); ?>px; --hr-weight: <?php echo esc_attr($hover_hr_weight_value); ?>; --hr-duration: <?php echo esc_attr($hover_hr_duration_value); ?>ms; --hr-timing: <?php echo esc_attr($hover_hr_timing_value); ?>px; --hr-spacing: <?php echo esc_attr($hover_hr_spacing_value); ?>px; --hr-backdrop: <?php echo esc_attr($hover_hr_backdrop_value . '(' . $hover_hr_backdrop_amount_value . ')'); ?>">
                                                <?php echo esc_html($hover_hr_text_value); ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Default Preview - Hover -->
                                    <div class="cursor-preview position-relative" id="hover_default_preview" data-state="hover" data-preview-type="default" style="
                                    <?php
                                    if ('default' !== $hover_cursor_type_value ) {
                                        echo esc_attr('display: none;'); 
                                    }
                                    ?>
                                    ">
                                        <div class="badge position-absolute"><?php echo esc_html__('Hover', 'wpcustom-cursors'); ?></div>
                                        <div class="cursor-wrapper"><?php echo esc_html__('Browser Default', 'wpcustom-cursors'); ?></div>
                                    </div>

                                    <!-- Snap Preview - Hover -->
                                    <div class="cursor-preview position-relative" id="hover_snap_preview" data-state="hover" data-preview-type="snap" style="
                                    <?php
                                    if ('snap' !== $hover_cursor_type_value ) {
                                        echo esc_attr('display: none;'); 
                                    }
                                    ?>
                                    ">
                                        <div class="badge position-absolute"><?php echo esc_html__('Hover', 'wpcustom-cursors'); ?></div>
                                        <div class="cursor-wrapper snap-container" id="hover_snap_element" style="--radius: <?php echo esc_attr($hover_snap_radius_value); ?>px; --padding: <?php echo esc_attr($hover_snap_padding_value); ?>px; --border-width: <?php echo esc_attr($hover_snap_border_width_value); ?>px; --border-color: <?php echo esc_attr($hover_snap_border_color_value); ?>; --bg-color: <?php echo esc_attr($hover_snap_background_value); ?>; --blending: <?php echo esc_attr($hover_snap_blending_value); ?>">
                                            <div class="element"><?php echo esc_html__('Element', 'wpcustom-cursors'); ?></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2 justify-content-between hover-actions">
                                    <div id="hover_list">        
                                    <?php
                                    if (isset($_GET['edit_row']) && $decoded_hover ) {
                                        foreach ( $decoded_hover as $key => $hover ) {
                                            ?>
                                            <div class="hover-badge 
                                            <?php
                                            if (0 === $key ) {
                                                                     echo esc_attr('active');
                                            }
                                            ?>
                                            ">
                                                <div class="cursor-pointer select-hover" data-id="<?php echo esc_attr($key); ?>">
                                            <?php echo esc_html(ucfirst($hover->hover_type)); ?>
                                                </div>
                                            <?php
                                            if ($key > 0 ) {
                                                ?>
                                                <i class="ms-1 ri-close-fill remove-hover cursor-pointer" data-id="<?php echo esc_attr($key); ?>"></i>
                                                                        <?php
                                            }
                                            ?>
                                            </div>
                                            <?php
                                        }
                                    } else {
                                        ?>
                                        <div class="hover-badge active">
                                            <div class="cursor-pointer select-hover" data-id="0">
                                        <?php echo esc_html(ucfirst($hover_cursor_type_value)); ?>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    </div>
                                    <div>
                                        <div class="add-hover-btn" id="add_hover_btn" style="
                                        <?php
                                        if ('default' === $hover_cursor_type_value ) {
                                            echo esc_attr('display: none;');
                                        }
                                        ?>
                                        "><i class="ri-add-fill"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md py-3 options-container" id="options_container">
                        <div id="normal_shape_options" style="
                        <?php
                        if ('shape' !== $cursor_type_value ) {
                            echo esc_attr('display: none;'); 
                        }
                        ?>
                        ">
                            <div class="accordion accordion-flush" id="shapeAccordionOptions">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed flex-column align-items-start" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                            <h4 class="h5"><?php echo esc_html__('Inner Circle', 'wpcustom-cursors'); ?></h4>
                                        </button>
                                    </h2>
                                    <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#shapeAccordionOptions">
                                        <div class="accordion-body">
                                            <!-- First Element Options - Normal -->
                                            <div id="shape_1_options" >
                                                <!-- Width -->
                                                <label for="fe_width" class="title-normal mt-3"><?php echo esc_html__('Width:', 'wpcustom-cursors'); ?></label>

                                                <div class="d-flex align-items-center mt-2">
                                                    <input type="range" data-apply="el_1" data-variable="width" data-unit="px" class="form-range me-2" min="1" max="300" id="fe_width_range" value="<?php echo esc_attr($fe_width_value); ?>">
                                                    <input type="number" data-apply="el_1" data-variable="width" data-unit="px" min="1" max="300" id="fe_width" class="number-input" value="<?php echo esc_attr($fe_width_value); ?>" data-name="fe_width">
                                                    <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                                                </div>

                                                <!-- Height -->
                                                <label for="fe_height" class="title-normal mt-3"><?php echo esc_html__('Height:', 'wpcustom-cursors'); ?></label>

                                                <div class="d-flex align-items-center mt-2">
                                                    <input type="range" data-apply="el_1" data-variable="height" data-unit="px" class="form-range me-2" min="1" max="300" id="fe_height_range" value="<?php echo esc_attr($fe_height_value); ?>">
                                                    <input type="number" data-apply="el_1" data-variable="height" data-unit="px" min="1" max="300" id="fe_height" class="number-input" value="<?php echo esc_attr($fe_height_value); ?>" data-name="fe_height">
                                                    <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                                                </div>

                                                <!-- Color -->
                                                <div class="title-normal mt-3">
                                                    <?php echo esc_html__('Color:', 'wpcustom-cursors'); ?>
                                                </div>
                                                <div class="color_select form-group mt-2">
                                                    <label class="w-100">
                                                        <input type='text' class="form-control basic wp-custom-cursor-color-picker" data-apply="el_1" data-variable="color" id="fe_color" value="<?php echo esc_attr($fe_color_value); ?>" data-name="fe_color">
                                                    </label>
                                                </div>

                                                <!-- Border -->
                                                <label for="fe_border" class="title-normal mt-3"><?php echo esc_html__('Border Width:', 'wpcustom-cursors'); ?></label>

                                                <div class="d-flex align-items-center mt-2">
                                                    <input type="range" data-apply="el_1" data-variable="border" data-unit="px" class="form-range me-2" min="0" max="100" id="fe_border_range" value="<?php echo esc_attr($fe_border_value); ?>">
                                                    <input type="number" data-apply="el_1" data-variable="border" data-unit="px" min="0" max="100" id="fe_border" class="number-input" value="<?php echo esc_attr($fe_border_value); ?>" data-name="fe_border_width">
                                                    <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                                                </div>

                                                <!-- Radius -->
                                                <label for="fe_radius" class="title-normal mt-3"><?php echo esc_html__('Border Radius:', 'wpcustom-cursors'); ?></label>

                                                <div class="d-flex align-items-center mt-2">
                                                    <input type="range" data-apply="el_1" data-variable="radius" data-unit="px" class="form-range me-2" min="0" max="1000" id="fe_radius_range" value="<?php echo esc_attr($fe_radius_value); ?>">
                                                    <input type="number" data-apply="el_1" data-variable="radius" data-unit="px" min="0" max="1000" id="fe_radius" class="number-input" value="<?php echo esc_attr($fe_radius_value); ?>" data-name="fe_radius">
                                                    <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                                                </div>

                                                <!-- Border Color -->
                                                <div class="title-normal mt-3">
                                                    <?php echo esc_html__('Border Color:', 'wpcustom-cursors'); ?>
                                                </div>
                                                <div class="color_select form-group mt-2">
                                                    <label class="w-100">
                                                        <input type='text' class="form-control basic wp-custom-cursor-color-picker" data-apply="el_1" data-variable="border-color" id="fe_border_color" value="<?php echo esc_attr($fe_border_color_value); ?>" data-name="fe_border_color">
                                                    </label>
                                                </div>

                                                <!-- Transition Duration -->
                                                <label for="fe_duration" class="title-normal mt-3"><?php echo esc_html__('Transition Duration:', 'wpcustom-cursors'); ?></label>

                                                <div class="d-flex align-items-center mt-2">
                                                    <input type="range" data-apply="el_1" data-variable="duration" data-unit="ms" class="form-range me-2" min="0" max="1000" id="fe_duration_range" value="<?php echo esc_attr($fe_duration_value); ?>">
                                                    <input type="number" data-apply="el_1" data-variable="duration" data-unit="ms" min="0" max="1000" id="fe_duration" class="number-input" value="<?php echo esc_attr($fe_duration_value); ?>" data-name="fe_duration">
                                                    <span class="ms-2 small"><?php echo esc_html__('MS', 'wpcustom-cursors'); ?></span>
                                                </div>

                                                <!-- Transition Timing Function -->
                                                <div class="form-group">
                                                    <label for="fe_timing" class="title-normal mt-3"><?php echo esc_html__('Transition Timing Function:', 'wpcustom-cursors'); ?></label>
                                                    <select class="form-control mt-2" id="fe_timing" data-apply="el_1" data-variable="timing" data-name="fe_timing">
                                                        <option value="ease" 
                                                        <?php
                                                        if (isset($fe_timing_value) ) {
                                                            selected($fe_timing_value, 'ease');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Ease', 'wpcustom-cursors'); ?></option>
                                                        <option value="ease-in" 
                                                        <?php
                                                        if (isset($fe_timing_value) ) {
                                                            selected($fe_timing_value, 'ease-in');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Ease In', 'wpcustom-cursors'); ?></option>
                                                        <option value="ease-out" 
                                                        <?php
                                                        if (isset($fe_timing_value) ) {
                                                            selected($fe_timing_value, 'ease-out');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Ease Out', 'wpcustom-cursors'); ?></option>
                                                        <option value="ease-in-out" 
                                                        <?php
                                                        if (isset($fe_timing_value) ) {
                                                            selected($fe_timing_value, 'ease-in-out');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Ease In Out', 'wpcustom-cursors'); ?></option>
                                                        <option value="linear" 
                                                        <?php
                                                        if (isset($fe_timing_value) ) {
                                                            selected($fe_timing_value, 'linear');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Linear', 'wpcustom-cursors'); ?></option>
                                                    </select>
                                                </div>

                                                <!-- Blending Mode -->
                                                <div class="form-group">
                                                    <label for="fe_blending" class="title-normal mt-3"><?php echo esc_html__('Blending Mode:', 'wpcustom-cursors'); ?></label>
                                                    <select class="form-control mt-2" id="fe_blending" data-apply="el_1" data-variable="blending" data-name="fe_blending">
                                                        <option value="normal" 
                                                        <?php
                                                        if (isset($fe_blending_value) ) {
                                                            selected($fe_blending_value, 'normal');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Normal', 'wpcustom-cursors'); ?></option>
                                                        <option value="multiply" 
                                                        <?php
                                                        if (isset($fe_blending_value) ) {
                                                            selected($fe_blending_value, 'multiply');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Multiply', 'wpcustom-cursors'); ?></option>
                                                        <option value="screen" 
                                                        <?php
                                                        if (isset($fe_blending_value) ) {
                                                            selected($fe_blending_value, 'screen');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Screen', 'wpcustom-cursors'); ?></option>
                                                        <option value="overlay" 
                                                        <?php
                                                        if (isset($fe_blending_value) ) {
                                                            selected($fe_blending_value, 'overlay');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Overlay', 'wpcustom-cursors'); ?></option>
                                                        <option value="darken" 
                                                        <?php
                                                        if (isset($fe_blending_value) ) {
                                                            selected($fe_blending_value, 'darken');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Darken', 'wpcustom-cursors'); ?></option>
                                                        <option value="lighten" 
                                                        <?php
                                                        if (isset($fe_blending_value) ) {
                                                            selected($fe_blending_value, 'lighten');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Lighten', 'wpcustom-cursors'); ?></option>
                                                        <option value="color-dodge" 
                                                        <?php
                                                        if (isset($fe_blending_value) ) {
                                                            selected($fe_blending_value, 'color-dodge');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Color Dodge', 'wpcustom-cursors'); ?></option>
                                                        <option value="color-burn" 
                                                        <?php
                                                        if (isset($fe_blending_value) ) {
                                                            selected($fe_blending_value, 'color-burn');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Color Burn', 'wpcustom-cursors'); ?></option>
                                                        <option value="hard-light" 
                                                        <?php
                                                        if (isset($fe_blending_value) ) {
                                                            selected($fe_blending_value, 'hard-light');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Hard Light', 'wpcustom-cursors'); ?></option>
                                                        <option value="soft-light" 
                                                        <?php
                                                        if (isset($fe_blending_value) ) {
                                                            selected($fe_blending_value, 'soft-light');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Soft Light', 'wpcustom-cursors'); ?></option>
                                                        <option value="difference" 
                                                        <?php
                                                        if (isset($fe_blending_value) ) {
                                                            selected($fe_blending_value, 'difference');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Difference', 'wpcustom-cursors'); ?></option>
                                                        <option value="exclusion" 
                                                        <?php
                                                        if (isset($fe_blending_value) ) {
                                                            selected($fe_blending_value, 'exclusion');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Exclusion', 'wpcustom-cursors'); ?></option>
                                                        <option value="hue" 
                                                        <?php
                                                        if (isset($fe_blending_value) ) {
                                                            selected($fe_blending_value, 'hue');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Hue', 'wpcustom-cursors'); ?></option>
                                                        <option value="saturation" 
                                                        <?php
                                                        if (isset($fe_blending_value) ) {
                                                            selected($fe_blending_value, 'saturation');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Saturation', 'wpcustom-cursors'); ?></option>
                                                        <option value="color" 
                                                        <?php
                                                        if (isset($fe_blending_value) ) {
                                                            selected($fe_blending_value, 'color');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Color', 'wpcustom-cursors'); ?></option>
                                                        <option value="luminosity" 
                                                        <?php
                                                        if (isset($fe_blending_value) ) {
                                                            selected($fe_blending_value, 'luminosity');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Luminosity', 'wpcustom-cursors'); ?></option>
                                                    </select>
                                                </div>

                                                <!-- Velocity Mode -->
                                                <div class="form-group">
                                                    <label for="fe_velocity" class="title-normal mt-3"><?php echo esc_html__('Velocity:', 'wpcustom-cursors'); ?></label>
                                                    <select class="form-control mt-2" id="fe_velocity" data-apply="el_1" data-variable="velocity" data-name="fe_velocity">
                                                        <option value="none" 
                                                        <?php
                                                        if (isset($fe_velocity_value) ) {
                                                            selected($fe_velocity_value, 'none');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('None', 'wpcustom-cursors'); ?></option>
                                                        <option value="xy" 
                                                        <?php
                                                        if (isset($fe_velocity_value) ) {
                                                            selected($fe_velocity_value, 'xy');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('X,Y', 'wpcustom-cursors'); ?></option>
                                                        <option value="resize" 
                                                        <?php
                                                        if (isset($fe_velocity_value) ) {
                                                            selected($fe_velocity_value, 'resize');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Resize', 'wpcustom-cursors'); ?></option>
                                                    </select>
                                                </div>

                                                <!-- Z-index -->
                                                <label for="fe_zindex" class="title-normal mt-3"><?php echo esc_html__('Z-index:', 'wpcustom-cursors'); ?></label>

                                                <div class="d-flex align-items-center mt-2">
                                                    <input type="range" data-apply="el_1" data-variable="zindex" class="form-range me-2" min="100" max="1000000" id="fe_zindex_range" value="<?php echo esc_attr($fe_zindex_value); ?>">
                                                    <input type="number" data-apply="el_1" data-variable="zindex" min="100" max="1000000" id="fe_zindex" class="number-input" value="<?php echo esc_attr($fe_zindex_value); ?>" data-name="fe_zindex">
                                                </div>

                                                <!-- Backdrop Filter -->
                                                <div class="form-group">
                                                    <label for="fe_backdrop" class="title-normal mt-3"><?php echo esc_html__('Backdrop Filter:', 'wpcustom-cursors'); ?></label>
                                                    <select class="form-control mt-2" id="fe_backdrop" data-apply="el_1" data-variable="backdrop" data-name="fe_backdrop">
                                                        <option value="none" 
                                                        <?php
                                                        if (isset($fe_backdrop_value) ) {
                                                            selected($fe_backdrop_value, 'none');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('None', 'wpcustom-cursors'); ?></option>
                                                        <option value="blur" 
                                                        <?php
                                                        if (isset($fe_backdrop_value) ) {
                                                            selected($fe_backdrop_value, 'blur');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Blur', 'wpcustom-cursors'); ?></option>
                                                        <option value="brightness" 
                                                        <?php
                                                        if (isset($fe_backdrop_value) ) {
                                                            selected($fe_backdrop_value, 'brightness');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Brightness', 'wpcustom-cursors'); ?></option>
                                                        <option value="contrast" 
                                                        <?php
                                                        if (isset($fe_backdrop_value) ) {
                                                            selected($fe_backdrop_value, 'contrast');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Contrast', 'wpcustom-cursors'); ?></option>
                                                        <option value="drop-shadow" 
                                                        <?php
                                                        if (isset($fe_backdrop_value) ) {
                                                            selected($fe_backdrop_value, 'drop-shadow');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Drop Shadow', 'wpcustom-cursors'); ?></option>
                                                        <option value="grayscale" 
                                                        <?php
                                                        if (isset($fe_backdrop_value) ) {
                                                            selected($fe_backdrop_value, 'grayscale');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Grayscale', 'wpcustom-cursors'); ?></option>
                                                        <option value="hue-rotate" 
                                                        <?php
                                                        if (isset($fe_backdrop_value) ) {
                                                            selected($fe_backdrop_value, 'hue-rotate');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Hue Rotate', 'wpcustom-cursors'); ?></option>
                                                        <option value="invert" 
                                                        <?php
                                                        if (isset($fe_backdrop_value) ) {
                                                            selected($fe_backdrop_value, 'invert');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Invert', 'wpcustom-cursors'); ?></option>
                                                        <option value="opacity" 
                                                        <?php
                                                        if (isset($fe_backdrop_value) ) {
                                                            selected($fe_backdrop_value, 'opacity');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Opacity', 'wpcustom-cursors'); ?></option>
                                                        <option value="sepia" 
                                                        <?php
                                                        if (isset($fe_backdrop_value) ) {
                                                            selected($fe_backdrop_value, 'sepia');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Sepia', 'wpcustom-cursors'); ?></option>
                                                        <option value="saturate" 
                                                        <?php
                                                        if (isset($fe_backdrop_value) ) {
                                                            selected($fe_backdrop_value, 'saturate');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Saturate', 'wpcustom-cursors'); ?></option>
                                                        <option value="revert" 
                                                        <?php
                                                        if (isset($fe_backdrop_value) ) {
                                                            selected($fe_backdrop_value, 'revert');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Revert', 'wpcustom-cursors'); ?></option>
                                                    </select>
                                                </div>

                                                <!-- Backdrop Filter Value -->
                                                <div class="form-floating mt-3">
                                                    <input type="text" class="form-control" id="fe_backdrop_amount" placeholder="<?php echo esc_html__('e.g. 2px', 'wpcustom-cursors'); ?>" value="<?php echo esc_attr($fe_backdrop_amount_value); ?>" data-name="fe_backdrop_value">
                                                    <label for="fe_backdrop_amount"><?php echo esc_html__('Value with unit e.g. 2px', 'wpcustom-cursors'); ?></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed flex-column align-items-start" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                            <h4 class="h5"><?php echo esc_html__('Outer Circle', 'wpcustom-cursors'); ?></h4>
                                        </button>
                                    </h2>
                                    <div id="flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#shapeAccordionOptions">
                                        <div class="accordion-body">
                                            <!-- Second Element Options - Normal -->
                                            <div id="shape_2_options">
                                                <!-- Width -->
                                                <label for="se_width" class="title-normal mt-3"><?php echo esc_html__('Width:', 'wpcustom-cursors'); ?></label>

                                                <div class="d-flex align-items-center mt-2">
                                                    <input type="range" data-apply="el_2" data-variable="width" data-unit="px" class="form-range me-2" min="1" max="300" id="se_width_range" value="<?php echo esc_attr($se_width_value); ?>">
                                                    <input type="number" data-apply="el_2" data-variable="width" data-unit="px" min="1" max="300" id="se_width" class="number-input" value="<?php echo esc_attr($se_width_value); ?>" data-name="se_width">
                                                    <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                                                </div>

                                                <!-- Height -->
                                                <label for="se_height" class="title-normal mt-3"><?php echo esc_html__('Height:', 'wpcustom-cursors'); ?></label>

                                                <div class="d-flex align-items-center mt-2">
                                                    <input type="range" data-apply="el_2" data-variable="height" data-unit="px" class="form-range me-2" min="1" max="300" id="se_height_range" value="<?php echo esc_attr($se_height_value); ?>">
                                                    <input type="number" data-apply="el_2" data-variable="height" data-unit="px" min="1" max="300" id="se_height" class="number-input" value="<?php echo esc_attr($se_height_value); ?>" data-name="se_height">
                                                    <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                                                </div>

                                                <!-- Color -->
                                                <div class="title-normal mt-3">
                                                    <?php echo esc_html__('Color:', 'wpcustom-cursors'); ?>
                                                </div>
                                                <div class="color_select form-group mt-2">
                                                    <label class="w-100">
                                                        <input type='text' class="form-control basic wp-custom-cursor-color-picker" data-apply="el_2" data-variable="color" id="se_color" value="<?php echo esc_attr($se_color_value); ?>" data-name="se_color">
                                                    </label>
                                                </div>

                                                <!-- Border -->
                                                <label for="se_border" class="title-normal mt-3"><?php echo esc_html__('Border Width:', 'wpcustom-cursors'); ?></label>

                                                <div class="d-flex align-items-center mt-2">
                                                    <input type="range" data-apply="el_2" data-variable="border" data-unit="px" class="form-range me-2" min="0" max="100" id="se_border_range" value="<?php echo esc_attr($se_border_value); ?>">
                                                    <input type="number" data-apply="el_2" data-variable="border" data-unit="px" min="0" max="100" id="se_border" class="number-input" value="<?php echo esc_attr($se_border_value); ?>" data-name="se_border_width">
                                                    <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                                                </div>

                                                <!-- Radius -->
                                                <label for="se_radius" class="title-normal mt-3"><?php echo esc_html__('Border Radius:', 'wpcustom-cursors'); ?></label>

                                                <div class="d-flex align-items-center mt-2">
                                                    <input type="range" data-apply="el_2" data-variable="radius" data-unit="px" class="form-range me-2" min="0" max="1000" id="se_radius_range" value="<?php echo esc_attr($se_radius_value); ?>">
                                                    <input type="number" data-apply="el_2" data-variable="radius" data-unit="px" min="0" max="1000" id="se_radius" class="number-input" value="<?php echo esc_attr($se_radius_value); ?>" data-name="se_radius">
                                                    <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                                                </div>

                                                <!-- Border Color -->
                                                <div class="title-normal mt-3">
                                                    <?php echo esc_html__('Border Color:', 'wpcustom-cursors'); ?>
                                                </div>
                                                <div class="color_select form-group mt-2">
                                                    <label class="w-100">
                                                        <input type='text' class="form-control basic wp-custom-cursor-color-picker" data-apply="el_2" data-variable="border-color" id="se_border_color" value="<?php echo esc_attr($se_border_color_value); ?>" data-name="se_border_color">
                                                    </label>
                                                </div>

                                                <!-- Transition Duration -->
                                                <label for="se_duration" class="title-normal mt-3"><?php echo esc_html__('Transition Duration:', 'wpcustom-cursors'); ?></label>

                                                <div class="d-flex align-items-center mt-2">
                                                    <input type="range" data-apply="el_2" data-variable="duration" data-unit="ms" class="form-range me-2" min="0" max="1000" id="se_duration_range" value="<?php echo esc_attr($se_duration_value); ?>">
                                                    <input type="number" data-apply="el_2" data-variable="duration" data-unit="ms" min="0" max="1000" id="se_duration" class="number-input" value="<?php echo esc_attr($se_duration_value); ?>" data-name="se_duration">
                                                    <span class="ms-2 small"><?php echo esc_html__('MS', 'wpcustom-cursors'); ?></span>
                                                </div>

                                                <!-- Transition Timing Function -->
                                                <div class="form-group">
                                                    <label for="se_timing" class="title-normal mt-3"><?php echo esc_html__('Transition Timing Function:', 'wpcustom-cursors'); ?></label>
                                                    <select class="form-control mt-2" id="se_timing" data-apply="el_2" data-variable="timing" data-name="se_timing">
                                                        <option value="ease" 
                                                        <?php
                                                        if (isset($se_timing_value) ) {
                                                            selected($se_timing_value, 'ease');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Ease', 'wpcustom-cursors'); ?></option>
                                                        <option value="ease-in" 
                                                        <?php
                                                        if (isset($se_timing_value) ) {
                                                            selected($se_timing_value, 'ease-in');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Ease In', 'wpcustom-cursors'); ?></option>
                                                        <option value="ease-out" 
                                                        <?php
                                                        if (isset($se_timing_value) ) {
                                                            selected($se_timing_value, 'ease-out');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Ease Out', 'wpcustom-cursors'); ?></option>
                                                        <option value="ease-in-out" 
                                                        <?php
                                                        if (isset($se_timing_value) ) {
                                                            selected($se_timing_value, 'ease-in-out');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Ease In Out', 'wpcustom-cursors'); ?></option>
                                                        <option value="linear" 
                                                        <?php
                                                        if (isset($se_timing_value) ) {
                                                            selected($se_timing_value, 'linear');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Linear', 'wpcustom-cursors'); ?></option>
                                                    </select>
                                                </div>

                                                <!-- Blending Mode -->
                                                <div class="form-group">
                                                    <label for="se_blending" class="title-normal mt-3"><?php echo esc_html__('Blending Mode:', 'wpcustom-cursors'); ?></label>
                                                    <select class="form-control mt-2" id="se_blending" data-apply="el_2" data-variable="blending" data-name="se_blending">
                                                        <option value="normal" 
                                                        <?php
                                                        if (isset($se_blending_value) ) {
                                                            selected($se_blending_value, 'normal');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Normal', 'wpcustom-cursors'); ?></option>
                                                        <option value="multiply" 
                                                        <?php
                                                        if (isset($se_blending_value) ) {
                                                            selected($se_blending_value, 'multiply');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Multiply', 'wpcustom-cursors'); ?></option>
                                                        <option value="screen" 
                                                        <?php
                                                        if (isset($se_blending_value) ) {
                                                            selected($se_blending_value, 'screen');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Screen', 'wpcustom-cursors'); ?></option>
                                                        <option value="overlay" 
                                                        <?php
                                                        if (isset($se_blending_value) ) {
                                                            selected($se_blending_value, 'overlay');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Overlay', 'wpcustom-cursors'); ?></option>
                                                        <option value="darken" 
                                                        <?php
                                                        if (isset($se_blending_value) ) {
                                                            selected($se_blending_value, 'darken');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Darken', 'wpcustom-cursors'); ?></option>
                                                        <option value="lighten" 
                                                        <?php
                                                        if (isset($se_blending_value) ) {
                                                            selected($se_blending_value, 'lighten');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Lighten', 'wpcustom-cursors'); ?></option>
                                                        <option value="color-dodge" 
                                                        <?php
                                                        if (isset($se_blending_value) ) {
                                                            selected($se_blending_value, 'color-dodge');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Color Dodge', 'wpcustom-cursors'); ?></option>
                                                        <option value="color-burn" 
                                                        <?php
                                                        if (isset($se_blending_value) ) {
                                                            selected($se_blending_value, 'color-burn');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Color Burn', 'wpcustom-cursors'); ?></option>
                                                        <option value="hard-light" 
                                                        <?php
                                                        if (isset($se_blending_value) ) {
                                                            selected($se_blending_value, 'hard-light');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Hard Light', 'wpcustom-cursors'); ?></option>
                                                        <option value="soft-light" 
                                                        <?php
                                                        if (isset($se_blending_value) ) {
                                                            selected($se_blending_value, 'soft-light');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Soft Light', 'wpcustom-cursors'); ?></option>
                                                        <option value="difference" 
                                                        <?php
                                                        if (isset($se_blending_value) ) {
                                                            selected($se_blending_value, 'difference');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Difference', 'wpcustom-cursors'); ?></option>
                                                        <option value="exclusion" 
                                                        <?php
                                                        if (isset($se_blending_value) ) {
                                                            selected($se_blending_value, 'exclusion');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Exclusion', 'wpcustom-cursors'); ?></option>
                                                        <option value="hue" 
                                                        <?php
                                                        if (isset($se_blending_value) ) {
                                                            selected($se_blending_value, 'hue');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Hue', 'wpcustom-cursors'); ?></option>
                                                        <option value="saturation" 
                                                        <?php
                                                        if (isset($se_blending_value) ) {
                                                            selected($se_blending_value, 'saturation');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Saturation', 'wpcustom-cursors'); ?></option>
                                                        <option value="color" 
                                                        <?php
                                                        if (isset($se_blending_value) ) {
                                                            selected($se_blending_value, 'color');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Color', 'wpcustom-cursors'); ?></option>
                                                        <option value="luminosity" 
                                                        <?php
                                                        if (isset($se_blending_value) ) {
                                                            selected($se_blending_value, 'luminosity');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Luminosity', 'wpcustom-cursors'); ?></option>
                                                    </select>
                                                </div>

                                                <!-- Velocity -->
                                                <div class="form-group">
                                                    <label for="se_velocity" class="title-normal mt-3"><?php echo esc_html__('Velocity:', 'wpcustom-cursors'); ?></label>
                                                    <select class="form-control mt-2" id="se_velocity" data-apply="el_2" data-variable="velocity" data-name="se_velocity">
                                                        <option value="none" 
                                                        <?php
                                                        if (isset($se_velocity_value) ) {
                                                            selected($se_velocity_value, 'none');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('None', 'wpcustom-cursors'); ?></option>
                                                        <option value="xy" 
                                                        <?php
                                                        if (isset($se_velocity_value) ) {
                                                            selected($se_velocity_value, 'xy');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('X,Y', 'wpcustom-cursors'); ?></option>
                                                        <option value="resize" 
                                                        <?php
                                                        if (isset($se_velocity_value) ) {
                                                            selected($se_velocity_value, 'resize');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Resize', 'wpcustom-cursors'); ?></option>
                                                    </select>
                                                </div>

                                                <!-- Z-index -->
                                                <label for="se_zindex" class="title-normal mt-3"><?php echo esc_html__('Z-index:', 'wpcustom-cursors'); ?></label>

                                                <div class="d-flex align-items-center mt-2">
                                                    <input type="range" data-apply="el_2" data-variable="zindex" class="form-range me-2" min="100" max="1000000" id="se_zindex_range" value="<?php echo esc_attr($se_zindex_value); ?>">
                                                    <input type="number" data-apply="el_2" data-variable="zindex" min="100" max="1000000" id="se_zindex" class="number-input" value="<?php echo esc_attr($se_zindex_value); ?>" data-name="se_zindex">
                                                </div>

                                                <!-- Backdrop Filter -->
                                                <div class="form-group">
                                                    <label for="se_backdrop" class="title-normal mt-3"><?php echo esc_html__('Backdrop Filter:', 'wpcustom-cursors'); ?></label>
                                                    <select class="form-control mt-2" id="se_backdrop" data-apply="el_2" data-variable="backdrop" data-name="se_backdrop">
                                                        <option value="none" 
                                                        <?php
                                                        if (isset($se_backdrop_value) ) {
                                                            selected($se_backdrop_value, 'none');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('None', 'wpcustom-cursors'); ?></option>
                                                        <option value="blur" 
                                                        <?php
                                                        if (isset($se_backdrop_value) ) {
                                                            selected($se_backdrop_value, 'blur');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Blur', 'wpcustom-cursors'); ?></option>
                                                        <option value="brightness" 
                                                        <?php
                                                        if (isset($se_backdrop_value) ) {
                                                            selected($se_backdrop_value, 'brightness');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Brightness', 'wpcustom-cursors'); ?></option>
                                                        <option value="contrast" 
                                                        <?php
                                                        if (isset($se_backdrop_value) ) {
                                                            selected($se_backdrop_value, 'contrast');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Contrast', 'wpcustom-cursors'); ?></option>
                                                        <option value="drop-shadow" 
                                                        <?php
                                                        if (isset($se_backdrop_value) ) {
                                                            selected($se_backdrop_value, 'drop-shadow');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Drop Shadow', 'wpcustom-cursors'); ?></option>
                                                        <option value="grayscale" 
                                                        <?php
                                                        if (isset($se_backdrop_value) ) {
                                                            selected($se_backdrop_value, 'grayscale');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Grayscale', 'wpcustom-cursors'); ?></option>
                                                        <option value="hue-rotate" 
                                                        <?php
                                                        if (isset($se_backdrop_value) ) {
                                                            selected($se_backdrop_value, 'hue-rotate');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Hue Rotate', 'wpcustom-cursors'); ?></option>
                                                        <option value="invert" 
                                                        <?php
                                                        if (isset($se_backdrop_value) ) {
                                                            selected($se_backdrop_value, 'invert');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Invert', 'wpcustom-cursors'); ?></option>
                                                        <option value="opacity" 
                                                        <?php
                                                        if (isset($se_backdrop_value) ) {
                                                            selected($se_backdrop_value, 'opacity');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Opacity', 'wpcustom-cursors'); ?></option>
                                                        <option value="sepia" 
                                                        <?php
                                                        if (isset($se_backdrop_value) ) {
                                                            selected($se_backdrop_value, 'sepia');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Sepia', 'wpcustom-cursors'); ?></option>
                                                        <option value="saturate" 
                                                        <?php
                                                        if (isset($se_backdrop_value) ) {
                                                            selected($se_backdrop_value, 'saturate');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Saturate', 'wpcustom-cursors'); ?></option>
                                                        <option value="revert" 
                                                        <?php
                                                        if (isset($se_backdrop_value) ) {
                                                            selected($se_backdrop_value, 'revert');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Revert', 'wpcustom-cursors'); ?></option>
                                                    </select>
                                                </div>

                                                <!-- Backdrop Filter Value -->
                                                <div class="form-floating mt-3">
                                                    <input type="text" class="form-control" id="se_backdrop_amount" placeholder="<?php echo esc_html__('e.g. 2px', 'wpcustom-cursors'); ?>" value="<?php echo esc_attr($se_backdrop_amount_value); ?>" data-name="se_backdrop_value">
                                                    <label for="se_backdrop_amount"><?php echo esc_html__('Value with unit e.g. 2px', 'wpcustom-cursors'); ?></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Hover Shape Options -->
                        <div id="hover_shape_options" style="
                        <?php
                        if ('shape' !== $hover_cursor_type_value || 'normal' === $normal_hover_state ) {
                            echo esc_attr('display: none;'); 
                        }
                        ?>
                        ">
                            <div class="row hover-elements-wrapper py-2 rounded">
                                <div class="col-md-12">
                                    <div class="error-message align-items-center text-danger mb-2">
                                        <i class="ri-error-warning-line me-1"></i><span class="small"><?php echo esc_html__('You need to select at least one!', 'wpcustom-cursors'); ?></span>
                                    </div>
                                    <div class="title-normal">
                                        <?php echo esc_html__('Where to show this hover cursor?', 'wpcustom-cursors'); ?>
                                    </div>
                                    <!-- Links -->
                                    <label class="toggler-wrapper mt-2 style-4"> 
                                        <span class="toggler-label"><?php echo esc_html__('On Links', 'wpcustom-cursors'); ?></span>
                                        <input type="checkbox" id="shape_hover_trigger_link" value="<?php echo esc_attr($hover_trigger_link_value); ?>" <?php checked($hover_trigger_link_value, 'on'); ?> data-name="links" data-default="<?php echo esc_attr($hover_trigger_link_value); ?>" data-off="shape_hover_trigger_custom" data-hide-error="hover-elements-wrapper, show-error">
                                        <div class="toggler-slider">
                                            <div class="toggler-knob"></div>
                                        </div>
                                    </label>

                                    <!-- Buttons -->
                                    <label class="toggler-wrapper mt-2 style-4"> 
                                        <span class="toggler-label"><?php echo esc_html__('On Buttons', 'wpcustom-cursors'); ?></span>
                                        <input type="checkbox" id="shape_hover_trigger_button" value="<?php echo esc_attr($hover_trigger_button_value); ?>" <?php checked($hover_trigger_button_value, 'on'); ?> data-name="buttons" data-default="<?php echo esc_attr($hover_trigger_button_value); ?>" data-off="shape_hover_trigger_custom" data-hide-error="hover-elements-wrapper, show-error">
                                        <div class="toggler-slider">
                                            <div class="toggler-knob"></div>
                                        </div>
                                    </label>

                                    <!-- Images -->
                                    <label class="toggler-wrapper mt-2 style-4"> 
                                        <span class="toggler-label"><?php echo esc_html__('On Images', 'wpcustom-cursors'); ?></span>
                                        <input type="checkbox" id="shape_hover_trigger_image" value="<?php echo esc_attr($hover_trigger_image_value); ?>" <?php checked($hover_trigger_image_value, 'on'); ?> data-name="images" data-default="<?php echo esc_attr($hover_trigger_image_value); ?>" data-off="shape_hover_trigger_custom" data-hide-error="hover-elements-wrapper, show-error">
                                        <div class="toggler-slider">
                                            <div class="toggler-knob"></div>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <!-- Custom Element -->
                                    <label class="toggler-wrapper style-4"> 
                                        <span class="toggler-label"><?php echo esc_html__('On Custom Element', 'wpcustom-cursors'); ?></span>
                                        <input type="checkbox" id="shape_hover_trigger_custom" value="<?php echo esc_attr($hover_trigger_custom_value); ?>" <?php checked($hover_trigger_custom_value, 'on'); ?> data-toggle="hover-selector-wrapper" data-name="custom" data-default="<?php echo esc_attr($hover_trigger_custom_value); ?>" data-off="shape_hover_trigger_image,shape_hover_trigger_button,shape_hover_trigger_link" data-hide-error="hover-elements-wrapper, show-error">
                                        <div class="toggler-slider">
                                            <div class="toggler-knob"></div>
                                        </div>
                                    </label>

                                    <!-- Custom Hover Selector -->
                                    <div class="input-group mt-2 hover-selector-wrapper" id="shape_hover_trigger_custom_wrapper" style="
                                    <?php
                                    if ('off' === $hover_trigger_custom_value ) {
                                        echo esc_attr('display: none;');
                                    }
                                    ?>
                                    ">
                                        <span class="input-group-text" id="shape_custom_hover_selector"><?php echo esc_html__('CSS Selector', 'wpcustom-cursors'); ?></span>
                                        <input type="text" value="<?php echo esc_attr($hover_trigger_selector_value); ?>" class="form-control" placeholder="<?php echo esc_html__('e.g. .btn', 'wpcustom-cursors'); ?>" aria-label="<?php echo esc_html__('Selector', 'wpcustom-cursors'); ?>" aria-describedby="shape_custom_hover_selector" id="shape_hover_trigger_selector" data-name="selector" data-default="<?php echo esc_attr($hover_trigger_selector_value); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="accordion accordion-flush mt-3" id="hoverShapeAccordionOptions">
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed flex-column align-items-start" type="button" data-bs-toggle="collapse" data-bs-target="#hover_flush-collapseOne" aria-expanded="false" aria-controls="hover_flush-collapseOne">
                                            <h4 class="h5"><?php echo esc_html__('Inner Circle', 'wpcustom-cursors'); ?></h4>
                                        </button>
                                    </h2>
                                    <div id="hover_flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#hoverShapeAccordionOptions">
                                        <div class="accordion-body">
                                            <!-- First Element Options - Normal -->
                                            <div id="hover_shape_1_options" >
                                                <!-- Width -->
                                                <label for="hover_fe_width" class="title-normal mt-3"><?php echo esc_html__('Width:', 'wpcustom-cursors'); ?></label>

                                                <div class="d-flex align-items-center mt-2">
                                                    <input type="range" data-apply="hover_el_1" data-variable="width" data-unit="px" class="form-range me-2" min="1" max="300" id="hover_fe_width_range" value="<?php echo esc_attr($hover_fe_width_value); ?>">
                                                    <input type="number" data-apply="hover_el_1" data-variable="width" data-unit="px" min="1" max="300" id="hover_fe_width" class="number-input" value="<?php echo esc_attr($hover_fe_width_value); ?>" data-name="hover_fe_width" data-default="<?php echo esc_attr($hover_fe_width_value); ?>">
                                                    <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                                                </div>

                                                <!-- Height -->
                                                <label for="hover_fe_height" class="title-normal mt-3"><?php echo esc_html__('Height:', 'wpcustom-cursors'); ?></label>

                                                <div class="d-flex align-items-center mt-2">
                                                    <input type="range" data-apply="hover_el_1" data-variable="height" data-unit="px" class="form-range me-2" min="1" max="300" id="hover_fe_height_range" value="<?php echo esc_attr($hover_fe_height_value); ?>">
                                                    <input type="number" data-apply="hover_el_1" data-variable="height" data-unit="px" min="1" max="300" id="hover_fe_height" class="number-input" value="<?php echo esc_attr($hover_fe_height_value); ?>" data-name="hover_fe_height" data-default="<?php echo esc_attr($hover_fe_height_value); ?>">
                                                    <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                                                </div>

                                                <!-- Color -->
                                                <div class="title-normal mt-3">
                                                    <?php echo esc_html__('Color:', 'wpcustom-cursors'); ?>
                                                </div>
                                                <div class="color_select form-group mt-2">
                                                    <label class="w-100">
                                                        <input type='text' class="form-control basic wp-custom-cursor-color-picker" data-apply="hover_el_1" data-variable="color" id="hover_fe_color" value="<?php echo esc_attr($hover_fe_color_value); ?>" data-name="hover_fe_color" data-default="<?php echo esc_attr($hover_fe_color_value); ?>">
                                                    </label>
                                                </div>

                                                <!-- Border -->
                                                <label for="hover_fe_border" class="title-normal mt-3"><?php echo esc_html__('Border Width:', 'wpcustom-cursors'); ?></label>

                                                <div class="d-flex align-items-center mt-2">
                                                    <input type="range" data-apply="hover_el_1" data-variable="border" data-unit="px" class="form-range me-2" min="0" max="100" id="hover_fe_border_range" value="<?php echo esc_attr($hover_fe_border_value); ?>">
                                                    <input type="number" data-apply="hover_el_1" data-variable="border" data-unit="px" min="0" max="100" id="hover_fe_border" class="number-input" value="<?php echo esc_attr($hover_fe_border_value); ?>" data-name="hover_fe_border_width" data-default="<?php echo esc_attr($hover_fe_border_value); ?>">
                                                    <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                                                </div>

                                                <!-- Radius -->
                                                <label for="hover_fe_radius" class="title-normal mt-3"><?php echo esc_html__('Border Radius:', 'wpcustom-cursors'); ?></label>

                                                <div class="d-flex align-items-center mt-2">
                                                    <input type="range" data-apply="hover_el_1" data-variable="radius" data-unit="px" class="form-range me-2" min="0" max="1000" id="hover_fe_radius_range" value="<?php echo esc_attr($hover_fe_radius_value); ?>">
                                                    <input type="number" data-apply="hover_el_1" data-variable="radius" data-unit="px" min="0" max="1000" id="hover_fe_radius" class="number-input" value="<?php echo esc_attr($hover_fe_radius_value); ?>" data-name="hover_fe_radius" data-default="<?php echo esc_attr($hover_fe_radius_value); ?>">
                                                    <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                                                </div>

                                                <!-- Border Color -->
                                                <div class="title-normal mt-3">
                                                    <?php echo esc_html__('Border Color:', 'wpcustom-cursors'); ?>
                                                </div>
                                                <div class="color_select form-group mt-2">
                                                    <label class="w-100">
                                                        <input type='text' class="form-control basic wp-custom-cursor-color-picker" data-apply="hover_el_1" data-variable="border-color" id="hover_fe_border_color" value="<?php echo esc_attr($hover_fe_border_color_value); ?>" data-name="hover_fe_border_color" data-default="<?php echo esc_attr($hover_fe_border_color_value); ?>">
                                                    </label>
                                                </div>

                                                <!-- Transition Duration -->
                                                <label for="hover_fe_duration" class="title-normal mt-3"><?php echo esc_html__('Transition Duration:', 'wpcustom-cursors'); ?></label>

                                                <div class="d-flex align-items-center mt-2">
                                                    <input type="range" data-apply="hover_el_1" data-variable="duration" data-unit="ms" class="form-range me-2" min="0" max="1000" id="hover_fe_duration_range" value="<?php echo esc_attr($hover_fe_duration_value); ?>">
                                                    <input type="number" data-apply="hover_el_1" data-variable="duration" data-unit="ms" min="0" max="1000" id="hover_fe_duration" class="number-input" value="<?php echo esc_attr($hover_fe_duration_value); ?>" data-name="hover_fe_duration" data-default="<?php echo esc_attr($hover_fe_duration_value); ?>">
                                                    <span class="ms-2 small"><?php echo esc_html__('MS', 'wpcustom-cursors'); ?></span>
                                                </div>

                                                <!-- Transition Timing Function -->
                                                <div class="form-group">
                                                    <label for="hover_fe_timing" class="title-normal mt-3"><?php echo esc_html__('Transition Timing Function:', 'wpcustom-cursors'); ?></label>
                                                    <select class="form-control mt-2" id="hover_fe_timing" data-apply="hover_el_1" data-variable="timing" data-name="hover_fe_timing" data-default="<?php echo esc_attr($hover_fe_timing_value); ?>">
                                                        <option value="ease" 
                                                        <?php
                                                        if (isset($hover_fe_timing_value) ) {
                                                            selected($hover_fe_timing_value, 'ease');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Ease', 'wpcustom-cursors'); ?></option>
                                                        <option value="ease-in" 
                                                        <?php
                                                        if (isset($hover_fe_timing_value) ) {
                                                            selected($hover_fe_timing_value, 'ease-in');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Ease In', 'wpcustom-cursors'); ?></option>
                                                        <option value="ease-out" 
                                                        <?php
                                                        if (isset($hover_fe_timing_value) ) {
                                                            selected($hover_fe_timing_value, 'ease-out');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Ease Out', 'wpcustom-cursors'); ?></option>
                                                        <option value="ease-in-out" 
                                                        <?php
                                                        if (isset($hover_fe_timing_value) ) {
                                                            selected($hover_fe_timing_value, 'ease-in-out');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Ease In Out', 'wpcustom-cursors'); ?></option>
                                                        <option value="linear" 
                                                        <?php
                                                        if (isset($hover_fe_timing_value) ) {
                                                            selected($hover_fe_timing_value, 'linear');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Linear', 'wpcustom-cursors'); ?></option>
                                                    </select>
                                                </div>

                                                <!-- Blending Mode -->
                                                <div class="form-group">
                                                    <label for="hover_fe_blending" class="title-normal mt-3"><?php echo esc_html__('Blending Mode:', 'wpcustom-cursors'); ?></label>
                                                    <select class="form-control mt-2" id="hover_fe_blending" data-apply="hover_el_1" data-variable="blending" data-name="hover_fe_blending" data-default="<?php echo esc_attr($hover_fe_blending_value); ?>">
                                                        <option value="normal" 
                                                        <?php
                                                        if (isset($hover_fe_blending_value) ) {
                                                            selected($hover_fe_blending_value, 'normal');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Normal', 'wpcustom-cursors'); ?></option>
                                                        <option value="multiply" 
                                                        <?php
                                                        if (isset($hover_fe_blending_value) ) {
                                                            selected($hover_fe_blending_value, 'multiply');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Multiply', 'wpcustom-cursors'); ?></option>
                                                        <option value="screen" 
                                                        <?php
                                                        if (isset($hover_fe_blending_value) ) {
                                                            selected($hover_fe_blending_value, 'screen');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Screen', 'wpcustom-cursors'); ?></option>
                                                        <option value="overlay" 
                                                        <?php
                                                        if (isset($hover_fe_blending_value) ) {
                                                            selected($hover_fe_blending_value, 'overlay');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Overlay', 'wpcustom-cursors'); ?></option>
                                                        <option value="darken" 
                                                        <?php
                                                        if (isset($hover_fe_blending_value) ) {
                                                            selected($hover_fe_blending_value, 'darken');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Darken', 'wpcustom-cursors'); ?></option>
                                                        <option value="lighten" 
                                                        <?php
                                                        if (isset($hover_fe_blending_value) ) {
                                                            selected($hover_fe_blending_value, 'lighten');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Lighten', 'wpcustom-cursors'); ?></option>
                                                        <option value="color-dodge" 
                                                        <?php
                                                        if (isset($hover_fe_blending_value) ) {
                                                            selected($hover_fe_blending_value, 'color-dodge');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Color Dodge', 'wpcustom-cursors'); ?></option>
                                                        <option value="color-burn" 
                                                        <?php
                                                        if (isset($hover_fe_blending_value) ) {
                                                            selected($hover_fe_blending_value, 'color-burn');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Color Burn', 'wpcustom-cursors'); ?></option>
                                                        <option value="hard-light" 
                                                        <?php
                                                        if (isset($hover_fe_blending_value) ) {
                                                            selected($hover_fe_blending_value, 'hard-light');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Hard Light', 'wpcustom-cursors'); ?></option>
                                                        <option value="soft-light" 
                                                        <?php
                                                        if (isset($hover_fe_blending_value) ) {
                                                            selected($hover_fe_blending_value, 'soft-light');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Soft Light', 'wpcustom-cursors'); ?></option>
                                                        <option value="difference" 
                                                        <?php
                                                        if (isset($hover_fe_blending_value) ) {
                                                            selected($hover_fe_blending_value, 'difference');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Difference', 'wpcustom-cursors'); ?></option>
                                                        <option value="exclusion" 
                                                        <?php
                                                        if (isset($hover_fe_blending_value) ) {
                                                            selected($hover_fe_blending_value, 'exclusion');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Exclusion', 'wpcustom-cursors'); ?></option>
                                                        <option value="hue" 
                                                        <?php
                                                        if (isset($hover_fe_blending_value) ) {
                                                            selected($hover_fe_blending_value, 'hue');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Hue', 'wpcustom-cursors'); ?></option>
                                                        <option value="saturation" 
                                                        <?php
                                                        if (isset($hover_fe_blending_value) ) {
                                                            selected($hover_fe_blending_value, 'saturation');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Saturation', 'wpcustom-cursors'); ?></option>
                                                        <option value="color" 
                                                        <?php
                                                        if (isset($hover_fe_blending_value) ) {
                                                            selected($hover_fe_blending_value, 'color');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Color', 'wpcustom-cursors'); ?></option>
                                                        <option value="luminosity" 
                                                        <?php
                                                        if (isset($hover_fe_blending_value) ) {
                                                            selected($hover_fe_blending_value, 'luminosity');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Luminosity', 'wpcustom-cursors'); ?></option>
                                                    </select>
                                                </div>

                                                <!-- Velocity -->
                                                <div class="form-group">
                                                    <label for="hover_fe_velocity" class="title-normal mt-3"><?php echo esc_html__('Velocity:', 'wpcustom-cursors'); ?></label>
                                                    <select class="form-control mt-2" id="hover_fe_velocity" data-apply="hover_el_1" data-variable="velocity" data-name="hover_fe_velocity" data-default="<?php echo esc_attr($hover_fe_velocity_value); ?>">
                                                        <option value="none" 
                                                        <?php
                                                        if (isset($hover_fe_velocity_value) ) {
                                                            selected($hover_fe_velocity_value, 'none');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('None', 'wpcustom-cursors'); ?></option>
                                                        <option value="xy" 
                                                        <?php
                                                        if (isset($hover_fe_velocity_value) ) {
                                                            selected($hover_fe_velocity_value, 'xy');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('X,Y', 'wpcustom-cursors'); ?></option>
                                                        <option value="resize" 
                                                        <?php
                                                        if (isset($hover_fe_velocity_value) ) {
                                                            selected($hover_fe_velocity_value, 'resize');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Resize', 'wpcustom-cursors'); ?></option>
                                                    </select>
                                                </div>

                                                <!-- Z-index -->
                                                <label for="hover_fe_zindex" class="title-normal mt-3"><?php echo esc_html__('Z-index:', 'wpcustom-cursors'); ?></label>

                                                <div class="d-flex align-items-center mt-2">
                                                    <input type="range" data-apply="hover_el_1" data-variable="zindex" class="form-range me-2" min="100" max="1000000" id="hover_fe_zindex_range" value="<?php echo esc_attr($hover_fe_zindex_value); ?>">
                                                    <input type="number" data-apply="hover_el_1" data-variable="zindex" min="100" max="1000000" id="hover_fe_zindex" class="number-input" value="<?php echo esc_attr($hover_fe_zindex_value); ?>" data-name="hover_fe_zindex" data-default="<?php echo esc_attr($hover_fe_zindex_value); ?>">
                                                </div>

                                                <!-- Backdrop Filter -->
                                                <div class="form-group">
                                                    <label for="hover_fe_backdrop" class="title-normal mt-3"><?php echo esc_html__('Backdrop Filter:', 'wpcustom-cursors'); ?></label>
                                                    <select class="form-control mt-2" id="hover_fe_backdrop" data-apply="hover_el_1" data-variable="backdrop" data-name="hover_fe_backdrop" data-default="<?php echo esc_attr($hover_fe_backdrop_value); ?>">
                                                        <option value="none" 
                                                        <?php
                                                        if (isset($hover_fe_backdrop_value) ) {
                                                            selected($hover_fe_backdrop_value, 'none');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('None', 'wpcustom-cursors'); ?></option>
                                                        <option value="blur" 
                                                        <?php
                                                        if (isset($hover_fe_backdrop_value) ) {
                                                            selected($hover_fe_backdrop_value, 'blur');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Blur', 'wpcustom-cursors'); ?></option>
                                                        <option value="brightness" 
                                                        <?php
                                                        if (isset($hover_fe_backdrop_value) ) {
                                                            selected($hover_fe_backdrop_value, 'brightness');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Brightness', 'wpcustom-cursors'); ?></option>
                                                        <option value="contrast" 
                                                        <?php
                                                        if (isset($hover_fe_backdrop_value) ) {
                                                            selected($hover_fe_backdrop_value, 'contrast');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Contrast', 'wpcustom-cursors'); ?></option>
                                                        <option value="drop-shadow" 
                                                        <?php
                                                        if (isset($hover_fe_backdrop_value) ) {
                                                            selected($hover_fe_backdrop_value, 'drop-shadow');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Drop Shadow', 'wpcustom-cursors'); ?></option>
                                                        <option value="grayscale" 
                                                        <?php
                                                        if (isset($hover_fe_backdrop_value) ) {
                                                            selected($hover_fe_backdrop_value, 'grayscale');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Grayscale', 'wpcustom-cursors'); ?></option>
                                                        <option value="hue-rotate" 
                                                        <?php
                                                        if (isset($hover_fe_backdrop_value) ) {
                                                            selected($hover_fe_backdrop_value, 'hue-rotate');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Hue Rotate', 'wpcustom-cursors'); ?></option>
                                                        <option value="invert" 
                                                        <?php
                                                        if (isset($hover_fe_backdrop_value) ) {
                                                            selected($hover_fe_backdrop_value, 'invert');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Invert', 'wpcustom-cursors'); ?></option>
                                                        <option value="opacity" 
                                                        <?php
                                                        if (isset($hover_fe_backdrop_value) ) {
                                                            selected($hover_fe_backdrop_value, 'opacity');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Opacity', 'wpcustom-cursors'); ?></option>
                                                        <option value="sepia" 
                                                        <?php
                                                        if (isset($hover_fe_backdrop_value) ) {
                                                            selected($hover_fe_backdrop_value, 'sepia');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Sepia', 'wpcustom-cursors'); ?></option>
                                                        <option value="saturate" 
                                                        <?php
                                                        if (isset($hover_fe_backdrop_value) ) {
                                                            selected($hover_fe_backdrop_value, 'saturate');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Saturate', 'wpcustom-cursors'); ?></option>
                                                        <option value="revert" 
                                                        <?php
                                                        if (isset($hover_fe_backdrop_value) ) {
                                                            selected($hover_fe_backdrop_value, 'revert');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Revert', 'wpcustom-cursors'); ?></option>
                                                    </select>
                                                </div>

                                                <!-- Backdrop Filter Value -->
                                                <div class="form-floating mt-3">
                                                    <input type="text" class="form-control" id="hover_fe_backdrop_amount" placeholder="<?php echo esc_html__('e.g. 2px', 'wpcustom-cursors'); ?>" value="<?php echo esc_attr($hover_fe_backdrop_amount_value); ?>" data-name="hover_fe_backdrop_value" data-default="<?php echo esc_attr($hover_fe_backdrop_amount_value); ?>">
                                                    <label for="hover_fe_backdrop_amount"><?php echo esc_html__('Value with unit e.g. 2px', 'wpcustom-cursors'); ?></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed flex-column align-items-start" type="button" data-bs-toggle="collapse" data-bs-target="#hover_flush-collapseTwo" aria-expanded="false" aria-controls="hover_flush-collapseTwo">
                                            <h4 class="h5"><?php echo esc_html__('Outer Circle', 'wpcustom-cursors'); ?></h4>
                                        </button>
                                    </h2>
                                    <div id="hover_flush-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#hoverShapeAccordionOptions">
                                        <div class="accordion-body">
                                            <!-- Second Element Options - Normal -->
                                            <div id="hover_shape_2_options">
                                                <!-- Width -->
                                                <label for="hover_se_width" class="title-normal mt-3"><?php echo esc_html__('Width:', 'wpcustom-cursors'); ?></label>

                                                <div class="d-flex align-items-center mt-2">
                                                    <input type="range" data-apply="hover_el_2" data-variable="width" data-unit="px" class="form-range me-2" min="1" max="300" id="hover_se_width_range" value="<?php echo esc_attr($hover_se_width_value); ?>">
                                                    <input type="number" data-apply="hover_el_2" data-variable="width" data-unit="px" min="1" max="300" id="hover_se_width" class="number-input" value="<?php echo esc_attr($hover_se_width_value); ?>" data-name="hover_se_width" data-default="<?php echo esc_attr($hover_se_width_value); ?>">
                                                    <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                                                </div>

                                                <!-- Height -->
                                                <label for="hover_se_height" class="title-normal mt-3"><?php echo esc_html__('Height:', 'wpcustom-cursors'); ?></label>

                                                <div class="d-flex align-items-center mt-2">
                                                    <input type="range" data-apply="hover_el_2" data-variable="height" data-unit="px" class="form-range me-2" min="1" max="300" id="hover_se_height_range" value="<?php echo esc_attr($hover_se_height_value); ?>">
                                                    <input type="number" data-apply="hover_el_2" data-variable="height" data-unit="px" min="1" max="300" id="hover_se_height" class="number-input" value="<?php echo esc_attr($hover_se_height_value); ?>" data-name="hover_se_height" data-default="<?php echo esc_attr($hover_se_height_value); ?>">
                                                    <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                                                </div>

                                                <!-- Color -->
                                                <div class="title-normal mt-3">
                                                    <?php echo esc_html__('Color:', 'wpcustom-cursors'); ?>
                                                </div>
                                                <div class="color_select form-group mt-2">
                                                    <label class="w-100">
                                                        <input type='text' class="form-control basic wp-custom-cursor-color-picker" data-apply="hover_el_2" data-variable="color" id="hover_se_color" value="<?php echo esc_attr($hover_se_color_value); ?>" data-name="hover_se_color" data-default="<?php echo esc_attr($hover_se_color_value); ?>">
                                                    </label>
                                                </div>

                                                <!-- Border -->
                                                <label for="hover_se_border" class="title-normal mt-3"><?php echo esc_html__('Border Width:', 'wpcustom-cursors'); ?></label>

                                                <div class="d-flex align-items-center mt-2">
                                                    <input type="range" data-apply="hover_el_2" data-variable="border" data-unit="px" class="form-range me-2" min="0" max="100" id="hover_se_border_range" value="<?php echo esc_attr($hover_se_border_value); ?>">
                                                    <input type="number" data-apply="hover_el_2" data-variable="border" data-unit="px" min="0" max="100" id="hover_se_border" class="number-input" value="<?php echo esc_attr($hover_se_border_value); ?>" data-name="hover_se_border_width" data-default="<?php echo esc_attr($hover_se_border_value); ?>">
                                                    <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                                                </div>

                                                <!-- Radius -->
                                                <label for="hover_se_radius" class="title-normal mt-3"><?php echo esc_html__('Border Radius:', 'wpcustom-cursors'); ?></label>

                                                <div class="d-flex align-items-center mt-2">
                                                    <input type="range" data-apply="hover_el_2" data-variable="radius" data-unit="px" class="form-range me-2" min="0" max="1000" id="hover_se_radius_range" value="<?php echo esc_attr($hover_se_radius_value); ?>">
                                                    <input type="number" data-apply="hover_el_2" data-variable="radius" data-unit="px" min="0" max="1000" id="hover_se_radius" class="number-input" value="<?php echo esc_attr($hover_se_radius_value); ?>" data-name="hover_se_radius" data-default="<?php echo esc_attr($hover_se_radius_value); ?>">
                                                    <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                                                </div>

                                                <!-- Border Color -->
                                                <div class="title-normal mt-3">
                                                    <?php echo esc_html__('Border Color:', 'wpcustom-cursors'); ?>
                                                </div>
                                                <div class="color_select form-group mt-2">
                                                    <label class="w-100">
                                                        <input type='text' class="form-control basic wp-custom-cursor-color-picker" data-apply="hover_el_2" data-variable="border-color" id="hover_se_border_color" value="<?php echo esc_attr($hover_se_border_color_value); ?>" data-name="hover_se_border_color" data-default="<?php echo esc_attr($hover_se_border_color_value); ?>">
                                                    </label>
                                                </div>

                                                <!-- Transition Duration -->
                                                <label for="hover_se_duration" class="title-normal mt-3"><?php echo esc_html__('Transition Duration:', 'wpcustom-cursors'); ?></label>

                                                <div class="d-flex align-items-center mt-2">
                                                    <input type="range" data-apply="hover_el_2" data-variable="duration" data-unit="ms" class="form-range me-2" min="0" max="1000" id="hover_se_duration_range" value="<?php echo esc_attr($hover_se_duration_value); ?>">
                                                    <input type="number" data-apply="hover_el_2" data-variable="duration" data-unit="ms" min="0" max="1000" id="hover_se_duration" class="number-input" value="<?php echo esc_attr($hover_se_duration_value); ?>" data-name="hover_se_duration" data-default="<?php echo esc_attr($hover_se_duration_value); ?>">
                                                    <span class="ms-2 small"><?php echo esc_html__('MS', 'wpcustom-cursors'); ?></span>
                                                </div>

                                                <!-- Transition Timing Function -->
                                                <div class="form-group">
                                                    <label for="hover_se_timing" class="title-normal mt-3"><?php echo esc_html__('Transition Timing Function:', 'wpcustom-cursors'); ?></label>
                                                    <select class="form-control mt-2" id="hover_se_timing" data-apply="hover_el_2" data-variable="timing" data-name="hover_se_timing" data-default="<?php echo esc_attr($hover_se_timing_value); ?>">
                                                        <option value="ease" 
                                                        <?php
                                                        if (isset($hover_se_timing_value) ) {
                                                            selected($hover_se_timing_value, 'ease');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Ease', 'wpcustom-cursors'); ?></option>
                                                        <option value="ease-in" 
                                                        <?php
                                                        if (isset($hover_se_timing_value) ) {
                                                            selected($hover_se_timing_value, 'ease-in');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Ease In', 'wpcustom-cursors'); ?></option>
                                                        <option value="ease-out" 
                                                        <?php
                                                        if (isset($hover_se_timing_value) ) {
                                                            selected($hover_se_timing_value, 'ease-out');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Ease Out', 'wpcustom-cursors'); ?></option>
                                                        <option value="ease-in-out" 
                                                        <?php
                                                        if (isset($hover_se_timing_value) ) {
                                                            selected($hover_se_timing_value, 'ease-in-out');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Ease In Out', 'wpcustom-cursors'); ?></option>
                                                        <option value="linear" 
                                                        <?php
                                                        if (isset($hover_se_timing_value) ) {
                                                            selected($hover_se_timing_value, 'linear');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Linear', 'wpcustom-cursors'); ?></option>
                                                    </select>
                                                </div>

                                                <!-- Blending Mode -->
                                                <div class="form-group">
                                                    <label for="hover_se_blending" class="title-normal mt-3"><?php echo esc_html__('Blending Mode:', 'wpcustom-cursors'); ?></label>
                                                    <select class="form-control mt-2" id="hover_se_blending" data-apply="hover_el_2" data-variable="blending" data-name="hover_se_blending" data-default="<?php echo esc_attr($hover_se_blending_value); ?>">
                                                        <option value="normal" 
                                                        <?php
                                                        if (isset($hover_se_blending_value) ) {
                                                            selected($hover_se_blending_value, 'normal');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Normal', 'wpcustom-cursors'); ?></option>
                                                        <option value="multiply" 
                                                        <?php
                                                        if (isset($hover_se_blending_value) ) {
                                                            selected($hover_se_blending_value, 'multiply');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Multiply', 'wpcustom-cursors'); ?></option>
                                                        <option value="screen" 
                                                        <?php
                                                        if (isset($hover_se_blending_value) ) {
                                                            selected($hover_se_blending_value, 'screen');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Screen', 'wpcustom-cursors'); ?></option>
                                                        <option value="overlay" 
                                                        <?php
                                                        if (isset($hover_se_blending_value) ) {
                                                            selected($hover_se_blending_value, 'overlay');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Overlay', 'wpcustom-cursors'); ?></option>
                                                        <option value="darken" 
                                                        <?php
                                                        if (isset($hover_se_blending_value) ) {
                                                            selected($hover_se_blending_value, 'darken');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Darken', 'wpcustom-cursors'); ?></option>
                                                        <option value="lighten" 
                                                        <?php
                                                        if (isset($hover_se_blending_value) ) {
                                                            selected($hover_se_blending_value, 'lighten');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Lighten', 'wpcustom-cursors'); ?></option>
                                                        <option value="color-dodge" 
                                                        <?php
                                                        if (isset($hover_se_blending_value) ) {
                                                            selected($hover_se_blending_value, 'color-dodge');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Color Dodge', 'wpcustom-cursors'); ?></option>
                                                        <option value="color-burn" 
                                                        <?php
                                                        if (isset($hover_se_blending_value) ) {
                                                            selected($hover_se_blending_value, 'color-burn');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Color Burn', 'wpcustom-cursors'); ?></option>
                                                        <option value="hard-light" 
                                                        <?php
                                                        if (isset($hover_se_blending_value) ) {
                                                            selected($hover_se_blending_value, 'hard-light');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Hard Light', 'wpcustom-cursors'); ?></option>
                                                        <option value="soft-light" 
                                                        <?php
                                                        if (isset($hover_se_blending_value) ) {
                                                            selected($hover_se_blending_value, 'soft-light');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Soft Light', 'wpcustom-cursors'); ?></option>
                                                        <option value="difference" 
                                                        <?php
                                                        if (isset($hover_se_blending_value) ) {
                                                            selected($hover_se_blending_value, 'difference');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Difference', 'wpcustom-cursors'); ?></option>
                                                        <option value="exclusion" 
                                                        <?php
                                                        if (isset($hover_se_blending_value) ) {
                                                            selected($hover_se_blending_value, 'exclusion');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Exclusion', 'wpcustom-cursors'); ?></option>
                                                        <option value="hue" 
                                                        <?php
                                                        if (isset($hover_se_blending_value) ) {
                                                            selected($hover_se_blending_value, 'hue');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Hue', 'wpcustom-cursors'); ?></option>
                                                        <option value="saturation" 
                                                        <?php
                                                        if (isset($hover_se_blending_value) ) {
                                                            selected($hover_se_blending_value, 'saturation');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Saturation', 'wpcustom-cursors'); ?></option>
                                                        <option value="color" 
                                                        <?php
                                                        if (isset($hover_se_blending_value) ) {
                                                            selected($hover_se_blending_value, 'color');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Color', 'wpcustom-cursors'); ?></option>
                                                        <option value="luminosity" 
                                                        <?php
                                                        if (isset($hover_se_blending_value) ) {
                                                            selected($hover_se_blending_value, 'luminosity');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Luminosity', 'wpcustom-cursors'); ?></option>
                                                    </select>
                                                </div>

                                                <!-- Velocity -->
                                                <div class="form-group">
                                                    <label for="hover_se_velocity" class="title-normal mt-3"><?php echo esc_html__('Velocity:', 'wpcustom-cursors'); ?></label>
                                                    <select class="form-control mt-2" id="hover_se_velocity" data-apply="hover_el_2" data-variable="velocity" data-name="hover_se_velocity" data-default="<?php echo esc_attr($hover_se_velocity_value); ?>">
                                                        <option value="none" 
                                                        <?php
                                                        if (isset($hover_se_velocity_value) ) {
                                                            selected($hover_se_velocity_value, 'none');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('None', 'wpcustom-cursors'); ?></option>
                                                        <option value="xy" 
                                                        <?php
                                                        if (isset($hover_se_velocity_value) ) {
                                                            selected($hover_se_velocity_value, 'xy');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('X,Y', 'wpcustom-cursors'); ?></option>
                                                        <option value="resize" 
                                                        <?php
                                                        if (isset($hover_se_velocity_value) ) {
                                                            selected($hover_se_velocity_value, 'resize');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Resize', 'wpcustom-cursors'); ?></option>
                                                    </select>
                                                </div>

                                                <!-- Z-index -->
                                                <label for="hover_se_zindex" class="title-normal mt-3"><?php echo esc_html__('Z-index:', 'wpcustom-cursors'); ?></label>

                                                <div class="d-flex align-items-center mt-2">
                                                    <input type="range" data-apply="hover_el_2" data-variable="zindex" class="form-range me-2" min="100" max="1000000" id="hover_se_zindex_range" value="<?php echo esc_attr($hover_se_zindex_value); ?>">
                                                    <input type="number" data-apply="hover_el_2" data-variable="zindex" min="100" max="1000000" id="hover_se_zindex" class="number-input" value="<?php echo esc_attr($hover_se_zindex_value); ?>" data-name="hover_se_zindex" data-default="<?php echo esc_attr($hover_se_zindex_value); ?>">
                                                </div>

                                                <!-- Backdrop Filter -->
                                                <div class="form-group">
                                                    <label for="hover_se_backdrop" class="title-normal mt-3"><?php echo esc_html__('Backdrop Filter:', 'wpcustom-cursors'); ?></label>
                                                    <select class="form-control mt-2" id="hover_se_backdrop" data-apply="hover_el_2" data-variable="backdrop" data-name="hover_se_backdrop" data-default="<?php echo esc_attr($hover_se_backdrop_value); ?>">
                                                        <option value="none" 
                                                        <?php
                                                        if (isset($hover_se_backdrop_value) ) {
                                                            selected($hover_se_backdrop_value, 'none');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('None', 'wpcustom-cursors'); ?></option>
                                                        <option value="blur" 
                                                        <?php
                                                        if (isset($hover_se_backdrop_value) ) {
                                                            selected($hover_se_backdrop_value, 'blur');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Blur', 'wpcustom-cursors'); ?></option>
                                                        <option value="brightness" 
                                                        <?php
                                                        if (isset($hover_se_backdrop_value) ) {
                                                            selected($hover_se_backdrop_value, 'brightness');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Brightness', 'wpcustom-cursors'); ?></option>
                                                        <option value="contrast" 
                                                        <?php
                                                        if (isset($hover_se_backdrop_value) ) {
                                                            selected($hover_se_backdrop_value, 'contrast');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Contrast', 'wpcustom-cursors'); ?></option>
                                                        <option value="drop-shadow" 
                                                        <?php
                                                        if (isset($hover_se_backdrop_value) ) {
                                                            selected($hover_se_backdrop_value, 'drop-shadow');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Drop Shadow', 'wpcustom-cursors'); ?></option>
                                                        <option value="grayscale" 
                                                        <?php
                                                        if (isset($hover_se_backdrop_value) ) {
                                                            selected($hover_se_backdrop_value, 'grayscale');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Grayscale', 'wpcustom-cursors'); ?></option>
                                                        <option value="hue-rotate" 
                                                        <?php
                                                        if (isset($hover_se_backdrop_value) ) {
                                                            selected($hover_se_backdrop_value, 'hue-rotate');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Hue Rotate', 'wpcustom-cursors'); ?></option>
                                                        <option value="invert" 
                                                        <?php
                                                        if (isset($hover_se_backdrop_value) ) {
                                                            selected($hover_se_backdrop_value, 'invert');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Invert', 'wpcustom-cursors'); ?></option>
                                                        <option value="opacity" 
                                                        <?php
                                                        if (isset($hover_se_backdrop_value) ) {
                                                            selected($hover_se_backdrop_value, 'opacity');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Opacity', 'wpcustom-cursors'); ?></option>
                                                        <option value="sepia" 
                                                        <?php
                                                        if (isset($hover_se_backdrop_value) ) {
                                                            selected($hover_se_backdrop_value, 'sepia');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Sepia', 'wpcustom-cursors'); ?></option>
                                                        <option value="saturate" 
                                                        <?php
                                                        if (isset($hover_se_backdrop_value) ) {
                                                            selected($hover_se_backdrop_value, 'saturate');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Saturate', 'wpcustom-cursors'); ?></option>
                                                        <option value="revert" 
                                                        <?php
                                                        if (isset($hover_se_backdrop_value) ) {
                                                            selected($hover_se_backdrop_value, 'revert');
                                                        }
                                                        ?>
                                                        ><?php echo esc_html__('Revert', 'wpcustom-cursors'); ?></option>
                                                    </select>
                                                </div>

                                                <!-- Backdrop Filter Value -->
                                                <div class="form-floating mt-3">
                                                    <input type="text" class="form-control" id="hover_se_backdrop_amount" placeholder="<?php echo esc_html__('e.g. 2px', 'wpcustom-cursors'); ?>" value="<?php echo esc_attr($hover_se_backdrop_amount_value); ?>" data-name="hover_se_backdrop_value" data-default="<?php echo esc_attr($hover_se_backdrop_amount_value); ?>">
                                                    <label for="hover_se_backdrop_amount"><?php echo esc_html__('Value with unit e.g. 2px', 'wpcustom-cursors'); ?></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Image Cursor Options - Normal -->
                        <div id="normal_image_options" style="
                        <?php
                        if ('image' !== $cursor_type_value ) {
                            echo esc_attr('display: none;'); 
                        }
                        ?>
                        ">

                            <!-- Uploaded Image URL Input -->
                            <input type="hidden" id="normal_image_url_input" class="image-url-input" value="<?php echo esc_attr($image_url_value); ?>" data-name="image_url">

                            <!-- Click Point Inputs -->
                            <input type="hidden" id="normal_click_point_input" data-name="click_point" value="<?php echo esc_attr($click_point_value); ?>">

                            <!-- Image Width -->
                            <label for="normal_image_width" class="title-normal"><?php echo esc_html__('Width:', 'wpcustom-cursors'); ?></label>

                            <div class="d-flex align-items-center mt-2">
                                <input type="range" data-apply="normal_uploaded_image_wrapper" data-variable="image-width" data-unit="px" data-state="normal" class="form-range me-2 image-width-input" min="10" max="500" id="normal_image_width_range" value="<?php echo esc_attr($image_width_value); ?>">
                                <input type="number" data-apply="normal_uploaded_image_wrapper" data-variable="image-width" data-unit="px" data-state="normal" min="10" max="500" id="normal_image_width" class="number-input image-width-input" value="<?php echo esc_attr($image_width_value); ?>" data-name="width">
                                <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                                <input type="hidden" data-name="height" value="<?php echo esc_attr($image_height_value); ?>" id="normal_image_height" data-variable="image-height" data-unit="px">
                            </div>

                            <!-- Background -->
                            <label class="toggler-wrapper mt-3 style-4"> 
                                <span class="toggler-label"><?php echo esc_html__('Background', 'wpcustom-cursors'); ?></span>
                                <input type="checkbox" id="image_background" value="<?php echo esc_attr($image_background_value); ?>" <?php checked($image_background_value, 'on'); ?> data-name="background" data-toggle="image-bg-options" data-state="normal">
                                <div class="toggler-slider">
                                    <div class="toggler-knob"></div>
                                </div>
                            </label>

                            <div class="image-bg-options border p-4 rounded-3 mt-3" style="
                            <?php
                            if ('off' === $image_background_value ) {
                                echo esc_attr('display: none;');
                            }
                            ?>
                            ">
                                <!-- Background Color -->
                                <div class="title-normal mt-3">
                                    <?php echo esc_html__('Background Color:', 'wpcustom-cursors'); ?>
                                </div>
                                <div class="color_select form-group mt-2">
                                    <label class="w-100">
                                        <input type='text' data-apply="normal_uploaded_image_wrapper" data-variable="image-background-color" class="form-control basic wp-custom-cursor-color-picker" id="normal_image_background_color" value="<?php echo esc_attr($image_background_color_value); ?>" data-name="color">
                                    </label>
                                </div>

                                <!-- Radius -->
                                <label for="image_background_radius" class="title-normal mt-3"><?php echo esc_html__('Background Radius:', 'wpcustom-cursors'); ?></label>

                                <div class="d-flex align-items-center mt-2">
                                    <input type="range" data-apply="normal_uploaded_image_wrapper" data-variable="image-background-radius" data-unit="px" class="form-range me-2" min="0" max="500" id="image_background_radius_range" value="<?php echo esc_attr($image_background_radius_value); ?>">
                                    <input type="number" data-apply="normal_uploaded_image_wrapper" data-variable="image-background-radius" data-unit="px" min="0" max="500" id="image_background_radius" class="number-input" value="<?php echo esc_attr($image_background_radius_value); ?>" data-name="radius">
                                    <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                                </div>

                                <!-- Padding -->
                                <label for="image_background_padding" class="title-normal mt-3"><?php echo esc_html__('Padding:', 'wpcustom-cursors'); ?></label>

                                <div class="d-flex align-items-center mt-2">
                                    <input type="range" data-apply="normal_uploaded_image_wrapper" data-variable="image-background-padding" data-unit="px" class="form-range me-2" min="0" max="100" id="image_background_padding_range" value="<?php echo esc_attr($image_background_padding_value); ?>">
                                    <input type="number" data-apply="normal_uploaded_image_wrapper" data-variable="image-background-padding" data-unit="px" min="0" max="100" id="image_background_padding" class="number-input" value="<?php echo esc_attr($image_background_padding_value); ?>" data-name="padding">
                                    <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                                </div>
                            </div>

                            <!-- Image Blending Mode -->
                            <div class="form-group">
                                <label for="image_blending" class="title-normal mt-3"><?php echo esc_html__('Blending Mode:', 'wpcustom-cursors'); ?></label>
                                <select class="form-control mt-2" id="image_blending" data-apply="normal_uploaded_image_wrapper" data-variable="image-background-blending" data-name="blending">
                                    <option value="normal" 
                                    <?php
                                    if (isset($image_blending_value) ) {
                                        selected($image_blending_value, 'normal');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Normal', 'wpcustom-cursors'); ?></option>
                                    <option value="multiply" 
                                    <?php
                                    if (isset($image_blending_value) ) {
                                        selected($image_blending_value, 'multiply');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Multiply', 'wpcustom-cursors'); ?></option>
                                    <option value="screen" 
                                    <?php
                                    if (isset($image_blending_value) ) {
                                        selected($image_blending_value, 'screen');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Screen', 'wpcustom-cursors'); ?></option>
                                    <option value="overlay" 
                                    <?php
                                    if (isset($image_blending_value) ) {
                                        selected($image_blending_value, 'overlay');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Overlay', 'wpcustom-cursors'); ?></option>
                                    <option value="darken" 
                                    <?php
                                    if (isset($image_blending_value) ) {
                                        selected($image_blending_value, 'darken');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Darken', 'wpcustom-cursors'); ?></option>
                                    <option value="lighten" 
                                    <?php
                                    if (isset($image_blending_value) ) {
                                        selected($image_blending_value, 'lighten');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Lighten', 'wpcustom-cursors'); ?></option>
                                    <option value="color-dodge" 
                                    <?php
                                    if (isset($image_blending_value) ) {
                                        selected($image_blending_value, 'color-dodge');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Color Dodge', 'wpcustom-cursors'); ?></option>
                                    <option value="color-burn" 
                                    <?php
                                    if (isset($image_blending_value) ) {
                                        selected($image_blending_value, 'color-burn');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Color Burn', 'wpcustom-cursors'); ?></option>
                                    <option value="hard-light" 
                                    <?php
                                    if (isset($image_blending_value) ) {
                                        selected($image_blending_value, 'hard-light');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Hard Light', 'wpcustom-cursors'); ?></option>
                                    <option value="soft-light" 
                                    <?php
                                    if (isset($image_blending_value) ) {
                                        selected($image_blending_value, 'soft-light');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Soft Light', 'wpcustom-cursors'); ?></option>
                                    <option value="difference" 
                                    <?php
                                    if (isset($image_blending_value) ) {
                                        selected($image_blending_value, 'difference');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Difference', 'wpcustom-cursors'); ?></option>
                                    <option value="exclusion" 
                                    <?php
                                    if (isset($image_blending_value) ) {
                                        selected($image_blending_value, 'exclusion');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Exclusion', 'wpcustom-cursors'); ?></option>
                                    <option value="hue" 
                                    <?php
                                    if (isset($image_blending_value) ) {
                                        selected($image_blending_value, 'hue');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Hue', 'wpcustom-cursors'); ?></option>
                                    <option value="saturation" 
                                    <?php
                                    if (isset($image_blending_value) ) {
                                        selected($image_blending_value, 'saturation');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Saturation', 'wpcustom-cursors'); ?></option>
                                    <option value="color" 
                                    <?php
                                    if (isset($image_blending_value) ) {
                                        selected($image_blending_value, 'color');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Color', 'wpcustom-cursors'); ?></option>
                                    <option value="luminosity" 
                                    <?php
                                    if (isset($image_blending_value) ) {
                                        selected($image_blending_value, 'luminosity');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Luminosity', 'wpcustom-cursors'); ?></option>
                                </select>
                            </div>
                        </div>

                        <!-- Image Cursor Options - Hover -->
                        <div id="hover_image_options" style="
                        <?php
                        if ('image' !== $hover_cursor_type_value || 'normal' == $normal_hover_state ) {
                            echo esc_attr('display: none;'); 
                        }
                        ?>
                        ">
                            <div class="row hover-elements-wrapper py-2 rounded">
                                <div class="col-md-12">
                                    <div class="error-message align-items-center text-danger mb-2">
                                        <i class="ri-error-warning-line me-1"></i><span class="small"><?php echo esc_html__('You need to select at least one!', 'wpcustom-cursors'); ?></span>
                                    </div>
                                    <div class="title-normal">
                                        <?php echo esc_html__('Where to show this hover cursor?', 'wpcustom-cursors'); ?>
                                    </div>
                                    <!-- Links -->
                                    <label class="toggler-wrapper mt-2 style-4"> 
                                        <span class="toggler-label"><?php echo esc_html__('On Links', 'wpcustom-cursors'); ?></span>
                                        <input type="checkbox" id="image_hover_trigger_link" value="<?php echo esc_attr($hover_trigger_link_value); ?>" <?php checked($hover_trigger_link_value, 'on'); ?> data-name="links" data-default="<?php echo esc_attr($hover_trigger_link_value); ?>" data-off="image_hover_trigger_custom" data-hide-error="hover-elements-wrapper, show-error">
                                        <div class="toggler-slider">
                                            <div class="toggler-knob"></div>
                                        </div>
                                    </label>

                                    <!-- Buttons -->
                                    <label class="toggler-wrapper mt-2 style-4"> 
                                        <span class="toggler-label"><?php echo esc_html__('On Buttons', 'wpcustom-cursors'); ?></span>
                                        <input type="checkbox" id="image_hover_trigger_button" value="<?php echo esc_attr($hover_trigger_button_value); ?>" <?php checked($hover_trigger_button_value, 'on'); ?> data-name="buttons" data-default="<?php echo esc_attr($hover_trigger_button_value); ?>" data-off="image_hover_trigger_custom" data-hide-error="hover-elements-wrapper, show-error">
                                        <div class="toggler-slider">
                                            <div class="toggler-knob"></div>
                                        </div>
                                    </label>

                                    <!-- Images -->
                                    <label class="toggler-wrapper mt-2 style-4"> 
                                        <span class="toggler-label"><?php echo esc_html__('On Images', 'wpcustom-cursors'); ?></span>
                                        <input type="checkbox" id="image_hover_trigger_image" value="<?php echo esc_attr($hover_trigger_image_value); ?>" <?php checked($hover_trigger_image_value, 'on'); ?> data-name="images" data-default="<?php echo esc_attr($hover_trigger_image_value); ?>" data-off="image_hover_trigger_custom" data-hide-error="hover-elements-wrapper, show-error">
                                        <div class="toggler-slider">
                                            <div class="toggler-knob"></div>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <!-- Custom Element -->
                                    <label class="toggler-wrapper style-4"> 
                                        <span class="toggler-label"><?php echo esc_html__('On Custom Element', 'wpcustom-cursors'); ?></span>
                                        <input type="checkbox" id="image_hover_trigger_custom" value="<?php echo esc_attr($hover_trigger_custom_value); ?>" <?php checked($hover_trigger_custom_value, 'on'); ?> data-toggle="hover-selector-wrapper" data-name="custom" data-default="<?php echo esc_attr($hover_trigger_custom_value); ?>" data-off="image_hover_trigger_image,image_hover_trigger_button,image_hover_trigger_link" data-hide-error="hover-elements-wrapper, show-error">
                                        <div class="toggler-slider">
                                            <div class="toggler-knob"></div>
                                        </div>
                                    </label>

                                    <!-- Custom Hover Selector -->
                                    <div class="input-group mt-2 hover-selector-wrapper" id="image_hover_trigger_custom_wrapper" style="
                                    <?php
                                    if ('off' === $hover_trigger_custom_value ) {
                                        echo esc_attr('display: none;');
                                    }
                                    ?>
                                    ">
                                        <span class="input-group-text" id="image_custom_hover_selector"><?php echo esc_html__('CSS Selector', 'wpcustom-cursors'); ?></span>
                                        <input type="text" value="<?php echo esc_attr($hover_trigger_selector_value); ?>" class="form-control" placeholder="<?php echo esc_html__('e.g. .btn', 'wpcustom-cursors'); ?>" aria-label="<?php echo esc_html__('Selector', 'wpcustom-cursors'); ?>" aria-describedby="image_custom_hover_selector" id="image_hover_trigger_selector" data-name="selector" data-default="<?php echo esc_attr($hover_trigger_selector_value); ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Uploaded Image URL Input -->
                            <input type="hidden" id="hover_image_url_input" class="image-url-input" value="<?php echo esc_attr($hover_image_url_value); ?>" data-name="hover_image_url" data-default="<?php echo esc_attr($hover_image_url_value); ?>">

                            <!-- Click Point Inputs -->
                            <input type="hidden" id="hover_click_point_input" data-name="hover_click_point" value="<?php echo esc_attr($hover_click_point_value); ?>" data-default="<?php echo esc_attr($hover_click_point_value); ?>">

                            <!-- Image Width -->
                            <label for="hover_image_width" class="title-normal"><?php echo esc_html__('Width:', 'wpcustom-cursors'); ?></label>

                            <div class="d-flex align-items-center mt-2">
                                <input type="range" data-apply="hover_uploaded_image_wrapper" data-variable="image-width" data-unit="px" data-state="hover" class="form-range me-2 image-width-input" min="10" max="500" id="hover_image_width_range" value="<?php echo esc_attr($hover_image_width_value); ?>">
                                <input type="number" data-apply="hover_uploaded_image_wrapper" data-variable="image-width" data-unit="px" data-state="hover" min="10" max="500" id="hover_image_width" class="number-input image-width-input" value="<?php echo esc_attr($hover_image_width_value); ?>" data-name="width" data-default="<?php echo esc_attr($hover_image_width_value); ?>">
                                <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                                <input type="hidden" data-name="height" value="<?php echo esc_attr($hover_image_height_value); ?>" id="hover_image_height" data-variable="image-height" data-unit="px" data-default="<?php echo esc_attr($hover_image_height_value); ?>">
                            </div>

                            <!-- Background -->
                            <label class="toggler-wrapper mt-3 style-4"> 
                                <span class="toggler-label"><?php echo esc_html__('Background', 'wpcustom-cursors'); ?></span>
                                <input type="checkbox" id="hover_image_background" value="<?php echo esc_attr($hover_image_background_value); ?>" <?php checked($hover_image_background_value, 'on'); ?> data-name="background" data-default="<?php echo esc_attr($hover_image_background_value); ?>" data-toggle="image-bg-options" data-state="hover">
                                <div class="toggler-slider">
                                    <div class="toggler-knob"></div>
                                </div>
                            </label>

                            <div class="image-bg-options border p-4 rounded-3 mt-3" style="
                            <?php
                            if ('off' === $hover_image_background_value ) {
                                echo esc_attr('display: none;');
                            }
                            ?>
                            ">
                                <!-- Background Color -->
                                <div class="title-normal mt-3">
                                    <?php echo esc_html__('Background Color:', 'wpcustom-cursors'); ?>
                                </div>
                                <div class="color_select form-group mt-2">
                                    <label class="w-100">
                                        <input type='text' data-apply="hover_uploaded_image_wrapper" data-variable="image-background-color" class="form-control basic wp-custom-cursor-color-picker" id="hover_image_background_color" value="<?php echo esc_attr($hover_image_background_color_value); ?>" data-name="color" data-default="<?php echo esc_attr($hover_image_background_color_value); ?>">
                                    </label>
                                </div>

                                <!-- Radius -->
                                <label for="hover_image_background_radius" class="title-normal mt-3"><?php echo esc_html__('Background Radius:', 'wpcustom-cursors'); ?></label>

                                <div class="d-flex align-items-center mt-2">
                                    <input type="range" data-apply="hover_uploaded_image_wrapper" data-variable="image-background-radius" data-unit="px" class="form-range me-2" min="0" max="500" id="hover_image_background_radius_range" value="<?php echo esc_attr($hover_image_background_radius_value); ?>">
                                    <input type="number" data-apply="hover_uploaded_image_wrapper" data-variable="image-background-radius" data-unit="px" min="0" max="500" id="hover_image_background_radius" class="number-input" value="<?php echo esc_attr($hover_image_background_radius_value); ?>" data-name="radius" data-default="<?php echo esc_attr($hover_image_background_radius_value); ?>">
                                    <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                                </div>

                                <!-- Padding -->
                                <label for="hover_image_background_padding" class="title-normal mt-3"><?php echo esc_html__('Padding:', 'wpcustom-cursors'); ?></label>

                                <div class="d-flex align-items-center mt-2">
                                    <input type="range" data-apply="hover_uploaded_image_wrapper" data-variable="image-background-padding" data-unit="px" class="form-range me-2" min="0" max="100" id="hover_image_background_padding_range" value="<?php echo esc_attr($hover_image_background_padding_value); ?>">
                                    <input type="number" data-apply="hover_uploaded_image_wrapper" data-variable="image-background-padding" data-unit="px" min="0" max="100" id="hover_image_background_padding" class="number-input" value="<?php echo esc_attr($hover_image_background_padding_value); ?>" data-name="padding" data-default="<?php echo esc_attr($hover_image_background_padding_value); ?>">
                                    <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                                </div>
                            </div>

                            <!-- Image Blending Mode -->
                            <div class="form-group">
                                <label for="hover_image_blending" class="title-normal mt-3"><?php echo esc_html__('Blending Mode:', 'wpcustom-cursors'); ?></label>
                                <select class="form-control mt-2" id="hover_image_blending" data-apply="hover_uploaded_image_wrapper" data-variable="image-background-blending" data-name="blending" data-default="<?php echo esc_attr($hover_image_blending_value); ?>">
                                    <option value="normal" 
                                    <?php
                                    if (isset($hover_image_blending_value) ) {
                                        selected($hover_image_blending_value, 'normal');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Normal', 'wpcustom-cursors'); ?></option>
                                    <option value="multiply" 
                                    <?php
                                    if (isset($hover_image_blending_value) ) {
                                        selected($hover_image_blending_value, 'multiply');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Multiply', 'wpcustom-cursors'); ?></option>
                                    <option value="screen" 
                                    <?php
                                    if (isset($hover_image_blending_value) ) {
                                        selected($hover_image_blending_value, 'screen');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Screen', 'wpcustom-cursors'); ?></option>
                                    <option value="overlay" 
                                    <?php
                                    if (isset($hover_image_blending_value) ) {
                                        selected($hover_image_blending_value, 'overlay');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Overlay', 'wpcustom-cursors'); ?></option>
                                    <option value="darken" 
                                    <?php
                                    if (isset($hover_image_blending_value) ) {
                                        selected($hover_image_blending_value, 'darken');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Darken', 'wpcustom-cursors'); ?></option>
                                    <option value="lighten" 
                                    <?php
                                    if (isset($hover_image_blending_value) ) {
                                        selected($hover_image_blending_value, 'lighten');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Lighten', 'wpcustom-cursors'); ?></option>
                                    <option value="color-dodge" 
                                    <?php
                                    if (isset($hover_image_blending_value) ) {
                                        selected($hover_image_blending_value, 'color-dodge');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Color Dodge', 'wpcustom-cursors'); ?></option>
                                    <option value="color-burn" 
                                    <?php
                                    if (isset($hover_image_blending_value) ) {
                                        selected($hover_image_blending_value, 'color-burn');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Color Burn', 'wpcustom-cursors'); ?></option>
                                    <option value="hard-light" 
                                    <?php
                                    if (isset($hover_image_blending_value) ) {
                                        selected($hover_image_blending_value, 'hard-light');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Hard Light', 'wpcustom-cursors'); ?></option>
                                    <option value="soft-light" 
                                    <?php
                                    if (isset($hover_image_blending_value) ) {
                                        selected($hover_image_blending_value, 'soft-light');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Soft Light', 'wpcustom-cursors'); ?></option>
                                    <option value="difference" 
                                    <?php
                                    if (isset($hover_image_blending_value) ) {
                                        selected($hover_image_blending_value, 'difference');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Difference', 'wpcustom-cursors'); ?></option>
                                    <option value="exclusion" 
                                    <?php
                                    if (isset($hover_image_blending_value) ) {
                                        selected($hover_image_blending_value, 'exclusion');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Exclusion', 'wpcustom-cursors'); ?></option>
                                    <option value="hue" 
                                    <?php
                                    if (isset($hover_image_blending_value) ) {
                                        selected($hover_image_blending_value, 'hue');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Hue', 'wpcustom-cursors'); ?></option>
                                    <option value="saturation" 
                                    <?php
                                    if (isset($hover_image_blending_value) ) {
                                        selected($hover_image_blending_value, 'saturation');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Saturation', 'wpcustom-cursors'); ?></option>
                                    <option value="color" 
                                    <?php
                                    if (isset($hover_image_blending_value) ) {
                                        selected($hover_image_blending_value, 'color');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Color', 'wpcustom-cursors'); ?></option>
                                    <option value="luminosity" 
                                    <?php
                                    if (isset($hover_image_blending_value) ) {
                                        selected($hover_image_blending_value, 'luminosity');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Luminosity', 'wpcustom-cursors'); ?></option>
                                </select>
                            </div>
                        </div>

                        <!-- Text Cursor Options - Normal -->
                        <div id="normal_text_options" style="
                        <?php
                        if ('text' !== $cursor_type_value || 'normal' === $normal_hover_state ) {
                            echo esc_attr('display: none;'); 
                        }
                        ?>
                        ">

                            <!-- Text Type Dropdown -->
                            <div class="form-group mb-3">
                                <label for="normal_text_type" class="title-normal"><?php echo esc_html__('Text Type:', 'wpcustom-cursors'); ?></label>
                                <select class="form-control mt-2" id="normal_text_type" data-name='normal_text_type' data-select="normal_hr_text_type" data-state="normal">
                                    <option value="text" 
                                    <?php
                                    if (isset($normal_text_type_value) ) {
                                        selected($normal_text_type_value, 'text');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Circular', 'wpcustom-cursors'); ?></option>
                                    <option value="horizontal" 
                                    <?php
                                    if (isset($normal_text_type_value) ) {
                                        selected($normal_text_type_value, 'horizontal');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Horizontal', 'wpcustom-cursors'); ?></option>
                                </select>
                            </div>

                            <!-- Text Content -->
                            <label for="cursor_text_input" class="form-label"><?php echo esc_html__('Text for the cursor', 'wpcustom-cursors'); ?></label>
                            <input type="text" data-apply="textpath" value="<?php echo esc_attr($cursor_text_value); ?>" class="form-control" placeholder="<?php echo esc_html__('Enter Text', 'wpcustom-cursors'); ?>" aria-label="<?php echo esc_html__('Text Cursor', 'wpcustom-cursors'); ?>" id="cursor_text_input" data-name="text">

                            <!-- Dot -->
                            <label class="toggler-wrapper mt-3 style-4"> 
                                <span class="toggler-label"><?php echo esc_html__('Show Dot', 'wpcustom-cursors'); ?></span>
                                <input type="checkbox" id="show_dot" data-apply="svg_node" value="on" <?php checked($show_dot_value, 'on'); ?> data-name="dot" data-toggle="dot-options">
                                <div class="toggler-slider">
                                    <div class="toggler-knob"></div>
                                </div>
                            </label>

                            <div class="border p-4 rounded-3 mt-3 dot-options" id="dot_options" style="
                            <?php
                            if ('off' === $show_dot_value ) {
                                echo esc_attr('display:none');
                            }
                            ?>
                            ">
                                <!-- Dot Width -->
                                <label for="dot_width" class="title-normal mt-3"><?php echo esc_html__('Dot Width:', 'wpcustom-cursors'); ?></label>

                                <div class="d-flex align-items-center mt-2">
                                    <input type="range" data-apply="svg_circle_node" data-variable="dot-width" data-unit="px" class="form-range me-2" min="10" max="200" id="dot_width_range" value="<?php echo esc_attr($dot_width_value); ?>">
                                    <input type="number" data-apply="svg_circle_node" data-variable="dot-width" data-unit="px" min="10" max="200" id="dot_width" class="number-input" value="<?php echo esc_attr($dot_width_value); ?>" data-name="dot_width">
                                    <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                                </div>

                                <!-- Dot Color -->
                                <div class="title-normal mt-3">
                                    <?php echo esc_html__('Dot Color:', 'wpcustom-cursors'); ?>
                                </div>
                                <div class="color_select form-group mt-2">
                                    <label class="w-100">
                                        <input type='text' class="form-control basic wp-custom-cursor-color-picker" data-apply="svg_node" data-variable="dot-fill" id="dot_color" value="<?php echo esc_attr($dot_color_value); ?>" data-name="dot_color">
                                    </label>
                                </div>
                            </div>

                            <!-- Width -->
                            <label for="text_width" class="title-normal mt-3"><?php echo esc_html__('Width:', 'wpcustom-cursors'); ?></label>

                            <div class="d-flex align-items-center mt-2">
                                <input type="range" data-apply="svg_node" data-variable="text-width" data-unit="px" class="form-range me-2" min="50" max="500" id="text_width_range" value="<?php echo esc_attr($text_width_value); ?>">
                                <input type="number" data-apply="svg_node" data-variable="text-width" data-unit="px" min="50" max="500" id="text_width" class="number-input" value="<?php echo esc_attr($text_width_value); ?>" data-name="width">
                                <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                            </div>

                            <!-- Text Transform -->
                            <div class="form-group">
                                <label for="text_transform" class="title-normal mt-3"><?php echo esc_html__('Text Transform:', 'wpcustom-cursors'); ?></label>
                                <select class="form-control mt-2" id="text_transform" data-apply="svg_node" data-variable="text-transform" data-name="text_transform">
                                    <option value="uppercase" 
                                    <?php
                                    if (isset($text_transform_value) ) {
                                        selected($text_transform_value, 'uppercase');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Uppercase', 'wpcustom-cursors'); ?></option>
                                    <option value="lowercase" 
                                    <?php
                                    if (isset($text_transform_value) ) {
                                        selected($text_transform_value, 'lowercase');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Lowercase', 'wpcustom-cursors'); ?></option>
                                    <option value="capitalize" 
                                    <?php
                                    if (isset($text_transform_value) ) {
                                        selected($text_transform_value, 'capitalize');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Capitalize', 'wpcustom-cursors'); ?></option>
                                </select>
                            </div>

                            <!-- Font Weight -->
                            <div class="form-group">
                                <label for="font_weight" class="title-normal mt-3"><?php echo esc_html__('Font Weight:', 'wpcustom-cursors'); ?></label>
                                <select class="form-control mt-2" id="font_weight" data-apply="svg_node" data-variable="font-weight" data-name="font_weight">
                                    <option value="normal" 
                                    <?php
                                    if (isset($font_weight_value) ) {
                                        selected($font_weight_value, 'normal');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Normal', 'wpcustom-cursors'); ?></option>
                                    <option value="bold" 
                                    <?php
                                    if (isset($font_weight_value) ) {
                                        selected($font_weight_value, 'bold');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Bold', 'wpcustom-cursors'); ?></option>
                                </select>
                            </div>

                            <!-- Text Color -->
                            <div class="title-normal mt-3">
                                <?php echo esc_html__('Text Color:', 'wpcustom-cursors'); ?>
                            </div>
                            <div class="color_select form-group mt-2">
                                <label class="w-100">
                                    <input type='text' class="form-control basic wp-custom-cursor-color-picker" data-apply="svg_node" data-variable="text-color" id="text_color" value="<?php echo esc_attr($text_color_value); ?>" data-name="text_color">
                                </label>
                            </div>

                            <!-- Font Size -->
                            <label for="font_size" class="title-normal mt-3"><?php echo esc_html__('Font Size:', 'wpcustom-cursors'); ?></label>

                            <div class="d-flex align-items-center mt-2">
                                <input type="range" data-apply="svg_node" data-variable="font-size" data-unit="px" class="form-range me-2" min="10" max="200" id="font_size_range" value="<?php echo esc_attr($font_size_value); ?>">
                                <input type="number" data-apply="svg_node" data-variable="font-size" data-unit="px" min="10" max="200" id="font_size" class="number-input" value="<?php echo esc_attr($font_size_value); ?>" data-name="font_size">
                                <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                            </div>

                            <!-- Word Spacing -->
                            <label for="word_spacing" class="title-normal mt-3"><?php echo esc_html__('Word Spacing:', 'wpcustom-cursors'); ?></label>

                            <div class="d-flex align-items-center mt-2">
                                <input type="range" data-apply="svg_node" data-variable="word-spacing" data-unit="px" class="form-range me-2" min="1" max="200" id="word_spacing_range" value="<?php echo esc_attr($word_spacing_value); ?>">
                                <input type="number" data-apply="svg_node" data-variable="word-spacing" data-unit="px" min="1" max="200" id="word_spacing" class="number-input" value="<?php echo esc_attr($word_spacing_value); ?>" data-name="word_spacing">
                                <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                            </div>

                            <!-- Animation -->
                            <div class="form-group">
                                <label for="text_animation" class="title-normal mt-3"><?php echo esc_html__('Text Animation:', 'wpcustom-cursors'); ?></label>
                                <select class="form-control mt-2" id="text_animation" data-apply="svg_node" data-variable="animation-name" data-name="animation">
                                    <option value="none" 
                                    <?php
                                    if (isset($text_animation_value) ) {
                                        selected($text_animation_value, 'none');
                                    }
                                    ?>
                                    ><?php echo esc_html__('None', 'wpcustom-cursors'); ?></option>
                                    <option value="spinright" 
                                    <?php
                                    if (isset($text_animation_value) ) {
                                        selected($text_animation_value, 'spinright');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Spin Right', 'wpcustom-cursors'); ?></option>
                                    <option value="spinleft" 
                                    <?php
                                    if (isset($text_animation_value) ) {
                                        selected($text_animation_value, 'spinleft');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Spin Left', 'wpcustom-cursors'); ?></option>
                                </select>
                            </div>

                            <!-- Text Animation Duration -->
                            <label for="text_animation_duration" class="title-normal mt-3"><?php echo esc_html__('Text Animation Duration:', 'wpcustom-cursors'); ?></label>

                            <div class="d-flex align-items-center mt-2">
                                <input type="range" data-apply="svg_node" data-variable="animation-duration" data-unit="s" class="form-range me-2" min="0" max="100" id="text_animation_duration_range" value="<?php echo esc_attr($text_animation_duration_value); ?>">
                                <input type="number" data-apply="svg_node" data-variable="animation-duration" data-unit="s" min="0" max="100" id="text_animation_duration" class="number-input" value="<?php echo esc_attr($text_animation_duration_value); ?>" data-name="animation_duration">
                                <span class="ms-2 small"><?php echo esc_html__('S', 'wpcustom-cursors'); ?></span>
                            </div>
                        </div>

                        <!-- Horizontal Text Options - Normal -->
                        <div id="normal_horizontal_options" style="
                        <?php
                        if ('text' !== $cursor_type_value || 'horizontal' !== $normal_text_type_value ) {
                            echo esc_attr('display: none;'); 
                        }
                        ?>
                        ">

                            <!-- Text Type Dropdown -->
                            <div class="form-group mb-3">
                                <label for="normal_hr_text_type" class="title-normal"><?php echo esc_html__('Text Type:', 'wpcustom-cursors'); ?></label>
                                <select class="form-control mt-2" id="normal_hr_text_type" data-name='normal_text_type' data-select="normal_text_type" data-state="normal">
                                    <option value="text" 
                                    <?php
                                    if (isset($text_type_value) ) {
                                        selected($text_type_value, 'text');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Circular', 'wpcustom-cursors'); ?></option>
                                    <option value="horizontal" 
                                    <?php
                                    if (isset($text_type_value) ) {
                                        selected($text_type_value, 'horizontal');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Horizontal', 'wpcustom-cursors'); ?></option>
                                </select>
                            </div>

                            <!-- Text Content -->
                            <label for="hr_text_input" class="form-label"><?php echo esc_html__('Text for the cursor', 'wpcustom-cursors'); ?></label>
                            <input type="text" data-apply="hr_text_container" value="<?php echo esc_attr($hr_text_value); ?>" class="form-control" placeholder="<?php echo esc_html__('Enter Text', 'wpcustom-cursors'); ?>" aria-label="<?php echo esc_html__('Text Cursor', 'wpcustom-cursors'); ?>" id="hr_text_input" data-name="hr_text">

                            <!-- Background Color -->
                            <div class="title-normal mt-3">
                                <?php echo esc_html__('Background Color:', 'wpcustom-cursors'); ?>
                            </div>
                            <div class="color_select form-group mt-2">
                                <label class="w-100">
                                    <input type='text' data-apply="hr_text_container" data-variable="bg-color" class="form-control basic wp-custom-cursor-color-picker" id="hr_background_color" value="<?php echo esc_attr($hr_background_value); ?>" data-name="hr_bgcolor">
                                </label>
                            </div>

                            <!-- Radius -->
                            <label for="hr_radius" class="title-normal mt-3"><?php echo esc_html__('Radius:', 'wpcustom-cursors'); ?></label>

                            <div class="d-flex align-items-center mt-2">
                                <input type="range" data-apply="hr_text_container" data-variable="hr-radius" data-unit="px" class="form-range me-2" min="0" max="500" id="hr_radius_range" value="<?php echo esc_attr($hr_radius_value); ?>">
                                <input type="number" data-apply="hr_text_container" data-variable="hr-radius" data-unit="px" min="0" max="500" id="hr_radius" class="number-input" value="<?php echo esc_attr($hr_radius_value); ?>" data-name="hr_radius">
                                <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                            </div>

                            <!-- Padding -->
                            <label for="hr_padding" class="title-normal mt-3"><?php echo esc_html__('Padding:', 'wpcustom-cursors'); ?></label>

                            <div class="d-flex align-items-center mt-2">
                                <input type="range" data-apply="hr_text_container" data-variable="hr-padding" data-unit="px" class="form-range me-2" min="0" max="100" id="hr_padding_range" value="<?php echo esc_attr($hr_padding_value); ?>">
                                <input type="number" data-apply="hr_text_container" data-variable="hr-padding" data-unit="px" min="0" max="100" id="hr_padding" class="number-input" value="<?php echo esc_attr($hr_padding_value); ?>" data-name="hr_padding">
                                <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                            </div>

                            <!-- Width -->
                            <label for="hr_width" class="title-normal mt-3"><?php echo esc_html__('Width:', 'wpcustom-cursors'); ?></label>

                            <div class="d-flex align-items-center mt-2">
                                <input type="range" data-apply="hr_text_container" data-variable="hr-width" data-unit="px" class="form-range me-2" min="50" max="500" id="hr_width_range" value="<?php echo esc_attr($hr_width_value); ?>">
                                <input type="number" data-apply="hr_text_container" data-variable="hr-width" data-unit="px" min="50" max="500" id="hr_width" class="number-input" value="<?php echo esc_attr($hr_width_value); ?>" data-name="hr_width">
                                <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                            </div>

                            <!-- Text Transform -->
                            <div class="form-group">
                                <label for="hr_transform" class="title-normal mt-3"><?php echo esc_html__('Text Transform:', 'wpcustom-cursors'); ?></label>
                                <select class="form-control mt-2" id="hr_transform" data-apply="hr_text_container" data-variable="hr-transform" data-name="hr_transform">
                                    <option value="uppercase" 
                                    <?php
                                    if (isset($hr_transform_value) ) {
                                        selected($hr_transform_value, 'uppercase');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Uppercase', 'wpcustom-cursors'); ?></option>
                                    <option value="lowercase" 
                                    <?php
                                    if (isset($hr_transform_value) ) {
                                        selected($hr_transform_value, 'lowercase');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Lowercase', 'wpcustom-cursors'); ?></option>
                                    <option value="capitalize" 
                                    <?php
                                    if (isset($hr_transform_value) ) {
                                        selected($hr_transform_value, 'capitalize');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Capitalize', 'wpcustom-cursors'); ?></option>
                                </select>
                            </div>

                            <!-- Font Weight -->
                            <div class="form-group">
                                <label for="hr_weight" class="title-normal mt-3"><?php echo esc_html__('Font Weight:', 'wpcustom-cursors'); ?></label>
                                <select class="form-control mt-2" id="hr_weight" data-apply="hr_text_container" data-variable="hr-weight" data-name="hr_weight">
                                    <option value="normal" 
                                    <?php
                                    if (isset($hr_weight_value) ) {
                                        selected($hr_weight_value, 'normal');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Normal', 'wpcustom-cursors'); ?></option>
                                    <option value="bold" 
                                    <?php
                                    if (isset($hr_weight_value) ) {
                                        selected($hr_weight_value, 'bold');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Bold', 'wpcustom-cursors'); ?></option>
                                </select>
                            </div>

                            <!-- Text Color -->
                            <div class="title-normal mt-3">
                                <?php echo esc_html__('Text Color:', 'wpcustom-cursors'); ?>
                            </div>
                            <div class="color_select form-group mt-2">
                                <label class="w-100">
                                    <input type='text' class="form-control basic wp-custom-cursor-color-picker" data-apply="hr_text_container" data-variable="hr-color" id="hr_color" value="<?php echo esc_attr($hr_color_value); ?>" data-name="hr_color">
                                </label>
                            </div>

                            <!-- Font Size -->
                            <label for="hr_size" class="title-normal mt-3"><?php echo esc_html__('Font Size:', 'wpcustom-cursors'); ?></label>

                            <div class="d-flex align-items-center mt-2">
                                <input type="range" data-apply="hr_text_container" data-variable="hr-size" data-unit="px" class="form-range me-2" min="10" max="200" id="hr_size_range" value="<?php echo esc_attr($hr_size_value); ?>">
                                <input type="number" data-apply="hr_text_container" data-variable="hr-size" data-unit="px" min="10" max="200" id="hr_size" class="number-input" value="<?php echo esc_attr($hr_size_value); ?>" data-name="hr_size">
                                <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                            </div>

                            <!-- Word Spacing -->
                            <label for="hr_spacing" class="title-normal mt-3"><?php echo esc_html__('Word Spacing:', 'wpcustom-cursors'); ?></label>

                            <div class="d-flex align-items-center mt-2">
                                <input type="range" data-apply="hr_text_container" data-variable="hr-spacing" data-unit="px" class="form-range me-2" min="1" max="200" id="hr_spacing_range" value="<?php echo esc_attr($hr_spacing_value); ?>">
                                <input type="number" data-apply="hr_text_container" data-variable="hr-spacing" data-unit="px" min="1" max="200" id="hr_spacing" class="number-input" value="<?php echo esc_attr($hr_spacing_value); ?>" data-name="hr_spacing">
                                <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                            </div>

                            <!-- Transition Duration -->
                            <label for="hr_duration" class="title-normal mt-3"><?php echo esc_html__('Transition Duration:', 'wpcustom-cursors'); ?></label>

                            <div class="d-flex align-items-center mt-2">
                                <input type="range" data-apply="hr_text_container" data-variable="hr_duration" data-unit="ms" class="form-range me-2" min="0" max="1000" id="hr_duration_range" value="<?php echo esc_attr($hr_duration_value); ?>">
                                <input type="number" data-apply="hr_text_container" data-variable="hr_duration" data-unit="ms" min="0" max="1000" id="hr_duration" class="number-input" value="<?php echo esc_attr($hr_duration_value); ?>" data-name="hr_duration">
                                <span class="ms-2 small"><?php echo esc_html__('MS', 'wpcustom-cursors'); ?></span>
                            </div>

                            <!-- Transition Timing Function -->
                            <div class="form-group">
                                <label for="hr_timing" class="title-normal mt-3"><?php echo esc_html__('Transition Timing Function:', 'wpcustom-cursors'); ?></label>
                                <select class="form-control mt-2" id="hr_timing" data-apply="hr_text_container" data-variable="hr_timing" data-name="hr_timing">
                                    <option value="ease" 
                                    <?php
                                    if (isset($hr_timing_value) ) {
                                        selected($hr_timing_value, 'ease');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Ease', 'wpcustom-cursors'); ?></option>
                                    <option value="ease-in" 
                                    <?php
                                    if (isset($hr_timing_value) ) {
                                        selected($hr_timing_value, 'ease-in');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Ease In', 'wpcustom-cursors'); ?></option>
                                    <option value="ease-out" 
                                    <?php
                                    if (isset($hr_timing_value) ) {
                                        selected($hr_timing_value, 'ease-out');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Ease Out', 'wpcustom-cursors'); ?></option>
                                    <option value="ease-in-out" 
                                    <?php
                                    if (isset($hr_timing_value) ) {
                                        selected($hr_timing_value, 'ease-in-out');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Ease In Out', 'wpcustom-cursors'); ?></option>
                                    <option value="linear" 
                                    <?php
                                    if (isset($hr_timing_value) ) {
                                        selected($hr_timing_value, 'linear');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Linear', 'wpcustom-cursors'); ?></option>
                                </select>
                            </div>

                            <!-- Horizontal Text Backdrop Filter -->
                            <div class="form-group">
                                <label for="hr_backdrop" class="title-normal mt-3"><?php echo esc_html__('Backdrop Filter:', 'wpcustom-cursors'); ?></label>
                                <select class="form-control mt-2" id="hr_backdrop" data-name="hr_backdrop">
                                    <option value="none" 
                                    <?php
                                    if (isset($hr_backdrop_value) ) {
                                        selected($hr_backdrop_value, 'none');
                                    }
                                    ?>
                                    ><?php echo esc_html__('None', 'wpcustom-cursors'); ?></option>
                                    <option value="blur" 
                                    <?php
                                    if (isset($hr_backdrop_value) ) {
                                        selected($hr_backdrop_value, 'blur');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Blur', 'wpcustom-cursors'); ?></option>
                                    <option value="brightness" 
                                    <?php
                                    if (isset($hr_backdrop_value) ) {
                                        selected($hr_backdrop_value, 'brightness');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Brightness', 'wpcustom-cursors'); ?></option>
                                    <option value="contrast" 
                                    <?php
                                    if (isset($hr_backdrop_value) ) {
                                        selected($hr_backdrop_value, 'contrast');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Contrast', 'wpcustom-cursors'); ?></option>
                                    <option value="drop-shadow" 
                                    <?php
                                    if (isset($hr_backdrop_value) ) {
                                        selected($hr_backdrop_value, 'drop-shadow');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Drop Shadow', 'wpcustom-cursors'); ?></option>
                                    <option value="grayscale" 
                                    <?php
                                    if (isset($hr_backdrop_value) ) {
                                        selected($hr_backdrop_value, 'grayscale');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Grayscale', 'wpcustom-cursors'); ?></option>
                                    <option value="hue-rotate" 
                                    <?php
                                    if (isset($hr_backdrop_value) ) {
                                        selected($hr_backdrop_value, 'hue-rotate');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Hue Rotate', 'wpcustom-cursors'); ?></option>
                                    <option value="invert" 
                                    <?php
                                    if (isset($hr_backdrop_value) ) {
                                        selected($hr_backdrop_value, 'invert');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Invert', 'wpcustom-cursors'); ?></option>
                                    <option value="opacity" 
                                    <?php
                                    if (isset($hr_backdrop_value) ) {
                                        selected($hr_backdrop_value, 'opacity');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Opacity', 'wpcustom-cursors'); ?></option>
                                    <option value="sepia" 
                                    <?php
                                    if (isset($hr_backdrop_value) ) {
                                        selected($hr_backdrop_value, 'sepia');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Sepia', 'wpcustom-cursors'); ?></option>
                                    <option value="saturate" 
                                    <?php
                                    if (isset($hr_backdrop_value) ) {
                                        selected($hr_backdrop_value, 'saturate');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Saturate', 'wpcustom-cursors'); ?></option>
                                    <option value="revert" 
                                    <?php
                                    if (isset($hr_backdrop_value) ) {
                                        selected($hr_backdrop_value, 'revert');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Revert', 'wpcustom-cursors'); ?></option>
                                </select>
                            </div>

                            <!-- Backdrop Filter Value -->
                            <div class="form-floating mt-3">
                                <input type="text" class="form-control" id="hr_backdrop_amount" placeholder="<?php echo esc_html__('e.g. 2px', 'wpcustom-cursors'); ?>" value="<?php echo esc_attr($hr_backdrop_amount_value); ?>" data-name="hr_backdrop_amount">
                                <label for="hr_backdrop_amount"><?php echo esc_html__('Value with unit e.g. 2px', 'wpcustom-cursors'); ?></label>
                            </div>
                        </div>

                        <!-- Text Cursor Options - Hover -->
                        <div id="hover_text_options" style="
                        <?php
                        if ('text' !== $hover_cursor_type_value || 'normal' === $normal_hover_state ) {
                            echo esc_attr('display: none;'); 
                        }
                        ?>
                        ">
                            <div class="row hover-elements-wrapper py-2 rounded">
                                <div class="col-md-12">

                                    <div class="error-message align-items-center text-danger mb-2">
                                        <i class="ri-error-warning-line me-1"></i><span class="small"><?php echo esc_html__('You need to select at least one!', 'wpcustom-cursors'); ?></span>
                                    </div>
                                    <div class="title-normal">
                                        <?php echo esc_html__('Where to show this hover cursor?', 'wpcustom-cursors'); ?>
                                    </div>
                                    <!-- Links -->
                                    <label class="toggler-wrapper mt-2 style-4"> 
                                        <span class="toggler-label"><?php echo esc_html__('On Links', 'wpcustom-cursors'); ?></span>
                                        <input type="checkbox" id="text_hover_trigger_link" value="<?php echo esc_attr($hover_trigger_link_value); ?>" <?php checked($hover_trigger_link_value, 'on'); ?> data-name="links" data-default="<?php echo esc_attr($hover_trigger_link_value); ?>" data-off="text_hover_trigger_custom" data-hide-error="hover-elements-wrapper, show-error">
                                        <div class="toggler-slider">
                                            <div class="toggler-knob"></div>
                                        </div>
                                    </label>

                                    <!-- Buttons -->
                                    <label class="toggler-wrapper mt-2 style-4"> 
                                        <span class="toggler-label"><?php echo esc_html__('On Buttons', 'wpcustom-cursors'); ?></span>
                                        <input type="checkbox" id="text_hover_trigger_button" value="<?php echo esc_attr($hover_trigger_button_value); ?>" <?php checked($hover_trigger_button_value, 'on'); ?> data-name="buttons" data-default="<?php echo esc_attr($hover_trigger_button_value); ?>" data-off="text_hover_trigger_custom" data-hide-error="hover-elements-wrapper, show-error">
                                        <div class="toggler-slider">
                                            <div class="toggler-knob"></div>
                                        </div>
                                    </label>

                                    <!-- Images -->
                                    <label class="toggler-wrapper mt-2 style-4"> 
                                        <span class="toggler-label"><?php echo esc_html__('On Images', 'wpcustom-cursors'); ?></span>
                                        <input type="checkbox" id="text_hover_trigger_image" value="<?php echo esc_attr($hover_trigger_image_value); ?>" <?php checked($hover_trigger_image_value, 'on'); ?> data-name="images" data-default="<?php echo esc_attr($hover_trigger_image_value); ?>" data-off="text_hover_trigger_custom" data-hide-error="hover-elements-wrapper, show-error">
                                        <div class="toggler-slider">
                                            <div class="toggler-knob"></div>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <!-- Custom Element -->
                                    <label class="toggler-wrapper style-4"> 
                                        <span class="toggler-label"><?php echo esc_html__('On Custom Element', 'wpcustom-cursors'); ?></span>
                                        <input type="checkbox" id="text_hover_trigger_custom" value="<?php echo esc_attr($hover_trigger_custom_value); ?>" <?php checked($hover_trigger_custom_value, 'on'); ?> data-toggle="hover-selector-wrapper" data-name="custom" data-default="<?php echo esc_attr($hover_trigger_custom_value); ?>" data-off="text_hover_trigger_image,text_hover_trigger_button,text_hover_trigger_link" data-hide-error="hover-elements-wrapper, show-error">
                                        <div class="toggler-slider">
                                            <div class="toggler-knob"></div>
                                        </div>
                                    </label>

                                    <!-- Custom Hover Selector -->
                                    <div class="input-group mt-2 hover-selector-wrapper" id="text_hover_trigger_custom_wrapper" style="
                                    <?php
                                    if ('off' === $hover_trigger_custom_value ) {
                                        echo esc_attr('display: none;');
                                    }
                                    ?>
                                    ">
                                        <span class="input-group-text" id="text_custom_hover_selector"><?php echo esc_html__('CSS Selector', 'wpcustom-cursors'); ?></span>
                                        <input type="text" value="<?php echo esc_attr($hover_trigger_selector_value); ?>" class="form-control" placeholder="<?php echo esc_html__('e.g. .btn', 'wpcustom-cursors'); ?>" aria-label="<?php echo esc_html__('Selector', 'wpcustom-cursors'); ?>" aria-describedby="text_custom_hover_selector" id="text_hover_trigger_selector" data-name="selector" data-default="<?php echo esc_attr($hover_trigger_selector_value); ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Text Type Dropdown -->
                            <div class="form-group mb-3">
                                <label for="hover_text_type" class="title-normal"><?php echo esc_html__('Text Type:', 'wpcustom-cursors'); ?></label>
                                <select class="form-control mt-2" id="hover_text_type" data-name='hover_text_type' data-select="hover_hr_text_type" data-state="hover" data-default="<?php echo esc_attr($hover_text_type_value); ?>">
                                    <option value="text" 
                                    <?php
                                    if (isset($hover_text_type_value) ) {
                                        selected($hover_text_type_value, 'text');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Circular', 'wpcustom-cursors'); ?></option>
                                    <option value="horizontal" 
                                    <?php
                                    if (isset($hover_text_type_value) ) {
                                        selected($hover_text_type_value, 'horizontal');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Horizontal', 'wpcustom-cursors'); ?></option>
                                </select>
                            </div>

                            <!-- Text Content -->
                            <label for="hover_cursor_text_input" class="form-label"><?php echo esc_html__('Text for the cursor', 'wpcustom-cursors'); ?></label>
                            <input type="text" data-apply="hover_textpath" value="<?php echo esc_attr($hover_cursor_text_value); ?>" class="form-control" placeholder="<?php echo esc_html__('Enter Text', 'wpcustom-cursors'); ?>" aria-label="<?php echo esc_html__('Text Cursor', 'wpcustom-cursors'); ?>" id="hover_cursor_text_input" data-name="hover_text" data-default="<?php echo esc_attr($hover_cursor_text_value); ?>">

                            <!-- Dot -->
                            <label class="toggler-wrapper mt-3 style-4"> 
                                <span class="toggler-label"><?php echo esc_html__('Show Dot', 'wpcustom-cursors'); ?></span>
                                <input type="checkbox" id="hover_show_dot" data-apply="hover_svg_node" value="on" <?php checked($hover_show_dot_value, 'on'); ?> data-name="hover_dot" data-default="<?php echo esc_attr($hover_show_dot_value); ?>" data-toggle="dot-options">
                                <div class="toggler-slider">
                                    <div class="toggler-knob"></div>
                                </div>
                            </label>

                            <div class="border p-4 rounded-3 mt-3 dot-options" id="hover_dot_options" style="
                            <?php
                            if ('off' === $hover_show_dot_value ) {
                                echo esc_attr('display:none');
                            }
                            ?>
                            ">
                                <!-- Dot Width -->
                                <label for="hover_dot_width" class="title-normal mt-3"><?php echo esc_html__('Dot Width:', 'wpcustom-cursors'); ?></label>

                                <div class="d-flex align-items-center mt-2">
                                    <input type="range" data-apply="hover_svg_circle_node" data-variable="dot-width" data-unit="px" class="form-range me-2" min="10" max="200" id="hover_dot_width_range" value="<?php echo esc_attr($hover_dot_width_value); ?>">
                                    <input type="number" data-apply="hover_svg_circle_node" data-variable="dot-width" data-unit="px" min="10" max="200" id="hover_dot_width" class="number-input" value="<?php echo esc_attr($hover_dot_width_value); ?>" data-name="hover_dot_width" data-default="<?php echo esc_attr($hover_dot_width_value); ?>">
                                    <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                                </div>

                                <!-- Dot Color -->
                                <div class="title-normal mt-3">
                                    <?php echo esc_html__('Dot Color:', 'wpcustom-cursors'); ?>
                                </div>
                                <div class="color_select form-group mt-2">
                                    <label class="w-100">
                                        <input type='text' class="form-control basic wp-custom-cursor-color-picker" data-apply="hover_svg_node" data-variable="dot-fill" id="hover_dot_color" value="<?php echo esc_attr($hover_dot_color_value); ?>" data-name="dot_color" data-default="<?php echo esc_attr($hover_dot_color_value); ?>">
                                    </label>
                                </div>
                            </div>

                            <!-- Width -->
                            <label for="text_width" class="title-normal mt-3"><?php echo esc_html__('Width:', 'wpcustom-cursors'); ?></label>

                            <div class="d-flex align-items-center mt-2">
                                <input type="range" data-apply="hover_svg_node" data-variable="text-width" data-unit="px" class="form-range me-2" min="50" max="500" id="hover_text_width_range" value="<?php echo esc_attr($hover_text_width_value); ?>">
                                <input type="number" data-apply="hover_svg_node" data-variable="text-width" data-unit="px" min="50" max="500" id="hover_text_width" class="number-input" value="<?php echo esc_attr($hover_text_width_value); ?>" data-name="width" data-default="<?php echo esc_attr($hover_text_width_value); ?>">
                                <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                            </div>

                            <!-- Text Transform -->
                            <div class="form-group">
                                <label for="hover_text_transform" class="title-normal mt-3"><?php echo esc_html__('Text Transform:', 'wpcustom-cursors'); ?></label>
                                <select class="form-control mt-2" id="hover_text_transform" data-apply="hover_svg_node" data-variable="text-transform" data-name="hover_text_transform" data-default="<?php echo esc_attr($hover_text_transform_value); ?>">
                                    <option value="uppercase" 
                                    <?php
                                    if (isset($hover_text_transform_value) ) {
                                        selected($hover_text_transform_value, 'uppercase');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Uppercase', 'wpcustom-cursors'); ?></option>
                                    <option value="lowercase" 
                                    <?php
                                    if (isset($hover_text_transform_value) ) {
                                        selected($hover_text_transform_value, 'lowercase');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Lowercase', 'wpcustom-cursors'); ?></option>
                                    <option value="capitalize" 
                                    <?php
                                    if (isset($hover_text_transform_value) ) {
                                        selected($hover_text_transform_value, 'capitalize');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Capitalize', 'wpcustom-cursors'); ?></option>
                                </select>
                            </div>

                            <!-- Font Weight -->
                            <div class="form-group">
                                <label for="hover_font_weight" class="title-normal mt-3"><?php echo esc_html__('Font Weight:', 'wpcustom-cursors'); ?></label>
                                <select class="form-control mt-2" id="hover_font_weight" data-apply="hover_svg_node" data-variable="font-weight" data-name="hover_font_weight" data-default="<?php echo esc_attr($hover_font_weight_value); ?>">
                                    <option value="normal" 
                                    <?php
                                    if (isset($hover_font_weight_value) ) {
                                        selected($hover_font_weight_value, 'normal');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Normal', 'wpcustom-cursors'); ?></option>
                                    <option value="bold" 
                                    <?php
                                    if (isset($hover_font_weight_value) ) {
                                        selected($hover_font_weight_value, 'bold');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Bold', 'wpcustom-cursors'); ?></option>
                                </select>
                            </div>

                            <!-- Text Color -->
                            <div class="title-normal mt-3">
                                <?php echo esc_html__('Text Color:', 'wpcustom-cursors'); ?>
                            </div>
                            <div class="color_select form-group mt-2">
                                <label class="w-100">
                                    <input type='text' class="form-control basic wp-custom-cursor-color-picker" data-apply="hover_svg_node" data-variable="text-color" id="hover_text_color" value="<?php echo esc_attr($hover_text_color_value); ?>" data-name="hover_text_color" data-default="<?php echo esc_attr($hover_text_color_value); ?>">
                                </label>
                            </div>

                            <!-- Font Size -->
                            <label for="hover_font_size" class="title-normal mt-3"><?php echo esc_html__('Font Size:', 'wpcustom-cursors'); ?></label>

                            <div class="d-flex align-items-center mt-2">
                                <input type="range" data-apply="hover_svg_node" data-variable="font-size" data-unit="px" class="form-range me-2" min="10" max="200" id="hover_font_size_range" value="<?php echo esc_attr($hover_font_size_value); ?>">
                                <input type="number" data-apply="hover_svg_node" data-variable="font-size" data-unit="px" min="10" max="200" id="hover_font_size" class="number-input" value="<?php echo esc_attr($hover_font_size_value); ?>" data-name="font_size" data-default="<?php echo esc_attr($hover_font_size_value); ?>">
                                <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                            </div>

                            <!-- Word Spacing -->
                            <label for="hover_word_spacing" class="title-normal mt-3"><?php echo esc_html__('Word Spacing:', 'wpcustom-cursors'); ?></label>

                            <div class="d-flex align-items-center mt-2">
                                <input type="range" data-apply="hover_svg_node" data-variable="word-spacing" data-unit="px" class="form-range me-2" min="1" max="200" id="hover_word_spacing_range" value="<?php echo esc_attr($hover_word_spacing_value); ?>">
                                <input type="number" data-apply="hover_svg_node" data-variable="word-spacing" data-unit="px" min="1" max="200" id="hover_word_spacing" class="number-input" value="<?php echo esc_attr($hover_word_spacing_value); ?>" data-name="hover_word_spacing" data-default="<?php echo esc_attr($hover_word_spacing_value); ?>">
                                <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                            </div>

                            <!-- Animation -->
                            <div class="form-group">
                                <label for="hover_text_animation" class="title-normal mt-3"><?php echo esc_html__('Text Animation:', 'wpcustom-cursors'); ?></label>
                                <select class="form-control mt-2" id="hover_text_animation" data-apply="hover_svg_node" data-variable="animation-name" data-name="hover_animation" data-default="<?php echo esc_attr($hover_text_animation_value); ?>">
                                    <option value="none" 
                                    <?php
                                    if (isset($hover_text_animation_value) ) {
                                        selected($hover_text_animation_value, 'none');
                                    }
                                    ?>
                                    ><?php echo esc_html__('None', 'wpcustom-cursors'); ?></option>
                                    <option value="spinright" 
                                    <?php
                                    if (isset($hover_text_animation_value) ) {
                                        selected($hover_text_animation_value, 'spinright');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Spin Right', 'wpcustom-cursors'); ?></option>
                                    <option value="spinleft" 
                                    <?php
                                    if (isset($hover_text_animation_value) ) {
                                        selected($hover_text_animation_value, 'spinleft');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Spin Left', 'wpcustom-cursors'); ?></option>
                                </select>
                            </div>

                            <!-- Text Animation Duration -->
                            <label for="hover_text_animation_duration" class="title-normal mt-3"><?php echo esc_html__('Text Animation Duration:', 'wpcustom-cursors'); ?></label>

                            <div class="d-flex align-items-center mt-2">
                                <input type="range" data-apply="hover_svg_node" data-variable="animation-duration" data-unit="s" class="form-range me-2" min="0" max="100" id="hover_text_animation_duration_range" value="<?php echo esc_attr($hover_text_animation_duration_value); ?>">
                                <input type="number" data-apply="hover_svg_node" data-variable="animation-duration" data-unit="s" min="0" max="100" id="hover_text_animation_duration" class="number-input" value="<?php echo esc_attr($hover_text_animation_duration_value); ?>" data-name="hover_animation_duration" data-default="<?php echo esc_attr($hover_text_animation_duration_value); ?>">
                                <span class="ms-2 small"><?php echo esc_html__('S', 'wpcustom-cursors'); ?></span>
                            </div>
                        </div>

                        <!-- Horizontal Text Options - Hover -->
                        <div id="hover_horizontal_options" style="
                        <?php
                        if ('text' !== $hover_cursor_type_value || 'horizontal' !== $hover_text_type_value || 'normal' === $normal_hover_state ) {
                            echo esc_attr('display: none;'); 
                        }
                        ?>
                        ">

                            <div class="row hover-elements-wrapper py-2 rounded">
                                <div class="col-md-12">
                                    <div class="error-message align-items-center text-danger mb-2">
                                        <i class="ri-error-warning-line me-1"></i><span class="small"><?php echo esc_html__('You need to select at least one!', 'wpcustom-cursors'); ?></span>
                                    </div>
                                    <div class="title-normal">
                                        <?php echo esc_html__('Where to show this hover cursor?', 'wpcustom-cursors'); ?>
                                    </div>
                                    <!-- Links -->
                                    <label class="toggler-wrapper mt-2 style-4"> 
                                        <span class="toggler-label"><?php echo esc_html__('On Links', 'wpcustom-cursors'); ?></span>
                                        <input type="checkbox" id="horizontal_hover_trigger_link" value="<?php echo esc_attr($hover_trigger_link_value); ?>" <?php checked($hover_trigger_link_value, 'on'); ?> data-name="links" data-default="<?php echo esc_attr($hover_trigger_link_value); ?>" data-off="horizontal_hover_trigger_custom" data-hide-error="hover-elements-wrapper, show-error">
                                        <div class="toggler-slider">
                                            <div class="toggler-knob"></div>
                                        </div>
                                    </label>

                                    <!-- Buttons -->
                                    <label class="toggler-wrapper mt-2 style-4"> 
                                        <span class="toggler-label"><?php echo esc_html__('On Buttons', 'wpcustom-cursors'); ?></span>
                                        <input type="checkbox" id="horizontal_hover_trigger_button" value="<?php echo esc_attr($hover_trigger_button_value); ?>" <?php checked($hover_trigger_button_value, 'on'); ?> data-name="buttons" data-default="<?php echo esc_attr($hover_trigger_button_value); ?>" data-off="horizontal_hover_trigger_custom" data-hide-error="hover-elements-wrapper, show-error">
                                        <div class="toggler-slider">
                                            <div class="toggler-knob"></div>
                                        </div>
                                    </label>

                                    <!-- Images -->
                                    <label class="toggler-wrapper mt-2 style-4"> 
                                        <span class="toggler-label"><?php echo esc_html__('On Images', 'wpcustom-cursors'); ?></span>
                                        <input type="checkbox" id="horizontal_hover_trigger_image" value="<?php echo esc_attr($hover_trigger_image_value); ?>" <?php checked($hover_trigger_image_value, 'on'); ?> data-name="images" data-default="<?php echo esc_attr($hover_trigger_image_value); ?>" data-off="horizontal_hover_trigger_custom" data-hide-error="hover-elements-wrapper, show-error">
                                        <div class="toggler-slider">
                                            <div class="toggler-knob"></div>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <!-- Custom Element -->
                                    <label class="toggler-wrapper style-4"> 
                                        <span class="toggler-label"><?php echo esc_html__('On Custom Element', 'wpcustom-cursors'); ?></span>
                                        <input type="checkbox" id="horizontal_hover_trigger_custom" value="<?php echo esc_attr($hover_trigger_custom_value); ?>" <?php checked($hover_trigger_custom_value, 'on'); ?> data-toggle="hover-selector-wrapper" data-name="custom" data-default="<?php echo esc_attr($hover_trigger_custom_value); ?>" data-off="horizontal_hover_trigger_image,horizontal_hover_trigger_button,horizontal_hover_trigger_link" data-hide-error="hover-elements-wrapper, show-error">
                                        <div class="toggler-slider">
                                            <div class="toggler-knob"></div>
                                        </div>
                                    </label>

                                    <!-- Custom Hover Selector -->
                                    <div class="input-group mt-2 hover-selector-wrapper" id="horizontal_hover_trigger_custom_wrapper" style="
                                    <?php
                                    if ('off' === $hover_trigger_custom_value ) {
                                        echo esc_attr('display: none;');
                                    }
                                    ?>
                                    ">
                                        <span class="input-group-text" id="horizontal_custom_hover_selector"><?php echo esc_html__('CSS Selector', 'wpcustom-cursors'); ?></span>
                                        <input type="text" value="<?php echo esc_attr($hover_trigger_selector_value); ?>" class="form-control" placeholder="<?php echo esc_html__('e.g. .btn', 'wpcustom-cursors'); ?>" aria-label="<?php echo esc_html__('Selector', 'wpcustom-cursors'); ?>" aria-describedby="horizontal_custom_hover_selector" id="horizontal_hover_trigger_selector" data-name="selector" data-default="<?php echo esc_attr($hover_trigger_selector_value); ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Text Type Dropdown -->
                            <div class="form-group mb-3">
                                <label for="hover_hr_text_type" class="title-normal"><?php echo esc_html__('Text Type:', 'wpcustom-cursors'); ?></label>
                                <select class="form-control mt-2" id="hover_hr_text_type" data-name='hover_text_type' data-select="hover_text_type" data-state="hover" data-default="<?php echo esc_attr($hover_text_type_value); ?>">
                                    <option value="text" 
                                    <?php
                                    if (isset($hover_text_type_value) ) {
                                        selected($hover_text_type_value, 'text');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Circular', 'wpcustom-cursors'); ?></option>
                                    <option value="horizontal" 
                                    <?php
                                    if (isset($hover_text_type_value) ) {
                                        selected($hover_text_type_value, 'horizontal');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Horizontal', 'wpcustom-cursors'); ?></option>
                                </select>
                            </div>

                            <!-- Text Content -->
                            <label for="hover_hr_text_input" class="form-label"><?php echo esc_html__('Text for the cursor', 'wpcustom-cursors'); ?></label>
                            <input type="text" data-apply="hover_hr_text_container" value="<?php echo esc_attr($hover_hr_text_value); ?>" class="form-control" placeholder="<?php echo esc_html__('Enter Text', 'wpcustom-cursors'); ?>" aria-label="<?php echo esc_html__('Text Cursor', 'wpcustom-cursors'); ?>" id="hover_hr_text_input" data-name="hover_hr_text" data-default="<?php echo esc_attr($hover_hr_text_value); ?>">

                            <!-- Background Color -->
                            <div class="title-normal mt-3">
                                <?php echo esc_html__('Background Color:', 'wpcustom-cursors'); ?>
                            </div>
                            <div class="color_select form-group mt-2">
                                <label class="w-100">
                                    <input type='text' data-apply="hover_hr_text_container" data-variable="bg-color" class="form-control basic wp-custom-cursor-color-picker" id="hover_hr_background_color" value="<?php echo esc_attr($hover_hr_background_value); ?>" data-name="hover_hr_bgcolor" data-default="<?php echo esc_attr($hover_hr_background_value); ?>">
                                </label>
                            </div>

                            <!-- Radius -->
                            <label for="hover_hr_radius" class="title-normal mt-3"><?php echo esc_html__('Radius:', 'wpcustom-cursors'); ?></label>

                            <div class="d-flex align-items-center mt-2">
                                <input type="range" data-apply="hover_hr_text_container" data-variable="hr-radius" data-unit="px" class="form-range me-2" min="0" max="500" id="hover_hr_radius_range" value="<?php echo esc_attr($hover_hr_radius_value); ?>">
                                <input type="number" data-apply="hover_hr_text_container" data-variable="hr-radius" data-unit="px" min="0" max="500" id="hover_hr_radius" class="number-input" value="<?php echo esc_attr($hover_hr_radius_value); ?>" data-name="hover_hr_radius" data-default="<?php echo esc_attr($hover_hr_radius_value); ?>">
                                <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                            </div>

                            <!-- Padding -->
                            <label for="hover_hr_padding" class="title-normal mt-3"><?php echo esc_html__('Padding:', 'wpcustom-cursors'); ?></label>

                            <div class="d-flex align-items-center mt-2">
                                <input type="range" data-apply="hover_hr_text_container" data-variable="hr-padding" data-unit="px" class="form-range me-2" min="0" max="100" id="hover_hr_padding_range" value="<?php echo esc_attr($hover_hr_padding_value); ?>">
                                <input type="number" data-apply="hover_hr_text_container" data-variable="hr-padding" data-unit="px" min="0" max="100" id="hover_hr_padding" class="number-input" value="<?php echo esc_attr($hover_hr_padding_value); ?>" data-name="hover_hr_padding" data-default="<?php echo esc_attr($hover_hr_padding_value); ?>">
                                <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                            </div>

                            <!-- Width -->
                            <label for="hover_hr_width" class="title-normal mt-3"><?php echo esc_html__('Width:', 'wpcustom-cursors'); ?></label>

                            <div class="d-flex align-items-center mt-2">
                                <input type="range" data-apply="hover_hr_text_container" data-variable="hr-width" data-unit="px" class="form-range me-2" min="50" max="500" id="hover_hr_width_range" value="<?php echo esc_attr($hover_hr_width_value); ?>">
                                <input type="number" data-apply="hover_hr_text_container" data-variable="hr-width" data-unit="px" min="50" max="500" id="hover_hr_width" class="number-input" value="<?php echo esc_attr($hover_hr_width_value); ?>" data-name="hover_hr_width" data-default="<?php echo esc_attr($hover_hr_width_value); ?>">
                                <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                            </div>

                            <!-- Text Transform -->
                            <div class="form-group">
                                <label for="hover_hr_transform" class="title-normal mt-3"><?php echo esc_html__('Text Transform:', 'wpcustom-cursors'); ?></label>
                                <select class="form-control mt-2" id="hover_hr_transform" data-apply="hover_hr_text_container" data-variable="hr-transform" data-name="hover_hr_transform" data-default="<?php echo esc_attr($hover_hr_transform_value); ?>">
                                    <option value="uppercase" 
                                    <?php
                                    if (isset($hover_hr_transform_value) ) {
                                        selected($hover_hr_transform_value, 'uppercase');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Uppercase', 'wpcustom-cursors'); ?></option>
                                    <option value="lowercase" 
                                    <?php
                                    if (isset($hover_hr_transform_value) ) {
                                        selected($hover_hr_transform_value, 'lowercase');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Lowercase', 'wpcustom-cursors'); ?></option>
                                    <option value="capitalize" 
                                    <?php
                                    if (isset($hover_hr_transform_value) ) {
                                        selected($hover_hr_transform_value, 'capitalize');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Capitalize', 'wpcustom-cursors'); ?></option>
                                </select>
                            </div>

                            <!-- Font Weight -->
                            <div class="form-group">
                                <label for="hover_hr_weight" class="title-normal mt-3"><?php echo esc_html__('Font Weight:', 'wpcustom-cursors'); ?></label>
                                <select class="form-control mt-2" id="hover_hr_weight" data-apply="hover_hr_text_container" data-variable="hr-weight" data-name="hover_hr_weight" data-default="<?php echo esc_attr($hover_hr_weight_value); ?>">
                                    <option value="normal" 
                                    <?php
                                    if (isset($hover_hr_weight_value) ) {
                                        selected($hover_hr_weight_value, 'normal');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Normal', 'wpcustom-cursors'); ?></option>
                                    <option value="bold" 
                                    <?php
                                    if (isset($hover_hr_weight_value) ) {
                                        selected($hover_hr_weight_value, 'bold');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Bold', 'wpcustom-cursors'); ?></option>
                                </select>
                            </div>

                            <!-- Text Color -->
                            <div class="title-normal mt-3">
                                <?php echo esc_html__('Text Color:', 'wpcustom-cursors'); ?>
                            </div>
                            <div class="color_select form-group mt-2">
                                <label class="w-100">
                                    <input type='text' class="form-control basic wp-custom-cursor-color-picker" data-apply="hover_hr_text_container" data-variable="hr-color" id="hover_hr_color" value="<?php echo esc_attr($hover_hr_color_value); ?>" data-name="hover_hr_color" data-default="<?php echo esc_attr($hover_hr_color_value); ?>">
                                </label>
                            </div>

                            <!-- Font Size -->
                            <label for="hover_hr_size" class="title-normal mt-3"><?php echo esc_html__('Font Size:', 'wpcustom-cursors'); ?></label>

                            <div class="d-flex align-items-center mt-2">
                                <input type="range" data-apply="hover_hr_text_container" data-variable="hr-size" data-unit="px" class="form-range me-2" min="10" max="200" id="hover_hr_size_range" value="<?php echo esc_attr($hover_hr_size_value); ?>">
                                <input type="number" data-apply="hover_hr_text_container" data-variable="hr-size" data-unit="px" min="10" max="200" id="hover_hr_size" class="number-input" value="<?php echo esc_attr($hover_hr_size_value); ?>" data-name="hover_hr_size" data-default="<?php echo esc_attr($hover_hr_size_value); ?>">
                                <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                            </div>

                            <!-- Word Spacing -->
                            <label for="hover_hr_spacing" class="title-normal mt-3"><?php echo esc_html__('Word Spacing:', 'wpcustom-cursors'); ?></label>

                            <div class="d-flex align-items-center mt-2">
                                <input type="range" data-apply="hover_hr_text_container" data-variable="hr-spacing" data-unit="px" class="form-range me-2" min="1" max="200" id="hover_hr_spacing_range" value="<?php echo esc_attr($hover_hr_spacing_value); ?>">
                                <input type="number" data-apply="hover_hr_text_container" data-variable="hr-spacing" data-unit="px" min="1" max="200" id="hover_hr_spacing" class="number-input" value="<?php echo esc_attr($hover_hr_spacing_value); ?>" data-name="hover_hr_spacing" data-default="<?php echo esc_attr($hover_hr_spacing_value); ?>">
                                <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                            </div>

                            <!-- Transition Duration -->
                            <label for="hover_hr_duration" class="title-normal mt-3"><?php echo esc_html__('Transition Duration:', 'wpcustom-cursors'); ?></label>

                            <div class="d-flex align-items-center mt-2">
                                <input type="range" data-apply="hr_text_container" data-variable="hr_duration" data-unit="ms" class="form-range me-2" min="0" max="1000" id="hover_hr_duration_range" value="<?php echo esc_attr($hover_hr_duration_value); ?>">
                                <input type="number" data-apply="hover_hr_text_container" data-variable="hr_duration" data-unit="ms" min="0" max="1000" id="hover_hr_duration" class="number-input" value="<?php echo esc_attr($hover_hr_duration_value); ?>" data-name="hover_hr_duration" data-default="<?php echo esc_attr($hover_hr_duration_value); ?>">
                                <span class="ms-2 small"><?php echo esc_html__('MS', 'wpcustom-cursors'); ?></span>
                            </div>

                            <!-- Transition Timing Function -->
                            <div class="form-group">
                                <label for="hover_hr_timing" class="title-normal mt-3"><?php echo esc_html__('Transition Timing Function:', 'wpcustom-cursors'); ?></label>
                                <select class="form-control mt-2" id="hover_hr_timing" data-apply="hover_hr_text_container" data-variable="hr_timing" data-name="hover_hr_timing" data-default="<?php echo esc_attr($hover_hr_timing_value); ?>">
                                    <option value="ease" 
                                    <?php
                                    if (isset($hover_hr_timing_value) ) {
                                        selected($hover_hr_timing_value, 'ease');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Ease', 'wpcustom-cursors'); ?></option>
                                    <option value="ease-in" 
                                    <?php
                                    if (isset($hover_hr_timing_value) ) {
                                        selected($hover_hr_timing_value, 'ease-in');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Ease In', 'wpcustom-cursors'); ?></option>
                                    <option value="ease-out" 
                                    <?php
                                    if (isset($hover_hr_timing_value) ) {
                                        selected($hover_hr_timing_value, 'ease-out');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Ease Out', 'wpcustom-cursors'); ?></option>
                                    <option value="ease-in-out" 
                                    <?php
                                    if (isset($hover_hr_timing_value) ) {
                                        selected($hover_hr_timing_value, 'ease-in-out');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Ease In Out', 'wpcustom-cursors'); ?></option>
                                    <option value="linear" 
                                    <?php
                                    if (isset($hover_hr_timing_value) ) {
                                        selected($hover_hr_timing_value, 'linear');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Linear', 'wpcustom-cursors'); ?></option>
                                </select>
                            </div>

                            <!-- Horizontal Text Backdrop Filter -->
                            <div class="form-group">
                                <label for="hover_hr_backdrop" class="title-normal mt-3"><?php echo esc_html__('Backdrop Filter:', 'wpcustom-cursors'); ?></label>
                                <select class="form-control mt-2" id="hover_hr_backdrop" data-name="hover_hr_backdrop" data-default="<?php echo esc_attr($hover_hr_backdrop_value); ?>">
                                    <option value="none" 
                                    <?php
                                    if (isset($hover_hr_backdrop_value) ) {
                                        selected($hover_hr_backdrop_value, 'none');
                                    }
                                    ?>
                                    ><?php echo esc_html__('None', 'wpcustom-cursors'); ?></option>
                                    <option value="blur" 
                                    <?php
                                    if (isset($hover_hr_backdrop_value) ) {
                                        selected($hover_hr_backdrop_value, 'blur');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Blur', 'wpcustom-cursors'); ?></option>
                                    <option value="brightness" 
                                    <?php
                                    if (isset($hover_hr_backdrop_value) ) {
                                        selected($hover_hr_backdrop_value, 'brightness');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Brightness', 'wpcustom-cursors'); ?></option>
                                    <option value="contrast" 
                                    <?php
                                    if (isset($hover_hr_backdrop_value) ) {
                                        selected($hover_hr_backdrop_value, 'contrast');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Contrast', 'wpcustom-cursors'); ?></option>
                                    <option value="drop-shadow" 
                                    <?php
                                    if (isset($hover_hr_backdrop_value) ) {
                                        selected($hover_hr_backdrop_value, 'drop-shadow');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Drop Shadow', 'wpcustom-cursors'); ?></option>
                                    <option value="grayscale" 
                                    <?php
                                    if (isset($hover_hr_backdrop_value) ) {
                                        selected($hover_hr_backdrop_value, 'grayscale');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Grayscale', 'wpcustom-cursors'); ?></option>
                                    <option value="hue-rotate" 
                                    <?php
                                    if (isset($hover_hr_backdrop_value) ) {
                                        selected($hover_hr_backdrop_value, 'hue-rotate');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Hue Rotate', 'wpcustom-cursors'); ?></option>
                                    <option value="invert" 
                                    <?php
                                    if (isset($hover_hr_backdrop_value) ) {
                                        selected($hover_hr_backdrop_value, 'invert');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Invert', 'wpcustom-cursors'); ?></option>
                                    <option value="opacity" 
                                    <?php
                                    if (isset($hover_hr_backdrop_value) ) {
                                        selected($hover_hr_backdrop_value, 'opacity');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Opacity', 'wpcustom-cursors'); ?></option>
                                    <option value="sepia" 
                                    <?php
                                    if (isset($hover_hr_backdrop_value) ) {
                                        selected($hover_hr_backdrop_value, 'sepia');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Sepia', 'wpcustom-cursors'); ?></option>
                                    <option value="saturate" 
                                    <?php
                                    if (isset($hover_hr_backdrop_value) ) {
                                        selected($hover_hr_backdrop_value, 'saturate');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Saturate', 'wpcustom-cursors'); ?></option>
                                    <option value="revert" 
                                    <?php
                                    if (isset($hover_hr_backdrop_value) ) {
                                        selected($hover_hr_backdrop_value, 'revert');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Revert', 'wpcustom-cursors'); ?></option>
                                </select>
                            </div>

                            <!-- Backdrop Filter Value -->
                            <div class="form-floating mt-3">
                                <input type="text" class="form-control" id="hover_hr_backdrop_amount" placeholder="<?php echo esc_html__('e.g. 2px', 'wpcustom-cursors'); ?>" value="<?php echo esc_attr($hover_hr_backdrop_amount_value); ?>" data-name="hover_hr_backdrop_amount" data-default="<?php echo esc_attr($hover_hr_backdrop_amount_value); ?>">
                                <label for="hover_hr_backdrop_amount"><?php echo esc_html__('Value with unit e.g. 2px', 'wpcustom-cursors'); ?></label>
                            </div>
                        </div>

                        <!-- Default Cursor Options - Hover -->
                        <div id="hover_default_options" style="
                        <?php
                        if ('default' !== $hover_cursor_type_value || 'normal' === $normal_hover_state ) {
                            echo esc_attr('display: none;'); 
                        }
                        ?>
                        ">
                            <div class="h5"><?php echo esc_html__('Use browser\'s default hover cursor.', 'wpcustom-cursors'); ?></div>
                            <div class="title-normal"><?php echo esc_html__('This is normally a hand pointer for links and buttons.', 'wpcustom-cursors'); ?></div>
                        </div>

                        <!-- Snap Cursor Options - Hover -->
                        <div id="hover_snap_options" style="
                        <?php
                        if ('snap' !== $hover_cursor_type_value || 'normal' === $normal_hover_state ) {
                            echo esc_attr('display: none;'); 
                        }
                        ?>
                        ">
                            <div class="title-normal"><?php echo esc_html__('Snap the cursor the the element on hover.', 'wpcustom-cursors'); ?></div>
                            <div class="row hover-elements-wrapper py-2 rounded">
                                <div class="col-md-12">
                                    <div class="error-message align-items-center text-danger mb-2">
                                        <i class="ri-error-warning-line me-1"></i><span class="small"><?php echo esc_html__('You need to select at least one!', 'wpcustom-cursors'); ?></span>
                                    </div>
                                    <div class="title-normal">
                                        <?php echo esc_html__('Where to show this hover cursor?', 'wpcustom-cursors'); ?>
                                    </div>
                                    <!-- Links -->
                                    <label class="toggler-wrapper mt-2 style-4"> 
                                        <span class="toggler-label"><?php echo esc_html__('On Links', 'wpcustom-cursors'); ?></span>
                                        <input type="checkbox" id="snap_hover_trigger_link" value="<?php echo esc_attr($hover_trigger_link_value); ?>" <?php checked($hover_trigger_link_value, 'on'); ?> data-name="links" data-default="<?php echo esc_attr($hover_trigger_link_value); ?>" data-off="snap_hover_trigger_custom" data-hide-error="hover-elements-wrapper, show-error">
                                        <div class="toggler-slider">
                                            <div class="toggler-knob"></div>
                                        </div>
                                    </label>

                                    <!-- Buttons -->
                                    <label class="toggler-wrapper mt-2 style-4"> 
                                        <span class="toggler-label"><?php echo esc_html__('On Buttons', 'wpcustom-cursors'); ?></span>
                                        <input type="checkbox" id="snap_hover_trigger_button" value="<?php echo esc_attr($hover_trigger_button_value); ?>" <?php checked($hover_trigger_button_value, 'on'); ?> data-name="buttons" data-default="<?php echo esc_attr($hover_trigger_button_value); ?>" data-off="snap_hover_trigger_custom" data-hide-error="hover-elements-wrapper, show-error">
                                        <div class="toggler-slider">
                                            <div class="toggler-knob"></div>
                                        </div>
                                    </label>

                                    <!-- Images -->
                                    <label class="toggler-wrapper mt-2 style-4"> 
                                        <span class="toggler-label"><?php echo esc_html__('On Images', 'wpcustom-cursors'); ?></span>
                                        <input type="checkbox" id="snap_hover_trigger_image" value="<?php echo esc_attr($hover_trigger_image_value); ?>" <?php checked($hover_trigger_image_value, 'on'); ?> data-name="images" data-default="<?php echo esc_attr($hover_trigger_image_value); ?>" data-off="snap_hover_trigger_custom" data-hide-error="hover-elements-wrapper, show-error">
                                        <div class="toggler-slider">
                                            <div class="toggler-knob"></div>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <!-- Custom Element -->
                                    <label class="toggler-wrapper style-4"> 
                                        <span class="toggler-label"><?php echo esc_html__('On Custom Element', 'wpcustom-cursors'); ?></span>
                                        <input type="checkbox" id="snap_hover_trigger_custom" value="<?php echo esc_attr($hover_trigger_custom_value); ?>" <?php checked($hover_trigger_custom_value, 'on'); ?> data-toggle="hover-selector-wrapper" data-name="custom" data-default="<?php echo esc_attr($hover_trigger_custom_value); ?>" data-off="snap_hover_trigger_image,snap_hover_trigger_button,snap_hover_trigger_link" data-hide-error="hover-elements-wrapper, show-error">
                                        <div class="toggler-slider">
                                            <div class="toggler-knob"></div>
                                        </div>
                                    </label>

                                    <!-- Custom Hover Selector -->
                                    <div class="input-group mt-2 hover-selector-wrapper" id="snap_hover_trigger_custom_wrapper" style="
                                    <?php
                                    if ('off' === $hover_trigger_custom_value ) {
                                        echo esc_attr('display: none;');
                                    }
                                    ?>
                                    ">
                                        <span class="input-group-text" id="snap_custom_hover_selector"><?php echo esc_html__('CSS Selector', 'wpcustom-cursors'); ?></span>
                                        <input type="text" value="<?php echo esc_attr($hover_trigger_selector_value); ?>" class="form-control" placeholder="<?php echo esc_html__('e.g. .btn', 'wpcustom-cursors'); ?>" aria-label="<?php echo esc_html__('Selector', 'wpcustom-cursors'); ?>" aria-describedby="snap_custom_hover_selector" id="text_hover_trigger_selector" data-name="selector" data-default="<?php echo esc_attr($hover_trigger_selector_value); ?>">
                                    </div>
                                </div>
                            </div>

                            <!-- Radius -->
                            <label for="hover_snap_radius" class="title-normal mt-3"><?php echo esc_html__('Radius:', 'wpcustom-cursors'); ?></label>

                            <div class="d-flex align-items-center mt-2">
                                <input type="range" data-apply="hover_snap_element" data-variable="radius" data-unit="px" class="form-range me-2" min="0" max="500" id="hover_snap_radius_range" value="<?php echo esc_attr($hover_snap_radius_value); ?>">
                                <input type="number" data-apply="hover_snap_element" data-variable="radius" data-unit="px" min="0" max="500" id="hover_snap_radius" class="number-input" value="<?php echo esc_attr($hover_snap_radius_value); ?>" data-name="radius" data-default="<?php echo esc_attr($hover_snap_radius_value); ?>">
                                <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                            </div>

                            <!-- Padding -->
                            <label for="hover_snap_padding" class="title-normal mt-3"><?php echo esc_html__('Padding:', 'wpcustom-cursors'); ?></label>

                            <div class="d-flex align-items-center mt-2">
                                <input type="range" data-apply="hover_snap_element" data-variable="padding" data-unit="px" class="form-range me-2" min="0" max="100" id="hover_snap_padding_range" value="<?php echo esc_attr($hover_snap_padding_value); ?>">
                                <input type="number" data-apply="hover_snap_element" data-variable="padding" data-unit="px" min="0" max="100" id="hover_snap_padding" class="number-input" value="<?php echo esc_attr($hover_snap_padding_value); ?>" data-name="padding" data-default="<?php echo esc_attr($hover_snap_padding_value); ?>">
                                <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                            </div>

                            <!-- Width -->
                            <label for="hover_snap_border_width" class="title-normal mt-3"><?php echo esc_html__('Border Width:', 'wpcustom-cursors'); ?></label>

                            <div class="d-flex align-items-center mt-2">
                                <input type="range" data-apply="hover_snap_element" data-variable="border-width" data-unit="px" class="form-range me-2" min="1" max="100" id="hover_snap_border_width_range" value="<?php echo esc_attr($hover_snap_border_width_value); ?>">
                                <input type="number" data-apply="hover_snap_element" data-variable="border-width" data-unit="px" min="1" max="100" id="hover_snap_border_width" class="number-input" value="<?php echo esc_attr($hover_snap_border_width_value); ?>" data-name="border_width" data-default="<?php echo esc_attr($hover_snap_border_width_value); ?>">
                                <span class="ms-2 small"><?php echo esc_html__('PX', 'wpcustom-cursors'); ?></span>
                            </div>

                            <!-- Border Color -->
                            <div class="title-normal mt-3">
                                <?php echo esc_html__('Border Color:', 'wpcustom-cursors'); ?>
                            </div>
                            <div class="color_select form-group mt-2">
                                <label class="w-100">
                                    <input type='text' data-apply="hover_snap_element" data-variable="border-color" class="form-control basic wp-custom-cursor-color-picker" id="hover_snap_border_color" value="<?php echo esc_attr($hover_snap_border_color_value); ?>" data-name="border_color" data-default="<?php echo esc_attr($hover_snap_border_color_value); ?>">
                                </label>
                            </div>

                            <!-- Background Color -->
                            <div class="title-normal mt-3">
                                <?php echo esc_html__('Background Color:', 'wpcustom-cursors'); ?>
                            </div>
                            <div class="color_select form-group mt-2">
                                <label class="w-100">
                                    <input type='text' data-apply="hover_snap_element" data-variable="bg-color" class="form-control basic wp-custom-cursor-color-picker" id="hover_snap_background_color" value="<?php echo esc_attr($hover_snap_background_value); ?>" data-name="bgcolor" data-default="<?php echo esc_attr($hover_snap_background_value); ?>">
                                </label>
                            </div>

                            <!-- Blending Mode -->
                            <div class="form-group">
                                <label for="hover_snap_blending" class="title-normal mt-3"><?php echo esc_html__('Blending Mode:', 'wpcustom-cursors'); ?></label>
                                <select class="form-control mt-2" id="hover_snap_blending" data-apply="hover_snap_element" data-variable="blending" data-name="blending" data-default="<?php echo esc_attr($hover_snap_blending_value); ?>">
                                    <option value="normal" 
                                    <?php
                                    if (isset($hover_snap_blending_value) ) {
                                        selected($hover_snap_blending_value, 'normal');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Normal', 'wpcustom-cursors'); ?></option>
                                    <option value="multiply" 
                                    <?php
                                    if (isset($hover_snap_blending_value) ) {
                                        selected($hover_snap_blending_value, 'multiply');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Multiply', 'wpcustom-cursors'); ?></option>
                                    <option value="screen" 
                                    <?php
                                    if (isset($hover_snap_blending_value) ) {
                                        selected($hover_snap_blending_value, 'screen');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Screen', 'wpcustom-cursors'); ?></option>
                                    <option value="overlay" 
                                    <?php
                                    if (isset($hover_snap_blending_value) ) {
                                        selected($hover_snap_blending_value, 'overlay');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Overlay', 'wpcustom-cursors'); ?></option>
                                    <option value="darken" 
                                    <?php
                                    if (isset($hover_snap_blending_value) ) {
                                        selected($hover_snap_blending_value, 'darken');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Darken', 'wpcustom-cursors'); ?></option>
                                    <option value="lighten" 
                                    <?php
                                    if (isset($hover_snap_blending_value) ) {
                                        selected($hover_snap_blending_value, 'lighten');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Lighten', 'wpcustom-cursors'); ?></option>
                                    <option value="color-dodge" 
                                    <?php
                                    if (isset($hover_snap_blending_value) ) {
                                        selected($hover_snap_blending_value, 'color-dodge');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Color Dodge', 'wpcustom-cursors'); ?></option>
                                    <option value="color-burn" 
                                    <?php
                                    if (isset($hover_snap_blending_value) ) {
                                        selected($hover_snap_blending_value, 'color-burn');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Color Burn', 'wpcustom-cursors'); ?></option>
                                    <option value="hard-light" 
                                    <?php
                                    if (isset($hover_snap_blending_value) ) {
                                        selected($hover_snap_blending_value, 'hard-light');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Hard Light', 'wpcustom-cursors'); ?></option>
                                    <option value="soft-light" 
                                    <?php
                                    if (isset($hover_snap_blending_value) ) {
                                        selected($hover_snap_blending_value, 'soft-light');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Soft Light', 'wpcustom-cursors'); ?></option>
                                    <option value="difference" 
                                    <?php
                                    if (isset($hover_snap_blending_value) ) {
                                        selected($hover_snap_blending_value, 'difference');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Difference', 'wpcustom-cursors'); ?></option>
                                    <option value="exclusion" 
                                    <?php
                                    if (isset($hover_snap_blending_value) ) {
                                        selected($hover_snap_blending_value, 'exclusion');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Exclusion', 'wpcustom-cursors'); ?></option>
                                    <option value="hue" 
                                    <?php
                                    if (isset($hover_snap_blending_value) ) {
                                        selected($hover_snap_blending_value, 'hue');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Hue', 'wpcustom-cursors'); ?></option>
                                    <option value="saturation" 
                                    <?php
                                    if (isset($hover_snap_blending_value) ) {
                                        selected($hover_snap_blending_value, 'saturation');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Saturation', 'wpcustom-cursors'); ?></option>
                                    <option value="color" 
                                    <?php
                                    if (isset($hover_snap_blending_value) ) {
                                        selected($hover_snap_blending_value, 'color');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Color', 'wpcustom-cursors'); ?></option>
                                    <option value="luminosity" 
                                    <?php
                                    if (isset($hover_snap_blending_value) ) {
                                        selected($hover_snap_blending_value, 'luminosity');
                                    }
                                    ?>
                                    ><?php echo esc_html__('Luminosity', 'wpcustom-cursors'); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                <div class="card-footer text-muted">

                    <!-- Input to store cursor data -->
                <input type="hidden" id="cursor_options" name="cursor_options" value="<?php echo esc_attr($cursor_options_value); ?>">

                <!-- Input to store Hover cursors -->
                <input type="hidden" id="hover_cursors" name="hover_cursors" value="<?php echo esc_attr($hover_cursors_value); ?>">

                <?php
                if (isset($_GET['edit_row']) ) {
                    ?>
                        <input type="hidden" name="update_id" value="<?php echo intval(esc_attr(sanitize_text_field($_GET['edit_row']))); ?>">
                        <input type="submit" name="update_created" class="btn btn-primary" value="<?php echo esc_html__('Update Cursor', 'wpcustom-cursors'); ?>" />
                    <?php
                } else {
                    ?>
                        <input disabled class="btn btn-primary" value="<?php echo esc_html__('Save Cursor (Pro)', 'wpcustom-cursors'); ?>" />
                    <?php
                }
                ?>
            </div>
            <?php wp_nonce_field('wpcc_create_cursor', 'wpcc_create_nonce'); ?>
        </form>
    </div>