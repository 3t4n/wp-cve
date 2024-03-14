<table class="berocket_pagination_style">
    <tr>
        <td colspan="8"><h3><?php _e( 'Pagination style', 'pagination-styler-for-woocommerce' ) ?></h3></td>
    </tr>
    <tr>
        <td colspan="8"><h4><?php _e( 'Color', 'pagination-styler-for-woocommerce' ) ?></h4></td>
    </tr>
    <tr>
        <th><?php _e( 'Background color', 'pagination-styler-for-woocommerce' ) ?></th>
        <th><?php _e( 'Border color', 'pagination-styler-for-woocommerce' ) ?></th>
        <th><?php _e( 'Button text color', 'pagination-styler-for-woocommerce' ) ?></th>
        <th><?php _e( 'Button text color on mouse over', 'pagination-styler-for-woocommerce' ) ?></th>
        <th><?php _e( 'Button background color', 'pagination-styler-for-woocommerce' ) ?></th>
        <th><?php _e( 'Button background color on mouse over', 'pagination-styler-for-woocommerce' ) ?></th>
        <th><?php _e( 'Button border color', 'pagination-styler-for-woocommerce' ) ?></th>
        <th><?php _e( 'Button border color on mouse over', 'pagination-styler-for-woocommerce' ) ?></th>
    </tr>
    <tr>
        <?php 
        $colors = array(
            array('ul_style', 'background-color'),
            array('ul_style', 'border-color'),
            array('ul_li_a-span_style', 'color'),
            array('ul_li_a-span_hover_style', 'color'),
            array('ul_li_a-span_style', 'background-color'),
            array('ul_li_a-span_hover_style', 'background-color'),
            array('ul_li_style', 'border-color'),
            array('ul_li_hover_style', 'border-color'),
        );
        foreach($colors as $color) {
            echo '<td>';
            echo br_color_picker($settings_name.'[style_settings]['.implode('][', $color).']', br_get_value_from_array($options, $color), -1);
            echo '</td>';
        }
        ?>
    </tr>
    <tr>
        <td colspan="8"><h4><?php _e( 'Size', 'pagination-styler-for-woocommerce' ) ?></h4></td>
    </tr>
    <tr>
        <th><?php _e( 'Border width', 'pagination-styler-for-woocommerce' ) ?></th>
        <th><?php _e( 'Button border width', 'pagination-styler-for-woocommerce' ) ?></th>
        <th><?php _e( 'Paddings', 'pagination-styler-for-woocommerce' ) ?></th>
        <th><?php _e( 'Button paddings', 'pagination-styler-for-woocommerce' ) ?></th>
        <th><?php _e( 'Border round', 'pagination-styler-for-woocommerce' ) ?></th>
        <th><?php _e( 'Button border round', 'pagination-styler-for-woocommerce' ) ?></th>
        <th><?php _e( 'Padding between buttons', 'pagination-styler-for-woocommerce' ) ?></th>
        <th></th>
    </tr>
    <tr>
        <td>
            <p><?php _e( 'Top', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][ul_style][border-top-width]" type="text" value="<?php echo $options['ul_style']['border-top-width']; ?>">
            <p><?php _e( 'Bottom', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][ul_style][border-bottom-width]" type="text" value="<?php echo $options['ul_style']['border-bottom-width']; ?>">
            <p><?php _e( 'Left', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][ul_style][border-left-width]" type="text" value="<?php echo $options['ul_style']['border-left-width']; ?>">
            <p><?php _e( 'Right', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][ul_style][border-right-width]" type="text" value="<?php echo $options['ul_style']['border-right-width']; ?>">
        </td>
        <td>
            <p><?php _e( 'Top', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][ul_li_style][border-top-width]" type="text" value="<?php echo $options['ul_li_style']['border-top-width']; ?>">
            <p><?php _e( 'Bottom', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][ul_li_style][border-bottom-width]" type="text" value="<?php echo $options['ul_li_style']['border-bottom-width']; ?>">
            <p><?php _e( 'Left', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][ul_li_style][border-left-width]" type="text" value="<?php echo $options['ul_li_style']['border-left-width']; ?>">
            <p><?php _e( 'Right', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][ul_li_style][border-right-width]" type="text" value="<?php echo $options['ul_li_style']['border-right-width']; ?>">
        </td>
        <td>
            <p><?php _e( 'Top', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][ul_style][padding-top]" type="text" value="<?php echo $options['ul_style']['padding-top']; ?>">
            <p><?php _e( 'Bottom', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][ul_style][padding-bottom]" type="text" value="<?php echo $options['ul_style']['padding-bottom']; ?>">
            <p><?php _e( 'Left', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][ul_style][padding-left]" type="text" value="<?php echo $options['ul_style']['padding-left']; ?>">
            <p><?php _e( 'Right', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][ul_style][padding-right]" type="text" value="<?php echo $options['ul_style']['padding-right']; ?>">
        </td>
        <td>
            <p><?php _e( 'Top', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][ul_li_a-span_style][padding-top]" type="text" value="<?php echo $options['ul_li_a-span_style']['padding-top']; ?>">
            <p><?php _e( 'Bottom', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][ul_li_a-span_style][padding-bottom]" type="text" value="<?php echo $options['ul_li_a-span_style']['padding-bottom']; ?>">
            <p><?php _e( 'Left', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][ul_li_a-span_style][padding-left]" type="text" value="<?php echo $options['ul_li_a-span_style']['padding-left']; ?>">
            <p><?php _e( 'Right', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][ul_li_a-span_style][padding-right]" type="text" value="<?php echo $options['ul_li_a-span_style']['padding-right']; ?>">
        </td>
        <td>
            <p><?php _e( 'Top-Left', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][ul_style][border-top-left-radius]" type="text" value="<?php echo $options['ul_style']['border-top-left-radius']; ?>">
            <p><?php _e( 'Top-Right', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][ul_style][border-top-right-radius]" type="text" value="<?php echo $options['ul_style']['border-top-right-radius']; ?>">
            <p><?php _e( 'Bottom-Right', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][ul_style][border-bottom-right-radius]" type="text" value="<?php echo $options['ul_style']['border-bottom-right-radius']; ?>">
            <p><?php _e( 'Bottom-Left', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][ul_style][border-bottom-left-radius]" type="text" value="<?php echo $options['ul_style']['border-bottom-left-radius']; ?>">
        </td>
        <td>
            <p><?php _e( 'Top-Left', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][ul_li_style][border-top-left-radius]" type="text" value="<?php echo $options['ul_li_style']['border-top-left-radius']; ?>">
            <p><?php _e( 'Top-Right', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][ul_li_style][border-top-right-radius]" type="text" value="<?php echo $options['ul_li_style']['border-top-right-radius']; ?>">
            <p><?php _e( 'Bottom-Right', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][ul_li_style][border-bottom-right-radius]" type="text" value="<?php echo $options['ul_li_style']['border-bottom-right-radius']; ?>">
            <p><?php _e( 'Bottom-Left', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][ul_li_style][border-bottom-left-radius]" type="text" value="<?php echo $options['ul_li_style']['border-bottom-left-radius']; ?>">
        </td>
        <td>
            <p><?php _e( 'Top', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][ul_li_style][margin-top]" type="text" value="<?php echo $options['ul_li_style']['margin-top']; ?>">
            <p><?php _e( 'Bottom', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][ul_li_style][margin-bottom]" type="text" value="<?php echo $options['ul_li_style']['margin-bottom']; ?>">
            <p><?php _e( 'Left', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][ul_li_style][margin-left]" type="text" value="<?php echo $options['ul_li_style']['margin-left']; ?>">
            <p><?php _e( 'Right', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][ul_li_style][margin-right]" type="text" value="<?php echo $options['ul_li_style']['margin-right']; ?>">
        </td>
        <td></td>
    </tr>
