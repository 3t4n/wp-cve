<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
class Dracula_Hooks
{
    private static  $instance = null ;
    public function __construct()
    {
        // Frontend Hooks
        if ( !is_admin() ) {
            
            if ( dracula_get_settings( 'frontendDarkMode', true ) ) {
                add_action( 'wp_head', array( $this, 'header_scripts' ) );
                add_action( 'login_head', array( $this, 'header_scripts' ) );
                add_action( 'init', function () {
                    if ( dracula_get_settings( 'performanceMode', false ) ) {
                        add_filter(
                            'script_loader_tag',
                            [ $this, 'add_defer_attribute' ],
                            10,
                            2
                        );
                    }
                } );
                add_action( 'wp_footer', array( $this, 'render_floating_toggle' ) );
                add_action( 'login_footer', array( $this, 'render_floating_toggle' ) );
                add_action(
                    'wp_nav_menu_items',
                    [ $this, 'add_menu_toggle' ],
                    10,
                    2
                );
                // Add page transition animation
                add_filter( 'body_class', [ $this, 'add_page_transition_class' ] );
            }
        
        }
    }
    
    public function add_page_transition_class( $classes )
    {
        $transition = dracula_get_settings( 'pageTransition', 'none' );
        $classes[] = "dracula-transition-{$transition}";
        return $classes;
    }
    
    public function print_custom_css()
    {
        //Light Mode CSS
        $css = dracula_get_settings( 'lightModeCSS' );
        if ( !empty($css) ) {
            echo  '<style type="text/css" id="dracula-light-mode-css">' . $css . '</style>' ;
        }
    }
    
    public function add_menu_toggle( $items, $args )
    {
        $display_in_menu = dracula_get_settings( 'displayInMenu', false );
        if ( !$display_in_menu ) {
            return $items;
        }
        $toggleMenus = dracula_get_settings( 'toggleMenus', [] );
        $menu_id = $args->menu->slug;
        
        if ( in_array( $menu_id, $toggleMenus ) ) {
            $position = dracula_get_settings( 'menuTogglePosition', 'end' );
            $style = dracula_get_settings( 'menuToggleStyle', '14' );
            $class = 'dracula-toggle-wrap menu-item';
            $id = '';
            if ( strpos( $style, 'layout-' ) !== false ) {
                $id = str_replace( 'layout-', '', $style );
            }
            
            if ( !empty($id) ) {
                $class .= " custom-toggle";
                $toggle = Dracula_Toggle_Builder::instance()->get_toggle( $id );
                
                if ( !empty($toggle->config) ) {
                    $data = unserialize( $toggle->config );
                    $item = sprintf(
                        '<li class="%s" data-id="%s"><script type="application/json">%s</script> </li>',
                        $class,
                        $id,
                        json_encode( $data )
                    );
                }
            
            } else {
                $item = '<li class="dracula-toggle-wrap menu-item" data-style="' . $style . '"></li>';
            }
            
            
            if ( dracula_page_excluded() || dracula_taxonomy_excluded() ) {
                $items;
            } else {
                
                if ( 'start' == $position ) {
                    $items = $item . $items;
                } else {
                    $items .= $item;
                }
            
            }
        
        }
        
        return $items;
    }
    
    public function add_defer_attribute( $tag, $handle )
    {
        if ( in_array( $handle, array( 'dracula-dark-mode', 'dracula-frontend' ) ) ) {
            $tag = str_replace( ' src', ' defer src', $tag );
        }
        return $tag;
    }
    
