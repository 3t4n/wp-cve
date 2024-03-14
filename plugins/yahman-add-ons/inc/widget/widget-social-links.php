<?php
/**
 * Widget Social Link
 *
 * @package YAHMAN Add-ons
 */


class yahman_addons_social_links_widget extends WP_Widget {

	
	function __construct() {
		add_action('admin_enqueue_scripts', array($this, 'scripts'));

		parent::__construct(
			'ya_social_links', // Base ID
			esc_html__( '[YAHMAN Add-ons] Social Links', 'yahman-add-ons' ), // Name
			array( 'description' => esc_html__( 'Support Social Links for Widget', 'yahman-add-ons' ), ) // Args
		);
	}

	/**
	 * Set default settings of the widget
	 */
	private function default_settings() {

		$defaults = array(
			'display_style'    => 'icon_square',
			'icon_size'    => '',
			'icon_user_color'    => '',
			'icon_user_hover_color'    => '',
			'icon_tooltip'    => ' sns_tooltip',
			'title'    => '',
			'icon_align'    => 'center',
		);

		return $defaults;
	}

	public function widget( $args, $instance ) {

		$option = get_option('yahman_addons');

		$settings = wp_parse_args( $instance, $this->default_settings() );

		$title = esc_html( ! empty( $instance['title'] ) ? $instance['title'] : '' );
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$sns_info['icon_shape']  = ! empty( $instance['display_style'] ) ? $instance['display_style'] : 'icon_square';
		$sns_info['icon_size']  = ! empty( $instance['icon_size'] ) ? $instance['icon_size'] : '';

		$sns_info['icon_user_color']  = ! empty( $instance['icon_user_color'] ) ? $instance['icon_user_color'] : '';
		$sns_info['icon_user_hover_color']  = ! empty( $instance['icon_user_hover_color'] ) ? $instance['icon_user_hover_color'] : '';

		$sns_info['icon_tooltip']  = ! empty( $instance['icon_tooltip'] ) ? ' sns_tooltip' : '';

		$i = 0;
		while($i++ < 10){
			$sns_info['share'][$i] = $sns_info['url'][$i] = '';
			$sns_info['icon'][$i] = ! empty( $instance['icon_'.$i] ) ? $instance['icon_'.$i] : 'none';
			$sns_info['account'][$i] = isset($option['sns_account'][$sns_info['icon'][$i]]) ? $option['sns_account'][$sns_info['icon'][$i]] : '';
		}

		$sns_info['icon_align'] = ' jc_c';

		switch ($settings['icon_align']){
			case 'left':
			$sns_info['icon_align'] = 'jc_fs';
			break;

			case 'right':
			$sns_info['icon_align'] = ' jc_fe';
			break;

			case 'space_between':
			$sns_info['icon_align'] = ' jc_sb';
			break;

			case 'space_around':
			$sns_info['icon_align'] = ' jc_sa';
			break;

			default:
		}

		$sns_info['loop'] = 10;
		$sns_info['class'] = ' '.$sns_info['icon_align'];

		$sns_info['widget_id'] = $args['widget_id'];

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . esc_html($title) . $args['after_title'];
		}
		echo '<div class="social_list">';

		require_once YAHMAN_ADDONS_DIR . 'inc/widget/social-output.php';
		yahman_addons_social_output($sns_info);

