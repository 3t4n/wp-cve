<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly
$facebook_feed_fetch = unserialize(get_option("weblizar_facebook_feed_option_settings"));

//page url
if (isset($facebook_feed_fetch["ffp_page_url"])) {
	$ffp_page_url = $facebook_feed_fetch["ffp_page_url"];
} else {
	$ffp_page_url = "https://www.facebook.com/weblizarstyle/";
}
//post limit
if (isset($facebook_feed_fetch["ffp_limit"])) {
	$ffp_limit = $facebook_feed_fetch["ffp_limit"];
} else {
	$ffp_limit = "10";
}
//post layout
if (isset($facebook_feed_fetch["ffp_timeline_layout"])) {
	$ffp_timeline_layout = $facebook_feed_fetch["ffp_timeline_layout"];
} else {
	$ffp_timeline_layout = "full_width";
}
//custom css
if (isset($facebook_feed_fetch["feed_customs_css"])) {
	$feed_customs_css = $facebook_feed_fetch["feed_customs_css"];
} else {
	$feed_customs_css = "";
}
//hover color
if (isset($facebook_feed_fetch["ffp_hover_color"])) {
	$ffp_hover_color = $facebook_feed_fetch["ffp_hover_color"];
} else {
	$ffp_hover_color = "#2e2c2c";
}
//Header show hide
if (isset($facebook_feed_fetch["ffp_header_check"])) {
	$ffp_header_check = $facebook_feed_fetch["ffp_header_check"];
} else {
	$ffp_header_check = "yes";
}
//post layout assign to variable
if ($ffp_timeline_layout == 'full_width') {
	$layout1 = "col-md-12";
	$layout2 = "col-md-12";
}
if ($ffp_timeline_layout == 'half_width') {
	$layout1 = "col-md-6";
	$layout2 = "col-md-6";
}
if ($ffp_timeline_layout == 'thumbnail') {
	$layout1 = "col-md-9";
	$layout2 = "col-md-3";
}
//facebook page id
if (isset($facebook_feed_fetch["ffp_page_id"])) {
	$ffp_page_id = $facebook_feed_fetch["ffp_page_id"];
} else {
	$ffp_page_id = "EAAETHMFXDyMBAJOSHSUgQrkC2y7aoTHKZCwr5hzZAUDjDWD36RqqdCg5sclue9SXIrh5HHkXuc5wV4MCSgcZBLMeWC5lZAQAqXZCFzxSZBSfDrAzFaAVCxgNPTQnA3QhrXbvJPednCma5IwcJPjlT1BacrQm1n0hgqkhylPwW4SgZDZD";
}

$ffp_page_url = str_replace(['https://www.facebook.com/', 'https://facebook.com/', 'facebook.com/'], '', $ffp_page_url);
if (strpos($ffp_page_url, '/') !== true) {
	$ffp_page_url = preg_replace('#\/[^/]*$#', '', $ffp_page_url);
}

if (strpos($ffp_page_url, '?') !== true) {
	$ffp_page_url = preg_replace('#\?[^?]*$#', '', $ffp_page_url);
}

//deafult token for page

