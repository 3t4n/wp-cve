<?php
defined('ABSPATH') || exit;

class SparxpresUtils
{
    const HOUR_IN_SECONDS = 60 * 60;
    private static $SPARXPRES_BASE_URI = "https://app.sparxpres.dk/spx/rest/calculator";
    private static $CACHE_KEY = "sparxpres_loaninfo";

    // Settings
    public static $DK_SPARXPRES_LINK_ID = "dk_sparxpres_link_id";
    public static $DK_SPARXPRES_INFO_PAGE_ID = "dk_sparxpres_info_page_id";
    public static $DK_SPARXPRES_VIEW_TYPE = "dk_sparxpres_display_view_type";
    public static $DK_SPARXPRES_WRAPPER_TYPE_PRODUCT_PAGE = "dk_sparxpres_display_wrapper_type_product";
    public static $DK_SPARXPRES_WRAPPER_TYPE_CART_PAGE = "dk_sparxpres_display_wrapper_type_cart";

    // Advanced settings
    public static $DK_SPARXPRES_CONTENT_DISPLAY_TYPE = "dk_sparxpres_content_display_type";
    public static $DK_SPARXPRES_MAIN_COLOR = "dk_sparxpres_main_color";
    public static $DK_SPARXPRES_SLIDER_BG_COLOR = "dk_sparxpres_slider_bg_color";
    public static $DK_SPARXPRES_CALLBACK_IDENTIFIER = "dk_sparxpres_callback_identifier";

    /**
     * Constructor
     */
    private function __construct()
    {
    }

    /**
     * Get the linkId
     */
    public static function get_link_id()
    {
        $linkId = wp_unslash(get_option(SparxpresUtils::$DK_SPARXPRES_LINK_ID));
        return empty($linkId) || strlen($linkId) !== 36 ? null : $linkId;
    }

    /**
     * Get the information page id
     */
    public static function get_information_page_id()
    {
        $pageId = intval(get_option(SparxpresUtils::$DK_SPARXPRES_INFO_PAGE_ID));
        return empty($pageId) ? -1 : $pageId;
    }

    /**
     * Get main color
     */
    public static function get_main_color()
    {
        $color = get_option(SparxpresUtils::$DK_SPARXPRES_MAIN_COLOR);
        return empty($color) ? null : $color;
    }

    /**
     * Get secondary color
     */
    public static function get_slider_background_color()
    {
        $color = get_option(SparxpresUtils::$DK_SPARXPRES_SLIDER_BG_COLOR);
        return empty($color) ? null : $color;
    }

    /**
     * Get content display type
     */
    public static function get_content_display_type()
    {
        $dspType = get_option(SparxpresUtils::$DK_SPARXPRES_CONTENT_DISPLAY_TYPE);
        return empty($dspType) ? 'filter' : $dspType;
    }

    /**
     * Get callback identifier key
     */
    public static function get_callback_identifier()
    {
        $callbackIdentifier = get_option(SparxpresUtils::$DK_SPARXPRES_CALLBACK_IDENTIFIER);
        if (empty($callbackIdentifier)) {
            $callbackIdentifier = sanitize_key(str_replace('.', '-', uniqid('spx-', true)));
            update_option(SparxpresUtils::$DK_SPARXPRES_CALLBACK_IDENTIFIER, $callbackIdentifier);
        }
        return $callbackIdentifier;
    }

    /**
     * Get the loan information
     * @param string $linkId
     * @param int $price
     * @param string $version
     * @return mixed|null
     */
    public static function get_loan_information($linkId, $price = 0, $version = null)
    {
        if (empty($linkId)) {
            return null;
        }

        $loanInfo = get_transient(self::$CACHE_KEY);
        if (false === $loanInfo || ($price > 0 && $loanInfo->dynamicPeriod === true)) {
            $url = self::$SPARXPRES_BASE_URI . "/loaninfo?linkId=" . $linkId;
            if (isset($price) && $price > 0) {
                $url .= "&amount=" . $price;
            }
            if (isset($version)) {
                $url .= "&websaleversion=wordpress_v" . $version;
            }

            $loanInfo = SparxpresUtils::get_remote_json($url);
            if (isset($loanInfo) && ($loanInfo->dynamicPeriod === false || $price === 0)) {
                set_transient(self::$CACHE_KEY, $loanInfo, self::HOUR_IN_SECONDS);
            }
        }

        return $loanInfo;
    }

