<?php

/**
 * @package: Category Featured Images Extended
 * @Version: 1.5
 * @Date: 1 September 2017
 * @Author: CK MacLeod
 * @Author: URI: http://ckmacleod.com
 * @License: GPL3
 */

defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' ) ;
   
//get options 
$images                 =   
        get_option( 'cks_cfix_featured_images' ) ? 
        get_option( 'cks_cfix_featured_images' ) : '';
$excluded_categories    = 
        get_option( 'cks_cfix_exclude_category' ) ? 
        get_option( 'cks_cfix_exclude_category' ) : '' ;
$fallback_cat           = 
        get_option( 'cks_cfix_fallback_category' ) ?  
        get_option( 'cks_cfix_fallback_category') : '' ;
$use_yoast_cat = 
        get_option( 'cks_cfix_use_yoast_primary' ) ? 
        get_option( 'cks_cfix_use_yoast_primary' ) : '' ;
$yoast_active_class = is_plugin_active('wordpress-seo/wp-seo.php') ? 
        'yoast-active' : 'yoast-inactive' ;

$version = CKS_CFIX_VERSION ;

//derive dependent variables

$fallback_cat_id = $tax = '' ;

if ( get_cat_ID( esc_html( $fallback_cat ) ) ) {
    
    $fallback_cat_id        = get_cat_ID( esc_html($fallback_cat) ) ;
    $tax = 'category' ;
    
} else {
            
    if (get_term_by( 'name', $fallback_cat, 'post_tag' ) ) {

        $fall_cat_obj = get_term_by( 'name', $fallback_cat, 'post_tag' ) ;
        $fallback_cat_id = $fall_cat_obj->term_id ;
        $tax = $fall_cat_obj->taxonomy ;

    }

}

$fallback_img_id        =  
       isset( $images[$fallback_cat_id] ) ? 
        $images[$fallback_cat_id] : '' ;

if ( $images ) {
    
    $images_message =  'Click on image then Save Changes '
            . 'to make it your Current Global Fallback.' ;
    
} else {
    
    $images_message =  sprintf( __( 'You need to %sadd at least one image '
            . 'to a Category%s or %sto a Tag%s '
            . 'to be able to select a Global Fallback.', 
            'cks_cfix'), 
            '<a href="' . admin_url(
                    '/term.php?taxonomy=category&post_type=post' 
                    ) . '">', '</a>',
            '<a href="' . admin_url(
                    '/term.php?taxonomy=post_tag&post_type=post' 
                    ) . '">', '</a>' ) ;
    
}

?>

