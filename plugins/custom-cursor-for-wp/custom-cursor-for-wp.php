<?php
/*
Plugin Name: Custom Cursor For WP
description: Custom Cursor For WP - Help you to customize your WordPress website cursor or mouse pointer and It's very easy to use. You get a variety of cursor options to choose from like the animated cursor, WordPress dashicon icon cursor and even you can add your customized image as cursor as per your choice.
Version: 1.0.0
Author: Softices
Author URI: https://softices.com/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html 
*/ 

function ccwp_insert_jquery_head(){
		wp_enqueue_script('jquery', false, array(), false, false);
}
add_filter('wp_enqueue_scripts','ccwp_insert_jquery_head',1);

add_action('admin_menu', 'ccwp_custom_cursor_menu');
function ccwp_custom_cursor_menu() {
	add_menu_page('Custom Cursor For WP', 'Custom Cursor For WP', 'administrator', __FILE__, 'ccwp_custom_cursor_menu_page','dashicons-art
',99999);
	add_action( 'admin_init', 'ccwp_register_custom_cursor_menu_settings' );
}

function ccwp_register_custom_cursor_menu_settings() {
	 register_setting( 'ccwp_register_custom_cursor_menu_settings_group', 'status_ccwp' );
	 register_setting( 'ccwp_register_custom_cursor_menu_settings_group', 'cursor_type_ccwp' );
	 register_setting( 'ccwp_register_custom_cursor_menu_settings_group', 'ccwp_cursor_color' );
	 register_setting( 'ccwp_register_custom_cursor_menu_settings_group', 'ccwp_show_default_cursor' );
	 register_setting( 'ccwp_register_custom_cursor_menu_settings_group', 'ccwp_cursor_size' );
	 register_setting( 'ccwp_register_custom_cursor_menu_settings_group', 'ccwp_front_cursor_type' );
	 register_setting( 'ccwp_register_custom_cursor_menu_settings_group', 'cursor-image-url' );
}

function ccwp_custom_cursor_menu_page()
{
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-tabs');  
	wp_enqueue_media();
	wp_enqueue_style("jquery-ui-tab-min", plugin_dir_url(__FILE__) . "css/jquery-ui.min.css");
	?>
	<div class="wrap">
	<h1>Custom Cursor For WP</h1>
		<form id='ccwp-admin-custom-cursor-form' method="post" action="options.php" enctype="multipart/form-data">
			<?php settings_fields( 'ccwp_register_custom_cursor_menu_settings_group' ); ?>
			<?php do_settings_sections( 'ccwp_register_custom_cursor_menu_settings_group' ); ?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">Custom Cursor <span class='semi-text'>(Enable/Disable)</span></th>
					<td>
						<label class="switch">
							<input type="checkbox" name='status_ccwp' value='enable' <?php if(get_option('status_ccwp')=='enable'){ echo  esc_attr("checked='checked'"); }  ?>>
							<span class="slider"></span>
						</label>
					</td>	
				</tr>
				<tr valign="top">
					<th scope="row">Select Cursor Type</th>
					<td>
						<div id="tabs">
							<ul>
								<li><a href="#tabs-1" class='cursor-type-link' data-cursor_type='animated_cursor'>Animated Cursor</a></li>
								<li><a href="#tabs-2" class='cursor-type-link' data-cursor_type='icon_cursor'>Wordpress Dashicon Icon Cursor</a></li>
								<li><a href="#tabs-3" class='cursor-type-link' data-cursor_type='image_cursor'>Cutom Image Cursor</a></li>
								<input type='hidden' name='ccwp_front_cursor_type' value='<?php echo esc_attr( get_option('ccwp_front_cursor_type')); ?>' >
							</ul>
							<div id="tabs-1">
									
									<label class='ccwp-img-wrap'>
										<input type='radio' name='cursor_type_ccwp' value='cursor-4' <?php if(get_option('cursor_type_ccwp')=='cursor-4'){ echo  esc_attr("checked='checked'"); }  ?>>
										<img src="<?php echo esc_attr(plugin_dir_url(__FILE__)); ?>images/cursor-4.gif" alt="">
									</label>
									<label class='ccwp-img-wrap'>
										<input type='radio' name='cursor_type_ccwp' value='cursor-5' <?php if(get_option('cursor_type_ccwp')=='cursor-5'){ echo  esc_attr("checked='checked'"); }  ?>>
										<img src="<?php echo esc_attr(plugin_dir_url(__FILE__)); ?>images/cursor-5.gif" alt="">
									</label>
									<label class='ccwp-img-wrap'>
										<input type='radio' name='cursor_type_ccwp' value='cursor-6' <?php if(get_option('cursor_type_ccwp')=='cursor-6'){ echo  esc_attr("checked='checked'"); }  ?>>
										<img src="<?php echo esc_attr(plugin_dir_url(__FILE__)); ?>images/cursor-6.gif" alt="">
									</label>
									<label class='ccwp-img-wrap'>
										<input type='radio' name='cursor_type_ccwp' value='cursor-7' <?php if(get_option('cursor_type_ccwp')=='cursor-7'){ echo  esc_attr("checked='checked'"); }  ?>>
										<img src="<?php echo esc_attr(plugin_dir_url(__FILE__)); ?>images/cursor-7.gif" alt="">
									</label>
									<label class='ccwp-img-wrap'>
										<input type='radio' name='cursor_type_ccwp' value='cursor-8' <?php if(get_option('cursor_type_ccwp')=='cursor-8'){ echo  esc_attr("checked='checked'"); }  ?>>
										<img src="<?php echo esc_attr(plugin_dir_url(__FILE__)); ?>images/cursor-8.gif" alt="">
									</label>
									<label class='ccwp-img-wrap'>
										<input type='radio' name='cursor_type_ccwp' value='cursor-9' <?php if(get_option('cursor_type_ccwp')=='cursor-9'){ echo  esc_attr("checked='checked'"); }  ?>>
										<img src="<?php echo esc_attr(plugin_dir_url(__FILE__)); ?>images/cursor-9.gif" alt="">
									</label>
									<label class='ccwp-img-wrap'>
										<input type='radio' name='cursor_type_ccwp' value='cursor-1' <?php if(get_option('cursor_type_ccwp')=='cursor-1'){ echo esc_attr("checked='checked'"); }  ?>>
										<img src="<?php echo esc_attr(plugin_dir_url(__FILE__)); ?>images/cursor-1.gif" alt="">
									</label>
									<label class='ccwp-img-wrap'>
										<input type='radio' name='cursor_type_ccwp' value='cursor-2' <?php if(get_option('cursor_type_ccwp')=='cursor-2'){ echo  esc_attr("checked='checked'"); }  ?>>
										<img src="<?php echo esc_attr(plugin_dir_url(__FILE__)); ?>images/cursor-2.gif" alt="">
									</label>
									<label class='ccwp-img-wrap'>
										<input type='radio' name='cursor_type_ccwp' value='cursor-3' <?php if(get_option('cursor_type_ccwp')=='cursor-3'){ echo  esc_attr("checked='checked'"); }  ?>>
										<img src="<?php echo esc_attr(plugin_dir_url(__FILE__)); ?>images/cursor-3.gif" alt="">
									</label>
							</div>
							<div id="tabs-2">
								<?php 
								$dashicon_array=array(
									"dashicons dashicons-menu",
									"dashicons dashicons-admin-site",
									"dashicons dashicons-dashboard",
									"dashicons dashicons-admin-post",
									"dashicons dashicons-admin-media",
									"dashicons dashicons-admin-links",
									"dashicons dashicons-admin-page",
									"dashicons dashicons-admin-comments",
									"dashicons dashicons-admin-appearance",
									"dashicons dashicons-admin-plugins",
									"dashicons dashicons-admin-users",
									"dashicons dashicons-admin-tools",
									"dashicons dashicons-admin-settings",
									"dashicons dashicons-admin-network",
									"dashicons dashicons-admin-home",
									"dashicons dashicons-admin-generic",
									"dashicons dashicons-admin-collapse",
									"dashicons dashicons-filter",
									"dashicons dashicons-admin-customizer",
									"dashicons dashicons-admin-multisite",
									"dashicons dashicons-welcome-write-blog",
									"dashicons dashicons-welcome-add-page",
									"dashicons dashicons-welcome-view-site",
									"dashicons dashicons-welcome-widgets-menus",
									"dashicons dashicons-welcome-comments",
									"dashicons dashicons-welcome-learn-more",
									"dashicons dashicons-format-aside",
									"dashicons dashicons-format-image",
									"dashicons dashicons-format-gallery",
									"dashicons dashicons-format-video",
									"dashicons dashicons-format-status",
									"dashicons dashicons-format-quote",
									"dashicons dashicons-format-chat",
									"dashicons dashicons-format-audio",
									"dashicons dashicons-camera",
									"dashicons dashicons-images-alt",
									"dashicons dashicons-images-alt2",
									"dashicons dashicons-video-alt",
									"dashicons dashicons-video-alt2",
									"dashicons dashicons-video-alt3",
									"dashicons dashicons-media-archive",
									"dashicons dashicons-media-audio",
									"dashicons dashicons-media-code",
									"dashicons dashicons-media-default",
									"dashicons dashicons-media-document",
									"dashicons dashicons-media-interactive",
									"dashicons dashicons-media-spreadsheet",
									"dashicons dashicons-media-text",
									"dashicons dashicons-media-video",
									"dashicons dashicons-playlist-audio",
									"dashicons dashicons-playlist-video",
									"dashicons dashicons-controls-play",
									"dashicons dashicons-controls-pause",
									"dashicons dashicons-controls-forward",
									"dashicons dashicons-controls-skipforward",
									"dashicons dashicons-controls-back",
									"dashicons dashicons-controls-skipback",
									"dashicons dashicons-controls-repeat",
									"dashicons dashicons-controls-volumeon",
									"dashicons dashicons-controls-volumeoff",
									"dashicons dashicons-image-crop",
									"dashicons dashicons-image-rotate",
									"dashicons dashicons-image-rotate-left",
									"dashicons dashicons-image-rotate-right",
									"dashicons dashicons-image-flip-vertical",
									"dashicons dashicons-image-flip-horizontal",
									"dashicons dashicons-image-filter",
									"dashicons dashicons-undo",
									"dashicons dashicons-redo",
									"dashicons dashicons-editor-bold",
									"dashicons dashicons-editor-italic",
									"dashicons dashicons-editor-ul",
									"dashicons dashicons-editor-ol",
									"dashicons dashicons-editor-quote",
									"dashicons dashicons-editor-alignleft",
									"dashicons dashicons-editor-aligncenter",
									"dashicons dashicons-editor-alignright",
									"dashicons dashicons-editor-insertmore",
									"dashicons dashicons-editor-spellcheck",
									"dashicons dashicons-editor-expand",
									"dashicons dashicons-editor-contract",
									"dashicons dashicons-editor-kitchensink",
									"dashicons dashicons-editor-underline",
									"dashicons dashicons-editor-justify",
									"dashicons dashicons-editor-textcolor",
									"dashicons dashicons-editor-paste-word",
									"dashicons dashicons-editor-paste-text",
									"dashicons dashicons-editor-removeformatting",
									"dashicons dashicons-editor-video",
									"dashicons dashicons-editor-customchar",
									"dashicons dashicons-editor-outdent",
									"dashicons dashicons-editor-indent",
									"dashicons dashicons-editor-help",
									"dashicons dashicons-editor-strikethrough",
									"dashicons dashicons-editor-unlink",
									"dashicons dashicons-editor-rtl",
									"dashicons dashicons-editor-break",
									"dashicons dashicons-editor-code",
									"dashicons dashicons-editor-paragraph",
									"dashicons dashicons-editor-table",
									"dashicons dashicons-align-left",
									"dashicons dashicons-align-right",
									"dashicons dashicons-align-center",
									"dashicons dashicons-align-none",
									"dashicons dashicons-lock",
									"dashicons dashicons-unlock",
									"dashicons dashicons-calendar",
									"dashicons dashicons-calendar-alt",
									"dashicons dashicons-visibility",
									"dashicons dashicons-hidden",
									"dashicons dashicons-post-status",
									"dashicons dashicons-edit",
									"dashicons dashicons-trash",
									"dashicons dashicons-sticky",
									"dashicons dashicons-external",
									"dashicons dashicons-arrow-up",
									"dashicons dashicons-arrow-down",
									"dashicons dashicons-arrow-right",
									"dashicons dashicons-arrow-left",
									"dashicons dashicons-arrow-up-alt",
									"dashicons dashicons-arrow-down-alt",
									"dashicons dashicons-arrow-right-alt",
									"dashicons dashicons-arrow-left-alt",
									"dashicons dashicons-arrow-up-alt2",
									"dashicons dashicons-arrow-down-alt2",
									"dashicons dashicons-arrow-right-alt2",
									"dashicons dashicons-arrow-left-alt2",
									"dashicons dashicons-sort",
									"dashicons dashicons-leftright",
									"dashicons dashicons-randomize",
									"dashicons dashicons-list-view",
									"dashicons dashicons-excerpt-view",
									"dashicons dashicons-grid-view",
									"dashicons dashicons-move",
									"dashicons dashicons-share",
									"dashicons dashicons-share-alt",
									"dashicons dashicons-share-alt2",
									"dashicons dashicons-rss",
									"dashicons dashicons-email",
									"dashicons dashicons-email-alt",
									"dashicons dashicons-networking",
									"dashicons dashicons-facebook",
									"dashicons dashicons-facebook-alt",
									"dashicons dashicons-twitter",
									"dashicons dashicons-hammer",
									"dashicons dashicons-art",
									"dashicons dashicons-migrate",
									"dashicons dashicons-performance",
									"dashicons dashicons-universal-access",
									"dashicons dashicons-universal-access-alt",
									"dashicons dashicons-tickets",
									"dashicons dashicons-nametag",
									"dashicons dashicons-clipboard",
									"dashicons dashicons-heart",
									"dashicons dashicons-megaphone",
									"dashicons dashicons-schedule",
									"dashicons dashicons-wordpress",
									"dashicons dashicons-wordpress-alt",
									"dashicons dashicons-pressthis",
									"dashicons dashicons-update",
									"dashicons dashicons-screenoptions",
									"dashicons dashicons-info",
									"dashicons dashicons-cart",
									"dashicons dashicons-feedback",
									"dashicons dashicons-cloud",
									"dashicons dashicons-translation",
									"dashicons dashicons-tag",
									"dashicons dashicons-category",
									"dashicons dashicons-archive",
									"dashicons dashicons-tagcloud",
									"dashicons dashicons-text",
									"dashicons dashicons-yes",
									"dashicons dashicons-no",
									"dashicons dashicons-no-alt",
									"dashicons dashicons-plus",
									"dashicons dashicons-plus-alt",
									"dashicons dashicons-plus-alt2",
									"dashicons dashicons-minus",
									"dashicons dashicons-dismiss",
									"dashicons dashicons-marker",
									"dashicons dashicons-star-filled",
									"dashicons dashicons-star-half",
									"dashicons dashicons-star-empty",
									"dashicons dashicons-flag",
									"dashicons dashicons-warning",
									"dashicons dashicons-location",
									"dashicons dashicons-location-alt",
									"dashicons dashicons-vault",
									"dashicons dashicons-shield",
									"dashicons dashicons-shield-alt",
									"dashicons dashicons-sos",
									"dashicons dashicons-search",
									"dashicons dashicons-slides",
									"dashicons dashicons-analytics",
									"dashicons dashicons-chart-pie",
									"dashicons dashicons-chart-bar",
									"dashicons dashicons-chart-line",
									"dashicons dashicons-chart-area",
									"dashicons dashicons-groups",
									"dashicons dashicons-businessman",
									"dashicons dashicons-id",
									"dashicons dashicons-id-alt",
									"dashicons dashicons-products",
									"dashicons dashicons-awards",
									"dashicons dashicons-forms",
									"dashicons dashicons-testimonial",
									"dashicons dashicons-portfolio",
									"dashicons dashicons-book",
									"dashicons dashicons-book-alt",
									"dashicons dashicons-download",
									"dashicons dashicons-upload",
									"dashicons dashicons-backup",
									"dashicons dashicons-clock",
									"dashicons dashicons-lightbulb",
									"dashicons dashicons-microphone",
									"dashicons dashicons-desktop",
									"dashicons dashicons-laptop",
									"dashicons dashicons-tablet",
									"dashicons dashicons-smartphone",
									"dashicons dashicons-phone",
									"dashicons dashicons-index-card",
									"dashicons dashicons-carrot",
									"dashicons dashicons-building",
									"dashicons dashicons-store",
									"dashicons dashicons-album",
									"dashicons dashicons-palmtree",
									"dashicons dashicons-tickets-alt",
									"dashicons dashicons-money",
									"dashicons dashicons-smiley",
									"dashicons dashicons-thumbs-up",
									"dashicons dashicons-thumbs-down",
									"dashicons dashicons-layout",
									"dashicons dashicons-paperclip",
								);
                                foreach ($dashicon_array as $das) {
									?>
									<label class='ccwp-img-wrap icon'>
										<input type='radio' name='cursor_type_ccwp' value='<?php echo esc_attr($das); ?>' <?php if(get_option('cursor_type_ccwp')==$das){ echo  esc_attr("checked='checked'"); }  ?>>
										<i class="<?php echo esc_attr($das);?>"></i>
									</label>
									<?php
								}
								?>
							</div>
							<div id="tabs-3">
								<div class='cc-image-wrap'>
									<div class='cc-image-section-wrap'>
										<input type='text' id='cursor-image-url' value='<?php echo esc_attr( get_option('cursor-image-url')); ?>' name='cursor-image-url'>
										<input type='button' value='Upload Image' id='upload-image-cursor-btn' class='button-secondary'>
										<input type='radio' class='image_cursor_radio' name='cursor_type_ccwp' value='image_cursor' <?php if(get_option('cursor_type_ccwp')=='image_cursor'){ echo  esc_attr("checked='checked'"); }  ?>>
									</div>
									<div class='cc-image-disp-section'>
										<?php 
											if(get_option('cursor-image-url')!='' && get_option('cursor-image-url')!=null)
											{
												?>
													<label class='ccwp-img-wrap'>
														<input type='radio'  name='cursor_type_ccwp' value='image_cursor' <?php if(get_option('cursor_type_ccwp')=='image_cursor'){ echo  esc_attr("checked='checked'"); }  ?>>
														<img class='disp-img' src='<?php echo esc_attr( get_option('cursor-image-url') ); ?>' >
													</label>
												<?php
											}
										?>
									</div>
								</div>
							</div>
						</div>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Cursor Color</th>
					<td>
						<input type='color' name='ccwp_cursor_color' value='<?php echo esc_attr(get_option('ccwp_cursor_color')); ?>'>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">Hide default cursor?</th>
					<td>
						<label class="switch">
							<input type="checkbox" name='ccwp_show_default_cursor' value='none' <?php if(get_option('ccwp_show_default_cursor')=='none'){ echo  esc_attr("checked='checked'"); }  ?>>
							<span class="slider"></span>
						</label>
					</td>	
				</tr>
				<tr valign="top">
					<th scope="row">Cursor Size</th>
					<td>
						<input type="number" name="ccwp_cursor_size" min="10" max="100" value='<?php echo esc_attr(get_option('ccwp_cursor_size'));?>'>
					</td>	
				</tr>
				<tr>
					<th scope='row'>
						<?php submit_button(); ?>
					</th>
					<td></td>
				</tr>
			</table>
		</form>
		<style>
			.ccwp-img-wrap [type=radio] { 
			position: absolute;
			opacity: 0;
			width: 0;
			height: 0;
			}
			span.semi-text {
				font-size: 12px;
				color: grey;
				font-style: italic;
			}
			.ccwp-img-wrap [type=radio] + img, .ccwp-img-wrap [type=radio] + i {
			cursor: pointer;
			}
			.ccwp-img-wrap [type=radio]:checked + img ,.ccwp-img-wrap [type=radio]:checked + i {
			outline: 2px solid #2271b1;
			}	
			.ccwp-img-wrap img {
				border: 1px solid #eae2e2;
				width: 67px;
				height: 67px;
				margin: 0 2px;
			}
			.ccwp-img-wrap.icon i {
				padding: 10px 10px;
			}
			.switch {
				position: relative;
				display: inline-block;
				width: 50px;
				height: 24px;
			}
			.switch input { 
				opacity: 0;
				width: 0;
				height: 0;
			}
			.slider {
			position: absolute;
			cursor: pointer;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			border-radius: 34px;
			background-color: #ccc;
			-webkit-transition: .4s;
			transition: .4s;
			}
			.slider:before {
			position: absolute;
			content: "";
			height: 16px;
			border-radius: 50%;
			width: 16px;
			left: 4px;
			bottom: 4px;
			background-color: white;
			-webkit-transition: .4s;
			transition: .4s;
			}
			input:checked + .slider {
			background-color: #2271b1;
			}
			input:focus + .slider {
			box-shadow: 0 0 1px #2271b1;
			}
			input:checked + .slider:before {
			-webkit-transform: translateX(26px);
			-ms-transform: translateX(26px);
			transform: translateX(26px);
			}
			.cc-image-wrap {
				padding: 50px 25px;
			}
			input.image_cursor_radio {
				display: none;
			}
			img.disp-img {
				border: 2px solid #e2d5d5;
				padding: 15px;
			}
			.cc-image-disp-section {
				padding: 0px 60px;
			}
			.cc-image-wrap {
				display: flex;
				flex-direction: row;
				justify-content: flex-start;
				align-items: center;
			}
		</style>
		<?php  
			$cursor_type_ccwp=get_option('cursor_type_ccwp');
		?>
		<script>
			jQuery( function() {
				var cursor_type_ccwp='<?php echo esc_attr($cursor_type_ccwp); ?>';
				if(cursor_type_ccwp.indexOf('dashicons') > -1)
				{
					var active_tab=1;
				}
				else if(cursor_type_ccwp=='image_cursor')
				{
					var active_tab=2;
				}
				else{
					var active_tab=0;
				}
				jQuery( "#tabs" ).tabs({
					active: active_tab,
				}
				);
 			 } );
			  jQuery(document).ready(function($){
				var mediaUploader;
			$('#upload-image-cursor-btn').on('click',function(e) {
			e.preventDefault();
			if( mediaUploader ){
				mediaUploader.open();
				return;				
			}
			mediaUploader = wp.media.frames.file_frame = wp.media({
				title: 'Select a Cursor Image',
				button: {
					text: 'Select Cursor Image'
				},
				multiple: false
			});
			mediaUploader.on('select', function(){
				logo = mediaUploader.state().get('selection').first().toJSON();
				$('#cursor-image-url').val(logo.url);
				$(document).find('img.disp-img').attr('src',logo.url);
				jQuery(document).find('input.image_cursor_radio').prop('checked',true);
			});
			mediaUploader.open();
		});
					jQuery(document).on('click','a.cursor-type-link',function(){
						var ctype=jQuery(this).data('cursor_type');
						jQuery(document).find('input[name="ccwp_front_cursor_type"]').val(ctype);
					});
			  });
		</script>
	</div>
<?php 
}

add_action( 'wp_footer', 'ccwp_footer_action');
function ccwp_footer_action()
{
	wp_enqueue_style('dashicons');

	$status_ccwp=get_option('status_ccwp');
	$cursor_type_ccwp=get_option('cursor_type_ccwp');
	$ccwp_cursor_color=get_option('ccwp_cursor_color');
	$ccwp_show_default_cursor=get_option('ccwp_show_default_cursor');
	$ccwp_cursor_size=get_option('ccwp_cursor_size');
	if($status_ccwp!=null && $status_ccwp!='' && $status_ccwp=='enable')
	{
		if($ccwp_cursor_color!='' && $ccwp_cursor_color!=null)
		{
			$ccwp_color=$ccwp_cursor_color;
		}
		else{
			$ccwp_color='#00000';
		}
		if($ccwp_show_default_cursor!='' && $ccwp_show_default_cursor!=null)
		{
			$ccwp_show_default_cursor=$ccwp_show_default_cursor;
		}
		else{
			$ccwp_show_default_cursor='default';
		}
		if($ccwp_cursor_size!='' && $ccwp_cursor_size!=null)
		{
			$ccwp_cursor_size=$ccwp_cursor_size.'px';
			$ccwp_cursor_size_cnt=$ccwp_cursor_size;
		}
		else
		{
			$ccwp_cursor_size='20px';
			$ccwp_cursor_size_cnt='20';
		}
		?>
		<?php 
			if($cursor_type_ccwp=='cursor-4')
			{
				?>
					 <div id="cursor"></div>
   					 <div id="follower"></div>
						<style>
							#cursor,
							#follower {
							position: fixed;
							border-radius: 50%;
							pointer-events: none;
							opacity: 0;
							}
							#cursor {
							background: <?php echo esc_attr($ccwp_color);?>;
							width:<?php echo esc_attr($ccwp_cursor_size);?>;
    						height:<?php echo esc_attr($ccwp_cursor_size);?>;
							z-index: 1001;
							opacity: 0;
							transform: translate(-50%, -50%);
							-o-transform: translate(-50%, -50%);
							-moz-transform: translate(-50%, -50%);
							-ms-transform: translate(-50%, -50%);
							-webkit-transform: translate(-50%, -50%);
							transition: transform 0.5s;
							-webkit-transition: -webkit-transform 0.5s;
							-ms-transition: -ms-transform 0.5s;
							-o-transition: -o-transform 0.5s;
							-moz-transition: -moz-transform 0.5s;
							}
							#follower {
							background: <?php echo esc_attr($ccwp_color);?>;
							opacity: 0.2;
							width:<?php echo esc_attr($ccwp_cursor_size_cnt)*4;?>px;
    						height:<?php echo esc_attr($ccwp_cursor_size_cnt)*4;?>px;
							border-radius: 50%;
							transform: translate(-50%, -50%);
							-o-transform: translate(-50%, -50%);
							-moz-transform: translate(-50%, -50%);
							-ms-transform: translate(-50%, -50%);
							-webkit-transform: translate(-50%, -50%);
							z-index: 1000;
							transition: transform 0.5s;
							-webkit-transition: -webkit-transform 0.5s;
							-ms-transition: -ms-transform 0.5s;
							-o-transition: -o-transform 0.5s;
							-moz-transition: -moz-transform 0.5s;
							}
							#follower.active {
							transform: translate(-50%, -50%) scale(2.4);
							-o-transform: translate(-50%, -50%) scale(2.4);
							-moz-transform: translate(-50%, -50%) scale(2.4);
							-ms-transform:translate(-50%, -50%) scale(2.4);
							-webkit-transform:translate(-50%, -50%) scale(2.4);
							background: <?php echo esc_attr($ccwp_color);?>;
							opacity: 0.2;
							}
							#cursor.hide,
							#follower.hide {
							display: none;
							}
						</style>
						<script type='text/javascript'>
							jQuery(document).ready(function(){
							const cursor=jQuery("#cursor");
							const follower=jQuery("#follower");
							const iframes = document.querySelectorAll("iframe");
							jQuery(document).on("mousemove",function(e){
								const x=e.clientX;
								const y=e.clientY;
								cursor.css({
									"opacity":"1",
									"top":y+"px",
									"left":x+"px"
								});  
								setTimeout(function(){
									follower.css({
										"opacity":"0.2",
										"top":y+"px",
										"left":x+"px"
									});
								},100);
								});
							jQuery("a").on({
								"mouseenter": function() {
									cursor.addClass("active");
									follower.addClass("active");
								},
								"mouseleave": function() {
									cursor.removeClass("active");
									follower.removeClass("active");
								}
								});
							jQuery("iframe").on({
								"mouseenter": function() {
									cursor.addClass("hide");
									follower.addClass("hide");
								},
								"mouseleave": function() {
									cursor.removeClass("hide");
									follower.removeClass("hide");
								}
								});
							});
						</script>
				<?php
			}
			else if($cursor_type_ccwp=='cursor-5')
			{
				?>
				<span class="cursord twirlbg"><i class="bg"></i></span>
			    <span class="cursord"><i class="fg"></i></span>
				<style>
					.cursord{
						position: fixed;
						display: block;
						width: 30px;
						height: 30px;
						transform: translate(-15px, -15px);
						-o-transform: translate(-15px, -15px);
						-moz-transform:translate(-15px, -15px);
						-ms-transform: translate(-15px, -15px);
						-webkit-transform: translate(-15px, -15px);
						transition: 70ms ease transform;
						-webkit-transition: 70ms ease -webkit-transform;
						-ms-transition: 70ms ease -ms-transform;
						-o-transition: 70ms ease -o-transform;
						-moz-transition: 70ms ease -moz-transform;
						pointer-events: none;
						user-select: none;
						z-index: 100;
					}
					.cursord i{
						top: 50%;
						left: 50%;
						transform: translate(-50%, -50%);
						-o-transform:translate(-50%, -50%);
						-moz-transform:translate(-50%, -50%);
						-ms-transform: translate(-50%, -50%);
						-webkit-transform: translate(-50%, -50%);
						border-radius: 100%;
						position: absolute;
						display: block;
					}
					.twirlbg{
						transition: 75ms ease;
						-webkit-transition: 75ms ease;
						-ms-transition:75ms ease;
						-o-transition: 75ms ease;
						-moz-transition:75ms ease;
					}
					.cursord i.bg{
						width:<?php echo esc_attr($ccwp_cursor_size_cnt)+15;?>px;
    					height:<?php echo esc_attr($ccwp_cursor_size_cnt)+15;?>px;
						background: #333;
						transition: 75ms ease;
						-webkit-transition: 75ms ease;
						-ms-transition:75ms ease;
						-o-transition: 75ms ease;
						-moz-transition:75ms ease;
					}
					.cursord.hover i.bg{
						background: transparent;
						width: 28px;
						height: 28px;
					}
					.cursord.click i.bg{
						background: rgba(255, 255, 255, .3);
						width: 23px;
						height: 23px;
					}
					.cursord i.fg{
						width:<?php echo esc_attr($ccwp_cursor_size);?>;
    					height:<?php echo esc_attr($ccwp_cursor_size);?>;
						background: <?php echo esc_attr($ccwp_color);?>;
						transition: 75ms ease;
						-webkit-transition: 75ms ease;
						-ms-transition:75ms ease;
						-o-transition: 75ms ease;
						-moz-transition:75ms ease;
					}					
					.cursord.hover i.fg{
						width: 15px;
						height: 15px;
					}					
					.cursord.click i.fg{
						width: 7px;
						height: 7px;
					}				
				</style>
				<script>
				jQuery(document).on("mousemove", function(event){
					var px = event.clientX;
					var py = event.clientY;
					jQuery(".cursord").css({"top": py +"px","left": px +"px"});
				  });  
				  jQuery("a").on("mouseenter", function(){
					jQuery(".cursord").addClass("hover");
				  });
				  jQuery("a").on("mouseleave", function(){
					jQuery(".cursord").removeClass("hover");
				  });
				  jQuery(document).on("mousedown", function(){
					jQuery(".cursord").addClass("click");
				  });
				  jQuery(document).on("mouseup", function(){
					jQuery(".cursord").removeClass("click");
				  });
				</script>
				<?php
			}
			else if($cursor_type_ccwp=='cursor-7')
			{
				?>
					<div id="cursor"></div>
					<style>
						#cursor {
							position: fixed;
							width: <?php echo esc_attr($ccwp_cursor_size);?>;
							height: <?php echo esc_attr($ccwp_cursor_size);?>;
							top: 50%;
							left: 50%;
							border-radius: 45% 77% 75% 45% / 45% 45% 75% 75%;
							background: <?php echo esc_attr($ccwp_color);?>;
							pointer-events: none;
							mix-blend-mode: multiply;
							-webkit-mix-blend-mode: multiply;
							-ms-blend-mode: multiply;
							-moz-mix-blend-mode: multiply;
							-o-mix-blend-mode: multiply;
							z-index: 99999999999;
							transition: transform .5s;
							-webkit-transition: -webkit-transform .5s;
							-ms-transition: -ms-transform .5s;
							-moz-transition: -moz-transform .5s;
							-o-transition: -o-transform .5s;
							animation: animateBlob 3s infinite linear;
							-webkit-animation: animateBlob 3s infinite linear;
							-ms-animation: animateBlob 3s infinite linear;
							-moz-animation: animateBlob 3s infinite linear;
							-o-animation: animateBlob 3s infinite linear;
							transform: translate(-50%, -50%);
							-o-transform: translate(-50%, -50%);
							-moz-transform: translate(-50%, -50%);
							-ms-transform: translate(-50%, -50%);
							-webkit-transform: translate(-50%, -50%);
							}
							#cursor.hovered {
							transform:translate(-50%, -50%) scale(1.5);
							-o-transform: translate(-50%, -50%) scale(1.5);
							-moz-transform: translate(-50%, -50%) scale(1.5);
							-ms-transform: translate(-50%, -50%) scale(1.5);
							-webkit-transform: translate(-50%, -50%) scale(1.5);
							animation: animateBlob 3s infinite linear;
							-webkit-animation: animateBlob 3s infinite linear;
							-ms-animation: animateBlob 3s infinite linear;
							-moz-animation: animateBlob 3s infinite linear;
							-o-animation: animateBlob 3s infinite linear;
							z-index:99999999999;
							opacity: 0.5;
							}
							@keyframes animateBlob {
							0%, 100% {
								border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
							}
							25% {
								border-radius: 72% 28% 30% 70% / 30% 28% 72% 70%;
							}
							50% {
								border-radius: 53% 47% 31% 69% / 48% 70% 30% 52%;
							}
							75% {
								border-radius: 42% 58% 68% 32% / 68% 52% 48% 32%;
							}
							}
							@-webkit-keyframes animateBlob {
							0%, 100% {
								border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
							}
							25% {
								border-radius: 72% 28% 30% 70% / 30% 28% 72% 70%;
							}
							50% {
								border-radius: 53% 47% 31% 69% / 48% 70% 30% 52%;
							}
							75% {
								border-radius: 42% 58% 68% 32% / 68% 52% 48% 32%;
							}
							}
					</style>
					<script>
						 var a_link = document.querySelectorAll("a");
							jQuery('a').on('mouseenter',function(e){
								handleMouseEnter(e);
							});
							jQuery('a').on('mouseleave',function(e){
								handleMouseLeave(e);
							});
							window.addEventListener('mousemove', handleMouseMove);
							function handleMouseMove(event) {
								var px = event.clientX;
								var py = event.clientY;
							cursor.style.top = py + 'px';
							cursor.style.left = px + 'px';
							}
							function handleMouseEnter() {
							cursor.classList.add('hovered');
							}
							function handleMouseLeave() {
							cursor.classList.remove('hovered');
							}
					</script>
				<?php
			}
			else if($cursor_type_ccwp=='cursor-8')
			{
				?>
					  <div id="cursor"></div>
					  <style>
						  #cursor {
								position: fixed;
								width:  <?php echo esc_attr($ccwp_cursor_size);?>;
								height:  <?php echo esc_attr($ccwp_cursor_size);?>;
								top: 50%;
								left: 50%;
								clip-path: polygon(20% 0, 85% 10%, 100% 55%, 70% 90%, 10% 90%, 0 40%);
								border-radius: 5px;
								background:<?php echo esc_attr($ccwp_color);?>;
								pointer-events: none;
								mix-blend-mode: multiply;
								z-index: 99999999999;
								transition: transform .5s;
								transform: translate(-50%, -50%);
								}
								#cursor.hovered {
								transform: translate(-50%, -50%) scale(1.75);
								z-index: 99999999999;
								opacity: 0.5;
								}
								#cursor.hovered.shape1 {
								clip-path: polygon(0 23%, 100% 14%, 80% 79%, 0 69%);
								}
								#cursor.hovered.shape2 {
								clip-path: polygon(12% 21%, 94% 30%, 100% 70%, 0 80%);
								}
								#cursor.hovered.shape3 {
								clip-path: polygon(0 30%, 100% 34%, 96% 79%, 6% 71%);
								}
								#cursor.hovered.shape4 {
								clip-path: polygon(11% 22%, 100% 34%, 94% 80%, 0 73%);
								}
								
					  </style>
					  <script>
						  var a_link = document.querySelectorAll("a");
						a_link.forEach(e => e.addEventListener('mouseenter', handleMouseEnter));
						a_link.forEach(e => e.addEventListener('mouseleave', handleMouseLeave));
						window.addEventListener('mousemove', handleMouseMove);
						function handleMouseMove(event) {
							var px = event.clientX;
							var py = event.clientY;
						cursor.style.top = py + 'px';
						cursor.style.left = px + 'px';
						}
						function handleMouseEnter(event) {
						var _a = this;
						var _a_width = _a.offsetWidth;
						var classes = ['shape1', 'shape2', 'shape3', 'shape4'];
						var shape_class = classes[Math.floor(Math.random() * classes.length)];
						cursor.style.width = _a_width + 'px';
						cursor.classList.add('hovered', shape_class);
						}
						function handleMouseLeave() {
						cursor.style.width = '60px';
						cursor.classList = '';
						}
					  </script>
				<?php
			}
			else if($cursor_type_ccwp=='cursor-9')
			{
				?>
					<div id="cursor">
						<span></span>
						<span></span>
						<span></span>
						<span></span>
					</div>
					<style>
						#cursor {
								position: fixed;
								width:  <?php echo esc_attr($ccwp_cursor_size);?>;
								height:  <?php echo esc_attr($ccwp_cursor_size);?>;
								top: 50%;
								left: 50%;
								border-radius: 25%;
								background: <?php echo esc_attr($ccwp_color);?>;
								pointer-events: none;
								mix-blend-mode: multiply;
								z-index: 99999999999999;
								animation: rotateShape 10s infinite linear;
								transition: width .5s, height .5s;
								}
								@keyframes rotateShape {
								0% {
									-webkit-transform: translate(-50%, -50%) rotate(0);
									-moz-transform: translate(-50%, -50%) rotate(0);
									-ms-transform: translate(-50%, -50%) rotate(0);
									-o-transform: translate(-50%, -50%) rotate(0);
									transform: translate(-50%, -50%) rotate(0);
								}
								100% {
									-webkit-transform: translate(-50%, -50%) rotate(360deg);
									-moz-transform: translate(-50%, -50%) rotate(360deg);
									-ms-transform: translate(-50%, -50%) rotate(360deg);
									-o-transform: translate(-50%, -50%) rotate(360deg);
									transform: translate(-50%, -50%) rotate(360deg);
								}
								}
								#cursor span {
								position: absolute;
								width: 5px;
								height: 5px;
								border-radius: 50%;
								background:<?php echo esc_attr($ccwp_color);?>;
								opacity: 0;
								}
								#cursor.hovered
								{
									z-index:9999999999999;
									opacity: 0.5;
								}
								#cursor.hovered span {
								animation: animateBlob 1.5s infinite;
								}
								#cursor span:first-child {
								top: -5px;
								left: -5px;
								animation-delay: .1s;
								}
								#cursor span:nth-child(2) {
								top: 20px;
								left: -15px;
								animation-delay: .2s;
								}
								#cursor span:nth-child(3) {
								top: 5px;
								left: 40px;
								animation-delay: .3s;
								}
								#cursor span:last-child {
								right: -5px;
								bottom: -5px;
								animation-delay: .4s;
								}

								@keyframes animateBlob {
								0% {
									opacity: 1;
								}
								40% {
									transform: scale(10);
									opacity: 0;
								}
								100% {
									opacity: 0;
								}
								}
					</style>
					<script>
						var a_link = document.querySelectorAll("a");
						a_link.forEach(e => e.addEventListener('mouseenter', handleMouseEnter));
						a_link.forEach(e => e.addEventListener('mouseleave', handleMouseLeave));
						window.addEventListener('mousemove', handleMouseMove);
						function handleMouseMove(event) {
							var px = event.clientX;
							var py = event.clientY;
						cursor.style.top = py + 'px';
						cursor.style.left = px + 'px';
						}
						function handleMouseEnter() {
						cursor.classList.add('hovered');
						}
						function handleMouseLeave() {
						cursor.classList.remove('hovered');
						}
					</script>
				<?php
			}
			else if(strpos($cursor_type_ccwp, 'dashicon') !== false)
			{
				?>
				<span class="cursor-icon cursor <?php echo esc_attr($cursor_type_ccwp); ?>"></span>
				<script type='text/javascript'>
								var cursor_type='<?php echo esc_attr($cursor_type_ccwp); ?>';
								var cursor = document.querySelector(".cursor-icon");
							jQuery(document).on("mousemove", function(event){
					var px = event.clientX;
					var py = event.clientY;
					cursor.style.left = (px-10) + 'px';
								cursor.style.top = (py-10) + 'px';	
				  }); 
					</script>
				<?php
			}
			else if($cursor_type_ccwp=='image_cursor')
			{
				$uploaded_cursor_img_url=get_option('cursor-image-url');
				?>
				<span class='image-cursor'></span>
				<style>
					body{
						cursor:url('<?php echo esc_attr($uploaded_cursor_img_url); ?>'), auto !important;
					}
				</style>
				<?php
			}
			else
			{
                ?>
					<span class="<?php echo esc_attr($cursor_type_ccwp); ?>"></span>
					<script type='text/javascript'>
								var cursor_type='<?php echo esc_attr($cursor_type_ccwp); ?>';
								var cursor = document.querySelector("."+cursor_type);
								jQuery(document).on("mousemove", function(event){
					var px = event.clientX;
					var py = event.clientY;
						cursor.style.left = px + 'px';
								cursor.style.top = py + 'px';	
				  }); 
					</script>
				<?php
            } ?>
