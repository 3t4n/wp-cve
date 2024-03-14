<div class="wrap pctag-containter">

    <?php 
Pagup\Pctag\Core\Plugin::view( 'inc/top', compact( 'active_tab' ) );
?>

    <!-- start main settings column -->
    <div class="pctag-row">
        <div class="pctag-column col-9">
            <div class="pctag-main">
                <form method="post">

                    <?php 
if ( function_exists( 'wp_nonce_field' ) ) {
    wp_nonce_field( 'pctag-settings', 'pctag-nonce' );
}
?>
                    
                    <br />

                    <div class="pctag-note">
                        <h3><?php 
echo  esc_html__( 'PINTEREST CONVERSION TAGS, how does it work?', "add-pinterest-conversion-tags" ) ;
?></h3>
                        <p><?php 
echo  sprintf( wp_kses( __( 'Pinterest conversion tag plugin allows you to easily add your Pinterest base code everywhere on your website but also to create events on specific pages with a few clicks. Don\'t forget to check on your pages to find META BOX feature under WordPress post editor. If you have any doubt, please refer to <a href="%s" target="_blank">Pinterest documentation</a>. Enjoy', "add-pinterest-conversion-tags" ), array(
    'a' => array(
    'href'   => array(),
    'target' => array(),
),
) ), esc_url( 'https://developers.pinterest.com/docs/ad-tools/conversion-tag/' ) ) ;
?></p>
                    </div>
                    
                    <div class="pctag-segment">

                        <h2><?php 
echo  esc_html__( 'Pinterest Conversion Tags', "add-pinterest-conversion-tags" ) ;
?></h2>

                        <div class="pctag-row">

                            <div class="pctag-column col-4">
                                <span class="pctag-label"><?php 
echo  esc_html__( 'Enable Pinterest Conversion Tag', "add-pinterest-conversion-tags" ) ;
?></span>
                            </div>

                            <div class="pctag-column col-8">
                                <label class="pctag-switch">
                                    <input type="checkbox" id="enable_pctag" name="enable_pctag" value="enable_pctag"
                                    <?php 
if ( $options::check( 'enable_pctag' ) ) {
    echo  'checked' ;
}
?> />
                                    <span class="pctag-slider"></span>
                                </label>
                                &nbsp;
                                <span><?php 
echo  esc_html__( 'This feature will add the Pinterest base code to all pages', "add-pinterest-conversion-tags" ) ;
?></span>
                            </div>

                        </div>

                        <div class="pctag-row">

                            <div class="pctag-column col-4">
                                <span class="pctag-label"><?php 
echo  esc_html__( 'Enter Pinterest Conversion Tag ID', "add-pinterest-conversion-tags" ) ;
?></span>
                                <div class="pctag-tooltip">
                                    <span class="dashicons dashicons-editor-help"></span>
                                    <span class="pctag-tooltiptext"><?php 
echo  __( 'Please refer to FAQ section : How to find your TAG ID - Please do NOT enter anything else BUT your TAG ID', "add-pinterest-conversion-tags" ) ;
?></span>
                                </div>
                            </div>

                            <div class="pctag-column col-8">
                                <input type="text" name="pctag_id" id="pctag_id" class="pctag-field" value="<?php 
if ( $options::check( 'pctag_id' ) ) {
    echo  $options::get( 'pctag_id' ) ;
}
?>" placeholder="1234567890">

                                <?php 

if ( $options::check( 'enable_pctag' ) && !$options::check( 'pctag_id' ) ) {
    ?>

                                    <div class="pctag-alert pctag-warning"><span class="closebtn">&times;</span><?php 
    echo  __( 'It seems you enabled Pinterest Conversion Tag above but forgot to enter TAG ID. Please make sure to enter TAG ID otherwise it won\'t work.', "add-pinterest-conversion-tags" ) ;
    ?></div>

                                <?php 
}

