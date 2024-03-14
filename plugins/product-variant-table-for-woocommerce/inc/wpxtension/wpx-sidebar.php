<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WPXtension_Sidebar' ) ) {
	class WPXtension_Sidebar {

		protected static $_instance = null;

		public static function instance() {
	        if ( is_null( self::$_instance ) ) {
	            self::$_instance = new self();
	        }

	        return self::$_instance;
	    }

	    public static function sidebar_start(){
	    	echo '
	    		 <div id="postbox-container-1" class="postbox-container">

            		<div class="meta-box-sortables">

	    	';
	    }

	    public static function sidebar_end(){
	    	echo '
	    		 	</div>
		            <!-- .meta-box-sortables -->

		        </div>
		        <!-- #postbox-container-1 .postbox-container -->

	    	';
	    }


	    public static function block($icon, $title, $details){

	    	do_action('wpx_sidebar_before_block');

	    	?>

	    	<div class="postbox">

                <h2><span class="<?php echo esc_attr($icon); ?>"></span><span><?php esc_attr_e(
                            $title, 'wpxtension'
                        ); ?></span></h2>

                <div class="inside">
                    <p><?php _e(
                            $details,
                            'wpxtension'
                        ); ?></p>
                </div>
                <!-- .inside -->

            </div>
            <!-- .postbox -->


            <?php

	    	do_action('wpx_sidebar_after_block');

	    }

		
	}

}
