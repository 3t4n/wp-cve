<?php

namespace QT_Importer;

class Single_Post {

	private $wpdb;
	private $qt_default_language;
	private $qt_active_languages;
	private $qt_url_mode;
	private $utils;
	static $all_languages;

	public function __construct( $qt_default_language, $qt_active_languages, $qt_url_mode ) {
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->utils = new Utils();
		$this->qt_default_language = $qt_default_language;
		$this->qt_active_languages = $qt_active_languages;
		$this->qt_url_mode = $qt_url_mode;
	}

	public function process_post( $post_id ) {
		global $sitepress, $sitepress_settings;

		if ( get_post_meta( $post_id, '_qt_imported', true ) ) return; // already imported

		$post = get_post( $post_id, ARRAY_A );

		$translatable_tax = $sitepress->get_translatable_taxonomies( true, $post['post_type'] );

		if ( $post ) {

			$posts_to_create = [];

			$post['post_title'] = $this->replace_legacies_in_post_element( $post['post_title'] );
			$posts_to_create = $this->add_post_to_posts_to_create( $posts_to_create, $post, 'post_title' );

			$post['post_content'] = $this->replace_legacies_in_post_element( $post['post_content'] );
			$posts_to_create = $this->add_post_to_posts_to_create( $posts_to_create, $post, 'post_content' );

			$post['post_excerpt'] = $this->replace_legacies_in_post_element( $post['post_excerpt'] );
			$posts_to_create = $this->add_post_to_posts_to_create( $posts_to_create, $post, 'post_excerpt' );

			$posts_to_create = $this->add_all_cf_to_posts_to_create( $posts_to_create, $post_id );

			$posts_to_create = $this->handle_empty_titles( $posts_to_create, $post );
			$posts_to_create = $this->mark_post_in_default_language( $posts_to_create );
			$posts_to_create = $this->copy_post_status( $posts_to_create, $post );

			foreach ( $this->get_all_languages() as $language ) {

				if ( empty( $posts_to_create[ $language ]['post_title'] ) ) {
					continue;
				} // obslt

				$_POST['post_title'] = $post['post_title']   = $posts_to_create[ $language ]['post_title'];
				$post['post_content'] = isset( $posts_to_create[ $language ]['post_content'] ) ? $posts_to_create[ $language ]['post_content'] : '';
				if ( isset( $posts_to_create[ $language ]['post_excerpt'] ) ) {
					$post['post_excerpt'] = $posts_to_create[ $language ]['post_excerpt'];
				}
				$_POST['icl_post_language'] = $this->utils->_lang_map( $language );

				$this->remove_icl_post_actions();

				if ( ! empty( $posts_to_create[ $language ]['__icl_source'] ) ) { // is it post in original language

					$trid = $sitepress->get_element_trid( $post['ID'], 'post' . $post['post_type'] );
					if ( is_null( $trid ) ) {
						$sitepress->set_element_language_details( $post['ID'], 'post_' . $post['post_type'], null, $this->utils->_lang_map( $this->qt_default_language ) );
					}

					$id = wp_update_post( $post );
					update_post_meta( $post['ID'], '_qt_imported', 'original' );
					$post_imported = true;
				} else {
					$_POST['icl_translation_of'] = $post['ID'];
					$post_copy                   = $post;

					unset( $post_copy['ID'], $post_copy['post_name'], $post_copy['post_parent'],
						$post_copy['guid'], $post_copy['comment_count'], $post_copy['ancestors'] );

					$iclsettings['sync_page_parent'] = 0;

					if ( ! in_array( $post['post_type'], array(
						'post',
						'page'
					) ) ) {
						$iclsettings['custom_posts_sync_option'][ $post['post_type'] ] = 1;
					}

					$sitepress->save_settings( $iclsettings );

					$current_language = apply_filters( 'wpml_current_language', null );
					do_action( 'wpml_switch_language', $this->utils->_lang_map( $language ) );

					$id = wp_insert_post( $post_copy );

					do_action( 'wpml_switch_language', $current_language );

					if ( isset( $sitepress_settings['sync_page_parent'] ) ) {
						$iclsettings['sync_page_parent'] = $sitepress_settings['sync_page_parent'];
					}
					$sitepress->save_settings( $iclsettings );

					update_post_meta( $id, '_qt_imported', 'from-' . $post['ID'] );
					$post_imported = true;

					unset( $_POST['icl_translation_of'], $_POST['post_title'], $_POST['icl_post_language'] );

					// fix terms
					foreach ( $translatable_tax as $tax ) {
						$terms = wp_get_object_terms( $post['ID'], $tax );

						if ( $terms ) {
							$translated_terms = array();
							foreach ( $terms as $term ) {
								$translated_term = icl_object_id( $term->term_id, $tax, false, $this->utils->_lang_map( $language ) );
								if ( $translated_term ) {
									$translated_terms[] = intval( $translated_term );
								}
							}

							wp_set_object_terms( $id, $translated_terms, $tax, false );
						}
					}

					if ( $post['post_status'] == 'publish' ) {
						$_qt_redirects_map = get_option( '_qt_redirects_map' );

						$original_url = get_permalink( $post['ID'] );
						if ( $this->qt_url_mode == 1 ) {
							$glue         = false === strpos( $original_url, '?' ) ? '?' : '&';
							$original_url .= $glue . 'lang=' . $language;
						} elseif ( $this->qt_url_mode == 2 ) {
							$original_url = str_replace( home_url(), rtrim( home_url(), '/' ) . '/' . $language, $original_url );
						} elseif ( $this->qt_url_mode == 2 ) {
							$parts        = parse_url( home_url() );
							$original_url = str_replace( $parts['host'], $language . '.' . $parts['host'], $original_url );
						}

						$_qt_redirects_map[ $original_url ] = get_permalink( $id );
						update_option( '_qt_redirects_map', $_qt_redirects_map );
					}


				}

				if ( ! empty( $posts_to_create[ $language ]['custom_fields'] ) ) {
					foreach ( $posts_to_create[ $language ]['custom_fields'] as $k => $v ) {
						update_post_meta( $id, $k, $v );
					}
				}

			}

			if ( ! isset( $post_imported ) || ! $post_imported ) {
				update_post_meta( $post['ID'], '_qt_imported', 'import_failed' );
			}

			// handle comments
			$comments = $this->wpdb->get_col( $this->wpdb->prepare( "SELECT comment_ID FROM {$this->wpdb->comments} WHERE comment_post_ID = %d", $post['ID'] ) );
			if ( $comments ) {
				foreach ( $comments as $comment_id ) {
					$sitepress->set_element_language_details( $comment_id, 'comment', null, $this->utils->_lang_map( $this->qt_default_language ) );
				}
			}
		}
	}

