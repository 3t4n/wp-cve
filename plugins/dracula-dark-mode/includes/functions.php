<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
function dracula_get_settings( $key = null, $default = '' )
{
    $settings_name = 'dracula_settings';
    $settings = get_option( $settings_name, array() );
    if ( !$key ) {
        return $settings;
    }
    if ( isset( $settings[$key] ) ) {
        return $settings[$key];
    }
    return $default;
}

function dracula_get_config()
{
    $activeCustomPreset = dracula_get_settings( 'activeCustomPreset' );
    $customPresets = ( !empty(dracula_get_settings( 'customPresets' )) ? dracula_get_settings( 'customPresets' ) : array() );
    $index = array_search( $activeCustomPreset, array_column( $customPresets, 'id' ) );
    $currentCustomPreset = ( !empty($index) ? $customPresets[$index] : '' );
    $brightness = dracula_get_settings( 'brightness', 100 );
    $contrast = dracula_get_settings( 'contrast', 90 );
    $sepia = dracula_get_settings( 'sepia', 10 );
    $grayscale = dracula_get_settings( 'grayscale', 0 );
    $color_mode = dracula_get_settings( 'colorMode', false );
    $background_color = $currentCustomPreset['colors']['bg'] ?? '#181a1b';
    $text_color = $currentCustomPreset['colors']['text'] ?? '#e8e6e3';
    $change_font = dracula_get_settings( 'changeFont', false );
    $font_family = dracula_get_settings( 'fontFamily' );
    $darken_background_images = dracula_get_settings( 'darkenBackgroundImages', true );
    $text_stroke = dracula_get_settings( 'textStroke', '0' );
    $dark_to_light = dracula_get_settings( 'darkToLight', false );
    $preset_key = dracula_get_settings( 'preset', 'dracula' );
    $scrollbar_dark_mode = dracula_get_settings( 'scrollbarDarkMode', 'auto' );
    $scrollbar_color = dracula_get_settings( 'scrollbarColor', '#181a1b' );
    $config = array(
        'mode'                   => ( $dark_to_light ? 0 : 1 ),
        'brightness'             => $brightness,
        'contrast'               => $contrast,
        'sepia'                  => $sepia,
        'grayscale'              => $grayscale,
        'excludes'               => dracula_get_excludes(),
        'darkenBackgroundImages' => $darken_background_images,
        'textStroke'             => $text_stroke,
    );
    
    if ( 'presets' === $color_mode ) {
        $preset = dracula_get_preset( $preset_key );
        $config['darkSchemeBackgroundColor'] = $preset['colors']['bg'];
        $config['darkSchemeTextColor'] = $preset['colors']['text'];
        $config['lightSchemeBackgroundColor'] = $preset['colors']['bg'];
        $config['lightSchemeTextColor'] = $preset['colors']['text'];
    } elseif ( 'custom' === $color_mode ) {
        $config['darkSchemeBackgroundColor'] = $background_color;
        $config['darkSchemeTextColor'] = $text_color;
        $config['lightSchemeTextColor'] = $background_color;
        $config['lightSchemeBackgroundColor'] = $text_color;
    }
    
    
    if ( $change_font ) {
        $config['useFont'] = $change_font;
        $config['fontFamily'] = $font_family;
    }
    
    // Scrollbar
    
    if ( 'custom' == $scrollbar_dark_mode ) {
        $config['scrollbarColor'] = $scrollbar_color;
    } elseif ( 'disabled' == $scrollbar_dark_mode ) {
        $config['scrollbarColor'] = '';
    } elseif ( 'auto' == $scrollbar_dark_mode ) {
        $config['scrollbarColor'] = 'auto';
    } else {
        $config['scrollbarColor'] = '';
    }
    
    return $config;
}

function dracula_get_admin_config()
{
    $brightness = dracula_get_settings( 'adminBrightness', '100' );
    $contrast = dracula_get_settings( 'adminContrast', '90' );
    $sepia = dracula_get_settings( 'adminSepia', '10' );
    $grayscale = dracula_get_settings( 'adminGrayscale', '0' );
    $config = array(
        'brightness' => $brightness,
        'contrast'   => $contrast,
        'sepia'      => $sepia,
        'grayscale'  => $grayscale,
        'excludes'   => '.dracula-ignore',
    );
    return $config;
}

