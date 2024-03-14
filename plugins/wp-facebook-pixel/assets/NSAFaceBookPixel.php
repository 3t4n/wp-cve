<?php

/**
 * FaceBookPixel short summary.
 *
 * FaceBookPixel description.
 *
 * @version 1.0
 * @author Jacob.Hulse
 * @company Night Shift Productions
 */
class NSAFaceBookPixel
{

    private $WPFacebookPixel;
    /**
     * Pixel identifier provided by Facebook on generation
     * @var string
     */
    private $PixelID = '';



    public function __construct() {
        add_action('wp_head',               array($this, 'insert_Main_Pixel'));
        add_action('wp_head',               array($this, 'getEvents'));
        add_filter('nsa_wpfbp_events',      array($this, 'get_Page_Events'));
        add_filter('nsa_wpfbp_events',      array($this, 'get_WooCommerce_Events'));
        add_action('wp_enqueue_scripts',    array($this, 'Enqueue_Scripts'));
        add_action('init',                  array($this, 'StartSession'), 1);


        global $WPFacebookPixel;
        $this->WPFacebookPixel = $WPFacebookPixel;

        /** SUPPORT FOR USER TYPE TRACKING */
		add_filter('nsa_wp_fbp_doAddPixel', function($doTrack) {
            global $WPFacebookPixel;
			//Do not track if already set to not track
			if(!$doTrack) return $doTrack;

			//Is the current user tracked?
			$track = false;
			$user_type_tracking = $WPFacebookPixel->settings->get_value('general', 'user_type_tracking');
			$current_user = wp_get_current_user();
			$roles = $current_user->roles;

            //Anonymous user, Track em!
            if (count($roles) == 0) return true;

			while(!$track && count($roles) > 0) {
				$role = array_shift($roles);
				$track = (in_array($role, $user_type_tracking));
			}
			return $track;
		});
		/** END SUPPORT FOR USER TYPE TRACKING */
    }


    public function StartSession() {
        if(!is_admin() && !session_id()) {
            session_start();
        }
    }


    /**
     * Registers scripts used by this plugin on the front end only.
     * 
     * Hooked into the 'wp_enqueue_scripts' action.
     */
    function Enqueue_Scripts()
    {
        if (!is_admin()) {
            wp_enqueue_script('jquery');

            wp_register_script('nsautilities.js', plugins_url('../inc/scripts/nsautilities.min.js', __FILE__), 'jquery', null, true);
            wp_localize_script('nsautilities.js', 'elementSelector', apply_filters('nsa_wpfbp_updateSelectorCode', array('pro' => '') ));
            wp_enqueue_script('nsautilities.js');
            wp_enqueue_script('NSAFacebookPixel.js', plugins_url('../assets/NSAFacebookPixel.min.js', __FILE__), 'nsautilities.js', null, true);
        }
    }


    /**
     * Indicates if we have a value in the Pixel Code
     * 
     * Returns True if we do
     * Returns False if no Pixel code was set
     * @return bool
     */
    private function doAddPixel() {
        //Do we have a Pixel ID?
        $pixel = $this->WPFacebookPixel->facebook_pixel_id;
        if (empty($pixel)) return false;

        return apply_filters('nsa_wp_fbp_doAddPixel', true);
    }


    public function insert_Main_Pixel() {
        //Base Pixel from Plugin Settings
        if($this->doAddPixel()) {
            $pixel = $this->WPFacebookPixel->facebook_pixel_id;
            if (empty($pixel)) return; //We got nothing Jim
            $this->PixelID = $pixel;

            $pixel_Code = "
                <!-- remarketable Code - Main -->
                <script>
                !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
                n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
                t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
                document,'script','//connect.facebook.net/en_US/fbevents.js');

                fbq('init', '{$pixel}');
                
                console.log('remarketable > Sending PageView event to Facebook');
                fbq('track', \"PageView\");
                var fbqEvents = new Array();
                </script>
                <noscript><img height=\"1\" width=\"1\" style=\"display:none\"
                src=\"https://www.facebook.com/tr?id={$pixel}&ev=PageView&noscript=1\"
                /></noscript>
                <!-- End remarketable Code - Main -->";

            echo $pixel_Code;
        }
    }


