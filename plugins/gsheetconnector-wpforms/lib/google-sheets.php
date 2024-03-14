<?php

if (!defined('ABSPATH'))
   exit;

include_once ( plugin_dir_path(__FILE__) . 'vendor/autoload.php' );

class wpfgsc_googlesheet {

   private $token;
   private $spreadsheet;
   private $worksheet;
   //const redirect = 'urn:ietf:wg:oauth:2.0:oob';

   /////=========desktop app
    const clientId_desk = '1075324102277-drjc21uouvq2d0l7hlgv3bmm67er90mc.apps.googleusercontent.com';
    const clientSecret_desk = 'RFM9hElCqJMsXyc8YNjhf9Zs';
    /////=========web app
    const clientId_web = '1075324102277-mdac3ljkp964kie3usoc8qj28laen2tb.apps.googleusercontent.com';
    const clientSecret_web = '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';

   private static $instance;

   public function __construct() {
      
   }

   public static function setInstance(Google_Client $instance = null) {
      self::$instance = $instance;
   }

   public static function getInstance() {
      if (is_null(self::$instance)) {
         throw new LogicException("Invalid Client");
      }

      return self::$instance;
   }

   //constructed on call
   public static function preauth($access_code) {

      $newClientSecret = get_option('is_new_client_secret_wpformsgsc');
      $clientId = ($newClientSecret == 1) ? wpfgsc_googlesheet::clientId_web : wpfgsc_googlesheet::clientId_desk;
      $clientSecret = ($newClientSecret == 1) ? wpfgsc_googlesheet::clientSecret_web : wpfgsc_googlesheet::clientSecret_desk;

      $client = new Google_Client();
      $client->setClientId($clientId);
      $client->setClientSecret($clientSecret);
      $client->setRedirectUri('https://oauth.gsheetconnector.com');
      //$client->setRedirectUri(wpfgsc_googlesheet::redirect);
      $client->setScopes(Google_Service_Sheets::SPREADSHEETS);
      $client->setScopes(Google_Service_Drive::DRIVE_METADATA_READONLY);
      $client->setAccessType('offline');
      $client->fetchAccessTokenWithAuthCode($access_code);
      $tokenData = $client->getAccessToken();

      wpfgsc_googlesheet::updateToken($tokenData);
   }


   /* preauth for manual client and secret id */
   //  public static function preauth_manual( $access_code, $client_id, $secret_id, $redirect_url ) {
   //        $client = new Google_Client();
   //        $client->setClientId( $client_id );
   //        $client->setClientSecret( $secret_id );
   //        $client->setRedirectUri( $redirect_url );
   //        $client->setScopes( Google_Service_Sheets::SPREADSHEETS );
   //        $client->setScopes( Google_Service_Drive::DRIVE_METADATA_READONLY );
   //        $client->setAccessType( 'offline' );
   //        $client->fetchAccessTokenWithAuthCode( $access_code );
   //        $tokenData = $client->getAccessToken();
   //        wpfgsc_googlesheet::updateToken_manual( $tokenData );
   // }

   // public static function updateToken_manual( $tokenData ) {
   //    $tokenData['expire'] = time() + intval( $tokenData['expires_in'] );
   //    try {
   //       $tokenJson = json_encode( $tokenData );
   //       update_option( 'gs_wpforms_token_manual', $tokenJson );
   //    } catch ( Exception $e ) {
   //       Gs_Connector_Utility::gs_debug_log( "Token write fail! - " . $e->getMessage() );
   //    }
   // }


   public static function updateToken($tokenData) {
     $expires_in = isset($tokenData['expires_in']) ? intval($tokenData['expires_in']) : 0;
    $tokenData['expire'] = time() + $expires_in;
      try {
         //$tokenJson = json_encode($tokenData);
         //update_option('wpform_gs_token', $tokenJson);
         
         //resolved - google sheet permission issues - START
         //echo "<pre>";
         //print_r($tokenData);
         //exit;
         if(isset($tokenData['scope'])){
            $permission = explode(" ", $tokenData['scope']);
            if((in_array("https://www.googleapis.com/auth/drive.metadata.readonly",$permission)) && (in_array("https://www.googleapis.com/auth/spreadsheets",$permission))) {
               update_option('wpform_gs_verify', 'valid');
            }else{
               update_option('wpform_gs_verify', 'invalid-auth');
            }
         }
         $tokenJson = json_encode($tokenData);
         update_option('wpform_gs_token', $tokenJson);
         //resolved - google sheet permission issues - END
         
      } catch (Exception $e) {
         Wpform_gs_Connector_Utility::gs_debug_log("Token write fail! - " . $e->getMessage());
      }
   }

