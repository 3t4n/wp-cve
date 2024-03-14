<?php
/**
 * check condtions to display messenger or not
 * get app id
 * get page id
 * and add it to script, div
 */

if (!defined('ABSPATH')) exit;

if (!class_exists('HTCC_Test_Chatbot')) :

	class HTCC_Test_Chatbot
	{

		public $api;

		public function __construct()
		{
			$this->api = new MobileMonkeyApi();
		}

		public function chatbot()
		{

			$htcc_options = ht_cc()->variables->get_option;
			$htcc_js_options = get_option('htcc_fb_js_src');
			if (isset($_GET['page']) && $_GET['page']== HTCC_PLUGIN_MAIN_MENU){
			?>
			<script src='<?php echo $htcc_js_options?>'></script>
			<script>
                var oldCB = window.fbAsyncInit;
                window.fbAsyncInit = function () {
                    if (typeof oldCB === 'function') {
                        oldCB();
                    }
                    var waitForEl = function(selector, callback) {
                        if (jQuery(selector).length) {
                            callback();
                        } else {
                            setTimeout(function() {
                                waitForEl(selector, callback);
                            }, 100);
                        }
                    };

                    waitForEl('.fb_dialog', function() {
                        jQuery('#fb-root').hide();
                    });
                    FB.Event.subscribe('send_to_messenger', function (e) {
                        if (e.event === 'opt_in') {
                            jQuery('#fb-root').show();
                            jQuery('.test-bot-button').hide();
                            FB.XFBML.parse(jQuery("#htcc-messenger").ref);
                            jQuery('.testchat').show();
                            jQuery('.testchat').on('click',function () {
                                setTimeout(function(){
                                    /*FB.XFBML.parse(e.ref);*/
                                    jQuery('.test-bot-button').show();
                                    jQuery('.testchat').hide();
                                }, 5000);
                            })
                        }
                    });
                };
			</script>

			<?php
			}
		}



	}


	$chatbot = new HTCC_Test_Chatbot();

	add_action( 'admin_head', array( $chatbot, 'chatbot' ));


endif; // END class_exists check