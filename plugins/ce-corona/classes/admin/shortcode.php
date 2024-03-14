<?php
/**
 * Settings Class
 *
 * @package Corona
 */
namespace CoderExpert\Corona;

defined( 'ABSPATH' ) or exit;

class Shortcode {
    public static function init() {
        Loader::add_action( 'wp_enqueue_scripts', __CLASS__, 'scripts' );
        Loader::add_action( 'wp_ajax_save_corona_series', __CLASS__, 'save_data_series' );
        add_shortcode( 'ce_corona', [ __CLASS__, 'display' ] );
        add_shortcode( 'cec_corona', [ __CLASS__, 'display_country' ] );
        add_shortcode( 'cec_graph', [ __CLASS__, 'display_graph' ] );
    }
    public static function scripts( $hook ){
        $suffix = ! ( defined('CORONA_DEV') && CORONA_DEV ) ? '.min' : '';
        wp_register_style( 'ce-corona',
            CE_CORONA_ASSETS . 'css/corona.css', [],
            \filemtime( CE_CORONA_PATH . 'assets/css/corona.css' ), 'all'
        );
        wp_register_style( 'ce-corona-wp-widget',
            CE_CORONA_ASSETS . 'css/corona-wp-widget.css', [],
            \filemtime( CE_CORONA_PATH . 'assets/css/corona-wp-widget.css' ), 'all'
        );
        wp_register_script( 'ce-corona-jcountto',
            CE_CORONA_ASSETS . 'js/jquery-countTo.js',
            [ 'jquery' ],
            \filemtime( CE_CORONA_PATH . 'assets/js/jquery-countTo.js' ), true
        );
        wp_register_script( 'ce-corona-wp-widget',
            CE_CORONA_ASSETS . 'js/widget'. $suffix .'.js',
            [ 'jquery', 'ce-corona-jcountto' ],
            \filemtime( CE_CORONA_PATH . 'assets/js/widget'. $suffix .'.js' ), true
        );
        wp_register_script( 'ce-corona',
            CE_CORONA_ASSETS . 'js/corona'. $suffix .'.js',
            [ 'jquery', 'wp-i18n', 'wp-components' ],
            \filemtime( CE_CORONA_PATH . 'assets/js/corona'. $suffix .'.js' ), true
        );

        wp_register_style( 'ce-elementor-country-corona',
			CE_CORONA_ASSETS . 'css/corona-countrywise.css', [],
			\filemtime( CE_CORONA_PATH . 'assets/css/corona-countrywise.css' ), 'all'
		);
		wp_register_script( 'ce-elementor-corona-nformat',
			CE_CORONA_ASSETS . 'js/ce-numberformat.js',
			[ 'jquery' ],
			\filemtime( CE_CORONA_PATH . 'assets/js/ce-numberformat.js' ), true
		);
		wp_register_script( 'ce-elementor-country-corona',
			CE_CORONA_ASSETS . 'js/countrywise'. $suffix .'.js',
			[ 'jquery', 'wp-i18n', 'ce-elementor-corona-nformat' ],
			\filemtime( CE_CORONA_PATH . 'assets/js/countrywise'. $suffix .'.js' ), true
        );
		wp_register_style( 'cec-graph',
			CE_CORONA_ASSETS . 'css/cegraph.css', [],
			\filemtime( CE_CORONA_PATH . 'assets/css/cegraph.css' ), 'all'
        );
		wp_register_script( 'cec-graph',
			CE_CORONA_ASSETS . 'js/cegraph'. $suffix .'.js',
			[ 'jquery', 'ce-elementor-corona-nformat' ],
			\filemtime( CE_CORONA_PATH . 'assets/js/cegraph'. $suffix .'.js' ), true
        );
        wp_set_script_translations( 'ce-corona', 'ce-corona', plugin_dir_path( CE_CORONA_FILE ) . 'languages' );
        self::coronaTableTranslate();
    }

    public static function coronaTableTranslate ( $key = 'ce-corona' ){
        wp_localize_script( $key, 'CeCoronaDataTable', array(
            'logo'            => CE_CORONA_ASSETS . 'images/logo.png',
            'locale'          => \get_locale(),
            'last_update'     => __( 'Last Updated', 'ce-corona' ),
            'flag'            => __( 'Flag', 'ce-corona' ),
            'country'         => __( 'Country', 'ce-corona' ),
            'state'           => __( 'States', 'ce-corona' ),
            'total_cases'     => __( 'Total Cases', 'ce-corona' ),
            'confirmed_case'  => __( 'Confirmed Cases', 'ce-corona' ),
            'recovered'       => __( 'Recovered', 'ce-corona' ),
            'deaths'          => __( 'Deaths', 'ce-corona' ),
            'new_cases'       => __( 'New Cases', 'ce-corona' ),
            'total_deaths'    => __( 'Total Deaths', 'ce-corona' ),
            'new_deaths'      => __( 'New Deaths', 'ce-corona' ),
            'total_recovered' => __( 'Total Recovered', 'ce-corona' ),
            'active_case'     => __( 'Active', 'ce-corona' ),
            'in_critical'     => __( 'Critical', 'ce-corona' ),
            'total_tests'     => __( 'Total Tests', 'ce-corona' ),
            'tests_per_m'     => __( 'Tests/1M', 'ce-corona' ),
            'case_per_m'      => __( 'Case/1M', 'ce-corona' ),
            'deaths_per_m'    => __( 'Deaths/1M', 'ce-corona' ),
            'population'      => __( 'Population', 'ce-corona' ),
            'date'            => __( 'Date', 'ce-corona' ),
            'type_for_search' => __( 'Type for search...', 'ce-corona' ),
            'short_months'    => array(
                __( 'Jan', 'ce-corona' ),
                __( 'Feb', 'ce-corona' ),
                __( 'Mar', 'ce-corona' ),
                __( 'Apr', 'ce-corona' ),
                __( 'May', 'ce-corona' ),
                __( 'Jun', 'ce-corona' ),
                __( 'Jul', 'ce-corona' ),
                __( 'Aug', 'ce-corona' ),
                __( 'Sep', 'ce-corona' ),
                __( 'Oct', 'ce-corona' ),
                __( 'Nov', 'ce-corona' ),
                __( 'Dec', 'ce-corona' )
            ),
            'months'          => array(
                __( 'January', 'ce-corona' ),
                __( 'February', 'ce-corona' ),
                __( 'March', 'ce-corona' ),
                __( 'April', 'ce-corona' ),
                __( 'May', 'ce-corona' ),
                __( 'June', 'ce-corona' ),
                __( 'July', 'ce-corona' ),
                __( 'August', 'ce-corona' ),
                __( 'September', 'ce-corona' ),
                __( 'October', 'ce-corona' ),
                __( 'November', 'ce-corona' ),
                __( 'December', 'ce-corona' )
            ),
            'week_days'       => array(
                __( 'Sun', 'ce-corona' ),
                __( 'Mon', 'ce-corona' ),
                __( 'Tue', 'ce-corona' ),
                __( 'Wed', 'ce-corona' ),
                __( 'Thu', 'ce-corona' ),
                __( 'Fri', 'ce-corona' ),
                __( 'Sat', 'ce-corona' ),
            ),
            'week_days_full'       => array(
                __( 'Sunday', 'ce-corona' ),
                __( 'Monday', 'ce-corona' ),
                __( 'Tuesday', 'ce-corona' ),
                __( 'Wednesday', 'ce-corona' ),
                __( 'Thursday', 'ce-corona' ),
                __( 'Friday', 'ce-corona' ),
                __( 'Saturday', 'ce-corona' ),
            ),
            'countries'       => self::countries(),
            'reversed_countries'       => self::reversed_countries(),
            'graphsLabel' => array(
                "exportToSVG"   => __( "Download SVG", 'ce-corona' ),
                "exportToPNG"   => __( "Download PNG", 'ce-corona' ),
                "menu"          => __( "Menu", 'ce-corona' ),
                "selection"     => __( "Selection", 'ce-corona' ),
                "selectionZoom" => __( "Selection Zoom", 'ce-corona' ),
                "zoomIn"        => __( "Zoom In", 'ce-corona' ),
                "zoomOut"       => __( "Zoom Out", 'ce-corona' ),
                "pan"           => __( "Panning", 'ce-corona' ),
                "reset"         => __( "Reset Zoom", 'ce-corona' )
            )
        ));
    }

