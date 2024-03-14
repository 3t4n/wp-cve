<p>
<div class="acf-rpw-columns-3">
	<?php
	echo parent::gti( 'tu', __( 'Title', 'acf-recent-posts-widget' ) );
	echo parent::gti( 'css', __( 'CSS Class', 'acf-recent-posts-widget' ) );
	echo parent::gtc( 'is', __( 'Ignore sticky posts', 'acf-recent-posts-widget' ), array( 'ignore' => __( 'Ignore', 'acf-recent-posts-widget' ) ) );
	echo parent::gti( 's', __( 'Search Keyword', 'acf-recent-posts-widget' ), __( 'If specified it will limit posts satisfying the search query.', 'acf-recent-posts-widget' ) );
	echo parent::gti( 'ex', __( 'Exclude', 'acf-recent-posts-widget' ), __( 'Specify comma separated post ids.', 'acf-recent-posts-widget' ) );
	echo parent::gtc( 'dd', __( 'Display Date', 'acf-recent-posts-widget' ), array( __( 'Display Date', 'acf-recent-posts-widget' ) ) );
	echo parent::gtc( 'dlm', __( 'Display Modified Date', 'acf-recent-posts-widget' ), array( __( 'Display Modified Date', 'acf-recent-posts-widget' ) ), __( 'Checked - displays the last modified date of the post. Settings below apply.', 'acf-recent-posts-widget' ) );
	echo parent::gti( 'df', __( 'Date Format', 'acf-recent-posts-widget' ), __( 'Specify any custom date format - <a href="http://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">reference</a>.', 'acf-recent-posts-widget' ) );
	echo parent::gtc( 'dr', __( 'Date Relative', 'acf-recent-posts-widget' ), array( 'Date Relative' ), __( 'Checked - ignores the date format. Displays date in relateive format ex: 2 minutes ago.', 'acf-recent-posts-widget' ) );
	echo parent::gti( 'ds', __( 'Date Start', 'acf-recent-posts-widget' ), __( 'Start date of posts to render. Posts during that day are not included.', 'acf-recent-posts-widget' ), 'picker' );
	echo parent::gti( 'de', __( 'Date End', 'acf-recent-posts-widget' ), __( 'End date of posts to render. Posts during that day are not included.', 'acf-recent-posts-widget' ), 'picker' );
	echo parent::gti( 'pass', __( 'Password', 'acf-recent-posts-widget' ), __( 'If not empty, only post with specific password will be shown.', 'acf-recent-posts-widget' ) );
	echo parent::gtc( 'hp', __( 'Show password protected posts only?', 'acf-recent-posts-widget' ), array( __( 'Has Password', 'acf-recent-posts-widget' ) ) );
	echo parent::gtc( 'ep', __( 'Exclude password protected posts?', 'acf-recent-posts-widget' ), array( __( 'No Password', 'acf-recent-posts-widget' ) ), __( 'Has lowest priority over the other password fields!', 'acf-recent-posts-widget' ) );
	// not needed without specific time echo parent::gtc(   'di', 'Date Inclusive', 'If set includes the start and end posts in the loop.' , array( 'include' => 'include' ) );
	?>
</div>

