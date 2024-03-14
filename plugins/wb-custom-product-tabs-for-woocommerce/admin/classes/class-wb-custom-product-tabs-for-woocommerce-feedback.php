<?php
if ( ! defined( 'WPINC' ) ) {
    die;
}

class Wb_Custom_Product_Tabs_For_Woocommerce_Feedback {
	private $reasons = array();
	public function __construct() {

		$this->reasons = array(
			'not-working' => __('Not working', 'wb-custom-product-tabs-for-woocommerce'),
			'found-better' => __('Found better', 'wb-custom-product-tabs-for-woocommerce'),
			'not-meet-my-requirements' => __("It doesn't meet my requirements", 'wb-custom-product-tabs-for-woocommerce'),
			'other' => __("Other", 'wb-custom-product-tabs-for-woocommerce'),
		);

        add_action( 'admin_footer', array($this, 'add_content') );
        add_action( 'wp_ajax_wb_cptb_submit_feedback', array($this, "submit_feedback") );
    }

    public function add_content() {
    	global $pagenow;
        if ( 'plugins.php' !== $pagenow ) {
            return;
        }

        ?>
        <style type="text/css">
        	.wb_cptb_feedback_popupbg{ position:fixed; z-index:100000000; width:100%; height:100%; background-color:rgba(0, 0, 0,.8); left:0px; top:0px; display:none;}
        	.wb_cptb_feedback_popup{ position:fixed; z-index:100000001; background:#fff; width:100%; max-width:600px; height:auto; left:50%; top:40%; transform:translate(-50%, -50%); box-sizing:border-box; box-shadow:0px 0px 2px #ccc; display:none;}
        	.wb_cptb_feedback_popup_head{ width:100%; box-sizing:border-box; padding:0px 15px; min-height:40px; background:#f0f6fc; font-size:14px; font-weight:bold; line-height:40px; }
        	.wb_cptb_feedback_popup_close{ float:right; min-width:40px; min-height:40px; margin-right:-15px; text-align:center; cursor:pointer; color:#d63638; }
        	.wb_cptb_feedback_popup_content{ width:100%; box-sizing:border-box; padding:15px; height:auto; font-size:14px; }
        	.wb_cptb_feedback_popup_content label{ width:100%; display:block; font-weight:bold; margin-top:20px; margin-bottom:5px;}
        	.wb_cptb_feedback_popup_content textarea{ width:100%; display:block; }
        </style>
        <script type="text/javascript">
        	jQuery(document).ready(function(){
        		jQuery(document).on('click', 'a#deactivate-wb-custom-product-tabs-for-woocommerce', function(e){
        			e.preventDefault();

        			if(!jQuery('.wb_cptb_feedback_popupbg').length)
        			{
        				jQuery('body').prepend('<div class="wb_cptb_feedback_popupbg"></div>');
        			}

        			jQuery('.wb_cptb_feedback_popup, .wb_cptb_feedback_popupbg').show();

        			jQuery('.wb-cptb-skip-and-deactivate').attr({'href': jQuery(this).attr('href')});
        		});

        		jQuery(document).on('click', '.wb_cptb_feedback_popup_close, .wb-cptb-cancel-uninstall', function(e){
        			jQuery('.wb_cptb_feedback_popup, .wb_cptb_feedback_popupbg').hide();
        		});

        		jQuery(document).on('click', 'button.wb-cptb-uninstall-submit', function(e){
        			e.preventDefault();
        			jQuery('.wb-cptb-skip-and-deactivate, .wb-cptb-uninstall-submit, .wb-cptb-cancel-uninstall').prop('disabled', true).addClass('disabled');
        			jQuery('.wb-cptb-uninstall-submit').html('<?php esc_html_e('Submitting...', 'wb-custom-product-tabs-for-woocommerce'); ?>');

        			jQuery.ajax({
                        url: '<?php echo esc_url( admin_url('admin-ajax.php') ); ?>',
                        type: 'POST',
                        data: {
                            action: 'wb_cptb_submit_feedback',
                            reason: jQuery('[name="wb-cptb-uninstall-reason"]').val(),
                            reason_brief: jQuery('[name="wb-cptb-uninstall-reason-brief"]').val(),
                        },
                        complete:function() {
                            window.location.href = jQuery('.wb-cptb-skip-and-deactivate').attr('href');
                        }
                    });

        		});
        	});
        </script>
        <div class="wb_cptb_feedback_popup">
        	<div class="wb_cptb_feedback_popup_head">
        		<?php esc_html_e('If you can take a moment, kindly share with us the reason for your deactivation', 'wb-custom-product-tabs-for-woocommerce'); ?>
        		<div class="wb_cptb_feedback_popup_close" title="<?php esc_attr_e('Close', 'wb-custom-product-tabs-for-woocommerce'); ?>">X</div>	
        	</div>
        	<div class="wb_cptb_feedback_popup_content">
        		<div>
        			<label><?php esc_html_e('Please choose a reason.', 'wb-custom-product-tabs-for-woocommerce'); ?></label>
        			<select name="wb-cptb-uninstall-reason">
        				<option value=""><?php esc_html_e('Choose a reason.', 'wb-custom-product-tabs-for-woocommerce'); ?></option>
        				<?php
        				foreach( $this->reasons as $key => $value ) {
        					?>
        					<option value="<?php echo esc_html($key); ?>"><?php echo esc_html($value); ?></option>
        					<?php
        				}
        				?>	
        			</select>
        		</div>
        		<div>
        			<label><?php esc_html_e('Can you provide us with additional information?', 'wb-custom-product-tabs-for-woocommerce'); ?></label>
        			<textarea name="wb-cptb-uninstall-reason-brief"></textarea>
        		</div>
        		<div>
        			<p><?php esc_html_e('No personal data is gathered when you submit this form.', 'wb-custom-product-tabs-for-woocommerce'); ?></p>
        		</div>
        		<div style="width:100%; margin-top:0px; padding:15px 0px; box-sizing:border-box; float:left;">
        			<button class="button button-primary wb-cptb-uninstall-submit" style="float:right;"><?php esc_html_e('Submit and deactivate', 'wb-custom-product-tabs-for-woocommerce'); ?></button>
        			<a class="button button-secondary wb-cptb-skip-and-deactivate" style="float:right; margin-right:10px;"><?php esc_html_e('Skip and deactivate', 'wb-custom-product-tabs-for-woocommerce'); ?></a>
        			<a class="button button-secondary wb-cptb-cancel-uninstall" style="float:right; margin-right:10px;"><?php esc_html_e('Cancel', 'wb-custom-product-tabs-for-woocommerce'); ?></a>
        		</div>
        	</div>
        </div>
        <?php
    }

    public function submit_feedback() {
    	global $wpdb;

        if (!isset($_POST['reason']) && 
        	(isset($_POST['reason']) && "" === trim($_POST['reason'])) 
        ) {
            return;
        }

        $data = array(
            'plugin' 			=> "wb_cptb",
            'version' 			=> WB_CUSTOM_PRODUCT_TABS_FOR_WOOCOMMERCE_VERSION,
            'date' 				=> gmdate("M d, Y h:i:s A"),
            'reason' 			=> sanitize_text_field($_POST['reason']),
            'reason_brief' 		=> isset($_REQUEST['reason_brief']) ? trim(stripslashes($_REQUEST['reason_brief'])) : '',
            'software' 			=> $_SERVER['SERVER_SOFTWARE'],
            'php_version' 		=> phpversion(),
            'mysql_version' 	=> $wpdb->db_version(),
            'wp_version' 		=> get_bloginfo('version'),
            'wc_version' 		=> (defined('WC_VERSION') ? WC_VERSION : ''),
            'locale' 			=> get_locale(),
            'multisite' 		=> is_multisite() ? 'Yes' : 'No',
        );
        

        $resp = wp_remote_post('https://feedback.webbuilder143.com/wp-json/feedback/v1', array(
            'method' 		=> 'POST',
            'timeout' 		=> 45,
            'redirection' 	=> 5,
            'httpversion' 	=> '1.0',
            'blocking' 		=> false,
            'body' 			=> $data,
            'cookies' 		=> array(),
            )
        );

        return;
    }
}

new Wb_Custom_Product_Tabs_For_Woocommerce_Feedback();