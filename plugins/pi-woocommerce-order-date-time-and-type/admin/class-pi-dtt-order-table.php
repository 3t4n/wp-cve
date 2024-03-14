<?php

if(!class_exists('pisol_dtt_order_table')){
class pisol_dtt_order_table{
    function __construct(){
        add_filter( 'manage_edit-shop_order_columns', array($this,'deliverPickupDateColumn') );
        add_filter( 'manage_woocommerce_page_wc-orders_columns', array($this,'deliverPickupDateColumn') ); //hpos

        add_action( 'manage_shop_order_posts_custom_column', array($this,'deliverPickupDate') );
        add_action( 'manage_woocommerce_page_wc-orders_custom_column', array($this,'deliverPickupDateHPOS'),10, 2 ); //hpos

        add_filter( 'manage_edit-shop_order_sortable_columns', array($this,'sortByDate') );
        add_filter( 'woocommerce_shop_order_list_table_sortable_columns', array($this,'sortByDate') ); //hpos
        add_filter( 'manage_woocommerce_page_wc-orders_sortable_columns', array($this,'sortByDate') );//hpos

        add_action( 'pre_get_posts', array($this,'sortByValue')  );
        add_filter( 'woocommerce_shop_order_list_table_prepare_items_query_args', array($this, 'sortByValueHPOS')  ); //hpos
    }

    function sortByDate($col){
        $col['pisol_dpd'] = 'pisol_dpd';
        $col['pisol_method'] = 'pisol_method';
        $col['pisol_time'] = 'pisol_time';
        return $col;
    }

    function sortByValue($query){
        if( ! is_admin() ) return;
 
        $orderby = $query->get( 'orderby');
    
        if( 'pisol_dpd' == $orderby ) {
            $query->set('meta_key','pi_system_delivery_date');
            $query->set('meta_type','DATE');
            $query->set('orderby','meta_value');
        }

        if( 'pisol_method' == $orderby ) {
            $query->set('meta_key','pi_delivery_type');
            $query->set('orderby','meta_value');
        }

        if( 'pisol_time' == $orderby ) {
            $query->set('meta_key','pi_delivery_time');
            $query->set('meta_type','TIME');
            $query->set('orderby','meta_value');
        }
    }

        /**
     * At present sorting is not working 
     */
    function sortByValueHPOS($args){
        if( ! is_admin() ) return $args;
 
        $orderby = isset($args[ 'orderby']) ? $args[ 'orderby'] : '';
        
        if( 'pisol_dpd' == $orderby ) {
            $args['orderby'] = 'meta_value';
            $args['meta_key'] = 'pi_system_delivery_date';
            $args['meta_type'] = 'DATE';
        }
        
        if( 'pisol_method' == $orderby ) {
            $args['meta_key'] = 'pi_delivery_type';
            $args['orderby'] = 'meta_value';
        }

        if( 'pisol_time' == $orderby ) {
            $args['orderby'] = 'meta_value';
            $args['meta_key'] = 'pi_delivery_time';
            $args['meta_type'] = 'TIME';
        }
        
        return $args;
    }

    function deliverPickupDateColumn( $columns ) {
        $columns['pisol_dpd'] = __('Order Arrival Date','pisol-dtt');
        $columns['pisol_time'] = __('Order Arrival Time','pisol-dtt');
        $columns['pisol_method'] = __('Method','pisol-dtt');
        $columns['pisol_pickup_location'] = __('Pickup shop','pisol-dtt');
        return $columns;
    }

    function deliverPickupDate( $column ) {
        global $post;
        $order = wc_get_order( $post->ID );

        if(empty($order)) return;

        if ( 'pisol_dpd' === $column ) {
            $date = "";
            $sysdeldate = $order->get_meta( 'pi_system_delivery_date', true );
            $old_date = $order->get_meta( 'pi_delivery_date', true );

            if(! empty($sysdeldate)){
                
                $date = pi_dtt_date::translatedDate($sysdeldate);
                echo $this->emptyValue($date);

			}elseif(!empty($old_date)){
                $date = ($old_date);
                echo $date;
            }
            
        }

        if ( 'pisol_time' === $column ) {
            $time = $order->get_meta( 'pi_delivery_time', true );
            echo pisol_dtt_time::formatTimeForDisplay($time);
        }

        if ( 'pisol_method' === $column ) {
            $delivery_method = $order->get_meta( 'pi_delivery_type', true );

            if( $delivery_method =='pickup'){
                $order_delivery_type =  __('Pickup','pisol-dtt'); 
                $class= "processing";
            }else{ 
                $order_delivery_type = __('Delivery','pisol-dtt');
                $class= "completed";
            }
            $order_delivery_type = '<mark class="order-status status-'.$class.' "><span>'.$order_delivery_type.'</span></mark>';
            $order_delivery_type = apply_filters('pisol_dtt_order_table_delivery_method', $order_delivery_type, $delivery_method);
            echo $this->emptyValue($order_delivery_type) ;
        }

        if ( 'pisol_pickup_location' === $column ) {
            $old_location = $order->get_meta( 'pickup_location', true );
            $location_id = $order->get_meta( 'pickup_location_id', true );

            if(empty($location_id)){
                echo $old_location;
            }elseif(!empty($location_id)){
                $location_post = get_post($location_id);
                $location_title = $location_post->post_title;
                echo $location_title;
            }
        }
    }

    function deliverPickupDateHPOS( $column, $order ) {

        if(empty($order)) return;

        if ( 'pisol_dpd' === $column ) {
            $date = "";
            $sysdeldate = $order->get_meta('pi_system_delivery_date', true );
            $old_date = $order->get_meta( 'pi_delivery_date', true );

            if(! empty($sysdeldate)){

                $date = pi_dtt_date::translatedDate($sysdeldate);
                echo $this->emptyValue($date);

			}elseif(!empty($old_date)){
                $date = ($old_date);
                echo $date;
            }
            
        }

        if ( 'pisol_time' === $column ) {
            $time = $order->get_meta( 'pi_delivery_time', true );
            echo pisol_dtt_time::formatTimeForDisplay($time);
        }

        if ( 'pisol_method' === $column ) {
            $delivery_method = $order->get_meta( 'pi_delivery_type', true );

            if( $delivery_method =='pickup'){
                $order_delivery_type =  __('Pickup','pisol-dtt'); 
                $class= "processing";
            }else{ 
                $order_delivery_type = __('Delivery','pisol-dtt');
                $class= "completed";
            }
            $order_delivery_type = '<mark class="order-status status-'.$class.' "><span>'.$order_delivery_type.'</span></mark>';
            $order_delivery_type = apply_filters('pisol_dtt_order_table_delivery_method', $order_delivery_type, $delivery_method);
            echo $this->emptyValue($order_delivery_type) ;
        }

        if ( 'pisol_pickup_location' === $column ) {
            $old_location = $order->get_meta( 'pickup_location', true );
            $location_id = $order->get_meta( 'pickup_location_id', true );

            if(empty($location_id)){
                echo $old_location;
            }elseif(!empty($location_id)){
                $location_post = get_post($location_id);
                $location_title = $location_post->post_title;
                echo $location_title;
            }
        }
    }

    function emptyValue($value){
        if($value != ""){
            return $value;
        }else{
            return '-';
        }
    }
}

new pisol_dtt_order_table();
}