<?php

/* --------------------------------------------------------- */
/* !Add shortcodes to the generator - 1.1.0 */
/* --------------------------------------------------------- */

	function mtphr_members_shortcodes() {

		global $mtphr_shortcode_gen_assets;

		$shortcodes = array();
		$shortcodes['mtphr_member_archive_gen'] = array(
			'label' => __('Member Archive', 'mtphr-members')
		);
		$shortcodes['mtphr_member_title_gen'] = array(
			'label' => __('Member Title', 'mtphr-members')
		);
		$shortcodes['mtphr_member_contact_info_gen'] = array(
			'label' => __('Member Contact Info', 'mtphr-members')
		);
		$shortcodes['mtphr_member_social_sites_gen'] = array(
			'label' => __('Member Social Sites', 'mtphr-members')
		);
		$shortcodes['mtphr_member_twitter_gen'] = array(
			'label' => __('Member Twitter', 'mtphr-members')
		);

		// Add the shortcodes to the list
		$mtphr_shortcode_gen_assets['mtphr_members'] = array(
			'label' => __('Metaphor Members', 'mtphr-members'),
			'shortcodes' => $shortcodes
		);
	}
	add_action( 'admin_init', 'mtphr_members_shortcodes' );



/* --------------------------------------------------------- */
/* !Ajax member archive shortcode - 1.1.0 */
/* --------------------------------------------------------- */

	function mtphr_member_archive_gen() {
		check_ajax_referer( 'mtphr_shortcode_gen_nonce', 'security' );
		?>
		<div class="mtphr-shortcode-gen-container mtphr-shortcode-gen-mtphr_member_archive">
			<input type="hidden" class="shortcode" value="mtphr_member_archive" />
			<input type="hidden" class="shortcode-insert" />
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('Posts Per Page', 'mtphr-members'); ?> <small>(<?php _e('Use -1 to display all', 'mtphr-members'); ?>)</small></label>
				<input type="number" name="posts_per_page" placeholder="9" />
			</div>
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('Columns', 'mtphr-members'); ?></label>
				<select name="columns">
					<option>1</option>
					<option>2</option>
					<option selected="selected">3</option>
					<option>4</option>
					<option>5</option>
					<option>6</option>
				</select>
			</div>
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('Order By', 'mtphr-members'); ?></label>
				<select name="orderby">
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
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('Order', 'mtphr-members'); ?></label>
				<select name="order">
					<option>ASC</option>
					<option selected="selected">DESC</option>
				</select>
			</div>
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('Excerpt Length', 'mtphr-members'); ?> <small class="optional">(<?php _e('Optional', 'mtphr-members'); ?>)</small></label>
				<input type="number" name="excerpt_length" placeholder="140" />
			</div>
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('Excerpt More', 'mtphr-members'); ?> <small class="optional">(<?php _e('Optional', 'mtphr-members'); ?>)</small></label>
				<input type="text" name="excerpt_more" placeholder="&hellip;" />
				<label class="checkbox"><input type="checkbox" name="more_link" value="true" /> <?php _e('Link to post', 'mtphr-members'); ?></label>
			</div>
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('Categories', 'mtphr-members'); ?> <small class="optional">(<?php _e('Optional', 'mtphr-members'); ?>)</small></label>
				<span class="description"><?php _e('Use slugs separated by (,) commas.', 'mtphr-members'); ?></span>
				<input type="text" name="categories" />
			</div>
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('Assets', 'mtphr-members'); ?> <small class="optional">(<?php _e('Optional', 'mtphr-members'); ?>)</small></label>
				<span class="description"><?php _e('Separate assets by (,) commas. Re-order or remove the following: <strong>thumbnail</strong>,<strong>name</strong>,<strong>info</strong>,<strong>social</strong>,<strong>title</strong>,<strong>excerpt</strong>', 'mtphr-members'); ?></span>
				<input type="text" name="assets" value="thumbnail,name,social,title,excerpt" />
			</div>
		</div>
		<?php
		die();
	}
	add_action( 'wp_ajax_mtphr_member_archive_gen', 'mtphr_member_archive_gen' );



