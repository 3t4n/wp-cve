<?php

class CodePeopleMediaPlayer {

	// PROPERTIES
	private $current_player_playlist; // playlist of current public player
	private $in_preview = false;
	private $observer_embeded = false;

	// METHODS
	private function transform_url( $url ) {
		return str_replace( ' ', '%20', wp_kses_decode_entities( $url ) );
	}

	private function is_media( $extension ) {
		return in_array( $extension, array( 'hls', 'mp4', 'webm', 'ogg', 'youtube', 'vimeo', 'mp3', 'wav' ) );
	}

	private function get_extension( $file, $type = '' ) {
		$file = strtolower( $file );
		if ( stripos( $file, 'youtube' ) !== false ) {
			$ext = 'youtube';
		} elseif ( stripos( $file, 'vimeo' ) !== false ) {
			$ext = 'vimeo';
		} else {
			$ext = substr( $file, strlen( $file ) - 4 );
			if ( '.' == $ext[0] ) {
				$ext = substr( $ext, 1 );
			}

			switch ( $ext ) {
				case 'm3u':
				case 'm3u8':
					return 'hls';
				break;
				case 'mp4':
				case 'm4v':
				case 'm4a':
				case 'mov':
					$ext = 'mp4';
					break;
				case 'webm':
				case 'webma':
				case 'webmv':
					$ext = 'webm';
					break;
				case 'wmv':
					$ext = 'wmv';
					break;
				case 'ogg':
				case 'oga':
				case 'ogv':
					$ext = 'ogg';
					break;
				case 'mp3':
					$ext = 'mp3';
					break;
				default:
					if ( 'audio' == $type ) {
						$ext = 'mp3';
					} elseif ( 'video' == $type ) {
						$ext = 'mp4';
					} else {
						$ext = $ext;
					}
					break;
			}
		}
		return $ext;
	}

	/*
		Register plugin
		Create database structure
	*/
	public function register_plugin( $networkwide ) {
		global $wpdb;

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {
			if ( $networkwide ) {
				$old_blog = $wpdb->blogid;
				// Get all blog ids
				$blogids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
				foreach ( $blogids as $blog_id ) {
					switch_to_blog( $blog_id );
					$this->_create_db_structure();
				}
				switch_to_blog( $old_blog );
				return;
			}
		}
		$this->_create_db_structure();
	}

	/*
		A new blog has been created in a multisite WordPress
	*/
	public function installing_new_blog( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
		global $wpdb;

		if ( is_plugin_active_for_network() ) {
			$current_blog = $wpdb->blogid;
			switch_to_blog( $blog_id );
			$this->_create_db_structure();
			switch_to_blog( $current_blog );
		}
	}

	/*
		Create the database structure for save player's data
	*/
	public function _create_db_structure() {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();

		$db_queries   = array();
		$db_queries[] = 'CREATE TABLE ' . $wpdb->prefix . CPMP_PLAYER . " (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			player_name VARCHAR(250) NOT NULL DEFAULT '',
			config LONGTEXT NULL,
			playlist LONGTEXT NULL,
            UNIQUE KEY id (id)
        ) $charset_collate;";

