<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Traits;

use Exception;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event\On;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event\Save_Post;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event\Save_Wc_Order_Post;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event\Wc_Add_To_Cart;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event\Wc_Before_Checkout_Form;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event\Wc_Before_Settings;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event\Wc_Before_Shop_Loop_Item;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event\Wc_Before_Single_Product;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event\Wc_Checkout_Page;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event\Wc_Checkout_Update_Order_Meta;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event\Wc_Filter_Price_Html;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event\Wc_Loaded;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event\Wc_New_Order;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event\Wc_Order_Status_Changed;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event\Wc_Product_Options_General_Product_Data;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event\Wc_Product_Options_Pricing;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event\Wc_Product_Update;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event\Wc_Remove_Cart_Item;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event\Wc_Variation_Create;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event\Wc_Variation_Update;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event\Wp;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event\Wp_Admin_Footer;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event\Wp_Admin_Init;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event\Wp_Ajax;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event\Wp_Cron_Every_N_Minutes;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event\Wp_Footer;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event\Wp_Init;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Event_Chain\Event_Chain;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Field_Ajax_Interface;
trait Events
{
    /**
     * @throws Exception
     */
    public function on_woocommerce_checkout_update_order_meta() : Event_Chain
    {
        $event = new Wc_Checkout_Update_Order_Meta();
        $this->get_event_chain()->add_event($event);
        return $this->get_event_chain();
    }
    protected abstract function get_event_chain() : Event_Chain;
    /**
     * @throws Exception
     */
    public function on_wp_init() : Event_Chain
    {
        $event = new Wp_Init();
        $this->get_event_chain()->add_event($event);
        return $this->get_event_chain();
    }
    /**
     * @throws Exception
     */
    public function on_wp_admin_init() : Event_Chain
    {
        $event = new Wp_Admin_Init();
        $this->get_event_chain()->add_event($event);
        return $this->get_event_chain();
    }
    /**
     * @throws Exception
     */
    public function on_wp() : Event_Chain
    {
        $event = new Wp();
        $this->get_event_chain()->add_event($event);
        return $this->get_event_chain();
    }
    /**
     * @throws Exception
     */
    public function on_save_post() : Event_Chain
    {
        $event = new Save_Post();
        $this->get_event_chain()->add_event($event);
        return $this->get_event_chain();
    }
    /**
     * @throws Exception
     */
    public function on_save_wc_order_post() : Event_Chain
    {
        $event = new Save_Wc_Order_Post();
        $this->get_event_chain()->add_event($event);
        return $this->get_event_chain();
    }
    /**
     * @throws Exception
     */
    public function on_wc_before_shop_loop_item() : Event_Chain
    {
        $event = new Wc_Before_Shop_Loop_Item();
        $this->get_event_chain()->add_event($event);
        return $this->get_event_chain();
    }
    /**
     * @throws Exception
     */
    public function on_wc_before_single_product() : Event_Chain
    {
        $event = new Wc_Before_Single_Product();
        $this->get_event_chain()->add_event($event);
        return $this->get_event_chain();
    }
    /**
     * @throws Exception
     */
    public function on_wc_loaded() : Event_Chain
    {
        $event = new Wc_Loaded();
        $this->get_event_chain()->add_event($event);
        return $this->get_event_chain();
    }
    /**
     * @throws Exception
     */
    public function on_wc_add_to_cart() : Event_Chain
    {
        $event = new Wc_Add_To_Cart();
        $this->get_event_chain()->add_event($event);
        return $this->get_event_chain();
    }
    public function on_wc_remove_cart_item() : Event_Chain
    {
        $event = new Wc_Remove_Cart_Item();
        $this->get_event_chain()->add_event($event);
        return $this->get_event_chain();
    }
    public function on_wc_before_checkout_form() : Event_Chain
    {
        $event = new Wc_Before_Checkout_Form();
        $this->get_event_chain()->add_event($event);
        return $this->get_event_chain();
    }
    public function on_wc_order_status_changed() : Event_Chain
    {
        $event = new Wc_Order_Status_Changed();
        $this->get_event_chain()->add_event($event);
        return $this->get_event_chain();
    }
    public function on_wp_footer() : Event_Chain
    {
        $event = new Wp_Footer();
        $this->get_event_chain()->add_event($event);
        return $this->get_event_chain();
    }
    public function on_wc_checkout_page() : Event_Chain
    {
        $event = new Wc_Checkout_Page();
        $this->get_event_chain()->add_event($event);
        return $this->get_event_chain();
    }
    public function on_wp_admin_footer() : Event_Chain
    {
        $event = new Wp_Admin_Footer();
        $this->get_event_chain()->add_event($event);
        return $this->get_event_chain();
    }
    public function on_wc_new_order() : Event_Chain
    {
        $event = new Wc_New_Order();
        $this->get_event_chain()->add_event($event);
        return $this->get_event_chain();
    }
    public function on_wp_cron_every_n_minutes(int $interval, string $schedule_id) : Event_Chain
    {
        $event = new Wp_Cron_Every_N_Minutes($interval, $schedule_id);
        $this->get_event_chain()->add_event($event);
        return $this->get_event_chain();
    }
    public function on_wc_before_settings(string $section_id) : Event_Chain
    {
        $event = new Wc_Before_Settings($section_id);
        $this->get_event_chain()->add_event($event);
        return $this->get_event_chain();
    }
    public function on_wc_product_update() : Event_Chain
    {
        $event = new Wc_Product_Update();
        $this->get_event_chain()->add_event($event);
        return $this->get_event_chain();
    }
    public function on_wc_variation_update() : Event_Chain
    {
        $event = new Wc_Variation_Update();
        $this->get_event_chain()->add_event($event);
        return $this->get_event_chain();
    }
    public function on_wc_variation_create() : Event_Chain
    {
        $event = new Wc_Variation_Create();
        $this->get_event_chain()->add_event($event);
        return $this->get_event_chain();
    }
    public function on_wc_product_options_general_product_data() : Event_Chain
    {
        $event = new Wc_Product_Options_General_Product_Data();
        $this->get_event_chain()->add_event($event);
        return $this->get_event_chain();
    }
    public function on_wc_product_options_pricing() : Event_Chain
    {
        $event = new Wc_Product_Options_Pricing();
        $this->get_event_chain()->add_event($event);
        return $this->get_event_chain();
    }
    /**
     * @throws Exception
     */
    public function on_wp_ajax(Field_Ajax_Interface $field_ajax) : Event_Chain
    {
        $event = new Wp_Ajax($field_ajax);
        $this->get_event_chain()->add_event($event);
        return $this->get_event_chain();
    }
    /**
     * @throws Exception
     */
    public function on(string $hook_name, $priority = 10) : Event_Chain
    {
        $event = new On($hook_name, $priority);
        $this->get_event_chain()->add_event($event);
        return $this->get_event_chain();
    }
    /**
     * @throws Exception
     */
    public function filter_wc_price_html() : Event_Chain
    {
        $event = new Wc_Filter_Price_Html();
        $this->get_event_chain()->add_event($event);
        return $this->get_event_chain();
    }
}
