<?php
class MaxGalleriaAdmin {
	public function __construct() {
		add_action('admin_menu', array($this, 'add_menu_pages'));
	}
	
	public function add_menu_pages() {
		
	  global $maxgalleria;
		
		$edit_page = 'edit.php?post_type=' . MAXGALLERIA_POST_TYPE;		
		
		do_action(MAXGALLERIA_ACTION_BEFORE_ADMIN_MENU_PAGES, $edit_page);
				
//    $parent_slug = $edit_page;
//    $page_title = esc_html__('MaxGalleria: Get Addons', 'maxgalleria');
//    $sub_menu_title = esc_html__('Get Addons', 'maxgalleria');
//    $capability = 'manage_options';
//    $menu_slug = 'maxgalleria-addons';
//    $function = array($this, 'add_addon_page');
//    add_submenu_page($parent_slug, $page_title, $sub_menu_title, $capability, $menu_slug, $function);		
      
		$parent_slug = $edit_page;
		$page_title = esc_html__('Upgrade to Pro', 'maxgalleria');
		$sub_menu_title = esc_html__('Upgrade to Pro', 'maxgalleria');
		$capability = 'upload_files';
		$menu_slug = 'mg-upgrade-to-pro';
		$function = array($this, 'mg_upgrade_to_pro');
		add_submenu_page($parent_slug, $page_title, $sub_menu_title, $capability, $menu_slug, $function);
      		
		$parent_slug = $edit_page;
		$page_title = esc_html__('MaxGalleria: NextGEN Importer', 'maxgalleria');
		$sub_menu_title = esc_html__('NextGEN Importer', 'maxgalleria');
		$capability = 'upload_files';
		$menu_slug = 'maxgalleria-nextgen-importer';
		$function = array($this, 'add_nextgen_importer_page');
		add_submenu_page($parent_slug, $page_title, $sub_menu_title, $capability, $menu_slug, $function);
		
		$parent_slug = $edit_page;
		$page_title = esc_html__('MaxGalleria: Settings', 'maxgalleria');
		$sub_menu_title = esc_html__('Settings', 'maxgalleria');
		$capability = 'manage_options';
		$menu_slug = 'maxgalleria-settings';
		$function = array($this, 'add_settings_page');
		add_submenu_page($parent_slug, $page_title, $sub_menu_title, $capability, $menu_slug, $function);
        
		$parent_slug = $edit_page;
		$page_title = esc_html__('MaxGalleria: Support', 'maxgalleria');
		$sub_menu_title = esc_html__('Support', 'maxgalleria');
		$capability = 'manage_options';
		$menu_slug = 'maxgalleria-support';
		$function = array($this, 'add_support_page');
		add_submenu_page($parent_slug, $page_title, $sub_menu_title, $capability, $menu_slug, $function);
		
//		$parent_slug = $edit_page;
//		$page_title = esc_html__('MaxGalleria: Get Addons', 'maxgalleria');
//		$sub_menu_title = esc_html__('Get Addons', 'maxgalleria');
//		$capability = 'manage_options';
//		$menu_slug = 'maxgalleria-addons';
//		$function = array($this, 'add_addon_page');
//		add_submenu_page($parent_slug, $page_title, $sub_menu_title, $capability, $menu_slug, $function);
		    		
		$capability = 'manage_options';
		$menu_slug = 'mg-admin-notice';
		$function = array($this, 'set_admin_notice_true');    
		add_submenu_page($parent_slug, '', '', $capability, $menu_slug, $function);
        
		$capability = 'manage_options';
		$menu_slug = 'mg-review-notice';
		$function = array($this, 'set_review_notice_true');    
		add_submenu_page($parent_slug, '', '', $capability, $menu_slug, $function);
    
		$capability = 'manage_options';
		$menu_slug = 'mg-review-later';
		$function = array($this, 'set_review_notice_true');    
		add_submenu_page($parent_slug, '', '', $capability, $menu_slug, $function);
    		
		do_action(MAXGALLERIA_ACTION_AFTER_ADMIN_MENU_PAGES, $edit_page);
	}

	public function add_nextgen_importer_page() {
		require_once 'admin/nextgen-importer.php';
	}

