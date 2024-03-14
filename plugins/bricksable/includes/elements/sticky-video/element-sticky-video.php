<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Bricksable_Sticky_Video extends \Bricks\Element {
	public $block     = array( 'core/video', 'core-embed/youtube', 'core-embed/vimeo' );
	public $category  = 'bricksable';
	public $name      = 'ba-sticky-video';
	public $icon      = 'ti-video-clapper';
	public $scripts   = array( 'bricksableStickyVideo' );
	public $draggable = false;

	public function get_label() {
		return esc_html__( 'Sticky Video', 'bricksable' );
	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'bricks-animate' );
		wp_enqueue_style( 'ba-sticky-video' );

		if ( isset( $this->settings['customPlayer'] ) ) {
			wp_enqueue_style( 'video-plyr', BRICKS_URL_ASSETS . 'css/libs/plyr.min.css', array(), '3.6.3' );
			wp_enqueue_script( 'video-plyr', BRICKS_URL_ASSETS . 'js/libs/plyr.min.js', array( 'bricks-scripts' ), '3.6.3', true );
		}
		if ( ( isset( $this->settings['videoType'] ) && 'youtube' === $this->settings['videoType'] ) && ( isset( $this->settings['stickyOnPlay'] ) && true === $this->settings['stickyOnPlay'] ) ) {
			wp_enqueue_script( 'ba-sticky-video-youtube-api', 'https://www.youtube.com/iframe_api', array(), null, false );
		}
		if ( ( isset( $this->settings['videoType'] ) && 'vimeo' === $this->settings['videoType'] ) && ( isset( $this->settings['stickyOnPlay'] ) && true === $this->settings['stickyOnPlay'] ) ) {
			wp_enqueue_script( 'ba-sticky-video-vimeo-api', 'https://player.vimeo.com/api/player.js', array(), null, false );
		}
		wp_enqueue_script( 'ba-sticky-video' );

		wp_localize_script(
			'ba-sticky-video',
			'bricksableStickyVideoData',
			array(
				'StickyVideoInstances' => array(),
			)
		);
	}

	public function set_control_groups() {
		$this->control_groups['icon']              = array(
			'title' => esc_html__( 'Overlay', 'bricksable' ) . ' / ' . esc_html__( 'Icon', 'bricksable' ),
			'tab'   => 'content',
		);
		$this->control_groups['stickySettings']    = array(
			'title' => esc_html__( 'Sticky Settings', 'bricksable' ),
			'tab'   => 'content',
		);
		$this->control_groups['stickyCloseButton'] = array(
			'title' => esc_html__( 'Sticky Close Button', 'bricksable' ),
			'tab'   => 'content',
		);
	}

	public function set_controls() {
		$this->controls['videoType'] = array(
			'tab'       => 'content',
			'label'     => esc_html__( 'Source', 'bricksable' ),
			'type'      => 'select',
			'options'   => array(
				'youtube' => 'YouTube',
				'vimeo'   => 'Vimeo',
				'media'   => esc_html__( 'Media', 'bricksable' ),
				'file'    => esc_html__( 'File URL', 'bricksable' ),
				'meta'    => esc_html__( 'Dynamic Data', 'bricksable' ),
			),
			'default'   => 'youtube',
			'inline'    => true,
			'clearable' => false,
		);

		// @since 1.6.1
		$this->controls['iframeTitle'] = array(
			'tab'      => 'content',
			'label'    => esc_html__( 'Iframe title', 'bricksable' ),
			'type'     => 'text',
			'inline'   => true,
			'required' => array( 'videoType', '=', array( 'youtube', 'vimeo' ) ),
		);

		/**
		 * Type: YouTube
		 */

		$this->controls['youTubeId'] = array(
			'tab'      => 'content',
			'label'    => esc_html__( 'YouTube video ID', 'bricksable' ),
			'type'     => 'text',
			'inline'   => true,
			'required' => array( 'videoType', '=', 'youtube' ),
			'default'  => '5DGo0AYOJ7s',
		);

		// Cannot be used if using preview image.
		$this->controls['youtubeAutoplay'] = array(
			'tab'      => 'content',
			'label'    => esc_html__( 'Autoplay', 'bricksable' ),
			'type'     => 'checkbox',
			'info'     => 'YouTube: ' . esc_html__( 'Not supported on mobile devices', 'bricksable' ),
			'required' => array(
				array( 'videoType', '=', 'youtube' ),
				array( 'previewImage', '!=', true ),
			),
		);

		$this->controls['youtubeControls'] = array(
			'tab'      => 'content',
			'label'    => esc_html__( 'Controls', 'bricksable' ),
			'type'     => 'checkbox',
			'default'  => true,
			'required' => array( 'videoType', '=', 'youtube' ),
		);

		$this->controls['youtubeLoop'] = array(
			'tab'      => 'content',
			'label'    => esc_html__( 'Loop', 'bricksable' ),
			'type'     => 'checkbox',
			'required' => array( 'videoType', '=', 'youtube' ),
		);

		$this->controls['youtubeMute'] = array(
			'tab'      => 'content',
			'label'    => esc_html__( 'Mute', 'bricksable' ),
			'type'     => 'checkbox',
			'required' => array( 'videoType', '=', 'youtube' ),
		);

		$this->controls['youtubeShowinfo'] = array(
			'tab'      => 'content',
			'label'    => esc_html__( 'Show info', 'bricksable' ),
			'type'     => 'checkbox',
			'default'  => true,
			'required' => array( 'videoType', '=', 'youtube' ),
		);

		$this->controls['youtubeRel'] = array(
			'tab'      => 'content',
			'label'    => esc_html__( 'Related videos from other channels', 'bricksable' ),
			'type'     => 'checkbox',
			'required' => array( 'videoType', '=', 'youtube' ),
		);

		/**
		 * Type: Vimeo
		 */

		$this->controls['vimeoId'] = array(
			'tab'      => 'content',
			'label'    => esc_html__( 'Vimeo video ID', 'bricksable' ),
			'type'     => 'text',
			'inline'   => true,
			'required' => array( 'videoType', '=', 'vimeo' ),
		);

		// Support unlisted vimeo videos.
		$this->controls['vimeoHash'] = array(
			'tab'      => 'content',
			'label'    => esc_html__( 'Vimeo privacy hash', 'bricksable' ),
			'type'     => 'text',
			'inline'   => true,
			'info'     => esc_html__( 'If the video is unlisted, you will need to enter the video privacy hash.', 'bricksable' ),
			'required' => array( 'videoType', '=', 'vimeo' ),
		);

		// Cannot be used if using preview image.
		$this->controls['vimeoAutoplay'] = array(
			'tab'      => 'content',
			'label'    => esc_html__( 'Autoplay', 'bricksable' ),
			'type'     => 'checkbox',
			'required' => array(
				array( 'videoType', '=', 'vimeo' ),
				array( 'previewImage', '!=', true ),
			),
		);

		$this->controls['vimeoLoop'] = array(
			'tab'      => 'content',
			'label'    => esc_html__( 'Loop', 'bricksable' ),
			'type'     => 'checkbox',
			'required' => array( 'videoType', '=', 'vimeo' ),
		);

		$this->controls['vimeoMute'] = array(
			'tab'      => 'content',
			'label'    => esc_html__( 'Mute', 'bricksable' ),
			'type'     => 'checkbox',
			'required' => array( 'videoType', '=', 'vimeo' ),
		);

		$this->controls['vimeoByline'] = array(
			'tab'      => 'content',
			'label'    => esc_html__( 'Byline', 'bricksable' ),
			'type'     => 'checkbox',
			'default'  => true,
			'required' => array( 'videoType', '=', 'vimeo' ),
		);

		$this->controls['vimeoTitle'] = array(
			'tab'      => 'content',
			'label'    => esc_html__( 'Title', 'bricksable' ),
			'type'     => 'checkbox',
			'default'  => true,
			'required' => array( 'videoType', '=', 'vimeo' ),
		);

		$this->controls['vimeoPortrait'] = array(
			'tab'      => 'content',
			'label'    => esc_html__( 'User portrait', 'bricksable' ),
			'type'     => 'checkbox',
			'default'  => true,
			'required' => array( 'videoType', '=', 'vimeo' ),
		);

		$this->controls['vimeoDoNotTrack'] = array(
			'tab'      => 'content',
			'label'    => esc_html__( 'Do not track', 'bricksable' ),
			'type'     => 'checkbox',
			'required' => array( 'videoType', '=', 'vimeo' ),
		);

		$this->controls['vimeoColor'] = array(
			'tab'      => 'content',
			'label'    => esc_html__( 'Color', 'bricksable' ),
			'type'     => 'color',
			'required' => array( 'videoType', '=', 'vimeo' ),
		);

		/**
		 * Preview image
		 *
		 * Load video YouTube/Vimeo iframe after preview image click.
		 *
		 * Cannot be used with autoplay.
		 *
		 * @since 1.7.2
		 */
		$this->controls['previewImageSeparator'] = array(
			'tab'         => 'content',
			'type'        => 'separator',
			'label'       => esc_html__( 'Preview image', 'bricksable' ),
			'description' => esc_html__( 'The video <iframe> is lazy loaded after clicking the preview image.', 'bricksable' ),
			'required'    => array(
				array( 'videoType', '=', array( 'vimeo', 'youtube' ) ),
			),
		);

		$this->controls['previewImage'] = array(
			'tab'         => 'content',
			'type'        => 'select',
			'options'     => array(
				'default' => esc_html__( 'Default', 'bricksable' ) . ' (API)',
				'custom'  => esc_html__( 'Custom', 'bricksable' ),
			),
			'placeholder' => esc_html__( 'None', 'bricksable' ),
			/*
			'description' => sprintf(
				'%s :<br> %s > %s > %s',
				esc_html__( 'Fallback preview image', 'bricksable' ),
				esc_html__( 'Settings', 'bricksable' ),
				esc_html__( 'Theme Styles', 'bricksable' ),
				esc_html__( 'Element - Video', 'bricksable' )
			),
			*/
			'required'    => array(
				array( 'videoType', '=', array( 'vimeo', 'youtube' ) ),
			),
		);

		$this->controls['previewImageCustom'] = array(
			'tab'      => 'content',
			'type'     => 'image',
			'required' => array(
				array( 'videoType', '=', array( 'vimeo', 'youtube' ) ),
				array( 'previewImage', '=', 'custom' ),
			),
		);

		$this->controls['previewImageIconInfo'] = array(
			'tab'      => 'content',
			'type'     => 'info',
			'content'  => esc_html__( 'Set "Icon" as video play button for a better user experience.', 'bricksable' ),
			'required' => array(
				array( 'previewImage', '!=', '' ),
				array( 'overlayIcon', '=', '' ),
			),
		);

		$this->controls['previewImageYoutubeAutoplay'] = array(
			'tab'      => 'content',
			'type'     => 'info',
			'content'  => esc_html__( 'Autoplay is not supported when using preview image.', 'bricksable' ),
			'required' => array(
				array( 'previewImage', '!=', '' ),
				array( 'youtubeAutoplay', '!=', '' ),
			),
		);

		$this->controls['previewImageVimeoAutoplay'] = array(
			'tab'      => 'content',
			'type'     => 'info',
			'content'  => esc_html__( 'Autoplay is not supported when using preview image.', 'bricksable' ),
			'required' => array(
				array( 'previewImage', '!=', '' ),
				array( 'vimeoAutoplay', '!=', '' ),
			),
		);

		/**
		 * Type: Media
		 */

		$this->controls['media'] = array(
			'tab'      => 'content',
			'label'    => esc_html__( 'Media', 'bricksable' ),
			'type'     => 'video',
			'required' => array( 'videoType', '=', 'media' ),
		);

		/**
		 * Type: File
		 */

		$this->controls['fileUrl'] = array(
			'tab'      => 'content',
			'label'    => esc_html__( 'Video file URL', 'bricksable' ),
			'type'     => 'text',
			'required' => array( 'videoType', '=', 'file' ),
		);

		/**
		 * Type: Meta
		 */

		$this->controls['useDynamicData'] = array(
			'tab'            => 'content',
			'label'          => '',
			'type'           => 'text',
			'placeholder'    => esc_html__( 'Select dynamic data', 'bricksable' ),
			'hasDynamicData' => 'link',
			'required'       => array( 'videoType', '=', 'meta' ),
		);

		/**
		 * Type: Media & File
		 */

		$this->controls['filePreload'] = array(
			'tab'         => 'content',
			'label'       => esc_html__( 'Preload', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'metadata' => esc_html__( 'Metadata', 'bricksable' ),
				'auto'     => esc_html__( 'Auto', 'bricksable' ),
			),
			'placeholder' => esc_html__( 'None', 'bricksable' ),
			'inline'      => true,
			'required'    => array( 'videoType', '=', array( 'media', 'file', 'meta' ) ),
		);

		$this->controls['fileAutoplay'] = array(
			'tab'      => 'content',
			'label'    => esc_html__( 'Autoplay', 'bricksable' ),
			'type'     => 'checkbox',
			'required' => array( 'videoType', '=', array( 'media', 'file', 'meta' ) ),
		);

		$this->controls['fileLoop'] = array(
			'tab'      => 'content',
			'label'    => esc_html__( 'Loop', 'bricksable' ),
			'type'     => 'checkbox',
			'required' => array( 'videoType', '=', array( 'media', 'file', 'meta' ) ),
		);

		$this->controls['fileMute'] = array(
			'tab'      => 'content',
			'label'    => esc_html__( 'Mute', 'bricksable' ),
			'type'     => 'checkbox',
			'required' => array( 'videoType', '=', array( 'media', 'file', 'meta' ) ),
		);

		$this->controls['fileInline'] = array(
			'tab'      => 'content',
			'label'    => esc_html__( 'Play inline', 'bricksable' ),
			'type'     => 'checkbox',
			'required' => array( 'videoType', '=', array( 'media', 'file', 'meta' ) ),
		);

		$this->controls['fileControls'] = array(
			'tab'      => 'content',
			'label'    => esc_html__( 'Controls', 'bricksable' ),
			'type'     => 'checkbox',
			'default'  => true,
			'required' => array( 'videoType', '=', array( 'media', 'file', 'meta' ) ),
		);

		$this->controls['customPlayer'] = array(
			'tab'         => 'content',
			'label'       => esc_html__( 'Custom Video Player', 'bricksable' ),
			'description' => sprintf(
				'If enabled an additional JS & CSS file is loaded. (Learn more: <a href="%s" target="_blank" rel="noopener">%s</a>)',
				esc_url( 'https://plyr.io/' ),
				esc_html__( 'plyr.io', 'bricksable' ),
			),
			'type'        => 'checkbox',
			'default'     => false,
			'required'    => array(
				array(
					'fileControls',
					'!=',
					'',
				),
				array(
					'videoType',
					'=',
					array( 'media', 'file', 'meta' ),
				),
			),
		);

		$this->controls['customPlayerSeparator'] = array(
			'tab'      => 'content',
			'type'     => 'separator',
			'required' => array( 'customPlayer', '!=', '' ),
		);

		$this->controls['fileRestart'] = array(
			'tab'      => 'content',
			'label'    => esc_html__( 'Restart', 'bricksable' ),
			'type'     => 'checkbox',
			'default'  => false,
			'required' => array( 'customPlayer', '!=', '' ),
		);

		$this->controls['fileRewind'] = array(
			'tab'      => 'content',
			'label'    => esc_html__( 'Rewind', 'bricksable' ),
			'type'     => 'checkbox',
			'default'  => false,
			'required' => array( 'customPlayer', '!=', '' ),
		);

		$this->controls['fileFastForward'] = array(
			'tab'      => 'content',
			'label'    => esc_html__( 'Fast forward', 'bricksable' ),
			'type'     => 'checkbox',
			'default'  => false,
			'required' => array( 'customPlayer', '!=', '' ),
		);

		$this->controls['fileSpeed'] = array(
			'tab'      => 'content',
			'label'    => esc_html__( 'Speed', 'bricksable' ),
			'type'     => 'checkbox',
			'default'  => false,
			'required' => array( 'customPlayer', '!=', '' ),
		);

		$this->controls['filePip'] = array(
			'tab'      => 'content',
			'label'    => esc_html__( 'Picture to Picture', 'bricksable' ),
			'type'     => 'checkbox',
			'default'  => false,
			'required' => array( 'customPlayer', '!=', '' ),
		);

		$this->controls['customPlayerEndSeparator'] = array(
			'tab'      => 'content',
			'type'     => 'separator',
			'required' => array( 'customPlayer', '!=', '' ),
		);

		/*
		$this->controls['infoControls'] = array(
			'tab'      => 'content',
			'content'  => esc_html__( 'Set individual video player controls under: Settings > Theme Styles > Element - Video', 'bricksable' ),
			'type'     => 'info',
			'required' => array( 'videoType', '=', array( 'media', 'file', 'meta' ) ),
		);*/

		$this->controls['videoPoster'] = array(
			'tab'         => 'content',
			'label'       => esc_html__( 'Poster', 'bricksable' ),
			'type'        => 'image',
			'description' => esc_html__( 'Set for video SEO best practices.', 'bricksable' ),
			'required'    => array( 'videoType', '=', array( 'media', 'file', 'meta' ) ),
		);

		// OVERLAY / ICON.

		$this->controls['overlay'] = array(
			'tab'      => 'content',
			'group'    => 'icon',
			'type'     => 'background',
			'label'    => esc_html__( 'Overlay', 'bricksable' ),
			'exclude'  => 'video',
			'rerender' => true,
			'css'      => array(
				array(
					'property' => 'background',
					'selector' => '.bricks-video-overlay',
				),
			),
		);

		$this->controls['overlayIcon'] = array(
			'tab'      => 'content',
			'group'    => 'icon',
			'label'    => esc_html__( 'Icon', 'bricksable' ),
			'type'     => 'icon',
			'rerender' => true,
		);

		$this->controls['overlayIconTypography'] = array(
			'tab'      => 'content',
			'group'    => 'icon',
			'label'    => esc_html__( 'Icon typography', 'bricksable' ),
			'type'     => 'typography',
			'css'      => array(
				array(
					'property' => 'font',
					'selector' => '.bricks-video-overlay-icon',
				),
			),
			'exclude'  => array(
				'font-family',
				'font-weight',
				'font-style',
				'text-align',
				'text-decoration',
				'text-transform',
				'line-height',
				'letter-spacing',
			),
			'required' => array( 'overlayIcon.icon', '!=', '' ),
		);

		$this->controls['overlayIconPadding'] = array(
			'tab'      => 'content',
			'group'    => 'icon',
			'label'    => esc_html__( 'Icon padding', 'bricksable' ),
			'type'     => 'spacing',
			'css'      => array(
				array(
					'property' => 'padding',
					'selector' => '.bricks-video-overlay-icon',
				),
			),
			'required' => array( 'overlayIcon', '!=', '' ),
		);

		$this->controls['overlayIconBackgroundColor'] = array(
			'tab'      => 'content',
			'group'    => 'icon',
			'label'    => esc_html__( 'Icon background color', 'bricksable' ),
			'type'     => 'color',
			'css'      => array(
				array(
					'property' => 'background-color',
					'selector' => '.bricks-video-overlay-icon',
				),
			),
			'required' => array( 'overlayIcon', '!=', '' ),
		);

		$this->controls['overlayIconBorder'] = array(
			'tab'      => 'content',
			'group'    => 'icon',
			'label'    => esc_html__( 'Icon border', 'bricksable' ),
			'type'     => 'border',
			'css'      => array(
				array(
					'property' => 'border',
					'selector' => '.bricks-video-overlay-icon',
				),
			),
			'required' => array( 'overlayIcon', '!=', '' ),
		);

		$this->controls['overlayIconBoxShadow'] = array(
			'tab'      => 'content',
			'group'    => 'icon',
			'label'    => esc_html__( 'Icon box shadow', 'bricksable' ),
			'type'     => 'box-shadow',
			'css'      => array(
				array(
					'property' => 'box-shadow',
					'selector' => '.bricks-video-overlay-icon',
				),
			),
			'required' => array( 'overlayIcon', '!=', '' ),
		);

		// Settings.
		$this->controls['stickyOnPlay'] = array(
			'tab'         => 'content',
			'group'       => 'stickySettings',
			'label'       => esc_html__( 'Sticky on play only', 'bricksable' ),
			'description' => esc_html__( 'The video will be sticky only when video is playing.', 'bricksable' ),
			'type'        => 'checkbox',
			'inline'      => true,
			'default'     => true,
		);
		// Position.
		$this->controls['stickyPosition']       = array(
			'tab'         => 'content',
			'group'       => 'stickySettings',
			'label'       => esc_html__( 'Position', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'top-left'     => esc_html__( 'Top Left', 'bricksable' ),
				'top-right'    => esc_html__( 'Top Right', 'bricksable' ),
				'bottom-left'  => esc_html__( 'Bottom Left', 'bricksable' ),
				'bottom-right' => esc_html__( 'Bottom Right', 'bricksable' ),
			),
			'inline'      => true,
			'default'     => 'bottom-right',
			'rerender'    => true,
			'placeholder' => esc_html__( 'Bottom Right', 'bricksable' ),
			'required'    => array( 'stickyCustomPosition', '=', '' ),
		);
		$this->controls['stickyCustomPosition'] = array(
			'tab'    => 'content',
			'group'  => 'stickySettings',
			'label'  => esc_html__( 'Custom Position', 'bricksable' ),
			'type'   => 'checkbox',
			'inline' => true,
		);

		$this->controls['stickyCustomTopPosition'] = array(
			'tab'         => 'content',
			'group'       => 'stickySettings',
			'label'       => esc_html__( 'Top Position', 'bricksable' ),
			'type'        => 'number',
			'css'         => array(
				array(
					'property' => 'top',
					'selector' => '.ba-sticky-video-wrapper.ba-video-is-sticky',
				),
			),
			'units'       => 'px',
			'inline'      => false,
			'pasteStyles' => true,
			'required'    => array( 'stickyCustomPosition', '!=', '' ),
		);

		$this->controls['stickyCustomRightPosition'] = array(
			'tab'         => 'content',
			'group'       => 'stickySettings',
			'label'       => esc_html__( 'Right Position', 'bricksable' ),
			'type'        => 'number',
			'css'         => array(
				array(
					'property' => 'right',
					'selector' => '.ba-sticky-video-wrapper.ba-video-is-sticky',
				),
			),
			'units'       => 'px',
			'inline'      => false,
			'pasteStyles' => true,
			'required'    => array( 'stickyCustomPosition', '!=', '' ),
		);

		$this->controls['stickyCustomBottomPosition'] = array(
			'tab'         => 'content',
			'group'       => 'stickySettings',
			'label'       => esc_html__( 'Bottom Position', 'bricksable' ),
			'type'        => 'number',
			'css'         => array(
				array(
					'property' => 'bottom',
					'selector' => '.ba-sticky-video-wrapper.ba-video-is-sticky',
				),
			),
			'units'       => 'px',
			'inline'      => false,
			'pasteStyles' => true,
			'required'    => array( 'stickyCustomPosition', '!=', '' ),
		);

		$this->controls['stickyCustomLeftPosition'] = array(
			'tab'         => 'content',
			'group'       => 'stickySettings',
			'label'       => esc_html__( 'Left Position', 'bricksable' ),
			'type'        => 'number',
			'css'         => array(
				array(
					'property' => 'left',
					'selector' => '.ba-sticky-video-wrapper.ba-video-is-sticky',
				),
			),
			'units'       => 'px',
			'inline'      => false,
			'pasteStyles' => true,
			'required'    => array( 'stickyCustomPosition', '!=', '' ),
		);

		$this->controls['StickyVideoWidth']  = array(
			'tab'         => 'content',
			'group'       => 'stickySettings',
			'label'       => esc_html__( 'Sticky Video Width', 'bricksable' ),
			'description' => esc_html__( 'The minimum width is 280px.', 'bricksable' ),
			'type'        => 'number',
			'css'         => array(
				array(
					'property' => 'max-width',
					'selector' => '.ba-video-is-sticky',
				),
			),
			'units'       => 'px',
			'default'     => '350px',
			'inline'      => false,
			'pasteStyles' => true,
			'placeholder' => '350px',
		);
		$this->controls['StickyVideoHeight'] = array(
			'tab'         => 'content',
			'group'       => 'stickySettings',
			'label'       => esc_html__( 'Sticky Video Height', 'bricksable' ),
			'description' => esc_html__( 'The minimum height is 158px.', 'bricksable' ),
			'type'        => 'number',
			'css'         => array(
				array(
					'property' => 'max-height',
					'selector' => '.ba-video-is-sticky',
				),
			),
			'units'       => 'px',
			'default'     => '200px',
			'inline'      => false,
			'pasteStyles' => true,
			'placeholder' => '200px',
		);

		// Close Button.
		$this->controls['stickyCloseIcon'] = array(
			'tab'     => 'content',
			'group'   => 'stickyCloseButton',
			'label'   => esc_html__( 'Close Icon', 'bricksable' ),
			'type'    => 'icon',
			'default' => array(
				'library' => 'themify',
				'icon'    => 'ti-close',
			),
		);

		$this->controls['stickyCloseButtonBackground'] = array(
			'tab'   => 'content',
			'group' => 'stickyCloseButton',
			'type'  => 'background',
			'label' => esc_html__( 'Close Button Background', 'bricksable' ),
			'css'   => array(
				array(
					'property' => 'background',
					'selector' => '.ba-sticky-video-close-icon',
				),
			),
		);

		$this->controls['stickyCloseIconTypography'] = array(
			'tab'      => 'content',
			'group'    => 'stickyCloseButton',
			'label'    => esc_html__( 'Close Icon Typography', 'bricksable' ),
			'type'     => 'typography',
			'css'      => array(
				array(
					'property' => 'font',
					'selector' => '.ba-sticky-video-close-icon',
				),
			),
			'exclude'  => array(
				'font-family',
				'font-weight',
				'font-style',
				'text-align',
				'text-decoration',
				'text-transform',
				'line-height',
				'letter-spacing',
			),
			'default'  => array(
				'font-size' => '16px',
			),
			'required' => array( 'stickyCloseIcon.icon', '!=', '' ),
		);

		$this->controls['stickyCloseIconBorder'] = array(
			'tab'    => 'content',
			'group'  => 'stickyCloseButton',
			'label'  => esc_html__( 'Border', 'bricksable' ),
			'type'   => 'border',
			'css'    => array(
				array(
					'property' => 'border',
					'selector' => '.ba-sticky-video-close-icon',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		$this->controls['stickyCloseIconBoxShadow'] = array(
			'tab'    => 'content',
			'group'  => 'stickyCloseButton',
			'label'  => esc_html__( 'BoxShadow', 'bricksable' ),
			'type'   => 'box-shadow',
			'css'    => array(
				array(
					'property' => 'box-shadow',
					'selector' => '.ba-sticky-video-close-icon',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		$this->controls['stickyCloseIconPadding'] = array(
			'tab'      => 'content',
			'group'    => 'stickyCloseButton',
			'label'    => esc_html__( 'Close Icon Padding', 'bricksable' ),
			'type'     => 'spacing',
			'css'      => array(
				array(
					'property' => 'padding',
					'selector' => '.ba-sticky-video-close-icon',
				),
			),
			'required' => array( 'stickyCloseIcon', '!=', '' ),
		);

		$this->controls['stickyCloseTopPosition'] = array(
			'tab'         => 'content',
			'group'       => 'stickyCloseButton',
			'label'       => esc_html__( 'Top Position', 'bricksable' ),
			'type'        => 'number',
			'css'         => array(
				array(
					'property' => 'top',
					'selector' => '.ba-sticky-video-close-icon',
				),
			),
			'units'       => 'px',
			'default'     => '-30px',
			'inline'      => false,
			'pasteStyles' => true,
			'placeholder' => '-30px',
		);

		$this->controls['stickyCloseRightPosition'] = array(
			'tab'         => 'content',
			'group'       => 'stickyCloseButton',
			'label'       => esc_html__( 'Right Position', 'bricksable' ),
			'type'        => 'number',
			'css'         => array(
				array(
					'property' => 'right',
					'selector' => '.ba-sticky-video-close-icon',
				),
			),
			'units'       => 'px',
			'default'     => '0px',
			'inline'      => false,
			'pasteStyles' => true,
			'placeholder' => '0px',
		);

		$this->controls['stickyClosePauseVideo'] = array(
			'tab'     => 'content',
			'group'   => 'stickyCloseButton',
			'label'   => esc_html__( 'Pause Video on Close', 'bricksable' ),
			'type'    => 'checkbox',
			'default' => true,
		);

		$this->controls['animationSeparator'] = array(
			'type'  => 'separator',
			'tab'   => 'content',
			'group' => 'stickySettings',
			'label' => esc_html__( 'Animation', 'bricksable' ),
		);

		$this->controls['animationIn'] = array(
			'tab'         => 'content',
			'group'       => 'stickySettings',
			'label'       => esc_html__( 'Entry animation', 'bricksable' ),
			'type'        => 'select',
			'searchable'  => true,
			'options'     => array(
				'bounce'            => esc_html__( 'bounce', 'bricksable' ),
				'flash'             => esc_html__( 'flash', 'bricksable' ),
				'pulse'             => esc_html__( 'pulse', 'bricksable' ),
				'rubberBand'        => esc_html__( 'rubberBand', 'bricksable' ),
				'swing'             => esc_html__( 'swing', 'bricksable' ),
				'tada'              => esc_html__( 'tada', 'bricksable' ),
				'wobble'            => esc_html__( 'wobble', 'bricksable' ),
				'jello'             => esc_html__( 'jello', 'bricksable' ),
				'bounceIn'          => esc_html__( 'bounceIn', 'bricksable' ),
				'bounceInDown'      => esc_html__( 'bounceInDown', 'bricksable' ),
				'bounceInLeft'      => esc_html__( 'bounceInLeft', 'bricksable' ),
				'bounceInRight'     => esc_html__( 'bounceInRight', 'bricksable' ),
				'bounceInUp'        => esc_html__( 'bounceInUp', 'bricksable' ),
				'backInDown'        => esc_html__( 'backInDown', 'bricksable' ),
				'backInLeft'        => esc_html__( 'backInLeft', 'bricksable' ),
				'backInRight'       => esc_html__( 'backInRight', 'bricksable' ),
				'backInUp'          => esc_html__( 'backInUp', 'bricksable' ),
				'fadeIn'            => esc_html__( 'fadeIn', 'bricksable' ),
				'fadeInDown'        => esc_html__( 'fadeInDown', 'bricksable' ),
				'fadeInDownBig'     => esc_html__( 'fadeInDownBig', 'bricksable' ),
				'fadeInLeft'        => esc_html__( 'fadeInLeft', 'bricksable' ),
				'fadeInLeftBig'     => esc_html__( 'fadeInLeftBig', 'bricksable' ),
				'fadeInRight'       => esc_html__( 'fadeInRight', 'bricksable' ),
				'fadeInRightBig'    => esc_html__( 'fadeInRightBig', 'bricksable' ),
				'fadeInUp'          => esc_html__( 'fadeInUp', 'bricksable' ),
				'fadeInUpBig'       => esc_html__( 'fadeInUpBig', 'bricksable' ),
				'flip'              => esc_html__( 'flip', 'bricksable' ),
				'flipInX'           => esc_html__( 'flipInX', 'bricksable' ),
				'flipInY'           => esc_html__( 'flipInY', 'bricksable' ),
				'lightSpeedInRight' => esc_html__( 'lightSpeedInRight', 'bricksable' ),
				'lightSpeedInLeft'  => esc_html__( 'lightSpeedInLeft', 'bricksable' ),
				'rotateIn'          => esc_html__( 'rotateIn', 'bricksable' ),
				'rotateInDownLeft'  => esc_html__( 'rotateInDownLeft', 'bricksable' ),
				'rotateInDownRight' => esc_html__( 'rotateInDownRight', 'bricksable' ),
				'rotateInUpLeft'    => esc_html__( 'rotateInUpLeft', 'bricksable' ),
				'rotateInUpRight'   => esc_html__( 'rotateInUpRight', 'bricksable' ),
				'slideInUp'         => esc_html__( 'slideInUp', 'bricksable' ),
				'slideInDown'       => esc_html__( 'slideInDown', 'bricksable' ),
				'slideInLeft'       => esc_html__( 'slideInLeft', 'bricksable' ),
				'slideInRight'      => esc_html__( 'slideInRight', 'bricksable' ),
				'zoomIn'            => esc_html__( 'zoomIn', 'bricksable' ),
				'zoomInDown'        => esc_html__( 'zoomInDown', 'bricksable' ),
				'zoomInLeft'        => esc_html__( 'zoomInLeft', 'bricksable' ),
				'zoomInRight'       => esc_html__( 'zoomInRight', 'bricksable' ),
				'zoomInUp'          => esc_html__( 'zoomInUp', 'bricksable' ),
				'jackInTheBox'      => esc_html__( 'jackInTheBox', 'bricksable' ),
				'rollIn'            => esc_html__( 'rollIn', 'bricksable' ),
			),
			'inline'      => true,
			'default'     => 'fadeInUp',
			'placeholder' => esc_html__( 'fadeInUp', 'bricksable' ),
		);

		$this->controls['animationDuration'] = array(
			'tab'         => 'content',
			'group'       => 'stickySettings',
			'label'       => esc_html__( 'Animation duration', 'bricksable' ),
			'type'        => 'select',
			'searchable'  => true,
			'options'     => array(
				'slower' => esc_html__( 'Very slow', 'bricksable' ),
				'slow'   => esc_html__( 'Slow', 'bricksable' ),
				'normal' => esc_html__( 'Normal', 'bricksable' ),
				'fast'   => esc_html__( 'Fast', 'bricksable' ),
				'faster' => esc_html__( 'Very fast', 'bricksable' ),
				'custom' => esc_html__( 'Custom', 'bricksable' ),
			),
			'inline'      => true,
			'default'     => 'normal',
			'placeholder' => esc_html__( 'Normal', 'bricksable' ) . ' (1s)',
		);

		$this->controls['animationDurationCustom'] = array(
			'tab'         => 'content',
			'group'       => 'stickySettings',
			'label'       => esc_html__( 'Animation duration', 'bricksable' ) . ' (' . esc_html__( 'Custom', 'bricksable' ) . ')',
			'type'        => 'text',
			'css'         => array(
				array(
					'property' => 'animation-duration',
					'selector' => '.ba-video-is-sticky',
				),
			),
			'description' => esc_html__( 'For example: "1s" or "500ms"', 'bricksable' ),
			'inline'      => true,
			'required'    => array( 'animationDuration', '=', 'custom' ),
		);

		$this->controls['animationDelay'] = array(
			'tab'         => 'content',
			'group'       => 'stickySettings',
			'label'       => esc_html__( 'Animation delay', 'bricksable' ),
			'type'        => 'text',
			'css'         => array(
				array(
					'property' => 'animation-delay',
					'selector' => '.ba-video-is-sticky',
				),
			),
			'inline'      => true,
			'description' => esc_html__( 'For example:  "1s" or "500ms" or "-2.5s"', 'bricksable' ),
			'placeholder' => '0s',
		);
	}

	public function render() {
		$settings = $this->settings;

		// Return: No video type selected.
		if ( empty( $settings['videoType'] ) ) {
			return $this->render_element_placeholder(
				array(
					'title' => esc_html__( 'No video selected.', 'bricksable' ),
				)
			);
		}

		// Parse settings if videoType = 'meta' try fitting content into the other 'videoType' flows.
		$settings = $this->get_normalized_video_settings( $settings );

		$source = ! empty( $settings['videoType'] ) ? $settings['videoType'] : false;

		if ( $source === 'youtube' && empty( $settings['youTubeId'] ) ) {
			return $this->render_element_placeholder(
				array(
					'title' => esc_html__( 'No YouTube ID provided.', 'bricksable' ),
				)
			);
		}

		if ( $source === 'vimeo' && empty( $settings['vimeoId'] ) ) {
			return $this->render_element_placeholder(
				array(
					'title' => esc_html__( 'No Vimeo ID provided.', 'bricksable' ),
				)
			);
		}

		if ( $source === 'media' && empty( $settings['media'] ) ) {
			return $this->render_element_placeholder(
				array(
					'title' => esc_html__( 'No video selected.', 'bricksable' ),
				)
			);
		}

		if ( $source === 'file' && empty( $settings['fileUrl'] ) ) {
			return $this->render_element_placeholder(
				array(
					'title' => esc_html__( 'No file URL provided.', 'bricksable' ),
				)
			);
		}

		// If meta is still set, then something failed.
		if ( $source === 'meta' ) {
			if ( empty( $settings['useDynamicData'] ) ) {
				$message = esc_html__( 'No dynamic data set.', 'bricksable' );
			} else {
				$message = esc_html__( 'Dynamic data is empty.', 'bricksable' );
			}

			if ( ! empty( $message ) ) {
				return $this->render_element_placeholder(
					array(
						'title' => $message,
					)
				);
			}
		}

		// Build video URL.
		$video_url        = '';
		$video_parameters = array();

		// Use custom HTML5 video player: https://plyr.io (if controls are enabled).
		$use_custom_player = isset( $settings['customPlayer'] ) && isset( $settings['fileControls'] );

		switch ( $source ) {
			case 'youtube':
				$video_url = "https://www.youtube.com/embed/{$settings['youTubeId']}?";

				// https://developers.google.com/youtube/player_parameters.
				$video_parameters[] = 'wmode=opaque';

				if ( isset( $settings['youtubeAutoplay'] ) ) {
					$video_parameters[] = 'autoplay=1';
				}

				if ( ! isset( $settings['youtubeControls'] ) ) {
					$video_parameters[] = 'controls=0';
				}

				if ( isset( $settings['youtubeLoop'] ) ) {
					// Loop in iframe requires 'playlist' parameter.
					$video_parameters[] = "loop=1&playlist={$settings['youTubeId']}";
				}

				if ( isset( $settings['youtubeMute'] ) ) {
					$video_parameters[] = 'mute=1';
				}

				if ( ! isset( $settings['youtubeShowinfo'] ) ) {
					$video_parameters[] = 'showinfo=0';
				}

				if ( ! isset( $settings['youtubeRel'] ) ) {
					$video_parameters[] = 'rel=0';
				}

				// Add enablejsapi to autopause on bricks/popup/close (@since 1.8).
				$video_parameters[] = 'enablejsapi=1';

				break;

			case 'vimeo':
				$video_url = "https://player.vimeo.com/video/{$settings['vimeoId']}?";

				// https://developer.vimeo.com/apis/oembed#arguments.
				if ( isset( $settings['vimeoAutoplay'] ) ) {
					$video_parameters[] = 'autoplay=1';
				}

				if ( isset( $settings['vimeoHash'] ) ) {
					$video_parameters[] = 'h=' . $settings['vimeoHash'];
				}

				if ( isset( $settings['vimeoLoop'] ) ) {
					$video_parameters[] = 'loop=1';
				}

				if ( isset( $settings['vimeoMute'] ) ) {
					$video_parameters[] = 'muted=1';
				}

				if ( ! isset( $settings['vimeoByline'] ) ) {
					$video_parameters[] = 'byline=0';
				}

				if ( ! isset( $settings['vimeoTitle'] ) ) {
					$video_parameters[] = 'title=0';
				}

				if ( ! isset( $settings['vimeoPortrait'] ) ) {
					$video_parameters[] = 'portrait=0';
				}

				if ( isset( $settings['vimeoDoNotTrack'] ) ) {
					$video_parameters[] = 'dnt=1';
				}

				if ( ! empty( $settings['vimeoColor']['hex'] ) ) {
					$vimeo_color = str_replace( '#', '', $settings['vimeoColor']['hex'] );

					$video_parameters[] = "color={$vimeo_color}";
				}

				break;

			case 'media':
			case 'file':
				if ( $source === 'media' && ! empty( $settings['media']['url'] ) ) {
					$video_url = esc_url( $settings['media']['url'] );
				} elseif ( $source === 'file' && ! empty( $settings['fileUrl'] ) ) {
					$video_url = esc_url( bricks_render_dynamic_data( $settings['fileUrl'] ) );
				}

				$video_classes = array();

				if ( $this->lazy_load() ) {
					$video_classes = array( 'bricks-lazy-hidden' );
					$this->set_attribute( 'video', 'data-src', $video_url );
				} else {
					$this->set_attribute( 'video', 'src', $video_url );
				}
				$this->set_attribute( 'video', 'id', 'ba-sticky-video-' . $this->id );

				// Load custom video player if enabled.
				if ( $use_custom_player ) {
					$video_classes[] = 'bricks-plyr';
				}

				$this->set_attribute( 'video', 'class', $video_classes );

				if ( isset( $settings['filePreload'] ) ) {
					$this->set_attribute( 'video', 'preload', $settings['filePreload'] );
				}

				if ( isset( $settings['fileAutoplay'] ) ) {
					$this->set_attribute( 'video', 'autoplay' );

					// Necessary for autoplaying in iOS (https://webkit.org/blog/6784/new-video-policies-for-ios/).
					$this->set_attribute( 'video', 'playsinline' );
				} elseif ( isset( $settings['fileInline'] ) ) {
					$this->set_attribute( 'video', 'playsinline' );
				}

				if ( isset( $settings['fileControls'] ) ) {
					$this->set_attribute( 'video', 'controls' );
				} elseif ( ! $use_custom_player ) {
					$this->set_attribute( 'video', 'onclick', 'this.paused ? this.play() : this.pause()' );
				}

				if ( isset( $settings['fileLoop'] ) ) {
					$this->set_attribute( 'video', 'loop' );
				}

				if ( isset( $settings['fileMute'] ) ) {
					$this->set_attribute( 'video', 'muted' );
				}

				// Video poster (@since 1.8.5).
				$video_poster_image = $this->get_video_image_by_key( 'videoPoster' );

				if ( ! empty( $video_poster_image['url'] ) ) {
					$this->set_attribute( 'video', 'poster', $video_poster_image['url'] );
				}

				break;
		}

		// Set data-id so we could track the plyr instances.
		$this->set_attribute( 'wrapper', 'data-id', \Bricks\Helpers::generate_random_id( false ) );

		// Add parameters to final video URL.
		if ( ! empty( $video_parameters ) ) {
			$video_url .= join( '&', $video_parameters );
		}

		// STEP: Render.

		// Sticky Video Options.
		$sticky_options = array(
			'videoType'             => isset( $settings['videoType'] ) ? $settings['videoType'] : 'youtube',
			'stickyOnPlay'          => isset( $settings['stickyOnPlay'] ) ? true : false,
			'animationIn'           => isset( $settings['animationIn'] ) ? esc_attr( $settings['animationIn'] ) : 'fadeInUp',
			'animationDuration'     => isset( $settings['animationDuration'] ) && '' !== $settings['animationDuration'] ? 'brx-animate-' . $settings['animationDuration'] : '',
			'stickyClosePauseVideo' => isset( $settings['stickyClosePauseVideo'] ) ? true : false,
			'customPlayer'          => isset( $this->settings['customPlayer'] ) ? true : false,

		);

		$this->set_attribute( '_root', 'data-ba-bricks-sticky-video-options', wp_json_encode( $sticky_options ) );
		// Video HTML wrapper with iframe / video element for popup and non-popup settings.

		$output = "<div {$this->render_attributes( '_root' )}>";

		$overlay_icon = ! empty( $settings['overlayIcon'] ) ? $settings['overlayIcon'] : false;

		// Check: Theme style for video 'overlayIcon' setting (@since 1.7).
		if ( ! $overlay_icon && ! empty( $this->theme_styles['overlayIcon'] ) ) {
			$overlay_icon = $this->theme_styles['overlayIcon'];
		}

		$icon = $overlay_icon ? self::render_icon( $overlay_icon, array( 'bricks-video-overlay-icon' ) ) : false;

		if ( $use_custom_player ) {
			$video_config_plyr = array();

			// https://github.com/sampotts/plyr/blob/master/controls.md.
			if ( isset( $settings['fileControls'] ) ) {
				$video_config_plyr['controls'] = array( 'play' );

				// Play button (if no custom icon is set).
				if ( ! $icon ) {
					$video_config_plyr['controls'][] = 'play-large';
				}

				if ( isset( $settings['fileRestart'] ) ) {
					$video_config_plyr['controls'][] = 'restart';
				}

				if ( isset( $settings['fileRewind'] ) ) {
					$video_config_plyr['controls'][] = 'rewind';
				}

				if ( isset( $settings['fileFastForward'] ) ) {
					$video_config_plyr['controls'][] = 'fast-forward';
				}

				$video_config_plyr['controls'][] = 'current-time';
				$video_config_plyr['controls'][] = 'duration';
				$video_config_plyr['controls'][] = 'progress';
				$video_config_plyr['controls'][] = 'mute';
				$video_config_plyr['controls'][] = 'volume';

				if ( isset( $settings['fileSpeed'] ) ) {
					$video_config_plyr['controls'][] = 'settings';
				}

				if ( isset( $settings['filePip'] ) ) {
					$video_config_plyr['controls'][] = 'pip';
				}

				$video_config_plyr['controls'][] = 'fullscreen';
			}

			if ( isset( $settings['fileMute'] ) ) {
				$video_config_plyr['muted'] = true;

				// Store false required for muted to take effect.
				$video_config_plyr['storage'] = false;
			}
			// Future use for custom player.
			/*
			$this->set_attribute( 'stickyCustomWrapper', 'data-plyr-config', wp_json_encode( $video_config_plyr ) );
			*/
		}
		// stickyWrapper.
		$this->set_attribute(
			'stickyWrapper',
			'class',
			array(
				'ba-sticky-video-wrapper',
				isset( $settings['stickyCustomPosition'] ) && true === $settings['stickyCustomPosition'] ? '' : 'ba-sticky-video-' . $settings['stickyPosition'],
			),
		);

		$output .= "<div {$this->render_attributes( 'stickyWrapper' )}>";

		// Future use for custom player.
		/*
		if ( $use_custom_player ) {
			// stickyCustomWrapper.
			$this->set_attribute(
				'stickyCustomWrapper',
				'class',
				array(
					'plyr__video-embed',
				),
			);
			$output .= "<div {$this->render_attributes( 'stickyCustomWrapper' )}>";
		}
		*/
		$sticky_close_icon = ! empty( $settings['stickyCloseIcon'] ) ? $settings['stickyCloseIcon'] : false;
		if ( $sticky_close_icon ) {
			$output .= self::render_icon( $sticky_close_icon, array( 'ba-sticky-video-close-icon', 'icon' ) );
		}

		if ( $use_custom_player ) {
			$this->set_attribute( 'video', 'data-plyr-config', wp_json_encode( $video_config_plyr ) );
		}

		if ( $source === 'media' || $source === 'file' || $source === 'meta' ) {
			$output .= '<video ' . $this->render_attributes( 'video' ) . '>';
			$output .= '<p>' . esc_html__( 'Your browser does not support the video tag.', 'bricksable' ) . '</p>';
			$output .= '</video>';
		}

		if ( $source === 'youtube' || $source === 'vimeo' ) {
			$this->set_attribute( 'iframe', 'id', 'ba-sticky-video-' . $this->id );
			$this->set_attribute( 'iframe', 'allowfullscreen' );
			$this->set_attribute( 'iframe', 'allow', 'autoplay' );

			if ( ! empty( $settings['iframeTitle'] ) ) {
				$this->set_attribute( 'iframe', 'title', wp_strip_all_tags( $this->render_dynamic_data( $settings['iframeTitle'] ) ) );
			}
			$is_auto_play = $source === 'youtube' ? isset( $settings['youTubeAutoPlay'] ) : isset( $settings['vimeoAutoPlay'] );

			// STEP: Render YouTube/Vimeo iframe or div with background image.
			if ( ! empty( $settings['previewImage'] ) && ! $is_auto_play ) {
				// STEP: Render div with background image when video lazy load is enabled and autoplay is disabled.
				$this->set_attribute( 'iframe', 'data-iframe-src', $video_url );
				$this->set_attribute( 'iframe', 'class', 'bricks-video-preview-image' );

				$preview_image_url = $this->get_preview_image_url( $settings );
				$background_style  = $preview_image_url ? "background-image: url($preview_image_url);" : false;

				// STEP: Add background image to div.
				if ( $background_style ) {
					if ( $this->lazy_load() ) {
						// STEP: Global lazy load is enabled, background image added as data-style attribute.
						$this->set_attribute( 'iframe', 'data-style', $background_style );
						$this->set_attribute( 'iframe', 'class', 'bricks-lazy-hidden' );
					} else {
						// STEP: Global lazy load is enabled, background image added as data-style attribute.
						$this->set_attribute( 'iframe', 'style', $background_style );
					}
				}

				// Render as div.
				$output .= '<div ' . $this->render_attributes( 'iframe' ) . '></div>';
			}

			// STEP: Render iframe (when video lazy load is disabled or autoplay is enabled).
			else {
				if ( $this->lazy_load() ) {
					// STEP: Global lazy load is enabled, iframe src added as data-src attribute.
					$this->set_attribute( 'iframe', 'data-src', $video_url );
					$this->set_attribute( 'iframe', 'class', 'bricks-lazy-hidden' );
				} else {
					$this->set_attribute( 'iframe', 'src', $video_url );
				}

				// Render as iframe.
				$output .= '<iframe ' . $this->render_attributes( 'iframe' ) . '></iframe>';

			}
		}

		// Check: Element & theme style for 'overlay' setting (@since 1.7).
		// Use new helper function to check for 'overlay' setting from different breakpoints (@since 1.8).
		$has_overlay = \Bricks\Helpers::element_setting_has_value( 'overlay', $settings ) || \Bricks\Helpers::element_setting_has_value( 'overlay', $this->theme_styles );

		// Check: Element classes for 'overlay' setting (@since 1.7.1).
		$element_class_has_overlay = $this->element_classes_have( 'overlay' );

		if ( $element_class_has_overlay ) {
			$has_overlay = true;
		}

		if ( $has_overlay ) {
			$output .= $this->lazy_load() ? '<div class="bricks-lazy-hidden bricks-video-overlay"></div>' : '<div class="bricks-video-overlay"></div>';
		}

		if ( $icon ) {
			$output .= $icon;
		}
		// stickyCustomWrapper. Future use.
		/*
		if ( $use_custom_player ) {
			$output .= '</div>';
		}*/
		// stickyWrapper.
		$output .= '</div>';
		// Root.
		$output .= '</div>';

		echo $output;
	}

	public function convert_element_settings_to_block( $settings ) {
		$settings = $this->get_normalized_video_settings( $settings );
		$source   = ! empty( $settings['videoType'] ) ? $settings['videoType'] : false;
		$attrs    = array();
		$output   = '';

		// Video Type: Media file / File URL.
		if ( $source === 'media' || $source === 'file' ) {
			$block['blockName'] = 'core/video';

			if ( isset( $settings['media']['id'] ) ) {
				$attrs['id'] = $settings['media']['id'];
			}

			$output = '<figure class="wp-block-video"><video ';

			if ( isset( $settings['fileAutoplay'] ) ) {
				$output .= 'autoplay ';
			}

			if ( isset( $settings['fileControls'] ) ) {
				$output .= 'controls ';
			}

			if ( isset( $settings['fileLoop'] ) ) {
				$output .= 'loop ';
			}

			if ( isset( $settings['fileMute'] ) ) {
				$output .= 'muted ';
			}

			if ( isset( $settings['filePreload'] ) ) {
				$output .= 'preload="' . $settings['filePreload'] . '"';
			}

			if ( $source === 'media' ) {
				$output .= 'src="' . wp_get_attachment_url( intval( $settings['media']['id'] ) ) . '"';
			}

			if ( $source === 'file' ) {
				$output .= 'src="' . esc_url( $settings['fileUrl'] ) . '"';
			}

			if ( isset( $settings['fileInline'] ) ) {
				$output .= ' playsinline';
			}

			$output .= '></video></figure>';
		}

		// Video Type: YouTube.
		if ( $source === 'youtube' && isset( $settings['youTubeId'] ) ) {
			$block                     = array( 'blockName' => 'core-embed/youtube' );
			$attrs['url']              = 'https://www.youtube.com/watch?v=' . $settings['youTubeId'];
			$attrs['providerNameSlug'] = 'youtube';
			$attrs['type']             = 'video';
			$output                    = '<figure class="wp-block-embed-youtube wp-block-embed is-type-video is-provider-youtube"><div class="wp-block-embed__wrapper">' . $attrs['url'] . '</div></figure>';
		}

		// Video Type: Vimeo.
		if ( $source === 'vimeo' && isset( $settings['vimeoId'] ) ) {
			$block                     = array( 'blockName' => 'core-embed/vimeo' );
			$attrs['url']              = 'https://www.vimeo.com/' . $settings['vimeoId'];
			$attrs['providerNameSlug'] = 'vimeo';
			$attrs['type']             = 'video';
			$output                    = '<figure class="wp-block-embed-vimeo wp-block-embed is-type-video is-provider-vimeo"><div class="wp-block-embed__wrapper">' . $attrs['url'] . '</div></figure>';
		}

		$block['attrs']        = $attrs;
		$block['innerContent'] = array( $output );

		return $block;
	}

	public function convert_block_to_element_settings( $block, $attributes ) {
		$video_provider = isset( $attributes['providerNameSlug'] ) ? $attributes['providerNameSlug'] : false;

		// Type: YouTube.
		if ( $video_provider === 'youtube' ) {
			// Get YouTube video ID.
			parse_str( parse_url( $attributes['url'], PHP_URL_QUERY ), $url_params );

			return array(
				'videoType'       => 'youtube',
				'youTubeId'       => $url_params['v'],
				'youtubeControls' => true,
			);
		}

		// Type: Vimeo.
		if ( $video_provider === 'vimeo' ) {
			// Get Vimeo video ID.
			$url_parts = explode( '/', $attributes['url'] );

			$video_url = '';

			foreach ( $url_parts as $url_part ) {
				if ( is_numeric( $url_part ) ) {
					$video_url = $url_part;
				}
			}

			return array(
				'videoType'     => 'vimeo',
				'vimeoId'       => $video_url,
				'vimeoControls' => true,
			);
		}

		$output = $block['innerHTML'];

		// Type: Media file.
		$media_video_id = isset( $attributes['id'] ) ? intval( $attributes['id'] ) : 0;

		if ( $media_video_id ) {
			$media = array(
				'id'       => $media_video_id,
				'filename' => basename( get_attached_file( $media_video_id ) ),
				'url'      => wp_get_attachment_url( $media_video_id ),
				// 'mime'     => '',
			);

			$element_settings = array(
				'videoType'    => 'media',
				'media'        => $media,
				'fileAutoplay' => strpos( $output, ' autoplay' ) !== false,
				'fileControls' => strpos( $output, ' controls' ) !== false,
				'fileLoop'     => strpos( $output, ' loop' ) !== false,
				'fileMute'     => strpos( $output, ' muted' ) !== false,
				'fileInline'   => strpos( $output, ' playsinline' ) !== false,
			);

			if ( strpos( $output, ' preload="auto"' ) !== false ) {
				$element_settings['filePreload'] = 'auto';
			}

			return $element_settings;
		}

		// Type: File URL.
		$video_url_parts = explode( '"', $output );
		$video_url       = '';

		foreach ( $video_url_parts as $video_url_part ) {
			if ( filter_var( $video_url_part, FILTER_VALIDATE_URL ) ) {
				$video_url = $video_url_part;
			}
		}

		if ( $video_url ) {
			$element_settings = array(
				'videoType'    => 'file',
				'fileUrl'      => $video_url,
				'fileAutoplay' => strpos( $output, ' autoplay' ) !== false,
				'fileControls' => strpos( $output, ' controls' ) !== false,
				'fileLoop'     => strpos( $output, ' loop' ) !== false,
				'fileMute'     => strpos( $output, ' muted' ) !== false,
				'fileInline'   => strpos( $output, ' playsinline' ) !== false,
			);

			if ( strpos( $output, ' preload="auto"' ) !== false ) {
				$element_settings['filePreload'] = 'auto';
			}

			return $element_settings;
		}
	}

	/**
	 * Helper function to parse the settings when videoType = meta
	 *
	 * @return array
	 */
	public function get_normalized_video_settings( $settings = array() ) {
		if ( empty( $settings['videoType'] ) ) {
			return $settings;
		}

		if ( $settings['videoType'] === 'youtube' ) {

			if ( ! empty( $settings['youTubeId'] ) ) {
				$settings['youTubeId'] = $this->render_dynamic_data( $settings['youTubeId'] );
			}

			if ( ! empty( $settings['iframeTitle'] ) ) {
				$settings['iframeTitle'] = $this->render_dynamic_data( $settings['iframeTitle'] );
			}

			return $settings;
		}

		if ( $settings['videoType'] === 'vimeo' ) {

			if ( ! empty( $settings['vimeoId'] ) ) {
				$settings['vimeoId'] = $this->render_dynamic_data( $settings['vimeoId'] );
			}

			if ( ! empty( $settings['iframeTitle'] ) ) {
				$settings['iframeTitle'] = $this->render_dynamic_data( $settings['iframeTitle'] );
			}

			if ( ! empty( $settings['vimeoHash'] ) ) {
				$settings['vimeoHash'] = $this->render_dynamic_data( $settings['vimeoHash'] );
			}

			return $settings;
		}

		// Check 'file' and 'meta' videoType for dynamic data.
		$dynamic_data = false;

		if ( $settings['videoType'] === 'file' && ! empty( $settings['fileUrl'] ) && strpos( $settings['fileUrl'], '{' ) === 0 ) {
			$dynamic_data = $settings['fileUrl'];
		}

		if ( $settings['videoType'] === 'meta' && ! empty( $settings['useDynamicData'] ) ) {
			$dynamic_data = $settings['useDynamicData'];
		}

		if ( ! $dynamic_data ) {
			return $settings;
		}

		$meta_video_url = $this->render_dynamic_data_tag( $dynamic_data, 'link' );

		if ( empty( $meta_video_url ) ) {
			return $settings;
		}

		// Is YouTube video.
		if ( preg_match( '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $meta_video_url, $matches ) ) {
			// Regex from @see: https://gist.github.com/ghalusa/6c7f3a00fd2383e5ef33.
			$settings['youTubeId'] = $matches[1];
			$settings['videoType'] = 'youtube';

			if ( isset( $settings['fileAutoplay'] ) ) {
				$settings['youtubeAutoplay'] = $settings['fileAutoplay'];
			} else {
				unset( $settings['youtubeAutoplay'] );
			}

			if ( isset( $settings['fileControls'] ) ) {
				$settings['youtubeControls'] = $settings['fileControls'];
			} else {
				unset( $settings['youtubeControls'] );
			}

			if ( isset( $settings['fileLoop'] ) ) {
				$settings['youtubeLoop'] = $settings['fileLoop'];
			} else {
				unset( $settings['youtubeLoop'] );
			}

			if ( isset( $settings['fileMute'] ) ) {
				$settings['youtubeMute'] = $settings['fileMute'];
			} else {
				unset( $settings['youtubeMute'] );
			}
		}

		// Is Vimeo video.
		elseif ( preg_match( '%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)$%im', $meta_video_url, $matches ) ) {
			// Regex from @see: https://gist.github.com/anjan011/1fcecdc236594e6d700f.
			$settings['vimeoId']   = $matches[3];
			$settings['videoType'] = 'vimeo';

			if ( isset( $settings['fileAutoplay'] ) ) {
				$settings['vimeoAutoplay'] = $settings['fileAutoplay'];
			} else {
				unset( $settings['vimeoAutoplay'] );
			}

			if ( isset( $settings['fileLoop'] ) ) {
				$settings['vimeoLoop'] = $settings['fileLoop'];
			} else {
				unset( $settings['vimeoLoop'] );
			}

			if ( isset( $settings['fileMute'] ) ) {
				$settings['vimeoMute'] = $settings['fileMute'];
			} else {
				unset( $settings['vimeoMute'] );
			}
		} else {
			// Url of a video file (either hosted or external).
			$settings['fileUrl']   = $meta_video_url;
			$settings['videoType'] = 'file';
		}

		// Later the settings are used to control the video and the custom field should not be present.
		unset( $settings['useDynamicData'] );

		return $settings;
	}

	/**
	 * Get the video image image URL
	 *
	 * @param array $settings
	 *
	 * @since 1.7.2
	 */
	public function get_preview_image_url( $settings = array() ) {
		$preview_image_type = ! empty( $this->settings['previewImage'] ) ? $this->settings['previewImage'] : '';
		$preview_image      = $preview_image_type === 'custom' && ! empty( $this->settings['previewImageCustom'] ) ? $this->get_video_image_by_key( 'previewImageCustom' ) : false;

		// STEP: Preview image.
		if ( ! empty( $preview_image['url'] ) ) {
			return $preview_image['url'];
		}

		// Default: Youtube or Vimeo image.
		$video_type = ! empty( $settings['videoType'] ) ? $settings['videoType'] : false;

		// STEP: Get YouTube video preview image from API.
		if ( $video_type === 'youtube' ) {
			return "https://img.youtube.com/vi/{$settings['youTubeId']}/hqdefault.jpg";
		}

		// STEP: Get the Vimeo video preview image from API.
		if ( $video_type === 'vimeo' ) {
			$video_data = wp_remote_get( "https://vimeo.com/api/v2/video/{$settings['vimeoId']}.json" );

			// 404 error is returned if the video is not found, so we need to check for that.
			if ( ! is_wp_error( $video_data ) && $video_data['response']['code'] !== 404 ) {
				$video_data = json_decode( $video_data['body'] );

				// Ensure that the thumbnail_large exists before using it.
				if ( ! empty( $video_data[0]->thumbnail_large ) ) {
					return $video_data[0]->thumbnail_large;
				}
			}
		}

		// Image source empty: Use Theme Style "Preview image fallback image".
		if ( ! empty( $this->theme_styles['previewImageFallback']['url'] ) ) {
			return $this->theme_styles['previewImageFallback']['url'];
		}
	}

	/**
	 * Get the image by control key
	 *
	 * Similar to get_normalized_image_settings() in the image element.
	 *
	 * Might be a fix image, a dynamic data tag or external URL.
	 *
	 * @since 1.8.5
	 *
	 * @return array
	 */
	public function get_video_image_by_key( $control_key = '' ) {
		if ( empty( $control_key ) ) {
			return array();
		}

		$image = isset( $this->settings[ $control_key ] ) ? $this->settings[ $control_key ] : false;

		if ( ! $image ) {
			return array();
		}

		// STEP: Set image size.
		$image['size'] = isset( $image['size'] ) && ! empty( $image['size'] ) ? $image['size'] : BRICKS_DEFAULT_IMAGE_SIZE;

		// STEP: Image ID or URL from dynamic data.
		if ( ! empty( $image['useDynamicData'] ) ) {
			$dynamic_image = $this->render_dynamic_data_tag( $image['useDynamicData'], 'image', array( 'size' => $image['size'] ) );

			if ( ! empty( $dynamic_image[0] ) ) {
				if ( is_numeric( $dynamic_image[0] ) ) {
					// Use the image ID to populate and set $dynamic_image['url'].
					$image['id']  = $dynamic_image[0];
					$image['url'] = wp_get_attachment_image_url( $image['id'], $image['size'] );
				} else {
					$image['url'] = $dynamic_image[0];
				}
			} else {
				return array();
			}
		}

		// Set image ID.
		$image['id'] = empty( $image['id'] ) ? 0 : $image['id'];

		// Set image URL.
		if ( ! isset( $image['url'] ) ) {
			$image['url'] = ! empty( $image['id'] ) ? wp_get_attachment_image_url( $image['id'], $image['size'] ) : false;
		}

		return $image;
	}

}
