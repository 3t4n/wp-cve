<?php
/**
 * @package Book_Now
 * @version 1.5
 */
/*
Plugin Name: Book Now
Plugin URI: https://www.riangraphics.com/book-now/
Description: This plugin adds a fixed call to action button to your site, with text and link to anywhere you want.
Author: RianGraphics
Version: 1.5
Author URI: https://www.riangraphics.com/book-now/
*/

add_action( 'admin_menu', 'book_now_menu' );

function book_now_menu() {
	add_menu_page( 'Book Now', 'Book Now', 'manage_options', 'book-now-page.php', 'book_now_page', plugin_dir_url( __FILE__ ) . 'img/icon.png', 6  );
add_action( 'admin_init', 'register_book_now_settings' );

}

function register_book_now_settings() {
	//register our settings
        register_setting( 'book-now-settings-group', 'rg_book_enable' );
	    register_setting( 'book-now-settings-group', 'rg_book_text' );
	    register_setting( 'book-now-settings-group', 'rg_book_url' );
	    register_setting( 'book-now-settings-group', 'rg_left_right' );
        register_setting( 'book-now-settings-group', 'rg_book_color' );
        register_setting( 'book-now-settings-group', 'rg_text_color' );
        register_setting( 'book-now-settings-group', 'rg_book_bottom' );
        register_setting( 'book-now-settings-group', 'rg_page_id' );
        register_setting( 'book-now-settings-group', 'rg_target' );
        register_setting( 'book-now-settings-group', 'rg_width' );
		register_setting( 'book-now-settings-group', 'rg_font_size' );
		register_setting( 'book-now-settings-group', 'rg_font_size_m' );
		register_setting( 'book-now-settings-group', 'rg_btn_pad' );
		register_setting( 'book-now-settings-group', 'rg_btn_pad_m' );
		register_setting( 'book-now-settings-group', 'rg_font_family' );
}


