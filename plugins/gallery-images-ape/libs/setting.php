<?php 
/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

if ( ! defined( 'WPINC' ) )  die;


function wpApeGallerySetting() {

$active_tab = isset( $_GET[ 'tab' ] ) ? sanitize_text_field( $_GET[ 'tab' ] ): 'main_option';

?>
<div class="wrap">
     
    <h2><?php 
    	_e('Gallery Images Ape', 'gallery-images-ape');
    	echo ' ';
    	_e('Options', 'gallery-images-ape');
   	?></h2>
    <?php settings_errors(); ?>

	<h2 class="nav-tab-wrapper">
	    <a href="edit.php?post_type=wpape_gallery_type&page=wpape-gallery-settings&tab=main_option" class="nav-tab <?php echo $active_tab == 'main_option' ? 'nav-tab-active' : ''; ?>">
	    	<?php _e('Main Options', 'gallery-images-ape'); ?>
	    </a>
	    <a href="edit.php?post_type=wpape_gallery_type&page=wpape-gallery-settings&tab=clone_options" class="nav-tab <?php echo $active_tab == 'clone_options' ? 'nav-tab-active' : ''; ?>">
	    	<?php _e('Clone Options', 'gallery-images-ape'); ?>
	    </a>
	    <a href="edit.php?post_type=wpape_gallery_type&page=wpape-gallery-settings&tab=source_options" class="nav-tab <?php echo $active_tab == 'source_options' ? 'nav-tab-active' : ''; ?>">
	    	<?php echo _e('Default gallery options', 'gallery-images-ape'); ?>
	    </a>
	</h2>

	<form method="post" action="options.php?tab=<?php echo $active_tab; ?>">
    <?php
         
        if( $active_tab == 'main_option' ) {
            settings_fields( 'wpape_gallery_settings' );
            do_settings_sections( 'wpape_gallery_settings' );
            wpApeSettingMainOptions();
        } elseif( $active_tab == 'clone_options' ) {
            settings_fields( 'wpape_gallery_settings_clone' );
            do_settings_sections( 'wpape_gallery_settings_clone' );
            wpApeSettingCloneOptions();
        } else {
            settings_fields( 'wpape_gallery_settings_source' );
            do_settings_sections( 'wpape_gallery_settings_source' );
            wpApeSettingSourceOptions();
        } 
        submit_button();
    ?>
	</form>
	<div class="card">
		<p><?php echo 'Copyright &copy; 2018 <a href="https://wpape.net" target="_blank">Ape Team</a> '.__('All Rights Reserved', 'gallery-images-ape'); ?></p>
	</div>
</div>
<?php 
} 

function wpApeSettingMainOptions(){ 
?>
	<table class="form-table">
		<tr>
			<th scope="row"><?php _e('JS libs version', 'gallery-images-ape'); ?></th>
			<td>
				<fieldset>
					<label title='<?php _e('Latest version', 'gallery-images-ape'); ?>'>
						<input type='radio' name='<?php echo WPAPE_GALLERY_NAMESPACE.'jqueryVersion'; ?>' value='latest' <?php if( get_option(WPAPE_GALLERY_NAMESPACE.'jqueryVersion', 'latest')=='latest' ) echo " checked='checked'";?> /> <?php _e('Latest version', 'gallery-images-ape'); ?>
					</label>
					<br />
					<label title='<?php _e('No conflict mode to avoid JS conflict', 'gallery-images-ape'); ?>'>
						<input type='radio' name='<?php echo WPAPE_GALLERY_NAMESPACE.'jqueryVersion'; ?>' value='alt' <?php if( get_option(WPAPE_GALLERY_NAMESPACE.'jqueryVersion')=='alt' ) echo " checked='checked'";?>  /> <?php _e('No conflict mode to avoid JS conflict', 'gallery-images-ape'); ?>
					</label>
					<br />
					<label title='<?php _e('Hard code, without WP API', 'gallery-images-ape'); ?>'>
						<input type='radio' name='<?php echo WPAPE_GALLERY_NAMESPACE.'jqueryVersion'; ?>' value='include' <?php if( get_option(WPAPE_GALLERY_NAMESPACE.'jqueryVersion')=='include' ) echo " checked='checked'";?>  /> <?php _e('Hard code, without WP API', 'gallery-images-ape'); ?>
					</label>
					<p class="descroption">
						You need to install additional plugin with customized jQuery library. Please download this plugin <a target="_blank" href="https://wpape.net">here</a>
					</p>
				</fieldset>
			</td>
		</tr>
		
		<tr>
			<th scope="row"><?php _e('Start Delay', 'gallery-images-ape'); ?></th>
			<td>
				<input name="<?php echo WPAPE_GALLERY_NAMESPACE.'delay'; ?>" id="<?php echo WPAPE_GALLERY_NAMESPACE.'delay'; ?>" value="<?php echo (int) get_option(WPAPE_GALLERY_NAMESPACE.'delay', '1000'); ?>" class="small-text" type="text"> ms.
			</td>
		</tr>
	</table>
<?php 
}

