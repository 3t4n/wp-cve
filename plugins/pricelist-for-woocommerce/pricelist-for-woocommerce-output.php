<?php
if (!class_exists('pricelist_wc_outputter')) {
    class pricelist_wc_outputter {
        public $output;
        protected $options = array();
        protected $id;
        
        public function __construct(&$options, &$output, $id) {
            $this->output = &$output;
            $this->options = &$options;
            $this->id = $id;
        }
        
        public function declaration() {
            $this->output = '<!-- '.$this->options['plugin_declaration'].' -->'.PHP_EOL;
        }
        
        public function openPriceList() { }
        public function closePriceList() { }
        
        public function openCategory($args) { }
        public function closeCategory($args) { $this->output .= '</table>'.PHP_EOL; }
        
        public function openProductRow($product) { }
        public function closeProductRow() { $this->output .= '</tr>'.PHP_EOL; }
        
        public function productImage($image, $name) { }
        public function name($product, $width) { }
        public function description($description, $width) { }
        public function shortDescription($description, $width) { $this->description($description, $width); }
        public function price($product) { }
        
        protected function formatPrice ($price) {
            if (is_numeric($price)) {
                $priceStr = get_woocommerce_currency_symbol() . ' ' . number_format($price, wc_get_price_decimals(), wc_get_price_decimal_separator(), wc_get_price_thousand_separator());
            } else {
                $priceStr = $price;
            }
            return '<span class="price">'.$priceStr.'</span>';
        }
        
        protected function formatName ($product, $linkify = false) {
            $nameText = sanitize_text_field($product->name ?? '');
            return $nameText;
        }
        
        public function tag($tag, $classes = '', $style = '', $attributes = array()) {
            $output = '<'.$tag;
            if (!empty($classes)) $output .= ' class="'.$classes.'"';
            if (!empty($style)) {
                $output .= ' style="';
                foreach ($style as $attr => $val) $output .= $attr.':'.$val.';';
                $output .= '"';
            }
            foreach ($attributes as $attr => $val) $output .= ' '.$attr.'="'.$val.'"';
            $output .= '>';
            $this->output .= $output;
        }
    }
}
?>