// Add Options to post types
add_action( 'add_meta_boxes', 'rg_meta_box_add' );
function rg_meta_box_add() {
    $post_types = get_post_types( array('public' => true) );
	foreach($post_types as $post_typess) {
		add_meta_box( 'my-meta-box-id', 'Book Now Options', 'rg_meta_box_bn', $post_typess, 'side', 'high' );
	}
}
function rg_meta_box_bn( $post ) {
    $values = get_post_custom( $post->ID );
    $enable = isset( $values['my_meta_box_enable'] ) ? esc_attr( $values['my_meta_box_enable'][0] ) : '';
	$text = isset( $values['my_meta_box_text'] ) ? esc_attr( $values['my_meta_box_text'][0] ) : '';
	$url = isset( $values['my_meta_box_url'] ) ? esc_attr( $values['my_meta_box_url'][0] ) : '';
	$bg = isset( $values['my_meta_box_bg'] ) ? esc_attr( $values['my_meta_box_bg'][0] ) : '';
	$txtcolor = isset( $values['my_meta_box_txtcolor'] ) ? esc_attr( $values['my_meta_box_txtcolor'][0] ) : '';
	$target = isset( $values['my_meta_box_target'] ) ? esc_attr( $values['my_meta_box_target'][0] ) : '';
	$width = isset( $values['my_meta_box_width'] ) ? esc_attr( $values['my_meta_box_width'][0] ) : '';
	$fontsize = isset( $values['my_meta_box_font_size'] ) ? esc_attr( $values['my_meta_box_font_size'][0] ) : '';
	$fontsizem = isset( $values['my_meta_box_font_size_m'] ) ? esc_attr( $values['my_meta_box_font_size_m'][0] ) : '';
	$btnpad = isset( $values['my_meta_box_btn_pad'] ) ? esc_attr( $values['my_meta_box_btn_pad'][0] ) : '';
	$btnpadm = isset( $values['my_meta_box_btn_pad_m'] ) ? esc_attr( $values['my_meta_box_btn_pad_m'][0] ) : '';
    wp_nonce_field( 'my_meta_box_nonce', 'meta_box_nonce' );
    ?>
    <p>
        <label for="my_meta_box_enable">Enable/Disable for this page/post</label>
        <input name="my_meta_box_enable" id="my_meta_box_enable" type="checkbox" value="1" <?php checked( '1', $enable ); ?> />
    </p>
    <p>
        <label for="my_meta_box_text">Short text to display</label>
        <input type="text" name="my_meta_box_text" id="my_meta_box_text" value="<?php echo $text; ?>" />
    </p>
    <p>
        <label for="my_meta_box_url">Full url</label><br/>
        <input type="text" name="my_meta_box_url" id="my_meta_box_url" value="<?php echo $url; ?>" />
    </p>
<p>
	<label for="my_meta_box_target">Target</label>
	<select name="my_meta_box_target" id="my_meta_box_target">
	    <option disabled selected value> -- select an option -- </option>
        <option <?php if($target == '_blank') { echo 'selected';} ?> value="_blank">New window (_blank)</option>
        <option <?php if($target == '_self') { echo 'selected';} ?> value="_self">Same window (_self)</option>
        <option <?php if($target == '_parent') { echo 'selected';} ?> value="_parent">Parent frame (_parent)</option>
        <option <?php if($target == '_top') { echo 'selected';} ?> value="_top">Opens the linked document in the full body of the window
 (_top)</option>
       </select>
</p>
    <p>
        <label for="my_meta_box_bg">Background Color ex(#000000)</label>
        <input type="text" name="my_meta_box_bg" id="my_meta_box_bg" value="<?php echo $bg; ?>" />
    </p>
    <p>
        <label for="my_meta_box_txtcolor">Text Color ex(#FFFFFF)</label>
        <input type="text" name="my_meta_box_txtcolor" id="my_meta_box_txtcolor" value="<?php echo $txtcolor; ?>" />
    </p>
	<p>
        <label for="my_meta_box_font_size">Font size (px)</label>
        <input type="text" name="my_meta_box_font_size" id="my_meta_box_font_size" value="<?php echo $fontsize; ?>" />
    </p>
	<p>
        <label for="my_meta_box_font_size_m">Font size Mobile (px)</label>
        <input type="text" name="my_meta_box_font_size_m" id="my_meta_box_font_size_m" value="<?php echo $fontsizem; ?>" />
    </p>
	<p>
        <label for="my_meta_box_btn_pad">Button padding</label>
        <input type="text" name="my_meta_box_btn_pad" id="my_meta_box_btn_pad" value="<?php echo $btnpad; ?>" />
    </p>
	<p>
        <label for="my_meta_box_btn_pad_m">Button padding Mobile</label>
        <input type="text" name="my_meta_box_btn_pad_m" id="my_meta_box_btn_pad_m" value="<?php echo $btnpadm; ?>" />
    </p>
    <p>
        <label for="my_meta_box_width">Custom button width ex(200px)</label>
        <input type="text" name="my_meta_box_width" id="my_meta_box_width" value="<?php echo $width; ?>" />
    </p>
    <?php
}
add_action( 'save_post', 'rg_meta_box_save' );
function rg_meta_box_save( $post_id ) {
    // Bail if we're doing an auto save
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    // if our nonce isn't there, or we can't verify it, bail
    if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'my_meta_box_nonce' ) ) return;
    // if our current user can't edit this post, bail
    if( !current_user_can( 'edit_post', $post_id ) ) return;
    // now we can actually save the data
    $allowed = array(
        'a' => array( // on allow a tags
            'href' => array() // and those anchords can only have href attribute
        )
    );
    // Probably a good idea to make sure your data is set
    if( isset( $_POST['my_meta_box_enable'] ) )
        update_post_meta( $post_id, 'my_meta_box_enable', wp_kses( $_POST['my_meta_box_enable'], $allowed ) );
	if( isset( $_POST['my_meta_box_text'] ) )
        update_post_meta( $post_id, 'my_meta_box_text', wp_kses( $_POST['my_meta_box_text'], $allowed ) );
	if( isset( $_POST['my_meta_box_url'] ) )
        update_post_meta( $post_id, 'my_meta_box_url', wp_kses( $_POST['my_meta_box_url'], $allowed ) );
	if( isset( $_POST['my_meta_box_bg'] ) )
        update_post_meta( $post_id, 'my_meta_box_bg', wp_kses( $_POST['my_meta_box_bg'], $allowed ) );
	if( isset( $_POST['my_meta_box_txtcolor'] ) )
        update_post_meta( $post_id, 'my_meta_box_txtcolor', wp_kses( $_POST['my_meta_box_txtcolor'], $allowed ) );
	if( isset( $_POST['my_meta_box_font_size'] ) )
        update_post_meta( $post_id, 'my_meta_box_font_size', wp_kses( $_POST['my_meta_box_font_size'], $allowed ) );
	if( isset( $_POST['my_meta_box_font_size_m'] ) )
        update_post_meta( $post_id, 'my_meta_box_font_size_m', wp_kses( $_POST['my_meta_box_font_size_m'], $allowed ) );
	if( isset( $_POST['my_meta_box_btn_pad'] ) )
        update_post_meta( $post_id, 'my_meta_box_btn_pad', wp_kses( $_POST['my_meta_box_btn_pad'], $allowed ) );
	if( isset( $_POST['my_meta_box_btn_pad_m'] ) )
        update_post_meta( $post_id, 'my_meta_box_btn_pad_m', wp_kses( $_POST['my_meta_box_btn_pad_m'], $allowed ) );
	if( isset( $_POST['my_meta_box_target'] ) )
        update_post_meta( $post_id, 'my_meta_box_target', wp_kses( $_POST['my_meta_box_target'], $allowed ) );
	if( isset( $_POST['my_meta_box_width'] ) )
        update_post_meta( $post_id, 'my_meta_box_width', wp_kses( $_POST['my_meta_box_width'], $allowed ) );
}


