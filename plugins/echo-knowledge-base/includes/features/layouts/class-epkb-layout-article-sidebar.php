<?php

/**
 *  Outputs the Sidebar Layout for knowledge base on Article Page for core layouts.
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 */
class EPKB_Layout_Article_Sidebar extends EPKB_Layout {

	/**
	 * DISPLAY SIDEBAR
	 * For each CATEGORY display: a) its articles and b) top-level SUB-CATEGORIES with its articles
	 *
	 * @param $kb_config
	 */
	public function display_article_sidebar( $kb_config ) {

		// ONLY used to set up class and generate categories and articles sequence
		$this->display_kb_main_page( $kb_config );

		// Reformat Class Names
		$boxShadow = '';
		if ( ! empty( $this->kb_config['sidebar_section_box_shadow'] ) ) {
			switch ( $this->kb_config['sidebar_section_box_shadow'] ) {
				case 'section_light_shadow':
					$boxShadow = 'epkb-sidebar--light-shadow';
					break;
				case 'section_medium_shadow':
					$boxShadow = 'epkb-sidebar--medium-shadow';
					break;
				case 'section_bottom_shadow':
					$boxShadow = 'epkb-sidebar--bottom-shadow';
					break;
			}
		}

		$slimScrollbar = '';
		if ( ! empty( $this->kb_config['sidebar_scroll_bar'] ) ) {
			switch ( $this->kb_config['sidebar_scroll_bar'] ) {
				case 'slim_scrollbar':
					$slimScrollbar = 'epkb-sidebar--slim-scrollbar';
					break;
				case 'default_scrollbar':
					break;
			}
		}

		$sidebar_top_categories_collapsed_Class = '';
		$sidebar_top_categories_collapsed = $this->kb_config['sidebar_top_categories_collapsed'];
		if ( $sidebar_top_categories_collapsed == 'on' ) {
			$sidebar_top_categories_collapsed_Class = 'epkb-sidebar--TopCat-on';
		}   ?>

		<section id="epkb-sidebar-container-v2"
				 class="epkb-sidebar--reset <?php echo $boxShadow . ' ' . $slimScrollbar . ' ' . $sidebar_top_categories_collapsed_Class . ' ' . EPKB_Utilities::get_active_theme_classes( 'ap' ); ?>">

			<ul class="epkb-sidebar__cat-container">            <?php

				/** DISPLAY TOP CATEGORIES and ARTICLES */
				$section_count = 0;
				foreach ( $this->category_seq_data as $category_id => $subcategories ) { ?>

					<li id="epkb-top-cat-id-<?php echo $category_id; ?>"
						class="epkb-sidebar__cat__top-cat">                <?php
						$this->display_section_heading_V2( $category_id );
						$this->display_section_body_V2( $subcategories, $category_id ); ?>
					</li>                <?php
					$section_count ++;

				} ?>

			</ul>

		</section>        <?php
	}

