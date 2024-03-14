<?php
$quick_adsense_ads_displayed = 0;
$quick_adsense_ads_id        = [];
$quick_adsense_begin_end     = 0;

/**
 * Hook into the header to embed the Header tracking or ad code.
 */
add_action(
	'wp_head',
	function() {
		$settings = get_option( 'quick_adsense_settings' );
		if ( isset( $settings['header_embed_code'] ) && ( '' !== $settings['header_embed_code'] ) ) {
			echo wp_kses( $settings['header_embed_code'], quick_adsense_get_allowed_html() );
		}
	}
);

/**
 * Hook into the footer to embed the Footer tracking or ad code.
 */
add_action(
	'wp_footer',
	function() {
		$settings = get_option( 'quick_adsense_settings' );
		if ( isset( $settings['footer_embed_code'] ) && ( '' !== $settings['footer_embed_code'] ) ) {
			echo wp_kses( $settings['footer_embed_code'], quick_adsense_get_allowed_html() );
		}
	}
);

/**
 * Filter the post content to embed ads.
 */
add_filter(
	'the_content',
	function( $content ) {
		global $quick_adsense_ads_displayed;
		global $quick_adsense_ads_id;
		global $quick_adsense_begin_end;
		$settings = get_option( 'quick_adsense_settings' );

		if ( ! quick_adsense_postads_isactive( $settings, $content ) ) {
			$content = quick_adsense_content_clean_tags( $content );
			return $content;
		}
		/* Begin Enforce Max Ads Per Page Rule */
		$quick_adsense_ads_to_display = $settings['max_ads_per_page'];
		if ( strpos( $content, '<!--OffWidget-->' ) === false ) {
			for ( $i = 1; $i <= 10; $i++ ) {
				$widget_id                     = sanitize_title( str_replace( [ '(', ')' ], '', sprintf( 'AdsWidget%d (Quick Adsense)', $i ) ) );
				$quick_adsense_ads_to_display -= ( is_active_widget( true, $widget_id ) ) ? 1 : 0;
			}
		}
		if ( $quick_adsense_ads_displayed >= $quick_adsense_ads_to_display ) {
			$content = quick_adsense_content_clean_tags( $content );
			return $content;
		};
		/* End Enforce Max Ads Per Page Rule */

		/* Begin Check for Available Ad Blocks */
		if ( ! count( $quick_adsense_ads_id ) ) {
			for ( $i = 1; $i <= 10; $i++ ) {
				if ( isset( $settings[ 'onpost_ad_' . $i . '_content' ] ) && ! empty( $settings[ 'onpost_ad_' . $i . '_content' ] ) ) {
					if ( quick_adsense_advanced_postads_isactive( $settings, $i ) ) {
						array_push( $quick_adsense_ads_id, (string) $i );
					}
				}
			}
		}
		array_push( $quick_adsense_ads_id, 100 );

		if ( ! count( $quick_adsense_ads_id ) ) {
			$content = quick_adsense_content_clean_tags( $content );
			return $content;
		};
		/* End Check for Available Ad Blocks */

		/* Begin Insert StandIns for all Ad Blocks */
		$content = str_replace( '<p></p>', '##QA-TP1##', $content );
		$content = str_replace( '<p>&nbsp;</p>', '##QA-TP2##', $content );
		$offdef  = ( strpos( $content, '<!--OffDef-->' ) !== false );
		if ( ! $offdef ) {
			$quick_adsense_ads_id_cus = [];
			$cusads                   = 'CusAds';
			$cusrnd                   = 'CusRnd';

			$quick_adsense_enable_position_after_more_tag = ( ( isset( $settings['enable_position_after_more_tag'] ) ) ? $settings['enable_position_after_more_tag'] : '' );
			$quick_adsense_ad_after_more_tag              = ( ( isset( $settings['ad_after_more_tag'] ) ) ? $settings['ad_after_more_tag'] : '' );

			$quick_adsense_enable_position_before_last_para = ( ( isset( $settings['enable_position_before_last_para'] ) ) ? $settings['enable_position_before_last_para'] : '' );
			$quick_adsense_ad_before_last_para              = ( ( isset( $settings['ad_before_last_para'] ) ) ? $settings['ad_before_last_para'] : '' );

			$quick_adsense_enable_position_beginning_of_post = ( ( isset( $settings['enable_position_beginning_of_post'] ) ) ? $settings['enable_position_beginning_of_post'] : '' );
			$quick_adsense_ad_beginning_of_post              = ( ( isset( $settings['ad_beginning_of_post'] ) ) ? $settings['ad_beginning_of_post'] : '' );

			$quick_adsense_enable_position_middle_of_post = ( ( isset( $settings['enable_position_middle_of_post'] ) ) ? $settings['enable_position_middle_of_post'] : '' );
			$quick_adsense_ad_middle_of_post              = ( ( isset( $settings['ad_middle_of_post'] ) ) ? $settings['ad_middle_of_post'] : '' );

			$quick_adsense_enable_position_end_of_post = ( ( isset( $settings['enable_position_end_of_post'] ) ) ? $settings['enable_position_end_of_post'] : '' );
			$quick_adsense_ad_end_of_post              = ( ( isset( $settings['ad_end_of_post'] ) ) ? $settings['ad_end_of_post'] : '' );

			for ( $i = 1; $i <= 3; $i++ ) {
				$quick_adsense_enable_position_after_para[ $i ]      = ( ( isset( $settings[ 'enable_position_after_para_option_' . $i ] ) ) ? $settings[ 'enable_position_after_para_option_' . $i ] : '' );
				$quick_adsense_ad_after_para[ $i ]                   = ( ( isset( $settings[ 'ad_after_para_option_' . $i ] ) ) ? $settings[ 'ad_after_para_option_' . $i ] : '' );
				$quick_adsense_position_after_para[ $i ]             = ( ( isset( $settings[ 'position_after_para_option_' . $i ] ) ) ? $settings[ 'position_after_para_option_' . $i ] : '' );
				$quick_adsense_enable_jump_position_after_para[ $i ] = ( ( isset( $settings[ 'enable_jump_position_after_para_option_' . $i ] ) ) ? $settings[ 'enable_jump_position_after_para_option_' . $i ] : '' );
			}

			for ( $i = 1; $i <= 1; $i++ ) {
				$quick_adsense_enable_position_after_image      = ( ( isset( $settings[ 'enable_position_after_image_option_' . $i ] ) ) ? $settings[ 'enable_position_after_image_option_' . $i ] : '' );
				$quick_adsense_ad_after_image                   = ( ( isset( $settings[ 'ad_after_image_option_' . $i ] ) ) ? $settings[ 'ad_after_image_option_' . $i ] : '' );
				$quick_adsense_position_after_image             = ( ( isset( $settings[ 'position_after_image_option_' . $i ] ) ) ? $settings[ 'position_after_image_option_' . $i ] : '' );
				$quick_adsense_enable_jump_position_after_image = ( ( isset( $settings[ 'enable_jump_position_after_image_option_' . $i ] ) ) ? $settings[ 'enable_jump_position_after_image_option_' . $i ] : '' );
			}

			if ( 0 === $quick_adsense_ad_beginning_of_post ) {
				$quick_adsense_ad_beginning_of_post_stand_in = $cusrnd;
			} else {
				$quick_adsense_ad_beginning_of_post_stand_in = $cusads . $quick_adsense_ad_beginning_of_post;
				array_push( $quick_adsense_ads_id_cus, $quick_adsense_ad_beginning_of_post );
			};
			if ( 0 === $quick_adsense_ad_after_more_tag ) {
				$quick_adsense_ad_after_more_tag_stand_in = $cusrnd;
			} else {
				$quick_adsense_ad_after_more_tag_stand_in = $cusads . $quick_adsense_ad_after_more_tag;
				array_push( $quick_adsense_ads_id_cus, $quick_adsense_ad_after_more_tag );
			};
			if ( 0 === $quick_adsense_ad_middle_of_post ) {
				$quick_adsense_ad_middle_of_post_stand_in = $cusrnd;
			} else {
				$quick_adsense_ad_middle_of_post_stand_in = $cusads . $quick_adsense_ad_middle_of_post;
				array_push( $quick_adsense_ads_id_cus, $quick_adsense_ad_middle_of_post );
			};
			if ( 0 === $quick_adsense_ad_before_last_para ) {
				$quick_adsense_ad_before_last_para_stand_in = $cusrnd;
			} else {
				$quick_adsense_ad_before_last_para_stand_in = $cusads . $quick_adsense_ad_before_last_para;
				array_push( $quick_adsense_ads_id_cus, $quick_adsense_ad_before_last_para );
			};
			if ( 0 === $quick_adsense_ad_end_of_post ) {
				$quick_adsense_ad_end_of_post_stand_in = $cusrnd;
			} else {
				$quick_adsense_ad_end_of_post_stand_in = $cusads . $quick_adsense_ad_end_of_post;
				array_push( $quick_adsense_ads_id_cus, $quick_adsense_ad_end_of_post );
			};
			for ( $i = 1; $i <= 3; $i++ ) {
				if ( 0 === $quick_adsense_ad_after_para[ $i ] ) {
					$quick_adsense_ad_after_para_stand_in[ $i ] = $cusrnd;
				} else {
					$quick_adsense_ad_after_para_stand_in[ $i ] = $cusads . $quick_adsense_ad_after_para[ $i ];
					array_push( $quick_adsense_ads_id_cus, $quick_adsense_ad_after_para[ $i ] );
				};
			}
			if ( 0 === $quick_adsense_ad_after_image ) {
				$quick_adsense_ad_after_image_stand_in = $cusrnd;
			} else {
				$quick_adsense_ad_after_image_stand_in = $cusads . $quick_adsense_ad_after_image;
				array_push( $quick_adsense_ads_id_cus, $quick_adsense_ad_after_image );
			};

			if ( $quick_adsense_enable_position_middle_of_post && ( strpos( $content, '<!--OffMiddle-->' ) === false ) ) {
				if ( substr_count( strtolower( $content ), '</p>' ) >= 2 ) {
					$quick_adsense_selected_tag     = '</p>';
					$content                        = str_replace( '</P>', $quick_adsense_selected_tag, $content );
					$quick_adsense_temp_array       = explode( $quick_adsense_selected_tag, $content );
					$j                              = 0;
					$k                              = strlen( $content ) / 2;
					$quick_adsense_temp_array_count = count( $quick_adsense_temp_array );
					for ( $i = 0; $i < $quick_adsense_temp_array_count; $i++ ) {
						$j += strlen( $quick_adsense_temp_array[ $i ] ) + 4;
						if ( $j > $k ) {
							if ( ( $k - ( $j - strlen( $quick_adsense_temp_array[ $i ] ) ) ) > ( $j - $k ) && $i + 1 < count( $quick_adsense_temp_array ) ) {
								$quick_adsense_temp_array[ $i + 1 ] = '<!--' . $quick_adsense_ad_middle_of_post_stand_in . '-->' . $quick_adsense_temp_array[ $i + 1 ];
							} else {
								$quick_adsense_temp_array[ $i ] = '<!--' . $quick_adsense_ad_middle_of_post_stand_in . '-->' . $quick_adsense_temp_array[ $i ];
							}
							break;
						}
						$quick_adsense_temp_array_count = count( $quick_adsense_temp_array );
					}
					$content = implode( $quick_adsense_selected_tag, $quick_adsense_temp_array );
				}
			}
			if ( $quick_adsense_enable_position_after_more_tag && ( strpos( $content, '<!--OffAfMore-->' ) === false ) ) {
				$content = str_replace( '<span id="more-' . get_the_ID() . '"></span>', '<!--' . $quick_adsense_ad_after_more_tag_stand_in . '-->', $content );
			}
			if ( $quick_adsense_enable_position_beginning_of_post && ( strpos( $content, '<!--OffBegin-->' ) === false ) ) {
				$content = '<!--' . $quick_adsense_ad_beginning_of_post_stand_in . '-->' . $content;
			}
			if ( $quick_adsense_enable_position_end_of_post && ( strpos( $content, '<!--OffEnd-->' ) === false ) ) {
				$content = $content . '<!--' . $quick_adsense_ad_end_of_post_stand_in . '-->';
			}
			if ( $quick_adsense_enable_position_before_last_para && ( strpos( $content, '<!--OffBfLastPara-->' ) === false ) ) {
				$quick_adsense_selected_tag = '<p>';
				$content                    = str_replace( '<P>', $quick_adsense_selected_tag, $content );
				$quick_adsense_temp_array   = explode( $quick_adsense_selected_tag, $content );
				if ( count( $quick_adsense_temp_array ) > 2 ) {
					$content = implode( $quick_adsense_selected_tag, array_slice( $quick_adsense_temp_array, 0, count( $quick_adsense_temp_array ) - 1 ) ) . '<!--' . $quick_adsense_ad_before_last_para_stand_in . '-->' . $quick_adsense_selected_tag . $quick_adsense_temp_array[ count( $quick_adsense_temp_array ) - 1 ];
				}
			}
			for ( $i = 1; $i <= 3; $i++ ) {
				if ( $quick_adsense_enable_position_after_para[ $i ] ) {
					$quick_adsense_selected_tag = '</p>';
					$content                    = str_replace( '</P>', $quick_adsense_selected_tag, $content );
					$quick_adsense_temp_array   = explode( $quick_adsense_selected_tag, $content );
					if ( (int) $quick_adsense_position_after_para[ $i ] < count( $quick_adsense_temp_array ) ) {
						$content = implode( $quick_adsense_selected_tag, array_slice( $quick_adsense_temp_array, 0, $quick_adsense_position_after_para[ $i ] ) ) . $quick_adsense_selected_tag . '<!--' . $quick_adsense_ad_after_para_stand_in[ $i ] . '-->' . implode( $quick_adsense_selected_tag, array_slice( $quick_adsense_temp_array, $quick_adsense_position_after_para[ $i ] ) );
					} elseif ( $quick_adsense_enable_jump_position_after_para[ $i ] ) {
						$content = implode( $quick_adsense_selected_tag, $quick_adsense_temp_array ) . '<!--' . $quick_adsense_ad_after_para_stand_in[ $i ] . '-->';
					}
				}
			}
			if ( $quick_adsense_enable_position_after_image ) {
				$quick_adsense_selected_tag = '<img';
				$j                          = '>';
				$k                          = '[/caption]';
				$l                          = '</a>';
				$content                    = str_replace( '<IMG', $quick_adsense_selected_tag, $content );
				$content                    = str_replace( '</A>', $l, $content );
				$quick_adsense_temp_array   = explode( $quick_adsense_selected_tag, $content );
				if ( (int) $quick_adsense_position_after_image < count( $quick_adsense_temp_array ) ) {
					$m = explode( $j, $quick_adsense_temp_array[ $quick_adsense_position_after_image ] );
					if ( count( $m ) > 1 ) {
						$n = explode( $k, $quick_adsense_temp_array[ $quick_adsense_position_after_image ] );
						$o = ( count( $n ) > 1 ) ? ( strpos( strtolower( $n[0] ), '[caption ' ) === false ) : false;
						$p = explode( $l, $quick_adsense_temp_array[ $quick_adsense_position_after_image ] );
						$q = ( count( $p ) > 1 ) ? ( strpos( strtolower( $p[0] ), '<a href' ) === false ) : false;
						if ( $quick_adsense_enable_jump_position_after_image && $o ) {
							$quick_adsense_temp_array[ $quick_adsense_position_after_image ] = implode( $k, array_slice( $n, 0, 1 ) ) . $k . "\r\n" . '<!--' . $quick_adsense_ad_after_image_stand_in . '-->' . "\r\n" . implode( $k, array_slice( $n, 1 ) );
						} elseif ( $q ) {
							$quick_adsense_temp_array[ $quick_adsense_position_after_image ] = implode( $l, array_slice( $p, 0, 1 ) ) . $l . "\r\n" . '<!--' . $quick_adsense_ad_after_image_stand_in . '-->' . "\r\n" . implode( $l, array_slice( $p, 1 ) );
						} else {
							$quick_adsense_temp_array[ $quick_adsense_position_after_image ] = implode( $j, array_slice( $m, 0, 1 ) ) . $j . "\r\n" . '<!--' . $quick_adsense_ad_after_image_stand_in . '-->' . "\r\n" . implode( $j, array_slice( $m, 1 ) );
						}
					}
					$content = implode( $quick_adsense_selected_tag, $quick_adsense_temp_array );
				}
			}
		}
		/* End Insert StandIns for all Ad Blocks */

		/* Begin Replace StandIns for all Ad Blocks */
		$content = '<!--EmptyClear-->' . $content . "\n" . '<div style="font-size: 0px; height: 0px; line-height: 0px; margin: 0; padding: 0; clear: both;"></div>';
		$content = quick_adsense_content_clean_tags( $content, true );
		$ismany  = ( ! is_single() && ! is_page() );
		$showall = ( ( isset( $settings['enable_all_possible_ads'] ) ) ? $settings['enable_all_possible_ads'] : '' );

		if ( ! $offdef ) {
			$quick_adsense_ads_id_cus_count = count( $quick_adsense_ads_id_cus );
			for ( $i = 1; $i <= $quick_adsense_ads_id_cus_count; $i++ ) {
				if ( $showall || ! $ismany || $i !== $quick_adsense_begin_end ) {
					if ( ( ( strpos( $content, '<!--' . $cusads . $quick_adsense_ads_id_cus[ $i - 1 ] . '-->' ) !== false ) || ( strpos( $content, '<!--' . $cusads . $quick_adsense_ads_id_cus[ $i - 1 ] . '-->' ) !== false ) ) && in_array( $quick_adsense_ads_id_cus[ $i - 1 ], $quick_adsense_ads_id, true ) ) {
						$content              = quick_adsense_content_replace_ads( $content, $cusads . $quick_adsense_ads_id_cus[ $i - 1 ], $quick_adsense_ads_id_cus[ $i - 1 ] );
						$content              = quick_adsense_content_replace_ads( $content, $cusads . $quick_adsense_ads_id_cus[ $i - 1 ], $quick_adsense_ads_id_cus[ $i - 1 ] );
						$quick_adsense_ads_id = quick_adsense_content_del_element( $quick_adsense_ads_id, array_search( $quick_adsense_ads_id_cus[ $i - 1 ], $quick_adsense_ads_id, true ) );
						$quick_adsense_ads_displayed++;
						if ( $quick_adsense_ads_displayed >= $quick_adsense_ads_to_display || ! count( $quick_adsense_ads_id ) ) {
							$content = quick_adsense_content_clean_tags( $content );
							return $content;
						};
						$quick_adsense_begin_end = $i;
						if ( ! $showall && $ismany ) {
							break;
						}
					}
				}
				$quick_adsense_ads_id_cus_count = count( $quick_adsense_ads_id_cus );
			}
		}

		if ( $showall || ! $ismany ) {
			$j                          = 0;
			$quick_adsense_ads_id_count = count( $quick_adsense_ads_id );
			for ( $i = 1; $i <= $quick_adsense_ads_id_count; $i++ ) {
				if ( strpos( $content, '<!--Ads' . $quick_adsense_ads_id[ $j ] . '-->' ) !== false ) {
					$content              = quick_adsense_content_replace_ads( $content, 'Ads' . $quick_adsense_ads_id[ $j ], $quick_adsense_ads_id[ $j ] );
					$quick_adsense_ads_id = quick_adsense_content_del_element( $quick_adsense_ads_id, $j );
					$quick_adsense_ads_displayed++;
					if ( ( $quick_adsense_ads_displayed >= $quick_adsense_ads_to_display ) || ! count( $quick_adsense_ads_id ) ) {
						$content = quick_adsense_content_clean_tags( $content );
						return $content;
					};
				} else {
					$j++;
				}
				$quick_adsense_ads_id_count = count( $quick_adsense_ads_id );
			}
		}

		if ( ( strpos( $content, '<!--' . $cusrnd . '-->' ) !== false ) && ( $showall || ! $ismany ) ) {
			$j = substr_count( $content, '<!--' . $cusrnd . '-->' );
			for ( $i = count( $quick_adsense_ads_id ); $i <= $j - 1; $i++ ) {
				array_push( $quick_adsense_ads_id, -1 );
			}
			shuffle( $quick_adsense_ads_id );
			for ( $i = 1; $i <= $j; $i++ ) {
				$content              = quick_adsense_content_replace_ads( $content, $cusrnd, $quick_adsense_ads_id[0] );
				$quick_adsense_ads_id = quick_adsense_content_del_element( $quick_adsense_ads_id, 0 );
				$quick_adsense_ads_displayed++;
				if ( ( $quick_adsense_ads_displayed >= $quick_adsense_ads_to_display ) || ! count( $quick_adsense_ads_id ) ) {
					$content = quick_adsense_content_clean_tags( $content );
					return $content;
				};
			}
		}
		if ( ( strpos( $content, '<!--' . $cusrnd . '-->' ) !== false ) && ( $showall || ! $ismany ) ) {
			$quick_adsense_ads_id = $quick_adsense_ads_id;
			$key                  = array_search( '100', $quick_adsense_ads_id, true );
			if ( false !== $key ) {
				unset( $quick_adsense_ads_id[ $key ] );
			}
			$j = substr_count( $content, '<!--' . $cusrnd . '-->' );
			for ( $i = count( $quick_adsense_ads_id ); $i <= $j - 1; $i++ ) {
				array_push( $quick_adsense_ads_id, -1 );
			}
			shuffle( $quick_adsense_ads_id );
			for ( $i = 1; $i <= $j; $i++ ) {
				$content              = quick_adsense_content_replace_ads( $content, $cusrnd, $quick_adsense_ads_id[0] );
				$quick_adsense_ads_id = quick_adsense_content_del_element( $quick_adsense_ads_id, 0 );
				$quick_adsense_ads_displayed++;
				if ( ( $quick_adsense_ads_displayed >= $quick_adsense_ads_to_display ) || ! count( $quick_adsense_ads_id ) ) {
					$content = quick_adsense_content_clean_tags( $content );
					return $content;
				};
			}
		}

		if ( strpos( $content, '<!--RndAds-->' ) !== false && ( $showall || ! $ismany ) ) {
			$quick_adsense_ads_id_tmp = [];
			shuffle( $quick_adsense_ads_id );
			for ( $i = 1; $i <= ( $quick_adsense_ads_to_display - $quick_adsense_ads_displayed ); $i++ ) {
				if ( $i <= count( $quick_adsense_ads_id ) ) {
					array_push( $quick_adsense_ads_id_tmp, $quick_adsense_ads_id[ $i - 1 ] );
				}
			}
			$j = substr_count( $content, '<!--RndAds-->' );
			for ( $i = count( $quick_adsense_ads_id_tmp ); $i <= $j - 1; $i++ ) {
				array_push( $quick_adsense_ads_id_tmp, -1 );
			}
			shuffle( $quick_adsense_ads_id_tmp );
			for ( $i = 1; $i <= $j; $i++ ) {
				$tmp                      = $quick_adsense_ads_id_tmp[0];
				$content                  = quick_adsense_content_replace_ads( $content, 'RndAds', $quick_adsense_ads_id_tmp[0] );
				$quick_adsense_ads_id_tmp = quick_adsense_content_del_element( $quick_adsense_ads_id_tmp, 0 );
				if ( -1 !== $tmp ) {
					$quick_adsense_ads_displayed++;
				};
				if ( $quick_adsense_ads_displayed >= $quick_adsense_ads_to_display || ! count( $quick_adsense_ads_id_tmp ) ) {
					$content = quick_adsense_content_clean_tags( $content );
					return $content;
				};
			}
		}
		/* End Replace StandIns for all Ad Blocks */

		$content = quick_adsense_content_clean_tags( $content );
		return $content;
	}
);