    public static function display( $atts, $content = null ) {
        wp_enqueue_style( 'ce-corona' );
        wp_enqueue_script( 'ce-corona' );

        \extract( shortcode_atts( array(
            'compare'         => true,
            'now'             => true,
            'data_table'      => true,
            'global_data'     => true,
            'lastupdate'      => true,
            'countries'       => '',
            'columns'         => 'cases,todayCases,deaths,todayDeaths,recovered,active,critical,tests,casesPerOneMillion,deathsPerOneMillion,testsPerOneMillion, population',
            'table_style'     => 'default',
            'button_position' => 'above_data_table',
            'stats_title'     => __( 'Total Stats', 'ce-corona' ),
            'compare_text'    => __( 'Compare Data by Country', 'ce-corona' ),
            'recent_text'     => __( 'Recent', 'ce-corona' ),
            'affected_title'  => __( 'Affected Countries', 'ce-corona' ),
            'active_title'    => __( 'Active Cases', 'ce-corona' ),
            'deaths_title'    => __( 'Total Deaths', 'ce-corona' ),
            'confirmed_title' => __( 'Confirmed Cases', 'ce-corona' ),
            'recovered_title' => __( 'Total Recovered', 'ce-corona' ),
        ), $atts, 'ce_corona') );

        wp_localize_script( 'ce-corona', 'CeCorona', array(
            'logo'            => CE_CORONA_ASSETS . 'images/logo.png',
            'coronaBG'        => CE_CORONA_ASSETS . 'images/corona-bg.jpg',
            'compareCountry'  => $compare == "true",
            'now'             => $now == "true",
            'countries'       => trim( str_replace(' ', '', $countries), " ,"),
            'columns'         => \explode(',', str_replace(' ', '', $columns)),
            'data_table'      => $data_table == "true",
            'global_data'     => $global_data == "true",
            'lastupdate'      => $lastupdate == "true",
            'stats_title'     => __( $stats_title, 'ce-corona' ),
            'recent_text'     => __( $recent_text, 'ce-corona' ),
            'compare_text'    => __( $compare_text, 'ce-corona' ),
            'affected_title'  => __( $affected_title, 'ce-corona' ),
            'active_title'    => __( $active_title, 'ce-corona' ),
            'deaths_title'    => __( $deaths_title, 'ce-corona' ),
            'confirmed_title' => __( $confirmed_title, 'ce-corona' ),
            'recovered_title' => __( $recovered_title, 'ce-corona' ),
            'button_position' => $button_position,
            'table_style'     => $table_style,
        ) );

        return '<div id="ce-corona" class="alignwide"></div>';
    }

