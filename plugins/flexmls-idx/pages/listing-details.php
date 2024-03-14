<?php

#[\AllowDynamicProperties]
class flexmlsConnectPageListingDetails extends flexmlsConnectPageCore {

  private $listing_data;
  protected $search_criteria;
  protected $type;
  protected $property_detail_values;

  function __construct( $api, $type = null ){

    parent::__construct($api);
    $this->type = is_null($type) ? 'fmc_tag' : $type;

    add_filter( 'wpseo_title', array( $this, 'wpseo_title' ), 0 );
    add_filter( 'wpseo_canonical', array( $this, 'wpseo_canonical' ), 0 );
    add_filter( 'wp_robots', array($this, 'remove_wp_robots_closed_listing'), 0 );

    add_action('wp_head', array($this, 'open_graph_tags'), 0 );

  }

  function wp_head(){

    // WP-716: Add prerender next and previous tags for better performance
    if( $this->has_previous_listing() ){
        echo '<link rel="prerender" href="' . $this->browse_previous_url() . '">' . PHP_EOL;
    }
    if( $this->has_next_listing() ){
        echo '<link rel="prerender" href="' . $this->browse_next_url() . '">' . PHP_EOL;
    }
    if ( $this->uses_v2_template() ) {
      $this->render_template_styles();
    }
  }

  function pre_tasks($tag) {
    global $fmc_special_page_caught;
    global $fmc_api;

    add_action( 'wp_head', array( $this, 'wp_head' ) );

    // parse passed parameters for browsing capability
    list($params, $cleaned_raw_criteria, $context) = $this->parse_search_parameters_into_api_request();

    $this->search_criteria = $cleaned_raw_criteria;

    preg_match('/mls\_(.*?)$/', $tag, $matches);

    $id_found = $matches[1];

    $filterstr = "ListingId Eq '{$id_found}'";

    if ( $mls_id = flexmlsConnect::wp_input_get('m') ) {
      $filterstr .= " and MlsId Eq '".$mls_id."'";
    }

    $params = array(
        '_filter' => $filterstr,
        '_limit' => 1,
        '_expand' => 'Photos,Videos,OpenHouses,VirtualTours,Documents,Rooms,CustomFields,Supplement,FloPlans'
    );
    $result = $this->api->GetListings($params);

    $listing = (is_countable($result) && count($result) > 0) ? $result[0] : null;

    $fmc_special_page_caught['type'] = "listing-details";

    $this->listing_data = $listing;

    if ($listing != null) {
      $fmc_special_page_caught['page-title'] = flexmlsConnect::make_nice_address_title($listing);
      $fmc_special_page_caught['post-title'] = flexmlsConnect::make_nice_address_title($listing);
      $fmc_special_page_caught['page-url'] = flexmlsConnect::make_nice_address_url($listing);
    }
    else {
      $page = flexmlsConnect::get_no_listings_page_number();
      $page_data = get_page($page);
      $fmc_special_page_caught['page-title'] = "Listing Not Available";
      $fmc_special_page_caught['post-title'] = $page_data->post_title;

    }

  }