function dracula_get_excludes()
{
    $default = '.dracula-ignore ';
    $excludes = array_filter( dracula_get_settings( 'excludes', [] ) );
    if ( !empty($excludes) ) {
        $default .= ', ' . implode( ', ', $excludes );
    }
    return $default;
}

function dracula_get_includes()
{
    return dracula_get_settings( 'includes' );
}

function dracula_get_user_roles()
{
    $user_roles = array();
    $roles = get_editable_roles();
    foreach ( $roles as $role => $details ) {
        $user_roles[$role] = $details['name'];
    }
    return $user_roles;
}

function dracula_is_user_dark_mode()
{
    if ( !is_user_logged_in() ) {
        return false;
    }
    if ( current_user_can( 'administrator' ) ) {
        return true;
    }
    $dark_mode_user_roles = dracula_get_settings( 'userRoles', [ 'administrator' ] );
    $user = wp_get_current_user();
    $roles = $user->roles;
    if ( !array_intersect( $dark_mode_user_roles, $roles ) ) {
        return false;
    }
    return true;
}

function dracula_page_excluded()
{
    $excludes = dracula_get_settings( 'excludePages' );
    $exclude_all = dracula_get_settings( 'excludeAll', false );
    $excludes_except = dracula_get_settings( 'excludeExceptPages', [] );
    if ( empty($excludes) && !$exclude_all ) {
        return false;
    }
    if ( is_front_page() ) {
        
        if ( $exclude_all ) {
            return !in_array( 'home', $excludes_except );
        } else {
            return in_array( 'home', $excludes );
        }
    
    }
    //check search page
    if ( is_search() ) {
        
        if ( $exclude_all ) {
            return !in_array( 'search', $excludes_except );
        } else {
            return in_array( 'search', $excludes );
        }
    
    }
    //check 404 page
    if ( is_404() ) {
        
        if ( $exclude_all ) {
            return !in_array( '404', $excludes_except );
        } else {
            return in_array( '404', $excludes );
        }
    
    }
    //check archive page
    if ( is_archive() ) {
        
        if ( $exclude_all ) {
            return !in_array( 'archive', $excludes_except );
        } else {
            return in_array( 'archive', $excludes );
        }
    
    }
    //check author page
    if ( is_author() ) {
        
        if ( $exclude_all ) {
            return !in_array( 'author', $excludes_except );
        } else {
            return in_array( 'author', $excludes );
        }
    
    }
    //check tag page
    if ( is_tag() ) {
        
        if ( $exclude_all ) {
            return !in_array( 'tag', $excludes_except );
        } else {
            return in_array( 'tag', $excludes );
        }
    
    }
    //check category page
    if ( is_category() ) {
        
        if ( $exclude_all ) {
            return !in_array( 'category', $excludes_except );
        } else {
            return in_array( 'category', $excludes );
        }
    
    }
    //check if is login page
    
    if ( !empty($GLOBALS['pagenow']) && sanitize_text_field( $GLOBALS['pagenow'] ) === 'wp-login.php' ) {
        $is_register = !empty($_GET['action']) && $_GET['action'] == 'register';
        $is_lost_password = !empty($_GET['action']) && $_GET['action'] == 'lostpassword';
        
        if ( $is_register ) {
            
            if ( $exclude_all ) {
                return !in_array( 'register', $excludes_except );
            } else {
                return in_array( 'register', $excludes );
            }
        
        } elseif ( $is_lost_password ) {
            
            if ( $exclude_all ) {
                return !in_array( 'lostpassword', $excludes_except );
            } else {
                return in_array( 'lostpassword', $excludes );
            }
        
        } else {
            
            if ( $exclude_all ) {
                return !in_array( 'login', $excludes_except );
            } else {
                return in_array( 'login', $excludes );
            }
        
        }
    
    }
    
    // Check if post_id is in exclude list
    $query_id = get_queried_object_id();
    if ( !empty($query_id) ) {
        
        if ( $exclude_all ) {
            return !in_array( $query_id, $excludes_except );
        } else {
            return in_array( $query_id, $excludes );
        }
    
    }
    return false;
}

/**
 * Taxonomy & Tags Exclude
 * @darkmode
 * @since 1.10.0
 */