<style>
.site-branding-container {
    border: 1px solid #000000;
    height: 70px;
    width: 70px;
    margin: 0 auto;
}
body{
    cursor: <?php echo esc_attr($ccwp_show_default_cursor);?>;
}
.cursor-1 {
    width:<?php echo esc_attr($ccwp_cursor_size);?>;
    height:<?php echo esc_attr($ccwp_cursor_size);?>;
    border: 1px solid <?php echo esc_attr($ccwp_color);?>;
    border-radius: 50%;
    position: fixed;
    transition-duration: 200ms;
    transition-timing-function: ease-out;
    animation: animate1 .5s infinite alternate;
    pointer-events: none;
	overflow: hidden;
	z-index:9999999;
}
.cursor-6 {
	width:<?php echo esc_attr($ccwp_cursor_size);?>;
    height:<?php echo esc_attr($ccwp_cursor_size);?>;
    border: 3px solid <?php echo esc_attr($ccwp_color);?>;
    position: fixed;
	z-index: 99999999;
    transition-duration: 200ms;
    -webkit-transition-duration: 200ms;
    transition-timing-function: ease-out;
    -webkit-transition-timing-function: ease-out;
    animation: animate_cursor6 .5s infinite alternate;
    -webkit-animation: animate_cursor6 .5s infinite alternate;
    pointer-events: none;
    overflow: hidden;
    border-left: 0;
    border-top: 0;
}