  public function auth() {
        $tokenData = json_decode(get_option('wpform_gs_token'), true);
        if (!isset($tokenData['refresh_token']) || empty($tokenData['refresh_token'])) {
            throw new LogicException("Auth, Invalid OAuth2 access token");
            exit();
        }

        try {
            $newClientSecret = get_option('is_new_client_secret_wpformsgsc');
            $clientId = ($newClientSecret == 1) ? wpfgsc_googlesheet::clientId_web : wpfgsc_googlesheet::clientId_desk;
            $clientSecret = ($newClientSecret == 1) ? wpfgsc_googlesheet::clientSecret_web : wpfgsc_googlesheet::clientSecret_desk;
            
            $client = new Google_Client();
            $client->setClientId($clientId);
            $client->setClientSecret($clientSecret);

            $client->setScopes(Google_Service_Sheets::SPREADSHEETS);
            $client->setScopes(Google_Service_Drive::DRIVE_METADATA_READONLY);
            // $client->setScopes(Google_Service_Oauth2::USERINFO_EMAIL);
            $client->refreshToken($tokenData['refresh_token']);
            $client->setAccessType('offline');
            wpfgsc_googlesheet::updateToken($tokenData);

            self::setInstance($client);
        } catch (Exception $e) {
            throw new LogicException("Auth, Error fetching OAuth2 access token, message: " . $e->getMessage());
            exit();
        }
    }



  

   public function get_user_data() {
      $client = self::getInstance();

      $results = $this->get_spreadsheets();

      echo '<pre>';
      print_r($results);
      echo '</pre>';
      $spreadsheets = $this->get_worktabs('1mRuDMnZveDFQrmzHM9s5YkPA4F_dZkHJ1Gh81BvYB2k');
      echo '<pre>';
      print_r($spreadsheets);
      echo '</pre>';
      $this->setSpreadsheetId('1mRuDMnZveDFQrmzHM9s5YkPA4F_dZkHJ1Gh81BvYB2k');
      $this->setWorkTabId('Foglio1');
      $worksheetTab = $this->list_rows();
      echo '<pre>';
      print_r($worksheetTab);
      echo '</pre>';
   }

   //preg_match is a key of error handle in this case
   public function setSpreadsheetId($id) {
      $this->spreadsheet = $id;
   }

   public function getSpreadsheetId() {

      return $this->spreadsheet;
   }

   public function setWorkTabId($id) {
      $this->worksheet = $id;
   }

   public function getWorkTabId() {
      return $this->worksheet;
   }

   public function add_row( $data ) {

      try {

         $client = self::getInstance();
         $service = new Google_Service_Sheets($client);
         $spreadsheetId = $this->getSpreadsheetId();
         $work_sheets = $service->spreadsheets->get($spreadsheetId);
        


         if (!empty($work_sheets) && !empty($data)) {
            foreach ($work_sheets as $sheet) {
              
               $properties = $sheet->getProperties();
             
               $sheet_id = $properties->getSheetId();
              

               $worksheet_id = $this->getWorkTabId();
               


               if ($sheet_id == $worksheet_id) {
                  $worksheet_id = $properties->getTitle();
                  


                  $worksheetCell = $service->spreadsheets_values->get($spreadsheetId, $worksheet_id . "!1:1");




                  $insert_data = array();
                  if (isset($worksheetCell->values[0])) {
                     $insert_data_index = 0;

                     foreach ($worksheetCell->values[0] as $k => $name) {

                        if ($insert_data_index == 0) {
                           if (isset($data[$name]) && $data[$name] != '') {

                        $insert_data[] = $data[$name];
                           } else {
                              $insert_data[] = '';
                           }
                        } else {
                           if (isset($data[$name]) && $data[$name] != '') {
                              $insert_data[] = $data[$name];
                           } else {
                              $insert_data[] = '';
                           }
                        }
                        $insert_data_index++;
                     }
                  }
                  $range_new = $worksheet_id;

                  // Create the value range Object
                  $valueRange = new Google_Service_Sheets_ValueRange();

                  // set values of inserted data
                  $valueRange->setValues(["values" => $insert_data]);


                  // Add two values
                  // Then you need to add configuration
                  $conf = ["valueInputOption" => "USER_ENTERED"];

                  // append the spreadsheet(add new row in the sheet)
                  $result = $service->spreadsheets_values->append($spreadsheetId, $range_new, $valueRange, $conf);
               }
            }
         }
      } catch (Exception $e) {
         return null;
         exit();
      }
   }

   
   public function check_if_sheet_exist(){
      try{
         $client = self::getInstance();
         $service = new Google_Service_Sheets($client);
         $work_sheets = $service->spreadsheets->get($this->getSpreadsheetId());
         if(!empty($work_sheets)){
            $array_v['sheet'] = true;
            $array_v['tab'] = false;
            foreach($work_sheets as $sheet){
               $properties = $sheet->getProperties();
               $p_title = $properties->getSheetId();
               $w_title = $this->getWorkTabId();
               if($p_title == $w_title){
                  $array_v['tab'] = true;
               }
            }
         }
         else{
            $array_v['sheet'] = false;
            $array_v['tab'] = false;
         }
         
      }
      catch (Exception $e) {
         return null;
         exit();
      }
      
      return $array_v;
   }
        
        
   public function list_rows(){
      $work_tabs_list = array();
      try{
         $client = self::getInstance();
         $service = new Google_Service_Sheets($client);
         $spreadsheetId = $this->getSpreadsheetId();
         
         $work_sheets = $service->spreadsheets->get($spreadsheetId);
         if(!empty($work_sheets)){
            foreach($work_sheets as $sheet){

               $properties = $sheet->getProperties();
               $p_title = $properties->getSheetId();

               $w_title = $this->getWorkTabId();

               if($p_title == $w_title){
                  $w_title = $properties->getTitle();
       
                  $worksheetCell = $service->spreadsheets_values->get($spreadsheetId,$w_title."!1:1");
                  
                  if(isset($worksheetCell->values[0])){
                     foreach($worksheetCell->values[0] as $k=>$name){
                        $work_tabs_list[] = array(
                           'id' => $k,
                           'title' => $name,
                        );
                     }
                  }     
               }
            }
         }        
         
            
      }
      catch (Exception $e) {
         return null;
         exit();
      }
      return $work_tabs_list; 
   }
        
        
   //get all the spreadsheets
   public function get_spreadsheets() {
      $all_sheets = array();
      try {
         $client = self::getInstance();

         $service = new Google_Service_Drive($client);

         $optParams = array(
            'q' => "mimeType='application/vnd.google-apps.spreadsheet'"
         );
         $results = $service->files->listFiles($optParams);
        foreach ($results->files as $spreadsheet) {
            if (isset($spreadsheet['kind']) && $spreadsheet['kind'] == 'drive#file') {
               $all_sheets[] = array(
                  'id' => $spreadsheet['id'],
                  'title' => $spreadsheet['name'],
               );
            }
         }
      } catch (Exception $e) {
         return null;
         exit();
      }
      return $all_sheets;
   }
   