function dracula_taxonomy_excluded()
{
    if ( !is_singular() ) {
        return false;
    }
    $excludes = dracula_get_settings( 'excludeTaxs', [] );
    $exclude_all = dracula_get_settings( 'excludeAllTaxs' );
    $excludes_except = dracula_get_settings( 'excludeExceptTaxs', [] );
    if ( empty($excludes) && !$exclude_all ) {
        return false;
    }
    $id = get_queried_object_id();
    $taxonomy_ids = get_taxonomy_ids( $id );
    if ( !empty($taxonomy_ids) ) {
        
        if ( $exclude_all ) {
            return !array_intersect( $taxonomy_ids, $excludes_except );
        } else {
            return array_intersect( $taxonomy_ids, $excludes );
        }
    
    }
    return false;
}

/**
 * Reading Mode Excluded
 */
function dracula_reading_mode_excluded()
{
    $excludes = dracula_get_settings( 'excludeReadingModePages', [] );
    $exclude_all = dracula_get_settings( 'excludeReadingModeAll', false );
    $excludes_except = dracula_get_settings( 'excludeReadingModeExceptPages', [] );
    if ( empty($excludes) && !$exclude_all ) {
        return false;
    }
    if ( is_front_page() ) {
        
        if ( $exclude_all ) {
            return !in_array( 'home', $excludes_except );
        } else {
            return in_array( 'home', $excludes );
        }
    
    }
    //check search page
    if ( is_search() ) {
        
        if ( $exclude_all ) {
            return !in_array( 'search', $excludes_except );
        } else {
            return in_array( 'search', $excludes );
        }
    
    }
    //check 404 page
    if ( is_404() ) {
        
        if ( $exclude_all ) {
            return !in_array( '404', $excludes_except );
        } else {
            return in_array( '404', $excludes );
        }
    
    }
    //check archive page
    if ( is_archive() ) {
        
        if ( $exclude_all ) {
            return !in_array( 'archive', $excludes_except );
        } else {
            return in_array( 'archive', $excludes );
        }
    
    }
    //check author page
    if ( is_author() ) {
        
        if ( $exclude_all ) {
            return !in_array( 'author', $excludes_except );
        } else {
            return in_array( 'author', $excludes );
        }
    
    }
    //check tag page
    if ( is_tag() ) {
        
        if ( $exclude_all ) {
            return !in_array( 'tag', $excludes_except );
        } else {
            return in_array( 'tag', $excludes );
        }
    
    }
    //check category page
    if ( is_category() ) {
        
        if ( $exclude_all ) {
            return !in_array( 'category', $excludes_except );
        } else {
            return in_array( 'category', $excludes );
        }
    
    }
    //check if is login page
    
    if ( !empty($GLOBALS['pagenow']) && sanitize_text_field( $GLOBALS['pagenow'] ) === 'wp-login.php' ) {
        $is_register = !empty($_GET['action']) && $_GET['action'] == 'register';
        $is_lost_password = !empty($_GET['action']) && $_GET['action'] == 'lostpassword';
        
        if ( $is_register ) {
            
            if ( $exclude_all ) {
                return !in_array( 'register', $excludes_except );
            } else {
                return in_array( 'register', $excludes );
            }
        
        } elseif ( $is_lost_password ) {
            
            if ( $exclude_all ) {
                return !in_array( 'lostpassword', $excludes_except );
            } else {
                return in_array( 'lostpassword', $excludes );
            }
        
        } else {
            
            if ( $exclude_all ) {
                return !in_array( 'login', $excludes_except );
            } else {
                return in_array( 'login', $excludes );
            }
        
        }
    
    }
    
    //check if post_id is in exclude list
    global  $post ;
    if ( !empty($post) ) {
        
        if ( $exclude_all ) {
            return !in_array( $post->ID, $excludes_except );
        } else {
            return in_array( $post->ID, $excludes );
        }
    
    }
    return false;
}

/**
 * Reading Mode Taxonomy Exclude
 * @reading-mode
 * @since 1.10.0
 */
