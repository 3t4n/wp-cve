<?php
require_once(__DIR__ . '/shortcodes.php');

/**
 * blah blAH blaH!
 */
Class WP_Doin_Shortcodes_Generator {

	/**
	 * Store the shortcodes created
	 */
	private $shortcodes = array();

	/**
	 * Genrate an array of all shortcodes added
	 * 
	 * @return \WP_Doin_Shortcodes_Generator
	 */
	public function add_shortcode($id, $name, $description) {
		$shortcode = new WP_Doin_Shortcode( $id, $name, $description );
		$this->shortcodes[$id] = $shortcode;
		return $shortcode;
	}

	/**
	 * Hook into the media buttons and admin footer to show up the thicbkox script and add media buttons
	 */
	public function generate() {
		// make sure this is run only when allowed
		if ( !get_option( 'disable_shortcode' ) ) {
			add_action( 'media_buttons', array( $this, 'wp_doin_media_buttons' ) );
			add_action( 'admin_footer', array( $this, 'wp_doin_mce_popup' ) );
		}
	}

	/**
	 * Hook into the media buttons to show up custom media buttons
	 * @hook admin_footer
	 */
	public function wp_doin_media_buttons() {
		?>
		<style>
			.wp-core-ui a.editr_media_link {
				padding-left: 0.4em;
			}

			.label-desc {
				width: 27%;
				margin-right: 3%;
				float: left;
				font-weight: bold;
				text-align: right;
				padding-top: 2px;
			}
			.wp_doin_shortcode .content {
				float: left;
				width: 65%;
			}
			.field-container {
				margin: 5px 0;
				display: inline-block;
				width: 100%;
			}
			.field-container input[type="text"],
			.field-container textarea {
				width: 100%;
			}
			.field-container p {
				margin:0;
			}

			#TB_window {
				width:100% !important;
				top:0 !important;
				margin:0 0 !important;
				left: 0 !important;
			}

			#TB_ajaxContent {
				width:100% !important;
			}

			#TB_ajaxContent h3 {
				margin-bottom: 20px;
			}

			#TB_ajaxContent p {
				color: #777;
				font-style: italic;
			}

			.columns-3 {
				overflow: hidden;
				float: left;
				width: 30%;
				margin-right: 1%;
			}

			.wp_doin_shortcode_button {
				display:inline-block;
				margin:0;
			}
			.wp_doin_show_dropdown ul {
				display:none;
				position:absolute;
				z-index:99;
				border: 1px solid;
				padding: 10px;
				border-color: #ccc;
				background: #f7f7f7;
				-webkit-box-shadow: inset 0 1px 0 #fff,0 1px 0 rgba(0,0,0,.08);
				box-shadow: inset 0 1px 0 #fff,0 1px 0 rgba(0,0,0,.08);
			}
			.wp_doin_show_dropdown:hover ul {
				display:block;
			}

			.wp_doin_show_dropdown ul li {
				margin-bottom: 0;
				border-bottom: 1px solid #ccc;
			}

			.wp_doin_show_dropdown ul a {
				float:left;
			}

			.wp_doin_show_dropdown ul:hover {
				border-color: #999;
			}
			.wp_doin_shortcode .inner {
				border: 1px solid #eee;
				padding: 10px;
				max-height: 110px;
				overflow: auto;
				margin-top: 0;
			}

			.wp_doin_shortcode input[type="checkbox"]{
				text-align: center;
				vertical-align: middle;
				float: left;
			}

			.wp_doin_shortcode .chck-wrap {
				clear: both;
				padding: 10px 0;
			}
			.wp_doin_shortcode .chck-wrap p {
				float: left;
				padding: 0 !important;
				margin-top: -3px;
				font-style: normal !important;
				color: #444 !important;
				text-transform: capitalize;
			}

		</style>

		<?php
		// iterate over all of the fields and introduce corresponding buttons
		if ( !empty( $this->shortcodes ) ):
			add_thickbox();
			?>
			<ul class="wp_doin_shortcode_button">
				<li class="wp_doin_show_dropdown"><a href="#" class="button wp_doin_media_link">ACFRPW</a>
					<ul >
						<?php foreach ( $this->shortcodes as $name => $field ):
							?>
							<li><a href = "#TB_inline?width=900&height=1200&inlineId=<?php echo $name; ?>" class = "button thickbox wp_doin_media_link"  title = "<?php echo $field->name; ?>"><?php echo $field->name; ?></a><p><em><?php echo $field->description; ?></em></p></li>
						<?php endforeach; ?>
					</ul>
				</li>
			</ul>
		<?php endif; ?>
		<?php
	}

	/**
	 * Utility to add MCE Popup fired by custom Media Buttons button
	 * 
	 * @hook admin_footer
	 */
	public function wp_doin_mce_popup() {
		?>

		<script type="text/javascript">

			var obj = <?php echo json_encode( $this->shortcodes ); ?>;
			function InsertShortcode(name) {
				var atts = '';
				jQuery.each(obj[name]['fields'], function (key, value) {
					// get field type and specify different values for the atts as in textarea, select, checkbox, radio
					if (value['type'] === 'text') {
						var val = jQuery('.wp_doin_shortcode.' + name).find('.field-container.' + key).find('input').attr('value');
						if (val !== '') {
							atts += ' ' + key + '="' + val + '"';
						}
					}

					if (value['type'] === 'textarea') {
						var val = jQuery('.wp_doin_shortcode.' + name).find('.field-container.' + key).find('textarea').val();
						if (val !== '') {
							atts += ' ' + key + '="' + val + '"';
						}
					}

					if (value['type'] === 'checkbox') {
						var vals = '';
						var current_key = '';
						jQuery('.wp_doin_shortcode.' + name).find('.field-container.' + key).find('input:checked').each(function () {
							if (jQuery(this).is(':checked')) {
								var val = jQuery(this).val();
								vals += val + ',';
							}
						});

						if (typeof vals !== 'undefined' && vals !== '') {
							// remove the last coma instead of using the counter above
							vals = vals.replace(/,\s*$/, "");
							atts += ' ' + key + '="' + vals + '"';
						}

					}

					if (value['type'] === 'select') {
						var val = jQuery('.wp_doin_shortcode.' + name).find('.field-container.' + key).find('select option:selected').val();
						if (val !== '') {
							atts += ' ' + key + '="' + val + '"';
						}
					}

				});
				window.send_to_editor('[' + name + atts + ']');
			}
		</script>

		<?php
		/**
		 * Iterate over all of the shortcodes created and construct the thickbox triggering buttons
		 */
		foreach ( $this->shortcodes as $name => $shortcode ) :
			?>
			<div id="<?php echo $name; ?>" style="display:none;">
				<div class="wrap wp_doin_shortcode <?php echo $name; ?>">
					<div>
						<div style="overflow-y:scroll; height:650px;">
							<h3 style="color:#5A5A5A!important; font-family:Georgia,Times New Roman,Times,serif!important; font-size:1.8em!important; font-weight:normal!important;"><?php echo $shortcode->name; ?></h3>
							<p><?php echo $shortcode->description; ?></p>
							<hr />
							<?php echo $shortcode->generate_fields(); ?>
						</div>

						<hr />
						<div style="padding:40px;">
							<input type="button" class="button-primary" value="<?php echo __( 'Insert Shortcode', 'acf-recent-posts-widget' ); ?>" onclick="InsertShortcode('<?php echo $name; ?>');"/>&nbsp;&nbsp;&nbsp;
							<a class="button" href="#" onclick="tb_remove();
									return false;">Cancel</a>
						</div>
					</div>
				</div>
			</div>

		<?php endforeach; ?>

		<?php
	}

}

