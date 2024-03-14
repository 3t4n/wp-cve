<?php
// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-header.php' );
?>
	<div class="mmqw-section-left thedotstore-main-table res-cl">
		<h2><?php esc_html_e( 'Thanks For Installing Mass Pages Posts Creator for WordPress', 'mass-pages-posts-creator' ); ?></h2>
		<table class="table-outer">
			<tbody>
			<tr>
				<td class="fr-2">
					<p class="block gettingstarted"><strong><?php esc_html_e( 'Getting Started', 'mass-pages-posts-creator' ); ?> </strong></p>
                    <p class="block textgetting">
						<?php esc_html_e( 'This plugin uses to generate the list of Pages or posts in mass.', 'mass-pages-posts-creator' ); ?>
                    </p>
                    <p class="block textgetting">
						<?php esc_html_e( 'Using this plugin we can create thousands of pages or posts in one click only.', 'mass-pages-posts-creator' ); ?>
                    </p>
                    <p class="block textgetting">
						<?php esc_html_e( 'You can also change the status of the posts or pages like a draft or publish etc.', 'mass-pages-posts-creator' ); ?>
                    </p>
                    <p class="block textgetting">
						<?php esc_html_e( 'You can add Prefix and postfix for each title with text content and excerpt value.', 'mass-pages-posts-creator' ); ?>
                    </p>
                    <p class="block textgetting">
						<?php esc_html_e( 'You can add multiple names of Pages or posts separated by a comma and repeat each in look as much as you want by just entering the number.', 'mass-pages-posts-creator' ); ?>
                        <span class="gettingstarted">
                            <img src="<?php echo esc_url( plugin_dir_url( dirname( __FILE__ ) ) . 'images/Getting_Started_01.png' ); ?>" alt="<?php esc_attr_e( 'Getting_Started_01', 'mass-pages-posts-creator' ); ?>">
                        </span>
                    </p>
				</td>
			</tr>
			</tbody>
		</table>
	</div>

<?php
require_once( plugin_dir_path( __FILE__ ) . 'header/plugin-sidebar.php' );