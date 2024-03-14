<?php
	
/* --------------------------------------------------------- */
/* !Ajax member archive shortcode - 2.0.11 */
/* --------------------------------------------------------- */

function mtphr_member_archive_gen() {
	check_ajax_referer( 'mtphr_shortcode_gen_nonce', 'security' );
	?>
	<div class="mtphr-shortcode-gen mtphr-shortcode-gen-mtphr_member_archive">
		<input type="hidden" class="shortcode" value="mtphr_member_archive" />
		<input type="hidden" class="shortcode-insert" />
		
		<h2><?php _e('Member Archive', 'mtphr-members'); ?></h2>
		
		<div class="row">
			
			<div class="col-md-2">
				<div class="mtphr-ui-form-group">
					<label class="mtphr-ui-label-top"><?php _e('Posts Per Page', 'mtphr-members'); ?></label>
					<input class="mtphr-ui-number" type="number" name="posts_per_page" value="6" placeholder="<?php _e('Use -1 to display all', 'mtphr-members'); ?>" />
				</div>
			</div>
			
			<div class="col-md-2">
				<div class="mtphr-ui-form-group">
					<label class="mtphr-ui-label-top"><?php _e('Columns', 'mtphr-members'); ?></label>
					<select class="mtphr-ui-select" name="columns">
						<option>1</option>
						<option>2</option>
						<option selected="selected">3</option>
						<option>4</option>
						<option>5</option>
						<option>6</option>
					</select>
				</div>
			</div>
			
			<div class="col-md-4">
				<div class="mtphr-ui-form-group">
					<label class="mtphr-ui-label-top"><?php _e('Order By', 'mtphr-members'); ?></label>
					<select class="mtphr-ui-select" name="orderby">
						<option value="ID"><?php _e('ID', 'mtphr-members'); ?></option>
						<option value="author"><?php _e('Author', 'mtphr-members'); ?></option>
						<option value="title"><?php _e('Title', 'mtphr-members'); ?></option>
						<option value="name"><?php _e('Name', 'mtphr-members'); ?></option>
						<option value="date"><?php _e('Date', 'mtphr-members'); ?></option>
						<option value="modified"><?php _e('Modified', 'mtphr-members'); ?></option>
						<option value="parent"><?php _e('Parent', 'mtphr-members'); ?></option>
						<option value="rand"><?php _e('Random', 'mtphr-members'); ?></option>
						<option value="comment_count"><?php _e('Comment Count', 'mtphr-members'); ?></option>
						<option value="menu_order" selected="selected"><?php _e('Menu Order', 'mtphr-members'); ?></option>
					</select>
				</div>		
			</div>
			
			<div class="col-md-4">
				<div class="mtphr-ui-form-group">
					<label class="mtphr-ui-label-top"><?php _e('Order', 'mtphr-members'); ?></label>
					<select class="mtphr-ui-select" name="order">
						<option>ASC</option>
						<option selected="selected">DESC</option>
					</select>
				</div>
			</div>
		
		</div><!-- .row -->
		
		<div class="row">
			
			<div class="col-md-2">
				<div class="mtphr-ui-form-group">
					<label class="mtphr-ui-label-top"><?php _e('Excerpt Length', 'mtphr-members'); ?></label>
					<input class="mtphr-ui-number" type="number" name="excerpt_length" placeholder="140" />
				</div>
			</div>
			
			<div class="col-md-6">
				<div class="mtphr-ui-form-group">
					<label class="mtphr-ui-label-top"><?php _e('Excerpt More', 'mtphr-members'); ?></label>
					<input class="mtphr-ui-text" type="text" name="excerpt_more" placeholder="&hellip;" />
				</div>
			</div>
			
			<div class="col-md-4">
				<div class="mtphr-ui-form-group">
					<label class="mtphr-ui-label-top"><?php _e('Link "Excerpt More" to post', 'mtphr-members'); ?></label>
					<label class="mtphr-ui-checkbox-label"><input class="mtphr-ui-checkbox" type="checkbox" name="more_link" value="true" /> <?php _e('Link to post', 'mtphr-members'); ?></label>
				</div>
			</div>
		
		</div><!-- .row -->
		
		<div class="row">
		
			<div class="col-md-3">
				<div class="mtphr-ui-form-group mtphr-shortcode-gen-taxonomy">
					<label class="mtphr-ui-label-top"><?php _e('Taxonomy', 'mtphr-members'); ?></label>
					<select class="mtphr-ui-select" name="taxonomy">
						<option value="">-----</option>
						<?php	echo mtphr_shortcodes_select_taxonomies( 'mtphr_member' ); ?>
					</select>
				</div>
			</div>
			
			<div class="col-md-6 mtphr-shortcode-gen-taxonomy-fields">
				<div class="mtphr-ui-form-group">
					<label class="mtphr-ui-label-top"><?php _e('Terms', 'mtphr-members'); ?></label>
					<div class="mtphr-shortcode-gen-terms"></div>	
				</div>
			</div>
			
			<div class="col-md-3 mtphr-shortcode-gen-taxonomy-fields">
				<div class="mtphr-ui-form-group">
					<label class="mtphr-ui-label-top"><?php _e('Operator', 'mtphr-members'); ?></label>
					<select class="mtphr-ui-select" name="operator">
						<option value="IN"><?php _e('IN', 'mtphr-members'); ?></option>
						<option value="NOT IN"><?php _e('NOT IN', 'mtphr-members'); ?></option>
						<option value="AND"><?php _e('AND', 'mtphr-members'); ?></option>
					</select>
				</div>
			</div>
			
		</div><!-- .row -->
		
		<div class="mtphr-ui-form-group">
			<label class="mtphr-ui-label-top"><?php _e('Assets', 'mtphr-members'); ?> <small class="optional">(<?php _e('Drag to re-arrange', 'mtphr-members'); ?>)</small></label>				
			<div class="mtphr-shortcode-gen-rearranger">
				<label class="mtphr-ui-multi-check"><input class="mtphr-shortcode-gen-assets" value="thumbnail" type="checkbox" checked="checked"><?php _e('Thumbnail', 'mtphr-members'); ?></label>
				<label class="mtphr-ui-multi-check"><input class="mtphr-shortcode-gen-assets" value="name" type="checkbox" checked="checked"><?php _e('Name', 'mtphr-members'); ?></label>
				<label class="mtphr-ui-multi-check"><input class="mtphr-shortcode-gen-assets" value="info" type="checkbox" checked="checked"><?php _e('Info', 'mtphr-members'); ?></label>
				<label class="mtphr-ui-multi-check"><input class="mtphr-shortcode-gen-assets" value="social" type="checkbox" checked="checked"><?php _e('Social', 'mtphr-members'); ?></label>
				<label class="mtphr-ui-multi-check"><input class="mtphr-shortcode-gen-assets" value="title" type="checkbox" checked="checked"><?php _e('Title', 'mtphr-members'); ?></label>
				<label class="mtphr-ui-multi-check"><input class="mtphr-shortcode-gen-assets" value="excerpt" type="checkbox" checked="checked"><?php _e('Excerpt', 'mtphr-members'); ?></label>
			</div>
		</div>
		
	</div>
	<?php
	die();
}
add_action( 'wp_ajax_mtphr_member_archive_gen', 'mtphr_member_archive_gen' );