    public function header_scripts()
    {
        $is_active = dracula_get_settings( 'frontendDarkMode', true ) && !dracula_page_excluded();
        $is_active_tax = dracula_get_settings( 'frontendDarkMode', true ) && !dracula_taxonomy_excluded();
        if ( !$is_active || !$is_active_tax ) {
            return;
        }
        //TODO - Implement includes elements later
        //if ( ! empty( dracula_get_includes() ) ) {return;}
        $timeBasedMode = dracula_get_settings( 'timeBasedMode', false );
        $timeBasedModeStart = dracula_get_settings( 'timeBasedModeStart', '19:00' );
        $timeBasedModeEnd = dracula_get_settings( 'timeBasedModeEnd', '07:00' );
        $config = dracula_get_config();
        $is_default_mode = dracula_get_settings( 'defaultDarkMode', false );
        $is_auto = dracula_get_settings( 'matchOsTheme', true );
        $url_parameter = dracula_get_settings( 'urlParameter', false );
        // Scrollbar Settings
        $scrollbar_dark_mode = dracula_get_settings( 'scrollbarDarkMode', 'auto' );
        if ( 'disabled' == $scrollbar_dark_mode ) {
            printf(
                '%1$s  %2$s %3$s',
                '<style id="dracula-scrollbar-css">',
                dracula_add_dark_mode_selector_prefix( 'body::-webkit-scrollbar {width: 12px;}body::-webkit-scrollbar-track {background: #f0f0f0;}body::-webkit-scrollbar-thumb {background-color: #c1c1c1;border-radius: 6px;border: 3px solid #f0f0f0;}' ),
                '</style>'
            );
        }
        ?>

        <script>

            window.draculaCrossTabSession = {
                /**
                 * Initialize listeners for cross-tab session management.
                 */
                init: function () {
                    window.addEventListener("storage", this.sessionStorageTransfer.bind(this));
                    if (!sessionStorage.length) {
                        localStorage.setItem('getSessionStorage', 'init');
                        localStorage.removeItem('getSessionStorage');
                    }
                },

                /**
                 * Handle the transfer of sessionStorage between tabs.
                 */
                sessionStorageTransfer: function (event) {
                    if (!event.newValue) return;

                    switch (event.key) {
                        case 'getSessionStorage':
                            this.sendSessionStorageToTabs();
                            break;
                        case 'sessionStorage':
                            if (!sessionStorage.length) {
                                this.receiveSessionStorageFromTabs(event.newValue);
                            }
                            break;
                    }
                },

                /**
                 * Send current sessionStorage to other tabs.
                 */
                sendSessionStorageToTabs: function () {
                    localStorage.setItem('sessionStorage', JSON.stringify(sessionStorage));
                    localStorage.removeItem('sessionStorage');
                },

                /**
                 * Populate current tab's sessionStorage with data from another tab.
                 */
                receiveSessionStorageFromTabs: function (dataValue) {
                    const data = JSON.parse(dataValue);
                    for (let key in data) {
                        sessionStorage.setItem(key, data[key]);
                    }
                },

                /**
                 * Set data to sessionStorage and share it across tabs.
                 */
                set: function (key, value) {
                    sessionStorage.setItem(key, value);
                    this.sendSessionStorageToTabs();
                },

                /**
                 * Get data from sessionStorage.
                 */
                get: function (key) {
                    return sessionStorage.getItem(key);
                }
            };

            window.draculaCrossTabSession.init();
        </script>

        <script>

            function initDraculaDarkMode() {
                var ignoreEvent = false;

                if (!!<?php 
        echo  json_encode( $is_default_mode ) ;
        ?>) {
                    window.draculaMode = 'dark';
                }

                const savedMode = localStorage.getItem('dracula_mode');

                if (savedMode) {
                    window.draculaMode = savedMode;
                }

                if ('dark' === window.draculaMode) {
                    window.draculaDarkMode.enable(<?php 
        echo  json_encode( $config ) ;
        ?>);
                } else if ('auto' === savedMode || (!!<?php 
        echo  json_encode( $is_auto ) ;
        ?> && !savedMode)) {
                    ignoreEvent = true;
                    window.draculaDarkMode.auto(<?php 
        echo  json_encode( $config ) ;
        ?>);
                }

                // Time based mode
                if (!!<?php 
        echo  json_encode( $timeBasedMode ) ;
        ?> && !savedMode) {
                    const start = '<?php 
        echo  sanitize_text_field( $timeBasedModeStart ) ;
        ?>';
                    const end = '<?php 
        echo  sanitize_text_field( $timeBasedModeEnd ) ;
        ?>';

                    const currentTime = new Date();
                    const startTime = new Date();
                    const endTime = new Date();

                    // Splitting the start and end times into hours and minutes
                    const startParts = start.split(':');
                    const endParts = end.split(':');

                    // Setting hours and minutes for start time
                    startTime.setHours(parseInt(startParts[0], 10), parseInt(startParts[1] || '0', 10), 0);

                    // Setting hours and minutes for end time
                    endTime.setHours(parseInt(endParts[0], 10), parseInt(endParts[1] || '0', 10), 0);

                    // Adjust end time to the next day if end time is earlier than start time
                    if (endTime <= startTime) {
                        endTime.setDate(endTime.getDate() + 1);
                    }

                    // Check if current time is within the range
                    if (currentTime >= startTime && currentTime < endTime) {
                        ignoreEvent = true;
                        window.draculaDarkMode.enable(<?php 
        echo  json_encode( $config ) ;
        ?>);
                    }
                }

                // URL Parameter
                if (!!<?php 
        echo  json_encode( $url_parameter ) ;
        ?>) {
                    const urlParams = new URLSearchParams(window.location.search);
                    const mode = urlParams.get('darkmode');

                    if (mode) {
                        ignoreEvent = true;

                        if ('1' === mode) {
                            window.draculaDarkMode.enable(<?php 
        echo  json_encode( $config ) ;
        ?>);
                        } else if ('0' === mode) {
                            window.draculaMode = '';
                            window.draculaDarkMode.disable();
                        }
                    }
                }

                if (window.draculaDarkMode.isEnabled()) {
                    window.draculaMode = 'dark';
                    jQuery(document).ready(function () {

                        // Send dark mode page view analytics event
                        if (dracula.isPro && dracula.settings.enableAnalytics) {
                            wp.ajax.post('dracula_track_analytics', {type: 'dark_view'});
                        }

                        // Fire enable event
                        if (!ignoreEvent) {
                            const event = new CustomEvent('dracula:enable', {detail: {init: true}});
                            document.dispatchEvent(event);
                        }

                    });
                }
            }

            if (<?php 
        echo  json_encode( dracula_get_settings( 'performanceMode', false ) ) ;
        ?>) {
                jQuery(document).ready(initDraculaDarkMode);
            } else {
                initDraculaDarkMode();
            }

        </script>
	<?php 
    }
    
