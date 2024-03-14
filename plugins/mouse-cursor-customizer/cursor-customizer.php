<?php
/*
Plugin Name: Mouse cursor customizer
Description: Change the cursor on the site to your image.
Version: 1.2
Author: Alexander Koledov
*/


/*  Copyright 2019  Alexander Koledov  (email: alexander.koledov@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


add_action( 'admin_enqueue_scripts', 'cursor_custom_add_admin_scripts' );
add_action( 'wp_enqueue_scripts', 'cursor_custom_add_script_and_style', 1000 );
add_action( 'admin_menu', 'cursor_custom_create_page' );
add_action( 'admin_init', 'cursor_custom_cursor_details_cb' );




function cursor_custom_add_script_and_style() {
    wp_register_script( 'cursor_public_js', plugins_url( 'plugin.js', __FILE__ ), array( 'jquery' ) );
    wp_register_style( 'cursor_css', plugins_url( 'cursor.css', __FILE__ ) );

    $options = get_option( 'cursor_details' );

    wp_enqueue_script( 'cursor_public_js' );
    wp_enqueue_style( 'cursor_css' );

    $options['cursor_file1']['url_new'] = isset( $options['cursor_file1']['url_new'] ) ? esc_url( $options['cursor_file1']['url_new'] ) : '';
    $options['cursor_file2']['url_new'] = isset( $options['cursor_file2']['url_new'] ) ? esc_url( $options['cursor_file2']['url_new'] ) : '';
    $options['cursor_file3']['url_new'] = isset( $options['cursor_file3']['url_new'] ) ? esc_url( $options['cursor_file3']['url_new'] ) : '';
    $url_on_button = ( isset( $options['onbutton'] ) && $options['onbutton'] == 'on' ) ? $options['cursor_file1']['url_new'] : $options['cursor_file3']['url_new'];


    if ( $options['use_url_body'] == 'on' ) {
        $body_cursor = $options['url_body'];
    } else {
        $body_cursor = $options['cursor_file1']['url_new'];
    }


    if ( $options['use_url_link'] == 'on' ) {
        $url_on_link = ( isset( $options['onlink'] ) && $options['onlink'] == 'on' ) ? $body_cursor : $options['url_link'];
    } else {
        $url_on_link = ( isset( $options['onlink'] ) && $options['onlink'] == 'on' ) ? $body_cursor : $options['cursor_file2']['url_new'];
    }



    $custom_css = "
    /* Cursor customization for body */

    body, 
    body span, 
    body div {
        cursor: url($body_cursor), auto!important;
    }
    

    body.cursor-customizer103.cursor-customizer104.cursor-customizer105.cursor-customizer106,
    body.cursor-customizer103.cursor-customizer104.cursor-customizer105.cursor-customizer106 span,
    body.cursor-customizer103.cursor-customizer104.cursor-customizer105.cursor-customizer106 div {
        cursor: url($body_cursor), auto!important;
    }


    /* Cursor customization for links  */

    body a {
        cursor: url($url_on_link), pointer!important;
    }
    
    body a.cursor-customizer103.cursor-customizer104.cursor-customizer105.cursor-customizer106 {
        cursor: url($url_on_link), pointer!important;
    }
    
    
    /* ( v 1.2 Temporarily disable cursor changes on buttons )

    body button {
        cursor: url($url_on_button), pointer!important;
    }
    
    body button.cursor-customizer103.cursor-customizer104.cursor-customizer105.cursor-customizer106 {
        cursor: url($url_on_button), pointer!important;
    }

    body input {
        cursor: url($url_on_button), pointer!important;
    }

    body input.cursor-customizer103.cursor-customizer104.cursor-customizer105.cursor-customizer106 {
        cursor: url($url_on_button), pointer!important;
    }
    
    */";

    wp_add_inline_style( 'cursor_css', $custom_css );
}
// end of cursor_custom_add_script_and_style()


function cursor_custom_add_admin_scripts( $slug ) {

    wp_register_style( 'slider_ui_css', plugins_url( 'admin-cursor.css', __FILE__ ));
    wp_register_script( 'plugin_admin_js', plugins_url( 'plugin-admin.js', __FILE__ ), array('jquery-ui-slider') );

    $options = get_option( 'cursor_details' );

    if ( $slug != 'appearance_page_cursor-settings' ) {
        return;
    }

    wp_enqueue_script( 'plugin_admin_js' );
    wp_enqueue_style( 'slider_ui_css' );
    wp_localize_script( 'plugin_admin_js', 'ObjCursor', $options );
}



