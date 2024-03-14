<?php
class Wli_Popular_Posts extends WP_Widget {
	
	/**
	 * 
	 * Unique identifier for your widget.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * widget file.
	 *
	 * @since    1.0.1
	 *
	 * @var      string
	 */
	protected $widget_slug = 'wli_popular_posts';

	/**
	 *   Wli_Popular_Posts constructor
	 *
	 *  @since    			1.0.1
	 *
	 *  @return             void
	 *  @var                No arguments passed
	 *  @author             weblineindia
	 *
	 */
	public function __construct()
	{
		//Load options
	 	$this->options = get_option( 'wli_popular_posts_options' );

		parent::__construct(
				$this->get_widget_slug(),
				__( 'Popular Posts by Webline', 'popular-posts-by-webline' ),
				array(
					'classname'     =>  $this->get_widget_slug().'-class',
					'description'   => __( 'A Simple plugin to show the posts as per the filter applied.', 'popular-posts-by-webline' ),
				)
		);
		if ( ! class_exists( 'Walker_Category_Checklist_Widget' ) ) {
			require_once( 'walker.php' );
		}
	}

	/**
	 * get_widget_slug() is use to get the widget slug.
	 *
	 * @since     1.0.1
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_widget_slug() {
		return $this->widget_slug;
	}

	/**
	 *  form() is used to generates the administration form for the widget.
	 *
	 *  @since    			1.0.1
	 *
	 *  @return             void
	 *  @var                $instance
	 *  @author             weblineindia
	 *
	 */

