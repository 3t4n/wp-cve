<?php
/***
 * Implement the main component
 * and fire in on wp_footer
 */
function acwp_toolbar_component() {
    $noanimation = (get_option('acwp_no_toolbar_animation') == 'yes') ? 'acwp-noanimation' : '';
    $side = (get_option('acwp_toolbar_side') == 'right') ? 'acwp-right' : '';
    $style = 'acwp-style-default';
    
    if( get_option('acwp_toolbar_style') )
        $style = 'acwp-style-' . get_option('acwp_toolbar_style');
    ?>
    
    <div id="acwp-toolbar-btn-wrap" class="<?php echo $side . ' ' . $noanimation; ?>">
        <button type="button"id="acwp-toolbar-btn" tabindex="0" aria-label="<?php _e('Toggle Accessibility Toolbar', 'acwp');?>">
            <svg xmlns="http://www.w3.org/2000/svg" focusable="false" style="transform: rotate(360deg);" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 20 20">
                <path d="M10 2.6c.83 0 1.5.67 1.5 1.5s-.67 1.51-1.5 1.51c-.82 0-1.5-.68-1.5-1.51s.68-1.5 1.5-1.5zM3.4 7.36c0-.65 6.6-.76 6.6-.76s6.6.11 6.6.76s-4.47 1.4-4.47 1.4s1.69 8.14 1.06 8.38c-.62.24-3.19-5.19-3.19-5.19s-2.56 5.43-3.18 5.19c-.63-.24 1.06-8.38 1.06-8.38S3.4 8.01 3.4 7.36z" fill="currentColor"></path>
            </svg>
        </button>
    </div>
    
    <div id="acwp-toolbar" class="acwp-toolbar <?php echo $side . ' ' . $noanimation . ' ' . $style; ?>" aria-label="<?php _e('Accessibility Toolbar Toggle View', 'acwp'); ?>">
        <div id="acwp-toolbar-module">
            <?php acwp_toolbar_header(); ?>
            
            <div class="acwp-togglers">
                <?php
                
                if( get_option('acwp_hide_keyboard') != 'yes' )
                    acwp_toggler('keyboard', __('Keyboard Navigation', 'acwp'), 'keyboard');
                
                if( get_option('acwp_hide_animations') != 'yes' )
                    acwp_toggler('animations', __('Disable Animations', 'acwp'), 'visibility_off');
                
                if( get_option('acwp_hide_contrast') != 'yes' )
                    acwp_toggler('contrast', __('Contrast', 'acwp'), 'nights_stay');
                
                if( get_option('acwp_hide_fontsize') != 'yes' ) {
                    acwp_toggler('incfont', __('Increase Text', 'acwp'), 'format_size');
                    acwp_toggler('decfont', __('Decrease Text', 'acwp'), 'text_fields');
                }

                if( get_option('acwp_hide_readable') != 'yes' )
                    acwp_toggler('readable', __('Readable Font', 'acwp'), 'font_download');
                
                if( get_option('acwp_hide_titles') != 'yes' )
                    acwp_toggler('marktitles', __('Mark Titles', 'acwp'), 'title');
                
                if( get_option('acwp_hide_underline') != 'yes' )
                    acwp_toggler('underline', __('Highlight Links & Buttons', 'acwp'), 'link');
                
                ?>
            </div>
            
            <?php acwp_toolbar_footer(); ?>
        </div>
    </div>
    <?php
}
add_action('wp_footer', 'acwp_toolbar_component');

/**
 * Toolbar Header
 */
function acwp_toolbar_header(){
    $title_option = get_option('acwp_heading_title');
    $title = ( $title_option && $title_option != '' ) ? $title_option : __('Accessibility Toolbar', 'acwp');
    $button_label = __('Toggle the visibility of the Accessibility Toolbar', 'acwp');
    $icon_class = ( get_option('acwp_hide_icons') == 'yes' ) ? 'awp-close-icon' : 'material-icons';
    ?>
    <div class="acwp-heading">
        <p class="acwp-title"><?php echo $title; ?></p>
        <button type="button" id="acwp-close-toolbar">
            <i class="<?php echo $icon_class; ?>" aria-hidden="true"><?php if(get_option('acwp_hide_icons') != 'yes') echo 'close';?></i>
            <span class="sr-only"><?php echo $button_label;?></span>
        </button>
    </div>
    <?php
}

/**
 * Toolbar Footer
 */