    /**
     * Get the loan calculation
     * @param $linkId
     * @param $period
     * @param $price
     * @param $version
     * @return mixed|null
     */
    private static function get_loan_calculation($linkId, $period, $price, $version = null)
    {
        if (empty($linkId) || empty($period) || empty($price)) {
            return null;
        }

        $url = self::$SPARXPRES_BASE_URI . "/loancalc?linkId=" . $linkId . "&period=" . $period . "&amount=" . $price;
        if (isset($version)) {
            $url .= "&websaleversion=wordpress_v" . $version;
        }

        return SparxpresUtils::get_remote_json($url);
    }

    /**
     * @param $linkId
     * @param $loanInformation
     * @param $price
     * @param $period
     * @param $loanPeriods
     * @param $viewType
     * @param $html
     * @param $plugin_dir_path
     * @param $version
     * @return array|string|string[]|null
     */
    public static function get_html_with_loan_calculations(
        $linkId,
        $loanInformation,
        $price,
        $period,
        $loanPeriods,
        $viewType,
        $html,
        $plugin_dir_path,
        $version = null
    )
    {
        if (empty($html) || !isset($loanInformation)) {
            return null;
        }

        $isFinanceEnabled = SparxpresUtils::is_finance_enabled($loanInformation, $price);
        $isXpresPayEnabled = SparxpresUtils::is_xprespay_enabled($loanInformation, $price);
        if (!$isFinanceEnabled && !$isXpresPayEnabled) {
            return null;
        }

        if (!$isFinanceEnabled) {
            $html = "";
        } else {
            $monthlyPayments = '';
            $complianceText = '';

            $loanCalc = self::get_loan_calculation($linkId, $loanInformation->defaultPeriod, $price, $version);
            if (isset($loanCalc)) {
                if ($loanCalc->success) {
                    $monthlyPayments = $loanCalc->formattedMonthlyPayments;
                    $complianceText = $loanCalc->complianceText;
                } else {
                    $html = "";
                }
//            } else {
//                $html .= "SparxpresRuntimeRecalculate";
            }

            $periodHtml = '';
            if ($viewType == 'dropdown') {
                $periodHtml = '<select ' .
                    'class="sparxpres-select" ' .
                    'onchange="' .
                    'window.dispatchEvent(new CustomEvent(\'sparxpresPeriodChange\', {detail: {period: this.value}}));' .
                    '">';

                foreach ($loanPeriods as $loanPeriod) {
                    $periodHtml .= '<option ' .
                        'value="' . $loanPeriod->id . '" ' . selected($period, $loanPeriod->id, false) .
                        '>' .
                        $loanPeriod->text .
                        '</option>';
                }

                $periodHtml .= '</select>';
            } elseif ($viewType == 'slider') {
                $minPeriod = $loanPeriods[0]->id;
                $maxPeriod = $loanPeriods[count($loanPeriods) - 1]->id;
                $step = $loanPeriods[1]->id - $loanPeriods[0]->id;

                $style = "";
                if ($period != $minPeriod) {
                    $pct = ($period - $minPeriod) / ($maxPeriod - $minPeriod) * 100;
                    $style = "style=\"--sparxpres-slider-pct:" . round($pct, 2) . "%;\"";
                }

                $periodHtml = '<input type="range" class="sparxpres-slider" prefix="mdr." ' .
                    'min="' . $minPeriod . '" ' .
                    'max="' . $maxPeriod . '" ' .
                    'step="' . $step . '" ' .
                    'value="' . $period . '" ' .
                    'onchange="window.dispatchEvent(new CustomEvent(\'sparxpresPeriodChange\', ' .
                    '{detail: {period: this.value}}));" ' .
                    'oninput="window.dispatchEvent(new CustomEvent(\'sparxpresPeriodInput\', ' .
                    '{detail: {period:this.value,min:this.getAttribute(\'min\'),max:this.getAttribute(\'max\')}}));" ' .
                    $style .
                    ' />';

                $periodHtml .= '<div class="sparxpres-slider-steps">';
                foreach ($loanPeriods as $loanPeriod) {
                    $periodHtml .= '<div class="sparxpres-slider-step">' . $loanPeriod->id . '</div>';
                }
                $periodHtml .= '</div>';
            }

            if (!empty($periodHtml)) {
                $periodHtml = '<div id="sparxpres_web_sale_period">' . $periodHtml . '</div>';
            }

            $html = str_replace('##PERIOD_HTML##', $periodHtml, $html);
            $html = str_replace('##MONTHLY_PAYMENTS##', $monthlyPayments, $html);
            $html = str_replace('##COMPLIANCE_TEXT##', $complianceText, $html);
        }

        if ($isXpresPayEnabled) {
            $html .= file_get_contents($plugin_dir_path . 'assets/html/xprespay.html');
        }

        return $html;
    }

