<?php
if ( ! class_exists( 'CFB_Layouts' ) ) {

	class CFB_Layouts {

		// Declare class properties
		private $prefix;
		private $id;

		function __construct() {
			// variable for prefix
			$this->prefix = '_cfb_';
		}

		/**
		 * Layout handle method to handle the layouts in switch case.
		 *
		 * @param string $flip_layout The layout type.
		 * @param array  $atts The attributes.
		 * @param array  $entry The entry data.
		 * @param int    $i The iteration index.
		 * @return string HTML layout.
		 */
		public function layout_handle( $flip_layout, $atts, $entry, $i ) {
			$this->id    = $atts['id'];
			$id          = $this->id;
			$prefix      = $this->prefix;
			$flip_layout = get_post_meta( $id, $prefix . 'flip_layout', true );
			$effect      = get_post_meta( $id, $prefix . 'effect', true );
			$height      = get_post_meta( $id, $prefix . 'height', true ) ?: 'default';
			$icon_size   = get_post_meta( $id, $prefix . 'icon_size', true ) ?: '52px';
			$skincolor   = get_post_meta( $id, $prefix . 'skin_color', true ) ?: '#f4bf64';
			$cols        = get_post_meta( $id, $prefix . 'column', true );
			$entries     = get_post_meta( $id, $prefix . 'flip_repeat_group', true );
			$link_target = get_post_meta( $id, $prefix . 'LinkTarget', true ) ?: false;
			$flip_event  = get_post_meta( $id, $prefix . 'event', true ) ?: false;

			$dynamic_target = $link_target ? '_self' : '_blank';

			$flipbox_title        = $entry['flipbox_title'] ?? '';
			$back_desc            = mb_strimwidth( $entry['flipbox_desc'] ?? '', 0, $entry['flipbox_desc_length'] ?? '75', '...' );
			$single_f_c           = $entry['color_scheme'] ?? '';
			$flipbox_icon         = $entry['flipbox_icon'] ?? '';
			$flipbox_image        = $entry['flipbox_image'] ?? '';
			$flipbox_url          = $entry['flipbox_url'] ?? '';
			$front_desc           = mb_strimwidth( $entry['flipbox_label'] ?? '', 0, $entry['flipbox_desc_length'] ?? '75', '...' );
			$read_more_text       = $entry['read_more_link'] ?? '';
			$flipbox_color_scheme = $single_f_c ?: $skincolor;

			$front_desc_safe = wp_kses_post( $front_desc );
			$back_desc_safe  = wp_kses_post( $back_desc );
			// switch case start
			switch ( $flip_layout ) {
				// checking first case
				case 'dashed-with-icon':
					$flip_layout = 'layout-1';
					$layout_html = '';

					$layout_html .= '<div class="flex-' . esc_attr( $cols ) . ' cfb-box-wrapper">
                    <div class="flipbox-container cfb-' . esc_attr( $flip_layout ) . ' cfb-flip ' . esc_attr( $flip_event ) . '" data-effect="' . esc_attr( $effect ) . '" data-height="' . esc_attr( $height ) . '">
                    <div class="flipbox-front-layout cfb-data" style="border-color:' . esc_attr( $flipbox_color_scheme ) . '">';
					if ( ! empty( $flipbox_icon ) ) {
						$layout_html .= '<div class="flipbox-icon" style="font-size:' . esc_attr( $icon_size ) . '!important; color:' . esc_attr( $flipbox_color_scheme ) . '">
                        <i class="fa ' . esc_attr( $flipbox_icon ) . '"></i>
                        </div>';
					}
					$layout_html .= '<div class="flipbox-front-description">
                        <h4 style="color:' . esc_attr( $flipbox_color_scheme ) . '">' . esc_html( $flipbox_title ) . '</h4>
                        <p style="color:' . esc_attr( $flipbox_color_scheme ) . '">' . $front_desc_safe . '</p>
                        </div>
                    </div>
                    <div class="flipbox-back-layout cfb-data" style="background:' . esc_attr( $flipbox_color_scheme ) . ';border-color:' . esc_attr( $flipbox_color_scheme ) . '">
                        <p>' . $back_desc_safe . '</p>';
					if ( ! empty( $read_more_text ) && ! empty( $flipbox_url ) ) {
						$layout_html .= '<a target="' . esc_attr( $dynamic_target ) . '" href="' . esc_url( $flipbox_url ) . '" style="color:' . esc_attr( $flipbox_color_scheme ) . '" class="back-layout-btn">' . esc_html( $read_more_text ) . '</a>';
					}
						$layout_html .= '</div>
                    </div>
                    </div>';

					break;
				case 'with-image':
					// checking second case
					$flip_layout = 'layout-2';
					$layout_html = '';

					$layout_html         .= '<div class="flex-' . esc_attr( $cols ) . ' cfb-box-' . $i . ' cfb-box-wrapper">
                            <div class="flipbox-container cfb-' . esc_attr( $flip_layout ) . ' cfb-flip ' . esc_attr( $flip_event ) . '" data-effect="' . esc_attr( $effect ) . '" data-height="' . esc_attr( $height ) . '" >
                            <div class="flipbox-front-layout cfb-data">
                                <div class="flipbox-img">';
							$layout_html .= ! empty( $flipbox_image ) ? '<img src="' . esc_attr( $flipbox_image ) . '" alt="" />' : '<img src="' . CFB_URL . 'assets/images/black-background.jpg">';
							$layout_html .= '</div></div>
                            <div class="flipbox-back-layout cfb-data" style="background:' . esc_attr( $flipbox_color_scheme ) . '">
                            <h4>' . esc_html( $flipbox_title ) . '</h4>
                                <p>' . $back_desc_safe . '</p>';
					if ( ! empty( $read_more_text ) && ! empty( $flipbox_url ) ) {
						$layout_html .= '<a target="' . esc_attr( $dynamic_target ) . '" href="' . esc_url( $flipbox_url ) . '" style="color:' . esc_attr( $flipbox_color_scheme ) . '" class="back-layout-btn">' . esc_html( $read_more_text ) . '</a>';
					}
								$layout_html .= '</div>
                            </div>
                        </div>';

					break;
				case 'solid-with-icon':
					// checking thrid case
					$flip_layout = 'layout-3';
					$layout_html = '';

					$layout_html .= '<div class="flex-' . esc_attr( $cols ) . ' cfb-box-' . $i . ' cfb-box-wrapper">
                                <div class="flipbox-container cfb-' . esc_attr( $flip_layout ) . ' cfb-flip ' . esc_attr( $flip_event ) . '" data-effect="' . esc_attr( $effect ) . '" data-height="' . esc_attr( $height ) . '" >
                                  <div class="flipbox-front-layout cfb-data" style="border-color:' . esc_attr( $flipbox_color_scheme ) . '">';
					if ( ! empty( $flipbox_icon ) ) {
						$layout_html .= '<div class="flipbox-icon flipbox-solid-icon"  style="color:' . esc_attr( $flipbox_color_scheme ) . '">
                                      <i class="fa ' . esc_attr( $flipbox_icon ) . '" style="font-size:' . esc_attr( $icon_size ) . '!important"></i>
                                    </div>';
					}
								  $layout_html .= '<div class="flipbox-front-description">
                                      <h4  style="color:' . esc_attr( $flipbox_color_scheme ) . '">' . esc_html( $flipbox_title ) . '</h4>
                                      <p style="color:' . esc_attr( $flipbox_color_scheme ) . '">' . $front_desc_safe . '</p>
                                    </div>
                                  </div>
                                  <div class="flipbox-back-layout cfb-data" style="border-color:' . esc_attr( $flipbox_color_scheme ) . '">
                                    <p style="color:' . esc_attr( $flipbox_color_scheme ) . '">' . $back_desc_safe . '</p>';
					if ( ! empty( $read_more_text ) && ! empty( $flipbox_url ) ) {
						$layout_html .= '<a  target="' . esc_attr( $dynamic_target ) . '" style="color:' . esc_attr( $flipbox_color_scheme ) . '" href="' . esc_url( $flipbox_url ) . '" class="back-layout-link">' . esc_html( $read_more_text ) . '</a>';
					}
									$layout_html .= '</div>
                                </div>
                              </div>';

					break;
				case 'layout-4':
					// checking fourth case
					$layout_html = '';

					$layout_html                     .= '<div class="flex-' . esc_attr( $cols ) . ' cfb-box-' . $i . ' cfb-box-wrapper">
                                <div class="flipbox-container cfb-' . esc_attr( $flip_layout ) . ' cfb-flip ' . esc_attr( $flip_event ) . '" data-effect="' . esc_attr( $effect ) . '" data-height="' . esc_attr( $height ) . '">
                                  <div class="flipbox-front-layout cfb-data">
                                    <div class="flipbox-image-content">
                                      <div class="flipbox-image-top">';
										$layout_html .= ! empty( $flipbox_image ) ? '<img src="' . esc_attr( $flipbox_image ) . '" alt="" />' : '<img src="' . CFB_URL . 'assets/images' . '/layout-4.png" alt="" />';
					if ( ! empty( $flipbox_icon ) ) {
						$layout_html .= '<div class="flip-icon-bototm flipbox-icon" style="font-size:' . esc_attr( $icon_size ) . ';border-color:' . esc_attr( $flipbox_color_scheme ) . ';color:' . esc_attr( $flipbox_color_scheme ) . '">
                                            <i class="fa ' . esc_attr( $flipbox_icon ) . '"></i>
                                           </div>';
					}
										$layout_html .= '</div>
                                      <div class="flipbox-img-content">
                                        <h5 style="color:' . esc_attr( $flipbox_color_scheme ) . '">' . esc_html( $flipbox_title ) . '</h5>
                                      </div>
                                    </div>
                                    </div>
                                  <div class="flipbox-back-layout cfb-data" style="background-color:' . esc_attr( $flipbox_color_scheme ) . '">
                                    <p>' . $back_desc_safe . '</p>';
					if ( ! empty( $read_more_text ) && ! empty( $flipbox_url ) ) {
						$layout_html .= '<a target="' . esc_attr( $dynamic_target ) . '" href="' . esc_url( $flipbox_url ) . '"  class="back-layout-btn" style="color:' . esc_attr( $flipbox_color_scheme ) . '">' . esc_html( $read_more_text ) . '</a>';
					}
									$layout_html .= '</div>
                                </div>
                              </div>';

					break;
				case 'layout-5':
					// checking fifth case
					$layout_html = '';

					$layout_html .= '<div class="flex-' . esc_attr( $cols ) . ' cfb-box-' . $i . ' cfb-box-wrapper">
                        <div class="flipbox-container cfb-' . esc_attr( $flip_layout ) . ' cfb-flip ' . esc_attr( $flip_event ) . '" data-effect="' . esc_attr( $effect ) . '" data-height="' . esc_attr( $height ) . '" >
                            <div class="flipbox-front-layout flipbox-front-filled cfb-data"  style="background:' . esc_attr( $flipbox_color_scheme ) . '">';
					if ( ! empty( $flipbox_icon ) ) {
						$layout_html .= '<div class="flipbox-icon" style="font-size:' . esc_attr( $icon_size ) . '">
                                    <i class="fa ' . esc_attr( $flipbox_icon ) . '"></i>
                                </div>';
					}
							$layout_html .= '<div class="flipbox-front-description">
                                <h4>' . esc_html( $flipbox_title ) . '</h4>
                                <p>' . $front_desc_safe . '</p>
                            </div>
                            </div>
                            <div class="flipbox-back-layout cfb-data" style="background:' . esc_attr( $flipbox_color_scheme ) . '">
                                <p>' . $back_desc_safe . '</p>';
					if ( ! empty( $read_more_text ) && ! empty( $flipbox_url ) ) {
						$layout_html .= '<a target="' . esc_attr( $dynamic_target ) . '" href="' . esc_url( $flipbox_url ) . '" style="color:' . esc_attr( $flipbox_color_scheme ) . '" class="back-layout-btn">' . esc_html( $read_more_text ) . '</a>';
					}
							$layout_html .= '</div>
                        </div>
                    </div>';

					break;
				case 'layout-6':
					// checking sixth case
					$layout_html = '';

					$layout_html .= '<div class="flex-' . esc_attr( $cols ) . ' cfb-box-' . $i . ' cfb-box-wrapper">
                                <div class="flipbox-container cfb-' . esc_attr( $flip_layout ) . ' cfb-flip ' . esc_attr( $flip_event ) . '" data-effect="' . esc_attr( $effect ) . '" data-height="' . esc_attr( $height ) . '">
                                <div class="flipbox-front-layout cfb-data" style="border-color:' . esc_attr( $flipbox_color_scheme ) . '">';
					$layout_html .= ! empty( $flipbox_image ) ? '<div class="flipbox-img"><img src="' . esc_attr( $flipbox_image ) . '" alt="" /></div>' : '<img src="' . CFB_URL . 'assets/images' . '/layout-4.png">';
					$layout_html .= '</div>
                                <div class="flipbox-back-layout cfb-data" style="background-color:' . esc_attr( $flipbox_color_scheme ) . '">
                                    <h4>' . esc_html( $flipbox_title ) . '</h4>
                                    <p>' . $back_desc_safe . '</p>';
					if ( ! empty( $read_more_text ) && ! empty( $flipbox_url ) ) {
						$layout_html .= '<a target="' . esc_attr( $dynamic_target ) . '" href="' . esc_url( $flipbox_url ) . '" style="color:' . esc_attr( $flipbox_color_scheme ) . '" class="back-layout-btn">' . esc_html( $read_more_text ) . '</a>';
					}
					$layout_html .= '</div>
                                </div>
                            </div>';

					break;
				case 'layout-7':
					// checking seventh case
					$layout_html   = '';
					$flipbox_image = $flipbox_image ? $flipbox_image : CFB_URL . 'assets/images/black-background.jpg';

					$layout_html .= '<div class="flex-' . esc_attr( $cols ) . ' cfb-box-' . $i . ' cfb-box-wrapper">
                                <div class="flipbox-container cfb-' . esc_attr( $flip_layout ) . ' cfb-flip ' . esc_attr( $flip_event ) . '" data-effect="' . esc_attr( $effect ) . '" data-height="' . esc_attr( $height ) . '">
                                <div class="flipbox-front-layout flipbox-front-filled cfb-data" style="background:' . esc_attr( $flipbox_color_scheme ) . '">
                                    <div class="flipbox-front-description">
                                    <h4>' . esc_html( $flipbox_title ) . '</h4>
                                    <p>' . $front_desc_safe . '</p>
                                    </div>
                                </div>
                                <div class="flipbox-back-layout flipbox-background-img cfb-data" style="background-image: url(' . esc_attr( $flipbox_image ) . ');color:' . esc_attr( $flipbox_color_scheme ) . '">';
					if ( ! empty( $flipbox_icon ) ) {
						$layout_html .= '<div class="flipbox-icon flipbox-solid-icon" style="font-size:' . esc_attr( $icon_size ) . '">
                                        <i class="fa ' . esc_attr( $flipbox_icon ) . '"></i>
                                        </div>';
					}
								$layout_html .= '<p style="color:' . esc_attr( $flipbox_color_scheme ) . '">' . $back_desc_safe . '</p>';
					if ( ! empty( $read_more_text ) && ! empty( $flipbox_url ) ) {
						$layout_html .= '<a target="' . esc_attr( $dynamic_target ) . '" href="' . esc_url( $flipbox_url ) . '" style="color:' . esc_attr( $flipbox_color_scheme ) . '" class="back-layout-btn">' . esc_html( $read_more_text ) . '</a>';
					}
								$layout_html .= '</div>
                                </div>
                            </div>';

					break;
				case 'layout-8':
					// checking eighth case
					$layout_html   = '';
					$flipbox_image = $flipbox_image ? $flipbox_image : CFB_URL . 'assets/images/black-background.jpg';

					$layout_html .= '<div class="flex-' . esc_attr( $cols ) . ' cfb-box-' . $i . ' cfb-box-wrapper">
                                <div class="flipbox-container cfb-' . esc_attr( $flip_layout ) . ' cfb-flip ' . esc_attr( $flip_event ) . '" data-effect="' . esc_attr( $effect ) . '" data-height="' . esc_attr( $height ) . '" style="color:' . esc_attr( $flipbox_color_scheme ) . '">
                                  <div class="flipbox-front-layout flipbox-front-filled cfb-data" >
                                    <div class="flipbox-frontImg" style="background-image: url(' . esc_attr( $flipbox_image ) . ');">                
                                      <div class="flipbox-front-description" >';
					if ( ! empty( $flipbox_icon ) ) {
						$layout_html .= '<div class="flipbox-icon flipbox-solid-icon" style="font-size:' . esc_attr( $icon_size ) . '">
                                            <i class="fa ' . esc_attr( $flipbox_icon ) . '"></i>
                                          </div>';
					}
										$layout_html .= '<h4 style="color:' . esc_attr( $flipbox_color_scheme ) . '">' . esc_html( $flipbox_title ) . '</h4>
                                        <p style="color:' . esc_attr( $flipbox_color_scheme ) . '">' . $front_desc_safe . '</p>
                                      </div>
                                  </div>
                                  </div>
                                  <div class="flipbox-back-layout flipbox-background-img cfb-data" style="background-image: url(' . esc_attr( $flipbox_image ) . ');">
                                    <p style="color:' . esc_attr( $flipbox_color_scheme ) . '">' . $back_desc_safe . '</p>';
					if ( ! empty( $read_more_text ) && ! empty( $flipbox_url ) ) {
						$layout_html .= '<a target="' . esc_attr( $dynamic_target ) . '" href="' . esc_url( $flipbox_url ) . '" style="color:' . esc_attr( $flipbox_color_scheme ) . '" class="back-layout-btn">' . esc_html( $read_more_text ) . '</a>';
					}
									$layout_html .= '</div>
                                </div>
                              </div>';

					break;
				case 'layout-9':
					// checking nineth case
					$layout_html = '';

					$layout_html .= '<div class="flex-' . esc_attr( $cols ) . ' cfb-box-' . $i . ' cfb-box-wrapper">
                              <div class="flipbox-container facebook-icon cfb-' . esc_attr( $flip_layout ) . ' cfb-flip ' . esc_attr( $flip_event ) . '" data-effect="' . esc_attr( $effect ) . '" data-height="' . esc_attr( $height ) . '">
                                <div class="flipbox-front-layout cfb-data"  style="background:' . esc_attr( $flipbox_color_scheme ) . '">';
					if ( ! empty( $flipbox_icon ) ) {
						$layout_html .= '<div class="flipbox-icon flipbox-solid-icon" style="font-size:' . esc_attr( $icon_size ) . '">
                                    <i class="fa ' . esc_attr( $flipbox_icon ) . '"></i>
                                  </div>';
					}
					$layout_html .= '</div>
                                <div class="flipbox-back-layout cfb-data" style="color:' . esc_attr( $flipbox_color_scheme ) . '">
                                  <a target="' . esc_attr( $dynamic_target ) . '" href="' . esc_url( $flipbox_url ) . '">';
					if ( ! empty( $flipbox_icon ) ) {
						$layout_html .= '<div class="flipbox-icon flipbox-solid-icon" style="font-size:' . esc_attr( $icon_size ) . ';color:' . esc_attr( $flipbox_color_scheme ) . '">
                                        <i class="fa ' . esc_attr( $flipbox_icon ) . '"></i>
                                      </div>';
					}

					$layout_html .= '</a>  
                                </div>
                              </div>
                            </div>';

					break;
				default:
					// run for the default case
					return $layout_html;
					break;
			}
					// returning the $layout_html value
					return $layout_html;
		}
	}
}


