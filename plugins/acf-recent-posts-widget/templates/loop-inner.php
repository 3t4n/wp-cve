<?php
/*
 * @author WP doin
 * @package acf-recent-posts-widget/templates
 * @version 4.3
 * 
 * Don't remove these 3 lines, they contain the variables passed over to the query
 * 
 * @param MIXED / ARRAY_A $acf_rpw_instance contains the query arguments which correspond to the widget saved values from the database
 * @param MIXED / ARRAY_A $acf_rpw_args contains the arguments of the widget object, for instance ['widget_id'] can reference any particular widget from the sidebar
 * @param int $acf_rpw_id references the id_base of the widget
 */
global $acf_rpw_instance, $acf_rpw_args, $acf_rpw_id;

// extract all of the query arguments, available variable names are here
// text_fields = array( 'css', 'tu', 'ex', 's', 'df', 'ds', 'de', 'aut', 'mk', 'meta_value', 'ltt', 'np', 'ns', 'thh', 'thw', 'dfth', 'el', 'rt', 'pass' );
// text_areas = array( 'before', 'after', 'before_posts', 'after_posts', 'custom_css', /* 'mq' */ );
// checkboxes = array( 'is', 'ds', /* not needed without specific time'di', */ 'dd', 'dlm', 'dr', 'dth', 'pt', 'pf', 'ps', 'ltc', 'lttag', 'excerpt', 'rm', 'default_styles', 'hp', 'ep' );
// select_fields = array( 'ord', 'orderby', 'ltto', 'tha', 'meta_compare' );

extract( $acf_rpw_instance );
$args = $acf_rpw_args;
?>
<li class="acf-rpw-li acf-rpw-clearfix">
	<?php
	/**
	 * Print the attached post thumbnail
	 * @param bool $dth whether or not we want to print the thumbnail
	 */
	if ( has_post_thumbnail() and isset( $dth ) and isset( $thw ) and isset( $thh ) ):
		?>
		<a class="acf-rpw-img" rel="bookmark" href="<?php the_permalink(); ?>">
			<?php
			$thumb_id = get_post_thumbnail_id(); // Get the featured image id.
			$img_url = wp_get_attachment_url( $thumb_id ); // Get img URL.
			// crop the image with the resizer class
			$image = acf_rpwe_resize( $img_url, $thw, $thh, true );
			if ( $image ):
				?>
				<image src="<?php echo esc_url( $image ); ?>" class="<?php echo esc_attr( $tha ); ?> acf-rpw-thumb" />
				<?php
			else :
				echo get_the_post_thumbnail( get_the_ID(), array( $thw, $thh ), array(
					'class' => $tha . ' acf-rpw-thumb',
					'alt' => esc_attr( get_the_title() )
						)
				);
			endif;
			?>
		</a>
		<?php
	/*
	 * Display the default thumbnail if specified
	 * @param string $dfth url to the default thumbnail
	 */
	elseif ( isset( $dfth ) and ! empty( $dfth ) ):
		?>
		<a class="acf-rpw-img" rel="bookmark">
			<image src="<?php echo esc_url( $dfth ); ?>" class="<?php echo esc_attr( $tha ); ?> acf-rpw-thumb" />
		</a>
	<?php endif; ?>

	<h3 class="acf-rpw-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php get_the_title() ? the_title() : the_ID(); ?></a></h3>

	<?php
	/*
	 * Show the date if specified
	 * @param bool $dd display date
	 */
	if ( isset( $dd ) ):
		?>
		<time class="acf-rpw-time published" datetime="<?php echo get_the_date( 'c' ); ?>"><?php
			/**
			 * Display the relateive date if specified
			 * @param bool $dr
			 */
			if ( isset( $dr ) ):
				/*
				 * Display last modified date if checked
				 * @param bool $dlm
				 */
				if ( isset( $dlm ) ) {
					echo sprintf( __( '%s ago', 'acf-recent-posts-widget' ), human_time_diff( get_the_modified_date( 'U' ), current_time( 'timestamp' ) ) );
				} else {
					echo sprintf( __( '%s ago', 'acf-recent-posts-widget' ), human_time_diff( get_the_date( 'U' ), current_time( 'timestamp' ) ) );
				}
			else:
				if ( isset( $dlm ) ) {
					/**
					 * @param string $df date format specified
					 */
					the_modified_time( isset( $df ) ? $df : ''  );
				} else {
					the_time( isset( $df ) ? $df : ''  );
				}
			endif;
			?></time>
		<?php
	endif;

	// before each post
	if ( !empty( $before ) ):
		?>
		<div class="acf-rpw-before">
			<?php
			/*
			 * Filter the contents of the after textarea to allow custom ACF and Meta Fields be used
			 * @hooked af_bf_content_filter
			 */
			echo apply_filters( 'acp_rwp_before', htmlspecialchars_decode( $before ), $acf_rpw_instance, $acf_rpw_id );
			?>
		</div>
		<?php
	endif;
	// optionally print the excerpt
	if ( !isset( $excerpt ) ):
		/*
		 * Display the excerpt if custom excerpt length was specified
		 * @param int $el amount of excerpt words to display
		 */
		if ( isset( $el ) and ! empty( $el ) and is_numeric( $el ) ) {
			// TODO: does it work properly
			ACF_Rpw_Widget::$el = $el;
			/*
			 * Filter the length of the excerpt
			 * @hooked excerpt_length
			 */
			add_filter( 'excerpt_length', array( 'ACF_Rpw_Widget', 'excerpt_length' ), 999 );
		}
		/*
		 * Display the readmore text if specified
		 * @param bool $rm whether or not to display the readmore
		 * @param string $rt read more text
		 */
		if ( isset( $rm ) ) {
			if ( isset( $rt ) and ! empty( $rt ) ) {
				ACF_Rpw_Widget::$rt = $rt;
				// make sure custom filter is hooked and not default excerpt is used
				/*
				 * Filter more of the excerpt
				 * @hooked excerpt_more
				 */
				add_filter( 'excerpt_more', array( 'ACF_Rpw_Widget', 'excerpt_more' ), 999 );
			}
		} else {
			/*
			 * Hide the excerpt
			 * @hooked excerpt_length
			 */
			add_filter( 'excerpt_more', array( 'ACF_Rpw_Widget', 'excerpt_more' ), 999 );
		}
		?>
		<div class="acf-rpw-excerpt">
			<?php
			// display the excerpt
			echo the_excerpt();
			?>
		</div>
		<?php
		// remove custom excerpt length plugin filter
		if ( isset( $el ) and ! empty( $el ) and is_numeric( $el ) ) {
			remove_filter( 'excerpt_length', array( 'ACF_Rpw_Widget', 'excerpt_length' ), 999 );
		}
		// remove custom excerpt more plugin filter
		remove_filter( 'excerpt_more', array( 'ACF_Rpw_Widget', 'excerpt_more' ), 999 );
	endif;
	// after each post
	if ( !empty( $after ) ):
		?>
		<div class="acf-rpw-after"> 
			<?php
			/**
			 * Filter the contents of the after textarea to allow custom ACF and Meta Fields be used
			 * @hooked af_bf_content_filter
			 */
			echo apply_filters( 'acp_rwp_after', htmlspecialchars_decode( $after ), $acf_rpw_instance, $acf_rpw_id );
			?>
		</div>
		<?php
	endif;
	?>
</li>