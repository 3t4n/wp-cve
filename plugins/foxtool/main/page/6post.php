<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
global $foxtool_options; ?>
<h2><?php _e('POST, PAGE', 'foxtool'); ?></h2>
<div class="ft-on">
<label class="nut-fton">
<input class="toggle-checkbox" id="check6" data-target="play6" type="checkbox" name="foxtool_settings[post]" value="1" <?php if ( isset($foxtool_options['post']) && 1 == $foxtool_options['post'] ) echo 'checked="checked"'; ?> />
<span class="ftder"></span></label>
<label class="ft-on-right"><?php _e('ON/OFF', 'foxtool'); ?></label>
</div>
<div id="play6" class="ft-card toggle-div">
  <h3><i class="fa-regular fa-image"></i> <?php _e('Image function for posts', 'foxtool') ?></h3>
	<!-- post up hinh anh 1 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[post-up1]" value="1" <?php if ( isset($foxtool_options['post-up1']) && 1 == $foxtool_options['post-up1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Save images to media when copying from another source', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Enable this feature if you want images in posts copied from another source to be stored on your website', 'foxtool'); ?></p>
	
	<!-- xoa bai viet xoa hinh anh 1 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[post-del1]" value="1" <?php if ( isset($foxtool_options['post-del1']) && 1 == $foxtool_options['post-del1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable deleting posts to also delete images', 'foxtool'); ?></label>
	<p class="ft-note ft-note-red"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('This function allows you to delete images attached to posts when deleting the posts themselves. Note that if multiple posts use the same image, it will also be deleted when removing the post', 'foxtool'); ?></p>
	
	<!-- anh dau tiên làm ảnh đại diện bài viết -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[post-thum1]" value="1" <?php if ( isset($foxtool_options['post-thum1']) && 1 == $foxtool_options['post-thum1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('The first image becomes the featured image', 'foxtool'); ?></label>
	
	<p style="display:flex;">
	<input id="ft-add5" class="ft-input-big" name="foxtool_settings[post-thum11]" type="text" value="<?php if(!empty($foxtool_options['post-thum11'])){echo sanitize_text_field($foxtool_options['post-thum11']);} ?>" placeholder="<?php _e('Add default featured image', 'fox'); ?>" />
	<button class="ft-selec" data-input-id="ft-add5"><?php _e('Select image', 'foxtool'); ?></button>
	</p>
	
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Enable this feature if you want the first image in the post to become the featured image if the featured image field is empty. Additionally, you can select a default featured image in case both the featured image and the images in the post are empty', 'foxtool'); ?></p>
	
	<!-- đặt ảnh gốc khi thêm vào bài viết -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[post-imgsize1]" value="1" <?php if ( isset($foxtool_options['post-imgsize1']) && 1 == $foxtool_options['post-imgsize1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('The original image size when added to the post', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Enable this feature if you want the original image size to be selected by default whenever adding images to the post', 'foxtool'); ?></p>
  
  <h3><i class="fa-regular fa-copy"></i> <?php _e('Duplicate post, page', 'foxtool') ?></h3>
	<!-- post nhan ban 1 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[post-dup1]" value="1" <?php if ( isset($foxtool_options['post-dup1']) && 1 == $foxtool_options['post-dup1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Add duplicate button', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Enable if you want to add the duplicate post or page feature in the post or page management interface', 'foxtool'); ?></p>
	
  <h3><i class="fa-regular fa-link"></i> <?php _e('Configure category permalink', 'foxtool') ?></h3>
	<!-- thay doi slug 1 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[post-link1]" value="1" <?php if ( isset($foxtool_options['post-link1']) && 1 == $foxtool_options['post-link1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Remove category slug from permalink', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Convert paths from (domain.com/category/news/latest-news, domain.com/category/news) to (domain.com/latest-news, domain.com/news)', 'foxtool'); ?><br>
	<b><?php _e('Settings > Permalinks > Save changes', 'foxtool') ?></b>
	</p>
	<!-- thay doi slug 2 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[post-link2]" value="1" <?php if ( isset($foxtool_options['post-link2']) && 1 == $foxtool_options['post-link2'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Remove tag slug from permalink', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Convert path from domain.com/tag/news/ to domain.com/news/', 'foxtool'); ?><br>
	<b><?php _e('Settings > Permalinks > Save changes', 'foxtool') ?></b>
	</p>
  
  <h3><i class="fa-regular fa-link"></i> <?php _e('Add .html for page', 'foxtool') ?></h3>
	<!-- thay doi slug 1 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[post-html1]" value="1" <?php if ( isset($foxtool_options['post-html1']) && 1 == $foxtool_options['post-html1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable .html for page', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('If you enable this feature, your Pages will have .html appended to them, for example: domain.com/page.html', 'foxtool'); ?></p>
  
  <h3><i class="fa-regular fa-ranking-star"></i> <?php _e('SEO optimization', 'foxtool') ?></h3>
	<!-- thêm alt bằng tiêu đề bài viết cho ảnh -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[post-alt1]" value="1" <?php if ( isset($foxtool_options['post-alt1']) && 1 == $foxtool_options['post-alt1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Use titles as descriptions for images', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('This feature will use the title of the post as the description for the image when uploaded', 'foxtool'); ?></p>
	<!-- them nofollow và _blank cho đường dẫn bên ngoài -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[post-out1]" value="1" <?php if ( isset($foxtool_options['post-out1']) && 1 == $foxtool_options['post-out1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Add nofollow and _blank for external links', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('This feature will add nofollow and _blank for external links in posts', 'foxtool'); ?></p>

  <h3><i class="fa-regular fa-hammer"></i> <?php _e('Additional feature', 'foxtool') ?></h3>
	<!-- other 1 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[post-other1]" value="1" <?php if ( isset($foxtool_options['post-other1']) && 1 == $foxtool_options['post-other1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Allow using Shortcodes in post titles', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('This feature allows you to add Shortcodes to post titles, which is very convenient for using custom tools', 'foxtool'); ?></p>
	<!-- other 2 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[post-other2]" value="1" <?php if ( isset($foxtool_options['post-other2']) && 1 == $foxtool_options['post-other2'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Newly edited posts will be displayed first', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('This feature allows you to set newly edited posts to be displayed first in the main loop', 'foxtool'); ?></p>
  <h3><i class="fa-regular fa-eye-slash"></i> <?php _e('Hide post categories from homepage', 'foxtool') ?></h3>
	<!-- an chuyen muc khoi index -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[post-hiden1]" value="1" <?php if ( isset($foxtool_options['post-hiden1']) && 1 == $foxtool_options['post-hiden1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable if you want to hide categories', 'foxtool'); ?></label>
	<p>
	<input class="ft-input-big" placeholder="1, 2, 3, 4, 5" name="foxtool_settings[post-hiden11]" type="text" value="<?php if(!empty($foxtool_options['post-hiden11'])){echo sanitize_text_field($foxtool_options['post-hiden11']);} ?>"/>
	</p>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Enable and add the category IDs you want to hide from the main loop displaying posts on the homepage, for example: 1, 2, 3', 'foxtool'); ?></p>
  <h3><i class="fa-regular fa-calendar-image"></i> <?php _e('Advanced image viewing feature in posts', 'foxtool') ?></h3>
	<!-- an chuyen muc khoi index -->
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[post-fancy1]" value="1" <?php if ( isset($foxtool_options['post-fancy1']) && 1 == $foxtool_options['post-fancy1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable advanced image viewing', 'foxtool'); ?></label>
	</p>
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[post-fancy11]" value="1" <?php if ( isset($foxtool_options['post-fancy11']) && 1 == $foxtool_options['post-fancy11'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Full-screen browsing mode', 'foxtool'); ?></label>
	</p>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('This feature uses the Fancybox library, allowing you to open images in posts for viewing', 'foxtool'); ?></p>
</div>	