    /**
     * Get wrapper type
     * @param $isProductPage
     * @return string
     */
    public static function get_wrapper_type($isProductPage = true)
    {
        $wrapperType = get_option($isProductPage
            ? SparxpresUtils::$DK_SPARXPRES_WRAPPER_TYPE_PRODUCT_PAGE
            : SparxpresUtils::$DK_SPARXPRES_WRAPPER_TYPE_CART_PAGE);

        return empty($wrapperType) ? 'simple' : $wrapperType;
    }

    /**
     * Get the view type
     * @param int $loanPeriodCount
     * @return string
     */
    public static function get_view_type($loanPeriodCount = 0)
    {
        if ($loanPeriodCount < 2) {
            return "plain";
        }

        $viewType = get_option(SparxpresUtils::$DK_SPARXPRES_VIEW_TYPE);
        return empty($viewType) ? "slider" : $viewType;
    }

    /**
     * @param $loanInformation
     * @param $price
     * @return bool
     */
    public static function is_finance_enabled($loanInformation, $price = 0)
    {
        return isset($loanInformation)
            && $loanInformation->loanId > 0
            && $price >= $loanInformation->minAmount
            && $price <= $loanInformation->maxAmount;
    }

    /**
     * Is credit enabled?
     * @param $loanInformation
     * @param $price
     * @return bool
     */
    public static function is_xprespay_enabled($loanInformation, $price = 0)
    {
        return isset($loanInformation)
            && $loanInformation->spxCreditEnabled
            && $price > 0
            && $price <= $loanInformation->spxCreditMaximum;
    }

    /**
     * Get json from url and return it as an object
     * @param string $url
     * @return mixed|null
     */
    public static function get_remote_json_INACT($url)
    {
        try {
            $response = wp_remote_get($url, array(
                'httpversion' => '1.1',
                'sslverify' => false
            ));

            if (!is_wp_error($response) && 200 === wp_remote_retrieve_response_code($response)) {
                return json_decode(wp_remote_retrieve_body($response));
            }
        } catch (Exception $ex) {
            error_log($ex->getMessage());
            // nothing to do
        }
        return null;
    }

    /**
     * Get json from url and return it as an object
     * @param $url
     * @return mixed|null
     */
    public static function get_remote_json($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 4);    // Connection timeout
        curl_setopt($curl, CURLOPT_TIMEOUT, 6);           // Total timeout incl. connection timeout
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);

        $data = curl_exec($curl);
        $errno = curl_errno($curl);
        curl_close($curl);

        return $errno === 0 ? json_decode($data) : null;
    }

    /**
     * Post the callback key to Sparxpres api
     * @return bool
     */
    public static function post_callback_key($linkId = '')
    {
        try {
            $response = wp_remote_post("https://sparxpres.dk/app/plugin/deliver-authorization/", array(
                'httpversion' => '1.1',
                'sslverify' => false,
                'body' => array(
                    'cms' => "WordPress",
                    'linkId' => $linkId,
                    'url' => get_rest_url(null, '/sparxpres/v1/callback'),
                    'key' => SparxpresUtils::get_callback_identifier()
                )
            ));

            return !is_wp_error($response) && 201 === wp_remote_retrieve_response_code($response);
        } catch (Exception $ex) {
            return false;
        }
    }
}
