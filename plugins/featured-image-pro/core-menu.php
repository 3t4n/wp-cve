<?php
	$plugindir = plugin_dir_path( __FILE__ );
if ( !class_exists( 'featured_image_pro_settings' ) )
{
	class featured_image_pro_settings
	{
		public function __construct()
		{
			if ( defined ( 'FEATURED_IMAGE_PRO' ) )
				add_action( 'admin_menu', array( $this, 'featured_image_pro_options' ), 10 );
		}
		/**
		 * Add options page
		 */
		public function featured_image_pro_options()
		{
			add_options_page( 'Featured Image Pro', 'Featured Image Pro Options', 'manage_options', 'featured-image-pro-setting-admin', array( $this, 'featured_image_pro_page' ) );
		}
		public function featured_image_pro_page( )
		{
			// Set class property
?>
        <div>
                 <h3>Thank you for using Featured Image Pro, a plugin by <a href='https://shooflysolutions.com'>Shoofly Solutions</a></h3>

                <p><a href='http://www.shooflysolutions.com/contact-3/' target='_blank'>Contact us</a></p>
                <p><a href='http://plugins.shooflysolutions.com/featured-image-pro/plugin-documentation/' target='_blank'>Documentation</a></p>
  	             <p>Donations for extended support are very much appreciated but never required!</p>
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
               <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="FTBD2UDXFJDB6">
                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                </form>
                         </div>
            <div >
                <a target='_blank' href="https://wordpress.org/plugins/featured-image-pro/">Please rate this plugin!</a>
            </div>
                    <div class="wrap">
            <h1>Featured Image Pro Widget & Shortcode Options</h1>
            <?php include_once(  'options.html' );?>
        </div>



        <?php
		}
	}
	new featured_image_pro_settings();
}