		echo '</div>';
		echo $args['after_widget'];
	}
	public function form( $instance ) {

		$settings = wp_parse_args( $instance, $this->default_settings() );

		//$settings['title'] = ! empty( $instance['title'] ) ? $instance['title'] : '';
		//$settings['display_style']  = ! empty( $instance['display_style'] ) ? $instance['display_style'] : 'icon_square';
		//$settings['icon_size']  = ! empty( $instance['icon_size'] ) ? $instance['icon_size'] : '';
		//$settings['icon_tooltip']  = ! empty( $instance['icon_tooltip'] ) ? $instance['icon_tooltip'] : '';
		//$settings['icon_user_color']  = ! empty( $instance['icon_user_color'] ) ? $instance['icon_user_color'] : '';
		//$settings['icon_user_hover_color']  = ! empty( $instance['icon_user_hover_color'] ) ? $instance['icon_user_hover_color'] : '';

		$i = 0;
		while($i++ < 10){
			$settings['icon_'.$i] = ! empty( $instance['icon_'.$i] ) ? $instance['icon_'.$i] : 'none';
		}


		require_once YAHMAN_ADDONS_DIR . 'inc/social-list.php';

		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'yahman-add-ons' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'social_links_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['title'] ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'display_style' ) ); ?>">
				<?php esc_html_e( 'Display style', 'yahman-add-ons' ); ?>
			</label><br />
			<select id="<?php echo esc_attr( $this->get_field_id( 'display_style' )); ?>" name="<?php echo esc_attr( $this->get_field_name( 'display_style' )); ?>">
				<?php
				foreach (yahman_addons_social_shape_list() as $key => $value) {
					echo '<option '. selected( $settings['display_style'], $key ) .' value="'.esc_attr($key).'" >'.esc_html($value).'</option>';
				}
				?>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'icon_align' ) ); ?>">
				<?php esc_html_e( 'Align', 'yahman-add-ons' ); ?>
			</label><br />
			<select id="<?php echo esc_attr( $this->get_field_id( 'icon_align' )); ?>" name="<?php echo esc_attr( $this->get_field_name( 'icon_align' )); ?>">
				<?php
				foreach (yahman_addons_social_align_list() as $key => $value) {
					echo '<option '. selected( $settings['icon_align'], $key ) .' value="'.esc_attr($key).'" >'.esc_html($value).'</option>';
				}
				?>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'icon_size' ) ); ?>">
				<?php esc_html_e( 'Icon Size', 'yahman-add-ons' ); ?>
			</label><br />
			<select id="<?php echo esc_attr( $this->get_field_id( 'icon_size' )); ?>" name="<?php echo esc_attr( $this->get_field_name( 'icon_size' )); ?>">
				<?php
				foreach (yahman_addons_social_size_list() as $key => $value) {
					echo '<option '. selected( $settings['icon_size'], $key ) .' value="'.esc_attr($key).'" >'.esc_html($value).'</option>';
				}
				?>
			</select>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'icon_user_color' ) ); ?>" style="display:block;">
				<?php esc_html_e( 'Specifies the color of the icon.', 'yahman-add-ons'  ); ?>
			</label>
			<input class="ya_color-picker" id="<?php echo esc_attr( $this->get_field_id( 'icon_user_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'icon_user_color' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['icon_user_color'] ); ?>" data-alpha-enabled="true" data-alpha-color-type="hex" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'icon_user_hover_color' ) ); ?>" style="display:block;">
				<?php esc_html_e( 'Specifies the color of hover.', 'yahman-add-ons'  ); ?>
			</label>
			<input class="ya_color-picker" id="<?php echo esc_attr( $this->get_field_id( 'icon_user_hover_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'icon_user_hover_color' ) ); ?>" type="text" value="<?php echo esc_attr( $settings['icon_user_hover_color'] ); ?>" data-alpha-enabled="true" data-alpha-color-type="hex" />
		</p>

		<p>
			<input id="<?php echo esc_attr( $this->get_field_id( 'icon_tooltip' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'icon_tooltip' ) ); ?>" type="checkbox"  <?php checked( $settings['icon_tooltip'] ); ?>/>
			<label for="<?php echo esc_attr( $this->get_field_id( 'icon_tooltip' ) ); ?>">
				<?php esc_html_e( 'Tool tip', 'yahman-add-ons' ); ?>
			</label>
		</p>

		<p>
			<span class="customize-control-title"><?php esc_html_e( 'How to show social icon', 'yahman-add-ons' ) ?></span>
		</p>
		<?php

		$setting_url = esc_url(admin_url('options-general.php?page=yahman-add-ons'));

		
		echo sprintf(esc_html__( 'You must register your %1$s before you can show social links.', 'yahman-add-ons' ),'<a href="'.esc_url($setting_url).'">'.esc_html__( 'Social Account', 'yahman-add-ons' ).'</a>'); ?>


		<?php
		$i = 0;
		while($i++ < 10){
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'icon_'.$i ) ); ?>">
					<?php  echo sprintf(esc_html__( 'Social Icon #%s', 'yahman-add-ons'),esc_html($i)); ?>
				</label><br />
				<select id="<?php echo esc_attr($this->get_field_id( 'icon_'.$i )); ?>" name="<?php echo esc_attr($this->get_field_name( 'icon_'.$i )); ?>">
					<?php
					foreach(yahman_addons_social_name_list() as $account => $account_info){
						$selected = selected( $settings['icon_'.$i], $account, false );
						echo '<option '.esc_attr($selected).' value="'.esc_attr($account).'" >'.esc_html($account_info['name']).'</option>';
					}
					?>
				</select>
			</p>



			<?php
		}
	}




	public function update( $new_instance, $old_instance ) {
		$instance = array();

		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['display_style'] = ! empty( $new_instance['display_style'] ) ? esc_attr( $new_instance['display_style'] ) : 'icon_square';
		$instance['icon_align'] = ! empty( $new_instance['icon_align'] ) ? esc_attr( $new_instance['icon_align'] ) : 'center';
		$instance['icon_size'] = ! empty( $new_instance['icon_size'] ) ? esc_attr( $new_instance['icon_size'] ) : '';
		$instance['icon_user_color'] = ! empty( $new_instance['icon_user_color'] ) ? esc_attr( $new_instance['icon_user_color'] ) : '';
		$instance['icon_user_hover_color'] = ! empty( $new_instance['icon_user_hover_color'] ) ? esc_attr( $new_instance['icon_user_hover_color'] ) : '';
		$instance['icon_tooltip'] = ! empty( $new_instance['icon_tooltip'] ) ? esc_attr( $new_instance['icon_tooltip'] ) : '';
		$i = 0;
		while($i++ < 10){
			$instance['icon_'.$i] = ! empty( $new_instance['icon_'.$i] ) ? esc_attr( $new_instance['icon_'.$i] ) : 'none';
		}
		return $instance;
	}

	public function scripts($hook){
		if ($hook == 'widgets.php' || $hook == 'customize.php') {

			wp_enqueue_style( 'wp-color-picker');
			wp_enqueue_script( 'wp-color-picker');

			wp_enqueue_script('yahman_addons_widget-color-picker', YAHMAN_ADDONS_URI . 'assets/js/customizer/color-picker-widget.min.js', array('wp-color-picker'));

			wp_register_script('wp-color-picker-alpha',YAHMAN_ADDONS_URI . 'assets/js/customizer/wp-color-picker-alpha.min.js', array('wp-color-picker'), null , true );
			wp_add_inline_script(
				'wp-color-picker-alpha',
				'jQuery( function() { jQuery( ".color-picker" ).wpColorPicker(); } );'
			);
			wp_enqueue_script( 'wp-color-picker-alpha' );
		}


	}


} // class yahman_addons_social_links_widget