<div class="acf-rpw-columns-3">

	<?php
	// print the Post Types Checkboxes
	echo parent::gtc( 'pt', __( 'Post Types', 'acf-recent-posts-widget' ), array_combine( get_post_types( array( 'public' => true ), 'names' ), get_post_types( array( 'public' => true ), 'names' ) ) );

	// print the post formats checkboxes
	if ( current_theme_supports( 'post-formats' ) ):
		$post_formats = get_theme_support( 'post-formats' );
		if ( is_array( $post_formats[0] ) ):
			array_push( $post_formats[0], 'standard' );
			echo parent::gtc( 'pf', __( 'Post Formats', 'acf-recent-posts-widget' ), array_combine( $post_formats[0], $post_formats[0] ), __( 'Displays specific or multiple post formats', 'acf-recent-posts-widget' ) );
		endif;
	endif;

	$stati = wp_count_posts( 'post' );
	$statuses = array_keys( get_object_vars( $stati ) );
	// print the post statuses
	echo parent::gtc( 'ps', __( 'Post Statuses', 'acf-recent-posts-widget' ), array_combine( $statuses, $statuses ) );
        
	// allow inputting authors
	echo parent::gti( 'aut', __( 'Authors', 'acf-recent-posts-widget' ), __( 'Comma separated list of author ids. Ex. 1,2,3,4', 'acf-recent-posts-widget' ) );
	echo parent::gts( 'ord', __( 'Order', 'acf-recent-posts-widget' ), array( 'ASC' => __( 'Ascending', 'acf-recent-posts-widget' ), 'DESC' => __( 'Descending', 'acf-recent-posts-widget' ) ) );
	?>
	<?php
	echo parent::gts( 'orderby', __( 'Orderby', 'acf-recent-posts-widget' ), array(
		'ID' => __( 'ID', 'acf-recent-posts-widget' ),
		'author' => __( 'Author', 'acf-recent-posts-widget' ),
		'title' => __( 'Title', 'acf-recent-posts-widget' ),
		'date' => __( 'Date', 'acf-recent-posts-widget' ),
		'modified' => __( 'Modified', 'acf-recent-posts-widget' ),
		'rand' => __( 'Random', 'acf-recent-posts-widget' ),
		'comment_count' => __( 'Comment Count', 'acf-recent-posts-widget' ),
		'menu_order' => __( 'Menu Order', 'acf-recent-posts-widget' ),
		'meta_value' => __( 'Meta Value', 'acf-recent-posts-widget' ),
		'meta_value_num' => __( 'Meta Value Numeric', 'acf-recent-posts-widget' ) ), __( 'If meta order is specified the next field cannot be empty.', 'acf-recent-posts-widget' ) );
	echo parent::gti( 'mk', __( 'Meta Key', 'acf-recent-posts-widget' ), __( 'Fetch only posts having the Meta Key. Required if Meta Value or Meta Value Numeric was selected above.', 'acf-recent-posts-widget' ) );
	echo parent::gts( 'meta_compare', __( 'Meta compare', 'acf-recent-posts-widget' ), array(
		'' => __( 'None', 'acf-recent-posts-widget' ),
		'=' => __( '=', 'acf-recent-posts-widget' ),
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
			), __( 'Specify the meta compare format, see CODEX and plugin documentation for further reference.', 'acf-recent-posts-widget' ) );

	echo parent::gti( 'meta_value', __( 'Meta Value', 'acf-recent-posts-widget' ), __( 'Specify the Meta Value to compare the key with. Leave empty for none.', 'acf-recent-posts-widget' ) );
	//echo parent::gt( 'mq', __( 'Meta Query.', 'acf-recent-posts-widget' ), __( 'See plugin documentation for further reference.', 'acf-recent-posts-widget' ) );
	?>
</div>

