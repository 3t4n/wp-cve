<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="container-fluid afkw-container">
    <div class="afkw-inner-container">
    
        <?php 
include 'inc/top.view.php';
?>

        <div class="row" style="margin: 0 0.2rem 0;">
            
            <?php 
echo  wp_kses_post( $updated ) ;
?>

            <div class="afkw-segment" style="margin-bottom: 0;">
            
                <h2><?php 
echo  esc_html__( 'About Auto Focus Keyword for SEO', 'auto-focus-keyword-for-seo' ) ;
?></h2>
                <p><?php 
echo  esc_html__( 'The Automatic Focus keyword for SEO plugin will add automatically "focus keywords" on the backend (first, then on frontend, once saved), for websites using Yoast SEO or Rank math plugins, to all your pages, based on post titles (used for each of these pages) so that you can optimize your content using their dynamic live settings (meta title, meta description, slug, alt tags, ...) for SEO. Please refer to the FAQ section to learn about all these terms (Focus Keyword, Meta tag Keyword, etc.). Once done, you will be able to use several other plugins made by Pagup.com to automatically optimize Alt tags, Title tags, Internal links and more ... (as these plugins are based on these Focus keywords). Enjoy !', 'auto-focus-keyword-for-seo' ) ;
?>
                </p>
                <p style="margin-bottom: 0; font-style: italic;"><?php 
echo  esc_html__( 'PS: we strongly recommend that you do not use Yoast SEO and Rank Math plugins altogether (for compatibility matters).', 'auto-focus-keyword-for-seo' ) ;
?></p>
            </div>
        </div>
    
        <div class="row">
    
            <div id="afkw__app" class="col-xs-12 col-md-9 col-main" style="padding-right: 0.8rem; padding-left: 0.8rem;">
    
                <form method="post" class="afkw-form">
    
                    <?php 
wp_nonce_field( 'afkw__settings', 'afkw__nonce' );
?>
    
                    <div class="afkw-segment">
    
                        <h2>STEP 1: Settings</h2>

                        <div class="row" style="margin-top: 20px;">
    
                            <div class="col-xs-3">
                                <label class="afkw-label" for="afkw__enable">
                                    <strong>
                                        <?php 
echo  esc_html__( 'Post Types', 'auto-focus-keyword-for-seo' ) ;
?>
                                    </strong>
                                    <span
                                    tooltip="<?php 
echo  esc_html__( 'Please select which custom post types should be targeted by this process', 'auto-focus-keyword-for-seo' ) ;
?>"
                                    flow="right">
                                    <i class="dashicons dashicons-editor-help"></i>
                                    </span>
                                </label>
                            </div>
        
                            <div class="col-xs-9">
        
                            <?php 
foreach ( $post_types as $label => $post_type ) {
    ?>

                            <div class="afkw-checkbox">
                                <input id="<?php 
    echo  esc_html( "afkw-" . $post_type ) ;
    ?>" type="checkbox"
                                    
                                <?php 
    
    if ( !afkw__fs()->can_use_premium_code__premium_only() && $post_type == 'product' ) {
        echo  'disabled' ;
    } else {
        echo  "name='post_types[]' value=" . esc_html( $post_type ) ;
    }
    
    ?>
                                    <?php 
    if ( $options->check( 'post_types' ) && in_array( $post_type, $options->get( 'post_types' ) ) ) {
        echo  "checked" ;
    }
    ?> />

                                <label
                                    for="<?php 
    echo  esc_html( "afkw-" . $post_type ) ;
    ?>"><?php 
    echo  esc_html( $label ) . (( !afkw__fs()->can_use_premium_code__premium_only() && $post_type == 'product' ? " (PRO only)" : '' )) ;
    ?></label>
                            </div>

                            <?php 
}
?>

                            <?php 

if ( class_exists( 'WooCommerce' ) && !afkw__fs()->can_use_premium_code__premium_only() ) {
    ?>
                            <div class="afkw-alert afkw-info">
                                <?php 
    echo  sprintf( wp_kses( __( '<a href="%s">Get Pro version</a> to enable Woocommerce Products', "auto-focus-keyword-for-seo" ), array(
        'a' => array(
        'href'   => array(),
        'target' => array(),
    ),
    ) ), esc_url( "admin.php?page=auto-focus-keyword-for-seo-pricing" ) ) ;
    ?>
                            </div>
                            <?php 
}

