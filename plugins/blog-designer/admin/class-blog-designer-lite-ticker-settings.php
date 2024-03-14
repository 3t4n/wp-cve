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
class Blog_Designer_Lite_Ticker_Settings {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.8.2
	 */
	public function __construct() {

	}
	/**
	 * Html Display setting options
	 */
	public static function bd_main_ticker_function() {
		global $wp_version;
		$uic_l = 'ui-corner-left';
		$uic_r = 'ui-corner-right';
		?>
		<div class="wrap">
			<h2><?php esc_html_e( 'Ticker Settings', 'blog-designer' ); ?></h2>
			<?php
			if ( isset( $_REQUEST['bdRestoreDefault'] ) && isset( $_GET['updated'] ) && 'true' == sanitize_text_field( wp_unslash( $_GET['updated'] ) ) ) {
				echo '<div class="updated" ><p>' . esc_html__( 'Ticker setting restored successfully.', 'blog-designer' ) . '</p></div>';
			} elseif ( isset( $_GET['updated'] ) && 'true' == sanitize_text_field( wp_unslash( $_GET['updated'] ) ) ) {
				echo '<div class="updated" ><p>' . esc_html__( 'Ticker settings updated.', 'blog-designer' ) . '</p></div>';
			}
			$settings = get_option( 'wp_blog_news_ticker' );
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
			<form method="post" action="?page=bd_news_ticker&action=save&updated=true" class="bd-form-class bd-ticker-form-class">
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
								<h3><?php esc_html_e( 'Ticker settings', 'blog-designer' ); ?></h3>
							</div>
							<div class="pull-right">
								<input type="text" readonly="" onclick="this.select()" class="copy_shortcode" title="Copy Shortcode" value="[wp_blog_designer_ticker]">
								<a id="bd-submit-ticker-button" title="<?php esc_html_e( 'Save Changes', 'blog-designer' ); ?>" class="button">
									<span><i class="fas fa-check"></i>&nbsp;&nbsp;<?php esc_html_e( 'Save Changes', 'blog-designer' ); ?></span>
								</a>
							</div>
						</div>
						<div id="bdpgeneral" class="postbox postbox-with-fw-options" style="display: block;">
							<ul class="bd-settings">
								<li>
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'News Ticker Label', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( 'Enter News Ticker Label', 'blog-designer' ); ?></span></span>
											<?php

											$news_ticker_label = esc_html__( 'Latest Blog', 'blog-designer' );
											if ( isset( $settings['news_ticker_label'] ) ) {
												$orderby = $settings['news_ticker_label'];
											}
											?>
											<input name="news_ticker_label" type="text" id="news_ticker_label" value="<?php echo esc_attr( $news_ticker_label ); ?>"  />
									</div>
								</li>
								<li>
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Number of Posts to Display', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon"><span class="bd-tooltips"><?php esc_html_e( ' Select number of posts to display on ticker page', 'blog-designer' ); ?></span></span>
										<div class="quantity">
											<?php

											$posts_per_page = '5';
											if ( isset( $settings['posts_per_page'] ) ) {
												$orderby = $settings['posts_per_page'];
											}
											?>
											<input name="posts_per_page" type="number" step="1" min="1" id="posts_per_page" value="<?php echo esc_attr( $posts_per_page ); ?>" class="small-text" onkeypress="return isNumberKey(event)" />
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
								<li>
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Template Color', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon bd-tooltips-icon-color"><span class="bd-tooltips"><?php esc_html_e( 'Select post template color', 'blog-designer' ); ?></span></span>
										<input type="text" name="template_color" id="template_color" value="<?php echo isset( $settings['template_color'] ) ? esc_attr( $settings['template_color'] ) : '#2096cd'; ?>"/>
									</div>
								</li>
								<li>
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Label Text Color', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon bd-tooltips-icon-color"><span class="bd-tooltips"><?php esc_html_e( 'Select post template color', 'blog-designer' ); ?></span></span>
										<input type="text" name="template_text_color" id="template_text_color" value="<?php echo isset( $settings['template_text_color'] ) ? esc_attr( $settings['template_text_color'] ) : '#fff'; ?>"/>
									</div>
								</li>
								<li>
									<div class="bd-left">
										<span class="bd-key-title">
											<?php esc_html_e( 'Title Color', 'blog-designer' ); ?>
										</span>
									</div>
									<div class="bd-right">
										<span class="fas fa-question-circle bd-tooltips-icon bd-tooltips-icon-color"><span class="bd-tooltips"><?php esc_html_e( 'Select post template color', 'blog-designer' ); ?></span></span>
										<input type="text" name="template_titlecolor" id="template_titlecolor" value="<?php echo isset( $settings['template_titlecolor'] ) ? esc_attr( $settings['template_titlecolor'] ) : '#2096cd'; ?>"/>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<div class="inner">
					<?php wp_nonce_field( 'blog_ticker_nonce_ac', 'blog_ticker_nonce' ); ?>
					<input type="submit" style="display: none;" class="save_blogdesign_ticker" value="<?php esc_html_e( 'Save Changes', 'blog-designer' ); ?>" />
					<p class="wl-saving-warning"></p>
					<div class="clear"></div>
				</div>
			</form>
		</div>
		<?php
	}


}
new Blog_Designer_Lite_Ticker_Settings();