add_action( 'admin_head', 'acf_rpw_define_shortcode' );

/**
 * Hook into admin head and add ability to embed custom shortcodes
 * 
 * @hook admin_head
 */
function acf_rpw_define_shortcode() {
	$shortcodes = new WP_Doin_Shortcodes_Generator();

	$test = $shortcodes->add_shortcode( 'acfrpw', 'ACFRPW', 'Choose from a list of settings to create the acfrpw shortocde. Any content (shortcodes as well) can be placed in the textareas.' );

	$test
			->add_field( 'col', 'fcs', 'start' )
			->add_field( 'text', 'css', __( 'CSS Class', 'acf-recent-posts-widget' ) )
			->add_field( 'checkbox', 'is', __( 'Ignore sticky posts', 'acf-recent-posts-widget' ), '', array( 'is' => __( 'Ignore', 'acf-recent-posts-widget' ) ) )
			->add_field( 'text', 's', __( 'Search Keyword', 'acf-recent-posts-widget' ), __( 'If specified it will limit posts satisfying the search query.', 'acf-recent-posts-widget' ) )
			->add_field( 'text', 'ex', __( 'Exclude', 'acf-recent-posts-widget' ), __( 'Specify comma separated post ids.', 'acf-recent-posts-widget' ) )
			->add_field( 'checkbox', 'dd', __( 'Display Date', 'acf-recent-posts-widget' ), '', array( 'dd' => __( 'Display Date', 'acf-recent-posts-widget' ) ) )
			->add_field( 'checkbox', 'dlm', __( 'Display Modified Date', 'acf-recent-posts-widget' ), __( 'Checked - displays the last modified date of the post. Settings below apply.', 'acf-recent-posts-widget' ), array( 'dlm' => __( 'Display Modified Date', 'acf-recent-posts-widget' ) ) )
			->add_field( 'text', 'df', __( 'Date Format', 'acf-recent-posts-widget' ), __( 'Specify any custom date format - <a href = "http://codex.wordpress.org/Formatting_Date_and_Time" target = "_blank">reference</a>.', 'acf-recent-posts-widget' ) )
			->add_field( 'checkbox', 'dr', __( 'Date Relative', 'acf-recent-posts-widget' ), __( 'Checked - ignores the date format. Displays date in relateive format ex: 2 minutes ago.', 'acf-recent-posts-widget' ), array( 'dr' => __( 'Date Relative', 'acf-recent-posts-widget' ) ) )
			->add_field( 'text', 'ds', __( 'Date Start', 'acf-recent-posts-widget' ), __( 'Start date of posts to render. Posts during that day are not included.', 'acf-recent-posts-widget' ) )
			->add_field( 'text', 'de', __( 'Date End', 'acf-recent-posts-widget' ), __( 'End date of posts to render. Posts during that day are not included.', 'acf-recent-posts-widget' ) )
			->add_field( 'text', 'pass', __( 'Password', 'acf-recent-posts-widget' ), __( 'If not empty, only post with specific password will be shown.', 'acf-recent-posts-widget' ) )
			->add_field( 'checkbox', 'hp', __( 'Show password protected posts only?', 'acf-recent-posts-widget' ), '', array( 'hp' => __( 'Has Password', 'acf-recent-posts-widget' ) ) )
			->add_field( 'checkbox', 'ep', __( 'Exclude password protected posts?', 'acf-recent-posts-widget' ), __( 'Has lowest priority over the other password fields!', 'acf-recent-posts-widget' ), array( 'ep' => __( 'No Password', 'acf-recent-posts-widget' ) ) )
			->add_field( 'col', 'fce', 'end' )
			->add_field( 'col', 'tcs', 'start' )
			->add_field( 'checkbox', 'pt', __( 'Post Types', 'acf-recent-posts-widget' ), '', array_combine( get_post_types( array( 'public' => true ), 'names' ), get_post_types( array( 'public' => true ), 'names' ) ) );

// print the post formats checkboxes
	if ( current_theme_supports( 'post-formats' ) ):
		$post_formats = get_theme_support( 'post-formats' );
		if ( is_array( $post_formats[0] ) ):
			array_push( $post_formats[0], 'standard' );
			$test->add_field( 'checkbox', 'pf', __( 'Post Formats', 'acf-recent-posts-widget' ), __( 'Displays specific or multiple post formats', 'acf-recent-posts-widget' ), array_combine( $post_formats[0], $post_formats[0] ) );
		endif;
	endif;


	$test->add_field( 'checkbox', 'ps', __( 'Post Statuses', 'acf-recent-posts-widget' ), '', array_combine( get_available_post_statuses(), get_available_post_statuses() ) )
			->add_field( 'text', 'aut', __( 'Authors', 'acf-recent-posts-widget' ), __( 'Comma separated list of author ids. Ex. 1, 2, 3, 4', 'acf-recent-posts-widget' ) )
			->add_field( 'select', 'ord', __( 'Order', 'acf-recent-posts-widget' ), '', array( 'ASC' => __( 'Ascending', 'acf-recent-posts-widget' ), 'DESC' => __( 'Descending', 'acf-recent-posts-widget' ) ) )
			->add_field( 'select', 'orderby', __( 'Orderby', 'acf-recent-posts-widget' ), __( 'If meta order is specified the next field cannot be empty.', 'acf-recent-posts-widget' ), array(
				'ID' => __( 'ID', 'acf-recent-posts-widget' ),
				'author' => __( 'Author', 'acf-recent-posts-widget' ),
				'title' => __( 'Title', 'acf-recent-posts-widget' ),
				'date' => __( 'Date', 'acf-recent-posts-widget' ),
				'modified' => __( 'Modified', 'acf-recent-posts-widget' ),
				'rand' => __( 'Random', 'acf-recent-posts-widget' ),
				'comment_count' => __( 'Comment Count', 'acf-recent-posts-widget' ),
				'menu_order' => __( 'Menu Order', 'acf-recent-posts-widget' ),
				'meta_value' => __( 'Meta Value', 'acf-recent-posts-widget' ),
				'meta_value_num' => __( 'Meta Value Numeric', 'acf-recent-posts-widget' ) ) )
			->add_field( 'text', 'mk', __( 'Meta Key', 'acf-recent-posts-widget' ), __( 'Fetch only posts having the Meta Key. Required if Meta Value or Meta Value Numeric was selected above.', 'acf-recent-posts-widget' ) )
			->add_field( 'select', 'meta_compare', __( 'Meta compare', 'acf-recent-posts-widget' ), __( 'Specify the meta compare format, see CODEX and plugin documentation for further reference.', 'acf-recent-posts-widget' ), array(
				'' => __( 'None', 'acf-recent-posts-widget' ),
				' = ' => __( ' = ', 'acf-recent-posts-widget' ),
				'!=' => __( '!=', 'acf-recent-posts-widget' ),
				'&gt;' => __( '>', 'acf-recent-posts-widget' ),
				'&gt;=' => __( '>=', 'acf-recent-posts-widget' ),
				'&lt;' => __( '<', 'acf-recent-posts-widget' ),
				'&lt;=' => __( '<=', 'acf-recent-posts-widget' ),
				'LIKE' => __( 'LIKE', 'acf-recent-posts-widget' ),
				'IN' => __( 'IN', 'acf-recent-posts-widget' ),
				'NOT IN' => __( 'NOT IN', 'acf-recent-posts-widget' ),
				'BETWEEN' => __( 'BETWEEN', 'acf-recent-posts-widget' ),
				'NOT BETWEEN' => __( 'NOT BETWEEN', 'acf-recent-posts-widget' ),
				'EXISTS' => __( 'EXISTS', 'acf-recent-posts-widget' ),
				'NOT EXISTS' => __( 'NOT EXISTS', 'acf-recent-posts-widget' ),
				'REGEXP' => __( 'REGEXP', 'acf-recent-posts-widget' ),
				'NOT REGEXP' => __( 'NOT REGEXP', 'acf-recent-posts-widget' ),
				'RLIKE' => __( 'RLIKE', 'acf-recent-posts-widget' ),
			) )
			->add_field( 'text', 'meta_value', __( 'Meta Value', 'acf-recent-posts-widget' ), __( 'Specify the Meta Value to compare the key with. Leave empty for none.', 'acf-recent-posts-widget' ) )
			->add_field( 'col', 'tce', 'end' )
			->add_field( 'col', 'thcs', 'start' );

	// obtain the categories list
	$categories = array();
	if ( !is_wp_error( get_terms( 'category' ) ) ) {
		foreach ( get_terms( 'category' ) as $cat ) {
			$categories[$cat->term_id] = $cat->name;
		}
		$test->add_field( 'checkbox', 'ltc', __( 'Limit to Category', 'acf-recent-posts-widget' ), '', $categories );
	}

	// obtain the tags list
	$tags_get = get_terms( 'post_tag' );
	$tags = array();
	if ( !is_wp_error( $tags_get ) ) {
		foreach ( $tags_get as $tag ) {
			$tags[$tag->term_id] = $tag->name;
		}
		$test->add_field( 'checkbox', 'lttag', __( 'Limit to Tag', 'acf-recent-posts-widget' ), '', $tags );
	}

	$test->add_field( 'text', 'ltt', __( 'Limit to taxonomy', 'acf-recent-posts-widget' ), __( 'Ex: category=1,2,4&amp;post-tag=6,12.', 'acf-recent-posts-widget' ) )
			->add_field( 'select', 'ltto', __( 'Operator', 'acf-recent-posts-widget' ), __( '"IN" includes posts from the taxonomies, NOT IN excludes posts from these taxonomies.', 'acf-recent-posts-widget' ), array( 'IN' => __( 'IN', 'acf-recent-posts-widget' ), 'NOT IN' => __( 'NOT IN', 'acf-recent-posts-widget' ) ) )
			->add_field( 'text', 'np', __( 'Number of posts to show', 'acf-recent-posts-widget' ), __( 'Use -1 to list all posts.', 'acf-recent-posts-widget' ) )
			->add_field( 'text', 'ns', __( 'Number of posts to skip', 'acf-recent-posts-widget' ), __( 'Ignored if -1 is specified above.', 'acf-recent-posts-widget' ) );

// thumbnail related settings
	if ( current_theme_supports( 'post-thumbnails' ) ) {
		$test->add_field( 'checkbox', 'dth', __( 'Display Thumbnail', 'acf-recent-posts-widget' ), __( 'Needs to be set as post featured image', 'acf-recent-posts-widget' ), array( 'display' => __( 'Display', 'acf-recent-posts-widget' ) ) )
				->add_field( 'text', 'thh', __( 'Thumbnail Height', 'acf-recent-posts-widget' ) )
				->add_field( 'text', 'thw', __( 'Thumbnail Width', 'acf-recent-posts-widget' ) )
				->add_field( 'select', 'tha', __( 'Thumbnail Alignment', 'acf-recent-posts-widget' ), '', array(
					'acf-rpw-left' => __( 'Left', 'acf-recent-posts-widget' ),
					'acf-rpw-right' => __( 'Right', 'acf-recent-posts-widget' ),
					'acf-rpw-middle' => __( 'Middle', 'acf-recent-posts-widget' )
				) );
	}

	$test->add_field( 'text', 'dfth', __( 'Default Thumbnail', 'acf-recent-posts-widget' ), __( 'Specify full, valid image URL here. Ex: http://placehold.it/50x50/f0f0f0/ccc. All of the above apply to thumbnails but not to ACF image field type. Use CSS "acf-img" class to reference these.', 'acf-recent-posts-widget' ) )
			->add_field( 'checkbox', 'excerpt', __( 'Show excerpt', 'acf-recent-posts-widget' ), '', array( 'ignore' => __( 'Ignore', 'acf-recent-posts-widget' ) ) )
			->add_field( 'text', 'el', __( 'Excerpt Length', 'acf-recent-posts-widget' ), __( 'Limits the excerpt to specified number of words.', 'acf-recent-posts-widget' ) )
			->add_field( 'checkbox', 'rm', __( 'Display Readmore', 'acf-recent-posts-widget' ), '', array( 'display' => __( 'Readmore', 'acf-recent-posts-widget' ) ) )
			->add_field( 'text', 'rt', __( 'Readmore text', 'acf-recent-posts-widget' ), __( 'Leave empty for default "... Continue Reading" text. If full excerpt is printed, this text will not appear.', 'acf-recent-posts-widget' ) )
			->add_field( 'col', 'thce', 'end' );

	$test->add_field( 'textarea', 'before', __( 'HTML or text before each post.', 'acf-recent-posts-widget' ), __( 'You can use any HTML and meta / ACF keys here. [acf field_key] will render the corresponding ACF field\'s value. Meta can be obtained via [meta field_key].', 'acf-recent-posts-widget' ) );
	$test->add_field( 'textarea', 'after', __( 'HTML or text after each post.', 'acf-recent-posts-widget' ), __( 'You can use any HTML and meta / ACF keys here. [acf field_key] will render the corresponding ACF field\'s value. Meta can be obtained via [meta field_key].', 'acf-recent-posts-widget' ) );
	$test->add_field( 'textarea', 'before_posts', __( 'HTML or text before the whole loop.', 'acf-recent-posts-widget' ), __( 'You can use any HTML here, the markup appears after the widget container opening and after the title.', 'acf-recent-posts-widget' ) );
	$test->add_field( 'textarea', 'after_posts', __( 'HTML or text after the whole loop.', 'acf-recent-posts-widget' ), __( 'You can use any HTML here, the markup appears before the widget container closing.', 'acf-recent-posts-widget' ) );
	$test->add_field( 'checkbox', 'default_styles', __( 'Use Default Styles', 'acf-recent-posts-widget' ), '', array( 'default_styles' => __( 'Default', 'acf-recent-posts-widget' ) ) );
	$test->add_field( 'textarea', 'custom_css', __( 'Custom CSS', 'acf-recent-posts-widget' ), __( 'Disabling default CSS will let you type in any CSS here.', 'acf-recent-posts-widget' ) );

	$shortcodes->generate();
}