<div class="wrap cks_plugins">
    
    <h1>CATEGORY FEATURED IMAGES - EXTENDED</h1>
    
    <form method="post" action="options.php">     
     
     <?php 
     
    settings_fields('cks_cfix');  
    
    do_settings_fields('cks_cfix','');    
     
     ?>
        
    <div id="cks_plugins-main" >
        
         <p><?php _e( 'On this page, you can modify options '
                . 'for using Fallback Images. First, however, you must set '
                . 'at least one Category or Tag image.', 'cks_cfix') ; ?> 
        </p>
        
        <p><?php 
                printf( __( 
                'To set an image for fallback, '
                . '%sgo to Edit Categories%s '
                . 'and select an individual Category, or '
                . 'go %sto Edit Tags%s to '
                . 'set an individual Tag Image', 'cks_cfix '), 
                '<a href="' 
                . admin_url('/term.php?taxonomy=category&post_type=post') 
                . '">', '</a>', 
                '<a href="' 
                . admin_url('/term.php?taxonomy=post_tag&post_type=post') 
                . '">', '</a>' 
            ) ; ?>
        </p>
        
        <p>
            <?php printf( __( 
                    'When a Post has multiple Categories or Tags with images, ' 
                    . 'the plug-in will select the most recently added '
                    . 'eligible Category or Tag, '
                    . 'unless the Yoast SEO Primary Category option is set, '
                    . 'and has an image, in which instance'
                    . 'that image will be used. '
                    . 'This behavior can also be modified via filter hook'
                    . ' (see %splugin documentation%s).' , 
                                'cks_cfix' ), 
                    '<a href="http://ckmacleod.com/wordpress-plugins/category-'
                    . 'featured-images-extended/advanced/">', '</a>') ;  ?>
        </p>

        <table class="form-table">
            <tr valign="top" class="<?php echo $yoast_active_class ; ?>" >
                <th valign="top" scope="row">
                    <label><?php _e('Use Yoast SEO Primary Category', 
                            'cks_cfix') ; ?></label>
                </th>
                <td>
                   <input type='checkbox' name='cks_cfix_use_yoast_primary' <?php 
                   checked( $use_yoast_cat, 1 ) ; ?> value='1'>
                   <span class="description"><?php _e( 
                    'Check if you want '
                   . 'to fall back to a Yoast SEO "Primary Category" '
                           . 'when available.', 'cks_cfix' )
                   ; ?></span>  
                </td>
           </tr>
            <tr valign="top">
                <th scope="row">
                    <label for="cks_cfix_exclude_category"><?php _e(
                        'DO NOT Fall Back to These Post Categories or Tags:' , 
                           'cks_cfix' ) ; 
                    ?></label>
                </th>
                <td>
                    <textarea name='cks_cfix_exclude_category'><?php 
                    echo esc_attr( $excluded_categories ) ; ?></textarea>			
                    <span class="description"><?php _e( 
                            'Category or Tag Name, or Comma-Separated List '
                                . '- for example: <code>Science, War</code>.', 
                                'cks_cfix') ; 
                    ?></span>              
                    <p class="description"><?php _e( 
                        'The plug-in will ignore any Categories or Tags named '
                            . 'here when looking for fallback images '
                            . 'except as a "last resort" '
                            . 'set as Global Fallback below.', 
                            'cks_cfix') ; 
                    ?></p>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">
                    <label for="cks_cfix_fallback_category"><?php _e( 
                        'Global Fallback Category or Tag:', 'cks_cfix' ) 
                    ; ?></label>
                </th>
                <td>
                    <input type="text" name="cks_cfix_fallback_category" 
                           id="cks_cfix_fallback_category" 
                           value="<?php echo $fallback_cat ; ?>" />
                    <span class="description" ><?php _e( 
                            'Category or Tag Name.' , 'cks_cfix'
                            ) ; 
                    ?></span>
                    <p class="description"><?php _e( 
                            'You CAN repeat a "don\'t fall back" name '
                            . 'here if you want to use its image '
                            . 'as a "last resort."' , 
                                'cks_cfix'
                            ) ; 
                    ?></p>
                 </td>
            </tr>
            <tr valign="top">   

                  <th scope="row">
                      <label for="cks_cfix_fallback_image"><?php _e( 
                                'Current Global Fallback Image:', 'cks_cfix' ) ; 
                      ?></label>
                  </th>
                  <td> 
                    <?php cks_cfix_attachment_link( 
                        $fallback_cat, $fallback_cat_id, $fallback_img_id, $tax 
                        ) ; 
                    ?>
                    
                    <span class="description"><?php echo 
                    cks_cfix_fallback_description(         
                        $fallback_cat, $fallback_cat_id, $fallback_img_id, $tax 
                             ) ; 
                    ?></span>
                </td>
            </tr>
            <tr id="cks_cfix_cat_image_select-row">
                <th><label for="cks_cfix_cat_images"><?php _e( 
                        'Image Selection', 'cks_cfix' ) ; 
                ?></label></th>
                <td>
                    <?php cks_cfix_cat_image_select( $images ) ; ?>
                    <span class="description"><?php 
                    echo $images_message ?></span>
                </td>
            </tr>
                
        </table>
        
        

        <?php submit_button(); ?>

        <?php wp_nonce_field( 'cks_cfix_settings', '_cfix_settings_nonce' ) ; ?>
        
    </form>

    <?php cks_cfix_usage_notes() ; ?>
    
    <?php cks_cfix_shortcodes_and_functions() ; ?>


    </div> <!-- END cks_plugins_main -->

    <?php cks_cfix_sidebar( $version ) ; ?>

    <?php cks_cfix_plugins_footer( $version ) ; ?> 