?>
                            </div>

                        </div>

                        <div class="pctag-row">

                            <div class="pctag-column col-4">
                                <span class="pctag-label"><?php 
echo  esc_html__( 'Claim your website', "add-pinterest-conversion-tags" ) ;
?></span>
                                <div class="pctag-tooltip">
                                    <span class="dashicons dashicons-editor-help"></span>
                                    <span class="pctag-tooltiptext"><?php 
echo  __( 'To verify your website’s authenticity, please add pinterest meta tag to claim your website', "add-pinterest-conversion-tags" ) ;
?></span>
                                </div>
                            </div>

                            <div class="pctag-column col-8">
                            <?php 
echo  sprintf( wp_kses( __( 'Add your Pinterest Meta Tag with our <a href="%s" target="_blank">Meta tags for SEO</a> plugin', 'add-pinterest-conversion-tags' ), array(
    'a' => array(
    'href'   => array(),
    'target' => array(),
),
) ), esc_url( 'https://wordpress.org/plugins/meta-tags-for-seo/' ) ) ;
?>
                                
                            </div>

                        </div>

                    </div>

                    <div class="pctag-segment">

                        <h2><?php 
echo  esc_html__( 'PINTEREST CONVERSION EVENTS', "add-pinterest-conversion-tags" ) ;
?></h2>
                        
                        <div class="pctag-row">

                            <div class="pctag-column col-4">
                                <span class="pctag-label"><?php 
echo  esc_html__( 'Add Cart Event', "add-pinterest-conversion-tags" ) ;
?></span>

                                <div class="pctag-tooltip">
                                    <span class="dashicons dashicons-editor-help"></span>
                                    <span class="pctag-tooltiptext"><?php 
echo  sprintf( wp_kses( __( 'This feature will add a "addtocart" event on your <a href="%s" target="_blank">CART PAGE</a>, just after your base code', 'add-pinterest-conversion-tags' ), array(
    'a' => array(
    'href'   => array(),
    'target' => array(),
),
) ), esc_url( PCTAG_PLUGIN_DIR . '/admin/assets/imgs/code.jpg' ) ) ;
?></span>
                                </div>
                            </div>

                            <div class="pctag-column col-8">

                            <?php 
// free only
?>

                                    <label class="pctag-switch">
                                        <input type="checkbox" id="addtocart_event" value="addtocart_event"
                                        disabled ?> />
                                        <span class="pctag-slider"></span>
                                    </label>

                                <?php 
?>

                            </div>

                        </div>
                        
                        <div class="pctag-row">

                            <div class="pctag-column col-4">
                                <span class="pctag-label"><?php 
echo  esc_html__( 'Add Checkout Event', "add-pinterest-conversion-tags" ) ;
?></span>
                                <div class="pctag-tooltip">
                                    <span class="dashicons dashicons-editor-help"></span>
                                    <span class="pctag-tooltiptext"><?php 
echo  sprintf( wp_kses( __( 'This feature will add a "checkout" event on your <a href="%s" target="_blank">CHECKOUT PAGE</a>, just after your base code', 'add-pinterest-conversion-tags' ), array(
    'a' => array(
    'href'   => array(),
    'target' => array(),
),
) ), esc_url( PCTAG_PLUGIN_DIR . '/admin/assets/imgs/code.jpg' ) ) ;
?></span>
                                </div>  
                            </div>

                            <div class="pctag-column col-8">

                            <?php 
// free only
?>

                                    <label class="pctag-switch">
                                        <input type="checkbox" id="checkout_event" value="checkout_event"
                                        disabled ?> />
                                        <span class="pctag-slider"></span>
                                    </label>

                                <?php 
?>

                            </div>

                        </div>
                        
                        <div class="pctag-row">

                            <div class="pctag-column col-4">
                                <span class="pctag-label"><?php 
echo  esc_html__( 'Add Search Event', "add-pinterest-conversion-tags" ) ;
?></span>
                                <div class="pctag-tooltip">
                                    <span class="dashicons dashicons-editor-help"></span>
                                    <span class="pctag-tooltiptext"><?php 