</table>
<?php 
$buttons = array(
    'prev'      => __( 'Previous button', 'pagination-styler-for-woocommerce' ),
    'next'      => __( 'Next button', 'pagination-styler-for-woocommerce' ),
    'dots'      => __( 'Dots button', 'pagination-styler-for-woocommerce' ),
    'current'   => __( 'Current button', 'pagination-styler-for-woocommerce' ),
    'other'     => __( 'Other buttons', 'pagination-styler-for-woocommerce' ),
);
foreach($buttons as $button_id => $button_name) {
?>
<p>
    <label>
        <input class="br_use_spec_styles br_use_styles_<?php echo $button_id; ?>" data-id="<?php echo $button_id; ?>" name="<?php echo $settings_name; ?>[style_settings][use_styles][]" type="checkbox" value="<?php echo $button_id; ?>"<?php if ( isset($options['use_styles']) && is_array($options['use_styles']) && in_array($button_id, $options['use_styles']) ) echo ' checked'; ?>>
        <?php echo __( 'Special styles for', 'pagination-styler-for-woocommerce' ).' '.$button_name; ?>
    </label>
</p>
<table class="berocket_pagination_style berocket_pagi_styles_<?php echo $button_id; ?>"<?php if ( ! isset($options['use_styles']) || ! is_array($options['use_styles']) || ! in_array($button_id, $options['use_styles']) ) echo ' style="display: none;"'; ?>>
    <tr><th colspan="5" style="border-bottom: 1px solid #999;"><h3><?php echo $button_name; ?></h3></th></tr>
    <tr>
        <td>
            <h4><?php _e( 'Button text color', 'pagination-styler-for-woocommerce' ) ?></h4>
            <?php
            $color = array('buttons', $button_id, 'ul_li_a-span_style', 'color');
            echo br_color_picker($settings_name.'[style_settings]['.implode('][', $color).']', br_get_value_from_array($options, $color), -1);
            ?>
        </td>
        <th><?php _e( 'Button border width', 'pagination-styler-for-woocommerce' ) ?></th>
        <th><?php _e( 'Button paddings', 'pagination-styler-for-woocommerce' ) ?></th>
        <th><?php _e( 'Button border round', 'pagination-styler-for-woocommerce' ) ?></th>
        <th><?php _e( 'Padding between buttons', 'pagination-styler-for-woocommerce' ) ?></th>
    </tr>
    <tr>
        <td>
            <h4><?php _e( 'Button background color', 'pagination-styler-for-woocommerce' ) ?></h4>
            <?php
            $color = array('buttons', $button_id, 'ul_li_a-span_style', 'background-color');
            echo br_color_picker($settings_name.'[style_settings]['.implode('][', $color).']', br_get_value_from_array($options, $color), -1);
            ?>
        </td>
        <td rowspan="5">
            <p><?php _e( 'Top', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][buttons][<?php echo $button_id; ?>][ul_li_style][border-top-width]" type="text" value="<?php echo ( empty($options['buttons'][$button_id]['ul_li_style']['border-top-width']) ? '' : $options['buttons'][$button_id]['ul_li_style']['border-top-width'] ); ?>">
            <p><?php _e( 'Bottom', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][buttons][<?php echo $button_id; ?>][ul_li_style][border-bottom-width]" type="text" value="<?php echo ( empty($options['buttons'][$button_id]['ul_li_style']['border-bottom-width']) ? '' : $options['buttons'][$button_id]['ul_li_style']['border-bottom-width'] ); ?>">
            <p><?php _e( 'Left', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][buttons][<?php echo $button_id; ?>][ul_li_style][border-left-width]" type="text" value="<?php echo ( empty($options['buttons'][$button_id]['ul_li_style']['border-left-width']) ? '' : $options['buttons'][$button_id]['ul_li_style']['border-left-width'] ); ?>">
            <p><?php _e( 'Right', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][buttons][<?php echo $button_id; ?>][ul_li_style][border-right-width]" type="text" value="<?php echo ( empty($options['buttons'][$button_id]['ul_li_style']['border-right-width']) ? '' : $options['buttons'][$button_id]['ul_li_style']['border-right-width'] ); ?>">
        </td>
        <td rowspan="5">
            <p><?php _e( 'Top', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][buttons][<?php echo $button_id; ?>][ul_li_a-span_style][padding-top]" type="text" value="<?php echo ( empty($options['buttons'][$button_id]['ul_li_a-span_style']['padding-top']) ? '' : $options['buttons'][$button_id]['ul_li_a-span_style']['padding-top'] ); ?>">
            <p><?php _e( 'Bottom', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][buttons][<?php echo $button_id; ?>][ul_li_a-span_style][padding-bottom]" type="text" value="<?php echo ( empty($options['buttons'][$button_id]['ul_li_a-span_style']['padding-bottom']) ? '' : $options['buttons'][$button_id]['ul_li_a-span_style']['padding-bottom'] ); ?>">
            <p><?php _e( 'Left', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][buttons][<?php echo $button_id; ?>][ul_li_a-span_style][padding-left]" type="text" value="<?php echo ( empty($options['buttons'][$button_id]['ul_li_a-span_style']['padding-left']) ? '' : $options['buttons'][$button_id]['ul_li_a-span_style']['padding-left'] ); ?>">
            <p><?php _e( 'Right', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][buttons][<?php echo $button_id; ?>][ul_li_a-span_style][padding-right]" type="text" value="<?php echo ( empty($options['buttons'][$button_id]['ul_li_a-span_style']['padding-right']) ? '' : $options['buttons'][$button_id]['ul_li_a-span_style']['padding-right'] ); ?>">
        </td>
        <td rowspan="5">
            <p><?php _e( 'Top-Left', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][buttons][<?php echo $button_id; ?>][ul_li_style][border-top-left-radius]" type="text" value="<?php echo ( empty($options['buttons'][$button_id]['ul_li_style']['border-top-left-radius']) ? '' : $options['buttons'][$button_id]['ul_li_style']['border-top-left-radius'] ); ?>">
            <p><?php _e( 'Top-Right', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][buttons][<?php echo $button_id; ?>][ul_li_style][border-top-right-radius]" type="text" value="<?php echo ( empty($options['buttons'][$button_id]['ul_li_style']['border-top-right-radius']) ? '' : $options['buttons'][$button_id]['ul_li_style']['border-top-right-radius'] ); ?>">
            <p><?php _e( 'Bottom-Right', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][buttons][<?php echo $button_id; ?>][ul_li_style][border-bottom-right-radius]" type="text" value="<?php echo ( empty($options['buttons'][$button_id]['ul_li_style']['border-bottom-right-radius']) ? '' : $options['buttons'][$button_id]['ul_li_style']['border-bottom-right-radius'] ); ?>">
            <p><?php _e( 'Bottom-Left', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][buttons][<?php echo $button_id; ?>][ul_li_style][border-bottom-left-radius]" type="text" value="<?php echo ( empty($options['buttons'][$button_id]['ul_li_style']['border-bottom-left-radius']) ? '' : $options['buttons'][$button_id]['ul_li_style']['border-bottom-left-radius'] ); ?>">
        </td>
        <td rowspan="5">
            <p><?php _e( 'Top', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][buttons][<?php echo $button_id; ?>][ul_li_style][margin-top]" type="text" value="<?php echo ( empty($options['buttons'][$button_id]['ul_li_style']['margin-top']) ? '' : $options['buttons'][$button_id]['ul_li_style']['margin-top'] ); ?>">
            <p><?php _e( 'Bottom', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][buttons][<?php echo $button_id; ?>][ul_li_style][margin-bottom]" type="text" value="<?php echo ( empty($options['buttons'][$button_id]['ul_li_style']['margin-bottom']) ? '' : $options['buttons'][$button_id]['ul_li_style']['margin-bottom'] ); ?>">
            <p><?php _e( 'Left', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][buttons][<?php echo $button_id; ?>][ul_li_style][margin-left]" type="text" value="<?php echo ( empty($options['buttons'][$button_id]['ul_li_style']['margin-left']) ? '' : $options['buttons'][$button_id]['ul_li_style']['margin-left'] ); ?>">
            <p><?php _e( 'Right', 'pagination-styler-for-woocommerce' ) ?></p>
            <input name="<?php echo $settings_name; ?>[style_settings][buttons][<?php echo $button_id; ?>][ul_li_style][margin-right]" type="text" value="<?php echo ( empty($options['buttons'][$button_id]['ul_li_style']['margin-right']) ? '' : $options['buttons'][$button_id]['ul_li_style']['margin-right'] ); ?>">
        </td>
    </tr>
    <tr>
        <td>
            <h4><?php _e( 'Button border color', 'pagination-styler-for-woocommerce' ) ?></h4>
            <?php
            $color = array('buttons', $button_id, 'ul_li_style', 'border-color');
            echo br_color_picker($settings_name.'[style_settings]['.implode('][', $color).']', br_get_value_from_array($options, $color), -1);
            ?>
        </td>
    </tr>
    <?php if( ! in_array($button_id, array('dots', 'current')) ) { ?>
    <tr>
        <td>
            <h4><?php _e( 'Button text color on mouse over', 'pagination-styler-for-woocommerce' ) ?></h4>
            <?php
            $color = array('buttons', $button_id, 'ul_li_a-span_hover_style', 'color');
            echo br_color_picker($settings_name.'[style_settings]['.implode('][', $color).']', br_get_value_from_array($options, $color), -1);
            ?>
        </td>
    </tr>
    <tr>
        <td>
            <h4><?php _e( 'Button background color on mouse over', 'pagination-styler-for-woocommerce' ) ?></h4>
            <?php
            $color = array('buttons', $button_id, 'ul_li_a-span_hover_style', 'background-color');
            echo br_color_picker($settings_name.'[style_settings]['.implode('][', $color).']', br_get_value_from_array($options, $color), -1);
            ?>
        </td>
    </tr>
    <tr>
        <td>
            <h4><?php _e( 'Button border color on mouse over', 'pagination-styler-for-woocommerce' ) ?></h4>
            <?php
            $color = array('buttons', $button_id, 'ul_li_hover_style', 'border-color');
            echo br_color_picker($settings_name.'[style_settings]['.implode('][', $color).']', br_get_value_from_array($options, $color), -1);
            ?>
        </td>
    </tr>
    <?php } ?>
</table>
<?php } ?>
