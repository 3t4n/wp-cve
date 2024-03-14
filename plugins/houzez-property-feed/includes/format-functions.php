<?php

function get_houzez_property_feed_import_formats()
{
    $curl_warning = !function_exists('curl_version') ? __( 'cURL must be enabled in order to use this format', 'houzezpropertyfeed' ) : '';
    $simplexml_warning = !class_exists('SimpleXMLElement') ? __( 'SimpleXML must be enabled in order to use this format', 'houzezpropertyfeed' ) : '';

    $uploads_dir = wp_upload_dir();
    if( $uploads_dir['error'] === FALSE )
    {
        $uploads_dir = $uploads_dir['basedir'] . '/houzez_property_feed_import/';
    }

    $formats = array(
        '10ninety' => array(
            'name' => __( '10ninety', 'houzezpropertyfeed' ),
            'fields' => array(
                array(
                    'id' => 'xml_url',
                    'label' => __( 'XML URL', 'houzezpropertyfeed' ),
                    'type' => 'text',
                    'placeholder' => 'https://',
                )
            ),
            'address_fields' => array( 'ADDRESS_2', 'ADDRESS_3', 'TOWN', 'ADDRESS_4', 'COUNTY' ),
            'taxonomy_values' => array(
                'sales_status' => array(
                    '0' => 'Available',
                    '1' => 'SSTC',
                    '2' => 'SSTCM (Scotland only)',
                    '3' => 'Under Offer',
                    '6' => 'Sold',
                ),
                'lettings_status' => array(
                    '0' => 'Available',
                    '4' => 'Reserved',
                    '5' => 'Let Agreed',
                    '7' => 'Let',
                ),
                'property_type' => array(
                    '0' => 'Not Specified',
                    '1' => 'Terraced',
                    '2' => 'End of Terrace',
                    '3' => 'Semi-Detached ',
                    '4' => 'Detached',
                    '5' => 'Mews',
                    '6' => 'Cluster House',
                    '7' => 'Ground Flat',
                    '8' => 'Flat',
                    '9' => 'Studio',
                    '10' => 'Ground Maisonette',
                    '11' => 'Maisonette',
                    '12' => 'Bungalow',
                    '13' => 'Terraced Bungalow',
                    '14' => 'Semi-Detached Bungalow',
                    '15' => 'Detached Bungalow',
                    '16' => 'Mobile Home',
                    '17' => 'Hotel',
                    '18' => 'Guest House',
                    '20' => 'Land',
                    '21' => 'Link Detached House',
                    '22' => 'Town House',
                    '23' => 'Cottage',
                    '24' => 'Chalet',
                    '27' => 'Villa',
                    '28' => 'Apartment',
                    '29' => 'Penthouse',
                    '30' => 'Finca',
                    '43' => 'Barn Conversion',
                    '44' => 'Serviced Apartments',
                    '45' => 'Parking',
                    '46' => 'Sheltered Housing',
                    '47' => 'Retirement Property',
                    '48' => 'House Share',
                    '49' => 'Flat Share',
                    '51' => 'Garages',
                    '52' => 'Farm House',
                    '53' => 'Equestrian',
                    '56' => 'Duplex',
                    '59' => 'Triplex',
                    '68' => 'Barn',
                    '95' => 'Village House',
                    '107' => 'Farm Land',
                    '110' => 'Plot',
                    '113' => 'Country House',
                    '116' => 'Stone House',
                    '117' => 'Caravan',
                    '118' => 'Lodge',
                    '120' => 'Manor House',
                    '121' => 'Stately Home',
                    '125' => 'Off-Plan',
                    '128' => 'Semi-detached Villa',
                    '131' => 'Detached Villa',
                    '142' => 'Hotel Room',
                    '143' => 'Block of Apartments',
                    '144' => 'Private Halls',
                    '253' => 'Commercial Property',
                )
            ),
            'contact_information_fields' => array(
                'BRANCH_ID',
            ),
            'help_url' => 'https://houzezpropertyfeed.com/documentation/managing-imports/formats/10ninety/',
            'warnings' => array_filter( array( $simplexml_warning ) ),
        ),
        'acquaint' => array(
            'name' => __( 'Acquaint', 'houzezpropertyfeed' ),
            'fields' => array(
                array(
                    'id' => 'xml_url',
                    'label' => __( 'XML URL', 'houzezpropertyfeed' ),
                    'type' => 'text',
                    'placeholder' => 'https://',
                )
            ),
            'address_fields' => array( 'locality', 'town', 'region', 'area' ),
            'taxonomy_values' => array(
                'sales_status' => array(
                    'Available' => 'Available',
                    'Sold STC' => 'Sold STC',
                    'Under Offer' => 'Under Offer',
                    'Sold' => 'Sold',
                ),
                'lettings_status' => array(
                    'Available' => 'Available',
                    'Under Offer' => 'Under Offer',
                    'Let' => 'Let',
                ),
                'property_type' => array(
                    'House' => 'House',
                    'Detached' => 'Detached',
                    'Semi-Detached' => 'Semi-Detached',
                    'Terrace' => 'Terrace',
                    'End Terrace' => 'End Terrace',
                    'Flat' => 'Flat',
                    'Apartment' => 'Apartment',
                    'Studio' => 'Studio',
                    'Maisonette' => 'Maisonette',
                    'Bungalow' => 'Bungalow',
                    'Garage' => 'Garage',
                )
            ),
            'contact_information_fields' => array(
                'username',
            ),
            'help_url' => 'https://houzezpropertyfeed.com/documentation/managing-imports/formats/acquaint/',
            'warnings' => array_filter( array( $simplexml_warning ) ),
        ),
        'agentos' => array(
            'name' => __( 'agentOS', 'houzezpropertyfeed' ),
            'fields' => array(
                array(
                    'id' => 'api_key',
                    'label' => __( 'API Key', 'houzezpropertyfeed' ),
                    'type' => 'text',
                ),
                array(
                    'id' => 'short_name',
                    'label' => __( 'Short Name', 'houzezpropertyfeed' ),
                    'type' => 'text',
                ),
            ),
            'address_fields' => array( 'Address2', 'Address3', 'Address4' ),
            'taxonomy_values' => array(
                'sales_status' => array(
                    'For Sale' => 'For Sale',
                    'Under Offer' => 'Under Offer',
                ),
                'lettings_status' => array(
                    'To Let' => 'To Let',
                    'Let Agreed' => 'Let Agreed',
                ),
                'property_type' => array(
                    'House' => 'House',
                    'DetachedHouse' => 'DetachedHouse',
                    'SemiDetachedHouse' => 'SemiDetachedHouse',
                    'TerracedHouse' => 'TerracedHouse',
                    'EndTerraceHouse' => 'EndTerraceHouse',
                    'Cottage' => 'Cottage',
                    'Bungalow' => 'Bungalow',
                    'FlatApartment' => 'FlatApartment',
                    'HouseFlatShare' => 'HouseFlatShare',
                )
            ),
            'contact_information_fields' => array(
                'BranchOID',
            ),
            'help_url' => 'https://houzezpropertyfeed.com/documentation/managing-imports/formats/agentos/',
            'warnings' => array( __( 'AgentOS are very strict on the number of requests made per minute. As it takes so many individual requests to obtain the data we require, we\'ve had to add pauses to prevent you hitting this throttling limit. As a result, imports from AgentOS may take a while and therefore you\'ll likely need to increase the timeout limit on your server.', 'houzezpropertyfeed' ) ),
        ),
        'alto' => array(
            'name' => __( 'Alto by Vebra', 'houzezpropertyfeed' ),
            'fields' => array(
                array(
                    'id' => 'datafeed_id',
                    'label' => __( 'Datafeed ID', 'houzezpropertyfeed' ),
                    'type' => 'text',
                ),
                array(
                    'id' => 'username',
                    'label' => __( 'Username', 'houzezpropertyfeed' ),
                    'type' => 'text',
                ),
                array(
                    'id' => 'password',
                    'label' => __( 'Password', 'houzezpropertyfeed' ),
                    'type' => 'text',
                ),
            ),
            'address_fields' => array( 'locality', 'town', 'county' ),
            'taxonomy_values' => array(
                'sales_status' => array(
                     '0' => 'For Sale',
                    '1' => 'Under Offer',
                    '2' => 'Sold',
                    '3' => 'SSTC',
                    '4' => 'For Sale By Auction',
                    '5' => 'Reserved',
                    '6' => 'New Instruction',
                    '7' => 'Just on Market',
                    '8' => 'Price Reduction',
                    '9' => 'Keen to Sell',
                    '10' => 'No Chain',
                    '11' => 'Vendor will pay stamp duty',
                    '12' => 'Offers in the region of',
                    '13' => 'Guide Price',
                    '200' => 'For Sale',
                    '201' => 'Under Offer',
                    '202' => 'Sold',
                    '203' => 'SSTC',
                ),
                'lettings_status' => array(
                    '0' => 'To Let',
                    '1' => 'Let',
                    '2' => 'Under Offer',
                    '3' => 'Reserved',
                    '4' => 'Let Agreed',
                    '100' => 'To Let',
                    '101' => 'Let',
                    '102' => 'Under Offer',
                    '103' => 'Reserved',
                    '104' => 'Let Agreed',
                    '200' => 'To Let',
                    '214' => 'Let',
                ),
                'property_type' => array(
                    'House' => 'House',
                    'Flat' => 'Flat',
                )
            ),
            'contact_information_fields' => array(
                'firmid',
                'branchid',
            ),
            'help_url' => 'https://houzezpropertyfeed.com/documentation/managing-imports/formats/alto/',
            'warnings' => array_filter( array( $curl_warning, $simplexml_warning ) ),
        ),
        'apex27' => array(
            'name' => __( 'Apex27', 'houzezpropertyfeed' ),
            'fields' => array(
                array(
                    'id' => 'xml_url',
                    'label' => __( 'XML URL', 'houzezpropertyfeed' ),
                    'type' => 'text',
                    'placeholder' => 'https://',
                )
            ),
            'address_fields' => array( 'Address2', 'Address3', 'Address4', 'City', 'County' ),
            'taxonomy_values' => array(
                'sales_status' => array(
                    'Available' => 'Available',
                    'Under Offer' => 'Under Offer',
                    'SSTC' => 'SSTC',
                ),
                'lettings_status' => array(
                    'Available' => 'Available',
                    'Let Agreed' => 'Let Agreed',
                ),
                'property_type' => array(
                    'Detached House' => 'Detached House',
                    'Semi-detached House' => 'Semi-detached House',
                    'Detached Bungalow' => 'Detached Bungalow',
                    'Semi-detached Bungalow' => 'Semi-detached Bungalow',
                    'Apartment / Flat' => 'Apartment / Flat',
                )
            ),
            'contact_information_fields' => array(
                'Branch Name',
            ),
            'help_url' => 'https://houzezpropertyfeed.com/documentation/managing-imports/formats/apex27/',
            'warnings' => array_filter( array( $simplexml_warning ) ),
        ),
        'bdp' => array(
            'name' => __( 'BDP', 'houzezpropertyfeed' ),
            'fields' => array(
                array(
                    'id' => 'api_key',
                    'label' => __( 'API Key', 'houzezpropertyfeed' ),
                    'type' => 'text',
                ),
                array(
                    'id' => 'secret',
                    'label' => __( 'Secret', 'houzezpropertyfeed' ),
                    'type' => 'text',
                ),
                array(
                    'id' => 'account_id',
                    'label' => __( 'Account ID', 'houzezpropertyfeed' ),
                    'type' => 'text',
                ),
                array(
                    'id' => 'base_url',
                    'label' => __( 'API Base URL', 'houzezpropertyfeed' ),
                    'type' => 'text',
                    'default' => 'https://api.bdphq.com',
                )
            ),
            'address_fields' => array( 'addrL1', 'addrL2', 'addrL3', 'town' ),
            'taxonomy_values' => array(
                'sales_status' => array(
                    'Available' => 'Available',
                    'Under Offer' => 'Under Offer',
                ),
                'lettings_status' => array(
                    'Available' => 'Available',
                ),
                'property_type' => array(
                    'Bungalow' => 'Bungalow',
                    'Detached Bungalow' => 'Detached Bungalow',
                    'House' => 'House',
                    'Detached' => 'Detached',
                    'Semi-Detached' => 'Semi-Detached',
                    'Terraced' => 'Terraced',
                    'Townhouse' => 'Townhouse',
                    'Flat / Apartment' => 'Flat / Apartment',
                )
            ),
            'contact_information_fields' => array(
                'firmName',
                'branch_id',
                'branchName',
            ),
            'help_url' => 'https://houzezpropertyfeed.com/documentation/managing-imports/formats/bdp/',
            'warnings' => array_filter( array( $curl_warning ) ),
        ),
        'blm_local' => array(
            'name' => __( 'BLM - Local Directory', 'houzezpropertyfeed' ),
            'fields' => array(
                array(
                    'id' => 'local_directory',
                    'label' => __( 'Local Directory', 'houzezpropertyfeed' ),
                    'type' => 'text',
                    'default' => $uploads_dir,
                    'tooltip' => __( 'The full server path to where the BLM files will be received into', 'houzezpropertyfeed' ),
                ),
            ),
            'address_fields' => array( 'ADDRESS_2', 'ADDRESS_3', 'ADDRESS_4', 'TOWN', 'COUNTY' ),
            'taxonomy_values' => array(
                'sales_status' => array(
                    '0' => 'Available',
                    '1' => 'SSTC',
                    '2' => 'SSTCM (Scotland only)',
                    '3' => 'Under Offer',
                    '6' => 'Sold',
                ),
                'lettings_status' => array(
                    '0' => 'Available',
                    '4' => 'Reserved',
                    '5' => 'Let Agreed',
                    '7' => 'Let',
                ),
                'property_type' => array(
                    '0' => 'Not Specified',
                    '1' => 'Terraced',
                    '2' => 'End of Terrace',
                    '3' => 'Semi-Detached ',
                    '4' => 'Detached',
                    '5' => 'Mews',
                    '6' => 'Cluster House',
                    '7' => 'Ground Flat',
                    '8' => 'Flat',
                    '9' => 'Studio',
                    '10' => 'Ground Maisonette',
                    '11' => 'Maisonette',
                    '12' => 'Bungalow',
                    '13' => 'Terraced Bungalow',
                    '14' => 'Semi-Detached Bungalow',
                    '15' => 'Detached Bungalow',
                    '16' => 'Mobile Home',
                    '17' => 'Hotel',
                    '18' => 'Guest House',
                    '20' => 'Land',
                    '21' => 'Link Detached House',
                    '22' => 'Town House',
                    '23' => 'Cottage',
                    '24' => 'Chalet',
                    '27' => 'Villa',
                    '28' => 'Apartment',
                    '29' => 'Penthouse',
                    '30' => 'Finca',
                    '43' => 'Barn Conversion',
                    '44' => 'Serviced Apartments',
                    '45' => 'Parking',
                    '46' => 'Sheltered Housing',
                    '47' => 'Retirement Property',
                    '48' => 'House Share',
                    '49' => 'Flat Share',
                    '51' => 'Garages',
                    '52' => 'Farm House',
                    '53' => 'Equestrian',
                    '56' => 'Duplex',
                    '59' => 'Triplex',
                    '62' => 'Longere',
                    '65' => 'Gite',
                    '68' => 'Barn',
                    '71' => 'Trulli',
                    '74' => 'Mill',
                    '77' => 'Ruins',
                    '89' => 'Trulli',
                    '92' => 'Castle',
                    '95' => 'Village House',
                    '101' => 'Cave House',
                    '104' => 'Cortijo',
                    '107' => 'Farm Land',
                    '110' => 'Plot',
                    '113' => 'Country House',
                    '116' => 'Stone House',
                    '117' => 'Caravan',
                    '118' => 'Lodge',
                    '119' => 'Log Cabin',
                    '120' => 'Manor House',
                    '121' => 'Stately Home',
                    '125' => 'Off-Plan',
                    '128' => 'Semi-detached Villa',
                    '131' => 'Detached Villa',
                    '140' => 'Riad',
                    '141' => 'House Boat',
                    '142' => 'Hotel Room',
                    '143' => 'Block of Apartments',
                    '144' => 'Private Halls',
                    '253' => 'Commercial Property',
                )
            ),
            'contact_information_fields' => array(
                'BRANCH_ID',
            ),
            'help_url' => 'https://houzezpropertyfeed.com/documentation/managing-imports/formats/blm/',
        ),
        'csv' => array(
            'name' => __( 'CSV', 'houzezpropertyfeed' ),
            'fields' => array(
                array(
                    'id' => 'csv_url',
                    'label' => __( 'CSV URL', 'houzezpropertyfeed' ),
                    'type' => 'text',
                    'placeholder' => 'https://',
                ),
                array(
                    'id' => 'csv_delimiter',
                    'label' => __( 'CSV Delimiter Character', 'houzezpropertyfeed' ),
                    'type' => 'text',
                    'default' => ',',
                    'css' => 'max-width:50px;'
                ),
                array(
                    'type' => 'html',
                    'label' => '',
                    'html' => '<a href="" class="button hpf-fetch-csv-fields">' . __( 'Fetch CSV', 'houzezpropertyfeed' ) . '</a>'
                ),
                array(
                    'id' => 'property_id_field',
                    'label' => __( 'Unique Property ID Field', 'houzezpropertyfeed' ),
                    'type' => 'select',
                    'tooltip' => __( 'Please select which field in the CSV determines the property\'s unique ID. We\'ll use this to determine if a property has been inserted previously or not. If no options show, click the \'Fetch CSV\' button above', 'houzezpropertyfeed' ),
                ),
                array(
                    'id' => 'property_field_options',
                    'type' => 'hidden',
                ),
            ),
            'address_fields' => array(),
            'taxonomy_values' => array(),
            'contact_information_fields' => array(),
            'help_url' => 'https://houzezpropertyfeed.com/documentation/managing-imports/formats/csv/'
        ),
        'dezrez_rezi' => array(
            'name' => __( 'Dezrez Rezi', 'houzezpropertyfeed' ),
            'fields' => array(
                array(
                    'id' => 'api_key',
                    'label' => __( 'API Key', 'houzezpropertyfeed' ),
                    'type' => 'text',
                ),
                array(
                    'id' => 'branch_ids',
                    'label' => __( 'Branch ID(s)', 'houzezpropertyfeed' ),
                    'type' => 'text',
                    'tooltip' => __( 'A comma-delimited list of Dezrez branch IDs. Leave blank to import properties for all branches', 'houzezpropertyfeed' ),
                ),
                array(
                    'id' => 'tags',
                    'label' => __( 'Tag(s)', 'houzezpropertyfeed' ),
                    'type' => 'text',
                    'tooltip' => __( 'A comma-delimited list of agent defined tags within Dezrez. Leave blank if not wanting to filter properties by tag', 'houzezpropertyfeed' ),
                )
            ),
            'address_fields' => array( 'Locality', 'Town', 'County' ),
            'taxonomy_values' => array(
                'sales_status' => array(
                    'Reduced' => 'Reduced',
                    'OnMarket' => 'OnMarket',
                    'UnderOffer' => 'UnderOffer',
                    'OfferAccepted' => 'OfferAccepted',
                ),
                'lettings_status' => array(
                    'Reduced' => 'Reduced',
                    'OnMarket' => 'OnMarket',
                    'UnderOffer' => 'UnderOffer',
                    'OfferAccepted' => 'OfferAccepted',
                ),
                'property_type' => array(
                    'TerracedHouse' => 'TerracedHouse',
                    'EndTerraceHouse' => 'EndTerraceHouse',
                    'MidTerraceHouse' => 'MidTerraceHouse',
                    'SemiDetachedHouse' => 'SemiDetachedHouse',
                    'DetachedHouse' => 'DetachedHouse',
                    'RemoteDetachedHouse' => 'RemoteDetachedHouse',
                    'EndLinkHouse' => 'EndLinkHouse',
                    'MidLinkHouse' => 'MidLinkHouse',
                    'Flat' => 'Flat',
                    'Apartment' => 'Apartment',
                    'TerracedBungalow' => 'TerracedBungalow',
                    'EndTerraceBungalow' => 'EndTerraceBungalow',
                    'MidTerraceBungalow' => 'MidTerraceBungalow',
                    'SemiDetachedBungalow' => 'SemiDetachedBungalow',
                    'DetachedBungalow' => 'DetachedBungalow',
                    'RemoteDetachedBungalow' => 'RemoteDetachedBungalow',
                    'EndLinkBungalow' => 'EndLinkBungalow',
                    'MidLinkBungalow' => 'MidLinkBungalow',
                    'Cottage' => 'Cottage',
                    'TerracedCottage' => 'TerracedCottage',
                    'EndTerraceCottage' => 'EndTerraceCottage',
                    'MidTerraceCottage' => 'MidTerraceCottage',
                    'SemiDetachedCottage' => 'SemiDetachedCottage',
                    'DetachedCottage' => 'DetachedCottage',
                    'RemoteDetachedCottage' => 'RemoteDetachedCottage',
                    'TerracedTownHouse' => 'TerracedTownHouse',
                    'EndTerraceTownHouse' => 'EndTerraceTownHouse',
                    'MidTerraceTownHouse' => 'MidTerraceTownHouse',
                    'SemiDetachedTownHouse' => 'SemiDetachedTownHouse',
                    'DetachedTownHouse' => 'DetachedTownHouse',
                    'DetachedCountryHouse' => 'DetachedCountryHouse',
                    'NorthWingCountryHouse' => 'NorthWingCountryHouse',
                    'SouthWingCountryHouse' => 'SouthWingCountryHouse',
                    'EastWingCountryHouse' => 'EastWingCountryHouse',
                    'WestWingCountryHouse' => 'WestWingCountryHouse',
                    'TerracedChalet' => 'TerracedChalet',
                    'EndTerraceChalet' => 'EndTerraceChalet',
                    'MidTerraceChalet' => 'MidTerraceChalet',
                    'SemiDetachedChalet' => 'SemiDetachedChalet',
                    'DetachedChalet' => 'DetachedChalet',
                    'DetachedBarnConversion' => 'DetachedBarnConversion',
                    'RemoteDetachedBarnConversion' => 'RemoteDetachedBarnConversion',
                    'MewsStyleBarnConversion' => 'MewsStyleBarnConversion',
                    'GroundFloorPurposeBuiltFlat' => 'GroundFloorPurposeBuiltFlat',
                    'FirstFloorPurposeBuiltFlat' => 'FirstFloorPurposeBuiltFlat',
                    'GroundFloorConvertedFlat' => 'GroundFloorConvertedFlat',
                    'FirstFloorConvertedFlat' => 'FirstFloorConvertedFlat',
                    'SecondAndFloorConvertedFlat' => 'SecondAndFloorConvertedFlat',
                    'GroundAndFirstFloorMaisonette' => 'GroundAndFirstFloorMaisonette',
                    'FirstandSecondFloorMaisonette' => 'FirstandSecondFloorMaisonette',
                    'PenthouseApartment' => 'PenthouseApartment',
                    'DuplexApartment' => 'DuplexApartment',
                    'Mansion' => 'Mansion',
                    'QType' => 'QType',
                    'TType' => 'TType',
                    'Cluster' => 'Cluster',
                    'BuildingPlot' => 'BuildingPlot',
                    'ApartmentLowDensity' => 'ApartmentLowDensity',
                    'ApartmentStudio' => 'ApartmentStudio',
                    'Business' => 'Business',
                    'CornerTownhouse' => 'CornerTownhouse',
                    'VillaDetached' => 'VillaDetached',
                    'VillaLinkdetached' => 'VillaLinkdetached',
                    'VillaSemidetached' => 'VillaSemidetached',
                    'VillageHouse' => 'VillageHouse',
                    'LinkDetached' => 'LinkDetached',
                    'Studio' => 'Studio',
                    'Maisonette' => 'Maisonette',
                    'Shell' => 'Shell',
                    'Commercial' => 'Commercial',
                    'RetirementFlat' => 'RetirementFlat',
                    'Bedsit' => 'Bedsit',
                    'ParkHome' => 'ParkHome',
                    'ParkHomeMobileHome' => 'ParkHomeMobileHome',
                    'CommercialLand' => 'CommercialLand',
                    'Land' => 'Land',
                    'FarmLand' => 'FarmLand',
                )
            ),
            'contact_information_fields' => array(
                'Branch ID',
                'Branch Name',
            ),
            'help_url' => 'https://houzezpropertyfeed.com/documentation/managing-imports/formats/dezrez-rezi/'
        ),
        'domus' => array(
            'name' => __( 'Domus', 'houzezpropertyfeed' ),
            'fields' => array(
                array(
                    'id' => 'xml_url',
                    'label' => __( 'XML URL', 'houzezpropertyfeed' ),
                    'type' => 'text',
                    'placeholder' => 'https://{your-site}.domus.net/site/go/api/',
                )
            ),
            'address_fields' => array( 'locality', 'town', 'county' ),
            'taxonomy_values' => array(
                'sales_status' => array(
                    'Available' => 'Available',
                    'Under Offer' => 'Under Offer',
                    'Sold Subject to Contract' => 'Sold Subject to Contract',
                ),
                'lettings_status' => array(
                    'Available' => 'Available',
                    'Let Subject to Contract' => 'Let Subject to Contract',
                ),
                'property_type' => array(
                    'Detached' => 'Detached',
                    'Semi-Detached' => 'Semi-Detached',
                    'End Terraced' => 'End Terraced',
                    'Flat' => 'Flat',
                    'Studio' => 'Studio',
                )
            ),
            'contact_information_fields' => array(
                'branchID',
            ),
            'help_url' => 'https://houzezpropertyfeed.com/documentation/managing-imports/formats/domus/',
            'warnings' => array_filter( array( $simplexml_warning ) ),
        ),
        'expertagent' => array(
            'name' => __( 'Expert Agent', 'houzezpropertyfeed' ),
            'fields' => array(
                array(
                    'id' => 'ftp_host',
                    'label' => __( 'FTP Host', 'houzezpropertyfeed' ),
                    'type' => 'text',
                    'default' => 'ftp.expertagent.co.uk',
                ),
                array(
                    'id' => 'ftp_user',
                    'label' => __( 'FTP Username', 'houzezpropertyfeed' ),
                    'type' => 'text',
                    'placeholder' => '',
                ),
                array(
                    'id' => 'ftp_pass',
                    'label' => __( 'FTP Password', 'houzezpropertyfeed' ),
                    'type' => 'text',
                    'placeholder' => '',
                ),
                array(
                    'id' => 'ftp_passive',
                    'label' => __( 'Use FTP Passive Mode', 'houzezpropertyfeed' ),
                    'type' => 'checkbox',
                ),
                array(
                    'id' => 'xml_filename',
                    'label' => __( 'XML File Name', 'houzezpropertyfeed' ),
                    'type' => 'text',
                    'default' => 'properties.xml',
                ),
            ),
            'address_fields' => array( 'district', 'town', 'county' ),
            'taxonomy_values' => array(
                'sales_status' => array(
                    'On Market' => 'On Market',
                    'Sold STC' => 'Sold STC',
                    'Under Offer' => 'Under Offer',
                    'Exchanged' => 'Exchanged',
                ),
                'lettings_status' => array(
                    'On Market' => 'On Market',
                    'Available to Let' => 'Available to Let',
                    'Let' => 'Let',
                    'Let STC' => 'Let STC',
                ),
                'property_type' => array(
                    'House - Detached' => 'House - Detached',
                    'House - Semi Detached' => 'House - Semi Detached',
                    'House - Terraced' => 'House - Terraced',
                    'House - End of Terrace' => 'House - End of Terrace',
                    'Flat - Lower Ground Floor Flat' => 'Flat - Lower Ground Floor Flat',
                    'Flat - Ground Floor Flat' => 'Flat - Ground Floor Flat',
                    'Flat - Upper Floor Flat' => 'Flat - Upper Floor Flat',
                    'Bungalow - Detached' => 'Bungalow - Detached',
                    'Bungalow - Semi Detached' => 'Bungalow - Semi Detached',
                    'Bungalow - Terraced' => 'Bungalow - Terraced',
                    'Bungalow - End of Terrace' => 'Bungalow - End of Terrace',
                )
            ),
            'contact_information_fields' => array(
                'branch',
            ),
            'help_url' => 'https://houzezpropertyfeed.com/documentation/managing-imports/formats/expert-agent/',
            'warnings' => array_filter( array( $simplexml_warning ) ),
        ),
        'gnomen' => array(
            'name' => __( 'Gnomen', 'houzezpropertyfeed' ),
            'fields' => array(
                array(
                    'id' => 'xml_url',
                    'label' => __( 'XML URL', 'houzezpropertyfeed' ),
                    'type' => 'text',
                    'placeholder' => 'https://',
                )
            ),
            'address_fields' => array( 'address2', 'area', 'property_area' ),
            'taxonomy_values' => array(
                'sales_status' => array(
                    'For Sale' => 'For Sale',
                    'Under Offer' => 'Under Offer',
                    'Sold Subject to Contract' => 'Sold Subject to Contract',
                ),
                'lettings_status' => array(
                    'To Let' => 'To Let',
                    'Under Offer' => 'Under Offer',
                    'Let Agreed' => 'Let Agreed',
                ),
                'property_type' => array(
                    'Detached House' => 'Detached House',
                    'Semi-Detached House' => 'Semi-Detached House',
                    'Terraced House' => 'Terraced House',
                    'End of Terrace House' => 'End of Terrace House',
                    'Town House' => 'Town House',
                    'Apartment' => 'Apartment',
                    'Flat' => 'Flat',
                    'Maisonette' => 'Maisonette'
                )
            ),
            'contact_information_fields' => array(
                'branch_name',
                'negotiator_id',
                'negotiator',
            ),
            'help_url' => 'https://houzezpropertyfeed.com/documentation/managing-imports/formats/gnomen/',
            'warnings' => array_filter( array( $simplexml_warning ) ),
        ),
        'inmobalia' => array(
            'name' => __( 'Inmobalia API', 'houzezpropertyfeed' ),
            'fields' => array(
                array(
                    'id' => 'api_key',
                    'label' => __( 'API Key', 'houzezpropertyfeed' ),
                    'type' => 'text',
                ),
            ),
            'address_fields' => array( 'locationProvince', 'locationCity', 'locationArea', 'locationSubarea' ),
            'taxonomy_values' => array(
                'sales_status' => array(
                    'Available' => 'Available',
                    'UnderOffer' => 'UnderOffer',
                    'Sold' => 'Sold',
                ),
                'lettings_status' => array(
                    'Available' => 'Available',
                    'Rented' => 'Rented',
                ),
                'property_type' => array(
                    'Studio' => 'Studio',
                    'Apartment' => 'Apartment',
                    'Penthouse' => 'Penthouse',
                )
            ),
            'contact_information_fields' => array(
                'agency',
            ),
            'help_url' => 'https://houzezpropertyfeed.com/documentation/managing-imports/formats/inmobalia/'
        ),
        'jupix' => array(
            'name' => __( 'Jupix', 'houzezpropertyfeed' ),
            'fields' => array(
                array(
                    'id' => 'xml_url',
                    'label' => __( 'XML URL', 'houzezpropertyfeed' ),
                    'type' => 'text',
                    'placeholder' => 'https://',
                )
            ),
            'address_fields' => array( 'address2', 'address3', 'address4' ),
            'taxonomy_values' => array(
                'sales_status' => array(
                    '1' => 'On Hold',
                    '2' => 'For Sale',
                    '3' => 'Under Offer',
                    '4' => 'Sold STC',
                    '5' => 'Sold',
                ),
                'lettings_status' => array(
                    '1' => 'On Hold',
                    '2' => 'To Let',
                    '3' => 'References Pending',
                    '4' => 'Let Agreed',
                    '5' => 'Let',
                ),
                'property_type' => array(
                    '1 - 1' => 'House - Barn Conversion',
                    '1 - 2' => 'House - Cottage',
                    '1 - 3' => 'House - Chalet',
                    '1 - 4' => 'House - Detached House',
                    '1 - 5' => 'House - Semi-Detached House',
                    '1 - 28' => 'House - Link Detached',
                    '1 - 6' => 'House - Farm House',
                    '1 - 7' => 'House - Manor House',
                    '1 - 8' => 'House - Mews',
                    '1 - 9' => 'House - Mid Terraced House',
                    '1 - 10' => 'House - End Terraced House',
                    '1 - 11' => 'House - Town House',
                    '1 - 12' => 'House - Villa',
                    '1 - 29' => 'House - Shared House',
                    '1 - 31' => 'House - Sheltered Housing',
                    '2 - 13' => 'Flat - Apartment',
                    '2 - 14' => 'Flat - Bedsit',
                    '2 - 15' => 'Flat - Ground Floor Flat',
                    '2 - 16' => 'Flat - Flat',
                    '2 - 17' => 'Flat - Ground Floor Maisonette',
                    '2 - 18' => 'Flat - Maisonette',
                    '2 - 19' => 'Flat - Penthouse',
                    '2 - 20' => 'Flat - Studio',
                    '2 - 30' => 'Flat - Shared Flat',
                    '3 - 21' => 'Bungalow - Detached Bungalow',
                    '3 - 22' => 'Bungalow - Semi-Detached Bungalow',
                    '3 - 34' => 'Bungalow - Mid Terraced Bungalow',
                    '3 - 35' => 'Bungalow - End Terraced Bungalow',
                    '4 - 23' => 'Other - Building Plot / Land',
                    '4 - 24' => 'Other - Garage',
                    '4 - 25' => 'Other - House Boat',
                    '4 - 26' => 'Other - Mobile Home',
                    '4 - 27' => 'Other - Parking',
                    '4 - 32' => 'Other - Equestrian',
                    '4 - 33' => 'Other - Unconverted Barn',
                )
            ),
            'contact_information_fields' => array(
                'branchID',
                'branchName',
            ),
            'help_url' => 'https://houzezpropertyfeed.com/documentation/managing-imports/formats/jupix/',
            'warnings' => array_filter( array( $simplexml_warning ) ),
        ),
        'kyero' => array(
            'name' => __( 'Kyero', 'houzezpropertyfeed' ),
            'fields' => array(
                array(
                    'id' => 'xml_url',
                    'label' => __( 'XML URL', 'houzezpropertyfeed' ),
                    'type' => 'text',
                    'placeholder' => 'https://',
                )
            ),
            'address_fields' => array( 'town', 'province', 'location_detail' ),
            'taxonomy_values' => array(
                'sales_status' => array(
                    'Sales' => 'Sales',
                ),
                'lettings_status' => array(
                    'Lettings' => 'Lettings',
                ),
                'property_type' => array(
                    'Apartment' => 'Apartment',
                    'Finca' => 'Finca',
                    'Penthouse' => 'Penthouse',
                    'Plot' => 'Plot',
                    'Townhouse' => 'Townhouse',
                    'Villa' => 'Villa',
                )
            ),
            'contact_information_fields' => array(
                'Agent ID',
                'Agent Name',
            ),
            'help_url' => 'https://houzezpropertyfeed.com/documentation/managing-imports/formats/kyero/',
            'warnings' => array_filter( array( $simplexml_warning ) ),
        ),
        'loop' => array(
            'name' => __( 'Loop', 'houzezpropertyfeed' ),
            'fields' => array(
                array(
                    'id' => 'api_key',
                    'label' => __( 'API Key', 'houzezpropertyfeed' ),
                    'type' => 'text',
                )
            ),
            'address_fields' => array( 'Locality', 'Town', 'County' ),
            'taxonomy_values' => array(
                'sales_status' => array(
                    'forSale' => 'forSale',
                    'underOffer' => 'underOffer',
                    'soldSTC' => 'soldSTC',
                    'exchanged' => 'exchanged',
                    'completed' => 'completed',
                ),
                'lettings_status' => array(
                    'toLet' => 'toLet',
                    'let' => 'let',
                ),
                'property_type' => array(
                    'terraced' => 'terraced',
                    'endOfTerrace' => 'endOfTerrace',
                    'semiDetached' => 'semiDetached',
                    'detached' => 'detached',
                    'linkDetachedHouse' => 'linkDetachedHouse',
                    'mewsHouse' => 'mewsHouse',
                    'townHouse' => 'townHouse',
                    'countryHouse' => 'countryHouse',
                    'clusterHouse' => 'clusterHouse',
                    'flat' => 'flat',
                    'apartment' => 'apartment',
                    'penthouse' => 'penthouse',
                    'groundFloorFlat' => 'groundFloorFlat',
                    'maisonette' => 'maisonette',
                    'blockOfFlats' => 'blockOfFlats',
                    'studio' => 'studio',
                    'bungalow' => 'bungalow',
                    'terracedBungalow' => 'terracedBungalow',
                    'semiDetachedBungalow' => 'semiDetachedBungalow',
                    'cottage' => 'cottage',
                    'farmOrBarn' => 'farmOrBarn',
                    'mobileOrStatic' => 'mobileOrStatic',
                    'land' => 'land',
                )
            ),
            'contact_information_fields' => array(
                'api_key',
                'creatingAgentId',
            ),
            'help_url' => 'https://houzezpropertyfeed.com/documentation/managing-imports/formats/loop/'
        ),
        'mri' => array(
            'name' => __( 'MRI XML', 'houzezpropertyfeed' ),
            'fields' => array(
                array(
                    'id' => 'xml_url',
                    'label' => __( 'XML URL', 'houzezpropertyfeed' ),
                    'type' => 'text',
                    'placeholder' => 'https://v4.salesandlettings.online/pls/{client}/aspasia_search.xml',
                ),
                array(
                    'id' => 'password',
                    'label' => __( 'Password', 'houzezpropertyfeed' ),
                    'type' => 'text',
                )
            ),
            'address_fields' => array( 'address3', 'town', 'county' ),
            'taxonomy_values' => array(
                'sales_status' => array(
                    'AVAI' => 'For Sale',
                    'REACTIVATE' => 'Reactivated',
                    'UO' => 'Under Offer',
                    'SSTC' => 'Sold STC',
                ),
                'lettings_status' => array(
                    'AVAI' => 'Available to let',
                    'AV_LET' => 'Available to let',
                    'ARGAV' => 'Available arranging tenancy',
                    'LETSTC' => 'Let - Subject to references',
                    'LET' => 'Let',
                    'QUBEUNAVIL' => 'Unavailable',
                ),
                'property_type' => array(
                    'HOUSE-DETATCH' => 'House - Detached',
                    'HOUSE-SEMID' => 'House - Semi Detached',
                    'HOUSE-TERRACED' => 'House - Terraced',
                    'FLATT-GRNDFLR' => 'Flat - Ground Floor',
                    'FLATT-1STFLR' => 'Flat - First Floor',
                )
            ),
            'contact_information_fields' => array(
                'branch',
            ),
            'help_url' => 'https://houzezpropertyfeed.com/documentation/managing-imports/formats/mri/',
            'warnings' => array_filter( array( $simplexml_warning ) ),
        ),
        'pixxi' => array(
            'name' => __( 'Pixxi', 'houzezpropertyfeed' ),
            'fields' => array(
                array(
                    'id' => 'url',
                    'label' => __( 'URL', 'houzezpropertyfeed' ),
                    'type' => 'text',
                    'placeholder' => 'https://www.pixxicrm.ae/api/sync/pixxi/<COMPANY_ENPOINT>',
                )
            ),
            'address_fields' => array( 'region' ),
            'taxonomy_values' => array(
                'sales_status' => array(
                    'For Sale' => 'For Sale',
                ),
                'lettings_status' => array(
                    'To Let' => 'To Let',
                ),
                'property_type' => array(
                    'APARTMENT' => 'APARTMENT',
                    'VILLA' => 'VILLA',
                    'TOWNHOUSE' => 'TOWNHOUSE',
                    'PENTHOUSE' => 'PENTHOUSE',
                    'HOTEL_APARTMENT' => 'HOTEL_APARTMENT',
                    'DUPLEX' => 'DUPLEX',
                    'RESIDENTIAL_FLOOR' => 'RESIDENTIAL_FLOOR',
                    'RESIDENTIAL_PLOT' => 'RESIDENTIAL_PLOT',
                    'RESIDENTIAL_BUILDING' => 'RESIDENTIAL_BUILDING',
                    'COMPOUND' => 'COMPOUND',
                    'OFFICE' => 'OFFICE',
                    'SHOP' => 'SHOP',
                    'COMMERCIAL_BUILDING' => 'COMMERCIAL_BUILDING',
                    'COMMERCIAL_FLOOR' => 'COMMERCIAL_FLOOR',
                    'COMMERCIAL_PLOT' => 'COMMERCIAL_PLOT',
                    'LABOR_CAMP' => 'LABOR_CAMP',
                    'RETAIL' => 'RETAIL',
                    'SHOW_ROOM' => 'SHOW_ROOM',
                    'COMMERCIAL_VILLA' => 'COMMERCIAL_VILLA',
                    'WAREHOUSE' => 'WAREHOUSE',
                    'FARM' => 'FARM',
                    'FACTORY' => 'FACTORY',
                    'HOTEL' => 'HOTEL',
                )
            ),
            'contact_information_fields' => array(
                'agent_name',
            ),
            'help_url' => 'https://houzezpropertyfeed.com/documentation/managing-imports/formats/pixxi/',
            'warnings' => array(),
        ),
        'property_finder' => array(
            'name' => __( 'Property Finder / myCRM', 'houzezpropertyfeed' ),
            'fields' => array(
                array(
                    'id' => 'xml_url',
                    'label' => __( 'XML URL', 'houzezpropertyfeed' ),
                    'type' => 'text',
                    'placeholder' => 'https://',
                )
            ),
            'address_fields' => array( 'city', 'community', 'sub_community' ),
            'taxonomy_values' => array(
                'sales_status' => array(
                    'For Sale' => 'For Sale',
                ),
                'lettings_status' => array(
                    'To Let' => 'To Let',
                ),
                'property_type' => array(
                    'AP' => 'Apartment/Flat',
                    'BW' => 'Bungalow',
                    'DX' => 'Duplex',
                    'FF' => 'Full Floor',
                    'HF' => 'Half Floor',
                    'LP' => 'Land/Plot',
                    'PH' => 'Penthouse',
                    'TH' => 'Townhouse',
                    'VH' => 'Villa/House',
                    'WB' => 'Whole Building',
                    'BU' => 'Bulk Units',
                    'CD' => 'Compound',
                    'FA' => 'Factory',
                    'LC' => 'Labor Camp',
                    'LP' => 'Land/Plot',
                    'OF' => 'Office Space',
                    'BC' => 'Business Centre',
                    'RE' => 'Retail',
                    'RT' => 'Restaurant',
                    'SA' => 'Staff Accommodation',
                    'SH' => 'Shop',
                    'SR' => 'Showroom',
                    'CW' => 'Co-working Space',
                    'ST' => 'Storage',
                    'WH' => 'Warehouse',
                )
            ),
            'contact_information_fields' => array(
                'agent_name',
            ),
            'help_url' => 'https://houzezpropertyfeed.com/documentation/managing-imports/formats/property-finder/',
            'warnings' => array_filter( array( $simplexml_warning ) ),
        ),
        'remax' => array(
            'name' => __( 'RE/MAX', 'houzezpropertyfeed' ),
            'fields' => array(
                array(
                    'id' => 'api_key',
                    'label' => __( 'API Key', 'houzezpropertyfeed' ),
                    'type' => 'text',
                ),
                array(
                    'id' => 'access_key',
                    'label' => __( 'Access Key', 'houzezpropertyfeed' ),
                    'type' => 'text',
                ),
                array(
                    'id' => 'secret_key',
                    'label' => __( 'Secret Key', 'houzezpropertyfeed' ),
                    'type' => 'text',
                ),
                array(
                    'id' => 'office_id',
                    'label' => __( 'Office ID(s)', 'houzezpropertyfeed' ),
                    'type' => 'text',
                    'tooltip' => __( 'Enter a comma-delimited list of office IDs if only wanting to import specific office listings. Enter only Agent ID(s) or Office ID(s). Not both.', 'houzezpropertyfeed' ),
                ),
                array(
                    'id' => 'agent_id',
                    'label' => __( 'Agent ID(s)', 'houzezpropertyfeed' ),
                    'type' => 'text',
                    'tooltip' => __( 'Enter a comma-delimited list of agent IDs if only wanting to import specific agents listings. Enter only Agent ID(s) or Office ID(s). Not both.', 'houzezpropertyfeed' ),
                ),
            ),
            'address_fields' => array( 'suburb', 'city', 'province' ),
            'taxonomy_values' => array(
                'sales_status' => array(
                    'For Sale' => 'For Sale',
                    'New' => 'New',
                    'Price Reduced' => 'Price Reduced',
                    'Offer Made' => 'Offer Made',
                    'Sold' => 'Sold',
                ),
                'lettings_status' => array(
                    'To Rent' => 'To Rent',
                ),
                'property_type' => array(
                    'Apartment / Flat' => 'Apartment / Flat',
                    'House' => 'House',
                    'Townhouse' => 'Townhouse',
                    'Vacant Land / Plot' => 'Vacant Land / Plot',
                    'Farm' => 'Farm',
                    'Commercial Property: Office' => 'Commercial Property: Office',
                    'Commercial Property: Retail' => 'Commercial Property: Retail',
                    'Commercial Property: Accommodation' => 'Commercial Property: Accommodation',
                    'Commercial Property: Flatlet' => 'Commercial Property: Flatlet',
                    'Industrial Property: Factory' => 'Industrial Property: Factory',
                    'Industrial Property: Warehouse' => 'Industrial Property: Warehouse',
                    'Industrial Property: Storage' => 'Industrial Property: Storage',
                )
            ),
            'contact_information_fields' => array(
                'agent_id',
            ),
            'help_url' => 'https://houzezpropertyfeed.com/documentation/managing-imports/formats/remax/'
        ),
        'rentman' => array(
            'name' => __( 'Rentman', 'houzezpropertyfeed' ),
            'fields' => array(
                array(
                    'id' => 'local_directory',
                    'label' => __( 'Local Directory', 'houzezpropertyfeed' ),
                    'type' => 'text',
                    'default' => $uploads_dir,
                    'tooltip' => __( 'The full server path to where the XML files will be received into', 'houzezpropertyfeed' ),
                ),
            ),
            'address_fields' => array( 'Address3', 'Address4' ),
            'taxonomy_values' => array(
                'sales_status' => array(
                    'Available' => 'Available',
                    'Under Offer' => 'Under Offer',
                    'Unavailable' => 'Unavailable',
                    'For Sale' => 'For Sale',
                    'ForSale&ToLet' => 'ForSale&ToLet',
                    'Sold' => 'Sold',
                ),
                'lettings_status' => array(
                    'Available' => 'Available',
                    'Under Offer' => 'Under Offer',
                    'Unavailable' => 'Unavailable',
                    'ForSale&ToLet' => 'ForSale&ToLet',
                ),
                'property_type' => array(
                    'Detached' => 'Detached',
                    'Semi' => 'Semi',
                    'Terrace' => 'Terrace',
                    'Apartment' => 'Apartment',
                    'Flat' => 'Flat',
                    'Studio' => 'Studio',
                    'Cottage' => 'Cottage',
                    'Bungalow' => 'Bungalow',
                )
            ),
            'contact_information_fields' => array(
                'Branch',
            ),
            'help_url' => 'https://houzezpropertyfeed.com/documentation/managing-imports/formats/rentman/',
            'warnings' => array_filter( array( $simplexml_warning ) ),
        ),
        'resales_online' => array(
            'name' => __( 'ReSales Online', 'houzezpropertyfeed' ),
            'fields' => array(
                array(
                    'id' => 'xml_url',
                    'label' => __( 'XML URL', 'houzezpropertyfeed' ),
                    'type' => 'text',
                    'placeholder' => 'https://',
                )
            ),
            'address_fields' => array( 'area', 'town', 'province' ),
            'taxonomy_values' => array(
                'sales_status' => array(
                    'Available' => 'Available',
                    'Sold' => 'Sold',
                ),
                'lettings_status' => array(
                    'Available' => 'Available',
                ),
                'property_type' => array(
                    'Apartment - Ground Floor' => 'Apartment - Ground Floor',
                    'Apartment - Middle Floor' => 'Apartment - Middle Floor',
                    'Apartment - Penthouse' => 'Apartment - Penthouse',
                    'Plot - Land' => 'Plot - Land',
                    'Plot - Residential' => 'Plot - Residential',
                    'Townhouse - Terraced' => 'Townhouse - Terraced',
                    'Villa - Detached' => 'Villa - Detached',
                )
            ),
            'contact_information_fields' => array(),
            'help_url' => 'https://houzezpropertyfeed.com/documentation/managing-imports/formats/resales-online/',
            'warnings' => array_filter( array( $simplexml_warning ) ),
        ),
        'rex' => array(
            'name' => __( 'Rex', 'houzezpropertyfeed' ),
            'fields' => array(
                array(
                    'id' => 'username',
                    'label' => __( 'Username', 'houzezpropertyfeed' ),
                    'type' => 'text',
                ),
                array(
                    'id' => 'password',
                    'label' => __( 'Password', 'houzezpropertyfeed' ),
                    'type' => 'text',
                ),
                array(
                    'id' => 'base_url',
                    'label' => __( 'API Base URL', 'houzezpropertyfeed' ),
                    'type' => 'text',
                    'default' => 'https://api.uk.rexsoftware.com',
                )
            ),
            'address_fields' => array( 'locality', 'suburb_or_town', 'state_or_region' ),
            'taxonomy_values' => array(
                'sales_status' => array(
                    'Available' => 'Available',
                    'Under Offer' => 'Under Offer',
                    'Exchanged' => 'Exchanged',
                    'Completed' => 'Completed',
                ),
                'lettings_status' => array(
                    'Available' => 'Available',
                    'Under Offer' => 'Under Offer',
                ),
                'property_type' => array(
                    'Apartment' => 'Apartment',
                    'Barn Conversion' => 'Barn Conversion',
                    'Block of Flats' => 'Block of Flats',
                    'Bungalow' => 'Bungalow',
                    'Chalet' => 'Chalet',
                    'Coach House' => 'Coach House',
                    'Country House' => 'Country House',
                    'Cottage' => 'Cottage',
                    'Detached bungalow' => 'Detached bungalow',
                    'Detached house' => 'Detached house',
                    'End of terrace house' => 'End of terrace house',
                    'Finca' => 'Finca',
                    'Flat' => 'Flat',
                    'House Boat' => 'House Boat',
                    'Link detached house' => 'Link detached house',
                    'Lodge' => 'Lodge',
                    'Longere' => 'Longere',
                    'Maisonette' => 'Maisonette',
                    'Mews house' => 'Mews house',
                    'Park home' => 'Park home',
                    'Riad' => 'Riad',
                    'Semi-detached bungalow' => 'Semi-detached bungalow',
                    'Semi-detached house' => 'Semi-detached house',
                    'Studio' => 'Studio',
                    'Terraced bungalow' => 'Terraced bungalow',
                    'Terraced House' => 'Terraced House',
                    'Town House' => 'Town House',
                    'Villa' => 'Villa',
                )
            ),
            'contact_information_fields' => array(
                'listing_agent_name'
            ),
            'help_url' => 'https://houzezpropertyfeed.com/documentation/managing-imports/formats/rex/'
        ),
        'street' => array(
            'name' => __( 'Street', 'houzezpropertyfeed' ),
            'fields' => array(
                array(
                    'id' => 'api_key',
                    'label' => __( 'API Key', 'houzezpropertyfeed' ),
                    'type' => 'text',
                ),
                array(
                    'id' => 'base_url',
                    'label' => __( 'API Base URL', 'houzezpropertyfeed' ),
                    'type' => 'text',
                    'default' => 'https://street.co.uk',
                )
            ),
            'address_fields' => array( 'town', 'line_2', 'line_3' ),
            'taxonomy_values' => array(
                'sales_status' => array(
                    'For Sale' => 'For Sale',
                    'Under Offer' => 'Under Offer',
                    'Sold STC' => 'Sold STC',
                    'For Sale and To Let' => 'For Sale and To Let',
                ),
                'lettings_status' => array(
                    'To Let' => 'To Let',
                    'Let Agreed' => 'Let Agreed',
                    'For Sale and To Let' => 'For Sale and To Let',
                ),
                'property_type' => array(
                    'Detached House' => 'Detached House',
                    'Semi-Detached House' => 'Semi-Detached House',
                    'Terraced House' => 'Terraced House',
                    'Bungalow' => 'Bungalow',
                    'Flat / Apartment' => 'Flat / Apartment',
                    'Other' => 'Other',
                )
            ),
            'contact_information_fields' => array(
                'branch_uuid',
            ),
            'export_enquiries' => true,
            'help_url' => 'https://houzezpropertyfeed.com/documentation/managing-imports/formats/street/'
        ),
        'xml' => array(
            'name' => __( 'XML', 'houzezpropertyfeed' ),
            'fields' => array(
                array(
                    'id' => 'xml_url',
                    'label' => __( 'XML URL', 'houzezpropertyfeed' ),
                    'type' => 'text',
                    'placeholder' => 'https://',
                ),
                array(
                    'type' => 'html',
                    'label' => '',
                    'html' => '<a href="" class="button hpf-fetch-xml-nodes">' . __( 'Fetch XML', 'houzezpropertyfeed' ) . '</a>'
                ),
                array(
                    'id' => 'property_node',
                    'label' => __( 'Repeating Property Node', 'houzezpropertyfeed' ),
                    'type' => 'select',
                    'tooltip' => __( 'Please select which node in the XML determines a property record. If no options show, click the \'Fetch XML\' button above', 'houzezpropertyfeed' ),
                ),
                array(
                    'id' => 'property_id_node',
                    'label' => __( 'Unique Property ID Node', 'houzezpropertyfeed' ),
                    'type' => 'select',
                    'tooltip' => __( 'Please select which node in the XML determines the property\'s unique ID. We\'ll use this to determine if a property has been inserted previously or not. If no options show, click the \'Fetch XML\' button above', 'houzezpropertyfeed' ),
                ),
                array(
                    'id' => 'property_node_options',
                    'type' => 'hidden',
                ),
            ),
            'address_fields' => array(),
            'taxonomy_values' => array(),
            'contact_information_fields' => array(),
            'help_url' => 'https://houzezpropertyfeed.com/documentation/managing-imports/formats/xml/'
        ),
    );

    $formats = apply_filters( 'houzez_property_feed_import_formats', $formats );

    return $formats;
}

