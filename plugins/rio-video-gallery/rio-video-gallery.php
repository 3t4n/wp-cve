<?php
/**
* Plugin Name: Rio Video Gallery
* Plugin URI: https://wordpress.org/plugins/rio-video-gallery/
* Description: A powerful video gallery management plugin that allows you to embed videos from YouTube, Vimeo & Dailymotion.
* Version: 2.3.6
* Author: Riosis Private Limited
* Author URI: http://www.riosis.com
*/
function rvg_footer_scripts() {
	wp_enqueue_script('jquery');
	wp_enqueue_script( 'video-gallery-script', plugin_dir_url( __FILE__ ) . 'js/video-gallery-script.js' );
}
add_action('wp_footer', 'rvg_footer_scripts');

function getVimeoInfo_details($id, $info = 'thumbnail_medium')
{
	if (!function_exists('curl_init')) die('CURL is not installed!');
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "vimeo.com/api/v2/video/$id.php");
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	$output = unserialize(curl_exec($ch));
	$output = $output[0][$info];
	curl_close($ch);
	return $output;
}
/*
|--------------------------------------------------------------------------
| Custom post type  Video Gallery
|--------------------------------------------------------------------------
*/
function codex_custom_video_gallery()
{
	$labels = array(
		'name' => 'Video Gallery',
		'singular_name' => 'Video Gallery',
		'add_new' => 'Add New',
		'add_new_item' => 'Add New',
		'edit_item' => 'Edit Video',
		'new_item' => 'New Video',
		'all_items' => 'All Videos',
		'view_item' => 'View Video',
		'search_items' => 'Search Video',
		'not_found' => 'No Videos found',
		'not_found_in_trash' => 'No Videos found in Trash',
		'parent_item_colon' => '',
		'menu_name' => 'Video Gallery'
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		'query_var' => true,
		'rewrite' => array(
			'slug' => 'video-gallery'
		) ,
		'capability_type' => 'post',
		'has_archive' => true,
		'hierarchical' => false,
		'menu_position' => null,
		'menu_icon' => 'dashicons-video-alt3',
		'supports' => array(
			'title',
			'editor',
			'thumbnail',
			'comments'
		)
	);
	register_post_type('video-gallery', $args);
	register_taxonomy("video-categories", array(
		"video-gallery"
	) , array(
		"hierarchical" => true,
		'show_admin_column' => true,
		"label" => "Video Categories",
		"singular_label" => "Video Categories",
		"rewrite" => array(
			'slug' => 'videos'
		)
	));
}
add_action('init', 'codex_custom_video_gallery');
// For adding metabox to video post...
add_action('admin_init', 'fun_add_video_metaBox');
function fun_add_video_metaBox()
{
	add_meta_box('video_gallery_metabox', 'Video Gallery Options', 'fun_video_gallery_metabox_display', 'video-gallery', 'normal', 'high');
}
function fun_video_gallery_metabox_display($video_gallery)
{
	$post_order_res = get_post_meta($video_gallery->ID, 'video_post_order', true);
	$provider_res = get_post_meta($video_gallery->ID, 'video_provider', true);
	$video_id_res = esc_html(get_post_meta($video_gallery->ID, 'video_id', true));
	$video_post_id = $video_gallery->ID;
	?>
	<div class="inside">
		<table width="100%">
			<tr>
				<td width="178" align="right"><b>Post Order</b></td>
				<td width="4">:</td>
				<td><input size="10" value="<?php
				if (!empty($post_order_res)) {
					echo $post_order_res;
				}
				else {
					echo 0;
				}
			?>" name="video_post_order" type="text" class="widther" /></td>
			<td rowspan="4" width="230px" align="right"><?php
			if (!empty($provider_res) && $provider_res == 'youtube') {
				?>
				<iframe width="200" height="120" src="//www.youtube.com/embed/<?php echo $video_id_res; ?>?controls=0&showinfo=0" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
				<?php
			}
			else if (!empty($provider_res) && $provider_res == 'vimeo') {
				?>
				<iframe src="//player.vimeo.com/video/<?php echo $video_id_res; ?>" width="200" height="120" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
				<?php
			}
			else if (!empty($provider_res) && $provider_res == 'dailymotion') {
				?>
				<iframe frameborder="0" width="200" height="120" src="https://www.dailymotion.com/embed/video/<?php echo $video_id_res; ?>" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
				<?php
			}
			?>
		</td>
	</tr>
	<tr>
		<td align="right"><b>Select your video provider</b></td>
		<td>:</td>
		<td><select name="video_provider" id="video_provider">
			<option <?php
			if (!empty($provider_res) && $provider_res == '') {
				echo 'selected="selected"';
			}
		?> value="">-Select-</option>
		<option <?php
		if (!empty($provider_res) && $provider_res == 'youtube') {
			echo 'selected="selected"';
		}
	?> value="youtube">YouTube</option>
	<option <?php
	if (!empty($provider_res) && $provider_res == 'vimeo') {
		echo 'selected="selected"';
	}
?> value="vimeo">Vimeo</option>
<option <?php
if (!empty($provider_res) && $provider_res == 'dailymotion') {
	echo 'selected="selected"';
}
?> value="dailymotion">Dailymotion</option>
</select></td>
</tr>
<tr>
	<td align="right"><b>Video ID</b>&nbsp;<small>(refer help)</small>
		<?php
		/*?><a href="javascript:void(0);" title="Give video id from your provider URL" class="tooltip" > <img src="<?php echo plugins_url();?>/rio-video-gallery/img/help.png" /> </a><?php */
	?></td>
	<td>:</td>
	<td><input size="40" value="<?php
	if (!empty($video_id_res)) {
		echo $video_id_res;
	}
?>" name="video_id" type="text" class="widther" /></td>
</tr>
<?php
if (!empty($video_id_res) && !empty($provider_res)) {
	?>
	<?php
}
?>
<tr>
	<td align="right"><b>Short code</b></td>
	<td>:</td>
	<td colspan="2"><input size="30" type="text" onfocus="this.select();" readonly value='[videopost id="<?php
	echo $video_post_id;
?>"]'></td>
</tr>
</table>
</div>
<?php
}
// for inserting values into postmeta table..
add_action('save_post', 'save_video_meta_values', 10, 2);
function save_video_meta_values($video_postId, $video_gallery)
{
	if ($video_gallery->post_type == 'video-gallery') {
		if ($_POST) {
			if (empty($_POST['video_post_order'])) {
				$postOrder = 0;
			}
			else {
				$postOrder = $_POST['video_post_order'];
			}
			update_post_meta($video_postId, 'video_post_order', $postOrder);
			update_post_meta($video_postId, 'video_provider', $_POST['video_provider']);
			update_post_meta($video_postId, 'video_id', $_POST['video_id']);
		}
	}
}
function reg_video_set_submenu()
{
	// for registering submenu settings under video gallery post type..
	add_submenu_page('edit.php?post_type=video-gallery', 'Settings', /*page title*/
		'Settings', /*menu title*/
		'manage_options', /*roles and capabiliyt needed*/
	'video_gallery_settings', 'video_gallery_settings_fun' /*replace with your own function*/);
}
add_action('admin_menu', 'reg_video_set_submenu');
function video_gallery_settings_fun()
{
	// var_dump($_POST);
	if ($_POST) {
		/*..Video post starts from here...*/
		if (!empty($_POST['hid_video_post'])) {
			if(!empty($_POST['vposted_date_display'])) {
				$vposted_date_display = $_POST['vposted_date_display'];
			}else {
				$vposted_date_display = '';
			}
			if(!empty($_POST['vpost_order'])) {
				$vpost_order = $_POST['vpost_order'];
			}else {
				$vpost_order = '';
			}
			if(!empty($_POST['vrelated_posts'])) {
				$vrelated_posts = $_POST['vrelated_posts'];
			}else {
				$vrelated_posts = '';
			}
			if(!empty($_POST['vpost_category'])) {
				$vpost_category_res = $_POST['vpost_category'];
			}else {
				$vpost_category_res = '';
			}
			if(!empty($_POST['video_provider'])) {
				$video_provider_res = $_POST['video_provider'];
			}else {
				$video_provider_res = '';
			}
			$vpost_orderby = $_POST['vpost_orderby'];
			$video_count = stripslashes($_POST['video_count']);
			$video_thumb_height = stripslashes($_POST['video_thumb_height']);
			$video_thumb_width = stripslashes($_POST['video_thumb_width']);
			$video_sthumb_height = stripslashes($_POST['video_sthumb_height']);
			$video_sthumb_width = stripslashes($_POST['video_sthumb_width']);
			$video_layout = $_POST['video_layout'];
			$video_link_target = stripslashes($_POST['video_link_target']);
			$data = array(
				'vposted_date_display' => $vposted_date_display,
				'vpost_order' => $vpost_order,
				'vpost_category' => $vpost_category_res,
				'video_provider' => $video_provider_res,
				'vrelated_posts' => $vrelated_posts,
				'vpost_orderby' => $vpost_orderby,
				'video_layout' => $video_layout,
				'video_thumb_width' => $video_thumb_width,
				'video_thumb_height' => $video_thumb_height,
				'video_count' => $video_count,
				'video_sthumb_width' => $video_sthumb_width,
				'video_sthumb_height' => $video_sthumb_height,
				'video_link_target' => $video_link_target
			);
			// update video gallery option
			$updated_vgallery = update_option('video_gallery_settings', $data);
		}
		/*..Video post ends here...*/
	}
	$data_results = get_option('video_gallery_settings'); //get video gallery all settings
	$vposted_date_display_res = $data_results['vposted_date_display'];
	$vpost_order_res = $data_results['vpost_order'];
	$vpost_category_res = $data_results['vpost_category'];
	$video_provider_res = $data_results['video_provider'];
	$vpost_orderby_res = $data_results['vpost_orderby'];
	$video_layout_res = $data_results['video_layout'];
	$video_thumb_width_res = $data_results['video_thumb_width'];
	$video_thumb_height_res = $data_results['video_thumb_height'];
	$video_sthumb_width_res = $data_results['video_sthumb_width'];
	if (empty($video_sthumb_width_res)) {
		$video_sthumb_width_res = 600;
	}
	$video_sthumb_height_res = $data_results['video_sthumb_height'];
	if (empty($video_sthumb_height_res)) {
		$video_sthumb_height_res = 400;
	}
	$video_count_res = $data_results['video_count'];
	$video_link_target = $data_results['video_link_target'];
	?>
	<div class="wrap">
		<h2>Video Settings</h2>
		<br/>
		<?php if (!empty($updated_vgallery)) { ?>
			<div class="updated below-h2" id="message">
				<p>Options updated.</p>
			</div>
		<?php } ?>
		<!-- Basic Settings post box starts here..-->
		<div class="postbox">
			<div class="inside">
				<table>
					<tr>
						<th align="left">Basic Settings</th>
					</tr>
				</table>
				<form action="" method="post" name="frm_vid_gallery_settings">
					<table>
						<tr>
							<td align="right" width="222px">Number of videos</td>
							<td>:</td>
							<td><input size="8px" type="text" name="video_count" id="video_count" value="<?php if (!empty($video_count_res)) { echo $video_count_res; } else { echo '6'; } ?>"></td>
							<td class="desc">&nbsp;&nbsp;Number of videos display (Default display is 6).</td>
						</tr>
					</table>
					<table>
						<tr>
							<td align="right">Show total views &amp; posted date </td>
							<td>:</td>
							<td><input type="checkbox" value="1" id="vposted_date_display" name="vposted_date_display" <?php if (!empty($vposted_date_display_res)) { echo 'checked="checked"'; } ?>></td>
							<td class="desc">&nbsp;&nbsp;If enabled, total views and published date will display.</td>
						</tr>
						<tr>
							<td align="right">Display 'Related posts' in single page</td>
							<td>:</td>
							<td><input type="checkbox" value="1" id="vrelated_posts" name="vrelated_posts" <?php if (!empty($vrelated_posts)) { echo 'checked="checked"'; } ?>></td>
							<td class="desc">&nbsp;&nbsp;If enabled, Will show related posts in single page.</td>
						</tr>
						<tr>
							<td align="right">Enable video category</td>
							<td>:</td>
							<td><input type="checkbox" value="1" id="vpost_category" name="vpost_category" <?php if (!empty($vpost_category_res)) { echo 'checked="checked"'; } ?>></td>
							<td class="desc">&nbsp;&nbsp;If enabled, show the name of video category.</td>
						</tr>
						<tr>
							<td align="right">Enable video provider</td>
							<td>:</td>
							<td><input type="checkbox" value="1" id="video_provider" name="video_provider" <?php if (!empty($video_provider_res )) { echo 'checked="checked"'; } ?>></td>
							<td class="desc">&nbsp;&nbsp;If enabled, show the video provider.</td>
						</tr>
						<tr>
							<td align="right">Enable post order</td>
							<td>:</td>
							<td><input type="checkbox" value="1" id="vpost_order" name="vpost_order" <?php if (!empty($vpost_order_res)) { echo 'checked="checked"'; } ?>></td>
							<td class="desc">&nbsp;&nbsp;If enabled, videos will displays with post order.</td>
						</tr>
					</table>
					<table id="tbl_vpostorder_settings">
						<tr>
							<td width="237px">&nbsp;</td>
							<td><input id="asc_order" type="radio" <?php if (!empty($vpost_orderby_res) && $vpost_orderby_res == 'asc') { echo 'checked="checked"'; } 	elseif (empty($vpost_orderby_res)) { echo 'checked="checked"'; } ?> value="asc" name="vpost_orderby" >&nbsp;<label for="asc_order">Ascending</label></td>
							<td><input id="desc_order" type="radio" <?php
							if (!empty($vpost_orderby_res) && $vpost_orderby_res == 'desc') { echo 'checked="checked"'; } ?> value="desc" name="vpost_orderby">&nbsp;<label for="desc_order">Descending</label></td>
							<td class="desc">&nbsp;Videos will displays ascending or descending order.</td>
						</tr>
					</table>
				</div>
			</div>
			<!-- Basic Settings post box ends here..-->
			<!-- Layout post box starts here..-->
			<div class="postbox">
				<div class="inside">
					<table>
						<tr>
							<th align="left">Gallery Layout</th>
						</tr>
						<tr>
							<td align="right" width="152px">Choose Layout</td>
							<td>:</td>
							<td><input id="layout1" type="radio" <?php if (!empty($video_layout_res) && $video_layout_res == '1') { echo 'checked="checked"'; }  elseif (empty($video_layout_res)) { echo 'checked="checked"'; } ?> value="1" name="video_layout">
								<label for="layout1">
									<img src="<?php echo plugins_url(); ?>/rio-video-gallery/img/video_layout_1.png" alt="layout_1" style="vertical-align:middle;"></label></td>
									<td width="50px"></td>
									<td><input id="layout2" type="radio" <?php if (!empty($video_layout_res) && $video_layout_res == '2') { echo 'checked="checked"'; } ?> value="2" name="video_layout">
										<label for="layout2">
											<img src="<?php echo plugins_url(); ?>/rio-video-gallery/img/video_layout_2.png" alt="layout_2" style="vertical-align:middle;"></label></td>
											<td class="desc">&nbsp;Choose layout for your video gallery.</td>
										</tr>
									</table>
									<table>
										<tr>
											<td align="right">Video image thumbnail size</td>
											<td>:</td>
											<td>Width : &nbsp;
												<input size="8px" type="text" name="video_thumb_width" id="video_thumb_width" value="<?php if (!empty($video_thumb_width_res)) { echo $video_thumb_width_res; } else { echo 230; } ?>"></td>
												<td>Height : &nbsp;
													<input size="8px" type="text" id="video_thumb_height" name="video_thumb_height" value="<?php if (!empty($video_thumb_height_res)) { echo $video_thumb_height_res; } else { echo 160; } ?>"></td>
													<td class="desc">&nbsp;The image width and height in pixels.</td>
												</tr>
												<tr>
													<td align="right">Video player size</td>
													<td>:</td>
													<td>Width: &nbsp;<input size="8px" type="text" name="video_sthumb_width" id="video_sthumb_width" value="<?php if (!empty($video_sthumb_width_res)) { echo $video_sthumb_width_res; } ?>"></td>
													<td>Height: &nbsp;<input size="8px" type="text" id="video_sthumb_height" name="video_sthumb_height" value="<?php if (!empty($video_sthumb_height_res)) { echo $video_sthumb_height_res; } ?>"></td>
													<td class="desc">&nbsp;Player width and height in pixels, displays in single page.</td>
												</tr>
											</table>
										</div>
									</div>
									<!-- Layout post box ends here..-->
									<div class="postbox">
										<div class="inside">
											<table>
												<tr>
													<th align="left">Open Video links in</th>
												</tr>
												<tr>
													<td ><input id="popup_view" type="radio" <?php if(!empty($video_link_target) && $video_link_target == 'popup') { echo 'checked="checked"';} elseif(empty($video_link_target)) {echo 'checked="checked"';}?> value="popup" name="video_link_target" >
														<label for="popup_view">Popup View</label></td> 
														<td><input id="single_page"fv type="radio" <?php if(!empty($video_link_target) && $video_link_target == 'tab') { echo 'checked="checked"';}?> value="tab" name="video_link_target">
															<label for="single_page">Single Page</label></td>
															<td class="desc">(JQuery library is required. Add it to your theme from <a href="https://jquery.com/download/" target="_blank">here</a>)</td>
														</tr>
													</table>
												</div>
											</div>
											<!-- Short code post box starts here..-->
											<div class="postbox">
												<div class="inside">
													<?php
													$args = array(
														'orderby' => 'name',
														'order' => 'ASC',
														'hide_empty' => 1,
														'taxonomy' => 'video-categories',
														'pad_counts' => true
													);
											// returns all category under custom taxonomy 'video-categories'..
													$videocategories = get_categories($args);
													?>
													<table>
														<tr>
															<th align="left">Short codes</th>
														</tr>
														<tr>
															<td align="right">Select video category : &nbsp;
																<select name="sel_shortcode" id="sel_shortcode" >
																	<option value="all">All</option>
																	<?php
																	foreach($videocategories as $vcategory) {
																		$category_name = $vcategory->name;
																		$category_parent = $vcategory->parent;
																		$category_slug = $vcategory->slug;
																		if (empty($category_parent)) { ?>
																			<option value="<?php echo $category_slug; ?>"><?php echo $category_name; ?></option>
																<?php } // if category_parent checking close.
															} //foreach close..
															?>
														</select></td>
														<td><input size="50" type="text" onfocus="this.select();" id="shortcodeDisplay" readonly value='[videogallery view="all"]'></td>
														<td class="desc">&nbsp;&nbsp;Use this shortcode in your page.</td>
													</tr>
													<?php /*?><tr>
													<td align="right">Copy video list  category short code : &nbsp;</td>
													<td><input size="50" type="text" onfocus="this.select();" id="shortcodeDisplay_category" readonly value='[videocategory view="all"]'></td>
													<td class="desc">&nbsp;&nbsp;Use this shortcode for sidebar page.</td>
													</tr><?php */?>
												</table>
											</div>
										</div>
										<!-- Short code post box ends here..-->
										<table>
											<tr>
												<td class="spaciuosCells"><input type="hidden" name="hid_video_post" value="true">
													<input name="video_submit"  type="submit" value="Update options" class="widther button-primary" /></td>
												</tr>
											</table>
										</form>
									</div>
									<?php // for enable scripts.
									wp_register_script('video-custom-script', plugins_url() . '/rio-video-gallery/js/video-gallery-script.js', array(
										'jquery',
										'jquery-ui-core',
										'jquery-ui-tabs'
									) , '1.0.0', true);
									wp_enqueue_script('video-custom-script');
									// for enabling style.
									$css_path = plugins_url() . '/rio-video-gallery/css/video-gallery-style.css';
									wp_register_style('video-custom-style', $css_path);
									wp_enqueue_style('video-custom-style');
								}
								/*
								|--------------------------------------------------------------------------
								| short code function
								|--------------------------------------------------------------------------
								*/
								function fun_video_gallery_shortcode($atts)
								{
									$short_content_output='';
									extract(shortcode_atts(array(
										'view' => ''
									) , $atts));
									$view_status = $view; // returns short code attribute id..
									$data_results = get_option('video_gallery_settings'); //video gallery settings option
									$vposted_date_display_gshort = $data_results['vposted_date_display'];
									$vpost_order_gshort = $data_results['vpost_order'];
									$vpost_orderby_gshort = $data_results['vpost_orderby'];
									$video_layout_gshort = $data_results['video_layout'];
									$video_thumb_width_gshort = $data_results['video_thumb_width'];
									$video_link_target = $data_results['video_link_target'];
									$video_sthumb_width_res = $data_results['video_sthumb_width'];
									$video_sthumb_height_res = $data_results['video_sthumb_height'];
									$video_provider_result = $data_results['video_provider'];
									$vpost_category_result = $data_results['vpost_category'];
									if (empty($video_sthumb_width_res)) {
										$video_sthumb_width_res = 600;
									}
									$video_sthumb_height_res = $data_results['video_sthumb_height'];
									if (empty($video_sthumb_height_res)) {
										$video_sthumb_height_res = 400;
									}
									if (empty($video_thumb_width_gshort)) {
										$video_thumb_width_gshort = 230;
									}
									$video_thumb_height_gshort = $data_results['video_thumb_height'];
									if (empty($video_thumb_height_gshort)) {
										$video_thumb_height_gshort = 160;
									}
									$video_count_gshort = $data_results['video_count'];
									if (empty($video_count_gshort)) {
										$video_count_gshort = 6;
									}
									$short_code_output="<div class='rio-video-gallery-container-shortcode'>";
									$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
									if (!empty($view_status) && $view_status == 'all') // all categories under video gallery post type..
									{
										$video_link_target = $data_results['video_link_target'];
										if(empty($video_link_target))
										{
											$video_link_target='popup';
										}
										if (!empty($vpost_order_gshort)) //with post order
										{
											$exclude_terms = array(
												'post_type' => 'video-gallery',
												'posts_per_page' => $video_count_gshort,
												'meta_key' => 'video_post_order',
												'orderby' => 'meta_value_num',
												'paged' => $paged,
												'order' => $vpost_orderby_gshort
											);
										}
										else //without post order
										{
											$exclude_terms = array(
												'post_type' => 'video-gallery',
												'paged' => $paged,
												'posts_per_page' => $video_count_gshort
											);
										}
									}
									else //filtered category only under video-categories..
									{
										if (!empty($vpost_order_gshort)) //with post order
										{
											$exclude_terms = array(
												'post_type' => 'video-gallery',
												'posts_per_page' => $video_count_gshort,
												'paged' => $paged,
												'meta_key' => 'video_post_order',
												'orderby' => 'meta_value_num',
												'order' => $vpost_orderby_gshort,
												'tax_query' => array(
													array(
														'taxonomy' => 'video-categories',
														'field' => 'slug',
														'terms' => array(
															$view_status
														)
													)
												)
											);
										}
										else //without post order
										{
											$exclude_terms = array(
												'post_type' => 'video-gallery',
												'posts_per_page' => $video_count_gshort,
												'paged' => $paged,
												'tax_query' => array(
													array(
														'taxonomy' => 'video-categories',
														'field' => 'slug',
														'terms' => array(
															$view_status
														)
													)
												)
											);
										}
									}
									$the_query = new WP_Query($exclude_terms);
									if ($the_query->have_posts()):
										while ($the_query->have_posts()):
											$the_query->the_post();
											// if(have_posts()):while(have_posts()):the_post();
											$postid = get_the_ID();
											$provider_shortres = get_post_meta($postid, 'video_provider', true); // returns video provider from post..
											if($provider_shortres == 'dailymotion') { $provider_shortres_letter = 'D'; }
											else if($provider_shortres == 'youtube') { $provider_shortres_letter = 'Y'; }
											else if($provider_shortres == 'vimeo') { $provider_shortres_letter = 'V'; }
											$video_id_shortres = get_post_meta($postid, 'video_id', true); //returns curresponding video id..
											if (!empty($video_id_shortres)) {



												if (!empty($video_layout_gshort) && $video_layout_gshort == 1) {
													$short_code_output.= "<article class='video-item' style='width:".$video_thumb_width_gshort."px;'>
													<figure>";if(!empty($video_provider_result)&& $video_provider_result='1'){ $short_code_output.="<span class='".$provider_shortres."'>".$provider_shortres_letter."</span>";  } $short_code_output.=" <a ";
													if($video_link_target == 'popup'){
														$short_code_output.="id='".$postid."' href='javascript:void(0);'";} else {  $short_code_output.="href='".get_the_permalink()."'";
													}
													$short_code_output.="data-width='".$video_sthumb_width_res."' data-height='".$video_sthumb_height_res."'>&nbsp;</a>";
													if (!empty($provider_shortres) && $provider_shortres == 'youtube') {
														$short_code_output.="<img src='http://img.youtube.com/vi/".$video_id_shortres."/0.jpg' alt='".get_the_title()."' title='".get_the_title()."' style='height:'". $video_thumb_height_gshort."'; width:'".$video_thumb_width_gshort."';' width='".$video_thumb_width_gshort."' height='".$video_thumb_height_gshort."'>";
													}
													else if (!empty($provider_shortres) && $provider_shortres == 'vimeo') {
														$imgid = $video_id_shortres;
														$thumb = getVimeoInfo_details($imgid);
														if (empty($thumb)) {
															$thumb = plugins_url() . '/rio-video-gallery/img/video-failed.png';
														}
														$short_code_output.="<img src='".$thumb."' alt='".get_the_title()."' title='".get_the_title()."'
														style='height:'". $video_thumb_height_gshort."'; width:'".$video_thumb_width_gshort."';'
														width='".$video_thumb_width_gshort."' height='". $video_thumb_height_gshort."'>";
													} else if (!empty($provider_shortres) && $provider_shortres == 'dailymotion') {
														$short_code_output.="<img src='http://www.dailymotion.com/thumbnail/video/".$video_id_shortres."' alt='".get_the_title()."' title='".get_the_title()."'
														style='height:'". $video_thumb_height_gshort."'; width:'".$video_thumb_width_gshort."';' width='".$video_thumb_width_gshort."' height='".$video_thumb_height_gshort."'>";
													}
													$short_code_output.="</figure>";
														//Show cagegory name;
													$category=get_the_term_list(get_the_ID(),'video-categories','',', ','' );
														// $category=get_the_term_list($post->ID,'video-categories','',', ','' );
													$category=strip_tags($category);
													// if(!empty($vpost_category_result)&& $vpost_category_result='1'){
													// 	$short_code_output.="<span class='category_name'>".$category."</span>";
													// };
													$short_code_output.="<header>";
													// if (!empty($vposted_date_display_gshort)) {
													// 	$short_code_output.="<p><span>".human_time_diff(get_the_time('U') , current_time('timestamp')) . ' ago';
													// 	$short_code_output.="</span><span>".rio_video_getPostViews($postid)." Views</span></p>";
													// }
													$short_code_output.="</header>
													</article>";
													$short_code_output.="<div class='poup_window' id='show_content".$postid."'>
													<div class='popup-box'>";
													if(!empty($provider_shortres) && $provider_shortres == 'youtube') {
														$short_code_output.= "<iframe width='".$video_sthumb_width_res."' height='".$video_sthumb_height_res."' src='//www.youtube.com/embed/".$video_id_shortres."' frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
													} else if(!empty($provider_shortres) && $provider_shortres == 'vimeo') {
														$short_code_output.="<iframe src='//player.vimeo.com/video/".$video_id_shortres."' width='".$video_sthumb_width_res."' height='". $video_sthumb_height_res."' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
													} else if(!empty($provider_shortres) && $provider_shortres == 'dailymotion') {
														$short_code_output.="<iframe frameborder='0' width='". $video_sthumb_width_res."' height='".$video_sthumb_height_res."' src='https://www.dailymotion.com/embed/video/".$video_id_shortres."' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
													}
													$short_code_output.="<h4>".wp_trim_words(get_the_title(),10,'...')."</h4><p>";
													if(!empty($vpost_category_result)&& $vpost_category_result='1'){
														$short_code_output.="<span class='category_name'>".$category."</span>&nbsp;";
													};
													if (!empty($vposted_date_display_gshort)) {
														$short_code_output.="<span>".rio_video_getPostViews($postid)." Views</span> <span>".human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago';"</span>";
													}
													
													$short_code_output.="</p>
													<a class='close_this'>x</a>
													</div>
													</div>";
												} //layout condition 1 close here... ?>

												<?php if (!empty($video_layout_gshort) && $video_layout_gshort == 2) {
													$short_code_output.="<article class='video-item' style='width:".$video_thumb_width_gshort."px;'><figure>";
													if(!empty($video_provider_result)&& $video_provider_result='1'){ $short_code_output.="<span class='".$provider_shortres."'>".$provider_shortres_letter."</span>";  }	$short_code_output.=" <a ";
													if($video_link_target == 'popup'){ $short_code_output.= "id='".$postid."'";
													$short_code_output.=" href='javascript:void(0);'";
												} else { $short_code_output.=" href='".get_the_permalink()."'";
											}
											$short_code_output.="title='".get_the_title()."' data-width='".$video_sthumb_width_res."' data-height='".$video_sthumb_height_res."'>&nbsp;</a>";
											if (!empty($provider_shortres) && $provider_shortres == 'youtube') {
												$short_code_output.="<img src='http://img.youtube.com/vi/".$video_id_shortres."/0.jpg' alt='".get_the_title()."' title='".get_the_title()."' style='height:'". $video_thumb_height_gshort."'; width:'".$video_thumb_width_gshort."';' width='".$video_thumb_width_gshort."' height='".$video_thumb_height_gshort."'>";

											} else if (!empty($provider_shortres) && $provider_shortres == 'vimeo') {
												$imgid = $video_id_shortres;
												$thumb = getVimeoInfo_details($imgid);
												if (empty($thumb)) {
													$thumb = plugins_url() . '/rio-video-gallery/img/video-failed.png';
												}
												$short_code_output.="<img src='".$thumb."' alt='".get_the_title()."' title='".get_the_title()."' style='height:'". $video_thumb_height_gshort."'; width:'".$video_thumb_width_gshort."';' width='".$video_thumb_width_gshort."' height='".$video_thumb_height_gshort."'>";
											} else if (!empty($provider_shortres) && $provider_shortres == 'dailymotion') {
												$short_code_output.="<img src='http://www.dailymotion.com/thumbnail/video/".$video_id_shortres."' alt='".get_the_title()."' title='".get_the_title()."' style='height:'". $video_thumb_height_gshort."'; width:'".$video_thumb_width_gshort."';' width='".$video_thumb_width_gshort."' height='".$video_thumb_height_gshort."'>";
											}
											$short_code_output.="</figure>";
															//Show cagegory name;
											$category=get_the_term_list(get_the_ID(),'video-categories','',', ','' );
											$category=strip_tags($category);
											$short_code_output.="<header> <h3><a ";
											if($video_link_target == 'popup'){



												$short_code_output.="class='poup_here' id='".$postid."'";
											} else {
												$short_code_output.="href='".get_the_permalink()."'";
											} $short_code_output.=" title='".get_the_title()."'>".wp_trim_words(get_the_title(),10,'...')."</a></h3>";
											if(!empty($vpost_category_result)&& $vpost_category_result='1'){
												$short_code_output.="<span class='category_name'>".$category."</span>&nbsp;";
											};
											if (!empty($vposted_date_display_gshort)) {
												$short_code_output.="<p><span>".human_time_diff(get_the_time('U') , current_time('timestamp')) . ' ago';
												$short_code_output.="</span><span>".rio_video_getPostViews($postid)." Views</span></p>";
											}
											$short_code_output.="</header>
											</article>";
											$short_code_output.="<div class='poup_window' id='show_content".$postid."'>
											<div class='popup-box'>";
											if(!empty($provider_shortres) && $provider_shortres == 'youtube') {
												$short_code_output.="<iframe width='".$video_sthumb_width_res."' height='".$video_sthumb_height_res."' src='//www.youtube.com/embed/".$video_id_shortres."' frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
											} else if(!empty($provider_shortres) && $provider_shortres == 'vimeo') {
												$short_code_output.="<iframe src='//player.vimeo.com/video/".$video_id_shortres."' width='".$video_sthumb_width_res."' height='".$video_sthumb_height_res."' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
											} else if(!empty($provider_shortres) && $provider_shortres == 'dailymotion') {
												$short_code_output.="<iframe frameborder='0' width='".$video_sthumb_width_res."' height='".$video_sthumb_height_res."' src='https://www.dailymotion.com/embed/video/".$video_id_shortres."'webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
											}
											$category=get_the_term_list($postid,'video-categories','',', ','' );
											$category=strip_tags($category);
											$short_code_output.="<h4>".wp_trim_words(get_the_title(),10,'...')."</h4><p>";
											if(!empty($vpost_category_result)&& $vpost_category_result='1'){
												$short_code_output.="<span class='category_name'>".$category."</span>&nbsp;";
											}
											if(!empty($vposted_date_display_gshort)){
												$short_code_output.="<span>".rio_video_getPostViews(get_the_ID())." Views</span>";
												$short_code_output.="<span>".human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago';
												$short_code_output.="</span></p>";
											}
											$short_code_output.="<a class='close_this'>x</a>
											</div>
											</div>";

															} //layout condition 2 close here...
														} //checking $video_id_shortres exist close..
													endwhile;else: echo 'No Videos found'; endif; wp_reset_postdata(); //wp_reset_query();
													$short_code_output.="</div>
													<p class='pagination'>";
													$big = 999999999; // need an unlikely integer
													echo paginate_links(array(
														'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))) ,
														'format' => '?paged=%#%',
														'current' => max(1, get_query_var('paged')) ,
														'total' => $the_query->max_num_pages
													));
													"</p>";
													return $short_code_output;
												}
												// shortcode function close here..


												function wp_script_file2() {
													$data_results = get_option('video_gallery_settings'); //video gallery settings option
													$video_thumb_width_gshort = $data_results['video_thumb_width'];
													if (empty($video_thumb_width_gshort)) {
														$video_thumb_width_gshort = 230;
													}
													?>

													<?php
												}
												add_action('wp_footer', 'wp_script_file2');




												/*
												|--------------------------------------------------------------------------
												| create short code
												|--------------------------------------------------------------------------
												*/
												add_shortcode('videogallery', 'fun_video_gallery_shortcode');
												// videogallery shortcode ends here...
												//Short code for all category listing
												/*
												|--------------------------------------------------------------------------
												| short code function
												|--------------------------------------------------------------------------
												*/
												add_action( 'wp_ajax_nopriv_load-filter2', 'prefix_load_term_posts' );
												add_action( 'wp_ajax_load-filter2', 'prefix_load_term_posts' );
												function prefix_load_term_posts () {
													$term_id = $_POST[ 'term' ];
													$args = array (
														'term' => $term_id,
														'posts_per_page' => -1,
														'order' => 'DESC',
														'tax_query' => array(
															array(
																'taxonomy' => 'yourtaxonomyhere',
																'field'    => 'id',
																'terms'    => $term_id,
																'operator' => 'IN'
															)
														)
													);
													global $post;
													$myposts = get_posts( $args );
													ob_start (); ?>
													<ul class="list">
														<?php foreach( $myposts as $post ) : setup_postdata($post); ?>
															<li><a href="<?php get_the_permalink(); ?>" id="post-<?php the_ID(); ?>"><?php echo get_post_meta($post->ID, 'image', $single = true); ?></a><br />
																<?php the_title(); ?></li>
															<?php endforeach; ?>
														</ul>
														<?php wp_reset_postdata();
														$response = ob_get_contents();
														ob_end_clean();
														echo $response;
														die(1);
													}
													function fun_video_categorylist_shortcode($atts)
													{

														extract(shortcode_atts(array(
															'view' => ''
														) , $atts));
														$all_videos_count=wp_count_posts( 'video-gallery' )->publish;//return all count of the downloads section
														//Get the current category details
														$current_category=get_the_category();
														$current_category_slug=$current_category[0]->slug;
														$parentCatID = ($current_category[0]->parent); //for getting parent category slug//
														$parentCatName = &get_category($parentCatID);
														$parent_category_Slug=$parentCatName->slug;
														$parent_category_Slug;
														$view_status = $view;// return short code post id
														if (!empty($view_status) && $view_status == 'all') // all categories under video gallery post type..
														{
															?>
															<h1 class="widget-title green-text">Categories</h1>
															<ul>
																<li><a href="<?php  bloginfo('url');?>/video-gallery/">All Items <span>(<?php echo $all_videos_count;  ?>)</span></a></li>
																<?php
																$args = array('type'=> 'post','orderby'=> 'name','order'=> 'ASC','number'=> '5','taxonomy'=> 'video-categories','hide_empty'=>0);
																$categories = get_categories($args);
																foreach($categories as $categories)
																{
																	?>
																	<li><a href="#" onclick="term_ajax_get('<?php echo $categories->id;  ?>');"><?php echo $categories->name; ?> <span>(<?php echo $categories->count;?>)</span></a></li>

																<?php }//End foreach?>
															</ul>
															<?php
														}
													}
													add_shortcode('videocategory', 'fun_video_categorylist_shortcode');
													// videocategory list shortcode ends here...
													//function for category listing
													function category_list()
													{
														$all_videos_count=wp_count_posts( 'video-gallery' )->publish;//return all count of the downloads section
														//Get the current category details
														$current_category=get_the_category();
														$current_category_slug=$current_category[0]->slug;
														$parentCatID = ($current_category[0]->parent); //for getting parent category slug//
														$parentCatName = &get_category($parentCatID);
														$parent_category_Slug=$parentCatName->slug;
														$parent_category_Slug;
														?>
														<h1 class="widget-title green-text">Categories</h1>
														<ul>
															<li><a href="<?php  bloginfo('url');?>/video-gallery/">All Items <span>(<?php echo $all_videos_count;  ?>)</span></a></li>
															<?php
															$args = array('type'=> 'post','orderby'=> 'name','order'=> 'ASC','number'=> '5','taxonomy'=> 'video-categories','hide_empty'=>0);
															$categories = get_categories($args);
															foreach($categories as $categories)
															{
																?>
																<li><a href="#"><?php echo $categories->name; ?> <span>(<?php echo $categories->count;?>)</span></a></li>

															<?php }//End foreach?>
														</ul>
														<?php
													}
													// for enabling session in wordpress..
													function rio_video_gallery_init_session()
													{
														session_start();
													}
													add_action('init', 'rio_video_gallery_init_session', 1);
													// function for getting vimeo video imagethumb url..
													function getVimeoThumb($id)
													{
														$data = file_get_contents("http://vimeo.com/api/v2/video/$id.json");
														$data = json_decode($data);
														if (!empty($data[0]->thumbnail_medium)) {
															return $data[0]->thumbnail_medium;
														}
														else {
															return $css_path = plugins_url() . '/rio-video-gallery/img/video-failed.png';
														}
													}
													// function to display number of posts.
													function rio_video_getPostViews($postID)
													{
														$count_key = 'post_views_count';
														$count = get_post_meta($postID, $count_key, true);
														if ($count == '') {
															delete_post_meta($postID, $count_key);
															add_post_meta($postID, $count_key, '0');
															return "0";
														}
														return $count;
													}
													// function to count views.
													function rio_video_setPostViews()
													{
														if (is_single()) {
															global $post;
															$postID = $post->ID;
															$count_key = 'post_views_count';
															// for checking there is any viewed ids exist in viewed session array..
															if (!empty($_SESSION['viewed_IDs'])) {
																$viewed_IDs = $_SESSION['viewed_IDs']; // for getting all viewed_IDs by current user..
															}
															else {
																$viewed_IDs = ''; // viewed ids set as empty....
															}
															$count = get_post_meta($postID, $count_key, true);
															if ($count == '') {
																$count = 0;
																delete_post_meta($postID, $count_key);
																add_post_meta($postID, $count_key, '0');
															}
															else {
																if (@in_array($postID, $viewed_IDs)) //check this post id exist in viewed_IDs array..
																{
																	// 'Do nothing...'
																}
																else {
																	$count++;
																	update_post_meta($postID, $count_key, $count);
																}
															}
															if (!empty($viewed_IDs)) //if session is empty..
															{
																$videoPostIds = $viewed_IDs; // current ids...
																array_push($videoPostIds, $postID); // add new postID to the array..
															}
															else {
																$videoPostIds = array(); // create an array...
																array_push($videoPostIds, $postID); // add new postID to this array..
															}
															$_SESSION['viewed_IDs'] = $videoPostIds;
														}
													}
													add_action('wp_head', 'rio_video_setPostViews');
													// for generating shortcode for video post...
													// for site title shortcode..
													function fun_video_post_shortcode($atts)
													{
														extract(shortcode_atts(array(
															'id' => ''
														) , $atts));
														$videoPost_id = $id;
														$data_results = get_option('video_gallery_settings'); //video gallery settings option
														$vposted_date_display_pshort = $data_results['vposted_date_display'];
														$video_sthumb_width_pres = $data_results['video_sthumb_width'];
														$video_sthumb_height_pres = $data_results['video_sthumb_height'];
														if (!empty($videoPost_id)) {
															// return post with given id from shortcode..
															$getpost = get_post($videoPost_id);
															$posttitle = $getpost->post_title; //returns post title..
															$postcontent = $getpost->post_content; //returns post content..
															$posttitle = $getpost->post_title; //returns post title..
															$provider_pres = get_post_meta($videoPost_id, 'video_provider', true); // returns video provider from post..
															$video_id_pres = get_post_meta($videoPost_id, 'video_id', true); //returns corresponding video id..
															if (!empty($video_id_pres)) { ?>
																<figure>
																	<?php if (!empty($provider_pres) && $provider_pres == 'youtube') { ?>
																		<iframe width="<?php echo $video_sthumb_width_pres; ?>" height="<?php echo $video_sthumb_height_pres; ?>" src="//www.youtube.com/embed/<?php echo $video_id_pres; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen> </iframe>
																	<?php } else if (!empty($provider_pres) && $provider_pres == 'vimeo') { ?>
																		<iframe src="//player.vimeo.com/video/<?php echo $video_id_pres; ?>" width="<?php echo $video_sthumb_width_pres; ?>" height="<?php echo $video_sthumb_height_pres; ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
																	<?php } else if (!empty($provider_pres) && $provider_pres == 'dailymotion') { ?>
																		<iframe frameborder="0" width="<?php echo $video_sthumb_width_pres; ?>" height="<?php echo $video_sthumb_height_pres; ?>" src="https://www.dailymotion.com/embed/video/<?php echo $video_id_pres; ?>" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
																	<?php } ?>
																</figure>
															<?php } ?>
															<article>
																<header>
																	<?php if (!empty($posttitle)) { ?>
																		<h3><?php echo $posttitle; ?></h3>
																	<?php } ?>
																	<?php if (!empty($vposted_date_display_pshort)) { ?>
																		<p><span><?php echo human_time_diff(get_the_time('U', $videoPost_id) , current_time('timestamp')) . ' ago'; ?></span><span><?php
																		echo rio_video_getPostViews($videoPost_id); ?> Views</span></p>
																	<?php } ?>
																</header>
																<?php echo wpautop($postcontent); ?>
															</article>
															<?php
														} //checking videoPost_id exist or not close..
													}
													add_shortcode('videopost', 'fun_video_post_shortcode');
													add_action('admin_head', 'wpt_video_icons');
													function wpt_video_icons()
													{
														?>
														<style type="text/css" media="screen">
															#icon-edit.icon32-posts-video-gallery {background: url(<?php echo plugins_url(); ?>/rio-video-gallery/img/video_page_icon.png) no-repeat;}
														</style>
														<?php
													}
													/*
													|--------------------------------------------------------------------------
													| Widget section
													|--------------------------------------------------------------------------
													*/
													class video_gallery extends WP_Widget
													{
														function __construct()
														{
															$widget_ops = array(
																'classname' => 'video_gallery',
																'description' => 'Use this widget to display video gallery corresponding to your shortcode'
															);
															parent::__construct('video_gallery', 'Video Gallery', $widget_ops);
														}
														function form($instance) // Dashboard area..
														{
															// Check values
															if ($instance) {
																$short_code = $instance['video_short_code'];
															}
															else {
																$short_code = '';
															}
															?>
															<p>
																<label for="<?php echo $this->get_field_id('video_short_code'); ?>">Short code :</label>
																<input class="widefat" id="<?php echo $this->get_field_id('video_short_code'); ?>" name="<?php echo $this->get_field_name('video_short_code'); ?>" type="text" value='<?php echo $short_code; ?>' />
															</p>
															<?php
														}
														function update($new_instance, $old_instance) //update function..
														{
															$instance = $old_instance;
															// Fields
															$instance['video_short_code'] = $new_instance['video_short_code'];
															return $instance;
														}
														function widget($args, $instance) //output funtion...
														{
															extract($args, EXTR_SKIP);
															$short_code = $instance['video_short_code'];
															// WIDGET CODE GOES HERE
															echo do_shortcode($short_code);
														}
													}
													// add_action('widgets_init', create_function('', 'return register_widget("video_gallery");')); //widget
													function video_gallery_widget() {
														return register_widget('video_gallery');
													}
													add_action ('widgets_init', 'video_gallery_widget');
													/*
													|--------------------------------------------------------------------------
													| FILTERS
													|--------------------------------------------------------------------------
													*/
													// include video gallery single template from plugin direcotory
													add_filter("the_content", "get_video_gallery_type_template");
													// include video gallery archive template from plugin direcotory
													add_filter("archive_template", "get_archive_video_gallery_type_template");
													/*
													|--------------------------------------------------------------------------
													| Include custom template from plugin directory and arhive template
													|--------------------------------------------------------------------------
													*/
													function get_video_gallery_type_template($single_template)
													{
														global $post;
														// Get all settings options
														if ($post->post_type == 'video-gallery') {
															if (is_singular('video-gallery')) {
																$current_cat = get_taxonomy('video-categories');
																$obj = get_post_type_object('video-gallery');
																$singular_name = $obj->labels->singular_name;
																// for getting the feartured image...
																$image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID) , 'single-post-thumbnail');
																$provider_shortres = get_post_meta($post->ID, 'video_provider', true); // returns video provider from post..
																$video_id_shortres = get_post_meta($post->ID, 'video_id', true); //returns curresponding video id..
																$post_ID = $post->ID;
																$data_results = get_option('video_gallery_settings'); //get video gallery all settings
																$video_sthumb_width_res = $data_results['video_sthumb_width'];
																$vposted_published_date = $data_results['vposted_date_display'];
																if (empty($video_sthumb_width_res)) {
																	$video_sthumb_width_res = 600;
																}
																$video_sthumb_height_res = $data_results['video_sthumb_height'];
																if (empty($video_sthumb_height_res)) {
																	$video_sthumb_height_res = 400;
																}
																$data_result_date = get_option('video_gallery_settings');
																$display_related_posts = $data_result_date['vrelated_posts'];
																$vpost_category_result = $data_results['vpost_category'];
																$video_provider_result = $data_results['video_provider'];
																//Show the category name
																$category=get_the_term_list(get_the_ID(),'video-categories','',', ','' );
																// $category=get_the_term_list($post->ID,'video-categories','',', ','' );
																$category=strip_tags($category);
																$single_template = "<div id='rio-video-gallery-container-single' itemscope itemtype='http://schema.org/VideoObject'>
																<figure>";
																if (!empty($provider_shortres) && $provider_shortres == 'youtube') {
																	$single_template.= "<iframe width='" . $video_sthumb_width_res . "' height='" . $video_sthumb_height_res . "' src='//www.youtube.com/embed/" . $video_id_shortres . "' frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
																}
																else if (!empty($provider_shortres) && $provider_shortres == 'vimeo') {
																	$single_template.= "<iframe src='//player.vimeo.com/video/" . $video_id_shortres . "' width='" . $video_sthumb_width_res . "' height='" . $video_sthumb_height_res . "' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
																}
																else if (!empty($provider_shortres) && $provider_shortres == 'dailymotion') {
																	$single_template.= "<iframe frameborder='0' width='" . $video_sthumb_width_res . "' height='" . $video_sthumb_height_res . "' src='https://www.dailymotion.com/embed/video/" . $video_id_shortres . "' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
																}
																$single_template.= "<figcaption>";
																if(!empty($vpost_category_result)&& $vpost_category_result='1'){
																	$single_template.="<span class='category_name'>".$category."</span>&nbsp;";
																}
																if(!empty($vposted_published_date)) {
																	$single_template.="<span>Posted " . human_time_diff(get_the_time('U') , current_time('timestamp')) . ' ago' . "</span>";
																	$single_template.="<span>" . rio_video_getPostViews($post_ID) . " Views</span>";
																}
																$single_template.="</figcaption></figure><div class='clearFixer'>&nbsp;</div><p>" . str_replace("\r", "<br />", get_the_content(''))."</p>";
																if (!empty($display_related_posts) && $display_related_posts == 1) {
																	$single_template.= "<div class='clearFixer'>&nbsp;</div>
																	<h4>Related Posts</h4>";
																	$single_template.= "<div class='related-posts-container'>";
																	$args = array(
																		'post_type' => 'video-gallery',
																		'posts_per_page' => 3,
																		'post__not_in' => array(
																			$post_ID
																		)
																	);
																		// The Query
																	$query = new WP_Query($args);
																	if ($query->have_posts()):
																		while ($query->have_posts()):
																			$query->the_post();
																				$provider_res = get_post_meta($post->ID, 'video_provider', true); // returns video provider from post..
																				$video_id_res = get_post_meta($post->ID, 'video_id', true); //returns curresponding video id..
																				$single_template.= "<article itemprop='video' itemscope itemtype='http://schema.org/VideoObject'>
																				<figure>
																				<a href='" . get_the_permalink() . "'>";
																				if (!empty($provider_res) && $provider_res == 'youtube') {
																					$single_template.= "<img src='http://img.youtube.com/vi/" . $video_id_res . "/0.jpg' alt='".get_the_title()."' itemprop='thumbnail'>";
																				}
																				else if (!empty($provider_res) && $provider_res == 'vimeo') {
																					$imgid = $video_id_res;
																					$thumb = getVimeoInfo_details($imgid);
																					if (empty($thumb)) {
																						$thumb = plugins_url() . '/rio-video-gallery/img/video-failed.png';
																					}
																					$single_template.= "<img src='" . $thumb . "' alt='".get_the_title()."' itemprop='thumbnail'>";
																				}
																				else if (!empty($provider_res) && $provider_res == 'dailymotion') {
																					$single_template.= "<img src='http://www.dailymotion.com/thumbnail/video/" . $video_id_res . "' alt='".get_the_title()."' itemprop='thumbnail'>";
																				}
																				$single_template.= "</a>
																				</figure>
																				<header>
																				<h5><a href='" . get_the_permalink() . "' itemprop='name'>" .get_the_title(). "</a></h5>
																				<p>";
																				if(!empty($vposted_published_date)) {
																					$single_template.="<span itemprop='datePublished'>Posted ".human_time_diff(get_the_time('U') , current_time('timestamp'))." ago";
																					$single_template.= "</span>&nbsp;&nbsp;<span itemprop='playCount'>".rio_video_getPostViews($post->ID)." Views</span>";
																				}
																				$single_template.="</p>
																				</header>
																				</article>";
																			endwhile;
																		else:
																			echo 'No other posts found';
																		endif;
																		wp_reset_postdata();
																	}
																	$single_template.= "</div></div>";
																} //===========================Single content end here......
															}
															// if the photo gallery post type
															return $single_template;
														} //function end
														function get_archive_video_gallery_type_template($archivetemplate)
														{
															global $post;
															if ($post->post_type == 'video-gallery') {
																$archivetemplate = dirname(__FILE__) . '/includes/template/archive-video-gallery.php';
															}
															return $archivetemplate;
														}
														function get_taxonomy_video_gallery_type_template($archivetemplate)
														{
															global $post;
															if ($post->post_type == 'video-gallery') {
																$archivetemplate = dirname(__FILE__) . '/includes/template/archive-video-gallery.php';
															}
															return $archivetemplate;
														}
														/*
														|--------------------------------------------------------------------------
														| Hook css to wp_head
														|--------------------------------------------------------------------------
														*/
														add_action('wp_enqueue_scripts', 'rio_video_style_hook');
														function rio_video_style_hook()
														{
															// for enabling style.
															$css_path = plugins_url() . '/rio-video-gallery/css/video-gallery-style.css';
															wp_register_style('video-custom-style', $css_path);
															wp_enqueue_style('video-custom-style');
														}
														/*
														|--------------------------------------------------------------------------
														| short content function
														|--------------------------------------------------------------------------
														*/
														function video_short_content($num)
														{
															$limit = $num + 1;
															$content = str_split(get_the_content());
															$length = count($content);
															if ($length >= $num) {
																$content = array_slice($content, 0, $num);
																$content = implode("", $content) . "...";
																echo $content;
															}
															else {
																the_content();
															}
														}
														/*
														|--------------------------------------------------------------------------
														| Help Section
														|--------------------------------------------------------------------------
														*/
														function video_gallery_help($contextual_help, $screen_id, $screen)
														{
															$CurrentPostType = $screen->post_type; //returns post type..
															if ($CurrentPostType == 'video-gallery') {
																// Add my_help_tab if current screen is video-gallery
																$screen->add_help_tab(array(
																	'id' => 'about_video_gallery',
																	'title' => __('Overview') ,
																	'content' => video_overview()
																));
																// Add category
																$screen->add_help_tab(array(
																	'id' => 'video_gallery_category',
																	'title' => 'Add a Category',
																	'content' => video_add_category()
																));
																// Add POST
																$screen->add_help_tab(array(
																	'id' => 'video_gallery_post',
																	'title' => 'Add a Post',
																	'content' => video_add_post()
																));
																// Settings
																$screen->add_help_tab(array(
																	'id' => 'settings_video',
																	'title' => 'Settings',
																	'content' => video_settings()
																));
																// Youtube Video ID
																$screen->add_help_tab(array(
																	'id' => 'getting_youtube_id',
																	'title' => 'Get Youtube Id',
																	'content' => get_youtube_id()
																));
																// Vimeo Video ID
																$screen->add_help_tab(array(
																	'id' => 'getting_vimeo_id',
																	'title' => 'Get Vimeo Id',
																	'content' => get_vimeo_id()
																));
																// Vimeo Video ID
																$screen->add_help_tab(array(
																	'id' => 'getting_dialymotion_id',
																	'title' => 'Get Dailymotion Id',
																	'content' => get_dialymotion_id()
																));
															}
														}
														add_filter('contextual_help', 'video_gallery_help', 10, 3);
														function video_overview()
														{
															$output = '<h3>Video Gallery</h3>
															<p>This is the Video gallery section, here you can manage your video posts and do basic actions such as Add, Edit, View, Trash and setting up your videos.</p>
															<h3>Managing Video Gallery</h3>
															<ul>
															<li>
															<strong>Creating a new video post:</strong>
															Click on the "Add New" button on the top of the page to create a new video post.
															<p>Here, you need to add the following information.</p>
															<ul>
															<li><strong>Post Title:</strong> This is the title for your video post.</li>
															<li><strong>Post Content:</strong> This is the content for your video post.</li>
															<li><strong>Video Gallery Options:</strong> This section has the following fields.
															<ol>
															<li><strong>Post Order:</strong>Post order specifies the order for this post. The post will show in ascending order corresponding to the numerical value of this field.</li>
															<li><strong>Select your video provider:</strong> You can choose anyone from the three providers, such as YouTube, Vimeo, Dailymotion.</li>
															<li><strong>Video ID:</strong> The video ID corresponding to the provider.</li>
															<li><strong>Short code:</strong> To get this post in a specific page or post, you need to edit a page or post and insert its shortcode into the WordPress text editor</li>
															</ol>
															</li>
															</ul>
															</li>
															<li>
															<strong>Modifying a video post:</strong>
															Click on the name of the video post or the "Edit" button to jump to the edit page.
															</li>
															<li>
															<strong>Adding Video Categories:</strong>
															If you want to post a video under a specific category, you need to add a video category through "Video Categories" option.
															</li>
															<li>
															<strong>Settings of Video Gallery:</strong>
															The complete video gallery settings are under "Settings" options. Here you can manage the following options.
															<ul>
															<li><strong>Basic Settings:</strong> Here you can manage Main Title of the video gallery and can set up some listing options such as Number of videos display, Enable view all button, Show total views & posted date and Enable post order options. </li>
															<li><strong>Gallery Layout: </strong>
															You can choose video layout such as thumbnail only or by title.</li>
															<li><strong>Short codes:</strong> You can place your video into pages and posts with their shortcodes. You can find the shortcode for each video category or all video. To insert the video shortcode, edit a page or post and insert its shortcode into the WordPress text editor.
															<p>Please note that you have to update your video options before leaving from the settings page. These settings options only seems in shortcode used pages and posts.</p>
															</li>
															</ul>
															</li>
															</ul>';
															return $output;
														}
														function video_add_category()
														{
															$output = '<h3>Add a Video Category</h3>
															<p>
															<ul>
															<li> For adding video categories: Click on “Video Categories” link.</li>
															</ul>
															<p><img src="' . plugins_url() . '/rio-video-gallery/help/images/Rio-video-add-category.png" alt="" /><p>
															</p>';
															return $output;
														}
														function video_add_post()
														{
															$output = '<h3>Add a Video Post</h3>
															<p>
															<ul>
															<li>For adding video post: Click on “Add new” link.</li>
															</ul>
															<p><img src="' . plugins_url() . '/rio-video-gallery/help/images/Rio-video-add.png" alt="" /><p>
															</p>
															<p>
															<ul>
															<li> Enter the video title, contentPost Order, Video provider, Video ID and Short code.</li>
															</ul>
															<p><img src="' . plugins_url() . '/rio-video-gallery/help/images/Rio-video-add-new.png" alt="" /></p>
															</p>';
															return $output;
														}
														function video_settings()
														{
															$output = '<h3>Video Gallery Settings</h3>
															<p>
															<ul>
															<li> Click on “Settings” link.</li>
															</ul>
															<p>Here you can manage the following options.<p>
															</p>
															<p>
															<ul>
															<li><strong>Basic Settings:</strong> Here you can manage Main Title of the video gallery and can set up some listing options such as Number of videos display, Enable view all button, Show total views & posted date and Enable post order options. </li>
															<li><strong>Gallery Layout: </strong>
															You can choose video layout such as thumbnail only or by title.</li>
															<li><strong>Short codes:</strong> You can place your video into pages and posts with their shortcodes. You can find the shortcode for each video category or all video. To insert the video shortcode, edit a page or post and insert its shortcode into the WordPress text editor.
															<p>Please note that you have to update your video options before leaving from the settings page. These settings options only seems in shortcode used pages and posts.</p>
															</li>
															</ul>
															<p><img src="' . plugins_url() . '/rio-video-gallery/help/images/Rio-video-gallery-settings-page.png" alt="" /></p>

															</p>
															';
															return $output;
														}
														function get_youtube_id()
														{
															$output = '<h3>Getting Video Id</h3>
															<p><img src="' . plugins_url() . '/rio-video-gallery/help/images/get-youtube-video-id.png" alt="" /><p>
															';
															return $output;
														}
														function get_vimeo_id()
														{
															$output = '<h3>Getting Vimeo Video Id</h3>
															<p><img src="' . plugins_url() . '/rio-video-gallery/help/images/get-vimeo-id-1.png" alt="" /><p>
															<p><img src="' . plugins_url() . '/rio-video-gallery/help/images/get-vimeo-id-2.png" alt="" /><p>
															';
															return $output;
														}
														function get_dialymotion_id()
														{
															$output = '<h3>Getting Dialymotion Video Id</h3>
															<p><img src="' . plugins_url() . '/rio-video-gallery/help/images/get-dialymotion-id.png" alt="" /><p>
															';
															return $output;
														}
														?>
