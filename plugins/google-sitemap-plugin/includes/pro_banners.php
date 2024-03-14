<?php
/**
 * Banners on plugin settings page
 *
 * @package Sitemap by BestWebSoft
 * @since 3.0.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Wrapper. Show ads for PRO on plugin settings page
 *
 * @param     string     $func        function to call
 * @param     boolean    $show_cross  when it is 'false' ad will be displayed regardless of if other blocks are closed
 * @return    void
 */
if ( ! function_exists( 'gglstmp_pro_block' ) ) {
	function gglstmp_pro_block( $func, $show_cross = true ) {
		global $gglstmp_plugin_info, $wp_version, $gglstmp_options;
		if ( ! bws_hide_premium_options_check( $gglstmp_options ) ) : ?>
			<div class="bws_pro_version_bloc gglstmp_pro_block <?php echo esc_attr( $func ); ?>" title="<?php esc_html_e( 'This option is available in Pro version of plugin', 'google-sitemap-plugin' ); ?>">
				<div class="bws_pro_version_table_bloc">
					<?php if ( $show_cross ) { ?>
						<button type="submit" name="bws_hide_premium_options" class="notice-dismiss bws_hide_premium_options" title="<?php esc_html_e( 'Close', 'google-sitemap-plugin' ); ?>"></button>
					<?php } ?>
					<div class="bws_table_bg"></div>
					<?php call_user_func( $func ); ?>
				</div>
				<div class="bws_pro_version_tooltip">
					<a class="bws_button" href="https://bestwebsoft.com/products/wordpress/plugins/google-sitemap/?k=28d4cf0b4ab6f56e703f46f60d34d039&pn=83&v=<?php echo esc_attr( $gglstmp_plugin_info['Version'] ); ?>&wp_v=<?php echo esc_attr( $wp_version ); ?>" target="_blank" title="Sitemap Pro"><?php esc_html_e( 'Upgrade to Pro', 'google-sitemap-plugin' ); ?></a>
				</div>
			</div>
			<?php else : ?>
			<p>
				<?php
				esc_html_e( 'This tab contains Pro options only.', 'google-sitemap-plugin' );
				echo ' ' . sprintf(
					esc_html__( '%1$sChange the settings%2$s to view the Pro options.', 'google-sitemap-plugin' ),
					'<a href="admin.php?page=google-sitemap-plugin.php&bws_active_tab=misc">',
					'</a>'
				);
				?>
			</p>
				<?php
			endif;
	}
}


if ( ! function_exists( 'gglstmp_frequency_block' ) ) {
	/**
	 * The content of ad block on the "Settings" tab
	 *
	 * @param     void
	 * @return    void
	 */
	function gglstmp_frequency_block() {
		?>
		<tr>
			<th><?php esc_html_e( 'Title and Meta Description', 'google-sitemap-plugin' ); ?></th>
			<td>
				<input type='checkbox' disabled="disabled" />
				<span class="bws_info"><?php esc_html_e( 'Enable to change title and meta description.', 'google-sitemap-plugin' ); ?></span>
			</td>
		</tr>
		<tr valign="top">
			<th><?php esc_html_e( 'Change Frequency', 'google-sitemap-plugin' ); ?></th>
			<td>
				<select disabled="disabled">
					<option><?php esc_html_e( 'Monthly', 'google-sitemap-plugin' ); ?></option>
				</select>
				<div class="bws_info"><?php esc_html_e( 'This value provides general information to search engines and tell them how frequently the page is likely to change. It may not correlate exactly to how often they crawl the website.', 'google-sitemap-plugin' ); ?>&nbsp;<a href="http://www.sitemaps.org/protocol.html#changefreqdef" target="_blank"><?php esc_html_e( 'Learn More', 'google-sitemap-plugin' ); ?></a></div>
			</td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'External Sitemap Update Frequency', 'google-sitemap-plugin' ); ?></th>
			<td>
				<input disabled="disabled" class="small-text" type="number" value="7">&ensp;<?php esc_html_e( 'day(-s)', 'google-sitemap-plugin' ); ?>
				<div class="bws_info">
					<?php esc_html_e( 'This option sets how often the external index sitemap files should be checked for updates.', 'google-sitemap-plugin' ); ?>
				</div>
			</td>
		</tr>
		<?php
	}
}

if ( ! function_exists( 'gglstmp_extra_block' ) ) {
	/**
	 * The content of ad block on the "Extra settings" tab
	 *
	 * @param     void
	 * @return    void
	 */
	function gglstmp_extra_block() {
		?>
		<img style="max-width: 100%;" src="<?php echo esc_url( plugins_url( 'images/pro_screen_1.png', dirname( __FILE__ ) ) ); ?>" alt="<?php esc_html_e( "Example of site pages' tree", 'google-sitemap-plugin' ); ?>" title="<?php esc_html_e( "Example of site pages' tree", 'google-sitemap-plugin' ); ?>" />
		<?php
	}
}

