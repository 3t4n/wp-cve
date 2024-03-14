<?php

defined( 'ABSPATH' ) || exit;

final class CPT_Fields {
	/**
	 * @var array
	 */
	private $types = array();

	/**
	 * @param $key
	 * @param $parent_name
	 *
	 * @return string|void
	 */
	private function get_field_input_name( $key = '', $parent_name = false ) {
		if ( empty( $key ) ) {
			return;
		}

		return esc_html( 'meta-fields' . ( $parent_name ? $parent_name : '' ) . '[' . $key . ']' );
	}

	/**
	 * @param $key
	 * @param $parent_id
	 *
	 * @return string|void
	 */
	private function get_field_input_id( $key = '', $parent_id = false ) {
		if ( empty( $key ) ) {
			return;
		}
		$parent_id = $parent_id ? str_replace( '][', '-', $parent_id ) : '';
		$parent_id = str_replace( '[', '-', $parent_id );
		$parent_id = str_replace( ']', '', $parent_id );

		return esc_html( 'meta-fields' . $parent_id . '-' . $key );
	}

	/**
	 * @param $value
	 *
	 * @return mixed|string
	 */
	private function sanitize_recursive_value( $value ) {
		if ( is_string( $value ) ) {
			$value = esc_html( $value );
		} elseif ( is_array( $value ) ) {
			foreach ( $value as $i => $item ) {
				$value[ $i ] = self::sanitize_recursive_value( $item );
			}
		}

		return $value;
	}