/**
 * This function checks whether the ads are active on a post.
 *
 * @param array  $settings The user settings.
 * @param string $content The content.
 *
 * @return boolean true on success, false on faliure.
 */
function quick_adsense_postads_isactive( $settings, $content ) {
	if ( is_feed() ) {
		return false;
	} elseif ( strpos( $content, '<!--NoAds-->' ) !== false ) {
		return false;
	} elseif ( strpos( $content, '<!--OffAds-->' ) !== false ) {
		return false;
	} elseif ( is_single() && ! ( isset( $settings['enable_on_posts'] ) ) ) {
		return false;
	} elseif ( is_page() && ! ( isset( $settings['enable_on_pages'] ) ) ) {
		return false;
	} elseif ( is_home() && ! ( isset( $settings['enable_on_homepage'] ) ) ) {
		return false;
	} elseif ( is_category() && ! ( isset( $settings['enable_on_categories'] ) ) ) {
		return false;
	} elseif ( is_archive() && ! ( isset( $settings['enable_on_archives'] ) ) ) {
		return false;
	} elseif ( is_tag() && ! ( isset( $settings['enable_on_tags'] ) ) ) {
		return false;
	} elseif ( is_user_logged_in() && ( isset( $settings['disable_for_loggedin_users'] ) ) ) {
		return false;
	} else {
		return true;
	}
}

