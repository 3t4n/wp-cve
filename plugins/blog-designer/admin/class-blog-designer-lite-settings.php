<?php
/**
 * The admin-settings functionality of the plugin.
 *
 * @link       https://www.solwininfotech.com
 * @since      1.8.2
 *
 * @package    Blog_Designer
 * @subpackage Blog_Designer/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Blog_Designer
 * @subpackage Blog_Designer/admin
 * @author     Solwin Infotech <support@solwininfotech.com>
 */
class Blog_Designer_Lite_Settings {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.8.2
	 */
	public function __construct() {

	}
	/**
	 * Selected tab
	 *
	 * @param type $id id.
	 * @param type $page page.
	 * @return type closed class.
	 */
	public static function bd_postbox_classes( $id, $page ) {
		if ( ! isset( $_GET['action'] ) ) {
			$closed = array( 'bdpgeneral' );
			$closed = array_filter( $closed );
			$page   = 'designer_settings';
			$user   = wp_get_current_user();
			if ( is_array( $closed ) ) {
				update_user_option( $user->ID, "bdpclosedbdpboxes_$page", $closed, true );
			}
		}
		$closed = get_user_option( 'bdpclosedbdpboxes_' . $page );
		if ( $closed ) {
			if ( ! is_array( $closed ) ) {
				$classes = array( '' );
			} else {
				$classes = in_array( $id, $closed ) ? array( 'closed' ) : array( '' );
			}
		} else {
			$classes = array( '' );
		}
		return implode( ' ', $classes );
	}

