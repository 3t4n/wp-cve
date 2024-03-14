<?php
// Prevent direct access.
  if ( ! defined( 'ABSPATH' ) ) {
	  
   die( 'Nice try, But not here!!!' );
   
  }

// Adding settings menu to admin panel.
  add_action( 'admin_menu', 'seo_breadcrumbs_menu' );

// Settings menu initialization.
  function seo_breadcrumbs_menu()
 { 

   add_menu_page ( 
		          'SEO Breadcrumbs Settings',
		          'SEO Breadcrumbs',
		          'manage_options',
		          'seo_breadcrumbs',
              'seo_breadcrumbs_settings_page',
              'dashicons-art',
              99
              );

// Registering settings option keys.
   add_action( 'admin_init', 'register_seo_breadcrumbs_settings_setup' );

// Adding default values once.
seo_breadcrumbs_default_settings(get_option ('sbc_default_settings'));

 }


function seo_breadcrumbs_default_settings ($flag) {
  if( $flag == "" ) {
  $arr1 = array ( 'id', 'separator','home','before_bgcolor','after_bgcolor','before_fontcolor','after_fontcolor','separator_color');
  $arr2 = array ( 'style0', '&#155;','Home','#00aaff','#ff0000','#3377bc','#00ffff','#000000');
  for ( $x=0; $x<count($arr1); $x++ ) {
  update_option( 'sbc_' . $arr1[$x], $arr2[$x] ); 
  }
  update_option( 'sbc_default_settings',"completed");
  }
}


// Option values validator.
function seo_breadcrumbs_settings_validator ( $value )  {

if( false == preg_match('/^#[a-f0-9]{6}$/i', $value) ){
$type = 'error';
$message = "Please use hex color values on color styles.";
    add_settings_error(
        'seo_breadcrumbs_errors',
        esc_attr( 'setting-error-colors' ),
        $message,
        $type
    );
} else {
return $value; }
}


// Settings options register and sanitize callback functions.
function  register_seo_breadcrumbs_settings_setup()
 {
  $arr = array('default','id','separator','home','before_bgcolor','after_bgcolor','before_fontcolor','after_fontcolor','separator_color');
  for ( $x=0; $x<count($arr); $x++) {
  if( $x > 4 ) {
       register_setting( 'seo-breadcrumbs-settings-group', 'sbc_'.$arr[$x],'seo_breadcrumbs_settings_validator');
  } else {
  register_setting( 'seo-breadcrumbs-settings-group', 'sbc_'.$arr[$x]);
  }
  }
}

