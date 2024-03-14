<?php

/**
 * I Agree! Popups
 *
 * @package   I_Agree_Popups
 * @license   GPLv2 or later
**/

/**
 * Add markup and JS to wp_footer
 *
 * @package I_Agree_Popups
**/
 
class I_Agree_WP_Footer {
    
    // Initialise functions 
    public function init() {
        
        add_action('wp_footer', array( $this, 'footer_functions'));
        add_action('admin_bar_menu', array( $this, 'customise_menu'), 90);
        
    }
    
    /**
     * Functions to bring in popup to wp_footer
     *
     * @since 1.0
    **/
    public function footer_functions() {
        
        // Detect if post or page is a singular instance as opposed to a phantom front page or archive
        if (is_singular()) {    
            $currentPostID = get_the_ID();
            $meta = get_post_custom($currentPostID);
        } else {
            $meta = '';
        }
    
        // Call $post and store old data for retrieval later
        global $post;
        $post_old = $post;
    
        // Determine whether or not sitewide popups exist
        $sitewideArgs = array('numberposts' => -1, 'post_type' => 'i-agree-popup', 'meta_query' => array(
            array(
                'key' => 'sitewide',
                'value' => 'on',
                'compare' => 'LIKE'
            )
        ));
        $sitewidePopup = get_posts($sitewideArgs);
        $sitewidePopupExists = !empty($sitewidePopup);
        
        // Determine whether or not a popup has been assigned to this post/page
        $popupID = ! isset($meta['popupID'][0]) ? '' : $meta['popupID'][0];
        $singleArgs = array('post_type' => 'i-agree-popup', 'post__in' => array($popupID));
        $selectablePopup = get_posts($singleArgs);
    
        // If sitewide popup exists, use in foreach - if not, use assigned (if exists)
        if ($sitewidePopupExists) {    
            $swOrsp = $sitewidePopup;
        } else {    
            $swOrsp = $selectablePopup;
        }
    
        // Start foreach to bring in popup data   
        foreach ($swOrsp as $post) : setup_postdata( $post ); 
        $postID = get_the_ID();
        $meta = get_post_custom( $post->ID );
        // Popup custom fields
        $opacityBG = ! isset( $meta['opacity_bg'][0] ) ? '' : $meta['opacity_bg'][0];
        $popupBG = ! isset( $meta['popup_bg'][0] ) ? '' : $meta['popup_bg'][0];
        $buttonBG = ! isset( $meta['button_bg'][0] ) ? '' : $meta['button_bg'][0];
        $buttonTXT = ! isset( $meta['button_txt'][0] ) ? '' : $meta['button_txt'][0];
        $agreeTXT = ! isset( $meta['agree_txt'][0] ) ? '' : $meta['agree_txt'][0];
        $disagreeTXT = ! isset( $meta['disagree_txt'][0] ) ? '' : $meta['disagree_txt'][0];
        $disagreeRedir = ! isset( $meta['disagree_redir'][0] ) ? '' : $meta['disagree_redir'][0];
        $cookieDur = ! isset( $meta['cookie_dur'][0] ) ? '' : $meta['cookie_dur'][0];
        $sitewide = ! isset( $meta['sitewide'][0] ) ? '' : $meta['sitewide'][0];
     
        // Define cookie (made unique by popup $postID
        $rememberMe   = $_COOKIE['rememberMe'.$postID];
        
        // If cookie exists, do nothing. If not, bring in popup HTML and JS
        if ($rememberMe != '') {} else {
        
        // Here comes the science bit... concentrate!
        
?> 
   
<!-- I Agree! Popups - http://www.talismansolutions.co.uk/i-agree-popups -->
<div class="iAgreePopup">
    <div class="popupBG" <?php if ($opacityBG != '') { ?> style="background:<?php echo $opacityBG; ?>" <?php } else {} ?>>
        &nbsp;
    </div>
    <div class="popupContainer" <?php if ($popupBG != '') { ?> style="background:<?php echo $popupBG; ?>" <?php } else {} ?>>
        <div class="popupContent">
            <?php the_content(); ?>
        </div>
        <div class="popupChoices">
            <div class="yesNo">
                <a href="#" class="agree popupChoiceButton" style=" <?php if ($buttonBG != '') {echo 'background:'.$buttonBG.';'; } else {} if ($buttonTXT != '') {echo ' color:'.$buttonTXT.';'; } else {} ?> "><?php if ($agreeTXT != '') {echo $agreeTXT;} else {echo 'Agree';} ?></a>
                <a href="<?php if ($disagreeRedir != '') {echo $disagreeRedir;} else {echo 'javascript:history.back()';} ?>" class="disagree popupChoiceButton" style=" <?php if ($buttonBG != '') {echo 'background:'.$buttonBG.';'; } else {} if ($buttonTXT != '') {echo ' color:'.$buttonTXT.';'; } else {} ?> "><?php if ($disagreeTXT != '') {echo $disagreeTXT;} else {echo 'Disagree';} ?></a>
            </div>
        </div>
    </div>
</div>   
<script>
jQuery(document).ready(function($) {    
    $('.iAgreePopup').fadeIn();
    var popupContainerHeight = $('.iAgreePopup .popupContainer').height();
    var popupContainerWidth = $('.iAgreePopup .popupContainer').width();
    var popupContainerMarginTop = '-'+(popupContainerHeight / 2)+'px';
    var popupContainerMarginLeft = '-'+(popupContainerWidth / 2)+'px';
    $('.iAgreePopup .popupContainer').css({'margin-top':popupContainerMarginTop, 'margin-left':popupContainerMarginLeft});
    $('.iAgreePopup .agree').click(function(){                  
<?php
            // Get todays date/time and add the user defined cookie duration
            $date = new DateTime();
            $date->modify("+".$cookieDur." day");
?>
        document.cookie="rememberMe<?php echo $postID; ?>=yes;expires=<?php echo $date->format("D, d M Y H:i:s T"); ?>;path=/";
        $('.iAgreePopup').fadeOut();                          
    });              
    $(window).resize(function(){
        var popupContainerHeight = $('.iAgreePopup .popupContainer').height();
        var popupContainerWidth = $('.iAgreePopup .popupContainer').width();
        var popupContainerMarginTop = '-'+(popupContainerHeight / 2)+'px';
        var popupContainerMarginLeft = '-'+(popupContainerWidth / 2)+'px';
        $('.iAgreePopup .popupContainer').css({'margin-top':popupContainerMarginTop, 'margin-left':popupContainerMarginLeft});
    });
});
</script>   
<!-- end I Agree! Popups  -->

<?php

        }; 
 
        // End foreach and reinstate original post data
        endforeach;    
        wp_reset_postdata();    
        $post = $post_old;
        setup_postdata( $post );      

    }