	/**
	 * Html Display setting options
	 */
	public static function bd_main_menu_function() {
		global $wp_version;
		$uic_l     = 'ui-corner-left';
		$uic_r     = 'ui-corner-right';
		$args_kses = Blog_Designer_Lite_Template::args_kses();
		?>
		<div class="wrap">
			<h2><?php esc_html_e( 'Blog Designer Settings', 'blog-designer' ); ?></h2>
			<div class="updated notice notice-success bdp_a_notice" id="message">
				<p><a href="<?php echo esc_url( 'https://www.solwininfotech.com/documents/wordpress/blog-designer/' ); ?>" target="_blank"><?php esc_html_e( 'Read Online Documentation', 'blog-designer' ); ?></a>
				<a style="margin-left:50px;" href="<?php echo esc_url( 'http://blogdesigner.solwininfotech.com' ); ?>" target="blank"><?php esc_html_e( 'See Live Demo', 'blog-designer' ); ?></a></p>
				<p><?php echo esc_html__( 'Get access to ', 'blog-designer' ) . ' <b>' . esc_html__( '50+ new layouts', 'blog-designer' ) . '</b> ' . esc_html__( 'and', 'blog-designer' ) . ' <b>' . esc_html__( '150+ new premium', 'blog-designer' ) . '</b> ' . esc_html__( ' features.', 'blog-designer' ); ?> </p>
				<p class="bdp_green_p"><b><a href="<?php echo esc_url( 'https://codecanyon.net/item/blog-designer-pro-for-wordpress/17069678?ref=solwin' ); ?>" target="blank"><?php esc_html_e( 'Upgrade to PRO now', 'blog-designer' ); ?></a></b></p>
			</div>
			<?php
			$view_post_link = ( 0 != get_option( 'blog_page_display' ) ) ? '<span class="page_link"> <a target="_blank" href="' . esc_url( get_permalink( get_option( 'blog_page_display' ) ) ) . '"> ' . esc_html__( 'View Blog', 'blog-designer' ) . ' </a></span>' : '';
			if ( isset( $_REQUEST['bdRestoreDefault'] ) && isset( $_GET['updated'] ) && 'true' == sanitize_text_field( wp_unslash( $_GET['updated'] ) ) ) {
				echo '<div class="updated" ><p>' . esc_html__( 'Blog Designer settings restored successfully.', 'blog-designer' ) . ' ' . wp_kses( $view_post_link, $args_kses ) . '</p></div>';
			} elseif ( isset( $_GET['updated'] ) && 'true' == sanitize_text_field( wp_unslash( $_GET['updated'] ) ) ) {
				echo '<div class="updated" ><p>' . esc_html__( 'Blog Designer settings updated.', 'blog-designer' ) . ' ' . wp_kses( $view_post_link, $args_kses ) . '</p></div>';
			}
			$settings = get_option( 'wp_blog_designer_settings' );
			if ( isset( $_SESSION['success_msg'] ) ) {
				?>
				<div class="updated is-dismissible notice settings-error">
					<?php
					echo '<p>' . esc_html( $_SESSION['success_msg'] ) . '</p>';
					unset( $_SESSION['success_msg'] );
					?>
				</div>
				<?php
			}
			?>
			<form method="post" action="?page=designer_settings&action=save&updated=true" class="bd-form-class">
				<?php
				$page = '';
				if ( isset( $_GET['page'] ) && '' != $_GET['page'] ) {
					$page = sanitize_text_field( wp_unslash( $_GET['page'] ) );
					?>
					<input type="hidden" name="originalpage" class="bdporiginalpage" value="<?php echo esc_attr( $page ); ?>">
				<?php } ?>
				<div class="wl-pages" >
					<div class="bd-settings-wrappers bd_poststuff">
						<div class="bd-header-wrapper">
							<div class="bd-logo-wrapper pull-left">
								<h3><?php esc_html_e( 'Blog designer settings', 'blog-designer' ); ?></h3>
							</div>
							<div class="pull-right">
								<input type="text" readonly="" onclick="this.select()" class="copy_shortcode" title="Copy Shortcode" value="[wp_blog_designer]">
								<a id="bd-submit-button" title="<?php esc_html_e( 'Save Changes', 'blog-designer' ); ?>" class="button">
									<span><i class="fas fa-check"></i>&nbsp;&nbsp;<?php esc_html_e( 'Save Changes', 'blog-designer' ); ?></span>
								</a>
								<a id="bd-show-preview" title="<?php esc_html_e( 'Show Preview', 'blog-designer' ); ?>" class="button show_preview button-hero pro-feature" href="#">
									<span><i class="fas fa-eye"></i>&nbsp;&nbsp;<?php esc_html_e( 'Preview', 'blog-designer' ); ?></span>
								</a>
							</div>
						</div>
						<div class="bd-menu-setting">
							<?php
							$bdpgeneral_class             = '';
							$dbptimeline_class            = '';
							$bdpstandard_class            = '';
							$bdptitle_class               = '';
							$bdpcontent_class             = '';
							$bdpmedia_class               = '';
							$bdpslider_class              = '';
							$bdpcustomreadmore_class      = '';
							$bdpsocial_class              = '';
							$bdpslider_class              = '';
							$bdads_class                  = '';
							$bdpgeneral_class_show        = '';
							$dbptimeline_class_show       = '';
							$bdpstandard_class_show       = '';
							$bdptitle_class_show          = '';
							$bdpcontent_class_show        = '';
							$bdpmedia_class_show          = '';
							$bdpslider_class_show         = '';
							$bdpcustomreadmore_class_show = '';
							$bdpsocial_class_show         = '';
							$bdads_class_show             = '';
							$bdppagination_class_show     = '';
							$bdppagination_class          = '';
							if ( self::bd_postbox_classes( 'bdpgeneral', $page ) ) {
								$bdpgeneral_class      = 'bd-active-tab';
								$bdpgeneral_class_show = 'display:block';
							} elseif ( self::bd_postbox_classes( 'dbptimeline', $page ) ) {
								$dbptimeline_class      = 'bd-active-tab';
								$dbptimeline_class_show = 'display:block;';
							} elseif ( self::bd_postbox_classes( 'bdpstandard', $page ) ) {
								$bdpstandard_class      = 'bd-active-tab';
								$bdpstandard_class_show = 'display:block;';
							} elseif ( self::bd_postbox_classes( 'bdptitle', $page ) ) {
								$bdptitle_class      = 'bd-active-tab';
								$bdptitle_class_show = 'display:block;';
							} elseif ( self::bd_postbox_classes( 'bdpcontent', $page ) ) {
								$bdpcontent_class      = 'bd-active-tab';
								$bdpcontent_class_show = 'display:block;';
							} elseif ( self::bd_postbox_classes( 'bdpmedia', $page ) ) {
								$bdpmedia_class      = 'bd-active-tab';
								$bdpmedia_class_show = 'display:block;';
							} elseif ( self::bd_postbox_classes( 'bdpslider', $page ) ) {
								$bdpslider_class      = 'bd-active-tab';
								$bdpslider_class_show = 'display:block;';
							} elseif ( self::bd_postbox_classes( 'bdpcustomreadmore', $page ) ) {
								$bdpcustomreadmore_class      = 'bd-active-tab';
								$bdpcustomreadmore_class_show = 'display:block;';
							} elseif ( self::bd_postbox_classes( 'bdppagination', $page ) ) {
								$bdppagination_class      = 'bd-active-tab';
								$bdppagination_class_show = 'display:block;';
							} elseif ( self::bd_postbox_classes( 'bdpsocial', $page ) ) {
								$bdpsocial_class      = 'bd-active-tab';
								$bdpsocial_class_show = 'display:block;';
							} elseif ( self::bd_postbox_classes( 'bdpads', $page ) ) {
								$bdads_class      = 'bd-active-tab';
								$bdads_class_show = 'display:block;';
							} else {
								$bdpgeneral_class      = 'bd-active-tab';
								$bdpgeneral_class_show = 'display:block;';
							}
							?>
							<ul class="bd-setting-handle">
								<li data-show="bdpgeneral" class=<?php echo esc_attr( $bdpgeneral_class ); ?>>
									<i class="fas fa-cog"></i>
									<span><?php esc_html_e( 'General Settings', 'blog-designer' ); ?></span>
								</li>
								<li data-show="bdpstandard" class=<?php echo esc_attr( $bdpstandard_class ); ?>>
									<i class="fas fa-gavel"></i>
									<span><?php esc_html_e( 'Standard Settings', 'blog-designer' ); ?></span>
								</li>
								<li data-show="bdptitle" class=<?php echo esc_attr( $bdptitle_class ); ?>>
									<i class="fas fa-text-width"></i>
									<span><?php esc_html_e( 'Post Title Settings', 'blog-designer' ); ?></span>
								</li>
								<li data-show="bdpcontent" class=<?php echo esc_attr( $bdpcontent_class ); ?>>
									<i class="far fa-file-alt"></i>
									<span><?php esc_html_e( 'Post Content Settings', 'blog-designer' ); ?></span>
								</li>
								<li data-show="bdpslider" class=<?php echo esc_attr( $bdpslider_class ); ?>>
									<i class="fas fa-sliders-h"></i>
									<span><?php esc_html_e( 'Slider Settings', 'blog-designer' ); ?></span>
								</li>
								<li data-show="bdpmedia" class=<?php echo esc_attr( $bdpmedia_class ); ?>>
									<i class="far fa-image"></i>
									<span><?php esc_html_e( 'Media Settings', 'blog-designer' ); ?></span>
								</li>
								<li data-show="bdppagination" class=<?php echo esc_attr( $bdppagination_class ); ?>>
									<i class="fas fa-angle-double-right"></i>
									<span><?php esc_html_e( 'Pagination Settings', 'blog-designer-pro' ); ?></span>
								</li>
								<li data-show="bdpsocial" class=<?php echo esc_attr( $bdpsocial_class ); ?>>
									<i class="fas fa-share-alt"></i>
									<span><?php esc_html_e( 'Social Share Settings', 'blog-designer' ); ?></span>
								</li>
								<?php if ( is_plugin_active( 'blog-designer-ads/blog-designer-ads.php' ) ) { ?>
									<?php do_action( 'bdads_do_blog_settings', 'tab' ); ?>
									<?php
								} else {
									?>
									<li data-show="bdpads" class=<?php echo esc_attr( $bdads_class ); ?>>
										<i class="fab fa-adversal"></i>
										<span><?php esc_html_e( 'Ads Settings', 'blog-designer-pro' ); ?></span>
									</li>
									<?php
								}
								?>
							</ul>
						</div>
						<div id="bdpgeneral" class="postbox postbox-with-fw-options" style=<?php echo esc_attr( $bdpgeneral_class_show ); ?>>
							<ul class="bd-settings">
								<li>
									<h3 class="bd-table-title"><?php esc_html_e( 'Select Blog Layout', 'blog-designer' ); ?></h3>
									<div class="bd-left">
										<p class="bd-margin-bottom-50"><?php esc_html_e( 'Select your favorite layout from 15 free layouts.', 'blog-designer' ); ?> <b>
											<?php
											echo wp_kses(
												'Upgrade for just $59 to access 50+ brand new layouts and other premium features.',
												array(
													'del'  => array( 'class' => true ),
													'span' => array( 'class' => true ),
												)
											);
											?>
										</b></p>
										<p class="bd-margin-bottom-30"><b><?php esc_html_e( 'Current Template:', 'blog-designer' ); ?></b> &nbsp;&nbsp;
											<span class="bd-template-name">
												<?php
												if ( isset( $settings['template_name'] ) ) {
													echo esc_attr( str_replace( '_', '-', $settings['template_name'] ) ) . ' ';
													esc_html_e( 'Template', 'blog-designer' );
												}
												?>
											</span></p>
										<div class="bd_select_template_button_div">
											<input type="button" class="bd_select_template" value="<?php esc_attr_e( 'Select Other Template', 'blog-designer' ); ?>">
										</div>
										<?php
										$template_name = '';
										if ( isset( $settings['template_name'] ) && '' != $settings['template_name'] ) {
											$template_name = $settings['template_name']; }
										?>
										<input type="hidden" name="template_name" id="template_name" value="<?php echo esc_attr( $template_name ); ?>" />
										<div class="bd_select_template_button_div">
											<a id="bd-reset-button" title="<?php esc_html_e( 'Reset Layout Settings', 'blog-designer' ); ?>" class="bdp-restore-default button change-theme">
												<span><?php esc_html_e( 'Reset Layout Settings', 'blog-designer' ); ?></span>
											</a>
										</div>
									</div>
									<div class="bd-right">
										<div class="select-cover select-cover-template">
											<div class="bd_selected_template_image">
												<div 
												<?php
												if ( isset( $settings['template_name'] ) && empty( $settings['template_name'] ) ) {
													echo ' class="bd_no_template_found"';
												}
												?>
													>
													<?php
													if ( isset( $settings['template_name'] ) && ! empty( $settings['template_name'] ) ) {
														$image_name = $settings['template_name'] . '.jpg';
														?>
														<img src="<?php echo esc_url( BLOGDESIGNER_URL ) . 'admin/images/layouts/' . esc_attr( $image_name ); ?>" alt="
														<?php
														if ( isset( $settings['template_name'] ) ) {
															echo esc_attr( str_replace( '_', '-', $settings['template_name'] ) ) . ' ';
															esc_attr_e( 'Template', 'blog-designer' );
														}
														?>
															" title="
															<?php
															if ( isset( $settings['template_name'] ) ) {
																echo esc_attr( str_replace( '_', '-', $settings['template_name'] ) ) . ' ';
																esc_attr_e( 'Template', 'blog-designer' );
															}
															?>
															" />
														<label id="bd_template_select_name">
															<?php
															if ( isset( $settings['template_name'] ) ) {
																echo esc_attr( str_replace( '_', '-', $settings['template_name'] ) ) . ' ';
																esc_html_e( 'Template', 'blog-designer' );
															}
															?>
														</label>
														<?php
													} else {
														esc_html_e( 'No template exist for selection', 'blog-designer' );
													}
													?>
												</div>
											</div>
										</div>
									</div>
								</li>
								<li class="bd-caution">
									<div class="bdp-setting-caution">
										<b><?php esc_html_e( 'Caution:', 'blog-designer' ); ?></b>
										<?php
										esc_html_e( 'You are about to select the page for your layout. This will overwrite all the content on the page that you will select. Changes once lost can not be recovered. Please be cautious!', 'blog-designer' );
										?>
									</div>
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( ' Select Page for Blog ', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Select page for display blog layout', 'blog-designer' ); ?></span></span>
										<div class="select-cover">
											<?php
											echo wp_dropdown_pages(
												array(
													'name' => 'blog_page_display',
													'echo' => 0,
													'depth' => -1,
													'show_option_none' => '-- ' . esc_html__( 'Select Page', 'blog-designer' ) . ' --',
													'option_none_value' => '0',
													'selected' => get_option( 'blog_page_display' ),
												)
											);
											?>
										</div>
									</div>
								</li>
								<li>
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Number of Posts to Display', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( ' Select number of posts to display on blog page', 'blog-designer' ); ?></span></span>
										<div class="quantity">
											<input name="posts_per_page" type="number" step="1" min="1" id="posts_per_page" value="<?php echo esc_attr( get_option( 'posts_per_page' ) ); ?>" class="small-text" onkeypress="return isNumberKey(event)" />
											<div class="quantity-nav">
												<div class="quantity-button quantity-up">+</div>
												<div class="quantity-button quantity-down">-</div>
											</div>
										</div>
									</div>
								</li>
								<li>
								<div class="bd-left"><span class="bd-key-title"><?php esc_html_e( 'Blog Order by', 'blog-designer' ); ?></span></div>
								<div class="bd-right">
									<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Select order for blog', 'blog-designer' ); ?></span></span>
									<?php
									$orderby = 'date';
									if ( isset( $settings['bdp_blog_order_by'] ) ) {
										$orderby = $settings['bdp_blog_order_by'];
									}
									?>
									<div class="select-cover">
										<select id="bdp_blog_order_by" name="bdp_blog_order_by">
											<option value="rand" <?php echo selected( 'rand', $orderby ); ?>><?php esc_html_e( 'Random', 'blog-designer-pro' ); ?></option>
											<option value="ID" <?php echo selected( 'ID', $orderby ); ?>><?php esc_html_e( 'Post ID', 'blog-designer-pro' ); ?></option>
											<option value="author" <?php echo selected( 'author', $orderby ); ?>><?php esc_html_e( 'Author', 'blog-designer-pro' ); ?></option>
											<option value="title" <?php echo selected( 'title', $orderby ); ?>><?php esc_html_e( 'Post Title', 'blog-designer-pro' ); ?></option>
											<option value="name" <?php echo selected( 'name', $orderby ); ?>><?php esc_html_e( 'Post Slug', 'blog-designer-pro' ); ?></option>
											<option value="date" <?php echo selected( 'date', $orderby ); ?>><?php esc_html_e( 'Publish Date', 'blog-designer-pro' ); ?></option>
											<option value="modified" <?php echo selected( 'modified', $orderby ); ?>><?php esc_html_e( 'Modified Date', 'blog-designer-pro' ); ?></option>
											<option value="meta_value_num" <?php echo selected( 'meta_value_num', $orderby ); ?>><?php esc_html_e( 'Post Likes', 'blog-designer-pro' ); ?></option>
										</select>
									</div>
									<div class="blg_order">
										<?php
										$order = 'DESC';
										if ( isset( $settings['bdp_blog_order'] ) ) {
											$order = $settings['bdp_blog_order'];
										}
										?>
										<fieldset class="buttonset green" data-hide='1'>
											<input id="bdp_blog_order_asc" name="bdp_blog_order" type="radio" value="ASC" <?php checked( 'ASC', $order ); ?> />
											<label id="bdp-options-button" for="bdp_blog_order_asc"><?php esc_html_e( 'Ascending', 'blog-designer-pro' ); ?></label>
											<input id="bdp_blog_order_desc" name="bdp_blog_order" type="radio" value="DESC" <?php checked( 'DESC', $order ); ?> />
											<label id="bdp-options-button" for="bdp_blog_order_desc"><?php esc_html_e( 'Descending', 'blog-designer-pro' ); ?></label>
										</fieldset>
									</div>
								</div>
							</li>
								<li>
									<div class="bd-left">
										<span class="bd-key-title">
										<?php esc_html_e( 'Select Post Categories', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( ' Select post categories to filter posts via categories', 'blog-designer' ); ?></span></span>
										<?php
										$categories = get_categories(
											array(
												'child_of' => '',
												'hide_empty' => 1,
											)
										);
										?>
										<select data-placeholder="<?php esc_attr_e( 'Choose Post Categories', 'blog-designer' ); ?>" class="chosen-select" multiple style="width:220px;" name="template_category[]" id="template_category">
											<?php foreach ( $categories as $category_obj ) : ?>
												<option value="<?php echo esc_html( $category_obj->term_id ); ?>" 
												<?php
												if ( isset( $settings['template_category'] ) && is_array( $settings['template_category'] ) && in_array( $category_obj->term_id, $settings['template_category'] ) ) {
													echo 'selected="selected"';
												}
												?>
														><?php echo esc_html( $category_obj->name ); ?>
												</option><?php endforeach; ?>
										</select>
									</div>
								</li>
								<li>
									<div class="bd-left">
										<span class="bd-key-title">
										<?php esc_html_e( 'Select Post Tags', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( ' Select post tag to filter posts via tags', 'blog-designer' ); ?></span></span>
											<?php
											$tags          = get_tags();
											$template_tags = isset( $settings['template_tags'] ) ? $settings['template_tags'] : array();
											?>
										<select data-placeholder="<?php esc_attr_e( 'Choose Post Tags', 'blog-designer' ); ?>" class="chosen-select" multiple style="width:220px;" name="template_tags[]" id="template_tags">
											<?php foreach ( $tags as $tag ) : ?>
												<option value="<?php echo esc_html( $tag->term_id ); ?>"
												<?php
												if ( isset( $template_tags ) && is_array( $template_tags ) && in_array( $tag->term_id, $template_tags ) ) {
													echo 'selected="selected"';
												}
												?>
												><?php echo esc_html( $tag->name ); ?></option>
											<?php endforeach; ?>
										</select>
									</div>
								</li>
								<li>
									<div class="bd-left">
										<span class="bd-key-title">
										<?php esc_html_e( 'Select Post Authors', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( ' Select post authors to filter posts via authors', 'blog-designer' ); ?></span></span>
											<?php
											$blogusers        = get_users( 'orderby=nicename&order=asc' );
											$template_authors = isset( $settings['template_authors'] ) ? $settings['template_authors'] : array();
											?>
										<select data-placeholder="<?php esc_attr_e( 'Choose Post Authors', 'blog-designer' ); ?>" class="chosen-select" multiple style="width:220px;" name="template_authors[]" id="template_authors">
											<?php foreach ( $blogusers as $user ) : ?>
												<option value="<?php echo esc_html( $user->ID ); ?>" 
												<?php
												if ( isset( $template_authors ) && is_array( $template_authors ) && in_array( $user->ID, $template_authors ) ) {
													echo 'selected="selected"';
												}
												?>
												><?php echo esc_html( $user->display_name ); ?></option>
												<?php endforeach; ?>
										</select>
									</div>
								</li>
								<li class="bd-display-settings">
									<h3 class="bd-table-title"><?php esc_html_e( 'Display Settings', 'blog-designer' ); ?></h3>
									<div class="bd-typography-wrapper bd-button-settings">
										<div class="bd-typography-cover">
											<div class="bdp-typography-label">
												<span class="bd-key-title">
													<?php esc_html_e( 'Post Category', 'blog-designer' ); ?>
												</span>
												<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Show post category on blog layout', 'blog-designer' ); ?></span></span>
											</div>
											<div class="bd-typography-content">
												<fieldset class="buttonset">
													<input id="display_category_0" name="display_category" type="radio" value="0" <?php echo checked( 0, get_option( 'display_category' ) ); ?>/>
													<label class="<?php echo esc_html( $uic_l ); ?>" for="display_category_0"><?php esc_html_e( 'Yes', 'blog-designer' ); ?></label>
													<input id="display_category_1" name="display_category" type="radio" value="1" <?php echo checked( 1, get_option( 'display_category' ) ); ?> />
													<label for="display_category_1" class="<?php echo esc_html( $uic_r ); ?>"><?php esc_html_e( 'No', 'blog-designer' ); ?></label>
												</fieldset>
											</div>
										</div>
										<div class="bd-typography-cover">
											<div class="bdp-typography-label">
												<span class="bd-key-title">
													<?php esc_html_e( 'Post Tag', 'blog-designer' ); ?>
												</span>
												<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Show post tag on blog layout', 'blog-designer' ); ?></span></span>
											</div>
											<div class="bd-typography-content">
												<fieldset class="buttonset">
													<input id="display_tag_0" name="display_tag" type="radio" value="0" <?php echo checked( 0, get_option( 'display_tag' ) ); ?>/>
													<label for="display_tag_0" class="<?php echo esc_html( $uic_l ); ?>"><?php esc_html_e( 'Yes', 'blog-designer' ); ?></label>
													<input id="display_tag_1" name="display_tag" type="radio" value="1" <?php echo checked( 1, get_option( 'display_tag' ) ); ?> />
													<label for="display_tag_1" class="<?php echo esc_html( $uic_r ); ?>"><?php esc_html_e( 'No', 'blog-designer' ); ?></label>
												</fieldset>
											</div>
										</div>

										<div class="bd-typography-cover">
											<div class="bdp-typography-label">
												<span class="bd-key-title">
													<?php esc_html_e( 'Post Author ', 'blog-designer' ); ?>
												</span>
												<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Show post author on blog layout', 'blog-designer' ); ?></span></span>
											</div>
											<div class="bd-typography-content">
												<fieldset class="buttonset">
													<input id="display_author_0" name="display_author" type="radio" value="0" <?php echo checked( 0, get_option( 'display_author' ) ); ?>/>
													<label for="display_author_0" class="<?php echo esc_html( $uic_l ); ?>"><?php esc_html_e( 'Yes', 'blog-designer' ); ?></label>
													<input id="display_author_1" name="display_author" type="radio" value="1" <?php echo checked( 1, get_option( 'display_author' ) ); ?> />
													<label for="display_author_1" class="<?php echo esc_html( $uic_r ); ?>"><?php esc_html_e( 'No', 'blog-designer' ); ?></label>
												</fieldset>
											</div>
										</div>
										<div class="bd-typography-cover">
											<div class="bdp-typography-label">
												<span class="bd-key-title">
													<?php esc_html_e( 'Post Published Date', 'blog-designer' ); ?>
												</span>
												<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Show post published date on blog layout', 'blog-designer' ); ?></span></span>
											</div>
											<div class="bd-typography-content">
												<fieldset class="buttonset">
													<input id="display_date_0" name="display_date" type="radio" value="0" <?php echo checked( 0, get_option( 'display_date' ) ); ?>/>
													<label for="display_date_0" class="<?php echo esc_html( $uic_l ); ?>"><?php esc_html_e( 'Yes', 'blog-designer' ); ?></label>
													<input id="display_date_1" name="display_date" type="radio" value="1" <?php echo checked( 1, get_option( 'display_date' ) ); ?> />
													<label for="display_date_1" class="<?php echo esc_html( $uic_r ); ?>"><?php esc_html_e( 'No', 'blog-designer' ); ?></label>
												</fieldset>
											</div>
										</div>
										<div class="bd-typography-cover">
											<div class="bdp-typography-label">
												<span class="bd-key-title">
													<?php esc_html_e( 'Comment Count', 'blog-designer' ); ?>
												</span>
												<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Show post comment count on blog layout', 'blog-designer' ); ?></span></span>
											</div>
											<div class="bd-typography-content">
												<fieldset class="buttonset">
													<input id="display_comment_count_0" name="display_comment_count" type="radio" value="0" <?php echo checked( 0, get_option( 'display_comment_count' ) ); ?>/>
													<label for="display_comment_count_0" class="<?php echo esc_html( $uic_l ); ?>"><?php esc_html_e( 'Yes', 'blog-designer' ); ?></label>
													<input id="display_comment_count_1" name="display_comment_count" type="radio" value="1" <?php echo checked( 1, get_option( 'display_comment_count' ) ); ?> />
													<label for="display_comment_count_1" class="<?php echo esc_html( $uic_r ); ?>"><?php esc_html_e( 'No', 'blog-designer' ); ?></label>
												</fieldset>
											</div>
										</div>
										<div class="bd-typography-cover pro-feature">
											<div class="bdp-typography-label">
												<span class="bd-key-title">
														<?php esc_html_e( 'Post Like', 'blog-designer' ); ?>
													<span class="bdp-pro-tag"><?php esc_html_e( 'PRO', 'blog-designer' ); ?></span>
												</span>
												<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Show post like on blog layout', 'blog-designer' ); ?></span></span>
											</div>
											<div class="bd-typography-content">
												<fieldset class="buttonset">
													<input id="display_postlike_0" name="display_postlike" type="radio" value="0" />
													<label for="display_postlike_0" class="<?php echo esc_html( $uic_l ); ?>"><?php esc_html_e( 'Yes', 'blog-designer' ); ?></label>
													<input id="display_postlike_1" name="display_postlike" type="radio" value="1" checked="checked"/>
													<label for="display_postlike_1" class="<?php echo esc_html( $uic_r ); ?>"><?php esc_html_e( 'No', 'blog-designer' ); ?></label>
												</fieldset>
											</div>
										</div>
										<div class="bd-typography-cover">
											<div class="bdp-typography-label">
												<span class="bd-key-title">
												<?php esc_html_e( 'Display Sticky Post First', 'blog-designer' ); ?>
												</span>
												<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Show Sticky Post first on blog layout', 'blog-designer' ); ?></span></span>
											</div>
											<div class="bd-typography-content">
												<?php
												$display_sticky = get_option( 'display_sticky' );
												?>
												<fieldset class="buttonset">
													<input id="display_sticky_0" name="display_sticky" type="radio" value="0" <?php echo checked( 0, $display_sticky ); ?>/>
													<label for="display_sticky_0" class="<?php echo esc_html( $uic_l ); ?>"><?php esc_html_e( 'Yes', 'blog-designer' ); ?></label>
													<input id="display_sticky_1" name="display_sticky" type="radio" value="1" <?php echo checked( 1, $display_sticky ); ?> />
													<label for="display_sticky_1" class="<?php echo esc_html( $uic_r ); ?>"><?php esc_html_e( 'No', 'blog-designer' ); ?></label>
												</fieldset>
											</div>
										</div>
									</div>
								</li>
								<li>
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Custom CSS', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon bd-tooltips-icon-textarea"><span class="bd-tooltips"><?php esc_html_e( 'Write a "Custom CSS" to add your additional design for blog page', 'blog-designer' ); ?></span></span>
										<textarea class="widefat textarea" name="custom_css" id="custom_css" placeholder=".class_name{ color:#ffffff }"><?php echo esc_textarea( wp_unslash( get_option( 'custom_css' ) ) ); ?></textarea>
										<div class="bd-setting-description bd-note">
											<b class=""><?php esc_html_e( 'Example', 'blog-designer' ); ?>:</b>
											<?php echo '.class_name{ color:#ffffff }'; ?>
										</div>
									</div>
								</li>                            
							</ul>
						</div>
						<div id="bdpstandard" class="postbox postbox-with-fw-options" style=<?php echo esc_attr( $bdpstandard_class_show ); ?>>
							<ul class="bd-settings">
								<li>
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Main Container Class Name', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Enter main container class name.', 'blog-designer' ); ?> <br/> <?php esc_html_e( 'Leave it blank if you do not want to use it', 'blog-designer' ); ?></span></span>
										<input type="text" name="main_container_class" id="main_container_class" placeholder="<?php esc_attr_e( 'main cover class name', 'blog-designer' ); ?>" value="<?php echo isset( $settings['main_container_class'] ) ? esc_attr( $settings['main_container_class'] ) : ''; ?>"/>
									</div>
								</li>

								<li class="blog-columns-tr">
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Blog Grid Columns', 'blog-designer' ); ?>
										<?php echo '<br />(<i>' . esc_html__( 'Desktop - Above', 'blog-designer' ) . ' 980px</i>)'; ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon bd-tooltips-icon-cosettingslor"><span class="bd-tooltips"><?php esc_html_e( 'Select column for post', 'blog-designer' ); ?></span></span>
										<?php $settings['template_columns'] = isset( $settings['template_columns'] ) ? $settings['template_columns'] : 2; ?>
										<select name="template_columns" id="template_columns" class="chosen-select">
											<option value="1" 
											<?php
											if ( '1' === $settings['template_columns'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '1 Column', 'blog-designer' ); ?>
											</option>
											<option value="2" 
											<?php
											if ( '2' === $settings['template_columns'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '2 Columns', 'blog-designer' ); ?>
											</option>
											<option value="3" 
											<?php
											if ( '3' === $settings['template_columns'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '3 Columns', 'blog-designer' ); ?>
											</option>
											<option value="4" 
											<?php
											if ( '4' === $settings['template_columns'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '4 Columns', 'blog-designer' ); ?>
											</option>
										</select>
									</div>
								</li>
								<li class="blog-columns-tr">
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Blog Grid Columns', 'blog-designer' ); ?>
										<?php echo '<br />(<i>' . esc_html__( 'iPad', 'blog-designer' ) . ' - 720px - 980px</i>)'; ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon bd-tooltips-icon-color"><span class="bd-tooltips"><?php esc_html_e( 'Select column for post', 'blog-designer' ); ?></span></span>
										<?php $settings['template_columns_ipad'] = isset( $settings['template_columns_ipad'] ) ? $settings['template_columns_ipad'] : 2; ?>
										<select name="template_columns_ipad" id="template_columns_ipad" class="chosen-select">
											<option value="1" 
											<?php
											if ( '1' === $settings['template_columns_ipad'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '1 Column', 'blog-designer' ); ?>
											</option>
											<option value="2" 
											<?php
											if ( '2' === $settings['template_columns_ipad'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '2 Columns', 'blog-designer' ); ?>
											</option>
											<option value="3" 
											<?php
											if ( '3' === $settings['template_columns_ipad'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '3 Columns', 'blog-designer' ); ?>
											</option>
											<option value="4" 
											<?php
											if ( '4' === $settings['template_columns_ipad'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '4 Columns', 'blog-designer' ); ?>
											</option>
										</select>
									</div>
								</li>
								<li class="blog-columns-tr">
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Blog Grid Columns', 'blog-designer' ); ?>
										<?php echo '<br />(<i>' . esc_html__( 'Tablet', 'blog-designer' ) . ' - 480px - 720px</i>)'; ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon bd-tooltips-icon-color"><span class="bd-tooltips"><?php esc_html_e( 'Select column for post', 'blog-designer' ); ?></span></span>
											<?php $settings['template_columns_tablet'] = isset( $settings['template_columns_tablet'] ) ? $settings['template_columns_tablet'] : 2; ?>
										<select name="template_columns_tablet" id="template_columns_tablet" class="chosen-select">
											<option value="1" 
											<?php
											if ( '1' === $settings['template_columns_tablet'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '1 Column', 'blog-designer' ); ?>
											</option>
											<option value="2" 
											<?php
											if ( '2' === $settings['template_columns_tablet'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '2 Columns', 'blog-designer' ); ?>
											</option>
											<option value="3" 
											<?php
											if ( '3' === $settings['template_columns_tablet'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '3 Columns', 'blog-designer' ); ?>
											</option>
											<option value="4" 
											<?php
											if ( '4' === $settings['template_columns_tablet'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '4 Columns', 'blog-designer' ); ?>
											</option>
										</select>
									</div>
								</li>
								<li class="blog-columns-tr">
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Blog Grid Columns', 'blog-designer' ); ?>
										<?php echo '<br />(<i>' . esc_html__( 'Mobile - Smaller Than', 'blog-designer' ) . ' 480px </i>)'; ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon bd-tooltips-icon-color"><span class="bd-tooltips"><?php esc_html_e( 'Select column for post', 'blog-designer' ); ?></span></span>
											<?php $settings['template_columns_mobile'] = isset( $settings['template_columns_mobile'] ) ? $settings['template_columns_mobile'] : 2; ?>
										<select name="template_columns_mobile" id="template_columns_mobile" class="chosen-select">
											<option value="1" 
											<?php
											if ( '1' === $settings['template_columns_mobile'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '1 Column', 'blog-designer' ); ?>
											</option>
											<option value="2" 
											<?php
											if ( '2' === $settings['template_columns_mobile'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '2 Columns', 'blog-designer' ); ?>
											</option>
											<option value="3" 
											<?php
											if ( '3' === $settings['template_columns_mobile'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '3 Columns', 'blog-designer' ); ?>
											</option>
											<option value="4" 
											<?php
											if ( '4' === $settings['template_columns_mobile'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '4 Columns', 'blog-designer' ); ?>
											</option>
										</select>
									</div>
								</li>
								<li class="blog-sallet-slider-tr">
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Display Blog Content in ', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon bd-tooltips-icon-color"><span class="bd-tooltips"><?php esc_html_e( 'Select column for post', 'blog-designer' ); ?></span></span>
											<?php $settings['template_slider_content'] = isset( $settings['template_slider_content'] ) ? $settings['template_slider_content'] : 'center'; ?>
										<select name="template_slider_content" id="template_slider_content" class="chosen-select">
											<option value="center" 
											<?php
											if ( 'center' === $settings['template_slider_content'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( 'Center', 'blog-designer' ); ?>
											</option>
											<option value="right" 
											<?php
											if ( 'right' === $settings['template_slider_content'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( 'Right', 'blog-designer' ); ?>
											</option>
										</select>
									</div>
								</li>
								
								<li class="blog-templatecolor-tr">
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Blog Posts Template Color', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon bd-tooltips-icon-color"><span class="bd-tooltips"><?php esc_html_e( 'Select post template color', 'blog-designer' ); ?></span></span>
										<input type="text" name="template_color" id="template_color" value="<?php echo isset( $settings['template_color'] ) ? esc_attr( $settings['template_color'] ) : ''; ?>"/>
									</div>
								</li>

								<li class="hoverbackcolor-tr">
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Blog Posts Hover Background Color', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon bd-tooltips-icon-color"><span class="bd-tooltips"><?php esc_html_e( 'Select post background color', 'blog-designer' ); ?></span></span>
										<input type="text" name="grid_hoverback_color" id="grid_hoverback_color" value="<?php echo ( isset( $settings['grid_hoverback_color'] ) ) ? esc_attr( $settings['grid_hoverback_color'] ) : ''; ?>"/>
									</div>
								</li>
								<li class="blog-template-tr">
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Background Color for Blog Posts', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon bd-tooltips-icon-color"><span class="bd-tooltips"><?php esc_html_e( 'Select post background color', 'blog-designer' ); ?></span></span>
										<input type="text" name="template_bgcolor" id="template_bgcolor" value="<?php echo ( isset( $settings['template_bgcolor'] ) ) ? esc_attr( $settings['template_bgcolor'] ) : ''; ?>"/>
									</div>
								</li>
								<li class="blog-template-tr alternative-tr">
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Alternative Background Color', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon bd-tooltips-icon-color"><span class="bd-tooltips"><?php esc_html_e( 'Display alternative background color', 'blog-designer' ); ?></span></span>
										<?php $bd_alter = get_option( 'template_alternativebackground' ); ?>
										<fieldset class="buttonset">
											<input id="template_alternativebackground_0" name="template_alternativebackground" type="radio" value="0" <?php echo checked( 0, $bd_alter ); ?>/>
											<label for="template_alternativebackground_0" class="<?php echo esc_html( $uic_l ); ?>"><?php esc_html_e( 'Yes', 'blog-designer' ); ?></label>
											<input id="template_alternativebackground_1" name="template_alternativebackground" type="radio" value="1" <?php echo checked( 1, $bd_alter ); ?> />
											<label for="template_alternativebackground_1" class="<?php echo esc_html( $uic_r ); ?>"><?php esc_html_e( 'No', 'blog-designer' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li class="alternative-color-tr">
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Choose Alternative Background Color', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon bd-tooltips-icon-color"><span class="bd-tooltips"><?php esc_html_e( 'Select alternative background color', 'blog-designer' ); ?></span></span>
										<input type="text" name="template_alterbgcolor" id="template_alterbgcolor" value="<?php echo ( isset( $settings['template_alterbgcolor'] ) ) ? esc_attr( $settings['template_alterbgcolor'] ) : ''; ?>"/>
									</div>
								</li>
								<li class="link-color-tr">
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Choose Link Color', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon bd-tooltips-icon-color"><span class="bd-tooltips"><?php esc_html_e( 'Select link color', 'blog-designer' ); ?></span></span>
										<input type="text" name="template_ftcolor" id="template_ftcolor" value="<?php echo ( isset( $settings['template_ftcolor'] ) ) ? esc_attr( $settings['template_ftcolor'] ) : ''; ?>"/>
									</div>
								</li>
								<li class="link-hovercolor-tr">
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Choose Link Hover Color', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon bd-tooltips-icon-color"><span class="bd-tooltips"><?php esc_html_e( 'Select link hover color', 'blog-designer' ); ?></span></span>
										<input type="text" name="template_fthovercolor" id="template_fthovercolor" value="<?php echo ( isset( $settings['template_fthovercolor'] ) ) ? esc_attr( $settings['template_fthovercolor'] ) : ''; ?>" data-default-color="<?php echo ( isset( $settings['template_fthovercolor'] ) ) ? esc_attr( $settings['template_fthovercolor'] ) : ''; ?>"/>
									</div>
								</li>
								<li class="design-type-tr">
									<div class="bd-left">
										<span class="bd-key-title">
										<?php esc_html_e( 'Choose Design', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Select design for layout', 'blog-designer' ); ?></span></span>
											<?php $settings['slider_design_type'] = ( isset( $settings['slider_design_type'] ) ) ? esc_attr( $settings['slider_design_type'] ) : ''; ?>
										<select name="slider_design_type" id="slider_design_type" class="chosen-select">
											<option value="design1" 
											<?php
											if ( 'design1' === $settings['slider_design_type'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( 'Design 1', 'blog-designer' ); ?>
											</option>
											<option value="design2" 
											<?php
											if ( 'design2' === $settings['slider_design_type'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( 'Design 2', 'blog-designer' ); ?>
											</option>
										</select>
									</div>
								</li>
								<li class="label_text_color_tr">
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Label Text Color', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon bd-tooltips-icon-color"><span class="bd-tooltips"><?php esc_html_e( 'Select label text color', 'blog-designer' ); ?></span></span>
										<input type="text" name="template_labeltextcolor" id="template_labeltextcolor" value="<?php echo isset( $settings['template_labeltextcolor'] ) ? esc_attr( $settings['template_labeltextcolor'] ) : ''; ?>"/>
									</div>
								</li>
								<li class="ticker_label_tr">
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Ticker Label', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Enter Ticker Label', 'blog-designer' ); ?></span></span>
										<?php

										$ticker_label = esc_html__( 'Latest Blog', 'blog-designer' );
										if ( isset( $settings['ticker_label'] ) ) {
											$ticker_label = $settings['ticker_label'];
										}
										?>
										<input name="ticker_label" type="text" id="ticker_label" value="<?php echo esc_attr( $ticker_label ); ?>"  />
									</div>
								</li>
							</ul>
						</div>
						<div id="bdptitle" class="postbox postbox-with-fw-options" style=<?php echo esc_attr( $bdptitle_class_show ); ?>>
							<ul class="bd-settings">
								<li class="pro-feature">
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Post Title Link', 'blog-designer' ); ?>
										</span>
										<span class="bdp-pro-tag"><?php esc_html_e( 'PRO', 'blog-designer' ); ?></span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Select post title link', 'blog-designer' ); ?></span></span>
										<fieldset class="buttonset">
											<input id="bdp_post_title_link_1" name="bdp_post_title_link" type="radio" value="1" checked="checked"/>
											<label for="bdp_post_title_link_1" class="<?php echo esc_html( $uic_l ); ?>"><?php esc_html_e( 'Yes', 'blog-designer' ); ?></label>
											<input id="bdp_post_title_link_0" name="bdp_post_title_link" type="radio" value="0"/>
											<label for="bdp_post_title_link_0" class="<?php echo esc_html( $uic_r ); ?>"><?php esc_html_e( 'No', 'blog-designer' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li>
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Post Title Color', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon bd-tooltips-icon-color"><span class="bd-tooltips"><?php esc_html_e( 'Select post title color', 'blog-designer' ); ?></span></span>
										<input type="text" name="template_titlecolor" id="template_titlecolor" value="<?php echo ( isset( $settings['template_titlecolor'] ) ) ? esc_attr( $settings['template_titlecolor'] ) : ''; ?>"/>
									</div>
								</li>
								<li class="pro-feature">
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Post Title Link Hover Color', 'blog-designer' ); ?>
										</span>
										<span class="bdp-pro-tag"><?php esc_html_e( 'PRO', 'blog-designer' ); ?></span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon bd-tooltips-icon-color"><span class="bd-tooltips"><?php esc_html_e( 'Select post title link hover color', 'blog-designer' ); ?></span></span>
										<input type="text" name="template_titlehovercolor" id="template_titlehovercolor" value=""/>
									</div>
								</li>
								<li>
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Post Title Background Color', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon bd-tooltips-icon-color"><span class="bd-tooltips"><?php esc_html_e( 'Select post title background color', 'blog-designer' ); ?></span></span>
										<input type="text" name="template_titlebackcolor" id="template_titlebackcolor" value="<?php echo ( isset( $settings['template_titlebackcolor'] ) ) ? esc_attr( $settings['template_titlebackcolor'] ) : ''; ?>"/>
									</div>
								</li>
								<li>
									<h3 class="bd-table-title"><?php esc_html_e( 'Typography Settings', 'blog-designer' ); ?></h3>

									<div class="bd-typography-wrapper bd-typography-options">

										<div class="bd-typography-cover pro-feature">
											<div class="bdp-typography-label">
												<span class="bd-key-title">
													<?php esc_html_e( 'Font Family', 'blog-designer' ); ?>
												</span>
												<span class="bdp-pro-tag"><?php esc_html_e( 'PRO', 'blog-designer' ); ?></span>
												<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Select post title font family', 'blog-designer' ); ?></span></span>
											</div>
											<div class="bd-typography-content">
												<div class="select-cover">
													<select name="" id=""></select>
												</div>
											</div>
										</div>
										<div class="bd-typography-cover">
											<div class="bdp-typography-label">
												<span class="bd-key-title">
													<?php esc_html_e( 'Font Size (px)', 'blog-designer' ); ?>
												</span>
												<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Select post title font size', 'blog-designer' ); ?></span></span>
											</div>
											<div class="bd-typography-content">
												<div class="grid_col_space range_slider_fontsize" id="template_postTitlefontsizeInput" data-value="<?php echo esc_attr( get_option( 'template_titlefontsize' ) ); ?>"></div>
												<div class="slide_val">
													<span></span>
													<input class="grid_col_space_val range-slider__value" name="template_titlefontsize" id="template_titlefontsize" value="<?php echo esc_attr( get_option( 'template_titlefontsize' ) ); ?>" onkeypress="return isNumberKey(event)" />
												</div>
											</div>
										</div>
										<div class="bd-typography-cover pro-feature">
											<div class="bdp-typography-label">
												<span class="bd-key-title">
													<?php esc_html_e( 'Font Weight', 'blog-designer' ); ?>
												</span>
												<span class="bdp-pro-tag"><?php esc_html_e( 'PRO', 'blog-designer' ); ?></span>
												<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Select font weight', 'blog-designer' ); ?></span></span>
											</div>
											<div class="bd-typography-content">
												<div class="select-cover">
													<select name="" id="">
													</select>
												</div>
											</div>
										</div>
										<div class="bd-typography-cover pro-feature">
											<div class="bdp-typography-label">
												<span class="bd-key-title">
													<?php esc_html_e( 'Line Height (px)', 'blog-designer' ); ?>
												</span>
												<span class="bdp-pro-tag"><?php esc_html_e( 'PRO', 'blog-designer' ); ?></span>
												<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Enter line height', 'blog-designer' ); ?></span></span>
											</div>
											<div class="bd-typography-content">
												<div class="quantity">
													<input type="number" name="" id="" step="0.1" min="0" value="1.5" onkeypress="return isNumberKey(event)">
													<div class="quantity-nav">
														<div class="quantity-button quantity-up">+</div>
														<div class="quantity-button quantity-down">-</div>
													</div>
												</div>
											</div>
										</div>
										<div class="bd-typography-cover pro-feature">
											<div class="bdp-typography-label">
												<span class="bd-key-title">
													<?php esc_html_e( 'Italic Font Style', 'blog-designer' ); ?>
												</span>
												<span class="bdp-pro-tag"><?php esc_html_e( 'PRO', 'blog-designer' ); ?></span>
												<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Display italic font style', 'blog-designer' ); ?></span></span>
											</div>
											<div class="bd-typography-content ">
												<fieldset class="buttonset">
													<input id="italic_font_title_0" name="italic_font_title" type="radio" value="0" />
													<label for="italic_font_title_0" class="<?php echo esc_html( $uic_l ); ?>"><?php esc_html_e( 'Yes', 'blog-designer' ); ?></label>
													<input id="italic_font_title_1" name="italic_font_title" type="radio" value="1" checked="checked" />
													<label for="italic_font_title_1" class="<?php echo esc_html( $uic_r ); ?>"><?php esc_html_e( 'No', 'blog-designer' ); ?></label>
												</fieldset>
											</div>
										</div>
										<div class="bd-typography-cover pro-feature">
											<div class="bdp-typography-label">
												<span class="bd-key-title">
													<?php esc_html_e( 'Text Transform', 'blog-designer' ); ?>
												</span>
												<span class="bdp-pro-tag"><?php esc_html_e( 'PRO', 'blog-designer' ); ?></span>
												<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Select text transform style', 'blog-designer' ); ?></span></span>
											</div>
											<div class="bd-typography-content">
												<div class="select-cover">
													<select name="" id=""></select>
												</div>
											</div>
										</div>
										<div class="bd-typography-cover pro-feature">
											<div class="bdp-typography-label">
												<span class="bd-key-title">
													<?php esc_html_e( 'Text Decoration', 'blog-designer' ); ?>
												</span>
												<span class="bdp-pro-tag"><?php esc_html_e( 'PRO', 'blog-designer' ); ?></span>
												<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Select text decoration style', 'blog-designer' ); ?></span></span>
											</div>
											<div class="bd-typography-content">
												<div class="select-cover">
													<select name="" id=""></select>
												</div>
											</div>
										</div>
										<div class="bd-typography-cover pro-feature">
											<div class="bdp-typography-label">
												<span class="bd-key-title">
													<?php esc_html_e( 'Letter Spacing (px)', 'blog-designer' ); ?>
												</span>
												<span class="bdp-pro-tag"><?php esc_html_e( 'PRO', 'blog-designer' ); ?></span>
												<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Enter letter spacing', 'blog-designer' ); ?></span></span>
											</div>
											<div class="bd-typography-content">
												<div class="quantity">
													<input type="number" name="" id="" step="1" min="0" value="0" onkeypress="return isNumberKey(event)">
													<div class="quantity-nav">
														<div class="quantity-button quantity-up">+</div>
														<div class="quantity-button quantity-down">-</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</li>
							</ul>
						</div>
						<div id="bdpcontent" class="postbox postbox-with-fw-options" style=<?php echo esc_attr( $bdpcontent_class_show ); ?>>
							<ul class="bd-settings">
								<li>
									<div class="bd-left">
										<span class="bd-key-title">
										<?php esc_html_e( 'For each Article in a Feed, Show ', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'To display full text for each post, select full text option, otherwise select the summary option.', 'blog-designer' ); ?></span></span>
										<?php
										$rss_use_excerpt = get_option( 'rss_use_excerpt' );
										?>
										<fieldset class="buttonset green">
											<input id="rss_use_excerpt_0" name="rss_use_excerpt" type="radio" value="0" <?php echo checked( 0, $rss_use_excerpt ); ?> />
											<label for="rss_use_excerpt_0" class="<?php echo esc_html( $uic_l ); ?>"><?php esc_html_e( 'Full Text', 'blog-designer' ); ?></label>
											<input id="rss_use_excerpt_1" name="rss_use_excerpt" type="radio" value="1" <?php echo checked( 1, $rss_use_excerpt ); ?> />
											<label for="rss_use_excerpt_1" class="<?php echo esc_html( $uic_r ); ?>"><?php esc_html_e( 'Summary', 'blog-designer' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li class="excerpt_length">
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Post Content Length (words)', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Enter number of words for post content length', 'blog-designer' ); ?></span></span>
										<div class="quantity">
											<input type="number" id="txtExcerptlength" name="txtExcerptlength" value="<?php echo esc_attr( get_option( 'excerpt_length' ) ); ?>" min="0" step="1" class="small-text" onkeypress="return isNumberKey(event)">
											<div class="quantity-nav">
												<div class="quantity-button quantity-up">+</div>
												<div class="quantity-button quantity-down">-</div>
											</div>
										</div>
									</div>
								</li>
								<li class="excerpt_length pro-feature">
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Show Content From', 'blog-designer' ); ?>
										</span>
										<span class="bdp-pro-tag"><?php esc_html_e( 'PRO', 'blog-designer' ); ?></span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'To display text from post content or from post excerpt', 'blog-designer' ); ?></span></span>
										<div class="select-cover">
											<select name="" id=""></select>
										</div>
									</div>
								</li>
								<li class="excerpt_length">
									<?php $display_html_tags = get_option( 'display_html_tags', 0 ); ?>
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Display HTML tags with Summary', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Show HTML tags with summary', 'blog-designer' ); ?></span></span>
										<fieldset class="buttonset">
											<input id="display_html_tags_1" name="display_html_tags" type="radio" value="1" <?php echo checked( 1, $display_html_tags ); ?>/>
											<label for="display_html_tags_1" class="<?php echo esc_html( $uic_l ); ?>"><?php esc_html_e( 'Yes', 'blog-designer' ); ?></label>
											<input id="display_html_tags_0" name="display_html_tags" type="radio" value="0" <?php echo checked( 0, $display_html_tags ); ?> />
											<label for="display_html_tags_0" class="<?php echo esc_html( $uic_r ); ?>"><?php esc_html_e( 'No', 'blog-designer' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li class="pro-feature">
									<?php $firstletter_big = 0; ?>
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'First letter as Dropcap', 'blog-designer' ); ?>
										</span>
										<span class="bdp-pro-tag"><?php esc_html_e( 'PRO', 'blog-designer' ); ?></span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Enable first letter as Dropcap', 'blog-designer' ); ?></span></span>
										<fieldset class="buttonset">
											<input id="firstletter_big_1" name="firstletter_big" type="radio" value="1" <?php echo checked( 1, $firstletter_big ); ?>/>
											<label for="firstletter_big_1" class="<?php echo esc_html( $uic_l ); ?>"><?php esc_html_e( 'Yes', 'blog-designer' ); ?></label>
											<input id="firstletter_big_0" name="firstletter_big" type="radio" value="0" <?php echo checked( 0, $firstletter_big ); ?> />
											<label for="firstletter_big_0" class="<?php echo esc_html( $uic_r ); ?>"><?php esc_html_e( 'No', 'blog-designer' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li>
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Post Content Color', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon bd-tooltips-icon-color"><span class="bd-tooltips"><?php esc_html_e( 'Select post content color', 'blog-designer' ); ?></span></span>
										<input type="text" name="template_contentcolor" id="template_contentcolor" value="<?php echo esc_attr( $settings['template_contentcolor'] ); ?>"/>
									</div>
								</li>
								<li class="read_more_on">
									<h3 class="bd-table-title"><?php esc_html_e( 'Read More Settings', 'blog-designer' ); ?></h3>
									<div style="margin-bottom: 15px;">
										<div class="bd-left">
											<span class="bd-key-title">
											<?php esc_html_e( 'Display Read More On', 'blog-designer' ); ?>
											</span>
										</div>
										<div class="bd-right">
											<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Select option for display read more button where to display', 'blog-designer' ); ?></span></span>
											<?php
											$read_more_on = get_option( 'read_more_on' );
											$read_more_on = ( '' != $read_more_on ) ? $read_more_on : 2;
											?>
											<fieldset class="buttonset three-buttomset">
												<input id="readmore_on_1" name="readmore_on" type="radio" value="1" <?php checked( 1, $read_more_on ); ?> />
												<label id="bdp-options-button" for="readmore_on_1" <?php checked( 1, $read_more_on ); ?>><?php esc_html_e( 'Same Line', 'blog-designer' ); ?></label>
												<input id="readmore_on_2" name="readmore_on" type="radio" value="2" <?php checked( 2, $read_more_on ); ?> />
												<label id="bdp-options-button" for="readmore_on_2" <?php checked( 2, $read_more_on ); ?>><?php esc_html_e( 'Next Line', 'blog-designer' ); ?></label>
												<input id="readmore_on_0" name="readmore_on" type="radio" value="0" <?php checked( 0, $read_more_on ); ?>/>
												<label id="bdp-options-button" for="readmore_on_0" <?php checked( 0, $read_more_on ); ?>><?php esc_html_e( 'Disable', 'blog-designer' ); ?></label>
											</fieldset>
										</div>
									</div>
									<div class="bd-typography-wrapper bd-typography-options bd-readmore-options">
										<div class="bd-typography-cover read_more_text">
											<div class="bdp-typography-label">
												<span class="bd-key-title">
													<?php esc_html_e( 'Read More Text', 'blog-designer' ); ?>
												</span>
												<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Enter text for read more button', 'blog-designer' ); ?></span></span>
											</div>
											<div class="bd-typography-content">
												<input type="text" name="txtReadmoretext" id="txtReadmoretext" value="<?php echo esc_attr( get_option( 'read_more_text' ) ); ?>" placeholder="Enter read more text">
											</div>
										</div>
										<div class="bd-typography-cover read_more_text_color">
											<div class="bdp-typography-label">
												<span class="bd-key-title">
													<?php esc_html_e( 'Text Color', 'blog-designer' ); ?>
												</span>
												<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Select read more text color', 'blog-designer' ); ?></span></span>
											</div>
											<div class="bd-typography-content">
												<input type="text" name="template_readmorecolor" id="template_readmorecolor" value="<?php echo ( isset( $settings['template_readmorecolor'] ) ) ? esc_attr( $settings['template_readmorecolor'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="bd-typography-cover read_more_text_background">
											<div class="bdp-typography-label">
												<span class="bd-key-title">
													<?php esc_html_e( 'Background Color', 'blog-designer' ); ?>
												</span>
												<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Select read more text background color', 'blog-designer' ); ?></span></span>
											</div>
											<div class="bd-typography-content">
												<input type="text" name="template_readmorebackcolor" id="template_readmorebackcolor" value="<?php echo ( isset( $settings['template_readmorebackcolor'] ) ) ? esc_attr( $settings['template_readmorebackcolor'] ) : ''; ?>"/>
											</div>
										</div>
										<div class="bd-typography-cover read_more_text_background pro-feature">
											<div class="bdp-typography-label">
												<span class="bd-key-title">
													<?php esc_html_e( 'Hover Background Color', 'blog-designer' ); ?>
												</span>
												<span class="bdp-pro-tag"><?php esc_html_e( 'PRO', 'blog-designer' ); ?></span>
												<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Select Read more text hover background color', 'blog-designer' ); ?></span></span>
											</div>
											<div class="bd-typography-content">
												<input type="text" name="" id="template_readmorebackcolor" value=""/>
											</div>
										</div>
									</div>
								</li>
								<li>
									<h3 class="bd-table-title"><?php esc_html_e( 'Typography Settings', 'blog-designer' ); ?></h3>
									<div class="bd-typography-wrapper bd-typography-options">
										<div class="bd-typography-cover pro-feature">
											<div class="bdp-typography-label">
												<span class="bd-key-title">
													<?php esc_html_e( 'Font Family', 'blog-designer' ); ?>
												</span>
												<span class="bdp-pro-tag"><?php esc_html_e( 'PRO', 'blog-designer' ); ?></span>
												<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Select post content font family', 'blog-designer' ); ?></span></span>
											</div>
											<div class="bd-typography-content">
												<div class="select-cover">
													<select name="" id=""></select>
												</div>
											</div>
										</div>
										<div class="bd-typography-cover">
											<div class="bdp-typography-label">
												<span class="bd-key-title">
													<?php esc_html_e( 'Font Size (px)', 'blog-designer' ); ?>
												</span>
												<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Select font size for post content', 'blog-designer' ); ?></span></span>
											</div>
											<div class="bd-typography-content">
												<div class="grid_col_space range_slider_fontsize" id="template_postContentfontsizeInput" data-value="<?php echo esc_attr( get_option( 'content_fontsize' ) ); ?>"></div>
												<div class="slide_val">
													<span></span>
													<input class="grid_col_space_val range-slider__value" name="content_fontsize" id="content_fontsize" value="<?php echo esc_attr( get_option( 'content_fontsize' ) ); ?>" onkeypress="return isNumberKey(event)" />
												</div>
											</div>
										</div>
										<div class="bd-typography-cover pro-feature">
											<div class="bdp-typography-label">
												<span class="bd-key-title">
													<?php esc_html_e( 'Font Weight', 'blog-designer' ); ?>
												</span>
												<span class="bdp-pro-tag"><?php esc_html_e( 'PRO', 'blog-designer' ); ?></span>
												<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Select font weight', 'blog-designer' ); ?></span></span>
											</div>
											<div class="bd-typography-content">
												<div class="select-cover">
													<select name="" id="">
													</select>
												</div>
											</div>
										</div>
										<div class="bd-typography-cover pro-feature">
											<div class="bdp-typography-label">
												<span class="bd-key-title">
													<?php esc_html_e( 'Line Height (px)', 'blog-designer' ); ?>
												</span>
												<span class="bdp-pro-tag"><?php esc_html_e( 'PRO', 'blog-designer' ); ?></span>
												<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Enter line height', 'blog-designer' ); ?></span></span>
											</div>
											<div class="bd-typography-content">
												<div class="quantity">
													<input type="number" name="" id="" step="0.1" min="0" value="1.5" onkeypress="return isNumberKey(event)">
													<div class="quantity-nav">
														<div class="quantity-button quantity-up">+</div>
														<div class="quantity-button quantity-down">-</div>
													</div>
												</div>
											</div>
										</div>
										<div class="bd-typography-cover pro-feature">
											<div class="bdp-typography-label">
												<span class="bd-key-title">
													<?php esc_html_e( 'Italic Font Style', 'blog-designer' ); ?>
												</span>
												<span class="bdp-pro-tag"><?php esc_html_e( 'PRO', 'blog-designer' ); ?></span>
												<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Display italic font style', 'blog-designer' ); ?></span></span>
											</div>
											<div class="bd-typography-content">
												<fieldset class="buttonset">
													<input id="italic_font_content_0" name="italic_font_content" type="radio" value="0" />
													<label for="italic_font_content_0" class="<?php echo esc_html( $uic_l ); ?>"><?php esc_html_e( 'Yes', 'blog-designer' ); ?></label>
													<input id="italic_font_content_1" name="italic_font_content" type="radio" value="1" checked="checked" />
													<label for="italic_font_content_1" class="<?php echo esc_html( $uic_r ); ?>"><?php esc_html_e( 'No', 'blog-designer' ); ?></label>
												</fieldset>
											</div>
										</div>
										<div class="bd-typography-cover pro-feature">
											<div class="bdp-typography-label">
												<span class="bd-key-title">
													<?php esc_html_e( 'Text Transform', 'blog-designer' ); ?>
												</span>
												<span class="bdp-pro-tag"><?php esc_html_e( 'PRO', 'blog-designer' ); ?></span>
												<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Select text transform style', 'blog-designer' ); ?></span></span>
											</div>
											<div class="bd-typography-content">
												<div class="select-cover">
													<select name="" id=""></select>
												</div>
											</div>
										</div>
										<div class="bd-typography-cover pro-feature">
											<div class="bdp-typography-label">
												<span class="bd-key-title">
													<?php esc_html_e( 'Text Decoration', 'blog-designer' ); ?>
												</span>
												<span class="bdp-pro-tag"><?php esc_html_e( 'PRO', 'blog-designer' ); ?></span>
												<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Select text decoration style', 'blog-designer' ); ?></span></span>
											</div>
											<div class="bd-typography-content">
												<div class="select-cover">
													<select name="" id=""></select>
												</div>
											</div>
										</div>
										<div class="bd-typography-cover pro-feature">
											<div class="bdp-typography-label">
												<span class="bd-key-title">
													<?php esc_html_e( 'Letter Spacing (px)', 'blog-designer' ); ?>
												</span>
												<span class="bdp-pro-tag"><?php esc_html_e( 'PRO', 'blog-designer' ); ?></span>
												<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Enter letter spacing', 'blog-designer' ); ?></span></span>
											</div>
											<div class="bd-typography-content">
												<div class="quantity">
													<input type="number" name="" id="" step="1" min="0" value="0" onkeypress="return isNumberKey(event)">
													<div class="quantity-nav">
														<div class="quantity-button quantity-up">+</div>
														<div class="quantity-button quantity-down">-</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</li>
							</ul>
						</div>
						<div id="bdpslider" class="postbox postbox-with-fw-options" style=<?php echo esc_attr( $bdpslider_class_show ); ?>>
							<ul class="bd-settings">
								<li>
									<div class="bd-left">
										<span class="bdp-key-title">
										<?php esc_html_e( 'Slider Effect', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Select effect for slider layout', 'blog-designer' ); ?></span></span>
											<?php $settings['template_slider_effect'] = ( isset( $settings['template_slider_effect'] ) ) ? esc_attr( $settings['template_slider_effect'] ) : ''; ?>
										<select name="template_slider_effect" id="template_slider_effect" class="chosen-select">
											<option value="slide" 
											<?php
											if ( 'slide' === $settings['template_slider_effect'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( 'Slide', 'blog-designer' ); ?>
											</option>
											<option value="fade" 
											<?php
											if ( 'fade' === $settings['template_slider_effect'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( 'Fade', 'blog-designer' ); ?>
											</option>
										</select>
									</div>
								</li>
								<li class="slider_columns_tr">
									<div class="bd-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Slider Columns', 'blog-designer' ); ?>
										<?php echo '<br />(<i>' . esc_html__( 'Desktop - Above', 'blog-designer' ) . ' 980px</i>)'; ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Select column for slider', 'blog-designer' ); ?></span></span>
											<?php $settings['template_slider_columns'] = ( isset( $settings['template_slider_columns'] ) ) ? esc_attr( $settings['template_slider_columns'] ) : 2; ?>
										<select name="template_slider_columns" id="template_slider_columns" class="chosen-select">
											<option value="1" 
											<?php
											if ( '1' === $settings['template_slider_columns'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '1 Column', 'blog-designer' ); ?>
											</option>
											<option value="2" 
											<?php
											if ( '2' === $settings['template_slider_columns'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '2 Columns', 'blog-designer' ); ?>
											</option>
											<option value="3" 
											<?php
											if ( '3' === $settings['template_slider_columns'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '3 Columns', 'blog-designer' ); ?>
											</option>
											<option value="4" 
											<?php
											if ( '4' === $settings['template_slider_columns'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '4 Columns', 'blog-designer' ); ?>
											</option>
											<option value="5" 
											<?php
											if ( '5' === $settings['template_slider_columns'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '5 Columns', 'blog-designer' ); ?>
											</option>
											<option value="6" 
											<?php
											if ( '6' === $settings['template_slider_columns'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '6 Columns', 'blog-designer' ); ?>
											</option>
										</select>
									</div>
								</li>
								<li class="slider_columns_tr">
									<div class="bd-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Slider Columns', 'blog-designer' ); ?>
										<?php echo '<br />(<i>' . esc_html__( 'iPad', 'blog-designer' ) . ' - 720px - 980px</i>)'; ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Select column for slider', 'blog-designer' ); ?></span></span>
										<?php $settings['template_slider_columns_ipad'] = ( isset( $settings['template_slider_columns_ipad'] ) ) ? esc_attr( $settings['template_slider_columns_ipad'] ) : 2; ?>
										<select name="template_slider_columns_ipad" id="template_slider_columns_ipad" class="chosen-select">
											<option value="1" 
											<?php
											if ( '1' === $settings['template_slider_columns_ipad'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '1 Column', 'blog-designer' ); ?>
											</option>
											<option value="2" 
											<?php
											if ( '2' === $settings['template_slider_columns_ipad'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '2 Columns', 'blog-designer' ); ?>
											</option>
											<option value="3" 
											<?php
											if ( '3' === $settings['template_slider_columns_ipad'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '3 Columns', 'blog-designer' ); ?>
											</option>
											<option value="4" 
											<?php
											if ( '4' === $settings['template_slider_columns_ipad'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '4 Columns', 'blog-designer' ); ?>
											</option>
											<option value="5" 
											<?php
											if ( '5' === $settings['template_slider_columns_ipad'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '5 Columns', 'blog-designer' ); ?>
											</option>
											<option value="6" 
											<?php
											if ( '6' === $settings['template_slider_columns_ipad'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '6 Columns', 'blog-designer' ); ?>
											</option>
										</select>
									</div>
								</li>
								<li class="slider_columns_tr">
									<div class="bd-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Slider Columns', 'blog-designer' ); ?>
										<?php echo '<br />(<i>' . esc_html__( 'Tablet', 'blog-designer' ) . ' - 480px - 720px</i>)'; ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Select column for slider', 'blog-designer' ); ?></span></span>
										<?php $settings['template_slider_columns_tablet'] = ( isset( $settings['template_slider_columns_tablet'] ) ) ? esc_attr( $settings['template_slider_columns_tablet'] ) : 2; ?>
										<select name="template_slider_columns_tablet" id="template_slider_columns_tablet" class="chosen-select">
											<option value="1" 
											<?php
											if ( '1' === $settings['template_slider_columns_tablet'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '1 Column', 'blog-designer' ); ?>
											</option>
											<option value="2" 
											<?php
											if ( '2' === $settings['template_slider_columns_tablet'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '2 Columns', 'blog-designer' ); ?>
											</option>
											<option value="3" 
											<?php
											if ( '3' === $settings['template_slider_columns_tablet'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '3 Columns', 'blog-designer' ); ?>
											</option>
											<option value="4" 
											<?php
											if ( '4' === $settings['template_slider_columns_tablet'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '4 Columns', 'blog-designer' ); ?>
											</option>
											<option value="5" 
											<?php
											if ( '5' === $settings['template_slider_columns_tablet'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '5 Columns', 'blog-designer' ); ?>
											</option>
											<option value="6" 
											<?php
											if ( '6' === $settings['template_slider_columns_tablet'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '6 Columns', 'blog-designer' ); ?>
											</option>
										</select>
									</div>
								</li>
								<li class="slider_columns_tr">
									<div class="bd-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Slider Columns', 'blog-designer' ); ?>
											<?php echo '<br />(<i>' . esc_html__( 'Mobile - Smaller Than', 'blog-designer' ) . ' 480px </i>)'; ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Select column for slider', 'blog-designer' ); ?></span></span>
										<?php $settings['template_slider_columns_mobile'] = ( isset( $settings['template_slider_columns_mobile'] ) ) ? esc_attr( $settings['template_slider_columns_mobile'] ) : 1; ?>
										<select name="template_slider_columns_mobile" id="template_slider_columns_mobile" class="chosen-select">
											<option value="1" 
											<?php
											if ( '1' === $settings['template_slider_columns_mobile'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '1 Column', 'blog-designer' ); ?>
											</option>
											<option value="2" 
											<?php
											if ( '2' === $settings['template_slider_columns_mobile'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '2 Columns', 'blog-designer' ); ?>
											</option>
											<option value="3" 
											<?php
											if ( '3' === $settings['template_slider_columns_mobile'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '3 Columns', 'blog-designer' ); ?>
											</option>
											<option value="4" 
											<?php
											if ( '4' === $settings['template_slider_columns_mobile'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '4 Columns', 'blog-designer' ); ?>
											</option>
											<option value="5" 
											<?php
											if ( '5' === $settings['template_slider_columns_mobile'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '5 Columns', 'blog-designer' ); ?>
											</option>
											<option value="6" 
											<?php
											if ( '6' === $settings['template_slider_columns_mobile'] ) {
												?>
												selected="selected"<?php } ?>>
												<?php esc_html_e( '6 Columns', 'blog-designer' ); ?>
											</option>
										</select>
									</div>
								</li>
								<li class="slider_scroll_tr pro-feature">
									<div class="bd-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Slide to Scroll', 'blog-designer' ); ?>
										</span>
										<span class="bdp-pro-tag"><?php esc_html_e( 'PRO', 'blog-designer' ); ?></span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Select number of slide to scroll', 'blog-designer' ); ?></span></span>
										<?php $template_slider_scroll = isset( $settings['template_slider_scroll'] ) ? esc_attr( $settings['template_slider_scroll'] ) : '1'; ?>
										<select name="template_slider_scroll" id="template_slider_scroll" class="chosen-select">
											<option value="1" 
											<?php
											if ( '1' === $template_slider_scroll ) {
												?>
												selected="selected"<?php } ?>>1</option>
											<option value="2" 
											<?php
											if ( '2' === $template_slider_scroll ) {
												?>
												selected="selected"<?php } ?>>2</option>
											<option value="3" 
											<?php
											if ( '3' === $template_slider_scroll ) {
												?>
												selected="selected"<?php } ?>>3</option>
										</select>
									</div>
								</li>

								<li class="pro-feature">
									<div class="bd-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Display Slider Navigation', 'blog-designer' ); ?>
										</span>
										<span class="bdp-pro-tag"><?php esc_html_e( 'PRO', 'blog-designer' ); ?></span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Show slider navigation', 'blog-designer' ); ?></span></span>
										<?php $display_slider_navigation = isset( $settings['display_slider_navigation'] ) ? esc_attr( $settings['display_slider_navigation'] ) : '1'; ?>
										<fieldset class="bdp-social-options bdp-display_slider_navigation buttonset buttonset-hide ui-buttonset">
											<input id="display_slider_navigation_1" name="display_slider_navigation" type="radio" value="1" <?php checked( 1, $display_slider_navigation ); ?> />
											<label for="display_slider_navigation_1" class="<?php echo esc_html( $uic_l ); ?>" <?php checked( 1, $display_slider_navigation ); ?>><?php esc_html_e( 'Yes', 'blog-designer' ); ?></label>
											<input id="display_slider_navigation_0" name="display_slider_navigation" type="radio" value="0" <?php checked( 0, $display_slider_navigation ); ?> />
											<label for="display_slider_navigation_0" class="<?php echo esc_html( $uic_r ); ?>" <?php checked( 0, $display_slider_navigation ); ?>><?php esc_html_e( 'No', 'blog-designer' ); ?></label>
										</fieldset>
									</div>
								</li>

								<li class="pro-feature select_slider_navigation_tr">
									<div class="bd-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Slider Navigation Icon', 'blog-designer' ); ?>
										</span>
										<span class="bdp-pro-tag"><?php esc_html_e( 'PRO', 'blog-designer' ); ?></span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Select Slider navigation icon', 'blog-designer' ); ?></span></span>
										<?php $slider_navigation = isset( $settings['navigation_style_hidden'] ) ? esc_attr( $settings['navigation_style_hidden'] ) : 'navigation3'; ?>
										<div class="select_button_upper_div ">
											<div class="bdp_select_template_button_div">
												<input type="button" class="button bdp_select_navigation" value="<?php esc_attr_e( 'Select Navigation', 'blog-designer' ); ?>">
												<input style="visibility: hidden;" type="hidden" id="navigation_style_hidden" class="navigation_style_hidden" name="navigation_style_hidden" value="<?php echo esc_attr( $slider_navigation ); ?>" />
											</div>
											<div class="bdp_selected_navigation_image">
												<div class="bdp-dialog-navigation-style slider_controls" >
													<div class="bdp_navigation_image_holder navigation_hidden" >
														<img src="<?php echo esc_url( BLOGDESIGNER_URL ) . 'admin/images/navigation/' . esc_attr( $slider_navigation ) . '.png'; ?>">
													</div>
												</div>
											</div>
										</div>
									</div>
								</li>

								<li>
									<div class="bd-left">
										<span class="bdp-key-title">
										<?php esc_html_e( 'Display Slider Controls', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Show slider control', 'blog-designer' ); ?></span></span>
										<?php $display_slider_controls = isset( $settings['display_slider_controls'] ) ? esc_attr( $settings['display_slider_controls'] ) : '1'; ?>
										<fieldset class="bdp-social-options bdp-display_slider_controls buttonset buttonset-hide ui-buttonset">
											<input id="display_slider_controls_1" name="display_slider_controls" type="radio" value="1" <?php checked( 1, $display_slider_controls ); ?> />
											<label for="display_slider_controls_1" class="<?php echo esc_html( $uic_l ); ?>" <?php checked( 1, $display_slider_controls ); ?>><?php esc_html_e( 'Yes', 'blog-designer' ); ?></label>
											<input id="display_slider_controls_0" name="display_slider_controls" type="radio" value="0" <?php checked( 0, $display_slider_controls ); ?> />
											<label for="display_slider_controls_0" class="<?php echo esc_html( $uic_r ); ?>" <?php checked( 0, $display_slider_controls ); ?>><?php esc_html_e( 'No', 'blog-designer' ); ?></label>
										</fieldset>
									</div>
								</li>

								<li class="select_slider_controls_tr pro-feature">
									<div class="bd-left">
										<span class="bdp-key-title">
											<?php esc_html_e( 'Select Slider Arrow', 'blog-designer' ); ?>
										</span>
										<span class="bdp-pro-tag"><?php esc_html_e( 'PRO', 'blog-designer' ); ?></span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Select slider arrow icon', 'blog-designer' ); ?></span></span>
										<?php $slider_arrow = isset( $settings['arrow_style_hidden'] ) ? esc_attr( $settings['arrow_style_hidden'] ) : 'arrow1'; ?>
										<div class="select_button_upper_div ">
											<div class="bdp_select_template_button_div">
												<input type="button" class="button bdp_select_arrow" value="<?php esc_attr_e( 'Select Arrow', 'blog-designer' ); ?>">
												<input style="visibility: hidden;" type="hidden" id="arrow_style_hidden" class="arrow_style_hidden" name="arrow_style_hidden" value="<?php echo esc_attr( $slider_arrow ); ?>" />
											</div>
											<div class="bdp_selected_arrow_image">
												<div class="bdp-dialog-arrow-style slider_controls" >
													<div class="bdp_arrow_image_holder arrow_hidden" >
														<img src="<?php echo esc_url( BLOGDESIGNER_URL ) . 'admin/images/arrow/' . esc_attr( $slider_arrow ) . '.png'; ?>">
													</div>
												</div>
											</div>
										</div>
									</div>
								</li>

								<li>
									<div class="bd-left">
										<span class="bdp-key-title">
										<?php esc_html_e( 'Slider Autoplay', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Show slider autoplay', 'blog-designer' ); ?></span></span>
										<?php $slider_autoplay = isset( $settings['slider_autoplay'] ) ? esc_attr( $settings['slider_autoplay'] ) : '1'; ?>
										<fieldset class="bdp-social-options bdp-slider_autoplay buttonset buttonset-hide ui-buttonset">
											<input id="slider_autoplay_1" name="slider_autoplay" type="radio" value="1" <?php checked( 1, $slider_autoplay ); ?> />
											<label for="slider_autoplay_1" class="<?php echo esc_html( $uic_l ); ?>" <?php checked( 1, $slider_autoplay ); ?>><?php esc_html_e( 'Yes', 'blog-designer' ); ?></label>
											<input id="slider_autoplay_0" name="slider_autoplay" type="radio" value="0" <?php checked( 0, $slider_autoplay ); ?> />
											<label for="slider_autoplay_0" class="<?php echo esc_html( $uic_r ); ?>" <?php checked( 0, $slider_autoplay ); ?>><?php esc_html_e( 'No', 'blog-designer' ); ?></label>
										</fieldset>
									</div>
								</li>

								<li class="slider_autoplay_tr">
									<div class="bd-left">
										<span class="bdp-key-title">
										<?php esc_html_e( 'Enter slider autoplay intervals (ms)', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Enter slider autoplay intervals', 'blog-designer' ); ?></span></span>
										<?php $slider_autoplay_intervals = isset( $settings['slider_autoplay_intervals'] ) ? esc_attr( $settings['slider_autoplay_intervals'] ) : '1'; ?>
										<input type="number" id="slider_autoplay_intervals" name="slider_autoplay_intervals" step="1" min="0" value="<?php echo isset( $settings['slider_autoplay_intervals'] ) ? esc_attr( $settings['slider_autoplay_intervals'] ) : '3000'; ?>" placeholder="<?php esc_attr_e( 'Enter slider intervals', 'blog-designer' ); ?>" onkeypress="return isNumberKey(event)">
									</div>
								</li>

								<li class="slider_autoplay_tr">
									<div class="bd-left">
										<span class="bdp-key-title">
										<?php esc_html_e( 'Slider Speed (ms)', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Enter slider speed', 'blog-designer' ); ?></span></span>
										<?php $slider_speed = isset( $settings['slider_speed'] ) ? esc_attr( $settings['slider_speed'] ) : '300'; ?>
										<input type="number" id="slider_speed" name="slider_speed" step="1" min="0" value="<?php echo isset( $settings['slider_speed'] ) ? esc_attr( $settings['slider_speed'] ) : '300'; ?>" placeholder="<?php esc_attr_e( 'Enter slider intervals', 'blog-designer' ); ?>" onkeypress="return isNumberKey(event)">
									</div>
								</li>
							</ul>
						</div>
						<div id="bdpmedia" class="postbox postbox-with-fw-options" style=<?php echo esc_attr( $bdpmedia_class_show ); ?>>
							<ul class="bd-settings">
								<li class="pro-feature">
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Post Image Link', 'blog-designer' ); ?>
										</span>
										<span class="bdp-pro-tag"><?php esc_html_e( 'PRO', 'blog-designer' ); ?></span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Enable/Disable post image link', 'blog-designer' ); ?></span></span>
										<fieldset class="buttonset">
											<input id="bdp_post_image_link_1" name="bdp_post_image_link" type="radio" value="1" checked="checked"/>
											<label for="bdp_post_image_link_1" class="<?php echo esc_html( $uic_l ); ?>"><?php esc_html_e( 'Enable', 'blog-designer' ); ?></label>
											<input id="bdp_post_image_link_0" name="bdp_post_image_link" type="radio" value="0" />
											<label for="bdp_post_image_link_0" class="<?php echo esc_html( $uic_r ); ?>"><?php esc_html_e( 'Disable', 'blog-designer' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li class="pro-feature">
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Select Post Default Image', 'blog-designer' ); ?>
										</span>
										<span class="bdp-pro-tag"><?php esc_html_e( 'PRO', 'blog-designer' ); ?></span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Select post default image', 'blog-designer' ); ?></span></span>
										<input class="button bdp-upload_image_button" type="button" value="<?php esc_attr_e( 'Upload Image', 'blog-designer' ); ?>">
									</div>
								</li>
								<li class="pro-feature">
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Select Post Media Size', 'blog-designer' ); ?>
										</span>
										<span class="bdp-pro-tag"><?php esc_html_e( 'PRO', 'blog-designer' ); ?></span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Select size of post media', 'blog-designer' ); ?></span></span>
										<div class="select-cover">
											<select name="" id=""> </select>
										</div>
									</div>
								</li>
							</ul>
						</div>
						<div id="bdppagination" class="postbox postbox-with-fw-options" style=<?php echo esc_attr( $bdppagination_class_show ); ?>>
							<ul class="bd-settings">
								<li>
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Pagination Type', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Select pagination type', 'blog-designer' ); ?></span></span>
										<?php

										$pagination_type = 'paged';
										if ( isset( $settings['pagination_type'] ) && '' != $settings['pagination_type'] ) {
											$pagination_type = $settings['pagination_type']; }

										?>
										<div class="select-cover">
											<select name="pagination_type" id="pagination_type">
												<option value="no_pagination" <?php echo selected( 'no_pagination', $pagination_type ); ?>>
													<?php esc_html_e( 'No Pagination', 'blog-designer' ); ?>
												</option>
												<option value="paged" <?php echo selected( 'paged', $pagination_type ); ?>>
													<?php esc_html_e( 'Paged', 'blog-designer' ); ?>
												</option>
												<option value="load_more_btn" <?php echo selected( 'load_more_btn', $pagination_type ); ?>>
													<?php esc_html_e( 'Load More Button', 'blog-designer' ); ?>
												</option>
											</select>
										</div>
									</div>
								</li>
								<li class="loadmore_btn_option">
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Load More Button Text', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Enter load more button text', 'blog-designer' ); ?></span></span>
										<?php $loadmore_button_text = ( isset( $settings['loadmore_button_text'] ) && '' != $settings['loadmore_button_text'] ) ? $settings['loadmore_button_text'] : esc_html__( 'Load More', 'blog-designer' ); ?>
										<input type="text" name="loadmore_button_text" id="loadmore_button_text" value="<?php echo esc_attr( $loadmore_button_text ); ?>" placeholder="<?php esc_attr_e( 'Enter load more button text', 'blog-designer' ); ?>">
									</div>
								</li>
							</ul>
						</div>
						<div id="bdpsocial" class="postbox postbox-with-fw-options" style=<?php echo esc_attr( $bdpsocial_class_show ); ?>>
							<ul class="bd-settings">
								<li>
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Social Share', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Enable/Disable social share link', 'blog-designer' ); ?></span></span>
										<fieldset class="bdp-social-options buttonset buttonset-hide" data-hide='1'>
											<input id="social_share_1" name="social_share" type="radio" value="1" <?php echo checked( 1, get_option( 'social_share' ) ); ?>/>
											<label id="social_share_1" for="social_share_1" class="<?php echo esc_html( $uic_l ); ?>" <?php checked( 1, get_option( 'social_share' ) ); ?>><?php esc_html_e( 'Enable', 'blog-designer' ); ?></label>
											<input id="social_share_0" name="social_share" type="radio" value="0" <?php echo checked( 0, get_option( 'social_share' ) ); ?> />
											<label id="social_share_0" for="social_share_0" class="<?php echo esc_html( $uic_r ); ?>" <?php checked( 0, get_option( 'social_share' ) ); ?>><?php esc_html_e( 'Disable', 'blog-designer' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li class="pro-feature bd-social-share-options">
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Social Share Style', 'blog-designer' ); ?>
										</span>
										<span class="bdp-pro-tag"><?php esc_html_e( 'PRO', 'blog-designer' ); ?></span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Select social share style', 'blog-designer' ); ?></span></span>
										<fieldset class="buttonset green">
											<input id="social_style_0" name="social_style" type="radio" value="0" />
											<label for="social_style_0" class="<?php echo esc_html( $uic_l ); ?>"><?php esc_html_e( 'Custom', 'blog-designer' ); ?></label>
											<input id="social_style_1" name="social_style" type="radio" value="1" checked="checked" />
											<label for="social_style_1" class="<?php echo esc_html( $uic_r ); ?>"><?php esc_html_e( 'Default', 'blog-designer' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li class="pro-feature bd-social-share-options">
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Available Icon Themes', 'blog-designer' ); ?>
										</span>
										<span class="bdp-pro-tag"><?php esc_html_e( 'PRO', 'blog-designer' ); ?></span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon bd-tooltips-icon-social"><span class="bd-tooltips"><?php esc_html_e( 'Select icon theme from available icon theme', 'blog-designer' ); ?></span></span>
										<div class="social-share-theme social-share-td">
											<?php for ( $i = 1; $i <= 10; $i++ ) { ?>
												<div class="social-cover social_share_theme_<?php echo intval( $i ); ?>">
													<label>
														<input type="radio" id="default_icon_theme_<?php echo intval( $i ); ?>" value="" name="default_icon_theme" />
														<span class="bdp-social-icons facebook-icon bdp_theme_wrapper"></span>
														<span class="bdp-social-icons twitter-icon bdp_theme_wrapper"></span>
														<span class="bdp-social-icons linkdin-icon bdp_theme_wrapper"></span>
														<span class="bdp-social-icons pin-icon bdp_theme_wrapper"></span>
														<span class="bdp-social-icons whatsup-icon bdp_theme_wrapper"></span>
														<span class="bdp-social-icons telegram-icon bdp_theme_wrapper"></span>
														<span class="bdp-social-icons pocket-icon bdp_theme_wrapper"></span>
														<span class="bdp-social-icons mail-icon bdp_theme_wrapper"></span>
														<span class="bdp-social-icons reddit-icon bdp_theme_wrapper"></span>
														<span class="bdp-social-icons tumblr-icon bdp_theme_wrapper"></span>
														<span class="bdp-social-icons skype-icon bdp_theme_wrapper"></span>
														<span class="bdp-social-icons wordpress-icon bdp_theme_wrapper"></span>
													</label>
												</div>
											<?php } ?>
										</div>
									</div>
								</li>
								<li class="bd-social-share-options">
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Shape of Social Icon', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Select shape of social icon', 'blog-designer' ); ?></span></span>
										<fieldset class="buttonset green">
											<input id="social_icon_style_0" name="social_icon_style" type="radio" value="0" <?php echo checked( 0, get_option( 'social_icon_style' ) ); ?>/>
											<label for="social_icon_style_0" class="<?php echo esc_html( $uic_l ); ?>"><?php esc_html_e( 'Circle', 'blog-designer' ); ?></label>
											<input id="social_icon_style_1" name="social_icon_style" type="radio" value="1" <?php echo checked( 1, get_option( 'social_icon_style' ) ); ?> />
											<label for="social_icon_style_1" class="<?php echo esc_html( $uic_r ); ?>"><?php esc_html_e( 'Square', 'blog-designer' ); ?></label>
										</fieldset>
									</div>
								</li>
								<li class="bd-display-settings bd-social-share-options">
									<h3 class="bd-table-title"><?php esc_html_e( 'Social Share Links Settings', 'blog-designer' ); ?></h3>
									<div class="bd-typography-wrapper">
										<div class="bd-typography-cover">
											<div class="bdp-typography-label">
												<span class="bd-key-title">
													<?php esc_html_e( 'Facebook Share Link', 'blog-designer' ); ?>
												</span>
												<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Display facebook share link', 'blog-designer' ); ?></span></span>
											</div>
											<div class="bd-typography-content">
												<fieldset class="buttonset">
													<input id="facebook_link_0" name="facebook_link" type="radio" value="0" <?php echo checked( 0, get_option( 'facebook_link' ) ); ?>/>
													<label for="facebook_link_0" class="<?php echo esc_html( $uic_l ); ?>"><?php esc_html_e( 'Yes', 'blog-designer' ); ?></label>
													<input id="facebook_link_1" name="facebook_link" type="radio" value="1" <?php echo checked( 1, get_option( 'facebook_link' ) ); ?> />
													<label for="facebook_link_1" class="<?php echo esc_html( $uic_r ); ?>"><?php esc_html_e( 'No', 'blog-designer' ); ?></label>
												</fieldset>
											</div>
										</div>
										<div class="bd-typography-cover">
											<div class="bdp-typography-label">
												<span class="bd-key-title">
													<?php esc_html_e( 'Linkedin Share Link', 'blog-designer' ); ?>
												</span>
												<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Display linkedin share link', 'blog-designer' ); ?></span></span>
											</div>
											<div class="bd-typography-content">
												<fieldset class="buttonset">
													<input id="linkedin_link_0" name="linkedin_link" type="radio" value="0" <?php echo checked( 0, get_option( 'linkedin_link' ) ); ?>/>
													<label for="linkedin_link_0" class="<?php echo esc_html( $uic_l ); ?>"><?php esc_html_e( 'Yes', 'blog-designer' ); ?></label>
													<input id="linkedin_link_1" name="linkedin_link" type="radio" value="1" <?php echo checked( 1, get_option( 'linkedin_link' ) ); ?> />
													<label for="linkedin_link_1" class="<?php echo esc_html( $uic_r ); ?>"><?php esc_html_e( 'No', 'blog-designer' ); ?></label>
												</fieldset>
											</div>
										</div>
										<div class="bd-typography-cover">
											<div class="bdp-typography-label">
												<span class="bd-key-title">
													<?php esc_html_e( 'Pinterest Share link', 'blog-designer' ); ?>
												</span>
												<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Display Pinterest share link', 'blog-designer' ); ?></span></span>
											</div>
											<div class="bd-typography-content">
												<fieldset class="buttonset">
													<input id="pinterest_link_0" name="pinterest_link" type="radio" value="0" <?php echo checked( 0, get_option( 'pinterest_link' ) ); ?>/>
													<label for="pinterest_link_0" class="<?php echo esc_html( $uic_l ); ?>"><?php esc_html_e( 'Yes', 'blog-designer' ); ?></label>
													<input id="pinterest_link_1" name="pinterest_link" type="radio" value="1" <?php echo checked( 1, get_option( 'pinterest_link' ) ); ?> />
													<label for="pinterest_link_1" class="<?php echo esc_html( $uic_r ); ?>"><?php esc_html_e( 'No', 'blog-designer' ); ?></label>
												</fieldset>
											</div>
										</div>
										<div class="bd-typography-cover">
											<div class="bdp-typography-label">
												<span class="bd-key-title">
													<?php esc_html_e( 'Twitter Share Link', 'blog-designer' ); ?>
												</span>
												<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Display twitter share link', 'blog-designer' ); ?></span></span>
											</div>
											<div class="bd-typography-content">
												<fieldset class="buttonset">
													<input id="twitter_link_0" name="twitter_link" type="radio" value="0" <?php echo checked( 0, get_option( 'twitter_link' ) ); ?>/>
													<label for="twitter_link_0" class="<?php echo esc_html( $uic_l ); ?>"><?php esc_html_e( 'Yes', 'blog-designer' ); ?></label>
													<input id="twitter_link_1" name="twitter_link" type="radio" value="1" <?php echo checked( 1, get_option( 'twitter_link' ) ); ?> />
													<label for="twitter_link_1" class="<?php echo esc_html( $uic_r ); ?>"><?php esc_html_e( 'No', 'blog-designer' ); ?></label>
												</fieldset>
											</div>
										</div>
									</div>
								</li>
							</ul>
						</div>
						<?php
						if ( is_plugin_active( 'blog-designer-ads/blog-designer-ads.php' ) ) {
							do_action( 'bdads_do_blog_settings', 'tab_content' );
						} else {
							?>
							<div id="bdpads" class="postbox postbox-with-fw-options" style=<?php echo esc_attr( $bdads_class_show ); ?>>
								<div class="inside">
									<ul class="bdp-settings bdp-lineheight"> 
										<li>
											<div class="ads-pro-feature">
												<div class="bdp-left"><?php esc_html_e( 'Ads', 'blog-designer' ); ?></div>
												<div class="bdp-right">
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Enable/Disable repeated ads', 'blog-designer' ); ?></span></span>
													<?php
													$bdads_ads_set = isset( $settings['bdads_ads_set'] ) ? $settings['bdads_ads_set'] : '0';
													?>
													<fieldset class="buttonset green buttonset-hide" data-hide='1'>
														<input id="bdads_ads_set_1" name="bdads_ads_set" type="radio" value="1" <?php checked( 1, esc_html( $bdads_ads_set ) ); ?> /><label id="bdp-options-button" for="bdads_ads_set_1" <?php checked( 1, esc_html( $bdads_ads_set ) ); ?>><?php esc_html_e( 'Repeated', 'blog-designer' ); ?></label>
														<input id="bdads_ads_set_2" name="bdads_ads_set" type="radio" value="2" <?php checked( 2, esc_html( $bdads_ads_set ) ); ?> /><label id="bdp-options-button bdp-ads-set" for="bdads_ads_set_2" <?php checked( 2, esc_html( $bdads_ads_set ) ); ?>><?php esc_html_e( 'Normal', 'blog-designer' ); ?></label>
														<input id="bdads_ads_set_0" name="bdads_ads_set" type="radio" value="0" <?php checked( 0, esc_html( $bdads_ads_set ) ); ?> /><label id="bdp-options-button bdp-ads-set" for="bdads_ads_set_0" <?php checked( 0, esc_html( $bdads_ads_set ) ); ?>><?php esc_html_e( 'Disable', 'blog-designer' ); ?></label>
													</fieldset>
												</div>
											</div>
										</li>
										<li class="bdads_is_random_ads_col">
											<div class="ads-pro-feature">
												<div class="bdp-left"><?php esc_html_e( 'Random Ads', 'blog-designer' ); ?></div>
												<div class="bdp-right">
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Show random ads after x number of posts', 'blog-designer' ); ?></span></span>
													<?php $bdads_is_random = isset( $settings['bdads_is_random'] ) ? $settings['bdads_is_random'] : '0'; ?>
													<fieldset class="buttonset buttonset-hide" data-hide='1'>
														<input id="bdads_is_random_1" name="bdads_is_random" type="radio" value="1" <?php checked( 1, $bdads_is_random ); ?> /><label id="bdp-options-button" for="bdads_is_random_1" class="<?php echo esc_html( $uic_l ); ?>" <?php checked( 1, $bdads_is_random ); ?>><?php esc_html_e( 'Enable', 'blog-designer' ); ?></label>
														<input id="bdads_is_random_0" name="bdads_is_random" type="radio" value="0" <?php checked( 0, $bdads_is_random ); ?> /><label id="bdp-options-button" for="bdads_is_random_0" class="<?php echo esc_html( $uic_r ); ?>" <?php checked( 0, $bdads_is_random ); ?>><?php esc_html_e( 'Disable', 'blog-designer' ); ?></label>
													</fieldset>
												</div>
											</div>
										</li>
										<li class="bdads_repeated_ads_col">
											<?php $repeated_ad_post_number = isset( $settings['repeatedAdPostNumber'] ) ? $settings['repeatedAdPostNumber'] : '1'; ?>
											<div class="ads-pro-feature">
												<div class="bdp-left"><?php esc_html_e( 'Show Ad after', 'blog-designer' ); ?></div>
												<div class="bdp-right">
													<span class="fas fa-question-circle bdp-tooltips-icon"><span class="bdp-tooltips"><?php esc_html_e( 'Show Ad after number of post', 'blog-designer' ); ?></span></span>
													<input type="number" id="repeatedAdPostNumber" name="repeatedAdPostNumber" step="1" min="0" value="<?php echo esc_html( $repeated_ad_post_number ); ?>" onkeypress="return isNumberKey(event)">
												</div>
											</div>
										</li>
									</ul>
								</div>
							</div>
							<?php
						}
						?>
					</div>
				</div>
				<div class="inner">
					<?php wp_nonce_field( 'blog_nonce_ac', 'blog_nonce' ); ?>
					<input type="submit" style="display: none;" class="save_blogdesign" value="<?php esc_html_e( 'Save Changes', 'blog-designer' ); ?>" />
					<p class="wl-saving-warning"></p>
					<div class="clear"></div>
				</div>
			</form>
			<div class="bd-admin-sidebar hidden">
				<div class="bd-help">
					<h2><?php esc_html_e( 'Help to improve this plugin!', 'blog-designer' ); ?></h2>
					<div class="help-wrapper">
						<span><?php esc_html_e( 'Enjoyed this plugin?', 'blog-designer' ); ?>&nbsp;</span>
						<span><?php esc_html_e( 'You can help by', 'blog-designer' ); ?>
							<a href="https://wordpress.org/support/plugin/blog-designer/reviews?filter=5#new-post" target="_blank">&nbsp;
							<?php esc_html_e( 'rate this plugin 5 stars!', 'blog-designer' ); ?>
							</a>
						</span>
						<div class="bd-total-download">
							<?php esc_html_e( 'Downloads:', 'blog-designer' ); ?><?php self::bd_get_total_downloads(); ?>
							<?php
							if ( $wp_version > 3.8 ) {
								bd_custom_star_rating();
							}
							?>
						</div>
					</div>
				</div>
				<div class="useful_plugins">
					<h2>
						<?php esc_html_e( 'Blog Designer PRO', 'blog-designer' ); ?>
					</h2>
					<div class="help-wrapper">
						<div class="pro-content">
							<ul class="advertisementContent">
								<li><?php esc_html_e( '50 Beautiful Blog Templates', 'blog-designer' ); ?></li>
								<li><?php esc_html_e( '5+ Unique Timeline Templates', 'blog-designer' ); ?></li>
								<li><?php esc_html_e( '10 Unique Grid Templates', 'blog-designer' ); ?></li>
								<li><?php esc_html_e( '3 Unique Slider Templates', 'blog-designer' ); ?></li>
								<li><?php esc_html_e( '200+ Blog Layout Variations', 'blog-designer' ); ?></li>
								<li><?php esc_html_e( 'Multiple Single Post Layout options', 'blog-designer' ); ?></li>
								<li><?php esc_html_e( 'Category, Tag, Author & Date Layouts', 'blog-designer' ); ?></li>
								<li><?php esc_html_e( 'Post Type & Taxonomy Filter', 'blog-designer' ); ?></li>
								<li><?php esc_html_e( '800+ Google Font Support', 'blog-designer' ); ?></li>
								<li><?php esc_html_e( '600+ Font Awesome Icons Support', 'blog-designer' ); ?></li>
							</ul>
							<p class="pricing_change"><?php esc_html_e( 'Now only at', 'blog-designer' ); ?> <ins>39</ins></p>
						</div>
						<div class="pre-book-pro">
							<a href="<?php echo esc_url( 'https://codecanyon.net/item/blog-designer-pro-for-wordpress/17069678?ref=solwin' ); ?>" target="_blank">
								<?php esc_html_e( 'Buy Now on Codecanyon', 'blog-designer' ); ?>
							</a>
						</div>
					</div>
				</div>
				<div class="bd-support">
					<h3><?php esc_html_e( 'Need Support?', 'blog-designer' ); ?></h3>
					<div class="help-wrapper">
						<span><?php esc_html_e( 'Check out the', 'blog-designer' ); ?>
							<a href="<?php echo esc_url( 'https://wordpress.org/plugins/blog-designer/faq/' ); ?>" target="_blank"><?php esc_html_e( 'FAQs', 'blog-designer' ); ?></a>
							<?php esc_html_e( 'and', 'blog-designer' ); ?>
							<a href="<?php echo esc_url( 'https://wordpress.org/support/plugin/blog-designer' ); ?>" target="_blank"><?php esc_html_e( 'Support Forums', 'blog-designer' ); ?></a>
						</span>
					</div>
				</div>
				<div class="bd-support">
					<h3><?php esc_html_e( 'Share & Follow Us', 'blog-designer' ); ?></h3>
					<!-- Twitter -->
					<div class="help-wrapper">
						<div style='display:block;margin-bottom:8px;'>
							<a href="<?php echo esc_url( 'https://twitter.com/solwininfotech' ); ?>" class="twitter-follow-button" data-show-count="false" data-show-screen-name="true" data-dnt="true">Follow @solwininfotech</a>
							<script>!function (d, s, id) {
									var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
									if (!d.getElementById(id)) {
										js = d.createElement(s);
										js.id = id;
										js.src = p + '://platform.twitter.com/widgets.js';
										fjs.parentNode.insertBefore(js, fjs);
									}
								}(document, 'script', 'twitter-wjs');
							</script>
						</div>
						<!-- Facebook -->
						<div style='display:block;margin-bottom:10px;'>
							<div id="fb-root"></div>
							<script>(function (d, s, id) {
									var js, fjs = d.getElementsByTagName(s)[0];
									if (d.getElementById(id))
										return;
									js = d.createElement(s);
									js.id = id;
									js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.5";
									fjs.parentNode.insertBefore(js, fjs);
								}(document, 'script', 'facebook-jssdk'));</script>
							<div class="fb-share-button" data-href="https://wordpress.org/plugins/blog-designer/" data-layout="button"></div>
						</div>
						<div style='display:block;margin-bottom:8px;'>
							<script src="https://platform.linkedin.com/in.js" type="text/javascript"></script>							<script type="IN/Share" data-url="https://wordpress.org/plugins/blog-designer/" ></script>
						</div>
					</div>
				</div>
			</div>
			<div id="bd_popupdiv" class="bd-template-popupdiv" style="display: none;">
				<?php
				$tempate_list = Blog_Designer_Lite_Template::bd_template_list();
				foreach ( $tempate_list as $key => $value ) {
					$classes = explode( ' ', $value['class'] );
					foreach ( $classes as $class ) {
						$all_class[] = $class;
					}
				}
				$count = array_count_values( $all_class );
				?>
				<ul class="bd_template_tab">
					<li class="bd_current_tab">
						<a href="#all"><?php esc_html_e( 'All', 'blog-designer' ); ?></a>
					</li>
					<li>
						<a href="#free"><?php echo esc_html__( 'Free', 'blog-designer' ) . ' (' . esc_attr( $count['free'] ) . ')'; ?></a>
					</li>
					<li>
						<a href="#full-width"><?php echo esc_html__( 'Full Width', 'blog-designer' ) . ' (' . esc_attr( $count['full-width'] ) . ')'; ?></a>
					</li>
					<li>
						<a href="#grid"><?php echo esc_html__( 'Grid', 'blog-designer' ) . ' (' . esc_attr( $count['grid'] ) . ')'; ?></a>
					</li>
					<li>
						<a href="#masonry"><?php echo esc_html__( 'Masonry', 'blog-designer' ) . ' (' . esc_attr( $count['masonry'] ) . ')'; ?></a>
					</li>
					<li>
						<a href="#magazine"><?php echo esc_html__( 'Magazine', 'blog-designer' ) . ' (' . esc_attr( $count['magazine'] ) . ')'; ?></a>
					</li>
					<li>
						<a href="#timeline"><?php echo esc_html__( 'Timeline', 'blog-designer' ) . ' (' . esc_attr( $count['timeline'] ) . ')'; ?></a>
					</li>
					<li>
						<a href="#slider"><?php echo esc_html__( 'Slider', 'blog-designer' ) . ' (' . esc_attr( $count['slider'] ) . ')'; ?></a>
					</li>
					<div class="bd-template-search-cover">
						<input type="text" class="bd-template-search" id="bd-template-search" placeholder="<?php esc_html_e( 'Search Template', 'blog-designer' ); ?>" />
						<span class="bd-template-search-clear"></span>
					</div>
				</ul>
				<?php
				echo '<div class="bd-template-cover">';
				foreach ( $tempate_list as $key => $value ) {
					if ( 'boxy-clean' === $key || 'crayon_slider' === $key || 'classical' === $key || 'lightbreeze' === $key || 'spektrum' === $key || 'evolution' === $key || 'timeline' === $key || 'news' === $key || 'glossary' === $key || 'nicy' === $key || 'sallet_slider' === $key || 'media-grid' === $key || 'blog-carousel' === $key || 'blog-grid-box' === $key || 'ticker' === $key ) {
						$class = 'bd-lite';
					} else {
						$class = 'bp-pro';
					}
					?>
					<div class="bd-template-thumbnail <?php echo esc_attr( $value['class'] . ' ' . $class ); ?>">
						<div class="bd-template-thumbnail-inner">
							<img src="<?php echo esc_url( BLOGDESIGNER_URL ) . 'admin/images/layouts/' . esc_attr( $value['image_name'] ); ?>" data-value="<?php echo esc_attr( $key ); ?>" alt="<?php echo esc_attr( $value['template_name'] ); ?>" title="<?php echo esc_attr( $value['template_name'] ); ?>">
							<?php if ( 'bd-lite' === $class ) { ?>
								<div class="bd-hover_overlay">
									<div class="bd-popup-template-name">
										<div class="bd-popum-select"><a href="#"><?php esc_html_e( 'Select Template', 'blog-designer' ); ?></a></div>
										<div class="bd-popup-view"><a href="<?php echo esc_attr( $value['demo_link'] ); ?>" target="_blank"><?php esc_html_e( 'Live Demo', 'blog-designer' ); ?></a></div>
									</div>
								</div>
							<?php } else { ?>
								<div class="bd_overlay"></div>
								<div class="bd-img-hover_overlay">
									<img src="<?php echo esc_url( BLOGDESIGNER_URL ) . 'admin/images/pro-tag.png'; ?>" alt="Available in Pro" />
								</div>
								<div class="bd-hover_overlay">
									<div class="bd-popup-template-name">
										<div class="bd-popup-view"><a href="<?php echo esc_attr( $value['demo_link'] ); ?>" target="_blank"><?php esc_html_e( 'Live Demo', 'blog-designer' ); ?></a></div>
									</div>
								</div>
							<?php } ?>
						</div>
						<span class="bd-span-template-name"><?php echo esc_attr( $value['template_name'] ); ?></span>
					</div>
					<?php
				}
				echo '</div>';
				echo '<h3 class="no-template" style="display: none;">' . esc_html__( 'No template found. Please try again', 'blog-designer' ) . '</h3>';
				?>
			</div>
			<div id="bd-advertisement-popup">
				<div class="bd-advertisement-cover">
					<a class="bd-advertisement-link" target="_blank" href="<?php echo esc_url( 'https://codecanyon.net/item/blog-designer-pro-for-wordpress/17069678?ref=solwin' ); ?>">
						<img src="<?php echo esc_url( BLOGDESIGNER_URL ) . 'admin/images/bd_advertisement_popup.png'; ?>" />
					</a>
				</div>
			</div>
			<div id="bd-ads-advertisement-popup">
				<div class="bd-ads-advertisement-cover">
					<a class="bd-ads-advertisement-link" target="_blank" href="<?php echo esc_url( 'https://codecanyon.net/item/blog-designer-ads/26605381?ref=solwin' ); ?>">
						<img src="<?php echo esc_url( BLOGDESIGNER_URL ) . 'admin/images/ads-wordpress-plugin.jpg'; ?>" />
					</a>
				</div>
			</div>
		</div>
		<?php
	}
	/**
	 * Display Option form
	 */
	public static function bd_welcome_function() {
		global $wpdb;
		$bd_admin_email = get_option( 'admin_email' );
		?>
		<div class='bd_header_wizard'>
			<p><?php echo esc_html__( 'Hi there!', 'blog-designer' ); ?></p>
			<p><?php echo esc_html__( "Don't ever miss an opportunity to opt in for Email Notifications / Announcements about exciting New Features and Update Releases.", 'blog-designer' ); ?></p>
			<p><?php echo esc_html__( 'Contribute in helping us making our plugin compatible with most plugins and themes by allowing to share non-sensitive information about your website.', 'blog-designer' ); ?></p>
			<p><b><?php echo esc_html__( 'Email Address for Notifications', 'blog-designer' ); ?> :</b></p>
			<p><input type='email' value='<?php echo esc_attr( $bd_admin_email ); ?>' id='bd_admin_email' /></p>
			<p><?php echo esc_html__( "If you're not ready to Opt-In, that's ok too!", 'blog-designer' ); ?></p>
			<p><b><?php echo esc_html__( 'Blog Designer will still work fine.', 'blog-designer' ); ?> :</b></p>
			<p onclick="bd_show_hide_permission()" class='bd_permission'><b><?php echo esc_html__( 'What permissions are being granted?', 'blog-designer' ); ?></b></p>
			<div class='bd_permission_cover' style='display:none'>
				<div class='bd_permission_row'>
					<div class='bd_50'>
						<i class='dashicons dashicons-admin-users gb-dashicons-admin-users'></i>
						<div class='bd_50_inner'>
							<label><?php echo esc_html__( 'User Details', 'blog-designer' ); ?></label>
							<label><?php echo esc_html__( 'Name and Email Address', 'blog-designer' ); ?></label>
						</div>
					</div>
					<div class='bd_50'>
						<i class='dashicons dashicons-admin-plugins gb-dashicons-admin-plugins'></i>
						<div class='bd_50_inner'>
							<label><?php echo esc_html__( 'Current Plugin Status', 'blog-designer' ); ?></label>
							<label><?php echo esc_html__( 'Activation, Deactivation and Uninstall', 'blog-designer' ); ?></label>
						</div>
					</div>
				</div>
				<div class='bd_permission_row'>
					<div class='bd_50'>
						<i class='dashicons dashicons-testimonial gb-dashicons-testimonial'></i>
						<div class='bd_50_inner'>
							<label><?php echo esc_html__( 'Notifications', 'blog-designer' ); ?></label>
							<label><?php echo esc_html__( 'Updates & Announcements', 'blog-designer' ); ?></label>
						</div>
					</div>
					<div class='bd_50'>
						<i class='dashicons dashicons-welcome-view-site gb-dashicons-welcome-view-site'></i>
						<div class='bd_50_inner'>
							<label><?php echo esc_html__( 'Website Overview', 'blog-designer' ); ?></label>
							<label><?php echo esc_html__( 'Site URL, WP Version, PHP Info, Plugins & Themes Info', 'blog-designer' ); ?></label>
						</div>
					</div>
				</div>
			</div>
			<p>
				<input type='checkbox' class='bd_agree' id='bd_agree_gdpr' value='1' />
				<label for='bd_agree_gdpr' class='bd_agree_gdpr_lbl'><?php echo esc_html__( 'By clicking this button, you agree with the storage and handling of your data as mentioned above by this website. (GDPR Compliance)', 'blog-designer' ); ?></label>
			</p>
			<p class='bd_buttons'>
				<a href="javascript:void(0)" class='button button-secondary' onclick="bd_submit_optin('cancel')">
					<?php
					echo esc_html__( 'Skip', 'blog-designer' );
					echo ' &amp; ';
					echo esc_html__( 'Continue', 'blog-designer' );
					?>
				</a>
				<a href="javascript:void(0)" class='button button-primary' onclick="bd_submit_optin('submit')">
					<?php
					echo esc_html__( 'Opt-In', 'blog-designer' );
					echo ' &amp; ';
					echo esc_html__( 'Continue', 'blog-designer' );
					?>
				</a>
			</p>
		</div>
		<?php
	}
	/**
	 * Display total downloads of plugin
	 */
	public static function bd_get_total_downloads() {
		// Set the arguments. For brevity of code, I will set only a few fields.
		$plugins  = '';
		$response = '';
		$args     = array(
			'author' => 'solwininfotech',
			'fields' => array(
				'downloaded'   => true,
				'downloadlink' => true,
			),
		);
		// Make request and extract plug-in object. Action is query_plugins.
		$response = wp_remote_get(
			'http://api.wordpress.org/plugins/info/1.0/',
			array(
				'body' => array(
					'action'  => 'query_plugins',
					'request' => maybe_serialize( (object) $args ),
				),
			)
		);
		if ( ! is_wp_error( $response ) ) {
			$returned_object = maybe_unserialize( wp_remote_retrieve_body( $response ) );
			$plugins         = $returned_object->plugins;
		}
		$current_slug = 'blog-designer';
		if ( $plugins ) {
			foreach ( $plugins as $plugin ) {
				if ( $plugin->slug == $current_slug ) {
					if ( $plugin->downloaded ) {
						?>
						<span class="total-downloads">
							<span class="download-number"><?php echo esc_attr( $plugin->downloaded ); ?></span>
						</span>
						<?php
					}
				}
			}
		}
	}
}
new Blog_Designer_Lite_Settings();
