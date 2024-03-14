<?php
/**
 * 
 * Fuse General Functions
 * 
 * */
function fuse_float_plugin_scripts() {
    $options = get_option( 'fuse' );
    $options = $options['opt-sortable'];
    wp_enqueue_style( 'fuse-awesome', plugin_dir_url( __FILE__ )."font-awesome/css/font-awesome.min.css", array() , FUSE_VERSION );
    if ( !empty($options['Threads']) ) {
        wp_enqueue_style( 'fuse-custom-icons', plugin_dir_url( __FILE__ )."font-awesome/css/custom-icons.css", array() , FUSE_VERSION );
    }

}
add_action( 'wp_enqueue_scripts', 'fuse_float_plugin_scripts' );

/*---------------------------------------------------
Social Icons generator for front-end
----------------------------------------------------*/
function fuse_social_update_analytics()
{
    // The $_REQUEST contains all the data sent via ajax
    
    if ( isset( $_POST['connect'] ) && isset( $_POST['nonce'] ) ) {
        $connect = sanitize_text_field( $_POST['connect'] );
        $nonce = sanitize_text_field( $_POST['nonce'] );
        
        if ( wp_verify_nonce( $nonce, 'fuse_social_floating' ) ) {
            $fuse_click_data = unserialize( get_option( 'fuse_click_data' ) );
            $connect_click = $fuse_click_data[$connect];
            $connect_click = $connect_click + 1;
            // Increasing one more click here
            $fuse_click_data[$connect] = $connect_click;
            $fuse_click_data = serialize( $fuse_click_data );
            update_option( 'fuse_click_data', $fuse_click_data );
        }
    
    }
    
    // Always die in functions echoing ajax content
    die;
}

add_action( 'wp_ajax_fuse_social_update_analytics', 'fuse_social_update_analytics' );
add_action( 'wp_ajax_nopriv_fuse_social_update_analytics', 'fuse_social_update_analytics' );
//Checking is style is square or round.
$fuse_social_opt_front = array(
    'square' => array(
    'value' => 'square',
    'label' => __( '', 'awesome-social' ),
),
    'round'  => array(
    'value' => 'round',
    'label' => __( '', 'awesome-social' ),
),
);
global  $fuse_social_style ;
if ( !isset( $checked ) ) {
    $checked = '';
}
foreach ( $fuse_social_opt_front as $option ) {
    $radio_setting = "";
    if ( !empty($options['style_input']) ) {
        $radio_setting = $options['style_input'];
    }
    if ( '' != $radio_setting ) {
        
        if ( $options['style_input'] == $option['value'] ) {
            $fuse_social_style = $options['style_input'];
            $checked = "checked=\"checked\"";
        } else {
            $checked = '';
        }
    
    }
}
class Making_Fuse_Icons
{
    // Generating Icons with respective links
    function Create_Awesome_Icons()
    {
        $options = get_option( 'fuse' );
        
        if ( $this->fuse_check_if_key_val( $options ) ) {
            $this->fuse_redux_generate_HTML( $options );
        } else {
            $options = get_option( 'fuse_social_options' );
            $this->fuse_generate_HTML( $options );
        }
        
        $countiner = 20;
    }
    
    function fuse_check_if_key_val( $options )
    {
        foreach ( $options as $key => $value ) {
            if ( $value ) {
                return 1;
            }
        }
        return 0;
    }
    
