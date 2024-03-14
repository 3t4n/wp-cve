<?php
if (!defined( 'ABSPATH' ) ) {
	exit;
}

class Epaka_Api_Controller {

    private static $ApiUrl = EPAKA_DOMAIN."api/";

    public function __construct() {

    }

    public static function getInstance(){
        return new Epaka_Api_Controller;
    }

    public static function authorize($email, $password){
        $args = [
            'method' => 'POST',
            'redirection' => 5,
            'blocking' => true,
            'body' => [
                'email' => sanitize_email($email),
                'password' => sanitize_text_field($password)
            ]
        ];

        $response = wp_remote_request(Epaka_Api_Controller::$ApiUrl."login.xml", $args);

        $response = simplexml_load_string($response["body"]);

        if ($response->status == "OK") {
            if(empty(get_option('epakaSession'))){
                add_option('epakaSession',$response->session->__toString());
            }else{
                update_option('epakaSession',$response->session->__toString());
            }
            if(empty(get_option('epakaE')) && empty(get_option('epakaP'))){
                add_option('epakaE',$email);
                add_option('epakaP',$password);
            }else{
                update_option('epakaE',$email);
                update_option('epakaP',$password);
            }
            return [
                "status"=>$response->status,
                "message"=>""
            ];
        } else {
            return [
                "status"=>$response->status,
                "message"=>$response->message
            ];
        }
    }

    public function getProfile(){
        return Epaka_Api_Controller::sendRequest("profile.xml");
    }

    public function saveProfile($request){
        if(!Epaka_Api_Controller::isAuthorized()) wp_redirect(admin_url('admin.php?page=epaka_admin_panel_login_page'));
        $profile = $this->getProfile();

        $profileDataToSave = [
            "name" => $profile->name->__toString(),
            "lastName" => $profile->lastName->__toString(),
            "company" => $profile->company->__toString(),
            "tin" => $profile->tin->__toString(),
            "street" => $profile->street->__toString(),
            "houseNumber" => $profile->houseNumber->__toString(),
            "flatNumber" => $profile->flatNumber->__toString(),
            "postCode" => $profile->postCode->__toString(),
            "city" => $profile->city->__toString(),
            "country" => $profile->country->__toString(),
            "bankAccount" => $profile->bankAccount->__toString(),
            "phone" => $profile->phone->__toString(),
            "senderName" => $profile->senderName->__toString(),
            "senderLastName" => $profile->senderLastName->__toString(),
            "senderCompany" => $profile->senderCompany->__toString(),
            "senderStreet" => $profile->senderStreet->__toString(),
            "senderHouseNumber" => $profile->senderHouseNumber->__toString(),
            "senderFlatNumber" => $profile->senderFlatNumber->__toString(),
            "senderPostCode" => $profile->senderPostCode->__toString(),
            "senderCity" => $profile->senderCity->__toString(),
            "senderCountry" => $profile->senderCountry->__toString(),
            "senderPhone" => $profile->senderPhone->__toString(),
            "invoices" => intval($profile->invoices->__toString()),
            "zebra" => intval($profile->zebra->__toString()),
            "newsletter" => intval($profile->newsletter->__toString()),
            "defaultPaczkomat" => $profile->defaultPaczkomat->__toString(),
            "defaultPaczkomatDescription" => $profile->defaultPaczkomatDescription->__toString(),
            "defaultPunktRuchu" => $profile->defaultPunktRuchu->__toString(),
            "defaultPunktRuchuDescription" => $profile->defaultPunktRuchuDescription->__toString(),
            "defaultPunktPaczka48" => $profile->defaultPunktPaczka48->__toString(),
            "defaultPunktPaczka48Description" => $profile->defaultPaczka48Description->__toString(),
        ];

        foreach($request->get_body_params()['profile'] as $key=>$value){
            $profileDataToSave[$key] = sanitize_text_field($value);
        }

        $response = Epaka_Api_Controller::sendRequest("saveProfile.xml",$profileDataToSave);


        return $response;
    }