   //get worksheets title
   public function get_worktabs($spreadsheet_id) {


      $work_tabs_list = array();
      try {
         $client = self::getInstance();
         $service = new Google_Service_Sheets($client);
         $work_sheets = $service->spreadsheets->get($spreadsheet_id);


         foreach ($work_sheets as $sheet) {
            $properties = $sheet->getProperties();
            $work_tabs_list[] = array(
               'id' => $properties->getSheetId(),
               'title' => $properties->getTitle(),
            );
         }
      } catch (Exception $e) {
         return null;
         exit();
      }

      return $work_tabs_list;
   }
/**
    * Function - Adding custom column header to the sheet
    * @param string $sheet_name
    * @param string $tab_name
    * @param array $gs_map_tags 
    * @since 1.0
    */
   public function add_header($sheetname, $tabname, $final_header_array, $old_header) {
      $client = self::getInstance();
      $service = new Google_Service_Sheets($client);
      $spreadsheetId = $this->getSpreadsheetId();
      $work_sheets = $service->spreadsheets->get($spreadsheetId);
      
      $field_tag_array[] = '';
      if (!empty($work_sheets)) {
         foreach ($work_sheets as $sheet) {
             
            $properties = $sheet->getProperties();
            $sheet_id = $properties->getSheetId();
            $worksheet_id = $this->getWorkTabId();
           
            if ($sheet_id == $worksheet_id) {
               $worksheet_title = $properties->getTitle();
               $form_tag = isset($_POST['wp-custom-ck']) ? $_POST['wp-custom-ck'] : array();
               $form_tag_key = isset($_POST['wp-custom-header-key']) ? $_POST['wp-custom-header-key'] : "";
               $form_tag_placeholder = isset($_POST['wp-custom-header-placeholder']) ? $_POST['wp-custom-header-placeholder'] : "";
        $form_tag_column = isset($_POST['wp-custom-header']) ? $_POST['wp-custom-header'] : "";
        
        
         if (!empty($form_tag)) {
            foreach ($form_tag as $key => $value) {
               $wpf_key = $form_tag_key[$key];
               $wpf_val = (!empty($form_tag_column[$key]) ) ? $form_tag_column[$key] : $form_tag_placeholder[$key];
               if ($wpf_val !== "") {
                  $form_tag_array[$wpf_key] = $wpf_val;
                  $wpform_tags[] = $wpf_val;
               }
            }
         }
               $range = $worksheet_title . '!1:1';
              
               $values = array(array_values(array_filter($field_tag_array)));


               $count_old_header = count($old_header);
               $count_new_header = count($final_header_array);
               $data_values = array();

// If old header count is greater than new header count than empty the header
               if ($count_old_header !== 0 && $count_old_header > $count_new_header) {
                  for ($i = 0; $i <= $count_old_header; $i++) {
                     $column_name = isset($final_header_array[$i]) ? $final_header_array[$i] : "";
                     if ($column_name !== "") {
                        $data_values[] = $column_name;
                     } else {
                        $data_values[] = "";
                     }
                  }
               } else {

                  foreach ($final_header_array as $column_name) {
                     $data_values[] = $column_name;
                  }
               }

               $values = array($data_values);
             

               $requestBody = new Google_Service_Sheets_ValueRange([
                  'values' => $values
               ]);

               $params = [
                  'valueInputOption' => 'RAW'
               ];
               $response = $service->spreadsheets_values->update($spreadsheetId, $range, $requestBody, $params);
               
            
         }
      }
      }
   }
   
   
  /*******************************************************************************/
   /********************************  VERSION 3.1 *********************************/
   /*******************************************************************************/
   
   
   /** 
   * GFGSC_googlesheet::get_sheet_name
   * get WorkSheet Name
   * @since 3.1 
   * @param string $spreadsheet_id
   * @param string $tab_id
   * @retun string $tab_name
   **/
   public function get_sheet_name( $spreadsheet_id, $tab_id ) {
      
      $all_sheet_data = get_option( 'wpforms_gs_sheetId' );
      
      $tab_name = "";
      foreach( $all_sheet_data as $spreadsheet ) {
         
         if( $spreadsheet['id'] == $spreadsheet_id ) {
            $tabs = $spreadsheet['tabId'];
            
            foreach( $tabs as $name => $id ) {
               if( $id == $tab_id ) {
                  $tab_name = $name;
               }
            }
         }
      }
      
      return $tab_name;
   }
   
   
   /** 
   * GFGSC_googlesheet::get_sheet_name
   * get SpreadSheet Name
   * @since 3.1 
   * @param string $spreadsheet_id
   * @retun string $spreadsheetName
   **/
   public function get_spreadsheet_name( $spreadsheet_id ) {
      
      $all_sheet_data = get_option( 'wpforms_gs_sheetId' );
      
      $spreadsheetName = "";
      foreach( $all_sheet_data as $spreadsheet_name => $spreadsheet ) {
         
         if( $spreadsheet['id'] == $spreadsheet_id ) {
            $spreadsheetName = $spreadsheet_name;
         }
      }
      
      return $spreadsheetName;
   }
   