    public static function display_country( $atts, $content = null ){
        wp_enqueue_style( 'ce-elementor-country-corona' );
        wp_enqueue_script( 'ce-elementor-corona-nformat' );
        wp_enqueue_script( 'ce-elementor-country-corona' );
        self::coronaTableTranslate( 'ce-elementor-country-corona' );

        $items = [ 'update_time', 'confirmed', 'recovered', 'deaths', 'todayCases', 'active', 'critical', 'todayDeaths', 'case_per_m', 'deaths_per_m', 'tests', 'tests_per_m' ];

        \extract( shortcode_atts( array(
            'country_code'       => 'US',
            'flag'               => true,
            'states'             => false,
            'table_style'        => 'default',
            'title'              => true,
            'country_name'       => true,
            'active_items'       => \implode(',', $items),
            'columns'            => 'cases,todayCases,deaths,todayDeaths,active,tests,testsPerOneMillion',
            'updated_title'      => __( 'Last Updated', 'ce-corona' ),
            'active_title'       => __( 'Active Cases', 'ce-corona' ),
            'deaths_title'       => __( 'Total Deaths', 'ce-corona' ),
            'todayDeaths_title'  => __( 'New Deaths', 'ce-corona' ),
            'new_case_title'     => __( 'New Case', 'ce-corona' ),
            'confirmed_title'    => __( 'Confirmed Cases', 'ce-corona' ),
            'recovered_title'    => __( 'Total Recovered', 'ce-corona' ),
            'critical_title'     => __( 'in Critical', 'ce-corona' ),
            'case_per_m_title'   => __( 'Case/1M', 'ce-corona' ),
            'deaths_per_m_title' => __( 'Deaths/1M', 'ce-corona' ),
            'tests_title'        => __( 'Total Tests', 'ce-corona' ),
            'tests_per_m_title'  => __( 'Tests/1M', 'ce-corona' ),
        ), $atts, 'cec_corona') );

        wp_localize_script( 'ce-elementor-country-corona', 'CeCorona', array(
            'logo'            => CE_CORONA_ASSETS . 'images/logo.png',
            'coronaBG'        => CE_CORONA_ASSETS . 'images/corona-bg.jpg',
            'compareCountry'  => "true",
            'table_style'     => $table_style,
            'states'          => $states,
            'columns'         => \explode(',', \str_replace(' ', '', $columns)),
            'now'             => "true",
            'data_table'      => "true",
            'global_data'     => "true",
            'lastupdate'      => "true",
            'stats_title'     => __( 'Total Stats', 'ce-corona' ),
            'recent_text'     => __( 'Recent', 'ce-corona' ),
            'compare_text'    => __( 'Compare By Country', 'ce-corona' ),
            'affected_title'  => __( 'Affected Countries', 'ce-corona' ),
            'active_title'    => __( $active_title, 'ce-corona' ),
            'deaths_title'    => __( $deaths_title, 'ce-corona' ),
            'confirmed_title' => __( $confirmed_title, 'ce-corona' ),
            'recovered_title' => __( $recovered_title, 'ce-corona' ),
            'button_position' => 'above_data_table',
        ) );

        $output = '<div id="cecShortcode">';
            $output .= '<div class="cec-elementor-country-wise cec-elementor-country-wise-loading alignwide" country_name="'. $country_code .'">';
                $output .= '<div class="cec-elementor-country-wise-inner">';
                $isUpdate = in_array( 'update_time', explode(',', $active_items) );
                if( $country_name == 'true' || $flag == "true" || $isUpdate ) {
                    $output .= '<div class="cec-elementor-country">';
                        if( $flag == "true" ) {
                            $output .= '<img class="cec-elementor-country-flag" src="https://raw.githubusercontent.com/disease-sh/API/master/public/assets/img/flags/unknown.png" alt="'. self::countries()[ $country_code ] .'" />';
                        }
                        if( $country_name == 'true' || $isUpdate ) {
                            $output .= '<div class="cec-elementor-country-name-wrapper">';
                                if( $country_name == 'true' ) {
                                    $output .= '<span class="cec-elementor-country-name">'. self::countries()[ $country_code ] .'</span>';
                                }
                                if( in_array( 'update_time', explode(',', $active_items) ) ) {
                                    $output .= '<span class="cec-elementor-updated-time-wrapper">'. $updated_title .': <span class="cec-elementor-updated-time">Loading...</span></span>';
                                }
                            $output .= '</div>';
                        }
                    $output .= '</div>';
                }
                    $output .= '<div class="clearfix cec-cn-case-wrapper">';
                        $output .= self::case_box( 'confirmed', $confirmed_title, 0, $active_items, $title );
                        $output .= self::case_box( 'todayCases', $new_case_title, 0, $active_items, $title );
                        $output .= self::case_box( 'deaths', $deaths_title, 0, $active_items, $title );
                        $output .= self::case_box( 'todayDeaths', $todayDeaths_title, 0, $active_items, $title );
                        $output .= self::case_box( 'recovered', $recovered_title, 0, $active_items, $title );
                        $output .= self::case_box( 'active', $active_title, 0, $active_items, $title );
                        $output .= self::case_box( 'critical', $critical_title, 0, $active_items, $title );
                        $output .= self::case_box( 'case_per_m', $case_per_m_title, 0, $active_items, $title );
                        $output .= self::case_box( 'deaths_per_m', $deaths_per_m_title, 0, $active_items, $title );
                        $output .= self::case_box( 'tests', $tests_title, 0, $active_items, $title );
                        $output .= self::case_box( 'tests_per_m', $tests_per_m_title, 0, $active_items, $title );
                    $output .= '</div>';
                $output .= '</div>';
            $output .= '</div>';
            if( $states == 'true' && ( $country_code == 'US' || $country_code == 'USA' ) ) {
                $output .= '<div id="cecStates"></div>';
            }
        $output .= '</div>';

        return $output;
    }

    public static function case_box( $key, $title, $value, $items, $hide_title ){
        $output = '';
        if( in_array( $key, explode(',', str_replace(' ', '', $items)) ) ) {
            $output .= '<div class="cec-cn-case-singe cec-cn-case-'. $key .'">';
                if( $hide_title == 'true' ) {
                    $output .= '<span class="cec-cn-title">' . __( $title, 'ce-corona' ) . '</span>';
                }
                $output .= '<span class="cec-cn-number">'. $value .'</span>';
            $output .= '</div>';
        }
        return $output;
    }

