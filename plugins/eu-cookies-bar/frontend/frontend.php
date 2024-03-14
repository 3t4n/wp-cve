<?php

/**
 * Class EU_COOKIES_BAR_Frontend_Frontend
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EU_COOKIES_BAR_Frontend_Frontend {
	protected $settings;
	protected $cookies;

	public function __construct() {
		$this->cookies  = array();
		$this->settings = EU_COOKIES_BAR_Data::get_instance();
		add_action( 'wp_loaded', array( $this, 'block_cookies' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'wp_footer', array( $this, 'cookies_bar_html' ) );
	}

	public function block_cookies() {
		$this->cookies = $_COOKIE;
		if ( ! $this->settings->get_params( 'enable' ) ) {
			return;
		}
		if ( current_user_can( 'manage_options' ) ) {
			return;
		}
		$strictly_necessary        = $this->settings->get_params( 'strictly_necessary' ) ? explode( ',', $this->settings->get_params( 'strictly_necessary' ) ) : array();
		$strictly_necessary[]      = 'eu_cookies_bar';
		$strictly_necessary[]      = 'eu_cookies_bar_block';
		$strictly_necessary[]      = 'eu_cookies_bar_decline';
		$strictly_necessary[]      = 'wordpress_test_cookie';
		$strictly_necessary        = array_unique( array_map( 'trim', $strictly_necessary ) );
		$strictly_necessary_family = $this->settings->get_params( 'strictly_necessary_family' ) ? explode( ',', $this->settings->get_params( 'strictly_necessary_family' ) ) : array();
		$strictly_necessary_family = array_unique( array_map( 'trim', $strictly_necessary_family ) );
		if ( ( ! isset( $_COOKIE['eu_cookies_bar'] ) && $this->settings->get_params( 'block_until_accept' ) ) || isset( $_COOKIE['eu_cookies_bar_decline'] ) ) {
			if ( count( headers_list() ) ) {
				foreach ( headers_list() as $header ) {
					if ( preg_match( '/Set-Cookie: (.+?)=/si', $header, $match ) ) {
						if ( in_array( $match[1], $strictly_necessary ) ) {
							continue;
						}
						if ( count( $strictly_necessary_family ) ) {
							$flag = false;
							foreach ( $strictly_necessary_family as $strictly_necessary_family_pat ) {
								if ( preg_match( '/^' . $strictly_necessary_family_pat . '(|.+?)/si', $match[1] ) ) {
									$flag = true;
									break;
								}
							}
							if ( $flag ) {
								continue;
							}
						}
						if ( version_compare( phpversion(), '5.3.0', '>=' ) ) {
							header_remove( 'Set-Cookie' ); // php 5.3
						} else {
							header( 'Set-Cookie:' ); // php 5.2
						}
					}
				}
			}
			if ( count( $_COOKIE ) ) {
				foreach ( $_COOKIE as $item => $value ) {

					if ( in_array( $item, $strictly_necessary ) ) {
						continue;
					}
					if ( count( $strictly_necessary_family ) ) {
						$flag = false;
						foreach ( $strictly_necessary_family as $strictly_necessary_family_pat ) {
							if ( preg_match( '/^' . $strictly_necessary_family_pat . '(|.+?)/si', $item ) ) {
								$flag = true;
								break;
							}
						}
						if ( $flag ) {
							continue;
						}
					}
					$this->setcookie( $item, '', ( time() - 8640000 ) );
					if ( isset( $_COOKIE[ $item ] ) ) {
						unset( $_COOKIE[ $item ] );
					}
				}
			}
		} elseif ( isset( $_COOKIE['eu_cookies_bar_block'] ) && ! empty( $_COOKIE['eu_cookies_bar_block'] ) ) {
			$block_cookies = explode( ',', sanitize_text_field( $_COOKIE['eu_cookies_bar_block'] ) );
			$block_cookies = array_unique( array_map( 'trim', $block_cookies ) );
			if ( count( $block_cookies ) ) {
				foreach ( $block_cookies as $name ) {
					if ( count( $strictly_necessary ) && in_array( $name, $strictly_necessary ) ) {
						continue;
					}
					if ( count( $strictly_necessary_family ) ) {
						$flag = false;
						foreach ( $strictly_necessary_family as $strictly_necessary_family_pat ) {
							if ( preg_match( '/^' . $strictly_necessary_family_pat . '(|.+?)/si', $name ) ) {
								$flag = true;
								break;
							}
						}
						if ( $flag ) {
							continue;
						}
					}
					$this->setcookie( $name, '', ( time() - 8640000 ) );
					if ( isset( $_COOKIE[ $name ] ) ) {
						unset( $_COOKIE[ $name ] );
					}
				}
				if ( count( headers_list() ) ) {
					foreach ( headers_list() as $header ) {
						if ( preg_match( '/Set-Cookie: (.+?)=/si', $header, $match ) ) {
							if ( in_array( $match[1], $strictly_necessary ) ) {
								continue;
							}
							if ( count( $strictly_necessary_family ) ) {
								$flag = false;
								foreach ( $strictly_necessary_family as $strictly_necessary_family_pat ) {
									if ( preg_match( '/^' . $strictly_necessary_family_pat . '(|.+?)/si', $match[1] ) ) {
										$flag = true;
										break;
									}
								}
								if ( $flag ) {
									continue;
								}
							}
							if ( in_array( $match[1], $block_cookies ) ) {
								if ( version_compare( phpversion(), '5.3.0', '>=' ) ) {
									header_remove( 'Set-Cookie' ); // php 5.3
								} else {
									header( 'Set-Cookie:' ); // php 5.2
								}
							}
						}
					}
				}

			}
		}

	}

	public function setcookie( $name, $value, $expire = 0, $secure = false ) {
		if ( ! headers_sent() ) {
			setcookie( $name, $value, $expire, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, $secure );
		} elseif ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			headers_sent( $file, $line );
			trigger_error( "{$name} cookie cannot be set - headers already sent by {$file} on line {$line}", E_USER_NOTICE ); // @codingStandardsIgnoreLine
		}
	}

	public function wp_enqueue_scripts() {
		if ( ! $this->settings->get_params( 'enable' ) ) {
			return;
		}
		wp_enqueue_style( 'eu-cookies-bar-icons', EU_COOKIES_BAR_CSS . 'eu-cookies-bar-icons.css', array(), EU_COOKIES_BAR_VERSION );
		wp_enqueue_style( 'eu-cookies-bar-style', EU_COOKIES_BAR_CSS . 'eu-cookies-bar.css', array(), EU_COOKIES_BAR_VERSION );
		$css = '';
		if ( ! isset( $_COOKIE['eu_cookies_bar'] ) ) {
			$css .= '.eu-cookies-bar-cookies-bar-wrap{';
			if ( $this->settings->get_params( 'cookies_bar_font_size' ) ) {
				$css .= 'font-size:' . $this->settings->get_params( 'cookies_bar_font_size' ) . 'px;';
			}
			if ( $this->settings->get_params( 'cookies_bar_color' ) ) {
				$css .= 'color:' . $this->settings->get_params( 'cookies_bar_color' ) . ';';
			}
			if ( $this->settings->get_params( 'cookies_bar_margin' ) ) {
				$css .= 'margin:' . $this->settings->get_params( 'cookies_bar_margin' ) . 'px;';
			}
			if ( $this->settings->get_params( 'cookies_bar_padding' ) ) {
				$css .= 'padding:' . $this->settings->get_params( 'cookies_bar_padding' ) . 'px;';
			}
			if ( $this->settings->get_params( 'cookies_bar_border_radius' ) ) {
				$css .= 'border-radius:' . $this->settings->get_params( 'cookies_bar_border_radius' ) . 'px;';
			}
			$opacity    = ( $this->settings->get_params( 'cookies_bar_opacity' ) !== '' ) ? ( $this->settings->get_params( 'cookies_bar_opacity' ) ) : 0.7;
			$background = ( $this->settings->get_params( 'cookies_bar_bg_color' ) !== '' ) ? ( $this->settings->get_params( 'cookies_bar_bg_color' ) ) : '#000000';
			$css        .= 'background:' . eu_cookies_bar_hex2rgba( $background, $opacity ) . ';';
			$css        .= '}';
			if ( $this->settings->get_params( 'cookies_bar_show_button_accept' ) ) {
				$css .= '.eu-cookies-bar-cookies-bar-button-accept{';
				if ( $this->settings->get_params( 'cookies_bar_button_accept_color' ) ) {
					$css .= 'color:' . $this->settings->get_params( 'cookies_bar_button_accept_color' ) . ';';
				}
				if ( $this->settings->get_params( 'cookies_bar_button_accept_bg_color' ) ) {
					$css .= 'background:' . $this->settings->get_params( 'cookies_bar_button_accept_bg_color' ) . ';';
				}
				if ( $this->settings->get_params( 'cookies_bar_button_accept_border_radius' ) ) {
					$css .= 'border-radius:' . $this->settings->get_params( 'cookies_bar_button_accept_border_radius' ) . 'px;';
				}
				$css .= '}';
			}
			if ( $this->settings->get_params( 'cookies_bar_show_button_decline' ) ) {
				$css .= '.eu-cookies-bar-cookies-bar-button-decline{';
				if ( $this->settings->get_params( 'cookies_bar_button_decline_color' ) ) {
					$css .= 'color:' . $this->settings->get_params( 'cookies_bar_button_decline_color' ) . ';';
				}
				if ( $this->settings->get_params( 'cookies_bar_button_decline_bg_color' ) ) {
					$css .= 'background:' . $this->settings->get_params( 'cookies_bar_button_decline_bg_color' ) . ';';
				}
				if ( $this->settings->get_params( 'cookies_bar_button_decline_border_radius' ) ) {
					$css .= 'border-radius:' . $this->settings->get_params( 'cookies_bar_button_decline_border_radius' ) . 'px;';
				}
				$css .= '}';
			}
		}
		if ( $this->settings->get_params( 'user_cookies_settings_enable' ) ) {
			$css .= '.eu-cookies-bar-cookies-bar-settings-header{';
			if ( $this->settings->get_params( 'user_cookies_settings_heading_color' ) ) {
				$css .= 'color:' . $this->settings->get_params( 'user_cookies_settings_heading_color' ) . ';';
			}
			if ( $this->settings->get_params( 'user_cookies_settings_heading_bg_color' ) ) {
				$css .= 'background:' . $this->settings->get_params( 'user_cookies_settings_heading_bg_color' ) . ';';
			}
			$css .= '}';
			$css .= '.eu-cookies-bar-cookies-bar-settings-save-button{';
			if ( $this->settings->get_params( 'user_cookies_settings_button_save_color' ) ) {
				$css .= 'color:' . $this->settings->get_params( 'user_cookies_settings_button_save_color' ) . ';';
			}
			if ( $this->settings->get_params( 'user_cookies_settings_button_save_bg_color' ) ) {
				$css .= 'background:' . $this->settings->get_params( 'user_cookies_settings_button_save_bg_color' ) . ';';
			}
			$css .= '}';
		}
		if ( $this->settings->get_params( 'custom_css' ) ) {
			$css .= $this->settings->get_params( 'custom_css' );
		}
		wp_add_inline_style( 'eu-cookies-bar-style', $css );
		wp_enqueue_script( 'eu-cookies-bar-script', EU_COOKIES_BAR_JS . 'eu-cookies-bar.js', array( 'jquery' ) );
		$strictly_necessary        = $this->settings->get_params( 'strictly_necessary' ) ? explode( ',', $this->settings->get_params( 'strictly_necessary' ) ) : array();
		$strictly_necessary        = array_unique( array_map( 'trim', $strictly_necessary ) );
		$strictly_necessary_family = $this->settings->get_params( 'strictly_necessary_family' ) ? explode( ',', $this->settings->get_params( 'strictly_necessary_family' ) ) : array();
		$strictly_necessary_family = array_unique( array_map( 'trim', $strictly_necessary_family ) );
		wp_localize_script( 'eu-cookies-bar-script', 'eu_cookies_bar_params', array(
			'cookies_bar_on_close'         => $this->settings->get_params( 'cookies_bar_on_close' ),
			'cookies_bar_on_scroll'        => $this->settings->get_params( 'cookies_bar_on_scroll' ),
			'cookies_bar_on_page_redirect' => $this->settings->get_params( 'cookies_bar_on_page_redirect' ),
			'block_until_accept'           => $this->settings->get_params( 'block_until_accept' ),
			'strictly_necessary'           => $strictly_necessary,
			'strictly_necessary_family'    => $strictly_necessary_family,
			'expire_time'                  => current_time( 'timestamp', true ) + 86400 * absint( $this->settings->get_params( 'expire' ) ),
			'cookiepath'                   => COOKIEPATH,
			'user_cookies_settings_enable' => $this->settings->get_params( 'user_cookies_settings_enable' ),
		) );
	}

	public function cookies_bar_html() {

		if ( ! $this->settings->get_params( 'enable' ) ) {
			return;
		}
		if ( ! isset( $_COOKIE['eu_cookies_bar'] ) ) {

			?>
            <div class="eu-cookies-bar-cookies-bar-wrap eu-cookies-bar-cookies-bar-position-<?php echo esc_attr( $this->settings->get_params( 'cookies_bar_position' ) ) ?>">
                <div class="eu-cookies-bar-cookies-bar">
                    <div class="eu-cookies-bar-cookies-bar-message">
                        <div>
							<?php echo wp_kses_post( force_balance_tags( $this->settings->get_params( 'cookies_bar_message' ) ) ); ?>
							<?php
							if ( $this->settings->get_params( 'privacy_policy_url' ) ) {
								?>
                                <a target="_blank"
                                   href="<?php echo esc_url( $this->settings->get_params( 'privacy_policy_url' ) ) ?>"><?php esc_html_e( 'View more', 'eu-cookies-bar' ) ?></a>
								<?php
							} elseif ( get_option( 'wp_page_for_privacy_policy', '' ) ) {
								?>
                                <a target="_blank"
                                   href="<?php echo esc_url( get_page_link( (int) get_option( 'wp_page_for_privacy_policy', '' ) ) ) ?>"><?php esc_html_e( 'View more', 'eu-cookies-bar' ) ?></a>
								<?php
							}
							?>
                        </div>
                    </div>
                    <div class="eu-cookies-bar-cookies-bar-button-container">

                        <div class="eu-cookies-bar-cookies-bar-button-wrap">
							<?php
							if ( $this->settings->get_params( 'user_cookies_settings_enable' ) ) {
								?>
                                <div class="eu-cookies-bar-cookies-bar-button eu-cookies-bar-cookies-bar-button-settings">
                                    <span><?php esc_html_e( 'Cookies settings', 'eu-cookies-bar' ); ?></span>
                                </div>
								<?php
							}
							if ( $this->settings->get_params( 'cookies_bar_show_button_accept' ) ) {
								?>
                                <div class="eu-cookies-bar-cookies-bar-button eu-cookies-bar-cookies-bar-button-accept">
                                    <span class="eu-cookies-bar-tick"><?php echo esc_html( $this->settings->get_params( 'cookies_bar_button_accept_title' ) ); ?></span>
                                </div>
								<?php
							}
							if ( $this->settings->get_params( 'cookies_bar_show_button_decline' ) ) {
								?>
                                <div class="eu-cookies-bar-cookies-bar-button eu-cookies-bar-cookies-bar-button-decline">
                                    <span class="eu-cookies-bar-decline"><?php echo esc_html( $this->settings->get_params( 'cookies_bar_button_decline_title' ) ) ?></span>
                                </div>
								<?php
							}
							if ( $this->settings->get_params( 'cookies_bar_show_button_close' ) ) {
								?>
                                <div class="eu-cookies-bar-cookies-bar-button eu-cookies-bar-cookies-bar-button-close">
                                    <span class="eu-cookies-bar-close"></span>
                                </div>
								<?php
							}
							?>
                        </div>

                    </div>
                </div>
            </div>
			<?php

		}
		if ( $this->settings->get_params( 'user_cookies_settings_enable' ) ) {
			?>
            <div class="eu-cookies-bar-cookies-bar-settings-wrap">
                <div class="eu-cookies-bar-cookies-bar-settings-wrap-container">
                    <div class="eu-cookies-bar-cookies-bar-settings-overlay">
                    </div>
                    <div class="eu-cookies-bar-cookies-bar-settings">
						<?php
						?>
                        <div class="eu-cookies-bar-cookies-bar-settings-header">
                            <span class="eu-cookies-bar-cookies-bar-settings-header-text"><?php echo esc_html( $this->settings->get_params( 'user_cookies_settings_heading_title' ) ); ?></span>
                            <span class="eu-cookies-bar-close eu-cookies-bar-cookies-bar-settings-close"></span>
                        </div>
                        <div class="eu-cookies-bar-cookies-bar-settings-nav">
                            <div class="eu-cookies-bar-cookies-bar-settings-privacy eu-cookies-bar-cookies-bar-settings-nav-active">
								<?php esc_html_e( 'Privacy & Cookies policy', 'eu-cookies-bar' ); ?>
                            </div>
                            <div class="eu-cookies-bar-cookies-bar-settings-cookie-list"><?php esc_html_e( 'Cookies list', 'eu-cookies-bar' ); ?></div>
                        </div>
                        <div class="eu-cookies-bar-cookies-bar-settings-content">
                            <table class="eu-cookies-bar-cookies-bar-settings-content-child eu-cookies-bar-cookies-bar-settings-content-child-inactive">
                                <tbody>
                                <tr>
                                    <th><?php esc_html_e( 'Cookie name', 'eu-cookies-bar' ); ?></th>
                                    <th><?php esc_html_e( 'Active', 'eu-cookies-bar' ); ?></th>
                                </tr>
								<?php
								$cookies                   = $this->cookies;
								$block_cookies             = ( isset( $cookies['eu_cookies_bar_block'] ) && $cookies['eu_cookies_bar_block'] ) ? explode( ',', $cookies['eu_cookies_bar_block'] ) : array();
								$block_cookies             = array_unique( array_map( 'trim', $block_cookies ) );
								$strictly_necessary        = $this->settings->get_params( 'strictly_necessary' ) ? ( explode( ',', $this->settings->get_params( 'strictly_necessary' ) ) ) : array();
								$strictly_necessary[]      = 'eu_cookies_bar';
								$strictly_necessary[]      = 'eu_cookies_bar_block';
								$strictly_necessary[]      = 'eu_cookies_bar_decline';
								$strictly_necessary[]      = 'wordpress_test_cookie';
								$strictly_necessary        = array_unique( array_map( 'trim', $strictly_necessary ) );
								$strictly_necessary_family = $this->settings->get_params( 'strictly_necessary_family' ) ? ( explode( ',', $this->settings->get_params( 'strictly_necessary_family' ) ) ) : array();
								$strictly_necessary_family = array_unique( array_map( 'trim', $strictly_necessary_family ) );
								$cookies                   = array_unique( array_merge( array_keys( $cookies ), $block_cookies ) );
								sort( $cookies );
								if ( ( ! isset( $_COOKIE['eu_cookies_bar'] ) ) || isset( $_COOKIE['eu_cookies_bar_decline'] ) ) {
									if ( count( $cookies ) ) {
										if ( $this->settings->get_params( 'block_until_accept' ) ) {
											foreach ( $cookies as $key => $cookie_name ) {
												if ( count( $strictly_necessary_family ) ) {
													$flag = false;
													foreach ( $strictly_necessary_family as $strictly_necessary_family_pat ) {
														if ( preg_match( '/^' . $strictly_necessary_family_pat . '(|.+?)/si', $cookie_name ) ) {
															?>
                                                            <tr>
                                                                <td><?php echo esc_html( $cookie_name ); ?></td>
                                                                <td><input type="checkbox" checked disabled></td>
                                                            </tr>
															<?php
															$flag = true;
															break;
														}
													}
													if ( $flag ) {
														continue;
													}
												}
												if ( in_array( $cookie_name, $strictly_necessary ) ) {
													?>
                                                    <tr>
                                                        <td>
															<?php echo esc_html( $cookie_name ); ?>
                                                        </td>
                                                        <td>
                                                            <input type="checkbox"
                                                                   value="<?php echo esc_html( $cookie_name ); ?>"
                                                                   checked
                                                                   disabled>
                                                        </td>
                                                    </tr>
													<?php
												} else {
													?>
                                                    <tr>
                                                        <td>
                                                            <label for="<?php echo esc_html( $cookie_name ); ?>"><?php echo esc_html( $cookie_name ); ?></label>
                                                        </td>
                                                        <td>
                                                            <input type="checkbox"
                                                                   id="<?php echo esc_attr( $cookie_name ); ?>"
                                                                   class="eu-cookies-bar-cookie-checkbox"
                                                                   value="<?php echo esc_attr( $cookie_name ); ?>">
                                                        </td>
                                                    </tr>
													<?php
												}
											}
										} else {
											foreach ( $cookies as $key => $cookie_name ) {
												if ( count( $strictly_necessary_family ) ) {
													$flag = false;
													foreach ( $strictly_necessary_family as $strictly_necessary_family_pat ) {
														if ( preg_match( '/^' . $strictly_necessary_family_pat . '(|.+?)/si', $cookie_name ) ) {
															?>
                                                            <tr>
                                                                <td><?php echo esc_html( $cookie_name ); ?></td>
                                                                <td><input type="checkbox" checked disabled></td>
                                                            </tr>
															<?php
															$flag = true;
															break;
														}
													}
													if ( $flag ) {
														continue;
													}
												}
												if ( in_array( $cookie_name, $strictly_necessary ) ) {
													?>
                                                    <tr>
                                                        <td>
															<?php echo esc_html( $cookie_name ); ?>
                                                        </td>
                                                        <td>
                                                            <input type="checkbox"
                                                                   value="<?php echo esc_attr( $cookie_name ); ?>"
                                                                   checked
                                                                   disabled>
                                                        </td>
                                                    </tr>
													<?php
												} else {
													?>
                                                    <tr>
                                                        <td>
                                                            <label for="<?php echo esc_attr( $cookie_name ); ?>"><?php echo esc_html( $cookie_name ); ?></label>
                                                        </td>
                                                        <td>
                                                            <input type="checkbox"
                                                                   id="<?php echo esc_attr( $cookie_name ); ?>"
                                                                   class="eu-cookies-bar-cookie-checkbox"
                                                                   value="<?php echo esc_attr( $cookie_name ); ?>"
                                                                   checked>
                                                        </td>
                                                    </tr>
													<?php
												}
											}
										}

									}
								} elseif ( isset( $_COOKIE['eu_cookies_bar'] ) ) {
									if ( count( $cookies ) ) {
										foreach ( $cookies as $key => $cookie_name ) {
											if ( count( $strictly_necessary_family ) ) {
												$flag = false;
												foreach ( $strictly_necessary_family as $strictly_necessary_family_pat ) {
													if ( preg_match( '/^' . $strictly_necessary_family_pat . '(|.+?)/si', $cookie_name ) ) {
														?>
                                                        <tr>
                                                            <td><?php echo esc_html( $cookie_name ); ?></td>
                                                            <td><input type="checkbox" checked disabled></td>
                                                        </tr>
														<?php
														$flag = true;
														break;
													}
												}
												if ( $flag ) {
													continue;
												}
											}
											if ( in_array( $cookie_name, $strictly_necessary ) ) {
												?>
                                                <tr>
                                                    <td>
														<?php echo esc_html( $cookie_name ); ?>
                                                    </td>
                                                    <td>
                                                        <input type="checkbox"
                                                               value="<?php echo esc_attr( $cookie_name ); ?>"
                                                               checked
                                                               disabled>
                                                    </td>
                                                </tr>
												<?php
											} else {
												?>
                                                <tr>
                                                    <td>
                                                        <label for="<?php echo esc_attr( $cookie_name ); ?>"><?php echo esc_html( $cookie_name ); ?></label>
                                                    </td>
                                                    <td>
                                                        <input type="checkbox"
                                                               id="<?php echo esc_attr( $cookie_name ); ?>"
                                                               class="eu-cookies-bar-cookie-checkbox"
                                                               value="<?php echo esc_attr( $cookie_name ); ?>" <?php if ( ! in_array( $cookie_name, $block_cookies ) ) {
															echo esc_attr( 'checked' );
														} ?>>
                                                    </td>
                                                </tr>
												<?php
											}
										}
									}
								}
								?>
                                </tbody>
                            </table>
                            <div class="eu-cookies-bar-cookies-bar-settings-policy eu-cookies-bar-cookies-bar-settings-content-child">
								<?php echo do_shortcode( $this->settings->get_params( 'privacy_policy' ) ) ?>
                            </div>
                        </div>

                        <span class="eu-cookies-bar-cookies-bar-settings-save-button"><?php esc_html_e( 'Save settings', 'eu-cookies-bar' ) ?></span>

						<?php
						?>
                    </div>
                </div>
            </div>
			<?php
			if ( $this->settings->get_params( 'user_cookies_settings_bar_position' ) != 'hide' ) {
				?>
                <div class="eu-cookies-bar-cookies-settings-call-container <?php echo esc_attr( 'eu-cookies-bar-cookies-settings-call-position-' . $this->settings->get_params( 'user_cookies_settings_bar_position' ) ) ?>">
                    <div class="eu-cookies-bar-cookies-settings-call-button eu-cookies-bar-cookies-bar-button-settings">
                        <span><?php esc_html_e( 'Cookies settings', 'eu-cookies-bar' ); ?></span>
                    </div>
                </div>
				<?php
			}
		}
	}
}