	public function add_settings_page() {
		require_once 'admin/settings.php';
	}
	
	public function add_support_page() {
		require_once 'admin/support.php';
	}
  
	public function set_admin_notice_true() {
    
    $current_user_id = get_current_user_id(); 
    
    update_user_meta( $current_user_id, MAXGALLERIA_ADMIN_NOTICE, "off" );
    
    $request = sanitize_url($_SERVER["HTTP_REFERER"]);
    
    echo "<script>window.location.href = '" . esc_url_raw($request) . "'</script>";             
    
	}
  
	public function set_review_notice_true() {
    
    $current_user_id = get_current_user_id(); 
    
    update_user_meta( $current_user_id, MAXGALLERIA_REVIEW_NOTICE, "off" );
    
    $request = sanitize_url($_SERVER["HTTP_REFERER"]);
    
    echo "<script>window.location.href = '" . esc_url_raw($request) . "'</script>";             
    
	}
  
	public function set_review_later() {
    
    $current_user_id = get_current_user_id(); 
    
    $review_date = date('Y-m-d', strtotime("+14 days"));
        
    update_user_meta( $current_user_id, MAXGALLERIA_REVIEW_NOTICE, $review_date );
    
    $request = sanitize_url($_SERVER["HTTP_REFERER"]);
    
    echo "<script>window.location.href = '" . esc_url_raw($request) . "'</script>";             
    
	}
	