if ( ! function_exists( 'gglstmp_custom_links_block' ) ) {
	/**
	 * The content of ad block on the "Custom links" tab
	 *
	 * @param     void
	 * @return    void
	 */
	function gglstmp_custom_links_block() {
		$date = date_i18n( get_option( 'date_format' ), 1458086400 );
		?>
		<ul class="subsubsub">
			<li class="all"><a class="current"><?php esc_html_e( 'All', 'google-sitemap-plugin' ); ?>&nbsp;<span class="count">(5)</span></a> |</li>
			<li class="enabled"><a><?php esc_html_e( 'Enabled', 'google-sitemap-plugin' ); ?>&nbsp;<span class="count">(3)</span></a> |</li>
			<li class="disabled"><a><?php esc_html_e( 'Disabled', 'google-sitemap-plugin' ); ?>&nbsp;<span class="count">(2)</span></a></li>
		</ul>
		<p class="search-box">
			<input type="search" disabled="disabled"/>
			<input class="button" value="<?php esc_html_e( 'Search', 'google-sitemap-plugin' ); ?>" type="submit" disabled="disabled" />
		</p>
		<div class="tablenav top">
			<div class="alignleft actions bulkactions">
				<select disabled="disabled">
					<option value="-1"><?php esc_html_e( 'Bulk Actions', 'google-sitemap-plugin' ); ?></option>
				</select>
				<input disabled="disabled" id="doaction" class="button action" value="<?php esc_html_e( 'Apply', 'google-sitemap-plugin' ); ?>" type="submit">
			</div>
			<div class="tablenav-pages one-page"><span class="displaying-num">5 <?php esc_html_e( 'items', 'google-sitemap-plugin' ); ?></span></div>
		</div>
		<table class="wp-list-table widefat fixed striped links" id="gglstmp_table">
			<thead>
				<tr>
					<td id="cb" class="manage-column column-cb check-column"><input id="cb-select-all" type="checkbox" disabled="disabled" /></td>
					<th scope="col" id="url" class="manage-column column-url column-primary sortable asc"><a>URL</a></th>
					<th scope="col" id="is_sitemap" class="manage-column column-is_sitemap sortable desc"><a>Sitemap</a></th>
					<th scope="col" id="priority" class="manage-column column-priority sortable desc"><a><?php esc_html_e( 'Priority', 'google-sitemap-plugin' ); ?></a></th>
					<th scope="col" id="frequency" class="manage-column column-frequency"><?php esc_html_e( 'Change Frequency', 'google-sitemap-plugin' ); ?></th>
					<th scope="col" id="date" class="manage-column column-date sortable desc"><a><?php esc_html_e( 'Last Changed', 'google-sitemap-plugin' ); ?></a></th>
				</tr>
			</thead>
			<tbody data-wp-lists="list:link">
				<tr style="overflow: visible;">
					<th scope="row" class="check-column"></th>
					<td class="url column-url has-row-actions column-primary" data-colname="URL">
						<input type="url" style="width: 100%; box-sizing: border-box;" disabled="disabled" />
						<div class="bws_info">
							<strong><?php esc_html_e( 'Please note', 'google-sitemap-plugin' ); ?>:</strong>
							<?php esc_html_e( 'All URLs listed in the sitemap.xml must use the same protocol (HTTP or HTTPS) and must be located on the same host as the sitemap.xml.', 'google-sitemap-plugin' ); ?>&nbsp;<a href="http://www.sitemaps.org/protocol.html#location" target="_blank"><?php esc_html_e( 'Learn More', 'google-sitemap-plugin' ); ?></a><br/><strong><?php esc_html_e( 'You can also add multiple URLs at once.', 'google-sitemap-plugin' ); ?></strong><?php echo wp_kses_post( bws_add_help_box( '' ) ); ?></div>
						</div>
					</td>
					<td class="is_sitemap column-is_sitemap" data-colname="Sitemap"><input type="checkbox" disabled="disabled" value="1"></td>
					<td class="priority column-priority" data-colname="Priority"><input class="small-text" value="100" type="number" disabled="disabled" />&nbsp;%</td>
					<td class="frequency column-frequency" data-colname="Change Frequency">
						<select disabled="disabled" >
							<option value="always"><?php esc_html_e( 'Always', 'google-sitemap-plugin' ); ?></option>
						</select>
					</td>
					<td class="date column-date" data-colname="Last Changed">
						<input class="button button-primary" value="<?php esc_html_e( 'Save', 'google-sitemap-plugin' ); ?>" type="submit" disabled="disabled" />
					</td>
				</tr>
				<tr>
					<th scope="row" class="check-column"><input type="checkbox" disabled="disabled" /></th>
					<td class="url column-url has-row-actions column-primary" data-colname="URL">
						http://example.com/sitemap.xml
						<div class="row-actions visible">
							<span class="edit"><a><?php esc_html_e( 'Edit', 'google-sitemap-plugin' ); ?></a> | </span>
							<span class="disable"><a><?php esc_html_e( 'Disable', 'google-sitemap-plugin' ); ?></a> | </span>
							<span class="delete"><a><?php esc_html_e( 'Delete', 'google-sitemap-plugin' ); ?></a></span>
						</div>
					</td>
					<td class="is_sitemap column-is_sitemap" data-colname="Sitemap"><?php esc_html_e( 'Yes', 'google-sitemap-plugin' ); ?></td>
					<td class="priority column-priority" data-colname="Priority">100&nbsp;%</td>
					<td class="frequency column-frequency" data-colname="Change Frequency"><?php esc_html_e( 'Monthly', 'google-sitemap-plugin' ); ?></td>
					<td class="date column-date" data-colname="Last Changed"><?php echo esc_html( $date ); ?></td>
				</tr>
				<tr>
					<th scope="row" class="check-column"><input type="checkbox" disabled="disabled" /></th>
					<td class="url column-url has-row-actions column-primary" data-colname="URL">http://example.com/lorem/ipsum/dolor/sit/amet<div class="row-actions">&nbsp;</div></td>
					<td class="is_sitemap column-is_sitemap" data-colname="Sitemap"><?php esc_html_e( 'No', 'google-sitemap-plugin' ); ?></td>
					<td class="priority column-priority" data-colname="Priority">100&nbsp;%</td>
					<td class="frequency column-frequency" data-colname="Change Frequency"><?php esc_html_e( 'Monthly', 'google-sitemap-plugin' ); ?></td>
					<td class="date column-date" data-colname="Last Changed"><?php echo esc_html( $date ); ?></td>
				</tr>
				<tr class="gglstmp_disabled">
					<th scope="row" class="check-column"><input type="checkbox" disabled="disabled" /></th>
					<td class="url column-url has-row-actions column-primary" data-colname="URL">http://example.com/donec-fringilla<div class="row-actions">&nbsp;</div></td>
					<td class="is_sitemap column-is_sitemap" data-colname="Sitemap"><?php esc_html_e( 'No', 'google-sitemap-plugin' ); ?></td>
					<td class="priority column-priority" data-colname="Priority">100&nbsp;%</td>
					<td class="frequency column-frequency" data-colname="Change Frequency"><?php esc_html_e( 'Monthly', 'google-sitemap-plugin' ); ?></td>
					<td class="date column-date" data-colname="Last Changed"><?php echo esc_html( $date ); ?></td>
				</tr>
				<tr class="gglstmp_disabled">
					<th scope="row" class="check-column"><input type="checkbox" disabled="disabled" /></th>
					<td class="url column-url has-row-actions column-primary" data-colname="URL">http://example.com/lorem-ipsum<div class="row-actions">&nbsp;</div></td>
					<td class="is_sitemap column-is_sitemap" data-colname="Sitemap"><?php esc_html_e( 'No', 'google-sitemap-plugin' ); ?></td>
					<td class="priority column-priority" data-colname="Priority">100&nbsp;%</td>
					<td class="frequency column-frequency" data-colname="Change Frequency"><?php esc_html_e( 'Monthly', 'google-sitemap-plugin' ); ?></td>
					<td class="date column-date" data-colname="Last Changed"><?php echo esc_html( $date ); ?></td>
				</tr>
				<tr>
					<th scope="row" class="check-column"><input type="checkbox" disabled="disabled" /></th>
					<td class="url column-url has-row-actions column-primary" data-colname="URL">http://example.com/?s_id=123&amp;p_id=2<div class="row-actions">&nbsp;</div></td>
					<td class="is_sitemap column-is_sitemap" data-colname="Sitemap"><?php esc_html_e( 'No', 'google-sitemap-plugin' ); ?></td>
					<td class="priority column-priority" data-colname="Priority">100&nbsp;%</td>
					<td class="frequency column-frequency" data-colname="Change Frequency"><?php esc_html_e( 'Monthly', 'google-sitemap-plugin' ); ?></td>
					<td class="date column-date" data-colname="Last Changed"><?php echo esc_html( $date ); ?></td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td class="manage-column column-cb check-column"><input id="cb-select-all-2" type="checkbox" disabled="disabled" /></td>
					<th scope="col" class="manage-column column-url column-primary sortable asc"><a>URL</a></th>
					<th scope="col" class="manage-column column-is_sitemap sortable desc"><a>Sitemap</a></th>
					<th scope="col" class="manage-column column-priority sortable desc"><a><?php esc_html_e( 'Priority', 'google-sitemap-plugin' ); ?></a></th>
					<th scope="col" class="manage-column column-frequency"><?php esc_html_e( 'Change Frequency', 'google-sitemap-plugin' ); ?></th>
					<th scope="col" class="manage-column column-date sortable desc"><a><?php esc_html_e( 'Last Changed', 'google-sitemap-plugin' ); ?></a></th>
				</tr>
			</tfoot>
		</table>
		<?php
	}
}