function get_houzez_property_feed_import_format( $key )
{
    $formats = get_houzez_property_feed_import_formats();
    
    return isset($formats[$key]) ? $formats[$key] : false;
}

function get_format_from_import_id( $import_id )
{
    $formats = get_houzez_property_feed_import_formats();

    $options = get_option( 'houzez_property_feed' , array() );
    $imports = ( isset($options['imports']) && is_array($options['imports']) && !empty($options['imports']) ) ? $options['imports'] : array();

    if ( isset($imports[$import_id]) )
    {
        $format = $imports[$import_id]['format'];

        return get_houzez_property_feed_import_format( $format );
    }
    
    return false;
}

function get_houzez_property_feed_export_formats()
{
    $curl_warning = !function_exists('curl_version') ? __( 'cURL must be enabled in order to use this format', 'houzezpropertyfeed' ) : '';
    $simplexml_warning = !class_exists('SimpleXMLElement') ? __( 'SimpleXML must be enabled in order to use this format', 'houzezpropertyfeed' ) : '';
    // FTP warning?
    // zipArchive warning?

    $branch_mapping_options = array();

    $houzez_ptype_settings = get_option('houzez_ptype_settings', array() );

    $args = array(
        'post_type' => 'property',
        'fields' => 'ids',
        'meta_query' => array(
            array(
                'key' => 'fave_agent_display_option',
                'value' => 'author_info',
            )
        ),
    );

    $property_query = new WP_Query( $args );

    if ( $property_query->have_posts() )
    {
        $branch_mapping_options['author_info'] = array();

        $users = get_users( array( 'orderby' => 'name' ) );
        foreach ( $users as $user ) 
        {
            $branch_mapping_options['author_info'][$user->ID] = $user->display_name;
        }
    }

    if ( !isset($houzez_ptype_settings['houzez_agents_post']) || ( isset($houzez_ptype_settings['houzez_agents_post']) && $houzez_ptype_settings['houzez_agents_post'] != 'disabled' ) )
    {
        $args = array(
            'post_type' => 'property',
            'fields' => 'ids',
            'meta_query' => array(
                array(
                    'key' => 'fave_agent_display_option',
                    'value' => 'agent_info',
                )
            ),
        );

        $property_query = new WP_Query( $args );

        if ( $property_query->have_posts() )
        {
            $args = array(
                'post_type' => 'houzez_agent',
                'nopaging' => true
            );

            $agent_query = new WP_Query( $args );

            if ( $agent_query->have_posts() )
            {
                $branch_mapping_options['agent_info'] = array();

                while ( $agent_query->have_posts() )
                {
                    $agent_query->the_post();

                    $branch_mapping_options['agent_info'][get_the_ID()] = get_the_title();
                }
            }
        }
    }

    if ( !isset($houzez_ptype_settings['houzez_agencies_post']) || ( isset($houzez_ptype_settings['houzez_agencies_post']) && $houzez_ptype_settings['houzez_agencies_post'] != 'disabled' ) )
    {
        $args = array(
            'post_type' => 'property',
            'fields' => 'ids',
            'meta_query' => array(
                array(
                    'key' => 'fave_agent_display_option',
                    'value' => 'agency_info',
                )
            ),
        );

        $property_query = new WP_Query( $args );

        if ( $property_query->have_posts() )
        {
            $args = array(
                'post_type' => 'houzez_agency',
                'nopaging' => true
            );

            $agent_query = new WP_Query( $args );

            if ( $agent_query->have_posts() )
            {
                $branch_mapping_options['agency_info'] = array();

                while ( $agent_query->have_posts() )
                {
                    $agent_query->the_post();

                    $branch_mapping_options['agency_info'][get_the_ID()] = get_the_title();
                }
            }
        }
    }

    $branch_mapping_fields = array(
        array(
            'type' => 'html',
            'html' => '<p style="font-size:1.1em"><strong>' . __( 'Branch Codes', 'houzezpropertyfeed' ) . '</strong></p>',
        )
    );

    if ( !empty($branch_mapping_options) )
    {
        foreach ( $branch_mapping_options as $type => $values )
        {
            foreach ( $values as $id => $name )
            {
                $branch_mapping_fields[] = array(
                    'type' => 'text',
                    'id' => 'branch_code_' . $type . '_' . $id . '_sales',
                    'label' => $name . ' (' . __( ucwords(str_replace("_info", "", $type)), 'houzezpropertyfeed' ) . ') - ' . __( 'Sales', 'houzezpropertyfeed' ),
                );
                $branch_mapping_fields[] = array(
                    'type' => 'text',
                    'id' => 'branch_code_' . $type . '_' . $id . '_lettings',
                    'label' => $name . ' (' . __( ucwords(str_replace("_info", "", $type)), 'houzezpropertyfeed' ) . ') - ' . __( 'Lettings', 'houzezpropertyfeed' ),
                );
            }
        }
    }

    $formats = array(
        'blm' => apply_filters( 'houzez_property_feed_export_format_options_blm', array(
            'name' => __( 'BLM', 'houzezpropertyfeed' ),
            'method' => 'cron', // cron / realtime / url
            'fields' => array_merge(array(
                array(
                    'type' => 'html',
                    'html' => '<p style="font-size:1.1em"><strong>' . __( 'FTP Details', 'houzezpropertyfeed' ) . '</strong></p>',
                ),
                array(
                    'id' => 'ftp_host',
                    'label' => __( 'FTP Host', 'houzezpropertyfeed' ),
                    'type' => 'text',
                ),
                array(
                    'id' => 'ftp_user',
                    'label' => __( 'FTP Username', 'houzezpropertyfeed' ),
                    'type' => 'text',
                ),
                array(
                    'id' => 'ftp_pass',
                    'label' => __( 'FTP Password', 'houzezpropertyfeed' ),
                    'type' => 'text',
                ),
                array(
                    'id' => 'ftp_dir',
                    'label' => __( 'FTP Directory', 'houzezpropertyfeed' ),
                    'type' => 'text',
                ),
                array(
                    'id' => 'ftp_passive',
                    'label' => __( 'Use FTP Passive Mode', 'houzezpropertyfeed' ),
                    'type' => 'checkbox',
                )
            ), $branch_mapping_fields, array(
                array(
                    'type' => 'html',
                    'html' => '<p style="font-size:1.1em"><strong>' . __( 'Advanced Settings', 'houzezpropertyfeed' ) . '</strong></p>',
                ),
                array(
                    'id' => 'overseas',
                    'label' => __( 'Use Overseas Format', 'houzezpropertyfeed' ),
                    'type' => 'checkbox',
                    'tooltip' => __( 'If selected, version 3i of the BLM format will be used<br>For overseas to work you must have the Houzez Country taxonomy enabled and a country selected that\'s not anything other than UK, United Kingdom, GB, Britain, Great Britain, England, Scotland, Wales or Ireland', 'houzezpropertyfeed' )
                ),
                array(
                    'id' => 'media_sent_as',
                    'label' => __( 'Media Sent as', 'houzezpropertyfeed' ),
                    'type' => 'select',
                    'options' => array(
                        'urls' => 'URLs',
                        'files' => 'Files',
                    ),
                    'tooltip' => 'URLs: Full URL to any media will be sent and the third party will need to download the files<br>Files: The physical media files will be sent to the portal'
                ),
                array(
                    'id' => 'incremental',
                    'label' => __( 'Incremental', 'houzezpropertyfeed' ),
                    'type' => 'checkbox',
                    'tooltip' => 'Only applicable when \'Files\' is selected above. If this setting is checked only new or changed media will be included in the upload, thus preventing every media file to be sent every time an export runs. We advise leaving this unticked whilst the feed is being setup. Only tick this once it\'s all up and running'
                ),
                array(
                    'id' => 'compressed',
                    'label' => __( 'Compress Files Into ZIP', 'houzezpropertyfeed' ),
                    'type' => 'checkbox',
                    'tooltip' => __( 'Compress everything into a single ZIP file and upload that one file', 'houzezpropertyfeed' ),
                )
            )),
            'field_mapping_fields' => array(
                'ADDRESS_1' => 'ADDRESS_1',
                'ADDRESS_2' => 'ADDRESS_2 (non-overseas only)',
                'ADDRESS_3' => 'ADDRESS_3 (non-overseas only)',
                'TOWN' => 'TOWN (non-overseas only)',
                'POSTCODE1' => 'POSTCODE1 (non-overseas only)',
                'POSTCODE2' => 'POSTCODE2 (non-overseas only)',
                'HOUSE_NAME_NUMBER' => 'HOUSE_NAME_NUMBER (overseas only)',
                'STREET_NAME' => 'STREET_NAME (overseas only)',
                'OS_TOWN_CITY' => 'OS_TOWN_CITY (overseas only)',
                'OS_REGION' => 'OS_REGION (overseas only)',
                'ZIPCODE' => 'ZIPCODE (overseas only)',
                'COUNTRY_CODE' => 'COUNTRY_CODE (overseas only)',
                'EXACT_LATITUDE' => 'EXACT_LATITUDE (overseas only)',
                'EXACT_LONGITUDE' => 'EXACT_LONGITUDE (overseas only)',
                'FEATURE1' => 'FEATURE1',
                'FEATURE2' => 'FEATURE2',
                'FEATURE3' => 'FEATURE3',
                'FEATURE4' => 'FEATURE4',
                'FEATURE5' => 'FEATURE5',
                'FEATURE6' => 'FEATURE6',
                'FEATURE7' => 'FEATURE7',
                'FEATURE8' => 'FEATURE8',
                'FEATURE9' => 'FEATURE9',
                'FEATURE10' => 'FEATURE10',
                'SUMMARY' => 'SUMMARY',
                'DESCRIPTION' => 'DESCRIPTION',
                'STATUS_ID' => 'STATUS_ID',
                'BEDROOMS' => 'BEDROOMS',
                'BATHROOMS' => 'BATHROOMS',
                'LIVING_ROOMS' => 'LIVING_ROOMS',
                'PRICE' => 'PRICE',
                'PRICE_QUALIFIER' => 'PRICE_QUALIFIER',
                'PROP_SUB_ID' => 'PROP_SUB_ID',
                'CREATE_DATE' => 'CREATE_DATE',
                'UPDATE_DATE' => 'UPDATE_DATE',
                'DISPLAY_ADDRESS' => 'DISPLAY_ADDRESS',
                'PUBLISHED_FLAG' => 'PUBLISHED_FLAG',
                'LET_DATE_AVAILABLE' => 'LET_DATE_AVAILABLE',
                'LET_BOND' => 'LET_BOND',
                'ADMINISTRATION_FEE' => 'ADMINISTRATION_FEE',
                'LET_TYPE_ID' => 'LET_TYPE_ID',
                'LET_FURN_ID' => 'LET_FURN_ID',
                'LET_RENT_FREQUENCY' => 'LET_RENT_FREQUENCY',
                'TENURE_TYPE_ID' => 'TENURE_TYPE_ID',
                'COUNCIL_TAX_BAND' => 'COUNCIL_TAX_BAND',
                'SHARED_OWNERSHIP' => 'SHARED_OWNERSHIP',
                'SHARED_OWNERSHIP_PERCENTAGE' => 'SHARED_OWNERSHIP_PERCENTAGE',
                'ANNUAL_GROUND_RENT' => 'ANNUAL_GROUND_RENT',
                'GROUND_RENT_REVIEW_PERIOD_YEARS' => 'GROUND_RENT_REVIEW_PERIOD_YEARS',
                'ANNUAL_SERVICE_CHARGE' => 'ANNUAL_SERVICE_CHARGE',
                'TENURE_UNEXPIRED_YEARS' => 'TENURE_UNEXPIRED_YEARS',
                'TRANS_TYPE_ID' => 'TRANS_TYPE_ID',
            ),
            'taxonomy_values' => array(
                'status' => array(
                    '0' => 'Available',
                    '1' => 'SSTC',
                    '2' => 'SSTCM (Scotland only)',
                    '3' => 'Under Offer',
                    '4' => 'Reserved',
                    '5' => 'Let Agreed',
                    '6' => 'Sold',
                    '7' => 'Let',
                ),
                'property_type' => array(
                    '0' => 'Not Specified',
                    '1' => 'Terraced',
                    '2' => 'End of Terrace',
                    '3' => 'Semi-Detached ',
                    '4' => 'Detached',
                    '5' => 'Mews',
                    '6' => 'Cluster House',
                    '7' => 'Ground Flat',
                    '8' => 'Flat',
                    '9' => 'Studio',
                    '10' => 'Ground Maisonette',
                    '11' => 'Maisonette',
                    '12' => 'Bungalow',
                    '13' => 'Terraced Bungalow',
                    '14' => 'Semi-Detached Bungalow',
                    '15' => 'Detached Bungalow',
                    '16' => 'Mobile Home',
                    '17' => 'Hotel',
                    '18' => 'Guest House',
                    '20' => 'Land',
                    '21' => 'Link Detached House',
                    '22' => 'Town House',
                    '23' => 'Cottage',
                    '24' => 'Chalet',
                    '27' => 'Villa',
                    '28' => 'Apartment',
                    '29' => 'Penthouse',
                    '30' => 'Finca',
                    '43' => 'Barn Conversion',
                    '44' => 'Serviced Apartments',
                    '45' => 'Parking',
                    '46' => 'Sheltered Housing',
                    '47' => 'Retirement Property',
                    '48' => 'House Share',
                    '49' => 'Flat Share',
                    '51' => 'Garages',
                    '52' => 'Farm House',
                    '53' => 'Equestrian',
                    '56' => 'Duplex',
                    '59' => 'Triplex',
                    '68' => 'Barn',
                    '95' => 'Village House',
                    '107' => 'Farm Land',
                    '110' => 'Plot',
                    '113' => 'Country House',
                    '116' => 'Stone House',
                    '117' => 'Caravan',
                    '118' => 'Lodge',
                    '120' => 'Manor House',
                    '121' => 'Stately Home',
                    '125' => 'Off-Plan',
                    '128' => 'Semi-detached Villa',
                    '131' => 'Detached Villa',
                    '142' => 'Hotel Room',
                    '143' => 'Block of Apartments',
                    '144' => 'Private Halls',
                    '253' => 'Commercial Property',
                ),
                'price_qualifier' => array(
                    '1' => 'POA',
                    '2' => 'Guide Price',
                    '3' => 'Fixed Price',
                    '4' => 'Offers in Excess of',
                    '5' => 'OIRO',
                    '6' => 'Sale by Tender',
                    '7' => 'From',
                    '9' => 'Shared Ownership',
                    '10' => 'Offers Over',
                    '11' => 'Part Buy Part Rent',
                    '12' => 'Shared Equity',
                ),
            ),
            'help_url' => 'https://houzezpropertyfeed.com/documentation/managing-exports/formats/blm/',
            'warnings' => array(), // maybe FTP warning? maybe ZipArchive warning
        ) ),
        'facebook' => apply_filters( 'houzez_property_feed_export_format_options_facebook', array(
            'name' => __( 'Facebook', 'houzezpropertyfeed' ),
            'method' => 'url', // cron / realtime / url
            'fields' => array(),
            'field_mapping_fields' => array(
                'name' => 'name',
                'availability' => 'availability',
                'description' => 'description',
                'latitude' => 'latitude',
                'longitude' => 'longitude',
                'listing_type' => 'listing_type',
                'num_baths' => 'num_baths',
                'num_beds' => 'num_beds',
                'price' => 'price',
                'property_type' => 'property_type',
                'url' => 'url',
                'year_built' => 'year_built',
            ),
            'taxonomy_values' => array(
                'status' => array(
                    'for_sale' => 'for_sale',
                    'for_rent' => 'for_rent',
                    'sale_pending' => 'sale_pending',
                    'recently_sold' => 'recently_sold',
                    'off_market' => 'off_market',
                    'available_soon' => 'available_soon',
                ),
                'property_type' => array(
                    'apartment' => 'apartment',
                    'builder_floor' => 'builder_floor',
                    'condo' => 'condo',
                    'house' => 'house',
                    'house_in_condominium' => 'house_in_condominium',
                    'house_in_villa' => 'house_in_villa',
                    'loft' => 'loft',
                    'penthouse' => 'penthouse',
                    'studio' => 'studio',
                    'townhouse' => 'townhouse',
                    'other' => 'other',
                )
            ),
            'help_url' => 'https://houzezpropertyfeed.com/documentation/managing-exports/formats/facebook/',
            'warnings' => array_filter( array( $simplexml_warning ) ),
        ) ),
        'idealista' => apply_filters( 'houzez_property_feed_export_format_options_idealista', array(
            'name' => __( 'Idealista', 'houzezpropertyfeed' ),
            'method' => 'cron', // cron / realtime / url
            'fields' => array_merge(array(
                array(
                    'type' => 'html',
                    'html' => '<div style="border:1px solid #c3c4c7; border-left:2px solid #2271b1; padding:1px 12px; box-shadow:0 1px 1px rgba(0,0,0,.04)">
                        <p style="margin:0.5em 0; padding:2px">Please note we have been made aware that Idealista no longer support this format but instead use the \'Kyero\' format to accept properties export.</p>
                        <p style="margin:0.5em 0; padding:2px"><a href="https://houzezpropertyfeed.com/documentation/managing-exports/formats/idealista/" target="_blank">Read more here</a></p>
                    </div>',
                ),
                array(
                    'type' => 'html',
                    'html' => '<p style="font-size:1.1em"><strong>' . __( 'Details', 'houzezpropertyfeed' ) . '</strong></p>',
                ),
                array(
                    'id' => 'customer_code',
                    'label' => __( 'Customer Code', 'houzezpropertyfeed' ),
                    'type' => 'text',
                ),
                array(
                    'id' => 'country',
                    'label' => __( 'Country', 'houzezpropertyfeed' ),
                    'type' => 'text',
                ),
                array(
                    'id' => 'contact_name',
                    'label' => __( 'Contact Name', 'houzezpropertyfeed' ),
                    'type' => 'text',
                ),
                array(
                    'id' => 'contact_email',
                    'label' => __( 'Contact Email Address', 'houzezpropertyfeed' ),
                    'type' => 'text',
                ),
                array(
                    'id' => 'primary_telephone_number_prefix',
                    'label' => __( 'Primary Telephone Number Prefix', 'houzezpropertyfeed' ),
                    'type' => 'text',
                ),
                array(
                    'id' => 'primary_telephone_number',
                    'label' => __( 'Primary Telephone Number', 'houzezpropertyfeed' ),
                    'type' => 'text',
                ),
                array(
                    'type' => 'html',
                    'html' => '<p style="font-size:1.1em"><strong>' . __( 'FTP Details', 'houzezpropertyfeed' ) . '</strong></p>',
                ),
                array(
                    'id' => 'ftp_host',
                    'label' => __( 'FTP Host', 'houzezpropertyfeed' ),
                    'type' => 'text',
                ),
                array(
                    'id' => 'ftp_user',
                    'label' => __( 'FTP Username', 'houzezpropertyfeed' ),
                    'type' => 'text',
                ),
                array(
                    'id' => 'ftp_pass',
                    'label' => __( 'FTP Password', 'houzezpropertyfeed' ),
                    'type' => 'text',
                ),
                array(
                    'id' => 'ftp_dir',
                    'label' => __( 'FTP Directory', 'houzezpropertyfeed' ),
                    'type' => 'text',
                ),
                array(
                    'id' => 'ftp_passive',
                    'label' => __( 'Use FTP Passive Mode', 'houzezpropertyfeed' ),
                    'type' => 'checkbox',
                )
            )
            ),
            'field_mapping_fields' => array(
                'ADDRESS_1' => 'ADDRESS_1',
            ),
            'taxonomy_values' => array(
                'property_type' => array(
                    "flat" => "",
                    "house" => "",
                    "house_andar_moradia" => "",
                    "house_independent" => "",
                    "house_semidetached" => "",
                    "house_terraced" => "",
                    "house_villa" => "",
                    "rustic" => "",
                    "rustic_house" => "",
                    "rustic_village" => "",
                    "rustic_castle" => "",
                    "rustic_palace" => "",
                    "rustic_baita" => "",
                    "rustic_rural" => "",
                    "rustic_casalecascina" => "",
                    "rustic_caseron" => "",
                    "rustic_cortijo" => "",
                    "rustic_masia" => "",
                    "rustic_masseria" => "",
                    "rustic_moinho" => "",
                    "rustic_montealentejano" => "",
                    "rustic_quinta" => "",
                    "rustic_solar" => "",
                    "rustic_terrera" => "",
                    "rustic_torre" => "",
                    "rustic_trullo" => "",
                ),
            ),
            'help_url' => 'https://houzezpropertyfeed.com/documentation/managing-exports/formats/idealista/',
            'warnings' => array(),
        ) ),
        'kyero' => apply_filters( 'houzez_property_feed_export_format_options_kyero', array(
            'name' => __( 'Kyero', 'houzezpropertyfeed' ),
            'method' => 'url', // cron / realtime / url
            'fields' => array(),
            'field_mapping_fields' => array(
                'date' => 'date',
                'ref' => 'ref',
                'price' => 'price',
                'currency' => 'currency',
                'price_freq' => 'price_freq',
                'type' => 'type',
                'town' => 'town',
                'province' => 'province',
                'country' => 'country',
                'latitude' => 'latitude',
                'longitude' => 'longitude',
                'beds' => 'beds',
                'baths' => 'baths',
                'desc/en' => 'desc',
            ),
            'taxonomy_values' => array(
                'property_type' => array(
                    'apartment' => 'Apartment',
                    'duplex' => 'Duplex',
                    'penthouse' => 'Penthouse',
                    'studio' => 'Studio',
                    'triplex' => 'Triplex',
                    'bungalow' => 'Bungalow',
                    'villa' => 'Villa',
                    'terraced house' => 'Terraced house',
                    'town house' => 'Town house',
                    'village house' => 'Village house',
                    'farm' => 'Farm',
                    'cortijo' => 'Cortijo',
                    'country house' => 'Country house',
                    'farmhouse' => 'Farmhouse',
                    'finca' => 'Finca',
                    'land' => 'Land',
                    'ruin' => 'Ruin',
                    'cave house' => 'Cave house',
                    'garage' => 'Garage',
                    'parking space' => 'Parking space',
                    'commercial' => 'Commercial',
                    'mobile home' => 'Mobile home',
                    'wooden chalet' => 'Wooden chalet',
                )
            ),
            'help_url' => 'https://houzezpropertyfeed.com/documentation/managing-exports/formats/kyero/',
            'warnings' => array_filter( array( $simplexml_warning ) ),
        ) ),
        'rtdf' => apply_filters( 'houzez_property_feed_export_format_options_rtdf', array(
            'name' => __( 'Rightmove Real-Time Data Feed', 'houzezpropertyfeed' ),
            'method' => 'realtime', // cron / realtime / url
            'fields' => array_merge(array(
                array(
                    'id' => 'network_id',
                    'label' => __( 'Network ID', 'houzezpropertyfeed' ),
                    'type' => 'text',
                ),
                array(
                    'id' => 'certificate_file',
                    'label' => __( 'Certificate File (.pem)', 'houzezpropertyfeed' ),
                    'type' => 'file',
                ),
                array(
                    'id' => 'certificate_password',
                    'label' => __( 'Certificate  Password', 'houzezpropertyfeed' ),
                    'type' => 'text',
                ),
            ), $branch_mapping_fields, array(
                array(
                    'type' => 'html',
                    'html' => '<p style="font-size:1.1em"><strong>' . __( 'API URLs', 'houzezpropertyfeed' ) . '</strong></p>',
                ),
                array(
                    'id' => 'send_property_url',
                    'label' => __( 'Send Property URL', 'houzezpropertyfeed' ),
                    'type' => 'text',
                    'tooltip' => __( 'For Rightmove this will likely be:<br>
                    Test: https://adfapi.adftest.rightmove.com/v1/property/sendpropertydetails<br>
                    Live: https://adfapi.rightmove.co.uk/v1/property/sendpropertydetails<br><br>
                    For OnTheMarket this will likely be:<br>
                    https://realtime-api.onthemarket.com/v1/property/sendpropertydetails', 'houzezpropertyfeed' )
                ),
                array(
                    'id' => 'previous_send_property_url',
                    'type' => 'hidden',
                ),
                array(
                    'id' => 'remove_property_url',
                    'label' => __( 'Remove Property URL', 'houzezpropertyfeed' ),
                    'type' => 'text',
                    'tooltip' => __( 'For Rightmove this will likely be:<br>
                    Test: https://adfapi.adftest.rightmove.com/v1/property/removeproperty<br>
                    Live: https://adfapi.rightmove.co.uk/v1/property/removeproperty<br><br>
                    For OnTheMarket this will likely be:<br>
                    https://realtime-api.onthemarket.com/v1/property/removeproperty', 'houzezpropertyfeed' )
                ),
                array(
                    'id' => 'get_branch_properties_url',
                    'label' => __( 'Get Branch Properties URL', 'houzezpropertyfeed' ),
                    'type' => 'text',
                    'tooltip' => __( 'For Rightmove this will likely be:<br>
                    Test: https://adfapi.adftest.rightmove.com/v1/property/getbranchpropertylist<br>
                    Live: https://adfapi.rightmove.co.uk/v1/property/getbranchpropertylist<br><br>
                    For OnTheMarket this will likely be:<br>
                    https://realtime-api.onthemarket.com/v1/property/getbranchpropertylist', 'houzezpropertyfeed' )
                ),
                array(
                    'type' => 'html',
                    'html' => '<p style="font-size:1.1em"><strong>' . __( 'Advanced Settings', 'houzezpropertyfeed' ) . '</strong></p>',
                ),
                array(
                    'id' => 'overseas',
                    'label' => __( 'Use Overseas Format', 'houzezpropertyfeed' ),
                    'type' => 'checkbox',
                    'tooltip' => __( 'For overseas to work you must have the Houzez Country taxonomy enabled and a country selected that\'s not anything other than UK, United Kingdom, GB, Britain, Great Britain, England, Scotland, Wales or Ireland', 'houzezpropertyfeed' )
                ),
                array(
                    'id' => 'only_send_if_different',
                    'label' => __( 'Only Send When Data Has Changed', 'houzezpropertyfeed' ),
                    'type' => 'checkbox',
                    'tooltip' => __( 'To reduce the number of requests tick this option to ensure we only send data if it\'s different to the last time we sent it', 'houzezpropertyfeed' )
                ),
            )),
            'taxonomy_values' => array(
                'status' => array(
                    '1' => 'Available',
                    '2' => 'SSTC',
                    '3' => 'SSTCM',
                    '4' => 'Under Offer',
                    '5' => 'Reserved',
                    '6' => 'Let Agreed',
                ),
                'property_type' => array(
                    '0' => 'Not Specified',
                    '1' => 'Terraced House',
                    '2' => 'End of terrace house',
                    '3' => 'Semi-detached house',
                    '4' => 'Detached house',
                    '5' => 'Mews house',
                    '6' => 'Cluster house',
                    '7' => 'Ground floor flat',
                    '8' => 'Flat',
                    '9' => 'Studio flat',
                    '10' => 'Ground floor maisonette',
                    '11' => 'Maisonette',
                    '12' => 'Bungalow',
                    '13' => 'Terraced bungalow',
                    '14' => 'Semi-detached bungalow',
                    '15' => 'Detached bungalow',
                    '16' => 'Mobile home',
                    '20' => 'Land (Residential)',
                    '21' => 'Link detached house',
                    '22' => 'Town house',
                    '23' => 'Cottage',
                    '24' => 'Chalet',
                    '25' => 'Character Property',
                    '26' => 'House (unspecified)',
                    '27' => 'Villa',
                    '28' => 'Apartment',
                    '29' => 'Penthouse',
                    '30' => 'Finca',
                    '43' => 'Barn Conversion',
                    '44' => 'Serviced apartment',
                    '45' => 'Parking',
                    '46' => 'Sheltered Housing',
                    '47' => 'Retirement property',
                    '48' => 'House share',
                    '49' => 'Flat share',
                    '50' => 'Park home',
                    '51' => 'Garages',
                    '52' => 'Farm House',
                    '53' => 'Equestrian facility',
                    '56' => 'Duplex',
                    '59' => 'Triplex',
                    '62' => 'Longere',
                    '65' => 'Gite',
                    '68' => 'Barn',
                    '71' => 'Trulli',
                    '74' => 'Mill',
                    '77' => 'Ruins',
                    '80' => 'Restaurant',
                    '83' => 'Cafe',
                    '86' => 'Mill',
                    '92' => 'Castle',
                    '95' => 'Village House',
                    '101' => 'Cave House',
                    '104' => 'Cortijo',
                    '107' => 'Farm Land',
                    '110' => 'Plot',
                    '113' => 'Country House',
                    '117' => 'Caravan',
                    '118' => 'Lodge',
                    '119' => 'Log Cabin',
                    '120' => 'Manor House',
                    '121' => 'Stately Home',
                    '125' => 'Off-Plan',
                    '128' => 'Semi-detached Villa',
                    '131' => 'Detached Villa',
                    '134' => 'Bar/Nightclub',
                    '137' => 'Shop',
                    '140' => 'Riad',
                    '141' => 'House Boat',
                    '142' => 'Hotel Room',
                    '143' => 'Block of Apartments',
                    '144' => 'Private Halls',
                    '178' => 'Office',
                    '181' => 'Business Park',
                    '184' => 'Serviced Office',
                    '187' => 'Retail Property (High Street)',
                    '190' => 'Retail Property (Out of Town)',
                    '193' => 'Convenience Store',
                    '196' => 'Garages',
                    '199' => 'Hairdresser/Barber Shop',
                    '202' => 'Hotel',
                    '205' => 'Petrol Station',
                    '208' => 'Post Office',
                    '211' => 'Pub',
                    '214' => 'Workshop & Retail Space,',
                    '217' => 'Distribution Warehouse',
                    '220' => 'Factory',
                    '223' => 'Heavy Industrial',
                    '226' => 'Industrial Park',
                    '229' => 'Light Industrial',
                    '232' => 'Storage',
                    '235' => 'Showroom',
                    '238' => 'Warehouse',
                    '241' => 'Land (Commercial)',
                    '244' => 'Commercial Development',
                    '247' => 'Industrial Development',
                    '250' => 'Residential Development',
                    '253' => 'Commercial Property',
                    '256' => 'Data Centre',
                    '259' => 'Farm',
                    '262' => 'Healthcare Facility',
                    '265' => 'Marine Property',
                    '268' => 'Mixed Use',
                    '271' => 'Research & Development Facility',
                    '274' => 'Science Park',
                    '277' => 'Guest House',
                    '280' => 'Hospitality',
                    '283' => 'Leisure Facility',
                    '298' => 'Takeaway',
                    '301' => 'Childcare Facility',
                    '304' => 'Smallholding',
                    '307' => 'Place of Worship',
                    '310' => 'Trade Counter',
                    '511' => 'Coach House',
                    '512' => 'House of Multiple Occupation',
                    '535' => 'Sports facilities',
                    '538' => 'Spa',
                    '541' => 'Campsite & Holiday Village',
                )
            ),
            'help_url' => 'https://houzezpropertyfeed.com/documentation/managing-exports/formats/rtdf/',
            'warnings' => array_filter( array( $curl_warning ) ),
        ) ),
        'thribee' => apply_filters( 'houzez_property_feed_export_format_options_thribee', array(
            'name' => __( 'Thribee (Trovit/Mitula/Nestoria/Nuroa)', 'houzezpropertyfeed' ),
            'method' => 'url', // cron / realtime / url
            'fields' => array(),
            'field_mapping_fields' => array(
                'id' => 'id',
                'url' => 'url',
                'title' => 'title',
                'type' => 'type',
                'agency' => 'agency',
                'content' => 'content',
                'property_type' => 'property_type',
                'rooms' => 'rooms',
                'bathrooms' => 'bathrooms',
                'parking' => 'parking',
                'address' => 'address',
                'city' => 'city',
                'city_area' => 'city_area',
                'postcode' => 'postcode',
                'region' => 'region',
                'latitude' => 'latitude',
                'longitude' => 'longitude',
                'virtual_tour' => 'virtual_tour',
                'expiration_date' => 'expiration_date',
                'plot_area' => 'plot_area',
                'floor_number' => 'floor_number',
                'is_furnished' => 'is_furnished',
                'is_new' => 'is_new',
                'condition' => 'condition',
                'year' => 'year',
            ),
            'taxonomy_values' => array(
                'property_type' => array(
                    'cottage' => 'cottage',
                    'bungalow' => 'bungalow',
                    'flat' => 'flat',
                    'apartment' => 'apartment',
                    'penthouse' => 'penthouse',
                    'garden flat' => 'garden flat',
                    'studio' => 'studio',
                    'loft' => 'loft',
                    'detached house' => 'detached house',
                    'semi-detached house' => 'semi-detached house',
                    'terraced house' => 'terraced house',
                    'maisonette' => 'maisonette',
                )
            ),
            'help_url' => 'https://houzezpropertyfeed.com/documentation/managing-exports/formats/thribee/',
            'warnings' => array_filter( array( $simplexml_warning ) ),
        ) ),
        'zoopla' => apply_filters( 'houzez_property_feed_export_format_options_zoopla', array(
            'name' => __( 'Zoopla Real-Time Data Feed', 'houzezpropertyfeed' ),
            'method' => 'realtime', // cron / realtime / url
            'fields' => array_merge(array(
                array(
                    'id' => 'certificate_file',
                    'label' => __( 'Signed Certificate File (.crt)', 'houzezpropertyfeed' ),
                    'type' => 'file',
                ),
                array(
                    'id' => 'private_key_file',
                    'label' => __( 'Private Key File (.pem)', 'houzezpropertyfeed' ),
                    'type' => 'file',
                ),
            ), $branch_mapping_fields, array(
                array(
                    'type' => 'html',
                    'html' => '<p style="font-size:1.1em"><strong>' . __( 'API URLs', 'houzezpropertyfeed' ) . '</strong></p>',
                ),
                array(
                    'id' => 'send_property_url',
                    'label' => __( 'Send Property URL', 'houzezpropertyfeed' ),
                    'type' => 'text',
                    'tooltip' => __( 'For Zoopla this will likely be:<br>
                    Test: https://realtime-listings-api.webservices.zpg.co.uk/sandbox/v1/listing/update<br>
                    Live: https://realtime-listings-api.webservices.zpg.co.uk/live/v1/listing/update', 'houzezpropertyfeed' )
                ),
                array(
                    'id' => 'previous_send_property_url',
                    'type' => 'hidden',
                ),
                array(
                    'id' => 'remove_property_url',
                    'label' => __( 'Remove Property URL', 'houzezpropertyfeed' ),
                    'type' => 'text',
                    'tooltip' => __( 'For Zoopla this will likely be:<br>
                    Test: https://realtime-listings-api.webservices.zpg.co.uk/sandbox/v1/listing/delete<br>
                    Live: https://realtime-listings-api.webservices.zpg.co.uk/live/v1/listing/delete', 'houzezpropertyfeed' )
                ),
                array(
                    'id' => 'get_branch_properties_url',
                    'label' => __( 'Get Branch Properties URL', 'houzezpropertyfeed' ),
                    'type' => 'text',
                    'tooltip' => __( 'For Zoopla this will likely be:<br>
                    Test: https://realtime-listings-api.webservices.zpg.co.uk/sandbox/v1/listing/list<br>
                    Live: https://realtime-listings-api.webservices.zpg.co.uk/live/v1/listing/list', 'houzezpropertyfeed' )
                ),
                array(
                    'type' => 'html',
                    'html' => '<p style="font-size:1.1em"><strong>' . __( 'Advanced Settings', 'houzezpropertyfeed' ) . '</strong></p>',
                ),
                array(
                    'id' => 'overseas',
                    'label' => __( 'Use Overseas Format', 'houzezpropertyfeed' ),
                    'type' => 'checkbox',
                    'tooltip' => __( 'For overseas to work you must have the Houzez Country taxonomy enabled and a country selected that\'s not anything other than UK, United Kingdom, GB, Britain, Great Britain, England, Scotland, Wales or Ireland', 'houzezpropertyfeed' )
                ),
                array(
                    'id' => 'only_send_if_different',
                    'label' => __( 'Only Send When Data Has Changed', 'houzezpropertyfeed' ),
                    'type' => 'checkbox',
                    'tooltip' => __( 'To reduce the number of requests tick this option to ensure we only send data if it\'s different to the last time we sent it', 'houzezpropertyfeed' )
                ),
            )),
            'taxonomy_values' => array(
                'status' => array(
                    'available' => 'Available',
                    'under_offer' => 'Under Offer',
                    'sold_subject_to_contract' => 'Sold STC',
                    'sold' => 'Sold',
                    'let_agreed' => 'Let Agreed',
                    'let' => 'Let',
                ),
                'property_type' => array(
                    'barn_conversion' => 'Barn conversion',
                    'block_of_flats' => 'Block of flats',
                    'bungalow' => 'Bungalow',
                    'chalet' => 'Chalet',
                    'chateau' => 'Château',
                    'cottage' => 'Cottage',
                    'country_house' => 'Country house',
                    'detached' => 'Detached house',
                    'detached_bungalow' => 'Detached bungalow',
                    'end_terrace' => 'End terrace house',
                    'equestrian' => 'Equestrian property',
                    'farm' => 'Farm',
                    'farmhouse' => 'Farmhouse',
                    'finca' => 'Finca',
                    'flat' => 'Flat',
                    'houseboat' => 'Houseboat',
                    'land' => 'Land',
                    'link_detached' => 'Link-detached house',
                    'lodge' => 'Lodge',
                    'longere' => 'Longère',
                    'maisonette' => 'Maisonette',
                    'mews' => 'Mews house',
                    'park_home' => 'Mobile/park home',
                    'parking' => 'Parking/garage',
                    'riad' => 'Riad',
                    'semi_detached' => 'Semi-detached house',
                    'semi_detached_bungalow' => 'Semi-detached bungalow',
                    'studio' => 'Studio',
                    'terraced' => ' Terraced house',
                    'terraced_bungalow' => 'Terraced bungalow',
                    'town_house' => 'Town house',
                    'villa' => 'Villa',
                )
            ),
            'help_url' => 'https://houzezpropertyfeed.com/documentation/managing-exports/formats/zoopla/',
            'warnings' => array_filter( array( $curl_warning ) ),
        ) ),
    );

    $formats = apply_filters( 'houzez_property_feed_export_formats', $formats );

    return $formats;
}

function get_houzez_property_feed_export_format( $key )
{
    $formats = get_houzez_property_feed_export_formats();
    
    return isset($formats[$key]) ? $formats[$key] : false;
}

function get_format_from_export_id( $export_id )
{
    $formats = get_houzez_property_feed_export_formats();

    $options = get_option( 'houzez_property_feed' , array() );
    $exports = ( isset($options['exports']) && is_array($options['exports']) && !empty($options['exports']) ) ? $options['exports'] : array();

    if ( isset($exports[$export_id]) )
    {
        $format = $exports[$export_id]['format'];

        return get_houzez_property_feed_export_format( $format );
    }
    
    return false;
}