function dracula_reading_mode_taxonomy_excluded()
{
    $excludes = dracula_get_settings( 'excludeReadingModeTaxs', [] );
    $exclude_all = dracula_get_settings( 'excludeReadingModeAllTaxs' );
    $excludes_except = dracula_get_settings( 'excludeReadingModeExceptTaxs', [] );
    if ( empty($excludes) && !$exclude_all ) {
        return false;
    }
    $id = get_queried_object_id();
    $taxonomy_ids = get_taxonomy_ids( $id );
    if ( !empty($taxonomy_ids) ) {
        
        if ( $exclude_all ) {
            return !array_intersect( $taxonomy_ids, $excludes_except );
        } else {
            return array_intersect( $taxonomy_ids, $excludes );
        }
    
    }
    return false;
}

function dracula_get_menus()
{
    //get all menus
    $menus = wp_get_nav_menus();
    $menu_list = array();
    foreach ( $menus as $menu ) {
        $menu_list[$menu->slug] = $menu->name;
    }
    return $menu_list;
}

function dracula_get_exclude_list()
{
    $front_page_id = null;
    if ( get_option( 'show_on_front' ) === 'page' ) {
        $front_page_id = get_option( 'page_on_front' );
    }
    $general_options = [];
    if ( empty($front_page_id) ) {
        $general_options['home'] = __( 'Homepage', 'dracula-dark-mode' );
    }
    $general_options['search'] = __( 'Search page', 'dracula-dark-mode' );
    $general_options['tag'] = __( 'Tag page', 'dracula-dark-mode' );
    $general_options['category'] = __( 'Category page', 'dracula-dark-mode' );
    $general_options['archive'] = __( 'Archive page', 'dracula-dark-mode' );
    $general_options['author'] = __( 'Author page', 'dracula-dark-mode' );
    $general_options['404'] = __( '404 error page', 'dracula-dark-mode' );
    $general_options['login'] = __( 'Login page', 'dracula-dark-mode' );
    $general_options['register'] = __( 'Register page', 'dracula-dark-mode' );
    $general_options['lostpassword'] = __( 'Lost password page', 'dracula-dark-mode' );
    $list = [
        'general' => [
        'label'   => 'General',
        'options' => $general_options,
    ],
    ];
    // Get only visible post types
    $visible_post_types = get_post_types( array(
        "public" => true,
    ) );
    // Each post types
    foreach ( $visible_post_types as $post_type ) {
        $query = new WP_Query( array(
            'post_type'      => $post_type,
            'posts_per_page' => 999,
        ) );
        
        if ( $query->have_posts() ) {
            $list[$post_type] = [
                'label' => ucfirst( $post_type ),
            ];
            $post_type_options = [];
            while ( $query->have_posts() ) {
                $query->the_post();
                $post_type_options[get_the_ID()] = get_the_title();
            }
            $list[$post_type]['options'] = $post_type_options;
        }
    
    }
    return $list;
}

/**
 * Post List for Reading Mode post/pages
 */
function dracula_get_exclude_reading_list()
{
    $list = [];
    // Get only visible post types
    $visible_post_types = get_post_types( array(
        "public" => true,
    ) );
    // Each post types
    foreach ( $visible_post_types as $post_type ) {
        $query = new WP_Query( array(
            'post_type'      => $post_type,
            'posts_per_page' => 999,
        ) );
        
        if ( $query->have_posts() ) {
            $list[$post_type] = [
                'label' => ucfirst( $post_type ),
            ];
            $post_type_options = [];
            while ( $query->have_posts() ) {
                $query->the_post();
                $post_type_options[get_the_ID()] = get_the_title();
            }
            $list[$post_type]['options'] = $post_type_options;
        }
    
    }
    return $list;
}

/**
 * Taxonomy List
 */
function dracula_get_exclude_taxonomy_list()
{
    $list = array();
    $args = array(
        'public'  => true,
        'show_ui' => true,
    );
    $taxonomies = get_taxonomies( $args );
    foreach ( $taxonomies as $taxonomy ) {
        $query = get_terms( array(
            'taxonomy'   => $taxonomy,
            'hide_empty' => 0,
        ) );
        $taxonomy_type_options = [];
        foreach ( $query as $data ) {
            $list[$data->taxonomy] = [
                'label' => ucfirst( ( $data->taxonomy == 'post_tag' ? 'tags' : $data->taxonomy ) ),
            ];
            $taxonomy_type_options[$data->term_id] = $data->name;
            $list[$data->taxonomy]['options'] = $taxonomy_type_options;
        }
    }
    return $list;
}

