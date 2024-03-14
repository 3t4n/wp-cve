<?php
/**
 * Plugin Name: PE Panels
 * Plugin URI:  https://pixelemu.com
 * Description: Simple Panels with blog posts and pages.
 * Version:     1.09
 * Author:      pixelemu.com
 * Author URI:  https://www.pixelemu.com
 * Text Domain: pe-panels
 * License:     GPLv2 or later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}

if( !class_exists('PE_Panels') ) {
	class PE_Panels extends WP_Widget {

		function __construct() {
			$widget_ops = array( 'classname' => 'pe_panels_widget', 'description' => __('Simple Panels (tabs or accordion) with posts and pages.','pe-panels') );
			parent::__construct( 'PE_Panels', __('PE Panels','pe-panels'), $widget_ops );
		}

		// ---------------------------------------------------------------
		// Widget
		// ---------------------------------------------------------------

		public function widget($args,  $setup) {
			extract($args);

			$title = apply_filters('widget_title', $setup['title']);
			if ( empty($title) ) $title = false;

			$widget_id = $this->number;
			$widget_name = $this->id;

			$view = ( !empty($setup['view']) ) ? intval($setup['view']) : 0;

			$orderArray = explode(',',$setup['order']);
			$orderCleanArray = array_map('intval', $orderArray);
			$orderString = join(',', $orderCleanArray);

			$order = ( !empty($orderString) ) ? $orderString : false;

			$content_type = ( !empty($setup['content_type']) ) ? intval($setup['content_type']) : 0;

			$excerpt = ( !empty($setup['excerpt']) ) ? intval($setup['excerpt']) : 0;
			$showreadmore = ( !empty($setup['showreadmore']) ) ? intval($setup['showreadmore']) : 0;
			$showthumb = ( !empty($setup['showthumb']) ) ? intval($setup['showthumb']) : 0;
			$imgalign = ( !empty($setup['imgalign']) ) ? esc_attr($setup['imgalign']) : 'left';
			$imgsize = ( !empty($setup['imgsize']) ) ? esc_attr($setup['imgsize']) : 'thumbnail';

			if( $showthumb == 0 ) {
				$imgwidth = 0;
				$imgheight = 0;
			} else {
				$imgwidth = ( !empty($setup['imgwidth']) ) ? intval($setup['imgwidth']) : get_option( 'medium_size_w' ) ;
				$imgheight = ( !empty($setup['imgheight']) ) ? intval($setup['imgheight']) : get_option( 'medium_size_h' ) ;
			}

			$imgcrop = false;

			if( !empty($setup['imgcrop']) ) {
				if($setup['imgcrop'] == 1) { $imgcrop = true; }
				elseif($setup['imgcrop'] == 2) { $imgcrop = array('center','top'); }
				elseif($setup['imgcrop'] == 3) { $imgcrop = array('center','bottom'); }
				elseif($setup['imgcrop'] == 4) { $imgcrop = array('left','top'); }
				elseif($setup['imgcrop'] == 5) { $imgcrop = array('left','center'); }
				elseif($setup['imgcrop'] == 6) { $imgcrop = array('left','bottom'); }
				elseif($setup['imgcrop'] == 7) { $imgcrop = array('right','top'); }
				elseif($setup['imgcrop'] == 8) { $imgcrop = array('right','center'); }
				elseif($setup['imgcrop'] == 9) { $imgcrop = array('right','bottom'); }
			}

			$showsubtitle = ( !empty($setup['showsubtitle']) ) ? intval($setup['showsubtitle']) : 0;

			$responsive = ( !empty($setup['responsive']) ) ? intval($setup['responsive']) : 0;

			//tabs
			$fullwidth = ( !empty($setup['fullwidth']) ) ? intval($setup['fullwidth']) : 0;
			$position = ( !empty($setup['position']) ) ? intval($setup['position']) : 0;

			//accordion
			$showdate = ( !empty($setup['showdate']) ) ? intval($setup['showdate']) : 0;
			$firstopen = ( !empty($setup['firstopen']) ) ? intval($setup['firstopen']) : 0;
			$multiselect = ( !empty($setup['multiselect']) ) ? intval($setup['multiselect']) : 0;

			// before widget
			echo $before_widget;

			// title
			if($title):
				echo $before_title;
				echo $title;
				echo $after_title;
			endif;

			// get posts and pages
			if( !empty($order) ) {

				$post_args = array(
					'posts_per_page' => -1,
					'orderby'        => 'post__in',
					'include'        => $order,
					'post_type'      => array('post', 'page'),
					'post_status'    => 'publish',
				);

				$panels = get_posts($post_args);
				$count_panel = count($panels);

			}

			// if there are posts or pages
			if( !empty($panels) ) :

			// ----------------------------------------------------------
			// TABS LAYOUT
			// ----------------------------------------------------------

			if( $view == 0 ) {

			// full width class
			$fullWidthClass = ( $fullwidth == 1 ) ? ' full' : '';

			// tabs positon class
			if( $position == 1 ) {
				$position_class = ' left';
			} elseif( $position == 2 ) {
				$position_class = ' right';
			} else {
				$position_class = ' above';
			}

			?>

			<div class="pe-panels tabs default-theme desktop<?php echo $fullWidthClass . $position_class . ' items-' . $count_panel; ?>" data-responsive="<?php echo $responsive; ?>">
				<div class="pn-headings">
					<ul class="pn-list pn-clearfix" role="tablist" aria-label="<?php echo __('Indicators','pe-panels'); ?>">
						<?php
							$tcount = 0;
							foreach( $panels as $item ) {
							$tcount++;

							$itemID = $widget_id . '-post' . $item->ID;

						?>

						<li class="pn-item item<?php echo $tcount; ?> <?php if($tcount == 1) echo'active'; ?>" role="presentation" data-number="<?php echo $tcount; ?>">
							<a href="#<?php echo $itemID; ?>" role="tab" aria-selected="false" aria-controls="<?php echo $itemID; ?>" id="<?php echo $itemID; ?>-panel">
							<?php
								// show date
								if( $showdate == 1 ) {
									echo '<span class="pn-date">' . mysql2date(get_option('date_format'), $item->post_date) . '</span>';
								}

								echo $item->post_title;

								// show subtitle
								$subtitle = get_the_excerpt($item->ID);
								if( $showsubtitle == 1 && !empty($subtitle)  ) {
									echo '<span class="pn-subtitle">' . esc_attr( $subtitle ) . '</span>';
								}
							?>
							</a>
						</li>
						<?php } ?>
					</ul>
				</div>
				<div class="pn-contents" role="tabpanel">
					<?php
						$ccount = 0;
						foreach( $panels as $item ) {
							$ccount++;

							if( $content_type === 1 && !empty($item->post_excerpt) ) {
								$content = $item->post_excerpt;
							} else {
								$content = $item->post_content;
							}

							if ( ! $content ) $content = '<h2>' . __('No content !', 'pe-panels') . '</h2>';

							$itemID = $widget_id . '-post' . $item->ID;
					?>
					<div id="<?php echo $itemID; ?>" class="pn-content item<?php echo $ccount; ?> pn-clearfix <?php if($ccount == 1) echo'active'; ?>" tabindex="0" aria-labelledby="<?php echo $itemID; ?>-panel">
						<?php

							// show date
							if( $showdate == 2 ) {
								echo '<span class="pn-date above">' . mysql2date(get_option('date_format'), $item->post_date) . '</span>';
							}

							//show thumbnail
							if( $showthumb == 1 && has_post_thumbnail($item->ID) ) {
								echo '<div class="pn-thumbnail ' . $imgalign . '"><a href="' . get_permalink( $item->ID ) . '">' . pe_panels_thumbnail( $widget_id, $item->ID, $imgsize, $imgcrop ) . '</a></div>';
							}

							// show content
							pe_panels_excerpt($content, $excerpt);

							// show readmore
							if( $showreadmore == 1 ) {
								echo '<span class="pn-readmore"><a href="' . get_permalink( $item->ID ) . '" class="readmore">' . __('Readmore','pe-panels') . '</a></span>';
							}

							// show date
							if( $showdate == 3 ) {
								echo '<span class="pn-date below">' . mysql2date(get_option('date_format'), $item->post_date) . '</span>';
							}

						?>
					</div>
					<?php } ?>
				</div>
			</div>

			<?php

			// ----------------------------------------------------------
			// ACCORDION LAYOUT
			// ----------------------------------------------------------

			} else {

				$multiSelectAttr = ( $multiselect == 1 ) ? 'aria-multiselectable="true"' : '';
				$fistOpenClass = ( $firstopen == 1 ) ? 'first-open' : 'closed';

			?>

			<div class="pe-panels acco default-theme <?php echo 'items-' . $count_panel . ' ' . $fistOpenClass; ?>" role="tablist" <?php echo $multiSelectAttr; ?>  data-responsive="<?php echo $responsive; ?>">
				<?php
				$pcount = 0;
				foreach( $panels as $item ) {
					$pcount++;

					if( $content_type === 1 && !empty($item->post_excerpt) ) {
						$content = $item->post_excerpt;
					} else {
						$content = $item->post_content;
					}

					if ( ! $content ) $content = '<h2>' . __('No content !', 'pe-panels') . '</h2>';

					$headingId = $widget_id . '-post' . $item->ID . '-heading';
					$contentId = $widget_id . '-post' . $item->ID . '-content';

					if($firstopen == 1) {
						$active = ( $pcount == 1 ) ? 'active' : '';
						$expanded = ( $pcount == 1 ) ? 'true' : 'false';
					} else {
						$active = '';
						$expanded = 'false';
					}

					$panel_class = 'item' . $pcount;

				?>

				<div class="pn-panel <?php echo $panel_class . ' ' . $active; ?>">
					<div id="<?php echo $headingId; ?>" class="pn-heading">
						<a href="#<?php echo $contentId; ?>" class="pn-heading-button" role="button" aria-controls="<?php echo $contentId; ?>" id="<?php echo $headingId; ?>"  aria-expanded="<?php echo $expanded; ?>">
							<?php
							// show date
							if( $showdate == 1 ) {
								echo '<span class="pn-date">' . mysql2date(get_option('date_format'), $item->post_date) . '</span>';
							}

							echo $item->post_title;

							// show subtitle
							$subtitle = get_the_excerpt($item->ID);
							if( $showsubtitle == 1 && !empty($subtitle)  ) {
								echo '<span class="pn-subtitle">' . esc_attr( $subtitle ) . '</span>';
							}
							?>
						</a>
					</div>
					<div id="<?php echo $contentId; ?>" class="pn-content pn-clearfix" role="region" aria-labelledby="<?php echo $headingId; ?>">
						<?php
							//show thumbnail
							if( $showthumb == 1 && has_post_thumbnail($item->ID) ) {
								echo '<div class="pn-thumbnail ' . $imgalign . '"><a href="' . get_permalink( $item->ID ) . '">' . pe_panels_thumbnail( $widget_id, $item->ID, $imgsize, $imgcrop ) . '</a></div>';
							}

							// show date
							if( $showdate == 2 ) {
								echo '<span class="pn-date above">' . mysql2date(get_option('date_format'), $item->post_date) . '</span>';
							}

							// show content
							pe_panels_excerpt($content, $excerpt);

							// show readmore
							if( $showreadmore == 1 ) {
								echo '<span class="pn-readmore"><a href="' . get_permalink( $item->ID ) . '" class="readmore">' . __('Readmore','pe-panels') . '</a></span>';
							}

							// show date
							if( $showdate == 3 ) {
								echo '<span class="pn-date below">' . mysql2date(get_option('date_format'), $item->post_date) . '</span>';
							}
						?>

					</div>
				</div>
				<?php
				}
				?>

			</div>

			<?php } ?>

			<?php else: ?>
			<div class="pe-panels">
				<h2><?php _e('No panels !', 'pe-panels'); ?></h2>
			</div>
			<?php endif;

			// after widget
			echo $after_widget;

		}

		// ---------------------------------------------------------------
		// WIDGET FORM
		// ---------------------------------------------------------------

		public function form($setup) {
			$setup = wp_parse_args( (array) $setup, array(
				'title'           => '',
				'view'            => 0,
				'post_id'         => '',
				'page_id'         => '',
				'order'           => '',
				'content_type'    => 0,
				'fullwidth'       => 0,
				'position'        => 0,
				'showdate'        => 0,
				'showreadmore'    => 0,
				'firstopen'       => 1,
				'multiselect'     => 0,
				'excerpt'         => 0,
				'showthumb'       => 0,
				'imgalign'        => 'left',
				'imgsize'         => 'thumbnail',
				'imgwidth'        => '',
				'imgheight'       => '',
				'imgcrop'         => 0,
				'showsubtitle'    => 0,
				'imgregenerate'   => 0,
				'responsive'      => 767,
			) );

			$title = $setup['title'];

			$view = $setup['view'];

			$excerpt = $setup['excerpt'];
			$showreadmore = $setup['showreadmore'];
			$showthumb = $setup['showthumb'];
			$imgalign = $setup['imgalign'];
			$imgsize = $setup['imgsize'];
			$imgwidth = $setup['imgwidth'];
			$imgheight = $setup['imgheight'];
			$imgcrop = $setup['imgcrop'];
			$showsubtitle = $setup['showsubtitle'];

			$content_type = $setup['content_type'];

			//tabs
			$fullwidth = $setup['fullwidth'];
			$position = $setup['position'];

			//accordion
			$showdate = $setup['showdate'];
			$firstopen = $setup['firstopen'];
			$multiselect = $setup['multiselect'];

			$responsive = $setup['responsive'];

			//view class
			if( $view == 0 ) {
				$saved_layout = 'tabs-view';
			} else {
				$saved_layout = 'acco-view';
			}

			//get posts and check order
			$post_args = array(
				'posts_per_page' => -1,
				'orderby'        => 'post__in',
				'include'        => esc_attr($setup['order']),
				'post_type'      => array('post', 'page'),
				'post_status'    => 'publish',
			);

			$panels = get_posts($post_args);
			$panelArray = array();
			foreach ( $panels as $panel ) {
				$panelArray[] = $panel->ID;
			}

			//return only correct IDs

			//get order field
			$orderString = trim($setup['order']);
			$orderArray = explode(',', $orderString);

			//get post_id field, return only IDs which are in both arrays
			if( is_array($setup['post_id']) ) {
				$postsArray = array_intersect($orderArray, $setup['post_id']);
			} else {
				$postsArray = false;
			}

			//get page_id field, return only IDs which are in both arrays
			if( is_array($setup['page_id']) ) {
				$pagesArray = array_intersect($orderArray, $setup['page_id']);
			} else {
				$pagesArray = false;
			}

			//compare order field and post / pages ids
			if( is_array($orderArray) ) {
				$order = array_intersect($orderArray, $panelArray);
				$order = implode(', ', $order);
			}

			$selected = ( !empty($order) ) ? $order : '';

			?>
			<div class="pe-panels-widget-container <?php echo $saved_layout; ?>">
				<p class="pe-panels pe-panels-title">
					<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'pe-panels'); ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
				</p>

				<p class="pe-panels pe-panels-view">
					<label for="<?php echo $this->get_field_id('view'); ?>"><?php _e('View:', 'pe-panels'); ?></label>
					<span><input class="input-tabs" type="radio" name="<?php echo $this->get_field_name('view'); ?>" value="0" <?php checked( $setup['view'], '0' ); ?>><?php _e('Tabbed', 'pe-panels'); ?></span>
					<span><input class="input-acco" type="radio" name="<?php echo $this->get_field_name('view'); ?>" value="1" <?php checked( $setup['view'], '1' ); ?>><?php _e('Collapsed', 'pe-panels'); ?></span>
				</p>

				<p class="pe-panels pe-panels-source"><?php _e('Choose Panel source. You can select posts or / and pages. Panels ordering depends on chosen order.', 'pe-panels') ?></p>

				<?php
					$page_args = array(
						'posts_per_page' => -1,
						'orderby' => 'date',
						'post_type' => 'page',
						'post_status' => 'publish',
					);

					$pages = get_posts($page_args);

					if( !empty($pages) ) :
				?>
				<p class="pe-panels pe-panels-pages">
					<label for="<?php echo $this->get_field_id('page_id'); ?>"><?php _e('Pages:', 'pe-panels'); ?></label>
					<?php

						echo '<div style="max-height:150px; overflow:auto; border:1px solid #dfdfdf; padding:5px; margin-bottom:5px;">';
						echo '<ul class="pe-panels-id-list page-id-list">';
						foreach ( $pages as $page ) {
							if( $pagesArray ) {
								$checked = in_array($page->ID, $pagesArray) ? ' checked="checked"' : '';
							} else {
								$checked = '';
							}
							$option = '<li><input type="checkbox" name="' . $this->get_field_name('page_id') . '[]" id="page-' . $page->ID . '" value="' . $page->ID . '" ' . $checked . '>';
							$option .= '<span>(' . $page->ID . ') ' . $page->post_title . '</span></li>';
							echo $option;
						}
						echo '</ul>';
						echo '</div>';

					?>
				</p>
				<?php
					endif;

					$post_args = array(
						'posts_per_page' => -1,
						'orderby' => 'date',
						'post_type' => 'post',
						'post_status' => 'publish',
					);

					$posts = get_posts($post_args);

					if( !empty($posts) ) :
				?>
				<p class="pe-panels pe-panels-posts">
					<label for="<?php echo $this->get_field_id('post_id'); ?>"><?php _e('Posts:', 'pe-panels'); ?></label>
					<?php

						echo '<div style="max-height:150px; overflow:auto; border:1px solid #dfdfdf; padding:5px; margin-bottom:5px;">';
						echo '<ul class="pe-panels-id-list post-id-list">';
						foreach ( $posts as $post ) {
							if( $postsArray ) {
								$checked = in_array($post->ID, $postsArray) ? ' checked="checked"' : '';
							} else {
								$checked = '';
							}
							$option = '<li><input type="checkbox" name="' . $this->get_field_name('post_id') . '[]" id="post-' . $post->ID . '" value="' . $post->ID . '" ' . $checked . '>';
							$option .= '<span>(' . $post->ID . ') ' . $post->post_title . '</span></li>';
							echo $option;
						}
						echo '</ul>';
						echo '</div>';

					?>
				</p>
				<?php
					endif;
				?>

				<p class="pe-panels pe-panels-order-result">
					<label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Items order <small>(ID)</small>:', 'pe-panels'); ?></label>
					<input class="order widefat" id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>" type="text" value="<?php echo esc_attr($selected); ?>" readonly/>
				</p>

				<p class="pe-panels pe-panels-content-type">
					<label for="<?php echo $this->get_field_id('content_type'); ?>"><?php _e('Content source:', 'pe-panels'); ?></label>
					<span><input type="radio" name="<?php echo $this->get_field_name('content_type'); ?>" value="0" <?php checked( $setup['content_type'], '0' ); ?>><?php _e('Post content', 'pe-panels'); ?></span>
					<span><input type="radio" name="<?php echo $this->get_field_name('content_type'); ?>" value="1" <?php checked( $setup['content_type'], '1' ); ?>><?php _e('Post excerpt <small>(if exists)</small>', 'pe-panels'); ?></span>
				</p>

				<p class="pe-panels pe-panels-excerpt">
					<label for="<?php echo $this->get_field_id('excerpt'); ?>"><?php _e('Content limit:', 'pe-panels'); ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id('Excerpt'); ?>" name="<?php echo $this->get_field_name('excerpt'); ?>" type="text" value="<?php echo esc_attr($excerpt); ?>" />
					<small><?php _e('Note: Leave empty or 0 to display whole content. The limit will be ignored if More tag exists in the content. HTML and Shortcodes will be removed.', 'pe-panels'); ?></small>
				</p>

				<p class="pe-panels pe-panels-show-readmore">
					<label for="<?php echo $this->get_field_id('showreadmore'); ?>"><?php _e('Show readmore:', 'pe-panels'); ?></label>
					<select name="<?php echo $this->get_field_name('showreadmore'); ?>" id="<?php echo $this->get_field_id('showreadmore'); ?>">
						<option value="0"<?php selected( $setup['showreadmore'], '0' ); ?>><?php _e('No', 'pe-panels'); ?></option>
						<option value="1"<?php selected( $setup['showreadmore'], '1' ); ?>><?php _e('Yes', 'pe-panels'); ?></option>
					</select>
				</p>

				<p class="pe-panels pe-panels-show-date">
					<label for="<?php echo $this->get_field_id('showdate'); ?>"><?php _e('Show date:', 'pe-panels'); ?></label>
					<select name="<?php echo $this->get_field_name('showdate'); ?>" id="<?php echo $this->get_field_id('showdate'); ?>">
						<option value="0"<?php selected( $setup['showdate'], '0' ); ?>><?php _e('No', 'pe-panels'); ?></option>
						<option value="1"<?php selected( $setup['showdate'], '1' ); ?>><?php _e('Next to the title', 'pe-panels'); ?></option>
						<option value="2"<?php selected( $setup['showdate'], '2' ); ?>><?php _e('Above the content', 'pe-panels'); ?></option>
						<option value="3"<?php selected( $setup['showdate'], '3' ); ?>><?php _e('Below the content', 'pe-panels'); ?></option>
					</select>
				</p>

				<p class="pe-panels pe-panels-show-thumbnail">
					<label for="<?php echo $this->get_field_id('showthumb'); ?>"><?php _e('Show article thumbnail:', 'pe-panels'); ?></label>
					<select name="<?php echo $this->get_field_name('showthumb'); ?>" id="<?php echo $this->get_field_id('showthumb'); ?>">
						<option value="0"<?php selected( $setup['showthumb'], '0' ); ?>><?php _e('No', 'pe-panels'); ?></option>
						<option value="1"<?php selected( $setup['showthumb'], '1' ); ?>><?php _e('Yes', 'pe-panels'); ?></option>
					</select>
				</p>

				<p class="pe-panels pe-panels-img-align">
					<label for="<?php echo $this->get_field_id('imgalign'); ?>"><?php _e('Thumbnail alignment:', 'pe-panels'); ?></label>
					<select name="<?php echo $this->get_field_name('imgalign'); ?>" id="<?php echo $this->get_field_id('imgalign'); ?>">
						<option value="center"<?php selected( $setup['imgalign'], 'center' ); ?>><?php _e('Center', 'pe-panels'); ?></option>
						<option value="left"<?php selected( $setup['imgalign'], 'left' ); ?>><?php _e('Left', 'pe-panels'); ?></option>
						<option value="right"<?php selected( $setup['imgalign'], 'right' ); ?>><?php _e('Right', 'pe-panels'); ?></option>
					</select>
				</p>

				<p class="pe-panels pe-panels-img-size">
					<label for="<?php echo $this->get_field_id('imgsize'); ?>"><?php _e('Thumbnail Size:', 'pe-panels'); ?></label>
					<select name="<?php echo $this->get_field_name('imgsize'); ?>" id="<?php echo $this->get_field_id('imgsize'); ?>">
						<?php
							//get all available post sizes (except custom created)
							$pe_image_sizes = get_intermediate_image_sizes();
							foreach ($pe_image_sizes as $size_name):
								$custom_size_name = strpos($size_name, 'pe_panels-');
								if( $custom_size_name === false ) :
						?>
							<option value="<?php echo $size_name; ?>"<?php selected( $setup['imgsize'], $size_name ); ?>><?php echo $size_name; ?></option>
						<?php
								endif;
							endforeach;
						?>
						<option value="pe_panels_custom_size"<?php selected( $setup['imgsize'], 'pe_panels_custom_size' ); ?>><?php _e(' - Custom Size -', 'pe-panels'); ?></option>
					</select>
				</p>

				<p class="pe-panels pe-panels-img-width">
					<label for="<?php echo $this->get_field_id('imgwidth'); ?>"><?php _e('Width <small>(px)</small>:', 'pe-panels'); ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id('imgwidth'); ?>" name="<?php echo $this->get_field_name('imgwidth'); ?>" type="text" value="<?php echo esc_attr($imgwidth); ?>" />
				</p>

				<p class="pe-panels pe-panels-img-height">
					<label for="<?php echo $this->get_field_id('imgheight'); ?>"><?php _e('Height <small>(px)</small>:', 'pe-panels'); ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id('imgheight'); ?>" name="<?php echo $this->get_field_name('imgheight'); ?>" type="text" value="<?php echo esc_attr($imgheight); ?>" />
				</p>

				<p class="pe-panels pe-panels-img-crop">
					<label for="<?php echo $this->get_field_id('imgcrop'); ?>"><?php _e('Crop:', 'pe-panels'); ?></label>
					<select name="<?php echo $this->get_field_name('imgcrop'); ?>" id="<?php echo $this->get_field_id('imgcrop'); ?>">
						<option value="0"<?php selected( $setup['imgcrop'], '0' ); ?>><?php _e('No', 'pe-panels'); ?></option>
						<option value="1"<?php selected( $setup['imgcrop'], '1' ); ?>><?php _e('Center', 'pe-panels'); ?></option>
						<option value="2"<?php selected( $setup['imgcrop'], '2' ); ?>><?php _e('Center, Top', 'pe-panels'); ?></option>
						<option value="3"<?php selected( $setup['imgcrop'], '3' ); ?>><?php _e('Center, Bottom', 'pe-panels'); ?></option>
						<option value="4"<?php selected( $setup['imgcrop'], '4' ); ?>><?php _e('Left, Top', 'pe-panels'); ?></option>
						<option value="5"<?php selected( $setup['imgcrop'], '5' ); ?>><?php _e('Left, Center', 'pe-panels'); ?></option>
						<option value="6"<?php selected( $setup['imgcrop'], '6' ); ?>><?php _e('Left, Bottom', 'pe-panels'); ?></option>
						<option value="7"<?php selected( $setup['imgcrop'], '7' ); ?>><?php _e('Right, Top', 'pe-panels'); ?></option>
						<option value="8"<?php selected( $setup['imgcrop'], '8' ); ?>><?php _e('Right, Center', 'pe-panels'); ?></option>
						<option value="9"<?php selected( $setup['imgcrop'], '9' ); ?>><?php _e('Right, Bottom', 'pe-panels'); ?></option>
					</select>
				</p>

				<p class="pe-panels pe-panels-show-subtitle">
					<label for="<?php echo $this->get_field_id('showsubtitle'); ?>"><?php _e('Show subtitle <small>(taken from post excerpt)</small>:', 'pe-panels'); ?></label>
					<select name="<?php echo $this->get_field_name('showsubtitle'); ?>" id="<?php echo $this->get_field_id('showsubtitle'); ?>">
						<option value="0"<?php selected( $setup['showsubtitle'], '0' ); ?>><?php _e('No', 'pe-panels'); ?></option>
						<option value="1"<?php selected( $setup['showsubtitle'], '1' ); ?>><?php _e('Yes', 'pe-panels'); ?></option>
					</select>
				</p>

				<p class="pe-panels tabs pe-panels-full-width">
					<label for="<?php echo $this->get_field_id('fullwidth'); ?>"><?php _e('Full width <small>(tab headings)</small>:', 'pe-panels'); ?></label>
					<select name="<?php echo $this->get_field_name('fullwidth'); ?>" id="<?php echo $this->get_field_id('fullwidth'); ?>">
						<option value="0"<?php selected( $setup['fullwidth'], '0' ); ?>><?php _e('No', 'pe-panels'); ?></option>
						<option value="1"<?php selected( $setup['fullwidth'], '1' ); ?>><?php _e('Yes', 'pe-panels'); ?></option>
					</select>
				</p>

				<p class="pe-panels tabs pe-panels-position">
					<label for="<?php echo $this->get_field_id('position'); ?>"><?php _e('Tabs align :', 'pe-panels'); ?></label>
					<select name="<?php echo $this->get_field_name('position'); ?>" id="<?php echo $this->get_field_id('position'); ?>">
						<option value="0"<?php selected( $setup['position'], '0' ); ?>><?php _e('Above', 'pe-panels'); ?></option>
						<option value="1"<?php selected( $setup['position'], '1' ); ?>><?php _e('Left', 'pe-panels'); ?></option>
						<option value="2"<?php selected( $setup['position'], '2' ); ?>><?php _e('Right', 'pe-panels'); ?></option>
					</select>
				</p>

				<p class="pe-panels tabs responsive">
					<label for="<?php echo $this->get_field_id('responsive'); ?>"><?php _e('Responsive break point <small>(px)</small>:', 'pe-panels'); ?></label>
					<input class="widefat" id="<?php echo $this->get_field_id('responsive'); ?>" name="<?php echo $this->get_field_name('responsive'); ?>" type="text" value="<?php echo esc_attr($responsive); ?>" />
				</p>

				<p class="pe-panels acco pe-panels-first-open">
					<label for="<?php echo $this->get_field_id('firstopen'); ?>"><?php _e('First panel opened:', 'pe-panels'); ?></label>
					<select name="<?php echo $this->get_field_name('firstopen'); ?>" id="<?php echo $this->get_field_id('firstopen'); ?>">
						<option value="0"<?php selected( $setup['firstopen'], '0' ); ?>><?php _e('No', 'pe-panels'); ?></option>
						<option value="1"<?php selected( $setup['firstopen'], '1' ); ?>><?php _e('Yes', 'pe-panels'); ?></option>
					</select>
				</p>

				<p class="pe-panels acco pe-panels-multi-select">
					<label for="<?php echo $this->get_field_id('multiselect'); ?>"><?php _e('Multi select:', 'pe-panels'); ?></label>
					<select name="<?php echo $this->get_field_name('multiselect'); ?>" id="<?php echo $this->get_field_id('multiselect'); ?>">
						<option value="0"<?php selected( $setup['multiselect'], '0' ); ?>><?php _e('No', 'pe-panels'); ?></option>
						<option value="1"<?php selected( $setup['multiselect'], '1' ); ?>><?php _e('Yes', 'pe-panels'); ?></option>
					</select>
				</p>

			</div>
			<?php
		}

		// ---------------------------------------------------------------
		// WIDGET UPDATE
		// ---------------------------------------------------------------

		public function update($new_setup, $old_setup) {
			$setup = $old_setup;

			// check if we need regenerate thumbnail
			if(
					($new_setup['showthumb'] == 1 && $new_setup['imgsize'] == 'pe_panels_custom_size' && $setup['order'] != $new_setup['order']) ||
					($new_setup['showthumb'] == 1 && $new_setup['imgsize'] == 'pe_panels_custom_size' && $setup['imgcrop'] != $new_setup['imgcrop']) ||
					($new_setup['showthumb'] == 1 && $new_setup['imgsize'] == 'pe_panels_custom_size' && $setup['imgwidth'] != $new_setup['imgwidth']) ||
					($new_setup['showthumb'] == 1 && $new_setup['imgsize'] == 'pe_panels_custom_size' && $setup['imgheight'] != $new_setup['imgheight'])
				) {
				$setup['imgregenerate'] = 1;
			} else {
				$setup['imgregenerate'] = 0;
			}

			$setup['title'] = strip_tags($new_setup['title']);

			$setup['view'] = intval($new_setup['view']);

			if( is_array($new_setup['post_id']) ) {
				$setup['post_id'] = array_map('intval', $new_setup['post_id']);
			}
			if( is_array($new_setup['page_id']) ) {
				$setup['page_id'] = array_map('intval', $new_setup['page_id']);
			}

			$orderArray = explode(',',$new_setup['order']);
			$orderCleanArray = array_map('intval', $orderArray);
			$orderString = join(',', $orderCleanArray);
			$setup['order'] = $orderString;

			$setup['content_type'] = intval($new_setup['content_type']);

			$setup['excerpt'] = intval($new_setup['excerpt']);
			$setup['showreadmore'] = intval($new_setup['showreadmore']);

			$setup['showthumb'] = intval($new_setup['showthumb']);
			$setup['imgalign'] = esc_attr($new_setup['imgalign']);
			$setup['imgsize'] = esc_attr($new_setup['imgsize']);
			$setup['imgwidth'] = intval($new_setup['imgwidth']);
			$setup['imgheight'] = intval($new_setup['imgheight']);
			$setup['imgcrop'] = intval($new_setup['imgcrop']);

			$setup['showsubtitle'] = intval($new_setup['showsubtitle']);

			//tabs
			$setup['fullwidth'] = intval($new_setup['fullwidth']);
			$setup['position'] = intval($new_setup['position']);

			//accordion
			$setup['showdate'] = intval($new_setup['showdate']);
			$setup['firstopen'] = intval($new_setup['firstopen']);
			$setup['multiselect'] = intval($new_setup['multiselect']);

			$setup['responsive'] = intval($new_setup['responsive']);

			return $setup;
		}

	}
}

// ---------------------------------------------------------------
// ADDITIONAL FUNCTIONS
// ---------------------------------------------------------------

// register images
add_action('after_setup_theme', 'pe_panels_register_images');
if ( ! function_exists( 'pe_panels_register_images' ) ) {
	function pe_panels_register_images() {
		$widget_settings = get_option('widget_pe_panels');

		if( $widget_settings ) {
			foreach($widget_settings as $id => $opt ) {

				$widget_name = 'pe_panels-' . $id;

				$imgcrop = false;

				//options from widget
				$imgwidth = ( !empty($opt['imgwidth']) ) ? (int)$opt['imgwidth'] : 0;
				$imgheight = ( !empty($opt['imgheight']) ) ? (int)$opt['imgheight'] : 0;

				if( !empty($opt['imgcrop']) ) {
					if($opt['imgcrop'] == 1) { $imgcrop = true; }
					elseif($opt['imgcrop'] == 2) { $imgcrop = array('center','top'); }
					elseif($opt['imgcrop'] == 3) { $imgcrop = array('center','bottom'); }
					elseif($opt['imgcrop'] == 4) { $imgcrop = array('left','top'); }
					elseif($opt['imgcrop'] == 5) { $imgcrop = array('left','center'); }
					elseif($opt['imgcrop'] == 6) { $imgcrop = array('left','bottom'); }
					elseif($opt['imgcrop'] == 7) { $imgcrop = array('right','top'); }
					elseif($opt['imgcrop'] == 8) { $imgcrop = array('right','center'); }
					elseif($opt['imgcrop'] == 9) { $imgcrop = array('right','bottom'); }
				}

				//register only if size > 0
				if( $imgwidth > 0 && $imgheight > 0 ) {
					add_image_size( $widget_name, $imgwidth, $imgheight, $imgcrop );
				}
			}
		}

	}
}

// get thumbnails
if ( ! function_exists( 'pe_panels_thumbnail' ) ) {
	function pe_panels_thumbnail($widget_id, $post_id, $size = 'thumbnail', $crop = false, $force = false) {

			// get widget settings
			$widget_settings = get_option('widget_pe_panels');

			$widget_name = 'pe_panels-' . $widget_id;

			$width = $widget_settings[$widget_id]['imgwidth'];
			$height = $widget_settings[$widget_id]['imgheight'];
			$imgregenerate = $widget_settings[$widget_id]['imgregenerate'];

			// custom size
			// -------------------------------------------

			//custom and cropp (needed: crop, width, height)
			if( $size == 'pe_panels_custom_size' && !empty($crop) && !empty($width) && !empty($height) ) {

				// generate images
				if($imgregenerate == 1 || $force) {
					require_once( ABSPATH . 'wp-admin/includes/image.php' );

					// get attachment id
					$attachment_id = get_post_thumbnail_id( $post_id );

					// check if attachment is image
					if (! wp_attachment_is_image($attachment_id) ) {
						return __('PE Panels Error: the id is not referring to a media.', 'pe-panels');
					}

					// get upload directory info
					$upload_info = wp_upload_dir();
					$upload_dir  = $upload_info['basedir'];
					$upload_url  = $upload_info['baseurl'];

					// get file path info
					$path = get_attached_file( $attachment_id );
					$path_info = pathinfo( $path );

					$attach_data = wp_generate_attachment_metadata( $attachment_id, $path );
					wp_update_attachment_metadata( $attachment_id, $attach_data );
					update_attached_file($attachment_id, $path);

					if ( $attach_data ) {
						//Update widget settings
						$widget_settings[$widget_id]['imgregenerate'] = 0;

						//Update entire array
						update_option('widget_pe_panels', $widget_settings);
					} else {
						return __('PE Panels Error: Creating thumbnail failed.', 'pe-panels');
					}
				}
				// show cropped thumbnail
				return get_the_post_thumbnail($post_id, $widget_name); // image generated correctly
			}
			// custom and resize (needed: width, height)
			elseif( $size == 'pe_panels_custom_size' && !empty($width) && !empty($height) ) {
				return get_the_post_thumbnail( $post_id, array($width, $height) );
			}
			// default sizes
			elseif( $size != 'pe_panels_custom_size' ) {
				return get_the_post_thumbnail( $post_id, $size );
			}
			// if not required parameters
			else {
				return get_the_post_thumbnail( $post_id, 'large' );
			}

	}
}

// post excerpt
if ( ! function_exists( 'pe_panels_excerpt' ) ) {
	function pe_panels_excerpt($content, $len = 55, $trim = '&hellip;') {

		if ($len == 0) {
			echo '<!-- excerpt 0 -->';
			echo apply_filters('the_content', $content);
		} elseif ( strpos($content, '<!--more-->') ) { //if more quicktag in post
			echo '<!-- excerpt quick tag -->';
			$content = explode('<!--more-->', $content);
			echo apply_filters('the_content', $content[0]);
		} else { //prepare excerpt depends on limit
			echo '<!-- excerpt ' . $len . ' -->';
			$excerpt = strip_shortcodes( $content );
			$excerpt = strip_tags( $excerpt );
			$excerpt = str_split($excerpt);

			$letters = count($excerpt);

			if ($letters > $len) {
				$excerpt = array_slice($excerpt, 0, $len);
			} else {
				$trim = '';
			}

			$excerpt = implode('', $excerpt);
			$excerpt .= $trim;

			echo apply_filters('the_content', $excerpt);
		}
	}
}

// ---------------------------------------------------------------
// WIDGET SCRIPTS AND STYLES
// ---------------------------------------------------------------

add_action( 'wp_enqueue_scripts', 'pe_panels_enqueue' );
function pe_panels_enqueue() {
	$plugin_data = get_file_data(__FILE__, array('Version' => 'Version'), false);
	wp_enqueue_style( 'pe-panels', plugins_url() . '/pe-panels/css/panels.min.css', array(), $plugin_data['Version'] );
	wp_enqueue_script( 'jquery.touchSwipe', plugins_url() . '/pe-panels/js/jquery.touchSwipe.min.js', array('jquery'), '16.18', true );
	wp_enqueue_script( 'pe-panels-js', plugins_url() . '/pe-panels/js/panels.js', array('jquery', 'jquery.touchSwipe'), $plugin_data['Version'], true );
}

// load admin script
add_action( 'admin_enqueue_scripts', 'pe_panels_admin_enqueue' );
function pe_panels_admin_enqueue($hook) {
	if( $hook != 'widgets.php' ) return;
	$plugin_data = get_file_data(__FILE__, array('Version' => 'Version'), false);
	wp_enqueue_script( 'pe-panels-admin-js', plugins_url() . '/pe-panels/js/admin.js', array('jquery'), $plugin_data['Version'], false );
}


// register widget
add_action( 'widgets_init', 'pe_panels_reigster_widget', 1 );
function pe_panels_reigster_widget() {
	register_widget( 'PE_Panels' );
}

//enable translations
add_action('plugins_loaded', 'pe_panels_textdomain');
function pe_panels_textdomain() {
	load_plugin_textdomain( 'pe-panels', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
}
?>