function book_now_page() {
?>
<div class="wrap">
    <img src="<?php echo plugin_dir_url( __FILE__ ) . 'img/book-now-logo.png'; ?>" alt="Book Now" />
		<h2>Book Now Settings</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'book-now-settings-group' ); ?>
    <?php do_settings_sections( 'book-now-settings-group' ); ?>
	<?php //echo get_option( 'rg_book_enable' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Enable/Disable</th>
        <td><input name="rg_book_enable" type="checkbox" value="1" <?php checked( '1', get_option( 'rg_book_enable' ) ); ?> /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Short text to display</th>
        <td><input type="text" name="rg_book_text" value="<?php echo esc_attr( get_option('rg_book_text', 'Book Now') ); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Full url</th>
        <td><input type="text" name="rg_book_url" value="<?php echo esc_attr( get_option('rg_book_url', 'http://example.com') ); ?>" /></td>
        </tr>

        <tr valign="top">
        <th scope="row">Link Target</th>
        <td>
<select name="rg_target">
         <option <?php if(get_option('rg_target') == '_blank') { echo 'selected';} ?> value="_blank">New window (_blank)</option>
        <option <?php if(get_option('rg_target') == '_self') { echo 'selected';} ?> value="_self">Same window (_self)</option>
        <option <?php if(get_option('rg_target') == '_parent') { echo 'selected';} ?> value="_parent">Parent frame (_parent)</option>
        <option <?php if(get_option('rg_target') == '_top') { echo 'selected';} ?> value="_top">Opens the linked document in the full body of the window
 (_top)</option>
       </select>
</td>
        </tr>

        <tr valign="top">
        <th scope="row">Left or Right</th>
        <td>
<select name="rg_left_right">
         <option <?php if(get_option('rg_left_right') == 'left') { echo 'selected';} ?> value="left">Left</option>
        <option <?php if(get_option('rg_left_right') == 'right') { echo 'selected';} ?> value="right">Right</option>
       </select>
