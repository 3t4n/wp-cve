<?php
defined('ABSPATH') || exit;

class SparxpresWebSaleFrontend
{
    private $plugin;
    private $linkId;
    private $loanInformation;
    private $wrapperType;

    /**
     * Constructor
     * @param $version
     * @param $root_file
     */
    public function __construct($version, $root_file)
    {
        // Plugin Details
        $this->plugin = new stdClass();
        $this->plugin->version = $version;
        $this->plugin->plugin_root_file = $root_file;
        $this->plugin->plugin_dir_path = plugin_dir_path($root_file);

        $this->linkId = null;
        $this->loanInformation = null;
        $this->wrapperType = null;

        if (!empty($this->getLinkId())) {
            add_action('wp_enqueue_scripts', array($this, 'addToHeader'));
            add_action('wp_footer', array($this, 'addToFooter'));

            add_action('woocommerce_after_add_to_cart_form', array($this, 'addProductPageContent'));
            add_action('woocommerce_after_cart_totals', array($this, 'addCartPageContent'));

            if (SparxpresUtils::get_content_display_type() !== "shortcode") {
                add_filter('the_content', array($this, 'addInformationFilterContent'));
            } else {
                add_shortcode('sparxpres_information', array($this, 'addInformationShortCodeContent'));
            }
        }
    }

    /**
     * @return null
     */
    private function getLinkId()
    {
        if (is_null($this->linkId)) {
            $this->linkId = SparxpresUtils::get_link_id();
            if (empty($this->linkId) || strlen($this->linkId) != 36) {
                $this->linkId = null;
            }
        }
        return $this->linkId;
    }

    /**
     * @return array
     */
    private function getLoanPeriods(): array
    {
        $lInfo = $this->getLoanInformation();
        if (isset($lInfo) && property_exists($lInfo, "loanPeriods")) {
            return $lInfo->loanPeriods;
        }
        return array();
    }

    /**
     * @return int
     */
    private function getLoanPeriodCount(): int
    {
        return count($this->getLoanPeriods());
    }

    /**
     * @return int
     */
    private function getDefaultPeriod(): int
    {
        $lInfo = $this->getLoanInformation();
        if (isset($lInfo) && property_exists($lInfo, "defaultPeriod")) {
            return $lInfo->defaultPeriod;
        }
        return 12;
    }

    /**
     * @return int
     */
    public function getLoanId(): int
    {
        $lInfo = $this->getLoanInformation();
        if (!empty($lInfo) && property_exists($lInfo, "loanId")) {
            return $lInfo->loanId;
        }
        return 0;
    }

    /**
     * Get the loan information
     * @param int $price
     * @return mixed|null
     */
    private function getLoanInformation(int $price = 0)
    {
        if (is_null($this->loanInformation) && $price > 0) {
            $this->loanInformation = SparxpresUtils::get_loan_information(
                $this->getLinkId(),
                $price,
                $this->plugin->version
            );
        }
        return $this->loanInformation;
    }

    /**
     * @param $isProductPage
     * @return string
     */
    private function getWrapperType($isProductPage = true)
    {
        if (is_null($this->wrapperType)) {
            $this->wrapperType = SparxpresUtils::get_wrapper_type($isProductPage);
        }
        return $this->wrapperType;
    }

    /**
     * Add product page loan calculation
     */
    public function addProductPageContent()
    {
        $wrapperType = $this->getWrapperType(true);
        if ($wrapperType == "none") {
            return;
        }

        global $product;
        $price = ceil(wc_get_price_including_tax($product));

        $this->addPageContent($price, $wrapperType, true);
    }

    /**
     * Add cart page loan calculation
     */
    public function addCartPageContent()
    {
        $wrapperType = $this->getWrapperType(false);
        if ($wrapperType == "none") {
            return;
        }

        $price = ceil(WC()->cart->get_total('edit'));
        if (empty($price) &&
            preg_match('/(\d{1,3}[ .,]?\d{3})[.,]?(\d{0,2})/', WC()->cart->get_total('edit'), $matches)) {
            $price = ceil(preg_replace('/[ ,.]/', '', $matches[1]) . '.' . $matches[2]);
        }

        $this->addPageContent($price, $wrapperType, false);
    }

    /**
     * Add finance information content to filter
     * @param $content
     * @return mixed
     */
    public function addInformationFilterContent($content)
    {
        if (is_page() && in_the_loop() && is_main_query()) {
            $pageId = SparxpresUtils::get_information_page_id();
            if ($pageId > 0 && is_page($pageId)) {
                echo $this->get_information_content($this->getLinkId());
            }
        }
        return $content;
    }

