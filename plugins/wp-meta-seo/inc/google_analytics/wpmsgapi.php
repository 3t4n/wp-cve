<?php
/**
 * Author: Alin Marcu
 * Author URI: https://deconf.com
 * Copyright 2013 Alin Marcu
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Modified by Joomunited
 */

// Exit if accessed directly

/* Prohibit direct script loading */
defined('ABSPATH') || die('No direct script access allowed!');

if (!class_exists('WpmsGapiController')) {
    require_once(WPMETASEO_PLUGIN_DIR . 'inc/class.metaseo-admin.php');

    /**
     * Class WpmsGapiController
     */
    class WpmsGapiController extends MetaSeoAdmin
    {
        /**
         * Google service analytics
         *
         * @var WPMSGoogle\Service\Analytics
         */
        public $service;
        /**
         * Google analytics 4 service
         *
         * @var WPMSGoogle\Service\AnalyticsData
         */
        public $service_ga4;
        /**
         * Time shift
         *
         * @var null
         */
        public $timeshift;
        /**
         * Managequota
         *
         * @var string
         */
        private $managequota;
        /**
         * Google analytics manager
         *
         * @var null|WpmsGaManager
         */
        private $wpmsga;

        /**
         * WpmsGapiController constructor.
         */
        public function __construct()
        {
            parent::__construct();
            $google_alanytics = get_option('wpms_google_alanytics');
            $this->wpmsga = WPMSGA();

            require_once WPMETASEO_PLUGIN_DIR . 'inc/google_analytics/wpmstools.php';
            $this->client = WpmsGaTools::initClient($google_alanytics['wpmsga_dash_clientid'], $google_alanytics['wpmsga_dash_clientsecret']);

            $this->setErrorTimeout();
            $this->managequota = 'u' . get_current_user_id() . 's' . get_current_blog_id();

            $this->service = new WPMSGoogle\Service\Analytics($this->client);
            $this->service_ga4 = new WPMSGoogle\Service\AnalyticsData($this->client);
            if (!empty($google_alanytics['googleCredentials'])) {
                $token = $google_alanytics['googleCredentials'];
                if ($token) {
                    try {
                        if (WpmsGaTools::isTokenExpired($token)) {
                            $token =  WpmsGaTools::getAccessTokenFromRefresh($google_alanytics['wpmsga_dash_clientid'], $google_alanytics['wpmsga_dash_clientsecret'], $token) ;
                        }
                        $this->client->setAccessToken($token);
                    } catch (WPMSGoogle\Service\Exception $e) {
                        WpmsGaTools::setCache(
                            'wpmsga_dash_lasterror',
                            date('Y-m-d H:i:s') . ': ' . esc_html('(' . $e->getCode() . ') ' . $e->getMessage()),
                            $this->error_timeout
                        );
                        WpmsGaTools::setCache(
                            'wpmsga_dash_gapi_errors',
                            $e->getCode(),
                            $this->error_timeout
                        );
                        $this->resetToken();
                    } catch (Exception $e) {
                        WpmsGaTools::setCache(
                            'wpmsga_dash_lasterror',
                            date('Y-m-d H:i:s') . ': ' . esc_html($e),
                            $this->error_timeout
                        );
                        $this->resetToken();
                    }
                }
            }
        }

        /**
         * Handles errors returned by GAPI Library
         *
         * @return boolean
         */
        public function gapiErrorsHandler()
        {
            $errors = WpmsGaTools::getCache('gapi_errors');
            if ($errors === false || !isset($errors[0])) { // invalid error
                return false;
            }
            if (isset($errors[1][0]['reason'])
                && ($errors[1][0]['reason'] === 'invalidCredentials'
                    || $errors[1][0]['reason'] === 'authError'
                    || $errors[1][0]['reason'] === 'insufficientPermissions'
                    || $errors[1][0]['reason'] === 'required'
                    || $errors[1][0]['reason'] === 'keyExpired')) {
                $this->resetToken(false);
                return true;
            }
            if (isset($errors[1][0]['reason'])
                && ($errors[1][0]['reason'] === 'userRateLimitExceeded'
                    || $errors[1][0]['reason'] === 'quotaExceeded')) {
                if ($this->wpmsga->config->options['api_backoff'] <= 5) {
                    usleep(rand(100000, 1500000));
                    return false;
                } else {
                    return true;
                }
            }
            if ((int)$errors[0] === 400 || (int)$errors[0] === 401 || (int)$errors[0] === 403) {
                return true;
            }
            return false;
        }

        /**
         * Calculates proper timeouts for each GAPI query
         *
         * @param string $daily Timeout
         *
         * @return number
         */
        public function getTimeouts($daily)
        {
            $local_time = time() + (int)$this->timeshift;
            if ($daily) {
                $nextday = explode('-', date('n-j-Y', strtotime(' +1 day', $local_time)));
                $midnight = mktime(0, 0, 0, $nextday[0], $nextday[1], $nextday[2]);
                return $midnight - $local_time;
            } else {
                $nexthour = explode('-', date('H-n-j-Y', strtotime(' +1 hour', $local_time)));
                $newhour = mktime($nexthour[0], 0, 0, $nexthour[1], $nexthour[2], $nexthour[3]);
                return $newhour - $local_time;
            }
        }

        /**
         * Handles the token reset process
         *
         * @return void
         */
        public function resetToken()
        {
            update_option('wpms_google_alanytics', array());
        }

        /**
         * Get and cache Core Reports
         *
         * @param string $projectId Unique table ID for retrieving Analytics data. Table ID is of the form ga:XXXX, where XXXX is the Analytics view (profile) ID.
         * @param string $from      Start date for fetching Analytics data. Requests can specify a start date formatted as YYYY-MM-DD, or as a relative date
         * @param string $to        End date for fetching Analytics data. Request can should specify an end date formatted as YYYY-MM-DD, or as a relative date
         * @param string $metrics   A comma-separated list of Analytics metrics.
         * @param array  $options   Optional parameters.
         * @param string $serial    Serial
         *
         * @return boolean|WPMSGoogle\Service\Analytics\GaData|integer|mixed
         */
        private function handleCorereports($projectId, $from, $to, $metrics, $options, $serial)
        {
            try {
                if ($from === 'today') {
                    $timeouts = 0;
                } else {
                    $timeouts = 1;
                }

               // WpmsGaTools::deleteCache($serial);
                $transient = WpmsGaTools::getCache($serial);
                if ($transient === false) {
                    if ($this->gapiErrorsHandler()) {
                        return -23;
                    }

                    $google_analytics = get_option('wpms_google_alanytics');
                    $profile_info = WpmsGaTools::getSelectedProfile($google_analytics['profile_list'], $google_analytics['tableid_jail']);
                    $property_type = isset($profile_info[4]) ? $profile_info[4] : '';

                    if ($property_type === 'UA') {
                        $data = $this->service->data_ga->get('ga:' . $projectId, $from, $to, $metrics, $options);
                    } else {
                        if ($metrics === 'bottomstats') {
                            $ga4_metrics_sessions = new WPMSGoogle\Service\AnalyticsData\Metric();
                            $ga4_metrics_sessions->setName('sessions');

                            $ga4_metrics_users = new WPMSGoogle\Service\AnalyticsData\Metric();
                            $ga4_metrics_users->setName('totalUsers');

                            $ga4_metrics_page_views = new WPMSGoogle\Service\AnalyticsData\Metric();
                            $ga4_metrics_page_views->setName('screenPageViews');

                            $ga4_metrics_engagement = new WPMSGoogle\Service\AnalyticsData\Metric();
                            $ga4_metrics_engagement->setName('engagementAVG');
                            $ga4_metrics_engagement->setExpression('userEngagementDuration/totalUsers');

                            $ga4_metrics_organic = new WPMSGoogle\Service\AnalyticsData\Metric();
                            $ga4_metrics_organic->setName('transactions');

                            $ga4_metrics_pageViewsPerSession = new WPMSGoogle\Service\AnalyticsData\Metric();
                            $ga4_metrics_pageViewsPerSession->setName('PageViewsPerSession');
                            $ga4_metrics_pageViewsPerSession->setExpression('screenPageViews/sessions');

                            $ga4_metrics = array($ga4_metrics_sessions, $ga4_metrics_users, $ga4_metrics_page_views, $ga4_metrics_engagement,
                                $ga4_metrics_organic, $ga4_metrics_pageViewsPerSession);
                        } elseif ($options['ga4_dimensions'] === 'visitorType') {
                            $ga4_metric_new_user = new WPMSGoogle\Service\AnalyticsData\Metric();
                            $ga4_metric_new_user->setName('newUsers');

                            $ga4_metric_total_users = new WPMSGoogle\Service\AnalyticsData\Metric();
                            $ga4_metric_total_users->setName('totalUsers');

                            $ga4_metrics = array($ga4_metric_new_user, $ga4_metric_total_users);
                        } elseif ($metrics === 'averageEngagementTime') {
                            $ga4_metrics = new WPMSGoogle\Service\AnalyticsData\Metric();
                            $ga4_metrics->setName('averageEngagementTime');
                            $ga4_metrics->setExpression('userEngagementDuration/totalUsers');
                        } else {
                            $ga4_metrics = new WPMSGoogle\Service\AnalyticsData\Metric();
                            $ga4_metrics->setName($metrics);
                        }

                        if ($options['ga4_dimensions'] === 'month_and_year') {
                            $dim_month = new WPMSGoogle\Service\AnalyticsData\Dimension();
                            $dim_month->setName('month');

                            $dim_year = new WPMSGoogle\Service\AnalyticsData\Dimension();
                            $dim_year->setName('year');

                            $ga4_dimensions = array($dim_month, $dim_year);
                        } else {
                            $dim = $options['ga4_dimensions'];
                            $ga4_dimensions = new WPMSGoogle\Service\AnalyticsData\Dimension();
                            $ga4_dimensions->setName($dim);
                        }

                        $date_range = new WPMSGoogle\Service\AnalyticsData\DateRange();
                        $date_range->setStartDate($from);
                        $date_range->setEndDate($to);

                        $request = new WPMSGoogle\Service\AnalyticsData\RunReportRequest();
                        $request->setMetrics(array($ga4_metrics));
                        $request->setDateRanges($date_range);
                        if ($metrics !== 'bottomstats' && $options['ga4_dimensions'] !== 'visitorType') {
                            $request->setDimensions($ga4_dimensions);
                        }

                        $data = $this->service_ga4->properties->runReport('properties/' . $projectId, $request);
                    }
                    WpmsGaTools::setCache($serial, $data, $this->getTimeouts($timeouts));
                } else {
                    $data = $transient;
                }
            } catch (WPMSGoogle\Service\Exception $e) {
                WpmsGaTools::setCache(
                    'last_error',
                    date('Y-m-d H:i:s') . ': ' . esc_html('(' . $e->getCode() . ') ' . $e->getMessage()),
                    $this->error_timeout
                );
                WpmsGaTools::setCache(
                    'gapi_errors',
                    $e->getCode(),
                    $this->error_timeout
                );
                return $e->getCode();
            } catch (Exception $e) {
                WpmsGaTools::setCache('last_error', date('Y-m-d H:i:s') . ': ' . esc_html($e), $this->error_timeout);
                return $e->getCode();
            }
            if ($data->getRows() > 0) {
                return $data;
            } else {
                return -21;
            }
        }

        /**
         * Generates serials for transients
         *
         * @param string $serial Serial
         *
         * @return string
         */
        public function getSerial($serial)
        {
            return sprintf('%u', crc32($serial));
        }

        /**
         * Analytics data for Area Charts (Admin Dashboard Widget report)
         *
         * @param string $projectId     Unique table ID for retrieving Analytics data. Table ID is of the form ga:XXXX, where XXXX is the Analytics view (profile) ID.
         * @param string $from          Start date for fetching Analytics data. Requests can specify a start date formatted as YYYY-MM-DD, or as a relative date
         * @param string $to            End date for fetching Analytics data. Request can should specify an end date formatted as YYYY-MM-DD, or as a relative date
         * @param string $query         Query
         * @param string $filter        Filter
         * @param string $property_type Google analytic property type Universal|GA4
         *
         * @return array|integer|string
         */
        private function getAreachartData($projectId, $from, $to, $query, $filter, $property_type)
        {
            switch ($query) {
                case 'users':
                    if ($property_type === 'GA4') {
                        $query = 'totalUsers'; // GA4 support
                    }                    $title = esc_html__('Users', 'wp-meta-seo');
                    break;
                case 'pageviews':
                    if ($property_type === 'GA4') {
                        $query = 'screenPageViews'; // GA4 support
                    }                    $title = esc_html__('Page Views', 'wp-meta-seo');
                    break;
                case 'visitBounceRate':
                    $title = esc_html__('Bounce Rate', 'wp-meta-seo');
                    if ($property_type === 'GA4') {
                        $query = 'averageEngagementTime'; // GA4 support: average engagement time
                        $title = esc_html__('AVG Engagement Time', 'wp-meta-seo');
                    }
                    break;
                case 'organicSearches':
                    $title = esc_html__('Organic Searches', 'wp-meta-seo');
                    if ($property_type === 'GA4') {
                        $query = 'transactions'; // GA4 support
                        $title = esc_html__('Transactions', 'wp-meta-seo');
                    }
                    break;
                case 'uniquePageviews':
                    $title = esc_html__('Unique Page Views', 'wp-meta-seo');
                    break;
                default:
                    $title = esc_html__('Sessions', 'wp-meta-seo');
            }
            if ($property_type === 'UA') {
                $metrics = 'ga:' . $query;
                if ($from === 'today' || $from === 'yesterday') {
                    $dimensions = 'ga:hour';
                    $dayorhour = esc_html__('Hour', 'wp-meta-seo');
                } elseif ($from === '365daysAgo' || $from === '1095daysAgo') {
                    $dimensions = 'ga:yearMonth, ga:month';
                    $dayorhour = esc_html__('Date', 'wp-meta-seo');
                } else {
                    $dimensions = 'ga:date,ga:dayOfWeekName';
                    $dayorhour = esc_html__('Date', 'wp-meta-seo');
                }
                $options = array('dimensions' => $dimensions, 'quotaUser' => $this->managequota . 'p' . $projectId);
                if ($filter) {
                    $options['filters'] = 'ga:pagePath==' . $filter;
                }
            } else {
                $metrics = $query;
                if ($from === 'today' || $from === 'yesterday') {
                    $dimensions = 'hour';
                    $dayorhour = esc_html__('Hour', 'wp-meta-seo');
                } elseif ($from === '365daysAgo' || $from === '1095daysAgo') {
                    $dimensions = 'month_and_year';
                    $dayorhour = esc_html__('Date', 'wp-meta-seo');
                } else {
                    $dimensions = 'date';
                    $dayorhour = esc_html__('Date', 'wp-meta-seo');
                }
                $options = array('ga4_dimensions' => $dimensions, 'quotaUser' => $this->managequota . 'p' . $projectId);
                if ($filter) {
                    $options['filters'] = 'pagePathPlusQueryString==' . $filter;
                }
            }

            $serial = 'qr2_' . $this->getSerial($projectId . $from . $metrics . $filter);
            $data = $this->handleCorereports($projectId, $from, $to, $metrics, $options, $serial);
            if (is_numeric($data)) {
                return $data;
            }
            $wpmsga_data = array(array($dayorhour, $title));
            if ($property_type === 'UA') {
                if ($from === 'today' || $from === 'yesterday') {
                    foreach ($data->getRows() as $row) {
                        $wpmsga_data[] = array((int)$row[0] . ':00', round($row[1], 2));
                    }
                } elseif ($from === '365daysAgo' || $from === '1095daysAgo') {
                    foreach ($data->getRows() as $row) {
                        /*
                         * translators:
                         * Example: 'F, Y' will become 'November, 2015'
                         * For details see: http://php.net/manual/en/function.date.php#refsect1-function.date-parameters
                         */
                        $wpmsga_data[] = array(
                            date_i18n(esc_html__('F, Y', 'wp-meta-seo'), strtotime($row[0] . '01')),
                            round($row[2], 2)
                        );
                    }
                } else {
                    foreach ($data->getRows() as $row) {
                        /*
                         * translators:
                         * Example: 'l, F j, Y' will become 'Thusday, November 17, 2015'
                         * For details see: http://php.net/manual/en/function.date.php#refsect1-function.date-parameters
                         */
                        $wpmsga_data[] = array(
                            date_i18n(esc_html__('l, F j, Y', 'wp-meta-seo'), strtotime($row[0])),
                            round($row[2], 2)
                        );
                    }
                }
            } else {
                if ($from === 'today' || $from === 'yesterday') {
                    for ($i = 1; $i <= 24; $i++) {
                        $wpmsga_data[] = array($i - 1 . ':00', round(0, 2));
                    }

                    if (!is_numeric($data->rowCount)) {
                        return $wpmsga_data;
                    }
                    foreach ($data->getRows() as $row) {
                        $dim = $row->getDimensionValues();
                        $dimensionValues = $dim[0]->getValue();
                        $metr = $row->getMetricValues();
                        $metricValues = $metr[0]->getValue();
                        $wpmsga_data[$dimensionValues + 1] = array((int)$dimensionValues . ':00', round($metricValues, 2));
                    }
                } elseif ($from === '365daysAgo' || $from === '1095daysAgo') {
                    $start_month = 0;
                    if ($from === '365daysAgo') {
                        $end_month = 12;
                    } else {
                        $end_month = 36;
                    }
                    $now_month = date('m');
                    $now_year = date('Y');

                    $month_index = $now_month;
                    $year_index = $now_year;
                    while ($start_month <= $end_month) {
                        $wpmsga_data_tmp[$year_index . str_pad($month_index, 2, '0', STR_PAD_LEFT)] = array(
                            date_i18n(esc_html__('F, Y', 'wp-meta-seo'), strtotime($year_index . str_pad($month_index, 2, '0', STR_PAD_LEFT) . '01')),
                            round(0, 2));
                        $month_index--;
                        if ($month_index === 0) {
                            $month_index = 12;
                            $year_index--;
                        }
                        /*$wpmsga_data_tmp[strtotime($now_month . '-' . $start_month . 'month')] = array(
                            date_i18n(esc_html__('F, Y', 'wp-meta-seo'), strtotime($now_month . '-' . $start_month . 'day')),
                            round(0, 2)
                        );*/
                        $start_month++;
                    }
                    $wpmsga_data_tmp = array_reverse($wpmsga_data_tmp, true);
                    foreach ($data->getRows() as $row) {
                        /*
                         * translators:
                         * Example: 'F, Y' will become 'November, 2015'
                         * For details see: http://php.net/manual/en/function.date.php#refsect1-function.date-parameters
                         */
                        $dim_y = $row->getDimensionValues();
                        $year_dim = $dim_y[1]->getValue();
                        $dim_m = $row->getDimensionValues();
                        $month_dim = $dim_m[0]->getValue();

                        $month_and_year = date_i18n(esc_html__('F, Y', 'wp-meta-seo'), strtotime($year_dim . $month_dim .  '01'));
                        $metr = $row->getMetricValues();
                        $metric_values = $metr[0]->getValue();
                        if (isset($wpmsga_data_tmp[$year_dim . $month_dim])) {
                            $wpmsga_data_tmp[$year_dim . $month_dim] = array(
                                $month_and_year,
                                round($metric_values, 2)
                            );
                        }
                    }

                    foreach ($wpmsga_data_tmp as $tmp) {
                        $wpmsga_data[] = $tmp;
                    }
                } else {
                    $end_date_index = 1;
                    switch ($from) {
                        case '7daysAgo':
                            $start_date_index = 7;
                            break;
                        case '14daysAgo':
                            $start_date_index = 14;
                            break;
                        case '30daysAgo':
                            $start_date_index = 30;
                            break;
                        case '90daysAgo':
                            $start_date_index = 90;
                            break;
                        default:
                            $start_date_index = 7;
                    }

                    $now_date = date('Ymd');

                    while ($start_date_index >= $end_date_index) {
                        $wpmsga_data_tmp[strtotime($now_date . '-' . $start_date_index . 'day')] = array(
                            date_i18n(esc_html__('l, F j, Y', 'wp-meta-seo'), strtotime($now_date . '-' . $start_date_index . 'day')),
                            round(0, 2)
                        );
                        $start_date_index--;
                    }
                    foreach ($data->getRows() as $row) {
                        /*
                         * translators:
                         * Example: 'l, F j, Y' will become 'Thusday, November 17, 2015'
                         * For details see: http://php.net/manual/en/function.date.php#refsect1-function.date-parameters
                         */
                        $dim = $row->getDimensionValues();
                        $dateValues = date_i18n(esc_html__('l, F j, Y', 'wp-meta-seo'), strtotime($dim[0]->getValue()));
                        $metr = $row->getMetricValues();
                        $metricValues = $metr[0]->getValue();
                        if (isset($wpmsga_data_tmp[strtotime($dim[0]->getValue())])) {
                            $wpmsga_data_tmp[strtotime($dim[0]->getValue())] = array(
                                $dateValues,
                                round($metricValues, 2)
                            );
                        }
                    }
                    foreach ($wpmsga_data_tmp as $tmp) {
                        $wpmsga_data[] = $tmp;
                    }
                }
            }

            return $wpmsga_data;
        }

        /**
         * Analytics data for Bottom Stats (bottom stats on main report)
         *
         * @param string $projectId     Unique table ID for retrieving Analytics data. Table ID is of the form ga:XXXX, where XXXX is the Analytics view (profile) ID.
         * @param string $from          Start date for fetching Analytics data. Requests can specify a start date formatted as YYYY-MM-DD, or as a relative date
         * @param string $to            End date for fetching Analytics data. Request can should specify an end date formatted as YYYY-MM-DD, or as a relative date
         * @param string $query         Query
         * @param string $filter        Filter
         * @param string $property_type Google analytic property type
         *
         * @return array|boolean|WPMSGoogle\Service\Analytics\GaData|integer|mixed
         */
        private function getNottomstats($projectId, $from, $to, $query, $filter = '', $property_type = 'UA')
        {
            if ($property_type === 'UA') {
                $options = array('dimensions' => null, 'quotaUser' => $this->managequota . 'p' . $projectId);
                if ($filter) {
                    $options['filters'] = 'ga:pagePath==' . $filter;
                    $metrics = 'ga:uniquePageviews,ga:users,ga:pageviews,ga:BounceRate,ga:organicSearches,ga:pageviewsPerSession';
                } else {
                    $metrics = 'ga:sessions,ga:users,ga:pageviews,ga:BounceRate,ga:organicSearches,ga:pageviewsPerSession';
                }
            } else {
                $options = array('quotaUser' => $this->managequota . 'p' . $projectId);
                $metrics = $query;
            }


            $serial = 'qr3_' . $this->getSerial($projectId . $from . $filter);
            $data = $this->handleCorereports($projectId, $from, $to, $metrics, $options, $serial);
            if (is_numeric($data)) {
                if ((int)$data === -21) {
                    return array_fill(0, 6, 0);
                } else {
                    return $data;
                }
            }
            $wpmsga_data = array();

            // i18n support
            if ($property_type === 'UA') {
                foreach ($data->getRows() as $row) {
                    $wpmsga_data = array_map('floatval', $row);
                }
            } else {
                if (!is_numeric($data->rowCount)) {
                    $wpmsga_data = array('0', '0', '0', '0', '0', '0');
                } else {
                    $get_row = $data->getRows();
                    $metric_data = $get_row[0];
                    foreach ($metric_data->getMetricValues() as $getMetricValue) {
                        $wpmsga_data[] = (int)$getMetricValue->getValue();
                    }
                }
            }
            $wpmsga_data[0] = number_format_i18n($wpmsga_data[0]); // sessions
            $wpmsga_data[1] = number_format_i18n($wpmsga_data[1]); // users
            $wpmsga_data[2] = number_format_i18n($wpmsga_data[2]); // page views
            $wpmsga_data[3] = number_format_i18n($wpmsga_data[3], 2); // bounce rate | engagement time
            $wpmsga_data[4] = number_format_i18n($wpmsga_data[4]); // organic search | transactions
            $wpmsga_data[5] = number_format_i18n($wpmsga_data[5], 2); // page/session

            return $wpmsga_data;
        }

        /**
         * Analytics data for Org Charts & Table Charts (content pages)
         *
         * @param string $projectId     Unique table ID for retrieving Analytics data. Table ID is of the form ga:XXXX, where XXXX is the Analytics view (profile) ID.
         * @param string $from          Start date for fetching Analytics data. Requests can specify a start date formatted as YYYY-MM-DD, or as a relative date
         * @param string $to            End date for fetching Analytics data. Request can should specify an end date formatted as YYYY-MM-DD, or as a relative date
         * @param string $filter        Filter
         * @param string $property_type Google analytic property type
         *
         * @return array|boolean|WPMSGoogle\Service\Analytics\GaData|integer|mixed
         */
        private function getContentPages($projectId, $from, $to, $filter = '', $property_type = 'UA')
        {
            if ($property_type === 'GA4') {
                $metrics = 'screenPageViews';
                $dimensions = 'pageTitle';
                $options = array(
                    'ga4_dimensions' => $dimensions,
                    'quotaUser' => $this->managequota . 'p' . $projectId
                );
            } else {
                $metrics = 'ga:pageviews';
                $dimensions = 'ga:pageTitle';
                $options = array(
                    'dimensions' => $dimensions,
                    'sort' => '-ga:pageviews',
                    'quotaUser' => $this->managequota . 'p' . $projectId
                );
                if ($filter) {
                    $options['filters'] = 'ga:pagePath==' . $filter;
                }
            }
            $serial = 'qr4_' . $this->getSerial($projectId . $from . $filter);
            $data = $this->handleCorereports($projectId, $from, $to, $metrics, $options, $serial);
            if (is_numeric($data)) {
                return $data;
            }
            $wpmsga_data = array(array(esc_html__('Pages', 'wp-meta-seo'), esc_html__('Views', 'wp-meta-seo')));
            foreach ($data->getRows() as $row) {
                if ($property_type === 'GA4') {
                    $dim = $row->getDimensionValues();
                    $metr = $row->getMetricValues();
                    $wpmsga_data[] = array(esc_html($dim[0]->getValue()), (int)$metr[0]->getValue());
                } else {
                    $wpmsga_data[] = array(esc_html($row[0]), (int)$row[1]);
                }
            }

            return $wpmsga_data;
        }

        /**
         * Analytics data for Org Charts & Table Charts (referrers)
         *
         * @param string $projectId     Unique table ID for retrieving Analytics data. Table ID is of the form ga:XXXX, where XXXX is the Analytics view (profile) ID.
         * @param string $from          Start date for fetching Analytics data. Requests can specify a start date formatted as YYYY-MM-DD, or as a relative date
         * @param string $to            End date for fetching Analytics data. Request can should specify an end date formatted as YYYY-MM-DD, or as a relative date
         * @param string $filter        Filter
         * @param string $property_type Google analytic property
         *
         * @return array|boolean|WPMSGoogle\Service\Analytics\GaData|integer|mixed
         */
        private function getReferrers($projectId, $from, $to, $filter = '', $property_type = 'UA')
        {
            if ($property_type === 'UA') {
                $metrics = 'ga:sessions';
                $dimensions = 'ga:source';
                $options = array(
                    'dimensions' => $dimensions,
                    'sort' => '-ga:sessions',
                    'quotaUser' => $this->managequota . 'p' . $projectId
                );
                if ($filter) {
                    $options['filters'] = 'ga:medium==referral;ga:pagePath==' . $filter;
                } else {
                    $options['filters'] = 'ga:medium==referral';
                }
            } else {
                $metrics = 'sessions';
                $dimensions = 'source';
                $options = array(
                    'ga4_dimensions' => $dimensions,
                    'quotaUser' => $this->managequota . 'p' . $projectId
                );
            }
            $serial = 'qr5_' . $this->getSerial($projectId . $from . $filter);
            $data = $this->handleCorereports($projectId, $from, $to, $metrics, $options, $serial);

            if (is_numeric($data)) {
                return $data;
            }
            $wpmsga_data = array(array(esc_html__('Referrers', 'wp-meta-seo'), esc_html__('Sessions', 'wp-meta-seo')));
            if ($property_type === 'UA') {
                foreach ($data->getRows() as $row) {
                    $wpmsga_data[] = array(esc_html($row[0]), (int)$row[1]);
                }
            } else {
                foreach ($data->getRows() as $row) {
                    $metr = $row->getMetricValues();
                    $metric_value = $metr[0]->getValue();
                    $dim = $row->getDimensionValues();
                    $dimension_value = $dim[0]->getValue();
                    $wpmsga_data[] = array(esc_html($dimension_value), (int)$metric_value);
                }
            }

            return $wpmsga_data;
        }

        /**
         * Analytics data for Org Charts & Table Charts (searches)
         *
         * @param string $projectId     Unique table ID for retrieving Analytics data. Table ID is of the form ga:XXXX, where XXXX is the Analytics view (profile) ID.
         * @param string $from          Start date for fetching Analytics data. Requests can specify a start date formatted as YYYY-MM-DD, or as a relative date
         * @param string $to            End date for fetching Analytics data. Request can should specify an end date formatted as YYYY-MM-DD, or as a relative date
         * @param string $filter        Filter
         * @param string $property_type Google analytic property
         *
         * @return array|boolean|WPMSGoogle\Service\Analytics\GaData|integer|mixed
         */
        private function getSearches($projectId, $from, $to, $filter = '', $property_type = 'UA')
        {
            if ($property_type === 'UA') {
                $metrics = 'ga:sessions';
                $dimensions = 'ga:keyword';
                $options = array(
                    'dimensions' => $dimensions,
                    'sort' => '-ga:sessions',
                    'quotaUser' => $this->managequota . 'p' . $projectId
                );
                if ($filter) {
                    $options['filters'] = 'ga:keyword!=(not set);ga:pagePath==' . $filter;
                } else {
                    $options['filters'] = 'ga:keyword!=(not set)';
                }
            } else {
                $metrics = 'sessions';
                $dimensions = 'sessionGoogleAdsKeyword';
                $options = array(
                    'ga4_dimensions' => $dimensions,
                    'quotaUser' => $this->managequota . 'p' . $projectId
                );
            }
            $serial = 'qr6_' . $this->getSerial($projectId . $from . $filter);
            $data = $this->handleCorereports($projectId, $from, $to, $metrics, $options, $serial);
            if (is_numeric($data)) {
                return $data;
            }

            $wpmsga_data = array(array(esc_html__('Searches', 'wp-meta-seo'), esc_html__('Sessions', 'wp-meta-seo')));
            if ($property_type === 'UA') {
                foreach ($data->getRows() as $row) {
                    $wpmsga_data[] = array(esc_html($row[0]), (int)$row[1]);
                }
            } else {
                foreach ($data->getRows() as $row) {
                    $metr = $row->getMetricValues();
                    $metric_value = $metr[0]->getValue();
                    $dim = $row->getDimensionValues();
                    $dimension_value = $dim[0]->getValue();
                    $wpmsga_data[] = array(esc_html($dimension_value), (int)$metric_value);
                }
            }

            return $wpmsga_data;
        }

        /**
         * Analytics data for Org Charts & Table Charts (location reports)
         *
         * @param string $projectId     Unique table ID for retrieving Analytics data. Table ID is of the form ga:XXXX, where XXXX is the Analytics view (profile) ID.
         * @param string $from          Start date for fetching Analytics data. Requests can specify a start date formatted as YYYY-MM-DD, or as a relative date
         * @param string $to            End date for fetching Analytics data. Request can should specify an end date formatted as YYYY-MM-DD, or as a relative date
         * @param string $filter        Filter
         * @param string $property_type Google analytic property
         *
         * @return array|boolean|WPMSGoogle\Service\Analytics\GaData|integer|mixed
         */
        private function getLocations($projectId, $from, $to, $filter = '', $property_type = 'UA')
        {
            $title = esc_html__('Countries', 'wp-meta-seo');
            $serial = 'qr7_' . $this->getSerial($projectId . $from . $filter);
            if ($property_type === 'UA') {
                $metrics = 'ga:sessions';
                $dimensions = 'ga:country';
                $local_filter = '';
                $options = array(
                    'dimensions' => $dimensions,
                    'sort' => '-ga:sessions',
                    'quotaUser' => $this->managequota . 'p' . $projectId
                );
                if ($filter) {
                    $options['filters'] = 'ga:pagePath==' . $filter;
                    if ($local_filter) {
                        $options['filters'] .= ';' . $local_filter;
                    }
                } else {
                    if ($local_filter) {
                        $options['filters'] = $local_filter;
                    }
                }
            } else {
                $metrics = 'sessions';
                $dimensions = 'country';
                $options = array(
                    'ga4_dimensions' => $dimensions,
                    'sort' => '-ga:sessions',
                    'quotaUser' => $this->managequota . 'p' . $projectId
                );
            }
            $data = $this->handleCorereports($projectId, $from, $to, $metrics, $options, $serial);
            if (is_numeric($data)) {
                return $data;
            }
            $wpmsga_data = array(array($title, esc_html__('Sessions', 'wp-meta-seo')));
            if ($property_type === 'UA') {
                foreach ($data->getRows() as $row) {
                    if (isset($row[2])) {
                        $wpmsga_data[] = array(esc_html($row[0]) . ', ' . esc_html($row[1]), (int)$row[2]);
                    } else {
                        $wpmsga_data[] = array(esc_html($row[0]), (int)$row[1]);
                    }
                }
            } else {
                foreach ($data->getRows() as $row) {
                    $dim = $row->getDimensionValues();
                    $dimensionValues = $dim[0]->getValue();
                    $metr = $row->getMetricValues();
                    $metricValues = $metr[0]->getValue();
                    $wpmsga_data[] = array(esc_html($dimensionValues), (int)$metricValues);
                }
            }
            return $wpmsga_data;
        }

        /**
         * Analytics data for Org Charts (traffic channels, device categories)
         *
         * @param string $projectId     Unique table ID for retrieving Analytics data. Table ID is of the form ga:XXXX, where XXXX is the Analytics view (profile) ID.
         * @param string $from          Start date for fetching Analytics data. Requests can specify a start date formatted as YYYY-MM-DD, or as a relative date
         * @param string $to            End date for fetching Analytics data. Request can should specify an end date formatted as YYYY-MM-DD, or as a relative date
         * @param string $query         Query
         * @param string $filter        Filter
         * @param string $property_type Google analytic property
         *
         * @return array|boolean|WPMSGoogle\Service\Analytics\GaData|integer|mixed
         */
        private function getOrgchartData($projectId, $from, $to, $query, $filter = '', $property_type = 'UA')
        {
            if ($property_type === 'UA') {
                $metrics = 'ga:sessions';
                $dimensions = 'ga:' . $query;
                $options = array(
                    'dimensions' => $dimensions,
                    'sort' => '-ga:sessions',
                    'quotaUser' => $this->managequota . 'p' . $projectId
                );
                if ($filter) {
                    $options['filters'] = 'ga:pagePath==' . $filter;
                }
            } else {
                if ($query === 'channelGrouping') {
                    $query = 'sessionDefaultChannelGrouping'; // support GA4
                }

                $metrics = 'sessions';
                $dimensions = $query;
                $options = array(
                    'ga4_dimensions' => $dimensions,
                    'quotaUser' => $this->managequota . 'p' . $projectId
                );
            }
            $serial = 'qr8_' . $this->getSerial($projectId . $from . $query . $filter);
            $data = $this->handleCorereports($projectId, $from, $to, $metrics, $options, $serial);
            if (is_numeric($data)) {
                return $data;
            }

            if ($property_type === 'UA') {
                $block = ($query === 'channelGrouping') ? esc_html__('Channels', 'wp-meta-seo') : esc_html__('Devices', 'wp-meta-seo');
                $wpmsga_data = array(
                    array(
                        '<div style="color:black; font-size:1.1em">' . $block . '</div>
<div style="color:darkblue; font-size:1.2em">' . (int)$data['totalsForAllResults']['ga:sessions'] . '</div>',
                        ''
                    )
                );
                foreach ($data->getRows() as $row) {
                    $shrink = explode(' ', $row[0]);
                    $wpmsga_data[] = array(
                        '<div style="color:black; font-size:1.1em">' . esc_html($shrink[0]) . '</div>
<div style="color:darkblue; font-size:1.2em">' . (int)esc_html($row[1]) . '</div>',
                        '<div style="color:black; font-size:1.1em">' . $block . '</div>
<div style="color:darkblue; font-size:1.2em">' . (int)esc_html($data['totalsForAllResults']['ga:sessions']) . '</div>'
                    );
                }
            } else {
                $block = ($query === 'sessionDefaultChannelGrouping') ? esc_html__('Channels', 'wp-meta-seo') : esc_html__('Devices', 'wp-meta-seo');
                $totalsForAllResults = 0;
                foreach ($data->getRows() as $row) {
                    $metr = $row->getMetricValues();
                    $totalsForAllResults += $metr[0]->getValue();
                }

                $wpmsga_data = array(
                    array(
                        '<div style="color:black; font-size:1.1em">' . $block . '</div>
<div style="color:darkblue; font-size:1.2em">' . (int)$totalsForAllResults . '</div>',
                        ''
                    )
                );
                foreach ($data->getRows() as $row) {
                    $dim = $row->getDimensionValues();
                    $dimension_value = $dim[0]->getValue();
                    $metr = $row->getMetricValues();
                    $metric_value = $metr[0]->getValue();
                    $shrink = explode(' ', $dimension_value);
                    $wpmsga_data[] = array(
                        '<div style="color:black; font-size:1.1em">' . esc_html($shrink[0]) . '</div>
<div style="color:darkblue; font-size:1.2em">' . (int)esc_html($metric_value) . '</div>',
                        '<div style="color:black; font-size:1.1em">' . $block . '</div>
<div style="color:darkblue; font-size:1.2em">' . (int)esc_html($totalsForAllResults) . '</div>'
                    );
                }
            }

            return $wpmsga_data;
        }

        /**
         * Analytics data for Pie Charts (traffic mediums,
         * serach engines, social networks, browsers, screen rsolutions, etc.)
         *
         * @param string $projectId     Unique table ID for retrieving Analytics data. Table ID is of the form ga:XXXX, where XXXX is the Analytics view (profile) ID.
         * @param string $from          Start date for fetching Analytics data. Requests can specify a start date formatted as YYYY-MM-DD, or as a relative date
         * @param string $to            End date for fetching Analytics data. Request can should specify an end date formatted as YYYY-MM-DD, or as a relative date
         * @param string $query         Query
         * @param string $filter        Filter
         * @param string $property_type Google analytic property
         *
         * @return array|boolean|WPMSGoogle\Service\Analytics\GaData|integer|mixed
         */
        private function getPiechartData($projectId, $from, $to, $query, $filter = '', $property_type = 'UA')
        {
            if ($property_type === 'UA') {
                $metrics = 'ga:sessions';
                $dimensions = 'ga:' . $query;

                if ($query === 'source') {
                    $options = array(
                        'dimensions' => $dimensions,
                        'sort' => '-ga:sessions',
                        'quotaUser' => $this->managequota . 'p' . $projectId
                    );
                    if ($filter) {
                        $options['filters'] = 'ga:medium==organic;ga:keyword!=(not set);ga:pagePath==' . $filter;
                    } else {
                        $options['filters'] = 'ga:medium==organic;ga:keyword!=(not set)';
                    }
                } else {
                    $options = array(
                        'dimensions' => $dimensions,
                        'sort' => '-ga:sessions',
                        'quotaUser' => $this->managequota . 'p' . $projectId
                    );
                    if ($filter) {
                        $options['filters'] = 'ga:' . $query . '!=(not set);ga:pagePath==' . $filter;
                    } else {
                        $options['filters'] = 'ga:' . $query . '!=(not set)';
                    }
                }
            } else {
                $metrics = 'sessions';
                if ($query === 'socialNetwork') {
                    $dimensions = 'adSourceName';
                } else {
                    $dimensions = $query;
                }
                $options = array(
                    'ga4_dimensions' => $dimensions,
                    'quotaUser' => $this->managequota . 'p' . $projectId
                );
            }
            $serial = 'qr10_' . $this->getSerial($projectId . $from . $query . $filter);
            $data = $this->handleCorereports($projectId, $from, $to, $metrics, $options, $serial);
            if (is_numeric($data)) {
                return $data;
            }
            $wpmsga_data = array(array(esc_html__('Type', 'wp-meta-seo'), esc_html__('Sessions', 'wp-meta-seo')));
            $i = 0;
            $included = 0;
            $others = 0;
            if ($property_type === 'UA') {
                foreach ($data->getRows() as $row) {
                    if ($i < 20) {
                        $wpmsga_data[] = array(str_replace('(none)', 'direct', esc_html($row[0])), (int)$row[1]);
                        $included += $row[1];
                        $i++;
                    } else {
                        break;
                    }
                }
                $totals = $data->getTotalsForAllResults();
                $others = $totals['ga:sessions'] - $included;
            } else {
                foreach ($data->getRows() as $row) {
                    if ($query === 'visitorType') {
                        $metr = $row->getMetricValues();
                        $newUsers = (int)$metr[0]->getValue();
                        if ($newUsers > 0) {
                            $wpmsga_data[] = array(esc_html__('New users', 'wp-meta-seo'), $newUsers);
                        }

                        $totalUsers = (int)$metr[1]->getValue();
                        if ($totalUsers > $newUsers) {
                            $wpmsga_data[] = array(esc_html__('Returning users', 'wp-meta-seo'), $totalUsers - $newUsers);
                        }

                        break;
                    }
                    $dimV = $row->getDimensionValues();
                    $dimension_value = $dimV[0]->getValue();
                    $metrV = $row->getMetricValues();
                    $metric_value = $metrV[0]->getValue();
                    if ($i < 20) {
                        $wpmsga_data[] = array(str_replace('(none)', 'direct', $dimension_value), (int)$metric_value);
                        $included += $metric_value;
                        $i++;
                    } elseif ($i >= 20 && $i < 30) {
                        $others += $metric_value;
                        $i++;
                    } else {
                        break;
                    }
                }
            }

            if ($others > 0) {
                $wpmsga_data[] = array(esc_html__('Other', 'wp-meta-seo'), $others);
            }

            return $wpmsga_data;
        }

        /**
         * Analytics data for Frontend Widget (chart data and totals)
         *
         * @param string $projectId Unique table ID for retrieving Analytics data. Table ID is of the form ga:XXXX, where XXXX is the Analytics view (profile) ID.
         * @param string $from      Start date for fetching Analytics data. Requests can specify a start date formatted as YYYY-MM-DD, or as a relative date
         * @param string $anonim    Anonim
         *
         * @return array|boolean|WPMSGoogle\Service\Analytics\GaData|integer|mixed
         */
        public function frontendWidgetStats($projectId, $from, $anonim)
        {
            $content = '';
            $to = 'yesterday';
            $metrics = 'ga:sessions';
            $dimensions = 'ga:date,ga:dayOfWeekName';
            $options = array('dimensions' => $dimensions, 'quotaUser' => $this->managequota . 'p' . $projectId);
            $serial = 'qr2_' . $this->getSerial($projectId . $from . $metrics);
            $data = $this->handleCorereports($projectId, $from, $to, $metrics, $options, $serial);
            if (is_numeric($data)) {
                return $data;
            }
            $wpmsga_data = array(array(esc_html__('Date', 'wp-meta-seo'), esc_html__('Sessions', 'wp-meta-seo')));
            $max = 1;
            if ($anonim) {
                $max_array = array();
                foreach ($data->getRows() as $item) {
                    $max_array[] = $item[2];
                }
                $max = max($max_array) ? max($max_array) : 1;
            }
            foreach ($data->getRows() as $row) {
                $wpmsga_data[] = array(
                    date_i18n(esc_html__('l, F j, Y', 'wp-meta-seo'), strtotime($row[0])),
                    ($anonim ? round($row[2] * 100 / $max, 2) : (int)$row[2])
                );
            }
            $totals = $data->getTotalsForAllResults();
            return array($wpmsga_data, $anonim ? 0 : number_format_i18n($totals['ga:sessions']));
        }

        /**
         * Analytics data for Realtime component (the real-time report)
         *
         * @param string $projectId     Unique table ID for retrieving Analytics data. Table ID is of the form ga:XXXX, where XXXX is the Analytics view (profile) ID.
         * @param string $property_type Google analytic property
         *
         * @return array|integer|mixed
         */
        private function getRealtime($projectId, $property_type)
        {
            if ($property_type === 'GA4') {
                // Create the Metrics object.
                $metric_active_users = new WPMSGoogle\Service\AnalyticsData\Metric();
                $metric_active_users->setName('activeUsers');

                $metric_count_page_view = new WPMSGoogle\Service\AnalyticsData\Metric();
                $metric_count_page_view->setName('screenPageViews');

                // Create the Dimension object
                $dimension_all_active_users = new WPMSGoogle\Service\AnalyticsData\Dimension();
                $dimension_all_active_users->setName('audienceName');

                $dimension_page_title = new WPMSGoogle\Service\AnalyticsData\Dimension();
                $dimension_page_title->setName('unifiedScreenName');

                // Create the Request object
                $request_all_active_users = new WPMSGoogle\Service\AnalyticsData\RunRealtimeReportRequest();
                $request_all_active_users->setMetrics($metric_active_users);
                $request_all_active_users->setDimensions($dimension_all_active_users);

                $request_all_page_title = new WPMSGoogle\Service\AnalyticsData\RunRealtimeReportRequest();
                $request_all_page_title->setMetrics($metric_count_page_view);
                $request_all_page_title->setDimensions($dimension_page_title);
            } else {
                $metrics = 'rt:activeUsers';
                $dimensions = 'rt:pagePath,rt:source,rt:keyword,rt:trafficType,rt:visitorType,rt:pageTitle';
            }
            try {
                $serial = 'qr_realtimecache_' . $this->getSerial($projectId);
                $transient = WpmsGaTools::getCache($serial);
                if ($transient === false) {
                    if ($this->gapiErrorsHandler()) {
                        return -23;
                    }
                    if ($property_type === 'GA4') {
                        $data_1 = $this->service_ga4->properties->runRealtimeReport('properties/' . $projectId, $request_all_active_users);
                        $data_2 = $this->service_ga4->properties->runRealtimeReport('properties/' . $projectId, $request_all_page_title);
                    } else {
                        $data = $this->service->data_realtime->get(
                            'ga:' . $projectId,
                            $metrics,
                            array(
                                'dimensions' => $dimensions,
                                'quotaUser' => $this->managequota . 'p' . $projectId
                            )
                        );
                    }
                    WpmsGaTools::setCache($serial, $data, 55);
                } else {
                    $data = $transient;
                }
            } catch (WPMSGoogle_Service_Exception $e) {
                WpmsGaTools::setCache(
                    'last_error',
                    date('Y-m-d H:i:s') . ': ' . esc_html('(' . $e->getCode() . ') ' . $e->getMessage()),
                    $this->error_timeout
                );
                WpmsGaTools::setCache(
                    'gapi_errors',
                    $e->getCode(),
                    $this->error_timeout
                );
                return $e->getCode();
            } catch (Exception $e) {
                WpmsGaTools::setCache(
                    'last_error',
                    date('Y-m-d H:i:s') . ': ' . esc_html($e),
                    $this->error_timeout
                );
                return $e->getCode();
            }

            if ($property_type === 'GA4') {
                foreach ($data_1->getRows() as $row) {
                    $data_active_users[] = $row;
                }

                foreach ($data_2->getRows() as $row) {
                    $data_page_title[] = $row;
                }
                $wpmsga_data = array_merge($data_active_users, $data_page_title);
                if (empty($wpmsga_data[0])) {
                    return -21;
                }
            } else {
                if ($data->getRows() < 1) {
                    return -21;
                }
                $i = 0;
                $wpmsga_data = $data;
                foreach ($data->getRows() as $row) {
                    $wpmsga_data->rows[$i] = array_map('esc_html', $row);
                    $i++;
                }
            }
            return array($wpmsga_data);
        }

        /**
         * Handles ajax requests and calls the needed methods
         *
         * @param string         $projectId     Unique table ID for retrieving Analytics data. Table ID is of the form ga:XXXX, where XXXX is the Analytics view (profile)
         * @param string         $query         QueryID.
         * @param boolean|string $from          Start date for fetching Analytics data. Requests can specify a start date formatted as YYYY-MM-DD, or as a relative date
         * @param boolean|string $to            End date for fetching Analytics data. Request can should specify an end date formatted as YYYY-MM-DD, or as a relative date
         * @param string         $filter        Filter
         * @param string         $property_type Google analytic property
         *
         * @return array|boolean|WPMSGoogle\Service\Analytics\GaData|integer|mixed
         */
        public function get($projectId, $query, $from = false, $to = false, $filter = '', $property_type = 'UA')
        {
            if (empty($projectId) || !is_numeric($projectId)) {
                wp_die(-26);
            }

            $groups = array(
                'sessions',
                'users',
                'organicSearches',
                'visitBounceRate',
                'pageviews',
                'uniquePageviews'
            );
            if (in_array($query, $groups)) {
                return $this->getAreachartData($projectId, $from, $to, $query, $filter, $property_type);
            }
            if ($query === 'bottomstats') {
                return $this->getNottomstats($projectId, $from, $to, $query, $filter, $property_type);
            }
            if ($query === 'locations') {
                return $this->getLocations($projectId, $from, $to, $filter, $property_type);
            }
            if ($query === 'referrers') {
                return $this->getReferrers($projectId, $from, $to, $filter, $property_type);
            }
            if ($query === 'contentpages') {
                return $this->getContentPages($projectId, $from, $to, $filter, $property_type);
            }
            if ($query === 'searches') {
                return $this->getSearches($projectId, $from, $to, $filter, $property_type);
            }
            if ($query === 'realtime') {
                return $this->getRealtime($projectId, $property_type);
            }
            if ($query === 'channelGrouping' || $query === 'deviceCategory') {
                return $this->getOrgchartData($projectId, $from, $to, $query, $filter, $property_type);
            }

            $arrs = array(
                'medium',
                'visitorType', // new uers or returning user
                'socialNetwork',
                'source',
                'browser',
                'operatingSystem',
                'screenResolution',
                'mobileDeviceBranding'
            );
            if (in_array($query, $arrs)) {
                return $this->getPiechartData($projectId, $from, $to, $query, $filter, $property_type);
            }
            wp_die(-27);
        }
    }
}
