<?php
/**
 * Admin View: Display HTML Sitemap - Settings
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Initializing Sitemap Object
$htmlSitemap = new DisplayHtmlSitemap();

// Retrive all options
$dhswp_sortorder = get_option('dhswp_sortorder');
$dhswp_exclude   = get_option('dhswp_exclude');

?>

<div class="wrap">
	
	<h2><?php echo __( 'HTML Sitemap Options', 'dhswp' ); ?></h2>

	<?php if( isset( $_GET['message'] ) ) { ?>
	<div id="message" class="updated notice is-dismissible">
		
		<?php if( $_GET['message'] == 1 ) { ?>
			<p>Settings have been updated</p> 
		<?php } ?>

		<?php if( $_GET['message'] == 2 ) { ?>
			<p>You are authorized to access this page. Please contact your administrator.</p> 
		<?php } ?>

	</div>
	<?php } ?>
	
	<form method="post" action="">

		<?php settings_fields( 'dhswp' ); ?>

		<?php wp_nonce_field( 'save_sitemap_option', 'save_sitemap_option' ); ?>

		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-2" style="min-width:650px;">
				<div id="post-body-content">
					<div id="dhswp-option-wrapper">
						<div id="dhswp-option-title">
							<div class="dhswp-title dhswp-title1">
								<?php echo __( 'Drag to Sort', 'dhswp' ); ?></div>
							<div class="dhswp-title dhswp-title2">
								<?php echo __( 'Show', 'dhswp' ); ?></div>
							<div class="dhswp-title dhswp-title3">
								<?php echo __( 'Post Type Name', 'dhswp' ); ?></div>
							<div class="dhswp-title dhswp-title4">
								<?php echo __( 'Post Type Slug', 'dhswp' ); ?></div>
						</div><!-- #dhswp-option-title -->
			
						<ul id="dhswp-sortable">
							
							<?php $posts_list = $htmlSitemap->dhswp_posts_list(); ?>
							<?php echo $htmlSitemap->dhswp_sortable_list( $posts_list ); ?>
							
							<?php 
							// creating sort order adding new post type and removing removed post type
							$dhswp_sortorder = implode( ',', array_keys( $posts_list ) );
							?>
							
						</ul><!-- #sortable -->
				
					</div><!-- #dhswp-option-wrapper -->

					<div class="clr"></div>
				
					<div class="postbox" >
						<h3 class='hndle'><?php echo __( 'Exclude Posts', 'dhswp' ); ?></h3>
						<div class="inside">
							<div class="submitbox">
								<?php echo __( 'Provide Post IDs', 'dhswp' ); ?>:
								<input type="text" name="dhswp-exclude" id="dhswp-exclude" style="width:400px;" value="<?php echo $dhswp_exclude; ?>" />
								<p class="description"><?php echo __( 'Please insert comma separated post IDs which you want to hide on Sitemap page', 'dhswp' ); ?> <br> <?php echo __( 'Example', 'dhswp' ); ?>: <code>8,56,98,106</code></p>
								<div class="clear"></div>
							</div><!-- .submitbox -->
						</div><!-- .inside -->
					</div><!-- #postbox-container-1 .postbox-container -->
				
					
					<p class="submit">
						<input type="hidden" name="dhswp-sortorder" id="dhswp-sortorder" value="<?php echo $dhswp_sortorder; ?>" />
						<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" name="dhswp-update" />
					</p>
			
				</div><!-- #post-body-content -->
		
				<div id="postbox-container-1" class="postbox-container">
					<div class="postbox">
						<h3 class='hndle'><?php echo __( 'Quick Guide', 'dhswp' ) ?></h3>
						<div class="inside">
							<div class="submitbox">
								<?php echo __( 'Steps', 'dhswp' ); ?>:
								<ol>
									<li><?php echo __( 'Select Post Types from left, which you want to show on Sitemap Page. Than click "Save Changes" button.', 'dhswp' ); ?></li>
									<li><?php echo __( 'Create a new page (for sitemap) and insert <code>[display-html-sitemap]</code> in content area.', 'dhswp' ); ?></li>
									<li><?php echo __( 'Done, your sitemap is ready to go :)', 'dhswp' ); ?></li>
								</ol>
								
								<div class="clear"></div>
							
							</div><!-- .submitbox -->
						</div><!-- .inside -->
					</div><!-- .postbox -->
					
				</div><!-- .postbox-container #postbox-container-1 -->
				<div class="clear"></div>

			</div><!-- #post-body -->
			<div class="clear"></div>
		
		</div><!-- #poststuff -->
	</form>
</div>