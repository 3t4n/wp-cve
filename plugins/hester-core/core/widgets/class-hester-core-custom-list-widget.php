<?php
/**
 * Hester Core Widget: Custom List.
 *
 * @package Hester Core
 * @author  Peregrine Themes <peregrinethemes@gmail.com>
 * @since   1.0.0
 */
class Hester_Core_Custom_List_Widget extends WP_Widget {

	/**
	 * Holds widget settings defaults, populated in constructor.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $defaults;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	function __construct() {

		// Widget defaults.
		$this->defaults = array(
			'title'   => '',
			'content' => '',
			'items'   => array(),
		);

		// Widget Slug.
		$widget_slug = 'hester-core-custom-list-widget';

		// Widget basics.
		$widget_ops = array(
			'classname'   => $widget_slug,
			'description' => _x( 'A list of items with optional icon and separator.', 'Widget', 'hester-core' ),
		);

		// Widget controls.
		$control_ops = array(
			'id_base' => $widget_slug,
		);

		// load widget.
		parent::__construct( $widget_slug, _x( '[Hester] Custom List', 'Widget', 'hester-core' ), $widget_ops, $control_ops );

		// Hook into dynamic styles.
		add_filter( 'hester_dynamic_styles', array( $this, 'dynamic_styles' ) );
	}

	/**
	 * Outputs the HTML for this widget.
	 *
	 * @since 1.0.0
	 * @param array $args An array of standard parameters for widgets in this theme.
	 * @param array $instance An array of settings for this widget instance.
	 */
	function widget( $args, $instance ) {

		// Merge with defaults.
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		echo wp_kses_post( $args['before_widget'] );

		do_action( 'hester_before_custom_list_widget', $instance );

		// Title.
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		if ( ! empty( $instance['content'] ) ) {
			$instance['content'] = apply_filters( 'hester_dynamic_strings', $instance['content'] );
			echo '<div class="hester-custom-list-widget-desc">' . wp_kses_post( wpautop( $instance['content'], true ) ) . '</div>';
		}

		if ( ! empty( $instance['items'] ) ) {

			echo '<div class="hester-custom-list-widget-items">';

			foreach ( $instance['items'] as $entry ) {

				$separator_class = $entry['separator'] ? 'hester-clw-sep ' : '';

				echo '<div class="' . esc_attr( $separator_class ) . 'hester-custom-list-widget-item">';

				if ( $entry['icon'] ) {

					$entry['icon'] = $this->process_icon( $entry['icon'] );

					if ( false !== strpos( $entry['icon'], '<svg' ) ) {
						$hester_get_allowed_html_tags =  hester_core()->theme_name . '_get_allowed_html_tags';
						echo wp_kses( $entry['icon'], $hester_get_allowed_html_tags( 'svg' ) );
					} else {
						echo '<i class="hester-widget-icon ' . esc_attr( $entry['icon'] ) . '" aria-hidden="true"></i>';
					}
				}

				if ( $entry['description'] ) {
					echo '<span class="hester-entry">' . wp_kses_post( nl2br( $entry['description'] ) ) . '</span>';
				}

				echo '</div>';
			}

			echo '</div>';
		}

		do_action( 'hester_after_custom_list_widget', $instance );

		echo wp_kses_post( $args['after_widget'] );
	}