/**
 * This function removes the quicktags from the content.
 *
 * @param string  $content The content.
 * @param boolean $trimonly Just trim (remove p tags around the quicktag) or completly remove the quicktag.
 *
 * @return string The processed content.
 */
function quick_adsense_content_clean_tags( $content, $trimonly = false ) {
	global $quick_adsense_ads_displayed;
	global $quick_adsense_ads_id;
	global $quick_adsense_begin_end;
	$quicktags = [
		'EmptyClear',
		'RndAds',
		'NoAds',
		'OffDef',
		'OffAds',
		'OffWidget',
		'OffBegin',
		'OffMiddle',
		'OffEnd',
		'OffBfMore',
		'OffAfLastPara',
		'CusRnd',
	];
	for ( $i = 1; $i <= 10; $i++ ) {
		array_push( $quicktags, 'CusAds' . $i );
		array_push( $quicktags, 'Ads' . $i );
	};
	foreach ( $quicktags as $quicktag ) {
		if ( ( strpos( $content, '<!--' . $quicktag . '-->' ) !== false ) || ( 'EmptyClear' === $quicktag ) ) {
			if ( $trimonly ) {
				$content = str_replace( '<p><!--' . $quicktag . '--></p>', '<!--' . $quicktag . '-->', $content );
			} else {
				$content = str_replace( [ '<p><!--' . $quicktag . '--></p>', '<!--' . $quicktag . '-->' ], '', $content );
				$content = str_replace( '##QA-TP1##', '<p></p>', $content );
				$content = str_replace( '##QA-TP2##', '<p>&nbsp;</p>', $content );
			}
		}
	}
	if ( ! $trimonly && ( is_single() || is_page() ) ) {
		$quick_adsense_ads_displayed = 0;
		$quick_adsense_ads_id        = [];
		$quick_adsense_begin_end     = 0;
	}
	return $content;
}

