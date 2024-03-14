<?php 
namespace PISOL\CEFW;

class ExtraFees{
    private $fees_id;

    function __construct( $fees_id ){
        $this->fees_id = $fees_id;
    }

    function get_id(){
        return $this->fees_id;
    }

    function get_name(){
        $fees_id = $this->get_id();
        return "pisol-cefw-fees:{$fees_id}";
    }

    function get_title(){
        $title = get_the_title( $this->get_id() );
        return  apply_filters('wpml_translate_single_string', $title, 'conditional-extra-fees-woocommerce', 'pisol_cefw_fees_title_'.$this->get_id());
    }

    function get_checkbox_title(){
        $checkbox_title = esc_html(get_post_meta( $this->get_id(), 'pi_checkbox_title', true ));

        $checkbox_title = apply_filters('wpml_translate_single_string', $checkbox_title, 'conditional-extra-fees-woocommerce', 'pisol_cefw_fees_checkbox_title_'. $this->get_id());

        if(empty($checkbox_title)){
            $title = apply_filters('pisol_cefw_title_filter', $this->get_title(), $this->get_id());
        }else{
            $title = $checkbox_title;
        }
        
        return apply_filters('pisol_cefw_optional_fess_title_checkbox', $title, $this->get_title(), $this->get_id());
    }

    function get_type(){
        return get_post_meta( $this->get_id(), 'pi_fees_type', true);
    }

    function get_fees(){
        return get_post_meta( $this->get_id(), 'pi_fees', true);
    }

    function get_start_time(){
        return get_post_meta( $this->get_id(), 'pi_fees_start_time', true);
    }

    function get_end_time(){
        return get_post_meta( $this->get_id(), 'pi_fees_end_time', true);
    }

    function get_tax_class(){
        return get_post_meta( $this->get_id(), 'pi_fees_tax_class', true);
    }

    function get_status(){
        $pi_status  = get_post_meta( $this->get_id(), 'pi_status', true );
				
		if ( 'off' === $pi_status ) return false;

        return true;
    }

    function get_tooltip(){
        return get_post_meta( $this->get_id(), 'pi_tooltip', true);
    }

    function get_min_fees(){
        return get_post_meta( $this->get_id(), 'pi_min_cost', true );
    }

    function get_max_fees(){
        return get_post_meta( $this->get_id(), 'pi_max_cost', true );
    }

    function is_taxable(){
        $taxable_val = get_post_meta( $this->get_id(), 'pi_fees_taxable', true);
        return $taxable_val === 'yes' ? true : false;
    }

    function is_available(){
        $start_time = $this->get_start_time();
        $end_time = $this->get_end_time();
        return $this->get_status() && $this->offerStarted($start_time) && !$this->offerEnded( $end_time );
    }

    function is_optional(){
        $is_optional_fees = get_post_meta( $this->get_id(), 'pi_is_optional_fees', true );
        return empty($is_optional_fees) || $is_optional_fees == 'no' ? false : true;
    }

    function offerStarted($start_time){

        if($start_time == ""){
            return true;
        }

        $start_timestamp = strtotime($start_time);
        $now = current_time( 'timestamp' );
        if($start_timestamp <= $now){
            return true;
        }

        return false;
    }

    function offerEnded( $end_time ){

        if($end_time == ""){
            return false;
        }

        $end_timestamp = strtotime($end_time);
        $now = current_time( 'timestamp' );
        if($end_timestamp <= $now){
            return true;
        }

        return false;
    }

    static function matched_fees( $package ){
        $matched_methods = array();
        $args         = array(
            'post_type'      => 'pi_fees_rule',
            'posts_per_page' => - 1,
        );
        $args = apply_filters('pisol_cefw_fees_query', $args);
        $all_methods        = get_posts( $args );
        foreach ( $all_methods as $method ) {

            $fees_obj = new ExtraFees( $method->ID );

            if( !$fees_obj->is_available()) continue;

            if(!pisol_cefw_CurrencyValid($method->ID)) continue;
           
            $is_match = self::matchConditions( $method, $package );
           

            if ( $is_match === true ) {
                $matched_methods[] = $method;
            }
        }

        return $matched_methods;
    }

    static function matched_optional_fees ( $package ){
        $matched_methods = array();
        $args         = array(
            'post_type'      => 'pi_fees_rule',
            'posts_per_page' => - 1,
            'meta_query' => array(
                array(
                    'key' => 'pi_is_optional_fees', 
                    'compare' => '==',
                    'value' => 'yes'
                )
            )
        );

        $args = apply_filters('pisol_cefw_fees_query', $args);
        $all_methods        = get_posts( $args );
        foreach ( $all_methods as $method ) {

            $fees_obj = new ExtraFees( $method->ID );

            if( !$fees_obj->is_available()) continue;

            $is_match = self::matchConditions( $method, $package );
           

            if ( $is_match === true ) {
                $matched_methods[] = $method;
            }
        }

        return $matched_methods;
    }

    static function matchConditions( $method = array(), $package = array() ) {

        if ( empty( $method ) ) {
            return false;
        }

        if ( ! empty( $method ) ) {
            $method_eval_obj = new \Pisol_cefw_method_evaluation( $method, $package );
            $final_condition_match = $method_eval_obj->finalResult();

            if ( $final_condition_match ) {
                return true;
            }
        }

        return false;
    }
}