</td>
        </tr>
        <tr valign="top">
        <th scope="row">Bottom or not on mobile</th>
        <td><input name="rg_book_bottom" type="checkbox" value="1" <?php checked( '1', get_option( 'rg_book_bottom' ) ); ?> /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Background Color ex(#000000)</th>
        <td><input type="text" name="rg_book_color" value="<?php echo esc_attr( get_option('rg_book_color', '#000000') ); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Text Color ex(#FFFFFF)</th>
        <td><input type="text" name="rg_text_color" value="<?php echo esc_attr( get_option('rg_text_color', '#FFFFFF') ); ?>" /></td>
        </tr>
				<tr valign="top">
        <th scope="row">Font Family</th>
        <td><input type="text" name="rg_font_family" value="<?php echo esc_attr( get_option('rg_font_family', 'Arial') ); ?>" /></td>
        </tr>
		<tr valign="top">
        <th scope="row">Font size (px)</th>
        <td><input type="text" name="rg_font_size" value="<?php echo esc_attr( get_option('rg_font_size', '16px') ); ?>" /></td>
        </tr>
		<tr valign="top">
        <th scope="row">Font size Mobile (px)</th>
        <td><input type="text" name="rg_font_size_m" value="<?php echo esc_attr( get_option('rg_font_size_m', '16px') ); ?>" /></td>
        </tr>
		<tr valign="top">
        <th scope="row">Button padding</th>
        <td><input type="text" name="rg_btn_pad" value="<?php echo esc_attr( get_option('rg_btn_pad', '10px') ); ?>" /></td>
        </tr>
		<tr valign="top">
        <th scope="row">Button padding Mobile</th>
        <td><input type="text" name="rg_btn_pad_m" value="<?php echo esc_attr( get_option('rg_btn_pad_m', '3%') ); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Custom button width ex(200px)</th>
        <td><input type="text" name="rg_width" value="<?php echo esc_attr( get_option('rg_width', '200px') ); ?>" /></td>
        </tr>
        <tr valign="top">
        <th scope="row">Exclude pages/posts, comma separated for multiple values</th>
        <td><input type="text" name="rg_page_id" value="<?php echo esc_attr( get_option('rg_page_id', '') ); ?>" /></td>
        </tr>
    </table>

    <?php submit_button(); ?>

</form>
</div>
<?php }


function book_now() {

	if (get_post_meta(get_the_ID(), 'my_meta_box_text', true)) {
	    $mytext = __(get_post_meta(get_the_ID(), 'my_meta_box_text', true));
	} else {
		$mytext = __(get_option('rg_book_text'));
	}

	if (get_post_meta(get_the_ID(), 'my_meta_box_url', true)) {
	    $myurl = __(get_post_meta(get_the_ID(), 'my_meta_box_url', true));
	} else {
		$myurl = __(get_option('rg_book_url'));
	}

	if (get_post_meta(get_the_ID(), 'my_meta_box_enable', true)) {
	    $mytrue = __(get_post_meta(get_the_ID(), 'my_meta_box_enable', true));
	} else {
		$mytrue = get_option( 'rg_book_enable' );
	}

	if (get_post_meta(get_the_ID(), 'my_meta_box_target', true)) {
	    $target = __(get_post_meta(get_the_ID(), 'my_meta_box_target', true));
	} else {
		$target = get_option( 'rg_target' );
	}

    $pageid = get_option( 'rg_page_id' );
if(!empty($pageid)) {
	$truepgid = explode(',',$pageid);
} else {
	$truepgid = "";
}

	if(!is_admin() && $mytrue === '1') {
if(!empty($truepgid)) {
	if(!is_page($truepgid) && !is_single($truepgid)) {
	echo "
              <div id='rg-book'>
              <a href='$myurl' target='$target'>$mytext</a>
              </div>
               ";
	}
  } else {
	echo "
              <div id='rg-book'>
              <a href='$myurl' target='$target'>$mytext</a>
              </div>
               ";
}
	}
}

// Now we set that function up to execute when the admin_notices action is called
add_action( 'wp_footer', 'book_now' );