/**
 * This function replaces the quicktag with the actual ad.
 *
 * @param string  $content The content.
 * @param string  $quicktag The quicktag.
 * @param integer $ad_index The array index.
 *
 * @return string The processed content.
 */
function quick_adsense_content_replace_ads( $content, $quicktag, $ad_index ) {
	if ( strpos( $content, '<!--' . $quicktag . '-->' ) === false ) {
		return $content;
	}
	$settings         = get_option( 'quick_adsense_settings' );
	$onpost_ad_styles = [
		'',
		'float: left; margin: %1$dpx %1$dpx %1$dpx 0;',
		'float: none; margin:%1$dpx 0 %1$dpx 0; text-align:center;',
		'float: right; margin:%1$dpx 0 %1$dpx %1$dpx;',
		'float: none; margin:0px;',
	];

	if ( ( -1 !== $ad_index ) ) {
		$onpost_ad_alignment = ( ( isset( $settings[ 'onpost_ad_' . $ad_index . '_alignment' ] ) ) ? $settings[ 'onpost_ad_' . $ad_index . '_alignment' ] : '' );
		$onpost_ad_margin    = ( ( isset( $settings[ 'onpost_ad_' . $ad_index . '_margin' ] ) ) ? $settings[ 'onpost_ad_' . $ad_index . '_margin' ] : '' );
		$onpost_ad_style     = sprintf( $onpost_ad_styles[ (int) $onpost_ad_alignment ], $onpost_ad_margin );
		$onpost_ad_code      = ( ( isset( $settings[ 'onpost_ad_' . $ad_index . '_content' ] ) ) ? $settings[ 'onpost_ad_' . $ad_index . '_content' ] : '' );
		$onpost_ad_code      = '<div class="' . md5( get_bloginfo( 'url' ) ) . '" data-index="' . $ad_index . '" style="' . $onpost_ad_style . '">' . "\n" . $onpost_ad_code . "\n" . '</div>' . "\n";
	} else {
		$onpost_ad_code = '';
	}
	$content = explode( '<!--' . $quicktag . '-->', $content, 2 );
	return $content[0] . $onpost_ad_code . $content[1];
}

