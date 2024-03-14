<?php


class Smartlib_Theme_Integration
{

    public $theme_config;

    function __construct()
    {
        $this->theme_config = apply_filters('smartlib_get_theme_config', array());


        /*Customizer*/

        add_action('customize_register', array($this, 'register_sections'));

        /*add theme kirki option*/

        add_filter('kirki/controls', array($this, 'smartlib_add_theme_options'));

        $this->social_button_scripts();


    }

    function register_sections($wp_customize)
    {

        $this->add_sections($wp_customize);

    }

    function add_sections($wp_customize)
    {

        $wp_customize->add_section('smartlib_social_links', array(
            'title' => __('Social media & RSS links', 'smartlib'),
            'priority' => 100,
            'panel' => 'smartlib_panel_general_settings',
        ));

        $wp_customize->add_section('smartlib_social_buttons', array(
            'title' => __('Social media buttons', 'smartlib'),
            'priority' => 100,
            'panel' => 'smartlib_panel_general_settings',
        ));

    }


    function smartlib_add_theme_options($controls)
    {

        /*social*/

        $controls[] = array(
            'type' => 'radio',
            'mode' => 'buttonset',
            'setting' => 'smartlib_social_facebook_button_default',
            'label' => __('Facebook Like Button', 'smartlib'),
            'section' => 'smartlib_social_buttons',
            'default' => '1',
            'priority' => 1,
            'choices' => array(
                '0' => __('Off', 'smartlib'),
                '1' => __('On', 'smartlib')
            )
        );
        $controls[] = array(
            'type' => 'radio',
            'mode' => 'buttonset',
            'setting' => 'smartlib_social_gplus_button_default',
            'label' => __('Google Plus Button', 'smartlib'),
            'section' => 'smartlib_social_buttons',
            'default' => '1',
            'priority' => 2,
            'choices' => array(
                '0' => __('Off', 'smartlib'),
                '1' => __('On', 'smartlib')
            )
        );

        $controls[] = array(
            'type' => 'radio',
            'mode' => 'buttonset',
            'setting' => 'smartlib_social_pinterest_button_default',
            'label' => __('Pin It Button', 'smartlib'),
            'section' => 'smartlib_social_buttons',
            'default' => '1',
            'priority' => 2,
            'choices' => array(
                '0' => __('Off', 'smartlib'),
                '1' => __('On', 'smartlib')
            )
        );

        $controls[] = array(
            'type' => 'radio',
            'mode' => 'buttonset',
            'setting' => 'smartlib_social_twitter_button_default',
            'label' => __('Tweet Button', 'smartlib'),
            'section' => 'smartlib_social_buttons',
            'default' => '1',
            'priority' => 2,
            'choices' => array(
                '0' => __('Off', 'smartlib'),
                '1' => __('On', 'smartlib')
            )
        );

        $controls = $this->generate_social_options($controls);

        return $controls;
    }

    protected function generate_social_options($controls)
    {


        $config_media_options = $this->theme_config->supported_social_media;
        $i = 1;
        foreach ($config_media_options as $key => $row) {
            $i++;
            $controls[] = array(
                'type' => 'text',
                'setting' => 'smartlib_socialmedia_link_' . $key,
                'label' => $row,
                'section' => 'smartlib_social_links',

                'priority' => $i);

        };

        return $controls;
    }

    /**
     * Enqueue and register CSS files here.
     */
    public function social_button_scripts()
    {
        /*register awesome css*/

        $facebook = get_theme_mod('smartlib_social_facebook_button_default', '1');
        $pinterest = get_theme_mod('smartlib_social_pinterest_button_default', '1');
        $twitter = get_theme_mod('smartlib_social_twitter_button_default', '1');

        if ($facebook == '1') {
            add_action('wp_footer', array($this, 'smartlib_facebook_scripts'));
        }

        if ($pinterest == '1') {
            add_action('wp_footer', array($this, 'smartlib_pinterest_scripts'));
        }

        if ($twitter == '1') {
            add_action('wp_footer', array($this, 'smartlib_twitter_scripts'));
        }


    }

    /**
     * Display facebook script
     */
    function smartlib_facebook_scripts()
    {
        ?>
        <div id="fb-root"></div>
        <script>(function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3&appId=252087941485995";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));</script>
    <?php
    }


    function smartlib_pinterest_scripts()
    {
        ?>
        <script type="text/javascript" async src="//assets.pinterest.com/js/pinit.js"></script>
    <?php
    }

    function smartlib_twitter_scripts(){
        ?>
        <script>!function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
                if (!d.getElementById(id)) {
                    js = d.createElement(s);
                    js.id = id;
                    js.src = p + '://platform.twitter.com/widgets.js';
                    fjs.parentNode.insertBefore(js, fjs);
                }
            }(document, 'script', 'twitter-wjs');</script>
<?php
    }

}



function smartlib_add_custom_metaboxes(){

    /**
     * prefix of meta keys (optional)
     * Use underscore (_) at the beginning to make keys hidden
     * Alt.: You also can make prefix empty to disable it
     */
    // Better has an underscore as last sign
    $prefix = 'stool_';
    // 1st meta box
    $meta_boxes[] = array(
        // Meta box id, UNIQUE per meta box. Optional since 4.1.5
        'id'         => 'standard',
        // Meta box title - Will appear at the drag and drop handle bar. Required.
        'title'      => __( 'Additional Fields', 'bootframe' ),
        // Post types, accept custom post types as well - DEFAULT is 'post'. Can be array (multiple post types) or string (1 post type). Optional.
        'post_types' => array(  'smartlib_testimonial' ),
        // Where the meta box appear: normal (default), advanced, side. Optional.
        'context'    => 'normal',
        // Order of meta box: high (default), low. Optional.
        'priority'   => 'high',
        // Auto save: true, false (default). Optional.
        'autosave'   => true,
        // List of meta fields
        // Show this meta box for posts matched below conditions


        'fields'     => array(
            // TEXT
            array(
                // Field name - Will be used as label
                'name'  => __( 'Client Name', 'bootframe' ),
                // Field ID, i.e. the meta key
                'id'    => "{$prefix}client_name",
                // Field description (optional)
                'desc'  => __( 'Add client name', 'bootframe' ),
                'type'  => 'text',
                'size' => 100




            ),

            array(
                // Field name - Will be used as label
                'name'  => __( 'Company Name', 'bootframe' ),
                // Field ID, i.e. the meta key
                'id'    => "{$prefix}company_name",
                'type'  => 'text',
                'size' => 100

            ),

        ),

    );

    return $meta_boxes;
}

add_filter( 'rwmb_meta_boxes', 'smartlib_add_custom_metaboxes' );