   public function add_bulk_rows_to_sheet( $spreadsheet_id, $tab_name, $row_data_arrays, $processed_entries = array() ) {
      
      if( ! $row_data_arrays ) {
         return;
      }
      
      $client = self::getInstance();   
      
      if( ! $client ) {
         return false;
      }
      
      try {    
         
         $service = new Google_Service_Sheets($client);
         $full_range = $tab_name."!A1:Z";
         $response   = $service->spreadsheets_values->get( $spreadsheet_id, $full_range );
         $get_values = $response->getValues();
         
         if( $get_values) {
            $row  = count( $get_values ) + 1;
         }
         else {
            $row = 1;
         }
         
         $total_row_data = count( $row_data_arrays );
         $start_index = $row;
         $end_index = $row + $total_row_data;
         
         foreach( $row_data_arrays as &$row_data ) {           
            ksort($row_data);
            
            foreach( $row_data as &$data ) {
               $data = str_replace( "{row}", $row, $data );
               $data = html_entity_decode($data, ENT_QUOTES, 'UTF-8');//Solved All ASCII (Currency) Code Issue
            }
            $row++;
         }
         
         $range = $tab_name."!A".$start_index.":A";
         $valueRange = new Google_Service_Sheets_ValueRange();

         $valueRange->setValues($row_data_arrays);

         // $range = 'Sheet1!A1:A';
         $conf = ["valueInputOption" => "USER_ENTERED"];
         $service->spreadsheets_values->append($spreadsheet_id, $range, $valueRange, $conf);
         
         do_action( "gfgc_after_bulk_entries_added", $row_data_arrays, $processed_entries );
         return true;
      } 
      catch (Exception $e) {
         Wpform_gs_Connector_Utility::gs_debug_log( __METHOD__ . " Error while adding row to sheet." . $e->getMessage() );
         return false;
      }
   }
   
   
   /** 
   * GFGSC_googlesheet::add_row_to_sheet
   * Send row data to sheet
   * @since 3.1 
   * @param string $spreadsheet_id
   * @param string $tab_name
   * @param array $row_data
   * @param array $is_header
   * @retun bool $result
   **/
   public function add_row_to_sheet( $spreadsheet_id, $tab_name, $row_data, $is_header = false ) {
      
      if( ! $row_data ) {
         return;
      }
      
      
      ksort($row_data);
      
      try {       
         $client = self::getInstance();   
         
         if( ! $client ) {
            return false;
         }
               
         $service = new Google_Service_Sheets($client);
         
         
         $full_range = $tab_name."!A1:Z";
         $response   = $service->spreadsheets_values->get( $spreadsheet_id, $full_range );
         $get_values = $response->getValues();
         
         if( $get_values) {
            $row  = count( $get_values ) + 1;
         }
         else {
            $row = 1;
         }
         
         if( $is_header ) {
            $row = 1;
         }
         
         foreach($row_data as &$data) {
            $data = str_replace( "{row}", $row, $data );
            $data = html_entity_decode($data, ENT_QUOTES, 'UTF-8');//Solved All ASCII (Currency) Code Issue
         }
         
         if( $is_header ) {
            $range = $tab_name . '!1:1';
            $valueRange = new Google_Service_Sheets_ValueRange();
            $valueRange->setValues(["values" => $row_data]);
            $conf = ["valueInputOption" => "RAW"];
            $result = $service->spreadsheets_values->update($spreadsheet_id, $range, $valueRange, $conf);   
            do_action( "wpgs_header_updated", $row_data );
         }
         else { 
            $range = $tab_name."!A".$row.":Z";
            $valueRange = new Google_Service_Sheets_ValueRange();
            $valueRange->setValues(["values" => $row_data]);
            $conf = ["valueInputOption" => "USER_ENTERED", "insertDataOption" => "INSERT_ROWS"];
            $result = $service->spreadsheets_values->append($spreadsheet_id, $range, $valueRange, $conf);               
            do_action( "wpgs_entry_added", $row_data );
         }
         
         return true;
      } 
      catch (Exception $e) {
         Wpform_gs_Connector_Utility::gs_debug_log( __METHOD__ . " Error while adding row to sheet." . $e->getMessage() );
         return false;
      }
   }
   
   
   /** 
   * GFGSC_googlesheet::get_header_row
   * Send row data to sheet
   * @since 3.1 
   * @param string $spreadsheet_id
   * @param string $tab_id
   * @retun array $header_cells
   **/
   public function get_header_row( $spreadsheet_id, $tab_id ) {
      
      $header_cells = array();
      try {
      
         $client = $this->getInstance();        
         
         if( ! $client ) {
            return false;
         }        
         
         $service = new Google_Service_Sheets($client);
         
         $work_sheets = $service->spreadsheets->get($spreadsheet_id);
         
         if( $work_sheets ) {
            
            foreach ($work_sheets as $sheet) {
            
               $properties = $sheet->getProperties();
               $work_sheet_id = $properties->getSheetId();
               
               if( $work_sheet_id == $tab_id ) {
                  
                  $tab_title = $properties->getTitle();
                  $header_row = $service->spreadsheets_values->get($spreadsheet_id, $tab_title . "!1:1");
                  
                  $header_row_values = $header_row->getValues();
                  
                  if( isset( $header_row_values[0] ) && $header_row_values[0] ) {
                     $header_cells = $header_row_values[0];
                  }     
               }
            }
         }
      }
      catch (Exception $e) {
         //echo $e->getMessage();
         $header_cells = array();
         return $header_cells;
      }
      
      return $header_cells;
   }
   
   
   /** 
   * GFGSC_googlesheet::sort_sheet_by_column
   * Sort Sheet by column Index
   * @since 3.1 
   * @param string $spreadsheet_id
   * @param string $tab_id
   * @param int $column_index
   * @param string $sort_order
   * @retun bool $result
   **/
   public function sort_sheet_by_column( $spreadsheet_id, $tab_id, $column_index, $sort_order = "ASCENDING" ) {
      
      try {
         if( $column_index !== false && is_numeric($column_index) ) {         
            $client = $this->getInstance();
            
            if( ! $client ) {
               return false;
            }
            
            $service = new Google_Service_Sheets($client);
            
            $args = array(
               "sortRange" => array(
                  'range' => array(
                     'sheetId' => $tab_id,
                     'startRowIndex' => 1,
                     'startColumnIndex' => 0,
                  ),
                  'sortSpecs' => array(
                     array(
                        'sortOrder' => $sort_order,
                        'dimensionIndex' => $column_index,
                     ),                   
                  ),
               )
            );
            
            $google_service_sheet_request = new Google_Service_Sheets_Request( $args );            
            $request = array( $google_service_sheet_request );          
            $args = array( "requests" => $request );
            $batchUpdateRequest = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest( $args );
            $result = $service->spreadsheets->batchUpdate($spreadsheet_id, $batchUpdateRequest);
            return true;
         }
      }
      
      catch (Exception $e) {
         Wpform_gs_Connector_Utility::gs_debug_log( __METHOD__ . " Error in sorting of sheet." . $e->getMessage() );
         return false;
      }
   }
   
   
   /** 
   * GFGSC_googlesheet::hex_color_to_google_rgb
   * Function to convert hex to rgb for google
   * @since 3.1 
   * @param string $hex
   * @retun array $rgba
   **/
   function hex_color_to_google_rgb($hex) {
   
      $rgb_return = array();
      
      $hex      = str_replace('#', '', $hex);
      $length   = strlen($hex);
      $rgb['red'] = hexdec($length == 6 ? substr($hex, 0, 2) : ($length == 3 ? str_repeat(substr($hex, 0, 1), 2) : 0));
      $rgb['green'] = hexdec($length == 6 ? substr($hex, 2, 2) : ($length == 3 ? str_repeat(substr($hex, 1, 1), 2) : 0));
      $rgb['blue'] = hexdec($length == 6 ? substr($hex, 4, 2) : ($length == 3 ? str_repeat(substr($hex, 2, 1), 2) : 0));
      
      foreach( $rgb as $key => $clr ) {
         $rgb_return[$key] = $clr / 255;
      }
      return $rgb_return;
   }
   
   
   /** 
   * GFGSC_googlesheet::freeze_row
   * Freeze the header
   * @since 3.1 
   * @param string $spreadsheet_id
   * @param string $tab_id
   * @param int $number_of_rows
   * @retun bool $result
   **/
   public function freeze_row( $spreadsheet_id, $tab_id, $number_of_rows = 1 ) {
   
      $number_of_rows = apply_filters( "gsheet_default_frozen_rows", $number_of_rows );
      
      try {
         $client = $this->getInstance();  
         
         if( ! $client ) {
            return false;
         }
         
         $service = new Google_Service_Sheets($client);
         $args = array(
            "updateSheetProperties" => array(
               'fields' => 'gridProperties.frozenRowCount',
               'properties' => [
                  'sheetId' => $tab_id,
                  'gridProperties' => array(
                     'frozenRowCount' => $number_of_rows
                  ),
               ],
            )
         );
         
         $google_service_sheet_request = new Google_Service_Sheets_Request( $args );            
         $request = array( $google_service_sheet_request );          
         $args = array( "requests" => $request );
         $batchUpdateRequest = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest( $args );
         $result = $service->spreadsheets->batchUpdate($spreadsheet_id, $batchUpdateRequest);
         return true;
      }
      catch (Exception $e) {
         
         Wpform_gs_Connector_Utility::gs_debug_log( __METHOD__ . " Error in freezing header rows." . $e->getMessage() );
         return false;
      }
   }

   
   /** 
   * GFGSC_googlesheet::set_alternate_colors
   * Set alternate colors
   * @since 3.1 
   * @param string $spreadsheet_id
   * @param string $tab_id
   * @param string $headerColor
   * @param string $oddColor
   * @param string $evenColor
   * @retun bool $result
   **/
   public function set_alternate_colors( $spreadsheet_id, $tab_id, $headerColor, $oddColor, $evenColor ) {
   
      try {
         $client = $this->getInstance();  
         
         if( ! $client ) {
            return false;
         }
         
         $service = new Google_Service_Sheets($client);
         $work_sheets = $service->spreadsheets->get($spreadsheet_id);
         
         $range = array( 'sheetId' => $tab_id );
         $args = array();
         
         $range_exist = false;
         
         $rowProperties = array();
         $rowProperties["headerColor"] = $headerColor ? $this->hex_color_to_google_rgb($headerColor) : $this->hex_color_to_google_rgb("#ffffff");
         $rowProperties["firstBandColor"] = $oddColor ? $this->hex_color_to_google_rgb($oddColor) : $this->hex_color_to_google_rgb("#ffffff");
         $rowProperties["secondBandColor"] = $evenColor ? $this->hex_color_to_google_rgb($evenColor) : $this->hex_color_to_google_rgb("#ffffff");
         
         $banded_range_id = 100;
         if( $tab_id != 0 ) {
            $generate_banded_range_id = substr($tab_id, 0, 4);
            $banded_range_id = $generate_banded_range_id;
         }
         
         $banding_request = array(  
            "bandedRange" => array(
               "bandedRangeId" => $banded_range_id,
               "range" => $range,
               "rowProperties" => $rowProperties,
            )
         ); 
         
         
         foreach ($work_sheets as $sheet) {
            $properties = $sheet->getProperties();       
            if( $properties->sheetId == $tab_id ) {            
               $bandedRanges = $sheet->getBandedRanges();
               foreach( $bandedRanges as $bandedRange  ) {              
                  if( $bandedRange->bandedRangeId == $banded_range_id ) {
                     $range_exist = true;
                  }
               }
            }
         }
         
         if( $range_exist ) {
            $args['updateBanding'] = $banding_request;
            $args['updateBanding']['fields'] = "*";
         }
         else {
            $args['addBanding'] = $banding_request;
         }
         
         $banding_request = new Google_Service_Sheets_Request( $args ); 
         $request = array( $banding_request );        
         
         $args = array( "requests" => $request );
         $batchUpdateRequest = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest( $args );
         $result = $service->spreadsheets->batchUpdate($spreadsheet_id, $batchUpdateRequest);
         return true;
      }
      catch (Exception $e) {
         echo " Error in setting alternate colors. $spreadsheet_id == $tab_id " . $e->getMessage();
         Wpform_gs_Connector_Utility::gs_debug_log( __METHOD__ . " Error in setting alternate colors." . $e->getMessage() );
         return false;
      }
   }

   
   /** 
   * GFGSC_googlesheet::remove_alternate_colors
   * Remove alternate colors
   * @since 3.1 
   * @param string $spreadsheet_id
   * @param string $tab_id
   * @retun bool $result
   **/
   public function remove_alternate_colors( $spreadsheet_id, $tab_id ) {
   
      try {
         $client = $this->getInstance();  
         
         if( ! $client ) {
            return false;
         }
         
         $service = new Google_Service_Sheets($client);
         $work_sheets = $service->spreadsheets->get($spreadsheet_id);
         
         $range = array( 'sheetId' => $tab_id );
         $args = array();
         
         $range_exist = false;
         
         $banded_range_id = 100;
         if( $tab_id != 0 ) {
            $generate_banded_range_id = substr($tab_id, 0, 4);
            $banded_range_id = $generate_banded_range_id;
         }
         
         foreach ($work_sheets as $sheet) {
            $properties = $sheet->getProperties();       
            if( $properties->sheetId == $tab_id ) {            
               $bandedRanges = $sheet->bandedRanges;           
               foreach( $bandedRanges as $bandedRange  ) {              
                  if( $bandedRange->bandedRangeId == $banded_range_id ) {
                     $range_exist = true;
                  }
               }
            }
         }
         
         if( $range_exist ) {
            $args = array( 
               'deleteBanding' => array(
                  "bandedRangeId" => $banded_range_id,
               )
            );
            
            $banding_request = new Google_Service_Sheets_Request( $args ); 
            $request = array( $banding_request );        
            
            $args = array( "requests" => $request );
            $batchUpdateRequest = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest( $args );
            $result = $service->spreadsheets->batchUpdate($spreadsheet_id, $batchUpdateRequest);
            return true;
         }
      }
      catch (Exception $e) {
         echo " Error in removing alternate colors." . $e->getMessage();
         Wpform_gs_Connector_Utility::gs_debug_log( __METHOD__ . " Error in removing alternate colors." . $e->getMessage() );
         return false;
      }
   }
   