	function form( $instance ) {
		$defaults = array(
				'category_name' 	 	=> 'category',
				'category' 	 	 		=> array(),
				'title'	   	 	 		=> 'Popular Posts',
				'no_posts' 	 	 		=> '3',
				'select_posttype'		=> 'post',
				'days_filter'	 		=> 'None',
				'sort_by'		 		=> 'Post Views Count',
				'display_type'			=> 'list',
				'no_comments'	 		=> 'yes',
				'views_count'	 		=> 'yes',
				'post_date'		 		=> 'yes',
				'featured_image' 		=> 'yes',
				'featured_width' 		=> '100',
				'featured_height'		=> '100',
				'featured_align'		=> 'left',
                'content'		 		=> 'yes',
				'content_length' 		=> '25',
				'readmore_text'			=> '[...]',
				'exc_curr_post' 		=> 'no',
				'relative_date' 		=> 'no',
				'wrap_desc'				=> 'no'
		);
		
		$instance		= wp_parse_args( (array) $instance, $defaults );
		
		$title			= esc_attr($instance['title']);
		$no_posts		= absint($instance['no_posts']);
		$select_posttype= esc_attr($instance['select_posttype']);
		$days_filter	= esc_attr($instance['days_filter']);
		$sort_by		= esc_attr($instance['sort_by']);
		$category_name	= esc_attr($instance['category_name']);
		$category		= ($instance['category']);
		$display_type 	= esc_attr($instance['display_type']);
		$comments 		= esc_attr($instance['no_comments']);
		$views_count 	= esc_attr($instance['views_count']);
		$post_date		= esc_attr($instance['post_date']);
		$featured_image = esc_attr($instance['featured_image']);
		$featured_width = esc_attr($instance['featured_width']);
		$featured_height= esc_attr($instance['featured_height']);
		$featured_align = esc_attr($instance['featured_align']);
		$content		= esc_attr($instance['content']);
		$content_length = absint($instance['content_length']);
		$readmore_text  = esc_attr($instance['readmore_text']);
		$exc_curr_post	= esc_attr($instance['exc_curr_post']);
		$relative_date	= esc_attr($instance['relative_date']);
		$wrap_desc		= esc_attr($instance['wrap_desc']);
		?>
		<style>
			.categorychecklist
			{
				border: 1px solid #EEE; 
				padding: 2px 0px 0px 5px; 
				max-height: 100px; 
				overflow: auto;
				margin-top:-10px;
			}
			.pp_input_box{width: 31.3337%; margin:-2% 1% 4% 0 !important; float: left;}
			.wlipp-post-category label {margin-bottom: 12px;display: inline-block;}
			.pp_show_content_input {display: inline-block;width: 100%;}
		</style>
		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'popular-posts-by-webline' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title;?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('no_posts'); ?>"><?php _e('No. of Posts to Show', 'popular-posts-by-webline' ); ?></label> 
			<input class="widefat" maxlength="4" id="<?php echo $this->get_field_id('no_posts'); ?>" name="<?php echo $this->get_field_name('no_posts'); ?>" type="text" value="<?php echo $no_posts; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('select_posttype'); ?>"><?php _e('Select Post Type', 'popular-posts-by-webline' ); ?></label> 
			<select id="<?php echo $this->get_field_id('select_posttype'); ?>" name="<?php echo $this->get_field_name('select_posttype'); ?>" class="widefat wlipp-select_posttype">
				<?php $posttypes = $this->options['wli_select_posts'];
				$allposttypes = get_post_types(array('public'   => true, '_builtin' => false), 'objects'); ?>

				<option <?php selected( $instance['select_posttype'], 'post' ); ?> value="post"><?php _e( 'Posts', $this->widget_slug );?></option>
				<?php foreach($allposttypes as $posttype) {
					if( in_array( $posttype->name, $posttypes ) ) { ?>
						<option <?php selected( $instance['select_posttype'], $posttype->name ); ?> value="<?php echo $posttype->name; ?>">
							<?php echo $posttype->label; ?>
						</option>
				<?php }
				} ?>
			</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('days_filter'); ?>"><?php _e('Show Post Added within # Days', 'popular-posts-by-webline' ); ?></label> 
			<select id="<?php echo $this->get_field_id('days_filter'); ?>" name="<?php echo $this->get_field_name('days_filter'); ?>" class="widefat">
				<?php $filterby=array( 'None', '7', '15', '30', '45' );?>
				<?php foreach($filterby as $post_type) { ?>
				<option <?php selected( $instance['days_filter'], $post_type ); ?> value="<?php echo $post_type; ?>">
					<?php echo $post_type; ?>
				</option>
				<?php } ?>
			</select>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('sort_by'); ?>"><?php _e('Sort By', 'popular-posts-by-webline' ); ?></label> 
			<select id="<?php echo $this->get_field_id('sort_by'); ?>" name="<?php echo $this->get_field_name('sort_by'); ?>" class="widefat">
				<option <?php selected( $instance['sort_by'], 'Post Views Count'); ?> value="Post Views Count"><?php _e('Post Views Count', 'popular-posts-by-webline' ); ?></option>
				<option <?php selected( $instance['sort_by'], 'Comments'); ?> value="Comments"><?php _e('Comments', 'popular-posts-by-webline' ); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('display_type'); ?>"><?php _e('Display Type', 'popular-posts-by-webline' ); ?></label><br>
			<input id="<?php echo $this->get_field_id('display_type');?>-list" type="radio" name="<?php echo $this->get_field_name('display_type'); ?>" value="list" class="widefat" <?php echo checked( 'list', $instance['display_type'], true ); ?>>
			<label for="<?php echo $this->get_field_id('display_type');?>-list"><?php _e('List', 'popular-posts-by-webline' ); ?></label>&nbsp;&nbsp;
			<input id="<?php echo $this->get_field_id('display_type');?>-slider" type="radio" name="<?php echo $this->get_field_name('display_type'); ?>" value="slider" class="widefat" <?php echo checked( 'slider', $instance['display_type'], true ); ?> >
			<label for="<?php echo $this->get_field_id('display_type');?>-slider"><?php _e('Slider', 'popular-posts-by-webline' ); ?></label>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('no_comments'); ?>"><?php _e('Show No. of Comments', 'popular-posts-by-webline' ); ?></label><br>
			<input id="<?php echo $this->get_field_id('no_comments');?>-yes" type="radio" name="<?php echo $this->get_field_name('no_comments'); ?>" value="yes" class="widefat" <?php echo checked( 'yes', $instance['no_comments'], true ); ?>>
			<label for="<?php echo $this->get_field_id('no_comments');?>-yes"><?php _e('Yes', 'popular-posts-by-webline' ); ?></label>&nbsp;&nbsp;
			<input id="<?php echo $this->get_field_id('no_comments');?>-no" type="radio" name="<?php echo $this->get_field_name('no_comments'); ?>" value="no" class="widefat" <?php echo checked( 'no', $instance['no_comments'], true ); ?> >
			<label for="<?php echo $this->get_field_id('no_comments');?>-no"><?php _e('No', 'popular-posts-by-webline' ); ?></label>&nbsp;&nbsp;
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('views_count'); ?>"><?php _e('Show Post Views Count', 'popular-posts-by-webline' ); ?></label><br>
			<input id="<?php echo $this->get_field_id('views_count');?>-yes" type="radio" name="<?php echo $this->get_field_name('views_count'); ?>" value="yes" class="widefat" <?php echo checked( 'yes', $instance['views_count'], true ); ?>>
			<label for="<?php echo $this->get_field_id('views_count');?>-yes"><?php _e('Yes', 'popular-posts-by-webline' ); ?></label>&nbsp;&nbsp;
			<input id="<?php echo $this->get_field_id('views_count');?>-no" type="radio" name="<?php echo $this->get_field_name('views_count'); ?>" value="no" class="widefat" <?php echo checked( 'no', $instance['views_count'], true ); ?> >
			<label for="<?php echo $this->get_field_id('views_count');?>-no"><?php _e('No', 'popular-posts-by-webline' ); ?></label>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('post_date'); ?>"><?php _e('Show Post Date', 'popular-posts-by-webline' ); ?></label><br>
			<input id="<?php echo $this->get_field_id('post_date');?>-yes" type="radio" name="<?php echo $this->get_field_name('post_date'); ?>" value="yes" class="widefat" <?php echo checked( 'yes', $instance['post_date'], true ); ?>>
			<label for="<?php echo $this->get_field_id('post_date');?>-yes"><?php _e('Yes', 'popular-posts-by-webline' ); ?></label>&nbsp;&nbsp;
			<input id="<?php echo $this->get_field_id('post_date');?>-no" type="radio" name="<?php echo $this->get_field_name('post_date'); ?>" value="no" class="widefat" <?php echo checked( 'no', $instance['post_date'], true ); ?> >
			<label for="<?php echo $this->get_field_id('post_date');?>-no"><?php _e('No', 'popular-posts-by-webline' ); ?></label>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('featured_image'); ?>"><?php _e('Show Featured Image', 'popular-posts-by-webline' ); ?></label><br>
			<input id="<?php echo $this->get_field_id('featured_image');?>-yes" type="radio" name="<?php echo $this->get_field_name('featured_image'); ?>" value="yes" class="widefat" <?php echo checked( 'yes', $instance['featured_image'], true ); ?>>
			<label for="<?php echo $this->get_field_id('featured_image');?>-yes"><?php _e('Yes', 'popular-posts-by-webline' ); ?></label>&nbsp;&nbsp;
			<input id="<?php echo $this->get_field_id('featured_image');?>-no" type="radio" name="<?php echo $this->get_field_name('featured_image'); ?>" value="no" class="widefat" <?php echo checked( 'no', $instance['featured_image'], true ); ?>>
			<label for="<?php echo $this->get_field_id('featured_image');?>-no"><?php _e('No', 'popular-posts-by-webline' ); ?></label>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('featured_width'); ?>"><?php _e('Featured Image (Width,Height,Align)', 'popular-posts-by-webline' ); ?></label> 
		</p>
		
		<p class="pp_input_box">
			<input class="widefat" id="<?php echo $this->get_field_id('featured_width'); ?>" name="<?php echo $this->get_field_name('featured_width'); ?>" type="text" value="<?php echo $featured_width; ?>" />
		</p>
		
		<p class="pp_input_box">
			<input class="widefat" id="<?php echo $this->get_field_id('featured_height'); ?>" name="<?php echo $this->get_field_name('featured_height'); ?>" type="text" value="<?php echo $featured_height; ?>" />
		</p>
	
		<p class="pp_input_box">
			<select id="<?php echo $this->get_field_id( 'featured_align' ); ?>" name="<?php echo $this->get_field_name( 'featured_align' ); ?>" class="widefat">
				<option value="left" <?php selected( $instance['featured_align'], 'left' ); ?>><?php _e('Left', 'popular-posts-by-webline' ); ?></option>
				<option value="right" <?php selected( $instance['featured_align'], 'right' ); ?>><?php _e('Right', 'popular-posts-by-webline' ); ?></option>
				<option value="top" <?php selected( $instance['featured_align'], 'top' ); ?>><?php _e('Top', 'popular-posts-by-webline' ); ?></option>
			</select>
		</p>
		
		<p class="pp_show_content_input">
			<label for="<?php echo $this->get_field_id('content'); ?>"><?php _e('Show Content', 'popular-posts-by-webline' ); ?></label><br>
			<input id="<?php echo $this->get_field_id('content');?>-yes" type="radio" name="<?php echo $this->get_field_name('content'); ?>" value="yes" class="widefat" <?php echo checked( 'yes', $instance['content'], true ); ?>>
			<label for="<?php echo $this->get_field_id('content');?>-yes"><?php _e('Yes', 'popular-posts-by-webline' ); ?></label>&nbsp;&nbsp;
			<input id="<?php echo $this->get_field_id('content');?>-no" type="radio" name="<?php echo $this->get_field_name('content'); ?>" value="no" class="widefat" <?php echo checked( 'no', $instance['content'], true ); ?> >
			<label for="<?php echo $this->get_field_id('content');?>-no"><?php _e('No', 'popular-posts-by-webline' ); ?></label>
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('content_length'); ?>"><?php _e('Content Length', 'popular-posts-by-webline' ); ?></label><br>
			<input class="widefat" id="<?php echo $this->get_field_id('content_length'); ?>" name="<?php echo $this->get_field_name('content_length'); ?>" type="text" value="<?php echo $content_length;?>" />
		</p>
		
		<p>
			<label for="<?php echo $this->get_field_id('readmore_text'); ?>"><?php _e('Read More Text', 'popular-posts-by-webline' ); ?></label><br>
			<input class="widefat" id="<?php echo $this->get_field_id('readmore_text'); ?>" name="<?php echo $this->get_field_name('readmore_text'); ?>" type="text" value="<?php echo $readmore_text;?>" />
		</p>
		
		<div class="wlipp-post-category" id="post" <?php echo ( empty( $select_posttype ) || $select_posttype == 'post' ) ? '' : 'style="display:none;"';?>>
			<label for="<?php echo $this->get_field_id('category'); ?>"><?php _e('Select Category', 'popular-posts-by-webline' ); ?></label>
			<?php
			$walker = new Walker_Category_Checklist_Widget($this->get_field_name('category'), $this->get_field_id('category'));
			echo '<ul class="categorychecklist">';
			wp_category_checklist( 0, 0, $instance['category'], FALSE, $walker, FALSE);
			echo '</ul>';
			?>
		</div>

		<?php 
		foreach ($allposttypes as $posttype) {
			if( (is_array($posttypes) && in_array( $posttype->name, $posttypes )) || $posttypes == $posttype->name) {
				$taxonomies = get_object_taxonomies( $posttype->name );
				foreach($taxonomies as $key => $val) { 
				    if($val == 'product_type' || $val == 'product_type' || $val == 'product_visibility' || $val == 'product_tag' || $val == 'product_shipping_class' || $val == 'pa_color' || $val == 'pa_size') { 
				        unset($taxonomies[$key]); 
				    } 
				}

				$taxonomylist = implode(',', $taxonomies); 
				?>

				<?php foreach ($taxonomies as $tax) {
					$taxonomy = get_terms( array(
						    'taxonomy' => $tax,
						    'hide_empty' => true,
					) );
					if(!empty($taxonomy)) {
							$walkertax = new Walker_Category_Checklist_Widget($this->get_field_name('category'), $this->get_field_id($tax));
							$taxonomy_details = get_taxonomy( $tax ); ?>
							<div class="wlipp-post-category" id="<?php echo $posttype->name; ?>"  <?php echo ( empty( $select_posttype ) || $select_posttype == $posttype->name ) ? '' : 'style="display:none;"';?>>
								<label><?php _e('Select ' . $taxonomy_details->label, $this->get_widget_slug()); ?></label>
								<ul class="categorychecklist">
								<?php wp_terms_checklist( 0, array('taxonomy' => $tax, 'selected_cats' => $instance['category'], 'walker' => $walkertax ) ); ?>
								</ul>
								<input class="widefat taxonomylist" type="hidden" value="<?php echo $taxonomylist; ?>" />
							</div>
					<?php }
				}
			}
		}
		?>
		<p>
			<input class="widefat wlipp-taxonomy" id="<?php echo $this->get_field_id('category_name'); ?>" name="<?php echo $this->get_field_name('category_name'); ?>" type="hidden" value="<?php if(!empty($category_name)) { echo $category_name; } ?>" />
		</p>

		<p>
			<input class="checkbox" type="checkbox" value="yes" <?php checked($instance['exc_curr_post'], 'yes'); ?> id="<?php echo $this->get_field_id('exc_curr_post'); ?>" name="<?php echo $this->get_field_name('exc_curr_post'); ?>" />
			<label for="<?php echo $this->get_field_id('exc_curr_post'); ?>"><?php _e( 'Exclude Current Post', 'popular-posts-by-webline' ); ?></label>
		</p>
		
		<p>
			<input class="checkbox" type="checkbox" value="yes" <?php checked($instance['relative_date'], 'yes'); ?> id="<?php echo $this->get_field_id('relative_date'); ?>" name="<?php echo $this->get_field_name('relative_date'); ?>" />
			<label for="<?php echo $this->get_field_id('relative_date'); ?>"><?php _e( 'Show Relative Date', 'popular-posts-by-webline' ); ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" value="yes" <?php checked($instance['wrap_desc'], 'yes'); ?> id="<?php echo $this->get_field_id('wrap_desc'); ?>" name="<?php echo $this->get_field_name('wrap_desc'); ?>" />
			<label for="<?php echo $this->get_field_id('wrap_desc'); ?>"><?php _e( '
			Wrap Anchor Link on Post Description (Excerpt)', 'popular-posts-by-webline' ); ?></label>
		</p>
		<script type="text/javascript">
			jQuery( document ).on( 'change', '.wlipp-select_posttype', function( event ) {
				var tax;
				var ele = jQuery( this ).val();
				if( ele ) {
					jQuery('.wlipp-post-category').hide();
					jQuery('.wlipp-post-category').find('input[type="checkbox"]').prop("checked", false);
					jQuery('.wlipp-post-category').find('input[type="checkbox"]').removeAttr("checked");
					jQuery('.wlipp-post-category').removeClass('active');
					jQuery('.wlipp-post-category#' + ele).show();
					jQuery('.wlipp-post-category#' + ele).addClass('active');
					tax = jQuery('.wlipp-post-category.active').find('.widefat').val();
					jQuery('input.wlipp-taxonomy').val(tax);
				} else {
					jQuery('.wlipp-post-category').hide();
					jQuery('.wlipp-post-category').removeClass('active');
				}
			});
		</script>
	<?php
	}

	/**
	 *  update() is used to replace the new value when the Saved button is clicked.
	 *
	 *  @since    			1.0.1
	 *
	 *  @return             $instance
	 *  @var                $new_instance,$old_instance
	 *  @author             weblineindia
	 *
	 */
	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;
		$instance['title']			= sanitize_text_field($new_instance['title']);
		$instance['no_posts'] 		= sanitize_text_field($new_instance['no_posts']);
		$instance['select_posttype']= sanitize_text_field($new_instance['select_posttype']);
		$instance['days_filter']	= sanitize_text_field($new_instance['days_filter']);
		$instance['sort_by']		= sanitize_text_field($new_instance['sort_by']);
		$instance['category_name']		= isset($new_instance['category_name'])?sanitize_text_field($new_instance['category_name']) :'category';
		$instance['category']		= isset($new_instance['category'])?($new_instance['category']) :array();
		$instance['display_type']	= isset($new_instance['display_type'])?sanitize_text_field($new_instance['display_type']) :'list';
		$instance['no_comments']	= isset($new_instance['no_comments'])?sanitize_text_field($new_instance['no_comments']) :'list';
		$instance['views_count']	= isset($new_instance['views_count'])?sanitize_text_field($new_instance['views_count']) :'yes';
		$instance['post_date']		= isset($new_instance['post_date'])?sanitize_text_field($new_instance['post_date']) :'yes';
		$instance['featured_image'] = isset($new_instance['featured_image'])?sanitize_text_field($new_instance['featured_image']) :'yes';
		$instance['featured_width']	= sanitize_text_field($new_instance['featured_width']);
		$instance['featured_height']= sanitize_text_field($new_instance['featured_height']);
		$instance['featured_align'] = sanitize_text_field($new_instance['featured_align']);
		$instance['content']		= isset($new_instance['content'])?sanitize_text_field($new_instance['content']) :'yes';
		$instance['content_length']	= sanitize_text_field($new_instance['content_length']);
		$instance['readmore_text']	= sanitize_text_field($new_instance['readmore_text']);
		$instance['exc_curr_post']	= isset($new_instance['exc_curr_post'])?sanitize_text_field($new_instance['exc_curr_post']) :'no';
		$instance['relative_date']	= isset($new_instance['relative_date'])?sanitize_text_field($new_instance['relative_date']) :'no';
		$instance['wrap_desc']		= isset($new_instance['wrap_desc'])?sanitize_text_field($new_instance['wrap_desc']) :'no';
		return $instance;
	}
	
