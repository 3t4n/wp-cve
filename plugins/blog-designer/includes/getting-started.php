<?php
/**
 * Shortcode File for Blog Designer Block
 *
 * @version 1.0
 * @package Blog Designer
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$settings      = get_option( 'wp_blog_designer_settings' );
$template_name = ( isset( $settings['template_name'] ) && '' !== $settings['template_name'] ) ? $settings['template_name'] : 'Classical';
$bd_version    = get_option( 'bd_version' );
?>
<div class="wrap getting-started-wrap">
	<h2 style="display:none"></h2>
	<div class="intro">
		<div class="intro-content">
			<h3><?php esc_html_e( 'Getting Started', 'blog-designer' ); ?></h3>
			<h4><?php esc_html_e( 'You will find everything you need to get started here with Blog Designer plugin.', 'blog-designer' ); ?></h4>
		</div>
		<div class="intro-logo">
			<div class="intro-logo-cover">
				<img src="<?php echo esc_url( BLOGDESIGNER_URL ) . 'admin/images/bdp-logo.png'; ?>" alt="<?php esc_html_e( 'Blog Designer PRO', 'blog-designer' ); ?>" />
				<span class="bdp-version"><?php echo esc_html__( 'Version', 'blog-designer' ) . ' ' . esc_attr( $bd_version ); ?></span>
			</div>
		</div>
	</div>
	<div class="blog-designer-panel">
		<ul class="blog-designer-panel-list">
			<li class="panel-item active">
				<a data-id="bd-help-files" href="javascript:void(0)"  ><?php esc_html_e( 'Read This First', 'blog-designer' ); ?></a>
			</li>
			<li class="panel-item ">
				<a data-id="bd-unintall-data" href="javascript:void(0)"  ><?php esc_html_e( 'Uninstall Data Settings', 'blog-designer' ); ?></a>
			</li>			
		</ul>
		<div class="blog-designer-panel-wrap">
			<div id="bd-unintall-data" class="bd-unintall-data " >
				<form method="post" action="">
				<?php $bd_unintall_data = get_option( 'bd_unintall_data', 0 ); ?>
				<div class="bd-unintall-wrap">
					<input type="checkbox" name="bd_unintall_data" id="bd_unintall_data" value="1" 
					<?php
					if ( isset( $bd_unintall_data ) ) {
						checked( 1, $bd_unintall_data ); }
					?>
					><label for="bd_unintall_data"><?php esc_html_e( "Delete plugin's settings data on deletion of plugin", 'blog-designer' ); ?></label>
				</div>
				<?php wp_nonce_field( 'blog_designer_unintall_data', 'bd_unintall_data_nonce' ); ?>
				<input type="submit" class="save_bd_unintall_data button button-primary" value="<?php esc_html_e( 'Save Changes', 'blog-designer' ); ?>" />
				</form>
			</div>
			<div id="bd-help-files" class="bd-help-files active" style="display: block;">
				<div class="bd-panel-left">
					<div class="bd-notification">
						<h2>
							<?php printf( esc_html__( 'Success, The Blog Designer is now activated!', 'blog-designer' ) ); ?> &#x1F60A;
						</h2>
						<?php
						$create_test    = true;
						$post_link      = get_option( 'blog_page_display', 0 );
						$view_post_link = '';
						if ( '' === $post_link || 0 == $post_link ) {
							$create_test = false;
						} else {
							$view_post_link = get_permalink( $post_link );
						}
						?>
						<h4 class="do-create-test-page" <?php echo ( $create_test ) ? 'style="display: none;"' : ''; ?>>
							<?php esc_html_e( 'Would you like to create one test blog page to check usage of Blog Designer plugin?', 'blog-designer' ); ?> <br/>
							<a class="create-test-page" href="javascript:void(0)"><?php esc_html_e( 'Yes, Please do it', 'blog-designer' ); ?></a> | <a href="<?php echo esc_url( 'https://www.solwininfotech.com/documents/wordpress/blog-designer/#quick_guide' ); ?>" target="_blank"> <?php esc_html_e( 'No, I will configure my self (Give me steps)', 'blog-designer' ); ?> </a>
							<img src="<?php echo esc_url( BLOGDESIGNER_URL ) . 'admin/images/ajax-loader.gif'; ?>" style="display: none;"/>
						</h4>
						<p class="done-create-test-page" <?php echo ( ! $create_test ) ? 'style="display: none;"' : ''; ?>>
							<?php echo esc_html__( 'We have created a', 'blog-designer' ) . ' <b>' . esc_html__( 'Blog Page', 'blog-designer' ) . '</b> ' . esc_html__( 'with', 'blog-designer' ) . ' <span class="template_name">"' . esc_attr( $template_name ) . '"</span> ' . esc_html__( 'blog template.', 'blog-designer' ); ?>
							<a href="<?php echo esc_url( $view_post_link ); ?>" target="_blank"><?php esc_html_e( 'Visit blog page', 'blog-designer' ); ?></a>
						</p>
						<p><?php echo esc_html__( 'To customize the Blog Page design after complete installation,', 'blog-designer' ) . ' <a href="admin.php?page=designer_settings">' . esc_html__( 'Go to Blog Designer Settings', 'blog-designer' ) . '</a>. ' . esc_html__( 'In case of an any doubt,', 'blog-designer' ) . ' <a href="http://solwininfotech.com/documents/wordpress/blog-designer/" target="_blank"> ' . esc_html__( 'Read Documentation', 'blog-designer' ) . ' </a> ' . esc_html__( 'or write to us via', 'blog-designer' ) . ' <a href="http://support.solwininfotech.com/" target="_blank">' . esc_html__( 'support portal', 'blog-designer' ) . '</a> or <a href="https://wordpress.org/support/plugin/blog-designer" target="_blank">' . esc_html__( 'support forum', 'blog-designer' ) . '</a>.'; ?> </p>
					</div>
					<h3>
						<?php esc_html_e( 'Getting Started', 'blog-designer' ); ?> <span>(<?php esc_html_e( 'Must Read', 'blog-designer' ); ?>)</span>
					</h3>
					<p><?php esc_html_e( 'Once you’ve activated your plugin, you’ll be redirected to this Getting Started page (Blog Designer > Getting Started). Here, you can view the required and helpful steps to use plugin.', 'blog-designer' ); ?></p>
					<p><?php esc_html_e( 'We recommed that please read the below sections for more details.', 'blog-designer' ); ?></p>
					<hr id="bd-important-things">
					<h3>
						<?php esc_html_e( 'Important things', 'blog-designer' ); ?> <span>(<?php esc_html_e( 'Required', 'blog-designer' ); ?>)</span> <a href="#bd-important-things">#</a>
						<a class="back-to-top" href="#bd-help-files"><?php esc_html_e( 'Back to Top', 'blog-designer' ); ?></a>
					</h3>
					<p><?php esc_html_e( 'To use Blog Designer, follow the below steps for initial setup - Correct the Reading Settings.', 'blog-designer' ); ?></p>
					<ul>
						<li><?php echo esc_html__( 'To check the reading settings, click', 'blog-designer' ) . ' <b><a href="options-reading.php" target="_blank">' . esc_html__( 'Settings > Reading', 'blog-designer' ) . '</a></b> ' . esc_html__( 'in the WordPress admin menu.', 'blog-designer' ); ?></li>
						<li><?php echo esc_html__( 'If your ', 'blog-designer' ) . '<b>' . esc_html__( 'Posts page', 'blog-designer' ) . ' </b> ' . esc_html__( ' selection selected with the same exact', 'blog-designer' ) . ' <b>' . esc_html__( 'Blog Page', 'blog-designer' ) . '</b> ' . esc_html__( 'selection that same page you seleced under Blog Designer settings then change that selection to default one (', 'blog-designer' ) . ' <b>' . esc_html__( '" — Select — "', 'blog-designer' ) . '</b> ' . esc_html__( ') from the dropdown.', 'blog-designer' ); ?></li>
					</ul>
					<hr id="bd-shortcode-usage">
					<h3>
						<?php esc_html_e( 'How to use Blog Designer Shortcode?', 'blog-designer' ); ?> <span>(<?php esc_html_e( 'Optional', 'blog-designer' ); ?>)</span> <a href="#bd-shortcode-usage">#</a>
						<a class="back-to-top" href="#bd-help-files"><?php esc_html_e( 'Back to Top', 'blog-designer' ); ?></a>
					</h3>
					<p><?php esc_html_e( 'Blog Designer is flexible to be used with any page builders like Visual Composer, Elementor, Beaver Builder, SiteOrigin, Tailor, etc.', 'blog-designer' ); ?></p>
					<ul>
						<li><?php echo esc_html__( 'Use shortcode', 'blog-designer' ) . ' <b>' . esc_html__( '[wp_blog_designer]', 'blog-designer' ) . '</b> ' . esc_html__( 'in any WordPress post or page.', 'blog-designer' ); ?></li>
						<li><?php echo esc_html__( 'Use', 'blog-designer' ) . ' <b> &lt;&quest;php echo do_shortcode("[wp_blog_designer]"); &nbsp;&quest;&gt; </b>' . esc_html__( 'into a template file within your theme files.', 'blog-designer' ); ?></li>
					</ul>
					<hr id="bd-dummy-posts">
					<h3>
						<?php esc_html_e( 'Import Dummy Posts', 'blog-designer' ); ?> <span>(<?php esc_html_e( 'Optional', 'blog-designer' ); ?>)</span> <a href="#bd-dummy-posts">#</a>
						<a class="back-to-top" href="#bd-help-files"><?php esc_html_e( 'Back to Top', 'blog-designer' ); ?></a>
					</h3>
					<p><?php esc_html_e( 'We have craeted a dummy set of posts for you to get started with Blog Designer.', 'blog-designer' ); ?></p>
					<p><?php esc_html_e( 'To import the dummy posts, follow the below process:', 'blog-designer' ); ?></p>
					<ul>
						<li><?php echo esc_html__( 'Go to', 'blog-designer' ) . ' <b>' . esc_html__( 'Tools > Import', 'blog-designer' ) . '</b> ' . esc_html__( 'in WordPress Admin panel.', 'blog-designer' ); ?></li>
						<li><?php echo esc_html__( 'Run ', 'blog-designer' ) . ' <b>' . esc_html__( 'WordPress Importer ', 'blog-designer' ) . '</b> ' . esc_html__( ' at the end of the presentated list.', 'blog-designer' ); ?></li>
						<li><?php echo esc_html__( 'You will be redirected on ', 'blog-designer' ) . ' <b>' . esc_html__( 'Import WordPress ', 'blog-designer' ) . '</b> ' . esc_html__( ' where we need to select actual sample posts XML file.', 'blog-designer' ); ?></li>
						<li><?php echo esc_html__( 'Select', 'blog-designer' ) . ' <b> import-sample_posts.xml </b> ' . esc_html__( 'from', 'blog-designer' ) . ' <b>' . esc_html__( 'blog-designer > includes > dummy-data', 'blog-designer' ) . '</b> ' . esc_html__( 'folder.', 'blog-designer' ); ?></li>
						<li><?php echo esc_html__( 'Click on', 'blog-designer' ) . ' <b>' . esc_html__( 'Upload file and import', 'blog-designer' ) . '</b> ' . esc_html__( 'and with next step please select', 'blog-designer' ) . ' <b>' . esc_html__( 'Download and import file attachments', 'blog-designer' ) . '</b> ' . esc_html__( 'checkbox. Enjoy your cuppa joe with WordPress imports.', 'blog-designer' ); ?></li>
						<li><?php esc_html_e( 'All done! Your website is ready with sample blog posts.', 'blog-designer' ); ?></li>
					</ul>
					<hr id="bd-plugin-support">
					<h3>
						<?php esc_html_e( 'Blog Designer Plugin Support', 'blog-designer' ); ?> <a href="#bd-plugin-support">#</a>
						<a class="back-to-top" href="#bd-help-files"><?php esc_html_e( 'Back to Top', 'blog-designer' ); ?></a>
					</h3>
					<p><?php esc_html_e( 'Blog Designer comes with this handy help file to help you get started with setting up the plugin and showcasing blog page in beautiful ways.', 'blog-designer' ); ?></p>
					<p><?php echo esc_html__( ' Please consider purchasing a', 'blog-designer' ) . ' <a href="' . esc_url( 'https://codecanyon.net/item/blog-designer-pro-for-wordpress/17069678?ref=solwin' ) . '" target="_blank">' . esc_html__( ' PRO version', 'blog-designer' ) . '</a>, ' . esc_html__( 'which grants you access to more blog templates instead of limited templates, useful features like to design category/tag/author pages as well as single post pages, hassle-free regular updates, and a premium support for 6 months or one year based on your purchase!', 'blog-designer' ); ?></p>
				</div>
				<div class="bd-panel-right">
					<div class="panel-aside panel-club">
						<img src="<?php echo esc_url( BLOGDESIGNER_URL ) . 'admin/images/bd-getting-started.jpg'; ?>" alt="<?php esc_attr_e( 'Blog Designer PRO', 'blog-designer' ); ?>"/>
						<div class="panel-club-inside">
							<h4><?php esc_html_e( 'Get an entire collection of beautiful blog templates for one low price.', 'blog-designer' ); ?></h4>
							<p><?php esc_html_e( 'Blog Designer PRO for WordPress grants you access to our collection of pixel-perfect blog templates, support of multiple blog pages — a complete value for price!', 'blog-designer' ); ?></p>
							<a class="button button-primary bdp-button" target="_blank" href="<?php echo esc_url( 'https://codecanyon.net/item/blog-designer-pro-for-wordpress/17069678?ref=solwin' ); ?>"><?php esc_html_e( 'Learn about the Blog Designer PRO', 'blog-designer' ); ?></a>
						</div>
					</div>
					<div class="panel-aside panel-club">
						<img src="<?php echo esc_url( BLOGDESIGNER_URL ) . 'admin/images/ads-slide.jpg'; ?>" alt="<?php esc_attr_e( 'Blog Designer Ads', 'blog-designer' ); ?>"/>
						<div class="panel-club-inside">
							<h4><?php esc_html_e( 'Blog Designer Ads is an add-on WordPress plugin for Blog Designer Pro and Blog Designer plugin', 'blog-designer' ); ?></h4>
							<p><?php esc_html_e( 'Blog Designer Ads supports 3rd party ads such as Google AdSense, also supports custom ads with many customization features such as html ads, image ads, slider etc. There are customization settings such as font color, background, margin, padding, border etc to display your ads beautifully.', 'blog-designer' ); ?></p>
							<a class="button button-primary bdp-button" target="_blank" href="<?php echo esc_url( 'https://www.solwininfotech.com/product/wordpress-plugins/blog-designer-ads/' ); ?>"><?php esc_html_e( 'Learn about the Blog Designer Ads', 'blog-designer' ); ?></a>
						</div>
					</div>					
				</div>
			</div>
		</div>
	</div>
</div>
<?php
