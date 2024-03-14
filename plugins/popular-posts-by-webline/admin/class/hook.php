<?php
include ('popular-posts.php');
class wliPopularPosts {

	private $widget_slug = 'wli_popular_posts';

	private $plugin_options_key = 'wli-popular-posts-by-webline';

	private $options;
	
	public function __construct() {

		//Load options
	 	$this->options = get_option( 'wli_popular_posts_options' );

	 	//Action to load settings
		add_action( 'admin_init', array( $this, 'wli_popular_posts_settings_init' ) );

		//Activation & Deactivation hook
		register_activation_hook(PP_PATH . PP_PLUGIN_FILE, array($this,'wli_popular_posts_default_option_value'));
		register_uninstall_hook(PP_PATH . PP_PLUGIN_FILE,array(__CLASS__,'wli_popular_posts_delete_option_value'));

		//Action fire when plugin upgrade
		add_action('upgrader_process_complete', array($this, 'wli_popular_posts_default_option_value'), 10, 2);

		//Action for enqueue scripts for admin
		add_action( 'admin_enqueue_scripts', array( $this, 'wli_enqueue_popular_posts_admin_styles_scripts' ) );

		//Action for enqueue scripts for public
		add_action( 'wp_enqueue_scripts', array( $this, 'wli_enqueue_popular_posts_custom_styles' ) ); 

		//Action to add admin menu pages
		add_action( 'admin_menu', array( $this, 'wli_register_popular_posts_menu_page' ) );

		//Action to add shortcode
		add_shortcode( 'wli_popular_posts', array( $this, 'wli_add_popular_posts_shortcode' ) );

		//Get all post types
		$wli_post_types = !empty($this->options['wli_select_posts']) ? $this->options['wli_select_posts'] : array( 'post' );

		//Loop for valid post types
		foreach ( $wli_post_types as $post_type ) {

			// Filter to add custom columns
			add_filter( "manage_{$post_type}_posts_columns", array( $this,'custom_post_type_columns' ), 10, 1 );

			// Action to display custom column value
			add_action( "manage_{$post_type}_posts_custom_column", array( $this,'custom_post_type_column_value' ), 10, 2 );
		}

		//Action to add inline style
		if( !empty( $_GET['post_type'] ) && in_array( $_GET['post_type'], $wli_post_types ) ) {
			add_action( 'admin_head', array( $this, 'custom_admin_inline_style' ) );
		}

		// Admin footer text.
		add_filter( 'admin_footer_text', array( $this, 'wli_popular_posts_admin_footer' ), 1, 2 );

		add_action('admin_notices',array( $this, 'ppbw_admin_notice_callback'));

		add_filter('pre_set_site_transient_update_plugins', array( $this, 'update_ppbw_plugin'));
		add_action('wp_logout', array( $this, 'ppbw_clear_cookie'));
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
	 *  wli_popular_posts_default_option_value() is called when the plugin is activated.
	 *
	 *  @return             void
	 *  @var                No arguments passed
	 *  @author             Weblineindia
	 *
	 */
	public function wli_popular_posts_default_option_value() {

		//Check existing plugin version
		$plugin_opts = get_option( 'wli_popular_posts_settings' );
		if( !empty( $plugin_opts['version'] ) && version_compare( $plugin_opts['version'], '1.0.5', '<=' ) ) {

			//Update default settings values
			$options = get_option( 'wli_popular_posts_options' );
			$settings = array(
			    'wli_enable_pp' 	=> '1',
			    'wli_select_posts'	=> !empty( $options['wli_select_posts'] ) ? $options['wli_select_posts'] : array(),
			    'wli_popular_posts_postview_icon'	=> !empty( $options['wli_popular_posts_postview_icon'] ) ? $options['wli_popular_posts_postview_icon'] : '',
			    'wli_popular_posts_postcomment_icon'=> !empty( $options['wli_popular_posts_postcomment_icon'] ) ? $options['wli_popular_posts_postcomment_icon'] : '',
			    'wli_popular_posts_postdate_icon'	=> !empty( $options['wli_popular_posts_postdate_icon'] ) ? $options['wli_popular_posts_postdate_icon'] : ''
			);
			update_option( 'wli_popular_posts_options', $settings );
		}

		//Update current plugin version
		$default_values=array(
			'version' => WLIPOPULARPOSTS_VERSION,
		);
		update_option( 'wli_popular_posts_settings', $default_values );
		update_option('ppbw_activation_date', time());
	}

	/* Check update hook Start */
	function update_ppbw_plugin($transient)
	{
		if (empty($transient->checked)) {
			return $transient;
		}
		$plugin_folder = plugin_basename(__FILE__);
		if (isset($transient->checked[$plugin_folder])) {
			update_option('ppbw_activation_date', time());
		}
		return $transient;
	}   
	/* Check update hook End */

	/**
	 *   wli_popular_posts_delete_option_value() is called when when the plugin is deleted.
	 *
	 *  @return             void
	 *  @var                No arguments passed
	 *  @author             Weblineindia
	 *
	 */
	public static function wli_popular_posts_delete_option_value() {
		//delete_option('wli_popular_posts_settings');
	}

	//Add plugin settings page link
	public function wli_add_popular_posts_settings_link( $links ) {
		$links[] = '<a href="'. admin_url( 'options-general.php?page=wli-popular-posts-by-webline' ) .
			'">'. __( 'Settings', 'popular-posts-by-webline' ) .'</a>';
		return $links;
	}

	/* Function to register admin stylesheets and scripts */
	public function wli_enqueue_popular_posts_admin_styles_scripts( $hook ) {

		//Check pages where scripts needed
		if( $hook == 'settings_page_wli-popular-posts-by-webline' ) {

			if (get_bloginfo('version') >= 3.5)
				wp_enqueue_media();
			else {
				wp_enqueue_style('thickbox');
				wp_enqueue_script('thickbox');
			}

			// Add the color picker CSS file       
	        wp_enqueue_style( 'wp-color-picker' );

	        // Add the color picker JS file       
	        wp_enqueue_script( 'wp-color-picker' );

			wp_enqueue_script( 'popular-posts-script', PP_URL . '/admin/assets/js/popular-posts-by-webline-admin.js', array('jquery'), WLIPOPULARPOSTS_VERSION, true );
			wp_localize_script( 'popular-posts-script', 'WLIPP_ScriptsData', array(
	        	'ajaxurl' => admin_url( 'admin-ajax.php' ),
	        	'choose_image_title' => __( 'Choose an image', $this->get_widget_slug() ),
	        	'use_image_btn_text' => __( 'Use image', $this->get_widget_slug() ),
	        ));
		}

		// Enqueue admin notices JS
		wp_enqueue_script('ppbw-js', PP_URL . '/admin/assets/js/ppbw-admin-noitces.js', array('jquery'), '1.0', true);
		// Enqueue admin notices CSS
		wp_enqueue_style( 'ppbw-css', PP_URL . '/admin/assets/css/ppbw-admin-noitces.css', array(), WLIPOPULARPOSTS_VERSION );
	}

	/* Function to register public custom stylesheets */
	public function wli_enqueue_popular_posts_custom_styles() {

		//Enqueue slick scripts
		wp_enqueue_style( 'slick-min', PP_URL .'/admin/assets/css/slick.css', array(), WLIPOPULARPOSTS_VERSION );
		wp_enqueue_style( 'slick-theme', PP_URL .'/admin/assets/css/slick-theme.css', array(), WLIPOPULARPOSTS_VERSION );

		//Enqueue public style
		wp_enqueue_style( 'popular-posts-style', PP_URL . '/admin/assets/css/popular-posts-style.css', array(), WLIPOPULARPOSTS_VERSION, 'all' );

		$popular_posts_setting_css = '';
		$wli_heading_color = !empty( $this->options['wli_heading_color'] ) ? $this->options['wli_heading_color'] : '';
		$wli_heading_font_size = !empty( $this->options['wli_heading_font_size'] ) ? $this->options['wli_heading_font_size'] : '';
		$wli_heading_line_height = !empty( $this->options['wli_heading_line_height'] ) ? $this->options['wli_heading_line_height'] : '';
		$wli_link_color = !empty( $this->options['wli_link_color'] ) ? $this->options['wli_link_color'] : '';
		$wli_link_hover_color = !empty( $this->options['wli_link_hover_color'] ) ? $this->options['wli_link_hover_color'] : '';

		$wli_content_color = !empty( $this->options['wli_content_color'] ) ? $this->options['wli_content_color'] : '';
		$wli_content_font_size = !empty( $this->options['wli_content_font_size'] ) ? $this->options['wli_content_font_size'] : '';
		$wli_content_line_height = !empty( $this->options['wli_content_line_height'] ) ? $this->options['wli_content_line_height'] : '';

		if(!empty($wli_heading_color) || !empty($wli_heading_font_size) || !empty($wli_heading_line_height) || !empty($wli_link_color) || !empty($wli_link_hover_color)) {
			$popular_posts_setting_css = '.wli_popular_posts-class h3, .wli_popular_posts-class h3 a {';
			if(!empty($wli_heading_color)) {
				$popular_posts_setting_css .= 'color: ' . esc_attr( $wli_heading_color ) . ' !important;';
			}
			if(!empty($wli_heading_font_size)) {
				$popular_posts_setting_css .= 'font-size: ' . absint( $wli_heading_font_size ) . 'px !important;';
			}
			if(!empty($wli_heading_line_height)) {
				$popular_posts_setting_css .= 'line-height: ' . absint( $wli_heading_line_height ) . 'px !important;';
			}
			$popular_posts_setting_css .= '}';

			if(!empty($wli_link_color)) {
				$popular_posts_setting_css .= '.wli_popular_posts-class a {
						color: ' . esc_attr( $wli_link_color ) . ' !important;
				}';
			}
				
			if(!empty($wli_link_hover_color)) {
				$popular_posts_setting_css .= '.wli_popular_posts-class a:hover, .wli_popular_posts-class h3 a:hover {
						color: ' . esc_attr( $wli_link_hover_color ) . ' !important;
					}';
			}
		}

