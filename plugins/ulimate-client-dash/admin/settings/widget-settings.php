<?php


// Widget Page Content

add_action( 'ucd_settings_content', 'ucd_widget_options_page' );
function ucd_widget_options_page() {
    global $ucd_active_tab;
    if ( 'widget-settings' != $ucd_active_tab )
    return;
?>

  	<h3><?php _e( 'Dashboard Widgets', 'ultimate-client-dash' ); ?></h3>

    <!-- Begin settings form -->
    <form action="options.php" method="post">

    <?php
    settings_fields( 'ultimate-client-dash-widget' );
    do_settings_sections( 'ultimate-client-dash-widget' );
    $ucd_widget_body = get_option( 'ucd_widget_body' );
    $ucd_widget_two_body = get_option( 'ucd_widget_two_body' );
    $ucd_widget_three_body = get_option( 'ucd_widget_three_body' );
    $ucd_widget_four_body = get_option( 'ucd_widget_four_body' );
    ?>

            <!-- Dashboard Styling Option Section -->

            <div class="ucd-inner-wrapper settings-widgets">
            <p class="ucd-settings-desc" style="padding-bottom: 6px;">The dashboard widget option is a great way to customize and declutter the WordPress dashboard homepage. You can choose the row format you would like to display widgets in, and even create your own custom widgets. You can include things like text, links, videos, iframes, and more. These settings apply to all user roles.</p>

            <div class="ucd-form-message"><?php settings_errors('ucd-notices'); ?></div>

                <!-- Table Start -->
                <table class="form-table">
                <tbody>

                    <div class="ucd-dynamic-item">
                        <div class="ucd-item-name"><?php _e( 'Welcome', 'ultimate-client-dash' ) ?></div>
                        <div class="ucd-item-capabilities">
                            <div class="ucd-client"><label class="ucd-switch"><input type="checkbox" name="ucd_widget_welcome" value="#welcome-panel" <?php checked( '#welcome-panel', get_option( 'ucd_widget_welcome' ) ); ?> /><span class="ucd-slider round"></span></label>Hide</div>
                        </div>
                    </div>

                    <div class="ucd-dynamic-item">
                        <div class="ucd-item-name"><?php _e( 'Try Glutenberg', 'ultimate-client-dash' ) ?></div>
                        <div class="ucd-item-capabilities">
                            <div class="ucd-client"><label class="ucd-switch"><input type="checkbox" name="ucd_widget_glutenberg" value="try_gutenberg_panel" <?php checked( 'try_gutenberg_panel', get_option( 'ucd_widget_glutenberg' ) ); ?> /><span class="ucd-slider round"></span></label>Hide</div>
                        </div>
                    </div>

                    <div class="ucd-dynamic-item">
                        <div class="ucd-item-name"><?php _e( 'WordPress Event & News', 'ultimate-client-dash' ) ?></div>
                        <div class="ucd-item-capabilities">
                            <div class="ucd-client"><label class="ucd-switch"><input type="checkbox" name="ucd_widget_primary" value="#dashboard_primary" <?php checked( '#dashboard_primary', get_option( 'ucd_widget_primary' ) ); ?> /><span class="ucd-slider round"></span></label>Hide</div>
                        </div>
                    </div>

                    <div class="ucd-dynamic-item">
                        <div class="ucd-item-name"><?php _e( 'Other WordPress News', 'ultimate-client-dash' ) ?></div>
                        <div class="ucd-item-capabilities">
                            <div class="ucd-client"><label class="ucd-switch"><input type="checkbox" name="ucd_widget_secondary" value="dashboard_secondary" <?php checked( 'dashboard_secondary', get_option( 'ucd_widget_secondary' ) ); ?> /><span class="ucd-slider round"></span></label>Hide</div>
                        </div>
                    </div>

                    <div class="ucd-dynamic-item">
                        <div class="ucd-item-name"><?php _e( 'Activity', 'ultimate-client-dash' ) ?></div>
                        <div class="ucd-item-capabilities">
                            <div class="ucd-client"><label class="ucd-switch"><input type="checkbox" name="ucd_widget_activity" value="#dashboard_activity" <?php checked( '#dashboard_activity', get_option( 'ucd_widget_activity' ) ); ?> /><span class="ucd-slider round"></span></label>Hide</div>
                        </div>
                    </div>

                    <div class="ucd-dynamic-item">
                        <div class="ucd-item-name"><?php _e( 'At A Glance', 'ultimate-client-dash' ) ?></div>
                        <div class="ucd-item-capabilities">
                            <div class="ucd-client"><label class="ucd-switch"><input type="checkbox" name="ucd_widget_glance" value="#dashboard_right_now" <?php checked( '#dashboard_right_now', get_option( 'ucd_widget_glance' ) ); ?> /><span class="ucd-slider round"></span></label>Hide</div>
                        </div>
                    </div>

                    <div class="ucd-dynamic-item">
                        <div class="ucd-item-name"><?php _e( 'Quick Draft', 'ultimate-client-dash' ) ?></div>
                        <div class="ucd-item-capabilities">
                            <div class="ucd-client"><label class="ucd-switch"><input type="checkbox" name="ucd_widget_draft" value="#dashboard_quick_press" <?php checked( '#dashboard_quick_press', get_option( 'ucd_widget_draft' ) ); ?> /><span class="ucd-slider round"></span></label>Hide</div>
                        </div>
                    </div>

                    <div class="ucd-dynamic-item">
                        <div class="ucd-item-name"><?php _e( 'Recent Draft', 'ultimate-client-dash' ) ?></div>
                        <div class="ucd-item-capabilities">
                            <div class="ucd-client"><label class="ucd-switch"><input type="checkbox" name="ucd_widget_recent_drafts" value="dashboard_recent_drafts" <?php checked( 'dashboard_recent_drafts', get_option( 'ucd_widget_recent_drafts' ) ); ?> /><span class="ucd-slider round"></span></label>Hide</div>
                        </div>
                    </div>

                    <div class="ucd-dynamic-item">
                        <div class="ucd-item-name"><?php _e( 'Incoming Links', 'ultimate-client-dash' ) ?></div>
                        <div class="ucd-item-capabilities">
                            <div class="ucd-client"><label class="ucd-switch"><input type="checkbox" name="ucd_widget_incoming_links" value="dashboard_incoming_links" <?php checked( 'dashboard_incoming_links', get_option( 'ucd_widget_incoming_links' ) ); ?> /><span class="ucd-slider round"></span></label>Hide</div>
                        </div>
                    </div>

                    <div class="ucd-dynamic-item">
                        <div class="ucd-item-name"><?php _e( 'Recent Comments', 'ultimate-client-dash' ) ?></div>
                        <div class="ucd-item-capabilities">
                            <div class="ucd-client"><label class="ucd-switch"><input type="checkbox" name="ucd_widget_recent_comments" value="dashboard_recent_comments" <?php checked( 'dashboard_recent_comments', get_option( 'ucd_widget_recent_comments' ) ); ?> /><span class="ucd-slider round"></span></label>Hide</div>
                        </div>
                    </div>

                    <div class="ucd-dynamic-item">
                        <div class="ucd-item-name"><?php _e( 'Site Health Status', 'ultimate-client-dash' ) ?></div>
                        <div class="ucd-item-capabilities">
                            <div class="ucd-client"><label class="ucd-switch"><input type="checkbox" name="ucd_widget_site_health" value="ucd_widget_site_health" <?php checked( 'ucd_widget_site_health', get_option( 'ucd_widget_site_health' ) ); ?> /><span class="ucd-slider round"></span></label>Hide</div>
                        </div>
                    </div>

                    <div class="ucd-dynamic-item">
                        <div class="ucd-item-name"><?php _e( 'PHP Update Required', 'ultimate-client-dash' ) ?></div>
                        <div class="ucd-item-capabilities">
                            <div class="ucd-client"><label class="ucd-switch"><input type="checkbox" name="ucd_widget_php_update" value="ucd_widget_php_update" <?php checked( 'ucd_widget_php_update', get_option( 'ucd_widget_php_update' ) ); ?> /><span class="ucd-slider round"></span></label>Hide</div>
                        </div>
                    </div>

                <tr class="ucd-title-holder" style="margin-top: 24px;">
                <th><h2 class="ucd-inner-title"><?php _e( 'Custom Widgets', 'ultimate-client-dash' ) ?></h2></th>
                </tr>

                      <tr>
                      <th><?php _e( 'Widget Format', 'ultimate-client-dash' ) ?></th>
                      <td class="ucd-radio-row">

                      <input type="radio" name="ucd_widget_rows" value="25%"<?php checked( '25%', get_option( 'ucd_widget_rows' ) ); ?> />4 Rows
                      <input class="ucd-radio-spacing" type="radio" name="ucd_widget_rows" value="50%"<?php checked( '50%', get_option( 'ucd_widget_rows' ) ); ?> />2 Rows
                      <input class="ucd-radio-spacing" type="radio" name="ucd_widget_rows" value="100%"<?php checked( '100%', get_option( 'ucd_widget_rows' ) ); ?> />1 Row
                      <p>Customize the widget row format for the WordPress dashboard. (default 4 rows)</p>
                      </td>
                      </tr>

                      <tr class="ucd-pro-version">
                      <th><?php _e( 'Widget Count', 'ultimate-client-dash' ) ?><p>Create up to 4 custom widgets to be displayed on the WordPress dashboard.</p></th>
                      <td><select name="ucd_custom_widget_count">
                      <option value="one" <?php selected(get_option('ucd_custom_widget_count'), "one"); ?>>1</option>
                      <option value="two" <?php selected(get_option('ucd_custom_widget_count'), "two"); ?>>2</option>
                      <option value="three" <?php selected(get_option('ucd_custom_widget_count'), "three"); ?>>3</option>
                      <option value="four" <?php selected(get_option('ucd_custom_widget_count'), "four"); ?>>4</option>
                      </select>
                      <p>
                      Select the number of custom widgets you would like to create. (save changes to update)
                      </p>
                      </td>
                      </tr>

                      <?php
                      $ucd_widget_count = get_option('ucd_custom_widget_count');
                      if ($ucd_widget_count == "one" || "two" || "three" || "four") {
                      ?>

                      <tr class="ucd-title-holder">
                      <th><h2 class="ucd-inner-title"><?php _e( 'Widget - ', 'ultimate-client-dash' ) ?><?php echo esc_attr( get_option('ucd_widget_title') ); ?></h2></th>
                      </tr>

                      <tr>
                      <th><?php _e( 'Widget - ', 'ultimate-client-dash' ) ?><?php echo esc_attr( get_option('ucd_widget_title') ); ?></th>
                      <td><label class="ucd-switch"><input type="checkbox" name="ucd_custom_widget" value="#brand_dash_widget" <?php checked( '#brand_dash_widget', get_option( 'ucd_custom_widget' ) ); ?> /><span class="ucd-slider round"></span></label>Enable
                      <p>
                      Enable to activate widget.
                      </p>
                      </td>
                      </tr>

                      <tr>
                      <th><?php _e( 'Title', 'ultimate-client-dash' ) ?></th>
                      <td><input type="text" placeholder="" name="ucd_widget_title" value="<?php echo esc_attr( get_option('ucd_widget_title') ); ?>" size="70" /></td>
                      </tr>

                      <tr>
                      <th><?php _e( 'Shortcode', 'ultimate-client-dash' ) ?><p>Displays below content.</p></th>
                      <td><input type="text" placeholder="" name="ucd_custom_widget_shortcode" value="<?php echo esc_attr( get_option('ucd_custom_widget_shortcode') ); ?>" size="70" />
                      <p>If you would like to use a shortcode with your custom widget you can add it here.</p>
                      </td>
                      </tr>

                      <tr>
                      <th><?php _e( 'Content', 'ultimate-client-dash' ) ?>
                      <p>Customize the widget by using the content editor to add your own text. HTML markup can be used.</p>
                      </th>

                      <td class="ucd-custom-content">
                      <?php
                      wp_editor( $ucd_widget_body , 'ucd_widget_body', array(
                      'wpautop'       => false,
                      'media_buttons' => true,
                      'textarea_name' => 'ucd_widget_body',
                      'editor_class'  => 'my_custom_class',
                      'textarea_rows' => 15
                      ) );
                      ?>
                      </td>
                      </tr>

                      <?php } ?>
                      <?php
                      $ucd_widget_count = get_option('ucd_custom_widget_count');
                      if ($ucd_widget_count !== "one")  {
                      ?>

                      <tr class="ucd-title-holder">
                      <th><h2 class="ucd-inner-title"><?php _e( 'Widget - ', 'ultimate-client-dash' ) ?><?php echo esc_attr( get_option('ucd_widget_two_title') ); ?></h2></th>
                      </tr>

                      <tr>
                      <th><?php _e( 'Widget - ', 'ultimate-client-dash' ) ?><?php echo esc_attr( get_option('ucd_widget_two_title') ); ?></th>
                      <td><label class="ucd-switch"><input type="checkbox" name="ucd_custom_widget_two" value="#brand_dash_widget" <?php checked( '#brand_dash_widget', get_option( 'ucd_custom_widget_two' ) ); ?> /><span class="ucd-slider round"></span></label>Enable
                      <p>
                      Enable to activate widget.
                      </p>
                      </td>
                      </tr>

                      <tr>
                      <th><?php _e( 'Title', 'ultimate-client-dash' ) ?></th>
                      <td><input type="text" placeholder="" name="ucd_widget_two_title" value="<?php echo esc_attr( get_option('ucd_widget_two_title') ); ?>" size="70" /></td>
                      </tr>

                      <tr>
                      <th><?php _e( 'Shortcode', 'ultimate-client-dash' ) ?><p>Displays below content.</p></th>
                      <td><input type="text" placeholder="" name="ucd_custom_widget_two_shortcode" value="<?php echo esc_attr( get_option('ucd_custom_widget_two_shortcode') ); ?>" size="70" />
                      <p>If you would like to use a shortcode with your custom widget you can add it here.</p>
                      </td>
                      </tr>

                      <tr>
                      <th><?php _e( 'Content', 'ultimate-client-dash' ) ?>
                      <p>Customize the widget by using the content editor to add your own text. HTML markup can be used.</p>
                      </th>

                      <td class="ucd-custom-content">
                      <?php
                      wp_editor( $ucd_widget_two_body , 'ucd_widget_two_body', array(
                      'wpautop'       => false,
                      'media_buttons' => true,
                      'textarea_name' => 'ucd_widget_two_body',
                      'editor_class'  => 'my_custom_class',
                      'textarea_rows' => 15
                      ) );
                      ?>
                      </td>
                      </tr>

                      <?php }?>
                      <?php
                      $ucd_widget_count = get_option('ucd_custom_widget_count');
                      if ($ucd_widget_count == "three" || "four" && $ucd_widget_count !== "two" && $ucd_widget_count !== "one") {
                      ?>

                      <tr class="ucd-title-holder">
                      <th><h2 class="ucd-inner-title"><?php _e( 'Widget - ', 'ultimate-client-dash' ) ?><?php echo esc_attr( get_option('ucd_widget_three_title') ); ?></h2></th>
                      </tr>

                      <tr>
                      <th><?php _e( 'Widget - ', 'ultimate-client-dash' ) ?><?php echo esc_attr( get_option('ucd_widget_two_title') ); ?></th>
                      <td><label class="ucd-switch"><input type="checkbox" name="ucd_custom_widget_three" value="#brand_dash_widget" <?php checked( '#brand_dash_widget', get_option( 'ucd_custom_widget_three' ) ); ?> /><span class="ucd-slider round"></span></label>Enable
                      <p>
                      Enable to activate widget.
                      </p>
                      </td>
                      </tr>

                      <tr>
                      <th><?php _e( 'Title', 'ultimate-client-dash' ) ?></th>
                      <td><input type="text" placeholder="" name="ucd_widget_three_title" value="<?php echo esc_attr( get_option('ucd_widget_three_title') ); ?>" size="70" /></td>
                      </tr>

                      <tr>
                      <th><?php _e( 'Shortcode', 'ultimate-client-dash' ) ?><p>Displays below content.</p></th>
                      <td><input type="text" placeholder="" name="ucd_custom_widget_three_shortcode" value="<?php echo esc_attr( get_option('ucd_custom_widget_three_shortcode') ); ?>" size="70" />
                      <p>If you would like to use a shortcode with your custom widget you can add it here.</p>
                      </td>
                      </tr>

                      <tr>
                      <th><?php _e( 'Content', 'ultimate-client-dash' ) ?>
                      <p>Customize the widget by using the content editor to add your own text. HTML markup can be used.</p>
                      </th>

                      <td class="ucd-custom-content">
                      <?php
                      wp_editor( $ucd_widget_three_body , 'ucd_widget_three_body', array(
                      'wpautop'       => false,
                      'media_buttons' => true,
                      'textarea_name' => 'ucd_widget_three_body',
                      'editor_class'  => 'my_custom_class',
                      'textarea_rows' => 15
                      ) );
                      ?>
                      </td>
                      </tr>

                      <?php } ?>
                      <?php
                      $ucd_widget_count = get_option('ucd_custom_widget_count');
                      if ($ucd_widget_count == "four") {
                      ?>

                      <tr class="ucd-title-holder">
                      <th><h2 class="ucd-inner-title"><?php _e( 'Widget - ', 'ultimate-client-dash' ) ?><?php echo esc_attr( get_option('ucd_widget_four_title') ); ?></h2></th>
                      </tr>

                      <tr>
                      <th><?php _e( 'Widget - ', 'ultimate-client-dash' ) ?><?php echo esc_attr( get_option('ucd_widget_two_title') ); ?></th>
                      <td><label class="ucd-switch"><input type="checkbox" name="ucd_custom_widget_four" value="#brand_dash_widget" <?php checked( '#brand_dash_widget', get_option( 'ucd_custom_widget_four' ) ); ?> /><span class="ucd-slider round"></span></label>Enable
                      <p>
                      Enable to activate widget.
                      </p>
                      </td>
                      </tr>

                      <tr>
                      <th><?php _e( 'Title', 'ultimate-client-dash' ) ?></th>
                      <td><input type="text" placeholder="" name="ucd_widget_four_title" value="<?php echo esc_attr( get_option('ucd_widget_four_title') ); ?>" size="70" /></td>
                      </tr>

                      <tr>
                      <th><?php _e( 'Shortcode', 'ultimate-client-dash' ) ?><p>Displays below content.</p></th>
                      <td><input type="text" placeholder="" name="ucd_custom_widget_four_shortcode" value="<?php echo esc_attr( get_option('ucd_custom_widget_four_shortcode') ); ?>" size="70" />
                      <p>If you would like to use a shortcode with your custom widget you can add it here.</p>
                      </td>
                      </tr>

                      <tr>
                      <th><?php _e( 'Content', 'ultimate-client-dash' ) ?>
                      <p>Customize the widget by using the content editor to add your own text. HTML markup can be used.</p>
                      </th>

                      <td class="ucd-custom-content">
                      <?php
                      wp_editor( $ucd_widget_four_body , 'ucd_widget_four_body', array(
                      'wpautop'       => false,
                      'media_buttons' => true,
                      'textarea_name' => 'ucd_widget_four_body',
                      'editor_class'  => 'my_custom_class',
                      'textarea_rows' => 15
                      ) );
                      ?>
                      </td>
                      </tr>

                      <?php } ?>

                <tr class="ucd-float-option">
                <th class="ucd-save-section">
                <?php submit_button(); ?>
                </th>
                </tr>

                </tbody>
                </table>
            </div>

    </form>
<?php }
