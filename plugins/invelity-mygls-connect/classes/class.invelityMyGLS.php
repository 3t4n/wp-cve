<?php


class MyGLS
{
    private $order;
    private $options;
    private $parcelOptions;
    private $url;

    public function __construct($options)
    {
        $this->options = $options;
        $this->setUrl();
    }

    public function printLabels($options)
    {
        $optionsJson = json_encode($options);

        $password = "[" . implode(',', unpack('C*', hash('sha512', $this->options['password'], true))) . "]";
        $request = '{"Username":"' . $this->options['email'] . '","Password":' . $password . ',"ParcelList":' . $optionsJson . '}';

        $response = $this->getResponse($this->url, 'PrintLabels', $request);
        if ( is_wp_error($response) ) {
            $status = [
                'success' => false,
                'errors'  => [
                    [
                        'order_id' => 'global',
                        'message'  => $response->get_error_message(),
                    ],
                ],
            ];
            error_log('MyGLS error: '. $response->get_error_message());

            return $status;
        }

        $responseArr = json_decode($response);
        if (!$responseArr) {
            $status = [
                'success' => false,
                'errors'  => [
                    [
                        'order_id' => 'global',
                        'message'  => 'Check your settings',
                    ],
                ],
            ];

            return $status;
        }

        $errors = [];
        if (count($responseArr->PrintLabelsErrorList)) {
            foreach ($responseArr->PrintLabelsErrorList as $error) {
                $ids = $error->ClientReferenceList;
                $ids = array_map(function ($id) {
                    return str_replace($this->options['clientref'] . ' - ', '', $id);
                }, $ids);

                $errorDescription = $error->ErrorDescription ;
                if ( $error->ErrorCode == 13) {
                    $errorDescription =  $error->ErrorDescription . '. Check if customer info are correctly formatted';
                }

                $errors[] = [
                    'order_id' => implode(', ', $ids),
                    'message'  => $errorDescription,
                ];
            }
        }

        if ($response == true && count(json_decode($response)->PrintLabelsErrorList) == 0 && count(json_decode($response)->Labels) > 0) {
            //Label(s) saving:
            $pdf = implode(array_map('chr', json_decode($response)->Labels));

            $fileName = 'labels-' . time() . '.pdf';

            $status = [
                'success' => true,
                'url'     => $fileName,
            ];

            header('Content-Type: application/pdf');
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=" . $fileName);

            echo $pdf;
            die();
            file_put_contents(__DIR__ . '/../labels/' . $fileName, $pdf);
            readfile(__DIR__ . '/../labels/' . $fileName);
            die();
            return $status;

        } else {
            $status = [
                'success' => false,
                'errors'  => $errors,
            ];

            return $status;
        }
    }

    private function setUrl()
    {
        $this->url = 'https://api.mygls.' . $this->options['country_version'] . '/ParcelService.svc/json/';
    }

    private function getResponse($url, $method, $request)
    {
        $response = wp_remote_post($url.$method, [
            'headers'     => ['Content-Type' => 'application/json; charset=utf-8'],
            'body'        => $request,
            'method'      => 'POST',
            'data_format' => 'body',
            'timeout'     => 30,
        ]);

        if ( is_wp_error($response) ) {
            return $response;
        }

        return $response['body'];
    }


    public function setParcelOptions($order)
    {

        //date_default_timezone_set("Europe/Budapest");

        $pickupDate = date('Y-m-d', strtotime('+1 day'));
        while ($this->isSviatok($pickupDate)) {
            $pickupDate = date('Y-m-d', strtotime('+1 day', strtotime($pickupDate)));
        }

        $weekDay = date('w', strtotime($pickupDate));

        if (($weekDay == 5)) //Check if the day is saturday or not.
        {
            $pickupDate = date('Y-m-d', strtotime('+2 day', strtotime($pickupDate)));
        } elseif ($weekDay == 6) {
            $pickupDate = date('Y-m-d', strtotime('+1 day', strtotime($pickupDate)));
        };

        $pickupDate = "/Date(" . (strtotime($pickupDate) * 1000) . ")/";

        $paymentMethod = $order->get_payment_method();
        $total = $order->get_total();

        $parcelOptions = [
            'ClientNumber'    => intval($this->options['sender_id']),
            'ClientReference' => $this->options['clientref'] . ' - ' . $this->filterOrderId($order), //shop name,
            'CODAmount'       => $paymentMethod === 'cod' ? $total : 0,
            'CODReference'    => $this->filterOrderId($order),
            'Content'         => $this->filterOrderId($order),
            'Count'           => 1,
            'DeliveryAddress' => [
                'City'           => $this->filterShippingCity($order),
                'ContactEmail'   => $this->filterEmail($order),
                'ContactName'    => $this->filterName($order),
                'ContactPhone'   => $this->filterPhone($order),
                'CountryIsoCode' => $this->filterShippingCountry($order),
                'Name'           => $this->filterName($order),
                'Street'         => $this->filterShippingAddress($order),
                "ZipCode"        => $this->filterShippingPostcode($order),
            ],
            'PickupAddress'   => [
                'City'           => $this->options['sender_city'],
                'ContactEmail'   => $this->options['sender_email_email'],
                'ContactName'    => $this->options['sender_contact_name'],
                'ContactPhone'   => $this->options['sender_phone'],
                'CountryIsoCode' => strtoupper($this->options['country_version']),
                'Name'           => $this->options['sender_name'],
                'Street'         => $this->options['sender_address'],
                'ZipCode'        => $this->options['sender_zip'],
            ],
            'PickupDate'      => $pickupDate,
            'ServiceList'     => [],
        ];

        if (isset($this->options['fds']) && $this->options['fds'] == 'on') {
            $parcelOptions['ServiceList'][] = [
                'Code'         => 'FDS',
                'FDSParameter' => [
                    'ServiceParameterString' => $this->filterEmail($order),
                ],
            ];
        }
        if (isset($this->options['fss']) && $this->options['fss'] == 'on') {
            $parcelOptions['ServiceList'][] = [
                'Code'         => 'FSS',
                'FSSParameter' => [
                    'ServiceParameterString' => $this->filterPhone($order),
                ],
            ];
        }
        if (isset($this->options['sm2']) && $this->options['sm2'] == 'on') {
            $parcelOptions['ServiceList'][] = [
                'Code'         => 'SM2',
                'SM2Parameter' => [
                    'ServiceParameterString' => $this->filterPhone($order),
                ],
            ];
        }

        if ($this->getShippingMethod($order) == 'inv_gls_parcel_shop') {
            $parcelOptions['ServiceList'][] = [
                'Code' => 'PSD',
                'PSDParameter' => [
                    'StringValue' => $this->getPSDService($order)
                ],
            ];

        }


        return $parcelOptions;
    }

