<?php
if (!class_exists('pricelist_wc_outputter_pdf')) {
    global $pricelist_plugin;
    require_once $pricelist_plugin->include_file('outputter');
    trait pricelist_wc_outputter_pdf_trait {
        protected $tdStyle;
        protected $cssClass = '';
        
        public function init(&$options, &$output, $id) {
            $this->tdStyle = 'background-color:' . $options['td'] . ' !important; color:' . $options['tdc'] . ' !important; border-color: ' . $options['tdc'] . ' !important;';
            
        }
        public function setWidth($obj_pdf) {
            $this->width = floor(($obj_pdf->getPageWidth()-PDF_MARGIN_LEFT-PDF_MARGIN_RIGHT)*$obj_pdf->getScaleFactor()*$obj_pdf->getImageScale());
        }
        
        protected function bigHeader($title, $image = false, $description = false) {
            $options = &$this->options;
            $width = $this->width;
            
            $this->output .= '<table width="100%" style="margin: 0; padding: 0; background-color:' . $options['th'] . '; color:' . $options['thc'] . '; border: 1px solid ' . $options['tdc'] . ';" border="0" cellspacing="0" cellpadding="3">';
            $this->output .= '<tr nobr="true">';
            
            if (!empty($image)) {
                $width -= 100;
                $this->output .= '<td style="" width="100px"><img height="65px" src="' . $image .'" alt="Image for '.$title.'"/></td>';
            }
            $this->output .= '<td width="'.$width.'px"><h3 style="font-size: larger; font-weight: bold;">' . $title . '</h3>';
            if (!empty($description)) {
                $this->output .= '<p style="">' . $description . '</p>';
            }
            
            $this->output .= '</td></tr></table><br/><br/>';
        }
        
        protected function smallTableHeader($title, $fontSize = 'large') {
            $options = &$this->options;
            
            $this->output .= '<table style="background-color:' . $options['td'] . '; color:' . $options['tdc'] . '; border: 1px solid ' . $options['tdc'] . ';" border="1" cellspacing="0" cellpadding="3">';
            $this->output .= '<thead><tr style="background-color:' . $options['th'] . '; color:' . $options['thc'] . ';  font-weight:bold;"><th colspan="42" style="border: 1px solid ' . $options['tdc'] . '; font-size: ' . $fontSize . '; font-weight: bold; text-align: center;">' . $title . '</th></tr></thead>';
        }
        
        public function openCategory($args) {
            extract($args);
            
            if ($isMain) $this->bigHeader($title, $image, $description);
            if ($hasContent) $this->smallTableHeader($title);
        }
        public function closeCategory($args) {
            extract($args);
            if ($hasContent) $this->output .= '</table><br/><br/>'.PHP_EOL;
        }
        
        public function openProductRow($product) {
            $hasInlineAttachments = isset($product->SubProducts) && $this->options['attachment'] === 'inline';
            $omitBorder = $hasInlineAttachments ? 'border-bottom-color: '.$this->options['td'].';' : '';
            $this->output .= '<tr nobr="true" style="'.$omitBorder.'">';
        }
        
        public function productImage($image, $name) {
            $this->output .=  '<td width="5%" style="text-align:center; vertical-align: middle;">';
            if ($image) $this->output .=  '<img src="' . $image . '"/>';
            $this->output .=  '</td>';
        }
        public function name($product, $width) {
            $isVariation = $product->type === 'WC_Product_Variation';
            $name = $this->formatName($product, !$isVariation);
            $hasInlineAttachments = isset($product->SubProducts) && $this->options['attachment'] === 'inline';
            $omitBorder = $hasInlineAttachments ? 'border-bottom-color: '.$this->options['td'].';' : '';
            
            $this->output .= '<td width="'.$width.'%" style="text-align: center; vertical-align: middle; font-weight: bold; '.$omitBorder.'">' . $name . '</td>';
        }
        public function description($description, $width) {
            $this->output .= '<td width="'.$width.'%" class="'.$this->cssClass.'" style="vertical-align: middle;">'.$description.'</td>';
        }
        
        public function price($product, $attachments = false) {
            $price = $product->Price ?? 0;
            $this->output .= '<td valign="middle" width="10%" style="text-align:center;vertical-align: middle;font-size:';
            if($price < 1000){
                $this->output .= '10';
            }else if($price < 10000){
                $this->output .= '8.5';
            }else if($price < 100000){
                $this->output .= '8';
            }else {
                $this->output .= '7';
            }
            $this->output .= 'vw">';
            $this->output .= $this->formatPrice($product->Price ?? '');
            $this->output .= '</td>';
        }
    }

    class pricelist_wc_outputter_pdf extends pricelist_wc_outputter {
        use pricelist_wc_outputter_pdf_trait;
        public function __construct(&$options, &$output, $id) {
            parent::__construct($options, $output, $id);
            $this->init($options, $output, $id);
        }
    }
}
?>