/**
 * Delete the selected item from the array by index.
 *
 * @param array   $quick_adsense_temp_array The input array.
 * @param integer $index The array index.
 *
 * @return array The processed array.
 */
function quick_adsense_content_del_element( $quick_adsense_temp_array, $index ) {
	$copy = [];
	if ( function_exists( 'quick_adsense_postads_update_impressions' ) ) {
		quick_adsense_postads_update_impressions( $quick_adsense_temp_array[ $index ] );
	}
	$quick_adsense_temp_array_count = count( $quick_adsense_temp_array );
	for ( $i = 0; $i < $quick_adsense_temp_array_count; $i++ ) {
		if ( $index !== $i ) {
			array_push( $copy, $quick_adsense_temp_array[ $i ] );
			$quick_adsense_temp_array_count = count( $quick_adsense_temp_array );
		}
	}
	return $copy;
}

/**
 * This function checks if the selected ad is active basedon the user settings.
 *
 * @param array   $settings The user settings.
 * @param integer $index The ad index.
 *
 * @return boolean true on success, false on faliure.
 */
function quick_adsense_advanced_postads_isactive( $settings, $index ) {
	$mobile_detect = new Mobile_Detect();
	// Begin Device Type.
	if ( isset( $settings[ 'onpost_ad_' . $index . '_hide_device_mobile' ] ) && $mobile_detect->isMobile() ) {
		return false;
	}
	if ( isset( $settings[ 'onpost_ad_' . $index . '_hide_device_tablet' ] ) && $mobile_detect->isTablet() ) {
		return false;
	}
	if ( isset( $settings[ 'onpost_ad_' . $index . '_hide_device_desktop' ] ) && ! $mobile_detect->isMobile() && ! $mobile_detect->isTablet() ) {
		return false;
	}
	// End Device Type.
	// Begin Visitor Source.
	$referer = quick_adsense_get_value( $_SERVER, 'HTTP_REFERER' );
	if ( '' === $referer ) {
		if ( isset( $settings[ 'onpost_ad_' . $index . '_hide_visitor_direct' ] ) ) {
			return false;
		}
	} else {
		if ( preg_match( '/www\.google.*|search\.msn.*|search\.yahoo.*|www\.bing.*|msxml\.excite\.com|search.lycos\.com|www\.alltheweb\.com|search\.aol\.com|ask\.com|www\.hotbot\.com|www\.metacrawler\.com|search\.netscape\.com|go\.google\.com|dpxml\.webcrawler\.com|search\.earthlink\.net|www\.ask\.co\.uk/i', $referer ) ) {
			if ( isset( $settings[ 'onpost_ad_' . $index . '_hide_visitor_searchengine' ] ) ) {
				return false;
			}
		} else {
			if ( isset( $settings[ 'onpost_ad_' . $index . '_hide_visitor_indirect' ] ) ) {
				return false;
			}
		}
	}
	// End Visitor Source.
	// Begin Visitor Type.
	if ( is_user_logged_in() ) {
		if ( isset( $settings[ 'onpost_ad_' . $index . '_hide_visitor_loggedin' ] ) ) {
			return false;
		}
	} else {
		if ( isset( $settings[ 'onpost_ad_' . $index . '_hide_visitor_guest' ] ) ) {
			return false;
		}
	}
	if ( isset( $settings[ 'onpost_ad_' . $index . '_hide_visitor_bot' ] ) && $mobile_detect->is( 'Bot' ) ) {
		return false;
	}
	if (
		isset( $settings[ 'onpost_ad_' . $index . '_hide_visitor_knownbrowser' ] ) ||
		isset( $settings[ 'onpost_ad_' . $index . '_hide_visitor_unknownbrowser' ] )
	) {
		if (
			$mobile_detect->match( '/msie|firefox|safari|chrome|edge|opera|netscape|maxthon|konqueror|mobile/i' )
		) {
			if ( isset( $settings[ 'onpost_ad_' . $index . '_hide_visitor_knownbrowser' ] ) ) {
				return false;
			}
		} else {
			if ( isset( $settings[ 'onpost_ad_' . $index . '_hide_visitor_unknownbrowser' ] ) ) {
				return false;
			}
		}
	}
	// End Visitor Type.
	// Begin Geotargeting.
	if ( isset( $settings[ 'onpost_ad_' . $index . '_limit_visitor_country' ] ) && ( is_array( $settings[ 'onpost_ad_' . $index . '_limit_visitor_country' ] ) ) && ( count( $settings[ 'onpost_ad_' . $index . '_limit_visitor_country' ] ) > 0 ) ) {
		$user_ip = quick_adsense_get_value( $_SERVER, 'REMOTE_ADDR' );
		if ( '' !== $user_ip ) {
			$geo_ip       = new \iriven\GeoIPCountry();
			$country_code = $geo_ip->resolve( $user_ip );
			if ( ! in_array( $country_code, $settings[ 'onpost_ad_' . $index . '_limit_visitor_country' ], true ) ) {
				return false;
			}
		}
	}
	// End Geotargeting.
	return true;
}

