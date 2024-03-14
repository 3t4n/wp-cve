<?php
/**
 * Settings Page
 *
 * @package WP Trending Post Slider and Widget
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) )  {
	exit; // Exit if accessed directly
}

$reg_post_types			= wtpsw_get_post_types();
$tp_support_post_types	= wtpsw_get_option( 'post_types', array() );
?>

<div class="wrap wtpsw-settings">

<h2><?php esc_html_e( 'Trending Post - Settings', 'wtpsw' ); ?></h2>

<?php
if( isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true' ) {
	echo '<div id="message" class="updated notice notice-success is-dismissible">
			<p>'.esc_html__( "Your changes saved successfully.", "wtpsw" ).'</p>
		</div>';
}
?>

<form action="options.php" method="POST" id="wtpsw-settings-form" class="wtpsw-settings-form">

	<?php settings_fields( 'wtpsw_plugin_options' );
		global $wtpsw_options; ?>

	<div id="wtpsw-general-settings" class="post-box-container wtpsw-general-settings">
		<div class="metabox-holder">
			<div class="meta-box-sortables ui-sortable">
				<div id="general" class="postbox">
					<div class="postbox-header">
						<h2 class="hndle">
							<span><?php esc_html_e( 'General Settings', 'wtpsw' ); ?></span>
						</h2>
					</div>
					<div class="inside">
						<table class="form-table wtpsw-general-settings-tbl">
							<tbody>

								<tr>
									<th scope="row">
										<label for="wtpsw-post-within"><?php esc_html_e( 'Post Within', 'wtpsw' ); ?></label>
									</th>
									<td>
										<select id="wtpsw-post-within" class="wtpsw-post-within" name="wtpsw_options[post_range]">
											<option value=""><?php esc_attr_e( 'All Time', 'wtpsw' ); ?></option>
											<option value="daily" <?php selected( $wtpsw_options['post_range'], 'daily' ); ?>><?php esc_html_e( 'Today', 'wtpsw' ); ?></option>
											<option value="last_day" <?php selected( $wtpsw_options['post_range'], 'last_day' ); ?>><?php esc_html_e( 'Last Day', 'wtpsw' ); ?></option>
											<option value="last_week" <?php selected( $wtpsw_options['post_range'], 'last_week' ); ?>><?php esc_html_e( 'Last 7 Days', 'wtpsw' ); ?></option>
											<option value="last_month" <?php selected( $wtpsw_options['post_range'], 'last_month' ); ?>><?php esc_html_e( 'Last Month', 'wtpsw' ); ?></option>
										</select><br/>
										<span class="description"><?php esc_html_e( 'Select time range for post visibility. Note: The post published within this time range will be visible.', 'wtpsw' ); ?></span>
									</td>
								</tr>

								<tr>
									<th>
										<label for="select-post-type"><?php esc_html_e( 'Select Post Types', 'wtpsw' ); ?></label>
									</th>
									<td>
										<?php if( ! empty( $reg_post_types )) {
											foreach ( $reg_post_types as $post_key => $post_label ) { ?>
												<div class="ftpp-post-type-wrap">
													<label>
														<input type="checkbox" id="ftpp-tp-post-<?php echo esc_attr($post_key); ?>" value="<?php echo esc_attr($post_key); ?>" name="wtpsw_options[post_types][]" <?php checked( in_array( $post_key, $tp_support_post_types ), true );  ?> />
														<?php echo esc_attr( $post_label ); ?>( <?php echo esc_attr__('Post Type','wtpsw').' : '.esc_attr( $post_key ); ?> )
													</label>
												</div>
										<?php } } ?>
										<span class="description"><?php esc_html_e( 'Select post type box for trending post. You can enter post type name in shortcode parameter.', 'wtpsw' ); ?></span> <br/>
									</td>
								</tr>

								<tr>
									<td colspan="2" valign="top" scope="row">
										<input type="submit" id="wtpsw-settings-submit" name="wtpsw-settings-submit" class="button button-primary right" value="<?php esc_attr_e( 'Save Changes','wtpsw' ); ?>" />
									</td>
								</tr>
							</tbody>
						</table>

					</div><!-- .inside -->
				</div><!-- #general -->

			</div><!-- .meta-box-sortables ui-sortable -->
		</div><!-- .metabox-holder -->
	</div><!-- #wtpsw-general-settings -->

</form><!-- end .wtpsw-settings-form -->

</div><!-- end .wtpsw-settings -->