echo  __( 'This feature will add a "search" event on all pages, just after your pinterest conversion base code', "add-pinterest-conversion-tags" ) ;
?></span>
                                </div>
                            </div>

                            <div class="pctag-column col-8">

                            <?php 
// free only
?>

                                    <label class="pctag-switch">
                                        <input type="checkbox" id="search_event" value="search_event"
                                        disabled ?> />
                                        <span class="pctag-slider"></span>
                                    </label>

                            <?php 
?>

                            </div>

                        </div>

                        <div class="pctag-row">

                            <div class="pctag-column col-4">
                                <span class="pctag-label"><?php 
echo  esc_html__( 'Add ViewCategory Event', "add-pinterest-conversion-tags" ) ;
?></span>
                                <div class="pctag-tooltip">
                                    <span class="dashicons dashicons-editor-help"></span>
                                    <span class="pctag-tooltiptext"><?php 
echo  sprintf( wp_kses( __( 'This feature will add a "ViewCategory" event on your <a href="%s" target="_blank">Category pages</a>, just after your base code', 'add-pinterest-conversion-tags' ), array(
    'a' => array(
    'href'   => array(),
    'target' => array(),
),
) ), esc_url( PCTAG_PLUGIN_DIR . '/admin/assets/imgs/code.jpg' ) ) ;
?></span>
                                </div>  
                            </div>

                            <div class="pctag-column col-8">

                            <?php 
// free only
?>

                                    <label class="pctag-switch">
                                        <input type="checkbox" id="viewCategory_event" value="viewCategory_event"
                                        disabled ?> />
                                        <span class="pctag-slider"></span>
                                    </label>

                                <?php 
?>

                            </div>

                            </div>

                        
                        <?php 
//pro only
?>
                            <div class="pctag-alert pctag-info">
                                <span class="closebtn">&times;</span> 
                                <?php 
echo  $get_pro . " " . __( 'add to cart event, checkout event and search event.', "add-pinterest-conversion-tags" ) ;
?>
                            </div>
                        <?php 
?>
                        
                    </div>
                    

                    <?php 
// free only
?>

                        <div class="pctag-alert pctag-info"><span class="closebtn">&times;</span><?php 
echo  $get_pro . sprintf( wp_kses( __( ' "PageVisit", "Signup", "WatchVideo" & "Lead" events, which are managed with a &nbsp;<a href="%s" target="_blank">META BOX feature</a>', 'add-pinterest-conversion-tags' ), array(
    'a' => array(
    'href'   => array(),
    'target' => array(),
),
) ), esc_url( PCTAG_PLUGIN_DIR . '/admin/assets/imgs/meta-box.png' ) ) ;
?></div>

                    <?php 
?>

                    <div class="pctag-segment">

                        <div class="pctag-row">

                        <div class="pctag-column col-4">
                            <span class="pctag-label"><?php 
echo  __( 'Boost your ranking on Search engines', "add-pinterest-conversion-tags" ) ;
?></span>
                        </div>
                        
                        <div class="pctag-column col-8">
                            
                        <label class="pctag-switch pctag-boost-robot-label">
                            <input type="checkbox" id="boost-robot" name="boost-robot" value="boost-robot" <?php 
if ( $options::check( 'boost-robot' ) ) {
    echo  'checked="checked"' ;
}
?> />
                            <span class="pctag-slider"></span>
                        </label>

                        &nbsp; <span><?php 
echo  __( 'Optimize site\'s crawlability with an optimized robots.txt', "add-pinterest-conversion-tags" ) ;
?></span>
                            
                            <div class="pctag-boost-robot" <?php 

if ( $options::check( 'boost-robot' ) ) {
    echo  'style="display: inline;"' ;
} else {
    echo  'style="display: none;"' ;
}