function wpApeSettingCloneOptions(){ 
?>
	<table class="form-table">
		<tr>
			<th scope="row"><?php _e('Title Prefix', 'gallery-images-ape'); ?></th>
			<td>
				<input name="<?php echo WPAPE_GALLERY_NAMESPACE.'copyPrefix'; ?>" id="<?php echo WPAPE_GALLERY_NAMESPACE.'copyPrefix'; ?>" value="<?php echo get_option(WPAPE_GALLERY_NAMESPACE.'copyPrefix', ''); ?>" class="regular-text code" type="text">
			</td>
		</tr>
		<tr>
			<th scope="row"><?php _e('Title Suffix', 'gallery-images-ape'); ?></th>
			<td>
				<input name="<?php echo WPAPE_GALLERY_NAMESPACE.'copySuffix'; ?>" id="<?php echo WPAPE_GALLERY_NAMESPACE.'copySuffix'; ?>" value="<?php echo get_option(WPAPE_GALLERY_NAMESPACE.'copySuffix', 'copy'); ?>" class="regular-text code" type="text">
			</td>
		</tr>

		<tr>
			<th scope="row"><?php _e('Copy gallery date', 'gallery-images-ape'); ?></th>
			<td>
				<fieldset>
					<label title='<?php _e('Disable', 'gallery-images-ape'); ?>'>
						<input type='radio' name='<?php echo WPAPE_GALLERY_NAMESPACE.'copyDate'; ?>' value='0' <?php if( !get_option(WPAPE_GALLERY_NAMESPACE.'copyDate', 0) ) echo " checked='checked'";?> /> <?php _e('Disable', 'gallery-images-ape'); ?>
					</label>
					<br />
					<label title='<?php _e('Enable', 'gallery-images-ape'); ?>'>
						<input type='radio' name='<?php echo WPAPE_GALLERY_NAMESPACE.'copyDate'; ?>' value='1' <?php if(  get_option(WPAPE_GALLERY_NAMESPACE.'copyDate', 0)=='1' ) echo " checked='checked'";?>  /> <?php _e('Enable', 'gallery-images-ape'); ?>
					</label><br />			
				</fieldset>
				<p class="description"></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php _e('Copy gallery slug', 'gallery-images-ape'); ?></th>
			<td>
				<fieldset>
					<label title='<?php _e('Enable', 'gallery-images-ape'); ?>'>
						<input type='radio' name='<?php echo WPAPE_GALLERY_NAMESPACE.'emptySlug'; ?>' value='0' <?php if( !get_option(WPAPE_GALLERY_NAMESPACE.'emptySlug', 0) ) echo " checked='checked'";?> /> <?php _e('Enable', 'gallery-images-ape'); ?>
					</label>
					<br />
					<label title='<?php _e('Disable', 'gallery-images-ape'); ?>'>
						<input type='radio' name='<?php echo WPAPE_GALLERY_NAMESPACE.'emptySlug'; ?>' value='1' <?php if(  get_option(WPAPE_GALLERY_NAMESPACE.'emptySlug', 0)=='1' ) echo " checked='checked'";?>  /> <?php _e('Disable', 'gallery-images-ape'); ?>
					</label><br />			
				</fieldset>
				<p class="description"></p>
			</td>
		</tr>
	</table>
<?php 
}