    function fuse_redux_generate_HTML( $options )
    {
        global  $post ;
        $pageid = $post->ID;
        echo  "<div id='icon_wrapper'>" ;
        $fuse_settings = $options;
        $display_flag = 1;
        $pages_hide_from = "";
        $hide_blog_posts = "";
        $custom_icons = "";
        $options = $options['opt-sortable'];
        // Checking if target is _self or _blank
        
        if ( $fuse_settings['linksnewtab'] ) {
            $target = 'target="_blank"';
        } else {
            $target = 'target="_self"';
        }
        
        $alllinks = array();
        // Checking if social icon value is set from admin settings then display that icon, other wise not.
        foreach ( $options as $socialnet => $socialinks ) {
            
            if ( $socialinks ) {
                $alllinks[$socialnet] = $socialinks;
                
                if ( !empty($alllinks['Facebook']) ) {
                    $alllinks['facebook'] = $socialinks;
                    unset( $alllinks['Facebook'] );
                }
                if ( !empty($alllinks['Threads']) ) {
                    $alllinks['threads'] = $socialinks;
                    unset( $alllinks['Threads'] );
                }                
                
                if ( !empty($alllinks['Twitter']) ) {
                    $alllinks['twitter'] = $socialinks;
                    unset( $alllinks['Twitter'] );
                }
                
                
                if ( !empty($alllinks['RSS']) ) {
                    $alllinks['rss'] = $socialinks;
                    unset( $alllinks['RSS'] );
                }
                
                
                if ( !empty($alllinks['Linkedin']) ) {
                    $alllinks['linkedin'] = $socialinks;
                    unset( $alllinks['Linkedin'] );
                }
                
                
                if ( !empty($alllinks['Youtube']) ) {
                    $alllinks['youtube'] = $socialinks;
                    unset( $alllinks['Youtube'] );
                }
                
                
                if ( !empty($alllinks['Flickr']) ) {
                    $alllinks['flickr'] = $socialinks;
                    unset( $alllinks['Flickr'] );
                }
                
                
                if ( !empty($alllinks['Stumbleupon']) ) {
                    $alllinks['stumbleupon'] = $socialinks;
                    unset( $alllinks['Stumbleupon'] );
                }
                
                
                if ( !empty($alllinks['Instagram']) ) {
                    $alllinks['instagram'] = $socialinks;
                    unset( $alllinks['Instagram'] );
                }
                
                
                if ( !empty($alllinks['Tumblr']) ) {
                    $alllinks['tumblr'] = $socialinks;
                    unset( $alllinks['Tumblr'] );
                }
                
                
                if ( !empty($alllinks['Vine']) ) {
                    $alllinks['vine'] = $socialinks;
                    unset( $alllinks['Vine'] );
                }
                
                
                if ( !empty($alllinks['VK']) ) {
                    $alllinks['vk'] = $socialinks;
                    unset( $alllinks['VK'] );
                }
                
                
                if ( !empty($alllinks['SoundCloud']) ) {
                    $alllinks['soundcloud'] = $socialinks;
                    unset( $alllinks['SoundCloud'] );
                }
                
                
                if ( !empty($alllinks['Pinterest']) ) {
                    $alllinks['pinterest'] = $socialinks;
                    unset( $alllinks['Pinterest'] );
                }
                
                
                if ( !empty($alllinks['Reddit']) ) {
                    $alllinks['reddit'] = $socialinks;
                    unset( $alllinks['Reddit'] );
                }
                
                
                if ( !empty($alllinks['StackOverFlow']) ) {
                    $alllinks['stack-overflow'] = $socialinks;
                    unset( $alllinks['StackOverFlow'] );
                }
                
                
                if ( !empty($alllinks['Behance']) ) {
                    $alllinks['behance'] = $socialinks;
                    unset( $alllinks['Behance'] );
                }
                
                
                if ( !empty($alllinks['Github']) ) {
                    $alllinks['github'] = $socialinks;
                    unset( $alllinks['Github'] );
                }
                
                
                if ( !empty($alllinks['Email']) ) {
                    $alllinks['envelope'] = $socialinks;
                    unset( $alllinks['Email'] );
                }
            
            }
        
        }
        if ( !empty($pages_hide_from) ) {
            
            if ( in_array( $pageid, $pages_hide_from ) ) {
                $display_flag = 0;
            } else {
                $display_flag = 1;
            }
        
        }
        if ( $hide_blog_posts ) {
            $display_flag = 0;
        }

        $relattr = "";
        if(!empty($fuse_settings['relattr'])){
            if ( $fuse_settings['relattr'] == 1 ) {
                $relattr = 'rel="nofollow"';
            } else {
                $relattr = "";
            }
        }

        
        if ( $display_flag ) {
            if ( !empty($fuse_settings['custom_icon_on_top']) ) {
                $this->fuse_get_custom_icons( $fuse_settings );
            }
            foreach ( $alllinks as $key => $value ) {
                if ( !empty($value) ) {
                    $value = esc_url($value);
                    echo  "<a {$target} class='fuse_social_icons_links' data-nonce='" . wp_create_nonce( 'fuse_social_floating' ) . "' data-title='{$key}' href='{$value}' $relattr><i class='fsf fuseicon-{$key} {$key}-awesome-social awesome-social'></i></a>" ;
                }
            }
            if ( empty($fuse_settings['custom_icon_on_top']) ) {
                $this->fuse_get_custom_icons( $fuse_settings );
            }
        }

        echo "</div>";
    
    }
    
