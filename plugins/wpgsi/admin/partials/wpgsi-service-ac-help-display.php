<?php
/**
 * Provide a admin area view for the plugin
 * This file is used to markup the admin-facing aspects of the plugin.
 * @link       javmah.com
 * @since      <b>1.</b>0.0
 * @package    Wpgsi
 * @subpackage Wpgsi/admin/partials
 */
?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class='wrap'>
    <div id="icon-options-general" class="icon32"> </div>
	<h1> <?php esc_attr_e( "Step-by-Step instructions to create service account :", "wpgsi" ); ?> </h1>

    <div id="service_account_help">
       
        <div id='step_1'>
            <h3> Step 1 : </h3>
            <p>
                <b>1.</b> Click to the <code> <b>Spreadsheet Integration</b></code> button on WP Admin.
                <br><br>
                <b>2.</b> Click to the <code> <b>Settings</b></code> button.
                <br><br>
                <b>3.</b> Click to the <code> <b>enable API & create a service account</b></code> link. It will open Google API console in another Browser Tab. See the next step.
                <br><br>
                <!-- Loading Starts -->
                <img  style="display: block; margin: 0 auto; "  width="80%" height="80%" src="<?php echo plugins_url( '../css/screenshot-1.png', __FILE__  ); ?>" alt="loading...">
                <!-- Loading Ends -->
            </p>
        </div>

        <div id='step_2'>
            <h3> Step 2 : </h3>
            <p>
                <b>1.</b> After clicking to the <code> <b>3. enable API & create a service account</b></code> link. Below Window will Open. click to the Dropdown Arrow. ( text may not be the same as 'demo-account' ).
                <br><br>
                <!-- Loading Starts -->
                <img  style="display: block; margin: 0 auto; "  width="80%" height="80%" src="<?php echo plugins_url( '../css/screenshot-2.png', __FILE__  ); ?>" alt="loading...">
                <!-- Loading Ends -->
            </p>
        </div>
        
        <div id='step_3'>
            <h3> Step 3 : </h3>
            <p>
                <b>1.</b> Click to <code><b> NEW PROJECT</b></code> and create a New API Project.
                <br><br>
                <!-- Loading Starts -->
                <img  style="display: block; margin: 0 auto; "  width="80%" height="80%" src="<?php echo plugins_url( '../css/screenshot-3.png', __FILE__  ); ?>" alt="loading...">
                <!-- Loading Ends -->
            </p>
        
        </div>

        <div id='step_4'>
            <h3> Step 4 : </h3>
            <p>
                <b>1.</b> Change to your <code><b>Project name*</b></code>
                <br><br>
                <b>2.</b>  Click to <code><b> CREATE</b></code> button. for creating the Project.
                <br><br>
                <!-- Loading Starts -->
                <img  style="display: block; margin: 0 auto; "  width="80%" height="80%" src="<?php echo plugins_url( '../css/screenshot-4.png', __FILE__  ); ?>" alt="loading...">
                <!-- Loading Ends -->
            </p>
        
        </div>

        <div id='step_5'>
            <h3> Step 5 : </h3>
            <p>
                <b>1.</b> Click to the Dropdown Arrow and Select Newly Created project ( text may not be the same as 'New Spreadsheet Integration' ).
                <br><br>
                <b>2.</b> Click to the <code><b> Library</b></code> button. API Library will open.
                <br><br>
                <!-- Loading Starts -->
                <img  style="display: block; margin: 0 auto; "  width="80%" height="80%" src="<?php echo plugins_url( '../css/screenshot-5.png', __FILE__  ); ?>" alt="loading...">
                <!-- Loading Ends -->
            </p>
        </div>

        <div id='step_6'>
            <h3> Step 6 : </h3>
            <p>
                <b>1.</b> After clicking the <code><b> Library</b></code> button. The below page will open. Make sure that Project is your Newly Created one ( text may not be the same as 'New Spreadsheet Integration' ).
                <br><br>
                <b>2.</b> Now click Google Drive API & Enable it 
                <br><br>
                <b>3.</b> After Enable  Google Drive API, Now Enable Google Sheets API
                <br><br>
              
                <!-- Loading Starts -->
                <img  style="display: block; margin: 0 auto; "  width="80%" height="80%" src="<?php echo plugins_url( '../css/screenshot-6.png', __FILE__  ); ?>" alt="loading...">
                <!-- Loading Ends -->
                <br>
                <b>1.</b> Click the <code><b> ENABLE</b></code> button. and enable Google Drive API.
                <br><br>
                <!-- Loading Starts -->
                <img  style="display: block; margin: 0 auto; "  width="80%" height="80%" src="<?php echo plugins_url( '../css/screenshot-7.png', __FILE__  ); ?>" alt="loading...">
                <!-- Loading Ends -->
                <br>
                <b>1.</b> Make sure API Enabled.
                <br><br>
                <!-- Loading Starts -->
                <img  style="display: block; margin: 0 auto; "  width="80%" height="80%" src="<?php echo plugins_url( '../css/screenshot-8.png', __FILE__  ); ?>" alt="loading...">
                <!-- Loading Ends -->
                
                <!-- New Code Starts -->

                <br>
                <b>1.</b> Now click to Burger menu Icon.
                <br><br>
                <b>2.</b> Click to APIs & Services.
                <br><br>
                <b>3.</b> Select library again to see the API lists.
                <br><br>
                <!-- Loading Starts -->
                <img  style="display: block; margin: 0 auto;"  width="80%" height="80%" src="<?php echo plugins_url( '../css/screenshot-8-plus.png', __FILE__  ); ?>" alt="loading...">
                <!-- Loading Ends -->

                <!-- New Code Ends -->

                <br>
                <b>1.</b> Click <code><b> ENABLE</b></code> button. and enable Google Sheets API.
                <br><br>
                <!-- Loading Starts -->
                <img  style="display: block; margin: 0 auto; "  width="80%" height="80%" src="<?php echo plugins_url( '../css/screenshot-9.png', __FILE__  ); ?>" alt="loading...">
                <!-- Loading Ends -->
                <br>
                <b>1.</b> Make sure API Enabled.
                <br><br>
                <!-- Loading Starts -->
                <img  style="display: block; margin: 0 auto; "  width="80%" height="80%" src="<?php echo plugins_url( '../css/screenshot-10.png', __FILE__  ); ?>" alt="loading...">
                <!-- Loading Ends -->
                <br>
                <b>1.</b> Make sure Both Drive and Sheets API is Enabled.
                <br><br>
                <!-- Loading Starts -->
                <img  style="display: block; margin: 0 auto; "  width="80%" height="80%" src="<?php echo plugins_url( '../css/screenshot-11.png', __FILE__  ); ?>" alt="loading...">
                <!-- Loading Ends -->
            </p>
        
        </div>

        <div id='step_7'>
            <h3> Step 7 : </h3>
            <p>
                <b>1.</b>  Click to <code><b> Credentials</b></code> button. 
                <br><br>
                <b>2.</b> Make Sure Project is your newly created one ( text may not be the same as 'New Spreadsheet Integration' ).
                <br><br>
                <b>3.</b>  Click to <code><b>+ CREATE CREDENTIALS</b></code> button. 
                <br><br>
                <b>4.</b>  Select  <code><b> Service account</b></code>
                <br><br>
                <!-- Loading Starts -->
                <img  style="display: block; margin: 0 auto; "  width="80%" height="80%" src="<?php echo plugins_url( '../css/screenshot-14.png', __FILE__  ); ?>" alt="loading...">
                <!-- Loading Ends -->
            </p>
        </div>

        <div id='step_8'>
            <h3> Step 8 : </h3>
            <p>
                <b>1.</b> Now you have to create a Google service account to access its services. click to the  <code><b> Service account</b></code>
                <br><br>
                <b>2.</b> Make Sure Project is your newly created one ( text may not be the same as 'New Spreadsheet Integration' ).
                <br><br>
                <b>3.</b> Name your service account in our case I used  <code><b> Spreadsheet Integration Service Account </b></code>
                <br><br>
                <b>4.</b> After naming it will create a service account Email address. 
                <br><br>
                <b>5.</b> Click the <code><b> DONE</b></code> button.
                <br><br>
                <img  style="display: block; margin: 0 auto; "  width="80%" height="80%" src="<?php echo plugins_url( '../css/screenshot-15-Plus.png', __FILE__  ); ?>" alt="loading...">
                <!-- Loading Ends -->
                <br>
                <b>1.</b>  Click the service account Email Address.
                <br><br>
                <img  style="display: block; margin: 0 auto; "  width="80%" height="80%" src="<?php echo plugins_url( '../css/screenshot-16-plus.png', __FILE__  ); ?>" alt="loading...">
               <!-- Loading Ends -->
                <br>
                <b>1.</b> Now you have to create a Google service account to access its services. click to the  <code><b> KEYS </b></code> tab
                <br><br>
                <b>2.</b> Click <code><b>ADD KEY</b></code> dropdown.
                <br><br>
                <b>3.</b> Select <code><b> Create new key </b></code>  A new popups will open.
                <br><br>
                <img  style="display: block; margin: 0 auto; "  width="80%" height="80%" src="<?php echo plugins_url( '../css/screenshot-17-plus.png', __FILE__  ); ?>" alt="loading...">
                <!-- Loading Ends -->

                <br>
                <b>1.</b> Select <code><b> JSON</b></code> on popups.
                <br>
                <b>2.</b> Click on <code><b> CREATE</b></code>  after that a JSON file will download on your PC. Open that file & copy the content carefully  <code><b> as is</b></code>
                <br><br>

                <!-- Loading Starts -->
                <img  style="display: block; margin: 0 auto; "  width="80%" height="80%" src="<?php echo plugins_url( '../css/screenshot-19.png', __FILE__  ); ?>" alt="loading...">
                <!-- Loading Ends -->
            </p>
        </div>

        <div id='step_9'>
            <h3> Step 9 : </h3>
            <p>
                <b>1.</b> Click to the <code> <b>Spreadsheet Integration</b></code> button on WP Admin.
                <br><br>
                <b>2.</b> Click to the <code> <b> Settings</b></code> 
                <br><br>
                <b>3.</b> Past the Downloaded key here. Make sure Key starts with   <code> <b> { </b></code> 
                <br><br>
                <b>4.</b>  Make sure Key ends with   <code> <b> } </b></code> 
                <br><br>
                <b>5.</b>  Click  on  the <code> <b> SAVE</b></code>  button.
                <br><br>
                <!-- Loading Starts -->
                <img  style="display: block; margin: 0 auto; "  width="80%" height="80%" src="<?php echo plugins_url( '../css/screenshot-20.png', __FILE__  ); ?>" alt="loading...">
                <!-- Loading Ends -->
            </p>
        </div>


        <div id='step_10'>
            <h3> Step 10 : </h3>
            <p>
                <b>1.</b> This is your service account Email address, share your Google sheets with this address. 
                <br><br>
                <b>2.</b> For Remove click <code> <b> Remove Credentials</b></code>  button.
                <br><br>
                <b>3.</b> If everything is Okay this <code><b> <span class="dashicons dashicons-yes"></span></b></code> will show.
                <br><br>
                <b>4.</b> If you face any Error Regarding this Plugin, See the Log. This Log is Specific to this Plugin.
                <br><br>
                <!-- Loading Starts -->
                <img  style="display: block; margin: 0 auto; "  width="80%" height="80%" src="<?php echo plugins_url( '../css/screenshot-21.png', __FILE__  ); ?>" alt="loading...">
                <!-- Loading Ends -->
                <br><br>
                <b>1.</b> Go to your Google Spreadsheet  &  Click  on the <code> <b> Share</b></code>  button.
                <br>
                <b>2.</b> Place your service account email address here.
                <br>
                <b>3.</b> Click to the <code> <b> Share</b></code> button
                <br><br>
                <!-- Loading Starts -->
                <img  style="display: block; margin: 0 auto; "  width="80%" height="80%" src="<?php echo plugins_url( '../css/screenshot-22.png', __FILE__  ); ?>" alt="loading...">
                <!-- Loading Ends -->
            </p>

            <br><b>1.</b>  You can also see  the below <a href='https://youtu.be/ylUE1t3bkqs' target='_blank' > video, for the video tutorial</a>.<br>
            <!-- youtube starts -->
            <iframe  style="display: block; margin: 0 auto; " width="560" height="315" src="https://www.youtube.com/embed/ylUE1t3bkqs" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <!-- youtube ends -->
           <br>
           <b> Note : If you fail then Try again. Please don't skip any step. try from PC. if you fail again  <a  href=" <?php echo admin_url( 'admin.php?page=wpgsi-contact' ); ?> "> Create a Support ticket </a> from the Plugin Contact Us menu.  - Best Regards </b>
        </div>
    </div>
			
				
</div>