?>>

                            <div class="pctag-alert pctag-success" style="margin-top: 10px;"><?php 
echo  sprintf( wp_kses( __( 'Click <a href="%s" target="_blank">HERE</a> to Install <a href="%2s" target="_blank">Better Robots.txt plugin</a> to boost your robots.txt', "add-pinterest-conversion-tags" ), array(
    'a' => array(
    'href'   => array(),
    'target' => array(),
),
    'a' => array(
    'href'   => array(),
    'target' => array(),
),
) ), esc_url( "https://wordpress.org/plugins/better-robots-txt/" ), esc_url( "https://wordpress.org/plugins/better-robots-txt/" ) ) ;
?>
                                </div>
                            </div>
                        </div>
                        
                    </div>

                    <div class="pctag-row">

                        <div class="pctag-column col-4">
                            <span class="pctag-label"><?php 
echo  __( 'Boost your Alt texts', "add-pinterest-conversion-tags" ) ;
?></span>
                        </div>
                        
                        <div class="pctag-column col-8">
                            
                        <label class="pctag-switch pctag-boost-alt-label">
                            <input type="checkbox" id="boost-alt" name="boost-alt" value="boost-alt" <?php 
if ( $options::check( 'boost-alt' ) ) {
    echo  'checked="checked"' ;
}
?> />
                            <span class="pctag-slider"></span>
                        </label>

                            &nbsp; <span><?php 
echo  __( 'Boost your ranking with optimized Alt tags', "add-pinterest-conversion-tags" ) ;
?></span>
                            
                            <div class="pctag-boost-alt" <?php 

if ( $options::check( 'boost-alt' ) ) {
    echo  'style="display: inline;"' ;
} else {
    echo  'style="display: none;"' ;
}

?>>

                                <div class="pctag-alert pctag-success" style="margin-top: 10px;"><?php 
echo  sprintf( wp_kses( __( 'Click <a href="%s" target="_blank">HERE</a> to Install <a href="%2s" target="_blank">BIALTY Wordpress plugin</a> & auto-optimize all your alt texts for FREE', "add-pinterest-conversion-tags" ), array(
    'a' => array(
    'href'   => array(),
    'target' => array(),
),
    'a' => array(
    'href'   => array(),
    'target' => array(),
),
) ), esc_url( "https://wordpress.org/plugins/bulk-image-alt-text-with-yoast/" ), esc_url( "https://wordpress.org/plugins/bulk-image-alt-text-with-yoast/" ) ) ;
?>
                                </div>
                            </div>
                        </div>
                        
                    </div>

                    <div class="pctag-row">

                        <div class="pctag-column col-4">
                            <span class="pctag-label"><?php 
echo  __( 'Mobile-Friendly & responsive design', "add-pinterest-conversion-tags" ) ;
?></span>
                        </div>
                        
                        <div class="pctag-column col-8">
                            
                        <label class="pctag-switch pctag-mobi-label">
                            <input type="checkbox" id="pctag-mobilook" name="pctag-mobilook" value="pctag-mobilook" <?php 
if ( $options::check( 'pctag-mobilook' ) ) {
    echo  'checked="checked"' ;
}
?> />
                            <span class="pctag-slider"></span>
                        </label>

                            &nbsp; <span><?php 
echo  __( 'Get dynamic mobile previews of your pages/posts/products + Facebook debugger', "add-pinterest-conversion-tags" ) ;
?></span>
                            
                            <div class="pctag-mobi" <?php 

if ( $options::check( 'pctag-mobilook' ) ) {
    echo  'style="display: inline;"' ;
} else {
    echo  'style="display: none;"' ;
}

?>>

                                <div class="pctag-alert pctag-success" style="margin-top: 10px;"><?php 