   /** 
   * GFGSC_googlesheet::sync_with_google_account
   * Fetch Spreadsheets
   * @since 3.1 
   **/
   public function sync_with_google_account() {
      return;
      
      $return_ajax = false;
      
      if ( isset( $_POST['isajax'] ) && $_POST['isajax'] == 'yes' ) {
         check_ajax_referer( 'gf-ajax-nonce', 'security' );
         $init = sanitize_text_field( $_POST['isinit'] );
         $return_ajax = true;
      }
      
      include_once( GS_CONNECTOR_PRO_ROOT . '/lib/google-sheets.php');
      $worksheet_array = array();
      $sheetdata = array();
      $doc = new GFGSC_googlesheet();
      $doc->auth();
      $spreadsheetFeed = $doc->get_spreadsheets();
      
      if( ! $spreadsheetFeed ) {
         return false;
      }
      
      foreach ( $spreadsheetFeed as $sheetfeeds ) {
         $sheetId = $sheetfeeds['id'];
         $sheetname = $sheetfeeds['title'];

         $worksheetFeed = $doc->get_worktabs( $sheetId );

         foreach ( $worksheetFeed as $worksheet ) {
            $tab_id = $worksheet['id'];
            $tab_name = $worksheet['title'];


            $worksheet_array[] = $tab_name;
            $worksheet_ids[$tab_name] = $tab_id;
         }

         $sheetId_array[$sheetname] = array(
         "id" => $sheetId,
         "tabId" => $worksheet_ids
         );

         unset( $worksheet_ids );
         $sheetdata[$sheetname] = $worksheet_array;
         unset( $worksheet_array );
      }

      update_option( 'wpforms_gs_sheetId', $sheetId_array );
      update_option( 'gfgs_feeds', $sheetdata );

      if ( $return_ajax == true ) {
         if ( $init == 'yes' ) {
            wp_send_json_success( array( "success" => 'yes' ) );
         } 
         else {
            wp_send_json_success( array( "success" => 'no' ) );
         }
      }
   }
   
   
   /** 
   * GFGSC_googlesheet::gsheet_get_google_account
   * Get Google Account
   * @since 3.1 
   * @retun $user
   **/
   public function gsheet_get_google_account() {      
   
      try {
         $client = $this->getInstance();
         
         if( ! $client ) {
            return false;
         }
         
         $service = new Google_Service_Oauth2($client);
         $user = $service->userinfo->get();        
      }
      catch (Exception $e) {
         Wpform_gs_Connector_Utility::gs_debug_log( __METHOD__ . " Error in fetching user info: \n " . $e->getMessage() );
         return false;
      }
      
      return $user;
   }
   
   
   /** 
   * GFGSC_googlesheet::gsheet_get_google_account_email
   * Get Google Account Email
   * @since 3.1 
   * @retun string $email
   **/
   public function gsheet_get_google_account_email() {      
      $google_account = $this->gsheet_get_google_account(); 
      
      if( $google_account ) {
         return $google_account->email;
      }
      else {
         return "";
      }
   }
   
   
   /** 
   * GFGSC_googlesheet::gsheet_print_google_account_email
   * Get Google Account Email
   * @since 3.1 
   * @retun string $google_account
   **/
   public function gsheet_print_google_account_email() {
      try{
         $google_account = get_option("wpgs_email_account");
      
            $google_sheet = new wpfgsc_googlesheet();
            $google_sheet->auth();            
            $email = $google_sheet->gsheet_get_google_account_email();
            update_option("wpgs_email_account", $email);
            return $email;
         

      }catch(Exception $e){
         return false;
      }     
   }
   