    public function setShippingCourierMapping(){
        if(is_array($_POST['data']['Epaka_Shipping_Mapping'])){
            foreach($_POST['data']['Epaka_Shipping_Mapping'] as &$mapping){
                if(is_array($mapping)) {
                    foreach ($mapping as &$value) {
                        $value['epaka_courier'] = sanitize_text_field($value['epaka_courier']);
                        $value['map_source_url'] = sanitize_text_field($value['map_source_url']);
                        $value['map_source_name'] = sanitize_text_field($value['map_source_name']);
                        $value['map_source_id'] = sanitize_text_field($value['map_source_id']);
                    }
                }else{
                    return ["status" => "Error"];
                }
            }
        }else{
            return ["status" => "Error"];
        }

        $MapInJsonString = json_encode($_POST['data']);

        if(empty(get_option('epakaShippingCourierMapping'))){
            add_option('epakaShippingCourierMapping',$MapInJsonString);
        }else{
            update_option('epakaShippingCourierMapping',$MapInJsonString);
        }

        return ["status" => "OK"];
    }

    public function getMap(){
        $mapSearch = false;

        if($_GET['endpoint'] == "mapSearch"){
            $mapSearch = true;
            $_GET['endpoint'] = '/'.sanitize_text_field(urldecode($_GET['payload']));
            if(preg_match('/^\/map.php/', sanitize_text_field($_GET['endpoint'])) == 0){
                return false;
            }
        }elseif(preg_match('/map_popup/', sanitize_text_field($_GET['endpoint']))){
            $mapSearch = true;
        }elseif(preg_match('/api/',sanitize_text_field($_GET['endpoint'])) == 0){
            return null;
        }

        $enpoint = sanitize_text_field($_GET['endpoint']);

        $response = null;
        if(!empty($_POST)){
            $lat = floatval(sanitize_text_field($_POST['lat']));
            $lon = floatval(sanitize_text_field($_POST['lon']));
            $map = sanitize_text_field($_POST['map']);

            $args = [
                'method' => 'POST',
                'timeout' => 60,
                'redirection' => 5,
                'httpversion' => '1.0',
                'headers' => array(
                    'Expect' => '',
                ),
                'body' => [
                    'lat' => $lat,
                    'lon' => $lon,
                    'map' => $map
                ]
            ];

            $response = wp_remote_request(str_replace('/api/','',Epaka_Api_Controller::$ApiUrl).$enpoint, $args);
        }else{
            $args = [
                'method' => 'GET',
                'redirection' => 5
            ];

            $response = wp_remote_get(str_replace('/api/','',Epaka_Api_Controller::$ApiUrl).$enpoint);
        }

        if(!empty($_POST)){
            $response = simplexml_load_string($response["body"],'SimpleXMLElement',LIBXML_NOCDATA);
        }else{
            $response = $response["body"];
        }

        return $response;
    }

    public function getOrderIframe(){
        $args = [
            'method' => 'POST',
            'timeout' => 60,
            'redirection' => 5,
            'body' => [
                'session' => get_option('epakaSession')
            ]
        ];
        $server_output = wp_remote_request(Epaka_Api_Controller::$ApiUrl.'/getOrderIframe', $args);

        header('Content-Type: text/html');
        echo $server_output['body'];
    }

    public function getAvailableCouriers(){
        return Epaka_Api_Controller::sendRequest("getAvailableCouriers.xml");
    }

    public function cancelEpakaOrder(){
        if(!empty($_POST['epaka_order_id'])){
            $id = intval(sanitize_text_field($_POST['epaka_order_id']));
            if($id == 0){
                return new WP_REST_Response(null, 404);
            }

            $try1 = Epaka_Api_Controller::sendRequest("cancelOrder/".$id.".xml");
            if($try1->status->__toString() == "ERROR"){
                $try2 = Epaka_Api_Controller::sendRequest("cancelOrderEmail/".$id.".xml");
                if($try1->status->__toString() == "ERROR"){
                    return new WP_REST_Response(null, 404);
                }
                return new WP_REST_Response("OK", 200);
            }
            return new WP_REST_Response("OK", 200);
        }
        return new WP_REST_Response(null, 404);
    }

