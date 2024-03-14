<?php
/**
 * @author  CodeFlavors
 * @project codeflavors-vimeo-video-post-lite
 */

namespace Vimeotheque\Admin\Page;

use Vimeotheque\Admin\Helper_Admin;
use Vimeotheque\Plugin;
use Vimeotheque\Vimeo_Api\Vimeo_Oauth;

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

class Setup_Page extends Page_Abstract implements Page_Interface {
	/**
	 * @var Vimeo_Oauth
	 */
	private $vimeo_oauth;

	/**
	 * @inheritDoc
	 */
	public function get_html() {
		// The page header.
		$this->the_header();
        $options = Plugin::instance()->get_options();
        $player_opt = Plugin::instance()->get_embed_options();
?>
<div class="wrap vimeotheque-setup">
    <div class="navigator">
        <div class="step">
            <a href="#step-1" class="active" data-step="1">
                <?php _e( 'Video posts display method', 'codeflavors-vimeo-video-post-lite' );?>
            </a>
        </div>
        <div class="step-divider"></div>
        <div class="step">
            <a href="#step-2" data-step="2">
			    <?php _e( 'Post content', 'codeflavors-vimeo-video-post-lite' );?>
            </a>
        </div>
        <div class="step-divider"></div>
        <div class="step">
            <a href="#step-3" data-step="3">
                <?php _e( 'Player embed options', 'codeflavors-vimeo-video-post-lite' );?>
            </a>
        </div>
        <div class="step-divider"></div>
        <div class="step">
            <a href="#step-4" data-step="4">
                <?php _e( 'Vimeo API setup', 'codeflavors-vimeo-video-post-lite' );?>
            </a>
        </div>
    </div>

    <div class="container">
        <form method="post" action="" id="setup-form">
            <div class="step step-1" id="step-1">
                <div class="title">
                    <h2><?php _e( 'Welcome to Vimeotheque', 'codeflavors-vimeo-video-post-lite' );?></h2>
                    <p>
                        <?php _e( 'Choose how to display your video posts.', 'codeflavors-vimeo-video-post-lite' );?>
                    </p>
                </div>

                <div class="content">
                    <input type="radio" name="enable_templates" value="1" id="enable_templates_yes" checked="checked" />
                    <label for="enable_templates_yes">
                        <?php _e( 'Use the WordPress templates from Vimeotheque to embed the videos (recommended).', 'codeflavors-vimeo-video-post-lite' );?>
                    </label>
                    <div class="note">
                        <p>
                            <?php _e( 'Customizable from your WordPress theme, the Vimeotheque video templates display video posts using enhanced functionality that helps increase the user engagement time on your pages.', 'codeflavors-vimeo-video-post-lite' );?>
                        </p>
                    </div>

                    <input type="radio" name="enable_templates" value="0" id="enable_templates_no" />
                    <label for="enable_templates_no">
                        <?php _e( 'Embed videos into the post content or replace the post featured image with the video embed.', 'codeflavors-vimeo-video-post-lite' );?>
                    </label>
                    <div class="note">
                        <p>
                            <?php _e( 'The basic, default way Vimeotheque embeds videos is by either placing the video above or below the post content or by replacing the post featured image, if set.', 'codeflavors-vimeo-video-post-lite' );?>
                        </p>
                    </div>
                </div>
            </div><!-- .step-1 -->

            <div class="step step-2 inactive" id="step-2">
                <div class="title">
                    <h2><?php _e( 'Post options', 'codeflavors-vimeo-video-post-lite' );?></h2>
                    <p>
			            <?php _e( 'Vimeotheque imports videos by creating WP posts.', 'codeflavors-vimeo-video-post-lite' );?><br />
                        <?php _e( 'Choose the data that gets imported into each video post.', 'codeflavors-vimeo-video-post-lite' );?>
                    </p>
                </div>
                <div class="content">
                    <table class="form-table">
                        <tbody>
                        <tr valign="top">
                            <th scope="row"><label for="import_date"><?php _e('Import date', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
                            <td>
                                <input type="checkbox" value="1" name="import_date" id="import_date"<?php Helper_Admin::check($options['import_date']);?> />
                                <span class="description"><?php _e("Posts imported from Vimeo videos will have the same publishing date as the one on Vimeo.", 'codeflavors-vimeo-video-post-lite');?></span>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row"><label for="import_tags"><?php _e('Import tags', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
                            <td>
                                <input type="checkbox" value="1" id="import_tags" name="import_tags"<?php Helper_Admin::check($options['import_tags']);?> />
                                <span class="description"><?php _e('Automatically import video tags from Vimeo as WordPress post tags.', 'codeflavors-vimeo-video-post-lite');?></span>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row"><label for="max_tags"><?php _e('Number of tags', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
                            <td>
                                <input type="text" value="<?php echo $options['max_tags'];?>" id="max_tags" name="max_tags" size="1" />
                                <span class="description"><?php _e('The maximum number of tags that will be imported.', 'codeflavors-vimeo-video-post-lite');?></span>
                            </td>
                        </tr>

		                <?php
		                /**
		                 * Action that allows other settings to be displayed in page into the Content Options tab.
		                 */
		                do_action( 'vimeotheque\admin\setup\content_options_section' );
		                ?>

                        </tbody>
                    </table>
                </div>
            </div><!-- .step-2 -->

            <div class="step step3 inactive" id="step-3">
                <div class="title">
                    <h2><?php _e( 'Player aspect', 'codeflavors-vimeo-video-post-lite' );?></h2>
                    <p>
			            <?php _e( 'Each video post created by Vimeotheque will embed the video into the page.', 'codeflavors-vimeo-video-post-lite' );?><br />
			            <?php _e( 'Set up the player aspect for the embeds.', 'codeflavors-vimeo-video-post-lite' );?>
                    </p>
                </div>
                <div class="content">
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <th><label for="cvm_width"><?php _e('Player width', 'codeflavors-vimeo-video-post-lite');?>:</label></th>
                            <td>
                                <input type="text" name="width" id="cvm_width" value="<?php echo $player_opt['width'];?>" size="2" />px
                                <p class="description">
                                    <?php _e('The player embed is responsive, the width value is the maximum value that the player may have.', 'codeflavors-vimeo-video-post-lite');?>
                                </p>
                            </td>
                        </tr>

                        <tr>
                            <th><label for="cvm_max_height"><?php _e( 'Maximum embed height', 'codeflavors-vimeo-video-post-lite' );?></label>:</th>
                            <td>
                                <input type="text" name="max_height" id="cvm_max_height" value="<?php echo $player_opt['max_height'];?>" size="2" />px
                                <p class="description">
                                    <?php _e( 'The player height is automatically calculated based on the player width and the player aspect ratio retrieved from Vimeo. This option helps set a maximum height for the player that will not be exceeded when the height is calculated automatically.', 'codeflavors-vimeo-video-post-lite' );?>
                                </p>
                            </td>
                        </tr>

                        <tr id="video-position-row">
                            <th><label for="cvm_video_position"><?php _e('Display video','codeflavors-vimeo-video-post-lite');?>:</label></th>
                            <td>
				                <?php
				                $args = [
					                'options' => [
						                'above-content' => __( 'Above post content', 'codeflavors-vimeo-video-post-lite' ),
						                'below-content' => __( 'Below post content', 'codeflavors-vimeo-video-post-lite' ),
						                'replace-featured-image' => __( 'Replace featured image', 'codeflavors-vimeo-video-post-lite' )
					                ],
					                'name' 		=> 'video_position',
					                'id'		=> 'cvm_video_position',
					                'selected' 	=> $player_opt['video_position']
				                ];
				                Helper_Admin::select( $args );
				                ?>
                            </td>
                        </tr>

                        <tr id="video-align-row">
                            <th><label for="cvm_video_align"><?php _e('Align video','codeflavors-vimeo-video-post-lite');?>:</label></th>
                            <td>
		                        <?php
		                        $args = [
			                        'options' => [
				                        'align-left' => __('Left', 'codeflavors-vimeo-video-post-lite'),
				                        'align-center' => __('Center', 'codeflavors-vimeo-video-post-lite'),
				                        'align-right' => __('Right', 'codeflavors-vimeo-video-post-lite')
			                        ],
			                        'name' 		=> 'video_align',
			                        'id'		=> 'cvm_video_align',
			                        'selected' 	=> $player_opt['video_align']
		                        ];
		                        Helper_Admin::select( $args );
		                        ?>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row"><label for="lazy_load"><?php _e('Lazy load videos', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
                            <td>
                                <input type="checkbox" value="1" id="lazy_load" name="lazy_load"<?php Helper_Admin::check( ( bool ) $player_opt['lazy_load'] );?> />
                                <span class="description">
                                    <?php _e( 'When checked, Vimeotheque will display the video featured image with a play icon above it. When the featured image is clicked, the video will be embedded into the page.', 'codeflavors-vimeo-video-post-lite' );?>
                                </span>
                            </td>
                        </tr>

                        <tr id="play-icon-color-row" class="<?php echo !$player_opt['lazy_load'] ? 'hide' : '';?>">
                            <th scope="row"><label for="play_icon_color"><?php _e('Lazy loaded videos icon color', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
                            <td>
                                <input type="text" name="play_icon_color" id="play_icon_color" value="<?php echo $player_opt['play_icon_color'];?>" data-colorPicker="true" />
                                <p class="description">
                                    <?php _e( 'The play icon color that is displayed above the featured image when video is lazy loaded.', 'codeflavors-vimeo-video-post-lite' );?>
                                </p>
                            </td>
                        </tr>

		                <?php
		                /**
		                 * Action that allows other settings to be displayed in page into the Embed Options tab.
		                 */
		                do_action( 'vimeotheque\admin\setup\embed_options_section' );
		                ?>

                        <tr>
                            <th colspan="2">
                                <h4><i class="dashicons dashicons-video-alt3"></i> <?php _e('Embed settings', 'codeflavors-vimeo-video-post-lite');?></h4>
                                <p class="description"><?php _e('General Vimeo player settings. These settings will be applied to any new video by default and can be changed individually for every imported video.', 'codeflavors-vimeo-video-post-lite');?></p>
                            </th>
                        </tr>

                        <tr>
                            <th><label for="cvm_volume"><?php _e('Volume', 'codeflavors-vimeo-video-post-lite');?></label>:</th>
                            <td>
                                <input type="number" step="1" min="0" max="100" name="volume" id="cvm_volume" value="<?php echo $player_opt['volume'];?>" />
                                <label for="cvm_volume">
                                    <span class="description">
                                        <?php _e('A number between 0 (mute) and 100 (max)', 'codeflavors-vimeo-video-post-lite');?>
                                    </span>
                                </label>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row"><label for="title"><?php _e('Show video title', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
                            <td>
                                <input type="checkbox" value="1" id="title" name="title"<?php Helper_Admin::check( (bool )$player_opt['title'] );?> />
                                <span class="description">
                                    <?php _e('The video player will display the video title.', 'codeflavors-vimeo-video-post-lite');?>
                                </span>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row"><label for="byline"><?php _e('Show video author', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
                            <td>
                                <input type="checkbox" value="1" id="byline" name="byline"<?php Helper_Admin::check( (bool )$player_opt['byline'] );?> />
                                <span class="description">
                                    <?php _e('The video player will display the video author name.', 'codeflavors-vimeo-video-post-lite');?>
                                </span>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row"><label for="portrait"><?php _e('Show author portrait', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
                            <td>
                                <input type="checkbox" value="1" id="portrait" name="portrait"<?php Helper_Admin::check( (bool )$player_opt['portrait'] );?> />
                                <span class="description">
                                    <?php _e('The video player will display the video author profile image.', 'codeflavors-vimeo-video-post-lite');?>
                                </span>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row"><label for="cvm_color"><?php _e('Player color', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
                            <td>
                                <input type="text" name="color" id="cvm_color" value="<?php echo $player_opt['color'];?>" data-colorPicker="true" />
                                <p class="description">
                                    <?php _e('The color of the video player play button and progress bar.', 'codeflavors-vimeo-video-post-lite');?>
                                </p>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row"><label for="transparent"><?php _e('Transparent background', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
                            <td>
                                <input type="checkbox" value="1" id="transparent" name="transparent"<?php Helper_Admin::check( ( bool )$player_opt['transparent'] );?> />
                                <span class="description">
                                    <?php _e('The video player will have a transparent background.', 'codeflavors-vimeo-video-post-lite');?>
                                </span>
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row"><label for="dnt"><?php _e('Do not track users', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
                            <td>
                                <input type="checkbox" value="1" id="dnt" name="dnt"<?php Helper_Admin::check( (bool )$player_opt['dnt'] );?> />
                                <span class="description"><?php _e( 'Block the player from tracking any session data, including all cookies and stats.', 'codeflavors-vimeo-video-post-lite' );?></span>
                            </td>
                        </tr>


                        </tbody>
                    </table>


                </div>
            </div><!-- .step-3 -->

            <div class="step step-4 inactive" id="step-4">
                <div class="title">
                    <h2><?php _e( 'API Setup', 'codeflavors-vimeo-video-post-lite' );?></h2>
                    <p>
				        <?php _e( 'The Vimeo API requires credentials in order to allow queries for videos.', 'codeflavors-vimeo-video-post-lite' );?><br />
				        <?php _e( 'Setup the Vimeo API credentials for access to videos.', 'codeflavors-vimeo-video-post-lite' );?>
                    </p>
                </div>

                <?php
                    if(empty( $options['vimeo_consumer_key'] ) || empty( $options['vimeo_secret_key'] )):
                ?>
                <div class="oauth-instructions">
                    <a href="#" class="toggler" data-toggle="#oauth-instructions" data-show_text="<?php esc_attr_e('View instructions ');?>"  data-hide_text="<?php esc_attr_e('Hide instructions ');?>"><?php _e( 'View instructions','codeflavors-vimeo-video-post-lite' );?></a>

                    <div id="oauth-instructions">
                        <p><?php _e( 'In order to be able to import videos using Vimeotheque, you must register a new Vimeo App (requires a Vimeo account).', 'codeflavors-vimeo-video-post-lite' );?></p>
                        <p>
                            <?php
                            printf(
                                'The Vimeo App must be set under App Callback URL with the value: %s',
                                sprintf(
                                    '<strong class="callback-url" id="callback-url-value">%s</strong>',
                                    Plugin::instance()->get_admin()->get_admin_menu()->get_page( 'cvm_settings' )->get_menu_page(false)
                                )
                            );
                            ?>
                        </p>
                        <p class="actions">
                            <?php
                            printf(
                                '%s %s',
                                sprintf(
                                    '<a href="%s" target="_blank">%s</a>',
                                    'https://developer.vimeo.com/apps/new',
                                    __( 'Create Vimeo App', 'codeflavors-vimeo-video-post-lite' )
                                ),
                                sprintf(
                                    '<a href="%s" target="_blank">%s</a>',
                                    Helper_Admin::docs_link( 'how-to-create-a-new-vimeo-app/' ),
                                    __( 'See tutorial', 'codeflavors-vimeo-video-post-lite' )
                                )
                            )
                            ?>
                        </p>
                    </div>
                </div>
                <?php endif;?>

                <div class="content">
                    <?php
                        if(empty( $options['vimeo_consumer_key'] ) || empty( $options['vimeo_secret_key'] )):
                    ?>
                    <div id="vimeo-oauth-response"></div>
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th scope="row"><label for="vimeo_consumer_key"><?php _e('Enter Vimeo Client Identifier', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
                                <td>
                                    <input type="text" name="vimeo_consumer_key" id="vimeo_consumer_key" value="<?php echo $options['vimeo_consumer_key'];?>" size="60" />
                                    <p class="description"><?php _e('Requires an active Vimeo Account.', 'codeflavors-vimeo-video-post-lite');?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label for="vimeo_secret_key"><?php _e('Enter Vimeo Client Secrets', 'codeflavors-vimeo-video-post-lite')?>:</label></th>
                                <td>
                                    <input type="text" name="vimeo_secret_key" id="vimeo_secret_key" value="<?php echo $options['vimeo_secret_key'];?>" size="60" />
                                    <p class="description"><?php _e('Requires an active Vimeo Account.', 'codeflavors-vimeo-video-post-lite');?></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <?php
                        // API OAuth credentials were found, show the message.
                        else:
                    ?>
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th scope="row"><label><?php _e('Plugin access to Vimeo account', 'codeflavors-vimeo-video-post-lite');?>:</label></th>
                                <td>
                                    <p>
                                        <?php _e( 'Your Vimeo keys are successfully installed.', 'codeflavors-vimeo-video-post-lite' );?>
                                    </p>
                                    <p class="description">
                                        <?php _e( 'You can now query public videos on Vimeo and import them as WordPress posts.', 'codeflavors-vimeo-video-post-lite' );?>
                                    </p>
                                    <hr />
                                    <?php
                                    /**
                                     * Action that allows display of additional OAuth settings.
                                     */
                                    do_action( 'vimeotheque\admin\setup\api_oauth_section' );
                                    ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php
                        endif;
                    ?>
                </div>
            </div><!-- .step-4 -->

            <div class="step step-success step999 inactive" id="step-success">
                <div class="title">
                    <h2><?php _e( 'Setup complete!', 'codeflavors-vimeo-video-post-lite' );?></h2>
                    <p>
				        <?php _e( 'You have completed the setup process.', 'codeflavors-vimeo-video-post-lite' );?>
                    </p>
                </div>
                <div class="content">
                    <p>
                        <?php _e( 'Congratulations, the setup process is complete. You can now start importing your Vimeo videos into WordPress.', 'codeflavors-vimeo-video-post-lite' );?>
                    </p>
                    <a
                        href="<?php $this->get_admin()->get_admin_menu()->get_page( 'cvm_import' )->get_menu_page();?>"
                        class="button button-primary large single-line center"
                    >
                        <?php _e( 'Go to video importer', 'codeflavors-vimeo-video-post-lite' );?>
                    </a>
                </div>
            </div>

            <div class="controls">
                <div class="form-controls">
                    <a href="#" class="back"><?php _e( 'Previous step', 'codeflavors-vimeo-video-post-lite' );?></a>
                    <input
                            type="button"
                            class="submit-button"
                            value="<?php esc_attr_e( 'Next step', 'codeflavors-vimeo-video-post-lite' );?>"
                            data-value="<?php esc_attr_e( 'Next step', 'codeflavors-vimeo-video-post-lite' );?>"
                            data-loading="<?php esc_attr_e( 'Saving ...', 'codeflavors-vimeo-video-post-lite' );?>"
                            data-save="<?php esc_attr_e( 'Save options', 'codeflavors-vimeo-video-post-lite' );?>"
                    />
                </div>
                <a
                    href="<?php menu_page_url( 'cvm_settings' );?>"
                    id="skip-setup"
                    data-message="<?php esc_attr_e( 'Are you sure you want to skip setup? You will be able to configure the plugin from the Vimeotheque Settings page.', 'codeflavors-vimeo-video-post-lite' );?>">
                    <?php _e( 'Skip setup', 'codeflavors-vimeo-video-post-lite' );?>
                </a>
            </div>

        </form>
    </div>
</div>
<?php
		// The page footer.
		$this->the_footer();
	}

	/**
	 * @inheritDoc
	 */
	public function on_load() {
		$_GET['noheader'] = 'true';
		if( !defined('IFRAME_REQUEST') ){
			define('IFRAME_REQUEST', true);
		}

        $options = Plugin::instance()->get_options();
		// you must use this instead of menu_page_url() to avoid API error
        $settings_page_url = sprintf(
            'edit.php?post_type=%s&page=%s',
	        Plugin::instance()->get_admin()->get_post_type()->get_post_type(),
	        Plugin::instance()->get_admin()->get_admin_menu()->get_page( 'cvm_settings' )->get_menu_slug()
        );

        $this->vimeo_oauth = new Vimeo_Oauth(
			$options['vimeo_consumer_key'],
			$options['vimeo_secret_key'],
			$options['oauth_token'],
			admin_url( $settings_page_url )
		);

		wp_enqueue_style(
			'vimeotheque-setup',
			VIMEOTHEQUE_URL . 'assets/back-end/css/setup-page.css',
			false,
			VIMEOTHEQUE_VERSION
		);

		wp_enqueue_script(
			'vimeotheque-setup',
			VIMEOTHEQUE_URL . 'assets/back-end/js/setup.js',
			[ 'jquery', 'wp-color-picker' ],
			VIMEOTHEQUE_VERSION
		);

        wp_localize_script(
            'vimeotheque-setup',
            'vimeotheque',
            [
                'restURL' => rest_url(),
                'restNonce' => wp_create_nonce( 'wp_rest' )
            ]
        );

		wp_enqueue_style('wp-color-picker');
	}

	/**
     * The page header
     *
	 * @return void
	 */
	private function the_header(){
		_wp_admin_html_begin();
		printf('<title>%s</title>', __('Quick Setup', 'codeflavors-vimeo-video-post-lite'));
		wp_enqueue_style( 'colors' );
		wp_enqueue_style( 'ie' );
		wp_enqueue_script( 'utils' );
		wp_enqueue_script( 'buttons' );

		/**
		 * @ignore
		 */
		do_action('admin_print_styles');
		/**
		 * @ignore
		 */
		do_action('admin_print_scripts');
		/**
		 * Action triggered on loading the video modal window
		 * @ignore
		 */
		do_action('vimeotheque\admin\setup_wizard_print_scripts');
		echo '</head>';
		echo '<body class="wp-core-ui">';
	}

	/**
     * The page footer
     *
	 * @return void
	 */
	private function the_footer() {
		echo '</body>';
		echo '</html>';
		die();
	}
}