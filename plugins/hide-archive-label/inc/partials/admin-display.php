<?php
defined( 'ABSPATH' ) or die( "You can't access this file directly." );

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       www.catchplugins.com
 * @since      1.0.0
 *
 * @package    Hide_Archive_Label
 * @subpackage Hide_Archive_Label/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
	<h1 class="wp-heading-inline"><?php esc_html_e( 'Hide/Remove Archive Label', 'hide-archive-label' ); ?></h1>
	<div id="plugin-description">
		<p><?php esc_html_e( 'Hide Archive Label is a free WordPress plugin to quickly hide or remove archive page title prefixes on your site such as “Category:”, “Tags:”, “Author:”, and more. A clean archive page titles in just a few seconds!', 'hide-archive-label' ); ?></p>
	</div>
	<div class="catchp-content-wrapper">
		<div class="catchp_widget_settings">
			<form id="sticky-main" method="post" action="options.php">
				<h2 class="nav-tab-wrapper">
					<a class="nav-tab nav-tab-active" id="dashboard-tab" href="#dashboard"><?php esc_html_e( 'Dashboard', 'hide-archive-label' ); ?></a>
					<a class="nav-tab" id="features-tab" href="#features"><?php esc_html_e( 'Features', 'hide-archive-label' ); ?></a>

				</h2>
				<div id="dashboard" class="wpcatchtab nosave active">
					<?php require_once HAL_PATH . 'inc/partials/dashboard-display.php'; ?>

				</div><!---dashboard---->

				<div id="features" class="wpcatchtab save">
					<div class="content-wrapper col-3">
						<div class="header">
							<h3><?php esc_html_e( 'Features', 'hide-archive-label' ); ?></h3>
						</div><!-- .header -->
						<div class="content">
							<ul class="catchp-lists">
								<li>
									<strong><?php esc_html_e( 'Hide Archive Label Accessibly', 'hide-archive-label' ); ?></strong>
									<p><?php esc_html_e( 'You can hide the label for any archive page accessibly. It means your archive texts will not be completely removed, but instead, it will be hidden from visibility. Accessible hiding causes wrapping the archive title label in a screen-reader-text CSS class. And wrapping the text in such a CSS class element means it will be hidden but still will be accessible. ', 'hide-archive-label' ); ?></p>
								</li>

								<li>
									<strong><?php esc_html_e( 'Remove Archive Label Completely', 'hide-archive-label' ); ?></strong>
									<p><?php esc_html_e( 'Instead of partially hiding it, you can completely remove the archive label from your website if you choose not to display them even on the screen reader. ', 'hide-archive-label' ); ?></p>
								</li>
								<li>
									<strong><?php esc_html_e( 'Hide Labels', 'hide-archive-label' ); ?></strong>
									<p><?php esc_html_e( 'You can choose which ones to hide from a bunch of archive page title labels.', 'hide-archive-label' ); ?></p>
								</li>
								<li>
									<strong><?php esc_html_e( 'Lightweight', 'hide-archive-label' ); ?></strong>
									<p><?php esc_html_e( 'Hide Archive Label is an expedient WordPress plugin to hide or remove archive titles that is extremely lightweight. It means you will not have to worry about your website getting slower because of the plugin.', 'hide-archive-label' ); ?></p>
								</li>
								<li>
									<strong><?php esc_html_e( 'Super Responsive', 'hide-archive-label' ); ?></strong>
									<p><?php esc_html_e( 'Our new WordPress plugin for hiding archive labels comes with a responsive design, therefore, there is no need to strain about the plugin breaking your website. ', 'hide-archive-label' ); ?></p>
								</li>
								<li>
									<strong><?php esc_html_e( 'Compatible with all WordPress Themes', 'hide-archive-label' ); ?></strong>
									<p><?php esc_html_e( 'Our new Hide Archive Label plugin has been crafted in a way that supports all the WordPress themes. The plugin functions smoothly on any WordPress theme.', 'hide-archive-label' ); ?></p>
								</li>
								<li>
									<strong><?php esc_html_e( 'Gutenberg Ready', 'hide-archive-label' ); ?></strong>
									<p><?php esc_html_e( 'Gutenberg Compatibility is one of the major concerns nowadays for every plugin developer. The plugin is fully compatible with the block editor. So, if you’re someone who’s using the new block editor, Hide Archive Label shall work just fine for you without any complications.', 'hide-archive-label' ); ?></p>
								</li>
								<li>
									<strong><?php esc_html_e( 'Incredible Support', 'hide-archive-label' ); ?></strong>
									<p><?php esc_html_e( 'Hide Archive Label comes with Incredible Support. Our plugin documentation answers most questions about using the plugin. If you’re still having difficulties, you can post it in our Support Forum.', 'hide-archive-label' ); ?></p>
								</li>
							</ul>

						</div><!-- .content -->
					</div><!-- content-wrapper -->
				</div> <!-- Featured -->
			</form><!-- sticky-main -->
		</div><!-- .catchp_widget_settings -->
		<?php require_once HAL_PATH . 'inc/partials/sidebar.php'; ?>
	</div><!---catch-content-wrapper---->
<?php require_once HAL_PATH . 'inc/partials/footer.php'; ?>
</div><!-- .wrap -->
