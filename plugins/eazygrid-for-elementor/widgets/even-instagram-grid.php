<?php

namespace EazyGrid\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;

class Even_Instagram_Grid extends \EazyGrid\Elementor\Base\EazyGrid_Base {

	public function get_title() {
		return __( 'Even Instagram Grid', 'eazygrid-elementor' );
	}

	public function get_icon() {
		return 'ezicon ezicon-image-even';
	}

	public function get_keywords() {
		return ['eazygrid-elementor', 'eazygrid', 'eazygrid-elementor', 'eazy', 'grid', 'even'];
	}

	/**
	 * Register content controls
	 */
	public function register_content_controls() {
		$this->__insta_feed_content_controls();
	}


	// Instagram Feed content
	protected function __insta_feed_content_controls() {

		$this->start_controls_section(
			'_section_instagram',
			[
				'label' => __( 'Instagram Feed', 'eazygrid-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'access_token',
			[
				'label'       => __( 'Access Token', 'eazygrid-elementor' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '',
			]
		);

		$this->add_control(
			'important_note',
			[
				'label'           => __( 'Important Note', 'eazygrid-elementor' ),
				'show_label'      => false,
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => '<a href="https://developers.facebook.com/docs/instagram-basic-display-api/getting-started" target="_blank">Get Access Token</a>',
				'content_classes' => 'your-class',
			]
		);

		$this->add_control(
			'clear_cash',
			[
				'label'        => __( 'Remove Cache', 'eazygrid-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'no',
				'separator'    => 'after',
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label'           => __( 'Columns', 'eazygrid-elementor' ),
				'type'            => Controls_Manager::SELECT,
				'options'         => [
					1 => __( '1 Column', 'eazygrid-elementor' ),
					2 => __( '2 Columns', 'eazygrid-elementor' ),
					3 => __( '3 Columns', 'eazygrid-elementor' ),
					4 => __( '4 Columns', 'eazygrid-elementor' ),
					5 => __( '5 Columns', 'eazygrid-elementor' ),
					6 => __( '6 Columns', 'eazygrid-elementor' ),
				],
				'desktop_default' => 3,
				'tablet_default'  => 3,
				'mobile_default'  => 2,
				'selectors'       => [
					'{{WRAPPER}} .ezg-ele-even-instagram-grid-wrap' => 'grid-template-columns: repeat( {{VALUE}}, 1fr );',
				],
				'style_transfer'  => true,
			]
		);

		$this->add_control(
			'instagram_item',
			[
				'label'          => __( 'Image Items', 'eazygrid-elementor' ),
				'type'           => Controls_Manager::NUMBER,
				'min'            => 0,
				'max'            => 100,
				'step'           => 1,
				'default'        => 12,
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'sort_by',
			[
				'label'     => __( 'Sort By', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'recent-posts',
				'options'   => [
					'recent-posts' => __( 'Recent Posts', 'eazygrid-elementor' ),
					'old-posts'    => __( 'Old Posts', 'eazygrid-elementor' ),
				],
				'separetor' => 'before',
			]
		);

		$this->add_control(
			'show_caption',
			[
				'label'          => __( 'Show Caption?', 'eazygrid-elementor' ),
				'type'           => Controls_Manager::SWITCHER,
				'return_value'   => 'yes',
				'default'        => 'yes',
				'style_transfer' => true,
			]
		);

		$this->add_control(
			'link_target',
			[
				'label'        => __( 'Open in new tab?', 'eazygrid-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register style controls
	 */
	public function register_style_controls() {
		$this->layout_style_tab_controls();
		$this->__instagram_style_tab_controls();
	}

	/**
	 * Layout Style controls
	 */
	protected function layout_style_tab_controls() {

		$this->start_controls_section(
			'_section_layout_style',
			[
				'label' => __( 'Layout', 'eazygrid-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'column_gap',
			[
				'label'     => __( 'Columns Gap', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 30,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ezg-ele-even-instagram-grid-wrap' => 'grid-column-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label'     => __( 'Rows Gap', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 35,
				],
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ezg-ele-even-instagram-grid-wrap' => 'grid-row-gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __instagram_style_tab_controls() {

		$this->start_controls_section(
			'_section__instagram_style',
			[
				'label' => __( 'Instagram', 'eazygrid-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'item_box_bg',
			[
				'label'     => __( 'Background', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ezg-ele-even-instagram-grid-item' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'item_box_border_radius',
			[
				'label'      => __( 'Border Radius', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-even-instagram-grid-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'item_box_border',
				'label'    => __( 'Border', 'eazygrid-elementor' ),
				'selector' => '{{WRAPPER}} .ezg-ele-even-instagram-grid-item',
			]
		);

		$this->add_control(
			'hr',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'caption_color',
			[
				'label'     => esc_html__( 'Caption Color', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ezg-ele-even-instagram-grid-item p' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_caption' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'caption_padding',
			[
				'label'      => __( 'Caption Padding', 'eazygrid-elementor' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .ezg-ele-even-instagram-grid-item .ezg-ele-even-instagram-grid-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'show_caption' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$access_token  = $settings['access_token'];
		$widget_id     = $this->get_id();
		$transient_key = 'happy_insta_feed_data' . $widget_id . str_replace( '.', '_', $access_token );
		$transient_key = substr( $transient_key, 0, 170 );

		if ( ezg_ele_is_edit_mode() && 'yes' === $settings['clear_cash'] ) {
			delete_transient( $transient_key );
			// echo 'Delete cache';
		}

		$messages       = array();
		$instagram_data = get_transient( $transient_key );
		if ( false === $instagram_data && $access_token ) {
			$url            = 'https://graph.instagram.com/me/media/?fields=caption,id,media_type,media_url,permalink,thumbnail_url,timestamp,username&limit=100&access_token=' . esc_html( $access_token );
			$instagram_data = wp_remote_retrieve_body( wp_remote_get( $url ) );
			// echo 'Data cache';
			set_transient( $transient_key, $instagram_data, 1 * MONTH_IN_SECONDS ); //HOUR_IN_SECONDS
		}

		$instagram_data = apply_filters( 'happy_instagram_data', $instagram_data, $transient_key, $access_token, $widget_id );
		$instagram_data = json_decode( $instagram_data, true );
		// code 100 = ID dose not exist.
		// code 190 = invalid access token or session expire
		if ( empty( $access_token ) ) {
			$messages['invalid_token_id'] = __( 'Please Input Access Token', 'eazygrid-elementor' );
		} elseif ( empty( $access_token ) || empty( $instagram_data ) ) {
			$messages['invalid_token_id'] = __( 'Please Input Valid Access Token', 'eazygrid-elementor' );
		} elseif ( array_key_exists( 'error', $instagram_data ) ) {
			$messages['invalid_token'] = $instagram_data['error']['message'];
		} elseif ( empty( $instagram_data['data'] ) ) {
			$messages['data_empty'] = __( 'Whoops! It seems like this account has not created any post yet. Please, make some post on Instagram.', 'eazygrid-elementor' );
		} elseif ( empty( $settings['instagram_item'] ) ) {
			$messages['item_empty'] = __( 'Must set how many items want to show.', 'eazygrid-elementor' );
		} elseif ( $settings['instagram_item'] > count( $instagram_data['data'] ) ) {
			$messages['item_empty'] = __( 'The image number is more than the total post\'s number of instagram. Please set it less or equal to total post\'s number.', 'eazygrid-elementor' );
		}
		if ( ! empty( $messages ) ) {
			foreach ( $messages as $key => $message ) {
				printf( '<span class="ezg-ele-even-instagram-grid-error-message">%1$s</span>', esc_html( $message ) );
			}
			return;
		}

		switch ( $settings['sort_by'] ) {
			case 'old-posts':
				usort($instagram_data['data'], function ( $a, $b ) {
					if ( strtotime( $a['timestamp'] ) == strtotime( $b['timestamp'] ) ) {
						return 0;
					}
					return ( strtotime( $a['timestamp'] ) < strtotime( $b['timestamp'] ) ) ? -1 : 1;
				});
				break;
			default:
				$instagram_data['data'];
		}
		$instagram_data = array_splice( $instagram_data['data'], 0, $settings['instagram_item'] );
		$target         = ( 'yes' === $settings['link_target'] ) ? '_blank' : '_self';
		?>
		<div class="ezg-ele-even-instagram-grid-wrap">
			<?php
			foreach ( $instagram_data as $key => $single ) :
				$image_src = ( 'VIDEO' === $single['media_type'] ) ? $single['thumbnail_url'] : $single['media_url'];
				?>
				<div class="ezg-ele-even-instagram-grid-item">
					<a class="ezg-ele-even-instagram-grid-image" href="<?php echo esc_url( $single['permalink'] ); ?>" target="<?php echo esc_attr( $target ); ?>">
						<img src="<?php echo esc_url( $image_src ); ?>" alt="<?php echo esc_html( $single['caption'] ); ?>">
					</a>
					<?php if ( 'yes' === $settings['show_caption'] ) : ?>
					<div class="ezg-ele-even-instagram-grid-content">
						<div class="ezg-ele-even-instagram-grid-caption">
							<p><?php echo esc_html( $single['caption'] ); ?></p>
						</div>
					</div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
