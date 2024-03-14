<?php 

//loads the products via the Amazon-API
function eapi_get_amazon_products($passed_get_information){
$affili_amazon_tag = get_option('eapi_affiliate_link');
$aws_access_key_id = get_option('eapi_access_key_id');

$all_elements = array();

if(isset($passed_get_information['product'])){
	$keyword = str_replace(" ", "+", $passed_get_information['product']);
}else{
	$keyword = "Nischenseite";
}
if(!isset($passed_get_information['n'])){
	$passed_get_information['n']  = "1";
}

$keyword = str_replace(" ", "+", $keyword);
$keyword_arr = explode(",", $keyword);

$num_of_wanted_results =  $passed_get_information['n'];
foreach($keyword_arr as $keyword_to_look){
  $pageNumber = 1;
  do{
    $elements_last_round = sizeof($all_elements);

    $json_result = eapi_call_amazon_api($keyword_to_look, $pageNumber, $affili_amazon_tag);
    if($json_result){
      $all_elements =  eapi_parse_data($json_result, $num_of_wanted_results, $all_elements);
    }
    $pageNumber++;
  } while($num_of_wanted_results > sizeof($all_elements) AND $elements_last_round != sizeof($all_elements));
}
return json_encode($all_elements);
}

// parses the information from the xml-var
function eapi_parse_data($json_result, $num_of_wanted_results, $ret_elements)
{
    $json_obj = json_decode($json_result);
  	$count_of_pushed_elemets = 0;
  	if(isset(($json_obj->SearchResult))) {
      foreach($json_obj->SearchResult->Items as $resultItem) {
        $arr = array();
        $arr['asin'] = $resultItem->ASIN;
        $small_img_arr = array();
        $medium_img_arr = array();
        $large_img_arr = array();

        if(isset($resultItem->Images)) {
            if(isset($resultItem->Images->Primary->Small)) {
              array_push($small_img_arr, (String)$resultItem->Images->Primary->Small->URL);
            }
            if(isset($resultItem->Images->Primary->Medium)) {
              array_push($medium_img_arr, (String)$resultItem->Images->Primary->Medium->URL);
            }
            if(isset($resultItem->Images->Primary->Large)) {
              array_push($large_img_arr, (String)$resultItem->Images->Primary->Large->URL);
            }
        }
        $arr['small_image'] = json_encode($small_img_arr);
        $arr['medium_image'] = json_encode($medium_img_arr);
        $arr['large_image'] = json_encode($large_img_arr);

        if(isset($resultItem->ItemInfo->Title)){
          $arr['title'] = (String)$resultItem->ItemInfo->Title->DisplayValue;
        }

        if(isset($resultItem->ItemInfo->Features)){
          $feature_arr = array();
          foreach($resultItem->ItemInfo->Features->DisplayValues as $feature){
            array_push($feature_arr , htmlspecialchars(preg_replace('/✔/', '', (string)$feature)));
          }
          $arr['features'] = json_encode($feature_arr, JSON_UNESCAPED_UNICODE);
        }

        if(isset($resultItem->ItemInfo->ExternalIds->EANs)){
            $arr['ean'] = (String)$resultItem->ItemInfo->ExternalIds->EANs->DisplayValues[0];
        }
        //default
        $arr['has_prime'] = false;
        if(isset($resultItem->Offers->Listings)) {
          $arr['new_price'] = trim(str_replace(".", ",", (String)$resultItem->Offers->Listings[0]->Price->Amount));
          if(isset($resultItem->Offers->Listings[0]->DeliveryInfo)) {
              $arr['has_prime'] = $resultItem->Offers->Listings[0]->DeliveryInfo->IsPrimeEligible;
          }
          if(isset($resultItem->Offers->Listings[0]->Price->Savings)) {
            $arr['amount_saved'] = str_replace(".", ",", $resultItem->Offers->Listings[0]->Price->Savings->Amount);
          }

        } else {
          $arr['new_price'] = '';
        }
        $arr['review_information'] = json_encode(array(
          'has_reviews' => 0,
          'iframe_url' => "https://www.amazon.de/product-reviews/" . $arr['asin'],
          'num_reviews' => '',
          'rating' => ''
        ));
        $arr['availability'] = 'now';
        $arr['shop'] = 'amazon';

      if(($num_of_wanted_results == 1 AND $count_of_pushed_elemets < 1) OR
        ($num_of_wanted_results > 1 AND sizeof($ret_elements) < $num_of_wanted_results) ){
          array_push($ret_elements, $arr);
          $count_of_pushed_elemets++;
          if(sizeof($ret_elements) >= 10){//more than 10 results are requestet
            if($num_of_wanted_results == ($count_of_pushed_elemets + sizeof($ret_elements)) ){
              break;
            }
          }else{
            if($num_of_wanted_results == $count_of_pushed_elemets){
              break;
            }
          }
        }
      }
  	}

	return $ret_elements;
}

