<?php

/**
 * I Agree! Popups
 *
 * @package   I_Agree_Popups
 * @license   GPLv2 or later
**/

/**
 * Add metaboxes to Popups
 *
 * @package I_Agree_Popups
**/

class I_Agree_Popup_Metaboxes {
    
    // Initialise functions
    public function init() {
        
        add_action( 'add_meta_boxes', array( $this, 'popup_style_meta' ) );
        add_action( 'save_post', array( $this, 'save_style_meta' ),  10, 2 );
        add_action( 'add_meta_boxes', array( $this, 'popup_config_meta' ) );
        add_action( 'save_post', array( $this, 'save_config_meta' ),  10, 2 );
        
    }

    /**
     * Register the Style and Config metaboxes for popups
     *
     * @since 1.0
    **/
    public function popup_style_meta() {
        add_meta_box(
            'popup_style',
            'Popup Style',
            array( $this, 'render_style_meta' ),
            'i-agree-popup',
            'normal',
            'high'
        );
    } 
    public function popup_config_meta() {
        add_meta_box(
            'popup_config',
            'Configuration',
            array( $this, 'render_config_meta' ),
            'i-agree-popup',
            'side',
            ''
        );
    }

    /**
     * Render the HTML and JS for the Style metabox
     *
     * @since 1.0
    **/
    function render_style_meta( $post ) {  
        
        // Custom Fields
        $meta = get_post_custom( $post->ID );
        $opacityBG = ! isset( $meta['opacity_bg'][0] ) ? '' : $meta['opacity_bg'][0];
        $popupBG = ! isset( $meta['popup_bg'][0] ) ? '' : $meta['popup_bg'][0];
        $buttonBG = ! isset( $meta['button_bg'][0] ) ? '' : $meta['button_bg'][0];
        $buttonTXT = ! isset( $meta['button_txt'][0] ) ? '' : $meta['button_txt'][0];

        wp_nonce_field( basename( __FILE__ ), 'popup_style' ); 

        // Colour Picker JS followed by Metabox HTML    
?>
                
        <script src='<?php echo plugin_dir_url( __FILE__ ) . 'assets/js/spectrum.js'; ?>'></script>
        <link rel='stylesheet' href='<?php echo plugin_dir_url( __FILE__ ) . 'assets/css/spectrum.css'; ?>' />
        <script>
        jQuery(document).ready(function($) { 
           $("#opacityBG").spectrum({color: "<?php if ($opacityBG != '') {echo $opacityBG;} else {echo '#000';} ?>", showInput: true, className: "full-spectrum",showInitial: true,showSelectionPalette: true,maxSelectionSize: 10,preferredFormat: "hex"});
           $("#opacityBG").show();
           $("#opacityBG").change(function() {$('#opacityBG').spectrum("set", $("#opacityBG").val());});
           $("#popupBG").spectrum({color: "<?php if ($popupBG != '') {echo $popupBG;} else {echo '#999';} ?>", showInput: true, className: "full-spectrum",showInitial: true,showSelectionPalette: true,maxSelectionSize: 10,preferredFormat: "hex"});
           $("#popupBG").show();
           $("#popupBG").change(function() {$('#popupBG').spectrum("set", $("#popupBG").val());});
           $("#buttonBG").spectrum({color: "<?php if ($buttonBG != '') {echo $buttonBG;} else {echo '#000';} ?>", showInput: true, className: "full-spectrum",showInitial: true,showSelectionPalette: true,maxSelectionSize: 10,preferredFormat: "hex"});
           $("#buttonBG").show();
           $("#buttonBG").change(function() {$('#buttonBG').spectrum("set", $("#buttonBG").val());});
           $("#buttonTXT").spectrum({color: "<?php if ($buttonTXT != '') {echo $buttonTXT;} else {echo '#FFF';} ?>", showInput: true, className: "full-spectrum",showInitial: true,showSelectionPalette: true,maxSelectionSize: 10,preferredFormat: "hex"});
           $("#buttonTXT").show();
           $("#buttonTXT").change(function() {$('#buttonTXT').spectrum("set", $("#buttonTXT").val());});
        });
        </script>

        <table class="form-table">

            <tr>
                <td class="popup_meta_box_td" colspan="3">
                    <label for="opacity_bg"><?php _e( 'Opacity Background Colour', 'i-agree-popups' ); ?>
                    </label>
                </td>
                <td colspan="4">
                    <input type='text' name="opacity_bg" value="<?php if ($opacityBG != '') {echo $opacityBG;} else {echo '#000';} ?>" id="opacityBG" />
                    <p class="description"><?php _e( 'Hex value for the transparent colour that blocks out the page.', 'i-agree-popups' ); ?></p>
                </td>
            </tr>

            <tr>
                <td class="popup_meta_box_td" colspan="3">
                    <label for="popup_bg"><?php _e( 'Popup Background Colour', 'i-agree-popups' ); ?>
                    </label>
                </td>
                <td colspan="4">
                    <input type='text' name="popup_bg" value="<?php if ($popupBG != '') {echo $popupBG;} else {echo '#999';} ?>" id="popupBG" />
                    <p class="description"><?php _e( "Hex value for the popup's background colour.", 'i-agree-popups' ); ?></p>
                </td>
            </tr>

            <tr>
                <td class="popup_meta_box_td" colspan="3">
                    <label for="button_bg"><?php _e( 'Button Background Colour', 'i-agree-popups' ); ?>
                    </label>
                </td>
                <td colspan="4">
                    <input type='text' name="button_bg" value="<?php if ($buttonBG != '') {echo $buttonBG;} else {echo '#000';} ?>" id="buttonBG" />
                    <p class="description"><?php _e( "Hex value for the button background colour.", 'i-agree-popups' ); ?></p>
                </td>
            </tr>
            
            <tr>
                <td class="popup_meta_box_td" colspan="3">
                    <label for="button_txt"><?php _e( 'Button Text Colour', 'i-agree-popups' ); ?>
                    </label>
                </td>
                <td colspan="4">
                    <input type='text' name="button_txt" value="<?php if ($buttonTXT != '') {echo $buttonTXT;} else {echo '#FFF';} ?>" id="buttonTXT" />
                    <p class="description"><?php _e( "Hex value for the button text colour.", 'i-agree-popups' ); ?></p>
                </td>
            </tr>

        </table>

<?php 
}

