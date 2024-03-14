<?php  if ( ! defined( 'ABSPATH' ) ) exit;

    global $wpdp_pro, $wpdp_url, $wpdp_premium_link, $wpdp_android_settings;
	if ( !current_user_can( 'administrator' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.', 'wp-datepicker' ) );
	}
// Save the field values

    if(isset($_GET['wpdp_delete_option']) && $wpdp_pro){

        delete_option($_GET['wpdp_delete_option']);

        ?>

            <script type="text/javascript" language="JavaScript">
                history.pushState({}, jQuery('title').text(), '<?php echo admin_url('/options-general.php?page=wp_dp') ?>');
            </script>

        <?php
    }


	if ( isset( $_POST['wpdp_fields_submitted'] ) && $_POST['wpdp_fields_submitted'] == 'submitted' && false) {
		
		
						
			
			if ( 
				! isset( $_POST['wpdp_nonce_action_field'] ) 
				|| ! wp_verify_nonce( $_POST['wpdp_nonce_action_field'], 'wpdp_nonce_action' ) 
			) {
			
			   print __('Sorry, your nonce did not verify.', 'wp-datepicker');
			   exit;
			
			} else {
			
			   // process form data
						
				
				update_option( 'wp_datepicker_months', 0);
				
				if($wpdp_pro){
					foreach ( $_POST as $key => $value ) {		
						if(is_array($value)){
							$value = array_map( 'esc_attr', $value );
							//pree($value);
							update_option( sanitize_wpdp_data($key), ($value) );
						}else{
							if ( get_option( $key ) != $value ) {
								update_option( sanitize_wpdp_data($key), sanitize_wpdp_data($value) );
							} else {
								add_option( sanitize_wpdp_data($key), sanitize_wpdp_data($value), '', 'no' );
							}
						}
					}
				}else{
				
					
					update_option( 'wp_datepicker', sanitize_wpdp_data($_POST['wp_datepicker']));
					update_option( 'wp_datepicker_weekends', sanitize_wpdp_data($_POST['wp_datepicker_weekends']));
					update_option( 'wp_datepicker_autocomplete', sanitize_wpdp_data($_POST['wp_datepicker_autocomplete']));
					update_option( 'wp_datepicker_beforeShowDay', sanitize_wpdp_data($_POST['wp_datepicker_beforeShowDay']));
					
					update_option( 'wp_datepicker_months', sanitize_wpdp_data($_POST['wp_datepicker_months']));
					update_option( 'wp_datepicker_wpadmin', sanitize_wpdp_data($_POST['wp_datepicker_wpadmin']));
					
					
					update_option( 'wp_datepicker_language', sanitize_wpdp_data($_POST['wp_datepicker_language']));
	 				update_option( 'wp_datepicker_readonly', sanitize_wpdp_data($_POST['wp_datepicker_readonly']));
				}
				
			}
			
		
		
		
	}


    if ( isset( $_POST['wpdp_fields_submitted'] ) && $_POST['wpdp_fields_submitted'] == 'submitted' ) {

        if (
            ! isset( $_POST['wpdp_nonce_action_field'] )
            || ! wp_verify_nonce( $_POST['wpdp_nonce_action_field'], 'wpdp_nonce_action' )
        ) {

            print __('Sorry, your nonce did not verify.', 'wp-datepicker');
            exit;

        } else {

            // process form data

            if(isset($_POST['wpdp'])){
                $wpdp_data_post = sanitize_wpdp_data($_POST['wpdp']);

                $option_name = current(array_keys($wpdp_data_post));
                $options_data_array = current($wpdp_data_post);

                if(strlen($option_name)){

                    update_option( esc_attr($option_name), $options_data_array);

                }

            }


        }


    }
	
    if ( isset( $_POST['wpdp_global_settings_tab'] ) && $_POST['wpdp_global_settings_tab'] == 'submitted' ) {

        if (
            ! isset( $_POST['wpdp_nonce_action_field'] )
            || ! wp_verify_nonce( $_POST['wpdp_nonce_action_field'], 'wpdp_nonce_action' )
        ) {

            print __('Sorry, your nonce did not verify.', 'wp-datepicker');
            exit;

        } else {

            // process form data

            if(isset($_POST['wpdp_global_settings'])){
                $wpdp_data_post = sanitize_wpdp_data($_POST['wpdp_global_settings']);

                update_option( 'wpdp_global_settings', $wpdp_data_post);

            }


        }


    }	
	
	
	

    global $wpdp_options_data, $current_option, $wpdp_ajax_request;

    if(isset($_POST['wpdp_get_selected_datepicker'])){

        $current_option = sanitize_wpdp_data($_POST['wpdp_get_selected_datepicker']);
        $wpdp_ajax_request = true;


    }else{

        $current_option = wpdp_get_current_option_name();
        $wpdp_ajax_request = false;

    }


    $wpdp_options_data = get_option($current_option, array());
    $wpdp_options_data_copy = $wpdp_options_data;

//    pree($wpdp_options_data);



    $wp_datepicker = false;
	$wp_datepicker_language = false;
	$wp_datepicker_weekends = false;
	$wp_datepicker_autocomplete = true;
	$wp_datepicker_beforeShowDay = false;
	$wp_datepicker_months = false;
	$wp_datepicker_wpadmin = false;
	$wp_datepicker_readonly = true;

    if(array_key_exists('wpdp_options', $wpdp_options_data_copy)){

        unset($wpdp_options_data_copy['wpdp_options']);

    }



	extract($wpdp_options_data_copy);

    $wpdp_selectors = $wp_datepicker;
    $wp_datepicker_language = wpdp_slashes($wp_datepicker_language);



    $wpdb_string = wpdp_slashes($wpdp_selectors);
	
	$wpdb_arr = explode(',', $wpdb_string);
	
	$wpdb_arr = array_filter($wpdb_arr, 'strlen');
	
	if(empty($wpdb_arr)){
		$wpdb_arr = array('.datepicker');
	}
	
	$attrib = array('type'); //array('accept', 'align', 'right', 'top', 'middle', 'bottom', 'alt ', 'autocomplete ', 'autofocus', 'checked', 'disabled', 'max', 'maxlength', 'min', 'multiple', 'name', 'pattern', 'placeholder', 'readonly', 'required', 'size', 'src', 'step', 'type', 'value', 'width');
	$inputs = array(
		'class' => array('symbol' => '.',),
		'id' => array('symbol' => '#',),
		'input' => array(
			'symbol' => 'input',
			'type' => array(
			'button', 'checkboxcolor', 'date', 'datetime', 'datetime-local', 'email', 'file', 'hidden', 'image', 'month', 'number', 'password', 'radio', 'range', 'reset', 'search', 'submit', 'tel', 'text', 'time', 'url', 'week')
			),
		'textarea' => array('symbol' => 'textarea',),
		'select' => array('symbol' => 'select',),		
	);
	
	$wp_datepicker_alive_scripts = array_key_exists('wp_datepicker_alive_scripts', $wpdp_options_data) ? $wpdp_options_data['wp_datepicker_alive_scripts'] : 'no';
	
	
?>	
<div class="wrap wpdp" id="wpdp-<?php echo $wpdp_pro?'pro':'free'; ?>">


	
    
  <div class="head_area">
	<h2 class="plugin-heading"><span class="dashicons dashicons-welcome-widgets-menus"></span><?php echo 'WP Datepicker '.'('.$wpdp_data['Version'].($wpdp_pro?') '.__('Pro', 'wp-datepicker').'':')'); ?> - <?php _e('Settings', 'wp-datepicker'); ?></h2>
      

      <h2 class="nav-tab-wrapper">
          <a class="nav-tab nav-tab-active"><?php _e("Datepicker","wp-datepicker"); ?> <i class="fas fa-paint-brush"></i></a>
          <a class="nav-tab"><?php _e("Date Range","wp-datepicker"); ?> <i class="far fa-calendar-alt"></i></a>
          <a class="nav-tab"><?php _e("Speed Optimization","wp-datepicker"); ?> <i class="fas fa-tachometer-alt"></i></a>
          <a class="nav-tab"><?php _e("Add-ons","wp-datepicker"); ?> <i class="fas fa-plug"></i></a>
          <a class="nav-tab"><?php _e("Developer","wp-datepicker"); ?> <i class="fas fa-code"></i></a>
          <a class="nav-tab"><?php _e("Global Settings","wp-datepicker"); ?> <i class="fa-solid fa-gears"></i></a>
          <a class="nav-tab" data-tab="help" data-type="free"><i class="far fa-question-circle"></i>&nbsp;<?php _e("Help", 'wp-datepicker'); ?></a>
          
          

          <div class="wpdp_android">
		  <?php $wpdp_android_settings->ab_io_display($wpdp_url); ?>
	      </div>
      </h2>
    
    </div>
<?php if(!$wpdp_pro): ?>
<a title="<?php _e('Click here to download pro version', 'wp-datepicker'); ?>" style="background-color: #25bcf0;    color: #fff !important;    padding: 2px 30px;    cursor: pointer;    text-decoration: none;    font-weight: bold;    right: 0;    position: absolute;    top: 0;    box-shadow: 1px 1px #ddd;" href="https://shop.androidbubbles.com/download/" target="_blank"><?php _e('Already a Pro Member?', 'wp-datepicker'); ?></a>
<?php endif; ?>
<div class="nav-tab-content">
<form method="post" action="" id="wpdp_form">
<?php wp_nonce_field( 'wpdp_nonce_action', 'wpdp_nonce_action_field' ); ?>
<input type="hidden" name="wpdp_fields_submitted" value="submitted" />


<div class="alert alert-success fade in alert-dismissible show" style="margin-top:18px; display: none">
   <strong><?php _e( 'Success!', 'wp-datepicker' ); ?></strong> <?php _e( 'Settings are updated successfully.', 'wp-datepicker' ); ?>
</div>

<div class="wpdp_settings">

    <?php if($wpdp_pro && function_exists('wpdb_pro_settings_list')){ wpdb_pro_settings_list($current_option); } ?>


    <div class="wpdp_settings_fields">
<?php if($wpdp_pro): ?>
<a class="delete_wpdp" href="<?php echo admin_url('/options-general.php?page=wp_dp&wpdp_delete_option='.$current_option) ?>" style="text-decoration: none;"><i class="far fa-trash-alt"></i> <?php _e( 'Delete', 'wp-datepicker' ); ?></a>
        <?php endif; ?>

<span title="<?php _e('Click here to keep scripts alive if your forms are making changes in fields', 'wp-datepicker'); ?>" class="alive_wpdp <?php echo ($wp_datepicker_alive_scripts=='yes'?'awake':''); ?>">
    <a href="" data-id="<?php echo $current_option; ?>"><i class="fas fa-robot"></i> <?php _e( 'Alive Scripts', 'wp-datepicker' ); ?></a>
</span>
        
<span class="refresh_wpdp">
    <img src="<?php echo $wpdp_url?>/img/loader.gif" alt="">
    <i class="fa fa-check-circle"></i>
    <a href=""><i class="fa fa-sync-alt"></i> <?php _e( 'Refresh Scripts', 'wp-datepicker' ); ?></a>
</span>



<?php
	if(!empty($wpdb_arr)){
?>
		<a class="wpdp_cg_btn"><?php _e('How it works?', 'wp-datepicker'); ?></a>
		<div class="wpdp_cg">
		<h3><?php _e( 'Code Generator', 'wp-datepicker' ); ?>: <small>(<?php _e( 'Optional', 'wp-datepicker' ); ?>)</small></h3>
<?php
		foreach($wpdb_arr as $vals){
			$label = '';
			$type = substr($vals, 0, 1);
			$type_d = '';
			switch($type){
				case "#":
					$label = $type_d = 'id';
				break;
				case ".":
					$label = $type_d = 'class';
				break;
				case "i":
				case "s":
				case "t":
					$type_d = explode('[', $vals);
					$label = current($type_d);
				break;
			}
?>

        <?php if(!empty($inputs)): ?>
        <div class="wpdp_demo_div">
		<select name="wpdp_sel[]" class="ignore-save" style="width:200px;">
        <?php foreach($inputs as $tag_type => $input): ?>
        	<option style="background-color:#CCC; font-weight:bold;" data-tag="<?php echo $tag_type; ?>" data-type="<?php echo $inputs[$tag_type]['symbol']; ?>" value=""><?php echo $tag_type; ?></option>
            <?php if(!empty($attrib)): ?>
            <?php foreach($attrib as $attr): ?>

            <?php if(!empty($input) && isset($input[$attr])): ?>
            <option style="padding-left:20px;" data-type="<?php echo $attr; ?>" value="<?php echo $attr; ?>" <?php selected( $type, $attr ); ?>><?php echo $attr; ?></option>
            <?php foreach($input as $t => $t_array): ?>
            <?php if(is_array($t_array) && !empty($t_array)): ?>
            <?php foreach($t_array as $tag_elem): ?>
            	<option style="padding-left:40px;" data-tag="<?php echo $tag_type; ?>" data-type="<?php echo $t; ?>" value="<?php echo $tag_elem; ?>" <?php selected( $type, $tag_elem ); ?>><?php echo $tag_elem; ?></option>
            <?php endforeach; ?>
        	<?php endif; ?>
            <?php endforeach; ?>
        	<?php endif; ?>

         	<?php endforeach; ?>
        	<?php endif; ?>
        <?php endforeach; ?>
        </select>
        <?php endif; ?>
        <input name="wpdp_demo_str[]" class="ignore-save" placeholder="" type="text" value="" />
		<input name="wpdp_demo_output[]" class="ignore-save" type="text" value="<?php echo $vals; ?>" style="width:350px" /><small><?php _e('Insert the output text below and glue with comma for next.', 'wp-datepicker'); ?></small>
        </div>
<?php
		}
?><br />
<?php _e('Video Tutorials', 'wp-datepicker'); ?>:<br />
<iframe width="200" height="120" src="https://www.youtube.com/embed/eILaObbYucU" frameborder="0" allowfullscreen></iframe>
<iframe width="200" height="120" src="https://www.youtube.com/embed/c2afBhUPp4w" frameborder="0" allowfullscreen></iframe>
</div>
<?php
	}

?>

<?php
global $wpdp_dir;

//pree($wpdp_options_data);

//pree($wp_datepicker_alive_scripts);
?>




<input type="hidden" id="wp_datepicker_alive_scripts" name="wpdp[<?php echo $current_option ?>][wp_datepicker_alive_scripts]" value="<?php echo $wp_datepicker_alive_scripts; ?>" />

<input type="text" width="100%" value="<?php echo wpdp_slashes($wpdp_selectors); ?>"  name="wpdp[<?php echo $current_option ?>][wp_datepicker]" class="wpdp-useable wpdp_selectors" data-name="[wp_datepicker]" placeholder="<?php _e('Enter', 'wp-datepicker'); ?> id, class, name based and/or type based CSS <?php _e('selector', 'wp-datepicker'); ?>" /><br />
<small>
<?php _e('You can enter multiple selectors as CSV', 'wp-datepicker'); ?> (<?php _e('Comma Separated Values', 'wp-datepicker'); ?>).<br />

e.g. <br />
<span class="wpdp_1">#datepicker</span><br />
or<br />
<span class="wpdp_2">#datepicker, .hasDatepicker, .date-field</span><br />
and<br />
<span class="wpdp_3"><?php _e('Sample', 'wp-datepicker'); ?> HTML: &lt;input type=&quot;text&quot; id=&quot;datepicker&quot; /&gt;</span>
</small>


<br />
<br />

<select name="wpdp[<?php echo $current_option ?>][wp_datepicker_language]" class="wpdp-useable wpdp_selectors" data-name="[wp_datepicker_language]">
<option><?php _e('Select Language', 'wp-datepicker'); ?></option>
<?php
foreach (glob($wpdp_dir."js/i18n/*.js") as $filename) {
    $content = file_get_contents($filename);
	$lines = nl2br($content);
	$lines = explode('<br>', $lines);
	$line = explode(' ', $lines[0]);
	$title = $line[1];

	$code = str_replace(array('datepicker-', '.js'), '', basename($filename));
	$val = $code.'|'.basename($filename);
?>
	<option value="<?php echo $val; ?>" <?php echo ($wp_datepicker_language==$val?'selected="selected"':''); ?>><?php echo $code.' ('.$title.')'; ?></option>
<?php
}
?>
</select>




<div class="wp_datepicker_readyonly">
<label for=""><?php _e( 'Make datepicker field editable or readonly?', 'wp-datepicker' ); ?></label>
<input type="radio" name="wpdp[<?php echo $current_option ?>][wp_datepicker_readonly]" class="wpdp-useable" data-name="[wp_datepicker_readonly]" id="wp_datepicker_readonly_yes" value="1" <?php checked($wp_datepicker_readonly); ?> /><label for="wp_datepicker_readonly_yes"><?php _e('Read-only', 'wp-datepicker'); ?></label>
<input type="radio" name="wpdp[<?php echo $current_option ?>][wp_datepicker_readonly]" class="wpdp-useable" data-name="[wp_datepicker_readonly]" id="wp_datepicker_readonly_no" value="0" <?php checked(!$wp_datepicker_readonly); ?> /><label for="wp_datepicker_readonly_no"><?php _e('Editable', 'wp-datepicker'); ?></label>
</div>


<div class="wp_datepicker_months">
<label for=""><?php _e( 'Weekends?', 'wp-datepicker' ); ?></label>
<input type="radio" name="wpdp[<?php echo $current_option ?>][wp_datepicker_weekends]" class="wpdp-useable" data-name="[wp_datepicker_weekends]" id="wp_datepicker_weekends_yes" value="0" <?php checked(!$wp_datepicker_weekends); ?> /><label for="wp_datepicker_weekends_yes"><?php _e('Enable', 'wp-datepicker'); ?></label>
<input type="radio" name="wpdp[<?php echo $current_option ?>][wp_datepicker_weekends]" class="wpdp-useable" data-name="[wp_datepicker_weekends]" id="wp_datepicker_weekends_no" value="1" <?php checked($wp_datepicker_weekends); ?> /><label for="wp_datepicker_weekends_no"><?php _e('Disable', 'wp-datepicker'); ?></label>
<small><?php echo __( 'Will remove Saturdays & Sundays from date picker.', 'wp-datepicker').' '.__("Some service businesses don't offer weekend service.", 'wp-datepicker' ); ?></small>
</div>

<div class="wp_datepicker_months">
    <label for=""><?php _e( 'Auto Complete?', 'wp-datepicker' ); ?></label>
    <input type="radio" name="wpdp[<?php echo $current_option ?>][wp_datepicker_autocomplete]" class="wpdp-useable" data-name="[wp_datepicker_autocomplete]" id="wp_datepicker_autocomplete_yes" value="1" <?php checked($wp_datepicker_autocomplete); ?> /><label for="wp_datepicker_autocomplete_yes"><?php _e('Enable', 'wp-datepicker'); ?></label>
    <input type="radio" name="wpdp[<?php echo $current_option ?>][wp_datepicker_autocomplete]" class="wpdp-useable" data-name="[wp_datepicker_autocomplete]" id="wp_datepicker_autocomplete_no" value="0" <?php checked(!$wp_datepicker_autocomplete); ?> /><label for="wp_datepicker_autocomplete_no"><?php _e('Disable', 'wp-datepicker'); ?></label>
</div>

<?php if($wpdp_pro){ ?>
<div class="wp_datepicker_months beforeShowDay collapsed">
<label style="width:100%" for="" title="<?php _e( 'Click here for custom scripts', 'wp-datepicker' ); ?>"><i style="position: relative;top: 2px;left: -2px; font-size:18px;" class="fa fa-code" aria-hidden="true"></i> <?php _e( 'Any other requirements with weekdays?', 'wp-datepicker' ); ?></label>
<div class="textarea_div">
<textarea name="wpdp[<?php echo $current_option ?>][wp_datepicker_beforeShowDay]" class="wpdp-useable" data-name="[wp_datepicker_beforeShowDay]" id="wp_datepicker_beforeShowDay" placeholder="<?php _e('Insert your custom code here for beforeShowDay'); ?>"><?php echo $wp_datepicker_beforeShowDay; ?></textarea>
<?php
	$scripts_arr = array(
		array(
		 	'title' => ''.__('Enable first Thursday Only?', 'wp-datepicker').'',
			'guide' => __( 'e.g.', 'wp-datepicker').' '.__('Need to enable first Thursday Only?', 'wp-datepicker').'<a style="float:right;" href="https://www.youtube.com/embed/Qb9O7TUyLek" target="_blank">'.__('Video Tutorial', 'wp-datepicker' ).'</a>'.'
<pre>
function (date) { 
	var day = date.getDay(); 
	return [(day == 4) && date.getDate()<8];  
}
</pre><br />'.__('It will disable every date except first thursday of each month.', 'wp-datepicker').'<br />'.__('Note: It will override weekends functionality.', 'wp-datepicker' )
		),
		array(
			'title' => ''.__('Disable Sunday & Monday?', 'wp-datepicker').'',
			'guide' => __( 'e.g.', 'wp-datepicker').' '.__('Need to disable Sunday & Monday?', 'wp-datepicker').'<a style="float:right;" href="https://www.youtube.com/embed/57Mwqy3vWEk" target="_blank">'.__('Video Tutorial', 'wp-datepicker' ).'</a/>
<pre>
function (date) { 
	var day = date.getDay(); 
	return [(day != 0 && day != 1)]; 
}
</pre><br />'.__('It will disable every Sunday & Monday each month.', 'wp-datepicker').'<br />'.__('Note: It will override weekends functionality.', 'wp-datepicker' )
		),
		array(
			'title' => ''.__('Disable the months July and December?', 'wp-datepicker').'',
			'guide' => __( 'e.g.', 'wp-datepicker').' '.__('Need to disable the months July and December?', 'wp-datepicker').'<a style="float:right;" href="https://www.youtube.com/embed/0s7loonWbuw" target="_blank">'.__('Video Tutorial', 'wp-datepicker' ).'</a>
<pre>
function (date) { 
	var month = date.getMonth(); 
	return [(month != 6) && (month != 11)]; 
}
</pre><br />'.__('It will disable the months July and December.', 'wp-datepicker').'<br />'.__('Note: It will override weekends functionality.', 'wp-datepicker' )
		),
		array(
			'title' => ''.__('Disable specific set of dates?', 'wp-datepicker').'',
			'guide' => __( 'e.g.', 'wp-datepicker').' '.__('Need to disable 25th December for specific years?', 'wp-datepicker').'<a style="float:right;" href="" target="_blank">'.__('Video Tutorial', 'wp-datepicker' ).'</a>
			<pre>
function (date) { 
	var array = ["2020-12-25","2025-12-25","2030-12-25"]; 
	var string = jQuery.datepicker.formatDate("yy-mm-dd", date); 
	return [ array.indexOf(string) == -1 ]; 
}
</pre><br />'.__('It will disable 25th December of specific years.', 'wp-datepicker').'<br />'.__('Note: It will override weekends functionality.', 'wp-datepicker' )
		),
		array(
			'title' => ''.__('Disable Sunday, Monday, Tuesday and also specific dates like', 'wp-datepicker').' 07/04/'.date('Y').' or 12/25/'.date('Y'),
			'guide' => __( 'e.g.', 'wp-datepicker').' '.__('Need to disable specific days and dates together?', 'wp-datepicker').'<a style="float:right;" href="https://wordpress.org/support/topic/disabling-specific-dates-pro-version" target="_blank">'.__('Support Thread', 'wp-datepicker' ).'</a>
			<pre>
function (date) {
	var day = date.getDay();
	var array = ["'.date('Y').'-07-04","'.date('Y').'-12-25"];
	var string = jQuery.datepicker.formatDate("yy-mm-dd", date);
	var sunday = 0;
	var monday = 1;
	var tuesday = 2;
	return [(day != sunday && day != monday && day != tuesday && array.indexOf(string) == -1)];
}</pre><br />'.__('It will disable specific dates and weekdays together with one snippet of code.', 'wp-datepicker').'<br />'.__('Note: It will override weekends functionality.', 'wp-datepicker' )
		),
        array(
            'title' => ''.__('Enable only dates from 14th to 19th December & 21st to 24th December?', 'wp-datepicker').'',
            'guide' => __( 'e.g.', 'wp-datepicker').' '.__('Need to Enable only dates from 14th to 19th December & 21st to 24th December?', 'wp-datepicker').'
 <pre>
 function (date) {
    var month = date.getMonth()+1;
    var year = date.getFullYear();
    var dated = date.getDate();
    return [( month==12 && ((dated>13 && dated < 20 ) || ( dated>20 && dated<=24 )))];
 }
 </pre>
 <br />'.__('It will enable only dates from 14th to 19th December & 21st to 24th December. All other dates will be disabled', 'wp-datepicker').'<br />'.__('Note: It will override weekends functionality.', 'wp-datepicker' )
        ),
		array(
            'title' => ''.__('Disable dates expect 24th Nov to 25th Dec (including Sundays) of Current Year?', 'wp-datepicker').'',
            'guide' => __( 'e.g.', 'wp-datepicker').' '.__('Need to Disable all dates except 24th Nov to 25th Dec?', 'wp-datepicker').'
	<pre>
function (date) {
	var month = date.getMonth()+1;
	var year = date.getFullYear();
	var dated = date.getDate();
	var d = new Date();
	dyear = d.getFullYear();
	
	return [(dyear==year && ((month==11 && dated>=24) || (month==12 && dated <= 25)))];
}
 </pre>
 <br />'.__('It will disable all dates except 24th Nov to 25th Dec (including Sundays).', 'wp-datepicker').'<br />'.__('Note: It will override weekends functionality.', 'wp-datepicker' )
		),
		array(
            'title' => ''.__('Disable dates expect 24th Nov to 25th Dec (excluding Sundays) of Current Year?', 'wp-datepicker').'',
            'guide' => __( 'e.g.', 'wp-datepicker').' '.__('Need to Disable all dates except 24th Nov to 25th Dec?', 'wp-datepicker').'
	<pre>
function (date) {
	var month = date.getMonth()+1;
	var year = date.getFullYear();
	var dated = date.getDate();
	var d = new Date();
	dyear = d.getFullYear();
	
	return [(dyear==year && ((month==11 && dated>=24 && d.getDay()!=7) || (month==12 && dated <= 25 && d.getDay()!=7)))];
}
 </pre>
 <br />'.__('It will disable all dates except 24th Nov to 25th Dec (excluding Sundays).', 'wp-datepicker').'<br />'.__('Note: It will override weekends functionality.', 'wp-datepicker' )
		)
	);
?>
<?php if(!empty($scripts_arr)): ?>
<select name="custom-scripts">
<?php foreach($scripts_arr as $script_key => $script_val): ?>
<option value="<?php echo $script_key; ?>"><?php echo $script_val['title']; ?></option>
<?php endforeach; ?>
</select>
<?php endif; ?>

<span class="code-to-text">&#8624;</span>

<?php if(!empty($scripts_arr)): ?>
<?php foreach($scripts_arr as $script_key => $script_val): //pree($script_val);?>

<small class="custom-scripts script-no-<?php echo $script_key; ?>"><br /><br /><br /><?php echo $script_val['guide']; ?></small>

<?php endforeach; ?>
<?php endif; ?>

</div>
</div>
<?php } ?>

<div class="wp_datepicker_months">
<label><?php _e( 'Need months in full or short?', 'wp-datepicker' ); ?></label>
<input type="radio" name="wpdp[<?php echo $current_option ?>][wp_datepicker_months]" class="wpdp-useable" data-name="[wp_datepicker_months]" id="wp_datepicker_months_yes" value="1" <?php checked($wp_datepicker_months); ?> /><label for="wp_datepicker_months_yes"><?php _e('Short', 'wp-datepicker'); ?></label>
<input type="radio" name="wpdp[<?php echo $current_option ?>][wp_datepicker_months]" class="wpdp-useable" data-name="[wp_datepicker_months]" id="wp_datepicker_months_no" value="0" <?php checked(!$wp_datepicker_months); ?> /><label for="wp_datepicker_months_no"><?php _e('Full', 'wp-datepicker'); ?></label>
<small><?php echo __( 'e.g.', 'wp-datepicker').' Sep '.__('or September?', 'wp-datepicker' ); ?></small>
</div>



<?php if($wpdp_pro && function_exists('wpdp_pro_settings')){ wpdp_pro_settings($current_option); }else{ wpdp_free_settings($current_option);

?>
<?php
} ?>


<!--<p class="submit"><input type="submit" name="Submit" class="button-primary" value="--><?php //_e( 'Save Changes', 'wp-datepicker' ); ?><!--" /></p>-->
</div>
<?php if(!$wpdp_pro): ?>
<div class="wpdp_go_premium">
<a href="<?php echo $wpdp_premium_link; ?>" target="_blank"><img src="<?php echo $wpdp_url.'img/'; ?>go-premium.png" /></a>
</div>
<?php endif; ?>
</div>

    <div class="wpdp_modal">
        <div class="wpdp_modal_content">
           
            <img src="<?php echo $wpdp_url.'img/loader.gif';?>">
        </div>
    </div>

</form>
</div>
<div class="nav-tab-content date_range hide">
    <?php include_once ('date_range.php'); ?>
</div>
<div class="nav-tab-content speed_opt_content hide">
    <?php include_once ('speed_opt_template.php'); ?>
</div>
<div class="nav-tab-content addon-content hide">
<?php $addons = array('wp-hamburger'=>array('ext'=>'png'), 'wp-docs'=>array('ext'=>'gif'), 'woo-installments'=>array('ext'=>'png'), 'inbox'=>array('ext'=>'gif')); ?>
<?php if(!empty($addons)): ?>
<ul>
<?php foreach($addons as $addon=>$details): $name = strtoupper(str_replace('-', ' ', $addon));  ?>
<li><a href="https://wordpress.org/plugins/<?php echo $addon; ?>/" target="_blank"><img src="https://ps.w.org/<?php echo $addon; ?>/assets/icon-256x256.<?php echo $details['ext']; ?>" /><br />
<center><?php echo $name; ?></center></a></li>
<?php endforeach; ?>
</ul>
<?php endif; ?>
</div>


    <div class="nav-tab-content hide wpdp_developer">

        <?php

            $wpdp_developer_options = get_option('wpdp_developer_options', array());
            $wpdp_js_init = (array_key_exists('js_init', $wpdp_developer_options) && $wpdp_developer_options['js_init'] == 'true');

        ?>


        <label>
            <input type="checkbox" name="js_init" value="1" class="wpdp_developer_inputs" <?php echo checked($wpdp_js_init) ?>>
            <?php _e('Regenerate datepicker js file on init (by default on settings update new file created).', 'wp-datepicker') ?>
        </label>

        <h3>
            <?php _e('How to use default date', 'wp-datepicker') ?>:
        </h3>
    
        <code style="right: 0;">
	<?php _e('Default date from input field can be used with two methods, are given below. ', 'wp-datepicker') ?>
    
            <br/>
            <br/>
    
	&lt;input type="text" id="joining_date" data-default="<?php echo date('d-m-Y', time()) ?>" /&gt;
    
    
        </code>

        <h3>
            <?php _e('How to extend datepicker options', 'wp-datepicker') ?>:
        </h3>




<pre>
    <?php _e('Use $instance_id 1, 2, 3 etc. If you have more than one instance.', 'wp-datepicker') ?>


    add_filter('wpdp_extend_options_instance_'.$instance_id, 'wpdp_add_datepicker_option', 10, 2);
    function wpdp_add_datepicker_option($options, $instance_id){
        //$instance_option_default = get_option('wp_datepicker_option-'.$instance_id, array());
        $options['onSelect'] = "
        function(date_str, dp_instance){
            console.log(date_str);
            console.log(dp_instance.currentDay);
            console.log(dp_instance.currentMonth + 1);
            console.log(dp_instance.currentYear);
        }";
        return $options;
    }

</pre>

    
    </div>
    
    <div class="nav-tab-content hide wpdp_global_settings">
<?php
	$wpdp_global_settings = get_option('wpdp_global_settings');
	$wpdp_global_settings = is_array($wpdp_global_settings)?$wpdp_global_settings:array();
	$wp_datepicker_wpadmin = (array_key_exists('wp_datepicker_wpadmin', $wpdp_global_settings) && $wpdp_global_settings['wp_datepicker_wpadmin']==1);
	$wp_datepicker_bootstrap_disabled = (array_key_exists('wp_datepicker_bootstrap_disabled', $wpdp_global_settings) && $wpdp_global_settings['wp_datepicker_bootstrap_disabled']==1);
	
?>
<form method="post" action="" id="wpdp_form">
<?php wp_nonce_field( 'wpdp_nonce_action', 'wpdp_nonce_action_field' ); ?>
<input type="hidden" name="wpdp_global_settings_tab" value="submitted" />

    
<div class="wp_datepicker_wpadmin">
<label for=""><?php _e( 'Enable for backend (wp-admin)?', 'wp-datepicker' ); ?></label>
<input type="radio" name="wpdp_global_settings[wp_datepicker_wpadmin]" class="wpdp-useable" data-name="[wp_datepicker_wpadmin]" id="wp_datepicker_wpadmin_yes" value="1" <?php checked($wp_datepicker_wpadmin); ?> /><label for="wp_datepicker_wpadmin_yes"><?php _e('Enable', 'wp-datepicker'); ?></label>
<input type="radio" name="wpdp_global_settings[wp_datepicker_wpadmin]" class="wpdp-useable" data-name="[wp_datepicker_wpadmin]" id="wp_datepicker_wpadmin_no" value="0" <?php checked(!$wp_datepicker_wpadmin); ?> /><label for="wp_datepicker_wpadmin_no"><?php _e('Disable', 'wp-datepicker'); ?></label>
<br /><small><?php _e( 'Will implement datepicker in wp-admin pages as well.', 'wp-datepicker' ); ?></small>
</div>


<div class="wp_datepicker_bootstrap_disabled">
<label for=""><?php _e( 'Disable Bootstrap for backend (wp-admin)?', 'wp-datepicker' ); ?></label>

<input type="radio" name="wpdp_global_settings[wp_datepicker_bootstrap_disabled]" class="wpdp-useable" data-name="[wp_datepicker_bootstrap_disabled]" id="wp_datepicker_bootstrap_disabled_yes" value="1" <?php checked($wp_datepicker_bootstrap_disabled); ?> /><label for="wp_datepicker_bootstrap_disabled_yes"><?php _e('Yes', 'wp-datepicker'); ?></label>
<input type="radio" name="wpdp_global_settings[wp_datepicker_bootstrap_disabled]" class="wpdp-useable" data-name="[wp_datepicker_bootstrap_disabled]" id="wp_datepicker_bootstrap_disabled_no" value="0" <?php checked(!$wp_datepicker_bootstrap_disabled); ?> /><label for="wp_datepicker_bootstrap_disabled_no"><?php _e('No', 'wp-datepicker'); ?></label>
<br /><small><?php _e( 'Will implement Bootstrap on Datepicker settings page.', 'wp-datepicker' ); ?></small>
</div>    


<p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php _e( 'Save Changes', 'wp-datepicker' ); ?>" /></p>

</form>
    
    </div>


<div class="nav-tab-content container-fluid hide" data-content="help">

        <div class="row mt-3">
        
        	<ul class="position-relative">
            	<li><a class="btn btn-sm btn-info" href="https://wordpress.org/support/plugin/wp-datepicker/" target="_blank"><?php _e('Open a Ticket on Support Forums', 'wp-datepicker'); ?></a></li>
                <li><a class="btn btn-sm btn-warning" href="http://demo.androidbubble.com/contact/" target="_blank"><?php _e('Contact Developer', 'wp-datepicker'); ?></a><i class="fas fa-headset"></i></li>
                <li><iframe width="560" height="315" src="https://www.youtube.com/embed/60hbh837wDU" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></li>
			</ul>                
        </div>

    </div>
    
    <div class="wpdp_fix_alert">
        <span class="dashicons dashicons-yes"></span>
        <?php _e('Settings Saved', 'wp-datepicker') ?>
    </div>
    
</div>


<style type="text/css">
.update-nag, #message{ display:none; }
</style>

<script type="text/javascript" language="javascript">

    jQuery(document).ready(function($) {


        <?php if(isset($_GET['t'])): ?>

        $('.nav-tab-wrapper .nav-tab:nth-child(<?php echo $_GET['t']+1; ?>)').click();

        <?php endif; ?>

    });

</script>