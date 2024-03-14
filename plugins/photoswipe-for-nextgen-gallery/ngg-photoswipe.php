<?php
   /*
   Plugin Name: Photoswipe for NextGEN Gallery
   Plugin URI: http://adriza.net
   Description: Automatically use Photoswipe to navigate NextGEN galleries when using a mobile browser
   Version: 1.2.1
   Author: Guillermo Señas
   Author URI: http://adriza.net
   License: GPL2
   */
   
   /*  Copyright 2012 Guillermo Señas  (email : gsenas+nggphotoswipe@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function photoswipe_js() {
	if(!is_admin()) {
	
		$isMobile=(preg_match('/(alcatel|amoi|android|avantgo|blackberry|benq|cell|cricket|docomo|elaine|htc|iemobile|iphone|ipad|ipaq|ipod|j2me|java|midp|mini|mmp|mobi|motorola|nec-|nokia|palm|panasonic|philips|phone|playbook|sagem|sharp|sie-|silk|smartphone|sony|symbian|t-mobile|telus|up\.browser|up\.link|vodafone|wap|webos|wireless|xda|xoom|zte)/i', $_SERVER['HTTP_USER_AGENT']) || preg_match('/android.+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|meego.+mobile|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)));	
		
		if(get_option('nggphotoswipe_enabled')=="all" || (get_option('nggphotoswipe_enabled')=="mobile" && $isMobile) ) {				
			wp_enqueue_script('klass.min.js', plugins_url('photoswipe-for-nextgen-gallery/lib/photoswipe/klass.min.js', dirname(__FILE__)),array( 'jquery' ));
			wp_enqueue_script('code.photoswipe.jquery-3.0.5', plugins_url('photoswipe-for-nextgen-gallery/lib/photoswipe/code.photoswipe.jquery-3.0.5.min.js', dirname(__FILE__)),array( 'jquery' ));
			wp_enqueue_script('ngg-photoswipe.js',  plugins_url('photoswipe-for-nextgen-gallery/js/ngg-photoswipe.js', dirname(__FILE__)),array( 'jquery' ));
			wp_enqueue_style( 'photoswipe_css',  plugins_url('photoswipe-for-nextgen-gallery/lib/photoswipe/photoswipe.css', dirname(__FILE__)));
			
			//Add scripts to disable additional viewers:
			$options = get_option( 'additional_viewers' );
			if (1 == $options['fancybox']) {
				wp_enqueue_script('ngg-photoswipe-disable-fancybox.js',  plugins_url('photoswipe-for-nextgen-gallery/js/ngg-photoswipe-disable-fancybox.js', dirname(__FILE__)),array( 'jquery' ));
			}
		}
	}
}

add_action('init', 'photoswipe_js');

register_activation_hook( __FILE__, "nggphotoswipe_options_set" );
register_deactivation_hook( __FILE__, "nggphotoswipe_options_unset" );
add_action( 'admin_menu', 'nggphotoswipe_menu' );

function nggphotoswipe_menu() {
	add_submenu_page('nextgen-gallery', "Photoswipe", "Photoswipe", 'manage_options', __FILE__, 'nggphotoswipe_options_page');
}

function nggphotoswipe_options_set() {
	add_option("nggphotoswipe_enabled","mobile");
	add_option("additional_viewers","none");
}

function nggphotoswipe_options_unset() {
	delete_option("nggphotoswipe_enabled");
	delete_option("additional_viewers");
}

function nggphotoswipe_options_page() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	
	if ($_REQUEST['nggphotoswipe_enabled']) {
		update_option('nggphotoswipe_enabled',$_REQUEST['nggphotoswipe_enabled']);
	}
	
	update_option('additional_viewers',$_REQUEST['additional_viewers']);	
	?>
<div class="wrap">
<h2>Photoswipe for NextGEN Gallery</h2>

<form method="post">
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Plugin behavior</th>
        <td>
		<input type="radio" name="nggphotoswipe_enabled" value="mobile" <?php checked( get_option('nggphotoswipe_enabled'), "mobile", 1 ); ?>> Replace viewer with Photoswipe <u>only</u> on mobile browsers (Default, recommended)<br/>
		<input type="radio" name="nggphotoswipe_enabled" value="all" <?php checked( get_option('nggphotoswipe_enabled'), "all", 1 ); ?>> Always replace viewer with Photoswipe (Useful for testing)<br/>
		<input type="radio" name="nggphotoswipe_enabled" value="never" <?php checked( get_option('nggphotoswipe_enabled'), "never", 1 ); ?>> Never replace the viewer<br/>
		</td>
        </tr>
		<?php $options = get_option( 'additional_viewers' );  ?>
        <th scope="row">Disable other viewers</th>
        <td>
		<input type="checkbox" name="additional_viewers[fancybox]" value="1" <?php checked( $options['fancybox'], 1 ); ?>> Disable Fancybox<br/>
		</td>
        </tr>		
    </table>
    
    <?php global $wp_version;
		  if (version_compare($wp_version, '3.1', '>=')) {	
			submit_button();
		  } else { ?>
	<p class="submit"><input id="submit" class="button button-primary" type="submit" value="Submit" name="submit"></p>
	<?php } ?>
</form>
</div>	
<hr/>
<p>If this plugin saved your life, or if you consider it helpful, please donate at least <b>ten cents (0,10 €)</b></p>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/es_ES/i/scr/pixel.gif" width="1" height="1">
</form>
<?php } ?>