<?php

/**
 * Calls the class on the post edit screen.
 */
function PMCS_Order_Metabox() {
	new PMCS_Order_Metabox();
}

if ( is_admin() ) {
	add_action( 'load-post.php', 'PMCS_Order_Metabox' );
	add_action( 'load-post-new.php', 'PMCS_Order_Metabox' );
}

/**
 * The Class.
 */
class PMCS_Order_Metabox {

	protected $post_type = 'shop_order';

	/**
	 * Hook into the appropriate actions when the class is constructed.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post', array( $this, 'save' ), 95 );
	}

	/**
	 * Adds the meta box container.
	 *
	 * @param string $post_type
	 */
	public function add_meta_box( $post_type ) {
		if ( $post_type == $this->post_type ) {
			add_meta_box(
				'pmcs_currency',
				__( 'Order Currency', 'pmcs' ),
				array( $this, 'render_meta_box_content' ),
				$post_type,
				'side',
				'high'
			);
		}
	}

	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save( $post_id ) {

		/*
		 * We need to verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST['pmcs_currency_nonce'] ) ) {
			return $post_id;
		}

		$nonce = $_POST['pmcs_currency_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'pmcs_currency' ) ) {
			return $post_id;
		}

		/*
		 * If this is an autosave, our form has not been submitted,
		 * so we don't want to do anything.
		 */
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Check the user's permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}

		/* OK, it's safe for us to save the data now. */

		// Sanitize the user input.
		$currency_code = sanitize_text_field( $_POST['_pmcs_order_currency'] );

		$rate = get_post_meta( $post_id, '_currency_rate', true );
		if ( ! $rate ) {
			update_post_meta( $post_id, '_currency_rate', pmcs()->switcher->get_rate( $currency_code ) );
			update_post_meta( $post_id, '_base_currency', pmcs()->switcher->get_woocommerce_currency() );
		}

		update_post_meta( $post_id, '_currency_checkout', $currency_code );

		// Update the meta field.
		update_post_meta( $post_id, '_order_currency', $currency_code );
	}


	/**
	 * Render Meta Box content.
	 *
	 * @param WP_Post $post The post object.
	 */
	public function render_meta_box_content( $post ) {

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'pmcs_currency', 'pmcs_currency_nonce' );

		// Use get_post_meta to retrieve an existing value from the database.
		$value = get_post_meta( $post->ID, '_order_currency', true );

		// Display the form, using the current value.
		$list = pmcs()->switcher->get_currencies();
		$currencies = pmcs()->switcher->get_woocommerce_currencies();

		$default_value = '';
		$default_label = '';
		if ( ! empty( $value ) ) {
			if ( ! isset( $list[ $value ] ) ) {
				$default_value = $value;
				$default_label = $value . ' - ' . $currencies[ $value ];
			}
		}

		?>
		<select name="_pmcs_order_currency" id="pmcs_order_currency">
			<?php
			if ( $default_value ) {
				?>
				<option value="<?php echo esc_attr( $default_value ); ?>"><?php echo esc_html( $default_label ); ?></option>
				<?php
			}
			?>
			<?php foreach ( (array) $list as $code => $currency ) { ?>
			<option <?php echo selected( $value, $code ); ?> value="<?php echo esc_attr( $code ); ?>"><?php echo $code . ' - ' . esc_attr( $currency['display_text'] ); ?></option>
			<?php } ?>
		</select>
		<?php
	}
}