function cursor_custom_cursor_details_cb() {

    if( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $args = array(
        'sanitize_callback' => 'cursor_custom_sanitize_callback_set',
        'group' => 'cursor_details',
    );

    register_setting( 'cursor_details', 'cursor_details', $args  );

    add_settings_section( 'cursor_image_section_id', 'Choose and setup image for your cursors', '', 'cursor-settings' );

    add_settings_field( 'cursor_image1', 'Cursor image', 'cursor_custom_cursor_image_cb1', 'cursor-settings', 'cursor_image_section_id'  );
    add_settings_field( 'cursor_image2', '', 'cursor_custom_cursor_image_cb2', 'cursor-settings', 'cursor_image_section_id'  );
    add_settings_field( 'cursor_image3', '', 'cursor_custom_cursor_image_cb3', 'cursor-settings', 'cursor_image_section_id'  );

}


function cursor_custom_sanitize_callback_set( $options ) {
    $options__old = get_option( 'cursor_details' );

    if ( isset( $_FILES ) ) {

        foreach ( $_FILES as $file => $value ) {

            $file = sanitize_text_field( $file );

            if ( ! empty( $value['tmp_name'] ) )  {

                $value['type'] = sanitize_mime_type( $value['type'] );

                if ( $value['type'] == 'image/gif' || $value['type'] == 'image/png' || $value['type'] == 'image/jpeg' || $value['type'] == 'image/GIF' || $value['type'] == 'image/PNG' || $value['type'] == 'image/JPEG' ) {
                    $overrides = array( 'test_form' => false );
                    $fileimg = wp_handle_upload( $value, $overrides );

                    if ( ! is_null( $fileimg['url'] ) ) {
                        $options[ $file ] = cursor_custom_img_new_create( $fileimg['url'], $file );
                    }

                } else {
                    add_settings_error( 'my_option', 'settings_updated', 'Sorry, but you cannot use a file of this format.<strong> Only jpg, jpeg, png, gif.</strong>', 'error' );
                    $options[ $file ]['url_new'] = isset( $options__old[ $file ]['url_new'] ) ? $options__old[ $file ]['url_new'] : 'none';
                }
            } else {

                if ( ! empty( $_POST['cursor_details'][ $file ]['size'] ) && isset( $options__old[ $file ]['url'] ) ) {
                    $options[ $file ] = cursor_custom_resize( $_POST['cursor_details'][ $file ]['size'], $options__old[ $file ], $file );
                }

            }
        }
    }


    $options['use_url_body'] = isset( $_POST['cursor_details']['use_url_body'] ) ? sanitize_text_field( $_POST['cursor_details']['use_url_body'] ) : 'off';
    $options['url_body']     = isset( $_POST['cursor_details']['url_body'] ) ? esc_url( $_POST['cursor_details']['url_body'] ) : '';
    $options['use_url_link'] = isset( $_POST['cursor_details']['use_url_link'] ) ? sanitize_text_field( $_POST['cursor_details']['use_url_link'] ) : 'off';
    $options['url_link']     = isset( $_POST['cursor_details']['url_link'] ) ? esc_url( $_POST['cursor_details']['url_link'] ) : '';

    $options['onlink']   = isset( $_POST['cursor_details']['onlink'] ) ? sanitize_text_field( $_POST['cursor_details']['onlink'] ) : 'off';
    $options['onbutton'] = isset( $_POST['cursor_details']['onbutton'] ) ? sanitize_text_field( $_POST['cursor_details']['onbutton'] ) : 'off';



    if ( isset( $_POST['cursor_details']['delete1'] ) &&  $_POST['cursor_details']['delete1'] == 'on' ) {
        unset( $options['cursor_file1'] );
    }

    if ( isset( $_POST['cursor_details']['delete2'] ) &&  $_POST['cursor_details']['delete2'] == 'on' ) {
        unset( $options['cursor_file2'] );
    }

    if ( isset( $_POST['cursor_details']['delete3'] ) &&  $_POST['cursor_details']['delete3'] == 'on' ) {
        unset( $options['cursor_file3'] );
    }


    $clean_options = array();
    foreach ( $options as $key_ => $val_ ) {

        if ( is_array( $val_ ) ) {
            foreach ( $val_ as $k => $v ) {
                $clean_options[ $key_ ][ $k ] = wp_filter_nohtml_kses( $v );
            }
        } else {
            $clean_options[ $key_ ] = wp_filter_nohtml_kses( $val_ );
        }
    }

    return $clean_options;
}
// end of cursor_custom_sanitize_callback_set()


function cursor_custom_img_new_create( $img_old, $dir ) {
    $result = array();
    $cursor_image = wp_get_image_editor( $img_old );

    // v1.1 Resolving an error caused by the inability to process certain images
    if ( ! is_wp_error( $cursor_image ) ) {

        $img_size = $cursor_image->get_size( $img_old );
        if ( $img_size['width'] > 60 ) {
            $path = __DIR__ . "/cursor/$dir/*";
            $trash_file = glob( $path );

            foreach ( $trash_file as $file ) {

                if ( is_file( $file ) ) {
                    unlink( $file );
                }

            }

            $cursor_image->resize( 60 , NULL, false );
            $filename = $cursor_image->generate_filename( 'NEW_CURSOR', __DIR__ . "/cursor/$dir/", NULL );
            $saved = $cursor_image->save( $filename) ;
            $result['url'] = $img_old;
            $result['url_new'] = plugins_url( "/cursor/$dir/{$saved['file']}", __FILE__ );
            $result['size'] = $img_size['width'];
            $result['size_new'] = 60;
            return $result;
        } else {
            $result['url'] = $img_old;
            $result['url_new'] = $img_old;
            $result['size'] = $img_size['width'];
            $result['size_new'] = $img_size['width'];
            return $result;
        }

    } else {
        add_settings_error( 'my_option', 'settings_updated', 'Sorry, but this image could not be processed.', 'error' );
    }

}


function cursor_custom_resize( $new_size, $options_old, $dir ) {

    $new_size = (int) sanitize_text_field( $new_size );

    if ( $new_size == $options_old['size_new'] ) {
        return $options_old;
    }

    $result = array();
    $path = __DIR__ . "/cursor/$dir/*";
    $trash_file = glob( $path );

    foreach ( $trash_file as $file ) {

        if ( is_file( $file ) ) {
            unlink( $file );
        }

    }

    $cursor_original = wp_get_image_editor( $options_old['url'] );
    $cursor_old_size = $cursor_original->get_size( $options_old['url'] );
    $cursor_original->resize( $new_size , NULL, false );
    $filename = $cursor_original->generate_filename( 'NEW_CURSOR', __DIR__ . "/cursor/$dir/", NULL );
    $saved = $cursor_original->save( $filename );
    $result['url'] = $options_old['url'];
    $result['url_new'] = plugins_url( "/cursor/$dir/{$saved['file']}", __FILE__ );
    $result['size'] = $cursor_old_size['width'];
    $result['size_new'] = $new_size;
    return $result;
}


function cursor_custom_cursor_image_cb1() {
    $options = get_option( 'cursor_details' );
    ?>
    <div class="cursor_details_onbody">
        <h4>Body</h4>
        <p>
            <input type="file" name="cursor_file1" id="cursor_image1" />
        </p>
        <?php if ( isset( $options['cursor_file1']['url_new'] ) ) { ?>
            <p style="margin: 20px auto; min-height:65px; display:flex; align-items:center; justify-content:center;">
                <img id="img-adm-cursor1" src="<?php echo esc_url( $options['cursor_file1']['url'] ); ?>" style="width: <?php echo esc_attr( (int) $options['cursor_file1']['size_new'] ); ?>px;" alt="" />
            </p>
            <div style="margin:20px 0px; max-width:100%; text-align:center;">
                <input type="hidden"  name="cursor_details[cursor_file1][size]" id="cursor-size1" value="" />
                <div id="size1"></div>
                <p>
                    <span id="amount1"></span><span> pixels </span>
                </p>
                <p>
                    <input type="checkbox" name="cursor_details[delete1]" id="cursor-body-onlink-delete1" class="regular-text" value="on" />
                    <label for="cursor-body-onlink-delete1"> Delete this image </label>
                </p>
            </div>
        <?php } ?>
        <div class="cursor_url_block" >
            <?php
            $url_body = '';
            if( isset( $options['url_body'] ) ){
                $url_body = $options['url_body'];
            }
            ?>
            <input type="checkbox" name="cursor_details[use_url_body]" id="use_url_body" class="regular-text" <?php checked( $options['use_url_body'], 'on' ) ?> />
            <label for="use_url_body">Use image url?</label><br />
            <input type="text" name="cursor_details[url_body]" class="regular-text" value="<?php echo esc_url( $url_body ) ?>" />
            <span class="recommend_for_url">Less than 60x60 px is recommended</span>
        </div>
    </div>

    <?php
}


function cursor_custom_cursor_image_cb2() {
    $options = get_option( 'cursor_details' );
    isset( $options['onlink'] ) ? $options['onlink'] : $options['onlink'] = 'on';
    ?>
    <div class="cursor_details_onlink" >
        <h4>Links</h4>
        <input type="checkbox" name="cursor_details[onlink]" id="cursor-body-onlink" class="regular-text" <?php checked( $options['onlink'], 'on' ) ?> />
        <label for="cursor-body-onlink">Do not change the cursor when hovering over a <strong>link</strong>.</label><br />
        <a class="subinput-togler" href="">upload another image for links</a>
        <div class="subinput <?php if( isset( $options['cursor_file2']['url_new'] ) ) print 'img-loaded' ; ?>" style="margin:20px 0px; max-width:100%; text-align:center;">
            <input type="file" name="cursor_file2" id="cursor_image2" />
            <?php if ( isset( $options['cursor_file2']['url_new'] ) ) { ?>
                <p style="margin: 20px auto; min-height:65px; display:flex; align-items:center;justify-content:center;" >
                    <img id="img-adm-cursor2" src="<?php echo esc_url( $options['cursor_file2']['url'] ); ?>" style="width: <?php echo esc_attr( (int) $options['cursor_file2']['size_new'] ); ?>px;" alt="" />
                </p>
                <input type="hidden"  name="cursor_details[cursor_file2][size]" id="cursor-size2" value="" />
                <div id="size2"></div>
                <p>
                    <span id="amount2"></span><span> pixels </span>
                </p>
                <p>
                    <input type="checkbox" name="cursor_details[delete2]" id="cursor-body-onlink-delete2" class="regular-text" value="on" />
                    <label for="cursor-body-onlink-delete2"> Delete this image </label>
                </p>
            <?php  }?>
        </div>


        <div class="cursor_url_block" >
            <?php
                $url_link = '';
                if( isset( $options['url_link'] ) ){
                    $url_link = $options['url_link'];
                }
            ?>
            <input type="checkbox" name="cursor_details[use_url_link]" id="use_url_link" class="regular-text" <?php checked( $options['use_url_link'], 'on' ) ?> />
            <label for="use_url_link">Use image url?</label><br />
            <input type="text" name="cursor_details[url_link]" class="regular-text" value="<?php echo esc_url( $url_link ) ?>" />
            <span class="recommend_for_url">Less than 60x60 px is recommended</span>
        </div>

    </div>
    <?php
}



function cursor_custom_cursor_image_cb3() {
    $options = get_option( 'cursor_details' );
    isset( $options['onbutton'] ) ? $options['onbutton'] : $options['onbutton'] = 'on';
    ?>
    <div class="cursor_details_onbutton" >
        <input type="checkbox" name="cursor_details[onbutton]" id="cursor-body-onbutton" class="regular-text" <?php checked( $options['onbutton'], 'on' ) ?>  />
        <label for="cursor-body-onbutton">Do not change the cursor when hovering over a <strong>button</strong>.</label> <br />
        <a class="subinput-togler" href="">upload another image for buttons</a>
        <div class="subinput <?php if( isset($options['cursor_file3']['url']) ) print 'img-loaded'; ?>" style="margin:20px 0px;max-width:200px; text-align:center;">
            <input type="file" name="cursor_file3" id="cursor_image3" />
            <?php if ( isset( $options['cursor_file3']['url_new'] ) ) { ?>
                <p style="margin: 20px auto; min-height:65px; display:flex; align-items:center;justify-content:center;">
                    <img id="img-adm-cursor3" src="<?php echo esc_url( $options['cursor_file3']['url'] ); ?>" style="width: <?php echo esc_attr( (int) $options['cursor_file3']['size_new'] ); ?>px;"  alt="" />
                </p>
                <input type="hidden"  name="cursor_details[cursor_file3][size]" id="cursor-size3" value="" />
                <div id="size3"></div>
                <p>
                    <span id="amount3"></span><span> pixels </span>
                </p>
                <p>
                    <input type="checkbox" name="cursor_details[delete3]" id="cursor-body-onlink-delete3" class="regular-text" value="on" />
                    <label for="cursor-body-onlink"> Delete this image </label>
                </p>
            <?php  } ?>
        </div>
    </div>
    <?php
}


// Create page
function cursor_custom_create_page() {
    add_theme_page( 'Cursor Settings', 'Cursor Settings', 'manage_options', 'cursor-settings', 'cursor_custom_cursor_settings_render_page' );
}


function cursor_custom_cursor_settings_render_page() {
    $options = get_option( 'cursor_details' );
    settings_errors();
    ?>
    <div class="wrap">
        <h2>Cursor Settings</h2>
        <form action="options.php" method="post" enctype="multipart/form-data">
            <?php settings_fields( 'cursor_details' ); ?>
            <?php do_settings_sections( 'cursor-settings' ); ?>
            <?php submit_button(); ?>
        </form>
        <div id="cursor-customizer-author-msg"><strong>This is free software.</strong><a href="https://www.paypal.com/paypalme/alexanderkoledov">Donate for plugin support.</a></div>
    </div>
    <?php
}