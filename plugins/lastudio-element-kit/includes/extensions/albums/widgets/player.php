<?php

namespace LaStudioKitExtensions\Albums\Widgets;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\LaStudioKit_Base;

class Player extends LaStudioKit_Base{

	protected function enqueue_addon_resources(){
		$this->add_script_depends('lastudio-kit-base');
		$this->add_script_depends('lastudio-kit-player');
		$this->add_style_depends('lastudio-kit-player');
	}

	public function get_name() {
		return 'lakit-player';
	}

	public function get_widget_title() {
		return __('Audio Player', 'lastudio-kit');
	}

	public function get_keywords() {
		return [ 'audio', 'player', 'music', 'mp3' ];
	}

	public function get_icon() {
		return 'eicon-headphones';
	}

	protected function set_template_output(){
		return lastudio_kit()->plugin_path('includes/extensions/albums/widget-templates');
	}

	protected function register_controls() {

		$css_schema = apply_filters(
			'lastudio-kit/'.$this->get_lakit_name().'/css-schema',
			array(
				'wrapper'  => '.lakitplayer',
				'controls' => '.lakitplayer__controls',
				'control_item' => '.lakitplayer__controls button',
				'available_on' => '.lakitplayer__album_available',
				'available_on_label' => '.lakitplayer__album_available--label',
				'available_on_icons' => '.lakitplayer__album_available--icons',
				'available_on_item' => '.lakitplayer__album_available--icons a',
				'playlist' => '.lakitplayer__playlist_wrapper',
				'tracklist' => '.lakitplayer_playlists',
				'track_item' => '.lakitplayer_playlist__item',
				'track_item_info' => '.lakitplayer_playlist__item-info',
				'track_item_title' => '.lakitplayer_playlist__item_title',
				'track_item_artist' => '.lakitplayer_playlist__item_artist',
				'track_item_control' => '.lakitplayer_playlist__item-controls',
			)
		);

		$this->start_controls_section(
			'section_data_source',
			array(
				'label' => esc_html__( 'Data Source', 'lastudio-kit' ),
			)
		);

		$this->add_control(
			'preset',
			array(
				'label'     => esc_html__( 'Preset', 'lastudio-kit' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'type-1',
				'options'   => [
					'type-1' => __( 'Type 1', 'lastudio-kit' ),
					'type-2' => __( 'Type 2', 'lastudio-kit' ),
					'type-3' => __( 'Type 3', 'lastudio-kit' ),
				]
			)
		);

		$this->add_control(
			'album_ids',
			[
				'label'        => esc_html__( 'Albums', 'lastudio-kit' ),
				'type'         => 'lastudiokit-query',
				'options'      => [],
				'label_block'  => true,
				'multiple'      => false,
				'autocomplete' => [
					'object' => 'post',
					'query'  => [
						'post_type' => [ 'la_album' ],
					],
				],
			]
		);

		$this->_add_control(
			'show_player',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Show Player', 'lastudio-kit' ),
				'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
				'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);
		$this->_add_control(
			'show_playlist',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Show Playlist', 'lastudio-kit' ),
				'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
				'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->end_controls_section();

		/* GENERAL */
		$this->_start_controls_section(
			'section_general_style',
			array(
				'label'      => esc_html__( 'General', 'lastudio-kit' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);
		$this->end_controls_section();

		/**
		 * PLAYER
		 */
		$this->_start_controls_section(
			'section_player_style',
			array(
				'label'      => esc_html__( 'Player', 'lastudio-kit' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'controls_box_typography',
				'selector' => '{{WRAPPER}}  ' . $css_schema['controls'],
			),
			50
		);

		$this->_add_responsive_control(
			'controls_item_size',
			array(
				'label'      => esc_html__( 'Control Size', 'lastudio-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_schema['control_item'] => 'font-size: {{SIZE}}{{UNIT}}',
				)
			)
		);
		$this->_add_responsive_control(
			'controls_box_gap',
			array(
				'label'      => esc_html__( 'Box Item Gap', 'lastudio-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_schema['wrapper'] => '--control-box-gap: {{SIZE}}{{UNIT}}',
				)
			)
		);
		$this->_add_responsive_control(
			'controls_item_gap',
			array(
				'label'      => esc_html__( 'Item gap', 'lastudio-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_schema['wrapper'] => '--control-item-gap: {{SIZE}}{{UNIT}}',
				)
			)
		);

		$this->_add_control(
			'controls_box_color',
			array(
				'label'     => esc_html__( 'Color', 'lastudio-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_schema['controls'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'controls__progressbar',
			array(
				'label'      => esc_html__( 'Progress Bar', 'lastudio-kit' ),
				'type'       => Controls_Manager::HEADING,
			)
		);
		$this->_add_responsive_control(
			'controls__progressbar_height',
			array(
				'label'      => esc_html__( 'Height', 'lastudio-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_schema['wrapper'] => '--track-height: {{SIZE}}{{UNIT}}',
				)
			)
		);
		$this->_add_control(
			'controls__progressbar_color',
			array(
				'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_schema['wrapper'] => '--track-bg: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'controls__progressbar_active_color',
			array(
				'label'     => esc_html__( 'Active Background Color', 'lastudio-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_schema['wrapper'] => '--track-active-bg: {{VALUE}}',
				),
			),
			25
		);
		$this->_add_responsive_control(
			'controls__progressbar_bullet',
			array(
				'label'      => esc_html__( 'Bullet Size', 'lastudio-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_schema['wrapper'] => '--thumb-size: {{SIZE}}{{UNIT}}',
				)
			)
		);
		$this->_add_control(
			'controls__progressbar_bullet_color',
			array(
				'label'     => esc_html__( 'Bullet Color', 'lastudio-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_schema['wrapper'] => '--thumb-bg: {{VALUE}}',
				),
			),
			25
		);


		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'controls_box_bg',
				'selector' => '{{WRAPPER}} ' . $css_schema['controls'],
				'separator' => 'before',
			),
			25
		);
		$this->_add_responsive_control(
			'controls_box_padding',
			array(
				'label'       => esc_html__( 'Padding', 'lastudio-kit' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => ['px', 'em', 'custom'],
				'selectors'   => array(
					'{{WRAPPER}} ' . $css_schema['controls'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);
		$this->_add_responsive_control(
			'controls_box_margin',
			array(
				'label'       => esc_html__( 'Margin', 'lastudio-kit' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => ['px', 'em', 'custom'],
				'selectors'   => array(
					'{{WRAPPER}} ' . $css_schema['controls'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'controls_box_radius',
			array(
				'label'       => esc_html__( 'Border Radius', 'lastudio-kit' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => ['px', 'em', 'custom'],
				'selectors'   => array(
					'{{WRAPPER}} ' . $css_schema['controls'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'controls_box_border',
				'label'       => esc_html__( 'Border', 'lastudio-kit' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_schema['controls'],
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'controls_box_shadow',
				'selector' => '{{WRAPPER}} ' . $css_schema['controls'],
			),
			75
		);

		$this->end_controls_section();


		/**
		 * PLAYLIST
		 */
		$this->_start_controls_section(
			'section_playlist_style',
			array(
				'label'      => esc_html__( 'Playlist', 'lastudio-kit' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'show_playlist!' => ''
				]
			)
		);

		$this->_add_responsive_control(
			'playlist_height',
			array(
				'label'      => esc_html__( 'Height', 'lastudio-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_schema['wrapper'] => '--playlist-height: {{SIZE}}{{UNIT}}',
				)
			)
		);
		$this->_add_control(
			'scrollbar_color',
			array(
				'label'     => esc_html__( 'Scrollbar BgColor', 'lastudio-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_schema['wrapper'] => '--scrollbar-bgcolor: {{VALUE}}',
				),
			),
			25
		);
		$this->_add_control(
			'scrollbar_activecolor',
			array(
				'label'     => esc_html__( 'Scrollbar Active BgColor', 'lastudio-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_schema['wrapper'] => '--scrollbar-bgcolor-active: {{VALUE}}',
				),
			),
			25
		);


		$this->_add_group_control(
			Group_Control_Background::get_type(),
			array(
				'name'     => 'playlist_bg',
				'selector' => '{{WRAPPER}} ' . $css_schema['tracklist'],
			),
			25
		);

		$this->_add_responsive_control(
			'playlist_padding',
			array(
				'label'       => esc_html__( 'Padding', 'lastudio-kit' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => ['px', 'em', 'custom'],
				'selectors'   => array(
					'{{WRAPPER}} ' . $css_schema['tracklist'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);
		$this->_add_responsive_control(
			'playlist_margin',
			array(
				'label'       => esc_html__( 'Margin', 'lastudio-kit' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => ['px', 'em', 'custom'],
				'selectors'   => array(
					'{{WRAPPER}} ' . $css_schema['playlist'] => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'playlist_radius',
			array(
				'label'       => esc_html__( 'Border Radius', 'lastudio-kit' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => ['px', 'em', 'custom'],
				'selectors'   => array(
					'{{WRAPPER}} ' . $css_schema['tracklist'] => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'playlist_border',
				'label'       => esc_html__( 'Border', 'lastudio-kit' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_schema['tracklist'],
			),
			75
		);

		$this->_add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'playlist_shadow',
				'selector' => '{{WRAPPER}} ' . $css_schema['tracklist'],
			),
			75
		);


		$this->end_controls_section();

		/**
		 * PLAYLIST
		 */
		$this->_start_controls_section(
			'section_trackitem_style',
			array(
				'label'      => esc_html__( 'Track Item', 'lastudio-kit' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition' => [
					'show_playlist!' => ''
				]
			)
		);
		$this->_add_control(
			'track_item_disable_preview',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Disable Preview', 'lastudio-kit' ),
				'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
				'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'selectors'    => [
					'{{WRAPPER}} .lakitplayer_playlists .lakitplayer__control__preview' => 'display: none',
				]
			)
		);
		$this->_add_control(
			'track_item_disable_artist',
			array(
				'type'         => Controls_Manager::SWITCHER,
				'label'        => esc_html__( 'Disable Artist', 'lastudio-kit' ),
				'label_on'     => esc_html__( 'Yes', 'lastudio-kit' ),
				'label_off'    => esc_html__( 'No', 'lastudio-kit' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'selectors'    => [
					'{{WRAPPER}} .lakitplayer_playlists .lakitplayer_playlist__item_artist' => 'display: none',
				]
			)
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'track_item_title_typography',
				'selector' => '{{WRAPPER}}  ' . $css_schema['track_item_title']
			),
			50
		);
		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'track_item_artist_typography',
				'selector' => '{{WRAPPER}}  ' . $css_schema['track_item_artist'],
				'label'     => esc_html__('Artist Typography', 'lastudio-kit')
			),
			50
		);

		$this->_add_responsive_control(
			'track_item_padding',
			array(
				'label'       => esc_html__( 'Item Padding', 'lastudio-kit' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => ['px', 'em', 'custom'],
				'selectors'   => array(
					'{{WRAPPER}} ' . $css_schema['track_item'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_responsive_control(
			'track_item_margin',
			array(
				'label'       => esc_html__( 'Item Padding', 'lastudio-kit' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => ['px', 'em', 'custom'],
				'selectors'   => array(
					'{{WRAPPER}} ' . $css_schema['track_item'] => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			),
			50
		);

		$this->_add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'        => 'track_item_border',
				'label'       => esc_html__( 'Border', 'lastudio-kit' ),
				'placeholder' => '1px',
				'selector'    => '{{WRAPPER}} ' . $css_schema['track_item'],
			),
			75
		);


		$this->_start_controls_tabs( 'trackitem_tab' );

		$this->_start_controls_tab(
			'trackitem_tab_normal',
			array(
				'label' => esc_html__( 'Normal', 'lastudio-kit' ),
			)
		);
		$this->_add_control(
			'track_item_bgcolor',
			array(
				'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_schema['track_item'] => 'background-color: {{VALUE}}',
				),
			),
			25
		);
		$this->_add_control(
			'track_item_color',
			array(
				'label'     => esc_html__( 'Color', 'lastudio-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_schema['track_item'] => 'color: {{VALUE}}',
				),
			),
			25
		);
		$this->_add_control(
			'track_item_artist_color',
			array(
				'label'     => esc_html__( 'Artist Color', 'lastudio-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_schema['track_item_artist'] => 'color: {{VALUE}}',
				),
			),
			25
		);
		$this->_add_responsive_control(
			'track_item_title_gap',
			array(
				'label'      => esc_html__( 'Title Spacing', 'lastudio-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_schema['track_item_info'] => 'gap: {{SIZE}}{{UNIT}}',
				)
			)
		);
		$this->_add_responsive_control(
			'track_item_control_size',
			array(
				'label'      => esc_html__( 'Control Size', 'lastudio-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_schema['track_item_control'] => 'font-size: {{SIZE}}{{UNIT}}',
				)
			)
		);
		$this->_end_controls_tab();

		$this->_start_controls_tab(
			'trackitem_tab_active',
			array(
				'label' => esc_html__( 'Active', 'lastudio-kit' ),
			)
		);
		$this->_add_control(
			'track_item_bgcolor_active',
			array(
				'label'     => esc_html__( 'Background Color', 'lastudio-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_schema['track_item'] . '.active-track' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_schema['track_item']. ':hover' => 'background-color: {{VALUE}}',
				),
			),
			25
		);
		$this->_add_control(
			'track_item_color_active',
			array(
				'label'     => esc_html__( 'Color', 'lastudio-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_schema['track_item'] . '.active-track' => 'color: {{VALUE}}',
					'{{WRAPPER}} ' . $css_schema['track_item']. ':hover' => 'color: {{VALUE}}',
				),
			),
			25
		);
		$this->_add_control(
			'track_item_artist_color_active',
			array(
				'label'     => esc_html__( 'Artist Color', 'lastudio-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lakitplayer_playlist__item.active-track ' . $css_schema['track_item_artist'] => 'color: {{VALUE}}',
					'{{WRAPPER}} .lakitplayer_playlist__item:hover ' . $css_schema['track_item_artist'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'track_item_border_color_active',
			array(
				'label'     => esc_html__( 'Border Color', 'lastudio-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .lakitplayer_playlist__item.active-track ' . $css_schema['track_item'] => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .lakitplayer_playlist__item:hover ' . $css_schema['track_item'] => 'border-color: {{VALUE}}',
				),
			),
			25
		);
		$this->_add_responsive_control(
			'track_item_control_size_active',
			array(
				'label'      => esc_html__( 'Control Size', 'lastudio-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .lakitplayer_playlist__item.active-track ' . $css_schema['track_item_control'] => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .lakitplayer_playlist__item:hover ' . $css_schema['track_item_control'] => 'font-size: {{SIZE}}{{UNIT}}',
				)
			)
		);
		$this->_end_controls_tab();
		$this->_end_controls_tabs();

		$this->end_controls_section();


		/**
		 * AVAILABLE ON
		 */
		$this->_start_controls_section(
			'section_available_style',
			array(
				'label'      => esc_html__( 'Available', 'lastudio-kit' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->_add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'available_heading_typography',
				'selector' => '{{WRAPPER}}  ' . $css_schema['available_on_label'],
			),
			50
		);

		$this->_add_control(
			'available_heading_color',
			array(
				'label'     => esc_html__( 'Heading Color', 'lastudio-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_schema['available_on_label'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_responsive_control(
			'available_heading_gap',
			array(
				'label'      => esc_html__( 'Spacing', 'lastudio-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_schema['available_on'] => 'gap: {{SIZE}}{{UNIT}}',
				)
			)
		);

		$this->_add_responsive_control(
			'available_icon_size',
			array(
				'label'      => esc_html__( 'Icon size', 'lastudio-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_schema['available_on_icons'] => 'font-size: {{SIZE}}{{UNIT}}',
				)
			)
		);

		$this->_add_responsive_control(
			'available_icon_gap',
			array(
				'label'      => esc_html__( 'Item gap', 'lastudio-kit' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $css_schema['available_on_icons'] => 'gap: {{SIZE}}{{UNIT}}',
				)
			)
		);

		$this->_add_control(
			'available_icon_color',
			array(
				'label'     => esc_html__( 'Icon Color', 'lastudio-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_schema['available_on_item'] => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->_add_control(
			'available_icon_color_hover',
			array(
				'label'     => esc_html__( 'Icon Hover Color', 'lastudio-kit' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $css_schema['available_on_item'] . ':hover' => 'color: {{VALUE}}',
				),
			),
			25
		);

		$this->end_controls_section();

	}

	protected function render() {

		$this->_context = 'render';

		$this->_open_wrap();
		include $this->_get_global_template( 'index' );
		$this->_close_wrap();
	}

	public function getIconSvg( $name = '' ){
		$iconLists = [
			'spotify'       => '<svg xmlns="http://www.w3.org/2000/svg" width="258" height="300" viewBox="0 0 258 300" class="lakit-font-icon-svg" data-icon-name="b-spotify-1" data-icon-type="LaStudioIcons"><path d="M188.7 202.5q0-5.4-5.1-8.4-32.1-19.2-74.7-19.2-22.2 0-48 5.7-7.2 1.5-7.2 8.7 0 3.3 2.4 5.7t6 2.4q.6 0 6-1.5 22.2-4.5 40.8-4.5 37.8 0 66.3 17.4 3.3 1.8 5.7 1.8 3 0 5.4-2.4t2.4-5.7zm16.2-36q0-6.6-6-10.2-39.6-23.7-91.8-23.7-25.5 0-50.7 7.2-8.1 2.1-8.1 10.8 0 4.2 3 6.9t7.2 3q1.2 0 6-1.2 20.7-5.7 42.3-5.7 46.5 0 81.6 20.7 3.9 2.4 6.3 2.4 4.2 0 7.2-3t3-7.2zm18-41.4q0-7.8-6.9-11.7-21-12.3-48.9-18.6t-57.3-6.3q-34.2 0-61.2 7.8-3.6 1.2-6.3 4.5t-2.7 8.1q0 5.1 3.6 8.7t8.4 3.3q2.1 0 6.9-1.2 22.2-6.3 51.3-6.3 26.7 0 51.9 5.7T204 135q3.6 2.1 6.9 2.1 4.8 0 8.4-3.3t3.6-8.7zm34.2 24.9q0 35.1-17.1 64.5t-46.8 46.8-64.5 17.4-64.8-17.4-46.5-46.8T0 150t17.4-64.5 46.5-46.8 64.8-17.4 64.5 17.4T240 85.5t17.1 64.5z"></path></svg>',
			'youtube'       => '<svg xmlns="http://www.w3.org/2000/svg" width="300" height="300" viewBox="0 0 300 300" class="lakit-font-icon-svg" data-icon-name="b-youtube-play" data-icon-type="LaStudioIcons"><path d="M119.1 188.7l81-41.7-81-42.3v84zM150 44.4q28.2 0 54.3.9t38.4 1.5l12.3.6 2.7.3q2.7.3 3.9.6t3.9.6 4.8 1.5 4.8 2.1 5.1 3.3 4.8 4.5q1.2.9 2.7 3t4.8 9.9T297 90q1.2 10.8 2.1 22.8t.9 19.2v29.4q.3 24.3-3 48.6-1.2 9-4.2 16.5t-5.4 10.5l-2.4 2.7q-2.1 2.4-4.8 4.5t-5.1 3-4.8 2.1-4.8 1.5-3.9.6-3.9.6-2.7.3q-42 3.3-105 3.3-34.5-.6-60.3-1.2t-33.3-1.2l-8.4-.9-6-.6q-6-.9-9-1.5t-8.7-3.6-9.3-6.9q-1.2-.9-2.7-3t-4.8-9.9T3 210Q1.8 199.2.9 187.2T0 168v-29.4Q-.3 114.3 3 90q1.2-9.3 4.2-16.5T12.6 63l2.4-2.7q2.4-2.7 4.8-4.5t5.1-3.3 4.8-2.1 4.8-1.5 3.9-.6 3.9-.6 2.7-.3q42-3 105-3z"></path></svg>',
			'itunes'        => '<svg aria-hidden="true" class="e-font-icon-svg e-fab-itunes-note" viewBox="0 0 384 512" xmlns="http://www.w3.org/2000/svg"><path d="M381.9 388.2c-6.4 27.4-27.2 42.8-55.1 48-24.5 4.5-44.9 5.6-64.5-10.2-23.9-20.1-24.2-53.4-2.7-74.4 17-16.2 40.9-19.5 76.8-25.8 6-1.1 11.2-2.5 15.6-7.4 6.4-7.2 4.4-4.1 4.4-163.2 0-11.2-5.5-14.3-17-12.3-8.2 1.4-185.7 34.6-185.7 34.6-10.2 2.2-13.4 5.2-13.4 16.7 0 234.7 1.1 223.9-2.5 239.5-4.2 18.2-15.4 31.9-30.2 39.5-16.8 9.3-47.2 13.4-63.4 10.4-43.2-8.1-58.4-58-29.1-86.6 17-16.2 40.9-19.5 76.8-25.8 6-1.1 11.2-2.5 15.6-7.4 10.1-11.5 1.8-256.6 5.2-270.2.8-5.2 3-9.6 7.1-12.9 4.2-3.5 11.8-5.5 13.4-5.5 204-38.2 228.9-43.1 232.4-43.1 11.5-.8 18.1 6 18.1 17.6.2 344.5 1.1 326-1.8 338.5z"></path></svg>',
			'apple'         => '<svg aria-hidden="true" class="e-font-icon-svg e-fab-apple" viewBox="0 0 384 512" xmlns="http://www.w3.org/2000/svg"><path d="M318.7 268.7c-.2-36.7 16.4-64.4 50-84.8-18.8-26.9-47.2-41.7-84.7-44.6-35.5-2.8-74.3 20.7-88.5 20.7-15 0-49.4-19.7-76.4-19.7C63.3 141.2 4 184.8 4 273.5q0 39.3 14.4 81.2c12.8 36.7 59 126.7 107.2 125.2 25.2-.6 43-17.9 75.8-17.9 31.8 0 48.3 17.9 76.4 17.9 48.6-.7 90.4-82.5 102.6-119.3-65.2-30.7-61.7-90-61.7-91.9zm-56.6-164.2c27.3-32.4 24.8-61.9 24-72.5-24.1 1.4-52 16.4-67.9 34.9-17.5 19.8-27.8 44.3-25.6 71.9 26.1 2 49.9-11.4 69.5-34.3z"></path></svg>',
			'soundcloud'    => '<svg xmlns="http://www.w3.org/2000/svg" width="386" height="300" viewBox="0 0 386 300" class="lakit-font-icon-svg" data-icon-name="b-soundcloud" data-icon-type="LaStudioIcons"><path d="M131.4 229.8l2.4-40.5-2.4-87.6q-.3-1.5-1.5-2.7t-2.7-1.2q-1.5 0-2.7 1.2t-1.2 2.7l-2.1 87.6 2.1 40.5q.3 1.5 1.5 2.7t2.4.9q3.6 0 4.2-3.6zm49.5-5.1l1.8-35.1-2.1-98.1q0-2.7-2.1-4.2-1.5-.9-2.7-.9t-2.7.9q-2.1 1.5-2.1 4.2l-.3.9-1.5 96.9q0 .3 1.8 39.6 0 1.8.9 3 1.5 1.8 3.9 1.8 1.8 0 3.3-1.5 1.5-1.2 1.5-3.3zM6 168l3.3 21.3-3.3 21q-.6 1.5-1.5 1.5T3 210.3l-3-21L3 168q.3-1.5 1.5-1.5T6 168zm14.4-13.2l4.2 34.5-4.2 33.9q-.6 1.5-1.8 1.5-1.5 0-1.5-1.5l-3.9-33.9 3.9-34.5q0-1.5 1.5-1.5 1.2 0 1.8 1.5zm46.8 75.6zm-31.5-82.2l4.2 41.1-4.2 39.6q0 1.8-1.8 1.8t-2.1-1.8l-3.6-39.6 3.6-41.1q.3-1.8 2.1-1.8t1.8 1.8zm15.6-1.2l3.9 42.3-3.9 40.8q-.3 2.4-2.1 2.4-2.4 0-2.4-2.4l-3.3-40.8 3.3-42.3q0-2.1 2.4-2.1 1.8 0 2.1 2.1zm15.9 3.3l3.6 39-3.6 41.1q-.3 2.7-2.7 2.7-.9 0-1.8-.6t-.6-2.1l-3.6-41.1 3.6-39q0-1.2.6-1.8t1.8-.9q2.4 0 2.7 2.7zm64.2 79.5zM82.8 125.7l3.6 63.6-3.6 41.1q0 1.2-.9 2.1t-1.8.9q-2.7 0-3-3l-3-41.1 3-63.6q.3-3 3-3 1.2 0 1.8.9t.9 2.1zm15.9-14.4l3 78.3-3 40.8q0 1.5-.9 2.4t-2.4.9q-3 0-3.3-3.3l-2.7-40.8 2.7-78.3q.3-3.3 3.3-3.3 1.5 0 2.4 1.2t.9 2.1zm16.2-6.6l3 84.6-3 40.5q-.3 3.6-3.6 3.6-3 0-3.6-3.6l-2.7-40.5 2.7-84.6q0-1.5 1.2-2.7t2.4-1.2q1.5 0 2.7 1.2t.9 2.7zm65.7 124.2zm-33-125.1l2.4 85.5-2.4 39.9q0 1.8-1.5 3t-2.7 1.2-3-.9-1.2-3.3l-2.4-39.9 2.4-85.5q0-1.8 1.2-3t3-.9 2.7.9 1.5 3zm16.5 3.3l2.4 82.5-2.4 39.3q0 1.8-1.5 3.3t-3 1.2-3.3-1.2-1.5-3.3l-1.8-39.3 1.8-82.5q.3-2.1 1.5-3.3t3.3-1.5 3 1.5 1.5 3.3zm35.4 82.5l-2.4 38.7q0 2.1-1.5 3.6t-3.6 1.5-3.6-1.5-1.8-3.6l-.9-19.2-.9-19.5 1.8-106.5v-.6q.3-2.4 2.1-3.9 1.5-1.2 3.3-1.2 1.5 0 2.4.9 2.4 1.2 2.7 4.2zm186.3-3.3q0 19.5-14.1 33.3t-33.3 13.8H206.7q-2.1-.3-3.6-1.8t-1.5-3.6V77.4q0-3.9 4.8-5.4 14.1-5.7 30.3-5.7 32.4 0 56.4 21.9t26.7 54.3q9-3.6 18.6-3.6 19.5 0 33.3 13.8t14.1 33.6z"></path></svg>',
			'bandcamp'      => '<svg aria-hidden="true" class="e-font-icon-svg e-fab-bandcamp" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M256,8C119,8,8,119,8,256S119,504,256,504,504,393,504,256,393,8,256,8Zm48.2,326.1h-181L207.9,178h181Z"></path></svg>',
			'googleplay'    => '<svg aria-hidden="true" class="e-font-icon-svg e-fab-google-play" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M325.3 234.3L104.6 13l280.8 161.2-60.1 60.1zM47 0C34 6.8 25.3 19.2 25.3 35.3v441.3c0 16.1 8.7 28.5 21.7 35.3l256.6-256L47 0zm425.2 225.6l-58.9-34.1-65.7 64.5 65.7 64.5 60.1-34.1c18-14.3 18-46.5-1.2-60.8zM104.6 499l280.8-161.2-60.1-60.1L104.6 499z"></path></svg>',
			'amazon'        => '<svg aria-hidden="true" class="e-font-icon-svg e-fab-amazon" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M257.2 162.7c-48.7 1.8-169.5 15.5-169.5 117.5 0 109.5 138.3 114 183.5 43.2 6.5 10.2 35.4 37.5 45.3 46.8l56.8-56S341 288.9 341 261.4V114.3C341 89 316.5 32 228.7 32 140.7 32 94 87 94 136.3l73.5 6.8c16.3-49.5 54.2-49.5 54.2-49.5 40.7-.1 35.5 29.8 35.5 69.1zm0 86.8c0 80-84.2 68-84.2 17.2 0-47.2 50.5-56.7 84.2-57.8v40.6zm136 163.5c-7.7 10-70 67-174.5 67S34.2 408.5 9.7 379c-6.8-7.7 1-11.3 5.5-8.3C88.5 415.2 203 488.5 387.7 401c7.5-3.7 13.3 2 5.5 12zm39.8 2.2c-6.5 15.8-16 26.8-21.2 31-5.5 4.5-9.5 2.7-6.5-3.8s19.3-46.5 12.7-55c-6.5-8.3-37-4.3-48-3.2-10.8 1-13 2-14-.3-2.3-5.7 21.7-15.5 37.5-17.5 15.7-1.8 41-.8 46 5.7 3.7 5.1 0 27.1-6.5 43.1z"></path></svg>',
		];
		if( !empty($name) && isset($iconLists[$name]) ){
			return $iconLists[$name];
		}
		else{
			return '';
		}
	}
}