   /** 
   * GFGSC_googlesheet::gsheet_print_google_account_email
   * Get Google Account Email
   * @since 3.1 
   * @param string $sheet_title
   * @retun array $response
   **/
   public function gsheet_create_google_sheet($sheet_title = "") {
   
      $response = false;
      
      try {
         $client = $this->getInstance();
         
         if( ! $client ) {
            return false;
         }
         
         
         $title = $sheet_title ? $sheet_title : "GSheetConnector WPForms";
         
         $properties = new Google_Service_Sheets_SpreadsheetProperties();
         $properties->setTitle($title);

         $spreadsheet = new Google_Service_Sheets_Spreadsheet();
         $spreadsheet->setProperties($properties);

         $sheet_service = new Google_Service_Sheets($client);     
         $create_spreadsheet = $sheet_service->spreadsheets->create( $spreadsheet );
         
         $spreadsheet = array(
            "spreadsheet_id" => $create_spreadsheet->spreadsheetId,
            "spreadsheet_name" => $title,
            "spreadsheet" => $create_spreadsheet,
            
         );
         $response = array( "result" => true, "spreadsheet" => $spreadsheet );
         
         do_action("gsheet_after_create_google_sheet", $response);
         $this->update_google_spreadsheets_option( $create_spreadsheet->spreadsheetId, $sheet_title );
      }
      catch (Exception $e) {
         $response = array( "result" => false, "error" => $e->getMessage() );
         Wpform_gs_Connector_Utility::gs_debug_log( __METHOD__ . " Error in creating google sheet: \n " . $e->getMessage() );
      }
      
      return $response;
   }
   
   
   public function update_google_spreadsheets_option( $spreadsheet_id, $sheet_title ) {
      
      $wpforms_gs_sheetId = get_option( 'wpforms_gs_sheetId' );
      $gfgs_feeds = get_option( 'wpforms_gs_feeds' );
      
      if( ! $wpforms_gs_sheetId ) {
         $wpforms_gs_sheetId = array();
      }
      if( ! $gfgs_feeds ) {
         $gfgs_feeds = array();
      }
      
      $wpforms_gs_sheetId[$sheet_title] = array(
         "id" => $spreadsheet_id,
         "tabId" => array(
            "Sheet1" => 0
         ),
      );
      
      $gfgs_feeds[$sheet_title] = array(
         "0" => "Sheet1",
      );
      
      update_option( 'wpforms_gs_sheetId', $wpforms_gs_sheetId );
      update_option( 'wpforms_gs_feeds', $gfgs_feeds );
      
   }


