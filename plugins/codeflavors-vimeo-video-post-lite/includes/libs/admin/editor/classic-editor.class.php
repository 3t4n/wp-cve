<?php

namespace Vimeotheque\Admin\Editor;

use Vimeotheque\Admin\Helper_Admin;
use Vimeotheque\Helper;
use Vimeotheque\Player\Player;
use Vimeotheque\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Classic_Editor
 * @package Vimeotheque\Admin\Editor
 *
 * Implements the functioanlity needed by the Classic editor.
 */
class Classic_Editor{
	/**
	 * @var \WP_Screen
	 */
	private $screen;

	/**
	 * Classic_Editor constructor.
	 *
	 * @param \WP_Screen $screen
	 */
	public function __construct( \WP_Screen $screen ) {
		// allow only on classic editor
		if( !$screen->is_block_editor && 'post' == $screen->base ){
			$this->screen = $screen;
			$this->init();
		}
	}

	/**
     * Initialize hooks
     */
	private function init(){
		// create edit meta boxes
		add_action( 'admin_head', [
			$this,
			'add_meta_boxes'
		] );

		// shortcode modal window output
		add_action( 'admin_footer', [
				$this,
				'shortcode_modal'
		] );

		// add tinymce plugin
		add_action( 'admin_head', [
				$this,
				'tinymce'
		] );

		// assets needed in various pages
		add_action( 'admin_enqueue_scripts', [
				$this,
				'post_edit_assets'
		] );

		// post thumbnails
		add_filter( 'admin_post_thumbnail_html', [
				$this,
				'post_thumbnail_meta_panel'
		], 10, 2 );

		add_action(
		    'admin_footer',
            function(){
		        ?>
<script>
    ( function( $ ){

        $(document).ready( function(){

            $('.toggler-checkbox').on(
                'click',
                function(){
                    var _class = $(this).data('hide_elements');

                    if( $(this).is(':checked') ){
                        $( _class ).hide();
                    }else{
                        $( _class ).show();
                    }
                }
            );
        })

    }(jQuery) );
</script>
                <?php
            }
        );
	}

	/**
     * Add the meta boxes
     */
	public function add_meta_boxes(){
		global $post;
		if( ! $post ){
			return;
		}

		$_post = Helper::get_video_post( $post );
		// video post specific meta boxes
		if( $_post->is_video() ){
			add_meta_box(
				'cvm-video-settings',
				__( 'Video settings', 'codeflavors-vimeo-video-post-lite' ),
				[ $this, 'post_video_settings_meta_box' ],
				$post->post_type,
				'normal',
				'high'
			);

			add_meta_box(
				'cvm-show-video',
				__( 'Live video', 'codeflavors-vimeo-video-post-lite' ),
				[ $this, 'post_show_video_meta_box' ],
				$post->post_type,
				'normal',
				'high'
			);
		}else {
			/**
			 * Filter to disable the shortcode metabox in Classic Editor.
             *
             * @param bool $allow   Allow the shortcode meta box (true) or disable it (false).
			 */
		    $allowed = apply_filters( 'vimeotheque\classic_editor\show_shortcode_meta_box', true );

		    if( true === $allowed && post_type_supports( $post->post_type, 'editor' ) ) {
			    // Shortcode meta box
			    add_meta_box(
				    'cvm-add-video',
				    __( 'Vimeotheque shortcode',
					    'codeflavors-vimeo-video-post-lite' ),
				    [ $this, 'post_shortcode_meta_box' ],
				    null,
				    'side',
				    'low'
			    );
		    }
		}
	}

