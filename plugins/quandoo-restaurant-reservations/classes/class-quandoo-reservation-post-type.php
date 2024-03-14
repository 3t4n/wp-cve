<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

class Quandoo_Reservation_Post_Type {
	public $post_type;
	public $singular;
	public $plural;
	public $args;
	public $taxonomies;

	public function __construct( $post_type = 'quandoo-reservation', $singular = '', $plural = '', $args = array(), $taxonomies = array() ) {
		$this->post_type = $post_type;
		$this->singular = $singular;
		$this->plural = $plural;
		$this->args = $args;
		$this->taxonomies = $taxonomies;

		add_action( 'init', array( $this, 'register_post_type' ) );

		if ( is_admin() ) {
			global $pagenow;

			add_action( 'admin_menu', array( $this, 'meta_box_setup' ), 20 );
			add_action( 'save_post', array( $this, 'meta_box_save' ) );
			add_filter( 'enter_title_here', array( $this, 'enter_title_here' ) );

			if ( $pagenow == 'edit.php' && isset( $_GET['post_type'] ) && esc_attr( $_GET['post_type'] ) == $this->post_type ) {

				add_filter( 'manage_edit-' . $this->post_type . '_columns', array( $this, 'register_custom_column_headings' ), 10, 1 );

				add_action( 'manage_posts_custom_column', array( $this, 'register_custom_columns' ), 10, 2);
			}
		}
	} // End __construct()

