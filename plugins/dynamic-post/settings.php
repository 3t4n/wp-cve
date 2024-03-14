<?php
error_reporting(0);
if(!class_exists('WP_Plugin_Dynamic_Post_Settings'))
{
	class WP_Plugin_Dynamic_Post_Settings
	{
		/**
		 * Construct the plugin object
		*/
		public function __construct()
		{
			//register actions
            add_action('admin_init', array(&$this, 'admin_init'));
        	add_action('admin_menu', array(&$this, 'add_menu'));    
		} 
        //END public function __construct
        /**
         * hook into WP's admin_init action hook
        */
        public function admin_init()
        {
        	//register your plugin's settings
			register_setting('wp_plugin_dynamic_post-group', 'terms_of_use');
        	register_setting('wp_plugin_dynamic_post-group', 'api_key');
			register_setting('wp_plugin_dynamic_post-group', 'hide_images');
            register_setting('wp_plugin_dynamic_post-group', 'hide_metadata');
            register_setting('wp_plugin_dynamic_post-group', 'canonical_metadata');
            register_setting('wp_plugin_dynamic_post-group', 'feat_ured');
            register_setting('wp_plugin_dynamic_post-group', 'feat_ured2');
			register_setting('wp_plugin_dynamic_post-group', 'auto_up');
			register_setting('wp_plugin_dynamic_post-group', 'content_css');
        	//add your settings section
        	add_settings_section(
								'wp_plugin_dynamic_post-section', 
								'', 
								array(&$this, 'settings_section_wp_plugin_dynamic_post'), 
								'wp_plugin_dynamic_post'
        						);
		   	//add your setting's fields
			add_settings_field(
                				'wp_plugin_dynamic_post-api_key', 
                				'API Key', 
                				array(&$this, 'settings_field_input_text'), 
                				'wp_plugin_dynamic_post', 
                				'wp_plugin_dynamic_post-section',
                				array(
                    					'field' => 'api_key'
                					)
            				);
			add_settings_field(
                				'wp_plugin_dynamic_post-terms_of_use', 
                				'Terms of Service', 
                				array(&$this, 'settings_field_input_checkbox2'), 
                				'wp_plugin_dynamic_post', 
                				'wp_plugin_dynamic_post-section',
                				array(
                    					'field' => 'terms_of_use'
                					)
            				);
                            add_settings_field(
                                'wp_plugin_dynamic_post-auto_up', 
                                'Auto Posting Blogs every month', 
                                array(&$this, 'settings_field_input_checkbox_aut'), 
                                'wp_plugin_dynamic_post', 
                                'wp_plugin_dynamic_post-section',
                                array(
                                        'field' => 'auto_up'
                                    )
                            );

                            add_settings_field(
								'wp_plugin_dynamic_post-hide_metadata',
								'Show/Hide Meta Data',
								array(&$this, 'settings_field_input_checkbox1'),
								'wp_plugin_dynamic_post',
								'wp_plugin_dynamic_post-section',
								array(
										'field' => 'hide_metadata'
									)
			 					); 
            
                         add_settings_field(
                                'wp_plugin_dynamic_post-canonical_metadata',
                                'Canonical Option',
                                array(&$this, 'settings_field_input_canonical'),
                                'wp_plugin_dynamic_post',
                                'wp_plugin_dynamic_post-section',
                                array(
                                        'field' => 'canonical_metadata'
                                    )
                                ); 

                                add_settings_field(
                                    'wp_plugin_dynamic_post-featured', 
                                    'Show/Hide Content Image of a Post on Post Page',
                                    array(&$this, 'settings_field_input_checkbox_f'), 
                                    'wp_plugin_dynamic_post', 
                                    'wp_plugin_dynamic_post-section',
                                    array(
                                            'field' => 'feat_ured'
                                        )
                                );
		            	add_settings_field(
                				'wp_plugin_dynamic_post-hide_images', 
                				'Show/Hide Thumbnail Image of Pages', 
                				array(&$this, 'settings_field_input_checkbox'), 
                				'wp_plugin_dynamic_post', 
                				'wp_plugin_dynamic_post-section',
                				array(
                    					'field' => 'hide_images'
                					)
            			    	);
                    
                        add_settings_field(
                                'wp_plugin_dynamic_single-featured', 
                                'Show/Hide Featured Image of Post', 
                                array(&$this, 'settings_field_input_checkbox_single'), 
                                'wp_plugin_dynamic_post', 
                                'wp_plugin_dynamic_post-section',
                                array(
                                        'field' => 'feat_ured2'
                                    )
                               );
           
			            add_settings_field(
								'wp_plugin_dynamic_post-content_css', 
								'Custom CSS', 
								array(&$this, 'settings_field_input_textarea'), 
								'wp_plugin_dynamic_post', 
								'wp_plugin_dynamic_post-section',
								array(
										'field' => 'content_css'
									)
			 					);
		} 
        //END public static function activate
		public function settings_section_wp_plugin_dynamic_post()
        {
			//Think of this as help text for the section.
			echo '<kbd class="pluging-type api-key-msg">Loading...</kbd>';
        }
		/**
         * This function provides text inputs for settings fields
        */
        public function settings_field_input_text($args)
        {
            //Get the field name from the $args array
            $field = $args['field'];   
			
            //Get the value of this setting
            $value = ( get_option($field) ) ? get_option($field) : '79C6DA03-9130-4649-8448-15B4AB2CC7DF';
			
			//echo a proper input type="text"
            echo sprintf('<input type="text" class="form-control" placeholder="/* Enter Your Paid/Free API Key Here */" 
                name="%s" id="%s" value="%s" />', $field, $field, $value);
        }
		//END public function settings_field_input_text($args)
		public function settings_field_input_checkbox2($args)
        { ?>
			<?php
				//Get the field name from the $args array
				$field = $args['field'];   

				//Get the value of this setting
				//$value = get_option($field);
                $value = get_option( 'terms_of_use' );

				//echo a proper input type="checkbox"
				$checked = get_option( 'terms_of_use' ) ? 'checked="checked"' : '';
		 	?>
			<label class="custom-control custom-checkbox <?php echo (get_option('terms_of_use')== 1) ? "rt-fal" : '' ?>">
				<input type="checkbox" id="terms_of_use" name="terms_of_use" class="custom-control-input" 
                value="1" 
                <?php checked( 1, get_option('terms_of_use'), true );
                    //echo get_option( 'terms_of_use' ); 
                     //echo (get_option('terms_of_use')== 1) ? "disabled" : '';
                ?>
                >
                  <script type="text/javascript">
                        var ref_false= '<?php echo get_option( 'terms_of_use' );  ?>';
                          if(ref_false==1){
                               jQuery('.rt-fal').click(function() { return false; })
                          }else{
                          }
                  </script>
				<span class="custom-control-indicator"></span>
				I have read and accept the Terms of Service
			</label>
        <?php }
		//END public function settings_field_input_checkbox($args)
            
        //Auto Posting
            public function settings_field_input_checkbox_aut($args){ 
                //Get the field name from the $args array
                $field = $args['field'];   
                //Get the value of this setting
                $value = get_option($field);

                //echo a proper input type="checkbox"
                $checked = get_option( 'auto_up' ) ? 'checked="checked"' : '';
             ?>
                <label class="custom-control custom-checkbox">
                    <input type="checkbox" id="auto_up" name="auto_up" class="custom-control-input" value="1" <?php checked(1, get_option('auto_up'), true); ?> >
                    <span class="custom-control-indicator"></span>
                    <?php
                        if (get_option( 'auto_up' ) == 1)
                        {
                            echo "<span class='cus-sp toggle_auto_up'>Auto Posting is Turned On</span>";
                        }
                        else
                        {
                            echo "<span class='cus-sp toggle_auto_up'>Auto Posting is Turned Off</span>";
                        }
                    ?>
                </label>
           <?php }
         //Auto Posting Ends

		public function settings_field_input_checkbox($args)
        { ?>
			<?php
				//Get the field name from the $args array
				$field = $args['field'];   

				//Get the value of this setting
				$value = get_option($field);

				//echo a proper input type="checkbox"
				$checked = get_option( 'hide_images' ) ? 'checked="checked"' : '';
		 	?>
			<label class="custom-control custom-checkbox">
				<input type="checkbox" id="hide_images" name="hide_images" class="custom-control-input" value="1" <?php checked(1, get_option('hide_images'), true); ?> >
				<span class="custom-control-indicator"></span>
				<?php
					if (get_option( 'hide_images' ) == 1)
					{
						echo "<span class='cus-sp toggle_hide_images'>Pages Thumbnail Image is visible (Belongs to Pages using Page Builder like Elementor, Divi etc).</span>";
					}
					else
					{
						echo "<span class='cus-sp toggle_hide_images'>Pages Thumbnail Image is not visible (Belongs to Pages using Page Builder like Elementor, Divi etc).</span>";
					}
				?>
			</label>
        <?php }
		//END public function settings_field_input_checkbox($args)
        public function settings_field_input_checkbox_f($args)
        { ?>
            <?php
                //Get the field name from the $args array
                $field = $args['field'];   

                //Get the value of this setting
                $value = get_option($field);

                //echo a proper input type="checkbox"
                $checked = get_option( 'feat_ured' ) ? 'checked="checked"' : '';
            ?>
            <label class="custom-control custom-checkbox">
                <input type="checkbox" id="feat_ured" name="feat_ured" class="custom-control-input" value="1" <?php checked(1, get_option('feat_ured'), true); ?> >
                <span class="custom-control-indicator"></span>
                <?php
                    if (get_option( 'feat_ured' ) == 1)
                    {
                        //echo "Featured Image is hidden now.";
                        echo "<span class='cus-sp toggle_feat_ured'>Post Content Thumbnail Image is visible.</span>";
                      
                    }
                    else
                    {
                        echo "<span class='cus-sp toggle_feat_ured'>Post Content Thumbnail Image is not visible.</span>";
                    }
                ?>
            </label>
        <?php }

        public function settings_field_input_checkbox_single($args)
        { ?>
            <?php
                //Get the field name from the $args array
                $field = $args['field'];   

                //Get the value of this setting
                $value = get_option($field);

                //echo a proper input type="checkbox"
                $checked = get_option( 'feat_ured2' ) ? 'checked="checked"' : '';
            ?>
            <label class="custom-control custom-checkbox">
                <input type="checkbox" id="feat_ured2" name="feat_ured2" class="custom-control-input" value="1" <?php checked(1, get_option('feat_ured2'), true); ?> >
                <span class="custom-control-indicator"></span>
                <?php
                    if (get_option( 'feat_ured2' ) == 1)
                    {
                        echo "<span class='cus-sp toggle_feat_ured2'>Post Feature Image is visible.</span>";
                    }
                    else
                    {
                       //echo "Featured Thumbnail is shown now";
                        echo "<span class='cus-sp toggle_feat_ured2'>Post Featured Image is not visible.</span>";
                        
                    }
                ?>
            </label>
        <?php }

       
        //END public function settings_field_input_checkbox1($args)
		public function settings_field_input_checkbox1($args)
        { ?>
			<?php
				//Get the field name from the $args array
				$field = $args['field'];   

				//Get the value of this setting
				$value = get_option($field);

				//echo a proper input type="checkbox"
				$checked = get_option( 'hide_metadata' ) ? 'checked="checked"' : '';
		 	?>
			<label class="custom-control custom-checkbox">
				<input type="checkbox" id="hide_metadata" name="hide_metadata" class="custom-control-input" value="1" <?php checked(1, get_option('hide_metadata'), true); ?> >
				<span class="custom-control-indicator"></span>
				<?php
					if (get_option( 'hide_metadata' ) == 1)
					{
                        echo "<span class='cus-sp toggle_hide_metadata'>Preoptimized Meta keywords & Description.</span>";
					}
					else
					{
						echo "<span class='cus-sp toggle_hide_metadata'>Use this option if you are doing your own SEO on these posts.</span>";	
					}
				?>
			</label>
        <?php }
        public function settings_field_input_canonical($args)
        { ?>
            <?php
                //Get the field name from the $args array
                $field = $args['field'];   

                //Get the value of this setting
                $value = get_option($field);

                //echo a proper input type="checkbox"
                $checked = get_option( 'canonical_metadata' ) ? 'checked="checked"' : '';
            ?>
            <label class="custom-control custom-checkbox">
                <input type="checkbox" id="canonical_metadata" name="canonical_metadata" class="custom-control-input" value="1" 
                <?php checked(1, get_option('canonical_metadata'), true); ?> >
                <span class="custom-control-indicator"></span>
                <?php
                    if( get_option( 'canonical_metadata' ) == 1 )
                    {
                        echo "<span class='cus-sp toggle_canonical_metadata'>WordPress canonical.</span>";
                    }
                    else
                    {
                        echo "<span class='cus-sp toggle_canonical_metadata'>Dynamicontent.net canonical.</span>";
                    }
                ?>
            </label>
        <?php }
		//END public function settings_field_input_canonical($args)
        public function settings_field_input_textarea($args)
        {
            //Get the field name from the $args array
            $field = $args['field']; 
            //Get the value of this setting
            $value = get_option($field);
            //echo a proper input type="text" 
            echo sprintf('<textarea class="form-control" placeholder="/* Enter Your Custom CSS Here */" rows="6" cols="120" name="%s" id="%s" />%s</textarea>', $field, $field, $value);
        } 
        //END public function settings_field_input_textarea($args)
        public function add_menu()
        {            
            add_menu_page(__('Dynamic Post'), 'Dynamic Post', 'manage_options', 'dynamic_post', array(&$this, 'plugin_settings_page'),'dashicons-visibility');
            //add_submenu_page('dynamic_post', __('Dynamic Post Submenu'), 'Dynamic Post Submenu', 'manage_options', 'dynamic_post_submenu', array(&$this, 'plugin_submenu_page'), 'dashicons-visibility' );
        } 
        //END public function add_menu()
        /**
         * Menu Callback
        */		
        public function plugin_settings_page()
        {           
        	if(!current_user_can('manage_options'))
        	{
        		wp_die( __( 'Sorry, you are not allowed to manage options for this site.' ) );
        	}
            //Render the settings template
            include(sprintf("%s/setting_template/settings.php", dirname(__FILE__)));	
        }
        /*public function plugin_submenu_page()
        {           
            if(!current_user_can('manage_options'))
            {
                wp_die( __( 'Sorry, you are not allowed to manage options for this site.' ) );
            }
            //Render the settings template
            include(sprintf("%s/submenu_template/submenu_options.php", dirname(__FILE__)));    
        }*/       
        // END public function plugin_settings_page()
    } 
    // END class WP_Plugin_Dynamic_Post_Settings
} 
// END if(!class_exists('WP_Plugin_Dynamic_Post_Settings'))
/******************** Shortcodes ************************************/
// Function to create Shortcode for Articles
function create_dynamic_post_shortcode($atts)
{
    global $post;
    $get_current_month = date('n');
    $get_current_year = date('Y');
    $post_start_date = get_option( 'post_start_date' );

    $class = new Post_Type_Dynamic_Post;                               
    $json = $class->return_result();

    $exclude_args = array ('exclude'=>1,'fields'=>'ids');   
    $exclude_uncategorized = get_terms('category',$exclude_args);

    $get_shortcode_atts = shortcode_atts(
    array(
        'cat' => '',
    ), $atts );
    $args = array(
                    'category_name' => $get_shortcode_atts['cat'], 
                    'monthnum' => $get_current_month,
                    'year'     => $get_current_year,
                    'post_type'=>'post', 
                    'order' => 'asc',
                    'category__in' => $exclude_uncategorized,
                    'post_status'=>'publish',
                    'posts_per_page'=>-1
                );
    $output = '';
    $posts = get_posts($args);
    if ($posts && $post_start_date == $json->start_date && $json->message == 'Valid Licensed API Key; Articles found')
    {
        foreach ($posts as $post) 
        {
            $text = strip_shortcodes( $post->post_content );
            $text = apply_filters( 'the_content', $text );
            $text = str_replace(']]>', ']]&gt;', $text);
            $excerpt_length = apply_filters( 'excerpt_length', 55 );
            $text = wp_trim_words( $text, $excerpt_length );
            $read_full_article = '<p class="link-more"><a href="'.get_permalink( $post->ID ).'" class="more-link">Read Full Article</a><p>';
            //$meta_img = get_post_meta( get_the_ID(), 'featured_img', TRUE );
            $output .= '<article id="post-'.$post->ID.'" class="post-'.$post->ID.'">
                                <div class="entry-meta">
                                        <span class="screen-reader-text">Posted on</span>
                                        <time class="entry-date published updated">
                                                <a href="'.get_permalink( $post->ID ).'">'.get_the_time('F j, Y', $post->ID).'</a>
                                        </time>
                                </div>
                                <h3 class="dynamic-post-title"><a href="'.get_permalink( $post->ID ).'" title="'.$post->post_title.'">'.$post->post_title.'</a></h3>
                                <div class="post-thumbnail">
                                        <a href="'.get_permalink( $post->ID ).'" title="'.$post->post_title.'">'.get_the_post_thumbnail( $post->ID ).'</a>
                                </div>
                                <div class="entry-content">'.$text.'<br/>'.$read_full_article.'</div><hr>
                        </article>';
        }
    }
    else if ($posts && $json->message == 'Free API Key Articles')
    {
        foreach ($posts as $post) 
        {
            $text = strip_shortcodes( $post->post_content );
            $text = apply_filters( 'the_content', $text );
            $text = str_replace(']]>', ']]&gt;', $text);
            $excerpt_length = apply_filters( 'excerpt_length', 55 );
            $text = wp_trim_words( $text, $excerpt_length );
            $read_full_article = '<p class="link-more"><a href="'.get_permalink( $post->ID ).'" class="more-link">Read Full Article</a><p>';

            $output .= '<article id="post-'.$post->ID.'" class="post-'.$post->ID.'">
                                <div class="entry-meta">
                                        <span class="screen-reader-text">Posted on</span>
                                        <time class="entry-date published updated">
                                                <a href="'.get_permalink( $post->ID ).'">'.get_the_time('F j, Y', $post->ID).'</a>
                                        </time>
                                </div>
                                <h3 class="dynamic-post-title"><a href="'.get_permalink( $post->ID ).'" title="'.$post->post_title.'">'.$post->post_title.'</a></h3>
                                <div class="post-thumbnail">
                                        <a href="'.get_permalink( $post->ID ).'" title="'.$post->post_title.'">'.get_the_post_thumbnail( $post->ID ).'</a>
                                </div>
                                <div class="entry-content">'.$text.'<br/>'.$read_full_article.'</div><hr>
                        </article>';
        }
    }
    else 
    {
        $output .= '<div class="alert alert-danger">
                            <strong>Sorry !</strong> No articles found.
                    </div>';
    }
    return $output;
}
add_shortcode('dynamic-post', 'create_dynamic_post_shortcode');
// Function to create Shortcode for Archives
function create_dynamic_post_shortcode_archive($atts_archive)
{
    global $post_archive;
    $get_cat_name_archive = get_the_category( $post->ID );
    $get_current_month_archive = date("M", strtotime( $post_archive->post_date ));
    $get_current_year_archive = date("Y", strtotime( $post_archive->post_date ));
    $class = new Post_Type_Dynamic_Post;                               
    $json = $class->return_result();
    $get_shortcode_atts_archive = shortcode_atts(
    array(
        'cat' => $get_cat_name_archive,
        'month' => $get_current_month_archive,
        'year' => $get_current_year_archive,
    ), $atts_archive );	
    $exclude_args = array ('exclude'=>1,'fields'=>'ids');   
    $exclude_uncategorized = get_terms('category',$exclude_args);
    $args_archive = array(
                            'category_name' => $get_shortcode_atts_archive['cat'], 
                            'monthnum' => $get_shortcode_atts_archive['month'], 
                            'year' => $get_shortcode_atts_archive['year'],
                            'post_type'=>'post', 
                            'order' => 'asc',
                            'category__in' => $exclude_uncategorized,
                            'post_status'=>'publish',
                            'posts_per_page'=>-1 
                        );
    $output_archive = '';
    $posts_archive = get_posts($args_archive);
    if ( $posts_archive && $json->message == 'Valid Licensed API Key; Articles found' )
    {
        foreach ($posts_archive as $post_archive) 
        {
            $text_archive = strip_shortcodes( $post_archive->post_content );
            $text_archive = apply_filters( 'the_content', $text_archive );
            $text_archive = str_replace(']]>', ']]&gt;', $text_archive);
            $excerpt_length_archive = apply_filters( 'excerpt_length', 55 );
            $text_archive = wp_trim_words( $text_archive, $excerpt_length_archive );
            $read_full_article_archive = '<p class="link-more"><a href="'.get_permalink( $post_archive->ID ).'" class="more-link">Read Full Article</a><p>';
            $output_archive .= '<article id="post-'.$post->ID.'" class="post-'.$post->ID.'">
                                        <div class="entry-meta">
                                                <span class="screen-reader-text">Posted on</span>
                                                <time class="entry-date published updated">
                                                        <a href="'.get_permalink( $post_archive->ID ).'">'.get_the_time('F j, Y', $post_archive->ID).'</a>
                                                </time>
                                        </div>
                                        <h3 class="dynamic-post-title"><a href="'.get_permalink( $post_archive->ID ).'" title="'.$post_archive->post_title.'">'.$post_archive->post_title.'</a></h3>
                                        <div class="post-thumbnail">
                                                <a href="'.get_permalink( $post_archive->ID ).'" title="'.$post_archive->post_title.'">'.get_the_post_thumbnail( $post_archive->ID ).'</a>
                                        </div>
                                        <div class="entry-content">'.$text_archive.'<br/>'.$read_full_article_archive.'</div><hr>
                                </article>';
        }
    }
    else 
    {
    $output_archive .= '<div class="alert alert-danger">
                                <strong>Sorry !</strong> No articles found for this month.
                        </div>';
    }
    return $output_archive;
}
add_shortcode('dynamic-posts', 'create_dynamic_post_shortcode_archive');
?>