    public function fuse_get_custom_icons( $fuse_settings )
    {
        
        if ( $fuse_settings['linksnewtab'] ) {
            $target = 'target="_blank"';
        } else {
            $target = 'target="_self"';
        }
        
        if ( !empty($fuse_settings['fuse-custom-icons']) ) {
            for ( $i = 0 ;  $i < count( $fuse_settings['fuse-custom-icons'] ) ;  $i++ ) {
                
                if ( !empty($fuse_settings['fuse-custom-icons']['social_icon_url'][$i]) ) {
                    $icondata = "";
                    $icon_settings = $fuse_settings['fuse-custom-icons']['icon_url'][$i];
                    $iconbackground = "";
                    $iconsocialimgclass = "";
                    
                    if ( $icon_settings['url'] ) {
                        $icondata = '<img src="' . esc_url($icon_settings['url']) . '" alt="' . esc_attr($icon_settings['title']) . '" style="width:' . esc_attr($fuse_settings['fuse-custom-icons']['icon-size'][$i]) . 'px;" />';
                        $iconbackground = "style='background-color:" . esc_attr($fuse_settings['fuse-custom-icons']['bg_color'][$i]) . ";'";
                        $iconsocialimgclass = esc_attr("awesome-social awesome-social-img");
                    } else {
                        $icondata = "<i class='" . esc_attr($fuse_settings['fuse-custom-icons']['select_font_icon'][$i]) . " custom-fuse-social awesome-social' style='background-color:" . esc_attr($fuse_settings['fuse-custom-icons']['bg_color'][$i]) . ";color:" . esc_attr($fuse_settings['fuse-custom-icons']['icon_m_color'][$i]) . " !important;'></i>";
                    }
                    
                    echo  "<a {$target}  {$iconbackground} class='{$iconsocialimgclass} fuse_social_icons_links fuse_custom_icons' data-nonce='" . esc_attr(wp_create_nonce( 'fuse_social_floating' )) . "' data-title='" . esc_attr($fuse_settings['fuse-custom-icons']['title_field'][$i]) . "' href='" . esc_url($fuse_settings['fuse-custom-icons']['social_icon_url'][$i]) . "'>" . wp_kses_data($icondata) . "</a>" ;
                }
            
            }
        }
    }
    