.cursor-icon {
    width:<?php echo esc_attr($ccwp_cursor_size);?>; 
    height:<?php echo esc_attr($ccwp_cursor_size);?>;
    position: fixed;
	color:<?php echo esc_attr($ccwp_color);?>;
	font-size:<?php echo esc_attr($ccwp_cursor_size);?>;
    pointer-events: none;
	z-index: 99999999;
	font-family: dashicons;
}
.cursor-1::after,.cursor-2::after,.cursor-3::after{
    content: "";
	width:<?php echo esc_attr($ccwp_cursor_size);?>;
    height:<?php echo esc_attr($ccwp_cursor_size);?>;
    position: fixed;
    border: 8px solid <?php echo esc_attr($ccwp_color);?>;
    border-radius: 50%;
    opacity: .5;
    top:-1px;
    left:-1px;
    animation: animate2 .5s infinite alternate;
}
.cursor-6::after {
    content: "";
    width: 0;
    height: 0;
    position: fixed;
    opacity: 0.5;
    top: 0px;
    animation: animate_cursor6_after .5s infinite alternate;
    -webkit-animation: animate_cursor6_after .5s infinite alternate;
    border-left: <?php echo esc_attr($ccwp_cursor_size);?> solid transparent;
    border-bottom: <?php echo esc_attr($ccwp_cursor_size);?> solid <?php echo esc_attr($ccwp_color);?>;
}

