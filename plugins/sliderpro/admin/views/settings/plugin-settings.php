<div class="wrap sliderpro-admin plugin-settings">
	<h2><?php _e( 'Plugin Settings', 'sliderpro' ); ?></h2>

	<form action="" method="post">
        <?php wp_nonce_field( 'plugin-settings-update', 'plugin-settings-nonce' ); ?>
        
        <table>
            <tr>
                <td>
                    <label for="load-stylesheets"><?php echo $plugin_settings['load_stylesheets']['label']; ?></label>
                </td>
                <td>
                    <select id="load-stylesheets" name="load_stylesheets">
                        <?php
                            foreach ( $plugin_settings['load_stylesheets']['available_values'] as $value_name => $value_label ) {
                                $selected = $value_name === $load_stylesheets ? ' selected="selected"' : '';
                                echo '<option value="' . $value_name . '"' . $selected . '>' . $value_label . '</option>';
                            }
                        ?>
                    </select>
                 </td>
                <td>
                    <p><?php echo $plugin_settings['load_stylesheets']['description']; ?></p>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="load-js-in-all-pages"><?php echo $plugin_settings['load_js_in_all_pages']['label']; ?></label>
                </td>
                <td>
                    <input type="checkbox" id="load-js-in-all-pages" name="load_js_in_all_pages" <?php echo $load_js_in_all_pages == true ? 'checked="checked"' : ''; ?>>
                </td>
                <td>
                    <p><?php echo $plugin_settings['load_js_in_all_pages']['description']; ?></p>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="load-unminified-scripts"><?php echo $plugin_settings['load_unminified_scripts']['label']; ?></label>
                </td>
                <td>
                    <input type="checkbox" id="load-unminified-scripts" name="load_unminified_scripts" <?php echo $load_unminified_scripts == true ? 'checked="checked"' : ''; ?>>
                </td>
                <td>
                    <p><?php echo $plugin_settings['load_unminified_scripts']['description']; ?></p>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="cache-expiry-interval"><?php echo $plugin_settings['cache_expiry_interval']['label']; ?></label>
                </td>
                <td>
                    <input type="text" id="cache-expiry-interval" name="cache_expiry_interval" value="<?php echo esc_attr( $cache_expiry_interval ); ?>"><span>hours</span>
                </td>
                <td>
                    <p><?php echo $plugin_settings['cache_expiry_interval']['description']; ?></p>

                    <a class="button-secondary clear-all-cache" data-nonce="<?php echo wp_create_nonce( 'clear-all-cache' ); ?>"><?php _e( 'Clear all cache now', 'sliderpro' ); ?></a>
                    <span class="spinner clear-cache-spinner"></span>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="max-sliders-on-page"><?php echo $plugin_settings['max_sliders_on_page']['label']; ?></label>
                </td>
                <td>
                    <input type="text" id="max-sliders-on-page" name="max_sliders_on_page" value="<?php echo esc_attr( $max_sliders_on_page ); ?>">
                </td>
                <td>
                    <p><?php echo $plugin_settings['max_sliders_on_page']['description']; ?></p>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="hide-inline-info"><?php echo $plugin_settings['hide_inline_info']['label']; ?></label>
                </td>
                <td>
                    <input type="checkbox" id="hide-inline-info" name="hide_inline_info" <?php echo $hide_inline_info == true ? 'checked="checked"' : ''; ?>>
                </td>
                <td>
                    <p><?php echo $plugin_settings['hide_inline_info']['description']; ?></p>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="hide-getting-started-info"><?php echo $plugin_settings['hide_getting_started_info']['label']; ?></label>
                </td>
                <td>
                    <input type="checkbox" id="hide-getting-started-info" name="hide_getting_started_info" <?php echo $hide_getting_started_info == true ? 'checked="checked"' : ''; ?>>
                </td>
                <td>
                    <p><?php echo $plugin_settings['hide_getting_started_info']['description']; ?></p>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="hide-image-size-warning"><?php echo $plugin_settings['hide_image_size_warning']['label']; ?></label>
                </td>
                <td>
                    <input type="checkbox" id="hide-image-size-warning" name="hide_image_size_warning" <?php echo $hide_image_size_warning == true ? 'checked="checked"' : ''; ?>>
                </td>
                <td>
                    <p><?php echo $plugin_settings['hide_image_size_warning']['description']; ?></p>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="access"><?php echo $plugin_settings['access']['label']; ?></label>
                </td>
                <td>
                    <select id="access" name="access">
                        <?php
                            foreach ( $plugin_settings['access']['available_values'] as $value_name => $value_label ) {
                                $selected = $value_name === $access ? ' selected="selected"' : '';
                                echo '<option value="' . $value_name . '"' . $selected . '>' . $value_label . '</option>';
                            }
                        ?>
                    </select>
                 </td>
                <td>
                    <p><?php echo $plugin_settings['access']['description']; ?></p>
                </td>
            </tr>
        </table>

    	<input type="submit" name="plugin_settings_update" class="button-primary" value="Update Settings" />
	</form>
</div>