</div><!-- END wrap cks_plugins -->

<?php 

/**
 * SET IMAGE OR MESSAGE FOR CURRENT GLOBAL FALLBACK
 * depending on status of fallback category and image
 * @param type $fallback_cat
 * @param type $fallback_cat_id
 * @param type $fallback_img_id
 */
function cks_cfix_attachment_link( 
        $fallback_cat, $fallback_cat_id, $fallback_img_id, $tax ) {
    
    $attachment_link = '<div id="cfix-thumbnail" class="cfix-thumbnail">'
            . '<a href="' 
            . admin_url( '/term.php?taxonomy=' . $tax . '&tag_ID=' 
                    . $fallback_cat_id . '&post_type=post' ) 
            .'">' . wp_get_attachment_image( $fallback_img_id ) . '</a>' ;
    
    $attachment_link .= '<br><a href="' 
            . admin_url( '/term.php?taxonomy=' . $tax . '&tag_ID=' 
                    . $fallback_cat_id . '&post_type=post' ) 
            .'"><span id="cfix-selected-cat">' 
            . $fallback_cat . '</span></a></div>' ;
    
    $no_attachment_link = 
            '<div id="cfix-thumbnail-no-image" class="cfix-thumbnail">'
            . '<span id="cfix-no-image">' . 
            
            sprintf( __( 
                'Set a New Fallback Image '
                    . 'in %sEdit Categories%s or %sEdit Tags%s.' 
                , 'cks_cfix' ), 
                '<br><a href="' . admin_url(
                '/term.php?taxonomy=category&post_type=post' ) 
                . '">', '</a>', 
                '<br><a href="' . admin_url(
                '/term.php?taxonomy=post_tag&post_type=post' ) 
                . '">', '</a>' 
                    ) 
            
            . '</span></a></div>' ;
    
    $no_attachment_link_but_global = 
            '<div id="cfix-thumbnail-no-image" class="cfix-thumbnail">'
            . '<a href="' 
            . admin_url('/term.php?taxonomy=' . $tax . '&tag_ID=' 
                    . $fallback_cat_id . 'post_type=post' ) 
            . '"><span id="cfix-no-image">' 
            . sprintf( __( 
                    'Set a Fallback Image%s for %s' , 'cks_cfix' ), '<br>', 
                    $fallback_cat ) 
            . '</span></a></div>' ;

    if ( $fallback_cat && $fallback_img_id ) { 
        
        echo $attachment_link ; 
        
    }
    
    if ( $fallback_cat && ! $fallback_img_id ) {
        
        echo $no_attachment_link_but_global ; 
        
    }
    
    if ( ! $fallback_cat ) {
        
        echo $no_attachment_link ;
        
    }
    
}

/**
 * ADDS VERSION INFO TO SIDEBAR
 * @param string $version
 */
function cks_cfix_sidebar( $version ) {

    ?>

    <div id="cks_plugins-sidebar">

        <?php cks_cfix_illustrations() ; ?>

        <div id="cks_plugins-version" class="sidebar-version">

            <p>Category Featured Images - Extended <br>Version <?php 
                echo $version ; 
            ?><br><i>by CK MacLeod</i></p>

        </div>
        
            <?php cks_cfix_tip_jar() ; ?>

    </div>

<?php  

}


/**
 * GET GLOBAL FALLBACK DESCRIPTION WITH LINKS
 * @param string $fallback_cat
 * @param int $fallback_cat_id
 * @param int $fallback_img_id
 * @return string
 */