    /**
     * Function to add 'Edit Popup' to WP admin bar
     *
     * @since 1.0
    **/
    function customise_menu(){
        
        // Detect if post or page is a singular instance as opposed to a phantom front page or archive
        if (is_singular()) {    
            $currentPostID = get_the_ID();
            $meta = get_post_custom($currentPostID);
        } else {
            $meta = '';
        }
        
        // Call $post and store old data for retrieval later
        global $post;
        $post_old = $post;

        // Call $wp_admin_bar and keep it global
        global $wp_admin_bar;

        // Get site path
        ob_start(); bloginfo('url'); $bloginfoURL = ob_get_contents(); ob_end_clean(); 
        $sitePath = $bloginfoURL;
        
        // Determine whether or not sitewide popups exist
        $sitewideArgs = array('numberposts' => -1, 'post_type' => 'i-agree-popup', 'meta_query' => array(
            array(
                'key' => 'sitewide',
                'value' => 'on',
                'compare' => 'LIKE'
            )
        ));
        $sitewidePopup = get_posts($sitewideArgs);
        $sitewidePopupExists = !empty($sitewidePopup);
    
        // Determine whether or not a popup has been assigned to this post/page
        $popupID = ! isset($meta['popupID'][0]) ? '' : $meta['popupID'][0];
        $singleArgs = array('post_type' => 'i-agree-popup', 'post__in' => array($popupID));
        $selectablePopup = get_posts($singleArgs);
        $selectablePopupExists = !empty($selectablePopup);
    
        // If sitewide popup exists, use in foreach - if not, use assigned (if exists)
        if ($sitewidePopupExists) {   
            $swOrsp = $sitewidePopup;
        } else if ($selectablePopupExists) {    
            $swOrsp = $selectablePopup;
        } else {}
 
        // If either a sitewide popup or assigned popup exists, add the 'Edit Popup' button
        if ($swOrsp != '') {
        
            foreach ($swOrsp as $post) : setup_postdata( $post );
            $wp_admin_bar->add_menu(array(
                'id' => 'i-agree-popups',
                'title'  => '<span class="ab-icon"></span>'.__( 'Edit Popup', 'some-textdomain' ),
                'class'    => 'wpse-edit-popup',
                'href' => $sitePath.'/wp-admin/post.php?post='.$post->ID.'&action=edit',
            ));

            // End foreach
            endforeach; 
            wp_reset_postdata();
    
        }
        // Reinstate original post data
        $post = $post_old;
        setup_postdata($post);    
        
    }

}