// We need some CSS to position the paragraph
function book_css() {

       if (get_post_meta(get_the_ID(), 'my_meta_box_enable', true)) {
	    $mytrue = __(get_post_meta(get_the_ID(), 'my_meta_box_enable', true));
	} else {
		$mytrue = get_option( 'rg_book_enable' );
	}

	if (get_post_meta(get_the_ID(), 'my_meta_box_bg', true)) {
	    $bgcolor = __(get_post_meta(get_the_ID(), 'my_meta_box_bg', true));
	} else {
		$bgcolor = get_option('rg_book_color');
	}

	if (get_post_meta(get_the_ID(), 'my_meta_box_txtcolor', true)) {
	    $txtcolor = __(get_post_meta(get_the_ID(), 'my_meta_box_txtcolor', true));
	} else {
		$txtcolor = get_option('rg_text_color');
	}

	if (get_post_meta(get_the_ID(), 'my_meta_box_font_size', true)) {
	    $fontsize = __(get_post_meta(get_the_ID(), 'my_meta_box_font_size', true));
	} else {
		$fontsize = get_option('rg_font_size');
	}

	if (get_post_meta(get_the_ID(), 'my_meta_box_font_size_m', true)) {
	    $fontsizem = __(get_post_meta(get_the_ID(), 'my_meta_box_font_size_m', true));
	} else {
		$fontsizem = get_option('rg_font_size_m');
	}

	if (get_post_meta(get_the_ID(), 'my_meta_box_btn_pad', true)) {
	    $btnpad = __(get_post_meta(get_the_ID(), 'my_meta_box_btn_pad', true));
	} else {
		$btnpad = get_option('rg_btn_pad');
	}

	if (get_post_meta(get_the_ID(), 'my_meta_box_btn_pad_m', true)) {
	    $btnpadm = __(get_post_meta(get_the_ID(), 'my_meta_box_btn_pad_m', true));
	} else {
		$btnpadm = get_option('rg_btn_pad_m');
	}

	if (get_post_meta(get_the_ID(), 'my_meta_box_width', true)) {
	    $customwidth = __(get_post_meta(get_the_ID(), 'my_meta_box_width', true));
	} else {
		$customwidth = get_option('rg_width');
	}

        $mymobile = get_option( 'rg_book_bottom' );
				$fontfamily = get_option( 'rg_font_family' );

        $lor = get_option('rg_left_right');

        $numberofchars = strlen(get_option('rg_book_text'));

        $totalW = 25 * $numberofchars;

        $distance = 10 * $numberofchars;

        $deg = '';

        if($lor == 'right') {
         $deg = '-90deg';
        } else {
           $deg = '90deg';
        }
if( $mytrue == 1 ) {
	echo '
	<style type="text/css">
	#rg-book {
           position: fixed;
           transform: rotate('.$deg.');
           '.$lor.': -85px;
           width: '.$customwidth.';
           height: auto;
           text-align: center;
           padding:'.$btnpad.';
           border-top-left-radius: 10px;
           border-top-right-radius: 10px;
           z-index: 9999999;
           bottom: 40%;
           background: '.$bgcolor.';
           color: '.$txtcolor.'!important;
           box-shadow: 0 10px 20px rgba(0,0,0,0.19), 0 6px 6px rgba(0,0,0,0.23);

	}

      #rg-book a {
          color: '.$txtcolor.';
          text-transform:uppercase;
          font-size:'.$fontsize.';
          font-weight:700;
					font-family:'.$fontfamily.';
        }
@media screen and (max-width:767px) {
 #rg-book {
    position: fixed;
    transform: none;
    right: inherit;
	left: inherit;
    width: 100%;
    margin:0 auto;
    height: auto;
    text-align: center;
    padding: '.$btnpadm.';
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    z-index: 9999999;
    bottom: 0;
    background: '.$bgcolor.';
}

#rg-book a {
  text-transform:uppercase;
  font-size:'.$fontsizem.';
  font-weight:700;

}
}
	</style>
	';
}
if($mymobile !=1) {
    echo '<style>
    @media screen and (max-width:767px) {
        #rg-book {
            transform: rotate('.$deg.');
            '.$lor.': -'.$distance.'px;
            bottom: 40%;
            width: '.$totalW.'px;
       }
       }
       </style>';
}
}

add_action( 'wp_footer', 'book_css' );

?>