   /**
   * Generate token for the user and refresh the token if it's expired.
   *
   * @return array
   */
   public static function getClient_auth( $flag = 0,  $gscwpforms_clientId='', $gscwpforms_clientSecert=''){  
         $gscwpforms_client = new Google_Client();
         $gscwpforms_client->setApplicationName('Manage wpforms Forms with Google Spreadsheet');
         $gscwpforms_client->setScopes(Google_Service_Sheets::SPREADSHEETS_READONLY);
         $gscwpforms_client->setScopes(Google_Service_Drive::DRIVE_METADATA_READONLY);
         $gscwpforms_client->addScope(Google_Service_Sheets::SPREADSHEETS);
         $gscwpforms_client->addScope( 'https://www.googleapis.com/auth/userinfo.email' );
         //$gscwpforms_client->addScope( 'https://www.googleapis.com/auth/userinfo.profile' );
         $gscwpforms_client->setClientId($gscwpforms_clientId);
         $gscwpforms_client->setClientSecret($gscwpforms_clientSecert);
         $gscwpforms_client->setRedirectUri( esc_html( admin_url( 'admin.php?page=wpform-google-sheet-config' ) ) );
         $gscwpforms_client->setAccessType('offline');
         $gscwpforms_client->setApprovalPrompt('force');
         try{
         if(empty( $gscwpforms_auth_token )){
            $gscwpforms_auth_url = $gscwpforms_client->createAuthUrl();
            return $gscwpforms_auth_url;
         }
         if ( !empty( $gscwpforms_gscwpforms_accessToken ) ) {
            $gscwpforms_accessToken = json_decode($gscwpforms_gscwpforms_accessToken, true);
         } else { 
            if ( empty( $gscwpforms_auth_token ) ) {
               $gscwpforms_auth_url = $gscwpforms_client->createAuthUrl();
               return $gscwpforms_auth_url;
            }
            
         }
         
          $gscwpforms_client->setAccessToken( $gscwpforms_accessToken );
         // Refresh the token if it's expired.
         if ($gscwpforms_client->isAccessTokenExpired()) {
            // save refresh token to some variable
            $gscwpforms_refreshTokenSaved = $gscwpforms_client->getRefreshToken();       
            $gscwpforms_client->fetchAccessTokenWithRefreshToken($gscwpforms_client->getRefreshToken());
             // pass access token to some variable
            $gscwpforms_accessTokenUpdated = $gscwpforms_client->getAccessToken();
            // append refresh token
            $gscwpforms_accessTokenUpdated['refresh_token'] = $gscwpforms_refreshTokenSaved;
            //Set the new acces token
            $gscwpforms_accessToken = $gscwpforms_refreshTokenSaved;
            gscwpforms::gscwpforms_update_option('wpformssheets_google_accessToken', json_encode( $gscwpforms_accessTokenUpdated ) );
            $gscwpforms_accessToken = json_decode( json_encode( $gscwpforms_accessTokenUpdated ), true); 
            $gscwpforms_client->setAccessToken($gscwpforms_accessToken);
         }
      }catch( Exception $e ){
         if( $flag ){
            return $e->getMessage();
         }else{
            return false;  
         }     
      }
         return $gscwpforms_client;
      }