	/**
	 * @param $field_config
	 *
	 * @return false|string|void
	 */
	public function get_field_template( $field_config = array() ) {
		$parent     = ! empty( $field_config['parent'] ) ? $field_config['parent'] : false;
		$input_id   = $this->get_field_input_id( $field_config['key'], $parent );
		$input_name = $this->get_field_input_name( $field_config['key'], $parent );
		$field_type = $this->get_field( $field_config['type'] );
		if ( ! $field_type ) {
			return;
		}
		if ( ! empty( $field_config['extra']['placeholder'] ) ) {
			$field_config['extra']['placeholder'] = esc_html( $field_config['extra']['placeholder'] );
		}
		if ( 'repeater' !== $field_config['type'] && ! empty( $field_config['value'] ) ) {
			$field_config['value'] = self::sanitize_recursive_value( $field_config['value'] );
		}
		ob_start();
		?>
		<div
				class="cpt-field"<?php echo ! empty( $field_config['wrap']['width'] ) ? ' style="width: ' . esc_html( $field_config['wrap']['width'] ) . '%"' : ''; ?>
				data-field-type="<?php echo esc_html( $field_config['type'] ); ?>">
			<div class="cpt-field-inner">
				<input type="hidden" name="<?php echo esc_html( $input_name ); ?>" value="">
				<?php
				printf(
					'<div class="cpt-field-wrap%s"%s><label for="%s">%s</label><div class="input">%s</div>%s</div>',
					esc_html( ! empty( $field_config['wrap']['layout'] ) ? ' ' . $field_config['wrap']['layout'] : '' ) .
					( $field_config['required'] ? ' cpt-field-required' : '' ) .
					esc_html( ! empty( $field_config['wrap']['class'] ) ? ' ' . $field_config['wrap']['class'] : '' ) .
					esc_html( ! empty( $field_config['extra']['prepend'] ) ? ' cpt-field-prepend' : '' ) .
					esc_html( ! empty( $field_config['extra']['append'] ) ? ' cpt-field-append' : '' ),
					! empty( $field_config['wrap']['id'] ) ? ' id="' . esc_html( $field_config['wrap']['id'] ) . '"' : '',
					esc_html( $input_id ),
					wp_kses_post( $field_config['label'] ),
					$field_type::render( $input_name, $input_id, $field_config ),
					! empty( $field_config['info'] ) ? '<div class="description"><p>' . wp_kses_post( $field_config['info'] ) . '</p></div>' : ''
				);
				?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * @param $config
	 * @param $get_value_callback
	 *
	 * @return false|string
	 */
	private function get_fields_section( $config = array(), $get_value_callback = null ) {
		$fields_group_id = $config['id'];
		$fields          = ! empty( $config['fields'] ) ? $config['fields'] : array();
		ob_start();
		wp_nonce_field( CPT_NONCE_KEY, 'fields-nonce' );
		?>
		<div class="cpt-fields-section" data-id="<?php echo esc_html( $fields_group_id ); ?>">
			<?php
			foreach ( $fields as $field ) {
				$field['value']           = $get_value_callback( $field['key'] );
				$field_type               = $field['type'];
				$field                    = apply_filters( 'cpt_field_args', $field, $field_type );
				$field['fields_group_id'] = $fields_group_id;
				echo $this->get_field_template( $field );
			}
			?>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * @param $field_group
	 * @param $save_callback
	 * @param $content_type
	 * @param $content_id
	 *
	 * @return void
	 */
	private function save_meta( $field_group, $save_callback, $content_type, $content_id ) {
		if (
			empty( $_POST['fields-nonce'] ) ||
			! wp_verify_nonce( $_POST['fields-nonce'], CPT_NONCE_KEY ) ||
			( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) ||
			! is_callable( $save_callback )
		) {
			return;
		}
		$meta_values = isset( $_POST['meta-fields'] ) ? $_POST['meta-fields'] : array();
		$fields      = $field_group['fields'];

		foreach ( $fields as $field ) {
			$meta_key  = $field['key'];
			$meta_type = $field['type'];
			if ( ! isset( $meta_values[ $meta_key ] ) ) {
				continue;
			} elseif ( ! empty( $meta_values[ $meta_key ] ) ) {
				$meta_value     = $meta_values[ $meta_key ];
				$sanitize_value = apply_filters( 'cpt_field_sanitize', $meta_value, $meta_key, $meta_type, $field_group, $content_type, $content_id );
			} else {
				$sanitize_value = '';
			}
			$save_callback( $meta_key, $sanitize_value );
		}
	}

	/**
	 * @param $route
	 * @param $field_group
	 * @param $get_callback
	 *
	 * @return void
	 */
	private function init_rest_fields( $route, $field_group, $get_callback ) {
		if (
			empty( $route ) ||
			empty( $field_group ) ||
			! is_array( $field_group ) ||
			empty( $get_callback ) ||
			! is_callable( $get_callback )
		) {
			return;
		}

		$group_id = $field_group['id'];
		if ( ! empty( $field_group['show_in_rest'] ) ) {
			$fields = ! empty( $field_group['fields'] ) && is_array( $field_group['fields'] ) ? $field_group['fields'] : array();
			add_action(
				'rest_api_init',
				function () use ( $route, $group_id, $fields, $get_callback ) {
					register_rest_field(
						$route,
						$group_id,
						array(
							'get_callback' => function ( $item ) use ( $route, $group_id, $fields, $get_callback ) {
								$content_id = $item['id'];
								$values     = array();
								foreach ( $fields as $field ) {
									$meta_key            = $field['key'];
									$output_filter       = apply_filters( 'cpt_rest_output', true, $meta_key, $route, $group_id, $content_id );
									$values[ $meta_key ] = $get_callback( $meta_key, $content_id, $output_filter );
								}

								return $values;
							},
						)
					);
				}
			);
		}
	}

	/**
	 * @param $post_type
	 * @param $field_group
	 *
	 * @return void
	 */
	public function init_post_type_fields( $post_type = '', $field_group = array() ) {
		// /wp-json/wp/v2/POST-TYPE/POST-ID
		$this->init_rest_fields(
			$post_type,
			$field_group,
			function ( $key, $id, $output_filter ) {
				return cpt_get_post_meta( $key, $id, $output_filter );
			}
		);

		add_action(
			'add_meta_boxes',
			function ( $posttype ) use ( $post_type, $field_group ) {
				if ( $posttype !== $post_type ) {
					return;
				}
				add_meta_box(
					$field_group['id'],
					$field_group['label'],
					function ( $post ) use ( $field_group ) {
						echo $this->get_fields_section(
							$field_group,
							function ( $key ) use ( $post ) {
								return ! empty( $post->ID ) ? get_post_meta( $post->ID, $key, true ) : null;
							}
						);
					},
					$post_type,
					$field_group['position']
				);
			}
		);
		add_action(
			'save_post_' . $post_type,
			function ( $post_id ) use ( $field_group, $post_type ) {
				$field_group_id = $field_group['id'];
				$this->save_meta(
					$field_group,
					function ( $key, $value ) use ( $post_id ) {
						return update_post_meta( $post_id, $key, $value );
					},
					$post_type,
					$post_id
				);
			}
		);
	}

	/**
	 * @param $taxonomy
	 * @param $field_group
	 *
	 * @return void
	 */
	public function init_taxonomy_fields( $taxonomy = '', $field_group = array() ) {
		// /wp-json/wp/v2/TAXONOMY/TERM-ID
		$this->init_rest_fields(
			$taxonomy,
			$field_group,
			function ( $key, $id, $output_filter ) {
				return cpt_get_term_meta( $key, $id, $output_filter );
			}
		);

		$add_actions = array(
			$taxonomy . '_add_form_fields',
			$taxonomy . '_edit_form',
		);
		foreach ( $add_actions as $action ) {
			add_action(
				$action,
				function ( $term ) use ( $field_group ) {
					echo $this->get_fields_section(
						$field_group,
						function ( $key ) use ( $term ) {
							return ! empty( $term->term_id ) ? get_term_meta( $term->term_id, $key, true ) : null;
						}
					);
				}
			);
		}

		$save_actions = array(
			'edited_' . $taxonomy,
			'created_' . $taxonomy,
		);
		foreach ( $save_actions as $action ) {
			add_action(
				$action,
				function ( $term_id ) use ( $field_group, $taxonomy ) {
					$this->save_meta(
						$field_group,
						function ( $key, $value ) use ( $term_id ) {
							return update_term_meta( $term_id, $key, $value );
						},
						$taxonomy,
						$term_id
					);
				}
			);
		}
	}

	/**
	 * @param $options_page
	 * @param $field_group
	 *
	 * @return void
	 */
	public function init_options_page_fields( $options_page = '', $field_group = array() ) {
		foreach ( $field_group['fields'] as $i => $field ) {
			$field_group['fields'][ $i ]['key'] = $options_page . '-' . $field_group['fields'][ $i ]['key'];
		}

		add_action(
			'admin_init',
			function () use ( $options_page, $field_group ) {
				register_setting( $options_page, 'meta-fields' );
				add_settings_section(
					$field_group['id'],
					$field_group['label'],
					function () use ( $options_page, $field_group ) {
						echo $this->get_fields_section(
							$field_group,
							function ( $key ) {
								return get_option( $key );
							}
						);
					},
					$options_page
				);
			}
		);
		add_action(
			'update_option_meta-fields',
			function () use ( $options_page, $field_group ) {
				$this->save_meta(
					$field_group,
					function ( $key, $value ) {
						return update_option( $key, $value );
					},
					'option',
					$options_page
				);
			}
		);
	}

	/**
	 * @param $field_group
	 *
	 * @return void
	 */
	public function init_user_fields( $field_group = array() ) {
		// /wp-json/wp/v2/users/USER-ID
		$this->init_rest_fields(
			'user',
			$field_group,
			function ( $key, $id, $output_filter ) {
				return cpt_get_user_meta( $key, $id, $output_filter );
			}
		);

		$add_actions = array(
			'show_user_profile',
			'edit_user_profile',
		);
		foreach ( $add_actions as $action ) {
			add_action(
				$action,
				function ( $user ) use ( $field_group ) {
					echo $this->get_fields_section(
						$field_group,
						function ( $key ) use ( $user ) {
							return ! empty( $user->ID ) ? get_user_meta( $user->ID, $key, true ) : null;
						}
					);
				}
			);
		}

		$save_actions = array(
			'personal_options_update',
			'edit_user_profile_update',
		);
		foreach ( $save_actions as $action ) {
			add_action(
				$action,
				function ( $user_id ) use ( $field_group ) {
					$this->save_meta(
						$field_group,
						function ( $key, $value ) use ( $user_id ) {
							return update_user_meta( $user_id, $key, $value );
						},
						'user',
						$user_id
					);
				}
			);
		}
	}

	/**
	 * @param $field_group
	 *
	 * @return void
	 */
	public function init_media_fields( $field_group = array() ) {
		// /wp-json/wp/v2/media/MEDIA-ID
		$this->init_rest_fields(
			'attachment',
			$field_group,
			function ( $key, $id, $output_filter ) {
				return cpt_get_media_meta( $key, $id, $output_filter );
			}
		);

		add_action(
			'add_meta_boxes',
			function ( $post_type ) use ( $field_group ) {
				if ( 'attachment' !== $post_type ) {
					return;
				}
				add_meta_box(
					$field_group['id'],
					$field_group['label'],
					function ( $post ) use ( $field_group ) {
						echo $this->get_fields_section(
							$field_group,
							function ( $key ) use ( $post ) {
								return ! empty( $post->ID ) ? get_post_meta( $post->ID, $key, true ) : null;
							}
						);
					},
					'attachment',
					$field_group['position']
				);
			}
		);
		add_action(
			'edit_attachment',
			function ( $post_id ) use ( $field_group ) {
				$this->save_meta(
					$field_group,
					function ( $key, $value ) use ( $post_id ) {
						return update_post_meta( $post_id, $key, $value );
					},
					'attachment',
					$post_id
				);
			}
		);
	}

	/**
	 * @param $field_group
	 *
	 * @return void
	 */
	public function init_comment_fields( $field_group = array() ) {
		// /wp-json/wp/v2/comments/COMMENT-ID
		$this->init_rest_fields(
			'comment',
			$field_group,
			function ( $key, $id, $output_filter ) {
				return cpt_get_comment_meta( $key, $id, $output_filter );
			}
		);

		add_action(
			'add_meta_boxes',
			function ( $post_type ) use ( $field_group ) {
				if ( 'comment' !== $post_type ) {
					return;
				}
				add_meta_box(
					$field_group['id'],
					$field_group['label'],
					function ( $comment ) use ( $field_group ) {
						echo $this->get_fields_section(
							$field_group,
							function ( $key ) use ( $comment ) {
								return ! empty( $comment->comment_ID ) ? get_comment_meta( $comment->comment_ID, $key, true ) : null;
							}
						);
					},
					'comment',
					$field_group['position']
				);
			}
		);
		add_action(
			'edit_comment',
			function ( $comment_id ) use ( $field_group ) {
				$this->save_meta(
					$field_group,
					function ( $key, $value ) use ( $comment_id ) {
						return update_comment_meta( $comment_id, $key, $value );
					},
					'comment',
					$comment_id
				);
			}
		);
	}

	/**
	 * @param $field_group
	 *
	 * @return void
	 */
	public function init_menu_item_fields( $field_group = array() ) {
		// /wp-json/wp/v2/menu-items/ITEM-ID
		$this->init_rest_fields(
			'nav_menu_item',
			$field_group,
			function ( $key, $id, $output_filter ) {
				return cpt_get_menu_item_meta( $key, $id, $output_filter );
			}
		);

		add_action(
			'wp_nav_menu_item_custom_fields',
			function ( $item_id ) use ( $field_group ) {
				foreach ( $field_group['fields'] as $i => $field ) {
					$field_group['fields'][ $i ]['key'] .= '-' . $item_id;
				}

				echo '<div class="description description-wide">';
				echo $this->get_fields_section(
					$field_group,
					function ( $key ) use ( $item_id ) {
						$key = str_replace( '-' . $item_id, '', $key );

						return ! empty( $item_id ) ? get_post_meta( $item_id, $key, true ) : null;
					}
				);
				echo '</div>';
			}
		);
		add_action(
			'wp_update_nav_menu_item',
			function ( $menu_id, $item_id ) use ( $field_group ) {
				foreach ( $field_group['fields'] as $i => $field ) {
					$field_group['fields'][ $i ]['key'] .= '-' . $item_id;
				}

				$this->save_meta(
					$field_group,
					function ( $key, $value ) use ( $item_id ) {
						$key = str_replace( '-' . $item_id, '', $key );

						return update_post_meta( $item_id, $key, $value );
					},
					'menu-item',
					$item_id
				);
			},
			10,
			2
		);
	}

	/**
	 * @param $meta_key
	 * @param $content_type
	 * @param $content_type_id
	 *
	 * @return mixed|string
	 */
	public function get_field_object( $meta_key, $content_type, $content_type_id ) {
		$content_type_fields = cpt_utils()->get_fields_by_supports( "$content_type/$content_type_id" );
		$field_object        = ! empty( $content_type_fields[ $meta_key ] ) ? $content_type_fields[ $meta_key ] : null;

		return $field_object;
	}

	/**
	 * @param $meta_key
	 * @param $content_type
	 * @param $content_type_id
	 * @param $content_id
	 * @param $get_callback
	 * @param $output_filter
	 *
	 * @return mixed|string|void|null
	 */
	public function get_meta( $meta_key, $content_type, $content_type_id, $content_id, $get_callback = null, $output_filter = true ) {
		if ( ! is_callable( $get_callback ) ) {
			return;
		}
		$field_object = $this->get_field_object( $meta_key, $content_type, $content_type_id );
		$meta_type    = isset( $field_object['type'] ) ? $field_object['type'] : null;
		if ( ! $meta_type ) {
			return '';
		}
		$meta_value = $get_callback( $content_id, $meta_key );
		if ( ! $output_filter ) {
			return $meta_value;
		}
		if ( ! is_string( $meta_value ) && ( ! cpt_utils()->is_rest() || 'the_content' == current_filter() ) ) { //phpcs:ignore Universal.Operators.StrictComparisons
			if ( current_user_can( 'edit_posts' ) ) {
				return sprintf(
					'<pre><i>%s</i><br>%s</pre>',
					__( 'This meta value cannot be returned without processing:', 'custom-post-types' ),
					print_r( $meta_value, true )
				);
			}

			return '';
		}

		return apply_filters( 'cpt_field_get', $meta_value, $meta_key, $meta_type, $content_type_id, $content_id );
	}

	/**
	 * @param $field_class
	 * @param $override
	 *
	 * @return void
	 */
	public function add_field_type( $field_class, $override = false ) {

		if ( empty( $this->types[ $field_class::get_type() ] ) || $override ) {
			if ( $override ) {
				$old_class = $this->types[ $field_class::get_type() ];
				remove_filter( 'cpt_field_sanitize', array( $old_class, 'sanitize_value' ), 10 );
				remove_filter( 'cpt_field_get', array( $old_class, 'get_value' ), 10 );
			}
			$this->types[ $field_class::get_type() ] = $field_class;
			add_filter( 'cpt_field_sanitize', array( $field_class, 'sanitize_value' ), 10, 3 );
			add_filter( 'cpt_field_get', array( $field_class, 'get_value' ), 10, 3 );
		}
	}

	/**
	 * @return array
	 */
	public function get_field_types() {
		return $this->types;
	}

	/**
	 * @param $type
	 *
	 * @return false|mixed
	 */
	public function get_field( $type ) {

		$field_class = ! empty( $this->types[ $type ] ) ? $this->types[ $type ] : false;

		return $field_class;
	}
}
