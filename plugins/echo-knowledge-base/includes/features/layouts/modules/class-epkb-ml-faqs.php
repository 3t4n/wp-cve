<?php

/**
 *  Outputs the FAQs module for Modular Main Page.
 *
 * @copyright   Copyright (c) 2022, Echo Plugins
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
class EPKB_ML_FAQs {

	const FAQS_KB_ID = 'epkb_ml_faqs_kb_id';
	const FAQS_CATEGORY_IDS = 'epkb_ml_faqs_category_ids';
	const FAQ_GROUP_IDS = 'epkb_faq_group_ids';

	private $kb_config;

	private $faqs_kb_config;
	private $faqs_category_ids;
	private $faqs_category_seq_data;
	private $faqs_articles_seq_data;

	function __construct( $kb_config ) {
		$this->kb_config = $kb_config;

		// FAQs module can use Categories and Articles from another KB
		$faqs_kb_id = EPKB_Utilities::get_kb_option( $this->kb_config['id'], self::FAQS_KB_ID, null );
		if ( empty( $faqs_kb_id ) ) {
			return;
		}

		$this->faqs_kb_config = epkb_get_instance()->kb_config_obj->get_kb_config( $faqs_kb_id, true );
		if ( is_wp_error( $this->faqs_kb_config ) ) {
			return;
		}

		// Display categories and articles only from published KBs
		if ( $this->faqs_kb_config['status'] != 'published' ) {
			return;
        }

		$this->faqs_category_ids = EPKB_Utilities::get_kb_option( $this->kb_config['id'], self::FAQS_CATEGORY_IDS, array() );
		$this->faqs_category_seq_data = EPKB_Utilities::get_kb_option( $this->faqs_kb_config['id'], EPKB_Categories_Admin::KB_CATEGORIES_SEQ_META, array(), true );
		$this->faqs_articles_seq_data = EPKB_Utilities::get_kb_option( $this->faqs_kb_config['id'], EPKB_Articles_Admin::KB_ARTICLES_SEQ_META, array(), true );

		// for WPML filter categories and articles given active language
		if ( EPKB_Utilities::is_wpml_enabled( $this->faqs_kb_config ) ) {
			$this->faqs_category_seq_data = EPKB_WPML::apply_category_language_filter( $this->faqs_category_seq_data );
			$this->faqs_articles_seq_data = EPKB_WPML::apply_article_language_filter( $this->faqs_articles_seq_data );
		}
	}

	public function display_faqs_module() {

		// do we display old FAQ Categories?
		if ( ! empty( $this->faqs_category_ids ) ) {
			$this->get_faqs_as_categories();
			return;
		}

		// do we have FAQ Groups to display?
		$selected_faq_group_ids = EPKB_Utilities::get_kb_option( $this->kb_config['id'], EPKB_ML_FAQs::FAQ_GROUP_IDS, array() );
		if ( empty( $selected_faq_group_ids ) ) {
			return;
		}

		$faq_groups = EPKB_FAQs_Utilities::get_faq_groups( $selected_faq_group_ids, 'include' );
		if ( is_wp_error( $faq_groups ) ) {
			echo EPKB_FAQs_Utilities::display_error( $faq_groups->get_error_message() );
		}

		$faq_groups_questions = EPKB_FAQs_Utilities::get_faq_groups_questions( $faq_groups );

		echo EPKB_FAQs_Utilities::display_faqs( $this->kb_config, $faq_groups_questions, $this->kb_config['ml_faqs_title_text'] );
	}

	private function get_faqs_as_categories() {

		$stored_ids_obj = new EPKB_Categories_Array( $this->faqs_category_seq_data ); // normalizes the array as well
		$allowed_categories_ids = $stored_ids_obj->get_all_keys();

		// No categories found - message only for admins
		if ( empty( $allowed_categories_ids ) ) {
			if ( current_user_can( 'manage_options' ) ) {
				esc_html_e( 'FAQs Module: No categories with articles found.', 'echo-knowledge-base' );
			}
			return;
		}

		// remove epkb filter
		remove_filter( 'the_content', array( 'EPKB_Layouts_Setup', 'get_kb_page_output_hook' ), 99999 );    ?>

		<div id="epkb-ml-faqs-<?php echo strtolower( $this->kb_config['kb_main_page_layout'] ); ?>-layout" class="epkb-ml-faqs-container <?php echo esc_html( $this->kb_config['ml_faqs_custom_css_class'] ); ?>">

			<div class="epkb-ml-faqs__row"> <?php

				$faq_groups = [];
				foreach( $this->faqs_category_ids as $selected_category_id ) {

					if ( empty( $this->faqs_articles_seq_data[$selected_category_id] ) ) {
						continue;
					}

					if ( empty( $allowed_categories_ids[$selected_category_id] ) ) {
						continue;
					}

					foreach ( $this->faqs_articles_seq_data[$selected_category_id] as $article_id => $article_title ) {

						// category title/description
						if ( $article_id == 0 || $article_id == 1 ) {
							continue;
						}

						// exclude linked articles
						$article = get_post( $article_id );

						// disallow article that failed to retrieve
						if ( empty( $article ) || empty( $article->post_status ) ) {
							unset( $this->faqs_articles_seq_data[$selected_category_id][$article_id] );
							continue;
						}

						if ( EPKB_Utilities::is_link_editor( $article ) ) {
							unset( $this->faqs_articles_seq_data[$selected_category_id][$article_id] );
							continue;
						}

						// exclude not allowed
						if ( ! EPKB_Utilities::is_article_allowed_for_current_user( $article_id ) ) {
							unset( $this->faqs_articles_seq_data[$selected_category_id][$article_id] );
						}
					}

					// not empty term but with hidden articles for the user
					if ( empty( $this->faqs_articles_seq_data[$selected_category_id] ) ) {
						continue;
					}

					$faqs = [];
					foreach( $this->faqs_articles_seq_data[$selected_category_id] as $article_id => $article_title ) {

						if ( $article_id == 0 || $article_id == 1 ) {
							continue;
						}

						// second call is cached by wp core, will not create db query
						$article = get_post( $article_id );

						// disallow article that failed to retrieve
						if ( empty( $article ) || empty( $article->post_status ) ) {
							continue;
						}

						// ignore password-protected pages
						if ( ! empty( $article->post_password ) ) {
							continue;
						}

						if ( $this->kb_config['ml_faqs_content_mode'] == 'excerpt' ) {
							$post_content = $article->post_excerpt;
						} else {
							$post_content = $article->post_content;
						}

						$article->post_content = $post_content;
						$article->post_title = get_the_title($article);

						$faqs[] = $article;
					}

					$faq_groups[$selected_category_id] = ['title' => $this->faqs_articles_seq_data[$selected_category_id][0], 'faqs' => $faqs];
				}

				echo EPKB_FAQs_Utilities::display_faqs( $this->kb_config, $faq_groups, $this->kb_config['ml_faqs_title_text'] );				?>

			</div>

		</div>  <?php

		// add epkb filter back
		add_filter( 'the_content', array( 'EPKB_Layouts_Setup', 'get_kb_page_output_hook' ), 99999 );
	}

	/**
	 * Returns inline styles for FAQs Module
	 *
	 * @param $kb_config
	 * @return string
	 */
	public static function get_inline_styles( $kb_config ) {

		/*
		 * Legacy Layouts that have specific settings
		 */
		$legacy_layouts = [
			EPKB_Layout::BASIC_LAYOUT,
			EPKB_Layout::TABS_LAYOUT,
			EPKB_Layout::CATEGORIES_LAYOUT,
			EPKB_Layout::SIDEBAR_LAYOUT,
			EPKB_Layout::GRID_LAYOUT,
		];

		// Use CSS Settings from Layout selected to match the styling.
		$output = '';
		$shadow_setting_name = 'section_box_shadow';
		$background_setting_name = 'section_body_background_color';
		$border_setting_prefix = 'section_border';
		$head_font_setting_name = 'section_head_font_color';
		$head_typography_setting_name = 'section_head_typography';
		$article_typography_setting_name = 'article_typography';
		$article_font_setting_name = 'article_font_color';
		if ( EPKB_Utilities::is_elegant_layouts_enabled() ) {
			switch ( $kb_config['kb_main_page_layout'] ) {
				case EPKB_Layout::GRID_LAYOUT:
					$shadow_setting_name = 'grid_section_box_shadow';
					$border_setting_prefix = 'grid_section_border';
					$head_typography_setting_name = 'grid_section_typography';
					$article_font_setting_name = 'section_category_font_color';
					break;
				case EPKB_Layout::SIDEBAR_LAYOUT:
					$shadow_setting_name = 'sidebar_section_box_shadow';
					$background_setting_name = 'sidebar_background_color';
					$border_setting_prefix = 'sidebar_section_border';
					$head_font_setting_name = 'sidebar_section_head_font_color';
					$head_typography_setting_name = 'sidebar_section_category_typography';
					$article_typography_setting_name = 'sidebar_section_body_typography';
					$article_font_setting_name = 'sidebar_article_font_color';
					break;
				default: break;
			}
		}

		// Container -----------------------------------------/
		$container_shadow = '';
		if ( in_array( $kb_config['kb_main_page_layout'], $legacy_layouts ) ) {

			switch ( $kb_config[$shadow_setting_name] ) {
				case 'section_light_shadow':
					$container_shadow = '
						box-shadow: 0px 3px 20px -10px rgba(0, 0, 0, 0.75);
						padding:20px;';
					break;
				case 'section_medium_shadow':
					$container_shadow = '
						box-shadow: 0px 3px 20px -4px rgba(0, 0, 0, 0.75);
						padding:20px;';
					break;
				case 'section_bottom_shadow':
					$container_shadow = '
						box-shadow: 0 2px 0 0 #E1E1E1;
						padding:20px;';
					break;
				case 'no_shadow':
				default:
					break;
			}
			$output .= '
			#epkb-modular-main-page-container .epkb-faqs-cat-container {
				' . esc_attr( $container_shadow ) . '
			}';
		}

		// Headings Typography -----------------------------------------/
		if ( in_array( $kb_config['kb_main_page_layout'], $legacy_layouts ) ) {
			if ( ! empty( $kb_config[$head_typography_setting_name]['font-size'] ) || ! empty( $kb_config[$head_typography_setting_name]['font-weight'] ) ) {
				$output .= '
				.epkb-faqs-container .epkb-faqs-title {
                ' . ( empty( $kb_config[$head_typography_setting_name]['font-size'] ) ? '' : 'font-size:' . ( intval( $kb_config[$head_typography_setting_name]['font-size'] ) + 5 ) . 'px !important;' ) . '
				}
				.epkb-faqs-cat-container .epkb-faqs__cat-header .epkb-faqs__cat-header__title {
				    ' . ( empty( $kb_config[$head_typography_setting_name]['font-size'] ) ? '' : 'font-size:' . esc_attr( $kb_config[$head_typography_setting_name]['font-size'] ) . 'px !important;' ) . '
				    ' . ( empty( $kb_config[$head_typography_setting_name]['font-weight'] ) ? '' : 'font-weight:' . esc_attr( $kb_config[$head_typography_setting_name]['font-weight'] ) . '!important;' ) . '
			    }';
			}
		}

		return $output;
	}
}