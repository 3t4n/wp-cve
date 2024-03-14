<?php
/**
 * Define settings for options
 *
 * @package   PT_Content_Views
 * @author    PT Guy <http://www.contentviewspro.com/>
 * @license   GPL-2.0+
 * @link      http://www.contentviewspro.com/
 * @copyright 2014 PT Guy
 */
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

if ( !class_exists( 'PT_CV_Settings' ) ) {

	/**
	 * @name PT_CV_Settings
	 * @todo Define settings for options
	 */
	class PT_CV_Settings {

		/**
		 * Get collection : Taxonomies => Terms
		 *
		 * @param string $taxonomies Array of taxonomies
		 * @param array  $args       Array of query parameters
		 */
		static function terms_of_taxonomies( $taxonomies = array(), $args = array() ) {
			$terms_of_taxonomies = $result				 = array();
			// Get taxonomies
			$taxonomies			 = PT_CV_Values::taxonomy_list();
			// Get slug list of taxonomies
			$taxonomies_slug	 = array_keys( $taxonomies );

			foreach ( $taxonomies_slug as $taxonomy_slug ) {
				PT_CV_Values::term_of_taxonomy( $taxonomy_slug, $terms_of_taxonomies, $args );
			}

			foreach ( $terms_of_taxonomies as $taxonomy_slug => $terms ) {

				$result[ $taxonomy_slug ] = apply_filters( PT_CV_PREFIX_ . 'taxonomy_settings', array(
					// Select term to filter
					array(
						'label'	 => array(
							'text' => __( 'Select terms', 'content-views-query-and-display-post-page' ),
						),
						'params' => array(
							array(
								'type'		 => 'select',
								'name'		 => $taxonomy_slug . '-terms[]',
								'options'	 => $terms,
								'std'		 => '',
								'class'		 => apply_filters( PT_CV_PREFIX_ . 'select_term_class', 'select2' ),
								'multiple'	 => '1',
							),
						),
					),
					//Operator
					array(
						'label'		 => array(
							'text' => __( 'Operator', 'content-views-query-and-display-post-page' ),
						),
						'params'	 => array(
							array(
								'type'		 => 'select',
								'name'		 => $taxonomy_slug . '-operator',
								'options'	 => PT_CV_Values::taxonomy_operators(),
								'std'		 => 'IN',
							),
						),
						'dependence' => array( 'taxonomy-term-info', 'as_output', '!=' ),
					),
					), $taxonomy_slug );
			}

			return $result;
		}

		/**
		 * Order by options
		 *
		 * @return array
		 */
		static function orderby() {
			$result = array();

			$result[ 'common' ] = array(
				// Order By
				array(
					'label'	 => array(
						'text' => __( 'Sort by', 'content-views-query-and-display-post-page' ),
					),
					'params' => array(
						array(
							'type'		 => 'select',
							'name'		 => 'orderby',
							'options'	 => PT_CV_Values::post_regular_orderby(),
							'std'		 => '',
						),
					),
				),
				// Upgrade to Pro: More sort by options
				!get_option( 'pt_cv_version_pro' ) ? PT_CV_Settings::get_cvpro( 'proSort', __( 'Sort by drag & drop, custom field, random order, comment count', 'content-views-query-and-display-post-page' ) ) : '',
				// Order
				apply_filters( PT_CV_PREFIX_ . 'orders', array(
					'label'	 => array(
						'text' => __( 'Order' ),
					),
					'params' => array(
						array(
							'type'		 => 'radio',
							'name'		 => 'order',
							'options'	 => PT_CV_Values::orders(),
							'std'		 => 'asc',
						),
					),
				) ),
			);

			$result = apply_filters( PT_CV_PREFIX_ . 'orderby', $result );

			return $result;
		}

		/**
		 * Pagination settings
		 *
		 * @return array
		 */
		static function settings_pagination() {

			$prefix = 'pagination-';

			$result = array(
				// Pagination
				array(
					'label'			 => array(
						'text' => '',
					),
					'extra_setting'	 => array(
						'params' => array(
							'width' => 12,
						),
					),
					'params'		 => array(
						array(
							'type'		 => 'checkbox',
							'name'		 => 'enable-pagination',
							'options'	 => PT_CV_Values::yes_no( 'yes', __( 'Enable' ) ),
							'std'		 => '',
						),
					),
				),
				// Items per page
				apply_filters( PT_CV_PREFIX_ . 'ppp_settings', array(
					'label'			 => array(
						'text' => __( 'Items per page', 'content-views-query-and-display-post-page' ),
					),
					'extra_setting'	 => array(
						'params' => array(
							'wrap-class' => PT_CV_PREFIX . 'w200',
						),
					),
					'params'		 => array(
						array(
							'type'			 => 'number',
							'name'			 => $prefix . 'items-per-page',
							'std'			 => '5',
							'placeholder'	 => 'for example: 5',
							'desc'			 => sprintf( __( 'If value of the %s setting is not empty, this value should be smaller than that', 'content-views-query-and-display-post-page' ), sprintf( '<code>%s</code>', __( 'Limit', 'content-views-query-and-display-post-page' ) ) ),
						),
					),
					'dependence'	 => array( 'enable-pagination', 'yes' ),
				) ),
				// Pagination Type
				array(
					'label'		 => array(
						'text' => __( 'Type' ),
					),
					'params'	 => array(
						array(
							'type'		 => 'radio',
							'name'		 => $prefix . 'type',
							'options'	 => PT_CV_Values::pagination_types(),
							'std'		 => 'ajax',
						),
					),
					'dependence' => array( 'enable-pagination', 'yes' ),
				),
				// Pagination Style
				array(
					'label'			 => array(
						'text' => '',
					),
					'extra_setting'	 => array(
						'params' => array(
							'width' => 12,
						),
					),
					'params'		 => array(
						array(
							'type'	 => 'group',
							'params' => array(
								array(
									'label'		 => array(
										'text' => __( 'Style', 'content-views-query-and-display-post-page' ),
									),
									'params'	 => array(
										array(
											'type'		 => 'radio',
											'name'		 => $prefix . 'style',
											'options'	 => PT_CV_Values::pagination_styles(),
											'std'		 => PT_CV_Functions::array_get_first_key( PT_CV_Values::pagination_styles() ),
										),
									),
									'dependence' => array( $prefix . 'type', 'normal', '!=' ),
								),
								!get_option( 'pt_cv_version_pro' ) ? PT_CV_Settings::get_cvpro( 'pagingStyle', __( 'Advanced pagination styles: Infinite scrolling, Load more button', 'content-views-query-and-display-post-page' ) ) : '',
							),
						),
					),
					'dependence'	 => array( 'enable-pagination', 'yes' ),
				),
			);

			$result = apply_filters( PT_CV_PREFIX_ . 'settings_pagination', $result, $prefix );

			return $result;
		}

		/**
		 * Other settings for All View Type
		 */
		static function settings_other() {

			$prefix = 'other-';

			$result = array(
				// Open an item in
				array(
					'label'	 => array(
						'text' => __( 'Open item in', 'content-views-query-and-display-post-page' ),
					),
					'params' => array(
						array(
							'type'		 => 'radio',
							'name'		 => $prefix . 'open-in',
							'options'	 => PT_CV_Values::open_in(),
							'std'		 => PT_CV_Functions::array_get_first_key( PT_CV_Values::open_in() ),
							'desc'		 => __( 'How to open item when click on Title, Thumbnail, Read-more button', 'content-views-query-and-display-post-page' ),
						),
					),
				),
			);

			$result = apply_filters( PT_CV_PREFIX_ . 'settings_other', $result, $prefix );

			return $result;
		}

		/**
		 * Fields settings
		 */
		static function field_settings() {

			$prefix	 = 'field-';
			$prefix2 = 'show-' . $prefix;

			$result = array(
				// Fields display
				array(
					'label'			 => array(
						'text' => '',
					),
					'extra_setting'	 => array(
						'params' => array(
							'wrap-class' => PT_CV_Html::html_group_class(),
							'width'		 => 12,
						),
					),
					'params'		 => array(
						array(
							'type'	 => 'group',
							'params' => PT_CV_Settings::field_display_settings(),
						),
					),
				),
				// Upgrade to Pro: Drag & Drop
				!get_option( 'pt_cv_version_pro' ) ? PT_CV_Settings::get_cvpro( 'fieldsPosition', __( 'Show Custom Fields, show Title above Thumbnail ...', 'content-views-query-and-display-post-page' ), 12, 'margin-top: -10px; margin-bottom: 10px;' ) : '',
				// Title settings
				get_option( 'pt_cv_version_pro' ) ? apply_filters( PT_CV_PREFIX_ . 'settings_title_display', array(), $prefix, $prefix2 ) :
					array(
					'label'			 => array(
						'text' => __( 'Title' ),
					),
					'extra_setting'	 => array(
						'params' => array(
							'group-class'	 => PT_CV_PREFIX . 'field-setting',
							'wrap-class'	 => PT_CV_Html::html_group_class() . ' ' . PT_CV_PREFIX . 'title-setting',
						),
					),
					'params'		 => array(
						array(
							'type'	 => 'group',
							'params' => array(
								PT_CV_Settings::title_heading_tag( $prefix )
							),
						),
					),
					'dependence'	 => array( $prefix2 . 'title', 'yes' ),
					)
				,
				apply_filters( PT_CV_PREFIX_ . 'topmeta_settings', [], $prefix2 ),
				// Thumbnail settings
				array(
					'label'			 => array(
						'text' => __( 'Thumbnail' ),
					),
					'extra_setting'	 => array(
						'params' => array(
							'group-class'	 => PT_CV_PREFIX . 'field-setting',
							'wrap-class'	 => PT_CV_Html::html_group_class() . ' ' . PT_CV_PREFIX . 'thumbnail-setting' . ' ' . PT_CV_PREFIX . 'w50',
						),
					),
					'params'		 => array(
						array(
							'type'	 => 'group',
							'params' => PT_CV_Settings::field_thumbnail_settings( $prefix ),
						),
					),
					'dependence'	 => array( $prefix2 . 'thumbnail', 'yes' ),
				),
				// Content settings
				array(
					'label'			 => array(
						'text' => __( 'Content' ),
					),
					'extra_setting'	 => array(
						'params' => array(
							'group-class' => PT_CV_PREFIX . 'field-setting' . ' ' . PT_CV_PREFIX . 'content-setting',
						),
					),
					'params'		 => array(
						apply_filters( PT_CV_PREFIX_ . 'content_settings', array(
							'type'		 => 'radio',
							'name'		 => $prefix . 'content-show',
							'options'	 => PT_CV_Values::content_show(),
							'std'		 => 'excerpt',
						), $prefix ),
					),
					'dependence'	 => array( $prefix2 . 'content', 'yes' ),
				),
				// Full content (use same group class to use existing js + css)
				array(
					'label'			 => array(
						'text' => '',
					),
					'extra_setting'	 => array(
						'params' => array(
							'width'			 => 12,
							'group-class'	 => PT_CV_PREFIX . 'field-setting' . ' ' . PT_CV_PREFIX . 'excerpt-setting',
						),
					),
					'params'		 => array(
						array(
							'type'	 => 'group',
							'params' => array(
								array(
									'label'			 => array(
										'text' => '',
									),
									'extra_setting'	 => array(
										'params' => array(
											'width' => 12,
										),
									),
									'params'		 => array(
										array(
											'type'		 => 'checkbox',
											'name'		 => $prefix . 'content-skip-balance-tag',
											'options'	 => PT_CV_Values::yes_no( 'yes', __( 'Do not attempt to balance HTML tags in content', 'content-views-query-and-display-post-page' ) ),
											'std'		 => '',
											'desc'		 => __( 'Balancing tag prevents unmatched elements. But it does not work for all cases.<br> Check this option to show original content', 'content-views-query-and-display-post-page' ),
										),
									),
									'dependence'	 => array( $prefix . 'content-show', 'full' ),
								),
							),
						),
					),
					'dependence'	 => array( $prefix2 . 'content', 'yes' ),
				),
				// Excerpt settings
				array(
					'label'			 => array(
						'text' => '',
					),
					'extra_setting'	 => array(
						'params' => array(
							'width'			 => 12,
							'group-class'	 => PT_CV_PREFIX . 'field-setting' . ' ' . PT_CV_PREFIX . 'excerpt-setting',
							'wrap-id'		 => PT_CV_Html::html_group_id( 'excerpt-settings' ),
						),
					),
					'params'		 => array(
						array(
							'type'	 => 'group',
							'params' => array(
								array(
									'label'			 => array(
										'text' => '',
									),
									'extra_setting'	 => array(
										'params' => array(
											'width' => 12,
										),
									),
									'params'		 => array(
										array(
											'type'	 => 'group',
											'params' => apply_filters(
												PT_CV_PREFIX_ . 'excerpt_settings', array(
												// Excerpt length
												array(
													'label'	 => array(
														'text' => __( 'Excerpt settings', 'content-views-query-and-display-post-page' ),
													),
													'params' => array(
														array(
															'type'			 => 'number',
															'name'			 => $prefix . 'excerpt-length',
															'std'			 => '20',
															'placeholder'	 => 'for example: 20',
															'append_text'	 => 'words',
															'desc'			 => __( 'Generate excerpt by selecting the first X words of post content', 'content-views-query-and-display-post-page' ),
														),
													),
												),
												!get_option( 'pt_cv_version_pro' ) ? array(
													'label'	 => array(
														'text' => '',
													),
													'params' => array(
														array(
															'type'		 => 'checkbox',
															'name'		 => $prefix . 'excerpt-manual',
															'options'	 => PT_CV_Values::yes_no( 'yes', __( 'Use the manual excerpt', 'content-views-query-and-display-post-page' ) ),
															'std'		 => 'yes',
														),
													),
												) : '',
												// Allow HTML tags
												array(
													'label'	 => array(
														'text' => '',
													),
													'params' => array(
														array(
															'type'		 => 'checkbox',
															'name'		 => $prefix . 'excerpt-allow_html',
															'options'	 => PT_CV_Values::yes_no( 'yes', __( 'Allow HTML tags (a, br, strong, em, strike, i, ul, ol, li) in excerpt', 'content-views-query-and-display-post-page' ) ),
															'std'		 => '',
														),
													),
												),
												apply_filters( PT_CV_PREFIX_ . 'excerpt_extra_settings', [], $prefix ),
												// Read more text
												!get_option( 'pt_cv_version_pro' ) ? array(
													'label'	 => array(
														'text' => __( 'Read More', 'content-views-query-and-display-post-page' ),
													),
													'params' => array(
														array(
															'type'		 => 'checkbox',
															'name'		 => $prefix . 'excerpt-readmore',
															'options'	 => PT_CV_Values::yes_no( 'yes', __( 'Enable' ) ),
															'std'		 => 'yes',
														),
													),
													) : '',												
												!get_option( 'pt_cv_version_pro' ) ? array(
													'label'	 => array(
														'text' => '',
													),
													'params' => array(
														array(
															'type'	 => 'text',
															'name'	 => $prefix . 'excerpt-readmore-text',
															'std'	 => ucwords( rtrim( __( 'Read more...' ), '.' ) ),
															'desc'	 => sprintf( __( 'To change color of this button, <a href="%s" target="_blank"> please check this document </a>', 'content-views-query-and-display-post-page' ), 'http://docs.contentviewspro.com/change-color-read-more-button/?utm_source=client&utm_medium=read-more-color&utm_campaign=gopro' ),
														),
													),
													'dependence'	 => array( $prefix . 'excerpt-readmore', 'yes' ),
													) : '',
												!get_option( 'pt_cv_version_pro' ) ? apply_filters( PT_CV_PREFIX_ . 'readmore_extra_settings', [] ) : '',
												), $prefix . 'excerpt-'
											),
										),
									),
									'dependence'	 => array( $prefix . 'content-show', 'excerpt' ),
								),
							),
						),
					),
					'dependence'	 => array( $prefix2 . 'content', 'yes' ),
				),
				// Meta fields settings
				array(
					'label'			 => array(
						'text' => __( 'Meta fields', 'content-views-query-and-display-post-page' ),
					),
					'extra_setting'	 => array(
						'params' => array(
							'group-class'	 => PT_CV_PREFIX . 'field-setting' . ' ' . PT_CV_PREFIX . 'metafield-setting',
							'wrap-class'	 => PT_CV_Html::html_group_class() . ' ' . PT_CV_PREFIX . 'meta-fields-settings',
						),
					),
					'params'		 => array(
						array(
							'type'	 => 'group',
							'params' => PT_CV_Settings::field_meta_fields( 'meta-fields-' ),
							'desc'	 => apply_filters( PT_CV_PREFIX_ . 'settings_sort_text', '' ),
						),
					),
					'dependence'	 => array( $prefix2 . 'meta-fields', 'yes' ),
				),
				apply_filters( PT_CV_PREFIX_ . 'metafield_extra_settings', [] ),
				// Taxonomies settings
				apply_filters( PT_CV_PREFIX_ . 'settings_taxonomies_display', array(), 'meta-fields-' ),
			);

			$result = apply_filters( PT_CV_PREFIX_ . 'field_settings', $result, $prefix2 );

			return $result;
		}

		/**
		 * Fields display
		 *
		 * @return array
		 */
		static function field_display_settings() {

			$field_display_settings = array(
				array(
					'label'			 => array(
						'text' => '',
					),
					'extra_setting'	 => array(
						'params' => array(
							'width'		 => 12,
							'wrap-class' => PT_CV_PREFIX . 'field-display',
						),
					),
					'params'		 => array(
						array(
							'type'	 => 'group',
							'params' => PT_CV_Settings::field_display(),
							'desc'	 => apply_filters( PT_CV_PREFIX_ . 'settings_sort_text', '' ),
						),
					),
				),
			);

			$result = apply_filters( PT_CV_PREFIX_ . 'field_display_settings', $field_display_settings );

			return $result;
		}

		/**
		 * Options to check/uncheck to display fields
		 *
		 * @return array
		 */
		static function field_display() {

			$prefix = 'show-field-';

			$result = array(
				// Thumbnail position
				array(
					'label'			 => array(
						'text' => __( 'Thumbnail position', 'content-views-query-and-display-post-page' ),
					),
					'extra_setting'	 => array(
						'params' => array(
							'group-class'	 => PT_CV_PREFIX . 'thumb-position',
							'wrap-class'	 => PT_CV_PREFIX . 'w200',
						),
					),
					'params'		 => array(
						array(
							'type'		 => 'select',
							'name'		 => 'field-' . 'thumbnail-position',
							'options'	 => PT_CV_Values::thumbnail_position(),
							'std'		 => PT_CV_Functions::array_get_first_key( PT_CV_Values::thumbnail_position() ),
						),
					),
					'dependence'	 => apply_filters( PT_CV_PREFIX_ . 'thumbnail_position_depend', array( 'layout-format', '2-col' ) ),
				),
				apply_filters( PT_CV_PREFIX_ . 'thumbnail_position_extra', [] ),
				// Show Thumbnail
				array(
					'label'			 => array(
						'text' => '',
					),
					'extra_setting'	 => array(
						'params' => array(
							'width' => 12,
						),
					),
					'params'		 => array(
						array(
							'type'		 => 'checkbox',
							'name'		 => $prefix . 'thumbnail',
							'options'	 => PT_CV_Values::yes_no( 'yes', __( 'Show Thumbnail', 'content-views-query-and-display-post-page' ) ),
							'std'		 => 'yes',
						),
					),
				),
				// Show Title
				array(
					'label'			 => array(
						'text' => '',
					),
					'extra_setting'	 => array(
						'params' => array(
							'width' => 12,
						),
					),
					'params'		 => array(
						array(
							'type'		 => 'checkbox',
							'name'		 => $prefix . 'title',
							'options'	 => PT_CV_Values::yes_no( 'yes', __( 'Show Title', 'content-views-query-and-display-post-page' ) ),
							'std'		 => 'yes',
						),
					),
				),
				// Show Content
				array(
					'label'			 => array(
						'text' => '',
					),
					'extra_setting'	 => array(
						'params' => array(
							'width' => 12,
						),
					),
					'params'		 => array(
						array(
							'type'		 => 'checkbox',
							'name'		 => $prefix . 'content',
							'options'	 => PT_CV_Values::yes_no( 'yes', __( 'Show Content', 'content-views-query-and-display-post-page' ) ),
							'std'		 => 'yes',
						),
					),
				),
				// Show Meta fields
				array(
					'label'			 => array(
						'text' => '',
					),
					'extra_setting'	 => array(
						'params' => array(
							'width' => 12,
						),
					),
					'params'		 => array(
						array(
							'type'		 => 'checkbox',
							'name'		 => $prefix . 'meta-fields',
							'options'	 => PT_CV_Values::yes_no( 'yes', sprintf( '%s (%s, %s, %s, %s)', __( 'Show Meta Fields', 'content-views-query-and-display-post-page' ), __( 'Taxonomy', 'content-views-query-and-display-post-page' ), __( 'Author' ), __( 'Date' ), __( 'Comment' ) ) ),
							'std'		 => '',
						),
					),
				),
			);

			// Add/remove params
			$result = apply_filters( PT_CV_PREFIX_ . 'field_display', $result, $prefix );

			// Sort array of params by saved order
			$result = apply_filters( PT_CV_PREFIX_ . 'settings_sort', $result, PT_CV_PREFIX . $prefix );

			array_splice( $result, 2, 0, array( apply_filters( PT_CV_PREFIX_ . 'topmeta_show', [], $prefix ) ) );

			return $result;
		}

		/**
		 * Setting options for Field = Thumbnail
		 */
		static function field_thumbnail_settings( $prefix ) {

			$result = array(
				// Size
				array(
					'label'	 => array(
						'text' => __( 'Size' ),
					),
					'params' => array(
						array(
							'type'		 => 'select',
							'name'		 => $prefix . 'thumbnail-size',
							'options'	 => PT_CV_Values::field_thumbnail_sizes(),
							'std'		 => 'medium',
						),
					),
				),
				// Disable WP 4.4 responsive image
				!PT_CV_Functions::wp_version_compare( '4.4' ) ? '' :
					'disable-wp44-resimg' => array(
					'label'	 => array(
						'text' => '',
					),
					'params' => array(
						array(
							'type'		 => 'checkbox',
							'name'		 => $prefix . 'thumbnail-nowprpi',
							'options'	 => PT_CV_Values::yes_no( 'yes', __( 'Disable responsive image of WordPress', 'content-views-query-and-display-post-page' ) ),
							'std'		 => '',
							'desc'		 => __( 'If thumbnail looks blurry, check this option, and select a bigger size option above', 'content-views-query-and-display-post-page' ),
						),
					),
				),
			);

			$result = apply_filters( PT_CV_PREFIX_ . 'field_thumbnail_settings', $result, $prefix );

			return $result;
		}

		/**
		 * Show settings of other fields
		 */
		static function field_meta_fields( $prefix ) {

			$result = array(
				// Date
				array(
					'label'			 => array(
						'text' => '',
					),
					'extra_setting'	 => array(
						'params' => array(
							'width' => 12,
						),
					),
					'params'		 => array(
						array(
							'type'		 => 'checkbox',
							'name'		 => $prefix . 'date',
							'options'	 => PT_CV_Values::yes_no( 'yes', __( 'Show Date', 'content-views-query-and-display-post-page' ) ),
							'std'		 => 'yes',
						),
					),
				),
				// Author
				array(
					'label'			 => array(
						'text' => '',
					),
					'extra_setting'	 => array(
						'params' => array(
							'width' => 12,
						),
					),
					'params'		 => array(
						array(
							'type'		 => 'checkbox',
							'name'		 => $prefix . 'author',
							'options'	 => PT_CV_Values::yes_no( 'yes', __( 'Show Author', 'content-views-query-and-display-post-page' ) ),
							'std'		 => '',
						),
					),
				),
				// Taxonomy
				array(
					'label'			 => array(
						'text' => '',
					),
					'extra_setting'	 => array(
						'params' => array(
							'width' => 12,
						),
					),
					'params'		 => array(
						array(
							'type'		 => 'checkbox',
							'name'		 => $prefix . 'taxonomy',
							'options'	 => PT_CV_Values::yes_no( 'yes', sprintf( __( 'Show Taxonomies (%s, %s...)', 'content-views-query-and-display-post-page' ), __( 'Categories' ), __( 'Tags' ) ) ),
							'std'		 => 'yes',
						),
					),
				),
				// Comment
				array(
					'label'			 => array(
						'text' => '',
					),
					'extra_setting'	 => array(
						'params' => array(
							'width' => 12,
						),
					),
					'params'		 => array(
						array(
							'type'		 => 'checkbox',
							'name'		 => $prefix . 'comment',
							'options'	 => PT_CV_Values::yes_no( 'yes', __( 'Show Comment Count', 'content-views-query-and-display-post-page' ) ),
							'std'		 => '',
						),
					),
				),
			);

			// Sort array of params by saved order
			$result = apply_filters( PT_CV_PREFIX_ . 'settings_sort', $result, PT_CV_PREFIX . $prefix );

			return $result;
		}

		/**
		 * Settings of View Type = Grid
		 *
		 * @return array
		 */
		static function view_type_settings_grid() {

			$prefix = 'grid-';

			$result = array(
				// Number of columns
				array(
					'label'		 => array(
						'text' => __( 'Items per row', 'content-views-query-and-display-post-page' ),
					),
					'params'	 => array(
						array(
							'type'			 => 'number',
							'name'			 => $prefix . 'number-columns',
							'std'			 => '2',
							'append_text'	 => '1 &rarr; 12',
						),
					),
					'dependence' => array( 'view-type', 'grid' ),
				),
			);

			$result = apply_filters( PT_CV_PREFIX_ . 'view_type_settings_grid', $result );

			return $result;
		}

		/**
		 * Settings of View Type = Collapsible
		 *
		 * @return array
		 */
		static function view_type_settings_collapsible() {
			$prefix	 = 'collapsible-';
			$result	 = array(
				array(
					'label'			 => array(
						'text' => '',
					),
					'extra_setting'	 => array(
						'params' => array(
							'width' => 12,
						),
					),
					'params'		 => array(
						array(
							'type'		 => 'checkbox',
							'name'		 => $prefix . 'open-first-item',
							'options'	 => PT_CV_Values::yes_no( 'yes', __( 'Open the first item by default', 'content-views-query-and-display-post-page' ) ),
							'std'		 => 'yes',
						),
					),
				),
			);

			$result = apply_filters( PT_CV_PREFIX_ . 'view_type_settings_collapsible_184', $result );

			return $result;
		}

		/**
		 * Settings of View Type = Scrollable
		 *
		 * @return array
		 */
		static function view_type_settings_scrollable() {

			$prefix = 'scrollable-';

			$result = array(
				PT_CV_Settings::get_cvpro( 'scrollOptions', __( 'Increase columns and rows in each slide, automatically cycle', 'content-views-query-and-display-post-page' ), 12, 'margin-top: 5px; margin-bottom: -5px;' )
			);

			$result = apply_filters( PT_CV_PREFIX_ . 'view_type_settings_scrollable', $result );

			return $result;
		}

		/**
		 * Setting with no option
		 *
		 * @return array
		 */
		static function setting_no_option( $only_text = false ) {
			$msg	 = !get_option( 'pt_cv_version_pro' ) ? '' : __( 'There is no option', 'content-views-query-and-display-post-page' );
			$class	 = PT_CV_PREFIX . 'text cv-noop-profe';
			$text	 = "<div class='$class' style='color:#999'>$msg</div>";

			return $only_text ? $text : array(
				'label'			 => array(
					'text' => '',
				),
				'extra_setting'	 => array(
					'params' => array(
						'width' => 12,
					),
				),
				'params'		 => array(
					array(
						'type'		 => 'html',
						'content'	 => $text,
					),
				),
			);
		}

		/**
		 * Show Get CVPro
		 *
		 * @param string $text
		 * @param int $width
		 * @param string $style
		 * @param bool $notice
		 * @return string
		 */
		static function get_cvpro( $campaign, $text, $width = 10, $style = '', $notice = false ) {
			$url = sprintf( ' &raquo; <a href="%s" target="_blank">%s</a>', esc_url( "https://www.contentviewspro.com/?utm_source=client&utm_medium=view_fields&utm_campaign={$campaign}" ), __( 'get Pro version', 'content-views-query-and-display-post-page' ) );

			return array(
				'label'			 => array(
					'text' => '',
				),
				'extra_setting'	 => array(
					'params' => array(
						'width' => $width,
					),
				),
				'params'		 => array(
					array(
						'type'		 => 'html',
						'content'	 => $notice ?
							sprintf( '<div class="upgrade-notice cvgopro">&rarr; %s</div>', $text . $url . '.' ) :
							sprintf( '<p class="text-muted cvgopro" style="%s">&rarr; %s</p>', $style, $text . $url ),
					),
				),
			);
		}

		/**
		 * Adjust title heading tag
		 *
		 * @since 1.9.7
		 * @param string $prefix
		 * @return array
		 */
		static function title_heading_tag( $prefix ) {
			return array(
				'label'			 => array(
					'text' => __( 'HTML tag', 'content-views-query-and-display-post-page' ),
				),
				'extra_setting'	 => array(
					'params' => array(
						'wrap-class' => PT_CV_PREFIX . 'w200',
					),
				),
				'params'		 => array(
					array(
						'type'		 => 'select',
						'options'	 => PT_CV_Values::title_tag(),
						'name'		 => $prefix . 'title-tag',
						'std'		 => apply_filters( PT_CV_PREFIX_ . 'field_title_tag', 'h4' ),
					),
				),
			);
		}

		static function blank_settings() {
			return array(
				'label'	 => array(
					'text' => '',
				),
				'params' => array(
					array(
						'type'		 => 'html',
						'content'	 => '',
					),
				),
			);
		}

		static function prosetting_class( $value ) {
			$pro = get_option( 'pt_cv_version_pro' ) ? false : $value;
			return $pro ? array(
				'params' => array(
					'group-class' => PT_CV_PREFIX . 'ispro',
				),
			) : [];
		}

		// @since Hybrid
		static function view_type_settings_hybrid( $layout ) {
			if ( array_key_exists( $layout, PT_CV_Values::view_type_pro() ) ) {
				return array(
					self::blank_settings()
				);
			}

			$prefix	 = "$layout-";
			$columns = PT_CV_Values::column_layouts( $layout );

			$result = array(
				self::require_pro_version( $layout ),
				in_array( $layout, PT_CV_Values::fixed_ppp_layouts() ) ? array(
					'label'		 => array(
						'text' => '',
					),
					'extra_setting'	 => array(
						'params' => array(
							'width' => 12,
						),
					),
					'params'	 => array(
						array(
							'type'		 => 'html',
							'content'	 => sprintf( '<p class="text-muted">%s.</p>', __( 'This layout uses its own "Limit" and "Items Per Page" options', 'content-views-query-and-display-post-page' ) ),
						),
					),
				) : [],
				self::hybrid_variants( $prefix ),
				$columns ? array(
					'label'	 => array(
						'text' => __( 'Items per row', 'content-views-pro' ),
					),
					'params' => array(
						array(
							'type'			 => 'number',
							'name'			 => $prefix . 'number-columns',
							'std'			 => $columns,
							'append_text'	 => '1 &rarr; 12',
						),
					),
				) : [],
			);

			return $result;
		}

		static function require_pro_version( $layout ) {
			if ( !in_array( $layout, array_keys( PT_CV_Values::hybrid_layouts() ) ) ) {
				return [];
			}

			$require_pro = '6.0';
			$pro_version = get_option( 'pt_cv_version_pro' );
			$required	 = $pro_version && version_compare( $pro_version, $require_pro, '<' ) ? true : false;

			return $required ? array(
				'label'			 => array(
					'text' => '',
				),
				'extra_setting'	 => array(
					'params' => array(
						'width' => 12,
					),
				),
				'params'		 => array(
					array(
						'type'		 => 'html',
						'content'	 => sprintf( '<p><strong>%s</strong>.</p>', sprintf( __( 'This layout requires PRO version %s or higher', 'content-views-query-and-display-post-page' ), $require_pro ) ),
					),
				),
			) : [];
		}

		static function layout_shared_settings() {
			$extra = PT_CV_Settings::prosetting_class( true );

			$result = array(
				array(
					'label'	 => array(
						'text' => __( 'Gap', 'content-views-pro' ),
					),
					'params' => array(
						array(
							'type'			 => 'number',
							'name'			 => 'gridGap',
							'std'			 => '15',
							'append_text'	 => 'px',
						),
					),
					'dependence' => array( 'view-type', array_keys( PT_CV_Values::hybrid_layouts() ) ),
				),
				array(
					'label'			 => array(
						'text' => __( 'Row Height', 'content-views-pro' ),
					),
					'extra_setting'	 => $extra,
					'params'		 => array(
						array(
							'type'			 => 'number',
							'name'			 => 'hetargetHeight',
							'std'			 => '250',
							'append_text'	 => 'px',
						),
					),
					'dependence'	 => array( 'view-type', PT_CV_Values::ovl_layouts() ),
				),
				array(
					'label'	 => array(
						'text' => __( 'Swap Position', 'content-views-pro' ),
					),
					'params' => array(
						array(
							'type'		 => 'checkbox',
							'name'		 => 'swapPosition',
							'options'	 => PT_CV_Values::yes_no( 'yes', __( 'Enable', 'content-views-pro' ) ),
							'std'		 => '',
						),
					),
					'dependence' => array( 'view-type', 'onebig2' ),
				),
				array(
					'label'		 => array(
						'text' => __( 'Big Item Width', 'content-views-pro' ),
					),
					'params'	 => array(
						array(
							'type'		 => 'select',
							'name'		 => 'oneWidth',
							'options'	 => ContentViews_Block_OneBig2::one_width(),
							'std'		 => '50%',
						),
					),
					'dependence' => array( 'view-type', 'onebig2' ),
				),
				array(
					'label'		 => array(
						'text' => '',
					),
					'extra_setting'	 => array(
						'params' => array(
							'width' => 12,
						),
					),
					'params'	 => array(
						array(
							'type'	 => 'group',
							'params' => self::ovl_settings( $extra ),
						),
					),
					'dependence' => array( 'view-type', PT_CV_Values::ovl_layouts() ),
				)
			);

			return array(
				'label'	 => array(
					'text' => '',
				),
				'extra_setting'	 => array(
					'params' => array(
						'group-class' => PT_CV_PREFIX . 'shared-settings',
					),
				),
				'params' => array(
					array(
						'type'	 => 'group',
						'params' => $result,
					),
				),
			);
		}

		static function hybrid_variants( $prefix ) {
			$layout		 = trim( $prefix, '-' );
			$layout		 = PT_CV_Values::lname( $layout );
			$all		 = ContentViews_Block_Common::layout_variants();
			$variants	 = isset( $all[ $layout ] ) ? $all[ $layout ] : [];
			$options	 = [];
			foreach ( array_keys( $variants ) as $variant ) {
				$img				 = plugins_url( 'block/assets/layouts/', PT_CV_FILE ) . "$layout-$variant.svg";
				$options[ $variant ] = PT_CV_Settings::layout_radio( $layout, $variant, $img, '' );
			}

			return $options ? array(
				'label'	 => array(
					'text' => __( 'Variant', 'content-views-query-and-display-post-page' ),
				),
				'extra_setting'	 => array(
					'params' => array(
						'group-class' => PT_CV_PREFIX . 'variant-list',
					),
				),
				'params' => array(
					array(
						'type'		 => 'radio',
						'name'		 => $prefix . 'layout-variant',
						'options'	 => $options,
						'std'		 => 'layout1',
					),
				),
			) : [];
		}

		/**
		 * @param type $layout	layout
		 * @param type $variant	variant/false
		 * @param type $img		img url
		 * @param type $content content
		 * @return type
		 */
		static function layout_radio( $layout, $variant, $img, $content = '' ) {
			$badge = PT_CV_Values::isprlayout( $layout, $variant ) ? '<span class="pro-opt">PRO</span>' : '';
			return sprintf( '<div class="%s">%s<img src="%s" loading="lazy" >%s</div>', $badge ? 'pro-opt' : '', $badge, $img, $content );
		}

		static function ovl_settings( $extra ) {
			$depend = array( 'overlaid', 'yes' );

			return array(
				array(
					'label'			 => array(
						'text' => __( 'Mode', 'content-views-pro' ),
					),
					'extra_setting'	 => $extra,
					'params'		 => array(
						array(
							'type'		 => 'radio',
							'name'		 => 'overlaid',
							'options'	 => [
								'yes'	 => __( 'Overlay', 'content-views-query-and-display-post-page' ),
								''		 => __( 'Compound', 'content-views-query-and-display-post-page' ),
							],
							'std'		 => 'yes',
						),
					),
				),
				array(
					'label'		 => array(
						'text' => __( 'Text Position', 'content-views-pro' ),
					),
					'params'	 => array(
						array(
							'type'		 => 'select',
							'name'		 => 'overlayPosition',
							'options'	 => ContentViews_Block_Common::ovl_positions(),
							'std'		 => 'bottom',
						),
					),
					'dependence' => $depend,
				),
				array(
					'label'	 => array(
						'text' => __( 'Overlay Type', 'content-views-pro' ),
					),
					'extra_setting'	 => $extra,
					'params' => array(
						array(
							'type'		 => 'radio',
							'name'		 => 'overlayType',
							'options'	 => ContentViews_Block_Common::ovl_types(),
							'std'		 => 'simple',
						),
					),
					'dependence'	 => $depend,
				),
				array(
					'label'		 => array(
						'text' => __( 'Show Text', 'content-views-query-and-display-post-page' ),
					),
					'extra_setting'	 => $extra,
					'params'	 => array(
						array(
							'type'		 => 'radio',
							'name'		 => 'overOnHover',
							'options'	 => [
								''		 => __( 'Always', 'content-views-query-and-display-post-page' ),
								'yes'	 => __( 'On hover', 'content-views-query-and-display-post-page' ),
							],
							'std'		 => '',
						),
					),
					'dependence'	 => $depend,
				),
				array(
					'label'		 => array(
						'text' => __( 'Overlay Clickable', 'content-views-query-and-display-post-page' ),
					),
					'extra_setting'	 => $extra,
					'params'	 => array(
						array(
							'type'		 => 'checkbox',
							'name'		 => 'overlayClickable',
							'options'	 => PT_CV_Values::yes_no( 'yes', __( 'Enable' ) ),
							'std'		 => get_option( 'pt_cv_version_pro' ) ? 'yes' : '',
						),
					),
					'dependence'	 => $depend,
				),
			);
		}

		static function others_settings( $name, $text, $skip_label = false, $width = 10 ) {

			return array(
				'label'	 => array(
					'text' => $skip_label ? '' : $text,
				),
				'extra_setting'	 => array(
					'params' => array(
						'group-class'	 => PT_CV_PREFIX . 'others-settings',
						'width'			 => $width,
					)
				),
				'params' => array(
					array(
						'type'		 => 'checkbox',
						'name'		 => $name,
						'options'	 => PT_CV_Values::yes_no( 'yes', $skip_label ? $text : __( 'Enable' ) ),
						'std'		 => 'yes',
					),
				),
				'dependence' => array( 'view-type', PT_CV_Values::hasone_layouts() ),
			);
		}

		static function topmeta_show_settings( $args, $prefix ) {
			$args = array(
				'label'			 => array(
					'text' => '',
				),
				'extra_setting'	 => array(
					'params' => array(
						'group-class' => PT_CV_PREFIX . 'thumb-position' . ' ' . PT_CV_PREFIX . 'topmeta-enable',
						'width' => 12,
					),
				),
				'params'		 => array(
					array(
						'type'		 => 'checkbox',
						'name'		 => $prefix . 'taxoterm',
						'options'	 => PT_CV_Values::yes_no( 'yes', __( 'Show Top Meta', 'content-views-query-and-display-post-page' ) ),
						'std'		 => 'yes',
					),
				),
			);

			return $args;
		}

		static function topmeta_settings( $args, $prefix2 ) {

			$prefix = 'taxoterm';

			$extra = PT_CV_Settings::prosetting_class( true );

			$args = array(
				'label'			 => array(
					'text' => __( 'Top Meta' ),
				),
				'extra_setting'	 => array(
					'params' => array(
						'group-class' => PT_CV_PREFIX . 'field-setting',
					),
				),
				'params'		 => array(
					array(
						'type'	 => 'group',
						'params' => array(
							array(
								'label'	 => array(
									'text' => __( 'Select Meta', 'content-views-pro' ),
								),
								'extra_setting'	 => $extra,
								'params' => array(
									array(
										'type'		 => 'select',
										'name'		 => 'topmeta-which',
										'options'	 => ContentViews_Block_Common::topmeta_options(),
										'std'		 => '',
									),
								),
							),
							array(
								'label'	 => array(
									'text' => __( 'Taxonomy', 'content-views-pro' ),
								),
								'extra_setting'	 => $extra,
								'params' => array(
									array(
										'type'		 => 'select',
										'name'		 => 'taxo-which',
										'options'	 => PT_CV_Values::taxonomy_list(),
										'std'		 => '',
									),
								),
								'dependence' => array( 'topmeta-which', 'mtt_taxonomy' ),
							),
							array(
								'label'		 => array(
									'text' => __( 'Position', 'content-views-pro' ),
								),
								'params'	 => array(
									array(
										'type'		 => 'select',
										'name'		 => 'taxo-position',
										'options'	 => ContentViews_Block_Common::topmeta_positions(),
										'std'		 => 'above_title',
									),
								),
								'dependence' => array( 'view-type', 'collapsible', '!=' ),
							),
							PT_CV_Settings::others_settings( 'show-field-taxoterm-Others', __( 'For Other Posts', 'content-views-pro' ) ),
						),
					),
				),
				'dependence'	 => array( $prefix2 . $prefix, 'yes' ),
			);

			return $args;
		}

		static function content_settings( $args, $prefix ) {
			return array(
				'type'	 => 'group',
				'params' => array(
					array(
						'label'			 => array(
							'text' => '',
						),
						'extra_setting'	 => array(
							'params' => array(
								'width' => 12,
							),
						),
						'params'		 => array(
							$args
						),
					),
					PT_CV_Settings::others_settings( 'show-field-content-Others', __( 'For Other Posts', 'content-views-pro' ) ),
				),
			);
		}

		static function excerpt_extra_settings( $args, $prefix ) {
			return array(
				'label'	 => array(
					'text' => '',
				),
				'params' => array(
					array(
						'type'			 => 'number',
						'name'			 => 'excerptLengthOthers',
						'std'			 => '15',
						'placeholder'	 => '',
						'append_text'	 => 'words',
						'desc'			 => __( 'Excerpt length for other posts', 'content-views-query-and-display-post-page' ),
					),
				),
				'dependence' => array( 'view-type', PT_CV_Values::hasone_layouts() ),
			);
		}

		static function readmore_extra_settings( $args ) {
			return PT_CV_Settings::others_settings( 'show-field-readmore-Others', __( 'Enable For Other Posts', 'content-views-pro' ), true, get_option( 'pt_cv_version_pro' ) ? 12 : 10 );
		}

		static function metafield_extra_settings( $args ) {
			$args = array(
				'label'			 => array(
					'text' => '',
				),
				'extra_setting'	 => array(
					'params' => array(
						'group-class' => PT_CV_PREFIX . 'field-setting' . ' ' . PT_CV_PREFIX . 'metafield-extra1' . ' ' . (!get_option( 'pt_cv_version_pro' ) ? PT_CV_PREFIX . 'inlite' : ''),
					),
				),
				'params'		 => array(
					array(
						'type'	 => 'group',
						'params' => array(
							PT_CV_Settings::others_settings( 'show-field-meta-fields-Others', __( 'For Other Posts', 'content-views-pro' ) )
						),
					),
				),
				'dependence'	 => array( 'show-field-' . 'meta-fields', 'yes' ),
			);

			return $args;
		}

		static function thumb_wh_default( $layout, $key ) {
			if ( !empty( $GLOBALS[ 'contentviews_blocks' ][ $layout ]->custom_attributes[ $key ][ 'default' ] ) ) {
				$value = (array) $GLOBALS[ 'contentviews_blocks' ][ $layout ]->custom_attributes[ $key ][ 'default' ];
				return isset( $value[ 'md' ] ) ? $value[ 'md' ] : '';
			}
			return '';
		}

		static function thumbnail_extra_settings( $args, $prefix ) {
			// Width, Height
			$units			 = [ 'px', 'em', 'rem', '%', 'vw', 'vh' ];
			$idx			 = 0;
			$layouts_with_wh = array_values( array_diff( array_keys( PT_CV_Values::hybrid_layouts() ), PT_CV_Values::ovl_layouts() ) );
			foreach ( $layouts_with_wh as $layout ) {
				$with_sm = in_array( $layout, [ 'onebig1', 'onebig2' ] ) ? true : false;
				foreach ( PT_CV_BlockToView::thumb_wh( $with_sm ) as $ar1 ) {
					$inserted = array(
						'label'			 => array(
							'text' => $ar1[ 0 ],
						),
						'extra_setting'	 => array(
							'params' => array(
								'group-class' => PT_CV_PREFIX . 'size-group',
							),
						),
						'params'		 => array(
							array(
								'type'	 => 'number',
								'name'	 => "$layout-" . $ar1[ 1 ],
								'std'	 => self::thumb_wh_default( $layout, $ar1[ 1 ] ),
							),
							array(
								'type'		 => 'select',
								'name'		 => "$layout-" . $ar1[ 2 ],
								'options'	 => array_combine( $units, $units ),
								'std'		 => self::thumb_wh_default( $layout, $ar1[ 2 ] ),
							),
						),
						'dependence'	 => array( 'view-type', $layout ),
					);

					$inserted = array(
						'label'			 => array(
							'text' => '',
						),
						'extra_setting'	 => array(
							'params' => array(
								'group-class' => PT_CV_PREFIX . 'group-depend',
							),
						),
						'params'		 => array(
							array(
								'type'	 => 'group',
								'params' => array(
									$inserted
								),
							),
						),
						'dependence'	 => array( 'field-thumbnail-size', PT_CV_PREFIX . 'custom', '!=' ),
					);

					array_splice( $args, 1 + $idx++, 0, array( $inserted ) );
				}
			}

			return $args;
		}

		static function thumbnail_bottom_settings( $args, $prefix ) {
			$extra = PT_CV_Settings::prosetting_class( true );

			// Effect
			$args[] = array(
				'label'		 => array(
					'text' => __( 'Hover Effect' ),
				),
				'extra_setting'	 => $extra,
				'params'	 => array(
					array(
						'type'		 => 'select',
						'name'		 => 'thumbnailEffect',
						'options'	 => ContentViews_Block_Common::thumbnail_effects(),
						'std'		 => '',
					),
				),
				//'dependence' => array( 'view-type', array_keys( PT_CV_Values::hybrid_layouts() ) ),
			);

			if ( !get_option( 'pt_cv_version_pro' ) ) {
				$args[] = array(
					'label'			 => array(
						'text' => __( 'Substitute Option' ),
					),
					'extra_setting'	 => $extra,
					'params'		 => array(
						array(
							'type'		 => 'select',
							'name'		 => $prefix . 'thumbnail-subtmp',
							'options'	 => ContentViews_Block_Common::img_sub_options(),
							'std'		 => 'image',
						),
					),
				);
				$args[]	 = PT_CV_Settings::get_cvpro( 'subImg', __( 'If no featured image is added to a post, show the substitute option as thumbnail', 'content-views-query-and-display-post-page' ) );

				$args[]	 = array(
					'label'			 => array(
						'text' => __( 'Lazy load' ),
					),
					'extra_setting'	 => $extra,
					'params'		 => array(
						array(
							'type'		 => 'checkbox',
							'name'		 => $prefix . 'thumbnail-lazytmp',
							'options'	 => PT_CV_Values::yes_no( 'yes', __( 'Enable' ) ),
							'std'		 => '',
						),
					),
				);
				$args[]	 = PT_CV_Settings::get_cvpro( 'lazyImg', __( 'Defer loading of images until they are needed, to improve page load time', 'content-views-query-and-display-post-page' ) );
			}

			return $args;
		}

		static function thumbnail_position_extra_settings( $args ) {

			$args = array(
				'label'			 => array(
					'text' => __( 'Thumbnail position (for other posts)', 'content-views-query-and-display-post-page' ),
				),
				'extra_setting'	 => array(
					'params' => array(
						'group-class'	 => PT_CV_PREFIX . 'thumb-position',
						'wrap-class'	 => PT_CV_PREFIX . 'w200',
					),
				),
				'params'		 => array(
					array(
						'type'		 => 'select',
						'name'		 => 'thumbPositionOthers',
						'options'	 => PT_CV_Values::thumbnail_position(),
						'std'		 => 'left',
					),
				),
				'dependence'	 => array( 'view-type', [ 'onebig1' ] ),
			);

			return $args;
		}

		static function ppp_settings( $args ) {
			$args = array(
				'label'			 => array(
					'text' => '',
				),
				'extra_setting'	 => array(
					'params' => array(
						'width' => 12,
					),
				),
				'params'		 => array(
					array(
						'type'	 => 'group',
						'params' => array(
							$args
						),
					),
				),
				'dependence'	 => array( 'view-type', PT_CV_Values::fixed_ppp_layouts(), '!=' ),
			);

			return $args;
		}

	}

}