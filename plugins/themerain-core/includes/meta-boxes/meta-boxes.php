<?php

class ThemeRain_Meta_Boxes {

	protected $meta_boxes;

	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
		add_action( 'add_meta_boxes', array( $this, 'add' ) );
		add_action( 'save_post', array( $this, 'save' ) );
	}

	public function init() {
		$this->meta_boxes = $this->get_meta_boxes();
	}

	public function get_meta_boxes() {
		return apply_filters( 'themerain_meta_boxes', array() );
	}

	public function enqueue() {
		wp_enqueue_style( 'trc-meta-boxes', TRC_ASSETS_URL . '/css/meta-boxes.css' );
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'trc-meta-boxes', TRC_ASSETS_URL . '/js/meta-boxes.js', array( 'jquery', 'wp-color-picker', 'wp-data', 'wp-editor' ), false, true );
	}

	public function add() {
		foreach ( $this->meta_boxes as $meta_box ) {
			add_meta_box(
				'themerain_meta_' . $meta_box['id'],
				$meta_box['title'],
				array( $this, 'render' ),
				$meta_box['screen'],
				'normal',
				'high',
				array( 'meta_box' => $meta_box )
			);
		}
	}

	public function render( $post, $args ) {
		$args = $args['args']['meta_box'];

		wp_nonce_field( $args['id'], $args['id'] . '_nonce' );

		if ( isset( $args['template'] ) ) {
			echo '<input type="hidden" class="themerain-condition-template" data-id="themerain_meta_' . $args['id'] . '" data-template="' . $args['template'] . '">';
		}

		foreach ( $args['fields'] as $field ) {
			$this->field( $post, $field );
		}
	}

	public function field( $post, $field ) {
		$meta    = get_post_meta( $post->ID, $field['id'], true );
		$std     = isset( $field['std'] ) ? $field['std'] : '';
		$meta    = ( $meta || '0' === $meta ) ? $meta : $std;
		$id      = $field['id'] ? esc_attr( $field['id'] ) : '';
		$cond    = '';
		$class[] = 'themerain-meta-field';
		$class[] = 'themerain-meta-' . $field['type'];

		if ( isset( $field['cond'] ) ) {
			$cond_meta = get_post_meta( $post->ID, $field['cond'][0], true );
			$cond      = ' data-condition="' . implode( ',', $field['cond'] ) . '"';
			$class[]   = ( ( '1' === $field['cond'][1] && $cond_meta ) || ( '0' === $field['cond'][1] && ! $cond_meta ) || $cond_meta === $field['cond'][1] ) ? '' : 'themerain-hidden';
		}

		$html = '<div class="' . implode( ' ', $class ) . '"' . $cond . '>';

		$html .= '<div class="themerain-meta-label">';
			$html .= '<label for="' . $id . '">' . $field['label'] . '</label>';
			$html .= isset( $field['desc'] ) ? '<p class="description">' . $field['desc'] . '</p>' : '';
		$html .= '</div>';

		$html .= '<div class="themerain-meta-input">';

		switch ( $field['type'] ) {

			case 'text':
				$html .= '<input type="text" id="' . $id . '" name="' . $id . '" value="' . wp_kses_post( $meta ) . '">';
				break;

			case 'textarea':
				$html .= '<textarea id="' . $id . '" name="' . $id . '" rows="5">' . wp_kses_post( $meta ) . '</textarea>';
				break;

			case 'url':
				$html .= '<div class="themerain-meta-url__wrap">';
				$html .= '<i class="themerain-meta-url__icon"></i>';
				$html .= '<input type="url" id="' . $id . '" name="' . $id . '" value="' . esc_url( $meta ) . '">';
				$html .= '</div>';
				break;

			case 'checkbox':
				$html .= '<input type="checkbox" id="' . $id . '" name="' . $id . '"' . ( $meta ? ' checked' : '' ) . '>';
				break;

			case 'toggle':
				$html .= '<div class="themerain-toggle' . ( $meta ? ' is-checked' : '' ) . '">';
				$html .= '<input type="checkbox" id="' . $id . '" name="' . $id . '"' . ( $meta ? ' checked' : '' ) . '>';
				$html .= '<div class="themerain-toggle__track"></div><div class="themerain-toggle__slider"></div>';
				$html .= '</div>';
				break;

			case 'select':
				$html .= '<select id="' . $id . '" name="' . $id . '">';
				foreach( $field['choices'] as $value => $label ) {
					$html .= '<option value="' . esc_attr( $value ) . '"' . ( $meta === $value ? ' selected' : '' ) . '>' . esc_html( $label ) . '</option>';
				}
				$html .= '</select>';
				break;

			case 'color':
				$html .= '<input type="text" id="' . $id . '" name="' . $id . '" value="' . sanitize_hex_color( $meta ) . '">';
				break;

			case 'range':
				$range = isset( $field['range'] ) ? ' min="' . $field['range'][0] . '" max="' . $field['range'][1] . '" step="' . $field['range'][2] . '"' : '';

				$html .= '<input type="range" id="' . $id . '" name="' . $id . '" value="' . esc_attr( $meta ) . '"' . $range . '>';
				$html .= '<span class="themerain-meta-range__value">' . esc_attr( $meta ) . '</span>';
				break;

			case 'group':
				foreach ( $field['choices'] as $value => $label ) {
					$html .= '<label class="button' . ( $value === $meta ? ' button-primary' : '' ) . '">';
					$html .= '<input type="radio" name="' . $id . '" value="' . esc_attr( $value ) . '"' . ( $value === $meta ? ' checked' : '' ) . '>';
					$html .= $label . '</label> ';
				}
				break;

			case 'media':
				if ( isset( $field['media'] ) || isset( $field['media_type'] ) ) {
					$media     = $meta ? '<div></div>' : '';
					$data_type = 'video/mp4';
					$btn_text  = 'Upload video';
				} else {
					$media     = $meta ? '<img src="' . wp_get_attachment_image_url( esc_attr( $meta ) ) . '">' : '';
					$data_type = 'image';
					$btn_text  = 'Upload image';
				}

				$html .= '<div class="themerain-meta-media__wrap' . ( $meta ? ' has-value' : '' ) . '">';
				$html .= '<input type="hidden" name="' . $id . '" value="' . esc_attr( $meta ) . '" data-type="' . $data_type . '">';
				$html .= '<a href="#" class="themerain-meta-media__preview">' . $media . '</a>';
				$html .= '<a href="#" class="themerain-meta-media__remove" title="Remove"></a>';
				$html .= '<button class="button button-secondary themerain-meta-media__upload">' . $btn_text . '</button>';
				$html .= '</div>';
				break;

			case 'pages':
				$dropdown = wp_dropdown_pages(
					array(
						'name'              => $id,
						'echo'              => 0,
						'show_option_none'  => '&mdash; Select &mdash;',
						'option_none_value' => '0',
						'selected'          => $meta
					)
				);

				$html .= $dropdown;
				break;

			case 'taxonomy':
				$tax   = isset( $field['taxonomy'] ) ? $field['taxonomy'] : 'project-category';
				$terms = get_terms( $tax, array( 'hide_empty' => false ) );

				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
					$html .= '<div class="themerain-meta-taxonomy-holder">';
						foreach( $terms as $term ) {
							$checked = ( is_array( $meta ) && in_array( $term->term_id, $meta ) ) ? ' checked' : '';
							$html .= '<label><input type="checkbox" name="' . $id . '[]" value="' . $term->term_id . '"' . $checked . '><span>' . esc_html( $term->name ) . '</span></label>';
						}
					$html .= '</div>';
				} else {
					$html .= 'No categories found.';
				}
				break;
		}

		$html .= '</div>';
		$html .= '</div>';

		echo $html;
	}

	public function save( $post_id ) {
		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );

		if ( $is_autosave || $is_revision ) {
			return;
		}

		foreach( $this->meta_boxes as $meta_box ) {
			$nonce_name     = $meta_box['id'] . '_nonce';
			$is_valid_nonce = ( isset( $_POST[$nonce_name] ) && wp_verify_nonce( $_POST[$nonce_name], $meta_box['id'] ) ) ? true : false;

			if ( $is_valid_nonce ) {
				foreach( $meta_box['fields'] as $field ) {
					$new = isset( $_POST[$field['id']] ) ? $_POST[$field['id']] : '';
					update_post_meta( $post_id, $field['id'], $new );
				}
			}
		}
	}
}

new ThemeRain_Meta_Boxes();
