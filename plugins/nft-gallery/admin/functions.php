<?php
/**
* NFT Gallery - Admin Functions
*
* In this file,
* you will find all functions related to the plugin settings in WP-Admin area.
*
* @author 	Hendra Setiawan
* @version 	1.0.0
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function nftgallery_admin_js($hook) {
	if( 'toplevel_page_nft-gallery' != $hook ) {
		return;
	}
wp_enqueue_script('nftgallery_admin_js_file', plugin_dir_url(__FILE__) . 'js/admin.js');
}
add_action('admin_enqueue_scripts', 'nftgallery_admin_js');

add_action( 'admin_menu', 'nftgallery_admin_menu' );
function nftgallery_admin_menu() {
	add_menu_page(__('NFT Gallery','nft-gallery'), __('NFT Gallery','nft-gallery'), 'manage_options', 'nft-gallery', 'nftgallery_toplevel_page', 'dashicons-grid-view', 16 );
}

function nftgallery_register_settings() {
    register_setting('nftgallery-settings-group', 'nftgallery-api');
    register_setting('nftgallery-settings-group', 'nftgallery-type');
	register_setting('nftgallery-settings-group', 'nftgallery-limit');
	register_setting('nftgallery-settings-group', 'nftgallery-style');
	register_setting('nftgallery-settings-group', 'nftgallery-id');
}
add_action('admin_init', 'nftgallery_register_settings');

function nftgallery_toplevel_page() {
	// Permission check
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

	// Get the default API key
	$nftgalleryAPI = get_option('nftgallery-api');

	// Get the default type
	if( get_option('nftgallery-type') ) {
		$nftgalleryType = get_option('nftgallery-type'); }
	else {
		$nftgalleryType = 'collection'; }

	// Get the default style
	if( get_option('nftgallery-style') ) {
		$nftgalleryStyle = get_option('nftgallery-style'); }
	else {
		$nftgalleryStyle = 'grid'; }		

	// Get the default limit
	if( get_option('nftgallery-limit') ) {
		$nftgalleryLimit = get_option('nftgallery-limit'); }
	else {
		$nftgalleryLimit = 18; }	

	// Get collection slug or wallet address
	$nftgalleryID = get_option('nftgallery-id');
?>
<div class="wpwrap">
	<div class="card" style="border-radius: 10px;">	
	<h1 style="padding-top: 15px; text-align: center;"><?php _e('NFT Gallery','nft-gallery'); ?></h1>
		<div class="form-wrap">
			<form method="post" action="options.php">
				<?php settings_fields('nftgallery-settings-group'); ?>
				<?php do_settings_sections('nftgallery-settings-group'); ?>
				<div class="form-field nftgallery-wrapper">
					<label for="nftgallery-api" style="font-weight: bold;"><?php _e('OpenSea API Key','nft-gallery'); ?></label>
					<input type="text" style="width: 100%;" value="<?php echo esc_html($nftgalleryAPI); ?>" name="nftgallery-api">
					<p>In order to get the API Key, you can refer to this <a href="https://docs.opensea.io/reference/api-keys" target="_blank">OpenSea Docs</a>.</p>

					<label for="nftgallery-type" style="font-weight: bold;"><?php _e('Type','nft-gallery'); ?></label>
					<select name="nftgallery-type" class="ostype">
						<option value="collection" <?php if($nftgalleryType == 'collection') echo 'selected'; ?>>Collection</option>
						<option value="owner" <?php if($nftgalleryType == 'owner') echo 'selected'; ?>>Owner</option>
					</select>
					<p>Choose which type of NFTs that you would like to show, either from a collection or a single wallet address.</p>

					<label for="nftgallery-id" style="font-weight: bold;" class="osid"><?php _e('Wallet Address','nft-gallery'); ?></label>
					<input type="text" name="nftgallery-id" style="width: 100%;" value="<?php echo esc_html($nftgalleryID); ?>" required="">
					<p class="osidcaption">Please specify your wallet address.</p>

					<label for="nftgallery-style" style="font-weight: bold;"><?php _e('Gallery Style','nft-gallery'); ?></label>
					<select name="nftgallery-style" class="style">
						<option value="grid" <?php if($nftgalleryStyle == 'grid') echo 'selected'; ?>>Grid</option>
						<option value="photography" <?php if($nftgalleryStyle == 'photography') echo 'selected'; ?>>Photography</option>
					</select>
					<p>Choose which type of layout that you want to use.</p>					

					<label for="nftgallery-limit" style="font-weight: bold;"><?php _e('Limit','nft-gallery'); ?></label>
					<input type="number" name="nftgallery-limit" style="width: 60px;" value="<?php echo esc_html($nftgalleryLimit); ?>">
					<p>Specify the number of NFTs to show.</p>				
				</div>

				<hr />
				<h3>Shortcode</h3>
				<?php
				if($nftgalleryAPI){
					echo '<p>Copy and paste this shortcode directly into any post or page.</p>';
					echo '<textarea style="width: 100%;" readonly="readonly">[nftgallery]</textarea>';
				} else {
					echo '<p style="color: red;">Problem detected! Please set your OpenSea API.</p>';
				}
				?>
				<?php submit_button(__( 'Save Settings', 'nft-gallery' ), 'primary'); ?>
			</form>		
			<p style="text-align: center; border-top: 1px solid #eee; padding-top: 15px;">NFT Gallery. Made with <span style="color: #f0b125;" class="dashicons dashicons-heart"></span> by Hendra from <a href="https://skybee.io/" target="_blank">Skybee.io</a></p>
		</div>
	</div>
</div>
<?php
}