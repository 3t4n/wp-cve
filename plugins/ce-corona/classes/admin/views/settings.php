<div class="wrap">
    <hr class="wp-header-end"/>
    <h1>Corona Virus Information</h1>
    <div class="ce-tab-menu">
        <ul class="nav-tab-wrapper ce-admin-tab-nav">
            <li class="nav-tab active" data-id="ce-general">General Information</li>
            <li data-id="ce-documentation" class="nav-tab">Documentation</li>
            <li data-id="ce-credits" class="nav-tab">Credits</li>
        </ul>
    </div>
    <div class="ce-tab-contents">
        <div id="ce-general" class="ce-tab-content active">
            <h3>Features of this Plugin</h3>
            <ul>
                <li>You can search by country.</li>
                <li>You can compare by country on a specific date.</li>
                <li>You have a WordPress Widget to use it in your site sidebar.</li>
                <li>This Plugin uses data from https://worldometers.info/coronavirus</li>
                <li>This Plugin is free to use.</li>
                <li>Review the plugin. <a href="https://wordpress.org/support/plugin/ce-corona/reviews/#new-post">Rate the plugin</a></li>
            </ul>
            <h4>What is a ‘novel’&nbsp;coronavirus?</h4>
            <p>A novel coronavirus (CoV) is a new strain of coronavirus.</p>

            <p>The disease caused by the novel coronavirus first identified in Wuhan, China, has been named&nbsp;<strong>coronavirus disease 2019 (COVID-19)</strong>&nbsp;– ‘CO’ stands for corona, ‘VI’ for virus, and ‘D’ for disease. Formerly, this disease was referred to as ‘2019 novel coronavirus’ or ‘2019-nCoV.’</p>

            <p>The COVID-19 virus is a new virus linked to the same family of viruses as Severe Acute Respiratory Syndrome (SARS) and some types of common cold.</p>

            <h2>What you can do regarding this period or what you should do regularly</h2>
            <ul class="cec-help-info">
                <li><span class="icon-box-facial-napkin-paper-tissue cec-admin-icon"></span> Use tissue while sneezing</li>
                <li><span class="icon-coronavirus-covid-19-infection-tourist-transmission-travel-virus cec-admin-icon"></span> Avoid Traveling</li>
                <li><span class="icon-covid-19-home-hygiene-prevent-protection-quarantine-stay cec-admin-icon"></span> Stay At Home</li>
                <li><span class="icon-covid-19-doctor-flu-mask-protection-wearing cec-admin-icon"></span> Use mask if you go out</li>
                <li><span class="icon-clean-hands-hygiene-soap-washing cec-admin-icon"></span> Wash your hands frequently with soap</li>
                <li><span class="icon-avoid-contact-covid-19-crowd-no-people cec-admin-icon"></span> Avoid Crowd</li>
                <li><span class="icon-advise-avatar-doctor-suggestion-warning cec-admin-icon"></span> Be Safe.</li>
            </ul>

        </div>
        <div id="ce-documentation" class="ce-tab-content">
            <h2>Hey there</h2>
            <p>This plugin is for showing Coronavirus Live data in your site. This can be through <strong>shortcode</strong> or <strong>elementor elements</strong> or you can use with <strong>WordPress Widget</strong> in your site sidebar.</p>

            <strong>New WordPress Widget Added</strong>

            <p>You can display data by Country with another <strong>shortcode</strong>. For Example: <code>[cec_corona country_code="BD"]</code></p>
            <hr>
            <p>You have three shortcode listed with some attributes.</p>
            <ul>
                <li><code>[ce_corona]</code> <strong>This for stats and global data</strong></li>
                <li><code>[cec_corona]</code> <strong>This for only country specific data</strong></li>
                <li><code>[cec_graph]</code> <strong>This for only graph comparison of new case, deaths, recovered data</strong></li>
            </ul>

            <mark>You can use both version with <strong>Elementor</strong></mark>
            <img src="<?php echo CE_CORONA_ASSETS . 'images/admin/elementor-elements.png'; ?>" alt="">
            <br>
            <hr>
            <h1>Shortcode One <mark><code>[ce_corona]</code></mark> How to use it.</h1>
            <p>with <code>code</code> you can use it in php code like this: <code>&lt;?php echo do_shotcode( '[ce_corona]' ); ?&gt;</code> along with all attributes.</p>
            <p>or you can use it in editor <code>[ce_corona]</code> like this.</p>
            <p>It takes below list as attributes.</p>
            <code><pre>
                'compare'         => true,
                'now'             => true,
                'data_table'      => true,
                'global_data'     => true,
                'lastupdate'      => true,
                'table_style'     => 'default',
                'button_position' => 'above_data_table',
                'stats_title'     => __( 'Total Stats', 'ce-corona' ),
                'compare_text'    => __( 'Compare Data by Country', 'ce-corona' ),
                'recent_text'     => __( 'Recent', 'ce-corona' ),
                'affected_title'  => __( 'Affected Countries', 'ce-corona' ),
                'active_title'    => __( 'Active Cases', 'ce-corona' ),
                'deaths_title'    => __( 'Total Deaths', 'ce-corona' ),
                'confirmed_title' => __( 'Confirmed Cases', 'ce-corona' ),
                'recovered_title' => __( 'Recovered', 'ce-corona' ),
                </pre></code>
            <p>For <mark>button_position</mark>, there is two defined value, 1. <code>above_data_table</code> 2. <code>inside_stats</code></p>
            <p>For <mark>table_style</mark>, there is two defined value, 1. <code>default</code> 2. <code>one</code></p>
            <h2>How to use with attributes.</h2>
            <p><strong>in php code: </strong> <code>&lt;?php echo do_shotcode( '[ce_corona data_table=false]' ); ?&gt;</code></p>
            <p><strong>in editor: </strong> <code>[ce_corona data_table=false]</code></p>
            <hr>
            <h1>Shortcode Two <mark><code>[cec_corona]</code></mark> How to use it.</h1>
            <p>with <code>code</code> you can use it in php code: <code>&lt;?php echo do_shotcode( '[cec_corona]' ); ?&gt;</code> along with all attributes.</p>
            <p>or you can use it in editor <code>[cec_corona]</code> like this.</p>
            <p>It takes below list as attributes.</p>
            <code><pre>
                'country_code'    => 'US',
                'flag'            => true,
                'states'          => false,
                'title'           => true,
                'country_name'    => true,
                'active_items'    => 'update_time, confirmed, recovered, deaths, todayCases, active, critical, todayDeaths, case_per_m, deaths_per_m, tests, tests_per_m',
                'updated_title'   => __( 'Last Updated', 'ce-corona' ),
                'active_title'    => __( 'Active Cases', 'ce-corona' ),
                'deaths_title'    => __( 'Total Deaths', 'ce-corona' ),
                'new_case_title'  => __( 'New Case', 'ce-corona' ),
                'confirmed_title' => __( 'Confirmed Cases', 'ce-corona' ),
                'recovered_title' => __( 'Total Recovered', 'ce-corona' ),
                'critical_title'  => __( 'in Critical', 'ce-corona' ),
                </pre></code>
                <p>For <mark>country_code</mark>, by default will display US data. If you want you can change it.</p>
                <p>For <mark>states</mark>, its enabled for only US states.</p>
                <p>For <mark>active_items</mark>, if you remove one of the word from the string than that box will be hide. <strong>For example: </strong> if you remove <code>critical</code> than <strong>critical</strong> data will not display</p>
                <h2>How to use with attributes.</h2>
                <p><strong>in php code: </strong> <code>&lt;?php echo do_shotcode( '[cec_corona flag=false country_code=BD]' ); ?&gt;</code></p>
                <p><strong>in editor: </strong> <code>[cec_corona flag=false country_code=BD]</code></p>
                <hr>
                <h1>Shortcode Three <mark><code>[cec_graph]</code></mark> How to use it.</h1>
                <p>with <code>code</code> you can use it in php code: <code>&lt;?php echo do_shotcode( '[cec_graph]' ); ?&gt;</code> along with all attributes.</p>
                <p>or you can use it in editor <code>[cec_graph]</code> like this.</p>
                <p>It takes below list as attributes.</p>
                <code><pre>
                'data'            => 'all',     // ISO2 or ISO3 ( eample: us,bd,it ) comma separated list
                'last'            => 7,
                'type'            => 'area',    // line, bar
                'legend'          => 'top',     // left, right, bottom
                'labels'          => "true",
                'title'           => __( 'Compare Cases by Region', 'ce-corona' ),
                'timeline'        => __( 'Timeline', 'ce-corona' ),
                'case_title'      => __( 'New Cases', 'ce-corona' ),
                'deaths_title'    => __( 'New Deaths', 'ce-corona' ),
                'recovered_title' => __( 'Recovered', 'ce-corona' ),
                    </pre></code>
                <h3>Country Supported List - with Code</h3>
                <code><pre>
            "AF" => "Afghanistan",
            "AL" => "Albania",
            "DZ" => "Algeria",
            "AS" => "American Samoa",
            "AD" => "Andorra",
            "AO" => "Angola",
            "AI" => "Anguilla",
            "AQ" => "Antarctica",
            "AG" => "Antigua and Barbuda",
            "AR" => "Argentina",
            "AM" => "Armenia",
            "AW" => "Aruba",
            "AU" => "Australia",
            "AT" => "Austria",
            "AZ" => "Azerbaijan",
            "BS" => "Bahamas",
            "BH" => "Bahrain",
            "BD" => "Bangladesh",
            "BB" => "Barbados",
            "BY" => "Belarus",
            "BE" => "Belgium",
            "BZ" => "Belize",
            "BJ" => "Benin",
            "BM" => "Bermuda",
            "BT" => "Bhutan",
            "BO" => "Bolivia",
            "BA" => "Bosnia and Herzegovina",
            "BW" => "Botswana",
            "BV" => "Bouvet Island",
            "BR" => "Brazil",
            "IO" => "British Indian Ocean Territory",
            "BN" => "Brunei Darussalam",
            "BG" => "Bulgaria",
            "BF" => "Burkina Faso",
            "BI" => "Burundi",
            "KH" => "Cambodia",
            "CM" => "Cameroon",
            "CA" => "Canada",
            "CV" => "Cape Verde",
            "KY" => "Cayman Islands",
            "CF" => "Central African Republic",
            "TD" => "Chad",
            "CL" => "Chile",
            "CN" => "China",
            "CX" => "Christmas Island",
            "CC" => "Cocos (Keeling) Islands",
            "CO" => "Colombia",
            "KM" => "Comoros",
            "CG" => "Congo",
            "CD" => "Congo, the Democratic Republic of the",
            "CK" => "Cook Islands",
            "CR" => "Costa Rica",
            "CI" => "Cote D'Ivoire",
            "HR" => "Croatia",
            "CU" => "Cuba",
            "CY" => "Cyprus",
            "CZ" => "Czech Republic",
            "DK" => "Denmark",
            "DJ" => "Djibouti",
            "DM" => "Dominica",
            "DO" => "Dominican Republic",
            "EC" => "Ecuador",
            "EG" => "Egypt",
            "SV" => "El Salvador",
            "GQ" => "Equatorial Guinea",
            "ER" => "Eritrea",
            "EE" => "Estonia",
            "ET" => "Ethiopia",
            "FK" => "Falkland Islands (Malvinas)",
            "FO" => "Faroe Islands",
            "FJ" => "Fiji",
            "FI" => "Finland",
            "FR" => "France",
            "GF" => "French Guiana",
            "PF" => "French Polynesia",
            "TF" => "French Southern Territories",
            "GA" => "Gabon",
            "GM" => "Gambia",
            "GE" => "Georgia",
            "DE" => "Germany",
            "GH" => "Ghana",
            "GI" => "Gibraltar",
            "GR" => "Greece",
            "GL" => "Greenland",
            "GD" => "Grenada",
            "GP" => "Guadeloupe",
            "GU" => "Guam",
            "GT" => "Guatemala",
            "GN" => "Guinea",
            "GW" => "Guinea-Bissau",
            "GY" => "Guyana",
            "HT" => "Haiti",
            "HM" => "Heard Island and Mcdonald Islands",
            "VA" => "Holy See (Vatican City State)",
            "HN" => "Honduras",
            "HK" => "Hong Kong",
            "HU" => "Hungary",
            "IS" => "Iceland",
            "IN" => "India",
            "ID" => "Indonesia",
            "IR" => "Iran, Islamic Republic of",
            "IQ" => "Iraq",
            "IE" => "Ireland",
            "IL" => "Israel",
            "IT" => "Italy",
            "JM" => "Jamaica",
            "JP" => "Japan",
            "JO" => "Jordan",
            "KZ" => "Kazakhstan",
            "KE" => "Kenya",
            "KI" => "Kiribati",
            "KP" => "Korea, Democratic People's Republic of",
            "KR" => "Korea, Republic of",
            "KW" => "Kuwait",
            "KG" => "Kyrgyzstan",
            "LA" => "Lao People's Democratic Republic",
            "LV" => "Latvia",
            "LB" => "Lebanon",
            "LS" => "Lesotho",
            "LR" => "Liberia",
            "LY" => "Libyan Arab Jamahiriya",
            "LI" => "Liechtenstein",
            "LT" => "Lithuania",
            "LU" => "Luxembourg",
            "MO" => "Macao",
            "MK" => "Macedonia, the Former Yugoslav Republic of",
            "MG" => "Madagascar",
            "MW" => "Malawi",
            "MY" => "Malaysia",
            "MV" => "Maldives",
            "ML" => "Mali",
            "MT" => "Malta",
            "MH" => "Marshall Islands",
            "MQ" => "Martinique",
            "MR" => "Mauritania",
            "MU" => "Mauritius",
            "YT" => "Mayotte",
            "MX" => "Mexico",
            "FM" => "Micronesia, Federated States of",
            "MD" => "Moldova, Republic of",
            "MC" => "Monaco",
            "MN" => "Mongolia",
            "MS" => "Montserrat",
            "MA" => "Morocco",
            "MZ" => "Mozambique",
            "MM" => "Myanmar",
            "NA" => "Namibia",
            "NR" => "Nauru",
            "NP" => "Nepal",
            "NL" => "Netherlands",
            "AN" => "Netherlands Antilles",
            "NC" => "New Caledonia",
            "NZ" => "New Zealand",
            "NI" => "Nicaragua",
            "NE" => "Niger",
            "NG" => "Nigeria",
            "NU" => "Niue",
            "NF" => "Norfolk Island",
            "MP" => "Northern Mariana Islands",
            "NO" => "Norway",
            "OM" => "Oman",
            "PK" => "Pakistan",
            "PW" => "Palau",
            "PS" => "Palestinian Territory, Occupied",
            "PA" => "Panama",
            "PG" => "Papua New Guinea",
            "PY" => "Paraguay",
            "PE" => "Peru",
            "PH" => "Philippines",
            "PN" => "Pitcairn",
            "PL" => "Poland",
            "PT" => "Portugal",
            "PR" => "Puerto Rico",
            "QA" => "Qatar",
            "RE" => "Reunion",
            "RO" => "Romania",
            "RU" => "Russian Federation",
            "RW" => "Rwanda",
            "SH" => "Saint Helena",
            "KN" => "Saint Kitts and Nevis",
            "LC" => "Saint Lucia",
            "PM" => "Saint Pierre and Miquelon",
            "VC" => "Saint Vincent and the Grenadines",
            "WS" => "Samoa",
            "SM" => "San Marino",
            "ST" => "Sao Tome and Principe",
            "SA" => "Saudi Arabia",
            "SN" => "Senegal",
            "CS" => "Serbia and Montenegro",
            "SC" => "Seychelles",
            "SL" => "Sierra Leone",
            "SG" => "Singapore",
            "SK" => "Slovakia",
            "SI" => "Slovenia",
            "SB" => "Solomon Islands",
            "SO" => "Somalia",
            "ZA" => "South Africa",
            "GS" => "South Georgia and the South Sandwich Islands",
            "ES" => "Spain",
            "LK" => "Sri Lanka",
            "SD" => "Sudan",
            "SR" => "Suriname",
            "SJ" => "Svalbard and Jan Mayen",
            "SZ" => "Swaziland",
            "SE" => "Sweden",
            "CH" => "Switzerland",
            "SY" => "Syrian Arab Republic",
            "TW" => "Taiwan",
            "TJ" => "Tajikistan",
            "TZ" => "Tanzania, United Republic of",
            "TH" => "Thailand",
            "TL" => "Timor-Leste",
            "TG" => "Togo",
            "TK" => "Tokelau",
            "TO" => "Tonga",
            "TT" => "Trinidad and Tobago",
            "TN" => "Tunisia",
            "TR" => "Turkey",
            "TM" => "Turkmenistan",
            "TC" => "Turks and Caicos Islands",
            "TV" => "Tuvalu",
            "UG" => "Uganda",
            "UA" => "Ukraine",
            "AE" => "United Arab Emirates",
            "GB" => "United Kingdom",
            "US" => "United States",
            "UM" => "United States Minor Outlying Islands",
            "UY" => "Uruguay",
            "UZ" => "Uzbekistan",
            "VU" => "Vanuatu",
            "VE" => "Venezuela",
            "VN" => "Viet Nam",
            "VG" => "Virgin Islands, British",
            "VI" => "Virgin Islands, U.s.",
            "WF" => "Wallis and Futuna",
            "EH" => "Western Sahara",
            "YE" => "Yemen",
            "ZM" => "Zambia",
            "ZW" => "Zimbabwe"
                </pre></code>
        </div>
        <div id="ce-credits" class="ce-tab-content">
            <p><strong>Data Credits: </strong> https://worldometers.info/coronavirus </p>
            <p><strong>API: </strong> https://github.com/pomber/covid19</p>
            <p><strong>API: </strong> https://github.com/NovelCOVID/API</p>
            <p><strong>API Privacy Policy:</strong> https://github.com/NovelCOVID/API/blob/master/privacy.md</p>
        </div>
    </div>    
</div>