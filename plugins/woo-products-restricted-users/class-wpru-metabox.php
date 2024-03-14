<?php
/**
 * Metabox
 *
 * @author   Codection
 * @category Root
 * @package  Products Restricted Users from WooCommerce
 * @version  1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPRU_Metabox {
	/**
	 * Array Containing the screen where it should appear.
	 *
	 * @var screen
	 */
	private $screen;

	/**
	 * Array Containing the info of the fields.
	 *
	 * @var screen
	 */
	private $meta_fields;

	/**
	 * Constructor
	 **/
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_fields' ) );
		add_action( 'admin_footer', array( $this, 'scripts' ) );

		$this->screen = array( 'product' );
		$this->meta_fields = array(
			array(
				'label' => __( 'Enable', 'wpru' ),
				'id' => 'wpru_enable',
				'type' => 'checkbox',
				'options' => false,
			),
			array(
				'label' => 'Mode',
				'id' => 'wpru_mode',
				'multiple' => false,
				'type' => 'select',
				'options' => array(
					'view' => __( 'Only users in the list can see this product, the other will be redirected to homepage', 'wpru' ),
					'buy' => __( 'Only users in the list can see and buy this product, the others users could see it but they could not buy it', 'wpru' ),
				),
			),
			array(
				'label' => 'Users',
				'id' => 'wpru_users',
				'multiple' => true,
				'type' => 'select',
				'options' => $this->get_user_list(),
			),
		);
	}

	/**
	 * Enqueue
	 **/
	public function enqueue() {
		wp_enqueue_style( 'select2', plugins_url( 'assets/select2.min.css', __FILE__ ), array(), '4.0.5' );
		wp_enqueue_script( 'select2', plugins_url( 'assets/select2.full.min.js', __FILE__ ), array( 'jquery' ), '4.0.5' );
	}

	/**
	 * Add meta boxes
	 **/
	public function add_meta_boxes() {
		foreach ( $this->screen as $single_screen ) {
			add_meta_box(
				'userswhichcanviewand',
				__( 'Users which can view and buy this product', 'wpru' ),
				array( $this, 'meta_box_callback' ),
				$single_screen,
				'advanced',
				'default'
			);
		}
	}

	/**
	 * Get a list of users
	 **/
	public function get_user_list() {
		$users = get_users( array( 'fields' => array( 'ID', 'user_nicename' ) ) );
		$list = array();

		foreach ( $users as $user ) {
			$list[ $user->ID ] = $user->user_nicename;
		}

		return $list;
	}

	/**
	 * Meta box callback
	 *
	 * @param WP_Post $post  The current post.
	 **/
	public function meta_box_callback( $post ) {
		wp_nonce_field( 'userswhichcanviewand_data', 'userswhichcanviewand_nonce' );
		echo 'If you want to hide or make this product not purchasable to a group of users, please enable it. The users you will choose in the list will not suffer the restriction.';
		$this->field_generator( $post );
	}

	/**
	 * Field generator for metabox
	 *
	 * @param WP_Post $post  The current post.
	 **/
	public function field_generator( $post ) {
		$output = '';
		foreach ( $this->meta_fields as $meta_field ) {
			$label = '<label for="' . $meta_field['id'] . '">' . $meta_field['label'] . '</label>';
			$meta_value = get_post_meta( $post->ID, $meta_field['id'], true );

			if ( empty( $meta_value ) ) {
				$meta_value = array();
			}

			switch ( $meta_field['type'] ) {
				case 'select':
					$input = sprintf( '<select style="width:100%%;" %s id="%s" name="%s%s">', $meta_field['multiple'] ? 'multiple' : '', $meta_field['id'], $meta_field['id'], $meta_field['multiple'] ? '[]' : '' );

					foreach ( $meta_field['options'] as $key => $value ) {
						if ( $meta_field['multiple'] ) {
							$input .= sprintf( '<option %s value="%s">%s</option>', in_array( $key, $meta_value ) ? 'selected' : '', $key, $value );
						} else {
							$input .= sprintf( '<option %s value="%s">%s</option>', ( $key == $meta_value ) ? 'selected' : '', $key, $value );
						}
					}

					$input .= '</select>';

					break;

				case 'checkbox':
					$checked = ( $meta_value == true ) ? 'checked="checked"' : '';
					$input = sprintf( '<input type="checkbox" multiple id="%s" name="%s" %s>', $meta_field['id'], $meta_field['id'], $checked );
					break;
			}

			$output .= $this->format_rows( $label, $input );
		}

		echo '<table class="form-table"><tbody>' . wp_kses( $output, $this->allowed_tags() ) . '</tbody></table>';
	}

	/**
	 * Expand allowed tags.
	 **/
	private function allowed_tags() {
		$allowed = wp_kses_allowed_html( 'post' );

		$allowed['input'] = array(
			'class' => array(),
			'id'    => array(),
			'name'  => array(),
			'value' => array(),
			'type'  => array(),
			'checked' => array(),
		);

		$allowed['select'] = array(
			'class'  => array(),
			'id'     => array(),
			'name'   => array(),
			'value'  => array(),
			'type'   => array(),
			'multiple' => array(),
		);

		$allowed['option'] = array(
			'selected' => array(),
			'value' => array(),
		);

		return $allowed;
	}

	/**
	 * Format row for field generator
	 *
	 * @param string $label The label.
	 * @param string $input The input.
	 **/
	public function format_rows( $label, $input ) {
		return '<tr><th>' . $label . '</th><td>' . $input . '</td></tr>';
	}

	/**
	 * Scripts
	 **/
	public function scripts() {
		global $post;

		if ( empty( $post ) ) {
			return;
		}

		if ( $post->post_type !== 'product' ) {
			return;
		}
		?>
		<script type="text/javascript">
		jQuery( document ).ready( function( $ ){
			$( '#wpru_users' ).select2({
				width: 'element',
				minimumResultsForSearch: Infinity,
			});
		} )
		</script>
		<?php
	}

	/**
	 * Save fields
	 *
	 * @param int $post_id The post id.
	 **/
	public function save_fields( $post_id ) {
		if ( ! isset( $_POST['userswhichcanviewand_nonce'] ) ) {
			return $post_id;
		}

		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['userswhichcanviewand_nonce'] ) ), 'userswhichcanviewand_data' ) ) {
			return $post_id;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		$mode = isset( $_POST['wpru_mode'] ) ? sanitize_text_field( wp_unslash( $_POST['wpru_mode'] ) ) : 'view';

		$wpru_users = array();
		$wpru_users_from_post = isset( $_POST['wpru_users'] ) ? $_POST['wpru_users'] : array();
		foreach ( $wpru_users_from_post as $wpru_user ) {
			$wpru_users[] = absint( $wpru_user );
		}

		if ( isset( $_POST['wpru_enable'] ) && ! empty( $_POST['wpru_enable'] ) ) {
			update_post_meta( $post_id, 'wpru_enable', true );
			update_post_meta( $post_id, 'wpru_mode', $mode );
			update_post_meta( $post_id, 'wpru_users', $wpru_users );

			( $mode === 'view' ) ? $this->refresh_allowed_products_users( $wpru_users, $post_id ) : $this->maybe_remove_allowed_products_users( $wpru_users, $post_id );
		} else {
			update_post_meta( $post_id, 'wpru_enable', false );
			update_post_meta( $post_id, 'wpru_users', array() );
			update_post_meta( $post_id, 'wpru_mode', 'restrict' );
			$this->maybe_remove_allowed_products_users( $wpru_users, $post_id );
		}
	}

	/**
	 * After saving, refresh related data
	 *
	 * @param Array $users The list of users.
	 * @param int   $product_id The product_id.
	 **/
	public function refresh_allowed_products_users( $users, $product_id ) {
		foreach ( $users as $user_id ) {
			$allowed_products = WPRU_Filters::get_allowed_products( $user_id );

			if ( in_array( $product_id, $allowed_products ) ) {
				continue;
			}

			$allowed_products[] = $product_id;
			update_user_meta( $user_id, 'wpru_allowed_products', $allowed_products );
		}
	}

	/**
	 * After saving, refresh related data to deactivate
	 *
	 * @param Array $users The list of users.
	 * @param int   $product_id The product_id.
	 **/
	public function maybe_remove_allowed_products_users( $users, $product_id ) {
		foreach ( $users as $user_id ) {
			$allowed_products = WPRU_Filters::get_allowed_products( $user_id );
			$key = array_search( $product_id, $allowed_products );

			if ( $key !== false ) {
				unset( $allowed_products[ $key ] );
			}

			update_user_meta( $user_id, 'wpru_allowed_products', $allowed_products );
		}
	}
}