function cks_cfix_fallback_description( 
        $fallback_cat, $fallback_cat_id, $fallback_img_id, $tax ) {
    
    $tag_label = ( 'category' === $tax ) ? 'Category' : 'Tag' ;
    
    if ( $fallback_cat && ! $fallback_img_id ) { 

        //tax logic
        $message = sprintf( __(
            'You still need to %s add an image to the %s %s%s.', 
            'cks_cfix' ), 
            '<a href="' . admin_url( '/term.php?taxonomy=category&tag_ID=' . 
            $fallback_cat_id . '&post_type=post' ) . '">', 
            $fallback_cat, $tag_label, '</a>' 
        ) ; 

    } 

    if ( $fallback_cat && $fallback_img_id ) {
        
        //tag-logic needed
        $message = sprintf( __(
            'Modify This Image in %s"Edit %s %s,"%s; '
                . 'OR type a different Global Fallback Category or Tag name, '
                . 'or choose from Image Selection; '
                . 'OR add an image '
                . '%sto a different Category%s or %sTag%s '
                . 'and set the Global Fallback there.', 
                'cks_cfix' ), 
            '<a href="' . admin_url( '/term.php?taxonomy=' . $tax . '&tag_ID=' . 
            $fallback_cat_id . '&post_type=post' ) . '">', 
            $fallback_cat,
            $tag_label,'</a>',   
            '<a href="' . admin_url( '/term.php?taxonomy=category' ) . '">',
            '</a>',  
            '<a href="' . admin_url( '/term.php?taxonomy=post_tag' ) . '">',
            '</a>'
        ) ; 

    }

    if ( ! $fallback_cat && ! $fallback_img_id ) {

        $message = sprintf( __(
            'To set a Global Fallback Image, select from '
                . 'Tag or Category Images in Image Selection, '
                . 'OR type a different Global Fallback Category or Tag name,, '
                . 'OR add an image to a Category or Tag in ' 
                . '%sEdit Categories%s or %sEdit Tags%s '
                . 'and set it as Global Fallback there.', 
                'cks_cfix' ), 
            '<a href="' . admin_url(
                    '/term.php?taxonomy=category&post_type=post' 
                    ) . '">', '</a>', 
            '<a href="' . admin_url(
                    '/term.php?taxonomy=post_tag&post_type=post' 
                    ) . '">', '</a>' 
                ) ;

    }
    
    $message .= __( ' (Dimensions of Thumbnail or Featured Images '
                        . 'as actually displayed will depend on theme styling.)', 
                    'cks_cfix' ) ;
    
    return $message ;

}

/**
 * CATEGORY IMAGE SELECTION MENU
 * @param array $images
 */
function cks_cfix_cat_image_select( $images ) {
    
     echo '<div id="cfix-cat-image-select" class="cfix-cat-image-select">' ;
    
    if ( $images ) {
    
        foreach ($images as $cat_id => $image ) {

            if ( wp_get_attachment_image( $image ) ) {

                $cat_name = get_term( $cat_id )->name ;
                $tax = get_term( $cat_id )->taxonomy ;

                if ( $tax === 'category' ) {

                    $cats[] = array( $cat_id, $cat_name, $tax, $image ) ;
                }

                if ( $tax === 'post_tag' ) {

                    $tags[] = array( $cat_id, $cat_name, $tax, $image ) ;
                }
//where cpt would go
                
                if ( $tax === 'series' ) {
                    
                   $taxes[] = array( $cat_id, $cat_name, $tax, $image ) ; 
                    
                }
            }

        }
        
        if ( $cats ) {

            echo '<div id="cfix-select-category-images" class="cfix-select-images">' ;

            echo '<h4>Category Images:</h4>' ;

            foreach ( $cats as $cat ) {

                echo cks_cfix_get_cat_select_image( 
                        $cat[0], $cat[1], $cat[2], $cat[3] ) ;

            }

            echo '</div>' ;
        
            
        }
        
        if ( $tags ) {

            echo '<div id="cfix-select-tag-images" class="cfix-select-images">' ;

            echo '<h4>Tag Images:</h4>' ;

            foreach ( $tags as $tag ) {

                echo cks_cfix_get_cat_select_image( 
                        $tag[0], $tag[1], $tag[2], $tag[3] ) ;

            }

            echo '</div>' ;
        
        }
        
        if ( $taxes ) {

            echo '<div id="cfix-select-tag-images" class="cfix-select-images">' ;

            echo '<h4>Taxonomy Images:</h4>' ;

            foreach ( $taxes as $a_tax ) {

                echo cks_cfix_get_cat_select_image( 
                        $a_tax[0], $a_tax[1], $a_tax[2], $a_tax[3] ) ;

            }

            echo '</div>' ;
        
        }
    
    } else {
        
        echo '<div id="cfix-select-category-images" class="cfix-select-images">' ;
        
        echo '<h4>No Images Created Yet</h4>' ;
        
        echo '</div>' ;
        
    }
    
    echo '</div>';
    
}