function wpApeSettingSourceOptions(){ 
?>
	<br/>
	<p  class="description">
		<?php _e('here you can configure settings for default gallery usage.', 'gallery-images-ape'); ?>
		<br/>
		<?php _e('Just select which Ape gallery will provide settings source for default WordPress gallery which gonna be published on your website', 'gallery-images-ape'); ?>
	</p>
	<table class="form-table">
		<tr>
			<th scope="row">
				<?php _e('Replace default gallery shortcode', 'gallery-images-ape'); ?>
			</th>
			<td>
				<fieldset>
					<label title='<?php _e('Disable', 'gallery-images-ape'); ?>'>
						<input type='radio' name='<?php echo WPAPE_GALLERY_NAMESPACE.'sourceGalleryEnable'; ?>' value='0' <?php if( !get_option(WPAPE_GALLERY_NAMESPACE.'sourceGalleryEnable') ) echo " checked='checked'";?> /> 
						<?php _e('Disable', 'gallery-images-ape'); ?>
					</label><br />
					<label title='<?php _e('Enable', 'gallery-images-ape'); ?>'>
						<input type='radio' name='<?php echo WPAPE_GALLERY_NAMESPACE.'sourceGalleryEnable'; ?>' value='1' <?php if( get_option(WPAPE_GALLERY_NAMESPACE.'sourceGalleryEnable')=='1' ) echo " checked='checked'";?>  /> 
						<?php _e('Enable', 'gallery-images-ape'); ?>
					</label><br />			
				</fieldset>
				<p class="description"><?php _e('all setting for default gallery will be read from selected source gallery', 'gallery-images-ape'); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php _e('Default gallery source', 'gallery-images-ape'); ?></th>
			<td>
				<?php
					 $args = array(
				      'child_of'     => 0,
				      'sort_order'   => 'ASC',
				      'sort_column'  => 'post_title',
				      'hierarchical' => 1,
				      'selected'     => get_option(WPAPE_GALLERY_NAMESPACE.'sourceGallery', ''),
				      'name'         => WPAPE_GALLERY_NAMESPACE.'sourceGallery',
				      'id'           => WPAPE_GALLERY_NAMESPACE.'sourceGallery',
				      'class'		=> ' ',
				      'echo'    => 1,
				      'show_option_none' => '',
				      'option_none_value' => '0',
				      'post_type' => WPAPE_GALLERY_POST
				);
		      	 wp_dropdown_pages( $args ); 
		      	 ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php _e('Gallery Shortcode catcher', 'gallery-images-ape'); ?></th>
			<td>
				<input name="<?php echo WPAPE_GALLERY_NAMESPACE.'shortcode'; ?>" id="<?php echo WPAPE_GALLERY_NAMESPACE.'shortcode'; ?>" value="<?php echo apeGalleryHelper::clearString(get_option(WPAPE_GALLERY_NAMESPACE.'shortcode', '')); ?>" class="regular-text" type="text">
				<p class="description">
					<?php _e('here you can define custom shortcode of any gallery on your blog and our gallery automatically pick up this shortcodes and replace it by proper styles and settings pre-defined in our gallery. This option depend of implementation of other plugin ( support of wordpress API is required)', 'gallery-images-ape'); ?>
				</p>
			</td>
		</tr>
	</table>
<?php 
}

wpApeGallerySetting();