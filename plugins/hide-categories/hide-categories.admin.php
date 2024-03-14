<?php
function cp_multiSelect( $var, $arrValues, $arrSelected, $label, $description ) {

	if( $label != "" ) {
		echo "<label for=\"$var\">$label</label>";
	}
	
	echo "<select multiple=\"true\" size=\"7\" name=\"$var\" id=\"$var\" style=\"height:150px;\">\n";

	foreach( $arrValues as $arr ) {
		
		$extra = "";
		if( @in_array( $arr[ 0 ], $arrSelected ) ) { $extra = " selected=\"true\""; }
		echo "<option value=\"" . $arr[ 0 ] . "\"$extra>" . $arr[ 1 ] . "</option>\n";

	}
	
	echo "</select>";
	
	echo "<p style=\"font-size:0.9em; color:#999; margin:0;\">$description</p>";
	
}
function HideCategories_update() {
	$HideCategories_options = array(
						"excludeCategories"=>"",
					);
	foreach($HideCategories_options as $key => $value){
		if($_POST[$key]){
			$HideCategories_options[$key] = $_POST[$key];
		}
	}
	
	if(!get_option('HideCategories')){
		add_option('HideCategories',$HideCategories_options);
	}else{
		update_option('HideCategories',$HideCategories_options);
	}

}
function HideCategories_config(){
	
	if(isset($_POST['action'])){
		switch($_POST['action']){
			case "HideCategories_update":
				HideCategories_update();
				break;
		}
	}
	
	$options = get_option('HideCategories');
	

?>
<div class="wrap">
	<h2>Hide Categories Configuration</h2>
	<form name="dofollow" action="" method="post">
    <div id="poststuff">
	    <div class="postbox">
	<h3>config</h3>
    <div class="inside">
    <table class="form-table">
<tr valign="bottom">

<th scope="row">
<?php echo __('hide these categories')?>
</th>
<td>
<?php
$cp_categories = get_categories('hide_empty=0');
foreach ( $cp_categories as $b ) {
	$cp_cat[] = array( $b->cat_ID, $b->cat_name );
}
cp_multiSelect( "excludeCategories[]", $cp_cat, $options['excludeCategories'], "", "Hold down Ctrl button to select multiple categories." );?>
</td>
</tr>
</table>
    
    </div>
    </div>
    <p class="submit">
	
<input type="hidden" name="action" value="HideCategories_update" /> 
<input type="hidden" name="page_options" value="HideCategories-config" /> 
<input type="submit" class="button-primary" name="Submit" value="<?php echo __('Update Options')?> &raquo;" /> 
</p>
    </div>
    </div>
<?php

}
?>