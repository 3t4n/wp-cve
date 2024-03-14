<?php
/**
 * LinkPizza Links widget
 *
 * @link       http://linkpizza.com
 * @since      1.0.0
 *
 * @package    linkPizza_Manager
 * @subpackage widget
 */
class LinkPizza_Manager_Widget extends WP_Widget {


	/**
	 * Registers widget
	 *
	 * @return void
	 */
	public function register_widget() {
		register_widget( 'LinkPizza_Manager_Widget' );
	}

	/**
	 * Sets up the widgets name
	 *
	 * @return LinkPizza_Manager_Widget
	 * @since    1.0.0
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'LinkPizza_Manager_Widget',
			'description' => __( 'Allows you to create a list of links', 'linkpizza-manager' ),
		);
		parent::__construct( 'LinkPizza_Manager_Widget', __( 'LinkPizza Links', 'linkpizza-manager' ), $widget_ops );

		add_action( 'admin_enqueue_scripts', array( $this, 'pzz_load_scripts' ) );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args       Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
	 * @param array $instance   The settings for the particular instance of the widget.
	 */
	public function widget( $args, $instance ) {
		$title  = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'List', 'linkpizza-manager' ) : $instance['title'] );
		$amount = empty( $instance['amount'] ) ? 0 : $instance['amount'];

		for ( $i = 1; $i <= $amount; $i++ ) {
			if ( ! empty( $instance[ 'item' . $i ] ) ) :
				$items[ $i - 1 ]        = $instance[ 'item' . $i ];
				$item_links[ $i - 1 ]   = $instance[ 'item_link' . $i ];
				$item_targets[ $i - 1 ] = isset( $instance[ 'item_target' . $i ] ) ? $instance[ 'item_target' . $i ] : false;
			endif;
		}

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $args['before_widget'];
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $args['before_title'];
		echo esc_html( $title );
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $args['after_title'];

		echo '<ul class="list">';

		if ( ! empty( $items ) ) :
			foreach ( $items as $num => $item ) :
				if ( ! empty( $item ) ) :
					if ( empty( $item_links[ $num ] ) ) :
						echo '<li>' . esc_html( $item ) . '</li>';
					else :
						if ( $item_targets[ $num ] ) :
							// TODO: maybe add rel="noopener to prevent security issues?
							echo '<li><a href="' . esc_url( $item_links[ $num ] ) . '" target="_blank">' . esc_html( $item ) . '</a></li>';
						else :
							echo '<li><a href="' . esc_url( $item_links[ $num ] ) . '">' . esc_html( $item ) . '</a></li>';
						endif;
					endif;
				endif;
			endforeach;
		endif;

		echo '</ul>';

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title'      => '',
				'text'       => '',
				'title_link' => '',
			)
		);
		$title    = wp_strip_all_tags( $instance['title'] );
		$amount   = empty( $instance['amount'] ) ? 3 : $instance['amount'];

		for ( $i = 1; $i <= $amount; $i++ ) {
			$items[ $i ]        = empty( $instance[ 'item' . $i ] ) ? '' : $instance[ 'item' . $i ];
			$item_links[ $i ]   = empty( $instance[ 'item_link' . $i ] ) ? '' : $instance[ 'item_link' . $i ];
			$item_targets[ $i ] = empty( $instance[ 'item_target' . $i ] ) ? 'on' : $instance[ 'item_target' . $i ];
		}
		?>
		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'linkpizza-manager' ); ?></label>
			<input 	class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
					name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text"
					value="<?php echo esc_attr( $title ); ?>" placeholder="<?php echo esc_attr_e( 'Enter the title of the block here', 'linkpizza-manager' ); ?>"/></p>
		<div class="pzz-list">
			<?php
			foreach ( $items as $num => $item ) :
				$item      = esc_attr( $item );
				$item_link = esc_attr( $item_links[ $num ] );
				$checked   = checked( $item_targets[ $num ], 'on', false );
				?>

				<div id="<?php echo esc_attr( $this->get_field_id( $num ) ); ?>" class="list-item">
					<h5 class="moving-handle"><span class="number"><?php echo esc_html( $num ); ?></span>. <span
							class="item-title">
							<?php
							echo esc_html( $item );
							$response = pzz_do_oauth_call_with_refresh_check( PZZ_OIDC_API_BASE_PATH . '/url/isMonetizable?url=' . $item_link . '', array(), false );
							if ( $response ) {
								?>
								<img src="<?php echo esc_url( plugins_url( '../admin/assets/dollar-sign-orange-small.png', __FILE__ ) ); ?>" alt="<?php esc_attr_e( 'Monetized', 'linkpizza-manager' ); ?>" title="<?php esc_attr_e( 'Monetized', 'linkpizza-manager' ); ?>" style="margin: -4px;padding-left: 25px;"/>
								<?php
							} else {
								?>
								<img src="<?php echo esc_url( plugins_url( '../admin/assets/dollar-sign-small.png', __FILE__ ) ); ?>" alt="<?php esc_attr_e( 'Not Monetized', 'linkpizza-manager' ); ?>" title="<?php esc_attr_e( 'Not Monetized', 'linkpizza-manager' ); ?>" style="margin: -4px;padding-left: 25px;"/>
								<?php
							}
							?>
							</span><a class="pzz-action hide-if-no-js"></a></h5>
					<div class="pzz-edit-item">
						<label
							for="<?php echo esc_attr( $this->get_field_id( 'item' . $num ) ); ?>"><?php esc_html_e( 'Text', 'linkpizza-manager' ); ?> <?php esc_html_e( '(required)', 'linkpizza-manager' ); ?></label>
						<input 	class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'item' . $num ) ); ?>"
								name="<?php echo esc_attr( $this->get_field_name( 'item' . $num ) ); ?>" type="text"
								value="<?php echo esc_attr( $item ); ?>"/>
						<label
							for="<?php echo esc_attr( $this->get_field_id( 'item_link' . $num ) ); ?>"><?php esc_html_e( 'Link', 'linkpizza-manager' ); ?></label>
						<input 	class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'item_link' . $num ) ); ?>"
								name="<?php echo esc_attr( $this->get_field_name( 'item_link' . $num ) ); ?>" type="text"
								value="<?php echo esc_attr( $item_link ); ?>" placeholder="https://"/>
						<input 	type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'item_target' . $num ) ); ?>"
								id="<?php echo esc_attr( $this->get_field_id( 'item_target' . $num ) ); ?>"
								<?php echo $checked; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> />
						<label
							for="<?php echo esc_attr( $this->get_field_id( 'item_target' . $num ) ); ?>"><?php echo esc_html_e( 'Open in new window', 'linkpizza-manager' ); ?></label>
						<a class="pzz-delete hide-if-no-js"><?php esc_html_e( 'Remove', 'linkpizza-manager' ); ?>
						</a>
					</div>
				</div>

				<?php
			endforeach;

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized, WordPress.Security.ValidatedSanitizedInput.MissingUnslash
			if ( isset( $_GET['editwidget'] ) && $_GET['editwidget'] ) :
				?>
				<table class='widefat'>
					<thead>
					<tr>
						<th><?php esc_html_e( 'Item', 'linkpizza-manager' ); ?></th>
						<th><?php esc_html_e( 'Position/Action', 'linkpizza-manager' ); ?></th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ( $items as $num => $item ) : ?>
						<tr>
							<td><?php echo esc_html( $item ); ?></td>
							<td>
								<select id="<?php echo esc_attr( $this->get_field_id( 'position' . $num ) ); ?>"
										name="<?php echo esc_attr( $this->get_field_name( 'position' . $num ) ); ?>">
									<option><?php esc_html_e( '&mdash; Select &mdash;', 'linkpizza-manager' ); ?></option>
									<?php $number_of_items = count( $items ); ?>
									<?php for ( $i = 1; $i <= $number_of_items; $i++ ) : ?>
										<option value="<?php echo esc_attr( $i ); ?>" <?php selected( $num, $i ); ?>><?php echo esc_html( $i ); ?></option>
									<?php endfor; ?>

									<option value="-1"><?php esc_html_e( 'Delete', 'linkpizza-manager' ); ?></option>
								</select>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
				</table>

				<div class="pzz-row">
					<input 	type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'new_item' ) ); ?>"
							id="<?php echo esc_attr( $this->get_field_id( 'new_item' ) ); ?>"/> <label
							for="<?php echo esc_attr( $this->get_field_id( 'new_item' ) ); ?>"><?php esc_html_e( 'Add Extra Link', 'linkpizza-manager' ); ?></label>
				</div>
			<?php endif; ?>

		</div>
		<div class="pzz-row hide-if-no-js">
			<a class="pzz-add button-secondary"><?php esc_html_e( 'Add Item', 'linkpizza-manager' ); ?></a>
		</div>

		<input 	type="hidden" id="<?php echo esc_attr( $this->get_field_id( 'amount' ) ); ?>" class="amount"
				name="<?php echo esc_attr( $this->get_field_name( 'amount' ) ); ?>" value="<?php echo esc_attr( $amount ); ?>"/>
		<input 	type="hidden" id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" class="order"
				name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>"
				value="<?php echo esc_attr( implode( ',', range( 1, $amount ) ) ); ?>"/>
		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options.
	 * @param array $old_instance The previous options.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance['title'] = wp_strip_all_tags( $new_instance['title'] );
		$amount            = $new_instance['amount'];

		if ( isset( $new_instance['position1'] ) ) {
			for ( $i = 1; $i <= $new_instance['amount']; $i++ ) {
				if ( -1 !== $new_instance[ 'position' . $i ] ) {
					$position[ $i ] = $new_instance[ 'position' . $i ];
				} else {
					$amount--;
				}
			}
			if ( $position ) {
				asort( $position );
				$order = array_keys( $position );
				if ( wp_strip_all_tags( $new_instance['new_item'] ) ) {
					$amount++;
					array_push( $order, $amount );
				}
			}
		} else {
			$order = explode( ',', $new_instance['order'] );
			foreach ( $order as $key => $order_str ) {
				$num = strrpos( $order_str, '-' );
				if ( false !== $num ) {
					$order[ $key ] = substr( $order_str, $num + 1 );
				}
			}
		}

		if ( $order ) {
			foreach ( $order as $i => $item_num ) {
				$instance[ 'item' . ( $i + 1 ) ]        = empty( $new_instance[ 'item' . $item_num ] ) ? '' : wp_strip_all_tags( $new_instance[ 'item' . $item_num ] );
				$instance[ 'item_link' . ( $i + 1 ) ]   = empty( $new_instance[ 'item_link' . $item_num ] ) ? '' : $this->normalize_url( wp_strip_all_tags( $new_instance[ 'item_link' . $item_num ] ) );
				$instance[ 'item_target' . ( $i + 1 ) ] = empty( $new_instance[ 'item_target' . $item_num ] ) ? '' : wp_strip_all_tags( $new_instance[ 'item_target' . $item_num ] );
			}
		}

		$instance['amount'] = $amount;
		return $instance;
	}

	/**
	 * Loads required scripts and stylesheets
	 *
	 * @param string $hook The current admin page.
	 * @return void
	 */
	public function pzz_load_scripts( $hook ) {
		if ( 'widgets.php' !== $hook ) {
			return;
		}
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! isset( $_GET['editwidget'] ) ) {
			wp_enqueue_script( 'pzz-link-sort-js', plugin_dir_url( __FILE__ ) . 'js/pzz-link-sort.js', array( 'jquery' ), PZZ_VERSION, true );
		}
		wp_enqueue_style( 'pzz-link-css', plugin_dir_url( __FILE__ ) . 'css/pzz-link-css.css', array(), PZZ_VERSION );
	}

	/**
	 * Normalizes url (if http is missing it will be added)
	 *
	 * @param string $url the url to be checked.
	 * @return string the url with http:// prefixed.
	 */
	public function normalize_url( $url ) {
		if ( ! $this->startsWith( $url, '/' ) && ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
			if ( filter_var( 'http://' . $url, FILTER_VALIDATE_URL ) ) {
				return 'http://' . $url;
			}
			return $url;
		}
		return $url;
	}

	/**
	 * Checks if the string $string starts with $tofind.
	 *
	 * @param string $string string to search in.
	 * @param string $tofind string to search for.
	 * @return true|false true if the string $string starts with $tofind, false otherwise.
	 */
	public function startsWith( $string, $tofind ) {
		return '' === $tofind || strrpos( $string, $tofind, -strlen( $string ) ) !== false;
	}
}