		dbDelta( $db_queries ); // Running the queries
	}

	public function _get_skin_list( &$selected_skin, $type, &$width, &$height ) {
		$skin_dir          = CPMP_PLUGIN_DIR . '/skins';
		$skins_arr         = array();
		$skins_list        = '';
		$skins_list_script = 'var cpmp_skin_list = [];';
		$c                 = 0;
		if ( file_exists( $skin_dir ) ) {
			$d = dir( $skin_dir );
			while ( false !== ( $entry = $d->read() ) ) {
				if ( '.' != $entry && '..' != $entry && is_dir( $skin_dir . '/' . $entry ) ) {
					$this_skin = $skin_dir . '/' . $entry . '/';
					if ( file_exists( $this_skin ) ) {
						$skin_data = parse_ini_file( $this_skin . 'config.ini', true );
						if ( isset( $skin_data['id'] ) ) {
							if ( empty( $selected_skin ) ) {
								$selected_skin = $skin_data['id'];
							}

							$skins_list        .= '
									<img
										src="' . esc_url( ( isset( $skin_data['thumbnail'] ) ) ? CPMP_PLUGIN_URL . '/skins/' . $entry . '/' . $skin_data['thumbnail'] : CPMP_PLUGIN_URL . '/images/thumbnail.jpg' ) . '"
										title="' . esc_attr( ( isset( $skin_data['name'] ) ) ? $skin_data['name'] : $skin_data['id'] ) . ' - Available"
										onclick="cpmp.set_skin(this, \'' . $skin_data['id'] . '\', \'' . ( ( isset( $skin_data[ $type ]['width'] ) ) ? $skin_data[ $type ]['width'] : '' ) . '\', \'' . ( ( isset( $skin_data[ $type ]['height'] ) ) ? $skin_data[ $type ]['height'] : '' ) . '\');"';
							$skins_list_script .= 'cpmp_skin_list[' . $c . ']="' . $skin_data['id'] . '";';
							$c++;

							if ( $selected_skin == $skin_data['id'] ) {
								$skins_list .= ' class="skin_selected" style="border: 2px solid #4291D1;margin-left:5px;cursor:pointer;" ';
								$width       = ( ( isset( $skin_data[ $type ]['width'] ) ) ? $skin_data[ $type ]['width'] : '' );
								$height      = ( ( isset( $skin_data[ $type ]['height'] ) ) ? $skin_data[ $type ]['height'] : '' );
							} else {
								$skins_list .= ' style=" style="border: 2px solid #FFF;margin-left:5px;cursor:pointer;" ';
							}

							$skins_list .= '/><script>' . $skins_list_script . '</script>';
						}
					}
				}
			}
			$d->close();
		}

		return $skins_list;
	}

	/*
		Create the settings page
	*/
	public function _paypal_buttons() {
		$p   = CPMP_PLUGIN_DIR . '/images/paypal_buttons';
		$d   = dir( $p );
		$str = '';
		while ( false !== ( $entry = $d->read() ) ) {
			if ( '.' != $entry && '..' != $entry && is_file( "$p/$entry" ) ) {
				$str .= "<input aria-label='" . esc_attr( __( 'PayPal Button', 'codepeople-media-player' ) ) . ' ' . $entry . "' type='radio' disabled />&nbsp;<img src='" . esc_url( CPMP_PLUGIN_URL . "/images/paypal_buttons/$entry" ) . "'/>&nbsp;&nbsp;";
			}
		}
		$d->close();
		return $str;
	}

	public function admin_page() {
		global $wpdb;
		wp_enqueue_media();
		?>
		<style>.cpm-disabled,.cpm-disabled *{color: #DDDDDD !important;}.cpm-disabled img{opacity: 0.5 !important;}</style>
		<h1><?php esc_html_e( 'Audio And Video Player', 'codepeople-media-player' ); ?></h1>
		<p  style="border:1px solid #E6DB55;margin-bottom:10px;padding:5px;background-color: #FFFFE0;"><?php _e( 'For any issues with the media player, go to our <a href="https://cpmediaplayer.dwbooster.com/contact-us" target="_blank">contact page</a> and leave us a message.', 'codepeople-media-player' ); // phpcs:ignore WordPress.Security.EscapeOutput
		?>
		<br />
		<?php _e( 'If you want test the premium version of CP Media Player go to the following links:<br/> <a href="https://demos.dwbooster.com/audio-and-video/wp-login.php" target="_blank">Administration area: Click to access the administration area demo</a><br/><a href="https://demos.dwbooster.com/audio-and-video/" target="_blank">Public page: Click to access the Public Page</a>', 'codepeople-media-player' ); // phpcs:ignore WordPress.Security.EscapeOutput
		?>
		</p>

		<?php
		if ( isset( $_POST['cpmp_player_create_update_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cpmp_player_create_update_nonce'] ) ), __FILE__ ) ) {
			// Save player's data
			// Constructs the configuration stdClass
			$conf = new stdClass();

			if ( ! empty( $_POST['cpmp_width'] ) ) {
				$conf->width = sanitize_text_field( wp_unslash( $_POST['cpmp_width'] ) );
			}
			if ( ! empty( $_POST['cpmp_height'] ) ) {
				$conf->height = sanitize_text_field( wp_unslash( $_POST['cpmp_height'] ) );
			}
			if ( ! empty( $_POST['cpmp_type'] ) ) {
				$conf->type = sanitize_text_field( wp_unslash( $_POST['cpmp_type'] ) );
			}
			if ( ! empty( $_POST['cpmp_skin'] ) ) {
				$conf->skin = sanitize_text_field( wp_unslash( $_POST['cpmp_skin'] ) );
			}
			if ( isset( $_POST['cpmp_autoplay'] ) ) {
				$conf->autoplay = 'autoplay';
			}
			if ( isset( $_POST['cpmp_show_playlist'] ) ) {
				$conf->playlist = true;
			}
			if ( isset( $_POST['cpmp_playlist_download_links'] ) ) {
				$conf->playlist_download_links = true;
			}
			if ( isset( $_POST['cpmp_loop'] ) ) {
				$conf->loop = 'loop';
			}
			$conf->preload = ( isset( $_POST['cpmp_preload'] ) ) ? 'auto' : 'none';
			$playlist      = isset( $_POST['cpmp_media_player_playlist'] ) ? json_decode( sanitize_text_field( wp_unslash( $_POST['cpmp_media_player_playlist'] ) ) ) : null;

			$data   = array(
				'player_name' => isset( $_POST['cpmp_player_name'] ) ? sanitize_text_field( wp_unslash( $_POST['cpmp_player_name'] ) ) : '',
				'config'      => serialize( $conf ),
				'playlist'    => serialize( $playlist ),
			);
			$format = array( '%s', '%s', '%s' );
			if ( empty( $_POST['cpmp_player_id'] ) ) {
				$wpdb->insert(
					$wpdb->prefix . CPMP_PLAYER,
					$data,
					$format
				);
			} else {
				$wpdb->update(
					$wpdb->prefix . CPMP_PLAYER,
					$data,
					array( 'id' => isset( $_POST['cpmp_player_id'] ) && is_numeric( $_POST['cpmp_player_id'] ) ? intval( $_POST['cpmp_player_id'] ) : 0 ),
					$format,
					'%d'
				);
			}
		}

		// Save general settings
		if ( isset( $_POST['cpmp_general_settings'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cpmp_general_settings'] ) ), __FILE__ ) ) {
			update_option( 'cpmp_play_all', isset( $_POST['cpmp_play_all'] ) ? 1 : 0 );
		}

		if (
			empty( $_POST['cpmp_action'] ) ||
			in_array( $_POST['cpmp_action'], [ 'remove', 'duplicate' ] )
	    ) {
			if ( isset( $_POST['player_id'] ) ) {

				$player_id = isset( $_POST['player_id'] ) && is_numeric( $_POST['player_id'] ) ? intval( $_POST['player_id'] ) : 0;

				if (
					isset( $_POST['cpmp_player_edition_nonce'] ) && isset( $_POST['cpmp_action'] ) &&
					wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cpmp_player_edition_nonce'] ) ), __FILE__ )
				) {
					if ( 'remove' == $_POST['cpmp_action'] ) {
						$wpdb->query(
							$wpdb->prepare( 'DELETE FROM ' . $wpdb->prefix . CPMP_PLAYER . ' WHERE id=%d', $player_id ) // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
						);
					}

					if ( 'duplicate' == $_POST['cpmp_action'] ) {
						$player_to_duplicate = $wpdb->get_row(
							$wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . CPMP_PLAYER . ' WHERE id=%d', $player_id ), // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
							ARRAY_A
						);

						if ( ! empty( $player_to_duplicate ) ) {
							unset( $player_to_duplicate['id'] );
							$wpdb->insert(
								$wpdb->prefix . CPMP_PLAYER,
								$player_to_duplicate
							);
						}
					}
				}
			}

			$sql     = 'SELECT * FROM ' . $wpdb->prefix . CPMP_PLAYER . ';';
			$players = $wpdb->get_results( $sql );  // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

			if ( count( $players ) ) {
				wp_enqueue_script( 'cpmp-admin', plugin_dir_url( __FILE__ ) . 'js/cpmp_admin.js', array( 'jquery' ), CPMP_VERSION, true );
				?>
				<style>#codepeople-media-playerbuyer_email{min-width:70%;margin-right:5px;}@media (max-width:710px) {.cpm-players-list tbody *{width:100%;clear:both;display:block;max-width:100%;margin:0;text-align:center;}.cpm-players-list tbody tr td:last-child{white-space:normal !important;}.cpm-players-list [type="button"]{margin-bottom:10px;}.cpm-players-list thead tr{display:flex;flex-wrap:wrap;}.cpm-players-list thead th{flex:1;}}</style>
				<div class="wrap">

					<!-- Players List -->
					<form method="post" action="<?php echo isset( $_SERVER['REQUEST_URI'] ) ? esc_url( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) : ''; ?>">
					<?php
						// create a custom nonce for submit verification later
						echo '<input type="hidden" name="cpmp_player_edition_nonce" value="' . esc_attr( wp_create_nonce( __FILE__ ) ) . '" />';
					?>
						<input type="hidden" name="cpmp_action" id="cpmp_action" value="update">
						<input type="hidden" name="player_id" id="player_id">
						<div class="postbox">
							<h2 class="hndle" style="padding:5px;"><?php esc_html_e( 'Edit an existent player', 'codepeople-media-player' ); ?></h2>
							<div class="inside">
								<table border="0" style="width:100%" class="cpm-players-list">
									<thead>
										<tr>
											<th><?php esc_html_e( 'Player', 'codepeople-media-player' ); ?></th>
											<th><?php esc_html_e( 'Type', 'codepeople-media-player' ); ?></th>
											<th><?php esc_html_e( 'Shortcode', 'codepeople-media-player' ); ?></th>
											<th><?php esc_html_e( 'Actions', 'codepeople-media-player' ); ?></th>
										</tr>
									</thead>
									<tbody>
									<tr><td colspan="4"><hr /></td></tr>
									<?php
									foreach ( $players as $player ) {

										$config = @unserialize( $player->config );
										print '
										<tr>
											<td style="width:100%;font-weight:bold;">' . esc_html( wp_unslash( $player->player_name ) ) . '</td>
											<td style="white-space:nowrap;min-width:150px;text-align:center;">' . esc_html( $config && isset( $config->type ) ? $config->type : '' ) . '</td>
											<td style="white-space:nowrap;min-width:250px;text-align:center;">[cpm-player id="' . esc_attr( $player->id ) . '"]</td>
											<td style="white-space:nowrap;">
												<input type="button" value="' . esc_attr( __( 'Edit', 'codepeople-media-player' ) ) . '" class="button-primary" onclick="cpmp.edit_player(' . esc_attr( $player->id ) . ');">
												<input type="button" value="' . esc_attr__( 'Duplicate', 'codepeople-media-player' ) . '" class="button-primary" onclick="cpmp.duplicate_player(' . esc_attr( $player->id ) . ');">
												<input type="button" value="' . esc_attr( __( 'Remove', 'codepeople-media-player' ) ) . '" class="button-primary" onclick="if(confirm(' . esc_attr( '"' . __( 'Are you sure?', 'codepeople-media-player' ) . '"' ) . ')) cpmp.remove_player(' . esc_attr( $player->id ) . ');">
											</td>
										</tr>
										<tr><td colspan="4"><hr /></td></tr>';
									}
									?>
									</tbody>
								</table>
							</div>
						</div>
					</form>
				<?php
			} // End player edition
			wp_enqueue_style( 'cpmp-magnific-popup-css', plugin_dir_url( __FILE__ ) . 'css/magnific-popup.css', array(), CPMP_VERSION );
			wp_enqueue_script( 'cpmp-magnific-popup-js', plugin_dir_url( __FILE__ ) . 'js/jquery.magnific-popup.min.js', array( 'jquery' ), CPMP_VERSION, true );
			?>
					<script>jQuery(function(){jQuery('.cpmp-popup-youtube').magnificPopup({disableOn: 700,type: 'iframe',mainClass: 'mfp-fade',removalDelay: 160,preloader: false,fixedContentPos: false});});</script>
					<div style="padding:10px; border: 1px solid #DADADA;margin-bottom:20px;text-align:center;">
						<h2><?php esc_html_e( 'Video Tutorials', 'codepeople-media-player' ); ?></h2>
						<div style="width:33%;text-align:right;display:inline-block">
							<a href="https://www.youtube.com/watch?v=YJSkEdkDJM8" class="cpmp-popup-youtube" style="display:inline-block;"><img alt="<?php esc_attr_e( 'Audio player', 'codepeople-media-player' ); ?>" style="width:128px;" src="<?php print esc_attr( plugin_dir_url( __FILE__ ) ); ?>images/icon-audio.png" /></a>
						</div>
						<div style="width:33%;text-align:center;display:inline-block">
							<a href="https://www.youtube.com/watch?v=QG5gGBnVqB0" class="cpmp-popup-youtube" style="display:inline-block;"><img alt="<?php esc_attr_e( 'Video player', 'codepeople-media-player' ); ?>" style="width:128px;" src="<?php print esc_attr( plugin_dir_url( __FILE__ ) ); ?>images/icon-video.png" /></a>
						</div>
						<div style="width:33%;text-align:left;display:inline-block">
							<a href="https://www.youtube.com/watch?v=WS449LCClA8" class="cpmp-popup-youtube" style="display:inline-block;"><img alt="<?php esc_attr_e( 'From gallery', 'codepeople-media-player' ); ?>" style="width:128px;" src="<?php print esc_attr( plugin_dir_url( __FILE__ ) ); ?>images/icon-gallery.png" /></a>
						</div>
					</div>

					<!-- New Player Section -->
					<form method="post" action="<?php echo isset( $_SERVER['REQUEST_URI'] ) ? esc_url( sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) : ''; ?>">
						<?php
							// create a custom nonce for submit verification later
							echo '<input type="hidden" name="cpmp_player_creation_nonce" value="' . esc_attr( wp_create_nonce( __FILE__ ) ) . '" />';
						?>
						<input type="hidden" name="cpmp_action" value="create" />
						<div class="postbox">
							<h2 class="hndle" style="padding:5px;"><?php esc_html_e( 'Create new one', 'codepeople-media-player' ); ?></h2>
							<div class="inside">
								<label style="margin-right:20px;"><input aria-label="<?php esc_attr_e( 'Audio', 'codepeople-media-player' ); ?>" type="radio" name="player_type" value="audio" checked> <?php esc_html_e( 'Audio', 'codepeople-media-player' ); ?></label> <label style="margin-right:20px;"><input aria-label="<?php esc_attr_e( 'Video', 'codepeople-media-player' ); ?>" type="radio" name="player_type" value="video"> <?php esc_html_e( 'Video', 'codepeople-media-player' ); ?></label> <input type="submit" value="Create new media player" class="button-primary" />
							</div>
						</div>
					</form>

					<p style="border:1px solid #E6DB55;margin-bottom:10px;padding:5px;background-color: #FFFFE0;font-size:18px;">
					<?php _e( 'The Protection and PayPal settings are available only in the commercial version of <a href="https://cpmediaplayer.dwbooster.com/download" target="_blank">Audio and Video Player</a>.', 'codepeople-media-player' ); // phpcs:ignore WordPress.Security.EscapeOutput
					?>
					</p>

					<!-- General Settings -->
					<h2 id="general-settings"><?php esc_html_e( 'General Settings', 'codepeople-media-player' ); ?></h2>
					<div class="postbox">
						<h2 class="hndle" style="padding:5px;"><?php esc_html_e( 'General Settings', 'codepeople-media-player' ); ?></h2>
						<div class="inside">
							<form method="post" action="<?php echo esc_url( ( isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '' ) . '#general-settings' ); ?>">
								<?php
									// create a custom nonce for submit verification later
									echo '<input type="hidden" name="cpmp_general_settings" value="' . esc_attr( wp_create_nonce( __FILE__ ) ) . '" />';
								?>
								<table class="form-table">
									<tr valign="top">
										<th><?php esc_html_e( 'Play All', 'codepeople-media-player' ); ?></th>
										<td>
											<input type="checkbox" aria-label="<?php esc_attr_e( 'Play All' ); ?>" name="cpmp_play_all" <?php print get_option( 'cpmp_play_all', 1 ) ? 'CHECKED' : ''; ?> />
											<?php esc_html_e( 'Plays all audios or videos on the page.', 'codepeople-media-player' ); ?>
										</td>
									</tr>
								</table>
								<div><input type="submit" value="<?php esc_attr_e( 'Save General Settings', 'codepeople-media-player' ); ?>" class="button-primary" /></div>
							</form>
							<hr style="margin:20px 0;" />
							<!-- Watermark Settings -->
							<div class="cpm-disabled">
							<h2 id="watermark-settings"><?php esc_html_e( 'Protect the audio files (EXPERIMENTAL)', 'codepeople-media-player' ); ?></h2>
							<p><?php esc_html_e( 'It applies a watermark to the audio files and/or truncate them to protect against downloads. This feature requires the FFMpeg application to be installed on the server to generate the new audio files for playing.', 'codepeople-media-player' ); ?></p>
							<form method="post" action="<?php echo esc_url( ( isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '' ) . '#watermark-settings' ); ?>">
								<?php
									// create a custom nonce for submit verification later
									echo '<input type="hidden" name="cpmp_protection_settings" value="' . esc_attr( wp_create_nonce( __FILE__ ) ) . '" />';
								?>
								<table class="form-table for-watermark">
									<tr valign="top">
										<th><?php esc_html_e( 'Enable Protection?', 'codepeople-media-player' ); ?></th>
										<td>
											<input aria-label="<?php esc_attr_e( 'Enable Protection', 'codepeople-media-player' ); ?>" type="checkbox" disabled />
										</td>
									</tr>
									<tr valign="top">
										<th><?php esc_html_e( 'ffmpeg path', 'codepeople-media-player' ); ?></th>
										<td>
											<input aria-label="<?php esc_attr_e( 'ffmpeg path', 'codepeople-media-player' ); ?>" type="text" disabled style="min-width:70%;" /><br />
											<i>Ex: /usr/bin/</i>
										</td>
									</tr>
									<tr valign="top">
										<th><?php esc_html_e( 'Watermark audio', 'codepeople-media-player' ); ?></th>
										<td>
											<input aria-label="<?php esc_attr_e( 'Watermark audio', 'codepeople-media-player' ); ?>" type="text" disabled style="min-width:70%; margin-right:5px;" /><input type="button" class="button-secondary" value="<?php esc_attr_e( 'Select', 'codepeople-media-player' ); ?>" disabled /><br />
											<i><?php esc_html_e( 'Select an audio file to apply as watermark to the audio files for demos (Experimental feature).', 'codepeople-media-player' ); ?></i>
										</td>
									</tr>
									<tr valign="top">
										<th><?php esc_html_e( 'Duration of the demo files (in seconds)', 'codepeople-media-player' ); ?></th>
										<td>
											<input aria-label="<?php esc_attr_e( 'Duration', 'codepeople-media-player' ); ?>" type="number" style="width:200px" disabled /><br />
											<i><?php esc_html_e( 'Enter the duration in seconds of the demo files. Leave the field empty if you do not want to truncate the files.', 'codepeople-media-player' ); ?></i>
										</td>
									</tr>
								</table>
								<div><input type="submit" value="<?php esc_attr_e( 'Save Protection Settings', 'codepeople-media-player' ); ?>" class="button-primary" disabled /></div>
							</form>
							</div>
						</div>
					</div>

					<!-- PayPal Settings -->
					<h2 id="paypal-settigns"><?php esc_html_e( 'For sale a file associated to your player, complete the PayPal data', 'codepeople-media-player' ); ?></h2>
					<p><?php esc_html_e( 'It is possible to create the media player with samples of audio or video files, and then associate a full version of files for sale.' ); ?></p>
					<div class="postbox cpm-disabled">
						<h2 class="hndle" style="padding:5px;"><?php esc_html_e( 'PayPal Settings', 'codepeople-media-player' ); ?></h2>
						<div class="inside">
							<table class="form-table">
								<tr valign="top">
									<th scope="row"><?php esc_html_e( 'Enable Paypal Payments?', 'codepeople-media-player' ); ?></th>
									<td><input aria-label="<?php esc_attr_e( 'Enable PayPal payments', 'codepeople-media-player' ); ?>" type="checkbox" disabled /></td>
								</tr>

								<tr valign="top">
									<th scope="row"><?php esc_html_e( 'Enabling the Sandbox?', 'codepeople-media-player' ); ?></th>
									<td><input aria-label="<?php esc_attr_e( 'PayPal sandbox', 'codepeople-media-player' ); ?>" type="checkbox" disabled /></td>
								</tr>

								<tr valign="top">
									<th scope="row"><?php esc_html_e( 'Paypal email', 'codepeople-media-player' ); ?></th>
									<td><input aria-label="<?php esc_attr_e( 'PayPal email', 'codepeople-media-player' ); ?>" type="text" size="40" disabled style="min-width:70%;" /></td>
								</tr>

								<tr valign="top">
									<th scope="row"><?php esc_html_e( 'Currency', 'codepeople-media-player' ); ?></th>
									<td>
										<select aria-label="<?php esc_attr_e( 'Currency', 'codepeople-media-player' ); ?>" disabled><option>USD</option></select>
									</td>
								</tr>

								<tr valign="top">
									<th scope="row"><?php esc_html_e( 'Currency Symbol', 'codepeople-media-player' ); ?></th>
									<td><input aria-label="<?php esc_attr_e( 'Currency symbol', 'codepeople-media-player' ); ?>" type="text" disabled /></td>
								</tr>

								<tr valign="top">
									<th scope="row"><?php esc_html_e( 'Paypal language', 'codepeople-media-player' ); ?></th>
									<td><select aria-label="<?php esc_attr_e( 'Language', 'codepeople-media-player' ); ?>" disabled><option>United States - U.S. English</option></select>
									</td>
								</tr>

								<tr valign="top">
									<th scope="row"><?php esc_html_e( 'Paypal button for instant purchases', 'codepeople-media-player' ); // phpcs:ignore WordPress.Security.EscapeOutput
									?></th>
									<td><?php
									print $this->_paypal_buttons(); // phpcs:ignore WordPress.Security.EscapeOutput
									?></td>
								</tr>

								<tr valign="top">
									<th scope="row"><?php esc_html_e( 'Download link valid for', 'codepeople-media-player' ); ?></th>
									<td><input aria-label="<?php esc_attr_e( 'Download link validity', 'codepeople-media-player' ); ?>" type="text" disabled /> <?php esc_html_e( 'day(s)', 'codepeople-media-player' ); ?></td>
								</tr>
							</table>
							<hr />
							<h2><?php esc_html_e( 'Purchase notifications', 'codepeople-media-player' ); ?></h2>
							<table class="form-table">
								<tr valign="top">
									<th scope="row"><?php esc_html_e( 'Notification "from" email', 'codepeople-media-player' ); ?></th>
									<td><input aria-label="<?php esc_attr_e( 'Notification from email', 'codepeople-media-player' ); ?>" type="text" size="40" disabled style="min-width:70%;" /></td>
								</tr>

								<tr valign="top">
									<th scope="row"><?php esc_html_e( 'Send notification to email', 'codepeople-media-player' ); ?></th>
									<td><input aria-label="<?php esc_attr_e( 'Send notification to email', 'codepeople-media-player' ); ?>" type="text" size="40" disabled style="min-width:70%;" /></td>
								</tr>

								<tr valign="top">
									<th scope="row"><?php esc_html_e( 'Email subject confirmation to user', 'codepeople-media-player' ); ?></th>
									<td><input aria-label="<?php esc_attr_e( 'Email subject confirmation to user', 'codepeople-media-player' ); ?>" type="text" size="40" disabled style="min-width:70%;" /></td>
								</tr>

								<tr valign="top">
									<th scope="row"><?php esc_html_e( 'Email confirmation to user', 'codepeople-media-player' ); ?></th>
									<td><textarea aria-label="<?php esc_attr_e( 'Email confirmation to user', 'codepeople-media-player' ); ?>" cols="60" rows="2" disabled style="min-width:70%;"></textarea></td>
								</tr>

								<tr valign="top">
									<th scope="row"><?php esc_html_e( 'Email subject notification to admin', 'codepeople-media-player' ); ?></th>
									<td><input aria-label="<?php esc_attr_e( 'Email subject notification to admin', 'codepeople-media-player' ); ?>" type="text" size="40" disabled style="min-width:70%;" /></td>
								</tr>

								<tr valign="top">
									<th scope="row"><?php esc_html_e( 'Email notification to admin', 'codepeople-media-player' ); ?></th>
									<td><textarea aria-label="<?php esc_attr_e( 'Email notification to admin', 'codepeople-media-player' ); ?>" cols="60" rows="2" disabled style="min-width:70%;"></textarea></td>
								</tr>
							</table>
							<div><input type="button" value="<?php esc_attr_e( 'Save PayPal Settings', 'codepeople-media-player' ); ?>" class="button-primary" disabled /></div>
						</div>
					</div>
			</div>
			<style>#wpfooter{position:relative !important;}</style>
			<?php
		} else {
			wp_enqueue_style( 'thickbox' );
			wp_enqueue_script( 'cpmp-admin', plugin_dir_url( __FILE__ ) . 'js/cpmp_admin.js', array( 'jquery', 'thickbox' ), CPMP_VERSION, true );

			$player                = new stdClass();
			$config                = new stdClass();
			$config->skin          = '';
			$playlist              = array();
			$insertion_button_text = __( 'Create Media Player', 'codepeople-media-player' );
			$playlist_item_list    = '';

			if (
				isset( $_POST['cpmp_player_edition_nonce'] ) &&
				wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cpmp_player_edition_nonce'] ) ), __FILE__ ) &&
				isset( $_POST['player_id'] )
			) { // Edition
				$player = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . CPMP_PLAYER . ' WHERE id=%d', @intval( $_POST['player_id'] ) ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
				if ( ! empty( $player ) ) {
					$player_id = $player->id;

					$config_tmp = unserialize( $player->config );
					if ( $config_tmp ) {
						$config = $config_tmp;
					}

					$player->playlist = str_replace( ';d:', ';i:', $player->playlist );
					$playlist_tmp     = unserialize( $player->playlist );
					if ( $playlist_tmp ) {
						$playlist = $playlist_tmp;
					}

					foreach ( $playlist as $item ) {
						$playlist_item_list .= '<div id="' . esc_attr( $item->id ) . '" class="playlist_item" style="cursor:pointer;width:100%;margin:5px;background-color:#c7e4f3;">
						<div style="float:left;">
						<a href="javascript:void(0);" onclick="cpmp.move_item(\'' . esc_js( $item->id ) . '\', -1);" title="Up" style="text-decoration:none;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0V0z"/><path d="M4 12l1.41 1.41L11 7.83V20h2V7.83l5.58 5.59L20 12l-8-8-8 8z"/></svg></a>
						<a href="javascript:void(0);" onclick="cpmp.move_item(\'' . esc_js( $item->id ) . '\', 1);" title="Down" style="text-decoration:none;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" d="M0 0h24v24H0V0z"/><path fill="#010101" d="M20 12l-1.41-1.41L13 16.17V4h-2v12.17l-5.58-5.59L4 12l8 8 8-8z"/></svg></a>
						<a href="javascript:void(0);" onclick="cpmp.delete_item(\'' . esc_js( $item->id ) . '\');" title="Delete item" style="text-decoration:none;"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/><path d="M0 0h24v24H0z" fill="none"/></svg></a>
						</div>
						<div style="float:left;line-height:24px;"><span>' . $item->annotation . '</span></div>
						<div style="clear:both;"></div>
						</div>';
					}

					$file_for_sale         = property_exists( $player, 'file_for_sale' ) ? $player->file_for_sale : '';
					$sale_price            = property_exists( $player, 'sale_price' ) ? $player->sale_price : '';
					$promotional_text      = property_exists( $player, 'promotional_text' ) ? $player->promotional_text : '';
					$insertion_button_text = __( 'Update Media Player', 'codepeople-media-player' );
				}

				// Create the playlist data
				$playlist_json = json_encode( $playlist );
				echo "<script>var cpmp_playlist_items = {$playlist_json};</script>"; // phpcs:ignore WordPress.Security.EscapeOutput
			}

			if ( isset( $_POST['cpmp_player_creation_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['cpmp_player_creation_nonce'] ) ), __FILE__ ) ) {
				$config->type = ( isset( $_POST['player_type'] ) && 'video' == $_POST['player_type'] ) ? 'video' : 'audio';
			}

			$width_limit  = '';
			$height_limit = '';
			$skin_list    = $this->_get_skin_list( $config->skin, $config->type, $width_limit, $height_limit );
			$player_type  = ( isset( $config->type ) && 'video' == $config->type ) ? 'video' : 'song';
			?>
			<div class="wrap">
				<form id="cpmp_media_player_form" method="post" action="<?php echo esc_url( isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '' ); ?>">
					<input type="hidden" value="<?php esc_attr_e( ( isset( $config->skin ) ? $config->skin : '' ), 'codepeople-media-player' ); // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
					?>" name="cpmp_skin" />
					<?php
					if ( isset( $player_id ) ) {
						echo '<input type="hidden" value="' . esc_attr( $player_id ) . '" name="cpmp_player_id" />';
					}
					if ( isset( $config->type ) ) {
						echo '<input type="hidden" value="' . esc_attr( $config->type ) . '" name="cpmp_type" />';
					}
					?>
					<div class="updated" style="padding:5px;">
						<?php _e( 'For more information go to the <a href="https://cpmediaplayer.dwbooster.com" target="_blank">Audio And Video Player</a> plugin page', 'codepeople-media-player' ); // phpcs:ignore WordPress.Security.EscapeOutput
						?>
					</div>
					<div><h1><?php esc_html_e( 'Player shortcode', 'codepeople-media-player' ); ?>: [cpm-player id="<?php echo esc_html( ( ! empty( $player_id ) ) ? $player_id : 'in progress' ); ?>"]</h1></div>
					<!-- General Settings -->
					<div style="text-align:right;margin-bottom:20px;">
						<input type="button" id="create2" name="create2" value="<?php print esc_attr( 'Save Player Modifications', 'codepeople-media-player' ); ?>" class="button-primary" onclick="cpmp.submit_item_form();" />
						<a type="button" class="button-secondary" href="<?php echo esc_url( isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '' ); ?>"><?php esc_html_e( 'Cancel', 'codepeople-media-player' ); ?></a>
					</div>
					<div class="postbox">
						<h2 class="hndle" style="padding:5px;"><?php esc_html_e( 'General Settings', 'codepeople-media-player' ); ?></h2>
						<div class="inside">
							<!-- Skins -->
							<h3><?php esc_html_e( 'Select the media player skin', 'codepeople-media-player' ); ?></h3>
							<div id="skin_container" style="overflow-x:auto;height:130px;width:100%;">
							<?php
								print $skin_list; // phpcs:ignore WordPress.Security.EscapeOutput
							?>
							</div>
							<!-- Settings -->
							<hr style="margin:20px 0;" />
							<h3><?php esc_html_e( 'Configure media player', 'codepeople-media-player' ); ?></h3>
							<table class="form-table">
								<tbody>
									<tr valign="top">
										<th scope="row" nowrap>
											<label for="cpmp_player_name"><?php esc_html_e( 'Player name', 'codepeople-media-player' ); ?> <span style="color:red">*</span> :</label>
										</th>
										<td style="width:100%">
											<input aria-label="<?php esc_attr_e( 'Player name', 'codepeople-media-player' ); ?>" type="text" id="cpmp_player_name" name="cpmp_player_name" value="<?php echo esc_attr( ( isset( $player->player_name ) ) ? wp_unslash( $player->player_name ) : '' ); ?>" style="min-width:70%;" />
										</td>
									</tr>

									<tr valign="top">
										<th scope="row">
											<label for="cpmp_width"><?php esc_html_e( 'Width:', 'codepeople-media-player' ); ?></label>
										</th>
										<td style="width:100%">
											<input aria-label="<?php esc_attr_e( 'Width', 'codepeople-media-player' ); ?>" type="text" id="cpmp_width" name="cpmp_width" value="<?php echo esc_attr( ( isset( $config->width ) ) ? $config->width : '' ); ?>" /><div id="cpmp_width_info" style="font-style:italic; color:#666;"><?php esc_html_e( 'Value should be greater than or equal to:', 'codepeople-media-player' );
											print esc_html( $width_limit ); ?><br /><?php
											_e( 'To make the players responsive, essential in mobile devices, <b>enter the player\'s width in percentage</b>, for example: 100%', 'codepeople-media-player' ); // phpcs:ignore WordPress.Security.EscapeOutput
?></div>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row">
											<label for="cpmp_height"><?php esc_html_e( 'Height:', 'codepeople-media-player' ); ?></label>
										</th>
										<td style="width:100%">
											<input aria-label="<?php esc_attr_e( 'Height', 'codepeople-media-player' ); ?>" type="text" id="cpmp_height" name="cpmp_height" value="<?php echo esc_attr( ( isset( $config->height ) ) ? $config->height : '' ); ?>" /><div id="cpmp_height_info" style="font-style:italic; color:#666;"><?php esc_html_e( 'Value should be greater than or equal to:', 'codepeople-media-player' );
											print esc_html( $height_limit ); ?></div>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row">
											<label for="cpmp_autoplay"><?php esc_html_e( 'Autoplay:', 'codepeople-media-player' ); ?></label>
										</th>
										<td style="width:100%">
											<input aria-label="<?php esc_attr_e( 'Autoplay', 'codepeople-media-player' ); ?>" type="checkbox" id="cpmp_autoplay" name="cpmp_autoplay" <?php echo ( ( isset( $config->autoplay ) ) ? 'checked' : '' ); ?> /> <span style="font-style:italic; color:#666;"><?php esc_html_e( "Some devices don't allow autoplay", 'codepeople-media-player' ); ?></span>
										</td>
									</tr>
									<tr valign="top">
										<th scope="row">
											<label for="cpmp_loop"><?php esc_html_e( 'Loop:', 'codepeople-media-player' ); ?></label>
										</th>
										<td style="width:100%">
											<input aria-label="<?php esc_attr_e( 'Loop', 'codepeople-media-player' ); ?>" type="checkbox" id="cpmp_loop" name="cpmp_loop" <?php echo ( ( isset( $config->loop ) ) ? 'checked' : '' ); ?> />
										</td>
									</tr>
									<tr valign="top">
										<th scope="row">
											<label for="cpmp_preload"><?php esc_html_e( 'Preload:', 'codepeople-media-player' ); ?></label>
										</th>
										<td style="width:100%">
											<input aria-label="<?php esc_attr_e( 'Preload', 'codepeople-media-player' ); ?>" type="checkbox" id="cpmp_preload" name="cpmp_preload" <?php echo ( ( isset( $config->preload ) && 'none' != $config->preload ) ? 'checked' : '' ); ?> />
										</td>
									</tr>
									<tr valign="top">
										<th scope="row">
											<label for="cpmp_preload"><?php esc_html_e( 'Show playlist:', 'codepeople-media-player' ); ?></label>
										</th>
										<td style="width:100%">
											<input aria-label="<?php esc_attr_e( 'Show playlist', 'codepeople-media-player' ); ?>" type="checkbox" id="cpmp_show_playlist" name="cpmp_show_playlist" <?php echo ( ( isset( $config->playlist ) ) ? 'checked' : '' ); ?> />
										</td>
									</tr>
									<tr valign="top">
										<th scope="row">
											<label for="cpmp_preload"><?php esc_html_e( 'Include download links in the playlist:', 'codepeople-media-player' ); ?></label>
										</th>
										<td style="width:100%">
											<input aria-label="<?php esc_attr_e( 'Download links', 'codepeople-media-player' ); ?>" type="checkbox" id="cpmp_playlist_download_links" name="cpmp_playlist_download_links" <?php echo ( ( isset( $config->playlist_download_links ) ) ? 'checked' : '' ); ?> />
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<!-- Playlist -->
					<div class="postbox">
						<h3 class="hndle" style="padding:5px;"><?php esc_html_e( 'Playlist', 'codepeople-media-player' ); ?></h3>
						<div class="inside">
							<style>.form-table th,#item_form th{white-space:nowrap;min-width:100px;}</style>
							<table class="form-table">
								<tbody>
									<tr>
										<td>
											<div id="item_form" style="border:1px solid #ddd;padding:10px;">
												<h3><?php esc_html_e( 'Enter', 'codepeople-media-player' );
												print esc_html( " {$player_type} " );
												esc_html_e( 'data', 'codepeople-media-player' ); ?></h3>
												<input type="hidden" name="item_id" id="item_id" value="" />
												<table>
													<tbody>
														<tr>
															<th>
																<label for="item_annotation"><?php esc_html_e( 'Title', 'codepeople-media-player' ); ?> <span style="color:red;">*</span> :</label>
															</th>
															<td style="width:100%;">
																<input aria-label="<?php esc_attr_e( 'Title', 'codepeople-media-player' ); ?>" type="text" name="item_annotation" id="item_annotation" style="min-width:70%;" />
															</td>
														</tr>
														<tr>
															<th nowrap>
																<label><?php esc_html_e( $player_type, 'codepeople-media-player' ); // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
																?> <span style="color:red;">*</span> :</label>
															</th>
															<td>
																<div style="padding-bottom:10px;">
																	<input aria-label="<?php esc_attr_e( 'File', 'codepeople-media-player' ); ?>" type="text" name="item_file[]" class="item_file" id="item_file" value="" style="min-width:60%; margin-right:5px;">
																	<input type="button" title="<?php esc_attr_e( 'Select the item file', 'codepeople-media-player' ); ?>" onclick="avp_select_file( this );" value="<?php esc_attr_e( 'Select File', 'codepeople-media-player' ); ?>" class="button-secondary" />
																	<a href="javascript:void(0)" onclick="cpmp.add_field(this, 'item_file')" style="text-decoration:none;">[+] New file format</a>&nbsp;&nbsp;&nbsp;&nbsp;
																</div>
																<br />
																<span style="font-style:italic; color:#666;">
																<?php
																	esc_html_e( 'Select a', 'codepeople-media-player' );
																	print esc_html( " {$player_type} " );
																	esc_html_e( 'from Media Library or enter the URL to the', 'codepeople-media-player' );
																	print esc_html( " {$player_type} " );
																	_e( 'file directly on field. If the Media Library is empty, go to the <a href="media-new.php">Media Library</a> and upload the files.', 'codepeople-media-player' ); // phpcs:ignore WordPress.Security.EscapeOutput
																?>
																</span>
															</td>
														</tr>
														<tr><td colspan="2"><a href="javascript:void(0);" style="text-decoration:none;" onclick="avp_toggle_additional_attributes(this);"><?php esc_html_e( '[+] additional attributes', 'codepeople-media-player' ); ?></a></td></tr>
														<tr style="display:none;" class="cpmp-additional-attr">
															<th nowrap>
																<label for="item_link"><?php esc_html_e( 'Associated link:', 'codepeople-media-player' ); ?></label>
															</th>
															<td>
																<input aria-label="<?php esc_attr_e( 'Associated link', 'codepeople-media-player' ); ?>" type="text" name="item_link" id="item_link" style="min-width:70%;" />
															</td>
														</tr>
														<?php if ( 'video' == $player_type ) { ?>
														<tr style="display:none;" class="cpmp-additional-attr">
															<th>
																<label for="item_poster"><?php esc_html_e( 'Poster:', 'codepeople-media-player' ); ?></label>
															</th>
															<td>
																<input aria-label="<?php esc_attr_e( 'Poster', 'codepeople-media-player' ); ?>" type="text" name="item_poster" id="item_poster" class="item_poster" style="min-width:70%; margin-right:5px;" />
																<input type="button" title="<?php esc_attr_e( 'Select the item poster', 'codepeople-media-player' ); ?>" onclick="avp_select_file( this );" value="<?php esc_attr_e( 'Select Poster', 'codepeople-media-player' ); ?>" class="button-secondary" />
																<br />
																<span style="font-style:italic; color:#666;">
																<?php
																	_e( 'Select a poster image from Media Library or enter the URL to the poster file directly on field. If the Media Library is empty, go to the <a href="media-new.php">Media Library</a> and upload the files.', 'codepeople-media-player' ); // phpcs:ignore WordPress.Security.EscapeOutput
																?>
																</span>
															</td>
														</tr>
														<tr style="display:none;" class="cpmp-additional-attr">
															<th>
																<label><?php esc_html_e( 'Subtitles:', 'codepeople-media-player' ); ?></label>
															</th>
															<td>
																<div style="padding-bottom:10px;">
																	<input aria-label="<?php esc_attr_e( 'Subtitle', 'codepeople-media-player' ); ?>" type="text" name="item_subtitle[]" class="item_subtitle" value="" />
																	<?php esc_html_e( 'Lang: ', 'codepeople-media-player' ); ?>
																	<input aria-label="<?php esc_attr_e( 'Language', 'codepeople-media-player' ); ?>" type="text" name="item_subtitle_lang[]" class="item_subtitle_lang" value="" style="margin-right:5px;" />
																	<input type="button" value="Add new language" onclick="cpmp.add_field(this, 'item_subtitle');" class="button-secondary">
																</div>
																<br />
																<span style="font-style:italic; color:#666;">
																<?php
																	esc_html_e( 'Set the URL to the subtitle file directly on field. If the language field is omitted, the language is inferred from subtitle location\'s field', 'codepeople-media-player' );
																?>
																</span>
															</td>
														</tr>
														<?php } ?>
														<tr><td colspan="2"><hr /></td></tr>
														<tr>
															<td></td>
															<td>
																<input type="button" name="insert_item" value="<?php esc_attr_e( 'Add / Update item on playlist', 'codepeople-media-player' ); ?>" class="button-primary" onclick="cpmp.add_item();">
																<input type="button" name="clear_item_form" value="<?php esc_attr_e( 'Clear', 'codepeople-media-player' ); ?>" class="button-primary" onclick="cpmp.clear_item_form();">
															</td>
														</tr>
													</tbody>
												</table>
											</div>
										</td>
									</tr>
									<tr valign="top">
										<td>
											<div id="items_container" style="border:1px solid #ddd; width:100%; height:200px; overflow:scroll;">
											<?php
											if ( isset( $playlist_item_list ) ) {
												echo $playlist_item_list; // phpcs:ignore WordPress.Security.EscapeOutput
											}
											?>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<!-- For Selling -->
					<p style="border:1px solid #E6DB55;margin-bottom:10px;padding:5px;background-color: #FFFFE0;">
					<?php _e( 'The PayPal settings are available only in the commercial version of <a href="https://cpmediaplayer.dwbooster.com/download" target="_blank">Audio and Video Player</a>.', 'codepeople-media-player' ); // phpcs:ignore WordPress.Security.EscapeOutput
					?>
					</p>
					<div class="postbox cpm-disabled">
						<h3 class="hndle" style="padding:5px;"><?php esc_html_e( 'For Sale', 'codepeople-media-player' ); ?></h3>
						<div class="inside">
							<table class="form-table for-sale">
								<tbody>
									<tr valign="top">
										<th>
											<label><?php esc_html_e( 'Select the file:', 'codepeople-media-player' ); ?> </label>
										</th>
										<td>
											<div>
												<input aria-label="<?php esc_attr_e( 'File', 'codepeople-media-player' ); ?>" type="text" disabled style="min-width:70%; margin-right:5px;" />
												<input type="button" class="button-secondary" value="<?php esc_attr_e( 'Select file', 'codepeople-media-player' ); ?>" disabled />
											</div>
										</td>
									</tr>
									<tr valign="top">
										<th>
											<label><?php esc_html_e( 'Enter price:', 'codepeople-media-player' ); ?> </label>
										</th>
										<td>
											<div>
												<input aria-label="<?php esc_attr_e( 'Price', 'codepeople-media-player' ); ?>" type="text" disabled /> USD
											</div>
										</td>
									</tr>
									<tr valign="top">
										<th>
											<label><?php esc_html_e( 'Promotional text:', 'codepeople-media-player' ); ?> </label>
										</th>
										<td>
											<div>
												<textarea aria-label="<?php esc_attr_e( 'Promotional text', 'codepeople-media-player' ); ?>" cols="60" rows="2" disabled style="min-width:70%;"></textarea>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div>
						<input type="button" id="create" name="create" value="<?php echo esc_attr( $insertion_button_text ); ?>" class="button-primary" onclick="cpmp.submit_item_form();" />
						<a type="button" class="button-secondary" href="<?php echo esc_url( isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '' ); ?>"><?php esc_html_e( 'Cancel', 'codepeople-media-player' ); ?></a>
					</div>
					<?php
						// create a custom nonce for submit verification later
						echo '<input type="hidden" name="cpmp_player_create_update_nonce" value="' . esc_attr( wp_create_nonce( __FILE__ ) ) . '" />';
					?>
				</form>
			</div>
			<?php
		}
	} // End admin_page

	// Button in the post edition for media player insertion
	public function insert_player_button() {
		print '
			<a href="javascript:cpmp.new_player_window(\'audio\');" title="' . esc_attr( __( 'New Audio Player', 'codepeople-media-player' ) ) . '"><img src="' . esc_url( CPMP_PLUGIN_URL . '/images/cpmp_audio.png' ) . '" alt="' . esc_attr( __( 'New Audio Player', 'codepeople-media-player' ) ) . '" /></a>
			<a href="javascript:cpmp.new_player_window(\'video\');" title="' . esc_attr( __( 'New Video Player', 'codepeople-media-player' ) ) . '"><img src="' . esc_url( CPMP_PLUGIN_URL . '/images/cpmp_video.png' ) . '" alt="' . esc_attr( __( 'New Video Player', 'codepeople-media-player' ) ) . '" /></a>
			<a href="javascript:cpmp.open_insertion_window();" title="' . esc_attr( __( 'Insert Player From Gallery', 'codepeople-media-player' ) ) . '"><img src="' . esc_url( CPMP_PLUGIN_URL . '/images/cpmp_gallery.png' ) . '" alt="' . esc_attr( __( 'Insert Player From Gallery', 'codepeople-media-player' ) ) . '" /></a>
		';
	}//end insert_player_button()

	// Load the player button scripts and initialize the insertion dialog
	public function set_load_media_player_window() {
		global $wpdb;

		wp_enqueue_style( 'wp-jquery-ui-dialog' );
		wp_enqueue_style( 'cpmp-admin', CPMP_PLUGIN_URL . '/css/cpmp_admin.css', array(), CPMP_VERSION );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_script(
			'cpmp-admin',
			CPMP_PLUGIN_URL . '/js/cpmp_admin.js',
			array( 'jquery', 'jquery-ui-core', 'jquery-ui-dialog' ),
			CPMP_VERSION,
			true
		);

		// Load players
		$sql     = 'SELECT id, player_name FROM ' . $wpdb->prefix . CPMP_PLAYER . ';';
		$players = $wpdb->get_results( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

		$options = '';
		$label   = '';
		if ( count( $players ) ) {
			foreach ( $players as $player ) {
				$options .= '<option value="' . esc_attr( $player->id ) . '">' . wp_unslash( $player->player_name ) . '</option>';
			}
			$tag   = '<select aria-label="' . esc_attr( __( 'Player', 'codepeople-media-player' ) ) . '" id="cpmp_media_player">' . $options . '</select>';
			$label = __( 'Select the player to insert:', 'codepeople-media-player' );
		} else {
			$tag = __( 'You must to define a media player before use it on page/post.', 'codepeople-media-player' );
		}

		// Skins
		$skins    = '<select aria-label="' . esc_attr( __( 'Skin', 'codepeople-media-player' ) ) . '" id="cpmp_skins">';
		$skin_dir = CPMP_PLUGIN_DIR . '/skins';
		if ( file_exists( $skin_dir ) ) {
			$d = dir( $skin_dir );
			while ( false !== ( $entry = $d->read() ) ) {
				if ( '.' != $entry && '..' != $entry && is_dir( $skin_dir . '/' . $entry ) ) {
					$this_skin = $skin_dir . '/' . $entry . '/';
					if ( file_exists( $this_skin ) ) {
						$skin_data = parse_ini_file( $this_skin . 'config.ini', true );
						$skins    .= '<option value="' . $skin_data['id'] . '">' . esc_html( $skin_data['name'] ) . '</option>';
					}
				}
			}
			$d->close();
		}
		$skins .= '</select>';

		wp_localize_script(
			'cpmp-admin',
			'cpmp_insert_media_player',
			array(
				'title'     => __( 'Insert media player on your post/page', 'codepeople-media-player' ),
				'label'     => $label,
				'new_label' => __( 'Create or Edit a Player', 'codepeople-media-player' ),
				'tag'       => $tag,
				'skins'     => $skins,
			)
		);
	}//end set_load_media_player_window()

	public function get_player() {
		if(
			! empty( $_GET['cpmp-avp'] ) &&
			'' != ( $cpmp_avp = sanitize_text_field( wp_unslash( $_GET['cpmp-avp'] ) ) )
		) {
			// Load player into iframe
			$player_shortcode = get_transient( $cpmp_avp, '' );
			if ( ! empty( $player_shortcode ) ) {
				$this->in_preview = true;
				error_reporting(E_ERROR|E_PARSE);
				print do_shortcode( $player_shortcode );
				remove_all_actions('shutdown');
				if ( class_exists('Error') ) {
					try{ wp_footer(); } catch(Error $err) {}
				}
				exit;
			}
		}
	} // End get_player

	/**
	 * To generate the players previews.
	 */
	public function preview() {
		 $user         = wp_get_current_user();
		$allowed_roles = array( 'editor', 'administrator', 'author' );

		if ( array_intersect( $allowed_roles, $user->roles ) ) {
			if ( ! empty( $_REQUEST['cpmp-avp-preview'] ) ) {
				// Sanitizing variable
				$preview = sanitize_text_field( wp_unslash( $_REQUEST['cpmp-avp-preview'] ) );

				// Remove every shortcode that is not in the plugin
				remove_all_shortcodes();
				add_shortcode( 'codepeople-html5-media-player', array( $this, 'replace_shortcode' ) );
				add_shortcode( 'cpm-player', array( $this, 'replace_shortcode' ) );
				add_shortcode( 'codepeople-html5-playlist-item', array( $this, 'replace_shortcode_playlist_item' ) );
				add_shortcode( 'cpm-item', array( $this, 'replace_shortcode_playlist_item' ) );

				if (
					has_shortcode( $preview, 'codepeople-html5-media-player' ) ||
					has_shortcode( $preview, 'cpm-player' ) ||
					has_shortcode( $preview, 'codepeople-html5-playlist-item' ) ||
					has_shortcode( $preview, 'cpm-item' )
				) {
					$this->in_preview = true;
					print '<!DOCTYPE html>';

					// Deregister all scripts and styles for loading only the plugin styles.
					global  $wp_styles, $wp_scripts;
					if ( ! empty( $wp_scripts ) ) {
						$wp_scripts->reset();
					}
					wp_enqueue_script( 'jquery' );

					$if_empty = __( 'Select at least one media file or player (if applicable)', 'codepeople-media-player' );

					$output = do_shortcode( $preview );

					if ( preg_match( '/^\s*$/', $output ) ) {
						$output = '<div>' . $if_empty . '</div>';
					}

					if ( ! empty( $wp_styles ) ) {
						$wp_styles->do_items();
					}
					if ( ! empty( $wp_scripts ) ) {
						$wp_scripts->do_items();
					}

					print '<div class="cpmp-preview-container">' . $output . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput
					print '<script type="text/javascript">jQuery(window).on("load", function(){ var frameEl = window.frameElement; if(frameEl) frameEl.height = jQuery(".cpmp-preview-container").outerHeight(true)+25; });</script>';
					exit;
				}
			}
		}
	} // End preview

	public function replace_shortcode_playlist_item( $atts, $content = '' ) {
		$atts = shortcode_atts(
			array(
				'file'     => '',
				'name'     => '',
				'poster'   => '',
				'link'     => '',
				'subtitle' => '',
				'lang'     => '',
			),
			$atts
		);

		extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract

		if ( ! empty( $file ) ) {
			if ( ! empty( $content ) ) {
				$name = $content;
			}
			$obj             = new stdClass();
			$obj->files      = array( $file );
			$obj->poster     = $poster;
			$obj->annotation = $name;
			$obj->link       = $link;

			$obj->subtitles = array();
			if ( ! empty( $subtitle ) ) {
				$subtitle_obj           = new stdClass();
				$subtitle_obj->link     = $subtitle;
				$subtitle_obj->language = $lang;
				$obj->subtitles[]       = $subtitle_obj;
			}

			$this->current_player_playlist[] = $obj;
		}
		return '';
	}

	private function _scan_dir( $dir ) {
		$result = array();

		$cdir = scandir( $dir );
		foreach ( $cdir as $key => $value ) {
			if ( ! in_array( $value, array( '.', '..' ) ) ) {
				$value = $dir . '/' . $value;
				if ( is_dir( $value ) ) {
					$result = array_merge( $result, $this->_scan_dir( $value ) );
				} elseif ( $this->is_media( $this->get_extension( $value ) ) ) {
					$result[] = $value;
				}
			}
		}

		return $result;
	}

	public function playlist_from_dir( $dirname ) {
		 $uploads = wp_upload_dir();
		$basedir  = str_replace( '\\', '/', $uploads['basedir'] );
		$basedir  = realpath( $basedir );
		if ( $basedir ) {
			$dirname = str_replace( '\\', '/', $dirname );
			$dirname = ltrim( $dirname, '/' );

			$resource_dir = realpath( $basedir . '/' . $dirname );
			if ( $resource_dir && stripos( $resource_dir, $basedir ) !== false ) {
				$resource_files = $this->_scan_dir( $resource_dir );
				if ( count( $resource_files ) ) {
					require_once ABSPATH . '/wp-admin/includes/media.php';
					foreach ( $resource_files as $file ) {
						try {
							$metadata                        = wp_read_audio_metadata( $file );
							$obj                             = new stdClass();
							$obj->annotation                 = ! empty( $metadata['title'] ) ? $metadata['title'] : pathinfo( $file, PATHINFO_FILENAME );
							$file                            = str_replace( $basedir, $uploads['baseurl'], $file );
							$obj->files                      = array( $file );
							$obj->subtitles                  = array();
							$this->current_player_playlist[] = $obj;
						} catch ( Exception $err ) {
							error_log( $err->getMessage() );
						}
					}
				}
			}
		}
	}

	public function replace_shortcode( $atts = array(), $content = '', $shortcode_tag = '' ) {
		global $wpdb;
		extract( $atts ); // phpcs:ignore WordPress.PHP.DontExtract

		if ( ! empty( $iframe ) && ! $this->in_preview ) {
			$shortcode  = '[' . $shortcode_tag;
			foreach ( $atts as $att_name => $att_value )
				$shortcode .= ' ' . $att_name .'="' . esc_attr( $att_value ) . '"';
			$shortcode .= ']' . $content . '[/' . $shortcode_tag . ']';
			$shortcode_id = urlencode( md5( $shortcode ) );
			set_transient( $shortcode_id, $shortcode, 30*24*60*60 );
			$url  = get_home_url( get_current_blog_id(), '', is_ssl() ? 'https' : 'http' );
			$url .= ( ( strpos( $url, '?' ) === false ) ? '?' : '&' ) . 'cpmp-avp=' . $shortcode_id;
			$output = '<iframe src="' . esc_attr( $url ) . '" style="border:none;width:100%;" onload="this.width=this.contentWindow.document.body.scrollWidth;this.height=this.contentWindow.document.body.scrollHeight+20;" allowtransparency="true" scrolling="no"></iframe>';
			if ( ! $this->observer_embeded ) {
				$output .= '<script>document.addEventListener("DOMContentLoaded", function(){window.addEventListener("message", function(evt){
					var matches = document.querySelectorAll("iframe");
					for (i=0; i<matches.length; i++){
						if( matches[i].contentWindow == evt.source ){
							matches[i].height = Number( evt.data.height )
							return 1;
						}
					}
				},false);});</script>';
				$this->observer_embeded = 1;
			}
			return $output;
		}

		$this->current_player_playlist = array();
		if ( ! empty( $dir ) ) {
			$this->playlist_from_dir( $dir );
		}
		$content = do_shortcode( $content );

		// Variables
		$player_id     = 'codepeople_media_player' . time() . mt_rand( 1, 999999 );
		$mp_atts       = array(); // Media Player attributes
		$pl_items      = array(); // Playlist items
		$srcs          = array(); // Sources of first item
		$mp_subtitles  = array(); // Subtitles list of first item
		$styles        = '';
		$paypal_button = '';

		if ( isset( $id ) ) {
			$sql    = $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . CPMP_PLAYER . ' WHERE id=%d', $id ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$player = $wpdb->get_row( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

			if ( null != $player ) {
				$config_obj = ( isset( $player->config ) ) ? unserialize( $player->config ) : new stdClass();
				// Set attributes
				if ( empty( $config_obj->type ) ) {
					$config_obj->type = 'audio';
				}
				if ( ! isset( $type ) ) {
					$type = trim( $config_obj->type );
				}
				if ( ! isset( $width ) && isset( $config_obj->width ) ) {
					$width = trim( $config_obj->width );
				}
				if ( ! isset( $height ) && isset( $config_obj->height ) ) {
					$height = trim( $config_obj->height );
				}
				if ( ! isset( $skin ) && isset( $config_obj->skin ) ) {
					$skin = trim( $config_obj->skin );
				}
				if ( ! isset( $loop ) && isset( $config_obj->loop ) ) {
					$loop = trim( $config_obj->loop );
				}
				if ( ! isset( $autoplay ) && isset( $config_obj->autoplay ) ) {
					$autoplay = trim( $config_obj->autoplay );
				}
				if ( ! isset( $preload ) && isset( $config_obj->preload ) ) {
					$preload = trim( $config_obj->preload );
				}
				if ( ! isset( $playlist ) && isset( $config_obj->playlist ) ) {
					$playlist = trim( $config_obj->playlist );
				}
				if ( ! isset( $playlist_download_links ) && isset( $config_obj->playlist_download_links ) ) {
					$playlist_download_links = trim( $config_obj->playlist_download_links );
				}

				if ( empty( $this->current_player_playlist ) ) {
					$this->current_player_playlist = ( isset( $player->playlist ) ) ? unserialize( $player->playlist ) : array();
				}
			}
		}
		if ( empty( $type ) ) {
			$type = 'audio';
		}
		if ( ! empty( $this->current_player_playlist ) ) {
			$first_item = true;
			foreach ( $this->current_player_playlist as $item ) {
				$item_srcs      = array();
				$item_subtitles = array();

				foreach ( $item->files as $file ) {
					$file = $this->transform_url( $file );
					$ext  = $this->get_extension( $file, $type );

					// Removing the protocol from the file's URL
					$file = preg_replace( '/^http(s)?\:/i', '', $file );

					$item_src_obj       = new stdClass();
					$item_src_obj->src  = $file;
					$item_src_obj->type = $type . '/' . $ext;
					$item_srcs[]        = $item_src_obj;

					if ( $first_item ) {
						if ( ! empty( $item->poster ) ) {
							$mp_atts[] = 'poster="' . esc_url( $item->poster ) . '"';
						}
						$srcs[] = '<source src="' . esc_attr( $file ) . '" type="' . esc_attr( $item_src_obj->type ) . '" />';
					}
				}

				foreach ( $item->subtitles as $subtitle ) {
					$location = $this->transform_url( $subtitle->link );
					$language = $subtitle->language;
					if ( $first_item ) {
						$mp_subtitles[] = '<track src="' . esc_url( $location ) . '" kind="subtitles" srclang="' . esc_attr( $language ) . '"></track>';
					}

					$item_subtitle_obj          = new stdClass();
					$item_subtitle_obj->kind    = 'subtitles';
					$item_subtitle_obj->src     = $location;
					$item_subtitle_obj->srclang = $language;
					$item_subtitles[]           = $item_subtitle_obj;
				}

				$pl_item_obj = new stdClass();
				if ( ! empty( $item->poster ) ) {
					$pl_item_obj->poster = $this->transform_url( $item->poster );
				}
				$pl_item_obj->source = $item_srcs;
				$pl_item_obj->track  = $item_subtitles;

				$pl_items[] = '<li value="' . esc_attr( json_encode( $pl_item_obj ) ) . '">' .
				( ! empty( $playlist_download_links ) && ( 'true' == $playlist_download_links || '1' == $playlist_download_links || 1 == $playlist_download_links ) && ! empty( $item_srcs ) ? '<a class="cpmp-playlist-download-link" target="_blank" href="' . esc_url( $item_srcs[0]->src ) . '" title="' . __( 'Download', 'codepeople-media-player' ) . '" download></a>' : '' ) .
				( ! empty( $item->link ) ? '<a class="cpmp-info" href="' . esc_url( $item->link ) . '">+</a>' : '' ) .
				( ! empty( $item->annotation ) ? '<span class="cpmp-annotation">' . $item->annotation . '</span>' : '' ) .
				'</li>';

				$first_item = false;
			}
		} else {
			return '';
		}

		if ( empty( $skin ) ) {
			$skin = 'device-player-skin';
		}
		$skin = trim( $skin );

		$base_path = dirname( __FILE__ ) . '/';
		$base_url  = plugin_dir_url( __FILE__ );

		wp_enqueue_style( 'wp-mediaelement' );
		wp_enqueue_style( 'codepeople_media_player', $base_url . 'css/cpmp.css', array(), CPMP_VERSION );

		$css_path = $base_path . 'skins/' . $skin . '/' . $skin . '.css';
		$css_url  = $base_url . 'skins/' . $skin . '/' . $skin . '.css';
		$js_path  = $base_path . 'skins/' . $skin . '/' . $skin . '.js';
		$js_url   = $base_url . 'skins/' . $skin . '/' . $skin . '.js';

		if ( file_exists( $css_path ) ) {
			wp_enqueue_style( 'codepeople_media_player_style_' . $skin, $css_url, array(), CPMP_VERSION );
		}

		if ( file_exists( $js_path ) ) {
			wp_enqueue_script( 'codepeople_media_player_script_' . $skin, $js_url, array( 'jquery' ), CPMP_VERSION, true );
		}

		wp_enqueue_script( 'wp-mediaelement' );
		wp_enqueue_script( 'codepeople_media_player_vimeo', '//cdnjs.cloudflare.com/ajax/libs/mediaelement/4.2.16/renderers/vimeo.min.js', array( 'wp-mediaelement' ), CPMP_VERSION );
		wp_enqueue_script( 'codepeople_media_player_script', $base_url . 'js/codepeople-plugins.js', array( 'jquery', 'wp-mediaelement' ), CPMP_VERSION, true );

		// Inside iframe tag
		if ( $this->in_preview ) {
			wp_enqueue_script( 'codepeople_media_player_iframe_script', $base_url . 'js/codepeople-plugins-iframe.js', array(), CPMP_VERSION, true );
		}

		wp_localize_script( 'codepeople_media_player_script', 'cpmp_general_settings', array( 'play_all' => get_option( 'cpmp_play_all', 1 ) ) );

		$styles .= 'style="max-width:99%;box-sizing:border-box;margin-left:auto;margin-right:auto;margin-top:2px;';
		if ( ! empty( $width ) ) {
			if ( is_numeric( $width ) ) {
				$width .= 'px';
			}
			$width     = esc_attr( $width );
			$styles   .= 'width:' . $width . ';';
			$mp_atts[] = 'width="' . $width . '"';
		}
		$styles .= '"';

		if ( ! empty( $height ) ) {
			$mp_atts[] = 'height="' . esc_attr( $height ) . '"';
		}

		$mp_atts[] = 'class="codepeople-media ' . ( ! empty( $skin ) ? esc_attr( $skin ) : '' ) . '"';

		if ( ! empty( $shuffle ) && 'false' != $shuffle ) {
			$mp_atts[] = 'shuffle="shuffle"';
		}
		if ( ! empty( $loop ) && 'false' != $loop ) {
			$mp_atts[] = 'loop="loop"';
		}
		if ( ! empty( $autoplay ) && 'false' != $autoplay ) {
			$mp_atts[] = 'autoplay="autoplay"';
		}
		if ( isset( $preload ) ) {
			$mp_atts[] = 'preload="' . esc_attr( $preload ) . '"';
		} else {
			$mp_atts[] = 'preload="none"';
		}

		if ( wp_is_mobile() && 'device-player-skin' == $skin ) {
			$mp_atts[] = 'controls';
		}

		return '<div id="ms_avp" ' . $styles . ( ! empty( $class ) ? ' class="' . esc_attr( $class ) . '"' : '' ) . '><' . $type . ' id="' . esc_attr( $player_id ) . '" ' . implode( ' ', $mp_atts ) . ' ' . $styles . '>' . implode( '', $srcs ) . implode( '', $mp_subtitles ) . '</' . $type . '>' . ( ( count( $pl_items ) > 0 ) ? '<ul id="' . esc_attr( $player_id ) . '-list" class="' . ( ( ! empty( $playlist ) && 'false' != $playlist ) ? '' : 'no-playlist' ) . '" style="display:none;">' . implode( ' ', $pl_items ) . '</ul>' : '' ) . '<noscript>audio-and-video-player require JavaScript</noscript><div style="clear:both;"></div></div>';

	} // End replace_shortcode

	public static function troubleshoot( $option ) {
		if ( ! is_admin() ) {
			// Solves a conflict caused by the "Speed Booster Pack" plugin
			if ( is_array( $option ) && isset( $option['jquery_to_footer'] ) ) {
				unset( $option['jquery_to_footer'] );
			}
		}
		return $option;
	}
}