    public static function countries(){
        return array(
            "AF"  => __( "Afghanistan", 'ce-corona' ),
            "AL"  => __( "Albania", 'ce-corona' ),
            "DZ"  => __( "Algeria", 'ce-corona' ),
            "AS"  => __( "American Samoa", 'ce-corona' ),
            "AD"  => __( "Andorra", 'ce-corona' ),
            "AO"  => __( "Angola", 'ce-corona' ),
            "AI"  => __( "Anguilla", 'ce-corona' ),
            "AQ"  => __( "Antarctica", 'ce-corona' ),
            "AG"  => __( "Antigua and Barbuda", 'ce-corona' ),
            "AR"  => __( "Argentina", 'ce-corona' ),
            "AM"  => __( "Armenia", 'ce-corona' ),
            "AW"  => __( "Aruba", 'ce-corona' ),
            "AU"  => __( "Australia", 'ce-corona' ),
            "AT"  => __( "Austria", 'ce-corona' ),
            "AZ"  => __( "Azerbaijan", 'ce-corona' ),
            "BS"  => __( "Bahamas", 'ce-corona' ),
            "BH"  => __( "Bahrain", 'ce-corona' ),
            "BD"  => __( "Bangladesh", 'ce-corona' ),
            "BB"  => __( "Barbados", 'ce-corona' ),
            "BY"  => __( "Belarus", 'ce-corona' ),
            "BE"  => __( "Belgium", 'ce-corona' ),
            "BZ"  => __( "Belize", 'ce-corona' ),
            "BJ"  => __( "Benin", 'ce-corona' ),
            "BM"  => __( "Bermuda", 'ce-corona' ),
            "BT"  => __( "Bhutan", 'ce-corona' ),
            "BO"  => __( "Bolivia", 'ce-corona' ),
            "BA"  => __( "Bosnia and Herzegovina", 'ce-corona' ),
            "BW"  => __( "Botswana", 'ce-corona' ),
            "BV"  => __( "Bouvet Island", 'ce-corona' ),
            "BR"  => __( "Brazil", 'ce-corona' ),
            "IO"  => __( "British Indian Ocean Territory", 'ce-corona' ),
            "BN"  => __( "Brunei Darussalam", 'ce-corona' ),
            "BG"  => __( "Bulgaria", 'ce-corona' ),
            "BF"  => __( "Burkina Faso", 'ce-corona' ),
            "BI"  => __( "Burundi", 'ce-corona' ),
            "KH"  => __( "Cambodia", 'ce-corona' ),
            "CM"  => __( "Cameroon", 'ce-corona' ),
            "CA"  => __( "Canada", 'ce-corona' ),
            "CV"  => __( "Cape Verde", 'ce-corona' ),
            "KY"  => __( "Cayman Islands", 'ce-corona' ),
            "CF"  => __( "Central African Republic", 'ce-corona' ),
            "TD"  => __( "Chad", 'ce-corona' ),
            "CL"  => __( "Chile", 'ce-corona' ),
            "CN"  => __( "China", 'ce-corona' ),
            "CX"  => __( "Christmas Island", 'ce-corona' ),
            "CC"  => __( "Cocos (Keeling) Islands", 'ce-corona' ),
            "CO"  => __( "Colombia", 'ce-corona' ),
            "KM"  => __( "Comoros", 'ce-corona' ),
            "CG"  => __( "Congo", 'ce-corona' ),
            "CD"  => __( "Congo, the Democratic Republic of the", 'ce-corona' ),
            "CK"  => __( "Cook Islands", 'ce-corona' ),
            "CR"  => __( "Costa Rica", 'ce-corona' ),
            "CI"  => __( "Cote D'Ivoire", 'ce-corona' ),
            "HR"  => __( "Croatia", 'ce-corona' ),
            "CU"  => __( "Cuba", 'ce-corona' ),
            "CY"  => __( "Cyprus", 'ce-corona' ),
            "CZ"  => __( "Czech Republic", 'ce-corona' ),
            "DK"  => __( "Denmark", 'ce-corona' ),
            "DJ"  => __( "Djibouti", 'ce-corona' ),
            "DM"  => __( "Dominica", 'ce-corona' ),
            "DO"  => __( "Dominican Republic", 'ce-corona' ),
            "EC"  => __( "Ecuador", 'ce-corona' ),
            "EG"  => __( "Egypt", 'ce-corona' ),
            "SV"  => __( "El Salvador", 'ce-corona' ),
            "GQ"  => __( "Equatorial Guinea", 'ce-corona' ),
            "ER"  => __( "Eritrea", 'ce-corona' ),
            "EE"  => __( "Estonia", 'ce-corona' ),
            "ET"  => __( "Ethiopia", 'ce-corona' ),
            "FK"  => __( "Falkland Islands (Malvinas)", 'ce-corona' ),
            "FO"  => __( "Faroe Islands", 'ce-corona' ),
            "FJ"  => __( "Fiji", 'ce-corona' ),
            "FI"  => __( "Finland", 'ce-corona' ),
            "FR"  => __( "France", 'ce-corona' ),
            "GF"  => __( "French Guiana", 'ce-corona' ),
            "PF"  => __( "French Polynesia", 'ce-corona' ),
            "TF"  => __( "French Southern Territories", 'ce-corona' ),
            "GA"  => __( "Gabon", 'ce-corona' ),
            "GM"  => __( "Gambia", 'ce-corona' ),
            "GE"  => __( "Georgia", 'ce-corona' ),
            "DE"  => __( "Germany", 'ce-corona' ),
            "GH"  => __( "Ghana", 'ce-corona' ),
            "GI"  => __( "Gibraltar", 'ce-corona' ),
            "GR"  => __( "Greece", 'ce-corona' ),
            "GL"  => __( "Greenland", 'ce-corona' ),
            "GD"  => __( "Grenada", 'ce-corona' ),
            "GP"  => __( "Guadeloupe", 'ce-corona' ),
            "GU"  => __( "Guam", 'ce-corona' ),
            "GT"  => __( "Guatemala", 'ce-corona' ),
            "GN"  => __( "Guinea", 'ce-corona' ),
            "GW"  => __( "Guinea-Bissau", 'ce-corona' ),
            "GY"  => __( "Guyana", 'ce-corona' ),
            "HT"  => __( "Haiti", 'ce-corona' ),
            "HM"  => __( "Heard Island and Mcdonald Islands", 'ce-corona' ),
            "VA"  => __( "Vatican City State", 'ce-corona' ),
            "HN"  => __( "Honduras", 'ce-corona' ),
            "HK"  => __( "Hong Kong", 'ce-corona' ),
            "HU"  => __( "Hungary", 'ce-corona' ),
            "IS"  => __( "Iceland", 'ce-corona' ),
            "IN"  => __( "India", 'ce-corona' ),
            "ID"  => __( "Indonesia", 'ce-corona' ),
            "IR"  => __( "Iran", 'ce-corona' ),
            "IQ"  => __( "Iraq", 'ce-corona' ),
            "IE"  => __( "Ireland", 'ce-corona' ),
            "IL"  => __( "Israel", 'ce-corona' ),
            "IT"  => __( "Italy", 'ce-corona' ),
            "JM"  => __( "Jamaica", 'ce-corona' ),
            "JP"  => __( "Japan", 'ce-corona' ),
            "JO"  => __( "Jordan", 'ce-corona' ),
            "KZ"  => __( "Kazakhstan", 'ce-corona' ),
            "KE"  => __( "Kenya", 'ce-corona' ),
            "KI"  => __( "Kiribati", 'ce-corona' ),
            "KP"  => __( "Korea, Democratic People's Republic of", 'ce-corona' ),
            "KR"  => __( "Korea, Republic of", 'ce-corona' ),
            "KW"  => __( "Kuwait", 'ce-corona' ),
            "KG"  => __( "Kyrgyzstan", 'ce-corona' ),
            "LA"  => __( "Lao People's Democratic Republic", 'ce-corona' ),
            "LV"  => __( "Latvia", 'ce-corona' ),
            "LB"  => __( "Lebanon", 'ce-corona' ),
            "LS"  => __( "Lesotho", 'ce-corona' ),
            "LR"  => __( "Liberia", 'ce-corona' ),
            "LY"  => __( "Libyan Arab Jamahiriya", 'ce-corona' ),
            "LI"  => __( "Liechtenstein", 'ce-corona' ),
            "LT"  => __( "Lithuania", 'ce-corona' ),
            "LU"  => __( "Luxembourg", 'ce-corona' ),
            "MO"  => __( "Macao", 'ce-corona' ),
            "MK"  => __( "Macedonia, the Former Yugoslav Republic of", 'ce-corona' ),
            "MG"  => __( "Madagascar", 'ce-corona' ),
            "MW"  => __( "Malawi", 'ce-corona' ),
            "MY"  => __( "Malaysia", 'ce-corona' ),
            "MV"  => __( "Maldives", 'ce-corona' ),
            "ML"  => __( "Mali", 'ce-corona' ),
            "MT"  => __( "Malta", 'ce-corona' ),
            "MH"  => __( "Marshall Islands", 'ce-corona' ),
            "MQ"  => __( "Martinique", 'ce-corona' ),
            "MR"  => __( "Mauritania", 'ce-corona' ),
            "MU"  => __( "Mauritius", 'ce-corona' ),
            "YT"  => __( "Mayotte", 'ce-corona' ),
            "MX"  => __( "Mexico", 'ce-corona' ),
            "FM"  => __( "Micronesia, Federated States of", 'ce-corona' ),
            "MD"  => __( "Moldova, Republic of", 'ce-corona' ),
            "MC"  => __( "Monaco", 'ce-corona' ),
            "MN"  => __( "Mongolia", 'ce-corona' ),
            "MS"  => __( "Montserrat", 'ce-corona' ),
            "MA"  => __( "Morocco", 'ce-corona' ),
            "MZ"  => __( "Mozambique", 'ce-corona' ),
            "MM"  => __( "Myanmar", 'ce-corona' ),
            "NA"  => __( "Namibia", 'ce-corona' ),
            "NR"  => __( "Nauru", 'ce-corona' ),
            "NP"  => __( "Nepal", 'ce-corona' ),
            "NL"  => __( "Netherlands", 'ce-corona' ),
            "AN"  => __( "Netherlands Antilles", 'ce-corona' ),
            "NC"  => __( "New Caledonia", 'ce-corona' ),
            "NZ"  => __( "New Zealand", 'ce-corona' ),
            "NI"  => __( "Nicaragua", 'ce-corona' ),
            "NE"  => __( "Niger", 'ce-corona' ),
            "NG"  => __( "Nigeria", 'ce-corona' ),
            "NU"  => __( "Niue", 'ce-corona' ),
            "NF"  => __( "Norfolk Island", 'ce-corona' ),
            "MP"  => __( "Northern Mariana Islands", 'ce-corona' ),
            "NO"  => __( "Norway", 'ce-corona' ),
            "OM"  => __( "Oman", 'ce-corona' ),
            "PK"  => __( "Pakistan", 'ce-corona' ),
            "PW"  => __( "Palau", 'ce-corona' ),
            "PS"  => __( "Palestinian Territory, Occupied", 'ce-corona' ),
            "PA"  => __( "Panama", 'ce-corona' ),
            "PG"  => __( "Papua New Guinea", 'ce-corona' ),
            "PY"  => __( "Paraguay", 'ce-corona' ),
            "PE"  => __( "Peru", 'ce-corona' ),
            "PH"  => __( "Philippines", 'ce-corona' ),
            "PN"  => __( "Pitcairn", 'ce-corona' ),
            "PL"  => __( "Poland", 'ce-corona' ),
            "PT"  => __( "Portugal", 'ce-corona' ),
            "PR"  => __( "Puerto Rico", 'ce-corona' ),
            "QA"  => __( "Qatar", 'ce-corona' ),
            "RE"  => __( "Reunion", 'ce-corona' ),
            "RO"  => __( "Romania", 'ce-corona' ),
            "RU"  => __( "Russian Federation", 'ce-corona' ),
            "RW"  => __( "Rwanda", 'ce-corona' ),
            "SH"  => __( "Saint Helena", 'ce-corona' ),
            "KN"  => __( "Saint Kitts and Nevis", 'ce-corona' ),
            "LC"  => __( "Saint Lucia", 'ce-corona' ),
            "PM"  => __( "Saint Pierre and Miquelon", 'ce-corona' ),
            "VC"  => __( "Saint Vincent and the Grenadines", 'ce-corona' ),
            "WS"  => __( "Samoa", 'ce-corona' ),
            "SM"  => __( "San Marino", 'ce-corona' ),
            "ST"  => __( "Sao Tome and Principe", 'ce-corona' ),
            "SA"  => __( "Saudi Arabia", 'ce-corona' ),
            "SN"  => __( "Senegal", 'ce-corona' ),
            "CS"  => __( "Serbia and Montenegro", 'ce-corona' ),
            "SC"  => __( "Seychelles", 'ce-corona' ),
            "SL"  => __( "Sierra Leone", 'ce-corona' ),
            "SG"  => __( "Singapore", 'ce-corona' ),
            "SK"  => __( "Slovakia", 'ce-corona' ),
            "SI"  => __( "Slovenia", 'ce-corona' ),
            "SB"  => __( "Solomon Islands", 'ce-corona' ),
            "SO"  => __( "Somalia", 'ce-corona' ),
            "ZA"  => __( "South Africa", 'ce-corona' ),
            "GS"  => __( "South Georgia and the South Sandwich Islands", 'ce-corona' ),
            "ES"  => __( "Spain", 'ce-corona' ),
            "LK"  => __( "Sri Lanka", 'ce-corona' ),
            "SD"  => __( "Sudan", 'ce-corona' ),
            "SR"  => __( "Suriname", 'ce-corona' ),
            "SJ"  => __( "Svalbard and Jan Mayen", 'ce-corona' ),
            "SZ"  => __( "Swaziland", 'ce-corona' ),
            "SE"  => __( "Sweden", 'ce-corona' ),
            "CH"  => __( "Switzerland", 'ce-corona' ),
            "SY"  => __( "Syrian Arab Republic", 'ce-corona' ),
            "TW"  => __( "Taiwan", 'ce-corona' ),
            "TJ"  => __( "Tajikistan", 'ce-corona' ),
            "TZ"  => __( "Tanzania, United Republic of", 'ce-corona' ),
            "TH"  => __( "Thailand", 'ce-corona' ),
            "TL"  => __( "Timor-Leste", 'ce-corona' ),
            "TG"  => __( "Togo", 'ce-corona' ),
            "TK"  => __( "Tokelau", 'ce-corona' ),
            "TO"  => __( "Tonga", 'ce-corona' ),
            "TT"  => __( "Trinidad and Tobago", 'ce-corona' ),
            "TN"  => __( "Tunisia", 'ce-corona' ),
            "TR"  => __( "Turkey", 'ce-corona' ),
            "TM"  => __( "Turkmenistan", 'ce-corona' ),
            "TC"  => __( "Turks and Caicos Islands", 'ce-corona' ),
            "TV"  => __( "Tuvalu", 'ce-corona' ),
            "UG"  => __( "Uganda", 'ce-corona' ),
            "UA"  => __( "Ukraine", 'ce-corona' ),
            "AE"  => __( "United Arab Emirates", 'ce-corona' ),
            "GB"  => __( "United Kingdom", 'ce-corona' ),
            "US"  => __( "United States", 'ce-corona' ),
            "USA" => __( "United States", 'ce-corona' ),
            "UM"  => __( "United States Minor Outlying Islands", 'ce-corona' ),
            "UY"  => __( "Uruguay", 'ce-corona' ),
            "UZ"  => __( "Uzbekistan", 'ce-corona' ),
            "VU"  => __( "Vanuatu", 'ce-corona' ),
            "VE"  => __( "Venezuela", 'ce-corona' ),
            "VN"  => __( "Viet Nam", 'ce-corona' ),
            "VG"  => __( "Virgin Islands, British", 'ce-corona' ),
            "VI"  => __( "Virgin Islands, U.s.", 'ce-corona' ),
            "WF"  => __( "Wallis and Futuna", 'ce-corona' ),
            "EH"  => __( "Western Sahara", 'ce-corona' ),
            "YE"  => __( "Yemen", 'ce-corona' ),
            "ZM"  => __( "Zambia", 'ce-corona' ),
            "ZW"  => __( "Zimbabwe", 'ce-corona' ),
            'JE'  => __( 'Channel Islands', 'ce-corona' ),
            'RS'  => __( 'Serbia', 'ce-corona' ),
            'ME'  => __( 'Montenegro', 'ce-corona' ),
            'IM'  => __( 'Isle of Man', 'ce-corona' ),
            'SX'  => __( 'Sint Maarten', 'ce-corona' ),
            'MF'  => __( 'Saint Martin', 'ce-corona' ),
            'CW'  => __( 'Curaçao', 'ce-corona' ),
            'BL'  => __( 'St. Barth', 'ce-corona' ),
            'BQ'  => __( 'Caribbean Netherlands', 'ce-corona' ),
            'SS'  => __( 'South Sudan', 'ce-corona' ),
            'DP'  => __( 'Diamond Princess', 'ce-corona' ),
            'MSZ' => __( 'MS Zaandam', 'ce-corona' ),
        );
    }
    public static function reversed_countries(){
        return array(
            "Afghanistan"  => __( "Afghanistan", 'ce-corona' ),
            "Albania"  => __( "Albania", 'ce-corona' ),
            "Algeria"  => __( "Algeria", 'ce-corona' ),
            "American Samoa"  => __( "American Samoa", 'ce-corona' ),
            "Andorra"  => __( "Andorra", 'ce-corona' ),
            "Angola"  => __( "Angola", 'ce-corona' ),
            "Anguilla"  => __( "Anguilla", 'ce-corona' ),
            "Antarctica"  => __( "Antarctica", 'ce-corona' ),
            "Antigua and Barbuda"  => __( "Antigua and Barbuda", 'ce-corona' ),
            "Argentina"  => __( "Argentina", 'ce-corona' ),
            "Armenia"  => __( "Armenia", 'ce-corona' ),
            "Aruba"  => __( "Aruba", 'ce-corona' ),
            "Australia"  => __( "Australia", 'ce-corona' ),
            "Austria"  => __( "Austria", 'ce-corona' ),
            "Azerbaijan"  => __( "Azerbaijan", 'ce-corona' ),
            "Bahamas"  => __( "Bahamas", 'ce-corona' ),
            "Bahrain"  => __( "Bahrain", 'ce-corona' ),
            "Bangladesh"  => __( "Bangladesh", 'ce-corona' ),
            "Barbados"  => __( "Barbados", 'ce-corona' ),
            "Belarus"  => __( "Belarus", 'ce-corona' ),
            "Belgium"  => __( "Belgium", 'ce-corona' ),
            "Belize"  => __( "Belize", 'ce-corona' ),
            "Benin"  => __( "Benin", 'ce-corona' ),
            "Bermuda"  => __( "Bermuda", 'ce-corona' ),
            "Bhutan"  => __( "Bhutan", 'ce-corona' ),
            "Bolivia"  => __( "Bolivia", 'ce-corona' ),
            "Bosnia and Herzegovina"  => __( "Bosnia and Herzegovina", 'ce-corona' ),
            "Botswana"  => __( "Botswana", 'ce-corona' ),
            "Bouvet Island"  => __( "Bouvet Island", 'ce-corona' ),
            "Brazil"  => __( "Brazil", 'ce-corona' ),
            "British Indian Ocean Territory"  => __( "British Indian Ocean Territory", 'ce-corona' ),
            "Brunei Darussalam"  => __( "Brunei Darussalam", 'ce-corona' ),
            "Bulgaria"  => __( "Bulgaria", 'ce-corona' ),
            "Burkina Faso"  => __( "Burkina Faso", 'ce-corona' ),
            "Burundi"  => __( "Burundi", 'ce-corona' ),
            "Cambodia"  => __( "Cambodia", 'ce-corona' ),
            "Cameroon"  => __( "Cameroon", 'ce-corona' ),
            "Canada"  => __( "Canada", 'ce-corona' ),
            "Cape Verde"  => __( "Cape Verde", 'ce-corona' ),
            "Cayman Islands"  => __( "Cayman Islands", 'ce-corona' ),
            "Central African Republic"  => __( "Central African Republic", 'ce-corona' ),
            "Chad"  => __( "Chad", 'ce-corona' ),
            "Chile"  => __( "Chile", 'ce-corona' ),
            "China"  => __( "China", 'ce-corona' ),
            "Christmas Island"  => __( "Christmas Island", 'ce-corona' ),
            "Cocos (Keeling) Islands"  => __( "Cocos (Keeling) Islands", 'ce-corona' ),
            "Colombia"  => __( "Colombia", 'ce-corona' ),
            "Comoros"  => __( "Comoros", 'ce-corona' ),
            "Congo"  => __( "Congo", 'ce-corona' ),
            "Congo, the Democratic Republic of the"  => __( "Congo, the Democratic Republic of the", 'ce-corona' ),
            "Cook Islands"  => __( "Cook Islands", 'ce-corona' ),
            "Costa Rica"  => __( "Costa Rica", 'ce-corona' ),
            "Cote D'Ivoire"  => __( "Cote D'Ivoire", 'ce-corona' ),
            "Croatia"  => __( "Croatia", 'ce-corona' ),
            "Cuba"  => __( "Cuba", 'ce-corona' ),
            "Cyprus"  => __( "Cyprus", 'ce-corona' ),
            "Czech Republic"  => __( "Czech Republic", 'ce-corona' ),
            "Denmark"  => __( "Denmark", 'ce-corona' ),
            "Djibouti"  => __( "Djibouti", 'ce-corona' ),
            "Dominica"  => __( "Dominica", 'ce-corona' ),
            "Dominican Republic"  => __( "Dominican Republic", 'ce-corona' ),
            "Ecuador"  => __( "Ecuador", 'ce-corona' ),
            "Egypt"  => __( "Egypt", 'ce-corona' ),
            "El Salvador"  => __( "El Salvador", 'ce-corona' ),
            "Equatorial Guinea"  => __( "Equatorial Guinea", 'ce-corona' ),
            "Eritrea"  => __( "Eritrea", 'ce-corona' ),
            "Estonia"  => __( "Estonia", 'ce-corona' ),
            "Ethiopia"  => __( "Ethiopia", 'ce-corona' ),
            "Falkland Islands (Malvinas)"  => __( "Falkland Islands (Malvinas)", 'ce-corona' ),
            "Faroe Islands"  => __( "Faroe Islands", 'ce-corona' ),
            "Fiji"  => __( "Fiji", 'ce-corona' ),
            "Finland"  => __( "Finland", 'ce-corona' ),
            "France"  => __( "France", 'ce-corona' ),
            "French Guiana"  => __( "French Guiana", 'ce-corona' ),
            "French Polynesia"  => __( "French Polynesia", 'ce-corona' ),
            "French Southern Territories"  => __( "French Southern Territories", 'ce-corona' ),
            "Gabon"  => __( "Gabon", 'ce-corona' ),
            "Gambia"  => __( "Gambia", 'ce-corona' ),
            "Georgia"  => __( "Georgia", 'ce-corona' ),
            "Germany"  => __( "Germany", 'ce-corona' ),
            "Ghana"  => __( "Ghana", 'ce-corona' ),
            "Gibraltar"  => __( "Gibraltar", 'ce-corona' ),
            "Greece"  => __( "Greece", 'ce-corona' ),
            "Greenland"  => __( "Greenland", 'ce-corona' ),
            "Grenada"  => __( "Grenada", 'ce-corona' ),
            "Guadeloupe"  => __( "Guadeloupe", 'ce-corona' ),
            "Guam"  => __( "Guam", 'ce-corona' ),
            "Guatemala"  => __( "Guatemala", 'ce-corona' ),
            "Guinea"  => __( "Guinea", 'ce-corona' ),
            "Guinea-Bissau"  => __( "Guinea-Bissau", 'ce-corona' ),
            "Guyana"  => __( "Guyana", 'ce-corona' ),
            "Haiti"  => __( "Haiti", 'ce-corona' ),
            "Heard Island and Mcdonald Islands"  => __( "Heard Island and Mcdonald Islands", 'ce-corona' ),
            "Holy See (Vatican City State)"  => __( "Holy See (Vatican City State)", 'ce-corona' ),
            "Honduras"  => __( "Honduras", 'ce-corona' ),
            "Hong Kong"  => __( "Hong Kong", 'ce-corona' ),
            "Hungary"  => __( "Hungary", 'ce-corona' ),
            "Iceland"  => __( "Iceland", 'ce-corona' ),
            "India"  => __( "India", 'ce-corona' ),
            "Indonesia"  => __( "Indonesia", 'ce-corona' ),
            "Iran, Islamic Republic of"  => __( "Iran, Islamic Republic of", 'ce-corona' ),
            "Iraq"  => __( "Iraq", 'ce-corona' ),
            "Ireland"  => __( "Ireland", 'ce-corona' ),
            "Israel"  => __( "Israel", 'ce-corona' ),
            "Italy"  => __( "Italy", 'ce-corona' ),
            "Jamaica"  => __( "Jamaica", 'ce-corona' ),
            "Japan"  => __( "Japan", 'ce-corona' ),
            "Jordan"  => __( "Jordan", 'ce-corona' ),
            "Kazakhstan"  => __( "Kazakhstan", 'ce-corona' ),
            "Kenya"  => __( "Kenya", 'ce-corona' ),
            "Kiribati"  => __( "Kiribati", 'ce-corona' ),
            "Korea, Democratic People's Republic of"  => __( "Korea, Democratic People's Republic of", 'ce-corona' ),
            "Korea, Republic of"  => __( "Korea, Republic of", 'ce-corona' ),
            "Kuwait"  => __( "Kuwait", 'ce-corona' ),
            "Kyrgyzstan"  => __( "Kyrgyzstan", 'ce-corona' ),
            "Lao People's Democratic Republic"  => __( "Lao People's Democratic Republic", 'ce-corona' ),
            "Latvia"  => __( "Latvia", 'ce-corona' ),
            "Lebanon"  => __( "Lebanon", 'ce-corona' ),
            "Lesotho"  => __( "Lesotho", 'ce-corona' ),
            "Liberia"  => __( "Liberia", 'ce-corona' ),
            "Libyan Arab Jamahiriya"  => __( "Libyan Arab Jamahiriya", 'ce-corona' ),
            "Liechtenstein"  => __( "Liechtenstein", 'ce-corona' ),
            "Lithuania"  => __( "Lithuania", 'ce-corona' ),
            "Luxembourg"  => __( "Luxembourg", 'ce-corona' ),
            "Macao"  => __( "Macao", 'ce-corona' ),
            "Macedonia, the Former Yugoslav Republic of"  => __( "Macedonia, the Former Yugoslav Republic of", 'ce-corona' ),
            "Madagascar"  => __( "Madagascar", 'ce-corona' ),
            "Malawi"  => __( "Malawi", 'ce-corona' ),
            "Malaysia"  => __( "Malaysia", 'ce-corona' ),
            "Maldives"  => __( "Maldives", 'ce-corona' ),
            "Mali"  => __( "Mali", 'ce-corona' ),
            "Malta"  => __( "Malta", 'ce-corona' ),
            "Marshall Islands"  => __( "Marshall Islands", 'ce-corona' ),
            "Martinique"  => __( "Martinique", 'ce-corona' ),
            "Mauritania"  => __( "Mauritania", 'ce-corona' ),
            "Mauritius"  => __( "Mauritius", 'ce-corona' ),
            "Mayotte"  => __( "Mayotte", 'ce-corona' ),
            "Mexico"  => __( "Mexico", 'ce-corona' ),
            "Micronesia, Federated States of"  => __( "Micronesia, Federated States of", 'ce-corona' ),
            "Moldova, Republic of"  => __( "Moldova, Republic of", 'ce-corona' ),
            "Monaco"  => __( "Monaco", 'ce-corona' ),
            "Mongolia"  => __( "Mongolia", 'ce-corona' ),
            "Montserrat"  => __( "Montserrat", 'ce-corona' ),
            "Morocco"  => __( "Morocco", 'ce-corona' ),
            "Mozambique"  => __( "Mozambique", 'ce-corona' ),
            "Myanmar"  => __( "Myanmar", 'ce-corona' ),
            "Namibia"  => __( "Namibia", 'ce-corona' ),
            "Nauru"  => __( "Nauru", 'ce-corona' ),
            "Nepal"  => __( "Nepal", 'ce-corona' ),
            "Netherlands"  => __( "Netherlands", 'ce-corona' ),
            "Netherlands Antilles"  => __( "Netherlands Antilles", 'ce-corona' ),
            "New Caledonia"  => __( "New Caledonia", 'ce-corona' ),
            "New Zealand"  => __( "New Zealand", 'ce-corona' ),
            "Nicaragua"  => __( "Nicaragua", 'ce-corona' ),
            "Niger"  => __( "Niger", 'ce-corona' ),
            "Nigeria"  => __( "Nigeria", 'ce-corona' ),
            "Niue"  => __( "Niue", 'ce-corona' ),
            "Norfolk Island"  => __( "Norfolk Island", 'ce-corona' ),
            "Northern Mariana Islands"  => __( "Northern Mariana Islands", 'ce-corona' ),
            "Norway"  => __( "Norway", 'ce-corona' ),
            "Oman"  => __( "Oman", 'ce-corona' ),
            "Pakistan"  => __( "Pakistan", 'ce-corona' ),
            "Palau"  => __( "Palau", 'ce-corona' ),
            "Palestinian Territory, Occupied"  => __( "Palestinian Territory, Occupied", 'ce-corona' ),
            "Panama"  => __( "Panama", 'ce-corona' ),
            "Papua New Guinea"  => __( "Papua New Guinea", 'ce-corona' ),
            "Paraguay"  => __( "Paraguay", 'ce-corona' ),
            "Peru"  => __( "Peru", 'ce-corona' ),
            "Philippines"  => __( "Philippines", 'ce-corona' ),
            "Pitcairn"  => __( "Pitcairn", 'ce-corona' ),
            "Poland"  => __( "Poland", 'ce-corona' ),
            "Portugal"  => __( "Portugal", 'ce-corona' ),
            "Puerto Rico"  => __( "Puerto Rico", 'ce-corona' ),
            "Qatar"  => __( "Qatar", 'ce-corona' ),
            "Reunion"  => __( "Reunion", 'ce-corona' ),
            "Romania"  => __( "Romania", 'ce-corona' ),
            "Russian Federation"  => __( "Russian Federation", 'ce-corona' ),
            "Rwanda"  => __( "Rwanda", 'ce-corona' ),
            "Saint Helena"  => __( "Saint Helena", 'ce-corona' ),
            "Saint Kitts and Nevis"  => __( "Saint Kitts and Nevis", 'ce-corona' ),
            "Saint Lucia"  => __( "Saint Lucia", 'ce-corona' ),
            "Saint Pierre and Miquelon"  => __( "Saint Pierre and Miquelon", 'ce-corona' ),
            "Saint Vincent and the Grenadines"  => __( "Saint Vincent and the Grenadines", 'ce-corona' ),
            "Samoa"  => __( "Samoa", 'ce-corona' ),
            "San Marino"  => __( "San Marino", 'ce-corona' ),
            "Sao Tome and Principe"  => __( "Sao Tome and Principe", 'ce-corona' ),
            "Saudi Arabia"  => __( "Saudi Arabia", 'ce-corona' ),
            "Senegal"  => __( "Senegal", 'ce-corona' ),
            "Serbia and Montenegro"  => __( "Serbia and Montenegro", 'ce-corona' ),
            "Seychelles"  => __( "Seychelles", 'ce-corona' ),
            "Sierra Leone"  => __( "Sierra Leone", 'ce-corona' ),
            "Singapore"  => __( "Singapore", 'ce-corona' ),
            "Slovakia"  => __( "Slovakia", 'ce-corona' ),
            "Slovenia"  => __( "Slovenia", 'ce-corona' ),
            "Solomon Islands"  => __( "Solomon Islands", 'ce-corona' ),
            "Somalia"  => __( "Somalia", 'ce-corona' ),
            "South Africa"  => __( "South Africa", 'ce-corona' ),
            "South Georgia and the South Sandwich Islands"  => __( "South Georgia and the South Sandwich Islands", 'ce-corona' ),
            "Spain"  => __( "Spain", 'ce-corona' ),
            "Sri Lanka"  => __( "Sri Lanka", 'ce-corona' ),
            "Sudan"  => __( "Sudan", 'ce-corona' ),
            "Suriname"  => __( "Suriname", 'ce-corona' ),
            "Svalbard and Jan Mayen"  => __( "Svalbard and Jan Mayen", 'ce-corona' ),
            "Swaziland"  => __( "Swaziland", 'ce-corona' ),
            "Sweden"  => __( "Sweden", 'ce-corona' ),
            "Switzerland"  => __( "Switzerland", 'ce-corona' ),
            "Syrian Arab Republic"  => __( "Syrian Arab Republic", 'ce-corona' ),
            "Taiwan"  => __( "Taiwan", 'ce-corona' ),
            "Tajikistan"  => __( "Tajikistan", 'ce-corona' ),
            "Tanzania, United Republic of"  => __( "Tanzania, United Republic of", 'ce-corona' ),
            "Thailand"  => __( "Thailand", 'ce-corona' ),
            "Timor-Leste"  => __( "Timor-Leste", 'ce-corona' ),
            "Togo"  => __( "Togo", 'ce-corona' ),
            "Tokelau"  => __( "Tokelau", 'ce-corona' ),
            "Tonga"  => __( "Tonga", 'ce-corona' ),
            "Trinidad and Tobago"  => __( "Trinidad and Tobago", 'ce-corona' ),
            "Tunisia"  => __( "Tunisia", 'ce-corona' ),
            "Turkey"  => __( "Turkey", 'ce-corona' ),
            "Turkmenistan"  => __( "Turkmenistan", 'ce-corona' ),
            "Turks and Caicos Islands"  => __( "Turks and Caicos Islands", 'ce-corona' ),
            "Tuvalu"  => __( "Tuvalu", 'ce-corona' ),
            "Uganda"  => __( "Uganda", 'ce-corona' ),
            "Ukraine"  => __( "Ukraine", 'ce-corona' ),
            "United Arab Emirates"  => __( "United Arab Emirates", 'ce-corona' ),
            "United Kingdom"  => __( "United Kingdom", 'ce-corona' ),
            "US"  => __( "United States", 'ce-corona' ),
            "United States Minor Outlying Islands"  => __( "United States Minor Outlying Islands", 'ce-corona' ),
            "Uruguay"  => __( "Uruguay", 'ce-corona' ),
            "Uzbekistan"  => __( "Uzbekistan", 'ce-corona' ),
            "Vanuatu"  => __( "Vanuatu", 'ce-corona' ),
            "Venezuela"  => __( "Venezuela", 'ce-corona' ),
            "Viet Nam"  => __( "Viet Nam", 'ce-corona' ),
            "Virgin Islands, British"  => __( "Virgin Islands, British", 'ce-corona' ),
            "Virgin Islands, U.s."  => __( "Virgin Islands, U.s.", 'ce-corona' ),
            "Wallis and Futuna"  => __( "Wallis and Futuna", 'ce-corona' ),
            "Western Sahara"  => __( "Western Sahara", 'ce-corona' ),
            "Yemen"  => __( "Yemen", 'ce-corona' ),
            "Zambia"  => __( "Zambia", 'ce-corona' ),
            "Zimbabwe"  => __( "Zimbabwe", 'ce-corona' ),
        );
    }