	private function replace_legacies_in_post_element( $post_element ) {
		$post_element = preg_replace_callback( '#<!--:--><!--:([a-zA-Z]{2})-->#', function ( $found ) {
			return '[:' . strtolower( $found[1] ) . ']';
		}, $post_element ); // replace middle legacy syntax <!--:--><!--:en--> into [:en]
		$post_element = str_replace( '<!--:-->', '[:]', $post_element ); // replace end legacy syntax <!--:--> into [:]
		$post_element = preg_replace_callback( '#<!--:([a-zA-Z]{2})-->#', function ( $found ) {
			return '[:' . strtolower( $found[1] ) . ']';
		}, $post_element ); // replace start legacy syntax <!--:en--> into [:en]
		if ( false !== strrpos( $post_element, '[:]' ) && 3 == strlen( $post_element ) - strrpos( $post_element, '[:]' ) ) {
			// remove last [:] but remember it exists only if string is translated
			$post_element = substr( $post_element, 0, strlen( $post_element ) - 3 );
		}
		return $post_element;
	}

	private function add_post_to_posts_to_create( $posts_to_create, $post, $element_type ) {
		$elements_by_language = preg_split( '#\[:([a-z]{2})\]#i', $post[ $element_type ] );
		array_shift( $elements_by_language );
		preg_match_all( '#\[:([a-z]{2})\]#i', $post[ $element_type ], $matches );
		$languages = array_map( 'strtolower', $matches['1'] );
		
		foreach ( $elements_by_language as $key => $element ) {
			$posts_to_create[ $languages[ $key ] ][ $element_type ] = $element; // @todo check "PHP Notice:  Undefined offset: 0 ...", I guess $key is 0
			if ( 'post_content' === $element_type && $key === 0 && count( $elements_by_language ) > 2 ) { // if post has <!--more--> tag, add this tag to first language as well
				$posts_to_create[ $languages[ $key ] ][ $element_type ] .= "<!--more-->"; // @todo adds this incorrectly now, to every post which has more than 2 languages
			}
		};

		return $posts_to_create;
	}

