<?php
namespace Thim_EL_Kit\Elementor;

use LearnPress;
use Thim_EL_Kit\SingletonTrait;
use Thim_EL_Kit\Custom_Post_Type;
use Thim_EL_Kit\Settings;
use Thim_EL_Kit\Modules\SinglePost\Init as SinglePost;
use Thim_EL_Kit\Modules\ArchivePost\Init as ArchivePost;
use Thim_EL_Kit\Modules\ArchiveProduct\Init as ArchiveProduct;
use Thim_EL_Kit\Modules\SingleProduct\Init as SingleProduct;
use Thim_EL_Kit\Modules\ArchiveCourse\Init as ArchiveCourse;
use Thim_EL_Kit\Modules\SingleCourse\Init as SingleCourse;
use Thim_EL_Kit\Modules\SingleCourseItem\Init as SingleCourseItem;

class Widgets {

	use SingletonTrait;

	const WIDGETS = array(
		'global'             => array(
			'nav-menu',
			'site-logo',
			'product-base',
			// 'course-base', // Delete because it error in ajax. Need include in once when use in widget - Nhamdv.
			'header-info',
			'social',
			'social-share',
			'minicart',
			'blog-base',
			'list-blog',
			'list-course',
			 'list-product',
			'team',
			'testimonial',
			'back-to-top',
			'contact-form-7',
			'breadcrumb',
			'search-form',
			'categories',
			'image-accordion',
			'heading',
			'button',
			'tab',
			'accordion',
			'slider',
			'icon-box',
			'login-popup',
			'login-form',
			'instagram',
			'video',
			'page-title',
			'dark-mode',
			'archive-description'
		),
		'archive-post'       => array( 'archive-post' ),
		'single-post'        => array( 'post-title', 'post-content', 'post-image', 'author-box', 'post-comment', 'post-navigation', 'post-info', 'post-related' ,'reading-time-post'),
		'archive-product'    => array( 'archive-product' ),
		'single-product'     => array(
			'product-title',
			'product-image',
			'product-price',
			'product-add-to-cart',
			'product-rating',
 			'product-notices',
			'product-meta',
			'product-short-description',
			'product-content',
			'product-tabs',
			'product-additional-information',
			'product-related',
			'product-upsell',
		),
		'archive-course'     => array( 'archive-course' ),
		'single-course'      => array(
			'course-title',
			'course-instructor',
			'course-meta',
			'course-category',
			'course-tags',
			'course-image',
			'course-price',
			'course-graduation',
			'course-user-time',
			'course-user-progress',
			'course-tabs',
			'course-extra',
			'course-buttons',
			'course-related',
			'course-rating',
			'course-description',
			'course-offer-end',
			'course-comment',
		),
		'single-course-item' => array(
			'course-item-section',
			// 'course-title',
			'course-item-progress',
			'course-item-search-form',
			'course-item-data',
			'course-curriculum',
			'course-item-nav',
			'course-close-sidebar',
			'back-to-course',
		),
		'loop-item'          => array(
			'loop-item-title',
			'loop-item-excerpt',
			'loop-item-featured-image',
			'loop-item-read-more',
			'loop-item-info',
			'loop-product-button',
			'loop-product-countdown',
			'loop-product-price',
			'loop-product-ratting',
			'loop-product-stock',
			'loop-product-sale',
		),
	);

