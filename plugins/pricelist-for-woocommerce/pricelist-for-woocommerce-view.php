<?php

if (!class_exists('pricelist_wc_generate_output')) {
    class pricelist_wc_generate_output {
        protected $controller;
        protected $options = array();
        protected $output = '';
        protected $outputter;
        protected $current_main_cat;
        protected $current_cat_path;
        protected $allowLocalImages, $forceLocalImages;
        protected $id;
        protected static $counter = 0;
        
        protected function __construct($controller, $use_outputter = true) {
            global $pricelist_plugin;
            $this->id = ++self::$counter;
            $this->controller = $controller;
            $this->options = &$controller->getOptions();
            $plugin_name = 'pricelist-for-woocommerce'.(defined('PRICELIST_WC_PRO')?'-pro':'');
            $file = trailingslashit(WP_PLUGIN_DIR).trailingslashit($plugin_name).$plugin_name.'.php';
            $plugin = get_file_data($file, array('Name' => 'Plugin Name', 'Version' => 'Version', 'URI'   => 'Plugin URI'), 'plugin');
            $this->options['plugin_declaration'] = $plugin['Name'].' '.$plugin['Version'].' ('.$plugin['URI'].')';
            $this->options['th'] = $this->options['table_header_color'];
            $this->options['td'] = $this->options['table_color'];
            $this->options['tdc'] = self::computeContrast($this->options['td']);
            $this->options['thc'] = self::computeContrast($this->options['th']);
            $matches = [];
            if ($use_outputter && preg_match('/^pricelist_wc(_pro)?_generate_(.+)$/', get_called_class(), $matches)) {
                $view = $matches[2];
                if ($view !== 'output') {
                    $this->outputter = $pricelist_plugin->instantiate('outputter_'.$view, null, $this->options, $this->output, $this->id);
                    $this->output = &$this->outputter->output;
                }
            }
        }
        
        protected static function computeContrast($color) {
            $R = hexdec(substr($color, 1, 2));
            $G = hexdec(substr($color, 3, 2));
            $B = hexdec(substr($color, 5, 2));
            $L1 = self::colorToLightness($R, $G, $B);
            $L2 = self::colorToLightness(255, 255, 255);
            $colorContrast = (max($L1, $L2)+0.05) / (min($L1, $L2)+0.05);
            return $colorContrast > 5 ? '#ffffff' : '#000000';
        }
        
        private static function colorToLightness($R, $G, $B) {
            return 0.2126 * pow($R/255, 2.2) +
                   0.7152 * pow($G/255, 2.2) +
                   0.0722 * pow($B/255, 2.2);
        }
        
        protected function newPage($bookmark = false) {}
        
        protected function prepareImageURL($url) {
            if (empty($url)) return $url;
            $original = filter_var($url, FILTER_SANITIZE_URL);
            if (!$this->allowLocalImages && !$this->forceLocalImages) return $original;
            
            $url = parse_url($original);
            $local = realpath($_SERVER['DOCUMENT_ROOT'].$url['path']);
            if (!$local) return $this->forceLocalImages ? '' : $original;
            
            $file = pathinfo($local);
            $small = $file['dirname'].'/'.$file['filename'].'-100x100'.'.'.$file['extension'];
            $small = realpath($small);
            if ($small) return 'file://' . $small;
            
            if (substr($file['filename'], -8) === '-scaled') {
                $small = $file['dirname'].'/'.substr($file['filename'], -8).'-100x100'.'.'.$file['extension'];
                $small = realpath($small);
                
                if (!$small) echo $file['dirname'].'/'.substr($file['filename'], -8).'-100x100'.'.'.$file['extension']."<br>\r\n";
                if ($small) return 'file://' . $small;
            }
            return 'file://' . $local;
        }
        
        public function generateOutput() {
            $options = &$this->options;
            
            $this->outputter->openPriceList();
            
            $attachments = [];
            $categories = $this->controller->getData();
            foreach ($categories as $category) {
                if ($category->parent == 0) {
                    $this->displayCategory($categories, $category->term_id, $attachments);
                }
            }
            
            $this->displayAttachments($attachments);
            
            $this->outputter->closePriceList();
            return $this->output;
        }
        
        protected function displayCategory($categories, $id, &$attachments, $parent = 0, $prefix = '') {
            $category = $categories[$id];
            if (!$category->hasContent) return;
            $isMain = $parent == 0;
            $title = $prefix.$category->name;
            $hasContent = !empty($category->products);
            $args = [
                'isMain' => $isMain,
                'title' => $title,
                'hasContent' => $hasContent,
            ];
            if ($isMain) {
                $this->newPage($category->name);
                $args['description'] = empty($category->description) ? false : $category->description;
                $args['image'] = empty($category->image) ? false : $this->prepareImageURL($category->image);
            }
            if ($isMain || $hasContent) {
                $this->outputter->openCategory($args);
                foreach ($category->products as $product) {
                    $this->displayProduct($product, $attachments);
                }
                $this->outputter->closeCategory($args);
            }
            foreach ($category->children as $child) {
                $this->displayCategory($categories, $child, $attachments, $category->term_id, $prefix.$category->name.' &gt; ');
            }
        }
        
        protected function displayProduct($product, &$attachments = null) {
            $product->isVariation = $product->type === 'WC_Product_Variation';
            
            $this->outputter->openProductRow($product);
            $this->displayImage($product);
            $this->displayName($product);
            $this->displayDescriptions($product);
            $this->displayPrice($product, $attachments);
            $this->outputter->closeProductRow();
        }
        
        protected function displayImage($product) {
            if ($this->options['product_image'] && !$product->isVariation) {
                $this->outputter->productImage(isset($product->Product_image) ? $this->prepareImageURL($product->Product_image) : false, $product->name);
            }
        }
        protected function displayName($product) {
            $options = $this->options;
            $name_width = 90;
            if($options['product_image'] && !$product->isVariation){ $name_width -= 5; }
            if(($options['description'] || $options['short_description']) && !$product->isVariation) { $name_width = 25; }
            $this->outputter->name($product, $name_width);
        }
        protected function displayDescriptions($product) {
            $options = $this->options;
            $desc_width = 65;
            if($options['product_image']){ $desc_width -= 5; }
            if($options['description'] && $options['short_description']) { $desc_width /= 2; }
            if ($options['short_description'] && !$product->isVariation) {
                $this->outputter->description($product->Short_description ?? '', $desc_width);
            }
            if ($options['description'] && !$product->isVariation) {
                $this->outputter->description($product->Description ?? '', $desc_width);
            }
        }
        protected function displayPrice($product, $attachments) {
            $this->outputter->price($product, $attachments !== null);
        }
        
        protected function displayAttachments($attachments) {}
    }
}

global $pricelist_plugin;
require_once $pricelist_plugin->include_file('generate_html', false);
require_once $pricelist_plugin->include_file('generate_pdf', false);
require_once $pricelist_plugin->include_file('generate_pdf_button', false);
?>