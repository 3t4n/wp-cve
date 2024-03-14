<?php
if (!class_exists('pricelist_wc_option')) {
    class pricelist_wc_option {
        const NOT_LOADED = -1;
        const USED_DEFAULT = 0;
        const USED_STORED_VALUE = 1;
        const USED_GIVEN_VALUE = 2;
        
        protected static $max_order = 0;
        
        public $name, $code_name, $get_name, $post_name, $opt_name;
        public $type, $description;
        public $display, $tooltip;
        public $default;
        public $context;
        public $pro;
        
        protected $value = null;
        
        public function __construct($args) {
            $this->name = $args['name'];
            $this->display = $args['display'];
            $this->default = $args['default'] ?? null;
            $this->type = $args['type'];
            $this->description = $args['description'] ?? '';
            $this->tooltip = $args['tooltip'] ?? $this->description;
            $this->context = $args['context'] ?? 'settings';
            $this->pro = $args['pro'] ?? false;
            $this->code_name = $args['code_name'] ?? $this->name;
            $this->get_name = $args['get_name'] ?? $this->name;
            $this->post_name = 'pricelist_';
            $this->post_name .= $args['post_name'] ?? $this->name;
            $this->opt_name = 'pricelist_'.(defined('PRICELIST_WC_PRO')?'pro_':'');
            $this->opt_name .= $args['opt_name'] ?? $this->name;
            
            $this->order = $args['order'] ?? static::$max_order;
            static::$max_order = max(static::$max_order, $this->order+1);
        }
        protected function loadFromSettings() {
            if (($value = get_option($this->opt_name)) !== false) {
                $this->value = $this->fromStorage($value);
                return pricelist_wc_option::USED_STORED_VALUE;
            } elseif ($this->default !== null) {
                $this->value = $this->fromStorage($this->default);
                return pricelist_wc_option::USED_DEFAULT;
            } else {
                return pricelist_wc_option::NOT_LOADED;
            }
        }
        protected function loadFromShortcode($value, $strict = false) {
            return $this->loadFromRequest($value, $strict);
        }
        protected function loadFromRequest($value, $strict = false) {
            if ($this->processUserValue($value)) {
                $this->value = $value;
                return pricelist_wc_option::USED_GIVEN_VALUE;
            } elseif (!$strict) {
                return $this->loadFromSettings();
            } else {
                return pricelist_wc_option::NOT_LOADED;
            }
        } 
        protected function processUserValue(&$value) {
            return true;
        }
        protected function fromStorage($value) {
            return $value;
        }
        protected function toStorage() {
            return $this->value;
        }
        public function strValue() {
            return $this->toStorage();
        }
        protected function updateOption($args) {
            return isset($args[$this->post_name])
                && $this->loadFromRequest($args[$this->post_name], true) === pricelist_wc_option::USED_GIVEN_VALUE
                && update_option($this->opt_name, $this->toStorage());
        }
        
        private static $options = [];
        public static function Register($option) {
            static::$options[$option->name] = $option;
        }
        public static function RegisterSettings() {
            global $pricelist_plugin;
            $group = get_class($pricelist_plugin).'-group';
            foreach (static::$options as $name => $option) {
                if ($option instanceof pricelist_wc_option_group) continue;
                register_setting($group, $option->opt_name, [
                    'type' => $option->type,
                    'description' => $option->description,
                    'default' => $option->default
                ]);
            }
            static::add_settings_section( $group.'-section', 'Configure Default Shortcode Values', null, 'section_options_page_type' );
        }
        static $page_sections = array();
        public static function whitelist_custom_options_page( $whitelist_options ){
            foreach(static::$page_sections as $page => $sections ){
                $whitelist_options[$page] = array();
                foreach( $sections as $section ) {
                    foreach( $whitelist_options[$section] as $option ) {
                        $whitelist_options[$page][] = $option;
                    }
                }
            }
            return $whitelist_options;
        }
        protected static function add_settings_section( $id, $title, $cb, $page ){
            add_settings_section( $id, $title, $cb, $page );
            if( $id != $page ){
                if( !isset(static::$page_sections[$page]))
                    static::$page_sections[$page] = array();
                static::$page_sections[$page][$id] = $id;
            }
        }
        
        public static function Get(...$names) {
            $n = count($names);
            if ($n === 0) return static::$options;
            if ($n === 1) return static::$options[$names[0]];
            $options = [];
            foreach ($names as $name) {
                $options[$name] = static::$options[$name];
            }
            return $options;
        }
        public static function Value($name) {
            return static::$options[$name]->value;
        }
        public static function Values($context = '', $pro = true) {
            $options = [];
            foreach (static::$options as $option) {
                if (!empty($context) && $option->context !== $context || $option->pro && !$pro) continue;
                $options[$option->name] = $option->value;
            }
            return $options;
        }
        public static function Load($src, $args = null) {
            if (!in_array($src, ['settings', 'shortcode', 'get', 'post'])) {
                _doing_it_wrong(__FUNCTION__, "src argument has illegal value '$src'", '6.0.1');
            }
            foreach(static::$options as $option) {
                if ($src === 'shortcode') {
                    if (isset($args[$option->code_name])) {
                        $option->loadFromShortcode($args[$option->code_name]);
                        continue;
                    }
                } elseif ($src === 'post') {
                    if (isset($args[$option->post_name])) {
                        $option->loadFromRequest($args[$option->post_name]);
                        continue;
                    }
                } elseif ($src === 'get') {
                    if (isset($args[$option->get_name])) {
                        $option->loadFromRequest($args[$option->get_name]);
                        continue;
                    }
                }
                $option->loadFromSettings();
            }
        }
        
        public static function Update($args, $context, $pro) {
            $options = [];
            foreach(static::$options as $option) {
                if ($option->context !== $context || $option->pro && !$pro) continue;
                if ($option->updateOption($args) || $option->value === null) {
                    $option->loadFromSettings();
                }
                $options[$option->name] = $option->value;
            }
            return $options;
        }
    }
    class pricelist_wc_text_option extends pricelist_wc_option {
        public function __construct($args) {
            if (!isset($args['type'])) $args['type'] = 'string';
            parent::__construct($args);
        }
        protected function fromStorage($value) {
            return esc_html($value);
        }
        protected function loadFromRequest($value, $strict = false) {
            return parent::loadFromRequest(sanitize_text_field($value), $strict);
        }
    }
    class pricelist_wc_enum_option extends pricelist_wc_option {
        public function __construct($args) {
            if (!isset($args['type'])) $args['type'] = 'string';
            parent::__construct($args);
            $this->possibilities = $args['possibilities'];
        }
        protected function processUserValue(&$value) {
            return in_array($value, $this->possibilities);
        }
    }
    class pricelist_wc_bool_option extends pricelist_wc_option {
        public function __construct($args) {
            if (!isset($args['type'])) $args['type'] = 'boolean';
            parent::__construct($args);
        }
        protected function processUserValue(&$value) {
            if ($value === 'true') $value = true;
            if ($value === 'false') $value = false;
            return is_bool($value);
        }
        protected function fromStorage($value) {
            return $value === 'true';
        }
        protected function toStorage() {
            return $this->value ? 'true' : 'false';
        }
    }
    class pricelist_wc_color_option extends pricelist_wc_option {
        public function __construct($args) {
            if (!isset($args['type'])) $args['type'] = 'string';
            parent::__construct($args);
        }
        protected function processUserValue(&$value) {
            if (empty($value)) return false;
            $x = $value;
            if ($x[0] !== '#') $x = '#'.$x;
            if (!preg_match('/^\#([A-F0-9]{3}){1,2}$/i', $x)) return false;
            if (strlen($x) === 4) {
                $x = '#'.$x[1].$x[1].$x[2].$x[2].$x[3].$x[3];
            }
            $value = $x;
            return true;
        }
        protected function fromStorage($value) {
            if ($value[0] !== '#') $value = '#'.$value;
            return $value;
        }
    }
    class pricelist_wc_int_option extends pricelist_wc_option {
        public function __construct($args) {
            if (!isset($args['type'])) $args['type'] = 'integer';
            parent::__construct($args);
            if (isset($args['min'])) $this->min = $args['min'];
            if (isset($args['max'])) $this->max = $args['max'];
        }
        protected function processUserValue(&$value) {
            if (!is_numeric($value)) return false;
            $oldValue = $value;
            if (is_string($value) || is_float($value)) {
                if (floatval(intval($value)) !== floatval($value)) return false;
                $value = intval($value);
            }
            if ((!isset($this->min) || $this->min <= $value)
                && (!isset($this->max) || $this->max >= $value)) return true;
            $value = $oldValue;
            return false;
        }
        protected function fromStorage($value) {
            return intval($value);
        }
        public function strValue() {
            return strval($this->value);
        }
    }
    class pricelist_wc_array_option extends pricelist_wc_option {
        public function __construct($args) {
            if (!isset($args['type'])) $args['type'] = 'array';
            parent::__construct($args);
            $this->possibilities = $args['possibilities'] ?? null;
        }
        protected function processUserValue(&$value) {
            if (is_string($value)) {
                $value = explode(',', $value);
            } elseif (!is_array($value)) return false;
            if (is_array($this->possibilities)) $value = array_intersect($value, $this->possibilities);
            return true;
        }
        public function strValue() {
            return serialize($this->value);
        }
        protected function updateOption($args) {
            if (!isset($args[$this->post_name]) && !empty($args['has_'.$this->post_name])) {
                $args[$this->post_name] = [];
            }
            return parent::updateOption($args);
        }
    }
    class pricelist_wc_option_group extends pricelist_wc_option {
        public function __construct($args, $options) {
            if (!isset($args['type'])) $args['type'] = 'array';
            parent::__construct($args);
            $this->members = $options;
        }
    }

    pricelist_wc_option::Register(new pricelist_wc_text_option([
        'name'        => 'company',
        'default'     => 'My Company',
        'display'     => 'Company Name',
        'description' => 'The name of your company to be displayed in the PDF output.',
        'context'     => 'settings',
        'pro'         => false,
        'order'       => 0,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_text_option([
        'name'        => 'name',
        'default'     => 'Price List',
        'display'     => 'Price List Name',
        'description' => 'The name of the price list.',
        'tooltip'     => 'The name of the price list to be displayed above the HTML price list table and in the top right corner of each PDF page.',
        'context'     => 'settings',
        'pro'         => false,
        'order'       => 1,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_text_option([
        'name'        => 'page',
        'default'     => 'Page',
        'display'     => 'Page Name',
        'description' => 'Page name indicator.',
        'tooltip'     => 'The page name indicator at the bottom of each page in the PDF.',
        'context'     => 'settings',
        'pro'         => false,
        'order'       => 2,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_enum_option([
        'name'        => 'output',
        'get_name'    => 'delivery',
        'possibilities' => ['html', 'pdf', 'dl'],
        'default'     => 'html',
        'display'     => 'Shortcode Output',
        'description' => 'The type of output you want the shortcode to display.',
        'tooltip'     => 'Which type of output the shortcode will display. When it\'s on HTML it will display the output directly to the page, when it\'s on PDF Display it will create a button for the visitor to generate and view a PDF price list in their browser, and when set to PDF Download that price list PDF will be downloaded.',
        'context'     => 'settings',
        'pro'         => false,
        'order'       => 3,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_color_option([
        'name'        => 'table_header_color',
        'get_name'    => 'th_color',
        'default'     => '#82aad7',
        'display'     => 'Table Header Color',
        'description' => 'A hexadecimal color code with a # in front of it for the headers of the tables displaying the products.',
        'tooltip'     => 'The color for the headers of the tables displaying the products.',
        'context'     => 'settings',
        'pro'         => false,
        'order'       => 4,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_color_option([
        'name'        => 'table_color',
        'get_name'    => 'color',
        'default'     => '#ffffff',
        'display'     => 'Table Color',
        'description' => 'A hexadecimal color code with a # in front of it for the tables displaying the products.',
        'tooltip'     => 'The color for the rest of the entire table.',
        'context'     => 'settings',
        'pro'         => false,
        'order'       => pricelist_wc_option::Get('table_header_color')->order+.1,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_bool_option([
        'name'        => 'description',
        'default'     => 'false',
        'display'     => 'Product Description',
        'description' => 'Whether the full WooCommerce product description should be displayed in the price list. (This usually ends up in squeezed space, so it is often a better idea to use the short description.)',
        'context'     => 'settings',
        'pro'         => false,
        'order'       => 5,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_bool_option([
        'name'        => 'short_description',
        'default'     => 'false',
        'display'     => 'Product Short Description',
        'description' => 'Whether the short WooCommerce product description should be displayed in the price list.',
        'context'     => 'settings',
        'pro'         => false,
        'order'       => pricelist_wc_option::Get('description')->order+.1,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_bool_option([
        'name'        => 'product_image',
        'default'     => 'false',
        'display'     => 'Product Image',
        'description'     => 'Whether the WooCommerce product image should be displayed in the table for each product.',
        'context'     => 'settings',
        'pro'         => false,
        'order'       => pricelist_wc_option::Get('short_description')->order+.1,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_bool_option([
        'name'        => 'category_description',
        'default'     => 'false',
        'display'     => 'Category Description',
        'description'     => 'Whether the WooCommerce category description should be displayed for each category.',
        'context'     => 'settings',
        'pro'         => false,
        'order'       => 6,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_bool_option([
        'name'        => 'category_image',
        'default'     => 'false',
        'display'     => 'Category Image',
        'description'     => 'Whether the WooCommerce category image should be displayed for each category.',
        'context'     => 'settings',
        'pro'         => false,
        'order'       => pricelist_wc_option::Get('category_description')->order+.1,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_int_option([
        'name'        => 'date1',
        'min'         => 0,
        'max'         => 3,
        'default'     => 0,
        'display'     => 'PDF Date',
        'description' => 'PDF Date 1',
        'tooltip'     => 'How the date should be displayed in the top right corner of the PDF',
        'order'       => 7,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_int_option([
        'name'        => 'date2',
        'min'         => 0,
        'max'         => 3,
        'default'     => 2,
        'display'     => '',
        'description' => 'PDF Date 2',
        'tooltip'     => '',
        'order'       => 0,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_int_option([
        'name'        => 'date3',
        'min'         => 0,
        'max'         => 3,
        'default'     => 3,
        'display'     => '',
        'description' => 'PDF Date 3',
        'tooltip'     => '',
        'order'       => 0,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_option_group([
        'name'        => 'date',
        'display'     => 'PDF Date',
        'tooltip'     => 'How the date should be displayed in the top right corner of the PDF.',
        'pro'         => false,
        'order'       => 7,
    ], [pricelist_wc_option::Get('date1'), pricelist_wc_option::Get('date2'), pricelist_wc_option::Get('date3')]));
    pricelist_wc_option::Register(new pricelist_wc_text_option([
        'name'        => 'read_more',
        'default'     => 'Read More',
        'display'     => 'Read More Text',
        'description' => 'Read More text.',
        'tooltip'     => 'What should be displayed when the (short) description gets cut off.',
        'pro'         => true,
        'order'       => pricelist_wc_option::Get('name')->order+.1,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_enum_option([
        'name'        => 'attachment',
        'possibilities' => ['end', 'inline', 'false'],
        'default'     => 'end',
        'display'     => 'Attachments',
        'description' => 'If and how attachments are displayed. With the `inline` option they are displayed in the table immediately below the product they are attached to, whereas with the `end` option each grouped product is displayed in a table at the end of the pricelist (after all categories).',
        'pro'         => true,
        'order'       => pricelist_wc_option::Get('output')->order+.1,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_bool_option([
        'name'        => 'local_images',
        'default'     => 'false',
        'display'     => 'Omit External Images',
        'description' => 'Whether to omit external images to speed up price list PDF generation.',
        'tooltip'     => 'Product or category images that are located on external websites (i.e. not on this website\'s server) need to be (down)loaded every time a price list PDF is generated, which can greatly slow down the process. Omit these external images from the price list PDFs to speed up their generation. Loading these images will display them in the generated PDFs if they still exist in the linked location.',
        'pro'         => true,
        'order'       => pricelist_wc_option::Get('attachment')->order+.1,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_bool_option([
        'name'        => 'output_toggle',
        'default'     => 'true',
        'display'     => 'Togglable Output',
        'description' => 'Whether the user should be able to toggle between the output types.',
        'pro'         => true,
        'order'       => pricelist_wc_option::Get('local_images')->order+.1,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_int_option([
        'name'        => 'description_limit',
        'default'     => 55,
        'display'     => '(Short) Description Word Limit',
        'description' => 'Description word limit.',
        'tooltip'     => 'The maximum number of words the product description and short description can have. Does not apply to category descriptions.',
        'min'         => 0,
        'max'         => 999,
        'pro'         => true,
        'order'       => pricelist_wc_option::Get('table_color')->order+.1,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_bool_option([
        'name'        => 'generate_url',
        'default'     => 'false',
        'display'     => false,
        'description' => 'Whether to generate a plain text url instead of a download button for PDF output.',
        'tooltip'     => '',
        'pro'         => true,
        'order'       => 0,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_bool_option([
        'name'        => 'sku',
        'default'     => 'false',
        'display'     => ['true' => 'Show SKU', 'false' => 'Hide SKU'],
        'description' => 'Whether the product SKU should be displayed in the table for each product.',
        'pro'         => true,
        'order'       => pricelist_wc_option::Get('date1')->order+.1,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_bool_option([
        'name'        => 'stock_status',
        'default'     => 'false',
        'display'     => ['true' => 'Show Status', 'false' => 'Hide Status'],
        'description' => 'Whether the product stock status should be displayed in the table for each product.',
        'pro'         => true,
        'order'       => pricelist_wc_option::Get('sku')->order+.1,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_bool_option([
        'name'        => 'stock_quantity',
        'default'     => 'false',
        'display'     => ['true' => 'Show Quantity', 'false' => 'Hide Quantity'],
        'description' => 'Whether the products stock quantity should be displayed in the table for each product.',
        'pro'         => true,
        'order'       => pricelist_wc_option::Get('stock_status')->order+.1,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_option_group([
        'name'        => 'stock_options',
        'display'     => 'Stock Options',
        'tooltip'     => 'What stock data should be displayed: the SKU, stock status and stock quantity.',
        'pro'         => true,
        'order'       => pricelist_wc_option::Get('date')->order+.1,
    ], [pricelist_wc_option::Get('sku'), pricelist_wc_option::Get('stock_status'), pricelist_wc_option::Get('stock_quantity')]));
    pricelist_wc_option::Register(new pricelist_wc_array_option([
        'name'        => 'socials',
        'possibilities' => ['twitter', 'facebook', 'tumblr', 'email', 'pinterest', 'linkedin', 'reddit', 'buffer', 'diggit', 'stumbleupon', 'vk', 'yummly'],
        'default'     => [],
        'display'     => 'Share Icons',
        'description' => 'Which social media buttons should be displayed and their order.',
        'tooltip'     => 'Select which social media share icons should be displayed and drag them in the desirable order.',
        'pro'         => true,
        'order'       => pricelist_wc_option::Get('stock_quantity')->order+.1,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_array_option([
        'name'        => 'category_include',
        'default'     => [],
        'display'     => 'Categories to include',
        'description' => 'Which categories should be included in the pricelist.',
        'tooltip'     => 'Select which categories should be displayed and drag them in the desirable order. Unless explicitly excluded, subcategories will also be included.',
        'context'     => 'categories',
        'pro'         => true,
        'order'       => pricelist_wc_option::Get('socials')->order+.1,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_array_option([
        'name'        => 'category_exclude',
        'default'     => [],
        'display'     => 'Categories to exclude',
        'description' => 'Which categories should be excluded from the pricelist. Unless explicitly included, subcategories will also be excluded.',
        'tooltip'     => 'Select which categories should not be displayed.',
        'context'     => 'categories',
        'pro'         => true,
        'order'       => pricelist_wc_option::Get('category_include')->order+.1,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_array_option([
        'name'        => 'category_order',
        'default'     => [],
        'display'     => 'Order categories',
        'description' => 'The order in which the categories should be displayed.',
        'tooltip'     => '',
        'context'     => 'categories',
        'pro'         => true,
        'order'       => pricelist_wc_option::Get('category_exclude')->order+.1,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_text_option([
        'name'        => 'download_pdf',
        'display'     => 'Download PDF Button',
        'tooltip'     => 'Name of the button to download the PDF.',
        'description' => 'Download PDF button.',
        'default'     => 'Download PDF',
        'context'     => 'languages',
        'pro'         => true,
        'order'       => 100,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_text_option([
        'name'        => 'attachment_checkbox',
        'display'     => 'Attachment Checkbox',
        'tooltip'     => 'Description for the attachment checkbox displayed at the Download PDF Button. Leave empty to omit possibility to include/exclude attachments.',
        'description' => 'Attachment checkbox description.',
        'default'     => 'Generate including attachments.',
        'context'     => 'languages',
        'pro'         => true,
        'order'       => 101,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_text_option([
        'name'        => 'grouped_product_elements',
        'display'     => 'Grouped Product Elements',
        'tooltip'     => 'How to call products grouped under a product group (\'Grouped Product\' in WooCommerce language).',
        'description' => 'How to call products grouped under a product group (\'Grouped Product\' in WooCommerce language).',
        'default'     => 'Grouped Products',
        'context'     => 'languages',
        'pro'         => true,
        'order'       => 102,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_text_option([
        'name'        => 'variable_product_variations',
        'display'     => 'Variable Product Variations',
        'tooltip'     => 'How to call variations of Variable Products.',
        'description' => 'How to call variations of Variable Products.',
        'default'     => 'Variations',
        'context'     => 'languages',
        'pro'         => true,
        'order'       => pricelist_wc_option::Get('grouped_product_elements')->order+.1,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_text_option([
        'name'        => 'attachment_show',
        'display'     => 'Show Attachments Button',
        'tooltip'     => 'Name of the button to show the attachments of grouped and variable products.',
        'description' => 'Show attachment button.',
        'default'     => 'Show Attachments',
        'context'     => 'languages',
        'pro'         => true,
        'order'       => 103,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_text_option([
        'name'        => 'attachment_hide',
        'display'     => 'Hide Attachments Button',
        'tooltip'     => 'Name of the button to hide the attachments of grouped and variable products.',
        'description' => 'Hide attachment button.',
        'default'     => 'Hide Attachments',
        'context'     => 'languages',
        'pro'         => true,
        'order'       => pricelist_wc_option::Get('attachment_show')->order+.1,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_text_option([
        'name'        => 'toggle_to_html',
        'display'     => 'Toggle to HTML Button',
        'tooltip'     => 'When the Togglable Output option is enabled, this is the text shown on the toggle button when currently the download/view PDF button is shown and users want to toggle to showing the price list as an HTML table.',
        'description' => 'When the Togglable Output option is enabled, this is the text shown on the toggle button when currently the download/view PDF button is shown and users want to toggle to showing the price list as an HTML table.',
        'default'     => 'Show Price List Table',
        'context'     => 'languages',
        'pro'         => true,
        'order'       => 104,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_text_option([
        'name'        => 'toggle_to_pdf',
        'display'     => 'Toggle to PDF Button',
        'tooltip'     => 'When the Togglable Output option is enabled, this is the text shown on the toggle button when the pricelist is currently displayed as HTML and should toggle to displaying the download/view PDF button.',
        'description' => 'When the Togglable Output option is enabled, this is the text shown on the toggle button when the pricelist is currently displayed as HTML and should toggle to displaying the download/view PDF button.',
        'default'     => 'Create Price List PDF',
        'context'     => 'languages',
        'pro'         => true,
        'order'       => pricelist_wc_option::Get('toggle_to_html')->order+.1,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_text_option([
        'name'        => 'instock',
        'display'     => 'In stock',
        'tooltip'     => 'Words to describe that a product is in stock.',
        'description' => 'Words to describe that a product is in stock.',
        'default'     => 'In stock',
        'context'     => 'languages',
        'pro'         => true,
        'order'       => 105,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_text_option([
        'name'        => 'outofstock',
        'display'     => 'Out of stock',
        'tooltip'     => 'Words to describe that a product is out of stock.',
        'description' => 'Words to describe that a product is out of stock.',
        'default'     => 'Out of stock',
        'context'     => 'languages',
        'pro'         => true,
        'order'       => pricelist_wc_option::Get('instock')->order+.1,
    ]));
    pricelist_wc_option::Register(new pricelist_wc_text_option([
        'name'        => 'onbackorder',
        'display'     => 'On backorder',
        'tooltip'     => 'Words to describe that a product is on backorder.',
        'description' => 'Words to describe that a product is on backorder.',
        'default'     => 'On backorder',
        'context'     => 'languages',
        'pro'         => true,
        'order'       => pricelist_wc_option::Get('outofstock')->order+.1,
    ]));
}
?>