    /**
     * Render the HTML for the Config metabox
     *
     * @since 1.0
    **/
    function render_config_meta( $post ) {
        
        $meta = get_post_custom( $post->ID );
        $agreeTXT = ! isset( $meta['agree_txt'][0] ) ? '' : $meta['agree_txt'][0];
        $disagreeTXT = ! isset( $meta['disagree_txt'][0] ) ? '' : $meta['disagree_txt'][0];
        $disagreeRedir = ! isset( $meta['disagree_redir'][0] ) ? '' : $meta['disagree_redir'][0];
        $cookieDur = ! isset( $meta['cookie_dur'][0] ) ? '' : $meta['cookie_dur'][0];
        $sitewide = ! isset( $meta['sitewide'][0] ) ? '' : $meta['sitewide'][0];

        wp_nonce_field( basename( __FILE__ ), 'popup_config' ); 
    
        // Metabox HTML        
?>

        <table class="form-table" width="100%">

            <tr>
                <td class="popup_meta_box_td">
                    <label for="agree_txt"><?php _e( 'Agree Text', 'i-agree-popups' ); ?>
                    </label><br>
                    <input type="text" name="agree_txt" class="regular-text" value="<?php if ($agreeTXT != '') {echo $agreeTXT;} else {echo 'Agree';} ?>" maxlength="25" style="width:100%;">
                    <p class="description"><?php _e( "Text for the 'Agree' Button. Limited to 25 characters.", 'i-agree-popups' ); ?></p>
                </td>
            </tr>

            <tr>
                <td class="popup_meta_box_td">
                    <label for="disagree_txt"><?php _e( 'Disagree Text', 'i-agree-popups' ); ?>
                    </label><br>
                    <input type="text" name="disagree_txt" class="regular-text" value="<?php if ($disagreeTXT != '') {echo $disagreeTXT;} else {echo 'Disagree';} ?>" maxlength="25" style="width:100%;">
                    <p class="description"><?php _e( "Text for the 'Disagree' Button. Limited to 25 characters.", 'i-agree-popups' ); ?></p>
                </td>
            </tr>
            
            <tr>
                <td class="popup_meta_box_td">
                    <label for="disagree_redir"><?php _e( 'Disagree Redirect', 'i-agree-popups' ); ?>
                    </label><br>
                    <input type="text" name="disagree_redir" class="regular-text" value="<?php if ($disagreeRedir != '') {echo $disagreeRedir;} else {} ?>" style="width:100%;">
                    <p class="description"><?php _e( "The URL that a user should be redirected to if they disagree. Leave blank to send the user back a page.", 'i-agree-popups' ); ?></p>
                </td>
            </tr>
            
            <tr>
                <td class="popup_meta_box_td">
                    <label for="cookie_dur"><?php _e( 'Cookie Duration', 'i-agree-popups' ); ?>
                    </label><br>
                    <input type="number" name="cookie_dur" id="cookie_dur" value="<?php if ($cookieDur != '') {echo $cookieDur;} else {echo '30';} ?>"> days
                    <p class="description"><?php _e( "How long the user will be remembered after agreeing to the terms and gaining access to the site.", 'i-agree-popups' ); ?></p>
                </td>
            </tr>

            <tr>
                <td class="popup_meta_box_td" style="vertical-align: top;" style="width:100%;">
                    <label for="sitewide"><?php _e( 'Placement on the site', 'i-agree-popups' ); ?>
                    </label><br>

<?php 
        // Call $post and store old data for retrieval later
        global $post;
        $post_old = $post;
        
        // Determine whether or not sitewide popups exist
        $sitewideArgs = array( 'numberposts' => -1, 'post_type' => 'i-agree-popup', 'meta_query' => array(
            array(
                'key' => 'sitewide',
                'value' => 'on',
                'compare' => 'LIKE'
            )
        ));
        $sitewidePopup = get_posts($sitewideArgs);
        $sitewidePopupExists = !empty($sitewidePopup);
        
        // Display notices depending on whether a Sitewide popup exists or not
        if ($sitewidePopupExists && $sitewide == '') { 
            foreach ($sitewidePopup as $post) : setup_postdata( $post );  
?>

                <input type="checkbox" disabled />  Sitewide<br><br>
                <div style="padding:10px; background:#F80B0F; color:#fff;">One of your other popups, <a href="<?php bloginfo('url'); ?>/wp-admin/post.php?post=<?php echo get_the_ID(); ?>&action=edit"><?php the_title(); ?></a>, is already set to appear sitewide. You can still save/edit this one but to use it, please <a href="<?php bloginfo('url'); ?>/wp-admin/post.php?post=<?php echo get_the_ID(); ?>&action=edit">edit</a> <?php the_title(); ?> and uncheck the 'Sitewide' box. You must also do this if you plan to assign any popups to individual posts or pages.</div>
                
<?php 
            endforeach;  
        } else if ($sitewidePopupExists && $sitewide != '') { 
?>

                <input type="checkbox" name="sitewide" checked />  Sitewide<br><br>
                <div style="padding:10px; background:#F80B0F; color:#fff;">This popup is currently appearing across the whole site and overriding all other popups. If you wish to make another one appear sitewide, or want to assign popups to individual posts or pages, please disable this one first by unchecking the above box and updating.</div>
                
<?php 
        } else { 
?>

                <input type="checkbox" name="sitewide" />  Sitewide<br>
                <p class="description">Checking the above box will enable your popup across the whole site, overriding all other popups. To assign it to an individual post or page, leave the box unchecked and just select this popup from the 'I Agree!' dropdown box when editing the post or page where you want it to appear. </p>

<?php 
        }  
        wp_reset_postdata();
?>

                </td>
            </tr>

        </table>

<?php 
    }
    