function cks_cfix_get_cat_select_image( $cat_id, $cat_name, $tax, $image ) {
    
    $html = '<a class="cfix-cat-image-link" '
                    . 'alt="' . $cat_name 
                    . '" title="' . $cat_name 
                    . '" data="' . $cat_name 
                    . '" href="' . admin_url(
                            '/term.php?taxonomy=' . $tax . '&tag_ID=' 
                            . $cat_id . '&post_type=post') 
                    .'">' 
                    . wp_get_attachment_image( 
                        $image, array( 50,50 ), false, 
                            array( 
                                
                                'class' => 'cfix-cat-image', 
                                'data'  => $cat_name ,
                                'alt'   => $cat_name ,
                                'title' => $cat_name , 
                                
                            ) 
                            
                    ) . '</a>' ;
    
    return $html ;
    
}

/**
* USAGE NOTES
*/
function cks_cfix_usage_notes() {

    ?>

    <div id="cks_cfix-usage-notes" class="ck-usage-notes">

        <h3><?php _e( 'Usage Notes', 'cks_cfix' ) ; ?></h3>

        <p><b><?php printf( 
            __('This plugin will not affect image display at your site until '
            . 'you have added %sCategory%s or %sTag%s Fallback images. ', 'cks_cfix' ),
                '<a href="' . admin_url(
                    '/term.php?taxonomy=category&post_type=post' 
                    ) . '">', '</a>',
                '<a href="' . admin_url(
                    '/term.php?taxonomy=post_tag&post_type=post' 
                    ) . '">', '</a>' ) ;
        ?></b><?php _e( 'You cannot add an image to a Tag whose name is identical to a Category\'s.', 'cks_cfix') ; ?></p>

        <p><?php _e( 
            'Under the default settings, if there is already a specific post Thumbnail or Featured Image available, the one belonging to the most recently added Category or Tag will be used. If none has been set, the plug-in will use a Tag or Category Fallback image if available. Category and Tag images will also be used wherever else they are independently enabled in your theme - typically in archive headings.', 'cks_cfix' ) ; ?></p>

        <p><?php _e( 'If no Post Category or Tag image has been set, Category Featured Images Extended will look for an image from a Parent Category.', 'cks_cfix' ) ; ?></p>

        <p><?php _e( 'The plug-in provides two additional options, which can be used together: First, you can name one or more Categories that will be excluded from the fallback process: This feature will be helpful when you have not set unique Category images for all of the Categories in use at your site, and when posts are tending to fall back to a general Category rather than to relatively more specific Parent Categories.', 'cks_cfix' ) ; ?></p>

        <p><?php _e( 'The second option is to set a specific Category image as Global Fallback, or "site-wide Thumbnail of last resort." If no Post, Category, Tag, or Parent Category image is available, this Global Fallback will be used. You can name a Category here that you have also included among the excluded Categories: The effect will be the equivalent of saying "Always use a specific other Category if available, but go ahead and use this one if there\'s no alternative.', 'cks_cfix' ) ;?></p>
    </div>

    <?php
        
}

/**
 * ADDITIONAL USAGE NOTES
 */
