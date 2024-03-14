<?php
if(!class_exists('EOD_API'))
{
    class EOD_API{
        public function __construct(){

        }

        /**
         * Get User API key (token)
         * @return string
         */
        public static function get_eod_api_key()
        {
            $plugin_options = get_option('eod_options');
            if($plugin_options === false) $plugin_options = array();
            //Default token
            $apiKey = EOD_DEFAULT_API;
            if(array_key_exists('api_key', $plugin_options) && $plugin_options['api_key']){
                $apiKey = $plugin_options['api_key'];
            }
            return($apiKey);
        }

        /**
         * Check the API key (token) and its tariff plan for the possibility of receiving data
         * @param string type
         * @param array props
         * @return mixed
         */
        public static function check_token_capability($type, $props)
        {
            if(($type === 'historical' || $type === 'live') && isset($props['target'])){
                return self::get_real_time_ticker($type, $props['target']);
            }

            if($type === 'news' && ($props['target'] || $props['tag'])){
                return self::get_news($props['target'], array(
                    'limit' => 1,
                    'tag'   => ''
                ));
            }

            if($type === 'fundamental' && $props['target']){
                return self::get_fundamental_data($props['target']);
            }

            return array();
        }


        /**
         * Get news via API
         * @param string target
         * @param array args
         * @return mixed
         */
        public static function get_news( $target = '', $args = array() ){
            // Check target/tag
            if((!$target || gettype($target) !== 'string') && (!isset($args['tag']) || gettype($args['tag']) !== 'string'))
                return array('error' => 'wrong target');

            $apiKey = self::get_eod_api_key();

            // Base URL
            $apiUrl = "https://eodhd.com/api/news?api_token=$apiKey";

            // Target
            if($target && gettype($target) === 'string')
                $apiUrl .= "&s=$target";
            // Tag
            if($args['tag'] && gettype($args['tag']) === 'string')
                $apiUrl .= "&t=".str_replace(' ','%20', $args['tag']);

            // Offset
            $offset = isset($args['offset']) ? intval($args['offset']) : 0;
            if($offset < 0) $offset = 0;
            $apiUrl .= "&offset=$offset";

            // Limit
            $limit = isset($args['limit']) ? intval($args['limit']) : 50;
            if($limit < 1) $limit = 1;
            if($limit > 1000) $limit = 1000;
            $apiUrl .= "&limit=$limit";

            // Date range
            if($args['from']){
                $d = DateTime::createFromFormat('Y-m-d', $args['from']);
                if($d && $d->format('Y-m-d') === $args['from'])
                    $apiUrl .= "&from=".$args['from'];
            }
            if($args['to']){
                $d = DateTime::createFromFormat('Y-m-d', $args['to']);
                if($d && $d->format('Y-m-d') === $args['to'])
                    $apiUrl .= "&to=".$args['to'];
            }

            return self::call_eod_api($apiUrl);
        }


        /**
         * Searching for items from API by string
         * @param string $needle
         * @return mixed
         */
        public static function search_by_string($needle)
        {
            if(!$needle){
                return array('error' => 'empty string');
            }
            $needle = sanitize_text_field($needle);

//            $apiKey = self::get_eod_api_key();
//            $apiUrl = "https://eodhd.com/api/search/".
//                $needle .
//                "?api_token=$apiKey";
            $apiUrl = "https://eodhd.com/api/query-search-extended/?q=$needle";

            $data = self::call_eod_api($apiUrl);
            if(!$data)
                return array('error' => 'no result from api');

            return $data;
        }

        /**
         * Get Fundamental Data
         * @param string target
         * @return mixed
         */
        public static function get_fundamental_data($target)
        {
            if(!is_string($target)) return array('error' => 'Wrong target');

            $apiKey = self::get_eod_api_key();
            $apiUrl = "https://eodhd.com/api/fundamentals/".
                strtoupper($target).
                "?api_token=$apiKey".
                "&fmt=json";

            $fundamental_data = self::call_eod_api($apiUrl);
            if(!$fundamental_data)
                return array('error' => 'no result from fundamental data api');

            return $fundamental_data;
        }


        /**
         * Get Ticker infos and calculate evolution
         * @param string type
         * @param mixed targets
         * @return mixed
         */
        public static function get_real_time_ticker($type = 'historical', $targets)
        {
            $apiKey = self::get_eod_api_key();

            if($type === 'historical'){
                if(!is_array($targets)){
                    $targets = array($targets);
                }
                $apiUrl = "https://eodhd.com/api/eod-bulk-last-day/US".
                    "?api_token=$apiKey".
                    "&fmt=json".
                    "&symbols=".strtoupper(implode(',', $targets));
            }else if($type === 'live'){
                if(!is_array($targets)){
                    $targets = array($targets[0]);
                }
                $extraTargets = strtoupper(implode(',', array_slice($targets,1)));
                $apiUrl = "https://eodhd.com/api/real-time/".strtoupper($targets[0]).
                    "?api_token=$apiKey".
                    "&fmt=json";
                //Extra target management.
                if($extraTargets) $apiUrl .= "&s=$extraTargets";
            }else{
                return array('error' => 'wrong type');
            }

            // TODO: probably in the absence of additional targets, need to wrap result in a array list
            $tickerData = self::call_eod_api($apiUrl);
            if(!$tickerData)
                return array('error' => 'no result from real time api');

            // TODO: PLUG, remove it after realise subscribe module
            if($type === 'historical' && isset($tickerData['error_code']) && $tickerData['error_code'] === 'forbidden'){
                $tickerData = [];
                foreach ($targets as $target){
                    $apiUrl = "https://eodhd.com/api/eod/$target?api_token=demo&fmt=json&filter=last_close";
                    $single_data = self::call_eod_api($apiUrl);
                    if($single_data && !isset($single_data['error'])){
                        $tickerData[] = [
                            'code' => explode('.', $target)[0],
                            'exchange_short_name' => explode('.', $target)[1],
                            'close' => $single_data,
                            'change' => '',
                            'change_p' => '',
                        ];
                    }
                }
            }

            return $tickerData;
        }


        /**
         * Will cal api asking then returns the result
         * @param string apiUrl
         * @return mixed
         */
        public static function call_eod_api($apiUrl)
        {
            if(!$apiUrl || gettype($apiUrl) !== 'string')
                return array('error' => 'Wrong API URL');

            //Create request and get result
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_VERBOSE, true);
            curl_setopt($ch, CURLOPT_HEADER, true);

            $response = curl_exec($ch);

            //Parse response (headers vs body)
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $headers = substr($response, 0, $header_size);
            $body = substr($response, $header_size);
            curl_close($ch);

            //Parse json body or return error
            if(!$body || strlen(trim($body)) === 0){
                return array('error_code' => 'null', 'error' => 'null body', 'headers'  => $headers);
            }
            if(in_array($body, ["Forbidden. Please contact support@eodhistoricaldata.com", "Forbidden"])){
                return array('error_code' => 'forbidden', 'error' => 'Forbidden. Perhaps your data plan does not allow you to receive data. Please contact support@eodhistoricaldata.com', 'headers'  => $headers);
            }
            if(in_array($body, ["Unauthenticated"])){
                return array('error_code' => 'unauthenticated', 'error' => 'Unauthenticated', 'headers'  => $headers);
            }

            try {
                $result = json_decode($body, true);
            } catch (Exception $err) {
                $result = array('error' => $body, 'exception' => $err->getMessage(), 'headers'  => $headers);
                error_log('Error getting api result : '.print_r($err,true));
            }

            return $result;
        }


        /**
         * Get list of news topics
         * @return array
         */
        public static function get_news_topics()
        {
            return ['balance sheet', 'capital employed', 'class action', 'company announcement', 'consensus eps estimate', 'consensus estimate', 'credit rating', 'discounted cash flow', 'dividend payments', 'earnings estimate', 'earnings growth', 'earnings per share', 'earnings release', 'earnings report', 'earnings results', 'earnings surprise', 'estimate revisions', 'european regulatory news', 'financial results', 'fourth quarter', 'free cash flow', 'future cash flows', 'growth rate', 'initial public offering', 'insider ownership', 'insider transactions', 'institutional investors', 'institutional ownership', 'intrinsic value', 'market research reports', 'net income', 'operating income', 'present value', 'press releases', 'price target', 'quarterly earnings', 'quarterly results', 'ratings', 'research analysis and reports', 'return on equity', 'revenue estimates', 'revenue growth', 'roce', 'roe', 'share price', 'shareholder rights', 'shareholder', 'shares outstanding', 'strong buy', 'total revenue', 'zacks investment research', 'zacks rank'];
        }


        /**
         * Get array with hierarchy for fundamental data
         * @return array
         */
        public static function get_fd_hierarchy()
        {
            return array(
                "crypto" => array(
                    "General" => array(
                        "list" => [
                            "Name" =>       ["title" => __("Name","EOD-f-data")],
                            "Type" =>       ["title" => __("Type","EOD-f-data")],
                            "Category" =>   ["title" => __("Category","EOD-f-data")],
                            "WebURL" =>     ["title" => __("Web URL","EOD-f-data")],
                        ],
                    ),
                    "Statistics" => array(
                        "list" => [
                            "MarketCapitalization" =>        ["title" => __("Market Capitalization","EOD-f-data"), "type" => "number"],
                            "MarketCapitalizationDiluted" => ["title" => __("Market Capitalization Diluted","EOD-f-data"), "type" => "number"],
                            "CirculatingSupply" =>           ["title" => __("Circulating Supply","EOD-f-data"), "type" => "number"],
                            "TotalSupply" =>                 ["title" => __("Total Supply","EOD-f-data"), "type" => "number"],
                            "MaxSupply" =>                   ["title" => __("Max Supply","EOD-f-data"), "type" => "number"],
                            "MarketCapDominance" =>          ["title" => __("Market CapDominance","EOD-f-data"), "type" => "number"],
                            "TechnicalDoc" =>                ["title" => __("Technical Doc","EOD-f-data")],
                            "Explorer" =>                    ["title" => __("Explorer","EOD-f-data")],
                            "SourceCode" =>                  ["title" => __("Source Code","EOD-f-data")],
                            "MessageBoard" =>                ["title" => __("Message Board","EOD-f-data")],
                            "LowAllTime" =>                  ["title" => __("Low All Time","EOD-f-data"), "type" => "number"],
                            "HighAllTime" =>                 ["title" => __("High All Time","EOD-f-data"), "type" => "number"],
                        ],
                    ),
                ),
                "index" => array(
                    "General" => array(
                        "list" => [
                            "Code" =>           ["title" => __("Code","EOD-f-data")],
                            "Type" =>           ["title" => __("Type","EOD-f-data")],
                            "Name" =>           ["title" => __("Name","EOD-f-data")],
                            "Exchange" =>       ["title" => __("Exchange","EOD-f-data")],
                            "CurrencyCode" =>   ["title" => __("Currency Code","EOD-f-data")],
                            "CurrencyName" =>   ["title" => __("Currency Name","EOD-f-data")],
                            "CurrencySymbol" => ["title" => __("Currency Symbol","EOD-f-data")],
                            "CountryName" =>    ["title" => __("Country Name","EOD-f-data")],
                            "CountryISO" =>     ["title" => __("Country ISO","EOD-f-data")],
                            "OpenFigi" =>       ["title" => __("OpenFIGI","EOD-f-data")],
                        ],
                    ),
                    "Components" => ["title" => __("Components","EOD-f-data")],
                    "HistoricalTickerComponents" => ["title" => __("Historical Ticker Components","EOD-f-data")],
                ),
                "fund" => array(
                    "General" => array(
                        "list" => [
                            "Code" =>               ["title" => __("Code","EOD-f-data")],
                            "Type" =>               ["title" => __("Type","EOD-f-data")],
                            "Name" =>               ["title" => __("Name","EOD-f-data")],
                            "Exchange" =>           ["title" => __("Exchange","EOD-f-data")],
                            "CurrencyCode" =>       ["title" => __("Currency Code","EOD-f-data")],
                            "CurrencyName" =>       ["title" => __("Currency Name","EOD-f-data")],
                            "CurrencySymbol" =>     ["title" => __("Currency Symbol","EOD-f-data")],
                            "CountryName" =>        ["title" => __("Country Name","EOD-f-data")],
                            "CountryISO" =>         ["title" => __("Country ISO","EOD-f-data")],
                            "ISIN" =>               ["title" => __("ISIN","EOD-f-data")],
                            "CUSIP" =>              ["title" => __("CUSIP","EOD-f-data")],
                            "Fund_Summary" =>       ["title" => __("Fund Summary","EOD-f-data")],
                            "Fund_Family" =>        ["title" => __("Fund Family","EOD-f-data")],
                            "Fund_Category" =>      ["title" => __("Fund Category","EOD-f-data")],
                            "Fund_Style" =>         ["title" => __("Fund Style","EOD-f-data")],
                            "Fiscal_Year_End" =>    ["title" => __("Fiscal Year End","EOD-f-data")],
                            "MarketCapitalization" => ["title" => __("Market Capitalization","EOD-f-data"), "type" => "number"],
                        ],
                    ),
                    "MutualFund_Data" => array(
                        "title" => __("Mutual Fund Data","EOD-f-data"),
                        "list" => [
                            "Fund_Category" =>              ["title" => __("Fund Category","EOD-f-data")],
                            "Fund_Style" =>                 ["title" => __("Fund Style","EOD-f-data")],
                            "Nav" =>                        ["title" => __("Nav","EOD-f-data"), "type" => "number"],
                            "Prev_Close_Price" =>           ["title" => __("Prev Close Price","EOD-f-data"), "type" => "number"],
                            "Update_Date" =>                ["title" => __("Update Date","EOD-f-data")],
                            "Portfolio_Net_Assets" =>       ["title" => __("Portfolio Net Assets","EOD-f-data"), "type" => "number"],
                            "Share_Class_Net_Assets" =>     ["title" => __("Share Class Net Assets","EOD-f-data"), "type" => "number"],
                            "Morning_Star_Rating" =>        ["title" => __("Morning Star Rating","EOD-f-data")],
                            "Morning_Star_Risk_Rating" =>   ["title" => __("Morning Star Risk Rating","EOD-f-data")],
                            "Morning_Star_Category" =>      ["title" => __("Morning Star Category","EOD-f-data")],
                            "Inception_Date" =>             ["title" => __("Inception Date","EOD-f-data")],
                            "Currency" =>                   ["title" => __("Currency","EOD-f-data")],
                            "Domicile" =>                   ["title" => __("Domicile","EOD-f-data")],
                            "Yield" =>                      ["title" => __("Yield","EOD-f-data"), "type" => "number"],
                            "Yield_YTD" =>                  ["title" => __("Yield YTD","EOD-f-data"), "type" => "number"],
                            "Yield_1Year_YTD" =>            ["title" => __("Yield 1 Year YTD","EOD-f-data"), "type" => "number"],
                            "Yield_3Year_YTD" =>            ["title" => __("Yield 3 Year YTD","EOD-f-data"), "type" => "number"],
                            "Yield_5Year_YTD" =>            ["title" => __("Yield 5 Year YTD","EOD-f-data"), "type" => "number"],
                            "Expense_Ratio" =>              ["title" => __("Expense Ratio","EOD-f-data"), "type" => "number"],
                            "Expense_Ratio_Date" =>         ["title" => __("Expense Ratio Date","EOD-f-data")],
                            "Asset_Allocation" =>           ["title" => __("Asset Allocation","EOD-f-data")],
                            "Value_Growth" =>               ["title" => __("Value Growth","EOD-f-data")],
                            "Top_Holdings" =>               ["title" => __("Top Holdings","EOD-f-data")],
                            "Market_Capitalization" =>      ["title" => __("Market Capitalization","EOD-f-data")],
                            "Top_Countries" =>              ["title" => __("Top Countries","EOD-f-data")],
                            "Sector_Weights" => array(
                                "title" => __("Sector Weights","EOD-f-data"),
                                "list" => [
                                    "Cyclical" =>                   ["title" => __("Cyclical","EOD-f-data")],
                                    "Defensive" =>                  ["title" => __("Defensive","EOD-f-data")],
                                    "Sensitive" =>                  ["title" => __("Sensitive","EOD-f-data")],
                                    "Bond Sector" =>                  ["title" => __("Bond Sector","EOD-f-data")],
                                ],
                            ),
                            "World_Regions" => array(
                                "title" => __("World Regions","EOD-f-data"),
                                "list" => [
                                    "Americas" =>                   ["title" => __("Americas","EOD-f-data")],
                                    "Greater Asia" =>               ["title" => __("Greater Asia","EOD-f-data")],
                                    "Greater Europe" =>             ["title" => __("Greater Europe","EOD-f-data")],
                                    "Market Classification" =>      ["title" => __("Market Classification","EOD-f-data")],
                                ],
                            ),
                        ],
                    ),
                ),
                "etf" => array(
                    "General" => array(
                        "list" => [
                            "Code"          => ["title" => __("Code","EOD-f-data")],
                            "Type"          => ["title" => __("Type","EOD-f-data")],
                            "Name"          => ["title" => __("Name","EOD-f-data")],
                            "Exchange"      => ["title" => __("Exchange","EOD-f-data")],
                            "CurrencyCode"  => ["title" => __("Currency Code","EOD-f-data")],
                            "CurrencyName"  => ["title" => __("Currency Name","EOD-f-data")],
                            "CurrencySymbol" => ["title" => __("Currency Symbol","EOD-f-data")],
                            "CountryName"   => ["title" => __("Country Name","EOD-f-data")],
                            "CountryISO"    => ["title" => __("Country ISO","EOD-f-data")],
                            "OpenFigi"      => ["title" => __("OpenFIGI","EOD-f-data")],
                            "Description"   => ["title" => __("Description","EOD-f-data")],
                            "Category"      => ["title" => __("Category","EOD-f-data")],
                            "UpdatedAt"     => ["title" => __("Updated At","EOD-f-data")],
                        ],
                    ),
                    "Technicals" => array(
                        "list" => [
                            "Beta"          => ["title" => __("Beta","EOD-f-data"), "type" => "number"],
                            "52WeekHigh"    => ["title" => __("52 Week High","EOD-f-data"), "type" => "number"],
                            "52WeekLow"     => ["title" => __("52 Week Low","EOD-f-data"), "type" => "number"],
                            "50DayMA"       => ["title" => __("50 Day MA","EOD-f-data"), "type" => "number"],
                            "200DayMA"      => ["title" => __("200 Day MA","EOD-f-data"), "type" => "number"],
                        ],
                    ),
                    "ETF_Data" => array(
                        "list" => [
                            "ISIN"                      => ["title" => __("ISIN","EOD-f-data")],
                            "Company_Name"              => ["title" => __("Company Name","EOD-f-data")],
                            "Company_URL"               => ["title" => __("Company URL","EOD-f-data")],
                            "ETF_URL"                   => ["title" => __("ETF URL","EOD-f-data")],
                            "Domicile"                  => ["title" => __("Domicile","EOD-f-data")],
                            "Index_Name"                => ["title" => __("Index Name","EOD-f-data")],
                            "Yield"                     => ["title" => __("Yield","EOD-f-data"), "type" => "number"],
                            "Dividend_Paying_Frequency" => ["title" => __("Dividend Paying Frequency","EOD-f-data")],
                            "Inception_Date"            => ["title" => __("Inception Date","EOD-f-data")],
                            "Max_Annual_Mgmt_Charge"    => ["title" => __("Max Annual Mgmt Charge","EOD-f-data"), "type" => "number"],
                            "Ongoing_Charge"            => ["title" => __("Ongoing Charge","EOD-f-data"), "type" => "number"],
                            "Date_Ongoing_Charge"       => ["title" => __("Date Ongoing Charge","EOD-f-data"), "type" => "number"],
                            "NetExpenseRatio"           => ["title" => __("Net Expense Ratio","EOD-f-data"), "type" => "number"],
                            "AnnualHoldingsTurnover"    => ["title" => __("Annual Holdings Turnover","EOD-f-data"), "type" => "number"],
                            "TotalAssets"               => ["title" => __("Total Assets","EOD-f-data"), "type" => "number"],
                            "Average_Mkt_Cap_Mil"       => ["title" => __("Average Mkt Cap Mil","EOD-f-data")],
                            "Market_Capitalisation" => array(
                                "title" => __("Market Capitalisation","EOD-f-data"),
                                "list" => [
                                    "Mega"      => ["title" => __("Mega","EOD-f-data"), "type" => "number"],
                                    "Big"       => ["title" => __("Big","EOD-f-data"), "type" => "number"],
                                    "Medium"    => ["title" => __("Medium","EOD-f-data"), "type" => "number"],
                                    "Small"     => ["title" => __("Small","EOD-f-data"), "type" => "number"],
                                    "Micro"     => ["title" => __("Micro","EOD-f-data"), "type" => "number"],
                                ],
                            ),
                            "Asset_Allocation"          => ["title" => __("Asset Allocation","EOD-f-data")],
                            "World_Regions"             => ["title" => __("World Regions","EOD-f-data")],
                            "Sector_Weights"            => ["title" => __("Sector Weights","EOD-f-data")],
                            "Fixed_Income"              => ["title" => __("Fixed Income","EOD-f-data")],
                            "Holdings_Count"            => ["title" => __("Holdings Count","EOD-f-data"), "type" => "number"],
                            "Top_10_Holdings"           => ["title" => __("Top 10 Holdings","EOD-f-data")],
                            "Holdings"                  => ["title" => __("Holdings","EOD-f-data")],
                            "MorningStar"               => ["title" => __("Morning Star","EOD-f-data")],
                            "Valuations_Growth" => array(
                                "title" => __("Valuations Growth","EOD-f-data"),
                                "list" => [
                                    "Valuations_Rates_Portfolio"        => ["title" => __("Valuations Rates Portfolio","EOD-f-data")],
                                    "Valuations_Rates_To_Category"      => ["title" => __("Valuations Rates To Category","EOD-f-data")],
                                    "Growth_Rates_Portfolio"            => ["title" => __("Growth Rates Portfolio","EOD-f-data")],
                                    "Growth_Rates_To_Category"          => ["title" => __("Growth Rates To Category","EOD-f-data")],
                                ],
                            ),
                            "Performance" => array(
                                "title" => __("Performance","EOD-f-data"),
                                "list" => [
                                    "1y_Volatility"     => ["title" => __("1y Volatility","EOD-f-data"), "type" => "number"],
                                    "3y_Volatility"     => ["title" => __("3y Volatility","EOD-f-data"), "type" => "number"],
                                    "3y_ExpReturn"      => ["title" => __("3y Exp Return","EOD-f-data"), "type" => "number"],
                                    "3y_SharpRatio"     => ["title" => __("3y Sharp Ratio","EOD-f-data"), "type" => "number"],
                                    "Returns_YTD"       => ["title" => __("Returns YTD","EOD-f-data"), "type" => "number"],
                                    "Returns_1Y"        => ["title" => __("Returns 1Y","EOD-f-data"), "type" => "number"],
                                    "Returns_3Y"        => ["title" => __("Returns 3Y","EOD-f-data"), "type" => "number"],
                                    "Returns_5Y"        => ["title" => __("Returns 5Y","EOD-f-data"), "type" => "number"],
                                    "Returns_10Y"       => ["title" => __("Returns 10Y","EOD-f-data"), "type" => "number"],
                                ],
                            )
                        ],
                    ),
                ),
                "common_stock" => array(
                    "General" => array(
                        "list" => [
                            "Code" => ["title" => __("Code","EOD-f-data")],
                            "Type" => ["title" => __("Type","EOD-f-data")],
                            "Name" => ["title" => __("Name","EOD-f-data")],
                            "Exchange" => ["title" => __("Exchange","EOD-f-data")],
                            "CurrencyCode" => ["title" => __("Currency Code","EOD-f-data")],
                            "CurrencyName" => ["title" => __("Currency Name","EOD-f-data")],
                            "CurrencySymbol" => ["title" => __("Currency Symbol","EOD-f-data")],
                            "CountryName" => ["title" => __("Country Name","EOD-f-data")],
                            "CountryISO" => ["title" => __("Country ISO","EOD-f-data")],
                            "OpenFigi" =>       ["title" => __("OpenFIGI","EOD-f-data")],
                            "ISIN" => ["title" => __("ISIN","EOD-f-data")],
                            "LEI" => ["title" => __("LEI","EOD-f-data")],
                            "CUSIP" => ["title" => __("CUSIP","EOD-f-data")],
                            "CIK" => ["title" => __("CIK","EOD-f-data")],
                            "EmployerIdNumber" => ["title" => __("Employer Id Number","EOD-f-data")],
                            "FiscalYearEnd" => ["title" => __("Fiscal Year End","EOD-f-data")],
                            "IPODate" => ["title" => __("IPO Date","EOD-f-data")],
                            "InternationalDomestic" => ["title" => __("International Domestic","EOD-f-data")],
                            "Sector" => ["title" => __("Sector","EOD-f-data")],
                            "Industry" => ["title" => __("Industry","EOD-f-data")],
                            "GicSector" => ["title" => __("Gic Sector","EOD-f-data")],
                            "GicGroup" => ["title" => __("Gic Group","EOD-f-data")],
                            "GicIndustry" => ["title" => __("Gic Industry","EOD-f-data")],
                            "GicSubIndustry" => ["title" => __("Gic SubIndustry","EOD-f-data")],
                            "HomeCategory" => ["title" => __("Home Category","EOD-f-data")],
                            "IsDelisted" => ["title" => __("Is Delisted","EOD-f-data")],
                            "Description" => ["title" => __("Description","EOD-f-data")],
                            "Address" => ["title" => __("Address","EOD-f-data")],
                            "AddressData" => array(
                                "title" => __("AddressData","EOD-f-data"),
                                "list" => [
                                    "Street" => ["title" => __("Street","EOD-f-data")],
                                    "City" => ["title" => __("City","EOD-f-data")],
                                    "State" => ["title" => __("State","EOD-f-data")],
                                    "Country" => ["title" => __("Country","EOD-f-data")],
                                    "ZIP" => ["title" => __("ZIP","EOD-f-data")],
                                ],
                            ),
                            "Listings" => ["title" => __("Listings","EOD-f-data")],
                            "Officers" => ["title" => __("Officers","EOD-f-data")],
                            "Phone" => ["title" => __("Phone","EOD-f-data")],
                            "WebURL" => ["title" => __("Web URL","EOD-f-data")],
                            "LogoURL" => ["title" => __("Logo URL","EOD-f-data")],
                            "FullTimeEmployees" => ["title" => __("Full Time Employees","EOD-f-data"), "type" => "number"],
                            "UpdatedAt" => ["title" => __("Updated At","EOD-f-data")],
                        ],
                    ),
                    "Highlights" => array(
                        "list" => [
                            "MarketCapitalization" => ["title" => __("Market Capitalization","EOD-f-data"), "type" => "number"],
                            "MarketCapitalizationMln" => ["title" => __("Market Capitalization Mln","EOD-f-data")],
                            "EBITDA" => ["title" => __("EBITDA","EOD-f-data"), "type" => "number"],
                            "PERatio" => ["title" => __("PE Ratio","EOD-f-data"), "type" => "number"],
                            "PEGRatio" => ["title" => __("PEG Ratio","EOD-f-data"), "type" => "number"],
                            "WallStreetTargetPrice" => ["title" => __("Wall Street Target Price","EOD-f-data"), "type" => "number"],
                            "BookValue" => ["title" => __("Book Value","EOD-f-data"), "type" => "number"],
                            "DividendShare" => ["title" => __("Dividend Share","EOD-f-data"), "type" => "number"],
                            "DividendYield" => ["title" => __("Dividend Yield","EOD-f-data"), "type" => "number"],
                            "EarningsShare" => ["title" => __("Earnings Share","EOD-f-data"), "type" => "number"],
                            "EPSEstimateCurrentYear" => ["title" => __("EPS Estimate Current Year","EOD-f-data"), "type" => "number"],
                            "EPSEstimateNextYear" => ["title" => __("EPS Estimate Next Year","EOD-f-data"), "type" => "number"],
                            "EPSEstimateNextQuarter" => ["title" => __("EPS Estimate Next Quarter","EOD-f-data"), "type" => "number"],
                            "EPSEstimateCurrentQuarter" => ["title" => __("EPS Estimate Current Quarter","EOD-f-data"), "type" => "number"],
                            "MostRecentQuarter" => ["title" => __("Most Recent Quarter","EOD-f-data")],
                            "ProfitMargin" => ["title" => __("Profit Margin","EOD-f-data"), "type" => "number"],
                            "OperatingMarginTTM" => ["title" => __("Operating Margin TTM","EOD-f-data"), "type" => "number"],
                            "ReturnOnAssetsTTM" => ["title" => __("Return On Assets TTM","EOD-f-data"), "type" => "number"],
                            "ReturnOnEquityTTM" => ["title" => __("Return On Equity TTM","EOD-f-data"), "type" => "number"],
                            "RevenueTTM" => ["title" => __("Revenue TTM","EOD-f-data"), "type" => "number"],
                            "RevenuePerShareTTM" => ["title" => __("Revenue Per Share TTM","EOD-f-data"), "type" => "number"],
                            "QuarterlyRevenueGrowthYOY" => ["title" => __("Quarterly Revenue Growth YOY","EOD-f-data"), "type" => "number"],
                            "GrossProfitTTM" => ["title" => __("Gross Profit TTM","EOD-f-data"), "type" => "number"],
                            "DilutedEpsTTM" => ["title" => __("Diluted Eps TTM","EOD-f-data"), "type" => "number"],
                            "QuarterlyEarningsGrowthYOY" => ["title" => __("Quarterly Earnings Growth YOY","EOD-f-data"), "type" => "number"],
                        ],
                    ),
                    "Valuation" => array(
                        "list" => [
                            "TrailingPE" => ["title" => __("Trailing PE","EOD-f-data"), "type" => "number"],
                            "ForwardPE" => ["title" => __("Forward PE","EOD-f-data"), "type" => "number"],
                            "PriceSalesTTM" => ["title" => __("Price Sales TTM","EOD-f-data"), "type" => "number"],
                            "PriceBookMRQ" => ["title" => __("Price Book MRQ","EOD-f-data"), "type" => "number"],
                            "EnterpriseValue" => ["title" => __("Enterprise Value","EOD-f-data"), "type" => "number"],
                            "EnterpriseValueRevenue" => ["title" => __("Enterprise Value Revenue","EOD-f-data"), "type" => "number"],
                            "EnterpriseValueEbitda" => ["title" => __("Enterprise Value Ebitda","EOD-f-data"), "type" => "number"],
                        ],
                    ),
                    "SharesStats" => array(
                        "list" => [
                            "SharesOutstanding" => ["title" => __("Shares Outstanding","EOD-f-data"), "type" => "number"],
                            "SharesFloat" => ["title" => __("Shares Float","EOD-f-data"), "type" => "number"],
                            "PercentInsiders" => ["title" => __("Percent Insiders","EOD-f-data"), "type" => "number"],
                            "PercentInstitutions" => ["title" => __("Percent Institutions","EOD-f-data"), "type" => "number"],
                            "SharesShort" => ["title" => __("Shares Short","EOD-f-data"), "type" => "number"],
                            "SharesShortPriorMonth" => ["title" => __("Shares Short Prior Month","EOD-f-data"), "type" => "number"],
                            "ShortRatio" => ["title" => __("Short Ratio","EOD-f-data"), "type" => "number"],
                            "ShortPercentOutstanding" => ["title" => __("Short Percent Outstanding","EOD-f-data"), "type" => "number"],
                            "ShortPercentFloat" => ["title" => __("Short Percent Float","EOD-f-data"), "type" => "number"],
                        ],
                    ),
                    "Technicals" => array(
                        "list" => [
                            "Beta" => ["title" => __("Beta","EOD-f-data"), "type" => "number"],
                            "52WeekHigh" => ["title" => __("52 Week High","EOD-f-data"), "type" => "number"],
                            "52WeekLow" => ["title" => __("52 Week Low","EOD-f-data"), "type" => "number"],
                            "50DayMA" => ["title" => __("50 Day MA","EOD-f-data"), "type" => "number"],
                            "200DayMA" => ["title" => __("200 Day MA","EOD-f-data"), "type" => "number"],
                            "SharesShort" => ["title" => __("Shares Short","EOD-f-data"), "type" => "number"],
                            "SharesShortPriorMonth" => ["title" => __("Shares Short Prior Month","EOD-f-data"), "type" => "number"],
                            "ShortRatio" => ["title" => __("Short Ratio","EOD-f-data"), "type" => "number"],
                            "ShortPercent" => ["title" => __("Short Percent","EOD-f-data"), "type" => "number"],
                        ],
                    ),
                    "SplitsDividends" => array(
                        "list" => [
                            "ForwardAnnualDividendRate" => ["title" => __("Forward Annual Dividend Rate","EOD-f-data"), "type" => "number"],
                            "ForwardAnnualDividendYield" => ["title" => __("Forward Annual Dividend Yield","EOD-f-data"), "type" => "number"],
                            "PayoutRatio" => ["title" => __("Payout Ratio","EOD-f-data"), "type" => "number"],
                            "DividendDate" => ["title" => __("Dividend Date","EOD-f-data")],
                            "ExDividendDate" => ["title" => __("Ex Dividend Date","EOD-f-data")],
                            "LastSplitFactor" => ["title" => __("Last Split Factor","EOD-f-data")],
                            "LastSplitDate" => ["title" => __("Last Split Date","EOD-f-data")],
                            "NumberDividendsByYear" => ["title" => __("Number Dividends By Year","EOD-f-data")],
                        ],
                    ),
                    "AnalystRatings" => array(
                        "title" => __("Analyst Ratings","EOD-f-data"),
                        "list" => [
                            "Rating" => ["title" => __("Rating","EOD-f-data")],
                            "TargetPrice" => ["title" => __("Target Price","EOD-f-data")],
                            "StrongBuy" => ["title" => __("Strong Buy","EOD-f-data")],
                            "Buy" => ["title" => __("Buy","EOD-f-data")],
                            "Hold" => ["title" => __("Hold","EOD-f-data")],
                            "Sell" => ["title" => __("Sell","EOD-f-data")],
                            "StrongSell" => ["title" => __("Strong Sell","EOD-f-data")],
                        ],
                    ),
                    "Holders" => array(
                        "title" => __("Holders","EOD-f-data"),
                        "list" => [
                            "Institutions" => ["title" => __("Institutions","EOD-f-data")],
                            "Funds" => ["title" => __("Funds","EOD-f-data")],
                        ],
                    ),
                    "InsiderTransactions" => ["title" => __("Insider Transactions","EOD-f-data")],
                    "ESGScores" => array(
                        "title" => __("ESG Scores","EOD-f-data"),
                        "list" => [
                            "Disclaimer" => ["title" => __("Disclaimer","EOD-f-data")],
                            "RatingDate" => ["title" => __("Rating Date","EOD-f-data")],
                            "TotalEsg" => ["title" => __("Total Esg","EOD-f-data"), "type" => "number"],
                            "TotalEsgPercentile" => ["title" => __("Total Esg Percentile","EOD-f-data"), "type" => "number"],
                            "EnvironmentScore" => ["title" => __("Environment Score","EOD-f-data"), "type" => "number"],
                            "EnvironmentScorePercentile" => ["title" => __("Environment Score Percentile","EOD-f-data"), "type" => "number"],
                            "SocialScore" => ["title" => __("Social Score","EOD-f-data"), "type" => "number"],
                            "SocialScorePercentile" => ["title" => __("Social Score Percentile","EOD-f-data"), "type" => "number"],
                            "GovernanceScore" => ["title" => __("Governance Score","EOD-f-data"), "type" => "number"],
                            "GovernanceScorePercentile" => ["title" => __("Governance Score Percentile","EOD-f-data"), "type" => "number"],
                            "ControversyLevel" => ["title" => __("Controversy Level","EOD-f-data"), "type" => "number"],
                            "ActivitiesInvolvement" => ["title" => __("Activities Involvement","EOD-f-data")],
                        ],
                    ),
                )
            );
        }


        /**
         * Get library of financials labels
         * @return array
         */
        public function get_financial_hierarchy()
        {
            return array(
                "Earnings->History" => array(
                    "Earnings" => array(
                        "list" => [
                            "History" => array(
                                "timeline" => "quarterly",
                                "list" => [
                                    "beforeAfterMarket" => ["title" => __("Before After Market","EOD-f-data")],
                                    "currency" => ["title" => __("Currency","EOD-f-data")],
                                    "epsActual" => ["title" => __("EPS Actual","EOD-f-data")],
                                    "epsEstimate" => ["title" => __("EPS Estimate","EOD-f-data")],
                                    "epsDifference" => ["title" => __("EPS Difference","EOD-f-data")],
                                    "surprisePercent" => ["title" => __("Surprise Percent","EOD-f-data")],
                                ],
                            ),
                        ],
                    ),
                ),
                "Earnings->Trend" => array(
                    "Earnings" => array(
                        "list" => [
                            "Trend" => array(
                                "timeline" => "quarterly",
                                "list" => [
                                    "period" => ["title" => __("Period","EOD-f-data")],
                                    "growth" => ["title" => __("Growth","EOD-f-data")],
                                    "earningsEstimateAvg" => ["title" => __("Earnings Estimate Avg","EOD-f-data")],
                                    "earningsEstimateLow" => ["title" => __("Earnings Estimate Low","EOD-f-data")],
                                    "earningsEstimateHigh" => ["title" => __("Earnings Estimate High","EOD-f-data")],
                                    "earningsEstimateYearAgoEps" => ["title" => __("Earnings Estimate Year Ago EPS","EOD-f-data")],
                                    "earningsEstimateNumberOfAnalysts" => ["title" => __("Earnings Estimate Number of Analysts","EOD-f-data")],
                                    "earningsEstimateGrowth" => ["title" => __("Earnings Estimate Growth","EOD-f-data")],
                                    "revenueEstimateAvg" => ["title" => __("Revenue Estimate Avg","EOD-f-data")],
                                    "revenueEstimateLow" => ["title" => __("Revenue Estimate Low","EOD-f-data")],
                                    "revenueEstimateHigh" => ["title" => __("Revenue Estimate High","EOD-f-data")],
                                    "revenueEstimateYearAgoEps" => ["title" => __("Revenue Estimate Year Ago EPS","EOD-f-data")],
                                    "revenueEstimateNumberOfAnalysts" => ["title" => __("Revenue Estimate Number of Analysts","EOD-f-data")],
                                    "revenueEstimateGrowth" => ["title" => __("Revenue Estimate Growth","EOD-f-data")],
                                    "epsTrendCurrent" => ["title" => __("EPS Trend Current","EOD-f-data")],
                                    "epsTrend7daysAgo" => ["title" => __("EPS Trend 7 days Ago","EOD-f-data")],
                                    "epsTrend30daysAgo" => ["title" => __("EPS Trend 30 days Ago","EOD-f-data")],
                                    "epsTrend60daysAgo" => ["title" => __("EPS Trend 60 days Ago","EOD-f-data")],
                                    "epsTrend90daysAgo" => ["title" => __("EPS Trend 90 days Ago","EOD-f-data")],
                                    "epsRevisionsUpLast7days" => ["title" => __("EPS Revisions up Last 7 days","EOD-f-data")],
                                    "epsRevisionsUpLast30days" => ["title" => __("EPS Revisions up Last 30 days","EOD-f-data")],
                                    "epsRevisionsDownLast7days" => ["title" => __("EPS Revisions down Last 7 days","EOD-f-data")],
                                    "epsRevisionsDownLast30days" => ["title" => __("EPS Revisions down Last 30 days","EOD-f-data")],
                                ],
                            ),
                        ],
                    ),
                ),
                "Earnings->Annual" => array(
                    "Earnings" => array(
                        "list" => [
                            "Annual" => array(
                                "timeline" => "yearly",
                                "list" => [
                                    "epsActual" => ["title" => __("EPS Actual","EOD-f-data")],
                                ],
                            ),
                        ],
                    ),
                ),
                "Financials->Balance_Sheet" => array(
                    "Financials" => array(
                        "list" => [
                            "Balance_Sheet" => array(
                                "timeline" => "both",
                                "list" => [
                                    "currency_symbol" => ["title" => __("Currency Symbol","EOD-f-data")],
                                    "totalAssets" => ["title" => __("Total Assets","EOD-f-data"), "type" => "number"],
                                    "intangibleAssets" => ["title" => __("Intangible Assets","EOD-f-data")],
                                    "earningAssets" => ["title" => __("Earning Assets","EOD-f-data")],
                                    "otherCurrentAssets" => ["title" => __("Other Current Assets","EOD-f-data")],
                                    "totalLiab" => ["title" => __("Total Liab","EOD-f-data")],
                                    "totalStockholderEquity" => ["title" => __("Total Stockholder Equity","EOD-f-data")],
                                    "deferredLongTermLiab" => ["title" => __("Deferred Long Term Liab","EOD-f-data")],
                                    "otherCurrentLiab" => ["title" => __("Other Current Liab","EOD-f-data")],
                                    "commonStock" => ["title" => __("Common Stock","EOD-f-data")],
                                    "retainedEarnings" => ["title" => __("Retained Earnings","EOD-f-data")],
                                    "otherLiab" => ["title" => __("Other Liab","EOD-f-data")],
                                    "goodWill" => ["title" => __("Good Will","EOD-f-data")],
                                    "otherAssets" => ["title" => __("Other Assets","EOD-f-data")],
                                    "cash" => ["title" => __("Cash","EOD-f-data")],
                                    "totalCurrentLiabilities" => ["title" => __("Total Current Liabilities","EOD-f-data")],
                                    "netDebt" => ["title" => __("Net Debt","EOD-f-data")],
                                    "shortTermDebt" => ["title" => __("Short Term Debt","EOD-f-data")],
                                    "shortLongTermDebt" => ["title" => __("Short Long Term Debt","EOD-f-data")],
                                    "shortLongTermDebtTotal" => ["title" => __("Short Long Term Debt Total","EOD-f-data")],
                                    "otherStockholderEquity" => ["title" => __("Other Stockholder Equity","EOD-f-data")],
                                    "propertyPlantEquipment" => ["title" => __("Property Plant Equipment","EOD-f-data")],
                                    "totalCurrentAssets" => ["title" => __("Total Current Assets","EOD-f-data")],
                                    "longTermInvestments" => ["title" => __("Long Term Investments","EOD-f-data")],
                                    "netTangibleAssets" => ["title" => __("Net Tangible Assets","EOD-f-data")],
                                    "shortTermInvestments" => ["title" => __("Short Term Investments","EOD-f-data")],
                                    "netReceivables" => ["title" => __("Net Receivables","EOD-f-data")],
                                    "longTermDebt" => ["title" => __("Long TermDebt","EOD-f-data")],
                                    "inventory" => ["title" => __("Inventory","EOD-f-data")],
                                    "accountsPayable" => ["title" => __("Accounts Payable","EOD-f-data")],
                                    "totalPermanentEquity" => ["title" => __("Total Permanent Equity","EOD-f-data")],
                                    "noncontrollingInterestInConsolidatedEntity" => ["title" => __("Non Controlling Interest In Consolidated Entity","EOD-f-data")],
                                    "temporaryEquityRedeemableNoncontrollingInterests" => ["title" => __("Temporary Equity Redeemable Non Controlling Interests","EOD-f-data")],
                                    "accumulatedOtherComprehensiveIncome" => ["title" => __("Accumulated Other Comprehensive Income","EOD-f-data")],
                                    "additionalPaidInCapital" => ["title" => __("Additional Paid In Capital","EOD-f-data")],
                                    "commonStockTotalEquity" => ["title" => __("Common Stock Total Equity","EOD-f-data")],
                                    "preferredStockTotalEquity" => ["title" => __("Preferred Stock Total Equity","EOD-f-data")],
                                    "retainedEarningsTotalEquity" => ["title" => __("Retained Earnings Total Equity","EOD-f-data")],
                                    "treasuryStock" => ["title" => __("Treasury Stock","EOD-f-data")],
                                    "accumulatedAmortization" => ["title" => __("Accumulated Amortization","EOD-f-data")],
                                    "nonCurrrentAssetsOther" => ["title" => __("Non Current Assets Other","EOD-f-data")],
                                    "deferredLongTermAssetCharges" => ["title" => __("Deferred Long Term Asset Charges","EOD-f-data")],
                                    "nonCurrentAssetsTotal" => ["title" => __("Non Current Assets Total","EOD-f-data")],
                                    "capitalLeaseObligations" => ["title" => __("Capital Lease Obligations","EOD-f-data")],
                                    "longTermDebtTotal" => ["title" => __("Long Term Debt Total","EOD-f-data")],
                                    "nonCurrentLiabilitiesOther" => ["title" => __("Non Current Liabilities Other","EOD-f-data")],
                                    "nonCurrentLiabilitiesTotal" => ["title" => __("Non Current Liabilities Total","EOD-f-data")],
                                    "negativeGoodwill" => ["title" => __("Negative Goodwill","EOD-f-data")],
                                    "warrants" => ["title" => __("Warrants","EOD-f-data")],
                                    "preferredStockRedeemable" => ["title" => __("Preferred Stock Redeemable","EOD-f-data")],
                                    "capitalSurpluse" => ["title" => __("Capital Surplus","EOD-f-data")],
                                    "liabilitiesAndStockholdersEquity" => ["title" => __("Liabilities And Stockholders Equity","EOD-f-data")],
                                    "cashAndShortTermInvestments" => ["title" => __("Cash AndShort Term Investments","EOD-f-data")],
                                    "propertyPlantAndEquipmentGross" => ["title" => __("Property Plant And Equipment Gross","EOD-f-data")],
                                    "propertyPlantAndEquipmentNet" => ["title" => __("Property Plant And Equipment Net","EOD-f-data")],
                                    "accumulatedDepreciation" => ["title" => __("Accumulated Depreciation","EOD-f-data")],
                                    "netWorkingCapital" => ["title" => __("Net Working Capital","EOD-f-data")],
                                    "netInvestedCapital" => ["title" => __("Net Invested Capital","EOD-f-data")],
                                    "commonStockSharesOutstanding" => ["title" => __("Common Stock Shares Outstanding","EOD-f-data")],
                                ],
                            ),
                        ],
                    ),
                ),
                "Financials->Cash_Flow" => array(
                    "Financials" => array(
                        "list" => [
                            "Cash_Flow" => array(
                                "timeline" => "both",
                                "list" => [
                                    "currency_symbol" => ["title" => __("Currency Symbol","EOD-f-data")],
                                    "investments" => ["title" => __("Investments","EOD-f-data")],
                                    "changeToLiabilities" => ["title" => __("Change to Liabilities","EOD-f-data")],
                                    "totalCashflowsFromInvestingActivities" => ["title" => __("Total Cash Flows From Investing Activities","EOD-f-data")],
                                    "netBorrowings" => ["title" => __("Net Borrowings","EOD-f-data")],
                                    "totalCashFromFinancingActivities" => ["title" => __("Total Cash from Financing Activities","EOD-f-data")],
                                    "changeToOperatingActivities" => ["title" => __("Change to Operating Activities","EOD-f-data")],
                                    "netIncome" => ["title" => __("Net Income","EOD-f-data")],
                                    "changeInCash" => ["title" => __("Change in Cash","EOD-f-data")],
                                    "beginPeriodCashFlow" => ["title" => __("Begin Period Cash Flow","EOD-f-data")],
                                    "endPeriodCashFlow" => ["title" => __("End Period Cash Flow","EOD-f-data")],
                                    "totalCashFromOperatingActivities" => ["title" => __("Total Cash From Operating Activities","EOD-f-data")],
                                    "depreciation" => ["title" => __("Depreciation","EOD-f-data")],
                                    "otherCashflowsFromInvestingActivities" => ["title" => __("Other Cash Flows from Investing Activities","EOD-f-data")],
                                    "dividendsPaid" => ["title" => __("Dividends Paid","EOD-f-data")],
                                    "changeToInventory" => ["title" => __("Change to Inventory","EOD-f-data")],
                                    "changeToAccountReceivables" => ["title" => __("Change to Account Receivables","EOD-f-data")],
                                    "salePurchaseOfStock" => ["title" => __("Sale Purchase of Stock","EOD-f-data")],
                                    "otherCashflowsFromFinancingActivities" => ["title" => __("Other Cash Flows from Financing Activities","EOD-f-data")],
                                    "changeToNetincome" => ["title" => __("Change to Net Income","EOD-f-data")],
                                    "capitalExpenditures" => ["title" => __("Capital Expenditures","EOD-f-data")],
                                    "changeReceivables" => ["title" => __("Change Receivables","EOD-f-data")],
                                    "cashFlowsOtherOperating" => ["title" => __("Cash Flows Other Operating","EOD-f-data")],
                                    "exchangeRateChanges" => ["title" => __("Exchange Rate Changes","EOD-f-data")],
                                    "cashAndCashEquivalentsChanges" => ["title" => __("Cash and Cash Equivalents Changes","EOD-f-data")],
                                    "changeInWorkingCapital" => ["title" => __("Change in Working Capital","EOD-f-data")],
                                    "otherNonCashItems" => ["title" => __("Other Non Cash Items","EOD-f-data")],
                                    "freeCashFlow" => ["title" => __("Free Cash Flow","EOD-f-data")],
                                ],
                            ),
                        ],
                    ),
                ),
                "Financials->Income_Statement" => array(
                    "Financials" => array(
                        "list" => [
                            "Income_Statement" => array(
                                "timeline" => "both",
                                "list" => [
                                    "currency_symbol" => ["title" => __("Currency Symbol","EOD-f-data")],
                                    "researchDevelopment" => ["title" => __("Research Development","EOD-f-data")],
                                    "effectOfAccountingCharges" => ["title" => __("Effect of Accounting Charges","EOD-f-data")],
                                    "incomeBeforeTax" => ["title" => __("Income Before Tax","EOD-f-data")],
                                    "minorityInterest" => ["title" => __("Minority Interest","EOD-f-data")],
                                    "netIncome" => ["title" => __("Net Income","EOD-f-data")],
                                    "sellingGeneralAdministrative" => ["title" => __("Selling General Administrative","EOD-f-data")],
                                    "sellingAndMarketingExpenses" => ["title" => __("Selling and Marketing Expenses","EOD-f-data")],
                                    "grossProfit" => ["title" => __("Gross Profit","EOD-f-data")],
                                    "reconciledDepreciation" => ["title" => __("Reconciled Depreciation","EOD-f-data")],
                                    "ebit" => ["title" => __("EBIT","EOD-f-data")],
                                    "ebitda" => ["title" => __("EBITDA","EOD-f-data")],
                                    "depreciationAndAmortization" => ["title" => __("Depreciation and Amortization","EOD-f-data")],
                                    "nonOperatingIncomeNetOther" => ["title" => __("Non Operating Income Net Other","EOD-f-data")],
                                    "operatingIncome" => ["title" => __("Operating Income","EOD-f-data")],
                                    "otherOperatingExpenses" => ["title" => __("Other Operating Expenses","EOD-f-data")],
                                    "interestExpense" => ["title" => __("Interest Expense","EOD-f-data")],
                                    "taxProvision" => ["title" => __("Tax Provision","EOD-f-data")],
                                    "interestIncome" => ["title" => __("Interest Income","EOD-f-data")],
                                    "netInterestIncome" => ["title" => __("Net Interest Income","EOD-f-data")],
                                    "extraordinaryItems" => ["title" => __("Extraordinary Items","EOD-f-data")],
                                    "nonRecurring" => ["title" => __("Non Recurring","EOD-f-data")],
                                    "otherItems" => ["title" => __("Other Items","EOD-f-data")],
                                    "incomeTaxExpense" => ["title" => __("Income Tax Expense","EOD-f-data")],
                                    "totalRevenue" => ["title" => __("Total Revenue","EOD-f-data")],
                                    "totalOperatingExpenses" => ["title" => __("Total Operating Expenses","EOD-f-data")],
                                    "costOfRevenue" => ["title" => __("Cost of Revenue","EOD-f-data")],
                                    "totalOtherIncomeExpenseNet" => ["title" => __("Total Other Income Expense Net","EOD-f-data")],
                                    "discontinuedOperations" => ["title" => __("Discontinued Operations","EOD-f-data")],
                                    "netIncomeFromContinuingOps" => ["title" => __("Net Income From Continuing Ops","EOD-f-data")],
                                    "netIncomeApplicableToCommonShares" => ["title" => __("Net Income Applicable to Common Shares","EOD-f-data")],
                                    "preferredStockAndOtherAdjustments" => ["title" => __("Preferred Stock and Other Adjustments","EOD-f-data")],
                                ],
                            ),
                        ],
                    ),
                ),
            );
        }


        /**
         * Get array of cryptocurrency codes
         * @return array
         */
        public function get_cc_codes()
        {
            return ['1GOLD','1INCH','1WO','2GIVE','42','4ART','777','A','A5T','AAA','AAC','AAVE','ABAT','Abet','ABL','ABT','ABUSD','ABYSS','AC','ACA1','ACES','ACH','ACH1','ACM','ACOIN','ACS','ACT','ACTINIUM','ADA','ADABEAR','ADADOWN','ADAI','ADAUP','ADAX','ADB','ADC','ADEL','ADI','ADK','ADM','ADP','ADS','ADX','ADZ','AE','AEON','AERGO','AET','aEth','AFC1','AfroX','AGIX','AGVC','AIB','AIM','AION','AITRA','AK12','AKA','AKN','ALAYA','ALBT','ALCHEMY','ALEPH','ALG','ALGO','ALIAS','ALICE','ALINK','ALLBI','ALPA','ALPACA','ALY','AMA','AMB','AME','AMKR','AMLT','AMN','AMO','AMON','AMP','AMS','ANC','ANC1','ANG','ANI','ANT','ANY','AOA','AOG','APC','APE3','APIX','APL','APM','APOLLON-LIMASSOL','APPC','APT','APT21794','APY','AR','ARAW','ARB','ARB11841','ARCH','ARCO','ARDR','AREPA','ARGUS','ARIA20','ARK','ARMOR','ARO','ARPA','ARQ','ARRR','ARX','ASAFE','ASKO','ASM','ASP','AST','ASTR','ASTRO1','ASY','ATB','ATL','ATM1','ATMOS','ATOM','ATP','ATT','ATUSD','AUC','AUCTION','AUDIO','AUDT','AUR','AUSCM','AUTO1','AUTOFARM','AUX','AV','AVAX','AVT','AWG','AXE','AXEL','AXIOM','AXIS','AXPR','AXS','AZUKI','B20','B3X','BAC','BADGER','BAKE','BAL','BALI','BAMBOO','BAN','BANCA','BAND','BAO','BAS','BASIC','BAT','BBP','BBS','BC','BCA','BCD','BCDN','BCH','BCN','BCNT','BDCC','BDP','BDT','BEAM','BELA','BERN','BERRY','BEST','BETA1','BETH','BF','BFT','BGB','BHD','BHIG','BID','BIDAO','BIFI','BIGONE-TOKEN','BIP','BIR','BIS','BIT1','BITB','BITC','BITCNY','BITCOIN-CLASSIC','BITCOIN-FILE','BITCOINV','BITCOIVA','BITO','BITS','BITSTAR','BITSTEN-TOKEN','BIX','BIZZ','BKK','BLAZR','BLINK','BLK','BLOCK','BLTG','BLU','BLUR','BLY','BLZ','BMI','BMX','BNA','BNANA','BNB','BNBDOWN','BNBUP','BNOX','BNS','BNSD','BNT','BOLI','BOMB','BOND','BONDLY','BONO','BOSON','BOUTS','BPLC','BPS','BRD','BREW','BRY','BRZE','BSC','BSD','BSOV','BSP','BSTY','BSV','BSY','BT','BTA','BTC','BTC2','BTCB','BTCDOWN','BTCHG','BTCR','BTCST','BTCV','BTG','BTG1','BTM','BTO','BTR','BTRL','BTRS','BTS','BTSE','BTT','BTU','BTX','BTZC','BUB','BUNNY','BUP','BURN','BUSD','BUX','BUXCOIN','BUY','BUZZ','BVOL','BXA','BXC','C2','C20','C98','CAB','CAKE','CAMP','CANN','CARBON','CAS','CASH','CAT','CATT','CBANK','CBC','CBK','CBM','CBSE','CCA','CCO','CCX','CCXX','CDAI','CDEX','CEL','CENNZ','CERE','CF','CFX','CHAIN','CHART','CHAT','CHEESE','CHESS','CHESS10974','CHFT','CHI','CHP','CHR','CHSB','CHX','CHZ','CIX100','CJ','CKB','CLAM','CLIQ','CLO','CLOAK','CLR','CLT','CLUB','CLV','CLX','CMT','CNB','CND','CNFI','CNNC','CNNS','CNS','CNT','CNTM','CNX','COAL','COCOS','COFI','COLX','COMBO','COMET','COMP5692','CONTENTBOX','CONX','CORAL','CORE','CORN','CORX','COS','COSM','COT','COTI','COV','COVA','COVAL','CPOOL','CRBN','CRDT','CRE','CREAM','CREP','CRM','CRO','CRPT','CRU','CRV','CRW','CRYPTOBHARATCOIN','CSC','CSPR','CTCN','CTI','CTK','CTK4807','CTL','CTSI','CUBE1','CUDOS','CURE','CURIO-GOVERNANCE','CUSDC','CUSDT','CV','CVC','CVNT','CVP','CVR','CVX','CWBTC','CWS','CXC','CXO','CXT','CYL','D','DAAPL','DACC','DAD','DAI','DAPP','DAPPT','DAR','DARA','DARK-ENERGY-CRYSTALS','DASH','DASHG','DATP','DAV','DAX','DBC','DCN','DCNTR','DCR','DDD','DDIM','DDK','DDRT','DECURIAN','DEFI','DEFIBOX','DEGO','DEM','DENT','DEP','DEQ','DERI','DEUS','DEXE','DEXG','DFGL','DFL','DFT','DGB','DGC','DGD','DGP','DGX','DHT','DIA','DIAMOND','DIC','DIGEX','DIGG','DIGITAL-RESERVE-CURRENCY','DIME','DIP','DIPPER-NETWORK','DKA','DLC','DLT','DMD','DMG','DMS','DMT','DMTC','DMX','DNA','DNT','DOGE','DOKI','DONU','DOT','DOUGH','DPI','DRC','DREAMCOIN','DREP','DRGN','DRM','DSD','DTA','DTC','DTEP','DTX','DUCK','DUSD','DUSK','DVI','DVT','DX','DYDX','DYP','EARN-DEFI','EARN','EARNBET','EC','ECELL','ECO-VALUE-COIN','ECOIN','ECU','EDG','EDGELESS','EDI','EDRC','EFI','EGEM','EGG','EGLD','EKO','EKT','ELD','ELEC','ELF','EMC','EMC2','ENG','ENQ','ENS','ENT','EOS','EOSDT','EOST','EPAN','EPIC','EQUAD','ERC20','ERG','ES','ESD','ESRC','ESS','ESTI','ETC','ETGP','ETH','ETH20SMACO','ETHBTCRSI','ETHIX','ETHM','ETHMACOAPY','ETHO','ETHPA','ETHUP','ETHW','ETN','ETNX','ETP','EUC','EUNO','EURS','EURU','EVC','EVIL','EVN','EVX','EVZ','EWT','EXE','EXM','EXO','EXP','EXRN','EYES','EZ','F1C','FACEBOOK-TOKENIZED-STOCK-BITTREX','FAR','FARM','FAST','FB11308','FBN','FDZ','FET','FEX','FFYI','FIC','FIDA','FIL','FIN','FIRE-PROTOCOL','FIRMACHAIN','FIRO','FIS','FIT','FKX','FLEX','FLIXX','FLL','FLM1','FLO','FLOKI','FLOW','FLR','FMA','FNK','FNX','FOL','FOR','FORTH','FOUR','FOX','FRA','FRAX','FRC','FRED','FREE','FRN','FRST','FS','FSC','FSCC','FSHN','FSN','FSW','FTC','FTI','FTM','FTT','FTX','FTXT','FUEL','FUN','FUND','FUSE','FUZZ','FWT','FX','FX1','FXS','FYD','FYP','GAL','GAL11877','GALA','GAME','GARD','GAS','GB','GBX','GBYTE','GCN','GCR','GDAO','GDC','GEM','GEN','GENE1','GET','GFARM2','GGTK','GHOST','GHOSTPRISM','GHST','GIO','GLC','GLEEC','GLM','GLMR','GLS','GLT','GM','GME','GMNG','GMX','GMX11857','GNO','GNS','GNX','GNY','GOD','GOF','GOHELPFUND','GOLD','GOLDEN-TOKEN','GOSS','GPKR','GRAP','GRC','GRFT','GRIM-EVO','GRIN','GRLC','GRS','GSC','GSR','GSWAP','GTF','GTO','GTX','GUAP','GUCCIONECOIN','GUM','GUSD','GVT','GXC','GXT','GZIL','HAKKA','HANDY','HARD','HAVY','HB','HBAR','HBD','HBDC','HBN','HBO','HBTC','HBX','HEDG','HEGIC','HERB','HEX','HEZ','HFT','HFT22461','HGET','HH','HIBS','HIT','HMQ','HNC','HNS','HNST','HNT','HNY','HOGE','HOT','HOT1','HPS','HPT','HPY','HT','HTML','HTR','HUB','HUM','HUNT','HUSD','HUSH','HUSL','HVCO','HVN','HYDRA','HYDRO','HYVE','IBANK','IBP','IBS','IC','ICH','ICHI','ICOB','ICON','IDEA','IDEX','IDH','IDK','IDLE','IETHEREUM','IFT','IG','IHT','ILC','ILV','IMS','IMT','IND','INDEX','INJ','INK','INN','INSUR','INT','INTRATIO','INXT','ION','IOTX','IPL','IPX','IQ','IQCASH','IQN','IRA','IRD','IRIS','ISIKC','ISP','ISR','ITAM','IXC','IZE','JADE','JAR','JASMY','JBX','JCC','JET','JEWEL','JFC','JFI','JGN','JNTR','JOE','JOINT','JRT','JST','JUP','KAI','KAN','KARMA','KAT','KAVA','KCASH','KCS','KDA','KDAG','KEMA','KEYFI','KEYT','KGC','KIF','KIN','KISHU','KLAY','KLIMA','KLKS','KLP','KMD','KNC','KNDC','KNT','KOBO','KOK','KP3R','KRB','KRL','KRT','KSM','KSS','KURT','KUV','KXC','KZC','L2','LANA','LAZIO','LBA','LBC','LBK','LBTC','LBXC','LCG','LDN','LDO','LEAD','LEMO','LEO','LET','LGCY','LHB','LIBARTYSHARETOKEN','LIBFX','LIEN','LIKE','LINA','LINA7102','LINEAR','LINK','LINKA','LINKBEAR','LINKETHPA','LINKPT','LIT1','LITION','LIVE','LKN','LN','LNC','LNT','LOC','LOG','LOOM','LOON','LOV','LPOOL','LPT','LQDR','LRC','LSK','LSV','LTC','LTCR','LTCU','LTHN','LTK','LTO','LTX','LUA','LUN','LUNA1','LUNES','LUX','LYM','LYR','LYXe','MACH','MAID','MAN','MANA','MANNA','MAP','MAPR','MAPS','MARSCOIN','MARTK','MAS','MASK8536','MASQ','MASS','MATH','MATIC','MATTER','MAX-EXCHANGE-TOKEN','MAX','MAY','MBL','MBOX','MC','MCB','MCH','MCOBIT','MCPC','MDA','MDM','MDX','MED','MEDIC','MEMBRANA','MEME','MEMETIC','META','METIS','MFG','MICRO','MIDAS','MIN','MINA','MINI','MINTCOIN','MINTME','MIOTA','MIRROR','MITH','MITX','MIX','MKR','MLN','MLR','MM','MMO','MNTIS','MOB','MOC','MODEX','MODIC','MOF','MOJO','MOLK','MONA','MONAVALE','MORE','MORPHER','MOTA','MOTO','MOVR','MPH','MRPH','MRX','MSB','MSR','MST','MSTR','MSWAP','MT','MTA','MTH','MTL','MTLX','MTN','MTRG','MTS','MTV','MULTI','MUSE','MUST','MVEDA','MVP','MWC','MX','MXC','MYST','NANJ','NAS','NAV','NAVI','NAX','NBC','NBOT','NBXC','NCT','NDAU','NDX','NEAR','NEBL','NEBO','NEO','NESTEGG-COIN','NETKO','NEU','NEVA','NEW','NEXO','NFT9816','NFUP','NFXC','NGC','NIF','NIM','NKN','NLC2','NMC','NMR','NOIA','NOTE','NOW','NPXS','NRG','NRP','NSBT','NSURE','NTB','NTK','NTR','NTRN','NTX13198','NUT','NVDA','NWC','NXS','NXT','NYC','OBSR','OBTC','OCE','OCEAN','OCN','OCP','OCT','ODEM','OG','OGN','OGO','OIN','OK','OKT','OLT','OLY','OM','OMC','OMG','OMNI','ONC','ONE','ONES','ONION','ONS','ONT','ONTOLOGY-GAS','ONX','OP','OPAL','OPCT','OPEN-PLATFORM','OPNN','ORACOLXOR','ORAI','ORB','ORC','OSB','OSMO','OST','OTB','OTO','OUSD','OVR','OXT','PAC','PAID','PAK','PAN','PAR','PART','PASC','PAXG','PAY','PBR','PBTC','pBTC35A','PCX','PEAK','PEG','PERL','PERP','PERX','PEX','PHA','PHONEUM','PHR','PI','PICA','PIE','PINK','PIVX','PKB','PLANET','PLAT','PLAY','PLAYCHIP','PLAYDAPP','PLBT','PLC','PLEX','PLF','PLNC','PLR','PLU','PLURA','PLUS1','PMA','PNK','PNT','POA','POE','POL','POLIS','POLK','POLS','PONZI','POOL','POOLZ','POP-NETWORK-TOKEN','POST','POSW','POT','POWR','POX','PPAY','PPC','PPT','PRB19201','PRE','PREMIA','PRIX','PRO','PROB','PROM','PROS','PROXI','PRQ','PSG','PST','PTF','PTM','PUT','PVT','PXC','PXI','PYR','PYRK','PZM','QARK','QASH','QBC','QBZ','QI','QKC','QNT','QRDO','QRK','QRL','QRX','QSP','QTCON','QTUM','QUAN','R2R','RAE','RAK','RAMP','RANK','RARE','RARE1','RARI','RATECOIN','RATING','RAZOR','RBBT','RBIES','RBN','RBT','RBTC','RBY','RCN','RDD','RDN','RDNT','REAP','RED','REEF','REM','REN','RENBTC','REP','REQ','REV','REVV','RFOX','RFR','RGT','RIF','RIGEL','RINGX','RITO','RLC','RLY','RNDR','RNO','RNT','ROOBEE','ROOK','ROOM','ROUTE','ROX','ROYA','RPL','RSR','RTH','RUC','RUNE','RUP','RVC','RVN','RVT','RWN','RYO','S4F','SAFECOIN','SALE','SALT','SAN','SAND','SANDG','SASHIMI','SATT','SBD','SC','SCAP','SCRIV','SCS','SCSX','SDAO','SEELE','SEFA','SEND','SERO','SETH','SEX','SFCP','SFD','SFI','SFT','SFUND','SFX','SG','SHA','SHARE','SHB','SHD2','SHDW','SHIB','SHMN','SHND','SHROOM','SIB','SIGN','SIN','SIX','SKB','SKEY','SKI','SKIN','SKL','SKM','SKY','SLNV2','SLP','SLS','SMART','SMARTCREDIT','SMBSWAP','SMLY','SMT','SNB','SNC','SNET','SNM','SNN','SNRG','SNT','SNTVT','SOC','SOCC','SOCKS','SODA-COIN','SOL','SOLAPE','SOLID1','SOLO','SOLVE','SONG','SOP','SORA','SORA-VALIDATOR-TOKEN','SOS','SOUL','SPARTA','SPAZ','SPC','SPD','SPDR','SPELL','SPK','SPORTS','SPR','SQUID','SRN','SSP','SSV','STABLE-ASSET','STAKE','STAKECUBECOIN','START','STBZ','STC','STEEM','STIPEND','STM','STMX','STO','STOP','STORJ','STOX','STPT','STRAX','STRONG','STX','STX4847','SUB','SUI20947','SUMO','SUP','SUPER-BITCOIN','SUPERFARM','SURE','SUSD','SUSHI','SUTER','SWACE','SWC','SWING','SWINGBY','SWM','SWRV','SWT','SX','SXP','SYLO','SYS','SZC','TAG','TAJ','TAU','TBT','TBTC','TCT','TDP','TEL','TELOS','TEM','TEMCO','TEN','TERA','TERN','TFT','TFUEL','TH','THC','THE-BANK-COIN','THE','THETA','THORCHAIN-ERC20','TIGER','TIME','TIPS','TITAN','TKN','TKO','TKP','TKX','TLM','TMED','TMTG','TNB','TNC','TNS','TOC','TOKO','TOM','TOMO','TONCOIN','TONE','TONTOKEN','TOP','TOR','TOTO','TRA','TRAC','TRADE','TRAT','TRB','TRC','TRCL','TREX','TRIBE','TRINITY-NETWORK-CREDIT','TRISM','TRIX','TROLL','TRONBETDICE','TRP','TRST','TRUE','TRV','TRX','TRYB','TSHP','TSL','TTT','TUBE','TUNE','TUP','TUSD','TVK','TVNT','TWT','TX','TXL','TZC','UAT','UBEX','UBQ','UBT','UBTC','UBX','UCA','UCO','UFO','UFR','UGAS','UHP','UIP','UMA','UMB','UMX','UNCL','UNCX','UNFI','UNI','UNI7083','UNIFI-PROTOCOL','UNIFY','UNISTAKE','UNIT-PROTOCOL-DUCK','UNIT','UNN','UNO','UPI','UPX','USDF','USDK','USDP','USDT','USDU','USDX','UST','UTK','UTU','UUU','VAL','VALOR','VALUE','vBCH','VBIT','vDAI','VDL','vDOT','VEC2','VEE','VEIL','VELO','VEO','VERI','VEST','VET','VGX','VIA','VIB','VIBE','VID','VIDYX','VIDZ','VIG','VIKKY','VIVID','VKNF','vLINK','VLT','vLTC','VLX','VNDC','VOICE','VOLLAR','VOLT','VOLTZ','VOXEL','VRA','VRO','VRSC','VRX','VSP','VTC','VTHO','vUSDC','vUSDT','VVS','vXRP','WAB','WAN','WARP','WAVES','WAXE','WAXP','WBB','WBNB','WBTC','WBX','WELD','WEMIX','WEST','WGR','WGRT','WHALE','WHL','WHT','WICC','WIN','WINGS','WIS','WISE','WIX','WNXM','WOM','WOO','WOW','WOZX','WRC','WRLD','WTC','WXDAI','WXT','X42','XAG','XANK','XAP','XAS','XAU','XAUR','XAUT','XBC','XBI','XBP','XBTC21','XBY','XCASH','XCH','XCM','XCO','XCP','XCUR','XDAG','XDC','XDN','XED','XEM','XEN','XEP','XEQ','XFT','XFUEL','XFUND','XGM','XGS','XHI','XHV','XIO','XLA','XLAB','XLM','XLT','XMC','XMON','XMR','XMV','XMX','XMY','XNC','XNO','XNO1','XNODE','XNV','XOR','XPA','XPC','XPD','XPM','XPN','XPR','XPTX','XPX','XPY','XQC','XQN','XRC','XRP','XRPDOWN','XRU','XSGD','XSPC','XSR','XTP','XTZ','XUC','XUEZ','XUSD','XVG','XVS','XWC','XWP','XXA','XYO','YAM','YAMV1','YAX','YCC','YCE','YF-DAI','YFA','YFFII','YFI','YFID','YFII','YFO','YGG','YIELD','YLC','YOP','YOUC','YT','YTN','ZANO','ZAP','ZASH','ZCL','ZCR','ZEBI','ZEC','ZEE','ZEFU','ZEL','ZEN','ZENI','ZER','ZERO','ZET','ZIL','ZKS','ZLP','ZLW','ZMT','ZNT','ZNZ','ZOC','ZORA','ZPT','ZRX','ZUR','ZYD','ZYRO'];
        }

        /**
         * Get array of currency codes
         * @return array
         */
        public function get_forex_codes()
        {
            return ['ANG','ARS','AUD','BRL','CAD','CHF','CLP','CNY','CZK','DKK','EUR','GBP','HKD','HUF','IDR','ILS','INR','ISK','JPY','KRW','LKR','MXN','MXV','MYR','NGN','NOK','NZD','PEN','PHP','PKR','PLN','RON','RUB','SAR','SEK','SGD','THB','TOP','TRY','TWD','USD','UYU','VND','XDR','ZAC','ZAR'];
        }
    }
}


if(class_exists('EOD_API')) {
    function eod_api()
    {
        global $eod_api;

        // Instantiate only once.
        if ( ! isset( $eod_api ) ) {
            $eod_api = new EOD_API();
        }
        return $eod_api;
    }

    // Instantiate.
    eod_api();
}