<?php
if ( ! function_exists( 'eddmp_register_playlist_widget' ) ) {
	function eddmp_register_playlist_widget() {
		if ( ! class_exists( 'Easy_Digital_Downloads' ) ) {
			return;
		}
		return register_widget( 'EDDMP_PLAYLIST_WIDGET' );
	}
}
add_action( 'widgets_init', 'eddmp_register_playlist_widget' );

if ( ! class_exists( 'EDDMP_PLAYLIST_WIDGET' ) ) {
	class EDDMP_PLAYLIST_WIDGET extends WP_Widget {

		public function __construct() {
			$widget_ops = array(
				'classname'   => 'EDDMP_PLAYLIST_WIDGET',
				'description' => 'Includes a playlist with the audio files of downloads selected',
			);

			parent::__construct( 'EDDMP_PLAYLIST_WIDGET', 'Music Player for Easy Digital Downloads - Playlist', $widget_ops );
		}

		public function form( $instance ) {
			$instance = wp_parse_args(
				(array) $instance,
				array(
					'title'                      => '',
					'downloads_ids'              => '',
					'volume'                     => '',
					'highlight_current_download' => 0,
					'continue_playing'           => 0,
				)
			);

			$title                      = sanitize_text_field( $instance['title'] );
			$downloads_ids              = sanitize_text_field( $instance['downloads_ids'] );
			$volume                     = sanitize_text_field( $instance['volume'] );
			$highlight_current_download = sanitize_text_field( $instance['highlight_current_download'] );
			$continue_playing           = sanitize_text_field( $instance['continue_playing'] );
			$player_style               = sanitize_text_field( $instance['player_style'] );

			$play_all = sanitize_text_field(
				$GLOBALS['EDDMusicPlayer']->get_global_attr(
					'_eddmp_play_all',
					// This option is only for compatibility with versions previous to 1.0.28
											$GLOBALS['EDDMusicPlayer']->get_global_attr(
												'play_all',
												0
											)
				)
			);
			$preload = sanitize_text_field(
				$GLOBALS['EDDMusicPlayer']->get_global_attr(
					'_eddmp_preload',
					// This option is only for compatibility with versions previous to 1.0.28
											$GLOBALS['EDDMusicPlayer']->get_global_attr(
												'preload',
												'metadata'
											)
				)
			);
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'music-player-for-easy-digital-downloads' ); ?>: <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'downloads_ids' ) ); ?>"><?php esc_html_e( 'Downloads IDs', 'music-player-for-easy-digital-downloads' ); ?>: <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'downloads_ids' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'downloads_ids' ) ); ?>" type="text" value="<?php echo esc_attr( $downloads_ids ); ?>" placeholder="<?php esc_attr_e( 'Downloads IDs separated by comma, or a * for all', 'music-player-for-easy-digital-downloads' ); ?>" /></label>
			</p>
			<p>
				<?php
					esc_html_e( 'Enter the ID of downloads separated by comma, or a * symbol to includes all downloads in the playlist.', 'music-player-for-easy-digital-downloads' );
				?>
			</p>
			<p>
				<label><?php esc_html_e( 'Volume (enter a number between 0 and 1)', 'music-player-for-easy-digital-downloads' ); ?>: <input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'volume' ) ); ?>" type="number" min="0" max="1" step="0.01" value="<?php echo esc_attr( $volume ); ?>" /></label>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'player_style' ) ); ?>"><?php esc_html_e( 'Player layout', 'music-player-for-easy-digital-downloads' ); ?>: </label>
			</p>
			<p>
				<label><input name="<?php echo esc_attr( $this->get_field_name( 'player_style' ) ); ?>" type="radio" value="mejs-classic" <?php echo ( ( 'mejs-classic' == $player_style ) ? 'checked' : '' ); ?> style="float:left; margin-top:8px;" /><img src="<?php print esc_url( EDDMP_PLUGIN_URL ); ?>/views/assets/skin1_btn.png" /></label>
			</p>
			<p>
				<label><input name="<?php echo esc_attr( $this->get_field_name( 'player_style' ) ); ?>" type="radio" value="mejs-ted" <?php echo ( ( 'mejs-ted' == $player_style ) ? 'checked' : '' ); ?> style="float:left; margin-top:8px;" /><img src="<?php print esc_url( EDDMP_PLUGIN_URL ); ?>/views/assets/skin2_btn.png" /></label>
			</p>
			<p>
				<label><input name="<?php echo esc_attr( $this->get_field_name( 'player_style' ) ); ?>" type="radio" value="mejs-wmp" <?php echo ( ( 'mejs-wmp' == $player_style ) ? 'checked' : '' ); ?> style="float:left; margin-top:16px;" /><img src="<?php print esc_url( EDDMP_PLUGIN_URL ); ?>/views/assets/skin3_btn.png" /></label>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'play_all' ) ); ?>"><?php esc_html_e( 'Play all', 'music-player-for-easy-digital-downloads' ); ?>: <input id="<?php echo esc_attr( $this->get_field_id( 'play_all' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'play_all' ) ); ?>" type="checkbox" <?php echo ( ( $play_all ) ? 'CHECKED' : '' );
				?> /></label>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'preload' ) ); ?>"><?php esc_html_e( 'Preload', 'music-player-for-easy-digital-downloads' ); ?>:</label><br />
				<label><input name="<?php echo esc_attr( $this->get_field_name( 'preload' ) ); ?>" type="radio" value="none" <?php echo ( ( 'none' == $preload ) ? 'CHECKED' : '' ); ?> /> None</label>
				<label><input name="<?php echo esc_attr( $this->get_field_name( 'preload' ) ); ?>" type="radio" value="metadata" <?php echo ( ( 'metadata' == $preload ) ? 'CHECKED' : '' ); ?> /> Metadata</label>
				<label><input name="<?php echo esc_attr( $this->get_field_name( 'preload' ) ); ?>" type="radio" value="auto" <?php echo ( ( 'auto' == $preload ) ? 'CHECKED' : '' ); ?> /> Auto</label>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'highlight_current_download' ) ); ?>"><?php esc_html_e( 'Highlight the current download', 'music-player-for-easy-digital-downloads' ); ?>: <input id="<?php echo esc_attr( $this->get_field_id( 'highlight_current_download' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'highlight_current_download' ) ); ?>" type="checkbox" <?php echo ( ( $highlight_current_download ) ? 'CHECKED' : '' ); ?> /></label>
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'continue_playing' ) ); ?>"><?php esc_html_e( 'Continue playing after navigate', 'music-player-for-easy-digital-downloads' ); ?>: <input id="<?php echo esc_attr( $this->get_field_id( 'continue_playing' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'continue_playing' ) ); ?>" type="checkbox" <?php echo ( ( $continue_playing ) ? 'CHECKED' : '' ); ?> value="1" /></label>
			</p>
			<p>
				<?php
					esc_html_e( 'Continue playing the same song at same position after navigate. You can experiment some delay because the music player should to load the audio file again, and in some mobiles devices, where the action of the user is required, the player cannot starting playing automatically.', 'music-player-for-easy-digital-downloads' );
				?>
			</p>
			<?php
		}

		public function update( $new_instance, $old_instance ) {
			$instance                               = $old_instance;
			$instance['title']                      = sanitize_text_field( $new_instance['title'] );
			$instance['downloads_ids']              = sanitize_text_field( $new_instance['downloads_ids'] );
			$instance['volume']                     = sanitize_text_field( $new_instance['volume'] );
			$instance['highlight_current_download'] = ( ! empty( $new_instance['highlight_current_download'] ) ) ? true : false;
			$instance['continue_playing']           = ( ! empty( $new_instance['continue_playing'] ) ) ? true : false;
			$instance['player_style']               = sanitize_text_field( $new_instance['player_style'] );

			$global_settings                    = get_option( 'eddmp_global_settings', array() );
			$global_settings['_eddmp_play_all'] = ( ! empty( $new_instance['play_all'] ) ) ? 1 : 0;
			$global_settings['_eddmp_preload']  = (
					! empty( $new_instance['preload'] ) &&
					in_array( $new_instance['preload'], array( 'none', 'metadata', 'auto' ) )
				) ? $new_instance['preload'] : 'metadata';

			update_option( 'eddmp_global_settings', $global_settings );

			return $instance;
		}

		public function widget( $args, $instance ) {
			if ( ! is_array( $args ) ) {
				$args = array();
			}
			extract( $args, EXTR_SKIP ); // phpcs:ignore WordPress.PHP.DontExtract

			$title = empty( $instance['title'] ) ? ' ' : apply_filters( 'widget_title', $instance['title'] );

			$attrs = array(
				'downloads_ids'              => $instance['downloads_ids'],
				'highlight_current_download' => $instance['highlight_current_download'],
				'continue_playing'           => $instance['continue_playing'],
				'player_style'               => $instance['player_style'],
			);

			if ( ! empty( $instance['volume'] ) && ( $volume = @floatval( $instance['volume'] ) ) != 0 ) { // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments
				$attrs['volume'] = min( 1, $volume );
			}

			$output = $GLOBALS['EDDMusicPlayer']->replace_playlist_shortcode( $attrs );

			if ( 0 == strlen( $output ) ) {
				return;
			}

			print $before_widget; // phpcs:ignore WordPress.Security.EscapeOutput
			if ( ! empty( $title ) ) {
				print $before_title . $title . $after_title; // phpcs:ignore WordPress.Security.EscapeOutput
			}
			print $output; // phpcs:ignore WordPress.Security.EscapeOutput
			print $after_widget; // phpcs:ignore WordPress.Security.EscapeOutput
		}
	} // End Class EDDMP_PLAYLIST_WIDGET
}
