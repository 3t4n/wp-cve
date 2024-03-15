<?php

use  Elementor\Widget_Base ;
use  Elementor\Controls_Manager ;
use  Elementor\Core\Kits\Documents\Tabs\Global_Colors ;
use  Elementor\Group_Control_Typography ;
use  Elementor\Core\Kits\Documents\Tabs\Global_Typography ;
use  Elementor\Modules\DynamicTags\Module as TagsModule ;
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
class Widget_Slide_Compact_Player extends Widget_Base
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
        return 'slide-compact-player';
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
        return __( 'The Music Player - Compact', 'music-player-for-elementor' );
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
            'podcast player',
            'compact player'
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
            'label' => __( 'Cover Image', 'music-player-for-elementor' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );
        $this->add_control( 'show_player_left_get_pro', [
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
            'label'     => __( 'Player Cover Image', 'music-player-for-elementor' ),
            'type'      => \Elementor\Controls_Manager::MEDIA,
            'default'   => [
            'url' => \Elementor\Utils::get_placeholder_image_src(),
        ],
            'selectors' => [
            '{{WRAPPER}} .swp-compact-cover-container' => 'background-image: url("{{URL}}"); background-size: cover;',
        ],
        ] );
        $this->add_control( 'left_bg_pos', [
            'label'       => __( 'Cover Background Position', 'artemis-core' ),
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
            '{{WRAPPER}} .swp-compact-cover-container' => 'background-position: {{VALUE}};',
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
        $this->add_responsive_control( 'cover_width', [
            'label'      => __( 'Cover Width', 'music-player-for-elementor' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%' ],
            'range'      => [
            'px' => [
            'min'  => 0,
            'max'  => 400,
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
            'size' => 200,
        ],
            'selectors'  => [
            '{{WRAPPER}} .swp-compact-cover'       => 'width: {{SIZE}}{{UNIT}};',
            '{{WRAPPER}} .swp-compact-player-info' => 'width: calc( 100% - {{SIZE}}{{UNIT}} );',
        ],
        ] );
        $this->end_controls_section();
        $this->start_controls_section( 'section_album_promo', [
            'label' => __( 'Album Promo Links', 'music-player-for-elementor' ),
            'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
        ] );
        $repeater_promo = new \Elementor\Repeater();
        $repeater_promo->add_control( 'provider_name', [
            'label'   => __( 'Provider Name', 'music-player-for-elementor' ),
            'type'    => Controls_Manager::TEXT,
            'default' => __( 'Spotify', 'music-player-for-elementor' ),
        ] );
        $repeater_promo->add_control( 'album_buy_url', [
            'label'         => __( 'Promo URL', 'music-player-for-elementor' ),
            'type'          => \Elementor\Controls_Manager::URL,
            'placeholder'   => __( 'https://www.spotify.com/', 'music-player-for-elementor' ),
            'show_external' => true,
            'default'       => [
            'url'         => '',
            'is_external' => false,
            'nofollow'    => false,
        ],
            'description'   => __( 'Leave it emtpy to disable the icon link.', 'music-player-for-elementor' ),
        ] );
        $repeater_promo->add_control( 'button_buy_icon', [
            'label'   => __( 'Promo Icon', 'music-player-for-elementor' ),
            'type'    => \Elementor\Controls_Manager::ICONS,
            'default' => [
            'value'   => 'fab fa-spotify',
            'library' => 'solid',
        ],
        ] );
        $this->add_control( 'promo_links', [
            'label'       => __( 'Promo Links', 'music-player-for-elementor' ),
            'type'        => \Elementor\Controls_Manager::REPEATER,
            'fields'      => $repeater_promo->get_controls(),
            'default'     => [],
            'title_field' => '{{{ provider_name }}}',
        ] );
        $this->end_controls_section();
        $this->start_controls_section( 'section_playlist', [
            'label' => __( 'Playlist', 'music-player-for-elementor' ),
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
        $this->add_control( 'show_playlist', [
            'label'   => __( 'Show Playlist', 'shop-maker' ),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'default' => 'popup',
            'options' => [
            'popup' => esc_html( 'In a popup', 'shop-maker' ),
            'under' => esc_html( 'Under the player', 'shop-maker' ),
        ],
        ] );
        $this->add_control( 'add_album_to_song_name', [
            'label'        => __( 'Add albums/series to the song title', 'music-player-for-elementor' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => __( 'Yes', 'music-player-for-elementor' ),
            'label_off'    => __( 'No', 'music-player-for-elementor' ),
            'return_value' => 'yes',
            'default'      => 'no',
        ] );
        $this->add_control( 'separator_panel_1', [
            'type'  => Controls_Manager::DIVIDER,
            'style' => 'thick',
        ] );
        $repeater = new \Elementor\Repeater();
        $repeater->add_control( 'song_name', [
            'label'       => __( 'Song Name', 'music-player-for-elementor' ),
            'type'        => Controls_Manager::TEXT,
            'default'     => __( 'Swinging Christmas', 'music-player-for-elementor' ),
            'label_block' => 'true',
            'dynamic'     => [
            'active' => true,
        ],
        ] );
        $repeater->add_control( 'album_name', [
            'label'       => __( 'Album/Series', 'music-player-for-elementor' ),
            'type'        => Controls_Manager::TEXT,
            'default'     => __( 'Pear Tree Restaurant', 'music-player-for-elementor' ),
            'label_block' => 'true',
            'dynamic'     => [
            'active'     => true,
            'categories' => [ TagsModule::MEDIA_CATEGORY ],
        ],
        ] );
        $repeater->add_control( 'separator_panel_2', [
            'type'  => Controls_Manager::DIVIDER,
            'style' => 'thick',
        ] );
        $repeater->add_control( 'audio_file', [
            'label'       => esc_html__( 'Choose Audio', 'music-player-for-elementor' ),
            'type'        => 'mpfe-audio-chooser',
            'placeholder' => esc_html__( 'URL to audio file', 'music-player-for-elementor' ),
            'description' => esc_html__( 'Choose audio file from media library.', 'music-player-for-elementor' ),
            'dynamic'     => [
            'active' => true,
        ],
        ] );
        $repeater->add_control( 'song_description', [
            'label'       => esc_html__( 'Song Description', 'music-player-for-elementor' ),
            'type'        => \Elementor\Controls_Manager::WYSIWYG,
            'default'     => '',
            'placeholder' => esc_html__( 'Type your description here', 'music-player-for-elementor' ),
        ] );
        $repeater->add_control( 'separator_panel_3', [
            'type'  => Controls_Manager::DIVIDER,
            'style' => 'thick',
        ] );
        $repeater->add_control( 'amazon_buy_url_get_pro', [
            'label'         => __( 'Amazon Promo URL', 'music-player-for-elementor' ),
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
            'label'         => __( 'Spotify Promo URL', 'music-player-for-elementor' ),
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
            'label'         => __( 'Apple Promo URL', 'music-player-for-elementor' ),
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
            'label'         => __( 'YouTube Music Promo URL', 'music-player-for-elementor' ),
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
            'label'         => __( 'Beatport Promo URL', 'music-player-for-elementor' ),
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
        $repeater->add_control( 'separator_panel_4', [
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
        $repeater->add_control( 'separator_panel_5', [
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
        $this->start_controls_section( 'section_player_general', [
            'label' => __( 'General', 'music-player-for-elementor' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );
        $this->add_responsive_control( 'player_padding', [
            'label'      => esc_html__( 'Player Padding', 'music-player-for-elementor' ),
            'type'       => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px' ],
            'selectors'  => [
            '{{WRAPPER}} .swp-compact-player-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        ],
        ] );
        $this->add_responsive_control( 'player_border_radius_get_pro', [
            'label'      => __( 'Rounded Corners', 'music-player-for-elementor' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%' ],
            'range'      => [
            'px' => [
            'min'  => 0,
            'max'  => 50,
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
            'size' => 3,
        ],
            'selectors'  => [],
            'classes'    => 'mpfe-get-pro',
        ] );
        $this->add_control( 'trigger_responsive_view_get_pro', [
            'label'       => __( 'Trigger Responsive View On', 'shop-maker' ),
            'type'        => \Elementor\Controls_Manager::SELECT,
            'default'     => 'mobile',
            'options'     => [
            'mobile' => esc_html( 'Mobile Devices < 480px', 'shop-maker' ),
            'tablet' => esc_html( 'Tablet Devices < 768px', 'shop-maker' ),
        ],
            'label_block' => 'false',
            'classes'     => 'mpfe-get-pro',
        ] );
        $this->end_controls_section();
        $this->start_controls_section( 'section_player_bg', [
            'label' => __( 'Player Background', 'music-player-for-elementor' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'right_bg_col_free', [
            'label'       => __( 'Background Color', 'music-player-for-elementor' ),
            'type'        => Controls_Manager::COLOR,
            'global'      => [
            'default' => Global_Colors::COLOR_PRIMARY,
        ],
            'default'     => '#ED4C15',
            'selectors'   => [
            '{{WRAPPER}} .album_right_overlay' => 'background-color: {{VALUE}};',
        ],
            'description' => __( 'Background image and gradient backgrounds are available for the PRO version only.', 'music-player-for-elementor' ),
        ] );
        $this->end_controls_section();
        $this->start_controls_section( 'section_player_top_info', [
            'label' => __( 'Player Top Info', 'music-player-for-elementor' ),
            'tab'   => Controls_Manager::TAB_STYLE,
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
        $this->add_control( 'separator_panel_6', [
            'type'  => Controls_Manager::DIVIDER,
            'style' => 'thick',
        ] );
        $this->add_group_control( Group_Control_Typography::get_type(), [
            'label'    => __( 'Subtitle Typography', 'music-player-for-elementor' ),
            'name'     => 'subtitle_typo_get_pro',
            'global'   => [
            'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
        ],
            'selector' => '',
            'classes'  => 'mpfe-get-pro',
        ] );
        $this->add_control( 'subtitle_col_get_pro', [
            'label'     => __( 'Subtitle Color', 'music-player-for-elementor' ),
            'type'      => Controls_Manager::COLOR,
            'global'    => [
            'default' => Global_Colors::COLOR_PRIMARY,
        ],
            'default'   => '',
            'selectors' => [],
            'classes'   => 'mpfe-get-pro',
        ] );
        $this->add_control( 'separator_panel_7', [
            'type'  => Controls_Manager::DIVIDER,
            'style' => 'thick',
        ] );
        $this->add_responsive_control( 'bottom_margin', [
            'label'      => __( 'Bottom Margin', 'music-player-for-elementor' ),
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
            'size' => 25,
        ],
            'selectors'  => [
            '{{WRAPPER}} .compact-info-top' => 'margin-bottom: {{SIZE}}{{UNIT}};',
        ],
        ] );
        $this->end_controls_section();
        $this->start_controls_section( 'section_progress_bar', [
            'label' => __( 'Progress Bar', 'music-player-for-elementor' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'cb_bg_col_get_pro', [
            'label'     => __( 'Time Slider Base Background Color', 'music-player-for-elementor' ),
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
        $this->add_control( 'separator_panel_8', [
            'type'  => Controls_Manager::DIVIDER,
            'style' => 'thick',
        ] );
        $this->add_control( 'progress_bar_bg_get_pro', [
            'label'   => __( 'Progress Bar Background Type', 'music-player-for-elementor' ),
            'type'    => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
            'classic'  => [
            'title' => __( 'Classic', 'music-player-for-elementor' ),
            'icon'  => 'eicon-paint-brush',
        ],
            'gradient' => [
            'title' => __( 'Gradient', 'music-player-for-elementor' ),
            'icon'  => 'eicon-barcode',
        ],
        ],
            'default' => 'classic',
            'toggle'  => true,
            'classes' => 'mpfe-get-pro',
        ] );
        $this->add_control( 'cb_progress_col_get_pro', [
            'label'     => __( 'Progress Background Color', 'music-player-for-elementor' ),
            'type'      => Controls_Manager::COLOR,
            'global'    => [
            'default' => Global_Colors::COLOR_PRIMARY,
        ],
            'default'   => '',
            'selectors' => [],
            'condition' => [
            'progress_bar_bg_get_pro' => 'classic',
        ],
            'classes'   => 'mpfe-get-pro',
        ] );
        $this->add_control( 'separator_panel_9', [
            'type'  => Controls_Manager::DIVIDER,
            'style' => 'thick',
        ] );
        $this->add_responsive_control( 'pb_bottom_margin', [
            'label'      => __( 'Bottom Margin', 'music-player-for-elementor' ),
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
            'size' => 15,
        ],
            'selectors'  => [
            '{{WRAPPER}} .smc_player_progress_bar.compact-progress-bar' => 'margin-bottom: {{SIZE}}{{UNIT}};',
        ],
        ] );
        $this->end_controls_section();
        $this->start_controls_section( 'section_promo_links_style', [
            'label' => __( 'Promo Icon Links', 'music-player-for-elementor' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'pl_col', [
            'label'     => __( 'Icons Color', 'music-player-for-elementor' ),
            'type'      => Controls_Manager::COLOR,
            'global'    => [
            'default' => Global_Colors::COLOR_PRIMARY,
        ],
            'default'   => '#fff',
            'selectors' => [
            '{{WRAPPER}} a.compact-promo-link' => 'color: {{VALUE}};',
        ],
        ] );
        $this->add_control( 'pl_hov_col', [
            'label'     => __( 'Icons Hover Color', 'music-player-for-elementor' ),
            'type'      => Controls_Manager::COLOR,
            'global'    => [
            'default' => Global_Colors::COLOR_PRIMARY,
        ],
            'default'   => '',
            'selectors' => [
            '{{WRAPPER}} a.compact-promo-link:hover' => 'color: {{VALUE}};',
        ],
        ] );
        $this->add_control( 'separator_panel_pm_1', [
            'type'  => Controls_Manager::DIVIDER,
            'style' => 'thick',
        ] );
        $this->add_responsive_control( 'pl_size', [
            'label'      => __( 'Icons Size', 'music-player-for-elementor' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [
            'px' => [
            'min'  => 0,
            'max'  => 30,
            'step' => 1,
        ],
        ],
            'default'    => [
            'unit' => 'px',
            'size' => 16,
        ],
            'selectors'  => [
            '{{WRAPPER}} a.compact-promo-link i' => 'font-size: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
        ],
        ] );
        $this->add_control( 'separator_panel_pm_2', [
            'type'  => Controls_Manager::DIVIDER,
            'style' => 'thick',
        ] );
        $this->add_responsive_control( 'pl_left_margin', [
            'label'      => __( 'Icons Block Left Margin', 'music-player-for-elementor' ),
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
            'size' => 30,
        ],
            'selectors'  => [
            '{{WRAPPER}} .compact-promo-links' => 'margin-left: {{SIZE}}{{UNIT}};',
        ],
        ] );
        $this->add_responsive_control( 'pl_icon_gap', [
            'label'      => __( 'Icons Gap', 'music-player-for-elementor' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [
            'px' => [
            'min'  => 0,
            'max'  => 50,
            'step' => 1,
        ],
        ],
            'default'    => [
            'unit' => 'px',
            'size' => 16,
        ],
            'selectors'  => [
            '{{WRAPPER}} .compact-promo-links i' => 'margin-right: {{SIZE}}{{UNIT}};',
        ],
        ] );
        $this->add_control( 'separator_panel_pm_3', [
            'type'  => Controls_Manager::DIVIDER,
            'style' => 'thick',
        ] );
        $this->add_responsive_control( 'pl_bottom_margin', [
            'label'       => __( 'Promo Icons Bottom Margin', 'music-player-for-elementor' ),
            'type'        => Controls_Manager::SLIDER,
            'size_units'  => [ 'px' ],
            'range'       => [
            'px' => [
            'min'  => 0,
            'max'  => 100,
            'step' => 1,
        ],
        ],
            'default'     => [
            'unit' => 'px',
            'size' => 0,
        ],
            'selectors'   => [
            '{{WRAPPER}} .compact-promo-links' => 'margin-bottom: {{SIZE}}{{UNIT}};',
        ],
            'description' => __( 'Use a non zero value only for mobile devices under 480px. Option is meant to be used in mobile view. Please enable Elementor responsive mode to test this option.', 'music-player-for-elementor' ),
        ] );
        $this->end_controls_section();
        $this->start_controls_section( 'section_controls_bar', [
            'label' => __( 'Timer & Controls Bar', 'music-player-for-elementor' ),
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
        $this->add_control( 'separator_panel_10', [
            'type'  => Controls_Manager::DIVIDER,
            'style' => 'thick',
        ] );
        $this->add_group_control( Group_Control_Typography::get_type(), [
            'label'    => __( 'Timer Typography', 'music-player-for-elementor' ),
            'name'     => 'timer_typo',
            'global'   => [
            'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
        ],
            'selector' => '{{WRAPPER}} .compact-timeline',
        ] );
        $this->add_control( 'current_time_col', [
            'label'     => __( 'Current Time Color', 'music-player-for-elementor' ),
            'type'      => Controls_Manager::COLOR,
            'global'    => [
            'default' => Global_Colors::COLOR_PRIMARY,
        ],
            'default'   => '',
            'selectors' => [
            '{{WRAPPER}} .song_current_progress' => 'color: {{VALUE}};',
        ],
        ] );
        $this->add_control( 'full_time_col', [
            'label'     => __( 'Full Time Color', 'music-player-for-elementor' ),
            'type'      => Controls_Manager::COLOR,
            'global'    => [
            'default' => Global_Colors::COLOR_PRIMARY,
        ],
            'default'   => '',
            'selectors' => [
            '{{WRAPPER}} .player_duration_sep, {{WRAPPER}} .song_duration' => 'color: {{VALUE}};',
        ],
        ] );
        $this->add_responsive_control( 'timer_bottom_margin', [
            'label'       => __( 'Timer Bottom Margin', 'music-player-for-elementor' ),
            'type'        => Controls_Manager::SLIDER,
            'size_units'  => [ 'px' ],
            'range'       => [
            'px' => [
            'min'  => 0,
            'max'  => 100,
            'step' => 1,
        ],
        ],
            'default'     => [
            'unit' => 'px',
            'size' => 0,
        ],
            'selectors'   => [
            '{{WRAPPER}} .compact-timeline' => 'margin-bottom: {{SIZE}}{{UNIT}};',
        ],
            'description' => __( 'Use a non zero value only for mobile devices under 480px. Option is meant to be used in mobile view. Please enable Elementor responsive mode to test this option.', 'music-player-for-elementor' ),
        ] );
        $this->add_control( 'separator_panel_11', [
            'type'  => Controls_Manager::DIVIDER,
            'style' => 'thick',
        ] );
        $this->add_control( 'play_pause_col_get_pro', [
            'label'     => __( 'Play/Pause Color', 'music-player-for-elementor' ),
            'type'      => Controls_Manager::COLOR,
            'global'    => [
            'default' => Global_Colors::COLOR_PRIMARY,
        ],
            'default'   => '',
            'selectors' => [],
            'classes'   => 'mpfe-get-pro',
        ] );
        $this->add_control( 'play_pause_bg_style_get_pro', [
            'label'   => __( 'Play/Pause Background Type', 'music-player-for-elementor' ),
            'type'    => \Elementor\Controls_Manager::CHOOSE,
            'options' => [
            'classic'  => [
            'title' => __( 'Classic', 'music-player-for-elementor' ),
            'icon'  => 'eicon-paint-brush',
        ],
            'gradient' => [
            'title' => __( 'Gradient', 'music-player-for-elementor' ),
            'icon'  => 'eicon-barcode',
        ],
        ],
            'default' => 'classic',
            'toggle'  => true,
            'classes' => 'mpfe-get-pro',
        ] );
        $this->add_control( 'play_pause_bg_col_get_pro', [
            'label'     => __( 'Play/Pause Background Color', 'music-player-for-elementor' ),
            'type'      => Controls_Manager::COLOR,
            'global'    => [
            'default' => Global_Colors::COLOR_PRIMARY,
        ],
            'default'   => '',
            'selectors' => [],
            'condition' => [
            'play_pause_bg_style_get_pro' => 'classic',
        ],
            'classes'   => 'mpfe-get-pro',
        ] );
        $this->add_control( 'separator_panel_12', [
            'type'  => Controls_Manager::DIVIDER,
            'style' => 'thick',
        ] );
        $this->add_control( 'nprs_col_get_pro', [
            'label'     => __( 'Next/Prev/Repeat/Shuffle Color', 'music-player-for-elementor' ),
            'type'      => Controls_Manager::COLOR,
            'global'    => [
            'default' => Global_Colors::COLOR_PRIMARY,
        ],
            'default'   => '',
            'selectors' => [],
            'classes'   => 'mpfe-get-pro',
        ] );
        $this->add_control( 'nprs_hov_col_get_pro', [
            'label'     => __( 'Next/Prev/Repeat/Shuffle Hover Color', 'music-player-for-elementor' ),
            'type'      => Controls_Manager::COLOR,
            'global'    => [
            'default' => Global_Colors::COLOR_PRIMARY,
        ],
            'default'   => '',
            'selectors' => [],
            'classes'   => 'mpfe-get-pro',
        ] );
        $this->add_control( 'separator_panel_13', [
            'type'  => Controls_Manager::DIVIDER,
            'style' => 'thick',
        ] );
        $this->add_control( 'show_playback_speed_get_pro', [
            'label'        => __( 'Show Playback Speed', 'music-player-for-elementor' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => __( 'Yes', 'music-player-for-elementor' ),
            'label_off'    => __( 'No', 'music-player-for-elementor' ),
            'return_value' => 'yes',
            'default'      => 'no',
            'classes'      => 'mpfe-get-pro',
        ] );
        $this->add_control( 'separator_panel_13_list', [
            'type'  => Controls_Manager::DIVIDER,
            'style' => 'thick',
        ] );
        $this->add_control( 'list_icon_col_get_pro', [
            'label'     => __( 'List Icon Color', 'music-player-for-elementor' ),
            'type'      => Controls_Manager::COLOR,
            'global'    => [
            'default' => Global_Colors::COLOR_PRIMARY,
        ],
            'default'   => '',
            'selectors' => [],
            'classes'   => 'mpfe-get-pro',
        ] );
        $this->add_control( 'list_icon_hov_col_get_pro', [
            'label'     => __( 'List Icon Hover Color', 'music-player-for-elementor' ),
            'type'      => Controls_Manager::COLOR,
            'global'    => [
            'default' => Global_Colors::COLOR_PRIMARY,
        ],
            'default'   => '',
            'selectors' => [],
            'classes'   => 'mpfe-get-pro',
        ] );
        $this->add_control( 'separator_panel_14_volume', [
            'type'  => Controls_Manager::DIVIDER,
            'style' => 'thick',
        ] );
        $this->add_control( 'show_volume_control_get_pro', [
            'label'        => __( 'Show Volume Control', 'music-player-for-elementor' ),
            'type'         => \Elementor\Controls_Manager::SWITCHER,
            'label_on'     => __( 'Yes', 'music-player-for-elementor' ),
            'label_off'    => __( 'No', 'music-player-for-elementor' ),
            'return_value' => 'yes',
            'default'      => 'no',
            'classes'      => 'mpfe-get-pro',
        ] );
        $this->end_controls_section();
        $this->start_controls_section( 'section_controls_playlist', [
            'label' => __( 'Playlist', 'music-player-for-elementor' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'playlist_overlay_color', [
            'label'     => __( 'Background Color', 'music-player-for-elementor' ),
            'type'      => Controls_Manager::COLOR,
            'global'    => [
            'default' => Global_Colors::COLOR_PRIMARY,
        ],
            'default'   => '#ff7600',
            'selectors' => [
            '{{WRAPPER}} .swp-compact-playlist.list-visible'       => 'background-color: {{VALUE}};',
            '{{WRAPPER}} .swp-compact-playlist.swp-playlist-under' => 'background-color: {{VALUE}};',
        ],
        ] );
        $this->add_control( 'separator_panel_14_0', [
            'type'  => Controls_Manager::DIVIDER,
            'style' => 'thick',
        ] );
        $this->add_responsive_control( 'playlist_top_pos', [
            'label'      => __( 'Playlist Top Distance', 'music-player-for-elementor' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ '%', 'px' ],
            'range'      => [
            'px' => [
            'min'  => 0,
            'max'  => 100,
            'step' => 1,
        ],
        ],
            'default'    => [
            'unit' => '%',
            'size' => 35,
        ],
            'selectors'  => [
            '{{WRAPPER}} .swp-compact-playlist-inner' => 'top: {{SIZE}}{{UNIT}};',
        ],
            'condition'  => [
            'show_playlist' => 'popup',
        ],
        ] );
        $this->add_responsive_control( 'player_playlist_gap', [
            'label'      => __( 'Player To Playlist Distance', 'music-player-for-elementor' ),
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
            '{{WRAPPER}} .swp-compact-player' => 'margin-bottom: {{SIZE}}{{UNIT}};',
        ],
            'condition'  => [
            'show_playlist' => 'under',
        ],
        ] );
        $this->add_control( 'separator_panel_14_1', [
            'type'  => Controls_Manager::DIVIDER,
            'style' => 'thick',
        ] );
        $this->add_control( 'playlist_container_width', [
            'label'      => __( 'Playlist Container Width', 'music-player-for-elementor' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [
            'px' => [
            'min'  => 0,
            'max'  => 1600,
            'step' => 20,
        ],
        ],
            'default'    => [
            'unit' => 'px',
            'size' => 1000,
        ],
            'selectors'  => [
            '{{WRAPPER}} .swp-compact-playlist-inner-container' => 'width: {{SIZE}}{{UNIT}};',
        ],
            'condition'  => [
            'show_playlist' => 'popup',
        ],
        ] );
        $this->add_responsive_control( 'cplaylist_lr_padding', [
            'label'      => __( 'Playlist Left/Right Padding', 'music-player-for-elementor' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [
            'px' => [
            'min'  => 0,
            'max'  => 50,
            'step' => 1,
        ],
        ],
            'default'    => [
            'unit' => 'px',
            'size' => 40,
        ],
            'selectors'  => [
            '{{WRAPPER}} .swp_music_player_entry' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
        ],
        ] );
        $this->add_responsive_control( 'cplaylist_tb_padding_under', [
            'label'      => __( 'Playlist Top/Bottom Padding', 'music-player-for-elementor' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [
            'px' => [
            'min'  => 0,
            'max'  => 50,
            'step' => 1,
        ],
        ],
            'default'    => [
            'unit' => 'px',
            'size' => 00,
        ],
            'selectors'  => [
            '{{WRAPPER}} .swp-compact-playlist-inner' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}};',
        ],
            'condition'  => [
            'show_playlist' => 'under',
        ],
        ] );
        $this->add_control( 'separator_panel_14', [
            'type'  => Controls_Manager::DIVIDER,
            'style' => 'thick',
        ] );
        $this->add_control( 'play_icon_size_get_pro', [
            'label'      => __( 'Play Icon Size', 'music-player-for-elementor' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [
            'px' => [
            'min'  => 0,
            'max'  => 50,
            'step' => 1,
        ],
        ],
            'default'    => [
            'unit' => 'px',
            'size' => 9,
        ],
            'selectors'  => [],
            'classes'    => 'mpfe-get-pro',
        ] );
        $this->add_control( 'play_icon_col_get_pro', [
            'label'     => __( 'Play Icon Color', 'music-player-for-elementor' ),
            'type'      => Controls_Manager::COLOR,
            'global'    => [
            'default' => Global_Colors::COLOR_PRIMARY,
        ],
            'default'   => '',
            'selectors' => [],
            'classes'   => 'mpfe-get-pro',
        ] );
        $this->add_control( 'separator_panel_15', [
            'type'  => Controls_Manager::DIVIDER,
            'style' => 'thick',
        ] );
        $this->start_controls_tabs( 'track_style_tabs' );
        $this->start_controls_tab( 'track_style_normal_tab', [
            'label' => __( 'Normal', 'shop-maker' ),
        ] );
        $this->add_control( 'track_col_get_pro', [
            'label'     => __( 'Track Color', 'music-player-for-elementor' ),
            'type'      => Controls_Manager::COLOR,
            'global'    => [
            'default' => Global_Colors::COLOR_PRIMARY,
        ],
            'default'   => '',
            'selectors' => [],
            'classes'   => 'mpfe-get-pro',
        ] );
        $this->add_control( 'purchase_icons_color_get_pro', [
            'label'     => __( 'Purchase Icons Color', 'music-player-for-elementor' ),
            'type'      => Controls_Manager::COLOR,
            'global'    => [
            'default' => Global_Colors::COLOR_PRIMARY,
        ],
            'default'   => '',
            'selectors' => [],
            'classes'   => 'mpfe-get-pro',
        ] );
        $this->end_controls_tab();
        $this->start_controls_tab( 'track_style_hover_tab', [
            'label' => __( 'Hover', 'shop-maker' ),
        ] );
        $this->add_control( 'track_col_hov_get_pro', [
            'label'     => __( 'Track Color', 'music-player-for-elementor' ),
            'type'      => Controls_Manager::COLOR,
            'global'    => [
            'default' => Global_Colors::COLOR_PRIMARY,
        ],
            'default'   => '',
            'selectors' => [],
            'classes'   => 'mpfe-get-pro',
        ] );
        $this->add_control( 'purchase_icons_color_hov_get_pro', [
            'label'     => __( 'Purchase Icons Color', 'music-player-for-elementor' ),
            'type'      => Controls_Manager::COLOR,
            'global'    => [
            'default' => Global_Colors::COLOR_PRIMARY,
        ],
            'default'   => '',
            'selectors' => [],
            'classes'   => 'mpfe-get-pro',
        ] );
        $this->end_controls_tab();
        $this->start_controls_tab( 'track_style_active_tab', [
            'label' => __( 'Active', 'shop-maker' ),
        ] );
        $this->add_control( 'track_col_active_get_pro', [
            'label'     => __( 'Track Color', 'music-player-for-elementor' ),
            'type'      => Controls_Manager::COLOR,
            'global'    => [
            'default' => Global_Colors::COLOR_PRIMARY,
        ],
            'default'   => '',
            'selectors' => [],
            'classes'   => 'mpfe-get-pro',
        ] );
        $this->add_control( 'purchase_icons_color_active_get_pro', [
            'label'     => __( 'Purchase Icons Color', 'music-player-for-elementor' ),
            'type'      => Controls_Manager::COLOR,
            'global'    => [
            'default' => Global_Colors::COLOR_PRIMARY,
        ],
            'default'   => '',
            'selectors' => [],
            'classes'   => 'mpfe-get-pro',
        ] );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control( 'separator_panel_16', [
            'type'  => Controls_Manager::DIVIDER,
            'style' => 'thick',
        ] );
        $this->add_group_control( Group_Control_Typography::get_type(), [
            'label'    => __( 'Song Name Typography', 'music-player-for-elementor' ),
            'name'     => 'song_name_typo_get_pro',
            'global'   => [
            'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
        ],
            'selector' => '',
        ] );
        $this->add_control( 'separator_panel_17', [
            'type'  => Controls_Manager::DIVIDER,
            'style' => 'thick',
        ] );
        $this->add_control( 'purchase_icon_size', [
            'label'      => __( 'Purchase Icons Size', 'music-player-for-elementor' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [
            'px' => [
            'min'  => 0,
            'max'  => 50,
            'step' => 1,
        ],
        ],
            'default'    => [
            'unit' => 'px',
            'size' => 9,
        ],
            'selectors'  => [
            '{{WRAPPER}} .buy_song_icon i' => 'font-size: {{SIZE}}{{UNIT}};',
        ],
        ] );
        $this->add_responsive_control( 'purchase_icon_gap', [
            'label'      => __( 'Purchase Icons Gap', 'music-player-for-elementor' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px' ],
            'range'      => [
            'px' => [
            'min'  => 0,
            'max'  => 50,
            'step' => 1,
        ],
        ],
            'default'    => [
            'unit' => 'px',
            'size' => 9,
        ],
            'selectors'  => [
            '{{WRAPPER}} .song_buy_icons a' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
        ],
        ] );
        $this->add_control( 'separator_panel_18', [
            'type'  => Controls_Manager::DIVIDER,
            'style' => 'thick',
        ] );
        $this->add_group_control( Group_Control_Typography::get_type(), [
            'label'    => __( 'Song Duration Typography', 'music-player-for-elementor' ),
            'name'     => 'song_duration_typo_get_pro',
            'global'   => [
            'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
        ],
            'selector' => '',
        ] );
        $this->add_control( 'separator_panel_19', [
            'type'      => Controls_Manager::DIVIDER,
            'style'     => 'thick',
            'condition' => [
            'show_playlist' => 'popup',
        ],
        ] );
        $this->add_responsive_control( 'close_top', [
            'label'      => __( 'Close Button Top', 'music-player-for-elementor' ),
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
            'size' => 40,
        ],
            'selectors'  => [
            '{{WRAPPER}} .compact-close-playlist-container' => 'top: {{SIZE}}{{UNIT}};',
        ],
            'condition'  => [
            'show_playlist' => 'popup',
        ],
        ] );
        $this->add_responsive_control( 'close_right', [
            'label'      => __( 'Close Button Right', 'music-player-for-elementor' ),
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
            'size' => 70,
        ],
            'selectors'  => [
            '{{WRAPPER}} .compact-close-playlist-container' => 'right: {{SIZE}}{{UNIT}};',
        ],
            'condition'  => [
            'show_playlist' => 'popup',
        ],
        ] );
        $this->add_control( 'close_col_get_pro', [
            'label'     => __( 'Close Button Color', 'music-player-for-elementor' ),
            'type'      => Controls_Manager::COLOR,
            'global'    => [
            'default' => Global_Colors::COLOR_PRIMARY,
        ],
            'default'   => '#ffffff',
            'selectors' => [],
            'classes'   => 'mpfe-get-pro',
            'condition' => [
            'show_playlist' => 'popup',
        ],
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
        $show_shuffle_repeat = "no";
        $repeat_mode = "unset";
        $show_playback_speed = "no";
        $show_next_prev = "yes";
        $show_list_icon = "yes";
        $show_volume_control = "no";
        $stop_playlist_end = ( "yes" == $settings['stop_playlist_end'] ? "yes" : "no" );
        $stop_song_end = ( "yes" == $settings['stop_song_end'] ? "yes" : "no" );
        $playlist_popup = ( "popup" == $settings['show_playlist'] ? true : false );
        $player_id = uniqid( "mpfe_" );
        ?>

		<div class="swp_music_player compact-player" id="<?php 
        echo  esc_attr( $player_id ) ;
        ?>" data-autoplay="<?php 
        echo  esc_attr( $autoplay ) ;
        ?>" data-playmode="repeat" data-stopplaylistend="<?php 
        echo  esc_attr( $stop_playlist_end ) ;
        ?>" data-stopsongend="<?php 
        echo  esc_attr( $stop_song_end ) ;
        ?>" data-repeatmode="<?php 
        echo  esc_attr( $repeat_mode ) ;
        ?>">
			<div class="swp-compact-player">
				<div class="swp-compact-cover">
					<div class="swp-compact-cover-container"></div>
					<div class="compact-cover-overlay album_left_overlay lc_swp_overlay"></div>
				</div>

				<div class="swp-compact-player-info">
					<div class="compact-info-overlay album_right_overlay lc_swp_overlay"></div>
					<div class="compact-info-content">
						<div class="compact-info-top clearfix">
							<div class="compact-song-details">
								<div class="current_song_name compact-song-name"></div>
								<div class="current_album_name compact-album-name"></div>
							</div>
							<div class="compact-play-pause">
								<span class="compact-play-container ">
									<i class="fas fa-play player_play compact_play compact-playpause"></i>
								</span>
								<span class="compact-pause-container">
									<i class="fas fa-pause compact_pause compact-playpause display_none"></i>
								</span>
							</div>
						</div>

						<div class="smc_player_progress_bar compact-progress-bar">
							<div class="player_time_slider_base compact-player-slider-base"></div>
							<div class="player_time_slider compact-player-slider"></div>
						</div>

						<div class="compact-info-bottom clearfix">
							<div class="compact-timeline">
								<span class="song_current_progress compact_current_progress">0:00</span>
								<span class="player_duration_sep compact_duration_sep">&#47;</span>
								<span class="song_duration compact_song_duration">0:00</span>
							</div>

							<?php 
        
        if ( $settings['promo_links'] ) {
            ?>
							<div class="compact-promo-links">
								<?php 
            foreach ( $settings['promo_links'] as $promo ) {
                
                if ( strlen( $promo['album_buy_url']['url'] ) ) {
                    $buy_target = ( $promo['album_buy_url']['is_external'] ? '_blank' : '_self' );
                    ?>
										<div class="compact-promo-single">
											<a href="<?php 
                    echo  esc_url( $promo['album_buy_url']['url'] ) ;
                    ?>" target="<?php 
                    echo  esc_attr( $buy_target ) ;
                    ?>" class="compact-promo-link">
												<?php 
                    \Elementor\Icons_Manager::render_icon( $promo['button_buy_icon'], [
                        'aria-hidden' => 'true',
                    ] );
                    ?>
											</a>
										</div>
									<?php 
                }
                
                ?>
								<?php 
            }
            ?>
							</div>
							<?php 
        }
        
        ?>

							<div class="compact-controls">
								<?php 
        if ( "yes" == $show_volume_control ) {
            ?>
								<span class="mpfe-compact-volume">
									<i class="fa fa-volume-up mpfe-toggle-vol"></i>
									<div class="mpfe-range-vol">
										<input class="mpfe-input-range" orient="vertical" type="range" step="0.1" value="1" min="0" max="1">
									</div>
								</span>
								<?php 
        }
        ?>

								<?php 
        if ( "yes" == $show_shuffle_repeat ) {
            ?>
								<i class="fas fa-redo playback-repeat mpfe-compact-bottom-player-icon"></i>
								<?php 
        }
        ?>
								<?php 
        if ( $show_next_prev ) {
            ?>
								<i class="fas fa-step-backward mpfe-compact-bottom-player-icon"></i>
								<i class="fas fa-step-forward mpfe-compact-bottom-player-icon"></i>
								<?php 
        }
        ?>
								<?php 
        if ( "yes" == $show_shuffle_repeat ) {
            ?>
								<i class="fas fa-random playback-shuffle mpfe-compact-bottom-player-icon"></i>
								<?php 
        }
        ?>
								<?php 
        if ( "yes" == $show_playback_speed ) {
            ?>
								<span class="compact-playback-speed">
									<span class="ps-val">1x</span>
									<ul class="compact-ps-opts">
										<li class="compact-ps-opt">0.8x</li>
										<li class="compact-ps-opt">1x</li>
										<li class="compact-ps-opt">1.2x</li>
										<li class="compact-ps-opt">1.5x</li>
										<li class="compact-ps-opt">2x</li>
									</ul>
								</span>
								<?php 
        }
        ?>



								<?php 
        if ( $show_list_icon && $playlist_popup ) {
            ?>
								<i class="fas fa-list-ul mpfe-compact-list"></i>
								<?php 
        }
        ?>
							</div>
						</div>
					</div>
				</div>
			</div>

			<?php 
        $playlist_css = ( $playlist_popup ? 'swp-compact-playlist swp-playlist-popup' : 'swp-compact-playlist swp-playlist-under' );
        $playlist_inner_css = ( $playlist_popup ? 'swp-compact-playlist-inner-container swp-playlist-popup-inner' : 'swp-compact-playlist-inner-container swp-playlist-under-inner-container' );
        $album_to_song_name = ( "yes" == $settings['add_album_to_song_name'] ? true : false );
        ?>

			<div class="<?php 
        echo  esc_attr( $playlist_css ) ;
        ?>">
				<?php 
        if ( $playlist_popup ) {
            ?>
				<div class="compact-close-playlist-container">
				</div>
				<?php 
        }
        ?>
				<div class="<?php 
        echo  esc_attr( $playlist_inner_css ) ;
        ?>">
					<div class="swp-compact-playlist-inner">
					<?php 
        if ( $settings['audio_list'] ) {
            foreach ( $settings['audio_list'] as $song ) {
                $this->single_track_html( $song, $album_to_song_name );
            }
        }
        ?>
					</div>
				</div>
			</div>
		</div>

		<?php 
    }
    
    private function single_track_html( $song, $album_to_song_name = false )
    {
        ?>
		<div class="swp_music_player_entry compact-player-entry clearfix" data-mediafile="<?php 
        echo  esc_attr( $song['audio_file'] ) ;
        ?>">
			<span class="swp_song_details player_entry_left">
				<span class="play_icon">
					<i class="fas fa-play before_song compact_bs"></i>
				</span>
				<span class="player_song_name transition3" data-albumname="<?php 
        echo  esc_attr( $song['album_name'] ) ;
        ?>">
					<?php 
        echo  esc_html( $song['song_name'] ) ;
        ?>
				</span>
				<?php 
        
        if ( $album_to_song_name ) {
            ?>
				<span class="player_album_name">
					<?php 
            echo  ' - ' . esc_html( $song['album_name'] ) ;
            ?>
				</span>					
				<?php 
        }
        
        ?>
			</span>

			<span class="entry_duration mpfe_fix_lh"></span>		

			<span class="song_buy_icons compact_buy_icons transition3 clearfix mobile_visibility_<?php 
        echo  esc_attr( $song['show_icons_on_mobile'] ) ;
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

			<?php 
        
        if ( strlen( $song['song_description'] ) ) {
            ?>
				<div class="compact_song_desription">
					<?php 
            echo  wp_kses_post( $song['song_description'] ) ;
            ?>
				</div>
			<?php 
        }
        
        ?>
		</div>

		<?php 
    }

}