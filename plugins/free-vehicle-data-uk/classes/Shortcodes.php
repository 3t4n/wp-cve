<?php
namespace FreeVehicleData;

if ( ! defined( 'ABSPATH' ) )
	exit;

class Shortcodes{

    public function __construct() 
    {
        add_shortcode('fvd_calljson',[$this,'CallJson']);
        add_shortcode('fvd_searchbox',[$this,'DisplaySearchBox']);
        add_shortcode('fvd_getdata',[$this,'GetData']);
        add_shortcode('fvd_returnmotrecord',[$this,'GetMOT']);

    }
    public function GetMOT($atts)
    {
        global $json_return;
        global $global;

        $CurrentRecord = ($atts["record"]);

        if (isset ($json_return["Results"]["FullMotHistory"][($atts["record"])]))
        {

            $completiondate = ($json_return["Results"]["FullMotHistory"][$CurrentRecord]['completedDate']);
            $completiondate=substr($completiondate, 0, strrpos($completiondate, ' '));
            $completiondate = date('d M Y', strtotime(str_replace('.', '/', $completiondate)));

            $testresult = ($json_return["Results"]["FullMotHistory"][$CurrentRecord]['testResult']); // PASSED OR FAILED

            if (isset($json_return["Results"]["FullMotHistory"][$CurrentRecord]['expiryDate']))
            {
                $expiryDate = date('d M Y', strtotime(str_replace('.', '/', ($json_return["Results"]["FullMotHistory"][$CurrentRecord]['expiryDate']))));
            }
            else
            {
                $expiryDate = 'Not Available';
            }

            $odometerValue = ($json_return["Results"]["FullMotHistory"][$CurrentRecord]['odometerValue']); //e.g. 51200
            $odometerUnit = ($json_return["Results"]["FullMotHistory"][$CurrentRecord]['odometerUnit']); //mi or km

            if (isset(($json_return["Results"]["FullMotHistory"][$CurrentRecord]['rfrAndComments']['0']['text']))){
            $RfaComments = '<p><img src="' . plugin_dir_url(FVD_FILE) . 'assets/images/alert.png" width="20" height="15"> ' . ($json_return["Results"]["FullMotHistory"][$CurrentRecord]['rfrAndComments']['0']['text']) . '</p>';
            }else{
            $RfaComments = '<p>Nothing to display.</p>';	
            }
            if (isset(($json_return["Results"]["FullMotHistory"][$CurrentRecord]['rfrAndComments']['1']['text']))){
            $RfaComments = $RfaComments . '<p><img src="' . plugin_dir_url(FVD_FILE) . 'assets/images/alert.png" width="20" height="15"> ' . ($json_return["Results"]["FullMotHistory"][$CurrentRecord]['rfrAndComments']['1']['text']) . '</p>';
            }		
            if (isset(($json_return["Results"]["FullMotHistory"][$CurrentRecord]['rfrAndComments']['2']['text']))){
            $RfaComments = $RfaComments . '<p><img src="' . plugin_dir_url(FVD_FILE) . 'assets/images/alert.png" width="20" height="15"> ' . ($json_return["Results"]["FullMotHistory"][$CurrentRecord]['rfrAndComments']['2']['text']) . '</p>';
            }		
            if (isset(($json_return["Results"]["FullMotHistory"][$CurrentRecord]['rfrAndComments']['3']['text']))){
            $RfaComments = $RfaComments . '<p><img src="' . plugin_dir_url(FVD_FILE) . 'assets/images/alert.png" width="20" height="15"> ' . ($json_return["Results"]["FullMotHistory"][$CurrentRecord]['rfrAndComments']['3']['text']) . '</p>';
            }		
            if (isset(($json_return["Results"]["FullMotHistory"][$CurrentRecord]['rfrAndComments']['4']['text']))){
            $RfaComments = $RfaComments . '<p><img src="' . plugin_dir_url(FVD_FILE) . 'assets/images/alert.png" width="20" height="15"> ' . ($json_return["Results"]["FullMotHistory"][$CurrentRecord]['rfrAndComments']['4']['text']) . '</p>';
            }		
            if (isset(($json_return["Results"]["FullMotHistory"][$CurrentRecord]['rfrAndComments']['5']['text']))){
            $RfaComments = $RfaComments . '<p><img src="' . plugin_dir_url(FVD_FILE) . 'assets/images/alert.png" width="20" height="15"> ' . ($json_return["Results"]["FullMotHistory"][$CurrentRecord]['rfrAndComments']['5']['text']) . '</p>';
            }		
            if (isset(($json_return["Results"]["FullMotHistory"][$CurrentRecord]['rfrAndComments']['6']['text']))){
            $RfaComments = $RfaComments . '<p><img src="' . plugin_dir_url(FVD_FILE) . 'assets/images/alert.png" width="20" height="15"> ' . ($json_return["Results"]["FullMotHistory"][$CurrentRecord]['rfrAndComments']['6']['text']) . '</p>';
            }									
            if (isset(($json_return["Results"]["FullMotHistory"][$CurrentRecord]['rfrAndComments']['7']['text']))){
            $RfaComments = $RfaComments . '<p><img src="' . plugin_dir_url(FVD_FILE) . 'assets/images/alert.png" width="20" height="15"> ' . ($json_return["Results"]["FullMotHistory"][$CurrentRecord]['rfrAndComments']['7']['text']) . '</p>';
            }
            if (isset(($json_return["Results"]["FullMotHistory"][$CurrentRecord]['rfrAndComments']['8']['text']))){
            $RfaComments = $RfaComments . '<p><img src="' . plugin_dir_url(FVD_FILE) . 'assets/images/alert.png" width="20" height="15"> ' . ($json_return["Results"]["FullMotHistory"][$CurrentRecord]['rfrAndComments']['8']['text']) . '</p>';
            }
            if (isset(($json_return["Results"]["FullMotHistory"][$CurrentRecord]['rfrAndComments']['9']['text']))){
            $RfaComments = $RfaComments . '<p><img src="' . plugin_dir_url(FVD_FILE) . 'assets/images/alert.png" width="20" height="15"> ' . ($json_return["Results"]["FullMotHistory"][$CurrentRecord]['rfrAndComments']['9']['text']) . '</p>';
            }


            return '<div>
            <h3 class="mottitledate">' . $completiondate . '</h3>
            <h2 class="mottitle' . $testresult . '">' . $testresult . '</h2></div>
            <div class="motspecs">
            <p><span style="font-weight: bold;">Mileage:</span> ' . $odometerValue . ' ' . $odometerUnit . '<br>
            <span style="font-weight: bold;">Expiry Date:</span> ' . $expiryDate . '</p>
            </div><div style="height:22px" aria-hidden="true" class="mot-spacer"></div>
            <h4>Failure/Rectifiable Notice(s)</h4>' . $RfaComments . '
            <div style="height:52px" aria-hidden="true" class="mot-spacer"></div>';


            }
            else
            {
                if ($CurrentRecord == '0')
                {
                    return 'No MOT Records To Display..<div style="height:52px" aria-hidden="true" class="mot-spacer"></div>';	
                }
            }
    }
    public function GetData($atts)
    {
        global $json_return;
	global $global;

        // Return Types
        switch ($atts["return"]) 
        {

            case "Registration":
                 return ($json_return["Results"]["InitialVehicleCheckModel"]["Vrm"]);
            break;
            case "RegistrationGraphic":
                return '<span class="RegistrationPlate">' . (isset($json_return["Results"]["InitialVehicleCheckModel"]["Vrm"])?$json_return["Results"]["InitialVehicleCheckModel"]["Vrm"]:''). '</span>';
            break;				
            case "BasicVehicleDetails":
                
                if (isset($json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"][($atts["value"])])) 
                {
                    return $json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"][($atts["value"])];
                }
                else
                {
                    return 'Not Available';	
                }
            break;
            case "TitleMOT":
                if (isset($json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"]["IsMOTDue"])&&($json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"]["IsMOTDue"]) == true)
                {
                    // mot is due
                    return  '<h2> <img src="' . plugin_dir_url(FVD_FILE) . '/assets/images/alert.png"><span style="color: #C75000;"> MOT</span></h2>';	
                }
                else
                {
                    // mot not due
                    return  '<h2> <img src="' . plugin_dir_url(FVD_FILE) . '/assets/images/tick.png"> MOT</h2>';	
                }
            break;
            case "BHP":
                if (get_option('FVDUKCreditUs') == 'no')
                {
                    return 'GOLD API ONLY';
                }
                else
                {
                    return (isset($json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"]["Bhp"])?$json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"]["Bhp"]:'');
                }
            break;

            case "TopSpeed":
                if (get_option('FVDUKCreditUs') == 'no')
                {
                    return 'GOLD API ONLY';
                }
                else
                {
                    $TopSpeedVal = (isset($json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"]["TopSpeed"])?$json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"]["TopSpeed"]:'');
                    if ($TopSpeedVal == 'Not Available')
                    {
                        return $TopSpeedVal;
                    }
                    else
                    {
                        return $TopSpeedVal . ' MPH';	
                    }	
                }
            break;

            case "zerotosixty":
                if (get_option('FVDUKCreditUs') == 'no')
                {
                    return 'GOLD API ONLY';
                }
                else
                {
                    $ZeroToSixty = (isset($json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"]["zerotosixty"])?$json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"]["zerotosixty"]:'');
                    if ($ZeroToSixty == 'Not Available')
                    {
                        return $ZeroToSixty;
                    }
                    else
                    {
                        return $ZeroToSixty . ' Secs';	
                    }
                }
            break;

            case "VehicleType":
                if (get_option('FVDUKCreditUs') == 'no')
                {
                    return 'GOLD API ONLY';
                }
                else
                {
                    return (isset($json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"]["VehicleType"])?$json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"]["VehicleType"]:'');
                }
            break;		
            case "BodyStyle":
                if (get_option('FVDUKCreditUs') == 'no')
                {
                    return 'GOLD API ONLY';
                }
                else
                {
                    return (isset($json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"]["BodyStyle"])?$json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"]["BodyStyle"]:'');
                }
            break;		

                    case "InsuranceGroup":
                    if (get_option('FVDUKCreditUs') == 'no'){
                    return 'GOLD API ONLY';
                    }else{
                    return (isset($json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"]["InsuranceGroup"])?$json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"]["InsuranceGroup"]:'');
                    }
                    break;			

                    case "TitleTAX":

                    $RoadTaxStatus = (isset($json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"]["RoadTaxStatusDescription"])?$json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"]["RoadTaxStatusDescription"]:'');

                    if ($RoadTaxStatus == 'Taxed'){
                    return  '<h2> <img src="' . plugin_dir_url(FVD_FILE) . 'assets/images/tick.png"> TAX</h2>';	
                    }
                    if ($RoadTaxStatus == 'Untaxed'){
                    return  '<h2> <img src="' . plugin_dir_url(FVD_FILE) . 'assets/images/alert.png"><span style="color: #C75000;"> TAX</span></h2>';	
                    }
                    if ($RoadTaxStatus == 'SORN'){
                    return  '<h2> <img src="' . plugin_dir_url(FVD_FILE) . 'assets/images/alert.png"><span style="color: #C75000;"> TAX (SORN)</span></h2>';	
                    }
                    if ($RoadTaxStatus == null){
                    return  '<h2> <img src="' . plugin_dir_url(FVD_FILE) . 'assets/images/alert.png"><span style="color: #C75000;"> TAX</span></h2>';	
                    }			

                    break;		


                    case "MakeImage":
                        return '<img class="MakeImage" src="' . FreeVehicleData()->sBaseURL.'/CARLOGOS/transp/' . strtoupper(($json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"]["Make"])) . '.png' . '">'; 
                    break;

                    case "taxbandimage":
                    $TBI = (isset($json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"]["Co2Marker"])?$json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"]["Co2Marker"]:'');
                    if (isset($TBI)) {
                    return '<img class="taxbandimage" src="' . plugin_dir_url(FVD_FILE) . 'assets/taxband/' . $TBI . '.png' . '">';
                    }
                    break;

                    case "mileagechart":
                    if ( in_array( 'wp-charts-and-graphs/wp-charts-and-graphs.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

                    if (isset(($atts["hexcolor"]))){
                    $ChartHexColor = ($atts["hexcolor"]);
                    }else{
                    $ChartHexColor = '#69d2e7';
                    }
                    if (isset(($atts["type"]))){
                    $ChartType = ($atts["type"]);
                    }else{
                    $ChartType = 'linechart';	
                    }

                    $MileageGraphData = ($json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"]["MileageGraphData"]);
                    $MileageGraphDates = ($json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"]["MileageGraphDates"]);

                    if (isset($MileageGraphData)){
                        return do_shortcode('[wpcharts type="' . $ChartType . '" bgcolor="' . $ChartHexColor . ':' . $ChartHexColor . ':' . $ChartHexColor . '" legend="false" titles="' . $MileageGraphDates . '" values="' . $MileageGraphData . '"]');
                    }
                    }else{
                    return '<strong>ALERT:</strong> WP Charts and Graphs – WordPress Chart Plugin needs to be installed and actived for mileage graph feature, you can download this at WordPress.org:<br>https://wordpress.org/plugins/wp-charts-and-graphs/';	
                    }

                    break;

                    case "VehicleImage":
                      
                    $ImgURL         = isset($json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"]["VehicleImageUrl"])?$json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"]["VehicleImageUrl"]:'';
                        if(isset($json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"]["YearOfManufacture"])&&
                                isset($json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"]["Make"])&&
                                isset($json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"]["Model"])){
                            $ImageFilename  = ($json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"]["YearOfManufacture"]) . '-' . ($json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"]["Make"]) . '-' . ($json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"]["Model"]) . '.jpg';
                        }else{
                            $ImageFilename  = 'AwaitingImage.jpg';
                        }
                    

                    $ImageFilename = str_replace("/","",$ImageFilename);
                    $ImageFilename = str_replace(":","",$ImageFilename);
                    $ImageFilename = str_replace("*","",$ImageFilename);
                    $ImageFilename = str_replace("?","",$ImageFilename);
                    

                    if (strpos($ImgURL, 'AwaitingImage') !== false) {
                        $VehicleImg = plugin_dir_url(FVD_FILE) . 'assets/vehicleimages/AwaitingImage.jpg';
                    }

                    $SaveImage = plugin_dir_path(FVD_FILE) . '/assets/vehicleimages/' . $ImageFilename;
                    
                    if (strpos($ImgURL, 'AwaitingImage') !== true) {
                        if (file_exists($SaveImage)){
                            $VehicleImg = plugin_dir_url(FVD_FILE) . 'assets/vehicleimages/' . $ImageFilename;
                        }else{
                            if(!empty($SaveImage)&&!empty($ImgURL)){
                                file_put_contents($SaveImage, file_get_contents($ImgURL));
                                $VehicleImg = plugin_dir_url(FVD_FILE) . 'assets/vehicleimages/' . $ImageFilename;
                            }
                            
                        }

                    if (filesize($SaveImage) == 0){
                    $VehicleImg = plugin_dir_url(FVD_FILE) . 'assets/vehicleimages/AwaitingImage.jpg';
                    unlink($SaveImage);
                    }
                    }

                    return '<img class="VehicleImage" src="' . $VehicleImg . '">';
                    break;

                    case "VehicleImageLogoReg":
                    $ImgURL = ($json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"]["VehicleImageUrl"]);
                    $ImageFilename = ($json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"]["YearOfManufacture"]) . '-' . ($json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"]["Make"]) . '-' . ($json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"]["Model"]) . '.jpg';

                    // make logo;
                    $MakeLogoURL = FreeVehicleData()->sBaseURL.'/CARLOGOS/transp/' . strtoupper(($json_return["Results"]["InitialVehicleCheckModel"]["BasicVehicleDetailsModel"]["Make"])) . '.png';

                    $ImageFilename = str_replace("/","",$ImageFilename);
                    $ImageFilename = str_replace(":","",$ImageFilename);
                    $ImageFilename = str_replace("*","",$ImageFilename);
                    $ImageFilename = str_replace("?","",$ImageFilename);

                    if (strpos($ImgURL, 'AwaitingImage') !== false) {
                        $VehicleImg = plugin_dir_url(FVD_FILE) . 'assets/vehicleimages/AwaitingImage.jpg';
                    }

                    $SaveImage = plugin_dir_path(FVD_FILE) . '/assets/vehicleimages/' . $ImageFilename;
                    if (strpos($ImgURL, 'AwaitingImage') !== true) {
                        if (file_exists($SaveImage)){
                            $VehicleImg = plugin_dir_url(FVD_FILE) . 'assets/vehicleimages/' . $ImageFilename;
                        }else{
                            if(!empty($ImgURL)){
                                file_put_contents($SaveImage, file_get_contents($ImgURL));
                            }
                            $VehicleImg = plugin_dir_url(FVD_FILE) . 'assets/vehicleimages/' . $ImageFilename;
                        }

                        if (filesize($SaveImage) == 0){
                            $VehicleImg = plugin_dir_url(FVD_FILE) . 'assets/vehicleimages/AwaitingImage.jpg';
                            unlink($SaveImage);
                        }
                    }

                    $ImgPrtX01 = '<div class="rapidcarcheckparent">';
                    $ImgPrtX02 = '<img class="rapidcarimg1" src="' . $VehicleImg . '">';
                    $ImgPrtX03 = '<img class="rapidcarimg2" src="' . $MakeLogoURL . '">';
                    $ImgPrtX04 = '<div class="regcplate">' . ($json_return["Results"]["InitialVehicleCheckModel"]["Vrm"]) . '</div></div>';
                    return $ImgPrtX01 . $ImgPrtX02 . $ImgPrtX03 . $ImgPrtX04;
                    break;				

            }
    }
    public function DisplaySearchBox($atts){
        $sActionURL =  isset($atts["page"])?$atts["page"]:'';
        
        // Class=RapidCarcheck can be changed to move box alignment to: RapidCarCheckLeft, RapidCarCheckCenter, RapidCarCheckRight
        $SearchBox = '<form id="SearchRegistration" class="RapidCarCheck" method="GET" action="' . ($sActionURL) . '">
        <div><input id="RegSearchBox" class="RegSearchBox" maxlength="9" name="Reg" placeholder="ENTER REG" type="text" value=""></div>
        <div><button class="vehicle-btn-search" type="submit" id="Reg">Check Vehicle Now >></button></div></form>';

        if (get_option( 'FVDUKCreditUs' ) == 'yes'){
            if (get_option( 'FVDUKCreditLink' ) == false){
                $SearchBox = $SearchBox . '<p class="vehiclecredit"><a href="'.FreeVehicleData()->sBaseURL.'/developer-api/" target="_blank">Powered By Rapid Car Check</a></p>';		
            }
            else{
                $CreditLink = get_option( 'FVDUKCreditLink' );
                $SearchBox = $SearchBox . '<p class="vehiclecredit"><a href="'.$CreditLink.'" target="_blank">Powered By Rapid Car Check</a></p>';		
            }
        }
	
        //periodically check API access level and SYNC locally
        if (get_option( 'RandomAPIAccLevel1' ) == false){
            add_option( 'RandomAPIAccLevel1', 1 );//set option / not already set
        }
       
        if (get_option( 'RandomAPIAccLevel1' ) >= 10){
                //check API level and sync // Periodic API access status check
            $json_api_usage1 = json_decode(wp_remote_retrieve_body(wp_remote_get( FreeVehicleData()->sBaseURL.'/FreeAccess/Requests.php?auth=LeCc0xMsd00fnsMF345o3&site=' . '&site=' . get_option( 'siteurl' ))), true);
            if (isset(($json_api_usage1["Limit"]))){
                if ($json_api_usage1["Limit"] == '20'){
                    $CreditUsStats1 = get_option('FVDUKCreditUs');
                    if ($CreditUsStats1 == 'no'){	
                        // do nothing, API and WP backend match
                    }
                    else{
                        // SYNC with API to downgrade to bronze [ to match both API and backend stats ].
                        update_option("FVDUKCreditUs", "no");
                    }
                }
                else{
                    // server set to gold API [automatically upgrade in WP backend]
                    update_option("FVDUKCreditUs", "yes");
                }
            }		
            //reset periodic check counter
            update_option("RandomAPIAccLevel1", 1);	
        }
        else{
            //increment periodic check counter
            $CounterSet = get_option( 'RandomAPIAccLevel1' );
            update_option("RandomAPIAccLevel1", $CounterSet+1);
        }
	//periodic API access level check
	return $SearchBox;
    }
    public function CallJson($atts)
    {
        global $json_return;
        global $global;

        if (isset($_GET['Reg'])) 
        {
            
            $json_return = json_decode(wp_remote_retrieve_body(wp_remote_get( FreeVehicleData()->sBaseURL.'/FreeAccess/' . '?vrm=' . preg_replace('/\s+/', '', $_GET['Reg']) . '&auth=ACCESSAPIENDPOINT' . '&site=' . get_option( 'siteurl' ))), true);
            //Log Request Locally
            $LogFile = plugin_dir_path(FVD_FILE) . '/assets/log.txt';
            $LocalLog = date("d M Y") . ' @ ' . date("H:i") . ' - ' . strtoupper($_GET['Reg']);
            file_put_contents($LogFile, $LocalLog.PHP_EOL , FILE_APPEND);


            if (isset($json_return["HasError"])&&($json_return["HasError"]) == true)
            {
                if (isset($_SERVER['HTTP_REFERER']))
                {
                    exit('Vehicle Not Found, Please <a href="' . $_SERVER['HTTP_REFERER'] . '">go back</a>' . ' and try again.');
                }
                else
                {
                    exit('Vehicle Not Found, Please try again.');
                }
            } 
        }
    }
}