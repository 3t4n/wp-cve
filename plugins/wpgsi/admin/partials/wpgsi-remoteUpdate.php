<?php

/**
 * Provide a admin area view for the plugin
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       javmah.com
 * @since      3.6.0
 * @package    Wpgsi
 * @subpackage Wpgsi/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class='wrap'>
    <div id="icon-options-general" class="icon32"> </div>
	<h1><?php esc_attr_e("Step-by-Step instructions for WordPress and WooCommerce update from Google sheet.", "wpgsi");?></h1>
    <?php
        # Local host notification for remote update
        if(isset($_SERVER['REMOTE_ADDR']) AND in_array($_SERVER['REMOTE_ADDR'], array('localhost', '127.0.0.1', '::1'))){
            echo"<div class='notice notice-error'>";
                echo"<p> <b> INFORMATION : </b> the remote update will not work on localhost, For remote update site must have been <i> Google Apps Script </i> accessible.</p>";
            echo"</div>";
            # keeping log 
            $this->common->wpgsi_log(get_class($this), __METHOD__, "530", "ERROR: the remote update will not work on localhost.");
        }
    ?>
    <div id="service_account_help">
        <div id='step_1'>
            <h3> Step 1 : </h3>
            <p>
                <b>1.</b> Click to the <code> <b>Spreadsheet Integration</b></code> button on WP Admin.
                <br><br>
                <b>2.</b> Make sure remote update from Google sheet checkbox is checked.
                <br><br>
                <b>3.</b> Click to the button. You will see the this Help Page.
                <br><br>
                <!-- Loading Starts -->
                <img  style="display: block; margin: 0 auto;"  width="80%" height="80%" src="<?php echo plugins_url( '../css/update/1_update.PNG', __FILE__  ); ?>" alt="loading...">
                <!-- Loading Ends -->
                <br><br>
                <b>4.</b> Now please<i> <b  title="Click here to copy the code." id='copyCodeI' > copy the below code </b></i>. Don't make any change to the code.  
                <br><br>
<pre id='codePre'>   
<code id='appScriptCode'>
    function onOpen(){
        var ui = SpreadsheetApp.getUi();
        ui.createMenu("Update Site").addItem("<?php echo $integrationsTitle; ?>", "menuCallBackFunc").addToUi();
    }

    function menuCallBackFunc(){
        // Getting sheet information and data
        var sheet     = SpreadsheetApp.openById("<?php echo $SpreadsheetID; ?>").getSheetByName("<?php echo $Worksheet; ?>");
        var sheetData = sheet.getDataRange().getValues();
        // ***if you wish to skip the first row, uncomment the below line and comment on the previous line;
        // var sheetData = sheet.getRange(2,1,sheet.getLastRow()-1,sheet.getLastColumn()).getValues();
        var token     = "<?php echo $userToken ?>";
        var JSONdata  = JSON.stringify({"token": token,"sheetData": sheetData});
        // Processing data for HTTP request
        var options   = {
            "contentType"  : "application/json",
            "method"       : "post",
            "payload"      : JSONdata 
        }; 
        // URL
        var acceptUrl = "<?php echo get_site_url() . '/wp-json/wpgsi/accept'?>";
        // initiate request 
        var acceptResponse = UrlFetchApp.fetch( acceptUrl, options);
        // Converting getContentText() into JSON 
        var acceptResponseJSON = JSON.parse(acceptResponse.getContentText());
        // Check to see Data is saved in the option database 
        if(acceptResponseJSON['code'] && acceptResponseJSON['code'] == 200){
            // Keeping log
            console.log("SUCCESS: Site successfully saved the data to the Option table.");
            // request for update loop starts
            for(let i = 1; i < 10; i++){
                var updateResponse      = UrlFetchApp.fetch("<?php echo get_site_url() . '/wp-json/wpgsi/update'?>");
                var updateResponseJSON  = JSON.parse(updateResponse.getContentText());
                // Breaking the update request Loop if Update successfully completed
                if(updateResponseJSON['code'] && updateResponseJSON['code'] == 202){
                    // Showing popup notification 
                    SpreadsheetApp.getUi().alert("SUCCESS: Update success completed. Please see your plugin log for more information.");
                    console.log( "SUCCESS: Update success completed. Please see your plugin log for more information.");
                    break; 
                }
                // if error on updating data 
                if(updateResponseJSON['code'] && updateResponseJSON['code'] == 501){
                    // Showing popup notification 
                    SpreadsheetApp.getUi().alert("ERROR: error on update. Please see your plugin log for more information.");
                    console.log( "ERROR: error on update. Please see your plugin log for more information.");
                    console.log( updateResponseJSON );
                    break; 
                }
                // *remember some updateResponseJSON will be 201 code, thats are updating... done response is 202
                // Wait for 9 second
                Utilities.sleep(9000);
            }
        }else{
            console.log("ERROR : Data didn't saved in the site, Please see your plugin log for more information.");
            // Showing popup notification 
            SpreadsheetApp.getUi().alert("ERROR : Data didn't saved in the site, Please see your plugin log for more information.");
        }
    }
</code>
</pre>  
                <br><br>
            </p>
        </div>

       
        <div id='step_2'>
            <h3> Step 2 : </h3>
            <p> 
                <b>0.</b> Now open your connected Google Sheet <code> <b> <?php echo "<a target='_blank' href='https://docs.google.com/spreadsheets/d/" . $SpreadsheetID . '/edit#gid=' . $WorksheetID . "'>" . $Spreadsheet . " > " . $Worksheet  . "</a>" ; ?> </b></code>
                <br><br>
                <b>1.</b> Click to the <code><b> Extensions </b></code> menu button.
                <br><br>
                <b>2.</b> Click to the button <code><b> Apps Script </b></code>
                <br><br> 
                <!-- Loading Starts -->
                <img  style="display: block; margin: 0 auto;"  width="80%" height="80%" src="<?php echo plugins_url( '../css/update/2_update.PNG', __FILE__  ); ?>" alt="loading...">
                <!-- Loading Ends -->
                <br><br> 
                <b>3.</b> Now another window (App Script window) will open.
                <br><br> 
                <!-- Loading Starts -->
                <img  style="display: block; margin: 0 auto;"  width="80%" height="80%" src="<?php echo plugins_url( '../css/update/3_update.PNG', __FILE__  ); ?>" alt="loading...">
                <!-- Loading Ends -->
                <br><br> 
                <b>4.</b> Rename the App script project (currently it is <b>Untitled project</b> ).
                <br><br>
                <b>5.</b> Remove the existing code (if there is anything, remove myFunction() function)
                <br><br>
                <!-- Loading Starts -->
                <img  style="display: block; margin: 0 auto;"  width="80%" height="80%" src="<?php echo plugins_url( '../css/update/4_update.PNG', __FILE__  ); ?>" alt="loading...">
                <!-- Loading Ends -->
                <br><br>
                <b>6.</b>Now Past the copy code here as is and Now Save the Project 
                <br><br>
                <!-- Loading Starts -->
                <img  style="display: block; margin: 0 auto;"  width="80%" height="80%" src="<?php echo plugins_url( '../css/update/5_update.PNG', __FILE__  ); ?>" alt="loading...">
                <!-- Loading Ends -->
                <br><br>
                <b>7.</b> Now click to the <code>  Run </code> button. a Permission Window Will open 
                <br><br>
                <!-- Loading Starts -->
                <img  style="display: block; margin: 0 auto;"  width="80%" height="80%" src="<?php echo plugins_url( '../css/update/6_update.PNG', __FILE__  ); ?>" alt="loading...">
                <!-- Loading Ends -->
                <br><br>
                <b>7.</b> Give your script the Permission to run, So that it can Update the site.
                <br><br>
                <!-- Loading Starts -->
                <img  style="display: block; margin: 0 auto;"  width="80%" height="80%" src="<?php echo plugins_url( '../css/update/7_update.PNG', __FILE__  ); ?>" alt="loading...">
                <!-- Loading Ends -->
                <br><br>
                <!-- Loading Starts -->
                <img  style="display: block; margin: 0 auto;"  width="80%" height="80%" src="<?php echo plugins_url( '../css/update/8_update.PNG', __FILE__  ); ?>" alt="loading...">
                <!-- Loading Ends -->
                <br><br>
                <!-- Loading Starts -->
                <img  style="display: block; margin: 0 auto;"  width="80%" height="80%" src="<?php echo plugins_url( '../css/update/9_update.PNG', __FILE__  ); ?>" alt="loading...">
                <!-- Loading Ends -->
                <br><br>
                <!-- Loading Starts -->
                <img  style="display: block; margin: 0 auto;"  width="80%" height="80%" src="<?php echo plugins_url( '../css/update/10_update.PNG', __FILE__  ); ?>" alt="loading...">
                <!-- Loading Ends -->
                <br><br>
                <b>8.</b> Now open you connected Google sheet again. You will see a custom Menu button, Click there to update you site. it will show you popup message.
                <br><br>
                <!-- Loading Starts -->
                <img  style="display: block; margin: 0 auto;"  width="80%" height="80%" src="<?php echo plugins_url( '../css/update/11_update.PNG', __FILE__  ); ?>" alt="loading...">
                <!-- Loading Ends -->
            </p>
        </div>
      

        <div id='step_4'>
            <br><br>
            <h3> <span style='color:red;'> Step 3 : </span> Tips, Tricks & Safety measures. </h3>
            <p>
                <i><b>1.</b> Save a <b> backup of your site </b> before any kind of Update.</i>
                <br><br>

                <i><b>2.</b> Create separate integration for update. on that integrator use minimum event field, Just those field you wish to change. Don't forget to use post or product ID.</i>
                <br><br>

                <i><b>3.</b> Don't use this feature in multi-site network.</i>
                <br><br>

                <i><b>4.</b> This is a Powerfully feature Please use this responsibly.</i>
                <br><br>

                <i><b>5.</b> Remote update from Google Sheet will not work on <b> locally hosted site</b>.</i>
                <br><br>

                <i><b>6.</b> Don't update more than 100 post.</i>
                <br><br>

                <i><b>7.</b> If you face any Update ERROR please see this Plugin log.</i>
                <br><br>

                <i><b>8.</b> Please don't share your update code or token.</i>
                <br><br>

                <i><b>9.</b> If you don't use remote update, uncheck the remote update check box.</i>
                <br><br>

                <i><b>10.</b> If your integration didn't have any <b> Post ID or Product ID</b> it will not Update.</i>
                <br><br>

                <i><b>11.</b> If you don't sure what you are doing! then don't do anything. As you know its a Bulk update.</i>
                <br><br>
            </p>
        </div>
    </div>
	
    <!-- Style for this page  -->
    <style>
        #codePre{
            display:    block;
            padding:    10px;
            /* margin:  0 0 10px; */
            line-height:1.42857143;
            color:      #333;
            word-break: break-all;
            word-wrap:  break-word;
            background-color: #f8f9fa;
            border: 1px solid #eaeaea;
            width:  80%;
            margin: 0 auto;
        }

        #appScriptCode{
            background: #f8f9fa;
            color:      #003366;
            font-weight:bold;
        }
    </style>

    <script>
        function copyCodeFunc() {
            // Get the text field 
            // var copyText = document.getElementById("appScriptCode").innerHTML;

            // Copy the text inside the text field 
            // navigator.clipboard.writeText(copyText);

            // Testing to the Console 
            // console.log( copyText );
            // copyCodeI
            // document.getElementById("copyCodeI").style.color = "#00FF00";
        }

    </script>
				
</div>