    public function render_floating_toggle()
    {
        $show_toggle = dracula_get_settings( 'showToggle', true );
        if ( !$show_toggle ) {
            return;
        }
        $display_on = dracula_get_settings( 'floatingDevices', [ 'mobile', 'tablet', 'desktop' ] );
        $is_mobile = wp_is_mobile();
        $is_tablet = dracula_is_tablet();
        $is_desktop = !$is_mobile && !$is_tablet;
        if ( $is_mobile && !in_array( 'mobile', $display_on ) || $is_tablet && !in_array( 'tablet', $display_on ) || $is_desktop && !in_array( 'desktop', $display_on ) ) {
            return;
        }
        $style = dracula_get_settings( 'toggleStyle', '1' );
        $id = '';
        if ( strpos( $style, 'custom-' ) !== false ) {
            $id = str_replace( 'custom-', '', $style );
        }
        // check is return on render toggle
        if ( dracula_page_excluded() ) {
            return;
        }
        if ( dracula_taxonomy_excluded() ) {
            return;
        }
        echo  do_shortcode( "[dracula_toggle style='{$style}' id='{$id}' floating=1 ]" ) ;
    }
    
    /**
     * Render Template
     * @reading_mode
     */
    public function dracula_reading_mode()
    {
        if ( empty($_GET['reading-mode']) ) {
            return;
        }
        include_once DRACULA_TEMPLATES . '/reading-mode.php';
        exit;
    }
    
