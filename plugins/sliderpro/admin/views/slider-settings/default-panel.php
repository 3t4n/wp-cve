<table>
    <tbody>
        <?php
            if ( isset( $panel['list'] ) ) {
                foreach ( $panel['list'] as $setting_name ) {
                    $setting = BQW_SliderPro_Settings::getSettings( $setting_name );
        ?>
                    <tr>
                        <td>
                            <label data-info="<?php echo $setting['description']; ?>" for="<?php echo $setting_name; ?>"><?php echo $setting['label']; ?></label>
                        </td>
                        <td>
                            <?php
                                $value = isset( $slider_settings ) && isset( $slider_settings[ $setting_name ] ) ? $slider_settings[ $setting_name ] : $setting['default_value'];

                                if ( $setting['type'] === 'number' || $setting['type'] === 'text' || $setting['type'] === 'mixed' ) {
                                    echo '<input id="' . $setting_name . '" class="setting" type="text" name="' . $setting_name . '" value="' . esc_attr( $value ) . '" />';
                                } else if ( $setting['type'] === 'boolean' ) {
                                    echo '<input id="' . $setting_name . '" class="setting" type="checkbox" name="' . $setting_name . '"' . ( $value === true ? ' checked="checked"' : '' ) . ' />';
                                } else if ( $setting['type'] === 'select' ) {
                                    echo'<select id="' . $setting_name . '" class="setting" name="' . $setting_name . '">';
                                    
                                    if ( $setting_name === 'thumbnail_image_size' ) {
                                        $image_sizes = get_intermediate_image_sizes();
                                        
                                        foreach ( $image_sizes as $image_size ) {
                                            echo '<option value="' . $image_size . '"' . ( $value === $image_size ? ' selected="selected"' : '' ) . '>' . $image_size . '</option>';
                                        }
                                    } else {
                                        foreach ( $setting['available_values'] as $value_name => $value_label ) {
                                            echo '<option value="' . $value_name . '"' . ( $value === $value_name ? ' selected="selected"' : '' ) . '>' . $value_label . '</option>';
                                        }
                                    }
                                    
                                    echo '</select>';
                                }
                            ?>
                        </td>
                    </tr>
        <?php
                }
            }
        ?>
    </tbody>
</table>

<?php
    $hide_info = get_option( 'sliderpro_hide_inline_info' );

    if ( $hide_info != true && isset( $panel['inline_info'] ) ) {
?>
        <div class="inline-info sidebar-slide-info">
            <input type="checkbox" id="show-hide-<?php echo $panel_name; ?>-info" class="show-hide-info">
            <label for="show-hide-<?php echo $panel_name; ?>-info" class="show-info"><?php _e( 'Show info', 'sliderpro' ); ?></label>
            <label for="show-hide-<?php echo $panel_name; ?>-info" class="hide-info"><?php _e( 'Hide info', 'sliderpro' ); ?></label>
            
            <div class="info-content">
                <?php 
                    foreach( $panel['inline_info'] as $inline_info_paragraph ) {
                        echo '<p>' . $inline_info_paragraph . '</p>';
                    }
                ?>
            </div>
        </div>
<?php
    }
?>