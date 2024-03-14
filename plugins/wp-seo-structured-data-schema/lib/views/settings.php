<?php
global $KcSeoWPSchema;
$settings = get_option( $KcSeoWPSchema->options['main_settings'] );
?>
<div class="wrap">
    <h2><?php _e( 'Schema Settings', "wp-seo-structured-data-schema" ); ?></h2>

    <div id="kcseo-settings">
        <div id="kcseo-options">
            <form id="kcseo-main-settings">
                <table width="40%" cellpadding="10" class="form-table">
                    <tr>
                        <th><?php _e( "Business / Org Schema", "wp-seo-structured-data-schema" ) ?></th>
                        <td align="left" scope="row">
							<?php $dd = ! empty( $settings['site_schema'] ) ? $settings['site_schema'] : 'home_page'; ?>
                            <input type="radio" <?php echo( $dd == 'home_page' ? 'checked' : null ); ?>
                                   name="site_schema"
                                   value="home_page" id="site_schema_home"><label for="site_schema_home"><?php _e( "Home page
                                only", "wp-seo-structured-data-schema" ) ?></label><br>
                            <input type="radio" <?php echo( $dd == 'all' ? 'checked' : null ); ?> name="site_schema"
                                   value="all"
                                   id="site_schema_all"><label for="site_schema_all"><?php _e( "Sitewide (Apply General Settings schema
                                sitewide)", "wp-seo-structured-data-schema" ) ?></label><br>
                            <input type="radio" <?php echo( $dd == 'off' ? 'checked' : null ); ?> name="site_schema"
                                   value="off"
                                   id="site_schema_off"><label
                                    for="site_schema_off"><?php _e( "Turn off (Turn off global schema)", "wp-seo-structured-data-schema" ) ?></label>
                        </td>
                    </tr>
                    <tr>
                        <th>
							<?php _e( "Post Type", "wp-seo-structured-data-schema" ) ?>
                            <span class='kcseo-pro-label'><?php _e( "PRO", "wp-seo-structured-data-schema" ) ?></span>
                        </th>
                        <td align="left" scope="row">
							<?php
							$postTypes = $KcSeoWPSchema->get_post_type_list();
							foreach ( $postTypes as $key => $value ) {
								echo "<label for='pt-{$key}'><input disabled id='pt-{$key}' type='checkbox' /> {$value}</label><br>";
							}
							?>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2">
                            <h3><?php _e( "Site Navigation Element Schema", "wp-seo-structured-data-schema" ) ?></h3>
                        </th>
                    </tr>
                    <tr>
                        <th>
							<?php _e( "Publisher Name", "wp-seo-structured-data-schema" ) ?>
                            <span class='kcseo-pro-label'><?php _e( "PRO", "wp-seo-structured-data-schema" ) ?></span>
                        </th>
                        <td align="left" scope="row">
                            <select disabled>
                                <option value=""><?php _e( "Select one menu", "wp-seo-structure-data-schema-pro" ) ?></option>
								<?php
								$menus = get_terms( 'nav_menu' );
								if ( ! empty( $menus ) ) {
									foreach ( $menus as $menu ) {
										$slt = ( ! empty( $settings['site_nav'] ) && $settings['site_nav'] == $menu->term_id ) ? " selected" : null;
										echo "<option value='{$menu->term_id}'{$slt}>{$menu->name}</option>";
									}
								}
								?>
                            </select>
                            <p class="description"><?php _e( "Please deselect the navigation menu if you want to deactivate site navigation
                        schema.", "wp-seo-structured-data-schema" ) ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2"><h3><?php _e( "Publisher Information", "wp-seo-structured-data-schema" ) ?></h3>
                        </th>
                    </tr>
                    <tr>
                        <th>
							<?php _e( "Publisher Name", "wp-seo-structured-data-schema" ) ?>
                            <span class='kcseo-pro-label'><?php _e( "PRO", "wp-seo-structured-data-schema" ) ?></span>
                        </th>
                        <td align="left" scope="row">
                            <input type="text" disabled class="disabled regular-text" value=""/>
                        </td>
                    </tr>
                    <tr>
                        <th>
							<?php _e( "Publisher Logo", "wp-seo-structured-data-schema" ) ?>
                            <span class='kcseo-pro-label'><?php _e( "PRO", "wp-seo-structured-data-schema" ) ?></span>
                        </th>
                        <td align="left" scope="row"></td>
                    </tr>
                    <tr>
                        <th colspan="2">
                            <h3><?php esc_html_e( "Third party plugin Settings", "wp-seo-structured-data-schema" ) ?></h3>
                        </th>
                    </tr>
                    <tr>
                        <th><?php esc_html_e( "YOAST SEO Default Schema JSON-LD", "wp-seo-structured-data-schema" ) ?></th>
                        <td align="left" scope="row">
							<?php $dd = ! empty( $settings['yoast_wpseo_json_ld'] ) ? "checked" : null; ?>
                            <input type="checkbox" <?php echo $dd; ?> name="yoast_wpseo_json_ld" value="1"
                                   id="yoast-wpseo-json-ld"><label
                                    for="yoast-wpseo-json-ld"><?php esc_html_e( "Disable", "wp-seo-structured-data-schema" ) ?></label>
                            <p class="description"><?php esc_html_e( "This will remove all default schema generated by Yoast SEO plugin.", "wp-seo-structured-data-schema" ) ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e( "YOAST SEO sitelinks searchbox", "wp-seo-structured-data-schema" ) ?></th>
                        <td align="left" scope="row">
							<?php $dd = ! empty( $settings['yoast_wpseo_json_ld_search'] ) ? "checked" : null; ?>
                            <input type="checkbox" <?php echo $dd; ?> name="yoast_wpseo_json_ld_search" value="1"
                                   id="yoast-wpseo-json-ld-search"><label
                                    for="yoast-wpseo-json-ld-search"><?php esc_html_e( "Disable", "wp-seo-structured-data-schema" ) ?></label>
                            <p class="description"><?php esc_html_e( "This will remove sitelinks searchbox generated by Yoast SEO plugin.", "wp-seo-structured-data-schema" ) ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e( "Woocommerce default schema", "wp-seo-structured-data-schema" ) ?></th>
                        <td align="left" scope="row">
							<?php $dd = ! empty( $settings['wc_schema_disable'] ) ? "checked" : null; ?>
                            <input type="checkbox" <?php echo $dd; ?> name="wc_schema_disable" value="1"
                                   id="wc-schema-disable"><label
                                    for="wc-schema-disable"><?php esc_html_e( "Disable", "wp-seo-structured-data-schema" ) ?></label>
                            <p class="description"><?php esc_html_e( "This will remove Woocommerce plugin generated schema.", "wp-seo-structured-data-schema" ) ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e( "Easy Digital Download default schema", "wp-seo-structured-data-schema" ) ?></th>
                        <td align="left" scope="row">
							<?php $dd = ! empty( $settings['edd_schema_microdata'] ) ? "checked" : null; ?>
                            <input type="checkbox" <?php echo $dd; ?> name="edd_schema_microdata" value="1"
                                   id="edd-schema-microdata"><label
                                    for="edd-schema-microdata"><?php esc_html_e( "Disable", "wp-seo-structured-data-schema" ) ?></label>
                            <p class="description"><?php esc_html_e( "This will remove Easy Digital Download plugin generated schema.", "wp-seo-structured-data-schema" ) ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="2"><h3><?php _e( "System Settings", "wp-seo-structured-data-schema" ) ?></h3></th>
                    </tr>
                    <tr>
                        <th><?php _e( "Delete all data", "wp-seo-structured-data-schema" ) ?></th>
                        <td align="left" scope="row">
							<?php $dd = ! empty( $settings['delete-data'] ) ? "checked" : null; ?>
                            <input type="checkbox" <?php echo $dd; ?> name="delete-data" value="1"
                                   id="delete-data"><label
                                    for="delete-data"><?php _e( "Enable", "wp-seo-structured-data-schema" ) ?></label>
                            <p class="description"><?php _e( "This will delete all schema created and applied by this plugin when plugin is
                                deleted.", "wp-seo-structured-data-schema" ) ?></p>
                        </td>
                    </tr>
                </table>
                <p class="submit"><input type="submit" name="submit" id="tlpSaveButton" class="button button-primary"
                                         value="<?php _e( 'Save Changes', "wp-seo-structured-data-schema" ); ?>"></p>

				<?php wp_nonce_field( $KcSeoWPSchema->nonceText(), '_kcseo_nonce' ); ?>
            </form>
            <div id="response"></div>
        </div>
		<?php $KcSeoWPSchema->advertisingBlock(); ?>
    </div>

</div>