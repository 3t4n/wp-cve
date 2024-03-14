<?php  
return array(
    'shiptimizecolumntitle' => 'Status / Action',
    'automaticexport' => 'Automatic Export',
    'automaticexportdescription' => 'Send orders to shiptimize when status matches',
    'A new token will be automatically requested when this one expires' => 'A new token will be <b>automatically requested</b> when this one expires',
    "api sent status" => "api sent status",
    "api sent trackingId:" => "api sent trackingId:",
    'Carriers Available In your contract' => 'Carriers Available In your contract',
    'Has Pickup' => 'Has Pickup',
    'Click' => 'Click',
    'Choose Pickup Location' => 'Choose Pickup Location',
    'geolocationfailed' => 'Could not get latitude, longitude for address',
    'Credentials' => 'Credentials',
    "Diferent carrier selected by the api" => "Diferent carrier selected by the api",
    "Don't forget to set the appropriate cost for each carrier if you don't have free shipping for all orders"=>"Don't forget to set the appropriate cost for each carrier if you don't have free shipping for all orders" ,
    "Error on Export" => "Error on Export",
    "Exported" => 'Exported',
    'Export Preset Orders' => 'Export Preset Orders',
    'Export Preset Orders to' => 'Export Preset Orders to',
    'Export to' => 'Export to',
    'expires at' => 'Expires at',
    'Invalid Credentials' => 'Invalid Credentials',
    'If a google key is provided the map served will be a google map else an openmap will be shown' => "If a Google key is provided the map served  in check-out to offer Servicepoint locations will be a Google map instead of a default OpenMap. Make sure to enable <a href='%s'>Geocoding</a>" ,
    'if not opened' => 'if not opened',
    'No pickup points returned by the carrier for this address' => 'No pickup points returned by the carrier for this address',
    'Not Exported' => 'Not Exported',
    'Pickup Point' => 'Pickup Point',
    'Private Key' => 'Private Key',
    'Public Key' => 'Public Key',    
    'maptitle' => 'Select a Pickup Point',  
    'Select' => 'Select',
    'Selected Pickup' => 'Selected Pickup',
    'shipping zones' => 'shipping zones',
    'Sent %d orders. <br/>Exported: %d <br/> With Errors: %d' => 'Sent %d orders. <br/>Exported: %d <br/> With Errors: %d',
    "Unknown status of id" => 'Unknown status of id',
    "You can add them to" => "You can add them to",
    'When you click "Export All" in the orders view, export all orders not exported successfully, with status' => 'When you click "Export Preset Orders" in the orders view, export all orders not exported successfully, with status',
    'Service level' => 'Service Level',
    'printlabel' => 'Print Label', 
    'printlabeltitle' => 'Print Label', 
    'order'  => 'Order', 
    'label'  => 'Label', 
    'requestinglabel' => 'Requesting label',
    'labelclick' => 'click % to get your label if a new window did not open', 
    'labelprinted' => 'Label Printed',
    'labeltermsintro' => "<p>You can print labels manually or automatically by exporting them to shiptimize, but you can also print labels directly from Woocommerce. </p>
        <p>If you wish to print labels directly from Woocommerce, check that you've read these terms under \"print label\" in the tab \"options\".</p>  
        <p>To print a label from the order list, click the \"print label\" button. </p>",
    'labelterms' => "As soon as you click on the \"print label\" button the following happens:  
    <ol class=\"shiptimize-list\">
    <li> The order is exported to Shiptimize and the order details are forwarded to the carrier so that if the submission is successful we can send a shipping label back to the shopping platform. </li>
    <li> You can set the label format in the Shiptimize app  \"Settings\" > \"Printing\" > \"Print label format\"</li>
    <li> In case the order cannot be exported, an error message with additional information as to why the export was unsuccessful is returned.</li>
    <li> In case the order was previously exported, but no label is associated with it, the order data is resent. Any changes to the address are updated. </li>
    <li> If your <i>client choose one of the Shiptimize carriers at checkout</i>, the carrier and the options you set for it in woocommerce,  <i>given they are valid for the shipment</i>, will be assigned to the shipment</li>
    <li> In case no Shiptimize carrier is associated with the order, <i>the carrier and options set  in the Shiptimize shipping portal under \"Settings\" > \"Default Settings\" > \"Default Carrier\"</i> is automatically chosen. </li>
    <li>If you did not select a carrier in \"Standard Carrier\". The <i>first available carrier that ships to the given destination</i> is automatically selected.</li>
    <li><b>If you made a mistake, you can contact Shiptimize through the support section in the website and we'll help you. Time is paramount, please confirm if the label is correct after you get it.</b></li>
    </ol>",
    'labelbuttondescription' => 'To trigger a label print, in the order list, click the label button',
    'labelagree' => 'I have read the help section in the help tab and I understand how labels are printed from woocommerce.',
    'labellocked' => 'Print label disabled. Go to the shiptimize settings and check that you understand how print label works',
    'labelbulkprintitle' => 'Bulk print label',
    'labelbulkprint' => 'If you want to print labels for more than one order at a time. In the order list:
    <ol class="shiptimize-list">
    <li>select the orders for which you wish to print a label </li>
    <li>Select Shiptimize: print label, from the actions dropdown</li>
    <li>Click "apply"</li>
    </ol>',
    'pickupbehaviour' => 'Selecting a pickup Point is',
    'pickuppointbehavior0' => 'Optional',
    'pickuppointbehavior1' => 'Mandatory',
    'pickuppointbehavior2' => 'Disabled',
    'mandatorypointmsg' => "Please select a Pickup Point or choose a different shipping method",
//  WOO SPECIFIC STUFF 
    'PostNL uses a custom format for Netherlands addresses which will cause export errors. Please disable PostNL to use' => 'PostNL uses a custom format for Netherlands addresses which will cause export errors. Please disable PostNL to use',
    'setcredentials' =>  'invalid keys! Confirm in Shiptimize Settings if you copied them correctly',
    'pickuppointsoptions' => "Pickup Points",
    'pickuppointsdisable' => "Don't show this option to your clients, don't include the button or map at checkout ",
    'yes'=>'yes',
    'no'=>'no',
    'service_level'=>'Service Level',
    'cashservice' => 'Cash on delivery',
    'sendinsured' => 'Insured',
    'extraoptions' => 'Extra Options',
    'settings' => 'Settings',
    'help' => 'Help',
    'exportdescription' => '<p>In the Order list you can export orders by:</p>
    <b>Export All Orders</b><p>Will export only orders that where not exported and have one of the statuses you configured in <i>Export all</i> in the settings tab.</p>
    <br/><b>Export selected orders</b> <p>Will send to the app any selected order regardless of status. This allows you to re-export orders if you delete them in the app.</p>',
    'statusdescription' => 'In the order list you can view the order export status. If there is an error either while exporting or printing the label the status icon will become red.
    <p><b><i>Hover over the status icon to see the export history of the order</i></b></p><br/>Export status are listed bellow',
    'notexporteddescription' => 'Order not exported', 
    'successdescription' => 'Order exported successfully', 
    'exporterrordescription' => 'Order was exported <u>with errors</u>',
    'notprinteddescription' => 'No Label yet',
    'printsuccesseddescription' => 'Label successfully printed',
    'printerrordescription' => 'Label request <u>returned errors</u>',
    'useapititle' => 'Issues with order status updates?',
    'usewpapi' => "Use the woordpress API to send back order updates",
    'useapihelpinactive' => "<p>When you have configured order statusses to be automatically updated at certain events (on import, label create, and/or delivered) but you see <i>‘not found’</i> results in the tracking updates for shipments in your Shiptimize account the plugin could update order status updates via the WordPress API as an alternative. </p>
<p>However, the WordPress API seems to be disabled in your website. Hence, we have no alternative to update order statusses automatically.
<br/>Please contact your webdeveloper to enable your WordPress API and revisit this screen to find instructions. %s</p>
    ", 
    'useapihelp' => 'When you have configured order statusses to be automatically updated at certain events (on import, label create, and/or delivered) but you see ‘not found’ results in the tracking updates for shipments in your Shiptimize account please take the following steps:
<ol>
    <li>Activate the ‘Use WordPress API for Order updates’ bellow. %s </li>
    <li>Go to your Shiptimize account to create new keys from ‘Settings’ > ‘Integrations’ > ‘Key Management’ and enable the keys.</li>
    <li>Paste these new keys in the Shiptimize Credentials field in the Shiptimize Settings here in WordPress and click ‘Save Changes’ button.</li>
</ol>',
//  Sub values..     
    'extraoptions55' => '&nbsp;&nbsp;&nbsp;&nbsp;>',
    'extraoptions73' => '&nbsp;&nbsp;&nbsp;&nbsp;>',
//  Marketplace 
    'sending' => 'Sending ...',
    'requestaccount' => 'Request Shiptimize Account for this vendor',
    'submitrequest' => 'Request Account',
    'streetname' => 'Street',
    'zipcode' => 'Postal Code',
    'city' => 'City',
    'province' => 'Province', 
    'country' => 'Country', 
    'requestsent' => 'Our team will get back to you soon.',
    'connect2shiptimize' => 'Connect to Shiptimize',
    'disconnectshiptimize' => 'Disconnect from Shiptimize',
    'welcometitle' => 'Welcome to Shiptimize  & PakketMail',
    'welcomedescription' => 'The Multi-carrier shipping software that saves you time and money!',
    'welcomeskip' => 'Skip this, I already have an account!',
    'fiscal' => 'Fiscal',
    'start'  => "Let's start",
    'stepback' => 'Go Back',
    'continue' => 'Continue',
    'step1title'=>'Ship faster and better',
    'step1description'=>'with these three key improvements to your shipping process',
    'feature1title' => 'Save time with automation',
    'feature1description' => 'Optimize your deliveries by automating some of the most time consuming part of the process.
<br/><br/>Shipping labels, Track&Trace emails,
Return labels, Shipping Status',
    'feature2title' => 'Aggregation makes your life easier',
   'feature2description' => 'Gather different options and possibilities in one simple online platform.
<br/><br/>Multiple carriers, Shipping records, 
All selling channels',
    'feature3title' => 'Get a first-class assistance', 
    'feature3description' => 'Count on our first-class customer service to help you deal with all shipping related matters.
<br/><br/>Online and phone support, Bridge between your shop and the carriers,
Enhance of your customer services',
    'step2title' =>  'Your Shipping information',
    'step2description' => 'Why we need this? To serve you better and give you the best options.',
    'averageshipments' => 'Average Shipments per month',
    'companyname' => 'Company name',
    'contactperson' => 'Contact name',
    'contactemail' => 'Contact email',
    'contactphone' => 'Contact phone',
    'origincountry' => 'Country  I ship from',
    'contriesship' => 'Contries I currently ship to',
    'step3title' =>'Your account information is on his way!',
    'step3description' => 'Thanks for joining us! You will receive all the details via the email provided.',
    'finishsetup' => 'Finish Setup',
    'shiptimizesettings' => 'Shiptimize Settings',
    'whopays' => 'Who pays Shiptimize?',
    'you' => 'You',
    'yourvendors' => 'Your Vendors',
    'whopaysdescription' => 'If you pay Shiptimize, then every vendor shipping with shiptimize will inherit the rates you define',
    'inheritadminrates' => 'Shipping rules are defined by admin',
    'errors' => 'Errors', 
    'exportvendorsbtn' => 'Export Vendor List to Csv File',
    'defaultshipping' => 'Default Shipping type',
    'by_weight' => 'Shipping by Weight',
    'by_country' => 'Shipping by Country',
    'provincesdescription' => 'declare aditional provinces',
    'hidenotfree' => 'If at least one shipping method is available with cost 0, hide any shipping method with cost > 0',
    'hidenotfreetitle' => 'Hide Shipping Methods',
    'excludeclasses' => 'If at least one item in cart contains of these classes do not display this method',
    'exportvirtualtitle' => 'Virtual products & Virtual orders',
    'exportvirtualorders' => 'Export orders containing only virtual products',
    'exportvirtualproducts' => 'When exporting append virtual products to orders',
    'mapfieldmandatory' => 'mandatory. Please define a value.',
    'multiorderlabelwarn' => 'If you wish to print more than one label at a time, please use the application'
);  