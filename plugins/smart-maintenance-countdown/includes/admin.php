<?php
//add_menu_page( page_title, menu_title, capability,  menu_slug,  function, icon_url, position );
//ADDING MENU
add_action('admin_menu', 'SmartMaintenance');

wp_enqueue_script('jquery');

function SmartMaintenance()
{
//create custom top-level menu
    add_menu_page('Smart Maintenance & Countdown',
        'SMaintenance',
        'administrator',
        'SmartMaintenance_option',
        'SmartMaintenance_option_page',
        plugins_url('../images/logo.png', __FILE__),
        100
    );
}

//ADDING ADMIN VALUE SAVE WINDOW
function SmartMaintenance_option_page()
{
    global $wp_roles;
    $roles = $wp_roles->get_names();

    $smSettings['pageTitle'] = "Coming Soon Page";
    $smSettings['companyName'] = "My Company";
    $smSettings['message'] = "Coming Soon";
    $smSettings['template'] = "Under Maintenance";
    $smSettings['year'] = "2014";
    $smSettings['month'] = "01";
    $smSettings['day'] = "01";
    $smSettings['hour'] = "01";
    $smSettings['minute'] = "00";
    $smSettings['status'] = "1";
    $smSettings['contactEmail'] = "";
    $smSettings['contactNumber'] = "";
    $smSettings['facebookLink'] = "";
    $smSettings['twitterLink'] = "";
    $smSettings['googleLink'] = "";
//DEFAULT VARIABLES
    $templates = array();
    $templates[0]="NoCountDown";
    $templates[1]="CountDown_1";
    $templates[2]="CountDown_2";
    $months = array();
    $months[0]="January";
    $months[1]="February";
    $months[2]="March";
    $months[3]="April";
    $months[4]="May";
    $months[5]="June";
    $months[6]="July";
    $months[7]="August";
    $months[8]="September";
    $months[9]="October";
    $months[10]="November";
    $months[11]="December";
    $errorMessage = "";
//DEFAULT VARIABLES
    if (isset($_POST['SaveSettings'])) {

        $smSettings['pageTitle'] = trim($_POST['pageTitle']);
        $smSettings['companyName'] = trim($_POST['companyName']);
        $smSettings['message'] = trim($_POST['message']);
        $smSettings['template'] = trim($_POST['template']);
        $smSettings['year'] = trim($_POST['year']);
        $smSettings['month'] = trim($_POST['month']);
        $smSettings['day'] = trim($_POST['day']);
        $smSettings['hour'] = trim($_POST['hour']);
        $smSettings['minute'] = trim($_POST['minute']);
        $smSettings['status'] = trim($_POST['status']);
        $smSettings['contactEmail'] = trim($_POST['contactEmail']);
        $smSettings['contactNumber'] = trim($_POST['contactNumber']);
        $smSettings['facebookLink'] = trim($_POST['facebookLink']);
        $smSettings['twitterLink'] = trim($_POST['twitterLink']);
        $smSettings['googleLink'] = trim($_POST['googleLink']);


/*        $timestamp = mktime( $smSettings['hour'], $smSettings['minute'], 0, $months[$smSettings['month']], $smSettings['day'], $smSettings['year'] );

        var_dump($timestamp);*/

            //$errorMessage="Invalid Date Time";


        foreach($roles as $temp){
            if($temp != "Administrator"){
                if (isset($_POST[$temp])) {
                    $smSettings[$temp] = $_POST[$temp];
                }
            }
        }

        $chk = get_option('SmartMaintenance_settings');

        if($errorMessage ==""){
            if($chk == false){
                add_option('SmartMaintenance_settings', $smSettings);
            }
            else{
                update_option('SmartMaintenance_settings', $smSettings);
            }
        }
    }
    $chk = get_option('SmartMaintenance_settings');

    if($chk == true){
        $smSettings['pageTitle'] = $chk['pageTitle'];
        $smSettings['companyName'] = $chk['companyName'];
        $smSettings['message'] = $chk['message'];
        $smSettings['template'] = $chk['template'];
        $smSettings['year'] = $chk['year'];
        $smSettings['month'] = $chk['month'];
        $smSettings['day'] = $chk['day'];
        $smSettings['hour'] = $chk['hour'];
        $smSettings['minute'] = $chk['minute'];
        $smSettings['status'] = $chk['status'];
        $smSettings['contactEmail'] = $chk['contactEmail'];
        $smSettings['contactNumber'] = $chk['contactNumber'];
        $smSettings['facebookLink'] = $chk['facebookLink'];
        $smSettings['twitterLink'] = $chk['twitterLink'];
        $smSettings['googleLink'] = $chk['googleLink'];

        foreach($roles as $temp){
            if($temp != "Administrator"){
                if (isset($chk['status'])) {
                    $smSettings[$temp] = $chk[$temp];
                }
            }
        }
    }
    if($errorMessage ==""){
        echo $errorMessage."<br />";
    }
    //var_dump($smSettings);
    //var_dump(TEMPLATE_PATH);
    //var_dump(TEMPLATE_URL);
    /*var_dump(UnderMaintanence);
    var_dump(plugins_url('templates/UnderMaintanence/style.css', __FILE__));*/
    $adminBody = '
    <form name="settings" action="" method="post">
    <table border="0" cellpadding="2" cellspacing="0">
	<tr>
        <td colspan="7" style="text-align: center;"><h1>Smart Maintenance & Countdown</h1></td>
    </tr>
    <tr>
    	<td width="130px">Status</td>
        <td>:</td>
        <td colspan="5">
        	<select name="status" id="status">';
    if ($smSettings['status'] == "1") {
        $adminBody = $adminBody . '<option value="1" selected="selected">ON</option>
            	<option value="0">OFF</option>';
    } else {
        $adminBody = $adminBody . '<option value="1">ON</option>
            	<option value="0" selected="selected">OFF</option>';
    }
    $adminBody = $adminBody . '</select>
        </td>
    </tr>
	<tr>
    	<td>Page Title</td>
        <td>:</td>
        <td colspan="5"><input type="text" name="pageTitle" id="pageTitle" value=\'' . $smSettings['pageTitle'] . '\' /></td>
    </tr>
	<tr>
    	<td>Company Name</td>
        <td>:</td>
        <td colspan="5"><input type="text" name="companyName" id="companyName" value=\'' . $smSettings['companyName'] . '\' /></td>
    </tr>
	<tr>
    	<td>Message</td>
        <td>:</td>
        <td colspan="5"><input type="text" name="message" id="message" value=\'' . $smSettings['message'] . '\' /></td>
    </tr>
	<tr>
    	<td>Template</td>
        <td>:</td>
        <td colspan="5">
        	<select name="template" id="template">';
    foreach($templates as $temp){
        if ($smSettings['template'] == $temp) {
            $adminBody = $adminBody . '<option value="'.$temp.'" selected="selected">'.$temp.'</option>';
        } else {
            $adminBody = $adminBody . '<option value="'.$temp.'">'.$temp.'</option>';
        }
    }
    $adminBody = $adminBody . '</select>
        </td>
    </tr>
	<tr>
    	<td>Roles To Deny</td>
        <td>:</td>
        <td colspan="5">';

    foreach($roles as $temp){
        if($temp != "Administrator"){
            if ($smSettings[$temp] == $temp) {
                $adminBody = $adminBody . '<input type="checkbox" name="'.$temp.'" id="'.$temp.'" value="'.$temp.'" checked="checked" />'.$temp.'<br />';
            } else {
                $adminBody = $adminBody . '<input type="checkbox" name="'.$temp.'" id="'.$temp.'" value="'.$temp.'" />'.$temp.'<br />';
            }
        }
    }
    $adminBody = $adminBody . '</select>
        </td>
    </tr>
	<tr>
    	<td></td>
        <td></td>
        <td>Year</td>
        <td>Month</td>
        <td>Day</td>
        <td>Hour</td>
        <td>Minute</td>
    </tr>
	<tr>
    	<td>Comeback Time</td>
        <td>:</td>
        <td><select name="year" id="year">';
    for($temp=date("Y");$temp<date("Y")+2;$temp++){
        if ($smSettings['year'] == $temp) {
            $adminBody = $adminBody . '<option value="'.$temp.'" selected="selected">'.$temp.'</option>';
        } else {
            $adminBody = $adminBody . '<option value="'.$temp.'">'.$temp.'</option>';
        }
    }
    $adminBody = $adminBody . '</select></td>
        <td><select name="month" id="month">';
    foreach($months as $temp){
        if ($smSettings['month'] == $temp) {
            $adminBody = $adminBody . '<option value="'.$temp.'" selected="selected">'.$temp.'</option>';
        } else {
            $adminBody = $adminBody . '<option value="'.$temp.'">'.$temp.'</option>';
        }
    }
    $adminBody = $adminBody . '</select></td>
        <td><select name="day" id="day">';
    for($temp=1;$temp<32;$temp++){
        if ($smSettings['day'] == $temp) {
            $adminBody = $adminBody . '<option value="'.$temp.'" selected="selected">'.$temp.'</option>';
        } else {
            $adminBody = $adminBody . '<option value="'.$temp.'">'.$temp.'</option>';
        }
    }
    $adminBody = $adminBody . '</select></td>
        <td><select name="hour" id="hour">';
    for($temp=0;$temp<24;$temp++){
        if ($smSettings['hour'] == $temp) {
            $adminBody = $adminBody . '<option value="'.$temp.'" selected="selected">'.$temp.'</option>';
        } else {
            $adminBody = $adminBody . '<option value="'.$temp.'">'.$temp.'</option>';
        }
    }
    $adminBody = $adminBody . '</select></td>
        <td><select name="minute" id="minute">';
    for($temp=0;$temp<61;$temp++){
        if ($smSettings['minute'] == $temp) {
            $adminBody = $adminBody . '<option value="'.$temp.'" selected="selected">'.$temp.'</option>';
        } else {
            $adminBody = $adminBody . '<option value="'.$temp.'">'.$temp.'</option>';
        }
    }
    $adminBody = $adminBody . '</select></td>
    </tr>
	<tr>
    	<td>Contact Email</td>
        <td>:</td>
        <td colspan="5"><input type="text" name="contactEmail" id="contactEmail" value=\'' . $smSettings['contactEmail'] . '\' /></td>
    </tr>
	<tr>
    	<td>Contact Number</td>
        <td>:</td>
        <td colspan="5"><input type="text" name="contactNumber" id="contactNumber" value=\'' . $smSettings['contactNumber'] . '\' /></td>
    </tr>
	<tr>
    	<td>Facebook Link</td>
        <td>:</td>
        <td colspan="5"><input type="text" name="facebookLink" id="facebookLink" value=\'' . $smSettings['facebookLink'] . '\' /></td>
    </tr>
	<tr>
    	<td>Twitter Link</td>
        <td>:</td>
        <td colspan="5"><input type="text" name="twitterLink" id="twitterLink" value=\'' . $smSettings['twitterLink'] . '\' /></td>
    </tr>
	<tr>
    	<td>Google+ Link</td>
        <td>:</td>
        <td colspan="5"><input type="text" name="googleLink" id="googleLink" value=\'' . $smSettings['googleLink'] . '\' /></td>
    </tr>
    <tr>
        <td colspan="7" align="center">
			<input type="SUBMIT" id="SaveSettings" name="SaveSettings" value="Save Settings" />
        </td>
    </tr>
    </table>
</form>
</div>';
    echo $adminBody;
}

?>