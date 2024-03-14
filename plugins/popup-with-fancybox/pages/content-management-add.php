<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php if ( ! empty( $_POST ) && ! wp_verify_nonce( $_REQUEST['wp_create_nonce'], 'content-management-add-nonce' ) )  { die('<p>Security check failed.</p>'); } ?>
<div class="wrap">
<?php
$Popupwfb_errors = array();
$Popupwfb_success = '';
$Popupwfb_error_found = FALSE;

// Preset the form fields
$form = array(
	'Popupwfb_width' => '',
	'Popupwfb_timeout' => '',
	'Popupwfb_title' => '',
	'Popupwfb_content' => '',
	'Popupwfb_group' => '',
	'Popupwfb_status' => '',
	'Popupwfb_expiration' => '',
	'Popupwfb_starttime' => '',
	'Popupwfb_id' => ''
);

// Form submitted, check the data
if (isset($_POST['Popupwfb_form_submit']) && $_POST['Popupwfb_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('Popupwfb_form_add');
	
	$form['Popupwfb_width'] = isset($_POST['Popupwfb_width']) ? sanitize_text_field($_POST['Popupwfb_width']) : '';
	if ($form['Popupwfb_width'] == '')
	{
		$Popupwfb_errors[] = __('Please enter the popup window width, only number.', 'popup-with-fancybox');
		$Popupwfb_error_found = TRUE;
	}
	if(!is_numeric($form['Popupwfb_width'])) { $form['Popupwfb_width'] = 500; }
	
	$form['Popupwfb_timeout'] = isset($_POST['Popupwfb_timeout']) ? sanitize_text_field($_POST['Popupwfb_timeout']) : '';
	if ($form['Popupwfb_timeout'] == '')
	{
		$Popupwfb_errors[] = __('Please enter popup timeout, only number.', 'popup-with-fancybox');
		$Popupwfb_error_found = TRUE;
	}
	if(!is_numeric($form['Popupwfb_timeout'])) { $form['Popupwfb_timeout'] = 3000; }

	$form['Popupwfb_title'] = isset($_POST['Popupwfb_title']) ? sanitize_text_field($_POST['Popupwfb_title']) : '';
	if ($form['Popupwfb_title'] == '')
	{
		$Popupwfb_errors[] = __('Please enter popup title.', 'popup-with-fancybox');
		$Popupwfb_error_found = TRUE;
	}
	
	$form['Popupwfb_content'] = isset($_POST['Popupwfb_content']) ? wp_filter_post_kses($_POST['Popupwfb_content']) : '';
	if ($form['Popupwfb_content'] == '')
	{
		$Popupwfb_errors[] = __('Please enter popup message.', 'popup-with-fancybox');
		$Popupwfb_error_found = TRUE;
	}
	
	$form['Popupwfb_group'] = isset($_POST['Popupwfb_group']) ? sanitize_text_field($_POST['Popupwfb_group']) : '';
	if ($form['Popupwfb_group'] == '')
	{
		$Popupwfb_errors[] = __('Please select available group for your popup message.', 'popup-with-fancybox');
		$Popupwfb_error_found = TRUE;
	}
	
	$form['Popupwfb_status'] = isset($_POST['Popupwfb_status']) ? sanitize_text_field($_POST['Popupwfb_status']) : '';
	if ($form['Popupwfb_status'] == '')
	{
		$Popupwfb_errors[] = __('Please select popup status.', 'popup-with-fancybox');
		$Popupwfb_error_found = TRUE;
	}
	if($form['Popupwfb_status'] != "YES" && $form['Popupwfb_status'] != "NO")
	{
		$form['Popupwfb_status'] = "YES";
	}
	
	$form['Popupwfb_expiration'] = isset($_POST['Popupwfb_expiration']) ? sanitize_text_field($_POST['Popupwfb_expiration']) : '9999-12-31';
	if (!preg_match("/\d{4}\-\d{2}-\d{2}/", $form['Popupwfb_expiration'])) 
	{
		$Popupwfb_errors[] = __('Please enter popup display start date in this format YYYY-MM-DD.', 'popup-with-fancybox');
		$Popupwfb_error_found = TRUE;
	}
	
	$form['Popupwfb_starttime'] = isset($_POST['Popupwfb_starttime']) ? sanitize_text_field($_POST['Popupwfb_starttime']) : '0000-00-00';
	if (!preg_match("/\d{4}\-\d{2}-\d{2}/", $form['Popupwfb_starttime'])) 
	{
		$Popupwfb_errors[] = __('Please enter the expiration date in this format YYYY-MM-DD.', 'popup-with-fancybox');
		$Popupwfb_error_found = TRUE;
	}	

	//	No errors found, we can add this Group to the table
	if ($Popupwfb_error_found == FALSE)
	{
		$sql = $wpdb->prepare(
			"INSERT INTO `".Popupwfb_Table."`
			(`Popupwfb_width`, `Popupwfb_timeout`, `Popupwfb_title`, `Popupwfb_content`, `Popupwfb_group`, `Popupwfb_status`, `Popupwfb_expiration`, `Popupwfb_starttime`)
			VALUES(%s, %s, %s, %s, %s, %s, %s, %s)",
			array($form['Popupwfb_width'], $form['Popupwfb_timeout'], $form['Popupwfb_title'], 
			$form['Popupwfb_content'], $form['Popupwfb_group'], $form['Popupwfb_status'], $form['Popupwfb_expiration'], $form['Popupwfb_starttime'])
		);
		$wpdb->query($sql);
		
		$Popupwfb_success = __('New details was successfully added.', 'popup-with-fancybox');
		
		// Reset the form fields
		$form = array(
			'Popupwfb_width' => '',
			'Popupwfb_timeout' => '',
			'Popupwfb_title' => '',
			'Popupwfb_content' => '',
			'Popupwfb_group' => '',
			'Popupwfb_status' => '',
			'Popupwfb_expiration' => '',
			'Popupwfb_starttime' => '',
			'Popupwfb_id' => ''
		);
	}
}

if ($Popupwfb_error_found == TRUE && isset($Popupwfb_errors[0]) == TRUE)
{
	?>
	<div class="error fade">
		<p><strong><?php echo $Popupwfb_errors[0]; ?></strong></p>
	</div>
	<?php
}
if ($Popupwfb_error_found == FALSE && strlen($Popupwfb_success) > 0)
{
	?>
	  <div class="updated fade">
		<p><strong><?php echo $Popupwfb_success; ?> <a href="<?php echo POPUPWFB_ADMIN_URL; ?>"><?php _e('Click here', 'popup-with-fancybox'); ?></a> 
			<?php _e('to view the details', 'popup-with-fancybox'); ?></strong></p>
	  </div>
	  <?php
	}
?>
<div class="form-wrap">
	<div id="icon-edit" class="icon32 icon32-posts-post"><br></div>
	<h2><?php _e('Popup with fancybox', 'popup-with-fancybox'); ?></h2>
	<form name="Popupwfb_form" method="post" action="#" onsubmit="return _Popupwfb_submit()"  >
      <h3><?php _e('Add details', 'popup-with-fancybox'); ?></h3>
      
		<label for="tag-a"><?php _e('Popup width', 'popup-with-fancybox'); ?></label>
		<input name="Popupwfb_width" type="text" id="Popupwfb_width" value="500" size="20" maxlength="4" />
		<p><?php _e('Enter your popup window width. (Ex: 500)', 'popup-with-fancybox'); ?></p>
		
		<label for="tag-a"><?php _e('Popup timeout', 'popup-with-fancybox'); ?></label>
		<input name="Popupwfb_timeout" type="text" id="Popupwfb_timeout" value="3000" size="20" maxlength="5" />
		<p><?php _e('Enter your popup window timeout in millisecond. (Ex: 3000)', 'popup-with-fancybox'); ?></p>
		
		<label for="tag-a"><?php _e('Popup title', 'popup-with-fancybox'); ?></label>
		<input name="Popupwfb_title" type="text" id="Popupwfb_title" value="" size="50" maxlength="250" />
		<p><?php _e('Enter your popup title.', 'popup-with-fancybox'); ?></p>
	  
	  	<label for="tag-a"><?php _e('Popup message', 'popup-with-fancybox'); ?></label>
		<?php wp_editor("", "Popupwfb_content"); ?>
		<p><?php _e('Enter your popup message.', 'popup-with-fancybox'); ?></p>
		
		<label for="tag-a"><?php _e('Popup display', 'popup-with-fancybox'); ?></label>
		<select name="Popupwfb_status" id="Popupwfb_status">
			<option value='YES' selected="selected">Yes</option>
			<option value='NO'>No</option>
		</select>
		<p><?php _e('Please select your popup display status. (Select NO if you want to hide the popup in front end)', 'popup-with-fancybox'); ?></p>
		
		<label for="tag-a"><?php _e('Popup group', 'popup-with-fancybox'); ?></label>
	    <select name="Popupwfb_group" id="Popupwfb_group">
		<option value=''>Select</option>
		<?php
		$sSql = "SELECT distinct(Popupwfb_group) as Popupwfb_group FROM `".Popupwfb_Table."` order by Popupwfb_group";
		$myDistinctData = array();
		$arrDistinctDatas = array();
		$myDistinctData = $wpdb->get_results($sSql, ARRAY_A);
		$i = 1;
		foreach ($myDistinctData as $DistinctData)
		{
			$arrDistinctData[$i]["Popupwfb_group"] = strtoupper($DistinctData['Popupwfb_group']);
			$i = $i+1;
		}
		for($j=$i; $j<$i+10; $j++)
		{
			$arrDistinctData[$j]["Popupwfb_group"] = "GROUP" . $j;
		}
		//$arrDistinctDatas = array_unique($arrDistinctData, SORT_REGULAR);
		foreach ($arrDistinctData as $arrDistinct)
		{
			?><option value='<?php echo strtoupper($arrDistinct["Popupwfb_group"]); ?>'><?php echo strtoupper($arrDistinct["Popupwfb_group"]); ?></option><?php
		}
		?>
		</select>
		<p><?php _e('Please select available group for your popup message.', 'popup-with-fancybox'); ?></p>
		
		<label for="tag-title"><?php _e('Start date', 'popup-with-fancybox'); ?></label>
		<input name="Popupwfb_starttime" type="text" id="Popupwfb_starttime" value="0000-00-00" maxlength="10" />
		<p><?php _e('Please enter popup display start date in this format YYYY-MM-DD <br /> 0000-00-00 : Is equal to no min date.', 'popup-with-fancybox'); ?></p>
		
		<label for="tag-title"><?php _e('Expiration date', 'popup-with-fancybox'); ?></label>
		<input name="Popupwfb_expiration" type="text" id="Popupwfb_expiration" value="9999-12-31" maxlength="10" />
		<p><?php _e('Please enter the expiration date in this format YYYY-MM-DD <br /> 9999-12-31 : Is equal to no expire.', 'popup-with-fancybox'); ?></p>
	  
      <input name="Popupwfb_id" id="Popupwfb_id" type="hidden" value="">
      <input type="hidden" name="Popupwfb_form_submit" value="yes"/>
      <p class="submit">
        <input name="publish" lang="publish" class="button" value="<?php _e('Submit', 'popup-with-fancybox'); ?>" type="submit" />
        <input name="publish" lang="publish" class="button" onclick="_Popupwfb_redirect()" value="<?php _e('Cancel', 'popup-with-fancybox'); ?>" type="button" />
        <input name="Help" lang="publish" class="button" onclick="_Popupwfb_help()" value="<?php _e('Help', 'popup-with-fancybox'); ?>" type="button" />
      </p>
	  <?php wp_nonce_field('Popupwfb_form_add'); ?>
	  <input type="hidden" name="wp_create_nonce" id="wp_create_nonce" value="<?php echo wp_create_nonce( 'content-management-add-nonce' ); ?>"/>
    </form>
</div>
<p class="description">
	<?php _e('Check official website for more information', 'popup-with-fancybox'); ?>
	<a target="_blank" href="<?php echo Popupwfb_FAV; ?>"><?php _e('click here', 'popup-with-fancybox'); ?></a>
</p>
</div>