	/**
	 * Video embed settings meta box displayed on custom post type post editing and
	 * regular post editing for videos imported as regular posts
	 *
	 * @return null
	 */
	public function post_video_settings_meta_box(){
		$settings = Helper::get_video_post()->get_embed_options();
		$plugin_options = Helper::get_embed_options();
		?>
		<?php wp_nonce_field('cvm-save-video-settings', 'cvm-video-nonce');?>
		<?php if( $this->is_option_override() ): ?>
			<div style="background-color: rgba(252,255,22,0.1); padding:.5em 1em;">
				<p>
				<h3><?php _e('Options override is ON', 'codeflavors-vimeo-video-post-lite');?></h3>
				<?php
				printf(
					__( 'Individual video post options are not editable; to change video options globally, go to plugin %sSettings%s, tab Embed options.', 'codeflavors-vimeo-video-post-lite' ),
					'<a href="' . menu_page_url( 'cvm_settings', false ) . '#embed-options">',
					'</a>'
				);?>
				</p>
			</div>
		<?php endif;?>
		<table class="form-table cvm-player-settings-options">
			<tbody>
            <?php
                /**
                 * Run action that can be used to hook to and display additional options.
                 * @ignore
                 *
                 * @param string $where The position that is currently running (ie. before, after, etc). Must be used to check which position is displayed to avoid showing options twice
                 */
                do_action(
                    'vimeotheque\editor\classic-editor-options-output',
                    'before'
                );
            ?>
			<tr>
				<th><label for="cvm_aspect_ratio"><?php _e('Player size', 'codeflavors-vimeo-video-post-lite');?>:</label></th>
				<td>
					<?php if( $this->is_option_override() ):?>
						<?php $this->option_override( 'width', sprintf( __( '%s X %s px' ), $plugin_options['width'], Helper::calculate_player_height( $plugin_options['aspect_ratio'], $plugin_options['width'] ) ) );?>
						<?php $this->option_override( 'aspect_ratio', sprintf( __( '/ Aspect ratio: %s', 'codeflavors-vimeo-video-post-lite' ), $plugin_options['aspect_ratio'] ) );?>
					<?php else: // is not option override?>
						<label for="cvm_aspect_ratio"><?php _e('Aspect ratio');?> :</label>
						<?php
						$args = [
							'name' => 'aspect_ratio',
							'id' => 'cvm_aspect_ratio',
							'class' => 'cvm_aspect_ratio',
							'selected' => $settings[ 'aspect_ratio' ]
						];
						Helper_Admin::aspect_ratio_select( $args );
						?>
						<label for="cvm_width"><?php _e('Width', 'codeflavors-vimeo-video-post-lite');?> :</label>
						<input type="text" name="width" id="cvm_width" class="cvm_width" value="<?php echo $settings['width'];?>" size="2" />px
						| <?php _e('Height', 'codeflavors-vimeo-video-post-lite');?> : <span class="cvm_height" id="cvm_calc_height"><?php echo Helper::calculate_player_height( $settings['aspect_ratio'], $settings['width'] );?></span>px
					<?php endif;// end option override?>
				</td>
			</tr>

			<tr>
				<th><label for="cvm_video_position"><?php _e('Display video','codeflavors-vimeo-video-post-lite');?>:</label></th>
				<td>
					<?php if( $this->is_option_override() ):
						$video_positions = [
							'above-content' => __( 'Above post content', 'codeflavors-vimeo-video-post-lite' ),
							'below-content' => __( 'Below post content', 'codeflavors-vimeo-video-post-lite' ),
							'replace-featured-image' => __( 'Replace featured image', 'codeflavors-vimeo-video-post-lite' ),
						];
						?>
						<?php $this->option_override( 'video_position', $video_positions[ $plugin_options['video_position'] ] );?>
					<?php else: // is not option override?>
						<?php
						$args = [
							'options' => [
								'above-content' => __( 'Above post content', 'codeflavors-vimeo-video-post-lite' ),
								'below-content' => __( 'Below post content', 'codeflavors-vimeo-video-post-lite' ),
                                'replace-featured-image' => __( 'Replace featured image', 'codeflavors-vimeo-video-post-lite' )
							],
							'name' => 'video_position',
							'id' => 'cvm_video_position',
							'selected' => $settings[ 'video_position' ]
						];
						Helper_Admin::select( $args );
						?>
					<?php endif;// end option override?>
				</td>
			</tr>

            <tr>
                <th><label for="lazy_load"><?php _e('Lazy load', 'codeflavors-vimeo-video-post-lite');?></label>:</th>
                <td>
		            <?php if( $this->is_option_override() ):?>
			            <?php $this->option_override( 'lazy_load', ( $plugin_options['lazy_load'] ? __( 'On', 'codeflavors-vimeo-video-post-lite' ) : __( 'Off', 'codeflavors-vimeo-video-post-lite' ) ) );?>
		            <?php else: // is not option override?>
                        <input name="lazy_load" id="lazy_load" type="checkbox" value="1" <?php Helper_Admin::check( (bool) $settings['lazy_load'] );?> />
                        <label for="lazy_load"><span class="description">( <?php _e('when checked, video will be lazy loaded', 'codeflavors-vimeo-video-post-lite');?> )</span></label>
		            <?php endif;// end option override?>
                </td>
            </tr>

			<tr class="toggled-background-mode toggled-muted-mode" style="<?php Helper_Admin::css_hide( ( $settings['background'] || $settings['muted'] ) ) ;?>">
				<th><label for="cvm_volume"><?php _e('Volume', 'codeflavors-vimeo-video-post-lite');?></label>:</th>
				<td>
					<?php if( $this->is_option_override() ):?>
						<?php $this->option_override( 'volume', $plugin_options['volume'] );?>
					<?php else: // is not option override?>
						<input type="text" name="volume" id="cvm_volume" value="<?php echo $settings['volume'];?>" size="1" maxlength="3" />
					<?php endif;// end option override?>
					<label for="cvm_volume"><span class="description">( <?php _e('number between 0 (mute) and 100 (max)', 'codeflavors-vimeo-video-post-lite');?> )</span></label>
				</td>
			</tr>
            <tr>
                <th><label for="cvm_background"><?php _e('Background video', 'codeflavors-vimeo-video-post-lite');?></label>:</th>
                <td>
                    <input name="background" id="cvm_background" type="checkbox" value="1" <?php Helper_Admin::check( (bool) $settings['background'] );?> class="toggler-checkbox" data-hide_elements=".toggled-background-mode" />
                    <label for="cvm_background">
                        <span class="description">( <?php _e('the video will be loaded in background mode which hides the playback controls, enables autoplay, and loops the video', 'codeflavors-vimeo-video-post-lite');?> )</span>
                    </label>
                </td>
            </tr>
            <tr class="toggled-background-mode" style="<?php Helper_Admin::css_hide( $settings['background'] ) ;?>">
				<th><label for="cvm_muted"><?php _e('Mute video', 'codeflavors-vimeo-video-post-lite');?></label>:</th>
				<td>
                    <input name="muted" id="cvm_muted" type="checkbox" value="1" <?php Helper_Admin::check( (bool) $settings['muted'] );?> class="toggler-checkbox" data-hide_elements=".toggled-muted-mode" />
					<label for="cvm_muted"><span class="description">( <?php _e('will load video muted; needs to be checked for the autoplay behavior in some browsers', 'codeflavors-vimeo-video-post-lite');?> )</span></label>
				</td>
			</tr>
			<tr class="toggled-background-mode" style="<?php Helper_Admin::css_hide( $settings['background'] ) ;?>">
				<th><label for="cvm_autoplay"><?php _e('Autoplay', 'codeflavors-vimeo-video-post-lite');?></label>:</th>
				<td>
					<?php if( $this->is_option_override() ):?>
						<?php $this->option_override( 'autoplay', ( $plugin_options['autoplay'] ? __( 'On', 'codeflavors-vimeo-video-post-lite' ) : __( 'Off', 'codeflavors-vimeo-video-post-lite' ) ) );?>
					<?php else: // is not option override?>
						<input name="autoplay" id="cvm_autoplay" type="checkbox" value="1" <?php Helper_Admin::check( (bool) $settings['autoplay'] );?> />
						<label for="cvm_autoplay"><span class="description">( <?php _e('when checked, video will start playing once page is loaded', 'codeflavors-vimeo-video-post-lite');?> )</span></label>
					<?php endif;// end option override?>
					<p class="description"><?php _e( 'Autoplay may be blocked in some environments, such as IOS, Chrome 66+, and Safari 11+. In these cases, Vimeo player will revert to standard playback requiring viewers to initiate playback.', 'codeflavors-vimeo-video-post-lite' );?></p>
				</td>
			</tr>
			<tr class="toggled-background-mode" style="<?php Helper_Admin::css_hide( $settings['background'] ) ;?>">
				<th><label for="cvm_loop"><?php _e('Loop video', 'codeflavors-vimeo-video-post-lite');?></label>:</th>
				<td>
					<?php if( $this->is_option_override() ):?>
						<?php $this->option_override( 'loop', ( $plugin_options['loop'] ? __( 'On', 'codeflavors-vimeo-video-post-lite' ) : __( 'Off', 'codeflavors-vimeo-video-post-lite' ) ) );?>
					<?php else: // is not option override?>
						<input name="loop" id="cvm_loop" type="checkbox" value="1" <?php Helper_Admin::check( (bool) $settings['loop'] );?> /> <label for="cvm_loop">
							<span class="description">( <?php _e('when checked, the video will play again when it reaches the end', 'codeflavors-vimeo-video-post-lite');?> )</span></label>
					<?php endif;// end option override?>
				</td>
			</tr>
			<tr class="toggled-background-mode" style="<?php Helper_Admin::css_hide( $settings['background'] ) ;?>">
				<th><label for="title"><?php _e('Show video title', 'codeflavors-vimeo-video-post-lite');?></label>:</th>
				<td>
					<?php if( $this->is_option_override() ):?>
						<?php $this->option_override( 'title', ( $plugin_options['title'] ? __( 'On', 'codeflavors-vimeo-video-post-lite' ) : __( 'Off', 'codeflavors-vimeo-video-post-lite' ) ) );?>
					<?php else: // is not option override?>
						<input name="title" id="cvm_title" class="cvm_title" type="checkbox" value="1" <?php Helper_Admin::check( (bool) $settings['title'] );?> />
						<label for="cvm_title"><span class="description">( <?php _e('when checked, player will display video title', 'codeflavors-vimeo-video-post-lite');?> )</span></label>
					<?php endif;// end option override?>
				</td>
			</tr>
			<tr class="toggled-background-mode" style="<?php Helper_Admin::css_hide( $settings['background'] ) ;?>">
				<th><label for="cvm_color"><?php _e('Player color', 'codeflavors-vimeo-video-post-lite');?></label>:</th>
				<td>
					<?php if( $this->is_option_override() ):?>
						<?php $this->option_override( 'color', ( empty( $plugin_options['color'] ) ? __( 'Default', 'codeflavors-vimeo-video-post-lite' ) : '#' . $plugin_options['color'] ) );?>
						<?php if( !empty( $plugin_options['color'] ) ):?>
							<div style="width: 20px; height: 20px; background-color: #<?php echo $plugin_options['color'];?>; float: left; margin-right:10px;">&nbsp;</div>
						<?php endif;?>
					<?php else: // is not option override?>
						<input type="text" name="color" id="cvm_color" value="<?php echo $settings['color'];?>" />
					<?php endif;// end option override?>
				</td>
			</tr>

			<tr valign="top" class="toggled-background-mode" style="<?php Helper_Admin::css_hide( $settings['background'] ) ;?>">
				<th scope="row"><label for="byline"><?php _e('Show video author', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
				<td>
					<?php if( $this->is_option_override() ):?>
						<?php $this->option_override( 'byline', ( $plugin_options['byline'] ? __( 'On', 'codeflavors-vimeo-video-post-lite' ) : __( 'Off', 'codeflavors-vimeo-video-post-lite' ) ) );?>
					<?php else: // is not option override?>
						<input type="checkbox" value="1" id="byline" name="byline" <?php Helper_Admin::check( (bool) $settings['byline'] );?> />
						<span class="description"><?php _e('When checked, player will display video uploader.', 'codeflavors-vimeo-video-post-lite');?></span>
					<?php endif;// end option override?>
				</td>
			</tr>

			<tr valign="top" class="toggled-background-mode" style="<?php Helper_Admin::css_hide( $settings['background'] ) ;?>">
				<th scope="row"><label for="portrait"><?php _e('Author portrait', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
				<td>
					<?php if( $this->is_option_override() ):?>
						<?php $this->option_override( 'portrait', ( $plugin_options['portrait'] ? __( 'On', 'codeflavors-vimeo-video-post-lite' ) : __( 'Off', 'codeflavors-vimeo-video-post-lite' ) ) );?>
					<?php else: // is not option override?>
						<input type="checkbox" value="1" id="portrait" name="portrait" <?php Helper_Admin::check( (bool) $settings['portrait'] );?> />
						<span class="description"><?php _e('When checked, player will display uploader image.', 'codeflavors-vimeo-video-post-lite');?></span>
					<?php endif;// end option override?>
				</td>
			</tr>

            <tr valign="top">
				<th scope="row"><label for="start_time"><?php _e('Start at', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
				<td>
					<input name="start_time" id="start_time" type="number" value="<?php echo $settings['start_time'];?>" step="1" min="0" max="<?php echo Helper::get_video_post()->duration;?>" /> <abbr title="<?php esc_attr_e( 'Seconds', 'codeflavors-vimeo-video-post-lite' );?>"><?php _ex( 'sec.', 'Abbreviation for "seconds"', 'codeflavors-vimeo-video-post-lite' ) ;?></abbr>
					<p class="description">
                        <?php
                           printf(
                                __('Video will be embedded to start from the specified time. Value must not exceed the total video duration of %d seconds.', 'codeflavors-vimeo-video-post-lite'),
                                Helper::get_video_post()->duration
                           );
                        ?>
                    </p>
				</td>
			</tr>

            <tr valign="top">
                <th scope="row"><label for="transparent"><?php _e('Transparent background', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
                <td>
		            <?php if( $this->is_option_override() ):?>
			            <?php $this->option_override( 'transparent', ( $plugin_options['transparent'] ? __( 'On', 'codeflavors-vimeo-video-post-lite' ) : __( 'Off', 'codeflavors-vimeo-video-post-lite' ) ) );?>
		            <?php else: // is not option override?>
                        <input type="checkbox" value="1" id="transparent" name="transparent" <?php Helper_Admin::check( (bool) $settings['transparent'] );?> />
                        <span class="description"><?php _e('When checked, the video will be embedded without a background.', 'codeflavors-vimeo-video-post-lite');?></span>
		            <?php endif;// end option override?>
                </td>
            </tr>

			</tbody>
		</table>
		<?php

		/**
		 * Run action that can be used to hook to and display additional options
		 * @ignore
		 * @param string $where The position that is currently running (ie. before, after, etc). Must be used to check which position is displayed to avoid showing options twice
		 */
		do_action(
			'vimeotheque\editor\classic-editor-options-output',
			'after'
		);
	}

	/**
	 * Display live video meta box on post editing
	 */
	public function post_show_video_meta_box(){
	    $_post = Helper::get_video_post();
	    $player = new Player( $_post );
		$player->get_output(true, 700);
	}

	/**
	 * Post add shortcode meta box output
	 */
	public function post_shortcode_meta_box(){
		?>
		<p><?php _e('Add video/playlist into post.', 'codeflavors-vimeo-video-post-lite');?>
		<p>
		<a class="button" href="#" id="cvm-shortcode-2-post"
		   title="<?php _e('Add shortcode');?>"><?php _e('Add video shortcode');?></a>
		<?php
	}

	/**
	 * @param $input_name
	 * @param string $message
	 */
	private function option_override( $input_name, $message = '' ){
		$settings = Helper::get_video_post()->get_embed_options();
?>
    <input type="hidden" name="<?php echo esc_attr( $input_name );?>" value="<?php echo esc_attr( $settings[ $input_name ] );?>" />
    <em><?php echo $message ;?></em>
<?php
    }

	/**
	 * @return mixed
	 */
	private function is_option_override(  ){
	    $option = Plugin::instance()->get_embed_options_obj()->get_option('allow_override');

		return is_wp_error( $option ) ? false : $option;
	}

	/**
	 * Video/playlist embed shortcode modal window output
	 *
	 * @return null
	 */
	public function shortcode_modal(){
		global $post;
		if( ! $post ){
			return;
		}

		$options = Helper::get_embed_options();
		?>

<div id="CVMVideo_Modal_Window" style="display: none;">
	<div class="wrap">
		<div id="cvm-playlist-items">
			<div class="inside">
				<h3><?php _e('Playlist settings', 'codeflavors-vimeo-video-post-lite');?></h3>
				<div id="cvm-playlist-settings" class="cvm-player-settings-options">
					<table>
						<tr>
							<th valign="top"><label for="cvm_playlist_theme"><?php _e('Theme', 'codeflavors-vimeo-video-post-lite');?>:</label></th>
							<td>
								<?php
                                    Helper_Admin::select_playlist_theme(
                                            'cvm_playlist_theme',
                                            false,
                                            'cvm_playlist_theme'
                                    );
                                ?>
                                    <div class="cvm-theme-customize default">
                                        <?php _e( 'Playlist location', 'codeflavors-vimeo-video-post-lite' ) ;?> :
                                        <label for=""><input type="radio" name="cvm_theme_default_layout" value="" checked="checked" /> <?php _e( 'bottom', 'codeflavors-vimeo-video-post-lite' );?></label>
                                        <label for=""><input type="radio" name="cvm_theme_default_layout" value="right" /> <?php _e( 'right', 'codeflavors-vimeo-video-post-lite' );?></label>
                                        <label for=""><input type="radio" name="cvm_theme_default_layout" value="left" /> <?php _e( 'left', 'codeflavors-vimeo-video-post-lite' );?></label>
                                        <p>
                                            <label>
                                                <input type="checkbox" name="cvm_theme_default_show_excerpts" id="cvm_theme_default_show_excerpts" value="1" />
                                                <?php _e('Show posts excerpts', 'codeflavors-vimeo-video-post-lite');?>
                                            </label>
                                        </p>
                                        <p>
                                            <label>
                                                <input type="checkbox" name="cvm_theme_default_use_original_thumbnails" id="cvm_theme_default_show_excerpts" value="1" />
                                                <?php _e('Original thumbnail size ratio', 'codeflavors-vimeo-video-post-lite');?>
                                            </label>
                                        </p>
                                    </div>

                                    <?php
                                        /**
                                         * Theme specific playlist shortcode settings
                                         * @ignore
                                         */
                                        do_action(
                                            'vimeotheque\admin\classic-editor\playlist-shortcode-theme-settings'
                                        );
                                    ?>
								</td>
						</tr>
                        <tr>
                            <th><label for="cvm_posts_order"><?php _e( 'Posts order', 'codeflavors-vimeo-video-post-lite' );?>:</label></th>
                            <td>
	                            <?php
	                            Helper_Admin::select(
	                                [
                                        'options' => [
	                                        'manual' => __( 'Manual order', 'codeflavors-vimeo-video-post-lite' ),
	                                        'newest' => __( 'Newest first', 'codeflavors-vimeo-video-post-lite' ),
	                                        'oldest' => __( 'Oldest first', 'codeflavors-vimeo-video-post-lite' ),
	                                        'alphabetical' => __( 'Alphabetically', 'codeflavors-vimeo-video-post-lite' )
                                        ],
                                        'name' => 'cvm_playlist_posts_order',
                                        'id' => 'cvm_playlist_posts_order',
                                        'selected' => 'manual',
                                        'use_keys' => true
	                                ]
                                );
	                            ?>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="2"><h4><?php _e( 'Video embed options', 'codeflavors-vimeo-video-post-lite' );?></h4></td>
                        </tr>

						<tr>
							<th><label for="cvm_aspect_ratio"><?php _e('Aspect', 'codeflavors-vimeo-video-post-lite');?>:</label></th>
							<td>
								<?php
                                $args = [
                                        'name' => 'aspect_ratio',
                                        'id' => 'aspect_ratio',
                                        'class' => 'cvm_aspect_ratio'
                                ];
                                Helper_Admin::aspect_ratio_select( $args );
                                ?>
								</td>
						</tr>

						<tr>
							<th><label for="width"><?php _e('Width', 'codeflavors-vimeo-video-post-lite');?>:</label></th>
							<td><input type="text" class="cvm_width" name="width" id="width"
								value="<?php echo $options['width'];?>" size="2" />px
									| <?php _e('Height', 'codeflavors-vimeo-video-post-lite');?> : <span class="cvm_height"
								id="cvm_calc_height"><?php echo Helper::calculate_player_height( $options['aspect_ratio'], $options['width'] );?></span>px
							</td>
						</tr>

						<tr>
							<th><label for="volume"><?php _e('Volume', 'codeflavors-vimeo-video-post-lite');?></label>:</th>
							<td><input type="text" name="volume" id="volume"
								value="<?php echo $options['volume'];?>" size="1" maxlength="3" />
								<label for="volume"><span class="description"><?php _e('number between 0 (mute) and 100 (max)', 'codeflavors-vimeo-video-post-lite');?></span></label>
							</td>
						</tr>

						<tr>
							<th><label for="cvm_title"><?php _e('Title', 'codeflavors-vimeo-video-post-lite');?></label>:</th>
							<td><input type="checkbox" name="title" id="cvm_title" value="1"
								<?php if($options['title']) echo 'checked="checked"';?> /> <label
								for="cvm_title"><span class="description"><?php _e('will display title on video', 'codeflavors-vimeo-video-post-lite');?></span></label>
							</td>
						</tr>

						<tr>
							<th><label for="cvm_byline"><?php _e('Author', 'codeflavors-vimeo-video-post-lite');?></label>:</th>
							<td><input type="checkbox" name="byline" id="cvm_byline"
								value="1"
								<?php if($options['byline']) echo 'checked="checked"';?> /> <label
								for="cvm_byline"><span class="description"><?php _e('will display author name on video', 'codeflavors-vimeo-video-post-lite');?></span></label>
							</td>
						</tr>

						<tr>
							<th><label for="cvm_portrait"><?php _e('Image', 'codeflavors-vimeo-video-post-lite');?></label>:</th>
							<td><input type="checkbox" name="portrait" id="cvm_portrait"
								value="1"
								<?php if($options['portrait']) echo 'checked="checked"';?> /> <label
								for="cvm_portrait"><span class="description"><?php _e('will display author image on video', 'codeflavors-vimeo-video-post-lite');?></span></label>
							</td>
						</tr>

						<tr>
							<th><label for="playlist_loop"><?php _e('Loop', 'codeflavors-vimeo-video-post-lite');?></label>:</th>
							<td><input type="checkbox" name="playlist_loop"
								id="playlist_loop" value="1" /> <label for="playlist_loop"><span
									class="description"><?php _e('will automatically play next video when current playing video ends', 'codeflavors-vimeo-video-post-lite');?></span></label>
							</td>
						</tr>
					</table>
					<p>
						<input type="button" id="cvm-insert-playlist-shortcode"
							class="button primary"
							value="<?php _e('Insert playlist', 'codeflavors-vimeo-video-post-lite');?>" />
					</p>
				</div>

                <input type="hidden" name="cvm_selected_categories" value="" />
                <h3><?php _e( 'Create from categories', 'codeflavors-vimeo-video-post-lite' );?></h3>
                <div id="cvm-categories-list">
                    <em>
                        <?php _e( 'Select some categories to display videos from.', 'codeflavors-vimeo-video-post-lite' ) ;?><br />
                        <?php _e( 'Categories can be added by clicking "Add category to shortcode" button above the posts table.', 'codeflavors-vimeo-video-post-lite' );?>
                    </em>
                </div>

				<input type="hidden" name="cvm_selected_items" value="" />
				<h3><?php _e('Videos selected in playlist', 'codeflavors-vimeo-video-post-lite');?></h3>

				<div id="cvm-list-items">
					<em><?php _e('No videos selected', 'codeflavors-vimeo-video-post-lite');?><br /><?php _e('To create a playlist check some videos from the list on the right.', 'codeflavors-vimeo-video-post-lite');?></em>
				</div>
			</div>
		</div>
		<div id="cvm-display-videos">
			<iframe
				src="edit.php?post_type=<?php echo Plugin::instance()->get_cpt()->get_post_type();?>&page=cvm_videos"
				frameborder="0" width="100%" height="100%"></iframe>
		</div>
	</div>
</div>
<?php
	}

	/**
	 * Add filters to put tinymce plugin buttons on editor rich edit mode
	 *
	 * @return null
	 */
	public function tinymce(){
		// Don't bother doing this stuff if the current user lacks permissions
		if( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) )
			return;

			// Don't load unless is post editing (includes post, page and any custom posts set)
		$screen = get_current_screen();
		if( !$screen || 'post' != $screen->base || Plugin::instance()->get_cpt()->get_post_type() == $screen->post_type ){
			return;
		}

		// Add only in Rich Editor mode
		if( get_user_option( 'rich_editing' ) == 'true' ){

			wp_enqueue_script( [
					'jquery-ui-dialog'
			] );

			wp_enqueue_style( [
					'wp-jquery-ui-dialog'
			] );

			add_filter( 'mce_external_plugins', [
					$this,
					'tinymce_plugins'
			] );
			add_filter( 'mce_buttons', [
					$this,
					'tinymce_buttons'
			] );
		}
	}

	/**
	 * Add tinymce plugin ( mce_external_plugins filter callback set in function $this->tinymce )
	 *
	 * @param $plugin_array
	 *
	 * @return array
	 */
	public function tinymce_plugins( $plugin_array ){
		$plugin_array[ 'cvm_shortcode' ] = VIMEOTHEQUE_URL . 'assets/back-end/js/tinymce/shortcode.js';
		return $plugin_array;
	}

	/**
	 * Add tinymce buttons for the mce plugin registered above
	 *
	 * @param $buttons
	 *
	 * @return array
	 */
	public function tinymce_buttons( $buttons ){
		array_push( $buttons, 'separator', 'cvm_shortcode' );
		return $buttons;
	}

	/**
	 * Add scripts to custom post edit page
	 *
	 * @param string $hook
	 */
	public function post_edit_assets( $hook ){
		if( 'post.php' !== $hook && 'post-new.php' !== $hook ){
			return;
		}

		global $post;
		if( ! $post ){
			return;
		}

		$_post = Helper::get_video_post( $post );

		// video/playlist shortcode functionality
		wp_enqueue_style(
		    'cvm-shortcode-modal',
			VIMEOTHEQUE_URL . 'assets/back-end/css/shortcode-modal.css',
            [ 'wp-jquery-ui-dialog' ],
            '1.0'
        );

		wp_enqueue_script(
		    'cvm-shortcode-modal',
			VIMEOTHEQUE_URL . 'assets/back-end/js/shortcode-modal.js',
            [ 'jquery-ui-dialog' ],
            '1.0'
        );

		wp_localize_script(
		    'cvm-shortcode-modal',
            'CVM_SHORTCODE_MODAL',
			[
				'playlist_title' => __( 'Videos in playlist', 'codeflavors-vimeo-video-post-lite' ),
				'no_videos' => sprintf(
				    '%s <br />%s',
					__( 'No videos selected.', 'codeflavors-vimeo-video-post-lite' ),
					__( 'To create a playlist check some videos from the list on the right.', 'codeflavors-vimeo-video-post-lite' )
                ),
				'deleteItem' => __( 'Delete from playlist', 'codeflavors-vimeo-video-post-lite' ),
				'insert_playlist' => __( 'Add shortcode into post', 'codeflavors-vimeo-video-post-lite' ),
				'deleteCategory' => __( 'Remove category', 'codeflavors-vimeo-video-post-lite' ),
				'no_categories' => __( 'Select some categories to display videos from. Categories can be added by clicking "Add category to shortcode" button above the posts table.', 'codeflavors-vimeo-video-post-lite' )
			]
        );

		// some files are needed only on custom post type edit page
		if( $_post->is_video() ){
			// add video player for video preview on post
			Helper::enqueue_player();

			wp_enqueue_script(
			    'cvm-video-edit',
				VIMEOTHEQUE_URL . 'assets/back-end/js/video-edit.js',
                [ 'jquery', 'wp-color-picker' ],
                '1.0'
            );

			wp_enqueue_style('wp-color-picker');

			wp_enqueue_style(
			    'cvm-video-thumbnail',
				VIMEOTHEQUE_URL . 'assets/back-end/css/video-thumbnail.css',
                false,
                '1.0'
            );

			if( !$this->screen->is_block_editor ) {
				// video thumbnail functionality
				wp_enqueue_script(
					'cvm-video-thumbnail',
					VIMEOTHEQUE_URL . 'assets/back-end/js/video-thumbnail.js',
                    [ 'jquery' ],
                    '1.0'
                );

				wp_localize_script(
				    'cvm-video-thumbnail',
                    'cvm_thumb_message',
                    [
					    'loading'       => __( '... importing thumbnail', 'codeflavors-vimeo-video-post-lite' ),
					    'still_loading' => __( '... hold on, still loading' )
				    ]
                );
			}

			/**
			 * Run hook when Vimeotheque loads scripts and styles for the Classic editor.
             * @ignore
			 */
			do_action( 'vimeotheque\editor\classic-editor-enqueue');

		}
	}

	/**
	 * Manipulate output for featured image on custom post to allow importing of thumbnail as featured image
	 *
	 * @param $content
	 * @param $post_id
	 *
	 * @return string
	 */
	public function post_thumbnail_meta_panel( $content, $post_id ){
		$_post = Helper::get_video_post( $post_id );

		if( !$_post || !$_post->video_id ){
			return $content;
		}

		$has_thumbnail = get_post_meta( $post_id, '_thumbnail_id', true );
		if( !$_post->thumbnails ){
			$has_thumbnail = true;
		}

		if( $has_thumbnail ){
			$content .= '<hr /><br />';
			$content .= sprintf(
			        '<a href="#" id="cvm-import-video-thumbnail" data-refresh="1" data-post="%d"><i class="dashicons dashicons-update"></i> %s</a>',
                    $post_id,
                    __( 'Refresh Vimeo image', 'codeflavors-vimeo-video-post-lite' )
            );
			$content .= sprintf(
				'<p class="description" id="cvm-thumb-response">%s</p>',
				__( 'Will import a fresh image from Vimeo. If already existing, it will be duplicated.', 'codeflavors-vimeo-video-post-lite' )
            );
		}else{
			$content .= '<hr /><br />';
			$content .= sprintf(
			        '<a href="#" id="cvm-import-video-thumbnail" data-refresh="0" data-post="%d"><i class="dashicons dashicons-download"></i >%s</a>',
                    $post_id, __( 'Import Vimeo image', 'codeflavors-vimeo-video-post-lite' )
            );
			$content .= sprintf(
				'<p class="description" id="cvm-thumb-response">%s</p>',
				__( 'Will first search the Media Gallery for an already imported image and will import if none found.', 'codeflavors-vimeo-video-post-lite' )
			);
		}
		return $content;
	}
}