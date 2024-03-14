<?php
/**
 * [Short description]
 *
 * @package    DEVRY\FIP
 * @copyright  Copyright (c) 2024, Developry Ltd.
 * @license    https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @since      1.3
 */

namespace DEVRY\FIP;

! defined( ABSPATH ) || exit; // Exit if accessed directly.

$fip_admin = new FIP_Admin();

$has_user_cap = $fip_admin->check_user_cap();

?>
<div class="fip-admin">
	<div class="fip-loading-bar"></div>
	<div id="fip-output" class="notice is-dismissible fip-output"></div>
	<?php settings_errors( 'fip_settings_errors' ); ?>

	<div class="fip-pro">
		<h4>
			<?php echo esc_html__( 'Get the PRO version today!', 'featured-image-plus' ); ?>
		</h4>

		<p>
			<?php echo esc_html__( 'With the PRO version you will get a lot more features with better performance, support as well as integration with OpenAI and Unsplash APIs.', 'featured-image-plus' ); ?>
		</p>

		<table>
			<tr>
				<th><?php echo esc_html__( 'Feature', 'featured-image-plus' ); ?></th>
				<th><?php echo esc_html__( 'Free', 'featured-image-plus' ); ?></th>
				<th><?php echo esc_html__( 'PRO', 'featured-image-plus' ); ?></th>
			</tr>
			<tr>
				<td><?php echo esc_html__( 'AI Image Creator', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'no', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'yes', 'featured-image-plus' ); ?></td>
			</tr>
			<tr>
				<td><?php echo esc_html__( 'Auto-generate images from Unsplash or OpenAI', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'no', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'yes', 'featured-image-plus' ); ?></td>
			</tr>
			<tr>
				<td><?php echo esc_html__( 'Select support from all registered CPTs', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'Posts and Pages', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'All CPTs', 'featured-image-plus' ); ?></td>
			</tr>
			<tr>
				<td><?php echo esc_html__( 'Manage featured image visibility, position, size, etc.', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'no', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'yes', 'featured-image-plus' ); ?></td>
			</tr>
			<tr>
				<td><?php echo esc_html__( 'Add theme support for featured images', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'no', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'yes', 'featured-image-plus' ); ?></td>
			</tr>
			<tr>
				<td><?php echo esc_html__( 'Add support to all public taxonomies', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'no', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'yes', 'featured-image-plus' ); ?></td>
			</tr>
			<tr>
				<td><?php echo esc_html__( 'Auto-remove and attach featured images from post content; works with Classic, Block, and Elementor editors	', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'no', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'yes', 'featured-image-plus' ); ?></td>
			</tr>
			<tr>
				<td><?php echo esc_html__( 'WooCommerce and other 3rd-partry plugin support', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'no', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'yes', 'featured-image-plus' ); ?></td>
			</tr>
			<tr>
				<td><?php echo esc_html__( 'Unsplash API itegration	', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'no', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'yes', 'featured-image-plus' ); ?></td>
			</tr>
			<tr>
				<td><?php echo esc_html__( 'OpenAI/DALL-E 2 API integration', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'no', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'yes', 'featured-image-plus' ); ?></td>
			</tr>
			<tr>
				<td><?php echo esc_html__( 'Sort and filter by featured images	', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'yes', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'yes', 'featured-image-plus' ); ?></td>
			</tr>
			<tr>
				<td><?php echo esc_html__( 'Priority email support', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'no', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'yes', 'featured-image-plus' ); ?></td>
			</tr>
			<tr>
				<td><?php echo esc_html__( 'Regular plugin updates', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'delayed', 'featured-image-plus' ); ?></td>
				<td><?php echo esc_html__( 'first release', 'featured-image-plus' ); ?></td>
			</tr>
		</table>

		<p class="button-group">
			<a
				class="button button-primary button-pro"
				href="https://bit.ly/43el4Il"
				target="_blank"
			>
				<?php echo esc_html__( 'GET PRO VERSION', 'featured-image-plus' ); ?>
			</a>
			<a
				class="button button-primary button-watch-video"
				href="https://www.youtube.com/watch?v=r3czQg7Xqec "
				target="_blank"
			>
				<?php echo esc_html__( 'Watch Video', 'featured-image-plus' ); ?>
			</a>
		</p>
	</div>

	<h2>
		<?php echo esc_html__( 'Featured Image Plus', 'featured-image-plus' ); ?>
	</h2>

	<p>
		<?php
		printf(
			wp_kses(
				__( 'Optimize your WordPress workflow by rapidly managing featured images on Posts and Pages with our enhancements to the bulk and quick edit actions.' ),
				json_decode( FIP_PLUGIN_ALLOWED_HTML_ARR )
			),
		);
		?>
	</p>

	<hr />

	<form method="post" action="<?php echo esc_url( admin_url( 'options.php' ) ); ?>">
		<?php wp_nonce_field( 'fip_security', 'fip_nonce' ); ?>
		<?php
			settings_fields( FIP_SETTINGS_SLUG );
			do_settings_sections( FIP_SETTINGS_SLUG );
		?>
		<div class="submit button-group">
			<p class="submit button-group">
				<button
					type="submit"
					class="button button-primary"
					id="submit-button"
					name="submit-button"
				>
					<?php echo esc_html__( 'Save', 'featured-image-plus' ); ?>
				</button>
				<button
					type="button"
					class="button"
					id="fip-reset-button"
					name="fip-reset-button"
				>
					<?php echo esc_html__( 'Reset', 'featured-image-plus' ); ?>
				</button>
			</p>
		</div>
	</form>

	<br clear="all" />

	<hr />

	<div class="fip-support-credits">
		<p>
			<?php
			printf(
				wp_kses(
					/* translators: %1$s is replaced with "Link to WP.org support forums" */
					__( 'If something is not clear, please open a ticket on the official plugin %1$s. All tickets should be addressed within a couple of working days.', 'featured-image-plus' ),
					json_decode( FIP_PLUGIN_ALLOWED_HTML_ARR )
				),
				'<a href="' . esc_url( FIP_PLUGIN_WPORG_RATE ) . '" target="_blank">' . esc_html__( 'Support Forum', 'featured-image-plus' ) . '</a>'
			);
			?>
		</p>
		<p>
			<strong><?php echo esc_html__( 'Please rate us', 'featured-image-plus' ); ?></strong>
			<a href="<?php echo esc_url( FIP_PLUGIN_WPORG_RATE ); ?>" target="_blank">
				<img src="<?php echo esc_url( FIP_PLUGIN_DIR_URL ); ?>assets/dist/img/rate.png" alt="Rate us @ WordPress.org" />
			</a>
		</p>
		<p>
			<strong><?php echo esc_html__( 'Having issues?', 'featured-image-plus' ); ?></strong> 
			<a href="<?php echo esc_url( FIP_PLUGIN_WPORG_RATE ); ?>" target="_blank">
				<?php echo esc_html__( 'Create a Support Ticket', 'featured-image-plus' ); ?>
			</a>
		</p>
		<p>
			<strong><?php echo esc_html__( 'Developed by', 'featured-image-plus' ); ?></strong>
			<a href="<?php echo esc_url( 'https://' . FIP_PLUGIN_DOMAIN ); ?>" target="_blank">
				<?php echo esc_html__( 'Krasen Slavov @ Developry', 'featured-image-plus' ); ?>
			</a>
		</p>
	</div>

	<hr />

	<p>
		<small>
			<?php
			printf(
				wp_kses(
					/* translators: %1$s is replaced with "Link to Patreon account for support" */
					__( '* For the price of a cup of coffee per month, you can %1$s in continuing to develop and maintain all of my free WordPress plugins, every little bit helps and is greatly appreciated!', 'featured-image-plus' ),
					json_decode( FIP_PLUGIN_ALLOWED_HTML_ARR )
				),
				'<a href="https://patreon.com/krasenslavov" target="_blank">' . __( 'help and support me on Patreon', 'featured-image-plus' ) . '</a>'
			);
			?>
		</small>
	</p>
</div>