/**
 * This function increments the ad impressions for the ad at the selected index.
 *
 * @param integer $index The array index.
 */
function quick_adsense_postads_update_impressions( $index ) {
	$settings = get_option( 'quick_adsense_settings' );
	if ( isset( $settings ) && isset( $settings[ 'onpost_ad_' . $index . '_enable_stats' ] ) ) {
		$stats = get_option( 'quick_adsense_onpost_ad_' . $index . '_stats' );
		if ( isset( $stats ) && is_array( $stats ) ) {
			if ( isset( $stats[ gmdate( 'dmY' ) ] ) ) {
				$stats[ gmdate( 'dmY' ) ]['i'] += 1;
			} else {
				$stats[ gmdate( 'dmY' ) ] = [
					'i' => 1,
					'c' => 0,
				];
				$stats_count              = count( $stats );
				while ( $stats_count > 30 ) {
					array_shift( $stats );
					$stats_count = count( $stats );
				}
			}
		} else {
			$stats = [
				gmdate( 'dmY' ) => [
					'i' => 1,
					'c' => 0,
				],
			];
		}
		update_option( 'quick_adsense_onpost_ad_' . $index . '_stats', $stats );
	}
}

/**
 * Add inline script to frontend to record ad stats.
 */
add_action(
	'wp_enqueue_scripts',
	function() {
		wp_enqueue_script( 'jquery' );
		wp_add_inline_script(
			'jquery',
			quick_adsense_load_file(
				'templates/js/script-frontend-stats.php',
				[
					'target'   => md5( get_bloginfo( 'url' ) ),
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'nonce'    => wp_create_nonce( 'quick-adsense-stats' ),
				]
			)
		);
	}
);