    /**
     * Position placement
     * @reading_mode
     */
    public function add_positions()
    {
        $post_id = get_the_ID();
        if ( !dracula_reading_mode_should_render( $post_id ) ) {
            return false;
        }
        // check reading mode enable
        $readingMode = dracula_get_settings( 'readingMode' );
        // check & return reading mode button
        if ( dracula_reading_mode_excluded() ) {
            return;
        }
        if ( dracula_reading_mode_taxonomy_excluded() ) {
            return;
        }
        
        if ( !!$readingMode ) {
            if ( !is_front_page() && !is_home() ) {
                add_filter(
                    'the_title',
                    array( $this, 'title_content' ),
                    10,
                    2
                );
            }
            
            if ( is_singular() ) {
                if ( !is_front_page() && !is_home() ) {
                    add_filter( 'the_content', [ $this, 'content_single' ] );
                }
            } else {
                if ( is_home() || is_archive() || is_search() ) {
                    add_filter( 'get_the_excerpt', array( $this, 'content_archive' ) );
                }
            }
            
            add_filter( 'comments_template', array( $this, 'remove_comments_title_content' ) );
        }
    
    }
    
    public function remove_comments_title_content( $theme_template )
    {
        remove_filter( 'the_title', array( $this, 'title_content' ) );
        return $theme_template;
    }
    
    /**
     * Title content
     * @reading_mode
     */
    public function title_content( $title, $id )
    {
        
        if ( in_the_loop() ) {
            
            if ( is_singular() ) {
                $current_object = get_queried_object();
                $post_id = $current_object->ID;
            } else {
                $post_id = get_the_ID();
            }
            
            // If not the same post, return.
            if ( $id != $post_id ) {
                return $title;
            }
            $title_prefix = '';
            $title_suffix = '';
            $button_position = dracula_get_settings( 'buttonPosition', 'aboveContent' );
            // Reading Mode Button
            if ( dracula_should_show_button() ) {
                
                if ( $button_position == 'aboveTitle' ) {
                    $title_prefix .= dracula_reading_mode_get_button_html( $post_id );
                } elseif ( $button_position == 'belowTitle' ) {
                    $title_suffix .= dracula_reading_mode_get_button_html( $post_id );
                }
            
            }
            return '<span class="reading-mode-buttons">' . $title_prefix . '</span>' . $title . '<span class="reading-mode-buttons">' . $title_suffix . '</span>';
        }
        
        return $title;
    }
    
    /**
     * Content Single
     * @reading_mode
     */
    public function content_single( $content )
    {
        $excludeReadingModePages = dracula_get_settings( 'excludeReadingModePages', [] );
        $excludeReadingModeAll = dracula_get_settings( 'excludeReadingModeAll' );
        $excludeReadingModeExceptPages = dracula_get_settings( 'excludeReadingModeExceptPages', [] );
        
        if ( in_the_loop() ) {
            $post_id = get_the_ID();
            $content_prefix = '';
            // Reading Time
            
            if ( dracula_should_show_time() ) {
                $time_position = dracula_get_settings( 'timePosition', 'aboveTitle' );
                if ( $time_position === 'aboveContent' ) {
                    $content_prefix .= dracula_reading_mode_get_time_html( $post_id );
                }
            }
            
            $button_position = dracula_get_settings( 'buttonPosition', 'aboveContent' );
            // Reading Mode Button
            if ( dracula_should_show_button() ) {
                if ( $button_position === 'aboveContent' ) {
                    if ( !$excludeReadingModeAll && !in_array( $post_id, $excludeReadingModePages ) || $excludeReadingModeAll && in_array( $post_id, $excludeReadingModeExceptPages ) ) {
                        $content_prefix .= dracula_reading_mode_get_button_html( $post_id );
                    }
                }
            }
            return '<span class="reading-mode-buttons">' . $content_prefix . '</span><div class="reading-mode-content">' . $content . '</div>';
        }
        
        return $content;
    }
    
    public function content_archive( $excerpt )
    {
        
        if ( in_the_loop() ) {
            $post_id = get_the_ID();
            $content_prefix = '';
            // Reader Mode Button
            
            if ( dracula_should_show_button() ) {
                $button_position = dracula_get_settings( 'buttonPosition', 'aboveContent' );
                if ( $button_position === 'aboveContent' ) {
                    $content_prefix .= dracula_reading_mode_get_button_html( $post_id );
                }
            }
            
            return '<span class="reading-mode-buttons">' . $content_prefix . '</span>' . $excerpt;
        }
        
        return $excerpt;
    }
    
    public static function instance()
    {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

}
Dracula_Hooks::instance();