       /** 
       * GFGSC_googlesheet::gsheet_print_google_account_email
       * Get Google Account Email
       * @since 3.1 
       * @retun string $google_account
       **/
       public function gsheet_print_google_account_email_manual() {
          try{
             $google_account = get_option("wpforms_email_account_manual");
             if( false && $google_account ) {
                return $google_account;
             }
             else {
                
                $google_sheet = new wpfgsc_googlesheet();
                $google_sheet->auth();            
                $email = $google_sheet->gsheet_get_google_account_email();
                update_option("wpforms_email_account_manual", $email);
                return $email;
             }
          }catch(Exception $e){
             return false;
          }    
               
       }

   public static function revokeToken_auto($access_code){
      $newClientSecret = get_option('is_new_client_secret_wpformsgsc');
      $clientId = ($newClientSecret == 1) ? wpfgsc_googlesheet::clientId_web : wpfgsc_googlesheet::clientId_desk;
      $clientSecret = ($newClientSecret == 1) ? wpfgsc_googlesheet::clientSecret_web : wpfgsc_googlesheet::clientSecret_desk;

      $client = new Google_Client();
      $client->setClientId( $clientId );
      $client->setClientSecret( $clientSecret );
      $tokendecode = json_decode($access_code);
      $token = $tokendecode->access_token;
      $client->revokeToken( $token );
    }


    public static function revokeToken_manual($access_code){
      $clientId = get_option( 'gs_wpforms_client_id');
      $clientSecret =get_option( 'gs_wpforms_secret_id');
      $client = new Google_Client();
      $client->setClientId( $clientId );
      $client->setClientSecret( $clientSecret );
      $tokendecode = json_decode($access_code);
      $token = $tokendecode->access_token;
      $client->revokeToken( $token );
    }
   
   
}