function dracula_is_block_editor_page()
{
    
    if ( function_exists( 'get_current_screen' ) ) {
        $current_screen = get_current_screen();
        if ( !empty($current_screen->is_block_editor) ) {
            return true;
        }
    }
    
    return false;
}

function dracula_is_classic_editor_page()
{
    
    if ( function_exists( 'get_current_screen' ) ) {
        $current_screen = get_current_screen();
        if ( $current_screen && $current_screen->base == 'post' && empty($current_screen->is_block_editor) ) {
            return true;
        }
    }
    
    return false;
}

function dracula_is_elementor_editor_page()
{
    return !empty($_GET['elementor-preview']);
}

function dracula_is_tablet()
{
    if ( !isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
        return false;
    }
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    if ( preg_match( '/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', $user_agent ) ) {
        return true;
    }
    return false;
}

function dracula_get_preset( $key = null )
{
    $presets = array(
        array(
            'key'    => 'default',
            'label'  => 'Default',
            'colors' => array(
            'bg'   => '#181a1b',
            'text' => '#e8e6e3',
        ),
        ),
        array(
            'key'    => 'dracula',
            'label'  => 'Dracula',
            'colors' => array(
            'bg'   => '#282b36',
            'text' => '#e8e6e3',
        ),
        ),
        array(
            'key'    => 'catppuccin',
            'label'  => 'Catppuccin',
            'colors' => array(
            'bg'   => '#161320',
            'text' => '#d9e0ee',
        ),
        ),
        array(
            'key'    => 'gruvbox',
            'label'  => 'Gruvbox',
            'colors' => array(
            'bg'   => '#282828',
            'text' => '#ebdbb2',
        ),
        ),
        array(
            'key'    => 'nord',
            'label'  => 'Nord',
            'colors' => array(
            'bg'   => '#2e3440',
            'text' => '#eceff4',
        ),
        ),
        array(
            'key'    => 'rosePine',
            'label'  => 'Rose Pine',
            'colors' => array(
            'bg'   => '#191724',
            'text' => '#e0def4',
        ),
        ),
        array(
            'key'    => 'solarized',
            'label'  => 'Solarized',
            'colors' => array(
            'bg'   => '#002b36',
            'text' => '#93a1a1',
        ),
        ),
        array(
            'key'    => 'tokyoNight',
            'label'  => 'Tokyo Night',
            'colors' => array(
            'bg'   => '#1a1b26',
            'text' => '#a9b1d6',
        ),
        ),
        array(
            'key'    => 'monokai',
            'label'  => 'Monokai',
            'colors' => array(
            'bg'   => '#272822',
            'text' => '#f8f8f2',
        ),
        ),
        array(
            'key'    => 'ayuMirage',
            'label'  => 'Ayu Mirage',
            'colors' => array(
            'bg'   => '#1f2430',
            'text' => '#cbccc6',
        ),
        ),
        array(
            'key'    => 'ayuDark',
            'label'  => 'Ayu Dark',
            'colors' => array(
            'bg'   => '#0a0e14',
            'text' => '#b3b1ad',
        ),
        ),
        array(
            'key'    => 'material',
            'label'  => 'Material',
            'colors' => array(
            'bg'   => '#263238',
            'text' => '#eceff1',
        ),
        ),
        array(
            'key'    => 'oneDark',
            'label'  => 'One Dark',
            'colors' => array(
            'bg'   => '#282c34',
            'text' => '#abb2bf',
        ),
        ),
        array(
            'key'    => 'oceanicNext',
            'label'  => 'Oceanic Next',
            'colors' => array(
            'bg'   => '#1B2B34',
            'text' => '#CDD3DE',
        ),
        ),
        array(
            'key'    => 'cityLights',
            'label'  => 'City Lights',
            'colors' => array(
            'bg'   => '#1d252c',
            'text' => '#b6bfc4',
        ),
        ),
        array(
            'key'    => 'nightOwl',
            'label'  => 'Night Owl',
            'colors' => array(
            'bg'   => '#011627',
            'text' => '#d6deeb',
        ),
        ),
        // Sites Presets
        array(
            'key'    => 'youtube',
            'label'  => 'YouTube',
            'colors' => array(
            'bg'   => '#181818',
            'text' => '#ffffff',
        ),
        ),
        array(
            'key'    => 'twitter',
            'label'  => 'Twitter',
            'colors' => array(
            'bg'   => '#15202b',
            'text' => '#ffffff',
        ),
        ),
        array(
            'key'    => 'reddit',
            'label'  => 'Reddit (Night mode)',
            'colors' => array(
            'bg'   => '#1a1a1b',
            'text' => '#d7dadc',
        ),
        ),
        array(
            'key'    => 'discord',
            'label'  => 'Discord',
            'colors' => array(
            'bg'   => '#36393f',
            'text' => '#dcddde',
        ),
        ),
        array(
            'key'    => 'slack',
            'label'  => 'Slack',
            'colors' => array(
            'bg'   => '#1d1c1d',
            'text' => '#e7e7e7',
        ),
        ),
        array(
            'key'    => 'whatsapp',
            'label'  => 'WhatsApp',
            'colors' => array(
            'bg'   => '#121212',
            'text' => '#e6e5e4',
        ),
        ),
        array(
            'key'    => 'github',
            'label'  => 'GitHub',
            'colors' => array(
            'bg'   => '#0d1117',
            'text' => '#c9d1d9',
        ),
        ),
        array(
            'key'    => 'stackoverflow',
            'label'  => 'StackOverflow',
            'colors' => array(
            'bg'   => '#2d2d2d',
            'text' => '#f2f2f2',
        ),
        ),
    );
    if ( !is_null( $key ) ) {
        foreach ( $presets as $preset ) {
            if ( $preset['key'] === $key ) {
                return $preset;
            }
        }
    }
    return $presets;
}

