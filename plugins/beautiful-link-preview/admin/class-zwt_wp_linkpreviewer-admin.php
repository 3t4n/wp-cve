<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://zeitwesentech.com
 * @since      1.0.0
 *
 * @package    Zwt_wp_linkpreviewer
 * @subpackage Zwt_wp_linkpreviewer/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Zwt_wp_linkpreviewer
 * @subpackage Zwt_wp_linkpreviewer/admin
 * @author     zeitwesentech <sayhi@zeitwesentech.com>
 */
class Zwt_wp_linkpreviewer_Admin
{
    const TAB_INTRO = "intro";
    const TAB_SETTINGS = "settings";
    const TAB_LINKPREVIEWS = "link_previews";
    const CLASS_NAV_TAB_ACTIVE = 'nav-tab-active';
    const SLUG_SETTINGS_PAGE = 'settingsTab';
    const SLUG_SETTINGS_SECTION = 'zwt_bl_section';
    const LAYOUT_COMPACT = "compact";
    const LAYOUT_FULL = "full";
    const TARGET_BLANK = "_blank";
    const TARGET_SELF = "_self";
    const TARGET_PARENT = "_parent";
    const TARGET_TOP = "_top";

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Zwt_wp_linkpreviewer_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Zwt_wp_linkpreviewer_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/zwt_wp_linkpreviewer-admin.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Zwt_wp_linkpreviewer_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Zwt_wp_linkpreviewer_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/zwt_wp_linkpreviewer-admin.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->plugin_name . '_blockSettings', plugin_dir_url(__FILE__) . 'js/zwt_wp_linkpreviewer-globals.js', array(), $this->version, false);
        wp_localize_script($this->plugin_name . '_blockSettings', 'zwt', array(
            'imgCompactSizeWidth' => Zwt_wp_linkpreviewer_Constants::$FETCH_IMG_COMPACT_SIZE,
            'imgFullSizeWidth' => Zwt_wp_linkpreviewer_Constants::$FETCH_IMG_FULL_SIZE,
            'layout' => Zwt_wp_linkpreviewer_Utils::getOptionValue(Zwt_wp_linkpreviewer_Constants::$KEY_LAYOUT),
            'max_desc_chars' => Zwt_wp_linkpreviewer_Utils::getOptionValue(Zwt_wp_linkpreviewer_Constants::$KEY_MAX_DESC_CHARS),
            'max_title_chars' => Zwt_wp_linkpreviewer_Utils::getOptionValue(Zwt_wp_linkpreviewer_Constants::$KEY_MAX_TITLE_CHARS),
            'pluginStatus' => (Zwt_wp_linkpreviewer_Utils::getOptionValue(Zwt_wp_linkpreviewer_Constants::$KEY_ENABLED) == 1) ? 'enabled' : 'disabled',
            'rel' => $this->getOrDefault(Zwt_wp_linkpreviewer_Constants::$KEY_REL, Zwt_wp_linkpreviewer_Constants::$OPTION_DEFAULT_REL),
            'restNamespace' => Zwt_wp_linkpreviewer_Constants::$REST_NAMESPACE,
            'styleMaxWidth' => 'maxWidth',
            'target' => Zwt_wp_linkpreviewer_Utils::getOptionValue(Zwt_wp_linkpreviewer_Constants::$KEY_TARGET),
            'targetOptions' => array(
                self::TARGET_BLANK,
                self::TARGET_SELF,
                self::TARGET_PARENT,
                self::TARGET_TOP,
            )
        ));
    }


    public function init_admin_menu()
    {
        require_once plugin_dir_path(__FILE__) . 'class-zwt_wp_linkpreviewer-linktable.php';
        require_once plugin_dir_path(__FILE__) . '../includes/class-zwt_wp_linkpreviewer-contentfetcher.php';

        add_options_page(Zwt_wp_linkpreviewer_Constants::$TEXT_PLUGIN_NAME, Zwt_wp_linkpreviewer_Constants::$TEXT_PLUGIN_NAME, 'manage_options', Zwt_wp_linkpreviewer_Constants::$SETTINGS_SLUG, array($this, 'zwt_wp_link_previewer_options_page'));
        add_filter('plugin_action_links', array($this, 'add_plugin_action_settings_link'), 10, 3);
        add_action('admin_init', array($this, 'zwt_wp_link_previewer_settings_init'));
        add_action('admin_head', array($this, 'style_links_table'));
    }

    public function maybe_register_gutenberg_block() {
        if (Zwt_wp_linkpreviewer_Utils::getOptionValue(Zwt_wp_linkpreviewer_Constants::$KEY_ENABLED) == 1 && function_exists('register_block_type_from_metadata')) {
            register_block_type_from_metadata( __DIR__ );
        }
    }

    public function style_links_table()
    {
        echo '<style type="text/css">';
        echo '.column-img_compact_len { width: 50px; padding: 5px; }';
        echo '</style>';
    }

    public function add_plugin_action_settings_link($links, $file)
    {
        if (strpos($file, 'zwt_wp_linkpreview.php') !== false) {
            $settingsLabel = esc_html(Zwt_wp_linkpreviewer_Constants::$ADMIN_TEXT_TAB_SETTINGS);
            $settingsLink = Zwt_wp_linkpreviewer_Constants::$SETTINGS_BASE . Zwt_wp_linkpreviewer_Constants::$SETTINGS_SLUG;
            $new_links = array(
                'doc' => "<a href=$settingsLink>$settingsLabel</a>"
            );
            $links = array_merge($links, $new_links);
        }
        return $links;
    }

    public function zwt_wp_link_previewer_options_page()
    {
        $tab0_key = self::TAB_INTRO;
        $tab1_key = self::TAB_SETTINGS;
        $tab2_key = self::TAB_LINKPREVIEWS;
        $active_tab = $this->sanitize_tab(isset($_GET['tab']) ? sanitize_title($_GET['tab']) : $tab0_key);
        echo $this->show_disabled_warning();

        echo "<div class=\"wrap\">";
        echo '<h1>' . esc_html(Zwt_wp_linkpreviewer_Constants::$ADMIN_TEXT_TAB_SETTINGS) . ' &rsaquo; ' . Zwt_wp_linkpreviewer_Constants::$TEXT_PLUGIN_NAME . '</h1>';
        echo "  <h2 class=\"nav-tab-wrapper\">";

        $lnk = Zwt_wp_linkpreviewer_Constants::$SETTINGS_BASE . Zwt_wp_linkpreviewer_Constants::$SETTINGS_SLUG;

        $tab_zero_active = $active_tab == $tab0_key ? self::CLASS_NAV_TAB_ACTIVE : '';
        $tab_one_active = $active_tab == $tab1_key ? self::CLASS_NAV_TAB_ACTIVE : '';
        $tab_two_active = $active_tab == $tab2_key ? self::CLASS_NAV_TAB_ACTIVE : '';

        echo "      <a href=\"$lnk&tab=$tab0_key\" class=\"nav-tab $tab_zero_active\">" . Zwt_wp_linkpreviewer_Constants::$ADMIN_TEXT_TAB_INTRO . "</a>";
        echo "      <a href=\"$lnk&tab=$tab1_key\" class=\"nav-tab $tab_one_active\">" . Zwt_wp_linkpreviewer_Constants::$ADMIN_TEXT_TAB_SETTINGS . "</a>";
        echo "      <a href=\"$lnk&tab=$tab2_key\" class=\"nav-tab $tab_two_active\">" . Zwt_wp_linkpreviewer_Constants::$ADMIN_TEXT_TAB_LINKS . "</a>";
        echo "  </h2>";

        if ($active_tab == $tab0_key) {
            echo $this->handle_tab_introduction();
        } else if ($active_tab == $tab1_key) {
            $this->handleTabSettings();
        } else if ($active_tab == $tab2_key) {
            $this->handleTabLinks($tab2_key);
        }
        echo "</div >";
    }

    private function handle_tab_introduction(){
        $file_content = file_get_contents(dirname(__FILE__) . "/introduction.html");
        $img_banner = plugins_url("/img/banner-772x250.png", __FILE__);
        $url_wp_plugin_directory = Zwt_wp_linkpreviewer_Constants::$ADMIN_TEXT_WP_PLUGIN_DIR_URL;
        $url_settings = Zwt_wp_linkpreviewer_Constants::$SETTINGS_BASE.Zwt_wp_linkpreviewer_Constants::$SETTINGS_SLUG."&tab=".self::TAB_SETTINGS;
        $url_link_previews = Zwt_wp_linkpreviewer_Constants::$SETTINGS_BASE.Zwt_wp_linkpreviewer_Constants::$SETTINGS_SLUG."&tab=".self::TAB_LINKPREVIEWS;
        return sprintf($file_content, $img_banner, "max-width:100%;", $url_wp_plugin_directory, $url_settings, $url_link_previews, $url_wp_plugin_directory, Zwt_wp_linkpreviewer_Constants::$PLUGIN_LINK);
    }

    private function sanitize_tab($tab)
    {
        return $tab == self::TAB_INTRO || $tab == self::TAB_SETTINGS || $tab == self::TAB_LINKPREVIEWS ? $tab : self::TAB_INTRO;
    }

    private function handleTabSettings()
    {
        echo "<div class=\"zwt-wp-lnk-prev-intro-container\">";
        echo "  <form method=\"post\" action=\"options.php\">";
        settings_fields(self::SLUG_SETTINGS_PAGE);
        do_settings_sections(self::SLUG_SETTINGS_PAGE);
        submit_button();
        echo "</form >";
        echo "</div>";
    }

    private function handleTabLinks($tab2_key)
    {
        $action = $this->sanitize_action(isset($_GET["action"]) ? sanitize_title($_GET["action"]) : null);
        $item_hash = isset($_GET["item"]) ? sanitize_title($_GET["item"]) : null;
        $dbInstance = new Zwt_wp_linkpreviewer_Db();
        if ($item_hash) {
            if ($action == "refresh") {
                $entry = $dbInstance->getEntryForHash($item_hash);
                //first remove item_hash
                if ($entry && $dbInstance->deleteEntry($item_hash)) {
                    //now fetch again
                    Zwt_wp_linkpreviewer_Utils::fetchUrl($dbInstance, $entry->url);
                    echo Zwt_wp_linkpreviewer_Utils::wrap_notice(sprintf(Zwt_wp_linkpreviewer_Constants::$ADMIN_TEXT_TAB_LINKS_ACTION_REFRESHED, $entry->url));
                }
            } else if ($action == "delete") {
                $entry = $dbInstance->getEntryForHash($item_hash);
                if (isset($entry) && $dbInstance->deleteEntry($item_hash)) {
                    echo Zwt_wp_linkpreviewer_Utils::wrap_notice(sprintf(Zwt_wp_linkpreviewer_Constants::$ADMIN_TEXT_TAB_LINKS_ACTION_DELETED, $entry->url));
                }
            }
        }
        $myListTable = new Zwt_wp_linkpreviewer_Linktable($dbInstance, $tab2_key);
        $myListTable->prepare_items();
        $myListTable->display();
    }

    private function sanitize_action($action)
    {
        return $action == "refresh" || $action == "delete" ? $action : null;
    }

    public function zwt_wp_link_previewer_settings_init()
    {
        register_setting(self::SLUG_SETTINGS_PAGE, Zwt_wp_linkpreviewer_Constants::$OPTION_KEY_SETTINGS, array($this, 'sanitize_callback'));

        add_settings_section(
            self::SLUG_SETTINGS_SECTION,
            '',
            array($this, 'zwt_wp_link_previewer_settings_section_callback'),
            self::SLUG_SETTINGS_PAGE
        );
        // PLUGIN ENABLED
        add_settings_field(
            'zwt_wp_link_previewer_enabled',
            Zwt_wp_linkpreviewer_Constants::$ADMIN_TEXT_TAB_SETTINGS_ENABLED,
            array($this, 'zwt_wp_link_previewer_checkbox_enabled'),
            self::SLUG_SETTINGS_PAGE,
            self::SLUG_SETTINGS_SECTION
        );
        // LAYOUT
        add_settings_field(
            'zwt_wp_link_previewer_layout',
            Zwt_wp_linkpreviewer_Constants::$ADMIN_TEXT_TAB_SETTINGS_DEFAULT_LAYOUT,
            array($this, 'zwt_wp_link_previewer_select_layout'),
            self::SLUG_SETTINGS_PAGE,
            self::SLUG_SETTINGS_SECTION
        );
        // TARGET
        add_settings_field(
            'zwt_wp_link_previewer_target',
            Zwt_wp_linkpreviewer_Constants::$ADMIN_TEXT_TAB_SETTINGS_DEFAULT_LINK_TARGET,
            array($this, 'zwt_wp_link_previewer_select_target'),
            self::SLUG_SETTINGS_PAGE,
            self::SLUG_SETTINGS_SECTION
        );
        // REL ATTRIBUTE
        add_settings_field(
            'zwt_wp_link_previewer_rel',
            Zwt_wp_linkpreviewer_Constants::$ADMIN_TEXT_TAB_SETTINGS_DEFAULT_REL,
            array($this, 'zwt_wp_link_previewer_custom_rel'),
            self::SLUG_SETTINGS_PAGE,
            self::SLUG_SETTINGS_SECTION
        );
        // TITLE MAXCHARS
        add_settings_field(
            'zwt_wp_link_previewer_title_maxchars',
            Zwt_wp_linkpreviewer_Constants::$ADMIN_TEXT_TAB_SETTINGS_DEFAULT_TITLE_MAX_CHARS,
            array($this, 'zwt_wp_link_previewer_inputnum_title_maxchars'),
            self::SLUG_SETTINGS_PAGE,
            self::SLUG_SETTINGS_SECTION
        );
        // DESC MAXCHARS
        add_settings_field(
            'zwt_wp_link_previewer_desc_maxchars',
            Zwt_wp_linkpreviewer_Constants::$ADMIN_TEXT_TAB_SETTINGS_DEFAULT_DESCRIPTION_MAX_CHARS,
            array($this, 'zwt_wp_link_previewer_inputnum_desc_maxchars'),
            self::SLUG_SETTINGS_PAGE,
            self::SLUG_SETTINGS_SECTION
        );
        // CREDITS LINK
        add_settings_field(
            'zwt_wp_link_previewer_show_zwt_info',
            Zwt_wp_linkpreviewer_Constants::$ADMIN_TEXT_TAB_SETTINGS_ADD_SUPPORT_LINK,
            array($this, 'zwt_wp_link_previewer_checkbox_previewby'),
            self::SLUG_SETTINGS_PAGE,
            self::SLUG_SETTINGS_SECTION
        );
        // ON UNINSTALL DELETE DATA
        add_settings_field(
            'zwt_wp_link_previewer_on_uninstall_delete_data',
            Zwt_wp_linkpreviewer_Constants::$ADMIN_TEXT_TAB_SETTINGS_DELETE_DATA_UNINSTALL,
            array($this, 'zwt_wp_link_previewer_checkbox_on_uninstall'),
            self::SLUG_SETTINGS_PAGE,
            self::SLUG_SETTINGS_SECTION
        );
    }

    public function sanitize_callback($option)
    {
        $this->sanitize_whitelist($option, "enabled", "0", array("0", "1"));
        $this->sanitize_whitelist($option, "layout", self::LAYOUT_COMPACT, array(self::LAYOUT_COMPACT, self::LAYOUT_FULL));
        $this->sanitize_whitelist($option, "target", self::TARGET_BLANK, array(self::TARGET_BLANK, self::TARGET_SELF, self::TARGET_PARENT, self::TARGET_TOP));
        $this->sanitize_whitelist($option, "show_previewby", "0", array("0", "1"));
        $this->sanitize_whitelist($option, "on_uninstall_delete_data", "0", array("0", "1"));
        $this->sanitize_int($option, "max_title_chars", Zwt_wp_linkpreviewer_Constants::$OPTION_DEFAULT_MAX_TITLE_CHARS, Zwt_wp_linkpreviewer_Constants::$FETCH_TITLE_MAX_CHARS);
        $this->sanitize_int($option, "max_desc_chars", Zwt_wp_linkpreviewer_Constants::$OPTION_DEFAULT_MAX_DESC_CHARS, Zwt_wp_linkpreviewer_Constants::$FETCH_DESC_MAX_CHARS);
        return $option;
    }

    private function sanitize_whitelist(&$option, $name, $default, $white_listed_value)
    {
        if (isset($option[$name])) {
            $var = $option[$name];
            if (!in_array($var, $white_listed_value)) {
                $option[$name] = $default;
            }
        } else {
            $option[$name] = $default;
        }
    }

    private function sanitize_int(&$option, $name, $default, $max_length)
    {
        $var = intval($option[$name]);
        if (empty($var)) {
            $var = $default;
        }
        if ($var < 0) {
            $var = 0;
        } else if ($var > $max_length) {
            $var = $max_length;
        }
        $option[$name] = $var;
    }


    public function zwt_wp_link_previewer_checkbox_enabled()
    {
        $this->output_checkbox(Zwt_wp_linkpreviewer_Constants::$KEY_ENABLED, "false", Zwt_wp_linkpreviewer_Constants::$ADMIN_TEXT_TAB_SETTINGS_ENABLED_LABEL);
    }

    public function zwt_wp_link_previewer_checkbox_previewby()
    {
        $text = Zwt_wp_linkpreviewer_Utils::link_preview_by();
        $this->output_checkbox(Zwt_wp_linkpreviewer_Constants::$KEY_SHOW_PREVIEWBY, Zwt_wp_linkpreviewer_Constants::$OPTION_DEFAULT_SHOW_PREVIEWBY, sprintf(Zwt_wp_linkpreviewer_Constants::$ADMIN_TEXT_TAB_SETTINGS_ADD_SUPPORT_LINK_LABEL, $text));
        echo Zwt_wp_linkpreviewer_Constants::$ADMIN_TEXT_TAB_SETTINGS_ADD_SUPPORT_LINK_DONATE;
        echo Zwt_wp_linkpreviewer_Utils::wrap_anchor(Zwt_wp_linkpreviewer_Constants::$ADMIN_TEXT_TAB_SETTINGS_ADD_SUPPORT_LINK_DONATE_PAYPAL, Zwt_wp_linkpreviewer_Constants::$ADMIN_TEXT_TAB_SETTINGS_ADD_SUPPORT_LINK_DONATE_BUTTON, "button button-secondary", self::TARGET_BLANK);
    }

    public function zwt_wp_link_previewer_checkbox_on_uninstall()
    {
        $this->output_checkbox(Zwt_wp_linkpreviewer_Constants::$KEY_ON_UNINSTALL_DELETE_DATA, Zwt_wp_linkpreviewer_Constants::$OPTION_DEFAULT_ON_UNINSTALL_DELETE_DATA, Zwt_wp_linkpreviewer_Constants::$ADMIN_TEXT_TAB_SETTINGS_DELETE_DATA_UNINSTALL_LABEL);
    }

    public function zwt_wp_link_previewer_select_layout()
    {
        $this->output_radio($this->get_input_name(Zwt_wp_linkpreviewer_Constants::$KEY_LAYOUT), $this->get_plugin_options()[Zwt_wp_linkpreviewer_Constants::$KEY_LAYOUT],
            array(
                self::LAYOUT_COMPACT => "Compact",
                self::LAYOUT_FULL => "Full"));
    }

    public function zwt_wp_link_previewer_select_target()
    {
        $this->output_select($this->get_input_name(Zwt_wp_linkpreviewer_Constants::$KEY_TARGET), $this->get_plugin_options()[Zwt_wp_linkpreviewer_Constants::$KEY_TARGET],
            array(
                self::TARGET_BLANK => self::TARGET_BLANK,
                self::TARGET_SELF => self::TARGET_SELF,
                self::TARGET_PARENT => self::TARGET_PARENT,
                self::TARGET_TOP => self::TARGET_TOP));
    }

    public function zwt_wp_link_previewer_custom_rel()
    {
        $key = Zwt_wp_linkpreviewer_Constants::$KEY_REL;
        $input_name = $this->get_input_name($key);
        $value = $this->getOrDefault($key, Zwt_wp_linkpreviewer_Constants::$OPTION_DEFAULT_REL);
        $this->output_text_input($input_name, $value, Zwt_wp_linkpreviewer_Constants::$ADMIN_TEXT_TAB_SETTINGS_DEFAULT_REL_LABEL, '30');
    }

    public function zwt_wp_link_previewer_inputnum_title_maxchars()
    {
        $key = Zwt_wp_linkpreviewer_Constants::$KEY_MAX_TITLE_CHARS;
        $input_name = $this->get_input_name($key);
        $value = $this->getOrDefault($key, Zwt_wp_linkpreviewer_Constants::$OPTION_DEFAULT_MAX_TITLE_CHARS);
        echo "<input type='text' name='$input_name' value='$value'>";
    }

    public function zwt_wp_link_previewer_inputnum_desc_maxchars()
    {
        $key = Zwt_wp_linkpreviewer_Constants::$KEY_MAX_DESC_CHARS;
        $input_name = $this->get_input_name($key);
        $value = $this->getOrDefault($key, Zwt_wp_linkpreviewer_Constants::$OPTION_DEFAULT_MAX_DESC_CHARS);
        echo "<input type='text' name='$input_name' value='$value'>";
    }

    public function zwt_wp_link_previewer_settings_section_callback()
    {
        // needs to be empty
    }

    private function show_disabled_warning()
    {
        if ($this->getOrDefault(Zwt_wp_linkpreviewer_Constants::$KEY_ENABLED, "0") != 1) {
            $settingsLnk = Zwt_wp_linkpreviewer_Utils::wrap_anchor("options-general.php?page=beautiful_link_preview-settings.php&tab=settings", Zwt_wp_linkpreviewer_Constants::$ADMIN_TEXT_TAB_SETTINGS, "", self::TARGET_SELF);
            echo Zwt_wp_linkpreviewer_Utils::wrap_warning(sprintf(Zwt_wp_linkpreviewer_Constants::$ADMIN_TEXT_TAB_SETTINGS_WARNING_DISABLED, $settingsLnk));
        }
    }

    private function getOrDefault($key, $defaultValue)
    {
        $options = $this->get_plugin_options();
        if (array_key_exists($key, $options)) {
            return $options[$key];
        }
        return $defaultValue;
    }

    private function output_radio($input_name, $set_value, $options)
    {
        echo "<fieldset>";
        foreach ($options as $k => $v) {
            $this->output_radio_item($input_name, $set_value, $k, $v);
        }
        echo "</fieldset>";
    }

    private function output_radio_item($input_name, $set_value, $value, $label)
    {
        echo "<input type=\"radio\" id=\"$value\" name=\"$input_name\" value=\"$value\"";
        checked($set_value, $value);
        echo "/>";
        echo "<label for=\"$value\">";

        if ($value == self::LAYOUT_COMPACT) {
            echo $this->output_img("layout_compact.png");
        } else if ($value == self::LAYOUT_FULL) {
            echo "<br>";
            echo $this->output_img("layout_full.png");
        }
        echo "<br>$label</label>";
        echo "<br>";
    }

    private function output_img($src)
    {
        $url = plugins_url("/img/" . $src, __FILE__);
        echo "<img src=\"$url\">";
    }

    private function output_select($input_name, $set_value, $options)
    {
        echo "<select name='$input_name'>";
        foreach ($options as $k => $v) {
            $this->output_option($set_value, $k, $v);
        }
        echo "</select>";
    }

    private function output_option($set_value, $value, $label)
    {
        echo "<option value='$value' ";
        selected($set_value, $value);
        echo ">$label</option>";
    }

    private function output_text_input($input_name, $value, $label, $size)
    {
        echo "<input type='text'" . ($size ? " size='$size'":'') . " name='$input_name' value='$value'>";
        if ($label) {
            echo "<br><span class='zwt-label'>$label</span>";
        }
    }

    private function output_checkbox($key, $default, $label)
    {
        $input_name = $this->get_input_name($key);
        echo "<input type='checkbox' name='$input_name' id='$input_name'";
        $value = $this->getOrDefault($key, $default);
        if ($value) {
            checked($value, 1);
        } else {
            checked($default, 1);
        }
        echo "value='1'>";

        echo "<label for=\"$input_name\">$label</label>";
    }

    private function get_plugin_options()
    {
        return get_option(Zwt_wp_linkpreviewer_Constants::$OPTION_KEY_SETTINGS, array());
    }

    private function get_input_name($key)
    {
        return Zwt_wp_linkpreviewer_Constants::$OPTION_KEY_SETTINGS . "[" . $key . "]";
    }

}