    /**
     * Save Style Metadata
     *
     * @since 1.0
    **/
    function save_style_meta( $post_id ) {

        global $post;

        // Verify nonce
        if ( !isset( $_POST['popup_style'] ) || !wp_verify_nonce( $_POST['popup_style'], basename(__FILE__) ) ) {
            return $post_id;
        }

        // Check Autosave
        if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
            return $post_id;
        }

        // Don't save if only a revision
        if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
            return $post_id;
        }

        // Check permissions
        if ( !current_user_can( 'edit_post', $post->ID ) ) {
            return $post_id;
        }

        // Save these custom fields
        $meta['opacity_bg'] = ( isset( $_POST['opacity_bg'] ) ? esc_textarea( $_POST['opacity_bg'] ) : '' );
        $meta['popup_bg'] = ( isset( $_POST['popup_bg'] ) ? esc_textarea( $_POST['popup_bg'] ) : '' );
        $meta['button_bg'] = ( isset( $_POST['button_bg'] ) ? esc_textarea( $_POST['button_bg'] ) : '' );
        $meta['button_txt'] = ( isset( $_POST['button_txt'] ) ? esc_textarea( $_POST['button_txt'] ) : '' );

        foreach ( $meta as $key => $value ) {
            update_post_meta( $post->ID, $key, $value );
        }
        
    }
    
    /**
     * Save Config Metadata
     *
     * @since 1.0
    **/
    function save_config_meta( $post_id ) {

        global $post;

        // Verify nonce
        if ( !isset( $_POST['popup_config'] ) || !wp_verify_nonce( $_POST['popup_config'], basename(__FILE__) ) ) {
            return $post_id;
        }

        // Check Autosave
        if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
            return $post_id;
        }

        // Don't save if only a revision
        if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
            return $post_id;
        }

        // Check permissions
        if ( !current_user_can( 'edit_post', $post->ID ) ) {
            return $post_id;
        }

        // Save these custom fields
        $meta['agree_txt'] = ( isset( $_POST['agree_txt'] ) ? esc_textarea( $_POST['agree_txt'] ) : '' );
        $meta['disagree_txt'] = ( isset( $_POST['disagree_txt'] ) ? esc_textarea( $_POST['disagree_txt'] ) : '' );
        $meta['disagree_redir'] = ( isset( $_POST['disagree_redir'] ) ? esc_url( $_POST['disagree_redir'] ) : '' );
        $meta['cookie_dur'] = ( isset( $_POST['cookie_dur'] ) ? esc_textarea( $_POST['cookie_dur'] ) : '' );
        $meta['sitewide'] = ( isset( $_POST['sitewide'] ) ? esc_textarea( $_POST['sitewide'] ) : '' );

        foreach ( $meta as $key => $value ) {
            update_post_meta( $post->ID, $key, $value );
        }
        
    }
    
}