$token = $ffp_page_id;
if ($ffp_page_id == "") {
	$token = "EAAETHMFXDyMBAJOSHSUgQrkC2y7aoTHKZCwr5hzZAUDjDWD36RqqdCg5sclue9SXIrh5HHkXuc5wV4MCSgcZBLMeWC5lZAQAqXZCFzxSZBSfDrAzFaAVCxgNPTQnA3QhrXbvJPednCma5IwcJPjlT1BacrQm1n0hgqkhylPwW4SgZDZD";
}
//header curl url
$header_string = "https://graph.facebook.com/v2.10/" . $ffp_page_url . "?access_token=" . $token . "&fields=fan_count,cover,picture.width(300),name,link";
//page curl url
$page_timeline_string = "https://graph.facebook.com/v2.10/" . $ffp_page_url . "?access_token=" . $token . "&fields=posts.limit(200){type,source,link,attachments,comments.limit(3).summary(true){message,from,comment_count,created_time,id},full_picture,object_id,message,message_tags,created_time,from,shares,reactions.type(LOVE).limit(0).summary(total_count).as(reactions_love),reactions.type(WOW).limit(0).summary(total_count).as(reactions_wow),reactions.type(HAHA).limit(0).summary(total_count).as(reactions_haha),reactions.type(PRIDE).limit(0).summary(total_count).as(reactions_pride),reactions.type(THANKFUL).limit(0).summary(total_count).as(reactions_thankful),reactions.type(SAD).limit(0).summary(total_count).as(reactions_sad),reactions.type(LIKE).limit(0).summary(total_count).as(reactions_like),reactions.type(ANGRY).limit(0).summary(total_count).as(reactions_angry)}";
//custom box sidebar start
if (isset($_POST['action']) == "get_feed_like_comment") {
	$data_string = "" ?>
	<div class="wp-weblizar_like_comment_div  clearfix">
		<?php if (isset($_REQUEST['id'])) // post id 
		{
			if (isset($_REQUEST['type']))  // post type
			{
				$feed_type = sanitize_text_field($_REQUEST['type']);
				$data_var  = sanitize_text_field($_REQUEST['id']);
				$data_var  = preg_replace('/\s+/', '', $data_var);
				if ($feed_type == 'post') {
					$post_type = "";
					if (isset($_REQUEST['post_types'])) {
						$post_type = sanitize_text_field($_REQUEST['post_types']);
						if ($post_type == 'photo') {
							//custom box sidebar curl 
							$data_string = "https://graph.facebook.com/v2.10/" . $data_var . "?access_token=" . $token . "&fields=created_time,from,id,comments.limit(5).summary(true){message,from,comment_count,created_time},name,message,message_tags,reactions.type(LOVE).limit(0).summary(total_count).as(reactions_love),reactions.type(WOW).limit(0).summary(total_count).as(reactions_wow),reactions.type(HAHA).limit(0).summary(total_count).as(reactions_haha),reactions.type(PRIDE).limit(0).summary(total_count).as(reactions_pride),reactions.type(THANKFUL).limit(0).summary(total_count).as(reactions_thankful),reactions.type(SAD).limit(0).summary(total_count).as(reactions_sad),reactions.type(LIKE).limit(0).summary(total_count).as(reactions_like),reactions.type(ANGRY).limit(0).summary(total_count).as(reactions_angry)";
						}
					}
				}
				if (!$jsondata = wp_remote_get($data_string) || (!wp_remote_retrieve_body($jsondata))) {
					esc_html_e('HTTP request failed.', WEBLIZAR_FACEBOOK_TEXT_DOMAIN);
				} else {
					$jsondata = wp_remote_get($data_string);
					$jsondata = wp_remote_retrieve_body($jsondata);
					$data_content = json_decode($jsondata, true);
					//post create time
					if (isset($data_content['created_time'])) {
						$create_time = $data_content['created_time'];
						$s = strtotime($create_time);
					}
					// post create author
					if (isset($data_content['from']['id'])) {	?>
						<div class="weblizar-fb-eapps-facebook-feed-popup-item-header weblizar-fb-eapps-facebook-feed-item-author">
							<div class="weblizar-fb-eapps-facebook-feed-item-author-picture">
								<!-- post author link and image  -->
								<a href="https://facebook.com/<?php echo esc_attr($data_content['from']['id']); ?>" target="_blank" rel="nofollow">
									<img src="https://graph.facebook.com/<?php echo esc_attr($data_content['from']['id']); ?>/picture?type=square">
								</a>
							</div>
							<div class="weblizar-fb-eapps-facebook-feed-item-author-info">
								<!-- post author name  -->
								<div class="weblizar-fb-eapps-facebook-feed-item-author-name">
									<a href="https://facebook.com/<?php echo esc_attr($data_content['from']['id']); ?>" target="_blank" rel="nofollow"><?php echo esc_html($data_content['from']['name']); ?></a>
								</div>
								<!-- post create date  -->
								<div class="weblizar-fb-eapps-facebook-feed-item-date"><i class="fas fa-clock-o"></i>&nbsp; <span><?php printf(esc_html(_x('%s Ago', '%s = human-readable time difference', WEBLIZAR_FACEBOOK_TEXT_DOMAIN)), human_time_diff(date('U', $s), current_time('timestamp'))); ?></span>
								</div>
							</div>
						</div>
					<?php }
					if ($feed_type == 'post') { ?>
						<div class="web_interes">
							<?php if (isset($data_content['message'])) //post text message
							{
								$text = $data_content['message'];
								$reg_exUrl = "/(((http|https|ftp|ftps)\:\/\/)|(www\.))[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\:[0-9]+)?(\/\S*)?/";
								if (preg_match($reg_exUrl, $text, $url)) {
									// make the urls hyper links
									$text = preg_replace($reg_exUrl, "<a class='fb_messaage_tag' href='" . $url[0] . "' target='_blank'>" . $url[0] . "</a> ", $text);
								}
								$text = preg_replace('/#(\\w+)/', '<a class=post_hastag_color href=https://www.facebook.com/hashtag/$1 target=_blank>$0</a>', $text);
								// fetch url tag from post  message
								if (isset($data_content['message_tags'])) {
									//count url tag from post message  
									$tag_size = sizeof($data_content['message_tags']);
									//fetch tag from  post message
									for ($t = 0; $t < $tag_size; $t++) {
										$ar = $data_content['message_tags'][$t]["name"];
										$br = $data_content['message_tags'][$t]["id"];
										$text = str_replace($data_content['message_tags'][$t]["name"], "<a class='fb_messaage_tag' href='http://facebook.com/" . $br . "' target='_blank' >" . $ar . "</a>", $text);
									}
								} ?>

								<p class="text weblizar_fb-post_font_color">
									<?php if (strlen(strip_tags($text)) >= 250)  // post message display
									{ ?>
										<span class="weblizar_fb_teaser weblizar_fb-post_font_color"><?php echo esc_html(substr(strip_tags($text), 0, 250)); ?></span>
										<span class="weblizar_fb_complete weblizar_fb-post_font_color" style="display:none"><?php echo esc_html($text); ?></span>
										<span data-text="...Show less" class="facebook_feed_more_page"><?php esc_html_e('...See more', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></span>
									<?php } else { ?>
										<span class="weblizar_fb-post_font_color"><?php echo esc_html($text); ?></span>
									<?php }  ?>
								</p>
							<?php }  ?>
						</div>
					<?php } ?>
					<div class=" clearfix"> </div>
					<!-- like button -->
					<div class="wp-weblizar_moment-link pull-left">
						<ul>
							<li>
								<a href="#"><i class="fas fa-thumbs-up"></i><?php esc_html_e('Like', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?></a>
								<ul>
									<!-- like count display -->
									<li class="tooltip-new"><a href="#"><i class="fas wp-weblizar_fb wp-weblizar_s-3"><span class="tooltiptext like"><?php echo esc_html($data_content['reactions_like']['summary']['total_count']); ?></span> </i> </a> </li>
									<!-- love count display -->
									<li> <a href="#"> <i class="fas wp-weblizar_fb wp-weblizar_s-2"><span class="tooltiptext love"><?php echo esc_html($data_content['reactions_love']['summary']['total_count']); ?></span> </i> </a></li>
									<!-- haha count display -->
									<li><a href="#"> <i class="fas wp-weblizar_fb wp-weblizar_s-1"> <span class="tooltiptext haha"><?php echo esc_html($data_content['reactions_haha']['summary']['total_count']); ?></span></i> </a></li>
									<!-- angry count display -->
									<li><a href="#"> <i class="fas wp-weblizar_fb wp-weblizar_s-4"><span class="tooltiptext angry"><?php echo esc_html($data_content['reactions_angry']['summary']['total_count']); ?> </i> </a></li>
									<!-- sad count display -->
									<li><a href="#"> <i class="fas wp-weblizar_fb wp-weblizar_s-5"> <span class="tooltiptext"><?php echo esc_html($data_content['reactions_sad']['summary']['total_count']); ?></span></i> </a></li>
								</ul>
							</li>
						</ul>
					</div>
					<div class=" clearfix"> </div>
					<!-- comment display -->
					<?php if (isset($data_content['comments']['data'])) { ?>
						<div class="col-md-12 wp-weblizar_custom_box_comment">
							<div class="wp-weblizar_comment-area col-md-12 padding-0" id="multiCollapseExample1">
								<p class="wp-weblizar_comment-area-titel"><?php esc_html_e('Comment on Facebook', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>
								</p>
							</div>

							<?php $comments = sizeof($data_content['comments']['data']); // number of comment count
							for ($c = 0; $c < $comments; $c++) // comment data fetch start
							{ ?>
								<div class="weblizar-fb-eapps-facebook-feed-popup-item-header weblizar-fb-eapps-facebook-feed-item-author">
									<!-- comment author image and link -->
									<div class="weblizar-fb-eapps-facebook-feed-item-author-picture">
										<?php if (isset($data_content['comments']['data'][$c]['from']['id'])) { ?>
											<a href="https://facebook.com/<?php echo esc_attr($data_content['comments']['data'][$c]['from']['id']); ?>" target="_blank" rel="nofollow">
												<img src="https://graph.facebook.com/<?php echo esc_attr($data_content['comments']['data'][$c]['from']['id']); ?>/picture?type=square">
											</a>
										<?php } ?>
									</div>
									<!-- comment author info -->
									<div class="weblizar-fb-eapps-facebook-feed-item-author-info">
										<?php if (isset($data_content['comments']['data'][$c]['from']['id'])) { ?>
											<!-- comment author name -->
											<div class="weblizar-fb-eapps-facebook-feed-item-author-name">
												<a href="https://facebook.com/<?php echo esc_attr($data_content['comments']['data'][$c]['from']['id']); ?>" target="_blank" rel="nofollow"><?php echo esc_html($data_content['comments']['data'][$c]['from']['name']); ?></a>
											</div>
										<?php } ?>
										<!-- comment created time-->
										<div class="weblizar-fb-eapps-facebook-feed-item-date">
											<span><?php if (isset($data_content['comments']['data'][$c]['created_time'])) {
														$s = strtotime($data_content['comments']['data'][$c]['created_time']);
														printf(esc_html(_x('%s Ago', '%s = human-readable time difference', WEBLIZAR_FACEBOOK_TEXT_DOMAIN)), human_time_diff(date('U', $s), current_time('timestamp')));
													} ?>
											</span>
										</div>
									</div>
									<div class="weblizar-fb-eapps-facebook-feed-popup-item-content">
										<div class="weblizar-fb-eapps-facebook-feed-popup-item-content-text">
											<div class="weblizar-fb-eui-item-text">
												<div class="weblizar-fb-eui-item-text-excerpt">
													<!-- comment message-->
													<span class="webliza-r-fpcmt-dec"> <?php if (isset($data_content['comments']['data'][$c]['message'])) {
																							echo esc_html($data_content['comments']['data'][$c]['message']);
																						} ?> </span>
													<!-- comment reply count-->
													<span class="webliza-r-fpcmt-reply"> <?php if (isset($data_content['comments'][$c]['comment_count'])) {
																								echo esc_html($data_content['comments'][$c]['comment_count']);
																							} else {
																								echo esc_attr("0");
																							} ?>&nbsp;<?php esc_html_e('reply', WEBLIZAR_FACEBOOK_TEXT_DOMAIN); ?>
													</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php }  ?>
						</div>
		<?php	}
				}
			}
		} ?>
	</div>
<?php } //custom box sidebar end 
?>

<script>
	/* custom box js start */
	(function($) {
		jQuery.fn.Am2_SimpleSlider = function() {
			//popup div
			$div = $('<div class="weblizar-fb-product-gallery-popup weblizar-fb-model"> <div class="popup-overlay"></div> <div class="weblizar-fb-product-popup-content "> <div class="weblizar-fb-product-image col-md-8"> <div class="ffp_weblizar_img"></div> <div class="gallery-nav-btns"> <a id="nav-btn-next" data-id="0" feed-type="0" feed-post="0" feed-token="0" feed-id="0" class="nav-btn next" > <i class="fas fa-arrow-circle-o-right"> </i></a> <a id="nav-btn-prev" data-id="0" feed-type="0" feed-post="0" feed-token="0" feed-id="0"  class="nav-btn prev" >   <i class="fas fa-arrow-circle-o-left"> </i> </a></div> </div><div class="weblizar-fb-product-information text col-md-4"><div class="card-footer   text-muted" style="display:none"> <div class="wp-weblizar_moment-link pull-left text-muted"> <ul>  <li>  <a href="#"> <i class="fas fa-thumbs-up"> 0 </i> </a> <ul> <li class="tooltip-new"> <a href="#"> <i class="fas wp-weblizar_fb wp-weblizar_s-3"><span class="tooltiptext like"> </span> </i> </a> </li>  <li> <a href="#"> <i class="fas wp-weblizar_fb wp-weblizar_s-2"><span class="tooltiptext love"> </span> </i> </a></li>  <li><a href="#"> <i class="fas wp-weblizar_fb wp-weblizar_s-1"> <span class="tooltiptext haha"> </span></i> </a></li><li><a href="#"> <i class="fas wp-weblizar_fb wp-weblizar_s-4"><span class="tooltiptext angry"> </span> </i> </a></li>  <li><a href="#"> <i class="fas wp-weblizar_fb wp-weblizar_s-5"> <span class="tooltiptext "> </span></i> </a></li> </ul></li> <li> <a data-toggle="collapse" href="#multiCollapseExample12" aria-expanded="false" aria-controls="multiCollapseExample12"> <i class="fas fa-comment"> 0 </i></a></li></ul></div></div><div class="wp-weblizar_comment-area col-md-12 padding-0" id="multiCollapseExample1"><p style="display:none"> Comment on Facebook </p>  <div class="weblizar-comment"></div></div>  </div> <div class="clear"></div><a href="#" class="cross">X</a></div></div>').appendTo('body');

			//on image click   
			$(this).click(function() {
				$('.weblizar-fb-product-gallery-popup').fadeIn(500);
				$('body').css({
					'overflow': 'hidden'
				});
				$('.weblizar-fb-product-popup-content  a#nav-btn-next').attr('data-id', $(this).find('dialog').attr('data-id'));
				$('.weblizar-fb-product-popup-content  a#nav-btn-next').attr('feed-type', $(this).find('dialog').attr('feed-type'));
				$('.weblizar-fb-product-popup-content  a#nav-btn-next').attr('feed-post', $(this).find('dialog').attr('feed-post'));
				$('.weblizar-fb-product-popup-content  a#nav-btn-next').attr('feed-token', $(this).find('dialog').attr('feed-token'));
				$('.weblizar-fb-product-popup-content  a#nav-btn-prev').attr('data-id', $(this).find('dialog').attr('data-id'));
				$('.weblizar-fb-product-popup-content  a#nav-btn-prev').attr('feed-type', $(this).find('dialog').attr('feed-type'));
				$('.weblizar-fb-product-popup-content  a#nav-btn-prev').attr('feed-post', $(this).find('dialog').attr('feed-post'));
				$('.weblizar-fb-product-popup-content  a#nav-btn-prev').attr('feed-token', $(this).find('dialog').attr('feed-token'));
				var val = jQuery(this).find('dialog').attr('data-id');
				var typ = jQuery(this).find('dialog').attr('feed-type');
				var post_type = jQuery(this).find('dialog').attr('feed-post');
				var feed_id = jQuery(this).find('dialog').attr('feed-id');
				jQuery(".feed_demo_cls").hide();
				jQuery('div.inner_box_' + val).append("<div class='feed_demo_cls' style='text-align:center;'><img id='load_img_comment' src='<?php echo esc_url(WEBLIZAR_FACEBOOK_PLUGIN_URL . 'images/loader.gif'); ?>' height='50' width='50'/></div>");
				jQuery.ajax({
					type: "POST",
					url: location.href,
					data: {
						'action': 'get_feed_like_comment',
						'id': val,
						'type': typ,
						'post_types': post_type,
						'feed_id': feed_id,
						//'post_token':post_token,
					},
					success: function(data) {

						like_comment = jQuery(data).find('div.wp-weblizar_like_comment_div:first');
						jQuery('div.weblizar-fb-product-gallery-popup').find('div.inner_box_' + val).html(like_comment);
						jQuery(document).unbind("ajaxComplete");
						jQuery(".feed_more").click(function() {
							jQuery(".weblizar_fb_teaser").toggle();
							jQuery(".weblizar_fb_complete").toggle();
							var oldText = $(this).text();
							var newText = $(this).attr('data-text');
							if (jQuery(this).text(oldText)) {
								jQuery(this).text(newText);
							} else {
								jQuery(this).text(oldText);
							}
							jQuery(this).attr('data-text', oldText);
						});
						//Query(".custom_box_load_div").hide(); 
					}
				});
				$('.weblizar-fb-product-popup-content .weblizar-fb-product-image div.ffp_weblizar_img').html($(this).find('span.weblizar_span_img').html());
				$('.weblizar-fb-product-popup-content .weblizar-fb-product-information .weblizar-comment').html($(this).find('dialog').html());
				$Current = $(this);
				$PreviousElm = $(this).closest('.custom_box_gallary').prev().find('.gallery-img')
				$nextElm = $(this).closest('.custom_box_gallary').next().find('.gallery-img')
				if ($PreviousElm.length === 0) {
					$('.nav-btn.prev').css({
						'display': 'none'
					});
				} else {
					$('.nav-btn.prev').css({
						'display': 'block'
					});
				}
				if ($nextElm.length === 0) {
					$('.nav-btn.next').css({
						'display': 'none'
					});
				} else {
					$('.nav-btn.next').css({
						'display': 'block'
					});
				}
			});
			//on Next click
			$('.next').click(function() {
				$NewCurrent = $nextElm;
				$PreviousElm = $NewCurrent.closest('.custom_box_gallary').prev().find('.gallery-img')
				$nextElm = $NewCurrent.closest('.custom_box_gallary').next().find('.gallery-img')
				$('.weblizar-fb-product-popup-content .weblizar-fb-product-image img').clearQueue().animate({
					opacity: '0'
				}, 0).attr('src', $NewCurrent.find('img').attr('src')).animate({
					opacity: '1'
				}, 500);

				$('.weblizar-fb-product-popup-content  a#nav-btn-next').attr('data-id', $NewCurrent.find('dialog').attr('data-id'));
				$('.weblizar-fb-product-popup-content  a#nav-btn-next').attr('feed-type', $NewCurrent.find('dialog').attr('feed-type'));
				$('.weblizar-fb-product-popup-content  a#nav-btn-next').attr('feed-post', $NewCurrent.find('dialog').attr('feed-post'));
				$('.weblizar-fb-product-popup-content  a#nav-btn-next').attr('feed-token', $NewCurrent.find('dialog').attr('feed-token'));
				$('.weblizar-fb-product-popup-content  a#nav-btn-prev').attr('data-id', $NewCurrent.find('dialog').attr('data-id'));
				$('.weblizar-fb-product-popup-content  a#nav-btn-prev').attr('feed-type', $NewCurrent.find('dialog').attr('feed-type'));
				$('.weblizar-fb-product-popup-content  a#nav-btn-prev').attr('feed-post', $NewCurrent.find('dialog').attr('feed-post'));
				$('.weblizar-fb-product-popup-content  a#nav-btn-prev').attr('feed-token', $NewCurrent.find('dialog').attr('feed-token'));
				var val = jQuery(this).attr('data-id');
				var typ = jQuery(this).attr('feed-type');
				var post_type = jQuery(this).attr('feed-post');
				var feed_id = jQuery(this).find('dialog').attr('feed-id');
				jQuery(".feed_demo_cls").hide();
				jQuery('div.inner_box_' + val).append("<div class='feed_demo_cls' style='text-align:center;'><img id='load_img_comment' src='<?php echo esc_url(WEBLIZAR_FACEBOOK_PLUGIN_URL . 'images/loader.gif'); ?>' height='50' width='50'/></div>");
				jQuery.ajax({
					type: "POST",
					url: location.href,
					data: {
						'action': 'get_feed_like_comment',
						'id': val,
						'type': typ,
						'post_types': post_type,
						'feed_id': feed_id,
					},
					success: function(data) {

						like_comment = jQuery(data).find('div.wp-weblizar_like_comment_div:first');
						jQuery('div.weblizar-fb-product-gallery-popup').find('div.inner_box_' + val).html(like_comment);
						jQuery(document).unbind("ajaxComplete");
						jQuery(".feed_more").click(function() {
							jQuery(".weblizar_fb_teaser").toggle();
							jQuery(".weblizar_fb_complete").toggle();
							var oldText = $(this).text();
							var newText = $(this).attr('data-text');
							if (jQuery(this).text(oldText)) {
								jQuery(this).text(newText);
							} else {
								jQuery(this).text(oldText);
							}
							jQuery(this).attr('data-text', oldText);
						});
					}
				});

				$('.weblizar-fb-product-popup-content .weblizar-fb-product-image div.ffp_weblizar_img').html($NewCurrent.find('span.weblizar_span_img').html());
				$('.weblizar-fb-product-popup-content .weblizar-fb-product-information .weblizar-comment').html($NewCurrent.find('dialog').html());
				if ($PreviousElm.length === 0) {
					$('.nav-btn.prev').css({
						'display': 'none'
					});
				} else {
					$('.nav-btn.prev').css({
						'display': 'block'
					});
				}
				if ($nextElm.length === 0) {
					$('.nav-btn.next').css({
						'display': 'none'
					});
				} else {
					$('.nav-btn.next').css({
						'display': 'block'
					});
				}
			});
			//on Prev click
			$('.prev').click(function() {
				$NewCurrent = $PreviousElm;
				$PreviousElm = $NewCurrent.closest('.custom_box_gallary').prev().find('.gallery-img')
				$nextElm = $NewCurrent.closest('.custom_box_gallary').next().find('.gallery-img')
				$('.weblizar-fb-product-popup-content .weblizar-fb-product-image img').clearQueue().animate({
					opacity: '0'
				}, 0).attr('src', $NewCurrent.find('img').attr('src')).animate({
					opacity: '1'
				}, 500);

				$('.weblizar-fb-product-popup-content  a#nav-btn-next').attr('data-id', $NewCurrent.find('dialog').attr('data-id'));
				$('.weblizar-fb-product-popup-content  a#nav-btn-next').attr('feed-type', $NewCurrent.find('dialog').attr('feed-type'));
				$('.weblizar-fb-product-popup-content  a#nav-btn-next').attr('feed-post', $NewCurrent.find('dialog').attr('feed-post'));
				$('.weblizar-fb-product-popup-content  a#nav-btn-next').attr('feed-token', $NewCurrent.find('dialog').attr('feed-token'));
				$('.weblizar-fb-product-popup-content  a#nav-btn-prev').attr('data-id', $NewCurrent.find('dialog').attr('data-id'));
				$('.weblizar-fb-product-popup-content  a#nav-btn-prev').attr('feed-type', $NewCurrent.find('dialog').attr('feed-type'));
				$('.weblizar-fb-product-popup-content  a#nav-btn-prev').attr('feed-post', $NewCurrent.find('dialog').attr('feed-post'));
				$('.weblizar-fb-product-popup-content  a#nav-btn-prev').attr('feed-token', $NewCurrent.find('dialog').attr('feed-token'));
				var val = jQuery(this).attr('data-id');
				var typ = jQuery(this).attr('feed-type');
				var post_type = jQuery(this).attr('feed-post');
				var feed_id = jQuery(this).find('dialog').attr('feed-id');
				jQuery(".feed_demo_cls").hide();
				jQuery('div.inner_box_' + val).append("<div class='feed_demo_cls' style='text-align:center;'><img id='load_img_comment' src='<?php echo esc_url(WEBLIZAR_FACEBOOK_PLUGIN_URL . 'images/loader.gif'); ?>' height='50' width='50'/></div>");
				jQuery.ajax({
					type: "POST",
					url: location.href,
					data: {
						'action': 'get_feed_like_comment',
						'id': val,
						'type': typ,
						'post_types': post_type,
						'feed_id': feed_id,
					},
					success: function(data) {
						like_comment = jQuery(data).find('div.wp-weblizar_like_comment_div:first');
						jQuery('div.weblizar-fb-product-gallery-popup').find('div.inner_box_' + val).html(like_comment);
						jQuery(document).unbind("ajaxComplete");
						jQuery(".feed_more").click(function() {
							jQuery(".weblizar_fb_teaser").toggle();
							jQuery(".weblizar_fb_complete").toggle();
							var oldText = $(this).text();
							var newText = $(this).attr('data-text');
							if (jQuery(this).text(oldText)) {
								jQuery(this).text(newText);
							} else {
								jQuery(this).text(oldText);
							}
							jQuery(this).attr('data-text', oldText);
						});
					}
				});
				$('.weblizar-fb-product-popup-content .weblizar-fb-product-image div.ffp_weblizar_img').html($NewCurrent.find('span.weblizar_span_img').html());
				$('.weblizar-fb-product-popup-content .weblizar-fb-product-information .weblizar-comment').html($NewCurrent.find('dialog').html());
				if ($PreviousElm.length === 0) {
					$('.nav-btn.prev').css({
						'display': 'none'
					});
				} else {
					$('.nav-btn.prev').css({
						'display': 'block'
					});
				}
				if ($nextElm.length === 0) {
					$('.nav-btn.next').css({
						'display': 'none'
					});
				} else {
					$('.nav-btn.next').css({
						'display': 'block'
					});
				}
			});
			//Close Popup
			$('.cross,.popup-overlay').click(function() {
				$('.weblizar-fb-product-gallery-popup').fadeOut(500);
				$('body').css({
					'overflow': 'initial'
				});
			});

			//Key Events
			/* $(document).on('keyup', function (e) {
			     e.preventDefault();
			     //Close popup on esc
			     if (e.keyCode === 27) { $('.weblizar-fb-product-gallery-popup').fadeOut(500); $('body').css({ 'overflow': 'initial' }); }
			     //Next Img On Right Arrow Click
			     if (e.keyCode === 39) { NextProduct(); }
			     //Prev Img on Left Arrow Click
			     if (e.keyCode === 37) { PrevProduct(); }
			 });*/

			function NextProduct() {
				if ($nextElm.length === 1) {
					$NewCurrent = $nextElm;
					$PreviousElm = $NewCurrent.prev();
					$nextElm = $NewCurrent.next();
					$('.weblizar-fb-product-popup-content .weblizar-fb-product-image img').clearQueue().animate({
						opacity: '0'
					}, 0).attr('src', $NewCurrent.find('img').attr('src')).animate({
						opacity: '1'
					}, 500);

					$('.weblizar-fb-product-popup-content  a#nav-btn-next').attr('data-id', $NewCurrent.find('dialog').attr('data-id'));
					$('.weblizar-fb-product-popup-content  a#nav-btn-next').attr('feed-type', $NewCurrent.find('dialog').attr('feed-type'));
					$('.weblizar-fb-product-popup-content  a#nav-btn-next').attr('feed-post', $NewCurrent.find('dialog').attr('feed-post'));
					$('.weblizar-fb-product-popup-content  a#nav-btn-next').attr('feed-token', $NewCurrent.find('dialog').attr('feed-token'));
					$('.weblizar-fb-product-popup-content  a#nav-btn-prev').attr('data-id', $NewCurrent.find('dialog').attr('data-id'));
					$('.weblizar-fb-product-popup-content  a#nav-btn-prev').attr('feed-type', $NewCurrent.find('dialog').attr('feed-type'));
					$('.weblizar-fb-product-popup-content  a#nav-btn-prev').attr('feed-post', $NewCurrent.find('dialog').attr('feed-post'));
					$('.weblizar-fb-product-popup-content  a#nav-btn-prev').attr('feed-token', $NewCurrent.find('dialog').attr('feed-token'));
					var val = jQuery(this).attr('data-id');
					var typ = jQuery(this).attr('feed-type');
					var post_type = jQuery(this).attr('feed-post');
					var feed_id = jQuery(this).find('dialog').attr('feed-id');
					console.log(feed_id);
					jQuery(".feed_demo_cls").hide();
					jQuery('div.inner_box_' + val).append("<div class='feed_demo_cls' style='text-align:center;'><img id='load_img_comment' src='<?php echo esc_url(WEBLIZAR_FACEBOOK_PLUGIN_URL . 'images/loader.gif'); ?>' height='50' width='50'/></div>");
					jQuery.ajax({
						type: "POST",
						url: location.href,
						data: {
							'action': 'get_feed_like_comment',
							'id': val,
							'type': typ,
							'post_types': post_type,
							'feed_id': feed_id,
						},
						success: function(data) {
							like_comment = jQuery(data).find('div.wp-weblizar_like_comment_div:first');
							jQuery('div.weblizar-fb-product-gallery-popup').find('div.inner_box_' + val).html(like_comment);
							jQuery(document).unbind("ajaxComplete");
							jQuery(".feed_more").click(function() {
								jQuery(".weblizar_fb_teaser").toggle();
								jQuery(".weblizar_fb_complete").toggle();
								var oldText = $(this).text();
								var newText = $(this).attr('data-text');
								if (jQuery(this).text(oldText)) {
									jQuery(this).text(newText);
								} else {
									jQuery(this).text(oldText);
								}
								jQuery(this).attr('data-text', oldText);
							});
						}
					});
					$('.weblizar-fb-product-popup-content .weblizar-fb-product-image div.ffp_weblizar_img').html($NewCurrent.find('span.weblizar_span_img').html());
					$('.weblizar-fb-product-popup-content .weblizar-fb-product-information .weblizar-comment').html($NewCurrent.find('dialog').html());
					if ($PreviousElm.length === 0) {
						$('.nav-btn.prev').css({
							'display': 'none'
						});
					} else {
						$('.nav-btn.prev').css({
							'display': 'none'
						});
					}
					if ($nextElm.length === 0) {
						$('.nav-btn.next').css({
							'display': 'none'
						});
					} else {
						$('.nav-btn.next').css({
							'display': 'none'
						});
					}
				}

			}

			function PrevProduct() {
				if ($PreviousElm.length === 1) {
					$NewCurrent = $PreviousElm;
					$PreviousElm = $NewCurrent.prev();
					$nextElm = $NewCurrent.next();
					$('.weblizar-fb-product-popup-content .weblizar-fb-product-image img').clearQueue().animate({
						opacity: '0'
					}, 0).attr('src', $NewCurrent.find('img').attr('src')).animate({
						opacity: '1'
					}, 500);

					$('.weblizar-fb-product-popup-content  a#nav-btn-next').attr('data-id', $NewCurrent.find('dialog').attr('data-id'));
					$('.weblizar-fb-product-popup-content  a#nav-btn-next').attr('feed-type', $NewCurrent.find('dialog').attr('feed-type'));
					$('.weblizar-fb-product-popup-content  a#nav-btn-next').attr('feed-post', $NewCurrent.find('dialog').attr('feed-post'));
					$('.weblizar-fb-product-popup-content  a#nav-btn-next').attr('feed-token', $NewCurrent.find('dialog').attr('feed-token'));
					$('.weblizar-fb-product-popup-content  a#nav-btn-prev').attr('data-id', $NewCurrent.find('dialog').attr('data-id'));
					$('.weblizar-fb-product-popup-content  a#nav-btn-prev').attr('feed-type', $NewCurrent.find('dialog').attr('feed-type'));
					$('.weblizar-fb-product-popup-content  a#nav-btn-prev').attr('feed-post', $NewCurrent.find('dialog').attr('feed-post'));
					$('.weblizar-fb-product-popup-content  a#nav-btn-prev').attr('feed-token', $NewCurrent.find('dialog').attr('feed-token'));
					var val = jQuery(this).attr('data-id');
					var typ = jQuery(this).attr('feed-type');
					var post_type = jQuery(this).attr('feed-post');
					var feed_id = jQuery(this).find('dialog').attr('feed-id');

					jQuery(".feed_demo_cls").hide();
					jQuery('div.inner_box_' + val).append("<div class='feed_demo_cls' style='text-align:center;'><img id='load_img_comment' src='<?php echo esc_url(WEBLIZAR_FACEBOOK_PLUGIN_URL . 'images/loader.gif'); ?>' height='50' width='50'/></div>");
					jQuery.ajax({
						type: "POST",
						url: location.href,
						data: {
							'action': 'get_feed_like_comment',
							'id': val,
							'type': typ,
							'post_types': post_type,
							'feed_id': feed_id,
							//'post_token':post_token,
						},
						success: function(data) {
							//jQuery("#custom_box_load_div").hide();
							like_comment = jQuery(data).find('div.wp-weblizar_like_comment_div:first');
							jQuery('div.weblizar-fb-product-gallery-popup').find('div.inner_box_' + val).html(like_comment);

							jQuery(document).unbind("ajaxComplete");
							jQuery(".feed_more").click(function() {
								jQuery(".weblizar_fb_teaser").toggle();
								jQuery(".weblizar_fb_complete").toggle();
								var oldText = $(this).text();
								var newText = $(this).attr('data-text');
								if (jQuery(this).text(oldText)) {
									jQuery(this).text(newText);
								} else {
									jQuery(this).text(oldText);
								}
								jQuery(this).attr('data-text', oldText);
							});
							//jQuery(".custom_box_load_div").hide();
						}
					});

					$('.weblizar-fb-product-popup-content .weblizar-fb-product-image div.ffp_weblizar_img').html($NewCurrent.find('span.weblizar_span_img').html());
					$('.weblizar-fb-product-popup-content .weblizar-fb-product-information .weblizar-comment').html($NewCurrent.find('dialog').html());
					if ($PreviousElm.length === 0) {
						$('.nav-btn.prev').css({
							'display': 'none'
						});
					} else {
						$('.nav-btn.prev').css({
							'display': 'none'
						});
					}
					if ($nextElm.length === 0) {
						$('.nav-btn.next').css({
							'display': 'none'
						});
					} else {
						$('.nav-btn.next').css({
							'display': 'none'
						});
					}
				}

			}
		};

	}(jQuery));
	/* custom box js end */

	/* custom box js assign */
	jQuery(document).ready(function() {
		jQuery('.gallery-img').Am2_SimpleSlider();
	});
	/* custom box js assign end */

	/* post comment box open js */
	jQuery(document).ready(function() {
		var acc = document.getElementsByClassName("accordion");
		var i;

		for (i = 0; i < acc.length; i++) {
			acc[i].onclick = function() {
				this.classList.toggle("active");
				var panel = this.nextElementSibling;
				if (panel.style.display === "block") {
					panel.style.display = "none";
				} else {
					panel.style.display = "block";
				}
			}
		}
	});
	/* post comment box open js end */
</script>
<style>
	a.weblizar_fb-before-ovrly:after {
		position: absolute;
		left: 0;
		right: 0;
		content: '';
		top: 0;
		opacity: 0;
		bottom: 0;
		background-color: <?php echo esc_attr($ffp_hover_color); ?>;
		transition: .5s ease;
	}

	.gallery-img:hover a.weblizar_fb-before-ovrly:after {
		opacity: 0.5;
		transition: .5s ease;
	}

	<?php echo esc_html($feed_customs_css); ?>
</style>