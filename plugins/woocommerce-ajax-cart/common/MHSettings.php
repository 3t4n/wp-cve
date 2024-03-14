<?php
if ( !class_exists('MHSettings') ) {
    class MHSettings {
        private $pluginAlias;
        private $pluginTitle;
        private $pluginAbbrev;

        /**
         * @var MHCommon
         */
        private $common;

        public function __construct($pluginAlias, $pluginAbbrev, $pluginTitle, $pluginBaseFile, $common) {
            $this->pluginAlias = $pluginAlias;
            $this->pluginAbbrev = $pluginAbbrev;
            $this->pluginTitle = $pluginTitle;
            $this->pluginBaseFile = $pluginBaseFile;
            $this->common = $common;

            // do hooks and filters
            add_filter('plugin_action_links_' . plugin_basename($pluginBaseFile), array($this, 'settings_plugin_links') );
            add_action('admin_menu', array($this, 'settings_menu'), 59 );
            add_filter("mh_{$pluginAbbrev}_setting_value", array($this, 'get_option'));
            add_filter("mh_{$pluginAbbrev}_all_options", array($this, 'all_options'));
        }

        public function settings_menu() {
            $pluginAbbrev = $this->pluginAbbrev;
            $title = apply_filters("mh_{$pluginAbbrev}_menu_title", str_replace('WooCommerce ', '', $this->common->getPluginTitle()));
            $parentMenu = apply_filters("mh_{$pluginAbbrev}_parent_menu", 'woocommerce');
            $menuSlug = apply_filters("mh_{$pluginAbbrev}_menu_slug", $this->pluginAlias);

            add_submenu_page(
                $parentMenu,
                $title,
                $title,
                'manage_woocommerce',
                $menuSlug,
                array($this, 'settings_page')
            );
        }

        public function settings_plugin_links($links) {
            $action_links = array();

            if ( $this->display_premium_tab() ) {
                $action_links['get_pro'] = sprintf('<a href="%s" style="color: #46b450; font-weight: bold;">%s</a>', $this->tab_url('tab-buy.php'), esc_html__('Go Pro', 'woocommerce'));
            }

            $action_links['settings'] = sprintf('<a href="%s">%s</a>', $this->admin_url(), esc_html__('Settings', 'woocommerce'));
    
            return array_merge( $action_links, $links );
        }
        
        public function settings_save() {
            $opts = $this->merge_settings_post();
    
            // treat boolean values
            foreach ( $this->default_settings() as $name => $val ) {
                if ( isset($opts[$name]) && isset($_POST[$name]) && in_array($val, array('yes', 'no'))) {
                    $opts[$name] = ( !empty($_POST[$name]) ? 'yes' : 'no' );
                }
            }

            $opts = apply_filters("mh_{$this->pluginAbbrev}_saving_options", $opts);
            update_option($this->pluginAbbrev . '_settings', $opts);
        }
        
        public function merge_settings_post() {
            $defaultSettings = $this->default_settings();
            $allOptions = $this->all_options();
    
            foreach ( $_POST as $key => $val ) {
                if ( in_array($key, array_keys($defaultSettings)) ) {
                    if ( is_numeric($defaultSettings[$key]) ) {
                        $allOptions[$key] = (int) sanitize_text_field($val);
                    }
                    else if ( is_array($val) ) {
                        $allOptions[$key] = $val;
                    }
                    else {
                        $allOptions[$key] = sanitize_text_field($val);
                    }
                }
            }
    
            return $allOptions;
        }
        
        public function save_option($option, $value) {
            $opts = $this->all_options();
            $opts[$option] = $value;
    
            update_option($this->pluginAbbrev . '_settings', $opts);
        }
        
        public function all_options() {
            return array_merge($this->default_settings(),
                               get_option($this->pluginAbbrev . '_settings',
                               array()));
        }
        
        public function get_option($name, $default = null) {
            $options = $this->all_options();
            $value = isset($options[$name]) ? $options[$name] : $default;
    
            return $value;
        }
    
        public function admin_url() {
            if ( !empty($_REQUEST['page']) ) {
                $page = sanitize_text_field($_REQUEST['page']);
            }
            
            else {
                $page = apply_filters("mh_{$this->pluginAbbrev}_menu_slug", $this->pluginAlias);
            }

            return admin_url('admin.php?page=' . $page);
        }
        
        public function admin_url_current() {
            return $this->tab_url($this->active_tab());
        }
    
        public function tab_url($tab) {
            return $this->admin_url().'&tab='.$tab;
        }

        public function tab_premium_url() {
            return $this->tab_url('tab-buy.php');
        }
    
        public function setting_tab($tab, $label) {
            $class = ($tab == $this->active_tab()) ? 'nav-tab-active' : '';
            $tab = '<a href="'. $this->tab_url($tab) . '" class="nav-tab '.$class.'">'.$label.'</a>';
    
            return $tab;
        }
    
        public function active_tab() {
            $tab = basename(sanitize_file_name( isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : null ));
    
            if ( preg_match('/^tab-(.*)$/', $tab) ) {
                return $tab;
            }
    
            return 'tab-general.php';
        }
        
        public function admin_tab() {
            return esc_html__(str_replace('.php', '', $this->active_tab()));
        }

        public function normalize_tabname($tab) {
            return str_replace(array('tab-', '.php'), '', $tab);
        }

        public function display_premium_tab() {
            return ( !$this->common->isPremiumVersion() && $this->has_premium_features() );
        }
    
        public function settings_page() {
            if ( !current_user_can( 'manage_woocommerce' ) ) {
                exit("invalid permissions");
            }
    
            // Save settings if data has been posted
            if ( ! empty( $_POST ) && check_admin_referer('mh_nonce') ) {
                $btnClick = sanitize_text_field($_POST['save']);

                switch( $btnClick ) {
                    case esc_html__( 'Save settings' ):
                        $this->settings_save();
                    break;
                    case esc_html__( 'Reset all settings' ):
                        update_option($this->pluginAbbrev . '_settings', $this->default_settings());
                    break;
                    default:
                        do_action("mh_{$this->pluginAbbrev}_trigger_save", $btnClick);
                    break;
                }
            }
            
            $this->enqueue_admin_scripts();

            $title = esc_html__($this->common->getPluginTitle());
            $displayPremiumTab = $this->display_premium_tab();

            ?>
            <div class="wrap">
                <div class="icon32">
                <br />
                </div>
                <h2 class="nav-tab-wrapper">
                    <?php echo $title; ?>
                </h2>
                <?php if ( $displayPremiumTab ): ?>
                    <?php $this->premium_alert_box(); ?>
                <?php endif; ?>
                <nav class="nav-tab-wrapper woo-nav-tab-wrapper">
                    <?php foreach ( $this->build_tabs() as $name => $label ): ?>
                        <?php echo $this->setting_tab($name, $label) ?>
                    <?php endforeach; ?>
                    <?php if ( $displayPremiumTab ): ?>
                        <?php echo $this->setting_tab(
                            'tab-buy.php',
                            '<span style="color: #46b450;">' . esc_html__('Get PRO') . '</span>'
                        ); ?>
                    <?php endif; ?>
                </nav>
                <div class="mh-settings-page">
                    <?php $this->render_active_tab() ?>
                </div>
            </div>
            <?php
        }
        
        public function render_active_tab() {
            $tab = $this->active_tab();

            if ( $tab == 'tab-buy.php' ) {
                $this->render_buy_tab();
                return;
            }

            $settings = array();

            foreach ( $this->get_plugin_settings_definitions() as $name => $conf ) {
                $confTab = 'tab-' . strtolower($conf['tab']) . '.php';

                if ( $tab == $confTab ) {
                    $settings[$name] = $conf;
                }
            }

            $normalizedTab = $this->normalize_tabname($tab);

            ?>
            <?php $this->form_header() ?>
            <?php foreach ( $settings as $name => $conf ): ?>
                <div class="conf">
                    <?php if ( empty($conf['depends_on']) ): ?>
                        <?php $this->render_field($settings, $name, $conf) ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            <?php do_action("mh_{$this->pluginAbbrev}_after_tab", $normalizedTab); ?>
            <?php $this->form_footer() ?>
            <?php
        }

        public function render_field($settings, $name, $conf) {
            switch($conf['type']) {
                case 'checkbox':
                    $dependants = $this->get_dependant_fields($settings, $name);
                    
                    if ( !empty($dependants) ) {
                        $this->checkbox($name, $conf['label'], 'check-subconf');
                        $this->render_dependant_fields($settings, $dependants);
                    }
                    else {
                        $this->checkbox($name, $conf['label']);
                    }
                    break;

                case 'number':
                    $this->number($name, $conf['label'], $conf['min'], $conf['max']);
                    break;

                case 'text':
                    $size = !empty($conf['size']) ? $conf['size'] : 10;
                    $this->text($name, $conf['label'], $size);
                    break;

                case 'select':
                    $this->select($name, $conf['label'], $conf['options']);
                    break;

                case 'multiselect':
                    $this->multiselect($name, $conf['label'], $conf['options']);
                    break;

                case 'multicheckbox':
                    $this->multicheckbox($name, $conf['label'], $conf['options']);
                    break;

                case 'color':
                    $this->color($name, $conf['label']);
                    break;

                case 'date':
                    $this->date($name, $conf['label']);
                    break;
            }
        }

        public function get_dependant_fields($settings, $name) {
            $dependants = array();

            foreach ( $settings as $_name => $conf ) {
                if ( !empty($conf['depends_on']) && $conf['depends_on'] == $name ) {
                    $dependants[$_name] = $conf;
                }
            }

            return $dependants;
        }

        public function render_dependant_fields($settings, $dependants) {
            ?>
            <div class="mh-subconf">
                <?php foreach ( $dependants as $name => $conf ): ?>
                    <?php $this->render_field($settings, $name, $conf); ?>
                    <br/>
                <?php endforeach; ?>
            </div>
            <?php
        }

        public function build_tabs() {
            $arrSettings = $this->get_plugin_settings_definitions();
            $tabs = array();

            foreach ( $arrSettings as $conf ) {
                $name = 'tab-' . strtolower($conf['tab']) . '.php';
                $tabs[$name] = $conf['tab'];
            }

            return $tabs;
        }
    
        public function default_settings() {
            $arrSettings = $this->get_plugin_settings_definitions();
            $arr = array();

            foreach ( $arrSettings as $key => $conf ) {
                $arr[ $key ] = $conf['default'];
            }
    
            return $arr;
        }

        public function get_plugin_settings_definitions() {
            return apply_filters("mh_{$this->pluginAbbrev}_settings", array());
        }
        
        public function enqueue_admin_scripts() {
            wp_enqueue_style( $this->pluginAbbrev . '_admin_style', plugins_url('common/assets/admin-settings.css', $this->pluginBaseFile));
            wp_enqueue_script( $this->pluginAbbrev . '_admin_script', plugins_url('common/assets/admin-settings.js', $this->pluginBaseFile));
    
            wp_localize_script( $this->pluginAbbrev . '_admin_script', 'tab_current', array($this->admin_tab()));
        }
        
        public function form_header() {
            ?>
            <form method="post" id="mainform" action="" enctype="multipart/form-data">
                <?php wp_nonce_field('mh_nonce'); ?>
            <?php
        }
        
        public function form_footer() {
            ?>
                <hr/>
                <input name="save" class="button-primary" type="submit" value="<?php echo esc_html__( 'Save settings' ); ?>" />
                <?php do_action("mh_{$this->pluginAbbrev}_admin_buttons"); ?>
                <input name="save" class="button" type="submit"
                        value="<?php echo esc_html__( 'Reset all settings' ); ?>"
                        onclick="return confirm('<?php echo esc_html__('Are you sure?') ?>')"/>
            </form>
            <?php
        }

        private function get_rate_recommendation() {
            $pluginUrl = 'https://wordpress.org/support/plugin/' . $this->pluginAlias . '/reviews/#new-post';

            $text = sprintf(__('If you liked this plugin, please help us <a href="%s" target="_blank">giving a 5-star rate</a> on WordPress.org :)'), $pluginUrl);

            $text = wp_kses($text, array(
                'a' => array('href' => array(), 'target' => array())
            ));

            return $text;
        }

        public function get_premium_url() {
            return apply_filters("mh_{$this->pluginAbbrev}_premium_url", '');
        }

        public function has_premium_features() {
            return ( strlen($this->get_premium_url()) > 0 );
        }

        public function get_premium_notice() {
            $text = sprintf(
                __("You're using a limited version of %s. For more features and better support, please <a href='%s'>consider the premium version</a>."),
                $this->pluginTitle,
                $this->tab_premium_url()
            );

            $text = wp_kses($text, array(
                'a' => array('href' => array())
            ));

            return $text;
        }

        private function premium_alert_box() {
            if ( $this->active_tab() != 'tab-buy.php' ) {
                $imgAlert = plugins_url('common/assets/alert.png', $this->pluginBaseFile);

                ?>
                <div class="mh-update-message notice inline notice-warning notice-alt">
                    <p>
                        <img src="<?php echo $imgAlert; ?>"/>
                        <?php echo $this->get_premium_notice(); ?>
                    </p>
                </div>
                <?php
            }
        }

        public function list_premium_features() {
            $readmeFile = plugin_dir_path( $this->pluginBaseFile ) . '/readme.txt';
        
            if ( !file_exists($readmeFile) ) {
                return array();
            }
        
            $readme = file_get_contents($readmeFile);
        
            preg_match('/Premium version features:\n(.*)\n\n/isU', $readme, $match);
        
            if ( !empty($match[1]) ) {
                $features = explode("\n", $match[1]);
                return array_filter($features);
            }

            return array();
        }

        public function render_premium_features() {
            $features = $this->list_premium_features();
            $imgYT = plugins_url('common/assets/youtube.png', $this->pluginBaseFile);

            ?>
            <?php foreach ( $features as $feature ): ?>
                <li>
                    <?php echo $this->format_feature_name($feature); ?>
                    <?php $urlDemo = $this->get_feature_youtube_demo($feature);
                          if ( !empty($urlDemo)): ?>
                            &nbsp;
                            <a href="<?php echo $urlDemo; ?>" title="View demonstration video" class="mh-feature-video" target="_blank">
                                View demo
                                <img src="<?php echo $imgYT; ?>"/>
                            </a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
            <?php
        }

        public function format_feature_name($feature) {
            $feature = str_replace('* ', '', $feature);
            return preg_replace('/\[view demo\](.*)/', '', $feature);
        }

        public function get_feature_youtube_demo($feature) {
            preg_match('/\[view demo\](.*)/', $feature, $fmatch);
    
            if ( !empty($fmatch[1]) ) {
                $urlDemo = trim(str_replace(array('(', ')'), '', $fmatch[1]));
                $urlDemo = filter_var($urlDemo, FILTER_VALIDATE_URL);

                return $urlDemo;
            }

            return null;
        }

        private function parse_field_options($options) {
            if ( is_callable($options) ) {
                return call_user_func($options);
            }

            return $options;
        }

        public function render_buy_tab() {
            ?>
            <div class="mh-buy-div">
                <h1>
                    <?php echo esc_html__($this->pluginTitle) . ' PRO'; ?>
                </h1>
                <h3>
                    <?php echo esc_html__('Features included in PRO version') ?>:
                </h3>
                <ul class="mh-premium-features">
                    <?php $this->render_premium_features(); ?>
                </ul>
                <h1>
                    <a href="<?php echo $this->get_premium_url(); ?>" target="_blank" style="">
                        <?php echo esc_html__('Buy on Gumroad') ?> &rarr;
                    </a>
                </h1>
            </div>
            <?php
        }
        
        public function checkbox($name, $label, $class = null) {
            $value = $this->get_option($name);
            
            ?>
            <input type="hidden" name="<?php echo $name ?>" value="0" />
            <label>
                <input type="checkbox"
                       <?php if ( $value === 'yes' ): ?>checked<?php endif; ?>
                       class="<?php echo $class ?>"
                       name="<?php echo $name ?>">
                <?php echo esc_html__($label); ?>
            </label>
            <?php
        }
        
        public function text($name, $label, $size = null) {
            $value = $this->get_option($name);
            
            ?>
            <?php echo esc_html__($label); ?>:
            <input type="text"
                    value="<?php echo $value; ?>"
                    size="<?php echo $size; ?>"
                    name="<?php echo $name; ?>">
            <?php
        }
        
        public function number($name, $label, $min, $max) {
            $value = $this->get_option($name);
            
            ?>
            <?php echo esc_html__($label); ?>:
            <input type="number"
                    value="<?php echo $value; ?>"
                    name="<?php echo $name; ?>"
                    min="<?php echo $min; ?>"
                    max="<?php echo $max; ?>">
            <?php
        }
        
        public function color($name, $label) {
            $value = $this->get_option($name);
            
            ?>
            <?php echo esc_html__($label); ?>:
            <input type="color"
                    value="<?php echo $value; ?>"
                    name="<?php echo $name; ?>">
            <?php
        }

        public function date($name, $label) {
            $value = $this->get_option($name);
            
            ?>
            <?php echo esc_html__($label); ?>:
            <input type="date"
                    value="<?php echo $value; ?>"
                    name="<?php echo $name; ?>">
            <?php
        }

        public function select($name, $label, $options) {
            $value = $this->get_option($name);
            
            ?>
            <?php echo esc_html__($label); ?>:
            <select name="<?php echo $name ?>">
                <?php foreach ( (array) $options as $optVal => $optLabel ): ?>
                    <option <?php if ( $value == $optVal ): ?>selected<?php endif; ?>
                            value="<?php echo $optVal ?>"><?php echo $optLabel; ?></option>
                <?php endforeach; ?>
            </select>
            <?php
        }

        public function multiselect($name, $label, $options) {
            $values = (array) $this->get_option($name, array());
            $options = $this->parse_field_options($options);
            
            ?>
            <br/>
            <?php echo esc_html__($label); ?>:
            <br/>
            <input type="hidden" name="<?php echo $name; ?>" value=""/>
            <select multiple="multiple" name="<?php echo $name; ?>[]">
                <?php
                foreach ($options as $key => $label ) { ?>
                <?php $selected = in_array( $key, $values ) ? ' selected="selected" ' : ''; ?>
                    <option value="<?php echo $key; ?>" <?php echo $selected; ?> >
                        <?php echo $label; ?>
                    </option>
                <?php } //endforeach ?>
            </select>
            <?php
        }

        function multicheckbox($name, $label, $options ) {
            $values = (array) $this->get_option($name, array());

            ?>
            <?php echo esc_html__( $label ); ?>: &nbsp;
            <?php foreach ( $options as $key => $value ): ?>
                <label><input
                            name="<?php echo esc_attr($name); ?>[]"
                            value="<?php echo esc_attr( $key ); ?>"
                            <?php if ( in_array( $key, $values ) ): ?>checked="checked"<?php endif; ?>
                            type="checkbox"/><?php echo esc_html( $value ); ?></label>
                &nbsp;&nbsp;
            <?php endforeach; ?>
            <?php
        }
    }
}