function cks_cfix_shortcodes_and_functions() {
    
    ?>

    <div class="ck-usage-notes">

        <h3><?php _e( 'Shortcode/PHP Functions', 'cks_cfix') ; ?></h3>
        
        <p>
            <?php _e( 'The plugin provides two options, usable as either functions or shortcode, for deploying Fallback images in other places: To display Tag or Category Fallback image, use <code>[cfix_featured_image]</code> in a Post or <code>cfix_featured_image()</code> in a template. To get the image url, use <code>cfix_featured_image_url()</code>. The following optional arguments are available:', 'cks_cfix' ) ; ?>
        </p>

        <ul>
            <li><?php _e( '\'size\': \'thumbnail\', \'medium\', \'large\', \'full\'', 'cks_cfix') ; ?></li>
            <li><?php _e( '\'class\': class of the image tag', 'cks_cfix') ; ?></li>
            <li><?php _e( '\'alt\': alternative text of the image tag', 'cks_cfix') ; ?></li>
            <li><?php _e( '\'title\': title of the image tag', 'cks_cfix') ; ?></li>
            <li><?php _e( '\'cat_id\': specific Category (or Tag) id', 'cks_cfix') ; ?></li>   
        </ul>

        <p>
            <?php _e( 'Shortcode example:', 'cks_cfix') ; ?>
        </p>

        <p>
            <code><?php _e( 
                '[cfix_featured_image size="large" '
                . 'title="This is a test..." class="my-image" '
                . 'alt="My image"]', 'cks_cfix') ; 
            ?></code>
        </p>

        <p><?php _e( 'Function example 1:', 'cks_cfix') ; ?></p>

        <p>
            <code><?php _e( 'echo cfix_featured_image( array( '
                . '\'size\' => \'large\', '
                . '\'title\' => \'Category 4081 Image\', '
                . '\'class\' => \'cfix-image\', '
                . '\'alt\' => \'Category 4081 Image\', '
                . '\'cat_id\' => 4081 );', 'cks_cfix') ; 
            ?></code>
        </p>
        <p><?php _e( 'Displays the "large" version of the image with the assigned class and info, from the Category with ID #4081, in a function or template. Category ID does <b>not</b> need to be specified if used within the WordPress Loop as, for example, a substitute for a Featured Image function.', 'cks_cfix' ) ; ?></p>

        <p><?php _e( 'Function example 2:', 'cks_cfix') ; ?></p>

        <p><code><?php _e( 'cfix_featured_image_url( array( '
                . '\'size\' => \'large\' ) );', 'cks_cfix') ; 
        ?></code></p>
        <p><?php _e( 'Gets the URL for a large-size Fallback image.', 'cks_cfix' ) ; ?></p>
        
        
    </div>    

        <?php
        
}

/**
 * CK'S DONATION FORM
 * Outputs Paypal "Tip Jar"
 */
function cks_cfix_tip_jar() {
    
    ?> 
    
    <div class="ck-donation">
                
        <p><?php _e( 'If you think this plug-in saved you time, or work, '
                . 'or anxiety,<br>or money, or anyway<br>'
                . 'you\'d like to see more work like this...', 'cks_cfix' ) ; 
        ?></p>

        <div id="sos-button">

            <form id="sos-form" action="https://www.paypal.com/cgi-bin/webscr" 
                  method="post" target="_top">
                
                <input name="cmd" type="hidden" value="_xclick" />
                <input name="business" type="hidden" 
                       value="ckm@ckmacleod.com" />
                <input name="lc" type="hidden" value="US" />
                <input name="item_name" type="hidden" value="Tip CK!" />
                <input name="item_number" type="hidden" 
                       value="CFI-Extended" />
                <input name="button_subtype" type="hidden" value="services" />
                <input name="no_note" type="hidden" value="0" />
                <input name="cn" type="hidden" 
                       value="Add special instructions or message:" />
                <input name="no_shipping" type="hidden" value="1" />
                <input name="currency_code" type="hidden" value="USD" />
                <input name="weight_unit" type="hidden" value="lbs" />

                <div id="ck-donate-submit-line">
                    
                    <input id="sos-amount" 
                           title="Confirm or not when you get there..." 
                           name="amount" type="text" value="" 
                           placeholder="$xx.xx" />
                    <input id="sos-submit" title="Any amount is very cool..." 
                           alt="Go to Paypal to complete" 
                           name="submit" type="submit" value="<?php _e( 
                                   '...tip me!', 'cks_cfix' ) 
                                   ?>" />
                </div>

            </form>

        </div>

    </div>
    
    <?php
}