	/**
	 *  time_ago() is used to convert date to time duration.
	 *
	 *  @since    			1.0.3
	 *
	 *  @return             $since
	 *  @var                $from,$to
	 *  @author             Weblineindia
	 *
	 */
	public function time_ago( $from, $to = '' ) {
		if ( empty( $to ) ) {
			$to = time();
		}
	
		$diff = (int) abs( $to - $from );
	
		if ( $diff < HOUR_IN_SECONDS ) {
			$mins = round( $diff / MINUTE_IN_SECONDS );
			if ( $mins <= 1 )
				$mins = 1;
			$since = sprintf( _n( '%s min', '%s mins', $mins ), $mins );
		} elseif ( $diff < DAY_IN_SECONDS && $diff >= HOUR_IN_SECONDS ) {
			$hours = round( $diff / HOUR_IN_SECONDS );
			if ( $hours <= 1 )
				$hours = 1;
			$since = sprintf( _n( '%s hour', '%s hours', $hours ), $hours );
		} elseif ( $diff < WEEK_IN_SECONDS && $diff >= DAY_IN_SECONDS ) {
			$days = round( $diff / DAY_IN_SECONDS );
			if ( $days <= 1 )
				$days = 1;
			$since = sprintf( _n( '%s day', '%s days', $days ), $days );
		} elseif ( $diff < MONTH_IN_SECONDS && $diff >= WEEK_IN_SECONDS ) {
			$weeks = round( $diff / WEEK_IN_SECONDS );
			if ( $weeks <= 1 )
				$weeks = 1;
			$since = sprintf( _n( '%s week', '%s weeks', $weeks ), $weeks );
		} elseif ( $diff < YEAR_IN_SECONDS && $diff >= MONTH_IN_SECONDS ) {
			$months = round( $diff / MONTH_IN_SECONDS );
			if ( $months <= 1 )
				$months = 1;
			$since = sprintf( _n( '%s month', '%s months', $months ), $months );
		} elseif ( $diff >= YEAR_IN_SECONDS ) {
			$years = round( $diff / YEAR_IN_SECONDS );
			if ( $years <= 1 )
				$years = 1;
			$since = sprintf( _n( '%s year', '%s years', $years ), $years );
		}
	
		return $since." ago";
	}
	