	/**
	 * Deals with the settings when they are saved by the admin. Here is
	 * where any validation should be dealt with.
	 *
	 * @since 1.0.0
	 * @param array $new_instance An array of new settings as submitted by the admin.
	 * @param array $old_instance An array of the previous settings.
	 * @return array The validated and (if necessary) amended settings
	 */
	function update( $new_instance, $old_instance ) {

		$instance            = array();
		$instance['title']   = wp_strip_all_tags( $new_instance['title'] );
		$instance['content'] = isset( $new_instance['content'] ) ? wp_kses_post( $new_instance['content'] ) : '';
		$instance['items']   = array();

		if ( isset( $new_instance['items'] ) ) {
			foreach ( $new_instance['items'] as $entry ) {

				// Sanitize entry values.
				$new_entry = array(
					'icon'        => '',
					'description' => isset( $entry['description'] ) ? wp_kses_post( trim( $entry['description'] ) ) : '',
					'separator'   => isset( $entry['separator'] ) && $entry['separator'] ? true : false,
				);

				if ( isset( $entry['icon'] ) ) {
					$hester_get_allowed_html_tags =  hester_core()->theme_name . '_get_allowed_html_tags';
					$new_entry['icon'] = wp_kses( $this->process_icon( $entry['icon'] ), $hester_get_allowed_html_tags( 'svg' ) );
				}

				if ( ! empty( $new_entry['icon'] ) || ! empty( $new_entry['description'] ) || true === $new_entry['separator'] ) {
					$instance['items'][] = $new_entry;
				}
			}
		}

		return $instance;
	}