    public function unlinkEpakaOrderFromWooOrder(){
        if(!empty($_POST['woo_order_id'])){
            // $woo_order = wc_get_order($_POST['woo_order_id']);
            $id = intval(sanitize_text_field($_POST['woo_order_id']));
            if($id == 0){
                return new WP_REST_Response(null, 404);
            }
            wc_delete_order_item_meta($id,'_epakaOrderId');
            // $woo_order->delete_meta_data('_epakaOrderId');
            // $woo_order->save();
            return new WP_REST_Response("OK", 200);
        }
        return new WP_REST_Response(null, 404);
    }

    public function linkEpakaOrderToWooOrder(){
        if(!empty($_POST['woo_order_id'])){
            if(!empty($_POST['epaka_order_id'])){
                $woo_id = intval(sanitize_text_field($_POST['woo_order_id']));
                $epaka_id = intval(sanitize_text_field($_POST['epaka_order_id']));

                if($woo_id == 0 || $epaka_id == 0){
                    return new WP_REST_Response(null, 404);
                }

                wc_add_order_item_meta($woo_id,'_epakaOrderId',$epaka_id);
                // $woo_order = wc_get_order($_POST['woo_order_id']);
                // $woo_order->add_meta_data('_epakaOrderId', $_POST['epaka_order_id']);
                // $woo_order->save();
                return new WP_REST_Response("OK", 200);
            }
        }
        return new WP_REST_Response(null, 404);
    }

    public static function getEpakaOrderDetails($epakaId){
        $id = intval(sanitize_text_field($epakaId));
        if($id == 0){
            return new WP_REST_Response(null, 404);
        }

        $order = Epaka_Api_Controller::sendRequest("order/".$id.".xml");

        if(!empty($order)){
            if($order->status->__toString() == "OK"){
                $orderData = json_encode($order->orderDetails);
                $orderData = json_decode($orderData,true);

                return $orderData;
            }
        }

        return new WP_REST_Response(null, 404);
    }

    public function getEpakaCourierTracking(){
        // $order = Epaka_Api_Controller::sendRequest("order/".$_POST['orderId'].".xml");
        $label = sanitize_text_field($_POST['label']);

        $tracking = Epaka_Api_Controller::sendRequest("getTraceOfParcel.xml",[
            "parcelNumber" => sanitize_text_field($_POST['label'])
        ]);

        if($tracking->status->__toString() == "OK"){
            if($tracking->error->__toString() != "1"){
                $trace = json_encode($tracking->trace);
                $trace = json_decode($trace,true);
                return $trace;
            }else{
                return new WP_REST_Response($tracking->message->__toString(), 404);
            }
        }

        return new WP_REST_Response(null, 404);
    }

    public function getEpakaOrderLabel(){
        $id = intval(sanitize_text_field($_POST['orderId']));

        $label = Epaka_Api_Controller::sendRequest("label/".$id.".xml");
        if($label->status->__toString() == "OK"){
            return $label->label->__toString();
        }
        return new WP_REST_Response(null, 404);
    }

    public function getEpakaOrderProtocol(){
        $id = intval(sanitize_text_field($_POST['orderId']));

        $protocol = Epaka_Api_Controller::sendRequest("protocol/".$id.".xml");
        if($protocol->status->__toString() == "OK"){
            return $protocol->protocol->__toString();
        }
        return new WP_REST_Response(null, 404);
    }

    public function getEpakaOrderAuthorizationDocument(){
        $id = intval(sanitize_text_field($_POST['orderId']));

        $authorizationDocument = Epaka_Api_Controller::sendRequest("authorizationDocument/".$id.".xml");
        if($authorizationDocument->status->__toString() == "OK"){
            return $authorizationDocument->document->__toString();
        }
        return new WP_REST_Response(null, 404);
    }