		//Apply css for heading font weight
		$wli_heading_font_weight = !empty($this->options['wli_heading_font_weight']) ? $this->options['wli_heading_font_weight'] : '';
		if ($wli_heading_font_weight !== 'none') {
			$popular_posts_setting_css .= '.wli_popular_posts-class h3, .wli_popular_posts-class h3 a {
        		font-weight: ' . esc_attr($wli_heading_font_weight) . ' !important;
    		}';
		}

		//Apply css for heading font style
		$wli_heading_font_style = !empty($this->options['wli_heading_font_style']) ? $this->options['wli_heading_font_style'] : '';
		if ($wli_heading_font_style !== 'none') {
			$popular_posts_setting_css .= '.wli_popular_posts-class h3, .wli_popular_posts-class h3 a {
        		font-style: ' . esc_attr($wli_heading_font_style) . ' !important;
    		}';
		}

		//Apply css for content font weight
		$wli_content_font_weight = !empty($this->options['wli_content_font_weight']) ? $this->options['wli_content_font_weight'] : '';
		if ($wli_content_font_weight !== 'none') {
			$popular_posts_setting_css .= '.wli_popular_posts-class ul.wli_popular_posts-listing li .popular-posts-excerpt {
        		font-weight: ' . esc_attr($wli_content_font_weight) . ' !important;
    		}';
		}

		//Apply css for content font style
		$wli_content_font_style = !empty($this->options['wli_content_font_style']) ? $this->options['wli_content_font_style'] : '';
		if ($wli_content_font_style !== 'none') {
			$popular_posts_setting_css .= '.wli_popular_posts-class ul.wli_popular_posts-listing li .popular-posts-excerpt {
        		font-style: ' . esc_attr($wli_content_font_style) . ' !important;
    		}';
		}

		//Check and apply slider navigation color
		if( !empty( $this->options['wli_slider_nav_color'] ) ) {
			$popular_posts_setting_css .= '.wli_popular_posts-slider .slick-prev:before, .wli_popular_posts-slider .slick-next:before {
				color: ' . esc_attr( $this->options['wli_slider_nav_color'] ) . ' !important;
			}';
		}

		if(!empty($wli_content_color) || !empty($wli_content_font_size) || !empty($wli_content_line_height)) {
			$popular_posts_setting_css .= '.wli_popular_posts-class p, .wli_popular_posts-class .popular-posts-excerpt {';
			if(!empty($wli_content_color)) {
				$popular_posts_setting_css .= 'color: ' . esc_attr( $wli_content_color ) . ' !important;';
			}
			if(!empty($wli_content_font_size)) {
				$popular_posts_setting_css .= 'font-size: ' . absint( $wli_content_font_size ) . 'px !important;';
			}
			if(!empty($wli_content_line_height)) {
				$popular_posts_setting_css .= 'line-height: ' . absint( $wli_content_line_height ) . 'px !important;';
			}
			$popular_posts_setting_css .= '}';
		}

		//Added custom CSS
		if( !empty( $this->options["wli_custom_css"] ) ) {
			$popular_posts_setting_css .= $this->options["wli_custom_css"];			
		}

		//Load inline styles
		wp_add_inline_style( 'popular-posts-style', esc_attr( $popular_posts_setting_css ) );

		//Enqueue Scripts
		wp_enqueue_script( 'slick-js', PP_URL . '/admin/assets/js/slick.min.js', array('jquery'), WLIPOPULARPOSTS_VERSION, true );
		wp_enqueue_script( 'popular-posts-public-script', PP_URL . '/admin/assets/js/popular-posts-by-webline-public.js', array('jquery'), WLIPOPULARPOSTS_VERSION, true );

		//Localize script data
		wp_localize_script( 'popular-posts-public-script', 'WLIPP_ScriptsData', array(
        	'slider_options' => apply_filters( 'wli_popular_posts_slider_options', array(
        		'dots' 		=> false,
        		'infinite' 	=> false,
				'speed'		=> 300,
				'slidesToShow' => 1,
				'autoplay'	=> true,
				'infinite'	=> true
        	)),
        ));
	}

	public function wli_show_popular_posts_callback( $arguments ) { 
		if (empty($this->options['wli_enable_pp'])) {
			return '';
		}
		extract( $arguments, EXTR_SKIP );
		global $content_length, $readmore_text;
		$wli_post_types = !empty($this->options['wli_select_posts']) ? $this->options['wli_select_posts'] : array('post');

		$no_posts = !empty($arguments['no_posts']) ? $arguments['no_posts'] : '3';
		$select_posttype= !empty($arguments['select_posttype'])?$arguments['select_posttype']:'post';
		$days_filter = !empty($arguments['days_filter']) ? $arguments['days_filter'] : 'None';
		$sort_by = !empty($arguments['sort_by']) ? $arguments['sort_by'] : 'Post Views Count';
		$category_name	= !empty($arguments['category_name'])?$arguments['category_name']:'category';
		$category = !empty($arguments['category']) ? $arguments['category'] : '';
		$display_type 	= !empty($arguments['display_type'])?$arguments['display_type']:'list';
		$comments = !empty($arguments['no_comments']) ? $arguments['no_comments'] : 'yes';
		$views_count = !empty($arguments['views_count']) ? $arguments['views_count'] : 'yes';
		$post_date = !empty($arguments['post_date']) ? $arguments['post_date'] : 'yes';
		$featured_image = !empty($arguments['featured_image']) ? $arguments['featured_image'] : 'yes';
		$featured_width = !empty($arguments['featured_width'])?$arguments['featured_width']:'100';
		$featured_height= !empty($arguments['featured_height'])?$arguments['featured_height']:'100';
		$featured_align = !empty($arguments['featured_align']) ? $arguments['featured_align'] : 'left';
		$content = !empty($arguments['content']) ? $arguments['content'] : 'yes';
		$content_length = !empty($arguments['content_length'])?$arguments['content_length']:'25';
		$readmore_text  = !empty($arguments['readmore_text'])?$arguments['readmore_text']:'[...]';
		$exc_curr_post = !empty($arguments['exc_curr_post']) ? $arguments['exc_curr_post'] : 'no';
		$relative_date = !empty($arguments['relative_date']) ? $arguments['relative_date'] : 'no';
		$wrap_desc = !empty($arguments['wrap_desc']) ? $arguments['wrap_desc'] : 'no';

		if (!in_array($select_posttype, $wli_post_types)) {
			return '';
		}

		$categoryarr = explode(',', $category_name);
		$count = count($categoryarr);

		if (!empty($category) && is_string($category)) {
			$category = explode(',', $category);
			$category = array_map('intval', $category); 
			$category = array_filter($category);
		}

		if($sort_by == "Comments")
		{
			$args = array(
					'post_type' 	=> $select_posttype,
					'posts_per_page'=>	$no_posts,
					'orderby'		=>	'comment_count',
					'order'			=>  'DESC',
			);
		}
		else
		{
			$args = array(
					'post_type' 	=> $select_posttype,
					'posts_per_page'=>	$no_posts,
					'meta_key'		=>  'wli_pp_post_views_count',
					'orderby'		=>	'meta_value_num',
			);
		}

		//Added day filter query
		if( $days_filter != 'None' && $days_filter != '' ) {
			$args['date_query'] = array(
				array(
					'column' => 'post_date_gmt',
					'after'  => $days_filter .' days ago',
				)
			);
		}
		
		if($select_posttype == 'post' && !empty( $category ) && $category_name == 'category') {
			$args['category__in'] = $category;
		} 
		elseif ( !empty( $category ) && $category_name != 'category') {
			$tax_query = array(
				'taxonomy' => $category_name,
				'field'    => 'term_id',
				'terms'    => $category,
			);
			$args['tax_query'] = array($tax_query);
		}

		if($exc_curr_post == "yes" && is_single())
		{
			$args['post__not_in'] = array(get_the_ID());
		}

		$the_query = new WP_Query( $args );

		ob_start();

		if ( $the_query->have_posts() ) {

			if( $display_type == 'slider' ) {
	    		$class = 'wli_popular_posts-slider';
	    	} else {
	    		$class = '';
	    	}?>

	    	<div class="<?php echo $class; ?>">

			<?php
			if( $display_type == 'list' ) { ?>
	    	<ul class="wli_popular_posts-listing">
	    	<?php
	    	}

			while ( $the_query->have_posts() ) {

				$the_query->the_post();

				if( $display_type == 'list' ) { ?>
	    		<li>
	    		<?php
	    		} else if( $display_type == 'slider' ) { ?>
	    		<div class="">
	    		<?php
	    		}?>
				<?php 
				if($featured_image == 'yes')
				{
					if ( has_post_thumbnail() )
					{
						//Get featured image align class
						$featured_align_class = 'post_thumb_left';
						if( $featured_align == 'right' ) {
							$featured_align_class = 'post_thumb_right';
						} elseif ( $featured_align == 'top' ) {
							$featured_align_class = 'post_thumb_top';
						}
					?>
						<div class="post_thumb <?php echo $featured_align_class;?>">
	                        <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
								<?php the_post_thumbnail(array($featured_width,$featured_height));?>
							</a>
                        </div>
                    <?php
                    }	
				}?>
                <h3>
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title();?></a>
                </h3>
				<?php 
				if($content == 'yes')
				{
					if($wrap_desc == 'yes') { ?>
						<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
					<?php
					}

					//Display excerpt text
					$readmore = $readmore_text;
					if($wrap_desc != 'yes') {
						$readmore = '<a href="'. get_the_permalink() .'">'. $readmore_text .'</a>';
					}

					//Get content
					$post_content = '';
					if( get_post_type() == 'product' ) {
						$post_content = get_the_excerpt();
					} else {
						$post_content = get_the_content();
					}

					echo '<div class="popular-posts-excerpt">'. wp_trim_words( $post_content, $content_length, ' '. $readmore ) .'</div>';

					if($wrap_desc == 'yes') { ?>
						</a>
					<?php
					}
				}

				if($comments == 'yes' || $post_date == 'yes' || $views_count == 'yes')
				{
				?>
					<div class="bottom_bar">
                        <p>
	                        <?php
	                        if($views_count == 'yes')
	                        {
	                        	$postview_icon = $this->options['wli_popular_posts_postview_icon'];
	                        	if(!empty($postview_icon)) {
	                        		$postview_icon = wp_get_attachment_thumb_url( $this->options['wli_popular_posts_postview_icon'] );
	                        	} else {
	                        		$postview_icon = PP_URL . "/admin/assets/images/view_icon.png";
	                        	}

	                        	$post_views = wli_popular_posts_get_post_views(get_the_ID());
								$views_title = sprintf( _n( '%s Post View', '%s Post Views', $post_views, $this->get_widget_slug() ), $post_views );
		
	                        	echo "<span><img title='".$views_title."' src='" . esc_url( $postview_icon ) . "' width='24px'/> ".$post_views."</span>";
	                        }
							if($comments == 'yes')
							{
								$postcomment_icon = $this->options['wli_popular_posts_postcomment_icon'];
	                        	if(!empty($postcomment_icon)) {
	                        		$postcomment_icon = wp_get_attachment_thumb_url( $this->options['wli_popular_posts_postcomment_icon'] );
	                        	} else {
	                        		$postcomment_icon = PP_URL . "/admin/assets/images/comment_icon.png";
	                        	}

								$comments_count = wp_count_comments(get_the_ID());
								$comments_title = sprintf( _n( '%s Comment', '%s Comments', $comments_count->approved, $this->get_widget_slug() ), $comments_count->approved );
								echo "<span><a href='".get_comments_link( get_the_ID() )."' title='".$comments_title."'><img src='" . esc_url( $postcomment_icon ) . "' Width='24px'/> ".$comments_count->approved."</a></span>";
							}
							if($post_date == 'yes')
							{
								$postdate_icon = $this->options['wli_popular_posts_postdate_icon'];
	                        	if(!empty($postdate_icon)) {
	                        		$postdate_icon = wp_get_attachment_thumb_url( $this->options['wli_popular_posts_postdate_icon'] );
	                        	} else {
	                        		$postdate_icon = PP_URL . "/admin/assets/images/date_icon.png";
	                        	}

								if($relative_date == "yes"){
									$object = new Wli_Popular_Posts();
									$date = $object->time_ago(get_the_date('U'),current_time('timestamp'));
								}
								else{
									$date = get_the_date();
								}
								
								echo "<span><img title='".$date."' src='" . esc_url( $postdate_icon ) . "' Width='24px'/> ".$date."</span>";
							}
							?>
                        </p>
                    </div>
				<?php 
				}

				if( $display_type == 'list' ) { ?>
    			</li>
    			<?php
    			} else if( $display_type == 'slider' ) { ?>
    			</div>
    			<?php
    			}

			}

			if( $display_type == 'list' ) { ?>
			</ul>
			<?php
			}?>
		</div>
		<?php
		}

		wp_reset_postdata();

		return ob_get_clean();
	}

	/* Create shortcode for popular posts */
	public function wli_add_popular_posts_shortcode( $atts ) {

		global $content_length,$readmore_text;

		$shortcode_atts = shortcode_atts( array(
			'title'  => 'Popular Posts',
	        'no_posts' => '3',
	        'select_posttype' => 'post',
	        'days_filter'  => 'None',
	        'sort_by' => 'Comments',
	        'display_type' => 'list',
	        'no_comments'  => 'yes',
	        'views_count' => 'yes',
	        'post_date' => 'yes',
	        'featured_image'  => 'yes',
	        'featured_width' => '100',
	        'featured_height' => '100',
	        'featured_align'  => 'left',
	        'content' => 'yes',
	        'content_length' => '25',
	        'readmore_text' => '[...]',
	        'category_name' => 'category',
	        'category' => '',
	        'exc_curr_post'  => 'no',
	        'relative_date' => 'no',
	        'wrap_desc' => 'no',

	    ), $atts );

	    $title = esc_attr(trim($shortcode_atts['title']));
	    $showposts = absint(trim($shortcode_atts['no_posts']));
	    $posttype = esc_attr(trim($shortcode_atts['select_posttype']));
	    $days = esc_attr(trim($shortcode_atts['days_filter']));
	    $sortby = esc_attr(trim($shortcode_atts['sort_by']));
	    $displaytype = esc_attr(trim($shortcode_atts['display_type']));
	    $comments = esc_attr(trim($shortcode_atts['no_comments']));
	    $views_count = esc_attr(trim($shortcode_atts['views_count']));
	    $post_date = esc_attr(trim($shortcode_atts['post_date']));
	    $image = esc_attr(trim($shortcode_atts['featured_image']));
	    $imagewidth = absint(trim($shortcode_atts['featured_width']));
	    $imageheight = absint(trim($shortcode_atts['featured_height']));
	    $imagealign = esc_attr(trim($shortcode_atts['featured_align']));
	    $content = esc_attr(trim($shortcode_atts['content']));
	    $content_length = absint(trim($shortcode_atts['content_length']));
	    $readmore_text = esc_attr(trim($shortcode_atts['readmore_text']));
	    $category_name = esc_attr(trim($shortcode_atts['category_name']));
	    $category = esc_attr(trim($shortcode_atts['category']));
	    $exclude_current = esc_attr(trim($shortcode_atts['exc_curr_post']));
	    $relative_date = esc_attr(trim($shortcode_atts['relative_date']));
	    $wrap_desc = esc_attr(trim($shortcode_atts['wrap_desc'])); 

	    ob_start();
	    ?>
	    <div class="wli_popular_posts-class">

	    	<h2><?php echo $title; ?></h2>

	    	<?php echo $this->wli_show_popular_posts_callback( $shortcode_atts ); ?>

	    </div>
		<?php
		return ob_get_clean();
	}

	/* Register admin sub menu */
	public function wli_register_popular_posts_menu_page() {

        add_options_page( __('Popular Posts - By Webline', 'popular-posts-by-webline' ), __('Popular Posts - By Webline', 'popular-posts-by-webline' ), 'manage_options','wli-popular-posts-by-webline', [ $this, 'wli_popular_posts_settings_page' ], 10
        );
	
	}

	/**
	 * Function to display settings page
	 *
	 * @since    1.0.6
	 */
	public function wli_popular_posts_settings_page() { ?>

		<div class="wrap">
			<h1><?php _e( "Popular Posts - By Webline", 'popular-posts-by-webline' ); ?></h1>
			<?php $this->wli_popular_posts_general_settings_callback(); ?>
			<div class="column-wrap-pp">
				<div class="wli-box col-pp-50">
					<form id="wli-pp-form" method="post" action="options.php">
						<?php wp_nonce_field( 'update-options' ); ?>
						<?php settings_fields( $this->get_widget_slug() ); ?>
						<?php do_settings_sections( $this->get_widget_slug() ); ?>
						<?php submit_button(); ?>
					</form>
				</div>
				<div class="wliplugin-cta-wrap col-pp-50">
				<h2><?php _e( "Popular Posts Shortcode", 'popular-posts-by-webline' ); ?></h2>
				<p><?php _e( "To show popular posts in list or slider form include the Popular Posts shortcode as formatted below.", 'popular-posts-by-webline' ); ?></p>
				<p><?php _e("Here's how you format the shortcode:", $this->get_widget_slug()); ?></p>
				<p><code>[wli_popular_posts title="" no_posts="" select_posttype="" days_filter="" sort_by="" category_name="" category="" display_type="" no_comments="" views_count="" post_date="" featured_image="" featured_width="" featured_height="" featured_align="" content="" content_length="" readmore_text="" exc_curr_post="" relative_date="" wrap_desc=""]</code></p>
				<ul>
					<li><b>title :</b> <?php _e( "Define title for popular posts. Default 'Popular Posts'.", 'popular-posts-by-webline' );?></li>
					<li><b>no_posts :</b> <?php _e( "No. of Posts to Show, Default '3'.", 'popular-posts-by-webline' );?></li>
					<li><b>select_posttype :</b> <?php _e( "Define which post type to display in popular posts. Default 'post'.", 'popular-posts-by-webline' );?></li>
					<li><b>days_filter :</b> <?php _e( "Define days to show Post Added within # Days. Default 'None'.", 'popular-posts-by-webline' );?></li>
					<li><b>sort_by :</b> <?php _e( "Define value to view posts as per defined sorted order. Includes any of the values - 1. 'Post Views Count' (Default) 2. 'Comments'.", 'popular-posts-by-webline' );?></li>
					<li><b>category_name :</b> <?php _e( "Define category name (slug) to show popular posts of that particular category. Default 'category'.", 'popular-posts-by-webline' );?></li>
					<li><b>category :</b> <?php _e( "Define comma separated category ids to show popular posts of that particular category.", 'popular-posts-by-webline' );?></li>
					<li><b>display_type :</b> <?php _e( "Define popular posts display type. Includes any of the values - 1. 'list' (Default) 2. 'slider'.", 'popular-posts-by-webline' );?></li>
					<li><b>no_comments :</b> <?php _e( "Define 'yes' or 'no' whether to show comments count. Default 'yes'.", 'popular-posts-by-webline' );?></li>
					<li><b>views_count :</b> <?php _e( "Define 'yes' or 'no' whether to show post views count. Default 'yes'.", 'popular-posts-by-webline' );?></li>
					<li><b>post_date :</b> <?php _e( "Define 'yes' or 'no' whether to show post publish date. Default 'yes'.", 'popular-posts-by-webline' );?></li>
					<li><b>featured_image :</b> <?php _e( "Define 'yes' or 'no' whether to show featured image of post. Default 'yes'.", 'popular-posts-by-webline' );?></li>
					<li><b>featured_width :</b> <?php _e( "Define featured image width of post in pixels. Default '100'.", 'popular-posts-by-webline' );?></li>
					<li><b>featured_height :</b> <?php _e( "Define featured image height of post in pixels. Default '100'.", 'popular-posts-by-webline' );?></li>
					<li><b>featured_align :</b> <?php _e( "Define featured image alignment of post as left,right and top. Default 'left'.", 'popular-posts-by-webline' );?></li>
					<li><b>content :</b> <?php _e( "Define 'yes' or 'no' whether to show post content. Default 'yes'.", 'popular-posts-by-webline' );?></li>
					<li><b>content_length :</b> <?php _e( "Define post content length to show. Default '25'.", 'popular-posts-by-webline' );?></li>
					<li><b>readmore_text :</b> <?php _e( "Define post content read more text to show. Default '[...]'.", 'popular-posts-by-webline' );?></li>
					<li><b>exc_curr_post :</b> <?php _e( "Exclude current post from popular posts listing. Default 'no'.", 'popular-posts-by-webline' );?></li>
					<li><b>relative_date :</b> <?php _e( "Define 'yes' or 'no' whether to show relative date of post. Default 'no'.", 'popular-posts-by-webline' );?></li>
					<li><b>wrap_desc :</b> <?php _e( "Wrap content with anchor link. Default 'no'.", 'popular-posts-by-webline' );?></li>
				</ul>
			</div>
		</div>
		</div>

	<?php }

	/**
	 * Init function to initialize hooks & functions
	 *
	 * @since    1.0.0
	 */
	public function wli_popular_posts_settings_init() {

	 	register_setting( 'wli_popular_posts', 'wli_popular_posts_options', array(
	 		'sanitize_callback' => array( $this, 'wli_popular_posts_sanitize_setting_fields' )
	 	));

		/* Create settings section */
		add_settings_section(
		    'wli_popular_posts_general', 
		    false,
		    array(),
		    $this->get_widget_slug()
		);

		//init all options
	 	add_settings_field(
	        'wli_enable_pp',
	        __( 'Enable Popular Posts', 'popular-posts-by-webline' ),
	        array( $this, 'wli_popular_posts_enable_pp_callback' ),
	        $this->get_widget_slug(),
	        'wli_popular_posts_general',
	        array( 'label_for' => 'wli_enable_pp' )
	    );

	    add_settings_field(
	        'wli_select_posts',
	        __( 'Post Type', 'popular-posts-by-webline' ),
	        array( $this, 'wli_popular_posts_enable_pop_callback' ),
	        $this->get_widget_slug(),
	        'wli_popular_posts_general',
	        array( 'label_for' => 'wli_select_posts' )
	    );

	    add_settings_field ( 
	    	'wli_postview_icon', 
	    	__( 'Add custom icon for post views:', 'popular-posts-by-webline' ), 
	    	array (	$this,'wli_popular_posts_postview_icon_callback'
			), 
			$this->get_widget_slug(), 
			'wli_popular_posts_general', 
			array( 'label_for' => 'wli_postview_icon' ) );

	    add_settings_field ( 
	    	'wli_postcomment_icon', 
	    	__( 'Add custom icon for post comments:', 'popular-posts-by-webline' ), 
	    	array (	$this,'wli_popular_posts_postcomment_icon_callback'
			), 
			$this->get_widget_slug(), 
			'wli_popular_posts_general', 
			array( 'label_for' => 'wli_postcomment_icon' ) );

	    add_settings_field ( 
	    	'wli_postdate_icon', 
	    	__( 'Add custom icon for post date:', 'popular-posts-by-webline' ), 
	    	array (	$this,'wli_popular_posts_postdate_icon_callback' ), 
			$this->get_widget_slug(), 
			'wli_popular_posts_general', 
			array( 'label_for' => 'wli_postdate_icon' ) );

	    /* Create settings for plugin ui */
		add_settings_section(
		    'wli_popular_posts_ui', 
		    false,
		    array( $this, 'wli_popular_posts_ui_settings_callback' ),
		    $this->get_widget_slug()
		);

		add_settings_field(
	        'wli_heading_color',
	        __( 'Heading Color', 'popular-posts-by-webline' ),
	        array( $this, 'wli_popular_posts_heading_color_callback' ),
	        $this->get_widget_slug(),
	        'wli_popular_posts_ui',
	        array( 'label_for' => 'wli_heading_color' )
	    );

	    add_settings_field(
	        'wli_heading_font_size',
	        __( 'Heading Font Size (in px)', 'popular-posts-by-webline' ),
	        array( $this, 'wli_popular_posts_heading_font_size_callback' ),
	        $this->get_widget_slug(),
	        'wli_popular_posts_ui',
	        array( 'label_for' => 'wli_heading_font_size' )
	    );

	    add_settings_field(
	        'wli_heading_line_height',
	        __( 'Heading Line Height (in px)', 'popular-posts-by-webline' ),
	        array( $this, 'wli_popular_posts_heading_line_height_callback' ),
	        $this->get_widget_slug(),
	        'wli_popular_posts_ui',
	        array( 'label_for' => 'wli_heading_line_height' )
	    );

		add_settings_field(
			'wli_heading_font_weight',
			__('Heading Font Weight', 'popular-posts-by-webline'),
			array($this, 'wli_popular_posts_heading_font_weight_callback'),
			$this->get_widget_slug(),
			'wli_popular_posts_ui',
			array('label_for' => 'wli_heading_font_weight')
		);

		add_settings_field(
			'wli_heading_font_style',
			__('Heading Font Style', 'popular-posts-by-webline'),
			array($this, 'wli_popular_posts_heading_font_style_callback'),
			$this->get_widget_slug(),
			'wli_popular_posts_ui',
			array('label_for' => 'wli_heading_font_style')
		);

		add_settings_field(
	        'wli_content_color',
	        __( 'Content Color', 'popular-posts-by-webline' ),
	        array( $this, 'wli_popular_posts_content_color_callback' ),
	        $this->get_widget_slug(),
	        'wli_popular_posts_ui',
	        array( 'label_for' => 'wli_content_color' )
	    );

	    add_settings_field(
	        'wli_content_font_size',
	        __( 'Content Font Size (in px)', 'popular-posts-by-webline' ),
	        array( $this, 'wli_popular_posts_content_font_size_callback' ),
	        $this->get_widget_slug(),
	        'wli_popular_posts_ui',
	        array( 'label_for' => 'wli_content_font_size' )
	    );

	    add_settings_field(
	        'wli_content_line_height',
	        __( 'Content Line Height (in px)', 'popular-posts-by-webline' ),
	        array( $this, 'wli_popular_posts_content_line_height_callback' ),
	        $this->get_widget_slug(),
	        'wli_popular_posts_ui',
	        array( 'label_for' => 'wli_content_line_height' )
	    );

		add_settings_field(
			'wli_content_font_weight',
			__('Content Font Weight', 'popular-posts-by-webline'),
			array($this, 'wli_popular_posts_content_font_weight_callback'),
			$this->get_widget_slug(),
			'wli_popular_posts_ui',
			array('label_for' => 'wli_content_font_weight')
		);

		add_settings_field(
			'wli_content_font_style',
			__('Content Font Style', 'popular-posts-by-webline'),
			array($this, 'wli_popular_posts_content_font_style_callback'),
			$this->get_widget_slug(),
			'wli_popular_posts_ui',
			array('label_for' => 'wli_content_font_style')
		);

	    add_settings_field(
	        'wli_link_color',
	        __( 'Link Color', 'popular-posts-by-webline' ),
	        array( $this, 'wli_popular_posts_link_color_callback' ),
	        $this->get_widget_slug(),
	        'wli_popular_posts_ui',
	        array( 'label_for' => 'wli_link_color' )
	    );

	    add_settings_field(
	        'wli_link_hover_color',
	        __( 'Link Hover Color', 'popular-posts-by-webline' ),
	        array( $this, 'wli_popular_posts_link_hover_color_callback' ),
	        $this->get_widget_slug(),
	        'wli_popular_posts_ui',
	        array( 'label_for' => 'wli_link_hover_color' )
	    );

	    add_settings_field(
	        'wli_slider_nav_color',
	        __( 'Slider Navigation Color', 'popular-posts-by-webline' ),
	        array( $this, 'wli_popular_posts_slider_nav_color_callback' ),
	        $this->get_widget_slug(),
	        'wli_popular_posts_ui',
	        array( 'label_for' => 'wli_slider_nav_color' )
	    );

	    add_settings_field(
	        'wli_custom_css',
	        __( 'Custom CSS', 'popular-posts-by-webline' ),
	        array( $this, 'wli_popular_posts_custom_css_callback' ),
	        $this->get_widget_slug(),
	        'wli_popular_posts_ui',
	        array( 'label_for' => 'wli_custom_css' )
	    );
	}

	/* Function to sanitize all plugin settings fields */
	public function wli_popular_posts_sanitize_setting_fields( $input ) {

		$new_input = $input;

		$new_input['wli_enable_pp'] = sanitize_text_field($input['wli_enable_pp']);
		$new_input['wli_select_posts'] = $input['wli_select_posts'];
		$new_input['wli_postview_icon'] = sanitize_text_field($input['wli_postview_icon']);
		$new_input['wli_postcomment_icon'] = sanitize_text_field($input['wli_postcomment_icon']);
		$new_input['wli_postdate_icon'] = sanitize_text_field($input['wli_postdate_icon']);
		$new_input['wli_heading_color'] = sanitize_hex_color($input['wli_heading_color']);
		$new_input['wli_heading_font_size'] = absint($input['wli_heading_font_size']);
		$new_input['wli_heading_line_height'] = absint($input['wli_heading_line_height']);
		$new_input['wli_content_color'] = sanitize_hex_color($input['wli_content_color']);
		$new_input['wli_content_font_size'] = absint($input['wli_content_font_size']);
		$new_input['wli_content_line_height'] = absint($input['wli_content_line_height']);
		$new_input['wli_link_color'] = sanitize_hex_color($input['wli_link_color']);
		$new_input['wli_link_hover_color'] = sanitize_hex_color($input['wli_link_hover_color']);
		$new_input['wli_slider_nav_color'] = sanitize_hex_color($input['wli_slider_nav_color']);
		$new_input['wli_custom_css'] = sanitize_textarea_field($input['wli_custom_css']);
		$new_input['wli_heading_font_weight'] = sanitize_text_field($input['wli_heading_font_weight']);
		$new_input['wli_heading_font_style'] = sanitize_text_field($input['wli_heading_font_style']);
		$new_input['wli_content_font_weight'] = sanitize_text_field($input['wli_content_font_weight']);
		$new_input['wli_content_font_style'] = sanitize_text_field($input['wli_content_font_style']);

		return $new_input;
	}

	/**
	 * General section callback function.
	 *
	 * @since    1.0.0
	 */
	public function wli_popular_posts_general_settings_callback() {
		?>
		<div class="ppbw-plugin-cta-wrap">
			<h2 class="head">Thank you for downloading our plugin - Popular Posts by Webline.</h2>
			<h2 class="head">We're here to help !</h2>
			<p>Our plugin comes with free, basic support for all users. We also provide plugin customization in case you want to customize our plugin to suit your needs.</p>
			<a href="https://www.weblineindia.com/contact-us.html?utm_source=WP-Plugin&utm_medium=Popular%20Posts%20by%20Webline&utm_campaign=Free%20Support" target="_blank" class="button">Need help?</a>
			<a href="https://www.weblineindia.com/contact-us.html?utm_source=WP-Plugin&utm_medium=Popular%20Posts%20by%20Webline&utm_campaign=Plugin%20Customization" target="_blank" class="button">Want to customize plugin?</a>
		</div>
		<div class="ppbw-plugin-cta-upgrade">
			<p class="note">Want to hire Wordpress Developer to finish your wordpress website quicker or need any help in maintenance and upgrades?</p>
			<a href="https://www.weblineindia.com/contact-us.html?utm_source=WP-Plugin&utm_medium=Popular%20Posts%20by%20Webline&utm_campaign=Hire%20WP%20Developer" target="_blank" class="button button-primary">Hire now</a>
		</div>

		<h2><?php _e( 'General Settings', 'popular-posts-by-webline' ); ?></h2>
		<?php
	}

	/**
	 * Settings callback function.
	 *
	 * @since    1.0.0
	 */
	public function wli_popular_posts_enable_pp_callback() {
		
		//Get option
	 	$wli_enable_pp = !empty( $this->options['wli_enable_pp'] ) ? $this->options['wli_enable_pp'] : '';
		?>		
		<input type='checkbox' name='wli_popular_posts_options[wli_enable_pp]' <?php checked( $wli_enable_pp, 1 ); ?> value='1'>
		<?php
	}

	/**
	 * Settings callback function.
	 *
	 * @since    1.0.0
	 */
	public function wli_popular_posts_enable_pop_callback() { ?>
		<?php
		$wli_select_posts = !empty($this->options['wli_select_posts']) ? $this->options['wli_select_posts'] : array();

		$args = array(
			'public'   => true,
			'_builtin' => false,
		);
		$post_types = get_post_types($args, 'objects');  ?>
		<input type="checkbox" class="wli_select_posts" name="wli_popular_posts_options[wli_select_posts][]" id="post" value="post" <?php if(empty( $wli_select_posts )) { echo 'checked'; } checked( in_array( 'post', $wli_select_posts ), 1 ); ?>/>
		<label for="post" class="checkbox-label" style="margin-right: 10px;"><?php _e( 'Posts', 'popular-posts-by-webline' );?></label>

		<?php foreach($post_types as $type) { ?>
			<input type="checkbox" class="wli_select_posts" name="wli_popular_posts_options[wli_select_posts][]" id="<?php echo $type->name; ?>" value="<?php echo $type->name; ?>" <?php checked( in_array( $type->name, $wli_select_posts ), 1 ); ?>/>
			<label for="<?php echo $type->name; ?>" class="checkbox-label" style="margin-right: 10px;"><?php _e( $type->label, 'popular-posts-by-webline' );?></label>
		<?php } ?>
		<p class="description"><?php _e( 'Enable post type which you want to display as a popular posts', 'woo-stickers-by-webline', 'popular-posts-by-webline' );?></p>
	<?php
	}

	/**
	 * Custom Icon for Post Views
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function wli_popular_posts_postview_icon_callback() {

		if (!empty($this->options['wli_popular_posts_postview_icon']))
		{				
			$image_id = $this->options['wli_popular_posts_postview_icon'];
			echo '<div class="wli_popular_posts_upload_img_preview"><img class="wli_popular_posts_postview_icon" src="'. esc_url( wp_get_attachment_thumb_url( $image_id ) ) .'" width="50px" height="auto" /></div>';
		}
		else
		{
			$image_id = "";
			echo '<div class="wli_popular_posts_upload_img_preview"><img class="wli_popular_posts_postview_icon" width="50px" height="auto" /></div>';
		}

		echo '<br/>
					<input type="hidden" name="wli_popular_posts_options[wli_popular_posts_postview_icon]" id="wli_popular_posts_icon" class="wli_popular_posts_upload_img_id" value="'.$image_id.'" />
					<button class="upload_img_btn button">'. __( 'Upload Image', 'popular-posts-by-webline' ) .'</button>
					<button class="remove_img_btn button">'. __( 'Remove Image', 'popular-posts-by-webline' ) .'</button>								
				'; ?>

		<p class="description"><?php _e( 'Add your own custom icon for post views on popular posts instead of default icon.', 'popular-posts-by-webline' );?></p>
	<?php
	}

	/**
	 * Custom Icon for Post Comments
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function wli_popular_posts_postcomment_icon_callback() {

		if (!empty($this->options['wli_popular_posts_postcomment_icon']))
		{				
			$image_id = $this->options['wli_popular_posts_postcomment_icon'];
			echo '<div class="wli_popular_posts_upload_img_preview"><img class="wli_popular_posts_postcomment_icon" src="'. esc_url( wp_get_attachment_thumb_url( $image_id ) ) .'" width="50px" height="auto" /></div>';
		}
		else
		{
			$image_id = "";
			echo '<div class="wli_popular_posts_upload_img_preview"><img class="wli_popular_posts_postcomment_icon" width="50px" height="auto" /></div>';
		}
		
		echo '<br/>
					<input type="hidden" name="wli_popular_posts_options[wli_popular_posts_postcomment_icon]" id="wli_popular_posts_postcomment_icon" class="wli_popular_posts_upload_img_id" value="'.$image_id.'" />
					<button class="upload_img_btn button">'. __( 'Upload Image', 'popular-posts-by-webline' ) .'</button>
					<button class="remove_img_btn button">'. __( 'Remove Image', 'popular-posts-by-webline' ) .'</button>								
				'; ?>

		<p class="description"><?php _e( 'Add your own custom icon for post comments on popular posts instead of default icon.', 'popular-posts-by-webline' );?></p>
	<?php
	}

	/**
	 * Custom Icon for Post Date
	 *
	 * @return void
	 * @var No arguments passed
	 * @author Weblineindia
	 */
	public function wli_popular_posts_postdate_icon_callback() {

		if (!empty($this->options['wli_popular_posts_postdate_icon']))
		{				
			$image_id = $this->options['wli_popular_posts_postdate_icon'];
			echo '<div class="wli_popular_posts_upload_img_preview"><img class="wli_popular_posts_postdate_icon" src="'. esc_url( wp_get_attachment_thumb_url( $image_id ) ) .'" width="50px" height="auto" /></div>';
		}
		else
		{
			$image_id = "";
			echo '<div class="wli_popular_posts_upload_img_preview"><img class="wli_popular_posts_postdate_icon" width="50px" height="auto" /></div>';
		}
		
		
		echo '		<br/>
					<input type="hidden" name="wli_popular_posts_options[wli_popular_posts_postdate_icon]" id="wli_popular_posts_postdate_icon" class="wli_popular_posts_upload_img_id" value="'. $image_id .'" />
					<button class="upload_img_btn button">'. __( 'Upload Image', 'popular-posts-by-webline' ) .'</button>
					<button class="remove_img_btn button">'. __( 'Remove Image', 'popular-posts-by-webline' ) .'</button>								
				'; ?>

		<p class="description"><?php _e( 'Add your own custom icon for post date on popular posts instead of default icon.', 'popular-posts-by-webline' );?></p>
	<?php
	}

	/**
	 * Add custom column
	 *
	 * @since     1.0.7
	 */
	public function custom_post_type_columns( $columns ) {

		$columns['wli_pp_views'] = __( 'Popular Posts Views', 'popular-posts-by-webline' );

		return $columns;
	}

	/**
	 * Display column value
	 *
	 * @since     1.0.7
	 */
	public function custom_post_type_column_value( $column, $post_id ) {

		//Check if popular posts view count column
		if( $column == 'wli_pp_views' ) {
	        $view_count = get_post_meta( $post_id , 'wli_pp_post_views_count' , true );
	        echo !empty( $view_count ) ? $view_count : 0;
		}
    }

	/**
	 * Add inlne style
	 *
	 * @since     1.0.7
	 */
	public function custom_admin_inline_style() {
		echo '<style type="text/css">#wli_pp_views{width:50px}</style>';
	}

	/**
	 * When user is on Popular Posts related admin page, display footer text
	 * that graciously asks them to rate us.
	 *
	 * @since 1.0.0
	 *
	 * @param string $text
	 *
	 * @return string
	 */
	public function wli_popular_posts_admin_footer( $text ) {

		global $current_screen;

		//Check of relatd screen match
		if ( ! empty( $current_screen->id ) && strpos( $current_screen->id, $this->plugin_options_key ) !== false ) {
			
			$url  = 'https://wordpress.org/support/plugin/popular-posts-by-webline/reviews/?filter=5#new-post';
			$wpdev_url  = 'https://www.weblineindia.com/wordpress-development.html?utm_source=WP-Plugin&utm_medium=Popular%20Posts%20by%20Webline&utm_campaign=Footer%20CTA';
			$text = sprintf(
				wp_kses(
					'Please rate our plugin %1$s <a href="%2$s" target="_blank" rel="noopener noreferrer">&#9733;&#9733;&#9733;&#9733;&#9733;</a> on <a href="%3$s" target="_blank" rel="noopener">WordPress.org</a> to help us spread the word. Thank you from the <a href="%4$s" target="_blank" rel="noopener noreferrer">WordPress development</a> team at WeblineIndia.',
					array(
						'a' => array(
							'href'   => array(),
							'target' => array(),
							'rel'    => array(),
						),
					)
				),
				'<strong>"Popular Posts by Webline"</strong>',
				$url,
				$url,
				$wpdev_url
			);
		}

		return $text;
	}

	/**
	 * Plugin UI section callback function.
	 */
	public function wli_popular_posts_ui_settings_callback() { ?>
		<h2><?php _e( 'Plugin UI Settings', 'popular-posts-by-webline' ); ?></h2>
		<?php
	}

	/**
	 * Plugin UI heading color setting callback function.
	 */
	public function wli_popular_posts_heading_color_callback() {
		$wli_heading_color = (!empty($this->options['wli_heading_color'])) ? $this->options['wli_heading_color'] : '';
		?>
		<input type="text" id="wli_heading_color" class="wli_color_picker" name="wli_popular_posts_options[wli_heading_color]" value="<?php echo esc_attr( $wli_heading_color ); ?>"/>
		<p class="description"><?php _e( 'Specify heading font color for title to show on popular posts.', 'popular-posts-by-webline' );?></p>
		<?php
	}

	/**
	 * Plugin UI heading font size setting callback function.
	 */
	public function wli_popular_posts_heading_font_size_callback() {
		$wli_heading_font_size = !empty($this->options['wli_heading_font_size']) ? $this->options['wli_heading_font_size'] : '';
		?>
		<input type="number" id="wli_heading_font_size" class="wli_numeric_field" name="wli_popular_posts_options[wli_heading_font_size]" value="<?php echo esc_attr( $wli_heading_font_size ); ?>"/>
		<p class="description"><?php _e( 'Specify heading font size for title to show on popular posts.', 'popular-posts-by-webline' );?></p>
		<?php
	}

	/**
	 * Plugin UI heading line height setting callback function.
	 */
	public function wli_popular_posts_heading_line_height_callback() {
		$wli_heading_line_height = !empty($this->options['wli_heading_line_height']) ? $this->options['wli_heading_line_height'] : '';
		?>
		<input type="number" id="wli_heading_line_height" class="wli_numeric_field" name="wli_popular_posts_options[wli_heading_line_height]" value="<?php echo esc_attr( $wli_heading_line_height ); ?>"/>
		<p class="description"><?php _e( 'Specify heading line height for title to show on popular posts.', 'popular-posts-by-webline' );?></p>
		<?php
	}

	/**
	 * Plugin UI content color setting callback function.
	 */
	public function wli_popular_posts_content_color_callback() {
		$wli_content_color = !empty($this->options['wli_content_color']) ? $this->options['wli_content_color'] : '';
		?>
		<input type="text" id="wli_content_color" class="wli_color_picker" name="wli_popular_posts_options[wli_content_color]" value="<?php echo esc_attr( $wli_content_color ); ?>"/>
		<p class="description"><?php _e( 'Specify content font color for content to show on popular posts.', $this->get_widget_slug() );?></p>
		<?php
	}

	/**
	 * Plugin UI content font size setting callback function.
	 */
	public function wli_popular_posts_content_font_size_callback() {
		$wli_content_font_size = !empty($this->options['wli_content_font_size']) ? $this->options['wli_content_font_size'] : '';
		?>
		<input type="number" id="wli_content_font_size" class="wli_numeric_field" name="wli_popular_posts_options[wli_content_font_size]" value="<?php echo esc_attr( $wli_content_font_size ); ?>"/>
		<p class="description"><?php _e( 'Specify content font size for content to show on popular posts.', 'popular-posts-by-webline' );?></p>
		<?php
	}

	/**
	 * Plugin UI content line height setting callback function.
	 */
	public function wli_popular_posts_content_line_height_callback() {
		$wli_content_line_height = !empty($this->options['wli_content_line_height']) ? $this->options['wli_content_line_height'] : '';
		?>
		<input type="number" id="wli_content_line_height" class="wli_numeric_field" name="wli_popular_posts_options[wli_content_line_height]" value="<?php echo esc_attr( $wli_content_line_height ); ?>"/>
		<p class="description"><?php _e( 'Specify content line height for content to show on popular posts.', 'popular-posts-by-webline' );?></p>
		<?php
	}

	/**
	 * Plugin UI anchor link color setting callback function.
	 */
	public function wli_popular_posts_link_color_callback() {
		$wli_link_color = !empty($this->options['wli_link_color']) ? $this->options['wli_link_color'] : '';
		?>
		<input type="text" id="wli_link_color" class="wli_color_picker" name="wli_popular_posts_options[wli_link_color]" value="<?php echo esc_attr( $wli_link_color ); ?>"/>
		<p class="description"><?php _e( 'Specify anchor link color for link to show on popular posts.', 'popular-posts-by-webline' );?></p>
		<?php
	}

	/**
	 * Plugin UI anchor link hover color setting callback function.
	 */
	public function wli_popular_posts_link_hover_color_callback() {
		$wli_link_hover_color = !empty($this->options['wli_link_hover_color']) ? $this->options['wli_link_hover_color'] : '';
		?>
		<input type="text" id="wli_link_hover_color" class="wli_color_picker" name="wli_popular_posts_options[wli_link_hover_color]" value="<?php echo esc_attr( $wli_link_hover_color ); ?>"/>
		<p class="description"><?php _e( 'Specify anchor link color for link to show on popular posts.', 'popular-posts-by-webline' );?></p>
		<?php
	}

	/**
	 * Plugin UI slider navigation color setting callback function.
	 */
	public function wli_popular_posts_slider_nav_color_callback() {
		$wli_slider_nav_color = !empty($this->options['wli_slider_nav_color']) ? $this->options['wli_slider_nav_color'] : '';
		?>
		<input type="text" id="wli_slider_nav_color" class="wli_color_picker" name="wli_popular_posts_options[wli_slider_nav_color]" value="<?php echo esc_attr( $wli_slider_nav_color ); ?>"/>
		<p class="description"><?php _e( 'Choose navigation color on popular posts slider.', 'popular-posts-by-webline' );?></p>
		<?php
	}

	/**
	 * Plugin UI Custom Css options setting callback function.
	 */
	public function wli_popular_posts_custom_css_callback() {
		$wli_custom_css = !empty($this->options['wli_custom_css']) ? $this->options['wli_custom_css'] : '';
		?>
		<textarea id="wli_custom_css" class="wli_textarea_field" name="wli_popular_posts_options[wli_custom_css]" style="width:100%;"><?php echo esc_textarea( $wli_custom_css ); ?></textarea>
		<p class="description"><?php _e( 'Specify custom css option for popular posts.', 'popular-posts-by-webline' );?></p>
		<?php
	}

		/**
	 * Plugin UI heading font weight setting callback function.
	 */
	public function wli_popular_posts_heading_font_weight_callback()
	{
		$wli_heading_font_weight = !empty($this->options['wli_heading_font_weight']) ? $this->options['wli_heading_font_weight'] : '';
		?>
		<select id="wli_heading_font_weight" name="wli_popular_posts_options[wli_heading_font_weight]">
			<option value="none" <?php selected($wli_heading_font_weight, 'none'); ?>>
				<?php _e('None', 'popular-posts-by-webline'); ?>
			</option>
			<?php
			$font_weights = array('100', '200', '300', '400', '500', '600', '700', '800', '900', 'bold', 'bolder', 'lighter', 'normal', 'initial', 'inherit');
			foreach ($font_weights as $weight) {
				?>
				<option value="<?php echo esc_attr($weight); ?>" <?php selected($wli_heading_font_weight, $weight); ?>>
					<?php echo esc_html($weight); ?>
				</option>
			<?php } ?>
		</select>
		<p class="description">
			<?php _e('Select the font weight for the post title on popular posts.', 'popular-posts-by-webline'); ?>
		</p>
		<?php
	}

	/**
	 * Plugin UI heading font style setting callback function.
	 */
	public function wli_popular_posts_heading_font_style_callback()
	{
		$wli_heading_font_style = !empty($this->options['wli_heading_font_style']) ? $this->options['wli_heading_font_style'] : '';
		?>
		<select id="wli_heading_font_style" name="wli_popular_posts_options[wli_heading_font_style]">
			<option value="none" <?php selected($wli_heading_font_style, 'none'); ?>>
				<?php _e('None', 'popular-posts-by-webline'); ?>
			</option>
			<?php
			$font_styles = array('normal', 'italic', 'oblique', 'inherit', 'initial', 'unset', 'revert', 'revert-layer');
			foreach ($font_styles as $style) {
				?>
				<option value="<?php echo esc_attr($style); ?>" <?php selected($wli_heading_font_style, $style); ?>>
					<?php echo esc_html($style); ?>
				</option>
			<?php } ?>
		</select>
		<p class="description">
			<?php _e('Select the font style for the post title on popular posts. If "None" is selected, the default style will be applied.', 'popular-posts-by-webline'); ?>
		</p>
		<?php
	}

	/**
	 * Plugin UI content font weight setting callback function.
	 */
	public function wli_popular_posts_content_font_weight_callback()
	{
		$wli_content_font_weight = !empty($this->options['wli_content_font_weight']) ? $this->options['wli_content_font_weight'] : '';
		?>
		<select id="wli_content_font_weight" name="wli_popular_posts_options[wli_content_font_weight]">
			<option value="none" <?php selected($wli_content_font_weight, 'none'); ?>>
				<?php _e('None', 'popular-posts-by-webline'); ?>
			</option>
			<?php
			$font_weights = array('100', '200', '300', '400', '500', '600', '700', '800', '900', 'bold', 'bolder', 'lighter', 'normal', 'initial', 'inherit');
			foreach ($font_weights as $weight) {
				?>
				<option value="<?php echo esc_attr($weight); ?>" <?php selected($wli_content_font_weight, $weight); ?>>
					<?php echo esc_html($weight); ?>
				</option>
			<?php } ?>
		</select>
		<p class="description">
			<?php _e('Select the font weight for the content on popular posts.', 'popular-posts-by-webline'); ?>
		</p>
		<?php
	}

	/**
	 * Plugin UI content font style setting callback function.
	 */
	public function wli_popular_posts_content_font_style_callback()
	{
		$wli_content_font_style = !empty($this->options['wli_content_font_style']) ? $this->options['wli_content_font_style'] : '';
		?>
		<select id="wli_content_font_style" name="wli_popular_posts_options[wli_content_font_style]">
			<option value="none" <?php selected($wli_content_font_style, 'none'); ?>>
				<?php _e('None', 'popular-posts-by-webline'); ?>
			</option>
			<?php
			$font_styles = array('normal', 'italic', 'oblique', 'inherit', 'initial', 'unset', 'revert', 'revert-layer');
			foreach ($font_styles as $style) {
				?>
				<option value="<?php echo esc_attr($style); ?>" <?php selected($wli_content_font_style, $style); ?>>
					<?php echo esc_html($style); ?>
				</option>
			<?php } ?>
		</select>
		<p class="description">
			<?php _e('Select the font style for the content on popular posts.', 'popular-posts-by-webline'); ?>
		</p>
		<?php
	}

	// Function to clear_cookie rating admin notice
	function ppbw_clear_cookie() {
		setcookie("ppbw_dismissed", "", time() - 3600, "/");
	}

	// Function to call admin notices
	public function ppbw_admin_notice_callback()
    {
		$current_screen = get_current_screen();
		$activation_date = get_option('ppbw_activation_date');
        $days_since_activation = $activation_date ? floor((time() - $activation_date) / (60 * 60 * 24)) : 0;

        //Admin notice for customize
        if (!(isset($_COOKIE['ppbw_rating_remind_later']) && $_COOKIE['ppbw_rating_remind_later'] === 'true')) {
			if ($days_since_activation >= 15) {
				if (!(isset($_COOKIE['ppbw_dismissed']) && $_COOKIE['ppbw_dismissed'] === 'true')) {
					$notification_template = '<div class="%1$s"><p><strong>%2$s</strong></p><p>%3$s</p>%4$s</div>';
					$class = esc_attr('ppbw notice notice-info is-dismissible');
					$message = '<p>' . __('Hey', 'popular-posts-by-webline') . ', ' . __('you have been using the Popular Posts by Webline for a while now - that\'s great!', 'popular-posts-by-webline') . '</p><p>' .
						__('Could you do us a big favor and <strong>give us your wonderful review on WordPress.org</strong>? This will help us to increase our visibility and to develop even <strong>more features for you</strong>.', 'popular-posts-by-webline') . '</p><p>' . __('Thanks!', 'popular-posts-by-webline') . '</p>';
					$buttons =
						'<div style="margin-bottom: 15px;">'
						. sprintf(
							'<a class="button button-primary" style="margin-right: 15px;" href="%s" target="_blank" rel="noopener">%s</a>',
							'https://wordpress.org/support/plugin/popular-posts-by-webline/reviews/?filter=5#new-post',
							'<span class="dashicons dashicons-thumbs-up" style="line-height:28px;"></span> ' . __('Of course, you deserve it', 'popular-posts-by-webline')
						)
						. sprintf(
							'<a class="ppbw_rating_remind_later button" style="background:none;margin-right: 15px;" href="javascript:void(0);" data-action="ppbw_rating_remind_later">%s</a>',
							'<span class="dashicons dashicons-backup" style="line-height:28px;"></span> ' . __('Please remind me later', 'popular-posts-by-webline')
						)
						. '</div>';
						if ($current_screen && $current_screen->id != 'settings_page_wli-popular-posts-by-webline') {
							$buttons .=
							'<div class="ppbw-customize-text"><p><img src="' . PP_URL . '/admin/assets/images/logo.png" alt="Logo" style="float: left; margin-right: 10px; margin-top: -10px;"> Want to hire Wordpress Developer to finish your wordpress website quicker or need any help in maintenance and upgrades?'
							. sprintf('<a class="button button-primary" style="float:right; margin-top:-7px; margin-right:-26px;" href="%s" target="_blank" rel="noopener">%s</a>', 'https://www.weblineindia.com/contact-us.html?utm_source=WP-Plugin&utm_medium=Popular%20Posts%20by%20Webline&utm_campaign=Hire%20WP%20Developer', '' . __('Hire Now', 'popular-posts-by-webline'))
							. sprintf(
								'',
								5,
								'<span class="dashicons dashicons-backup" style="line-height:28px;"></span> ' . __('Please remind me later', 'popular-posts-by-webline')
							)
							. '</p></div>';
						}

					printf(
						$notification_template,
						$class,
						'Popular Posts by Webline :',
						$message,
						$buttons
					);
				}
			}
        }

		// Check whether current screen is settings page of the Popular posts plugin
        if ($current_screen && $current_screen->id === 'settings_page_wli-popular-posts-by-webline') {
            return;
        }

        //Admin notice for rating
        if (!(isset($_COOKIE['ppbw_customize_remind_later']) && $_COOKIE['ppbw_customize_remind_later'] === 'true')) {
			if ($days_since_activation < 15) {
				$notification_template = '<div class="%1$s">%2$s %3$s</div>';
				$class = esc_attr('notice notice-info ppbw-admin-notice');
				$message = '<div class="ppbw-plugin-cta-wrap">
								<h2 class="head">Thank you for downloading our plugin - Popular Posts by Webline.</h2>
								<h2 class="head">We\'re here to help !</h2>
								<p>Our plugin comes with free, basic support for all users. We also provide plugin customization in case you want to customize our plugin to suit your needs.</p>
								<a href="https://www.weblineindia.com/contact-us.html?utm_source=WP-Plugin&utm_medium=Popular%20Posts%20by%20Webline&utm_campaign=Free%20Support" target="_blank" class="button">Need help?</a>
								<a href="https://www.weblineindia.com/contact-us.html?utm_source=WP-Plugin&utm_medium=Popular%20Posts%20by%20Webline&utm_campaign=Plugin%20Customization" target="_blank" class="button">Want to customize plugin?</a>
							</div>';
				$buttons = '<div class="ppbw-rating-text"><p><img src="' . PP_URL . '/admin/assets/images/logo.png" alt="Logo" style="float: left; margin-right: 10px; margin-top: -10px;"> Want to hire Wordpress Developer to finish your wordpress website quicker or need any help in maintenance and upgrades?'
					. sprintf('<a class="button button-primary" style="float:right; margin-top:-7px;" href="%s" target="_blank" rel="noopener">%s</a>', 'https://www.weblineindia.com/contact-us.html?utm_source=WP-Plugin&utm_medium=Popular%20Posts%20by%20Webline&utm_campaign=Hire%20WP%20Developer', '' . __('Hire Now', 'popular-posts-by-webline'))
					. sprintf(
						'<a class="ppbw_customize_remind_later button" href="javascript:void(0);" data-action="ppbw_customize_remind_later" data-add="%d">%s</a>',
						5,
						'<span class="dashicons dashicons-backup" style="line-height:28px;"></span> ' . __('Please remind me later', 'popular-posts-by-webline')
					)
					. '</p></div>';
		
				printf(
					$notification_template,
					$class,
					$message,
					$buttons
				);
			}
        }
    }

}

new wliPopularPosts();
?>