	public function widgets() {
		// Include Elementor widget in here.
		$widgets = self::WIDGETS;

		global $post;

		// Only register archive-post, post-title in Elementor Editor only template.
		if ( $post && \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
			$type = get_post_meta( $post->ID, Custom_Post_Type::TYPE, true );

			foreach ( $widgets as $key => $widget ) {
				if ( $key === 'archive-post' && $type !== ArchivePost::instance()->tab ) {
					unset( $widgets['archive-post'] );
				}

				if ( $key === 'single-post' && $type !== SinglePost::instance()->tab ) {
					unset( $widgets['single-post'] );
				}

				if ( $key === 'archive-product' && ( ! class_exists( 'WooCommerce' ) || $type !== ArchiveProduct::instance()->tab ) ) {
					unset( $widgets['archive-product'] );
				}

				if ( $key === 'single-product' && ( ! class_exists( 'WooCommerce' ) || $type !== SingleProduct::instance()->tab ) ) {
					unset( $widgets['single-product'] );
				}

				if ( $key === 'archive-course' && ( ! class_exists( 'LearnPress' ) || $type !== ArchiveCourse::instance()->tab ) ) {
					unset( $widgets['archive-course'] );
				}

				if ( $key === 'single-course' && ( ! class_exists( 'LearnPress' ) || $type !== SingleCourse::instance()->tab ) ) {
					unset( $widgets['single-course'] );
				}

				if ( $key === 'single-course-item' && ( ! class_exists( 'LearnPress' ) || $type !== SingleCourseItem::instance()->tab ) ) {
					unset( $widgets['single-course-item'] );
				}
			}
		}

		if ( ! class_exists( 'WooCommerce' ) ) {
			unset( $widgets['archive-product'] );
			unset( $widgets['single-product'] );
			unset( $widgets['global'][array_search( 'minicart', $widgets['global'] )] );
			unset( $widgets['global'][array_search( 'list-product', $widgets['global'] )] );
			unset( $widgets['loop-item'][array_search( 'loop-product-button', $widgets['loop-item'] )] );
			unset( $widgets['loop-item'][array_search( 'loop-product-countdown', $widgets['loop-item'] )] );
			unset( $widgets['loop-item'][array_search( 'loop-product-price', $widgets['loop-item'] )] );
			unset( $widgets['loop-item'][array_search( 'loop-product-ratting', $widgets['loop-item'] )] );
			unset( $widgets['loop-item'][array_search( 'loop-product-stock', $widgets['loop-item'] )] );
			unset( $widgets['loop-item'][array_search( 'loop-product-sale', $widgets['loop-item'] )] );
		}

		if ( ! class_exists( 'LearnPress' ) ) {
			unset( $widgets['archive-course'] );
			unset( $widgets['single-course'] );
			unset( $widgets['single-course-item'] );

			unset( $widgets['global'][ array_search( 'list-course', $widgets['global'] ) ] );
		}
		if ( ! class_exists( 'LP_Addon_Course_Review_Preload' ) && ! empty( $widgets['single-course'] ) ) {
			unset( $widgets['single-course'][ array_search( 'course-rating', $widgets['single-course'] ) ] );
		}
		if ( ! class_exists( 'WPCF7' ) ) {
			unset( $widgets['global'][ array_search( 'contact-form-7', $widgets['global'] ) ] );
		}

		$widgets = apply_filters( 'thim_ekit/elementor/widgets/list', $widgets );

		return $widgets;
	}

	public function register_widgets( $widgets_manager ) {
		$widgets_all = $this->widgets();

		if ( ! empty( $widgets_all ) ) {
			foreach ( $widgets_all as $base => $widgets ) {
				if ( ! empty( $widgets ) ) {
					foreach ( $widgets as $widget ) {
						$class = $this->register_widget_class( $base, $widget );
						$class = apply_filters( 'thim_ekit/elementor/widget/register_widget_class', $class, $widget );

						if ( class_exists( $class ) && ! Settings::instance()->disable_widgets_settings( $widget ) ) {
							$widgets_manager->register( new $class() );
						}
					}
				}
			}
		}
	}

	public function register_widget_class( $base, $widget ) {
		$class = ucwords( str_replace( '-', ' ', $widget ) );
		$class = str_replace( ' ', '_', $class );
		$class = sprintf( '\Elementor\Thim_Ekit_Widget_%s', $class );

		if ( ! class_exists( $class ) ) {
			if ( class_exists( 'LearnPress' ) ) {
				$lp_version  = LearnPress::instance()->version;
				$widgets_new = [
					'archive-course'
				];
				if ( version_compare( $lp_version, '4.2.6-beta-0', '>=' ) && in_array( $widget, $widgets_new ) ) {
					$widget .= '-new';
				}
			}
			$widget_file_url = $widget . '.php';

			$file_function = function( $file_url ) use ( $base, $widget ) {
				$file       = apply_filters( 'thim_ekit/elementor/widget/file_path', THIM_EKIT_PLUGIN_PATH . 'inc/elementor/widgets/' . $base . '/' . $file_url, $widget );
				$file_theme = locate_template( 'thim-elementor-kit/' . $file_url );

				if ( file_exists( $file_theme ) ) {
					$file = $file_theme;
				}

				return $file;
			};

			if ( file_exists( $file_function( $widget . '/' . $widget_file_url ) ) ) {
				$file = $file_function( $widget . '/' . $widget_file_url );
			} else {
				$file = $file_function( $widget_file_url );
			}

			if ( file_exists( $file ) ) {
				include_once $file;
			}
		}

		return $class;
	}
}