    /**
     * Applies filter 'nsa_wpfbp_events' to get all events
     * 
     * Filter result expects array with the following indexes
     * - event: Name of event.
     * - data: Data object.
     * - fbqTrigger: Javascript to execute call to sendFBQ() function.
     * - preventDefault: Bool - Should prevent default action.
     */
    public function getEvents() {
        echo "<!-- remarketable Code - Page Events -->";
        if($this->doAddPixel()) {
            $events = apply_filters('nsa_wpfbp_events', array());
			
			nsau_Write_to_Log($this->WPFacebookPixel->plugin_id . " events", $events);
			
			//Merge event data for all events with the same event and fbqTrigger.
			$mergedEvents = [];
			foreach($events as $eventKey => $event) {
				$merged = false;
				foreach($mergedEvents as $mergedEventKey => $mergedEvent) {
					if($event['event'] === $mergedEvent['event'] && $event['fbqTrigger'] === $mergedEvent['fbqTrigger']) {
						//array_push($mergedEvents[$mergedEventKey]['data'], $event['data']);
						$mergedEvents[$mergedEventKey]['data'] = array_merge_recursive($mergedEvents[$mergedEventKey]['data'], $event['data']);
						$merged = true;
					}
				}
				if(!$merged) $mergedEvents[] = $event;
			}
			nsau_Write_to_Log($this->WPFacebookPixel->plugin_id . " mergedEvents", $mergedEvents);




			
            foreach($mergedEvents as $event) {
                $event = apply_filters('nsa_wpfbp_event_'.$event['event'], $event);
                $this->generate_Page_Event($event['event'], $event['data'], $event['preventDefault'], $event['fbqTrigger']);
            }
        }
        echo "<!-- END remarketable Code - Page Events -->";
    }

    
    
    
    

    public function get_Page_Events($events) {
        $prefix = wp_facebook_pixel::PLUGIN_ID.'_metabox_';

        //Get Post Specific Event
        $page_event = get_post_meta( get_the_ID(), $prefix.'event', true );
        if (empty($page_event)) return $events; //We got nothing Jim


        //Place in loop to support multiple events on single page
        $event_data = get_post_meta( get_the_ID(), $prefix.'event_values', true );
        $events[] = array(
            'event' => $page_event,
            'data' => $event_data,
            'preventDefault' => false,
            'fbqTrigger' => 'sendFBQ(null, event);',
        );
        //End Loop
        return $events;
        
    }
    
    
    
    
  
