<div class="wrap container-fluid atp-container">

        <?php 
Pagup\Twitter\Core\Plugin::view( 'inc/top', compact( 'active_tab' ) );
?>

        <div class="row">

            <div id="atp-app" class="col-xs-8 col-main">

                <form method="post" class="atp-form">
                        
                <?php 
if ( function_exists( 'wp_nonce_field' ) ) {
    wp_nonce_field( 'atp-settings', 'atp-nonce' );
}
?>

                <div class="atp-segment">

                    <h2><?php 
echo  __( 'About Custom Events?', $text_domain ) ;
?></h2>

                    <?php 
?>

                        <p><strong><?php 
echo  sprintf( wp_kses( __( 'Twitter pixel plugin allows you to easily create custom events on specific pages with a few clicks (PRO version only). Don\'t forget to check on your pages to find META BOX feature under WordPress post editor. If you have any doubt, please refer to <a href="%s" target="_blank">Twitter documentation</a>. Enjoy.', $text_domain ), array(
    'a' => array(
    'href'   => array(),
    'target' => array(),
),
) ), esc_url( 'https://business.twitter.com/en/help/campaign-measurement-and-analytics/conversion-tracking-for-websites.html' ) ) ;
?></strong></p>

                    <?php 
?>

                </div>

                <div class="atp-segment">

                    <h2 style="margin-bottom: 2em;"><?php 
echo  __( 'Twitter Pixel', $text_domain ) ;
?></h2>

                    <div class="row">
                        <div class="col-xs-3">
                            <label class="atp-label" for="twitter_id">
                                <strong>
                                    <?php 
echo  __( 'Twitter Tracking ID', $text_domain ) ;
?>
                                </strong>
                            </label>
                        </div>

                        <div class="col-xs-9">
                            <input type="text" id="twitter_id" class="atp-input" name="twitter_id" value="<?php 
if ( $options::check( 'twitter_id' ) ) {
    echo  $options::get( 'twitter_id' ) ;
}
?>" placeholder="Enter Your Twitter Tracking ID"/>

                            <p class="atp-comment"><?php 
echo  sprintf( wp_kses( __( 'Please check <a href="%s" target="_blank">here</a> to learn how to create a Twitter Tracking ID.', $text_domain ), array(
    'a' => array(
    'href'   => array(),
    'target' => array(),
),
) ), esc_url( 'https://business.twitter.com/en/help/campaign-measurement-and-analytics/conversion-tracking-for-websites.html' ) ) ;
?></p>
                        </div>

                    </div>

                </div>

                <div class="atp-segment">

                    <h2 style="margin-bottom: 2em;"><?php 
echo  __( 'Twitter Pixel on WooCommerce', $text_domain ) ;
?></h2>
            
                    <div class="row">

                        <div class="col-xs-3">
                            <label class="atp-label" for="enable_on_products">
                                <strong>
                                    <?php 
echo  __( 'Enable on product, cart & checkout pages', $text_domain ) ;
?>
                                </strong>
                            </label>
                        </div>
            
                        <div class="col-xs-9">

                        <?php 
?>

                            <label class="atp-toggle"><input id="enable_on_products" type="checkbox" name="enable_on_products" value="enable_on_products" disabled />
                            <span class='atp-toggle-slider atp-toggle-round'></span></label>

                        <?php 
?>

                        <?php 
?>
                            
                            <div class="atp-alert atp-info">
                                <span class="closebtn">&times;</span> 
                                <?php 
echo  $get_pro . " " . __( 'on Woocommerce product pages.', $text_domain ) ;
?>
                            </div>

                        <?php 
?>
                        </div>

                    </div>

                </div>
                
                <?php 
?>
                <div class="atp-alert atp-note" style="padding: 15px 20px; font-size: 16px">
                    <span class="closebtn">&times;</span> 
                    <?php 
echo  $get_pro . " " . sprintf( wp_kses( __( '"custom/single event" tracking with a <a href="%s" target="_blank">META BOX feature</a>', $text_domain ), array(
    'a' => array(
    'href'   => array(),
    'target' => array(),
),
) ), esc_url( ATP_PLUGIN_DIR . '/admin/assets/metabox.png' ) ) ;
?>
                </div>
                <?php 
?>

                <div class="atp-alert atp-note" style="padding: 15px 20px; font-size: 16px">
                    <span class="closebtn">&times;</span> 
                    <?php 
echo  __( '<strong>Allow Twitter from crawling your website:</strong> ', $text_domain ) . sprintf( wp_kses( __( 'Optimize your <a href="%s" target="_blank">robots.txt</a> for Twitter crawlers. (<a href="%2s" target="_blank">HERE</a>)', $text_domain ), array(
    'a' => array(
    'href'   => array(),
    'target' => array(),
),
), array(
    'a' => array(
    'href'   => array(),
    'target' => array(),
),
) ), esc_url( 'https://wordpress.org/plugins/better-robots-txt/' ), esc_url( ATP_PLUGIN_DIR . '/admin/assets/better-robots.jpg' ) ) ;
?>
                </div>

                <div class="atp-segment">
            
                    <div class="row">

                        <div class="col-xs-2">
                            <label class="atp-label" for="remove_settings">
                                <strong>
                                    <?php 
echo  __( 'Remove Settings', $text_domain ) ;
?>
                                </strong>
                            </label>
                        </div>
            
                        <div class="col-xs-2">
                            <label class="atp-toggle"><input id="remove_settings" type="checkbox" name="remove_settings" value="remove_settings"
                            <?php 
if ( $options::check( 'remove_settings' ) ) {
    echo  'checked' ;
}
?> />
                            <span class='atp-toggle-slider atp-toggle-round'></span></label>
                        </div>
            
                        <div class="col-xs-8 field">
                            <input type="submit" name="update" class="atp-submit" value="<?php 
echo  esc_html__( 'Save Changes', $text_domain ) ;
?>" />
                        </div>
            
                    </div>
                
                </div>

                <div class="atp-segment">
                    
                    <p><?php 
echo  __( "<strong>Note:</strong> once the codes are added, make sure to clear your cache. Then, you can create your conversion event. That is, you're ready to tell your tag what you want it to track.", $text_domain ) ;
?></p>

                </div>
            
            </form>

        </div>

        <div class="col-xs-4 atp-side">

            <?php 
Pagup\Twitter\Core\Plugin::view( 'inc/side', compact( 'text_domain' ) );
?>

        </div>