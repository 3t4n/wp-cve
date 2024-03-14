<?php
 
class pisol_cefw_weight_based_extra_charges_setting{

    function __construct(){
        $this->slug = 'weight';

        add_action('pi_cefw_additional_charges_tab', array($this, 'addTab'),10,1);
        add_action('pi_cefw_additional_charges_tab_content', array($this, 'addTabContent'),10,1);

        add_filter('pi_cefw_extra_charge_form_data',array($this, 'formData'), 20, 1);
        add_filter('pi_cefw_extra_charge_clone_form_data',array($this, 'cloneData'), 10, 2);
        add_action('pisol_cefw_save_extra_charge', array($this, 'saveForm'),20,1);

        add_filter('pi_cefw_add_additional_charges', array($this, 'addExtraCharge'),10, 3);
    }

    function addTab($data){
        pisol_cefw_additional_charges_form::tabName('Based on Cart Weight', $this->slug);
    }

    function addTabContent($data){
       echo '<div class="p-2 border additional-charges-tab-content" id="add-charges-tab-content-'.$this->slug.'">';
       include 'template/weight-based-charges.php';
       echo '</div>';
    }

    function rowTemplate($data){
        if(empty($data['pi_cart_weight_charges'] ) || !is_array($data['pi_cart_weight_charges'] )) return;
        $rows = array_values($data['pi_cart_weight_charges']);
        foreach($rows as $count => $row){
        ?>
        <tr>
        <td>Cart weight</td>
        <td class="pi-min-col"><input type="number" required name="pi_cart_weight_charges[<?php echo $count; ?>][min]" value="<?php echo self::value($row,'min'); ?>" min="0" class="form-control" step="0.0001"></td>
        <td class="pi-max-col"><input type="number" name="pi_cart_weight_charges[<?php echo $count; ?>][max]" value="<?php echo self::value($row,'max'); ?>"  min="0" class="form-control" step="0.0001"></td>
        <td class="pi-fee-col"><input type="text" required name="pi_cart_weight_charges[<?php echo $count; ?>][charge]" value="<?php echo self::value($row,'charge'); ?>" class="form-control"></td>
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
            $data['pi_enable_additional_charges_cart_weight'] = get_post_meta( $data['post_id'], 'pi_enable_additional_charges_cart_weight', true );
            $data['pi_enable_additional_charges_cart_weight']       = isset($data['pi_enable_additional_charges_cart_weight']) && 'on' === $data['pi_enable_additional_charges_cart_weight'] ? 'checked' : '';
            $data['pi_cart_weight_charges'] = get_post_meta($data['post_id'], 'pi_cart_weight_charges', true);

            $data['pi_cefw_cart_weight_sum_of_charges'] = get_post_meta($data['post_id'], 'pi_cefw_cart_weight_sum_of_charges', true);
        }else{
            $data['pi_enable_additional_charges_cart_weight']               = '';
            $data['pi_cart_weight_charges'] = '';
            $data['pi_cefw_cart_weight_sum_of_charges'] = 'all';
        }
        return $data;
    }

    function cloneData($data, $post_id){
        
            $data['pi_enable_additional_charges_cart_weight'] = get_post_meta( $post_id, 'pi_enable_additional_charges_cart_weight', true );
            
            $data['pi_cart_weight_charges'] = get_post_meta($post_id, 'pi_cart_weight_charges', true);

            $data['pi_cefw_cart_weight_sum_of_charges'] = get_post_meta($post_id, 'pi_cefw_cart_weight_sum_of_charges', true);
        
        return $data;
    }

    function saveForm($post_id){
        if ( isset( $_POST['pi_enable_additional_charges_cart_weight'] ) ) {
            update_post_meta( $post_id, 'pi_enable_additional_charges_cart_weight', "on" );
        } else {
            update_post_meta( $post_id, 'pi_enable_additional_charges_cart_weight', "off");
        }

        if ( isset( $_POST['pi_cart_weight_charges'] ) && is_array($_POST['pi_cart_weight_charges']) ) {
            update_post_meta( $post_id, 'pi_cart_weight_charges', $_POST['pi_cart_weight_charges'] );
        } else {
            update_post_meta( $post_id, 'pi_cart_weight_charges', array());
        }

        if ( isset( $_POST['pi_cefw_cart_weight_sum_of_charges'] )) {
            update_post_meta( $post_id, 'pi_cefw_cart_weight_sum_of_charges', $_POST['pi_cefw_cart_weight_sum_of_charges'] );
        } else {
            update_post_meta( $post_id, 'pi_cefw_cart_weight_sum_of_charges', 'all');
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

        $cart_weight = self::getCartWeight($package);

        $charge = self::getCharge($post_id, $get_charges, $cart_weight);

        return $charge;
    }

    static function getCharge($post_id, $get_charges, $cart_weight){
        $matched_charges = self::getMatchedCharges($get_charges, $cart_weight);

        if(empty($matched_charges)) return 0;
        
        $summing_method =  get_post_meta($post_id, 'pi_cefw_cart_weight_sum_of_charges', true);

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

    static function getMatchedCharges($get_charges, $cart_weight){
        $matched_charges = array();
        foreach($get_charges as $charge){
            if(!empty($charge['min']) && !empty($charge['max']) && ($cart_weight >= $charge['min'] && $cart_weight <= $charge['max'])){
                $matched_charges[] = self::evaluate_cost(  $charge['charge'], $cart_weight);
            }

            if(!empty($charge['min']) && empty($charge['max']) && ($cart_weight >= $charge['min'])){
                $matched_charges[] =  self::evaluate_cost(  $charge['charge'], $cart_weight);
            }
        }
        return $matched_charges;
    }

    static function evaluate_cost( $charge, $weight = 0 ) {
        

        include_once WC()->plugin_path() . '/includes/libraries/class-wc-eval-math.php';

        // Allow 3rd parties to process shipping cost arguments.
       
        $locale         = localeconv();
        $decimals       = array( wc_get_price_decimal_separator(), $locale['decimal_point'], $locale['mon_decimal_point'], ',' );
        

        $sum = do_shortcode(
            str_replace(
                array(
                    '[weight]'
                ),
                array(
                    $weight,
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

    static function getCartWeight(){
        if(!function_exists('WC') || !isset(WC()->cart)) return 0;

        $weight = WC()->cart->get_cart_contents_weight();
        return $weight;
    }

    static function groupEnabled($post_id){
        $group_enabled = get_post_meta( $post_id, 'pi_enable_additional_charges_cart_weight', true );
        return $group_enabled == 'on' ? true : false;
    }

    function getChargesRows($post_id){
        $ranges = get_post_meta($post_id, 'pi_cart_weight_charges',true);
        if(empty($ranges) || !is_array($ranges)) return array();

        return $ranges;
    }

}
new pisol_cefw_weight_based_extra_charges_setting();