    public function get_WooCommerce_Events($events) {
        
        if(class_exists('WooCommerce')) {
            global $WPFacebookPixel;
            $prefix = wp_facebook_pixel::PLUGIN_ID.'_metabox_';
            global $post;
            $product = wc_get_product();//new WC_Product($post->ID);

            //Product Page: View Content & Add To Cart
            if (is_product() && isset($product)) {
                

                $id = $WPFacebookPixel->Product_Id == 'data-product_id' ? $product->get_id() : $product->get_sku();
                

                $event_data = array(
                    array('key' => 'content_type', 'value' => 'product'),
                    array('key' => 'content_ids', 'value' => $id),
                    array('key' => 'currency', 'value' => get_woocommerce_currency()),
                );

                
                if($this->WPFacebookPixel->product_send_ViewContent) {
                    if($this->WPFacebookPixel->product_ViewContent_Value) {
                        $value = apply_filters('nsa_wpfbp_wcevent_value', $product->get_price());
                        $event_data[] = array('key' => 'value', 'value' => $value);
                    }

                    $events[] = array(
                        'event' => 'ViewContent',
                        'data' => $event_data,
                        'fbqTrigger' => 'sendFBQ(null, event);',
                        'preventDefault' => false,
                    );
                }

                if($this->WPFacebookPixel->product_send_AddToCart) {
                    if($this->WPFacebookPixel->product_AddToCart_Value) {
                        $value = apply_filters('nsa_wpfbp_wcevent_value', $product->get_price());
                        $event_data[] = array('key' => 'value', 'value' => $value);
                    }

                    $events[] = array(
                        'event' => 'AddToCart',
                        'data' => $event_data,
                        'fbqTrigger' => 'jQuery("[class*=add_to_cart_button]").one("click", function (e) { sendFBQ(e, event); });',
                        'preventDefault' => true,
                    );
                }

            }


            //Main Shop: Add To Cart
            if (is_shop() && $this->WPFacebookPixel->shop_send_AddToCart) {
                $event_data = array(
                    'content_type' => array('key' => 'content_type', 'value' => 'product'),
                    'content_ids' => array('key' => 'content_ids', 'value' => '~content_ids~'),
                    'currency' => array('key' => 'currency', 'value' => get_woocommerce_currency()),
                );
                if($this->WPFacebookPixel->shop_AddToCart_Value)
                    $event_data['value'] = array('key' => 'value', 'value' => '~value~');

                $events[] = array(
                    'event' => 'AddToCart',
                    'data' => $event_data,
                    'fbqTrigger' => 'jQuery("[class*=add_to_cart_button]").one("click", function (e) { sendFBQ(e, event); });',
                    'preventDefault' => false,
                );
            }


            
            //Checking out: Initiate Purchase
            //is_checkout()


            //Order Complete: Purchase
            if(is_wc_endpoint_url('order-received') && $this->WPFacebookPixel->order_recieved_send_purchase) {
               
                $content_ids = array();
                $totalValue = 0;
                
                global $wp;
                $order_id = $wp->query_vars['order-received'];

                $order = new WC_Order( apply_filters( 'woocommerce_thankyou_order_id', absint( $order_id ) ) );
                $products = $order->get_items();
                $count = 0;

                $productFactory = new WC_Product_Factory();

                foreach ($products as $product)
                {
                    //Get Product IDs
                    switch ($this->WPFacebookPixel->Product_Id)
                    {
                        case 'data-product_id':
                            $content_ids[] = $product['product_id'];
                            break;

                        case 'data-product_sku':
                            $_product = $productFactory->get_product($product['product_id']);
                            $content_ids[] = $_product->get_sku();
                            break;
                    }


                    //TODO: Do not use Cart's total in loop
                    // $totalValue += $WPFacebookPixel->Product_Value == 'current_price' ? 
                        // strip_tags(str_replace(get_woocommerce_currency_symbol(), '', $product['line_subtotal'])) : 
                        // get_post_meta( $product['product_id'], $prefix.'product_value', true ) * $product['qty'];


                    $count += empty( $product['qty'] ) ? 1 : $product['qty'];

                }
				
				$totalValue = $order->get_total();
                
                $event_data = array(
                    array('key' => 'content_type', 'value' => 'product'),
                    array('key' => 'content_ids', 'value' => $content_ids),
                    array('key' => 'value', 'value' => $totalValue),
                    array('key' => 'currency', 'value' => get_woocommerce_currency()),
                    array('key' => 'num_items', 'value' => $count )
                );
                $events[] = array(
                    'event' => 'Purchase',
                    'data' => $event_data,
                    'fbqTrigger' => 'sendFBQ(null, event);',
                    'preventDefault' => false,
                );

            }

        }

        return $events;
    }


    

    