//puts the parameters together to an array.
function eapi_amazon_keyword_search($searchString, $page, $affili_amazon_tag, $aws_access_key_id){
	//api needs an affiliate-link to work
	if($affili_amazon_tag == ""){
		$affili_amazon_tag = 1;
	}
	$data = array(
		'AWSAccessKeyId'=> $aws_access_key_id,
		'AssociateTag'=>$affili_amazon_tag,
		'ItemPage' => $page,
		'Keywords'=>$searchString,
		'Operation'=>'ItemSearch',
		#'ResponseGroup'=>'Large',
		'ResponseGroup'=>'BrowseNodes,Reviews,Images,ItemAttributes,OfferListings',
		'SearchIndex' => 'All',
		'Service'=> 'AWSECommerceService',
		'Sort' => '',
		'Timestamp'=> gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time()),
		'Version'=> '2013-08-01',
	);
return $data;
}

class SearchItemsRequest {
    public $PartnerType;
    public $PartnerTag;
    public $Keywords;
    public $SearchIndex;
    public $Resources;
    public $ItemPage;
}


//builds and calls the Amazon-API
function eapi_call_amazon_api($keyword_to_look, $pageNumber, $affili_amazon_tag){

    $searchItemRequest = new SearchItemsRequest();
    $searchItemRequest->PartnerType = "Associates";
    // Put your Partner tag (Store/Tracking id) in place of Partner tag
    $searchItemRequest->PartnerTag = $affili_amazon_tag;
    $searchItemRequest->Keywords = $keyword_to_look;
    $searchItemRequest->SearchIndex = "All";
    $searchItemRequest->ItemPage = $pageNumber;
    $searchItemRequest->Resources = [
    "Images.Primary.Small",
    "Images.Primary.Medium",
    "Images.Primary.Large",
    "ItemInfo.Title",
    "ItemInfo.ContentRating",
    "ItemInfo.Features",
    "ItemInfo.ExternalIds",
    "Offers.Listings.DeliveryInfo.IsPrimeEligible",
    "Offers.Listings.Price"];
    $host = "webservices.amazon.de";
    $path = "/paapi5/searchitems";
    $payload = json_encode ($searchItemRequest);
    //Put your Access Key in place of <ACCESS_KEY> and Secret Key in place of <SECRET_KEY> in double quotes
    $awsv4 = new AwsV4 (get_option('eapi_access_key_id'), get_option('eapi_secret_access_key'));
    $awsv4->setRegionName("eu-west-1");
    $awsv4->setServiceName("ProductAdvertisingAPI");
    $awsv4->setPath ($path);
    $awsv4->setPayload ($payload);
    $awsv4->setRequestMethod ("POST");
    $awsv4->addHeader ('content-encoding', 'amz-1.0');
    $awsv4->addHeader ('content-type', 'application/json; charset=utf-8');
    $awsv4->addHeader ('host', $host);
    $awsv4->addHeader ('x-amz-target', 'com.amazon.paapi5.v1.ProductAdvertisingAPIv1.SearchItems');
    $headers = $awsv4->getHeaders ();
    $headerString = "";
    foreach ( $headers as $key => $value ) {
        $headerString .= $key . ': ' . $value . "\r\n";
    }
    $params = array (
            'http' => array (
                'header' => $headerString,
                'method' => 'POST',
                'content' => $payload
            )
        );
    $stream = stream_context_create($params);

    $result = file_get_contents('https://'.$host.$path, false, $stream);

    if(isset($http_response_header)) {
      if(!(preg_match("#200#", $http_response_header[0]))){//error message is created, when HTTP status != 200
          $request_error =  gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time());
          $request_error .= (string)$result;
          $request_error .= implode($http_response_header);
          update_option('eapi_personal_error', $request_error);
        }
    }

    if(isset($http_response_header) AND preg_match("#200#", $http_response_header[0])){
      return $result;
    }else{
      return false;
    }
}
function eapi_get_rating_from_iframeurl($url, $passed_get_information){



$opts = array(
	'http'=>array(
	'header' => 'Connection: close',
	'ignore_errors' => true
	)
);
$context = stream_context_create($opts);
$ratings_round = "";
$num_reviews = "";
@$temp = file_get_contents($url, false, $context);
if($passed_get_information['debug']){
	print_r("<b>Rating_request:</b> <br>");
	print_r($http_response_header);
	print_r("\n");
}

if(isset($http_response_header)){
	if(!(array_key_exists(8, $http_response_header))){
		$http_response_header[8] = "";
	}
	if(preg_match("#200#", $http_response_header[8] . $http_response_header[0] )){//error message is created, when HTTP status != 200
		preg_match("#\d\.\d\svon\s5\sSternen#", $temp, $matches);
		$num_reviews =0;
		$ratings =0;
		if(isset($matches[0])){
			$ratings = preg_replace("#von\s5\sSternen#", "", $matches[0]);
		}
		//cast needed since PHP 7.
		$ratings = floatval($ratings);

		$ratings_round = round($ratings * 2, 0)/2;
		preg_match("#((\d)(\.))?\d+\sKundenrezension#", $temp, $matches);
		if(isset($matches[0])){
			$num_reviews = preg_replace("#(Kundenrezension|\s)#", "", $matches[0]);
		}
	}
}
$ret = array('rating' => $ratings_round, 'num_reviews' =>$num_reviews);

return $ret;
}

?>
