<?php

use  Elementor\Widget_Base ;
use  Elementor\Controls_Manager ;
use  Elementor\Core\Kits\Documents\Tabs\Global_Colors ;
use  Elementor\Group_Control_Typography ;
use  Elementor\Core\Kits\Documents\Tabs\Global_Typography ;
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
/**
 * Elementor Slide Music Player Widget.
 *
 * Elementor widget that displays a pre-styled section heading
 *
 * @since 1.0.0
 */
if ( !class_exists( "Widget_Slide_Music_Player_Free" ) ) {
    class Widget_Slide_Music_Player_Free extends Widget_Base
    {
        /**
         * Get widget name.
         *
         * Retrieve oEmbed widget name.
         *
         * @since 1.0.0
         * @access public
         *
         * @return string Widget name.
         */
        public function get_name()
        {
            return 'slide-music-player-free';
        }
        
        /**
         * Get widget title.
         *
         * Retrieve oEmbed widget title.
         *
         * @since 1.0.0
         * @access public
         *
         * @return string Widget title.
         */
        public function get_title()
        {
            return __( 'The Music Player - Original', 'music-player-for-elementor' );
        }
        
        /**
         * Get widget icon.
         *
         * Retrieve oEmbed widget icon.
         *
         * @since 1.0.0
         * @access public
         *
         * @return string Widget icon.
         */
        public function get_icon()
        {
            return 'eicon-play-o swp-widget-icon';
        }
        
        public function get_script_depends()
        {
            return [ 'mpfe-front' ];
        }
        
        /**
         * Get widget categories.
         *
         * Retrieve the list of categories the oEmbed widget belongs to.
         *
         * @since 1.0.0
         * @access public
         *
         * @return array Widget categories.
         */
        public function get_categories()
        {
            return [ 'slide-widgets' ];
        }
        
        /**
         * Get widget keywords.
         *
         * Retrieve the list of keywords the widget belongs to.
         *
         * @since 2.1.0
         * @access public
         *
         * @return array Widget keywords.
         */
        public function get_keywords()
        {
            return [
                'slide',
                'music player for elementor',
                'slide music player',
                'music',
                'player',
                'album',
                'mp3',
                'audio',
                'music player',
                'audio player',
                'podcast player'
            ];
        }
        
        /**
         * Register oEmbed widget controls.
         *
         * Adds different input fields to allow the user to change and customize the widget settings.
         *
         * @since 1.0.0
         * @access protected
         */
        protected function register_controls()
        {
            $this->start_controls_section( 'section_backgrounds', [
                'label' => __( 'Slide Music Player - Backgrounds', 'music-player-for-elementor' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ] );
            $this->add_responsive_control( 'show_player_left_get_pro', [
                'label'       => __( 'Show/Hide Player Cover', 'shop-maker' ),
                'type'        => \Elementor\Controls_Manager::SELECT,
                'default'     => 'yes',
                'options'     => [
                'yes' => esc_html( 'Yes - Show Cover', 'shop-maker' ),
                'no'  => esc_html( 'No - Hide Cover', 'shop-maker' ),
            ],
                'label_block' => 'true',
                'classes'     => 'mpfe-get-pro',
            ] );
            $this->add_control( 'left_bg_img', [
                'label'     => __( 'Left Background Image', 'music-player-for-elementor' ),
                'type'      => \Elementor\Controls_Manager::MEDIA,
                'default'   => [
                'url' => \Elementor\Utils::get_placeholder_image_src(),
            ],
                'dynamic'   => [
                'active' => true,
            ],
                'selectors' => [
                '{{WRAPPER}} .music_player_left' => 'background-image: url("{{URL}}"); background-size: cover;',
            ],
            ] );
            $this->add_control( 'left_bg_pos', [
                'label'       => __( 'Background Position', 'artemis-core' ),
                'type'        => Controls_Manager::SELECT,
                'options'     => [
                'center center' => esc_html__( 'Center Center', 'Background Control', 'music-player-for-elementor' ),
                'center left'   => esc_html__( 'Center Left', 'Background Control', 'music-player-for-elementor' ),
                'center right'  => esc_html__( 'Center Right', 'Background Control', 'music-player-for-elementor' ),
                'top center'    => esc_html__( 'Top Center', 'Background Control', 'music-player-for-elementor' ),
                'top left'      => esc_html__( 'Top Left', 'Background Control', 'music-player-for-elementor' ),
                'top right'     => esc_html__( 'Top Right', 'Background Control', 'music-player-for-elementor' ),
                'bottom center' => esc_html__( 'Bottom Center', 'Background Control', 'music-player-for-elementor' ),
                'bottom left'   => esc_html__( 'Bottom Left', 'Background Control', 'music-player-for-elementor' ),
                'bottom right'  => esc_html__( 'Bottom Right', 'Background Control', 'music-player-for-elementor' ),
            ],
                'default'     => 'center center',
                'label_block' => 'true',
                'selectors'   => [
                '{{WRAPPER}} .music_player_left' => 'background-position: {{VALUE}};',
            ],
            ] );
            $this->add_control( 'left_bg_overlay_get_pro', [
                'label'     => __( 'Left Background Color Overlay', 'music-player-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'global'    => [
                'default' => Global_Colors::COLOR_PRIMARY,
            ],
                'default'   => '#06062a00',
                'selectors' => [],
                'classes'   => 'mpfe-get-pro',
            ] );
            $this->add_control( 'separator_panel_bg_left', [
                'type'  => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ] );
            $this->add_control( 'right_bg_img', [
                'label'     => __( 'Right Background Image', 'music-player-for-elementor' ),
                'type'      => \Elementor\Controls_Manager::MEDIA,
                'default'   => [
                'url' => \Elementor\Utils::get_placeholder_image_src(),
            ],
                'dynamic'   => [
                'active' => true,
            ],
                'selectors' => [
                '{{WRAPPER}} .music_player_right' => 'background-image: url("{{URL}}"); background-size: cover;',
            ],
            ] );
            $this->add_control( 'right_bg_pos', [
                'label'     => __( 'Background Position', 'artemis-core' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                'center center' => esc_html__( 'Center Center', 'Background Control', 'music-player-for-elementor' ),
                'center left'   => esc_html__( 'Center Left', 'Background Control', 'music-player-for-elementor' ),
                'center right'  => esc_html__( 'Center Right', 'Background Control', 'music-player-for-elementor' ),
                'top center'    => esc_html__( 'Top Center', 'Background Control', 'music-player-for-elementor' ),
                'top left'      => esc_html__( 'Top Left', 'Background Control', 'music-player-for-elementor' ),
                'top right'     => esc_html__( 'Top Right', 'Background Control', 'music-player-for-elementor' ),
                'bottom center' => esc_html__( 'Bottom Center', 'Background Control', 'music-player-for-elementor' ),
                'bottom left'   => esc_html__( 'Bottom Left', 'Background Control', 'music-player-for-elementor' ),
                'bottom right'  => esc_html__( 'Bottom Right', 'Background Control', 'music-player-for-elementor' ),
            ],
                'default'   => 'center center',
                'selectors' => [
                '{{WRAPPER}} .music_player_right' => 'background-position: {{VALUE}};',
            ],
            ] );
            $this->add_control( 'right_bg_overlay_get_pro', [
                'label'     => __( 'Right Background Color Overlay', 'music-player-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'global'    => [
                'default' => Global_Colors::COLOR_PRIMARY,
            ],
                'default'   => '#06062a00',
                'selectors' => [],
                'classes'   => 'mpfe-get-pro',
            ] );
            $this->end_controls_section();
            $this->start_controls_section( 'section_album_details', [
                'label' => __( 'Slide Music Player - Album Details', 'music-player-for-elementor' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ] );
            $this->add_control( 'album_title', [
                'label'   => __( 'Album Title', 'music-player-for-elementor' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => [
                'active' => true,
            ],
                'default' => __( 'Last Man Standing', 'music-player-for-elementor' ),
            ] );
            $this->add_control( 'featured_text', [
                'label'   => __( 'Featured Text', 'music-player-for-elementor' ),
                'type'    => Controls_Manager::TEXT,
                'dynamic' => [
                'active' => true,
            ],
                'default' => __( 'Featured Album', 'music-player-for-elementor' ),
            ] );
            $this->add_control( 'separator_panel_1', [
                'type'  => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ] );
            $this->add_control( 'album_buy_text', [
                'label'   => __( 'Buy Text', 'music-player-for-elementor' ),
                'type'    => Controls_Manager::TEXT,
                'default' => __( 'Buy On Itunes', 'music-player-for-elementor' ),
            ] );
            $this->add_control( 'album_buy_url', [
                'label'         => __( 'Album Buy URL', 'music-player-for-elementor' ),
                'type'          => \Elementor\Controls_Manager::URL,
                'placeholder'   => __( 'https://your-link.com', 'music-player-for-elementor' ),
                'show_external' => true,
                'default'       => [
                'url'         => '#',
                'is_external' => false,
                'nofollow'    => false,
            ],
            ] );
            $this->add_control( 'button_buy_icon', [
                'label'   => __( 'Buy Icon', 'music-player-for-elementor' ),
                'type'    => \Elementor\Controls_Manager::ICONS,
                'default' => [
                'value'   => 'fab fa-apple',
                'library' => 'solid',
            ],
            ] );
            $this->add_control( 'separator_panel_2', [
                'type'  => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ] );
            $this->add_control( 'album_description_get_pro', [
                'label'       => __( 'Album Description', 'music-player-for-elementor' ),
                'type'        => \Elementor\Controls_Manager::TEXTAREA,
                'rows'        => 10,
                'classes'     => 'mpfe-get-pro',
                'default'     => __( 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do.', 'music-player-for-elementor' ),
                'placeholder' => __( 'Type your ablum description here', 'music-player-for-elementor' ),
            ] );
            $this->add_control( 'published_label_get_pro', [
                'label'       => __( 'Label for Published', 'music-player-for-elementor' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => 'true',
                'default'     => __( 'Published in', 'music-player-for-elementor' ),
                'classes'     => 'mpfe-get-pro',
            ] );
            $this->add_control( 'published_get_pro', [
                'label'       => __( 'Published Date', 'music-player-for-elementor' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => 'true',
                'default'     => __( 'February 2020', 'music-player-for-elementor' ),
                'classes'     => 'mpfe-get-pro',
            ] );
            $this->add_control( 'genre_label_get_pro', [
                'label'       => __( 'Label for Genre', 'music-player-for-elementor' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => 'true',
                'default'     => __( 'Genre', 'music-player-for-elementor' ),
                'classes'     => 'mpfe-get-pro',
            ] );
            $this->add_control( 'genre_get_pro', [
                'label'       => __( 'Genre', 'music-player-for-elementor' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => 'true',
                'default'     => __( 'Rock/Blues', 'music-player-for-elementor' ),
                'classes'     => 'mpfe-get-pro',
            ] );
            $this->add_control( 'author_label_get_pro', [
                'label'       => __( 'Label for Author', 'music-player-for-elementor' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => 'true',
                'default'     => __( 'Author', 'music-player-for-elementor' ),
                'classes'     => 'mpfe-get-pro',
            ] );
            $this->add_control( 'author_get_pro', [
                'label'       => __( 'Author', 'music-player-for-elementor' ),
                'type'        => Controls_Manager::TEXT,
                'label_block' => 'true',
                'default'     => __( 'The Band', 'music-player-for-elementor' ),
                'classes'     => 'mpfe-get-pro',
            ] );
            $this->end_controls_section();
            $this->start_controls_section( 'section_playlist', [
                'label' => __( 'Slide Music Player - Playlist', 'music-player-for-elementor' ),
                'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
            ] );
            $this->add_control( 'autoplay_get_pro', [
                'label'        => __( 'Autoplay', 'music-player-for-elementor' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'music-player-for-elementor' ),
                'label_off'    => __( 'No', 'music-player-for-elementor' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'classes'      => 'mpfe-get-pro',
                'description'  => __( 'The autoplay functionality depends on your browser autoplay policy.', 'music-player-for-elementor' ),
            ] );
            $this->add_control( 'stop_playlist_end', [
                'label'        => __( 'Stop When Playlist Ends', 'music-player-for-elementor' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'music-player-for-elementor' ),
                'label_off'    => __( 'No', 'music-player-for-elementor' ),
                'return_value' => 'yes',
                'default'      => 'no',
            ] );
            $this->add_control( 'stop_song_end', [
                'label'        => __( 'Stop When Current Song Ends', 'music-player-for-elementor' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'music-player-for-elementor' ),
                'label_off'    => __( 'No', 'music-player-for-elementor' ),
                'return_value' => 'yes',
                'default'      => 'no',
            ] );
            $this->add_control( 'purchase_icons_visible', [
                'label'        => __( 'Purchase Icons Always Visible', 'music-player-for-elementor' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'music-player-for-elementor' ),
                'label_off'    => __( 'No', 'music-player-for-elementor' ),
                'return_value' => 'yes',
                'default'      => 'no',
            ] );
            $this->add_control( 'separator_panel_3', [
                'type'  => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ] );
            $repeater = new \Elementor\Repeater();
            $repeater->add_control( 'song_name', [
                'label'       => __( 'Song Name', 'music-player-for-elementor' ),
                'type'        => Controls_Manager::TEXT,
                'default'     => __( 'Someone Like You', 'music-player-for-elementor' ),
                'label_block' => 'true',
            ] );
            $repeater->add_control( 'separator_panel_4', [
                'type'  => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ] );
            $repeater->add_control( 'audio_file', [
                'label'       => esc_html__( 'Choose Audio', 'music-player-for-elementor' ),
                'type'        => 'mpfe-audio-chooser',
                'placeholder' => esc_html__( 'URL to audio file', 'music-player-for-elementor' ),
                'description' => esc_html__( 'Choose audio file from media library.', 'music-player-for-elementor' ),
            ] );
            $repeater->add_control( 'song_custom_img_get_pro', [
                'label'       => __( 'Individual Cover Image', 'music-player-for-elementor' ),
                'type'        => \Elementor\Controls_Manager::MEDIA,
                'default'     => [],
                'separator'   => 'before',
                'selectors'   => [],
                'classes'     => 'mpfe-get-pro',
                'description' => __( 'This image will replace the player cover image when this song is playing.', 'music-player-for-elementor' ),
            ] );
            $repeater->add_control( 'separator_panel_5', [
                'type'  => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ] );
            $repeater->add_control( 'amazon_buy_url_get_pro', [
                'label'         => __( 'Amazon Buy URL', 'music-player-for-elementor' ),
                'type'          => \Elementor\Controls_Manager::URL,
                'placeholder'   => __( 'https://your-link.com', 'music-player-for-elementor' ),
                'show_external' => true,
                'default'       => [
                'url'         => '',
                'is_external' => false,
                'nofollow'    => false,
            ],
                'classes'       => 'mpfe-get-pro',
            ] );
            $repeater->add_control( 'spotify_buy_url_get_pro', [
                'label'         => __( 'Spotify Buy URL', 'music-player-for-elementor' ),
                'type'          => \Elementor\Controls_Manager::URL,
                'placeholder'   => __( 'https://your-link.com', 'music-player-for-elementor' ),
                'show_external' => true,
                'default'       => [
                'url'         => '',
                'is_external' => false,
                'nofollow'    => false,
            ],
                'classes'       => 'mpfe-get-pro',
            ] );
            $repeater->add_control( 'apple_buy_url_get_pro', [
                'label'         => __( 'Apple Buy URL', 'music-player-for-elementor' ),
                'type'          => \Elementor\Controls_Manager::URL,
                'placeholder'   => __( 'https://your-link.com', 'music-player-for-elementor' ),
                'show_external' => true,
                'default'       => [
                'url'         => '',
                'is_external' => false,
                'nofollow'    => false,
            ],
                'classes'       => 'mpfe-get-pro',
            ] );
            $repeater->add_control( 'gplay_buy_url_get_pro', [
                'label'         => __( 'YouTube Music Buy URL', 'music-player-for-elementor' ),
                'type'          => \Elementor\Controls_Manager::URL,
                'placeholder'   => __( 'https://your-link.com', 'music-player-for-elementor' ),
                'show_external' => true,
                'default'       => [
                'url'         => '',
                'is_external' => false,
                'nofollow'    => false,
            ],
                'classes'       => 'mpfe-get-pro',
            ] );
            $repeater->add_control( 'beatport_buy_url_get_pro', [
                'label'         => __( 'Beatport Buy URL', 'music-player-for-elementor' ),
                'type'          => \Elementor\Controls_Manager::URL,
                'placeholder'   => __( 'https://your-link.com', 'music-player-for-elementor' ),
                'show_external' => true,
                'default'       => [
                'url'         => '',
                'is_external' => false,
                'nofollow'    => false,
            ],
                'classes'       => 'mpfe-get-pro',
            ] );
            $repeater->add_control( 'custom_purcase_link', [
                'label'        => __( 'Custom Purchase Link', 'music-player-for-elementor' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'music-player-for-elementor' ),
                'label_off'    => __( 'No', 'music-player-for-elementor' ),
                'return_value' => 'yes',
                'default'      => 'no',
            ] );
            $repeater->add_control( 'custom_link', [
                'label'         => __( 'Custom Purchase URL', 'music-player-for-elementor' ),
                'type'          => \Elementor\Controls_Manager::URL,
                'placeholder'   => __( 'https://your-link.com', 'music-player-for-elementor' ),
                'show_external' => true,
                'default'       => [
                'url'         => '',
                'is_external' => false,
                'nofollow'    => false,
            ],
                'condition'     => [
                'custom_purcase_link' => 'yes',
            ],
            ] );
            $repeater->add_control( 'custom_icon', [
                'label'     => __( 'Custom Purchase Icon', 'music-player-for-elementor' ),
                'type'      => \Elementor\Controls_Manager::ICONS,
                'default'   => [
                'value'   => 'fas fa-arrow-down',
                'library' => 'solid',
            ],
                'condition' => [
                'custom_purcase_link' => 'yes',
            ],
            ] );
            $repeater->add_control( 'add_download_attribute', [
                'label'        => __( 'Add Download Attribute', 'music-player-for-elementor' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'music-player-for-elementor' ),
                'label_off'    => __( 'No', 'music-player-for-elementor' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'description'  => __( 'Please enable this option if this is a media download link.', 'music-player-for-elementor' ),
                'condition'    => [
                'custom_purcase_link' => 'yes',
            ],
            ] );
            $repeater->add_control( 'show_icons_on_mobile', [
                'label'       => esc_html__( 'Icons Visibility On Mobile', 'music-player-for-elementor' ),
                'type'        => \Elementor\Controls_Manager::SELECT,
                'default'     => 'hide',
                'separator'   => 'before',
                'label_block' => 'true',
                'options'     => [
                'hide' => esc_html__( 'Hide', 'music-player-for-elementor' ),
                'show' => esc_html__( 'Show', 'music-player-for-elementor' ),
            ],
                'description' => 'Decide to show or hide purchase icons on touch devices under 480px width.',
            ] );
            $repeater->add_control( 'buy_icon_pos_mobile', [
                'label'     => __( 'Icons Position (Mobile)', 'shop-maker' ),
                'type'      => \Elementor\Controls_Manager::CHOOSE,
                'options'   => [
                'left'  => [
                'title' => __( 'Left', 'shop-maker' ),
                'icon'  => 'eicon-h-align-left',
            ],
                'right' => [
                'title' => __( 'Right', 'shop-maker' ),
                'icon'  => 'eicon-h-align-right',
            ],
            ],
                'default'   => 'right',
                'condition' => [
                'show_icons_on_mobile' => 'show',
            ],
            ] );
            $repeater->add_control( 'separator_panel_7_0', [
                'type'  => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ] );
            if ( class_exists( 'woocommerce' ) ) {
                $repeater->add_control( 'product_id_get_pro', [
                    'label'       => esc_html__( 'Product ID', 'music-player-for-elementor' ),
                    'type'        => \Elementor\Controls_Manager::TEXT,
                    'description' => 'Insert the product ID that will be used to generate the add to cart button. Not sure where to get it? Click <a href="' . admin_url( 'edit.php?post_type=product' ) . '" target="_blank">here.</a> and hover any product from list to see the product ID.',
                    'default'     => '',
                    'classes'     => 'mpfe-get-pro',
                ] );
            }
            $repeater->add_control( 'separator_panel_7', [
                'type'  => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ] );
            $repeater->add_control( 'youtube_url', [
                'label'         => __( 'YouTube Promo URL', 'music-player-for-elementor' ),
                'type'          => \Elementor\Controls_Manager::URL,
                'placeholder'   => __( 'https://your-link.com', 'music-player-for-elementor' ),
                'show_external' => true,
                'default'       => [
                'url'         => '',
                'is_external' => false,
                'nofollow'    => false,
            ],
            ] );
            $repeater->add_control( 'soundcloud_url', [
                'label'         => __( 'SoundCloud URL', 'music-player-for-elementor' ),
                'type'          => \Elementor\Controls_Manager::URL,
                'placeholder'   => __( 'https://your-link.com', 'music-player-for-elementor' ),
                'show_external' => true,
                'default'       => [
                'url'         => '',
                'is_external' => false,
                'nofollow'    => false,
            ],
            ] );
            $this->add_control( 'audio_list', [
                'label'       => __( 'Audio List', 'music-player-for-elementor' ),
                'type'        => \Elementor\Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'default'     => [ [
                'song_name' => 'Someone Like You',
            ] ],
                'title_field' => '{{{ song_name }}}',
            ] );
            $this->end_controls_section();
            $this->start_controls_section( 'section_player_layout', [
                'label' => __( 'Player Layout', 'music-player-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ] );
            $this->add_control( 'cover_ar_get_pro', [
                'label'       => __( 'Album Cover Aspect Ratio', 'artemis-core' ),
                'type'        => Controls_Manager::SELECT,
                'options'     => [
                'mp_ar_tall'   => esc_html__( 'Portrait', 'music-player-for-elementor' ),
                'mp_ar_square' => esc_html__( 'Square', 'music-player-for-elementor' ),
            ],
                'label_block' => 'true',
                'default'     => 'mp_ar_tall',
                'classes'     => 'mpfe-get-pro',
            ] );
            $this->add_control( 'controls_bar_pos', [
                'label'       => __( 'Controls Bar Location', 'artemis-core' ),
                'type'        => Controls_Manager::SELECT,
                'options'     => [
                'right'        => esc_html__( 'Playlist Bottom', 'music-player-for-elementor' ),
                'playlist_top' => esc_html__( 'Playlist Top', 'music-player-for-elementor' ),
            ],
                'label_block' => 'true',
                'default'     => 'right',
            ] );
            $this->add_control( 'hide_top_info', [
                'label'        => __( 'Hide Top Album Info', 'music-player-for-elementor' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'music-player-for-elementor' ),
                'label_off'    => __( 'No', 'music-player-for-elementor' ),
                'return_value' => 'yes',
                'default'      => 'no',
            ] );
            $this->end_controls_section();
            $this->start_controls_section( 'section_style_album_info', [
                'label'     => __( 'Album Details Style', 'music-player-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                'hide_top_info' => 'yes',
            ],
            ] );
            $this->add_control( 'replace_playlist_with_info_get_pro', [
                'label'        => __( 'Replace Playlist With Album Info', 'music-player-for-elementor' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'music-player-for-elementor' ),
                'label_off'    => __( 'No', 'music-player-for-elementor' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'condition'    => [
                'hide_top_info' => 'yes',
            ],
                'classes'      => 'mpfe-get-pro',
            ] );
            $this->end_controls_section();
            $this->start_controls_section( 'section_top_middle_paddings', [
                'label' => __( 'Promo Text/Playlist Paddings', 'music-player-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ] );
            $this->add_responsive_control( 'playlist_song_lr_padding', [
                'label'      => __( 'Playlist Song Left/Right Padding', 'music-player-for-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                'px' => [
                'min'  => 0,
                'max'  => 100,
                'step' => 1,
            ],
                '%'  => [
                'min'  => 0,
                'max'  => 100,
                'step' => 1,
            ],
            ],
                'default'    => [
                'unit' => 'px',
                'size' => 0,
            ],
                'selectors'  => [
                '{{WRAPPER}} .swp_player_content' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
            ],
            ] );
            $this->add_responsive_control( 'playlist_song_tb_padding', [
                'label'      => __( 'Playlist Song Top/Bottom Padding', 'music-player-for-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                'px' => [
                'min'  => 0,
                'max'  => 100,
                'step' => 1,
            ],
                '%'  => [
                'min'  => 0,
                'max'  => 100,
                'step' => 1,
            ],
            ],
                'default'    => [
                'unit' => 'px',
                'size' => 0,
            ],
                'selectors'  => [
                '{{WRAPPER}} .swp_music_player_entry' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
            ],
            ] );
            $this->add_responsive_control( 'playlist_lr_padding', [
                'label'      => __( 'Playlist Left/Right Padding', 'music-player-for-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                'px' => [
                'min'  => 0,
                'max'  => 100,
                'step' => 1,
            ],
                '%'  => [
                'min'  => 0,
                'max'  => 100,
                'step' => 1,
            ],
            ],
                'default'    => [
                'unit' => 'px',
                'size' => 40,
            ],
                'selectors'  => [
                '{{WRAPPER}} .swp_player_top, {{WRAPPER}} .swp_music_player_entry' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
            ],
            ] );
            $this->add_responsive_control( 'playlist_tb_padding', [
                'label'      => __( 'Playlist Top/Bottom Padding', 'music-player-for-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                'px' => [
                'min'  => 0,
                'max'  => 100,
                'step' => 1,
            ],
                '%'  => [
                'min'  => 0,
                'max'  => 100,
                'step' => 1,
            ],
            ],
                'default'    => [
                'unit' => 'px',
                'size' => 0,
            ],
                'selectors'  => [
                '{{WRAPPER}} .swp_player_content' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
            ],
                'condition'  => [
                'hide_top_info' => 'yes',
            ],
            ] );
            $this->end_controls_section();
            $this->start_controls_section( 'section_colors_typography', [
                'label' => __( 'Promo Text/Playlist Colors & Typography', 'music-player-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ] );
            $this->add_control( 'title_col_get_pro', [
                'label'     => __( 'Title Color', 'music-player-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'global'    => [
                'default' => Global_Colors::COLOR_PRIMARY,
            ],
                'default'   => '',
                'selectors' => [],
                'classes'   => 'mpfe-get-pro',
            ] );
            $this->add_group_control( Group_Control_Typography::get_type(), [
                'label'    => __( 'Title Typography', 'music-player-for-elementor' ),
                'name'     => 'title_typo_get_pro',
                'global'   => [
                'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
            ],
                'selector' => '',
                'classes'  => 'mpfe-get-pro',
            ] );
            $this->add_control( 'separator_panel_title_get_pro', [
                'type'  => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ] );
            $this->add_control( 'featured_txt_col_get_pro', [
                'label'     => __( 'Featured Text Color', 'music-player-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'global'    => [
                'default' => Global_Colors::COLOR_PRIMARY,
            ],
                'default'   => '',
                'selectors' => [],
                'classes'   => 'mpfe-get-pro',
            ] );
            $this->add_group_control( Group_Control_Typography::get_type(), [
                'label'    => __( 'Featured Text Typography', 'music-player-for-elementor' ),
                'name'     => 'featured_typo_get_pro',
                'global'   => [
                'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
            ],
                'selector' => '',
                'classes'  => 'mpfe-get-pro',
            ] );
            $this->add_control( 'separator_panel_featured_get_pro', [
                'type'  => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ] );
            $this->add_control( 'text_col_get_pro', [
                'label'     => __( 'Playlist Text Color', 'music-player-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'global'    => [
                'default' => Global_Colors::COLOR_PRIMARY,
            ],
                'default'   => '',
                'selectors' => [],
                'classes'   => 'mpfe-get-pro',
            ] );
            $this->add_control( 'text_active_col_get_pro', [
                'label'     => __( 'Active Song Text Color', 'music-player-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'global'    => [
                'default' => Global_Colors::COLOR_PRIMARY,
            ],
                'default'   => '',
                'selectors' => [],
                'classes'   => 'mpfe-get-pro',
            ] );
            $this->add_group_control( Group_Control_Typography::get_type(), [
                'label'    => __( 'Playlist Text Typography', 'music-player-for-elementor' ),
                'name'     => 'txt_typo_get_pro',
                'global'   => [
                'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
            ],
                'selector' => '',
                'classes'  => 'mpfe-get-pro',
            ] );
            $this->add_control( 'purchase_col_get_pro', [
                'label'     => __( 'Purchase Icons Color', 'music-player-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'global'    => [
                'default' => Global_Colors::COLOR_PRIMARY,
            ],
                'default'   => '',
                'selectors' => [],
                'classes'   => 'mpfe-get-pro',
            ] );
            $this->add_control( 'separator_panel_text_get_pro', [
                'type'  => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ] );
            $this->add_control( 'hover_bar_col1', [
                'label'     => __( 'Hover Bar Color', 'music-player-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'global'    => [
                'default' => Global_Colors::COLOR_PRIMARY,
            ],
                'default'   => '#0a0a30',
                'selectors' => [
                '{{WRAPPER}} .swp_music_player_entry:hover' => 'background-color: {{VALUE}};',
            ],
            ] );
            $this->add_responsive_control( 'hover_radius', [
                'label'      => __( 'Hover Bar Border Radius', 'music-player-for-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range'      => [
                'px' => [
                'min'  => 0,
                'max'  => 100,
                'step' => 1,
            ],
            ],
                'default'    => [
                'unit' => 'px',
                'size' => 0,
            ],
                'selectors'  => [
                '{{WRAPPER}} .swp_music_player_entry:hover' => 'border-radius: {{SIZE}}{{UNIT}};',
            ],
            ] );
            $this->add_control( 'separator_panel_hoverbar', [
                'type'  => Controls_Manager::DIVIDER,
                'style' => 'thick',
            ] );
            $this->add_control( 'vibrant_background', [
                'label'   => __( 'Vibrant Background', 'music-player-for-elementor' ),
                'type'    => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                'classic' => [
                'title' => __( 'Classic', 'music-player-for-elementor' ),
                'icon'  => 'eicon-paint-brush',
            ],
            ],
                'default' => 'classic',
                'toggle'  => true,
            ] );
            $this->add_control( 'vibrant_color', [
                'label'     => __( 'Color', 'music-player-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'global'    => [
                'default' => Global_Colors::COLOR_PRIMARY,
            ],
                'default'   => '#fb3a64',
                'selectors' => [
                '{{WRAPPER}} .swp_player_bottom_inner i.fa-play, {{WRAPPER}} .swp_player_bottom_inner i.fa-pause, {{WRAPPER}}  .player_time_slider, {{WRAPPER}} .swp_player_button' => 'background-color: {{VALUE}};',
                '{{WRAPPER}} a.swp_player_button'                                                                                                                                   => 'border-color: {{VALUE}} !important;',
                '{{WRAPPER}} a.swp_player_button:hover'                                                                                                                             => 'background-color: transparent;',
                '{{WRAPPER}} i.before_song'                                                                                                                                         => 'color: {{VALUE}};',
            ],
                'condition' => [
                'vibrant_background' => 'classic',
            ],
            ] );
            $this->end_controls_section();
            $this->start_controls_section( 'section_controls_bar', [
                'label' => __( 'Controls Bar', 'music-player-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ] );
            $this->add_control( 'show_shuffle_repeat_get_pro', [
                'label'        => __( 'Show Shuffle Repeat', 'music-player-for-elementor' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => __( 'Yes', 'music-player-for-elementor' ),
                'label_off'    => __( 'No', 'music-player-for-elementor' ),
                'return_value' => 'yes',
                'default'      => 'no',
                'classes'      => 'mpfe-get-pro',
            ] );
            $this->add_control( 'repeat_is_for_get_pro', [
                'label'       => __( 'Repeat Icon Applies To', 'shop-maker' ),
                'type'        => \Elementor\Controls_Manager::SELECT,
                'default'     => 'playlist',
                'options'     => [
                'playlist'     => esc_html( 'Playlist', 'shop-maker' ),
                'current_song' => esc_html( 'Current Song', 'shop-maker' ),
            ],
                'label_block' => 'true',
                'classes'     => 'mpfe-get-pro',
            ] );
            $this->add_responsive_control( 'controls_padding', [
                'label'      => __( 'Controls Bar Left/Right Padding', 'music-player-for-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range'      => [
                'px' => [
                'min'  => 0,
                'max'  => 200,
                'step' => 1,
            ],
                '%'  => [
                'min'  => 0,
                'max'  => 100,
                'step' => 1,
            ],
            ],
                'default'    => [
                'unit' => 'px',
                'size' => 40,
            ],
                'selectors'  => [
                '{{WRAPPER}} .swp_current_play' => 'left: {{SIZE}}{{UNIT}};',
                '{{WRAPPER}} .swp_timeline'     => 'right: {{SIZE}}{{UNIT}};',
            ],
            ] );
            $this->add_control( 'controls_bar_bg', [
                'label'     => __( 'Controls Bar Background', 'music-player-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'global'    => [
                'default' => Global_Colors::COLOR_PRIMARY,
            ],
                'default'   => '#12143d00',
                'selectors' => [
                '{{WRAPPER}} .swp_player_bottom' => 'background-color: {{VALUE}};',
            ],
            ] );
            $this->add_control( 'controls_bar_txt_col_primary_get_pro', [
                'label'     => __( 'Controls Bar Primary Text Color', 'music-player-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'global'    => [
                'default' => Global_Colors::COLOR_PRIMARY,
            ],
                'default'   => '',
                'selectors' => [],
                'classes'   => 'mpfe-get-pro',
            ] );
            $this->add_control( 'controls_bar_txt_col_secondary_get_pro', [
                'label'     => __( 'Controls Bar Secondary Text Color', 'music-player-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'global'    => [
                'default' => Global_Colors::COLOR_PRIMARY,
            ],
                'default'   => '',
                'selectors' => [],
                'classes'   => 'mpfe-get-pro',
            ] );
            $this->add_control( 'next_prev_col_get_pro', [
                'label'       => __( 'Next/Prev Buttons Color', 'music-player-for-elementor' ),
                'type'        => Controls_Manager::COLOR,
                'global'      => [
                'default' => Global_Colors::COLOR_PRIMARY,
            ],
                'default'     => '',
                'selectors'   => [],
                'description' => __( 'Color used for Next/Prev/Repeat/Shuffle buttons', 'music-player-for-elementor' ),
                'classes'     => 'mpfe-get-pro',
            ] );
            $this->add_control( 'next_prev_hover_col_get_pro', [
                'label'       => __( 'Next/Prev Buttons Hover Color', 'music-player-for-elementor' ),
                'type'        => Controls_Manager::COLOR,
                'global'      => [
                'default' => Global_Colors::COLOR_PRIMARY,
            ],
                'default'     => '',
                'selectors'   => [],
                'description' => __( 'Color used for Next/Prev/Repeat/Shuffle buttons on hover state', 'music-player-for-elementor' ),
                'classes'     => 'mpfe-get-pro',
            ] );
            $this->add_control( 'play_pause_col_get_pro', [
                'label'     => __( 'Play/Pause Buttons Color', 'music-player-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'global'    => [
                'default' => Global_Colors::COLOR_PRIMARY,
            ],
                'default'   => '',
                'selectors' => [],
                'classes'   => 'mpfe-get-pro',
            ] );
            $this->add_control( 'shuffle_repeat_active_col_get_pro', [
                'label'       => __( 'Shuffle/Repeat Buttons Active Color', 'music-player-for-elementor' ),
                'type'        => Controls_Manager::COLOR,
                'global'      => [
                'default' => Global_Colors::COLOR_PRIMARY,
            ],
                'default'     => '',
                'selectors'   => [],
                'description' => __( 'Color used for Repeat/Shuffle buttons when they are active', 'music-player-for-elementor' ),
                'classes'     => 'mpfe-get-pro',
            ] );
            $this->add_control( 'time_slider_base_col_get_pro', [
                'label'     => __( 'Time Slider Base Color', 'music-player-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'global'    => [
                'default' => Global_Colors::COLOR_PRIMARY,
            ],
                'default'   => '',
                'selectors' => [],
                'classes'   => 'mpfe-get-pro',
            ] );
            $this->add_control( 'time_slider_base_opacity_get_pro', [
                'label'      => __( 'Time Slider Base Opacity', 'music-player-for-elementor' ),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => [ '%' ],
                'range'      => [
                '%' => [
                'min'  => 0,
                'max'  => 100,
                'step' => 1,
            ],
            ],
                'default'    => [
                'unit' => '%',
                'size' => 10,
            ],
                'selectors'  => [],
                'classes'    => 'mpfe-get-pro',
            ] );
            $this->end_controls_section();
        }
        
        /**
         * Render oEmbed widget output on the frontend.
         *
         * Written in PHP and used to generate the final HTML.
         *
         * @since 1.0.0
         * @access protected
         */
        protected function render()
        {
            $settings = $this->get_settings_for_display();
            $autoplay = "no";
            $replace_playlist_with_info = false;
            $cover_ar = "mp_ar_tall";
            $show_shuffle_repeat = "no";
            $repeat_mode = "unset";
            $album_cover = ( isset( $settings['left_bg_img'] ) ? $settings['left_bg_img']['url'] : "" );
            $stop_playlist_end = ( "yes" == $settings['stop_playlist_end'] ? "yes" : "no" );
            $stop_song_end = ( "yes" == $settings['stop_song_end'] ? "yes" : "no" );
            $buy_target = ( $settings['album_buy_url']['is_external'] ? '_blank' : '_self' );
            $hide_top_info = ( "yes" == $settings['hide_top_info'] ? true : false );
            $show_info_on_top = ( "playlist_top" == $settings['controls_bar_pos'] ? false : true );
            $player_content_css = "swp_player_content controls_on_" . esc_attr( $settings['controls_bar_pos'] );
            if ( $hide_top_info ) {
                $player_content_css .= " no_top_info_bar";
            }
            if ( $replace_playlist_with_info ) {
                $player_content_css .= " display_none";
            }
            if ( "yes" == $settings['purchase_icons_visible'] ) {
                $player_content_css .= ' purchase_icons_always_visible';
            }
            $player_id = uniqid( "mpfe_" );
            ?>

		<div class="swp_music_player clearfix" id="<?php 
            echo  esc_attr( $player_id ) ;
            ?>" data-autoplay="<?php 
            echo  esc_attr( $autoplay ) ;
            ?>" data-playmode="repeat" data-stopplaylistend="<?php 
            echo  esc_attr( $stop_playlist_end ) ;
            ?>" data-stopsongend="<?php 
            echo  esc_attr( $stop_song_end ) ;
            ?>" data-repeatmode="<?php 
            echo  esc_attr( $repeat_mode ) ;
            ?>" data-playerimg="<?php 
            echo  esc_attr( $album_cover ) ;
            ?>">
			<div class="music_player_left <?php 
            echo  esc_attr( $cover_ar ) ;
            ?>">
				<div class="album_left_overlay lc_swp_overlay"></div>
			</div>

			<div class="music_player_right <?php 
            echo  esc_attr( $cover_ar ) ;
            ?>">
				<div class="album_right_overlay lc_swp_overlay"></div>


				<?php 
            if ( "playlist_top" == $settings['controls_bar_pos'] ) {
                $this->controls_bar_html( "playlist_top", $show_shuffle_repeat );
            }
            ?>

				<?php 
            if ( !$hide_top_info && $show_info_on_top ) {
                $this->info_bar_html( $settings, $buy_target );
            }
            ?>

				<div class="<?php 
            echo  esc_attr( $player_content_css ) ;
            ?>">
					<?php 
            if ( $settings['audio_list'] ) {
                foreach ( $settings['audio_list'] as $song ) {
                    $this->single_track_html( $song );
                }
            }
            ?>
				</div>
				<?php 
            if ( $replace_playlist_with_info ) {
                $this->extended_album_info_html( $settings );
            }
            ?>	

				<?php 
            if ( !$hide_top_info && !$show_info_on_top ) {
                $this->info_bar_html( $settings, $buy_target );
            }
            ?>

				<?php 
            if ( "right" == $settings['controls_bar_pos'] ) {
                $this->controls_bar_html( "right", $show_shuffle_repeat );
            }
            ?>
			</div>

			<?php 
            if ( "bottom" == $settings['controls_bar_pos'] ) {
                $this->controls_bar_html( "bottom", $show_shuffle_repeat );
            }
            ?>
		</div>

	<?php 
        }
        
        private function info_bar_html( $settings, $buy_target )
        {
            ?>
		<div class="swp_player_top">
			<div class="player_top_content">
				<?php 
            
            if ( strlen( $settings['featured_text'] ) ) {
                ?>
					<div class="album_featured_text">
						<?php 
                echo  esc_html( $settings['featured_text'] ) ;
                ?>
					</div>
				<?php 
            }
            
            ?>

				<div class="swp_player_title">
					<?php 
            echo  esc_html( $settings['album_title'] ) ;
            ?>
				</div>
			</div>

			<?php 
            
            if ( strlen( $settings['album_buy_url']['url'] ) ) {
                ?>
			<div class="swp_player_button_container">
				<a class="mpfe_button mpfe_button_fill swp_player_button clearfix" href="<?php 
                echo  $settings['album_buy_url']['url'] ;
                ?>" target="<?php 
                echo  esc_attr( $buy_target ) ;
                ?>">

					<?php 
                \Elementor\Icons_Manager::render_icon( $settings['button_buy_icon'], [
                    'aria-hidden' => 'true',
                ] );
                echo  esc_html( $settings['album_buy_text'] ) ;
                ?>
				</a>
			</div>
			<?php 
            }
            
            ?>
		</div>
		<?php 
        }
        
        private function single_track_html( $song )
        {
            $buy_icon_pos_mobile = ( isset( $song['buy_icon_pos_mobile'] ) ? $song['buy_icon_pos_mobile'] : "" );
            $song_custom_img = ( mpfe_fs()->can_use_premium_code__premium_only() && isset( $song['song_custom_img'] ) ? $song['song_custom_img']['url'] : "" );
            ?>

		<div class="swp_music_player_entry clearfix" data-mediafile="<?php 
            echo  esc_attr( $song['audio_file'] ) ;
            ?>" data-trackimg="<?php 
            echo  esc_attr( $song_custom_img ) ;
            ?>">
			<span class="swp_song_details player_entry_left">
				<span class="play_icon">
					<i class="fas fa-play before_song"></i>
				</span>
				<span class="player_song_name transition3">
					<?php 
            echo  esc_html( $song['song_name'] ) ;
            ?>
				</span>
			</span>

			<span class="entry_duration mpfe_fix_lh"></span>		

			<span class="song_buy_icons transition3 mobile_visibility_<?php 
            echo  esc_attr( $song['show_icons_on_mobile'] ) ;
            ?> icons_pos_mobile_<?php 
            echo  esc_attr( $buy_icon_pos_mobile ) ;
            ?>">
				<?php 
            ?>

				<?php 
            
            if ( strlen( $song['youtube_url']['url'] ) ) {
                ?>
					<?php 
                $link_target = ( "on" == $song['youtube_url']['is_external'] ? "_blank" : "_self" );
                ?>
					<a target="<?php 
                echo  esc_attr( $link_target ) ;
                ?>" class="buy_song_icon" href="<?php 
                echo  esc_url( $song['youtube_url']['url'] ) ;
                ?>">
						<i class="fab fa-youtube"></i>
					</a>
				<?php 
            }
            
            ?>
				<?php 
            
            if ( strlen( $song['soundcloud_url']['url'] ) ) {
                ?>
					<?php 
                $link_target = ( "on" == $song['soundcloud_url']['is_external'] ? "_blank" : "_self" );
                ?>
					<a target="<?php 
                echo  esc_attr( $link_target ) ;
                ?>" class="buy_song_icon" href="<?php 
                echo  esc_url( $song['soundcloud_url']['url'] ) ;
                ?>">
						<i class="fab fa-soundcloud"></i>
					</a>
				<?php 
            }
            
            ?>
				<?php 
            
            if ( "yes" == $song['custom_purcase_link'] && strlen( $song['custom_link']['url'] ) ) {
                ?>
					<?php 
                $download_attribute = ( "yes" == $song['add_download_attribute'] ? "download" : "" );
                ?>
					<?php 
                $link_target = ( "on" == $song['custom_link']['is_external'] ? "_blank" : "_self" );
                ?>
					<a target="<?php 
                echo  esc_attr( $link_target ) ;
                ?>" class="buy_song_icon" href="<?php 
                echo  esc_url( $song['custom_link']['url'] ) ;
                ?>" <?php 
                echo  esc_attr( $download_attribute ) ;
                ?>>
						<?php 
                \Elementor\Icons_Manager::render_icon( $song['custom_icon'], [
                    'aria-hidden' => 'true',
                ] );
                ?>
					</a>
				<?php 
            }
            
            ?>
			</span>
		</div>

		<?php 
        }
        
        private function controls_bar_html( $position, $show_shuffle_repeat )
        {
            $progressbar_on_top = ( "playlist_top" == $position ? false : true );
            $show_sr = ( "yes" == $show_shuffle_repeat ? true : false );
            if ( "bottom" == $position ) {
                ?>
			<div class="clearfix"></div>
		<?php 
            }
            ?>

		<div class="swp_player_bottom show_on_<?php 
            echo  esc_attr( $position ) ;
            ?>">
			<div class="swp_player_bottom_inner">
				<?php 
            if ( $progressbar_on_top ) {
                ?>
				<div class="smc_player_progress_bar">
					<div class="player_time_slider_base"></div>
					<div class="player_time_slider"></div>
				</div>
				<?php 
            }
            ?>

				<div class="swp_current_play">
					<div class="before_current_song_name"><?php 
            echo  esc_html__( "Playing", "music-player-for-elementor" ) ;
            ?></div>
					<div class="current_song_name"></div>
				</div>

				<div class="swp_player_controls">
					<?php 
            if ( $show_sr ) {
                ?>
					<i class="fas fa-redo playback-repeat"></i>
					<?php 
            }
            ?>
					<a href="#!" class="mpfe-sr-helper">
						<i class="fas fa-step-backward"></i>
						<span class="visually-hidden"><?php 
            echo  esc_html__( "Previous Song", "music-player-for-elementor" ) ;
            ?></span>
					</a>
					<a href="#!" class="mpfe-sr-helper">
						<i class="fas fa-play player_play"></i>
						<span class="visually-hidden"><?php 
            echo  esc_html__( "Play", "music-player-for-elementor" ) ;
            ?></span>
					</a>
					<a href="#!" class="mpfe-sr-helper">
						<i class="fas fa-pause display_none"></i>
						<span class="visually-hidden"><?php 
            echo  esc_html__( "Pause", "music-player-for-elementor" ) ;
            ?></span>
					</a>
					<a href="#!" class="mpfe-sr-helper">
						<i class="fas fa-step-forward"></i>
						<span class="visually-hidden"><?php 
            echo  esc_html__( "Next Song", "music-player-for-elementor" ) ;
            ?></span>
					</a>
					<?php 
            if ( $show_sr ) {
                ?>
					<i class="fas fa-random playback-shuffle"></i>
					<?php 
            }
            ?>
				</div>

				<div class="swp_timeline">
					<span class="song_current_progress"></span>
					<span class="player_duration_sep">&#47;</span>
					<span class="song_duration"></span>
				</div>

				<?php 
            if ( !$progressbar_on_top ) {
                ?>
				<div class="smc_player_progress_bar show_on_bottom">
					<div class="player_time_slider_base"></div>
					<div class="player_time_slider"></div>
				</div>
				<?php 
            }
            ?>
			</div>
		</div>
		<?php 
        }
        
        private function extended_album_info_html( $settings )
        {
            $buy_target = ( $settings['album_buy_url']['is_external'] ? '_blank' : '_self' );
            ?>

		<div class="smc_extended_album_info cb_on_<?php 
            echo  esc_attr( $settings['controls_bar_pos'] ) ;
            ?>">
			<?php 
            ?>			
		</div>
	<?php 
        }
    
    }
}