/* --------------------------------------------------------- */
/* !Ajax member title shortcode - 1.1.0 */
/* --------------------------------------------------------- */

	function mtphr_member_title_gen() {
		check_ajax_referer( 'mtphr_shortcode_gen_nonce', 'security' );
		$settings = mtphr_members_settings();
		$args = array(
		  'posts_per_page' => -1,
		  'orderby' => 'title',
			'order' => 'ASC',
			'post_type' => 'mtphr_member'
		);
		$posts = get_posts( $args );
		?>
		<div class="mtphr-shortcode-gen-container mtphr-shortcode-gen-mtphr_member_title">
			<input type="hidden" class="shortcode" value="mtphr_member_title" />
			<input type="hidden" class="shortcode-insert" />
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('ID', 'mtphr-members'); ?> <small class="optional">(<?php _e('Optional', 'mtphr-members'); ?>)</small></label>
				<select name="id">
					<option value=""><?php printf(__('Use current %s', 'mtphr-members'), $settings['singular_label']); ?></option>
					<?php foreach( $posts as $post ) { ?>
						<option value="<?php echo $post->ID; ?>"><?php echo $post->post_title; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('Title element', 'mtphr-members'); ?> <small class="optional">(<?php _e('Optional', 'mtphr-members'); ?>)</small></label>
				<span class="description"><?php _e('Change the title element to any html element', 'mtphr-members'); ?></span>
				<input type="text" name="element" placeholder="h3" />
			</div>
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('Title before', 'mtphr-members'); ?> <small class="optional">(<?php _e('Optional', 'mtphr-members'); ?>)</small></label>
				<span class="description"><?php _e('Add content before the title', 'mtphr-members'); ?></span>
				<input type="text" name="before" />
			</div>
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('Title after', 'mtphr-members'); ?> <small class="optional">(<?php _e('Optional', 'mtphr-members'); ?>)</small></label>
				<span class="description"><?php _e('Add content after the title', 'mtphr-members'); ?></span>
				<input type="text" name="after" />
			</div>
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('Class', 'mtphr-members'); ?> <small class="optional">(<?php _e('Optional', 'mtphr-members'); ?>)</small></label>
				<span class="description"><?php _e('Add custom classes to the title', 'mtphr-members'); ?></span>
				<input type="text" name="class" />
			</div>
		</div>
		<?php
		die();
	}
	add_action( 'wp_ajax_mtphr_member_title_gen', 'mtphr_member_title_gen' );
	
	
/* --------------------------------------------------------- */
/* !Ajax member contact info shortcode - 1.1.0 */
/* --------------------------------------------------------- */

	function mtphr_member_contact_info_gen() {
		check_ajax_referer( 'mtphr_shortcode_gen_nonce', 'security' );
		$settings = mtphr_members_settings();
		$args = array(
		  'posts_per_page' => -1,
		  'orderby' => 'title',
			'order' => 'ASC',
			'post_type' => 'mtphr_member'
		);
		$posts = get_posts( $args );
		?>
		<div class="mtphr-shortcode-gen-container mtphr-shortcode-gen-mtphr_member_contact_info">
			<input type="hidden" class="shortcode" value="mtphr_member_contact_info" />
			<input type="hidden" class="shortcode-insert" />
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('ID', 'mtphr-members'); ?> <small class="optional">(<?php _e('Optional', 'mtphr-members'); ?>)</small></label>
				<select name="id">
					<option value=""><?php printf(__('Use current %s', 'mtphr-members'), $settings['singular_label']); ?></option>
					<?php foreach( $posts as $post ) { ?>
						<option value="<?php echo $post->ID; ?>"><?php echo $post->post_title; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('Title', 'mtphr-members'); ?> <small class="optional">(<?php _e('Optional', 'mtphr-members'); ?>)</small></label>
				<span class="description"><?php _e('Add a title to the contact info', 'mtphr-members'); ?></span>
				<input type="text" name="title" />
			</div>
			<div class="mtphr-shortcode-gen-attribute mtphr-shortcode-gen-attribute-title_element">
				<label><?php _e('Title element', 'mtphr-members'); ?> <small class="optional">(<?php _e('Optional', 'mtphr-members'); ?>)</small></label>
				<span class="description"><?php _e('Change the title element to any html element', 'mtphr-members'); ?></span>
				<input type="text" name="title_element" placeholder="h3" />
			</div>
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('Class', 'mtphr-members'); ?> <small class="optional">(<?php _e('Optional', 'mtphr-members'); ?>)</small></label>
				<span class="description"><?php _e('Add custom classes to the title', 'mtphr-members'); ?></span>
				<input type="text" name="class" />
			</div>
		</div>
		<?php
		die();
	}
	add_action( 'wp_ajax_mtphr_member_contact_info_gen', 'mtphr_member_contact_info_gen' );
	
	
/* --------------------------------------------------------- */
/* !Ajax member social sites shortcode - 1.1.0 */
/* --------------------------------------------------------- */

	function mtphr_member_social_sites_gen() {
		check_ajax_referer( 'mtphr_shortcode_gen_nonce', 'security' );
		$settings = mtphr_members_settings();
		$args = array(
		  'posts_per_page' => -1,
		  'orderby' => 'title',
			'order' => 'ASC',
			'post_type' => 'mtphr_member'
		);
		$posts = get_posts( $args );
		?>
		<div class="mtphr-shortcode-gen-container mtphr-shortcode-gen-mtphr_member_social_sites">
			<input type="hidden" class="shortcode" value="mtphr_member_social_sites" />
			<input type="hidden" class="shortcode-insert" />
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('ID', 'mtphr-members'); ?> <small class="optional">(<?php _e('Optional', 'mtphr-members'); ?>)</small></label>
				<select name="id">
					<option value=""><?php printf(__('Use current %s', 'mtphr-members'), $settings['singular_label']); ?></option>
					<?php foreach( $posts as $post ) { ?>
						<option value="<?php echo $post->ID; ?>"><?php echo $post->post_title; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('Title', 'mtphr-members'); ?> <small class="optional">(<?php _e('Optional', 'mtphr-members'); ?>)</small></label>
				<span class="description"><?php _e('Add a title to the social sites', 'mtphr-members'); ?></span>
				<input type="text" name="title" />
			</div>
			<div class="mtphr-shortcode-gen-attribute mtphr-shortcode-gen-attribute-title_element">
				<label><?php _e('Title element', 'mtphr-members'); ?> <small class="optional">(<?php _e('Optional', 'mtphr-members'); ?>)</small></label>
				<span class="description"><?php _e('Change the title element to any html element', 'mtphr-members'); ?></span>
				<input type="text" name="title_element" placeholder="h3" />
			</div>
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('Class', 'mtphr-members'); ?> <small class="optional">(<?php _e('Optional', 'mtphr-members'); ?>)</small></label>
				<span class="description"><?php _e('Add custom classes to the title', 'mtphr-members'); ?></span>
				<input type="text" name="class" />
			</div>
		</div>
		<?php
		die();
	}
	add_action( 'wp_ajax_mtphr_member_social_sites_gen', 'mtphr_member_social_sites_gen' );
	
	
/* --------------------------------------------------------- */
/* !Ajax member twitter shortcode - 1.1.0 */
/* --------------------------------------------------------- */

	function mtphr_member_twitter_gen() {
		check_ajax_referer( 'mtphr_shortcode_gen_nonce', 'security' );
		$settings = mtphr_members_settings();
		$args = array(
		  'posts_per_page' => -1,
		  'orderby' => 'title',
			'order' => 'ASC',
			'post_type' => 'mtphr_member'
		);
		$posts = get_posts( $args );
		?>
		<div class="mtphr-shortcode-gen-container mtphr-shortcode-gen-mtphr_member_social_sites">
			<input type="hidden" class="shortcode" value="mtphr_member_twitter" />
			<input type="hidden" class="shortcode-insert" />
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('ID', 'mtphr-members'); ?> <small class="optional">(<?php _e('Optional', 'mtphr-members'); ?>)</small></label>
				<select name="id">
					<option value=""><?php printf(__('Use current %s', 'mtphr-members'), $settings['singular_label']); ?></option>
					<?php foreach( $posts as $post ) { ?>
						<option value="<?php echo $post->ID; ?>"><?php echo $post->post_title; ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('Title', 'mtphr-members'); ?> <small class="optional">(<?php _e('Optional', 'mtphr-members'); ?>)</small></label>
				<span class="description"><?php _e('Add a title to the twitter feed', 'mtphr-members'); ?></span>
				<input type="text" name="title" />
			</div>
			<div class="mtphr-shortcode-gen-attribute mtphr-shortcode-gen-attribute-title_element">
				<label><?php _e('Title element', 'mtphr-members'); ?> <small class="optional">(<?php _e('Optional', 'mtphr-members'); ?>)</small></label>
				<span class="description"><?php _e('Change the title element to any html element', 'mtphr-members'); ?></span>
				<input type="text" name="title_element" placeholder="h3" />
			</div>
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('Limit', 'mtphr-members'); ?> <small class="optional">(<?php _e('Optional', 'mtphr-members'); ?>)</small></label>
				<span class="description"><?php _e('Set the number of tweets to display', 'mtphr-members'); ?></span>
				<input type="number" name="limit" placeholder="3" min="1" max="50" />
			</div>
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('Image/Avatar', 'mtphr-members'); ?> <small class="optional">(<?php _e('Optional', 'mtphr-members'); ?>)</small></label>
				<span class="description" style="margin-bottom:5px;"><?php _e('Display a Twitter image or your avatar', 'mtphr-members'); ?></span>
				<label style="display:inline-block;font-weight:normal;margin-right:10px;"><input type="radio" name="image" value="" checked="checked" /> <?php _e('None', 'mtphr-members'); ?></label>
				<label style="display:inline-block;font-weight:normal;margin-right:10px;"><input type="radio" name="image" value="image" /> <?php _e('Image', 'mtphr-members'); ?></label>
				<label style="display:inline-block;font-weight:normal;"><input type="radio" name="image" value="avatar" /> <?php _e('Avatar', 'mtphr-members'); ?></label>
			</div>
			<div class="mtphr-shortcode-gen-attribute">
				<label><?php _e('Class', 'mtphr-members'); ?> <small class="optional">(<?php _e('Optional', 'mtphr-members'); ?>)</small></label>
				<span class="description"><?php _e('Add custom classes to the title', 'mtphr-members'); ?></span>
				<input type="text" name="class" />
			</div>
		</div>
		<?php
		die();
	}
	add_action( 'wp_ajax_mtphr_member_twitter_gen', 'mtphr_member_twitter_gen' );
	
	
	

