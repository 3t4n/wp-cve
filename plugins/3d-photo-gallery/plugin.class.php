<?php 
	/**
	* Plugin Main Class
	*/
	class LA_Photo_Gallery
	{
		
		function __construct()
		{
			add_action( "admin_menu", array($this,'photo_gallery_admin_options'));
			add_action( 'admin_enqueue_scripts', array($this,'admin_enqueuing_scripts'));
			add_action('wp_ajax_la_save_photo_gallery_images', array($this, 'save_photo_gallery_images'));
			add_shortcode( '3D-photo-gallery', array($this, 'render_photo_gallery') );
		}
	

		function photo_gallery_admin_options(){
			add_menu_page( '3D Photo Gallery', '3D Photo Gallery', 'manage_options', 'photo_gallery', array($this,'render_menu_page'), 'dashicons-format-image' );
		}

		function admin_enqueuing_scripts($slug){
		if ($slug == 'toplevel_page_photo_gallery') {
			wp_enqueue_media();
			wp_enqueue_script( 'photo-gallery-admin-js', plugins_url( 'admin/admin.js' , __FILE__ ), array('jquery', 'jquery-ui-sortable', 'jquery-ui-accordion') );
			wp_enqueue_style( 'photo-gallery-admin-css', plugins_url( 'admin/style.css' , __FILE__ ));
			wp_localize_script( 'photo-gallery-admin-js', 'laAjax', array( 'url' => admin_url( 'admin-ajax.php' )));
		}
		}
	
		function render_menu_page(){
			$saved_images = get_option('la_photo_gallery');
			?>	
				
				<div class="la-photo-gallery">
				<a style="text-decoration:none;" href="https://codecanyon.net/item/unite-gallery-wordpress-gallery-plugin/10458750?ref=labibahmed" target="_blank"><h4 style="padding: 10px;background: #31b999;color: #fff;margin-bottom: 0px;text-align:center;font-size:24px;">TRY Pro Version</h4></a><br>
				
					<h3>3D Photo Gallery</h3>
					<p class="description">Select Images for the photo gallery.Put <b>"[3D-photo-gallery]"</b> this shortcode to render gallery on any page or post.Give width and height without px</p>
					<!-- <label for=""> Image widht : <input id="image-widht" type="text" value="<?php $saved_images['width']; ?>" placeholder="max-width = 500"></label>
					<label for=""> Image Height : <input id="image-height" type="text" value="<?php $saved_images['height']; ?>"  placeholder="max-height = 400"></label> -->
					<table class="form-table">
							<tr>
								<td>Gallery Width</td>
								<td><input id="image-widht" class="widefat" type="number"  value="<?php $saved_images['width']; ?>"></td>
								<td>Gallery Height</td>
								<td><input id="image-height" class="widefat" type="number" value="<?php $saved_images['height']; ?>"></td>
							</tr>
						</table>
				
					<button class="button-secondary upload_image_button">Upload Images</button>
					<hr>
					<div class="selected_images">
						<?php if ($saved_images['images'] != '') { 
							$decs = $saved_images['des'];
							$url = $saved_images['url'];
						foreach ($saved_images['images'] as $key => $value) {
							echo '<div><img src="'.$value.'"><span class="dashicons dashicons-dismiss"></span><textarea placeholder="Description" name="" id="desc">'.$decs[$key].'</textarea>
								<label>URL<input id="image-url" class="widefat" type="text" value="'.$url[$key].'"></label>
							</div>';
						}
					} ?>
					</div>
					<hr style="clear: both;  margin-top: 70px;">
					<button class="save_gallery button button-primary button-large"> Save Gallery </button>
					
					<span id="gallery-load"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>images/ajax-loader.gif"> Saving....</span>
					<span id="gallery-saved"> <b>Gallery Saved</b> </span>

		
				</div>
				
			<?php
		}

		function save_photo_gallery_images(){
		if (isset($_REQUEST['images'],$_REQUEST['des'],$_REQUEST['url'])) {
			update_option( 'la_photo_gallery', $_REQUEST );
		}

		die(0);
		}

		function render_photo_gallery(){
			$saved_images = get_option('la_photo_gallery');

			wp_enqueue_style( 'photo-gallery-css', plugins_url( 'css/style.css' , __FILE__ ));
			wp_enqueue_script( 'modernizr-js', plugins_url( 'js/modernizr.custom.53451.js' , __FILE__ ), array('jquery', 'jquery-ui-core', 'jquery-ui-draggable') );
			wp_enqueue_script( 'gallery-js', plugins_url( 'js/jquery.gallery.js' , __FILE__ ), array('jquery') );
			wp_enqueue_script( 'custom-js', plugins_url( 'js/custom.js' , __FILE__ ), array('jquery') );
			// wp_enqueue_script( 'wcp-custom-script');
			?>
			<section id="dg-container" class="dg-container">
				<div class="dg-wrapper">
					<?php if ($saved_images['images'] != '') {
						$decs = $saved_images['des'];
						$url = $saved_images['url'];

				foreach ($saved_images['images'] as $key => $value) {
					echo '<a href="'.$url[$key].'" style="width:'.$saved_images['width'].'px;height:'.$saved_images['height'].'px;" target="_blank"><img src="'.$value.'" style="width: 100%;height: 90%;"><div>'.$decs[$key].'</div></a>';
				}
			} ?>
				</div>
				<nav>	
					<span class="dg-prev">next</span>
					<span class="dg-next">&gt;</span>
				</nav>
			</section>

			<?php	
		}
	}
 ?>