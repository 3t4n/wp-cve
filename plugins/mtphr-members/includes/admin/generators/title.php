<?php
	
/* --------------------------------------------------------- */
/* !Ajax gallery shortcode - 2.0.11 */
/* --------------------------------------------------------- */

function mtphr_gallery_gen() {
	check_ajax_referer( 'mtphr_shortcode_gen_nonce', 'security' );
	$args = array(
	  'posts_per_page' => -1,
	  'orderby' => 'title',
		'order' => 'ASC',
		'post_type' => 'mtphr_member'
	);
	$posts = get_posts( $args );
	?>
	<div class="mtphr-shortcode-gen mtphr-shortcode-gen-mtphr_member_title">
		<input type="hidden" class="shortcode" value="mtphr_member_title" />
		<input type="hidden" class="shortcode-insert" />
		
		<h2><?php _e('Member Title', 'mtphr-members'); ?></h2>
		
		<div class="row">
			
			<div class="col-md-3">
				<div class="mtphr-ui-form-group">
					<label class="mtphr-ui-label-top"><?php _e('Gallery', 'mtphr-members'); ?> <small class="required">(<?php _e('Required', 'mtphr-members'); ?>)</small></label>
					<select class="mtphr-ui-select" name="id">
						<option value=""><?php _e('Select a Gallery', 'mtphr-members'); ?></option>
						<?php foreach( $posts as $post ) { ?>
							<option value="<?php echo $post->ID; ?>"><?php echo $post->post_title; ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			
			<div class="col-md-9">
				<div class="mtphr-ui-form-group">
					<label class="mtphr-ui-label-top"><?php _e('Class', 'mtphr-members'); ?> <small class="optional">(<?php _e('Optional', 'mtphr-members'); ?>)</small></label>
					<input class="mtphr-ui-text" type="text" name="class" placeholder="<?php _e('Add an optional class name', 'mtphr-members'); ?>" />
				</div>
			</div>
		
		</div>
		
		<div class="mtphr-ui-form-group">
			<label class="mtphr-ui-label-top"><?php _e('Layout', 'mtphr-members'); ?> <small class="optional">(<?php _e('Drag to re-arrange', 'mtphr-members'); ?>)</small></label>				
			<div class="mtphr-shortcode-gen-rearranger">
				<label class="mtphr-ui-multi-check"><input class="mtphr-shortcode-gen-assets" value="gallery" type="checkbox" checked="checked"><?php _e('Gallery', 'mtphr-members'); ?></label>
				<label class="mtphr-ui-multi-check"><input class="mtphr-shortcode-gen-assets" value="navigation" type="checkbox" checked="checked"><?php _e('Navigation', 'mtphr-members'); ?></label>
			</div>
		</div>
			
	</div>
	<?php
	die();
}
add_action( 'wp_ajax_mtphr_gallery_gen', 'mtphr_gallery_gen' );