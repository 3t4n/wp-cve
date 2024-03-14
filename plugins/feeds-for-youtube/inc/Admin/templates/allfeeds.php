<?php
/**
 * Custom Feeds for Twitter Feed Locator Summary Template
 * Creates the HTML for the feed locator summary
 *
 * @version 1.14 Custom Feeds for Twitter Pro by Smash Balloon
 *
 */
// Don't load directly
use SmashBalloon\YouTubeFeed\Feed_Locator;

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}
$locator_summary = Feed_Locator::summary();
$database_settings = sby_get_database_settings();
?>
<div class="sby-feed-locator-summary-wrap">
    <h3><?php esc_html_e( 'Feed Finder Summary', 'feeds-for-youtube' ); ?></h3>
    <p><?php esc_html_e( 'The table below shows a record of all feeds found on your site. A feed may not show up here immediately after being created.', 'feeds-for-youtube' ); ?></p>
	<?php
	if ( ! empty( $locator_summary ) ) : ?>

		<?php foreach ( $locator_summary as $locator_section ) :
			if ( ! empty( $locator_section['results'] ) ) : ?>
                <div class="sby-single-location">
                    <h4><?php echo esc_html( $locator_section['label'] ); ?></h4>
                    <table class="widefat striped">
                        <thead>
                        <tr>
                            <th><?php esc_html_e( 'Type', 'feeds-for-youtube' ); ?></th>
                            <th><?php esc_html_e( 'Sources', 'feeds-for-youtube' ); ?></th>
                            <th><?php esc_html_e( 'Shortcode', 'feeds-for-youtube' ); ?></th>
                            <th><?php esc_html_e( 'Location', 'feeds-for-youtube' ); ?></th>
                        </tr>
                        </thead>
                        <tbody>

						<?php foreach ( $locator_section['results'] as $result ) :
							$shortcode_atts = json_decode( $result['shortcode_atts'], true );
							$shortcode_atts = is_array( $shortcode_atts ) ? $shortcode_atts : array();

							if ( class_exists( 'SBY_Settings_Pro' ) ) {
								$settings_obj = new \SmashBalloon\YouTubeFeed\Pro\SBY_Settings_Pro( $shortcode_atts, $database_settings );
							} else {
								$settings_obj = new \SmashBalloon\YouTubeFeed\SBY_Settings( $shortcode_atts, $database_settings );
							}
							$settings = $settings_obj->get_settings();
							$settings_obj->set_feed_type_and_terms();
							$display_terms = $settings_obj->feed_type_and_terms_display();
							$comma_separated = implode(', ', $display_terms );
							//$comma_separated = implode(', ', array() );
							$display = $comma_separated;
							if ( strlen( $comma_separated ) > 31 ) {
								$display = '<span class="sby-condensed-wrap">' . substr( $comma_separated, 0, 30 ) . '<a class="sby-locator-more" href="JavaScript:void(0);">...</a></span>';
								$comma_separated = '<span class="sby-full-wrap">' . esc_html( $comma_separated ) . '</span>';
							} else {
								$comma_separated = '';
							}
							$type = isset( $settings['type'] ) ? $settings['type'] : 'user';
							$full_shortcode_string = '[youtube-feed';
							foreach ( $shortcode_atts as $key => $value ) {
								$full_shortcode_string .= ' ' . esc_html( $key ) . '="' . esc_html( $value ) . '"';
							}
							$full_shortcode_string .= ']';
							?>
                            <tr>
                                <td><?php echo esc_html( $type ); ?></td>
                                <td><?php echo $display . $comma_separated; ?></td>
                                <td>
                                    <span class="sby-condensed-wrap"><a class="sby-locator-more" href="JavaScript:void(0);"><?php esc_html_e( 'Show', 'feeds-for-youtube' ); ?></a></span>
                                    <span class="sby-full-wrap"><?php echo $full_shortcode_string; ?></span>
                                </td>
                                <td><a href="<?php echo esc_url( get_the_permalink( $result['post_id'] ) ); ?>" target="_blank" rel="noopener"><?php echo esc_html( get_the_title( $result['post_id'] ) ); ?></a></td>
                            </tr>
						<?php endforeach; ?>


                        </tbody>
                    </table>
                </div>

			<?php endif;
		endforeach;
	else: ?>
        <p><?php esc_html_e( 'Locations of your feeds are currently being detected. You\'ll see more information posted here soon!', 'feeds-for-youtube' ); ?></p>
	<?php endif; ?>
</div>