echo  sprintf( wp_kses( __( 'Click <a href="%s" target="_blank">HERE</a> to Install <a href="%2s" target="_blank">Mobilook</a> and test your website on Dualscreen format (Galaxy fold)', "add-pinterest-conversion-tags" ), array(
    'a' => array(
    'href'   => array(),
    'target' => array(),
),
    'a' => array(
    'href'   => array(),
    'target' => array(),
),
) ), esc_url( "https://wordpress.org/plugins/mobilook/" ), esc_url( "https://wordpress.org/plugins/mobilook/" ) ) ;
?>
                                </div>
                            </div>
                        </div>
                        
                    </div>
        
                    <div class="pctag-row">

                    <div class="pctag-column col-4">
                        <span class="pctag-label"><?php 
echo  __( 'Boost your image title attribute', "add-pinterest-conversion-tags" ) ;
?></span>
                    </div>

                    <div class="pctag-column col-8">

                    <label class="pctag-switch pctag-bigta-label">
                        <input type="checkbox" id="pctag-bigta" name="pctag-bigta" value="pctag-bigta" <?php 
if ( $options::check( 'pctag-bigta' ) ) {
    echo  'checked="checked"' ;
}
?> />
                        <span class="pctag-slider"></span>
                    </label>

                    &nbsp; <span><?php 
echo  __( 'Optimize all your image title attributes for UX & search engines performance', "add-pinterest-conversion-tags" ) ;
?></span>

                        <div class="pctag-bigta" <?php 

if ( $options::check( 'pctag-bigta' ) ) {
    echo  'style="display: inline;"' ;
} else {
    echo  'style="display: none;"' ;
}

?>>

                            <div class="pctag-alert pctag-success" style="margin-top: 10px;"><?php 
echo  sprintf( wp_kses( __( 'Click <a href="%s" target="_blank">HERE</a> to Install <a href="%2s" target="_blank">BIGTA</a> Wordpress plugin & auto-optimize all your image title attributes for FREE', "add-pinterest-conversion-tags" ), array(
    'a' => array(
    'href'   => array(),
    'target' => array(),
),
    'a' => array(
    'href'   => array(),
    'target' => array(),
),
) ), esc_url( "https://wordpress.org/plugins/bulk-image-title-attribute/" ), esc_url( "https://wordpress.org/plugins/bulk-image-title-attribute/" ) ) ;
?>
                            </div>
                        </div>
                    </div>

                </div>
  
            </div>

            <div class="pctag-segment">

                        <div class="pctag-row">

                            <div class="pctag-column col-4">
                                <span class="pctag-label"><?php 
echo  esc_html__( 'Delete Settings', "add-pinterest-conversion-tags" ) ;
?></span>
                            </div>

                            <div class="pctag-column col-8">
                                <label class="pctag-switch">
                                    <input type="checkbox" id="pctag_remove_settings" name="pctag_remove_settings" value="pctag_remove_settings"
                                    <?php 
if ( $options::check( 'pctag_remove_settings' ) ) {
    echo  'checked="checked"' ;
}
?> />
                                    <span class="pctag-slider"></span>
                                </label>
                                &nbsp;
                                <span><?php 
echo  esc_html__( 'Checking this box will remove all settings when you deactivate plugin.', "add-pinterest-conversion-tags" ) ;
?></span>
                            </div>

                        </div>

                    </div>

                
                    <p class="submit"><input type="submit" name="update" class="button-primary" value="<?php 
echo  esc_html__( 'Save Changes', "add-pinterest-conversion-tags" ) ;
?>" /></p>
                </form>

                <div class="pctag-note"><p><?php 
echo  __( "Note: Once the codes are added, clear your cache, give it 24 hours and then confirm in Pinterest Conversion Manager that the tags are properly implemented.", "add-pinterest-conversion-tags" ) ;
?></p></div>
    
            </div>
            <!-- end pctag-main -->
        </div>
        <!-- end main settings pctag-column col-8 -->

        <div class="pctag-column col-3 pctag-txt">

            <?php 
Pagup\Pctag\Core\Plugin::view( 'inc/side' );
?>

        </div>

    </div>

</div>