function dracula_sanitize_array( $array )
{
    foreach ( $array as $key => &$value ) {
        
        if ( is_array( $value ) ) {
            $value = dracula_sanitize_array( $value );
        } else {
            
            if ( in_array( $value, [ 'true', 'false' ] ) ) {
                $value = filter_var( $value, FILTER_VALIDATE_BOOLEAN );
            } elseif ( is_numeric( $value ) ) {
                
                if ( strpos( $value, '.' ) !== false ) {
                    $value = floatval( $value );
                } elseif ( filter_var( $value, FILTER_VALIDATE_INT ) !== false && $value <= PHP_INT_MAX ) {
                    $value = intval( $value );
                } else {
                    // Keep large integers or non-integer values as string
                    $value = $value;
                }
            
            } else {
                $value = wp_kses_post( $value );
            }
        
        }
    
    }
    return $array;
}

function dracula_add_dark_mode_selector_prefix( $css )
{
    // Split the CSS into rules using the '}' delimiter
    $rules = explode( '}', $css );
    // Iterate over each rule
    foreach ( $rules as &$rule ) {
        // Check if there is content in the rule (to avoid empty strings)
        
        if ( trim( $rule ) ) {
            // Add the .dark-mode prefix to the selector
            // We use '{' as a delimiter to find the end of the selector
            $parts = explode( '{', $rule, 2 );
            
            if ( count( $parts ) == 2 ) {
                $selectors = explode( ',', $parts[0] );
                foreach ( $selectors as &$selector ) {
                    $selector = trim( $selector );
                    // Prepend .dark-mode to each selector
                    $selector = 'html[data-dracula-scheme="dark"] ' . $selector;
                }
                // Reassemble the rule with modified selectors
                $rule = implode( ', ', $selectors ) . '{' . $parts[1];
            }
        
        }
    
    }
    // Reassemble the CSS
    return implode( '}', $rules );
}

/**
 * Color brightness
 */
function dracula_color_brightness( $hex, $steps )
{
    // return if not hex color
    if ( !preg_match( '/^#([a-f0-9]{3}){1,2}$/i', $hex ) ) {
        return $hex;
    }
    // Steps should be between -255 and 255. Negative = darker, positive = lighter
    $steps = max( -255, min( 255, $steps ) );
    // Normalize into a six character long hex string
    $hex = str_replace( '#', '', $hex );
    if ( strlen( $hex ) == 3 ) {
        $hex = str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 );
    }
    // Split into three parts: R, G and B
    $color_parts = str_split( $hex, 2 );
    $return = '#';
    foreach ( $color_parts as $color ) {
        $color = hexdec( $color );
        // Convert to decimal
        $color = max( 0, min( 255, $color + $steps ) );
        // Adjust color
        $return .= str_pad(
            dechex( $color ),
            2,
            '0',
            STR_PAD_LEFT
        );
        // Make two char hex code
    }
    return $return;
}

