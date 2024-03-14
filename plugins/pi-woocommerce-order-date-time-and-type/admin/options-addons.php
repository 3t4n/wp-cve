<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
class pisol_dtt_addons{

    private $settings = array();

    private $active_tab;

    private $this_tab = 'addons';

    private $tab_name = 'Addon Plugins';

    private $setting_key = 'pisol_dtt_addons';

   

    function __construct(){

        $this->active_tab = (isset($_GET['tab'])) ? sanitize_text_field($_GET['tab']) : 'default';
        

        if($this->this_tab == $this->active_tab){
            add_action('pisol_dtt_tab_content', array($this,'tab_content'));
        }

        add_action('pisol_dtt_tab', array($this,'tab'),10);
        
       

        
    }


    function tab(){
        ?>
        <a class=" pi-side-menu <?php echo ($this->active_tab == $this->this_tab ? 'bg-warning' : 'bg-warning'); ?>" href="<?php echo admin_url( 'admin.php?page='.esc_attr($_GET['page']).'&tab='.esc_attr($this->this_tab) ); ?>">
        <span class="dashicons dashicons-admin-plugins"></span>  <?php _e( $this->tab_name, 'pisol-dtt' ); ?> 
        </a>
        <a class=" pi-side-menu bg-warning" target="_blank" href="https://www.piwebsolution.com/documentation-for-order-delivery-pickup-date-time-and-location-for-woocommerce/">
        <span class="dashicons dashicons-media-document"></span> Documentation 
        </a>
        <?php
    }