function acwp_toolbar_footer() {
    $sitemap_link = get_option('acwp_sitemap');
    $sitemap_text = ( get_option('acwp_sitemap_label') && get_option('acwp_sitemap_label') != '') ? get_option('acwp_sitemap_label') : __('Site Map', 'acwp');
    
    $statement_link = get_option('acwp_statement');
    $statement_text = ( get_option('acwp_statement_label') && get_option('acwp_statement_label') != '') ? get_option('acwp_statement_label') : __('Accessibility Statement', 'acwp');
    
    $feedback_link = get_option('acwp_feedback');
    $feedback_text = ( get_option('acwp_feedback_label') && get_option('acwp_feedback_label') != '') ? get_option('acwp_feedback_label') : __('Send Feedback', 'acwp');
    $cnr = is_rtl() ? 'עמית' : 'Code';
    $cnr .= is_rtl() ? ' מורנו' : 'nroll';
    $url = is_rtl() ? 'https://amit' : 'https://www.code';
    $url .= is_rtl() ? 'moreno.com/' : 'nroll.co.il/';
    $icon = (get_option('acwp_hide_icons') != 'yes') ? '<i class="material-icons" aria-hidden="true">favorite</i><span class="sr-only">' . __('Love', 'acwp') . '</span>' : __('Love', 'acwp');
    ?>
    <div class="acwp-footer">
        <ul>
            <?php if($statement_link && $statement_link != '') : ?>
            <li><a href="<?php echo $statement_link; ?>"><?php echo $statement_text;?></a></li>
            <?php endif; ?>
            <?php if($feedback_link && $feedback_link != '') : ?>
            <li><a href="<?php echo $feedback_link; ?>"><?php echo $feedback_text;?></a></li>
            <?php endif; ?>
            <?php if($sitemap_link && $sitemap_link != '') : ?>
            <li><a href="<?php echo $sitemap_link; ?>"><?php echo $sitemap_text;?></a></li>
            <?php endif; ?>
            <li><?php _e('Powered with', 'acwp'); ?> <?php echo $icon;?> <?php _e('by', 'acwp');?> <a href="<?php echo $url;?>" target="_blank"><?php echo $cnr; ?></a></li>
        </ul>
    </div>
    <?php
}

/**
 * Toolbar Toggler
 */
function acwp_toggler($key = '', $label = '', $icon){
    $style = get_option('acwp_toolbar_style');
    ?>
    <div class="acwp-toggler acwp-toggler-<?php echo $key;?>">
        <label for="acwp-toggler-<?php echo $key; ?>" tabindex="0" data-name="<?php echo $key;?>">
            <?php if( isset($icon) && $icon != '' && get_option('acwp_hide_icons') != 'yes' ) : ?>
            <i class="material-icons" aria-hidden="true"><?php echo $icon;?></i>
            <?php endif; ?>
            <span><?php echo $label; ?></span>
            <?php if( !$style || $style == '' ) : ?>
            <div class="acwp-switcher">
                <input type="checkbox" id="acwp-toggler-<?php echo $key; ?>" hidden />
                <div class="acwp-switch"></div>
            </div>
            <?php else : ?>
            <input type="checkbox" id="acwp-toggler-<?php echo $key; ?>" hidden />
            <?php endif; ?>
        </label>
    </div>
    <?php
}

function acwp_iconsize_style(){
    $toolbar_fromtop = (get_option('acwp_toolbar_fromtop') && get_option('acwp_toolbar_fromtop') != '') ? get_option('acwp_toolbar_fromtop') : false;
    $toolbar_fromside = (get_option('acwp_toolbar_fromside') && get_option('acwp_toolbar_fromside') != '') ? get_option('acwp_toolbar_fromside') : false;
    $toolbar_side = get_option('acwp_toolbar_side');
    $the_side = ($toolbar_side == 'right') ? 'right' : 'left';
    ////
    $icon_fromtop = (get_option('acwp_toggle_fromtop') && get_option('acwp_toggle_fromtop') != '') ? get_option('acwp_toggle_fromtop') : '';
    $icon_fromside = (get_option('acwp_toggle_fromside') && get_option('acwp_toggle_fromside') != '') ? get_option('acwp_toggle_fromside') : '';
    $toolbar_stickness = get_option('acwp_toolbar_stickness');
    $vertical = $toolbar_stickness == 'bottom' ? 'bottom' : 'top';
$the_side = ($toolbar_side == 'right') ? 'right' : 'left';
    echo '<style>';

    // default
    echo 'body #acwp-toolbar-btn-wrap {'.$vertical.': 120px; '.$the_side.': 20px;}';
    
    if( $icon_fromtop != '' ){
        echo 'body.acwp-fromtop #acwp-toolbar-btn-wrap {'.$vertical.': '.$fromtop.'px;}';
    }
    if( $icon_fromside != '' ){
        
        echo 'body.acwp-fromtop #acwp-toolbar-btn-wrap {'.$the_side.': '.$fromside.'px;}';
    }

    // default
    echo '.acwp-toolbar{'.$vertical.': -100vh; '.$the_side.': 20px;}';
    echo '.acwp-toolbar.acwp-toolbar-show{'.$vertical.': 55px;}';

    if( $toolbar_fromtop ){
        echo '.acwp-toolbar.acwp-toolbar-show {'.$vertical.': '.$fromtop.'px;}';
    }
    if( $toolbar_fromside ){
        echo 'body .acwp-toolbar {'.$the_side.': '.$fromside.'px;}';
    }
    echo '</style>';
}
add_action('wp_footer', 'acwp_iconsize_style');

function acwp_set_custom_color(){
    $allow = get_option('acwp_custom_color_allow');
    if( isset($allow) && $allow == 'yes' ){
        $color = (get_option('acwp_custom_color') && get_option('acwp_custom_color') != '') ? get_option('acwp_custom_color') : '';

        if( $color != '' ){
            echo '<style>
                    #acwp-toolbar-btn-wrap {border-color: '.$color.'}
                    #acwp-toolbar .acwp-heading {background-color: '.$color.';}
                    body #acwp-toolbar-btn {background-color: '.$color.';}
                  </style>';
        }
    }
    
}
add_action('wp_footer', 'acwp_set_custom_color');