    public function getEpakaOrderProforma(){
        $id = intval(sanitize_text_field($_POST['orderId']));

        $proforma = Epaka_Api_Controller::sendRequest("proforma/".$id.".xml");
        if($proforma->status->__toString() == "OK"){
            return $proforma->document->__toString();
        }
        return new WP_REST_Response(null, 404);
    }

    public function getEpakaOrderLabelZebra(){
        $id = intval(sanitize_text_field($_POST['orderId']));

        $labelZebra = Epaka_Api_Controller::sendRequest("labelZebra/".$id.".xml");
        if($labelZebra->status->__toString() == "OK"){
            return $labelZebra->label->__toString();
        }
        return new WP_REST_Response(null, 404);
    }

    public static function getEpakaOrderPaymentInfo($epaka_order_id){
        $id = intval(sanitize_text_field($epaka_order_id));

        if(!empty($id)){
            $payment = Epaka_Api_Controller::sendRequest("getPaymentDataForOrder.xml",[
                "orderId"=>$id
            ]);
            if(!empty($payment)){
                if($payment->status->__toString() == "OK"){
                    return $payment;
                }
            }
        }
        return null;
    }

    public function sendOrder($data){

        $orderPackages = [];
        foreach($data->get_params()['data']['ZamowieniePaczka'] as $paczka){
            array_push($orderPackages,[
                "weight" => sanitize_text_field($paczka['waga']),
                "length" => sanitize_text_field($paczka['dlugosc']),
                "width" => sanitize_text_field($paczka['szerokosc']),
                "height" => sanitize_text_field($paczka['wysokosc']),
                "unsortableShape" => sanitize_text_field($paczka['ksztalt_niestandardowy'])
            ]);
        }

        $content = "";
        $contents = "";
        if(!empty($data->get_params()['data']['ZamowienieZawartosc'][0]['zawartosc'])){
            $contents = (new ArrayObject($data->get_params()['data']['ZamowienieZawartosc']))->getArrayCopy();
            if(!empty($contents[0]['ilosc']) || !empty($contents[0]['wartosc']) || !empty($contents[0]['waga'])){
                foreach($contents as &$contentValue)
                {
                    $contentValue = [
                        "content" => sanitize_text_field($contentValue["zawartosc"]),
                        "value" => sanitize_text_field($contentValue["wartosc"]),
                        "amount" => sanitize_text_field($contentValue["ilosc"]),
                        "weight" => sanitize_text_field($contentValue["waga"]),
                        "itemOrigin" => sanitize_text_field($contentValue["pochodzenie"]),
                        "itemCode" => sanitize_text_field($contentValue["kod"])
                    ];
                    // $value["zawartosc"] = sanitize_text_field($value["zawartosc"]);
                    // $value["pochodzenie"] = sanitize_text_field($value["pochodzenie"]);
                    // $value['kod'] = sanitize_text_field($value["kod"]);
                    // $value['ilosc'] = sanitize_text_field($value["ilosc"]);
                    // $value['wartosc'] = sanitize_text_field($value["wartosc"]);
                    // $value['waga'] = sanitize_text_field($value["waga"]);
                }
            }else{
                $content = sanitize_text_field($contents[0]['zawartosc']);
            }
            
        }else{
            $content = sanitize_text_field($data->get_params()['data']['Zamowienie']['zawartosc']);
        }

        $pickupTimeFrom = "";
        $pickupTimeTo = "";

        if(!empty($data->get_params()['data']['Zamowienie']['odbior_godzina'])){
            $pickupTime = explode("-",sanitize_text_field($data->get_params()['data']['Zamowienie']['odbior_godzina']));
            $pickupTimeFrom = $pickupTime[0];
            $pickupTimeTo = $pickupTime[1];
        }

        $orderData = [
            "isApplication" => 1,
            "appPlatform" => "Woocommerce",
            "paymentType" =>sanitize_text_field($data->get_params()['data']['Zamowienie']['platnosc']),
            "courierId" => sanitize_text_field($data->get_params()['data']['Zamowienie']['kurier_id']),
            "senderName" => sanitize_text_field($data->get_params()['data']['Zamowienie']['nadawca_imie']),
            "senderLastName" => sanitize_text_field($data->get_params()['data']['Zamowienie']['nadawca_nazwisko']),
            "senderCompany" => sanitize_text_field($data->get_params()['data']['Zamowienie']['nadawca_firma']),
            "senderStreet" => sanitize_text_field($data->get_params()['data']['Zamowienie']['nadawca_ulica']),
            "senderHouseNumber" => sanitize_text_field($data->get_params()['data']['Zamowienie']['nadawca_nrdomu']),
            "senderFlatNumber" => sanitize_text_field($data->get_params()['data']['Zamowienie']['nadawca_nrlokalu']),
            "senderPostCode" => sanitize_text_field($data->get_params()['data']['Zamowienie']['nadawca_kod']),
            "senderCity" => sanitize_text_field($data->get_params()['data']['Zamowienie']['nadawca_miasto']),
            "senderCountry" => sanitize_text_field($data->get_params()['data']['Zamowienie']['nadawca_kraj']),
            "senderPhone" => sanitize_text_field($data->get_params()['data']['Zamowienie']['nadawca_telefon']),
            "senderEmail" => sanitize_text_field($data->get_params()['data']['Zamowienie']['nadawca_email']),
            "senderMachineName" => sanitize_text_field($data->get_params()['data']['Zamowienie']['nadawca_paczkomat']),
            "senderMachineDescription" => sanitize_text_field($data->get_params()['data']['Zamowienie']['nadawca_paczkomat_opis']),
            "receiverName" => sanitize_text_field($data->get_params()['data']['Zamowienie']['odbiorca_imie']),
            "receiverLastName" => sanitize_text_field($data->get_params()['data']['Zamowienie']['odbiorca_nazwisko']),
            "receiverCompany" => sanitize_text_field($data->get_params()['data']['Zamowienie']['odbiorca_firma']),
            "receiverStreet" => sanitize_text_field($data->get_params()['data']['Zamowienie']['odbiorca_ulica']),
            "receiverHouseNumber" => sanitize_text_field($data->get_params()['data']['Zamowienie']['odbiorca_nrdomu']),
            "receiverFlatNumber" => sanitize_text_field($data->get_params()['data']['Zamowienie']['odbiorca_nrlokalu']),
            "receiverPostCode" => sanitize_text_field($data->get_params()['data']['Zamowienie']['odbiorca_kod']),
            "receiverCity" => sanitize_text_field($data->get_params()['data']['Zamowienie']['odbiorca_miasto']),
            "receiverCountry" => sanitize_text_field($data->get_params()['data']['Zamowienie']['odbiorca_kraj']),
            "receiverPhone" => sanitize_text_field($data->get_params()['data']['Zamowienie']['odbiorca_telefon']),
            "receiverEmail" => sanitize_text_field($data->get_params()['data']['Zamowienie']['odbiorca_email']),
            "receiverMachineName" => sanitize_text_field($data->get_params()['data']['Zamowienie']['odbiorca_paczkomat']),
            "receiverMachineDescription" => sanitize_text_field($data->get_params()['data']['Zamowienie']['odbiorca_paczkomat_opis']),
            "packageType" => sanitize_text_field($data->get_params()['data']['Zamowienie']['rodzaj_wysylki']),
            "packages" => $orderPackages,
            "content" => $content,
            "contents" => $contents,
            "pickupDate" => sanitize_text_field($data->get_params()['data']['Zamowienie']['odbior_dzien']),
            "pickupTimeFrom" => sanitize_text_field($pickupTimeFrom),
            "pickupTimeTo" =>  sanitize_text_field($pickupTimeTo),
            "comments" => sanitize_text_field($data->get_params()['data']['Zamowienie']['uwagi']),
            "eori" => sanitize_text_field($data->get_params()['data']['Zamowienie']['eori']),
            "pesel" => sanitize_text_field($data->get_params()['data']['Zamowienie']['pesel']),
            "purpose" => sanitize_text_field($data->get_params()['data']['Zamowienie']['przeznaczenie']),
            "insurance" => (!empty($data->get_params()['data']['Zamowienie']['wartosc'])) ? 1 : 0,
            "declaredValue" => sanitize_text_field($data->get_params()['data']['Zamowienie']['wartosc']),
        ];

        foreach($data->get_params()['data']['ZamowienieUsluga'] as $key=>$value){
            if($key != "on"){
                if($value["on"] == "1"){
                    $orderData[$key] = 1;
                }
            }
        }

        $orderData['cod'] = ($data->get_params()['data']['Zamowienie']['pobranie'] == "on") ? 1 : 0;
        $orderData['codType'] = strtoupper(sanitize_text_field($data->get_params()['data']['Zamowienie']['pobranie_typ']));
        $orderData['codAmount'] = sanitize_text_field($data->get_params()['data']['Zamowienie']['pobranie_kwota']);
        $orderData['codBankAccount'] = sanitize_text_field($data->get_params()['data']['Zamowienie']['pobranie_konto']);

        $orderResponse = Epaka_Api_Controller::sendRequest("makeOrder.xml", $orderData);

        if($orderResponse->status->__toString() == "OK"){
            Epaka_Api_Controller::updateWooCommerceOrder(sanitize_text_field($data->get_params()['data']['WooCommerceData']['id']),$orderResponse->orderId->__toString());
            
            return $orderResponse;
        }
        return new WP_REST_Response(null, 404);
    }

