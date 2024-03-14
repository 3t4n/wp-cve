<?php
	/**
	 * @since: 26/05/2019
	 * @author: Sarwar Hasan
	 * @version 1.0.0
	 */
	if(!class_exists("EliteCaller")) {
		class EliteCaller
        {
            public $host = "";
            public $apikey = "";

            function __construct($host, $apikey)
            {

                $this->host = $host;
                $this->apikey = $apikey;
            }

            function _request($relatetivePath, $data, &$error = '')
            {
                $response = new stdClass();
                $response->status = false;
                $response->msg = "Empty Response";
                $finalData = (array)($data);
                if (!isset($finalData['api_key'])) {
                    $finalData['api_key'] = $this->apikey;
                }
                $fullUrl = rtrim($this->host, '/') . '/' . ltrim($relatetivePath, '/');
                if (function_exists('wp_remote_post')) {
                    $serverResponse = wp_remote_post($fullUrl, array(
                            'method' => 'POST',
                            'sslverify' => false,
                            'timeout' => 45,
                            'redirection' => 5,
                            'httpversion' => '1.0',
                            'blocking' => true,
                            'headers' => array(),
                            'body' => $finalData,
                            'cookies' => array()
                        )
                    );


                    if (is_wp_error($serverResponse)) {
                        $error = $serverResponse->get_error_message();

                        return NULL;
                    } else {
                        if (!empty($serverResponse['body']) && $serverResponse['body'] != "GET404") {
                            return $this->process_response($serverResponse['body'], $error);
                        }
                    }

                }
                if (!extension_loaded('curl')) {
                    $error = "Curl extension is missing";

                    return NULL;
                }
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $fullUrl,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $finalData
                ));
                $serverResponse = curl_exec($curl);
                $error = curl_error($curl);
                curl_close($curl);
                if (!empty($serverResponse)) {
                    return $this->process_response($serverResponse, $error);
                }

                return NULL;
            }

            private function process_response($serverResponse, &$error = "")
            {
                if (!empty($serverResponse)) {
                    $obj = json_decode($serverResponse);
                    if (!empty($obj->code)) {
                        $error = !empty($obj->message) ? $obj->message : $error;

                        return NULL;
                    } else {
                        return $obj;
                    }
                }

                return NULL;
            }

            function IsApiCanAddEditLicense(&$error = "")
            {
                $error = "Unknown";
                $obj = $this->_request('hello', [], $error);
                if (!empty($obj)) {
                    if ($obj->status) {
                        if ($obj->data->add_license == "Y" && $obj->data->add_license == "Y" && $obj->data->view_product == "Y" && $obj->data->view_product == "Y") {
                            return true;
                        } else {
                            $error = "API doesn't have permission to add,edit or view license and product, Pleaes allow these permision on Elite Licenser for this API Key";

                            return false;
                        }
                    } else {
                        $error = !empty($obj->msg) ? $obj->msg : "";

                        return false;
                    }
                }

                return false;
            }

            function GetProductList(&$error = "")
            {
                $error = "Unknown";
                $obj = $this->_request('product/view-all-with-license-type', ["limit" => 0], $error);
                if (!empty($obj->status) && !empty($obj->data->products)) {
                    $pros = ['' => "Select Elite Product"];
                    $products = $obj->data->products;
                    if (!empty($products) && is_array($products)) {
                        foreach ($products as $p) {
                            if (!empty($p->product_licenses)) {
                                $p->product_licenses = (array)$p->product_licenses;
                                foreach ($p->product_licenses as $pl_key => $pl) {
                                    $pros["{$p->id}-{$pl_key}"] = $p->product_name . "-" . $pl;
                                }

                            }
                        }
                    }

                    return $pros;
                } else {
                    $error = $obj->msg;
                }

                return [];
            }

            function AddLicense($licenseArray, &$error = "")
            {
                $error = "Unknown";
                $obj = $this->_request('license/add ', $licenseArray, $error);
                if (!empty($obj->status) && !empty($obj->data->license_code)) {
                    return $obj->data;
                } else {
                    $error = $obj->msg;
                }

                return NULL;
            }

            function DisableLicenseLicense($licenseKey, $status = 'R', &$error = "",$expire_time='',$order_item_id='')
            {
                $error = "Unknown";
                $request_params=[
                    "license_code" => $licenseKey,
                    "status" => $status
                ];
                if(!empty($expire_time)){
                    $request_params["expiry_time"] = $expire_time;
                    $request_params["has_expiry"] = "Y";
                }
                if(!empty($order_item_id)) {
                    $request_params["extra_param"] = $order_item_id;
                }
                $obj = $this->_request('license/edit ', $request_params, $error);
                if (!empty($obj->status)) {
                    return true;
                } else {
                    $error = $obj->msg;
                }

                return false;
            }

            function UpdateLicenseExpiry($licenseKey, $expire_time, &$error = "")
            {
                $error = "Unknown";
                $request_params=[
                    "license_code" => $licenseKey,
                    "has_expiry" => 'Y'
                ];
                if(!empty($expire_time)){
                    $request_params["expiry_time"] = $expire_time;
                    $request_params["support_end_time"] = $expire_time;
                }
                $obj = $this->_request('license/edit ', $request_params, $error);
                if (!empty($obj->status)) {
                    return true;
                } else {
                    $error = $obj->msg;
                }

                return false;
            }
        }
	}