    private function getEaster($year)
    { //Generates holidays. Default: Slovakia
        $sviatky = [];
        $s = ['01-01', '01-06', '', '', '05-01', '05-08', '07-05', '08-29', '09-01', '09-15', '11-01', '11-17', '12-24', '12-25', '12-26'];
        $easter = date('m-d', easter_date($year));
        $sdate = strtotime($year . '-' . $easter);
        $s[2] = date('m-d', strtotime('-2 days', $sdate)); //Firday
        $s[3] = date('m-d', strtotime('+1 day', $sdate)); //Monday
        foreach ($s as $day) {
            $sviatky[] = $year . '-' . $day;
        }
        return $sviatky;
    }

    private function isSviatok($date)
    {
        $year = apply_filters('InvelityMyGLSConnectProcessIsSviatokYearFilter', date('Y'));
        $thisyear = $this->getEaster($year);
        $nextyear = $this->getEaster($year + 1); //generates next year for delivering after December in actual year
        $sviatky = [];
        $sviatky = array_merge($thisyear, $nextyear);
        $sviatky[] = '2018-10-30';
        $sviatky = apply_filters('InvelityMyGLSConnectProcessIsSviatokFilter', $sviatky);
        if (in_array($date, $sviatky)) {
            return true;
        }
        return false;
    }

    private function filterOrderId($order)
    {
        if (!defined('WC_VERSION')) {
            return false;
        }
        if (version_compare(WC_VERSION, '3.0', '<')) {
            return $order->id;
        } else {
            return $order->get_id();
        }
    }

    private function filterName($order)
    {
        if (!defined('WC_VERSION')) {
            return false;
        }
        if (version_compare(WC_VERSION, '3.0', '<')) {
            return $order->shipping_first_name . ' ' . $order->shipping_last_name;
        } else {
            return $order->get_shipping_first_name() . ' ' . $order->get_shipping_last_name();
        }
    }

    private function filterPhone($order)
    {
        if (!defined('WC_VERSION')) {
            return false;
        }
        if (version_compare(WC_VERSION, '3.0', '<')) {
            if ($order->shipping_phone != '') {
                return $order->shipping_phone;
            } else {
                return $order->billing_phone;
            }
        } else {
            return $order->get_billing_phone();
        }
    }

    private function filterEmail($order)
    {
        if (!defined('WC_VERSION')) {
            return false;
        }
        if (version_compare(WC_VERSION, '3.0', '<')) {
            if ($order->shipping_email != '') {
                return $order->shipping_email;
            } else {
                return $order->billing_email;
            }
        } else {
            return $order->get_billing_email();
        }
    }

    private function filterShippingAddress($order)
    {
        if (!defined('WC_VERSION')) {
            return false;
        }
        if (version_compare(WC_VERSION, '3.0', '<')) {
            return $order->shipping_address_1;
        } else {
            return $order->get_shipping_address_1();
        }
    }

    private function filterShippingCity($order)
    {
        if (!defined('WC_VERSION')) {
            return false;
        }
        if (version_compare(WC_VERSION, '3.0', '<')) {
            return $order->shipping_city;
        } else {
            return $order->get_shipping_city();
        }
    }

    private function filterShippingPostcode($order)
    {
        if (!defined('WC_VERSION')) {
            return false;
        }
        if (version_compare(WC_VERSION, '3.0', '<')) {
            return $order->shipping_postcode;
        } else {
            return $order->get_shipping_postcode();
        }
    }

    private function filterShippingCountry($order)
    {
        if (!defined('WC_VERSION')) {
            return false;
        }
        if (version_compare(WC_VERSION, '3.0', '<')) {
            return $order->shipping_country;
        } else {
            return $order->get_shipping_country();
        }
    }

    private function getPSDService($order)
    {
        if (!defined('WC_VERSION')) {
            return false;
        }

        if (version_compare(WC_VERSION, '3.0', '<')) {
            return get_post_meta($order->id, 'inv_gls_picked_shop_id', true) ? get_post_meta($order->id, 'inv_gls_picked_shop_id', true) : '';
        } else {
            return get_post_meta($order->get_id(), 'inv_gls_picked_shop_id', true) ? get_post_meta($order->get_id(), 'inv_gls_picked_shop_id', true) : '';
        }
    }

    private function getShippingMethod($order)
    {

        $shippingMethods = $order->get_shipping_methods();
        foreach ($shippingMethods as $shippingMethod) {
            return $shippingMethod->get_method_id();
        }
    }

}