add_shortcode( 'acfrpw', 'shortcode_handler' );

/**
 * Add function to print the Front End shortcode
 * 
 * @shortcode acfrpw
 */
function shortcode_handler($atts, $content, $name) {
	ob_start();

	// convert the string to the required array
	if ( isset( $atts['pt'] ) )
		$atts['pt'] = explode( ',', $atts['pt'] );
	if ( isset( $atts['pf'] ) )
		$atts['pf'] = explode( ',', $atts['pf'] );
	if ( isset( $atts['ps'] ) )
		$atts['ps'] = explode( ',', $atts['ps'] );

	// add default shortcode parameters if not specified
	$query_args = ACF_Rpw_Widget::_get_query_args( $atts, get_the_ID() );
	?>
	<div class="wp-doin-shortcode-div">
		<?php
		/**
		 * Filter the arguments for the Recent Posts widget.
		 *
		 * @since 3.4.0
		 *
		 * @see WP_Query::get_posts()
		 *
		 * @param array $args An array of arguments used to retrieve the recent posts.
		 */
		$r = new WP_Query( apply_filters( 'widget_posts_args', $query_args ) );
		if ( $r->have_posts() ) {
			// enable global variables to be used in the templat functions
			global $acf_rpw_instance, $acf_rpw_args, $acf_rpw_title, $acf_rpw_id;
			$acf_rpw_id = get_the_ID();
			$acf_rpw_title = get_the_title();
			$acf_rpw_instance = $atts;
			$acf_rpw_args = array( 'before_widget' => '<div>', 'before_title' => '<h2>', 'after_title' => '</h2>', 'after_widget' => '</div>' );
			ACF_Rpw_Widget::acfrpw_get_template_part( 'loop', 'before', 'shortcode' );

			while ( $r->have_posts() ) {
				$r->the_post();
				ACF_Rpw_Widget::acfrpw_get_template_part( 'loop', 'inner', 'shortcode' );
			}

			ACF_Rpw_Widget::acfrpw_get_template_part( 'loop', 'after', 'shortcode' );

			// Reset the global $the_post as this query will have stomped on it
			wp_reset_postdata();
		}
		?>
	</div>
	<?php
	return ob_get_clean();
}
