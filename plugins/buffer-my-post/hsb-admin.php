<?php
/**
 * @package hype-social-buffer
 */
require_once( 'buffer-my-post.php' );
require_once( 'hsb-core.php' );
require_once( 'hsb-xml.php' );
require_once( 'includes/hsb-debug.php' );
//require_once( 'lib/helpers.php' );

if ( ! class_exists( 'HsbAdmin' ) ) {
	/**
	 * Class HsbAdmin
	 */
	class HsbAdmin extends HsbCore {


		public function __construct() {

			parent::__construct();  //call HsbCore __construct() function

			add_action( 'admin_menu', array( $this, 'hsb_admin_actions' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'hsb_admin_enqueue_scripts' ) );
			add_action( 'admin_head', array( $this, 'hsb_opt_head_admin' ) );
			add_action( 'save_post', array( $this, 'hsb_buffer_save_post_meta' ) );

			add_action( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'hsb_plugin_action_links2' );
		}

		function hsb_plugin_action_links2( $links ) {
			$links[] = '<a href="http://hypestudio.org/">' . __( 'Newsletter', 'HYPESocialBuffer' ) . '</a>';
			$links[] = '<a href="' . site_url() . '/wp-admin/admin.php?page=HYPESocialBuffer">Settings</a>';

			return $links;
		}

		/*
		 *
		 * Creates admin page and subpage for HYPESocialBuffer settings
		 *
		 * @return
		 */
		public function hsb_admin_actions() {
			add_menu_page( __( 'HYPESocial Buffer Settings', 'HYPESocialBuffer' ), __( 'Buffer - HYPESocial', 'HYPESocialBuffer' ), 'manage_options', 'HYPESocialBuffer', array(
				$this,
				"hsb_admin"
			)/*, plugins_url( 'buffer-my-post/images/buffer-logo-light.svg' )*/);
			add_submenu_page( 'HYPESocialBuffer', __( 'Exclude Posts & Pages', 'HYPESocialBuffer' ), __( 'Exclude Posts & Pages', 'HYPESocialBuffer' ), 'manage_options', __( 'HSBExcludePosts', 'HYPESocialBuffer' ), array(
				$this,
				'hsb_exclude'
			) );
		}

		/*
		 * registers styles and scripts for admin
		 *
		 * @param varchar $hook
		 *
		 * @return
		 */
		public function hsb_admin_enqueue_scripts( $hook ) {

			$page = filter_input( INPUT_GET, 'page' );

			if ( ( $page == 'HYPESocialBuffer' && $hook == 'toplevel_page_HYPESocialBuffer' ) || $page == 'HSBExcludePosts' || $page == 'HSBRegisterPosts' || $page == 'HSBRegister' ) {


				wp_register_style( 'jqueryui-css', '//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css', false, '1.11.4' );
				wp_register_style( 'hype-social-buffer-admin-css', plugin_dir_url( __FILE__ ) . 'css/hype-social-buffer-admin.css', array( 'jqueryui-css' ), '15.0.4' );
				wp_enqueue_style( 'jqueryui-css' );
				wp_enqueue_style( 'hype-social-buffer-admin-css' );
				wp_enqueue_script( 'jquery-ui', '//code.jquery.com/ui/1.11.4/jquery-ui.js', array( 'jquery' ), '1.11.4', true );
				if ( $page == 'HYPESocialBuffer' ) {
					wp_enqueue_script( 'hype-social-buffer-admin-js', plugin_dir_url( __FILE__ ) . 'js/hype-social-buffer-admin.js', array( 'jquery-ui' ), '15.0.4', true );
				}
				elseif ( $page == 'HSBExcludePosts' ) {
					wp_enqueue_script( 'hype-social-buffers-admin-exclude-js', plugin_dir_url( __FILE__ ) . 'js/hype-social-buffer-admin-exclude.js', false, '15.0.4', true );
				}
				else // || $page == 'HSBRegister'
				{
					wp_enqueue_script( 'hype-social-buffer-register', plugin_dir_url( __FILE__ ) . 'js/hype-social-buffer-admin-register.js', false, '15.0.4', true );
				}
			}
		}

		public function hsb_opt_head_admin() {

		}

		/**
		 *
		 * Appends settings link on plugins overview page for HSB plugin
		 *
		 * @param array  $links
		 * @param string $file
		 *
		 * @return
		 */
		public function hsb_plugin_action_links( $links, $file ) {

			static $this_plugin;

			if ( ! $this_plugin ) {
				$this_plugin = plugin_basename( __FILE__ );
			}

			if ( $file == $this_plugin ) {
				// The "page" query string value must be equal to the slug
				// of the Settings admin page we defined earlier, which in
				// this case equals "myplugin-settings".
				$settings_link = '<a href="' . site_url() . '/wp-admin/admin.php?page=HYPESocialBuffer">Settings</a>';
				array_unshift( $links, $settings_link );
			}

			return $links;
		}

		/**
		 *
		 * Main settings for HYPESocialBuffer
		 *
		 * @return
		 */
		public function hsb_admin() {
			//check permission

			if ( current_user_can( 'activate_plugins' ) ) {
				$message         = null;
				$message_updated = __( "<em>HYPE Social</em> - Buffer(Options Updated).", 'HYPESocialBuffer' );
				$response        = null;
				$save            = true;
				$settings        = $this->hsb_get_settings();

				//if submit and if bitly selected its fields are filled then save
				if ( isset( $_POST['submit'] ) && $save ) {
					$message = $message_updated;

					//post interval
					if ( isset( $_POST['hsb_opt_interval'] ) ) {
						if ( is_numeric( $_POST['hsb_opt_interval'] ) && $_POST['hsb_opt_interval'] > 0 ) {
							update_option( 'hsb_opt_interval', $_POST['hsb_opt_interval'] );
						}
						else {
							update_option( 'hsb_opt_interval', "1" );
						}
					}

					//minimum post age to post
					if ( isset( $_POST['hsb_opt_age_limit'] ) ) {
						if ( is_numeric( $_POST['hsb_opt_age_limit'] ) && $_POST['hsb_opt_age_limit'] >= 0 ) {
							update_option( 'hsb_opt_age_limit', $_POST['hsb_opt_age_limit'] );
						}
						else {
							update_option( 'hsb_opt_age_limit', "30" );
						}
					}

					//maximum post age to post
					if ( isset( $_POST['hsb_opt_max_age_limit'] ) ) {
						if ( is_numeric( $_POST['hsb_opt_max_age_limit'] ) && $_POST['hsb_opt_max_age_limit'] > 0 ) {
							update_option( 'hsb_opt_max_age_limit', $_POST['hsb_opt_max_age_limit'] );
						}
						else {
							update_option( 'hsb_opt_max_age_limit', "0" );
						}
					}

					//number of posts to post
					if ( isset( $_POST['hsb_opt_no_of_post'] ) ) {
						if ( is_numeric( $_POST['hsb_opt_no_of_post'] ) && $_POST['hsb_opt_no_of_post'] > 0 ) {
							update_option( 'hsb_opt_no_of_post', $_POST['hsb_opt_no_of_post'] );
						}
						else {
							update_option( 'hsb_opt_no_of_post', "1" );
						}
					}

					//type of post to post
					if ( isset( $_POST['hsb_opt_post_type'] ) ) {
						update_option( 'hsb_opt_post_type', $_POST['hsb_opt_post_type'] );
					}

					//post format
					if ( isset( $_POST['hsb_opt_post_format'] ) ) {
						update_option( 'hsb_opt_post_format', $_POST['hsb_opt_post_format'] );
					}


					//hsb_disable_buffer
					if ( isset( $_POST['hsb_disable_buffer'] ) ) {
						update_option( 'hsb_disable_buffer', true );
					}
					else {
						update_option( 'hsb_disable_buffer', false );
					}

					//acntids
					if ( isset( $_POST['acntids'] ) ) {

						update_option( 'hsb_opt_acnt_id', $_POST['acntids'] );
					}


					//hsb_opt_access_token
					if ( isset( $_POST['hsb_opt_access_token'] ) ) {
						update_option( 'hsb_opt_access_token', $_POST['hsb_opt_access_token'] );
					}

					//option to enable log
					if ( isset( $_POST['hsb_enable_log'] ) ) {
						update_option( 'hsb_enable_log', true );
						global $hsb_debug;
						$hsb_debug->enable( true );

					}
					else {
						update_option( 'hsb_enable_log', false );
						global $hsb_debug;
						$hsb_debug->enable( false );
					}

					//categories to omit from post
					if ( isset( $_POST['post_category'] ) ) {
						update_option( 'hsb_opt_omit_cats', implode( ',', $_POST['post_category'] ) );
					}
					else {
						update_option( 'hsb_opt_omit_cats', '' );
					}

					//categories from custom posts to omit from tweet
					if ( isset( $_POST['tax_input'] ) ) {
						$mimp = hsb_multi_implode( $_POST['tax_input'], ',' );
						update_option( 'hsb_opt_omit_custom_cats', $mimp );
					}
					else {
						update_option( 'hsb_opt_omit_custom_cats', '' );
					}

					//successful update message
					print( '
				<div id="message" class="my-updated fade">
					<p>' . __( '<em>HYPE Social</em> - Buffer (Options Updated).', 'HYPESocialBuffer' ) . '</p>
				</div>' );
				} //post now clicked
				elseif ( isset( $_POST['post'] ) ) {
					$post_msg = $this->hsb_opt_buffer_my_post();
					print( '
				<div id="message" class="my-updated fade">
					<p>' . __( $post_msg, 'HYPESocialBuffer' ) . '</p>
				</div>' );
				}
				elseif ( isset( $_POST['reset'] ) ) {
					$this->hsb_reset_settings();
					echo '<script language="javascript">window.location.href= "' . site_url() . '/wp-admin/admin.php?page=HYPESocialBuffer&hsb=reset";</script>';
					die;
				}

				//set up data into fields from db
				//check data for option to pause sending to buffer
				$hsb_disable_buffer = get_option( 'hsb_disable_buffer' );
				if ( ! isset( $hsb_disable_buffer ) ) {
					$hsb_disable_buffer = "";
				}
				elseif ( ! $hsb_disable_buffer ) {
					$hsb_disable_buffer = "";
				}
				elseif ( $hsb_disable_buffer ) {
					$hsb_disable_buffer = "checked";
				}

				//Current URL
				$post_format = get_option( 'hsb_opt_post_format' );
				if ( ! isset( $post_format ) || $post_format == "" ) {
					$post_format = "{title} {url}";
					update_option( 'hsb_opt_post_format', $post_format );
				}

				$access_token = get_option( 'hsb_opt_access_token' );
				if ( ! isset( $access_token ) ) {
					$access_token = "";
					update_option( 'hsb_opt_access_token', $access_token );
				}


				//interval
				$interval = get_option( 'hsb_opt_interval' );
				if ( ! ( isset( $interval ) && is_numeric( $interval ) ) ) {
					$interval = hsb_opt_INTERVAL;
				}

				//min age limit
				$ageLimit = get_option( 'hsb_opt_age_limit' );
				if ( ! ( isset( $ageLimit ) && is_numeric( $ageLimit ) ) ) {
					$ageLimit = hsb_opt_AGE_LIMIT;
				}

				//max age limit
				$maxAgeLimit = get_option( 'hsb_opt_max_age_limit' );
				if ( ! ( isset( $maxAgeLimit ) && is_numeric( $maxAgeLimit ) ) ) {
					$maxAgeLimit = hsb_opt_MAX_AGE_LIMIT;
				}

				//number of post to post
				$hsb_opt_no_of_post = get_option( 'hsb_opt_no_of_post' );
				if ( ! ( isset( $hsb_opt_no_of_post ) && is_numeric( $hsb_opt_no_of_post ) ) ) {
					$hsb_opt_no_of_post = "1";
				}

				//buffer profile
				$acntids = get_option( 'hsb_opt_acnt_id' );

				if ( ! ( isset( $acntids ) ) ) {
					$acntids = "";
				}

				//type of post to post
				$hsb_opt_post_type = get_option( 'hsb_opt_post_type' );
				if ( ! isset( $hsb_opt_post_type ) ) {
					$hsb_opt_post_type = "post";
				}


				//check enable log
				$hsb_enable_log = get_option( 'hsb_enable_log' );
				if ( ! isset( $hsb_enable_log ) ) {
					$hsb_enable_log = "";
				}
				elseif ( $hsb_enable_log ) {
					$hsb_enable_log = "checked";
				}
				else {
					$hsb_enable_log = "";
				}

				//set omitted categories
				$omitCats = get_option( 'hsb_opt_omit_cats' );
				if ( ! isset( $omitCats ) ) {
					$omitCats = hsb_opt_OMIT_CATS;
				}

				//set omitted categories
				$omitCustomCats = get_option( 'hsb_opt_omit_custom_cats' );
				if ( ! isset( $omitCustomCats ) ) {
					$omitCustomCats = hsb_opt_OMIT_CUSTOM_CATS;
				}


				$x = WP_PLUGIN_URL . '/' . str_replace( basename( __FILE__ ), "", plugin_basename( __FILE__ ) );
				?>
				<div class="wrap">
					<div id="gopro">
						<div class="hsb_left">
							<img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/hype-social-logo-640.png'; ?>"
							     alt="HYPE Social - Buffer Logo"/>
							<!--
						<h1>
							<b><?php _e( '<em>HYPE Social</em> - Buffer&nbsp;&nbsp;(Settings) ', 'HYPESocialBuffer' ); ?></b>
						</h1>
						-->
						</div>
						<div style="float: right;" class="">
							<a class="button-primary" id="doc_button" target="_blank" download="hypesocial-buffer-pro.pdf"
							   href="<?php echo plugin_dir_url( __FILE__ ); ?>/documentation/hypesocial-buffer-pro.pdf"><?php _e( 'Documentation ', 'HYPESocialBuffer' ); ?></a>

						</div>
					</div>
					<div class="width70 hsb_left background-white">
						<form id="hsb_opt" name="hsb_HYPESocialBuffer" action="#" method="post">
							<input type="hidden" name="hsb_opt_action" value="hsb_opt_update_settings"/>
							<fieldset class="options">
								<ul class="hsb_tabs">
									<li><a href="#hsb_tab1"><?php _e( 'Enable/Disable', 'HYPESocialBuffer' ) ?></a></li>
									<li><a href="#hsb_tab2"><?php _e( 'Format & Hashtags', 'HYPESocialBuffer' ) ?></a></li>
									<li><a href="#hsb_tab3"><?php _e( 'Intervals', 'HYPESocialBuffer' ) ?></a></li>
									<li><a href="#hsb_tab4"><?php _e( 'Sharing Options', 'HYPESocialBuffer' ) ?></a></li>
									<li><a href="#hsb_tab5"><?php _e( 'Accounts', 'HYPESocialBuffer' ) ?></a></li>
									<li><a href="#hsb_tab6"><?php _e( 'Include Categories', 'HYPESocialBuffer' ) ?></a></li>
								</ul>
								<div class="clr"></div>
								<section class="block" style="border-bottom: 1px dashed #ccc;">
									<article id="hsb_tab1">
										<?php
										print( '<div class="option category"> ' . "<br/>" .
										       '<label for="hsb_opt_access_token">' . __( 'Your Buffer App Access Token: <br /><span class="desc">Copy your Buffer app access token here.</span> ', 'HYPESocialBuffer' ) . '</label>' . "<br/>" .
										       '<input type="text" id="hsb_opt_access_token" value="' . $access_token . '" name="hsb_opt_access_token" /> ' . " " . "<br/>&nbsp;&nbsp;&nbsp;&nbsp;<a href=" . plugin_dir_url( __FILE__ ) . "documentation/index.html#!/connect_with_buffer_app/" . " target='_blank'>How can I create/obtain Buffer App Access Token?</a>" .
										       '</div>' . "<br/>" );
										print( '<br/><div class="option"><label for="hsb_disable_buffer">' . __( 'Pause HYPE Social - Buffer ', 'HYPESocialBuffer' ) . '<span class="desc"> ' . __( 'This will pause HYPE Social - Buffer PRO from sending posts to Buffer.', 'HYPESocialBuffer' ) . ' </span></label>
				<input type="checkbox" name="hsb_disable_buffer" id="hsb_disable_buffer" ' . $hsb_disable_buffer . ' /></div><br />' );
										print( '<div class="option"><br/><label for="hsb_enable_log">' . __( 'Enable Log: ', 'HYPESocialBuffer' ) . '</label>
								<input type="checkbox" name="hsb_enable_log" id="hsb_enable_log" ' . $hsb_enable_log . ' />
	                                                        <b>' . __( 'saves log in log folder', 'HYPESocialBuffer' ) . '  </b>

							</div>' );
										?>
									</article>
									<article id="hsb_tab2">
										<?php
										print( '<div class="option">  ' . "<br/>" .
										       '<label for="hsb_opt_post_format"  class="hsb_opt_post_format_label">' . __( 'Post Format: <br/> <span class="desc">(Format of the Post to Buffer)</span>', 'HYPESocialBuffer' ) . '</label> ' . "<br/>" .
										       '<input type="text" style="width:500px" id="hsb_opt_post_format" value="' . $post_format . '" name="hsb_opt_post_format" /><br/>' . "<br/>" .
										       '<div class="clear" style="text-align: left; padding-left:80px; display: block;"><strong>Define the post format using tags.</strong>
				       <br/>  Valid tags are:<br/>
					{sitename}: the title of your blog<br/>
					{title}: the title of your blog post<br/>
					{excerpt}: a short excerpt of the post content<br/>
					{category}: the first selected category for the post<br/>
					{date}: the post date<br/>
					{url}: the post URL<br/>
					{author}: the post author <br/></div>' . "<br/>" .
										       '</div>' . "<br/>" );
										?>
									</article>
									<article id="hsb_tab3">
										<?php
										print( '<div class="option">' . "<br/>" .
										       '<label for="hsb_opt_interval">' . __( 'Choose the time between your posts (in hours): <br /><span class="desc"></span> ', 'HYPESocialBuffer' ) . '</label>' . "<br/>" .
										       '<input type="text" id="hsb_opt_interval" maxlength="5" value="' . $interval . '" name="hsb_opt_interval" /> Hour / Hours <b>(Note: If set to 0 it will take default of 1 hours)</b>' . "<br/>" .
										       '</div>' );

										print( '<div class="option">' . "<br/>" .
										       '<label for="hsb_opt_age_limit">' . __( 'Minimum age of post (in days) to be eligible for sharing: <br />', 'HYPESocialBuffer' ) . '</label>
								<input type="text" id="hsb_opt_age_limit" maxlength="5" value="' . $ageLimit . '" name="hsb_opt_age_limit" />
								<b> Set it at 0 if you want all your posts to be shared.</b>' . "<br/>" .

										       '</div>' );

										print( '<div class="option">' . "<br/>" .
										       '<label for="hsb_opt_max_age_limit">' . __( 'Maximum age of post (in days) to be eligible for sharing: <br /><span class="desc">Posts older than specified days will not be shared.</span>', 'HYPESocialBuffer' ) . '</label>
	                                                        <input type="text" id="hsb_opt_max_age_limit"  maxlength="5" value="' . $maxAgeLimit . '" name="hsb_opt_max_age_limit" /> <strong>Set it at 0 if you want all your posts to be shared.</strong>
					</div>' );
										?>
									</article>
									<article id="hsb_tab4">
										<?php

										print( '<br/><div class="option">
								<label for="hsb_opt_no_of_post">' . __( 'Number of Posts/Pages to Share:<br/><span class="desc">Number of posts/pages to share each time.</span>', 'HYPESocialBuffer' ) . '</label>
								<input type="text" style="width:30px" id="hsb_opt_no_of_post" value="' . $hsb_opt_no_of_post . '" name="hsb_opt_no_of_post" />
							</div>' );


										print( '<div class="option">
								<label for="hsb_opt_post_type">' . __( 'Post Type:<br/> <span class="desc">Choose the post type to share.</span>', 'HYPESocialBuffer' ) . '</label>
								<select id="hsb_opt_post_type" name="hsb_opt_post_type" style="width:150px">
									<option value="post" ' . $this->hsb_opt_optionselected( "post", $hsb_opt_post_type ) . '>' . __( ' Post Only ', 'HYPESocialBuffer' ) . ' </option>
									<option value="page" ' . $this->hsb_opt_optionselected( "page", $hsb_opt_post_type ) . '>' . __( ' Page Only ', 'HYPESocialBuffer' ) . ' </option>
									<option value="both" ' . $this->hsb_opt_optionselected( "both", $hsb_opt_post_type ) . '>' . __( ' Post & Page ', 'HYPESocialBuffer' ) . ' </option>
								</select>

							</div><br />' );
										?>
									</article>
									<article id="hsb_tab5">
										<?php
										print( '<br/><div class="option">
	                        <label for="hsb_opt_acnt_type">' . __( 'Accounts:<br/> <span class="desc">Choose accounts to share your content to.</span>', 'HYPESocialBuffer' ) . '</label>
	                        <div>' );

										$access_token = get_option( 'hsb_opt_access_token' );
										if ( ! empty( $access_token ) ) {
											$this->hsb_get_profiles( $access_token, $acntids );
										}

										?>
									</article>
									<article id="hsb_tab6">
										<?php

										print( '<br/>
<div class="option category">
					    	<div style="float:left">
							    	<label class="catlabel">' . __( 'Categories to share from posts: <br/><span class="desc">(Only check categories you want to share.)</span> ', 'HYPESocialBuffer' ) . '</label> </div><br/>
	         <br/>' );
										print( '<div style="float:left">' );
										print( '<ul id="categorychecklist" class="list:category categorychecklist form-no-clear">' );

										wp_category_checklist( 0, 0, explode( ',', $omitCats ) );
										//get custom posts
										$custom_posts    = hsb_get_custom_posts( 'objects' );
										$post_taxonomies = hsb_get_post_taxonomies( $custom_posts, 'object' );

										if ( ! empty( $custom_posts ) && ! empty( $post_taxonomies ) ) {
											//get post name label array $post_labels
											$post_labels = hsb_get_post_labels( $custom_posts, 'object' );
											//get post names array
											$post_names = array_values( $post_labels );
											//get post names from posts that have taxonomies
											$filtered_post_names = array_keys( $post_taxonomies );
											//compare post names and get diff
											$diff_post_names = array_diff( $post_names, $filtered_post_names );
											//array of post names and labels that have taxonomies
											$filtered_post_labels = array_diff_key( $post_labels, array_flip( $diff_post_names ) );

											//get category taxonomies
											$category_taxonomies = hsb_get_category_taxonomies( $post_taxonomies );
											//get the list of categories from "custom" posts
											print'</ul>
			                  </div>
			                  </div>
			                  <div class="option category clear">
							  <div style="float:left;">
			                  <label class="catlabel">' . __( 'Categories from custom posts: <br/><span class="desc">Only check categories to share.</span> ', 'HypeSocialBuffer' ) . '</label> </div>
			                  <div style="float:left;">
									            <ul id="cust_categorychecklist" class="list:category categorychecklist form-no-clear">';
											foreach ( $category_taxonomies as $taxonomy ) {
												echo "<li></li>";
												hsb_get_taxonomy_checklist( $taxonomy, explode( ',', $omitCustomCats ) );
											}
										}
										print( '				    		</ul>' );
										print( '</div></div></fieldset>' );
										?>
									</article>
								</section>
								<?php
								print( '<div style="clear:both; padding:40px;"><a href="' . site_url() . '/wp-admin/admin.php?page=HSBExcludePosts">' . __( 'Exclude Posts & Pages</a> from selected categories', 'HYPESocialBuffer' ) . '</div>' );


								?>

								<?php

								print( '
							<p class="hsb_submit">
							<input class="button-primary hsb_button" type="submit" name="submit" onclick="javascript:return hsb_validate()" value="' . __( 'Save/Update', 'HYPESocialBuffer' ) . '" />
							<input id="submitNow" class="button-primary hsb_button" type="submit" name="post" value="' . __( 'Post Now', 'HYPESocialBuffer' ) . '" />
	                        <input class="button-primary hsb_button" type="submit" onclick=\'return  hsb_resetSettings();\' name="reset" value="' . __( 'Reset Settings', 'HYPESocialBuffer' ) . '" />
						</p>

					</form>
					<div style="float:left; padding: 15px;">' . __( 'Please make sure to save changes with <strong>Save/Update</strong> button before clicking on <strong>Post Now</strong> button.', 'HYPESocialBuffer' ) . '</div>
					' );
								?>
					</div>

					<div class="width25" style="float: left;"><?php require_once( "hsb-sidebar.php" ) ?></div>

				</div>
				<?php
			}
			else {
				print( '
				<div id="message" class="my-updated fade">
					<p>' . __( 'You do not have enough permission to set the option. Please contact your admin.', 'HYPESocialBuffer' ) . '</p>
				</div>' );
			}

		}

		/**
		 * compares saved value if exist and generic value and returns 'selected="selected"' attribute
		 *
		 * @param string $opValue
		 * @param |string $value
		 *
		 * @return string
		 */
		public function hsb_opt_optionselected( $opValue, $value ) {
			if ( $opValue == $value ) {
				return 'selected="selected"';
			}

			return '';
		}

		/**
		 * saves custom field values for hsb data
		 *
		 * @return
		 */
		public function hsb_buffer_save_post_meta() {

			global $post;
			$hsb_disable_buffer_single = get_option( 'hsb_disable_buffer_single' );

			if ( ! isset( $_POST["hsb_buffer_repeat"] ) ) {
				return;
			}

			//remove post from posted posts if repeat set to more than 0
			if ( isset( $_POST["hsb_buffer_repeat"] ) && $_POST["hsb_buffer_repeat"] != 0 ) {
				$hsb_opt_posted_posts = get_option( 'hsb_opt_posted_posts' );
				if ( $hsb_opt_posted_posts != null ) {
					$post_id_exists = false;
					foreach ( $hsb_opt_posted_posts as $post_key => $postID ) {
						if ( $post->ID == $postID && $post_id_exists == false ) {
							unset( $hsb_opt_posted_posts[ $post_key ] );
							update_option( 'hsb_opt_posted_posts', $hsb_opt_posted_posts );
							$post_id_exists = true;
						}
					}

				}
			}
			update_post_meta( $post->ID, "hsb_buffer_repeat", $_POST["hsb_buffer_repeat"] );
			update_post_meta( $post->ID, "hsb_buffer_schedule_date", $_POST["hsb_buffer_schedule_date"] );


			if ( isset( $_POST["hsb_hours"] ) ) {
				if ( isset( $_POST["hsb_ampm"] ) && $_POST["hsb_ampm"] == 'pm' ) {
					$hours = $_POST["hsb_hours"] + 12;
					if ( $hours == 24 ) {
						$hours = '00';
					}
				}
				else {
					$hours = $_POST["hsb_hours"];
				}
				if ( isset( $_POST["hsb_minutes"] ) ) {
					$minutes = $_POST["hsb_minutes"];
				}
				else {
					$minutes = '00';
				}
				$time = $hours . ':' . $minutes;
			}
			$_POST["hsb_buffer_schedule_time"] = $time;

			update_post_meta( $post->ID, "hsb_buffer_schedule_time", $_POST["hsb_buffer_schedule_time"] );
			//skiep featured images
			update_post_meta( $post->ID, "hsb_buffer_image_skip", 1 );

			//add here scheduler and send to buffer with that time
			$schedule_at    = strtotime( $_POST["hsb_buffer_schedule_date"] . ' ' . $_POST["hsb_buffer_schedule_time"] );
			$current_time   = strtotime( 'now' );
			$date_scheduled = get_post_meta( $post->ID, 'hsb_buffer_schedule_date_scheduled', true );
			$time_scheduled = get_post_meta( $post->ID, 'hsb_buffer_schedule_time_scheduled', true );
			if ( $schedule_at > $current_time && ( $date_scheduled != $_POST["hsb_buffer_schedule_date"] || $time_scheduled != $_POST["hsb_buffer_schedule_time"] ) ) {
				$this->hsb_set_custom_schedule_time( $_POST["hsb_buffer_schedule_date"], $_POST["hsb_buffer_schedule_time"] );
				//send to buffer with schedule time and update meta so it doesn't send again unless date and time has changed again
				update_post_meta( $post->ID, "hsb_buffer_schedule_date_scheduled", $_POST["hsb_buffer_schedule_date"] );
				update_post_meta( $post->ID, "hsb_buffer_schedule_time_scheduled", $_POST["hsb_buffer_schedule_time"] );
				if ( ( ! isset( $_POST["hsb_buffer_send_now"] ) || $_POST["hsb_buffer_send_now"] != 1 ) && ! $hsb_disable_buffer_single ) {
					$this->hsb_publish( $post->ID, false, true );
				}
			}

			if ( isset( $_POST["hsb_buffer_send_now"] ) && $_POST["hsb_buffer_send_now"] == 1 && ! $hsb_disable_buffer_single ) {
				//send to buffer immediately
				$this->hsb_publish( $post->ID, false, false, true );
			}
		}

		/**
		 * Exclude settings page for excluded posts
		 *
		 * @return
		 */
		public function hsb_register() {
			if ( current_user_can( 'manage_options' ) ) {

			}

		}

		public function hsb_get_profiles( $access_token, $acntids ) {
			$profile_url = 'https://api.bufferapp.com/1/profiles.json?access_token=' . htmlentities( $access_token );
			$r           = wp_remote_get( $profile_url, array(
				'sslverify'  => false,
				'decompress' => false
			) );

			if ( ! function_exists( 'json_decode' ) ) {
				wp_die( 'A JSON library does not appear to be installed.\n\nPlease contact your server admin if you need help installing one.' );
			}
			else {
				$response = @json_decode( $r['body'] );
				if ( ! isset( $response ) || ! is_array( $response ) ) {
					print( '<p>' . __( 'Buffer has not returned an expected result', 'HYPESocialBuffer' ) . '<br />' . __( 'Please check your Token.', 'HYPESocialBuffer' ) . '</p>' );
				}
				else {
					$resp_count = count( $response );
					if ( $resp_count <= 1 ) {
						print( '<div class="buffer-account"><label><img src="' . $response[0]->avatar . '" width="48" height="48" alt="' . $response[0]->formatted_username . '" />
	              <input type="checkbox" name="profile" value="' . $response[0]->id . '" id="' . $response[0]->id . '" onchange="hsb_manageacntid(this,\'' . $response[0]->id . '\');"  />
	              <span class="' . $response[0]->service . '"></span></label></div>' );
					}
					else {
						print( '<div class="buffer-account"><label><img src="' . $response[0]->avatar . '" width="48" height="48" alt="' . $response[0]->formatted_username . '" />
	              <input type="checkbox" name="profile" value="' . $response[0]->id . '" id="' . $response[0]->id . '" onchange="hsb_manageacntid(this,\'' . $response[0]->id . '\');"  />
	              <span class="' . $response[0]->service . '"></span></label></div>' );
						print( '<div class="buffer-account"><label><img src="' . $response[1]->avatar . '" width="48" height="48" alt="' . $response[1]->formatted_username . '" />
	              <input type="checkbox" name="profile" value="' . $response[1]->id . '" id="' . $response[1]->id . '" onchange="hsb_manageacntid(this,\'' . $response[1]->id . '\');"  />
	              <span class="' . $response[1]->service . '"></span></label></div>' );

					}
				} //end else
			}
			print( '<input type="hidden" name="acntids" id="acntids" value="' . $acntids . '" /><br/>' );
		}

		/**
		 * Exclude settings page for excluded posts
		 *
		 * @return
		 */
		public function hsb_exclude() {
			if ( current_user_can( 'manage_options' ) ) {
				$message          = null;
				$message_updated  = __( "<em>HYPE Social - Buffer</em> (Options Updated).", 'HYPESocialBuffer' );
				$response         = null;
				$records_per_page = 20;
				$omit_cat         = get_option( 'hsb_opt_omit_cats' );
				$omitCustomCats   = get_option( 'hsb_opt_omit_custom_cats' );
				$update_text      = "Exclude/Update";
				$search_term      = "";
				$ex_filter        = "all";
				$cat_filter       = 0;

				global $wpdb;

				if ( ( ! isset( $_GET["paged"] ) ) && ( ! isset( $_POST["delids"] ) ) ) {
					$exposts = get_option( 'hsb_opt_excluded_post' );
				}
				else {
					$exposts = $_POST["delids"];
				}

				$exposts = preg_replace( '/,,+/', ',', $exposts );
				if ( substr( $exposts, 0, 1 ) == "," ) {
					$exposts = substr( $exposts, 1, strlen( $exposts ) );
				}
				if ( substr( $exposts, - 1, 1 ) == "," ) {
					$exposts = substr( $exposts, 0, strlen( $exposts ) - 1 );
				}
				$excluded_posts = explode( ",", $exposts );


				if ( ! isset( $_GET['paged'] ) ) {
					$_GET['paged'] = 1;
				}

				//submit
				if ( isset( $_POST["excludeall"] ) ) {
					if ( substr( $_POST["delids"], 0, - 1 ) == "" ) {
						print( '
				<div id="message" style="margin-hsb:30px" class="my-updated fade">
					<p>' . __( 'No post selected please select a post to be excluded.', 'HYPESocialBuffer' ) . '</p>
				</div>' );
					}
					else {

						update_option( 'hsb_opt_excluded_post', $exposts );
						print( '
				<div id="message" style="margin-hsb:30px" class="my-updated fade">
					<p>' . __( 'Posts excluded successfully.', 'HYPESocialBuffer' ) . '</p>
				</div>' );
					}
				}
				?>
				<div class="wrap">
				<div id="gopro">
					<div class="hsb_left">
						<img src="<?php echo plugin_dir_url( __FILE__ ) . 'images/hype-social-logo-640.png'; ?>"
						     alt="HYPE Social - Buffer Logo"/>
					</div>
					<div style="float: right;">
						<!--
						<a class="button-primary" id="doc_button" target="_blank"
						   href="<?php echo plugins_url() . '/hype-social-buffer/documentation/index.html' ?>"><?php _e( 'Documentation ', 'HYPESocialBuffer' ); ?></a> -->
					</div>
				</div>
				<?php
				print( '<p style="padding:10px;">' . __( 'Here you can find your posts & pages from your ', 'HYPESocialBuffer' ) . '<a href="' . site_url() . '/wp-admin/admin.php?page=HYPESocialBuffer">' . __( 'selected categories.', 'HYPESocialBuffer' ) . '</a> ' . __( ' In case you haven\'t selected any categories on your general settings page, you can find all your posts & pages from your site here.', 'HYPESocialBuffer' ) . __( '<br/>You can exclude any post and/or page that you don\'t want to share to Buffer App here (for example, the "contact us" page).<br/> All the posts & pages that you have selected here, will not be shared even if their category is selected in the general settings page. <br/>In order for those changes to be saved you have to click the <strong>Exclude/Update</strong> button after making your changes.' ) . '</p>' );
				print( '<p>You have selected the following POST IDs to be excluded from sharing: <span id="excludeList" style="font-weight:bold;font-style:italic;">""</span>.<br/> <strong>Note:</strong> If you have made any changes here and didn\'t click the <strong>Exclude/Update</strong> button those changes will not be saved. Please click the <strong>Exclude/Update</strong> button after making any changes.</p></div>' );

				$custom_posts = hsb_get_custom_posts( 'objects' );
				//var_dump($custom_posts);
				$post_taxonomies = hsb_get_post_taxonomies( $custom_posts, 'object' );
				$postnames       = '';
				if ( ! empty( $custom_posts ) && ! empty( $post_taxonomies ) ) {
					$custom_posts        = hsb_get_custom_posts( 'objects' );
					$post_taxonomies     = hsb_get_post_taxonomies( $custom_posts, 'object' );
					$post_labels         = hsb_get_post_labels( $custom_posts, 'object' );
					$post_names          = array_keys( $post_labels );
					$filtered_post_names = array_keys( $post_taxonomies );

					$customPostNames = implode( ',', array_map( 'hsb_add_quotes', $filtered_post_names ) );
					if ( $customPostNames != '' ) {
						$postnames = $customPostNames . ',' . '\'post\'';
					}
				}
				if ( empty( $postnames ) ) {
					$postnames = "'post'";
				}
				$post_type = "post_type IN(" . $postnames . ",'page')";

				$sql
					= "SELECT p.ID,p.post_title,p.post_date,u.user_nicename,p.guid,p.post_type FROM $wpdb->posts p join  $wpdb->users u on p.post_author=u.ID WHERE $post_type
	                  AND post_status = 'publish'";

				//if filter is set
				$args = array(
					'public'   => true,
					'_builtin' => false

				);

				$output   = 'names'; // or objects
				$operator = 'and'; // 'and' or 'or'
				if ( ! empty( $custom_posts ) && ! empty( $post_taxonomies ) ) {
					$taxonomies                 = get_taxonomies( $args, $output, $operator );
					$filtered_custom_taxonomies = array_keys( $taxonomies );
					$customTaxonomies           = implode( ',', array_map( 'hsb_add_quotes', $filtered_custom_taxonomies ) );
					$custom_taxonomies_merge    = $customTaxonomies . ',' . '\'category\'';
				}

				if ( isset( $_POST["setFilter"] ) ) {
						//echo "0";
					if ( $_POST["cat"] != 0 ) {
						//echo "1";
						$cat_filter = $_POST["cat"];
						$cat_filter = esc_attr( $cat_filter );

						$sql = $sql . " and p.ID IN ( SELECT tr.object_id FROM " . $wpdb->prefix . "term_relationships AS tr INNER JOIN " . $wpdb->prefix . "term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id WHERE tt.taxonomy = 'category' AND tt.term_id=" . $cat_filter . ")";

					}
					else {
						//echo "2";
						$sql        = $sql . " and p.ID IN ( SELECT tr.object_id FROM " . $wpdb->prefix . "term_relationships AS tr INNER JOIN " . $wpdb->prefix . "term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id WHERE tt.taxonomy = 'category' AND tt.term_id IN (" . $omit_cat . ',' . $omitCustomCats . "))";
						$cat_filter = 0;
					}

					if ( $_POST["selFilter"] == "excluded" ) {
						//echo "3";
						$sql         = $sql . " and p.ID IN (" . $exposts . ")";
						$update_text = "Update";
						$ex_filter   = "excluded";
					}

				} //no $_POST["setFilter"] all/excluded
				else {
					//categories and custom taxonomies
					if ( $omit_cat != '' && $omitCustomCats != '' && ! empty( $post_taxonomies ) ) {
						$sql = $sql . " and p.ID IN ( SELECT tr.object_id FROM " . $wpdb->prefix . "term_relationships AS tr INNER JOIN " . $wpdb->prefix . "term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id WHERE tt.taxonomy IN ($custom_taxonomies_merge) AND tt.term_id IN (" . $omit_cat . ',' . $omitCustomCats . "))";
						//categories only
					}
					elseif ( $omit_cat != '' ) {
						//echo "6";
						$sql = $sql . " and p.ID IN ( SELECT tr.object_id FROM " . $wpdb->prefix . "term_relationships AS tr INNER JOIN " . $wpdb->prefix . "term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id WHERE tt.term_id IN (" . $omit_cat . "))";
						//custom taxonomies
						//echo $sql;
					}
					elseif ( $omitCustomCats != '' && ! empty( $post_taxonomies ) ) {
						//echo "7";
						$sql = $sql . " and p.ID IN ( SELECT tr.object_id FROM " . $wpdb->prefix . "term_relationships AS tr INNER JOIN " . $wpdb->prefix . "term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id WHERE tt.taxonomy IN ($custom_taxonomies_merge) AND tt.term_id IN (" . $omitCustomCats . "))";
					}
				}


				if ( isset( $_POST["s"] ) ) {
					//echo "8";
					if ( trim( $_POST["s"] ) != "" ) {
					//	echo "9";
						$_s          = filter_input( INPUT_POST, 's', FILTER_SANITIZE_STRING );
						$sql         = $sql . " and post_title like '%" . trim( $_s ) . "%'";
						$search_term = trim( $_s );
					}
				}

				$sql   = $sql . " order by post_date desc";
				$posts = $wpdb->get_results( $sql );

				$from       = $_GET["paged"] * $records_per_page - $records_per_page;
				$to         = min( $_GET['paged'] * $records_per_page, count( $posts ) );
				$post_count = count( $posts );

				$ex = 0;
				for ( $j = 0; $j < $post_count; $j ++ ) {
					if ( in_array( $posts[ $j ]->ID, $excluded_posts ) ) {
						$excludeList[ $ex ] = $posts[ $j ]->ID;
						$ex                 = $ex + 1;
					}
				}
				if ( isset( $excludeList ) && count( $excludeList ) > 0 ) {
					$exposts = implode( ",", $excludeList );
				}
				print( '<form id="hsb_HYPESocialBuffer" name="hsb_HYPESocialBuffer" action="' . site_url() . '/wp-admin/admin.php?page=HSBExcludePosts" method="post"><input type="hidden" name="delids" id="delids" value="' . $exposts . '" /><input type="submit" id="pageit" name="pageit" style="display:none" value="" /> ' );
				print( '<div class="tablenav"><div class="alignleft actions">' );
				$dropdown_options = array(
					'show_option_all' => __( 'Selected Categories', 'HYPESocialBuffer' ),
					'exclude'         => $omit_cat,
					'selected'        => $cat_filter
				);
				print( '<br/><p class="search-box" style="margin:0px">
		<input type="text" id="post-search-input" name="s" value="' . $search_term . '" />
		<input type="submit" value="' . __( 'Search Posts', 'HYPESocialBuffer' ) . '" name="search" class="button-primary hsb_button" />
	<br/><br/></p>' );
				print( '</div>' );
				if ( count( $posts ) > 0 ) {

					$page_links = paginate_links( array(
						'base'      => add_query_arg( 'paged', '%#%' ),
						'format'    => '',
						'prev_text' => __( '&laquo;', 'HYPESocialBuffer' ),
						'next_text' => __( '&raquo;', 'HYPESocialBuffer' ),
						'total'     => ceil( count( $posts ) / $records_per_page ),
						'current'   => $_GET['paged']
					) );

					if ( $page_links ) {

						print( '<div class="tablenav-pages">' );
						$page_links_text = sprintf( '<span class="displaying-num">' . __( 'Displaying %s&#8211;%s of %s', 'HYPESocialBuffer' ) . '</span>%s',
							number_format_i18n( ( $_GET['paged'] - 1 ) * $records_per_page + 1 ),
							number_format_i18n( min( $_GET['paged'] * $records_per_page, count( $posts ) ) ),
							number_format_i18n( count( $posts ) ),
							$page_links
						);
						echo $page_links_text;
						print( '</div>' );
					}
					print( '</div>' );//tablenav div

					print( '	<div class="wrap">
					<table class="widefat fixed">
						<thead>
						<tr>
							<th class="manage-column column-cb check-column">&nbsp;
							</th>
							<th>' . __( 'No.', 'HYPESocialBuffer' ) . '</th>
							<th>' . __( 'Id', 'HYPESocialBuffer' ) . '
</th>
							<th>' . __( 'Post Title', 'HYPESocialBuffer' ) . '</th>
							<th>' . __( 'Author', 'HYPESocialBuffer' ) . '</th>
							<th>' . __( 'Post Date', 'HYPESocialBuffer' ) . '</th>
	                                                <th>' . __( 'Categories', 'HYPESocialBuffer' ) . '</th>
	                                                <th>' . __( 'Post Type', 'HYPESocialBuffer' ) . '</th>
						</tr>
						</thead>
						<tbody>
			' );


					for ( $i = $from; $i < $to; $i ++ ) {
						$categories = get_the_category( $posts[ $i ]->ID );
						if ( ! empty( $categories ) ) {
							$out = array();
							foreach ( $categories as $c ) {
								$out[] = "<a href='edit.php?post_type={$posts[$i]->post_type}&amp;category_name={$c->slug}'> " . esc_html( sanitize_term_field( 'name', $c->name, $c->term_id, 'category', 'display' ) ) . "</a>";
							}
							$cats = join( ', ', $out );
						}
						else {
							$cats = 'Uncategorized';
						}

						if ( in_array( $posts[ $i ]->ID, $excluded_posts ) ) {
							$checked = "Checked";
							$bgcolor = "#FFCC99";
						}
						else {
							$checked = "";
							$bgcolor = "#FFF";
						}

						print( '

					<tr style="background-color:' . $bgcolor . ';">
						<th class="check-column">
							<input type="checkbox" name="chkbx" id="del' . $posts[ $i ]->ID . '" onchange="javascript:hsb_managedelid(this,\'' . $posts[ $i ]->ID . '\');" value="' . $posts[ $i ]->ID . '" ' . $checked . '/>
						</th>
						<td>
							' . ( $i + 1 ) . '
						</td>
						<td>
							' . $posts[ $i ]->ID . '
						</td>
						<td>
							<a href=' . $posts[ $i ]->guid . ' target="_blank">' . $posts[ $i ]->post_title . '</a>
						</td>
						<td>
	                                            ' . $posts[ $i ]->user_nicename . '
	                                        </td>
	                                        <td>
	                                            ' . $posts[ $i ]->post_date . '
	                                        </td>
	                                        <td>
	                                            ' . $cats . '
	                                        </td>
	                                        <td>
	                                            ' . $posts[ $i ]->post_type . '
	                                        </td>
					</tr>

				' );
					}
					print( '
					</tbody>
					</table>
				</div>
			' );

					print( '<div class="wrap tablenav"><div class="alignleft actions"><input type="submit" class="button-primary hsb_button" name="excludeall" value="' . __( $update_text, 'HYPESocialBuffer' ) . '" /></div>' );

					if ( $page_links ) {

						print( '<div class="tablenav-pages">' );
						$page_links_text = sprintf( '<span class="displaying-num">' . __( 'Displaying %s&#8211;%s of %s', 'HYPESocialBuffer' ) . '</span>%s',
							number_format_i18n( ( $_GET['paged'] - 1 ) * $records_per_page + 1 ),
							number_format_i18n( min( $_GET['paged'] * $records_per_page, count( $posts ) ) ),
							number_format_i18n( count( $posts ) ),
							$page_links
						);
						echo $page_links_text;
						print( '</div>' );
					}
					print( '</div>' );
					print( '<script language="javascript">  window.hsb_exposts = "' . $exposts . '";  </script>' );
				}
				else {
					print( '</div>' );//tablenav div
					print( '
				<div id="message" style="margin-hsb:30px" class="my-updated fade">
					<p>' . __( 'No Posts found. Review your search or filter criteria/term.', 'HYPESocialBuffer' ) . '</p>
				</div>' );
				}
				print( '</form>' );
			}
			else {
				print( '
				<div id="message" class="my-updated fade">
					<p>' . __( 'You do not have enough permission to set the option. Please contact your admin.', 'HYPESocialBuffer' ) . '</p>
				</div>' );
			}
		}
	}

	global $hsb_admin;
	$hsb_admin = new HsbAdmin;
}