	private function add_custom_field_to_posts_to_create( $posts_to_create, $cf ) {
		$cf['meta_value'] = $this->replace_legacies_in_post_element( $cf['meta_value'] );
		$elements_by_language = preg_split( '#\[:([a-z]{2})\]#i', $cf['meta_value'] );
		array_shift( $elements_by_language );
		preg_match_all( '#\[:([a-z]{2})\]#i', $cf['meta_value'], $matches );
		$languages = array_map( 'strtolower', $matches['1'] );
		
		foreach ( $elements_by_language as $key => $element ) {
			if ( isset( $matches[2] ) ) {
				$posts_to_create[ $languages[ $key ] ]['custom_fields'][ $cf['meta_key'] ] = $matches[2];
			}
		}

		return $posts_to_create;
	}

	private function add_serialized_custom_field_to_posts_to_create( $posts_to_create, $cf ) {
		foreach ( $this->qt_active_languages as $lang ) {
			if ( $this->qt_default_language != $lang ) {
				$posts_to_create[ $lang ]['custom_fields'][ $cf['meta_key'] ] = $cf['meta_value'];
			}
		}
		return $posts_to_create;
	}

	private function get_custom_fields( $post_id ) {
		return $this->wpdb->get_results( $this->wpdb->prepare( "SELECT meta_key, meta_value FROM {$this->wpdb->postmeta} WHERE post_id=%d", $post_id ), ARRAY_A );
	}

	/**
	 * Return array with all qTranslate languages, default language always first.
	 *
	 * @return array All languages configured in qTranslate.
	 */
	private function get_all_languages() {
		if ( empty( self::$all_languages ) ) {
			self::$all_languages = array_merge( [ $this->qt_default_language ],
				array_diff( $this->qt_active_languages, [ $this->qt_default_language ] )
			);
		}
		return self::$all_languages;
	}

	private function handle_empty_titles( $posts_to_create, $post ) {
		// handle empty titles
		foreach ( $this->get_all_languages() as $language ) {
			if ( empty( $posts_to_create[ $language ]['post_title'] ) && ! empty( $posts_to_create[ $language ]['post_content'] ) ) {
				$posts_to_create[ $language ]['post_title'] = $post['post_title'];
			}
		}
		return $posts_to_create;
	}

	private function mark_post_in_default_language( $posts_to_create ) {
		// mark post in default language
		if ( isset( $posts_to_create[ $this->qt_default_language ] ) ) {
			$posts_to_create[ $this->qt_default_language ]['__icl_source'] = true;
		} else {
			// if the post in the default language does not exist pick a different post as a 'source'
			foreach ( $this->get_all_languages() as $language ) {
				if ( $language != $this->qt_default_language && ! empty( $posts_to_create[ $language ]['post_title'] ) ) {
					$posts_to_create[ $language ]['__icl_source'] = true;
					break;
				}
			}
		}
		return $posts_to_create;
	}

	private function add_all_cf_to_posts_to_create( $posts_to_create, $post_id ) {
		$custom_fields = $this->get_custom_fields( $post_id );
		foreach ( $custom_fields as $cf ) {
			// only handle scalar values
			if ( ! is_serialized( $cf['meta_value'] ) ) {
				$posts_to_create = $this->add_custom_field_to_posts_to_create( $posts_to_create, $cf );
			} else {
				// copying all the other custom fields
				$posts_to_create = $this->add_serialized_custom_field_to_posts_to_create( $posts_to_create, $cf );
			}
		}
		return $posts_to_create;
	}

	private function remove_icl_post_actions() {
		global $iclTranslationManagement;
		if ( ! empty( $iclTranslationManagement ) ) {
			remove_action( 'save_post', array(
				$iclTranslationManagement,
				'save_post_actions'
			), 11 );
		}
	}


	/**
	 * Copy post status from post being migrated to created posts.
	 *
	 * @param array $posts_to_create
	 * @param array $migrated_post
	 *
	 * @return array
	 */
	private function copy_post_status( $posts_to_create, $migrated_post ) {
		if ( isset( $migrated_post['post_status'] ) ) {
			foreach( $posts_to_create as $lang => $post_data ) {
				$posts_to_create[ $lang ][ 'post_status' ] = $migrated_post['post_status'];
			}
		}

		return $posts_to_create;
	}

}