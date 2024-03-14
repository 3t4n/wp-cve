<?php
/**
 * Widget Profile
 *
 * @package YAHMAN Add-ons
 */


class yahman_addons_profile_widget extends WP_Widget {

	
	function __construct() {

		parent::__construct(
			'ya_profile', // Base ID
			esc_html__( '[YAHMAN Add-ons] Profile', 'yahman-add-ons' ), // Name
			array( 'description' => esc_html__( 'Support profile for Widget', 'yahman-add-ons' ), ) // Args
		);
	}


	/**
	 * Set default settings of the widget
	 */
	private function default_settings() {

		$defaults = array(
			'title'    => esc_html__( 'About me', 'yahman-add-ons'),
			'name' => '',
			'image' => '',
			'image_bg' => '',
			'image_shape'   => 'circle',
			'text' => '',
			'read_more_url' => '',
			'read_more_text' => esc_html__( 'Read More', 'yahman-add-ons' ),
			'read_more_url' => '',
			'read_more_blank' => false,
			'icon_shape' => 'icon_square',
			'icon_size' => 'icon_medium',
			'icon_align'    => 'center',
			'icon_user_color' => '',
			'icon_user_hover_color' => '',
			'icon_tooltip' => false,
		);

		return $defaults;
	}

	public function widget( $args, $instance ) {

		$settings = array();

		$option = get_option('yahman_addons');

		$settings = wp_parse_args( $option['profile'], $this->default_settings() );
		//$settings['title'] = !empty($option['profile']['title']) ? $option['profile']['title'] : esc_html__( 'About me', 'yahman-add-ons' );



		//$settings['name'] = isset($option['profile']['name']) ? $option['profile']['name'] : '';

		//$settings['image'] = isset($option['profile']['image']) ? $option['profile']['image'] : '';

		//$settings['image_bg'] = isset($option['profile']['image_bg']) ? $option['profile']['image_bg'] : '';


		//$settings['image_shape'] = (isset($option['profile']['image_shape']) ? $option['profile']['image_shape'] : '');

		//if($settings['image_shape'] === 'circle') $settings['image_shape'] = ' br50';

		$settings['image_shape']  = $settings['image_shape'] === 'circle' ? ' br50' : '';
		$settings['read_more_blank']  = $settings['read_more_blank'] ? ' target="_blank"' : '';
		//$settings['text'] = isset($option['profile']['text']) ? $option['profile']['text'] : '';

		//$settings['read_more_url'] = isset($option['profile']['read_more_url']) ? $option['profile']['read_more_url'] : '';

		//$settings['read_more_text'] = !empty($option['profile']['read_more_text']) ? $option['profile']['read_more_text'] : esc_html__( 'Read More', 'yahman-add-ons' );

		//$settings['read_more_blank'] = (isset($option['profile']['read_more_blank']) ? ' target="_blank"' : '');


		//$sns_info['icon_shape'] = (isset($option['profile']['icon_shape']) ? $option['profile']['icon_shape'] : 'icon_square');
		//$sns_info['icon_size'] = (isset($option['profile']['icon_size']) ? $option['profile']['icon_size'] : 'icon_medium');

		//$sns_info['icon_align']  = isset($option['profile']['icon_align']) ? $option['profile']['icon_align'] : 'center';

		//$sns_info['icon_user_color']  = (isset($option['profile']['icon_user_color']) ? $option['profile']['icon_user_color'] : '');
		//$sns_info['icon_user_hover_color']  = (isset($option['profile']['icon_user_hover_color']) ? $option['profile']['icon_user_hover_color'] : '');

		$sns_info['icon_shape']  = $settings['icon_shape'];
		$sns_info['icon_size']  = $settings['icon_size'];
		$sns_info['icon_user_color']  = $settings['icon_user_color'];
		$sns_info['icon_user_hover_color']  = $settings['icon_user_hover_color'];
		$sns_info['icon_tooltip']  = $settings['icon_tooltip'];
		$sns_info['icon_tooltip']  = $sns_info['icon_tooltip'] ? ' sns_tooltip' : '';

		$sns_info['widget_id'] = $args['widget_id'];

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

		echo $args['before_widget'];

		if ( $settings['title'] ) {
			echo $args['before_title'] . esc_html( apply_filters('yahman_addons_profile_title', $settings['title'] ) ) . $args['after_title'];
		}//mb20
		require_once YAHMAN_ADDONS_DIR . 'inc/widget/profile_output.php';
		yahman_addons_profile_widget_output($settings);


		$i = 1;
      //$sns_icon = array();
		while($i <= 5){
			$sns_info['share'][$i] = $sns_info['url'][$i] = '';
			$sns_info['icon'][$i] = isset($option['profile']['icon_'.$i]) ? $option['profile']['icon_'.$i] : 'none';
			$sns_info['account'][$i] = isset($option['sns_account'][$option['profile']['icon_'.$i]]) ? $option['sns_account'][$option['profile']['icon_'.$i]] : '';
			if($sns_info['icon'][$i] != 'none')$sns_info['class'] = ' pf_sns_wrap';
			++$i;
		}
		$sns_info['loop'] = 5;
		$sns_info['class'] = $sns_info['icon_align'];

		if($sns_info['class'] != ''){
			require_once YAHMAN_ADDONS_DIR . 'inc/widget/social-output.php';
			yahman_addons_social_output($sns_info);
		}

		echo '</div>';
		echo $args['after_widget'];
	}
	public function form( $instance ) {

	}
	public function update( $new_instance, $old_instance ) {
		$instance = array();

		return $instance;
	}

} // class yahman_addons_profile_widget