    public static function updateWooCommerceOrder($woo_order_id, $epaka_order_id){
        if(!empty($woo_order_id)){
            if(!empty($epaka_order_id)){
                $woo_id = intval(sanitize_text_field($woo_order_id));
                $epaka_id = intval(sanitize_text_field($epaka_order_id));

                if($woo_id == 0 || $epaka_id == 0){
                    return;
                }

                wc_add_order_item_meta($woo_id,'_epakaOrderId',$epaka_id);
                // $woo_order = wc_get_order($woo_order_id);
                // $woo_order->add_meta_data('_epakaOrderId', $epaka_order_id);
                // $woo_order->save();
                // return new WP_REST_Response("OK", 200);
            }
        }
        // return new WP_REST_Response(null, 404);
    }

    public static function logout(){
        delete_option('epakaSession');
        delete_option('epakaE');
        delete_option('epakaP');
    }

    public static function isAuthorized(){
        if(!empty(get_option('epakaSession'))){
            return true;
        }else{
            return false;
        }
    }

    public static function ensureAuthorized(){
        return Epaka_Api_Controller::sendRequest("profile.xml");
    }

    public static function hasAuthMem(){
        if(!empty(get_option('epakaE')) && !empty(get_option('epakaP'))){
            return true;
        }else{
            return false;
        }
    }