    /**
     * Add finance information content to short code
     */
    public function addInformationShortCodeContent()
    {
        return $this->get_information_content($this->getLinkId());
    }

    /**
     * Add script to header
     */
    public function addToHeader()
    {
        if ((is_product() || is_cart()) && $this->getWrapperType(is_product()) != "none") {
            wp_enqueue_style(
                'sparxpres-websale',
                plugins_url('assets/css/sparxpres-websale.css', $this->plugin->plugin_root_file),
                array(),
                $this->plugin->version
            );

            wp_enqueue_script(
                'sparxpres-websale',
                plugins_url('assets/js/sparxpres-websale.js', $this->plugin->plugin_root_file),
                array('jquery'),
                $this->plugin->version,
                false
            );
        }
    }

    /**
     * Add script to footer
     */
    public function addToFooter()
    {
        if (is_product() && $this->getWrapperType(true) != "none") {
            global $product;
            if (!is_null($product) && $product->is_type('variable')) {
                printf(
                    '<script src="%s" id="%s"></script>',
                    plugins_url(
                        'assets/js/sparxpres-websale-vp.js',
                        $this->plugin->plugin_root_file
                    ) . '?ver=' . $this->plugin->version,
                    "sparxpres-websale-vp-js"
                );
            }
        }
    }

    /**
     * Add page loan calculation content
     * @param int $price
     * @param $wrapperType
     * @param bool $isProductPage
     * @param $wrapperType
     */
    private function addPageContent($price = 0, $wrapperType = 'simple', $isProductPage = true)
    {
        $currency = get_woocommerce_currency();
        $lId = $this->getLinkId();
        if ($currency != "DKK" || $price <= 0 || empty($lId)) {
            return;
        }

        $loanInfo = $this->getLoanInformation($price);
        if (!empty($loanInfo)) {
            echo $this->get_loan_content($lId, $price, $loanInfo, $wrapperType, $isProductPage);
        }
    }

    /**
     * Get the loan information text from sparxpres
     * @param $linkId
     * @param $price
     * @param $loanInformation
     * @param $wrapperType
     * @param $isProductPage
     * @return string
     */
    private function get_loan_content($linkId, $price, $loanInformation, $wrapperType, $isProductPage)
    {
        $viewType = SparxpresUtils::get_view_type($this->getLoanPeriodCount());
        $html = file_get_contents($this->plugin->plugin_dir_path . 'assets/html/sparxpres-' . $wrapperType . '.html');
        $html = SparxpresUtils::get_html_with_loan_calculations(
            $linkId,
            $loanInformation,
            $price,
            $this->getDefaultPeriod(),
            $this->getLoanPeriods(),
            $viewType,
            $html,
            $this->plugin->plugin_dir_path,
            $this->plugin->version
        );

        if (empty($html)) {
            return "";
        }

        $style = $this->getWebSaleElementStyle();

        // Build the content return string
        $retValue = '<div id="sparxpres_web_sale" ';
        $retValue .= 'class="' . ($isProductPage ? 'sparxpres_product' : 'sparxpres_cart') . '" ';
        $retValue .= 'data-link-id="' . $linkId . '" ';
        $retValue .= 'data-price="' . $price . '" ';
        $retValue .= 'data-period="' . $this->getDefaultPeriod() . '" ';
        $retValue .= 'data-loan-id="' . $this->getLoanId() . '" ';
        $retValue .= 'data-version="' . $this->plugin->version . '" ';
        $retValue .= $style . '>' . $html . '</div>';
        return $retValue;
    }

    /**
     * @return string
     */
    private function getWebSaleElementStyle(): string
    {
        $style = "";

        $mColor = SparxpresUtils::get_main_color();
        $sBgColor = SparxpresUtils::get_slider_background_color();
        if (!empty($mColor) || !empty($sBgColor)) {
            $style .= "style=\"";
            if (!empty($mColor)) {
                $style .= "--sparxpres-main-color:" . $mColor . ";";
            }
            if (!empty($sBgColor)) {
                $style .= "--sparxpres-slider-bg-color:" . $sBgColor . ";";
            }
            $style .= "\"";
        }

        return $style;
    }

    /**
     * Get the loan information text from sparxpres
     * @param $linkId
     * @return string
     */
    private function get_information_content($linkId)
    {
        if (empty($linkId)) {
            return "";
        }

        $url = "https://sparxpres.dk/app/webintegration/info/?linkId=" . $linkId;
        $data = SparxpresUtils::get_remote_json($url);
        if (empty($data) || empty($data->html)) {
            return "";
        }

        return '<div id="sparxpres_web_sale" class="sparxpres_information">' . $data->html . '</div>';
    }

}
