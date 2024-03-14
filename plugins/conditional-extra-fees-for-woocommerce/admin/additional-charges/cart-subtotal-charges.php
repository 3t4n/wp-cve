<?php
 
class pisol_cefw_cart_subtotal_extra_charges_setting{

    function __construct(){
        $this->slug = 'subtotal';

        add_action('pi_cefw_additional_charges_tab', array($this, 'addTab'),10,1);
        add_action('pi_cefw_additional_charges_tab_content', array($this, 'addTabContent'),10,1);

        add_filter('pi_cefw_extra_charge_form_data',array($this, 'formData'), 20, 1);

        add_filter('pi_cefw_extra_charge_clone_form_data',array($this, 'cloneData'), 10, 2);

        add_action('pisol_cefw_save_extra_charge', array($this, 'saveForm'),20,1);

        add_filter('pi_cefw_add_additional_charges', array($this, 'addExtraCharge'),10, 3);
    }

    function addTab($data){
        pisol_cefw_additional_charges_form::tabName('Based on Cart Subtotal', $this->slug);
    }

    function addTabContent($data){
       echo '<div class="p-2 border additional-charges-tab-content" id="add-charges-tab-content-'.$this->slug.'">';
       include 'template/subtotal-based-charges.php';
       echo '</div>';
    }

    function rowTemplate($data){
        if(empty($data['pi_cart_subtotal_charges'] ) || !is_array($data['pi_cart_subtotal_charges'] )) return;
        $rows = array_values($data['pi_cart_subtotal_charges']);
        foreach($rows as $count => $row){
        ?>
        <tr>
        <td>Cart subtotal</td>
        <td class="pi-min-col"><input type="number" required name="pi_cart_subtotal_charges[<?php echo $count; ?>][min]" value="<?php echo self::value($row,'min'); ?>"  min="1" class="form-control"></td>
        <td class="pi-max-col"><input type="number" name="pi_cart_subtotal_charges[<?php echo $count; ?>][max]" value="<?php echo self::value($row,'max'); ?>"  min="1" class="form-control"></td>
        <td class="pi-fee-col"><input type="text" required name="pi_cart_subtotal_charges[<?php echo $count; ?>][charge]" value="<?php echo self::value($row,'charge'); ?>" class="form-control"></td>
        <td><button class="delete-additional-charges btn btn-danger btn-sm"><span class="dashicons dashicons-trash"></span></button></td>
        </tr>
        <?php
        }
    }

    static function value($row, $name, $default = ''){
        return !empty($row[$name]) ? $row[$name] : $default ;
    }

    function formData($data){
        $action_value = filter_input( INPUT_GET, 'action' );
        $id_value     = filter_input( INPUT_GET, 'id' );
        if ( isset( $action_value ) && 'edit' === $action_value ) {
            $data['pi_enable_additional_charges_cart_subtotal'] = get_post_meta( $data['post_id'], 'pi_enable_additional_charges_cart_subtotal', true );
            $data['pi_enable_additional_charges_cart_subtotal']       = isset($data['pi_enable_additional_charges_cart_subtotal']) && 'on' === $data['pi_enable_additional_charges_cart_subtotal'] ? 'checked' : '';
            $data['pi_cart_subtotal_charges'] = get_post_meta($data['post_id'], 'pi_cart_subtotal_charges', true);

            $data['pi_cefw_cart_subtotal_sum_of_charges'] = get_post_meta($data['post_id'], 'pi_cefw_cart_subtotal_sum_of_charges', true);
        }else{
            $data['pi_enable_additional_charges_cart_subtotal']               = '';
            $data['pi_cart_subtotal_charges'] = '';

            $data['pi_cefw_cart_subtotal_sum_of_charges'] = 'all';
        }
        return $data;
    }

    function cloneData($data, $post_id){
       
            $data['pi_enable_additional_charges_cart_subtotal'] = get_post_meta( $post_id, 'pi_enable_additional_charges_cart_subtotal', true );
            
            $data['pi_cart_subtotal_charges'] = get_post_meta($post_id, 'pi_cart_subtotal_charges', true);

            $data['pi_cefw_cart_subtotal_sum_of_charges'] = get_post_meta($post_id, 'pi_cefw_cart_subtotal_sum_of_charges', true);
        
        return $data;
    }

    function saveForm($post_id){
        if ( isset( $_POST['pi_enable_additional_charges_cart_subtotal'] ) ) {
            update_post_meta( $post_id, 'pi_enable_additional_charges_cart_subtotal', "on" );
        } else {
            update_post_meta( $post_id, 'pi_enable_additional_charges_cart_subtotal', "off");
        }

        if ( isset( $_POST['pi_cart_subtotal_charges'] ) && is_array($_POST['pi_cart_subtotal_charges']) ) {
            update_post_meta( $post_id, 'pi_cart_subtotal_charges', $_POST['pi_cart_subtotal_charges'] );
        } else {
            update_post_meta( $post_id, 'pi_cart_subtotal_charges', array());
        }

        if ( isset( $_POST['pi_cefw_cart_subtotal_sum_of_charges'] )) {
            update_post_meta( $post_id, 'pi_cefw_cart_subtotal_sum_of_charges', $_POST['pi_cefw_cart_subtotal_sum_of_charges'] );
        } else {
            update_post_meta( $post_id, 'pi_cefw_cart_subtotal_sum_of_charges', 'all');
        }
    }

