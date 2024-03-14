<?php
if (!class_exists('pricelist_wc_outputter_html')) {
    global $pricelist_plugin;
    require_once $pricelist_plugin->include_file('outputter');
    trait pricelist_wc_outputter_html_trait {
        protected $tdStyle;
        protected $cssClass = '';
        protected $colSpan = null;
        
        private function init(&$options, &$output, $id) {
            $this->tdStyle = 'background-color:' . $options['td'] . ' !important; color:' . $options['tdc'] . ' !important; border-color: ' . $options['tdc'] . ' !important;';
            $this->tdStyle .= 'padding: 3px; border: 1px solid; vertical-align: middle !important;';
            $this->initColSpan($options);
        }
        
        protected function initColSpan($options) {
            $this->colSpan = 2;
            if ($options['product_image']) $this->colSpan++;
            if ($options['description']) $this->colSpan++;
            if ($options['short_description']) $this->colSpan++;
        }
        
        public function openPriceList() {
            $options = &$this->options;
            
            $this->declaration();
            
            $this->output .= '<style>
            .pricelist_zoom:hover {
                transform: scale(10);
            }
            </style>';
            $this->output .= '<div class="pricelist" id="pricelist'.$this->id.'" style="max-width: none !important; margin-bottom: 1em !important;">';
            $this->output .= '<h2>' . $options['name'] . '</h2>';
        }
        public function closePriceList() {
            $this->output .= '</div>'.PHP_EOL;
        }
        
        protected function startTableHeader($id = false, $tableClass = false) {
            $options = &$this->options;
            $cssClass = 'pricelist';
            if ($tableClass === false) $tableClass = $cssClass;

            $this->output .= '<table ';
            if (!empty($id)) $this->output .= 'id="'.$id.'" ';
            $this->output .= 'class="'.$tableClass.'" style="
                overflow: unset !important; 
                border-collapse: collapse !important;
                border: 1px solid !important;
                background-color:' . $options['td'] . ' !important;
                color:' . $options['tdc'] . ' !important; 
                border-color: ' . $options['tdc'] . ' !important;">';
            
            $this->output .= '<thead><tr class="'.$cssClass.'" style="
                font-weight: bold;
                background-color:' . $options['th'] . ' !important; 
                color:' . $options['thc'] . ' !important;">
            <th colspan="'.$this->colSpan.'" style="
                border: 1px solid !important;
                padding: 5px !important;
                background-color:' . $options['th'] . ' !important; 
                color:' . $options['thc'] . ' !important; 
                border-color: ' . $options['tdc'] . ' !important;">';
        }

        protected function fillTableHead($title, $image = false, $description = false) {
            $cssClass = 'pricelist';

            if (!empty($image)){
                $this->output .= '<div style="width: 100px; display: table-cell; vertical-align: middle"><img class="'.$cssClass.'" style="max-width:100px;max-height:100px;" src="' . $image .'" alt="Image for '.$title.'"/></div>';
            }
            $this->output .= '<div style="width: 100%; padding: 10px; display: table-cell; vertical-align: middle;"><h3 style="margin: 0px !important">' . $title . '</h3>';
            if (!empty($description)) {
                $this->output .= '<p style="">' . $description . '</p>';
            }
            $this->output .= '</div>';
        }
        
        public function openCategory($args) {
            extract($args);
            $newSub = !empty($sub_cat);
            $this->startTableHeader();
            if ($isMain) {
                $this->fillTableHead($title, $image, $description);
            } else {
                $this->output .= $title;
            }
            $this->output .= '</th></tr></thead>';
        }
        public function closeCategory($args) {
            $this->output .= '</table><br/>'.PHP_EOL;
        }
        public function openProductRow($product) {
            $hasInlineAttachments = isset($product->SubProducts) && $this->options['attachment'] === 'inline';
            $rowClass = $hasInlineAttachments ? 'pricelist_has_attachments' : '';
            $this->output .= '<tr class="'.$rowClass.'">';
        }
        
        public function productImage($image, $name) {
            $this->output .=  '<td style="'.$this->tdStyle.' width:5%; text-align:center;" class="'.$this->cssClass.'"><div style="display: flex; justify-content: center;">';
            if ($image) $this->output .=  '<img class="pricelist_zoom '.$this->cssClass.'" style="max-height:50px;max-width:50px;transition:transform .5s;transform-origin:left;" src="' . $image . '" alt="Product image for '.$name.'"/>';
            $this->output .=  '</div></td>';
        }
        public function name($product, $width) {
            $isVariation = $product->type === 'WC_Product_Variation';
            $name = $this->formatName($product, !$isVariation);
            $this->output .= '<td style="'.$this->tdStyle.' width:'.$width.'%;text-align:center;font-weight:bold;" class="'.$this->cssClass.'">' . $name;
            $this->output .= '</td>';
        }
        public function description($description, $width) {
            $this->output .= '<td style="'.$this->tdStyle.' width:'.$width.'%" class="'.$this->cssClass.'">' . $description .  '</td>';
        }
        
        public function price($product, $attachments = false) {
            $options = $this->options;
            $price = $product->Price ?? 0;
            $this->output .= '<td class="'.$this->cssClass.'" style="'.$this->tdStyle.' width:10%;text-align:center;font-size:';
            if($price < 1000){
                $this->output .= '1';
            }else if($price < 10000){
                $this->output .= '0.85';
            }else if($price < 100000){
                $this->output .= '0.8';
            }else {
                $this->output .= '0.7';
            }
            $this->output .= 'em;min-width:50px;';
            $this->output .= '">';
            $this->output .= $this->formatPrice($product->Price ?? '');
            $this->output .= '</td>';
        }
    }
    
    class pricelist_wc_outputter_html extends pricelist_wc_outputter {
        use pricelist_wc_outputter_html_trait;
        public function __construct(&$options, &$output, $id) {
            parent::__construct($options, $output, $id);
            $this->init($options, $output, $id);
        }
    }
}
?>