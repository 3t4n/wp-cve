<?php 

defined( 'ABSPATH' ) or die();
require_once( WL_COMPANION_PLUGIN_DIR_PATH . 'admin/inc/helpers/wl-companion-helper.php' );

/* class for font-family */
if ( class_exists( 'WP_Customize_Control' ) && ! class_exists( 'wheelify_Font_Control' ) ) :
	class wheelify_Font_Control extends WP_Customize_Control {
		public function render_content() {
			?>
            <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php $google_api_url = 'https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyC8GQW0seCcIYbo8xt_gXuToPK8xAMx83A';
			//lets fetch it
			$response = wp_remote_retrieve_body( wp_remote_get( $google_api_url, array( 'sslverify' => false ) ) );
			if ( $response == '' ) {
				echo '<script>jQuery(document).ready(function() {alert("Something went wrong! this works only when you are connected to Internet....!!");});</script>';
			}
			if ( is_wp_error( $response ) ) {
				echo 'Something went wrong!';
			} else {
				$json_fonts = json_decode( $response, true );
				// that's it
				if(isset($json_fonts['items'])) {
				$items = $json_fonts['items'];
				$i     = 0; ?>
                <select <?php $this->link(); ?> >
					<?php foreach ( $items as $item ) {
						$i ++;
						$str = $item['family']; ?>
                        <option value="<?php echo esc_attr( $str ); ?>" <?php if ( $this->value() == $str ) {
							echo 'selected="selected"';
						} ?>><?php echo esc_attr( $str ); ?></option>
					<?php } ?>
                </select>
				<?php
			} }
		}
	}
endif;
?>