    function tab_content(){
       ?>
    <div class="alert alert-warning mt-3">This addon plugins only works along with the PRO Version of the Order date, time pickup location plugin</div>

    <div class="border p-2 my-3">
            <div class="row align-items-center">
                    <div class="col-12 col-md-5">
                        <iframe style="width:100%; height:230px;" src="https://www.youtube.com/embed/890xVsrgQVU" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <div class="col-12 col-md-7">
                        <h3>Preparation time master (Addon plugin)</h3>
                        <p>This allows you to change various setting of the plugin on product level</p>
                        <ul>
                            <li>Set different preparation time for each product</li>
                            <li>Make product only available for pickup or delivery or both</li>
                            <li>Set product as only available at specific pickup location only </li>
                            <li>Make product available on specific day or date</li>
                        </ul>
                        <div class="py-2 text-right">
                            <a class="btn btn-md btn-primary" href="https://www.piwebsolution.com/cart/?add-to-cart=5844&variation_id=5846" target="_blank">Buy Now</a> 
                        </div>
                    </div>
            </div>
    </div>
    
    <div class="border p-2 my-3">
            <div class="row align-items-center">
                    <div class="col-12 col-md-5">
                        <iframe style="width:100%; height:230px;" src="https://www.youtube.com/embed/sPhxFkF_jsE" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <div class="col-12 col-md-7">
                        <h3>Special Working date & Special Timing Pro (Addon for pro plugin)</h3>
                        <ul>
                        <li>You can set special working dates that are outside your preorder day range</li>
 	<li>You can set special timing for this special date as well</li>
     <li>You can use this plugin to change time slot of some normal working date as well </li>
                        </ul>
                        <div class="py-2 text-right">
                            <a class="btn btn-md btn-primary" href="https://www.piwebsolution.com/cart/?add-to-cart=8739&variation_id=8745" target="_blank">Buy Now</a> 
                        </div>
                    </div>
            </div>
        </div>

      
      <div class="border p-2 my-3">
            <div class="row align-items-center">
                    <div class="col-12 col-md-5">
                        <iframe style="width:100%; height:230px;" src="https://www.youtube.com/embed/4lJdvR_s6gc" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <div class="col-12 col-md-7">
                        <h3>Different date time for Different pickup stores and Different delivery areas PRO (Addon for pro plugin)</h3>
                        <ul>
                            <li>Offer different Time for different pickup stores</li>
                            <li>Set different working days for different pickup stores</li>
                            <li>Offer different Time for different delivery zones (e.g: based on delivery area postcode)</li>
                        </ul>
                        <div class="py-2 text-right">
                            <a class="btn btn-md btn-primary" href="https://www.piwebsolution.com/cart/?add-to-cart=6068&variation_id=6069" target="_blank">Buy Now</a> 
                        </div>
                    </div>
            </div>
        </div>

        <div class="border p-2 my-3">
            <div class="row align-items-center">
                    <div class="col-12 col-md-5">
                        <iframe style="width:100%; height:230px;" src="https://www.youtube.com/embed/vk77jLrzp8g" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <div class="col-12 col-md-7">
                        <h3>Minimum Purchase Amount for Delivery order Pro (Addon for pro plugin)</h3>
                        <ul>
 	<li>Set Minimum purchase amount to get the Delivery option</li>
 	<li>Minimum purchase amount to get Pickup option</li>
 	<li>You can set a custom message to inform the user about the minimum purchase amount restriction</li>
 	<li>You can show a message when the user does not qualify for the delivery option, so the customer will know how he can get the delivery option</li>
 	<li>Likewise, if you want to set a minimum purchase about the restriction on your shop, you can do that as well bu setting minimum purchase amount restriction on delivery and pickup orders both</li>
</ul>
                        <div class="py-2 text-right">
                            <a class="btn btn-md btn-primary" href="https://www.piwebsolution.com/cart/?add-to-cart=6090&variation_id=6095" target="_blank">Buy Now</a> 
                        </div>
                    </div>
            </div>
        </div>

       <div class="border p-2 my-3">
            <div class="row align-items-center">
                    <div class="col-12 col-md-5">
                        <iframe style="width:100%; height:230px;" src="https://www.youtube.com/embed/nkVBYRXFStk" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <div class="col-12 col-md-7">
                        <h3>Delivery pickup reminder email WooCommerce PRO (Addon plugin)</h3>
                        <p>This is an addon plugin for order delivery date and time plugin, it automatically sends a reminder email to your customer, admin and staff regarding upcoming delivery or pickup for there order, you can configure in the plugin how much time before a reminder email should be sent.</p>

    <strong>E.g: if the user places a pickup order for 25th April 2020 3:00 PM<br>
    and you have set the reminder time to be 30 min <br>
    then the customer will receive and a reminder email on 25th April 2020 2:30 PM</strong>
                        <div class="py-2 text-right">
                            <a class="btn btn-md btn-primary" href="https://www.piwebsolution.com/cart/?add-to-cart=5195&variation_id=5199" target="_blank">Buy Now</a> <a class="btn btn-md btn-primary" href="https://wordpress.org/plugins/delivery-pickup-reminder-email-woocommerce/" target="_blank">Try Free version</a> 
                        </div>
                    </div>
            </div>
        </div>
            
        <div class="border p-2 my-3">
            <div class="row align-items-center">
                <div class="col-12 col-md-5">
                <iframe style="width:100%; height:230px;" src="https://www.youtube.com/embed/bJw2k4FniOQ" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="col-12 col-md-7">
                    <h3>Delivery date, time preference selection popup (Addon for pro plugin)</h3>
                    <ul>
                    <li>This plugin allows you to get user delivery preference way before they reach the checkout page.</li>
                    <li>There are multiple ways to trigger the preference popup like:
                    <ol>
                    <li>Link based trigger: create a link class name “pisol-date-time-popup” and that link will trigger the preference popup</li>
                    <li>Auto load the preference popup on every page untill user saves his preference</li>
                    </ol>
                    </li>
                    <li>Buyer can change there selected preference again on the checkout page</li>
                    <li>Auto fills the user selected preferences on the checkout page</li>
                    <li>Option to customize all the text of the form in your language, from plugin setting + Loco translator</li>
                    <li>Customize the looks of the preferece popup</li>
                    </ul>
                    <div class="py-2 text-right">
                        <a class="btn btn-md btn-primary" href="https://www.piwebsolution.com/cart/?add-to-cart=4462&variation_id=4467"  target="_blank">Buy Now</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="border p-2 my-3">
        <div class="row align-items-center">
                <div class="col-12 col-md-5">
                <iframe style="width:100%; height:230px;" src="https://www.youtube.com/embed/z0BzI2Mc2cs" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <div class="col-12 col-md-7">
                    <h3>Pro Order Calendar for WooCommerce (Add-on plugin)</h3>
                    <ul>
                        <li>It can show delivery date on the calendar</li>
                        <li>Show pickup dates on the calendar</li>
                        <li>This can be helpful for you to see upcoming delivery and pickup dates</li>
                        <li>Show calendar on the front end using the short-code [order_calendar]</li>
                        <li>Your staff can log in into the front end of the site to see order detail</li>
                        <li>Assign order detail access as per the employee role</li>
                        <li>If an employee is in a production show them product detail of the order</li>
                        <li>If an employee is in delivery then only show him customer detail or even product detail</li>
                    </ul>
                    <div class="py-2 text-right">
                        <a class="btn btn-md btn-primary" href="https://www.piwebsolution.com/cart/?add-to-cart=4052&variation_id=4054" target="_blank">Buy Now</a>
                    </div>
                </div>
            </div>
            </div>

            
       <?php
    }

    
}

new pisol_dtt_addons();