	/**
	 * Displays the form for this widget on the Widgets page of the WP Admin area.
	 *
	 * @since 1.0.0
	 * @param array $instance An array of the current settings for this widget.
	 * @return void
	 */
	function form( $instance ) {

		// Merge with defaults.
		$instance = wp_parse_args( (array) $instance, $this->defaults );
		$empty    = empty( $instance['items'] ) ? ' empty' : '';
		?>

		<div class="hester-repeatable-widget hester-custom-list-widget hester-widget">
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
					<?php _ex( 'Title:', 'Widget', 'hester-core' ); ?>
				</label>
				<input type="text" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat"/>
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'content' ) ); ?>">
					<?php _ex( 'Text Before:', 'Widget', 'hester-core' ); ?>
				</label>
				
				<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'content' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'content' ) ); ?>" rows="3"><?php echo wp_kses_post( $instance['content'] ); ?></textarea>

				<em class="description hester-description">
					<?php
					echo wp_kses_post(
						sprintf(
							// _x( 'HTML tags and %1$sdynamic strings%2$s allowed.', 'Widget', 'hester-core' ),
							_x( 'HTML tags allowed.', 'Widget', 'hester-core' ),
							'<a href="http://docs.peregrine-themes.com/" rel="nofollow noreferrer" target="_blank">',
							'</a>'
						)
					);
					?>
				</em>
			</p>

			<div class="hester-repeatable-container<?php echo esc_attr( $empty ); ?>">

				<?php
				if ( ! empty( $instance['items'] ) ) {
					foreach ( $instance['items'] as $index => $entry ) {
						?>
						<div class="hester-repeatable-item">
							
							<!-- Repeatable title -->
							<div class="hester-repeatable-item-title">
								<?php
								_ex( 'List Item', 'Widget', 'hester-core' );

								if ( ! empty( $entry['description'] ) ) {
									echo ': <span class="in-widget-title">' . esc_html( wp_trim_words( $entry['description'], 2, '...' ) ) . '</span>';
								}
								?>

								<div class="hester-repeatable-indicator">
									<span class="accordion-section-title" aria-hidden="true"></span>
								</div>
							</div>
							
							<!-- Repeatable content -->
							<div class="hester-repeatable-item-content">
								
								<p>
									<label for="<?php echo esc_attr( $this->get_field_id( 'items' ) . '-' . $index . '-icon' ); ?>">
										<?php _ex( 'Icon', 'Widget', 'hester-core' ); ?>
									</label>
									
									<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'icon' ) . '-' . $index . '-icon' ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'items' ) ); ?>[<?php echo absint( $index ); ?>][icon]" rows="3"><?php echo wp_kses_post( $entry['icon'] ); ?></textarea>
									<em class="description hester-description">
										<?php echo wp_kses_post( _x( 'Enter icon SVG code.', 'Widget', 'hester-core' ) ); ?>
									</em>
								</p>
								
								<p>
									<label for="<?php echo esc_attr( $this->get_field_id( 'items' ) . '-' . $index . '-description' ); ?>">
										<?php _ex( 'Item Description', 'Widget', 'hester-core' ); ?>
									</label>
									<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'items' ) . '-' . $index . '-description' ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'items' ) ); ?>[<?php echo absint( $index ); ?>][description]" rows="3"><?php echo wp_kses_post( $entry['description'] ); ?></textarea>
									<em class="description hester-description">
										<?php
										echo wp_kses_post(
											_x( 'HTML tags allowed.', 'Widget', 'hester-core' )
										);
										?>
									</em>
								</p>

								<p>
									<input type="checkbox" id="<?php echo esc_attr( $this->get_field_name( 'items' ) ); ?>[<?php echo $index; ?>][separator]" name="<?php echo $this->get_field_name( 'items' ); ?>[<?php echo absint( $index ); ?>][separator]" <?php checked( true, $entry['separator'] ); ?>/>
									<label for="<?php echo esc_attr( $this->get_field_name( 'items' ) ); ?>[<?php echo absint( $index ); ?>][separator]"><?php _ex( 'Add bottom separator', 'Widget', 'hester-core' ); ?></label>
								</p>

								<!-- Remove -->
								<button type="button" class="remove-repeatable-item button-link button-link-delete"><?php _ex( 'Remove', 'Widget', 'hester-core' ); ?></button>
							</div>

						</div>
						<?php
					}
				}
				?>

				<div class="hester-svg-icon hester-hide-if-not-empty">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12" y2="8"></line></svg>
				</div>

				<h5 class="hester-hide-if-not-empty">
					<?php _ex( 'No Items Found', 'Widget', 'hester-core' ); ?>
				</h5>

				<p class="hester-hide-if-not-empty">
					<?php _ex( 'Please add new items to see more options', 'Widget', 'hester-core' ); ?>
				</p>

				<div class="hester-repeatable-footer">
					<a href="#" class="button secondary add-new-item" data-index="<?php echo intval( count( $instance['items'] ) ); ?>" data-widget-name="<?php echo $this->get_field_name( 'items' ); ?>" data-widget-id="<?php echo $this->get_field_id( 'items' ); ?>"><?php esc_html_e( 'Add New', 'hester-core' ); ?></a>
				</div>
			</div>
			<!-- END .hester-repeatable-container -->

			<?php
			if ( function_exists( 'hester_help_link' ) ) {
				hester_help_link(
					array(
						'link' => 'http://docs.peregrine-themes.com/',
					)
				);
			}
			?>

		</div>
		<!-- END .hester-custom-list-widget -->

		<?php
	}

	/**
	 * Hook into Hester dynamic styles.
	 *
	 * @param  string $css Generated CSS code.
	 * @return string Modified CSS code.
	 */
	function dynamic_styles( $css ) {
		$css .= '.hester-core-custom-list-widget .hester-icon, .hester-core-custom-list-widget svg {
			fill: ' . hester_option( 'accent_color' ) . ';
			color: ' . hester_option( 'accent_color' ) . ';
		}';

		return $css;
	}

	function process_icon( $icon ) {

		// Icon is not an SVG.
		if ( false === strpos( $icon, '<svg' ) ) {

			$_icon = trim( str_replace( 'hester-icon', '', $icon ) );
			$_icon = trim( str_replace( 'hester-', '', $_icon ) );

			$function_name =  hester_core()->theme_name;

			$svg_icon = $function_name()->icons->get_svg( $_icon );

			if ( $svg_icon ) {
				$icon = $svg_icon;
			} elseif ( file_exists( HESTER_CORE_PLUGIN_DIR . '/assets/svg/' . $_icon . '.svg' ) ) {
				$icon = file_get_contents( HESTER_CORE_PLUGIN_DIR . '/assets/svg/' . $_icon . '.svg' );
			}
		}

		return $icon;
	}
}
