<?php
/**
 * @author  CodeFlavors
 * @project vimeotheque-templates
 */

namespace Vimeotheque\Templates;

use Vimeotheque\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class Template_Loader {

	public function __construct(){
		add_filter(
			'template_include',
			function( $template ){
				if ( is_embed() ) {
					return $template;
				}

				$default_file = $this->get_template_default_file();
				if( $default_file ){

					$search_files = $this->get_template_loader_files( $default_file );
					$template     = locate_template( $search_files );

					if( !$template || VIMEOTHEQUE_TEMPLATE_DEBUG_MODE ){
						if( false !== strpos( $default_file, Plugin::instance()->get_cpt()->get_tag_tax() ) ){
							$cs_template = str_replace( '_', '-', $default_file );
							$template = \Vimeotheque\Helper::get_path() . '/templates/' . $cs_template;
						}else{
							$template = \Vimeotheque\Helper::get_path() . '/templates/' . $default_file;
						}
					}
				}

				return $template;
			}
		);
	}

	/**
	 * Get the default filename for a template.
	 *
	 * @return string
	 */
	private function get_template_default_file(){
		if( is_singular( Plugin::instance()->get_cpt()->get_post_type() ) ){
			$default_file = 'single-'. Plugin::instance()->get_cpt()->get_post_type() .'.php';
		}elseif ( Helper::is_video_taxonomy() ){
			$object = get_queried_object();

			if( is_tax( Plugin::instance()->get_cpt()->get_post_tax() ) || is_tax( Plugin::instance()->get_cpt()->get_tag_tax() ) ){
				$default_file = 'taxonomy-' . $object->taxonomy . '.php';
			}else{
				$default_file = '';
			}
		}elseif( is_post_type_archive() ){
			$pt = get_query_var( 'post_type' );
			if( !is_array( $pt ) ){
				$pt = [$pt];
			}

			if( in_array( Plugin::instance()->get_cpt()->get_post_type(), $pt ) ) {
				$default_file = 'archive-'
				                . Plugin::instance()->get_cpt()->get_post_type()
				                . '.php';
			}else{
				$default_file = '';
			}
		}else{
			$default_file = '';
		}

		return $default_file;
	}

	private function get_template_loader_files( $default_file ) {

		$templates = [];

		if( is_singular( Plugin::instance()->get_cpt()->get_post_type() ) ){
			$object = get_queried_object();
			$name_decoded = urldecode( $object->post_name );
			if( $name_decoded != $object->post_name ){
				$templates[] = sprintf(
					'single-%s-%s.php',
					Plugin::instance()->get_cpt()->get_post_type(),
					$name_decoded
				);
			}
			$templates[] = sprintf(
				'single-%s-%s.php',
				Plugin::instance()->get_cpt()->get_post_type(),
				$object->post_name
			);
		}

		if( Helper::is_video_taxonomy() ){
			$object = get_queried_object();

			$templates[] = 'taxonomy-' . $object->taxonomy . '-' . $object->slug . '.php';
			$templates[] = Helper::template_path() . 'taxonomy-' . $object->taxonomy . '-' . $object->slug . '.php';
			$templates[] = 'taxonomy-' . $object->taxonomy . '.php';
			$templates[] = Helper::template_path() . 'taxonomy-' . $object->taxonomy . '.php';

			if( is_tax( Plugin::instance()->get_cpt()->get_tag_tax() ) ){
				$cs_taxonomy = str_replace( '_', '-', $object->taxonomy );
				$cs_default  = str_replace( '_', '-', $default_file );
				$templates[] = 'taxonomy-' . $object->taxonomy . '-' . $object->slug . '.php';
				$templates[] = Helper::template_path() . 'taxonomy-' . $cs_taxonomy . '-' . $object->slug . '.php';
				$templates[] = 'taxonomy-' . $object->taxonomy . '.php';
				$templates[] = Helper::template_path() . 'taxonomy-' . $cs_taxonomy . '.php';
				$templates[] = $cs_default;
			}
		}

		$templates[] = $default_file;
		if ( isset( $cs_default ) ) {
			$templates[] = Helper::template_path() . $cs_default;
		}
		$templates[] = Helper::template_path() . $default_file;

		return array_unique( $templates );
	}

}