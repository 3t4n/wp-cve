<?php if( is_object( $options['global']->button_titles ) ) {
    $options['global']->button_titles = (array)$options['global']->button_titles;
}?>
<div class="ep-setting-tab-content">
    <h2><?php esc_html_e( 'Labels', 'eventprime-event-calendar-management' );?></h2>
    <input type="hidden" name="em_setting_type" value="button_labels_settings">
</div>
<table class="form-table">
    <tbody>
        <?php foreach( $options['labelsections'] as $labels ) {?>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="button_titles_<?php echo esc_attr( $labels );?>">
                        <?php echo esc_html( $labels );?>
                    </label>
                </th>
                <td class="forminp forminp-text">
                    <input name="button_titles[<?php echo esc_attr( $labels );?>]" id="button_titles_<?php echo esc_attr( $labels );?>" class="regular-text" type="text" value="<?php if( isset( $options['global']->button_titles[$labels] ) && ! empty( $options['global']->button_titles[$labels] ) ) { echo esc_attr( $options['global']->button_titles[$labels] ) ; } ?>">
                    <?php if( ! empty( $options['buttons_help_text'][$labels] ) ) {?>
                        <div class="ep-help-tip-info ep-my-2 ep-text-muted">
                            <?php esc_html_e( $options['buttons_help_text'][$labels], 'eventprime-event-calendar-management' );?>
                        </div><?php
                    }?>
                </td>
            </tr><?php
        }?>
    </tbody>
</table>
<div class="ep-setting-tab-content">
    <h2><?php esc_html_e( 'Button', 'eventprime-event-calendar-management' );?></h2>
</div>
<table class="form-table">
    <tbody>
        <?php foreach( $options['buttonsections'] as $labels ) {?>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="button_titles_<?php echo $labels;?>">
                        <?php echo esc_html( $labels );?>
                    </label>
                </th>
                <td class="forminp forminp-text">
                    <input name="button_titles[<?php echo esc_attr( $labels );?>]" id="button_titles_<?php echo esc_attr( $labels );?>" class="regular-text" type="text" value="<?php if( isset( $options['global']->button_titles[$labels] ) && ! empty( $options['global']->button_titles[$labels] ) ) { echo esc_attr( $options['global']->button_titles[$labels] ) ; } ?>">
                </td>
            </tr><?php
        }?>
    </tbody>
</table>