	/**
	 * widget() is used to show the frontend part .
	 *
	 *  @since    1.0.1
	 *
	 *  @return             void
	 *  @var                $args,$instance
	 *  @author             weblineindia
	 *
	 */
	function widget($args, $instance) {
	
		global $content_length,$readmore_text;
		
		extract( $args,EXTR_SKIP);
	
		wp_enqueue_style( 'popularposts-style', PP_URL . '/admin/assets/css/popular-posts-style.css', array(), WLIPOPULARPOSTS_VERSION );
		
		$title = apply_filters( 'widget_title', $instance['title'] );
		
		echo $before_widget;
	
		echo $before_title . $title . $after_title;

		$wliPopularPostsObj = new wliPopularPosts();

		echo $wliPopularPostsObj->wli_show_popular_posts_callback( $instance );

		echo $after_widget;
	}

}

$options = get_option( 'wli_popular_posts_options' );

if( !empty( $options['wli_enable_pp'] ) && $options['wli_enable_pp'] == '1') {

	add_action( 'widgets_init', 'register_wli_popular_posts_widget' );
	add_action( 'wp_head', 'wli_popular_posts_track_post_views' );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );

}

function register_wli_popular_posts_widget() {
    register_widget( 'Wli_Popular_Posts' );
}

function wli_popular_posts_set_post_views($postID) {
	$count_key = 'wli_pp_post_views_count';
	$count = intval(get_post_meta($postID, $count_key, true));
	if($count==''){
		$count = 1;
		delete_post_meta($postID, $count_key);
		add_post_meta($postID, $count_key, '1');
	}else{
		$count++;
		update_post_meta($postID, $count_key, $count);
	}
}

function wli_popular_posts_track_post_views ($post_id) {
	$options = get_option( 'wli_popular_posts_options' );

	if ( !is_single() ) return;

	if ( empty ( $post_id ) ) {
		global $post;
		$post_id = $post->ID;
		$cur_posttype = get_post_type( $post_id );
	}

	if( in_array( $cur_posttype, $options['wli_select_posts'] ) ) {
		wli_popular_posts_set_post_views($post_id);
	}
}

function wli_popular_posts_get_post_views($postID){
	$count_key = 'wli_pp_post_views_count';
	$count = intval(get_post_meta($postID, $count_key, true));
	if($count==''){
		delete_post_meta($postID, $count_key);
		add_post_meta($postID, $count_key, '0');
		return "0";
	}
	return $count;
}