  function generate_page($from_shortcode = false) {
    global $fmc_api;
    global $fmc_special_page_caught;
    global $fmc_plugin_url;
    global $fmc_api_portal;

    if ($this->type == 'fmc_vow_tag' && !$fmc_api_portal->is_logged_in()){
      return "Sorry, but you must <a href={$fmc_api_portal->get_portal_page()}>log in</a> to see this page.<br />";
    }

    if ($this->listing_data == null) {
      if (flexmlsConnect::get_no_listings_pref() == 'page'){
        $page = flexmlsConnect::get_no_listings_page_number();
        $page_data = get_page($page);
        remove_filter('the_content', array('flexmlsConnectPage', 'custom_post_content'));
        echo apply_filters('the_content', $page_data->post_content);
      } else {
        echo "This listing is no longer available.";
      }
      return;
    }

    $standard_fields_plus = $this->api->GetStandardFields();
    $standard_fields_plus = $standard_fields_plus[0];
    // $custom_fields = $fmc_api->GetCustomFields();


    $options = get_option('fmc_settings');

    // set some variables
    $record =& $this->listing_data;
    $sf =& $record['StandardFields'];
    $listing_address = flexmlsConnect::format_listing_street_address($record);
    $first_line_address = htmlspecialchars($listing_address[0]);
    $second_line_address = htmlspecialchars($listing_address[1]);
    $one_line_address = htmlspecialchars($listing_address[2]);
    $one_line_address_add_slashes = addslashes($listing_address[2]);
    $one_line_without_zip_address = flexmlsSearchUtil::one_line_without_zip_address( $record );
    $mls_fields_to_suppress = flexmlsSearchUtil::mls_fields_to_suppress( $sf );

    $compList = flexmlsConnect::mls_required_fields_and_values("Detail",$record);

    //Organize Custom Fields ["Main"] and ["Details"] if they exist
    $custom_fields = array();
    if (is_array($record["CustomFields"][0]["Main"])) {
      foreach ($record["CustomFields"][0]["Main"] as $data) {
        foreach ($data as $group_name => $fields) {
          foreach ($fields as $field) {
            foreach ($field as $field_name => $val) {
              // check if the field already exists
              if( array_key_exists("Main", $custom_fields) and
                  array_key_exists($group_name, $custom_fields["Main"]) and
                  array_key_exists($field_name, $custom_fields["Main"][$group_name]) ) {
                // if it is an array, add the value to the end
                if(is_array($custom_fields["Main"][$group_name][$field_name])) {
                  $custom_fields["Main"][$group_name][$field_name][] = $val;
                }
                // if it's not, move the value to an array, and add the new value
                else {
                  $current_val = $custom_fields["Main"][$group_name][$field_name];
                  $custom_fields["Main"][$group_name][$field_name] = array($current_val, $val);
                }
              }
              // if the field doesn't already exsist, jsut add it normally
              else {
                $custom_fields["Main"][$group_name][$field_name]= $val;
              }
            }
          }
        }
      }
    }

    if (isset($record["CustomFields"][0]["Details"]) and is_array($record["CustomFields"][0]["Details"])) {
      foreach ($record["CustomFields"][0]["Details"] as $data) {
        foreach ($data as $group_name => $fields)
          foreach ($fields as $field)
            foreach ($field as $field_name => $val)
              $custom_fields["Details"][$group_name][$field_name]= $val;
      }
    }


    $MlsFieldOrder = $this->api->GetFieldOrder($sf["PropertyType"]);
    $property_features_values = array();
    if( $MlsFieldOrder ){
      foreach ($MlsFieldOrder as $field){
        foreach ($field as $name => $key){
          foreach ($key as $property){

            if (in_array($property["Label"],$mls_fields_to_suppress)){
              continue;
            }

            $is_standard_Field = false;
            if (isset($property["Domain"]) and (isset($sf[$property["Field"]]))){
              /* Temporary hack to prevent warnings until Field Ordering gets rewritten */
              if (is_array($sf[$property["Field"]])){
                continue;
              }
              if ($property["Domain"] == "StandardFields" and
                  flexmlsConnect::is_not_blank_or_restricted($sf[$property["Field"]])){
                $is_standard_Field = true;
              }
            }


            $detail_custom_bool = false;
            $custom_custom_bool = false;
            // If a field has a boolean for a value, mark it in the features section
            if (isset($custom_fields["Details"][$name][$property["Label"]])) {
              $detail_custom_bool = $custom_fields["Details"][$name][$property["Label"]] === true;
            }
            if (isset($custom_fields["Main"][$name][$property["Label"]])) {
              $custom_custom_bool = $custom_fields["Main"][$name][$property["Label"]] === true;
            }

            // Check if for Custom field Details
            $custom_details = false;
            if (isset($property["Detail"]) and isset($custom_fields["Details"][$name][$property["Label"]])){
              $custom_details = $property["Detail"] and flexmlsConnect::is_not_blank_or_restricted($custom_fields["Details"][$name][$property["Label"]]);
            }

            $custom_main = false;
            if ( isset($custom_fields["Main"][$name][$property["Label"]]) ) {
              $custom_main = flexmlsConnect::is_not_blank_or_restricted(
                $custom_fields["Main"][$name][$property["Label"]]
              );
            }

            //Standard Fields
            if( $is_standard_Field ){
              if( 'PublicRemarks' == $property[ 'Field' ] ){
                continue; //WP-325
              }
              switch( $property[ 'Label' ] ){
                case 'List Price':
                case 'Current Price':
                case 'Sold Price':
                if ( flexmlsConnect::is_not_blank_or_restricted( $sf['ClosePrice']) && $sf['MlsStatus'] == 'Closed') : 
                  if( $property[ 'Label' ] == 'List Price'){
                    $property[ 'Label' ] = 'Sold Price';
                  }
                  $this->add_property_detail_value( '$' . flexmlsConnect::gentle_price_rounding( $sf['ClosePrice'] ), $property[ 'Label' ], $name );
                else:
                  $this->add_property_detail_value( '$' . flexmlsConnect::gentle_price_rounding( $sf[ $property[ 'Field' ] ] ), $property[ 'Label' ], $name );
                endif;
                break;
                default:
                $this->add_property_detail_value( $sf[ $property[ 'Field' ] ], $property[ 'Label' ], $name );
              }
            }

            //Custom Fields with value of true are placed in property feature section
            else if ($detail_custom_bool or $custom_custom_bool){
              $property_features_values[$name][]= $property["Label"];
            }
            //Custom Fields - DETAIL
            else if ($custom_details){
              $this->property_detail_values[$name][] = "<b>".$property["Label"].":</b> " .
                $custom_fields["Details"][$name][$property["Label"]];
            }

            //Custom Fields - MAIN
            else if ($custom_main){
              $this->add_property_detail_value( $custom_fields["Main"][$name][$property["Label"]],
                $property["Label"], $name );

            }
          }
        }
      }
     }
     $room_fields = $this->api->GetRoomFields($sf['MlsId']);
     $room_names = array();
     $room_values = array();

     foreach ($room_fields as $mls_named_room){
       array_push($room_names,$mls_named_room["Label"]);
       array_push($room_values,array());
     }
     $room_information_values = array();

     if ( count($sf['Rooms']) > 0 ) {

       foreach ($sf['Rooms'] as $r) {

         foreach ($r['Fields'] as $rf) {
           foreach ($rf as $rfk => $rfv) {

             $label = null;
             if (is_array($room_fields) && array_key_exists($rfk, $room_fields)) {
               // since the given name is a key found in the metadata, use the metadata label for it
               $label = $room_fields[$rfk]['Label'];
             } else {
               $label = $rfk;
             }

             for ($i = 0; $i < count($room_names); $i++){
               if ($label == $room_names[$i]){
                 array_push($room_values[$i],$rfv);
               }
             }
             /*if     ($label == "Room") {
               $this_name = $rfv;
             }*/
           }
         }
       }

       //if all values in a field are zero append them to an array
       $toUnset = array();
       for ($i=0;$i<count($room_values);$i++){
         if (!array_filter($room_values[$i])) {
           array_push($toUnset,$i);
         }
       }
       //unset causes issues if attempt to do this in above for loop
       foreach ($toUnset as $index){
         unset($room_values[$index]);
         unset($room_names[$index]);
       }
       //reset the indexes to have order 0,1,2,...
       $room_values=array_values($room_values);
       $room_names= array_values($room_names);
     }


    // find the count for media stuff
    $count_photos = count($sf['Photos']);
    $count_videos = count($sf['Videos']);
    $count_tours = count($sf['VirtualTours']);
    $count_openhouses = count($sf['OpenHouses']);

    if ( $this->uses_v2_template() ) {
      ob_start();
  			global $fmc_plugin_dir;
  			require( $fmc_plugin_dir . "/views/v2/fmcListingDetails.php" );
  			$content = ob_get_contents();
  		ob_end_clean();
      return $content;
    }

    ob_start();
    flexmlsPortalPopup::popup_portal('detail_page');

    echo "<div class='flexmls_connect__prev_next'>";
    if ( $this->has_previous_listing() )
      echo "<button class='flexmls_connect__button left' href='". $this->browse_previous_url() ."'><img src='{$fmc_plugin_url}/assets/images/left.png' align='absmiddle' alt='Previous Listing' title='Previous Listing' /> Prev</button>";
    if ( $this->has_next_listing() )
      echo "<button class='flexmls_connect__button right' href='". $this->browse_next_url() ."'>Next <img src='{$fmc_plugin_url}/assets/images/right.png' align='absmiddle' alt='Next Listing' title='Next Listing' /></button>";
    echo "</div>";

    if ( ! empty( $record['FloPlans'] ) ) {
      $floplans_as_photos = $this->convert_floplans_array_into_photos( $record['FloPlans'] );
    }

    if ( is_array( $sf['Photos'] ) && ! empty( $floplans_as_photos ) ) {
      array_splice( $sf['Photos'], 1, 0, $floplans_as_photos );
    }

    // begin
    echo "<div class='flexmls_connect__sr_detail' title='{$one_line_address} - MLS# {$sf['ListingId']}'>";


    if ($sf['StateOrProvince'] == 'NY') {
      echo "<p>{$compList[0][0]} {$compList[0][1]}</p>";
    }

    echo "<hr class='flexmls_connect__sr_divider'>";
    echo "<div class='flexmls_connect__sr_address'>";

    // show price
    echo "<div class='flexmls_connect__ld_price'>";
      if(flexmlsConnect::is_not_blank_or_restricted($sf['ListPrice'])) echo '<div>$' . flexmlsConnect::gentle_price_rounding($sf['ListPrice'])."</div>";
    echo "</div>";
    fmcAccount::write_carts($record);

    // show top address details
    if (!empty($first_line_address)) echo "{$first_line_address}<br />";
    if (!empty($second_line_address)) echo "{$second_line_address}<br />";
    echo "MLS# {$sf['ListingId']}<br />";

    $status_class = ($sf['MlsStatus'] == 'Closed') ? 'status_closed' : '';

    if (($sf['MlsStatus'] != 'Active') and !in_array( "MlsStatus", $mls_fields_to_suppress))
      echo "Status: <span class='flexmls_connect__ld_status {$status_class}'>{$sf['MlsStatus']}</span><br />";

    // show under address details (beds, baths, etc.)
    $under_address_details = array();

    if ( flexmlsConnect::is_not_blank_or_restricted($sf['BedsTotal']) )
      $under_address_details[] = $sf['BedsTotal'] .' beds';
    if ( flexmlsConnect::is_not_blank_or_restricted($sf['BathsTotal']) )
      $under_address_details[] = $sf['BathsTotal'] .' baths';
    if ( flexmlsConnect::is_not_blank_or_restricted($sf['BuildingAreaTotal']) )
      $under_address_details[] = $sf['BuildingAreaTotal'] .' sqft';

    echo implode(" &nbsp;|&nbsp; ", $under_address_details) . "<br />";

    echo "</div>";
    echo "<hr class='flexmls_connect__sr_divider'>";


    // display buttons
    echo "<div class='flexmls_connect__sr_details'>";

    // first, media buttons are on the right
    echo "<div class='flexmls_connect__right'>";
    if ($count_videos > 0) {
      echo "<button class='video_click' rel='v-{$sf['ListingKey']}'>Videos ({$count_videos})</button>";
      if ($count_tours > 0) {
        echo " &nbsp;|&nbsp; ";
      }
    }
    if ($count_tours > 0) {
      echo "<button class='tour_click' rel='t-{$sf['ListingKey']}'>Virtual Tours ({$count_tours})</button>";
    }
    echo "</div>";

    // Share and Print buttons
    echo "<div class='flexmls_connect__ld_button_group'>";
      echo "<button class='print_click' onclick='flexmls_connect.print(this);'><img src='{$fmc_plugin_url}/assets/images/print.png'align='absmiddle' alt='Print' title='Print' /> Print</button>";

      $api_my_account = $this->api->GetMyAccount();

      if (isset($api_my_account['Name']) && isset($api_my_account['Emails'][0]['Address'])) : ?>
        <button onclick="flexmls_connect.scheduleShowing({
          'id': '<?php addslashes($sf['ListingKey']) ?>',
          'title': 'Schedule a Showing',
          'subject': '<?php echo $one_line_address_add_slashes; ?> - MLS# <?php echo addslashes($sf['ListingId']); ?>',
          'agentName': '<?php echo addslashes($api_my_account['Name'])?>',
          'agentEmail': '<?php echo $this->contact_form_agent_email($sf); ?>',
          'officeEmail': '<?php echo $this->contact_form_office_email($sf); ?>'
        })">
          <img src='<?php echo $fmc_plugin_url ?>/assets/images/showing.png' align='absmiddle' alt='Schedule a Showing' title='Schedule a Showing' /> Schedule a Showing
        </button>
      <?php endif ?>
      <button onclick="flexmls_connect.contactForm({
        'title': 'Ask a Question',
        'subject': '<?php echo $one_line_address_add_slashes; ?> - MLS# <?php echo addslashes($sf['ListingId'])?> ',
        'agentEmail': '<?php echo $this->contact_form_agent_email($sf); ?>',
        'officeEmail': '<?php echo $this->contact_form_office_email($sf); ?>',
        'id': '<?php echo addslashes($sf['ListingId']); ?>'
      });">
        <img src='<?php echo $fmc_plugin_url ?>/assets/images/admin_16.png' align='absmiddle' alt='Ask a Question' title='Ask a Question' />
        Ask a Question
      </button>
    </div>
    <?php

    echo "<div class='flexmls_connect__success_message' id='flexmls_connect__success_message'></div>";

    echo "</div>";

    echo "<hr class='flexmls_connect__sr_divider'>";

    // hidden divs for tours and videos (colorboxes)
    echo "<div class='flexmls_connect__hidden2'></div>";
    echo "<div class='flexmls_connect__hidden3'></div>";

    // Photos
    if (count($sf['Photos']) >= 1) {
    $main_photo_url = $sf['Photos'][0]['Uri640'];
    $main_photo_caption = htmlspecialchars($sf['Photos'][0]['Caption'], ENT_QUOTES);

      //set alt value
      if(!empty($main_photo_caption)){
        $main_photo_alt = $main_photo_caption;
      }
      elseif(!empty($one_line_address)){
        $main_photo_alt = $one_line_address;
      }
      else{
        $main_photo_alt = "Photo for listing #" . $sf['ListingId'];
      }

    //set title value
    $main_photo_title = "Photo for ";
    if(!empty($one_line_address)) {
      $main_photo_title .= $one_line_address . " - ";
    }
    $main_photo_title .= "Listing #" . $sf['ListingId'];

    echo "<div class='flexmls_connect__photos'>";
      echo "<div class='flexmls_connect__photo_container'>";
      echo "<img src='{$main_photo_url}' class='flexmls_connect__main_image' title='{$main_photo_title}' alt='{$main_photo_alt}' />";
      echo "</div>";

    // photo pager
    echo "<div class='flexmls_connect__photo_pager'>";

      echo "<div class='flexmls_connect__photo_switcher'>";
        echo "<button><img src='{$fmc_plugin_url}/assets/images/left.png' alt='Previous Photo' title='Previous Photo' /></button>";
        echo "&nbsp; <span>1</span> / {$count_photos} &nbsp;";
        echo "<button><img src='{$fmc_plugin_url}/assets/images/right.png' alt='Next Photo' title='Next Photo' /></button>";
      echo "</div>";

      // colobox photo popup
      echo "<button class='photo_click flexmls_connect__ld_larger_photos_link'>View Larger Photos ({$count_photos})</button>";

    echo "</div>";

    // filmstrip
    echo "<div class='flexmls_connect__filmstrip'>";
      if ($count_photos > 0) {
      $ind = 0;
        foreach ($sf['Photos'] as $p) {
          if(!empty($p['Caption'])){
            $img_alt_attr = htmlspecialchars($p['Caption'], ENT_QUOTES);
          }
          elseif(!empty($one_line_address)){
            $img_alt_attr = $one_line_address;
          }
          else{
            $img_alt_attr = "Photo for listing #" . $sf['ListingId'];
          }

          $img_title_attr = "Photo for ";
          if(!empty($one_line_address)){
            $img_title_attr .= $one_line_address . " - ";
          }
          $img_title_attr .= "Listing #" . $sf['ListingId'];

          echo "<img src='{$p['UriThumb']}' ind='{$ind}' fullsrc='{$p['UriLarge']}' alt='{$img_alt_attr}' title='{$img_title_attr}' width='65' /> ";

        $ind++;
        }
      }
    echo "</div>";
    echo "</div>";

    // hidden div for colorbox
    echo "<div class='flexmls_connect__hidden'>";
      if ($count_photos > 0) {
        foreach ($sf['Photos'] as $p) {
          echo "<a href='{$p['UriLarge']}' data-connect-ajax='true' rel='p-{$sf['ListingKey']}' title='".htmlspecialchars($p['Caption'], ENT_QUOTES)."'></a>";
        }
      }
      echo "</div>";
    }


    // Open Houses
    if ($count_openhouses > 0) {
      $this_o = $sf['OpenHouses'][0];
      echo "<div class='flexmls_connect__sr_openhouse'><em>Open House</em> (". $this_o['Date'] ." - ". $this_o['StartTime'] ." - ". $this_o['EndTime'] .")</div>";
    }


    // Property Dscription
    if ( flexmlsConnect::is_not_blank_or_restricted($sf['PublicRemarks']) ) {
      echo "<br /><b>Property Description</b><br />";
      echo $sf['PublicRemarks'];
      echo "<br /><br />";
    }

    // Tabs
    echo "<div class='flexmls_connect__tab_div'>";
    echo "<div class='flexmls_connect__tab active' group='flexmls_connect__detail_group'>Details</div>";
   if ( isset ( $options['google_maps_api_key'] ) && $options['google_maps_api_key'] && flexmlsConnect::is_not_blank_or_restricted($sf['Latitude']) && flexmlsConnect::is_not_blank_or_restricted($sf['Longitude']) ){
        echo "<div class='flexmls_connect__tab' group='flexmls_connect__map_group'>Maps</div>";
    }
      if ($sf['DocumentsCount'])
        echo "<div class='flexmls_connect__tab' group='flexmls_connect__document_group'>Documents</div>";
    echo "</div>";


    // build the Details portion of the page
    echo "<div class='flexmls_connect__tab_group' id='flexmls_connect__detail_group'>";

    // render the results now
  if( $this->property_detail_values ){
      foreach ($this->property_detail_values as $k => $v) {
        echo "<div class='flexmls_connect__ld_detail_table'>";
          echo "<div class='flexmls_connect__detail_header'>{$k}</div>";
          echo "<div class='flexmls_connect__ld_property_detail_body columns2'>";

            $details_count = 0;

            foreach ($v as $value) {
              $details_count++;

              if ($details_count === 1) {
                echo "<div class='flexmls_connect__ld_property_detail_row'>";
              }
              echo "<div class='flexmls_connect__ld_property_detail'>{$value}</div>";

              if ($details_count === 2) {
                echo "</div>"; // end row
                $details_count = 0;
              }
            }
            if ($details_count === 1) {
              // details ended earlier without closing the last row
              echo "</div>";
            }
          echo "</div>"; // end details body
        echo "</div>"; // end details table
      }
     }

    echo "<div class='flexmls_connect__ld_detail_table'>";
      echo "<div class='flexmls_connect__detail_header'>Property Features</div>";
      echo "<div class='flexmls_connect__ld_property_detail_body'>";

        foreach ($property_features_values as $k => $v) {
          $value = "<b>".$k.": </b>";
          foreach($v as $x){
            $value .= $x."; ";
          }
          $value = trim($value,"; ");

          echo "<div class='flexmls_connect__ld_property_detail_row'>";
            echo "<div class='flexmls_connect__ld_property_detail'>{$value}</div>";
          echo "</div>";
        }
      echo "</div>";
    echo "</div>";

    if ( flexmlsConnect::is_not_blank_or_restricted( $sf["Supplement"] ) ) {
      echo "<div class='flexmls_connect__ld_detail_table'>";
        echo "<div class='flexmls_connect__detail_header'>Supplements</div>";
        echo "<div class='flexmls_connect__ld_property_detail_body'>";
          echo "<div class='flexmls_connect__ld_property_detail_row'>";
            echo "<div class='flexmls_connect__ld_property_detail'>{$sf["Supplement"]}</div>";
          echo "</div>";
        echo "</div>";
      echo "</div>";
    }

    // build the Room Information portion of the page

    if ( count($sf['Rooms']) > 0 ) {
      $room_count = isset($room_values[0]) ? count($room_values[0]) : false;
      if ($room_count) {
        echo "<div class='flexmls_connect__detail_header'>Room Information</div>";
        echo "<table width='100%'>";
        echo "  <tr>";
        foreach ($room_names as $room){
          echo "    <td><b>{$room}</b></td>";
        }
        echo "  </tr>";

        for ($x = 0; $x < $room_count; $x++)
        {
          echo "  <tr " . ($x % 2 == 0 ? "class='flexmls_connect__sr_zebra_on'" : "") . ">";
          for ($i = 0; $i < count($room_values); $i++){
            echo "<td>{$room_values[$i][$x]}</td>";
          }
          echo "</tr>";
        }
        echo "</table>";
      }

      echo "</div>";

      }

     echo "</div>";

      // map details, if present
      if ( isset ( $options['google_maps_api_key'] ) && $options['google_maps_api_key'] && flexmlsConnect::is_not_blank_or_restricted($sf['Latitude']) && flexmlsConnect::is_not_blank_or_restricted($sf['Longitude']) ){
      echo "<div class='flexmls_connect__tab_group' id='flexmls_connect__map_group'>
        <div id='flexmls_connect__map_canvas' latitude='{$sf['Latitude']}' longitude='{$sf['Longitude']}'></div>
        </div>";
      }


      //Documents tab
      if ($sf['DocumentsCount'])
      {

        echo "<div class='flexmls_connect__tab_group' id='flexmls_connect__document_group' style='display:none'>";
        echo "<div class='flexmls_connect__detail_header'>Listing Documents</div>";
        echo "<table>";

        //Image extensions to show colorbox for
        $fmc_colorbox_extensions = array('gif', 'png');

        foreach ($sf['Documents'] as $fmc_document){
          if ($fmc_document['Privacy']=='Public'){
            echo "<tr class=flexmls_connect__zebra><td>";
            $fmc_extension = explode('.',$fmc_document['Uri']);
            $fmc_extension = ($fmc_extension[count($fmc_extension)-1]);
            if ($fmc_extension == 'pdf'){
              $fmc_file_image = $fmc_plugin_url . '/assets/images/pdf-tiny.gif';
              $fmc_docs_class = "class='fmc_document_pdf'";
            }
            elseif (in_array($fmc_extension, $fmc_colorbox_extensions)){
              $fmc_file_image = $fmc_plugin_url . '/assets/images/image_16.gif';
              $fmc_docs_class = "class='fmc_document_colorbox'";
            }
            else{
              $fmc_file_image = $fmc_plugin_url . '/assets/images/docs_16.gif';
            }
            echo "<a $fmc_docs_class value={$fmc_document['Uri']}><img src='{$fmc_file_image}' align='absmiddle' alt='View Document' title='View Document' /> {$fmc_document['Name']} </a>";

            echo "</td></tr>";
          }

        }
        echo "</table>";
        echo "</div>";
      }


      echo "  <hr class='flexmls_connect__sr_divider'>";
    // disclaimer
      echo "  <div class='flexmls_connect__idx_disclosure_text'>";

  if ($sf['StateOrProvince'] != 'NY'){
      foreach ($compList as $reqs){
          if (flexmlsConnect::is_not_blank_or_restricted($reqs[1])){
              if ($reqs[0] == 'LOGO'){
                  echo "<img style='padding-bottom: 5px' src='{$reqs[1]}' alt='{$one_line_address} - MLS# {$sf['ListingId']}' title='{$one_line_address} - MLS# {$sf['ListingId']}' />";
                  continue;
                }
                echo "<p>{$reqs[0]} {$reqs[1]}</p>";
              }
          }
  }