	public function register_post_type () {
		$labels = array(
			'name' => sprintf( _x( '%s', 'post type general name', 'quandoo-reservation' ), $this->plural ),
			'singular_name' => sprintf( _x( '%s', 'post type singular name', 'quandoo-reservation' ), $this->singular ),
			'add_new' => _x( 'New Widget', '$this->post_type', 'quandoo-reservation' ),
			'add_new_item' => sprintf( __( 'Add New %s', 'Widget' ), 'Widget' ),
			'edit_item' => sprintf( __( 'Edit %s', 'quandoo-reservation' ), $this->singular ),
			'new_item' => sprintf( __( 'New %s', 'quandoo-reservation' ), $this->singular ),
			'all_items' => sprintf( __( 'My %s', 'Widget' ), 'Widgets'),
			'view_item' => sprintf( __( 'View %s', 'quandoo-reservation' ), $this->singular ),
			'search_items' => sprintf( __( 'Search %a', 'quandoo-reservation' ), $this->plural ),
			'not_found' => sprintf( __( 'No %s Found', 'Widget' ), 'Widgets' ),
			'not_found_in_trash' => sprintf( __( 'No %s Found In Trash', 'quandoo-reservation' ), $this->plural ),
			'parent_item_colon' => '',
			'menu_name' => __('Reservations', 'quandoo-reservation'),
		);

		$single_slug = apply_filters( 'quandoo-reservation_single_slug', _x( sanitize_title_with_dashes( $this->singular ), 'single post url slug', 'quandoo-reservation' ) );
		$archive_slug = apply_filters( 'quandoo-reservation_archive_slug', _x( sanitize_title_with_dashes( $this->plural ), 'post archive url slug', 'quandoo-reservation' ) );

		$defaults = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => $single_slug ),
			'capability_type' => 'post',
			'has_archive' => $archive_slug,
			'hierarchical' => false,
			'supports' => array( 'title' ),
			'menu_position' => 2,
			'menu_icon' => 'dashicons-smiley',
		);

		$args = wp_parse_args( $this->args, $defaults );

		register_post_type( $this->post_type, $args );
	} // End register_post_type()

	public function register_custom_columns ( $column_name, $id ) {

		global $post;

		switch ( $column_name ) {
			case 'shortcode':
				echo '[qbook id="'.$id.'"]';
			break;
			default:
			break;
		}
	} // End register_custom_columns()

	public function register_custom_column_headings ( $defaults ) {
		$new_columns = array( 'shortcode' => __( 'Shortcode', 'quandoo-reservation' ) );
		$last_item = array();

		if ( isset( $defaults['date'] ) ) { unset( $defaults['date'] ); }

		if ( count( $defaults ) > 2 ) {
			$last_item = array_slice( $defaults, -1 );

			array_pop( $defaults );
		}
		$defaults = array_merge( $defaults, $new_columns );

		if ( is_array( $last_item ) && 0 < count( $last_item ) ) {
			foreach ( $last_item as $k => $v ) {
				$defaults[$k] = $v;
				break;
			}
		}

		return $defaults;
	} // End register_custom_column_headings()

	public function meta_box_setup () {
		add_meta_box( $this->post_type . '-data', __( 'Widget Details', 'quandoo-reservation' ), array( $this, 'meta_box_content' ), $this->post_type, 'normal', 'high' );
	} // End meta_box_setup()

	public function meta_box_content () {
		global $post_id;
		$fields = get_post_custom( $post_id );
		$field_data = $this->get_custom_fields_settings();

		$html = '';

		$html .= '<input type="hidden" name="quandoo-reservation_' . $this->post_type . '_noonce" id="quandoo-reservation_' . $this->post_type . '_noonce" value="' . wp_create_nonce( plugin_basename( dirname( Quandoo_Reservation()->plugin_path ) ) ) . '" />';

		if ( 0 < count( $field_data ) ) {
			$html .= '<table class="form-table">' . "\n";
			$html .= '<tbody>' . "\n";

			foreach ( $field_data as $k => $v ) {
				$data = $v['default'];
				if ( isset( $fields['_' . $k] ) && isset( $fields['_' . $k][0] ) ) {
					$data = $fields['_' . $k][0];
				}

				if ($v['type'] == 'text') {
					$html .= '<tr class="regular-text ' . $v['class'] .'" valign="top"><th scope="row"><label for="' . esc_attr( $k ) . '">' . $v['name'] . '</label></th><td><input name="' . esc_attr( $k ) . '" type="text" id="' . esc_attr( $k ) . '" value="' . esc_attr( $data ) . '" />' . "\n";
					$html .= '<p class="description">' . $v['description'] . '</p>' . "\n";
					$html .= '</td></tr>' . "\n";
				} if ($v['type'] == 'text-shortcode') {
					$html .= '<tr class="regular-text ' . $v['class'] .'" valign="top"><th scope="row"><label for="' . esc_attr( $k ) . '">' . $v['name'] . '</label></th><td><input name="' . esc_attr( $k ) . '" type="text" id="' . esc_attr( $k ) . '" value="' . esc_attr( $data ) . '" disabled />' . "\n";
					$html .= '<p class="description">' . $v['description'] . '</p>' . "\n";
					$html .= '</td></tr>' . "\n";
				} else if ($v['type'] == 'text-multi') {
					$html .= '<tr class="multi-text ' . $v['class'] .'" valign="top"><th scope="row"><label for="' . esc_attr( $k ) . '">' . $v['name'] . '</label></th><td><input name="' . esc_attr( $k ) . '" type="hidden" id="' . esc_attr( $k ) . '" value="' . esc_attr( $data ) . '" />' . "\n";
					$html .= '<button id="qAddRestaurant" class="button button-primary">' . $v['buttonText'] . '</button>' . "\n";
					$html .= '<p class="description">' . $v['description'] . '</p>' . "\n";
					$html .= '</td></tr>' . "\n";
				} else if ($v['type'] == 'select') {
					$html .= '<tr class="selectable ' . $v['class'] .'" valign="top"><th scope="row"><label for="' . esc_attr( $k ) . '">' . $v['name'] . '</label></th><td><select name="' . esc_attr( $k ) . '" type="select" id="' . esc_attr( $k ) . '" value="' . esc_attr( $data ) . '" />' . "\n";
					foreach ( $v['options'] as $key => $val ) {
						$xdrfg=$this->get_value(get_the_ID(), $k);
						$html .= '<option value="' . esc_attr( $key ) . '"' . selected( esc_attr( $xdrfg[0]), $key, false ) . '">'.esc_attr( $val ).'</option>'. "\n";
					}
					$html .= '</select>' . "\n";
					$html .= '<p class="description">' . $v['description'] . '</p>' . "\n";
					$html .= '</td></tr>' . "\n";
				} else if ($v['type'] == 'radio') {
					$i=0;
					$checked='';
					$html .= '<tr valign="top" class="selectable ' . $v['class'] .'"><th scope="row"><label for="' . esc_attr( $k ) . '">' . $v['name'] . '</label></th><td>' . "\n";
					foreach ( $v['options'] as $key => $val ) {
						if($i==0) {$checked = 'checked'; } else {$checked = '';}
						$tfrew=$this->get_value(get_the_ID(), $k);
						$html .= '<input '. $checked .' id="' . esc_attr( $key ) . '" name="' . esc_attr( $k ) . '" type="radio" value="' . esc_attr( $key ) . '"' . checked(  esc_attr( $tfrew[0]), $key, false ) . ' />' . esc_attr( $val ) . '<br>' . "\n";

						//$html .= '<input id="' . esc_attr( $key ) . '" name="' . esc_attr( $k ) . '" type="radio" value="' . esc_attr( $key ) . '"' . checked(  esc_attr( $this->get_value(get_the_ID(), $key)[0]), $key, false ) . ' />' . esc_attr( $val ) . '<br>' . "\n";

						$i++;
					}
					$html .= '<p class="description">' . $v['description'] . '</p>' . "\n";
					$html .= '</td></tr>' . "\n";
				}
				
			}

			$html .= '</tbody>' . "\n";
			$html .= '</table>' . "\n";
		}

		echo $html;
	} // End meta_box_content()

	public function get_value($id, $name) {
		$custom_fields = get_post_custom($id);
		if($custom_fields) {
			return $custom_fields["_".$name];
		}
        
	}

	public function meta_box_save ( $post_id ) {
		global $post, $messages;

		// Verify
		if ( get_post_type() != $this->post_type ) {
			return $post_id;
		}

		if ( ! isset( $_POST['quandoo-reservation_' . $this->post_type . '_noonce'] ) || ! wp_verify_nonce( $_POST['quandoo-reservation_' . $this->post_type . '_noonce'], plugin_basename( dirname( Quandoo_Reservation()->plugin_path ) ) ) ) {
			return $post_id;
		}

		if ( isset( $_POST['post_type'] ) && 'page' == esc_attr( $_POST['post_type'] ) ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
		}

		$field_data = $this->get_custom_fields_settings();
		$fields = array_keys( $field_data );

		foreach ( $fields as $f ) {

			${$f} = strip_tags(trim($_POST[$f]));

			// Escape the URLs.
			if ( 'url' == $field_data[$f]['type'] ) {
				${$f} = esc_url( ${$f} );
			}

			if ( get_post_meta( $post_id, '_' . $f ) == '' ) {
				add_post_meta( $post_id, '_' . $f, ${$f}, true );
			} elseif( ${$f} != get_post_meta( $post_id, '_' . $f, true ) ) {
				update_post_meta( $post_id, '_' . $f, ${$f} );
			} elseif ( ${$f} == '' ) {
				delete_post_meta( $post_id, '_' . $f, get_post_meta( $post_id, '_' . $f, true ) );
			}
		}
	} // End meta_box_save()

	public function enter_title_here ( $title ) {
		if ( get_post_type() == $this->post_type ) {
			$title = __( 'Enter the Widget title here', 'quandoo-reservation' );
		}
		return $title;
	} // End enter_title_here()

	public function get_custom_fields_settings () {
		$fields = array();

		$fields['select-widget-type'] = array(
		    'name' => __( 'Choose widget type', 'quandoo-reservation' ),
			'type' => 'select',
			'class' => 'custom-widget-type',
			'default' => 'calendar',
			'section' => 'intro',
			'options' => array(
				'calendar' => __( 'Calendar', 'quandoo-reservation' ),
				'button' => __( 'Button', 'quandoo-reservation' ),
				'multi' => __( 'Multi Restaurant', 'quandoo-reservation' )
			),
			'description' => __( 'Choose to display the full reservation calendar, or just a button that will lead to the calendar', 'quandoo-reservation' )
		);

		$fields['multi'] = array(
		    'name' => __( 'Restaurants', 'quandoo-reservation' ),
			'type' => 'text-multi',
			'class' => 'multi-reservation-key button-field multi-field multi-only hideme',
			'default' => '[]',
			'section' => 'intro',
			'buttonText' => __( 'Add restaurant', 'quandoo-reservation' ),
			'description' => __( 'First box: Restaurant name | Second box: Reservation key', 'quandoo-reservation' )
		);

		$fields['button-text'] = array(
		    'name' => __( 'Button text', 'quandoo-reservation' ),
			'type' => 'text',
			'class' => 'custom-button-text button-field multi-field hideme',
			'default' => __('Book Now', 'quandoo-reservation'),
			'section' => 'intro',
			'description' => __( 'This text will display on the button', 'quandoo-reservation' )
		);

		$fields['select-button-position'] = array(
			'name' => __( 'Position', 'quandoo-reservation' ),
			'type' => 'radio',
			'default' => 'inline',
			'section' => 'intro',
			'class' => 'custom-button-position button-field qbook-radio-button hideme',
			'options' => array(
				'inline' => __( 'Custom positioning (exactly where you paste the shorcode)', 'quandoo-reservation' ),
				'tl' => __( 'Top left', 'quandoo-reservation' ),
				'tr' => __( 'Top right', 'quandoo-reservation' ),
				'sr' => __( 'Side right', 'quandoo-reservation' ),
				'bl' => __( 'Bottom left', 'quandoo-reservation' ),
				'br' => __( 'Bottom right', 'quandoo-reservation' )
			),
			'description' => __( 'Choose the screen position of the button', 'quandoo-reservation' )
		);

		$fields['button-size'] = array(
			'name' => __( 'Button size', 'quandoo-reservation' ),
			'type' => 'select',
			'default' => 'sm',
			'section' => 'intro',
			'class' => 'button-field hideme multi-field',
			'options' => array(
				'sm' => __( 'Small', 'quandoo-reservation' ),
				'md' => __( 'Medium', 'quandoo-reservation' ),
				'lg' => __( 'Large', 'quandoo-reservation' )
			),
			'description' => __( 'Choose button size', 'quandoo-reservation' )
		);

		$fields['select-background-color'] = array(
			'name' => __( 'Set button colour', 'quandoo-reservation' ),
			'type' => 'text',
			'class' => 'qbook-color-picker button-field hideme multi-field',
			'default' => '#f8b333',
			'section' => 'intro',
			'description' => __( 'Set the background colour of the button', 'quandoo-reservation' )
		);

		$fields['select-text-color'] = array(
			'name' => __( 'Button text color', 'quandoo-reservation' ),
			'type' => 'text',
			'class' => 'qbook-color-picker button-field hideme multi-field',
			'default' => '#fff',
			'section' => 'intro',
			'description' => __( 'Set the colour of the text on the button', 'quandoo-reservation' )
		);

		$fields['select-calendar-color'] = array(
			'name' => __( 'Calendar theme', 'quandoo-reservation' ),
			'type' => 'select',
			'default' => 'brand',
			'section' => 'intro',
			'class' => 'custom-calendar-color',
			'options' => array(
				'brand' => __( 'Quandoo', 'quandoo-reservation' ),
				'dark' => __( 'Dark', 'quandoo-reservation' ),
				'light' => __( 'Light', 'quandoo-reservation' )				
			),
			'description' => __( 'Set the colour scheme of the reservation calendar', 'quandoo-reservation' )
		);

		$fields['get-shortcode'] = array(
			'name' => __( 'Shortcode', 'quandoo-reservation' ),
			'type' => 'text-shortcode',
			'class' => 'shortcode-field',
			'default' => '[qbook id="'.get_the_ID().'"]',
			'section' => 'intro',
			'description' => __( 'Copy the shortcode [qbook id="'.get_the_ID().'"] and paste it where you want to display the widget', 'quandoo-reservation' )
		);

		return (array)apply_filters( 'quandoo-reservation_custom_fields_settings', $fields );
	} // End get_custom_fields_settings()

} // End Class

//Frontend
require_once( 'class-quandoo-reservation-frontend.php' );