/**
 * Author Name
 */
function reading_mode_get_author_name( $post_id )
{
    $post = get_post( $post_id );
    $author_id = $post->post_author;
    return get_the_author_meta( 'display_name', $author_id );
}

/**
 * Reading mode render
 */
function dracula_reading_mode_should_render( $post_id )
{
    $post_type = get_post_type( $post_id );
    $post_types = dracula_get_settings( 'postTypes', [ 'post' ] );
    return in_array( $post_type, $post_types );
}

/**
 * Show time
 */
function dracula_should_show_time()
{
    $enable_reading_time = dracula_get_settings( 'enableReadingTime', true );
    $timeDisplay = dracula_get_settings( 'timeDisplay', [ 'single' ] );
    $should_show_single = in_array( 'single', $timeDisplay ) && is_singular();
    $should_show_blog = in_array( 'blog', $timeDisplay ) && is_home();
    $should_show_archive = in_array( 'archive', $timeDisplay ) && is_archive();
    $should_show_search = in_array( 'search', $timeDisplay ) && is_search();
    return $enable_reading_time && ($should_show_single || $should_show_blog || $should_show_archive || $should_show_search);
}

/**
 * get time html
 */
function dracula_reading_mode_get_time_html( $post_id )
{
    $reading_time = dracula_reading_mode_get_reading_time( $post_id );
    $timeStyle = dracula_get_settings( 'timeStyle', 'default' );
    $buttonSize = dracula_get_settings( 'buttonSize', 'medium' );
    $timeIcon = dracula_get_settings( 'timeIcon', 1 );
    $customTimeIcon = dracula_get_settings( 'customTimeIcon', '' );
    $icon = '';
    
    if ( $customTimeIcon ) {
        $icon_url = $customTimeIcon;
        $icon = sprintf( '<img class="reading-mode-time__icon custom-icon" src="%s" />', $icon_url );
    } else {
        
        if ( $timeIcon ) {
            $icon_url = 'url(../images/icons/time/' . $timeIcon . '.svg) no-repeat center / contain';
            $icon = sprintf( '<span class="reading-mode-time__icon" style="--time-icon: %s"></span>', $icon_url );
        }
    
    }
    
    if ( !empty($icon) ) {
        $icon = sprintf( '<span class="time-icon-wrap">%s</span>', $icon );
    }
    $timeText = dracula_get_settings( 'timeText', '%time% minutes read' );
    $timeText = str_replace( '%time%', $reading_time, $timeText );
    $time_text = sprintf( '<span class="reading-mode-time__text">%s</span>', $timeText );
    return sprintf(
        '<span class="reading-mode-time style-%s size-%s">%s %s</span>',
        $timeStyle,
        $buttonSize,
        $icon,
        $time_text
    );
}

/**
 * Dracula reading time
 * @reading_mode
 */
function dracula_reading_mode_get_reading_time( $post_id, $with_markup = false )
{
    $content = get_post_field( 'post_content', $post_id );
    $content = wp_strip_all_tags( $content );
    $words = str_word_count( $content );
    $words_per_minute_slow = 200;
    $words_per_minute_fast = 280;
    $reading_time_slow = ceil( $words / $words_per_minute_slow );
    $reading_time_fast = ceil( $words / $words_per_minute_fast );
    
    if ( $reading_time_slow == 0 && $reading_time_fast == 0 ) {
        $reading_time_slow = 1;
        $reading_time_fast = 1;
    }
    
    
    if ( $reading_time_slow == $reading_time_fast ) {
        $time = sprintf( '%s', $reading_time_slow );
    } else {
        $time = sprintf( '%s - %s', $reading_time_fast, $reading_time_slow );
    }
    
    
    if ( $with_markup ) {
        $time_text = dracula_get_settings( 'timeText', '%time% minutes read' );
        $time_text = str_replace( '%time%', $time, $time_text );
        $time = '<div class="reading-mode-time"><i class="dashicons dashicons-clock"></i> ' . $time_text . '</div>';
    }
    
    return $time;
}

/**
 * get button
 */