    // This function will be removed soon.
    function fuse_generate_HTML( $options )
    {
        echo  "<div id='icon_wrapper'>" ;
        // Checking if target is _self or _blank
        
        if ( $options['linksnewtab'] == 1 ) {
            $target = 'target="_blank"';
        } else {
            $target = 'target="_self"';
        }

        $relattr = "";
        if ( $options['relattr'] == 1 ) {
            $relattr = 'rel="nofollow"';
        } else {
            $relattr = "";
        }

        
        // Checking if social icon value is set from admin settings then display that icon, other wise not.
        
        if ( $options['facebook'] ) {
            $facebook = esc_url($options['facebook']);
            echo  "<a  {$target}  class='fuse_social_icons_links'  href='{$facebook}' $relattr>\t<i class='fsf fuseicon-facebook fb-awesome-social awesome-social'></i></a><br />" ;
        }
        
        
        if ( $options['twitter'] ) {
            $twitter = esc_url($options['twitter']);
            echo  "<a {$target} class='fuse_social_icons_links' href='{$twitter}' $relattr>\t<i class='fsf fuseicon-twitter tw-awesome-social awesome-social'></i></a><br />" ;
        }
        
        
        if ( $options['rss'] ) {
            $rss = esc_url($options['rss']);
            echo  "<a {$target} class='fuse_social_icons_links' href='{$rss}' $relattr>\t<i class='fsf fuseicon-rss rss-awesome-social awesome-social'></i></a><br />" ;
        }
        
        
        if ( $options['linkedin'] ) {
            $linkedin = esc_url($options['linkedin']);
            echo  "<a {$target} class='fuse_social_icons_links' href='{$linkedin}' $relattr>\t<i class='fsf fuseicon-linkedin linkedin-awesome-social awesome-social'></i></a><br />" ;
        }
        
        
        if ( $options['youtube'] ) {
            $youtube = esc_url($options['youtube']);
            echo  "<a {$target} class='fuse_social_icons_links' href='{$youtube}' $relattr>\t<i class='fsf fuseicon-youtube youtube-awesome-social awesome-social'></i></a><br />" ;
        }
        
        
        if ( $options['flickr'] ) {
            $flickr = esc_url($options['flickr']);
            echo  "<a {$target} class='fuse_social_icons_links' href='{$flickr}' $relattr>\t<i class='fsf fuseicon-flickr flickr-awesome-social awesome-social'></i></a><br />" ;
        }
        
        
        if ( $options['pinterest'] ) {
            $pinterest = esc_url($options['pinterest']);
            echo  "<a {$target} class='fuse_social_icons_links' href='{$pinterest}' $relattr>\t<i class='fsf fuseicon-pinterest pinterest-awesome-social awesome-social'></i></a><br />" ;
        }
        
        
        if ( $options['stumbleupon'] ) {
            $stumbleupon = esc_url($options['stumbleupon']);
            echo  "<a {$target} class='fuse_social_icons_links' href='{$stumbleupon}' $relattr>\t<i class='fsf fuseicon-stumbleupon stumbleupon-awesome-social awesome-social'></i></a><br />" ;
        }
        
        
        if ( $options['google-plus'] ) {
            $google = esc_url($options['google-plus']);
            echo  "<a {$target} class='fuse_social_icons_links' href='{$google}' $relattr>\t<i class='fsf fuseicon-google-plus google-plus-awesome-social awesome-social'></i></a><br />" ;
        }
        
        
        if ( $options['instagram'] ) {
            $instagram = $options['instagram'];
            echo  "<a {$target} class='fuse_social_icons_links' href='{$instagram}' $relattr>\t<i class='fsf fuseicon-instagram instagram-awesome-social awesome-social'></i></a><br />" ;
        }
        
        
        if ( $options['tumblr'] ) {
            $tumblr = esc_url($options['tumblr']);
            echo  "<a {$target} class='fuse_social_icons_links' href='{$tumblr}' $relattr>\t<i class='fsf fuseicon-tumblr tumblr-awesome-social awesome-social'></i></a><br />" ;
        }
        
        
        if ( $options['vine'] ) {
            $vine = esc_url($options['vine']);
            echo  "<a {$target} class='fuse_social_icons_links' href='{$vine}' $relattr>\t<i class='fsf fuseicon-vine vine-awesome-social awesome-social'></i></a><br />" ;
        }
        
        
        if ( $options['vk'] ) {
            $vk = esc_url($options['vk']);
            echo  "<a {$target} class='fuse_social_icons_links' href='{$vk}' $relattr>\t<i class='fsf fuseicon-vk vk-awesome-social awesome-social'></i></a><br />" ;
        }
        
        
        if ( $options['soundcloud'] ) {
            $soundcloud = esc_url($options['soundcloud']);
            echo  "<a {$target} class='fuse_social_icons_links' href='{$soundcloud}' $relattr>\t<i class='fsf fuseicon-soundcloud soundcloud-awesome-social awesome-social'></i></a><br />" ;
        }
        
        
        if ( $options['reddit'] ) {
            $reddit = esc_url($options['reddit']);
            echo  "<a {$target} class='fuse_social_icons_links' href='{$reddit}' $relattr>\t<i class='fsf fuseicon-reddit reddit-awesome-social awesome-social'></i></a><br />" ;
        }
        
        
        if ( $options['stack'] ) {
            $stack = esc_url($options['stack']);
            echo  "<a {$target} class='fuse_social_icons_links' href='{$stack}' $relattr>\t<i class='fsf fuseicon-stack-overflow stack-awesome-social awesome-social'></i></a><br />" ;
        }
        
        
        if ( $options['behance'] ) {
            $behance = esc_url($options['behance']);
            echo  "<a {$target} class='fuse_social_icons_links' href='{$behance}' $relattr>\t<i class='fsf fuseicon-behance behance-awesome-social awesome-social'></i></a><br />" ;
        }
        
        
        if ( $options['github'] ) {
            $github = esc_url($options['github']);
            echo  "<a {$target} class='fuse_social_icons_links' href='{$github}' $relattr >\t<i class='fsf fuseicon-github github-awesome-social awesome-social'></i></a><br />" ;
        }
        
        
        if ( $options['envelope'] ) {
            $envelope = esc_url($options['envelope']);
            echo  "<a {$target} class='fuse_social_icons_links' href='{$envelope}' $relattr>\t<i class='fsf fuseicon-envelope envelope-awesome-social awesome-social'></i></a><br />" ;
        }
    
    }

}