add_action( 'wp_ajax_quick_adsense_onpost_ad_click', 'quick_adsense_onpost_ad_click' );
add_action( 'wp_ajax_nopriv_quick_adsense_onpost_ad_click', 'quick_adsense_onpost_ad_click' );
/**
 * Ajax handler for "quick_adsense_onpost_ad_click" action which updates the ad click stats.
 */
function quick_adsense_onpost_ad_click() {
	if ( isset( $_POST['quick_adsense_nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['quick_adsense_nonce'] ), 'quick-adsense-stats' ) ) {
		if ( isset( $_POST['quick_adsense_onpost_ad_index'] ) ) {
			$index    = sanitize_key( $_POST['quick_adsense_onpost_ad_index'] );
			$settings = get_option( 'quick_adsense_settings' );
			if ( isset( $settings ) && isset( $settings[ 'onpost_ad_' . $index . '_enable_stats' ] ) ) {
				$stats = get_option( 'quick_adsense_onpost_ad_' . $index . '_stats' );
				if ( isset( $stats ) && is_array( $stats ) ) {
					if ( isset( $stats[ gmdate( 'dmY' ) ] ) ) {
						$stats[ gmdate( 'dmY' ) ]['c'] += 1;
					} else {
						$stats[ gmdate( 'dmY' ) ] = [
							'i' => 0,
							'c' => 1,
						];
						$stats_count              = count( $stats );
						while ( $stats_count > 30 ) {
							array_shift( $stats );
							$stats_count = count( $stats );
						}
					}
				} else {
					$stats = [
						gmdate( 'dmY' ) => [
							'i' => 0,
							'c' => 1,
						],
					];
				}
				update_option( 'quick_adsense_onpost_ad_' . $index . '_stats', $stats );
				wp_send_json_success();
			}
		}
	}
	wp_send_json_error();
}