.cursor-2 {
    width:<?php echo esc_attr($ccwp_cursor_size);?>;
    height:<?php echo esc_attr($ccwp_cursor_size);?>;
    border: 1px solid <?php echo esc_attr($ccwp_color);?>;
    border-radius: 25%;
    position: fixed;
    transition-duration: 200ms;
    transition-timing-function: ease-out;
    animation: animate1 .5s infinite alternate;
    pointer-events: none;
	overflow: hidden;
	z-index: 99999999;
}

.cursor-3 {
	border-color: transparent transparent <?php echo esc_attr($ccwp_color);?> transparent;
    border-style: solid;
    border-width: 0px <?php echo esc_attr($ccwp_cursor_size);?> <?php echo esc_attr($ccwp_cursor_size);?> <?php echo esc_attr($ccwp_cursor_size);?>;
    height: 0px;
    width: 0px;
	position: fixed;
	transition-duration: 200ms;
    transition-timing-function: ease-out;
    animation: animate1 .5s infinite alternate;
    pointer-events: none;
	overflow: hidden;
	z-index: 99999999;
}
@keyframes animate1{
	from{
	transform:translate(-50%, -50%) scale(1);
	}
	to{
	transform: translate(-50%, -50%) scale(0.7);
	}
	}

	@keyframes animate_cursor6{
	from{
		transform:translate(-50%, -50%) scale(1) rotate(225deg);
		-o-transform: translate(-50%, -50%) scale(1) rotate(225deg);
		-moz-transform: translate(-50%, -50%) scale(1) rotate(225deg);
		-ms-transform:translate(-50%, -50%) scale(1) rotate(225deg);
		-webkit-transform: translate(-50%, -50%) scale(1) rotate(225deg);
	}
	to{
	transform: translate(-50%, -50%) scale(0.7) rotate(225deg);
	-o-transform: translate(-50%, -50%) scale(0.7) rotate(225deg);
		-moz-transform: translate(-50%, -50%) scale(0.7) rotate(225deg);
		-ms-transform:translate(-50%, -50%) scale(0.7) rotate(225deg);
		-webkit-transform: translate(-50%, -50%) scale(0.7) rotate(225deg);
	
	}
	}
	@-webkit-keyframes animate_cursor6{
	from{
		transform:translate(-50%, -50%) scale(1) rotate(225deg);
		-o-transform: translate(-50%, -50%) scale(1) rotate(225deg);
		-moz-transform: translate(-50%, -50%) scale(1) rotate(225deg);
		-ms-transform:translate(-50%, -50%) scale(1) rotate(225deg);
		-webkit-transform: translate(-50%, -50%) scale(1) rotate(225deg);
	}
	to{
	transform: translate(-50%, -50%) scale(0.7) rotate(225deg);
	-o-transform: translate(-50%, -50%) scale(0.7) rotate(225deg);
		-moz-transform: translate(-50%, -50%) scale(0.7) rotate(225deg);
		-ms-transform:translate(-50%, -50%) scale(0.7) rotate(225deg);
		-webkit-transform: translate(-50%, -50%) scale(0.7) rotate(225deg);
	
	}
	}
	@keyframes animate_cursor6_after{
	from{
	transform: translate(-50%, -50%) scale(1) ;
	-o-transform: translate(-50%, -50%) scale(1) ;
		-moz-transform: translate(-50%, -50%) scale(1) ;
		-ms-transform:translate(-50%, -50%) scale(1) ;
		-webkit-transform: translate(-50%, -50%) scale(1) ;
	}
	to{
	transform: translate(-50%, -50%) scale(0.7);
	-o-transform:translate(-50%, -50%) scale(0.7);
		-moz-transform:translate(-50%, -50%) scale(0.7);
		-ms-transform:translate(-50%, -50%) scale(0.7);
		-webkit-transform: translate(-50%, -50%) scale(0.7);
	}
	}
	@-webkit-keyframes animate_cursor6_after{
	from{
	transform: translate(-50%, -50%) scale(1) ;
	-o-transform: translate(-50%, -50%) scale(1) ;
		-moz-transform: translate(-50%, -50%) scale(1) ;
		-ms-transform:translate(-50%, -50%) scale(1) ;
		-webkit-transform: translate(-50%, -50%) scale(1) ;
	}
	to{
	transform: translate(-50%, -50%) scale(0.7);
	-o-transform:translate(-50%, -50%) scale(0.7);
		-moz-transform:translate(-50%, -50%) scale(0.7);
		-ms-transform:translate(-50%, -50%) scale(0.7);
		-webkit-transform: translate(-50%, -50%) scale(0.7);
	}
	}

@keyframes animate2{
    from{
    transform:scale(1);
    }
    to{
    transform: scale(0.4);
    }
    }
	@-webkit-keyframes animate2{
    from{
    transform:scale(1);
    }
    to{
    transform: scale(0.4);
    }
    }
</style>
		<?php
	}
}

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'ccwp_setting_action_link' );
function ccwp_setting_action_link ( $links ) {
	 $settings_link = array('<a href="' . admin_url( 'admin.php?page=custom-cursor-for-wp/custom-cursor-for-wp.php' ) . '">Settings</a>');
	return array_merge( $links, $settings_link );
}
?>