    public static function display_graph( $atts, $content = null ){
        wp_enqueue_style( 'cec-graph' );
        wp_enqueue_script( 'cec-graph' );
        self::coronaTableTranslate( 'cec-graph' );

        \extract( shortcode_atts( array(
            'data'            => 'all',                                          // us,bd,it comma separated list
            'last'            => 7,
            'type'            => 'area',                                         // line, bar
            'legend'          => 'top',                                          // left, right, bottom
            'labels'          => "true",
            'title'           => __( 'Compare Cases by Region', 'ce-corona' ),
            'timeline'        => __( 'Timeline', 'ce-corona' ),
            'case_title'      => __( 'New Cases', 'ce-corona' ),
            'deaths_title'    => __( 'New Deaths', 'ce-corona' ),
            'recovered_title' => __( 'Recovered', 'ce-corona' ),
        ), $atts, 'cec_graph' ) );

        wp_localize_script( 'cec-graph', 'CeCorona', array(
            'data'            => $data,
            'last'            => intval( $last ) + 1,
            'type'            => $type,                                // line, bar
            'legend'          => $legend,                              // left, right, bottom
            'labels'          => $labels === "false" ? false : true,
            'title'           => $title,
            'timeline'        => $timeline,
            'case_title'      => $case_title,
            'deaths_title'    => $deaths_title,
            'recovered_title' => $recovered_title,
        ));
        return '<div class="cec-graph"></div>';
    }

    public static function save_data_series(){
        $series = isset( $_POST['series'] ) ? $_POST['series'] : [];
        // update_option( '' );
    }
}