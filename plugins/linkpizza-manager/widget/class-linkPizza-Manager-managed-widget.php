<?php
/**
 * LinkPizza Automated Links Widget
 *
 * @package    linkPizza_Manager
 * @subpackage widget
 */

// Url for the ads.
define( 'PZZ_WIDGET_JSON_URL', 'https://zeef.io/images/custom/widgets/widgets.json' );

/**
 * LinkPizza Automated Links Widget Class
 *
 * @link       http://linkpizza.com
 * @since      1.0.0
 */
class LinkPizza_Manager_Managed_Widget extends WP_Widget {

	/**
	 * Registers widget
	 *
	 * @return void
	 */
	public function register_widget() {
		register_widget( 'LinkPizza_Manager_Managed_Widget' );
	}

	/**
	 * Sets up the widgets name
	 *
	 * @return LinkPizza_Manager_Managed_Widget
	 * @since    1.0.0
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'LinkPizza_Manager_Managed_Widget',
			'description' => __( 'use a pre-selected list of links', 'linkpizza-manager' ),
		);
		parent::__construct( 'LinkPizza_Manager_Managed_Widget', __( 'LinkPizza Automatic Ads', 'linkpizza-manager' ), $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args       Display arguments including 'before_title', 'after_title', 'before_widget', and 'after_widget'.
	 * @param array $instance   The settings for the particular instance of the widget.
	 */
	public function widget( $args, $instance ) {
		if ( ! empty( $instance['pzz_block_id'] ) && ! empty( $instance['pzz_block_height'] ) && ! empty( $instance['pzz_block_width'] ) ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $args['before_widget'];
			$user_id    = false !== get_option( 'pzz_id' ) ? get_option( 'pzz_id' ) : 13003;
			$zeef_url   = '//zeef.io/block/' . $instance['pzz_block_id'];
			$iframe_url = add_query_arg(
				array(
					'lpuid'        => $user_id,
					'max_links'    => '7',
					'show_curator' => '0',
					'show_logo'    => '0',
				),
				$zeef_url
			);
			?>
			<!-- ZEEF widget start -->
			<iframe id="widget_pzz" src="<?php echo esc_url( $iframe_url ); ?>" width="<?php echo esc_attr( $instance['pzz_block_width'] ); ?>" height="<?php echo esc_attr( $instance['pzz_block_height'] ); ?>" frameborder="0" scrolling="no"></iframe><!-- ZEEF widget end -->
			<?php

			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $args['after_widget'];
		}
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options.
	 */
	public function form( $instance ) {
		if ( ! empty( $instance['pzz_block_id'] ) ) {
			$pzz_block_id = $instance['pzz_block_id'];
		} else {
			$pzz_block_id = 0;
		}

		if ( ! empty( $instance['pzz_block_height'] ) ) {
			$pzz_block_height = $instance['pzz_block_height'];
		} else {
			$pzz_block_height = 250;
		}

		if ( ! empty( $instance['pzz_block_width'] ) ) {
			$pzz_block_width = $instance['pzz_block_width'];
		} else {
			$pzz_block_width = 300;
		}

		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php esc_attr_e( 'List', 'linkpizza-manager' ); ?>
				<select class='widefat' id="<?php echo esc_attr( $this->get_field_id( 'pzz_block_id' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'pzz_block_id' ) ); ?>" type="text">
						<?php
							$response = wp_remote_get( PZZ_WIDGET_JSON_URL );
						if ( is_wp_error( $response ) ) :
								esc_html_e( 'Widgets could not be loaded, please try again later', 'linkpizza-manager' );
						else :
							try {
								$json = json_decode( $response['body'] );
								if ( ! empty( $json ) ) :
									foreach ( $json as $widget ) :
										?>
											<option value="<?php echo esc_attr( $widget->id ); ?>" <?php selected( $pzz_block_id, $widget->id ); ?>>
											<?php echo esc_html( $widget->title ); ?>
											</option>
											<?php
									endforeach;
									?>
										</select> </label>
										<?php
								else :
									?>
										</select> </label>
										<?php
											esc_html_e( 'Widgets could not be loaded, please try again later', 'linkpizza-manager' );
								endif;
							} catch ( Exception $ex ) {
								esc_html_e( 'Widgets could not be loaded, please try again later', 'linkpizza-manager' );
							}
						endif;
						?>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'label' ) ); ?>"><?php esc_attr_e( 'Height (absolute or in %)', 'linkpizza-manager' ); ?>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pzz_block_height' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pzz_block_height' ) ); ?>"  type="text" value="<?php echo esc_attr( $pzz_block_height ); ?>">
			</label>
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'label' ) ); ?>"><?php esc_html_e( 'Width (absolute or in %)', 'linkpizza-manager' ); ?>
				<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pzz_block_width' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pzz_block_width' ) ); ?>"  type="text" value="<?php echo esc_attr( $pzz_block_width ); ?>">
			</label>
		</p>

		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options.
	 * @param array $old_instance The previous options.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		if ( ! empty( $new_instance['pzz_block_id'] ) ) {
			$instance['pzz_block_id'] = $new_instance['pzz_block_id'];
		}
		if ( ! empty( $new_instance['pzz_block_width'] ) && preg_match( '/^[1-9]+\d*%?$/', $new_instance['pzz_block_width'] ) ) {
			$instance['pzz_block_width'] = $new_instance['pzz_block_width'];
		}
		if ( ! empty( $new_instance['pzz_block_height'] ) && preg_match( '/^[1-9]+\d*%?$/', $new_instance['pzz_block_height'] ) ) {
			$instance['pzz_block_height'] = $new_instance['pzz_block_height'];
		}
		return $instance;
	}

}