    function addExtraCharge($cost, $method_id, $package){
        $extra_charge = $this->extraCharges($method_id, $package);
        return $cost + $extra_charge;
    }

    function extraCharges($post_id, $package){
        $additional_charges_enabled = pisol_cefw_additional_charges_form::additionalChargesEnabled($post_id);

        if(!$additional_charges_enabled) return 0;

        $group_enabled = self::groupEnabled($post_id);

        if(!$group_enabled) return 0;

        $get_charges = self::getChargesRows($post_id);

        if(empty($get_charges)) return 0;

        $cart_subtotal = self::getCartSubtotal($post_id);

        $charge = self::getCharge($post_id, $get_charges, $cart_subtotal);

        return $charge;
    }

    static function groupEnabled($post_id){
        $group_enabled = get_post_meta( $post_id, 'pi_enable_additional_charges_cart_subtotal', true );
        return $group_enabled == 'on' ? true : false;
    }

    function getChargesRows($post_id){
        $ranges = get_post_meta($post_id, 'pi_cart_subtotal_charges',true);
        if(empty($ranges) || !is_array($ranges)) return array();

        return $ranges;
    }

    static function getCartSubtotal( $post_id ){
        if(!function_exists('WC') || !isset(WC()->cart)) return 0;

        if(apply_filters('pisol_addsw_additional_subtotal_after_discount', false, $post_id)){
            return self::getCartSubtotalAfterDiscount();
        }
        $subtotal = WC()->cart->get_displayed_subtotal();
        return $subtotal;
    }

    static function getCartSubtotalAfterDiscount(){
        if(!function_exists('WC') || !isset(WC()->cart)) return 0;

        $discount = WC()->cart->get_discount_total() ;
        $subtotal_before_discount = (float)WC()->cart->get_displayed_subtotal();
        if ( WC()->cart->display_prices_including_tax() ) {
            $discount = $discount +  WC()->cart->get_discount_tax();
        }
        $subtotal_after_discount = $subtotal_before_discount - $discount;
        return $subtotal_after_discount;
    }

    static function getCharge($post_id, $get_charges, $cart_subtotal){
        $matched_charges = self::getMatchedCharges($get_charges, $cart_subtotal);

        if(empty($matched_charges)) return 0;
        
        $summing_method =  get_post_meta($post_id, 'pi_cefw_cart_subtotal_sum_of_charges', true);

        switch($summing_method){
            case 'all':
                return array_sum($matched_charges);
            break;

            case 'smallest':
                return min($matched_charges);
            break;

            case 'largest':
                return max($matched_charges);
            break;
        }
    }

    static function getMatchedCharges($get_charges, $cart_subtotal){
        $matched_charges = array();
        foreach($get_charges as $charge){
            if(!empty($charge['min']) && !empty($charge['max']) && ($cart_subtotal >= $charge['min'] && $cart_subtotal <= $charge['max'])){
                $matched_charges[] = self::evaluate_cost(  $charge['charge'], $cart_subtotal);
            }

            if(!empty($charge['min']) && empty($charge['max']) && ($cart_subtotal >= $charge['min'])){
                $matched_charges[] =  self::evaluate_cost(  $charge['charge'], $cart_subtotal);
            }
        }
        return $matched_charges;
    }

    static function evaluate_cost( $charge, $subtotal = 0 ) {
        

        include_once WC()->plugin_path() . '/includes/libraries/class-wc-eval-math.php';

        // Allow 3rd parties to process shipping cost arguments.
       
        $locale         = localeconv();
        $decimals       = array( wc_get_price_decimal_separator(), $locale['decimal_point'], $locale['mon_decimal_point'], ',' );
        

        $sum = do_shortcode(
            str_replace(
                array(
                    '[subtotal]'
                ),
                array(
                    $subtotal,
                ),
                $charge
            )
        );


        // Remove whitespace from string.
        $sum = preg_replace( '/\s+/', '', $sum );

        // Remove locale from string.
        $sum = str_replace( $decimals, '.', $sum );

        // Trim invalid start/end characters.
        $sum = rtrim( ltrim( $sum, "\t\n\r\0\x0B+*/" ), "\t\n\r\0\x0B+-*/" );

        // Do the math.
        return $sum ? WC_Eval_Math::evaluate( $sum ) : 0;
    }

}
new pisol_cefw_cart_subtotal_extra_charges_setting();
