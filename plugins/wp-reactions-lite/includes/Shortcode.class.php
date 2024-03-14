<?php

namespace WP_Reactions\Lite;

/*
Class for Shortcode Handling
*/

class Shortcode {

	static function build( $options ) {
		return self::output( $options );
	}

	static function output( $atts ) {
		global $wpra_lite, $post, $wpdb;

		$post_related = array(
			'post_id'      => '',
			'start_counts' => '',
		);

		$defaults = array_merge( Config::$default_options, $post_related );
		$a        = shortcode_atts( $defaults, $atts );

		$post_id      = $a['post_id'] == '' ? $post->ID : $a['post_id'];
		$start_counts = $wpra_lite->getCountsTotal( $post_id );

		$tbl = Config::$tbl_reacted_users;

		$already = '';
		if ( isset( $_COOKIE['react_id'] ) ) {
			$already = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT emoji_id FROM $tbl WHERE bind_id = %s and react_id = %s",
					$post_id, $_COOKIE['react_id']
				)
			);
		}

		$title_styles = "color: {$a['title_color']};";
		$title_styles .= "font-size: {$a['title_size']};";
		$title_styles .= "font-weight: {$a['title_weight']};";
		if ( $a['show_title'] == 'false' ) {
			$title_styles .= 'display: none';
		}

		$reactions_styles = "border-color: {$a['border_color']};";
		$reactions_styles .= "border-width: {$a['border_width']};";
		$reactions_styles .= "border-radius: {$a['border_radius']};";
		$reactions_styles .= "border-style: {$a['border_style']};";

		if ( $a['bgcolor_trans'] == 'true' ) {
			$reactions_styles .= 'background: transparent;';
		} else {
			$reactions_styles .= "background: {$a['bgcolor']};";
		}

		if ( $a['shadow'] == 'false' ) {
			$reactions_styles .= 'box-shadow: none;';
		}

		$flex_aligns = array(
			'left'   => 'flex-start',
			'right'  => 'flex-end',
			'center' => 'center',
		);

		$wrap_styles = "justify-content: {$flex_aligns[$a['align']]};";

		$share_platforms_out = '';
		if ( $a['enable_share_buttons'] != 'false' ):
			$social_wrap_class = ' wpra-share-buttons-' . $a['social']['button_type'];
			$social_icon_color = '#ffffff';
			$social_btn_style  = "border-radius: {$a['social']['border_radius']};";
			ob_start();
			?>
            <div class="wpra-share-wrap <?php echo $social_wrap_class; ?>" style="<?php Helper::echoIf($a['enable_share_buttons'] == 'always', 'display: flex;');?>">
				<?php foreach ( Config::$default_options['social_platforms'] as $platform => $status ):
					if ( $a['social_platforms'] [ $platform ] == 'true' ):
						$label = empty( $a['social_labels'] [ $platform ] ) ? Config::$default_options['social_labels'][ $platform ] : $a['social_labels'][ $platform ];
						if ( $a['social']['button_type'] == 'bordered' ):
							$social_icon_color = Config::SOCIAL_PLATFORMS[ $platform ]['color'];
						endif; ?>
                        <a class="share-btn share-btn-<?php echo $platform; ?>" data-platform="<?php echo $platform; ?>" style="<?php echo $social_btn_style; ?>">
                            <span class="share-btn-icon">
                                <?php Helper::getSocialIcon( $platform, $social_icon_color ); ?>
                            </span>
                            <span><?php echo esc_html($label); ?></span>
                        </a>
					<?php endif;
				endforeach; ?>
            </div> <!-- end of share buttons -->
			<?php
			$share_platforms_out = ob_get_clean();
		endif;
		ob_start(); ?>
        <div class="wpra-reactions-wrap wpra-plugin-container" style="<?php echo $wrap_styles; ?>;">
            <div class="wpra-reactions-container"
                 data-ver="<?php echo WPRA_LITE_VERSION; ?>"
                 data-post_id="<?php echo $post_id; ?>"
                 data-show_count="<?php echo $a['show_count']; ?>"
                 data-enable_share="<?php echo $a['enable_share_buttons']; ?>"
                 data-behavior="<?php echo $a['behavior']; ?>"
                 data-animation="<?php echo $a['animation']; ?>"
                 data-share_url="<?php echo get_permalink( $post ); ?>"
                 data-secure="<?php echo wp_create_nonce( 'wpra-public-action' ); ?>">
                <div class="wpra-call-to-action" style="<?php echo $title_styles; ?>"><?php echo esc_html($a['title_text']); ?></div>
                <div class="wpra-reactions wpra-static-emojis size-<?php echo $a['size']; ?>" style="<?php echo $reactions_styles; ?>">
					<?php foreach ( Config::$current_options['emojis'] as $emoji_id ):
						if ( $emoji_id != - 1 ):
							Helper::getTemplate( '/view/front/single-emoji', [
								'options'         => $a,
								'emoji_id'        => $emoji_id,
								'already'         => $already,
								'start_count'     => $start_counts[ $emoji_id ],
								'start_count_fmt' => Helper::formatCount( $start_counts[ $emoji_id ] ),
							] );
						endif;
					endforeach; ?>
                </div>
                {share_platforms}
            </div> <!-- end of reactions container -->
        </div> <!-- end of reactions wrap -->
		<?php
		$reactions_out = ob_get_clean();

		return str_replace( '{share_platforms}', $share_platforms_out, $reactions_out );
	}

} // end of class