?>
      <?php if ( flexmlsConnect::is_not_blank_or_restricted( $sf['CompensationDisclaimer'] ) ) : ?>
    <hr />
    <div class="compensation-disclaimer">
      <?php echo $sf['CompensationDisclaimer']; ?>
    </div>
    <hr />
    <?php endif; ?>
<?php
      echo "<p>";
      echo flexmlsConnect::get_big_idx_disclosure_text();
      echo "</p><hr />";
?>

<?php if( flexmlsConnect::NAR_broker_attribution( $sf ) ) : ?>
      <div class='listing-req'>Broker Attribution: 
        <?php echo flexmlsConnect::NAR_broker_attribution( $sf ); ?>
      </div>
      <hr />
      <?php endif; ?>

    <div class="fbs-branding" style="text-align: center;">
    <?php echo flexmlsConnect::fbs_products_branding_link(); ?>
    </div>
<?php
      echo "</div>";

  // end
    $content = ob_get_contents();
    ob_end_clean();

    return $content;
  }

  function has_previous_listing() {
    return ( flexmlsConnect::wp_input_get('p') == 'y' ) ? true : false;
  }

  function has_next_listing() {
    return ( flexmlsConnect::wp_input_get('n') == 'y' ) ? true : false;
  }

  function browse_next_url() {
    $link_criteria = $this->search_criteria;
    $link_criteria['id'] = $this->listing_data['StandardFields']['ListingId'];
    return flexmlsConnect::make_nice_tag_url('next-listing', $link_criteria, $this->type);
  }

  function browse_previous_url() {
    $link_criteria = $this->search_criteria;
    $link_criteria['id'] = $this->listing_data['StandardFields']['ListingId'];
    return flexmlsConnect::make_nice_tag_url('prev-listing', $link_criteria,$this->type);
  }

  function remove_wp_robots_closed_listing($robots) {
    
    $link_criteria_mls_status = $this->listing_data['StandardFields']['MlsStatus'];
    
    if ( $link_criteria_mls_status == 'Closed' && get_option( 'blog_public' ) != 0 ) {
          $robots['noindex'] = true;
          $robots['nofollow'] = true;
      return $robots;
    }
  
    return $robots;
  
  }

  /**
   * Adds lines to $this->$property_detail_values. The line will only be added
   * if it doesn't already exist.
   *
   * @param $element The element that should be added. Can be array or string.
   * @param $label The label that should precede the element.
   * @param $field_group The group that this line should be added to.
   */
  private function add_property_detail_value($element, $label, $field_group) {

    if ( is_array($element) ){
      foreach ( $element as $value) {
        $this->add_property_detail_value($value, $label, $field_group);
      }
    } else {

      if(!is_array($this->property_detail_values)){
        $this->property_detail_values = Array();
      }

      $line = "<b>".$label.":</b> " . $element;
      if( !array_key_exists($field_group, $this->property_detail_values) or
          is_array($this->property_detail_values) && !in_array($line, $this->property_detail_values[$field_group]) ) {
        $this->property_detail_values[$field_group][] = $line;
      }
    }
  }

  function wpseo_title( $title ){
    $address = flexmlsConnect::format_listing_street_address( $this->listing_data );
    $title = array(
      'title' => $address[ 2 ],
      'site' => get_bloginfo( 'name' )
    );
    $sep = apply_filters( 'document_title_separator', '-' );
    $title = apply_filters( 'document_title_parts', $title );
    $title = implode( " $sep ", array_filter( $title ) );
    $title = capital_P_dangit( $title );
    return $title;
  }

  function open_graph_tags() {
    $site_name = get_bloginfo('name');
    $title = flexmlsConnect::make_nice_address_title($this->listing_data);
    $thumbnail = $this->listing_data['StandardFields']['Photos'][0]['Uri1280'];
    $description = substr($this->listing_data['StandardFields']['PublicRemarks'], 0, 140);
    $url = flexmlsConnect::make_nice_address_url($this->listing_data);

    echo "<!-- Flexmls® IDX WordPress Plugin - OpenGraph Tags for Listing Detail pages -->" . PHP_EOL;
    echo "<meta property='og:site_name' content='{$site_name}' />" . PHP_EOL;
    echo "<meta property='og:title' content='{$title}' />" . PHP_EOL;
    echo "<meta property='og:image' content='{$thumbnail}' />" . PHP_EOL;
    echo "<meta property='og:description' content=\"{$description}\" />" . PHP_EOL;
    echo "<meta property='og:url' content='{$url}' />" . PHP_EOL;
    echo "<meta property='og:type' content='website' />" . PHP_EOL;
    echo "<meta name='twitter:card' content='summary_large_image' />" . PHP_EOL;
    echo "<meta name='twitter:image' content='{$thumbnail}' />" . PHP_EOL;
    echo "<meta name='twitter:description' content=\"{$description}\" />" . PHP_EOL;
    echo "<meta name='twitter:title' content='{$title}' />" . PHP_EOL;
    echo "<!-- / Flexmls® IDX WordPress Plugin -->" . PHP_EOL;
  }

function filter_presenters( $filter ) {
    if (($key = array_search('Yoast\WP\SEO\Presenters\Twitter\Image_Presenter', $filter)) !== false) {
      unset($filter[$key]);
    }
    if (($key = array_search('Yoast\WP\SEO\Presenters\Open_Graph\Image_Presenter', $filter)) !== false) {
      unset($filter[$key]);
    }
    if (($key = array_search('Yoast\WP\SEO\Presenters\Twitter\Description_Presenter', $filter)) !== false) {
      unset($filter[$key]);
    }
    if (($key = array_search('Yoast\WP\SEO\Presenters\Open_Graph\Site_Name_Presenter', $filter)) !== false) {
      unset($filter[$key]);
    }

    return $filter;
  }

	function iframe_from_html_or_url( $html_or_url ) {
		if ( strpos( $html_or_url, '<iframe' ) !== false ) {
			return $html_or_url;
		} else {
			return '<iframe src="' . esc_url( $html_or_url ) . '"></iframe>';
		}
	}
}