<div class="acf-rpw-columns-3 acf-rpw-column-last">

	<?php
	// obtain the categories list
	$categories = array();
	foreach ( get_terms( 'category' ) as $cat ) {
		$categories[$cat->term_id] = $cat->name;
	}
	echo parent::gtc( 'ltc', __( 'Limit to Category', 'acf-recent-posts-widget' ), $categories );
	?>

	<?php
	// obtain the categories list
	$tags_get = get_terms( 'post_tag' );
	$tags = array();
	if ( !is_wp_error( $tags_get ) ) {
		foreach ( $tags_get as $tag ) {
			$tags[$tag->term_id] = $tag->name;
		}
	}

	echo parent::gtc( 'lttag', __( 'Limit to Tag', 'acf-recent-posts-widget' ), $tags );
	echo parent::gti( 'ltt', __( 'Limit to taxonomy', 'acf-recent-posts-widget' ), __( 'Ex: category=1,2,4&amp;post-tag=6,12.', 'acf-recent-posts-widget' ) );
	echo parent::gts( 'ltto', __( 'Operator', 'acf-recent-posts-widget' ), array( 'IN' => __( 'IN', 'acf-recent-posts-widget' ), 'NOT IN' => __( 'NOT IN', 'acf-recent-posts-widget' ) ), __( '"IN" includes posts from the taxonomies, NOT IN excludes posts from these taxonomies.', 'acf-recent-posts-widget' ) );
	?>

	<?php echo parent::gti( 'np', __( 'Number of posts to show', 'acf-recent-posts-widget' ), __( 'Use -1 to list all posts.', 'acf-recent-posts-widget' ) ); ?>
	<?php echo parent::gti( 'ns', __( 'Number of posts to skip', 'acf-recent-posts-widget' ), __( 'Ignored if -1 is specified above.', 'acf-recent-posts-widget' ) ); ?>
	<?php
	// thumbnail related settings
	if ( current_theme_supports( 'post-thumbnails' ) ) {
		?>
		<div class="small">
			<?php
			parent::gtc( 'dth', __( 'Display Thumbnail', 'acf-recent-posts-widget' ), array( 'display' => __( 'Display', 'acf-recent-posts-widget' ) ), __( 'Needs to be set as post featured image.', 'acf-recent-posts-widget' ) );
			parent::gti( 'thh', __( 'Thumbnail Height', 'acf-recent-posts-widget' ) );
			parent::gti( 'thw', __( 'Thumbnail Width', 'acf-recent-posts-widget' ) );
			parent::gts( 'tha', __( 'Thumbnail Alignment', 'acf-recent-posts-widget' ), array(
				'acf-rpw-left' => __( 'Left', 'acf-recent-posts-widget' ),
				'acf-rpw-right' => __( 'Right', 'acf-recent-posts-widget' ),
				'acf-rpw-middle' => __( 'Middle', 'acf-recent-posts-widget' )
					)
			);
			?>
		</div>
		<?php
		parent::gti( 'dfth', __( 'Default Thumbnail', 'acf-recent-posts-widget' ), __( 'Specify full, valid image URL here. Ex: http://placehold.it/50x50/f0f0f0/ccc. All of the above apply to thumbnails but not to ACF image field type. Use CSS "acf-img" class to reference these.', 'acf-recent-posts-widget' ) );
		?>

	<?php } ?>
	<?php
	echo parent::gtc( 'excerpt', __( 'Show excerpt', 'acf-recent-posts-widget' ), array( 'ignore' => __( 'Ignore', 'acf-recent-posts-widget' ) ) );
	echo parent::gti( 'el', __( 'Excerpt Length', 'acf-recent-posts-widget' ), __( 'Limits the excerpt to specified number of words.', 'acf-recent-posts-widget' ) );
	echo parent::gtc( 'rm', __( 'Display Readmore', 'acf-recent-posts-widget' ), array( __( 'Readmore', 'acf-recent-posts-widget' ) ) );
	?>
	<?php echo parent::gti( 'rt', __( 'Readmore text', 'acf-recent-posts-widget' ), __( 'Leave empty for default "... Continue Reading" text. If full excerpt is printed, this text will not appear.', 'acf-recent-posts-widget' ) ); ?>
</div>

<div class="clear"></div>
<div class="acf-rpw-block">
	<?php
	echo parent::gt( 'before', __( 'HTML or text before each post.', 'acf-recent-posts-widget' ), __( 'You can use any HTML and meta / ACF keys here. [acf field_key] will render the corresponding ACF field\'s value. Meta can be obtained via [meta field_key].', 'acf-recent-posts-widget' ) );
	echo parent::gt( 'after', __( 'HTML or text after each post.', 'acf-recent-posts-widget' ), __( 'You can use any HTML and meta / ACF keys here. [acf field_key] will render the corresponding ACF field\'s value. Meta can be obtained via [meta field_key].', 'acf-recent-posts-widget' ) );

	echo parent::gt( 'before_posts', __( 'HTML or text before the whole loop.', 'acf-recent-posts-widget' ), __( 'You can use any HTML here, the markup appears after the widget container opening and after the title.', 'acf-recent-posts-widget' ) );
	echo parent::gt( 'after_posts', __( 'HTML or text after the whole loop.', 'acf-recent-posts-widget' ), __( 'You can use any HTML here, the markup appears before the widget container closing.', 'acf-recent-posts-widget' ) );
	echo parent::gt( 'no_posts', __( 'HTML or text for no posts found matching the query criteria.', 'acf-recent-posts-widget' ), __( 'You can use any HTML here, the markup appears for no posts found.', 'acf-recent-posts-widget' ) );
	echo parent::gtc( 'default_styles', __( 'Use Default Styles', 'acf-recent-posts-widget' ), array( 'default' ) );
	echo parent::gt( 'custom_css', __( 'Custom CSS', 'acf-recent-posts-widget' ), __( 'Disabling default CSS will let you type in any CSS here.', 'acf-recent-posts-widget' ) );
	?>
</div>
</p>