	public static function generate_sidebar_CSS_V2( $kb_config ) {

		// Container
		$container_background_Color = $kb_config['sidebar_background_color'];
		$container_border_Color = $kb_config['sidebar_section_border_color'];
		$container_border_Width = $kb_config['sidebar_section_border_width'];
		$container_border_Radius = $kb_config['sidebar_section_border_radius'];
		$sidebar_side_bar_height = $kb_config['sidebar_side_bar_height'];

		// Category Heading
		$catHeading_alignment = $kb_config['sidebar_section_head_alignment'];
		$catHeading_dividerThickness = $kb_config['sidebar_section_divider_thickness'];
		$catHeading_paddingTop = $kb_config['sidebar_section_head_padding_top'];
		$catHeading_paddingBottom = $kb_config['sidebar_section_head_padding_bottom'];
		$catHeading_paddingLeft = $kb_config['sidebar_section_head_padding_left'];
		$catHeading_paddingRight = $kb_config['sidebar_section_head_padding_right'];
		$catHeading_dividerColor = $kb_config['sidebar_section_divider_color'];
		$catHeading_BackgroundColor = $kb_config['sidebar_section_head_background_color'];

		// Category Heading - Inner
		$catHeadingInner_fontColor = $kb_config['sidebar_section_head_font_color'];
		$catHeadingInner_TextAlignment = $kb_config['sidebar_section_head_alignment'];
		$catHeadingInner_DescColor = $kb_config['sidebar_section_head_description_font_color'];

		// Category Body
		$catBodyContainer_paddingTop = $kb_config['sidebar_section_body_padding_top'];
		$catBodyContainer_paddingBottom = $kb_config['sidebar_section_body_padding_bottom'];
		$catBodyContainer_paddingLeft = $kb_config['sidebar_section_body_padding_left'];
		$catBodyContainer_paddingRight = $kb_config['sidebar_section_body_padding_right'];

		$catBodyContainer_BodyHeight = $kb_config['sidebar_section_body_height'];

		// Article
		$article_Font_color = $kb_config['sidebar_article_font_color'];
		$article_Font_Active_color = $kb_config['sidebar_article_active_font_color'];
		$article_Font_BackgroundColor = $kb_config['sidebar_article_active_background_color'];

		// Category Main Category

		// Category Sub Category
		$catBodySubCatArticleMargin = $kb_config['sidebar_article_list_margin'];

		// Theme class for the themes wizard

		$output = '';

		/* Container */
		if ( $kb_config['sidebar_side_bar_height_mode'] == 'side_bar_fixed_height' ) {
			$output .= '
			#epkb-sidebar-container-v2 {
				overflow: auto;
				max-height: ' . $sidebar_side_bar_height . 'px;
			}';
		}
		$output .= '
			#epkb-sidebar-container-v2 {
				background-color: ' . $container_background_Color . ';
				border-color: ' . $container_border_Color . ';
				border-width: ' . $container_border_Width . 'px;
				border-radius: ' . $container_border_Radius . 'px;
			}';

		/* Category Heading */
		$output .= '
			#epkb-sidebar-container-v2 .epkb-sidebar__cat__top-cat__heading-container {
				text-align: ' . $catHeading_alignment . ';
                border-width: ' . $catHeading_dividerThickness . 'px;
                padding-top: ' . $catHeading_paddingTop . 'px;
                padding-bottom: ' . $catHeading_paddingBottom . 'px;
                padding-left: ' . $catHeading_paddingLeft . 'px;
                padding-right: ' . $catHeading_paddingRight . 'px;
                border-bottom-color: ' . $catHeading_dividerColor . ';
                background-color: ' . $catHeading_BackgroundColor . ';
			}';

			if ( $catHeading_alignment == 'right' ) {
				$output .='
					#epkb-sidebar-container-v2 .epkb-sidebar__cat__top-cat__heading-container .epkb-sidebar__heading__inner__name {
						flex-direction: row-reverse;
					}
					#epkb-sidebar-container-v2 .epkb-sidebar__cat__top-cat__heading-container {
	                    flex-direction: row-reverse;
	                }
	                .epkb_sidebar_expand_category_icon {
						padding-left: 5px !important;
						padding-right: 0px !important;
					}';

			} else if( $catHeading_alignment == 'center' ) {
				$output .= '
					#epkb-sidebar-container-v2 .epkb-sidebar__cat__top-cat__heading-container .epkb-sidebar__heading__inner__name {
						justify-content: center;
					}
		            .epkb-sidebar--TopCat-on .epkb-sidebar__cat__top-cat__heading-container {
		                justify-content: center;
		            }';
			}

			$output .= '
			#epkb-sidebar-container-v2 .epkb-sidebar__cat__top-cat:first-child .epkb-sidebar__cat__top-cat__heading-container {
				border-top-left-radius: ' . $container_border_Radius . 'px; 
				border-top-right-radius: ' . $container_border_Radius . 'px;
			}
			#epkb-sidebar-container-v2 .epkb-sidebar__cat__top-cat:last-child .epkb-sidebar__cat__top-cat__heading-container {
				border-bottom-left-radius: ' . $container_border_Radius . 'px; 
				border-bottom-right-radius: ' . $container_border_Radius . 'px;
			}
			#epkb-sidebar-container-v2 .epkb-sidebar__heading__inner .epkb-sidebar__heading__inner__name,
			#epkb-sidebar-container-v2 .epkb-sidebar__heading__inner .epkb-sidebar__heading__inner__cat-name,
			#epkb-sidebar-container-v2 .epkb-sidebar__heading__inner .epkb-sidebar__heading__inner__name > a {
				color: ' . $catHeadingInner_fontColor . '; 
				text-align: ' . $catHeadingInner_TextAlignment . ';
			}
			#epkb-sidebar-container-v2 .epkb-sidebar__heading__inner .epkb-sidebar__heading__inner__desc p {
				color: ' . $catHeadingInner_DescColor . '; 
				text-align: ' . $catHeadingInner_TextAlignment . ';
			}
			#epkb-sidebar-container-v2 .epkb-sidebar__cat__top-cat__body-container {
				padding-top: ' . $catBodyContainer_paddingTop . 'px; 
				padding-bottom: ' . $catBodyContainer_paddingBottom . 'px;
				padding-left: ' . $catBodyContainer_paddingLeft . 'px;
				padding-right: ' . $catBodyContainer_paddingRight . 'px;
			}
			';

		if ( $kb_config['sidebar_section_box_height_mode'] == 'section_min_height' ) {
			$output .= '
			#epkb-sidebar-container-v2 .epkb-sidebar__cat__top-cat__body-container {
				min-height: ' . $catBodyContainer_BodyHeight . ';
			}';
		} else if ( $kb_config['sidebar_section_box_height_mode'] == 'section_fixed_height' ) {
			$output .= '
			#epkb-sidebar-container-v2 .epkb-sidebar__cat__top-cat__body-container {
				overflow: auto;
				height: ' . $catBodyContainer_BodyHeight . ';
			}';
		}

		$output .= '
			#epkb-sidebar-container-v2 .epkb-sidebar__cat__top-cat__body-container .epkb-sidebar__body__sub-cat {
				padding-left: ' . $catBodySubCatArticleMargin . 'px;
			}
			.epkb-sidebar__cat__top-cat__body-container .epkb-articles .eckb-article-title {
				color: ' . $article_Font_color . '!important;
			}
			.epkb-sidebar__cat__top-cat__body-container .epkb-articles .active {
				color: ' . $article_Font_Active_color . ';
				background-color: ' . $article_Font_BackgroundColor . ';
			}
			.epkb-sidebar__cat__top-cat__body-container .epkb-articles .active .eckb-article-title {
				color: ' . $article_Font_Active_color . '!important;
			}';

		return $output;
	}

	private function display_section_heading_V2( $category_id ) {

		$section_divider = $this->kb_config['sidebar_section_divider'] == 'on' ? ' sidebar_section_divider' : '';

		$category_name = isset( $this->articles_seq_data[ $category_id ][0] ) ? $this->articles_seq_data[ $category_id ][0] : 'Uncategorized';
		$category_desc = isset( $this->articles_seq_data[ $category_id ][1] ) && $this->kb_config['sidebar_section_desc_text_on'] == 'on' ? $this->articles_seq_data[ $category_id ][1] : '';
		$box_category_data = $this->is_builder_on ? 'data-kb-category-id=' . $category_id . ' data-kb-type=category ' : '';

		$class1 = $this->get_css_class( '::sidebar_expand_articles_icon, epkb_sidebar_expand_category_icon' );

		$sidebar_top_categories_collapsed = $this->kb_config['sidebar_top_categories_collapsed'];
		$topClassCollapse = $this->kb_config['sidebar_top_categories_collapsed'] == 'on' ? ' epkb-top-class-collapse-on' : '';
		$categoryIcon = '';

		$top_category_style = $this->get_inline_style(
			'typography:: sidebar_section_category_typography'
		);
		$top_category_desc_style = $this->get_inline_style(
			'typography:: sidebar_section_category_typography_desc'
		);

		if ( $sidebar_top_categories_collapsed == 'on' ) {
			$categoryIcon = '<span ' . $class1 . '></span>';
		} ?>

		<div class="epkb-sidebar__cat__top-cat__heading-container <?php echo $topClassCollapse . ' ' . $section_divider; ?>">
			<div class="epkb-sidebar__heading__inner" <?php echo $box_category_data; ?>>

				<!-- CATEGORY ICON -->
				<div class="epkb-sidebar__heading__inner__name">
					<?php echo $categoryIcon; ?>
					<h2 class="epkb-sidebar__heading__inner__cat-name" <?php echo $top_category_style; ?>><?php echo $category_name; ?></h2>
				</div>


				<!-- CATEGORY DESC --> <?php
				if ( $category_desc ) { ?>
					<div class="epkb-sidebar__heading__inner__desc">
						<p <?php echo $top_category_desc_style; ?>><?php echo $category_desc; ?></p>
					</div>
					<?php
				} ?>
			</div>
		</div>        <?php
	}

	private function display_section_body_V2( $subcategories, $category_id ) {

		$top_category_body_style = $this->get_inline_style(
			'typography:: sidebar_section_body_typography'
		); ?>

		<div class="epkb-sidebar__cat__top-cat__body-container" <?php echo $top_category_body_style; ?>>  <?php

			$sub_category_list = is_array( $subcategories ) ? $subcategories : array();

			/** DISPLAY TOP-CATEGORY ARTICLES LIST */
			if ( $this->kb_config['sidebar_show_articles_before_categories'] != 'off' ) {
				$this->display_articles_list( 1, $category_id, ! empty( $sub_category_list ) );
			}

			if ( $sub_category_list ) { ?>
				<ul class="epkb-sidebar__body__sub-cat eckb-sub-category-ordering"><?php

					/** DISPLAY SUB-CATEGORIES */
					foreach ( $sub_category_list as $sub_category_id => $sub_sub_categories ) {
						$sub_category_name = isset( $this->articles_seq_data[ $sub_category_id ][0] ) ?
							$this->articles_seq_data[ $sub_category_id ][0] : _x( 'Category', 'taxonomy singular name' );

						$class1 = $this->get_css_class( '::sidebar_expand_articles_icon, epkb_sidebar_expand_category_icon' );
						$style1 = $this->get_inline_style( 'color:: sidebar_section_category_icon_color' );

						$box_sub_category_data = $this->is_builder_on ? 'data-kb-category-id=' . $sub_category_id . ' data-kb-type=sub-category ' : ''; ?>

						<li>
							<div class="epkb-category-level-2-3" <?php echo $this->get_inline_style( 'padding-bottom:: article_list_spacing,padding-top::article_list_spacing' ); ?><?php echo $box_sub_category_data; ?>>
								<span <?php echo $class1 . ' ' . $style1; ?> ></span>
								<a class="epkb-category-level-2-3__cat-name">
									<h3><?php echo $sub_category_name; ?></h3></a>
							</div> <?php

							/** DISPLAY SUB-CATEGORY ARTICLES LIST */
							if ( $this->kb_config['sidebar_show_articles_before_categories'] != 'off' ) {
								$this->display_articles_list( 2, $sub_category_id, ! empty( $sub_sub_categories ) );
							}

							$this->display_sub_sub_categories( $sub_sub_categories );

							/** DISPLAY SUB-CATEGORY ARTICLES LIST */
							if ( $this->kb_config['sidebar_show_articles_before_categories'] == 'off' ) {
								$this->display_articles_list( 2, $sub_category_id, ! empty( $sub_sub_categories ) );
							} ?>
						</li>    <?php

					}  //foreach  ?>

				</ul>            <?php
			}

			/** DISPLAY TOP-CATEGORY ARTICLES LIST */
			if ( $this->kb_config['sidebar_show_articles_before_categories'] == 'off' ) {
				$this->display_articles_list( 1, $category_id, ! empty( $sub_category_list ) );
			} ?>
		</div>    <?php
	}

	private function display_sub_sub_categories( $sub_sub_categories, $level = 'sub-', $levelNum = 4 ) {

		$level .= 'sub-';

		$sub_category_styles = is_rtl() ? 'padding-right:: sidebar_article_list_margin,' : 'padding-left:: sidebar_article_list_margin,';

		$sub_category_list = is_array( $sub_sub_categories ) ? $sub_sub_categories : array();
		if ( $sub_category_list ) { ?>
			<ul class="epkb-sub-sub-category eckb-sub-sub-category-ordering" <?php echo $this->get_inline_style( $sub_category_styles ); ?>>                    <?php

				/** DISPLAY SUB-SUB-CATEGORIES */
				foreach ( $sub_category_list as $sub_sub_category_id => $sub_sub_category_list ) {
					$sub_category_name = isset( $this->articles_seq_data[ $sub_sub_category_id ][0] ) ?
						$this->articles_seq_data[ $sub_sub_category_id ][0] : 'Category.';

					$class1 = $this->get_css_class( '::sidebar_expand_articles_icon, epkb_sidebar_expand_category_icon' );
					$style1 = $this->get_inline_style( 'color:: sidebar_section_category_icon_color' );
					$style2 = $this->get_inline_style( 'color:: sidebar_section_category_font_color' );

					$box_sub_category_data = $this->is_builder_on ? 'data-kb-category-id=' . $sub_sub_category_id . ' data-kb-type=' . $level . 'category ' : ''; ?>

					<li>
						<div class="epkb-category-level-2-3" <?php echo $this->get_inline_style( 'padding-bottom:: article_list_spacing, padding-top::article_list_spacing' ); ?> <?php echo $box_sub_category_data; ?>>
							<span <?php echo $class1 . ' ' . $style1; ?> ></span>
							<a class="epkb-category-level-2-3__cat-name" <?php echo $style2; ?> >
								<h<?php echo $levelNum; ?>><?php echo $sub_category_name; ?></h<?php echo $levelNum; ?> >
							</a>
						</div> <?php

						/** DISPLAY SUB-SUB-CATEGORY ARTICLES LIST */
						if ( $this->kb_config['sidebar_show_articles_before_categories'] != 'off' ) {
							$this->display_articles_list( 3, $sub_sub_category_id, ! empty( $sub_sub_category_list ), $level );
						}

						/** RECURSION DISPLAY SUB-SUB-...-CATEGORIES */
						if ( ! empty( $sub_sub_category_list ) && strlen( $level ) < 20 ) {
							$levelNum ++;
							$this->display_sub_sub_categories( $sub_sub_category_list, $level, $levelNum );
						}

						/** DISPLAY SUB-SUB-CATEGORY ARTICLES LIST */
						if ( $this->kb_config['sidebar_show_articles_before_categories'] == 'off' ) {
							$this->display_articles_list( 3, $sub_sub_category_id, ! empty( $sub_sub_category_list ), $level );
						} ?>
					</li>    <?php

				}  //foreach  			?>

			</ul>            <?php
		}
	}

	/**
	 * Display list of articles that belong to given subcategory
	 *
	 * @param $level
	 * @param $category_id
	 * @param bool $sub_category_exists - if true then we don't want to show "Articles coming soon" if there are no articles because
	 *                                   we have at least categories listed. But sub-category should always have that message if no article present
	 * @param string $sub_sub_string
	 */
	private function display_articles_list( $level, $category_id, $sub_category_exists = false, $sub_sub_string = '' ) {

		// retrieve articles belonging to given (sub) category if any
		$articles_list = array();
		if ( isset( $this->articles_seq_data[ $category_id ] ) ) {
			$articles_list = $this->articles_seq_data[ $category_id ];
			unset( $articles_list[0] );
			unset( $articles_list[1] );
		}

		// return if we have no articles and will not show 'Articles coming soon' message
		$articles_coming_soon_msg = $this->kb_config['sidebar_category_empty_msg'];
		if ( empty( $articles_list ) && ( $sub_category_exists || empty( $articles_coming_soon_msg ) ) ) {
			return;
		}

		$sub_category_styles = '';
		if ( $level == 1 ) {
			$data_kb_type = 'article';
			$sub_category_styles .= is_rtl() ? 'padding-right:: sidebar_article_list_margin,' : 'padding-left:: sidebar_article_list_margin,';
		} else if ( $level == 2 ) {
			$sub_category_styles .= is_rtl() ? 'padding-right:: sidebar_article_list_margin,' : 'padding-left:: sidebar_article_list_margin,';
			$data_kb_type = 'sub-article';
		} else {
			$sub_category_styles .= is_rtl() ? 'padding-right:: sidebar_article_list_margin,' : 'padding-left:: sidebar_article_list_margin';
			$data_kb_type = empty( $sub_sub_string ) ? 'sub-sub-article' : $sub_sub_string . 'article';
		}

		$class = 'class="' . ( $level == 1 ? 'epkb-sidebar__body__main-cat ' : '' ) . 'epkb-articles eckb-articles-ordering"'; ?>

		<ul <?php echo $class . ' ' . $this->get_inline_style( $sub_category_styles ); ?>> <?php

			$article_num = 0;

			$nof_articles_displayed = isset( $_GET['wizard-on'] ) ? 9999 : $this->kb_config['sidebar_nof_articles_displayed'];

			// show list of articles in this category
			foreach ( $articles_list as $article_id => $article_title ) {

				if ( ! EPKB_Utilities::is_article_allowed_for_current_user( $article_id ) ) {
					continue;
				}
				
				$article_num ++;
				$hide_class = $article_num > $nof_articles_displayed ? 'epkb-hide-elem' : '';
				$seq_no = isset( $this->displayed_article_ids[ $article_id ] ) ? $this->displayed_article_ids[ $article_id ] + 1 : 1;
				$style2 = 'id="sidebar_link_' . $article_id . ( $seq_no > 1 ? '_' . $seq_no : '' ) . '"';

				/** DISPLAY ARTICLE LINK */ ?>
				<li class="<?php echo $hide_class; ?>" <?php echo $style2 . ' ' . $this->get_inline_style( 'padding-bottom:: article_list_spacing,padding-top::article_list_spacing' ); ?> >   <?php
					$this->single_article_link( $article_title, $article_id, 'Article_Sidebar' ); ?>
				</li> <?php
			}

			// if article list is longer than initial article list size then show expand/collapse message
			if ( $article_num > $nof_articles_displayed ) { ?>
				<li class="epkb-show-all-articles" aria-expanded="false">
					<span class="epkb-show-text">
						<span><?php echo esc_html( $this->kb_config['sidebar_show_all_articles_msg'] ) . '</span> ( ' . ( $article_num - $nof_articles_displayed ); ?> )
					</span>
					<span class="epkb-hide-text epkb-hide-elem"><?php echo esc_html( $this->kb_config['sidebar_collapse_articles_msg'] ); ?></span>
				</li>                    <?php
			}

			if ( $article_num == 0 ) {
				echo '<li ' . $this->get_inline_style( 'padding-bottom:: article_list_spacing,padding-top::article_list_spacing' ) . 'class="epkb-articles-coming-soon">' .
					 esc_html__( $articles_coming_soon_msg, 'echo-elegant-layouts' ) . '</li>';
			} ?>

		</ul> <?php
	}

	protected function generate_kb_main_page() {
		// not used
	}
}
