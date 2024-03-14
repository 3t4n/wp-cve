<?php 
if ( ! defined( 'ABSPATH' ) ) { exit; }
global $foxtool_options; ?>
<h2><?php _e('OPTIMIZE', 'foxtool'); ?></h2>
<div class="ft-on">
<label class="nut-fton">
<input class="toggle-checkbox" id="check1" data-target="play1" type="checkbox" name="foxtool_settings[speed]" value="1" <?php if ( isset($foxtool_options['speed']) && 1 == $foxtool_options['speed'] ) echo 'checked="checked"'; ?> />
<span class="ftder"></span></label>
<label class="ft-on-right"><?php _e('ON/OFF', 'foxtool'); ?></label>
</div>
<div id="play1" class="toggle-div ft-card">
  <h3><i class="fa-regular fa-square-minus"></i> <?php _e('Disable unnecessary items', 'foxtool') ?></h3>
	<!-- tôi ưu 1 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[speed-off1]" value="1" <?php if ( isset($foxtool_options['speed-off1']) && 1 == $foxtool_options['speed-off1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Disable jQuery Migrate', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('jQuery Migrate is a library used to maintain the operation of certain themes, plugins that rely on older code. If your website no longer relies on this library, you can disable it', 'foxtool'); ?></p>
	<!-- tôi ưu 2 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[speed-off2]" value="1" <?php if ( isset($foxtool_options['speed-off2']) && 1 == $foxtool_options['speed-off2'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Disable Gutenberg CSS', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('If you not using it, you can disable Gutenberg CSS on the homepage', 'foxtool'); ?></p>
	<!-- tôi ưu 3 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[speed-off3]" value="1" <?php if ( isset($foxtool_options['speed-off3']) && 1 == $foxtool_options['speed-off3'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Disable Classic CSS', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('If you not using it, you can disable Classic CSS on the homepage', 'foxtool'); ?></p>
	<!-- tôi ưu 4 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[speed-off4]" value="1" <?php if ( isset($foxtool_options['speed-off4']) && 1 == $foxtool_options['speed-off4'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Disable Emoji', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('If you not using it, you can disable Emoji', 'foxtool'); ?></p>
	
  <h3><i class="fa-brands fa-square-js"></i> <?php _e('Optimization Library', 'foxtool') ?></h3>
	<!-- thư vien js 1 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[speed-link1]" value="1" <?php if ( isset($foxtool_options['speed-link1']) && 1 == $foxtool_options['speed-link1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable Instant-page', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Instant-page is a library that allows you to preload the content of a linked page into the browser memory simply by hovering over the link. When you click on the link, it provides a remarkably fast loading experience', 'foxtool'); ?></p>
	<!-- thư vien js 2 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[speed-link2]" value="1" <?php if ( isset($foxtool_options['speed-link2']) && 1 == $foxtool_options['speed-link2'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable Smooth-scroll', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('Smooth-scroll is a library that enables you to create a smooth scrolling effect, providing users with a perception of faster page navigation', 'foxtool'); ?></p>
  
  <h3><i class="fa-regular fa-loader"></i> <?php _e('The function of lazy loading images', 'foxtool') ?></h3>
	<!-- lazyload img 1 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[speed-lazy1]" value="1" <?php if ( isset($foxtool_options['speed-lazy1']) && 1 == $foxtool_options['speed-lazy1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable image lazy loading', 'foxtool'); ?></label>
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('If you want to lazy load images every time the page loads, then turn it on. This function helps your website load faster', 'foxtool'); ?>
	</p>
	
  <h3><i class="fa-regular fa-file-zipper"></i> <?php _e('Compress HTML into a single line', 'foxtool') ?></h3>
	<!-- nén 1 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[speed-zip1]" value="1" <?php if ( isset($foxtool_options['speed-zip1']) && 1 == $foxtool_options['speed-zip1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable HTML compression', 'foxtool'); ?></label>
	<p class="ft-note ft-note-red"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('With this feature, HTML will be compressed into a single line, removing unnecessary characters and whitespace to speed up page loading', 'foxtool'); ?><br>
	<b><?php _e('Do not enable if you are using optimization plugins with similar functionality (conflict)', 'foxtool'); ?></b>
	</p>
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[speed-zip11]" value="1" <?php if ( isset($foxtool_options['speed-zip11']) && 1 == $foxtool_options['speed-zip11'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Minify Inline JavaScript', 'foxtool'); ?></label>
	</p>
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[speed-zip12]" value="1" <?php if ( isset($foxtool_options['speed-zip12']) && 1 == $foxtool_options['speed-zip12'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Remove comments from HTML, JavaScript, and CSS', 'foxtool'); ?></label>
	</p>
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[speed-zip13]" value="1" <?php if ( isset($foxtool_options['speed-zip13']) && 1 == $foxtool_options['speed-zip13'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Remove XHTML closing tags from empty elements in HTML5', 'foxtool'); ?></label>
	</p>
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[speed-zip14]" value="1" <?php if ( isset($foxtool_options['speed-zip14']) && 1 == $foxtool_options['speed-zip14'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Remove relative domain from internal URLs', 'foxtool'); ?></label>
	</p>
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[speed-zip15]" value="1" <?php if ( isset($foxtool_options['speed-zip15']) && 1 == $foxtool_options['speed-zip15'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Remove protocols (HTTP: and HTTPS:) from all URLs', 'foxtool'); ?></label>
	</p>
	<p>
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[speed-zip16]" value="1" <?php if ( isset($foxtool_options['speed-zip16']) && 1 == $foxtool_options['speed-zip16'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Support multi-byte UTF-8 encoding (if you see strange characters)', 'foxtool'); ?></label>
	</p>
	
	
  <h3><i class="fa-regular fa-database"></i> <?php _e('Optimize saving post content into the database', 'foxtool') ?></h3>
	<!-- csdl 1 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[speed-data1]" value="1" <?php if ( isset($foxtool_options['speed-data1']) && 1 == $foxtool_options['speed-data1'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Enable revision limit', 'foxtool'); ?></label>
	
	<p>
	<input class="ft-input-small" placeholder="3" name="foxtool_settings[speed-data11]" type="number" value="<?php if(!empty($foxtool_options['speed-data11'])){echo sanitize_text_field($foxtool_options['speed-data11']);} ?>"/>
	<label class="ft-label-right"><?php _e('Enter the number of revisions', 'foxtool'); ?></label>
	</p>
	
	<!-- csdl 2 -->
	<label class="nut-switch">
	<input type="checkbox" name="foxtool_settings[speed-data2]" value="1" <?php if ( isset($foxtool_options['speed-data2']) && 1 == $foxtool_options['speed-data2'] ) echo 'checked="checked"'; ?> />
	<span class="slider"></span></label>
	<label class="ft-label-right"><?php _e('Change save interval', 'foxtool'); ?></label>
	
	<p>
	<input class="ft-input-small" placeholder="1" name="foxtool_settings[speed-data21]" type="number" value="<?php if(!empty($foxtool_options['speed-data21'])){echo sanitize_text_field($foxtool_options['speed-data21']);} ?>"/>
	<label class="ft-label-right"><?php _e('Save interval (minutes)', 'foxtool'); ?></label>
	</p>
	
	<p class="ft-note"><i class="fa-regular fa-lightbulb-on"></i> <?php _e('If you enable this feature and set automatic revision limit and automatic save time for posts or pages, it will reduce the amount of data stored in the database', 'foxtool'); ?></p>
	
	
  <h3><i class="fa-regular fa-trash"></i> <?php _e('Delete for database optimization', 'foxtool'); ?></h3>
	<div class="ft-del">
	<a class="delete-post-csdl" href="javascript:void(0)" id="delete-revisions"><i class="fa-regular fa-trash"></i> <?php _e('Delete revisions', 'foxtool'); ?></a>
	<a class="delete-post-csdl" href="javascript:void(0)" id="delete-auto-drafts"><i class="fa-regular fa-trash"></i> <?php _e('Delete autosaves', 'foxtool'); ?></a>
	<a class="delete-post-csdl" href="javascript:void(0)" id="delete-all-trashed-posts"><i class="fa-regular fa-trash"></i> <?php _e('Empty trash', 'foxtool'); ?></a>
	<div id="del-result"></div>
	<script>
		jQuery(document).ready(function($) {
			// xoa revisions
			$('#delete-revisions').click(function(event) {
				var ajax_nonce = '<?php echo wp_create_nonce('foxtool_post_revisions'); ?>';
				event.preventDefault();
				$.ajax({
					url: '<?php echo admin_url('admin-ajax.php');?>',
					type: 'POST',
					data: {
						action: 'foxtool_delete_revisions',
						security: ajax_nonce,
					},
					success: function(response) {
						$('#del-result').html('<span><?php _e('All revisions have been deleted', 'foxtool'); ?></span>');
					},
					error: function(response) {
						$('#del-result').html('<span><?php _e('Error! Unable to delete', 'foxtool'); ?></span>');
					}
				});
			});
			// xoa auto-drafts
			$('#delete-auto-drafts').click(function(event) {
				var ajax_nonce = '<?php echo wp_create_nonce('foxtool_post_drafts'); ?>';
				event.preventDefault();
				$.ajax({
					url: '<?php echo admin_url('admin-ajax.php');?>',
					type: 'POST',
					data: {
						action: 'foxtool_delete_auto_drafts',
						security: ajax_nonce,
					},
					success: function(response) {
						$('#del-result').html('<span><?php _e('All autosaves have been deleted', 'foxtool'); ?></span>');
					},
					error: function(response) {
						$('#del-result').html('<span><?php _e('Error! Unable to delete', 'foxtool'); ?></span>');
					}
				});
			});
			// xoa tat ca trong thung rac
			$('#delete-all-trashed-posts').click(function(event) {
				var ajax_nonce = '<?php echo wp_create_nonce('foxtool_post_trashed'); ?>';
				event.preventDefault();
				$.ajax({
					url: '<?php echo admin_url('admin-ajax.php');?>',
					type: 'POST',
					data: {
						action: 'foxtool_delete_all_trashed_posts',
						security: ajax_nonce,
					},
					success: function(response) {
						$('#del-result').html('<span><?php _e('All items in the trash have been deleted', 'foxtool'); ?></span>');
					},
					error: function(response) {
						$('#del-result').html('<span><?php _e('Error! Unable to delete', 'foxtool'); ?></span>');
					}
				});
			});
		});
	</script>
	</div>
</div>	