    public static function sendRequest($endpoint = "", $postFields = []){

        $args = [
            'method' => 'POST',
            'timeout' => 60,
            'redirection' => 5,
            'body' => $postFields
        ];

        $args['body']['session'] = get_option('epakaSession');

        $response = wp_remote_request(Epaka_Api_Controller::$ApiUrl.$endpoint.'?XDEBUG_SESSION_START=1', $args);

        $response = simplexml_load_string($response['body'],'SimpleXMLElement',LIBXML_NOCDATA);
       
        if($response->status == "OK"){
            return $response;
        }else if($response->code == "151" || $response->code == "150"){
            if(!empty(get_option('epakaE')) && !empty(get_option('epakaP'))){ 
                Epaka_Api_Controller::authorize(get_option('epakaE'), get_option('epakaP'));
        
                $args['body']['session'] = get_option('epakaSession');
        
                $response = wp_remote_request(Epaka_Api_Controller::$ApiUrl.$endpoint, $args);
        
                $response = simplexml_load_string($response['body'],'SimpleXMLElement',LIBXML_NOCDATA);

                if($response->status == "OK"){
                    return $response;
                }else{
                    Epaka_Api_Controller::logout();
                    wp_redirect(admin_url('admin.php?page=epaka_admin_panel_login_page'));
                }
            }
        }    
    }
}
