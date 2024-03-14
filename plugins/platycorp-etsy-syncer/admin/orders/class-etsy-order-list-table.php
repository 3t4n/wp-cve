<?php

class Etsy_Order_List_Table extends WC_Admin_List_Table_Orders{



    function __construct(){
        $this->list_table_type = 'etsy_shop_order';
        parent::__construct();
        add_filter('post_class', function($classes, $class, $id){
				$classes = array_map(function($c){return $c=="type-etsy_shop_order" ? "type-shop_order" : $c;}, $classes);
                return $classes;
        },10,3);

        add_filter('admin_body_class', function($classes){
            $classes = explode(" ", $classes);
            $classes[] = 'post-type-shop_order';
            return implode(" ", $classes);
        },1000,1);
       
    }
    protected function render_order_number_column() {
        ob_start();
        parent::render_order_number_column();
        $dom= new DomDocument();
        $html = ob_get_clean();
        $dom->loadXML('<div>' . $html . '</div>');
        $xpath = new DOMXPath($dom);
        $preview = $xpath->query('//a[@class="order-preview"]');
        foreach ($preview as $element) {
            $element->parentNode->removeChild($element);
        }
        echo $dom->saveHTML($dom);


        
    }

    public function search_label( $query ) {
        global $typenow;
        $typenow = 'shop_order';
        parent::search_label($query);
        $typenow = 'etsy_shop_order';
    }

    public function search_custom_fields( $wp ) {
        $wp->query_vars['post_type'] = "shop_order";
        parent::search_custom_fields($wp);
        $wp->query_vars['post_type'] = "etsy_shop_order";


    }

    protected function render_filters() {

    }
}