	public function add_addon_page($param) {		
	?>

<div class="utp-body"> 			
	<div id="get-mg" class="width-50">
		<div id="page-logo">
			<a href="<?php echo esc_url_raw(MG_ADDON_PAGE_LINK) ?>" target="_blank">
			  <img alt="" src="<?php echo esc_url_raw(MAXGALLERIA_PLUGIN_URL) ?>/images/addons/get-mg-addons.png" width="311" height="50">
			</a>
		</div>	
		<div id="mg-buy-btn">
			<a href="<?php echo esc_url_raw(MG_ADDON_PAGE_LINK) ?>" target="_blank">
				<img alt="" src="<?php echo esc_url_raw(MAXGALLERIA_PLUGIN_URL .'/images/addons/buy-addon-btn.png') ?>" width="228" height="56" >
			</a>
		</div>	
	</div>	
  <div class="top-section">
    <div class="container">
      <div class="row">
        <div class="width-50">
					<div id="ao-title-area">								
						<h1><?php esc_html_e('ADDONS TO ENHANCE YOUR WORDPRESS<BR>GALLERY EXPERIENCE', 'maxgalleria'); ?></h1>						
					</div><!-- ao-title-area -->
					<div class="ao-section-title">
						<?php esc_html_e('LAYOUT ADDONS', 'maxgalleria'); ?>
					</div>
					<div class="ao-row">
						
						<div class="ao-item">
							<div class="cover-title"><a href="<?php echo esc_url_raw(MG_SLICK_SLIDER_LINK) ?>" target="_blank">SLICK SLIDER</a></div>
								<a href="<?php echo esc_url_raw(MG_SLICK_SLIDER_LINK); ?>" target="_blank">
									<img alt="slick carousel image" src="<?php echo esc_url_raw(MAXGALLERIA_PLUGIN_URL .'/images/addons/slick-cover.png') ?>" width="230" height="131" >
								</a>
							<div class='cover-caption'>The last carousel you'll ever need!</div>
					  </div>
						
						<div class="ao-item">
							<div class="cover-title"><a href="<?php echo esc_url_raw(MG_IMAGE_CAROUSEL_LINK) ?>" target="_blank">IMAGE CAROUSEL</a></div>
								<a href="<?php echo esc_url_raw(MG_IMAGE_CAROUSEL_LINK) ?>" target="_blank">
									<img alt="image carousel addon image" src="<?php echo esc_url_raw(MAXGALLERIA_PLUGIN_URL .'/images/addons/image-carousel-cover.png') ?>" width="230" height="131" >
								</a>
							<div class='cover-caption'>Turn your galleries into carousels</div>
					  </div>
						
						<div class="ao-item">
							<div class="cover-title"><a href="<?php echo esc_url_raw(MG_ALBUMS_LINK) ?>" target="_blank">ALBUMS</a></div>
								<a href="<?php echo esc_url_raw(MG_ALBUMS_LINK); ?>" target="_blank">
									<img alt="albums addon image" src="<?php echo esc_url_raw(MAXGALLERIA_PLUGIN_URL .'/images/addons/albums-cover.png') ?>" width="230" height="131" >
								</a>
							<div class='cover-caption'>Organize your galleries into albums</div>
					  </div>
												
					</div>
					<div class="clearfix"></div>
					
					<div class="ao-row">
						
						<div class="ao-item">
							<div class="cover-title"><a href="<?php echo esc_url_raw(MG_MASONRY_LINK) ?>" target="_blank">MASONRY</a></div>
							  <a href="<?php echo esc_url_raw(MG_MASONRY_LINK) ?>" target="_blank">
							    <img alt="masonry addon image" src="<?php echo esc_url_raw(MAXGALLERIA_PLUGIN_URL .'/images/addons/masonry-cover.png') ?>" width="230" height="131" >
							  </a>
							<div class='cover-caption'>Display Images in a Masonry Grid</div>
					  </div>
						
						<div class="ao-item">
							<div class="cover-title"><a href="<?php echo esc_url_raw(MG_IMAGE_SHOWCASE_LINK) ?>" target="_blank">IMAGE SHOWCASE</a></div>
							  <a href="<?php echo esc_url_raw(MG_IMAGE_SHOWCASE_LINK) ?>" target="_blank">
									<img alt="image showcase addon image" src="<?php echo esc_url_raw(MAXGALLERIA_PLUGIN_URL .'/images/addons/image-slider-cover.png') ?>" width="230" height="131" >
							  </a>
							<div class='cover-caption'>Showcase image with thumbnails</div>
					  </div>
						
						<div class="ao-item">
							<div class="cover-title"><a href="<?php echo esc_url_raw(MG_IMAGE_SLIDER_LINK) ?>" target="_blank">IMAGE SLIDER</a></div>
							  <a href="<?php echo esc_url_raw(MG_IMAGE_SLIDER_LINK) ?>" target="_blank">
									<img alt="image slider addon image" src="<?php echo esc_url_raw(MAXGALLERIA_PLUGIN_URL .'/images/addons/albums-cover.png') ?>" width="230" height="131" >
							  </a>
							<div class='cover-caption'>Turn your galleries into sliders</div>
					  </div>
												
					</div>
					<div class="clearfix"></div>
					
					<div class="ao-row">
						
						<div class="ao-item">
							<div class="cover-title"><a href="<?php echo esc_url_raw(MG_VIDEO_SHOWCASE_LINK) ?>" target="_blank">VIDEO SHOWCASE</a></div>
							  <a href="<?php echo esc_url_raw(MG_VIDEO_SHOWCASE_LINK) ?>" target="_blank">
									<img alt="video showcase image" src="<?php echo esc_url_raw(MAXGALLERIA_PLUGIN_URL .'/images/addons/video-showcase-cover.png') ?>" width="230" height="131" >
							  </a>
							<div class='cover-caption'>Showcase video with thumbnails</div>
					  </div>
						
						<div class="ao-item">
							<div class="cover-title"><a href="<?php echo esc_url_raw(MG_FWGRID_LINK) ?>" target="_blank">FULL WIDTH GRID</a></div>
							  <a href="<?php echo esc_url_raw(MG_FWGRID_LINK) ?>" target="_blank">
									<img alt="Full Width Grid image" src="<?php echo esc_url_raw(MAXGALLERIA_PLUGIN_URL .'/images/addons/fwg-cover.png') ?>" width="230" height="131" >
							  </a>
							<div class='cover-caption'>Full Width Grid</div>
					  </div>
						
						<div class="ao-item">
							<div class="cover-title"><a href="<?php echo esc_url_raw(MG_HERO_LINK) ?>" target="_blank">HERO SLIDER</a></div>
							  <a href="<?php echo esc_url_raw(MG_HERO_LINK) ?>" target="_blank">
									<img alt="Hero Slider image" src="<?php echo esc_url_raw(MAXGALLERIA_PLUGIN_URL .'/images/addons/hero-cover.png') ?>" width="230" height="131" >
							  </a>
							<div class='cover-caption'>Hero Slider</div>
					  </div>
																		
					</div>
					<div class="clearfix"></div>
					
					<div id="sources-title" class="ao-section-title">
						<?php esc_html_e('MEDIA SOURCES', 'maxgalleria'); ?>
					</div>
					
					<div class="ao-row">
						
						<div class="ao-item">
							<div class="cover-title"><a href="<?php echo esc_url_raw(MLPP_LINK) ?>" target="_blank">MEDIA LIBRARY FOLDERS PRO</a></div>
							  <a href="<?php echo esc_url_raw(MLPP_LINK) ?>" target="_blank">
							    <img alt="media library folders pro image" src="<?php echo esc_url_raw(MAXGALLERIA_PLUGIN_URL .'/images/addons/mlpp-cover.png') ?>" width="230" height="131" >
							  </a>
							<div class='cover-caption'>Work smart with Large Media Libraries</div>
					  </div>
						
						<div class="ao-item">
							<div class="cover-title"><a href="<?php echo esc_url_raw(MG_FACEBOOK_LINK) ?>" target="_blank">FACEBOOK</a></div>
							  <a href="<?php echo esc_url_raw(MG_FACEBOOK_LINK) ?>" target="_blank">
									<img alt="facebook addon image" src="<?php echo esc_url_raw(MAXGALLERIA_PLUGIN_URL .'/images/addons/facebook-cover.png') ?>" width="230" height="131" >
							  </a>
							<div class='cover-caption'>Add Facebook photos to your galleries</div>
					  </div>
						
						<div class="ao-item">
							<div class="cover-title"><a href="<?php echo esc_url_raw(MG_VIMEO_LINK) ?>" target="_blank">VIMEO</a></div>
							  <a href="<?php echo esc_url_raw(MG_VIMEO_LINK) ?>" target="_blank">
									<img alt="vimeo addon image" src="<?php echo esc_url_raw(MAXGALLERIA_PLUGIN_URL .'/images/addons/albums-cover.png') ?>" width="230" height="131" >
							  </a>
							<div class='cover-caption'>Use Vimeo videos in your galleries</div>
					  </div>
												
					</div>
					<div class="clearfix"></div>
					
					<div class="ao-half-item">
						<p>&nbsp;</p>
					</div>
					
					<div class="ao-row ao-bottom">
							<div class="ao-item-container">
								<div class="ao-item">
									<div class="cover-title"><a href="<?php echo esc_url_raw(MG_INSTAGRAM_LINK) ?>" target="_blank">INSTAGRAM</a></div>
										<a href="<?php echo esc_url_raw(MG_INSTAGRAM_LINK) ?>" target="_blank">
											<img alt="instagrm addon image" src="<?php echo esc_url_raw(MAXGALLERIA_PLUGIN_URL .'/images/addons/instagram-cover.png') ?>" width="230" height="131" >
										</a>
									<div class='cover-caption'>Add Instagram photos to your galleries</div>
								</div>

								<div class="ao-item">
									<div class="cover-title"><a href="<?php echo esc_url_raw(MG_FLICKR_LINK) ?>" target="_blank">FLICKR</a></div>
										<a href="<?php echo esc_url_raw(MG_FLICKR_LINK) ?>" target="_blank">
											<img alt="flickr addon image" src="<?php echo esc_url_raw(MAXGALLERIA_PLUGIN_URL .'/images/addons/flickr-cover.png') ?>" width="230" height="131" >
										</a>
									<div class='cover-caption'>Pull images from your Flickr stream</div>
								</div>
						</div>
					</div>
					
					<div class="ao-half-item">
						<p>&nbsp;</p>
					</div>
					
					<!--<div class="clearfix"></div>-->
										
        </div>		
      </div><!-- row -->		
    </div>		
  </div>		
</div>		
	<?php
	}
  
  public function mg_upgrade_to_pro() {
    
    include_once "admin/upgrade-to-pro.php";

  }
    
}
?>