function dracula_reading_mode_get_button_html( $post_id )
{
    $readingModeStyle = dracula_get_settings( 'readingModeStyle', 'default' );
    $buttonSize = dracula_get_settings( 'buttonSize', 'medium' );
    $readingModeLabel = dracula_get_settings( 'readingModeLabel', true );
    $readingModeText = dracula_get_settings( 'readingModeText', 'Reading Mode' );
    $readingModeIcon = dracula_get_settings( 'readingModeIcon', 1 );
    $customReadingModeIcon = dracula_get_settings( 'customReadingModeIcon', '' );
    $icon = '';
    $reading_mode_icon_only = ( !$readingModeLabel ? 'reading-mode-icon-only' : '' );
    
    if ( $customReadingModeIcon ) {
        $icon_url = $customReadingModeIcon;
        $icon = sprintf( '<img class="reading-mode-button__icon custom-icon" src="%s" />', $icon_url );
    } else {
        
        if ( $readingModeIcon ) {
            $icon_url = 'url(../images/icons/reading-mode/' . $readingModeIcon . '.svg) no-repeat center / contain';
            $icon = sprintf( '<span class="reading-mode-button__icon" style="--reading-mode-icon: %s"></span>', $icon_url );
        }
    
    }
    
    if ( !empty($icon) ) {
        $icon = sprintf( '<span class="reading-mode-icon-wrap %s">%s</span>', $reading_mode_icon_only, $icon );
    }
    $reading_mode_text = sprintf( '<span class="reading-mode-button__text">%s</span>', $readingModeText );
    
    if ( $readingModeLabel ) {
        $html = sprintf(
            '<span class="reading-mode-button style-%s size-%s" data-post-id="%s">%s%s</span>',
            $readingModeStyle,
            $buttonSize,
            $post_id,
            $icon,
            $reading_mode_text
        );
    } else {
        $html = sprintf(
            '<span class="reading-mode-button style-%s %s size-%s" data-post-id="%s">%s</span>',
            $readingModeStyle,
            $reading_mode_icon_only,
            $buttonSize,
            $post_id,
            $icon
        );
    }
    
    return $html;
}

/**
 * show button
 */
function dracula_should_show_button()
{
    $enable_reading_mode = dracula_get_settings( 'readingMode', true );
    $readingModeDisplay = dracula_get_settings( 'readingModeDisplay', [ 'single' ] );
    $should_show_single = in_array( 'single', $readingModeDisplay ) && is_singular();
    $should_show_blog = in_array( 'blog', $readingModeDisplay ) && is_home();
    $should_show_archive = in_array( 'archive', $readingModeDisplay ) && is_archive();
    $should_show_search = in_array( 'search', $readingModeDisplay ) && is_search();
    return $enable_reading_mode && ($should_show_single || $should_show_blog || $should_show_archive || $should_show_search);
}

/**
 * Retrives post type list
 */
function dracula_get_post_type_list()
{
    $post_types = get_post_types( array(
        "public" => true,
    ), 'objects' );
    $excludes = array(
        'attachment',
        'elementor_library',
        'Media',
        'My Templates'
    );
    $list = [];
    foreach ( $post_types as $key => $obj ) {
        if ( in_array( $obj->label, $excludes ) ) {
            continue;
        }
        $list[] = [
            'value' => $key,
            'label' => $obj->labels->name,
        ];
    }
    return $list;
}

/**
 * Render Progress Bar
 */
function dracula_render_progressbar()
{
    $position = dracula_get_settings( 'progressPosition', 'top' );
    ?>
    <div class="reading-mode-progress position-<?php 
    echo  esc_attr( $position ) ;
    ?>">
        <div class="reading-mode-progress-fill"></div>
    </div>
<?php 
}

/**
 * get taxonomy IDs for a post
 */
function get_taxonomy_ids( $post_id )
{
    $taxonomy_ids = [];
    $object_taxonomies = get_object_taxonomies( get_post_type( $post_id ) );
    foreach ( $object_taxonomies as $taxonomy ) {
        $taxonomies = get_the_terms( $post_id, $taxonomy );
        if ( !empty($taxonomies) ) {
            foreach ( $taxonomies as $attachedTaxonomy ) {
                $taxonomy_ids[] = $attachedTaxonomy->term_id;
            }
        }
    }
    return $taxonomy_ids;
}