?>
        
                            </div>
    
                        </div>

                        <div class="row" style="margin-top: 15px;">
                            <div class="col-xs-6 col-md-3">
                                <label for="exclude_tags" class="afkw-label">
                                    <strong><?php 
echo  esc_html__( "Black List Post(s)", 'auto-focus-keyword-for-seo' ) ;
?></strong>
                                    <span
                                        tooltip="<?php 
echo  esc_html__( "Select Post(s) where you don't want to automatically add focus keyword.", 'auto-focus-keyword-for-seo' ) ;
?>"
                                        flow="right">
                                        <i class="dashicons dashicons-editor-help"></i>
                                    </span>
                                </label>
                            </div>
                            <div class="col-xs-6 col-md-9">
                                <div>
                                    <Multiselect
                                    v-model="blacklist"
                                    mode="tags"
                                    :close-on-select="false"
                                    :searchable="true"
                                    :create-option="true"
                                    :options="posts"
                                    />
                                </div>
                                <input type="hidden" class="afkw-input" v-model="blacklist" name="blacklist">
                            </div>
                        </div>
                        
                        <div class="afkw-sync-pro">
                            <h2><?php 
echo  esc_html__( "Auto Focus Keyword Continuously", 'auto-focus-keyword-for-seo' ) ;
?>
                            <?php 
echo  " (Pro Only)" ;
?>
                            <span tooltip="<?php 
echo  esc_html__( '100% Automatic - No more manual action required.', 'auto-focus-keyword-for-seo' ) ;
?>" flow="right"> <i class="dashicons dashicons-editor-help"></i></span>
                        </h2>

                            <div class="row" style="margin-top: 15px;">
                            
                                <div class="col-xs-6 col-md-6">
                                    <label for="disable_auto_sync" class="afkw-label" style="display: inline-block; margin-top: 10px;">
                                        <strong><?php 
echo  esc_html__( "Disable Auto Focus Keyword on Post Create/Update", 'auto-focus-keyword-for-seo' ) ;
?></strong>
                                        <span
                                            tooltip="<?php 
echo  esc_html__( 'This option will disable auto sync. Creating or updating post (or any other post type selected above) will not create focus keyword automatically.', 'auto-focus-keyword-for-seo' ) ;
?>"
                                            flow="right">
                                            <i class="dashicons dashicons-editor-help"></i>
                                        </span>
                                    </label>
                                </div>
                                <div class="col-xs-6 col-md-6">
                                <?php 
?>
                                    <label class="afkw-toggle"><input type="checkbox" disabled /><span class='afkw-toggle-slider afkw-toggle-round'></span></label>

                                    <span style="display: inline-block; margin-left: 10px;font-size: 12px;"><?php 
echo  sprintf( wp_kses( __( '<a href="%s">Get Pro version</a> to enable Continuous Sync feature', "auto-focus-keyword-for-seo" ), array(
    'a' => array(
    'href'   => array(),
    'target' => array(),
),
) ), esc_url( "admin.php?page=auto-focus-keyword-for-seo-pricing" ) ) ;
?></span>
                                <?php 
?>
                                </div>
                            </div>

                        </div>
                        
                        <div class="row" style="margin-top: 15px">

                            <div class="col-xs-3">
                                <label class="afkw-label" for="remove_settings">
                                    <strong>
                                        <?php 
echo  esc_html__( 'Remove Settings', 'auto-focus-keyword-for-seo' ) ;
?>
                                    </strong>
                                </label>
                            </div>

                            <div class="col-xs-2">
                                <label class="afkw-toggle"><input id="remove_settings" type="checkbox"
                                        name="remove_settings" value="allow" <?php 
if ( $options::check( 'remove_settings' ) ) {
    echo  'checked' ;
}
?> />
                                    <span class='afkw-toggle-slider afkw-toggle-round'></span></label>
                            </div>

                            <div class="col-xs-7 field">
                                <input type="submit" name="update" class="afkw-submit" value="<?php 
echo  esc_html__( 'Save Changes', 'auto-focus-keyword-for-seo' ) ;
?>" />
                            </div>

                        </div>
    
                    </div>

                </form>

                <?php 

if ( class_exists( 'WPSEO_Meta' ) || class_exists( 'RankMath' ) ) {
    include "inc/sync.view.php";
} else {
    include "inc/notice.view.php";
}

?>
    
            </div>

            <div class="col-xs-12 col-md-3 col-side" style="padding-right: 0.8rem; padding-left: 0.8rem;">

                <?php 
include "inc/side.view.php";
?>

            </div>
    
        </div>
    </div>
    
    
</div>