    private function generate_Page_Event($page_event, $event_data, $preventDefault = false, $fbqTrigger = null) {
		nsau_Write_to_Log($this->WPFacebookPixel->plugin_id, "Called");
        $event_json = array();
        $event_json_update = '';
        $event_fbq = '';
        $event_image = '';
        $GetImage = true;
        $image_data = '';

        //Post specific event key/value pairs
        if(!empty($event_data)) {
            $event_json = array();
            /*  REG EX TESTER
             *  https://regex101.com/r/wB4bK6/1
             * */
            foreach( $event_data as $event_keyvalue_pair ) {
                $key = $event_keyvalue_pair['key'];
                $value = $event_keyvalue_pair['value'];

                //Replace Query string values
                if(!is_array($value)) {
                    if (preg_match('/\\?.*\\?/', $value)) {
                        $event_json_update .= 'data.'.$key.' = getQueryStringValue("'.str_replace('?', '', $value).'");';
                        $value = '';
                        $GetImage = false;
                    }

                    //Replace Javascript Variable values
                    if (preg_match('/%.*%/', $value)) {
                        $event_json_update .= 'data.'.$key.' = typeof('.str_replace('%', '', $value).') !== "undefined" ? '.str_replace('%', '', $value).' : null;';
                        $value = '';
                        $GetImage = false;
                    }


                    //Update with triggering objects Attribute value
                    if (preg_match("/[[][^\"'].*[]][^\"']/", $value)) {
                        $event_json_update .= 'data.'.$key.' = jQuery(this).attr("'.str_replace('[', '', str_replace(']', '', $value)).'");';
                        $value = '';
                        $GetImage = false;
                    }


                    //Update WooCommerce Values
                    if (preg_match('/[~].*[~]/', $value)) {
                        $value = str_replace('~', '', str_replace('~', '', $value));

                        switch ($value) {
                            case 'content_ids':
                                $event_json_update .= '
                                data.'.$key.' = jQuery(e.currentTarget).attr("'.$this->WPFacebookPixel->Product_Id.'");';
                                break;

                            case 'value':
                                $update = apply_filters('nsa_wpfbp_woocom_product_value', 'data.'.$key.' = jQuery(e.currentTarget).parent().find("span.price").text().replace("'.get_woocommerce_currency().'", "")');
                                $event_json_update .= $update;
                                break;
                        }

                        $value = '';
                        $GetImage = false;
                    }
                }

                $event_json[$key] = $value;
                if(is_array($value)) $value = implode(",", $value);
                $image_data .= '&amp;cd['.$key.']='.$value;
            }
        }
            
        $event_fbq = "fbq('track', '".$page_event."'";
        if(empty($event_json)) { 
            $event_fbq .= ");";

        } else {
            $event_fbq .= ", eventJSON);";

        }
        
        if($preventDefault) {
            $event_fbq .= '
                e.isDefaultPrevented = function(){ return false; };
                jQuery(e.target).trigger(e);';
        }

        if($GetImage) { 
            /* Generate img element. Example:
             *
             * <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=FB_PIXEL_ID&amp;ev=Purchase
             *      &amp;cd[currency]=EUR
             *      &amp;cd[value]=15.20" />
             */
            $event_image = '<img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id='.$this->PixelID.'&amp;ev='.$page_event.$image_data.'" />';
        }

        $Facebookevent = new FacebookEvent();
        $Facebookevent->event = $page_event;
        $Facebookevent->data = json_encode($event_json);
        $Facebookevent->updateScript = $event_json_update;
        $Facebookevent->fbqTrigger = $fbqTrigger;
        $Facebookevent->preventDefault = $preventDefault;

        echo "<script>fbqEvents.push({$Facebookevent});</script>";
        echo "<noscript>$event_image</noscript>";
        
    }




}
$nsaFBPixel = new NSAFaceBookPixel();


class FacebookEvent {
    public $event;
    public $data;
    public $updateScript;
    public $preventDefault;
    public $fbqTrigger;

    public function __toString() {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }
    public function toArray() {
        return $this->processArray(get_object_vars($this));
    }
    private function processArray($array) {
        foreach($array as $key => $value) {
            if (is_object($value)) {
                $array[$key] = $value->toArray();
            }
            if (is_array($value)) {
                $array[$key] = $this->processArray($value);
            }
        }
        // If the property isn't an object or array, leave it untouched
        return $array;
    }
}