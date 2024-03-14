<?php
/**
 * Settings.
 *
 * @author dligthart
 * @package wpcp
 * @subpackage view
 * @version 0.1
 */
?>
<div class="wrap">

    <form method="post" action="options.php">
        <?php settings_fields('wpcp-group'); ?>

        <h2><?php _e('WP-Copyright-Protection', 'wpcp'); ?></h2>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"><?php _e('Exclude Pages', 'wpcp'); ?></th>

                <td><input type="text" name="wpcp_exclude_pages"
                           value="<?php echo get_option('wpcp_exclude_pages'); ?>"/>
                    <p><?php _e('Enter page ids: comma-separated. e.g. 1,2,3,4', 'wpcp'); ?></td>

            </tr>
            <tr>
                <th scope="row"><?php _e('Disable for registered users', 'wpcp'); ?></th>
                <td>
                    <input type="checkbox" name="wpcp_disable_for_regusers"
                           value="1" <?php if (get_option('wpcp_disable_for_regusers')) {
                        echo 'checked="checked"';
                    } ?>/>
                    <p><?php _e('Toggle', 'wpcp'); ?></p>
                </td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>

    <div id="hero" class="section-hero">
        
        <div class="div-hero-text">
            <h1 class="h1 hero">
              
            </h1>
        </div>
        <div class="banner_text">
            
        </div>
    </div>

</div>

<div class="wpcp-footer">
    <span>By</span> <a href="https://daveligthart.com" target="_blank" title="Created by Dave Ligthart">
        <span>
            Dave Ligthart
        </span>
    </a>
    <cite>Happy to be of service.</cite>
</div>
