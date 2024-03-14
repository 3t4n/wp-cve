<?php 
/**
 * Expand Divi Dashboard
 * Setup dashboard sections and fields
 *
 * @package  ExpandDiviDashboard
 */

// exit when accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class ExpandDiviDashboard {
    private $expand_divi_sections;
    private $expand_divi_fields;

    function __construct() {
      // the sections array
      $this->expand_divi_sections = [
        'general' => [
            'title' => esc_html__( 'General Settings', 'expand-divi' )
        ],
        'post' => [
            'title' => esc_html__( 'Post Layout', 'expand-divi' )
        ],
        'share' => [
            'title' => esc_html__( 'Social Share', 'expand-divi' )
        ],
        'follow' => [
            'title' => esc_html__( 'Social Follow', 'expand-divi' )
        ]
      ];

      // the fields array
      $this->expand_divi_fields = [
        'enable_preloader' => [
            'title'    => esc_html__( 'Enable Pre-loader', 'expand-divi' ),
            'type'     => 'select',
            'section'  => 'general', 
            'default'  => 0,
            'children' => [ esc_html__( 'Disabled', 'expand-divi' ), esc_html__( 'Enabled', 'expand-divi' ) ] 
        ],
        'preloader_img_url' => [
            'title'    => esc_html__( 'Pre-loader Image URL', 'expand-divi' ),
            'type'     => 'url',
            'section'  => 'general', 
            'default'  => '',
            'sanitize' => 'url'
         ], 
        'enable_fontawesome' => [
            'title'    => esc_html__( 'Enable Fontawesome', 'expand-divi' ),
            'type'     => 'select',
            'section'  => 'general', 
            'default'  => 0,
            'children' => [ esc_html__( 'Disabled', 'expand-divi' ), esc_html__( 'Enabled', 'expand-divi' ) ] 
        ],
        'enable_lightbox_everywhere' => [
            'title'    => esc_html__( 'Enable Lightbox for posts and pages', 'expand-divi' ),
            'type'     => 'select',
            'section'  => 'general',
            'default'  => 0,
            'children' => [ esc_html__( 'Disabled', 'expand-divi' ), esc_html__( 'Enabled', 'expand-divi' ) ] 
        ],
        'coming_soon' => [
            'title'    => esc_html__( 'Coming Soon Mode', 'expand-divi' ),
            'type'     => 'select',
            'section'  => 'general', 
            'default'  => 0,
            'children' => [ esc_html__( 'Disabled', 'expand-divi' ), esc_html__( 'Enabled', 'expand-divi' ) ] 
        ],
        'coming_soon_page' => [
            'title'    => esc_html__( 'Coming Soon Page', 'expand-divi' ),
            'type'     => 'pages',
            'section'  => 'general', 
            'default'  => ''
        ],
        'login_page_url' => [
           'title'    => esc_html__( 'Login Page Logo URL', 'expand-divi' ),
           'type'     => 'url',
           'section'  => 'general', 
           'default'  => ''
        ],
        'login_page_img_url' => [
            'title'    => esc_html__( 'Login Page Log Image URL', 'expand-divi' ),
            'type'     => 'url',
            'section'  => 'general', 
            'default'  => ''
        ],
        'tos_to_register_page' => [
            'title'    => esc_html__( 'Enable Terms Of Use on Register Page', 'expand-divi' ),
            'type'     => 'select',
            'section'  => 'general', 
            'default'  => 0,
            'children' => [ esc_html__( 'Disabled', 'expand-divi' ), esc_html__( 'Enabled', 'expand-divi' ) ]
        ],
        'enable_archive_blog_styles' => [
            'title'    => esc_html__( 'Enable Archive Blog Styles', 'expand-divi' ),
            'type'     => 'select',
            'section'  => 'post', 
            'default'  => 0,
            'children' => [ esc_html__( 'Disabled', 'expand-divi' ), esc_html__( 'Grid', 'expand-divi' ), esc_html__( 'List', 'expand-divi' ) ]
        ],         
        'enable_author_box' => [
            'title'    => esc_html__( 'Enable Author Box', 'expand-divi' ),
            'type'     => 'select',
            'section'  => 'post', 
            'default'  => 0,
            'children' => [ esc_html__( 'Disabled', 'expand-divi' ), esc_html__( 'Enabled', 'expand-divi' ) ] 
        ],
        'enable_single_post_pagination' => [
            'title'    => esc_html__( 'Enable Single Post Pagination', 'expand-divi' ),
            'type'     => 'select',
            'section'  => 'post', 
            'default'  => 0,
            'children' => [ esc_html__( 'Disabled', 'expand-divi' ), esc_html__( 'Enabled', 'expand-divi' ) ] 
        ],
        'enable_related_posts' => [
            'title'    => esc_html__( 'Enable Related Posts', 'expand-divi' ),
            'type'     => 'select',
            'section'  => 'post',
            'default'  => 0,
            'children' => [ esc_html__( 'Disabled', 'expand-divi' ), esc_html__( 'Enabled', 'expand-divi' ) ] 
        ],
        'related_posts_filter' => [
            'title'    => esc_html__( 'Show Related Posts By:', 'expand-divi' ),
            'type'     => 'select',
            'section'  => 'post',
            'default'  => 0,
            'children' => [ esc_html__( 'Tags', 'expand-divi' ), esc_html__( 'Categories', 'expand-divi' ) ]
        ],
        'related_posts_title' => [
            'title'    => esc_html__( 'Related Posts Title', 'expand-divi' ),
            'type'     => 'text',
            'section'  => 'post',
            'default'  => esc_html__( 'You Might Also Like:', 'expand-divi' ),
            'sanitize' => 'full'
        ],
        'enable_post_tags' => [
            'title'    => esc_html__( 'Enable Post Tags', 'expand-divi' ),
            'type'     => 'select',
            'section'  => 'post', 
            'default'  => 0,
            'children' => [ esc_html__( 'Disabled', 'expand-divi' ), esc_html__( 'Above Content', 'expand-divi' ), esc_html__( 'Below Content' )]
        ],
        'remove_sidebar' => [
            'title'    => esc_html__( 'Remove Sidebar', 'expand-divi' ),
            'type'     => 'select',
            'section'  => 'post', 
            'default'  => 0,
            'children' => [ esc_html__( 'Disabled', 'expand-divi' ), esc_html__( 'Globally', 'expand-divi' ), esc_html__( 'Posts Only', 'expand-divi' ), esc_html__( 'Archive Pages Only', 'expand-divi' ) ]
        ],
        'share_icons' => [
            'title'    => esc_html__( 'Enable Social Share Icons', 'expand-divi' ),
            'type'     => 'select',
            'section'  => 'share', 
            'default'  => 0,
            'children' => [ esc_html__( 'Disabled', 'expand-divi' ), esc_html__( 'Enabled', 'expand-divi' ) ] 
         ],
        'share_icons_text' => [
            'title'    => esc_html__( 'Share Text', 'expand-divi' ),
            'type'     => 'text',
            'section'  => 'share', 
            'default'  => esc_html__( 'Share:', 'expand-divi' ),
            'sanitize' => 'full'
         ],
        'share_icons_shortcode' => [
            'title'    => esc_html__( 'Share Icons Shortcode', 'expand-divi' ),
            'type'     => 'disabledtext',
            'section'  => 'share', 
            'default'  => '[ed_share_icons]',
            'sanitize' => 'full'
         ],
        'facebook_share_icon' => [
            'title'    => 'Facebook',
            'type'     => 'checkbox',
            'section'  => 'share', 
            'default'  => 1
         ],
        'twitter_share_icon' => [
            'title'    => 'Twitter',
            'type'     => 'checkbox',
            'section'  => 'share', 
            'default'  => 1
         ],
        'pinterest_share_icon' => [
            'title'    => 'Pinterest',
            'type'     => 'checkbox',
            'section'  => 'share', 
            'default'  => 1
         ],
        'whatsapp_share_icon' => [
            'title'    => 'WhatsApp',
            'type'     => 'checkbox',
            'section'  => 'share', 
            'default'  => 1
         ],
        'linkedin_share_icon' => [
            'title'    => 'Linkedin',
            'type'     => 'checkbox',
            'section'  => 'share', 
            'default'  => 1
         ],
        'reddit_share_icon' => [
            'title'    => 'Reddit',
            'type'     => 'checkbox',
            'section'  => 'share', 
            'default'  => 1
         ],
        'gmail_share_icon' => [
            'title'    => 'Gmail',
            'type'     => 'checkbox',
            'section'  => 'share', 
            'default'  => 1
         ],
        'email_share_icon' => [
            'title'    => 'Email',
            'type'     => 'checkbox',
            'section'  => 'share', 
            'default'  => 1
         ],
         'follow_icons_shortcode' => [
             'title'    => esc_html__( 'Follow Icons Shortcode', 'expand-divi' ),
             'type'     => 'disabledtext',
             'section'  => 'follow', 
             'default'  => '[ed_follow_icons]',
             'sanitize' => 'full'
          ],
         'facebook_follow_url' => [
             'title'    => esc_html__( 'Facebook URL', 'expand-divi' ),
             'type'     => 'url',
             'section'  => 'follow', 
             'default'  => '',
             'sanitize' => 'url'
          ],
          'facebook_follow_text' => [
              'title'    => esc_html__( 'Facebook Text/Count', 'expand-divi' ),
              'type'     => 'text',
              'section'  => 'follow', 
              'default'  => '',
              'sanitize' => 'full'
           ],
           'twitter_follow_url' => [
             'title'    => esc_html__( 'Twitter URL', 'expand-divi' ),
             'type'     => 'url',
             'section'  => 'follow', 
             'default'  => '',
             'sanitize' => 'url'
          ],
          'twitter_follow_text' => [
              'title'    => esc_html__( 'Twitter Text/Count', 'expand-divi' ),
              'type'     => 'text',
              'section'  => 'follow', 
              'default'  => '',
              'sanitize' => 'full'
           ],
           'youtube_follow_url' => [
             'title'    => esc_html__( 'Youtube URL', 'expand-divi' ),
             'type'     => 'url',
             'section'  => 'follow', 
             'default'  => '',
             'sanitize' => 'url'
          ],
          'youtube_follow_text' => [
              'title'    => esc_html__( 'Youtube Text/Count', 'expand-divi' ),
              'type'     => 'text',
              'section'  => 'follow', 
              'default'  => '',
              'sanitize' => 'full'
           ],
           'email_follow_url' => [
             'title'    => esc_html__( 'Email URL', 'expand-divi' ),
             'type'     => 'url',
             'section'  => 'follow', 
             'default'  => '',
             'sanitize' => 'url'
          ],
          'email_follow_text' => [
              'title'    => esc_html__( 'Email Text/Count', 'expand-divi' ),
              'type'     => 'text',
              'section'  => 'follow', 
              'default'  => '',
              'sanitize' => 'full'
           ],
           'linkedin_follow_url' => [
             'title'    => esc_html__( 'Linkedin URL', 'expand-divi' ),
             'type'     => 'url',
             'section'  => 'follow', 
             'default'  => '',
             'sanitize' => 'url'
          ],
          'linkedin_follow_text' => [
              'title'    => esc_html__( 'Linkedin Text/Count', 'expand-divi' ),
              'type'     => 'text',
              'section'  => 'follow', 
              'default'  => '',
              'sanitize' => 'full'
           ],
           'instagram_follow_url' => [
             'title'    => esc_html__( 'Instagram URL', 'expand-divi' ),
             'type'     => 'url',
             'section'  => 'follow', 
             'default'  => '',
             'sanitize' => 'url'
          ],
          'instagram_follow_text' => [
              'title'    => esc_html__( 'Instagram Text/Count', 'expand-divi' ),
              'type'     => 'text',
              'section'  => 'follow', 
              'default'  => '',
              'sanitize' => 'full'
           ],
            'whatsapp_follow_url' => [
              'title'    => esc_html__( 'WhatsApp URL', 'expand-divi' ),
              'type'     => 'url',
              'section'  => 'follow', 
              'default'  => '',
              'sanitize' => 'url'
           ],
           'whatsapp_follow_text' => [
               'title'    => esc_html__( 'WhatsApp Text/Count', 'expand-divi' ),
               'type'     => 'text',
               'section'  => 'follow', 
               'default'  => '',
               'sanitize' => 'full'
            ],
           'rss_follow_url' => [
             'title'    => esc_html__( 'RSS URL', 'expand-divi' ),
             'type'     => 'url',
             'section'  => 'follow', 
             'default'  => '',
             'sanitize' => 'url'
          ],
          'rss_follow_text' => [
              'title'    => esc_html__( 'RSS Text/Count', 'expand-divi' ),
              'type'     => 'text',
              'section'  => 'follow', 
              'default'  => '',
              'sanitize' => 'full'
           ],
           'soundcloud_follow_url' => [
             'title'    => esc_html__( 'Soundcloud URL', 'expand-divi' ),
             'type'     => 'url',
             'section'  => 'follow', 
             'default'  => '',
             'sanitize' => 'url'
          ],
          'soundcloud_follow_text' => [
              'title'    => esc_html__( 'Soundcloud Text/Count', 'expand-divi' ),
              'type'     => 'text',
              'section'  => 'follow', 
              'default'  => '',
              'sanitize' => 'full'
           ]
      ];

		add_action( 'admin_menu', array( $this, 'add_expand_divi_menu' ) );
        add_action( 'admin_init', array( $this, 'register_dashboard' ) );
    }

    function add_expand_divi_menu() {
		add_submenu_page( 'tools.php', esc_html__( 'Expand Divi', 'expand-divi' ), esc_html__( 'Expand Divi', 'expand-divi' ), 'manage_options', 'expand-divi', array( $this, 'expand_divi_dashboard_output' ) );
	}
    
    function expand_divi_dashboard_output() {
        // check if the user is admin
        if ( ! current_user_can('manage_options') ) {
            wp_die( esc_html__( 'You do not have permission to access this page!', 'expand-divi' ) );
        } ?>

        <!-- dashboard interface -->
        <div id="expand_divi_wrap">
            <h1><?php esc_html_e( 'Expand Divi Options', 'expand-divi' ); ?></h1>
            <?php settings_errors(); ?>

            <form method="post" action="options.php" id="expand_divi_form">
                <div class="expand_divi_sections_wrap">
                <?php
                    settings_fields( 'expand_divi' );
                    do_settings_sections( 'expand-divi' );
                ?>
                </div>
                <?php submit_button(); ?>
                <div id="expand_divi_save"></div>

            </form>
        </div>
    <?php
    }

    function register_dashboard() {
        register_setting( 'expand_divi', 'expand_divi', 'expand_divi_dashboard_validate' );

        foreach ($this->expand_divi_sections as $id => $value) {
            add_settings_section( $id, $value['title'], array($this, 'expand_divi_section_callback'), 'expand-divi');
        }

        foreach ($this->expand_divi_fields as $id => $value) {
            add_settings_field( $id, esc_html__( $value['title'], 'expand-divi' ), array($this, 'expand_divi_field_callback'), 'expand-divi', $value['section'], $id );
        }
    }

    function expand_divi_dashboard_validate( $input ) {
        $output = [];

        foreach ( $input as $key => $value ) {
            $field_sanitize = expand_divi_field_sanitize( $key );
            
            switch ( $field_sanitize ) {
                case 'default':
                    $output[ $key ] = strip_tags( stripslashes( $input[ $key ] ) );
                break;
                case 'full':
                    $output[ $key ] = esc_url_raw( strip_tags( stripslashes( $input[ $key ] ) ) ); 
                break;
                case 'url':
                    $output[ $key ] = esc_url( strip_tags( stripslashes( $input[ $key ] ) ) ); 
                break;
                default:
                    $output[ $key ] = $input[ $key ];
                break;
            }
        }
        return $output;
    }

    function expand_divi_field_sanitize( $key ) {
        return $this->expand_divi_fields[ $key ]['sanitize'];
    }

    // callback of add_settings_section()
    function expand_divi_section_callback( $args ) {
        return null;
    }

    // set the field's default value, used when no value is retrieved from DB
    function expand_divi_default_id( $id ) {
        return $this->expand_divi_fields[ $id ]['default'];
    }

    // callback of add_settings_field(), outputs the fields
    function expand_divi_field_callback( $id ) {
        $option = get_option( 'expand_divi' );
        $value_field = isset( $option[ $id ] ) ? $option[ $id ] : $this->expand_divi_default_id( $id );
        
        // output the field HTML according to expand_divi_field type
        switch ( $this->expand_divi_fields[ $id ]['type'] ) {
            case 'select':
                echo '<select name="expand_divi[' . $id . ']">';
                for ( $i = 0; $i < sizeof( $this->expand_divi_fields[ $id ]['children'] ); $i++ ) {
                    echo "<option value='" . $i ."' " . selected( $value_field, $i, false ) . ">";
                    echo $this->expand_divi_fields[ $id ]['children'][ $i ];
                    echo "</option>";
                }
                echo '</select>';
            break;
            case 'pages':
                echo '<select name="expand_divi[' . $id . ']">';
                $get_pages = get_pages();
                foreach ( $get_pages as $page ) {
                    echo "<option value='" . $page->ID ."' " . selected( $value_field, $page->ID, false ) . ">";
                    echo $page->post_title;
                    echo "</option>";
                } 
                echo '</select>';
            break;
            case 'text':
                echo '<input type="text" name="expand_divi[' . $id . ']" value="' . $value_field . '"/>';
            break;
            case 'disabledtext':
                echo '<input disabled type="text" value="' . $value_field . '"/>';
            break;
            case 'url':
                echo '<input type="url" name="expand_divi[' . $id . ']" value="' . $value_field . '"/>';
            break;
            case 'checkbox':
                // if $value_field == on the checkbox is checked, if == 1 it's not checked
                echo '<input type="checkbox" name="expand_divi[' . $id . ']" ' . checked( $value_field, "on", false ) . ' />';
            break;
        }
    }
}

new ExpandDiviDashboard();