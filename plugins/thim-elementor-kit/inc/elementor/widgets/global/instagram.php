<?php

namespace Elementor;

class Thim_Ekit_Widget_Instagram extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return 'thim-ekits-instagram';
	}

	public function get_title() {
		return esc_html__( 'Instagram Feed', 'thim-elementor-kit' );
	}

	public function get_icon() {
		return 'thim-eicon eicon-instagram-post';
	}

	public function get_categories() {
		return array( \Thim_EL_Kit\Elementor::CATEGORY );
	}

	public function get_keywords() {
		return [
			'instagram',
			'instagram feed',
			'social media',
			'social feed',
 			'instagram embed',
			'thim',
 		];
	}

	public function get_help_url() {
		return '';
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_image',
			[
				'label' => esc_html__( 'General', 'thim-elementor-kit' ),
			]
		);
		$this->add_control(
			'accesstoken',
			[
				'label'       => esc_html__( 'Access Token', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'description' => '<a href="https://developers.facebook.com/docs/instagram-basic-display-api/getting-started" target="_blank">' . __( 'Get Access Token', 'thim-elementor-kit' ) . '</a>',
			]
		);
		$this->add_control(
			'data_cache_limit',
			[
				'label'       => __( 'Data Cache Time', 'thim-elementor-kit' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 1,
				'default'     => 60,
				'description' => __( 'Cache expiration time (Minutes)', 'thim-elementor-kit' )
			]
		);
		$this->add_control(
			'sort_by',
			[
				'label'   => esc_html__( 'Sort By', 'thim-elementor-kit' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'none',
				'options' => [
					'none'         => esc_html__( 'None', 'thim-elementor-kit' ),
					'most-recent'  => esc_html__( 'Most Recent', 'thim-elementor-kit' ),
					'least-recent' => esc_html__( 'Least Recent', 'thim-elementor-kit' ),
				],
			]
		);

		$this->add_control(
			'show_number_item',
			array(
				'label'   => esc_html__( 'Show Number Item', 'thim-elementor-kit' ),
				'default' => '6',
				'type'    => Controls_Manager::NUMBER,
			)
		);
		$this->add_control(
			'show_caption',
			[
				'label'        => esc_html__( 'Display Caption', 'thim-elementor-kit' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
		$this->add_control(
			'caption_length',
			[
				'label'     => esc_html__( 'Max Caption Length', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 2000,
				'default'   => 60,
				'condition' => [
					'show_caption' => 'yes',
				],
			]
		);
		$this->add_control(
			'show_date',
			[
				'label'        => esc_html__( 'Display Date', 'thim-elementor-kit' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'enable_link',
			[
				'label'        => esc_html__( 'Enable Link', 'thim-elementor-kit' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'link_target',
			[
				'label'        => esc_html__( 'Open in new window?', 'thim-elementor-kit' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'enable_link' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		$this->_register_option_style();
	}

	protected function _register_option_style() {
		$this->start_controls_section(
			'_register_option_style_tab',
			[
				'label' => esc_html__( 'Options', 'thim-elementor-kit' ),
			]
		);

		$this->add_responsive_control(
			'columns',
			array(
				'label'     => esc_html__( 'Columns', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 6,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 10,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--thim-ekit-instagram-columns: repeat({{SIZE}}, 1fr)',
				),
			)
		);
		$this->add_responsive_control(
			'column_gap',
			array(
				'label'     => esc_html__( 'Columns Gap', 'thim-elementor-kit' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => array(
					'size' => 20,
				),
				'range'     => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					'{{WRAPPER}}' => '--thim-ekit-instagram-column-gap: {{SIZE}}{{UNIT}}',
				),
			)
		);

		$this->end_controls_section();
	}

	public function render() {
		$settings = $this->get_settings_for_display();
 		if ( empty( $settings['accesstoken'] ) ) {
			return;
		}
		echo '<div class="tp-instagram__list">' . $this->_render_items( $settings ) . '</div>';
	}

	public function _render_items( $settings ) {
		$key  = 'thim_ekits_instagram_' . md5( str_replace( '.', '_', $settings['accesstoken'] ) . $settings['data_cache_limit'] );
		$html = '';
		if ( get_transient( $key ) === false ) {
			$request_args   = array(
				'timeout' => 60,
			);
			$instagram_data = wp_remote_retrieve_body(
				wp_remote_get(
					'https://graph.instagram.com/me/media/?fields=username,id,caption,media_type,media_url,permalink,thumbnail_url,timestamp&limit=500&access_token=' . $settings['accesstoken'],
					$request_args
				)
			);
			$data_check     = json_decode( $instagram_data, true );
			if ( ! empty( $data_check['data'] ) ) {
				set_transient( $key, $instagram_data, ( $settings['data_cache_limit'] * MINUTE_IN_SECONDS ) );
			}
		} else {
			$instagram_data = get_transient( $key );
		}

		$instagram_data = json_decode( $instagram_data, true );

		if ( empty( $instagram_data['data'] ) ) {
			return;
		}

		switch ( $settings['sort_by'] ) {
			case 'most-recent':
				usort( $instagram_data['data'], function ( $a, $b ) {
					return (int) ( strtotime( $a['timestamp'] ) < strtotime( $b['timestamp'] ) );
				} );
				break;

			case 'least-recent':
				usort( $instagram_data['data'], function ( $a, $b ) {
					return (int) ( strtotime( $a['timestamp'] ) > strtotime( $b['timestamp'] ) );
				} );
				break;
		}

		if ( $items = $instagram_data['data'] ) {
			$items = array_splice( $items, $settings['show_number_item'], $settings['show_number_item'] );
			foreach ( $items as $item ) {
				$img_alt_posted_by = ! empty( $item['username'] ) ? $item['username'] : '-';
				$img_alt_content   = __( 'Photo by ', 'thim-elementor-kit' ) . $img_alt_posted_by;

				if ( 'yes' === $settings['enable_link'] ) {
					$target = ( $settings['link_target'] ) ? 'target=_blank' : 'target=_self';
				} else {
					$item['permalink'] = '#';
					$target            = '';
				}

				$html .= '<div  class="tp-instagram__item">
					<a href="' . $item['permalink'] . '" ' . esc_attr( $target ) . '> ';

				if ( $item['media_type'] == 'VIDEO' ) {
					$html .= '<div class="tp-instagram__video"><video width="400" controls>
                          <source src="' . $item['media_url'] . '" type="video/mp4">
                           <source src="' . $item['media_url'] . '" type="video/ogg">
                    </video></div>';
				} else {
					$html .= '<div class="tp-instagram__image"><img alt="' . $img_alt_content . '" class="instagram-img" src="' . $item['media_url'] . '">
                             <div class="tp-instagram__caption">';
					$html .= '<svg fill="none" height="30" viewbox="0 0 48 49" width="30" xmlns="http://www.w3.org/2000/svg">
                                 <path d="M24 0C17.487 0 16.668 0.030625 14.109 0.147C11.55 0.2695 9.807 0.679875 8.28 1.28625C6.67828 1.90126 5.22747 2.86597 4.029 4.11294C2.80823 5.33701 1.86332 6.81786 1.26 8.4525C0.666 10.0083 0.261 11.7906 0.144 14.3937C0.03 17.0122 0 17.8452 0 24.5031C0 31.1548 0.03 31.9878 0.144 34.6001C0.264 37.2094 0.666 38.9887 1.26 40.5475C1.875 42.1584 2.694 43.5243 4.029 44.8871C5.361 46.2499 6.699 47.089 8.277 47.7137C9.807 48.3201 11.547 48.7336 14.103 48.853C16.665 48.9694 17.481 49 24 49C30.519 49 31.332 48.9694 33.894 48.853C36.447 48.7305 38.196 48.3201 39.723 47.7137C41.3237 47.0984 42.7735 46.1337 43.971 44.8871C45.306 43.5243 46.125 42.1584 46.74 40.5475C47.331 38.9887 47.736 37.2094 47.856 34.6001C47.97 31.9878 48 31.1548 48 24.5C48 17.8452 47.97 17.0122 47.856 14.3968C47.736 11.7906 47.331 10.0083 46.74 8.4525C46.1368 6.81781 45.1918 5.33694 43.971 4.11294C42.7729 2.86551 41.322 1.90073 39.72 1.28625C38.19 0.679875 36.444 0.266438 33.891 0.147C31.329 0.030625 30.516 0 23.994 0H24.003H24ZM21.849 4.41613H24.003C30.411 4.41613 31.17 4.43756 33.699 4.557C36.039 4.66419 37.311 5.06537 38.157 5.39919C39.276 5.84325 40.077 6.37612 40.917 7.23362C41.757 8.09112 42.276 8.90575 42.711 10.0511C43.041 10.9117 43.431 12.2102 43.536 14.5989C43.653 17.1806 43.677 17.9554 43.677 24.4939C43.677 31.0323 43.653 31.8102 43.536 34.3919C43.431 36.7806 43.038 38.0761 42.711 38.9397C42.3262 40.0035 41.7121 40.9653 40.914 41.7541C40.074 42.6116 39.276 43.1414 38.154 43.5855C37.314 43.9224 36.042 44.3205 33.699 44.4308C31.17 44.5471 30.411 44.5747 24.003 44.5747C17.595 44.5747 16.833 44.5471 14.304 44.4308C11.964 44.3205 10.695 43.9224 9.849 43.5855C8.8065 43.1933 7.86338 42.5675 7.089 41.7541C6.29025 40.9641 5.67517 40.0013 5.289 38.9366C4.962 38.0761 4.569 36.7776 4.464 34.3888C4.35 31.8071 4.326 31.0323 4.326 24.4877C4.326 17.9462 4.35 17.1745 4.464 14.5928C4.572 12.2041 4.962 10.9056 5.292 10.0419C5.727 8.89962 6.249 8.08194 7.089 7.22444C7.929 6.36694 8.727 5.83713 9.849 5.39306C10.695 5.05619 11.964 4.65806 14.304 4.54781C16.518 4.44369 17.376 4.41306 21.849 4.41V4.41613ZM36.813 8.48312C36.4348 8.48312 36.0603 8.55917 35.7109 8.70692C35.3615 8.85467 35.044 9.07123 34.7765 9.34423C34.5091 9.61724 34.297 9.94134 34.1522 10.298C34.0075 10.6547 33.933 11.037 33.933 11.4231C33.933 11.8092 34.0075 12.1915 34.1522 12.5482C34.297 12.9049 34.5091 13.229 34.7765 13.502C35.044 13.775 35.3615 13.9916 35.7109 14.1393C36.0603 14.2871 36.4348 14.3631 36.813 14.3631C37.5768 14.3631 38.3094 14.0534 38.8495 13.502C39.3896 12.9507 39.693 12.2029 39.693 11.4231C39.693 10.6434 39.3896 9.89559 38.8495 9.34423C38.3094 8.79287 37.5768 8.48312 36.813 8.48312ZM24.003 11.9192C22.3682 11.8932 20.7447 12.1994 19.2269 12.8201C17.7092 13.4407 16.3275 14.3633 15.1625 15.5343C13.9974 16.7053 13.0721 18.1011 12.4405 19.6406C11.809 21.1801 11.4837 22.8325 11.4837 24.5015C11.4837 26.1706 11.809 27.823 12.4405 29.3625C13.0721 30.902 13.9974 32.2978 15.1625 33.4688C16.3275 34.6397 17.7092 35.5624 19.2269 36.183C20.7447 36.8036 22.3682 37.1099 24.003 37.0838C27.2386 37.0323 30.3246 35.684 32.5949 33.33C34.8652 30.9759 36.1377 27.805 36.1377 24.5015C36.1377 21.1981 34.8652 18.0271 32.5949 15.6731C30.3246 13.3191 27.2386 11.9708 24.003 11.9192ZM24.003 16.3323C26.125 16.3323 28.1601 17.1928 29.6606 18.7246C31.161 20.2563 32.004 22.3338 32.004 24.5C32.004 26.6662 31.161 28.7437 29.6606 30.2754C28.1601 31.8072 26.125 32.6677 24.003 32.6677C21.881 32.6677 19.8459 31.8072 18.3454 30.2754C16.845 28.7437 16.002 26.6662 16.002 24.5C16.002 22.3338 16.845 20.2563 18.3454 18.7246C19.8459 17.1928 21.881 16.3323 24.003 16.3323Z" fill="white"/>
                              </svg>';
					$html .= $this->_render_date_post( $settings, $item );
					$html .= $this->_render_caption( $settings, $item );
					$html .= '</div></div>';
				}
				$html .= '</a></div>';

			}
		}

		return $html;
	}

	public function _render_caption( $settings, $item ) {
		$caption_length = ( ! empty( $settings['caption_length'] ) & $settings['caption_length'] > 0 ) ? $settings['caption_length'] : 60;
		if ( $settings['show_caption'] && ! empty( $item['caption'] ) ) {
			return '<p class="caption-text">' . substr( $item['caption'], 0, intval( $caption_length ) ) . '...</p>';
		}
	}

	public function _render_date_post( $settings, $item ) {
		if ( $settings['show_date'] ) {
			return '<div class="tp-instagram__meta">' . date( "d M Y", strtotime( $item['timestamp'] ) ) . '</div>';
		}
	}
}
