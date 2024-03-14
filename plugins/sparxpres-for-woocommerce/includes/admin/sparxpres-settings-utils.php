<?php
defined('ABSPATH') || exit;

if (!class_exists('Sparxpres_Settings_Utils')) {
    class Sparxpres_Settings_Utils
    {
        private $settingsName;

        /**
         * Constructor
         */
        public function __construct($settingsName)
        {
            $this->settingsName = $settingsName;
        }

        public function add_basic_settings()
        {
            $sectionId = 'basic_settings';

            add_settings_section(
                $sectionId,
                esc_html__('General Settings', 'sparxpres'),    // Title
                false,                                          // Callback
                $this->settingsName                             // Page
            );

            add_settings_field(
                SparxpresUtils::$DK_SPARXPRES_LINK_ID,               // ID
                esc_html__('Sparxpres link id', 'sparxpres') . '*',  // Title
                array($this, 'buildLinkIdCallback'),                 // Callback
                $this->settingsName,                                 // Page
                $sectionId,
                array('label_for' => SparxpresUtils::$DK_SPARXPRES_LINK_ID)
            );

            add_settings_field(
                SparxpresUtils::$DK_SPARXPRES_VIEW_TYPE,           // ID
                esc_html__('Periode display type', 'sparxpres'),   // Title
                array($this, 'buildPeriodDisplayTypeCallback'),    // Callback
                $this->settingsName,                               // Page
                $sectionId,
                array('label_for' => SparxpresUtils::$DK_SPARXPRES_VIEW_TYPE)
            );

            add_settings_field(
                SparxpresUtils::$DK_SPARXPRES_WRAPPER_TYPE_PRODUCT_PAGE,  // ID
                esc_html__('Product page display', 'sparxpres'),          // Title
                array($this, 'buildProductPageWrapperTypeCallback'),      // Callback
                $this->settingsName,                                      // Page
                $sectionId,
                array('label_for' => SparxpresUtils::$DK_SPARXPRES_WRAPPER_TYPE_PRODUCT_PAGE)
            );

            add_settings_field(
                SparxpresUtils::$DK_SPARXPRES_WRAPPER_TYPE_CART_PAGE,   // ID
                esc_html__('Cart page display', 'sparxpres'),           // Title
                array($this, 'buildCartPageWrapperTypeCallback'),       // Callback
                $this->settingsName,                                    // Page
                $sectionId,
                array('label_for' => SparxpresUtils::$DK_SPARXPRES_WRAPPER_TYPE_CART_PAGE)
            );

            add_settings_field(
                SparxpresUtils::$DK_SPARXPRES_MAIN_COLOR,           // ID
                esc_html__('Main color', 'sparxpres'),   // Title
                array($this, 'buildDefaultColorCallback'),          // Callback
                $this->settingsName,                                // Page
                $sectionId,
                array('label_for' => SparxpresUtils::$DK_SPARXPRES_MAIN_COLOR)
            );
        }

        public function add_advanced_settings()
        {
            $sectionId = 'advanced_settings';

            add_settings_section(
                $sectionId,
                esc_html__('Advanced settings', 'sparxpres'),   // Title
                false,                                          // Callback
                $this->settingsName                             // Page
            );

            add_settings_field(
                SparxpresUtils::$DK_SPARXPRES_SLIDER_BG_COLOR,                // ID
                esc_html__('Slider background color', 'sparxpres'),           // Title
                array($this, 'buildDefaultBackgroundColorCallback'),          // Callback
                $this->settingsName,                                          // Page
                $sectionId,
                array('label_for' => SparxpresUtils::$DK_SPARXPRES_SLIDER_BG_COLOR)
            );

            add_settings_field(
                SparxpresUtils::$DK_SPARXPRES_INFO_PAGE_ID,         // ID
                esc_html__('Finance information', 'sparxpres'),     // Title
                array($this, 'buildInformationPageIdCallback'),     // Callback
                $this->settingsName,                                // Page
                $sectionId,
                array('label_for' => SparxpresUtils::$DK_SPARXPRES_INFO_PAGE_ID)
            );

            add_settings_field(
                SparxpresUtils::$DK_SPARXPRES_CONTENT_DISPLAY_TYPE,                // ID
                esc_html__('Finance information page integration', 'sparxpres'),   // Title
                array($this, 'buildInformationPageInsertionTypeCallback'),         // Callback
                $this->settingsName,                                               // Page
                $sectionId,
                array('label_for' => SparxpresUtils::$DK_SPARXPRES_CONTENT_DISPLAY_TYPE)
            );

            add_settings_field(
                SparxpresUtils::$DK_SPARXPRES_CALLBACK_IDENTIFIER,    // ID
                esc_html__('Callback key', 'sparxpres'),              // Title
                array($this, 'buildCallbackKeyIdentifierCallback'),   // Callback
                $this->settingsName,                                  // Page
                $sectionId,
                array('label_for' => SparxpresUtils::$DK_SPARXPRES_CALLBACK_IDENTIFIER)
            );
        }

        /**
         * Build link id field
         */
        public function buildLinkIdCallback()
        {
            echo $this->buildTextInputField(
                SparxpresUtils::$DK_SPARXPRES_LINK_ID,
                esc_attr(wp_unslash(get_option(SparxpresUtils::$DK_SPARXPRES_LINK_ID, ''))),
                esc_html__('Insert the link id you got from Sparxpres', 'sparxpres')
            );
        }

        /**
         * Build default main color field
         */
        public function buildDefaultColorCallback()
        {
            echo $this->buildTextInputField(
                SparxpresUtils::$DK_SPARXPRES_MAIN_COLOR,
                esc_attr(wp_unslash(get_option(SparxpresUtils::$DK_SPARXPRES_MAIN_COLOR, ''))),
                esc_html__('Insert a valid hex color code to change the default red color', 'sparxpres')
            );
        }

        /**
         * Build default background color field
         */
        public function buildDefaultBackgroundColorCallback()
        {
            echo $this->buildTextInputField(
                SparxpresUtils::$DK_SPARXPRES_SLIDER_BG_COLOR,
                esc_attr(wp_unslash(get_option(SparxpresUtils::$DK_SPARXPRES_SLIDER_BG_COLOR, ''))),
                esc_html__('Insert a valid hex color code to change slider range background color', 'sparxpres')
            );
        }

        /**
         * Build callback key identifier field
         */
        public function buildCallbackKeyIdentifierCallback()
        {
            $description = sprintf(
                '%s<br>(%s)',
                esc_html__('This key is used to authenticate callbacks.', 'sparxpres'),
                get_rest_url(null, '/sparxpres/v1/callback')
            );

            echo $this->buildTextInputField(
                SparxpresUtils::$DK_SPARXPRES_CALLBACK_IDENTIFIER,
                SparxpresUtils::get_callback_identifier(),
                $description,
                true
            );
        }

        /**
         * Build loan period display type selection
         */
        public function buildPeriodDisplayTypeCallback()
        {
            $viewType = wp_unslash(get_option(SparxpresUtils::$DK_SPARXPRES_VIEW_TYPE));

            $options = $this->buildOption('slider', $viewType, esc_attr__('Slider', 'sparxpres'));
            $options .= $this->buildOption('dropdown', $viewType, esc_attr__('Dropdown', 'sparxpres'));
            $options .= $this->buildOption('plain', $viewType, esc_attr__('Plain (only one period)', 'sparxpres'));

            printf(
                '<select name="%s" id="%s">%s</select><p class="description">%s</p>',
                SparxpresUtils::$DK_SPARXPRES_VIEW_TYPE,
                SparxpresUtils::$DK_SPARXPRES_VIEW_TYPE,
                $options,
                esc_html__('Choose how the periods should be displayed', 'sparxpres')
            );
        }

        /**
         * Build product page wrapper type selection
         */
        public function buildProductPageWrapperTypeCallback()
        {
            $wrapperType = wp_unslash(get_option(SparxpresUtils::$DK_SPARXPRES_WRAPPER_TYPE_PRODUCT_PAGE));

            $options = $this->buildOption('simple', $wrapperType,
                esc_attr__('Show the loan calculation integrated on the page', 'sparxpres'));
            $options .= $this->buildOption('modal', $wrapperType,
                esc_attr__('Show the loan calculation in a popup window (with a button on the page)', 'sparxpres'));
            $options .= $this->buildOption('none', $wrapperType,
                esc_attr__('Do not show the loan calculator', 'sparxpres'));

            printf(
                '<select name="%s" id="%s">%s</select><p class="description">%s</p>',
                SparxpresUtils::$DK_SPARXPRES_WRAPPER_TYPE_PRODUCT_PAGE,
                SparxpresUtils::$DK_SPARXPRES_WRAPPER_TYPE_PRODUCT_PAGE,
                $options,
                esc_html__('Choose how the calculation is displayed', 'sparxpres')
            );
        }

        /**
         * Build cart page wrapper type selection
         */
        public function buildCartPageWrapperTypeCallback()
        {
            $wrapperType = wp_unslash(get_option(SparxpresUtils::$DK_SPARXPRES_WRAPPER_TYPE_CART_PAGE));

            $options = $this->buildOption('simple', $wrapperType,
                esc_attr__('Show the loan calculation integrated on the page', 'sparxpres'));
            $options .= $this->buildOption('modal', $wrapperType,
                esc_attr__('Show the loan calculation in a popup window (with a button on the page)', 'sparxpres'));
            $options .= $this->buildOption('none', $wrapperType,
                esc_attr__('Do not show the loan calculator', 'sparxpres'));

            printf(
                '<select name="%s" id="%s">%s</select><p class="description">%s</p>',
                SparxpresUtils::$DK_SPARXPRES_WRAPPER_TYPE_CART_PAGE,
                SparxpresUtils::$DK_SPARXPRES_WRAPPER_TYPE_CART_PAGE,
                $options,
                esc_html__('Choose how the calculation is displayed', 'sparxpres')
            );
        }

        /**
         * Build information page id selection
         */
        public function buildInformationPageIdCallback()
        {
            $curId = SparxpresUtils::get_information_page_id();

            $options = sprintf(
                '<optgroup label="%s">%s</optgroup>',
                esc_attr__('Default'),
                $this->buildOption('-1', $curId, esc_attr__('Modal window', 'sparxpres'))
            );

            $pageOptions = '';
            foreach (get_pages() as $page) {
                $pageOptions .= $this->buildOption($page->ID, $curId, esc_attr($page->post_title));
            }

            $options .= sprintf(
                '<optgroup label="%s">%s</optgroup>',
                esc_attr__('Pages'),
                $pageOptions
            );

            printf(
                '<select name="%s" id="%s">%s</select><p class="description">%s</p>',
                SparxpresUtils::$DK_SPARXPRES_INFO_PAGE_ID,
                SparxpresUtils::$DK_SPARXPRES_INFO_PAGE_ID,
                $options,
                sprintf('<strong>%s</strong>', esc_html__('Deprecated', 'sparxpres'))
            );
        }

        /**
         * Build information page insertion type selection
         */
        public function buildInformationPageInsertionTypeCallback()
        {
            $contentType = wp_unslash(get_option(SparxpresUtils::$DK_SPARXPRES_CONTENT_DISPLAY_TYPE));

            $options = sprintf(
                '<optgroup label="%s">%s</optgroup>',
                esc_attr__('Default'),
                $this->buildOption('filter', $contentType, esc_attr__('WordPress filter', 'sparxpres'))
            );

            $options .= sprintf(
                '<optgroup label="%s">%s</optgroup>',
                esc_attr__('Others', 'sparxpres'),
                $this->buildOption('shortcode', $contentType,
                    esc_attr__('Shortcode', 'sparxpres') . ' ([sparxpres_information])')
            );

            printf(
                '<select name="%s" id="%s">%s</select><p class="description">%s</p>',
                SparxpresUtils::$DK_SPARXPRES_CONTENT_DISPLAY_TYPE,
                SparxpresUtils::$DK_SPARXPRES_CONTENT_DISPLAY_TYPE,
                $options,
                sprintf('<strong>%s</strong>', esc_html__('Deprecated', 'sparxpres'))
            );
        }

        /**
         * Build a option
         */
        private function buildOption($value, $selectedValue = '', $displayText = '', $disabled = false): string
        {
            return sprintf(
                '<option value="%s" %s %s>%s</option>',
                $value,
                selected($selectedValue, $value, false),
                $disabled ? 'disabled' : '',
                $displayText
            );
        }

        /**
         * Build text input field
         */
        private function buildTextInputField($fieldId, $value, $description = '', $readOnly = false): string
        {
            return sprintf(
                '<input type="text" id="%s" name="%s" class="regular-text" %s value="%s" />' .
                '<p class="description">%s</p>',
                $fieldId,
                $fieldId,
                $readOnly ? 'readonly' : '',
                $value,
                $description
            );
        }

    }
}