// Setting page html php mixed markup.
  function  seo_breadcrumbs_settings_page()
 {
?>
<div class="wrap">

<p style="line-height:45px;">
<img src="<?php echo plugins_url ("images/seo-breadcrumbs.png",__FILE__); ?>" style="float:left; clear:both; display:inline-block;margin-right:8px;border:5px solid #e0e0e0;border-radius:5px;box-shadow:0px 0px 5px rgba(0,0,0,.5);" width="45" height="45" /> <h1>SEO Breadcrumbs </h1></p><br/>
<p><?php settings_errors(); ?></p>
 <form method="post" action="options.php"  name="seo_breadcrumbs">
<?php settings_fields( 'seo-breadcrumbs-settings-group' ); ?>
<?php do_settings_sections( 'seo-breadcrumbs-settings-group' ); ?>
 <table class="form-table sbc">
 <tr valign="top">
 <th scope="row"> Breadcrumbs  Style : </th>
 <td>

<p>
<input type="radio" name="sbc_id" value="style0" <?php checked(get_option('sbc_id'),'style0' ); ?> > Default ( Universal ) <font style="color:green;font-weight:bolder;"> recommended </font> <br/>
<img src="<?php echo plugins_url( 'images/style0.png', __FILE__ ); ?>" />
</p>

<p>
<input type="radio" name="sbc_id" value="style1" <?php checked(get_option('sbc_id'),'style1' ); ?> > Style 1 ( Suitable for Tab, Desktop ) <br/>
<img src="<?php echo plugins_url( 'images/style1.png', __FILE__ ); ?>" />
</p>

<p>
<input type="radio" name="sbc_id" value="style2" <?php checked(get_option('sbc_id'),'style2' ); ?> > Style 2 ( Suitable for Desktop )
<br/>
<img src="<?php echo plugins_url( 'images/style2.png', __FILE__ ); ?>" />
</p>

<p>
<input type="radio" name="sbc_id" value="style3" <?php checked(get_option('sbc_id'),'style3' ); ?> > Style 3 ( Suitable for Desktop ) <br/>
<img src="<?php echo plugins_url( 'images/style3.png', __FILE__ ); ?>" />
</p>

<p>
<input type="radio" name="sbc_id" value="style4" <?php checked(get_option('sbc_id'),'style4' ); ?> > Style 4 ( Suitable for Desktop ) <br/>
<img src="<?php echo plugins_url( 'images/style4.png', __FILE__ ); ?>" />
</p>

<p>
<input type="radio" name="sbc_id" value="style5" <?php checked(get_option('sbc_id'),'style5' ); ?> > Style 5 ( Suitable for Mobile, Tab, Desktop ) <br/>
<img src="<?php echo plugins_url( 'images/style5.png', __FILE__ ); ?>" />
</p>

<p>
<input type="radio" name="sbc_id" value="style6" <?php checked(get_option('sbc_id'),'style6' ); ?> > Style 6 ( Suitable for Desktop ) <br/>
<img src="<?php echo plugins_url( 'images/style6.png', __FILE__ ); ?>" />
</p>

<p>
<input type="radio" name="sbc_id" value="style7" <?php checked(get_option('sbc_id'),'style7' ); ?> > Style 7 ( Suitable for Mobile, Tab, Desktop ) <br/>
<img src="<?php echo plugins_url( 'images/style7.png', __FILE__ ); ?>" />
</p>
<p><span>Description:  </span> You can select the breadcrumbs styles what you want. We recommend if you want responsive to all devices use <i> Default </i> style. If your website is desktop fit sites, prefer and use other seven styles for attractive look on your website. </p>
 </tr>
<tr valign="top">
 <th scope="row"> Color Styles : </th>
 <td>
<input type="text" name="sbc_before_bgcolor" value="<?php echo esc_attr( get_option('sbc_before_bgcolor') ) ?>" class="wp_color_picker" /> <label for="sbc_before_bgcolor"> - Before bgcolor  </label> <br/>
<input type="text" name="sbc_after_bgcolor" value="<?php echo esc_attr( get_option('sbc_after_bgcolor') ) ?>" class="wp_color_picker" /><label for="sbc_after_bgcolor"> - After bgcolor </label><br/>
<input type="text" name="sbc_before_fontcolor" value="<?php echo esc_attr( get_option('sbc_before_fontcolor') ) ?>" class="wp_color_picker" />
<label for="sbc_before_fontcolor"> - Before fontcolor</label> <br/>
<input type="text" name="sbc_after_fontcolor" value="<?php echo esc_attr( get_option('sbc_after_fontcolor') ) ?>" class="wp_color_picker" /><label for="sbc_font_bgcolor"> - After fontcolor</label> <br/>
<input type="text" name="sbc_separator_color" value="<?php echo esc_attr( get_option('sbc_separator_color') ) ?>" class="wp_color_picker" />
<label for="sbc_separator_color"> - Separator color</label> <br/>
<p><span>Description:  </span> You can customize the breadcrumbs colors before after and sparator colors etc.</p>
</td>
</tr>
 <tr valign="top">
 <th scope="row"> Home Style : </th>
  <td><input type="text" name="sbc_home" value="<?php echo esc_attr( get_option('sbc_home') ) ?>" placeholder="ie. Home" />
<p> <span>Description: </span> Enter the name of starting crumb. You can also use fontawesome-icons or boostrap and dash-icons etc...
</p>
</td>
 </tr>
<tr valign="top">
 <th scope="row">Separator Style :</th>
 <td>
<input type="text" name="sbc_separator" value="<?php echo esc_attr( get_option('sbc_separator') ) ?>" placeholder="ie. &#155;" />
<p><span>Description:  </span> If you want to change crumb separator, you can set the symbol or any font icon.  This option only works on Default style mode, in other styles their is not separator. </p>
 </tr>

<tr valign="top">
 <th scope="row"> <font style="font-weight:bolder;">Donate to this plugin</font> :</th>
 <td>
<br/><center>
<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4D6477WYM43WG" target="_blank">
<img src="<?php echo plugins_url('images/plugin-donate.png',__FILE__); ?>" style="width:92px;height:26px;" alt="plugin donate" /></a></center>
<p><i>" We Need Our Support! 
Now a days, it is hard to continue our plugin development. with your help, we can make our development in better and best quality standards.  If you enjoy using our plugin and find it use, you can appreciate our work with a small amount of donation. your donation will help encourage and support the plugin's continued development and better user support definitely. " </i> <font style="font-weight:bolder;margin-left:10px;">- Thankyou </font></p> <br/>
</td>
 </tr>
    </table>
<?php submit_button(); ?>
</form>
</div>
<?php } ?>