/**
 * SIDEBAR ILLUSTRATIONS
 * captions and images change 
 * @param array $options
 */
function cks_cfix_illustrations() {
    
    ?>
    
    <div class="ck-illustrations">

        <img src="<?php echo plugin_dir_url( __FILE__ ) ; 
        ?>images/screenshot-3.jpg" alt="<?php _e( 'Before Image', 'cks_cfix') ; 
        ?>" > 

        <p class="cks_plugins_admin-caption"><?php _e( 'Before', 'cks_cfix' ) ; 
        ?></p>  

        <img src="<?php echo plugin_dir_url( __FILE__ ) ; 
        ?>images/screenshot-4.jpg" alt="<?php _e( 'After Image', 'cks_cfix' ) ; 
        ?>" >

        <p class="cks_plugins_admin-caption"><?php _e( 'After', 'cks_cfix' ) ; 
        ?></p>

    </div>
    
    <?php
    
}

/**
 * CK'S PLUGINS FOOTER
 * @param string $version
 */
function cks_cfix_plugins_footer( $version ) {
    
    $plugin_home_page = 'http://ckmacleod.com/wordpress-plugins/'
            . 'category-featured-images-extended/'; 
    
    ?>
    
    <div id="cks_plugins_admin-footer">

        <a target="_blank" id="link-to-cks-plugins" 
           href="http://ckmacleod.com/wordpress-plugins/"><img src="<?php 
           echo plugin_dir_url( __FILE__ ) ; 
           ?>images/cks_wp_plugins_200x40.jpg"></a>
        
        <a target="_blank" id="link-to-cks-plugins-text" 
           href="http://ckmacleod.com/wordpress-plugins/">All CK's Plug-Ins</a>
        
        <a target="_blank" id="ck-home" href="<?php 
        echo $plugin_home_page ; 
        ?>">Plug-In Home Page</a>
        
        <a target="_blank" id="ck-faq" href="<?php 
        echo $plugin_home_page ; 
        ?>frequently-asked-questions/">FAQ</a>
        
        <a target="_blank" id="ck-style" href="<?php 
        echo $plugin_home_page ; 
        ?>download-changelog-installation/">Changelog</a>
        
        <a target="_blank" id="ck-help" href="<?php 
        echo $plugin_home_page ; 
        ?>support/">Feedback and Requests:<br>Contact CK</a>
        
        <a id="ck-support" class="<?php 
        echo ($version < 1 ) ? 'pre-wp-beta' : 'wordpress-link' ; ?>" 
           href="<?php echo 
           ($version < 1) ? '#" title="Beta: Not Yet at Wordpress.org"' : 
           'http://wordpress.org/support/plugin/category-featured-images-'
                   . 'extended/" target="_blank"' 
                   ?>">Support at Wordpress</a>
        
        <a id="ck-rate" class="last-link<?php echo ($version < 1 ) ? 
        ' pre-wp-beta' : ' wordpress-link' ; ?>" href="<?php echo 
        ($version < 1) ? '#" title="Beta: Not Yet at Wordpress.org"' : 
                'http://wordpress.org/support/view/plugin-reviews/category-'
                . 'featured-images-extended/" target="_blank"' ; 
        ?>" >&#9733; &#9733; &#9733; &#9733; &#9733;<br>Rate This Plugin!</a> 

    </div>
    
    <?php
    
}