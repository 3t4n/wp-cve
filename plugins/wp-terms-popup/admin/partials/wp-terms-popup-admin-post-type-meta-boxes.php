<?php
/**
 * HTML for post type meta boxes.
 *
 * @link       https://linksoftwarellc.com
 * @since      2.0.0
 *
 * @package    Wp_Terms_Popup
 * @subpackage Wp_Terms_Popup/admin/partials
 */
?>
<?php wp_nonce_field('post-type-meta-boxes', 'terms_popupmeta_nonce'); ?>

<h3><?php _e('Buttons', $this->plugin_name); ?></h3>

<table class="wptp-meta-box">
    <tbody>
        <tr>
            <td><?php _e('Agree Button Text', $this->plugin_name); ?></td>
            <td><input style="width:50%;" type="text" name="terms_agreetxt" size="20" value="<?php echo esc_attr($meta_terms_agreetxt); ?>"></td>
        </tr>
        
        <tr>
            <td><?php _e('Decline Button Text', $this->plugin_name); ?></td>
            <td><input style="width:50%;" type="text" name="terms_disagreetxt" size="20" value="<?php echo esc_attr($meta_terms_disagreetxt); ?>"></td>
        </tr>
        
        <tr class="has-help">
            <td><?php _e('Decline URL Redirect', $this->plugin_name); ?></td>
            <td>
                <input style="width:100%;" type="text" name="terms_redirecturl" size="45" value="<?php echo esc_attr($meta_terms_redirecturl); ?>"><br>
                <small><?php _e('This URL is the website users will be sent to if they click the Decline button.', $this->plugin_name); ?></small>
            </td>
        </tr>

        <tr class="has-help">
            <td><?php _e('Buttons Always Visible?', $this->plugin_name); ?></td>
            <td>
                <input type="checkbox" id="terms_buttons_always_visible" name="terms_buttons_always_visible" value="1" <?php checked('1', (isset($meta_terms_buttons_always_visible) ? $meta_terms_buttons_always_visible : 0)); ?>>
                <?php _e('Turning this option on will show the buttons without having to scroll.', $this->plugin_name); ?>
            </td>
        </tr>
    </tbody>
</table>

<h3><?php _e('Age Verification', $this->plugin_name); ?></h3>

<table class="wptp-meta-box">
    <tbody>
        <tr>
            <td><?php _e('Turn Age Verification On?', $this->plugin_name); ?></td>
            <td><input type="checkbox" name="terms_age_on" value="1" <?php checked('1', (isset($meta_terms_age_on) ? $meta_terms_age_on : 0)); ?>></td>
        </tr>
        
        <tr>
            <td><?php _e('Minimum Age Requirement', $this->plugin_name); ?></td>
            <td><input class="small-text" type="number" name="terms_age_requirement" min="1" max="100" value="<?php echo esc_attr($meta_terms_age_requirement); ?>"></td>
        </tr>
        
        <tr>
            <td><?php _e('Age Verification Date Format', $this->plugin_name); ?></td>
            <td>
                <label class="wptpa-radio"><input type="radio" name="terms_age_date_format" value="M-D-Y" <?php checked('M-D-Y', $meta_terms_age_date_format); ?>> Month, Day, Year</label><br>
                <label class="wptpa-radio"><input type="radio" name="terms_age_date_format" value="D-M-Y" <?php checked('D-M-Y', $meta_terms_age_date_format); ?>> Day, Month, Year</label><br>
                <label class="wptpa-radio"><input type="radio" name="terms_age_date_format" value="Y-M-D" <?php checked('Y-M-D', $meta_terms_age_date_format); ?>> Year, Month, Day</label><br>
            </td>
        </tr>
    </tbody>
</table>